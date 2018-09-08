<?php

class CalendarioModel extends FlujoModel{
	
	function saldoinicialCalendario($idbancaria,$fechainicial,$fechafin,$bancario){
		$fecha = explode("-",$fechainicial);
		$periodoinicial = intval( $fecha[1]);
		$periodo = $periodoinicial;
		$ejer = $fecha[0];
		if($periodoinicial == 1){
			$periodo  = 12;
			$ejer = $fecha[0]-1;
		}
		$idejer = $this->ejercicio($ejer);
		if($periodoinicial == 1){
			$idejer = "<=".$idejer;
		}else{
			$idejer = "=".$idejer;
		}
		$saldoConciliacion = $this->saldoconciliacionPeriodoEjer($idbancaria,$periodo,$idejer);
		if($saldoConciliacion->num_rows>0){
			$saldoConcili = $saldoConciliacion->fetch_assoc();
			$saldoini['saldoinicial'] = $saldoConcili['saldo_final'];//si tiene conciliaciones entonces el final sera el inicial del periodo
			if($saldoConcili['periodo']<12){
				$saldoConcili['periodo']+=1;
			}else{
				$saldoConcili['periodo']=1;
			}
			$fecha2 = $saldoConcili['ejercicio']."-".$saldoConcili['periodo']."-01";
		}else{
			$saldocuen = $this->query("select saldoinicial,fechainicial from bco_cuentas_bancarias where idbancaria=$idbancaria");
			$cuentas = $saldocuen->fetch_assoc();
			$saldoini['saldoinicial'] = $cuentas['saldoinicial'];
			$fechasepara=strtotime('+1 days',strtotime ($cuentas['fechainicial']));
			$fecha2=date("Y-m-d", $fechasepara);
		}
		
		$fechainicial = strtotime('-1 days',strtotime ($fechainicial) );$fechainicial=date("Y-m-d", $fechainicial);
/* BANCARIO 
 * son los montos de cuando se aplicaron en caso de depositos y cheques
 * para egresos e ingresos es tal cual la fecha del documento*/
if($bancario==1){		
		$egresos 		= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=5  and status=1  and idbancaria=$idbancaria  and fecha<='".$fechainicial."' ");
		$egresoscheq 	= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=1  and status=1 and cobrado=1  and idbancaria=$idbancaria  and fechaaplicacion<='".$fechainicial."' ");
		$egresos = $egresos->fetch_object();
		$egresoscheq = $egresoscheq->fetch_object();
		
		$ingresos 		= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=2 and status=1  and idbancaria=$idbancaria and fecha<='".$fechainicial."'");
		$deposito	 	= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=4  and status=1  and proceso=4  and idbancaria=$idbancaria  and fechaaplicacion<='".$fechainicial."' ");
		$ingresos = $ingresos->fetch_object();
		$deposito = $deposito->fetch_object();
		
		return $saldoini['saldoinicial'] + ($ingresos->ingresos + $deposito->ingresos) - ($egresos->egresos + $egresoscheq->egresos);
		
}else{
/* CONTABLE 
 * para el saldo contable trae todo los documentos sin importar cuando se cobren o depositen es tal cual la fecha del documento*/
		$egresos 		= $this->query("select sum(importe)egresos from bco_documentos where idDocumento in (5,1)  and status=1  and idbancaria=$idbancaria  and fecha<='".$fechainicial."' ");
		$egresos = $egresos->fetch_object();
		
		$ingresos 		= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento in (2,4) and status=1  and idbancaria=$idbancaria and fecha<='".$fechainicial."'");
		$ingresos = $ingresos->fetch_object();
		
		return $saldoini['saldoinicial'] + ($ingresos->ingresos) - ($egresos->egresos );
	}		
}		
function ingresosBancario($fechainicial,$fechafinal,$idbancaria){
	$ingresos = $this->query("select d.*,b.nombre banco,cb.cuenta
				from 
					bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb
				where 
					d.idDocumento=2 and d.status=1  and d.idbancaria=$idbancaria and d.fecha between '$fechainicial' and '$fechafinal'
					and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria ");
	if($ingresos->num_rows>0){
		return $ingresos;
	}else{
		return 0;
	}
}
function depositosBancario($fechainicial,$fechafinal,$idbancaria){
	$deposito	= $this->query("select d.*,b.nombre banco,cb.cuenta 
				from bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb 
				where 
					d.idDocumento=4  and d.status=1  and d.proceso=4  and d.idbancaria=$idbancaria  and d.fechaaplicacion between '$fechainicial' and '$fechafinal' 
					and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria ");
	if($deposito->num_rows>0){
		return $deposito;
	}else{
		return 0;
	}
}
function chequesBancario($fechainicial,$fechafinal,$idbancaria){
	$cheque = $this->query("select d.*,b.nombre banco,cb.cuenta 
			from 
				bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb  
			where 
			d.idDocumento=1  and d.status=1 and d.cobrado=1  and d.idbancaria=$idbancaria  and d.fechaaplicacion between '$fechainicial' and '$fechafinal' 
			and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria");
	if($cheque->num_rows>0){
		return $cheque;
	}else{
		return 0;
	}
}
function egresosBancario($fechainicial,$fechafinal,$idbancaria){
	$egresos = $this->query("select d.*,b.nombre banco,cb.cuenta 
				from bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb  
				where 
					d.idDocumento=5  and d.status=1  and d.idbancaria=$idbancaria  and d.fecha between '$fechainicial' and '$fechafinal'
					and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria");
	if($egresos->num_rows>0){
		return $egresos;
	}else{
		return 0;
	}
	
}
/* contable */
function chequesContable($fechainicial,$fechafinal,$idbancaria){
	$egresos = $this->query("
			select d.*,b.nombre banco,cb.cuenta 
			from 
				bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb  
			where 
			d.idDocumento=1  and d.status=1  and d.idbancaria=$idbancaria  and d.fecha between '$fechainicial' and '$fechafinal' and (fechaaplicacion>'$fechafinal' || fechaaplicacion is null) 
			and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria ");
	if($egresos->num_rows>0){
		return $egresos;
	}else{
		return 0;
	}
}
function depositosContable($fechainicial,$fechafinal,$idbancaria){
	$ingresos = $this->query("select d.*,b.nombre banco,cb.cuenta 
			from 
				bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb  
			where 
			d.idDocumento=4  and d.status=1  and d.idbancaria=$idbancaria  and d.fecha between '$fechainicial' and '$fechafinal' and (fechaaplicacion>'$fechafinal' || fechaaplicacion is null) 
			and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria");
	if($ingresos->num_rows>0){
		return $ingresos;
	}else{
		return 0;
	}	
}
/* estos nose visualizan en las tablas solo es para restarlos del saldo porq se crearon antes*/
function depositosContableantes($fechainicial,$fechafinal,$idbancaria){
	$ingresos = $this->query("
			select sum(importe )importe,idbancaria 
			from 
				bco_documentos
			where 
			idDocumento=4  and status=1 and proceso=4 and idbancaria=$idbancaria  and fechaaplicacion  between '$fechainicial' and '$fechafinal' and fecha<'$fechainicial' 
			");
	if($ingresos->num_rows>0){
		$row = $ingresos->fetch_array();
		return $row['importe'];
	}else{
		return 0;
	}
}
function chequesContableantes($fechainicial,$fechafinal,$idbancaria){
	$egresos = $this->query("
			select sum(importe )importe,idbancaria 
			from 
				bco_documentos
			where 
			idDocumento=1 and cobrado=1  and status=1 and idbancaria=$idbancaria  and fechaaplicacion  between '$fechainicial' and '$fechafinal' and fecha<'$fechainicial' 
			");
	if($egresos->num_rows>0){
		$row = $egresos->fetch_array();
		return $row['importe'];
	}else{
		return 0;
	}
}
		




/*no depositados */
function noDepositados($fechainicial,$fechafinal,$idbancaria){
	$ingresos = $this->query("select d.*,b.nombre banco,cb.cuenta 
					from 
						bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb   
					where 
						d.idDocumento=3  and d.status=1 and d.proceso=2 
						and d.idbancaria=$idbancaria  and d.fecha between '$fechainicial' and '$fechafinal'
						and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria");
	if($ingresos->num_rows>0){
		return $ingresos;
	}else{
		return 0;
	}
	
}

function saldoinicialTransito($fechainicial,$idbancaria){
	$fechainicial = strtotime('-1 days',strtotime ($fechainicial) );$fechainicial=date("Y-m-d", $fechainicial);
	
	//si se  creo(fecha) en meses anteriores pero no se aplico(fechaaplicacion) tampoco en mes anteiores esta en transito en el saldo
	$ingresos 	= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=4  and status=1  and idbancaria=$idbancaria  and fecha<='$fechainicial' and fechaaplicacion>='$fechainicial'");
	//y si no cobro esta en transito
	$sincobrar	= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=1  and status=1 and cobrado=0  and idbancaria=$idbancaria  and fecha<='$fechainicial'  ");
	//si cobrado pero no en el periodo se arrastra porq asta la fecha inicial aun no corresponde la aplicacion
	$cobradoNoenperiodo	 	= $this->query("	select sum(importe)egresos from bco_documentos where idDocumento=1  and status=1 and cobrado=1  and idbancaria=$idbancaria and fecha<='$fechainicial' and fechaaplicacion>='$fechainicial' ");

	$ingresos = $ingresos->fetch_object();
	$sincobrar = $sincobrar->fetch_object();	
	$cobradoNoenperiodo = $cobradoNoenperiodo->fetch_object();	
	$saldo = $ingresos->ingresos - ($sincobrar->egresos + $cobradoNoenperiodo->egresos);
	return $saldo;
	
}
/* 		AUXILIAR POR BENEFICIARIO/PAGADOR		*/
function ingresosAuxiliar($fechainicial,$fechafinal,$idbancaria,$beneficiario,$idbeneficiario){
	$ingresos = $this->query("select d.*,b.nombre banco,cb.cuenta
				from 
					bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb
				where 
					d.idDocumento=2 and d.status=1  and d.idbancaria=$idbancaria and d.fecha between '$fechainicial' and '$fechafinal'
					and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria and d.beneficiario=$beneficiario and d.idbeneficiario=$idbeneficiario ");
	if($ingresos->num_rows>0){
		return $ingresos;
	}else{
		return 0;
	}
}
function depositosAuxiliar($fechainicial,$fechafinal,$idbancaria,$beneficiario,$idbeneficiario){
	$deposito	= $this->query("select d.*,b.nombre banco,cb.cuenta 
				from bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb 
				where 
					d.idDocumento=4  and d.status=1  and d.proceso=4  and d.idbancaria=$idbancaria  and d.fechaaplicacion between '$fechainicial' and '$fechafinal' 
					and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria and d.beneficiario=$beneficiario and d.idbeneficiario=$idbeneficiario");
	if($deposito->num_rows>0){
		return $deposito;
	}else{
		return 0;
	}
}
function chequesAuxiliar($fechainicial,$fechafinal,$idbancaria,$beneficiario,$idbeneficiario,$cobrado){
	if($cobrado==1){
		$filtrocobro = "and d.cobrado=1";
		$fecha = "fechaaplicacion";
	}else{
		$filtrocobro = "";
		$fecha = "fecha";
	}
	$cheque = $this->query("select d.*,b.nombre banco,cb.cuenta 
			from 
				bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb  
			where 
			d.idDocumento=1  and d.status=1 $filtrocobro  and d.idbancaria=$idbancaria  and d.$fecha between '$fechainicial' and '$fechafinal' 
			and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria and d.beneficiario=$beneficiario and d.idbeneficiario=$idbeneficiario");
	if($cheque->num_rows>0){
		return $cheque;
	}else{
		return 0;
	}
}
function egresosAuxiliar($fechainicial,$fechafinal,$idbancaria,$beneficiario,$idbeneficiario){
	$egresos = $this->query("select d.*,b.nombre banco,cb.cuenta 
				from bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb  
				where 
					d.idDocumento=5  and d.status=1  and d.idbancaria=$idbancaria  and d.fecha between '$fechainicial' and '$fechafinal'
					and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria and d.beneficiario=$beneficiario and d.idbeneficiario=$idbeneficiario");
	if($egresos->num_rows>0){
		return $egresos;
	}else{
		return 0;
	}
	
}
function noDepositadosAuxiliar($fechainicial,$fechafinal,$idbancaria,$beneficiario,$idbeneficiario){
	$ingresos = $this->query("select d.*,b.nombre banco,cb.cuenta 
					from 
						bco_documentos d,cont_bancos b,bco_cuentas_bancarias cb   
					where 
						d.idDocumento=3  and d.status=1 and d.proceso=2 
						and d.idbancaria=$idbancaria  and d.fecha between '$fechainicial' and '$fechafinal'
						and b.idbanco=cb.idbanco and cb.idbancaria=d.idbancaria and d.beneficiario=$beneficiario and d.idbeneficiario=$idbeneficiario");
	if($ingresos->num_rows>0){
		return $ingresos;
	}else{
		return 0;
	}
	
}
/*   				FIN AUXILIAR					*/

/* proyectados de cxc y cxp 
 * $cobrar_pagar = 1 cxp
 * $cobrar_pagar = 0 cxc 
 * */

function listaCargosCalendario($cobrar_pagar,$moneda,$fecha,$fechafin){//$cobrar_pagar=1 CXP / $cobrar_pagar=0 CXC
   $myQuery = "SELECT p.id, p.tipo_cambio,p.origen, (SELECT codigo FROM cont_coin WHERE coin_id = p.id_moneda) AS moneda, p.tipo_cambio, p.fecha_pago, @c := p.cargo*p.tipo_cambio, p.cargo, p.concepto, @p := IFNULL((SELECT SUM(pr.abono) FROM app_pagos_relacion pr WHERE pr.id_tipo = 0 AND pr.id_documento = p.id  AND (SELECT id_moneda FROM app_pagos WHERE id = pr.id_pago) = 1),0) AS pagos,
					@p2 := IFNULL((SELECT SUM(pr.abono*(SELECT tipo_cambio FROM app_pagos WHERE id = pr.id_pago)) FROM app_pagos_relacion pr WHERE pr.id_tipo = 0 AND pr.id_documento = p.id AND (SELECT id_moneda FROM app_pagos WHERE id = pr.id_pago) != 1),0) AS pagos2,
                    (@c-(@p+@p2)) AS saldo
                    FROM app_pagos p
                    WHERE  p.cobrar_pagar = $cobrar_pagar
                    AND p.id_moneda= $moneda
                   	and p.fecha_pago  between  '$fecha' and '$fechafin'
                    AND p.cargo > 0 ";
                    
     return $this->query($myQuery);
}
function listaFacturasCalendario($cobrar_pagar,$moneda,$fecha,$fechafin){
	if(intval($cobrar_pagar))
        {
             
          $myQuery = "SELECT rq.tipo_cambio AS rq_tipo_cambio, r.id_oc, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, r.desc_concepto, r.id, r.fecha_factura,r.no_factura,r.imp_factura,(r.imp_factura*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, r.xmlfile, (SELECT diascredito FROM mrp_proveedor WHERE idPrv = c.id_proveedor) AS diascredito,
                    @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
                    @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
                    (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_recepcion r INNER JOIN app_ocompra c ON c.id = r.id_oc 
                    INNER JOIN app_requisiciones rq ON rq.id = id_requisicion
                    WHERE  xmlfile != '' and rq.id_moneda = $moneda
                    	and r.fecha_factura  between  '$fecha' and '$fechafin'
                    ORDER BY id_oc;";
        }
        else
        {
        		 $myQuery = "(SELECT rf.origen,rq.tipo_cambio AS rq_tipo_cambio, e.id_oventa, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, e.desc_concepto, rf.folio, e.id, rf.id AS idres, rf.fecha AS fecha_factura,e.total AS imp_factura, SUM(e.total*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, rf.xmlfile, (SELECT dias_credito FROM comun_cliente WHERE id = v.id_cliente) AS diascredito,
                    @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0 ),
                    (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_respuestaFacturacion rf
                    INNER JOIN  app_envios e ON e.id = rf.idSale AND e.forma_pago = 6
                    INNER JOIN app_oventa v ON v.id = e.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion and rq.id_moneda = $moneda
                    WHERE rf.tipoComp = 'F' AND rf.xmlfile != '' AND rf.origen = 1
					and rf.fecha <='$fechafin'
					)

				UNION ALL
                    
                    (SELECT rf.origen,rq.tipo_cambio AS rq_tipo_cambio, '' AS id_oventa, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, e.desc_concepto, rf.folio, e.id, rf.id AS idres, rf.fecha AS fecha_factura,SUM(e.total) AS imp_factura, SUM(pf.monto*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, rf.xmlfile, (SELECT dias_credito FROM comun_cliente WHERE id = v.id_cliente) AS diascredito,
                        @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                        @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                         (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_pendienteFactura pf
                    LEFT JOIN app_respuestaFacturacion rf ON pf.id_respFact = rf.id
                    INNER JOIN  app_envios e ON e.id = pf.id_sale AND e.forma_pago = 6
                    INNER JOIN app_oventa v ON v.id = e.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion and rq.id_moneda = $moneda
                    WHERE pf.id_respFact != 0
                    AND rf.idSale = 0
                    AND rf.borrado = 0
                    AND rf.xmlfile != ''
                    AND rf.idFact != 0
                    AND rf.tipoComp = 'F'
                    AND rf.origen = 1
                    and rf.fecha <='$fechafin'
                    GROUP BY pf.id_respFact)
                    ";
                    
			if($moneda==1){
                   $myQuery.= "UNION ALL
                    (SELECT rf.origen, 1 AS tipo_cambio, rf.idSale AS id_oventa, 'MXN' AS Moneda, CONCAT('Venta a credito POS ',rf.idSale) AS desc_concepto, rf.folio, v.idVenta AS id, rf.id AS idres, rf.fecha AS fecha_factura, vp.monto AS imp_factura, vp.monto AS importe_pesos,rf.xmlfile,(SELECT dias_credito FROM comun_cliente WHERE id = v.idCliente) AS diascredito,
                    @c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    @a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
                    (IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
                    FROM app_respuestaFacturacion rf
                    INNER JOIN app_pos_venta v ON v.idVenta = rf.idSale
                    INNER JOIN app_pos_venta_pagos vp ON vp.idVenta = v.idVenta AND vp.idFormapago = 6

                    WHERE rf.origen = 2
                    AND  rf.tipoComp = 'F'
					and rf.fecha<= '$fechafin'
					)

                    ";
			}
                   $myQuery.=" ORDER BY id_oventa;";
            
        }
    return $this->query($myQuery);
     
}

/* fin cxc y cxp */

}


?>
