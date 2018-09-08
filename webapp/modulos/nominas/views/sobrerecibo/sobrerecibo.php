<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.number.js"></script>
	<script type="text/javascript" src="../../libraries/numeral.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/sobrerecibo.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.multidatespicker.css">
	<link rel="stylesheet" type="text/css" href="css/sobrerecibo.css" />
	<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script language='javascript' src='js/pdfmail.js'></script>
</head>
<script>
	$(document).ready(function(){
		$("#diasdescansoseptimo").prop('disabled', true);
		$("#fechadescanso").datepicker("disable");
		<?php if ($empleado){?>
			$('#empleado > option[value="<?php echo $empleado?>"]').attr('selected', true);
			datosEmp();
			<?php } ?>
//$("#fechaactiva").datepicker("<?php echo $nominaActiva['fechafin']; ?>");
$("#fechainiciovacaciones").datepicker("option", "minDate","<?php echo $nominaActiva['fechainicio']; ?>");
// $("#fechafinalvacaciones").datepicker("option", "minDate","<?php echo $nominaActiva['fechainicio']; ?>");  
$("#fechapagovacaciones").datepicker("option", "minDate","<?php echo $nominaActiva['fechainicio']; ?>");   
$("#fechainiciovacaciones").datepicker("option", "maxDate","<?php echo $nominaActiva['fechafin']; ?>");
// $("#fechafinalvacaciones").datepicker("option", "maxDate","<?php echo $nominaActiva['fechafin']; ?>");  
$("#fechapagovacaciones").datepicker("option", "maxDate","<?php echo $nominaActiva['fechafin']; ?>"); 
$("#fechainicioincapacidad").datepicker("option", "minDate","<?php echo $nominaActiva['fechainicio']; ?>");
});

	function calculaVaciones(dias){
		$.post("ajax.php?c=Sobrerecibo&f=calculaVaciones",{
			idempleado:$("#empleado").val(),
			pinicial:'<?php echo $nominaActiva['fechainicio']; ?>',
			pfinal:'<?php echo $nominaActiva['fechafin']; ?>',
			fechainiciavac :  $("#fechainiciovacaciones").val()
// fechainiciaCaopa :  $("#fechainicioincapacidad").val()
},function (request){
	var dataJson = eval(request);
	if(!$("#diasvacprimavac").val()){ $("#diasvacprimavac").val(0);} 
//if(!$("#diasdescansoseptimo").val()){ $("#diasdescansoseptimo").val(0);} 
//se comento lo de arriba porq ahora se marcan las fechas q son del septimo dia
$("#diasdescansoseptimo").val(0);
for(var i in dataJson){
	$("#vacacionesacumuladas,#vacacionesacumuladasrespaldo").val( parseInt(dataJson[i].diastomados) + parseInt( dias ) - parseInt( $("#diasdescansoseptimo").val() ) );
	$("#vacapendientevacaciones,#vacapendientevacacionesrespaldo").val( parseInt(dataJson[i].diasrestantes) - parseInt ( dias ) + parseInt( $("#diasdescansoseptimo").val() ) );
	$("#primaacumuladovacaciones").val( parseFloat( $("#diasvacprimavac").val()) + parseFloat(dataJson[i].diasprima) );
	$("#diaprimapendientevacaciones").val( parseFloat(  $("#diasvacprimavac").val() ) + parseFloat(dataJson[i].diasprima) );
}
});
	}
