<?php

class FlujoModel extends ChequesModel{
	
	function cuentasBancariasPorMoneda($idmoneda,$idbancaria){
		if($idbancaria>0){
			$filtro = "and c.idbancaria=".$idbancaria;
		}else{
			$filtro = "";
		}
		$sql=$this->query("select c.*,b.nombre,cc.manual_code,cc.description 
		from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc,cont_coin d 
		where c.activo=-1  and c.cancelada=0 and c.idbanco=b.idbanco and c.account_id=cc.account_id and c.coin_id=d.coin_id and d.coin_id=".$idmoneda." $filtro");
		return $sql;
	}
	function saldoInicialFujo($idbancaria,$fechainicial,$fechafin,$cobrado,$proyectados){
		
		$fecha = explode("-",$fechainicial);
		$periodoinicial = intval( $fecha[1]);
		$periodo = $periodoinicial-1;
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
		/* Saca en rango de fechas por el hecho de conciliacion
		 * ya que si se toma el ultimo periodo conciliado como el saldo inicial 
		 * apartir de ahi cuanta los documentos para sacar el saldo ala fecha 
		 * marcada por el usuario, por eso va el between porq
		 * si se pusiera <=fecha seria traer todos los documentos
		 * y no se nesesitan todos solo los q se crearon despues del finalizar conciliacion
		 */
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
		
		//}estaba aca	
			$fechainicial = strtotime('-1 days',strtotime ($fechainicial) );$fechainicial=date("Y-m-d", $fechainicial);
			$egresos 		= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=5  and status=1  and idbancaria=$idbancaria  and fecha between '".$fecha2."' and '".$fechainicial."' ");
			$egresoscheq 	= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=1  and status=1 and cobrado=1  and idbancaria=$idbancaria  and fechaaplicacion between '".$fecha2."' and '".$fechainicial."' ");
			$egresoscheq 	= $egresoscheq->fetch_assoc();
			if( $cobrado==0){
					
				$egresoscheq3	= $this->query("select sum(importe)egresos from bco_documentos where idDocumento=1  and status=1 and cobrado=0  and idbancaria=$idbancaria  and fecha<='".$fechainicial."' ");
				$egresoscheq3 	= $egresoscheq3->fetch_assoc();
				
				//$egresoscheq['egresos']+=	$egresoscheq2['egresos'];
				$egresoscheq['egresos']+=	$egresoscheq3['egresos'];
			}
			$ingresosdepos	= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=4 and status=1 and proceso=4 and idbancaria=$idbancaria and fechaaplicacion between '".$fecha2."' and '".$fechainicial."'");
			$ingresos 		= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=2 and status=1  and idbancaria=$idbancaria and fecha between '".$fecha2."' and '".$fechainicial."'");
			$egresos 		= $egresos->fetch_assoc();
			
			$ingresosdepos 	= $ingresosdepos->fetch_assoc();
			$ingresos 		= $ingresos->fetch_assoc();
			
			if($proyectados==1){
				$ingresosdepos2	= $this->query("select sum(importe)ingresos from bco_documentos where idDocumento=4 and status=1  and proceso=4 and idbancaria=$idbancaria and fecha<='".$fechainicial."' and fechaaplicacion>'$fechafin'");
				$ingresosdepos2 	= $ingresosdepos2->fetch_assoc();
				$ingresosdepos['ingresos']+=$ingresosdepos2['ingresos'];
			}
		}//MOVI ESTE AQUI porq si hay conciliacion ya no debe calcular q a pasado solo toma ese sueldo y ya
		
		
		$saldo = $saldoini['saldoinicial'] + ($ingresos['ingresos'] + $ingresosdepos['ingresos']) - ($egresos['egresos'] + $egresoscheq['egresos']);
		return $saldo;
	}
	function saldoFinalFlujo($fechainicio,$fechafin,$saldoinicial,$idbancaria){
		
		$egresos = $this->query("select sum(importe)egresos from bco_documentos where (idDocumento=5 or idDocumento=1)  and status=1  and idbancaria=$idbancaria and fecha between '".$fechainicio."' and '".$fechafin."' ");
		$ingresos = $this->query("select sum(importe)ingresos from bco_documentos where (idDocumento=2 or idDocumento=4) and status=1 and idbancaria=$idbancaria and fecha between '".$fechainicio."' and '".$fechafin."'");
		$egresos = $egresos->fetch_assoc();
		$ingresos = $ingresos->fetch_assoc();
		return $saldoinicial + $ingresos['ingresos'] - $egresos['egresos'];
		
	}
	/* flujo por documento 
	 * no esta completo se dejara para segun face */
	function ingresosFlujo($fechainicio,$fechafin,$idbancaria){
		$sql = $this->query("select b.*,c.nombreclasificador,c.cuentapadre 
			from bco_documentos b,bco_clasificador c
			where b.idbancaria=$idbancaria and b.status=1 and (b.idDocumento=2 or b.idDocumento=4) 
			and c.id = b.idclasificador 
			and c.idNivel=1 and b.fecha between '$fechainicio' and '$fechafin' order by c.nombreclasificador,b.fecha asc;");
		return $sql;
	}
	function egresosFlujo($fechainicio,$fechafin,$idbancaria){
		$sql = $this->query("select b.*,c.nombreclasificador,c.cuentapadre 
		from bco_documentos b,bco_clasificador c
		where b.idbancaria=$idbancaria and b.status=1 and (b.idDocumento=5 or b.idDocumento=1) 
		and c.id = b.idclasificador and c.idNivel=1 and b.fecha between '$fechainicio' and '$fechafin' order by c.nombreclasificador,b.fecha  asc;");
		return $sql;
	}
	/* fin flujo por documento */
	
	/* flujo global por subclasificador 
	 * sera grupal por subclasificador desglosando padre e hijos*/
	 function ingresosFlujoGlobal($fechainicio,$fechafin,$idbancaria,$proyectados){
	 	if($proyectados==1){
	 		$doc=3; $filtro = " and d.proceso=2";
	 	}else{
	 		$doc=2; $filtro = "";
	 	}
	 	$sql = $this->query("
		select 
			sum( (case d.idclasificador when 0  then cd.importe else d.importe end) )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
		from 
			bco_documentos d
		left  join bco_documentoSubcategorias cd on d.id=cd.idDocumento 
		left  join  bco_clasificador c  on (c.id = cd.idSubcategoria || c.id=d.idclasificador)
		where 
			 d.idDocumento=$doc and d.fecha between '$fechainicio' and '$fechafin' 
			 and d.idbancaria=$idbancaria and d.status=1 and d.proceso=2
			 group by c.id
			"); 
		return $sql;
	 }
	 
	 /* los depositos seran acorde al clasificador del
	  * proyectado, proceso 4 que es depositado status 1 q es activo y solo los depositos 
	  * de la fecha del rango del filtro
	  * 	SE SEPARAN LOS DOCUMENTOS DESTINO DE TRASPASOS YA Q ELLOS NO TIENEN INGRESOS PROYECTADOS 
	  * SOLO SON DESTINO*/
	 function depositosFlujoGlobal($fechainicio,$fechafin,$idbancaria){
	 	$sql = $this->query("
			select 
				sum( (case d.idclasificador when 0  then cd.importe else d.importe end) )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
			from 
				bco_documentos d
			left join bco_ingresos_depositos dp on dp.idIngresoNoDepo=d.id and
				dp.idDeposito in( select id from bco_documentos where idDocumento=4 and fechaaplicacion between '$fechainicio' and '$fechafin'  and idbancaria=$idbancaria and status=1 and proceso=4 and idtraspaso=0 )
			
			left  join bco_documentoSubcategorias cd on d.id=cd.idDocumento 
			left  join  bco_clasificador c  on (c.id = cd.idSubcategoria || c.id=d.idclasificador)
			
			where 
				d.idDocumento=3 and d.status=1 and d.idbancaria=$idbancaria and d.proceso=4 and fecha between '$fechainicio' and '$fechafin' group by c.id
			"); 
		return $sql;
	 }
	 function depositosFlujoTraspasoGlobal($fechainicio,$fechafin,$idbancaria){
	 	$sql = $this->query("
			select 
				sum( d.importe  )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
			from 
				bco_documentos d
			
			left  join  bco_clasificador c  on  c.id=d.idclasificador
			
			where 
				d.idDocumento=4 and d.status=1 and d.idbancaria=$idbancaria and d.proceso=4 and d.fechaaplicacion between '$fechainicio' and '$fechafin'  and d.idtraspaso!=0 group by c.id
			"); 
		return $sql;
	 }
	  function depositosFlujoGlobalpendiente($fechainicio,$fechafin,$idbancaria){
	 	$sql = $this->query("
			select 
				sum( (case d.idclasificador when 0  then cd.importe else d.importe end) )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
			from 
				bco_documentos d
			left join bco_ingresos_depositos dp on dp.idIngresoNoDepo=d.id and
				dp.idDeposito in( select id from bco_documentos where idDocumento=4 and fecha between '$fechainicio' and '$fechafin' and fechaaplicacion>'$fechafin' and idbancaria=$idbancaria and status=1 and proceso=4 and idtraspaso=0 )
			
			left  join bco_documentoSubcategorias cd on d.id=cd.idDocumento 
			left  join  bco_clasificador c  on (c.id = cd.idSubcategoria || c.id=d.idclasificador)
			
			where 
				d.idDocumento=3 and d.status=1 and d.idbancaria=$idbancaria and d.proceso=4 and fecha>'$fechafin' group by c.id
			"); 
		return $sql;
	 }
	 function EgresosFlujoGlobal($fechainicio,$fechafin,$idbancaria){
	 	
	 	$sql = $this->query("
		select
			sum( (case d.idclasificador when 0  then cd.importe else d.importe end) )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
		from 
			bco_documentos d
		left  join bco_documentoSubcategorias cd on d.id=cd.idDocumento 
		left  join  bco_clasificador c  on (c.id = cd.idSubcategoria || c.id=d.idclasificador)
		where 
			d.idDocumento=5 and d.fecha between '$fechainicio' and '$fechafin' 
			and d.idbancaria=$idbancaria and d.status=1 
		group by c.id
		
			"); 
		return $sql;
	 }
	 
	 function chequesFlujoGlobal($fechainicio,$fechafin,$idbancaria){
	 	
	 	$sql = $this->query("
		
		select
			sum( (case d.idclasificador when 0  then cd.importe else d.importe end) )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
		from 
			bco_documentos d
		left  join bco_documentoSubcategorias cd on d.id=cd.idDocumento 
		left  join  bco_clasificador c  on (c.id = cd.idSubcategoria || c.id=d.idclasificador)
		where 
			d.idDocumento=1 and d.fechaaplicacion between '$fechainicio' and '$fechafin' 
			and d.idbancaria=$idbancaria and d.status=1 and d.cobrado=1
		group by c.id
			"); 
		return $sql;
	 }
	 function ChequesNocobraFlujoGlobal($fechainicio,$fechafin,$idbancaria){
	 	
	 	$sql = $this->query("
		
		select
			sum( (case d.idclasificador when 0  then cd.importe else d.importe end) )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
		from 
			bco_documentos d
		left  join bco_documentoSubcategorias cd on d.id=cd.idDocumento 
		left  join  bco_clasificador c  on (c.id = cd.idSubcategoria || c.id=d.idclasificador)
		where 
			d.idDocumento=1 and d.fecha between '$fechainicio' and '$fechafin' 
			and d.idbancaria=$idbancaria and d.status=1 and d.cobrado=0
		group by c.id
			"); 
		return $sql;
	 }
	 function ChequesNocobraFlujoGlobalCobradosDespues($fechainicio,$fechafin,$idbancaria){
	 	
	 	$sql = $this->query("
		
		select
			sum( (case d.idclasificador when 0  then cd.importe else d.importe end) )importe ,c.nombreclasificador,c.cuentapadre,c.id,d.id docu
		from 
			bco_documentos d
		left  join bco_documentoSubcategorias cd on d.id=cd.idDocumento 
		left  join  bco_clasificador c  on (c.id = cd.idSubcategoria || c.id=d.idclasificador)
		where 
			d.idDocumento=1 and d.fecha between '$fechainicio' and '$fechafin' and d.fechaaplicacion>'$fechafin'
			and d.idbancaria=$idbancaria and d.status=1 and d.cobrado=1
		group by c.id
			"); 
		return $sql;
	 }
	
	/* fin flujo por subclasificador */
	
/*		 POSICION BANCARIA DIARA			*/
function ingresosPosicion($fechainicio,$fechafin,$idbancaria,$proyectados){
	 	if($proyectados==1){
	 		$doc="3"; $filtro = " and d.proceso=2";
	 	}else{
	 		$doc="2"; $filtro = "";
	 	}
	 	$sql = $this->query("
	 	select sum(d.importe )importe,d.idbancaria 
		from 
			bco_documentos d
		where 
			 d.idDocumento=$doc and d.fecha between '$fechainicio' and '$fechafin' 
			 and d.idbancaria=$idbancaria and d.status=1 $filtro
			 "); 
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
		
		
}
function depositosPosicion($fechainicio,$fechafin,$idbancaria){
	 	
	 	$sql = $this->query("
	 	select sum(d.importe )importe,d.idbancaria 
		from 
			bco_documentos d
		where 
			 d.idDocumento=4 and d.fechaaplicacion between '$fechainicio' and '$fechafin' 
			 and d.idbancaria=$idbancaria and d.status=1  and proceso=4
			 "); 
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
		
		
}
function depositosPosiciontransito($fechainicio,$fechafin,$idbancaria){
	 	
	 	$sql = $this->query("
	 	select sum(d.importe )importe,d.idbancaria 
		from 
			bco_documentos d
		where 
			 d.idDocumento=4 and d.fecha between '$fechainicio' and '$fechafin' and fechaaplicacion>='$fechafin'
			 and d.idbancaria=$idbancaria and d.status=1  and proceso=4
			 "); 
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}
		
		
}
function egresosPosicion($fechainicio,$fechafin,$idbancaria){
 	
	$sql = $this->query("
	 	select sum(d.importe )importe,d.idbancaria 
		from 
			bco_documentos d
		where 
			 d.idDocumento=5 and d.fecha between '$fechainicio' and '$fechafin' 
			 and d.idbancaria=$idbancaria and d.status=1 
			 "); 
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}	
}
function chequesPosicion($fechainicio,$fechafin,$idbancaria,$cobrado){
 	if($cobrado==1){
 		$cobrado = " and d.cobrado=1 ";
		$fecha = "fechaaplicacion";
 	}else{
 		$cobrado = "and d.cobrado=0";
		$fecha = "fecha";
 	}
	$sql = $this->query("
	 	select sum(d.importe )importe,d.idbancaria 
		from 
			bco_documentos d
		where 
			 d.idDocumento=1 and d.$fecha <='$fechafin' 
			 and d.idbancaria=$idbancaria and d.status=1 $cobrado
			 "); 
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}	
}
function chequesPosicionCobradosDespues($fechainicio,$fechafin,$idbancaria){
 	
	$sql = $this->query("
	 	select sum(d.importe )importe,d.idbancaria 
		from 
			bco_documentos d
		where 
			 d.idDocumento=1 and d.fecha between '$fechainicio' and '$fechafin' and fechaaplicacion>'$fechafin'
			 and d.idbancaria=$idbancaria and d.status=1 and d.cobrado=1
			 "); 
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}	
}
function chequesPosicionCobradosAntes($fechainicio,$fechafin,$idbancaria){
 	
	$sql = $this->query("
	 	select sum(d.importe )importe,d.idbancaria 
		from 
			bco_documentos d
		where 
			 d.idDocumento=1 and d.fechaaplicacion between '$fechainicio' and '$fechafin' and fecha<'$fechainicio'
			 and d.idbancaria=$idbancaria and d.status=1 and d.cobrado=1
			 "); 
		if($sql->num_rows>0){
			return $sql->fetch_array();
		}else{
			return 0;
		}	
}



/* 		FIN POSICION BANCARIA DIARIA			*/
	
}
		
		
?>