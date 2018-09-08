<?php 

//Carga la funciones comunes top y footer
require('controllers/common.php');

//Carga el modelo para este controlador
require("models/caja.php");

$cajaModel = new CajaModel();
$cajaModel->connect();

$idCorte = $_GET['corte'];
$corteInfo = $cajaModel->saldosCorte($idCorte);
$cortes = $cajaModel->getCortes();

foreach ($cortes['cortes'] as $key => $value) {
    if( $value['corteZ'] != $idCorte ){
        unset( $cortes['cortes'][$key] );
    }
}

 ?>






<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Corte de Caja</title>
		<link rel="stylesheet" href="">
</head>
<!-- 		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
		<script src="../../libraries/jquery.min.js"></script>
<!--Select 2 -->
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
		<!-- Datepicker -->
		<link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
		<script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
		<script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js"></script>



		<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

		<!-- morris -->
		<link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
		<script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
		<script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>

	 <script>
	 $(document).ready(function() {
				//$('#tableCuts').DataTable();
				//graficar('','');

				$('#cliente').select2();

				$('#desde').datepicker({
						format: "yyyy-mm-dd",
						language: "es"
				});
				$('#hasta').datepicker({
						format: "yyyy-mm-dd",
						language: "es"
				});

				verCorte();
				 
	 });
	 </script>
	 <style>
			 #gridPagosCut_filter { display: none;}
	 </style>
<body>  
<div class="container well col-sm-12">
		<div class="row">
				<div class="col-md-2">
					 <h3>Corte <span id="idCorte"><?php echo $idCorte; ?></span></h3>
					 <input type="hidden" id="idCorte" value="<?php echo $idCorte; ?>">
					 <input type="hidden" id="empleado" value="<?php echo $corteInfo[0]['idEmpleado']; ?>">
				</div>
				<div class="col-sm-5">
						<label>Desde</label>
						<input type="text" id="desdeCut" class="form-control" value="<?php echo $corteInfo[0]['fechainicio']; ?>" readonly>
				</div>
				<div class="col-sm-5">
						<label>Hasta</label>
						<input type="text" id="hastaCut" class="form-control" value="<?php echo $corteInfo[0]['fechafin']; ?>" readonly>
				</div>
		</div>
								
										<!-- <div class="row">
												<div class="col-sm-2">
														<button class="btn btn-primary" data-toggle="modal" data-target="#modalArqueo" onclick="verArqueo();">
																Arqueo
														</button>
												</div>
										</div> -->
		<div class="row">
				<div class="col-sm-12">
						<div style="align:center;">
								<label>Usuario: </label><strong><?php echo ' '.$corteInfo[0]['usuario']; ?></strong>
						</div>
				</div>
		</div>
										
										


				<!-- <div class="panel panel-default" 
						<?php if ( count($cortes['cortes']) == 0 ) {  ?>
								hidden
						<?php } ?>
						>
						<div class="panel-heading" role="tab" id="headingZero">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseZero" aria-expanded="false" aria-controls="collapseZero">
												Cortes parciales
										</a>
								</h4>
						</div>
						<div id="collapseZero" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" 
						>
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridCortesParciales">
																<thead>
																		<tr>
																				<th>ID</th>
																				<th>Fecha Inicio</th>
																				<th>Fecha Fin</th>
																				<th>Saldo Inicial</th>
																				<th>Moto de Ventas</th>
																				<th>Retiro de Caja</th>
																				<th>Abono de Caja</th>
																				<th>Saldo Final</th>
																				<th>Usuario</th>
																				<th>Ver</th>
																				<th></th>
																		</tr>
																</thead>
																<tbody>
																		<?php 
																				foreach ($cortes['cortes'] as $key => $value) {
																						echo '<tr class="rows">';
																						echo '<td>'.$value['idCortecaja'].'</td>';
																						echo '<td>'.$value['fechainicio'].'</td>';
																						echo '<td>'.$value['fechafin'].'</td>';
																						echo '<td>$'.number_format($value['saldoinicialcaja'],2).'</td>';
																						echo '<td>$'.number_format($value['montoventa'],2).'</td>';
																						echo '<td>$'.number_format($value['retirocaja'],2).'</td>';
																						echo '<td>$'.number_format($value['abonocaja'],2).'</td>';
																						echo '<td>$'.number_format($value['saldofinalcaja'],2).'</td>';
																						echo '<td>'.$value['usuario'].'</td>';
																						echo '<td><a class="btn btn-primary active" href="index.php?c=caja&f=verCorte&idCorte='.$value['idCortecaja'].'"><i class="fa fa-list-ul"></i> Ver</a></td>';
																						echo '<td> <a class="btn btn-primary active" onclick="imprimeCorteTicket('.$value['idCortecaja'].');"><i class="fa fa-print"></i></a></td>';
																						echo '</tr>';
																						$final += $value['saldofinalcaja'];
																				}
																		?>
																</tbody>
														</table>
												</div>
										</div>
								</div>
						</div>
				</div> -->


