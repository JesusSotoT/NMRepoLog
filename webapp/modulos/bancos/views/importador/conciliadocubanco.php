<div id="sumamov" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Sumar Movimientos a un Documento</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col-md-12">
                		<section>
							<div class="row" style="margin: 0 !important;">
								<div class="col-md-7">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table id="tmovbancosinverso" class="table table-striped table-bordered" style="min-width: 120px;">
													<thead>
														<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
															<th>Fecha</th>
															<th>Folio</th>
															<th>Concepto</th>
															<th>Importe</th>
															<th>
																<input type="text" id="buscar2" placeholder="Buscar..." align="right" style="color: black" size="12"/>
															</th>
														</tr>
													</thead>
													<tbody style="overflow: auto; display: inline-block; height: 23vw ! important;">
													<tr>
														<td style="background-color:#6E6E6E;color:white;font-weight:bold;" colspan="5">Ingresos</td>
													</tr>
														
													<?php $cont2=0;
													if($ingresosP2){
														while ($row = $ingresosP2->fetch_assoc()){$cont2++;  ?>
															<tr >
																<td style='word-wrap: break-word;' align="center"><?php echo $row['fecha'];?></td>
																<td style='word-wrap: break-word;' align="center"><?php echo $row['folio'];?></td>
																<td style='overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;' align="center"><?php echo $row['concepto'];?></td>
																<td style='word-wrap: break-word;' align="right"><?php echo number_format($row['importe'],2,'.',',');?></td>
																<td align="center">
																	<div id="bancos<?php echo $row['id'];?>" data-role="movdoc" data-value="<?php echo $row['id'];?>" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;">
																	</div>
																</td>
															</tr>
														
													<?php	}
													}?>
																
													<tr><td style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="5">Depositos</td></tr>
													
													<?php
													if($depositosP2){	
														while ($row = $depositosP2->fetch_assoc()){ $cont2++; ?>
														 <tr >
															<td style='word-wrap: break-word;' align="center"><?php echo $row['fecha'];?></td>
															<td style='word-wrap: break-word;' align="center"><?php echo $row['folio'];?></td>
															<td style='overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;' align="center"><?php echo $row['concepto'];?></td>
															<td style='word-wrap: break-word;' align="right"><?php echo number_format($row['importe'],2,'.',',');?></td>
															<td align="center">
																<div id="bancos<?php echo $row['id'];?>" data-role="movdoc" data-value="<?php echo $row['id'];?>" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;">
																</div>
															</td>
														</tr>
													
													<?php	}
													}?>
													
													<tr><td style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="5">Egresos</td></tr>
													
													<?php
													if($egresosP2){	
														while ($row = $egresosP2->fetch_assoc()){ $cont2++; ?>
														 <tr >
															<td style='word-wrap: break-word;' align="center"><?php echo $row['fecha'];?></td>
															<td style='word-wrap: break-word;' align="center"><?php echo $row['folio'];?></td>
															<td style='overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;' align="center"><?php echo $row['concepto'];?></td>
															<td style='word-wrap: break-word;' align="right"><?php echo number_format($row['importe'],2,'.',',');?></td>
															<td align="center">
																<div id="bancos<?php echo $row['id'];?>" data-role="movdoc" data-value="<?php echo $row['id'];?>" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;">
																</div>
															</td>
														</tr>
													
												<?php	}
													}?>
												
													<tr><td style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="5">Cheques</td></tr>
													
													<?php
													if($chequesP2){	
														while ($row = $chequesP2->fetch_assoc()){ $cont2++; ?>
														 <tr >
															<td style='word-wrap: break-word;' align="center"><?php echo $row['fecha'];?></td>
															<td style='word-wrap: break-word;' align="center"><?php echo $row['folio'];?></td>
															<td style='overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;' align="center"><?php echo $row['concepto'];?></td>
															<td style='word-wrap: break-word;' align="right"><?php echo number_format($row['importe'],2,'.',',');?></td>
															<td align="center">
																<div id="bancos<?php echo $row['id'];?>" data-role="movdoc" data-value="<?php echo $row['id'];?>" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;">
																</div>
															</td>
														</tr>
													
													<?php }
													}?>
													<tr><td><input type="hidden" value="<?php echo $cont2;?>" id="numregistrossuma"/></td></tr>
			
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table id="" class="table" >
													<thead>
														<tr>
															<th style="border: 0 !important; background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="2">Movimientos Banco</th>
														</tr>
														<tr><th colspan="2" style="font-size: 11px; white-space: normal;border-bottom: medium none;background-color:#6E6E6E;color:white;font-weight:bold;">Deslize los movimientos correspondientes al Mov. Poliza</th></tr>
													<thead>
													<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;"><div id="circuloingreso" style="display: inline-block"></div>Depositos</th></tr>
													<tr>
														<td>
															<div style='height:70px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																<table style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																	<?php	
																	if($pendiente3){
																		while($row = $pendiente3->fetch_assoc()) {
																			if($row['abonos']>0){
																				echo "<tr><td >";
																				echo "	<li id=".$row['id']."  value=".$row['id']." class=\"out\"   ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style='background: #FFBF00'>
																				[".$row['fecha']."]-[".$row['folio']."]-[".$row['concepto']."]-[".number_format($row['abonos'],2,'.',',')."]</li>";
																				echo  "</td></tr>";
																			}
																		}	
																	}?>
																</table>
															</div>
														</td>
													</tr>
													<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;"><div id="circuloegreso" style="display: inline-block"></div>Retiros</th></tr>
													<tr>
														<td>
															<div style='height:70px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																<table  style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																	<?php	
																	if($pendiente2){
																		while($row = $pendiente2->fetch_assoc()) {
																		 
																			if($row['cargos']>0){
																				echo "<tr><td >";
																				echo "<li id=".$row['id']." value=".$row['id']." class=\"out\"  ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style=' background: #5882FA'>
																				[".$row['fecha']."]-[".$row['folio']."]-[".$row['concepto']."]-[".number_format($row['cargos'],2,'.',',')."]</li>";
																				echo  "</td></tr>";
																			}
																		}
																	}?>
																</table>
															</div>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php if( isset($_SESSION['Nohaypolizas']) ){ ?>	
							<div class="row">
								<div class="col-md-12">
									<label style="color: red" class="text-center">No tiene Documentos correspondientes a la cuenta bancaria</label>
								</div>
							</div>
							<?php } ?>
							<div class="row" style="display: none" id="loadsuma">
								<div class="col-md-12">
									<label class="text-center">Espere un momento...</label>
								</div>
							</div>
						</section>
                	</div>
                </div>
            </div>
            <div class="modal-footer">
            	<div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <input type="button" value="Conciliar Movimientos" class="btn btn-primary btnMenu" id="conciliarmovsuma">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
