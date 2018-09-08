<!DOCTYPE html>
<html>
<meta charset="UTF-8">
    <!--<LINK href="../../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" / -->
        <?php include('../../../netwarelog/design/css.php');?>
        <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- Slect con buscador -->
<script src="select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="select2/select2.css" />
<style type="text/css">
  .btnMenu{
    border-radius: 0; 
    width: 100%;
    margin-bottom: 0.3em;
    margin-top: 0.3em;
  }
  .row
  {
    margin-top: 0.5em !important;
  }
  h4, h3{
    background-color: #eee;
    padding: 0.4em;
  }
  .nmwatitles, [id="title"] {
    padding: 8px 0 3px !important;
    background-color: unset !important;
  }
  .select2-container{
    width: 100% !important;
  }
  .select2-container .select2-choice{
    background-image: unset !important;
    height: 31px !important;
  }
</style>

	<body>
		<?php 
		include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		
		?>

    <div class="container">
      <h3 class="nmwatitles text-center">Movimiento de mercanc&iacute;a entre almacenes</h3>
      <h5>A continuaci&oacute;n elija el Almac&eacute;n Origen, el producto y la cantidad que movera al Almac&eacute;n destino</h5>
      <h4>Almac&eacute;n Origen</h4>
      <section id='primera'>
        <div class="row">
          <div class="col-md-6">
            <select id="almacen"  onchange="consulta(1);" >
              <option  value="0">----- Elija un almac&eacute;n -----</option>
              <?php 
              $alma=$conection->query("select * from almacen");
              while($almacen=$alma->fetch_array(MYSQLI_ASSOC)){ 
              ?>
              <option value="<?php echo $almacen['idAlmacen']; ?>" ><?php echo $almacen['nombre']; ?></option>
              <?php } 
              ?>
            </select> 
          </div>
        </div>
        <h4>Filtrar productos por:</h4>
        <div class="row" id="prorigen">
          <div class="col-md-3">
            <label id="labedepa" style="">Departamento</label>
            <select id="departamento" onchange="consulta(4);" >
              <?php $depa=$conection->query("select * from mrp_departamento");
                        if($depa->num_rows>0){
                                    ?>
                       <option value="elije" selected>-- Elija un Departamento --</option>
                    <?php   while($departamento=$depa->fetch_array(MYSQLI_ASSOC)){ ?>

                        <option value="<?php echo $departamento['idDep']; ?>"><?php echo $departamento['nombre']; ?></option>
                    
                    <?php   }
                        }else{ ?>
                        <option selected>--No existen Departamentos--</option>
                      <?php   } ?>  
            </select>
          </div>
          <div class="col-md-3">
            <label id="labefami" >Familia</label>
            <select id="familia" onchange="consulta(5);">
                <option value="elije" selected >-- Elija una Familia --</option>
            </select>
          </div>
          <div class="col-md-3">
            <label id="labeline" >L&iacute;nea</label>
            <select id="linea" onchange="consulta(6);">
                <option selected value="elije">-- Elija una L&iacute;nea --</option>
            </select>
          </div>
          <div class="col-md-3" id="origen" style="display: none">
            <label id="cantorigen"></label>
            <input type="hidden" id="cantiorigen" />
            <label id="uniorigen"></label> en almac&eacute;n
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label>Producto:</label>
            <select id="producto"  onchange="consulta(2);" >
              <option value="elije" selected>----- Elija producto -----</option>

                    <?php 
                    $pro=$conection->query("select * from mrp_producto where estatus=1");
              while($producto=$pro->fetch_array(MYSQLI_ASSOC)){ 
              
                ?>
                  <option value="<?php echo $producto['idProducto']; ?>" ><?php echo $producto['codigo'].' / '.$producto['nombre']; ?></option>
                  
              <?php } ?>
            </select> 
          </div>
        </div>
      </section>
      <section id="destinodiv" style="display:none;">
        <section id="segunda">
          <h4>Almac&eacute;n Destino</h4>
          <div class="row">
            <div class="col-md-6">
              <select id="almadestino"  onchange="" >
              </select>
            </div>
          </div>
          <h4>Movimientos</h4>
          <div class="row">
            <div class="col-md-4">
              <label>Cantidad:</label>
              <input  id="cantdestino" size="5" type="text" onkeypress="return numbersonly(event)" class="form-control"/>
              <label id="unidest"></label>
              <input type="hidden" id="unidad" />
            </div>
            <div class="col-md-2">
              <button type="button" id="mover" onclick="mover();" class="btn btn-primary btnMenu">Mover</button>
            </div>
          </div>
        </section>
        <section>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table data-toggle="table" data-height="299" data-show-columns="true" class="table" id="tablita">
                  <thead>
                      <tr>
                          <th data-field="id">Almacen Origen</th>
                          <th data-field="id">Almacen Destino</th>
                          <th data-field="name">Producto</th>
                          <th data-field="name">Cantidad</th>
                          <th data-field="price">Unidad</th>
                          <th data-field="price">Eliminar</th>
                      </tr>
                  </thead> 
                </table>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 col-md-offset-10">
              <button type="button" id="guardar" onclick="guardar();" class="btn btn-primary btnMenu">Guardar</button>
            </div>
          </div>
        </section>
      </section>
      <div class="row">
        <div class="col-md-2">
          <button type="button" id="mover" onclick="almacenes();" class="btn btn-danger btnMenu">Regresar</button>
        </div>
      </div>
    </div>

	<!-- fin almacen destino -->
	</body>
