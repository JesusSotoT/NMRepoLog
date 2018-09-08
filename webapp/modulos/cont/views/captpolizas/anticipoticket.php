<!DOCTYPE >
<head>
	<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
	<script src="../../libraries/export_print/buttons.html5.min.js" type="text/javascript"></script>
	<script src="../../libraries/export_print/dataTables.buttons.min.js" type="text/javascript"></script>
	<script src="../../libraries/export_print/jszip.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<script src="../cont/js/ticketanticipo.js"></script>
	<link rel="stylesheet" href="css/ticketanticipo.css">

</head>
<body>
<div class="container well" style="width: 95%">
	<h3 class="text-primary" style="font-size: 25px" align="center">Comprobacion de Ticket de Anticipo</h3>
<br>
	
<div id="" class="col-md-12 alert alert-info" style="overflow-y: scroll; overflow-x: auto;display: block;";>
    <div align="right">  
   	 <button  id="" onclick="window.location.reload()" class="btn btn-primary">Actualizar listado</button>
	</div><br> 
  	<table  cellpadding="3" class="table-striped table-over table-bordered" style="border:solid 1px;" width="100%" id="table">
		<thead>
			<tr style="border:solid 0px;background:#6E6E6E; color:#F5F7F0" id="">
				<th>ID</th>
				<th>Num poliza</th>
				<th>Concepto Poliza</th>
				<th>Usuario Deudor</th>
				<th>Categoria</th>
				<th>Ticket</th>
				<th>Concepto ticket</th>
				<th>Importe de ticket</th>
				<th style="width: 10px">Anexar Factura</th>
				<th>XML</th>
				<td>Estatus</td>
			</tr>
		</thead>
		<tbody>
			<?php
			while($li = $listaanticipos->fetch_object()){?>
				<tr >
					<td><?php echo $li->idpoliza; ?></td>
					<td><?php echo $li->numpol; ?></td>
					<td><?php echo ($li->conceptopoliza); ?></td>
					<td><?php echo strtoupper($li->usuario); ?></td>
					<td><?php echo $li->nombrecategoria; ?></td>
					<td><?php $ticket = str_replace("webapp/modulos/",'', $li->ticket1); ?>
						<img class="zoom ima" src="<?php echo $ticket;?>" />
					</td>
					<td><?php echo $li->concepto; ?></td>
					<td align="right"><?php echo number_format($li->importe,2,'.',','); ?></td>
					<td style="width: 10px">
					
					<a href='javascript:facturas(<?php echo $li->idpoliza;?>,<?php echo $li->idnodedu;?>)' style='font-weight:bold;color:black;' title='Ver Factura' id='FacturasButton'>
						<img src='images/clip.png' style='vertical-align: middle;' width='40px' title='Anexar Factura'></a>
					
					</td>
					
					<td><a href="../cont/xmls/facturas/<?php echo $li->idpoliza."/".$li->xml; ?>"></a><?php echo $li->xml;?></td>
					<td>
						<?php
						if(!$li->xml){
							echo "Sin Facturar.";
						}else {
							echo "Facturado.";
						}
						
						?>
					</td>
				</tr>
				
			<?php 
			}
			?>
		</tbody>
	</table>
</div>
</div>
<div class="modal fade" id="Anexa" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h8 class="modal-title" id=""><b>Anexar Facturas</b></h8>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
		
      <div class="modal-body"><input type="hidden" id="nodeducible" name="nodeducible"/>
			<input type='file' name='factura' id='factura' ><input type='hidden' name='idpoli' id='idpoli' value=''>
			<span id='verif' style='color:green;display:none;'>Verificando...</span>
		
      </div>
      <table class="" id='listaFacturas'>
	  </table>
      <div class="modal-footer">
        <button type="submit" id="xmlantici" class="btn btn-primary">Anexar</button>
      </div>
      </form>
    </div>
  </div>
</div>
</html>