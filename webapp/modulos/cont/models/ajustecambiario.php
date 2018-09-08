<?php
    require("models/connection_sqli_manual.php"); // funciones mySQLi

	class AjustecambiarioModel extends Connection
	{
	
		function ejercicio(){
			$eje='select * from cont_ejercicios';
			$ejercicios = $this->query($eje);
			return $ejercicios;
		}
		function tipomoneda(){
			$sql=$this->query("select * from cont_coin");
			return $sql;
		}
		function utilidadperdida($cuenta){
			$cuentaok="";
			if($cuenta=="utilidad"){
				$cuentaok="c.CuentaUtilidad";
			}else if ($cuenta=="perdida"){
				$cuentaok="c.CuentaPerdida";
			}
			$sql = $this->query("select cu.* from cont_config c,cont_accounts cu where cu.main_father=".$cuentaok);
			return $sql;
		}
		
		function savePoliza2($idorg,$ejercicio,$periodo,$tipo,$concepto,$fecha)
		{
				$myQuery = "INSERT INTO cont_polizas(idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,concepto,fecha,fecha_creacion,activo,eliminado,usuario_creacion,usuario_modificacion,fecha_modificacion) VALUES($idorg,$ejercicio,$periodo,".($this->UltimoNumPol($periodo,$ejercicio,$tipo)+1).",".$tipo.",'".$concepto."','$fecha',NOW(),1,0,".$_SESSION['accelog_idempleado'].",".$_SESSION['accelog_idempleado'].",NOW())";
				if(!$this->query($myQuery)){
					$this->transaccion('Crea Poliza Ajuste Cambiario',$myQuery);
					return 1;
				}else{
					 return 0;
				}
		}
		function InsertMov2($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,$TipoMovto,$Importe,$referencia,$persona,$xml,$concepto,$fomapago)
		{

			$myQuery = "INSERT INTO cont_movimientos(IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona,FormaPago) VALUES($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,'$TipoMovto',$Importe,'$referencia','$concepto',1,NOW(),'$xml','".$persona."',$fomapago)";
			
			if($this->query($myQuery))
			{
				return true;
			}
			else
			{
				return false;
			}

		}
		function ajustecambiario($periodo,$ejer,$moneda){//saca los importes por movimiento
			// le kite el periodo and p.idperiodo=".$periodo."
			$sql=$this->query("select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,p.concepto,c.manual_code,p.idperiodo
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and  c.currency_id=".$moneda." and p.idejercicio=".$ejer." and c.`main_father`!=conf.CuentaBancos
			and m.IdPoliza=p.id  and p.activo=1 and m.Activo=1 and p.relacionExt!=0 and p.idperiodo<=".$periodo."
			group by p.relacionExt,m.TipoMovto
			");
			if($sql->num_rows>0){
				return $sql;
			}else{
				return 0;
			}
			
		}
		function provisionesunidas($cuenta,$poli){
			$sql=$this->query("select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.manual_code,p.idperiodo
			from cont_movimientos m,cont_polizas p,cont_accounts c
			where m.IdPoliza=p.id  and p.activo=1 and m.Activo=1 and p.id=".$poli." and m.cuenta=".$cuenta."  and c.account_id=m.Cuenta
			group by  m.Cuenta,m.TipoMovto");
			return $sql;
			
		}
		function provisinpagar($periodo,$ejer,$moneda){
			$sql=$this->query("select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,c.manual_code
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and  c.currency_id=".$moneda."  and c.`main_father`!=conf.CuentaBancos and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
			and m.IdPoliza=p.id and p.activo=1 and m.Activo=1 and p.idtipopoliza=3 and p.idperiodo in (".$periodo.",".($periodo-=1).") and p.id not in 
			(select relacionExt from cont_polizas where relacionExt!=0 and p.idperiodo in (".$periodo.",".($periodo-=1).") and idtipopoliza!=3)
			group by  m.Cuenta,m.TipoMovto order by m.TipoMovto desc");
				return $sql;
			
		}
		function cuentabancos(){//kite esto and p.idejercicio=1 
			$sql=$this->query("select m.IdPoliza,m.Cuenta,c.description,c.manual_code
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and  c.currency_id=2 
			and c.`main_father`=conf.CuentaBancos and p.idtipopoliza!=3 and m.TipoMovto not like '%M.E%'
			and m.IdPoliza=p.id  and p.activo=1  and m.Activo=1 group by m.Cuenta
			");
			return $sql;
		}
		function bancos($periodo,$ejer,$moneda){//le agrege a banco esto and p.idtipopoliza!=3 porq toma en cuenta nose xq las polizas de ajuste
			$sql=$this->query("select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,p.concepto
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and  c.currency_id=".$moneda." and p.idejercicio=".$ejer." and c.`main_father`=conf.CuentaBancos and p.idtipopoliza!=3
			and m.IdPoliza=p.id and p.idperiodo in (".$periodo.",".($periodo-=1).") and p.activo=1 and m.Activo=1
			group by m.Cuenta,m.TipoMovto");
			return $sql;
		}
		function TCultimodiames($fecha,$moneda){
			$sql=$this->query("select * from cont_tipo_cambio where fecha='".$fecha."' and moneda=".$moneda);
			if($sql->num_rows>0){
				return $sql;
			}else{
				return 0;
			}
		}
		function UltimoNUmPol($periodo,$ejercicio,$idtipopoliza)
		{
			$myQuery ="SELECT numpol FROM cont_polizas WHERE idperiodo = $periodo AND idejercicio = $ejercicio AND idtipopoliza=$idtipopoliza AND activo=1 ORDER BY numpol DESC LIMIT 1";
			$mov = $this->query($myQuery);
			$mov = $mov->fetch_assoc();
			return $mov['numpol'];
		}
		function getExerciseInfo()
		{
			$myQuery = "SELECT c.IdOrganizacion, o.nombreorganizacion,e.NombreEjercicio,e.Id AS IdEx,c.PeriodoActual,c.InicioEjercicio,c.FinEjercicio,c.PeriodosAbiertos FROM cont_config c INNER JOIN organizaciones o ON o.idorganizacion = c.IdOrganizacion INNER JOIN cont_ejercicios e ON e.NombreEjercicio = c.EjercicioActual";
			$companies = $this->query($myQuery);
			return $companies;
		}
		function getLastNumPoliza()
		{
			$myQuery = "SELECT id FROM cont_polizas ORDER BY id DESC LIMIT 1";
			$lastPoliza = $this->query($myQuery);
			$lp = $lastPoliza->fetch_assoc();
			return $lp;
		}
		function consultaprovisiones($ejer,$cuenta,$poli,$polirelacion){
			//asie stab aantes
			// $sql=$this->query("select p.*,c.manual_code from cont_polizas p,cont_movimientos m,cont_accounts c 
			// where p.idtipopoliza=3 and p.activo=1 and p.eliminado=0 and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
			// and m.IdPoliza=p.id and m.Cuenta=c.`account_id` and c.currency_id!=1 and p.relacionExt=0 
			// and p.idejercicio=".$ejer." and m.Cuenta=".$cuenta." group by p.id
			// ");
			$sql=$this->query("select p.* from cont_polizas p,cont_movimientos m,cont_accounts c 
			where p.idtipopoliza=3 and p.activo=1 and p.eliminado=0 and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
			and m.IdPoliza=p.id and m.Cuenta=c.`account_id` and c.currency_id!=1 and p.relacionExt=0 and saldado=0 
			and p.idejercicio=".$ejer." and m.Cuenta=".$cuenta." and p.id!=".$poli." or p.id=".$polirelacion." group by p.id
			");
				return $sql;
			
		}
		/* esta que solo sea a cuentas que no son bancos
		 *  porq en las provisiones no debe ir la cuenta de bancos
		 * y el ajuste necesita comparar previos y esto solo
		 * aplica para cuentas qno son de bancos*/
		function moviextranjeros($ejer,$poli){
			$sql=$this->query("select m.*,c.manual_code from cont_polizas p,cont_movimientos m,cont_accounts c,cont_config con
			where m.IdPoliza=p.id and m.Cuenta=c.`account_id` and c.currency_id!=1 and m.Activo=1 and c.main_father!=con.CuentaBancos
			 and p.idejercicio=".$ejer."  and p.id=".$poli);
			 
			return $sql;
			
		}
		function compruebapoliza($periodo,$ejer){
			$sql=$this->query("select id from cont_polizas where concepto='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA' and  activo=1 and idperiodo=".$periodo." and idejercicio=".$ejer);
			if($sql->num_rows>0){
				if($s=$sql->fetch_array()){
					return $s['id'];
				}
			}else{
				return 0;
			}
		}
		function borramovimientos($poliza){
			$sql=$this->query("update  cont_movimientos  set Activo=0 where IdPoliza=".$poliza);
			
		}
		function moneda($idmoneda){
			$sql=$this->query("select * from cont_coin where coin_id=".$idmoneda);
			if($m=$sql->fetch_array()){
				return $m['description'];
			}
		}
		// function bancossaldos($periodo,$ejer,$moneda){//kite and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
			// $sql=$this->query("select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,p.concepto,p.idperiodo,c.manual_code
			// from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			// where m.cuenta=c.account_id and  c.currency_id=".$moneda."  and c.`father_account_id`=conf.CuentaBancos 
			// and m.IdPoliza=p.id  and p.activo=1 and m.Activo=1 and p.idperiodo<=".$periodo." 
			// and p.id not in(select id from cont_polizas where concepto='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA' and idperiodo=".$periodo.")
			// group by  m.Cuenta,p.idperiodo,m.TipoMovto");
			// return $sql;
		// }
		/* new bancos */
		function bancossaldos($periodo,$ejer,$moneda,$opc){//kite and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
		switch($opc){
			case 1://cargo
				$mov="Cargo M.E.";
			break;
			case 2:
				$mov="Abono M.E";
			break;
			case 3:
				$mov="Abono";
			break;
			case 4:
				$mov="Cargo";
			break;
		}
			$sql=$this->query("select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,p.concepto,p.idperiodo,c.manual_code
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and  c.currency_id=".$moneda."  and c.`main_father`=conf.CuentaBancos and m.TipoMovto='".$mov."'
			and m.IdPoliza=p.id  and p.activo=1 and m.Activo=1 and p.idperiodo<=".$periodo." 
			and p.id not in(select id from cont_polizas where concepto='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA' and idperiodo=".$periodo.")
			group by  m.Cuenta,p.idperiodo,m.TipoMovto");
			return $sql;
		}
		function pagocobrobancossaldos($periodo,$ejer,$moneda,$cuenta,$opc){//kite and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
		switch($opc){
			case 1://cargo
				$mov="Cargo M.E.";
			break;
			case 2:
				$mov="Abono M.E";
			break;
			case 3:
				$mov="Abono";
			break;
			case 4:
				$mov="Cargo";
			break;
		}
			$sql=$this->query("select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,p.concepto,p.idperiodo,c.manual_code
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and  c.currency_id=".$moneda."  and c.`main_father`=conf.CuentaBancos and m.TipoMovto='".$mov."'
			and m.IdPoliza=p.id  and p.activo=1 and m.Activo=1 and p.idperiodo<=".$periodo." and m.Cuenta=".$cuenta."
			and p.id not in(select id from cont_polizas where concepto='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA' and idperiodo=".$periodo.")
			group by  m.Cuenta,p.idperiodo,m.TipoMovto");
			return $sql;
		}
		
		function ListaSegmentos()
		{
			$myQuery = "SELECT* FROM cont_segmentos";
			$ListaSucursales = $this->query($myQuery);
			return $ListaSucursales;		
		}

		function ListaSucursales()
		{
			$myQuery = "SELECT idSuc,nombre FROM mrp_sucursal";
			$ListaSucursales = $this->query($myQuery);
			return $ListaSucursales;		
		}
		function verificapagos($poli,$cuenta,$peri){
			$sql=$this->query("select sum(m.importe) as imp
							from 
							cont_movimientos m,cont_polizas p 
							where
							p.id=m.IdPoliza and   m.IdPoliza=".$poli." and m.TipoMovto like '%M.E%' and m.cuenta=".$cuenta."  and m.activo=1 
							and p.idperiodo<=".$peri);
			if($m=$sql->fetch_array()){
				return $m['imp'];
			}
		}
		function verificaprovi($poli,$cuenta,$peri){
			$sql=$this->query("select m.importe from cont_movimientos m,cont_polizas p where p.id=m.IdPoliza and  m.IdPoliza=".$poli." and m.TipoMovto like '%M.E%' and m.cuenta=".$cuenta." and m.activo=1 and p.idperiodo<=".$peri);
			if($m=$sql->fetch_array()){
				return $m['importe'];
			}
		}
		function consultaperiodo($peri){
			$sql=$this->query("select idperiodo from cont_polizas where id=".$peri);
			if($m=$sql->fetch_array()){
				return $m['idperiodo'];
			}
			
		}
		function compruebapolizssa($periodo){
			$sql=$this->query("select * from cont_polizas where concepto='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA' and  activo=1 and idperiodo=".$periodo);
			if($sql->num_rows>0){
				if($s=$sql->fetch_array()){
					return $s['id'];
				}
			}else{
				return 0;
			}
		}
		
		
		function polizaagregadapago($peri,$cuenta,$opc){
			if($opc==1){ $si="";}else{ $si="NOT";}
			$sql = $this->query("select sum(m.importe) importe,m.Cuenta from cont_polizas p, cont_movimientos m 
			 where m.IdPoliza=p.id and   idperiodo<=".$peri." and Cuenta=".$cuenta." and idtipopoliza=3 and p.id not in 
			(select relacionExt from cont_polizas where relacionExt!=0 and idperiodo<=".$peri." and idtipopoliza!=3 and activo=1) 
			 and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA' and TipoMovto ".$si." like '%M.E%'");
			 if($sql->num_rows>0){
				if($s=$sql->fetch_array()){
					return $s['importe'];
				}
			}else{
				return 0;
			}
		}
//////////////////nueva version///////////////////////////////////////////////////////
	
	function provisionescargos($peri,$ejer,$moneda,$opc){
		switch($opc){
			case 1://cargo
				$mov="Cargo M.E.";
			break;
			case 2:
				$mov="Abono M.E";
			break;
			case 3:
				$mov="Abono";
			break;
			case 4:
				$mov="Cargo";
			break;
		}
		$sql = $this->query('select m.IdPoliza,m.Cuenta,m.Importe importe,m.TipoMovto,c.description,p.relacionExt,p.concepto,c.manual_code,p.idperiodo
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and
			  c.currency_id='.$moneda.' and 
			  p.idejercicio='.$ejer.' and 
			  c.`main_father`!=conf.CuentaBancos and 
			  m.IdPoliza=p.id  and p.activo=1 and m.Activo=1  
			  and p.idperiodo<='.$peri.' and m.TipoMovto="'.$mov.'" and p.idtipopoliza=3 
			  and p.id not in(select id from cont_polizas where concepto="POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA" and idperiodo='.$peri.')
			group by m.TipoMovto,m.Cuenta,p.idperiodo');
		return $sql;
	}	
	function cobrocargosmn($peri,$ejer,$moneda,$opc){
		switch($opc){
			
			case 3:
				$mov="Abono";
			break;
			case 4:
				$mov="Cargo";
			break;
		}
		$sql = $this->query('select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,p.concepto,c.manual_code,p.idperiodo
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and
			  c.currency_id='.$moneda.' and 
			  p.idejercicio='.$ejer.' and 
			  c.`main_father`!=conf.CuentaBancos and 
			  m.IdPoliza=p.id  and p.activo=1 and m.Activo=1  
			  and p.idperiodo<='.$peri.' and m.TipoMovto="'.$mov.'" and p.idtipopoliza=3 
			  and p.id not in(select id from cont_polizas where concepto="POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA" and idperiodo='.$peri.')
			group by m.TipoMovto,m.Cuenta,p.idperiodo');
		return $sql;
	}	
	
	function pagoscobros($peri,$ejer,$moneda,$cuenta,$opc){ 
		switch($opc){
			case 1://cargo
				$mov="Cargo M.E.";
			break;
			case 2:
				$mov="Abono M.E";
			break;
			case 3:
				$mov="Abono";
			break;
			case 4:
				$mov="Cargo";
			break;
		}
		$sql = $this->query('select m.IdPoliza,m.Cuenta,sum(m.Importe) importe,m.TipoMovto,c.description,p.relacionExt,p.concepto,c.manual_code,p.idperiodo
			from cont_movimientos m,cont_polizas p,cont_accounts c,cont_config conf
			where m.cuenta=c.account_id and
			  c.currency_id='.$moneda.' and 
			  p.idejercicio='.$ejer.' and 
			  c.`main_father`!=conf.CuentaBancos and 
			  m.IdPoliza=p.id  and p.activo=1 and m.Activo=1  
			  and p.idperiodo<='.$peri.' and m.TipoMovto="'.$mov.'"  and m.Cuenta='.$cuenta.' 
			  and p.id not in(select id from cont_polizas where concepto="POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA" and idperiodo='.$peri.')
			  group by m.TipoMovto,m.Cuenta,p.idperiodo');
		return $sql;
		
	}
	function relacionext($idpoliza){
		$sql=$this->query("select relacionExt from cont_polizas where id=".$idpoliza);
		if($sql->num_rows>0){
			if($m=$sql->fetch_array()){
				return $m['relacionExt'];
			}
		}else{
			return 0;
		}
		
	}

		
		
		
		
	}
?>