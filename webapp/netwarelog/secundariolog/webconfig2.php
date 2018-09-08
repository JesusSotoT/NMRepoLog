<?php
	
	include("support.php");

	//Tipo de servidor de base de datos
	//mysql --> Servidor de MySQL 
	//mssql --> Servidor de Microsoft SQL Server
	$tipobd		= "mysql";						
	
	//servidor:puerto --- 3306
	//$servidor   	= "10.0.0.90"; 
	//$servidor   	= "netwaremonitor.dyndns.org";	 	
	$servidor   	= "localhost";	 	
	
	
	
	//Usuario para conectarse a MySQL
	$usuariobd  	= "root";				

    //Clave del usuario anterior
	$clavebd    	= "mysql";
	//$clavebd    	= "nmragus25262325";
	
	//Nombre de la base de datos: netwaremonitor cambielo por el nombre real.
	$bd		= "scm";		
	
	//INSTALACION DE LA BASE
	$instalarbase	= "1";					
	//Si es la primera vez que abrirá catalog en este proyecto
	//Dejelo con el 1, posterior a la apertura puede cambiarlo a 0.
	//Esta señal crea las tablas necesarias en la base automáticamente.
	//NOTA: El usuario debe tener permisos para crear tablas.

	//LINK DE REGRESO
	$link_regreso	= "../";				
	//Cuando hagan click en el botón de regresar
	//el sistema buscará este link.
	
	//LINK DEL GESTOR
	$link_gestor    = "catalog/gestor.php"; 
	//Este es el link que se grabara en la base de datos para llamar al gestor.
	
?>