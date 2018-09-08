<?php
require("models/connection_sqli_manual.php"); 
class ConfiguracionModel extends Connection{
	
	function validaAcontia(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=142");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	function configuracionAcontia(){
		$sql = $this->query("select * from cont_config;");
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
	}	
	function configuracionBancos(){
		$sql = $this->query("select * from bco_configuracion;");
		return $sql;
	}
	function ejercicioAcontia(){
		$sql = $this->query("select * from cont_ejercicios;");
		return $sql;
	}
	function ejercicioBancos(){
		$sql = $this->query("select * from bco_ejercicios;");
		return $sql;
	}
	function insertConf($rfc,$periodosAbiertos,$periodoActual,$ejercicioActual,$polizaAuto,$acontiaConf){
		$sql = "INSERT INTO `bco_configuracion` 
		(`RFC`, `PeriodosAbiertos`, `PeriodoActual`, `EjercicioActual`, `PolizaAuto`, `AcontiaConf`)
		VALUES ('$rfc', $periodosAbiertos,$periodoActual,$ejercicioActual,$polizaAuto, $acontiaConf); ";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function insertaEjercicio($ejercicio){
		$sql = "INSERT INTO `bco_ejercicios` (`NombreEjercicio`, `Cerrado`)VALUES($ejercicio, 0),(".($ejercicio+1).", 0);";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function passAdmin($Pass)
	{
		include('../../netwarelog/webconfig.php');
		$strPwd = crypt($Pass,$accelog_salt);

		$strResult = 0;

		$strSql = "SELECT * FROM accelog_usuarios WHERE idempleado = 2 AND clave = '" . $strPwd . "';";
		$rstPwd = $this->query($strSql);
		if($rstPwd->num_rows>0)
		{
			$strResult = 1;
		}
		return $strResult;
	}
	function reiniciar(){
		$sql = "
		truncate bco_configuracion;
		truncate bco_ejercicios;
		truncate bco_documentos;
		truncate bco_saldos_conciliacionBancos;
		truncate bco_saldo_bancario;
		truncate bco_documentoSubcategorias;
		truncate bco_cuentas_bancarias;
		truncate bco_sucursalBancaria;
		truncate bco_tiposDocumentoConcepto;
		truncate bco_devoluciones;
		truncate bco_conceptos;
		truncate bco_clasificador;
		truncate bco_ingresos_depositos";
		if($this->multi_query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function updateConfiguracion($rfc,$periodoActual,$polizaAuto,$periodosAbiertos){
		$sql = "update bco_configuracion set RFC='$rfc', PeriodoActual= $periodoActual, PeriodosAbiertos = $periodosAbiertos, PolizaAuto=$polizaAuto";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function updatepolizaAuto($polizaAuto){
		$sql = "update bco_configuracion set PolizaAuto=$polizaAuto";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
}
?>