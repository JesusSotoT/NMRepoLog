<?php
	class NominalibreModel extends CatalogosModel{
		
		function datosaFacturacion(){
			$sql = $this->query("select * from pvt_configura_facturacion;");
			if($sql->num_rows>0){
				return $sql->fetch_object();
			}else{
				return 0;
			}
		}
		function infoFactura(){
			$sql=$this->query("SELECT c.*,r.clave clavereg FROM pvt_configura_facturacion c,nomi_regimenfiscal r where r.idregfiscal = c.regimen;");
			return $sql;
		}
		function tiponomina(){
			$sql=$this->query("SELECT * FROM nomi_tiponomina;");
			return $sql;
		}
		function tipoHoras(){
			$sql=$this->query("SELECT * FROM nomi_tipohoras;");
			return $sql;
		}
		function incapacidadeslista(){
			$sql=$this->query("SELECT * FROM nomi_incapacidad;");
			return $sql;
		}
		function registroPatronalEdicion($idregistrop){
			$sql = $this->query("select registro from nomi_registropatronal where  idregistrop = $idregistrop");
			return $sql->fetch_object();
		}
		function tipocontratoEdicion($idtipocontrato){
			$sql = $this->query("select clave from nomi_tipocontrato where idtipocontrato=$idtipocontrato");
			return $sql->fetch_object();
		}
		function turnoJornada($idturno){
			$sql = $this->query("select j.clave from nomi_turno t, nomi_jornada j where t.idturno=$idturno and t.idjornada = j.idjornada");
			return $sql->fetch_object();
		}
		function regimenContrato($idregimencontrato){
			$sql = $this->query("select clave from nomi_regimencontratacion where idregimencontrato = $idregimencontrato");
			return $sql->fetch_object();
		}
		function depaNombre($idDep){
			$sql = $this->query("select nombre from nomi_departamento where idDep = $idDep;");
			return $sql->fetch_object();
		}
		function puestoNombre($idPuesto){
			$sql = $this->query("select p.nombre,r.idclaveriesgopuesto from nomi_puesto p,nomi_riesgopuesto r where p.idPuesto =$idPuesto and p.idclaveriesgopuesto = r.idclaveriesgopuesto;");
			return $sql->fetch_object();
		}
		//PeriodicidadPago relacionado al periodo que tiene cada empleado
		// en el periodo se define la periodicidad la cual se ultiliza la clave para el xml
		function PeriodicidadPago($idtipop){
			$sql = $this->query("select p.clave from nomi_tiposdeperiodos t, nomi_periodicidad p where t.idtipop=$idtipop and t.idperiodicidad = p.idperiodicidad;");
			return $sql->fetch_object();
		}
		function periodicidadpagoLibre(){
			$sql=$this->query("SELECT * FROM nomi_periodicidad;");
			return $sql;
		}
		function bancoClave($idbanco){
			$sql = $this->query("select Clave from cont_bancos where idbanco = $idbanco");
			return $sql->fetch_object();
		}
		function estadoClave($idestado){
			$sql = $this->query("select clave from estados where idestado = $idestado");
			return $sql->fetch_object();
		}
		function almacenaTimbrado($idEmpleado,$fechainicial,$fechafinal,$fechapago,$diaspago,$tiponomina,$idnomp,$subtotal,$descuento,$total,$timbrado,$selloSAT,$selloCFD,$fechaTimbrado,$UUID,$nombreXML,$cancelado,$regfiscalclave,$periodicidadclave){
			$sql = "INSERT INTO nomi_nominas_timbradas ( idEmpleado, fechainicial, fechafinal, fechapago, diaspago, tiponomina, idnomp, subtotal, descuento, total, timbrado, selloSAT, selloCFD, fechaTimbrado, UUID, nombreXML, cancelado,regfiscalclave,periodicidadclave)
						VALUES
					( $idEmpleado, '$fechainicial', '$fechafinal', '$fechapago', $diaspago, '$tiponomina', $idnomp, $subtotal, $descuento, $total, $timbrado, '$selloSAT', '$selloCFD', '$fechaTimbrado', '$UUID', '$nombreXML', $cancelado,'$regfiscalclave','$periodicidadclave');
					"	;
			return $this->insert_id($sql);	
		}
		function almacenaConceptotimbrado($idEmpleado,$idNominatimbre,$idnomp,$idtipo,$claveconcepto,$importe,$gravado,$exento){
			if(!$exento){$exento=0;}
			if(!$gravado){$gravado=0;}
			$sql  = ("INSERT INTO nomi_prenomina ( idNominatimbre, idnomp, idtipo, idEmpleado, claveconcepto, importe,gravado,exento)
					VALUES
					($idNominatimbre, $idnomp, $idtipo, $idEmpleado, '$claveconcepto', $importe,$gravado,$exento);");
			if($this->query($sql)){
				return 1;
			}else{
				return 0;
			}
		}
		function updateConceptosTimbrados($idNominatimbre,$opc){
			if($opc==1){
				$sql = "UPDATE nomi_prenomina SET idNominatimbre=$idNominatimbre WHERE idNominatimbre=0;";
			}else{
				$sql = "DELETE FROM nomi_prenomina WHERE idNominatimbre=0;";
			}
			if($this->query($sql)){
				return 1;
			}else{
				return 0;
			}
		}
		function datosNominas($idNominatimbre){
			$sql = $this->query("select * from nomi_prenomina where idNominatimbre = ".$idNominatimbre);
			if($sql->num_rows>0){
				return $sql;
			}else{
				return 0;
			}
		}
		function datosNominaTimbrada($idNominatimbre){
			$sql = $this->query("select idEmpleado,tiponomina,subtotal,descuento,total,regfiscalclave,periodicidadclave from nomi_nominas_timbradas where idNominatimbre = ".$idNominatimbre);
			return $sql->fetch_object();
		}
		
	}

?>