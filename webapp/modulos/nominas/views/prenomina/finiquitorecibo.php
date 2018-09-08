<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css"> 
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
</head>
<style>
	@media print{
		#regresar,#fin,#reimprimir,#impri { display:none; }
	}
</style>
<body>
	<?php
	include_once("js/letrasnum.php");
	
	?>
	
	<div class="container well " style="width: 90%">
		<h3 align="center">Calculo Provisional <i class="fa fa-reply fa-lg" id="regresar" onclick="javascript:window.location='index.php?c=Prenomina&f=verfiniquito'" style="cursor: pointer" title="Volver a Proceso"><b></b></i>
	<a type="button" id="impri" title="Imprimir Finiquito para revisión" class="btn btn-info btn btn-sm" href="javascript:window.print();" style="" hidden="true">
	<img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0">
	</a>
</h3>
		<div class="alert alert-info col-md-12" align="justify">
	         
	         <i class="fa fa-info-circle fa-lg"></i> 
	        INFORMACION.<br>
	        Finiquito que entrega la fuente de trabajo ubicada en <?php echo $org->domicilio." denominada <b>".strtoupper($org->nombreorganizacion);?></b>  por conducto de su representante legal Ing Edith Andrea Cantu Rangel a <b><?php echo strtoupper($nombreempleado) ;?> </b> por  <?php echo strtoupper($_REQUEST['causanombre'])." : ".$_REQUEST['fechabaja'];?>
	        
	        <br>Ingreso : <?php echo $_REQUEST['fechaalta']; ?>
	 </div>
		<div id="" class="col-md-12 alert" style="overflow-y: scroll; overflow-x: auto;display: block;";>
			<table  id="tablafiniquito" cellpadding="3" class="table-striped table-over table-bordered" style="border:solid 1px;" height="40px" width="100%">
				<thead>
					<tr style="background:#6E6E6E; color:#F5F7F0" id="">
						<th><b>Concepto</b></th>
						<th><b>Dias</b></th>
						<th><b>Percepciones</b></th>
						<th><b>Deducciones</b></th>
					</tr>
				</thead>
				<tbody class="" id="contenidop">	
					<?php $arrayids = "";
					foreach($_REQUEST['aplica'] as $key =>$val){
						if($val){
							$concepto = explode("/", $_REQUEST['concepto'][$key-1]);
							if($key!=10){
								
								if($key == 8){//prima antiguedad
								/* queda topado el salario a 2 umas verificar si queda o se cambia a SM*/
									$topadoPrima = 2 * $UMA->valor;
									$sueldoParaPrima = $_REQUEST['sueldo'];
									/*Si el sueldo es mayor al tope entonces la prima se ara sobre el topado
									 * no puede ser mas salario del tope*/
									if($_REQUEST['sueldo'] > $topadoPrima){
										$sueldoParaPrima = $topadoPrima;
									}
									$importe =  number_format( $_REQUEST['importedias'][$key-1] * $sueldoParaPrima,2,'.','');
									$totalp += $importe;
									echo "<tr>
										<td>".$concepto[1]."</td>
										<td align='right'>".$_REQUEST['importedias'][$key-1]."</td>
										<td align='right'>".number_format( $importe,2,'.',',')."</td>
										<td align='right'>0.00</td>
										</tr>";
										$arrayids .= "INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,salario,sdi) VALUES (2, ".$_REQUEST['empleado'].", ".$idnomp.", ". $concepto[0].", $importe,0,0,".$_REQUEST['importedias'][$key-1].",0,0,0,'".$_REQUEST['fechabaja']."',DATE_SUB(NOW(), INTERVAL 6 HOUR),".$_REQUEST['sueldo'].",".$_REQUEST['sdi'].");";
									
								}else{
									if($_REQUEST['importedias'][$key-1]>0){//si es negativo va ser descuento de aguinaldo
										$importe =  number_format( $_REQUEST['importedias'][$key-1] * $_REQUEST['sueldo'],2,'.','');
										$totalp += $importe;
										echo "<tr>
										<td>".$concepto[1]."</td>
										<td align='right'>".$_REQUEST['importedias'][$key-1]."</td>
										<td align='right'>".number_format( $importe,2,'.',',')."</td>
										<td align='right'>0.00</td>
										</tr>";
										$arrayids .= "INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,salario,sdi) VALUES (2, ".$_REQUEST['empleado'].", ".$idnomp.", ". $concepto[0].", $importe,0,0,".$_REQUEST['importedias'][$key-1].",0,0,0,'".$_REQUEST['fechabaja']."',DATE_SUB(NOW(), INTERVAL 6 HOUR),".$_REQUEST['sueldo'].",".$_REQUEST['sdi'].");";
											
									}else{
										$importe =  number_format( $_REQUEST['importedias'][$key-1] * $_REQUEST['sueldo'],2,'.','');
										$totalp += $importe;
										echo "<tr>
										<td>".$concepto[1]."</td>
										<td align='right'>".$_REQUEST['importedias'][$key-1]."</td>
										<td align='right'>".number_format( $importe,2,'.',',')."</td>
										<td align='right'>0.00</td>
										</tr>";
										$arrayids .= "INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,salario,sdi) VALUES (2, ".$_REQUEST['empleado'].", ".$idnomp.", ". $concepto[0].", $importe,0,0,".$_REQUEST['importedias'][$key-1].",0,0,0,'".$_REQUEST['fechabaja']."',DATE_SUB(NOW(), INTERVAL 6 HOUR),".$_REQUEST['sueldo'].",".$_REQUEST['sdi'].");";
										
										
									}
									
								}
									
							}//if diferen 10 (isr)
							else{
								$ISRto =  number_format( $val,2,'.','');
								echo "<tr>
										<td>".$concepto[1]."</td>
										<td></td>
										<td align='right'>0.00</td>
										<td align='right'>".number_format( $ISRto,2,'.',',')."</td>
									</tr>";
								$arrayids .= "INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,salario,sdi) VALUES (2, ".$_REQUEST['empleado'].", ".$idnomp.", ". $concepto[0].", $ISRto,0,0,0,0,0,0,'".$_REQUEST['fechabaja']."',DATE_SUB(NOW(), INTERVAL 6 HOUR),".$_REQUEST['sueldo'].",".$_REQUEST['sdi'].");";
									
									
							}

						}	

					}
					?>	
				</tbody>
				<tfoot>
					<tr >
						<td colspan="2"  align="right" ><b>Subtotales</b></td>
						<td align="right" ><b><?php  echo number_format($totalp,2,'.',',');?></b></td>
						<td align="right"><b><?php  echo number_format($ISRto,2,'.',',');?></td>
					</tr>
					<tr style="background:#6E6E6E; color:#F5F7F0">
						<td  colspan="3" align="right"><b>Neto a Pagar</b></td>
						<td align="right"><b><?php echo number_format($totalp - $ISRto,2,'.',','); ?></b></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class=" col-md-12">
			<?php  echo strtoupper(num2letras(number_format($totalp - $ISRto,2,'.','')))."<br>";?>
	         <i class="fa fa-calendar-o fa-lg"></i> 
	       	<?php
	       	if($org->estado){
				echo strtoupper($org->municipio.", ".$org->estado." ");
			}
	       	setlocale(LC_ALL,"es_ES");
			echo strftime("%A %d de %B del %Y")."<br>";
			
			?>
	       	<div align="right">
	       		<table>
	       			<tr>
	       				<td align="center">_______________________________</td>
	       			</tr>
	       			<tr>
	       				<td align="center"><?php echo $conf->representantelegal;?></td>
	       			</tr>
	       			<tr>
	       				<td align="center">Representante Legal de <?php echo $org->nombreorganizacion;?></td>
	       			</tr>
	       		</table>
	       		
	       	</div><br>
	       	<div align="justify" class="alert alert-info">
	       		Yo <b><?php echo strtoupper($nombreempleado) ;?></b> a mi entera satisfacción recibí la cantidad de <b><?php echo number_format($totalp - $ISRto,2,'.',',')." " .strtoupper(num2letras(number_format($totalp - $ISRto,2,'.',''))); ?></b> Por concepto de mi finiquito
	       		por <?php echo strtoupper($_REQUEST['causanombre']);?>. Declaro que durante el tiempo laborado no sufri riesgo o enfermedades de trabajo
	       		y en todo momento me fueron respetados mis derechos laborales y no me reservo accion alguna 
	       		de ejercitar en contra de la empresa denominada <?php echo strtoupper($org->nombreorganizacion);?> o de su representante legal, 
	       		ya sea de caracter mercantil, civil, penal, laboral o de cualquier otra indole. 
	       	</div><br>
	       	<div align="center">
	       		<table>
	       			<tr>
	       				<td align="center">_______________________________</td>
	       			</tr>
	       			<tr>
	       				<td align="center"><b><?php echo strtoupper($nombreempleado) ;?></b></td>
	       			</tr>
	       		</table>
	       	</div>
	   </div>
	   <div align="right">
	   		<button  type="button" class="btn btn-danger" id="fin" data-loading-text="<i class='fa fa-cog fa-spin fa-3x fa-fw margin-bottom'><i/>">Entregar Finiquito</button>
			<button  type="button" class="btn btn-danger" id="reimprimir" style="display: none" onclick="window.print()">Reimprimir recibo</button>

		</div>
	</div>
</body>
<script>
$(document).ready(function() {
	<?php 
	if(isset($_SESSION['finiquito'])){?>
		$("#fin").hide();
		$("#reimprimir").show();
<?php } ?>

	$("#fin").on('click', function() {
	
      	var btnguardar = $(this);
 		btnguardar.button("loading");
 		if(confirm("Si entrega el FINIQUITO se acumularan los conceptos mostrados y se dará de baja al empleado\n\n¿Desea continuar?")){
 			$.post("ajax.php?c=Prenomina&f=entregaFiniquito",{
 				insert: "<?php echo $arrayids;?>",
 				idEmpleado:<?php echo $_REQUEST['empleado'];?>,
 				fechabaja:"<?php echo $_REQUEST['fechabaja'];?>"
 				
 			},function (request){
 				if(request == 1){
 					alert("Acumulado");
 					$("#fin").hide();
					$("#reimprimir").show();
 					window.print();
 				}else{
 					alert("Ocurrio un error intente de nuevo.");
 				}
 				btnguardar.button('reset');
 			});
 		}else{
 			
 			btnguardar.button('reset');
 		}
 		
		 
	});
});
</script>
</html>