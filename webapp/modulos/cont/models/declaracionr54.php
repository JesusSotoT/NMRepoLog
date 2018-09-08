<?php
    require("models/connection_sqli_manual.php"); // funciones mySQLi

	class Declaracionr54Model extends Connection
	{
		function ejercicio(){
			$eje='select * from cont_ejercicios';
			$ejercicios = $this->query($eje);
			return $ejercicios;
		}
		
		function mesesanteriores($ejer,$peri,$opc){
			$periodo="";
			if($opc == 1){
				$periodo = "and  periodoAcreditamiento<".$peri;
			}else{
				$periodo = "and  periodoAcreditamiento=".$peri;
			}
			
			$sql = $this->query("
			select sum(d.acreditableIETU) ietu 
			from cont_rel_desglose_iva d,cont_polizas p 
			where d.ejercicioAcreditamiento=".$ejer." ".$periodo." and p.id=d.idPoliza and p.activo=1
			and d.aplica=1 and d.conceptoIETU=2");
			if($sql->num_rows>0){
				if($suma = $sql->fetch_array()){
					return $suma['ietu'];
				}
			}else{
				return 0;
			}
			
			
		}
		function impexentos($ejer,$peri){
			$sql = $this->query("select sum(d.acreditableIETU) ietu 
			from cont_rel_desglose_iva d,cont_polizas p 
			where d.ejercicioAcreditamiento=".$ejer." and p.id=d.idPoliza and p.activo=1
			and d.periodoAcreditamiento=".$peri." and d.aplica=1 and d.conceptoIETU between 3 and 7");
			if($sql->num_rows>0){
				if($suma = $sql->fetch_array()){
					return $suma['ietu'];
				}
			}else{
				return 0;
			}
		}
		function deduccionesautorizadas($ejer,$peri,$opc){
			$periodo="";
			if($opc == 1){
				$periodo = "and r.periodoAcreditamiento<".$peri;
			}else{
				$periodo = "and r.periodoAcreditamiento=".$peri;
			}
			
			$sql = $this->query("select sum(r.acreditableIETU) impbase from cont_rel_pol_prov r,cont_polizas p 
			where r.ejercicioAcreditamiento=".$ejer." ".$periodo." and 
			r.aplica=1 and r.activo=1 and r.idietu between 8 and 22 and p.id=r.idPoliza and p.idtipopoliza!=3");
			if($sql->num_rows>0){
				if($suma = $sql->fetch_array()){
					return $suma['impbase'];
				}
			}else{
				return 0;
			}
		}
		function acreditamientos($ejer,$peri,$id){//se cambio el periodos atras y estaba solo el actual
			$sql = $this->query("select sum(d.acreditableIETU) ietu 
			from cont_rel_pol_prov d,cont_polizas p 
			where d.ejercicioAcreditamiento=".$ejer." and p.id=d.idPoliza and p.activo=1
			and d.periodoAcreditamiento<=".$peri." and d.aplica=1 and d.idietu=".$id);
			if($sql->num_rows>0){
				if($suma = $sql->fetch_array()){
					return $suma['ietu'];
				}
			}else{
				return 0;
			}
		}
		function ejercicioactual(){
			$sql = $this->query("select * from cont_config ");
			if($suma = $sql->fetch_object()){
				return $suma;
			}		
		}
		function organizacion(){
			$qry_ej=$this->query("SELECT * FROM organizaciones WHERE idorganizacion=1");
			if(mysqli_num_rows($qry_ej)>0){
				$res=$qry_ej->fetch_object();
				return $res;
			}
			else return 0;
		}
		function ingresosietu($ejer,$peri,$ietu,$opc){
			if($opc == 1){//del periodo
				$filtro = "and periodoAcreditamiento=".$peri;
			}else{
				$filtro = "and periodoAcreditamiento<".$peri;
			}
			
			$sql = $this->query("select sum(d.acreditableIETU) ietu 
			from cont_rel_desglose_iva d,cont_polizas p 
			where d.ejercicioAcreditamiento=".$ejer." and p.id=d.idPoliza and p.activo=1
			$filtro and d.aplica=1 and d.conceptoIETU=".$ietu);
			if($sql->num_rows>0){
				$row = $sql->fetch_array();
				return $row['ietu'];
			}else{
				return 0;
			}
		}
		function deduccionesautorizadasdetalle($ejer,$peri,$ietu,$opc){
			$periodo="";
			if($opc == 2){
				$periodo = "and r.periodoAcreditamiento<".$peri;
			}else{
				$periodo = "and r.periodoAcreditamiento=".$peri;
			}
			
			$sql = $this->query("select sum(r.acreditableIETU) impbase from cont_rel_pol_prov r,cont_polizas p 
			where r.ejercicioAcreditamiento=".$ejer." ".$periodo." and 
			r.aplica=1 and r.activo=1 and r.idietu=".$ietu." and p.id=r.idPoliza and p.idtipopoliza!=3");
			if($sql->num_rows>0){
				if($suma = $sql->fetch_array()){
					return $suma['impbase'];
				}
			}else{
				return 0;
			}
		}
		
		
		
		
		
		
		
		
		
		
	}
?>