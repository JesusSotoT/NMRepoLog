<?php 
$base_url='../../../../../../';
$this->load->helper('url');
$base_url2=str_replace("modulos/mrp/","",base_url());
$this->load->model('Orden_compra');
?>

<LINK href="../../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />

<LINK href="../../../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>webapp/modulos/mrp/css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>webapp/modulos/mrp/css/typeahead.css" title="estilo" rel="stylesheet" type="text/css" />
  <!--<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>netwarelog/design/default/netwarlog.css" /-->  	
<?php include('../../netwarelog/design/css.php'); ?>
<LINK href="../../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->



	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script type="text/javascript" src="../../../../../modulos/mrp/js/typeahead.js"></script> 

	<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<link href="../../../../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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


	<script type="text/javascript" src="../../../../../modulos/mrp/js/jquery.alphanumeric.js"></script> 
	<script type="text/javascript" src="../../../../../modulos/mrp/js/buy_order.js"></script>
	<script type="text/javascript" src="../../../js/jquery.number.min.js"></script>
	<script src="../../../../mrp/js/ui.datepicker-es-MX.js"></script>

	<script>

	$.fn.disable = function() {
		return this.each(function() {          
			if (typeof this.disabled != "undefined") {
				$(this).data('jquery.disabled', this.disabled);

				this.disabled = true;
			}
		});
	};

	$.fn.enable = function() {
		return this.each(function() {
			if (typeof this.disabled != "undefined") {
				this.disabled = $(this).data('jquery.disabled');
			}
		});
	};


	$(document).ready(function() {

		$("#preloader_filtros").hide();
		$("#preloader_editar").hide();
		$("#preloader_agregar").hide();

		<?php
		$pedido_explode= explode(" ", $orden[0]->Fecha_pedido);
		$fecha_pedido = $pedido_explode[0];
		$pedido_explode= explode("-", $fecha_pedido);
		$fecha_pedido = $pedido_explode[0] . "-" . $pedido_explode[1] . "-" .$pedido_explode[2];	
		?>

		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	//$(".datepicker").datepicker();
	
	$(".positive").numeric({allow:"."});
	
	$("#fecha_entrega").datepicker({ 

		dateFormat: 'yy-mm-dd',
		minDate: 0,
		maxDate:"+60D",
		numberOfMonths: 1
	});  

	$( "#fecha_entrega" ).datepicker( "option", "minDate", new Date(<?php echo ($pedido_explode[0]); ?> , <?php echo ($pedido_explode[1]); ?> - 1, <?php echo ($pedido_explode[2]); ?>) );
	
	$( ".accordion" ).accordion({
		collapsible: true
	});

	<?php
	
	if($orden[0]->Estatus == 'Cerrada')
	{
		?>
		$('#editable *').disable();
		<?php
	}
	?>

	var currentRequestP;

	var clientes = $('#autorizado_por').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'id',
            displayKey: 'label',
            source: function(query, process) {

                if ($('#autorizado_por').val() != '')
                {
                    currentRequestP = $.ajax({
                        url: '../../buy_order/buscaUsuarios',
                        type: 'POST',
                        dataType: 'json',
                        data: {term: query},
                        beforeSend: function() {
                           if (currentRequestP != null) {
                                currentRequestP.abort();
                            }
                        },
                        success: function(data) {
                            return process(data);
                        },
                        error: function(data)
                        {
                            //$('#autorizado_por').removeClass('loader');
                        }
                    })
} else
{
    //$('#autorizado_por').removeClass('loader');
    if (currentRequestP != null) {
        currentRequestP.abort();
    }
}
}
}).on('typeahead:selected', function(event, data) {

    
    //caja.agregaProducto(data.id);
});

});
</script>

<?php
if(!isset($_SESSION)) {
	session_start();
}


unset( $_SESSION["cantidad_array"]);
unset( $_SESSION["unidad_array"]);
unset( $_SESSION["nombre_array"]);
unset( $_SESSION["proveedor_array"]);
unset( $_SESSION["costo_array"]);
unset( $_SESSION["subtotal_array"]);
unset( $_SESSION["unidad_texto"]);
unset( $_SESSION["nombre_texto"]);
unset( $_SESSION["proveedor_texto"]);
unset( $_SESSION['sucursal_solicita_temporal']);
unset( $_SESSION['fecha_pedido_temporal']);
unset( $_SESSION['fecha_entrega_temporal']);
unset( $_SESSION['elaborado_por_temporal']);

