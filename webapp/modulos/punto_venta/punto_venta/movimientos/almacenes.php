<!DOCTYPE html>
<html>
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
	<body>
		<?php 
		include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		
		?>
<h1 align="center" style="font-size:15pt; color:#1C1C1C">Movimiento de mercanc&iacute;a entre almacenes</h1>
<h3 align="center" style="font-size:12pt; color:#6E6E6E">A continuaci&oacute;n elija el Almac&eacute;n Origen, el producto y la cantidad que movera al Almac&eacute;n destino</h3>

<hr></hr>
<table>

	<tr ><label style="font-weight:bold">Almac&eacute;n Origen</label><br>
		<td>
	
	<select id="almacen"  onchange="consulta(1);" style=" cursor:pointer; margin-top:25px;   font-size:13px; ">
  <option selected>----- Elija un almac&eacute;n -----</option>
  		<?php 
  		$alma=$conection->query("select * from almacen");
 while($almacen=$alma->fetch_array(MYSQLI_ASSOC)){ 
  
   	?>
      <option value="<?php echo $almacen['idAlmacen']; ?>" ><?php echo $almacen['nombre']; ?></option>
      
<?php } ?></select>	
</td>
<td id="prorigen" >
	<div style="border: 1pt solid #6E6E6E;   text-align: center" >Filtrar productos  por:<br>


<label id="labedepa" style="">Departamento</label>
<select id="departamento" onchange="consulta(4);" style="cursor:pointer; margin-top:25px;   font-size:12px; " >
<?php $depa=$conection->query("select * from mrp_departamento");
			if($depa->num_rows>0){
						?>
           <option value="elije" selected>-- Elija un Departamento --</option>
		<?php	while($departamento=$depa->fetch_array(MYSQLI_ASSOC)){ ?>

			<option value="<?php echo $departamento['idDep']; ?>"><?php echo $departamento['nombre']; ?></option>
		
		<?php	}
			}else{ ?>
			<option selected>--No existen Departamentos--</option>
          <?php   }	?>	
</select>
<label id="labefami" >Familia</label>
					<select id="familia" onchange="consulta(5);" style="display:none cursor:pointer; margin-top:25px;   font-size:12px; " >
						<option value="elije" selected >-- Elija una Familia --</option>
					</select>
<label id="labeline" >L&iacute;nea</label>
					<select id="linea" onchange="consulta(6);" style="display:none cursor:pointer; margin-top:25px;   font-size:12px; " >
						<option selected value="elije">-- Elija una L&iacute;nea --</option>
					</select>
<br>
Producto<select id="producto"  onchange="consulta(2);" style=" cursor:pointer; margin-top:25px;   font-size:12px; ">
  <option value="elije" selected>----- Elija un producto -----</option>

		<?php 
  		$pro=$conection->query("select * from mrp_producto");
 while($producto=$pro->fetch_array(MYSQLI_ASSOC)){ 
  
   	?>
      <option value="<?php echo $producto['idProducto']; ?>" ><?php echo $producto['nombre']; ?></option>
      
<?php } ?></select>	
	</div>
	</td>
	<td  id="origen" style="display: none"><label id="cantorigen"></label>
				<input type="hidden" id="cantiorigen" />
				<label id="uniorigen"></label> en almac&eacute;n
	</td>
	</tr>
</table><br></br>
	<!-- almacen destino -->
<table>
	<tr>
				<div align="center">
					<td id="destino"  style="display: none"><label style="font-weight:bold">Almac&eacute;n Destino</label>
					<br>
					<select id="almadestino"  onchange="" style=" cursor:pointer; margin-top:25px;   font-size:12px; ">

					</select> Cantidad
						<input  id="cantdestino" size="5" type="text" onkeypress="return numbersonly(event)"/>

					<!-- lo de solo numeros solo pegar esto 				
 					<input  id="cantdestino" size="5" type="text" />

 -->
					<label id="unidest"></label>
					<input type="hidden" id="unidad" />
					<input type="button" value="Mover" id="mover" onclick="consulta(3);" style=" cursor:pointer; margin-top:25px;   font-size:12px; "/>
				</div>
			</tr>
	</table>
	<br>
<input type="button" value="Regresar" id="mover" onclick="almacenes();" style=" cursor:pointer; margin-top:25px;  font-size:12px; "/>


	<!-- fin almacen destino -->
	</body>
