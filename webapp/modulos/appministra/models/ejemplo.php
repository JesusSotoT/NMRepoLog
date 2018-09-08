<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class EjemploModel extends Connection
{
	public function lista()
	{
		$myQuery = "SELECT* FROM cont_ejercicios";
		$resultados = $this->query($myQuery);
		return $resultados;
	}

    public function cuenta($nc)
    {
        $myQuery = "SELECT description FROM cont_accounts WHERE account_id = $nc";
        $cuenta = $this->query($myQuery);
        $cuenta = $cuenta->fetch_assoc();
        return $cuenta['description'];
    }
}
?>
