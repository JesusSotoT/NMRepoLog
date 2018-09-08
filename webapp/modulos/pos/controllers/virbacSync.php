<?php 

//require __DIR__."/../../../netwarelog/webconfig.php";
//die;

try {

    $pdoVirbac = new PDO(
        'sqlsrv:Server=dbsqlserver.cyv2immv1rf9.us-west-2.rds.amazonaws.com;Database=virbac',
        'sa',
        'sql2017_sepa'
    );

	$getPurchaseLines = "SELECT	ibpuno id_ocompra, ibitno id_producto, 'sestmp' ses_tmp, '1' estatus, '1' activo, ibwhlo almacen, 0.0 cantidad, 0.0 costo, 0.0 impuestos, '0' caracteristica
			FROM	 dbo.mpline 
			WHERE	ibpuno = '7000068';";
    $getPurchaseLines = $pdoVirbac->query($getPurchaseLines);

    //For print columns' mames
	foreach (array_keys($getPurchaseLines->fetch(PDO::FETCH_ASSOC)) as $key => $columnName) {
    	print " {$columnName} \t|";
    }
    print "\n\n";
    //For print rows' data
	foreach ($getPurchaseLines as $key => $getPurchaseLineValue) {
    	for ($i=0; $i < $getPurchaseLines->columnCount() ; $i++) { 
    		print " {$getPurchaseLineValue[$i]} \t|";
    	}
    	print "\n";
    }

    //For get columns' names
    //For bind params on insert
/*    $columns = "";
    $columnsBind = "";
    $paramsBind = array();
    foreach (array_keys($getPurchaseLines->fetch(PDO::FETCH_ASSOC)) as $key => $getPurchaseLineValue) {
    	$paramsBind["$getPurchaseLineValue"] = "1";
    	if($key != 0) {
    		$columns .= ",$getPurchaseLineValue";
    		$columnsBind .= ",:$getPurchaseLineValue";
    	}
    	else {
    		$columns .= "$getPurchaseLineValue";
    		$columnsBind .= ":$getPurchaseLineValue";
    	}
    }*/

    //For bind params on update
/*    $columns = "";
    $columnsBind = "";
    $paramsBind = array();
    foreach (array_keys($getPurchaseLines->fetch(PDO::FETCH_ASSOC)) as $key => $getPurchaseLineValue) {
    	$paramsBind["$getPurchaseLineValue"] = "1";
    	if($key != 0) {
    		$columns .= ",$getPurchaseLineValue = :$getPurchaseLineValue";
    	}
    	else {
    		$columns .= "$getPurchaseLineValue = :$getPurchaseLineValue";
    	}
    }*/



    /*-------------------------------------------------------------------------------------------*/

    $pdoNetwar = new PDO(
        'mysql:host=nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com;dbname=_dbmlog0000012660',
        'nmdevel',
        'nmdevel'
    );

    $setPurchaseLine = "UPDATE app_ocompra_datos SET $columns
						WHERE id_ocompra = :id_ocompra;";

    $setPurchaseLine = $pdoNetwar->prepare($setPurchaseLine);
    foreach ($paramsBind as $key => $value) {
    	$setPurchaseLine->bindParam(":$key", $paramsBind["$key"]);    
    }
    foreach ($getPurchaseLines as $key => $getPurchaseLineValue) {
    	foreach ($getPurchaseLineValue as $key => $value) {
    		$paramsBind["$key"] = $value;
    	}
    	$setPurchaseLine->execute();
    }

    /*-------------------------------------------------------------------------------------------*/
    
/*    $appendPurchaseLine = "INSERT INTO app_ocompra_datos ({$columns}) VALUES ({$columnsBind})";
    $appendPurchaseLine = $pdoNetwar->prepare($appendPurchaseLine);
    foreach ($paramsBind as $key => $value) {
    	$appendPurchaseLine->bindParam(":$key", $paramsBind["$key"]);    
    }
    foreach ($getPurchaseLines as $key => $getPurchaseLineValue) {
    	foreach ($getPurchaseLineValue as $key => $value) {
    		$paramsBind["$key"] = $value;
    	}
    	$appendPurchaseLine->execute();
    }*/











    // Vincular
    //$sentencia->bindParam(':campo1', $campo1);
    //$sentencia->bindParam(':campo2', $campo2);

    

    /*$cajaModel = new CajaModel();
    $cajaModel->connect();

    $cajaModel->queryArray()*/
    

/*    // Preparar
    $sql = 'INSERT INTO productos (campo1, campo2) VALUES (:campo1, :campo2)';
    $sentencia = $pdo->prepare($sql);

    // Vincular
    $sentencia->bindParam(':campo1', $campo1);
    $sentencia->bindParam(':campo2', $campo2);

    // Ejecutar
    // Inserta una fila
    $campo1 = 'c1';
    $campo2 = 'c2';
    $sentencia->execute();

    // Insertar otra fila con diferentes datos
    $campo1 = 'C1';
    $campo2 = 'C2';
    $sentencia->execute();*/

} catch (PDOException $e) {
    echo 'Error!: ' . $e->getMessage() . PHP_EOL;

} finally {
    $pdoVirbac = null;
    $pdoNetwar = null;
}