<script type="text/javascript">
    $(document).ready(function() {

            $("#producto").select2({
                width : "250px"
            });

            $("#almacen").select2({
                width : "250px"
            });

            $("#departamento").select2({
                width : "1150px"
            });
            $("#familia").select2({
                width : "250px"
            });
            $("#linea").select2({
                width : "250px"
            });
            $('#almadestino').select2({
                width : "250px"
            });
    });



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
        $('#destinodiv').show('slow');	
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

        function mover(){
         
                alori = $("#almacen").val();
                aldes = $("#almadestino").val();
                cantidad = $("#cantdestino").val();
                unidad = $("#unidad").val();
                idProducto = $("#producto").val()
                cantiorigen = $("#cantiorigen").val();
                //alert(cantidad+'>'+cantiorigen);
                cantiorigen = parseFloat(cantiorigen);
                cantidad = parseFloat(cantidad);
                
                 if(alori=="" || alori==0){
                    alert('Selecciona un almacen.');
                    return;
                 }
                 if(aldes=="-- Elija un almacen --" || aldes==0){
                    alert('Selecciona un almacen destino.');
                    return;
                 }
                 if(cantidad<0 || cantidad==''){
                    alert('Agrega una cantidad a mover, tiene que ser mayo a 0.');
                    return;
                 }
                 if(idProducto==0 || idProducto==''){
                    alert('Selecciona un producto a mover.');
                    return;
                 }
                 if(cantidad > cantiorigen){
                    alert('No puedes mover una cantdad mayor a la que tienes en tu almacen de Origen.');
                    return;
                 }

                $.ajax({
                    url: 'consultas.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {opc: '11',origen:alori,destino:aldes,unidad:unidad,idProducto:idProducto},
                })
                .done(function(data) {
                  var count = $('#tablita tr').length;
  
                    console.log(data);
                    $('#guardar').show('slow');
                    $('#tablitaDiv').show('slow');
                     $.each(data, function(index, value) {
                        $('#tablita tr:last').after('<tr id='+value.idProducto+'>'+
                            '<td data-field="id">'+value.origen+'<input type="hidden" id="almorigen" value="'+alori+'"><input type="hidden" id="cantalmorigen" value="'+cantiorigen+'"></td>'+
                            '<td data-field="id">'+value.destino+'<input type="hidden" id="almdestino" value="'+aldes+'"></td>'+
                            '<td data-field="name">'+value.nombre+'<input type="hidden" id="idProducto" value="'+value.idProducto+'"></td>'+
                            '<td data-field="name">'+cantidad+'<input type="hidden" id="canti" value="'+cantidad+'"></td>'+
                            '<td data-field="price">'+value.unidad+'<input type="hidden" id="uni" value="'+unidad+'"></td>'+
                            '<td data-field="price"><button class="btn btn-danger btn-xs" type="button" onclick="elimPro('+value.idProducto+')">Eliminar</button></td>'+
                        '</tr>');
                     });
                    $('#cantorigen').text('');
                    $('#cantdestino').val('');        
                    $('#uniorigen').text('');
                    $('#producto > option[value="elije"]').prop('selected',true); 
                    $("#producto").select2({
                      width : "250px"
                    });

                    $('#almadestino > option[value="0"]').prop('selected',true); 
                    $("#almadestino").select2({
                      width : "250px"
                    });

                    $('#almacen > option[value="0"]').prop('selected',true); 
                    $("#almacen").select2({
                      width : "250px"
                    });                    
                    

       
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
        }

        function elimPro(id){
            $('#'+id).hide('slow');
            $('#'+id).remove();
        }
        function guardar(){

             var cantiorigen = $('#cantiorigen').val();
                var array = {};
                var contador = 0;
                $("#tablita tr").each(function (index) 
                {   //console.log($("#tablita input:hidden"));
                    x=$("input:hidden", this);
                    //console.log(x);
                    if(x.length>0){
                        item = {};
                        for (var i = x.length - 1; i >= 0; i--) {
                            item[$(x[i]).attr('id')]=$(x[i]).attr('value');
                        };
                        array['f' + index]=item;
                    }
                });

                //console.log(array);
                array = JSON.stringify(array);
                //console.log(array);
                $.ajax({
                        url: 'consultas.php',
                        type: 'POST',
                        data: {opc: '12', x:array},
                    })
                    .done(function(respuest) {
                        console.log("success");
                            if(respuest=="ok"){
                                alert("Movimiento Realizado");
                                window.location="listadomovimientos.php";
                            }else{
                                alert("Fallo en movimiento");
                            }                    
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    }); 
                        

               // console.log();
        }




</script>
<script type="text/javascript">
function almacenes(){
	window.location="listadomovimientos.php";
}
</script>
<?php $conection->close(); ?>
	</html>