<?php 
	$this->load->helper('url');
	$base_url=str_replace("modulos/mrp/","",base_url());
?>

<html>
	<head>
		<!--<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>netwarelog/design/default/netwarlog.css" /--> 
		<?php
			include('../../netwarelog/design/css.php');
			$url=base_url();
			$url.='js/product.js';
			// echo $url;
			// include('../../modulos/mrp/application/controllers/product.php');
		?>
		<LINK href="<?php echo $base_url; ?>netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
		<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" 	type="text/css" />
		<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.10.2.min.js"></script> 
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src='<?php echo $base_url; ?>modulos/cont/js/jquery.maskedinput.js' type='text/javascript'></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.numeros.js"></script> 
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.numeric.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/json2.js"></script>
		<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';</script>
		<script type="text/javascript" src="<?php echo $url ?>"></script>

		<script>
			$(function() {
				$("#contenido_etapas").hide();
				$("#loader").hide();
				$( "#tabs" ).tabs();
				$(".numero").numeric();
				$('.numero').validCampoFranz('0123456789.');
				$('.letras').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789abcdefghijklmnñopqrstuvwxyzáéiou ');
				$('.pro').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789 ');
				$(".float").numeric();
				$('.float').bind("cut copy paste",function(e) {
					e.preventDefault();
				});

				$(".pm").each(function(){
					if($(this).attr('id')==$('#proveedor').val()){
						$(this).hide();
					}
					//alert($(this).val())
				});

			//$("#duracionetapa").datetimepicker();
		});
	function validaneg(){
		if($("#inicial").val()<0){
			alert("No puedes tener numero negativos.");
			$("#inicial").val(0).focus();
	}
		}
	function valorConsumo(){
		if($('input[name=checkvendible]').is(':checked')==false){
			//alert('No esta seleccionado');
			$('#preciov').val('0');
		}
	} 
	function checkconsumo(){
		if($('#tipopro').val()== 5){
			$('#preciov').val('0');
		}
	}
	function loqueseaAceptar(etapa){
		anos=$("#anosin"+etapa).val();
		meses=$("#mesesin"+etapa).val();
		dias=$("#diasin"+etapa).val();
		horas=$("#horasin"+etapa).val();
		minutos=$("#minutosin"+etapa).val();
		segundos=$("#segundosin"+etapa).val();
			
		var tiempodur=anos+':'+meses+':'+dias+':'+horas+':'+minutos+':'+segundos;
		formato(tiempodur);

		$("#etaDuracion"+etapa).val(anos+':'+meses+':'+dias+':'+horas+':'+minutos+':'+segundos); 	

		$("#etaDuracion_h"+etapa).val(format);
		$('#my_' + etapa + '_Dialog').modal('hide');
		$("#anosin"+etapa).val(0);
		$("#mesesin"+etapa).val(0);
		$("#diasin"+etapa).val(0);
		$("#horasin"+etapa).val(0);
		$("#minutosin"+etapa).val(0);
		$("#segundosin"+etapa).val(0); 
	}
	function loquesea(etapa){
//my_<?php echo $key; ?>_Dialog
		$("#my_"+etapa+"_Dialog").modal('show');
		 $( "#anos"+etapa ).slider({
			value:0,
			min: 0,
			max: 10,
			step: 1,
			slide: function( event, ui ) {
				// $("#poll").val($( "#amount" ).val( "" + ui.value ));
				$("#anosin"+etapa).val(ui.value);
				$(ui.value).val($("#anosin"+etapa).val());
			}
		});
		$( "#meses"+etapa ).slider({
			value:0,
			min: 0,
			max: 11,
			step: 1,
			slide: function( event, ui ) {
				$("#mesesin"+etapa).val(ui.value);
				$(ui.value).val($("#mesesin"+etapa).val());
			}
		});
		$( "#dias"+etapa ).slider({
			value:0,
			min: 0,
			max: 6,
			step: 1,
			slide: function( event, ui ) {
				$("#diasin"+etapa).val(ui.value);
				$(ui.value).val($("#diasin"+etapa).val());
			}
		});
		$( "#horas"+etapa ).slider({
			value:0,
			min: 0,
			max: 23,
			step: 1,
			slide: function( event, ui ) {
				$("#horasin"+etapa).val(ui.value);
				$(ui.value).val($("#horasin"+etapa).val());
			}
		});
		$( "#minutos"+etapa ).slider({
			value:0,
			min: 0,
			max: 59,
			step: 1,
			slide: function( event, ui ) {
				$("#minutosin"+etapa).val(ui.value);
				$(ui.value).val($("#minutosin"+etapa).val());
			}
		});
		$( "#segundos"+etapa ).slider({
			value:0,
			min: 0,
			max: 59,
			step: 1,
			slide: function( event, ui ) {
				$("#segundosin"+etapa).val(ui.value);
				$(ui.value).val($("#segundosin"+etapa).val());
			}
		});
	}
	</script> 
	<style type="text/css">
		#tabs.ui-widget-content{
			border:none;
		}
		.npr{
			background-color: #f7f7f7;
			border: 1px solid #eee;
			border-radius: 0;
			margin: 0 5px 4px 0;
			padding: 1px;
			width: 150px;
		}		
		#contenido_etapas input{
		  border-radius: 0;
		  margin: 0 4px 4px 0;
		}
		#contenido_etapas textarea{
			border-radius: 0;
			margin: 0 4px 0 0;
			width:250px;
		}
		.edit{
			background-color: #2e9afe;
		}
		.save{
			background-color: #1FB347;
		}
		.delete{
			background-color: #F74242;
		}
		.btnMenu{
			border-radius: 0 !important; 
			width: 100%;
			margin-bottom: 1em !important;
			margin-top: 1em !important;
		}
		.row
		{
			margin-top: 1em !important;
		}
		.select2-container{
			width: 100% !important;
		}
		#tabs-2{
			width:100% !important;
		}
		.ui-widget-header{
			background: unset !important;
		}

		@medida only screen and (max-width: 800px){
			.ui-state-default{
				width: 32% !important;
				margin-left: 0.8% !important;
			}
		}

		@medida only screen and (max-width: 500px){
			.ui-state-default{
				width: 49% !important;
				margin-left: 0.5% !important;
			}
		}
	</style>

	<!-- Librerias de bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		
	<!-- librerias select con buscador -->
		<script src="<?php echo base_url(); ?>js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/select2/select2.css" />
