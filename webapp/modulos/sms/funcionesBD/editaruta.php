<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../js/ruta.js"></script>
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

<!-- Codigo de Google Maps -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDxA8VPevjm0deg6jIimOQ-SnOQXR_W7mA&sensor=false"></script>

 <script type="text/javascript">
   	
  	var map;
  	var marker;
  	
  	var origin;
  	var destiny;
  	
  	var geocoder;
  	
  	var direcciones = new Array();
	var mark = new Array();
	var i = 0;
  	
  	////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  	function initialize() 
  	{
  		geocoder = new google.maps.Geocoder();
  		
    	var mapOptions = {
      		zoom: 10,
      		mapTypeId: google.maps.MapTypeId.ROADMAP
    	};
    	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
  		
  		codeAddress();
  	}
  	
	function codeAddress() 
	{
		$.ajax({
			async:false,
			url:'consult.php',
			type: 'POST',
			data: {idRuta: <?php echo $_REQUEST['idr']; ?>}, 
			success: function(callback)
			{	
	     		direcciones = callback.split("///$$$<<<");
			}
		});
		
		cuentaMarkers();
	}	
	
	function cuentaMarkers()
	{
		if(i<(direcciones.length-1))
		{
			setMarker();
		}
	}
	
	function setMarker()
	{
		geocoder.geocode( {'address': direcciones[i]}, function(results, status) 
		{
			if (status == google.maps.GeocoderStatus.OK) 
			{
				map.setCenter(results[0].geometry.location);
				mark[i] = new google.maps.Marker(
				{
					map: map, 
					position: results[0].geometry.location
      			});
      			// alert(i + " se imprimio "+ results[0].geometry.location.lat() + ", " + results[0].geometry.location.lng() + ", " + direcciones[i]);
      			i++;
      			cuentaMarkers();
			} 
			else
			{
				alert("Geocode ("+i+") no funciono por: " + status);
    		}
  		});
	}
    
    </script>

<!-- Termina codigo de Google maps -->


<?php
//ini_set('display_errors', 1);
include_once('conexion.php');
    $consult = new Consult;
    //$conection = $consult -> conection();
	include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	 $idruta=$_REQUEST['idr'];//id de ruta_oferta
	 	
