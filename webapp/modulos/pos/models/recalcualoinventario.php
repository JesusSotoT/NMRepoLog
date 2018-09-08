<?php 

//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
if(!isset($_REQUEST["netwarstore"])) require("models/connection_sqli_manual.php"); // funciones mySQLi
else require("../webapp/modulos/pos/models/connection_sqli_manual.php"); // funciones mySQLi

class RecalculoInventarioModels extends Connection 
{

}