</script>
<body>
	<br>
	<div class="container" style="width: 95%;" id="tabss">
		<ul class="nav nav-tabs ocultos">
			<li class="active"><a data-toggle="tab" href="#general" onclick="conceptos()"><b>Percepciones y Deducciones</b></a></li>
			<li><a data-toggle="tab" href="#obli" ><b>Obligaciones</b></a></li>
			<li><a data-toggle="tab" href="#acumu"><b>Acumulados</b></a></li>
			<li><a data-toggle="tab" href="#perma" onclick="permanentesEmpleado()"><b>Movtos. Permanentes</b></a></li>
			<li><a data-toggle="tab" href="#infonavit" onclick="infonavitEmpleado()"><b>Infonavit</b></a></li>
			<li><a data-toggle="tab" href="#fonacot" onclick="fonacotEmpleado()"><b>FONACOT</b></a></li>
			<li><a data-toggle="tab" href="#incapa" onclick="incapacidadEmpleado()"><b>Incapacidades</b></a></li>
			<li><a data-toggle="tab" href="#vaca" onclick="vacacionesEmpleado()"><b>Vacaciones</b></a></li>
		</ul>
		<div class="row tab-content" style="background:#F5F5F5;font-weight: bold"><br>
			<table class="bh ocultos" align="right" border="0">
				<tr style="background-color:rgb(217,237,247);height: 35px;">            
					<td width="40" align="right"> 
						<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0" onclick="printl()"></a>
					</td>      
					<td width="40" align="center"> 
						<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png"  
							title ="Enviar reporte por correo electrónico" border="0"> 
						</a>
					</td>
					<td width="40" align="left">
						<a href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif"  
							title ="Generar reporte en PDF" border="0"> 
						</a>
					</td>
				</tr>
			</table>
			<br>
			<input type="hidden" id="tabvalor"     value="general"/>
			<input type="hidden" id="nominaactiva" value="<?php echo $nominaActiva['idnomp']; ?>"/>
			<input type="hidden"   id="fechaactiva" value="<?php echo $nominaActiva['fechafin']; ?>"/>
			<div   class="row ocultos">
				<div   class="col-md-12">
					<div   class="col-xs-3">
						Empleado
						<select id="empleado" name="empleado" class="selectpicker" data-width="100%" data-live-search="true" onchange="datosEmp()">
							<option value="0" selected="">--Ninguno--</option>
							<?php 
							if( $listaEmpleados->num_rows>0 ){
								while($e = $listaEmpleados->fetch_object()){?>
								<option  idnomp="<?php echo $e->idnomp; ?>" value="<?php echo $e->idEmpleado; ?>"><?php echo $e->nombreEmpleado." ".$e->apellidoPaterno; ?></option>
								<?php } 
							}?>
						</select>
						<input type="hidden" id="idnompenvi" />
					</div>
					<div class="col-xs-3">
						Contrato
						<input type="text" id="contrato" name="contrato" class="form-control"  readonly=""/>
					</div>
					<div class="col-xs-3">
						Sueldo
						<input type="text" id="sueldo" name="sueldo" class="form-control" value=""   readonly=""/>
					</div>
					<div class="col-xs-3">
						UUID:
						<span id="uuid" name="uuid" class="label label-primary"></span>
					</div>
					<div class="col-xs-3">
						Estado:
						<span id="estado" name="estado" class="label label-primary">Sin sellar</span>
					</div>
				</div>
			</div> <!--DivOcultos-->
			<br>
			<div id="general" class="tab-pane fade in active">
				<div class="alert alert-info" id="imprimible">
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" id="loade" style="display: none"></i>
<div class="panel-body">
<DIV hidden class="mostrar" id="muestradiv"  style="overflow-x: scroll;">
<table class="mostrar letr" id="tableuno">
<tr>
<td style="width: 180px;">
<?php
$url = explode('/modulos',$_SERVER['REQUEST_URI']);
if($logo1 == 'logo.png') $logo1= 'x.png';
$logo1 = str_replace(' ', '%20', $logo1);	 
?>
<img src=<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/".$logo1;?> style="width: 180px;height: 35px;">
</td>
<td style="font-size: 12px" class="trsize" colspan="2">&nbsp;&nbsp;<b><?php echo $infoEmpresa['nombreorganizacion']?><?php echo $infoEmpresa['RFC']." ";?></b>
<br>&nbsp;&nbsp;<b>Reg. Pat <?php echo $regPatronal['registro']?></b>
</td>
<td>
</td>  
</tr>
</table> 
<table class="mostrar letr" id="tabledos"  style="font-size: 8px">
<tr style="padding-top: 30px;font-size: 12px;" class="trsize">
<td colspan="3" style="padding-right: 9px;padding-top: 10px;"><b><label id="emple" name="emple"></label></b>
<b><label id="codigo" name="codigo"></label></b></td>
<td style="padding-top: 10px;"><b>Departamento:</b><label id="dep" name="dep" class="tipnormal"></label> 
<b><label id="codigo" name="codigo" class="tipnormal"></label></b></td>
<td colspan="2" style="padding-top: 10px;">
<b>Nomina:</b>
<label id="nomina" name="nomina" class="tipnormal">
</label>
</td>
</tr>
<tr style="font-size: 12px;" class="trsize">
<td colspan="2">
<b>CURP:</b><label id="curp" name="curp" class="tipnormal"></label></td>
<td><b>IMSS:</b> 
<label id="nss" name="nss" class="tipnormal"></label></td>
<td colspan="2">
<b>RFC:</b> 
<label id="rfc" name="rfc" class="tipnormal"></label>
</td>
</tr>
<tr style="font-size: 12px;" class="trsize">

