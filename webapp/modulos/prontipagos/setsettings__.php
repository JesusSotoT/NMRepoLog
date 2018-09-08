<?php
    include ('cProntipagos.php');
    include('../../netwarelog/webconfig.php');
    ini_set("display_errors",1);

    $a = new Prontipagos();


    $codeTransaction = '00';
    $statusTransaction = 'status';
    $codeDescription = "Description";
    $dateTransaction = "2015/12/12 00:00:00";
    $transactionID = 100000;
    $amount = 2.0;
    $sku = "SKU";


    echo $a->prueba();
    echo "<br>";

    echo "Usuario: $usuariobd <br>" ;
    echo "Clave DB" . $clavebd;

    var_dump($a);


    echo $a->transaccionLocal($codeTransaction, $statusTransaction, $codeDescription,
                  $dateTransaction, $transactionID, $amount, $sku, $conexionbd);


    echo "Aquí";

?>
