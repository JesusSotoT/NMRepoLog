<!DOCTYPE >
<html>
	<head>
		<meta charset="utf-8">
  		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
  		<script type="text/javascript" src="js/confiprenomina.js"></script>
  		<link rel="stylesheet" type="text/css" href="css/confi.css" />

	</head>
	<body><br>
	<div class="container well">
		<div class="panel panel-warning">
      		<div class="panel-heading">
			Deslice el o los conceptos que desea que aparezcan en la captura de la prenómina
			</div>
		</div>
			<section>
				<div class="col-md-6">

					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva" align="center">
							<table id="" class="table">
								<thead>
									<tr>
										<th style="text-align:center; border: 0 !important; background-color:#0B173B;color:white;font-weight:bold;height:30px;" colspan="2" align="center">Conceptos </th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th style="background-color:#81BEF7;color:black;font-weight:bold;"><div id="circulopercepciones" style="display: inline-block"></div>Percepciones</th>
									</tr>
									<tr>
										<td>
											<div style="height:100px;overflow:scroll;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
											<?php while ($p = $percepciones->fetch_object()){?>
													<li id="lista<?php echo $p->idconcepto; ?>" value="<?php echo $p->idconcepto; ?>" class="out"  ondragstart="dragStart(event)" ondrag="dragging(event)" draggable='true' style=' background:#6E6E6E;color: #F0F0F0'>
													<?php echo $p->concepto." - ".$p->descripcion; ?>
													 ( <input type="checkbox" value="0" onclick="valorconf(<?php echo $p->idconcepto; ?>)"  title="Valor" id="valor<?php echo $p->idconcepto; ?>" />Valor
													<input type="checkbox" value="0" onclick="importeconf(<?php echo $p->idconcepto; ?>)" title="Importe" id="importe<?php echo $p->idconcepto; ?>"/>Importe )
													</li>
											<?php } ?>
											</div>
										</td>
									</tr>
									<tr>
										<th style="background-color:#81BEF7;color:black;font-weight:bold;height:30px;"><div id="circulodeducciones" style="display: inline-block"></div>Deducciones</th>
									</tr>
									<tr>
										<td>
											<div style="height:100px;overflow:scroll;word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
											<?php while ($p = $deduciones->fetch_object()){?>
													<li id="lista<?php echo $p->idconcepto; ?>" value="<?php echo $p->idconcepto; ?>" class="out"  ondragstart="dragStart(event)" ondrag="dragging(event)" draggable='true' style=' background: #A4A4A4'>
													<?php echo $p->concepto." - ".$p->descripcion; ?>
													 ( <input type="checkbox" value="0" onclick="valorconf(<?php echo $p->idconcepto; ?>)"  title="Valor" id="valor<?php echo $p->idconcepto; ?>"/>Valor
													<input type="checkbox" value="0"  onclick="importeconf(<?php echo $p->idconcepto; ?>)" title="Importe" id="importe<?php echo $p->idconcepto; ?>"/>Importe )
													</li>
											<?php } ?>

											</div>
										</td>
									</tr>
									<tr>
										<th style="background-color:#81BEF7;color:black;font-weight:bold;height:30px;"><div id="circulobligaciones" style="display: inline-block"></div>Otro Pagos</th>
									</tr>
									<tr>
										<td>
											<div style="height:100px;overflow:scroll;word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
												
												<?php while ($p = $otrosp->fetch_object()){?>
													<li id="lista<?php echo $p->idconcepto; ?>" value="<?php echo $p->idconcepto; ?>" class="out"  ondragstart="dragStart(event)" ondrag="dragging(event)" draggable='true' style=' background: #D8D8D8;'>
													<?php echo $p->concepto." - ".$p->descripcion; ?>
													 ( <input type="checkbox" value="0" onclick="valorconf(<?php echo $p->idconcepto; ?>)"  title="Valor" id="valor<?php echo $p->idconcepto; ?>"/>Valor
													<input type="checkbox" value="0" onclick="importeconf(<?php echo $p->idconcepto; ?>)"  title="Importe" id="importe<?php echo $p->idconcepto; ?>"/>Importe )
													</li>
												<?php } ?>
											</div>
										</td>
									</tr>
							
								</tbody>
							</table>
							
						</div>
					
			
					</section>
					<section>
							<div class="col-md-6" align="center">
								<table id="" class="table">
									<thead>
										<tr>
											<th style="text-align:center; border: 0 !important; background-color:#0B173B;color:white;font-weight:bold;height:30px;" colspan="2" align="center">Conceptos en la prenómina</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
								<div id="todos" data-role="asignados" data-value="1" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;height:300px;width: 510px">
									
												<?php while ($p = $existente->fetch_object()){ 
														if($p->idtipo == 1){ $color = "background:#6E6E6E;color: #F0F0F0"; }
														if($p->idtipo == 2){ $color = " background: #A4A4A4"; }
														if($p->idtipo == 4){ $color = "  background: #D8D8D8;"; }
														if($p->valor == 1){ $checkva = "checked"; $val1 = 1; }else{ $checkva = ""; $val1 = 0; }
														if($p->importe == 1){ $checkimp = "checked"; $val2 = 1; }else{ $checkimp = ""; $val2 = 0;}
														
													?>
													<li id="lista<?php echo $p->idconcepto; ?>" value="<?php echo $p->idconcepto; ?>" class="out"  ondragstart="dragStart(event)" ondrag="dragging(event)" draggable='true' style=' <?php echo $color; ?>'>
													<?php echo $p->concepto." - ".$p->descripcion; ?>
													 ( <input type="checkbox" value="<?php echo $val1;?>" onclick="valorconf(<?php echo $p->idconcepto; ?>)"  title="Valor" id="valor<?php echo $p->idconcepto; ?>" <?php echo $checkva;?>/>Valor
													<input type="checkbox" value="<?php echo $val2;?>" onclick="importeconf(<?php echo $p->idconcepto; ?>)"  title="Importe" id="importe<?php echo $p->idconcepto; ?>" <?php echo $checkimp;?>/>Importe )
													</li>
												<?php } ?>				
								</div>
													
							</div>
							<div class="col-md-6" align="center">
								<button type="button" style="" class="btn btn-danger btn-xl" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" id="obteneromision">Obtener configuración por omisión</button>
							</div>
						</section>
						
						<div class="col-md-12" align="">
							<div class="col-md-6" align="">
								<input type="checkbox" name="omision" value="0" id="omision" onclick="omision()"/>
								<b>Guardar configuración por omisión.</b>
							</div>
							
						</div>
						
						<div class="col-md-12" align="right">
							<hr>
							<button type="button" style="" class="btn btn-primary btn-xl" data-loading-text="<i class='fa fa-refresh fa-spin '></i>" id="guardar">Guardar</button>
						</div>
				</div>
				
			</div>
			
						
						
				
		
		</div>
	</body>
</html>