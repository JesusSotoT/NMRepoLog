<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function()
{
	$('#nmloader_div',window.parent.document).hide();
});
function generaexcel()
{
	$().redirect('views/fiscal/generaexcel.php', 
		{
			'cont': $('#imprimible').html(), 
			'name': $('#titulo').val()
		});
}	

</script>
<!--FUNCIONES DE PDF Y MAIL-->
<!--COMIENZA-->
<script language='javascript' src='js/pdfmail.js'></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<!--TERMINA-->
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
.tit_tabla_buscar td
{
	font-size:medium;
}

#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}

@media print
{
	#imprimir,#filtros,#excel,#email_icon,#pdf_icon, .nmwatitles
	{
		display:none;
	}

	#logo_empresa
	{
		display:block;
	}

}
.btnMenu{
	border-radius: 0; 
	width: 100%;
	margin-bottom: 0.3em;
	margin-top: 0.3em;
}
.row
{
	margin-top: 0.5em !important;
}
h4, h3{
	background-color: #eee;
	padding: 0.4em;
}
.modal-title{
	background-color: unset !important;
	padding: unset !important;
}
.nmwatitles, [id="title"] {
	padding: 8px 0 3px !important;
 	background-color: unset !important;
}
.select2-container{
	width: 100% !important;
}
.select2-container .select2-choice{
	background-image: unset !important;
 	height: 31px !important;
}
.twitter-typeahead{
	width: 100% !important;
}
.tablaResponsiva{
  max-width: 100vw !important; 
  display: inline-block;
}
#volverArbol{
	position: absolute;
	right: 12.5%;
	margin: .5em .3em;
	z-index: 100;
}

</style>

<?php 
//Colores
$titulo1="background-color:#e4e7ea;font-size:11px;height:30px;font-weight:bold;";
$r1="#ffffff";
$r2="#f6f7f8";
//print_r($_POST);
?>

<div class="container">
	<a id="volverArbol" href="index.php?c=arbol&f=index" role="button" class="btn btn-primary btn-sm">	Volver al Árbol</a>
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Catálogo de Cuentas<br>
				<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
				<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
				<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0" id='pdf_icon'></a>   
				<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0" id='email_icon'></a>
				<a href='index.php?c=reports&f=catalogoCuentas' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros' onclick="javascript:$('#nmloader_div',window.parent.document).hide();"><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
			</h3>
			<input type='hidden' value='Catalogo de Cuentas.' id='titulo'>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="row">
						<?php
							$url = explode('/modulos',$_SERVER['REQUEST_URI']);
							if($logo == 'logo.png') $logo = 'x.png';
							$logo = str_replace(' ', '%20', $logo);
						?>
						<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
							<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
						</div>
					</div>
					<section id='imprimible'>
						<div class="row">
							<div class="col-md-12" style="text-align: center; width:100%;">
								<label style="font-size:18px; width:100%" class="text-center"><?php echo $empresa; ?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12" style="text-align: center; width:100%;">
								<label style="font-size:15px;color:#576370; width:100%" class="text-center">Catálogo de cuentas</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table align="center" style="width:100%;" cellpadding="3" style="font-size:9px;">
										<!--tr><td>Código Oficial</td><td>Código Manual</td><td>Nombre</td><td>Clasificación</td><td>Tipo de Cuenta</td><td>Naturaleza</td><td>Afectable</td></tr-->
										<thead>
											<tr style='<?php echo "$titulo1"; ?>;text-align:center;'>
												<td style="width:10%;">Código Oficial</td>
												<td style="width:12%;">Código Manual</td>
												<td style="width:29%;">Nombre</td>
												<td style="width:15%;">Clasificación</td>
												<td style="width:12%;">Tipo de Cuenta</td>
												<td style="width:12%;">Naturaleza</td>
												<td style="width:10%;">Afectable</td>
											</tr>
										</thead>
										<?php
										$n=1;
										while($c = $cuentas->fetch_object())
										{
											if($n%2){$r="$r1";}else{$r="$r2";}
											echo "<tr style='background-color:$r;font-size:9px;'>
													<td style='text-align:left;width:10%;'>$c->CodigoOficial</td>
													<td style='text-align:left;width:12%;'>$c->CodigoManual</td>
													<td style='text-align:left;width:29%;'>$c->Nombre</td>
													<td style='width:15%;'>$c->Clasificacion</td>
													<td style='width:12%;' class='text-center'>$c->TipoCuenta</td>
													<td style='width:12%;'>$c->Naturaleza</td>
													<td style='width:10%;' class='text-center'>$c->Afectable</td>
												  </tr>";
											$n++;
										}
										?>
									</table>
									<input type='hidden' id='totalMayores' value='<?php echo $sumaCont; ?>'>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
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
					<input type='hidden' name='nombreDocu' value='Estado de Resultados'>
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
					top:-30%
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