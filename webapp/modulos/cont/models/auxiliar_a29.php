<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

	class auxiliar_a29Model extends Connection
	{
		
		function ejercicios()
		{
			$myQuery = "SELECT Id, NombreEjercicio FROM cont_ejercicios";
			$ejercicios = $this->query($myQuery);
			return $ejercicios;
		}

		function nombreEjercicio($n)
		{
			$myQuery = "SELECT NombreEjercicio FROM cont_ejercicios WHERE Id=$n";
			$ejercicio = $this->query($myQuery);
			return $ejercicio;
		}

		function proveedores()
		{
			$myQuery = "SELECT idPrv, razon_social FROM mrp_proveedor ORDER BY idPrv";
			$proveedores = $this->query($myQuery);
			return $proveedores;
		}

		function obtenerDatos($inicio,$fin,$periodoAcreditamiento,$proveedores,$pinicial,$pfinal)
		{
			if($periodoAcreditamiento)
			{
				$i = explode('/',$inicio);
				$where="r.periodoAcreditamiento BETWEEN ".$i[1]." AND $fin AND r.ejercicioAcreditamiento = ".$i[0];
			}
			else
			{
				$where="p.fecha BETWEEN '$inicio' AND '$fin'";
			}

			if($proveedores == 'algunos')
			{
				$where .= " AND r.idProveedor BETWEEN $pinicial AND $pfinal";
			}

			$myQuery = "SELECT  
						mp.razon_social,
						mp.rfc,
						(SELECT tipotercero FROM cont_tipo_tercero WHERE id=mp.idtipotercero) AS tipo_tercero,
						(SELECT tipoOperacion FROM cont_tipo_operacion WHERE id=mp.idtipoperacion) AS tipo_operacion,
						(SELECT titulo FROM cont_tipos_poliza WHERE id = p.idtipopoliza) AS tipo_poliza,
						p.id,
						p.numpol,
						p.idperiodo,
						p.idejercicio,
						p.fecha,
						(SELECT NombreEjercicio FROM cont_ejercicios WHERE Id = p.idejercicio) AS ejercicio,
						r.periodoAcreditamiento,
						r.idProveedor,
						(SELECT valor FROM cont_tasaPrv WHERE id = r.tasa) AS Tasa,
						(SELECT tasa FROM cont_tasaPrv WHERE id = r.tasa) AS TasaNom,
						r.importeBase,
						r.ivaPagadoNoAcreditable,
						r.ivaRetenido

						FROM cont_rel_pol_prov r
						INNER JOIN cont_polizas p ON r.idPoliza = p.id 
						INNER JOIN mrp_proveedor mp ON mp.idPrv = r.idProveedor
						WHERE $where  AND r.activo=1 AND r.aplica=1 AND p.activo=1 AND p.idtipopoliza != 1
						ORDER BY mp.razon_social, p.id ";
			$exercise = $this->query($myQuery);
			//echo $myQuery;
			
			return $exercise;
		}
		function empresa()
		{
				$myQuery = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
				$empresa = $this->query($myQuery);	
				$empresa = $empresa->fetch_array();
				return $empresa['nombreorganizacion'];
		}

		function logo()
		{
			$myQuery = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
			$logo = $this->query($myQuery);
			$logo = $logo->fetch_assoc();
			return $logo['logoempresa'];
		}
	}
?>
