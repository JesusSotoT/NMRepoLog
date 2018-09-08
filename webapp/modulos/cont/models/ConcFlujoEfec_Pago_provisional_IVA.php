<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

	class ConcFlujoEfec_Pago_provisional_IVAModel extends Connection
	{
		function organizacion(){
			$qry_ej=$this->query("SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}

		function cuentas($cuenta_Ini=0,$cuenta_Fin=0){
			$filtro="";
			if($cuenta_Ini>0){
				$filtro.=" and cuentas.account_id between ".$cuenta_Ini." and ".$cuenta_Fin;
			}
			 // if($fecha_ini!=""){
				 // $filtro2=" and pol.fecha between '".$fecha_ini."' and '".$fecha_fin."'";
			// // }else{
				// $filtro2="";
			// }
			$miqry = "SELECT 
						cuentas.`account_id`, cuentas.`description`, cuentas.manual_code
					FROM 
						cont_accounts as cuentas,
						cont_config as config
						
					WHERE
						cuentas.removed=0 
						AND cuentas.status=1 
						AND cuentas.affectable=1 
						and cuentas.main_father = config.`CuentaFlujoEfectivo` 
						and cuentas.account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0) 
						".$filtro."
					ORDER BY 
						cuentas.`account_id`;";

			$qry_ej=$this->query($miqry);
			return $qry_ej;
		}

		

		function poliza($fecha_ini,$fecha_fin,$cuenta_Ini=0,$cuenta_Fin=0,$aplica){//$cualCuenta,$cuentaIni,$cuentaFin,$ini,$fin
			$filtro="";
			if($fecha_ini!=""){
				$filtro.=" and pol.fecha between '".$fecha_ini."' and '".$fecha_fin."'";
			}
			if($cuenta_Ini>0){
				$filtro .= " and cuentas.account_id between  ".$cuenta_Ini." and ".$cuenta_Fin;
			}if($aplica>0){
				$filtro.=" and polprov.aplica=1";
			}
			$miQry = "SELECT 
						cuentas.`account_id`,
						cuentas.`description`,
						cuentas.manual_code,
						pol.fecha,
						pol.id as num,
						poliza.`titulo` as tipo,
						pol.concepto,
						SUM(polprov.importeBase*(1+(tasa.valor/100))-polprov.`ivaRetenido`-polprov.`isrRetenido`) as total,
						SUM(polprov.importeBase) as base,
						SUM(polprov.importeBase*(tasa.valor/100)) as ivafiscal, 
						SUM(polprov.ivaPagadoNoAcreditable) as no_acreditable,
						polprov.`otrasErogaciones`
					FROM 
						cont_accounts as cuentas 
						inner join 
							cont_movimientos as mov on mov.Cuenta=cuentas.`account_id` 
						inner join 
							cont_polizas as pol on mov.`IdPoliza`=pol.`id`
						inner join 
							cont_rel_pol_prov as polprov on pol.id=polprov.`idPoliza`
						inner join 
							cont_tasaPrv as tasa on polprov.tasa=tasa.id
						inner join 
							mrp_proveedor as mrp on mrp.idPrv=polprov.`idProveedor`
						inner join 
							cont_accounts as cuenta on cuenta.`account_id`=mrp.`Cuenta`
						inner join 
							cont_ejercicios as ej on ej.id=pol.idejercicio,
						cont_tipos_poliza as poliza,
						cont_config as config
						
					WHERE
						cuentas.removed=0 
						AND cuentas.status=1 
						AND cuentas.affectable=1 
						and cuentas.main_father = config.`CuentaFlujoEfectivo` 
						and cuentas.account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0) 
						and importeBase>0 
						and poliza.`id`=pol.idtipopoliza 
						and polprov.activo=1
						".$filtro."
						
					GROUP BY 
						cuentas.`account_id`,pol.id;";

			$qry_ej=$this->query($miQry);

			return $qry_ej;
		}
		
		
	}
?>
