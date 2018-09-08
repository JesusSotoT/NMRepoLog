<?php
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class EduModel extends Connection
{
	public $connection_edu;
	function __construct()
	{
			//$instancia = "edu.netwarmonitor.com/htdocs/clientes/estudianteudg/webapp/netwarelog/accelog/index.php";
			//$instancia = "www.netwarmonitor.mx/clientes/appcontia/webapp/netwarelog/accelog/index.php";
			$instancia = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$instancia = explode("/",$instancia);
			
			if($instancia[3] == "webapp")
				$this->connection_edu = mysqli_connect("nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com","nmdevel","nmdevel","netwarstore");
				
			else
				$this->connection_edu = mysqli_connect("unmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com","unmdev","&=98+69unmdev","netwarstore");
		
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->connection_edu->close();
	}

	function tipoinstancia()
	 {
	 	$myQuery = "SELECT tipoinstancia FROM organizaciones WHERE idorganizacion=1;";
	 	$id = $this->query($myQuery);
		$id = $id->fetch_assoc();
		return $id['tipoinstancia'];
	 }

	 function idprofesor($instancia)
	 {
	 	$myQuery = "SELECT id FROM customer WHERE instancia = '$instancia'";
		$idprofesor = $this->connection_edu->query($myQuery);
	 	
	 	$idprofesor = $idprofesor->fetch_assoc();
	 	return $idprofesor['id'];
	 }


	 function lista_grupos($idprofesor)
	 {
	 	$myQuery = "SELECT DISTINCT a.idgrupo, g.descripcion FROM ugrupo g 
	 				INNER JOIN relacion_profesores_alumnos a  ON a.idgrupo = g.idgrupo 
	 				WHERE a.idprofesor = $idprofesor
	 				ORDER BY a.idgrupo;";
	 	$grupos = $this->connection_edu->query($myQuery);
	 	return $grupos;
	 }

	 function alumnos($idprofesor,$idgrupo)
	 {
	 	$grupo = '';
	 	if(intval($idgrupo))
	 		$grupo = "AND a.idgrupo = $idgrupo";
	 	$myQuery = "SELECT a.*,c.nombre, c.razon, c.instancia, c.nombre_db FROM relacion_profesores_alumnos a 
					INNER JOIN customer c ON c.id = a.idalumno
					WHERE a.idprofesor = $idprofesor $grupo";
	 	$alumnos = $this->connection_edu->query($myQuery);
	 	return $alumnos;
	 }

	 function listaUsers($var)
	 {
	 	$where = "instancia NOT LIKE '%-%' AND id >= 22 AND instancia NOT LIKE '%a%'";
	 	if($var['lista'] == 'alum')
	 	{
	 		$where = "instancia LIKE '%-%'";
	 	}
	 	$myQuery = "SELECT id, razon, nombre, correo, telefono, giro, instancia, usuario_master, pwd_master, profesor  
	 				FROM customer
					WHERE razon != '' AND idclient > 0 AND $where ;";
	 	$alumnos = $this->connection_edu->query($myQuery);
	 	return $alumnos;
	 }

	 function lista_todos_grupos($univ)
	 {
	 	$myQuery = "SELECT idgrupo, descripcion FROM ugrupo WHERE idcu = $univ;";
	 	$grupos = $this->connection_edu->query($myQuery);
	 	return $grupos;
	 }

	 function lista_todos_relaciones($univ,$grupo)
	 {
	 	$myQuery = "SELECT rpa.id, (SELECT CONCAT(nombre,' (',instancia,')') FROM customer WHERE id = rpa.idprofesor) AS profesor, (SELECT CONCAT(nombre,' (',instancia,')') FROM customer WHERE id = rpa.idalumno) AS alumno, (SELECT descripcion FROM ugrupo WHERE idgrupo = rpa.idgrupo) AS grupo FROM relacion_profesores_alumnos rpa WHERE rpa.iduniversidad = $univ AND rpa.idgrupo = $grupo;";
	 	$rels = $this->connection_edu->query($myQuery);
	 	return $rels;
	 }

	 function listaUniversidades()
	 {
	 	return $this->connection_edu->query("SELECT id, rubro FROM rubro");
	 }

	 function profe_default($grupo)
	 {
	 	$maestro = $this->connection_edu->query("SELECT cus.instancia FROM relacion_profesores_alumnos rpa INNER JOIN customer cus ON cus.id = rpa.idprofesor WHERE idgrupo = $grupo ORDER BY rpa.idprofesor DESC LIMIT 1;");
	 	$maestro = $maestro->fetch_assoc();
	 	return $maestro['instancia'];
	 }

	 function datos_user($instancia)
	 {
	 	$datos = $this->connection_edu->query("SELECT id,nombre FROM customer WHERE instancia = '$instancia'");
	 	if(intval($datos->num_rows))
	 	{
	 		$datos = $datos->fetch_object();
	 		$en_otros = $this->connection_edu->query("SELECT idgrupo FROM relacion_profesores_alumnos WHERE idalumno = $datos->id ORDER BY id DESC LIMIT 1");
	 		if(!intval($en_otros->num_rows))
	 		{
	 			return "<i sigue='si'>Nombre: ".$datos->nombre."</i>";
	 		}
	 		else
	 		{
	 			$en_otros = $en_otros->fetch_object();
	 			return "<i style='color:red;' sigue='no'>Este alumno ya tiene un grupo asignado: <b>".$en_otros->idgrupo."</b></i>";//Ya existe una relacion
	 		}
	 	}
	 	else
	 		return "<i style='color:red;' sigue='no'>No existe este usuario</i>";
	 }

	 function guarda_rel($vars)
	 {
	 	if($this->connection_edu->query("INSERT INTO relacion_profesores_alumnos(id,iduniversidad,idprofesor,idalumno,idgrupo) VALUES(0,".$vars['univ'].",(SELECT id FROM customer WHERE instancia = '".$vars['profe']."'),(SELECT id FROM customer WHERE instancia = '".$vars['alumno']."'),".$vars['grupo'].")"))
	 		return 1;
	 	else
	 		return 0;
	 }

	 function revisado($idregistro)
	 {
	 	$nueva_fecha = date("Y-m-d H:i:s");
	 	$nueva_fecha = strtotime ( '-7 hour' , strtotime ( $nueva_fecha ) ) ;
	 	$nueva_fecha = date("Y-m-d H:i:s",$nueva_fecha);

	 	$myQuery = "UPDATE relacion_profesores_alumnos SET ultima_revision = '$nueva_fecha' WHERE id=$idregistro";
	 	if($this->connection_edu->query($myQuery))
	 	{
	 		$myQuery = "INSERT INTO relacion_revisiones(idrelacion, fecha) VALUES($idregistro, '$nueva_fecha')";
	 		$this->connection_edu->query($myQuery);	
	 		return $nueva_fecha;
	 	}
	 }

	 function listado_revisiones($idrelacion)
	 {
	 	$lista = '';
	 	$myQuery = "SELECT fecha FROM netwarstore.relacion_revisiones WHERE idrelacion = $idrelacion ORDER BY fecha DESC LIMIT 5";
	 	$res = $this->connection_edu->query($myQuery);
	 	if($res->num_rows)
	 	{
	 		$lista = "Ultimas revisiones: \n\n";
	 		while($r = $res->fetch_assoc())
	 			$lista .= $r['fecha']."\n";
	 	}

	 	return $lista;
	 }

	 function cuentasEstudiante($db)
	 {
	 	$myQuery = "SELECT COUNT(account_id) AS cuentas FROM cont_accounts WHERE removed = 0";
	 	$this->connection_edu->select_db($db);
	 	if($cuentas = $this->connection_edu->query($myQuery))
	 	{
	 		$cuentas = $cuentas->fetch_assoc();
	 		return $cuentas['cuentas'];
	 	}
	 	else
	 		return 0;
	 }

	 function polizasEstudiante($db,$ejercicio,$periodo)
	 {
	 	$myQuery = "SELECT COUNT(id) AS polizas FROM cont_polizas WHERE activo = 1 AND idejercicio = (SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '$ejercicio') AND idperiodo = $periodo";
	 	//$this->connection_edu->select_db($db);
	 	if($polizas = $this->connection_edu->query($myQuery))
	 	{
	 		$polizas = $polizas->fetch_assoc();
	 		return $polizas['polizas'];
	 	}
	 	else
	 		return 0;

	 	
	 }

	 function b3($db,$ejercicio,$periodo,$sucursal,$segmento,$tipo,$p13)
	 {
			$where = '';

			if(intval($segmento))
			{
				$where .= "AND b.idsegmento = ".$segmento;
			}
			if(intval($sucursal))
			{
				$where .= " AND b.idsucursal = ".$sucursal;
			}
			
			$periodo13 = ' AND b.idperiodo != 13 ';
			$periodo13bb = ' AND bb.idperiodo != 13 ';
			$periodo13_sub = ' AND idperiodo != 13 ';
			$saldos = "AND IF (b.Fecha = '$ejercicio-12-31',b.idperiodo!=13,b.idperiodo<=13)";
			$saldosbb = "AND IF (bb.Fecha = '$ejercicio-12-31',bb.idperiodo!=13,bb.idperiodo<=13)";
			if(intval($p13))
			{
				$periodo13 = '';
				$periodo13_sub = '';
				$saldos = '';
			}

			$myQuery = '';
			if($tipo == 3)
			{
			$myQuery = "(SELECT 
Clasificacion,
Nivel,
NIF,
CodigoSistema,
Naturaleza,
SUM(IF(Naturaleza=1,Abonos-Cargos,Cargos-Abonos)) AS CargosAbonos,
SUM(IF(Naturaleza=1,AbonosAntes-CargosAntes,CargosAntes-AbonosAntes)) AS CargosAbonosAnterior

FROM ((SELECT 
b.Clasificacion,
(SELECT n.nivel FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS Nivel,
(SELECT CONCAT(n.id,'/',n.clasificacion) FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS NIF,
(SELECT account_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS CodigoSistema,
b.Naturaleza,
b.idsucursal,
b.idsegmento,
b.Cargos AS Cargos,
b.Abonos AS Abonos,
0 AS CargosAntes,
0 AS AbonosAntes

FROM cont_view_init_balance2 b
WHERE b.Cuenta_de_Mayor != '' $where $saldos AND b.Fecha BETWEEN '2000-01-01' AND '$ejercicio-".sprintf('%02d', (intval($periodo)))."-31' AND b.Clasificacion !='Resultados')

UNION ALL

(SELECT 
bb.Clasificacion,
(SELECT n.nivel FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = bb.Code AND removed=0)) AS Nivel,
(SELECT CONCAT(n.id,'/',n.clasificacion) FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = bb.Code AND removed=0)) AS NIF,
(SELECT account_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = bb.Code AND removed=0)) AS CodigoSistema,
bb.Naturaleza,
bb.idsucursal,
bb.idsegmento,
0 AS Cargos,
0 AS Abonos,
bb.Cargos AS CargosAntes,
bb.Abonos AS AbonosAntes

FROM cont_view_init_balance2 bb
WHERE bb.Cuenta_de_Mayor != '' $where $saldosbb AND bb.Fecha BETWEEN '2000-01-01' AND '$ejercicio-".sprintf('%02d', (intval($periodo)-1))."-31' AND bb.Clasificacion !='Resultados')) AS n
WHERE NIF != ''
GROUP BY NIF
)

UNION ALL";
}

			$myQuery .= "(SELECT 
Clasificacion,
Nivel,
NIF,
CodigoSistema,
Naturaleza,
SUM(IF(Naturaleza=1,Abonos-Cargos,Cargos-Abonos)) AS CargosAbonos,
SUM(IF(Naturaleza=1,AbonosAntes-CargosAntes,CargosAntes-AbonosAntes)) AS CargosAbonosAntes

FROM ((SELECT 
b.Clasificacion,
(SELECT n.nivel FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS Nivel,
(SELECT CONCAT(n.id,'/',n.clasificacion) FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS NIF,
(SELECT account_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS CodigoSistema,
b.Naturaleza,
b.idsucursal,
b.idsegmento,
b.Cargos AS Cargos,
b.Abonos AS Abonos,
0 AS CargosAntes,
0 AS AbonosAntes

FROM cont_view_init_balance2 b
WHERE b.Cuenta_de_Mayor != '' $where $periodo13 AND b.Fecha BETWEEN '$ejercicio-01-01' AND '$ejercicio-".sprintf('%02d', (intval($periodo)))."-31' AND b.Clasificacion ='Resultados')

UNION ALL

(SELECT 
bb.Clasificacion,
(SELECT n.nivel FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = bb.Code AND removed=0)) AS Nivel,
(SELECT CONCAT(n.id,'/',n.clasificacion) FROM cont_accounts a INNER JOIN cont_clasificacion_nif n  ON n.id = a.nif WHERE a.account_id = (SELECT main_father FROM cont_accounts WHERE account_code = bb.Code AND removed=0)) AS NIF,
(SELECT account_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = bb.Code AND removed=0)) AS CodigoSistema,
bb.Naturaleza,
bb.idsucursal,
bb.idsegmento,
0 AS Cargos,
0 AS Abonos,
bb.Cargos AS CargosAntes,
bb.Abonos AS AbonosAntes

FROM cont_view_init_balance2 bb
WHERE bb.Cuenta_de_Mayor != '' $where $periodo13bb AND bb.Fecha BETWEEN '$ejercicio-01-01' AND '$ejercicio-".sprintf('%02d', (intval($periodo)-1))."-31' AND bb.Clasificacion ='Resultados')) AS n
WHERE NIF != ''
GROUP BY NIF
)
ORDER BY SUBSTRING(NIF,1,2) * 1 ASC";
			$this->connection_edu->select_db($db);
			$datos = $this->connection_edu->query($myQuery);
			return $datos;
	 }
	
	 function balanceGeneralReporteDemas($db,$ejercicio,$periodo,$sucursal,$segmento,$tipo,$tipoCuenta,$detalle,$p13,$idioma,$presup)
		{
			$where = $whereSub = $myQuery = $activo = $cuentas = $wherePres = '';

			if($tipoCuenta == 'm')
			{
				$orden = 'Codigo';
				$masSplits = '';
			}
			//Si el tipo de codigo de la cuenta es automatico
			if($tipoCuenta == 'a')
			{
				$orden = '';
				$masSplits = '';
				for($i=3;$i<=8;$i++)
				{
					if($i!=8)
					{
						$orden .= "CAST(h$i AS UNSIGNED), ";
					}
					else
					{
						$orden .= "CAST(h$i AS UNSIGNED) ";
					}
					$masSplits .= "REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', $i), LENGTH(SUBSTRING_INDEX(a.account_code, '.', $i -1)) + 1),'.', '') AS h$i, ";
				}
			}

			if(intval($segmento))
			{
				$where .= 'AND m.IdSegmento = '.$segmento;
				$whereSub .= "AND idsegmento = ".$segmento;
				$wherePres .= "AND segmento = ".$segmento;
			}
			if(intval($sucursal))
			{
				$where .= ' AND m.IdSucursal = '.$sucursal;
				$whereSub .= " AND idsucursal = ".$sucursal;
				$wherePres .= " AND sucursal = ".$sucursal;
			}

			if(intval($tipo)==1)
			{
				$activo = "Clasificacion != 'Activo' AND";
			}

			$mayorOdetalle = $group = "";
			if(intval($detalle)==1)
			{
				$cuentas = ", a.manual_code";
				$mayorOdetalle = "a.description AS Cuenta, a.manual_code AS CuentaAfectable,";
				$group = ", a.manual_code";
				$orden .= ", CuentaAfectable";
				$id = "(SELECT account_id FROM cont_accounts WHERE account_code = a.account_code AND removed=0) AS account_id,";
				
			}else{
				$id = "(SELECT account_id FROM cont_accounts WHERE account_id = a.main_father AND removed=0) AS account_id,";
				
			}

			$periodo13 = ' AND p.idperiodo != 13 ';
			$periodo13_sub = ' AND idperiodo != 13 ';
			$saldos = "AND IF (p.fecha = '$ejercicio-12-31',p.idperiodo!=13,p.idperiodo<=13)";
			if(intval($p13))
			{
				$periodo13 = '';
				$periodo13_sub = '';
				$saldos = '';
			}

			$Grupo = "";
			$Clasificacion = "";
			$Desc_Mayor = "(SELECT CONCAT(manual_code,' / ',description) FROM cont_accounts WHERE account_id = a.main_father) AS Cuenta_de_Mayor,";
			if(intval($idioma))
			{
				$Grupo = "(SELECT sec_desc FROM cont_accounts WHERE account_code LIKE LEFT(a.account_code, 3) AND removed=0) AS Grupo_Alt,";
				$Clasificacion = "(case when (a.account_type = 1) then 'Assets' when (a.account_type = 2) then 'Liabilities' when (a.account_type = 3) then 'Capital' when (a.account_type = 4) then 'Results' end) AS Clasificacion_Alt,";
				$Desc_Mayor = "(SELECT CONCAT(manual_code,' / ',sec_desc) FROM cont_accounts WHERE account_id = a.main_father) AS Cuenta_de_Mayor,";
			}

			//PRESUPUESTOS
			if(intval($presup))
			{
				$sumaMeses = '';
				$presupuestal = "(SELECT SUM(REPLACE(SUBSTRING(SUBSTRING_INDEX(meses, '|', $periodo), LENGTH(SUBSTRING_INDEX(meses, '|', $periodo -1)) + 1),'|', '')) AS mes FROM cont_presupuestos WHERE activo=1 AND ejercicio = p.idejercicio AND cuenta = a.account_id $wherePres) AS PresupuestoMes,";
				for($i=1;$i<=intval($periodo);$i++)
				{
					$sumaMeses .= " + SUM(REPLACE(SUBSTRING(SUBSTRING_INDEX(meses, '|', $i), LENGTH(SUBSTRING_INDEX(meses, '|', $i -1)) + 1),'|', ''))";
				}
				$presupuestal .= "(SELECT $sumaMeses AS mes FROM cont_presupuestos WHERE activo=1 AND ejercicio = p.idejercicio AND cuenta = a.account_id $wherePres) AS PresupuestoAcum,";
			}

			if(intval($tipo))
			{
				$myQuery .= "(SELECT 
(case when (a.account_type = 1) then 'Activo' when (a.account_type = 2) then 'Pasivo' when (a.account_type = 3) then 'Capital' when (a.account_type = 4) then 'Resultados' end) AS Clasificacion, $Clasificacion
(SELECT description FROM cont_accounts WHERE account_code LIKE LEFT(a.account_code, 3) AND removed=0) AS Grupo, $Grupo
$Desc_Mayor 
$mayorOdetalle 
$id 
(SELECT manual_code FROM cont_accounts WHERE account_id = a.main_father) AS Codigo,
(SELECT account_code FROM cont_accounts WHERE account_id = a.main_father) AS CodigoSistema,
n.description AS Naturaleza,
m.IdSucursal,
m.IdSegmento,

REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 1),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 1 -1)) + 1),
       '.', '') AS h1,
REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 2),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 2 -1)) + 1),
       '.', '') AS h2,  
$masSplits 

TRUNCATE(SUM(IF(m.TipoMovto = 'Cargo',m.Importe,0))-SUM(IF(m.TipoMovto = 'Abono',m.Importe,0)),4) AS CargosAbonos,
TRUNCATE(SUM(IF(m.TipoMovto = 'Cargo' AND p.fecha < '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-01',m.Importe,0))-SUM(IF(m.TipoMovto = 'Abono' AND p.fecha < '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-01',m.Importe,0)),4) AS CargosAbonosAnterior

FROM cont_movimientos m 
INNER JOIN cont_polizas p ON p.id=m.IdPoliza 
LEFT JOIN cont_accounts a ON a.account_id = m.Cuenta
INNER JOIN cont_nature n ON n.nature_id = a.account_nature
WHERE 
m.Activo = 1
AND p.activo = 1
AND a.account_type < 5
AND p.fecha BETWEEN '2000-01-01' AND '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-31' 
$where 
$saldos 
GROUP BY Cuenta_de_Mayor $group 
HAVING $activo Clasificacion != 'Resultados' AND Cuenta_de_Mayor != '' AND CargosAbonos != 0) 

UNION ALL";
			}
	//agrege account_id		
$myQuery .= "
(SELECT 
(case when (a.account_type = 2) then 'Pasivo' when (a.account_type = 3) then 'Capital' when (a.account_type = 4) then 'Resultados' end) AS Clasificacion, $Clasificacion
(SELECT description FROM cont_accounts WHERE account_code LIKE LEFT(a.account_code, 3) AND removed=0) AS Grupo, $Grupo
$Desc_Mayor 
$mayorOdetalle 
$id
(SELECT manual_code FROM cont_accounts WHERE account_id = a.main_father) AS Codigo,
(SELECT account_code FROM cont_accounts WHERE account_id = a.main_father) AS CodigoSistema,
n.description AS Naturaleza,
m.IdSucursal,
m.IdSegmento,

$presupuestal

REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 1),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 1 -1)) + 1),
       '.', '') AS h1,
REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 2),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 2 -1)) + 1),
       '.', '') AS h2,  
$masSplits 
";

if(!intval($tipo))
{
$myQuery .= "TRUNCATE(SUM(IF(m.TipoMovto = 'Cargo' AND p.fecha >= '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-01',m.Importe,0))-SUM(IF(m.TipoMovto = 'Abono' AND p.fecha >= '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-01',m.Importe,0)),4) AS CargosAbonosMes,";
}

$myQuery .= "TRUNCATE(SUM(IF(m.TipoMovto = 'Cargo',m.Importe,0))-SUM(IF(m.TipoMovto = 'Abono',m.Importe,0)),4) AS CargosAbonos,
TRUNCATE(SUM(IF(m.TipoMovto = 'Cargo' AND p.fecha < '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-01',m.Importe,0))-SUM(IF(m.TipoMovto = 'Abono' AND p.fecha < '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-01',m.Importe,0)),4) AS CargosAbonosAnterior

FROM cont_movimientos m 
INNER JOIN cont_polizas p ON p.id=m.IdPoliza 
LEFT JOIN cont_accounts a ON a.account_id = m.Cuenta
INNER JOIN cont_nature n ON n.nature_id = a.account_nature
WHERE 
m.Activo = 1
AND p.activo = 1
AND a.account_type < 5
AND p.fecha BETWEEN '".$ejercicio."-01-01' AND '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-31' 
$where 
$periodo13 

