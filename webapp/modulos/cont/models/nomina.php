<?php

class NominaModel extends CaptPolizasModel
{
	function cuentaNomina($tabla,$clave){
		$sql=$this->query("select p.account_id,c.description,p.clave from $tabla p, cont_accounts c where p.clave='$clave' and c.account_id=p.account_id");
		if($sql->num_rows>0){
			$cuenta = $sql->fetch_assoc();
			return $cuenta['clave']."//".$cuenta['description']."//".$cuenta['account_id'];
		}else{
			return '//ASIGNE CUENTA';
		}
	}
	function percepcionesDeducciones($tabla){
		$sql=$this->query("select * from $tabla");
		return $sql;
	}
	function cuentasConfig(){
		$sql=$this->query("select * from cont_config ");
		return $sql->fetch_assoc();
	}
	function pasivoCirculante(){//para las sueldos y deducc nomina
		$sql=$this->query("select * from cont_accounts where   account_code like '2.1%' and affectable=1 ");
		return $sql;
	}
	function existeEmpleado($rfc){
		$sql = $this->query("select * from nomi_empleados where rfc='$rfc'");
		if(!$sql->num_rows>0){
			//$this->query("insert into")
		}
	}
	function updateConceptoPoliza($concepto,$id){
		$sql = $this->query("update cont_polizas set concepto='$concepto' where id=".$id);
	}
	function empleadosRegistrados(){
		$sql = $this->query("select * from nomi_empleados");
		return $sql;
	}
	function datosEmpleado($id){
		$sql = $this->query("select * from nomi_empleados where idEmpleado=".$id);
		return $sql->fetch_assoc();
	}
}
?>