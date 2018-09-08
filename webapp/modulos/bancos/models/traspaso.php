<?php
require("models/connection_sqli_manual.php"); // funciones mySQLi

class TraspasoModel extends Connection{
	
	function documentos(){//documentos de egreso y cheque
		$sql = $this->query("select * from bco_documentos where (idDocumento=1 or idDocumento=5) and status=1");
		return $sql;
	}
	function cuentasbancariaslista(){
		$sql=$this->query("select c.*,b.nombre,cc.manual_code,cc.description from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id ");
		return $sql;
	}
	function infoDocumento($idDocumento){
		$sql = $this->query("select * from bco_documentos where id=".$idDocumento);
		return $sql->fetch_assoc();
	}
	function organizacion(){
		$sql = $this->query("select * from organizaciones");
		return $sql->fetch_assoc();
	}
	function editados($idDocumento){
		$sql = $this->query("select * from bco_documentos where status!=3 and  id=".$idDocumento);
		$array = $sql->fetch_assoc();
		return $array;	
	}
	function crearTraspaso($fecha,$folio,$importe,$referencia,$concepto,$idbancaria,$beneficiario,$idbenefeciario,$proceso,$clasificador,$idDocumento){
		$sql = "insert into bco_documentos (fecha,fechacreacion,folio,importe,referencia,concepto,idbancaria,beneficiario,idbeneficiario,status,impreso,proceso,idclasificador,idDocumento) values ('$fecha',NOW(),'$folio',$importe,'$referencia','$concepto',$idbancaria,$beneficiario,$idbenefeciario,1,2,$proceso,$clasificador,$idDocumento)";
		if($this->query($sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
?>