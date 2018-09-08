<?php  
//ini_set('display_errors', 1);
session_start();
error_reporting(0);
$idCorte=$_REQUEST["corte"];
//$print = $_REQUEST["print"];
include("controllers/caja.php"); 
$cajaController = new Caja;
$saldos = $cajaController->saldosCorte($idCorte);
$resumenCorte = $cajaController->imprimeCorte($idCorte);
//print_r($resumenCorte['retiros']);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Corte de Caja</title>
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/corte.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>


    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- morris -->
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
	<script>
		$(document).ready(function() {
			window.print();
		});
	</script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Corte <?php echo $idCorte; ?></h3>
        </div>
    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Desde</label>
                            <h4><?php echo $saldos[0]['fechainicio']; ?></h4>
                        </div>
                        <div class="col-sm-3">
                            <label>Hasta</label>
                           	<h4><?php echo $saldos[0]['fechafin']; ?></h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Saldos</h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>Saldo inicial Caja </label>
                                                    <h4>$<?php echo number_format($saldos[0]['saldoinicialcaja'],2); ?></h4>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Monto de Ventas en el Periodo </label>
                                                    <h4>$<?php  echo number_format($saldos[0]['montoventa'],2);?></h4>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Saldo disponible en Caja $</label>
                                                    <h4>$<?php echo number_format(($saldos[0]['saldoinicialcaja']+$saldos[0]['montoventa']),2); ?></h4>	
                                                  
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h4>Depositos/Retiros</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>Retiro de Caja </label>
                                              		<h4>$<?php echo number_format($saldos[0]['retirocaja'],2); ?></h4>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Deposito de Caja</label>
                                                    <h4>$<?php echo number_format($saldos[0]['abonocaja'],2); ?></h4>
                                                </div>
                                                
                                                <div class="col-sm-4">
                                                    
                                                        <label>Usuario: </label>
                                                        <h4><?php echo ' '.$saldos[0]['usuario']; ?></h4>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

</div>



</body>
</html>