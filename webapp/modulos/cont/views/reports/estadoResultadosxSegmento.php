<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function()
{
	$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};
	
	$('.clasif-Clasificacion').remove()
	$(".clasif-Activo:contains('TOTAL GRUPO')").remove()
	$("tr[numero='0']").remove()

	

});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}	

</script>
<style>
	 table tr td {font-size: 10px; } 
</style>
<script language='javascript' src='js/pdfmail.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link href="css/style.css" rel="stylesheet" type="text/css" />
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
	#imprimir,#filtros,#excel,#email_icon
	{
		display:none;
	}

	#logo_empresa
	{
		display:block;
	}
}
</style>

<?php 
$moneda=$_POST['moneda'];
if($_POST['valMon']){$valMon=$_POST['valMon'];}else{$valMon=1;}
//$valMon=13.5;
$titulo1="font-size:10px;background-color:#f6f7f8;font-weight:bold;height:30px;";
$subtitulo="font-size:9px;font-weight:bold;height:30px;background-color:#fafafa;text-align:left;margin-left:10px;"

?>

<div class='iconos'  style='margin-left:10px;margin-bottom:10px;'><a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a><a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   <a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   <a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
	<a href='index.php?c=reports&f=balanceGeneral&tipo=0' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros' onclick="javascript:$('#nmloader_div',window.parent.document).hide();"><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a></div>

<div class="repTitulo">Estado de Resultados Comparativo por Segmentos</div>	



<input type='hidden' value='Estado de Resultados por Segmentos.' id='titulo'>
	

<div id='imprimible'>

	
	<!--Titulo congelado-->
<!--INICIA-->

<table style='width:100%' align="center">
	<tr><td width='50%'>

			<?php
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
			?>
			<!--img id='logo_empresa' src='<?php //echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'-->
			</td>
			<td valign="top" width='50%' style="font-size:7px;text-align:right;color:gray;">
		<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b><br>
	</td>
	</tr>
	<tr style="text-align:center;color:#576370;">
		<td colspan="2" style='font-size:15px;'>
			<b style='font-size:18px;color:#000000;'><?php echo $empresa; ?></b> <br>
			<b style='font-size:15px;'>Estado de Resultados Comparativo por Segmento </b> <br> 
			Ejercicio <b><?php echo $ej; ?></b>  Periodo  <b><?php echo $periodo; ?></b><br>
			Sucursal <b><?php echo $nomSucursal; ?></b> 
			<?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio $ <b>$valMon</b>";}?>
			<br><br>
		</td>
	</tr>
</table>
	
<?php 
		$wid=500;
		$tseg=0;
		while($seg1 = $segmentos->fetch_assoc())
									{
										//echo "<td style='width:140px;text-align:center;'>".$seg1['nombre']."</td>";
										$tsegR=$seg1['idSuc'];
										$tseg++;
										$seg[$tseg]=$tsegR;
										$ss[$tseg]=$seg1['nombre'];
										$wid +=140;
									}
		
		?>

