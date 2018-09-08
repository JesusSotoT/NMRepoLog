<?php
	$servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
	$usuariobd = "nmdevel";
    $clavebd = "nmdevel";
    $strInstanciaG = "verdupato34";
    
    $objConG = mysqli_connect($servidor, $usuariobd, $clavebd, "netwarstore");

    if (!$objConG)
    	echo "No se pudo conectar a la DB <br><br>";
   	else
   		echo "Connection. Great success! <br><br>";

   	$strSqlG = 
    "SELECT 
        a.limitdate as limit_date, 
        b.appname as app_name
    FROM 
        appclient a 
    INNER JOIN 
        appdescrip b ON a.idapp = b.idapp
    WHERE 
        a.idcustomer IN (SELECT id FROM customer WHERE instancia = '" . $strInstanciaG . "');";
    
    echo $strSqlG . "<br><br>";

    $rstWebconfigG = mysqli_query($objConG, $strSqlG);
    
    if (!$rstWebconfigG)
    	echo "No se pudo realizar la consulta. <br><br>"; 
    else
    	echo "Query. Great success! <br><br>";

    $dashboard_string = "<b>IMPORTANTE</b><p>La licencia de los siguientes productos ha expirado</p><ul>";
    $dashboard_string .= "</ul><p>Por favor, contacte a su representante de ventas.</p>";
       
    //echo "dashboard_string: <br>" . $dashboard_string;

    /*
    if ($result = $objConG->query("SELECT DATABASE()")) {
    	$row = $result->fetch_row();
    	printf("Default database is %s.\n", $row[0]);
    	$result->close();
	}*/

	$objConG->select_db("_dbmlog0000005863");

	/*if ($result = $objConG->query("SELECT DATABASE()")) {
    	$row = $result->fetch_row();
    	printf("Default database is %s.\n", $row[0]);
    	$result->close();
	}*/

	$strSqlG = "INSERT INTO dashboard_comunica (instancia, idempleado, msg, fechainicio, fechafin) ";
	$strSqlG .= " VALUES ('-1', -1, 'Oli', '2016-07-20', '2016-07-30')";

	if ($objConG->query($strSqlG) === TRUE) {
    	echo "Record added. Great success!";
	} else {
    	echo "Error: " . $strSqlG . "<br>" . $objConG->error;
	}


?>