<script type="text/javascript">
	function consulta(val){
	switch (val) {
		case 1:
		
		$('#producto ').val("elije");
		$('#prorigen').show();
		$('#producto').show();
		
		
		
		/////////////////
		
	//$('#labefami').val("elije");
	$('#familia').val("elije");
		//$('#labeline').hide();
		$('#linea').val("elije");
		$('#departamento').val("elije");
		
		
		///////////////
		$('#origen').hide();
		$('#destino').hide();
		$('#cantdestino').val("");
		$('#cantdestino').empty();
		$('#cantorigen').empty();
   		$('#uniorigen').empty();
		//showdire('#prorigen','#producto'); 
		
		break;
		
			case 2:
			var alma=jQuery('#almacen').val();
			if(alma=="----- Elija un almacen -----"){
				alert("Elija un almacen primero");
			}else{
		var pro=jQuery('#producto').val();
		
		$('#destino').hide();	//para si no hay unidades y previamente ubo no deje el td
		//$('#cantorigen').show(); 
		$('#cantorigen').empty();
   		$('#uniorigen').empty();
		
		
	$.post("consultas.php",{opc:1,p:pro,a:alma},
	function(respuesta) {
		$('#origen').show(); 
		
		
		var re=respuesta.split(",");
              
   		$('#cantorigen').html(re[0]);
   		$('#cantiorigen').val(re[0]);
   		$('#uniorigen').html(re[1]);
   		if(re[0]!=0){//para si no hay unidaddes
   		$('#destino').show();	
   		$('#unidest').html(re[1]);
   		$('#unidad').val(re[2]);
   		} 
   		
   
   $.post("consultas.php",{opc:2,a:alma},
	function(respues) {
		$('#almadestino').html(respues); 
		
		
   	});	
   
   });
   }
  break;
  case 3:
  var almacen=jQuery('#almacen').val();
	var almadestino=jQuery('#almadestino').val();
	var producto=jQuery('#producto').val();
	var unidad=jQuery('#unidad').val();
	var cantdestin=jQuery('#cantdestino').val();
	var cantidadorigen=parseInt(jQuery('#cantiorigen').val());
	var cantdestino=parseFloat(cantdestin);


  if (almadestino=="-- Elija un almacen --"){
  	alert("Elija el Almacen Destino");
  	
  }
  else if(cantdestin=="" || cantdestin==0){
  	alert("Debe introducir una cantidad para mover");
  }
  
  else if(cantdestino>cantidadorigen){
  	alert("No puede mover mas de la cantidad existente");
  }
  else if (almadestino!="-- Elija un almacen --" && cantdestino!="" && cantdestino<=cantidadorigen)
  {
  	$.post("consultas.php",{
  		opc:3,
  		almaorigen:almacen,
  		almadestino:almadestino,
  		producto:producto,
  		unidad:unidad,
  		cantidad:cantdestino},
	function(respuest) {
		if(respuest=="ok"){
			alert("Movimiento Realizado");
			window.location="listadomovimientos.php";
		}else{
			alert("Fallo en movimiento");
		}
	});	
  }
      // alert("origen"+almacen);
      // alert("destino"+almadestino);
       // alert("producto"+producto);
      // alert("unidad"+unidad);
      // alert("cantdestino"+cantdestino);
  break;
  case 4:
  $('#origen').hide();
	$('#destino').hide();
	$('#cantdestino').val("");
	$('#cantdestino').empty();
	$('#cantorigen').empty();
	$('#uniorigen').empty();
	$('#producto').empty();
	$('#producto ').val("elije");
	//$('#familia').hide();
	//$('#labeline').hide();
	//$('#linea').hide();

	var depa = jQuery('#departamento').val();
	$('#labefami').show();
	$('#familia').show();

	$.post("consultas.php", {
		opc : 4,
		depa : depa
	}, function(respues) {
		$('#familia').html(respues);
		$.post("consultas.php", {
			opc : 8,
			depa : depa
		}, function(respuest) {
			//alert(respuest);
			$('#producto').html(respuest);

		});
	});

	break;
	case 5:
	//$('#producto').empty();
	$('#producto ').html("<option selected>----- Elija un producto -----</option>");
	$('#origen').hide();
	$('#destino').hide();
	$('#cantdestino').val("");
	$('#cantdestino').empty();
	$('#cantorigen').empty();
	$('#uniorigen').empty();
	$('#labeline').show();
	$('#linea'
		).show();

		var depar = jQuery('#departamento').val();
	var fami = jQuery('#familia').val();
	$.post("consultas.php", {
	opc : 5,
	fami : fami
	}, function(respues) {

	$('#linea').html(respues);
	$.post("consultas.php", {
	opc : 7,
	familia : fami
	}, function(respuest) {
	//alert(respuest);
	$('#producto'
		).html(respuest);

		});
		});

		break;
		case 6:
		var depar = jQuery('#departamento').val();
		var fami = jQuery('#familia').val();
		var linea = jQuery('#linea').val();
		$.post("consultas.php", {
			opc : 6,
			fami : fami,
			depa : depar,
			linea : linea
		}, function(respues) {

			$('#producto').html(respues);

		});
		break;
		};
		};

 // $('input:checkbox').click(function(){
 	// //$('input:checkbox').live('click', function(){
 	// var nombre=($(this).val());
//         
 	// alert(nombre);
 // });

         	       
function numbersonly(e)
{// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57

	var tecla=e.charCode? e.charCode : e.keyCode;
	if ((tecla!=8 && tecla!=13 && tecla!=9) && (tecla<48 || tecla>57) && (tecla!=46) )
	{ 
		return false; 
	}
}
</script>
<script type="text/javascript">
function almacenes(){
	window.location="listadomovimientos.php";
}
</script>
<?php $conection->close(); ?>
	</html>