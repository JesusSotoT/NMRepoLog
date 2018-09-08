<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>
$(document).ready(function()
{
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};

	$('#nmloader_div',window.parent.document).hide();	
	
	var suma1=0;suma2=0;suma3=0;suma4=0;suma5=0;suma6=0;
	
	/*$(".mayores").each(function(index, el) {
		
		mayor = $(this).attr('id');
		$(".afectables[mayor='"+mayor+"']").each(function() {
			if(mayor == mayorAntes)
			{
				suma1 += parseFloat($('td:nth-child(3)',this).attr('cantidad'))
				suma2 += parseFloat($('td:nth-child(4)',this).attr('cantidad'))
				suma3 += parseFloat($('td:nth-child(5)',this).attr('cantidad'))
				suma4 += parseFloat($('td:nth-child(6)',this).attr('cantidad'))
				suma5 += parseFloat($('td:nth-child(7)',this).attr('cantidad'))
				suma6 += parseFloat($('td:nth-child(8)',this).attr('cantidad'))
			}
			else
			{
				$(".mayores[id='"+mayorAntes+"'] td:nth-child(3)").text("$ "+suma1.format())
				$(".mayores[id='"+mayorAntes+"'] td:nth-child(4)").text("$ "+suma2.format())
				$(".mayores[id='"+mayorAntes+"'] td:nth-child(5)").text("$ "+suma3.format())
				$(".mayores[id='"+mayorAntes+"'] td:nth-child(6)").text("$ "+suma4.format())
				$(".mayores[id='"+mayorAntes+"'] td:nth-child(7)").text("$ "+suma5.format())
				$(".mayores[id='"+mayorAntes+"'] td:nth-child(8)").text("$ "+suma6.format())
				suma1 = parseFloat($('td:nth-child(3)',this).attr('cantidad'))
				suma2 = parseFloat($('td:nth-child(4)',this).attr('cantidad'))
				suma3 = parseFloat($('td:nth-child(5)',this).attr('cantidad'))
				suma4 = parseFloat($('td:nth-child(6)',this).attr('cantidad'))
				suma5 = parseFloat($('td:nth-child(7)',this).attr('cantidad'))
				suma6 = parseFloat($('td:nth-child(8)',this).attr('cantidad'))
			}
			alert(mayorAntes)
			mayorAntes = mayor
			
		});
	});*/

	$(".mayores").each(function()
	{
		suma1=0;suma2=0;suma3=0;suma4=0;suma5=0;suma6=0;
		$(".afectables[mayor='"+$(this).attr('id')+"']").each(function()
		{
		 	suma1+= parseFloat($('td:nth-child(3)',this).attr('cantidad'))
		 	suma2+= parseFloat($('td:nth-child(4)',this).attr('cantidad'))
		 	suma3+= parseFloat($('td:nth-child(5)',this).attr('cantidad'))
		 	suma4+= parseFloat($('td:nth-child(6)',this).attr('cantidad'))
		 	suma5+= parseFloat($('td:nth-child(7)',this).attr('cantidad'))
		 	suma6+= parseFloat($('td:nth-child(8)',this).attr('cantidad'))
		});
		
		$(".mayores[id='"+$(this).attr('id')+"'] td:nth-child(3)").text("$ "+suma1.format()).attr("cantidad",suma1)
		$(".mayores[id='"+$(this).attr('id')+"'] td:nth-child(4)").text("$ "+suma2.format()).attr("cantidad",suma2)
		$(".mayores[id='"+$(this).attr('id')+"'] td:nth-child(5)").text("$ "+suma3.format()).attr("cantidad",suma3)
		$(".mayores[id='"+$(this).attr('id')+"'] td:nth-child(6)").text("$ "+suma4.format()).attr("cantidad",suma4)
		$(".mayores[id='"+$(this).attr('id')+"'] td:nth-child(7)").text("$ "+suma5.format()).attr("cantidad",suma5)
		$(".mayores[id='"+$(this).attr('id')+"'] td:nth-child(8)").text("$ "+suma6.format()).attr("cantidad",suma6)
	});

	$(".padres").each(function()
	{
		suma1=0;suma2=0;suma3=0;suma4=0;suma5=0;suma6=0;
		$(".afectables[padre='"+$(this).attr('id')+"']").each(function()
		{
		 	suma1+= parseFloat($('td:nth-child(3)',this).attr('cantidad'))
		 	suma2+= parseFloat($('td:nth-child(4)',this).attr('cantidad'))
		 	suma3+= parseFloat($('td:nth-child(5)',this).attr('cantidad'))
		 	suma4+= parseFloat($('td:nth-child(6)',this).attr('cantidad'))
		 	suma5+= parseFloat($('td:nth-child(7)',this).attr('cantidad'))
		 	suma6+= parseFloat($('td:nth-child(8)',this).attr('cantidad'))
		});
		
		$(".padres[id='"+$(this).attr('id')+"'] td:nth-child(3)").text("$ "+suma1.format())
		$(".padres[id='"+$(this).attr('id')+"'] td:nth-child(4)").text("$ "+suma2.format())
		$(".padres[id='"+$(this).attr('id')+"'] td:nth-child(5)").text("$ "+suma3.format())
		$(".padres[id='"+$(this).attr('id')+"'] td:nth-child(6)").text("$ "+suma4.format())
		$(".padres[id='"+$(this).attr('id')+"'] td:nth-child(7)").text("$ "+suma5.format())
		$(".padres[id='"+$(this).attr('id')+"'] td:nth-child(8)").text("$ "+suma6.format())
	});

	$(".padresMayor").each(function()
	{
		suma1=0;suma2=0;suma3=0;suma4=0;suma5=0;suma6=0;
		$(".mayores[padre='"+$(this).attr('id')+"']").each(function()
		{
		 	suma1+= parseFloat($('td:nth-child(3)',this).attr('cantidad'))
		 	suma2+= parseFloat($('td:nth-child(4)',this).attr('cantidad'))
		 	suma3+= parseFloat($('td:nth-child(5)',this).attr('cantidad'))
		 	suma4+= parseFloat($('td:nth-child(6)',this).attr('cantidad'))
		 	suma5+= parseFloat($('td:nth-child(7)',this).attr('cantidad'))
		 	suma6+= parseFloat($('td:nth-child(8)',this).attr('cantidad'))
		});
		
		$(".padresMayor[id='"+$(this).attr('id')+"'] td:nth-child(3)").text("$ "+suma1.format())
		$(".padresMayor[id='"+$(this).attr('id')+"'] td:nth-child(4)").text("$ "+suma2.format())
		$(".padresMayor[id='"+$(this).attr('id')+"'] td:nth-child(5)").text("$ "+suma3.format())
		$(".padresMayor[id='"+$(this).attr('id')+"'] td:nth-child(6)").text("$ "+suma4.format())
		$(".padresMayor[id='"+$(this).attr('id')+"'] td:nth-child(7)").text("$ "+suma5.format())
		$(".padresMayor[id='"+$(this).attr('id')+"'] td:nth-child(8)").text("$ "+suma6.format())
	});

	var total1=0;total2=0;total3=0;total4=0;total5=0;total6=0;
	$(".titulo").not("#ORDEN").each(function(){
		suma1=0;suma2=0;suma3=0;suma4=0;suma5=0;suma6=0;
		$(".afectables[titulo='"+$(this).attr('id')+"']").each(function(){
		 	suma1+= parseFloat($('td:nth-child(3)',this).attr('cantidad'))
		 	suma2+= parseFloat($('td:nth-child(4)',this).attr('cantidad'))
		 	suma3+= parseFloat($('td:nth-child(5)',this).attr('cantidad'))
		 	suma4+= parseFloat($('td:nth-child(6)',this).attr('cantidad'))
		 	suma5+= parseFloat($('td:nth-child(7)',this).attr('cantidad'))
		 	suma6+= parseFloat($('td:nth-child(8)',this).attr('cantidad'))
		 	total1+= parseFloat($('td:nth-child(3)',this).attr('cantidad'))
		 	total2+= parseFloat($('td:nth-child(4)',this).attr('cantidad'))
		 	total3+= parseFloat($('td:nth-child(5)',this).attr('cantidad'))
		 	total4+= parseFloat($('td:nth-child(6)',this).attr('cantidad'))
		 	total5+= parseFloat($('td:nth-child(7)',this).attr('cantidad'))
		 	total6+= parseFloat($('td:nth-child(8)',this).attr('cantidad'))
		});
		$("#"+$(this).attr('id')+" td:nth-child(3)").text("$ "+suma1.format())
		$("#"+$(this).attr('id')+" td:nth-child(4)").text("$ "+suma2.format())
		$("#"+$(this).attr('id')+" td:nth-child(5)").text("$ "+suma3.format())
		$("#"+$(this).attr('id')+" td:nth-child(6)").text("$ "+suma4.format())
		$("#"+$(this).attr('id')+" td:nth-child(7)").text("$ "+suma5.format())
		$("#"+$(this).attr('id')+" td:nth-child(8)").text("$ "+suma6.format())
	});
	$("#totales td:nth-child(3)").text("$ "+total1.format())
	$("#totales td:nth-child(4)").text("$ "+total2.format())
	$("#totales td:nth-child(5)").text("$ "+total3.format())
	$("#totales td:nth-child(6)").text("$ "+total4.format())
	$("#totales td:nth-child(7)").text("$ "+total5.format())
	$("#totales td:nth-child(8)").text("$ "+total6.format())
	
if($('#tipoVista').val() == '1')
{
	$(".mayores").remove();
	$(".padres").remove();
	$(".padresMayor").remove();
}

if($('#tipoVista').val() == '2')
{
	$(".afectables").remove();	
	$(".padres").remove();
	$(".padresMayor").remove();
}
});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<style>
	.titulo
	{
		color:black;background-color:#edeff1;height:30px;font-weight:bold;text-align:left;
	}

	.afectables
	{
		height: 30px !important;text-align:left;;
	}
	.mayores
	{
		color:black;height:30px;text-align:left;
	}
	.tit_tabla_buscar td
	{
		font-size:medium;
	}

	#logo_empresa /*Logo en pdf*/
		{
			display:none;
		}

	.clasemayor
	{
		color:white;
		background-color:gray;
	}
	#titulo_impresion
	{
		visibility: hidden;
	}
	#sc
	{
		overflow:scroll;
	}
	@media print
	{
		#imprimir,#filtros,#excel,#titulo,#email_icon, #botones
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
		.table-responsive{
			overflow-x: unset;
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
</style>
<?php
$moneda=$_POST['moneda'];
if($_POST['valMon']){$valMon=$_POST['valMon'];}else{$valMon=1;}
//$valMon=13.5;

ini_set('memory_limit', '-1');
?>	


<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Balanza de Comprobación<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=reports&f=balanzaComprobacion' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros' onclick="javascript:$('#nmloader_div',window.parent.document).hide();"><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<input type='hidden' value='Balanza de Comprobacion.' id='titulo'>
					<section id='imprimible'>
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
						<div class="row">
							<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
								<b style='text-align:center;'><?php echo $empresa;?></b>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12" style='text-align:center;'>
								<b style="font-size:15px;">Balanza de Comprobación </b><br>   
								Ejercicio <b><?php echo $ej2; ?></b> Periodo De <b><?php echo $fecIni; ?></b> A <b><?php echo $fecFin; ?></b><br>
								<?php
								if($tipoVista == 1)
								{
									$nivel = 'Afectables';
								}
								if($tipoVista == 2)
								{
									$nivel = 'Mayor';
								}
								if($tipoVista == 3)
								{
									$nivel = 'Todos';
								}
								?>
								A nivel de <span id='nivel' style='font-weight:bold;'><?php echo $nivel; ?></span> No. de Cuentas: <b><span id='cuentaCuentas'><?php echo $n_cuentas;?></span></b>
								<?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio <b>$ $valMon </b> ";}?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
										<div class="table-responsive">
											<table class="table" border='0' align='center' cellpadding=3 style='min-width:550px;max-width:950px;font-size:9px;'>
												<thead>
												<tr style='background-color:#e4e7ea;font-size:10px;font-weight:bold;height:30px;text-align:center;'>
													<td style='width:8%'>Código</td>
													<td style='width:20%'>Nombre</td>
													<td style='width:12%'>Saldo Inicial Deudor</td>
													<td style='width:12%'>Saldo Inicial Acreedor</td>
													<td style='width:12%'>Cargos</td>
													<td style='width:12%'>Abonos</td>
													<td style='width:12%'>Saldo Final Deudor</td>
													<td style='width:12%'>Saldo Final Acreedor</td>
												</tr>
												</thead>
												<tbody>
													<?php 
													$mayor 		= '';
													$padre 		= '';
													$padreMayor	= '';
													$codigo 	= '';
													$grupo		= '';
													$cargosMayor= 0;
													$abonosMayor= 0;
													$cargos 	= 0;
													$abonos 	= 0;
													$cont 		= 0;
													$contMayor	= 0;
														while($d = $datos->fetch_object())
														{

															//Pinta los grupos, activo, pasivo, capital y resultados
															if($d->h1 != $grupo)
															{
																if(intval($_POST['idioma']))
																{
																	switch($d->h1)
																	{
																		case 1:$tituloGrupo = 'ASSETS';break;
																		case 2:$tituloGrupo = 'LIABILITIES';break;
																		case 3:$tituloGrupo = 'CAPITAL';break;
																		case 4:$tituloGrupo = 'RESULTS';break;
																		case 5:$tituloGrupo = 'ORDER';break;
																	}
																}
																else
																{
																	switch($d->h1)
																	{
																		case 1:$tituloGrupo = 'ACTIVO';break;
																		case 2:$tituloGrupo = 'PASIVO';break;
																		case 3:$tituloGrupo = 'CAPITAL';break;
																		case 4:$tituloGrupo = 'RESULTADOS';break;
																		case 5:$tituloGrupo = 'ORDEN';break;
																	}
																}

																echo "<tr style='font-weight:bold;' id='$tituloGrupo' class='titulo'><td>$tituloGrupo</td><td></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td></tr>";
															}

															//pinta las sumas por cuenta de mayor
															$IdDescMayor = explode('/',$d->mayor);
															$IdDescPadre = explode('/',$d->padre);
															$IdDescPadreMayor = explode('/',$d->padreMayor);
															if($tipoVista==2)
															{
																
												    				$colorMayor='#fafafa';
												    				$colorPadre='#fafafa';
												    				$colorPadreMayor='#fafafa';
												    				
																
															}
															else
															{
																$colorMayor="#f6f7f8;font-weight: bold";
																$colorPadre="#edeff1;font-weight: bold";
																$colorPadreMayor="#edeff1;font-weight: bold";
															}
															if($d->padreMayor != $padreMayor && $IdDescPadreMayor[2])
															{
																echo "<tr class='padresMayor' style='background-color:$colorPadreMayor;' id='".$IdDescPadreMayor[0]."'><td><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=".$_POST['periodo_inicio']."&f4_1=".$_POST['periodo_fin']."&f3_3=".$ej2."&f4_3=".$ej2."&tipo=1&cuentas[]=".$d->account_id."&segmento=todos' target='_blank'>".$IdDescPadreMayor[0]."</a></td><td>".$IdDescPadreMayor[1]."</td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td></tr>";
															}

															if($d->mayor != $mayor)
															{
																echo "<tr class='mayores' style='background-color:$colorMayor;' id='".$IdDescMayor[0]."' padre='".$IdDescPadreMayor[0]."'><td><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=".$_POST['periodo_inicio']."&f4_1=".$_POST['periodo_fin']."&f3_3=".$ej2."&f4_3=".$ej2."&tipo=1&cuentas[]=".$d->account_id."&segmento=todos' target='_blank'>".$IdDescMayor[0]."</a></td><td>".$IdDescMayor[1]."</td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td></tr>";
															}

															if($d->padre != $padre && $d->padre != $d->mayor)
															{
																echo "<tr class='padres' style='background-color:$colorPadre;' id='".$IdDescPadre[0]."'><td><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=".$_POST['periodo_inicio']."&f4_1=".$_POST['periodo_fin']."&f3_3=".$ej2."&f4_3=".$ej2."&tipo=1&cuentas[]=".$d->account_id."&segmento=todos' target='_blank'>".$IdDescPadre[0]."</a></td><td>".$IdDescPadre[1]."</td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td></tr>";
															}

															//pinta las sumas por cuentas afectables
															if($d->CargosMes=='') $d->CargosMes=0;
															if($d->AbonosMes=='') $d->AbonosMes=0;
															if($d->CargosAntes=='') $d->CargosAntes=0;
															if($d->AbonosAntes=='') $d->AbonosAntes=0;
															
															if($d->account_nature == 1)
															{
																$InicialDeudor = 0;
																$InicialAcreedor = floatval($d->AbonosAntes) - floatval($d->CargosAntes);
																$InicialAcreedor = $InicialAcreedor/$valMon;
																$Suma = floatval($d->AbonosMes) - floatval($d->CargosMes);
																$Suma = $Suma/$valMon;
																$FinalDeudor = 0;
																$FinalAcreedor = floatval($InicialAcreedor) + floatval($Suma);
																$FinalAcreedor = number_format((float)$FinalAcreedor, 2, '.', '');
															}

															if($d->account_nature == 2)
															{
																$InicialDeudor = floatval($d->CargosAntes) - floatval($d->AbonosAntes);
																$InicialDeudor = $InicialDeudor/$valMon;
																$InicialAcreedor = 0;
																$Suma = floatval($d->CargosMes) - floatval($d->AbonosMes);
																$Suma = $Suma/$valMon;
																$FinalDeudor = floatval($InicialDeudor) + floatval($Suma);
																$FinalDeudor = number_format((float)$FinalDeudor, 2, '.', '');
																$FinalAcreedor = 0;
															}

															
																if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
																{
												    				$color2='#ffffff';
																}
																else//Si es impar pinta esto
																{
												    				$color2='#fafafa';
																}
																$cont++;
															
															
																$cm=$d->CargosMes/$valMon;
																$am=$d->AbonosMes/$valMon;
																
																$tot = 1;
																if(!$_POST['ceros'])
																	if(!$InicialDeudor && !$InicialAcreedor && !$d->AbonosMes && !$d->CargosMes)
																		$tot = 0;
																$display = '';
																if($_POST['saldo'])
																	if(!($FinalDeudor + $FinalAcreedor))
																		$display = 'display:none;';


																if($tot)
																	echo "<tr tot=$tot class='afectables' style='background-color:$color2;$display' mayor='".$IdDescMayor[0]."' titulo='$tituloGrupo' padre='".$IdDescPadre[0]."'><td><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=".$_POST['periodo_inicio']."&f4_1=".$_POST['periodo_fin']."&f3_3=".$ej2."&f4_3=".$ej2."&tipo=0&cuentas[]=".$d->account_id."&segmento=todos' target='_blank'>$d->manual_code</a></td><td>$d->description</td><td style='text-align:right;' cantidad='$InicialDeudor'>$ ".number_format($InicialDeudor,2)."</td><td style='text-align:right;' cantidad='$InicialAcreedor'>$ ".number_format($InicialAcreedor,2)."</td><td style='text-align:right;' cantidad='$cm'>$ ".number_format(($d->CargosMes/$valMon),2)."</td><td style='text-align:right;' cantidad='$am'>$ ".number_format(($d->AbonosMes/$valMon),2)."</td><td style='text-align:right;' cantidad='$FinalDeudor'>$ ".number_format($FinalDeudor,2)."</td><td style='text-align:right;' cantidad='$FinalAcreedor'>$ ".number_format($FinalAcreedor,2)."</td></tr>";	
															

															//guarda anterior
															$mayor 		= $d->mayor;
															$padre 		= $d->padre;
															$padreMayor	= $d->padreMayor;
															$codigo 	= $d->account_code;
															$grupo 		= $d->h1;

														}
														if(intval($_POST['idioma']))
															$total = "TOTALS";
														else
															$total = "TOTALES";
													?>
													<tr id='totales' class="titulo" style='background-color:e4e7ea;height:30px;font-weight:bold;'><td><?php echo $total; ?></td><td></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td><td style='text-align:right'></td></tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type='hidden' id='tipoVista' value='<?php echo $tipoVista; ?>'>
					</section>
				</div>
			</div>
			<div class="col-md-4 col-sm-1">
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
					<input type='hidden' name='nombreDocu' value='Balanza de Comprobacion'>
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