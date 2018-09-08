<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

	class resumenGeneralR21Model extends Connection
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

		function tasas_iva(){
			$qry_ej=$this->query("SELECT * FROM cont_tasas");
				return $qry_ej;
		}

		function organizacion(){
			$qry_ej=$this->query("SELECT nombreorganizacion,RFC,logoempresa FROM organizaciones WHERE idorganizacion=1");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}

		function valorBase($ejer){
			$qry_ej=$this->query("SELECT 
									polprov.`periodoAcreditamiento`,
									prv.tasa, 
									SUM(polprov.importeBase) as base, 
									mrp.idtipotercero
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
									inner join mrp_proveedor as mrp on mrp.idPrv = polprov.idProveedor
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0 and mrp.idtipotercero!=-1 and
									polprov.ejercicioAcreditamiento = ".$ejer."
									and
									polprov.importeBase>'0'
									GROUP by polprov.`periodoAcreditamiento`,prv.tasa,mrp.idtipotercero;");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}

		function valorIva($ejer){
			$qry_ej=$this->query("SELECT 
									polprov.`periodoAcreditamiento`,
									prv.tasa, 
									SUM(polprov.importeBase*(prv.valor/100)) as iva, 
									mrp.idtipotercero
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
									inner join mrp_proveedor as mrp on mrp.idPrv = polprov.idProveedor
								WHERE
									polprov.ejercicioAcreditamiento = ".$ejer." and pol.activo=1 and pol.eliminado=0
									and
									polprov.importeBase>'0'
									and prv.valor>0
									GROUP by polprov.`periodoAcreditamiento`,prv.tasa,mrp.idtipotercero;");
			if(mysqli_num_rows($qry_ej)>0){
				return $qry_ej;
			}
			else return 0;
		}

		function valorIvaTerceros($ejer){
			$qry_ej=$this->query("SELECT 
									polprov.`periodoAcreditamiento`, 
									SUM(polprov.importeBase*(prv.valor/100)) as iva, 
									mrp.idtipotercero, mrp.idtipoiva as tipoiva
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
									inner join mrp_proveedor as mrp on mrp.idPrv = polprov.idProveedor
								WHERE
									polprov.ejercicioAcreditamiento = ".$ejer." and pol.activo=1 and pol.eliminado=0
									and
									polprov.importeBase > ".$ejer."
									and prv.valor>0
									GROUP by polprov.`periodoAcreditamiento`,mrp.idtipotercero ;");
			return $qry_ej;
		}

		function TotalBasePeriodo($ejer){
			$miQuery="SELECT 
						polprov.`periodoAcreditamiento` as periodo, 
						SUM(polprov.importeBase) as base, 
						SUM((polprov.importeBase*(prv.valor/100))-(polprov.`ivaPagadoNoAcreditable`/(prv.valor/100))) as base_acreditable,  
						SUM((polprov.importeBase*(prv.valor/100))-polprov.`ivaPagadoNoAcreditable`) as iva, 
						SUM(polprov.`ivaPagadoNoAcreditable`) as ivaNoAcreditable, 
						SUM(polprov.`ivaRetenido`) as retenido, '' as ivaAcreditable
					FROM 
						cont_polizas as pol
						inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
						inner join cont_tasaPrv prv on prv.id = polprov.tasa
						inner join mrp_proveedor as mrp on mrp.idPrv = polprov.idProveedor
					WHERE
						polprov.ejercicioAcreditamiento = ".$ejer." and pol.activo=1 and pol.eliminado=0
						and
						polprov.importeBase>'0'
						and
						polprov.`activo`=1
						GROUP by polprov.`periodoAcreditamiento`;";

			$qry_ej=$this->query($miQuery);
			return $qry_ej;	
		}

		function GastosInversiones($ejer){
			$miQuery="SELECT 
						polprov.`periodoAcreditamiento` as periodo, 
						SUM(polprov.importeBase) as base, 
						SUM(polprov.importeBase*(prv.valor/100)) as iva, 
						tipoIva.id as tipoIvaId
					FROM 
						cont_polizas as pol
						inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
						inner join cont_tasaPrv prv on prv.id = polprov.tasa
						inner join mrp_proveedor as mrp on mrp.idPrv = polprov.idProveedor
						inner join cont_tipo_iva as tipoIva on mrp.idtipoiva=tipoIva.id
					WHERE
						polprov.ejercicioAcreditamiento = ".$ejer." and pol.activo=1 and pol.eliminado=0
						and
						polprov.importeBase>'0'
						and
						polprov.`activo`=1
						GROUP by polprov.`periodoAcreditamiento`,tipoIva.id ;";

			$qry_ej=$this->query($miQuery);
			return $qry_ej;	
		}

		function cobrado($ejer){
			$miQuery = "SELECT 
							cobrado.* 
						FROM 
							cont_rel_desglose_iva as cobrado 
							inner join cont_polizas as polizas on polizas.id = cobrado.`idPoliza`
						WHERE polizas.`idejercicio`=1 and polizas.activo=1 and polizas.eliminado=0
						GROUP BY 
							cobrado.`periodoAcreditamiento`;";
			$qry = $this->query($miQuery);
			return $qry;
		}
		
		/*  new mio */
		function totalbaseiva($ejer,$tasa){
			if($tasa=='16%' || $tasa=='11%'){ $t="- (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))";}else{ $t="";}
			$sql=$this->query("SELECT SUM(polprov.importeBase) ".$t." as base,
									polprov.periodoAcreditamiento,
									prv.tasa
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and
									polprov.ejercicioAcreditamiento =".$ejer."
									and
									polprov.importeBase>'0' and
									prv.tasa = '".$tasa."'
									group by polprov.periodoAcreditamiento,prv.tasa");
			
			return $sql;
		}
		function totalbaseimport($ejer,$tasa){
			if($tasa=='16%' || $tasa=='11%'){ $t="- (sum(polprov.ivaPagadoNoAcreditable)/(prv.valor/100))";}else{ $t="";}
				$sql=$this->query("SELECT 
									SUM(polprov.importeBase) ".$t." as base,
									polprov.periodoAcreditamiento,
									prv.tasa

								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and
									(mrpprov.`idtipotercero` = 2 or mrpprov.`idtipotercero` = 6)
									and
									polprov.ejercicioAcreditamiento=".$ejer."
									and
									polprov.importeBase>'0'
									and
									prv.tasa = '".$tasa."'
									group by polprov.periodoAcreditamiento,prv.tasa");
			return $sql;
		}
		function totalTasaIvaAcr($ejer,$tasa,$tercero){
			$filtro_tercero="";
			if ($tercero==2){ $filtro_tercero="and (mrpprov.`idtipotercero` = 2 or mrpprov.`idtipotercero` = 6)";}
			
			$qry_ej=$this->query("SELECT 
									SUM((polprov.importeBase*(prv.valor/100))-polprov.ivaPagadoNoAcreditable) as IVA,
									polprov.periodoAcreditamiento,
									prv.tasa
								FROM 
									cont_polizas as pol
									inner join cont_rel_pol_prov as polprov on pol.id = polprov.idPoliza 
									inner join mrp_proveedor as mrpprov on mrpprov.idPrv = polprov.idProveedor
									inner join cont_tasaPrv prv on prv.id = polprov.tasa
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0
									and
									polprov.ejercicioAcreditamiento=".$ejer."
									and
									polprov.importeBase>'0' ".$filtro_tercero."
									and 
									prv.tasa='".$tasa."'
								group by polprov.periodoAcreditamiento,prv.tasa
									");
			return $qry_ej;

		}
		function ivaXTipo($ejer){//
			$qry_ej=$this->query("SELECT 
									SUM((polprov.`importeBase` * (tasaprv.valor/100))- polprov.ivaPagadoNoAcreditable) as iva,
									mrpprov.`idtipoiva`,
									mrpprov.`idtipotercero` as tipoTercero,
									polprov.periodoAcreditamiento
								FROM 
									cont_rel_pol_prov as polprov
									inner join mrp_proveedor as mrpprov on polprov.`idProveedor`=mrpprov.`idPrv`
									inner join cont_tasaPrv as tasaprv on polprov.`tasa`=tasaprv.`id`
									inner join cont_tipo_iva as tipoIva	on tipoIva.id=mrpprov.`idtipoiva`	
									inner join cont_polizas as pol on pol.id=polprov.idPoliza
									inner join cont_tipo_tercero as tercero on tercero.id=mrpprov.`idtipotercero`
								WHERE
									polprov.activo=1 and polprov.aplica=1 and pol.activo=1 and pol.eliminado=0 and 
									polprov.ejercicioAcreditamiento=".$ejer."
								GROUP BY
								polprov.periodoAcreditamiento,
									mrpprov.`idtipotercero`,
									mrpprov.`idtipoiva`;");
			
			
			return $qry_ej;
		}
		
		function causado($ejer,$filtro){
			
			$t="";$tasa="";
			if($filtro=="tasa16"){ $t="desglose.tasa16";  }
			if($filtro=="tasa11"){ $t="desglose.tasa11";  }
			if($filtro=="tasa0"){ $t="desglose.tasa0";  }
			if($filtro=="tasaExenta"){ $t="desglose.tasaExenta";  }
			if($filtro=="otrasTasas"){ $t="desglose.otrasTasas";  } //preguntar si s etoman en cuenta otras tasas en la causacion
			
			$sql=$this->query("SELECT ".$t.",desglose.periodoAcreditamiento
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
						where  desglose.aplica=1 and pol.activo=1 and pol.eliminado=0 and desglose.ejercicioAcreditamiento=".$ejer." 
					GROUP BY 
						pol.id;
						");
			
			return $sql;
		}
		
		function resumenivas($ejer){
			$sql=$this->query("select * from cont_resumen_ivas_retenidos
					where idejercicio=".$ejer);
			
			return $sql;
		}
		
	}
?>