<!-- 										<div class="row">
												<div class="col-sm-12">
														<div class="row">
																<div class="col-sm-12">
																		<div class="panel panel-default">
																				<div class="panel-heading">
																						<h3 class="panel-title">Saldos</h3>
																				</div>
																				<div class="panel-body">
																						<div class="row">
																						<div class="col-sm-6">
																								<div class="row">
																										<div class="col-sm-6">
																												<label>Saldo inicial Caja </label>
																												<input type="text" class="form-control" id="saldo_inicial" value="<?php echo $corteInfo[0]['saldoinicialcaja']; ?>" readonly style="text-align: right;">
																										</div>
																										<div class="col-sm-6">
																												<label>Monto de Ventas en el Periodo  </label>
																												<input type="text" class="form-control" id="monto_ventas" value="<?php  echo $corteInfo[0]['montoventa'];?>" readonly style="text-align: right;">
																										</div>
																								</div>
																						</div>
																						<div class="col-sm-6">
																								<div class="row">
																										<div class="col-sm-6">
																												<label>Saldo Final </label>
																												<input type="text" class="form-control numeros" id="saldo_final" readonly style="text-align: right;">
																										</div>                                                    
																										<div class="col-sm-6">
																												<label>Saldo disponible en Caja </label>
																												<input type="text" class="form-control" id="saldo_disponible" value="<?php echo ($corteInfo[0]['saldoinicialcaja']+$corteInfo[0]['montoventa']); ?>" readonly style="text-align: right;">
																										</div>
																								</div>
																						</div>
																						</div>
																						<div class="row">
																								<div class="col-sm-12">
																										<h4>ABONOS / RETIROS</h4>
																								</div>
																						</div>
																						<div class="row">
																								<div class="col-sm-6">
																										<div class="row">
																												<div class="col-sm-6">
																														<label>Retiros de Caja $</label>
																														<input type="text" class="form-control" id="saldoRetirosCaja" readonly style="text-align: right;">
																												</div>                                                        
																												<div class="col-sm-6">
																														<label>Deposito de Corte Caja </label>
																														<input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;text-align: right;" value="<?php echo $corteInfo[0]['abonocaja']; ?>" readonly >
																												</div>
																										</div>
																								</div>
																								<div class="col-sm-6">
																										<div class="row">
																												<div class="col-sm-6">
																														<label>Retiro de Corte Caja </label>
																														<input type="text" class="form-control numeros" id="retiro_caja" style="background-color: #FFCCDD;text-align: right;" value="<?php echo $corteInfo[0]['retirocaja']; ?>" readonly >
																												</div>
																												<div class="col-sm-6">
																														<label>Saldo Otras Formas de Pago $</label>
																														<input type="text" class="form-control numeros" id="totalof"   readonly style="text-align: right;">
																												</div>
																										</div>
																								</div>
																						</div>
																				</div>
																		</div>
																</div>
														</div>
												</div>
										</div> -->



