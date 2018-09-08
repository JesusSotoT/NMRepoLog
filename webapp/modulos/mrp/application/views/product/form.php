<?php 
	$this->load->helper('url');
	$base_url=str_replace("modulos/mrp/","",base_url());
?>

<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo base_url(); ?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src='<?php echo $base_url; ?>modulos/cont/js/jquery.maskedinput.js' type='text/javascript'></script>
  
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script>  
<script type="text/javascript" src="http://malsup.github.com/jquery.form.js"></script>
<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';</script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/product.js"></script>
<script>
// Oculta el loader al cargar el documento
	$(function(){  $("#loader").hide(); });
	
	$(document).ready(function(){
		$("#kit_prod").click(function(){
			if( $( this ).is(':checked') ){
				$("#inicial").attr("disabled", "disabled");
			}else{
				$("#inicial").removeAttr("disabled");
			}
		});
		
		if($("#costo_proveedor").val()==''){
			$("#costo_proveedor").val('0');	
		}
		
		ListaMateriales_carga(<?php if(isset($datos_producto)){echo $datos_producto[0]->idProducto;}else {echo "0";} ?>,'<?php echo $base_url;?>');
	});
</script>

<center>
	<div style="width: 80%; display: table; text-align: left;">
		<div class='listadofila' title='Datos generales de producto' style="width: 30%; display: table-cell; padding: 10px">
			<div title='ID (automatico)' id='ID_div'>
				<label> ID: </label>
				<font color="silver">*</font>
				<br>
				<input type="hidden" id="id" name="id" value="<?php 
					if(isset($datos_producto)){echo $datos_producto[0]->idProducto;}?>">
				<input  type="text" onchange="campo_onchange(this,true)" value="(Autonúmerico)" style="text-align:right;color:#555555;" size="15" disabled="" >
			</div>
			
			<!-- ///////////////////////////// -->
			
			<div id='nombre_div' style='display: table; width: 100%;' title='Nombre de producto' >
				<label id="lbl357"> Nombre: </label>
				<font color="silver">*</font>
				<br>
				<input  type="text" id="name" maxlength="30" size="30"s style="width: 100%;" value="<?php 
					if(isset($datos_producto)){echo $datos_producto[0]->nombre;}?>" onkeydown="compruebaInputNombre(this.value);" onkeyup="compruebaInputNombre(this.value);">
				<div id='alerta_nombre'></div>	
			</div>
			
			<!-- ///////////////////////////// -->
			
			<div id='clave_div' style='display: table; width: 100%; padding-bottom: 10px; border-bottom: 1px solid #006efe;' title='Clave de producto' >
				<label id="lbl357"> Clave/código de barras (ISBN): </label>
				<font color="silver">*</font>
				<br>
				<input  type="text" size="13" id="codigo"  style="width: 100%;" name="codigo" value="<?php 
					if(isset($datos_producto)){echo $datos_producto[0]->codigo;}?>" >
				<div id='alerta_clave'></div>	
			</div>
			
			<!--  /////////////////////////////  -->
			
			<div id='precios_div' style='display: table; width: 100%; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #006efe;' title='Precios'>
				<div style='width: 100%; display: table;'>

					<div title='Precio' id='precio_div' style='display: table-cell; width: 50%; padding-right: 10px;'>
						
						<label>Precio venta:</label><font color="silver">*</font>
						<div style='display: table-cell;'>
							$
						</div>
						<div style='display: table-cell;'>
							<input value="<?php 
								if(isset($datos_producto)){echo $datos_producto[0]->precioventa;}?>" style="width: 100%;" type="text" id="preciov" name="preciov" class="float" maxlength="8" onkeyup="calcula_neto();">
						</div>
						<label>Precio Neto:</label>
						<div style='display: table-cell;'>
							$
						</div>
						<div style='display: table-cell;'>
							<input type="text" name="precio_neto" id="precio_neto" style="width: 100%;" class="float" onkeyup="calcula_venta();" readonly>
						</div>
						<div title='Precio' id='precio_div' style='display: table-cell; vertical-align: bottom; '>
							<IMG SRC="<?php echo base_url(); ?>images/calc.png" style="top:-5px; cursor:pointer;" width="20px" height="20px" id="cal_neto" value="calcula" onclick="calculaPrecio();">
							<!--<input type="button" id="cal_neto" value="calcula" onclick="calculaPrecio();">-->
						</div>
					</div>
					
					<div title='Impuestos' id='impuestos_div' style='display: table-cell; width: 50%;'>
						<!--<label>IVA (%):</label><input value="" type="text" id="iva"  style="width: 100%;" name="iva" class="numeric" maxlength="2">!-->
						<?php echo $imp; ?>
					</div>




				</div>

				<br />

				<div title="Unidad">
					<label id="lbl357"> Unidad: </label>
						<select id="unidadProd" style="width:100px;">
							
							
							<?php
							foreach($query_unidad as $unidad):
								if($datos_producto[0]->idunidad!='' && $datos_producto[0]->idunidad!=0 && $datos_producto[0]->idunidad!=1){

								if( $datos_producto[0]->idunidad==$unidad->idUni){
									
							echo  '<option selected value="'.$unidad->idUni.'" >'.utf8_decode($unidad->compuesto).'</option>';
							} }
								else{
							
						echo  '<option value="'.$unidad->idUni.'" >'.utf8_decode($unidad->compuesto).'</option>';		
							 } endforeach;
							 
						
								 
							?>
					
						</select>
				</div>



			</div>


			
			<!-- ///////////////////////////// -->
			
			<div id='imagen_div' style='display: table; width: 100%; padding-bottom: 10px; border-bottom: 1px solid #006efe;' title='Imagen de producto' >
				<center>
					<span id="imagen-producto">
						<?php if(isset($datos_producto))
								{
									echo '<img width="225" height="250"  src="'.base_url().$datos_producto[0]->imagen.'">';
								}
								else
								{
						?>
									<img src="<?php echo base_url();?>images/noimage.jpeg">
						<?php 	}
						?>
					</span>
				</center>
				<br>
				<form id="myForm" action="<?php echo base_url(); ?>index.php/product/uploadfile" method="post" enctype="multipart/form-data">
				     <input type="hidden" id="imagen" name="imagen" value="<?php if(isset($datos_producto))
						{echo $datos_producto[0]->imagen;}?>">
				     <input type="file" size="40" name="myfile"><br>
				     <input type="submit" value="Agregar Imagen" id="btnimagen">
				</form>
			</div>
			<!--si es simple-->
			<?php 
			$simple=false;
			$q=mysql_query("select a.idperfil from accelog_perfiles_me a where idmenu=1259");
			if(mysql_num_rows($q)>0)
			{
				$simple=true;
			}
			if($simple){ ?>
			<!--end si es simple-->	
			<!-- ///////////////////////////// -->
			<div id='proveedor_div' style='display: table; width: 100%; padding-bottom: 10px; border-bottom: 1px solid #006efe;' title='Proveedor principal' >
				<br>
				<div style="width: 100%; display: table;" title="Proveedor">
					<label>Proveedor: </label>
					<br>
					<div id='prv'>
						<?php echo $prv; ?>
					</div>
				</div>
				<br>
				<div style="width: 100%; display: table;" title="Costo">	
					<div>
						<label>Costo proveedor: </label>
					</div>
					<div>
						<div style='display: table-cell;'>
						$
						</div>
						<div style='display: table-cell;'>
							<input value="<?php if(isset($datos_producto) && $datos_producto[0]->costo>0 )
						{echo $datos_producto[0]->costo;}?>" type='text' id='costo_proveedor' name='Costo proveedor' class='float' style='width: 50%;'>
						</div>
					</div>
					<br>
				</div>
				<br>
			</div>
			
			<!-- ///////////////////////////// -->
			
			<br>
			<div style='display: table; width: 100%;' title='Mostrar detalles'>
				<input type='checkbox' id='detalles' onclick='ocultaDetallesProducto();'> Mostrar detalles (no es requerido para registrar el producto)
			</div>
		</div> 
		<!--si es simple-->
		<?php } ?>
		<!--end si es simple-->
		<!-- /////////////////////////////////////////////////////////////////////////////// -->
		<!-- /////////////////////////////////////////////////////////////////////////////// -->
		<!-- /////////////////////////////////////////////////////////////////////////////// -->
		<!-- /////////////////////////////////////////////////////////////////////////////// -->
		<!-- /////////////////////////////////////////////////////////////////////////////// -->
		
		<div class='listadofila' title='Datos generales de producto' style="width: 70%; display: table-cell;; padding: 10px; visibility: hidden;" id='detalles_div'>
			<div id="clasificacion_div" style="display: table; width: 100%; padding-bottom: 10px; border-bottom: 1px solid #006efe;" title="Clasificación">
				<div style='display: table; width: 100%; '>
					<div style="width: 60%; display: table-cell;">	
						<div style="width: 100%;" title="Departamento">
								<label id="lbl357">Departamento: </label>
								<br>
								<div id='dep'>
									<?php echo $dep; ?>
								</div>
						</div>
							
						<div style="width: 100%;" title="Familia">
								<label id="lbl357">Familia: </label>
								<br>
								<div id='fam_div'>
									<?php echo  $fam; ?>
								</div>
						</div>
						<div style="width: 100%;" title="Linea">
								<label id="lbl357">Linea: </label>
								<br>
								<div id='lin_div'>
									<?php echo  $lin; ?>
								</div>
						</div>
						<div style="width: 100%;" title="TipoProd">
								<label id="lbl357">Tipo de Producto: </label>
								<br>
								<div id='tipo-prod'>
										<select  id="tipopro">
											<option value="1">Producto</option>
											<option value="2">Producir producto</option>
											<option value="3">Material de produccion</option>
											<option value="4">Kit de productos</option>
											<option value="5">Producto de consumo</option>
										</select>
								</div>
						</div>
					</div>
					
					<div style="width: 40%; display: table-cell;">	
						<div style="width: 50%;" title="Color">
							<label id="lbl357"> Color: </label>
							<br>
							<select id='col'>
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
				
						<div style="width: 50%;" title="Talla">
							<label id="lbl357"> Talla: </label>
							<br>
							<select id='tal'>
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
						
						<br>
						<div>
							<input type="button" value="Lista de materiales " id="listam" title="Materiales" onClick="ListaMateriales(<?php if(isset($datos_producto)){echo $datos_producto[0]->idProducto;}else {echo "0";} ?>,'<?php echo $base_url;?>');">
							<span id="btonlistamateriales"></span>
						</div>
					</div>
				</div>
				
				<?php
				$propiedadc="";
				$propiedadv="checked"; 
				$prodkit = "";
				if(isset($datos_producto))
				{
					//echo "asdlknadlkansdlkadnlakndlaknsdlaksndlaksn";
					//echo var_dump( $datos_producto );
					 //$propiedadv = ( $datos_producto[ 0 ]->vendible == 1 ) : "checked" ? "";
					 if($datos_producto[0]->vendible==1){$propiedadv="checked";}else{$propiedadv="";}
					 if($datos_producto[0]->consumo==1){$propiedadc="checked";}else{$propiedadc="";}
					 if($datos_producto[0]->esreceta==1){$propiedadr="checked";}else{$propiedadr="";}
					 if($datos_producto[0]->eskit==1){$prodkit="checked";}else{$prodkit="";}
				}
				?>
				
				<div style='display: table; width: 100%; padding-top: 20px;'>
					<div style="display: table-cell; width: 30%;">
						<input <?php echo $propiedadc; ?> type="checkbox" id="consumo" value=1> Prod de producción
					</div>
					
					<div style="display: table-cell; width: 25%; border-right: 0px solid #AAAAAA;" >
						<input <?php echo $propiedadv; ?> type="checkbox" id="vendible" value=1> Prod vendible
					</div>
					<div style="display: table-cell; width: 15%; border-right: 1px solid #AAAAAA;" >
						<input <?php echo $prodkit; ?> type="checkbox" id="eskit" value=1> Es kit
					</div>
					
					<div style="display: table-cell; width: 20%; padding-left: 10px;" >
						<input <?php echo $propiedadr; ?> type="checkbox" id="esreceta" value=1> Es receta
					</div>
				</div>
				
			</div>

			<!--  /////////////////////////////  -->
			
			<div id='descripciones_div' style='display: table; width: 100%; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #006efe;' title='Descripciones'>
					<div class="campo">
						<label id="lbl357">Descripción corta: </label>
						<br>
						<textarea  rows="2" cols="70"  id="desc" style='width: 60%;' onkeydown="compruebaInputCorta();" onkeyup="compruebaInputCorta();"><?php if(isset($datos_producto)){echo $datos_producto[0]->descorta;}?></textarea>
						<br><div style='font-size: 11px;'>(Aparecerá en los ticket de ventas. En caso de no llenarse, aparecerá el nombre del producto)</div>
						<div id='alerta_corta'></div>
					</div>
					<br>
					<div class="campo">
						<label id="lbl357" >Descripción larga: </label>
						<br>
						<textarea rows="4" cols="80"  id="desl" style='width: 100%;' onkeydown="compruebaInputLarga();" onkeyup="compruebaInputLarga();"><?php if(isset($datos_producto)){echo $datos_producto[0]->deslarga;}?></textarea>
						<div id='alerta_larga'></div>	
					</div>
			
					<div class="campo">
						<label id="lbl357">Descripción cenefa: </label>
						<br>
						<textarea  rows="1" cols="50" id="descen" style='width: 40%;' onkeydown="compruebaInputCenefa();" onkeyup="compruebaInputCenefa();"><?php if(isset($datos_producto)){echo $datos_producto[0]->descenefa;}?></textarea>
						<div id='alerta_cenefa'></div>	
					</div>
			</div>
			<!-- ///////////////////////////// -->
			
			<div id='precios_div' style='display: table; width: 100%; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #006efe;' title='Stocks'>
				<div style="display: table-cell; width: 50%;" title="Maximo y minimo de reorden">
					<div title="Stock máximo">
						<label>Stock máximo:</label>
						<br>
						<input value="<?php 
							if(isset($datos_producto)){echo $datos_producto[0]->maximo;} else {echo "1";}?>"  type="text" id="maximo" name="maximo" class="float" maxlength="8">
					</div>
					<br>
					<div title="Stock minimo">
						<label>Stock mínimo:</label>
						<br>
						<input value="<?php 
							if(isset($datos_producto)){echo $datos_producto[0]->minimo;} else {echo "1";}?>" type="text" id="minimo" name="minimo" class="float" maxlength="8">
					</div>
					<br>
					<div title="Stock inicial">
						<label>Stock inicial:</label>
						<br>
						<input <?php 
								if(isset($datos_producto))
								{
									echo 'value="'.$datos_producto[0]->inicial.'"'; 
									if($datos_producto[0]->inicial>0)
										{
											echo 'disabled';
										}
								} 
								else 
									{ 
										echo 'value="0"';
									}
									?> type="text" id="inicial" name="inicial" class="float" maxlength="8">
					</div>
					<br>
					
				</div>
				
				<div style="display: table-cell; width: 50%; border-left: 1px solid #AAAAAA; padding-left: 10px; visibility: hidden;" title="Precios de mayoreo y liquidacion">
					<div title="Precio de mayoreo">
						<label>Precio de mayoreo:</label>
						<br>
						<input value="<?php 
							if(isset($datos_producto)){echo $datos_producto[0]->preciomayoreo;}?>"  type="text" id="preciom" name="preciom" class="float" maxlength="8">
					</div>
					<br>
					<div title="Precio de liquidacion">
						<label>Precio de liquidación:</label>
						<br>
						<input value="<?php 
							if(isset($datos_producto)){echo $datos_producto[0]->precioliquidacion;}?>"  type="text" id="preciol" name="preciol" class="float" maxlength="8">
					</div>
				</div>
			</div>	
		</div>
	</div>
	
	<div style="width: 80%; text-align: right;">
		<img src="<?php echo base_url();?>/images/preloader.gif" id="loader">
		<input id="send" type="button" value="Guardar" onclick="func('<?php echo $base_url;?>')" /> 

		<div id="divdepurar"></div>
		
		<div class="dialogLista"></div>
	</div>
</center>