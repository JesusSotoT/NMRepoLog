	<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
	
		<script src="../../libraries/export_print/buttons.html5.min.js" type="text/javascript"></script>
		<script src="../../libraries/export_print/dataTables.buttons.min.js" type="text/javascript"></script>
		<script src="../../libraries/export_print/jszip.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">

	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<script src="js/ingresosEdicion.js"></script>
<br>
<!-- <label for="buscar">Buscar:</label> <input type="text" id="buscar" value=""/> -->  <br>
  	<table  cellspacing="2" cellpadding="2" width="100%" class="table table-striped table-bordered" id="table">
  		<thead>
  			<tr style="">
  				<th style="width:30px !important;" class="nmcatalogbusquedatit">No.</th>
	  			<th style="width:100px !important;" class="nmcatalogbusquedatit">Cuenta</th>
	  			<th style="width:50px !important;"  class="nmcatalogbusquedatit">Fecha</th>
	  			<th  style="width:50px !important;" class="nmcatalogbusquedatit">Fecha Aplicacion</th>
	  			<th  style="width:100px !important;" class="nmcatalogbusquedatit">Referencia</th>
	  			<th  style="width:100px !important;" class="nmcatalogbusquedatit">Concepto</th>
	  			<th style="width:70px !important;"  class="nmcatalogbusquedatit">Importe</th>
  				<th style="width:50px !important;"  class="nmcatalogbusquedatit">Proceso</th>
  				<th style="width:50px !important;"  class="nmcatalogbusquedatit">Conciliado</th>
  			</tr>
  		</thead>
  	
  		<tbody>
  			
  	<?php if($depositos){
  				while($in = $depositos->fetch_assoc()){
  					$proceso = $this->proceso($in['proceso']);
  					if($in['formadeposito']==1){$formadeposito='Mismo Banco';}
					elseif($in['formadeposito']==2){$formadeposito='Otros Bancos';}
					elseif($in['formadeposito']==0){$formadeposito='Ninguno';}
  					if($in['conciliado']==1){
  						$conciliado = "Conciliado";
  					}else{
  						$conciliado = "Sin conciliar";
  					}
  					?>
  					<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" >
  						<td style="width:30px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $in['numdoc'];?></a></td>
  						<td style="width:100px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $in['cuenta'];?></a></td>
  						<td style="width:50px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $in['fecha'];?></a></td>
  						<td style="width:50px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $in['fechaaplicacion'];?></a></td>
  						<td style="width:100px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $in['referencia'];?></a></td>
  						<td style="width:100px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $in['concepto'];?></a></td>
  						<td style="width:70px !important;"  class=" nmcatalogbusquedacont_1 " align="right"><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><b style="color:red"><?php echo number_format($in['importe'],2,'.',',');?></b></a></td>
  					  	<td style="width:50px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $proceso;?></a></td>
  					  	<td style="width:50px !important;"  class=" nmcatalogbusquedacont_1 "><a href="javascript: <?php echo $_REQUEST['opc']."(".$in['id'].")";?>" ><?php echo $conciliado;?></a></td>

  					</tr>
  					
  		<?php 	}
		}else{ ?> 
  			<tr>
  				<td colspan="8" align="center">No tiene Depositos</td>
  			</tr>
  	<?php } ?>
  		</tbody>
  	</table>
  	</div>
  	<script>
  		function editar(id){
  			window.location="index.php?c=Ingresos&f=verDeposito&editar="+id;
  		}
  		function eliminar(id){
  			if(confirm("Esta seguro de eliminar el Documento?")){
  				$.post("ajax.php?c=Cheques&f=EliminaDocumentoDeposito",{
  					id:id
  				},function(resp){
  					if(resp==1){
  						$.post("ajax.php?c=Ingresos&f=eliminaIngresoDeDeposito",{
  						id:id
  						},function(respx){
  							if(respx==1){
  								alert("Documento Eliminado");
  								window.location.reload();
  							}else{
  								alert("Error al eliminar documento, intente de nuevo");
  							} 
  						});
  						
  					}else if(resp==2){
		  				alert("No puede eliminar documentos Conciliados");
		  					// }else if(resp==3){
		  						// alert("No puede eliminar documentos Depositados");
		  					// 
  					}else if(resp==4){
  						alert("No puede eliminar documentos de Traspaso");
  					}else{
  						alert("Error al eliminar documento, intente de nuevo");
  					} 
  				})
  			}
  		}
  	</script>