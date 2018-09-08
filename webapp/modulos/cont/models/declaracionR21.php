<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

	class declaracionR21Model extends Connection
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
		function tasas_iva(){
			$qry_ej=$this->query("SELECT * FROM cont_tasas");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}
		function totalBaseIva($tasa,$per_ini,$ejer,$noacreditable){
			if($tasa=='16%' || $tasa=='11%'){ $t="- (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))";}else{ $t="";}
			if($noacreditable == 1){ $t="+ (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))" ; }
			$qry_ej=$this->query("SELECT SUM(polprov.importeBase) ".$t." as base 
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and 
									polprov.periodoAcreditamiento = ".$per_ini."
									and
									polprov.ejercicioAcreditamiento = ".$ejer."
									and
									polprov.importeBase>'0'
									and
									prv.tasa = '".$tasa."';");
			return $qry_ej;
			
		}
		function totalBaseIva2($tasa,$per_ini,$ejer){
			$qry_ej=$this->query("SELECT SUM(polprov.importeBase) as base 
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and 
									polprov.periodoAcreditamiento = ".$per_ini."
									and
									polprov.ejercicioAcreditamiento = ".$ejer."
									and
									polprov.importeBase>'0'
									and
									prv.tasa = '".$tasa."';");
			return $qry_ej;
			
		}
		//bases
		function totalBaseIvaImpor($tasa,$per_ini,$ejer,$tipoIva){//nose usa
			$filtro="";
			if($tipoIva>0){$filtro=" and mrpprov.idtipoiva=".$tipoIva;}
			if($tasa=='16%' || $tasa=='11%'){ $t="- (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))";}else{ $t="";}
			// if($noacreditable == 1){ $t="+ (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))" ; }
			
			$qry_ej=$this->query("SELECT 
									SUM(polprov.importeBase) ".$t." as base
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and 
									polprov.periodoAcreditamiento = ".$per_ini."
									and
									(mrpprov.`idtipotercero` = 2 or mrpprov.`idtipotercero` = 6)
									and
									polprov.ejercicioAcreditamiento=".$ejer."
									and
									polprov.importeBase>'0'
									".$filtro."
									and
									prv.tasa = '".$tasa."';");
			$res = $qry_ej->fetch_object();
			return $res;			
		}
		function totalBaseIvaImpor2($tasa,$per_ini,$ejer,$tipoIva){//nose usa
			$filtro="";
			if($tipoIva>0){$filtro=" and mrpprov.idtipoiva=".$tipoIva;}
			if($tasa=='16%' || $tasa=='11%'){ $t="- (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))";}else{ $t="";}
			// if($noacreditable == 1){ $t="+ (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))" ; }
			
			$qry_ej=$this->query("SELECT 
									(SUM(polprov.importeBase))/(prv.valor/100) as base
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and 
									polprov.periodoAcreditamiento = ".$per_ini."
									and
									(mrpprov.`idtipotercero` = 2 or mrpprov.`idtipotercero` = 6)
									and
									polprov.ejercicioAcreditamiento=".$ejer."
									and
									polprov.importeBase>'0'
									".$filtro."
									and
									prv.tasa = '".$tasa."';");
			$res = $qry_ej->fetch_object();
			return $res;			
		}
		function totalTasaIvaAcr($tasa,$per_ini,$ejer,$tercero){
			$filtro_tercero="";
			// if($tercero==1){$filtro_tercero="and (mrpprov.`idtipotercero` = 2 or mrpprov.`idtipotercero` = 6)";}
			 //if($tercero==1){$filtro_tercero="and mrpprov.`idtipoperacion` in(1,2)";}
			//else  if($tercero==0){$filtro_tercero="and mrpprov.`idtipoperacion` not in(1,2)";}
		 if ($tercero==2){ $filtro_tercero="and (mrpprov.`idtipotercero` = 2 or mrpprov.`idtipotercero` = 6)";}
			$qry_ej=$this->query("SELECT 
									SUM((polprov.importeBase*(prv.valor/100)) - polprov.ivaPagadoNoAcreditable) as IVA
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza 
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and polprov.periodoAcreditamiento = ".$per_ini." 
									and
									polprov.ejercicioAcreditamiento=".$ejer."
									and
									polprov.importeBase>'0'
									".$filtro_tercero."
									and 
									prv.tasa='".$tasa."';");
			$res = $qry_ej->fetch_object();
			return $res;
		}
		function totalTasaIvaAcr2($tasa,$per_ini,$ejer,$tercero){
			$filtro_tercero="";
			if ($tercero==2){ $filtro_tercero="and (mrpprov.`idtipotercero` = 2 or mrpprov.`idtipotercero` = 6)";}
			$qry_ej=$this->query("SELECT 
									(SUM(polprov.importeBase))*(prv.valor/100) as IVA
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza 
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and polprov.periodoAcreditamiento = ".$per_ini." 
									and
									polprov.ejercicioAcreditamiento=".$ejer."
									and
									polprov.importeBase>'0'
									".$filtro_tercero."
									and 
									prv.tasa='".$tasa."';");
			$res = $qry_ej->fetch_object();
			return $res;
		}
		function totalIvaAcr($per_ini,$ejer){//and polprov.periodoAcreditamiento =2 tenia eso O.o
			$qry_ej=$this->query("SELECT 
									SUM((polprov.importeBase*(prv.valor/100))-polprov.ivaPagadoNoAcreditable) as IVA,
									SUM(polprov.`ivaRetenido`) as ivaRetenido
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza 
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and 
									polprov.periodoAcreditamiento = ".$per_ini."
									and
									polprov.ejercicioAcreditamiento=".$ejer."
									and
									polprov.importeBase>'0';");
			return $qry_ej;
		}

		function ivaXTipo($ejer,$periodo,$noacreditable){//agrege 
			if($noacreditable==0){ $t="-polprov.ivaPagadoNoAcreditable";}else{ $t="";}
			$qry_ej=$this->query("SELECT 
									SUM((polprov.`importeBase` * (tasaprv.valor/100)) ".$t.") as iva,
									mrpprov.`idtipoiva`,
									mrpprov.`idtipotercero` as tipoTercero
								FROM 
									cont_rel_pol_prov as polprov
									inner join mrp_proveedor as mrpprov on polprov.`idProveedor`=mrpprov.`idPrv`
									inner join cont_tasaPrv as tasaprv on polprov.`tasa`=tasaprv.`id`
									inner join cont_tipo_iva as tipoIva	on tipoIva.id=mrpprov.`idtipoiva`	
									inner join cont_polizas as pol on pol.id=polprov.idPoliza
									inner join cont_tipo_tercero as tercero on tercero.id=mrpprov.`idtipotercero`
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and polprov.periodoAcreditamiento =".$periodo." and 
									polprov.ejercicioAcreditamiento=".$ejer."
								GROUP BY
									mrpprov.`idtipotercero`,
									mrpprov.`idtipoiva`;");
			
			
			return $qry_ej;
		}
		
		function causado($per_ini,$ejer,$filtro,$num){
			
			$t="";$tasa="";
			if($filtro=="16"){ $t="desglose.tasa16"; $tasa="tasa16"; }
			if($filtro=="11"){ $t="desglose.tasa11"; $tasa="tasa11"; }
			if($filtro=="0"){ $t="desglose.tasa0"; $tasa="tasa0"; }
			if($filtro=="ex"){ $t="desglose.tasaExenta"; $tasa="tasaExenta"; }
			 if($filtro=="otra"){ $t="desglose.otrasTasas"; $tasa="otrasTasas"; } //preguntar si s etoman en cuenta otras tasas en la causacion
			// if($filtro=="rete"){ $t="desglose.ivaRetenido"; $tasa="ivaRetenido"; }
			// if($filtro=="isr"){ $t="desglose.isrRetenido"; $tasa="isrRetenido"; }
			
						//ingresos
						/* 1.-importe total
						 * 2.-importe antes d iva
						 * 3.-importe del iva
						 * 4.-iva pagado no acreditable*/
			$sql=$this->query("SELECT ".$t."
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
						where  desglose.aplica=1 and pol.activo=1 and pol.eliminado=0 and desglose.ejercicioAcreditamiento=".$ejer." and desglose.periodoAcreditamiento=".$per_ini."
					GROUP BY 
						pol.id;
						");
						$tasare=0;
			while($row=$sql->fetch_array()){
				$ta=explode('-',$row[$tasa]);
				$tasare+=$ta[$num];
			}
			return $tasare;
		}
//me quede con dudas de eeste si esta bien lo del retenido
		function retenidomes($peri,$ejer){
			$qry_ej=$this->query("SELECT sum(desglose.`ivaRetenido`) as retenido
						FROM cont_rel_desglose_iva as desglose,cont_polizas as pol						
						where  desglose.aplica=1 and pol.activo=1 and pol.eliminado=0 and desglose.idPoliza=pol.id and desglose.ejercicioAcreditamiento=".$ejer." and desglose.periodoAcreditamiento=".($peri));
			$res = $qry_ej->fetch_object();
			return $res;		
		}
		function pagadonpacredita($peri,$ejer){
			$sql=$this->query("select sum(rp.ivaPagadoNoAcreditable) as noacredita 
						from cont_rel_pol_prov rp,cont_polizas pol
						where rp.aplica=1 and rp.activo=1 and pol.activo=1 and pol.eliminado=0 and pol.id=rp.idPoliza and rp.periodoAcreditamiento=".$peri." and rp.ejercicioAcreditamiento=".$ejer);
			$res = $sql->fetch_object();
			return $res;
			}
		
		function retenidoiv($peri,$ejer){//retenido provee
			$sql=$this->query("select sum(rp.ivaRetenido) as noacredita 
						from cont_rel_pol_prov rp,cont_polizas pol
						where rp.aplica=1 and rp.activo=1 and pol.activo=1 and pol.eliminado=0 and pol.id=rp.idPoliza and rp.periodoAcreditamiento=".$peri." and rp.ejercicioAcreditamiento=".$ejer);
			$res = $sql->fetch_object();
			return $res;
		}
		function cargomesanterior($peri,$ejer){//este se comvertira en favor en el mes actual
			$sql=$this->query("select cargo from cont_resumen_ivas_retenidos where mes=".$peri." and idejercicio=".$ejer);
			$res = $sql->fetch_object();
			return $res;
		}
		
		
		
	}
?>