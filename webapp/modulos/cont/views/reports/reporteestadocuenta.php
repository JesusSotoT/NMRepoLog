<script src="../../libraries/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
<link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css"> 
<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script src="../../libraries/dataTable/js/datatables.min.js"></script>
<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
<script>

	$(document).ready(function(){
		$('#tablaestadocuenta').DataTable( {
			"language": {
				"url": "../../libraries/Spanish.json"
			}
		} );
	} );

	function generaexcel()
	{
		$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
	}
</script>

<script language='javascript' src='js/pdfmail.js'></script>
<link rel="stylesheet" href="css/style.css" type="text/css">
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<style>

	#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}


	#titulo_impresion
	{
		visibility: hidden;
	}

	@media print
	{
		#imprimir,#filtros,#excel,#titulo,#email_icon,#pdfre,#impri
		{
			display:none;
		}
		#titulo_impresion
		{
			visibility:visible;
		}
		#sc
		{
			overflow: visible;
		}

		#logo_empresa
		{
			display:block;
		}
	}
</style>
<div class='iconos'  style='margin-left:10px;margin-bottom:10px;'><a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a><a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   <a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0" id="pdfre"></a>   <a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" id="impri" title ="Enviar reporte por correo electrónico" border="0"></a>
	<a href='index.php?c=conciliacionAcontia&f=estadocuentafiltro' id='filtros'><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a></div>
	<div id='imprimible' >

		<table width='100%'>
			<tr>
				<td width="50%">
					<?php
					$url = explode('/modulos',$_SERVER['REQUEST_URI']);
					if($logo == 'logo.png') $logo = 'x.png';
					$logo = str_replace(' ', '%20', $logo);
					?>
					<!--img id='logo_empresa' src='<?php // echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'-->
				</td>
				<td valign="top" width='50%' style="font-size:7px;text-align:right;color:gray;">
					<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b><br>
				</td>
			</tr>
			<tr style="color:#576370;text-align:center;">
				<td colspan=2>
					<b style="font-size:18px;color:black;"><?php echo $empresa; ?></b><br>
					<b style="font-size:15px;">Estado de Cuenta Bancario </b><br>   
					Ejercicio <b><?php echo $ejercicio; ?></b> Periodo <b><?php echo $periodo; ?></b> <br>
					Cuenta Bancaria: <span id='' style='font-weight:bold;'><?php echo $infocuenta['nombre'];?></span> 
					<br><br>
				</td>
			</tr>
		</table>
<!-- <table id="tmovbancos" class="" width="100%" >
	<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
		<th style='width:100px !important;'  align="center">Fecha</th>
		<th style='width:100px !important;'  align="center">Referencia</th>
		<th style='width:150px !important;'  align="center">Concepto</th>
		<th style='width:100px !important;'  align="center">Depositos</th>
		<th style='width:100px !important;'  align="center">Retiros</th>
		<th style='width:100px !important;'  align="center">Total</th>
	</tr> 
</table>-->
<!-- scroll -->
<!-- <div style='height:300px;overflow:scroll;' width="100%"> -->
<div class="col-md-12">
	<!-- <table class="busqueda tablaResponsiva" id="tmovbancos" width="100%" border="1" > -->
	<table id="tablaestadocuenta" cellpadding="0" class="busqueda tablaResponsiva table table-striped table-bordered display" style="border:solid 1px;" border="1" bordercolor="#0000FF" >
		<thead>
			<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
				<th style="text-align:center" >Fecha</th>
				<th style="text-align:center">Referencia</th>
				<th style="text-align:center">Concepto</th>
				<th style="text-align:center">Depositos</th>
				<th style="text-align:center">Retiros</th>
				<th style="text-align:center">Total</th>
			</tr>
		</thead>
		<body>
			<?php 
			while ($row = $estadoCuenta->fetch_assoc()){  ?>
			<tr >
				<td ><?php echo $row['fecha'];?></td>
				<td  ><?php echo $row['folio'];?></td>
				<td  align="center"><?php echo $row['concepto'];?></td>
				<td  align="right"><?php echo number_format($row['abonos'],2,'.',',');?></td>
				<td  align="right"><?php echo number_format($row['cargos'],2,'.',',');?></td>
				<td  align="right"><?php echo number_format($row['saldofinal'],2,'.',',');?></td>

			</tr>
			<?php  }?>
		</body>

	</table>
</div>
<!-- </div> --></div>
<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Generar PDF</h4>
			</div>
			<form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
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
					<input type='hidden' name='nombreDocu' value='Estado de cuenta bancario'>
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
<!--GENERA PDF*************************************************-->
<!-- MAIL -->
<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
	<div 
	id="divmsg"
	style="
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