<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<body>
		<div class="col-md-12" align="center">
			<div id="contPerce" style='text-align: center'>
				<div id="imprimir" class="imprimir" style="text-align: center">
					<?php 

					$empleado =0;
					$nomina=0;
					$idtipop ="";
					$idnomp="";
					$sumaPercepciones = 0;
					$sumaDeducciones = 0 ;
					$origen = 0;
					$origendes="";
					if($cargaPerceFiltros->num_rows>0) {
						while($e = $cargaPerceFiltros->fetch_object()){
							$tablaDeduccionEmpleado =""; 
							if($empleado != $e->idEmpleado){
								$cargaDeduccion   = $this->ReportesModel->cargaDeduccionFiltros($e->idtipop,$e->idnomp,$e->idEmpleado);
								if($empleado !=0){ ?>

								<tr>
									<td colspan='2' align='right'><b>Suma de percepciones</b></td>
									<td align='right'><?php  echo (number_format($sumaPercepciones,2,'.',','));?></td>
									<td colspan='2' align='right'><b>Suma de deducciones</b></td>
									<td align='right'><?php echo (number_format($sumaDeducciones,2,'.',','));?></td>
								</tr>
							</table>
							<div class='container-fluid row negri' style='text-align: right;'> 
								<label>NETO A PAGAR:</label>  
								<label>
									<?php  if ($sumaPercepciones>$sumaDeducciones) {
										echo "$ ".number_format($sumaPercepciones - $sumaDeducciones,2,'.',',');

									}else
									{
										echo "$ ".number_format($sumaDeducciones-$sumaPercepciones,2,'.',',');

									}?>
								</label> 
							</div>
							<br>
							<?php if  ( $origen==1 ||  $origen==2 || $origen==3){
								if ($origen==1 ||  $origen==2 ) {
									$origendes = strtolower($origendes); 
								} ?>


								<div class='mostrar negri firma' hidden>
									<p class='firma'>Recibo la cantidad asentada en "Neto a Pagar" por concepto de mi <?php echo $origendes ?> y demas prestaciones correspondientes al periodo que termina hoy, sin que a la fecha se me adeude ninguna cantidad. </p>
									<br>
									<div class='row firma'>
										<div class='col-md-12' style='text-align: center'>
											<p>____________________________________</p>
											<p>Firma</p>
										</div>		

										<?php }else  { ?>

										<div class='mostrar negri firma' hidden>
											<p class='firma'>Recibo la cantidad asentada en "Neto a Pagar" por concepto de mi sueldo y demas prestaciones correspondientes al periodo que termina hoy, sin que a la fecha se me adeude ninguna cantidad. </p>
											<br>
											<div class='row firma'>
												<div class='col-md-12' style='text-align: center'>
													<p>____________________________________</p>
													<p>Firma</p>
												</div>
												<?php  }?>
											</div>
										</div>
									</div>
									<div class='saltoDePagina' style='height:30px'></div>

									<?php 
									$sumaPercepciones=0;
									$sumaDeducciones = 0;

								} ?>

								<div class='alert alert-info'>
									<div class='container row'>
										<table style='text-align:left;color:black;font-size: 12px;'>
											<tbody>
												<tr>
													<td colspan="2">
														<?php
														$url = explode('/modulos',$_SERVER['REQUEST_URI']);
														if($logo1 == 'logo.png') $logo1= 'x.png';
														$logo1 = str_replace(' ', '%20', $logo1);	 
														?>
														<img src=<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/".$logo1;?>                     style="width: 180px;height: 35px;">
													</td>
													<td style="font-size: 12px" class="trsize" colspan="2">
														&nbsp;&nbsp;<b><?php echo $infoEmpresa['nombreorganizacion']?><?php echo $infoEmpresa['RFC']." ";?></b>
														<br> &nbsp;&nbsp;<b>Reg. Pat:<?php echo $regPatronal['registro']?></b>
													</td>
													<td>
														<tr>
															<td colspan='3' style='padding-right: 40px;padding-top: 10px;'><b><?php echo $e->apellidoPaterno." ".$e->apellidoMaterno." ".$e->nombreEmpleado."</b>"." ".$e->codigo?></td>
															<td style='padding-right: 40px;padding-top: 10px;'>
																<b>Departamento:</b><?php echo $e->idDep?></td>
																<td style="padding-top: 10px;">
																	<b>Nomina:</b><?php echo $e->nombre?></td>
																</tr>
																<tr>
																	<td colspan='2'><b>Curp:</b><?php echo $e->curp?></td>
																	<td style="padding-right: 20px"><b>Imss:</b><?php echo $e->nss?></td>
																	<td style="padding-right: 20px;"><b>RFC:</b><?php echo $e->rfc?></td>
																</tr>
																<tr>
																	<td colspan='2' style="padding-right: 10px;"><b>Periodo</b><?php echo $e->fechainicio." al ".$e->fechafin?></td>
																	<td id='nomina' name='nomina'><b>Periodo:</b><?php echo $e->numnomina?><b>N</b></td>
																	<td><b>Salario:</b><?php echo (number_format($e->salario,2,'.',','))?>
																		<td><b>Jornada:</b><?php echo $e->horas?>
																			&nbsp;&nbsp;
																			<?php 
																			if ($_REQUEST['origen']!='' || $e->idtipop==3) {
																				echo"<b>"."Origen:</b>"."$e->origendes";
																			}?>
																		</td>
																	</tr>
																	<tr>
																		<td><b>Días Lab.Prop:</b><?php echo $e->diaslabproporcion?></td>
																		<td style="padding-right: 10px;"><b>Días Lab:</b><?php echo $e->diaslaborados?></td>
																		<td><b>Días Vac.Pagados:</b><?php echo $e->diasvac?></td>
																		<td><b>Días Festivos:</b><?php echo $e->diasfestivo?></td>
																		<td><b>Dias pagados:</b><?php echo $e->pagadosdias?></td>
																	</tr>
																</tbody>
															</table>
														</div>
														<br>
														<table class='tablasobrerecibo' width='100%' style='border: 1px solid; font-size: 12.5px;background-color:rgb(255,255,255)'; >
															<div class='row' align='left'; style='color: black;'>
																<div class='row'>
																	<thead style='border:solid 1px';>
																		<tr class='encpren'>
																			<td colspan='3'><b>Percepciones</b></td>
																			<td colspan='3'><b>Deducciones</b></td>
																		</tr> 
																	</thead>
																	<tbody>
																		<tr>
																			<td colspan='3' style='vertical-align: top;'>
																				<table style='width:100%; height:100%;font-weight: normal;'>
																					<thead>
																						<tr>
																							<th class='clave'>Clave</th>
																							<th class='concp'>Concepto</th>
																							<th class='imp'>Importe</th>
																						</tr>
																					</thead>
																				</table> 
																			</td>
																			<td colspan='3' style='vertical-align:top;'>
																				<table style='width:100%;height:100%;font-weight:normal;'>
																					<thead>
																						<tr>
																							<th class='clave'>Clave</th>
																							<th class='concp'>Concepto</th>
																							<th class='imp'>Importe</th>
																						</tr>
																					</thead>
																				</table> 
																			</td>
																		</tr>
																	</tbody>
																	<?php  }?>
																	<tr onMouseDown='adicional(".$e->idEmpleado.");' id='".$e->idEmpleado."'>
																		<td class='conc'><?php echo $e->concepto; ?></td>
																		<td class='desc'><?php echo $e->descripcion;?></td>
																		<td style='width:100px;text-align:right;background-color:#eeeeee;'><?php  echo (number_format($e->importe,2,'.',','))?></td>


																		<?php  if(mysqli_num_rows($cargaDeduccion)>0){
																			$ded = $cargaDeduccion->fetch_assoc();
																			if(sizeof($ded) >0){?>

																			<td class='conc'><?php echo $ded["concepto"]; ?></td>
																			<td class='desc'><?php echo $ded["descripcion"];?></td>
																			<td class='impo'><?php echo (number_format($ded["importe"],2,'.',','));?></td>
																			<?php  }
																			else{
																				?>
																				<td class='conc'></td>
																				<td class='desc'></td>
																				<td class='impo'></td>
																				<?php
																			}
																			$sumaDeducciones += $ded["importe"];
																		}
																		else {  
																			?>
																			<td class='conc'></td>
																			<td class='desc'></td>
																			<td class='impo'></td>

																			<?php } ?>  
																		</tr>
																		<?php

																		$sumaPercepciones += $e->importe; 
																		$empleado = $e->idEmpleado;
																		$nomina   = $e->numnomina;
																		$idnomp   = $e->idnomp;
																		$idtipop  = $e->idtipop;
																		$origen   = $e->origen;
																		$origendes= $e->origendes;
																	}?>

																</tbody>
																<tr>
																	<td colspan='2' align='right'><b>Suma de percepciones</b></td>
																	<td align='right'><?php  echo (number_format($sumaPercepciones,2,'.',','));?></td>
																	<td colspan='2' align='right'><b>Suma de deducciones</b></td>
																	<td align='right'><?php  echo (number_format($sumaDeducciones,2,'.',','));?></td>
																</tr>
															</table>
															<div class='container-fluid row negri' style='text-align: right;'> 
																<label>NETO A PAGAR:</label>  
																<label>
																	<?php 
																	if ($sumaPercepciones>$sumaDeducciones) {
																		echo "$ ".number_format($sumaPercepciones - $sumaDeducciones,2,'.',',');

																	}else
																	{
																		echo "$ ".number_format($sumaDeducciones-$sumaPercepciones,2,'.',',');
																	}
																	?>
																</label> 
															</div>
															<br>

															<?php 	
															if  ($origen==1 || $origen==2 || $origen==3){
																$origendes = strtolower($origendes);  ?>
																<div class='mostrar negri firma' hidden>
																	<p class='firma'>Recibo la cantidad asentada en "Neto a Pagar" por concepto de mi <?php echo $origendes ?> y demas prestaciones correspondientes al periodo que termina hoy, sin que a la fecha se me adeude ninguna cantidad. </p>
																	<br>
																	<div class='row firma'>
																		<div class='col-md-12' style='text-align: center'>
																			<p>____________________________________</p>
																			<p>Firma</p>
																		</div>		
																		<?php }else  { ?>	
																		<div class='mostrar negri firma' hidden>
																			<p class='firma'>Recibo la cantidad asentada en "Neto a Pagar" por concepto de mi sueldo y demas prestaciones correspondientes al periodo que termina hoy, sin que a la fecha se me adeude ninguna cantidad.</p>
																			<br>
																			<div class='row firma'>
																				<div class='col-md-12' style='text-align: center'>
																					<p>____________________________________</p>
																					<p>Firma</p>
																				</div>
																				<?php } ?>
																			</div> 
																		</div></div>
																		<?php 
																	}
																	else{
																		if(mysqli_num_rows($cargaPerceFiltros)==0 && $_REQUEST['idtipop']!=''){
																			?>
																			<div class='w3-panel w3-padding-24 alert alert-info' style='border:solid 0px;background-color:rgb(217,237,247);width:100%;'>
																				<h4>¡NO EXISTEN REGISTROS!</h4> 
																			</div>
																			<?php  }
																		}
																		?>
																	</div>
																</div> 
															</body>
															</html>