<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
		<!-- <script src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script> -->		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
		<script language='javascript' src='../cont/js/pdfmail.js'></script>
		<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>


		<!-- <script src="js/buttons.print.min.js"></script> -->
		
	</head>
	<script>
		$(document).ready(function() {
				//$('#global,#global2').DataTable();    
				// $('#madre').DataTable( {
        // dom: 'Bfrtip',
        // buttons: [
            // 'print'
        // ]
    // } );
			} );
			function generaexcel()
			{
				$("#padre").css("background","#D8D8D8");
				$("#ico").hide();
				$().redirect('../cont/views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
				$("#ico").show();
				$("#padre").css("background","#848484");
			}
			function antesmail(){
				$("#ico").hide();
				$("#padre").css("background","#D8D8D8");
				mail();
				$("#ico").show();
				$("#padre").css("background","#848484");
			}
			function antespdf(){
				$("#padre").css("background","#D8D8D8");
				$("#ico").hide();
				pdf();
				$("#ico").show();
				$("#padre").css("background","#848484");
			}
			
	</script>
	<style>
	@media print
{
	#global,#global2
	{
		
	}
	#ico,#filtros,#excel
	{
		display:none;
	}
	#padre{
		background:#D8D8D8;
	}
	
}

</style>

	<body>
	<?php
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
	?>
		
		<div class="iconos" id="ico">
                <table class="bh" align="right" border="0" >
                    <tr>            
                        <td width=16 align=right>
                            <a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
                        </td>
                        <td width=16  align=right>
							<a href="index.php?c=Flujo&f=verflujo"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" 
								title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
						</td>                        
						<td width=16 align=right>
							<a href="javascript:antesmail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png"  
							   title ="Enviar reporte por correo electrónico" border="0"> 
							</a>
						</td>
						<td width=16 align=right>
							<a href="javascript:antespdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif"  
							   title ="Generar reporte en PDF" border="0"> 
							</a>
						</td>
						<td width=16 align=right>
							<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
						</td>																				
                    </tr>
                </table>
            </div>	<br><br>
		
<div id='imprimible'>
		
