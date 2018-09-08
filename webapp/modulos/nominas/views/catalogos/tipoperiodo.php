	<!DOCTYPE html>
	<head>

		<meta charset="utf-8">
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
		<script type="text/javascript" src="js/tipoperiodo.js"></script>
	</head>
	<body>
		<script>
			$(document).ready(function(){
				$("#ajustemes").val(1).selectpicker('refresh');
				
				<?php 
				if(isset($datos)){?>
					$("#ajustemes").val(<?php echo $datos->ajustemes;?>).selectpicker('refresh');;
					<?php
					$funcion = "&opc=0"; 
					if($datos->extraordinario == 1){?>
						$("#extra").attr("checked",true);
						periodoExt();	
				<?php	
					}
					
				}else{
					$funcion = "&opc=1"; 
				}
				?>
			});
		</script>
		<div class="container">
			<form id="formtipo" action="ajax.php?c=Catalogos&f=almacenaTipop<?php echo $funcion;?>" method="post">
				<input type="hidden" value="<?php if(isset($datos)){ echo $datos->idtipop; } ?>" id="idtipop" name="idtipop"/>
				<input type="hidden" value="<?php if(isset($datos)){ echo $datos->diasperiodo; } ?>" id="diasperiodofijo" name="diasperiodofijo"/>

				<ul class="nav nav-tabs">
					<li>
						<a data-toggle="tab" href="">
							<a data-toggle="tab"  href=""  onclick="atraslistado()" title="Regresar listado">
								<i class="fa fa-arrow-left" aria-hidden="true"  ></i> Regresar
							</a>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="">
							<a data-toggle="tab"  href=""  onclick="Guardar()" title="Guardar concepto">
								<i class="fa fa-floppy-o" aria-hidden="true" id="guarda" ></i> Guardar
								<i class='fa fa-refresh fa-spin ' id="carga" style="display: none"></i>
							</a>
						</a>
					</li>

				</ul>

				<div class="tab-content"><br><br>
					<div id="general" class="tab-pane fade in active">

						<div class="alert alert-warning">
							<section>
								<div class="col-md-12" >
										<input type="checkbox" name="extra" id="extra"  onclick="periodoExt()"/><b style="font-size: 18px;color:red">Periodo Extraordinario</b>
										<input type="hidden" name="extrahidden" id="extrahidden" value="0"/>
									<br><br>
								</div>
								<div class="row">
									<div class="col-md-12">
									<div class="col-xs-3">
									Nombre:<b style="color:red">*</b>
									<input type="text" id="nombre" name="nombre" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->nombre; }  ?>"/>
								</div>
								<div class="col-xs-3 pex">
										Dias del periodo:<b style="color:red">*</b>
										<input type="text" id="diasperiodo" maxlength="3" onkeydown="cambioDias(this);" name="diasperiodo" class="solo-numero form-control input-md"  value="<?php if(isset($datos)){ echo $datos->diasperiodo; }  ?>"/>
								</div>
								<div class="col-xs-3">
									Fecha de inicio del ejercicio:<b style="color:red">*</b>
									<input type="date" id="fechainicio"  onchange="validarfechaperiodos()" name="fechainicio" readonly="readonly" placeholder="Fecha de inicio"  class="form-control"   value="<?php if(isset($datos)){ echo $datos->fechainicio;}?>" />
									<input type="hidden" id="hdnFechaInicio" value="<?php  if(isset($fechaInicioPeriodo)){ echo $fechaInicioPeriodo; }   ?>"/>
									</div>				
					   <div class="col-xs-3 pex">
							Dias de pago:
							<input type="text" id="diaspago" name="diaspago" onkeydown="cambioDias(this);" maxlength="4" onkeypress="return NumDec(event,this)" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->diaspago; }  ?>"/>
													</div>
									</div>
								</div>
								<br> 
								<div class="row pex" id="">
									<div class="col-md-12">
										<div class="col-xs-3">
											Periodo de trabajo:
											<input type="text" id="periodotrabajo" name="periodotrabajo" class="solo-numero form-control input-md"  maxlength="8" value="<?php if(isset($datos)){ echo $datos->periodotrabajo; }  ?>"/>
										</div>
										<div class="col-xs-3">
											Posicion de los septimos dias:
											<input type="text" id="septimodia" name="septimodia" class="solo-numero form-control input-md"  value="<?php if(isset($datos)){ echo $datos->septimodia; }  ?>"/>
										</div>
										<div class="col-xs-3">
											Posicion del dia de pago:
											<input type="text" id="diapago" name="diapago" class="solo-numero form-control input-md"  value="<?php if(isset($datos)){ echo $datos->diapago; }  ?>"/>
										</div>
										<div class="col-xs-3">
											Periodicidad de pago:
											<select id="idperiodicidad" name="idperiodicidad" class="selectpicker" data-width="100%" data-live-search="true">
												<?php while ($e = $periodicidad->fetch_object()){ $f="";
												if(isset($datos)){ if ($e->idperiodicidad == $datos->idperiodicidad ){  $f="selected";} } ?>
												<option value="<?php echo $e->idperiodicidad;?>" <?php echo $f; ?>><?php echo $e->clave." ".$e->descripcion;?> </option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<br>
								<div class="row pex">
									<div class="col-md-12">
										<div class="col-xs-3">
											Ajustar al mes calendario:
											<select id="ajustemes" name="ajustemes" onchange="tipoperiodos(this.value)" class="selectpicker" data-width="100%" data-live-search="true" >
												<option value="1" >SI</option>
												<option value="2" >NO</option>
											</select>
										</div>
										<div class="col-xs-3" align="center">
											Ajuste de dias pagados en quincenas de 16 dias o febrero:
										</div>
										<div class="col-xs-3"><br>
											<select id="idajuste" name="idajuste" class="selectpicker" data-width="100%" data-live-search="true" >
												<?php while ($e = $ajusteDias->fetch_object()){ $f="";

												if(isset($datos)){ if ($e->idajuste == $datos->idajuste ){  $f="selected";} 
													if( $datos->idajuste == 1){ ?>
													<script type="text/javascript">
													$(document).ready(function(){
														$('#idajuste').prop('disabled',true);
													});
													</script>
												<?php	}else{ ?>
														<script type="text/javascript">
														$(document).ready(function(){
															$('#idajuste').prop('disabled',false);
																});
														</script>
												<?php		}
												} 
												?>
												<option value="<?php echo $e->idajuste;?>" <?php echo $f; ?>><?php echo $e->nombre;?> </option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>
				</div>
			</body>
			</html>