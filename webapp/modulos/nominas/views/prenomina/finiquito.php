<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script type="text/javascript" src="js/finiquito.js"></script>
	<link   rel="stylesheet" type="text/css" href="css/finiquito.css" /> 
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css"> 
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

</head>
<body><br>
	<form action="index.php?c=Prenomina&f=reciboFiniquito" id="formfiniquito" method="post">
	<div class="container well" style="width: 95%">
		<h3 align="center"> Calculo	Provisional </h3><hr><br>
		
		<div class="col-md-12 alert alert-info">
			<fieldset>
			<div class="col-md-4" >
				<b>Empleado</b>
				<select id="empleado" name="empleado" class="selectpicker" data-width="100%" data-live-search="true" onchange="datosEmp()">
					<option value="0">--Ninguno--</option>
					<?php
					while($empleados = $listaempleados->fetch_object() ){?>
						<option value="<?php echo $empleados->idEmpleado;?>"><?php echo strtoupper("(".$empleados->codigo.") ".$empleados->nombreEmpleado." ".$empleados->apellidoPaterno); ?></option>
					<?php } ?>
					
				</select>
				<input type="hidden" name="nombreempleado" id="nombreempleado">
				<input type="hidden" name="sdi" id="sdi">
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" id="loade" style="display: none" ></i>
			</div>
			<div class="col-xs-4">
				<b>Contrato</b>
				<input type="text" id="contrato" name="contrato" class="form-control" value=""  readonly="" />
			</div>
			<div class="col-xs-4">
				<b>Sueldo</b>
				<input type="text" id="sueldo" name="sueldo" class="form-control" value=""   readonly=""/>
			<br></div>
			</fieldset>
		</div><br>
		
		<div class="col-md-12" style="text-align: center">
			<fieldset class="fieldset1">
			  <legend class="fieldset1">Configuracion del calculo del finiquito</legend>
			  		<div class="row">
			  		<div class="col-xs-2">
						<b>Fecha Alta: </b>
						<input id="fechaalta" name="fechaalta" style="color: red;text-align: center" readonly=""/>
					</div>
					</div>
					<div class="col-xs-2">
						Salario base finiquito
						<input type="text" id="salariobase" name="salariobase" style="text-align: center" class="form-control" />

					</div>
					<div class="col-xs-2">
						Fecha Baja/Finiquito
						<input type="text" readonly="" id="fechabaja" style="text-align: center" name="fechabaja" class="form-control" />
					</div>
					<div class="col-xs-2">
						Antigüedad  
						<input type="text" readonly="" id="antiguedad" style="text-align: center" name="antiguedad" class="form-control"  />
					</div>
					<div class="col-xs-2">
						Dias trans.en el año 
						<input type="text" readonly="" id="diasano"style="text-align: center"  name="diasano" class="form-control" />
					</div>
					<div class="col-xs-2">
						S.M.D.F
						<input type="text" readonly="" id="sm" name="sm" style="text-align: center" value="<?php echo $SMvigente->zona_a;?>" class="form-control" />
					</div>
					<div class="col-xs-2">
						<b>Causa del finiquito</b>
						<select id="causa" name="causa" class="selectpicker" onchange="causasConceptos(this.value)"  data-width="100%" data-live-search="true" >
						<?php
						while($causas = $cuasasfiniquito->fetch_object() ){?>
							<option value="<?php echo $causas->idcausaf;?>"><?php echo $causas->nombre; ?></option>
						<?php } ?>
						</select>
						<input type="hidden" id="causanombre" name="causanombre" />
					</div>
			  	
			</fieldset><br>
		</div>
		<div class="col-md-6" style="text-align: center">
			<!-- <fieldset class="fieldset1" style="height: 120px;">
			  <legend class="fieldset1">Criterios fiscales para el calculo del finiquito</legend>
			  		<div class="col-xs-4">
						Años completos ISR
						<input type="text" id="anoscompletosisr" class="form-control" />
					</div>
					<div class="col-xs-8">
						<input type="checkbox" id="calculodirecto">
						Calculo directo de ISR o SUBS para percep. norm.
					</div>
			 </fieldset>	 -->
		<!-- <div class="panel panel-default" >
				<div class="panel-heading" ><b style="font-size: 16px;">Criterios fiscales para el calculo del finiquito</b>
				</div>
				<div class="panel-body">
					<div class="col-xs-4">
						Años completos ISR
						<input type="text" id="anoscompletosisr" name="anoscompletosisr" class="form-control" />
					</div>
					<div class="col-xs-8">
						<input type="checkbox" id="calculodirecto" name="calculodirecto">
						Calculo directo de ISR o SUBS para percep. norm.
					</div>
				</div>
			</div> -->
		</div>
		<div class="col-md-6" style="text-align: center">
			<!-- <fieldset class="fieldset1" style="height: 120px">
			  <legend class="fieldset1">Aplicar subsidio del empleo en:</legend>
			  		<div class="col-xs-5">
						<input type="checkbox" id="usmo">
						Calculo de ISR USMO.
					</div>
					<div class="col-xs-7">
						<input type="checkbox" id="risr">
						Calculo de ISR a la fraccion II RISR 142<br>
					</div>
			 </fieldset>	 -->
			<!-- <div class="panel panel-default" style="height: 130px">
				<div class="panel-heading" ><b style="font-size: 16px;">Aplicar subsidio del empleo en</b>
				</div>
				<div class="panel-body">
					<div class="col-xs-5">
						<input type="checkbox" id="usmo" name="usmo">
						Calculo de ISR USMO.
					</div>
					<div class="col-xs-7">
						<input type="checkbox" id="risr" name="risr">
						Calculo de ISR a la fraccion II RISR 142<br>
					</div>
					
					</div>
				</div> -->
		
		</div>
	
	<div class="col-md-12 alert alert-info">
		
	<table  id="tabla" cellpadding="3" class="table-striped table-over table-bordered" width="100%">
		<thead class="" title="Deslizar para ver mas...">
			<tr style="border:solid 0px;background:#6E6E6E; color:#F5F7F0;">
				<th colspan="5" style="text-align: center">DESCRIPCION DE CONCEPTOS EN EL FINIQUITO</th>
			</tr>
			<tr style="border:solid 0px;background:#6E6E6E; color:#F5F7F0" id="trHeader">
				<th><b>Descripcion</b></th>
				<th><b>Concepto</b></th>
				<th style="text-align: center"><b>Aplica</b></th>
				<th><b>Dias Totales</b></th>
				<th style="text-align: center"><b>?</b></th>
			</tr>
		</thead>
		<tbody class="" id="contenidop">
			<?php 
			while($conceptos = $conceptosFiniquito->fetch_object()){?>
				<tr>
					<td><b><?php echo $conceptos->nombre;?></b></td>
					<td>
						<select name="concepto[]" id="select<?php echo $conceptos->idconf;?>"  class="selectpicker" data-width="100%" data-live-search="true" >
							<?php
							foreach($arrayconceptosnomina as $c){
							
								if($conceptos->idconcepto == $c['value']){ $f= "selected";}else{ $f="";}
								 echo "<option value='".$c['value']."/".$c['text']."' $f>".$c['text']."</option>"; 
							
								
							}?>
						</select>
					</td>
					<?php 	if($conceptos->idconf != 10){//no es isr ?>
					<td style="text-align: center">
						<div class="btn-group" data-toggle="buttons">
						<label   class="diascheck btn btn-info active " id="label<?php echo $conceptos->idconf; ?>" onclick="return check(<?php echo $conceptos->idconf; ?>)">
							<input value="0" class="checkcausa" name="aplica[<?php echo $conceptos->idconf; ?>]" type="checkbox"  id="check<?php echo $conceptos->idconf; ?>" >
							<span class="glyphicon glyphicon-ok"></span>
						</label>
						</div>
					</td>
					<td><input type="text" name="importedias[]" id="input<?php echo $conceptos->idconf;?>" value="<?php echo $conceptos->diastotal; ?>"/></td>
					<?php } ?>
				</tr>
			<?php } ?>		
		</tbody>
		
	</table>	
	</div>	
		<div align="right">
			<button title="Procesar Finiquito" type="button" class="btn btn-primary" id="finiquito" data-loading-text="<i class='fa fa-cog fa-spin fa-3x fa-fw margin-bottom'><i/>"><i class="fa fa-cogs" aria-hidden="true"></i> Procesar Finiquito</button>
		</div>
	</div>
	</form>
</body>
</html>