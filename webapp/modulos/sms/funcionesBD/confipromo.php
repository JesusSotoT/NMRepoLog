<?php
include_once('conexion.php');
    $consult = new Consult;
    $conection = $consult -> conection();
	?>
	
	<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<label class="listadofila"></label>
<div class="listadofila"  title="Configura Ruta" style="border:6px double orange; text-align: left; max-width: 450px;">
	PEDIDOS SIN ASIGNAR
	<br></br>
	<table border="1" style=" margin:0px auto 0px auto;">
		<tr> 
			
		<td style='border-width: 1px;border: solid; border: 3px outset #cccccc; background-color: #cccccc;'>
		
		<?php 
		$oferclien_sms=$consult->smsOfertaClient($conection);
		
		 while($ofertac=$oferclien_sms->fetch_array(MYSQLI_ASSOC)){	 
			 
		 	$ofert=$consult->smsOferta($conection, $ofertac['idOferta']);
		 	if($oferta=$ofert->fetch_array(MYSQLI_ASSOC)){
		 		
		 		$produ=$consult->mrproducto($conection, $oferta['idProducto']);
			$cantidadofer=$oferta['cantidad'];
				
				  if($produc=$produ->fetch_array(MYSQLI_ASSOC)){
				  	$producto=$produc['nombre'];
					  
					$uni=$consult->mrpunidad($conection, $oferta['idUnidad']);
					  if($unid=$uni->fetch_array(MYSQLI_ASSOC)){
					  	   $unidad=$unid['compuesto'];
					?>	
		 	
		 	
		 	
                    <table  style='font-family: Verdana; font-size: 12pt; color:#515054;' > 
			<tr>
            <td>
		<a style="text-decoration:none; " href="confirutas.php?a=<?php echo $ofertac['idOferta']; ?>&i=<?php echo $cantidadofer;?>&uni=<?php echo $unidad; ?>&pro=<?php echo $producto;?>&pre=<?php echo  $oferta['precio']; ?>"><?php echo $cantidadofer."-".$unidad."-".$producto." "."Precio"." ".$oferta['precio']; ?></a> </td>
			
		   </tr> 
		   <?php 
		     
					  	}//if unidades 	
				  }//if producto
		 	}//if oferta
		 	
		 }//while 
			?>
		</tr>
	
	</table>
	
</div>	
<?php	
	
?>