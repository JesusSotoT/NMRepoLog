<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script-->
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" language="javascript"></script>
<script language='javascript'>
$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};


	//Proceso que recorre los valores de los elementos y los suma.
	//Cuando termina agrega los elementos con los resultados

	//var total = 0;
	var subtotal = 0;
	for(var f = 7; f <= 8; f++)//Recorre cada columna
	{
		$('#xx').append("<td id='"+f+"'></td>");//Anexa un elemento a la tabla
	//	if(f!=9)
	//	{
			$("#polizas tr").each(function(index)//Recorre cada fila
			{
				if($(this).attr('tipo') == 'numero')//Si es un elemento de tipo numero hace la suma de los resulados
				{
					//total += parseFloat($("td:nth-child("+f+")",this).text())//Sumatoria total
					subtotal += parseFloat($("td:nth-child("+f+")",this).text().replace(/\,/g,'').replace('$',''))//Sumatoria del proveedor
				}
				if($(this).next().attr('tipo') == 'subtotal' && $(this).next().next().attr('tipo') != 'numero')//Si es un campo del tipo subtotal y el siguiente elemento no es de tipo numero entonces agrega elementos
				{
					if(f==7)//si se trata del primer barrido agraga td`s de relleno a la tabla
					{
						$(this).next().append("<td></td><td></td><td></td><td></td><td></td><td>Sumas Iguales:</td>");
					}

					if(isNaN(subtotal)){subtotal=0;}


						$(this).next().append("<td style='text-align:right;'>$ "+subtotal.format()+"</td>");//Agrega la suma del subtotal

					subtotal=0;	//se reinicia la suma
				}
			});
	//	if(isNaN(total)){total=0;}

	//	$("#"+f).text(total.toFixed(2));//Agrega el total al elemento
	//	total = 0;
	//	}
	}
$(".tbln1:first").html($(".tbl1:first").html());
});

