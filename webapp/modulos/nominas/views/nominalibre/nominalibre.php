<!DOCTYPE html>
<head>
	 <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
	<script type="text/javascript" src="js/jquery.number.js"></script>  

	<!-- <script src='js/select2/select2.min.js'></script>
	<link rel="stylesheet" href="js/select2/select2.css"> -->

  	<script type="text/javascript" src="js/nominalibre.js"></script>

</head>
<style>
.select2-container{
      	width: 100% !important;
  	}
  	.select2-container .select2-choice{
      	background-image: unset !important;
     	height: 31px !important;
  	}
  	/*.fila-base,.fila-base2,.fila-base3*/	
  	.fila-base2,.fila-base3,.fila-base,.filadefault,.filadefault2{ display: none; }	
  	.eliminar,.eliminar2{ cursor: pointer; color: #000; }
</style>
<script>
</script>

<?php

	$percepcionesarray = $deduccionesarray = $otrosarray = array();
	$percepcioneslista = "";
	while($row = $percepciones->fetch_array()){ $percepcionesarray[$row['idAgrupador']]['clave'] = $row['clave'];$percepcionesarray[$row['idAgrupador']]['descripcion'] = $row['descripcion'];
		$percepcioneslista .= "<option value='".$row['clave']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}
	$deduccioneslista = "";
	while($row = $deducciones->fetch_array()){	$deduccionesarray[$row['idAgrupador']]['clave'] = $row['clave'];	$deduccionesarray[$row['idAgrupador']]['descripcion'] = $row['descripcion'];
		$deduccioneslista .= "<option  value='".$row['clave']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}
	$otroslista = "";
	while($row = $otros->fetch_array()){		$otrosarray[$row['idAgrupador']]['clave'] = $row['clave']; $otrosarray[$row['idAgrupador']]['descripcion'] = $row['descripcion'];
		$otroslista .= "<option  value='".$row['clave']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}
	$incapacidadeslista = "";
	while($row = $incapacidades->fetch_array()){
		$incapacidadeslista .= "<option  value='".$row['clave']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}
	$tipoHoraslista = "";
	while($row = $tipoHoras->fetch_array()){
		$tipoHoraslista .= "<option  value='".$row['clave']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}
require("views/nominalibre/nominasreutiliza.php");
	
?>
<body>
	
 	<br>
	<div class="container well" style="width: 95%">
		<div class="alert alert-danger">
	        <button type="button" class="close" data-dismiss="alert">
	            <span aria-hidden="true">×</span>
	            <span class="sr-only">Cerrar</span>
	        </button>
	        <i class="fa fa-info-circle fa-lg"></i> 
	        Consulte la guía de llenado, los catálogos oficiales y demás documentación para la correcta emisión del comprobante con complemento de nómina en las siguientes ligas:
	        <ul>
	            <li>
	                <a class="alert-link" href="http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/Complementoscfdi/guianomina12.pdf" target="_blank">
	                    Guía de llenado del SAT <small>(PDF)</small>
	                </a>
	            </li>
	            <li>
	                <a class="alert-link" href="http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/Complementoscfdi/catNomina.xls" target="_blank">
	                    Catálogos de datos del SAT <small>(Excel)</small>
	                </a>
	            </li>
	            <li>
	                <a class="alert-link" href="http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/Preguntas_frecuentes_Nomina_1_2.pdf" target="_blank">
	                    Preguntas frecuentes del SAT <small>(PDF)</small>
	                </a>
	            </li>
	            <li>
	                <a class="alert-link" href="http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Paginas/video_chat_nomina.aspx" target="_blank">
	                    Material de apoyo del SAT <small>(PDF)</small>
	                </a>
	            </li>
	        </ul>
	    </div>
		<form action="index.php?c=Nominalibre&f=creaNominaXML" method="post" id="xmlnomina" >
		<h2 align="center" style="color:#39556B;"> 	Timbrado de Nomina Manual por Empleado. </h2><hr><br><br>
		<div class="">
		<div class="row">
			<div class="col-md-12" style="text-align: center;">
				<div class="col-xs-4" align="">
					<?php
							$logo=$org->logoempresa;
							$url = explode('/modulos',$_SERVER['REQUEST_URI']);
							if($logo == 'logo.png') $logo = 'x.png';
							$logo = str_replace(' ', '%20', $logo);
						echo 	"<img width='250' height='100' src='http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo' />";
						 
					?>
				</div>
				<div class="col-xs-4" align="" style="font-size: 16px">
				<b>Datos Facturacion</b><br>
				<?php
				if($datosaFacturacion == 0 ){?>
					<a onclick="irDatosFact()" style="color: red;cursor: pointer" >Debe configurar los datos de facturacion</a>
				 
				<?php }else{
					echo "
					<b>".$datosaFacturacion->razon_social."</b><br>
					RFC: ".$datosaFacturacion->rfc."<br>
						".$datosaFacturacion->calle."<br>
						Col.".$datosaFacturacion->colonia.", ".$datosaFacturacion->cp;
					
				} ?>
				<!-- <input type="hidden" value="<?php echo $confNomina->idregistrop; ?>" id="idregistrop" name="idregistrop"/> -->				<!-- <input type="hidden" value="<?php echo $org->curporg; ?>" id="curporg" name="curporg"/> -->				<input type="hidden" value="<?php echo $datosaFacturacion->rfc; ?>" id="rfc" name="rfc"/>
				<input type="hidden" value="<?php echo $datosaFacturacion->razon_social; ?>" id="razon_social" name="razon_social"/>


				</div>
				
				<div class="col-xs-4">
					<b>Regimen Fiscal</b><b style="color:red">*</b>
					<div class="alert alert-info">
						<select id="idregfiscal" name="idregfiscal" class="selectpicker" data-width="100%" data-live-search="true" >
							<option value="0">--</option>
							<?php while ($e = $regimenfiscal->fetch_object()){ $f="";
								if(isset($datosTimbrada)){ if ($e->clave == $datosTimbrada->regfiscalclave ){  $f="selected";} } ?>
								<option value="<?php echo $e->clave;?>" <?php echo $f; ?>><?php echo $e->clave." ".$e->descripcion; ?> </option>
							<?php } ?>
						</select>
					</div>
					<div class="alert alert-info">
						<b>Tipo de Nomina</b><b style="color:red">*</b>
						<select id="tiponomina" name="tiponomina" class="selectpicker" data-width="100%" data-live-search="true" >
							<?php while ($e = $tiponomina->fetch_object()){ $t="";
								if(isset($datosTimbrada)){ if ($e->clave == $datosTimbrada->tiponomina ){  $t="selected";} } ?>
								<option value="<?php echo $e->clave;?>" <?php echo $t; ?>><?php echo $e->descripcion; ?> </option>
							<?php } ?>
							
						</select>
					</div>
					
					<b>Registro Patronal</b>
					 <div class="alert alert-info">
						<select id="idregistrop" name="idregistrop" class="selectpicker" data-width="100%" data-live-search="true" >
							<option value="0">--</option>
							<?php while ($e = $registroPatronal->fetch_object()){ ?>
								<option value="<?php echo $e->idregistrop;?>"><?php echo $e->registro; ?> </option>
							<?php } ?>
						</select>
					</div>
					
				</div>
               				
			</div>
		</div>
			<br>
		<div class="row">
			<div class="col-md-12 alert alert-info">
				<div class="col-xs-2">
					Fecha Inicial de pago<b style="color:red">*</b>
					<input type="text" id="finicio"  name="finicio" class="form-control" value=""  readonly="" />

				</div>
				<div class="col-xs-2">
					Fecha final de pago<b style="color:red">*</b>
					<input type="text" id="fin"  name="fin" class="form-control" value=""  readonly="" />

				</div>
				
				<div class="col-xs-2">
					Fecha de pago<b style="color:red">*</b>
					<input type="text" id="fpago"  name="fpago" class="form-control" value=""  readonly="" />

				</div>
				<div class="col-xs-2">
					Dias de pago<b style="color:red">*</b>
					<input type="text" id="dpago"  name="dpago" class="form-control" value=""   />

				</div>
				<div class="col-xs-2">
					Empleado<b style="color:red">*</b>
					<select id="empleado" name="empleado" class="selectpicker" data-width="100%" data-live-search="true" onchange="">
						<option value="0">--Ninguno--</option>
						
					</select>

				</div>
				<div class="col-xs-2">
					Periodicidad de Pago<b style="color:red">*</b>
					<select id="periodicidad" name="periodicidad" class="selectpicker" data-width="100%" data-live-search="true" >
						<?php while ($e = $periodicidadpagoLibre->fetch_object()){ $p="";
							if(isset($datosTimbrada)){ if ($e->clave == $datosTimbrada->periodicidadclave ){  $p="selected";} } ?>
							<option value="<?php echo $e->clave;?>" <?php echo $p; ?>><?php echo $e->descripcion; ?> </option>
						<?php } ?>
						
					</select>
				</div>
				
			</div>
		</div>
		
		<div class="panel panel-default" >
			<div class="panel-heading"  ><b style="font-size: 16px;">Percepciones</b>
				<input type="button" id="agregar" value="Agregar" class="btn btn-primary btnMenu" onclick="agregarper();"/>
			</div>
		<div class="panel-body">
	
			<div class="col-md-12 alert alert-info" style="text-align: center;">
				<div class="col-md-12">
						<div class="col-md-2">
							Tipo
						</div>
						<div class="col-md-2">
							Clave
						</div>
						<div class="col-md-2">
							Concepto
						</div>
						<div class="col-md-2">
							Importe Gravado
						</div>
						<div class="col-md-2">
							Importe Exento
						</div>
						<div class="col-md-1">
							Otro
						</div>
						<div class="col-md-1">
							
						</div>
					</div>
				
			<script>
			function agregarper(){
				var random = Math.floor(Math.random()*100);
				trper = '<div id="'+random+'" class="row">';
				trper +='<div class="col-md-2">';
				trper +=	'<div class="form-group">';
				trper +='<input type="hidden" value='+random+' id="hi'+random+'" name="hi[]"/>';			
				trper +=	'<select name="percepciones[]" id="percepciones'+random+'" onchange="percepcioneselect(this.value,'+random+')" class="percepciones" data-width="100%" data-live-search="true" >';
				trper +=	'<option>Ninguno</option>';
				trper +=	"<?php echo $percepcioneslista; ?>";
				trper +=	'</select></div></div>';
				trper +=	'<div class="col-md-2">';
						
				trper +=	'<div class="form-group">';
				trper +=	'	<input type="text" id="clave'+random+'" name="clave[]" class="form-control" />';
				trper +=	'	</div></div>';
				trper +=	'	<div class="col-md-2">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<input type="text" id="concepto'+random+'" maxlength=100 name="concepto[]" class="form-control" />';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-2">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<input onkeyup="totalsueldoypercepciones()" type="text" onkeypress="return solonumeriviris(event,this)" data-value="0" id="pg'+random+'" name="pgravada[]"  class="form-control pegravada"  />';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-2">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<input type="text" onkeyup="totalsueldoypercepciones()" onkeypress="return solonumeriviris(event,this)" data-value="0" id="pe'+random+'" name="pexento[]" class="form-control peexento" />';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-1">';
				trper +=	'		<div class="form-group">';
				trper +=	'				<input type="button" style="display:none;" title="Agregar hora extra" id="agregarhview'+random+'" value="Hora +" class="btn btn-primary btnMenu" onclick="horasextras('+random+');"/>';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-1">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<button type="button" title="Eliminar percepcion" class="btn btn-danger btnMenu eliminar">Eliminar</button>';
				trper +=	'		</div>';
				trper +=	'	</div>';
				//AccionesOTitulos
				trper +='	<div class="col-md-12"  style="display:none" id="AccionesOTitulos">';
				trper +='   		<div class="col-md-6">';
				trper +='		</div>';
				trper +='   		<div class="col-md-2">';
				trper +='			Valor de mercado <b style="color: red">*</b>';
				trper +='			<input type="text" id="valormercado" name="valormercado[]" class="form-control" value="0.00"/>';
				trper +='		</div>';
				trper +='   		<div class="col-md-2">';
				trper +='			Precio al otorgarse <b style="color: red">*</b>';
				trper +='			<input type="text" id="preciootorgarse" name="preciootorgarse[]" class="form-control" value="0.00"/>';
				trper +='		<br></div>';
				trper +='	</div>';
				//fin AccionesOTitulos
				
				
				
			trper +='	<div class="col-md-12" id="divhorasextras'+random+'" style="display:none">';
			trper +='		<div class="col-md-2"></div>';
			trper +='		<div class="col-md-2">';
			trper +='			Dias<b style="color: red">*</b>';
			trper +='		</div>';
			trper +='		<div class="col-md-2">';
			trper +='			Tipo de horas <b style="color: red">*</b>';
			trper +='		</div>';
			trper +='		<div class="col-md-2">';
			trper +='			Num. Horas extras<b style="color: red">*</b>';
			trper +='		</div>';
			trper +='		<div class="col-md-2">';
			trper +='			Importe pagado<b style="color: red">*</b>';
			trper +='		</div>';
			trper +='		<div class="col-md-1">';
			trper +='		</div>';
			trper +='	<section id="tablahoras'+random+'"></section>';
			
				trper +=	'</div>';
				$("#tabla").append( trper );
				$(".percepciones").selectpicker("refresh");
			}
			function horasextras(random){
			
			horaex ='	<div class="col-md-12" id = "divhoras'+random+'">';
			horaex +='		<div class="col-md-2"></div>';
			horaex +='		<div class="col-md-2">';
			horaex +='			<input type="text" id="diash'+random+'"  onkeypress="return solonumeriviris(event,this)"  name="diash'+random+'[]" class="form-control" />';
			horaex +='		</div>';
			horaex +='		<div class="col-md-2">';
			horaex +='			<select name="horasext'+random+'[]" id="horasext'+random+'" onchange="" class="horasext" data-width="100%" data-live-search="true" >';
			horaex +='				<option>Ninguna</option>';
			horaex +="				<?php echo $tipoHoraslista; ?>";
			horaex +='			</select>';
			horaex +='		</div>';
			horaex +='		<div class="col-md-2">';
			horaex +='			<input type="text"  onkeypress="return solonumeriviris(event,this)"  id="numdiash'+random+'[]" name="numdiash'+random+'[]" class="form-control" />';
			horaex +='		</div>';
			horaex +='		<div class="col-md-2">';
			horaex +='			<input type="text"  onkeypress="return solonumeriviris(event,this)"  id="importehoras'+random+'[]" name="importehoras'+random+'[]" class="form-control" />';
			horaex +='		</div>';
			horaex +='		<div class="col-md-1">';
			horaex +='			<div class="form-group">';
			horaex +='				<button type="button" title="Eliminar hora extra" class="btn btn-info btnMenu eliminarh">Hora -</button>';
			horaex +='			</div>';
			horaex +='		</div>';
			horaex +='	</div>';
			$("#tablahoras"+random).append( horaex );
			$(".horasext").selectpicker("refresh");
			}
			
			
				</script>
				
			<div class="row filadefault">
					
					
					
				</div>
					<section id="tabla">
					</section>
					
					
			</div>
			<div class="col-md-12 alert-success" style="font-weight: bold;font-size: 14px">
				<br><div class="col-md-3">
					Total de percepciones gravadas <b style="color: red">*</b>
					<input type="text" readonly="" id="pgravadas" name="pgravadas" class="form-control" value="0.00"/>
				</div>
				<div class="col-md-3">
					Total de percepciones exentas <b style="color: red">*</b>
					<input type="text" readonly="" id="pexenta" name="pexenta" class="form-control" value="0.00"/>
				</div>
				<div class="col-md-3">
					Total de percepciones por sueldos <b style="color: red">*</b>
					<input type="text" readonly="" id="percepxsueldos" name="percepxsueldos" class="form-control" value="0.00" />
				</div>
				<div class="col-md-3">
					Total de percepciones  <b style="color: red">*</b>
					<input type="text" readonly="" id="totalpercepciones" name="totalpercepciones" class="form-control" value="0.00"/>
				<br></div>
				
			</div><br>
			<div class="col-md-12" id="separacion" style="font-size: 12px;display: none">
				<h4>Datos de separación o indemnización</h4><br>
				<div class="col-md-12">	
					<div class="col-md-4">
						Ingreso acumulable<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="ingresoacumulableseparacion" class="form-control" />
					</div>	
					<div class="col-md-4">
						Ingreso no acumulable<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="ingresonoacumulableseparacion" class="form-control" />
					</div>
					<div class="col-md-4">
						Total pagado<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="totalpagadoindemnizacion" class="form-control" />
					</div>
				</div>			
				<div class="col-md-12">
					<div class="col-md-4">
						Años de servicio<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="anosservicio" class="form-control" />
					</div>	
					<div class="col-md-4">
						Último sueldo mensual ordinario <b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="ultimosueldo" class="form-control" />
					</div>
					<div class="col-md-4">
						Importe total por antigüedad, separación o indemnización<b style="color: red">*</b>
						<input type="text" readonly="" onkeypress="return solonumeriviris(event,this)"  name="importetotalseparacion" id="importetotalseparacion" class="form-control" />
					</div>
				</div>
			</div>
			<div class="col-md-12" id="jubilacion" style="font-size: 12px;display: none;">
				<h4>Datos de jubilación, pensiones o haberes de retiro</h4><br>
				<div class="col-md-12">	
					<div class="col-md-2">
						Ingreso acumulable<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="ingresoacumulable" class="form-control" />
					</div>
					<div class="col-md-2">
						Ingreso no acumulable<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="ingresonoacumulable" class="form-control" />
					</div>
					<div class="col-md-2" id="totalparcialidadesp"><!-- cuando es 044 -->
						Total en parcialidades<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="totalparcialidades" class="form-control" />
					</div>
					<div class="col-md-2" id="unasolaexibicionp"> <!-- cuando es 039 -->
						Total en una sola exhibición<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="unasolaexibicion" class="form-control" />
					</div>
					<div class="col-md-2" id="montodiariop"><!-- cuando es 044 -->
						Monto diario<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)"  name="montodiario" class="form-control" />
					</div>
					<div class="col-md-4">
						Importe total por jubilación, pensión o haberes de retiro<b style="color: red">*</b>
						<input type="text"  onkeypress="return solonumeriviris(event,this)" readonly="" id="importetotaljubiliacionetc" name="importetotaljubiliacionetc" class="form-control" />
					</div>
					
				</div>	
			</div>
					
		</div>
	</div>
		<div class="panel panel-default" >
			<div class="panel-heading"  ><b style="font-size: 16px;">Deducciones</b>
				<input type="button" id="agregar2" value="Agregar" class="btn btn-primary btnMenu" onclick="agregardeduc();"/>
			</div>
		<div class="panel-body">
			<div class="col-md-12 alert alert-info" style="text-align: center;">
				<div class="col-md-12">
						<div class="col-md-3">
							Tipo
						</div>
						<div class="col-md-2">
							Clave
						</div>
						<div class="col-md-3">
							Concepto
						</div>
						<div class="col-md-3">
							Importe 
						</div>
						
					</div>
				<script>
			function agregardeduc(){
				var random = Math.floor(Math.random()*101);
				trded = '<div id="'+random+'" class="row ">';
					
				trded +=' 	<div class="col-md-3">';
				trded +='		<div class="form-group">';
							
				trded +='			<select name="deducciones[]" id="deducciones'+random+'" onchange="completaDeduc(this.value,'+random+')"  class="deducciones" data-width="100%" data-live-search="true">';
				trded +='				<option>Ninguno</option>';
				trded +="				<?php echo $deduccioneslista; ?>";
				trded +='			</select>';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-2">';
						
				trded +='		<div class="form-group">';
				trded +='			<input type="text" id="dclave'+random+'" name="dclave[]" class="form-control" />';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-3">';
				trded +='		<div class="form-group">';
				trded +='			<input type="text" id="dconcepto'+random+'" onkeypress="return solonumeriviris(event,this)" name="dconcepto[]" class="form-control" />';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-3">';
				trded +='		<div class="form-group">';
				trded +='			<input type="text" id="dimporte'+random+'" maxlength=100 onkeypress="return solonumeriviris(event,this)" onkeyup="totaldeduccionesGlobal()" data-value=0 name="dimporte[]" class="form-control deduccionesglobal" />';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-1">';
				trded +='		<div class="form-group">';
				trded +='			<button type="button" class="btn btn-danger btnMenu eliminar2">Eliminar</button>';
				trded +='		</div>';
				trded +='	</div>';
				trded +='</div>';
				
				$("#tabla2").append( trded );
				$(".deducciones").selectpicker("refresh");
			}		
				</script>
				<section id="tabla2">
					</section>
			</div>
			<div class="col-md-12 alert-success" style="font-weight: bold;font-size: 14px"><br>
				<div class="col-md-4">
					Total de otras deducciones 
					<input type="text" id="otrasdedu" readonly="" name="otrasdedu" class="form-control" value="0.00"/>
				</div>
				<div class="col-md-4">
					Total por impuestos retenidos 
					<input type="text" id="impuestosretenidos" readonly="" name="impuestosretenidos" class="form-control" value="0.00"/>
				</div>
				<div class="col-md-4">
					Total de deducciones <b style="color: red">*</b>
					<input type="text" id="totaldeducciones" readonly="" name="totaldeducciones" class="form-control" value="0.00" />
				<br></div>
				
			</div>
			</div>
		</div>
		<div class="panel panel-default" >
			<div class="panel-heading"  ><b style="font-size: 16px;">Otros Pagos</b>
				<input type="button" id="agregar3" value="Agregar" class="btn btn-primary btnMenu" onclick="otrospagos()"/>
			</div>
		<div class="panel-body">
			<div class="col-md-12 alert alert-info" style="text-align: center;">
				<div class="col-md-12">
						<div class="col-md-3">
							Tipo
						</div>
						<div class="col-md-2">
							Clave
						</div>
						<div class="col-md-3">
							Concepto
						</div>
						<div class="col-md-2">
							Importe 
						</div>
						
						<div class="col-md-1">
						<div class="form-group">
							
						</div>
					</div>
						
					</div>
				<script>
			function otrospagos(){
				var random = Math.floor(Math.random()*102);
				trotro = '<div id="'+random+'" class="row">';
					
				trotro += '	<div class="col-md-3">';
				trotro += '		<div class="form-group">';
							
				trotro += '			<select name="otros[]" id="otros'+random+'" onchange="conceptoOtros(this.value,'+random+')" class="otros" data-width="100%" data-live-search="true" >';
				trotro += '				<option>Ninguno</option>';
				trotro += "				<?php echo $otroslista; ?>";
				trotro += '			</select>';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '	<div class="col-md-2">';
						
				trotro += '		<div class="form-group">';
				trotro += '			<input type="text" id="oclave'+random+'" name="oclave[]" class="form-control" />';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '	<div class="col-md-3">';
				trotro += '		<div class="form-group">';
				trotro += '			<input type="text" id="oconcepto'+random+'" maxlength=100 name="oconcepto[]" class="form-control" />';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '	<div class="col-md-2">';
				trotro += '		<div class="form-group">';
				trotro += '			<input type="text" onkeyup="totalotrospagosglobal()" onkeypress="return solonumeriviris(event,this)"  data-value=0 id="oimporte'+random+'" name="oimporte[]" class="form-control totalotrospagosglobal" />';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '				<div class="col-md-8 subsidiocausado'+random+'" id="" style="display:none">';
				trotro += '				</div>';

				trotro += '				<div class="col-md-2 subsidiocausado'+random+'" id="" style="display:none;font-weight: bold;">';
				trotro += '					Subsidio causado<b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" onkeypress="return solonumeriviris(event,this)" data-value="0" id="subsidio'+random+'" name="subsidio[]" class="form-control peexento" />';
				trotro += '				<br></div>';
				
				trotro += '				<div class="col-md-3 saldofavorotro'+random+'"  style="display:none">';
				trotro += '				</div>';

				trotro += '				<div class="col-md-2 saldofavorotro'+random+'" style="display:none;font-weight: bold;">';
				trotro += '					Saldo a favor <b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" onkeypress="return solonumeriviris(event,this)" data-value="0" id="saldofavor'+random+'" name="saldofavor[]" class="form-control peexento" title="Se debe registrar el saldo a favor determinado por el patrón al trabajador en el ejercicio al que corresponde el comprobante, debe ser mayor o igual que el valor del campo RemanenteSalFav. Es la diferencia que resulte a favor del contribuyente derivado del cálculo del impuesto anual ajuste anual- realizado por el empleador, siempre que el trabajador preste sus servicios a un mismo patrón y no esté obligado a presentar declaración anual."/>';
				trotro += '			</div>';
				trotro += '			<div class="col-md-3 saldofavorotro'+random+'" style="display:none;font-weight: bold;">';
				trotro += '					Remanente del saldo a favor <b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" onkeypress="return solonumeriviris(event,this)" data-value="0" id="remanente'+random+'" name="remanente[]" class="form-control peexento" title="En el caso de haber resultado saldo a favor del trabajador en un ejercicio anterior, se reportará el mismo aquí, una vez restado el monto que en su caso se haya aplicado de haber existido saldo a cargo en el ejercicio al que corresponde este comprobante.Este campo sólo podrá utilizarse en comprobantes generados a partir del año 2017"/>';
				trotro += '				</div>';	
				trotro += '			<div class="col-md-2 saldofavorotro'+random+'" style="display:none;font-weight: bold;">';

				trotro += '					Año <b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" title="El valor de este atributo debe ser menor que el año en curso" onkeypress="return solonumeriviris(event,this)" data-value="0" id="anosubsidio'+random+'" name="anosubsidio[]" class="form-control peexento" />';
				trotro += '			<br></div>';
							
				trotro += '	<div class="col-md-1">';
				trotro += '		<div class="form-group">';
				trotro += '			<button type="button" class="btn btn-danger btnMenu eliminar3">Eliminar</button>';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '</div>';
				
				$("#tabla3").append(trotro);
				$(".otros").selectpicker('refresh');
			}		 
				</script>
				<section id="tabla3">
					</section>
			</div>
			<div class="col-md-12 alert-success"  style="font-weight: bold;font-size: 14px;">
				<br>
				<div class="col-md-3" >
					Total de otros pagos  <b style="color: red">*</b>
					<input type="text" readonly="" align="right" id="totalotrospagos" data-value="" name="totalotrospagos" class="form-control" value="0.00" />
				<br></div>
				
			</div>
		</div>	
			
	</div>
		
		
		<div class="panel panel-default" id="incapacidad" style="display: none">
		<!-- el importe de las incapacidades debe ser igual a la suma del concepto percepcion (importe gravado + importe exento) -->		
			
			<div class="panel-heading"  ><b style="font-size: 16px;">Incapacidades</b>
				<input type="button" id="agregarinc" value="Agregar" class="btn btn-primary btnMenu" onclick="agregarinca()"/>
			</div>
		<div class="papel-body">
			
			<div class="col-md-12 alert alert-warning" style="text-align: center;">
				<div class="col-md-12">
						<div class="col-md-3" title="Se debe registrar el número de días enteros que el trabajador se incapacitó en el periodo">
							Dias
						</div>
						<div class="col-md-4">
							Tipo de incapacidad
						</div>
						<div class="col-md-4">
							Importe monetario
						</div>
						
						<div class="col-md-1">
							
						</div>
						
					</div>
				<script>
				function agregarinca(){
				var random = Math.floor(Math.random()*103);	
				inca = '<div class="row " id="'+random+'" >';
				inca += '<div class="col-md-3">';
				inca += '		<div class="form-group">';
				inca += '			<input type="text" id="diasinc'+random+'" name="diasinc[]" class="form-control" />';
				inca += '		</div>';
				inca += '	</div>';
				inca += '	<div class="col-md-4">';
				inca += '		<div class="form-group">';
				inca += '			<select name="tipoinc[]" id="tipoinc'+random+'" onchange="" class="incap" data-width="100%" data-live-search="true" >';
				inca += "				<?php echo $incapacidadeslista; ?>";
				inca += '			</select>	';					
				inca += '		</div>';
				inca += '	</div>';
				inca += '	<div class="col-md-4">';
				inca += '		<div class="form-group">';
				inca += '			<input type="text" id="importeinc'+random+'" name="importeinc[]" class="form-control importeinca" />';
				inca += '		</div>';
				inca += '	</div>';
				inca += '	<div class="col-md-1">';
				inca += '		<div class="form-group">';
				inca += '			<button type="button" class="btn btn-danger btnMenu eliminar4">Eliminar</button>';
				inca += '		</div>';
				inca += '	</div>';
				inca += '</div>';
				$("#tablainc").append(inca);
				$(".incap").selectpicker('refresh');
				}
				</script>
				<section id="tablainc">
					</section>
			</div>
			
							</div>
		</div>
	</div>
	
	<div class="col-xs-12" >
		
		<hr>
		
		<div class="col-sm-6"></div>
		<div class="col-sm-6">
		<table class="table">
                <thead>
                		<tr class="danger">
                			  <th style="width:50%;text-align: center;font-size: 20px;" colspan="2">TOTAL</th>
                		</tr>
                    <tr class="">
                        <th style="width:50%;">Concepto</th>
                        <th style="width:50%;">Importe</th>
                    </tr>
                </thead>
                <tbody id="tb_totales" class="tbody_collapse">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">
                            <input type="text" class="form-control"  min="0.00" value="0.00" id="subtotal" name="subtotal"  readonly="">
                        </td>
                    </tr>
                    <tr>
                        <td>Descuento</td>
                        <td class="text-right">
                            <input type="text" class="form-control"  min="0.00" value="0.00" id="descuento" name="descuento"  readonly="">
                        </td>
                    </tr>
                    <tr>
                        <td>Neto a pagar</td>
                        <td class="text-right">
                            <input type="text" class="form-control"  min="0.00" value="0.00" id="detopagar" id="detopagar"  readonly="">
                        </td>
                    </tr>
                </tbody>
            </table>
           </div>
	</div>
	

	<div class="col-md-12" align="right">
			<button type="button" class="btn btn-primary" id="TimbraNomina" data-loading-text="Timbrando nomina espere.<i class='fa fa-refresh fa-spin '></i>">Timbrar</button>
	</div>
		
	</form>	
	</div>

</body>
</html>