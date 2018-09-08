<?php
	/**
	
		TODO:
		- Registro de saldos Iniciales.- Se piden los registros anteriores al periodo 
			- No importa si el mes es enero o Diciembre. Los saldos iniciales siempre son anteriores al Periodo Inicial !!!
		- Periodo Inicial.- Si es igual al Perodio Final solamente se solicicta del Inicio al final del mes. En caso contrario, si son distintos, se realiza una segmentacion Mes-Año.
		- Periodo Final.- Es el ultimo Periodo a mostrar....

		Son 2 consultas:

			SELECT SUM(IMPORTE),TipoMovto, "Saldos Inciales" AS Flag FROM movimientos WHERE periodo < [Periodo Inicial] ORDER BY cuenta,TipoMovto
			UNION 
			SELECT Importe, TipoMovto, poliza.fecha AS flag FROM movimientos WHERE Periodo BETWEEN "Primer dia del periodo Inicial" AND "Ultimo dia del Periodo Final"	



	**/
	
$empresa = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
$empresa = $conexion->consultar($empresa);	
$empresa = mysql_fetch_array($empresa);
echo "<table border=0 id='empresa' style='width:550px'><tr style='width:550px'><td style='text-align:center;width:550px'><b style='text-align:center;'>".$empresa['nombreorganizacion']."</b></td></tr></table>";

	$data = explode("\n", $sql);
	$anio = $data[0];
	$periodo_uno = $data[1];
	$periodo_dos = $data[2];
	//$tipo_reporte = $data[3];
	$xchange = explode(" " , $anio);
	$anio = $xchange[7];
	$xchange = explode(" " , $periodo_uno);
	$periodo_uno = $xchange[1];
	$xchange = explode(" " , $periodo_dos);
	$periodo_dos = $xchange[1];
	
	//$xchange = explode(" " , $tipo_reporte);
	//$tipo_reporte = $xchange[1];
	
	$select = "SELECT NombreEjercicio FROM cont_ejercicios WHERE id = " . $anio . " LIMIT 1;";
	$result = $conexion->consultar($select) or die( nl2br($sql) . "<br>" . $anio );
	
	$data = mysql_fetch_array($result);
	$anioFinal = (int) $data[0];

	$sql = 'SELECT 
	n.description AS Naturaleza, 
	p.fecha AS Fecha, 
	p.concepto AS Poliza, 
	a.description AS Cuenta, 
	if(TipoMovto = "Cargo",Importe, 0) AS Cargos, 
	if(TipoMovto = "Abono",Importe, 0) AS Abonos, 
	"Saldos Iniciales" AS Tipo
from 
	cont_movimientos m 
	INNER JOIN cont_accounts a 
		ON m.Cuenta = a.account_id 
	INNER JOIN cont_nature n 
		ON a.account_nature = n.nature_id 
	INNER JOIN cont_polizas p 
		ON m.idPoliza = p.id 
		AND p.fecha < "' . $anioFinal . '-' . $periodo_uno . '-01" 
WHERE 
	m.Activo = 1 
GROUP BY 
	m.Cuenta, 
	m.TipoMovto
UNION
SELECT 
	n.description AS Naturaleza, 
	p.fecha AS Fecha, 
	p.concepto AS Poliza, 
	a.description AS Cuenta, 
	IF(TipoMovto = "Cargo",Importe, 0) AS Cargos, 
	IF(TipoMovto = "Abono",Importe, 0) AS Abonos,
	"Otros Movimientos" AS Tipo 
FROM 
	cont_movimientos m 
	INNER JOIN cont_accounts a 
		ON m.Cuenta = a.account_id 
	INNER JOIN cont_nature n 
		ON a.account_nature = n.nature_id 
	INNER JOIN cont_polizas p 
		ON m.idPoliza = p.id  AND p.idperiodo < 13
		AND p.fecha BETWEEN "' . $anioFinal  . '-' . $periodo_uno . '-01" AND "' . $anioFinal . '-' . $periodo_dos . '-31" 
WHERE 
	m.Activo = 1 
ORDER BY 2,4;';

$sql = 'SELECT 
	Clasificacion,
	Cuenta_de_Mayor,
	Code AS "Code",
	Naturaleza,
	Fecha,
	Poliza,
	Cuenta,
	SUM(Cargos) AS Cargos,
	SUM(Abonos) AS Abonos,
	Flag
FROM 
	cont_view_init_balance2 
WHERE 
	Fecha < "' . $anioFinal . '-' . $periodo_uno . '-01"
GROUP BY Code
UNION 
SELECT 
	(CASE
	WHEN a.account_code LIKE "1%" THEN "Activo"
	WHEN a.account_code LIKE "2%" THEN "Pasivo"
	WHEN a.account_code LIKE "3%" THEN "Capital"
	WHEN a.account_code LIKE "4%" THEN "Resultados"
	END) AS "Clasificacion",
	(SELECT description FROM cont_accounts WHERE account_id = a.main_father) AS "Cuenta_de_Mayor",
	a.account_code AS "Code", 
	n.description AS Naturaleza, 
	p.fecha AS Fecha, 
	p.concepto AS Poliza, 
	a.description AS Cuenta, 
	IF(TipoMovto = "Cargo",Importe, 0) AS Cargos, 
	IF(TipoMovto = "Abono",Importe, 0) AS Abonos, 
	"Movimientos Corrientes" AS Flag 
FROM 
	cont_movimientos m 
	INNER JOIN cont_accounts a 
		ON m.Cuenta = a.account_id 
	INNER JOIN cont_nature n 
		ON a.account_nature = n.nature_id 
	INNER JOIN cont_polizas p 
		ON m.idPoliza = p.id 
		AND p.fecha BETWEEN "' . $anioFinal  . '-' . $periodo_uno . '-01" AND "' . $anioFinal . '-' . $periodo_dos . '-31"
WHERE m.Activo = 1 AND p.idperiodo != 13
ORDER BY 3,2,5,7;';

//echo $sql;
//echo "<div id='exec' style='display:none'>" . $tipo_reporte . "</div>";
?>