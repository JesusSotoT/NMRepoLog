<div id="sumamov" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Sumar Movimientos a una Poliza</h4>
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
												<!--Tabla de contenido no conciliados bancos-->
												<table id="tmovbancos" class="table table-striped table-bordered" style="min-width: 520px;">
													<thead>
														<tr><th style="border: 0 !important; background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="6">Movimientos Polizas</th></tr>
														<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
															<th>Fecha</th>
															<th>Ref. Pago</th>
															<th>Concepto</th>
															<th>Cargos</th>
															<th>Abonos</th>
															<th>
															<input type="text" id="buscarvalor2" placeholder="Buscar..." align="right" style="color: black" size="12"/>

															</th>
														</tr>
													</thead>
													<tbody style="overflow: auto; display: inline-block; height: 20vw ! important;">
														<?php $cont2=0;
															foreach ($_SESSION['movpolizas'] as $row){ $cont2++; ?>
																
															<tr >
																<td style='word-wrap: break-word;' align="center"><?php echo $row['fecha'];?></td>
																<td style='word-wrap: break-word;' align="center"><?php echo $row['numero'];?></td>
																<td style='overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;' align="center"><?php echo $row['concepto'];?></td>
																<td style='word-wrap: break-word;' align="right"><?php echo $row['cargo'];?></td>
																<td style='word-wrap: break-word;' align="right"><?php echo $row['abono'];?></td>
																<td align="center">
																	<div id="bancos<?php echo $row['idmov'];?>" data-role="movpolizas" data-value="<?php echo $row['idmov'];?>" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;">
																	</div>
																</td>
															</tr>
														<?php  }?>
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
													<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;">Depositos</th></tr>
													<tr>
														<td>
															<div style='height:70px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																<table style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																	<?php	
																	foreach ($_SESSION['movbancos'] as $row){
																		if($row['deposito']>0){
																			echo "<tr><td >";
																			echo "	<li id=".$row['id']."  value=".$row['id']." class=\"out\"   ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style='background: #81BEF7'>
																			[".$row['fecha']."]-[".$row['numero']."]-[".$row['concepto']."]-Deposito[".$row['deposito']."]</li>";
																			echo  "</td></tr>";
																		}
																		
																	}?>
																</table>
															</div>
														</td>
													</tr>
													<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">Retiros</th></tr>
													<tr>
														<td>
															<div style='height:70px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																<table  style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
																	<?php	
																	foreach ($_SESSION['movbancos'] as $row){
																	 
																		if($row['retiro']>0){
																			echo "<tr><td >";
																			echo "<li id=".$row['id']." value=".$row['id']." class=\"out\"  ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style=' background: #819FF7'>
																			[".$row['fecha']."]-[".$row['numero']."]-[".$row['concepto']."]-Retiro[".$row['retiro']."]</li>";
																			echo  "</td></tr>";
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
									<label style="color: red" class="text-center">No tiene Movimientos correspondientes a la cuenta bancaria</label>
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
