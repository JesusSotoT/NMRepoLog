<?php

	$a = array();
	
	$a[]="valor1";
	$a[]="valor2";
	$a[]="valor3";
	$a[]="valor4";
	$a[]="valor5";
	$a[]="valor6";
	
	foreach($a as $key => $valor){
		echo $valor."<br>";
	}
	
	
	$conexion = mysql_connect("127.0.0.1:3306","root","mysql");	
	echo $conexion;
	
	$bd=mysql_select_db("nw",$conexion);		
	echo "BASE DE DATOS:".$bd; 

?>