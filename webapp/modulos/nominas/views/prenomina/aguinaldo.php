<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/aguinaldo.js"></script>
	<link   rel="stylesheet" type="text/css" href="css/estilomodal.css" /> 
	<link   rel="stylesheet" type="text/css" href="css/calculo_ptu.css">
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css"> 
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
</head>
<body><br>
	
	<div class="container well" style="width: 95%">
		<h3 align="center"> Calculo	Aguinaldo </h3><hr><br>
		<div class="alert alert-danger col-md-12" align="justify">
	        <button type="button" class="close" data-dismiss="alert">
	            <span aria-hidden="true">×</span>
	            <span class="sr-only">Cerrar</span>
	        </button>
	         <i class="fa fa-info-circle fa-lg"></i> 
	         INFORMACION.<br><!-- 
	         Recuerde que al aplicar el procedimiento del art.96 ISR la base gravada del aguinaldo
	         no se suma a la base gravada del periodo, por lo que se obtiende un impuesto por separado. -->
	         Por ley se deben otorgar 15 dias de aguinaldo, pero si otorga otro numero de dias actualice
	         los dias de aguinaldo en <a href="" title="Ir a Antigüedades" onclick="mandatablaempresa()">Antigüedades</a><br>
	         <b>RECUERDE VERIFICAR EL CONCEPTO DE AGUINALDO EN LA <a href="" title="Ir a Configuracion" onclick="irConfiguracion()">CONFIGURACION</a> DONDE SE ACUMULARA EL IMPORTE.</b>
	         
	   </div>
		<div class="col-md-12">
			<div class="col-md-3" >
				Periodo
				<select id="periodo" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">--Ninguno--</option>
					<?php
					while($periodo = $tipoperiodo->fetch_object() ){?>
						<option value="<?php echo $periodo->idtipop."/".$periodo->nombre;?>"><?php echo $periodo->nombre; ?></option>
					<?php } ?>
					
				</select>
			</div>
			<div class="col-md-3">
				Modo de calcular el ISR de aguinaldo
				<select class="selectpicker" data-width="100%" data-live-search="true" id="israguinaldo">
					<!-- <option value="1">Aplicar articulo 142</option> -->	
					<option value="1">Aplicar articulo 96(sp/mens)</option>
				</select>
			</div>
			
			
			<div class="col-md-4">
				Incidencias que se descuentan de los dias trabajados:
				<select multiple="" class="selectpicker" data-width="100%" data-live-search="true" id="incidencias" name="incidencias[]" >
					<?php
					while($incidencias = $listaIncidencias->fetch_object() ){?>
						<option value="<?php echo $incidencias->idtipoincidencia;?>"><?php echo $incidencias->clave." ".$incidencias->nombre; ?></option>
					
					<?php } ?>
				</select><br><br>
			</div>
			
		</div>
		<div class="col-md-12">
			<fieldset class="scheduler-border col-md-12"><legend class="scheduler-border">Concepto que se calculan</legend>
			<!-- <div class="col-md-3">
				<b>Concepto para Aguinaldo</b>
				<select  class="selectpicker" data-width="100%" data-live-search="true" id="percep" name="percep" >
					<?php
					while($concep = $percepciones->fetch_object() ){?>
						<option value="<?php echo $concep->idconcepto;?>"><?php echo $concep->concepto." ".$concep->descripcion; ?></option>
					
					<?php } ?>
				</select>
			</div> -->
			<div class="col-md-3">
				<b>Concepto para ISR de Aguinaldo</b>
				<select  class="selectpicker" data-width="100%" data-live-search="true" id="isr" name="isr" >
					<?php
					while($concep = $deducciones->fetch_object() ){?>
						<option value="<?php echo $concep->idconcepto;?>"><?php echo $concep->concepto." ".$concep->descripcion; ?></option>
					
					<?php } ?>
				</select>
			</div>
			</fieldset>
		</div>
		
		<div align="right" class="col-md-12">
		
			<input type="checkbox"  name="diasrestantes" id="diasrestantes" value="0" onclick="diasrestan()"/>
			<b>‎¿Considerar dias que restan del año como trabajados?</b>

			<button  title="Calcular Nomina" type="button" class="btn btn-info" id="aguinaldo" data-loading-text="<i class='fa fa-cog fa-spin fa-3x fa-fw margin-bottom'></i>"><i class="fa fa-cogs" aria-hidden="true" ></i> Calcular Aguinaldo</button>
			<br>
		</div>
		<div id="" class="col-md-12 alert alert-info" style="overflow-y: scroll; overflow-x: auto;display: block;";>

			<table  id="tablaaguinaldo" cellpadding="3" class="table table-striped table-bordered" style="border:solid 1px;" width="100%">
				<thead class="" title="Deslizar para ver mas..."><!-- <thead title="Deslizar para ver mas..."> -->
					<tr style="border:solid 0px;background:#6E6E6E; color:#F5F7F0" id="trHeader">
						<th><b>Codigo</b></th>
						<th><b>Nombre Empleado</b></th>
						<th><b>Sueldo Diario</b></th>
						<th><b>Dias prestacion</b></th>
						<th><b>Dias proporcion Aguinaldo</b></th>
						<th><b>Subtotal Aguinaldo</b></th>
						<th><b>ISR</b></th>
						<th><b>Total Aguinaldo</b></th>
						<th><b>Tipo de periodo</b></th>
						<th><b>Dias trabajados</b></th>
						<th><b>Incidencias</b></th>
						<th><b>Dias restantes</b></th>
						<th><b>Total dias</b></th>
						<!-- <th><b>Tipo empleado</b></th> -->
					</tr>
				</thead>
				<tbody class="" id="contenidop">		
				</tbody>
				
			</table>
		</div>
		<div align="right">
			<button title="Entregar Aguinaldo" type="button" class="btn btn-primary" id="acumularaguinaldo" data-loading-text="<i class='fa fa-cog fa-spin fa-3x fa-fw margin-bottom'"><i class="fa fa-cogs" aria-hidden="true"></i> Entregar Aguinaldo</button>		
		</div>
	</div>
</body>
	
</html>