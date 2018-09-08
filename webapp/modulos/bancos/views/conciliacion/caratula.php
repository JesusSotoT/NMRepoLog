<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="js/bootstrap/bootstrap.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
	<script src="js/conciliacion.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
</head>
<body>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $vista_titulo ?></h3>
			<br />
			<div class="row">
				<div class="col-md-4">
					    Cuenta:
					    <select name="selec_cuenta" id="selec_cuenta" title="Cuenta bancaria">
							<option value="0"> - Seleccione -</option><?php
							
							foreach ($cuentas_bancarias as $c => $cc){ ?>
								<option value="<?php echo $cc['idbancaria']; ?>"> <?php echo $cc['cuenta'].' ['.$cc['nombre'].']'; ?> </option> <?php
							} ?>
						</select>
				</div>
				
				<div class="col-md-3">
			    	Ejercicio:
					<select name="ejercicio" id="ejercicio" title="Ejercicio de conciliacion">
						<option value="<?php echo $ejercicio ?>"><?php echo $ejercicio ?></option>
					</select>
				</div>
				
				<div class="col-md-3">
			    	Periodo:
					<select name="periodo" id="periodo" title="Periodo de conciliacion">
						<option value="0"> - Seleccione -</option>
						<option value="1"> Enero </option>
						<option value="2"> Febrero </option>
						<option value="3"> Marzo </option>
						<option value="4"> Abril </option>
						<option value="5"> Mayo </option>
						<option value="6"> Junio </option>
						<option value="7"> Julio </option>
						<option value="8"> Agosto </option>
						<option value="9"> Septiembre </option>
						<option value="10"> Octubre </option>
						<option value="11"> Noviembre </option>
						<option value="12"> Diciembre </option>
					</select>
				</div>
				
				<div class="col-md-2"><?php
					if ($vista==1) { ?>
					<!-- Conciliacion completa -->
				    	<button type="button" class="btn btn-default btn-sm" onclick="comparar1({periodo: $('#periodo').val(), cuenta:$('#selec_cuenta').val(), ejercicio:$('#ejercicio').val(), comparar:1, caratula:1, status:1, impreso:1, conciliado:0, completa:1})">
							Consultar
						</button><?php
					}
					
					if ($vista==2) { ?>
					<!-- Documentos conciliados -->
						<button type="button" class="btn btn-default" onclick="comparar1({vista:'<?php echo $vista ?>', periodo: $('#periodo').val(), cuenta:$('#selec_cuenta').val(), ejercicio:$('#ejercicio').val(), comparar:1, status:1, impreso:1, conciliado:1, fecha_aplicacion:1})">
							Consultar
						</button><?php
					}
					
					if ($vista==3) { ?>
					<!-- Documentos no Considerados por el Banco -->
						<button type="button" class="btn btn-default" onclick="comparar1({vista:'<?php echo $vista ?>', periodo: $('#periodo').val(), cuenta:$('#selec_cuenta').val(), ejercicio:$('#ejercicio').val(), comparar:1, status:1, impreso:1})">
							Consultar
						</button><?php
					}
					
					if ($vista==4) { ?>
					<!-- Documentos no Considerados por la Empresa -->
						<button type="button" class="btn btn-default" onclick="comparar1({vista:'<?php echo $vista ?>', periodo: $('#periodo').val(), cuenta:$('#selec_cuenta').val(), ejercicio:$('#ejercicio').val(), comparar:1, status:1, impreso:1})">
							Consultar
						</button><?php
					}
					
					if ($vista==5) { ?>
					<!-- Conciliacion manual -->
						<button type="button" class="btn btn-default" onclick="conciliacion_manual({vista:'<?php echo $vista ?>', periodo: $('#periodo').val(), cuenta:$('#selec_cuenta').val(), ejercicio:$('#ejercicio').val(), comparar:1, status:1, impreso:1})">
							Consultar
						</button><?php
					}  ?>
				</div>
			</div>
			
			<div class="panel-body">
				
			<!-- *  * *   - - -      Herramientas para hacer pruebas diabolicas     --- 	* *  * -->
				
				<!-- <div class="btn-group btn-group-sm" role="group" aria-label="...">
					<button type="button" class="btn btn-default" onclick="listar_documentos_js({periodo: $('#periodo').val(), cuenta:$('#cuenta').val(), ejercicio:$('#ejercicio').val(), status:1, impreso:1})">
						Documentos bancarios
					</button>
					
					<button type="button" class="btn btn-default" onclick="listar_cuentas_bancarias_js({periodo:$('#periodo').val(), cuenta:$('#cuenta').val(), ejercicio:$('#ejercicio').val()})">
						Cuenta Bancaria
					</button>
					
					<button type="button" class="btn btn-default" onclick="comparar1({vista:1, periodo: $('#periodo').val(), cuenta:$('#selec_cuenta').val(), ejercicio:$('#ejercicio').val(), comparar:1, status:1, impreso:1})">
						Comparar
					</button>
					
					<button type="button" class="btn btn-default" onclick="ajustar_js({periodo: $('#periodo').val(), cuenta:$('#cuenta').val(), ejercicio:$('#ejercicio').val(),  fecha: '2015-06-03', comparar:1, ajuste:1})">
						Ajustar
					</button> 
				</div> -->
			</div>
		</div>
	</div>
	
	<div class="panel panel-default"  id="contenedor">
		<!-- Div donde se cargara el contenido -->
	</div>
</body>
</html>