<table align="center" valing="center" cellpadding="3" style='font-size:9px;min-width:<?php echo $wid; ?>px' >
	<thead>
	<tr style='font-weight:bold;background-color:#edeff1;height:30px;' valign='center'>
		<td style='width:100px;'>CLAVE</td>
		<td style='width:230px;'>CUENTA <?php if(!intval($_POST['detalle'])) echo "DE MAYOR"; ?></td>
		<?php 
		$ts=1;
		while($ts <=$tseg){
							$nombre=$ss[$ts];
							echo "<td style='width:140px;text-align:center;'>".$nombre."</td>";
							$ts++;
							} 
		?>
		<td style='width:160px;text-align:center;'>TOTAL</td>
	</tr>
	</thead>

				
				<?php 

				//echo "$tseg - $tseg1";

				function startsWith($haystack, $needle) 
				{
				    // search backwards starting from haystack length characters from the end
				    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
				}
				
				
				$nn=0;
				$clasifAnterior='Clasificacion';//Almacena la clasificacion anterior
				$grupoAnterior='Grupo';
				$sumaCantidad = $sumaCantidadMes = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
				$sumaCantidadGrupo = $sumaCantidadGrupoMes = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
				
				
				while($d = $datos->fetch_object())
				{
					$title = $d->Grupo;

					$idSeg = $d->idsegmento;

					if(startsWith($d->Grupo,'RESULTADO ACRE') || startsWith($d->Grupo,'RESULTADOS ACRE'))
					{
						$d->Grupo = "INGRESOS";	
					}

					if(startsWith($d->Grupo,'RESULTADO DEUDOR') || startsWith($d->Grupo,'RESULTADOS DEUDOR'))
					{
						$d->Grupo = "EGRESOS";	
					}
					if($clasifAnterior != $d->Clasificacion)
					{
						if($grupoAnterior != $d->Grupo)
						{
							
							if($s){
											while($s<=$tseg){
											echo "<td style='text-align:right;' class='Seg-$s' cantidad='0.00'>$ 0.00</td>";
											$s++;
											}
											if(intval($sumaCuenta[$tc1])<0){$red="color:red";}else{$red='';}
											 echo "<td style='font-weight:bold;text-align:right;$red'>$ ".number_format($sumaCuenta[$tc1],2)."</td>";
											echo "</tr>";
										}
									$s=0;

							echo "<tr style='$subtitulo' class='clasif-$clasifAnterior'>
									<td></td><td>TOTAL ".strtoupper($grupoAnterior)."</td>";
									$s1=1;
									while($s1<=$tseg){
										if(floatval($sumaMayorMes[$s1][$cm1]) < 0) {$red = "color:red";}else{$red='';}
									echo "<td style='text-align:right;$red'>$ ".number_format($sumaMayorMes[$s1][$cm1],2)."</td>";
									$s1++;
									}
									if(floatval($sumaGrupo[$cm1]) < 0) {$red = "color:red";}else{$red='';}
							echo "<td style='text-align:right;$red' class='T-$cm1'>$ ".number_format($sumaGrupo[$cm1],2)."</td>";
							echo "</tr>";
							
							$sumaCantidadGrupo = $sumaCantidadGrupoMes = 0;
						}


						//comienza cuenta de clasificacion
						$red = $redMes = '';
						if(floatval($sumaCantidad) < 0) $red = "style='color:red;'";
						if(floatval($sumaCantidadMes) < 0) $redMes = "style='color:red;'";
						echo "<tr style='font-weight:bold;height:30px;' class='clasif-$clasifAnterior'>
								<td></td>
								<td>TOTAL ".strtoupper($clasifAnterior)."</td>";
								$s1=1;
								while($s1<=$tseg){
									if(floatval($sumaMayorMes[$s1][$cm1]) < 0) {$red = "color:red";}else{$red='';}
								echo "<td style='text-align:right;$red'>$ ".number_format($sumaMayorMes[$s1][$cm1],2)."</td>";
								$s1++;
								}
						echo "<td></td>";	
						echo "</tr>";
						$sumaCantidad = $sumaCantidadMes = 0;
						echo "<!--tr class='clasif-$clasifAnterior' style='font-weight:bold;height:3px;'><td colspan='6'></td></tr-->";	
						echo "<tr style='$titulo1' class='clasif-$d->Clasificacion'>
								<td></td>
								<td style='text-align:left;'>".strtoupper($d->Clasificacion)."</td>";
								$s1=1;
								while($s1<=$tseg){
								echo "<td></td>";
								$s1++;
								}
						echo "<td></td>";
						echo "</tr>";	
						//termina cuenta de clasificacion
						
						if($grupoAnterior != $d->Grupo)
						{
							if($s){
											while($s<=$tseg){
											echo "<td style='text-align:right;' class='Seg-$s' cantidad='0.00'>$ 0.00</td>";
											$s++;
											}
											if(intval($sumaCuenta[$tc1])<0){$red="color:red";}else{$red='';}
											echo "<td style='font-weight:bold;text-align:right;'>$ ".number_format($sumaCuenta[$tc1],2)."</td>";
											echo "</tr>";
										}
									$s=0;

							echo "<!--tr style='height:3px;'><td colspan='6' class='clasif-$d->Clasificacion'></td></tr-->";	
							echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'>
									<td></td>
									<td>".strtoupper($title)."</td>";
									$s1=1;
									while($s1<=$tseg){
									echo "<td></td>";
									$s1++;
									}
							echo "<td></td>";
							echo "</tr>";	
						}
						if($d->Cuenta_de_mayor != $mayorAnterior AND intval($_POST['detalle']))
						{	
							
							
							echo "<tr numero='$nn' style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' class='anterior'>
									<td></td>
									<td style='text-align:left;'>Total $mayorAnterior</td>";
									$s1=1;
									while($s1<=$tseg){
										if($grupoAnterior == "EGRESOS")
												{
													$sumaCodigo[$cod] = $sumaCodigo[$cod] *-1;
													$sumaMayorDet[$s1][$tc11] = $sumaMayorDet[$s1][$tc11] *-1;
												}
										if(floatval($sumaMayorDet[$s1][$tc11]) < 0) {$red = "color:red";}else{$red='';}
									echo "<td style='text-align:right;'>$ ".number_format($sumaMayorDet[$s1][$tc11],2)."</td>";
									$s1++;
									}
							echo "<td style='text-align:right;'>$ ".number_format($sumaCodigo[$cod],2)."</td>";
							echo "</tr>";
							echo "<tr style='color:gray;font-weight:bold;height:30px;text-align:left;'>
									<td></td>
									<td>$d->Cuenta_de_mayor</td>";
									$s1=1;
									while($s1<=$tseg){
									echo "<td></td>";
									$s1++;
									}
							echo "<td></td>";
							echo "</tr>";
							$sumaCantidadMayor=0;
							$sumaCantidadMayorMes=0;
							$nn++;
						}
					}
					else
					{
						
						if($d->Cuenta_de_mayor != $mayorAnterior AND intval($_POST['detalle']))
						{
							if($s){
									while($s<=$tseg){
									echo "<td style='text-align:right;' class='seg-$s' cantidad='0.00'>$ 0.00</td>";
									$s++;
									}
									if(floatval($sumaCuenta[$tc1]) < 0) {$red = "color:red";}else{$red='';}
								  	echo "<td style='font-weight:bold;text-align:right;'>$ ".number_format($sumaCuenta[$tc1],2)."</td>";
									echo "</tr>";
									$s=0;
								}
							echo "<tr numero='$nn' style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' class='anterior'>
									<td></td>
									<td style='text-align:left;'>Total $mayorAnterior</td>";
									$s1=1;
									while($s1<=$tseg){
										if($grupoAnterior == "EGRESOS")
												{
													$sumaCodigo[$cod] = $sumaCodigo[$cod] *-1;
													$sumaMayorDet[$s1][$tc11] = $sumaMayorDet[$s1][$tc11] *-1;
												}
										if(floatval($sumaMayorDet[$s1][$tc11] ) < 0) {$red = "color:red";}else{$red='';}
									echo "<td style='text-align:right;'>$ ".number_format($sumaMayorDet[$s1][$tc11] ,2)."</td>";
									$s1++;
									}	
							echo "<td style='text-align:right;'>$ ".number_format($sumaCodigo[$cod],2)."</td>";
							echo "</tr>";
						}
						if($grupoAnterior != $d->Grupo)
						{
							$red = $redMes ='';

							if($s){
											while($s<=$tseg){
											echo "<td style='text-align:right;' class='Seg-$s' cantidad='0.00'>$ 0.00</td>";
											$s++;
											}
											if(intval($sumaCuenta[$tc1])<0){$red="color:red";}else{$red='';}
											 echo "<td style='font-weight:bold;text-align:right;'>$ ".number_format($sumaCuenta[$tc1],2)."</td>";
											echo "</tr>";
										}
									$s=0;
							
							echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'>
									<td></td>
									<td>TOTAL ".strtoupper($titleAnterior)."</td>";
									$s1=1;
									while($s1<=$tseg){
										if(floatval($sumaMayorMes[$s1][$cm1]) < 0) {$red = "color:red";}else{$red='';}
									echo "<td style='text-align:right;$red'>$ ".number_format($sumaMayorMes[$s1][$cm1],2)."</td>";
									$s1++;
									}	
							if(floatval($sumaGrupo[$cm1]) < 0) {$red = "color:red";}else{$red='';}
							echo "<td style='text-align:right;$red' class='T-$cm1'>$ ".number_format($sumaGrupo[$cm1],2)."</td>";
							echo "</tr>";
							$sumaCantidadGrupo = $sumaCantidadGrupoMes = 0;
							echo "<!--tr><td colspan='6' class='clasif-$d->Clasificacion' style='height:15px;'></td></tr-->";	
							echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'>
									<td></td>
									<td>".strtoupper($title)."</td>";
									$s1=1;
									while($s1<=$tseg){
									echo "<td></td>";
									$s1++;
									}
							echo "<td></td>";
							echo "</tr>";	

						}
						if($d->Cuenta_de_mayor != $mayorAnterior AND intval($_POST['detalle']))
						{
							echo "<tr style='color:gray;font-weight:bold;height:30px;text-align:left;'>
									<td></td>
									<td>$d->Cuenta_de_mayor</td>";
									$s1=1;
									while($s1<=$tseg){
									echo "<td></td>";
									$s1++;
									}
							echo "<td></td>";
							echo "</tr>";
							$sumaCantidadMayor=0;
							$sumaCantidadMayorMes=0;
							$nn++;
						}
					}
					$red = $redMes = '';
					$ResultadosMes = $d->resultado/$valMon;

					$sumaTotal[$idSeg] 		+= $ResultadosMes;   	//Suma Total por Segmento 
					$sumaTotalGral			+= $d->resultado/$valMon;

					//$Resultados = $d->idsegmento;
					if($d->Grupo == "EGRESOS")
					{
						//$Resultados = $Resultados *-1;
						$ResultadosMes = $ResultadosMes *-1;
					} 
					/*if(floatval($Resultados) < 0) $red = 'color:red;'; */
					if(floatval($ResultadosMes) < 0) $redMes = 'color:red;';
					if(!intval($_POST['detalle']))
					{
						$nc = $d->Codigo;
						$tc = $d->Cuenta_de_mayor;
						$cds=$d->Cuenta_de_mayor;
					} 
					else
					{
						 $nc = $d->CuentaAfectable;
						 $tc = $d->Cuenta;
						 $cds= $d->code;
					}

					//echo "$cds | $cda <br>";

					if($cds!=$cda){
									if($s){
											while($s<=$tseg){
											echo "<td style='text-align:right;' class='RRSeg-$s' cantidad='0.00'>$ 0.00</td>";
											$s++;
											}
											if(floatval($sumaCuenta[$tc1]) < 0) {$redMes = 'color:red;';}else{$redMes='';}
											echo "<td style='font-weight:bold;text-align:right;$redMes'>$ ".number_format($sumaCuenta[$tc1],2)."</td>";
											echo "</tr>";
										}
									  
									echo "<tr class='clasif-$d->Clasificacion' nmtr='$nn'>
									<td style='mso-number-format:\"@\";'>".$nc."</td>
									<td style='text-align:left;'>".$tc."</td>";
									$s=1;
									while($seg[$s]<=$idSeg){
													if($seg[$s]==$idSeg){
														if(floatval($ResultadosMes) < 0) {$redMes = 'color:red;';}else{$redMes='';}
															echo "<td style='text-align:right;$redMes' class='seg-$idSeg' cantidad='".number_format($ResultadosMes,2,'.','')."'>$ ".number_format($ResultadosMes,2)."</td>";
															}else{
																	echo "<td style='text-align:right;' class='R1Seg-$s' cantidad='0.00'>$ 0.00</td>";
																}
														$s++;
													}
								}else{
									//echo "Entro al Else | $s == $seg[$s] == $idSeg ";

									while($seg[$s]<=$idSeg){
													//echo "Entro al While | $s";
													if($seg[$s]==$idSeg){
															if(floatval($ResultadosMes) < 0) {$redMes = 'color:red;';}else{$redMes='';}
															echo "<td style='text-align:right;$redMes' class='seg-$idSeg' cantidad='".number_format($ResultadosMes,2,'.','')."'>$ ".number_format($ResultadosMes,2)."</td>";
															}else{
																	echo "<td style='text-align:right;' class='R2Seg-$s' cantidad='0.00'>$ 0.00</td>";
																}
														$s++;
													}
									}
					


					//echo "</tr>";
					
					/*$sumaCantidadGrupo    += $Resultados;
					$sumaCantidad 			+= $Resultados;
					$sumaCantidadMayor 		+= $Resultados;*/
					if(!intval($_POST['detalle']))
					{
						$tc1 = $d->Cuenta_de_mayor;
						$cda = $d->Cuenta_de_mayor;
					} 
					else
					{
						$tc1 = $d->code;
						$cda = $d->code;
					}
					$tc11=$d->Cuenta_de_mayor;
					$cm1=$d->Grupo;
					$cod=$d->Codigo;
					$sumaCuenta[$tc1] 	    += $ResultadosMes;		//Suma por Cuenta
					$sumaMayor[$idSeg][$tc1] 	+= $ResultadosMes;
					$sumaMayorMes[$idSeg][$cm1] += $ResultadosMes;	//Suma por Segmento por Grupo
					$sumaMayor[$idSeg][$tc1] 	+= $ResultadosMes;	//Suma por Segmento por Cuenta
					$sumaGrupo[$cm1]		+= $d->resultado/$valMon;		//Suma por Grupo
					$sumaMayorDet[$idSeg][$tc11] 	+= $d->resultado/$valMon;
					$sumaCodigo[$cod]		+= $d->resultado/$valMon;			//Suma Total
					$clasifAnterior 		= $d->Clasificacion;
					$grupoAnterior 			= $d->Grupo;
					$mayorAnterior 			= $d->Cuenta_de_mayor;
					$red = $redMes = '';
					$titleAnterior 			= $title;

					
				}
				
				while($s<=$tseg){
						echo "<td style='text-align:right;' class='seg-$s' cantidad='0.00'>$ 0.00</td>";
						$s++;
				}

				if(floatval($sumaCuenta[$tc1]) < 0) {$red = "color:red";}else{$red='';}
				echo "<td style='font-weight:bold;text-align:right;$red'>$ ".number_format($sumaCuenta[$tc1],2)."</td>";
				echo "</tr>";

				echo "<tr numero='$nn' style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' class='anterior'>
						<td></td>
						<td style='text-align:left;'>Total $mayorAnterior</td>";
						$s1=1;
						while($s1<=$tseg){
							if($grupoAnterior == "EGRESOS")
												{
													$sumaCodigo[$cod] = $sumaCodigo[$cod] *-1;
													$sumaMayorDet[$s1][$tc11] = $sumaMayorDet[$s1][$tc11] *-1;
												}
							if(floatval($sumaMayorDet[$s1][$tc11]) < 0) {$red = "color:red";}else{$red='';}
						echo "<td style='text-align:right;'>$ ".number_format($sumaMayorDet[$s1][$tc11],2)."</td>";
						$s1++;
						}
						if(floatval($sumaCodigo[$cod]) < 0) {$red = "color:red";}else{$red='';}
				echo "<td style='text-align:right;'>$ ".number_format($sumaCodigo[$cod],2)."</td>";	
				echo "</tr>";

				echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'>
						<td></td>
						<td>TOTAL ".strtoupper($titleAnterior)."</td>";
						$s1=1;
						while($s1<=$tseg){
							if($grupoAnterior == "EGRESOS")
							{
								//$sumaMayorMes[$s1][$cm1] = $sumaMayorMes[$s1][$cm1] *-1;
								$sumaGrupo[$cm1] = $sumaGrupo[$cm1] *-1;
							}
						if(floatval($sumaMayorMes[$s1][$cm1]) < 0) {$red = "color:red";}else{$red='';}
						echo "<td style='text-align:right;$red'>$ ".number_format($sumaMayorMes[$s1][$cm1],2)."</td>";
						$s1++;
						}
						if(floatval($sumaGrupo[$cm1]) < 0) {$red = "color:red";}else{$red='';}	
				echo "<td style='text-align:right;$red'>$ ".number_format($sumaGrupo[$cm1],2)."</td>";
				echo "</tr>";

				echo "<tr style='$titulo1'>
						<td></td>
						<td style='text-align:left;'>TOTAL RESULTADOS</td>";
						$s1=1;
						while($s1<=$tseg){
							if(floatval($sumaTotal[$s1]) < 0) {$red = "color:red";}else{$red='';}
						echo "<td style='text-align:right;$red'>$ ".number_format($sumaTotal[$s1],2)."</td>";
						$s1++;
						}
						if(floatval($sumaTotalGral) < 0) {$red = "color:red";}else{$red='';}
				echo "<td style='text-align:right;$red'>$ ".number_format($sumaTotalGral,2)."</td>";
				echo "</tr>";
					 
				
				
				?>
			</table>
