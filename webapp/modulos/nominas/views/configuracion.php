<!DOCTYPE >
<html>
	<head>
		<meta charset="utf-8">
  		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
  		<script type="text/javascript" src="js/configuracion.js"></script>
  		


	</head>
	<script>
		$(document).ready(function(){
			<?php
	if($datos->fechainicio != 0){?>
		$("#fecha").attr("readonly",true);
		$("#fecha").datepicker("destroy");

	<?php
	}	
		if($datos->periodoanteriores == 1){?>
			$("#anteriores").prop("checked",true);
				
<?php	}
		
		if($datos->periodosfuturos == 1){?>
			 $("#futuros").prop("checked",true);	
<?php	}
		
		if($datos->emitirsellos == 1){ ?>
			$("#sellos").prop("checked",true);
<?php	}  ?>
		marcas();
		});
	</script>
	<body><br>
		<div class="container well">
			<form id="formconfig" action="ajax.php?c=Catalogos&f=almacenaConfiguracion" method="post">
				<div id="general" class="tab-pane fade in active">
  					<h3>Configuración Nominas</h3>
					<br>
					<?php 
					// el tipo 1 es edicion
					//tipo 0 nuevo primera vez
					if($datos->fechainicio){
						$tipo = 1;
					} else{
						$tipo = 0;
					}
					?>
					<input type="hidden" value="<?php echo $tipo;?>" name="tipo">
  					<div class="alert alert-info">
  						<div class="row">
               				<div class="col-md-12">
               					<div class="col-xs-4">
               						<b><?php echo $org->nombreorganizacion;?></b>
               					</div>
               					<div class="col-xs-4">
               						
               						<b>Regimen Fiscal: <?php echo $org->descripcion;?></b>
               					</div>
               					<div class="col-xs-4">
               						
               						<b>RFC: <?php echo $org->RFC;?></b>
               						<span class="glyphicon glyphicon-share-alt" style="cursor: pointe" title="Ir a organizacion" onclick="irOrganizacion()"></span>
               					</div>
               				
               					
               				</div>
               			</div>
               			<br>
  						<div class="row">
               				<div class="col-md-12">
               					<div class="col-xs-4">
               						Fecha de inicio de historia
               						<input type="text" id="fecha" name="fecha" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->fechainicio; }  ?>"/>
               					</div>
               					<!-- <div class="col-xs-3">
               						Regimen Fiscal<b style="color:red">*</b>					
               						<select id="idregfiscal" name="idregfiscal" class="selectpicker" data-width="100%" data-live-search="true" >
									<option value="0">--</option>
									<?php while ($e = $regimenfiscal->fetch_object()){ $f="";
										if(isset($datos)){ if ($e->idregfiscal == $datos->idregfiscal ){  $f="selected";} } ?>
										<option value="<?php echo $e->idregfiscal;?>" <?php echo $f; ?>><?php echo $e->clave." ".$e->descripcion; ?> </option>
									<?php } ?>
									</select>
               					</div> -->
               					<div class="col-xs-4">
	               					Registro patronal del IMSS
	               					<select id="patronal" name="patronal" class="selectpicker" data-width="100%" data-live-search="true" >
									<option value="0">--</option>
									<?php while ($e = $registroPatronal->fetch_object()){ $p="";
										if(isset($datos)){ if ($e->idregistrop == $datos->idregistrop ){  $p="selected";} } ?>
										<option value="<?php echo $e->idregistrop;?>" <?php echo $p; ?>><?php echo $e->registro; ?> </option>
									<?php } ?>
									</select>
	               				</div>
               					<div class="col-xs-4" align="center">
               						Factor no deducible por ingresos exentos
               					
               						<input type="text" id="factor" name="factor" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->factordeduexent; }  ?>"/>
               					</div>
               					
               				</div>
             			</div><br>
               			<div class="row">
               				<div class="col-md-12">
               					
               					
	               				<div class="col-xs-4">
	           						Registro Infonavit
	           						<input type="text" id="infonavit" name="infonavit" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->reginfonavit; }  ?>"/>
	           					</div>
	           					<div class="col-xs-4" align="">
               						Centro de trabajo FONACOT
               					
               						<input type="text" id="fonacot" name="fonacot" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->centrotrabajofonacot; }  ?>"/>
               					</div>
               					<div class="col-xs-4">
	           						Registro SS
	           						<input type="text" id="ss" name="ss" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->regss; }  ?>"/>
	           					</div>
               					
           					</div>
               			</div>
               			<br>
               			<div class="row">
               				<div class="col-md-12">
               					
	           					<div class="col-xs-4">
	           						Registro comision mixta
	           						<input type="text" id="mixta" name="mixta" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->regcomisionmixta; }  ?>"/>
	           					</div>
               					
               					<div class="col-xs-4" align="">
               						CURP
	           						<input type="text" id="curp" maxlength="18" name="curp" style='text-transform:uppercase'  class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->curp; }  ?>"/>
	           					
               					</div>
               					<div class="col-xs-4">
               						Zona de salario general
               						<select id="zona" name="zona" class="selectpicker" data-width="100%" data-live-search="true">
               							<?php while ($e = $zona->fetch_object()){ $p="";
										if(isset($datos)){ if ($e->idzona == $datos->idzona ){  $p="selected";} } ?>
										<option value="<?php echo $e->idzona;?>" <?php echo $p; ?>><?php echo $e->zonasalario; ?> </option>
										<?php } ?>
               						</select>
               					</div>
               				</div>
               			</div>
               			
               			
  					</div>
  					<br>
  					<div class="alert alert-info">
  						<h4>Generales</h4><hr>
  						<div class="row">
               				<div class="col-md-12">
               					
               					<div class="col-xs-3">
               						Concepto PTU
               						<select id="ptu" name="ptu" class="selectpicker" data-width="100%" data-live-search="true" >
									<?php foreach ($percepciones as $e){ $p="";
										if(isset($datos)){ if ($e['idconcepto'] == $datos->ptu ){  $p="selected";} } ?>
										<option value="<?php echo $e['idconcepto'] ;?>" <?php echo $p; ?>><?php echo $e['descripcion']; ?> </option>
									<?php } ?>
									</select>
               					</div>
               					<div class="col-xs-3">
               						Concepto de aguinaldo
               						<select id="aguinaldo" name="aguinaldo" class="selectpicker" data-width="100%" data-live-search="true" >
									<?php foreach ($percepciones as $e){ $p="";
										if(isset($datos)){ if ($e['idconcepto'] == $datos->aguinaldo ){  $p="selected";} } ?>
										<option value="<?php echo $e['idconcepto'] ;?>" <?php echo $p; ?>><?php echo $e['descripcion']; ?> </option>
									<?php } ?>
									</select>
               					</div>
               					<div class="col-xs-3">
               						Concepto de prima vacacional
               						<select id="prima" name="prima" class="selectpicker" data-width="100%" data-live-search="true" >
									<?php foreach ($percepciones as $e){ $p="";
										if(isset($datos)){ if ($e['idconcepto'] == $datos->primavac ){  $p="selected";} } ?>
										<option value="<?php echo $e['idconcepto'] ;?>" <?php echo $p; ?>><?php echo $e['descripcion']; ?> </option>
									<?php } ?>
									</select>
               					</div>
               					
               					<div class="col-xs-3" title="Representante legal de la empresa, este aparecerá en los recibos que requiera ">
               						<b>Representante legal</b>
               						<input type="text" id="representa" name="representa" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->representantelegal; }  ?>"/>
               					</div>
               					
               				</div>
             </div><br>
               			<div class="row">
               				<div class="col-md-12">
               					
               					<div class="col-xs-3">
               						Concepto de vacaciones a tiempo
               						<select id="vacaciones" name="vacaciones" class="selectpicker" data-width="100%" data-live-search="true" >
									<?php foreach ($percepciones as $e){ $p="";
										if(isset($datos)){ if ($e['idconcepto'] == $datos->vactiempo ){  $p="selected";} } ?>
										<option value="<?php echo $e['idconcepto'] ;?>" <?php echo $p; ?>><?php echo $e['descripcion']; ?> </option>
									<?php } ?>
									</select>
               					</div>
               					<div class="col-xs-3">
               						Ajuste del calculo invertido
               					
               						<select id="calculoinvertido" name="calculoinvertido" class="selectpicker" data-width="100%" data-live-search="true" >
									<?php foreach ($percepciones as $e){ $p="";
										if(isset($datos)){ if ($e['idconcepto'] == $datos->calculoinvertido ){  $p="selected";} } ?>
										<option value="<?php echo $e['idconcepto'] ;?>" <?php echo $p; ?>><?php echo $e['descripcion']; ?> </option>
									<?php } ?>
									</select>
               					</div>



                      <div class="col-xs-3">
                          Periodos existentes      
                          <select id="idtipop" name="idtipop" class="selectpicker" data-width="100%" data-live-search="true" >
                  <?php foreach ($tipPeriodos as $e){ $p="";
                    if(isset($datos)){ if ($e['idtipop'] == $datos->idtipop){  $p="selected";} } ?>
                    <option value="<?php echo $e['idtipop'] ;?>" <?php echo $p; ?>><?php echo $e['nombre']; ?> </option>
                  <?php } ?>
                  </select>
                        </div>
               					<!-- <div class="col-xs-3" >
               						<input type="checkbox" id="anteriores" name="anteriores" value="0" onclick="marcas()"/>
               						<b>Modificar periodos anteriores</b><br>
               					</div> -->
               					<div class="col-xs-3" >
               						<input type="checkbox" id="futuros" name="futuros" value="0" onclick="marcas()"/>
               						<b>Modificar periodos futuros</b>
               					</div>
               					<div class="col-xs-3">
               						<input type="checkbox" id="sellos" name="sellos"  onclick="marcas()" value="0"/>
               						<b>Emitir recibos sellados electronicamente</b>
               					</div>
               				</div>
               			</div>
               			
  					</div>
  					<div class="col-md-12">
  						<button type="button" class="btn btn-primary" id="load"   data-loading-text="<i class='fa fa-refresh fa-spin '></i>"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
					</div>
  				</div>
			</form>
		</div>
	</body>
</html>