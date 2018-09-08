<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
		<script type="text/javascript" src='js/retencionreporte.js'></script>
		<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<script language='javascript' src='../cont/js/pdfmail.js'></script>

</head>
<body>
<div style="width:100% background: #F2F2F2;" align="" class="" >
	<div class="panel panel-default" >
		<div class="panel-heading"  style="height: 46px;font-family: Courier;" align="center"><b style="font-size:25px;">Esquema de Retenciones e Informacion de Pagos</b></div> 
	</div>
	<div class="panel-body" >
		<div class="row" >
			<br><br><div align="center" class="col-md-3"></div>
			<div class="col-md-6" align="center">
				<form method="post" action="index.php?c=Cheques&f=verReporte" id="formfecha">
				<fieldset >
					<legend align="left">Fechas</legend>
					Del
					<input type="date" id="fechainicio" name="fechainicio" class="" style="width:150px;color: black " value="<?php echo @$_REQUEST['fechainicio'];?>"> 
					Al
					<input type="date" id="fechafin" name="fechafin" class="" style="width:150px;color: black " value="<?php echo @$_REQUEST['fechafin'];?>">
					
					<button type="button" class="btn btn-primary" id="load" style="center"   data-loading-text="Consultando<i class='fa fa-refresh fa-spin '></i>">Generar Reporte</button>

				</fieldset>	
				</form>		
			</div>
		</div>
		<br><br>
		
		<table class="table table-striped table-bordered" id="table" >
  		<thead>
  			<tr>
  				<th  style="width:10px !important;">No.(ID)</th>
  				<th  style="width:30px !important;">Folio Fiscal</th>
  				<th  style="width:30px !important;">Complemento</th>
  				<th  style="width:20px !important;">Fecha</th>
  				<th  style="width:30px !important;">Periodo Inicial</th>
  				<th  style="width:30px !important;">Periodo Final</th>
  				<th  style="width:30px !important;">Ejercicio</th>
  				<th  style="width:30px !important;">Beneficiario</th>
  				<th  style="width:30px !important;">Monto Operaciones</th>
  				<th  style="width:30px !important;">Gravado</th>
  				<th  style="width:30px !important;">Exento</th>
  				<th  style="width:30px !important;">Total Retenciones</th>
  				<th  style="width:30px !important;">Impuestos</th>
  				<th  style="width:30px !important;">ID Seguimiento</th>
  				<th  style="width:30px !important;">Accion</th>
  			</tr>
  		</thead>
  		<tbody>
  			<?php if($retenciones){
  				while($in = $retenciones->fetch_assoc()){
  				$meses = array('1' => 'Enero','2' => 'Febrero','3' => 'Marzo','4' => 'Abril','5' => 'Mayo','6' => 'Junio','7' => 'Julio','8' => 'Agosto','9' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre');
				if($in['cancelada']==1){ $cancel = "style='text-decoration: line-through' "; }else{ $cancel="";}	
  			?>
  					<tr <?php echo $cancel;?> class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" >
  						<td  style="width:10px !important;"><?php echo $in['idRetencion'];?></td>
  						<td  style="width:30px !important;"><?php echo $in['UUID'];?></td>
  						<td  style="width:30px !important;"><?php echo $in['nombre'];?></td>
  						<td  style="width:20px !important;"><?php echo $in['fecha'];?></td>
  						<td  style="width:30px !important;"><?php echo $meses[$in['mesInicial']];?></td>
  						<td  style="width:30px !important;"><?php echo $meses[$in['mesFinal']];?></td>
  						<td  style="width:30px !important;"><?php echo $in['ejercicio'];?></td>
  						<td  style="width:30px !important;"><?php echo $in['razon_social'];?></td>
  						<td  style="width:30px !important;"><?php echo number_format($in['totalOperaciones'],6,'.',',');?></td>
  						<td  style="width:30px !important;"><?php echo number_format($in['totalGravado'],6,'.',',');?></td>
  						<td  style="width:30px !important;"><?php echo number_format($in['totalExento'],6,'.',',');?></td>
  						<td  style="width:30px !important;"><?php echo number_format($in['totalRetenciones'],6,'.',',');?></td>
  						<td  style="width:30px !important;">	
  							<img   style="width: 26px;height: 26px" class="" src="images/re3.png" id="<?php echo $in['idRetencion'];?>" onclick="abreImpuestos(<?php echo $in['idRetencion'];?>)"  >			
						</td>
  						<td  style="width:30px !important;"><?php echo $in['trackID'];?></td>
  						<?php if($in['timbrado']==0){?>
  							<td>
  							<button onclick="volverTimbrar(<?php echo $in['trackID'];?>,<?php echo $in['idRetencion'];?>,<?php echo $in['idPrv'];?>)" type="button" class="btn btn-primary" id="timbre<?php echo $in['trackID'];?>" style="center"   data-loading-text="<i class='fa fa-refresh fa-spin '></i>">Timbrar</button>
							</td>
						<?php }else{ ?>
  						<td id="timbrenormal<?php echo $in['idComprobante'];?>"  style="width:30px !important;">
  						<?php
  						$url = $this->path('../cont/')."xmls/facturas/temporales/".$in['nombreXML'];
  						?>
  							<a href="<?php echo $url ?>" target='_blank'>Ver</a>/
  							<a href="javascript:mailBanco('<?php echo $in['nombreXML'];?>');"> <img src="../../../webapp/netwarelog/repolog/img/email.png"  
							   title ="Enviar XML por correo electrónico" border="0"> 
							</a>/
							<a href="javascript:cancelarFactura('<?php echo $in['UUID'];?>',<?php echo $in['idComprobante'];?>);"> 
								<img style="width: 22px;height: 18px;"  title="Cancelar Retencion" src="images/cancelar2.png">
							</a>
  						</td>
  						<!-- SI pongo el datatable con este td oculto causa problemas no lo pondre si despues se pide ver q hacer -->
  						<td id="cargador<?php echo $in['idComprobante'];?>" style="display: none;width:30px !important;">
  							<b>Cancelando</b><i class='fa fa-refresh fa-spin '></i>
  						</td>
  						
  						<?php } ?>
  					</tr>
  					
  		<?php 	}
		}else{ ?> 
  			<tr>
  				<td colspan="15" align="center">No tiene Retenciones</td>
  			</tr>
  	<?php } ?>
  		</tbody>
  		</table>
		
		
	</div>
</div>
<div id="impuestos" >
	
</div>


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
		