<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

	class Movimientos_aux_base_gravableModel extends Connection
	{

		function con_ejercicio($ejercicio=0){
			$filtro="";
			if($ejercicio>0){$filtro = "where id=".$ejercicio;}
			$qry_ej=$this->query("SELECT id, NombreEjercicio FROM cont_ejercicios ".$filtro." ORDER by id DESC");
			
			if($ejercicio>0){
				return $qry_ej->fetch_object();
			}
			else return $qry_ej;
			
		}
		function tasas_iva(){
			$qry_ej=$this->query("SELECT id, valor, tasa FROM cont_tasas");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
		}
		function tipo_operacion(){
			$qry_ej=$this->query("SELECT id, tipoOperacion FROM cont_tipo_operacion");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
		}
		function tipo_iva(){
			$qry_ej=$this->query("SELECT id, tipoiva FROM cont_tipo_iva");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
		}
		function organizacion(){
			$qry_ej=$this->query("SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}
		function cuenta_transladado(){
			$qry_ej=$this->query("SELECT manual_code,account_id,description FROM cont_accounts WHERE removed=0 AND status=1 AND affectable=1 AND account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0);");
				return $qry_ej;
		}

		function cuenta_acreditable(){
			$qry_ej=$this->query("SELECT manual_code,account_id,description FROM cont_accounts WHERE removed=0 AND status=1 AND affectable=1 AND account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0);");
				return $qry_ej;
		}

		function polizaCausado($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer){
			$filtro = "";
			if($ejer>0){$filtro .= " and desglose.ejercicioAcreditamiento = ".$ejer." and desglose.periodoAcreditamiento between ".$ini." and ".$fin;}
			else {$filtro .= " and pol.fecha between '".$ini."' and '".$fin."' ";}
			$miQuery="SELECT 
						pol.fecha,
						pol.id as num,
						pol.concepto,
						poliza.`titulo` as poliza,
						ej.NombreEjercicio,
						desglose.periodoAcreditamiento as idperiodo,
						desglose.tasa16,
						desglose.tasa11,
						desglose.tasa0,
						desglose.tasaExenta,
						desglose.otrasTasas,
						desglose.otros,
						desglose.ivaRetenido,
						desglose.isrRetenido,
						desglose.aplica
					FROM
						cont_polizas as pol
						inner join
							cont_rel_desglose_iva as desglose on pol.id=desglose.`idPoliza`
						inner join 
							cont_tipos_poliza as poliza on pol.`idtipopoliza` = poliza.id
						inner join 
							cont_movimientos as mov on mov.IdPoliza = pol.`id`
						inner join 
							cont_accounts as cuenta on cuenta.`account_id`=mov.`Cuenta`
						inner join 
							cont_ejercicios as ej on ej.id=pol.idejercicio
					WHERE
						cuenta.`account_id`=".$cuentaTrans."
						".$filtro."
					GROUP BY 
						pol.id;	";
			$qry_ej=$this->query($miQuery);
			return $qry_ej;
		}

		function poliza($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer){
			$filtro = "";
			if($ejer>0){
				$filtro .= " and polprov.`ejercicioAcreditamiento` = ".$ejer." and polprov.periodoAcreditamiento between ".$ini." and ".$fin;
			}
			else {
				$filtro .= " and pol.fecha between '".$ini."' and '".$fin."' ";
			}
			$miQuery="SELECT DISTINCT
						polprov.tasa,
						tasa.tasa,
						pol.fecha,
						polprov.idPoliza as num,
						poliza.`titulo` as poliza,
						pol.concepto,
						ej.NombreEjercicio,
						polprov.`periodoAcreditamiento` idperiodo, 
						SUM(polprov.importeBase) as base,
						SUM(polprov.importeBase*(tasa.valor/100)) as ivafiscal, 
						SUM(polprov.ivaPagadoNoAcreditable) as no_acreditable,
						SUM(polprov.importeBase*(1+(tasa.valor/100)-polprov.`ivaRetenido`-polprov.`isrRetenido`)) as total,
						SUM(polprov.`otrasErogaciones`) as otros,
						SUM(polprov.`ivaRetenido`) as ivaRetenido,
						SUM(polprov.`isrRetenido`) as isrRetenido
					FROM
						cont_polizas  as pol
						inner join
							cont_rel_pol_prov as polprov on pol.id=polprov.`idPoliza`
						inner join 
							cont_tasaPrv as tasa on polprov.tasa=tasa.id
						inner join 
							cont_tipos_poliza as poliza on poliza.`id`=pol.idtipopoliza
						inner join 
							cont_ejercicios as ej on ej.id=pol.idejercicio
						
					WHERE 
						polprov.importeBase>0
						and
						pol.id IN (SELECT mov.`IdPoliza` FROM cont_movimientos as mov WHERE mov.Cuenta=6)
						".$filtro."
					GROUP BY
						polprov.tasa,polprov.idPoliza
					ORDER BY
						tasa.tasa,pol.fecha;";
			$qry_ej=$this->query($miQuery);
			// $qry_ej=$this->query("SELECT 
			// 						tasa.tasa,
			// 						pol.fecha,
			// 						pol.id as num,
			// 						poliza.`titulo` as poliza,
			// 						pol.concepto,
			// 						ej.NombreEjercicio,
			// 						polprov.`periodoAcreditamiento` as idperiodo, 
			// 						SUM(polprov.importeBase) as base,
			// 						SUM(polprov.importeBase*(tasa.valor/100)) as ivafiscal, 
			// 						SUM(polprov.ivaPagadoNoAcreditable) as no_acreditable,
			// 						SUM(polprov.importeBase*(1+(tasa.valor/100))-polprov.`ivaRetenido`-polprov.`isrRetenido`) as total,
			// 						SUM(polprov.`otrasErogaciones`) as otros,
			// 						SUM(polprov.`ivaRetenido`) as ivaRetenido,
			// 						SUM(polprov.`isrRetenido`) as isrRetenido
			// 					FROM 
			// 						cont_polizas  as pol 
			// 						inner join cont_rel_pol_prov as polprov on pol.id=polprov.`idPoliza`
			// 						inner join cont_tasaPrv as tasa on polprov.tasa=tasa.id
			// 						inner join cont_movimientos as mov on mov.`IdPoliza`=pol.`id`
			// 						inner join cont_ejercicios as ej on ej.id=polprov.`ejercicioAcreditamiento`,
			// 						cont_tipos_poliza as poliza
			// 					WHERE 
			// 						polprov.importeBase>0 
			// 						".$filtro."
			// 						and 
			// 						mov.Cuenta=".$cuentaAcred."
			// 						and
			// 						poliza.`id`=pol.idtipopoliza
			// 					GROUP BY 
			// 						tasa.tasa,pol.fecha,pol.id
			// 					ORDER BY
			// 						tasa.tasa,pol.fecha,pol.id;");
			return $qry_ej;
		}
		
	}
?>