?>

	<div class="container" id="edicion_orden">
		<section id='editable'>
			<div class="row">
				<?php
				if ($orden[0]->Estatus == 'Cerrada')
				{?>
					<div class="col-md-12">
						<h3 class="nmwatitles text-center">
							Esta orden ya está cerrada. No puede modificarse.
		    			</h3>
					</div>
				<?php
				}
				$cerrada = true;
				?>
				<div class="col-md-12">
					<h3 class="nmwatitles text-center">
						Agregar un nuevo producto a la orden
	    			</h3>
				</div>
			</div>
			<section>
				<h4>Filtros de producto</h4>
				<div class="row">
					<div class="col-md-4">
						<label> Departamento: </label>
						<section id='dep_producto'>
							<?php echo $dep_prod; ?>
						</section>
					</div>
					<div class="col-md-4">
						<label> Familia: </label>
						<section id='fam_producto'>
							<?php echo $fam; ?>
						</section>
					</div>
					<div class="col-md-4">
						<label> Linea: </label>
						<section id='lin_producto'>
							<?php echo $lin; ?>
						</section>
					</div>
				</div>
			</section>
			<section>
				<h4>Producto</h4>
				<div class="row">
					<div class="col-md-4">
						<label id="lbl357">Producto: </label>
						<section id="producto_div">
							<?php echo $pro; ?>
						</section>
					</div>
					<div class="col-md-2">
						<label id="lbl357">Cantidad<font color="silver">*</font>: </label>
						<input class="positive form-control" maxlength="8" type="text" id="cantidad_producto" />
					</div>
					<div class="col-md-3">
						<label id="lbl357">Unidad de Compra: </label>
						<section id='uni'>
							<?php echo $uni; ?>
						</section>
					</div>
					<div class="col-md-3">
						<label id="lbl357">Ultimo costo de compra: </label>
						<input disabled type="text" id="ultimo_costo" value="" class="form-control"/>
					</div>
				</div>
				<div class="row" id="preloader_filtros">
					<div class="col-md-3">
						<h5 style="color: green !important;">Buscando producto...</h5>
					</div>
				</div>
				<div class="row" id="preloader_agregar">
					<div class="col-md-3">
						<h5 style="color: green !important;">Agregando producto...</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 col-md-offset-9">
						<input type="button" id="agregar" value="Agregar a la orden" onclick="registrar_producto_interface_edicion()" class="btn btnMenu btn-primary"/>
					</div>
				</div>
			</section>
			<section>
				<iframe src="" id="prueba" style="display:none;"></iframe>
			</section>
			<section id="orden_imprimible" class='editable' style="font-family: 'helvetica';">
				<page>	
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table" border="1" align="center" >

									<tr >
										<td width="50%" class="verde" align="left">Número de orden:<?php echo($orden[0]->Id); ?></td>
										<input type="hidden" value=<?php echo ($orden[0]->Id); ?> id="id_orden">
										<input type="hidden" value=<?php echo ($orden[0]->ID_Proveedor); ?> id="id_proveedor">
										<td width="50%" class="verde" align="right"><?php echo utf8_decode($orden[0]->Proveedor); ?></td>  
									</tr>

									<tr >
										<td class="campo pad" align="center">Fecha del pedido:<input id="fecha_pedido" type="text"   readonly value="<?php echo ($fecha_pedido); ?>" class="form-control"></td>
										<td class="campo pad" align="center" >Fecha de entrega:<input id="fecha_entrega" type="text" readonly value="<?php if($orden[0]->Fecha_de_entrega != "0000-00-00"){ echo $orden[0]->Fecha_de_entrega; }else if (isset($_SESSION['fecha_entrega_edicion'])){ echo $_SESSION['fecha_entrega_edicion']; }?>" class="form-control"></td>  
									</tr> 

									<tr><td colspan="2">
	

										<table border="1" width="100%" align="center">
											<tr class="tit_tabla_buscar">
												<td class="nmcatalogbusquedatit" align="center">Cantidad</td>
												<td class="nmcatalogbusquedatit" align="center">Unidad</td>
												<td class="nmcatalogbusquedatit" align="center">Producto</td>
												<td class="nmcatalogbusquedatit" align="center">Costo unitario</td>
												<td class="nmcatalogbusquedatit" align="center">Subtotal</td>
											</tr>	
											<?php 
											$i=0;$total=0; $contador_productos=0;
											$totalimpuesto=0;
											$impues=array();
											$impues['IVA']=0;
											$impues['ISR']=0;
											$impues['IEPS']=0;

											foreach($detalle_orden as $producto)
											{ 

												//
												$impuestos=$this->Orden_compra->productoImpuesto($producto->idProducto);
				
													$subtotal=$producto->ultCosto*$producto->cantidad;
													
													//$producto_impuesto=0;
													
													foreach ($impuestos as $impuesto => $impus) {
															
														$nomImpuesto=$impus->nombre;
														$producto_impuesto = (($subtotal) * $impus->valor / 100);
															if($nomImpuesto='IVA'){
																$producto_impuesto = (($subtotal) * $impus->valor / 100);
																$impues['IVA']+= $producto_impuesto;
															}else{
																$producto_impuesto = (($subtotal) * $impus->valor / 100);
																$impues['ISR']+= $producto_impuesto;
															}
													}
												//
												if($i%2==0)
												{
													echo '<tr class="nmcatalogbusquedacont_1">';
												} 
												else
												{ 
													echo '<tr class="nmcatalogbusquedacont_2">';
												}
												echo "<input id='id_producto_orden_".$contador_productos."' value=".$producto->idPrOr." type='hidden'> ";
												echo "<td align='center' ><input style='width:70%;' id='cantidad_".$contador_productos."' value='".$producto->cantidad."'  class='positive form-control' maxlength='8' onkeyup='ChangeOrder(".$contador_productos.");'></td>";
												echo "<td align='center' >".$producto->compuesto."</td>";
												echo "<td align='center' >".$producto->nombre."</td>";
												echo "<td align='center' ><input style='width:50%;' id='costo_producto_".$contador_productos."' maxlength='10' value='".$producto->ultCosto."' type='text' class='float form-control' onkeyup='ChangeOrder(".$contador_productos.");'></td>";
												echo "<td align='center' >$<span id='sub_".$contador_productos."'>".$producto->cantidad*$producto->ultCosto."</span></td>";
												echo "</tr>";

												$total+=$producto->cantidad*$producto->ultCosto;
												$contador_productos++;
											}

											echo "<input type='hidden' value=".$contador_productos." id='contador_productos'>";

											if($i%2==0){echo '<tr class="nmcatalogbusquedacont_1">';} else{ echo '<tr class="nmcatalogbusquedacont_2">';}
											echo "<td></td><td></td><td></td><td align='left'><strong>Neto:</strong></td><td align='center'><label id='precio_neto' style='size: 6px;'	value=''>$".$total."</label></td></tr>";
											$i++;
											if($i%2==0){echo '<tr class="nmcatalogbusquedacont_1">';} else{ echo '<tr class="nmcatalogbusquedacont_2">';}
											echo "<td></td><td></td><td></td><td align='left'><strong>Iva:</strong></td><td align='center'><label id='precio_iva' 	style='size: 6px;'	value=''>$".($impues['IVA'])."</label></td></tr>";
											$i++;
											if($i%2==0){echo '<tr class="nmcatalogbusquedacont_1">';} else{ echo '<tr class="nmcatalogbusquedacont_2">';}
											echo "<td></td><td></td><td></td><td align='left'><strong>Total:</strong></td><td align='center'><label id='precio_total' style='size: 6px;'	value=''>$".($total+$impues['IVA'])."</label></td></tr>";

											?>
										</table>

									</td></tr>


									<tr >
										<td class="campo pad" style="size: 8px;" align="center">Elaborado por: <?php echo ($orden[0]->Elaboro); ?></td>
										<td class="campo pad" align="center" ><label>Autorizado por:</label><br><input maxlength="100" id="autorizado_por" type="text" class="form-control" style="width: 100% !important;" value="<?php if($orden[0]->Autorizacion) echo $orden[0]->Autorizacion; else if (isset($_SESSION['autorizado_por'])) echo $_SESSION['autorizado_por'];?>"></td>  
									</tr>


									<tr > 
										<td colspan="2" class="verde" align="left">Almacen: <?php echo ($orden[0]->Almacen); ?></td> 
									</tr>

								</table>
							</div>
						</div>
					</div>
				</page>
			</section>
			<section>
				<div class="row">
					<div class="col-md-3">
						<?php 
							if($orden[0]->Enviada==1){ 
								$estatusEnviada='Volver a Enviar';
							}else{
								$estatusEnviada="Enviar";
							}
						?>
						<input type="button" value="<?php echo $estatusEnviada; ?>" onClick="enviar(<?php echo($orden[0]->Id); ?>);" class="btn btn-primary btnMenu"> 
					</div>
					<div class="col-md-2 col-md-offset-4">
						<input type="button" value="Imprimir" onClick="imprimir();" class="btn btn-primary btnMenu">
					</div>
					<div class="col-md-3">
						<label id="preloader_editar" style="color: green !important;">Guardando cambios...</label>
						<input type="button" id="guardar" value="Guardar cambios" onClick="guardarCambiosOrden();" class="btn btn-success btnMenu">
					</div>
				</div>
			</section>
		</section>
	</div>