<div style="background: #848484;" align="center" class="container well" id="padre">	
	<div class="panel panel-default" >
	
		<div class="panel-heading"  style="height: 153px">
			<h3>Reporte Flujo de Efectivo</h3>
			<?php
				echo "<b>Todas las cuentas</b>";
			?><br>
			<b style="font-family: Courier">Del <?php
			echo $_REQUEST['fechainicio'];
			?>
			Al <?php
			echo $_REQUEST['fechafin'];
			?></b><br>
			<?php
				if($detalle==0){
					$nivel="Global";
				}else{
					$nivel="Detalle";
				}
			?>
			<b style="font-size: 16px">Nivel: <?php echo $nivel; ?></b><br>
		<?php
				if($_REQUEST['proyectados']==1){?>
					/<b style="font-family: cursive;font-size: 11px;">Incluidos No Depositados del Periodo</b> /
		<?php	}
		if($_REQUEST['cobrados']==0){?>
					/<b style="font-family: cursive;font-size: 11px;">Incluidos Cheques No Cobrados</b> /
		<?php	}
			?>
		</div> 
		<br>	
		</th>
		
		</thead>
			<tbody>
		<tr><td>		
	
		<?php 
			if($detalle==1){ ?>
		<!-- <table class="" width="60%" cellspacing="0" cellpadding="3" border="0">
			<thead>
				<tr>
					<th>Fecha</th>
					<th>Clasificador</th>
					<th>Concepto</th>
					<th>Importe</th>
				</tr>
			</thead>
			<tbody>
				
				<tr>
					<td colspan="4"><b>Documentos Ingresos</b></td>
				</tr>
				<?php 
				foreach($ingresosArray as $index){ 
					foreach($index as $val){?>
				<tr>
					<td><?php echo $val['fecha']; ?></td>
					<td><?php echo $val['clasificador']; ?></td>
					<td><?php echo $val['concepto']; ?></td>
					<td><?php echo $val['importe']; ?></td>
				</tr>
					
				<?php }
					 
				} ?>
				<tr>
					<td colspan="4"><b>Documentos Egresos</b></td>
				</tr>
				<?php 
				foreach($egresosArray as $index){ 
					foreach($index as $val){?>
				<tr>
					<td><?php echo $val['fecha']; ?></td>
					<td><?php echo $val['clasificador']; ?></td>
					<td><?php echo $val['concepto']; ?></td>
					<td><?php echo $val['importe']; ?></td>
				</tr>
					
				<?php }
					 
				} ?>
				
			</tbody>
		</table> -->
	<?php } else{?>
		
		<div class="panel-heading" style = "background: #848484;"></div>

			<div class="panel-body" >
		
		<table  width="100%" class="table table-striped table-bordered" >
			<thead>
				<th></th>
				<?php $maximo = count($infocuenta);
				foreach ($infocuenta as $key){?>
				<th><b style=""><?php echo $key['nombre']." (".$key['numcuenta'].")";?></b></th>
				<?php } ?>
				<th style="color: black;" align="right"><b>GLOBAL</b></th>
			</thead>
			<tbody>
				<tr>
					<td style="color: black;font-size: 16px" align="left"><b>SALDO INICIAL</b></td>
				<?php $saldocuentas = 0; $saldoxcuenta = array();
				//echo print_r($infocuenta);
					foreach ($infocuenta as $key){
						if($key['saldoinicial']<0){
							$color = "color: red";
						}else{
							$color = "color:black";
						}
						 $saldocuentas+=$key['saldoinicial']; $saldoxcuenta[] = $key['saldoinicial']; ?>
					<td style="<?php echo $color;?>" align="right"><?php echo number_format($key['saldoinicial'],2,'.',',');?></td>
					<?php }
					if($saldocuentas<0){
						$color2 = "color: red";
					}else{
						$color2 = "color:black";
					} ?>
					<td align="right"><b style="<?php echo $color2; ?>"><?php echo number_format($saldocuentas,2,'.',',');?></b></td>
				</tr>
				
		
				<tr>
					<?php $maximo = count($infocuenta);?>
					<td align="left" colspan="<?php echo $maximo+1; ?>"><b style="">SubClaficador Ingresos</b></td>
				</tr>

			
				<?php 
				
				$saldoglobalingresos = 0; $saldoxingresos = array();
				foreach($ingresosArray as $cuenta=>$vale){$saldoxcuentaingresos = 0;$cont=0; ?>
				<tr>
					<td><?php echo $vale['clasificador']; ?></td>
					<?php
					
						
					foreach($infocuenta as $valor){
						if($valor['idbancaria']!=$vale[$valor['idbancaria']]['idbancaria']){  $saldoxingresos[$cont]+= 0; ?>
							
						<td align="right">0.00</td>
					
					<?php }else{  $saldoxingresos[$cont]+= $vale[$valor['idbancaria']]['importe'];
						$saldoxcuentaingresos 	+= $vale[$valor['idbancaria']]['importe'];
						$saldoglobalingresos 	+= $vale[$valor['idbancaria']]['importe'];
					?>
						
							<td align="right">
							<?php echo number_format($vale[$valor['idbancaria']]['importe'],2,'.',','); ?>
							</td>
									
					<?php }$cont++;
					}
					if($saldoxcuentaingresos<0){
						$color3 = "color:red";
					}else{
						$color3 = "color:black";
					}?>
					
					<td align="right"><b style="<?php echo $color3; ?>">
						<?php 
						
						@$saldoxingresos[$cont]+= $saldoxcuentaingresos;
						echo number_format($saldoxcuentaingresos,2,'.',',');?></b>
					</td>	
				</tr>	
				<?php	
				}?>
				<tr style="">
					
					<td align="left"><b style="">Total de Ingresos</b></td>
					<?php foreach($saldoxingresos as $val){ 
					if($val<0){
						$color3 = "color:red";
					}else{
						$color3 = "color:black";
					}?>
					<td align="right" style="<?php echo $color3;?>"><b><?php echo number_format($val,2,'.',','); ?></b></td>
					
					<?php } ?>
				</tr>
			
		
				<tr>
					<?php $maximo = count($infocuenta);?>
					<td align="left" colspan="<?php echo $maximo+1; ?>"><b style="">SubClaficador Egresos</b></td>
				</tr>

				<?php 
				
				$saldoglobalegre = 0; $saldoxegresos = array();
				
				foreach($egresosArray as $cuenta=>$vale){$saldoxcuentaegre = 0; $cont = 0;?>
				<tr>
					<td><?php echo $vale['clasificador']; ?></td>
					<?php
					
						
					foreach($infocuenta as $valor){
						
						
						
						if($valor['idbancaria']!=$vale[$valor['idbancaria']]['idbancaria']){ $saldoxegresos[$cont] += 0; ?>
							
					<td align="right">0.00</td>
					
					<?php }else{  $saldoxegresos[$cont] += $vale[$valor['idbancaria']]['importe'];
						$saldoxcuentaegre 	+= $vale[$valor['idbancaria']]['importe'];
						$saldoglobalegre 	+= $vale[$valor['idbancaria']]['importe'];
					?>
						
							<td align="right">
							<?php echo number_format($vale[$valor['idbancaria']]['importe'],2,'.',','); ?>
							</td>
									
					<?php } $cont++;
					}if($saldoxcuentaegre<0){
						$color4 = "color:red";
					}else{
						$color4 = "color:black";
					}?>
					
					<td align="right"><b style="<?php echo $color4; ?>">
						<?php @$saldoxegresos[$cont] += $saldoxcuentaegre;
						 echo number_format($saldoxcuentaegre,2,'.',',');?></b>
					</td>	
				</tr>	
				<?php	
				}?>
				<tr style="">
					
					<td align="left"><b style="">Total de Egresos</b></td>
					<?php foreach($saldoxegresos as $val){ ?>
					<td align="right"><b><?php echo number_format($val,2,'.',','); ?></b></td>
					<?php } ?>
				</tr>
				<tr>
					<td style="color: black;font-size: 16px" align="left"><b>SALDO FINAL</b></td>
					<?php
					foreach ($saldoxcuenta as $key=>$val){
					if(($val + $saldoxingresos[$key] - $saldoxegresos[$key])<0 ){
						$color5 = "color:red";
					}else{
						$color5 = "color:black";
					}?>
					<td align="right"><b style="<?php echo $color5; ?>"><?php echo number_format($val + $saldoxingresos[$key] - $saldoxegresos[$key],2,'.',',');?></b></td>
					<?php }
					if( ($saldocuentas+$saldoglobalingresos-$saldoglobalegre)<0 ){
						$color6 = "color:red";
					}else{
						$color6 = "color:black";
					}?>
					<td align="right"><b style="<?php echo $color6; ?>"><?php echo number_format($saldocuentas+$saldoglobalingresos-$saldoglobalegre,2,'.',',');?></b></td>
				</tr>
			</tbody>
		</table>
		</div><br>
		
	<?php }
	 ?>
	
	</div>
</div>
</div> <!-- div imprimible -->
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
								<input type='hidden' name='nombreDocu' value='Detalle Saldo Conciliacion'>
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
	</body>
</html>