<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
//require("models/captpolizas.php"); // funciones de captpolizas
require("models/reports.php"); // funciones de captpolizas

	class PresupuestoModel extends Connection
	{

		function listaSegmentoSucursal($tipo)
		{
			if($tipo)//Si es 0 es segmento , si es 1 es sucursal
			{
				return CaptPolizasModel::ListaSucursales();//Funcion que obtiene la lista de sucursales en la clase captpolizas
			}
			else
			{
				return CaptPolizasModel::ListaSegmentos();//Funcion que obtiene la lista de segmentos en la clase captpolizas
			}
		}

		function getAccounts()
		{
			return CaptPolizasModel::getAccounts('manual_code');
		}

		function listaPresupuesto($IdEjercicio,$tipoCuenta)
		{
			$masSplits = '';
			$orden = 'cuenta,';
			if($tipoCuenta == 'a')
			{
				$orden = '';
				for($i=3;$i<=8;$i++)
				{
					$orden .= "CAST(h$i AS UNSIGNED), ";
					$masSplits .= "REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', $i), LENGTH(SUBSTRING_INDEX(a.account_code, '.', $i -1)) + 1),'.', '') AS h$i, ";
				}
			}
			$myQuery = "SELECT p.id,p.anual,p.meses, 
CONCAT(a.manual_code,' / ',a.description) AS cuenta,
REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 1),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 1 -1)) + 1),
       '.', '') AS h1,
REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 2),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 2 -1)) + 1),
       '.', '') AS h2,
$masSplits 
CONCAT(s.Clave,' / ',s.nombre) AS segmento,
CONCAT(m.idSuc,' / ',m.nombre) AS sucursal
FROM cont_presupuestos p 
INNER JOIN cont_accounts a ON a.account_id = p.cuenta 
INNER JOIN cont_segmentos s ON s.idSuc = p.segmento
INNER JOIN mrp_sucursal m ON m.idSuc = p.sucursal
WHERE p.ejercicio = $IdEjercicio AND p.activo = 1 ORDER BY CAST(h1 AS UNSIGNED),CAST(h2 AS UNSIGNED), $orden segmento,sucursal";
			return $this->query($myQuery);
		}
		
		function guardaPresupuesto($IdEjercicio,$IdCuenta,$IdSegmento,$IdSucursal,$Anual,$Meses,$Act,$Id)
		{

			$return = 0;
			$myQuery = "SELECT id FROM cont_presupuestos WHERE cuenta=$IdCuenta AND segmento = $IdSegmento AND sucursal = $IdSucursal AND ejercicio = $IdEjercicio AND activo = 1;";
			$idcuenta = $this->query($myQuery);
			$idcuenta = $idcuenta->fetch_assoc();
			if(intval($Id) == intval($idcuenta['id']))
				$idcuenta['id'] = 0;
			
			if(!intval($idcuenta['id']))
			{
				if(intval($Act))
				{
					$myQuery = "UPDATE cont_presupuestos SET cuenta = $IdCuenta, segmento = $IdSegmento, sucursal = $IdSucursal, anual = $Anual, meses = '$Meses' WHERE id = $Id;";
					$this->query($myQuery);
					$return = 1;
				}
				else
				{
					$myQuery = "INSERT INTO cont_presupuestos(ejercicio,cuenta,segmento,sucursal,anual,meses,activo) VALUES($IdEjercicio,$IdCuenta,$IdSegmento,$IdSucursal,$Anual,'$Meses',1);";
					$this->query($myQuery);
					$return = 1;
				}
			}
			return $return;
		}

		function listaEjercicios()
		{
			return CaptPolizasModel::exercisesList();	
		}

		function ejercicioActual()
		{
			return CaptPolizasModel::getExerciseInfo();
		}

		function eliminaPresupuesto($Id)
		{
			$myQuery = "UPDATE cont_presupuestos SET activo=0 WHERE id=$Id";
			$this->query($myQuery);
		}

		function saldoCuenta($Cuenta,$Fecha,$tipo,$nivel,$segmento,$saldo,$sucursal)
		{
			return ReportsModel::Saldos($Cuenta,$Fecha,$tipo,$nivel,$segmento,$saldo,$sucursal);
		}

		function NombreEjercicio($idejercicio)
		{
			return ReportsModel::NombreEjercicio($idejercicio);//
		}

		function datosPresup($id)
		{
			$myQuery = "SELECT* FROM cont_presupuestos WHERE id=$id";
			$datos = $this->query($myQuery);
			return $datos;
		}
		function cuentasconf()
		{
			return CaptPolizasModel::cuentasconf();
		}

		function tipoCuenta()
		{
			return ReportsModel::tipoCuenta();
		}

		function repetedBD($ejercicio,$cuenta,$segmento,$sucursal)
		{
			$myQuery = "SELECT id FROM cont_presupuestos WHERE activo = 1 AND ejercicio = $ejercicio AND cuenta = $cuenta AND segmento = $segmento AND sucursal = $sucursal;";
			$datos = $this->query($myQuery);
			$datos = $datos->fetch_object();
			return $datos->id;	
		}
	}
?>


