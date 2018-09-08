<?php
require("models/connection_sqli_manual.php"); // funciones mySQLi
class CatalogosModel extends Connection{
	
	function percepdedu($tipo){
		if($tipo == 1){
			$sql = "SELECT * FROM nomi_percepciones where activo=1";
		}
		elseif($tipo == 2){
			$sql = "SELECT * FROM nomi_deducciones where activo=1";
		}
		elseif($tipo == 4){
			$sql = "SELECT * FROM nomi_otros_pagos where activo=1";
		}
		return $this->query($sql);
	}
	function otrosPagos(){
		$sql = "SELECT * FROM nomi_otros_pagos where activo=1";
		return $this->query($sql);
	}
	function clasefactor($clase){
		
		return $this->query("SELECT * FROM nomi_fraccionriesgocatalogo where idclaveriesgotrabajo=$clase");
		
	}
	
	function empleadosDperiodo($fecha){
		$sql = $this->query("SELECT  
				e.idEmpleado,e.codigo,e.alimento,
				e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,
				e.idFormapago,e.email,e.nss,e.idEstadoCivil,e.idsexo,
				e.idestado,e.idmunicipio,e.rfc,e.curp,e.idestadosat,
				e.cp,e.numeroCuenta,e.claveinterbancaria,e.activo,
				e.idtipocontrato,e.idtipop,e.idbase,e.idDep,e.idPuesto,
				e.idtipoempleado,e.idbasepago,e.idturno,e.idregimencontrato,
				e.fonacot,e.afore,e.idregistrop,
				
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
					INNER JOIN nomi_configuracion c ON e.idtipop = c.idtipop
					LEFT JOIN nomi_historial_empleado h ON h.idEmpleado = e.idEmpleado
					left join nomi_historico_salarios s on  s.idEmpleado =  e.idEmpleado and s.fechaAplicacion<='$fecha'
				where  
					CASE e.activo WHEN -1 THEN e.fechaAlta<='$fecha' WHEN 3 THEN  h.fecha<='$fecha' END
				GROUP BY e.idEmpleado ORDER BY e.idEmpleado ASC;
				");
		if($sql->num_rows>0){
			return $sql;
		}else{
			return 0;
		}
	}
	function empleadosperiodoNominalibre($fecha){
		$sql = $this->query("select e.idEmpleado,e.codigo,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.salario,e.idFormapago,e.email,e.nss,e.idEstadoCivil,e.idsexo,
		e.idestado,e.idmunicipio,e.rfc,e.curp,e.idestadosat,e.cp,e.numeroCuenta,e.claveinterbancaria,e.activo,e.idtipocontrato,e.idtipop,e.idbase,e.sbcfija,e.idDep,e.idPuesto,e.idtipoempleado,e.idbasepago,e.idturno,e.idregimencontrato,e.fonacot,e.afore,e.idregistrop,
			(case e.activo when -1 then e.fechaAlta when 3 then  h.fecha when 2 then  h.fecha end) as fechaActiva
			from 
			nomi_empleados e
			left join nomi_historial_empleado h on h.idEmpleado = e.idEmpleado
			where  
			case e.activo when -1 then e.fechaAlta<='$fecha' when 3 then  h.fecha>='$fecha' when 2 then  h.fecha>='$fecha' end
			group by e.idEmpleado order by e.idEmpleado asc;
			");
		if($sql->num_rows>0){
			return $sql;
		}else{
			return 0;
		}
	}

	function rfcEmpleados(){ 
		
		$sql=$this->query("select rfc from nomi_empleados");
		return $sql;
	}

	
	function formapago(){
		$sql = $this->query("select * from forma_pago where claveSat != '' ORDER BY claveSat");
		return $sql;
	}
	function estadocivil(){
		$sql = $this->query("select * from nomi_estado_civil");
		return $sql;
	}
	function bancos(){
		$sql = $this->query("select * from cont_bancos");
		return $sql;
	}
	function estados(){
		
		$sql = $this->query("select * from estados");
		return $sql;
	}
	function municipios($idestado){
		$sql = $this->query("select * from municipios where idestado=".$idestado);
		return $sql;
	}
	function registroPatronal(){
		$sql = $this->query("select * from nomi_registropatronal where activo=-1");
		return $sql;
	}
	function tipoperiodo(){
		$sql = $this->query("select * from nomi_tiposdeperiodos where activo=1 order by nombre asc");
		return $sql;
	}

	function basecotizacion(){
		$sql = $this->query("select * from nomi_base_cotizacion");
		return $sql;
	}
	function departamento(){
		$sql = $this->query("select * from nomi_departamento where activo=-1");
		return $sql;
	}
	function puesto(){
		$sql = $this->query("select * from nomi_puesto where activo=-1");
		return $sql;
	}
	function tipoEmpleado(){
		$sql = $this->query("select * from nomi_tipo_empleado");
		return $sql;
	}
	function basePago(){
		$sql = $this->query("select * from nomi_base_pago");
		return $sql;
	}
	function turno(){
		$sql = $this->query("select * from nomi_turno");
		return $sql;
	}
	function regimenContratacion(){
		$sql = $this->query("select * from nomi_regimencontratacion");
		return $sql;
	}
	function tipocontrato(){
		$sql = $this->query("select * from nomi_tipocontrato");
		return $sql;
	}
	//cambiar por el menu d configuracion de nominas
	function validaNominas(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=2228");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	function validaNominasManual(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=2356");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	//appministra
	public function listaClas2($t)
	{
		return $this->query("SELECT c1.* FROM app_clasificadores c1 WHERE c1.tipo = $t AND c1.padre != 0 ORDER BY c1.padre");
	}
	function areaempleadoapp(){
		return $this->query("select * from app_area_empleado");
	}	
	function validaAppministra(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=1959 or idmenu=1960");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	// fin appministra
	
	function almacenaEmpleado($imagen,$codigo,$fechaAlta,$apellidoPaterno,$apellidoMaterno,$nombreEmpleado,$salario,$idzona,$idFormapago,$email,$nss,$idEstadoCivil,$idsexo,$fechaNacimiento,$idestado,$idmunicipio,$rfc,$curp,$direccion,$idestadosat,$poblacion,$cp,$telefono,$idbanco,$numeroCuenta,$claveinterbancaria,$tipocomision,$comision="''",$idclasificacion,$idareaempleado,$idtipocontrato,$idtipop,$idbase,$sbcfija="''",$sbcvariable="''",$sbctopado="''",$idDep,$idPuesto,$idtipoempleado,$idbasepago,$idturno,$idregimencontrato,$fonacot,$afore="''",$idregistrop,$umf,$avisosimss="''",$horasext1="''",$horasext2="''",$horasext3="''",$diastrabajados="''",$diaspagados="''",$diascotizados="''",$ausencias="''",$incapacidades="''",$vacaciones="''",$septimosprop,$salariovariable,$fechavariable="''",$fechadiario="''",$salariopromedio,$fechapromedio="''",$fechaintegrado="''",$salarioliquidacion,$salarioajusteneto,$alimento,$tipocuenta,$neto){


		
		if(!($imagen)){$imagen= 0;}
		if(!($salario)){ $salario = 0;}
		if(!($idclasificacion)){ $idclasificacion = 0;}
		if(!($idareaempleado)){ $idareaempleado = 0;}
		if(!($idtipop)){ $idtipop = 0;}
		if(!($tipocomision)){ $tipocomision = 0;}
		if(!($idtipocontrato)){ $idtipocontrato = 0;}
		if(!($idtipoempleado)){ $idtipoempleado = 0;}
		if(!($idPuesto)){ $idPuesto = 0;}
		if(!($idbase)){ $idbase = 0;}
		if(!($idbasepago)){ $idbasepago = 0;}
		if(!($idturno)){ $idturno = 0;}
		if(!($fonacot)){ $fonacot = 0;}
		if(!($idDep)){ $idDep = 0;}
		if(!($salariovariable)){ $salariovariable = 0;}
		if(!($idmunicipio)){ $idmunicipio = 0;}
		if(!($idregistrop)){ $idregistrop = 0;}
		if(!($septimosprop)){ $septimosprop = 0;}
		if(!($salariopromedio)){ $salariopromedio = 0;}
		if(!($salarioliquidacion)){ $salarioliquidacion = 0;}
		if(!($salarioajusteneto)){ $salarioajusteneto = 1;}
		if(!($idregimencontrato)){ $idregimencontrato = 0;}
		if(!($alimento)){ $alimento = 0;}
		if(!($tipocuenta)){ $tipocuenta = 0;}
		if(!($rfc)){ $rfc = "";}
		if(!($curp)){ $curp = "";}
		if(!($fechaAlta)){ $fechaAlta = "";}
		if(!($idzona)){ $idzona =0;}
		if(!($tipocuenta)){ $tipocuenta = 0;}else{$tipocuenta = intval($tipocuenta);}
		if(!($neto)){ $neto =0;}
		
		
		
		$sql = "INSERT INTO nomi_empleados 
		(imagen,codigo, fechaAlta, apellidoPaterno, apellidoMaterno, nombreEmpleado, salario, idzona, idFormapago, email, nss, idEstadoCivil, idsexo, fechaNacimiento, idestado, idmunicipio, rfc, curp, direccion, poblacion, idestadosat, cp, telefono, idbanco, numeroCuenta, claveinterbancaria, id_tipo_comision, comision, id_clasificacion, id_area_empleado, idtipocontrato, idtipop, idbase, sbcfija, sbcvariable, sbctopado, idDep, idPuesto, idtipoempleado, idbasepago, idturno, idregimencontrato, fonacot, afore, idregistrop, umf, avisosimss, horasext1, horasext2, horasext3, diastrabajados, diaspagados, diascotizados, ausencias, incapacidades, vacaciones, septimosprop, salariovariable, fechavariable, fechadiario, salariopromedio, fechapromedio, fechaintegrado, salarioliquidacion, salarioajusteneto,alimento,tipocuenta,sueldoneto)
		VALUES
		(\"$imagen\",\"$codigo\", \"$fechaAlta\", \"$apellidoPaterno\", \"$apellidoMaterno\", \"$nombreEmpleado\", $salario, $idzona, $idFormapago, \"$email\", \"$nss\", $idEstadoCivil, $idsexo, \"$fechaNacimiento\", $idestado, $idmunicipio, \"$rfc\", \"$curp\", \"$direccion\", \"$poblacion\", $idestadosat, $cp, \"$telefono\", $idbanco, \"$numeroCuenta\", \"$claveinterbancaria\",  $tipocomision, $comision, $idclasificacion, $idareaempleado,  $idtipocontrato, $idtipop, $idbase, $sbcfija, $sbcvariable, $sbctopado, $idDep, $idPuesto, $idtipoempleado, $idbasepago, $idturno, $idregimencontrato, $fonacot, '$afore', $idregistrop, $umf, \"$avisosimss\", $horasext1, $horasext2, $horasext3, $diastrabajados, $diaspagados, $diascotizados, $ausencias, $incapacidades, $vacaciones, $septimosprop, $salariovariable, \"$fechavariable\", \"$fechadiario\", $salariopromedio, \"$fechapromedio\", \"$fechaintegrado\", $salarioliquidacion, $salarioajusteneto, \"$alimento\",$tipocuenta,$neto);
		";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}

	function updateEmpleado($imagen,$codigo,$fechaAlta,$apellidoPaterno,$apellidoMaterno,$nombreEmpleado,$salario,$idzona,$idFormapago,$email,$nss,$idEstadoCivil,$idsexo,$fechaNacimiento,$idestado,$idmunicipio=0,$rfc,$curp,$direccion,$idestadosat,$poblacion,$cp,$telefono,$idbanco,$numeroCuenta,$claveinterbancaria,$tipocomision=0,$comision=0,$idclasificacion=0,$idareaempleado=0,$idtipocontrato,$idtipop=0,$idbase,$sbcfija,$sbcvariable,$sbctopado,$idDep=0,$idPuesto=0,$idtipoempleado,$idbasepago,$idturno=0,$idregimencontrato,$fonacot=0,$afore='""',$idregistrop=0,$umf,$avisosimss,$horasext1,$horasext2,$horasext3,$diastrabajados,$diaspagados,$diascotizados,$ausencias,$incapacidades,$vacaciones,$septimosprop,$salariovariable,$fechavariable="''",$fechadiario="''",$salariopromedio,$fechapromedio="''",$fechaintegrado="''",$salarioliquidacion,$salarioajusteneto,$alimento,$tipocuenta,$idempleado,$fechahistorial,$SalarioHistorico,$neto){
// echo $idempleado;

//echo $SalarioHistorico;

		if(!($imagen)){ $imagen = 0;}

// if(!($salario)){ $salario = 0;}
		if(!($idclasificacion)){ $idclasificacion = 0;}
		if(!($idareaempleado)){ $idareaempleado = 0;}
		if(!($idtipop)){ $idtipop = 0;}
		if(!($tipocomision)){ $tipocomision = 0;}
		if(!($idtipocontrato)){ $idtipocontrato = 0;}
		if(!($idtipoempleado)){ $idtipoempleado = 0;}
		if(!($idPuesto)){ $idPuesto = 0;}
		if(!($idbase)){ $idbase = 0;}
		if(!($idbasepago)){ $idbasepago = 0;}
		if(!($idturno)){ $idturno = 0;}
		if(!($fonacot)){ $fonacot = 0;}
		if(!($idDep)){ $idDep = 0;}
		if(!($salariovariable)){ $salariovariable = 0;}
		if(!($idmunicipio)){ $idmunicipio = 0;}
		if(!($idregistrop)){ $idregistrop = 0;}
		if(!($septimosprop)){ $septimosprop = 0;}
		if(!($salariopromedio)){ $salariopromedio = 0;}
		if(!($salarioliquidacion)){ $salarioliquidacion = 0;}
		if(!($salarioajusteneto)){ $salarioajusteneto = 1;}
		if(!($idregimencontrato)){ $idregimencontrato = 0;}
		if(!($tipocuenta)){ $tipocuenta = 0;}
		if(!($neto)){ $neto =0;}

		$salari=0;
		$sdi=0;


		if ($SalarioHistorico==1) {

			$salari=("(SELECT salario)");
		}else
			{
			$salari=$salario;
			}

		if ($SalarioHistorico==1) {

			$sdi=("(SELECT sbcfija)");

		}else
			{
			$sdi=$sbcfija;

		 	}


		$sql = "UPDATE nomi_empleados SET

		". (($imagen != null) ? "imagen = '". $imagen ."'," : "imagen=imagen,") ."

		codigo = '$codigo',
		apellidoPaterno = '$apellidoPaterno',
		apellidoMaterno = '$apellidoMaterno',
		nombreEmpleado = '$nombreEmpleado' ,
		salario=$salari,
		idzona = $idzona,
		idFormapago =$idFormapago,
		email = '$email', 
		nss = '$nss', 
		idEstadoCivil = $idEstadoCivil, 
		idsexo = $idsexo, 
		fechaNacimiento = '$fechaNacimiento', 
		idestado = $idestado, 
		idmunicipio = $idmunicipio, 
		rfc = '$rfc', 
		curp = '$curp', 
		direccion = '$direccion' , 
		poblacion = '$poblacion', 
		idestadosat = $idestadosat, 
		cp = $cp, 
		telefono = '$telefono', 
		idbanco = $idbanco, 
		numeroCuenta = '$numeroCuenta', 
		claveinterbancaria =  '$claveinterbancaria', 
		id_tipo_comision = $tipocomision, 
		comision = $comision, 
		id_clasificacion = $idclasificacion, 
		id_area_empleado = $idareaempleado, 
		idtipocontrato = $idtipocontrato, 
		idtipop = $idtipop, 
		idbase = $idbase, 
		sbcfija = $sdi, 
		sbcvariable = $sbcvariable, 
		sbctopado = $sbctopado, 
		idDep = $idDep, 
		idPuesto = $idPuesto, 
		idtipoempleado = $idtipoempleado, 
		idbasepago = $idbasepago, 
		idturno = $idturno, 
		idregimencontrato = $idregimencontrato, 
		fonacot = $fonacot, 
		afore = '$afore', 
		idregistrop = $idregistrop, 
		umf = $umf, 
		avisosimss = '$avisosimss', 
		horasext1 = $horasext1, 
		horasext2 = $horasext2,
		horasext3 = $horasext3, 
		diastrabajados = $diastrabajados, 
		diaspagados = $diaspagados, 
		diascotizados = $diascotizados, 
		ausencias = $ausencias, 
		incapacidades = $incapacidades, 
		vacaciones = $vacaciones, 
		septimosprop = $septimosprop, 
		salariovariable = $salariovariable, 
		fechavariable = '$fechavariable', 
		fechadiario = '$fechadiario', 
		salariopromedio = $salariopromedio, 
		fechapromedio = '$fechapromedio', 
		fechaintegrado =  '$fechaintegrado', 
		salarioliquidacion = $salarioliquidacion, 
		salarioajusteneto = $salarioajusteneto,
		alimento = '$alimento',
		tipocuenta = $tipocuenta,
		sueldoneto = $neto
		where idEmpleado=".$idempleado;

		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function editarEmpleado($idempleado){



		$empleados = $this->query("SELECT distinct 
			(case s.idEmpleado WHEN  ne.idEmpleado
			THEN (select s.nuevoSalario from nomi_historico_salarios s where s.idEmpleado =  ne.idEmpleado order by s.fechaAplicacion desc,s.idEmpleado desc limit 1) 
			ELSE ne.salario END)salarionuevo,		
			(case s.idEmpleado WHEN  ne.idEmpleado
			then 1
			ELSE 0 END)SalarioHistorico,		
			case ne.activo when -1 then 0 else 1 end as fechahistorial,
			(case s.idEmpleado WHEN  ne.idEmpleado THEN (select s.nuevoSDI from nomi_historico_salarios s where s.idEmpleado =  ne.idEmpleado order by s.fechaAplicacion desc,s.idEmpleado desc limit 1) 
			ELSE ne.sbcfija END)sbcfijahisto,
			case ne.activo when -1 then ne.fechaAlta else he.fecha end as fecha_altabajareingreso,
			case ne.activo when -1 then 'Alta' when 2 then 'Baja' when 3 then 'Reingreso' end as descripcionestatus,
			ne.* from nomi_empleados ne 
			left join nomi_historial_empleado he 
			on he.idEmpleado=ne.idEmpleado and he.tipo =ne.activo 
			left join nomi_historico_salarios  s on  s.idEmpleado =  ne.idEmpleado
			where ne.idEmpleado=$idempleado order by he.idhistorial desc limit 1;");

		return $empleados->fetch_object();


	}

	function listaEmpleados(){
		return $this->query("select e.idEmpleado,e.codigo,e.fechaAlta,e.apellidoPaterno,e.apellidoMaterno,e.nombreEmpleado,e.salario,e.salario,e.idFormapago,e.email,e.nss,e.idEstadoCivil,e.idsexo,
		e.idestado,e.idmunicipio,e.rfc,e.curp,e.idestadosat,e.cp,e.numeroCuenta,e.claveinterbancaria,e.activo,e.idtipocontrato,e.idtipop,e.idbase,e.sbcfija,e.idDep,e.idPuesto,e.idtipoempleado,e.idbasepago,e.idturno,e.idregimencontrato,e.fonacot,e.afore,e.idregistrop
			 from nomi_empleados e");
	}
	function eliminarEmpleado(){
		$sql = "delete from nomi_empleados where idEmpleado=".$idempleado;
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	//(tipo-accion) 1-alta,2,baja,3-reingreso
	function accionEmpleado($idempleado,$accion,$fecha){
		$sql = "update nomi_empleados set activo=$accion where idEmpleado=".$idempleado."; 
		INSERT INTO nomi_historial_empleado (idEmpleado, tipo, fecha)
		VALUES
		($idempleado, $accion, '$fecha');
		";	
		//return $sql;
		if($this->dataTransact($sql) == true){				
			return 1;
		}else{
			return 0;
		}
	}

	
	/* catalogo de conceptos */
	function tipoconcepto(){
		return $this->query("select * from nomi_tipoconcepto");
	}
	// function clavesat(){
		// return $this->query("select * from nomi_tipoconcepto");
	// }
	function percepcion(){
		return $this->query("select * from nomi_percepciones");
	}
	function deduccion(){
		return $this->query("select * from nomi_deducciones");
	}
	function horasext(){
		return $this->query("select * from nomi_tipohoras");
	}
	function listaConceptos(){
		return $this->query("
			SELECT n.*,c.tipo,
			(CASE n.idtipo WHEN 1 THEN p.descripcion WHEN 2 THEN d.descripcion WHEN   4 THEN  o.descripcion ELSE '' END) sat
			FROM nomi_conceptos n
			INNER JOIN nomi_tipoconcepto c ON c.idtipo=n.idtipo
			LEFT JOIN nomi_percepciones p ON p.idAgrupador=n.idAgrupador
			LEFT JOIN nomi_deducciones d ON d.idAgrupador=n.idAgrupador
			LEFT JOIN nomi_otros_pagos o ON o.idAgrupador=n.idAgrupador
			WHERE 
			(CASE 	
			n.idtipo 
			WHEN 1 
			THEN 	
			p.idAgrupador=n.idAgrupador OR n.idAgrupador=0 
			WHEN 2 
			THEN 
			d.idAgrupador=n.idAgrupador OR n.idAgrupador=0 
			WHEN  4 
			THEN o.idAgrupador=n.idAgrupador OR n.idAgrupador=0 
			ELSE n.idAgrupador=0 END) 
			AND n.activo=1
			ORDER BY c.tipo ASC
			");
	}
	

	function consulte($idconcepto){
		
		$sql= $this->query("SELECT * from nomi_calculo_prenomina where idconfpre=$idconcepto;");

		if ($sql->num_rows>0){
			return 1;
		}else
			{
			return 0;
			}
		}

	function editarConcepto($idconcepto){

		$concepto = $this->query("select * from nomi_conceptos where idconcepto=".$idconcepto);
		return $concepto->fetch_object();

	}
	function almacenaConcepto($codigo,$descripcion,$global,$liquidacion,$especie,$idAgrupador,$idtipo, $idhora, $idFormapago,$idconcepto){
		
		$sql = "INSERT INTO nomi_conceptos (concepto, descripcion, global, liquidacion, especie, idAgrupador, idtipo, idhora, idFormapago)
		VALUES
		('$codigo','$descripcion',$global,$liquidacion,$especie,$idAgrupador,$idtipo, $idhora, $idFormapago);
		";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}

	//A C T U A L I Z A R   C O N C E P T O 
	function updateConcepto($codigo,$descripcion,$global,$liquidacion,$especie,$idAgrupador,$idtipo, $idhora, $idFormapago,$idconcepto){

		$sql = $this->query("SELECT 1 from nomi_calculo_prenomina where idconfpre=$idconcepto;");
		if($sql->num_rows>0){

			$sql = $this->query("UPDATE 
				nomi_conceptos 
				SET 	 
				idAgrupador = $idAgrupador
				WHERE 
				idconcepto = $idconcepto");

			return 2;
		}else{

			$sql = "UPDATE 
			nomi_conceptos 
			SET 
			concepto = '$codigo', 
			descripcion = '$descripcion', 
			global = $global, 
			liquidacion = $liquidacion, 
			especie = $especie, 
			idAgrupador = $idAgrupador, 
			idtipo = $idtipo, 
			idhora = '$idhora', 
			idFormapago = '$idFormapago'
			WHERE 
			idconcepto = $idconcepto;";
		}

		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	
	// configuracion	
	function regimenFiscal(){
		$sql = $this->query("select * from nomi_regimenfiscal");
		return $sql;
	}
	
	function configuracionNominas(){
		
		$sql = $this->query("select * from nomi_configuracion");
		if ($sql->num_rows>0){
			return $sql->fetch_object();
		}
	}

	function percepcionesConfiguracion(){
		$sql = $this->query("
			SELECT * FROM nomi_conceptos WHERE idtipo=1 AND activo=1 AND concepto!=0");
		if ($sql->num_rows>0){
			return $sql;
		}
	}
	function actualizaConfiguracion($tipo,$idregfiscal,$factordeduexent,$idregistrop,$reginfonavit,$centrotrabajofonacot,$regss,$regcomisionmixta,$periodosfuturos,$ptu,$aguinaldo,$primavac,$vactiempo,$calculoinvertido,$idzona,$emitirsellos,$fechainicio,$curp,$idtipop,$representa){
		//if($tipo == 1){
		$sql = "UPDATE nomi_configuracion
		
		SET 
		factordeduexent= $factordeduexent,
		idregistrop = $idregistrop,
		reginfonavit = '$reginfonavit',
		centrotrabajofonacot = $centrotrabajofonacot,
		regss = '$regss',
		regcomisionmixta = '$regcomisionmixta',
		periodosfuturos = $periodosfuturos,
		ptu = $ptu,
		aguinaldo = $aguinaldo,
		primavac = $primavac,
		vactiempo = $vactiempo,
		calculoinvertido = $calculoinvertido,
		idzona = $idzona,
		emitirsellos = $emitirsellos,
		fechainicio = '$fechainicio',
		curp = '$curp',
		idtipop = $idtipop,
		representantelegal = '$representa'

		WHERE idconfnomi=1;";
		//}else{		
		
			// $sql = "INSERT INTO nomi_configuracion 
						// (fechainicio, idregfiscal, factordeduexent, idregistrop, reginfonavit, centrotrabajofonacot, regss, regcomisionmixta, periodoanteriores, periodosfuturos, ptu, aguinaldo, primavac, vactiempo, calculoinvertido, emitirsellos, idzona)
					// VALUES
						// ('$fechainicio', $idregfiscal, $factordeduexent, $idregistrop,'$reginfonavit', $centrotrabajofonacot, '$regss', '$regcomisionmixta', $periodoanteriores, $periodosfuturos, $ptu, $aguinaldo, $primavac, $vactiempo, $calculoinvertido, $emitirsellos, $idzona);
			// ";
		//}
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
		
	}
	
	
	/* en 2017 se uliza solo la zona b pero
	 * se dejara con el catalogo por si la ley
	 * cambia de parecer ¬¬
	 */
	function zona(){
		$sql = $this->query("select * from nomi_zona");
		return $sql;
	}
	function organizacion(){
		$sql = $this->query("select o.*,f.descripcion,e.estado,m.municipio 
			from 
			organizaciones o
			left join	nomi_regimenfiscal f on f.idregfiscal = o.idregfiscal
			left join	estados e on  e.idestado=o.idestado
			left join municipios m on m.idmunicipio=o.idmunicipio
			LIMIT 1
			");
		return $sql->fetch_object();
	}
	
	
	/* C O N F I G U R A C I O N    P R E N O M I N A */
	
	function conceptosprenomina($tipo){
		$sql = $this->query("
			SELECT
			n.idconcepto,n.idtipo,n.descripcion,n.concepto
			FROM 
			nomi_conceptos n
			WHERE 
			n.activo=1 
			AND n.idtipo=$tipo
			AND n.activo=1
			AND n.idconcepto NOT IN (SELECT idconcepto FROM nomi_conf_prenomina)
			ORDER BY 
			n.concepto ASC");
		return $sql;
	}
	function eliminapreviosprenomina($default){
		if($default==1){
			$sql = "truncate nomi_conf_prenomina_default;";
		}else{
			$sql = "truncate nomi_conf_prenomina;";
		}
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function almacenaConceptosPrenomina($idtipo,$idconcepto,$valor,$importe,$default){
		
		$sql ="INSERT INTO nomi_conf_prenomina (idtipo, idconcepto, valor, importe)
		VALUES
		($idtipo,$idconcepto,$valor,$importe);
		";
		
		if($this->query($sql)){
			if($default==1){
				$sql ="INSERT INTO nomi_conf_prenomina_default (idtipo, idconcepto, valor, importe)
				VALUES
				($idtipo,$idconcepto,$valor,$importe);
				";
				if($this->query($sql)){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 1;
			}
			
		}else{
			return 0;
		}
	}
	function idtipo($idconcepto){
		$sql = $this->query("SELECT idtipo FROM nomi_conceptos WHERE idconcepto=$idconcepto");
		$tipo = $sql->fetch_object();
		return $tipo->idtipo;
	}
	function conceptosPrenominaExiste(){
		$sql = $this->query("SELECT p.*,c.descripcion,c.concepto,c.idAgrupador FROM nomi_conf_prenomina p 
			INNER JOIN nomi_conceptos c ON p.idconcepto = c.idconcepto order by p.idtipo asc;");
		return $sql;
	}
	function conceptosPrenominaDefault(){
		$sql = $this->query("SELECT p.*,c.descripcion,c.concepto FROM nomi_conf_prenomina_default p 
			INNER JOIN nomi_conceptos c ON p.idconcepto = c.idconcepto;");
		return $sql;
	}

	function accionEliminarConcepto($idconfpre){
		
		$sql = $this->query("select * from nomi_calculo_prenomina where idconfpre=$idconfpre;");
		if($sql->num_rows>0){
			return 2;

		}
		$sql = $this->query("select * from nomi_conf_prenomina_default where idconcepto=$idconfpre;");
		
		if($sql->num_rows>0){
			return 2;
		}

		$sql = $this->query("select * from nomi_conf_prenomina where idconcepto=$idconfpre;");
		if($sql->num_rows>0){
			return 2;

		}

		else{
			$sql=  
			"DELETE FROM nomi_conceptos where idconcepto = $idconfpre;";
			if(!$this->multi_query($sql)){
				return 0;
			}
			else return 1; 
		}
	}
	
	
	function insertPeriodoNominas($idtipop,$numnomina,$fechainicio,$fechafin,$ejercicio,$mes, $diaspago, $iniciomes, $iniciobimentreimss, $inicioejercicio, $finmes, $finbimentreimss, $finejercicio){
		$sql =$this->query( "INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
			VALUES
			( $idtipop,$numnomina,$fechainicio,$fechafin,$ejercicio,$mes, $diaspago, $iniciomes, $iniciobimentreimss, $inicioejercicio, $finmes, $finbimentreimss, $finejercicio);
			");
	}
	//SDI
	function antiguedades($idano){
		$sql = $this->query("SELECT * FROM nomi_antiguedades WHERE idantiguedad = ROUND($idano,0)");
		return $sql->fetch_object();
	}
	/* tipos de periodo */
	function tipoperiodocatalogo(){
		$sql = $this->query("select t.*,p.descripcion,p.clave from nomi_tiposdeperiodos t
			inner join nomi_periodicidad p on p.idperiodicidad=t.idperiodicidad
			");
		return $sql;
	}
	function accionTipop($idtipo,$accion){
		$sql1 = $this->query( "select c.* from nomi_calculo_prenomina c,nomi_tiposdeperiodos p, nomi_nominasperiodo n where n.idtipop=p.idtipop and c.idnomp = n.idnomp and p.idtipop=$idtipo and c.autorizado=1");
		if($sql1->num_rows>0){
			return 2;
		}else{
			$sql = "update nomi_tiposdeperiodos set activo=$accion where idtipop=".$idtipo;
			if($this->query($sql)){
				return 1;
			}else{
				return 0;
			}
		}

		
	} 
	function periodicidad(){
		$sql = $this->query("select * from nomi_periodicidad");
		return $sql;
	}
	//EDITAR PERIODO
	function editarTipoperidoo($idtipo){


		$tipoperiodo = $this->query("select * from nomi_tiposdeperiodos where idtipop=".$idtipo);
		return $tipoperiodo->fetch_object();





	}
	function almacenaTipoPeriodo($fechainicio, $nombre, $diasperiodo, $diaspago, $periodotrabajo, $ajustemes, $septimodia, $idajuste, $diapago, $idperiodicidad,$idtipop,$extraordinario){

		if(empty($idajuste)){
			$idajuste = 1;
		}
		$existe = 0;
		if($extraordinario == 1){
			//comprobamos si existe un extraordinario del ejercicio no puede existir mas de uno
			$ejercicio = explode('-', $fechainicio);
			$periodo = $this->query("select * from nomi_tiposdeperiodos where fechainicio like '%".$ejercicio[0]."%' and extraordinario=1");
			if($periodo->num_rows>0){
				$existe = 1;
			}
		}
		if($existe == 0){
			$sql = "INSERT INTO nomi_tiposdeperiodos ( fechainicio, nombre, diasperiodo, diaspago, periodotrabajo, ajustemes, septimodia, idajuste, diapago, idperiodicidad, activo,extraordinario)
			VALUES
			('$fechainicio', '$nombre', $diasperiodo, $diaspago, $periodotrabajo, $ajustemes, $septimodia, $idajuste, $diapago, $idperiodicidad, 1,$extraordinario);
			";
			$id = $this->insert_id($sql);
			if($id){
				return $id;
				
			}else{
				return 0;
			}
		}else{
			return -1;
		}
		
	}
	function eliminaNominasdelperiodo($idtipop){
		/*verifica que no tenga nominas autorizadas del periodo que se va eliminar si existen no podra eliminar*/
		$sql1 = $this->query( "select c.* from nomi_calculo_prenomina c,nomi_tiposdeperiodos p, nomi_nominasperiodo n where n.idtipop=p.idtipop and c.idnomp = n.idnomp and p.idtipop=$idtipop and c.autorizado=1");
		if($sql1->num_rows>0){
			return 2;
		}else{
			$sql ="DELETE FROM nomi_nominasperiodo WHERE idtipop=".$idtipop;
			if($this->query($sql)){
				return 1;
			}else{
				return 0;
			}
			
		}	
		
	}
	
	// function insertPeriodo($idtipop, $numnomia, $fecha3, $fecha, $ejercicio, $peri, $diaspago, $iniciomes, $iniciobimentreimss, $inicioejercicio, $finmes, $finbimentre, $finejercicio){
	// 	
		// $sql=("INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
		// VALUES
			// ( $idtipop, $numnomia, '$fecha3', '$fecha', $ejercicio, $peri, $diaspago, $iniciomes, $iniciobimentreimss,$inicioejercicio, $finmes, $finbimentre, $finejercicio);
			// ");
		// if($this->query($sql)){
			// return 1;
		// }else{
			// return 0;
		// }	
	// 	
	// 	
	// }
	function insertPeriodo($consultas){
		$sql = $this->dataTransact($consultas);
		if($sql  == true ){
			return 1;
		}else{
			return 0;
		}	
		
		
	}
	function ajusteDias(){
		$sql = $this->query("select * from nomi_ajustedias;");
		return $sql;
	}
	function updateTipoPeriodo($fechainicio, $nombre, $diasperiodo, $diaspago, $periodotrabajo, $ajustemes, $septimodia, $idajuste, $diapago, $idperiodicidad,$idtipop){

		if(empty($idajuste)){
			$idajuste = 1;
		}
		$sql = "UPDATE 
		nomi_tiposdeperiodos 
		SET 
		fechainicio = '$fechainicio', 
		nombre = '$nombre', 
		diasperiodo = $diasperiodo, 
		diaspago = $diaspago, 
		periodotrabajo = $periodotrabajo, 
		ajustemes = $ajustemes, 
		septimodia = $septimodia, 
		idajuste = $idajuste, 
		diapago = $diapago,
		idperiodicidad = $idperiodicidad
		where 
		idtipop = $idtipop;";
		
		if($this->query($sql)){
			return $idtipop;
		}else{
			return 0;
		}
	}
	
	/* P  E	R	I	O	D	O	S */
	
	function listadoNominasxPeriodo($idtipop){
		$sql =$this->query("SELECT * FROM nomi_nominasperiodo WHERE idtipop=".$idtipop);
		return $sql;	
	}

	function datosNomina($idNomina){
		$sql =$this->query("SELECT * FROM nomi_nominasperiodo WHERE idnomp=".$idNomina);
		return $sql->fetch_object();	
	}


	function updatePeriodo($idnomp,$numnomina,$fechainicio,$fechafin,$diaspago,$iniciomes, $iniciobimentreimss, $inicioejercicio, $finmes, $finbimentreimss, $finejercicio){


		$sql1 = $this->query("SELECT 1 from nomi_calculo_prenomina      where autorizado=1 and idnomp=$idnomp;");

		$sql2 = $this->query("SELECT 1 from nomi_claveincidencias       where autorizado=1 and idnomp=$idnomp;");

		$sql6 = $this->query("SELECT 1 from nomi_nominasperiodo        where autorizado=1 and idnomp=$idnomp;");

		$sql3 = $this->query("SELECT 1 from nomi_registro_entradas      where  idnomp=$idnomp;");
		$sql4 = $this->query("SELECT 1 from nomi_infonavit_sobrerecibo  where  idnomp=$idnomp;");
		$sql5 = $this->query("SELECT 1 from nomi_vacaciones_sobrerecibo where  idnomp=$idnomp;");


		if($sql1->num_rows>0 or ($sql2->num_rows>0) or ($sql3->num_rows>0) or ($sql4->num_rows>0) or ($sql5->num_rows>0) or ($sql6->num_rows>0)){
			return 2;

		}else{

			$sql = "UPDATE 
			nomi_nominasperiodo 
			SET 
			numnomina			    = $numnomina, 
			fechainicio 			= '$fechainicio', 
			fechafin				= '$fechafin', 
			diaspago 			    = $diaspago, 
			iniciomes 			    = $iniciomes, 
			iniciobimentreimss 	    = $iniciobimentreimss, 
			inicioejercicio 		= $inicioejercicio, 
			inicioejercicio 		= $inicioejercicio, 
			finmes 				    = $finmes,
			finbimentreimss 		= $finbimentreimss,
			finejercicio			= $finejercicio
			where 
			idnomp = $idnomp;";
		}
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}

	//funcion para validar la fecha de los periodos AM
	function validarfechaperiodos(){
		$sql = $this->query("SELECT fechainicio FROM nomi_configuracion");
		$fecha = $sql->fetch_object();
		return $fecha->fechainicio;

	}


	// /* prenomina */ //incidencias
	// function nominasPeriodo(){
	// 		$sql = $this->query("select p.*,c.idtipop from nomi_nominasperiodo p inner join nomi_configuracion c on p.idtipop=c.idtipop");
	// 		return $sql;
	// }
	// 
	
	function nominasPeriodo(){
		$sql = $this->query("SELECT nomi.*, periodoactual.idtipop,periodoactual.periodosfuturos,/*tomamos los campos seleccionados de la subconsulta*/
			case 
			when 
			nomi.idnomp < periodoactual.idnomp /*si el periodo de nominasperiodos es menor al periodo de la subconsulta entonces es periodo anteriors*/
			then 'p_anterior' /*los periodos anteriores al actual*/
			when 
			nomi.idnomp =periodoactual.idnomp  
			then 'p_actual'  /*periodo actual*/
			when 
			nomi.idnomp>periodoactual.idnomp 
			then 'p_futuro'    /*periodo futuro*/
			END as clasedeperiodo,/*creamos un campo en la consulta si es para saber la clase de periodo, anterior,actual o futuro*/
			case 
			when 
			nomi.idnomp=periodoactual.idnomp /**/
			then 1 /*asiganmos el valor de 1 al campo editable que indica si el periodo es editable*/
			else 
				case when nomi.idnomp>periodoactual.idnomp then periodoactual.periodosfuturos /*si el idnomp de nomi es mayor al idnomp del periodo actual de la subconsulta, entonces los periodos futuros son 1*/
			else 0
				end
			end as editable /*Creamos un campo en la consulta para saber los que tengan 1 podran ser editados*/
			from nomi_nominasperiodo as nomi, /**/
			
			(select idnomp, p.idtipop,c.periodosfuturos				/*hacemos una subconsulta para traer el periodo actual*/
			from nomi_nominasperiodo p 				/*(el primero que encuentra como autorizado=0)*/
			inner join nomi_configuracion c 
			on p.idtipop=c.idtipop /*idtipop de nomi_nominasperiodo es igual a idtipop de nomi_configuracion*/
			where 
			p.autorizado=0 
			order by numnomina asc limit 1 /*ordena por el campo numnomina mostrando solo el primer elemento que tenga 0 en el autorizdo*/
			) as periodoactual /*el periodo actual se tomara el primer 0 que encuentre la subconsonsulta*/
			where nomi.idtipop=periodoactual.idtipop
			");
		return $sql;
	}
	
	

	function 	valiperioact(){
		
		$sql = $this->query("select idnomp,p.fechainicio, p.fechafin,p.ejercicio,p.numnomina
			from nomi_nominasperiodo p 				 
			inner join nomi_configuracion c 
			on p.idtipop=c.idtipop 
			where 
			p.autorizado=0 
			order by numnomina asc limit 1");
		
		return $sql->fetch_array();
		
	}


	function periodoactual(){
		
		$sql = $this->query("select idnomp,p.fechainicio, p.fechafin,p.ejercicio,p.numnomina
			from nomi_nominasperiodo p 				 
			inner join nomi_configuracion c 
			on p.idtipop=c.idtipop 
			where 
			p.autorizado=0 
			order by numnomina asc limit 1");
		
		$periodoactual = $sql->fetch_object();
		return $periodoactual;
	}
	function periodoactualPrenomina(){
		
		$sql = $this->query("select idnomp,p.fechainicio, p.fechafin,p.ejercicio,p.numnomina,p.idtipop
			from nomi_nominasperiodo p 				 
			inner join nomi_configuracion c 
			on p.idtipop=c.idtipop 
			where 
			p.autorizado=0 
			order by numnomina asc limit 1");
		
		
		return $sql;
	}

	
	/* NOMINA ACTIVA ACORDE A AL PERIODO DE CONFIGURACION */
	function nominaActiva(){
		$sql = $this->query("select n.*,p.nombre
			from 
			nomi_nominasperiodo n
			inner join nomi_configuracion c on n.idtipop=c.idtipop
			inner join nomi_tiposdeperiodos p on p.idtipop=n.idtipop
			where 
			n.autorizado=0 
			order by numnomina asc limit 1");
		/* en teoría siempre tendría q ver una nomina activa
		 * ya que al finalizar un ejercicio abre  otro por default 
		 */
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			0;
		}
	}
	function insertSQL($sql){
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
		
	}
	
	function huellaEmpleado($idEmpleado){
		
		$sql = $this->query("SELECT * FROM  nomi_empleadoReHuella where idEmpleado=$idEmpleado");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	function eliminaHuella($idEmpleado){
		
		$sql = "delete from nomi_empleadoReHuella where idEmpleado=$idEmpleado;";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}

}
?>