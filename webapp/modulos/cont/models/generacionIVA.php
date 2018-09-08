<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

	class generacionIVAModel extends Connection
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
		function tipo_operacion(){
			$qry_ej=$this->query("SELECT id, tipoOperacion FROM cont_tipo_operacion");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}

		function organizacion(){
			$qry_ej=$this->query("SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}
		function gastosGravados($ejer=0,$ini,$fin,$per=1){
			if($per==1){
				$filtro = " and pol.idejercicio = ".$ejer." and polprov.periodoAcreditamiento between ".$ini." and ".$fin." ";
			}
			if($per==0){
				$filtro = " and pol.fecha between '".$ini."' and '".$fin."'";
				echo $ini."' and '".$fin;
			}
			$qry_ej=$this->query("SELECT prv.tasa, SUM(polprov.importeBase) as base,SUM(polprov.importeBase*(prv.valor/100)) as iva,mrp.idtipoiva as tipoIva, polprov.`ivaPagadoNoAcreditable` as noAcred
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
									inner join mrp_proveedor as mrp on mrp.idPrv=polprov.idProveedor 
								WHERE
									polprov.importeBase>'0'
									and
									prv.valor>0
									and
									mrp.idtipoiva>0
									".$filtro."
									GROUP by mrp.idtipoiva,prv.tasa;");
			
				return $qry_ej;
		}
		
		
	}
?>
