<?php
  require("models/connection_sqli_manual.php"); // funciones mySQLi

class conciliacionAcontiaModel extends Connection{
	function cuentasBancarias(){
		$sql = $this->query("select c.*,b.nombre,cc.manual_code,cc.description from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id and cc.currency_id=1");
		return $sql;
	}
	function periodos(){
		$sql = $this->query("select * from meses;");
		return $sql;
	}
	function ejercicio(){
		$sql = $this->query("select * from cont_ejercicios;");
		return $sql;
	}
	function movimientosConciliadosMarcados($ejercicio,$periodo,$idbancaria,$ids){
		$sql = $this->query("select m.id idmov,p.id,m.concepto,p.fecha,m.TipoMovto,m.Importe,p.numero,p.idtipopoliza from cont_polizas p 
			inner join cont_movimientos m
			inner join bco_saldo_bancario s
			where m.idPoliza = p.id   and  p.activo=1 and m.Activo=1 
			AND m.id  in (select replace(idMovimientoPoliza,',','' ) from bco_saldo_bancario where conciliado=1 and periodo=$periodo and idejercicio=$ejercicio and idbancaria=$idbancaria) group by m.id
		");
		return $sql;
	}
	
	function movimientosConciliados($ejercicio,$periodo,$idbancaria,$ids){//nivel poliza
		
		$sql = $this->query("
			select m.id idmov,p.id,s.id idmovbanc,m.concepto,p.fecha,m.TipoMovto,m.Importe,p.numero,b.idbancaria,p.idtipopoliza  from cont_polizas p 
			inner join cont_movimientos m
			inner join bco_cuentas_bancarias b
			inner join bco_saldo_bancario s
			where m.idPoliza = p.id  and m.Cuenta = b.account_id and b.idbancaria=s.idbancaria 
			and p.fecha = s.fecha and (	case m.TipoMovto when 'Abono' then m.Importe = s.cargos when 'Cargo' then m.Importe = s.abonos end ) 
			and (p.numero= CONVERT(s.folio using utf8) collate utf8_spanish_ci || p.numero='' ) 
			and p.activo=1 and m.Activo=1 and p.idperiodo<=".$periodo." and s.periodo = ".$periodo." and s.idejercicio=".$ejercicio." and s.idbancaria=".$idbancaria."  
			and s.conciliado =0 and m.conciliado=0 AND m.id not in ($ids) group by m.Id");
		
		return $sql ;
	}
	//mov de polizas q tienen q ver con la cuenta bancaria pero q aun no estan marcandas como referencia en el saldo bancario osea conciliadas
	function movimientosSinConciliar($ejercicio,$periodo,$idbancaria,$ids,$fecha){
			
		$sql = $this->query("
			select  m.id idmov,p.id,m.concepto,p.fecha,m.TipoMovto,m.Importe,p.numero,b.idbancaria,p.idtipopoliza,m.formapago from cont_polizas p 
			inner join cont_movimientos m
			inner join bco_cuentas_bancarias b
			inner join bco_saldo_bancario s
			where m.idPoliza = p.id and p.fecha<='".$fecha."' and m.Cuenta = b.account_id and b.idbancaria=s.idbancaria and  p.activo=1 and m.Activo=1 
			and s.periodo = ".$periodo." and s.idejercicio=".$ejercicio."  and s.idbancaria=".$idbancaria."
			and m.conciliado=0 AND m.id not in ($ids) group by m.id order by p.fecha;
		");
		
			return $sql ;
		
	}
	function movimientosSinConciliarReporte($ejercicio,$periodo,$idbancaria,$ids,$fecha){
			
		$sql = $this->query("
			select  m.id idmov,p.id,m.concepto,p.fecha,m.TipoMovto,m.Importe,p.numero,b.idbancaria,p.idtipopoliza,m.formapago from cont_polizas p 
			inner join cont_movimientos m
			inner join bco_cuentas_bancarias b
			inner join bco_saldo_bancario s
			where m.idPoliza = p.id and p.fecha<='".$fecha."' and m.Cuenta = b.account_id and b.idbancaria=s.idbancaria 
			and  p.activo=1 and m.Activo=1 and m.conciliado=0 and p.idperiodo<=".$periodo." and p.idejercicio=".$ejercicio."  
			and s.periodo = ".$periodo." and s.idejercicio=".$ejercicio."  and s.idbancaria=".$idbancaria."
			AND m.id not in ($ids) group by m.id order by p.fecha;
		");
		
			return $sql ;
		
	}
	function movimientosSinConciliarDescartar($ejercicio,$periodo,$idbancaria,$desde,$hasta){//movimientos correspondientes al estado bancario por fecha no se sacan los q no tienen q ver
		$sql = $this->query("
			select  m.id idmov,p.id,m.concepto,p.fecha,m.TipoMovto,m.Importe,p.numero,b.idbancaria,p.idtipopoliza,m.formapago,p.numpol from cont_polizas p 
			inner join cont_movimientos m
			inner join bco_cuentas_bancarias b
			inner join bco_saldo_bancario s
			where m.idPoliza = p.id and p.fecha between '$desde' and '$hasta' and m.Cuenta = b.account_id and b.idbancaria=s.idbancaria and  p.activo=1 and m.Activo=1 
			and s.periodo = ".$periodo." and s.idejercicio=".$ejercicio."  and s.idbancaria=".$idbancaria."
			and m.conciliado=0 group by m.id order by p.fecha;
		");
		
			return $sql ;
		
	}
	function movimientosSinConciliarBanco($ejercicio,$periodo,$idbancaria, $status){//mov del banco sin conciliar
		if($status==0){ $filtro = "";}
		if($status==1){ $filtro = " and s.abonos>0";}
		if($status==2){ $filtro = "and s.cargos>0";}
		$sql = $this->query("select s.* from  bco_saldo_bancario s where  s.periodo = ".$periodo." and s.idejercicio=".$ejercicio." and s.idbancaria=".$idbancaria." and s.conciliado=0 $filtro;");
			return $sql ;
	}
	function idsMovPoliConciliacion(){
		$sql = $this->query("select idMovimientoPoliza from bco_saldo_bancario where idMovimientoPoliza!=''");
		$ids="0";
		if($sql->num_rows>0){
			
			while($id = $sql->fetch_object()){
				$ids.=$id->idMovimientoPoliza;
			}
		}
			return $ids;
		
	}
	function movimientosConciliadosMarcadosXperiodo($periodo){
		$sql= $this->query("select idMovimientoPoliza from bco_saldo_bancario where idMovimientoPoliza!='' and periodo=$periodo");
		$ids="0";
		if($sql->num_rows>0){
			
			while($id = $sql->fetch_object()){
				$ids.=$id->idMovimientoPoliza;
			}
		}
		return $ids;
	}
	
	function idsMovPoliConciliacionReporte($periodo,$cuenta,$ejer){
		$sql = $this->query("select idMovimientoPoliza from bco_saldo_bancario where idMovimientoPoliza!='' and periodo<=".$periodo." and idejercicio=".$ejer." and idbancaria=".$cuenta);
		$ids="0";
		if($sql->num_rows>0){
			
			while($id = $sql->fetch_object()){
				$ids.=$id->idMovimientoPoliza;
			}
		}
			return $ids;
		
	}
	function conciliaMovimientos($idMovBancos,$idMovPoliza){
		$sql = "update bco_saldo_bancario set conciliado=1, idMovimientoPoliza=concat (idMovimientoPoliza,',$idMovPoliza') where id=".$idMovBancos;
		if($this->query($sql) ){
			return 1;
		}else{
			return 0;
		}
		
	}
	function conciliaMovimientosPrimerVez($idMovBancos,$idMovPoliza){
		$sqlprevio = $this->query("	select idMovimientoPoliza from bco_saldo_bancario where id=".$idMovBancos."	and idMovimientoPoliza!='' " );
		if($sqlprevio->num_rows>0){//si tiene registro encontro dos polizas iguales no alamcenara nada para que el user seleccione laq cubre al mov
			$idprevio = $sqlprevio->fetch_assoc();
			$this->desconsilia_Movnulosbancos('0'.$idprevio['idMovimientoPoliza'], $idMovBancos);
			return 1;
		}else{
			$sql = "update bco_saldo_bancario set conciliado=1, idMovimientoPoliza=concat (idMovimientoPoliza,',$idMovPoliza') where id=".$idMovBancos;
			if($this->query($sql) ){
				$this->query("update cont_movimientos set conciliado=1 where id in (0".$idMovPoliza.");");
				return 1;
			}else{
				return 0;
			}
		}
	}
	function saldoConciliacion($idbancaria,$periodo){//saldo final de la conciliacion anterior
		$sql = $this->query("select * from bco_saldos_conciliacion where idbancaria=".$idbancaria." and periodo<".$periodo." order by id desc limit 1");
		return $sql;
	}
	function saldoConciliacionReport($idbancaria,$periodo,$ejer){//saldo final de la conciliacion 
		$sql = $this->query("select * from bco_saldos_conciliacion where idbancaria=".$idbancaria." and periodo=".$periodo." and ejercicio=".$ejer);
		return $sql;
	}
	function salfoFinalEstadoCuenta($idbancaria,$periodo,$ejercicio){//saldo final del estado de cuenta actual
		$sql = $this->query("select saldofinal from bco_saldo_bancario where idbancaria=".$idbancaria." and periodo=".$periodo ." and idejercicio=".$ejercicio." order by id desc limit 1");
		return $sql->fetch_object();
	}
	function sumMovdelPeriodoPoliza($idbancaria,$periodo,$ejercicio){
		$sql = $this->query("select m.TipoMovto,IFNULL ( (sum(m.Importe)),0)  importe,b.idbancaria from cont_polizas p 
			inner join cont_movimientos m
			inner join bco_cuentas_bancarias b
			where m.idPoliza = p.id  and m.Cuenta = b.account_id and b.idbancaria=".$idbancaria." and m.TipoMovto='Abono'
			and p.activo=1 and m.Activo=1  and p.idperiodo = ".$periodo." and p.idejercicio=".$ejercicio." 
			union 
		select m.TipoMovto,sum(m.Importe) importe,b.idbancaria from cont_polizas p 
			inner join cont_movimientos m
			inner join bco_cuentas_bancarias b
			where m.idPoliza = p.id  and m.Cuenta = b.account_id and b.idbancaria=".$idbancaria." and m.TipoMovto='Cargo'
			and p.activo=1 and m.Activo=1  and p.idperiodo = ".$periodo." and p.idejercicio=".$ejercicio);
	
		return $sql;
	}
	
	function finalizaConciliacion($idbancaria,$periodo,$ejercicio,$saldoinicial,$saldofinal){
		$sql = "insert into bco_saldos_conciliacion (periodo,saldo_inicial,saldo_final,idbancaria,ejercicio) values ($periodo,$saldoinicial,$saldofinal,$idbancaria,$ejercicio)";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function existeFinconciliacion($idbancaria,$periodo,$ejercicio){
		$sql = $this->query("select * from bco_saldos_conciliacion where idbancaria=".$idbancaria." and periodo=".$periodo." and ejercicio=".$ejercicio);
		return $sql;
	}
	
	function estadoCuentaBancario($idbancaria,$periodo,$ejercicio){
		$sql  = $this->query("select s.* from  bco_saldo_bancario s where  s.periodo = ".$periodo." and s.idejercicio=".$ejercicio." and s.idbancaria=".$idbancaria);
		return $sql;
	}
	function logo()
		{
			$myQuery = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
			$logo = $this->query($myQuery);
			$logo = $logo->fetch_assoc();
			return $logo['logoempresa'];
		}
	function NombreEjercicio($ej)
		{
			$myQuery = "SELECT NombreEjercicio FROM cont_ejercicios WHERE Id=$ej";
			$ejercicio = $this->query($myQuery);	
			$ejercicio = $ejercicio->fetch_assoc();
			return $ejercicio['NombreEjercicio'];
		}
		function empresa()
		{
				$myQuery = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
				$empresa = $this->query($myQuery);	
				$empresa = $empresa->fetch_array();
				return $empresa['nombreorganizacion'];
		}
		
		function infocuentasBancarias($idbancaria){
		$sql = $this->query("select c.idbancaria,c.cuenta,b.nombre,c.account_id from bco_cuentas_bancarias c,cont_bancos b where c.idbanco=b.idbanco and idbancaria= ".$idbancaria);
		return $sql->fetch_assoc();
	}
	function Saldos($Cuenta,$Fecha,$tipo,$nivel)
		{
			if(intval($nivel))
			{
				$where = "m.Cuenta = $Cuenta";
			}
			else
			{
				$where = "m.Cuenta IN (SELECT account_id FROM cont_accounts WHERE main_father = $Cuenta)";
			}
			if($tipo == 'Antes')
			{
				$filtro = "p.fecha<'$Fecha'";
			}
			if($tipo == 'Despues')
			{
				$filtro = "p.fecha<='$Fecha'";
			}
			$myQuery = "SELECT 
			IFNULL(SUM(m.Importe),0) AS Suma
			FROM cont_movimientos m 
			INNER JOIN cont_polizas p ON p.id = m.idPoliza 
			WHERE $where AND $filtro AND m.tipoMovto='Cargo' AND p.activo=1 AND m.activo=1";
			$Cargos = $this->query($myQuery);
			$Cargos = $Cargos->fetch_array();


			$myQuery = "SELECT 
			IFNULL(SUM(m.Importe),0) AS Suma
			FROM cont_movimientos m 
			INNER JOIN cont_polizas p ON p.id = m.idPoliza 
			WHERE $where AND $filtro AND m.tipoMovto='Abono' AND p.activo=1 AND m.activo=1";
			$Abonos = $this->query($myQuery);
			$Abonos = $Abonos->fetch_array();

			$myQuery = "SELECT a.account_nature FROM cont_accounts a WHERE a.account_id=$Cuenta";
			$Naturaleza = $this->query($myQuery);
			$Naturaleza = $Naturaleza->fetch_array();	

			if($Naturaleza['account_nature'] == 1)
			{
				$Resultado = $Abonos['Suma'] - $Cargos['Suma'];
			}

			if($Naturaleza['account_nature'] == 2)
			{
				$Resultado = $Cargos['Suma'] - $Abonos['Suma'];
			}	

			return $Resultado;
		}
		function cambiaStatus($id){
			$sql=$this->query("update cont_movimientos set conciliado=1 where id=".$id);
		}
		function verificaMontosConciliados($ids){
			$sql = $this->query("select sum(Importe) importe from cont_movimientos m where m.id in ($ids)");
			$imp = $sql->fetch_assoc();
			return $imp['importe'];
		}
		function verificaMontosConciliadosPolizas($ids){
			$sql = $this->query("select case cargos when 0.00 then SUM(abonos) else SUM(cargos)  end importe from bco_saldo_bancario  where id in ($ids)");
			$imp = $sql->fetch_assoc();
			return $imp['importe'];
		}
		function idsMovBancario($idmov){//un movimiento bancario en dos polizas
			$sql= $this->query("select idMovimientoPoliza from bco_saldo_bancario where id=".$idmov);
			$ids="0";
			if($sql->num_rows>0){
				
				while($id = $sql->fetch_object()){
					$ids.=$id->idMovimientoPoliza;
				}
			}
				return $ids;
		}
		function idsMovBancarioPoliza($idmov){//para cuando es poliza con movimiento bancario una poliza en 2 mov bancarios
			$sql= $this->query("select id from bco_saldo_bancario where idMovimientoPoliza=',".$idmov."'");
			$ids="0";
			if($sql->num_rows>0){
				
				while($id = $sql->fetch_object()){
					$ids.=",".$id->id;
				}
			}
				return $ids;
		}
		function importeConceptoBancario($idmov){//sera al de la poliza no el banco
			$sql= $this->query("select concepto,(case  when cargos > abonos then cargos else abonos end)importe from bco_saldo_bancario where idMovimientoPoliza!='' and id=".$idmov);
			while($id = $sql->fetch_object()){
				return $id->importe."/".$id->concepto;
			}
				
		}
		function importeConceptoBancarioPoliza($idmov){//cuando es a nivel poliza
			$sql= $this->query("select concepto,importe from cont_movimientos where id=".$idmov);
			while($id = $sql->fetch_object()){
				return $id->importe."/".$id->concepto;
			}
		}
		function desconsilia_Movnulosbancos($ids,$idmov){
			$sql = $this->query("update cont_movimientos set conciliado=0 where id in (".$ids.");");
			$sql = $this->query("update bco_saldo_bancario set idMovimientoPoliza='', conciliado=0 where id=".$idmov.";");
		}
		function desconsilia_MovnulosbancosPoliza($ids,$idmov){
			$sql = $this->query("update cont_movimientos set conciliado=0 where id=".$idmov.";");
			$sql = $this->query("update bco_saldo_bancario set idMovimientoPoliza='', conciliado=0 where id in (".$ids.");");
		}
/* 	A C O N T I A		 C O N 		B A N C O S		 */	
function validaBancos(){
	$sql = $this->query("select * from accelog_perfiles_me where idmenu=1932");
	if($sql->num_rows>0){
		return 1;
	}else{
		return 0;
	}
}
function validaEstado($periodo,$ejercicio,$idbancaria){
	$sql = $this->query("select * from  bco_saldo_bancario where periodo=".$periodo." and idejercicio=".$ejercicio." and idbancaria=".$idbancaria);
	return $sql;
}
function verifaFinConciliacionBancos($idbancaria,$periodo,$ejercicio){
	$sql = $this->query("select * from bco_saldos_conciliacionBancos where idbancaria=".$idbancaria." and ejercicio=".$ejercicio." and periodo=".$periodo);
	return $sql;
}

/*				FIN ACONTIA CON BANCOS		*/	
	
}
?>