<td colspan="2"><b>Periodo:</b>
<label id="fechainicio" name="fechainicio" class="tipnormal"></label></td>
<td><b>Periodo:</b> 
<label id="numnomina" name="numnomina" class="tipnormal">
</label><b>N</b></td>
<td>
<b>Salario:</b>
<label id="salario" name="salario" class="tipnormal"></label></td>
<td>
<b>Jórnada:</b>
<label id="horas" name="horas" class="tipnormal"></label></td>
</tr>
<tr style="font-size: 12px;" class="trsize">
<td><b>Días Lab.Prop:</b><label id="diaslabproporcion" name="diaslabproporcion" onclick="editardias('diaslabproporcion')" class="tipnormal" title="Click para editar"></label><input id="i_diaslabproporcion" title="Presione ENTER para guardar y ESC para salir" style="display: none" onkeydown="guardaDias(event,'diaslabproporcion');" name="i_diaslabproporcion" class="tipnormal"/></td>
<td><b>Días Lab:</b><label id="diaslaborados" name="diaslaborados" onclick="editardias('diaslaborados')" class="tipnormal" title="Click para editar"></label><input id="i_diaslaborados" style="display: none" title="Presione ENTER para guardar y ESC para salir" onkeydown="guardaDias(event,'diaslaborados');" name="i_diaslaborados" class="tipnormal"/></td>
<td style="padding-right: 20px;"><b>Días Vac.Pagados:</b><label id="diasvac" name="diasvac" onclick="editardias('diasvac')" class="tipnormal" title="Click para editar"></label><input style="display: none" id="i_diasvac" title="Presione ENTER para guardar y ESC para salir"  onkeydown="guardaDias(event,'diasvac');" name="i_diasvac" class="tipnormal"/></td>
<td><b>Días Festivos:</b><label id="diasfestivo" name="diasfestivo" class="tipnormal" onclick="editardias('diasfestivo')" title="Click para editar"></label><input id="i_diasfestivo" name="i_diasfestivo" style="display: none" title="Presione ENTER para guardar y ESC para salir" onkeydown="guardaDias(event,'diasfestivo');" class="tipnormal"/></td>
<td><b>Días Pagados:</b><label id="diaspagados" name="diaspagados" class="tipnormal" onclick="editardias('diaspagados')" title="Click para editar"></label><input id="i_diaspagados" name="i_diaspagados" style="display: none"  title="Presione ENTER para guardar y ESC para salir" onkeydown="guardaDias(event,'diaspagados');" class="tipnormal"/></td>
</tr>
</table>
	</DIV>	
	<br>	
