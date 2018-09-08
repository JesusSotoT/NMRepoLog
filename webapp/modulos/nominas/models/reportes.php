<?php
//ini_set("display_errors", 1); error_reporting(E_ALL);
class ReportesModel extends NominalibreModel
{

	function listadoNominas($fechaini,$fechafin,$nomEmple,$nominas)
	{
       //se le suma undia porq la hora de la fecha no me trae la del dia por ese tiempo
		$fechafin = strtotime ('+1 day' , strtotime ($fechafin) );
		$fechafin = date ('Y-m-j', $fechafin );
		$filtroEmpleado = "";
		if($nomEmple != ""){
			$filtroEmpleado = " AND em.idEmpleado = $nomEmple ";
		}

		$sql=$this->query("select em.email,nt.cancelado,nt.idNominatimbre,nt.UUID,nt.idEmpleado,nt.nombreXML,nt.fechainicial,nt.fechafinal,nt.diaspago,nt.subtotal,nt.descuento,nt.total,em.apellidoPaterno,em.apellidoMaterno,em.nombreEmpleado from nomi_nominas_timbradas as nt inner join nomi_empleados as em on nt.idEmpleado=em.idEmpleado where fechainicial >= '$fechaini' AND fechafinal <= '$fechafin' $filtroEmpleado order by nombreEmpleado asc;");
		return $sql;
	}

	/* I N C I D E N C I A S      I N T E R F A Z */
	// function rangoConceptos(){
// 
		// $sql=$this->query("select cp.idconfpre,cp.idtipo,cp.idconcepto,cp.valor,cp.importe,c.idconcepto,c.concepto,c.descripcion,p.idEmpleado,p.idnomp,p.idconfpre,p.valordias,p.importe,p.idNominatimbre,p.idcal
			// from nomi_conf_prenomina as cp
			// inner join nomi_calculo_prenomina as p on cp.idconfpre=p.idconfpre
			// inner join nomi_conceptos as c on cp.idconcepto=c.idconcepto where idnomp=27 
			// GROUP BY descripcion;");
// 
		// return $sql;
// 
	// }

	function empleados($empleados)
	{
		$sql = $this->query("select * from nomi_empleados where activo=-1");
		return $sql;
	}

//R E P O R T E   D E   E N T R A D A S   D E   E M P L E A D O S 
	function entradaSalidasEmple($fechaini,$fechafin,$nomEmple,$periodos,$nominas,$empleados){

		$filtroEmpleado    = "";
		$filtroperiodo     = "";
		$filtronomina      = "";
		$filtroEmpleados   = "";

        //echo "Periodo: $periodos, Nomina: $nominas, Empleado: $empleados";

		if($nomEmple != ''){
			$filtroEmpleado = "AND em.idEmpleado = $nomEmple";
		}

		if($periodos != '*'){
			$filtroperiodo = "AND np.idtipop = $periodos";
		}

		if($nominas != '*'){
			$filtronomina = "AND np.idnomp = $nominas";
			$filtronomi="idnomp=np.idnomp and";
		}


		if($empleados != '*'){
			$filtroEmpleados = "AND em.idEmpleado = $empleados";
		}
		$sqlgeneral = "SELECT (SELECT COUNT(idnomp)  FROM nomi_registro_entradas where $filtronomi idEmpleado=em.idEmpleado)AS numerodias,np.idnomp,np.autorizado,np.idtipop,np.numnomina,np.fechainicio, np.fechafin,pn.nombre,pn.idtipop,em.apellidoPaterno, em.apellidoMaterno,em.nombreEmpleado,em.idtipop,em.idEmpleado,em.codigo,em.nss,em.rfc,em.curp, re.horaentrada,re.iniciocomida,re.fincomida,re.horasalida,re.idEmpleado,re.fecha,re.dia,re.idnomp,re.idregistro from nomi_registro_entradas as re inner join nomi_empleados as em on em.idEmpleado=re.idEmpleado inner join nomi_tiposdeperiodos as pn on pn.idtipop=em.idtipop inner join nomi_nominasperiodo as np on np.idnomp =re.idnomp";

		if($fechaini!='' && $fechafin!='')
		{
			$sql = $this->query("$sqlgeneral where re.fecha between '$fechaini' and '$fechafin' $filtroEmpleado order by  nombreEmpleado asc,re.fecha asc");
		}
		else
		{
			if($periodos!='')
			{

				$sql = $this->query("$sqlgeneral where 1=1 $filtroperiodo $filtronomina $filtroEmpleados order by nombreEmpleado asc,re.fecha asc");
			}
		}
		return $sql;
	}


