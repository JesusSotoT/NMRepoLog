<?php
//require("models/connection.php"); // funciones mySQL 
require_once 'connection_sqli_manual.php'; // funciones mySQLi

	class TipoCambioModel extends Connection
	{

		function __construct()
		{
			$this->connect();
		}


		function busca_tipo_cambio_hoy($moneda,$tipo,$hoy)
		{
			$myQuery = "SELECT fecha,tipo_cambio FROM cont_tipo_cambio WHERE fecha = '$hoy' AND moneda = $moneda AND tipo='$tipo'";
			$cambio = $this->query($myQuery);
			$cambio = $cambio->fetch_assoc();
			return $cambio;
		}

		function ultimo_registro($moneda)
		{
			$myQuery = "SELECT fecha FROM cont_tipo_cambio WHERE moneda = $moneda ORDER BY fecha DESC LIMIT 1";
			$ultima_fecha = $this->query($myQuery);
			$ultima_fecha = $ultima_fecha->fetch_assoc();
			return $ultima_fecha['fecha'];
		}

		function cambios_faltantes($moneda,$ultimo_cambio)
		{
			$myQuery = "SELECT  id,fecha, 'Contable' AS tipo, valor, moneda FROM nmdev_common.tipo_cambio_oficial WHERE fecha > '$ultimo_cambio' AND moneda = $moneda ORDER BY fecha";
			$conexion = mysqli_connect('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com','nmcommon','common123','nmdev_common');
			$registros = $conexion->query($myQuery);
			$conexion->close();
			return $registros;
		}

		function insertar_faltantes($myQuery)
		{
			if($this->multi_query($myQuery)) return 1;
				else return 0;
		}
		
	}
?>