<input type='hidden' id='totalMayores' value='<?php echo $sumaCont; ?>'>
<!--INICIA TITULO CONGELADO-->


<!--TERMINA-->
</div>

<!--GENERA PDF*************************************************-->
<div id="divpanelpdf"
				style="
					position: absolute; top:30%; left: 40%;
					opacity:0.9;
					padding: 20px;
					-webkit-border-radius: 20px;
    			border-radius: 10px;
					background-color:#000;
					color:white;
				  display:none;	
				  z-index:1;
				">
				<form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
				<!--form id="formpdf" action="../../../webapp/netwarelog/repolog/pdf.php" method="post" target="_blank" onsubmit="generar_pdf()">-->
					<center>
					<b> Generar PDF </b>
					<br><br>

					<table style="border:none;z-index:1;">
						<tbody>
							<tr>
								<td style="color:white;font-size:13px;">Escala:</td>
								<td style="color:white;font-size:13px;">
									<select id="cmbescala" name="cmbescala">
									<option value=100>100</option>
<option value=99>99</option>
<option value=98>98</option>
<option value=97>97</option>
<option value=96>96</option>
<option value=95>95</option>
<option value=94>94</option>
<option value=93>93</option>
<option value=92>92</option>
<option value=91>91</option>
<option value=90>90</option>
<option value=89>89</option>
<option value=88>88</option>
<option value=87>87</option>
<option value=86>86</option>
<option value=85>85</option>
<option value=84>84</option>
<option value=83>83</option>
<option value=82>82</option>
<option value=81>81</option>
<option value=80>80</option>
<option value=79>79</option>
<option value=78>78</option>
<option value=77>77</option>
<option value=76>76</option>
<option value=75>75</option>
<option value=74>74</option>
<option value=73>73</option>
<option value=72>72</option>
<option value=71>71</option>
<option value=70>70</option>
<option value=69>69</option>
<option value=68>68</option>
<option value=67>67</option>
<option value=66>66</option>
<option value=65>65</option>
<option value=64>64</option>
<option value=63>63</option>
<option value=62>62</option>
<option value=61>61</option>
<option value=60>60</option>
<option value=59>59</option>
<option value=58>58</option>
<option value=57>57</option>
<option value=56>56</option>
<option value=55>55</option>
<option value=54>54</option>
<option value=53>53</option>
<option value=52>52</option>
<option value=51>51</option>
<option value=50>50</option>
<option value=49>49</option>
<option value=48>48</option>
<option value=47>47</option>
<option value=46>46</option>
<option value=45>45</option>
<option value=44>44</option>
<option value=43>43</option>
<option value=42>42</option>
<option value=41>41</option>
<option value=40>40</option>
<option value=39>39</option>
<option value=38>38</option>
<option value=37>37</option>
<option value=36>36</option>
<option value=35>35</option>
<option value=34>34</option>
<option value=33>33</option>
<option value=32>32</option>
<option value=31>31</option>
<option value=30>30</option>
<option value=29>29</option>
<option value=28>28</option>
<option value=27>27</option>
<option value=26>26</option>
<option value=25>25</option>
<option value=24>24</option>
<option value=23>23</option>
<option value=22>22</option>
<option value=21>21</option>
<option value=20>20</option>
<option value=19>19</option>
<option value=18>18</option>
<option value=17>17</option>
<option value=16>16</option>
<option value=15>15</option>
<option value=14>14</option>
<option value=13>13</option>
<option value=12>12</option>
<option value=11>11</option>
<option value=10>10</option>
<option value=9>9</option>
<option value=8>8</option>
<option value=7>7</option>
<option value=6>6</option>
<option value=5>5</option>
<option value=4>4</option>
<option value=3>3</option>
<option value=2>2</option>
<option value=1>1</option>
									</select> %
								</td>
							</tr>
							<tr>
								<td style="color:white;font-size:13px;">Orientación:</td>
								<td style="color:white;">
									<select id="cmborientacion" name="cmborientacion">
										<option value='P'>Vertical</option>
										<option value='L'>Horizontal</option>
									</select>
								</td>
							</tr>
					</tbody>
				</table>
				<br>
					
				<textarea id="contenido" name="contenido" style="display:none"></textarea>
				<input type='hidden' name='tipoDocu' value='hg'>
				<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
				<input type='hidden' name='nombreDocu' value='Estado de Resultados'>
				<input type="submit" value="Crear PDF" autofocus >
				<input type="button" value="Cancelar" onclick="cancelar_pdf()">
				
				</center>
				</form>
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