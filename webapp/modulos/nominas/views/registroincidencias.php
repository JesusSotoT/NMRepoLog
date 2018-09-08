		<!DOCTYPE html>
		<head>
			<meta charset="utf-8">
			<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
			<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
			<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
			<script type="text/javascript" src="js/registroincidencias.js"></script>
			<link   rel="stylesheet" type="text/css" href="css/prenomina.css" /> 
			<link   rel="stylesheet" type="text/css" href="css/estilomodal.css" />
			<script src="../../libraries/dataTable/js/datatables.min.js"></script>
			<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
			<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css"> 
			<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
		</head> 
		<body> 
			<div>
				<form class="form-inline" id="forminci" action="ajax.php?c=registroincidencias&f=almacenaincidencia" method="post" >
					<!-- Modal -->
					<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" >
						<div class="modal-dialog">
							<!-- Contenido del modal-->
							<div class="modal-content" style="padding:20px;">
								<!-- <div class="panel-body modal-header"> -->
								<div class="modal-header-success alert-info encabemodal" >
									<h4 class="modal-title" style="text-align: left;height:30px;margin-left:5px;margin-top: 3px;font-size: 16px;">
										Selección de días y horas
										<button type="button" class="close panelTitleTxt glyphicon glyphicon-remove" data-dismiss="modal" id="btnCerrar" aria-hidden="true" style="margin-top: 2.5px;margin-right:5px;";></button>
									</h4>
									<!-- </div> -->
								</div> 
								<br>
								<br>
								<!-- Calendario icono -->
								<div class="row">
									<div class="col-md-2">
										<img src="images/calendario.png" style="width:75px;height: 75px">
									</div>
									<div class="col-md-10">
										<div class="row">
											<div class="col-md-12">
												<h>Seleccione la(s) incidencia(s) y capture su valor respectivo.</h6>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<label>Selección</label>
													<input type="text" style="height: 25px" class="form-control" name="seleccion" id="seleccion" disabled value="">
												</div> 
											</div>
										</div>
									</div>
									<br>  
									<!--</div>-->
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="clave"  id="clave"></div>
									</div>
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="idIncidencia" id="idtipoincidencia"></div>
									</div>
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="fecha"  id="fecha"></div>
									</div>
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="idnomp"  id="idnomp"></div>
									</div>
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="idempleado"  id="idempleado"></div>
									</div>	 
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="hdnfechainicio"  id="hdnfechainicio"></div>
									</div>
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="hdneditable" id="hdneditable"></div>
									</div> 
									<div class="row">
										<div class="col-md-12 col-md-offset-3" value=""><input type="hidden" name="hdnfechafin" id="hdnfechafin"></div>
									</div>
									<!-- <div class="row alert-info" style="padding:15px;background-color:#EEE;margin-top:1.3em;">  -->
									<div class="" width="100%"><FONT SIZE=3 COLOR="black"><b>Tipos de incidencia</b></font></div> 
									<div class="table-responsive alert alert-info">
										<table class="table table-sm table table-fixed table-hover table-bordered" width="100%">
											<thead style="background-color: rgb(110,110,110);color: #FFFFFF;" title="Deslizar para ver mas...">
												<tr>
													<th align="center">CLAVE</th>
													<th align="left" width="200px"> DESCRIPCIÓN</th>
													<th align="left">TIPO</th>
													<th align="left">VALOR</th>
												</tr>
											</thead>
											<tbody id="tablainci">
											</tbody>
										</table>   
									</div>
									<br>
									<div class="row">
										<div class="col-md-5 col-md-offset-7" style="margin-right: 3em">               
											<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times"></span> Cancelar</button>
											<button type="button" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-refresh fa-spin'></i>"><span class="glyphicon glyphicon-floppy-disk" style="align-content: right" disabled="false"></span> Aceptar</button>
										</div>
									</div>
									<br>
								</div>
							</div>    
						</div>
					</div>
					<!--TERMINA MODAL-->
					<!--Pantalla principal-->
					<div class="container well" style="width: 95%;height:100%;">
						<h3 align="center">Cat&aacutelogo Incidencias</h3><hr> 
						<h4 align="left" style="margin-left: 30px;
						border-bottom: 1px solid rgb(200, 200, 200";>PERIODO</h4> 
						<section>
							<div  class="col-md-3" style="overflow: auto;height: 200px; "> <!--height:alrura para el listado de las fechas del periodo-->
								<ul class="nav " >
									<?php 
									if($nominasPeriodo->num_rows>0){
										while ($p = $nominasPeriodo->fetch_object() ){?> 
										<li>
											<a data-toggle="tab" href="" class=" <?php echo ($p->clasedeperiodo); ?>" onclick="javascript:traerFechas(<?php echo $p->idnomp.",'".$p->fechainicio."','".$p->fechafin."',".$p->autorizado.",".$p->editable; ?> )">
												<?php echo $p->numnomina."  .-   (".$p->fechainicio." - ".$p->fechafin.")"; ?>
											</a>
										</li>
										<?php }
									}else{?>
									<li>
										<a data-toggle="tab" href="">
											No tiene fechas de periodos
										</a>
									</li>
									<?php } ?>
								</ul>
							</div>
						</section>
						<section>
							<div class="col-md-9 alert alert-info">
								<table id="tablaincidencias" cellpadding="0" class="table  nowrap table-striped table-over table-bordered" style="border:solid 1px;"  width="100%">
									<thead class="" title="Deslizar para ver mas..." >
										<tr style="background: #6E6E6E; color: #F5F7F0" align="center">
											<td  align="center" id="tdp" colspan="16"  style="background:#6E6E6E; color: #F5F7F0;border:solid 0px;"; >
												<b id="periodo" style="font-size:14px;width:100%";>PERIODO</b>
											</td>
										</tr>
										<tr style="border:solid 0px;background:#6E6E6E; color:#F5F7F0;font-size: 12px;" id="trHeader">
											<th><b>CÓDIGO EMPLEADO</b></th>
											<th><b>NOMBRE EMPLEADO</b></th>
										</tr>
									</thead>
									<tbody class="" id="contenidop" style="font-size: 12px;">
										<!--'contenidop' trae la informacion de los empleados-->
									</tbody> 
								</table>
							</div>
						</section>
					</div>
				</div>
			</form>
		</div>
		<div id="menu" style="display: none">
			<ul>
				<li id="sobre" >Abrir Sobre-Recibo</li>
				<li id="emple" >Ver empleado</li>
			</ul>
		</div>
	</body>
	<script type="text/javascript">
		var table = $('#tablaincidencias').DataTable();
		table.destroy();
		setTimeout(function(){
			$('#tablaincidencias').DataTable( {
				"language": {
					"url": "js/Spanish.json"
				}
			})
		}, 1000);

	</script>
	</html>
