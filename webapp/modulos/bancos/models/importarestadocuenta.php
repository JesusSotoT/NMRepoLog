<?php
   require("models/connection_sqli_manual.php"); // funciones mySQLi

class ImportaModel extends Connection{
	
	function cuentasBancarias(){
		$sql = $this->query("select c.*,b.nombre,cc.manual_code,cc.description from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id and c.activo=-1  and c.cancelada=0");
		return $sql;
	}
	function periodos(){
		$sql = $this->query("select * from meses");
		return $sql;
	}
	function importinsert($cadena)
	{
		$sql="INSERT INTO bco_saldo_bancario (periodo, idejercicio,fecha, saldoinicial, abonos, cargos, saldofinal, idbancaria, folio,concepto)
				VALUES ( ".$cadena.";";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
				
	}
	// function importinsertBancos($cadena)
	// {
		// $sql="INSERT INTO bco_saldo_bancario (periodo, idejercicio,fecha, saldoinicial, abonos, cargos, saldofinal, idbancaria, folio,concepto,idDocumentos,conciliadoBancos)
				// VALUES ( ".$cadena.";";
		// if($this->query($sql)){
			// return 1;
		// }else{
			// //$this->borraidsBancos($ids);
			// return 0;
		// }
// 				
	// }
	function importinsertBancos($cadena,$idDocs)
	{
		$sql="INSERT INTO bco_saldo_bancario (periodo, idejercicio,fecha, saldoinicial, abonos, cargos, saldofinal, idbancaria, folio,concepto,idDocumentos,conciliadoBancos)
				VALUES ( ".$cadena;
		
		if($this->query($sql)){
			$sql2="update bco_documentos set conciliado=1 where id in($idDocs);";
			if($this->query($sql2)){
				return 1;
			}else{
				return 0;
			}
		}else{
			//$this->borraidsBancos($ids);
			return 0;
		}
				
	}
	function ejercicio(){
		$sql = $this->query("select * from cont_ejercicios");
		return $sql;
	}
	
	function validaEstado($periodo,$ejercicio,$idbancaria){
		$sql = $this->query("select * from  bco_saldo_bancario where periodo=".$periodo." and idejercicio=".$ejercicio." and idbancaria=".$idbancaria);
		return $sql;
	}
	
	
	/* verifica si es la primera vez que concilia
	 * esto validando alguna finalizacion sino la tiene es la primera
	 * tomando en cuenta que tiene bancos y acontia
	 */
	
	function primerConciliacionB($idbancaria){
		$sql = $this->query("select * from  bco_saldo_bancario where idbancaria=".$idbancaria);
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	/* validar si el ejercicio periodo que se concilia
	 * es el siguiente osea que no se brinque periodos
	 * si ya finalizo
	 * 1 no puede importar 
	 * 0 si puede */
	function validaPeriodoPrevio($periodo,$ejercicio,$idbancaria){
		if($periodo == 1){
		 $periodo = 12; $ejercicio = $ejercicio-1;
		}else{
			$periodo = $periodo-1;
		}
		$sql = $this->query("select * from  bco_saldos_conciliacionBancos where periodo=".$periodo." and ejercicio=".$ejercicio." and idbancaria=".$idbancaria);
		if($sql->num_rows>0){
			return 0;
		}else{
			return 1;
		}
	}
	/* 0 si no tiene ninguna
	 * periodo si tiene y trae el ultimo para comparar
	 */
	function ultimaconciliacion($idbancaria,$periodo,$ejercicio){
		if($periodo == 1){
		 $ejercicio = $ejercicio-1;
		}
		$sql = $this->query("select * from  bco_saldos_conciliacionBancos where  idbancaria=".$idbancaria."  ORDER BY id ASC LIMIT 1");
		if($sql->num_rows>0){
			$val = $sql->fetch_object();
			return $val->periodo."-".$val->ejercicio;
		}else{
			return 1;
		}
	}
	
	/* valida que aya movimientos importandos
	 * pero si no esta finalizado entonces no se puede importar
	 * nada de esa cuenta hasta q finalice 
	 * el 0 puede importar un nuevo periodo
	 * 1 no puede importar hasta finalizar
	 * 2 esta queriendo importar sobre una finalizada
	 */
	function sinFinalizar($idbancaria,$periodo,$ejercicio){
		
		$sql =$this->query("
				SELECT 
					c.id,s.periodo,s.idejercicio,s.idbancaria,c.saldo_final
				FROM
					bco_saldo_bancario s
				LEFT JOIN 
					bco_saldos_conciliacion c ON c.ejercicio=c.ejercicio AND s.periodo=c.periodo AND s.idbancaria=c.idbancaria
				WHERE 
					(s.periodo != $periodo or s.idejercicio != $ejercicio )
					and s.idbancaria = ".$idbancaria." ORDER BY c.id ASC LIMIT 1");
		if($sql->num_rows>0){
			$val = $sql->fetch_object();
			if($val->saldo_final!=''){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 0;
		}
	}
	function idsConciliacion($idbancaria,$periodo,$ejercicio){
		$sql = $this->query("select idMovimientoPoliza from bco_saldo_bancario where idMovimientoPoliza!='' and idbancaria=".$idbancaria." and periodo=".$periodo." and idejercicio=".$ejercicio);
		$ids="0";
		if($sql->num_rows>0){
			
			while($id = $sql->fetch_object()){
				$ids.=$id->idMovimientoPoliza;
			}
		}
			return $ids;
	}
	
	function borraids($ids){
		$sql = "update cont_movimientos set conciliado=0 where id in ($ids)";
		if( $this->query($sql) ){
			return 1;
		}else{
			return 0;
		}
	}
	
	function borraMovsEstadoCuenta($idbancaria,$periodo,$ejercicio){
		$sql= "delete from bco_saldo_bancario where idbancaria=".$idbancaria." and idejercicio=".$ejercicio." and periodo=".$periodo;
		if( $this->query($sql) ){
			return 1;
		}else{
			return 0;
		}
	}
	function verifaFinConciliacion($idbancaria,$periodo,$ejercicio){
		$sql = $this->query("select * from bco_saldos_conciliacion where idbancaria=".$idbancaria." and ejercicio=".$ejercicio." and periodo=".$periodo);
		return $sql;
	}
	function verifaFinConciliacionBancos($idbancaria,$periodo,$ejercicio){
		$sql = $this->query("select * from bco_saldos_conciliacionBancos where idbancaria=".$idbancaria." and ejercicio=".$ejercicio." and periodo=".$periodo);
		return $sql;
	}
	function validaAcontia(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=142");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	
/* CONCILIACION DOCUMENTOS - BANCOS */

/* Los ingresos y egresos van tal cual ya que estos no tienen limitantes
 * como los cheques cobrados,los ingresos proyectados y depositos
 */
function borraidsBancos($ids){
	$sql = "update bco_documentos set conciliado=0,fechaaplicacion='' where id in ($ids) and idDocumento!=4;";
	if( $this->query($sql) ){
		if($this->query("update bco_documentos set conciliado=0 where id in ($ids) and idDocumento=4;"))
		{
			return 1;
		}
	}else{
		return 0;
	}
}
function validaBancos(){
	$sql = $this->query("select * from accelog_perfiles_me where idmenu=1932");
	if($sql->num_rows>0){
		return 1;
	}else{
		return 0;
	}
}
function idsConciliacionBancos($idbancaria,$periodo,$ejercicio){
	$sql = "
UPDATE   bco_documentos SET conciliado=0,fechaaplicacion='' WHERE id  IN ( 
SELECT REPLACE(idDocumentos,',','' )  FROM bco_saldo_bancario WHERE idejercicio=$ejercicio AND periodo=$periodo AND idbancaria=$idbancaria AND conciliadoBancos=1) AND idDocumento!=4;


	";		
	if( $this->query($sql) ){
		if($this->query(	"UPDATE   bco_documentos SET conciliado=0 WHERE id  IN ( 
						SELECT REPLACE(idDocumentos,',','' )  FROM bco_saldo_bancario WHERE idejercicio=$ejercicio AND periodo=$periodo AND idbancaria=$idbancaria AND conciliadoBancos=1) and idDocumento=4;") ){
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
	// $sql = $this->query("select idDocumentos from bco_saldo_bancario where idDocumentos!='' and idbancaria=".$idbancaria." and periodo=".$periodo." and idejercicio=".$ejercicio);
	// $ids="0";
	// if($sql->num_rows>0){
// 		
		// while($id = $sql->fetch_object()){
			// $ids.=$id->idDocumentos;
		// }
	// }
		// return $ids;
}
// CONCILIACION AUTOMATICA //
function ingresosEgresosB($idbancaria,$concepto,$fecha,$importe,$idDoc,$referencia){
	$sql = $this->query("select * from bco_documentos where idDocumento=$idDoc and ((importe = $importe and fecha='$fecha') || (fecha='$fecha' and referencia ='$referencia' and importe = $importe))   and status=1 and  conciliado=0 and idbancaria=".$idbancaria);
	if($sql->num_rows>0){
		$row = $sql->fetch_object();//verificar con ejemplos de dos resultados para ver q hace sino poner un group by
		return $row->id;
	}else{
		return 0;
	}
}

function chequesB($idbancaria,$concepto,$fecha,$importe,$referencia){
	$sql = $this->query("select * from bco_documentos where idDocumento=1  and ((fechaaplicacion='$fecha' and importe = $importe ) || (fechaaplicacion='$fecha' and importe = $importe and referencia ='$referencia')) and cobrado=1 and status=1 and conciliado=0 and idbancaria=".$idbancaria);
	if($sql->num_rows>0){
		$row = $sql->fetch_object();
		return $row->id;
	}else{
		return 0;
	}
}
function depositosB($idbancaria,$concepto,$fecha,$importe,$referencia){
	$sql = $this->query("select * from bco_documentos where idDocumento=4  and ((importe = $importe and fechaaplicacion='$fecha' ) || (fechaaplicacion='$fecha' and importe = $importe and referencia ='$referencia')) and status=1 and proceso=4 and conciliado=0 and idbancaria=".$idbancaria);
	if($sql->num_rows>0){
		$row = $sql->fetch_object();
		return $row->id;
	}else{
		return 0;
	}
}
/// F I N 		 C O N C I L I A C I O N		 A U T O M A T I C A //
function conciliaDocumentosManual($idMovBancos,$idDoc){
	$sql = "update bco_saldo_bancario set conciliadoBancos=1, idDocumentos=concat (idDocumentos,',$idDoc') where id=".$idMovBancos;
	if($this->query($sql) ){
		return 1;
	}else{
		return 0;
	}
	
}

function conciliaDocumentosAutomatico($idDocs){
	$sql = "update bco_documentos set conciliado=1 where id in($idDocs);";
	if($this->query($sql) ){
		return 1;
	}else{
		return 0;
	}
	
}

function listaConciliadosLista($periodo,$ejer,$idbancaria){
	$sql = $this->query("select d.*,(case idDocumento when 1 then 'Cheque' when 2 then 'Ingreso' when 4 then 'Deposito' when 5 then 'Egreso' end) documento 
			from bco_documentos d
			where  id in ( 
			select replace(idDocumentos,',','' ) from bco_saldo_bancario where conciliadoBancos=1 and periodo=$periodo and idejercicio=$ejer and idbancaria =$idbancaria)
			");
	return $sql;
}
// C O N CI L I A CI O N		 M A N U A L 		 B A N C O S //
function pendientesConciliar($idbancaria,$periodo,$ejercicio){
	$sql = $this->query("select * from bco_saldo_bancario where conciliadoBancos=0 and periodo=$periodo and idejercicio=$ejercicio and idbancaria=".$idbancaria);
	if($sql->num_rows>0){
		return $sql;
	}else{
		return 0;
	}
	
}
function nombreEjercicio($ejer){
	$sql = $this->query("select * from cont_ejercicios where Id=".$ejer);
	if($s = $sql->fetch_array()){
		return $s['NombreEjercicio'];
	}
}
function DocumenpendientesConciliar($idbancaria,$periodo,$ejercicio){
	
	$fecha = $this->nombreEjercicio($ejercicio)."-".$periodo."-01";
	$fechafin = $this->nombreEjercicio($ejercicio)."-".$periodo."-31";
	$sql = $this->query("
		select * 
		from 
			bco_documentos 
		where 
			idbancaria=$idbancaria and conciliado=0 and 
			(fechaaplicacion between '$fecha' and '$fechafin' and fecha  between '$fecha' and '$fechafin') 
			|| (fechaaplicacion between '$fecha' and '$fechafin' and fecha  <'$fecha') 
			and idDocumento in (1,4) 
		UNION
		select * 
			from 
				bco_documentos 
			where 
				fecha between '$fecha' and '$fechafin' 
				and conciliado=0 and idbancaria=$idbancaria and idDocumento in(2,5)
		
		");
	return $sql;
	
	
}

function documentosPendientes($idbancaria,$fecha,$idDoc){
	switch ($idDoc){
		case 1://cheque
			$sql = "select * from bco_documentos where idDocumento=1 and fechaaplicacion<='$fecha' and cobrado=1 and status=1 and conciliado=0 and idbancaria=".$idbancaria;
		break;
		case 2://ingreso
			$sql = "select * from bco_documentos where idDocumento=2 and fecha<='$fecha' and  status=1 and  conciliado=0 and idbancaria=".$idbancaria;
		break;
		case 4://depos
			$sql = "select * from bco_documentos where idDocumento=4 and fechaaplicacion<='$fecha' and status=1 and proceso=4 and conciliado=0 and idbancaria=".$idbancaria;
		break;
		case 5://egreso
			$sql = "select * from bco_documentos where idDocumento=5 and fecha<='$fecha' and  status=1 and  conciliado=0 and idbancaria=".$idbancaria;
		break;
	}
	$sql = $this->query($sql);
	if($sql->num_rows>0){
		return $sql;
	}else{
		return 0;
	}
}
function nombreEjer($id){
	$sql = $this->query("select NombreEjercicio from cont_ejercicios where Id=".$id);
	$row = $sql->fetch_assoc();
	return $row['NombreEjercicio'];
}
function idsMovBancarioDoc($idmov){//un movimiento bancario en dos documentos
	$sql= $this->query("select idDocumentos from bco_saldo_bancario where id=".$idmov);
	$ids="0";
	if($sql->num_rows>0){
		
		while($id = $sql->fetch_object()){
			$ids.=$id->idDocumentos;
		}
	}
		return $ids;
}
function idsMovBancarioDocumento($idmov){// documento en 2 mov bancarios
	$sql= $this->query("select id from bco_saldo_bancario where idDocumentos=',".$idmov."'");
	$ids="0";
	if($sql->num_rows>0){
		
		while($id = $sql->fetch_object()){
			$ids.=",".$id->id;
		}
	}
		return $ids;
}
function importeConceptoBancario($idmov){//sera banco
	$sql= $this->query("select concepto,(case  when cargos > abonos then cargos else abonos end)importe from bco_saldo_bancario where idDocumentos!='' and id=".$idmov);
	while($id = $sql->fetch_object()){
		return $id->importe."/".$id->concepto;
	}
		
}
function importeConceptoBancarioDocumento($idmov){//cuando es a nivel documento
	$sql= $this->query("select concepto,importe from bco_documentos where id=".$idmov);
	while($id = $sql->fetch_object()){
		return $id->importe."/".$id->concepto;
	}
}
function verificaMontosConciliados($ids){
	$sql = $this->query("select sum(importe) importe from bco_documentos m where m.id in ($ids)");
	$imp = $sql->fetch_assoc();
	return $imp['importe'];
}
function verificaMontosConciliadosB($ids){
			$sql = $this->query("select case cargos when 0.00 then SUM(abonos) else SUM(cargos)  end importe from bco_saldo_bancario  where id in ($ids)");
			$imp = $sql->fetch_assoc();
			return $imp['importe'];
		}
/* desconcilia documentos y movientos bancarios en base a
 * un Mov bancario tiene varios documentos
 * */
function desconsilia_Movnulosbancos($ids,$idmov){
	$this->borraidsBancos($ids);
	$sql = $this->query("update bco_saldo_bancario set idDocumentos='', conciliadoBancos=0 where id=".$idmov.";");
}
/* desconcilia documentos y movimientos en base a
 * Un Documentos varios mov bancarios*/
function desconsilia_MovnulosbancosDocumentos($ids,$idmov){
	$this->borraidsBancos($idmov);
	$sql = $this->query("update bco_saldo_bancario set idDocumentos='', conciliadoBancos=0 where id in (".$ids.");");
}
/* 			F I N    M A N U A L			 */

/*       F I N A L I Z A  C O N C I L I A C I O N		*/
function finalizaConciliacion($idbancaria,$periodo,$ejercicio,$saldoinicial,$saldofinal){
	$this->query("delete from bco_saldos_conciliacionBancos where periodo=$periodo and ejercicio=$ejercicio and idbancaria=".$idbancaria);
	$sql = "insert into bco_saldos_conciliacionBancos (periodo,saldo_inicial,saldo_final,idbancaria,ejercicio) values ($periodo,$saldoinicial,$saldofinal,$idbancaria,$ejercicio)";
	if($this->query($sql)){
		return 1;
	}else{
		return 0;
	}
}
function saldos($fin,$idbancaria,$periodo,$ejercicio){
	if($fin==1){//inicial
		$saldo = "saldoinicial";	
		$filtro = "asc";
	}else{
		$saldo = "saldofinal";	
		$filtro = "desc";
	}
	$sql =$this->query("select $saldo saldo from bco_saldo_bancario  where periodo=$periodo and idejercicio=$ejercicio and idbancaria=$idbancaria ORDER BY id $filtro limit 1");
	$row = $sql->fetch_object();
	return $row->saldo;
}
function saldoInicial($idbancaria,$periodo,$ejercicio){
		$nameEjer = $this->nombreEjer($ejercicio);
		
		$periodoinicial = intval($periodo);
		$periodo = $periodoinicial-1;
		$ejer = $nameEjer;
		if($periodoinicial == 1){
			$periodo  = 12;
			$ejer = $nameEjer-1;
		}
		$idejer = $this->idEjercicio($ejer);
		$nameEjer = $this->nombreEjer($idejer);
		if($periodoinicial == 1){
			$idejer = "<=".$idejer;
		}else{
			$idejer = "=".$idejer;
		}
		
		
		$fecha = $nameEjer."-".$periodo."-31";
		$saldoConciliacion = $this->saldoconciliacionPeriodoEjer($idbancaria,$periodo,$idejer);
		if($saldoConciliacion->num_rows>0){
			$saldoConcili = $saldoConciliacion->fetch_assoc();
			$saldoini['saldoinicial'] = $saldoConcili['saldo_final'];//si tiene conciliaciones entonces el final sera el inicial del periodo
			$saldo = $saldoConcili['saldo_final'];
		}else{
			$saldocuen = $this->query("select saldoinicial,fechainicial from bco_cuentas_bancarias where idbancaria=$idbancaria");
			$cuentas = $saldocuen->fetch_assoc();
			$saldoini['saldoinicial'] = $cuentas['saldoinicial'];
			$egresos 		= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=5  and status=1  and idbancaria=$idbancaria $cobrado and fecha<='".$fecha."' ");
			$egresoscheq 	= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=1 and cobrado=1  and status=1  and idbancaria=$idbancaria $cobrado and fechaaplicacion<='".$fecha."' ");
			$ingresosdepos	= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=4 and status=1  and idbancaria=$idbancaria and fechaaplicacion<='".$fecha."'");
			$ingresos 		= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=2 and status=1  and idbancaria=$idbancaria and fecha<='".$fecha."'");
			$egresos 		= $egresos->fetch_assoc();
			$egresoscheq 	= $egresoscheq->fetch_assoc();
			$ingresosdepos 	= $ingresosdepos->fetch_assoc();
			$ingresos 		= $ingresos->fetch_assoc();
			
			$saldo = $saldoini['saldoinicial'] + ($ingresos['ingresos'] + $ingresosdepos['ingresos']) - ($egresos['egresos'] + $egresoscheq['egresos']);
		
		}
		return $saldo;
	}
function saldoconciliacionPeriodoEjer($idbancaria,$periodo,$idejer){
	return $this->query("select * from bco_saldos_conciliacion where idbancaria=$idbancaria and periodo<=$periodo and ejercicio$idejer order by id desc limit 1");
}
function idEjercicio($ejer){
	$sql = $this->query("select * from cont_ejercicios where NombreEjercicio=".$ejer);
	if($sql->num_rows>0){
		if($s = $sql->fetch_array()){
			return $s['Id'];
		}
	}else{
		return 0;
	}
}
function conciliaAcontiaAuto($idbancaria,$periodo,$ejer){
	$sql = $this->query( "
			SELECT 
			 m.Id idmov,p.idDocumento FROM cont_polizas p, cont_movimientos m 
			WHERE 
			  m.idPoliza=p.id 
			  AND p.idDocumento IN
			      (SELECT REPLACE(idDocumentos,',','' ) FROM bco_saldo_bancario WHERE idbancaria=$idbancaria AND periodo=$periodo AND idejercicio=$ejer)
			  AND m.cuenta = 
			      (SELECT account_id FROM bco_cuentas_bancarias WHERE idbancaria=$idbancaria);
			");
	return $sql;
	
}
function updateMovPolizas($idDoc,$idMovPol){
	$sql =("UPDATE bco_saldo_bancario SET conciliado=1,idMovimientoPoliza=\",".$idMovPol."\"  WHERE idDocumentos=\",".$idDoc."\"");
	if( $this->query($sql) ){
		$this->query("UPDATE cont_movimientos SET conciliado=1 where Id=".$idMovPol);
	}
}
function pendientesConciliarAcont($idbancaria,$periodo,$ejercicio){
	$sql = $this->query("select * from bco_saldo_bancario where conciliado=0 and periodo=$periodo and idejercicio=$ejercicio and idbancaria=".$idbancaria);
	if($sql->num_rows>0){
		return 1;
	}else{
		/*Si no existen mov de banco Mov polizas
		 * sin conciliar finalazara la conciliacion acontia*/
		$sql2 = "INSERT INTO bco_saldos_conciliacion 
						(periodo,saldo_inicial,saldo_final,idbancaria,ejercicio)
				(SELECT periodo,saldo_inicial,saldo_final,idbancaria,ejercicio 
				FROM bco_saldos_conciliacionBancos 
				WHERE periodo=$periodo AND ejercicio=$ejercicio AND idbancaria=$idbancaria)";
		if($this->query($sql2)){
			return 2;
		}else{
			return 0;
		}
		
	}
	
}
function finalizaSinMovimientosenelMes($periodo,$saldo,$idbancaria,$ejercicio,$cadena){
	$sql = "insert into bco_saldos_conciliacionBancos (periodo,saldo_inicial,saldo_final,idbancaria,ejercicio) values ($periodo,$saldo,$saldo,$idbancaria,$ejercicio);";
	$sql.= "INSERT INTO bco_saldos_conciliacion (periodo,saldo_inicial,saldo_final,idbancaria,ejercicio) values ($periodo,$saldo,$saldo,$idbancaria,$ejercicio);";
	$sql.= "INSERT INTO bco_saldo_bancario (periodo, idejercicio,fecha, saldoinicial, abonos, cargos, saldofinal, idbancaria, folio,concepto,conciliado,conciliadoBancos)
				VALUES ( ".$cadena.";";	
	if($this->dataTransact($sql)){
		return 1;
	}else{
		return 0;
	}
}		
/* 		F I N 	C O N C I L I AC I O N */


/* RESPALDO DE CONSULTAS EN GRUPO CON LOS DATOS ALMACENADOS
 * INGRESOS , EGRESOS
 * select d.*,s.id from bco_documentos d,bco_saldo_bancario s
			where (case d.idDocumento when 5 then s.cargos=d.importe when 2 then s.abonos=d.importe end)
			and d.status=1 and d.idbancaria=s.idbancaria and d.idDocumento in (2,5) 
			and s.periodo=$idbancaria and s.idbancaria=$idbancaria and d.fecha=s.fecha
 * CHEQUES COBRADOS
 * select d.*,s.id from bco_documentos d,bco_saldo_bancario s
			where s.cargos=d.importe and d.status=1 and d.idbancaria=s.idbancaria 
			and d.idDocumento=1 and s.periodo=$idbancaria and s.idbancaria=$idbancaria and d.fecha=s.fecha and d.cobrado=1");
 * DEPOSITOS
 * select d.*,s.id from bco_documentos d,bco_saldo_bancario s
			where s.abonos=d.importe and d.status=1 and d.idbancaria=s.idbancaria 
			and d.idDocumento=4 and s.periodo=$idbancaria and s.idbancaria=$idbancaria and d.fechaaplicacion=s.fecha and d.proceso=4");
 * 
 */ 




}
		
?>

