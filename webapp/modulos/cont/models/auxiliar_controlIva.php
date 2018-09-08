<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

	class auxiliar_controlIvaModel extends Connection
	{
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

		function flujo()
		{
			$myQuery = "SELECT account_id,manual_code, description FROM cont_accounts
						WHERE account_code LIKE CONCAT((SELECT a.account_code FROM cont_accounts a RIGHT JOIN cont_config c ON c.CuentaFlujoEfectivo = a.account_id), '%') AND affectable=1 AND removed=0";
			$flujo = $this->query($myQuery);
			return $flujo;
		}

		function tasas()
		{
			$myQuery = "SELECT tasa,valor FROM cont_tasas";
			$flujo = $this->query($myQuery);
			return $flujo;
		}

		function obtenerDatos($inicio,$fin,$periodoAcreditamiento,$proveedores,$pinicial,$pfinal,$flujo,$noAplica,$tasas,$ejercicio)
		{
			if($periodoAcreditamiento)
			{
				$where="r.periodoAcreditamiento BETWEEN $inicio AND $fin";
				if($ejercicio)
				{
					$where .= " AND r.ejercicioAcreditamiento = $ejercicio";
				}
			}
			else
			{
				$where="p.fecha BETWEEN '$inicio' AND '$fin'";
				
			}

			if($proveedores == 'algunos')
			{
				$where .= " AND r.idProveedor BETWEEN $pinicial AND $pfinal";
			}

			if($flujo)
			{
				$where.= " AND p.id IN (SELECT IdPoliza FROM cont_movimientos WHERE Cuenta = $flujo)";
				//echo "Flujo: ".$flujo;
			}

			if($noAplica)
			{
				$where .= " AND r.aplica=0";
			}
			else
			{
				$where .= " AND r.aplica=1";	
			}

			$primero = 0;
			for($i=0;$i<=count($tasas);$i++)
			{
				if($tasas[$i] != NULL)
				{
					$primero++;
					if($primero == 1)
					{
						$where .= " AND (";
					}
					else
					{
						$where .= " OR ";
					}

					switch($i)
					{
						case 3 : $where .= "(t.valor = ".$tasas[$i]." AND r.tasa != 'Exenta')";
									break;
						case 4 : $where .= "(t.valor = ".$tasas[$i]." AND r.tasa = 'Exenta')";
									break;
						default : 	$where .= "t.valor = ".$tasas[$i];					
					}
				}
			}
			$where .= ")";

			
			$myQuery = "SELECT r.idProveedor,p.id, p.fecha, (SELECT titulo FROM cont_tipos_poliza WHERE id = p.idtipopoliza) AS tipoPoliza, r.referencia, (SELECT NombreEjercicio FROM cont_ejercicios WHERE id=p.idejercicio) AS ejercicio, p.idperiodo, p.numpol, r.periodoAcreditamiento, (SELECT razon_social FROM mrp_proveedor WHERE idPrv = r.idProveedor) AS proveedor, (SELECT rfc FROM mrp_proveedor WHERE idPrv = r.idProveedor) AS rfc, r.importeBase, r.otrasErogaciones, t.tasa, t.valor AS tasaValor, r.ivaRetenido, r.isrRetenido, r.ivaPagadoNoAcreditable 
			FROM cont_rel_pol_prov r
			INNER JOIN cont_polizas p ON p.id = r.idPoliza
			INNER JOIN cont_tasaPrv t ON t.id = r.tasa
			WHERE $where AND r.activo=1 AND p.activo=1
			ORDER BY r.idProveedor,r.id";
			$exercise = $this->query($myQuery);
			//echo $myQuery;
			//var_dump($tasas);
			//echo $tasas[0];
			
			return $exercise;
		}
	}
?>