	function nominasActivas(){
		$sql = $this->queryarray("select c.idtipop,np.idtipop,np.fechainicio,np.fechafin from nomi_nominasperiodo np inner join nomi_configuracion c on   c.idtipop=np.idtipop where autorizado=0 limit 1");
		return $sql;
	}

	function cargaPeriodo($idtipop){
		$sql = $this->query("select * from nomi_nominasperiodo where idtipop='$idtipop'");
		return $sql;
	}

	function cargaPeriodoD($tipo, $idnomp){

		if($tipo=='*'){
			$filtro='';
		}else{
			$filtro=" and idtipop='$tipo' ";
		}
		if($idnomp==''){
			$filtro2='';
		}else{
			$filtro2=" and fechainicio > (select fechainicio from nomi_nominasperiodo where idnomp ='$idnomp' ) ";
		}

		$sql = $this->queryarray("select * from nomi_nominasperiodo where (1=1) ".$filtro.$filtro2." ;");
		if($sql['total']>0){
			$JSON=array('success'=>1, 'data'=>$sql['rows']);
		}else{
			$JSON=array('success'=>0);
		}
		echo json_encode($JSON);
	}



	function listadoHoras($vali,$input){

		$horas = explode("_", $input);
		$id=$horas[0];
		$col=$horas[1];
		$colMod='';

		if($col==1){
			$colMod='horaentrada';
		}
		if($col==2){
			$colMod='iniciocomida';
		}
		if($col==3){
			$colMod='fincomida';
		}
		if($col==4){
			$colMod='horasalida';
		}


if ($vali=='') {
    
	$sql="UPDATE nomi_registro_entradas set ".$colMod."=NULL where idregistro='$id';";
	
}else{

    $sql="UPDATE nomi_registro_entradas set ".$colMod."='$vali' where idregistro='$id';";
}

		if($this->multi_query($sql)){
			return 1;
		}else{
			return 0;
		}
	}

	function tipoperiodo(){
		$sql = $this->query("select * from nomi_tiposdeperiodos where activo=1 order by nombre asc");
		return $sql;
	}

	function incidenciasfiltro(){

		$sql=$this->query("select idtipoincidencia,clave,nombre from nomi_tipoincidencias");
		return $sql;
	}

        /// R E P O R T E   DE   S O B R E R E C I B O/(P R E N O M I N A)
	function cargaEncabezadosPercepcionesFiltros($idtipop, $idnomp, $idEmpleado){

		if($idtipop != '*'){
			$filtroperiodo = " AND np.idtipop = $idtipop";
		}

		if($idnomp != '*'){
			$filtronomina = " AND cap.idnomp = $idnomp";
		}

		if($idEmpleado != '*'){
			$filtroEmpleados = " AND em.idEmpleado = $idEmpleado";
		}

		$sqlx="select distinct cp.idConcepto, c.Descripcion, c.idtipo
		From nomi_conf_prenomina cp Inner Join nomi_calculo_prenomina cap on cap.idconfpre = cp.idConcepto
		Inner Join nomi_conceptos c on cp.idConcepto =c.idConcepto 
		Inner join nomi_empleados em
		on em.idEmpleado=cap.idEmpleado
		Inner Join nomi_nominasperiodo np
		on np.idnomp = cap.idnomp
		Where (1=1)".$filtroperiodo.$filtronomina.$filtroEmpleados." order by cp.idConcepto";
		$sql = $this->query($sqlx);
		return $sql;
	}

	function cargaPercepcionesFiltros($idtipop,$idnomp,$idEmpleado){

//echo "Periodo: $idtipop, Nomina: $idnomp, Empleado: $idEmpleado, codigo: $codigode, codigodos: $codigoal";

		if($idEmpleado!='')
		{
			$sql = $this->query("call traerDatosCalculoPrenomina ('$idtipop','$idnomp','$idEmpleado')");
		}
		return $sql;
	}