<div style="width:100%">
                <table style="border-collapse: collapse; border: 1px solid black;">
                   <tr>
                      <th colspan="4"><h4>Saldos</h4></th>
                   </tr>
                   <tr>
                        <td style="width:25%; border: 1px solid black;">
                            <div style="margin-left:8%">
                          <label>Saldo inicial Caja </label>
                          <br>
                          <input type="text" class="form-control" id="saldo_inicial" value="<?php echo $corteInfo[0]['saldoinicialcaja']; ?>" readonly style="text-align: right; width:85%;">
                          <!-- <h4>$'.number_format($saldos[0]['saldoinicialcaja'],2).'</h4> -->
                          </div>
                        </td>
                        <td style="width:25%; border: 1px solid black;">
                            <div style="margin-left:8%">
                            <label>Ventas en Periodo</label>
                            <br>
                            <input type="text" class="form-control" id="monto_ventas" value="<?php  echo $corteInfo[0]['montoventa'];?>" readonly style="text-align: right; width:85%;">
                            <!-- <h4>$'.number_format($saldos[0]['montoventa'],2).'</h4> -->
                            </div>
                        </td>
                        <td style="width:25%;  border: 1px solid black;">
                            <div style="margin-left:8%">
                            <label>Saldo Final</label>
                            <br>
                            <input type="text" class="form-control numeros" id="saldo_final" readonly style="text-align: right; width:85%;">
                            <!-- <h4>$'.number_format($cantidadREt,2).'</h4> -->
                            </div> 
                        </td>
                        <td style="width:25%;  border: 1px solid black;">
                            <div style="margin-left:8%">
                            <label>Saldo disponible Caja</label>
                            <br>
                            <input type="text" class="form-control" id="saldo_disponible" value="<?php echo ($corteInfo[0]['saldoinicialcaja']+$corteInfo[0]['montoventa']); ?>" readonly style="text-align: right; width:85%;">
                           <!--  <h4>$'.number_format($dispoMeRe,2).'</h4> -->
                            </div>
                        </td>
                   </tr>
                    <tr>
                      <th colspan="4"><h4>Depositos/Retiros</h4></th>
                   </tr>
                   <tr>
                        <td style="width:25%; border: 1px solid black;">
                        <div style="margin-left:8%">
                                <label>Retiros de Caja </label>
                                <br>
                                <input type="text" class="form-control" id="saldoRetirosCaja" readonly style="text-align: right; width:85%;">
                                <!-- <h4>$'.number_format($saldos[0]['retirocaja'],2).'</h4> -->
                        </div>
                        </td>
                        <td style="width:25%; border: 1px solid black;">
                        <div style="margin-left:8%">
                                <label>Deposito Corte Caja</label>
                                <br>
                                <input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;text-align: right; width:85%;" value="<?php echo $corteInfo[0]['abonocaja']; ?>" readonly >
                                <!-- <h4>$'.number_format($saldos[0]['abonocaja'],2).'</h4> -->
                        </div>
                        </td>
                        <td style="width:25%; border: 1px solid black;">
                        <div style="margin-left:8%">
                                <label>Retiro Corte Caja</label>
                                <br>
                                <input type="text" class="form-control numeros" id="retiro_caja" style="background-color: #FFCCDD;text-align: right; width:85%;" value="<?php echo $corteInfo[0]['retirocaja']; ?>" readonly >
                                <!-- h4>$'.number_format($saldoFinal,2).'</h4> -->
                        </div>
                        </td>
                        <td style="width:25%; border: 1px solid black;">
                        <div style="margin-left:8%">
                                <label>Otras formas pago</label>
                                <br>
                                <input type="text" class="form-control numeros" id="totalof"   readonly style="text-align: right; width:85%;">
                                <!-- <h4> '.$saldos[0]['usuario'].'</h4> -->
                        </div>
                        </td>      
                   </tr>

                </table>
    
</div>



<div style="width:100%; align:center;"><h3>Pagos</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
		<table class="table table-bordered table-hover" id="gridPagosCut" style="border: 1px solid black;">
				<thead>
						<tr style="background-color:#dcdcdc;">
								<th>ID Venta</th>
								<th>Cliente</th>
								<th>Fecha</th>
								<th>EF</th>
								<th>TC</th>
								<th>TD</th>
								<th>CR</th>
								<th>CH</th>
								<th>TRA</th>
								<th>SPEI</th>
								<th>TR</th>
								<th>NI</th>
								<th>TVales</th>
								<th>Cortesía</th>
								<th>Otros</th>
								<th>Cambio</th>
								<th>Impuestos</th>
								<th>Monto</th>
								<th>Des.</th>
								<th>Importe</th>
								<th>Ingreso (EF-Cambio)</th>
						</tr>
				</thead>
				<tbody>
				</tbody>
		</table>
</div>

<div style="width:100%; align:center;"><h3>Tarjetas</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridTarjetas" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>Tarjeta</th>
							<th>Monto</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>

