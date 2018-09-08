<!-- jquery-ui -->
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- Select con buscador  -->
		<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- jquery.scrollTo.js -->
		<script type="text/javascript" src="js/jquery.scrollTo.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>

	<!-- ** Sistema -->
		<script type="text/javascript" src="js/comandas/comandas.js"></script>
		<script type="text/javascript" src="js/comandas/comandera.js"></script>
		<script type="text/javascript" src="js/pedidos/pedidos.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
			});
		</script>

<div class="row" style="margin: 0">
	<div class="row" style="margin: 0; margin-top: 10px;"><button class="btn btn-info" onclick="send_imp();" style="float:right; margin-right: 10px;">Imprimir seleccionados</button></div>
	<div class="row" style="margin-top: 5px;">
		<div class ="col-sm-12">
			<div class ="col-sm-12">
				<table id="table_mesas" class="table table-striped table-bordered" cellspacing="0">
					<thead>
						<tr >
							<th style="text-align: center;"><strong>ID</strong></th>
							<th style="text-align: center;"><strong>Nombre de la Mesa</strong></th>
							<th style="text-align: center;"><strong>Departamento</strong></th>
							<th style="text-align: center;"><strong>Mesero</strong></th>
							<th style="text-align: center;"><strong>Sucursal</strong></th>
							<th style="text-align: center;"><strong>QR</strong></th>
							<th style="text-align: center;"><strong>Impresión</strong></th>
							<th style="text-align: center;"><button class="btn btn-default" onclick="selAll();">Selecciona Todos</button></th>
						</tr>
					</thead>
					<tbody><?php
						foreach ($mesas as $key => $value) { ?>
							<?php if($value['tipo'] == 0 && $value['id_tipo_mesa'] != 7 && $value['id_tipo_mesa'] != 8 && $value['id_tipo_mesa'] != 9) { ?>
							<tr id="tr_mesa_<?php echo $value['mesa'] ?>">
								<td style="text-align: center;" id="mesa_<?php echo $value['mesa'];  ?>"><?php echo $value['mesa'] ?></td>
								<td style="text-align: center;" id="nombre_mesa_<?php echo $value['mesa'];  ?>"><?php echo $value['nombre_mesa'] ?></td>
								<td style="text-align: center;" id="departamento_<?php echo $value['mesa'];  ?>"><?php echo $value['nombre'] ?></td>
								<td style="text-align: center;" id="mesero_<?php echo $value['mesa'];  ?>"><?php echo $value['mesero'] ?></td>
								<td style="text-align: center;" id="sucursal_<?php echo $value['mesa'];  ?>"><?php echo $value['sucursal'] ?></td>
								<td style="text-align: center;" id="qr_<?php echo $value['mesa'];  ?>"><img src="<?php echo $value['qr'] ?>"></td>
								<td style="text-align: center;" align="center">
									<button 
						        		id="btn_imp_<?php echo $value['mesa'] ?>" 
						        		data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						        		onclick="imprimir_qr(<?php echo $value['mesa']?>)"
						        		class="btn btn-primary">
						        		<i class="fa fa-print"></i>
						        	</button>
								</td>
								<td style="text-align: center;" align="center">
									<input class="checkPro" type="checkbox" name="checked" value="<?php echo $value['mesa']?>" id="check_<?php echo $value['mesa']?>">
								</td>
							</tr><?php
							}
						} ?>
					</tbody>
				</table>
				<script>comandas.convertir_dataTable({id:'table_mesas'})</script>
			</div>
		</div>	
	</div>
</div> 
	
<script>
	
function selAll(){

	var oTable = $('#table_mesas').dataTable();
    var allPages = oTable.fnGetNodes();

    if ($('.checkPro',allPages).is(":checked")) {
    	$('.checkPro',allPages).prop('checked', false);
    }else{
    	$('.checkPro',allPages).prop('checked', true);
    }

}

function imprimir_qr(mesa){
	console.log("====> imprimir_qr");
	console.log(mesa);
	$.ajax({
		data:{mesas: mesa},
   		url:'ajax.php?c=configuracion&f=imprimir_qr',
   		type: 'POST',
   		dataType:'html',
   		success: function(resp){
   			console.log("imprimir_qr");
   			console.log(resp);

   			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
   			ventana.document.write(resp);
   			$(ventana).ready(function() {
   				console.log("print");
   				setTimeout(function(){
   					ventana.print();  
   				}, 500);
				 		
			});	
   		}
   	});

}

function send_imp(){
		var checados = 0;
		var oTable = $('#table_mesas').dataTable();
	    var allPages = oTable.fnGetNodes();
		

		$('input:checked', allPages).each(function(){
            checados ++;
        });

        setTimeout(function () {
        	if(checados<1){
				alert("Debe seleccionar al menos una mesa.");
				return 0;
			}

			cadena='';
			$('input:checked', allPages).each(function(){
	            cadena+=$(this,allPages).val()+',';
	        });
			console.log(cadena);
			$.ajax({
				data:{mesas: cadena},
		   		url:'ajax.php?c=configuracion&f=imprimir_qr',
		   		type: 'POST',
		   		dataType:'html',
		   		success: function(resp){
		   			console.log("imprimir_qr");
		   			console.log(resp);

		   			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
		   			ventana.document.write(resp);
		   			$(ventana).ready(function() {
		   				console.log("print");
		   				setTimeout(function(){
		   					ventana.print();  
		   				}, 500);
						 		
					});	
		   		}
		   	});
        }, 1000);
		
}
</script>