<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

	class flujoEfectivoIvaModel extends Connection
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
		function flujo()
		{
			$myQuery = "SELECT account_id,manual_code, description FROM cont_accounts
						WHERE account_code LIKE CONCAT((SELECT a.account_code FROM cont_accounts a RIGHT JOIN cont_config c ON c.CuentaBancos = a.account_id), '%') AND affectable=1 AND removed=0";
			$flujo = $this->query($myQuery);
			return $flujo;
		}

		function obtenerDatos($inicio,$fin,$Ap)
		{
			$where = '';
			if($Ap == 'Aplican')
			{
				$Sums = "
(SELECT SUM(importe) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS Importe, 
(SELECT SUM(importeBase) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS ImporteBase, 
(SELECT SUM(rp.importeBase * (SELECT valor FROM cont_tasaPrv WHERE id=rp.tasa)/100) FROM cont_rel_pol_prov rp WHERE rp.idPoliza = r.idPoliza AND activo=1) AS ImporteIva,
(SELECT SUM(otrasErogaciones) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS Erogaciones, 
(SELECT SUM(ivaRetenido) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS IvaRetenido,
(SELECT SUM(isrRetenido) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS IsrRetenido,
(SELECT SUM(ivaPagadoNoAcreditable) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS IvaPagadoNoAcreditable,
(SELECT SUM((rp.importeBase+(rp.importeBase * (SELECT valor FROM cont_tasaPrv WHERE id=rp.tasa)/100))+rp.otrasErogaciones-rp.ivaRetenido-rp.isrRetenido) FROM cont_rel_pol_prov rp WHERE rp.idPoliza = r.idPoliza AND activo=1) AS Total, 
";
$where = "AND r.aplica=1 ";
			}
			else
			{
				$Sums = "
(SELECT SUM(CASE r.aplica 
WHEN 1 THEN importe
WHEN 0 THEN 0 END) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS Importe, 

(SELECT SUM(CASE r.aplica 
WHEN 1 THEN importeBase
WHEN 0 THEN 0 END) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS ImporteBase,

(SELECT SUM(CASE r.aplica 
WHEN 1 THEN (rp.importeBase * (SELECT valor FROM cont_tasaPrv WHERE id=rp.tasa)/100)
WHEN 0 THEN 0 END) FROM cont_rel_pol_prov rp WHERE rp.idPoliza = r.idPoliza AND activo=1) AS ImporteIva,

(SELECT SUM(CASE r.aplica 
WHEN 1 THEN otrasErogaciones
WHEN 0 THEN 0 END) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS Erogaciones, 

(SELECT SUM(CASE r.aplica 
WHEN 1 THEN ivaRetenido
WHEN 0 THEN 0 END)  FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS IvaRetenido,

(SELECT SUM(CASE aplica 
WHEN 1 THEN isrRetenido
WHEN 0 THEN 0 END) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS IsrRetenido,

(SELECT SUM(CASE r.aplica 
WHEN 1 THEN ivaPagadoNoAcreditable
WHEN 0 THEN 0 END) FROM cont_rel_pol_prov WHERE idPoliza = r.idPoliza AND activo=1) AS IvaPagadoNoAcreditable,

(SELECT SUM(CASE r.aplica 
WHEN 1 THEN (rp.importeBase+(rp.importeBase * (SELECT valor FROM cont_tasaPrv WHERE id=rp.tasa)/100)+rp.otrasErogaciones-rp.ivaRetenido-rp.isrRetenido)
WHEN 0 THEN 0 END) FROM cont_rel_pol_prov rp WHERE rp.idPoliza = r.idPoliza AND activo=1) AS Total, ";
			}

			if($_POST['prov'] == 'algunos')
			{
				$where .= "AND m.Cuenta BETWEEN '".$_POST['finicial']."' AND '".$_POST['ffinal']."'";
			}
			$myQuery = "SELECT 
r.idPoliza,
p.id,
p.numpol,p.idperiodo,
p.fecha, 
(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS TipoPoliza,
p.referencia,  
p.concepto,  
(SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Abono' AND Cuenta = m.Cuenta) AS TotalAbonos,
$Sums
m.Cuenta, 
a.account_code, 
r.idProveedor,
a.manual_code, 
a.description 
FROM cont_polizas p
LEFT JOIN cont_rel_pol_prov r ON r.idPoliza = p.id AND r.activo=1
INNER JOIN cont_movimientos m ON m.IdPoliza = p.id AND m.Activo=1
INNER JOIN cont_accounts a ON a.account_id = m.Cuenta

WHERE m.TipoMovto = 'Abono' AND m.Cuenta IN(SELECT account_id FROM cont_accounts WHERE main_father = (SELECT CuentaBancos FROM cont_config WHERE id=1))
AND a.affectable=1 
AND a.removed=0 
AND p.activo=1 
AND p.fecha BETWEEN '$inicio' AND '$fin' 
$where

GROUP BY a.account_code,p.id
ORDER BY a.account_code,r.idPoliza
";
			$data = $this->query($myQuery);
			return $data;
		}

		function ProvsCuenta($poliza,$Ap)
		{
			if($Ap == 'Aplican')
			{
				$where ="r.aplica=1 AND";
			}
			else
			{
				$where = "";
			}
			$myQuery = "SELECT
r.idPoliza,
(SELECT razon_social FROM mrp_proveedor WHERE idPrv = r.idProveedor ) AS Proveedor,
((r.importeBase+(r.importeBase * (SELECT valor FROM cont_tasaPrv WHERE id=r.tasa)/100))+r.otrasErogaciones-r.ivaRetenido-isrRetenido) AS Total, 
r.importeBase,
(r.importeBase * (SELECT valor FROM cont_tasaPrv WHERE id=r.tasa)/100) AS ImporteIva,
r.ivaPagadoNoAcreditable,
r.otrasErogaciones,
r.aplica
FROM cont_rel_pol_prov r
WHERE $where r.activo=1 AND r.idPoliza = ".$poliza;

			$poliza = $this->query($myQuery);
			return $poliza;
		}
	}
?>
