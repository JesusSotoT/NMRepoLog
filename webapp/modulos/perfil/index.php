<?php
	
	//ini_set("display_errors",1); error_reporting(E_ALL);
	if(!isset($_SESSION)) session_start();
    require "../../netwarelog/mvc/libraries/access.php";
    
    //Desbloquea la seguridad del modulo, para que el desarrollador haga pruebas, 
    //cuando termines vuelve a comentar esta variable
    
    $bloqueo = 0;

	require "../../netwarelog/mvc/index.php";

?>