function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
$(document).ready(function(){
	$('#nmloader_div',window.parent.document).hide();
});
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
	@media print
	{
		#imprimir,#filtros,#excel,#estatico,#pdf,#email, #botones
		{
			display:none;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
		.brdSup > td{
			border-top: 1px black solid !important;
		}
		.brdInf > td{
			border-bottom: 1px black solid !important;
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
	if(($_GET['tipo']==1) || ($imprimirPoliza)){
		$tit="IMPRESION DE POLIZAS";
	} else {
		$tit="LIBRO DE DIARIO";
	}
?>

<!---------------------------------------------------------------------------->
<div id='imprimible' style="display:none;">

	<table style="border:none; width:100%;background-color:#ffffff;color:black;" >
	<tr>
					<td style="width:50%">
			<?php
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
			?>
			<!--img id='logo_empresa' src='<?php //echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'-->
					</td>
					<td style='width:50%;text-align:right;color:gray;font-size:7px;'>
						<!--b> Fecha de Impresión <br><?php // echo date("d/m/Y H:i:s"); ?></b><br><br-->
					</td>
	</tr>
	<tr><td colspan="2" style='text-align:center;font-size:18px;'><b style='text-align:center;color:black;'><?php echo $empresa;?></b></td></tr>
    <tr><td style="text-align:center;color:#576370;"  colspan="2">
				<b style="font-size:15px;"><?php echo "$tit";?></b><br>
				<?php if(!$imprimirPoliza) { ?>
				Del <b><?php echo $inicio;?></b> Al <b><?php echo $fin;?></b>
				<?php } ?>
				<br><br>
		</td>
	</tr>

	</table>


<!--TERMINA-->

<table border='0' align="center" id='polizas' cellpadding="3" style="width:100%;max-width:1000px;font-size:10px;" class="tbl1">

<!--Separador****|****-->
	<?php
	//print_r($_POST);
	//echo "$datos";
	$ahora="";
	$cont=1;
	while($d = $datos->fetch_object()){

		if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='#fafafa';
		}
		else//Si es impar pinta esto
		{
    		$color='#ffffff';
		}



		if($d->CARGO==0){
			$cargo=0;
		}else{
			$cargo=str_replace(',','',$d->CARGO);
			$SumTcargo += $cargo;
			$cargo='$ '.number_format($cargo,2);
		}
		if($d->ABONO==0){
			$abono=0;
		}else{
			$abono=str_replace(',','',$d->ABONO);
			$SumTabono += $abono;
			$abono='$ '.number_format($abono,2);
		}

		$ahora="$d->id";
		if($antes==$ahora){
		  echo "<tr class='tpNumero' style='height:30px;background-color:$color;text-align:center;font-size:9px;' tipo='numero'>
				<td>$d->NUM_MOVIMIENTO</td>
				<td>$d->CODIGO</td>
				<td>$d->CUENTA</td>
				<td>$d->SEGMENTO</td>
				<td>$d->REFERENCIA_MOV</td>
				<td>$d->CONCEPTO_MOV</td>
				<td style='text-align:right;font-weight:bold;'> $cargo</td>
				<td style='text-align:right;font-weight:bold;'> $abono</td>
				</tr>";
		}else{
		echo "
		<tr style='text-align:right;font-weight:bold;background-color:#f6f7f8;font-size:8px;' tipo='subtotal'></tr>
		<tr style='height:20px;'><td></td><td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td></tr>
		<!--Separador**|**-->
		<tr style='background-color:#edeff1;text-align:left;height:30px;' class='brdSup'>
		<td>No. Poliza</td>
		<td><b>$d->NUM_POL</b></td>
		<td>Concepto</td>
		<td><b>$d->CONCEPTO</b></td>
		<td>Tipo</td>
		<td><b>$d->TIPO_POLIZA</b></td>
		<td>Fecha</td>
		<td><b>$d->FECHA</b></td>
		</tr>";
		if($d->TIPO_POLIZA=="Egresos"){
			echo "
			<tr style='background-color:#D8D8D8;text-align:left;height:30px;' class='brdInf'>
				<td>Forma Pago: <b>$d->formapago</b></td>
				<td>Numero: <b>$d->numero</b></td>
				<td>Banco Origen:<b>$d->bancoorigen</b></td>
				<td>No.Cuenta Bancaria Origen/tarjeta:<b>$d->numerodestino</b></td>
				<td>Beneficiario:<b>$d->beneficiario</b></td>
				<td>RFC:<b>$d->rfc</b></td>
				<td>Banco Destino:<b>$d->bancodestino</b></td>
				<td>No. Cuenta Bancaria Destino/tarjeta:<b>$d->numtarjcuent</b></td>
		</tr>";

		}
		echo "<tr style='background-color:#f6f7f8;font-weight:bold;text-align:center;height:30px;' class='brdInf'>
			<td style='width:8%;'>No. Movimiento</td>
			<td style='width:10%;'>Código Manual</td>
			<td style='width:15%;'>Nombre de la Cuenta</td>
			<td style='width:16%;'>Segmento de Negocio</td>
			<td style='width:12%;'>Referencia del Movimiento</td>
			<td style='width:15%;'>Concepto del Movimiento</td>
			<td style='width:12%;'>Cargo</td>
			<td style='width:12%;'>Abono</td>
		</tr>
		<tr class='tpNumero' style='height:22px;background-color:$color;text-align:center;font-size:9px;' tipo='numero' >
				<td tittle='num'>$d->NUM_MOVIMIENTO</td>
				<td tittle='cod'>$d->CODIGO</td>
				<td tittle='cue'>$d->CUENTA</td>
				<td tittle='seg'>$d->SEGMENTO</td>
				<td tittle='ref'>$d->REFERENCIA_MOV</td>
				<td tittle='con'>$d->CONCEPTO_MOV</td>
				<td tittle='car' style='text-align:right;font-weight:bold;'> $cargo</td>
				<td tittle='abo' style='text-align:right;font-weight:bold;'> $abono</td>
				</tr>";
		}
		$cont++; //Incrementa contador
		$antes="$d->id";
	}
	?>
	<tr style='text-align:right;font-weight:bold;background-color:#f6f7f8;font-size:8px;' tipo='subtotal' class='brdSup'></tr>
	<!--tr style='height:22px;font-size:12px;font-weight:bold;text-align:right;background-color:#edeff1;'><td></td>
	<td></td>
	<td></td>
	<td></td><td>Total: </td><td id='totales' style:"padding:10px;"></td></tr-->
	<tr><td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td></tr>
	<?php if($_GET['tipo']!=1){
								//$SumTcargo=str_replace(',','',$SumTcargo);
								//$SumTabono=str_replace(',','',$SumTabono);

								echo "<tr style='height:22px;'><td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td></tr>
	<tr style='height:22px;font-weight:bold;background-color:#edeff1;'><td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td style='text-align:center'>Total</td>
											<td style='text-align:right'>$ ".number_format($SumTcargo,2)."</td>
											<td style='text-align:right'>$ ".number_format($SumTabono,2)."</td></tr>";
							}
								?>
	<!--tr  id='xx' style='text-align:center;font-weight:bold;background-color:#edeff1;font-size:9px;' tipo='total'><td>Totales:</td><td></td><td></td><td></td><td></td><td></td></tr-->
</table>
</div>

<!---------------------------------------------------------------------------------------->


<div class="container" style="width: 100%">
	<section id="botones">
		<h3 class="repTitulo">
			<?php echo "$tit"; ?><br>
			<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
			<a href="index.php?c=polizasImpresion&f=Inicial&tipo=<?php echo $_GET['tipo']; ?>"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
			<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0">
			<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
			<a href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>
		</h3>
	</section>
	<input type='hidden' value='<?php echo "$tit"; ?>' id='titulo'>
	<section style="margin-top: 3em">
		<div class="row">
			<div class="col-md-4">
	  		</div>
	  		<div class="col-md-4">
				<?php
					$url = explode('/modulos',$_SERVER['REQUEST_URI']);
					if($logo == 'logo.png') $logo = 'x.png';
					$logo = str_replace(' ', '%20', $logo);
				?>
	  		</div>
	  		<div class="col-md-4">
	  		</div>
	  	</div>
	  	<div class="row">
			<div class="col-md-4">
	  		</div>
	  		<div class="col-md-4" style='text-align:center;font-size:18px;'>
				<b style='text-align:center;color:black;'><?php echo $empresa;?></b>
	  		</div>
	  		<div class="col-md-4">
	  		</div>
	  	</div>
	  	<div class="row">
			<div class="col-md-4">

	  		</div>
	  		<div class="col-md-4" style="text-align:center;color:#576370;">
				<label><b style="font-size:15px;"><?php echo "$tit";?></b></label></br>
				<?php if(!$imprimirPoliza) { ?>
				Del <b><?php echo $inicio;?></b> Al <b><?php echo $fin;?></b>
				<?php } ?>
	  		</div>
	  		<div class="col-md-4">

	  		</div>
	  	</div>
		<div class="row">
			<div class="col-md-12 col-sm-12" id="imp_cont">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
						<div class="table-responsive">
							<style type="text/css">
								.table tr{
									background-color: white !important;
								}
								.tpNumero td{
									border: none !important;
								}
								.brdSup{
									border-top: 2px solid black !important;
								}
								.brdInf{
									border-bottom: 2px solid black !important;
								}
							</style>
			  				<table border='0' align="center" cellpadding="3" class="table tbln1" style="font-size:10px;">

							</table>
			  			</div>
			  		</div>
			  	</div>
			</div>
	  	</div>
	</section>
</div>

<!--INICIA TITULO CONGELADO-->

<!--TERMINA-->

<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generar PDF</h4>
            </div>
            <?php
			echo "<form id='formpdf' action='libraries/pdf/examples/polizasImpresion.php' method='post' target='_blank' onsubmit='generar_pdf()'>";
			?>
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
					<input type='hidden' name='nombreDocu' value='<?php echo $tit; ?>'>
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
			<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;">
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