<section>
	<div class="col-md-12 panel panel-default alert alert-info" style="overflow-y: scroll; overflow-x: auto;display: block;";>
		<table cellpadding="0" class="tablasobrerecibo" style="border:solid 1px;font-size: 12.5px;" width="100%" id="tablasobrerecibo">
			<thead>
				<tr>
					<td colspan="4" align="center" class='iEncab' style="color:white">
						<b>Percepciones</b> 
					</td>
					<td colspan="4" align="center" class='iEncab' style="color:white">
						<b>Deducciones</b>
					</td>
				</tr> 
			</thead>
			<tbody>
				<tr>
					<td colspan="4" style="vertical-align: top;background-color: rgb(255,255,255);">
						<table style="font-weight: normal;" class=" tabpercdedu table-hover"  width="100%">
							<thead>
								<tr  class="titcolor">
									<th  style="text-align: center"><b>Clave</b></th>
									<th  style="text-align: left"><b>Concepto</b></th>
									<th  style="text-align: left;"><b>Importe</b></th>
									<th  style="text-align: center;" class='accOculta'><b>Acción</b></th>
								</tr>
							</thead>
							<tbody id="contPerce">
								<tr style="height: 35px;"></tr>
							</tbody>
						</table> 
					</td>
					<td colspan="4" style="vertical-align: top;background-color: rgb(255,255,255);">
						<table style="font-weight: normal;" class=" tabpercdedu table-hover" width="100%">
							<thead>
								<tr class="titcolor">
									<th  style="text-align: center"><b>Clave</b></th>
									<th  style="text-align: left"><b>Concepto</b></th>
									<th  style="text-align: left;"><b>Importe</b></th>
									<th  style="text-align: center;" class='accOculta'><b>Acción</b></th>
								</tr>
							</thead>
							<tbody id="contDeduccion"> 
								<tr style="height: 35px;"></tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr style="height: 30px;">
					<td align="right" colspan="2"><b>Suma de percepciones</b></td>
					<td align="right" id="tdSumaPercepciones" colspan="2"></td>
					<td align="right" colspan="2"><b>Suma de deducciones</b></td>
					<td align="right" id="tdSumaDeducciones" colspan="2"></td>
				</tr>
			</tfoot>
		</table>
	</section>

	<div class="row ocultos agregPD"  >
		<div class="form-group col-md-6"> 
			<label for="percepcion" class="col-sm-4 col-form-label" style="text-align: center">Agregar percepción</label>
			<div class="col-sm-5">
				<select  id="percepcion" name="percepcion" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0"> Seleccione</option> 
					<?php while($p = $selectperce->fetch_object()){?>
					<option value="<?php echo $p->idconcepto ?>"><?php echo "(".$p->concepto.")".' '.$p->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group col-md-6"> 
			<label for="deduccion" class="col-sm-4 col-form-label" style="text-align: center">Agregar deducción</label>
			<div class="col-sm-5">
				<select  id="deduccion" name="deduccion" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0"> Seleccione</option> 
					<?php while($p = $selectdedu->fetch_object()){?>
					<option value="<?php echo $p->idconcepto ?>"><?php echo "(".$p->concepto.")".' '.$p->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>
			<button type="button" class="btn btn-primary btn-sm"  id="guardar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" style="text-align: right"><span class="glyphicon glyphicon-floppy-disk" style="align-content: right" disabled="false"></span> Guardar</button>
		</div>
	</div>
	<br>
	<br>
	<div class="container-fluid row" style="text-align: right;font-size: 16px"> 
		<label style="font-family: Courier">NETO A PAGAR:</label>  
		<label id="resta"></label> 
	</div>
</div>
<div class="mostrar" hidden>
	<p class="firma">Recibo la cantidad asentada en “Neto a Pagar” por concepto de mi sueldo y demas prestaciones correspondientes al periodo que termina hoy, sin que a la fecha se me adeude ninguna cantidad.</p>
	<br>
	<div class="row">
		<div class="col-md-12" style="text-align: center">
			<p>____________________________________</p>
			<p>Firma</p>
		</div>
	</div>
</div>
</div>
</div> <!--Div general-->

<!--SEPARACION-->
<div id="obli" class="tab-pane fade">
</div>
<div id="acumu" class="tab-pane fade">
</div>
<div id="perma" class="tab-pane fade">
	<div class="">
		<div class="col-md-3" style="height: 300px">
			<ul class="nav lista" id="listaMovPermanentes">
			</ul>
		</div>
		<div class="row col-md-9 alert alert-info">
			<var id="nuevop" style="display: none" class="nuevo">N U E V O !</var>
			<var id="edicionp" style="display: none" class="edicion"></var>
			<input type="hidden" value="0" id="nuevosdatos" />
			<input type="hidden" value="0" id="ediciondatos" />
			<br>
			<div class="col-md-12">
				<div class="col-md-3">
					Descripcion<input type="text" name="descripcion" id="descripcion"  class="form-control input-md" />
				</div>
				<div class="col-xs-3">
					Tipo de concepto:
					<select id="tipoconcepto" name="tipoconcepto" onchange="tipoconcepto();" class="selectpicker" data-width="100%" data-live-search="true">
						<?php while ($e = $tipoconcepto->fetch_object()){ ?>
						<option value="<?php echo $e->idtipo;?>" ><?php echo $e->tipo;?> </option>
						<?php } ?>
					</select>
				</div>
				<div class="col-xs-3">
					Concepto:
					<select id="concepto" name="concepto" class="selectpicker" data-width="100%" data-live-search="true">
						<?php while ($e = $listaConceptos->fetch_object()){ ?>
						<option value="<?php echo $e->idconcepto;?>" ><?php echo $e->concepto." ".$e->descripcion;?> </option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-3">
					Fecha de inicio de aplicacion
					<input type="text" name="fechaaplicacionpermanente" id="fechaaplicacionpermanente"  class="fechas form-control input-md" />
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="col-xs-3">
					Importe/Valor
					<select id="importeOvalor" name="importeOvalor" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Importe</option>
						<option value="2">Valor</option>
					</select>
				</div>
				<div class="col-md-3" >
					Importe/Valor:
					<input type="text" name="imporvalor" id="imporvalor"  class="form-control input-md"/>
				</div>
				<div class="col-md-3" >
					Veces a Aplicar:
					<input type="text" name="vecesaplica" id="vecesaplica"  class="form-control input-md"/>
				</div>
				<div class="col-md-3" >
					Veces aplicado:
					<input type="text" name="vecesaplicadopermanente" id="vecesaplicadopermanente"  class="form-control input-md" />
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="col-xs-3">
					Monto limite:
					<input type="text" name="montolimite" id="montolimite"  class="form-control input-md"/>
				</div>
				<div class="col-xs-3">
					Monto acumulado:
					<input type="text" name="montoacumulado" id="montoacumulado"  class="form-control input-md"/>
				</div>
				<div class="col-xs-3">
					Fecha de registro
					<input type="text" name="fecharegistropermanente" id="fecharegistropermanente"  class="fechas form-control input-md"/>
				</div>
				<div class="col-xs-3">
					Numero control
					<input type="text" name="numcontrol" id="numcontrol"  class="form-control input-md" />
				</div>
				<div class="col-xs-3"><br>
					Estatus
					<select id="estatuspermanente" name="estatuspermanente" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Activo</option>
						<option value="0">Inactivo</option>
					</select>
				</div>
			</div>
		</div>
	</div><br>
	<div style="" class="funciones col-md-3" align="right">
		<button title="Nuevo Mov. Permanente" onclick="javascript:nuevo('perma','nuevop')">
			<span class="glyphicon-plus"></span>
		</button>
		<button title="Guarda Mov. Permanente" onclick="javascript:guarda('perma','loadpermanente','gpermanente')">
			<span class="glyphicon glyphicon-floppy-disk" id="gpermanente"></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadpermanente" style="display: none"></i>
		</button>
		<button title="Elimina Mov. Permanente" onclick="javascript:elimina('perma','loadpermanente2','gpermanenete2')">
			<span class="glyphicon glyphicon-trash" id="gpermanenete2"></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadpermanente2" style="display: none"></i>
		</button>
	</div>
</div>
<div id="infonavit" class="tab-pane fade">
	<div class="">
		<div class="col-md-3">
			<ul class="nav lista" id="listaInfonavit">
			</ul>
		</div>
		<div class="row col-md-9 alert alert-info">
			<var id="nuevoinfo" style="display: none" class="nuevo">N U E V O !</var>
			<var id="edicioni" style="display: none" class="edicion"></var>
			<br>
			<div class="col-md-12">
				<div class="col-md-4" >
					Numero de credito Infonavit
					<input type="text" name="numinfonavit" id="numinfonavit"  class="form-control input-md" />
				</div>
				<div class="col-md-4">
					Descripcion
					<input type="text" name="descripcioninfonavit" id="descripcioninfonavit"  class="form-control input-md" />
				</div>
				<div class="col-xs-4">
					Tipo credito infonavit
					<select id="tipocreditoinfonavit" name="tipocreditoinfonavit" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Movto. Permanente ( Concepto D-59)</option>
						<option value="2">Porcentaje ( Concepto D-59)</option>
						<option value="3">Veces salario minimo ( Concepto D-15)</option>
						<option value="4">Cuota fija ( Concepto D-16)</option>
					</select>
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="col-md-4" >
					Factor mensual
					<input type="text" name="factormensual" id="factormensual"  class="form-control input-md" />
				</div>
				<div class="col-xs-4">
					Incluir pago de seguro (Concepto D-14)
					<select id="incluirpagoseguro" name="incluirpagoseguro" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">SI</option>
						<option value="0">NO</option>
					</select>
				</div>
				<div class="col-md-4" >
					Fecha de inicio de aplicacion
					<input type="text" name="fechaaplicacioninfonavit" id="fechaaplicacioninfonavit"  class="fechas form-control input-md" />
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="col-md-4" >
					Monto acumulado
					<input type="text" name="montoacumuladoinfonavit" id="montoacumuladoinfonavit"  class="form-control input-md" />
				</div>
				<div class="col-md-4" >
					Veces aplicado
					<input type="text" name="vecesaplicadoinfonavit" id="vecesaplicadoinfonavit"  class="form-control input-md" />
				</div>
				<div class="col-xs-4">
					Fecha de registro
					<input type="text" name="fecharegistroinfonavit" id="fecharegistroinfonavit"  class="fechas form-control input-md" />
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="col-xs-4">
					Estatus
					<select id="estatusinfonavit" name="estatusinfonavit" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Activo</option>
						<option value="0">Inactivo</option>
					</select>
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="panel panel-warning" style="font-weight:200 ">
					<div class="panel-heading">Conceptos usados para INFONAVIT</div>
					<div class="panel-body">
						D-14. Seguro de vivienda Infonavit<br>
						D-15. Prestamo infonavit(vsm)
						D-16. Prestamo infonavit(cf)<br>
						* Si sus conceptos deduccion 14 y/o 15 no pertenecen a INFONAVIT, debera realizar la conversion INFONAVIT 2012.<br>
						* Si su concepto deduccion 16 no pertenece a INFONAVIT, debera realizar la conversion INFONAVIT 2016
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="" class="funciones col-md-3" align="right">
		<button title="Nuevo credito infonavit" onclick="javascript:nuevo('infonavit','nuevoinfo')">
			<span class="glyphicon-plus"></span>
		</button>
		<button title="Guarda credito infonavit" onclick="javascript:guarda('infonavit','loadinfonavit','ginfonavit')">
			<span class="glyphicon glyphicon-floppy-disk" id='ginfonavit'></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadinfonavit" style="display: none"></i>
		</button>
		<button title="Elimina credito infonavit" onclick="javascript:elimina('infonavit','loadinfonavit2','ginfonavit2')">
			<span class="glyphicon glyphicon-trash" id='ginfonavit2'></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadinfonavit2" style="display: none"></i>
		</button>
	</div>
</div>
<div id="fonacot" class="tab-pane fade">
	<div class="">
		<div class="col-md-3">
			<ul class="nav lista" id="listafonacot">
			</ul>
		</div>
		<div class="row col-md-9 alert alert-info">
			<var id="nuevofonacot" style="display: none" class="nuevo">N U E V O !</var>
			<var id="edicionf" style="display: none" class="edicion"></var>
			<br>
			<div class="col-md-12">
				<div class="col-md-3" >
					Numero de credito
					<input type="text" name="numcreditofonacot" id="numcreditofonacot"  class="form-control input-md" />
				</div>
				<div class="col-md-3" >
					Descripcion
					<input type="text" title="Descripcion del codigo FONACOT" name="descripcionfonacot" id="descripcionfonacot"  class="form-control input-md" />
				</div>
				<div class="col-xs-3">
					Mes
					<select title="Mes de inicio de la retencion del credito FONACOT" id="mesfonacot" name="mesfonacot" class="selectpicker" data-width="100%" data-live-search="true">
						<?php while($m = $listameses->fetch_object()){?>
						<option value="<?php echo $m->id ?>"><?php echo $m->mes; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-3" >
					Ejercicio
					<input  onKeyDown="if(this.value.length == 4)return false;" title="Ejercicio de inicio de la retencion del credito FONACOT" type="number" name="ejerciciofonacot" id="ejerciciofonacot"  class="form-control input-md" value="2017"/>
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="col-md-3">
					Calculo de la retencion
					<select title="Metodo de calculo para la retencion" onchange="metodo();" id="calculoretencion" name="calculoretencion" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Importe Fijo</option>
						<option value="2">Proporcion a dias trabajados</option>
					</select>
				</div>
				<div class="col-md-3">
					Importe del credito
					<input type="text" onKeyup="calculasaldofonacot()"  title="Importe total del credito FONACOT" name="importecreditofonacot" id="importecreditofonacot"  class="form-control input-md" />
				</div>
				<div class="col-md-3" >
					<label id="tipoimporte">Importe Fijo</label>
					<input type="text" title="Importe de la retencion mensual del credito FONACOT" name="retencionmensual" id="retencionmensual"  class="form-control input-md" />
				</div>
				<div class="col-md-3" >
					Pagos hechos por otros patrones
					<input type="text" onKeyup="calculasaldofonacot()" title="Pagos realizados por otros patrones o por el mismo empleado" name="pagohechosotros" id="pagohechosotros"  class="form-control input-md" />
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="col-md-3" >
					Monto acumulado retenido
					<input type="text" readonly=""  name="retenidoacumulado" id="retenidoacumulado"  class="form-control input-md" />
				</div>
				<div class="col-md-3" >
					Saldo <!-- pagos hechos otros patrones - importe credito -->
					<input type="text" readonly="" name="saldofonacot" id="saldofonacot"  class="form-control input-md" />
				</div>
				<div class="col-xs-3">
					Estatus
					<select id="estatusfonacot" name="estatusfonacot" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Activo</option>
						<option value="0">Inactivo</option>
					</select>
				</div>
			</div>
			<div class="col-md-12"><br>
				<div class="panel panel-warning" >
					<div class="panel-heading">Observaciones</div>
					<div class="panel-body" style="font-weight:200 ">
						<textarea class="form-control input-md" id="observacionesfonacot" name="observacionesfonacot"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="" class="funciones col-md-3" align="right">
		<button title="Nuevo credito fonacot" onclick="javascript:nuevo('fonacot','nuevofonacot')">
			<span class="glyphicon-plus"></span>
		</button>
		<button title="Guarda credito fonacot" onclick="javascript:guarda('fonacot','loadfonacot','gfonacot')">
			<span class="glyphicon glyphicon-floppy-disk" id='gfonacot'></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadfonacot" style="display: none"></i>
		</button>
		<button title="Elimina credito fonacot" onclick="javascript:elimina('fonacot','loadfonacot2','gfonacot2')">
			<span class="glyphicon glyphicon-trash" id='gfonacot2'></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadfonacot2" style="display: none"></i>
		</button>
	</div>
</div>
<div id="incapa" class="tab-pane fade">
	<!-- <div class=""> -->
	<div class="col-md-3">
		<ul class="nav lista" id='listaincapacidad'>
		</ul>
	</div>
	<div class="row col-md-9 alert alert-info">
		<var id="nuevoinc" style="display: none" class="nuevo">N U E V O !</var>
		<var id="edicioninc" style="display: none" class="edicion"></var>
		<br>
		<div class="col-md-12">
			<div class="col-md-3" >
				Folio
				<input type="text" onKeyDown="if(this.value.length == 8)return false;" name="folioincapacidad" id="folioincapacidad"  class="form-control input-md" />
			</div>
			<div class="col-xs-3">
				Tipo de incidencia
				<select  id="tipoincidenciaincapacidad" name="tipoincidenciaincapacidad" class="selectpicker" data-width="100%" data-live-search="true">
					<?php while($m = $incapacidades->fetch_object()){?>
					<option value="<?php echo $m->idtipoincidencia ?>"><?php echo $m->clave." - ".$m->nombre; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-3">
				Dias autorizados
				<input type="text"  name="diasautorizadosincapacidad" id="diasautorizadosincapacidad"  class="form-control input-md" />
			</div>
			<div class="col-md-3" >
				Fecha de inicio
				<input type="text"  name="fechainicioincapacidad" id="fechainicioincapacidad"  class=" form-control input-md" />
			</div>
		</div>
		<div class="col-md-12"><br>
			<div class="col-xs-3">
				Ramo de seguro
				<select  id="ramoincapacidad" onchange="ramoincapacidad()" name="ramoincapacidad" class="selectpicker" data-width="100%" data-live-search="true">
					<?php while($m = $ramoIncapacidad->fetch_object()){?>
					<option value="<?php echo $m->clave ?>"><?php echo $m->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-xs-3">
				Tipo de riesgo
				<select  id="tiporiesgoincapacidad"  name="tiporiesgoincapacidad" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="1">Accidente</option>
					<option value="2">Enfermedad</option>
					<option value="3">Ninguno</option>
				</select>
			</div>
			<div class="col-md-3" >
				% de Incapacidad
				<input type="text" name="porcentajeincapacidad" id="porcentajeincapacidad"  class="form-control input-md" />
			</div>
		</div>
		<div class="col-md-12"><br>
			<div class="col-xs-3">
				Secuela o Consecuencia
				<select  id="secuelaincapacidad" name="secuelaincapacidad" class="selectpicker" data-width="100%" data-live-search="true">
					<?php while($m = $secuelaconsecuencia->fetch_object()){?>
					<option value="<?php echo $m->idsecuela ?>"><?php echo $m->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-xs-3">
				Control incapacidad
				<select  id="controlincapacidad" name="controlincapacidad" class="selectpicker" data-width="100%" data-live-search="true">
					<?php while($m = $controlIncapacidad->fetch_object()){?>
					<option value="<?php echo $m->idcontrol ?>"><?php echo $m->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-md-12"><br>
			<div class="panel panel-warning" >
				<div class="panel-heading">Descripcion precisa de los hechos y su ubicacion</div>
				<div class="panel-body" style="font-weight:200 ">
					<textarea class="form-control input-md" id="hechosincapacidad" name="hechosincapacidad"></textarea>
				</div>
			</div>
		</div>
	</div>
	<!-- </div> -->
	<div style="" class="funciones col-md-3" align="right">
		<button title="Nueva incapacidad" onclick="javascript:nuevo('incapa','nuevoinc')">
			<span class="glyphicon-plus"></span>
		</button>
		<button title="Guarda incapacidad" onclick="javascript:guarda('incapa','loadinc','ginc')">
			<span class="glyphicon glyphicon-floppy-disk" id='ginc'></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadinc" style="display: none"></i>
		</button>
		<button title="Elimina incapacidad" onclick="javascript:elimina('incapa','loadinc2','ginc2')">
			<span class="glyphicon glyphicon-trash" id='ginc2'></span>
			<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadinc2" style="display: none"></i>
		</button>
	</div>
</div>
<div id="vaca" class="tab-pane fade">
	<div class="">
		<div class="col-md-3">
			<ul class="nav lista" id="listavacaciones">
			</ul>
		</div>
		<div class="row col-md-9 alert alert-info">
			<var id="nuevovaca" style="display: none" class="nuevo">N U E V O !</var>
			<var id="edicionvaca" style="display: none" class="edicion"></var>
			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="col-xs-3">
						Tipo de captura
						<select  id="tipocapturavacaciones" onchange="tipocaptura()" name="tipocapturavacaciones" class="selectpicker" data-width="100%" data-live-search="true">
							<option value="1">Solo prima vacacional</option>
							<option value="2">Vacaciones y prima vacacional</option>
						</select>
					</div>
					<div class="col-md-3">
						Fecha inicial
						<input type="text"  name="fechainiciovacaciones" id="fechainiciovacaciones"  class=" form-control input-md" />
					</div>
					<div class="col-md-3">
						Fecha final
						<input type="text"  name="fechafinalvacaciones" id="fechafinalvacaciones"  class=" form-control input-md" />
					</div>
					<div class="col-md-3">
						Fecha pago
						<input type="text"  name="fechapagovacaciones" id="fechapagovacaciones"  class=" form-control input-md" />
					</div>
				</div>
			</div>
			<br>
			<br>
			<div class="row">
				<div class="col-md-12">
					<div class='col-xs-3'>
						<div class="form-group">
							Número de dias de descanso y/o septimos dias
							<div class='input-group date'>
								<input type="text"   name="diasdescansoseptimo" id="diasdescansoseptimo" value="0" class="form-control input-md" />
								<span class="input-group-addon" >
									<span> <input type="hidden" name="fechadescanso"  
										id="fechadescanso"  class="form-control input-md" /></span>
									</span>
								</div>
							</div>
						</div>
						<div class="col-xs-3">
							<br>
							Dias de vacaciones
							<input type="text" name="diasvacaciones" id="diasvacaciones"  class="form-control input-md" value="0" readonly=""/>
							<input type="hidden"  id="diasvacacionesrespaldo" />
						</div>
						<div class="col-xs-3">
							<br>
							Prima vac. de Dias de vac.
							<input type="text" onkeyup="diastranscurridos()" name="diasvacprimavac" id="diasvacprimavac" class="form-control input-md" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12"><br>
						<hr>
						<div class="col-xs-3">
							Vacaciones acumuladas
							<input type="text" readonly="" name="vacacionesacumuladas" id="vacacionesacumuladas"  class="form-control input-md" />
							<input type="hidden" id="vacacionesacumuladasrespaldo">
						</div>
						<div class="col-xs-3">
							Dias de prima acumulados
							<input type="text" readonly="" name="primaacumuladovacaciones" id="primaacumuladovacaciones"  class="form-control input-md" />
						</div>
						<div class="col-xs-3">
							Vacaciones pendientes
							<input type="text" readonly="" name="vacapendientevacaciones" id="vacapendientevacaciones"  class="form-control input-md" />
							<input type="hidden" id="vacapendientevacacionesrespaldo">
						</div>
						<div class="col-xs-3">
							Dias de prima pendientes
							<input type="text" readonly=""  name="diaprimapendientevacaciones" id="diaprimapendientevacaciones"  class="form-control input-md" />
						</div>
					</div>
				</div>
				<div class="col-md-12"><br>
					<div class="alert alert-warning">
						Las vacaciones acumuladas y pendientes se calculan a partir del estatus del empleado.
					</div>
				</div>
			</div>
		</div>
		<div style="" class="funciones col-md-3" align="right">
			<button title="Nuevas vacaciones" onclick="javascript:nuevo('vaca','nuevovaca')">
				<span class="glyphicon-plus"></span>
			</button>
			<button title="Guarda periodo vacaciones" onclick="javascript:guarda('vaca','loadvaca','gvaca')">
				<span class="glyphicon glyphicon-floppy-disk" id='gvaca'></span>
				<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadvaca" style="display: none"></i>
			</button>
			<button title="Elimina periodo vacaciones" onclick="javascript:elimina('vaca','loadvaca2','gvaca2')">
				<span class="glyphicon glyphicon-trash" id='gvaca2'></span>
				<i class="fa fa-spinner fa-pulse fa-1x fa-fw margin-bottom" id="loadvaca2" style="display: none"></i>
			</button>
		</div>
	</div>
	<br>
</div>
</div>
<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Generar PDF</h4>
			</div>
			<form id="formpdf" action="../cont/libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<label>Escala (%):</label>
							<select id="cmbescala" name="cmbescala" class="form-control">
								<?php
								for($i=100; $i > 0; $i--){
									echo '<option value='. $i .'>' . $i . '</option>';
								}
								?>
							</select>
						</div>
						<div class="col-md-6">
							<label>Orientación:</label>
							<select id="cmborientacion" name="cmborientacion" class="form-control">
								<option value='P'>Vertical</option>
								<option value='L'>Horizontal</option>
							</select>
						</div>
					</div>
					<textarea id="contenido" name="contenido" style="display:none"></textarea>
					<input type='hidden' name='tipoDocu' value='hg'>
					<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
					<input type='hidden' name='nombreDocu' value='Detalle Nomina'>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<input type="submit" value="Crear PDF" autofocus class="btn btn-primary btnMenu">
						</div>
						<div class="col-md-6">
							<input type="button" value="Cancelar" onclick="cancelar_pdf()" class="btn btn-danger btnMenu">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
	<div id="divmsg" style="
	opacity:0.8;
	position:relative;
	background-color:#000;
	color:white;
	padding: 20px;
	-webkit-border-radius: 20px;
	border-radius: 10px;
	left:-50%;
	top:-200px
	">
	<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
	</center>
</div>
</div>
<script>
	function cerrarloading(){
		$("#loading").fadeOut(0);
		var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
		$("#divmsg").html(divloading);
	}
</script>
</body>
</html>
