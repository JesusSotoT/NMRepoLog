<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL
require("models/connection_sqli_manual.php"); // funciones mySQLi

	class polizasImpresionModel extends Connection
	{
		function obtenerDatos($inicio,$fin,$tipo,$p13,$pol_ini,$pol_fin)
		{
			if(intval($p13))
			{
				$poliza13 = "";
			}
			else
			{
				$poliza13 = "AND p.idperiodo != 13";
			}

			if($tipo!=0){
							$where="AND p.idtipopoliza = $tipo";
							}else{
								$where="";
						}

			$numpol="";
			if(intval($pol_ini) AND intval($pol_fin))
			{
				$numpol = "AND p.numpol BETWEEN $pol_ini AND $pol_fin";
			}

			$myQuery = "SELECT m.NumMovto AS NUM_MOVIMIENTO,a.manual_code AS CODIGO,p.fecha AS FECHA,a.description AS CUENTA,p.numpol AS NUM_POL ,

			p.Concepto AS CONCEPTO,(select titulo From cont_tipos_poliza WHERE id=p.idtipopoliza) AS TIPO_POLIZA,m.Referencia AS REFERENCIA_MOV,

			m.Concepto AS CONCEPTO_MOV,  (select nombre from cont_segmentos where idSuc=m.IdSegmento) AS SEGMENTO, p.id,

			(select format(Importe,2) From cont_movimientos WHERE TipoMovto='Cargo' AND Id = m.Id) AS CARGO,

			(select format(Importe,2) From cont_movimientos WHERE TipoMovto='Abono' AND Id = m.Id) AS ABONO,

			p.rfc,p.numero,p. numtarjcuent,

 			(select razon_social from mrp_proveedor where idPrv=p.beneficiario) as beneficiario,

 			(select nombre from cont_bancos where idbanco=p.idbanco) as bancodestino,

 			(select nombre from forma_pago where idFormapago=m.FormaPago) as formapago,

 			(select b.nombre from cont_bancos b,bco_cuentas_bancarias cb where b.idbanco=cb.idbanco and cb.idbancaria=p.idCuentaBancariaOrigen) as bancoorigen,

			(select cuenta from bco_cuentas_bancarias where idbancaria=p.idCuentaBancariaOrigen) as numerodestino

			FROM cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza INNER JOIN cont_accounts a ON a.account_id = m.Cuenta

			WHERE p.fecha BETWEEN '$inicio' AND '$fin'  AND p.activo = 1 AND m.Activo = 1 $poliza13 $where $numpol

			ORDER BY p.idejercicio, p.idperiodo, FECHA, TIPO_POLIZA,numpol,NUM_MOVIMIENTO
			";

			$datos = $this ->query($myQuery);
			return $datos;
		}

		function obtenerPoliza($pol_id) {
			$myQuery = "SELECT m.NumMovto AS NUM_MOVIMIENTO,a.manual_code AS CODIGO,p.fecha AS FECHA,a.description AS CUENTA,p.numpol AS NUM_POL ,

			p.Concepto AS CONCEPTO,(select titulo From cont_tipos_poliza WHERE id=p.idtipopoliza) AS TIPO_POLIZA,m.Referencia AS REFERENCIA_MOV,

			m.Concepto AS CONCEPTO_MOV,  (select nombre from cont_segmentos where idSuc=m.IdSegmento) AS SEGMENTO, p.id,

			(select format(Importe,2) From cont_movimientos WHERE TipoMovto='Cargo' AND Id = m.Id) AS CARGO,

			(select format(Importe,2) From cont_movimientos WHERE TipoMovto='Abono' AND Id = m.Id) AS ABONO,

			p.rfc,p.numero,p. numtarjcuent,

 			(select razon_social from mrp_proveedor where idPrv=p.beneficiario) as beneficiario,

 			(select nombre from cont_bancos where idbanco=p.idbanco) as bancodestino,

 			(select nombre from forma_pago where idFormapago=m.FormaPago) as formapago,

 			(select b.nombre from cont_bancos b,bco_cuentas_bancarias cb where b.idbanco=cb.idbanco and cb.idbancaria=p.idCuentaBancariaOrigen) as bancoorigen,

			(select cuenta from bco_cuentas_bancarias where idbancaria=p.idCuentaBancariaOrigen) as numerodestino

			FROM cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza INNER JOIN cont_accounts a ON a.account_id = m.Cuenta

			WHERE p.`id` = '$pol_id'

			ORDER BY p.idejercicio, p.idperiodo, FECHA, TIPO_POLIZA,numpol,NUM_MOVIMIENTO
			";

			//echo $myQuery;
			$datos = $this ->query($myQuery);
			return $datos;
		}

		function tipoPoliza(){
			$myQuery = "SELECT id,titulo FROM cont_tipos_poliza";
			$tipo = $this ->query($myQuery);
			//$tipo=$tipo->fetch_object();
			return $tipo;
		}

		function empresa()
		{
			$myQuery = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
			$empresa = $this->query($myQuery);
			$empresa = $empresa->fetch_array();
			return $empresa['nombreorganizacion'];
		}

		function logo()
		{
			$myQuery = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
			$logo = $this->query($myQuery);
			$logo = $logo->fetch_assoc();
			return $logo['logoempresa'];
		}

//Nuevo Commit
	}

?>
