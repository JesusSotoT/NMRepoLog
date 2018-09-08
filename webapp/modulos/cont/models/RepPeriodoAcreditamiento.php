<?php
  require("models/connection_sqli.php"); // funciones mySQLi

	class RepPeriodoAcreditamientoModel extends Connection
	{
		function cargadatos($ejercicio,$delperiodo,$alperiodo,$proveedores,$aplica){
			$var=strrpos($proveedores,'and');
				if($var!==false){
			if($proveedores!=''){ $prove="and crp.idProveedor BETWEEN ".$proveedores;}else{ $prove='';}
				}else{ if($proveedores!=''){ $prove="and crp.idProveedor= ".$proveedores;}else{ $prove='';} }
			if($aplica==1){
				$apli=' crp.aplica=1 and';
			}else{
				$apli='';
			}
			
			$dato ='SELECT 
			mp.razon_social,
			cp.fecha,
			ctp.titulo,
			cp.id,
			cp.concepto,
			ce.NombreEjercicio,
			cp.idperiodo,
			crp.importe,
			tp.`valor` tasa,
			crp.idProveedor
                 from 
            cont_rel_pol_prov crp,
            cont_polizas cp,
            cont_tipos_poliza ctp,
            cont_ejercicios ce,
            mrp_proveedor mp,
            cont_tasaPrv tp
				where '.$apli.'
			crp.activo=1 
			and cp.activo=1
			and mp.idPrv=crp.idProveedor 
			and cp.id=crp.idPoliza 
			and ctp.id=cp.idtipopoliza 
			and ce.Id=cp.idejercicio
			and tp.id=crp.`tasa`
			'.$prove.' and cp.idejercicio='.$ejercicio.' and crp.periodoAcreditamiento BETWEEN '.$delperiodo.' and '.$alperiodo.' order by mp.razon_social';
		$ejercicios = $this->query($dato);
			if($ejercicios->num_rows>0){
				return $ejercicios;
			}else{
				return '0';
			}
			
		}
//-----------------------sin considerar aceditamiento -----------------------//
function cargadatos2($ejercicio,$inicio,$fin,$proveedores,$aplica){
			$var=strrpos($proveedores,'and');
				if($var!==false){
			if($proveedores!=''){ $prove="and crp.idProveedor BETWEEN ".$proveedores;}else{ $prove='';}
				}else{ if($proveedores!=''){ $prove="and crp.idProveedor= ".$proveedores;}else{ $prove='';} }
			if($aplica==1){
				$apli=' crp.aplica=1 and';
			}else{
				$apli='';
			}
			$dato ='SELECT 
			mp.razon_social,
			cp.fecha,
			ctp.titulo,
			cp.id,
			cp.concepto,
			ce.NombreEjercicio,
			cp.idperiodo,
			crp.importe,
			crp.tasa,
			crp.idProveedor
                 from 
            cont_rel_pol_prov crp,
            cont_polizas cp,
            cont_tipos_poliza ctp,
            cont_ejercicios ce,
            mrp_proveedor mp
				where 
			'.$apli.'
			crp.activo=1 
			and cp.activo=1
			and mp.idPrv=crp.idProveedor 
			and cp.id=crp.idPoliza 
			and ctp.id=cp.idtipopoliza 
			and ce.Id=cp.idejercicio
			'.$prove.'  AND cp.fecha BETWEEN "'.$inicio.'" and "'.$fin.'" order by mp.razon_social';
		$ejercicios = $this->query($dato);
			if($ejercicios->num_rows>0){
				return $ejercicios;
			}else{
				return '0';
			}
			
		}
//--------------------------fin----------------------------------------------//
		function ejercicio(){
			$eje='select * from cont_ejercicios';
			$ejercicios = $this->query($eje);
			return $ejercicios;
		}
		function ejerciciobusca($id){
			$eje='select NombreEjercicio from cont_ejercicios  where Id='.$id;
			$ejercicios = $this->query($eje);
			return $ejercicios;
		}
		
		function detalladomo($idpoliza,$idProveedor){//debe traer los movomeintos de al poiliza
			$eje='SELECT ';
			if(intval($idProveedor))
						{
							$eje .= "mp.razon_social,";
						}
						$eje .='

						cp.fecha,
						ctp.titulo,
						cp.id,
						cp.numpol,
						cp.concepto,
						ce.NombreEjercicio,
						cp.idperiodo,
						cm.importe,
						cm.Referencia,
						cm.Cuenta,
						ca.description,
						ca.manual_code,
						cm.TipoMovto
						
					FROM
						cont_polizas cp,
						cont_tipos_poliza ctp,
						cont_ejercicios ce, ';
						if(intval($idProveedor))
						{
							$eje .= " mrp_proveedor mp,";
						}

						$eje .= '
						cont_movimientos cm,
						cont_accounts ca
					WHERE
						 cm.Activo=1
						and cp.activo=1
						and ca.account_id=cm.Cuenta
 						and cp.id = cm.idPoliza 
						AND ctp.id = cp.idtipopoliza
						AND ce.Id = cp.idejercicio
						and cm.idPoliza='.$idpoliza;

						if(intval($idProveedor))
						{
							$eje .= " and mp.idPrv =".$idProveedor;
						}
			// $eje='SELECT
						// mp.razon_social,
						// cp.fecha,
						// ctp.titulo,
						// cp.id,
						// cp.concepto,
						// ce.NombreEjercicio,
						// cp.idperiodo,
						// cm.importe,
						// tp.valor,
						// crp.idProveedor,
					 	// crp.otrasErogaciones,
						// crp.ivaRetenido,
						// crp.isrRetenido,
						// crp.ivaPagadoNoAcreditable,
						// cm.Referencia,
						// cm.Cuenta,
						// ca.description,
						// ca.manual_code,
						// cm.TipoMovto,
						// crp.importeBase
// 						
					// FROM
						// cont_rel_pol_prov crp,
						// cont_polizas cp,
						// cont_tipos_poliza ctp,
						// cont_ejercicios ce,
						// mrp_proveedor mp,
						// cont_movimientos cm,
						// cont_accounts ca,
						// cont_tasaPrv tp
					// WHERE
						// tp.id=crp.tasa and
						// crp.activo=1 
						// and cm.Activo=1
						// and cp.activo=1
						// and ca.account_id=cm.Cuenta
						// AND mp.idPrv = crp.idProveedor
						// AND cp.id = crp.idPoliza
						// AND ctp.id = cp.idtipopoliza
						// AND ce.Id = cp.idejercicio
						// and cm.IdPoliza=crp.idPoliza
						// and crp.idPoliza='.$idpoliza.' 
						// and mp.idPrv ='.$idProveedor;
			$ejercicios = $this->query($eje);
			return $ejercicios;
		}
		function detallado2($idpoliza,$idProveedor){
		  	$eje='SELECT
					crp.idProveedor,
					mp.razon_social,
					crp.importe,
					tp.valor,
					crp.importeBase,
					crp.otrasErogaciones,
					crp.ivaRetenido,
					crp.isrRetenido,
					crp.ivaPagadoNoAcreditable,
					e.NombreEjercicio,
					cp.idperiodo,
					crp.periodoAcreditamiento,
					crp.ejercicioAcreditamiento

				FROM
					cont_rel_pol_prov crp,
					mrp_proveedor mp,
					cont_polizas cp,
					cont_ejercicios e,
					cont_tasaPrv tp
				WHERE
					tp.id=crp.tasa and
					e.Id=crp.ejercicioAcreditamiento
					and cp.id=crp.idPoliza
					and crp.aplica = 1 
					and crp.activo=1 
					and  mp.idPrv=crp.idProveedor
					and crp.idPoliza='.$idpoliza.'
					and crp.idProveedor='.$idProveedor;
			$ejercicios = $this->query($eje);
			return $ejercicios;
		}
			function empresa(){
				$eje='select * from organizaciones';
					$ejercicios = $this->query($eje);
						return $ejercicios;			
			}
	}
?>