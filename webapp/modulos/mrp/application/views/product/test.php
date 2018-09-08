	
	<div class='listadofila' title='Datos generales de producto' style="width: 50%; display: table; padding: 10px">
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
			<input  type="text"  maxlength="100" size="46" id="name" value="<?php 
				if(isset($datos_producto)){echo $datos_producto[0]->nombre;}?>">
		</div>
		<!-- ///////////////////////////// -->
		<div id='clave_div' style='display: table; width: 100%;' title='Clave de producto' >
			<label id="lbl357"> Clave/ISBN: </label>
			<font color="silver">*</font>
			<br>
			<input  type="text"  maxlength="100" size="46" id="codigo" name="codigo" value="<?php 
				if(isset($datos_producto)){echo $datos_producto[0]->codigo;}?>"  >
		</div>
		<!-- ///////////////////////////// -->
		<div id='imagen_div' style='display: table; width: 100%;' title='Imagen de producto' >
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
			<br>
			<form id="myForm" action="<?php echo base_url(); ?>index.php/product/uploadfile" method="post" enctype="multipart/form-data">
			     <input type="hidden" id="imagen" name="imagen" value="<?php if(isset($datos_producto))
					{echo $datos_producto[0]->imagen;}?>">
			     <input type="file" size="40" name="myfile"><br>
			     <input type="submit" value="Agregar Imagen" id="btnimagen">
			</form>
		</div>
		<!--  /////////////////////////////  -->
		<div id='precios_div' style='display: table; width: 100%;' title='Precios'>
			<div style='width: 100%; display: table;'>
				<div title='Precio' id='precio_div' style='display: table-cell; width: 50%;'>
					<label>Precio venta:</label><input value="<?php 
						if(isset($datos_producto)){echo $datos_producto[0]->precioventa;}?>" type="text" id="preciov" name="preciov" class="float" maxlength="8">
				</div>
				
				<div title='Precio' id='precio_div' style='display: table-cell; width: 50%;'>
					<label>IVA (%):</label><input value="" type="text" id="iva" name="iva" class="numeric" maxlength="2">
				</div>
			</div>
			
			<div style='width: 100%; display: table; text-align: right;'>
				<label>IVA (%):</label><input value="" type="text" id="iva" name="iva" class="numeric" maxlength="2">
			</div>
			
			<div style='width: 100%; display: table; text-align: right;'>
				<label>IVA (%):</label><input value="" type="text" id="iva" name="iva" class="numeric" maxlength="2">
			</div>
		</div>
		
		<div id='detalles_div' style='display: table; width: 100%;' title='Mostrar detalles'>
			<input type='checkbox' > Mostrar detalles (no es requerido para registrar el producto)
		</div>
	</div> 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	v
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<table width="80%" border="0">
<tr>

	<td rowspan="2">
	
	<form id="formulario_producto" name="formulario_producto">
		<input type="hidden" id="listamateriales" name="listamateriales">
		
		<table class="campos" cellspacing="0" cellpadding="3" border="0">
		
			<tbody>
				<tr>
					<td>
						<div class="tipo">
							<a href="javascript:window.print();">
								<img border="0" src="<?php echo $base_url;?>netwarelog/repolog/img/impresora.png">
							</a>
							<b>Registro nuevo</b>
						</div>
						<br>
					</td>
				</tr>
			
				<tr class="listadofila" valign="middle" title="ID Departamento">
					<td class="campo">
						<label> ID: </label>
						<font color="silver">*</font>
						<br>
						<input type="hidden" id="id" name="id" value="<?php 
							if(isset($datos_producto)){echo $datos_producto[0]->idProducto;}?>">
						<input  type="text" onchange="campo_onchange(this,true)" value="(Autonúmerico)" style="text-align:right;color:#555555;" size="15" disabled="" >
					</td>
			
					<td class="campo">
						<label id="lbl357"> Clave/ISBN: </label>
						<font color="silver">*</font>
						<br>
						<input  type="text"  maxlength="100" size="46" id="codigo" name="codigo" value="<?php 
							if(isset($datos_producto)){echo $datos_producto[0]->codigo;}?>"  >
					</td>
			
					<td>
						<label id="lbl357"> Nombre: </label>
						<font color="silver">*</font>
						<br>
						<input  type="text"  maxlength="100" size="46" id="name" value="<?php 
							if(isset($datos_producto)){echo $datos_producto[0]->nombre;}?>">
					</td>
				</tr>
				
		<!-- ////////////////////////////////////////////////// -->
				
				<tr class="listadofila" valign="middle" title="Nombre">
					<td class="campo">
						<label id="lbl357">Departamento: </label>
						<font color="silver">*</font>
						<br>
						<div id='dep'>
							<?php echo $dep; ?>
						</div>
					</td>
					
					<td class="campo">
						<label id="lbl357">Familia: </label>
						<font color="silver">*</font>
						<br>
						<div id='fam_div'>
							<?php echo  $fam; ?>
						</div>
					</td>
			
					<td class="campo">
						<label id="lbl357">Linea: </label>
						<font color="silver">*</font>
						<br>
						<div id='lin_div'>
							<?php echo  $lin; ?>
						</div>
					</td>
				</tr>
				
		<!-- ////////////////////////////////////////////////// -->		
				
				<tr class="listadofila" valign="middle" title="Nombre">
					<td class="campo">
						<label id="lbl357">Descripción corta: </label>
						<font color="silver">*</font>
						<br>
						<textarea  rows="5" cols="40"  id="desc" >
							<?php if(isset($datos_producto)){echo $datos_producto[0]->descorta;}?>
						</textarea>
					</td>
			
					<td class="campo">
						<label id="lbl357">Descripción larga: </label>
						<font color="silver">*</font>
						<br>
						<textarea rows="5" cols="43"  id="desl" >
							<?php if(isset($datos_producto)){echo $datos_producto[0]->deslarga;}?>
						</textarea>
					</td>
			
					<td class="campo">
						<label id="lbl357">Descripción cenefa: </label>
						<font color="silver">*</font>
						<br>
						<textarea  rows="5" cols="43" id="descen" >
							<?php if(isset($datos_producto)){echo $datos_producto[0]->descenefa;}?>
						</textarea>
					</td>
				</tr>
	
		<!-- ////////////////////////////////////////////////// -->		
				
				<tr class="listadofila" valign="middle" title="Nombre">
					<td class="campo">
						<label id="lbl357"> Color: </label>
						<font color="silver">*</font>
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
					</td>
			
					<td class="campo">
						<label id="lbl357"> Talla: </label>
						<font color="silver">*</font>
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
					</td>
					
					<td>
						<br>
						<input type="button" value="Lista de materiales " id="listam"  onClick="ListaMateriales(<?php if(isset($datos_producto)){echo $datos_producto[0]->idProducto;}else {echo "0";} ?>,'<?php echo $base_url;?>');">
						<span id="btonlistamateriales"></span>
					</td>
				</tr>
		
		<!-- ////////////////////////////////////////////////// -->		
				
				<?php
				$propiedadc="";
				$propiedadv=""; 
				if(isset($datos_producto))
				{
					if($datos_producto[0]->vendible==1){$propiedadv="checked";}else{$propiedadv="";}
					if($datos_producto[0]->consumo==1){$propiedadc="checked";}else{$propiedadc="";}
					if($datos_producto[0]->esreceta==1){$propiedadr="checked";}else{$propiedadr="";}
				}
				?>
				
		<!-- ////////////////////////////////////////////////// -->		
			
				<tr class="listadofila">
					<td style="padding-top: 15px; padding-bottom: 15px;">
						<label>Producto de consumo</label>
						<input <?php echo $propiedadc; ?> type="checkbox" id="consumo" value=1>
					</td>
					
					<td style="padding-top: 15px; padding-bottom: 15px;" >
						<label>Producto vendible</label>
						<input <?php echo $propiedadv; ?> type="checkbox" id="vendible" value=1>
					</td>
					
					<td style="padding-top: 15px; padding-bottom: 15px;" >
						<label>Es receta?</label>
						<input <?php echo $propiedadr; ?> type="checkbox" id="esreceta" value=1>
					</td>
				</tr>
			
		<!-- ////////////////////////////////////////////////// -->			
			
				<tr class="listadofila">
					<td colspan="4" class="campo">
			    		<fieldset><legend>Precios</legend>
			    		<table border="0" width="55%">
			    			<tr class="listadofila">
			   
							    <td class="campo"><label>Precio venta:</label><input value="<?php 
									if(isset($datos_producto)){echo $datos_producto[0]->precioventa;}?>" type="text" id="preciov" name="preciov" class="float" maxlength="8"></td>
							   
							    <td class="campo"><label>Precio mayoreo:</label><input value="<?php 
									if(isset($datos_producto)){echo $datos_producto[0]->preciomayoreo;}?>"  type="text" id="preciom" name="preciom" class="float" maxlength="8"></td>
							
							
							    <td class="campo"><label>Precio liquidación:</label><input value="<?php 
									if(isset($datos_producto)){echo $datos_producto[0]->precioliquidacion;}?>"  type="text" id="preciol" name="preciol" class="float" maxlength="8"></td>
							</tr>
						</table>
			    		</fieldset>
			    	</td>
				</tr>
			
			
				<tr class="listadofila">
					<td colspan="4" class="campo">
					    <fieldset><legend>Punto de reorden</legend>
					    <table border="0" width="55%">
					    	<tr class="listadofila">
					   
							    <td class="campo"><label>Minimo:</label><input value="<?php 
									if(isset($datos_producto)){echo $datos_producto[0]->minimo;}?>" type="text" id="minimo" name="minimo" class="numeric" maxlength="8"></td>
							   
							    <td class="campo"><label>Máximo:</label><input value="<?php 
									if(isset($datos_producto)){echo $datos_producto[0]->maximo;}?>"  type="text" id="maximo" name="maximo" class="numeric" maxlength="8"></td>
							</tr>
						</table>
					    </fieldset>
				   	</td>
				</tr>
				
		<!-- ////////////////////////////////////////////////// -->		
			
			</tbody>
		</table>
		
	</form>
	</td>
	

