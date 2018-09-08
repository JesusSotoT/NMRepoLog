<?php

// ini_set("display_errors", 1); error_reporting(E_ALL);
class SobrereciboModel extends PrenominaModel{

	function actualizarpercepcionesDeducciones($vali,$input){

		$perce = explode("_", $input);

		$id =$perce[0];
		$col=$perce[1];
		$colMod='';

		if($col==1){
			$colMod='importe';
		}


		$sql="UPDATE nomi_calculo_prenomina set ".$colMod."='$vali' where idcal='$id';";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}

	}
	function actualizarDias($idNom,$valor,$empleado,$campo){

		
		$sql="UPDATE nomi_calculo_prenomina set ".$campo."=$valor where idnomp=$idNom and idEmpleado=$empleado;";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}

	}
	function cargaPercepciones($idEmpleado,$idnomp){

$sql=$this->query("SELECT p.diaspagados as pagadosdias,cp.idconcepto,cp.valor,cp.importe,p.diaslaborados,tp.nombre,c.idconcepto,c.concepto,c.descripcion,p.idEmpleado,p.idnomp,np.numnomina,p.concpAgre,p.idconfpre,p.importe,p.idNominatimbre,p.idcal,e.codigo,p.valordias,e.*,np.fechainicio,np.fechafin, np.idtipop,nt.horas,nt.idjornada,cp.idconcepto
			from nomi_calculo_prenomina as p 	
			inner join nomi_conceptos as c on p.idconfpre=c.idconcepto 
			left join nomi_conf_prenomina as cp on cp.idconcepto=c.idconcepto 
			inner join nomi_nominasperiodo as np on np.idnomp = p.idnomp
			inner join nomi_empleados as e
			on e.idEmpleado=p.idEmpleado
			inner join nomi_tiposdeperiodos as tp
			on np.idtipop=tp.idtipop
			left join nomi_turno nt
			on nt.idturno=e.idturno
			where (c.idtipo=1 || c.idtipo=4 ) and p.idEmpleado=$idEmpleado and p.idnomp=$idnomp and  p.aplicarecibo=1 and  np.autorizado=0;");	

		return $sql;
	}

	function cargaDeduccion($idEmpleado,$idnomp){


		$sql=$this->query("SELECT p.diaspagados as pagadosdias,cp.idconcepto,cp.valor,cp.importe,p.diaslaborados,tp.nombre,c.idconcepto,c.concepto,c.descripcion,p.idEmpleado,p.concpAgre,p.idnomp,np.numnomina,p.idconfpre,p.importe,p.idNominatimbre,p.idcal,e.codigo,p.valordias,e.*,np.fechainicio,np.fechafin, np.idtipop,nt.horas,nt.idjornada,cp.idconcepto
			from nomi_calculo_prenomina as p 	
			inner join nomi_conceptos as c on p.idconfpre=c.idconcepto 
			left join nomi_conf_prenomina as cp on cp.idconcepto=c.idconcepto 
			inner join nomi_nominasperiodo as np on np.idnomp = p.idnomp
			inner join nomi_empleados as e
			on e.idEmpleado=p.idEmpleado
			inner join nomi_tiposdeperiodos as tp
			on np.idtipop=tp.idtipop
			left join nomi_turno nt
			on nt.idturno=e.idturno
			where c.idtipo=2 and p.idEmpleado=$idEmpleado and p.idnomp=$idnomp and p.aplicarecibo=1 and  np.autorizado=0;");	
		
		return $sql;
	}
	function empleadoSobreRecibo(){
		// $sql = $this->query("SELECT e.idEmpleado,e.nombreEmpleado,e.apellidoPaterno,e.idtipop from nomi_empleados e
		// 	inner join  nomi_configuracion c on e.idtipop=c.idtipop;");

		$sql = $this->query("SELECT np.numnomina,np.idnomp,e.idEmpleado,e.nombreEmpleado,e.apellidoPaterno,e.idtipop from nomi_empleados e
			inner join  nomi_configuracion c on e.idtipop=c.idtipop inner join nomi_nominasperiodo np on e.idtipop=np.idtipop where np.autorizado=0 group by e.idEmpleado;");


		if($sql->num_rows>0){
			return $sql;
		}else{
			return 0;
		}
	}
	function empleadoSobreRecibodetalle($idEmpleado,$idnomp){
		
		$sql = $this->query("SELECT  CASE WHEN EXISTS (SELECT 1 FROM nomi_calculo_prenomina WHERE idnomp = 3) THEN  (select cp.salario from nomi_calculo_prenomina cp where cp.idEmpleado=10 and cp.idnomp=3 limit 1) when exists(select nuevoSalario from nomi_historico_salarios hs where hs.idEmpleado=e.idEmpleado and hs.fechaAplicacion<=np.fechafin) then hs.nuevoSalario   ELSE e.salario END AS 
		salario,cp.diaslabproporcion,cp.diaspagados,cp.diaslaborados,cp.diasvac,cp.diasfestivo,
		p.nombre,np.fechainicio,np.fechafin,e.idEmpleado,e.codigo,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.nss,e.idsexo,e.rfc,e.curp,e.idBanco,e.idtipocontrato,e.idtipop,e.idbase,e.idDep,e.idPuesto,e.idtipoEmpleado,e.diascotizados,e.tipocuenta,c.descripcion,(case e.activo when -1 then e.fechaAlta when 3 then  h.fecha end) as fechaActiva,np.numnomina,nt.horas,nt.idjornada
			from 
			nomi_empleados e
			inner join 
			nomi_tipocontrato c on c.idtipocontrato=e.idtipocontrato
			left join nomi_historial_empleado h on h.idEmpleado = e.idEmpleado
			inner join nomi_nominasperiodo as np
			on e.idtipop=np.idtipop
			inner join nomi_tiposdeperiodos  as p
			on np.idtipop=p.idtipop
			left join nomi_calculo_prenomina as  cp
			on e.idEmpleado=cp.idEmpleado
			left join nomi_turno nt
			on nt.idturno=e.idturno
			left join nomi_historico_salarios hs on hs.idEmpleado=e.idEmpleado
			
			where e.idEmpleado =$idEmpleado and cp.idnomp=$idnomp  and np.autorizado=0  limit 1;");
		return $sql->fetch_object();
	}


	function infoEmpresa(){
		$sql=$this->query("select * from organizaciones;");
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			0;
		}
	}
	function infoRegPatronal(){
		$sql=$this->query("select r.registro from nomi_registropatronal r,nomi_configuracion c
 				where c.idregistrop=r.idregistrop and r.activo=-1;");
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



	function listaMovPermanentes(){
		$sql = $this->query("select * from nomi_movpermanentes_sobrerecibo;");
		return $sql;
	}
	function listaMovPermanentesXEmpleado($idempleado){
		$sql = $this->query("select * from nomi_movpermanentes_sobrerecibo where idEmpleado=$idempleado;");
		return $sql;
	}
	function listaMovPermanentesXEmpleadomovimiento($idempleado,$idmovper){
		$sql = $this->query("select * from nomi_movpermanentes_sobrerecibo where idEmpleado=$idempleado and idmovper=".$idmovper);
		return $sql;
	}
/////////////////////
	function listaInfonavitXEmpleado($idempleado){
		$sql = $this->query("select * from nomi_infonavit_sobrerecibo where idEmpleado=$idempleado;");
		return $sql;
	}
	function infonavitXEmpleadomovimiento($idempleado,$idmovper){
		$sql = $this->query("select * from nomi_infonavit_sobrerecibo where idEmpleado=$idempleado and idinfonavit=".$idmovper);
		return $sql;
	}
////	////////////////
	function listaFonacotXEmpleado($idempleado){
		$sql = $this->query("select * from nomi_fonacot_sobrerecibo where idEmpleado=$idempleado;");
		return $sql;
	}
	function fonacotXEmpleadomovimiento($idempleado,$idmovper){
		$sql = $this->query("select * from nomi_fonacot_sobrerecibo where idEmpleado=$idempleado and idfonacotsobre=".$idmovper);
		return $sql;
	}
//////////////////
	function listaIncapacidadesXEmpleado($idempleado){
		$sql = $this->query("select * from nomi_incapacidades_sobrerecibo where idEmpleado=$idempleado;");
		return $sql;
	}
	function incapacidadesXEmpleadomovimiento($idempleado,$idmovper){
		$sql = $this->query("select * from nomi_incapacidades_sobrerecibo where idEmpleado=$idempleado and idincapacidadsobre=".$idmovper);
		return $sql;
	}
/////	///	///	/	/////	//
	function listaVacacionesXEmpleado($idempleado){
		$sql = $this->query("select * from nomi_vacaciones_sobrerecibo where idEmpleado=$idempleado;");
		return $sql;
	}
	function vacacionesXEmpleadomovimiento($idempleado,$idmovper){
		$sql = $this->query("select * from nomi_vacaciones_sobrerecibo where idEmpleado=$idempleado and idvacasobrerecibo=".$idmovper);
		return $sql;
	}
//////	////	////
	function listaIncapacidades(){
		$sql = $this->query("select * from nomi_incapacidades_sobrerecibo;");
		return $sql;
	}
	function listaFonacot(){
		$sql = $this->query("select * from nomi_fonacot_sobrerecibo;");
		return $sql;
	}
	function listaInfonavit(){
		$sql = $this->query("select * from nomi_infonavit_sobrerecibo;");
		return $sql;
	}
	/*  /	/	/	/	/	/	/	/	/	/	/	/	/	/	/	/	*/
	function listameses(){
		$sql = $this->query("select * from meses;");
		return $sql;
	}
function incapacidades(){//solo las de incapacidad
	$sql = $this->query("select * from nomi_tipoincidencias where idconsiderado=2;");
	return $sql;
}
function secuelaconsecuencia(){
	$sql = $this->query("select * from nomi_incapacidad_secuela_consecuencia;");
	return $sql;
}
function controlIncapacidad(){
	$sql = $this->query("select * from nomi_control_incapacidad;");
	return $sql;
}
function ramoIncapacidad(){
	$sql = $this->query("select * from nomi_incapacidad;");
	return $sql;
}
function nominasActivaIncapa(){

	$sql = $this->query("select c.idtipop,np.idtipop,min(np.fechainicio),max(np.fechafin) from nomi_nominasperiodo np inner join nomi_configuracion as c on c.idtipop=np.idtipop where autorizado=0;");
	if($sql->num_rows>0){
		return $sql->fetch_array();
	}else{
		0;
	}
	echo $sql;
}
function almacenaPermanente($nominaactiva,$descripcion,$idtipo,$idconcepto,$fechainicio,$importeOvalor,$imporvalor,$vecesaplica,$vecesaplicado,$montolimite,$montoacumulado,$fecharegistro,$numerocontrol,$estatus,$idEmpleado){
	$sql = "INSERT INTO 
	nomi_movpermanentes_sobrerecibo 
	( descripcion, idtipo, idconcepto, fechainicio, importeOvalor, imporvalor, vecesaplica, vecesaplicado, montolimite, montoacumulado, fecharegistro, numerocontrol, estatus, idEmpleado , idnomp)
	VALUES
	('$descripcion', $idtipo, $idconcepto, '$fechainicio', $importeOvalor, $imporvalor, $vecesaplica, $vecesaplicado,$montolimite, $montoacumulado, '$fecharegistro', $numerocontrol, $estatus, $idEmpleado, $nominaactiva);
	";
	if($this->insert_id($sql)){
		return 1;

	}else{
		return 0;
	}
}
function updatePermanente($nominaactiva,$descripcion,$idtipo,$idconcepto,$fechainicio,$importeOvalor,$imporvalor,$vecesaplica,$vecesaplicado,$montolimite,$montoacumulado,$fecharegistro,$numerocontrol,$estatus,$idmovper){
	$sql = "UPDATE  
	nomi_movpermanentes_sobrerecibo 
	SET 
	descripcion	= '$descripcion', 
	idtipo = $idtipo, 
	idconcepto	= $idconcepto, 
	fechainicio	= '$fechainicio', 
	importeOvalor	= $importeOvalor, 
	imporvalor	= $imporvalor, 
	vecesaplica	= $vecesaplica,
	vecesaplicado	= $vecesaplicado, 
	montolimite	= $montolimite,
	montoacumulado	= $montoacumulado, 
	fecharegistro	= '$fecharegistro', 
	numerocontrol	= $numerocontrol, 
	estatus	= $estatus,
	idnomp	= $nominaactiva
	WHERE 
	idmovper = $idmovper;

	";
	if($this->query($sql)){
		return 1;

	}else{
		return 0;
	}
}
function almacenaInfonavit($nominaactiva,$numinfonavit,$descripcion,$tipocredito,$importecreditofactormensual,$incluirpagoseguro,$fechaaplicacion,$montoacumulado,$vecesaplicado,$fecharegistro,$estatus   ,$idEmpleado){

	$sql = "INSERT INTO nomi_infonavit_sobrerecibo 
	(numinfonavit, descripcion, tipocredito, importecreditofactormensual, incluirpagoseguro, fechaaplicacion, montoacumulado, vecesaplicado, fecharegistro, estatus, idEmpleado, idnomp)
	VALUES
	( '$numinfonavit', '$descripcion', $tipocredito, $importecreditofactormensual, $incluirpagoseguro, '$fechaaplicacion', $montoacumulado, $vecesaplicado, '$fecharegistro', $estatus, $idEmpleado, $nominaactiva);
	";
	if($this->insert_id($sql)){
		return 1;

	}else{
		return 0;
	}
}
function updateInfonavit($nominaactiva,$numinfonavit,$descripcion,$tipocredito,$importecreditofactormensual,$incluirpagoseguro,$fechaaplicacion,$montoacumulado,$vecesaplicado,$fecharegistro,$estatus,$idinfonavit){
	$sql = "UPDATE  
	nomi_infonavit_sobrerecibo 
	SET 
	numinfonavit	= '$numinfonavit', 
	descripcion = '$descripcion', 
	tipocredito	= $tipocredito, 
	importecreditofactormensual	= $importecreditofactormensual, 
	incluirpagoseguro	= $incluirpagoseguro, 
	fechaaplicacion	= '$fechaaplicacion', 
	montoacumulado	= $montoacumulado,
	vecesaplicado	= $vecesaplicado, 
	fecharegistro	= '$fecharegistro',
	estatus	= $estatus,
	idnomp	= $nominaactiva
	WHERE 
	idinfonavit = $idinfonavit;

	";
	if($this->query($sql)){
		return 1;

	}else{
		return 0;
	}
}
// FONACOT //
function almacenaFonacot($nominaactiva,$numcredito,$descripcion,$mes,$ejercicio,$calculoretencion,$importecredito,$retencionmensual,$pagohechosotros,$saldo,$estatus,$obervaciones,$idEmpleado){

	$sql = "INSERT INTO nomi_fonacot_sobrerecibo 
	(numcredito, descripcion, mes, ejercicio, calculoretencion, importecredito, retencionmensual, pagohechosotros, saldo, estatus, obervaciones, idEmpleado,idnomp)
	VALUES
	($numcredito, '$descripcion', $mes, $ejercicio, $calculoretencion, $importecredito, $retencionmensual, $pagohechosotros, $saldo, $estatus, '$obervaciones', $idEmpleado,$nominaactiva);
	";
	if($this->insert_id($sql)){
		return 1;

	}else{
		return 0;
	}
}
function updateFonacot($nominaactiva,$numcredito,$descripcion,$mes,$ejercicio,$calculoretencion,$importecredito,$retencionmensual,$pagohechosotros,$saldo,$estatus,$obervaciones,$idfonacotsobre){
	$sql = "UPDATE  
	nomi_fonacot_sobrerecibo 
	SET 
	numcredito	= '$numcredito', 
	descripcion	='$descripcion',
	mes	= $mes,
	ejercicio	= $ejercicio,
	calculoretencion	= $calculoretencion,
	importecredito	= $importecredito,
	retencionmensual	= $retencionmensual,
	pagohechosotros	= $pagohechosotros,
	saldo	= $saldo,
	obervaciones	= '$obervaciones',
	estatus	= $estatus,
	idnomp	= $nominaactiva
	WHERE 
	idfonacotsobre          = $idfonacotsobre;
	";
	if($this->query($sql)){
		return 1;

	}else{
		return 0;
	}
}
// FIN FONACOT //
// INCAPACIDAD // 
function almacenaIncapacidad($nominaactiva,$folio,$idtipoincidencia,$diasautorizados,$fechainicio,$ramoseguro,$tiporiesgo,$porcentajeincapacidad,$idsecuela,$idcontrol,$descripcion,$idEmpleado){

	$sqldos ='';
	$fechaseleccionada = $fechainicio; 

	$sql = "INSERT INTO nomi_incapacidades_sobrerecibo (folio,idtipoincidencia,diasautorizados,fechainicio,ramoseguro, tiporiesgo,porcentajeincapacidad,idsecuela,idcontrol,descripcion,idEmpleado,idnomp) 
	VALUES
	('$folio',$idtipoincidencia,$diasautorizados,'$fechainicio',$ramoseguro,$tiporiesgo,$porcentajeincapacidad,$idsecuela,$idcontrol,'$descripcion',$idEmpleado,$nominaactiva)";

       // $sql=$this->query($sql);
	$idsobre = $this->insert_id($sql);
	if(!$idsobre){
		return 0;
	}

	for($i=1;$i<=$diasautorizados; $i++){

		$sqldel="DELETE  from nomi_claveincidencias where idnomp=$nominaactiva and fechaseleccion ='$fechaseleccionada'  and idempleado=$idEmpleado;";

 	//$sqlse=$this->query($sqlse);
		if(! $this->query($sqldel)){
			return 0;
		}

		$sqldos="INSERT INTO nomi_claveincidencias(idtipoincidencia,idnomp,idempleado,fechaseleccion,clave,sobrerecibo,idsobrecibo)
		VALUES
		('$idtipoincidencia', '$nominaactiva', $idEmpleado,'$fechaseleccionada',(SELECT clave FROM nomi_tipoincidencias WHERE idtipoincidencia = $idtipoincidencia),1,$idsobre);";
		if(! $this->query($sqldos)){
			return 0;
		}

		$fechaseleccionada = date('Y-m-d', strtotime ('+1 day' , strtotime ($fechaseleccionada) )); 
	}         

	return 1;
}


function updateIncapacidad($nominaactiva,$folio,$idtipoincidencia,$diasautorizados,$fechainicio,$ramoseguro,$tiporiesgo,$porcentajeincapacidad,$idsecuela,$idcontrol,$descripcion,$idincapacidadsobre,$idEmpleado){
	$sqldos ='';
	$fechaseleccionada = $fechainicio;



	$sql = "UPDATE  
	nomi_incapacidades_sobrerecibo 
	SET 
	folio	= '$folio', 
	idtipoincidencia	= $idtipoincidencia,
	diasautorizados	= $diasautorizados,
	fechainicio	= '$fechainicio',
	ramoseguro	= $ramoseguro,
	tiporiesgo	= $tiporiesgo,
	porcentajeincapacidad	= $porcentajeincapacidad,
	idsecuela	= $idsecuela,
	idcontrol	= $idcontrol,
	descripcion	= '$descripcion',
	idnomp	= $nominaactiva
	WHERE 
	idincapacidadsobre      = $idincapacidadsobre;
	";

	if(!$this->query($sql) ){ 
		return 0;
	}

	$sqldel="DELETE from nomi_claveincidencias where idnomp=$nominaactiva  and idempleado=$idEmpleado and idsobrecibo=$idincapacidadsobre";

 	//$sqlse=$this->query($sqlse);
	if(! $this->query($sqldel)){
		return 0;
	}

	for($i=1;$i<=$diasautorizados; $i++){ 
		$sqldos="INSERT INTO nomi_claveincidencias(idtipoincidencia,idnomp,idempleado,fechaseleccion,clave,sobrerecibo, idsobrecibo)
		VALUES
		('$idtipoincidencia', '$nominaactiva', $idEmpleado,'$fechaseleccionada',(SELECT clave FROM nomi_tipoincidencias WHERE idtipoincidencia = $idtipoincidencia),1, $idincapacidadsobre );";
		if(! $this->query($sqldos)){
			return 0;
		}

		$fechaseleccionada = date('Y-m-d', strtotime ('+1 day' , strtotime ($fechaseleccionada) )); 
	}
	return 1;
}
// FIN INCAPACIDAD //


// V A C A C I O N E S //
function almacenaVacaciones($nominaactiva,$fechaactiva,$tipocaptura,$fechainicial,$fechafinal,$fechapago,$diasdescansoseptimo,$diasvacaciones,$diasvacprimavac,$idEmpleado){
	
	// if ($fechapago == "") $fechapago = "null"; else $fechapago = "'$fechapago'";
	if ($diasdescansoseptimo == "") $diasdescansoseptimo = "null";
	if ($diasvacaciones == "") $diasvacaciones = "null";
	if ($diasvacprimavac == "") $diasvacprimavac = "null";


	$sql = "call almacenaVacaciones ($nominaactiva, $idEmpleado, '$fechainicial', '$fechafinal', $tipocaptura, '$fechapago', $diasdescansoseptimo, $diasvacaciones, $diasvacprimavac )"; 
	if(! $this->query($sql)){
		return 0;
	}
	return 1;

}

function updateVacacionesAcumulado($vacacionesacumuladas,$primaacumuladovacaciones,$vacapendientevacaciones,$diaprimapendientevacaciones,$idEmpleado)
{
	$sql = $this->query("UPDATE nomi_vacaciones_sobrerecibo SET vacacionesacumuladas = $vacacionesacumuladas ,diasprimaacumulado = $primaacumuladovacaciones , vacacionespendientes = $vacapendientevacaciones ,diasprimapendiente = $diaprimapendientevacaciones  WHERE idEmpleado = $idEmpleado; ");
}

function updateVacaciones($nominaactiva,$fechaactiva,$tipocaptura,$fechainicial,$fechafinal,$fechapago,$diasdescansoseptimo,$diasvacaciones,$diasvacprimavac,$idvacasobrerecibo,$idEmpleado){

	$fechaseleccionada = $fechainicial; 

	$sqlc = $this->query("select 1 from  nomi_claveincidencias where autorizado=1 and idsobrecibo=$idvacasobrerecibo;");
	if($sqlc->num_rows>0){
		return 2;
	}
	else{
		
		$sql = "UPDATE  
		nomi_vacaciones_sobrerecibo 
		SET 
		tipocaptura	= $tipocaptura, 
		fechainicial	= '$fechainicial',
		fechafinal	= '$fechafinal',
		fechapago	= '$fechapago',
		diasdescansoseptimo = $diasdescansoseptimo,
		diasvacaciones = $diasvacaciones,
		diasvacprimavac = $diasvacprimavac,
		idnomp	= $nominaactiva
		WHERE 
		idvacasobrerecibo = $idvacasobrerecibo;

		";

		if(!$this->query($sql)){  
			return 0;
		}

		$sqldel="DELETE from nomi_claveincidencias where idnomp=$nominaactiva  	and idempleado=$idEmpleado and idsobrecibo=$idvacasobrerecibo";

		if(!$this->query($sqldel)){
			return 0;
		}

		for($i=1;$i<=$diasvacaciones; $i++){
			$sqldos="INSERT nomi_claveincidencias(idtipoincidencia,idnomp,idempleado,fechaseleccion,clave,sobrerecibo,idsobrecibo)
			VALUES
			((SELECT idtipoincidencia FROM nomi_tipoincidencias WHERE idconsiderado='3'),
			'$nominaactiva',$idEmpleado,'$fechaseleccionada',
			(SELECT clave FROM nomi_tipoincidencias WHERE idconsiderado='3'),2,$idvacasobrerecibo);";

			if(!$this->query($sqldos)){
				return 0;
			}

			$fechaseleccionada = date('Y-m-d', strtotime ('+1 day' , strtotime ($fechaseleccionada))); 

		}
		return 1;
	}
}

// FIN VACACIONES //
function eliminaMov($id,$opc){
	switch($opc){
		case 1:
		$sql = "DELETE FROM nomi_movpermanentes_sobrerecibo WHERE idmovper = $id;";
		break;
		case 2:
		$sql = "DELETE FROM nomi_infonavit_sobrerecibo WHERE idinfonavit = $id;";
		break;
		case 3:
		$sql = "DELETE FROM nomi_fonacot_sobrerecibo WHERE idfonacotsobre = $id;";
		break;

		case 4:

		$sql = $this->query("select 1 from  nomi_claveincidencias where autorizado=1 and idsobrecibo=$id;");
		if($sql->num_rows>0){
			return 2;
		}else{
			$sql=  
			"DELETE FROM nomi_incapacidades_sobrerecibo where idincapacidadsobre = $id;".
			"DELETE FROM nomi_claveincidencias where idsobrecibo = $id;";
			if(!$this->multi_query ($sql)){
				return 0;
			}
			else return 1; 
		}
		break;

		case 5:
		$sql = $this->query("select 1 from  nomi_claveincidencias where autorizado=1 and idsobrecibo=$id;");
		if($sql->num_rows>0){
			return 2;
		}else{
			$sql=  
			"DELETE FROM nomi_vacaciones_sobrerecibo where idvacasobrerecibo = $id;".
			"DELETE FROM nomi_claveincidencias where idsobrecibo = $id;";
			if(!$this->multi_query ($sql)){
				return 0;
			}
			else return 1; 
		}
		break;


	}
	if($this->query($sql)){
		return 1;

	}else{
		return 0;
	}
}
function antiguedadXEmpleado($idEmpleado,$periodoinicio,$periodofin,$fechainiciavac){
/* se resta uno a la antiguedad si el mes y dia de fecha de alta
* son posteriores al mes y día actual.
* ya que si aun no ha cumplido los años, no tendria que contabilizarse
* CURDATE() de respaldo para sacar la fecha actual
*/

$sql = $this->query("call calculavacaciones('".$periodoinicio."','".$periodofin."','".$fechainiciavac."',".$idEmpleado.")");
// $sql = $this->query("
// SELECT 
// @diainiciovac:=DATE_FORMAT('$fechainiciavac','%m-%d') as diainiciovac,
// @mesingreso:=DATE_FORMAT(n.fechaAlta,'%m-%d') as mesingreso,
// @pinicio:=DATE_FORMAT('$periodoinicio','%m-%d') as pinicio,
// @pfin:=DATE_FORMAT('$periodofin','%m-%d') as pfin,
// @anos:=(YEAR('$fechainiciavac') - YEAR(n.fechaAlta) + 
// IF ( (@mesingreso >=@pinicio && @mesingreso<=@pfin) || @diainiciovac > @mesingreso,    0, -1 ) ) as anos,
// ( a.dias_vac_conf  ) as diasrestante,
// SUM(v.diasvacprimavac) as diasprima,@anos
// FROM nomi_empleados n
// left join nomi_antiguedades a on a.idantiguedad=@anos
//
// left join nomi_vacaciones_sobrerecibo v on v.idEmpleado=n.idEmpleado
// WHERE n.idEmpleado =$idEmpleado 
// ");
$query = $sql->fetch_object();
return $query;
}

//COMIENZA PTU
function calculoptuview($montoRepartir, $descontarincidencias,$ptuselect){
	$descontar =0; 
	if ($descontarincidencias == 'on'){
		$descontar=1;
	} 
// $reacumular =0; 
// if ($ejercicio ==1){
// $reacumular=1;

// }

	$sql = $this->query("call calculoPTU(".$montoRepartir.",".$descontar.",0, 0,".$ptuselect.")");

	return $sql;
}



function existePTU (){

	$sql = $this->query(
		"select 
		(select count(1) from nomi_almacena_ptu ptu where ptu.ejercicio_calculado = year(curdate())-1) as existeptu ,
		(select count(1) from `nomi_calculo_prenomina` cp inner join nomi_almacena_ptu ptu
		on cp.idptu = ptu.idptu where ptu.ejercicio_calculado = year(curdate())-1 and cp.autorizado=1 and origen=3) as prenominaautorizados;");

	return $sql;

}

function guardarPTU($montoRepartir, $descontarincidencias,$ejercicio,$ptuselect){

	$descontar =0; 
	if ($descontarincidencias == 'on'){
		$descontar=1;
	} 

	$reacumular =0; 
	if ($ejercicio ==1){
		$reacumular=1;
	}

	$sql = $this->query("SELECT 1  FROM nomi_tiposdeperiodos WHERE  YEAR (fechainicio) = YEAR (CURDATE()) AND extraordinario=1;");
	if(!$sql->num_rows>0){
		return 2;
	}else{
		$sql=("call calculoPTU(".$montoRepartir.",".$descontar.",1, ".$reacumular.",".$ptuselect.")"); 

		if(!$this->multi_query ($sql)){
			return 0;
		}
		else return 1; 
	}
}

function obtenAcumulado (){

	$sql = $this->query(
		"select SUM(total_importe) as total_importe from nomi_almacena_ptu where status= 2  or 3 and ejercicio_pago =(year(curdate())-1);
		");

	return $sql;
}

function conceptos(){
	$sql = $this->query("select * from nomi_tipoconcepto order by tipo asc");
	return $sql;
}

//TODAS LAS FECHAS
function cargadeConceptos($idconcepto){
	$sql = $this->query("select * from nomi_conceptos where idconcepto='$idconcepto'");
	return $sql;
}


//TODAS LAS FECHAS
function cargaPeriodo($idtipop){
	$sql = $this->query("select * from nomi_nominasperiodo where idtipop='$idtipop'");
	return $sql;
}
function tipoperiodo(){



	$sql = $this->query(" Select np.idnomp,tp.nombre, np.idtipop,np.fechainicio, np.fechafin, np.autorizado ,min(np.numnomina) as numnomina
		From nomi_nominasperiodo np 
		inner join nomi_tiposdeperiodos tp 
		on tp.idtipop=np.idtipop
		Where np.autorizado=0
		and tp.activo=1  group by tp.idtipop order by tp.nombre asc");
	

	// $sql = $this->query("select * from nomi_tiposdeperiodos where activo=1 order by nombre asc");
	return $sql;
}
function cargaPeriodoD($tipo){

	if($tipo=='*'){
		$filtro='';
	}else{
		$filtro="where idtipo='$tipo'";
	}

	$sql = $this->queryarray("select * from nomi_conceptos ".$filtro." ");
	if($sql['total']>0){
		$JSON=array('success'=>1, 'data'=>$sql['rows']);
	}else{
		$JSON=array('success'=>0);
	}
	echo json_encode($JSON);
}


function conceptosPorTipo($tipo){

	$sql = $this->query("
		SELECT * FROM nomi_conceptos WHERE idtipo=$tipo AND activo=1 AND concepto!=0");
	if ($sql->num_rows>0){
		return $sql;
	}
}
//TERMINA PTU



//AGREGAR PERCEPCION O DEDUCCION EN SOBRERECIBO

function selectperce(){
	$sql = $this->query("SELECT * FROM nomi_conceptos WHERE idtipo=1 || idtipo=4;");
	if ($sql->num_rows>0){
		return $sql;
	}
}

function selectdedu(){
	$sql = $this->query("SELECT * FROM nomi_conceptos WHERE idtipo=2;");
	if ($sql->num_rows>0){
		return $sql;
	}
}

function guardarPercDedu($empleado,$perce,$dedu,$nominaactiva){
	$dato=0;

	if ($perce!='0') {
		$dato=$perce;
	}else{
		$dato=$dedu;
	}

	if ( $perce !='0' and $dedu !='0'){

		$sql = "INSERT INTO nomi_calculo_prenomina 
		(idEmpleado,idnomp,idconfpre,importe,origen,autorizado,concpAgre)
		VALUES
		('$empleado', '$nominaactiva', '$perce', '0','0', '0','1');".
		"INSERT INTO nomi_calculo_prenomina 
		(idEmpleado,idnomp,idconfpre,importe,origen,autorizado,concpAgre)
		VALUES
		('$empleado', '$nominaactiva', '$dedu', '0','0', '0','1');
		";	
	}

	else {

		$sql = "INSERT INTO nomi_calculo_prenomina 
		(idEmpleado,idnomp,idconfpre,importe,origen,autorizado,concpAgre)
		VALUES
		( '$empleado', '$nominaactiva', '$dato', '0','0', '0','1');
		";
	} 

	if($this->multi_query($sql)){
		return 1;

	}else{
		return 0;
	}
}


function accionEliminarConceptoSobre($empleado,$concepto){


	$sql = "DELETE FROM nomi_calculo_prenomina WHERE  idEmpleado=$empleado and idconfpre=$concepto; ";

	if($this->query($sql)){
		return 1;

	}else{
		return 0;
	}

}

//A U M E N T O  D E   S A L A R I O S
function registropatronal(){

	$sql = $this->query("SELECT * FROM nomi_registropatronal");

	return $sql;

}


function departamentos(){

	$sql = $this->query("SELECT * FROM nomi_departamento");

	return $sql;
}

function empleados(){

	$sql = $this->query("select * from nomi_empleados where  activo=-1");
	return $sql;
}

function cargaEmple($registro,$idtipop,$dep){
	// echo $registro;

if($registro==''){
		$filtroregi='';
	}else{
		$filtroregi="where e.idregistrop='$registro'";
	}


	if($idtipop=='*'){
		$filtro='';
	}else{
		$filtro="and tp.idtipop='$idtipop'";
	}

	if($dep=='*'){
		$filtrodep='';
	}else{
		$filtrodep="and e.idDep='$dep'";
	}

	$sql = $this->queryarray("SELECT tp.idtipop,tp.nombre,tp.activo,np.idtipop,np.numnomina,np.fechainicio,np.fechafin,np.ejercicio,np.autorizado,e.idEmpleado,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.idtipop,e.idDep,e.idregistrop

		from nomi_empleados e inner join 
		nomi_tiposdeperiodos as tp
		on 
		e.idtipop=tp.idtipop 
		inner join nomi_nominasperiodo as np
		on np.idtipop=tp.idtipop 
		".$filtroregi." ".$filtro." ".$filtrodep."  and  autorizado=0 group by idEmpleado;
		");

	if($sql['total']>0){
		$JSON=array('success'=>1, 'data'=>$sql['rows']);
	}else{
		$JSON=array('success'=>0);
	}
	echo json_encode($JSON);


}

function montoAumeSalario($checkSeleccionado,$empleados,$tipoAumento,$montosalario,$fechaAplic){

	$sql = $this->query("call aumentaSalario('".$checkSeleccionado."','".$empleados."',".$tipoAumento.",".$montosalario.",0,'')");
	return $sql;
}

function guardarAumentoSalaHisto($checkSeleccionado,$empleados,$tipoAumento,$montosalario,$fechaAplic, $idnomp, $idtipop){

	$sql = $this->query("SELECT 1 From nomi_nominasperiodo Where fechainicio ='$fechaAplic' and autorizado=0 and idtipop=$idtipop;");
	if($sql->num_rows==1){

		$sql="DELETE from nomi_historico_salarios where fechaAplicacion ='$fechaAplic';".
		"call aumentaSalario('".$checkSeleccionado."','".$empleados."',".$tipoAumento.",".$montosalario.",'".$fechaAplic."',1)"; 

		if(!$this->multi_query ($sql)){
			return 0;
		}
		else return 1; 

	}else{

		return 2;
	}
}

function existeAumento ($fechaAplicacion,$fechaActivPe){

	if(strcmp ($fechaAplicacion , $fechaActivPe )==0)
	{
		$sql = $this->query("SELECT count(*) From nomi_historico_salarios Where fechaAplicacion ='$fechaAplicacion'");
		if($sql->num_rows>0){

			return 2;
		}
		
	}else{
		return 1;
	}	
}

//C O N C I L I A C I O N
function conciliacion(){

	$sql = $this->query("SELECT
		CASE when EXISTS (SELECT 1 FROM  nomi_empleados where t.idEmpleado=he.idEmpleado)then  (SELECT fecha 
		FROM nomi_historial_empleado 
		WHERE idhistorial = (SELECT MAX(idhistorial) FROM nomi_historial_empleado)) else t.fechaAlta end as fechaActual, 
		CASE t.activo
		WHEN -1  then '1'
		WHEN  2  then '2'
		WHEN  3  then '3'
		End as tiponuevo,

		CASE t.activo
		WHEN -1  then 'Alta'
		WHEN  2  then 'Baja'
		WHEN  3  then 'Reingreso'
		End as descripcionEstatus,
		@row := @row + 1 as numRegi, t.*
		FROM nomi_empleados t

		Left join nomi_historial_empleado he
		On  he.idEmpleado=t.idEmpleado 
		And he.tipo =t.activo,
		(SELECT @row := 0) r 

		where t.activo In ('-1', '3') and (t.`validarfcsat` is null) or  (t.`validarfcsat`=0)   group by t.idEmpleado order by t.idEmpleado asc,numRegi asc;");	

	return $sql;
}



function guardarRespuestaSAT($tableData){
	$datosGuardar = json_decode($tableData);
	$sql = "";
	foreach ($datosGuardar as $empleado) {
		if($empleado->ResultadoSAT !="")
		$sql.= "Update nomi_empleados set validarfcsat = ".$empleado->ResultadoSAT." where rfc ='".$empleado->RFC."';
			" ;
	}

	return $this->multi_query($sql);
}
//T E R M I N A   C O N C I L I A C I O N


}


?>