<!DOCTYPE html>
<html>
<head>
	<title>Diagnostico de madurez</title>
	<link href="../../libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../libraries/sweetalert/css/sweetalert.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=0.5">
	<style type="text/css">
		.row {
			margin-bottom: 1em;
			margin-top: 1em;
		}

		.btn {
			width: 100% !important;
			padding: 1em;
		}
	</style>
</head>
<body>
	<form id="formulariocuatro">
		<!--Recursos humanos-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<h3 class="panel-title" align="center">RECURSOS HUMANOS</h3>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

	    <!--Pregunta 1 -->
	    <div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Tiene contratos de cada uno de sus empleados?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p1a" id="f4p1a" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p1a" id="f4p1b" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p1b" name="f4p1b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 2 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Capacita a sus empleados en las actividades que debe desempeñar?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p2a" id="orp2si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p2a" id="orp2no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p2b" name="f4p2b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 3 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Sus empleados tienen prestaciones?</label>
				            	<div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >IMSS</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p3r1a" id="orp3imsssi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p3r1a" id="orp3imssno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p3r1b" name="f4p3r1b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >INFONAVIT</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p3r2a" id="orp3infosi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p3r2a" id="orp3infono" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p3r2b" name="f4p3r2b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >VACACIONES</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p3r3a" id="orp3vacasi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p3r3a" id="orp3vacano" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p3r3b" name="f4p3r3b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;">PRIMA VACACIONAL</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p3r4a" id="orp3primavsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p3r4a" id="orp3primavno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p3r4b" name="f4p3r4b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >PRIMA DOMINICAL</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p3r5a" id="orp3primadsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p3r5a" id="orp3primadno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p3r5b" name="f4p3r5b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >AGUINALDO</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p3r6a" id="orp3aguisi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p3r6a" id="orp3aguino" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p3r6b" name="f4p3r6b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >REPARTO DE UTILIDADES</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p3r7a" id="orp3utilsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p3r7a" id="orp3utilno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p3r7b" name="f4p3r7b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 4 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿A sus empleados les entrega recibo de nomina?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p4a" id="orp4si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p4a" id="orp4no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p4b" name="f4p4b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 5 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con horarios de entrada y salida, así como un sistema de registro?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p5a" id="orp5si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p5a" id="orp5no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p5b" name="f4p5b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 6 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿De cuantas horas son sus jornadas laborales por semana?</label> 
				            	<div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >de 24 a 30 </label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p6r1a" id="orp6asi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p6r1a" id="orp6ano" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p6r1b" name="f4p6r1b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >de 31 a 40 </label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p6r2a" id="orp6bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p6r2a" id="orp6bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p6r2b" name="f4p6r2b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >de 41 a 48 </label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p6r3a" id="orp6csi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p6r3a" id="orp6cno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p6r3b" name="f4p6r3b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >más de 48 </label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p6r4a" id="orp6dsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p6r4a" id="orp6dno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p6r4b" name="f4p6r4b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 7 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Respeta los dias de asueto?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p7a" id="orp7si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p7a" id="orp7no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p7b" name="f4p7b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 8 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con reglamento interno (Comportamientos, politicas de trabajo, horarios, codigos de imagen y vestido, codigo de conducta)?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p8a" id="orp8si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p8a" id="orp8no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p8b" name="f4p8b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 9 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Aplica actas administrativas si el empleado incumple el reglamento interno?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p9a" id="orp9si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p9a" id="orp9no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p9b" name="f4p9b" placeholder="Comentarios"></textarea>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 10 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Mide y retroalimenta el desempeño de sus colaboradores?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p10a" id="orp10si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p10a" id="orp10no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p10b" name="f4p10b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 11 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Gratifica a sus colaboradores de acuerdo a su desempeño de manera economico para motivarlos?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p11a" id="orp11si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p11a" id="orp11no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p11b" name="f4p11b" placeholder="Comentarios"></textarea>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Operaciones -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<h3 class="panel-title" align="center">OPERACIONES</h3>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

	    <!--Pregunta 12 -->
	    <div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con manuales de operación de sus principales procesos?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p12a" id="orp12si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p12a" id="orp12no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p12b" name="f4p12b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 13 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con estándares de operación de cada proceso</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p13a" id="orp13si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p13a" id="orp13no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p13b" name="f4p13b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 14 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con alguna herramienta para medir el nivel de operación de sus actividades?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p14a" id="orp14si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p14a" id="orp14no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p14b" name="f4p14b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 15 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con métricas y monitoreos de sus principales procesos?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p15a" id="orp15si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p15a" id="orp15no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p15b" name="f4p15b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 16 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con un sistema de asignación de tareas?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p16a" id="orp16si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p16a" id="orp16no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p16b" name="f4p16b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Operaciones -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<h3 class="panel-title" align="center">COMERCIAL</h3>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

	    <!--Pregunta 17 -->
	    <div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con un logotipo?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p17a" id="orp17si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p17a" id="orp17no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p17b" name="f4p17b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 18 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Su logotipo esta en formato editable?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p18a" id="orp178si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p18a" id="orp18no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p18b" name="f4p18b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 19 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Su logotipo y marca estan registrados en el IMPI?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p19a" id="orp19si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p19a" id="orp19no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p19b" name="f4p19b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 20 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con tarjetas de presentación?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p20a" id="orp20si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p20a" id="orp20no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p20b" name="f4p20b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 21 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con correo institucional (@nombredelnegocio.com)?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p21a" id="orp21si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p21a" id="orp21no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p21b" name="f4p21b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 22 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con papelería institucional (hoja membretada, firma electrónica, sellos)?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p22a" id="orp22si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p22a" id="orp22no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p22b" name="f4p22b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 23 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con material de comunicación dentro de su negocio (posters, pegatinas, displays, etc.)?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p23a" id="orp23si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p23a" id="orp23no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p23b" name="f4p23b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 24 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con uniformes, material de empaque con su logo?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p24a" id="orp24si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p24a" id="orp24no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p24b" name="f4p24b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 25 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con algun especialista de diseño grafico?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p25a" id="orp25si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p25a" id="orp25no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p25b" name="f4p25b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 26 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con promociones de sus productos?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p26a" id="orp26si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p26a" id="orp26no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p26b" name="f4p26b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 27 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Utiliza medios de publicidad?</label>
				            	<div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Radio</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r1a" id="orp27radsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r1a" id="orp27radno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r1b" name="f4p27r1b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Volantes</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r2a" id="orp27volsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r2a" id="orp27volno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r2b" name="f4p27r2b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Periodico/Revistas</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r3a" id="orp27persi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r3a" id="orp27perno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r3b" name="f4p27r3b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Expos y Eventos</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r4a" id="orp27expsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r4a" id="orp27expno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r4b" name="f4p27r4b"  placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Mailing</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r5a" id="orp27mailsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r5a" id="orp27mailno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r5b" name="f4p27r5b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Whatsapp</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r6a" id="orp27whatsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r6a" id="orp27whatno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r6b"  name="f4p27r6b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Facebook</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r7a" id="orp27facesi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r7a" id="orp27faceno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r7b"  name="f4p27r7b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Google</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r8a" id="orp27googsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r8a" id="orp27googno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r8b" name="f4p27r8b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Otros</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p27r9a" id="orp27otrosi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p27r9a" id="orp27otrono" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p27r9b" name="f4p27r9b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 28 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Paga por publicidad?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p28a" id="orp28si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p28a" id="orp28no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p28b" name="f4p28b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
				         <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				            	<div class="input-group">
      								<div class="input-group-addon">$</div>
     									 <input type="text" class="form-control" id="f4p28c"  name="f4p28c" placeholder="Costo">
     								<div class="input-group-addon"></div>
    							</div>
      						</div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 29 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Con que frecuencia se publicita?</label>
				            	<div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Nunca </label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p29r1a" id="orp29asi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p29r1a" id="orp29ano" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p29r1b" name="f4p29r1b"  placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Cada año </label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p29r2a" id="orp29bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p29r2a" id="orp29bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p29r2b" name="f4p29r2b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;">Cada mes </label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p29r3a" id="orp29csi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p29r3a" id="orp29cno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p29r3b" name="f4p29r3b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Cada semana</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p29r4a" id="orp29dsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p29r4a" id="orp29dno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p29r4b" name="f4p29r4b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
						         <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Todos los días</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p29r5a" id="orp29esi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p29r5a" id="orp29eno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p29r5b" name="f4p29r5b" placeholder="Comentarios"></textarea>
										</div>
						            </div>
						        </div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 30 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con pagina de internet?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p30a" id="orp30si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p30a" id="orp30no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p30b" name="f4p30b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 31 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con ventas en linea (Mecadolibre, Pagina propia, etc.)?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p31a" id="orp31si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p31a" id="orp31no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p31b" name="f4p31b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 32 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con servicio a domicilio?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p32a" id="orp32si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p32a" id="orp32no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p32b" name="f4p32b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 33 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con buzón de quejas y sugerencias?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p33a" id="orp33si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p33a" id="orp33no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p33b" name="f4p33b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 34 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con una linea telefónica de atención y ventas?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p34a" id="orp34si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p34a" id="orp34no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p34b" name="f4p34b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 35 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con convenios con otras empresas?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p35a" id="orp35si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p35a" id="orp35no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p35b" name="f4p35b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 36 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con base de datos de sus clientes?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p36a" id="orp36si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p36a" id="orp36no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p36b" name="f4p36b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 37 -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con diferentes formas de pago?</label>  
				            	<div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Efectivo</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r1a" id="orp37asi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r1a" id="orp37ano" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r1b" name="f4p37r1b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Tarjeta de debito</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r2a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r2a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r2b" name="f4p37r2b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Tarjeta de credito a meses sin intereses</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r3a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r3a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r3b" name="f4p37r3b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Tarjeta de credito a meses con intereses</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r4a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r4a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r4b" name="f4p37r4b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Vales de despensa</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r5a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r5a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r5b" name="f4p37r5b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Depositos en el oxxo, 7eleven, etc.</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r6a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r6a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r6b" name="f4p37r6b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Tranferencia bancaria</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r7a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r7a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r7b" name="f4p37r7b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Pago por internet (Paypal, etc.)</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r8a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r8a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r8b" name="f4p37r8b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						        <div class="row">
					            	<div class="col-sm-4 col-md-4" align="left">
						                <div class="radio">
											<label style="vertical-align: middle;" >Credito Propio</label>
										</div>
						            </div>
					            	<div class="col-sm-4 col-md-4" align="center">
						                <div class="radio">
											<label style="margin-right: 3em">
											    <input type="radio" name="f4p37r9a" id="orp37bsi" value="si" checked>
													Si
											</label>
											<label>
											    <input type="radio" name="f4p37r9a" id="orp37bno" value="no">
												    No
											</label>
										</div>
						            </div>
						            <div class="col-sm-4 col-md-4">
						                <div class="radio">
											<textarea class="form-control" rows="1" id="f4p37r9b" name="f4p37r9b" placeholder="Comentarios"></textarea>
										</div>
						        	</div>
						        </div>
						     </div>
						 </div>
					</div>
				</div>
			</div>
		</div>
	    	
		<!--pregunta 38-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con tarjeta de clientes frecuentes?</label>   
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p38a" id="orp38si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p38a" id="orp38no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p38b" name="f4p38b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

	    <!--Administrativo-->
	    <div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<h3 class="panel-title" align="center">ADMINISTRATIVO</h3>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

	    <!--Pregunta 39-->
	    <div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Tiene contador?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p39a" id="orp39si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p39a" id="orp39no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p39b" name="f4p39b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 40-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Lleva su contabilidad en tiempo y forma (cada mes)?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p40a" id="orp40si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p40a" id="orp40no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p40b" name="f4p40b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 41-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿El contador le presenta sus estados de resultados y balance general?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p41a" id="orp41si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p41a" id="orp41no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p41b" name="f4p41b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 42-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Sabe interpretar la información financiera de su negocio?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p42a" id="orp42si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p42a" id="orp42no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p42b" name="f4p42b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 43-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Presenta declaraciones de pago de impuestos?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p43a" id="orp43si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p43a" id="orp43no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p43b" name="f4p43b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 44-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con estrategias fiscales?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p44a" id="orp44si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p44a" id="orp44no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p44b"  name="f4p44b"placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 45-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con sistema de facturación digital?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p45a" id="orp45si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p45a" id="orp45no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p45b" name="f4p45b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 46-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Tiene procedimientos para llevar el control de ventas, depositos en banco, etc.?</label>
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p46a" id="orp46si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p46a" id="orp46no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p46b" name="f4p46b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 47-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Lleva una relación de sus gastos/compras?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p47a" id="orp47si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p47a" id="orp47no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p47b" name="f4p47b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 48-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con contratos de prestación de servicios para sus proveedores y clientes?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p48a" id="orp48si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p48a" id="orp48no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p48b" name="f4p48b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 49-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con sistema de protección en ventas a credito (contrato, pagaré, referencias, aval, etc.)?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p49a" id="orp49si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p49a" id="orp49no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p49b" name="f4p49b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 50-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Su cartera de clientes a credito, tiene deudas?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p50a" id="orp50si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p50a" id="orp50no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p50b" name="f4p50b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 51-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con un abogado de servicios mercantiles?</label>   
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p51a" id="orp51si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p51a" id="orp51no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p51b" name="f4p51b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 52-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Realiza inventarios fisicos?</label>  
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p52a" id="orp52si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p52a" id="orp52no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p52b" name="f4p52b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Pregunta 53-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>¿Cuenta con formatos de responsabilidad de mercancias (cajeros, encargados de tienda/turno/almacen)?</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f4p53a" id="orp53si" value="si" checked>
											Si
									</label>
									<label>
									    <input type="radio" name="f4p53a" id="orp53no" value="no">
										    No
									</label>
								</div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
				                <textarea class="form-control" rows="3" id="f4p53b" name="f4p53b" placeholder="Comentarios"></textarea>
				             </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		   
	   </div>
	</form>
	<div class="panel panel-default">
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
					<button type="submit" class="btn btn-primary" id="btn-enviar">Enviar</button>
				</div>
			</div>
		</div>
	</div>

	<script src="../../libraries/jquery/js/jquery.min.js"></script>
   	<script src="../../libraries/sweetalert/js/sweetalert.min.js"></script>
   	<script src="../general.js"></script>
   	<script src="../../views/java_script/formulario_cuatro.js"></script>

</body>
</html>