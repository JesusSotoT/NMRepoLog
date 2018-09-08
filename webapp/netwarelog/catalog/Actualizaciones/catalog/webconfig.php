<?php
	
	include("support.php");

	$tipobd		= "mysql";						//Tipo de servidor de base de datos
												//mysql --> Servidor de MySQL 
												//mssql --> Servidor de Microsoft SQL Server

	$servidor   	= "localhost:8889"; 	//servidor:puerto --- 3306
	
	$usuariobd  	= "root";				//Usuario para conectarse a MySQL
	
	$clavebd    	= "mysql";				//Clave del usuario anterior
	
	$bd				= "netwaremonitor";		//Nombre de la base de datos: netwaremonitor cambielo por el nombre real.
	
	$instalarbase	= "1";					//Si es la primera vez que abrirá catalog en este proyecto
											//Dejelo con el 1, posterior a la apertura puede cambiarlo a 0.
											//Esta señal crea las tablas necesarias en la base automáticamente.
											//NOTA: El usuario debe tener permisos para crear tablas.
											
	$link_regreso	= "../";				//Cuando hagan click en el botón de regresar
											//el sistema buscará este link.
											
	$link_gestor    = "catalog/gestor.php"; //Este es el link que se grabara en la base de datos para llamar al gestor.
	
?>