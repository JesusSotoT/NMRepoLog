<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function()
{
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};
	var grupo=''
	var clasif=''
	var suma=0;
	var numero=0;
	var red;
	var cont=0;
	
			//Sumatorias por Grupo*********************************
			$(".Grupo").each(function(index)
			{
				grupo = $(this).attr('id')
				grupo = grupo.split('-')
				for(m=1;m<=12;m++)
				{
					$("td[grupo='"+grupo[1]+"'][mes='"+m+"']").each(function(index)
					{
						//alert($(this).attr('id'))
						numero = $(this).attr('cantidad')
						suma += parseFloat(numero)
						clasif = $(this).attr('Clasif')
					});
					if(m==1)
					{
						$("#sum-"+grupo[1]).append("<td colspan='2' style='font-weight:bold;'>TOTAL "+$('td',this).html()+"</td>");	
					}

					red=''
					if(suma<0) red='color:red;'
					$("#sum-"+grupo[1]).append("<td style='text-align:right;font-weight:bold;"+red+"' class='totalGrupo' Clasif='"+clasif+"' grupo='"+grupo[1]+"' mes='"+m+"' cantidad='"+suma.toFixed(2)+"'>$ "+suma.format()+"</td>");
					suma=0;
				}
			});

			//Sumatorias por Cuentas de Mayor*********************************
			var cantgrup,total,a,g;
			$(".mayor").each(function(index)
			{
				mayor = $(this).attr('id')
				mayor = mayor.split('-')
				for(m=1;m<=12;m++)
				{
					g = $(".totalGrupo[grupo='1'][mes='"+m+"']").attr('cantidad')
					$("td[mayor='"+mayor[1]+"'][mes='"+m+"']").each(function(index)
					{
						numero = $(this).attr('cantidad')
						suma += parseFloat(numero)
						clasif = $(this).attr('Clasif')
						grupo = $(this).attr('grupo')
						
						$(this).attr('title',(parseFloat(numero)/parseFloat(g)*100).toFixed(2)+"%")
					});
					
					red=''
					if(suma<0) red='color:red;'

					total = suma / g * 100

					$("#may-"+mayor[1]).append("<td title='"+total.toFixed(2)+"%' style='text-align:right;font-weight:bold;"+red+"' class='totalGrupo' grupom='"+grupo+"' mes='"+m+"' cantidad='"+suma.toFixed(2)+"'>$ "+suma.format()+"</td>");
					suma=0;
				}
			});

			//Sumatorias por Clasificacion***************************
			$(".Clasificacion").each(function(index)
			{
				clasif = $(this).attr('id')
				//alert(clasif)
				for(m=1;m<=12;m++)
				{
					cont=0
					$("td[clasif='"+clasif+"'][mes='"+m+"'][class='totalGrupo']").each(function(index)
					{
						numero = $(this).attr('cantidad')				
					
						if(clasif == 'Resultados')
						{
							if(cont==0)
							{
								suma += parseFloat(numero)
							}
							else
							{
								suma -= parseFloat(numero)
							}
							cont++
						}
						else
						{
							suma += parseFloat(numero)
						}

					});
					if(m==1)
					{
						var clsf;
						if(parseInt("<?php echo $_POST['idioma']; ?>"))
						{
							switch(clasif)
							{
								case "Activo":  clsf= "ASSETS";break;
								case "Pasivo":  clsf= "LIABILITIES";break;
								case "Capital":  clsf= "CAPITAL";break;
								case "Resultados":  clsf= "RESULTS";break;
							}
						}
						else
						{
							clsf = clasif.toUpperCase();
						}
						$("#sum-"+clasif).append("<td colspan='2' style='font-weight:bold;'>TOTAL "+clsf+"</td>");	
					}
					red=''
					if(suma<0) red='color:red;'
					$("#sum-"+clasif).append("<td style='text-align:right;font-weight:bold;"+red+"' cantidad='"+suma+"'>$ "+suma.format()+"</td>");
					suma=0;
				}
			});

			//Sumatoria de Pasivo, Capital y Resultados***************************
			var pasivo=0, capital=0, resultado=0, mes=0, res=0

			for(m=1;m<=12;m++)
			{
				mes = m+1;
				pasivo = $("#sum-Pasivo td:nth-child("+mes+")").attr('cantidad')
				if(typeof pasivo === 'undefined') pasivo=0

				capital = $("#sum-Capital td:nth-child("+mes+")").attr('cantidad')
				if(typeof capital === 'undefined') capital=0

				res = $("#sum-Resultados td:nth-child("+mes+")").attr('cantidad')
				if(typeof res === 'undefined') res=0

				resultado = parseFloat(pasivo) + parseFloat(capital) + parseFloat(res)
				
				red=''
				if(resultado<0) red='color:red;'

				$("#sum-pas-cap").append("<td style='text-align:right;"+red+"'>$ "+resultado.format()+"</td>")

			}

			//Remueve elementos que ya no son necesarios
			$("#sum-").remove();
			$("#sum-0").remove();
			if($("#tipo").val() == '1')
			{
				$("tr[clasif='Resultados']").remove();
			}
			else
			{
				$("#sum-pas-cap").remove();
			}

			$("#may-0").remove()
		$(".tbdy:first").html($(".tbl1:first").html());
});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
$(document).ready(function(){
	$('#nmloader_div',window.parent.document).hide();	
});			
</script>
<!--COMIENZA-->
<script language='javascript' src='js/pdfmail.js'></script>
<!--TERMINA-->
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
		#imprimir,#filtros,#excel,#email_icon, #botones
		{
			display:none;
		}
		#logo_empresa
		{
			display:inline;
		}
		.table-responsive{
			overflow-x: unset;
		}
		table{
			zoom: 0.5 !important;
		}
		#imp_cont{
			width: 100% !important
		}
		.tbdy{
			/*display: unset !important;*/
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
</style>
<?php
if(intval($_GET['tipo']))
{
	$titulo = "Balance General";
}
else
{
	$titulo = "Estado de Resultados";	
}
?>
<input type='hidden' id='tipo' value='<?php echo $_GET['tipo'];?>'>
<?php
$moneda=$_POST['moneda'];
if($_POST['valMon']){$valMon=$_POST['valMon'];}else{$valMon=1;}
//$valMon=13.5;
// Manejo de Colores -----------------------------------------------
$titulo1="font-weight:bold;font-size:10px;color:black;background-color:#edeff1;height:30px;";
$subtitulo="font-weight:bold;height:30px;background-color:#fafafa;font-size:9px;";
$total="height:30px;font-weight:bold;background-color:#dbdfe3;font-size:11px;";
?>

<!-- ---------------------------------------------------------------------------------- -->

<div id='imprimible' style="display:none;">
	
	

	<!--table width='95%'>
		<tr>
			<td colspan='3'><b> a 12 meses de: </b> <?php //echo $empresa; ?>&nbsp;&nbsp;&nbsp;<b>Del Ejercicio</b> <?php echo $ej; ?></td>
		</tr>
	</table-->
	<!--Titulo congelado-->
	<!--INICIA-->
	<table border=0 width='2120' style='background:white;font-size:10px;'>
	<tr style='background:white;'><td width:50%;>

				<?php
				$url = explode('/modulos',$_SERVER['REQUEST_URI']);
				if($logo == 'logo.png') $logo = 'x.png';
				$logo = str_replace(' ', '%20', $logo);
				?>
				<img id='logo_empresa' src='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'>
			</td><td width:50%;></td></tr>
			<tr style='background:white;'><td colspan='2' style='text-align:center;font-size:18px;'><b style='text-align:center;'><?php echo $empresa;?></b></td></tr>
	               <tr style='background:white;'> 	<td style="text-align:center;color:#576370;font-size:12px;" colspan='2'>
										<b style='font-size:15px;'><?php echo $titulo; ?> a 12 Periodos</b><br>
										Ejercicio: <b><?php echo $ej;?> </b>Sucursal <b><?php echo $nomSucursal; ?></b> Segmento <b><?php echo $nomSegmento; ?></b> 
										<?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio <b>$ $valMon </b> ";}?>
										<br><br>
					
					</td>
		</tr>
	</table>

	<table border=0 width='2120' style="background:white;">
		<!--Logo en las tablas de aqui-->
				
			<!--Logo en las tablas hasta aqui-->
		<tr style='<?php echo "$total"; ?>'><td style='text-align:left;width:100px;'>CODIGO</td><td style='text-align:left;width:220px;'>CUENTA DE MAYOR</td><td style='width:150px;'>ENERO</td><td style='width:150px;'>FEBRERO</td><td style='width:150px;'>MARZO</td><td style='width:150px;'>ABRIL</td><td style='width:150px;'>MAYO</td><td style='width:150px;'>JUNIO</td><td style='width:150px;'>JULIO</td><td style='width:150px;'>AGOSTO</td><td style='width:150px;'>SEPTIEMBRE</td><td style='width:150px;'>OCTUBRE</td><td style='width:150px;'>NOVIEMBRE</td><td style='width:150px;'>DICIEMBRE</td></tr>
	</table>
	<div style='width:2120px;height:400px;overflow:scroll;background:white;'>
	<!--TERMINA-->
		<table border=0 width='2120' class="tbl1">
			<tr style='text-align:right;font-weight:bold;height:5px;'><td style='text-align:left;width:100px;'></td><td style='text-align:left;width:220px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td><td style='width:150px;'></td></tr>
			<?php
			function startsWith($haystack, $needle) 
					{
					    // search backwards starting from haystack length characters from the end
					    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
					}
			$codigoAnterior='';
			$grupoAnterior='';
			$grupoCont = $number = 0;
			$clasifAnterior='';
				while($d = $datos->fetch_object())
				{
			$CM = explode(' / ',$d->Cuenta_de_Mayor,2);
					//Si es a modo detalle pinta la cuenta de mayor sino el nombre de la afectable
						if(intval($_POST['detalle']))
						{
							$account = $d->Cuenta;
						}
						else
						{
							$account = $CM[1];
						}
					if(intval($_POST['idioma']))
					{
						$apcr = $d->Clasificacion_Alt;
						$title = $d->Grupo_Alt;
					}
					else
					{
						$apcr = $d->Clasificacion;
						$title = $d->Grupo;
					}
					if(startsWith($d->Grupo,'RESULTADO ACRE') || startsWith($d->Grupo,'RESULTADOS ACRE'))
						{
							$d->Grupo = "INGRESOS";	
						}
						if(startsWith($d->Grupo,'RESULTADO DEUDOR') || startsWith($d->Grupo,'RESULTADOS DEUDOR'))
						{
							$d->Grupo = "EGRESOS";	
						}
						//Si los siguientes meses no tienen valores rellena con ceros
						//INICIA
						if($codigoAnterior != $d->Codigo)
						{
							if(intval($mesAnterior) < 12 && intval($mesAnterior > 0))
							{
								for($m=intval($mesAnterior)+1;$m<=12;$m++)
								{
									echo "<td clasif='$d->Clasificacion' mayor='$number' grupo='$grupoCont' mes='$m' style='text-align:right;' cantidad='0'>$ 0.00</td>";
								}
								echo "</tr>";
							}
						}
						//TERMINA
						if($d->Cuenta_de_Mayor != $mayorAnterior AND intval($_POST['detalle']))
							{	
								echo "<tr style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' id='may-$number'><td colspan='2' style='text-align:left;'>TOTAL $mayorAnterior</td></tr>";
							}
					if($clasifAnterior != $d->Clasificacion)
					{
						if($grupoAnterior != $d->Grupo)
						{
							$grupoCont++;
							echo "<tr id='sum-".($grupoCont-1)."' style='$subtitulo' clasif='$clasifAnterior'></tr>";	
						}
						
						echo "<tr id='sum-$clasifAnterior' style='$titulo1'></tr>";
						echo "<tr style='height:20px;'><td colspan='14'></td></tr>";	
						echo "<tr id='$d->Clasificacion' class='Clasificacion' style='$titulo1'><td colspan='14' >".strtoupper($apcr)."</td></tr>";
						
						if($grupoAnterior != $d->Grupo)
						{
							echo "<tr id='grupo-$grupoCont' class='Grupo' clasif='$d->Clasificacion' style='$subtitulo'><td colspan='14' >$title</td></tr>";	
						}
					}
					else
					{
						if($grupoAnterior != $d->Grupo)
						{
							$grupoCont++;
							echo "<tr id='sum-".($grupoCont-1)."' style='$subtitulo' clasif='$d->Clasificacion'></tr>";
							echo "<tr style='height:5px;'><td colspan='14'></td></tr>";		
							echo "<tr id='grupo-$grupoCont' style='$subtitulo' class='Grupo' clasif='$d->Clasificacion'><td colspan='14' >$title</td></tr>";	
						}
					}
					
					if(($d->Clasificacion == 'Pasivo' || $d->Clasificacion == 'Capital') && $d->Naturaleza == 'ACREEDORA')
						{
							$d->CargosAbonos = $d->CargosAbonos*-1;
						}
					if($d->Clasificacion == 'Resultados' && $d->Grupo == 'INGRESOS' && $d->Naturaleza == 'ACREEDORA')
						{
							$d->CargosAbonos = $d->CargosAbonos*-1;
						}	
					
					$red='';
					if(floatval($d->CargosAbonos)<0) $red='color:red;';
					
					if($codigoAnterior != $d->Codigo)
					{
						if($d->Cuenta_de_Mayor != $mayorAnterior AND intval($_POST['detalle']))
							{	
								//echo "<tr style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' id='may-$number'><td colspan='2' style='text-align:left;'>Total $mayorAnterior</td></tr>";
								$number++;
								echo "<tr style='color:gray;font-weight:bold;height:50px;' class='mayor' id='mayor-$number'><td colspan='2'>$d->Cuenta_de_Mayor</td></tr>";
							}
						if($_POST['detalle']==0){$t=1;}else{$t=0;}
						if($_POST['segmento']==0){ $_POST['segmento']='todos';}
						echo "<tr clasif='$d->Clasificacion'><td style='mso-number-format:\"@\";'><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=1&f4_1=12&f3_3=".$_POST['ejercicio']."&f4_3=".$_POST['ejercicio']."&tipo=".$t."&cuentas[]=".$d->account_id."&segmento=".$_POST['segmento']."' target='_blank'>".$d->Codigo."</a></td><td>".$account."</td>";
						for($m=1;$m<intval($d->Mes);$m++)
						{
							echo "<td clasif='$d->Clasificacion' mayor='$number' grupo='$grupoCont' mes='$m' style='text-align:right;' cantidad='0'>$ 0.00</td>";
						}
						echo "<td clasif='$d->Clasificacion' mayor='$number' grupo='$grupoCont' mes='".intval($d->Mes)."' style='text-align:right;$red' cantidad='".number_format(($d->CargosAbonos/$valMon),2,'.','')."'>$ ".number_format(($d->CargosAbonos/$valMon),2)."</td>";
					}
					else
					{
						//SI entre un registro y otro pasa mas de un mes sin nada entonces rellena con ceros
						//INICIAL
						if((intval($d->Mes) - intval($mesAnterior)) > 1 )
						{
							for($m=$mesAnterior;$m<intval($d->Mes-1);$m++)
							{
								echo "<td clasif='$d->Clasificacion' mayor='$number' grupo='$grupoCont' mes='$m' style='text-align:right;' cantidad='0'>$ 0.00</td>";
							}
						}
						//TERMINA
						echo "<td title='' clasif='$d->Clasificacion' mayor='$number' grupo='$grupoCont' mes='".intval($d->Mes)."' style='text-align:right;$red' cantidad='".number_format(($d->CargosAbonos/$valMon),2,'.','')."'>$ ".number_format(($d->CargosAbonos/$valMon),2)."</td>";
						if(intval($d->Mes)==12)
						{
							echo "</tr>";	
						}
					}
					$mesAnterior 	= $d->Mes;
					$codigoAnterior = $d->Codigo;
					$grupoAnterior 	= $d->Grupo;
					$clasifAnterior = $d->Clasificacion;
					$mayorAnterior 	= $d->Cuenta_de_Mayor;
					if(intval($_POST['idioma']))
						{
							$grupoAnterior_Alt 	= $d->Grupo_Alt;
							$clasifAnterior_Alt = $d->Clasificacion_Alt;
						}
				}
				if(intval($mesAnterior)<12)//Rellena con ceros si ya no existen mas registros en meses posteriores en el ultimo registro
						{
							$ini = intval($mesAnterior) + 1;
							for($m=$ini;$m<=12;$m++)
							{
								echo "<td clasif='$clasifAnterior' mayor='$number' grupo='$grupoCont' mes='$m' style='text-align:right;' cantidad='0'>$ 0.00</td>";
							}
							echo "</tr>";	
						}
				echo "<tr style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' id='may-$number'><td colspan='2' style='text-align:left;'>Total $mayorAnterior</td></tr>";
				
				if(intval($_GET['tipo'])){
					echo "<tr id='sum-$grupoCont' style='height:30px;' clasif='$clasifAnterior'></tr>";	
					echo "<tr id='sum-$clasifAnterior' style='$subtitulo'></tr>";
					echo "<tr style='height:7px;'><td colspan='14'></td></tr>";
					}	else{
					echo "<tr id='sum-$grupoCont' style='$subtitulo' clasif='$clasifAnterior'></tr>";	
					echo "<tr style='height:7px;'><td colspan='14'></td></tr>";
					echo "<tr id='sum-$clasifAnterior' style='$total'></tr>";
						}
				if(intval($_POST['idioma']))
				{
					$tpcr = "LIABILITIES, CAPITAL AND RESULTS";
				}
				else
				{
					$tpcr = "PASIVO, CAPITAL Y RESULTADOS";
				}
				echo "<tr id='sum-pas-cap' style='$total'><td colspan='2'>TOTAL $tpcr</td></tr>";	
			?>
	</table>
	<!--INICIA TITULO CONGELADO-->
	</div>
<!--TERMINA-->
</div>

<!-- ---------------------------------------------------------------------------------- -->

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				<?php echo $titulo; ?> Periodos<br>
				<section id="botones">
					<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
	                <a href="index.php?c=reports&f=balanceGeneral&tipo=<?php echo $_GET['tipo']; ?>"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
					<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"> </a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
				</section>
			</h3>
			<input type='hidden' value='<?php echo $titulo; ?> Periodos.' id='titulo'>
			<div class="row">
				<div class="col-md-1">
				</div>
				<div class="col-md-10" id="imp_cont">
					<section >
						<div class="row">
							<div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3 text-center">
								<?php
									$url = explode('/modulos',$_SERVER['REQUEST_URI']);
									if($logo == 'logo.png') $logo = 'x.png';
									$logo = str_replace(' ', '%20', $logo);
								?>
								<img id='logo_empresa' src='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<label style="font-size:18px;width:100%;" class="text-center"><?php echo $empresa;?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<label style='font-size:15px;color:#576370;width:100%' class="text-center"><?php echo $titulo; ?> a 12 Periodos</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<label style='font-size:12px;color:#576370;width:100%' class="text-center">
									Ejercicio: <b><?php echo $ej;?> </b>Sucursal <b><?php echo $nomSucursal; ?></b> Segmento <b><?php echo $nomSegmento; ?></b> 
									<?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio <b>$ $valMon </b> ";}?>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table border=0 class="table">
										<thead>
											<tr style='<?php echo "$total"; ?>'><td style='text-align:left;width:100px;'>CODIGO</td><td style='text-align:left;width:220px;'>CUENTA DE MAYOR</td><td style='width:150px;'>ENERO</td><td style='width:150px;'>FEBRERO</td><td style='width:150px;'>MARZO</td><td style='width:150px;'>ABRIL</td><td style='width:150px;'>MAYO</td><td style='width:150px;'>JUNIO</td><td style='width:150px;'>JULIO</td><td style='width:150px;'>AGOSTO</td><td style='width:150px;'>SEPTIEMBRE</td><td style='width:150px;'>OCTUBRE</td><td style='width:150px;'>NOVIEMBRE</td><td style='width:150px;'>DICIEMBRE</td></tr>
										</thead>
										<tbody style="overflow: auto;  height: auto ! important;" class="tbdy">
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>

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