<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- <link rel="stylesheet" type="text/css" href="../../../../../netwarelog/design/default/netwarlog.css" -->
<?php include('../../netwarelog/design/css.php');?>
<LINK href="<?php echo $base_url; ?>netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />	
<LINK href="<?php echo base_url(); ?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="../../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.number.min.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.form.js"></script> 

<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';</script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/buy.js"></script>
	
<script>
$(document).ready(function() {
	
	<?php
		$pedido_explode= explode(" ", $orden[0]->Fecha_pedido);
		$fecha_pedido = $pedido_explode[0];
		$pedido_explode= explode("-", $fecha_pedido);
		$fecha_pedido = $pedido_explode[0] . "-" . $pedido_explode[1] . "-" .$pedido_explode[2];	
	?>
	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	//$(".datepicker").datepicker();
	
	$("#fecha_entrega").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate:"+60D",
        numberOfMonths: 1
    });  
	
	$( "#fecha_entrega" ).datepicker( "option", "minDate", new Date(<?php echo ($pedido_explode[0]); ?> , <?php echo ($pedido_explode[1]); ?> - 1, <?php echo ($pedido_explode[2]); ?>) );
	if($('#autorizado_por').val() == '')
	{
		$('#btngcompra').css('display','none');
		alert('La orden no ha sido autorizada')
	}
});	
</script>
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
	.nminputtext {
	    background-color: unset;
	    border: 1px solid #c2c2c2;
	    box-shadow: unset;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<div class="row">
				<div class="col-md-6">
					<label>Ingrese la factura:</label>
					<input type="text" id="factura" <?php if(is_object($factura)  && strlen($factura->xml)>2 && strlen($factura->fact)>2  )     {echo "readonly";}?> class="form-control big" maxlength="12" value="<?php if(is_object($factura)){echo $factura->factura;}?>">
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<?php
					if(is_object($factura) && strlen($factura->fact)>2 ){ ?>
						<div class="row">
							<div class="col-md-12">
								<?php 
								echo "<strong>Factura: </strong>"."<a href='".base_url().$factura->fact."' target='_blank'>".str_replace("facturas/","",$factura->fact)."</a>";
								echo '<input type="hidden" id="fact" name="fact" value="'.$factura->fact.'">';
								?>
							</div>
						</div>
					<?php
					}else{ ?>
						<form id="myForm_factura" action="<?php echo base_url(); ?>index.php/buy/uploadfile" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-12">
									<label>Factura:</label>
									<input type="text" disabled="" id="fact" name="fact" value="" class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<input type="file" size="40" name="myfile">
    								<input type="hidden" id="ordenid1" name="ordenid" value="<?php echo $orden[0]->Id; ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 col-md-offset-8">
									<button class="btn btn-primary btnMenu" type="submit" id="btnimagen">Agregar factura</button>
								</div>
							</div>
						</form>
					<?php
					}
					?>
				</div>
				<div class="col-md-6">
					<?php
					if(is_object($factura) && strlen($factura->fact)>2 ){ ?>
						<div class="row">
							<div class="col-md-12">
								<?php 
								echo "<strong>Xml: </strong>"."<a href='".base_url().$factura->xml."' target='_blank'>".str_replace("facturas/","",$factura->xml)."</a>";
								echo '<input type="hidden" id="xml" name="xml" value="'.$factura->xml.'">';
								?>
							</div>
						</div>
					<?php
					}else{ ?>
						<form id="myForm_xml" action="<?php echo base_url(); ?>index.php/buy/uploadfile" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-12">
									<label>XML:</label>
									<input ttype="text" disabled="" id="xml" name="xml" value="" class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<input type="file" size="40" name="myfile">
     								<input type="hidden" id="ordenid2" name="ordenid" value="<?php echo $orden[0]->Id; ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 col-md-offset-8">
									<button class="btn btn-primary btnMenu" type="submit" id="btnimagen">Agregar XML</button>
								</div>
							</div>
						</form>
					<?php
					}
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table" border="1" align="center" >

							<tr >
								<td width="50%" class="verde" align="left">Número de orden:<?php if(count($orden)>0) {echo ($orden[0]->Id);}?>
							    <input type="hidden" value=<?php if(count($orden)>0) {echo ($orden[0]->Id);}?> id="orden">
							    </td>
								<td width="50%" class="verde" align="right"><?php if(count($orden)>0) {echo utf8_decode($orden[0]->Proveedor);}?></td>  
							</tr>



							<tr >
								<td class="campo pad" align="">Fecha del pedido:<input type="text" class="long-input form-control" readonly value="<?php if(count($orden)>0)echo ($orden[0]->Fecha_pedido);?>"></td>
								<td class="campo pad" align="" >Fecha de entrega:<input id="fecha_entrega" type="text" <?php if(is_object($factura)){echo "readonly";}?>  value="<?php if(count($orden)>0) echo ($orden[0]->Fecha_de_entrega);?>" class="form-control"></td>  
							</tr>

							<tr><td colspan="2">

							<?php echo $detalle_orden;?>

							</td></tr>


							<tr >
								<td class="campo pad" align="">Elaborado por:<input type="text"  class="long-input form-control" readonly value="<?php if(count($orden)>0) {echo ($orden[0]->Elaboro);}?>"></td>
								<td class="campo pad" align="" >Autorizado por:<input type="text" id='autorizado_por' class="long-input form-control" readonly  value="<?php if(count($orden)>0) {echo ($orden[0]->Autorizacion);}?>">
								
								</td>  
							</tr>
							<tr>
								<td>Comentarios:<br>
									<textarea  style="width: 444px; height: 52px;" align="center" type="text" id="comentario" placeholder="Comentario..." class="form-control"><?php if(count($orden)>0) {echo ($orden[0]->Comentario);}?></textarea>
								</td>
							</tr>


							<tr >
								<td colspan="1" class="verde" align="left">Almacen:<?php if(count($orden)>0){ echo utf8_decode($orden[0]->Almacen);}?>
							    <input type="hidden" id="sucursal" name="sucursal" value="<?php if(count($orden)>0){ echo $orden[0]->idAlmacen;}?>">
							    </td> 
							    
							    <td colspan="1" class="verde" align="left">
							    <?php if(count($orden)>0 && is_numeric($orden[0]->idOrdPro) ){
							   echo "Orden de producción:".$orden[0]->idOrdPro;
							    } ?>	
							    </td> 
							</tr>

						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<img id="preloader
					" src="<?php echo base_url();?>/images/preloader.gif">
				</div>
				<div class="col-md-3"> <button class="btn btn-primary btnMenu" type="button" onclick="window.print()">Imprimir</button> </div>

				<div class="col-md-3">
					<button class="btn btn-primary btnMenu" type="button" onclick="window.location='<?php echo base_url(); ?>index.php/buy'">Regresar</button>
					<?php 
					if(count($orden)>0) 
					{
				 		if(strcmp($orden[0]->Estatus,"Registrada")==0)
				 		{ 
						?>
						<button class="btn btn-primary btnMenu" type="button" id="btngcompra" onclick="Guardarcompra();">Ingresar mercancia</button>
					<?php            
						}
						else
						{
							if(is_object($factura)  && strlen($factura->xml)<2 || strlen($factura->fact)<2 )
							{
							?>
							<button type="button" id="btngcompra" onclick="Actualizar(<?php echo $factura->id; ?>);">Actualizar</button>
					<?php
							}
						}
					} 
					?>
				</div>
			</div>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>