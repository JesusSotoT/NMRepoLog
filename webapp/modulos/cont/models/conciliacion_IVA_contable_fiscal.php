<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

class conciliacion_IVA_contable_fiscalModel extends Connection{

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
		
	function consulta_ejercicio($ejercicio=0){
		$filtro="";
		if($ejercicio!=0){$filtro = "where id=".$ejercicio;}
		$qry_ej=$this->query("SELECT id, NombreEjercicio FROM cont_ejercicios ".$filtro." ORDER by id DESC");
		if(mysqli_num_rows($qry_ej)>0){
			if($ejercicio!=0){
				return $qry_ej->fetch_object();
			}
			else return $qry_ej;
		}
		else return 0;
	}


	function cuentas(){
		$qry_ej=$this->query("SELECT manual_code,account_id,description FROM cont_accounts WHERE removed=0 AND main_account = 3 ORDER BY manual_code");
		return $qry_ej;
	}

	function ivaTrasladado($periodoAcreditamiento,$rango_inicio,$rango_fin,$cuenta)
	{
		if($periodoAcreditamiento)
		{
			$inicio = explode('/',$rango_inicio);
			$where = "AND d.periodoAcreditamiento BETWEEN ".$inicio[1]." AND $rango_fin AND d.ejercicioAcreditamiento = ".$inicio[0];
		}
		else
		{
			$where = "AND p.fecha BETWEEN '$rango_inicio' AND '$rango_fin' ";
		}
		$myQuery = "SELECT
p.id,
p.numpol,p.idperiodo,
(SELECT NombreEjercicio FROM cont_ejercicios WHERE id = p.idejercicio) AS Ejercicio,
p.fecha, 
(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS TipoPoliza,
p.referencia,  
p.concepto,  
@ac := (SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Abono' AND Cuenta = $cuenta),
@cc := (SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Cargo' AND Cuenta = $cuenta),
(IFNULL(@ac,0) - IFNULL(@cc,0)) AS ImporteContable,
(SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Abono' AND Cuenta = $cuenta) AS TotalFiscal,
0 AS IvaNoAcreditable
FROM cont_polizas p
LEFT JOIN cont_rel_desglose_iva d ON d.idPoliza = p.id
WHERE 
p.id IN (SELECT idPoliza FROM cont_rel_desglose_iva WHERE activo=1) AND p.activo = 1
$where

ORDER BY p.numpol, p.idperiodo
";
		$datos = $this->query($myQuery);
		return $datos;

	}

	function ivaTrasladadoNo($periodoAcreditamiento,$rango_inicio,$rango_fin,$cuenta)
	{
		if($periodoAcreditamiento)
		{
			$inicio = explode('/',$rango_inicio);
			$where = "AND d.periodoAcreditamiento BETWEEN ".$inicio[1]." AND $rango_fin AND d.ejercicioAcreditamiento = ".$inicio[0];
		}
		else
		{
			$where = "AND p.fecha BETWEEN '$rango_inicio' AND '$rango_fin' ";
		}
		$myQuery = "SELECT
p.id,
p.numpol,p.idperiodo,
(SELECT NombreEjercicio FROM cont_ejercicios WHERE id = p.idejercicio) AS Ejercicio,
p.fecha, 
(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS TipoPoliza,
p.referencia,  
p.concepto,  
(SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Abono' AND Cuenta = $cuenta) AS ImporteContable,
0 AS TotalFiscal,
0 AS IvaNoAcreditable
FROM cont_polizas p
LEFT JOIN cont_rel_desglose_iva d ON d.idPoliza = p.id
WHERE 
p.id NOT IN (SELECT idPoliza FROM cont_rel_desglose_iva WHERE activo=1) AND p.activo = 1
AND p.id IN (SELECT IdPoliza FROM cont_movimientos WHERE TipoMovto = 'Abono' AND Activo=1 AND Cuenta = $cuenta )
$where

ORDER BY p.numpol, p.idperiodo
";
		$datos = $this->query($myQuery);
		return $datos;

	}

	function ivaAcreditable($periodoAcreditamiento,$rango_inicio,$rango_fin,$cuenta)
	{
		if($periodoAcreditamiento)
		{
			$inicio = explode('/',$rango_inicio);
			$where = "AND r.periodoAcreditamiento BETWEEN ".$inicio[1]." AND $rango_fin AND r.ejercicioAcreditamiento = ".$inicio[0];
		}
		else
		{
			$where = "AND p.fecha BETWEEN '$rango_inicio' AND '$rango_fin' ";
		}
		$myQuery = "SELECT
DISTINCT r.idPoliza,
p.id,
p.numpol,p.idperiodo,
(SELECT NombreEjercicio FROM cont_ejercicios WHERE id = p.idejercicio) AS Ejercicio,
p.fecha, 
(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS TipoPoliza,
p.referencia,  
p.concepto,
r.idProveedor, 
@cc := (SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Cargo' AND Cuenta = $cuenta),
@ac := (SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Abono' AND Cuenta = $cuenta),
 (IFNULL(@cc,0) - IFNULL(@ac,0)) AS ImporteContable, 
((SELECT SUM(rp.importeBase * (SELECT valor FROM cont_tasaPrv WHERE id=rp.tasa)/100) FROM cont_rel_pol_prov rp WHERE rp.idPoliza = p.id AND rp.activo=1 AND idProveedor = r.idProveedor)-r.ivaRetenido) AS ImporteFiscal,
 r.ivaPagadoNoAcreditable AS IvaNoAcreditable
FROM cont_polizas p
LEFT JOIN cont_rel_pol_prov r ON r.idPoliza = p.id AND r.activo=1
WHERE p.id IN (SELECT idPoliza FROM cont_rel_pol_prov WHERE activo=1) AND p.activo = 1
$where

ORDER BY p.numpol, idPoliza, p.idperiodo



";
		$datos = $this->query($myQuery);
		return $datos;

	}

	function ivaAcreditableNo($periodoAcreditamiento,$rango_inicio,$rango_fin,$cuenta)
	{
		if($periodoAcreditamiento)
		{
			$inicio = explode('/',$rango_inicio);
			$where = "AND r.periodoAcreditamiento BETWEEN ".$inicio[1]." AND $rango_fin AND r.ejercicioAcreditamiento = ".$inicio[0];
		}
		else
		{
			$where = "AND p.fecha BETWEEN '$rango_inicio' AND '$rango_fin' ";
		}
		$myQuery = "SELECT
DISTINCT r.idPoliza,
p.id,
p.numpol,p.idperiodo,
(SELECT NombreEjercicio FROM cont_ejercicios WHERE id = p.idejercicio) AS Ejercicio,
p.fecha, 
(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS TipoPoliza,
p.referencia,  
p.concepto,  
r.idProveedor,
(SELECT SUM(Importe) FROM cont_movimientos WHERE IdPoliza = p.id AND activo=1 AND TipoMovto='Cargo' AND Cuenta = $cuenta) AS ImporteContable,
0 AS ImporteFiscal,
0 AS IvaNoAcreditable

FROM cont_polizas p
LEFT JOIN cont_rel_pol_prov r ON r.idPoliza = p.id AND r.activo=1
WHERE p.id NOT IN (SELECT idPoliza FROM cont_rel_pol_prov WHERE activo=1)
AND p.id IN (SELECT IdPoliza FROM cont_movimientos WHERE TipoMovto = 'Cargo' AND Activo=1 AND Cuenta = $cuenta ) AND p.activo = 1
$where

ORDER BY p.numpol, p.idperiodo

";
		$datos = $this->query($myQuery);
		return $datos;

	}
}
?> 