	function infoEmpresa(){
		$sql=$this->query("select * from organizaciones;");
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			0;
		}
	}
	function logo()
	{
		$myQuery = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
		$logo = $this->query($myQuery);
		$logo = $logo->fetch_assoc();
		return $logo['logoempresa'];
	}
	function cargaPerceFiltros($idtipop,$idnomp,$idEmpleado,$codigode,$codigoal,$origen){
		$filtroperiodo="";
		$filtrorigen="";

		if($idtipop != '*' &&  $idtipop != ''){
			$filtroperiodo = " AND np.idtipop = $idtipop";
		}

		if($idnomp != '*' && $idnomp != ''){
			$filtronomina = " AND cp.idnomp = $idnomp";
		}

		if($idEmpleado != '*' && $idEmpleado != ''  ){
			$filtroEmpleados = " AND cp.idEmpleado = $idEmpleado";
		}

		if ($origen!='') {
			$filtrorigen=" AND cp.origen=$origen";
		}

		$sqlY="SELECT IFNULL(cp.salario,e.salario)as salario,cp.diasvac,cp.diasfestivo,cp.diaslabproporcion,
	CASE c.idtipo
		WHEN 1 THEN cp.importe
	ELSE 0 
			END AS percepciones,
	CASE c.idtipo
		WHEN 2 THEN cp.importe
	ELSE 0 
			END AS deducciones,
	CASE cp.origen
		WHEN 0 THEN  'Prenomina' 
		WHEN 1 THEN  'Aguinaldo'
		WHEN 2 THEN  'Finiquito'
		WHEN 3 THEN  'PTU'
	ELSE '' 
	END AS origendes,e.idempleado,e.codigo,e.idDep,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.idtipop,e.nss,e.rfc,e.curp,e.idturno,cp.idEmpleado,cp.idnomp,cp.idconfpre,cp.importe,cp.origen,cp.diaspagados AS pagadosdias,cp.diaslaborados,cp.aplicarecibo,c.idconcepto,c.concepto,c.descripcion,c.idtipo,c.activo,np.idnomp,np.idtipop,np.numnomina,np.fechainicio,np.fechafin,np.autorizado,tp.idtipop,tp.nombre,nt.idturno,nt.horas,nt.idjornada
FROM nomi_empleados e
LEFT JOIN nomi_calculo_prenomina cp
ON  e.idempleado=cp.idEmpleado
INNER JOIN nomi_conceptos c
ON cp.idconfpre=c.idconcepto
INNER JOIN nomi_nominasperiodo np
ON cp.idnomp=np.idnomp
INNER JOIN nomi_tiposdeperiodos tp
ON tp.idtipop=np.idtipop
LEFT JOIN nomi_turno nt
ON   e.idturno=nt.idturno
WHERE (c.idtipo=1 || c.idtipo=4) and cp.aplicarecibo = 1
";

		if ($codigode!='' && $codigoal!='')
		{  
			$sql = $this->query("$sqlY and e.idEmpleado between $codigode and $codigoal".$filtroperiodo.$filtronomina. " AND cp.aplicarecibo=1 order by  np.numnomina asc,e.idempleado asc");
		}

		else 
			if ($origen!='*') {
				
				$sql = $this->query("$sqlY".$filtroEmpleados.$filtroperiodo.$filtronomina.$filtrorigen." order by  cp.origen asc,cp.idnomp asc,e.idempleado asc");	 
			}
			
			else{
				if($idtipop!=''){

					$sql = $this->query("$sqlY".$filtroEmpleados.$filtroperiodo.$filtronomina." AND cp.aplicarecibo=1 order by  cp.origen asc,e.idempleado asc");
				}
			}
			return  $sql;
		}
		function cargaDeduccionFiltros($idtipop,$idnomp,$idEmpleado){


			if($idtipop != '*'){
				$filtroperiodo = " AND np.idtipop = $idtipop";
			}

			if($idnomp != '*'){
				$filtronomina = " AND cp.idnomp = $idnomp";
			}

			if($idEmpleado != '*'){
				$filtroEmpleados = " AND cp.idEmpleado = $idEmpleado";
			}

			$sqlY="SELECT cp.diasvac,cp.diasfestivo,cp.diaslabproporcion,e.idempleado,e.codigo,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.salario,e.idtipop,e.nss,e.rfc,e.curp,cp.idEmpleado,cp.idnomp,cp.idconfpre,cp.importe,cp.origen,cp.diaspagados,cp.aplicarecibo,c.idconcepto,c.concepto,c.descripcion,c.idtipo,c.activo,np.idnomp,np.idtipop,np.numnomina,np.fechainicio,np.fechafin,np.autorizado,tp.idtipop,tp.nombre
			from nomi_empleados e
			INNER JOIN nomi_calculo_prenomina cp
			ON  e.idempleado=cp.idEmpleado
			INNER JOIN nomi_conceptos c
			ON cp.idconfpre=c.idconcepto
			INNER JOIN nomi_nominasperiodo np
			ON cp.idnomp=np.idnomp
			INNER JOIN nomi_tiposdeperiodos tp
			ON tp.idtipop=np.idtipop  
			WHERE  cp.aplicarecibo = 1 and c.idtipo=2".$filtroperiodo.$filtronomina.$filtroEmpleados." order by np.numnomina asc,e.idempleado asc";
			$sql = $this->query($sqlY);
			return $sql;
		}

