<!DOCTYPE html>
<head>
	<!-- <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/ingresos.css" />
	<script src="../cont/js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="js/egresos.js"></script>
	<link rel="stylesheet" href="css/style.css" type="text/css"> -->

</head>
<style>
  	.focused {
    background: #abcdef;
	}
	.fila-baser{ display: none; } /* fila base oculta */
	.eliminarr{ cursor: pointer; color: #000; }
</style>
<body>
	
  <div class="modal-dialog" role="document" style="width: 75%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Documento de Retencion</h4>
      </div>
      <div class="modal-body">
      	<?php
		if($facturacion == 1){?> 
<form action="index.php?c=Cheques&f=crearRetencionFact" method="post" id="formretencion" name="formretencion">
<div class="panel panel-info" ><div class="panel-heading" align="left">
	<b >Datos Generales de la retencion</b>
</div>
	<div class="panel-body">
		  
		<div class="row">
			<div class="col-md-3">
				<input type="hidden" name="idincluir" id="idincluir" />
				<input type="hidden" name="tipoegreso" value="<?php echo $_REQUEST['tipo'];?>">
				<b>Tipo de Complemento:</b>
				 
					<select id="CveRetenc" name="CveRetenc" class="selectpicker" data-width="100%" data-live-search="true" onchange="ValidaComplemento(this.value)">
						<?php while($r = $complementos->fetch_assoc()){ ?>
							<option value="<?php echo $r['clave'];?>"><?php echo $r['nombre']; ?></option>
						<?php } ?>
					</select>
				
			</div>
			<div class="col-md-3">
				<b>Fecha</b><b style="color: red">*</b>
				<input type="text" id="fechar" name="fechar" class="form-control" readonly=""/>
			</div>
			<div class="col-md-3">
				<b>Beneficiario</b><b style="color: red">*</b>
				<select id="beneficiarior" name="beneficiarior" class="selectpicker" data-width="100%" data-live-search="true">
					<?php while($c = $benefeciarior->fetch_array()){ ?>
						<option value="<?php echo $c['idPrv']; ?>"><?php echo $c['razon_social']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-3">
				<b>Referencia:</b>
				<input type="text" id="referenciar" name="referenciar" class="form-control" placeholder="Escriba su Ref. aqui...">
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<b>Periodo Inicial</b>
				<select id="pInicial" name="pInicial" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Enero</option>
						<option value="2">Febreo</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
			</div>
			<div class="col-md-3">
				<b>Periodo Final</b>
				<select id="pFinal" name="pFinal" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="1">Enero</option>
						<option value="2">Febreo</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
			</div>
			<div class="col-md-3">
				<b>Ejercicio:</b>
				<select id="ejercicior" name="ejercicior" class="selectpicker" data-width="100%" data-live-search="true">
				<?php
					 while ($row = $ejercicios->fetch_assoc()) {?>
						<option value="<?php echo $row['NombreEjercicio'];?>"><?php echo $row['NombreEjercicio'];?></option>
				<?php }	?>	
				</select>
			</div>
			<div class="col-md-3" id="incDoc"><br>
				<input type="checkbox" id="inluirdocumento" onclick="incluir()"><b style="color: red">Retencion asociada a Documento</b>
				<input type="hidden" id="incluirdoc" name="incluirdoc" value="0">
			</div>
		</div>
	</div>
</div>
<div class="panel panel-info" ><div class="panel-heading" align="left"><b >Impuestos</b></div>
	<div class="panel-body">   
		<table id="tretencion" width="100%" >
			<thead>
			<tr>
				<th align="right">Importe base del impuesto</th>
				<th align="center">Tipo de Impuesto</th>
				<th align="center">Impuesto retenido</th>
				<th align="center">Tipo de Pago</th>
			</tr>
			</thead>
			<tbody>
			<tr  class="fila-baser">
				<td><input type="text" placeholder="0.00" name="importebase[]"  class="imp form-control" onkeypress="return decimalescomplementos(event,this)"/></td>
				<td align="center">
					<select name="retencionlistar[]" >
						<option value="01">ISR</option>
						<option value="02">IVA</option>
						<option value="03">IEPS</option>
					</select>
				</td>
				<td><input type="text" placeholder="0.00" class="form-control" name="impuestoretenido[]" onkeypress="return decimalescomplementos(event,this)"/></td>
				<td align="center">
					<select id="tipopagor" name="tipopagor[]">
						<option value="Pago definitivo">Pago Definitivo</option>
						<option value="Pago provisional">Pago Provisional</option>
					</select>
				</td>
				<!-- <td class="eliminarr">Eliminar</td> -->
			</tr>
			</tbody>
		</table><br>
		<input type="button" id="agregarr" value="+" title="Agregar Impuesto" align="left"/>
	</div>
</div>
<div class="panel panel-info" ><div class="panel-heading" align="left">Totales</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<b>Total monto de Operaciones:</b><b style="color: red">*</b>
				<input type="text" placeholder="0.00" class="form-control" name="montoTotOperacion" id="montoTotOperacion" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-3">
				<b>Total Gravado:</b><b style="color: red">*</b>
				<input type="text" placeholder="0.00"  class="form-control" name="montoTotGrav" id="montoTotGrav" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-3">
				<b>Total Exento:</b><b style="color: red">*</b>
				<input type="text" placeholder="0.00" class="form-control" name="montoTotExent" id="montoTotExent" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-3">
				<b>Total de Retenciones:</b><b style="color: red">*</b>
				<input type="text" placeholder="0.00" class="form-control" name="montoTotRet" id="montoTotRet" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
		</div>
	</div>
</div>
<br>	
<div class="panel panel-info" ><div class="panel-heading" align="left">Informacion del Complemento</div>
	<div class="panel-body" id="enajenacion" style="display: none">
		<h4 style="color: #727272">Enajenacion de Acciones</h4><br>
		<div class="row">
			<div class="col-md-12" style="color: #8A2908">
				Contrato de Intermediacion<b style="color: red">*</b>
			
				<input type="text" maxlength="100" class="form-control" id="ContratoIntermediacion" name="ContratoIntermediacion">
			
				Importe de la ganancia obtenida<b style="color: red">*</b>
				<input type="text" class="form-control" name="Ganancia" id="Ganancia" onkeypress="return decimalescomplementos(event,this)"/>
			
				Importe de perdida en el contrato de intermediacion<b style="color: red">*</b>
				<input type="text" class="form-control" id="Perdida" name="Perdida" onkeypress="return decimalescomplementos(event,this)"/>

			</div>
		</div>
	</div><!-- fin enajenacion -->
	<div class="panel-body" id="dividendos" style="display: none">
			<h4 style="color: #727272">Dividendos</h4><br>
		<div class="row" style="color: #8A2908">
			<div class="col-md-4">
				Tipo de dividendo<b style="color:red">*</b>
				<select id="CveTipDivOUtil" name="CveTipDivOUtil" class="selectpicker" data-width="100%" data-live-search="true">
					<?php
						 while ($row = $tipodividendo->fetch_assoc()) {?>
							<option value="<?php echo $row['clave'];?>"><?php echo $row['nombre'];?></option>
					<?php }	?>	
				</select>
			</div>
			<div class="col-md-4" style="color: #8A2908">
				Tipo de sociedad<b style="color:red">*</b>
				<select id="TipoSocDistrDiv" name="TipoSocDistrDiv" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">-Ninguno-</option>
					<option value="Sociedad Extranjera">Sociedad Extranjera</option>
					<option value="Sociedad Nacional">Sociedad Nacional</option>
				</select>
			</div>
			<div class="col-md-4" style="color: #8A2908" >
				ISR acreditable nacional
				<input type="text" class="form-control" id="MontISRAcredNal" name="MontISRAcredNal" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4" style="color: #8A2908" >
				Dividendo acumulable nacional
				<input type="text" class="form-control" id="MontDivAcumNal" name="MontDivAcumNal" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4" style="color: #8A2908" >
				Dividendo acumulable extranjero
				<input type="text" class="form-control" id="MontDivAcumExt" name="MontDivAcumExt" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4" style="color: #8A2908">
				Porcentaje de participacion acciones
				<input type="text" class="form-control" id="ProporcionRem" name="ProporcionRem" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4" style="color: #8A2908">
				Retencion dividendo en territorio nacional<b style="color:red">*</b>
				<input type="text" class="form-control" id="MontISRAcredRetMexico" name="MontISRAcredRetMexico" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4" style="color: #8A2908">
				Retencion en el extr. sobre dividendo en el extr.
				<input type="text" class="form-control" id="MontRetExtDivExt" name="MontRetExtDivExt" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4" style="color: #8A2908">
				Retencion dividendo en territorio extranjero<b style="color:red">*</b>
				<input type="text" class="form-control" id="MontISRAcredRetExtranjero" name="MontISRAcredRetExtranjero" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			
		</div>
	</div> <!-- fin dividendos -->
	<div class="panel-body" id="intereses" style=" display: none">
		<h4 style="color: #727272">Intereses</h4><br>
		<div class="row" style="color: #8A2908">
			<div class="col-md-4">
				Intereses provienen del sistema financiero<b style="color:red">*</b>
				<select  class="selectpicker" data-width="100%" data-live-search="true" id="SistFinanciero" name="SistFinanciero">
					<option value="0">-Ninguno-</option>
					<option value="NO">NO</option>
					<option value="SI">SI</option>
				</select>
			</div>
			<div class="col-md-4">
				Intereses retirados en el periodo o ejercicio<b style="color:red">*</b>
				<select id="RetiroAORESRetInt" name="RetiroAORESRetInt" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">-Ninguno-</option>
					<option value="NO">NO</option>
					<option value="SI">SI</option>
				</select>
			</div>
			<div class="col-md-4">
				 Corresponden a operac. financ. deriv.<b style="color:red">*</b>
				<select id="OperFinancDerivad" name="OperFinancDerivad" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">-Ninguno-</option>
					<option value="NO">NO</option>
					<option value="SI">SI</option>
				</select>
			</div>
		</div>
		<div class="row" style="color: #8A2908">
			<div class="col-md-4">
				Total interes nominal<b style="color:red">*</b>
				<input type="text" class="form-control" id="MontIntNominal" name="MontIntNominal" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4">
				Importe intereses reales<b style="color:red">*</b>
				<input type="text" class="form-control" id="MontIntReal" name="MontIntReal" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4">
				Perdida por intereses obtenidos<b style="color:red">*</b>
				<input type="text" class="form-control" id="PerdidaInteres" name="Perdida" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			
		</div>
		
	</div> <!-- fin intereses -->
	<div class="panel-body" id="arrendamiento" style=" display: none">
		<h4 style="color: #727272">Arrendamientos en fideicomisos</h4><br>
		<div class="row" style="color: #8A2908">
			<div class="col-md-4">
				Importe del pago efectuado al arrendador<b style="color:red">*</b>
				<input type="text" class="form-control" id="PagProvEfecPorFiduc" name="PagProvEfecPorFiduc" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4">
				Importe de rendimientos obtenidos<b style="color:red">*</b>
				<input type="text" class="form-control" id="RendimFideicom" name="RendimFideicom" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4">
				Importe otros conceptos distribuidos
				<input type="text" class="form-control" id="MontOtrosConceptDistr" name="MontOtrosConceptDistr" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			
		</div>
		<div class="row" style="color: #8A2908">
			<div class="col-md-4">
				Resultado fiscal distribuido por FIBRAS
				<input type="text" class="form-control" id="MontResFiscDistFibras" name="MontResFiscDistFibras" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4">
				Deduccion correspondientes al arrendamiento<b style="color:red">*</b>
				<input type="text" class="form-control" id="DeduccCorresp" name="DeduccCorresp" onkeypress="return decimalescomplementos(event,this)"/>
			</div>
			<div class="col-md-4">
				Descripcion otros conceptos distribuidos
				<input type="text" maxlength="100" class="form-control" id="DescrMontOtrosConceptDistr" name="DescrMontOtrosConceptDistr"/>
			</div>
		</div>
	</div> <!-- fin arrendamientos -->
		
		<div class="panel-body" id="pagoextranjero" style="display:none">
			<h4 style="color: #727272">Pagos a extranjeros</h4><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Beneficiario del pago es misma persona que retiene<b style="color:red">*</b>
					<select id="EsBenefEfectDelCobro" name="EsBenefEfectDelCobro" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">-Ninguno-</option>
						<option value="NO">NO</option>
						<option value="SI">SI</option>
					</select>
				</div>
				<div class="col-md-6">
					RFC representante legal en Mexico<b style="color:red">*</b>
					<input type="text" maxlength="13" class="form-control" id="rfc" name="rfc" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Pais residencia del extranjero<b style="color:red">*</b>
					<select id="PaisDeResidParaEfecFisc" name="PaisDeResidParaEfecFisc" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">-Ninguno-</option>
						<?php while($row = $paises->fetch_assoc()){?>
							<option value="<?php echo $row['clave'];?>"><?php echo $row['clave'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-6">
					CURP representante legal en Mexico<b style="color:red">*</b>
					<input type="text" maxlength="18" class="form-control" id="CURP" name="CURP" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
			
				<div class="col-md-6">
					
				</div>
				<div class="col-md-6">
					Nombre o razo social contribuyente<b style="color:red">*</b>
					<input type="text" maxlength="100" class="form-control" id="NomDenRazSocB" name="NomDenRazSocB"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Tipo contribuyente sujeto a la retencion<b style="color:red">*</b>
					<select id="TipoContribuyenteSujetoRetencionNoBene" name="TipoContribuyenteSujetoRetencionNoBene" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">-Ninguno-</option>
						<?php while($row = $contribuyente->fetch_assoc()){?>
							<option value="<?php echo $row['clave'];?>"><?php echo $row['nombre'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-6">
					Tipo contribuyente sujeto a retencion<b style="color:red">*</b>
					<select id="TipoContribuyenteSujetoRetencionBene" name="TipoContribuyenteSujetoRetencionBene" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">-Ninguno-</option>
						<?php while($row = $contribuyente2->fetch_assoc()){?>
							<option value="<?php echo $row['clave'];?>"><?php echo $row['nombre'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Descripcion del pago al residente en el extranjero(No Beneficiario)<b style="color:red">*</b>
					<input type="text" maxlength="100" class="form-control" id="ConceptoPagoNo" name="ConceptoPagoNo"/>
				</div>
				<div class="col-md-6">
					Descripcion del pago al residente en el extranjero(Beneficiario)<b style="color:red">*</b>
					<input type="text" maxlength="100" class="form-control" id="ConceptoPago" name="ConceptoPago"/>
				</div>
			</div>
			
		</div> <!-- fin pago extranjero -->
		<div class="panel-body" id="premios" style="display: none">
			<h4 style="color: #727272">Premios</h4><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Entidad federativa<b style="color:red">*</b>
					<select id="EntidadFederativa" name="EntidadFederativa" class="selectpicker" data-width="100%" data-live-search="true">
						<!-- <option value="0">-Ninguno-</option> -->
						<?php while($row = $estados->fetch_assoc()){?>
							<option value="<?php echo $row['idestado'];?>"><?php echo $row['estado'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-6">
					Importe pagado<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontTotPago" name="MontTotPago" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Importe gravado<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontTotPagoGrav" name="MontTotPagoGrav" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Importe exento<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontTotPagoExent" name="MontTotPagoExent" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
		</div> <!-- fin premios -->
		
		<div class="panel-body" id="fideicomiso" style="display: none">
			<h4 style="color: #727272">Fideicomisos no empresariales</h4><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Total ingresos en el periodo<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontTotEntradasPeriodo" name="MontTotEntradasPeriodo" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Total egresos en el periodo<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontTotEgresPeriodo" name="MontTotEgresPeriodo" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Parte proporcional ingresos acumulables<b style="color:red">*</b>
					<input type="text" class="form-control" id="PartPropAcumDelFideicom" name="PartPropAcumDelFideicom" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Parte proporcional deducciones<b style="color:red">*</b>
					<input type="text" class="form-control" id="PartPropDelFideicom" name="PartPropDelFideicom" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Ingresos proporcion de participacion<b style="color:red">*</b>
					<input type="text" class="form-control" id="PropDelMontTot" name="PropDelMontTot" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Egresos proporcion participacion<b style="color:red">*</b>
					<input type="text" class="form-control" id="PropDelMontTotEg" name="PropDelMontTotEg" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Concepto de ingresos<b style="color:red">*</b>
					<textarea maxlength="100" class="form-control" id="Concepto" name="Concepto"></textarea>
				</div>
				<div class="col-md-6">
					Concepto de egresos<b style="color:red">*</b>
					<textarea maxlength="100" class="form-control" id="ConceptoS" name="ConceptoS"></textarea>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Monto de retencion<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontRetRelPagFideic" name="MontRetRelPagFideic" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Descripcion retencion<b style="color:red">*</b>
					<textarea maxlength="100" maxlength="100" class="form-control" id="DescRetRelPagFideic" name="DescRetRelPagFideic" ></textarea>
				</div>
			</div>
		</div><!-- fin fideicomisos -->
		<div class="panel-body" id="retiro" style="display: none">
			<h4 style="color: #727272">Planes de retiro</h4><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Planes de retiro son del sistema financiero<b style="color:red">*</b>
				</div>
				<div class="col-md-4">
					<select id="SistemaFinanc" name="SistemaFinanc" class="selectpicker" data-width="100%" data-live-search="true">
						<!-- <option value="0">-Ninguno-</option> -->
						<option value="NO">NO</option>
						<option value="SI">SI</option>
					</select>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Se realizaron retiros en ejercicio inmediato anterior<b style="color:red">*</b>
				</div>
				<div class="col-md-4">
					<select id="HuboRetirosAnioInmAnt" name="HuboRetirosAnioInmAnt" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">-Ninguno-</option>
						<option value="NO">NO</option>
						<option value="SI">SI</option>
					</select>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Se realizaron retiros de recursos invertidos y sus rendimientos en ejercicio 
					inmediato anterior antes de cumplir requisitos de permanencia<b style="color:red">*</b>
				</div>
				<div class="col-md-4">
					<select id="HuboRetirosAnioInmAntPer" name="HuboRetirosAnioInmAntPer" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">-Ninguno-</option>
						<option value="NO">NO</option>
						<option value="SI">SI</option>
					</select>
				</div>
			</div><br><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-4">
					Total retiros realizados antes de cumplir requisitos de permanencia<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontTotRetiradoAnioInmAntPer" name="MontTotRetiradoAnioInmAntPer" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-4">
					Total de aportaciones actualizadas año anterior
					<input type="text" class="form-control" id="MontTotAportAnioInmAnterior" name="MontTotAportAnioInmAnterior" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-4">
					Total exento del retiro realizado ejercicio inmediato anterior
					<input type="text" class="form-control" id="MontTotExentRetiradoAnioInmAnt" name="MontTotExentRetiradoAnioInmAnt" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-4">
					Intereses reales devengados o percibidos durante el año inmediato anterior<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontIntRealesDevengAniooInmAnt" name="MontIntRealesDevengAniooInmAnt" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-4">
					Excedente del exento del retiro ejercicio anterior
					<input type="text" class="form-control" id="MontTotExedenteAnioInmAnt" name="MontTotExedenteAnioInmAnt" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-4">
					Total retiros en ejercicio inmediato anterior
					<input type="text" class="form-control" id="MontTotRetiradoAnioInmAnt" name="MontTotRetiradoAnioInmAnt" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
			
		</div> <!-- fin retiro -->
		<div class="panel-body" id="hipotecarios" style="display: none">
			<h4 style="color: #727272">Intereses hipotecarios</h4><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Credito otorgado por institucion financiera<b style="color:red">*</b>
					<select id="CreditoDeInstFinanc" name="CreditoDeInstFinanc" class="selectpicker" data-width="100%" data-live-search="true">
						<!-- <option value="0">-Ninguno-</option> -->
						<option value="NO">NO</option>
						<option value="SI">SI</option>
					</select>
				</div>
			</div><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Saldo insoluto al 31/dic año anterior o fecha contrato<b style="color:red">*</b>
					<input type="text" class="form-control" id="SaldoInsoluto" name="SaldoInsoluto" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Intereses nominales devengados y pagados
					<input type="text" class="form-control" id="MontTotIntNominalesDevYPag" name="MontTotIntNominalesDevYPag" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Proporcion deducible del credito sobre intereses reales devengados y pagados
					<input type="text" class="form-control" id="PropDeducDelCredit" name="PropDeducDelCredit" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Intereses reales pagados deducibles
					<input type="text" class="form-control" id="MontTotIntRealPagDeduc" name="MontTotIntRealPagDeduc" />
				</div>
			</div>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Total de intereses nominales devengados
					<input type="text" class="form-control" id="MontTotIntNominalesDev" name="MontTotIntNominalesDev" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					No. de contrato del credito hipotecario
					<input type="text" maxlength="100" class="form-control" id="NumContrato" name="NumContrato"/>
				</div>
			</div>
			
		</div> <!-- fin hipotecario -->
		<div class="panel-body" id="otrotipo" style="display: none">
			<h4 style="color: #727272">Ninguno</h4><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-4">
					Descripcion
					<textarea maxlength="100" class="form-control" id="DescRetenc" name="DescRetenc"></textarea> 
				</div>
			</div>
		</div><!-- fin otro -->
		
		<div class="panel-body" id="derivados" style="display: none">
			<h4 style="color: #727272">Operaciones con derivados</h4><br>
			<div class="row" style="color: #8A2908">
				<div class="col-md-6">
					Importe de la ganancia acumulable<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontGanAcum" name="MontGanAcum" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
				<div class="col-md-6">
					Importe de la perdida deducible<b style="color:red">*</b>
					<input type="text" class="form-control" id="MontPerdDed" name="MontPerdDed" onkeypress="return decimalescomplementos(event,this)"/>
				</div>
			</div>
		</div>
	
	
	
</div><!-- fin div complementos -->

	
      </div>
      <?php	
		}else{?>
			<script>
				$("#generaRetencion").hide();
			</script>
			<b style="color:red">Solo puede utilizar esta opcion si tiene el Modulo de Facturacion</b>
			<br>
			Contrate al <br>
			Tel.: +52 (33) 3675 6800<br>
			Tel.: 01 (800) APPS 321 - 01 (800) 2777 321 <br>
			ventas@netwarmonitor.com <br>
			<a target="_blank" href="http://www.netwarmonitor.mx/index.php">www.netwarmonitor.mx</a>
		<?php	
		}
		?>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrar">Cerrar</button>
        <button type="button" class="btn btn-primary" id="generaRetencion" data-loading-text="Puede tardar unos minutos...<i class='fa fa-refresh fa-spin '></i>">Timbrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</form>

<script type="text/javascript">
$(document).ready(function(){
	
	if($("#idtemporal").val()>0){
		$("#incDoc").hide();
	}else{
		$("#idincluir").val($("#id").val());
		$("#incDoc").show();
	}
$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

//Y-m-d\TH:i:sP
	$("#fechar").val('<?php  date_default_timezone_set("Mexico/General"); echo date('Y-m-d\TH:i:sP');?>');
	$(".imp").val($("#importe").val());
});
$(function(){
	$("#agregarr").on('click', function(){ 
		$("#tretencion tbody tr:eq(0)").clone().removeClass('fila-baser').appendTo("#tretencion tbody");
		$("#tretencion tbody td:last").after('<td class="eliminar">Eliminar</td>');
			
	});
 
	$(document).on("click",".eliminarr",function(){
		var parent = $(this).parents().get(0);
		$(parent).remove();
		
	});
	
	$(".selectpicker").selectpicker('refresh');
	
	
   
$('#generaRetencion').on('click', function() { 
   
   $(this).button('loading');
   var status = true;
   if(!$("#montoTotOperacion").val() || !$("#montoTotGrav").val() || !$("#montoTotExent").val() || !$("#montoTotRet").val()){
   		alert("Existen campos obligatorios vacios");
   		status = false; 
   		$(this).button('reset');
   }
   
  	if($("#CveRetenc").val()	== 19){//Enajenacion de acciones u operaciones en bolsa de valores
  		if(!$("#ContratoIntermediacion").val() || !$("#Ganancia").val() || !$("#Perdida").val()){
  			alert("Existen campos obligatorios vacios en (Enajenacion de Acciones)");
   			status = false; 
   			$(this).button('reset');
  		}
	}else if($("#CveRetenc").val() == 24){//Operaciones Financieras Derivadas de Capital
		if(!$("#MontGanAcum").val() || !$("#MontPerdDed").val()){
  			alert("Existen campos obligatorios vacios en (Operaciones con derivados)");
   			status = false; 
   			$(this).button('reset');
  		}
	}
	else if($("#CveRetenc").val() == 23){//Intereses reales deducibles por creditos hipotecarios
		if(!$("#CreditoDeInstFinanc").val() || !$("#SaldoInsoluto").val()){
  			alert("Existen campos obligatorios vacios en (Intereses hipotecarios)");
   			status = false; 
   			$(this).button('reset');
  		}
	}
	else if($("#CveRetenc").val() == 22){//Planes personales de retiro
		if($("#SistemaFinanc").val()==0 || !$("#MontIntRealesDevengAniooInmAnt").val() || $("#HuboRetirosAnioInmAntPer").val()==0 || $("#HuboRetirosAnioInmAnt").val()==0){
  			alert("Existen campos obligatorios vacios en (Planes de retiro)");
   			status = false; 
   			$(this).button('reset');
  		}
	}else if($("#CveRetenc").val() == 21){//Fideicomisos que no realizan actividades empresariales
		if(!$("#MontTotEntradasPeriodo").val() || !$("#PartPropAcumDelFideicom").val() || !$("#PropDelMontTot").val() || !$("#Concepto").val() || !$("#MontTotEgresPeriodo").val() || !$("#PartPropDelFideicom").val()
		|| !$("#PropDelMontTotEg").val() || !$("#ConceptoS").val() || !$("#MontRetRelPagFideic").val() || !$("#DescRetRelPagFideic").val()){
  			alert("Existen campos obligatorios vacios en (Fideicomisos no empresariales)");
   			status = false; 
   			$(this).button('reset');
  		}else{
	  		if($("#PropDelMontTotEg").val() % 1 == 0){
	  			alert("De acuerdo al SAT \"Egresos proporcion participacion\" debe ser decimal");
	   			status = false; 
	   			$(this).button('reset');
	  		}
  		}
	}else if($("#CveRetenc").val() == 20){//Obtencion de premios
		if(!$("#EntidadFederativa").val() || !$("#MontTotPago").val() || !$("#MontTotPagoGrav").val() || !$("#MontTotPagoExent").val()){
  			alert("Existen campos obligatorios vacios en (Premios)");
   			status = false; 
   			$(this).button('reset');
  		}
	}
	else if($("#CveRetenc").val() == 18){//Pagos realizados a favor de residentes en el extranjero
		if($("#EsBenefEfectDelCobro").val()==0){
			alert("Debe marcar si es Beneficiario en (Pagos a extranjeros)");
   			status = false; 
   			$(this).button('reset');
		}else{
			if($("#EsBenefEfectDelCobro").val()=="NO"){
				if($("#PaisDeResidParaEfecFisc").val()==0 || $("#TipoContribuyenteSujetoRetencionNoBene").val()==0 || !$("#ConceptoPagoNo").val()){
					
					alert("Existen campos obligatorios vacios en (Pagos a extranjeros)");
		   			status = false; 
		   			$(this).button('reset');
		   			
					if($("#PaisDeResidParaEfecFisc").val()==0){
						$('[data-id=PaisDeResidParaEfecFisc]').trigger('click');
					}
					else if($("#TipoContribuyenteSujetoRetencionNoBene").val()==0){
						$('[data-id=TipoContribuyenteSujetoRetencionNoBene]').trigger('click');
					}
					else if(!$("#ConceptoPagoNo").val()){
						$("#ConceptoPagoNo").focus();
					}
					
				}
			}else{ 
				if(!$("#rfc").val() || !$("#CURP").val() || !$("#NomDenRazSocB").val() || $("#TipoContribuyenteSujetoRetencionBene").val()==0 || !$("#ConceptoPago").val()){
					alert("Existen campos obligatorios vacios en (Pagos a extranjeros)");
		   			status = false; 
		   			$(this).button('reset');
					if(!$("#rfc").val()){
						$("#rfc").focus();
					}
					else if(!$("#CURP").val()){
						$("#CURP").focus();
					}
					else if(!$("#NomDenRazSocB").val()){
						$("#NomDenRazSocB").focus();
					}
					else if($("#TipoContribuyenteSujetoRetencionBene").val()==0){
						$('[data-id=TipoContribuyenteSujetoRetencionBene]').trigger('click');
					}
					else if(!$("#ConceptoPago").val()){
						$("#ConceptoPago").focus();
					}
					
				}else{
					var rfc = validarfc($("#rfc").val());
					if(rfc == 0){
						alert( " El RFC no es valido.");
						status = false; 
   						$(this).button('reset');
					}else{
						var curp = validacurp($("#CURP").val());
						if(curp == 0){
							alert( " La CURP no es valida.");
							status = false; 
	   						$(this).button('reset');
						}
					}
					
				}
			}
		} 
  			
  		
	}
	else if($("#CveRetenc").val() == 17){//Arrendamiento en fideicomiso
		if(!$("#PagProvEfecPorFiduc").val() || !$("#RendimFideicom").val() || !$("#DeduccCorresp").val()){
  			alert("Existen campos obligatorios vacios en (Arrendamientos en fideicomisos)");
   			
  		}
	}else if($("#CveRetenc").val() == 16){//Intereses
		if($("#SistFinanciero").val()==0 || $("#RetiroAORESRetInt").val()==0 || $("#OperFinancDerivad").val()==0 || !$("#MontIntNominal").val() || !$("#MontIntReal").val() || !$("#PerdidaInteres").val()){
  			alert("Existen campos obligatorios vacios en (Intereses)");
   			status = false; 
   			$(this).button('reset');
  		}
	}
	else if($("#CveRetenc").val() == 14){//Dividendos o utilidades distribuidas
		if( !$("#MontISRAcredRetMexico").val() || !$("#MontISRAcredRetExtranjero").val() || $("#TipoSocDistrDiv").val()==0){
  			alert("Existen campos obligatorios vacios en (Dividendos)");
   			status = false; 
   			$(this).button('reset');
  		}
	}
	
	
   if(status){
		//$(this).button('reset');
		$("#cerrar").hide();
   		$("#formretencion").submit();
  // alert("envia");
   }
   
 }); 

});

</script>

</body>
</html>