<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

class Auxiliar_ImpuestosModel extends Connection
{
	function empresa()
		{
				$myQuery = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
				$empresa = $this->query($myQuery);	
				$empresa = $empresa->fetch_array();
				return $empresa['nombreorganizacion'];
		}

	function con_ejercicio($ejercicio=0){
		$filtro="";
		if($ejercicio!=0){$filtro = "where id=".$ejercicio;}
		$qry_ej=$this->query("SELECT id, NombreEjercicio FROM cont_ejercicios ".$filtro." ORDER by id DESC");
		if(mysqli_num_rows($qry_ej)>0){
			
			if($ejercicio!=0){return $qry_ej->fetch_object();}
			else return $qry_ej;
		}
		else return 0;
	}

	function tasas_iva(){
		$qry_ej=$this->query("SELECT * FROM cont_tasas");
		if(mysqli_num_rows($qry_ej)>0){
			return $qry_ej;
		}
		else return 0;
	}

	function tipo_operacion(){
		$qry_ej=$this->query("SELECT id, tipoOperacion FROM cont_tipo_operacion");
		if(mysqli_num_rows($qry_ej)>0){
			return $qry_ej;
		}
		else return 0;
	}

	function tipo_tercero(){
		$qry_ej=$this->query("SELECT id, tipoTercero FROM cont_tipo_tercero");
		if(mysqli_num_rows($qry_ej)>0){
			return $qry_ej;
		}
		else return 0;
	}


	function tipo_iva(){
		$qry_ej=$this->query("SELECT id, tipoiva FROM cont_tipo_iva");
		if(mysqli_num_rows($qry_ej)>0){
			return $qry_ej;
		}
		else return 0;
	}