//T E R M I N A  T O D O  D E  R E P O R T E   P R E N O M I N A

		function incidencias($fechaini,$fechafin,$nomEmple,$periodos,$nominas,$empleados,$incidencia,$incidenciados ){

			$filtroEmpleado      = "";
			$filtroperiodo       = "";
			$filtronomina        = "";
			$filtroEmpleados     = "";
			$filtroIncidencia    = "";
			$filtroIncidenciados = "";

			//echo "Periodo: $periodos, Nomina: $nominas, Empleado: $empleados,Incidencia:$incidencia ";

			if($nomEmple != ''){
				$filtroEmpleado = "AND em.idEmpleado = $nomEmple";
			}

			if($periodos != '*'){
				$filtroperiodo = "AND pn.idtipop = $periodos";
			}

			if($nominas != '*'){
				$filtronomina = "AND pn.idnomp = $nominas";
			}

			if($empleados != '*'){
				$filtroEmpleados = "AND em.idEmpleado = $empleados";
			}

			if ($incidencia!='*') {
				$filtroIncidencia =	"AND ti.idtipoincidencia=$incidencia";
			}

			if ($incidenciados!='*') {
				$filtroIncidenciados =	"AND ti.idtipoincidencia=$incidenciados";

			}

			if ($incidencia!='*') {
				$filtroInciVac= "AND ci.idtipoincidencia=$incidencia";
			}

			if ($incidenciados!='*') {
				$filtroInciVaca= "AND ci.idtipoincidencia=$incidenciados";
			}

			$sqlgeneralinci="SELECT case ci.autorizado when 0 then 'Activa' when 1 then 'Aplicada' end as autorizadoletras, np.idnomp, np.autorizado, np.numnomina, np.fechainicio, np.fechafin, pn.nombre as nom, pn.idtipop, em.idtipop, em.idEmpleado, ti.idtipoincidencia, ti.nombre, ci.idempleado, ci.fechaseleccion, ci.idtipoincidencia, ci.idnomp, ci.autorizado, em.nombreEmpleado, em.apellidoPaterno, em.apellidoMaterno, 0 as 'DiasAutorizados', ci.fechaseleccion as fechafinal, ci.idsobrecibo, ci.sobrerecibo 
			from nomi_claveincidencias as ci left join nomi_empleados as em on em.idEmpleado=ci.idempleado 
			left join nomi_tiposdeperiodos as pn on pn.idtipop=em.idtipop 
			left join nomi_nominasperiodo as np on np.idnomp=ci.idnomp 
			left join nomi_tipoincidencias as ti on ci.idtipoincidencia=ti.idtipoincidencia 
			where ci.sobrerecibo=0";

			$sqlgeneralincidos="SELECT case ci.autorizado when 0 then 'Activa' when 1 then 'Aplicada' end as autorizadoletras, np.idnomp, np.autorizado, np.numnomina, np.fechainicio, np.fechafin, pn.nombre, pn.idtipop, em.idtipop, em.idEmpleado, ti.idtipoincidencia, ti.nombre, sr.idEmpleado, sr.fechainicio, sr.idtipoincidencia, sr.idnomp, ci.autorizado, em.nombreEmpleado, em.apellidoPaterno, em.apellidoMaterno, sr.diasautorizados, date_add(sr.fechainicio,interval diasautorizados day) as fechafinal, ci.idsobrecibo, ci.sobrerecibo from nomi_incapacidades_sobrerecibo as sr
			inner join nomi_empleados as em on em.idEmpleado=sr.idEmpleado 
			inner join nomi_claveincidencias as ci on ci.idsobrecibo=sr.idincapacidadsobre 
			inner join nomi_nominasperiodo as np on np.idnomp=sr.idnomp 
			inner join nomi_tiposdeperiodos as pn on pn.idtipop=np.idtipop 
			inner join nomi_tipoincidencias as ti on sr.idtipoincidencia=ti.idtipoincidencia 
			where ci.sobrerecibo=1";

			$sqlgeneralincitres="SELECT case ci.autorizado when 0 then 'Activa' when 1 then 'Aplicada' end as autorizadoletras, np.idnomp, np.autorizado, np.numnomina, np.fechainicio, np.fechafin, pn.nombre, pn.idtipop, em.idtipop, em.idEmpleado, ci.idtipoincidencia, (SELECT nombre FROM nomi_tipoincidencias WHERE idconsiderado=3), vs.idEmpleado, ci.fechaseleccion, (SELECT idtipoincidencia 
			FROM nomi_tipoincidencias
			WHERE idconsiderado=3), vs.idnomp, ci.autorizado, em.nombreEmpleado, em.apellidoPaterno, em.apellidoMaterno, vs.diasvacaciones, vs.fechafinal, ci.idsobrecibo,sobrerecibo from nomi_vacaciones_sobrerecibo as vs 
			inner join nomi_empleados as em on em.idEmpleado=vs.idEmpleado 
			inner join nomi_claveincidencias as ci on ci.idsobrecibo=vs.idvacasobrerecibo 
			inner join nomi_nominasperiodo as np on np.idnomp=vs.idnomp 
			left join nomi_tiposdeperiodos as pn on pn.idtipop=np.idtipop 
			where sobrerecibo=2";

			if($fechaini!='' && $fechafin!='')
			{
				
				$sql = $this->query("
					$sqlgeneralinci between '$fechaini'     and '$fechafin' $filtroEmpleado $filtroIncidencia 
					union all 
					$sqlgeneralincidos between '$fechaini'  and '$fechafin' $filtroEmpleado $filtroIncidencia 
					union all 
					$sqlgeneralincitres between '$fechaini' and '$fechafin' $filtroEmpleado $filtroInciVac
					");
			}

			else if($periodos!='' && $periodos != '*' )
			{
				
				$sql = $this->query("
					$sqlgeneralinci $filtroperiodo $filtronomina $filtroEmpleados $filtroIncidenciado 
					union all 
					$sqlgeneralincidos $filtroperiodo $filtronomina $filtroEmpleados $filtroIncidenciados 
					union all 
					$sqlgeneralincitres $filtroperiodo $filtronomina $filtroEmpleados $filtroInciVaca
					");		
			}
			else{
				if($periodos=='*'  && $empleados== '*' && $incidencia=='*'){
					
					$sql = $this->query("$sqlgeneralinci union all $sqlgeneralincidos union all 
						$sqlgeneralincitres 
						");
				}
			}

			return $sql;
		}



		/*R E P O R T E   D E   A C U M U L A D O*/

		function reporteAcumulado($nomEmple,$periodos,$nominas,$nominados,$origen){

			$filtroEmpleado    = "";
			$filtroperiodo     = "";
			$filtronomina      = "";
			$filtronominados   = "";
			$filtrorigen       = "";

			// echo "Periodo: $periodos, Nomina: $nominas, Nominados: $nominados, Empleado: $nomEmple,origen:$origen";

			if($nomEmple != '*'){
				$filtroEmpleado = "AND em.idEmpleado = $nomEmple";
			}

			if($periodos != '*'){
				$filtroperiodo = "AND np.idtipop = $periodos";
			}

			if($nominas != '*'){
				$filtronomina = "between $nominas";
			}

			if($nominados != '*'){
				$filtronominados = "AND $nominados";
			}

			if ($origen!='*') {
				$filtrorigen="AND cp.origen=$origen";
			}
/*aqui subsidio queda dentro de percepciones porq se suma a estas
 *  aunq en realidad es otro pago ver si mas adelante 
 * lo cambiamos o se queda a si
 */
			$sqlgeneral = "SELECT CASE c.idtipo
			WHEN 1 THEN cp.importe
			WHEN 4 THEN cp.importe
			ELSE 0 
			END AS percepciones,
			CASE c.idtipo
			WHEN 2 THEN cp.importe
			ELSE 0 
			END AS deducciones,
			CASE cp.origen
			when 0 THEN  'prenomina' 
			when 1 THEN  'aguinaldo'
			when 2 then  'finiquito'
			when 3 then  'PTU'
			else '' End as origen,
				cp.idEmpleado,cp.idcal,cp.idnomp,cp.idconfpre,c.idconcepto,c.idtipo,c.descripcion,cp.importe,cp.gravado,cp.exento,cp.idNominatimbre,cp.diaspagados,cp.diaslaborados,cp.valordias,cp.aplicarecibo,tp.idtipop,tp.fechainicio as fa,tp.nombre,np.idnomp,np.idtipop,np.numnomina,np.fechainicio,np.fechafin,em.apellidoPaterno,em.apellidoMaterno,em.nombreEmpleado,em.idtipop,em.idEmpleado,em.nss,em.rfc,em.curp
			FROM nomi_calculo_prenomina  cp 
			inner JOIN
			nomi_nominasperiodo np
			ON np.idnomp=cp.idnomp
			INNER JOIN nomi_empleados em
			ON cp.idEmpleado=em.idEmpleado
			INNER JOIN 
			nomi_tiposdeperiodos tp
			ON np.idtipop=tp.idtipop
			inner JOIN nomi_conceptos c
			ON c.idconcepto=cp.idconfpre";



			if($periodos!='' && $periodos!='3')
			{
				
				$sql = $this->query("$sqlgeneral where 1=1  $filtroperiodo and  np.idnomp   $filtronomina   $filtronominados $filtroEmpleado  $filtrorigen ORDER BY  cp.idEmpleado ASC,cp.idconfpre ASC ");
			}

			else if ($periodos=='3' && $nominas != '*') {
				
				$sql = $this->query("$sqlgeneral where 1=1  $filtroperiodo and  np.idnomp   and $nominas
					$filtroEmpleado  $filtrorigen ORDER BY  cp.idEmpleado ASC,cp.idconfpre ASC ");
				
			}

			else if ($periodos=='3' && $nominas == '*') {
				$sql = $this->query("$sqlgeneral where 1=1  $filtroperiodo and  np.idnomp   $filtronomina   $filtronominados $filtroEmpleado  $filtrorigen ORDER BY  cp.idEmpleado ASC,cp.idconfpre ASC ");
				
			}
			return $sql;
		}

		/*T E R M I N A   R E P O R T E   A C U M U L A D O */


// R E S U M  E N   A N A L I T I C O   P O R   D E P A R T A M E N T O 
function departamentos(){

$sql = $this->query("SELECT * FROM nomi_departamento");

return $sql;
}


function resumenAnaliticoDep($periodo,$nominauno,$nominados,$depart,$nomi){


// echo "string";
// echo "Periodo: $periodo, Nomina: $nominauno, Nominados: $nominados, depa: $depart,nomiprincipal:$nomi";

$filtroperiodo     = "";
$filtronomina      = "";
$filtronominados   = "";
$filtrodep         = "";
$filtronominauno   = "";

if($periodo != '*'){
$filtroperiodo = "AND np.idtipop = $periodo";
}

            if($nomi != '*'){
$filtronominauno = "and cp.idnomp in($nomi)";
}


if($nominas != '*'){
$filtronomina = "between $nominauno";
}

if($nominados != '*'){
$filtronominados = "AND $nominados";
}

if($depart != '*'){
$filtrodep = "AND e.idDep in($depart)";
}







$sql="SELECT (select sum( CASE when c.idtipo=1 then importe else 0 END))as perc, (select sum( CASE when c.idtipo=2 then importe else 0 END))as deduc, e.idEmpleado,e.nombreEmpleado,e.idDep,dep.idDep,cp.idEmpleado,cp.idnomp,cp.idconfpre,cp.importe,c.idconcepto,c.concepto,c.descripcion,c.idtipo
from nomi_empleados e
left join nomi_departamento dep 
on dep.idDep=e.idDep 
right join nomi_calculo_prenomina cp
on cp.idEmpleado=e.idEmpleado
left join nomi_conceptos c
on cp.idConfpre=c.idconcepto"; 


// $sql="SELECT (select sum(importe)), e.idEmpleado,e.nombreEmpleado,e.idDep,dep.idDep,cp.idEmpleado,cp.idnomp,cp.idconfpre,cp.importe,c.idconcepto,c.concepto,c.descripcion,c.idtipo
// from nomi_empleados e
// left join nomi_departamento dep 
// on dep.idDep=e.idDep 
// right join nomi_calculo_prenomina cp
// on cp.idEmpleado=e.idEmpleado
// left join nomi_conceptos c
// on cp.idConfpre=c.idconcepto
// where cp.idEmpleado=7 and c.idtipo=1 ORDER BY  e.idEmpleado ASC group by idconcepto; ";
if($periodo!='' && $depart!='')
{

//echo "$sql where 1=1  $filtroperiodo  $filtronominauno    $filtrodep   group by idconcepto ORDER BY  e.idEmpleado ASC ";

$sql = $this->query("$sql where 1=1  $filtroperiodo  $filtronominauno   $filtrodep   group by idconcepto ORDER BY  e.idEmpleado ASC ");
}


return $sql;
}

//R E P O R T E   P R E N O M I N A  D E T A L L A D O 

function reportePrenominaDetallado($nomEmple,$periodos,$nominas){

	$filtroEmpleado    = "";
	$filtroperiodo     = "";
	$filtronomina      = "";


//echo "Periodo: $periodos, Nomina: $nominas, Empleado: $nomEmple";

	if($nomEmple != '*'){
		$filtroEmpleado = "AND idEmpleado = $nomEmple";
	}

	if($periodos != '*'){
		$filtroperiodo = "where idtipop = $periodos";
	}

	if($nominas != '*'){
		$filtronomina = "AND idnomp  =$nominas";
	}


	$sqlgeneral ="SELECT *,idnomp,idconfpre,idEmpleado, 
			(CASE WHEN (base) then (base+entregado-retenido-imss+primavacacional+vacaciones-infonavit) else 0 end) AS neto, 
			(CASE when (sueldoneto) then(sueldoneto-infonavit)else 0 end) suelinfon,			
			(CASE WHEN (minutosdeben>0) then ((salarioHora/60)*(minutosdeben)) else 0 end)as totalarestar,	 
			(CASE WHEN  (minutosextras>0) then ((salarioHora/60)*(minutosextras)) else 0 end)as tiempoextra,
					
            (CASE WHEN (minutosextras>0) then ((base+entregado-retenido-imss+primavacacional+vacaciones-infonavit)+((salarioHora/60)*(minutosextras))) 
            else ((base+entregado-retenido-imss+primavacacional+vacaciones-infonavit)-(salarioHora/60)*(minutosdeben)) end)as totalfinal
			
			from (SELECT cp.idconfpre,cp.idEmpleado,
			
		    (SELECT IFNULL((select COUNT(re.idnomp) from `nomi_registro_entradas` re where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` ),0)) as DiasChe,
		    (SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(101)),0)) as sueldo,
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(103)),0)) as primaAsistencia,
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(104)),0)) as puntualidad, 
			(SELECT IFNULL((select valorneto from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(110)),0)) as ispt, 
			(SELECT IFNULL((select valorneto from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(105)),0)) as subsid,
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and aplicarecibo=1 and idconfpre in(110)),0)) as retenido, 
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and aplicarecibo=1 and idconfpre in(105)),0)) as entregado, 
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(109)),0)) as imss, 
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(107)),0)) as primavacacional,
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(18)),0)) as vacaciones,
			(SELECT(CASE when cp.diasvac then cp.diasvac else 0 END))as diasvacaciones, 
			(SELECT IFNULL((select importe from `nomi_calculo_prenomina` WHERE idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` AND idconfpre in(111) ),0)) as infonavit,
			(e.salario/nt.horasdetalle)as salarioHora,
			(case when importe then(select sum(importe) from `nomi_calculo_prenomina` where idEmpleado=cp.idEmpleado and idnomp=cp.`idnomp` and idconfpre in(101,103,104)) else 0 end) as base,
			

			 e.salario,e.sueldoneto,e.codigo,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,
			 e.nombreEmpleado,/* e.salario,nt.horas, */e.nss,e.rfc,e.curp,e.idtipop,e.sbcfija,cp.idnomp,
			 re.horaentrada,re.iniciocomida,re.fincomida,re.horasalida,
			 diferencias.minutosdemas, diferencias.minutosdemenos,nt.horasdetalle as horas,
			 (CASE WHEN (minutosdemas>minutosdemenos) THEN (minutosdemas-minutosdemenos) else 0 end)as minutosextras,
			(CASE WHEN (minutosdemas<minutosdemenos)  THEN (minutosdemenos-minutosdemas) else 0 end)as minutosdeben
				 from nomi_empleados e 
				 left join nomi_registro_entradas re 
				 on e.idEmpleado=re.idEmpleado
				 right join nomi_calculo_prenomina cp 
				 on e.idEmpleado=cp.idEmpleado
				 left join nomi_nominasperiodo np 
				 on np.idnomp=cp.idnomp 
				 left join nomi_tiposdeperiodos tp 
				 on tp.idtipop=np.idtipop 
				 left join nomi_turno nt 
				 on nt.idturno=e.idturno 
left join (
	select 	
		sum(case when a.minutosdiferenciaentrada >0 then a.minutosdiferenciaentrada else 0 end ) + 	
	   		sum(case when minutosdiferenciacomida >0 then minutosdiferenciacomida else 0 end ) + 
	   		sum(case when minutosdiferenciasalida >0 then minutosdiferenciasalida else 0 end ) as minutosdemas	,
	   (sum(case when minutosdiferenciaentrada <0 then minutosdiferenciaentrada else 0 end ) + 	
	   		sum(case when minutosdiferenciacomida <0 then minutosdiferenciacomida else 0 end ) + 
	   		sum(case when minutosdiferenciasalida <0 then minutosdiferenciasalida else 0 end )) * (-1) as minutosdemenos,a.idEmpleado, a.idnomp	
	From(
		select 
		TIMESTAMPDIFF (MINUTE, CONVERT( CONCAT(fecha, ' ' , horaentrada), datetime ), CONVERT( CONCAT(fecha, ' 08:00:00' ), datetime )) as minutosdiferenciaentrada ,
			case when dia !='Vie' then
				(TIMESTAMPDIFF (MINUTE, CONVERT( CONCAT(fecha, ' ' , iniciocomida), datetime ), CONVERT( CONCAT(fecha, ' ', fincomida ), datetime )) - 60) * (-1) 
			else 0 End as minutosdiferenciacomida,		 
		TIMESTAMPDIFF (MINUTE, CONVERT( CONCAT(fecha, ' ' , horasalida), datetime ), CONVERT( CONCAT(fecha, (case when dia !='Vie' Then ' 19:00:00' else ' 15:00:00' end) ), datetime )) 		* 	(-1) as minutosdiferenciasalida , 
		re.* from nomi_registro_entradas re 
	) as a	group by a.idEmpleado, a.idnomp
)as diferencias on diferencias.idempleado = cp.idempleado and diferencias.idnomp = cp.idnomp
where cp.idconfpre in (101,103,104,110,105,109,107,18,111) group by cp.idnomp,cp.idEmpleado order by cp.idEmpleado ) tab";
if($nominas!='')
{
$sql = $this->query("$sqlgeneral  $filtroperiodo  $filtronomina   $filtroEmpleado ;");
}

return $sql;
}

function sumasConceptos($nomEmple,$periodos,$nominas){
	$filtroEmpleado    = "";
	$filtroperiodo     = "";
	$filtronomina      = "";

	if($nomEmple != '*'){
	$filtroEmpleado = "AND cp.idEmpleado = $nomEmple";
	}

	if($periodos != '*'){
	$filtroperiodo = "AND tp.idtipop = $periodos";
	}

	if($nominas != '*'){
	$filtronomina = "AND n.idnomp  =$nominas";
	}

	$sqlgeneral ="	
 			SELECT  sum(cp.importe) as importe, c.concepto, c.descripcion 
		 	 from nomi_conceptos c 
		 	 left join nomi_calculo_prenomina cp
		 	 on  cp.idconfpre=c.idconcepto
		 	 inner join  nomi_nominasperiodo n
		 	 on cp.idnomp=n.idnomp	 
		 	 inner join nomi_tiposdeperiodos tp 
		 	 on  n.idtipop=tp.idtipop
		 	 inner join nomi_empleados em
		 	 on cp.idEmpleado=em.idEmpleado
		 	 where  cp.idconfpre not in (101,103,104,110,105,109,107,18,111)";

	if($nominas!='')
	{          
	$sql = $this->query("$sqlgeneral  $filtroperiodo  $filtronomina $filtroEmpleado   group by c.concepto;");
	
	}
	return $sql;

}

function tabladetallepre( $nomEmple,$periodos,$nominas){

$filtroEmpleado    = "";
$filtroperiodo     = "";
$filtronomina      = "";

if($nomEmple != '*'){
$filtroEmpleado = "AND cp.idEmpleado = $nomEmple";
}

if($periodos != '*'){
$filtroperiodo = "AND tp.idtipop = $periodos";
}

if($nominas != '*'){
$filtronomina = "AND n.idnomp  =$nominas";
}


$sqlgeneral ="	
 SELECT cp.idEmpleado,cp.idnomp,cp.idconfpre,cp.importe,c.idconcepto,c.concepto,c.descripcion,c.idtipo,tp.idtipop,tp.nombre,em.idEmpleado,em.nombreEmpleado
 	 from nomi_calculo_prenomina cp
 	 inner join nomi_conceptos c
 	 on  cp.idconfpre=c.idconcepto
 	 inner join  nomi_nominasperiodo n
 	 on cp.idnomp=n.idnomp	 
 	 inner join nomi_tiposdeperiodos tp 
 	 on  n.idtipop=tp.idtipop
 	 inner join nomi_empleados em
 	 on cp.idEmpleado=em.idEmpleado
 	 where  cp.idconfpre not in (101,103,104,110,105,109,107,18,111) 
 	 ";

if($nominas!='')
{          
	$sql = $this->query("$sqlgeneral  $filtroperiodo  $filtronomina $filtroEmpleado ;");
	
	}
	return $sql;
}

function infoRegPatronalRecibo(){
		$sql=$this->query("select r.registro from nomi_registropatronal r,nomi_configuracion c
 				where c.idregistrop=r.idregistrop and r.activo=-1;");
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			0;
		}
	}



}
?>