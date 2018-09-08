<div id="volverdescartar" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Movimientos sin conciliar para descartar</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col-md-12">
                		<div class="row">
							<div class="col-md-12">
								<p class="bg-danger">
									<span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'>
            						</span>Seleccione el rango de fechas de movimientos de polizas para descartar.
            					</p>
							</div>
						</div>
						<div class="row">
							<form action="index.php?c=conciliacionAcontia&f=volverAdescartar" method="post">
								<div class="col-md-3">
									<label>Desde:</label>
									<input type="hidden" name="nameejercicio2"  value="<?php echo $_SESSION['datos']['idejercicio']; ?>"/>
									<input type="hidden" name="periodo2" value="<?php echo $_SESSION['datos']['periodo']; ?>"/>
									<input  type="date" class="form-control" id="desde2" name="desde2"/>
								</div>
								<div class="col-md-3">
									<label>Hasta:</label>
									<input type="hidden" value="<?php echo $_SESSION['datos']['idbancaria'];?>"  name="idbancaria2"/>
									<input  type="date" class="form-control" id="hasta2" name="hasta2"/>
								</div>
								<div class="col-md-3">
									<label>&nbsp;</label>
									<input type="submit" value="Consultar" class="btn btn-primary btnMenu">
								</div>
							</form>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table width="99%" class="listacon">
										<thead>
											<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
												<th align="center">Fecha</th>
												<th align="center">No. Poliza</th>
												<th align="center">Referencia</th>
												<th align="center">Concepto</th>
												<th align="right">Cargos</th>
												<th align="right">Abonos</th>
												<th style='font-weight:bold;font-size:9px;text-align:center;color: black'>
													<button id="todos" onclick="buttonclick('descartar')">Todos</button>
													<button id="todos" onclick="buttondesclick('descartar')">Desmarcar</button>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php $cont=1;
												foreach ($_SESSION['movpolizasdescartardenuevo'] as $row){?>
												<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'"  >
															<td align="center"><?php echo $row['fecha'];?></td>
															<td align="center"><?php echo $row['numpol'];?></td>
															<td align="center"><?php echo $row['numero'];?></td>
															<td align="center"><?php echo $row['concepto'];?></td>
															<td align="right"><?php echo $row['cargo'];?></td>
															<td align="right"><?php echo $row['abono'];?></td>
															<td align="right">
																<input title='r' type='radio' name='radio-<?php echo $cont;?>' id='descartar-<?php echo $cont;?>' value='<?php echo $row['idmov'];?>' class='descartar'>
															</td>
														</tr>
												<?php $cont++; } 
												if(isset($_SESSION['movpolizasdescartarnuevosinpoliza'])){
													if($_SESSION['movpolizasdescartarnuevosinpoliza']==0){
													?>
													<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'"  >
															<td colspan="7" align="center">NO TIENE POLIZAS EN ESE RANGO DE FECHAS</td>
													</tr>
												<?php }} 
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row" style="display:none" id="loaddescar">
							<div class="col-md-12">
								<label class="text-center">Espere un momento...</label>
							</div>
						</div>
                	</div>
                </div>
            </div>
            <div class="modal-footer">
            	<div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <input type="button" value="Descartar" class="btn btn-primary btnMenu" id="finconciliacion" onclick="descartar()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
