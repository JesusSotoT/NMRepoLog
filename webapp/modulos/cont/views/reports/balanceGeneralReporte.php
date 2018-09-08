<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script language='javascript'>
$(document).ready(function()
{
	$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};

	Number.prototype.toFixedDown = function(digits) {
    var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
        m = this.toString().match(re);
    return m ? parseFloat(m[1]) : this.valueOf();
	};

	$('.clasif-Clasificacion').remove()
	$(".clasif-Activo:contains('TOTAL GRUPO')").remove()
	
	var total_ac = 0;
		$(".clasif-Activo").each(function(index, el) {
			if($("td:nth-child(2)",this).attr("cantidad") != undefined)
				total_ac += parseFloat($("td:nth-child(2)",this).attr("cantidad"));
		});

	
		
	$("#sumaTotal_AC").html("$ "+total_ac.toFixedDown(4).format()).css('text-align','right').attr('cantidad',total_ac.toFixed(2))
	if(total_ac < 0)
	{
		$("#sumaTotal_AC").css('color','red')
	}
	/////////////////////
	var pasivo = $("#sum-Pasivo").attr('cantidad')
	if(typeof pasivo === 'undefined') pasivo=0
		else pasivo = pasivo.replace('$ ','').replace(/,/g,'')
	
	var capital = $("#sum-Capital").attr('cantidad')
	if(typeof capital === 'undefined') capital=0
		else capital = capital.replace('$ ','').replace(/,/g,'')
	
	var resultados = $("#sum-Resultados").attr('cantidad')
	if(typeof resultados === 'undefined') resultados=0
		else resultados = resultados.replace('$ ','').replace(/,/g,'')
	
	var total_pcr = parseFloat(pasivo) + parseFloat(capital) + parseFloat(resultados)
	$("#sumaTotal_PCR").html("$ "+total_pcr.toFixedDown(4).format()).css('text-align','right').attr('cantidad',total_pcr.toFixed(2))
	if(total_pcr < 0)
	{
		$("#sumaTotal_PCR").css('color','red')
	}

	$(".clasif-Resultados").remove()
	//$("#totrest").prev().remove()
	//$("#totrest").prev().remove()
	if(parseFloat(total_ac.toFixed(2)) != parseFloat(total_pcr.toFixed(2)))
		alert("El Balance General no cuadra, asegurese que las polizas estan cuadradas.")

	$(".tbln1:first").html($(".tbl1:first").html());
	$(".tbln2:first").html($(".tbl2:first").html());
	$(".tbln3:first").html($(".tbl3:first").html());
	$(".tbln4:first").html($(".tbl4:first").html());
	creargrafica()
});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}		
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>

	/*
	table table tr{ background: #e4ecb8;}
	table table tr:nth-of-type(odd) { 
	        background: #f3f3f3; 
	    }
	*/
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
		#imprimir,#filtros,#excel, #botones
		{
			display:none;
		}
		#logo_empresa
		{
			display:block;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
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
    .table thead, .table tbody tr {
	    display:table;
	    width:100%;
	   	table-layout: fixed;/* even columns width , fix width of table too*/
	}
	.table tr, .table td{
		border: none !important;
	}
</style>

<?php
$moneda=$_POST['moneda'];
if($_POST['valMon']){$valMon=$_POST['valMon'];}else{$valMon=1;}
// Manejo de Colores -----------------------------------------------
$titulo="font-weight:bold;font-size:9px;color:black;background-color:#edeff1;height:30px;";
$subtitulo="font-weight:bold;height:30px;background-color:#fafafa;font-size:9px;";

			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
$activo = "ACTIVO";			
if(intval($_POST['idioma'])) $activo = "ACTIVE";
?>

<!-- --------------------------------------------------------------- -->
<div id='imprimible' style="display:none;">


<table border=0 style='width:100%;max-width:900px;' style='background:white;' align="center">
<tr style='background:white;'>
	<td ></td>
	<td></td>
	<td valign="top" style="font-size:7px;text-align:right;color:gray;">
		<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b><br>
	</td>
</tr>
<tr style='background:white;'>
	<td colspan='3' style='text-align:center;font-size:18px;'>
		<b style='text-align:center;'><?php echo $empresa;?></b>
	</td>
</tr>
<tr style='background:white;color:#576370;text-align:center;font-size:12px'> 	
	<td colspan='3'>
		<b style="font-size:15px;">Balance General</b><br>
		Ejercicio <b><?php echo $ej;?></b>  Periodo <b><?php echo $periodo; ?> </b><br>
		Sucursal <b><?php echo $nomSucursal; ?></b> Segmento <b><?php echo $nomSegmento; ?></b> 
		<?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio $ <b>$valMon</b>";}?>
		<br><br>
	</td>
</tr>

	<tr>
		<td valign='top' style='width:49%;'>
			<table class="tbl1" style="font-size:9px;width:100%;text-align:left;" cellpadding=3 >
				<tr style='<?php echo "$titulo"; ?>'><td colspan='3'><?php echo $activo; ?></td></tr>
				<?php
				//Carga los Activos***************************************************************************************************
				$grupoAnterior='Grupo';
				$sumaCantidad = 0;
				$n=1;
				while($a = $activos->fetch_object())
				{
					if(intval($_POST['idioma']))
					{
						$grupoNombre = $a->Grupo_Alt;
						$grupoAnteriorNombre = $grupoAnterior_Alt;
					}
					else
					{
						$grupoNombre = $a->Grupo;
						$grupoAnteriorNombre = $grupoAnterior;
					}
					$CM = explode(' / ',$a->Cuenta_de_Mayor,2);
					$grupoAnterior_1 = str_replace(' ', '', $grupoAnterior);
					if($grupoAnterior != $a->Grupo)
						{
							$red='';
							if(floatval($sumaCantidadGrupo) < 0) $red = "color:red;";
							if($grupoAnterior != 'Grupo') echo "<tr style='$subtitulo' class='clasif-$a->Clasificacion'><td colspan='2'>TOTAL ".strtoupper($grupoAnteriorNombre)."</td><td id='sumG-$grupoAnterior_1' style='text-align:right;$red' cantidad='$sumaCantidadGrupo'>$ ".number_format($sumaCantidadGrupo,2)."</td></tr>";
							$sumaCantidadGrupo = 0;
							// echo "<tr style='text-align:left;'><td colspan='3' class='clasif-$a->Clasificacion'></td></tr>";	
							echo "<tr style='$subtitulo' class='clasif-$a->Clasificacion'><td colspan='3'>".strtoupper($grupoNombre)."</td></tr>";	
						}
						$red='';
						if(floatval($a->CargosAbonos) < 0) $red = 'color:red;';
						if(round($a->CargosAbonos,2)>=0.01 || round($a->CargosAbonos,2)<= -0.01)
						{
							if($_POST['segmento']==0){ $_POST['segmento']='todos';}
							echo "<tr><td style='mso-number-format:\"@\";text-align:left;width:27%;min-width:80px;'><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=".$_POST['periodo']."&f4_1=".$_POST['periodo']."&f3_3=".$_POST['ejercicio']."&f4_3=".$_POST['ejercicio']."&tipo=".$_GET['tipo']."&cuentas[]=".$a->account_id."&segmento=".$_POST['segmento']."' target='_blank'>".$a->Codigo."</a></td><td style='width:43%;min-width:200px;'>".$CM[1]."</td><td style='text-align:right;$red;width:30%;min-width:120px;'>$ ".number_format($a->CargosAbonos/$valMon,2)."</td></tr>";
							$sumaCantidad += $a->CargosAbonos/$valMon;
							$sumaCantidadGrupo += $a->CargosAbonos/$valMon;
							$grupoAnterior = $a->Grupo;
							$grupoAnterior_Alt = $a->Grupo_Alt;
							$clasifUltima = $a->Clasificacion;
						}
						$n++;
				}
				$red='';
				$grupoAnterior_1 = str_replace(' ', '', $grupoAnterior);
				if(floatval($sumaCantidadGrupo) < 0) $red = "color:red;";
				echo "<tr style='$subtitulo' class='clasif-$clasifUltima'><td colspan='2'>TOTAL ".strtoupper($grupoNombre)."</td><td id='sumG-$grupoAnterior_1' style='text-align:right;$red' cantidad='$sumaCantidadGrupo'>$ ".number_format($sumaCantidadGrupo,2)."</td></tr>";
							$sumaCantidadGrupo = 0;
				$red='';
				if(floatval($sumaCantidad) < 0) $redT = "color:red;";
				?>
				
				<!--tr style='font-weight:bold;color:white;background-color:#69771e;height:30px;'><td colspan='2'>TOTAL ACTIVO</td><td id='sumaTotal_AC' style='text-align:right;<?php //echo $red;?>'></td></tr!-->
			</table>
			
		</td>
		<td style='width:2%;'></td>
		<td valign='top' style='width:49%;'>
			<table class="tbl2" style="font-size:9px;width:100%;text-align:left;" cellpadding=3  >
				<?php
				//Carga los Pasivo, Capital y Resutados******************************************************************************
				$clasifAnterior='Clasificacion';//Almacena la clasificacion anterior
				$grupoAnterior='Grupo';
				$sumaCantidad = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
				$sumaCantidadGrupo = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
				$t=1;
				$others = '';
				while($d = $datos->fetch_object())
				{
					if(intval($_POST['idioma']))
					{
						$grupoNombre = $d->Grupo_Alt;
						$grupoAnteriorNombre = $grupoAnterior_Alt;
						$clasNombre = $d->Clasificacion_Alt;
						$clasAnteriorNombre = $clasifAnterior_Alt;
						$y = 'and';
					}
					else
					{
						$grupoNombre = $d->Grupo;
						$grupoAnteriorNombre = $grupoAnterior;
						$clasNombre = $d->Clasificacion;
						$clasAnteriorNombre = $clasifAnterior;
						$y = "y";
					}
					$CM = explode(' / ',$d->Cuenta_de_Mayor,2);
					if($clasifAnterior != $d->Clasificacion)
					{
						$others .= $clasNombre.","; 
						if($grupoAnterior != $d->Grupo)
						{
							if(floatval($sumaCantidadGrupo) < 0) $red = "color:red;";
							if($grupoAnterior != 'Grupo') echo "<tr style='$subtitulo' class='clasif-$clasifAnterior'><td colspan='2'>TOTAL ".strtoupper($grupoAnteriorNombre)."</td><td id='sumG-$grupoAnterior' style='text-align:right;$red' cantidad='$sumaCantidadGrupo'>$ ".number_format($sumaCantidadGrupo,2)."</td></tr>";
							$sumaCantidadGrupo = 0;
						}
						//comienza cuenta de clasificacion
						$red='';
						if(floatval($sumaCantidad) < 0) $red = "color:red;";
						echo "<tr style='$titulo' class='clasif-$clasifAnterior'><td colspan='2'>TOTAL ".strtoupper($clasAnteriorNombre)."</td><td id='sum-$clasifAnterior' style='text-align:right;$red' cantidad='$sumaCantidad'>$ ".number_format($sumaCantidad,2)."</td></tr>";
						$sumaCantidad = 0;
						//echo "<tr class='clasif-$clasifAnterior'><td colspan='3'></td></tr>";	
						if('PASIVO'!=strtoupper($d->Clasificacion)){ echo "<tr><td colspan='3' style='height:15px;background-color:#ffffff'></td></tr>";}
						echo "<tr style='$titulo' class='clasif-$d->Clasificacion'><td colspan='3'>".strtoupper($clasNombre)."</td></tr>";	
							
						//termina cuenta de clasificacion
						
						if($grupoAnterior != $d->Grupo)
						{
							//echo "<tr><td colspan='3' class='clasif-$d->Clasificacion'></td></tr>";	
							echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'><td colspan='3'>".strtoupper($grupoNombre)."</td></tr>";	
						}
					}
					else
					{
						if($grupoAnterior != $d->Grupo)
						{
							$red='';
							if(floatval($sumaCantidadGrupo) < 0) $red = "color:red;";
							echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'><td colspan='2'>TOTAL ".strtoupper($grupoAnteriorNombre)."</td><td id='sumG-$grupoAnterior' style='text-align:right;$red' cantidad='$sumaCantidadGrupo'>$ ".number_format($sumaCantidadGrupo,2)."</td></tr>";
							$sumaCantidadGrupo = 0;
							// echo "<tr><td colspan='3' class='clasif-$d->Clasificacion'></td></tr>";	
							echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'><td colspan='3'>".strtoupper($grupoNombre)."</td></tr>";	
						}
					}
					$red='';
					if(round($d->CargosAbonos,2)>=0.01 || round($d->CargosAbonos,2)<= -0.01)
					{
						$Resultados = $d->CargosAbonos*-1;
						$Resultados = $Resultados/$valMon;
						if(floatval($Resultados) < 0) $red = 'color:red;';
						if($_POST['segmento']==0){ $_POST['segmento']='todos';}
						echo "<tr class='clasif-$d->Clasificacion'><td style='mso-number-format:\"@\";width:27%;min-width:80px;'><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=".$_POST['periodo']."&f4_1=".$_POST['periodo']."&f3_3=".$_POST['ejercicio']."&f4_3=".$_POST['ejercicio']."&tipo=".$_GET['tipo']."&cuentas[]=".$d->account_id."&segmento=".$_POST['segmento']."' target='_blank'>".$d->Codigo."</a></td><td style='width:43%;min-width:200px;'>".$CM[1]."</td><td style='text-align:right;$red:;width:30%;min-width:120px;'>$ ".number_format($Resultados,2)."</td></tr>";
						$sumaCantidadGrupo += $Resultados;
						$sumaCantidad += $Resultados;
						
						$grupoAnterior = $d->Grupo;
						$grupoAnterior_Alt = $d->Grupo_Alt;
						$red='';
						if(floatval($sumaCantidad) < 0) $red = "color:red;";
					}
					$t++;
					$clasifAnterior = $d->Clasificacion;
					$clasifAnterior_Alt = $d->Clasificacion_Alt;
				}
				if($clasifAnterior != 'Resultados')
				{
					echo "<tr style='$subtitulo' id='totrest'><td colspan='2'>TOTAL ". strtoupper($grupoAnterior)."</td><td id='sumG-$grupoAnterior' style='text-align:right;$red' cantidad='$sumaCantidadGrupo'>$ ". number_format($sumaCantidadGrupo,2)."</td></tr>";
					?> <tr style='<?php echo "$subtitulo"; ?>' id='totrest'><td colspan='2'>TOTAL <?php echo strtoupper($clasifAnterior);?></td><td id='sum-<?php echo $clasifAnterior."' style='text-align:right;".$red;?>' cantidad='<?php echo $sumaCantidad ?>'>$ <?php echo number_format($sumaCantidad,2); ?></td></tr><?php
				}else{
				?><tr style='<?php echo "$subtitulo"; ?>' id='totrest'><td colspan='2'>TOTAL <?php echo strtoupper($clasAnteriorNombre);?></td><td id='sum-<?php echo $clasifAnterior."' style='text-align:right;".$red;?>' cantidad='<?php echo $sumaCantidad ?>'>$ <?php echo number_format($sumaCantidad,2); ?></td></tr><?php		
			}
				?>
				
				
			
			</table>
		</td>
	</tr>
	<tr>
		
		<td><table class="tbl3" style="font-size:10px;padding:3px;width:100%;text-align:left;" ><tr style='<?php echo "$titulo"; ?>'><td style='width:70%;min-width:280px;'>TOTAL <?php echo $activo; ?></td><td id='sumaTotal_AC' style='text-align:right;width:30%;min-width:120px;<?php echo $redT;?>'></td></tr></table></td>
		<td></td>
		<?php
		$others = explode(',',$others);
		$others = $others[0].", ".$others[1]." $y ".$others[2];
		?>
		<td><table class="tbl4" style="font-size:10px;padding:3px;width:100%;text-align:left;" ><tr style='<?php echo "$titulo"; ?>'><td style='width:70%;min-widht:280px;'>TOTAL <?php echo strtoupper($others); ?></td><td id='sumaTotal_PCR' style='width:30%;min-width:120px;'></td></tr></table></td>
    </tr>

</table>	


<input type='hidden' id='totalMayores' value='<?php echo $sumaCont; ?>'>
</div>
<!-- ------------------------------------------------------------------ -->

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Balance General<br>
				<section id="botones">
					<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
                    <a href="index.php?c=reports&f=balanceGeneral&tipo=1"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
					<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"> </a>
					<a href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"> </a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>			
				</section>
			</h3>
			<div class="row">
				<div class="col-md-1">
				</div>
				<div class="col-md-10" id="imp_cont">
					<section class='descripcion'>
						<input type='hidden' value='Balance General.' id='titulo'>
						<section >
							<div class="row">
								<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
									<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
									<b style='text-align:center;'><?php echo $empresa;?></b>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
									<b style="font-size:15px;">Balance General</b><br>
									Ejercicio <b><?php echo $ej;?></b>  Periodo <b><?php echo $periodo; ?> </b><br>
									Sucursal <b><?php echo $nomSucursal; ?></b> Segmento <b><?php echo $nomSegmento; ?></b> 
									<br><br>
									<?php
										echo $alumno;
									?>
									<?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio $ <b>$valMon</b>";}?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="row" style="margin-right: -22.5px">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table class="table tbln1" style="font-size:9px;text-align:left;" cellpadding=3 >
												</table>
											</div>
										</div>
									</div>
									
								</div>
								<?php
									$others = explode(',',$others);
									$others = $others[0].", ".$others[1]." $y ".$others[2];
								?>
								<div class="col-md-6 col-sm-6">
									<div class="row" style="margin-left: -22.5px">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table class="table tbln2" style="font-size:9px;text-align:left;" cellpadding=3  >
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<input type='hidden' id='totalMayores' value='<?php echo $sumaCont; ?>'>
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table class="table tbln3" style="font-size:9px;text-align:left;" cellpadding=3 >
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table class="table tbln4" style="font-size:9px;text-align:left;" cellpadding=3 >
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</section>
					<div class="row">
						<div class="col-md-12">
							<div id="donutchart" style="height: 400px;">
								<!-- JS Google Chart --> 
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<div>
	<input type="hidden" id="hiddenGrafica" value="<?php echo(intval($_POST['graph_input'])); ?>">
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
					<input type='hidden' name='nombreDocu' value='Balance General'>
					<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
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
			<script type="text/javascript">
function creargrafica()
{
	if ($('#hiddenGrafica').attr('value') == 1) {
		google.charts.load("current", {packages:["corechart"]});
		google.charts.setOnLoadCallback(drawChart);
	} else {
		$('#donutchart').hide()
	}
}


function drawChart() {
	var a = window.activoSum;

  var data = google.visualization.arrayToDataTable([
    ['Nombre',     'Cantidad'],
    ['Activo',     parseFloat($('#sumaTotal_AC').attr('cantidad'))],
    ['Pasivo',     parseFloat($('#sum-Pasivo').attr('cantidad'))],
    ['Capital',    parseFloat($('#sum-Capital').attr('cantidad'))],
    ['Resultados', parseFloat($('#sum-Resultados').attr('cantidad'))]
  ]);

  var options = {
    title: 'Gráfica del balance.',
    pieStartAngle: 180
  };

  var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
  chart.draw(data, options);
}

</script>