<!DOCTYPE html>
<head>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/clipolizas.css"/>
	<script type="text/javascript" src="js/previo.js" ></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	

</head>
<body>
<div id="contenedor" class="div" align="right">
	<div id='cargando-mensaje' style='font-size:12px;color:blue;width:20px;display: none;'> Cargando...</div>
	<?php 
		if(isset($listacli)){?>
			<font color="red" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del cliente.</font>
			<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
			<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentascli()" src="images/reload.png">
			
			<select id="CuentaClientes">
				<option selected="" value="-1"> Elija una cuenta</option>
				<?php while ($li=$listacli->fetch_array()){?>
				<option value="<?php echo $li['account_id']; ?>"><?php echo $li['description']."(".$li['manual_code'].")"?></option>
				<?php	} ?>
			</select>
	<?php	} ?>
	<?php 
		if(isset($listaprove)){?>
			<font color="red" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del Proveedor.</font>
			<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
			<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentas()" src="images/reload.png">
			
			<select id="CuentaProveedores">
				<option selected="" value="-1"> Elija una cuenta</option>
				<?php while ($li=$listaprove->fetch_array()){?>
				<option value="<?php echo $li['account_id']; ?>"><?php echo $li['description']."(".$li['manual_code'].")"?></option>
				<?php	} ?>
			</select>
	<?php	} ?>
	<br>
	Fecha de poliza:<input  type="date" id="fecha" name="fecha" onmousemove="javascript:fecha()" />

