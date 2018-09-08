<?php
   require("models/connection_sqli_manual.php"); // funciones mySQLi

	class ChequesModel extends Connection{

		//Funcion que obtiene el valor de la cookie que tiene el nombre de la instancia hija seleccionada
		//Este valor se agrega a la url 
		function path($ruta=null)
        {
            if(is_null($ruta))
                $path = "";
            else
                $path = $ruta;
            
            if(isset($_COOKIE['inst_lig']))
                $path = "../../../../".$_COOKIE['inst_lig']."/webapp/modulos/cont/";
            return $path;
        }
		
		function logo()
		{
			$myQuery = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
			$logo = $this->query($myQuery);
			$logo = $logo->fetch_assoc();
			return $logo['logoempresa'];
		}
		
		function cuentasbancariaslista(){
			$sql=$this->query("select c.*,b.nombre,cc.manual_code,cc.description from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id and c.activo=-1  and c.cancelada=0");
			return $sql;
		}
		function cuentasbancariaslistaTras($idCuenOrigen){
			$sql=$this->query("select c.*,b.nombre,cc.manual_code,cc.description from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id  and c.activo=-1  and c.cancelada=0 and c.idbancaria not in ($idCuenOrigen) ");
			return $sql;
		}
		function buscanumerocheque($idbancaria){
		$msj = "";
			// $sql = $this->query("select folio from bco_documentos where idbancaria =".$idbancaria." and idDocumento=1 order by folio desc limit 1");
			// if($sql->num_rows>0){
				// $mov = $sql->fetch_assoc();
				// $longitudActual=strlen($mov['folio']);
				// $sumar=$mov['folio']+1; 
				// $longitudFinal=strlen($sumar); 
				// $cantidadCeros=$longitudActual-$longitudFinal; 
				// $suma=str_repeat("0",$cantidadCeros).$sumar; 
				// return $suma;
			// }else{
				$sql = $this->query("select numInicialCheque,numFinalCheque,controlNumeroCheq,numeroactual from bco_cuentas_bancarias where idbancaria=".$idbancaria);
				$mov = $sql->fetch_assoc();
				if($mov['controlNumeroCheq']==-1){//si lleva rango
						if($mov['numFinalCheque']!="" && $mov['numInicialCheque']!=""){
							$totalc = (intval($mov['numFinalCheque'])*.20);
							$resto = $mov['numFinalCheque']-$totalc;
							if($mov['numeroactual']>=$resto){
								$msj = " Esta por terminarse la chequera!";
							}
						}
				}
				return $mov['numeroactual']."/".$msj;
			//}
		}
	
		function moneda(){
			$sql = $this->query("select * from cont_coin");
			return $sql;
		}
		function proveedor(){
			$sql=$this->query("SELECT cuenta,idPrv,razon_social,idtipo FROM mrp_proveedor  order by razon_social asc ");
			return $sql;
		}
		function cliente(){
			$sql = $this->query("select * from comun_cliente");
			return $sql;
		}
		function proveedorMoneda($idbancaria){//moneda de la cuenta bancaria
			$sql=$this->query("SELECT p.cuenta,p.idPrv,p.razon_social FROM mrp_proveedor p ,bco_cuentas_bancarias cb,cont_accounts c where cb.idbancaria=".$idbancaria." and (c.account_id=p.cuenta and c.currency_id=cb.coin_id || p.cuenta=0) group by p.idPrv order by razon_social asc  ");
			return $sql;
		}
		/* 1.- cuentas mostrar si no tienen cuenta el beneficiario
		* 2.- ext si es 1 esq traera todas afectables 0 esq solo las de pesos.
		*/
		function cuentasAfectables($ext){
			if($ext==0){ $filtro = "and cu.currency_id=1"; }else{ $filtro = "";}
			$sql=$this->query("select cu.account_id,cu.description,cu.manual_code,cu.currency_id from cont_accounts cu where  cu.affectable=1 and cu.removed=0 and cu.status=1 $filtro
			");
			return $sql;
		}
		function cuentasAfectablesMoneda($idbancaria){//cuentas mostrar si no tienen cuenta el beneficiario por moneda
			$sql=$this->query("select cu.account_id,cu.description,cu.manual_code from cont_accounts cu,bco_cuentas_bancarias c where  cu.affectable=1 and cu.removed=0 and cu.status=1 and currency_id=c.coin_id and c.idbancaria=".$idbancaria."
			");
			return $sql;
		}
		function saldocuenta($idbancaria,$fecha,$cuenta){// no toma en cuenta proyectados ver si debe hacerlo
			$saldoConciliacion = $this->saldoconciliacion($idbancaria);
			if($saldoConciliacion->num_rows>0){
				$saldoConcili = $saldoConciliacion->fetch_assoc();
				$saldoini['saldoinicial'] = $saldoConcili['saldo_final'];//si tiene conciliaciones entonces el final sera el inicial del periodo
			}else{
				$saldoini = $this->query("select saldoinicial from bco_cuentas_bancarias where idbancaria=$idbancaria");
				$saldoini = $saldoini->fetch_assoc();
			}
			$egresos = $this->query("select sum(importe)egresos from bco_documentos where (idDocumento=5 or idDocumento=1)  and status=1 and impreso=1  and idbancaria=$idbancaria and fecha<='".$fecha."' and conciliado=0");
			$ingresos = $this->query("select sum(importe)ingresos from bco_documentos where (idDocumento=2 or idDocumento=4) and status=1 and impreso=1 and idbancaria=$idbancaria and fecha<='".$fecha."' and conciliado=0");
			$egresos = $egresos->fetch_assoc();
			$ingresos = $ingresos->fetch_assoc();
			
			$saldo = $saldoini['saldoinicial'] + $ingresos['ingresos'] - $egresos['egresos'];
			return $saldo;		
		}
		function saldocuentabancario($idbancaria,$fecha,$cuenta){
			//return $sql = $this->query("select * from bco_saldo_contable where idbancaria=".$idbancaria." and fecha<='".$fecha."'  and status=1  order by id desc limit 1");
			$saldoini = $this->query("select saldoinicial from bco_cuentas_bancarias where idbancaria=$idbancaria");
			$egresos = $this->query("select sum(importe)egresos from bco_documentos where (idDocumento=5 or idDocumento=1)  and status=1 and impreso=1  and idbancaria=$idbancaria and fechaaplicacion<='".$fecha."'");
			$ingresos = $this->query("select sum(importe)ingresos from bco_documentos where (idDocumento=2 or idDocumento=4) and status=1 and impreso=1 and idbancaria=$idbancaria and fechaaplicacion<='".$fecha."'");
			$egresos = $egresos->fetch_assoc();
			$ingresos = $ingresos->fetch_assoc();
			$saldoini = $saldoini->fetch_assoc();
			$saldo = $saldoini['saldoinicial'] + $ingresos['ingresos'] - $egresos['egresos'];
			return $saldo;		
		}
		/* El ultimo registro en conciliacion sin periodo ni ejer 
		 * porq puede ser el 12 de un ano antes o asi y para mejor se trae solo el
		 * ultimo registro que en teoria deberia ser consecutivo y en orden 
		 * como lo de bancos se ase al dia es poco pobrable q quieran registrar un documen
		 * de un ano antes si esto es asi entonces si va traer el saldo pero del ano actual si esq tiene
		 * registrada uan conciliacion actual
		 * osea estar en 2016 y tiene una conciliacion finalizada
		 * y ara un documento de 2015 consultara el saldo pero traera de la finalizada de 2016*/
		function saldoconciliacion($idbancaria){
			return $sql = $this->query("select * from bco_saldos_conciliacion where idbancaria=".$idbancaria." order by id desc limit 1");
		}
		/* para el flujo de efectivo si requiere que sea por periodo y ejercicio
		 * ya que puede consultar en cualquier momento
		 * y debera calcular en ese lapso de fechas*/
		function saldoconciliacionPeriodoEjer($idbancaria,$periodo,$idejer){
			
			return $this->query("select * from bco_saldos_conciliacion where idbancaria=$idbancaria and periodo<=$periodo and ejercicio$idejer order by id desc limit 1");
		}
		
		function concepto($tipo){//solo conceptos de egresos
			return $sql = $this->query("select * from bco_conceptos where idtipo=".$tipo);
		}
		function clasificador(){
			return $this->query("select * from bco_clasificador where idtipo=2 and idNivel=1 and activo=-1");
		}
		function UltimoNumPol($periodo,$ejercicio,$idtipopoliza)
		{
			$myQuery ="SELECT numpol FROM cont_polizas WHERE idperiodo = $periodo AND idejercicio = $ejercicio AND idtipopoliza=$idtipopoliza AND activo=1 ORDER BY numpol DESC LIMIT 1";
			$mov = $this->query($myQuery);
			$mov = $mov->fetch_assoc();
			return $mov['numpol'];
		}
		function UltimoNumDoc($idDocTipo)
		{
			$myQuery ="select numdoc from bco_documentos where idDocumento=$idDocTipo and importe>0 order by numdoc desc limit 1";
			$mov = $this->query($myQuery);
			$mov = $mov->fetch_assoc();
			return $mov['numdoc'];
		}
		function compruebaDisponibilidad($idDoc){
			$sql = $this->query("select idDocumento from bco_documentos where importe>0 and id=$idDoc;");
			if($sql->num_rows>0){// si trae esq ya se ocupo
				$mov = $sql->fetch_assoc();
				return $mov['idDocumento'];
			}else{
				return 0;
			}
		}
		function compruebaExistencia($idDoc){
			$sql = $this->query("select idDocumento from bco_documentos where  id=$idDoc;");
			if($sql->num_rows>0){// si existe se hace por si la session trae algun valor loco
				$mov = $sql->fetch_assoc();
				return $mov['idDocumento'];
			}else{
				return 0;
			}
		}
		
		function savePoliza($referencia,$idpoliza,$idorg,$ejercicio,$periodo,$tipo,$concepto,$fecha,$beneficiario,$numero,$rfc,$idbanco,$numtarjcuent,$bancoorigen,$idDocumento,$tipoBeneficiario,$statusanticipo=0,$idUser=0)
		{
				if(!$tipoBeneficiario){ $tipoBeneficiario = 0; }
				if(!$beneficiario){$beneficiario = 0;}
				if($tipoBeneficiario==6){ $beneficiario=1;}
			
				if($idpoliza>0){
				
				$myQuery = "update cont_polizas set 
							idorganizacion	=	$idorg,
							idejercicio		=	$ejercicio,
							idperiodo		=	$periodo,
							concepto			=	'$concepto',
							fecha			=	'$fecha',
							beneficiario		=	$beneficiario,
							numero					=	'$numero',
							rfc						=	'$rfc',
							idbanco					=	$idbanco,
							numtarjcuent				=	'$numtarjcuent',
							idCuentaBancariaOrigen	=	$bancoorigen,
							idDocumento				=	$idDocumento,
							usuario_modificacion		=	".$_SESSION['accelog_idempleado'].",
							fecha_modificacion		=	DATE_SUB(NOW(), INTERVAL 6 HOUR),
							tipoBeneficiario			=	$tipoBeneficiario,
							Anticipo					=	$statusanticipo,
							idUser					=	$idUser,
							referencia				=	'$referencia'
							
							where id	 = $idpoliza";	
				}else{
					$myQuery = "INSERT INTO cont_polizas(referencia,idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,concepto,fecha,fecha_creacion,activo,eliminado,beneficiario,numero,rfc,idbanco,numtarjcuent,idCuentaBancariaOrigen,idDocumento,usuario_creacion,usuario_modificacion,fecha_modificacion,tipoBeneficiario,Anticipo,idUser,origen,idorigen) VALUES('$referencia',$idorg,$ejercicio,$periodo,".($this->UltimoNumPol($periodo,$ejercicio,$tipo)+1).",".$tipo.",'".$concepto."','$fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR),1,0,$beneficiario,'$numero','$rfc',$idbanco,'$numtarjcuent',$bancoorigen,$idDocumento,".$_SESSION["accelog_idempleado"].",".$_SESSION["accelog_idempleado"].",DATE_SUB(NOW(), INTERVAL 6 HOUR),$tipoBeneficiario,$statusanticipo,$idUser,'Bancos',$idDocumento)";
				}
				if(!$this->query($myQuery)){
					return 1;
				}else{
					return 0;
				}
		}
		function getExerciseInfo()
		{
			$myQuery = "SELECT c.IdOrganizacion, o.nombreorganizacion,e.NombreEjercicio,e.Id AS IdEx,c.PeriodoActual,c.EjercicioActual,c.InicioEjercicio,c.FinEjercicio,c.PeriodosAbiertos FROM cont_config c INNER JOIN organizaciones o ON o.idorganizacion = c.IdOrganizacion INNER JOIN cont_ejercicios e ON e.NombreEjercicio = c.EjercicioActual";
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
		function datosproveedor($prove){
			 $sql=$this->query("select * from mrp_proveedor where idPrv=".$prove);
			 return $sql->fetch_assoc();
		}
		function InsertMov($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,$TipoMovto,$Importe,$concepto,$persona,$xml,$referencia,$fomapago,$tipocambio)
		{

			$myQuery = "INSERT INTO cont_movimientos(IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona,FormaPago,tipocambio) VALUES($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,'$TipoMovto',$Importe,'$referencia','$concepto',1,DATE_SUB(NOW(), INTERVAL 6 HOUR),'$xml','".$persona."',$fomapago,$tipocambio)";
			
			if($this->query($myQuery))
			{
				return true;
			}
			else
			{
				return false;
			}

		}
		
		function InsertCheque($fecha,$folio,$importe,$referencia,$concepto,$idbancaria,$beneficiario,$idbenefeciario,$proceso,$idclasificador,$idDocumento,$tipocambio,$idmoneda,$idTipoDoc,$bancoDestino,$numBancoDes,$tipopoliza){
			$numDoc = $this->UltimoNumDoc($tipoDoc);
			$sql="INSERT INTO bco_documentos 
					(fecha,fechacreacion, importe, folio,referencia, concepto, idbancaria, beneficiario, idbeneficiario, status, conciliado, impreso, asociado, proceso, idclasificador, idDocumento, posibilidadpago, xml, idmoneda,idTipoDoc,bancoDestino,numBancoDes,tipocambio,tipopoliza,numDoc) VALUES
					('$fecha', DATE_SUB(NOW(), INTERVAL 6 HOUR),$importe, '$folio','$referencia', '$concepto', $idbancaria,$beneficiario, $idbenefeciario, 1, 0, 2, 0, $proceso, $idclasificador, $idDocumento, NULL, NULL, $idmoneda,$idTipoDoc,$bancoDestino,'$numBancoDes',$tipocambio,$tipopoliza,".($numDoc+1)."); ";
		return $this->insert_id($sql);
		}
		function InsertDocumentoBasico($tipoDoc){
			$numDoc = $this->UltimoNumDoc($tipoDoc);
			
			$sql="INSERT INTO bco_documentos 
					(idDocumento,status,proceso,numdoc) VALUES
					($tipoDoc,1,2,".($numDoc+1)."); ";
		return $this->insert_id($sql);
		}
		function ejercicio($ejer){
			$sql = $this->query("select * from cont_ejercicios where NombreEjercicio=".$ejer);
			if($sql->num_rows>0){
				if($s = $sql->fetch_array()){
					return $s['Id'];
				}
			}else{
				return 0;
			}
		}
		function cont_ejercicios(){
			$sql = $this->query("select * from cont_ejercicios");
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
		function idUltimoDocumento($idDoc){//ultimo docume
			$sql = $this->query("select id from bco_documentos where idDocumento=$idDoc order by id desc limit 1");
			if($s = $sql->fetch_array()){
				return $s['id'];
			}
		}
		function idUltimoDocumentoBasico($idtipoDoc){//ultimo docume
			$sql = $this->query("select id from bco_documentos where idDocumento=$idtipoDoc and importe=0 order by id desc limit 1");
			if($sql->num_rows>0){
				if($s = $sql->fetch_array()){
					return $s['id'];
				}
			}else{
				return 0;
			}
		}
		function actuliazanumerodocumento($numerocheque,$idbancaria){
			$longitudActual=strlen($numerocheque);
			$sumar=$numerocheque+1; 
			$longitudFinal=strlen($sumar); 
			$cantidadCeros=$longitudActual-$longitudFinal; 
			$suma=str_repeat("0",$cantidadCeros).$sumar; 
			    
			$sql =$this->query("update bco_cuentas_bancarias set numeroactual='$suma' where  idbancaria=".$idbancaria);
			//$sql =$this->query("update bco_controlNumeroCheque set actualrango='".$suma."' where numeroactual='' and idbancaria=".$idbancaria);
			
		}
		function cancela($opc,$idDoc){
			
			$sql = ("update bco_documentos set status=".$opc." where id=".$idDoc);
				if($this->query($sql)){ return true; }else{ return false;}
		}
		function validaguardado($folio,$cuenta){
			$sql=$this->query("select id from bco_documentos where idbancaria=".$cuenta." and folio='".$folio."'");
			if($sql->num_rows>0){
				while($r = $sql->fetch_array()){
					return $r['id'];
				}
			}else{
				return 0;
			}
		}
		function updateimpreso($idDocumento){
			$sql = ("update bco_documentos set impreso=1 where id=".$idDocumento);
			if($this->query($sql)){ return true; }else{ return false;}
		}
		
		function status($idDocumento,$status){
			$sql = ("update bco_documentos set status=$status where id=".$idDocumento);
			if($this->query($sql)){ return true; }else{ return false;}
		}
		function listadoCheques(){//el status 3 es borrado
		// select d.*, c.cuenta,
				// (case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) when 0 then 'TRASPASO' end) razon_social,m.description 
				// from bco_documentos d,bco_cuentas_bancarias c,mrp_proveedor prv,comun_cliente cl,cont_coin m ,nomi_empleados em
				// where   (case d.beneficiario when 5 then cl.id=d.idbeneficiario when 1 then prv.idPrv=d.idbeneficiario  when 2 then em.idEmpleado=d.idbeneficiario  else d.idbeneficiario=0  end)
				// and c.idbancaria=d.idbancaria and  d.status!=3  and d.idDocumento=1 and m.coin_id=d.idmoneda
				// group by d.id order by d.id desc
			$sql = $this->query("
				select d.*, c.cuenta,
				(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) when 0 then 'TRASPASO' end) razon_social,m.description 
				from bco_documentos d
									
					left join  bco_cuentas_bancarias c on c.idbancaria=d.idbancaria 
					left join 	mrp_proveedor prv on (case d.beneficiario when 1 then prv.idPrv=d.idbeneficiario  end)
					left join 	comun_cliente cl on (case d.beneficiario when 5 then cl.id=d.idbeneficiario  end)
					left join 	cont_coin m on  m.coin_id=d.idmoneda
					left join 	nomi_empleados em on (case d.beneficiario when 2 then em.idEmpleado=d.idbeneficiario  end)

				where  d.status!=3  and d.idDocumento=1  and d.importe>0
				group by d.id order by d.id desc");
			return $sql;
			
		}
		function editados($idDocumento){
			$sql = $this->query("select * from bco_documentos where status!=3 and  id=".$idDocumento);
			$array = $sql->fetch_assoc();
			return $array;	
			
		}
		function numchequsado($numcheque,$idbancaria){
			$sql = $this->query("select * from bco_documentos where idbancaria=".$idbancaria." and folio='".$numcheque."' ");
			if($sql->num_rows>0){
				return 1;//ya esta registrado
			}else{
				$sql2 = $this->query("select numInicialCheque,numFinalCheque,controlNumeroCheq,numautomaticacheq from bco_cuentas_bancarias where idbancaria=".$idbancaria);
				$numero = $sql2->fetch_assoc();
				if($numero['controlNumeroCheq']==-1){//si lleva rango
						if($numero['numFinalCheque']!="" && $numero['numInicialCheque']!=""){
							
							if(intval($numcheque) <= intval($numero['numFinalCheque']) && intval($numcheque)>=intval($numero['numInicialCheque'])){
								return 0;
							}else{
								return 2;
							}
						}
					
				}else{// si no lleva rango el puede aser loq quiera en teoria se bloquera el campo
				//para captura de folio si es automatico el consecutivo
					return 0;
					// $sql2 = $this->query("select numeroactual from bco_cuentas_bancarias where idbancaria=".$idbancaria);
					// $numero = $sql2->fetch_assoc();
					// if(intval($numcheque) >= intval($numero['numeroactual'])){
						// return 0;//todo ok
					// }else{
						// return 2;//folio invalido
					// }
				}
			}
		}
		
		function buscaBeneficiario($idbeneficiario){
			$sql = $this->query("select idPrv,razon_social,cuenta from mrp_proveedor where idPrv=".$idbeneficiario);
			return $sql->fetch_assoc();
		}
		function borrar($idDocumento,$status){
			$sql = ("update bco_documentos set status=$status where id=".$idDocumento."; 
					update cont_polizas set activo=0 where idDocumento=".$idDocumento.";
					update cont_movimientos set Activo=0 where idPoliza=(select id from cont_polizas where idDocumento=".$idDocumento.");");
		
			if($this->multi_query($sql)){
				return true;
			}else{
				return false;
			}
		}
		function actualizaDocumento($id,$fecha,$folio,$importe,$referencia,$concepto,$idbancaria,$beneficiario,$idbeneficiario,$proceso,$idclasificador,$status,$impreso){
			$update="";
			if($fecha){ $update .= ",fecha='".$fecha."'";}
			if($folio){ $update .= ",folio='$folio'"; }
			if($importe){ $update .= ",importe=$importe"; }
			if($referencia){ $update .= ",referencia='$referencia'";}
			if($concepto){ $update .= ",concepto='$concepto'";}
			if($impreso){$update .= ",impreso=$impreso";}
			if($proceso){ $update .= ",proceso=$proceso"; }
			if($idclasificador){ $update .= ",idclasificador=$idclasificador";}
			$sql = "update  bco_documentos set fechacreacion=DATE_SUB(NOW(), INTERVAL 6 HOUR),idbancaria=$idbancaria,beneficiario=$beneficiario,idbeneficiario=$idbeneficiario,status=$status $update where id=".$id; 
			if($this->query($sql)){
				return true;
			}else{
				return false;
			}
		}
		function numDocumentoEdicion($idbancaria,$numerocheque,$idDocumento){
			$sql = $this->query("select idbancaria,folio from bco_documentos where id=".$idDocumento);
			$ed = $sql->fetch_assoc();
			if($ed['idbancaria']==$idbancaria && $ed['folio']==$numerocheque){
				return 0;//entonces son los mismos y guardara
			}else{
				$sql = $this->query("select * from bco_documentos where idbancaria=".$idbancaria." and folio='".$numerocheque."' ");
				if($sql->num_rows>0){
					return 1;//ya esta
				}else{
					$sql2 = $this->query("select numInicialCheque,numFinalCheque,controlNumeroCheq from bco_cuentas_bancarias where idbancaria=".$idbancaria);
					$numero = $sql2->fetch_assoc();
					if($numero['controlNumeroCheq']==-1){//si lleva rango
						if($numero['numFinalCheque']!="" && $numero['numInicialCheque']!=""){
							
							if(intval($numerocheque) <= intval($numero['numFinalCheque']) && intval($numerocheque)>=intval($numero['numInicialCheque'])){
								return 0;
							}else{
								return 2;
							}
						}else{
							return 0;
						// $sql2 = $this->query("select numeroactual from bco_cuentas_bancarias where idbancaria=".$idbancaria);
						// $numero = $sql2->fetch_assoc();
						// if(intval($numerocheque) >= intval($numero['numeroactual'])){
							// return 0;//todo ok
						// }else{
							// return 2;//folio invalido
						// }
						}
				}
			}
		}
	}
	function procesoUpdate($proceso,$id){
		$sql = "update bco_documentos set proceso=$proceso where id=".$id;
		if($this->query($sql)){
			return true;
		}else{
			return false;
		}
	}
	function polizaDocumento($idDocumento){
		$idpoliza = $this->query("select * from cont_polizas where activo=1 and idDocumento=".$idDocumento." and id not in( select idPoliza from bco_devoluciones where idDocumento=".$idDocumento." union select idPolizaInvertida from bco_devoluciones where idDocumento=".$idDocumento.")");
		if($idpoliza->num_rows>0){
			return $idpoliza->fetch_assoc();
		}else{
			return 0;
		}
	}
	function polizaDocumentoDevolucion($idDocumento){
		$idpoliza = $this->query("select * from cont_polizas where activo=1 and idDocumento=".$idDocumento);
		if($idpoliza->num_rows>0){
			return $idpoliza->fetch_assoc();
		}else{
			return 0;
		}
	}
	function deletePoliza($idpoliza){
	
		$sql = "
		DELETE FROM cont_polizas, cont_movimientos
		USING cont_polizas
		INNER JOIN cont_movimientos 
		WHERE cont_polizas.id = $idpoliza and cont_movimientos.idPoliza=cont_polizas.id";
		if($this->query($sql)){
			if ($vcarga = opendir($this->path('../cont/')."xmls/facturas/".$idpoliza)){
					while($file = readdir($vcarga)){
						if ($file != "." && $file != ".."){
							if (!is_dir($this->path('../cont/')."xmls/facturas/".$idpoliza.$file)){
									unlink($this->path('../cont/')."xmls/facturas/".$idpoliza.$file);
								}
							}
						}rmdir($this->path('../cont/')."xmls/facturas/".$idpoliza);
				}
			return 1;
		}else{
			return 0;
		}
	}
	function eliminaPolizaDocumento($idDoc){
		$poliza = $this->polizaDocumento($idDoc);
		$sql ="DELETE FROM cont_polizas, cont_movimientos
		USING cont_polizas
		INNER JOIN cont_movimientos 
		WHERE cont_polizas.idDocumento = $idDoc and cont_movimientos.idPoliza=cont_polizas.id";
		if( $this->query($sql) ){
			
			
			if($poliza){
				$this->inactivaRelacionPrv($poliza['id']);
				$this->deleteMovGrupoTodo($poliza['id']);
				if ($vcarga = opendir($this->path('../cont/')."xmls/facturas/".$poliza['id'])){
					while($file = readdir($vcarga)){
						if ($file != "." && $file != ".."){
							if (!is_dir($this->path('../cont/')."xmls/facturas/".$poliza['id'].$file)){
									unlink($this->path('../cont/')."xmls/facturas/".$poliza['id'].$file);
								}
							}
						}rmdir($this->path('../cont/')."xmls/facturas/".$poliza['id']);
				}
			}
			return 1;
		
		}else{
			return 0;
		}
	}
	function eliminaMovimientosPoliza($idDoc){
		$sql ="DELETE FROM cont_movimientos
		USING cont_polizas
		INNER JOIN cont_movimientos 
		WHERE cont_polizas.idDocumento = $idDoc and cont_movimientos.idPoliza=cont_polizas.id";
		if( $this->query($sql) ){
			return 1;
		
		}else{
			return 0;
		}
	}
	function inactivaRelacionPrv($idpoliza){
		$sql = $this->query("update cont_rel_pol_prov set activo=0 where idPoliza=".$idpoliza);
	}
	function identificaMovInverso($idDoc,$num,$cuenta,$movimiento,$tipo){
		if($tipo==1){ $like = "not  like '%$movimiento'";}else{ $like="like '%$movimiento'";}
		$sql = $this->query("select p.* from cont_polizas p, cont_movimientos m where p.idDocumento=$idDoc and p.numero='$num' and m.TipoMovto $like and m.Importe like '-%' and m.Cuenta=$cuenta and idtipopoliza=1  and m.idPoliza=p.id");
		return $sql;
	}
	function inactivActivaInverso($id,$status){
		$sql = $this->query("
				update  cont_polizas 
				inner join cont_movimientos on cont_movimientos.idPoliza=cont_polizas.id
				set 
				cont_polizas.activo=".$status.", cont_movimientos.Activo=".$status." 
				where  
				cont_polizas.id=".$id);
			
	}
	function inactivaPoliza($idDoc,$status,$num,$cuenta){
		$sql = $this->query("
				update  cont_polizas 
				inner join cont_movimientos on cont_movimientos.idPoliza=cont_polizas.id
				set 
				cont_polizas.activo=".$status.", cont_movimientos.Activo=".$status." 
				where 
				cont_polizas.idDocumento=$idDoc and cont_polizas.numero='$num' and cont_movimientos.Cuenta=$cuenta and cont_movimientos. idtipopoliza=2" 
				);
	}
	function multi_queryconsulta($query)
	{
		$result = $this->multi_query($query);
		return $result;
	}
	function tipodocumento($idDocPadre){//recibe el id del tipo de documento para traer los conceptos// deposito cheque ingreso etc
		$sql = $this->query("select * from bco_tiposDocumentoConcepto where id=".$idDocPadre." and idstatus=1");
		return $sql;
	}
	function editarDocumento($id){
		$sql = $this->query("select * from bco_documentos where id=".$id);
		return $sql->fetch_assoc();
	}
	function formapago(){
		$sql = $this->query("select * from forma_pago where claveSat != '' ORDER BY claveSat");
		return $sql;
	}
	function bancos(){
		$sql = $this->query("select * from cont_bancos");
		return $sql;
	}
	function creaEgreso($fecha,$importe,$referencia,$concepto,$idbancaria,$tipobeneficiario,$idbeneficiario,$idclasificador,$idmoneda,$idDocumento,$proceso,$idTipoDoc,$bancoDestino,$numBancoDes,$tc,$formadeposito,$tipopoliza){
		$sql="INSERT INTO bco_documentos (fecha, fechacreacion, importe, referencia, concepto, idbancaria, beneficiario, idbeneficiario, status, conciliado, impreso, asociado, proceso, idclasificador, idDocumento, posibilidadpago, xml, idmoneda,idTipoDoc,bancoDestino,numBancoDes,tipocambio,formadeposito,tipoPoliza) VALUES
		('$fecha', DATE_SUB(NOW(), INTERVAL 6 HOUR),$importe, '$referencia', '$concepto', $idbancaria, $tipobeneficiario, $idbeneficiario, 1, 0, 2, NULL, $proceso, $idclasificador, $idDocumento, NULL, NULL, $idmoneda,$idTipoDoc,$bancoDestino,'$numBancoDes',$tc,$formadeposito,$tipopoliza); ";
		return $this->insert_id($sql);
		// if($this->query($sql)){
			// return 1;
		// }else{
			// return 0;
		// }
	}
	function listadoEgreso(){
		return $this->query("select d.*, c.cuenta,
		(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social  when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) when 0 then 'TRASPASO' end) razon_social,
		m.description 
from bco_documentos d
left join  bco_cuentas_bancarias c on c.idbancaria=d.idbancaria 
left join 	mrp_proveedor prv on (case d.beneficiario when 1 then prv.idPrv=d.idbeneficiario end)
left join 	comun_cliente cl on (case d.beneficiario when 5 then cl.id=d.idbeneficiario  end)
left join 	cont_coin m on  m.coin_id=d.idmoneda
left join 	nomi_empleados em on (case d.beneficiario when 2 then em.idEmpleado=d.idbeneficiario  end)
where d.status=1  and d.idDocumento=5  and d.importe>0
		group by d.id order by d.id desc");
	}
	function buscabancos($idprv,$beneficiario){//$idprv se comvertira en id cliente si es beneficiario
		if($beneficiario==1){ $condicion = "and idPrv=".$idprv; }elseif($beneficiario==5){ $condicion = "and idCliente=".$idprv;}
		if($beneficiario == 2){
			$sql = $this->query("select e.idbanco,e.numeroCuenta,b.nombre from nomi_empleados e,cont_bancos b where e.activo=-1 and e.idEmpleado = $idprv and b.idbanco=e.idbanco");
		}else{
			$sql = $this->query("select b.nombre,p.idbanco,p.numCT,p.id from cont_bancosPrv p,cont_bancos b where b.idbanco=p.idbanco $condicion");
		}
			if($sql->num_rows>0){
				return $sql;
			}else{ return  0; }
		}
	function numbancos($idbancoprv,$prove,$beneficiario){
		//if($beneficiario==1){ $condicion = "and idPrv=".$prove; }elseif($beneficiario==5){ $condicion = "and idCliente=".$prove;}
		if($beneficiario == 2){
			$sql = $this->query("select idbanco,numeroCuenta numCT from nomi_empleados where activo=-1 and idEmpleado = ".$prove);
		}else{
			$sql = $this->query("select numCT from cont_bancosPrv where id=".$idbancoprv);
		}
		if($sql->num_rows>0){
			if($es=$sql->fetch_assoc()){
				return $es['numCT'];
			}
		}else{ return  0; }
	}
	
	function eliminaDocumento($id){
		$sql = "delete from bco_documentos where id=".$id;
		if($this->query($sql)){
			
			/*elimina documentos creados a partir de traspasos */
			$sql = $this->query("delete from bco_documentos where idtraspaso=".$id);
			if ($vcarga = opendir($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id)){
					while($file = readdir($vcarga)){
						if ($file != "." && $file != ".."){
							if (!is_dir($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id."/".$file)){
									unlink($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id."/".$file);
								}
							}
						}rmdir($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id);
				}
			return 1;
			/* elimina pagos y aplicaciones creadas con el documento */
			$this->eliminaDocumentoCXCCXCP($id);	
			
		}else{
			return 0;
		}
	}
	function actualizaEgreso($inserbasic,$idSeg,$idSuc,$numeroformap,$edita,$id,$fecha,$importe,$referencia,$concepto,$idbancaria,$tipobeneficiario,$idbeneficiario,$idclasificador,$idDocumento,$idTipoDoc,$bancoDestino,$numBancoDes,$formadeposito,$proceso,$idmoneda,$tc,$folio,$tipoPoliza,$traspaso,$comision,$statusanticipo=0,$idUser=0){
		if(!$tipoPoliza){$tipoPoliza=0;}if(!$idbeneficiario){ $idbeneficiario = $tipobeneficiario = 0; }
		if($edita==0){
			$idComprueba = $this->compruebaDisponibilidad($id);
			if($idComprueba!=0){//ya se ocupo
				$basico = $this->idUltimoDocumentoBasico($idComprueba);
				if($basico>0){
					$id = $basico;
				}else{
					$id = $this->InsertDocumentoBasico($idComprueba);//le paso el tipo de documento q debe hacer
					
				}
			}else{
				$existe = $this->compruebaExistencia($id);
				if($existe==0){//si es igual a 0 es qno existe y trae algo loco la session 
					$idtemporal = $this->InsertDocumentoBasico($inserbasic);
					$id = $idtemporal;
				}
			}
		}
		if(!$formapago){ $formapago=0;}
		if(!$numeroformap){ $numeroformap="-";}
		if(!$idSuc){ $idSuc=0;}
		if(!$idSeg){ $idSeg=0;}
		$sql=("
		UPDATE bco_documentos 
			set fecha = '$fecha', 
			fechacreacion = DATE_SUB(NOW(), INTERVAL 6 HOUR),
			importe = $importe, 
			referencia = '$referencia',
			concepto = '$concepto',
			idbancaria = $idbancaria,
			beneficiario = $tipobeneficiario,
			idbeneficiario = $idbeneficiario,
			idclasificador = $idclasificador,
			idTipoDoc = $idTipoDoc,
			formadeposito = $formadeposito,
			bancoDestino = $bancoDestino,
			numBancoDes = '$numBancoDes',
			proceso = $proceso,
			idmoneda = $idmoneda,
			tipocambio = $tc,
			folio = '$folio',
			tipoPoliza = $tipoPoliza,
			traspaso = $traspaso,
			comision = $comision,
			anticipo	 =	$statusanticipo,
			idUser	 = $idUser,
			numeroformapago = '$numeroformap',
			idSuc			= $idSuc,
			idSeg			= $idSeg
			where id=".$id);
			
		if($this->query($sql)){
			return $id;
		}else{
			return 0;
		}
	}
	function documentosSubcategorias($idDoc,$idSub,$porcen,$importe){
		$sql = $this->query("INSERT INTO bco_documentoSubcategorias 
					(idDocumento, idSubcategoria, porcentaje, importe)
					VALUES ( $idDoc, $idSub, $porcen, $importe);");
	}
	function consultaSubcategoriasDoc($idDoc){
		$sql = $this->query("select * from bco_documentoSubcategorias where idDocumento=".$idDoc);
		return $sql;
	}
	function eliminaSubcategoriaDoc($idDoc){
		$sql ="delete from bco_documentoSubcategorias where idDocumento=".$idDoc;
		if( $this->query($sql) ){
			return 1;
		}else{
			return 0;
		}
	}
	
	// retencion //
	function complementoRetenciones(){
		$sql = $this->query("select * from bco_complementos");
		return $sql;
	}
	function tipoDividendo(){
		$sql = $this->query("select * from bco_tipo_dividendo");
		return $sql;
	}
	function tipoContribuyente(){
		$sql = $this->query("select * from bco_tipo_contribuyente");
		return $sql;
	}
	function retencionPaises(){
		$sql = $this->query("select * from paises");
		return $sql;
	}
	function retencionEstados(){
		$sql = $this->query("select * from estados where idpais=1");
		return $sql;
	}
	/* retencion fin */
	function meses(){
		$sql = $this->query("select * from meses");
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
	function validaBancos(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=1932");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	function validaAppministra(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=1959 or idmenu=1960");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	function validaFacturacion(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=1595");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	function infoConfiguracion(){
		$sql = $this->query("select * from bco_configuracion");
		if($sql->num_rows>0){
			return $sql->fetch_assoc();
		}else{
			return 0;
		}
	}
	function configCuentas(){
		$sql= $this->query("select * from cont_config");
		return $sql->fetch_assoc();
	}
	function getFirstLastExercise($t,$modulo)
		{
			if(intval($t))
			{
				$acomodo = 'DESC';
			}
			else
			{
				$acomodo = 'ASC';	
			}
			$myQuery = "SELECT NombreEjercicio FROM ".$modulo."_ejercicios ORDER BY NombreEjercicio $acomodo LIMIT 1";
			$InicioEjercicio = $this->query($myQuery);
			$InicioEjercicio = $InicioEjercicio->fetch_assoc();
			return $InicioEjercicio['NombreEjercicio'];

		}
	function InicioEjercicio()
		{
			$myQuery = "SELECT InicioEjercicio FROM cont_config WHERE Id=1";
			$InicioEjercicio = $this->query($myQuery);
			$IE = $InicioEjercicio->fetch_assoc();
			return $IE;
		}
		function idex($NameEjercicio,$modulo)
		{
			$myQuery = "SELECT Id FROM ".$modulo."_ejercicios WHERE NombreEjercicio = $NameEjercicio";
			$id = $this->query($myQuery);
			$id = $id->fetch_assoc();
			return $id['Id'];
		}
	function idBeneficiario($idDoc){
		$sql = $this->query("select beneficiario,idbeneficiario,importe from bco_documentos where id=".$idDoc);
		return $sql->fetch_assoc();
	}
	function clienteBeneficiario(){
		return $this->query("select * from comun_cliente where beneficiario_pagador=-1 order by nombre asc");
	}
	function clienteInfo($id){
		$sql = $this->query("select * from comun_cliente where id=".$id);
		return $sql->fetch_assoc();
	}
	/* Moneda extranjera 
	 * consulta tipo cambio dia consulcambio()
	 * consulta tipo cambio mes tipoCambio()
	 */
	function consulcambio($idmodena,$fecha){
		$sql=$this->query("select * from cont_tipo_cambio where fecha='$fecha' and moneda=".$idmodena);
		if($sql->num_rows>0){
			return $sql;
		}else{
			return "0";
		}
	}
	function tipoCambio($idmoneda,$fecha){
		
		$sql = $this->query("select c.*,m.codigo from cont_tipo_cambio c,cont_coin m where m.coin_id=c.moneda and c.moneda=".$idmoneda." and c.fecha like '$fecha%'");
		return $sql;
	}
	/* actualizacion de catalogo INGRESOS
	 * 
	 */
	function updatePrvCuentaEgre($id,$cuenta){
		$sql = $this->query("update mrp_proveedor set cuenta=$cuenta where idPrv=".$id);
	}
	function updateClienteCuentaEgre($id,$cuenta){
		$sql = $this->query("update comun_cliente set cuentaprv=$cuenta where id=".$id);
	}
	/* FIN ACTUALIZACION */	
	/* movimiento inverso 
	 * sera copia de la poliza solo con los movimientos inversos*/
 function movInversoPoliza($idpoliza,$periodo,$ejercicio,$fecha,$conceptocopy,$idDocInverso){//pendiente copiar
		$numpol = ($this->UltimoNumPol($periodo,$ejercicio,2)+1);
		$sql = ("
			insert into cont_polizas 			
			(idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,referencia,concepto,fecha,fecha_creacion,activo,eliminado,relacionExt,beneficiario,numero,rfc,idbanco,numtarjcuent,saldado,idCuentaBancariaOrigen,idDocumento,tipoBeneficiario,usuario_creacion,usuario_modificacion,fecha_modificacion )
		(select 
			idorganizacion,$ejercicio,$periodo,$numpol,idtipopoliza,referencia,'$conceptocopy','$fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR),activo,eliminado,relacionExt,beneficiario,numero,rfc,idbanco,numtarjcuent,saldado,idCuentaBancariaOrigen,$idDocInverso,tipoBeneficiario,".$_SESSION["accelog_idempleado"].",".$_SESSION["accelog_idempleado"].",DATE_SUB(NOW(), INTERVAL 6 HOUR) 
 		from 
 			cont_polizas where id=".$idpoliza.")");
	 		$idPoliza = $this->insert_id($sql);
	 		$sql2=("insert into cont_movimientos (IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Persona,FormaPago,Factura,tipocambio) (
			select ".$idPoliza.",NumMovto,IdSegmento,IdSucursal,Cuenta,case TipoMovto when 'Abono' then 'Cargo' when 'Abono M.E' then 'Cargo M.E.' when 'Cargo M.E.' THEN 'Abono M.E' else   'Abono' end,Importe,Referencia,'$conceptocopy',Activo,DATE_SUB(NOW(), INTERVAL 6 HOUR),Persona,FormaPago,Factura,tipocambio from 
			cont_movimientos where IdPoliza=".$idpoliza."
			);");
		
	 	if($this->query($sql2)){
	 		return $idPoliza;
	 	}else{
	 		return 0;
	 	}
 	
}

function UltimoNumDevuelto($idDoc)
{
	$sql ="SELECT numDevolucion FROM bco_devoluciones WHERE idDocumento=$idDoc ORDER BY numDevolucion DESC LIMIT 1";
	$mov = $this->query($sql);
	$mov = $mov->fetch_assoc();
	return $mov['numDevolucion'];
}
function almacenaDevolucion($idDoc,$poliOrigen,$poliInver,$numDevolucion,$idDocInverso){
	if(!$numDevolucion){ $numDevolucion=0;}
	$sql ="insert into bco_devoluciones 
	(idDocumento,IdPoliza,idPolizaInvertida,numDevolucion,idDocInverso) 
	values 
		($idDoc,$poliOrigen,$poliInver,$numDevolucion+1,$idDocInverso)";
	if($this->query($sql)){
	 	return 1;
 	}else{
 		return 0;
 	}	
}
 
	/* fin inverso */
	
/* Cobrar Cheque */
function cobrarCheque($status,$idDoc,$fecha){
	$sql ="update bco_documentos set cobrado=$status,fechaaplicacion='$fecha' where id=".$idDoc;
	if($this->query($sql)){
	 	return 1;
 	}else{
 		return 0;
 	}
}
/* fin Cobrado */
function rfcOrganizacion(){
		$sql=$this->query("select RFC,nombreorganizacion from organizaciones ");
		return $sql->fetch_assoc();
	}
function movimientosPoliza($idDoc){
	$sql=$this->query("select m.* from cont_polizas p, cont_movimientos m
					where p.activo=1 and p.idDocumento=$idDoc and
 					p.id not in( select idPoliza from bco_devoluciones where idDocumento=$idDoc union select idPolizaInvertida from bco_devoluciones where idDocumento=$idDoc) 
 					and m.idPoliza=p.id  and m.TipoMovto not like '%M.E%';");
	return $sql;
}
function importMovBancoPoliza($idDoc,$cuentaBancos){
	$sql=$this->query("select m.importe,p.beneficiario from cont_polizas p, cont_movimientos m
					where p.activo=1 and p.idDocumento=$idDoc and
 					p.id not in( select idPoliza from bco_devoluciones where idDocumento=$idDoc union select idPolizaInvertida from bco_devoluciones where idDocumento=$idDoc) 
 					and m.idPoliza=p.id  and m.TipoMovto not like '%M.E%' and m.Cuenta=$cuentaBancos ;");
	if($sql->num_rows>0){
		$row = $sql->fetch_assoc();
		return $sql->fetch_assoc();
	}else{
		return 0;
	}
}

function borraFacturaForm($idDoc,$Archivo)
{
	$myQuery = "UPDATE cont_movimientos m,cont_polizas p SET m.Factura = '-', m.Referencia = '' WHERE p.idDocumento = $idDoc AND m.Factura LIKE '%$Archivo%';";
	$this->query($myQuery);
}	

/* fin mov xml */
function GetAllPolizaInfoActiva($id)
{
	$GetAllPolizaInfo =$this->query( "SELECT * FROM cont_polizas WHERE id=$id and activo=1");
	if($GetAllPolizaInfo->num_rows>0){
		$GPI = $GetAllPolizaInfo->fetch_assoc();
	}else{
		 $GPI=0;
	}
	return $GPI;
	
}
/* grupo facturas 
 * 
 * verificagrupo 
 * Verifica si existen mas facturas como grupo
 * de ser asi un nuevo xml solo anexa otro registro al grupo
 * */
function verificagrupo($idpoli){
	$sql = $this->query("select * from cont_grupo_facturas where idPoliza=".$idpoli);
	if($sql->num_rows>0){
		return 1;
	}else{
		return 0;
	}
}
/* Movimientos marcados como grupo de facturas */
function movMultipleFactUpdate($idMov, $poliza, $idmovPol, $xml, $uuid){
	$sql = $this->query("INSERT INTO cont_grupo_facturas 
					(IdPoliza, NumMovimiento, Factura, UUID) VALUES
					($poliza, $idmovPol, '$xml', '$uuid');");
	$sql = $this->query("update cont_movimientos set Referencia='Grupo de facturas', Factura='', MultipleFacturas=1 where Id=".$idMov);
	
}
function movUUID($uuid,$idMov,$xml){
	$sql = $this->query("update cont_movimientos set Referencia='$uuid',Factura='$xml', MultipleFacturas=0 where Id=".$idMov);
}
/* fin mov marcados */
function numMovGrupo($idPoli){
	$sql = $this->query("select COUNT(*) num from cont_grupo_facturas where idPoliza=".$idPoli." group by NumMovimiento limit 1");
	if($sql->num_rows>0){
		if($row = $sql->fetch_object()){
			return $row->num;
		}
	}else{
		return 0;
	}
}
function deleteMovGrupoTodo($poli){
	$sql = "delete from cont_grupo_facturas where idPoliza=".$poli;
	if($this->query($sql)){
		return 1;
	}else{
		return 0;
	}
}
function deleteMovGrupo($poli,$xml){
	$sql = "delete from cont_grupo_facturas where idPoliza=$poli and Factura='$xml'";
	if($this->query($sql)){
		return 1;
	}else{
		return 0;
	}
}
function ultimoGrupo($poli){
	$sql = $this->query("select * from cont_grupo_facturas where idPoliza=".$poli);
	return $sql->fetch_array();
}

/* fin grupo facturas */

/* Empleados para documentos */
function empleados()
{
	$sql = $this->query("select * from nomi_empleados where activo=-1");
	return $sql;
}
function datosempleados($idempleado)
{
	$sql = $this->query("select * from nomi_empleados where activo=-1 and idEmpleado=".$idempleado);
	return $sql->fetch_assoc();
}
function pasivoCirculante(){//para las sueldos 
	$sql=$this->query("select * from cont_accounts where   account_code like '2.1%' and affectable=1 ");
	return $sql;
}
/* fin empleados */

/* traspaso */
function creaIngresoNoDepositadoTraspaso($fecha,$importe,$referencia,$concepto,$idbancaria,$tipobeneficiario,$idbeneficiario,$idclasificador,$idmoneda,$proceso,$idTipoDoc,$tipocambio,$idtraspaso){
		$numDoc  = $this->UltimoNumDoc(3);
		$sql=("INSERT INTO bco_documentos (fecha, fechacreacion, importe, referencia, concepto, idbancaria, beneficiario, idbeneficiario, status, conciliado, impreso, asociado, proceso, idclasificador, idDocumento, posibilidadpago, xml, idmoneda,idTipoDoc,tipocambio,idtraspaso,numdoc) VALUES
		('$fecha', DATE_SUB(NOW(), INTERVAL 6 HOUR),$importe, '$referencia', '$concepto', $idbancaria, $tipobeneficiario, $idbeneficiario, 1, 0, 2, NULL, 2, 2, 3, NULL, NULL, $idmoneda,$idTipoDoc,$tipocambio,$idtraspaso,".($numDoc+1)."); ");
		return $this->insert_id($sql);
}
function creaDepositoTraspaso($idSeg,$idSuc,$numeroformapago,$fecha,$importe,$referencia,$concepto,$idbancaria,$formadeposito,$idTipoDoc,$moneda,$tc,$idtraspaso){
		$numDoc  = $this->UltimoNumDoc(4);
		
		$sql=("INSERT INTO bco_documentos (fecha, fechacreacion, importe, referencia, concepto, idbancaria, status, conciliado, impreso, asociado, proceso, idDocumento, posibilidadpago, xml, idmoneda,formadeposito,idTipoDoc,tipocambio,idtraspaso,idclasificador,fechaaplicacion,numDoc,idSuc,idSeg,numeroformapago) VALUES
		('$fecha', DATE_SUB(NOW(), INTERVAL 6 HOUR),$importe, '$referencia', '$concepto', $idbancaria, 1, 0, 2, NULL, 4, 4, NULL, NULL, $moneda,$formadeposito,$idTipoDoc,$tc,$idtraspaso,2,'$fecha',".($numDoc+1).",$idSuc,$idSeg,'$numeroformapago'); ");
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
function documentosDestinotraspaso($id){
	$sql = $this->query("select id,idDocumento from bco_documentos where idtraspaso=".$id);
	if($sql->num_rows>0){
		$r = $sql->fetch_assoc();
		return $r['id'].'/'.$r['idDocumento'];
	}else{
		 return 0;
	}
}
function documentoActivoInactivoTraspaso($status,$idDoc){
	$sql = "update bco_documentos set status=$status where idtraspaso=".$idDoc;
	if($this->query($sql)){
		return 1;
	}else{
		return 0;
	}
}
/* fin traspaso */

/* actualiza poliza para polizas manuales */


function actualizaPolizaManual($fecha,$cuenta,$importe,$idpoli,$moneda,$tc){
	$this->query("delete from  cont_movimientos where NumMovto=1 and IdPoliza=".$idpoli." and (TipoMovto='Cargo M.E.' || TipoMovto='Abono M.E')");
	if($moneda!=1){
			$this->query("
				insert into cont_movimientos (IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Multiplefacturas,FormaPago,conciliado,tipocambio)  (
	 			select IdPoliza,NumMovto,IdSegmento,IdSucursal,$cuenta,case TipoMovto when 'Cargo' then 'Cargo M.E.' when 'Abono' then 'Abono M.E' end,$importe,Referencia,Concepto,Activo,DATE_SUB(NOW(), INTERVAL 6 HOUR),Factura,Multiplefacturas,FormaPago,conciliado,$tc from cont_movimientos where NumMovto=1 and Activo=1 and IdPoliza=".$idpoli."
				)
				");
			$sql = "
			update  cont_polizas 
			inner join cont_movimientos on cont_movimientos.idPoliza=cont_polizas.id
			set 
			cont_polizas.fecha='$fecha', cont_movimientos.Importe=".number_format($importe*$tc,2,'.','')." , cont_movimientos.Cuenta=$cuenta, cont_movimientos.tipocambio=$tc ,cont_polizas.fecha_modificacion= DATE_SUB(NOW(), INTERVAL 6 HOUR),
			cont_polizas.usuario_modificacion=".$_SESSION["accelog_idempleado"]."
			where 
			cont_polizas.id=$idpoli and cont_movimientos.NumMovto=1 and (TipoMovto='Cargo' || TipoMovto='Abono')
			";
	}else{
		$sql = "
			update  cont_polizas 
			inner join cont_movimientos on cont_movimientos.idPoliza=cont_polizas.id
			set 
			cont_polizas.fecha='$fecha', cont_movimientos.Importe=$importe ,cont_movimientos.tipocambio=0.0000, cont_movimientos.Cuenta=$cuenta ,cont_polizas.fecha_modificacion= DATE_SUB(NOW(), INTERVAL 6 HOUR),
			cont_polizas.usuario_modificacion=".$_SESSION["accelog_idempleado"]."
			where 
			cont_polizas.id=$idpoli and cont_movimientos.NumMovto=1
			";
	}
	if($this->query($sql)){
		
		return 1;
	}else{
		return 0;
	}
			
	
}

/* fin actualizacion manual */

function existeConciliacion($idperiodo,$idejercicio,$idbancaria){
	$sql = $this->query("select * from bco_saldos_conciliacionBancos where periodo=".$idperiodo." and ejercicio=".$idejercicio." and idbancaria=".$idbancaria);
	if($sql->num_rows>0){
		return 1;
	}else{
		return 0;
	}
}

/* documento inverso de devolucion */
function documentoInverso($idOrigen,$fecha){
	$numDoc  = $this->UltimoNumDoc(2);
	$sql = "
		insert into bco_documentos
			(fecha,folio,importe,referencia,concepto,idbancaria,beneficiario,idbeneficiario,status,conciliado,proceso,idmoneda,tipocambio,tipoPoliza,comision,idDocumento,inverso,fechacreacion,idclasificador,idTipoDoc,numDoc)
			(select '$fecha',folio,importe,referencia,CONCAT('Documento inverso cheque No.',folio),idbancaria,beneficiario,idbeneficiario,1,conciliado,proceso,idmoneda,tipocambio,tipoPoliza,comision,2,$idOrigen,DATE_SUB(NOW(), INTERVAL 6 HOUR),5,2,".($numDoc+1)."
 		from bco_documentos where id=$idOrigen)";
 	return $this->insert_id($sql);
}
//la quitar de devolucion debe generar de nuevo el documento
//ya que en la conciliacion si vienen los movminetos de devoluciones
//NO SE PUEDE ELIMINAR EL DOCUMENTO DE ACTIVACION
function documentoReactivado($idOrigen){
	$numDoc  = $this->UltimoNumDoc(1);
	$sql = "
		 INSERT INTO bco_documentos ( fecha, fechacreacion, fechaaplicacion, folio, importe, referencia, concepto, idbancaria, beneficiario, idbeneficiario, status, conciliado, impreso, asociado, proceso, idclasificador, idDocumento, posibilidadpago, xml, idmoneda, formadeposito, idTipoDoc, bancoDestino, numBancoDes, tipocambio, tipoPoliza, cobrado, traspaso, idtraspaso, comision, interes,reactivadoc,numDoc)

	(select fecha, fechacreacion, fechaaplicacion, folio, importe, referencia, CONCAT('Reactivacion del cheque No.',folio), idbancaria, beneficiario, idbeneficiario, 1, conciliado, 0, asociado, proceso, idclasificador, idDocumento, posibilidadpago, xml, idmoneda, formadeposito, idTipoDoc, bancoDestino, numBancoDes, tipocambio, tipoPoliza, cobrado, traspaso, idtraspaso, comision, interes,$idOrigen,".($numDoc+1)."
 from bco_documentos where id=$idOrigen);";
 	return $this->insert_id($sql);
}
function regresaContador($idDocInverso,$idDocOrigen){
	$sql = "delete from bco_devoluciones where idDocInverso=$idDocInverso";
	if($this->query($sql)){
		$sql3 = $this->query("select numDevolucion from bco_devoluciones where idDocumento=$idDocOrigen");
		if($sql3->num_rows>0){
			//reiniciamos contador
				$sql2 ="
						UPDATE 
							bco_devoluciones
						SET 
							numDevolucion=0 where idDocumento=$idDocOrigen;
							
						SET @num=0 ;
						UPDATE
							bco_devoluciones
						SET
							numDevolucion=@num:=@num+1 where idDocumento=$idDocOrigen
						";
				if($this->dataTransact($sql2)){
					return 1;
				}else{
					return 0;
				}
			
		}else{
			$sql2 = "update bco_documentos set status=1 where id=$idDocOrigen ";
			if($this->query($sql2)){
			
				return 1;
			}else{
				return 0;
			}
		}
		
	}else{
		return 0;
	}
	
}
public function pagosCobrosSinAsignar($id,$cp,$moneda)
    {

        $myQuery = "SELECT p.*, (SELECT CONCAT('(',claveSat,') ',nombre) FROM forma_pago WHERE idFormapago = p.id_forma_pago) AS fp,
                    (SELECT codigo FROM cont_coin WHERE coin_id = p.id_moneda) AS Moneda, 
                    @c := (SELECT SUM(cargo) FROM app_pagos_relacion WHERE id_pago = p.id),
                    @a := (SELECT SUM(abono) FROM app_pagos_relacion WHERE id_pago = p.id),
                    @r := (IFNULL(@c,0) - IFNULL(@a,0)),
                    (p.abono - p.cargo + IFNULL(@r,0)) AS saldo
                    FROM app_pagos p
                    WHERE p.id_prov_cli = $id AND cargo = 0 AND cobrar_pagar = $cp and id_moneda=$moneda
                    
";
        return $this->query($myQuery);
    }
/* retenciones facturar */
function infoFactura(){
	$sql=$this->query("SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen");
	return $sql;
	
}
function pendienteTimbrar($claveComplemento,$fecha,$idPrv,$referencia,$mesInicial,$mesFinal,$ejercicio,$totalOperaciones,$totalGravado,$totalExento,$totalRetenciones,$trackID,$idComprobante,$idDocumento){
	$sql = ("INSERT INTO 
			bco_pendiente_timbrar 
				( claveComplemento, fecha, idPrv, referencia, mesInicial, mesFinal, ejercicio, totalOperaciones, totalGravado, totalExento, totalRetenciones, trackID,idComprobante,idDocumento)
			VALUES
				( '$claveComplemento', '$fecha', $idPrv, '$referencia', $mesInicial, $mesFinal, $ejercicio, $totalOperaciones, $totalGravado, $totalExento, $totalRetenciones, $trackID,'$idComprobante',$idDocumento);
	");
	return $this->insert_id($sql);
}
function impuestosRetencion($importBase,$tipoImpuesto,$impuestoRetenido,$tipoPago,$idRetencion){
	$sql= $this->query("INSERT INTO 
			bco_impuestos_retencion 
				(importBase, tipoImpuesto, impuestoRetenido, tipoPago, idRetencion)
			VALUES
				($importBase, '$tipoImpuesto', $impuestoRetenido, '$tipoPago', $idRetencion);
			");
}
function almacenaTimbrado($selloSAT,$fechaTimbrado,$UUID,$idRetencion,$nombreXML,$selloCFD){
	$sql = $this->query("update bco_pendiente_timbrar set timbrado=1, selloSAT = '$selloSAT' , fechaTimbrado='$fechaTimbrado' , UUID='$UUID',nombreXML='$nombreXML',selloCFD='$selloCFD' where idRetencion=".$idRetencion);
}
function sintimbrarnombre($nombreXML,$idRetencion){
	$this->query("update bco_pendiente_timbrar set timbrado=1,nombreXML='$nombreXML' where idRetencion=".$idRetencion);
}
function cancelaRetencion($uuid){
	$sql = $this->query("update bco_pendiente_timbrar set cancelada=1 where UUID=".$uuid);
}
function listadoFacturas($fechaini,$fechafin)
{
	//se le suma undia porq la hora de la fecha no me trae la del dia por ese tiempo
	$fechafin = strtotime ( '+1 day' , strtotime ( $fechafin ) );
	$fechafin = date ( 'Y-m-j' , $fechafin );
	$sql=$this->query("select b.*,p.razon_social,c.nombre from bco_pendiente_timbrar b,mrp_proveedor p,bco_complementos c where b.fecha between '$fechaini' and '$fechafin' and p.idPrv=b.idPrv and c.clave=b.claveComplemento;");
	return $sql;
}
function impuestosRetenidos($idRe){
	$sql = $this->query("select * from bco_impuestos_retencion where idRetencion=".$idRe);
	return $sql;
}
function borrarIncorrecto($idr){
	$sql = $this->query("delete from  bco_pendiente_timbrar where idRetencion=".$idr);
	$sql = $this->query("delete from  bco_impuestos_retencion where idRetencion=".$idr);
}
/* 		CXC Y CXP 		*/
 function listaCargos($idPrvCli,$cobrar_pagar,$moneda){//$cobrar_pagar=1 CXP / $cobrar_pagar=0 CXC
   if($idPrvCli=='undefined'){$idPrvCli=0;}
   if($moneda=='undefined'){$moneda=0;}
   $myQuery = "SELECT p.id, p.tipo_cambio,p.origen, (SELECT codigo FROM cont_coin WHERE coin_id = p.id_moneda) AS moneda, p.tipo_cambio, p.fecha_pago, @c := p.cargo*p.tipo_cambio, p.cargo, p.concepto, @p := IFNULL((SELECT SUM(pr.abono) FROM app_pagos_relacion pr WHERE pr.id_tipo = 0 AND pr.id_documento = p.id  AND (SELECT id_moneda FROM app_pagos WHERE id = pr.id_pago) = 1),0) AS pagos,
					@p2 := IFNULL((SELECT SUM(pr.abono*(SELECT tipo_cambio FROM app_pagos WHERE id = pr.id_pago)) FROM app_pagos_relacion pr WHERE pr.id_tipo = 0 AND pr.id_documento = p.id AND (SELECT id_moneda FROM app_pagos WHERE id = pr.id_pago) != 1),0) AS pagos2,
                    (@c-(@p+@p2)) AS saldo
                    FROM app_pagos p
                    WHERE p.id_prov_cli = $idPrvCli
                    AND p.cobrar_pagar = $cobrar_pagar
                    AND p.id_moneda= $moneda
                    AND p.cargo > 0 ";
                    
     return $this->query($myQuery);
}
function listaFacturas($idPrvCli,$cobrar_pagar,$moneda){
   if($idPrvCli=='undefined'){$idPrvCli=0;}
   if($moneda=='undefined'){$moneda=0;}
	if(intval($cobrar_pagar))
        {
            // $myQuery = "SELECT rq.tipo_cambio AS rq_tipo_cambio, r.id_oc, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, r.desc_concepto, r.id, r.fecha_factura,r.no_factura,r.imp_factura,r.xmlfile, (SELECT diascredito FROM mrp_proveedor WHERE idPrv = c.id_proveedor) AS diascredito,
                    // @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
                    // @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
                    // (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    // FROM app_recepcion r INNER JOIN app_ocompra c ON c.id = r.id_oc 
                    // INNER JOIN app_requisiciones rq ON rq.id = id_requisicion
                    // WHERE c.id_proveedor = $idPrvCli AND xmlfile != '' and rq.id_moneda = $moneda
                    // ORDER BY id_oc;";
                    
          
          $myQuery = "SELECT rq.tipo_cambio AS rq_tipo_cambio, r.id_oc, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, r.desc_concepto, r.id, r.fecha_factura,r.no_factura,r.imp_factura,(r.imp_factura*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, r.xmlfile, (SELECT diascredito FROM mrp_proveedor WHERE idPrv = c.id_proveedor) AS diascredito,
                    @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
                    @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
                    (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_recepcion r INNER JOIN app_ocompra c ON c.id = r.id_oc 
                    INNER JOIN app_requisiciones rq ON rq.id = id_requisicion
                    WHERE c.id_proveedor = $idPrvCli AND xmlfile != '' and rq.id_moneda = $moneda
                    ORDER BY id_oc;";
        }
        else
        {
        		// $myQuery = "(SELECT rf.origen,rq.tipo_cambio AS rq_tipo_cambio, e.id_oventa, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, e.desc_concepto, rf.folio, e.id, rf.id AS idres, rf.fecha AS fecha_factura,e.total AS imp_factura, SUM(e.total*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, rf.xmlfile, (SELECT dias_credito FROM comun_cliente WHERE id = v.id_cliente) AS diascredito,
                    // @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    // @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    // (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    // FROM app_respuestaFacturacion rf
                    // INNER JOIN  app_envios e ON e.id = rf.idSale AND e.forma_pago = 6
                    // INNER JOIN app_oventa v ON v.id = e.id_oventa
                    // INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion and rq.id_moneda = $moneda
                    // WHERE v.id_cliente = $idPrvCli AND rf.tipoComp = 'F' AND rf.xmlfile != '' AND rf.origen = 1 AND rf.borrado = 0)
// 
				// UNION ALL
//                     
                    // (SELECT rf.origen,rq.tipo_cambio AS rq_tipo_cambio, '' AS id_oventa, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, e.desc_concepto, rf.folio, e.id, rf.id AS idres, rf.fecha AS fecha_factura,SUM(e.total) AS imp_factura, SUM(pf.monto*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, rf.xmlfile, (SELECT dias_credito FROM comun_cliente WHERE id = v.id_cliente) AS diascredito,
                        // @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                        // @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                         // (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    // FROM app_pendienteFactura pf
                    // LEFT JOIN app_respuestaFacturacion rf ON pf.id_respFact = rf.id
                    // INNER JOIN  app_envios e ON e.id = pf.id_sale AND e.forma_pago = 6
                    // INNER JOIN app_oventa v ON v.id = e.id_oventa
                    // INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion and rq.id_moneda = $moneda
                    // WHERE pf.id_respFact != 0
                    // AND pf.id_cliente = $idPrvCli
                    // AND rf.idSale = 0
                    // AND rf.borrado = 0
                    // AND rf.xmlfile != ''
                    // AND rf.idFact != 0
                    // AND rf.tipoComp = 'F'
                    // AND rf.origen = 1
                    // GROUP BY pf.id_respFact)
                    // ";
//                     
			// if($moneda==1){
                   // $myQuery.= "UNION ALL
                    // (SELECT rf.origen, 1 AS tipo_cambio, rf.idSale AS id_oventa, 'MXN' AS Moneda, CONCAT('Venta a credito POS ',rf.idSale) AS desc_concepto, rf.folio, v.idVenta AS id, rf.id AS idres, rf.fecha AS fecha_factura, vp.monto AS imp_factura, vp.monto AS importe_pesos,rf.xmlfile,(SELECT dias_credito FROM comun_cliente WHERE id = v.idCliente) AS diascredito,
                    // @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    // @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    // (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    // FROM app_respuestaFacturacion rf
                    // INNER JOIN app_pos_venta v ON v.idVenta = rf.idSale
                    // INNER JOIN app_pos_venta_pagos vp ON vp.idVenta = v.idVenta AND vp.idFormapago = 6
// 
                    // WHERE rf.origen = 2
                    // AND v.idCliente = $idPrvCli AND rf.tipoComp = 'F')
// 
                    // ";
			// }
        	
        		 $myQuery = "(SELECT rf.id AS id,rf.origen,rq.tipo_cambio AS rq_tipo_cambio, e.id_oventa, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, e.desc_concepto, rf.folio,  rf.id AS idres, rf.fecha AS fecha_factura,e.total AS imp_factura, (e.total*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, rf.xmlfile, (SELECT dias_credito FROM comun_cliente WHERE id = v.id_cliente) AS diascredito,
                    @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_respuestaFacturacion rf
                    INNER JOIN  app_envios e ON e.id = rf.idSale AND e.forma_pago = 6
                    INNER JOIN app_oventa v ON v.id = e.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion and rq.id_moneda = $moneda
                    WHERE v.id_cliente = $idPrvCli AND rf.tipoComp = 'F' AND rf.xmlfile != '' AND rf.origen = 1 AND rf.borrado = 0)

				UNION ALL
                    
                    (SELECT rf.id AS id,rf.origen,rq.tipo_cambio AS rq_tipo_cambio, '' AS id_oventa, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, e.desc_concepto, rf.folio,  rf.id AS idres, rf.fecha AS fecha_factura,SUM(e.total) AS imp_factura, SUM(pf.monto*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, rf.xmlfile, (SELECT dias_credito FROM comun_cliente WHERE id = v.id_cliente) AS diascredito,
                        @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                        @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                         (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_pendienteFactura pf
                    LEFT JOIN app_respuestaFacturacion rf ON pf.id_respFact = rf.id
                    INNER JOIN  app_envios e ON e.id = pf.id_sale AND e.forma_pago = 6
                    INNER JOIN app_oventa v ON v.id = e.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion and rq.id_moneda = $moneda
                    WHERE pf.id_respFact != 0
                    AND pf.id_cliente = $idPrvCli
                    AND rf.idSale = 0
                    AND rf.borrado = 0
                    AND rf.xmlfile != ''
                    AND rf.idFact != 0
                    AND rf.tipoComp = 'F'
                    AND rf.origen = 1
                    GROUP BY pf.id_respFact)
                    ";
                    
			if($moneda==1){
                   $myQuery.= "UNION ALL
                    (SELECT rf.id AS id,rf.origen, 1 AS tipo_cambio, rf.idSale AS id_oventa, 'MXN' AS Moneda, CONCAT('Venta a credito POS ',rf.idSale) AS desc_concepto, rf.folio,  rf.id AS idres, rf.fecha AS fecha_factura, vp.monto AS imp_factura, vp.monto AS importe_pesos,rf.xmlfile,(SELECT dias_credito FROM comun_cliente WHERE id = v.idCliente) AS diascredito,
                    @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_respuestaFacturacion rf
                    INNER JOIN app_pos_venta v ON v.idVenta = rf.idSale
                    INNER JOIN app_pos_venta_pagos vp ON vp.idVenta = v.idVenta AND vp.idFormapago = 6

                    WHERE rf.origen = 2
                    AND v.idCliente = $idPrvCli AND rf.tipoComp = 'F')

                    ";
			}
                   $myQuery.=" ORDER BY id_oventa;";
            
        }
    return $this->query($myQuery);
     
}
//los pagos se registran como cargos y son validos hasta q estan en pagos relacion
function almacenaPago($cxccxp,$pagador,$importe,$fecha,$concepto,$formapago,$moneda,$tipocambio,$idDoc,$tipodoc,$refbanco){//origen es 3 que sera para bancos
	if(floatval($tipocambio)<1){ $tipocambio = 1; }
	if(!$refbanco){$refbanco="";}
	$sql = "INSERT INTO app_pagos
			 	(cobrar_pagar, id_prov_cli, cargo, abono, fecha_pago, concepto, id_forma_pago, id_moneda, tipo_cambio, origen,ref_bancos)
			VALUES
				($cxccxp, $pagador, 0, $importe, '$fecha', '$concepto-$tipodoc ID.$idDoc', $formapago, $moneda, $tipocambio, 3,'$refbanco');
			";
	return $this->insert_id($sql);
	
}
/*$id_tipo es el abono aq tipo de esta asiendo
 * 0 a un cargo
 * 1 a una factura
 */

function almacenaPagoRelacion($idPago,$iddeuda,$importe,$id_tipo){
	$this->query("INSERT INTO app_pagos_relacion 
	( id_pago, id_tipo, id_documento, cargo, abono)
VALUES
	($idPago,$id_tipo , $iddeuda, 0, $importe);
	");
	
}

function pagosConDocumento($idDoc,$idPrvCli,$cobrar_pagar){
	$sql = $this->query("
		select
		 	r.id,r.id_pago,r.abono abonorelacion,p.concepto,p.abono abonopago
		from 
			app_pagos p,app_pagos_relacion r 
		where 
			p.concepto like '%ID.$idDoc%' 
			and p.id_prov_cli=$idPrvCli 
			and r.id_pago=p.id 
			and p.cobrar_pagar=$cobrar_pagar");
	return $sql;
}
/* esta funcion elimina dentro del documento registro por registro */
function eliminaPagoApp($pagos,$pagosrelacion){
	$sql="delete from app_pagos where id=$pagos;
		  delete from app_pagos_relacion where id=$pagosrelacion;";
	if($this->dataTransact($sql)){
		return 1;
	}else{
		return 0;
	}
}
/* esta eliminara todos los registro de app_pagos y pagos_relacion 
 * cuando se elimine el documento */
function eliminaDocumentoCXCCXCP($idDoc){
	$sql ="
		delete from app_pagos_relacion where id_pago in(
		select id FROM app_pagos where concepto like '%ID.$idDoc%');
		delete FROM app_pagos where concepto like '%ID.$idDoc%';
	";
	if($this->dataTransact($sql)){
		return 1;
	}else{
		return 0;
	}
}
/* se duplico una para eliminar las de prove,clie en 
 * documento edicion ya que si se edita
 * debe eliminar las anteriores
 * se quiso usar la de eliminaDocumentoCXCCXCP pero se necesitaba solo eliminar del antiguo
 * beneficiario
 * */
function eliminaRegistrocxccxp($idDoc,$idbe){
	$sql =$this->query( "delete from app_pagos_relacion where id_pago in(
		select id FROM app_pagos where concepto like '%ID.$idDoc%' and id_prov_cli=".$idbe.");");
	$sql =$this->query( "delete FROM app_pagos where concepto like '%ID.$idDoc%' and id_prov_cli=".$idbe.";");
	
}
/* se agrego esta funcion
 * por el caso de las cxc
 * que al eliminar el depositos sus cxc solo debe eliminar el pago aplicado
 * no el registro y para no mover todo lo estable mejor se duplico 
 * la funcion y cambiar esa parte
 */
function eliminaDocumentoDep($id){
		$sql = "delete from bco_documentos where id=".$id;
		if($this->query($sql)){
			/* elimina pagos y aplicaciones creadas con el documento */
			$this->query("delete from app_pagos_relacion where id_pago in(
		select id FROM app_pagos where concepto like '%ID.$id%');");	
			
			/*elimina documentos creados a partir de traspasos */
			$sql = $this->query("delete from bco_documentos where idtraspaso=".$id);
			if ($vcarga = opendir($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id)){
					while($file = readdir($vcarga)){
						if ($file != "." && $file != ".."){
							if (!is_dir($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id."/".$file)){
									unlink($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id."/".$file);
								}
							}
						}rmdir($this->path('../cont/')."xmls/facturas/documentosbancarios/".$id);
				}
			return 1;
		}else{
			return 0;
		}
	}
/* se valida el beneficiario
 * ya que si se cambia en la edicion del documento
 * los pagos cxc cxp hechos al anterior beneficiario deben eliminarse
 */
 function benePrevio($id){
 	$sql = $this->query("select beneficiario,idbeneficiario from bco_documentos where id=".$id);
 	if($sql->num_rows>0){
 		return $sql->fetch_object();
 	}else{
 		return 0;
 	}
 }
 
 function cargoxDevolucion($id,$fecha){
 	
 	$this->query("
 			insert into app_pagos_relacion  (id_pago,id_tipo,id_documento,cargo,abono)
				(select ".
					
					$this->insert_id("INSERT INTO app_pagos (cobrar_pagar, id_prov_cli, cargo, abono, fecha_pago, concepto, id_forma_pago, id_moneda, tipo_cambio, origen, ref_bancos)
					(select cobrar_pagar, id_prov_cli, cargo,concat('-',sum(abono)),'$fecha', concat('Cargo por devolucion ',concepto), id_forma_pago, id_moneda, tipo_cambio, origen, ref_bancos 
					from app_pagos where concepto like '%ID.$id%' and (concepto not like '%Cargo por devolucion%' and concepto not like '%Reactivacion pago%' ));")
					
					.",id_tipo,id_documento,cargo,concat('-',abono) 
				from 
					app_pagos_relacion 
				where 
					id_pago in (
						select 
							id 
						from 
							app_pagos 
						where 	
							concepto like '%ID.$id%' and (concepto not like '%Cargo por devolucion%' and concepto not like '%Reactivacion pago%' ))); ");
 }
 function pagoxReactivacion($id){
 	$this->query("
 		insert into app_pagos_relacion (id_pago,id_tipo,id_documento,cargo,abono)
			(select ".
				 	$this->insert_id("INSERT INTO app_pagos (cobrar_pagar, id_prov_cli, cargo, abono, fecha_pago, concepto, id_forma_pago, id_moneda, tipo_cambio, origen, ref_bancos)
					(select cobrar_pagar, id_prov_cli,cargo,sum(abono),'".date('Y-m-d\TH:i:s')."', concat('Reactivacion pago ',concepto), id_forma_pago, id_moneda, tipo_cambio, origen, ref_bancos 
					from app_pagos where concepto like '%ID.$id%' and (concepto not like '%Cargo por devolucion%' and concepto not like '%Reactivacion pago%' ) );")
				 	
			.",id_tipo,id_documento,cargo,abono 
 			from 
 				app_pagos_relacion 
 			where 
 				id_pago in (
					select 
						id 
					from 
						app_pagos 
					where 
						concepto like '%ID.$id%' and (concepto not like '%Cargo por devolucion%' and concepto not like '%Reactivacion pago%' ))); ");
 
 }
                     
 
/* 			FIN CXC Y CXP			*/
/* 			ANTICIPOS DE GASTOS			*/
function usuarios(){
	$sql = $this->query("select u.idempleado,u.usuario from accelog_usuarios u, accelog_usuarios_per p where p.idempleado = u.idempleado and p.idperfil=32 order by usuario asc");
	return $sql;
}
/*			FIN ANTICIPO GASTOS			*/	


function nombreImpuesto($imp)
	{
		$return = array();
		for($i=0;$i<=count($imp)-1;$i++)
		{
			$res = $this->query("SELECT descripcion FROM cont_impuestos WHERE claveSat = '".$imp[$i]."'");
			$res = $res->fetch_assoc();
			array_push($return, $res['descripcion']);
		}
		
		return $return;
	}
function segmento(){
	$sql = $this->query("select * from cont_segmentos ");
	return $sql;
}
function sucursal(){
	$sql = $this->query("select * from mrp_sucursal ");
	return $sql;
}

function listaTemporalesBD($vars)
		{
			if($vars['fechas'] == '')
			{
				$buscar = $vars['folio_uuid'];
				if($buscar == '*')
					$buscar = '';
				
				//Si es por Folio
				if(!intval($vars['tipo_busqueda']))
					$where = "f.folio LIKE '%$buscar%'";
				
				//Si es por UUID
				if(intval($vars['tipo_busqueda']) == 1)
					$where = "f.uuid LIKE '%$buscar%'";
				
				//Si es por Razon social
				if(intval($vars['tipo_busqueda']) == 2)
				{
					$where = "f.emisor LIKE '%$buscar%' OR f.receptor LIKE '%$buscar%'";
				}
			}
			else
			{
				$fechas = explode(' / ',$vars['fechas']);
				$where = "fecha BETWEEN '$fechas[0] 00:00:00' AND '$fechas[1] 23:59:59'";
			}

			return $this->query("SELECT f.* FROM cont_facturas f WHERE $where AND f.temporal = 1 AND f.cancelada = 0;");
		}

	function quitaTemporal($xml)
	{
		$this->query("UPDATE cont_facturas SET temporal = 0 WHERE xml LIKE '$xml'; ");
	}

	function facturaRename($xml)
	{
		if(strpos($xml, "_") === false)
			$uuid = str_replace('.xml', '', $xml);
		else
		{
			$uuid = explode('_', $xml);
			$uuid = str_replace('.xml', '', $uuid[2]);
		}

		$this->query("UPDATE cont_facturas SET xml = '$xml' WHERE uuid = '$uuid';");
		
	}		


}
?>