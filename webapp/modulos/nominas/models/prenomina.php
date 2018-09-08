<?php
class PrenominaModel extends NominalibreModel{
	
	/* P R E N O M I N A      I N T E R F A Z */

	
	function incidenciasDeEmpleado($idEmpleado,$idnomp){
		$sql = $this->query("
			select 
				i.idnomp,t.derechosueldo,t.idconsiderado,t.idtipoincidencia,t.idclasificadorincidencia,i.sobrerecibo 
			from 
				nomi_claveincidencias i
			inner join 
				nomi_nominasperiodo n on n.idnomp = i.idnomp
			inner join 
				nomi_tipoincidencias t on t.idtipoincidencia = i.idtipoincidencia
			where i.idEmpleado = $idEmpleado and i.idnomp = $idnomp
		");
		if($sql->num_rows>0){
			return $sql;
		}else{
			return 0;
		}
	}
	/* se saca con los dias de incapacidad inc=1 porque estos son restado despues de sacar proporcion si fuera el caso
	 * tambien por esto en el caso de pago de alimentos debe restar los dias de checador q esten como inc ya que esos
	 * no debera pagarles el monto diario de alimentos "diasIncEnChecador" */
	function checkDeEmpleado($idEmpleado,$idnomp){
		$sql = $this->query("select * from nomi_registro_entradas where idEmpleado=$idEmpleado and idnomp=$idnomp");
		if($sql->num_rows>0){
			return $sql;
		}else{
			return 0;
		}
	}
	function tablaISR($periodo,$sueldobase){
		if($periodo == 2){//periodo semanal debera traer el isr y subsidio semanal
			$sql = "select limite_inferior,cuotafija,porcentaje  from nomi_tvig_isr_semanal where if (".$sueldobase."<=limite_inferior  , ".$sueldobase." between  limite_inferior and  limite_superior , limite_inferior<=".$sueldobase."  ) 
				order by idisrsemanal desc limit 1";

		// TABLA ISR SEMANAL x 2 para el periodo catorcenal todo por dos excepto el porcentaje
		}elseif($periodo == 3){//periodo catorcenal traer isr y subsidio catorcenal se multiplica el semanal x2 para sacar los 14
			$sql = "select limite_inferior,cuotafija,porcentaje  from nomi_tvig_isr_catorcenal where  if (".$sueldobase."<=limite_inferior  , ".$sueldobase." between  limite_inferior and  limite_superior , limite_inferior<=".$sueldobase."  ) 
				order by idisrcatorcenal desc limit 1";
		}
		$valor = $this->query($sql);
		return $valor->fetch_object();
	}
	function tablasISR($periodo,$basegravada){
		$sql = "select limite_inferior,cuotafija,porcentaje  from nomi_tvig_isr_$periodo where   if (".$basegravada."<=limite_inferior  , ".$basegravada." between  limite_inferior and  limite_superior , limite_inferior<=".$basegravada."  ) 
				order by idisr$periodo desc limit 1";
		$valor = $this->query($sql);
		return $valor->fetch_object();	
		
	}
	function tablaSubsidio($periodo,$sueldobase){
		if($periodo == 2){//periodo semanal debera traer el isr y subsidio semanal
			$sql = "select * from nomi_tvig_subemp_semanal where if (".$sueldobase." <=limite_inferior  , ".$sueldobase."  between  limite_inferior and  limite_superior , limite_inferior<=".$sueldobase." )
					order by idempsemanal desc limit 1 ";
		}elseif($periodo == 3){
			$sql = "select limite_inferior, limite_superior,subsalempleo  from nomi_tvig_subemp_catorcenal where if (".$sueldobase." <=limite_inferior  , ".$sueldobase."  between  limite_inferior and  limite_superior , limite_inferior<=".$sueldobase."   ) 
					order by idempcatorcenal desc limit 1";
		}
		$valor = $this->query($sql);
		return $valor->fetch_object();
	}
	function tablasSubsidio($periodo,$sueldobase){
		$sql = "select * from nomi_tvig_subemp_$periodo where if (".$sueldobase." <=limite_inferior  , ".$sueldobase."  between  limite_inferior and  limite_superior , limite_inferior<=".$sueldobase."   ) 
					order by idemp$periodo desc limit 1";
		
		$valor = $this->query($sql);
		return $valor->fetch_object();
	}
	function SMvigente(){
		$sql = "select * from nomi_salariosminimos where year(vigencia) = year(CURDATE()) order by vigencia desc limit 1;";
		$valor = $this->query($sql);
		return $valor->fetch_object();
	}
	function UMAvigente(){
		$sql = "select * from nomi_uma where year(vigencia) = year(CURDATE()) and periodo='diario'";
		$valor = $this->query($sql);
		return $valor->fetch_object();
	}
	function incapacidadesSobrerecibo($fechainiciop,$fechafinp,$idEmpleado){//se traen las fechas de inicio y fin del periodo para ver si existen incapacidades
		$sql = $this->query("select *,date_add(fechainicio,interval diasautorizados day) as fechafin,sum(diasautorizados)diasautorizados from nomi_incapacidades_sobrerecibo where idEmpleado = $idEmpleado and ( fechainicio between '$fechainiciop' and '$fechafinp' || date_add(fechainicio,interval diasautorizados day)  between '$fechainiciop' and '$fechafinp') group by idEmpleado;" );
		if($sql ->num_rows>0 ){
			return $sql->fetch_object();
		}else{
			return 0;
		}
	}
	function diasIncEnChecador($idEmpleado,$idnomp){
		$sql = $this->query("select sum(inc) diasinc from nomi_registro_entradas where idEmpleado=$idEmpleado and idnomp=$idnomp and inc=1");
		if($sql->num_rows>0){
			return 0;
		}else{
			return $sql->fetch_object();
		}
	}
	/* Si es porcentaje sobre => sdi * %porcentaje
	 * Si es vsm => salario minimo * veces
	 * Cuota fija = >monto
	 * Monto permanente => monto fijo por periodo sin operaciones
	 * SOLO SE PUEDE TENER UN CREDITO INFONAVIT*/
	function infonavitEmpleado($idEmpleado,$fechafin){
		$sql  = $this->query("select * from nomi_infonavit_sobrerecibo where idEmpleado=$idEmpleado and fechaaplicacion <='$fechafin' and estatus=1");
 		if($sql->num_rows>0){
 			return $sql->fetch_object();
 			
 		}else{
 			return 0;
 		}
	}
	function seguroViviendaInfonavit(){
		$sql = $this->query("select cuota from nomi_tinfonavit_segvivienda where year(vigencia) = year(CURDATE())");
		if($sql->num_rows>0){
 			$cuota = $sql->fetch_object();
			return $cuota->cuota;
 			
 		}else{
 			return 0;
 		}
	}
	function listaIncidencias(){//clasificaicon  de tipo 2 q es dias y q no sea considerado como vacaciones porq las vacas son pagadas
		$sql  = $this->query("select * from nomi_tipoincidencias where idclasificadorincidencia=2 and idconsiderado!=3;");
 		if($sql->num_rows>0){
 			return $sql;
 			
 		}else{
 			return 0;
 		}
	}
	
	function empleadosEn1periodo($idtipop){
		$sql = $this->query("select e.idEmpleado,e.codigo,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.idFormapago,e.email,e.nss,e.idEstadoCivil,e.idsexo,
			e.idestado,e.idmunicipio,e.rfc,e.curp,e.idestadosat,e.cp,e.numeroCuenta,e.claveinterbancaria,e.activo,e.idtipocontrato,e.idtipop,e.idbase,e.idDep,e.idPuesto,e.idtipoempleado,e.idbasepago,e.idturno,e.idregimencontrato,e.fonacot,e.afore,e.idregistrop,
			(CASE e.activo WHEN -1 THEN e.fechaAlta WHEN 3 THEN  h.fecha END) as fechaActiva,
				(CASE s.idEmpleado WHEN  e.idEmpleado THEN
 					(select s.nuevoSalario from nomi_historico_salarios s where s.idEmpleado =  e.idEmpleado  order by s.fechaAplicacion desc limit 1) 
 				ELSE 
 					e.salario END ) salario,
				(CASE s.idEmpleado WHEN  e.idEmpleado THEN
						(select s.nuevoSDI from nomi_historico_salarios s where s.idEmpleado =  e.idEmpleado  order by s.fechaAplicacion desc limit 1)
 				ELSE e.sbcfija END ) sbcfija
			from 
			nomi_empleados e
			left join nomi_historial_empleado h on h.idEmpleado = e.idEmpleado
			left join nomi_historico_salarios s on  s.idEmpleado =  e.idEmpleado 
			
			where  
			 e.idtipop=$idtipop group by e.idEmpleado order by e.idEmpleado asc;");
		return $sql;
	}
	function almacenaCalculo($origen,$idEmpleado,$idnomp,$idconcepto,$importe,$gravado,$exento,$diaspagados,$diasladorados,$valordias,$aplicarecibo,$fecha,$salario,$sdi,$valorneto,$diasvacaciones,$diasfestivos,$diaspagopropor){
		if(!$fecha){ $fecha = "";}
		if(!$diasvacaciones){$diasvacaciones=0;}
		if(!$diasfestivos){$diasfestivos=0;}
		if(!$salario){$salario=0;}
		if(!$sdi){$sdi=0;}
		if(!$valorneto){$valorneto=0;}
		
			//idnomina corresponde d ela nomina libre
		$sql = "INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,salario,sdi,valorneto,diasvac,diasfestivo,diaslabproporcion)
			VALUES ($origen, $idEmpleado, $idnomp, $idconcepto, $importe,$gravado,$exento,$diaspagados,$diasladorados,$valordias,$aplicarecibo,'$fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR),$salario,$sdi,$valorneto,$diasvacaciones,$diasfestivos,$diaspagopropor);";
		
		$id = $this->insert_id($sql);
		if($id){
			return $id;
		}else{
			return 0;
		}
	}
	/*se actualiza el valor neto que es el importe antes de retener o entregar ispt o subsidio*/
	function updateValorneto($valorneto,$idconfpre,$idnomp,$idempleado){
		if($valorneto){
			$sql = $this->query("update nomi_calculo_prenomina set valorneto =$valorneto where idconfpre=$idconfpre and idnomp=$idnomp and idEmpleado=$idempleado;");
		}
	}
	function updateValorDiasVac($valorneto,$idconfpre,$idnomp,$idempleado){
		if($valorneto){
			$sql = $this->query("update nomi_calculo_prenomina set diasvac =$valorneto where idconfpre=$idconfpre and idnomp=$idnomp and idEmpleado=$idempleado;");
		}
	}
	function updateValorDiasFestivo($valorneto,$idconfpre,$idnomp,$idempleado){
		if($valorneto){
			$sql = $this->query("update nomi_calculo_prenomina set diasfestivo =$valorneto where idconfpre=$idconfpre and idnomp=$idnomp and idEmpleado=$idempleado;");
		}
	}
	function eliminaCalculoprevio($idnomp,$origen){//origen 0 para solo eliminar los de prenomina
		$sql = $this->query("delete from nomi_calculo_prenomina where  idnomp=$idnomp and origen=$origen");	
		
	}
	function VerificaConceptoprevio($idnomp,$idconfpre,$idEmpleado){
		$sql = $this->query("select * from nomi_calculo_prenomina where  idnomp=$idnomp and idconfpre=$idconfpre and idEmpleado=$idEmpleado");	
		if($sql->num_rows>0){
			return 0;
		}else{
			return 1;
		}
	}
	function empleadosDperiodoSincalcular($fecha,$idnomp){
		$sql = $this->query("select 
		e.idEmpleado,e.codigo,e.alimento,
				e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.idzona,
				e.idFormapago,e.email,e.nss,e.idEstadoCivil,e.idsexo,
				e.idestado,e.idmunicipio,e.rfc,e.curp,e.idestadosat,
				e.cp,e.numeroCuenta,e.claveinterbancaria,e.activo,
				e.idtipocontrato,e.idtipop,e.idbase,e.idDep,e.idPuesto,
				e.idtipoempleado,e.idbasepago,e.idturno,e.idregimencontrato,
				e.fonacot,e.afore,e.idregistrop	,
			(CASE e.activo WHEN -1 THEN e.fechaAlta WHEN 3 THEN  h.fecha END) as fechaActiva,
			(CASE s.idEmpleado WHEN  e.idEmpleado THEN
				(select s.nuevoSalario from nomi_historico_salarios s where s.idEmpleado =  e.idEmpleado and s.fechaAplicacion<='$fecha' order by s.fechaAplicacion desc limit 1) 
			ELSE 
				e.salario END ) salario,
			(CASE s.idEmpleado WHEN  e.idEmpleado THEN
					(select s.nuevoSDI from nomi_historico_salarios s where s.idEmpleado =  e.idEmpleado and s.fechaAplicacion<='$fecha' order by s.fechaAplicacion desc limit 1)
			ELSE e.sbcfija END ) sbcfija
			
			from 
			nomi_empleados e
			inner join nomi_configuracion c on e.idtipop = c.idtipop
			left join nomi_historial_empleado h on h.idEmpleado = e.idEmpleado
			left join nomi_historico_salarios s on  s.idEmpleado =  e.idEmpleado and s.fechaAplicacion<='$fecha'
			where  
			case e.activo when -1 then e.fechaAlta<='$fecha' when 3 then  h.fecha<='$fecha' end
			and e.idEmpleado not in (select pr.idEmpleado from nomi_calculo_prenomina pr where pr.idnomp=$idnomp group by pr.idEmpleado)
			group by e.idEmpleado order by e.idEmpleado asc;
			");
		if($sql->num_rows>0){
			return $sql;
		}else{
			return 0;
		}
	}
	function empleadosEnNomina($idnomp){
		$sql = $this->query("select pr.*,concat(e.nombreEmpleado,' ' ,e.apellidoPaterno, ' ',e.apellidoMaterno)nombre,e.codigo from nomi_calculo_prenomina  pr, nomi_empleados e where pr.idnomp=$idnomp and e.idEmpleado=pr.idEmpleado");
		return $sql;
	}
	//aguinaldo//
	function acumuladoAguinaldo($monto,$exento,$gravado,$ejercicio){
		$sql = "INSERT INTO nomi_acumulado_aguinaldo ( monto, exento, gravado,ejercicio)
				VALUES ($monto, $exento, $gravado, $ejercicio);";	
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}	
	}
	function isrAnual($sueldobase){
		$sql = "select limite_inferior,cuotafija,porcentaje  from nomi_tvig_isr_anual where ".$sueldobase." between  limite_inferior and  limite_superior";
		$valor = $this->query($sql);
		return $valor->fetch_object();
	}
	function incidenciaEmpldelAno($idEmpleado,$idtipoincidencia){
		$sql = $this->query("select count(*) numreg from nomi_claveincidencias where sobrerecibo=0 and idEmpleado=$idEmpleado and year(fechaseleccion) = year(CURDATE()) and idtipoincidencia in($idtipoincidencia)");
		return $sql->fetch_object();
		
	}
	
	function empleadosParaFiniquito(){
		/*antes estaba asi para si es en la nomina activa
		 * 	/// finiquito empleados del periodo activo unicamente esto para al momento de acumular hacerlo sobre la nomian activa
		 * 
		 * select e.*,
			(case e.activo when -1 then e.fechaAlta when 3 then  h.fecha end) as fechaActiva
			from 
			nomi_empleados e
			left join nomi_historial_empleado h on h.idEmpleado = e.idEmpleado
			inner join nomi_configuracion c on c.idtipop = e.idtipop 
			where e.activo!=2
			    order by e.nombreEmpleado asc */
		$sql = $this->query("select e.idEmpleado,e.codigo,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.salario,e.idFormapago,e.email,e.nss,e.idEstadoCivil,e.idsexo,
		e.idestado,e.idmunicipio,e.rfc,e.curp,e.idestadosat,e.cp,e.numeroCuenta,e.claveinterbancaria,e.activo,e.idtipocontrato,e.idtipop,e.idbase,e.sbcfija,e.idDep,e.idPuesto,e.idtipoempleado,e.idbasepago,e.idturno,e.idregimencontrato,e.fonacot,e.afore,e.idregistrop,
			(case  e.activo when -1 then e.fechaAlta when 3 then (select h.fecha from nomi_historial_empleado h where  h.idEmpleado = e.idEmpleado order by h.fecha desc limit 1) end)as fechaActiva

			from 
			nomi_empleados e
			where e.activo!=2 and salario>0
			    order by e.nombreEmpleado asc");
		if($sql->num_rows>0){
			return $sql;
		}else{
			return 0;
		}
	}
	function datosEmpFiniquito($idEmpleado){
		$sql = $this->query("select e.idEmpleado,e.codigo,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.salario,e.salario,e.idFormapago,e.email,e.nss,e.idEstadoCivil,e.idsexo,
		e.idestado,e.idmunicipio,e.rfc,e.curp,e.idestadosat,e.cp,e.numeroCuenta,e.claveinterbancaria,e.activo,e.idtipocontrato,e.idtipop,e.idbase,e.sbcfija,e.idDep,e.idPuesto,e.idtipoempleado,e.idbasepago,e.idturno,e.idregimencontrato,e.fonacot,e.afore,e.idregistrop
			,c.descripcion,
			(case  e.activo when -1 then e.fechaAlta when 3 then (select h.fecha from nomi_historial_empleado h where  h.idEmpleado = e.idEmpleado order by h.fecha desc limit 1) end)as fechaActiva,
				(CASE s.idEmpleado WHEN  e.idEmpleado THEN
 					(select s.nuevoSalario from nomi_historico_salarios s where s.idEmpleado =  e.idEmpleado  order by s.fechaAplicacion desc limit 1) 
 				ELSE 
 					e.salario END ) salario,
				(CASE s.idEmpleado WHEN  e.idEmpleado THEN
						(select s.nuevoSDI from nomi_historico_salarios s where s.idEmpleado =  e.idEmpleado  order by s.fechaAplicacion desc limit 1)
 				ELSE e.sbcfija END ) sbcfija
			
			from 
			nomi_empleados e
			inner join 
			nomi_tipocontrato c on c.idtipocontrato=e.idtipocontrato
			left join nomi_historico_salarios s on  s.idEmpleado =  e.idEmpleado 
				
			where e.idEmpleado = $idEmpleado GROUP BY e.idEmpleado;");
		
			return $sql->fetch_object();
		
		
	}
	function causasFiniquito(){
		$sql = $this->query("select * from nomi_causa_finiquito");
		return $sql;
	}
	function conceptosFiniquito(){
		$sql = $this->query("select * from nomi_conceptos_finiquito");
		return $sql;
	}
	function relacionConcepCausasFiniquito($idcausaf){
		$sql = $this->query("select * from nomi_rel_concep_causa_finiquito rc
			inner join nomi_conceptos_finiquito cf on cf.idconf = rc.idconf
			where idcausaf= $idcausaf");
		return $sql;
	}
	function VacacionesDelAno($idEmpleado,$periodoinicio,$periodofin,$fechainiciavac){
		$sql = $this->query("call calculavacaciones('".$periodoinicio."','".$periodofin."','".$fechainiciavac."',".$idEmpleado.")");
		$query = $sql->fetch_object();	
		return $query;
	}
	
	//fin finiquito
	function conceptosPorTipo($tipo){
		$sql = $this->query("
			SELECT * FROM nomi_conceptos WHERE idtipo=$tipo AND activo=1 AND concepto!=0");
		if ($sql->num_rows>0){
			return $sql;
		}
	}
	function validaExtraordinario($ejercicio){
		$sql =$this->query("select n.* from nomi_nominasperiodo n,nomi_tiposdeperiodos p where p.idtipop = n.idtipop and p.extraordinario=1 and n.ejercicio=$ejercicio");
		if($sql->num_rows>0){
			$p = $sql->fetch_object();
			return $p->idnomp;
		}else{
			return 0;
		}
	}
	function acumulaExtraordinarioAguinaldo($periodo,$acumuladoaguinaldo,$conceptoacu,$isracu){
		$sql =$this->query("select n.* from nomi_nominasperiodo n,nomi_tiposdeperiodos p where p.idtipop = n.idtipop and p.extraordinario=1 and n.ejercicio=year(CURDATE())");
		if($sql->num_rows>0){
			
				$ex = $sql->fetch_object();
				$sql2 = $this->query( "select * from nomi_calculo_prenomina where origen=1 and idnomp=".$ex->idnomp);
				if($sql2->num_rows>0){
					$msj = 3;
				}else{
					foreach($acumuladoaguinaldo as $val){
								
						$sql3.= "INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento, diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,autorizado,salario,sdi)
							VALUES (1, ".$val['id'].",".$ex->idnomp.", ".$conceptoacu.",". $val['aguinaldo'].",".$val['partegravada'].",".$val['parteexenta'].", ".$val['diaspropocionaguinaldo'].",".$val['totaldias'].",0,0,'',DATE_SUB(NOW(), INTERVAL 5 HOUR),1,".$val['salario'].",".$val['sdi'].");";
						$sql3.= " INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,autorizado,salario,sdi)
							VALUES (1, ".$val['id'].",".$ex->idnomp.", ".$isracu.",". $val['isr'].",0,0,".$val['diaspropocionaguinaldo'].",".$val['totaldias'].",0,0,'',DATE_SUB(NOW(), INTERVAL 5 HOUR),1,".$val['salario'].",".$val['sdi'].");";
							
					}
					
					$msj = $this->insertPeriodo($sql3);
				}
			
			
			 
		}else{
			$msj =  4;
		}
		return $msj;
	}
	function buscaDescuentoAguinaldoFiniquito($idEmpleado,$ejercicio){
		$sql = $this->query("select c.diaspagados from nomi_calculo_prenomina c,nomi_nominasperiodo p,nomi_tiposdeperiodos t  where c.idEmpleado = $idEmpleado and c.origen=1 and p.idtipop=t.idtipop and t.extraordinario=1 and ejercicio = $ejercicio group by c.idnomp");
		if($sql->num_rows>0){
			$val = $sql->fetch_object(); 
			return $val->diaspagados;
		}else{
			return 0;
		}
	}
	function empleadoparaNominaAutorizar($idnomp,$fechafin,$fechainicio,$idtipoperiodo,$numnomina){
		$idsEmpl = "select idEmpleado from nomi_calculo_prenomina where idnomp=".$idnomp;
		//se autorizan incidencias
		$sql = "update nomi_claveincidencias set autorizado=1 where idnomp=$idnomp and fechaseleccion between '$fechainicio' and '$fechafin' and idempleado in ($idsEmpl);";
		//se autoriza acumulado de nomina en todos los procesos
		$sql.=" update nomi_calculo_prenomina set autorizado=1 where idnomp=$idnomp;";
		//se autoriza la nomina
		$sql.=" update nomi_nominasperiodo set autorizado=1 where idtipop=$idtipoperiodo and numnomina=$numnomina;";
		if($this->insertPeriodo($sql)){
			return 1;
		}else{
			return 0;
		}
		
	}
	/*xml de nominas autorizadas
	 * diapagados mayor a 0 
	 * timbrado=0 para no volver a crear el xml
	 * origen 0 para solo timbrar la nomina ordinaria*/
	function empleadosDeXML($idnomp){
		$sql = $this->query("select 
			cp.idEmpleado,
			cp.diaslaborados,
			cp.diaspagados,
			e.rfc,e.curp,e.nss,
			(case e.activo when -1 then e.fechaAlta when 3 then  h.fecha end) as fechaActiva,
			e.idtipoempleado,
			e.codigo,
			e.idtipop,
			e.idturno,
			e.idPuesto,
			e.idDep,
			e.idregimencontrato,e.idtipocontrato,
			e.idestado,	cp.importe,
			concat(e.nombreEmpleado,' ',apellidoPaterno,' ',apellidoMaterno) nombre
		from 
			nomi_calculo_prenomina cp
		inner join nomi_empleados e on e.idEmpleado = cp.idEmpleado
		left join nomi_historial_empleado h on h.idEmpleado = e.idEmpleado
		where 
			cp.idnomp=$idnomp and cp.origen=0  and cp.diaspagados>0  and cp.timbrado=0  group by cp.idEmpleado");
		return $sql;
	}
	/*Timbrado
	 * tipo
	 * 1-percepcion
	 * 2-deducciom
	 * 3 obligacion
	 * 4 otros pagos*/
	function importePortipoConcepto($idnomp,$idEmpleado,$tipo){
		
		$sql = $this->query("select sum(p.importe) importe,sum(gravado) gravado,sum(exento) exento
				from nomi_calculo_prenomina p
				inner join nomi_conceptos c on c.idconcepto =  p.idconfpre
				where idnomp=$idnomp and origen=0 and idEmpleado=$idEmpleado and c.idtipo=$tipo
				");
		if($sql->num_rows>0){
			return $sql->fetch_object();
			//return $imp->importe;
		}else{
			return 0;
		}
		
	}
	function conceptodeXML($idnomp,$idEmpleado,$tipo){
		if($tipo == 1){
			$innertabla = "nomi_percepciones";
		}elseif($tipo == 2){
			$innertabla = "nomi_deducciones";
		}elseif($tipo == 4){
			$innertabla = "nomi_otros_pagos";
		}
		$sql = $this->query("select p.importe ,p.gravado,p.exento,p.idconfpre,pr.clave clavesat,c.descripcion concepto,c.concepto claveint,valorneto
				from nomi_calculo_prenomina p
				inner join nomi_conceptos c on c.idconcepto =  p.idconfpre
				inner join $innertabla pr on pr.idAgrupador = c.idAgrupador
				where idnomp=$idnomp and origen=0 and idEmpleado=$idEmpleado and c.idtipo=$tipo and p.aplicarecibo=1
				");
		if($sql->num_rows>0){
			return $sql;
		}else{
			return false;
		}
	}
	/*Nominas sin timbrar
	 * puede tener algunos empleados de la nominas que no esten timbrados
	 * se marca como timbrado en nominasperiodos
	 * cuando se timbrar todas las nominas de empleados en calculoprenomina*/
	function nominasParaTimbrar(){
		$sql = $this->query("select idnomp,p.fechainicio, p.fechafin,p.ejercicio,p.numnomina,p.autorizado
			from nomi_nominasperiodo p 				 
			inner join nomi_configuracion c 
			on p.idtipop=c.idtipop 
			where 
			p.autorizado=1 and timbrado=0
		");
		return $sql;
	}
	function acumulaTimbreConcepto($idNomina,$idEmpleado,$idNominatimbre){
		$sql = "UPDATE nomi_calculo_prenomina SET timbrado=1, idNominatimbre=$idNominatimbre WHERE idnomp=$idNomina and idEmpleado=$idEmpleado;";
			
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	
}


?>