/////////////AKI PARA SACAR EL ID OFERTA////////////////////////////////////
$verificar=$conection->query("select * from sms_ruta_oferta where id=".$idruta." and status=2");
	if($ruta=$verificar->fetch_array(MYSQLI_ASSOC)){
		$totalruta=$ruta['cantidadtotal'];
		$global=$consult->rutaofertaclient($conection, $idruta);//consulta sms_ruta_oferta_cliente
	 if($global->num_rows>0){
		
$trt=$consult->transportesedit($conection, $ruta['idTransporte']);
   if($transport=$trt->fetch_array(MYSQLI_ASSOC)){ $trtedita=$transport['id'].", ".$transport['transporte'];
   $idtranselec=$transport['id']; }
	$clientrut=$consult->rutaofertaclient($conection, $ruta['id']);//consulta sms_ruta_oferta_cliente
		if($idrutaofer=$clientrut->fetch_array(MYSQLI_ASSOC)){
		
		$ofertcli=$conection->query("select * from sms_oferta_cliente where id=".$idrutaofer['idOfertacliente']);
			if($ofer=$ofertcli->fetch_array(MYSQLI_ASSOC)){
				 $idoferta=$ofer['idOferta'];
///////////////////////////////////////////////////////////	 copy de confi para armar la tabla
				 $ofer=$consult->smsOferta($conection, $idoferta);
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
////////////////////////////////////////////////////////////////		  
				 
				
			}//if de sms_oferta_cliente
		}//while de rutaofertaclient
	?>
	<input type="hidden" id="idoferta" value="<?php echo $idoferta; ?>" />
	<input type="hidden" id="idruta" value="<?php echo $idruta; ?>" />
	<input type="hidden" id="totalruta" value="<?php echo $totalruta; ?>" />
	
			
   		   <table border="2"  class="busqueda" style="font-size: 12px;color:red; "><caption align="center"><strong>Nombre de RUTA: <?php echo $ruta['nombre'];?>
		<br>Oferta:<?php 
 echo  $cantidadofert." ".$unidad." de ".$producto." a solo $".$precio; ?> </strong></caption>
   		   <tr><td>
Seleccione los Clientes que desea ELIMINAR de la Ruta.
   		   
  <table style="font-size: 12px;"  class="busqueda" cellpadding="3"  cellspacing="0" border="0" >
  <th align="left" width="455"><strong>Clientes Asignados a la ruta</strong></th>
  <th width="100"><strong>Cant. Ofertas</strong></th>
  <th align="center" ><strong>Direcci&oacute;n</strong></th>
  <td align="center"><strong>C. P.</strong></td>
 <!--   <th><strong>Rubro</strong></th>
    <th><strong>Giro</strong></th></tr>-->
  

	<?php
	
//////////////////////////////AKI PARA EMPEZAR LA TABLA///////////////////////////////////////////////////////
	$clientrut=$consult->rutaofertaclient($conection, $ruta['id']);//consulta sms_ruta_oferta_cliente
		while($idrutaofer=$clientrut->fetch_array(MYSQLI_ASSOC)){
		?><tr><td><?php
		$ofertcli=$conection->query("select * from sms_oferta_cliente where id=".$idrutaofer['idOfertacliente']);
			if($ofer=$ofertcli->fetch_array(MYSQLI_ASSOC)){
				 $idcliente=$ofer['idCliente'];
				 	$cantidadclient= $ofer['cantidad'];
///////////////////////////////////////////////////////////////////////////copy
			 $cli=$consult->clientes($conection, $idcliente);
  if($clientes=$cli->fetch_array(MYSQLI_ASSOC)){
  	$dire=$consult->estado($conection,$clientes['idEstado']);
 	 if($estado=$dire->fetch_array(MYSQLI_ASSOC)){
 	 	$muni=$consult->municipio($conection,$clientes['idMunicipio']);
 	 if($municipio=$muni->fetch_array(MYSQLI_ASSOC)){
 	 	//////////////////giro
 	 //	$gir=$consult->giro($conection, $clientes['idGiro']);
 	// if($giro=$gir->fetch_array(MYSQLI_ASSOC)){
//////////////////rubro
 	 	//$rub=$consult->rubro($conection, $clientes['idRubro']);
 //	 if($rubro=$rub->fetch_array(MYSQLI_ASSOC)){
   	// echo $cp;
   	$cp= $clientes['cp'];
   	
  ?>
 
    <input type="checkbox" id="cliente" data-cliente="resta" value="<?php echo $cantidadclient."/".$clientes['id'];	?>"> <?php echo $clientes['nombre'];?><br>	
   		
   		</td >
   		<td align="center" bgColor=#91C313>
   		<?php echo $cantidadclient;	?>
   		</td>
   	
   		<td align="center" ><?php echo utf8_encode($estado['estado'].",".$municipio['municipio'].", <br> Colonia: ".$clientes['colonia'].",<br> Calle: ".$clientes['direccion']); ?></td>
   		<td align="center" bgColor=#91C313><?php echo $cp;?></td>
   		<td><?php //echo $rubro['nombre'];?></td>
   		<td><?php //echo $giro['nombre'];?></td>
   		
    <?php    
  
  //	}//giro
 //}//rubro
 
	 }//municipio
	  }//estado 
	  
	   
 }//clientes 		
					
//////////////////////////////////////////////////////		
					
			}//if de sms_oferta_cliente
		
		}//while de rutaofertaclient
		?>
		<tr>
   		<td width="455"></td>
   		<td></td>
   		<td width="347"></td>
   		<td width="70"></td>
  </tr>
		<?php
///////////////////////AKI CLIENTES AGREGAR//////////////////////////////////////////	
//$verifica=$consult->smsOfertaContesto($conection,$idoferta);
 $verifica=$consult->clientessql($conection, $idoferta);
if($verifica->num_rows>0){
	?>	
		</td></tr></table>
		<hr>Seleccione los clientes que desea AGREGAR a la Ruta.</hr>
		<table  style="font-size: 12px;" cellpadding="3"  cellspacing="0">
				<tr><td>

   		   
  <table style="font-size: 12px;" cellpadding="5"  cellspacing="0" border="0" >
  <th  width="449" align="left"><strong>Clientes Nuevos</strong></th>
  <th width="0"><strong></strong></th>
  <th width="0" ><strong></strong></th>
  <td  width="0"><strong></strong></td>
    <th><strong></strong></th>
    <th><strong></strong></th></tr>
    <tr><td>
		<?php
///////////////////////////////////////////////	
//$verifica=$consult->smsOfertaContesto($conection,$idoferta);

		//if($oferta=$verifica->fetch_array(MYSQLI_ASSOC)){
  	
	
  $cli=$consult->clientessql($conection, $idoferta);
  if($clientes=$cli->fetch_array(MYSQLI_ASSOC)){
  	 $cantidadclient= $clientes['cantidad'];
 	$cp=$clientes['cp']; 
	  $client= $clientes['nombre'];
 	$dire=$consult->estado($conection,$clientes['idEstado']);
 	 if($estado=$dire->fetch_array(MYSQLI_ASSOC)){
 	 	$muni=$consult->municipio($conection,$clientes['idMunicipio']);
 	 if($municipio=$muni->fetch_array(MYSQLI_ASSOC)){
 	 	//////////////////giro
 //	 	$gir=$consult->giro($conection, $clientes['idGiro']);
 //	 if($giro=$gir->fetch_array(MYSQLI_ASSOC)){
//////////////////rubro
 	// 	$rub=$consult->rubro($conection, $clientes['idRubro']);
 	// if($rubro=$rub->fetch_array(MYSQLI_ASSOC)){
 	?>
 
  	
    <input  type="checkbox" id="cliente" data-cliente="suma" value="<?php echo $cantidadclient."/".$clientes['id'];	?>"><?php echo $clientes['nombre'];?><br>	
   		
   		</td>
   		
   		<td width="95" align="center" bgColor=#91C313>
   		<?php echo $cantidadclient;	?>
   		</td>
   		<td align="center" width="345"><?php echo utf8_encode($estado['estado'].",".$municipio['municipio'].", <br>"."Colonia: ".$clientes['colonia'].",<br> Calle: ".$clientes['direccion']); ?></td>
   		<td align="center" bgColor=#91C313><?php echo $cp;?></td>
   	   <!-- <td><?php //echo utf8_encode($rubro['nombre']);?></td>
   		<td><?php //echo utf8_encode($giro['nombre']);?></td> -->
   		</tr>
   		
 <?php //	}//giro
// }//rubro
	 }//municipio
	  }//estado
  }//clientes 
 // }//oferta cliente 1
  //}

  //while($oferta=$cli->fetch_array(MYSQLI_ASSOC)){ 
  	//<tr><td>
 
  
   //$cli=$consult->clientessql($conection, $idoferta);
  while($clientes=$cli->fetch_array(MYSQLI_ASSOC)){?>  <tr><td> <?php
  		$cantidadclient= $clientes['cantidad'];
  	$dire=$consult->estado($conection,$clientes['idEstado']);
 	 if($estado=$dire->fetch_array(MYSQLI_ASSOC)){
 	 	$muni=$consult->municipio($conection,$clientes['idMunicipio']);
 	 if($municipio=$muni->fetch_array(MYSQLI_ASSOC)){
 	 	//////////////////giro
 	 	$gir=$consult->giro($conection, $clientes['idGiro']);
 	 if($giro=$gir->fetch_array(MYSQLI_ASSOC)){
//////////////////rubro
 	 	$rub=$consult->rubro($conection, $clientes['idRubro']);
 	 if($rubro=$rub->fetch_array(MYSQLI_ASSOC)){
   	// echo $cp;
   	// echo $clientes['cp'];
   	if($clientes['cp']!=$cp){
    $cp=$clientes['cp'];
		$hr='<hr>';
   	}else{ $cp=$clientes['cp']; 
	$hr='';
   	  }//else 
   	  echo $hr;
  ?>
 
    <input type="checkbox" id="cliente" data-cliente="suma" value="<?php echo $cantidadclient."/".$clientes['id'];	?>"><?php echo utf8_encode($clientes['nombre']);?><br>	
   		
   		</td >
   		<td width="52" align="center" bgColor=#91C313>
   		<?php echo $cantidadclient;	?>
   		</td>
   	
   		<td align="center" width="345"><?php echo utf8_encode($estado['estado'].",".$municipio['municipio'].", <br>Colonia: ".$clientes['colonia'].",<br> Calle: ".$clientes['direccion']); ?></td>
   		<td align="center" bgColor=#91C313><?php echo $cp;?></td>
   		<!--<td><?php //echo utf8_encode($rubro['nombre']);?></td>
   		<td><?php //echo utf8_encode($giro['nombre']);?></td>-->
  
    <?php    
  
  	}//giro
 }//rubro
 
	 }//municipio
	  }//estado 
	  
	   
 }//clientes 
  
  //}//oferta cliente 2

}//verifica si esta comprobados
else{ ?>
</td></tr></table><hr>No hay clientes nuevos o aun no han sido aprobados.
	<table style="font-size: 12px;" cellpadding="5"  cellspacing="0">
				<tr><td>
		

<?php } ?>
 
  <tr>
  	
  	<!--<td ><hr><strong> Total:<span id="total">0</span><?php echo " ".$unidad; ?></strong></td>-->
  		<td></td><input type="hidden" id="tota" value="0"/>
  		
  </tr>

 <tr><td>
 	<select id="transporte"  style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;">
      <option selected><?php echo $trtedita; ?></option>
  		<?php 
  		$trans=$consult->transporteseditmas($conection,$idtranselec);
   while($transporte=$trans->fetch_array(MYSQLI_ASSOC)){ 

   	//$trans=$transporte['id'];
   	?>
      <option id="<?php echo $transporte['id']; ?>"><?php echo $transporte['id'].", ".$transporte['transporte']; ?></option>
      
<?php } ?></select></td>
  		
  		<td width="0"></td>
  		<td width="0">
  <input type="text" id="nombreruta"  value="<?php echo $ruta['nombre']; ?>"/>
  <input type="button"  id="guaruta" value="Actualizar" style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/>
  <input type="button" onclick="javascript:confirmar()" id="inicia" value="Iniciar Ruta" style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/>

       </td> 

 <td width="0"><img src="../images/preloader.gif"  id="carga" class="overbox" style="display: none;" />
 <input type="button"  value="Regresar"  onclick='regreso();' style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/>
</td>
<td>
  	</td></tr>
		</table>
	
		</table>
	
	
	
	
	
	
	
	
<?php	
}
else{ ?>
	
	<script type="text/javascript">
   		alert("La ruta se a quedado sin clientes"),window.location="../views/configrutas/listado_ofertas.php";
   	</script>
<?php }
	
	
}else{ //aki para cuando el estatus no es 2 osea q esta iniciada o cloncluida para ver las facturas

$fact=$conection->query("select * from sms_ruta_oferta where id=".$idruta." and (status=1 || status=0)");
if($factur=$fact->fetch_array(MYSQLI_ASSOC)){
	$clientrut=$consult->rutaofertaclient($conection, $idruta);//consulta sms_ruta_oferta_cliente
		if($idrutaofer=$clientrut->fetch_array(MYSQLI_ASSOC)){
$ofert=$conection->query('select 
CONCAT(o.cantidad," ",u.compuesto," de ",p.nombre," a sólo $",o.precio) oferta 
from 
sms_oferta o,
mrp_producto p, 
mrp_unidades u,
sms_oferta_cliente oc
where   p.idProducto=o.idProducto and o.idunidad=u.idUni 
and oc.idOferta=o.idOferta  and oc.id='.$idrutaofer['idOfertacliente']);
		if($ofe=$ofert->fetch_array(MYSQLI_ASSOC)){	
			$oferta=$ofe['oferta'];	
		}
		}?><a href="javascript:window.print();">
<img border="0" src="../../../netwarelog/repolog/img/impresora.png"></a>Facturas Generadas
	<table border="2" style="font-size: 15px;color:red; width: 100%;"><caption align="center"><strong>Nombre de RUTA: <?php echo $factur['nombre'];?>
		<br>Oferta:<?php echo  $oferta; ?> </strong></caption>
   		   	<tr><td>
Clientes agregados a la ruta
   		   
  <table style="font-size: 12px; width: 100%;" cellpadding="5"  cellspacing="0" border="0" >
  <th align="left" style="width: 20%;"><strong>Cliente</strong></th>
  <th><strong>Cantidad</strong></th>
  <th align="center" style="width: 40%;"><strong>Direcci&oacute;n</strong></th>
   <th><strong>Factura</strong></th>
  </tr>
  
  <?php
  $clientrut=$consult->rutaofertaclient($conection, $idruta);//consulta sms_ruta_oferta_cliente
		while($idrutaofer=$clientrut->fetch_array(MYSQLI_ASSOC)){
		?><tr><td><?php
		$ofertcli=$conection->query("select * from sms_oferta_cliente where id=".$idrutaofer['idOfertacliente']);
			if($ofer=$ofertcli->fetch_array(MYSQLI_ASSOC)){
				 $idcliente=$ofer['idCliente'];
				 	$cantidadclient= $ofer['cantidad'];
///////////////////////////////////////////////////////////////////////////copy
			 $cli=$consult->clientes($conection, $idcliente);
  if($clientes=$cli->fetch_array(MYSQLI_ASSOC)){
  	$dire=$consult->estado($conection,$clientes['idEstado']);
 	 if($estado=$dire->fetch_array(MYSQLI_ASSOC)){
 	 	$muni=$consult->municipio($conection,$clientes['idMunicipio']);
 	 if($municipio=$muni->fetch_array(MYSQLI_ASSOC)){
 	 	//////////////////giro
 	// 	$gir=$consult->giro($conection, $clientes['idGiro']);
 //	 if($giro=$gir->fetch_array(MYSQLI_ASSOC)){
//////////////////rubro
 	// 	$rub=$consult->rubro($conection, $clientes['idRubro']);
 	// if($rubro=$rub->fetch_array(MYSQLI_ASSOC)){
   	// echo $cp;
   	$cp= $clientes['cp'];
   	
  ?>
 
    <?php echo utf8_encode($clientes['nombre']);?><br>	
   		
   		</td >
   		<td align="center" bgColor=#91C313>
   		<?php echo $cantidadclient;	?>
   		</td>
   	
   		<td align="center" ><?php echo utf8_encode($estado['estado'].",".$municipio['municipio'].", <br>"."Colonia: ".$clientes['colonia'].",<br> Calle: ".$clientes['direccion']); ?></td>
   		<td align="center"> <a  href="../../../modulos/sms/facturacion/<?php echo $idrutaofer['factura'];?>.pdf"><?php echo $idrutaofer['factura'];?></a>
</td>
   		
  
    <?php    
  
  	//}//giro
 //}//rubro
 
	 }//municipio
	  }//estado 
	  
	   
 }//clientes 		
					
//////////////////////////////////////////////////////		
					
			}//if de sms_oferta_cliente
		
		}//while de rutaofertaclient
		?>  		

		</td></tr></table>  </table>
		<td><input type="button"  value="Regresar"  onclick='regreso();' style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/></td>

<!--AQUI PINTAMOS MAPA -->
		
		
	
		
		
		<p>
    	<center><div id="map_canvas" style="width:90%; height:70%"></div></center>
		<br><br><br>
		
		
		
		
			<script>
			initialize();
		</script>
		
		
		<?php
////////////////////////////////////////////////////////////////////////////////////////
}
}

?>
 <script type="text/javascript">var nombre=new Array();	
   $(document).ready(function(){
   
   		$('input:checkbox').click(function(){
   			
   			var subtotal=0;
   			var total=0;
   			//var tipo=$(this).attr('data-cliente');
   			
   			var num=($(this).val());
   			var elem = num.split('/');
   				 
   			//alert(nombre);
   			var idx = nombre.indexOf(elem[1]); // Localizamos el indice del elemento en array
if(idx!=-1){nombre.splice(idx, 1); // Lo borramos definitivamente
   		}else{ nombre.push(elem[1]);}	//lo agregamos	 
   		
   			$('input:checkbox:checked').each(function(){
   	   //alert(nombre);
   	   			var tipo=$(this).attr('data-cliente');
   				if(tipo=="suma"){
   					var sig=1;
   				}
   				if(tipo=="resta"){
   					var sig=-1;
   				}
   				//alert(parseInt($(this).val(),10));
   				subtotal+= (parseInt($(this).val(),10)*sig);
   				
   				total=subtotal*<?php echo 1; ?>
   			
   			});
   			
   			$('#tota').val(total);
   			
   			$('#total').text(total);
   			//alert(nombre);
   			
   			
   		});
   });
$(document).ready(function(){
   		$('#guaruta').click(function(){
   			//alert("entro");
   var namerut = jQuery("#nombreruta").val();          
   var idtrans = jQuery("#transporte").val();
   var total = jQuery("#tota").val();
   var idofert = jQuery("#idoferta").val();
   var idruta = jQuery("#idruta").val();
   var totalruta = jQuery("#totalruta").val();   
   
 var client="";  
  var x=0;
    
   for (x=1;x<nombre.length;x++){
   client+=nombre[x-1]+",";
    }
    client+=nombre[x-1];
    
     	//alert(client);
     	showloader();
 $.post("guardaedit.php",{namerut:namerut,idtrans:idtrans,total:total,client:client,idofert:idofert,idruta:idruta,totalruta:totalruta},
                  function(respuesta) {
                   	
                   	//respues = parseInt(respues,10);
                   	//alert(respuesta);
                     if(respuesta=="0"){ hideRegis();
                  		  alert("El nombre de ruta ya a sido asigando");
                  	 }
                  	 if(respuesta=="2"){ hideRegis();
                  	  alert("Ruta Actualizada"),window.location="../views/configrutas/listado_ofertas.php";
                  	  //window.location.reload();
                  	  }
                   if(respuesta=="1"){ hideRegis();
                  	  alert("Error..");
                  	   }
//                   	
     }
     );  
          
   		 });
   });
   
function confirmar(){   
//idruta=
var valor="";
confirmar=confirm("Si inicias la ruta ya no podras editarla,¿Estas seguro de Inicar la Ruta?"); 
    if (confirmar){
 		
 		showloader();
 
 $.post("iniciarut.php",{idruta:<?php echo $idruta; ?>},
                 function(respuesta) {
//alert(respuesta);
 valor=respuesta.split('//');
 //alert(valor[1])
 if(valor[1]=="2") {     hideRegis();               	 
 	 alert("Ruta Iniciada"),window.location="../views/configrutas/listado_ofertas.php";

                  	  }else{ 
                  	 	 hideRegis();
     alert("Error al iniciar ruta"),window.location="../views/configrutas/listado_ofertas.php";

                  	 	}  
                  
                 });
                  
 
    
 }else { hideRegis();
    // si pulsamos en cancelar
    window.location.reload();
    } 
    //if(resu!=""){
    	  // alert(resu);
   // }

 	} 
</script>
   