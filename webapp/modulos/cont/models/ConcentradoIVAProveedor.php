<?php
     require("models/connection_sqli.php"); // funciones mySQLi

	class ConcentradoIVAProveedorModel extends Connection
	{
		
		function empresa(){
				$eje='select * from organizaciones';
					$ejercicios = $this->query($eje);
						return $ejercicios;			
			}
		function tasaacredita($ejercicio,$delperiodo,$alperiodo,$proveedores,$aplica){
			$var=strrpos($proveedores,'and');
				if($var!==false){
			if($proveedores!=''){ $prove="and rp.idProveedor BETWEEN ".$proveedores;}else{ $prove='';}
				}else{ if($proveedores!=''){ $prove="and rp.idProveedor= ".$proveedores;}else{ $prove='';} }
			if($aplica==1){
				$apli=' rp.aplica=1 and';
			}else{
				$apli='';
			}
			
			$eje='select  0 as mastasa,0 as suma,p.rfc, p.razon_social,rp.otraserogaciones,tp.`tasa`,tp.valor,tt.tipotercero,p.curp,e.NombreEjercicio,rp.tasa idtasa,rp.idProveedor,
			
sum(rp.`importeBase`) importeBase,sum(rp.otraserogaciones)otraserogaciones,tp.valor,tp.tasa,sum((rp.`importeBase`/100)* tp.`valor`) acredita,
		sum(rp.ivaRetenido)ivaRetenido,sum(rp.`isrRetenido`)isrRetenido,sum(rp.`ivaPagadoNoAcreditable`)ivaPagadoNoAcreditable,sum(rp.`importeBase`+rp.otraserogaciones+((rp.`importeBase`/100)* tp.`valor`) -rp.ivaRetenido-rp.`isrRetenido`) totalerogacion
			
			from  cont_rel_pol_prov rp,mrp_proveedor p,cont_tasaPrv tp,`cont_tipo_tercero` tt,cont_polizas cp,cont_ejercicios e 
			where e.Id=cp.idejercicio and  p.idPrv=rp.`idProveedor`  and tt.id=p.idtipotercero and cp.activo=1 and cp.eliminado=0
			and tp.id=rp.tasa and '.$apli.' rp.activo=1 and tp.visible=1 and cp.id=rp.idPoliza
			'.$prove.' and rp.ejercicioAcreditamiento='.$ejercicio.' and rp.periodoAcreditamiento BETWEEN '.$delperiodo.' and '.$alperiodo.'  group by rp.idProveedor,tp.tasa order by rp.idProveedor';		
			$ejercicios = $this->query($eje);
			return $ejercicios;	
		}
	function suma($idproveedor,$idtasa,$ejercicio,$delperiodo,$alperiodo){
		$eje="select sum(rp.`importeBase`) importeBase,sum(rp.otraserogaciones)otraserogaciones,tp.valor,tp.tasa,sum((rp.`importeBase`/100)* tp.`valor`) acredita,
		sum(rp.ivaRetenido)ivaRetenido,sum(rp.`isrRetenido`)isrRetenido,sum(rp.`ivaPagadoNoAcreditable`)ivaPagadoNoAcreditable,sum(rp.`importeBase`+rp.otraserogaciones+((rp.`importeBase`/100)* tp.`valor`) -rp.ivaRetenido-rp.`isrRetenido`) totalerogacion
		from cont_rel_pol_prov rp,cont_tasaPrv tp,cont_polizas pol
		where tp.id=rp.tasa  and  rp.activo=1 and tp.visible=1  and rp.idProveedor=".$idproveedor." and pol.id=rp.idPoliza and pol.activo=1 and pol.eliminado=0 
		and rp.periodoAcreditamiento between $delperiodo and $alperiodo and  rp.ejercicioAcreditamiento=$ejercicio";
		$ejercicios = $this->query($eje);
		return $ejercicios;	
	}
	function suma2($idproveedor,$idtasa,$ejercicio,$delperiodo,$alperiodo){//fecha
		$eje="select sum(rp.`importeBase`) importeBase,sum(rp.otraserogaciones)otraserogaciones,tp.valor,tp.tasa,sum((rp.`importeBase`/100)* tp.`valor`) acredita,
		sum(rp.ivaRetenido)ivaRetenido,sum(rp.`isrRetenido`)isrRetenido,sum(rp.`ivaPagadoNoAcreditable`)ivaPagadoNoAcreditable,sum(rp.`importeBase`+rp.otraserogaciones+((rp.`importeBase`/100)* tp.`valor`) -rp.ivaRetenido-rp.`isrRetenido`) totalerogacion
		from cont_rel_pol_prov rp,cont_tasaPrv tp,cont_polizas pol
		where tp.id=rp.tasa  and  rp.activo=1 and tp.visible=1  and rp.idProveedor=".$idproveedor." and pol.id=rp.idPoliza and pol.activo=1 and pol.eliminado=0 
		and pol.fecha between '$delperiodo' and '$alperiodo' ";
		$ejercicios = $this->query($eje);
		return $ejercicios;	
	}
		function otrastasas($idproveedor,$tasa){
			$eje='
				select ta.`tasa`
				FROM 
				cont_tasas ta
				WHERE
				ta.`tasa`  NOT IN (
				select  p.tasa from`cont_tasaPrv` p
				where  p.visible=1 and p.`idPrv`='.$idproveedor.' 
				  and p.tasa="'.$tasa.'")';
			$ejercicios = $this->query($eje);
			return $ejercicios;	
		}
		
		//
		function tasaacredita2($ejercicio,$inicio,$fin,$proveedores,$aplica){
			$var=strrpos($proveedores,'and');
				if($var!==false){
			if($proveedores!=''){ $prove="and rp.idProveedor BETWEEN ".$proveedores;}else{ $prove='';}
				}else{ if($proveedores!=''){ $prove="and rp.idProveedor= ".$proveedores;}else{ $prove='';} }
			if($aplica==1){
				$apli=' rp.aplica=1 and';
			}else{
				$apli='';
			}
			
			$eje='select  0 as mastasa,0 as suma,p.rfc, p.razon_social,rp.otraserogaciones,tp.`tasa`,tp.valor,tt.tipotercero,p.curp,e.NombreEjercicio,rp.tasa idtasa,rp.idProveedor,
			
sum(rp.`importeBase`) importeBase,sum(rp.otraserogaciones)otraserogaciones,tp.valor,tp.tasa,sum((rp.`importeBase`/100)* tp.`valor`) acredita,
		sum(rp.ivaRetenido)ivaRetenido,sum(rp.`isrRetenido`)isrRetenido,sum(rp.`ivaPagadoNoAcreditable`)ivaPagadoNoAcreditable,sum(rp.`importeBase`+rp.otraserogaciones+((rp.`importeBase`/100)* tp.`valor`) -rp.ivaRetenido-rp.`isrRetenido`) totalerogacion
			
			from  cont_rel_pol_prov rp,mrp_proveedor p,cont_tasaPrv tp,`cont_tipo_tercero` tt,cont_polizas cp,cont_ejercicios e 
			where e.Id=cp.idejercicio and  p.idPrv=rp.`idProveedor`  and tt.id=p.idtipotercero 
			and tp.id=rp.tasa and  '.$apli.' rp.activo=1 and tp.visible=1 and cp.id=rp.idPoliza and cp.activo=1 and cp.eliminado=0
			'.$prove.'  and cp.fecha BETWEEN "'.$inicio.'" and "'.$fin.'"  group by rp.idProveedor,tp.tasa order by rp.idProveedor';
			$ejercicios = $this->query($eje);
			return $ejercicios;	
		}
	}
	
			// $eje='select  0 as mastasa,p.rfc, p.razon_social, rp.`importeBase`,rp.otraserogaciones,tp.`tasa`,tp.valor,
			// ((rp.`importeBase`/100)* tp.`valor`) acredita,rp.ivaRetenido,rp.`isrRetenido`,
			// (rp.`importeBase`+rp.otraserogaciones+((rp.`importeBase`/100)* tp.`valor`) -rp.ivaRetenido-rp.`isrRetenido`) totalerogacion,
			// rp.`ivaPagadoNoAcreditable`,tt.tipotercero,p.curp,e.NombreEjercicio,cp.id poliza,p.idPrv
			// from  cont_rel_pol_prov rp,mrp_proveedor p,cont_tasaPrv tp,`cont_tipo_tercero` tt,cont_polizas cp,cont_ejercicios e 
			// where e.Id=cp.idejercicio and  p.idPrv=rp.`idProveedor`  and tt.id=p.idtipotercero 
			// and tp.id=rp.tasa and  rp.aplica=1 and rp.activo=1 and tp.visible=1 and cp.id=rp.idPoliza
			// '.$prove.' and cp.idejercicio='.$ejercicio.' and rp.periodoAcreditamiento BETWEEN '.$delperiodo.' and '.$alperiodo.'  order by p.razon_social';		
			// $ejercicios = $this->query($eje);
?>