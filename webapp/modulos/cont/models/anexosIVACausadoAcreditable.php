<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

	class anexosIVACausadoAcreditableModel extends Connection
	{
		function con_ejercicio($ejercicio=0){
			$filtro="";
			if($ejercicio!=0){$filtro = "where id=".$ejercicio;}
			$qry_ej=$this->query("SELECT id, NombreEjercicio FROM cont_ejercicios ".$filtro." ORDER by id DESC");
			if(mysqli_num_rows($qry_ej)>0){
				
				if($ejercicio!=0){return $qry_ej->fetch_object();}
				else return $qry_ej;
			}
			else return 0;
		}
		function organizacion(){
			$qry_ej=$this->query("SELECT nombreorganizacion,logoempresa FROM organizaciones WHERE idorganizacion=1");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}
		//////////
		function desglose($ejercicio,$pinicio,$pfinal,$per){
			if($per==1){
				$filtro="and d.ejercicioAcreditamiento=".$ejercicio." and d.periodoAcreditamiento BETWEEN ".$pinicio." and ".$pfinal;
			}
			if($per==0){
				$filtro="and p.fecha BETWEEN ".$pinicio." and ".$pfinal;
			}
			$ejer=$this->query("select d.* from cont_rel_desglose_iva d,cont_polizas p
			where p.id=d.`idPoliza` AND p.activo=1 AND d.aplica=1 ".$filtro);
			return $ejer;
		}
		/////////
		
		function valorBase($ejer=0,$ini,$fin,$per){
			if($per==1){
				$filtro = "pol.idejercicio = ".$ejer." and polprov.periodoAcreditamiento between ".$ini." and ".$fin." ";
			}
			if($per==0){
				$filtro = "pol.fecha between '".$ini."' and '".$fin."'";
			}
			$qry_ej=$this->query("SELECT prv.tasa, SUM(polprov.importeBase) as base,sum(polprov.ivaRetenido) retenido
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									".$filtro."
									and
									polprov.importeBase>'0'
									AND pol.activo AND polprov.aplica=1 AND polprov.activo=1
									GROUP by prv.tasa;");
			
				return $qry_ej;
		}
		function valorIva($ejer=0,$ini,$fin,$per){
			if($per==1){
				$filtro = "pol.idejercicio = ".$ejer." and polprov.periodoAcreditamiento between ".$ini." and ".$fin." ";
			}
			if($per==0){
				$filtro = "pol.fecha between '".$ini."' and '".$fin."'";
			}
			$qry_ej=$this->query("SELECT prv.tasa, SUM(polprov.importeBase*(prv.valor/100)) as iva
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									".$filtro."
									and
									polprov.importeBase>'0'
									AND pol.activo=1 
									AND polprov.activo=1 
									AND polprov.aplica=1  
									and prv.valor>0
									GROUP by prv.tasa;");
			
				return $qry_ej;
		}
		function pagadoacredita($ejer=0,$ini,$fin,$per){
			if($per==1){
				$filtro=" polprov.ejercicioAcreditamiento=".$ejer." and polprov.periodoAcreditamiento BETWEEN ".$ini." and ".$fin;
			}
			if($per==0){
				$filtro="pol.fecha BETWEEN ".$ini." and ".$fin;
			}
			$ejer=$this->query("SELECT 
						prv.tasa,
						sum(polprov.ivaRetenido) retenido,
						polprov.ivaPagadoNoAcreditable,
						SUM((polprov.importebase*(prv.valor/100))-ivaPagadoNoAcreditable)/(prv.valor/100) as baseacr,
						SUM(polprov.importebase*(prv.valor/100)-ivaPagadoNoAcreditable) as ivacredit
					FROM 
						cont_polizas as pol
						inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
						inner join cont_tasaPrv prv on prv.id = polprov.tasa
					WHERE
					   ".$filtro." 
						and
						polprov.importeBase>'0'
						AND pol.activo=1
						AND polprov.activo=1 
						AND polprov.aplica=1 
						group by prv.tasa; ");
			return $ejer;
		}
		function mesanteriores($ejer=0,$ini,$fin,$meses){
			if($meses==1){
				$filtro=" polprov.ejercicioAcreditamiento=".$ejer." and polprov.periodoAcreditamiento<".$ini;
			}if($meses==0){
				$filtro="pol.fecha<".$ini;
			}
			$ejer=$this->query("SELECT 
						sum(polprov.ivaRetenido) mes
						FROM 
						cont_polizas as pol
						inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
						inner join cont_tasaPrv prv on prv.id = polprov.tasa
						WHERE
						".$filtro."
						and
						polprov.importeBase>'0'
						AND pol.activo=1
						AND polprov.activo=1 
						AND polprov.aplica=1 
						group by prv.tasa; ");
			return $ejer;	
		}
			
	}
?>