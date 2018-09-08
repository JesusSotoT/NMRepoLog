<?php
class IngresosModel extends ChequesModel{
	function clasificadorIngre(){
		return $this->query("select * from bco_clasificador where idtipo=1 and idNivel=1 and activo=-1");
	}
	function catalogoCliente(){
		return $this->query("select * from comun_cliente order by nombre asc");
	}
	function creaIngresoNoDepositado($fecha,$importe,$referencia,$concepto,$idbancaria,$tipobeneficiario,$idbeneficiario,$idclasificador,$idmoneda,$idDocumento,$proceso,$idTipoDoc,$tipocambio){
		$sql=("INSERT INTO `bco_documentos` (`fecha`, `fechacreacion`, `importe`, `referencia`, `concepto`, `idbancaria`, `beneficiario`, `idbeneficiario`, `status`, `conciliado`, `impreso`, `asociado`, `proceso`, `idclasificador`, `idDocumento`, `posibilidadpago`, `xml`, `idmoneda`,idTipoDoc,tipocambio) VALUES
		('$fecha', DATE_SUB(NOW(), INTERVAL 6 HOUR),$importe, '$referencia', '$concepto', $idbancaria, $tipobeneficiario, $idbeneficiario, 1, 0, 2, NULL, $proceso, $idclasificador, $idDocumento, NULL, NULL, $idmoneda,$idTipoDoc,$tipocambio); ");
		return $this->insert_id($sql);
		// if($this->query($sql)){
			// return 1;
		// }else{
			// return 0;
		// }
	}
	function ingresosPendientesDepositar(){
		return $this->query("select d.*, (case d.idbancaria when 0 then 'Sin cuenta' else c.cuenta end) cuenta,m.description,
(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social  when 2 then concat(e.nombreEmpleado, ' ' , e.apellidoPaterno) end) nombre 
 from bco_documentos d,bco_cuentas_bancarias c,cont_coin m,comun_cliente cl,mrp_proveedor prv,nomi_empleados e
 where   ((c.idbancaria=d.idbancaria and d.idbancaria=1) || d.idbancaria=0) and m.coin_id=d.idmoneda and d.idmoneda=1 and (case d.beneficiario when 5 then cl.id=d.idbeneficiario when 1 then prv.idPrv=d.idbeneficiario  when 2 then e.idEmpleado=d.idbeneficiario   end)   and  d.status=1 and d.proceso=2 and d.idDocumento=3 group by d.id");
	}
	function listadoIngresos(){
		//return $this->query("select d.*, c.cuenta,m.description,(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno)  end) nombre from bco_documentos d,bco_cuentas_bancarias c,cont_coin m,comun_cliente cl,mrp_proveedor prv,nomi_empleados em where   c.idbancaria=d.idbancaria  and m.coin_id=d.idmoneda and  (case d.beneficiario when 5 then cl.id=d.idbeneficiario when 1 then prv.idPrv=d.idbeneficiario  when 2 then em.idEmpleado=d.idbeneficiario else d.idbeneficiario=0   end)  and  d.status=1  and d.idDocumento=2 group by d.id order by d.id desc");
		return $this->query("select d.*, c.cuenta,m.description,(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno)  end) nombre 
		from bco_documentos d
			left join  bco_cuentas_bancarias c on c.idbancaria=d.idbancaria 
			left join 	mrp_proveedor prv on (case d.beneficiario when 1 then prv.idPrv=d.idbeneficiario  end)
			left join 	comun_cliente cl on (case d.beneficiario when 5 then cl.id=d.idbeneficiario  end)
			left join 	cont_coin m on  m.coin_id=d.idmoneda
			left join 	nomi_empleados em on (case d.beneficiario when 2 then em.idEmpleado=d.idbeneficiario  else d.idbeneficiario=0 end)
		 where  d.status=1  and d.idDocumento=2  and d.importe>0  group by d.id order by d.id desc	
	");
		
	}
	function listadoIngresosProyectados(){
			// select d.*, (case d.idbancaria when 0 then 'Sin cuenta' else c.cuenta end) cuenta,m.description,(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) when 0 then '".$infoOrg['nombreorganizacion']."' end) nombre from bco_documentos d,bco_cuentas_bancarias c,cont_coin m,comun_cliente cl,mrp_proveedor prv,nomi_empleados em
				// where (c.idbancaria=d.idbancaria || d.idbancaria=0) and m.coin_id=d.idmoneda   and  (case d.beneficiario when 5 then cl.id=d.idbeneficiario when 1 then prv.idPrv=d.idbeneficiario  when 2 then em.idEmpleado=d.idbeneficiario else d.idbeneficiario=0   end) and  d.status=1 and d.idDocumento=3 group by d.id order by d.id desc ");
		$infoOrg = $this->rfcOrganizacion();
		return $this->query("select d.*, (case d.idbancaria when 0 then 'Sin cuenta' else c.cuenta end) cuenta,m.description,(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) when 0 then 'unaor' end) nombre 
from bco_documentos d
left join  bco_cuentas_bancarias c on (c.idbancaria=d.idbancaria || d.idbancaria=0) 
left join 	mrp_proveedor prv on (case d.beneficiario when 1 then prv.idPrv=d.idbeneficiario    end)
left join 	comun_cliente cl on (case d.beneficiario when 5 then cl.id=d.idbeneficiario  end)
left join 	cont_coin m on  m.coin_id=d.idmoneda
left join 	nomi_empleados em on (case d.beneficiario when 2 then em.idEmpleado=d.idbeneficiario  end)

where  d.status=1 and d.idDocumento=3 and d.importe>0 group by d.id order by d.id desc ");
	}
	function listadoDepositos(){
		return $this->query("select d.*, c.cuenta from bco_documentos d,bco_cuentas_bancarias c where   c.idbancaria=d.idbancaria and  d.status=1 and d.proceso=4 and d.idDocumento=4 group by d.id order by d.id desc");
	}
	function creaDeposito($fecha,$importe,$referencia,$concepto,$idbancaria,$formadeposito,$idTipoDoc,$moneda,$tc){
		$sql=("INSERT INTO `bco_documentos` (`fecha`, `fechacreacion`, `importe`, `referencia`, `concepto`, `idbancaria`, `status`, `conciliado`, `impreso`, `asociado`, `proceso`, `idDocumento`, `posibilidadpago`, `xml`, `idmoneda`,formadeposito,idTipoDoc,tipocambio) VALUES
		('$fecha', DATE_SUB(NOW(), INTERVAL 6 HOUR),$importe, '$referencia', '$concepto', $idbancaria, 1, 0, 2, NULL, 4, 4, NULL, NULL, $moneda,$formadeposito,$idTipoDoc,$tc); ");
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	
	function ultimoDocumento(){
		$mov = $this->query("select id from bco_documentos  ORDER BY id DESC LIMIT 1");
		$mov = $mov->fetch_assoc();
		return $mov['id'];
	}
	function ingresosPendientesDepositarMoneda($idbancaria,$moneda){
		//if($fecha){ $fecha = "and fecha<='$fecha' ";}else{ $fecha="";}
		return $this->query("select d.*, (case d.idbancaria when 0 then 'Sin cuenta' else c.cuenta end) cuenta,m.description,(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) end) nombre  
		from bco_documentos d
				
		left join  bco_cuentas_bancarias c on c.idbancaria=d.idbancaria 
		left join 	mrp_proveedor prv on (case d.beneficiario when 1 then prv.idPrv=d.idbeneficiario  end)
		left join 	comun_cliente cl on (case d.beneficiario when 5 then cl.id=d.idbeneficiario end)
		left join 	cont_coin m on  m.coin_id=d.idmoneda
		left join 	nomi_empleados em on (case d.beneficiario when 2 then em.idEmpleado=d.idbeneficiario end)
		
		where   ((c.idbancaria=d.idbancaria and d.idbancaria=$idbancaria) || d.idbancaria=0) and d.idmoneda=".$moneda." and (cl.id=d.idbeneficiario || prv.idPrv=d.idbeneficiario || em.idEmpleado=d.idbeneficiario || d.idbeneficiario=0)  and  d.status=1 and d.proceso=2 and d.idDocumento=3 and d.idtraspaso=0 group by d.id");
	}
	function ingresosPendientesDepositarTraspaso($moneda){
		return $this->query("select d.*, (case d.idbancaria when 0 then 'Sin cuenta' else c.cuenta end) cuenta,m.description,(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) end) nombre  
		from bco_documentos d
			left join  bco_cuentas_bancarias c on c.idbancaria=d.idbancaria 
			left join 	mrp_proveedor prv on (case d.beneficiario when 1 then prv.idPrv=d.idbeneficiario  end)
			left join 	comun_cliente cl on (case d.beneficiario when 5 then cl.id=d.idbeneficiario end)
			left join 	cont_coin m on  m.coin_id=d.idmoneda
			left join 	nomi_empleados em on (case d.beneficiario when 2 then em.idEmpleado=d.idbeneficiario end)
		where   ((c.idbancaria=d.idbancaria ) || d.idbancaria=0)  and d.idmoneda=".$moneda." and (cl.id=d.idbeneficiario || prv.idPrv=d.idbeneficiario || em.idEmpleado=d.idbeneficiario || d.idbeneficiario=0)  and  d.status=1 and d.proceso=2 and d.idDocumento=3 and d.idtraspaso!=0 group by d.id");
	}
	function multiple($sql){
		$result = $this->multi_query($sql);
		return $result;
	}
	function updateIngresoNO($idIngre,$fecha,$idbancaria){
		$sql =$this->query( "update bco_documentos set proceso=4,fecha='$fecha',idbancaria=$idbancaria where id=".$idIngre."; ");
	}
	function insertIngreso_deposito($idIngre,$depo){
		$sql = $this->query("INSERT INTO bco_ingresos_depositos (idDeposito, idIngresoNoDepo)VALUES( ".$depo.", ".$idIngre."); ");
	}
	function proyectadoClienteCuenta($idDoc){
		$sql=$this->query("select d.idbeneficiario, (case cl.cuenta when 0 then 0 else cl.cuenta end) account_id,d.concepto,cl.nombre from bco_documentos d, comun_cliente cl where d.beneficiario=5 and cl.id=d.idbeneficiario  and d.id=".$idDoc);
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
	}
	function proyectadoEmpleado($idDoc){
		$sql=$this->query("select d.idbeneficiario, 
		d.concepto,concat (e.nombreEmpleado, ' ', e.apellidoPaterno) nombre from bco_documentos d,nomi_empleados e where d.beneficiario=2 and e.idEmpleado=d.idbeneficiario  and d.id=".$idDoc);
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
	}
	function proyectadoProveCuenta($idDoc){//para buscar los beneficiarios/pagador
		$sql=$this->query("select d.idbeneficiario, (case prv.cuentacliente when -1 then 0 else prv.cuentacliente end) account_id,
		d.concepto,prv.razon_social nombre from bco_documentos d,mrp_proveedor prv where d.beneficiario=1 and prv.idPrv=d.idbeneficiario  and d.id=".$idDoc);
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
	}
	function beneficiario_pagadorPrv(){
		$sql = $this->query("select d.idbeneficiario, c.account_id,d.concepto,pr.razon_social from bco_documentos d,cont_accounts c, mrp_proveedor pr where d.beneficiario=6 and pr.idPrv=d.idbeneficiario and pr.cuenta=c.account_id and d.id=".$idDoc);
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
	}
	
	function IngresosDepositados($id){
		$infoOrg = $this->rfcOrganizacion();
		return $this->query("select d.*,(case d.idbancaria when 0 then 'Sin cuenta' else c.cuenta end) cuenta,m.description,
			(case d.beneficiario when 5 then cl.nombre when 1 then prv.razon_social when 2 then concat(em.nombreEmpleado,' ',em.apellidoPaterno) when 0 then '".$infoOrg['nombreorganizacion']."' end) nombre
 			from bco_ingresos_depositos de
 				left join bco_documentos d on d.id=de.idIngresoNoDepo
 				left join  bco_cuentas_bancarias c on c.idbancaria=d.idbancaria 
				left join 	mrp_proveedor prv on (case d.beneficiario when 1 then prv.idPrv=d.idbeneficiario else d.idbeneficiario=0  end)
				left join 	comun_cliente cl on (case d.beneficiario when 5 then cl.id=d.idbeneficiario else d.idbeneficiario=0  end)
				left join 	cont_coin m on  m.coin_id=d.idmoneda
				left join 	nomi_empleados em on (case d.beneficiario when 2 then em.idEmpleado=d.idbeneficiario else d.idbeneficiario=0  end)
 			where (c.idbancaria=d.idbancaria || d.idbancaria=0) and 
 			(cl.id=d.idbeneficiario || prv.idPrv=d.idbeneficiario || em.idEmpleado=d.idbeneficiario ) and
  			 de.idDeposito=$id group by d.id");
		
	}
	function actualizaIngreso($idSeg,$idSuc,$numeroformap,$edicion,$id,$fecha,$importe,$referencia,$concepto,$idbancaria,$tipobeneficiario,$idbeneficiario,$idclasificador,$idmoneda,$idDocumento,$idTipoDoc,$tc,$tipoPoliza,$interes,$formapago){
		if($edicion==0){//si no es edicion comprueba
			$idComprueba = $this->compruebaDisponibilidad($id);
		
			if($idComprueba!=0){//ya se ocupo
				$basico = $this->idUltimoDocumentoBasico($idComprueba);
				if($basico>0){
					$id = $basico;
				}else{
					$id = $this->InsertDocumentoBasico($idComprueba);//le paso el tipo de documento q debe hacer
					
				}
			}else{
				$existe = $this->compruebaExistencia($id);
				if($existe==0){//si es igual a 0 es qno existe y trae algo loco la session 
					$idtemporal = $this->InsertDocumentoBasico($idDocumento);
					$id = $idtemporal;
				}
			}
		}
		if(!$formapago){ $formapago=0;}
		if(!$numeroformap){ $numeroformap="-";}
		if(!$idSuc){ $idSuc=0;}
		if(!$idSeg){ $idSeg=0;}
		
		$sql=("
		UPDATE bco_documentos 
			set fecha = '$fecha', 
			fechacreacion = DATE_SUB(NOW(), INTERVAL 6 HOUR),
			importe = $importe, 
			referencia = '$referencia',
			concepto = '$concepto',
			idbancaria = $idbancaria,
			beneficiario = $tipobeneficiario,
			idbeneficiario = $idbeneficiario,
			idclasificador = $idclasificador,
			idmoneda = $idmoneda,
			idTipoDoc = $idTipoDoc,
			tipocambio = $tc,
			tipoPoliza = $tipoPoliza,
			interes = $interes,
			formadeposito = $formapago,
			numeroformapago = '$numeroformap',
			idSuc			= $idSuc,
			idSeg			= $idSeg
			where id=".$id);
		if($this->query($sql)){
			return $id;
		}else{
			return 0;
		}
	}
	function actualizaDeposito($insertbasic,$idSeg,$idSuc,$numeroformapago,$edicion,$id,$fecha,$importe,$referencia,$concepto,$idbancaria,$idclasificador,$formadeposito,$idTipoDoc,$tc,$moneda,$tipoPoliza,$fechaaplicacion){
		if($edicion==0){
			$idComprueba = $this->compruebaDisponibilidad($id);
			if($idComprueba!=0){//ya se ocupo
				$basico = $this->idUltimoDocumentoBasico($idComprueba);
				if($basico>0){
					$id = $basico;
				}else{
					$id = $this->InsertDocumentoBasico($idComprueba);//le paso el tipo de documento q debe hacer
					
				}
			}else{
				$existe = $this->compruebaExistencia($id);
				if($existe==0){//si es igual a 0 es qno existe y trae algo loco la session 
					$idtemporal = $this->InsertDocumentoBasico($insertbasic);
					$id = $idtemporal;
				}
			}
		}
		if(!$formapago){ $formapago=0;}
		if(!$numeroformap){ $numeroformap="-";}
		if(!$idSuc){ $idSuc=0;}
		if(!$idSeg){ $idSeg=0;}
		$sql=("
		UPDATE bco_documentos 
			set fecha = '$fecha', 
			fechacreacion = DATE_SUB(NOW(), INTERVAL 6 HOUR),
			importe = $importe, 
			referencia = '$referencia',
			concepto = '$concepto',
			idbancaria = $idbancaria,
			idTipoDoc = $idTipoDoc,
			tipocambio = $tc,
			idmoneda = $moneda,
			proceso = 4,
			tipoPoliza = $tipoPoliza,
			fechaaplicacion = '$fechaaplicacion',
			formadeposito = $formadeposito,
			numeroformapago	= '$numeroformapago',
			idSuc			= $idSuc,
			idSeg			= $idSeg
			where id=".$id);
		if($this->query($sql)){
			return $id;
		}else{
			return 0;
		}
	}
	function updateIngresoSiempreNoDepositado($id){
		$sql =$this->query( "update bco_documentos set proceso=2 where id in (select idIngresoNoDepo from bco_ingresos_depositos where idDeposito=".$id.")");
		$this->query("delete from app_pagos_relacion where 
			id_pago in(
					select p.id	 from app_pagos p
					where p.cobrar_pagar=0 
					and p.concepto like CONCAT('%ID.',(select id from  bco_documentos where id in (select idIngresoNoDepo from bco_ingresos_depositos where idDeposito=$id)),'%')
				)");	
		$sql2 = "delete from bco_ingresos_depositos where idDeposito=".$id;
		if($this->query($sql2)){
			return 1;
		}else{
			return 0;
		}
	}
	
	/// beneficiario pagador prv
	function proveedorBeneficiario(){
			$sql=$this->query("SELECT cuentacliente,idPrv,razon_social,idtipo FROM mrp_proveedor  where beneficiario_pagador=-1 order by razon_social asc ;");
			return $sql;
		}
	function cuentaClientes(){
		$sql=$this->query('SELECT account_id, manual_code, description,currency_id FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config) and currency_id=1');
		return $sql;
	}
	function cuentaClientesAsignar(){
		$sql=$this->query('SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config) and currency_id=1');
		return $sql;
	}
	function cuentaProveedor(){
		$sql=$this->query('SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaProveedores FROM cont_config) ');
		return $sql;
	}
	/* actualizacion de catalogo INGRESOS
	 * 
	 */
	function updatePrvCuenta($cuenta,$id){
		$sql = $this->query("update mrp_proveedor set cuentacliente=$cuenta where idPrv=".$id);
	}
	function updateClienteCuenta($cuenta,$id){
		$sql = $this->query("update comun_cliente set cuenta=$cuenta where id=".$id);
	}
	function  updateCuentaSueldoXpagar($cuenta){
		$sql = $this->query("update cont_config set CuentaSueldoxPagar=$cuenta");
	}
	/* FIN ACTUALIZACION */
	
	// function prvInfo($id){
		// $sql = $this->query("select * from mrp_proveedor where idPrv=".$id);
		// return $sql->fetch_array();
	// }
	function desgloseIva($imporBase,$impoIva,$idPoli,$importe){
		$sql = $this->query("select * from cont_rel_desglose_iva where idPoliza=".$idPoli);
		if($sql->num_rows>0){
			$sql = ("update cont_rel_desglose_iva set tasa16='".$importe."-".number_format($imporBase,2,'.','')."-".number_format($impoIva,2,'.','')."-0.00', 
					tasa11	=	'0.00-0.00-0.00-0.00',  
					tasa0	=	'0.00-0.00-0.00-0.00',  
					tasaExenta	=	'0.00-0.00-0.00-0.00',  
					tasa15		=	'0.00-0.00-0.00-0.00',  
					tasa10		=	'0.00-0.00-0.00-0.00',  
					otrasTasas	=	'0.00-0.00-0.00-0.00',  
					ivaRetenido	= 0,
					isrRetenido	= 0,
					otros		= 0,
					acreditableIETU	= 0
					where idPoliza=".$idPoli);
			if($this->query($sql)){
				return 1;
			}
		}else{
			return 1;
		}
	}
	
/* CXC INGRESOS PENDIENTES SOLO TRAERA LA INFORMACION DE APP_PAGOS PORQ AUN NO SE APLICAN */
function porAplicar($idDoc,$idcli){
	$sql = $this->query("select
		 	p.id,p.concepto,p.abono,p.ref_bancos
		from 
			app_pagos p
		where 
			p.concepto like '%ID.$idDoc%' 
			and p.cobrar_pagar=0");
	return $sql;
}
function updateConceptoPago($concepto,$id){
	$this->query("update app_pagos set concepto ='$concepto'  where id=".$id);
}
/* cxc de no depositados para manejar el saldo tal cual si es en otra moneda
 * y no convertir porq en este caso
 * no se podra ya que en no depositados
 * no pide el tipo de cambio entonces
 * necesito que el dinerin venga tan cual lo puse con el ultimo tipo de cambio
 */


/* agrega el tipo de cambio del deposito en
 * el pago del no depositado para dejar el
 * registro
 */
 function updateNoDeptc($id,$tc,$fechaaplica){
 	$sql = $this->query("update app_pagos set tipo_cambio=$tc, fecha_pago = '$fechaaplica' where id=".$id);
 }
	
}
?>