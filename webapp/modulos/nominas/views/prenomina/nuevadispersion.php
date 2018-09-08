<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/dispersion.js"></script>
	<link   rel="stylesheet" href="css/bootstrap-datetimepicker.css">
	<link   href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<script type="text/javascript" src="js/moment.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src="../../libraries/numeral.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
	<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css"> 
	<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<link   rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script> 

	<script type="text/javascript">
		$(document).ready(function(){
			tpago=$("#pagot").val();
			$("#tipopago").val(tpago);
		});
	</script>

</head>
<body>

	<div class="container well">
	<div class="row" style="padding-left: 15px;">
<div>
<button class="btn btn-default" onclick="atraslistado()">
<i class="fa fa-arrow-left" aria-hidden="true"></i>
Regresar
</button>
<button id="guardar" class="btn btn-primary" data-loading-text="<i class='fa fa-refresh fa-spin '></i>" type="button">
<span class="glyphicon glyphicon-floppy-disk"></span>
Guardar
</button>
</div>
</div>
	<!-- <button class="btn btn-default" onclick="atraslistado()">
				<i class="fa fa-arrow-left" aria-hidden="true"></i>
				Regresar
			</button>
			<button id="guardar" class="btn btn-primary" data-loading-text="<i class='fa fa-refresh fa-spin '></i>" type="button">
				<span class="glyphicon glyphicon-floppy-disk"></span>
				Guardar
			</button> -->
		<form id="formdispersion" class="ocultos" method="post" action="index.php?c=Dispersion&f=cargaDeDatos">

			

			<div class="panel panel-default">
				<div class="panel-heading" style="height: 40px;">
					<div class="col-md-1">
						<h5 class="modal-title">Dispersión</h5>
					</div>
					<div class="col-md-11" style="padding-left: 30px;">
						<span class="label label-success">Nuevo</span>
					</div>
				</div>
				<div class="panel-body">
					<div class="form-group row">
						
						<label for="first_name" class="col-xs-2 col-form-label mr-2">Emisora</label>
						<div class="col-xs-4">
							<input  type="text" name="emisora" id="emisora" class="form-control numbersOnly"  maxlength="5" value=<?php echo @$_REQUEST['emisora'];?> >
						</div>
						<label for="consecutivo" class="col-xs-2 col-form-label mr-2" style="text-align: left;" >Consecutivo archivo</label>
						<div class="col-xs-4"> 
							<input type="text" name="consecutivo" id="consecutivo" class="form-control numbersOnly" maxlength="2" mask="00" value="<?php echo @$_REQUEST['consecutivo'];?>">
						</div>
					</div> 
					<div class="form-group row">
						<label for="txtfecha" class="col-xs-2 col-form-label mr-2">Fecha de aplicación</label>
						<div class="col-xs-4">
							<div class='input-group date' id='fecha'>
								<input type='text' name="txtfecha" id="txtfecha" class="form-control numbersOnly"  value=<?php echo @$_REQUEST['txtfecha'];?>>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div> 
						</div>
						<label for="tipopago" class="col-xs-2 col-form-label mr-2">Tipo de pago</label>
						<div class="col-xs-4"> 
							<select id="tipopago" name="tipopago" class="selectpicker" data-width="100%" data-live-search="true" value= <?php echo @$_REQUEST['tipopago'];?>>
								<option value="">Seleccione</option>
								<?php while ($e = $tipoPago->fetch_object()){ $es = "";
								if(isset($datos)){ if($e->idtipopago == $datos->idtipopago){ $es="selected"; }  } ?>  
								<?php echo @$_REQUEST['tipopago'];?>
								<option  maxlength="1" value="<?php echo $e->idtipopago;?>" <?php echo $es; ?>><?php echo $e->nombrepago;?> </option>
								<?php } ?>
							</select>
							<input type="hidden" name="pagot" id="pagot" value= <?php echo @$_REQUEST['tipopago'];?>>
						</div>
					</div>
					<div class="form-group row">
						<label for="cuentacargo" class="col-xs-2 col-form-label mr-2">Cuenta cargo</label>
						<div class="col-xs-4">
							<input type="text" name="cuentacargo"  id="cuentacargo" class="form-control numbersOnly" maxlength="10" value=<?php echo @$_REQUEST['cuentacargo'];?> >
						</div>
						<label for="fechainicio" class="col-xs-2 col-form-label mr-2">Fecha de adelanto (inicio)</label>
						<div class="col-xs-4"> 
							<div class='input-group date' id='fechainiciox'>
								<input type='text' id="fechainicio" class="form-control"  value=<?php echo date('Y-m-d')?> readonly>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div> 
						</div>
					</div> 

					<div class="form-group row">
						<label for="first_name" class="col-xs-2 col-form-label mr-2">Nominas autorizadas</label>
						<div class="col-xs-4">
							<select id="tipoperiodo" name="tipoperiodo" class="selectpicker" data-width="100%" data-live-search="true" value=<?php if (isset($_POST['tipoperiodo'])) echo $_POST['tipoperiodo']; ?> >
								<option value="*">Seleccione</option>
								<?php while ($e = $nominaAutorizada->fetch_object()){ $es = "";
								if(isset($datos)){ if($e->idnomp == $datos->idnomp){ $es="selected"; }  } ?>
								<option value=<?php echo $e->idnomp;?>><?php echo "($e->numnomina)"." ".$e->nombre."-->".$e->fechainicio." ".$e->fechafin;?></option>
								<?php } ?>
							</select>
							<input type="hidden" id="descperiodo" name="descperiodo" class="selectpicker btn-sm form-control" /> 
						</div>
						<label for="txtfechafin" class="col-xs-2 col-form-label mr-2">Fecha de adelanto (Fin)</label>
						<div class="col-xs-4"> 
							<div class='input-group date' id='fechafin'>
								<input type='text' id="txtfechafin" class="form-control numbersOnly"  disabled value=<?php echo date('Y-m-d')?>>
								<span class="input-group-addon"> 
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div> 
						</div>
					</div>
				</form>
				<?php 
				if ($_REQUEST['descperiodo'] !=""){
					echo"<div style='border: 2px solid rgb(180,191,193);'>";
					echo "<p style='padding-top: 10px; font-weight:bold;text-align:center;font-size:17.5px;' class='siz'>"."Periodo"." ".$_REQUEST['descperiodo']."</p>";
					echo"</div>";
				}
				?>
				<div class="alert alert-info wrap" cellspacing="0" width="100%" style="border: 1px solid rgb(217,237,247);">
					<table id="tablanueva" cellpadding="0" class="tablanueva table table-striped table-bordered" style="width: 100%" >
						<thead> 
							<tr style="background-color:#B4BFC1;color:#000000;">
								<th  style="width: 30px;font-weight:  bold;"></th>
								<th  style="width: 40px;font-weight:  bold; display: none"></th> 
								<th  style="width: 40px;font-weight:  bold">No.Empleado</th> 
								<th  style="width: 100px;font-weight: bold">Nombre</th>
								<th  style="width: 60px;font-weight:  bold">Importe</th>
								<th  style="width: 80px;font-weight:  bold">No.Banco receptor</th>
								<th  style="width: 60px;font-weight:  bold">Tipo de cuenta</th>
								<th  style="width: 60px;font-weight:  bold"> Cuenta</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(mysqli_num_rows($cargaDeDatos)!=0) {
								while($in = $cargaDeDatos->fetch_assoc()){
									?>
									<tr>
										<td style="width: 50px;">
											<?php if ($in['tipocuenta']==0){ ?>
											<input type="checkbox" style=" width:50px;
											height:20px;" class="check"  disabled="true">
											<?php }else{?>
											<input type="checkbox" style=" width:50px;
											height:20px;" class="check"  >
											<?php } ?>
										</td>
										<td style="width: 60px; display: none" class='tabledata idEmpleado'><?php echo $in['idEmpleado'];?></td>
										<td style="width: 60px;" class='tabledata'><?php echo $in['codigo'];?></td>
										<td style="width: 20px"  class='tabledata'><?php echo $in['nombreEmpleado'].' '.$in['apellidoPaterno'].' '.$in['apellidoMaterno'];?> 
										</td>
										<td style="width: 50;text-align: right"  class="importe tabledata"><?php echo number_format($in['total'],2,'.',',')?></td> 
										<td style="width: 50"  class="bancorecep tabledata"><?php echo $in['Clave'];?></td>
										<td style="width: 50"  class='tabledata'><?php if ($in['tipocuenta']==1 || $in['tipocuenta']==3) {
											echo '0'.$in['tipocuenta'];
										}else{
											echo $in['tipocuenta'];
										} ?></td>
										<td style="width: 50" class="numeroCuenta tabledata"><?php echo $in['numeroCuenta'];?></td>
									</tr>
									<?php  
								} 
							}?> 
						</tbody>
						<tfoot align="right">
						</tfoot>
					</table> 
					<label>Total</label>
					<input type="text" id="total" name="total" value="0.00" class="total form-control alert-info"   readonly="" />
				</div> 
			</div>
		</div>
		
		<input type="hidden" name="tipoRegistro" id="tipoRegistro" value="H" >
		<input type="hidden" name="claveServicio" id="claveServicio" value="NE" >
	    <input type="hidden" name="tiporegistro" id="tiporegistro"  value="D"  maxlength="1" />  
		<input type="hidden" name="numchecks"    id="numchecks"    class="numchecks"  value="0"  maxlength="6" /> 
		<input type="hidden" name="totalformt"   id="totalformt"    value="0"  maxlength="15" /> 
		<input type="hidden" name="naenviadas"   id="naenviadas"    value="0"  maxlength="6" /> 
		<input type="hidden" name="iaenviadas"   id="iaenviadas"    value="0"  maxlength="15" /> 
		<input type="hidden" name="nbenviadas"   id="nbenviadas"    value="0"  maxlength="6" /> 
		<input type="hidden" name="ibenviadas"   id="ibenviadas"    value="0"  maxlength="15" />
		<input type="hidden" name="cuentaverif"  id="cuentaverif"   value="0"  maxlength="6" />  
		<input type="hidden" name="espacios"     id="espacios"      value="0"  maxlength="4" />  
		<input type="hidden" name="filler"       id="filler"        value="0"  maxlength="55" />  

		<input type="hidden" name="refeServi"    id="refeServi"     value="0"  maxlength="40" /> 
		<input type="hidden" name="refeLeyeOr"   id="refeLeyeOr"    value="0"  maxlength="40" />
		<input type="hidden" name="bancorecepc"  id="bancorecepc"   value="0"  maxlength="3" /> 
		<input type="hidden" name="tipMovim"     id="tipMovim"      value="0"  maxlength="1" /> 
		<input type="hidden" name="accion"       id="accion"        value="0"  maxlength="1" /> 
		<input type="hidden" name="importIva"    id="importIva"     value="0"  maxlength="8" /> 
		<input type="hidden" id="nominadesc" name="nominadesc" value="<?php echo @$_REQUEST['tipoperiodo'];?>"/>
		<input type="hidden" name="cantEmpleSeleccion" id="cantEmpleSeleccion" >
	</body>
	</html>