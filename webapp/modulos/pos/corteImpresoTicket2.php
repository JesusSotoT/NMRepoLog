

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

    foreach ($resumenCorte['propinas'] as $key => $value) {
        $resumenCorte['efe'] = $resumenCorte['efe'] + $value['efectivo'];
        $resumenCorte['visa'] = $resumenCorte['visa'] + $value['visa'];
        $resumenCorte['amex'] = $resumenCorte['amex'] + $value['amex'];
        $resumenCorte['mc'] = $resumenCorte['mc'] + $value['mc'];
        $resumenCorte['total'] = $resumenCorte['total'] + $value['mc'] + $value['amex'] + $value['visa'] + $value['efectivo'];
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Corte de Caja</title>
	<!-- <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/imprimir_bootstrap.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>


    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>


	<script>
		$(document).ready(function() {
			window.print();
		});
	</script>
    <style>
        div, span {
            font-family: Tahoma,'Trebuchet MS',Arial;
            font-size: 11px;
            font-weight: bold;
            padding: 0px;
            margin: 0px;
        }
        div {

        }
        @media print {

        }

    </style>
</head>

<body>

<div class="container-fluid">
<div class="row">
  <div class="col-md-4">
      
  </div>
  <div class="col-md-2">
        <!-- <div class="row">
            <div class="col-sm-12" style="text-align:center;">
                <?php 
                $organizacion = $cajaController->datosorganizacion();
            $imagen='../../netwarelog/archivos/1/organizaciones/'.$organizacion[0]['logoempresa'];
            $imagesize=getimagesize($imagen);
            $porcentaje=0;
            if($imagesize[0]>200 && $imagesize[1]>90){
                if($imagesize[0]>$imagesize[1]){
                    $porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
                    $imagesize[0]=200;
                    $imagesize[1]=(($porcentaje*200)/100);
                }else{
                    $porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
                    $imagesize[0]=200;
                    $imagesize[1]=(($porcentaje*200)/100);  
                }
            }
            //"../../netwarelog/archivos/1/organizaciones/'.$cliente[0]->logoempresa.'"
            $src="";
            if($imagen!="" && file_exists($imagen))
                $src='<img src="'.$imagen.'" style="width:'.$imagesize[0].'px;height:'.$imagesize[1].'px;display:block;margin:0 auto 0 auto;"/>';
            echo $src;
        ?>
            </div>
        </div> -->
        <div class="row">
            <div class="col-sm-12" style="text-align:center;">
                <h3> <?php echo ' '.$saldos[0]['organizacion']; ?> </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" style="text-align:center;">
                <h3>CORTE DE CAJA<h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" style="text-align:center;">
                <h4>Usuario: <?php echo ' '.$saldos[0]['usuario']; ?></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <span> Caja: </span>
            </div>
            <div class="col-sm-6" >
                <?php echo ' '.$saldos[0]['sucursal']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <span> Del: </span>
            </div>
            <div class="col-sm-6" >
                <?php echo $saldos[0]['fechainicio']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <span> Al: </span>
            </div>
            <div class="col-sm-6" >
                <?php echo $saldos[0]['fechafin']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <span> ID corte: </span>
            </div>
            <div class="col-sm-6" >
                <?php echo $idCorte; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h3>CORTE</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span> + Saldo Inicial</span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php  echo number_format($saldos[0]['saldoinicialcaja'],2);?>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-sm-6">
                <span> + Total ventas</span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php  echo number_format($saldos[0]['montoventa'],2);?>
            </div>
        </div> -->
        <?php 
        $totalVentas = 0.0;
        $efectivo = 0.0;
        $tarjetaCredito = 0.0;
        $tarjetaDebito = 0.0;
        $cxc = 0.0;
        $cheque = 0.0;
        $transferencia = 0.0;
        $spei = 0.0;
        $tarjetaRegalo = 0.0;
        $ni = 0.0;
        $tarjetaVales = 0.0;
        $otros = 0.0;
        $cortesia = 0.0;

        foreach ($resumenCorte['ventas'] as $key => $value) { 
            $efectivo += $value['Efectivo'] - $value['cambio'];
            $tarjetaCredito += $value['TCredito'];
            $tarjetaDebito += $value['TDebito'];
            $cxc += $value['CxC'];
            $cheque += $value['Cheque'];
            $transferencia += $value['Trans'];
            $spei += $value['SPEI'];
            $tarjetaRegalo += $value['TRegalo'];
            $ni += $value['Ni'];
            $tarjetaVales += $value['TVales'];
            $otros += $value['Otros'];
            $cortesia += $value['Cortesia'];
        }

        $totalVentas += $efectivo + $tarjetaCredito + $tarjetaDebito + $cxc + $cheque + $transferencia + $spei + $tarjetaRegalo + $ni + $tarjetaVales + $otros /*+ $cortesia*/; 
        ?> 

            <div class="row">
                <div class="col-sm-6">
                    <span> * Ventas  </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($totalVentas, 2) ?>
                </div>
            </div>

            <?php if( number_format($efectivo, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + Efectivo </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($efectivo, 2) ?>
                </div>
            </div>
            <?php }if( number_format($tarjetaCredito, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + T. Crédito </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($tarjetaCredito, 2) ?>
                </div>
            </div>
            <?php }if( number_format($tarjetaDebito, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + T. Débito </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($tarjetaDebito, 2) ?>
                </div>
            </div>
            <?php }if( number_format($cxc, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + CxC </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($cxc, 2) ?>
                </div>
            </div>
            <?php }if( number_format($cheque, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + Cheque </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($cheque, 2) ?>
                </div>
            </div>
            <?php }if( number_format($transferencia, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + Transf. </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($transferencia, 2) ?>
                </div>
            </div>
            <?php }if( number_format($spei, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + SPEI </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($spei, 2) ?>
                </div>
            </div>
            <?php }if( number_format($tarjetaRegalo, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + T. Regalo </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($tarjetaRegalo, 2) ?>
                </div>
            </div>
            <?php }if( number_format($cortesia, 2) !== "0.00" ) { ?>
            <!-- <div class="row">
                <div class="col-sm-6">
                    <span> + Cortesía </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($cortesia, 2) ?>
                </div>
            </div> -->
            <?php }if( number_format($otros, 2) !== "0.00" ) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <span> + Otros </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($otros, 2) ?>
                </div>
            </div>
            <?php } ?>
        <div class="row" hidden>
            <div class="col-sm-6">
                <span> + Depósito </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php echo number_format($saldos[0]['abonocaja'],2); ?>
            </div>
        </div>
        <div class="row" hidden>
            <div class="col-sm-6">
                <span> - Retiro </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php echo number_format(abs( $saldos[0]['retirocaja'] ),2);?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <hr>
            </div>
            <div class="col-sm-5">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span> = Total Corte </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                    <?php 
                        $cantidadAbono = 0.0;
                        $cantidadAbonoEfectivo = 0.0;
                        foreach ($resumenCorte['abonos'] as $key => $value) {
                            $cantidadAbono += $value['cantidad'];

                            if($value['id_forma_pago'] == "1")
                                $cantidadAbonoEfectivo += $value['cantidad'];
                        }
                    ?>
                    <?php 
                        $cantidadRetiro = 0.0;
                        foreach ($resumenCorte['retiros'] as $key => $value) {
                            
                            $cantidadRetiro += $value['cantidad'];
                        }
                    ?>

                <?php 
                    $dispoMeRe = abs($saldos[0]['saldoinicialcaja']) + abs($totalVentas) + $cantidadAbono - $cantidadRetiro/*+  abs($saldos[0]['abonocaja']) - abs($saldos[0]['retirocaja'])*/;
                    echo number_format($dispoMeRe,2); 
                ?>
            </div>
        </div>
        <div class="row" hidden>
            <div class="col-sm-6">
                <span> = Total Efectivo </span>
            </div>
            <div class="col-sm-1"><br>$</div>
            <div class="col-sm-4" style="text-align:right;">
                <br>
                <?php 
                    $dispoMeRe = abs($saldos[0]['saldoinicialcaja']) + abs($efectivo)/* +  abs($saldos[0]['abonocaja']) - abs($saldos[0]['retirocaja'])*/;
                    echo number_format($dispoMeRe,2); 
                ?>
            </div>
        </div>



        <div class="row">
            <div class="col-sm-12">
                <h3>ABONOS Y RETIROS DE CAJA</h3>
            </div>
        </div>
<!--         <?php 
            $cantidadAbono = 0.0;
            foreach ($resumenCorte['abonos'] as $key => $value) {
                $cantidadAbono += $value['cantidad'];
            }
        ?> -->
        <div class="row">
            <div class="col-sm-6">
                <span> + Abonos</span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php echo number_format($cantidadAbonoEfectivo,2); ?>
            </div>
        </div>
<!--         <?php 
            $cantidadRetiro = 0.0;
            foreach ($resumenCorte['retiros'] as $key => $value) {
                
                $cantidadRetiro += $value['cantidad'];
            }
        ?> -->
        <div class="row">
            <div class="col-sm-6">
                <span> - Retiros</span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php echo number_format($cantidadRetiro,2); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <hr>
            </div>
            <div class="col-sm-5">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span> = T. Efectivo</span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php 
                    $saldoFinal = $dispoMeRe + $cantidadAbonoEfectivo - $cantidadRetiro;
                    echo number_format($saldoFinal,2);
                ?>
            </div>
        </div>

        <div class="row" >
            <div class="col-sm-6">
                <span> * Retiro </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?php echo number_format(abs( $saldos[0]['retirocaja'] ),2);?>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-sm-12">
                <h3>FORMA DE PAGO</h3>
            </div>
        </div>
        
           
        <div class="row">
            <div class="col-sm-6">
                <hr>
            </div>
            <div class="col-sm-5">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span> Total </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($totalVentas, 2) ?>
            </div>
        </div> -->


        <div class="row">
            <div class="col-sm-12">
                <h3>TARJETAS</h3>
            </div>
        </div>
        <?php 
        $totalTarjetas = 0.0;
        foreach ($resumenCorte['tarjetas'] as $key => $value) { ?>

            <div class="row">
                <div class="col-sm-6">
                    <span> <?= $value['tarjeta'] ?> </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($value['total'], 2) ?>
                </div>
            </div>
        <?php 
            $totalTarjetas += (float) $value['total'];
        } ?>    
        <div class="row">
            <div class="col-sm-6">
                <hr>
            </div>
            <div class="col-sm-5">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span> Total </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($totalTarjetas, 2) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h3>CANCELACIONES</h3>
            </div>
        </div>
        <div class="row">
                <div class="col-sm-6">
                    <span >  ID venta </span>
                </div>
                <div class="col-sm-5" style="text-align:right;">
                    Monto
                </div>
            </div>
        <?php 
        $totalCancelaciones = 0.0;
        foreach ($resumenCorte['cancelaciones'] as $key => $value) { ?>

            <div class="row">
                <div class="col-sm-6">
                    <span>  <?= $value['idVenta'] ?> </span>
                </div>
                <div class="col-sm-1">$</div>
                <div class="col-sm-4" style="text-align:right;">
                    <?= number_format($value['monto'], 2) ?>
                </div>
            </div>
        <?php 
            $totalCancelaciones += (float) $value['monto'];
        } ?>    
        <div class="row">
            <div class="col-sm-6">
                <hr>
            </div>
            <div class="col-sm-5">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span> Total </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($totalCancelaciones, 2) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h3>PROPINAS</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span>  Efectivo </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($resumenCorte['efe'], 2) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span>  Amex </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($resumenCorte['amex'], 2) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span>  Visa </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($resumenCorte['visa'], 2) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span>  Master Card </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($resumenCorte['mc'], 2) ?>
            </div>
        </div>
         <div class="row">
            <div class="col-sm-6">
                <hr>
            </div>
            <div class="col-sm-5">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <span> Total </span>
            </div>
            <div class="col-sm-1">$</div>
            <div class="col-sm-4" style="text-align:right;">
                <?= number_format($resumenCorte['total'], 2) ?>
            </div>
        </div>
        <div class="row" style="">
            <div class="col-sm-12" style="text-align: center; height: 70px; position: relative;">
                <span style="    position: absolute; bottom: 0;right: 0; left: 0;"> _________________________ </span>
            </div>
            <div class="col-sm-12" style="text-align: center">
                <span> <?php echo ' '.$saldos[0]['usuario']; ?> </span>
            </div>
        </div>
        


  </div>
  <div class="col-md-4">
      
  </div>
</div>
</div>

</body>
</html>