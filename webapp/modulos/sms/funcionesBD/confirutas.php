<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../js/ruta.js"></script>
<LINK href="../../../netwarelog/catalog/css/estilo.css" title=2"estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
<?php
//ini_set('display_errors',1);
  include("../../../netwarelog/webconfig.php");
include_once('conexion.php');
    $consult = new Consult;
    //$conection = $consult -> conection();


$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	
  $id=$_REQUEST['a'];
 //echo "select * from sms_oferta_cliente where contesto=1 and estatus=0 /* and idOrdenCompra is not null */ and idOferta=".$id;
 //aki aser consulta para pasar el id a la oferta
 $verificagene=$conection->query("select * from sms_oferta_cliente where contesto=1 and estatus=0 /* and idOrdenCompra is not null */ and idOferta=".$id);
 if($verificagene->num_rows>0){
	 
$verifica=$consult->clientessql($conection,$id);
if($verifica->num_rows>0){
	
 
 
   $ofer=$consult->smsOferta($conection, $id);
		 	if($ofertas=$ofer->fetch_array(MYSQLI_ASSOC)){
		 		
		 		$produ=$consult->mrproducto($conection, $ofertas['idProducto']);
			$cantidadofert=$ofertas['cantidad'];
				
				  if($produc=$produ->fetch_array(MYSQLI_ASSOC)){
				  	$producto=$produc['nombre'];
					  
					$uni=$consult->mrpunidad($conection, $ofertas['idUnidad']);
					  if($unid=$uni->fetch_array(MYSQLI_ASSOC)){
					  	   $unidad=$unid['compuesto'];
					
     $precio=$ofertas['precio'];
					  }}}	 
						  
   
   //$ofert=$consult->smsOfertaClientconf($conection, $id);//consulta oferta cliente 
  
   ?>
  
   	
   		  <!--///////////////// -->
   		  <input type="hidden" id="idoferta" value="<?php echo $id; ?>" />
   		   <table border="2">
   		   	<tr><td>
   		   
   		   
  <table style="font-size: 12px;" cellpadding="5"  cellspacing="0" border="0" ><caption><strong>Oferta:<?php 
 echo  $cantidadofert." ".$unidad." de ".$producto." a solo $".$precio; ?> </strong>
  	<br>Seleccione los clientes que desea agregar a la Ruta</caption>
  <th align="left"><strong>Cliente</strong></th>
  <th><strong>Cant. ofertas</strong></th>
  <th align="center"><strong>Direcci&oacute;n</strong></th>
  <td align="center"><strong>C. P.</strong></td>
   <!-- <th><strong>Rubro</strong></th>
    <th><strong>Giro</strong></th></tr>-->
  <tr><td>
  <?php 

 //if($oferta=$verifica->fetch_array(MYSQLI_ASSOC)){
  	// $cantidadclient= $oferta['cantidad'];
	
  //$cli=$consult->clientes($conection, $oferta['idCliente']);
  if($clientes=$verifica->fetch_array(MYSQLI_ASSOC)){
  	$cantidadclient= $clientes['cantidad'];
 	$cp=$clientes['cp']; 
	  $client= utf8_encode($clientes['nombre']);
 	$dire=$consult->estado($conection,$clientes['idEstado']);
 	 if($estado=$dire->fetch_array(MYSQLI_ASSOC)){
 	 	$muni=$consult->municipio($conection,$clientes['idMunicipio']);
 	 if($municipio=$muni->fetch_array(MYSQLI_ASSOC)){
 	 	//////////////////giro
 	 //	$gir=$consult->giro($conection, $clientes['idGiro']);
 	 //if($giro=$gir->fetch_array(MYSQLI_ASSOC)){
//////////////////rubro
 	// 	$rub=$consult->rubro($conection, $clientes['idRubro']);
 	// if($rubro=$rub->fetch_array(MYSQLI_ASSOC)){
 	?>
 
  	
    <input  type="checkbox" id="cliente" value="<?php echo $cantidadclient."/".$clientes['id'];	?>"><?php echo utf8_encode($clientes['nombre']);?><br>	
   		
   		</td>
   		
   		<td align="center" bgColor=#91C313>
   		<?php echo $cantidadclient;	?>
   		</td>
   		<td align="center" style="border-length: "><?php echo utf8_encode($estado['estado'].",".$municipio['municipio'].", <br>"."Colonia: ".$clientes['colonia'].",<br> Calle: ".$clientes['direccion']); ?></td>
   		<td align="center" bgColor=#91C313><?php echo $cp;?></td>
   	    <td><?php echo $rubro['nombre'];?></td>
   		<td><?php echo $giro['nombre'];?></td>
   		</tr>
   		
 <?php 	//}//giro
 // }//rubro
	 }//municipio
	  }//estado
  }//clientes 
 // }//oferta cliente 1
  //}

  while($clientes=$verifica->fetch_array(MYSQLI_ASSOC)){ ?>
  	<tr><td>
  		<?php
  	$cantidadclient= $clientes['cantidad'];

  	$dire=$consult->estado($conection,$clientes['idEstado']);
 	 if($estado=$dire->fetch_array(MYSQLI_ASSOC)){
 	 	$muni=$consult->municipio($conection,$clientes['idMunicipio']);
 	 if($municipio=$muni->fetch_array(MYSQLI_ASSOC)){

   	if($clientes['cp']!=$cp){
    $cp=$clientes['cp'];
		$hr='<hr>';
   	}else{ $cp=$clientes['cp']; 
	$hr='';
   	  }//else 
   	  echo $hr;
  ?>
 
    <input type="checkbox" id="cliente" value="<?php echo $cantidadclient."/".$clientes['id'];	?>"><?php echo utf8_encode($clientes['nombre']);?><br>	
   		
   		</td >
   		<td align="center" bgColor=#91C313>
   		<?php echo $cantidadclient;	?>
   		</td>
   	
   		<td align="center" ><?php echo utf8_encode($estado['estado'].",".$municipio['municipio'].", <br>"."Colonia: ".$clientes['colonia'].",<br> Calle: ".$clientes['direccion']); ?></td>
   		<td align="center" bgColor=#91C313><?php echo $cp;?></td>
   		<td><?php //echo $rubro['nombre'];?></td>
   		<td><?php //echo $giro['nombre'];?></td>
  
    <?php    
  
 // 	}//giro
// }//rubro
 
	 }//municipio
	  }//estado 
	  
	   
 //}//clientes 
  
  }//oferta cliente 2
  
  ?>
 
  <tr>
  	
  	<td ><hr><strong> Total:<span id="total"> 0</span><?php echo " ".$unidad; ?></strong></td>
  		<td></td><input type="hidden" id="tota"/>
  		<td><strong>Asigne un nombre a la ruta</strong>	<img src="../images/preloader.gif"  id="carga" class="overbox" style="display: none;" /></td>
  		
  </tr>

 <tr><td>
 	<select id="transporte"  style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;">
      <option selected>--- Eliga una Unidad ---</option>
  		<?php 
  		$trans=$consult->transportes($conection);
      
   while($transporte=$trans->fetch_array(MYSQLI_ASSOC)){ 
   	//$trans=$transporte['id'];
   	?>
      <option id="<?php echo $transporte['id']; ?>"><?php echo $transporte['id'].", ".$transporte['transporte']; ?></option>
      
<?php } ?></select></td>
  		
  		<td></td>
  		<td><input type="text" id="nombreruta"  placeholder="Nombre Ruta"/>
  			<input type="button"  id="guaruta" value="Guardar" style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/>
  		</td>
  		<td><input type="button"  value="Regresar"  onclick='regreso();' style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/></td>
  	</tr>
  
</table>
	
	</td></tr>
   		   </table>
   <!-- /////////////////////// -->
   <?php }//del if de contesto
else{  ?>
   	<script type="text/javascript">
   		alert("No hay clientes nuevos o aun no han sido aprobados"),window.location="../views/configrutas/listado_ofertas.php";
   	</script>
<?php } 
}//if general
else{//else general?>
	<script type="text/javascript">
   		alert("No hay clientes nuevos o aun no han sido aprobados"),window.location="../views/configrutas/listado_ofertas.php";
   	</script>
<?php
}
?>
   
   
   
 
   <script type="text/javascript">var nombre=new Array();
   $(document).ready(function(){
   	
   		$('input:checkbox').click(function(){
   			
   			var subtotal = 0;
   			var total=0;
   			var num=($(this).val());
   			var elem = num.split('/');

   				 
   			//alert(nombre);
   			var idx = nombre.indexOf(elem[1]); // Localizamos el indice del elemento en array
if(idx!=-1){nombre.splice(idx, 1); // Lo borramos definitivamente
   		}else{ nombre.push(elem[1]);}	//lo agregamos	 
   		
   			$('input:checkbox:checked').each(function(){
   	       
   			
   				subtotal += parseInt($(this).val(),10);
   				total=subtotal*<?php echo $cantidadofert; ?>;
   				
   			});
   			$('#total').text(total);
   			$('#tota').val(total);
   			//alert(nombre);
   			
   			
   		});
   });
  
  
   </script>
  <script>
  	
	  
  	$(document).ready(function(){
   		$('#guaruta').click(function(){
   		
   var namerut = jQuery("#nombreruta").val();          
   var idtrans = jQuery("#transporte").val();
   var total = jQuery("#tota").val();
   var idofert = jQuery("#idoferta").val();
   
 var client="";
 //alert(idtrans);
   var valida =  validaDatos(namerut,idtrans,nombre);   
  var x=0;
     if(valida==true){
     	    for (x=1;x<nombre.length;x++){
   client+=nombre[x-1]+",";
    }
    client+=nombre[x-1];
     	//alert(client);
     	showloader();
     	  $.post("guardaruta.php",{namerut:namerut,idtrans:idtrans,total:total,client:client,idofert:idofert},
                  function(respues) {
                  		//respues = parseInt(respues,10);
                  	//alert(respues);
                  	if(respues=="0"){
                  		hideRegis(); 
                  		alert("El nombre de ruta ya a sido asigando");
                  	}
                  	if(respues=="2"){
                  		hideRegis();
                  		alert("Ruta Agregada"),window.location="../views/configrutas/listado_ofertas.php";
                  	}
                  	if(respues=="1"){
                  		hideRegis(); 
                  	 alert("Error..");
                  	 }
//                   	
                 //	respuest = parseInt(respuest,10);
                 
   		        });
     }
       else{  hideRegis();
       	alert(valida);}   
        
   		});
   });
   
     
  </script>