GROUP BY Cuenta_de_Mayor $group 
HAVING Clasificacion = 'Resultados' AND Cuenta_de_Mayor != '' AND CargosAbonos != 0) 
ORDER BY CAST(h1 AS UNSIGNED),CAST(h2 AS UNSIGNED) , $orden";
//print($myQuery);

			$this->connection_edu->select_db($db);
			$datos = $this->connection_edu->query($myQuery);
			return $datos;
		}	 

		function balanceGeneralReporteActivo($db,$ejercicio,$periodo,$sucursal,$segmento,$tipoCuenta,$idioma)
		{

			if($tipoCuenta == 'm')
			{
				$orden = 'Codigo';
				$masSplits = '';
			}
			//Si el tipo de codigo de la cuenta es automatico
			if($tipoCuenta == 'a')
			{
				$orden = '';
				$masSplits = '';
				for($i=3;$i<=8;$i++)
				{
					if($i!=8)
					{
						$orden .= "CAST(h$i AS UNSIGNED), ";
					}
					else
					{
						$orden .= "CAST(h$i AS UNSIGNED) ";
					}
					$masSplits .= "REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', $i), LENGTH(SUBSTRING_INDEX(a.account_code, '.', $i -1)) + 1),'.', '') AS h$i, ";
				}
			}
			$where = '';
			if(intval($segmento))
			{
				$where .= ' AND m.IdSegmento = '.$segmento;
			}
			if(intval($sucursal))
			{
				$where .= ' AND m.IdSucursal = '.$sucursal;
			}

			$Grupo = "";
			$Clasificacion = "";
			$Desc_Mayor = "(SELECT CONCAT(manual_code,' / ',description) FROM cont_accounts WHERE account_id = a.main_father) AS Cuenta_de_Mayor,";
			if(intval($idioma))
			{
				$Grupo = "(SELECT sec_desc FROM cont_accounts WHERE account_code LIKE LEFT(a.account_code, 3) AND removed=0) AS Grupo_Alt,";
				$Clasificacion = "(case when (a.account_type = 1) then 'Assets' end) AS Clasificacion_Alt,";
				$Desc_Mayor = "(SELECT CONCAT(manual_code,' / ',sec_desc) FROM cont_accounts WHERE account_id = a.main_father) AS Cuenta_de_Mayor,";
			}
			$myQuery = "SELECT 
(case when (a.account_type = 1) then 'Activo' end) AS Clasificacion, $Clasificacion
(SELECT description FROM cont_accounts WHERE account_code LIKE LEFT(a.account_code, 3) AND removed=0) AS Grupo, $Grupo
$Desc_Mayor 
(SELECT manual_code FROM cont_accounts WHERE account_id = a.main_father) AS Codigo,
(SELECT account_code FROM cont_accounts WHERE account_id = a.main_father) AS CodigoSistema,
n.description AS Naturaleza,
m.IdSucursal,
m.IdSegmento,(SELECT account_id FROM cont_accounts WHERE account_id = a.main_father AND removed=0) AS account_id,

REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 1),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 1 -1)) + 1),
       '.', '') AS h1,
REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 2),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 2 -1)) + 1),
       '.', '') AS h2,  
$masSplits 

TRUNCATE(SUM(IF(m.TipoMovto = 'Cargo',m.Importe,0))-SUM(IF(m.TipoMovto = 'Abono',m.Importe,0)),4) AS CargosAbonos

FROM cont_movimientos m 
INNER JOIN cont_polizas p ON p.id=m.IdPoliza 
LEFT JOIN cont_accounts a ON a.account_id = m.Cuenta
INNER JOIN cont_nature n ON n.nature_id = a.account_nature
WHERE 
m.Activo = 1
AND p.activo = 1
AND a.account_type < 5
AND p.fecha BETWEEN '2000-01-01' AND '".$ejercicio."-".sprintf('%02d', (intval($periodo)))."-31' 
$where 
AND p.idperiodo != 13 
GROUP BY Cuenta_de_Mayor 
HAVING Clasificacion = 'Activo' AND Cuenta_de_Mayor != '' AND CargosAbonos != 0
ORDER BY CAST(h1 AS UNSIGNED),CAST(h2 AS UNSIGNED), $orden
";
			$this->connection_edu->select_db($db);
			$datos = $this->connection_edu->query($myQuery);
			return $datos;
		}
		function guarda_grupo($vars)
		{
			$return = 0;
			if(!intval($vars['nuevo']))
			{
				$busca = $this->connection_edu->query("SELECT SUM(idgrupo) AS n FROM ugrupo WHERE descripcion = '".$vars['nombre']."'");
				$busca = $busca->fetch_object();
				$busca = $busca->n;
				if(!intval($busca))
				{
					$myQuery = "INSERT INTO ugrupo(idgrupo,descripcion,idcu) VALUES(0,'".$vars['nombre']."',".$vars['idcu'].");";
					$return = 1;
				}
			}
			else
			{
				$myQuery = "UPDATE ugrupo SET descripcion = '".$vars['nombre']."' WHERE idgrupo = ".$vars['nuevo'];
				$return = 1;
			}
			if($return)
				if(!$this->connection_edu->query($myQuery))
					$return = 2;

			return $return;

		}

		function eliminar_grupo($id)
		{
			if($this->connection_edu->query("DELETE FROM ugrupo WHERE idgrupo = ".$id))
				$this->connection_edu->query("DELETE FROM relacion_profesores_alumnos WHERE idgrupo = ".$id);
		}

		function eliminar_relacion($id){
			$myQuery = "DELETE FROM relacion_profesores_alumnos WHERE id = ".$id;
			$Result = $this->connection_edu->query($myQuery);
			return $myQuery;
		}

		function datos_grupo($id)
		{
			return $this->connection_edu->query("SELECT idgrupo,descripcion FROM ugrupo WHERE idgrupo = $id");
		}

		function traerListaInstancias($idprofe,$idempleado)
		{
			$myQuery = "SELECT (SELECT instancia FROM customer WHERE id = r.idalumno) AS instancia ";
			
			if(intval($idempleado) >= 3)
				$myQuery .= ", i.id FROM relacion_usuarios_instancias i INNER JOIN relacion_profesores_alumnos r ON r.id = i.idrelacion WHERE r.idprofesor = $idprofe AND i.idusuario = $idempleado;";
			else
				$myQuery .= ", 0 AS id, r.id AS idrel FROM relacion_profesores_alumnos r WHERE r.idprofesor = $idprofe";	

			$res = $this->connection_edu->query($myQuery);
			return $res;
		}

		function lista_empleados()
		{
			return $this->query("SELECT idempleado, usuario FROM accelog_usuarios;");
		}

		function elimina_rel_emp($idrel)
		{
			$this->connection_edu->query("DELETE FROM relacion_usuarios_instancias WHERE id = $idrel");
		}

		function valida_rel_emp($empleado,$inst_rel)
		{
			$where = "";
			for($i=0;$i<=count($inst_rel)-1;$i++)
			{
				if($i)
					$where .= " OR ";
				$where .= "i.idrelacion = ".$inst_rel[$i];
			}

			$myQuery = "SELECT (SELECT instancia FROM customer WHERE id = r.idalumno) AS instancia_r FROM relacion_usuarios_instancias i INNER JOIN relacion_profesores_alumnos r ON r.id = i.idrelacion WHERE i.idusuario = $empleado AND ($where);";
			$rels = $this->connection_edu->query($myQuery);
			$num = $rels->num_rows;
			$lista_rels = "";
			while($r = $rels->fetch_object())
				$lista_rels .= $r->instancia_r.", ";
			return $num."**//**".$lista_rels;
		}

		function guarda_rel_emp($empleado,$inst_rel)
		{
			$myQuery = "INSERT INTO relacion_usuarios_instancias(id,idusuario,idrelacion) ";
			for($i=0;$i<=count($inst_rel)-1;$i++)
			{
				if(!$i)
					$myQuery .= "VALUES";
				else
					$myQuery .= ",";
				$myQuery .= " (0,$empleado,".$inst_rel[$i].")";
			}
			
			return $this->connection_edu->query($myQuery.";");
		}

		function agregar_notificacion($form){
			$archivo  = $form['pdf'];
			$fecha 	  = $form['fecha'];
			$activa   = $form['activa'];
			$titulo   = $form['titulo'];
			$mensaje  = $form['mensaje'];
			$producto = $form['producto'];

			/* # Prdocuto #
				1: Acontia
				2: Foodware
				3: Appministra
				4: Xtructur */
			$myQuery = "INSERT INTO notificaciones(producto, titulo, mensaje, fecha, estatus, archivo) 
			VALUES (".$producto.", '".$titulo."', '".$mensaje."', '".$fecha."', $activa, '".$archivo."');";
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
			//return 1;
		}

		function obtener_noticias($tipo){
			$myQuery = "SELECT id, producto, titulo, mensaje, estatus, fecha, archivo FROM notificaciones ";
			if (!isset($tipo)) {
				$myQuery .= "WHERE estatus = 1 ";
			}
			$myQuery .= "ORDER BY id DESC ";
			if (!isset($tipo)) {
				$myQuery .= "LIMIT 5";
			}
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
		}

		function editar_notificacion($form){
			$id       = $form['id'];
			$archivo  = $form['pdf'];
			$fecha 	  = $form['fecha'];
			$activa   = $form['activa'];
			$titulo   = $form['titulo'];
			$mensaje  = $form['mensaje'];
			$producto = $form['producto'];

			$myQuery = "".
			"UPDATE notificaciones 
			SET
			producto = $producto,
			titulo = '".$titulo."',
			mensaje = '".$mensaje."',
			fecha = '".$fecha."',
			estatus = '".$activa."',
			archivo = '".$archivo."'
			WHERE id = ".$id.";";

			$Result = $this->connection_edu->query($myQuery);
			return $Result;
			//return $myQuery;
		}

		function quitar_pdf($id){
			$myQuery = "UPDATE notificaciones SET archivo = 0 WHERE id = $id;";
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
		}

		function obtener_notificacion($id){
			$myQuery = "SELECT * FROM notificaciones WHERE id = ".$id;
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
		}

		function ultima_conexion($idalumno)
		{
			$acceso = 'Nunca';
			$myQuery = "SELECT fechaultimoacceso FROM netwarstore.customer WHERE id = $idalumno";
			$ultimo = $this->connection_edu->query($myQuery);

			if($ultimo->num_rows)
			{
				$ultimo = $ultimo->fetch_assoc();
				if($ultimo['fechaultimoacceso'])
					$acceso = $ultimo['fechaultimoacceso'];
			}
			return $acceso;
		}

		#Eventos ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞
		function agregar_evento($form){
			$email = $form['correos'];
			$asunto = $form['asunto'];
			$titulo = $form['titulo'];
			$mensaje = $form['mensaje'];
			$estatus = $form['estatus'];
			$fecha_fin = $form['fecha_fin'];
			$instancia = $form['instancia'];
			$fecha_inicio = $form['fecha_inicio'];
			$lista_usuarios = $form['lista_usuarios'];

			$myQuery = "
			INSERT INTO evento(nombre, fecha_inicio, fecha_fin, estatus, idinstancia, emails, asunto, mensaje) 
			VALUES (
				'".$titulo."', 
				'".$fecha_inicio."', 
				'".$fecha_fin."', 
				$estatus,
				".$this->idprofesor($instancia).",
				'".$email."',
				'".$asunto."',
				'".$mensaje."'
			);";

			$Result = $this->connection_edu->query($myQuery);
			return $Result;	
			//return $myQuery;
		}

		function agregar_usuarios_evento($usuarios, $instancia, $actualizar){
			$exito;
			$id_instancia = $this->obtener_ultimo_evento($instancia);	
			$id_evento_row = $this->obtener_ultimo_evento($instancia);
			$id_evento = $id_evento_row->fetch_assoc();
			$Result = 0;

			if (isset($actualizar)) {
				# Si se esta actualizando una lista de usuarios que pertenecen a un evento
				# se borra el conjunto de usuarios pertenecientes a ese evento y se añade 
				# el nuevo array.
				$deleteQuery = "DELETE FROM eventos_usuarios WHERE idevento=$actualizar;";
				$Result = $this->connection_edu->query($deleteQuery);
				#Añadimos el array de usuarios nuevo.
				foreach ($usuarios as $usuario => $id_usuario) {
					$myQuery = "
						INSERT INTO eventos_usuarios(idusuario, idevento)
						VALUES (
							".$id_usuario.",
							".$actualizar."
						)
					";
					$Result = $this->connection_edu->query($myQuery);
					if ($Result == 1) {
						$exito = 1;
					} else {
						$exito = 0;
					}
				}
			} else {
				# Si no, extraemos el ultimo id de evento agregado por el gestor del 
				# Despacho virtual.
				foreach ($usuarios as $usuario => $id_usuario) {
					$myQuery = "
						INSERT INTO eventos_usuarios(idusuario, idevento)
						VALUES(
							".$id_usuario.",
							".$id_evento['id']."
						);
					";
					$Result = $this->connection_edu->query($myQuery);
					if ($Result == 1) {
						$exito = 1;
					} else {
						$exito = 0;
					}
				}
			}

			return $exito;
		}

		function obtener_eventos($id, $tipo, $instancia){
			//Situacionales
			$mostrar_inactivas = '';
			$relacion_evt_user = '';
			$buscar_id = '';

			//Formar sentencia según caracteristicas.
			if (!$tipo == 1) {
				$mostrar_inactivas = " AND NOT estatus = 0 ";
			}

			if (isset($id)) {
				$relacion_evt_user = "INNER JOIN eventos_usuarios AS eu ON evento.id = eu.idevento";
				$buscar_id = " AND eu.idusuario = ".$id;
			}

			$instancia = $this->idprofesor($instancia);

			//Query
			$myQuery = "
			SELECT
			evento.id,
			evento.nombre,
			evento.fecha_inicio,
			evento.fecha_fin,
			evento.estatus
			
			FROM evento
			".$relacion_evt_user." WHERE idinstancia = $instancia ".$mostrar_inactivas.$buscar_id;

			//Cerrar query
			$myQuery .= ";";

			$Result = $this->connection_edu->query($myQuery);
			return $Result;
			//return $myQuery;
		}

		function obtener_evento($id){
			$myQuery = "SELECT * FROM evento WHERE id = ".$id;
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
		}

		//Obtenemos los usuarios según el evento que se recibe.
		function obtener_usuarios_evento($idevento){
			$myQuery = "
			SELECT idusuario 
			FROM eventos_usuarios 
			WHERE idevento = ".$idevento;
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
			//return $myQuery;
		}
		
		//Obtiene el ultimo evento registrado de la instancia que se ingrese.
		function obtener_ultimo_evento($instancia){
			$id_instancia = $this->idprofesor($instancia);
			$myQuery = "SELECT id FROM evento WHERE idinstancia = '".$id_instancia."' ORDER BY id DESC LIMIT 1;";
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
		}

		function editar_evento($form){
			$id = $form['id'];
			$email = $form['correos'];
			$nombre = $form['titulo'];
			$asunto = $form['asunto'];
			$mensaje = $form['mensaje'];
			$estatus = $form['estatus'];
			$fecha_fin  = $form['fecha_fin'];
			$fecha_inicio = $form['fecha_inicio'];
			$lista_usuarios = $form['lista_usuarios'];

			$myQuery = "
			UPDATE evento 
			SET 
			nombre = '".$nombre."',
			fecha_inicio = '".$fecha_inicio."',
			fecha_fin = '".$fecha_fin."',
			estatus = '".$estatus."',
			emails = '".$email."',
			asunto = '".$asunto."',
			mensaje = '".$mensaje."'
			WHERE id = ".$id.";";

			$Result = $this->connection_edu->query($myQuery);
			return $Result;
			//return $myQuery;
		}

		function validar_pertenece_despacho($id){
			$myQuery = "SELECT id FROM relacion_profesores_alumnos WHERE idalumno = $id OR idprofesor = $id;";
			$Result = $this->connection_edu->query($myQuery);
			return $Result->fetch_assoc();
			//return $myQuery;
		}

		#Importar ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞

		function obtener_nombre_bd($nombre_instancia){
			$myQuery = "SELECT nombre_db, instancia FROM customer WHERE instancia = '".$nombre_instancia."';";
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
			//return $myQuery;
		}

		function importar_datos($actual, $importar){
			$tablas = [
				'cont_config', 
				'cont_polizas', 
				'cont_accounts', 
				'comun_cliente',
				'mrp_proveedor',
				'cont_segmentos',
				'cont_ejercicios',
				'cont_movimientos',
				'cont_rel_pol_prov',
				'cont_grupo_facturas',
				'cont_rel_desglose_iva',
				'bco_cuentas_bancarias'
			];
			$resultmsg = array();
			foreach ($tablas as $tabla => $valor) {
				$myQuery = "INSERT INTO $actual.$valor SELECT * FROM $importar.$valor;";
				$Result = $this->connection_edu->query($myQuery);
				if ($Result == 1) {
					$resultmsg[$valor]['estado'] = "Exito.";
				} else {
					$resultmsg[$valor]['estado'] = "Fallido.";
					$resultmsg[$valor]['query']  = $myQuery;
				}
			}
			//return $Result;
			return $resultmsg;
		}

		/* Queries para reiniciar la contabilidad de polizas en prueb.
		use _dbmlog0000014365;

		DELETE FROM mrp_proveedor;
		alter table mrp_proveedor AUTO_INCREMENT = 1;

		DELETE FROM comun_cliente;
		alter table comun_cliente AUTO_INCREMENT = 1;

		TRUNCATE TABLE cont_polizas;
		TRUNCATE TABLE cont_accounts;
		TRUNCATE TABLE cont_config;
		TRUNCATE TABLE cont_ejercicios;
		TRUNCATE TABLE cont_grupo_facturas;
		TRUNCATE TABLE cont_movimientos;
		TRUNCATE TABLE cont_rel_desglose_iva;
		TRUNCATE TABLE cont_rel_pol_prov;
		TRUNCATE TABLE cont_segmentos;
		*/

		function validar_estatus($mes, $anio, $instancia){
			$myQuery = "SELECT id 
			FROM relacion_estatus_alumnos
			WHERE mes = $mes
			AND anio = $anio
			AND id_instancia = $instancia;
			";
			$Result = $this->connection_edu->query($myQuery);
			return $Result->fetch_assoc();
		}

		function agregar_estatus($estatus, $mes, $anio, $instancia){
			$myQuery = "INSERT INTO relacion_estatus_alumnos(estatus, mes, anio, id_instancia)
			VALUES(
			$estatus,
			$mes,
			$anio,
			$instancia);
			";
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
		}

		function cambiar_estatus($estatus, $mes, $anio, $instancia){
			$myQuery = "
			UPDATE relacion_estatus_alumnos
			SET estatus = $estatus
			WHERE mes = $mes
			AND anio = $anio
			AND id_instancia = $instancia;
			";
			$Result = $this->connection_edu->query($myQuery);
			return $Result;
		}

		function obtener_estatus($mes,$anio,$instancia){
			$myQuery = "SELECT estatus
			FROM netwarstore.relacion_estatus_alumnos
			WHERE mes = $mes
			AND anio = $anio
			AND id_instancia = $instancia;
			";
			$Result = $this->connection_edu->query($myQuery);
			return $Result->fetch_assoc();
			//return $myQuery;
		}
}
?>