	public function egresos($fecha,$tasa_sel,$acreed100,$pago_prov,$tipo_op,$tipo_iva)
	{
		$f = explode('*/*',$fecha);
		if($f[0])
		{
			$periodo = "r.periodoAcreditamiento = ".$f[1]." AND r.ejercicioAcreditamiento = ".$f[2];
			$periodo2 = "r.periodoAcreditamiento = ".(intval($f[1])-1)." AND r.ejercicioAcreditamiento = ".$f[2];
		}
		else
		{
			$periodo = "p.fecha BETWEEN '".$f[1]."' AND '".$f[2]."'";
			$periodo2 = "p.fecha BETWEEN '".date("Y-m-d",strtotime('-1 month',strtotime($f[1])))."' AND '".date("Y-m-d",strtotime('-1 month',strtotime($f[2])))."'";
		}
		$where = '';
		if($tasa_sel != '-')
		{
			$where .= " AND t.tasa = '".$tasa_sel."'";
		}

		if(intval($tipo_iva))
		{
			$where .= " AND m.idtipoiva = ".$tipo_iva;
		}

		if(intval($tipo_op))
		{
			$where .= " AND m.idtipoperacion=".$tipo_op;
		}

		if(intval($pago_prov))
		{
			$where .= " AND (m.idtipotercero = 1 OR m.idtipotercero = 5)";
		}

		$myQuery = "(SELECT
p.fecha, 
p.id,
p.numpol, 
p.concepto,
(SELECT titulo FROM cont_tipos_poliza WHERE id = idtipopoliza) AS titulo,
(SELECT NombreEjercicio FROM cont_ejercicios WHERE Id = r.ejercicioAcreditamiento) AS ejercicio,
r.periodoAcreditamiento AS periodo,
p.idperiodo,
t.tasa,
r.ImporteBase,
(r.ImporteBase * (t.valor/100)) AS impiva,
r.ivaRetenido,
0 AS retenidoMesAnterior,
(r.importeBase*(t.valor/100)-r.ivaPagadoNoAcreditable-r.ivaRetenido) as causado,
r.isrRetenido,
r.otrasErogaciones,
r.ivaPagadoNoAcreditable as iva_pag_no_acr,
r.idProveedor,
m.razon_social,
m.rfc,
r.referencia,
(SELECT tipoTercero FROM cont_tipo_tercero WHERE id = m.idtipotercero) AS tipotercero,
(SELECT tipoOperacion FROM cont_tipo_operacion WHERE id = m.idtipoperacion) AS tipooperacion,
(SELECT tipoiva FROM cont_tipo_iva WHERE id = m.idtipoiva) AS tipoiva

FROM cont_rel_pol_prov r
LEFT JOIN cont_polizas p ON p.id = r.idPoliza
LEFT JOIN mrp_proveedor m ON m.idPrv = r.idProveedor 
LEFT JOIN cont_tasaPrv t ON t.id = r.tasa
WHERE 
$periodo 
$where
 AND p.idtipopoliza != 1
 AND r.activo=1 AND r.aplica=1
 AND p.activo=1)
 UNION ALL
 (SELECT
p.fecha, 
p.id,
p.numpol, 
p.concepto,
(SELECT titulo FROM cont_tipos_poliza WHERE id = idtipopoliza) AS titulo,
(SELECT NombreEjercicio FROM cont_ejercicios WHERE Id = r.ejercicioAcreditamiento) AS ejercicio,
r.periodoAcreditamiento AS periodo,
p.idperiodo,
t.tasa,
0 AS ImporteBase,
0 AS impiva,
0 AS ivaRetenido,
r.ivaRetenido AS retenidoMesAnterior,
0 AS causado,
0 AS isrRetenido,
0 AS otrasErogaciones,
0 AS iva_pag_no_acr,
r.idProveedor,
m.razon_social,
m.rfc,
r.referencia,
(SELECT tipoTercero FROM cont_tipo_tercero WHERE id = m.idtipotercero) AS tipotercero,
(SELECT tipoOperacion FROM cont_tipo_operacion WHERE id = m.idtipoperacion) AS tipooperacion,
(SELECT tipoiva FROM cont_tipo_iva WHERE id = m.idtipoiva) AS tipoiva

FROM cont_rel_pol_prov r
LEFT JOIN cont_polizas p ON p.id = r.idPoliza
LEFT JOIN mrp_proveedor m ON m.idPrv = r.idProveedor 
LEFT JOIN cont_tasaPrv t ON t.id = r.tasa
WHERE 
$periodo2 
$where
 AND r.ivaRetenido > 0
 AND p.idtipopoliza != 1
 AND r.activo=1 AND r.aplica=1
 AND p.activo=1)
ORDER BY ejercicio, periodo, numpol";
//echo $myQuery;
		$e = $this->query($myQuery);
		return $e;
	}

	public function ingresos($fecha,$tasa_sel,$acreed100,$pago_prov,$tipo_op,$tipo_iva)
	{
		$f = explode('*/*',$fecha);
		if($f[0])
		{
			$where = "r.periodoAcreditamiento = ".$f[1]." AND r.ejercicioAcreditamiento = ".$f[2];
			$ac="r.periodoAcreditamiento AS Periodo, (SELECT NombreEjercicio FROM cont_ejercicios WHERE Id = r.ejercicioAcreditamiento) AS Ejercicio, ";
		}
		else
		{
			$where = "p.fecha BETWEEN '".$f[1]."' AND '".$f[2]."'";
			$ac="p.idperiodo AS Periodo, (SELECT NombreEjercicio FROM cont_ejercicios WHERE Id = p.idejercicio) AS Ejercicio, ";
		}

		if($tasa_sel != '-')
		{
			$myQuery = $this->contasa($tasa_sel,$where,$ac);
		}
		else
		{
			$str_tasas = '16/11/0/Exenta/15/10';
			$myQuery = $this->sintasa($str_tasas,$where,$ac);	
		}
		//echo $myQuery;
		$e = $this->query($myQuery);
		//echo $myQuery;
		return $e;
	}


	private function contasa($tasa,$where,$ac)
	{
		$t = str_replace('%', '', $tasa);
		$q = "SELECT
p.fecha,
p.id,
p.numpol,
(SELECT titulo FROM cont_tipos_poliza WHERE id = p.idtipopoliza) AS TipoPoliza,
p.concepto,
p.idperiodo,
$ac
'$tasa' AS Tasa,
@ImporteBase := SUBSTRING_INDEX(SUBSTRING_INDEX( r.tasa$t , '-', -3 ),'-',1) AS ImporteBase,
@ImporteIVA := SUBSTRING_INDEX(SUBSTRING_INDEX( r.tasa$t , '-', -2 ),'-',1) AS ImporteIVA,
@ImporteNoAcred := SUBSTRING_INDEX(SUBSTRING_INDEX( r.tasa$t , '-', -1 ),'-',1) AS ImporteNoAcred,
r.ivaRetenido,
(@ImporteIVA - @ImporteNoAcred - r.ivaRetenido) AS causado,
r.isrRetenido,
r.otros
FROM cont_rel_desglose_iva r
LEFT JOIN cont_polizas p ON p.id = r.idPoliza
WHERE 
$where
AND p.idtipopoliza = 1
AND r.aplica=1
AND p.activo=1
HAVING ImporteBase != 0
ORDER BY Ejercicio,Periodo, p.numpol";
		return $q;
	}

	private function sintasa($tasas,$where,$ac)
	{
		$tasas = explode('/',$tasas);
		$q='';
		for($i=0;$i<=5;$i++)
		{
			if($i!=0)
			{
				$q .= ' 
				UNION
				 ';
			}
			$q .= "(SELECT
p.fecha,
p.id,
p.numpol,
(SELECT titulo FROM cont_tipos_poliza WHERE id = p.idtipopoliza) AS TipoPoliza,
p.concepto,
p.idperiodo,
$ac
'".$tasas[$i]."%' AS Tasa,
@ImporteBase := SUBSTRING_INDEX(SUBSTRING_INDEX( r.tasa".$tasas[$i]." , '-', -3 ),'-',1) AS ImporteBase,
@ImporteIVA := SUBSTRING_INDEX(SUBSTRING_INDEX( r.tasa".$tasas[$i]." , '-', -2 ),'-',1) AS ImporteIVA,
@ImporteNoAcred := SUBSTRING_INDEX(SUBSTRING_INDEX( r.tasa".$tasas[$i]." , '-', -1 ),'-',1) AS ImporteNoAcred,
r.ivaRetenido,
(@ImporteIVA - @ImporteNoAcred - r.ivaRetenido) AS causado,
r.isrRetenido,
r.otros
FROM cont_rel_desglose_iva r
LEFT JOIN cont_polizas p ON p.id = r.idPoliza
WHERE 
$where
AND p.idtipopoliza = 1
AND r.aplica=1
AND p.activo=1
HAVING ImporteBase != 0)";
		}
		$q .= " 
		ORDER BY Ejercicio,Periodo, numpol
		";
		return $q;
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