</head>
<body>


	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Datos basicos</a></li>
						<li><a href="#tabs-2">Proveedor</a></li>
						<li><a href="#tabs-3">Categoria</a></li>
						<li><a href="#tabs-4">Tipo</a></li>
						<li><a href="#tabs-8">Lista de Precios</a></li>
						<li><a href="#tabs-5">Unidades de medida</a></li>
						<li><a href="#tabs-6">Descripcion</a></li>
						<li id="margen_ganancia"  style="display:none;">
							<a class="remarcado" href="#tabs-7">Margen de ganancia</a>
						</li>
					</ul>
					<div id="tabs-1" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12" title='ID (automatico)' id='ID_div'>
									<label> ID: </label>
									<font color="silver">*</font>
									<br>
									<input type="hidden" id="id" name="id" value="<?php if(isset($datos_producto)){echo $datos_producto[0]->idProducto;}?>">
									<input  type="text" onchange="campo_onchange(this,true)" value="(Autonúmerico)" style="text-align:right;color:#555555;" size="15" disabled="" class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12" id='nombre_div' title='Nombre de producto' >
									<label id="lbl357"> Nombre: </label>
									<font color="silver">*</font>
									<br>
									<input  type="text" id="name" maxlength="80" size="80" class="form-control" value="<?php if(isset($datos_producto)){echo $datos_producto[0]->nombre;}?>" onkeydown="compruebaInputNombre(this.value);" onkeyup="compruebaInputNombre(this.value);">
									<div id='alerta_nombre'></div>	
								</div>
							</div>
							<div class="row">
								<div class="col-md-12" id='clave_div' title='Clave de producto' >
									<label id="lbl357"> Clave/código de barras (ISBN): </label>
									<font color="silver">*</font>
									<br>
									<input  type="text" size="25" id="codigo" class="form-control"  value="<?php if(isset($datos_producto)){echo $datos_producto[0]->codigo;}?>" >
									<div id='alerta_clave'></div>	
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Precio venta: $</label>
									<font color="silver">*</font>
									<br>
									<input value="<?php if(isset($datos_producto)){echo $datos_producto[0]->precioventa;}?>" class="form-control" type="text" id="preciov" name="preciov" class="float" maxlength="8" onkeyup="calcula_neto();">
								</div>
								<div class="col-md-6">
									<label>Precio Neto: $</label>
									<img SRC="<?php echo base_url(); ?>images/calc.png" style="top:-5px; cursor:pointer;" width="20px" height="20px" id="cal_neto" value="calcula" onclick="calculaPrecio();">
									<br>
									<input type="text" name="precio_neto" id="precio_neto" class="form-control" class="float" onkeyup="calcula_venta();" readonly>
								</div>
							</div>
							<div class="row">
								<h4>Impuestos:</h4>
								<div class="col-md-12">
									<?php echo $imp; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-center" id="imagen-producto">
									<?php 	if(isset($datos_producto))
											{
												echo '<img width="250" height="250" src="'.base_url().$datos_producto[0]->imagen.'">';
											}
											else
											{
									?>
												<img width="250" height="250" src="<?php echo base_url();?>images/noimage.jpeg">
									<?php 	}
									?>
								</div>
							</div>
							<form id="myForm" action="<?php echo base_url(); ?>index.php/product/uploadfile" method="post" enctype="multipart/form-data">
								<div class="row">
									<div class="col-md-6">
										<input type="hidden" id="imagen" name="imagen" value="<?php if(isset($datos_producto)){echo $datos_producto[0]->imagen;}?>">
										<input type="file" size="40" name="myfile">
									</div>
									<div class="col-md-6">
										<button type="submit" class="btn btn-primary btnMenu" id="btnimagen">Agregar imagen</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div id="tabs-2" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<div class="row" id='proveedor_div'>
								<div class="col-md-6" title="Proveedor">
									<label>Proveedor Principal: </label>
									<br>
									<?php echo $prv; ?>
								</div>
								<div class="col-md-6" title="Costo">	
									<label>Costo proveedor: $</label>
									<br>
									<input value="<?php if(isset($datos_producto) && $datos_producto[0]->costo>0 ){echo $datos_producto[0]->costo;}?>" type='text' id='costo_proveedor' name='costo_proveedor' class='float form-control costo_prov' >
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<button class="btn btn-primary btnMenu" onclick="agregaProve();">Agregar proveedor</button>
								</div>
							</div>
							<div class="row" id="proveedores_select_hidden" style="display:none;">
								<?php print_r($prv_hidden);?>
							</div>
							<section id="provesmasivos">
								<section id='proveedor_div_masivos'>
									<?php
										foreach ($provesmasivos as $key) { 
									?>
											<div class="row" id="<?php echo $key->idPrv; ?>" class="pm">
												<div class="col-md-6" title="Proveedor">
													<label>Proveedor: </label>
													<br>
													<input type="text" name="" value="<?php echo utf8_decode($key->razon_social); ?>" attr="<?php echo $key->idPrv; ?>" class="form-control" readonly>
												</div>
												<div class="col-md-6" title="Costo">	
													<label>Costo proveedor: $</label>
													<input value="<?php echo $key->costo; ?>" type='text' id='costo_proveedor' name='costo_proveedor' class='float form-control costo_proveedor_added' style='width: 63%;' readonly>
													<input type="button" value="x" onclick="eliminarProve2(<?php echo $key->idPrv; ?>);">
												</div>
											</div>
									<?php } 
									?>	
								</section>
							</section>
							<form id="listaProvedores">
							</form>
						</div>
					</div>
					<div id="tabs-3" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<label id="lbl357">Departamento: </label>
									<br>
									<?php echo $dep; ?>
								</div>
								<div class="col-md-4">
									<label id="lbl357">Familia: </label>
									<br>
									<?php echo  $fam; ?>
								</div>
								<div class="col-md-4">
									<label id="lbl357">Linea: </label>
									<br>
									<?php echo  $lin; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label id="lbl357"> Color: </label>
									<br>
									<select id='col' class='form-control'>
										<option value=''>------</option>
										<?php
											foreach($query_color as $color):
												if($datos_producto[0]->color==$color->idCol)
												{echo '<option selected value="'.$color->idCol.'">'.utf8_decode($color->color).'</option>';}
												else{ echo '<option value="'.$color->idCol.'">'.utf8_decode($color->color).'</option>'; }		
											endforeach;
										?>
									</select>
								</div>
								<div class="col-md-4">
									<label id="lbl357"> Talla: </label>
									<br>
									<select id='tal' class='form-control'>
										<option value=''>------</option>
										<?php
											foreach($query_talla as $talla):
												if($datos_producto[0]->talla==$talla->idTal)
												{echo '<option selected value="'.$talla->idTal.'">'.utf8_decode($talla->talla).'</option>';}
												else{echo '<option value="'.$talla->idTal.'">'.utf8_decode($talla->talla).'</option>';}		
											endforeach;
										?>
									</select>
								</div>
								<div class="col-md-4">
									<?php
										$propiedadc="";
										$propiedadv="checked"; 
										$prodkit = "";
										if(isset($datos_producto))
										{
											if($datos_producto[0]->vendible==1){$propiedadv="checked";}else{$propiedadv="";}
											if($datos_producto[0]->consumo==1){$propiedadc="checked";}else{$propiedadc="";}
											if($datos_producto[0]->esreceta==1){$propiedadr="checked";}else{$propiedadr="";}
											if($datos_producto[0]->eskit==1){$prodkit="checked";}else{$prodkit="";} 
										}
									?>
								</div>
							</div>
						</div>
					</div>
					<div id="tabs-4" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<label id="lbl357">Tipo de Producto: </label>
									<?php	
									$idProducto = $datos_producto[0]->idProducto;
									$tipo = $datos_producto[0]->tipo_producto;
									$x1=$x2=$x3=$x4=$x5=$x6='';
									switch($tipo){
										case 1: 
											$x1='selected';
											echo "<script> $(function(){ $('#contenido_etapas').hide(); $('#margen_ganancia').hide();}); </script>"; 
										break;
										case 2: 
											$x2='selected';
											echo "<script>
											$(document).ready(function(){ 
												$('.tableCosto').show(); 
												peticionProdMateriales($idProducto,$tipo,0);
												$('#margen_ganancia').show('slow');
												$('#contenido_etapas').show('slow');
												});</script>"; ?>
												<script>
													tipo = 2;
													xx=window.location.href;
													if(xx.match(/formNuevo\/[0-9]{1,}/) ) {
														baseUrl='../../../';
													} else {
														baseUrl='../../';
													}
													url=baseUrl+'index.php/product/listaMateriales/'+tipo;
													
													$.ajax({
														type: 'POST',
														url:url,
														data: {producto:'<?php echo $idProducto; ?>',
															baseurl:baseUrl
														},
														success: function(resp){  
															if(resp=='dif'){
																	alert('No puedes cambiar el tipo de producto, elimina los materiales de la opcion "kit de productos"');
																return false;
															}
																$("#margen_ganancia").show('slow');
																$(".tableCosto").show('slow');
																$("#lista").empty().append(resp);
														}
													});
												</script><?php
										break;
										case 3:	
											$x3='selected';
											echo "<script> $(function(){ $('#contenido_etapas').hide(); $('#margen_ganancia').hide() }); </script>"; 
											break;
										case 4: 
											$x4='selected';
											echo "<script> $(function(){ $('#contenido_etapas').show('slow'); $('#margen_ganancia').show('slow');
												$('.tableCosto').show(); 
												peticionProdMateriales($idProducto,$tipo,0);
											 }); </script>"; ?>
											<script> 
												tipo = 4;
												xx=window.location.href;
												if(xx.match(/formNuevo\/[0-9]{1,}/) ) {
													baseUrl='../../../';
												} else {
													baseUrl='../../';
												}
												url=baseUrl+'index.php/product/listaMateriales/'+tipo;
									
												$.ajax({
													type: 'POST',
													url:url,
													data: {producto:'<?php echo $idProducto; ?>',
														baseurl:baseUrl
													},
													success: function(resp){  
														if(resp=='dif'){
															alert('No puedes cambiar el tipo de producto, elimina los materiales de la opcion "Producir productos"');
															return false;
														}
															$("#margen_ganancia").show('slow');
															$(".tableCosto").show('slow');
															$("#lista").empty().append(resp);
													}
												});
											</script><?php
										break;
										case 5: 
											$x5='selected'; 
											echo "<script> $(function(){ 
													$('#contenido_etapas').hide(); 
													$('#margen_ganancia').hide()
													}); 
												  </script>"; 
										break;
										case 6: 
											$x6='selected';echo "<script> $(function(){ $('#contenido_etapas').hide(); $('#margen_ganancia').hide() }); </script>";  
										break;
									}?>
									<select  id="tipopro" onchange="filtrar_tipo_producto({tipo_producto: $('#tipopro').val()});" class='form-control'>
										<option <?php echo $x1 ?> value="1">Producto</option>
										<option <?php echo $x2 ?> value="2">Producir producto</option>
										<option <?php echo $x3 ?> value="3">Material de produccion</option>
										<option <?php echo $x4 ?> value="4">Kit de productos</option>
										<option <?php echo $x5 ?> value="5">Producto de consumo</option>
										<option <?php echo $x6 ?> value="6">Servicio</option>
									</select>
								</div>
							</div>
							<section>
								<div class="row">
									<div class="col-md-3">
										<input <?php echo $propiedadv; ?> type="checkbox" id="vendible" value=1 onclick="valorConsumo()" name='checkvendible' class='nminputcheck'> Prod vendible
									</div>
									<div class="col-md-3" style="visibility:hidden;">
										<input <?php echo $propiedadc; ?> type="checkbox" id="consumo" value=1> Prod de producción
									</div>
									<div class="col-md-3" style="visibility:hidden;">
										<input <?php echo $prodkit; ?> type="checkbox" id="eskit" value=1> Es kit
									</div>
									<div class="col-md-3" style="visibility:hidden;">
										<input <?php echo $propiedadr; ?> type="checkbox" id="esreceta" value=1> Es receta
									</div>
								</div>
							</section>
							<section id='precios_div'>
								<div class="row" id='stock'>
									<div class="col-md-6">
										<label>Stock máximo:</label>
										<br>
										<input value="<?php 
											if(isset($datos_producto)){echo $datos_producto[0]->maximo;} else {echo "1";}?>"  type="text" id="maximo" name="maximo" class="float form-control" maxlength="8">
									</div>
									<div class="col-md-6">
										<label>Stock mínimo:</label>
										<br>
										<input value="<?php 
											if(isset($datos_producto)){echo $datos_producto[0]->minimo;} else {echo "1";}?>" type="text" id="minimo" name="minimo" class="float form-control" maxlength="8">
									</div>
								</div>
							</section>
							<section id="contenido_etapas" style="display:none;">
								<form id="www">
									<section id="etaps">
										<div class="row">
											<div class="col-md-6">
												<label id="lbl357">Etapas de Proceso: </label>
												<br>
												<input type="text" id="etapanombre" size="30" placeholder="Nombre Etapa" class="letras form-control">
											</div>
											<div class="col-md-2">
												<button type="button" class="btn btn-primary btnMenu" onclick="javascript:agregaetapa();return;">+</button>
											</div>
										</div>
										<?php
											foreach ($etapas as $key => $value) { 
												$formatoEtapa=str_replace('_', ' ',$key);
										?>
												<section id="e_<?php echo $key; ?>_e" class="etaps">
													<div class="row" id="xform">
														<div class="col-md-6">
															<input type="hidden"style="border: 1px solid #dddddd;margin-top: 0;padding: 1px;width: 100px;" id="labe_<?php echo $key; ?>_letapa" name="labeletapa" readonly value="<?php echo $key; ?>" class="letras">
															<input class="npr" id="labe_<?php echo $key; ?>_letapa" name="labeletapa" readonly value="<?php echo $formatoEtapa; ?>" class="letras form-control">
															<input type="hidden" style="width: 100px;border: 1px solid #dddddd;margin-top: 1px;padding: 2px;" id="total_<?php echo $key; ?>_hid" name="total" readonly value="<?php echo $value['dura']; ?>" class="letras">
														</div>
														<div class="col-md-6">
															<button class="btn btn-default btnMenu" onclick="quitaEtapa('<?php echo $key; ?>'); " style="cursor: pointer;">Eliminar</button>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4">
															<input type="text" placeholder="Nombre del proceso" id="etaName<?php echo $key; ?>" class="pro form-control">
														</div>
														<div class="col-md-4">
															<textarea id="etaDesc<?php echo $key; ?>"  placeholder="Descripcion" class="form-control" rows="5"></textarea>
														</div>
														<div class="col-md-4">
															<input type="hidden" id="etaDuracion<?php echo $key; ?>" value="" placeholder="Duracion" class="duracion" onclick="loquesea('<?php echo $key; ?>');" onkeyup="loquesea('<?php echo $key; ?>');">
															<input type="text" id="etaDuracion_h<?php echo $key; ?>" value="" placeholder="Duracion" class="duracion form-control" onclick="loquesea('<?php echo $key; ?>');" onkeyup="loquesea('<?php echo $key; ?>');">
														</div>

														<div class="modal fade" tabindex="-1" id="my_<?php echo $key; ?>_Dialog" role="dialog">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">Tiempo del Proceso</h4>
																	</div>
																	<div class="modal-body">
																		<div class="row">
																			<div class="col-md-6">
																				<label>Años:</label>
																				<br>
																				<input type="text" id="anosin<?php echo $key; ?>" class="form-control" readonly value="0">
																			</div>
																			<div class="col-md-1">
																			</div>
																			<div class="col-md-4" id="anos<?php echo $key; ?>" style="margin: 5% 6%;">
																			</div>
																			<div class="col-md-1">
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<label>Meses:</label>
																				<br>
																				<input type="text" id="mesesin<?php echo $key; ?>"  class="form-control" readonly value="0">
																			</div>
																			<div class="col-md-1">
																			</div>
																			<div class="col-md-4" id="meses<?php echo $key; ?>" style="margin: 5% 6%;">
																			</div>
																			<div class="col-md-1">
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<label>Dias:</label>
																				<br>
																				<input type="text" id="diasin<?php echo $key; ?>" class="form-control" readonly value="0">
																			</div>
																			<div class="col-md-1">
																			</div>
																			<div class="col-md-4" id="dias<?php echo $key; ?>" style="margin: 5% 6%;">
																			</div>
																			<div class="col-md-1">
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<label>Horas:</label>
																				<br>
																				<input type="text" id="horasin<?php echo $key; ?>" class="form-control" readonly value="0">
																			</div>
																			<div class="col-md-1">
																			</div>
																			<div class="col-md-4" id="horas<?php echo $key; ?>" style="margin: 5% 6%;">
																			</div>
																			<div class="col-md-1">
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<label>Minutos:</label>
																				<br>
																				<input type="text" id="minutosin<?php echo $key; ?>" class="form-control" readonly value="0">
																			</div>
																			<div class="col-md-1">
																			</div>
																			<div class="col-md-4" id="minutos<?php echo $key; ?>" style="margin: 5% 6%;">
																			</div>
																			<div class="col-md-1">
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<label>Segundos:</label>
																				<input type="text" id="segundosin<?php echo $key; ?>" class="form-control" readonly value="0">
																			</div>
																			<div class="col-md-1">
																			</div>
																			<div class="col-md-4" id="segundos<?php echo $key; ?>" style="margin: 5% 6%;">
																			</div>
																			<div class="col-md-1">
																			</div>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<div class="row">
																			<div class="col-md-6">
																				<button class="btn btnMenu btn-primary" onclick="javascript:loqueseaAceptar('<?php echo $key; ?>');">Aceptar</button>
																			</div>
																			<div class="col-md-6">
																				<button class="btn btnMenu btn-danger" data-dismiss="modal">Salir</button>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4">
															<input type="text" size="5" placeholder="Orden" id="etaOrden<?php echo $key; ?>" class="numero form-control">
														</div>
														<div class="col-md-4">
															<button class="btn btn-primary btnMenu" onclick="AgregaProceso('<?php echo $key; ?>');">+</button>
														</div>
														<div class="col-md-4">
														</div>
													</div>
												</section>
												<section id="listaprocesos_<?php echo $key; ?>" class="processes">
													<?php
														foreach ($etapas[$key] as $key1 => $value1) {
															if (is_array($value1)) {
																$fomaUsu=explode(':',$value1['duracion']);
																if($fomaUsu[0]=='0'){$anosf='';}else{$anosf=$fomaUsu[0].' Años ';}
																if($fomaUsu[1]=='0'){$mesesf='';}else{$mesesf=$fomaUsu[1].' Meses ';}
																if($fomaUsu[2]=='0'){$diasf='';}else{$diasf=$fomaUsu[2].' Dias ';}
																if($fomaUsu[3]=='0'){$horasf='';}else{$horasf=$fomaUsu[3].' Horas ';}
																if($fomaUsu[4]=='0'){$minutosf='';}else{$minutosf=$fomaUsu[4].' Minutos ';}
																if($fomaUsu[5]=='0'){$segundosf='';}else{$segundosf=$fomaUsu[5].' Segundos ';}
																if($anosf.$mesesf.$diasf.$horasf.$minutosf.$segundosf==''){
																	$formato='0 Segundos';
																}else{
																	$formato=$anosf.$mesesf.$diasf.$horasf.$minutosf.$segundosf;
																} 	
																$formatoProcceso=str_replace('_', ' ',$value1['proceso']);
													?>
																<section id="pro_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_pro">
																	<div class="row">
																		<div class="col-md-4">
																			<input name="pronombre" id="p_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_p" type="hidden" readonly value="<?php echo $value1['proceso'];?>" class="pro">
																			<input name="pronombreUsu" id="p_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_p" type="text" readonly value="<?php echo $formatoProcceso;?>" class="pro form-control">
																		</div>
																		<div class="col-md-4">
																			<textarea class="form-control" rows="5" name="prodesc" id="d_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_d" readonly><?php echo $value1['descripcion']; ?></textarea>
																		</div>
																		<div class="col-md-4">
																			<input name="produra" id="t_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_t" type="hidden" readonly value="<?php echo $value1['duracion']; ?>">
																			<input class="form-control" name="produraUsu" id="t_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_t" type="text" readonly value="<?php echo $formato; ?>">
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-4">
																			<input class="form-control" name="proorden" id="o_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_o" type="text" readonly value="<?php echo $value1['orden']; ?>" size="5">
																		</div>
																		<div class="col-md-4">
																			<button class="btn btn-primary btnMenu" onclick="quitaPro('pro_<?php echo $key; ?>_<?php echo $value1['proceso'];?>_pro','<?php echo $key; ?>','<?php echo $value1['proceso'];?>');" style="cursor:pointer;">Eliminar</button>
																		</div>
																		<div class="col-md-4">
																		</div>
																	</div>
																</section>
													<?php
															}
														}
													?>
												</section><?php
											}?>
										<section id="divetapa">
										</section>
									</section>
								</form>
							</section>
						</div>
					</div>
					<div id="tabs-5" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3">
									<label>Cantidad inicial:</label>
									<br>
									<input 
										<?php 
										if(isset($datos_producto))
										{
											echo 'value="'.$datos_producto[0]->inicial.'"'; 
											if($datos_producto[0]->inicial > 0)
											{
												echo 'disabled';
											}
										} 
										else 
										{ 
											echo 'value="0"';
										}
										?> 
										type="text" id="inicial" name="inicial" class="float form-control" maxlength="8" onblur="validaneg();">
								</div>
								<div class="col-md-3">
									<label id="lbl357"> Tipo de unidad: </label>
									<select 
										<?php if($datos_stock[0]['cantidad'] > 0 || $datos_producto[0]->inicial > 0){
											echo 'disabled';
										}
										?> 
										id="unidadProd"  onchange="javascript:unidadesCompra(<?php echo $type ?>,<?php echo '\''.$datos_producto[0]->idunidadCompra.','.$datos_producto[0]->idunidad.'\'';?>)" class="form-control">
										<?php
										foreach($query_unidad_conversion as $unidad):
											$caracteres = explode(",",$unidad->identificadores);
											$pintado = false;
											foreach ($caracteres as $key => $value) {
												if($value == $datos_producto[0]->idunidad)
												{
													echo  '<option selected identificadores="'.$unidad->identificadores.'" value="'.$unidad->id.'" >'.utf8_decode($unidad->compuesto).'</option>';
													
														?>
														<script type="text/javascript">
														var data = <?php echo '\''.$datos_producto[0]->idunidadCompra.','.$datos_producto[0]->idunidad.'\'';?>;
															unidadesCompra(2,data);
														</script>
														<?php
														$pintado = true;
														break;
												}
											}
											if($pintado == false)
											{
												echo  '<option identificadores="'.$unidad->identificadores.'" value="'.$unidad->id.'" >'.utf8_decode($unidad->compuesto).'</option>';
											}else
											{
												$pintado = false;
											}
										endforeach;
										?>		
									</select>
								</div>
								<div class="col-md-3">
									<label id="lblUCompra"> Unidad de compra: </label>
									<select <?php if($datos_stock[0]['cantidad'] > 0 || $datos_producto[0]->inicial > 0){
										echo 'disabled';
												}
									 ?> id="cboUCompra" onchange="javascript:conversion();" class="form-control">
									</select>
								</div>
								<div class="col-md-3">
									<label id="lblUVenta"> Unidad de venta: </label>
									<select <?php if($datos_stock[0]['cantidad'] > 0 || $datos_producto[0]->inicial > 0){
										echo 'disabled';
									}
									?> id="cboUVenta" onchange="javascript:conversion();" class="form-control">
									</select>
									<script type="text/javascript">
										unidadesCompra(1,',');
									</script>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label  style="float:left;">Conversion&nbsp;</label>
									<label id="lblConversion"></label>
								</div>
								<div class="col-md-3">
									<input type="button"  style="<?php if($datos_stock[0]['cantidad'] > 0){echo 'display: none;';}?>"
									value="Convertir" onclick="javascript:conversion();"  class="btn btn-primary btnMenu">
								</div>
								<div class="col-md-3">
									<input type="hidden" value="" id="hdConversion"	>
								</div>
								<div class="col-md-3">
								</div>
							</div>
						</div>
					</div>
					<div id="tabs-6" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<label id="lbl357">Descripción del ticket: </label>
									<br>
									<textarea  rows="2" cols="70"  id="desc" onkeydown="compruebaInputCorta();" onkeyup="compruebaInputCorta();" class="form-control"><?php if(isset($datos_producto)){echo $datos_producto[0]->descorta;}?></textarea>
									<br><div style='font-size: 11px;'>(Aparecerá en los ticket de ventas. En caso de no llenarse, aparecerá el nombre del producto)</div>
									<div id='alerta_corta'></div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label id="lbl357" >Descripción larga: </label>
									<br>
									<textarea rows="4" cols="80"  id="desl" onkeydown="compruebaInputLarga();" onkeyup="compruebaInputLarga();" class="form-control"><?php if(isset($datos_producto)){echo $datos_producto[0]->deslarga;}?></textarea>
									<div id='alerta_larga'></div>	
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label id="lbl357">Descripción cenefa: </label>
									<br>
									<textarea  rows="1" cols="50" id="descen" onkeydown="compruebaInputCenefa();" onkeyup="compruebaInputCenefa();" class="form-control"><?php if(isset($datos_producto)){echo $datos_producto[0]->descenefa;}?></textarea>
									<br>
									<div id='alerta_cenefa'></div>
								</div>
							</div>
						</div>
					</div>
					<div id="tabs-7" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<h4>Selecciona los materiales que componen este producto y su cantidad:</h4>
							<style type="text/css">
								.select2-container .select2-choice{
									background-image: unset !important;
									height: 34px !important;
								}
							</style>
							<section id="div_materiales">
								<div class="row">
									<div class="col-md-3">
										<span style="float: left; height: 34px; width: 25% ! important; padding-left: 6px; padding-top: 7px;" class="input-group-addon">Cant:</span>
										<input style="border-top-left-radius: 0px; border-bottom-left-radius: 0px; width: 75% ! important;" class="form-control" id="cantidad" min="0" type="number" class="form-control" placeholder="Ingresa una cantidad">
									</div>
									<div class="col-md-3">
										<select id="select_material" onchange="buscar_unidad({id:$('#select_material').val()})">
											<option value="">--Material--</option><?php		
											foreach($lista_materiales as $prod){ ?>
												<option value="<?php echo $prod->idProducto ?>"><?php echo $prod->nombre ?></option><?php
											} ?>
										</select>
									</div>
									<div class="col-md-3">
										<select id="select_unidad">
											<option value="">--Unidad--</option><?php
											foreach($lista_unidades as $uni){?>
												<option value="<?php echo $uni->idUni ?>"><?php echo $uni->compuesto ?></option><?php
											} ?>
										</select>
									</div>
									<div class="col-md-2">
										<select id="select_tipo">
											<option value="0">Normal</option>
											<option value="1">Opcional</option>
											<option value="2">Extra</option>
										</select>
									</div>
									<div class="col-md-1">
										<input type="button" value="+" onClick="agregar_material({cantidad:$('#cantidad').val(), id:$('#select_material').val(), unidad:$('#select_unidad').val(), tipo:$('#select_tipo').val()});">
									</div>
								</div>
							</section>
							<section id="agregados">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="tabla_agregados" border="0" class="table table-striped table-bordered" cellspacing="0">
												<?php
												session_start();
													
												if (!empty($_SESSION['materiales_agregados'])) { ?>
													<tr>
														<td><strong>Cantidad</strong></td>
														<td><strong>Material</strong></td>
														<td><strong>Unidad</strong></td>
														<td><strong>Tipo</strong></td>
														<td><strong>Costo</strong></td>
														<td align="center"><span class="glyphicon glyphicon-trash"></span></td>
													</tr><?php
													
													foreach ($_SESSION["materiales_agregados"] as $key => $value) {
														$value->idProducto;
														
														if ($value->tipo==0) {
															$tipo='Normal';
														}
														
														if ($value->tipo==1) {
															$tipo='Opcional';
														}
														
														if ($value->tipo==2) {
															$tipo='Extra';
														} 
														
														$total_agegados+=$value->costo;
														?>
														
														<tr id="tr_<?php echo $key ?>">
															<td><?php echo $value->cantidad ?></td>
															<td><?php echo $value->material ?></td>
															<td><?php echo $value->unidad ?></td>
															<td><?php echo $tipo ?></td>
															<td><?php echo $value->costo ?></td>
															<td align="center" onclick="eliminar_material({id:'<?php echo $key ?>'})">
																<a href="#">
																	<span class="glyphicon glyphicon-trash"></span>
																</a>
															</td>
														</tr> <?php
													} ?>
													
													<tr>
														<td colspan="4" align="right"><strong>Total: $</strong></td>
														<td><strong id="total_agregados"><?php echo $total_agegados ?></strong></td>
													</tr><?php
												} 
												?>
											</table>
										</div>
									</div>
								</div>
								<?php 
									// Si no existen materiales oculta la div del margen de ganancia
									if (empty($_SESSION['materiales_agregados'])) {
										$ocultar=' style="display: none;"';
									}
								?>
								<div class="row">
									<div class="col-md-3">
									</div>
									<div class="col-md-9" id="div_margen_ganancia" <?php echo $ocultar ?>>
										<div class="row">
											<div class="col-md-4" align="right">
												<label>Porcentaje de ganancia:<label>
											</div>
											
											<div class="col-md-4">
												% <input type="number" min="0" id="porcentaje_ganancia" style="width: 100px" value="<?php echo $margen_ganancia ?>">
											</div>
											
											<div class="col-md-4">
												<input  onclick="generar_margen({costo_total:$('#total_agregados').html(), margen:$('#porcentaje_ganancia').val()});" type="button" class="btnMenu btn btn-default" value="Margen de ganancia">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="row" id="div_margen_venta" style="display:none;">
											<div class="col-md-6">
												<label for="venta_margen">Precio de venta:</label>
												<input type="number" class="form-control" id="venta_margen" value="">
											</div>
											<div class="col-md-6">
												<button class="btn btn-primary btnMenu" type="button" onclick="aplicar_margen();">Aplicar</button>
											</div>
										</div>
									</div>
								</div>
							</section>
							<!-- Cambiamos los select por select con buscador -->
							<script type="text/javascript">
								$objeto=[];	
								// Creamos un arreglo con los id de los select
								$objeto[0]='select_material';
								$objeto[1]='select_unidad';
								$objeto[2]='select_tipo';		
								// Mandamos llamar la funcion que crea el buscador
								select_buscador($objeto);
							</script>
							<section id= "costoTable">
								<section id="lista">
								</section>
							</section>
							<section id="costos_extra" style="display:none;">
								<h3>Costos extra</h3>
								<div class="row">
									<div class="col-md-6">
										<label>Mano de obra:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input id="" type="text" class="form-control gasto_indirecto">
									</div>
									<div class="col-md-6">
										<label>Gastos indirectos: </label>
										<input id="" type="text" class="form-control gasto_indirecto">
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" id="lista">
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 tableCosto">
									</div>
								</div>
							</section>
							<input type="hidden" id="num_materiales" value="'.count($_SESSION["materiales_selecionados"]).'">
						</div>
					</div>
					<div id="tabs-8" style="font-size:11px;" class="row">
						<div class="col-md-12">
							<h3>Lista de precios</h3>
							<h5>Aqui puedes poner tus precios extras a tus productos, podras utilizarlos en la caja</h5>
							<div class="row">
								<div class="col-md-6">
									<button type="button" class="btn btn-primary btnMenu" onclick="agregaPrecios();">Agrega Precio</button>
								</div>
							</div>
							<section id="precioslistado">
								<div class="row" id="precioRegular">
									<div class="col-md-6">
										<label>Precio de Venta Regular: $</label>
										<br>
										<input type="text" id="price_<?php echo $datos_producto[0]->precioventa; ?>" class="form-control numero descu" value="<?php  echo $datos_producto[0]->precioventa; ?>" readonly>
										<?php 
										$descchek="checked";
										if(isset($datos_producto)){
											if($datos_producto[0]->descu==0){$descchek="checked";}else{$descchek="";}
										}
										?>
									</div>
									<div class="col-md-6">
										<label>Descuento : </label>
										<br>
										<input type="checkbox" id="descx" <?php echo $descchek; ?> >
									</div>
								</div>
								<section>
									<?php 
										foreach ($precios as $key) { 
											if($key->precio == $datos_producto[0]->precioventa){
												$pre.='<div id="precioRegulareach" class="row">';
													$pre.='<div class="col-md-6">';
														$pre.='<label>Precio de Venta Regular: $</label>';
														$pre.='<br>';
														$pre.='<input type="text" id="price_'.$key->id.'" class="form-control numero" value="'.$key->precio.'" readonly>';
													$pre.='</div>';
												$pre.='</div>';
											}else{
									?>
											<div class="row" id="precio_<?php echo $key->id;?>">
												<div class="col-md-3">
													<label>Descripcion: </label><br>
													<input type="text" name="" class="form-control" id="desc_precionuevo_<?php echo $key->id; ?>" value="<?php echo $key->descripcion; ?>" readonly>
												</div>
												<div class="col-md-3">
													<label>Precio: $</label><br>
													<input type="text" id="price_<?php echo $key->id; ?>" class="form-control numero" value="<?php echo $key->precio; ?>" readonly>
												</div>
												<div class="col-md-3">
													<label>Sujeto a descuento </label><br>
													<input type="text" id="descuento_<?php echo $key->id; ?>"class="form-control" value="<?php if($key->orden==0){ echo 'No'; }else{echo 'Si'; }?>" readonly>
													<select name="" id="select_descuento_<?php echo $key->id; ?>" style="display:none;" class="form-control">
														<option value="0">No</option>
														<option value="1">Si</option>
													</select> 
												</div>
												<div class="col-md-3">
													<input class="btn col-md-2 btnMenu edit" type="button" value="Editar" onclick="modificaPrecio(<?php echo $key->id; ?>)">
													<input type="button" value="Guardar" onclick="cambiaprecio(<?php echo $key->id; ?>);" style="display:none;" id="save_<?php echo $key->id; ?>" class="btn col-md-2 btnMenu save">
													<input type="button" value="x"  onclick="eliminaPrecio2(<?php echo $key->id; ?>);" class="btn col-md-2 btnMenu delete">
												</div>
											</div>
									<?php 
											}
										} 
									?>
								</section>
							</section>
							<form id="preciosnuevos">
							</form>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h4 style="border-top: 1px solid;"></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<button class="btn btn-success btnMenu" type="button" id="send" onclick=" func('<?php echo $base_url;?>');">Guardar información</button>
					</div>
					<div class="col-md-4">
						<img src="<?php echo base_url();?>/images/preloader.gif" id="loader">
					</div>
					<div class="col-md-4">
						<div id="divdepurar"></div>
						<div class="dialogLista modal fade" tabindex="-1" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title" id="dialogListaHeader"></h4>
									</div>
									<div class="modal-body" id="dialogListaBody">
									</div>
									<div class="modal-footer" id="dialogListaFooter">
										<div class="row" id="dialogListaFooterCalcular">
											<div class="col-md-6">
												<button class="btn btn-success btnMenu" onclick="javascript:cPCalcular();">Calcular precio</button>
											</div>
											<div class="col-md-6">
												<button class="btn btn-primary btnMenu" onclick="javascript:cPGuardar();">Guardar</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>