<div style="width:100%; align:center;"><h3>Productos Vendidos</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridProductosCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>Código</th>
							<th>Producto</th>
							<th>Cantidad</th>
							<th>Precio Unitario</th>
							<th>Descuento</th>
							<th>Impuestos</th>
							<th>Subtotal</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>

<div style="width:100%; align:center;"><h3>Retiros de caja</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridRetirosCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>ID Retiro</th>
							<th>Fecha</th>
							<th>Concepto</th>
							<th>Usuario</th>
							<th>Cantidad</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>

<div style="width:100%; align:center;"><h3>Abonos de Caja</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridAbonosCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>ID Abono</th>
							<th>Fecha</th>
							<th>Concepto</th>
							<th>Usuario</th>
							<th>Cantidad</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>		
			
<div style="width:100%; align:center;"><h3>Cortesías</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridCortesiasCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>ID Venta</th>
							<th>Monto</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>					

<div style="width:100%; align:center;"><h3>Propinas</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridPropinasCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
						<th>Venta</th>
                        <th>Mesero</th>
                        <th>Fecha y hora</th>
                        <th>Efectivo</th>
                        <th>Visa</th>
                        <th>MC</th>
                        <th>AMEX</th>
                        <th>Total</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>					

<div style="width:100%; align:center;"><h3>Devoluciones</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridDevolucionesCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>ID Venta</th>
							<th>Monto</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>	

<div style="width:100%; align:center;"><h3>Cancelaciones</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridCancelacionesCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>ID Venta</th>
							<th>Monto</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>				

<div style="width:100%; align:center;"><h3>Facturas</h3></div>
<div class="col-sm-12" style="width:100%; align:center;"">
	<table class="table table-bordered table-hover" id="gridFacturasCut" style="border: 1px solid black;">
			<thead>
					<tr style="background-color:#dcdcdc;">
							<th>ID Venta</th>
							<th>Monto</th>
					</tr>
			</thead>
			<tbody>
			</tbody>
	</table>
</div>	


		</div>
</div>


		
</body>

