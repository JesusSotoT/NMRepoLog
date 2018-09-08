<!DOCTYPE html>
<head>
	<meta charset="utf-8">
  	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
  	<script type="text/javascript" src="js/periodos.js"></script>
  	<link rel="stylesheet" href="css/style.css">

  	
</head>
<body><br>
<div class="container well">
	<h3 align="center"> Catalogos de Periodos</h3><hr>
	<section>
		<div class="col-md-3">
			<ul class="nav">
				<?php 
				if( $periodos->num_rows>0 ){
				while ( $p = $periodos->fetch_object() ){?>
		    		<li>
				    	<a data-toggle="tab" href="" onclick="javascript:listadoNominasxPeriodo(<?php echo ($p->idtipop); ?>,'<?php echo strtoupper($p->nombre); ?>')" id="prueba">
				    		<?php echo strtoupper($p->nombre); ?>
				    </a>
			    </li>
			   
		    		<?php }
		    		}else{?>
		    			<li>
				    		<a data-toggle="tab" href="">
				    			No tiene periodos
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<section>
		<div class="col-md-9"  >
				<div class="alert alert-info tablaResponsiva" >
				
						<table id="periodos" class="table table-bordered table-hover" >
							<thead title="Deslizar para ver mas...">
								<tr style="background: #6E6E6E; color: #F5F7F0">
									<td  colspan="3" align="center" id="tdp">
										<b id="periodo" style="font-size:14px">Nominas del Periodo</b>
									</td>
								</tr>
								<tr style="background: #6E6E6E; color: #F5F7F0">
									<td align="center"><b>Numero</b></td>
									<td align="center"><b>Fecha Inico</b></td>
									<td align="center"><b>Fecha Fin</b></td>
								</tr>
							</thead>
							<tbody style="overflow: auto; display: inline-block; height: 9.5vw ! important;"  id="contenidop">
							</tbody>
						</table>
					
					
					<div class="col-md-3">
						<input type="hidden" id="idnomina" name="idnomina"  />
						<input type="hidden" id="diasperiodo" name="diasperiodo"  />
						Numero<input type="text" name="numero" id="numero" class="form-control input-md" />
					</div>
					<div class="col-md-3">
						Fecha Inicio<input type="text" name="fechainicio" id="fechainicio"  class="form-control input-md" />
					</div>
					<div class="col-md-3">
						Fecha Fin<input type="text" name="fechafin" id="fechafin" class="form-control input-md" />
					</div>
					<div class="col-md-3">
						Dias de pago<input type="text" name="diaspago" id="diaspago" class="form-control input-md" />
					</div>
					
					<div class="col-md-12" ><br>
						<div class="col-md-3">
							<input type="checkbox" value="0" name="inimes" id="inimes" onclick="cambiocheck('inimes');">Inicio de mes
						</div>
						<div class="col-md-4">
							<input type="checkbox" value="0" name="inibimestre" id="inibimestre" onclick="cambiocheck('inibimestre');">Inicio de Bimestre IMSS
						</div>
						<div class="col-md-3">
							<input type="checkbox" value="0" name="iniejer" id="iniejer" onclick="cambiocheck('iniejer');">Inicio de Ejercicio
						</div>
					</div><br>
					<div class="col-md-12" >
						<div class="col-md-3">
							<input type="checkbox" value="0" name="finmes" id="finmes" onclick="cambiocheck('finmes');">Fin de mes
						</div>
						<div class="col-md-4">
							<input type="checkbox" value="0" name="finbimestre" id="finbimestre" onclick="cambiocheck('finbimestre');"> Fin de Bimestre IMSS
						</div>
						<div class="col-md-3">
							<input type="checkbox" value="0" name="finejer" id="finejer" onclick="cambiocheck('finejer');"> Fin de Ejercicio
						</div><br><br>
						<div class="col-xs-12" align="right">
						<button type="button" class="btn btn-primary" id="load"    data-loading-text="<i class='fa fa-refresh fa-spin '></i>"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
						</div>
					</div>
					
					
				</div>
				
		</div>
		
	</section>
</div>
</body>
</html>