<table id="datos" align="center" cellpadding="2" border="0" style="border: white 1px solid; " width="100%">
				<thead>
					<tr>
						<td colspan="2"><font color="red" face="Comic Sans MS,arial,verdana"><?php //if(@$previo['cliente']['abono2'] || @$previo['prove']['cargo2']){echo "Elija una cuenta para IVA."; }?></font></td>
						
						<td class="nmcatalogbusquedatit" align="center">Cargo</td>
						<td class="nmcatalogbusquedatit" align="center">Abono</td>
						<td class="nmcatalogbusquedatit" align="center">XML</td>
						<td class="nmcatalogbusquedatit" align="center">Segmento</td>
						<td class="nmcatalogbusquedatit" align="center">Sucursal</td>
					</tr>
					<tr><td colspan="6"><hr></hr></td></tr>
				</thead>
					
						<?php 
						 //echo count($_SESSION['tabla']);
				if($comprobante==1){?>
				<div class="nmwatitles" style="width: 70%">&nbsp;Provision de Ingresos.</div>
				<tbody>
					
					<?php foreach($previo as $cli){
					 	$seg=explode("//",$cli['segmento']); 
						$suc=explode("//",$cli['sucursal']);
					?>	
							
			
			 <tr><td colspan="7"><hr id></hr></td></tr>
				 <tr>
					 <td rowspan="2" align="center" ><b><?php echo utf8_decode($cli['referencia']); ?></b><br></br>Ref:<?php echo utf8_decode($cli['concepto']); ?></td>
					 <input type="hidden" value="<?php echo utf8_decode($cli['concepto']); ?>"  id="UUID"/>
					 <input type="hidden" value="<?php echo utf8_decode($cli['referencia']); ?>"  id="referencia"/>
					 <td  class="nmcatalogbusquedatit" align="center"><?php echo $cli['cuenta']; ?></td>
					 <td align="center">0.00</td>
				     <td align="center" id="subtotal"><?php echo number_format(floatval($cli['abono']),2,'.',''); ?></td><td></td>
				    <td align="center" name="xml" id="xml"><?php echo utf8_encode($cli['xml']); ?></td> 
				     <td align="center" id=""><b><?php echo $seg[1] ?></b></td>
				     <td align="center" id=""><b><?php echo  $suc[1]?></b></td>
				     
				      <input type="hidden" value="<?php echo $seg[0]; ?>"  id="segmento"/>
				      <input type="hidden" value="<?php echo $suc[0]; ?>"  id="sucursal"/>
				 </tr>
				 <tr>
				<?php if($cli['abono2']>0){ ?>
					<td  align="center">
					<input type="button" id="ivaingre" name="ivaingre" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" value="<?php $ivapendientecobro = explode("//", $ivapendientecobro); echo $ivapendientecobro[1];?>">
					
					</td>	
					<td align="center">0.00</td>
					<td align="center" id="importe"><?php echo number_format($cli['abono2'],2,'.','');?></td>
				<?php } ?> 
				 </tr>
				<tr><td></td>
					
					<?php if($cli['ieps']>0){ ?>
					<td align="center">
						<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  $iepspendientecobro = explode("//", $iepspendientecobro); echo $iepspendientecobro[1]; ?>"/>

					</td>
						<td align="center">0.00</td>
						<td align="center" id="importeieps"><?php echo number_format($cli['ieps'],2,'.','');?></td>
					<?php } ?>
					</tr>
					<!-- ISH -->
					<tr>
					
					<?php if($cli['ish']>0 ){ ?>
					<td><font color="red" face="Comic Sans MS,arial,verdana">Cuenta para ISH &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
					<td align="center"><!-- Cuenta para ISH -->
						<!-- <input type="button" id="ish" name="ish" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  $ishh = explode("//", $ishh); echo $ishh[1]; ?>"/> -->						
						<select id="ish" name="ish" class="nmcatalogbusquedatit">
						<?php while($egresos=$cuentaish->fetch_array()){ ?>
							<option value="<?php echo $egresos['account_id']?>"><?php echo $egresos['description']."(".$egresos['manual_code'].")"; ?></option>
						<?php } ?>
						</select>
					</td>
						<td align="center">0.00</td>
						<td align="center" id="importeish"><?php echo number_format(floatval($cli['ish']),2,'.','');?></td>
					<?php } ?>
					</tr>
					<!--FIN  ISH -->
				 <tr>
				 	<td rowspan="1" align="center"></td>
					 <td  class="nmcatalogbusquedatit" align="center"><?php echo utf8_encode($cli['nombre']); ?></td>
				    <input type="hidden" id="nombrereceptor" value="<?php echo utf8_encode($cli['nombre']); ?>">
				     <td align="center" id="total"><?php echo number_format(floatval($cli['cargo']),2,'.',''); ?></td>
				     <td align="center">0.00</td>
				 </tr>
				  <!-- retencion -->
				  <tr><td colspan="4" class="nmwatitles">Retencion</td></tr>
				 <?php foreach ( $cli['retenidos'] as $key => $value){ ?>
				<tr>
				 	<td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
				 	<td>
						<select id="<?php echo $key; ?>" class="nmcatalogbusquedatit"> 
							<?php while($ingre=$cli[$key]->fetch_array()){ ?>
								<option value="<?php echo $ingre['account_id'] ?>"><?php echo $ingre['description']."(".$ingre['manual_code'].")"; ?></option>
							<?php } ?>
						</select>
					</td>

<!-- 
				 	<?php 
				 		if($key=="IVA"){ ?>
				 		<td align="center">
						<input type="button" id="IVA" name="IVA" onclick="mandaasignarcuenta();" title="Ir a asignacion de cuentas" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  $ivaretenido = explode("//", $ivaretenido); echo $ivaretenido[1]; ?>"/>

						</td>	
				 	<?php	} ?>
				 	<?php 
				 		if($key=="ISR"){?>
				 		<td align="center">
					
						<input type="button" id="ISR" name="ISR" onclick="mandaasignarcuenta();" title="Ir a asignacion de cuentas" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  $isrretenido = explode("//", $isrretenido); echo $isrretenido[1]; ?>"/>
						
						</td>	
				 	<?php	} ?> -->
				 	
				    <td align="center" id="total<?php echo $key; ?>" name="total<?php echo $key; ?>"><?php echo number_format(floatval($value),2,'.',''); ?></td>
				    <td align="center">0.00</td>
				 </tr>
				 <?php } ?>
				<!-- fin retencion -->
				 <tr><td colspan="7"><hr></hr></td></tr>
			
			<?php }
			  ?>
		</tbody>
	
	<?php }else if($comprobante==2){?>
		<div class="nmwatitles" style="width: 70%">&nbsp;Provision de Egresos.</div>
		<tbody>
	<?php foreach($previo as $cli){
							$seg=explode("//",$cli['segmento']); 
							$suc=explode("//",$cli['sucursal']); 
						   ?>	
							
			
			 <tr><td colspan="6"><hr></hr></td></tr>
				 <tr>
					 <td rowspan="2" align="center" ><b></b><?php echo utf8_decode($cli['concepto']); ?></b><br></br>Ref:<?php echo utf8_encode($cli['referencia']); ?></td>
					 <input type="hidden" value="<?php echo utf8_decode($cli['concepto']); ?>"  id="UUID"/><br />
					 <input type="hidden" value="<?php echo utf8_decode($cli['referencia']); ?>"  id="referencia"/>
					 <td  class="nmcatalogbusquedatit" align="center" ><?php echo $cli['cuenta']; ?></td>
				     <td align="center" id="subtotalegre"><?php echo number_format(floatval($cli['cargo']),2,'.',''); ?></td>
				     <td align="center">0.00</td>
				     <td align="" name="xml" id="xml" style="size: 10px"><?php echo utf8_encode( $cli['xml']); ?></td> 
				     <td align="center" id=""><b><?php echo $seg[1] ?></b></td>
				     <td align="center" id=""><b><?php echo  $suc[1] ?></b></td>

					  <input type="hidden" value="<?php echo $seg[0]; ?>"  id="segmentoegre"/>
				      <input type="hidden" value="<?php echo $suc[0]; ?>"  id="sucursalegre"/>
				 </tr>
				 <tr>
				 	<?php if($cli['cargo2']>0){ ?>
					<td   align="center">
					<input type="button" id="ivaegre" name="ivaegre" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%;white-space: normal;" value="<?php  $ivapendientepago = explode("//", $ivapendientepago); echo $ivapendientepago[1]; ?>"/>

					</td>
					<td align="center" id="importeegre"><?php if($cli['cargo2']){echo number_format($cli['cargo2'],2,'.','');}else{ echo 0;} ?></td>
					<td align="center">0.00</td>
					<?php } ?> 
				 </tr>
				 <tr><td></td>
					
					<?php if($cli['ieps']>0){ ?>
					<td align="center">
					<input type="button" id="iepsegre" name="iepsegre" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  $iepspendientepago = explode("//", $iepspendientepago); echo $iepspendientepago[1]; ?>"/>

					</td>
						<td align="center" id="importeiepsegre"><?php echo number_format($cli['ieps'],2,'.','');?></td>
						<td align="center">0.00</td>
					<?php } ?>
					</tr>
					<!-- ISH -->
					<tr>
					
					<?php if($cli['ish']>0){ ?>
					<td><font color="red" face="Comic Sans MS,arial,verdana">Cuenta para ISH &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
					<td align="center">
						<select id="ishegre" name="ishegre" class="nmcatalogbusquedatit">
						<?php while($egresos=$cuentaishegre->fetch_array()){ ?>
							<option value="<?php echo $egresos['account_id']?>"><?php echo $egresos['description']."(".$egresos['manual_code'].")"; ?></option>
						<?php } ?>
						</select>
					</td>
						<td align="center" id="importeishegre"><?php echo number_format(floatval($cli['ish']),2,'.','');?></td>
						<td align="center">0.00</td>
					</td>
					<?php } ?>
					</tr>
					<!--FIN  ISH -->
				 <tr>
				 	<td rowspan="2" align="center"></td>
					 <td  class="nmcatalogbusquedatit" align="center"><?php echo utf8_encode($cli['nombre']); ?></td>
					 <input type="hidden" id="nombreemisor" value="<?php echo utf8_encode($cli['nombre']); ?>">
					 <td align="center">0.00</td>
				     <td align="center" id="totalegre"><?php echo number_format(floatval($cli['abono']),2,'.',''); ?></td>
				 </tr>
				<!-- retencion -->
				  <tr><td colspan="4" class="nmwatitles">Retencion</td></tr>
				 <?php foreach ( $cli['retenidos'] as $key => $value){ ?>
				 <tr><td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
					<td>
						<select id="<?php echo $key; ?>" class="nmcatalogbusquedatit"> 
							<?php while($ingre=$cli[$key]->fetch_array()){ ?>
								<option value="<?php echo $ingre['account_id'] ?>"><?php echo $ingre['description']."(".$ingre['manual_code'].")"; ?></option>
							<?php } ?>
						</select>
					</td>
			<!--	 	<?php 
				 		if($key=="IVA"){ ?>
				 		<td align="center">
						<input type="button" id="IVA" name="IVA" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  $ivaretenido = explode("//", $ivaretenido); echo $ivaretenido[1]; ?>"/>

						</td>	
				 	<?php	} ?>
				 	<?php 
				 		if($key=="ISR"){ ?>
				 		<td align="center">
						<input type="button" id="ISR" name="ISR" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  $isrretenido = explode("//", $isrretenido); echo $isrretenido[1]; ?>"/>

						</td>	
				 	<?php	} ?> -->
					<td align="center">0.00</td>
				    <td align="center" id="total<?php echo $key; ?>" name="total<?php echo $key; ?>"><?php echo number_format(floatval($value),2,'.',''); ?></td>
				    
				 </tr>
				 <?php }//foreach retencion ?>
				<!-- fin retencion -->
				 <tr><td colspan="6"><hr></hr></td></tr>
			 
			<?php }
			  ?>
		</tbody>	
	
	<?php }?>
	</table>
	<input type="hidden" id="cuentaingre" value="<?php echo $cuentaingre; ?>">
	<!-- <input type="hidden" id="ivaingre" value="<?php echo $ivaingre; ?>"> -->	
	<input type="hidden" id="cuentaegre" value="<?php echo $cuentaegre; ?>">
	<!-- <input type="hidden" id="ivaegre" value="<?php echo $ivaegre; ?>"> -->	
	<input type="hidden" id="comprobante" value="<?php echo $comprobante; ?>">
	
	<input type="hidden" id="rfcreceptor" value="<?php echo $rfcreceptor; ?>">
	<input type="hidden" id="municipiorecep" value="<?php echo $municipiorecep; ?>">
	<input type="hidden" id="callerecep" value="<?php echo $callerecep; ?>">
	<input type="hidden" id="noExteriorrecep" value="<?php echo $noExteriorrecep; ?>">
	<input type="hidden" id="coloniarecep" value="<?php echo $coloniarecep; ?>">
	<input type="hidden" id="codigoPostalrecep" value="<?php echo $codigoPostalrecep; ?>">
	
	<input type="hidden" id="rfcemisor" value="<?php echo $rfcemisor; ?>">
	
	<input type="hidden" id="municipioemisor" value="<?php echo $municipioemisor; ?>">
	<input type="hidden" id="calleemisor" value="<?php echo $calleemisor; ?>">
	<input type="hidden" id="noExterioremisor" value="<?php echo $noExterioremisor; ?>">
	
	<input type="hidden" id="comprobante" value="<?php echo $comprobante; ?>">
	<?php if(isset( $_COOKIE['ejercicio'])){ ?>
		<input type="hidden" id="idperio" value="<?php echo $_COOKIE['periodo']; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $_COOKIE['ejercicio'];?>" />
	<?php }else{ ?>
		<input type="hidden" id="idperio" value="<?php echo $idperio; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $ejercicio;?>" />
	<?php } ?>
	<input type="button" id="regresar" onclick="javascript:window.history.back();" value="Regresa">
	<input type="button" id="guardar" value="Guarda poliza" onclick="guardaprovi()">
					<img src="images/loading.gif" style="display: none" id="load">

 </div>
</body>
</html>