<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		

<td align="center">
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
	<br>
	
	<!-- ////////////////////////////////////////////////// -->	

	<form id="myForm" action="<?php echo base_url(); ?>index.php/product/uploadfile" method="post" enctype="multipart/form-data">
	     <input type="hidden" id="imagen" name="imagen" value="<?php if(isset($datos_producto))
			{echo $datos_producto[0]->imagen;}?>">
	     <input type="file" size="40" name="myfile"><br>
	     <input type="submit" value="Agregar Imagen" id="btnimagen">
	 </form>
</td>
</tr>

<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		

<TR>
	<TD align="center">
		<?php   
				if(isset($datos_producto)) 
				{  
					if(strlen($datos_producto[0]->idProducto)<12)
					{ 
						$ante=''; for($i=strlen($datos_producto[0]->idProducto);$i<=12;$i++){ $ante.="0";} 
					} 
		?>
				<span id="codigobarras">
				<!--	
				<img class="decoded"  height="80" alt="<?php echo base_url(); ?>barcode/barcode.php?code=<?php echo $ante.$datos_producto[0]->idProducto;?>" src="<?php echo base_url(); ?>barcode/barcode.php?code=<?php echo $ante.$datos_producto[0]->idProducto;?>">
				-->
				</span>
		<?php 	} 
		?>
	</TD>
</TR>
</table>

<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
<!-- ////////////////////////////////////////////////// -->		