<script>
	function verCorte(){
    //caja.mensaje('Procesando...');
    var idCorte = $('#idCorte').val();
    var desde = $('#desdeCut').val();
    var hasta = $('#hastaCut').val();
    var user = $('#empleado').val();
    
    $.ajax({
        url: 'ajax.php?c=caja&f=obtenCorte',
        type: 'post',
        dataType: 'json',
        data: {show: 1,
                desde : desde,
                hasta : hasta,
                user : user
            },
    })
    .done(function(resCor) {
        console.log(resCor);
        //$('#desdeCut').val(resCor.desde);
        //$('#hastaCut').val(resCor.hasta);
        ///Llena la tabla de los pagos
        var cliente = '';
        $('.cutRows').empty();

         var table1 = $('#gridPagosCut tbody');



        //table1.clear().draw();
        var x1 ='';
        var x1x ='';
        var Efectivo =0; 
        var TCredito =0;
        var TDebito =0;
        var CxC =0;
        var Cheque  =0;
        var Trans =0; 
        var SPEI  =0;
        var TRegalo  =0;
        var Ni  =0;
        var cambio =0;
        var Impuestos =0;
        var Monto =0;
        var Importe =0;
        var efectivoCambio2 =0;
        var desss = 0;
        var TVales = 0;
        var Otros = 0;
        var Cortesia = 0;

        $.each(resCor.ventas, function(index, val) {
            if(val.nombre==null){
                cliente = 'Publico General';
            }else{
                cliente = val.nombre;           
            }
            efectivoCambio = (val.Efectivo - val.cambio);
                    x1 = '<tr class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+cliente+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td align="center">$'+val.Efectivo+'</td>'+
                                    '<td align="center">$'+val.TCredito+'</td>'+
                                    '<td align="center">$'+val.TDebito+'</td>'+
                                    '<td align="center">$'+val.CxC+'</td>'+
                                    '<td align="center">$'+val.Cheque+'</td>'+
                                    '<td align="center">$'+val.Trans+'</td>'+
                                    '<td align="center">$'+val.SPEI+'</td>'+
                                    '<td align="center">$'+val.TRegalo+'</td>'+
                                    '<td align="center">$'+val.Ni+'</td>'+
                                    '<td align="center">$'+val.TVales+'</td>'+
                                    '<td align="center">$'+val.Cortesia+'</td>'+
                                    '<td align="center">$'+val.Otros+'</td>'+
                                    '<td align="center">$'+val.cambio+'</td>'+
                                    '<td align="center">$'+val.Impuestos+'</td>'+
                                    '<td align="center">$'+val.Monto+'</td>'+
                                    '<td align="center">$'+val.descuentoGeneral+'</td>'+
                                    '<td align="center">$'+val.Importe+'</td>'+
                                    '<td align="center">$'+efectivoCambio.toFixed(2)+'</td>'+
                            '</tr>';
                    table1.append(x1);

                    Efectivo +=  parseFloat(val.Efectivo);
                    TCredito += parseFloat(val.TCredito);
                    TDebito += parseFloat(val.TDebito);
                    CxC += parseFloat(val.CxC);
                    Cheque += parseFloat(val.Cheque);
                    Trans += parseFloat(val.Trans);
                    SPEI += parseFloat(val.SPEI);
                    TRegalo +=parseFloat(val.TRegalo);
                    Ni += parseFloat(val.Ni);
                    cambio += parseFloat(val.cambio);
                    Impuestos += parseFloat(val.Impuestos);
                    Monto += parseFloat(val.Monto);
                    Importe += parseFloat(val.Importe);
                    efectivoCambio2 += parseFloat(efectivoCambio); 
                    desss +=parseFloat(val.descuentoGeneral); 
                    TVales += parseFloat(val.TVales);
                    Otros += parseFloat(val.Otros);
                    Cortesia += parseFloat(val.Cortesia);

        }); 
                            x1x = '<tr style="background-color:#dcdcdc; class="cutRows"><td colspan="3"></td><td>EF</td><td>TC</td><td>TD</td><td>CR</td><td>CH</td><td>TRA</td><td>SPEI</td><td>TR</td><td>NI</td><td>TVales</td><td>Cortesía</td><td>Otros</td><td>Cambio</td><td>Impuestos</td><td>Monto</td><td>Des.</td><td>Importe</td><td>Ingresos</td></tr>';  
                            x1x += '<tr style="backgroud:white;" class="cutRows">'+
                                    '<td colspan="3">Totales</td>'+
                                    
                                    '<td align="center">$'+Efectivo.toFixed(2)+'</td>'+
                                    '<td align="center">$'+TCredito.toFixed(2)+'</td>'+
                                    '<td align="center">$'+TDebito.toFixed(2)+'</td>'+
                                    '<td align="center">$'+CxC.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Cheque.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Trans.toFixed(2)+'</td>'+
                                    '<td align="center">$'+SPEI.toFixed(2)+'</td>'+
                                    '<td align="center">$'+TRegalo.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Ni.toFixed(2)+'</td>'+
                                    '<td align="center">$'+TVales.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Cortesia.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Otros.toFixed(2)+'</td>'+
                                    '<td align="center">$'+cambio.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Impuestos.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Monto.toFixed(2)+'</td>'+
                                    '<td align="center">$'+desss.toFixed(2)+'</td>'+
                                    '<td align="center" style="background-color: #ffccdd;">$'+Importe.toFixed(2)+'</td>'+
                                    '<td align="center" style="background-color: #A9F5A9;">$'+efectivoCambio2.toFixed(2)+'</td>'+
                                    '</tr>';
                table1.append(x1x);


/**/
        var table10 = $('#gridTarjetas tbody');
//        table10.clear().draw();
        var x10 ='';
        var x10x ='';
        var cantidad10 = 0;

        $.each(resCor.tarjetas, function(index, val) {
                    x10 = '<tr class="cutRows">'+
                                    '<td align="center">'+val.tarjeta+'</td>'+
                                    '<td align="center">'+parseFloat(val.total).toFixed(2)+'</td>'+
                                    '</tr>';
                    table10.append(x10) 

                    cantidad10 += parseFloat(parseFloat(val.total).toFixed(2));
        }); 

          x10x = '<tr style="background:white;" class="cutRows">'+
                                    '<td >Totales</td>'+
                                    '<td align="center" style="background-color: #ffccdd;">$'+cantidad10.toFixed(2)+'</td>'+
                                    '</tr>';
                 table10.append(x10x);


                 /**/


        ///Llena la tabla de los productos
        var table2 = $('#gridProductosCut tbody');

        var x2 ='';
        var x2x = '';
        var Cantidad = 0;
        var Descuento = 0;
        var Impuestos2 = 0;
        var Subtot = 0;
        $.each(resCor.productos, function(index, val) {
                    x2 = '<tr class="cutRows">'+
                                    '<td>'+val.codigo+'</td>'+
                                    '<td>'+val.nombre+'</td>'+
                                    '<td align="center">'+val.Cantidad+'</td>'+
                                    '<td align="center">$'+val.preciounitario+'</td>'+
                                    '<td align="center">$'+val.Descuento+'</td>'+
                                    '<td align="center">$'+val.Impuestos+'</td>'+
                                    '<td align="center">$'+val.Subtot+'</td>'+
                                    '</tr>';
                    table2.append(x2);

                    Cantidad += parseFloat(val.Cantidad);
                    Descuento += parseFloat(val.Descuento);
                    Impuestos2 += parseFloat(val.Impuestos2);
                    Subtot += parseFloat(val.Subtot);

        });                 
                            x2x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+  
                                                
                                    '<td align="center">$'+Descuento.toFixed(2)+'</td>'+
                                    '<td align="center">$'+Impuestos.toFixed(2)+'</td>'+
                                    '<td align="center" style="background-color: #ffccdd;">$'+Subtot.toFixed(2)+'</td>'+
                                    '</tr>';
            table2.append(x2x);             
        ///Llena la Tabla de retiros
        var table3 = $('#gridRetirosCut tbody');
        var x3 ='';
        var x3x ='';
        var cantidad3 = 0;
        $.each(resCor.retiros, function(index, val) {
                    x3 = '<tr idRetiro="'+val.id+'" class="cutRows">'+
                                    '<td align="center">'+val.id+'</td>'+
                                    '<td align="center">'+val.fecha+'</td>'+
                                    '<td align="center">'+val.concepto+'</td>'+
                                    '<td align="center">'+val.usuario+'</td>'+
                                    '<td align="center">$'+val.cantidad+'</td>'+
                            '</tr>';
                    table3.append(x3); 

                    cantidad3 += parseFloat(val.cantidad);
        }); 

          x3x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad3.toFixed(2)+'</td>'+
                                    '</tr>';
                 table3.append(x3x);




        ///Llena la Tabla de abonos
        var table4 = $('#gridAbonosCut tbody');
        var x4 ='';
        var x4x ='';
        var cantidad4 = 0;
        var abonoEfectivo = 0;
        $.each(resCor.abonos, function(index, val) {
                    x4 = '<tr idRetiro="'+val.id+'" class="cutRows">'+
                                    '<td>'+val.id+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>'+val.concepto+'</td>'+
                                    '<td>'+val.usuario+'</td>'+
                                    '<td>$'+val.cantidad+'</td>'+
                                    '</tr>';
                    table4.append(x4); 
                    if(val.id_forma_pago == "1")
                        abonoEfectivo += parseFloat(val.cantidad);

                    cantidad4 += parseFloat(val.cantidad);
        }); 

          x4x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #A9F5A9;">$'+cantidad4.toFixed(2)+'</td>'+
                                    '</tr>';
                table4.append(x4x);




        ///Llena la Tabla de propinas
        var table5 = $('#gridPropinasCut tbody');
        var x5 ='';
        var x5x ='';
        var cantidad5 = 0;
        $.each(resCor.propinas, function(index, val) {
                    x5 = '<tr idRetiro="'+val.id_venta+'" class="cutRows">'+
                                    '<td>'+val.id_venta+'</td>'+
                                    '<td>'+val.nombre+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>'+val.efectivo+'</td>'+
                                    '<td>'+val.visa+'</td>'+
                                    '<td>'+val.mc+'</td>'+
                                    '<td>'+val.amex+'</td>'+
                                    '<td>'+val.total+'</td>'+
                                    '</tr>';
                    table5.append(x5); 

                    cantidad5 += parseFloat(val.total);
        }); 

          x5x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="7">Totales</td>'+
           
                                    '<td style="background-color: #A9F5A9;">$'+cantidad5.toFixed(2)+'</td>'+
                                    '</tr>';
                 table5.append(x5x);


        ///Llena la Tabla de devoluciones
        var table9 = $('#gridCortesiasCut tbody');
        var x9 ='';
        var x9x ='';
        var cantidad9 = 0;
        $.each(resCor.ventas, function(index, val) {
            if(val.Cortesia > 0){
                    x9 = '<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.Cortesia+'</td>'+
                                    '</tr>';
                    table9.append(x9); 

                    cantidad9 += parseFloat(val.Cortesia);
                }
        }); 

          x9x = '<tr style="background:white;" class="cutRows">'+
                                    '<td >Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad9.toFixed(2)+'</td>'+
                                    '</tr>';
                table9.append(x9x);

        ///Llena la Tabla de devoluciones
        var table6 = $('#gridDevolucionesCut tbody');
        var x6 ='';
        var x6x ='';
        var cantidad6 = 0;

        $.each(resCor.devoluciones, function(index, val) {
                    x6 = '<tr idRetiro="'+val.id_venta+'" class="cutRows">'+
                                    '<td>'+val.id_ov+'</td>'+
                                    '<td>'+val.total+'</td>'+
                                    '</tr>';
                    table6.append(x6); 

                    cantidad6 += parseFloat(val.total);
        }); 

          x6x = '<tr style="background:white;" class="cutRows">'+
                                    '<td >Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad6.toFixed(2)+'</td>'+
                                    '</tr>';
                 table6.append(x6x);


        ///Llena la Tabla de cancelaciones
        var table7 = $('#gridCancelacionesCut tbody');
        var x7 ='';
        var x7x ='';
        var cantidad7 = 0;
        $.each(resCor.cancelaciones, function(index, val) {
                    x7 = '<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.monto+'</td>'+
                                    '</tr>';
                    table7.append(x7); 

                    cantidad7 += parseFloat(val.monto);
        }); 

          x7x = '<tr style="background:white;" class="cutRows">'+
                                    '<td >Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad7.toFixed(2)+'</td>'+
                                    '</tr>';
                table7.append(x7x);



        ///Llena la Tabla de facturas
        var table8 = $('#gridFacturasCut tbody');
        var x8 ='';
        var x8x ='';
        var cantidad8 = 0;
        $.each(resCor.facturas, function(index, val) {
                    x8 = '<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.monto+'</td>'+
                                    '</tr>';
                    table8.append(x8); 

                    cantidad8 += parseFloat(val.monto);
        }); 

          x8x = '<tr style="background:white;" class="cutRows">'+
                                    '<td >Totales</td>'+
           
                                    '<td style="background-color: #A9F5A9;">$'+cantidad8.toFixed(2)+'</td>'+
                                    '</tr>';
                 table8.append(x8x);

        //$('#saldo_inicial').val(resCor.montoInical);
        //$('#monto_ventas').val( $('#monto_ventas').val - (monto_ventas + Cortesia) );
        var saldoDisponibleMret = parseFloat($('#saldo_disponible').val()) - parseFloat(cantidad3.toFixed(2)) + parseFloat(cantidad4.toFixed(2));
        //alert(saldoDisponibleMret);
        //$('#saldo_disponible').val(saldoDisponibleMret.toFixed(2));
        
        //$('#saldo_disponible').val(resCor.monto_ventas.toFixed(2));
        $('#saldoRetirosCaja').val(cantidad3);

        var x  = parseFloat(saldoDisponibleMret) - parseFloat($('#retiro_caja').val());


        var saldoFinal = parseFloat(x) + parseFloat($('#deposito_caja').val());
        
        /* invertdos
        $('#saldo_final').val(saldoFinal.toFixed(2));
        $('#totalof').val(resCor.totalof.toFixed(2));
        */
$('#monto_ventas').val( parseFloat($('#monto_ventas').val())  );
        $('#totalof').val(saldoFinal);
        $('#saldo_final').val( parseFloat($('#saldo_inicial').val()) + parseFloat($('#monto_ventas').val()) + parseFloat(cantidad4) - parseFloat(cantidad3) );
        $('#saldo_disponible').val(parseFloat( $('#saldo_inicial').val()) + efectivoCambio2 + parseFloat(abonoEfectivo) - parseFloat(cantidad3) );

//$('#monto_ventas').val( parseFloat(Cortesia) );
	window.print();
	
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    

}
</script>
</html>