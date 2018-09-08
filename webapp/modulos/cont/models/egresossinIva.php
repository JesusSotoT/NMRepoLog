<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

	class EgresosSinIvaModel extends Connection
	{
		function obtenerDatos($inicio,$fin)
		{
			$myQuery = "(SELECT DISTINCT 
p.numpol,p.idperiodo, p.fecha, p.id, (SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS tipoPoliza, p.concepto, p.referencia, SUM(m.importe) AS Erogacion
FROM cont_polizas p
RIGHT JOIN cont_rel_pol_prov r ON r.idPoliza = p.id  AND r.aplica=0 AND r.activo=1
INNER JOIN cont_movimientos m ON m.idPoliza = p.id AND m.activo=1 AND TipoMovto = 'Abono' AND m.Cuenta IN(SELECT account_id FROM cont_accounts WHERE main_father = (SELECT CuentaBancos FROM cont_config WHERE id=1))
WHERE p.fecha BETWEEN '$inicio' AND '$fin' AND p.activo=1 
GROUP BY idperiodo,numpol ASC
)

UNION

(SELECT p.numpol,p.idperiodo, p.fecha, p.id, (SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS tipoPoliza, p.concepto, p.referencia, SUM(m.importe) AS Erogacion
FROM cont_polizas p
INNER JOIN cont_movimientos m ON m.idPoliza = p.id AND m.activo=1 AND m.TipoMovto = 'Abono' AND m.Cuenta IN(SELECT account_id FROM cont_accounts WHERE main_father = (SELECT CuentaBancos FROM cont_config WHERE id=1))
WHERE p.fecha BETWEEN '$inicio' AND '$fin' AND p.activo=1 AND p.id NOT IN(SELECT idPoliza FROM cont_rel_pol_prov WHERE activo=1) 
						
GROUP BY idperiodo,numpol ASC)
ORDER BY idperiodo,numpol";
/*$myQuery = "(SELECT p.id, p.numpol,p.idperiodo, p.fecha, (SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS tipoPoliza, p.concepto, p.referencia, SUM(m.importe) AS Erogacion, 
						(SELECT COUNT(*) FROM cont_movimientos WHERE IdPoliza = p.id AND Factura LIKE \"%.xml\" AND Activo=1) AS Contando
						FROM cont_polizas p
						INNER JOIN cont_movimientos m ON m.idPoliza = p.id AND m.activo=1 AND TipoMovto = 'Abono'
						WHERE p.fecha BETWEEN '$inicio' AND '$fin' AND p.activo=1 AND p.idtipopoliza != 1 AND p.id NOT IN(SELECT idPoliza FROM cont_rel_pol_prov WHERE activo=1) GROUP BY idperiodo,numpol ASC 
						HAVING Contando != 0)";*/
			$exercise = $this->query($myQuery);
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
