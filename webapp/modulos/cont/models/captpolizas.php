<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

	class CaptPolizasModel extends Connection
	{
		function confirm()
		{
			$myQuery = "SELECT ConfirmPoliza FROM cont_config WHERE id = 1";
			$confirmar = $this->query($myQuery);
			$confirmar = $confirmar->fetch_assoc();
			return $confirmar['ConfirmPoliza'];
		}

		function getExerciseInfo()
		{
			$myQuery = "SELECT c.IdOrganizacion, o.nombreorganizacion,e.NombreEjercicio,e.Id AS IdEx,c.PeriodoActual,c.EjercicioActual,c.InicioEjercicio,c.FinEjercicio,c.PeriodosAbiertos,c.Estructura, c.TodasFacturas, c.siete_dimensiones FROM cont_config c INNER JOIN organizaciones o ON o.idorganizacion = c.IdOrganizacion INNER JOIN cont_ejercicios e ON e.NombreEjercicio = c.EjercicioActual";
			$companies = $this->query($myQuery);
			//echo "<input type='hidden' value='m:$myQuery'>";
			return $companies;
		}

		function getSegmentoInfo()
		{
			$myQuery = "SELECT* FROM cont_segmentos;";
			$s = $this->query($myQuery);
			return $s->num_rows;
		}

		function getActivePolizas($ejercicio,$periodo,$conf=null,$inicial=null,$final=null)
		{
			//$myQuery = "SELECT p.*,(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS tipopoliza FROM cont_polizas p WHERE p.activo=1 AND p.idejercicio=".$ejercicio." AND p.idperiodo=".$periodo;
			$where = "";
			$fechas = "p.idejercicio = $ejercicio AND p.idperiodo = $periodo";
			if($conf)
			{
				$inicial = explode('-', $inicial);
				$inicial = $inicial[2]."-".$inicial[1]."-".$inicial[0];
				$final = explode('-', $final);
				$final = $final[2]."-".$final[1]."-".$final[0];
				$where = "AND (confirmado1<>1 OR confirmado2<>1)";
				$fechas = "p.fecha BETWEEN '$inicial' AND '$final' ";
			}
			$myQuery = "SELECT 
id,
p.numpol,
(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS tipopoliza,
p.concepto,
p.fecha,
IFNULL((SELECT SUM(Importe) FROM cont_movimientos WHERE TipoMovto ='Cargo' AND IdPoliza = p.id AND Activo=1),0) AS Cargos,
IFNULL((SELECT SUM(Importe) FROM cont_movimientos WHERE TipoMovto = 'Abono' AND IdPoliza = p.id AND Activo=1),0) AS Abonos,
fecha_creacion,
usuario_creacion,
fecha_modificacion,
usuario_modificacion,
confirmado1, confirmado2, validador
FROM cont_polizas p
WHERE 
 $fechas 
AND p.activo=1 $where 
ORDER BY idtipopoliza,numpol";
			$polizas = $this->query($myQuery);
			return $polizas;
		}

		function savePoliza($idorg,$ejercicio,$periodo,$anticipo,$idtipopoliza)
		{
			$con = 0;
			if(!intval($this->confirm()))
				$con = 1;
			
				$myQuery = "INSERT INTO cont_polizas(idorganizacion,idejercicio,idperiodo,numpol,fecha_creacion,activo,eliminado,Anticipo,usuario_creacion,usuario_modificacion,fecha_modificacion,confirmado1,confirmado2) VALUES($idorg,$ejercicio,$periodo,".($this->UltimoNumPol($periodo,$ejercicio,$idtipopoliza)+1).",DATE_SUB(NOW(), INTERVAL 6 HOUR),0,0,$anticipo,".$_SESSION["accelog_idempleado"].",".$_SESSION["accelog_idempleado"].",DATE_SUB(NOW(), INTERVAL 6 HOUR),$con,$con)";
				$this->query($myQuery);
				$this->transaccion('Crea Poliza',$myQuery);
		}
		function transaccionController($accion,$query){
			$this->transaccion($accion,$query);
		}
		function UltimoNumPol($periodo,$ejercicio,$idtipopoliza)
		{
			$myQuery ="SELECT numpol FROM cont_polizas WHERE idperiodo = $periodo AND idejercicio = $ejercicio AND idtipopoliza=$idtipopoliza AND activo=1 ORDER BY numpol DESC LIMIT 1";
			$mov = $this->query($myQuery);
			$mov = $mov->fetch_assoc();
			return $mov['numpol'];
		}

		function getLastNumPoliza()
		{
			$myQuery = "SELECT id FROM cont_polizas ORDER BY id DESC LIMIT 1";
			$lastPoliza = $this->query($myQuery);
			$lp = $lastPoliza->fetch_assoc();
			return $lp;
		}

		function ActualizarPoliza($p,$tipoPoliza,$periodo,$fecha,$numpol,$referencia,$concepto,$relacion,$beneficiario,$numero,$rfc,$idbanco,$numtarjcuent,$idCuentaBancariaOrigen,$usuarioanticipo,$tipoBeneficiario=0)
		{
			
			$fecha = explode("-",$fecha);
			$myQuery = "UPDATE cont_polizas SET  idperiodo = $periodo, fecha = '".$fecha['2']."-".$fecha['1']."-".$fecha['0']."', numpol = $numpol, idtipopoliza = $tipoPoliza, concepto = '$concepto', referencia = '$referencia', activo = 1, relacionExt=$relacion, beneficiario=$beneficiario,numero='$numero',rfc='$rfc',idbanco=$idbanco,numtarjcuent='$numtarjcuent',idCuentaBancariaOrigen=$idCuentaBancariaOrigen, idUser=$usuarioanticipo ,tipoBeneficiario = ".intval($tipoBeneficiario).", usuario_modificacion =".$_SESSION["accelog_idempleado"]." , fecha_modificacion=DATE_SUB(NOW(), INTERVAL 6 HOUR) WHERE id = $p";
			$this->query($myQuery);
			$this->transaccion('Actualizar poliza',$myQuery);
		}

		function NumPeriodos($id)
		{
			$myQuery = "SELECT NumPeriodos FROM cont_config WHERE IdOrganizacion=".$id;
			$periods = $this->query($myQuery);
			$per = $periods->fetch_assoc();
			return $per['NumPeriodos'];
		}

		function ActActivo($id)
		{
			$myQuery = "UPDATE cont_polizas SET activo=0, usuario_modificacion =".$_SESSION["accelog_idempleado"]." , fecha_modificacion=DATE_SUB(NOW(), INTERVAL 6 HOUR) WHERE id=".$id;
			$this->query($myQuery);
			$myQuery = "UPDATE cont_movimientos SET Activo=0 WHERE IdPoliza=".$id;
			$this->query($myQuery);
			$this->transaccion('Desactivar poliza',$myQuery);
			
		}

		function idex($NameEjercicio)
		{
			$myQuery = "SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = $NameEjercicio";
			$id = $this->query($myQuery);
			$id = $id->fetch_assoc();
			return $id['Id'];
		}

		function InsertMov($IdPoliza,$Movto,$IdMov,$Cuenta,$TipoMovto,$Importe,$Referencia,$Concepto,$Nuevo,$Segmento,$Sucursal,$Factura,$FormaPago,$Sel_Multiple,$tipocambio)
		{
			if(!$tipocambio){ $tipocambio=0.0000;}
			$success=false;
			if($Nuevo)
			{
				//Inserta el nuevo registro
				$myQuery 	= "INSERT INTO cont_movimientos(IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,MultipleFacturas,FormaPago,tipocambio) VALUES($IdPoliza,$Movto,$Segmento,$Sucursal,$Cuenta,'$TipoMovto',$Importe,'$Referencia','$Concepto',1,DATE_SUB(NOW(), INTERVAL 6 HOUR),'$Factura',$Sel_Multiple,$FormaPago,$tipocambio)";
				
					if($insert_id = $this->insert_id($myQuery))
					{
						//Actualiza los numeros de todos movimientos superiores
						if($TipoMovto=="Cargo M.E." || $TipoMovto=="Abono M.E"){
						$myQuery2	= "UPDATE cont_movimientos SET NumMovto = NumMovto + 1 WHERE IdPoliza = $IdPoliza AND NumMovto >= $Movto AND (SELECT count(Id) FROM (SELECT Id FROM cont_movimientos where IdPoliza = $IdPoliza AND Activo = 1 AND NumMovto = $Movto) AS loco) > 1 AND Activo=1 AND Id != $insert_id";
						$this->query($myQuery2);
						}
						$ultima_modificacion = "UPDATE cont_polizas SET usuario_modificacion =".$_SESSION["accelog_idempleado"]." , fecha_modificacion=DATE_SUB(NOW(), INTERVAL 6 HOUR) WHERE id=$IdPoliza";
						$this->query($ultima_modificacion);
						$this->transaccion('Insertar movimiento',$myQuery);
						$success = true;
						
					}
				
			}
			else
			{
			//Actualiza los numeros de todos movimientos superiores
				$myQuery 	= "UPDATE cont_movimientos SET NumMovto = $Movto, IdSegmento = $Segmento, IdSucursal = $Sucursal, Cuenta = $Cuenta, TipoMovto = '$TipoMovto', Importe = $Importe, Referencia = '$Referencia', Concepto = '$Concepto', Factura = '$Factura', MultipleFacturas = $Sel_Multiple, FormaPago = $FormaPago, tipocambio = $tipocambio WHERE IdPoliza = $IdPoliza AND Id = $IdMov";
				$myQuery2	= "UPDATE cont_movimientos SET NumMovto = NumMovto + 1 WHERE IdPoliza = $IdPoliza AND NumMovto >= $Movto AND (SELECT count(Id) FROM (SELECT Id FROM cont_movimientos where IdPoliza = $IdPoliza AND Activo = 1 AND NumMovto = $Movto) AS loco) > 1 AND Id != $IdMov AND Activo=1";
				
					if($this->query($myQuery))
					{	if($TipoMovto=="Cargo M.E." || $TipoMovto=="Abono M.E"){
						$this->query($myQuery2);
						}
						$success = true;
						$ultima_modificacion = "UPDATE cont_polizas SET usuario_modificacion =".$_SESSION["accelog_idempleado"]." , fecha_modificacion=DATE_SUB(NOW(), INTERVAL 6 HOUR) WHERE id=$IdPoliza";
						$this->query($ultima_modificacion);
						$this->transaccion('Actualizar movimiento',$myQuery);
					}
				
			}

			return $success;

		}

		function BorraGrupoFacturas($Poliza,$Movto)
		{
			//Borra registros anteriores
			$myQuery = "DELETE FROM cont_grupo_facturas WHERE IdPoliza = $Poliza AND NumMovimiento = $Movto";
			$this->query($myQuery);
		}

		function GrupoFacturas($Facturas,$Poliza,$Movto)
		{
			//Borra registros anteriores
			$myQuery = "DELETE FROM cont_grupo_facturas WHERE IdPoliza = $Poliza AND NumMovimiento = $Movto";
			$this->query($myQuery);

			//Inserta los nuevos.
			$myQuery = 'INSERT INTO cont_grupo_facturas VALUES';
			for($i=0;$i<=count($Facturas)-1;$i++)
			{
				if($Facturas[$i] != '-')
				{
					if($i>0) $myQuery .= ",";
					$myQuery .= "($Poliza,$Movto,'".$Facturas[$i]."','0011')";
				}
			}
			$this->multi_query($myQuery);
		}

		function MultiplesFacturas($Poliza,$NumMovto)
		{
			$myQuery = "SELECT* FROM cont_grupo_facturas WHERE IdPoliza = $Poliza AND NumMovimiento = $NumMovto";
			$resultados = $this->query($myQuery);
			return $resultados;

		}

		function NumMovs($IdPoliza)
		{
			$myQuery = "SELECT m.Id,m.NumMovto,a.description AS Cuenta,m.TipoMovto,m.Importe,m.Concepto,manual_code,(select (case m.formapago when 0 then 0 else p.nombre end) from forma_pago p where p.idFormapago=m.formapago) as pago,a.account_id,
			(select (case m.IdSegmento when 0 then 0 else s.nombre end) from cont_segmentos s where s.idSuc=m.IdSegmento) as seg,m.Referencia,tipocambio
			FROM cont_movimientos m LEFT JOIN cont_accounts a ON a.account_id = m.Cuenta WHERE m.Activo=1 AND m.IdPoliza=".$IdPoliza." ORDER BY m.NumMovto ASC";
			$Movimientos = $this->query($myQuery);
			return $Movimientos;
		}

		function MovFacturas($IdPoliza)
		{
			/*$myQuery = "(SELECT m.NumMovto, m.Factura, 1 AS Activo FROM cont_movimientos m WHERE m.IdPoliza = $IdPoliza AND m.Factura != '' AND m.Factura != '-' AND m.Factura != '0' AND m.Activo = 1) 
			UNION 
			(SELECT g.NumMovimiento AS NumMovto, g.Factura, (SELECT Activo FROM cont_movimientos WHERE IdPoliza = $IdPoliza AND NumMovto = g.NumMovimiento AND Activo = 1) AS Activo FROM cont_grupo_facturas g WHERE g.IdPoliza = $IdPoliza HAVING Activo=1) ORDER BY NumMovto ";*/
			$myQuery = "SELECT g.NumMovimiento AS NumMovto, g.Factura, (SELECT Activo FROM cont_movimientos WHERE IdPoliza = $IdPoliza AND NumMovto = g.NumMovimiento AND Activo = 1 AND TipoMovto not like '%M.E%') AS Activo FROM cont_grupo_facturas g WHERE g.IdPoliza = $IdPoliza HAVING Activo=1 ORDER BY NumMovto ";
			$result = $this->query($myQuery);
			return $result;
		}

		function GetAbonosCargos($tipo,$por,$PeriodoActual,$IdEx,$conf=null)
		{
			if($por == 'Periodo')
			{
				$confirm = "";
				if(intval($this->confirm()))
					if($conf)
						$confirm = "AND (p.confirmado1 = 0 OR p.confirmado2 = 0)";
					else
						$confirm = "AND p.confirmado1 = 1 AND p.confirmado2 = 1";
				$where = "AND p.activo = 1 AND m.Activo = 1 AND p.idperiodo=$PeriodoActual $confirm";
			}
			if($por == 'Poliza')
			{
				$where = "AND m.Activo = 1 AND m.IdPoliza=$PeriodoActual";
			}

			$myQuery = "SELECT SUM(m.Importe) AS Cantidad FROM cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza WHERE p.idejercicio = $IdEx AND m.TipoMovto='$tipo' ".$where;
			$AbonosCargos = $this->query($myQuery);
			$AC = $AbonosCargos->fetch_assoc();
			return $AC;
		}

		function ActMovActivo($id)
		{
			$myQuery = "UPDATE cont_movimientos SET Activo=0 WHERE Id=".$id;
			$this->query($myQuery);
			$ultima_modificacion = "UPDATE cont_polizas SET usuario_modificacion =".$_SESSION["accelog_idempleado"]." , fecha_modificacion=DATE_SUB(NOW(), INTERVAL 6 HOUR) WHERE id=(SELECT IdPoliza FROM cont_movimientos WHERE Id = $id)";
			$this->query($ultima_modificacion);
			$this->transaccion('Desactivar movimiento',$myQuery);
		}

		function InicioEjercicio()
		{
			$myQuery = "SELECT InicioEjercicio FROM cont_config WHERE Id=1";
			$InicioEjercicio = $this->query($myQuery);
			$IE = $InicioEjercicio->fetch_assoc();
			return $IE;
		}

		function CambioPeriodo($Periodo,$NameEjercicio)
		{
			$myQuery = "UPDATE cont_config SET PeriodoActual=$Periodo WHERE EjercicioActual=$NameEjercicio";
			$InicioEjercicio = $this->query($myQuery);
		}

		function CambioEjercicio($Ejercicio)
		{
			$myQuery = "UPDATE cont_config SET EjercicioActual=$Ejercicio, InicioEjercicio = '$Ejercicio-01-01',FinEjercicio = '$Ejercicio-12-31' WHERE id=1";
			$InicioEjercicio = $this->query($myQuery);
		}

		function getFirstLastExercise($t)
		{
			if(intval($t))
			{
				$acomodo = 'DESC';
			}
			else
			{
				$acomodo = 'ASC';	
			}
			$myQuery = "SELECT NombreEjercicio FROM cont_ejercicios ORDER BY NombreEjercicio $acomodo LIMIT 1";
			$InicioEjercicio = $this->query($myQuery);
			$InicioEjercicio = $InicioEjercicio->fetch_assoc();
			return $InicioEjercicio['NombreEjercicio'];

		}

		function GetAllPolizaInfo($id)
		{
			$myQuery = "SELECT* FROM cont_polizas WHERE id=$id";
			$GetAllPolizaInfo = $this->query($myQuery);
			$GPI = $GetAllPolizaInfo->fetch_assoc();
			return $GPI;
		}
		function GetAllPolizaInfoActiva($id)
		{
			$GetAllPolizaInfo =$this->query( "SELECT * FROM cont_polizas WHERE id=$id and activo=1");
			if($GetAllPolizaInfo->num_rows>0){
				$GPI = $GetAllPolizaInfo->fetch_assoc();
			}else{
				 $GPI=0;
			}
			return $GPI;
			
		}

		function UltimoMov($IdPoliza)
		{
			$myQuery = "SELECT NumMovto FROM cont_movimientos WHERE IdPoliza=$IdPoliza AND Activo = 1 ORDER BY NumMovto DESC LIMIT 1";
			$UltimoMov = $this->query($myQuery);
			$UM = $UltimoMov->fetch_assoc();
			return $UM['NumMovto'];
		}

		function DatosMov($Id)
		{
			$myQuery = "SELECT * FROM cont_movimientos WHERE Id=$Id";
			$UltimoMov = $this->query($myQuery);
			$UM = $UltimoMov->fetch_assoc();
			return $UM;	
		}

		function ListaTiposPoliza()
		{
			$myQuery = "SELECT* FROM cont_tipos_poliza";
			$ListaTiposPoliza = $this->query($myQuery);
			return $ListaTiposPoliza;		
		}

		function ListaSegmentos()
		{
			$myQuery = "SELECT* FROM cont_segmentos";
			$ListaSucursales = $this->query($myQuery);
			return $ListaSucursales;		
		}

		function ListaSucursales()
		{
			$myQuery = "SELECT idSuc,nombre FROM mrp_sucursal";
			$ListaSucursales = $this->query($myQuery);
			return $ListaSucursales;		
		}

		function getAccounts($code,$res)
		{
			

			if(intval($res))
			{
				$father = $this->CuentaSaldosConfigurado();
				$account_type = "AND main_father = $father";
			}
			$myQuery = "SELECT account_id, description, ".$code." FROM cont_accounts 
			WHERE status=1 AND removed=0 AND affectable=1 $account_type AND account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0)";

			$ListaCuentas = $this->query($myQuery);
			return $ListaCuentas;	
		}

		function ListaPolizasEliminadas($n)
		{
			$EjercicioActual = $this->query("SELECT EjercicioActual FROM cont_config WHERE id=1");
			$EjercicioActual = $EjercicioActual->fetch_assoc();
			$myQuery = "SELECT p.id,p.concepto,p.referencia,p.fecha,e.NombreEjercicio,p.idperiodo,p.numpol FROM cont_polizas p INNER JOIN cont_ejercicios e ON e.Id = p.idejercicio AND e.NombreEjercicio='".$EjercicioActual['EjercicioActual']."' WHERE p.activo=0 AND p.eliminado=0 AND p.pdv_aut=$n AND e.Cerrado=0 ORDER BY p.idperiodo,p.numpol";
			$ListaPolizasEliminadas = $this->query($myQuery);
			//echo $myQuery;
			return $ListaPolizasEliminadas;	
		}

		function conectado()
		{
			$conPdv = $this->query("SELECT conectar FROM cont_config_pdv WHERE id=1");
			$conPdv = $conPdv->fetch_assoc();
			return $conPdv['conectar'];
		}

		function RestaurarPoliza($IdPoliza,$PDV)
		{
			$ids = explode(',',$IdPoliza);
			for($i=0;$i<=count($ids)-1;$i++)
			{
				$numpol = $this->getNumpol($ids[$i]);
				$myQuery = "UPDATE cont_polizas SET activo = 1, pdv_aut = 0, numpol = $numpol WHERE id = ".$ids[$i];
				$this->query($myQuery);
				if(!$PDV)
				{
					$myQuery = "UPDATE cont_movimientos SET Activo = 1 WHERE IdPoliza = ".$ids[$i];
					$this->query($myQuery);
				}	
			}
		}

		function EliminarPoliza($IdPoliza)
		{
			$ids = explode(',',$IdPoliza);
			for($i=0;$i<=count($ids)-1;$i++)
			{
				if($i==0)
				{
					$where = "id = ".$ids[$i];
				}
				else
				{
					$where .= " OR id = ".$ids[$i];
				}
			}
			$myQuery = "UPDATE cont_polizas SET eliminado=1,usuario_modificacion =".$_SESSION["accelog_idempleado"]." , fecha_modificacion=DATE_SUB(NOW(), INTERVAL 6 HOUR) WHERE $where";
			$this->query($myQuery);
		}

		function getNumpol($IdPoliza)
		{
			$myQuery = "SELECT 
p.idperiodo,
p.idejercicio,
p.numpol, 
p.idtipopoliza,
(SELECT id FROM cont_polizas WHERE activo=1 AND idejercicio = p.idejercicio AND idperiodo = p.idperiodo AND idtipopoliza = p.idtipopoliza AND numpol = p.numpol AND id != p.id) AS Existe,
(SELECT numpol FROM cont_polizas WHERE activo=1 AND idejercicio = p.idejercicio AND idperiodo = p.idperiodo AND idtipopoliza = p.idtipopoliza ORDER BY numpol DESC LIMIT 1) AS Ultimo
FROM cont_polizas p 
WHERE p.id=$IdPoliza";
			$numpol = $this->query($myQuery);
			$numpol = $numpol->fetch_assoc();

			$ultimo = intval($numpol['Ultimo']);
			if(is_null($numpol['Existe']))
			{
				return $numpol['numpol'];
			}
			else
			{
				return $ultimo + 1;
			}


		}

		function MovimientosPolizasEliminadas($IdPoliza)
		{
			$myQuery = "SELECT m.Id,m.NumMovto,a.description AS Cuenta,m.TipoMovto,m.Importe,m.Concepto,m.Activo,m.Referencia,m.Persona FROM cont_movimientos m INNER JOIN cont_accounts a ON a.account_id = m.Cuenta WHERE m.IdPoliza=".$IdPoliza;
			$Movs = $this->query($myQuery);
			return $Movs;
		}

		function CuentaTipoCaptura()
		{
			$myQuery = "SELECT Estructura,TipoNiveles FROM cont_config";
			$valor = $this->query($myQuery);
			$valor = $valor->fetch_assoc();
			if(strtolower($valor['TipoNiveles']) == 'a') $resultado = 'account_code';
			if(strtolower($valor['TipoNiveles']) == 'm') $resultado = 'manual_code';

			return $resultado;
		}

		function MovimientosCierreEjercicio($numPoliza,$IdEx,$NombreEjercicio)
		{

			$myQuery = "UPDATE cont_polizas SET activo=0 WHERE idejercicio = $IdEx AND idperiodo = 13;";
			$this->query($myQuery);

			$myQuery = "UPDATE cont_movimientos SET Activo=0 WHERE IdPoliza IN (SELECT id FROM cont_polizas WHERE idejercicio = $IdEx AND idperiodo = 13);";
			$this->query($myQuery);


			//$myQuery = "SELECT * FROM cont_view_init_balance2 b WHERE (b.Code LIKE '4.1%' OR b.Code LIKE '4.2%') AND Fecha BETWEEN '$NombreEjercicio-01-01' AND '$NombreEjercicio-12-31'";
			$myQuery = "SELECT b.* , SUM(b.Cargos) AS SumCargos, SUM(b.Abonos) AS SumAbonos  FROM cont_view_init_balance2 b WHERE (b.Code LIKE '4.1%' OR b.Code LIKE '4.2%') AND Fecha BETWEEN '$NombreEjercicio-01-01' AND '$NombreEjercicio-12-31' GROUP BY b.Code,b.idsegmento,b.idsucursal";

			$Movs = $this->query($myQuery);
			$contador=0;
			
			while($Movimientos = $Movs->fetch_assoc())
			{
				$contador++;
				$Tipo=0;
				$Cantidad=0;
				

				$res = $Movimientos['SumCargos'] - $Movimientos['SumAbonos'];
				if($res>0)
				{
					$Tipo = 'Abono';
					$Cantidad = $res;
				}

				if($res<0)
				{
					$Tipo = 'Cargo';
					$Cantidad = $res*-1;
				}


				$myQuery2="INSERT INTO cont_movimientos (IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Concepto,Activo,FechaCreacion) 
						VALUES ($numPoliza,$contador,".$Movimientos['idsegmento'].",".$Movimientos['idsucursal'].",(SELECT account_id FROM cont_accounts WHERE account_code='".$Movimientos['Code']."' AND removed = 0),'$Tipo',$Cantidad,'Poliza de Cierre',1,DATE_SUB(NOW(), INTERVAL 6 HOUR))";
			
				$this->query($myQuery2);

			}
			$idEjercicio = $this->getExerciseId($NombreEjercicio);

			//Lista de segmentos
			$segmentos = $this->ListaSegmentos();

			while($nseg = $segmentos->fetch_object())
			{
				//Lista de sucursales
				$sucursales = $this->ListaSucursales();
				while($nsuc = $sucursales->fetch_object())
				{
					$Importe = $this->total13($idEjercicio,'Cargo',$numPoliza,$nseg->idSuc,$nsuc->idSuc) - $this->total13($idEjercicio,'Abono',$numPoliza,$nseg->idSuc,$nsuc->idSuc);
					if(floatval($Importe)!=0)
					{
						//Guardar saldo en cuenta de capital
					
						if(floatval($Importe) > 0)
							{
								$Tipo = 'Abono';
							}
						if(floatval($Importe) < 0)
							{
								$Tipo = 'Cargo';
								$Importe = $Importe*-1;
							}	
							//echo "Importe: ".$Importe."Cargos :".$this->total13($idEjercicio,'Cargo',$numPoliza)."Abonos: ".$this->total13($idEjercicio,'Abono',$numPoliza);
						if(floatval($Importe) != 0)
							{
								$myQuery2="INSERT INTO cont_movimientos (IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Concepto,Activo,FechaCreacion) 
											VALUES ($numPoliza,$contador+1,".$nseg->idSuc.",".$nsuc->idSuc.",".$_SESSION['saldos'].",'$Tipo',$Importe,'Poliza de Cierre (Saldo)',1,DATE_SUB(NOW(), INTERVAL 6 HOUR))";
							
									$this->query($myQuery2);
									$contador++;
							}	
					}
				}

			}
		}

		function ActivaMovPDV($Id,$Activo)
		{
			$myQuery = "UPDATE cont_movimientos SET Activo=$Activo WHERE Id=$Id";
			$this->query($myQuery);
		}

		function savePolizaPDV($Fecha,$IdEmp,$Activar,$Tipo)
		{
				$fecha_amd = explode('-',$Fecha);
				$periodo = intval($fecha_amd[1]);
				$myQuery = "INSERT INTO cont_polizas(idorganizacion,idejercicio,idperiodo,idtipopoliza,referencia,concepto,fecha,fecha_creacion,activo,eliminado,pdv_aut) 
							VALUES(1,
								(SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '$fecha_amd[0]'),
								$periodo,
								$Tipo,
								(SELECT nombreusuario FROM administracion_usuarios WHERE idadmin = $IdEmp-1),
								'Corte de caja PDV','$Fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR),$Activar,0,1)";
				$this->query($myQuery);
				$ultPol = $this->getLastNumPoliza();
				return  $ultPol['id'];
		}

		function updatePolizaPDV($IdPoliza,$Activar)
		{
			$this->query("UPDATE cont_polizas SET eliminado = $Activar WHERE id = $IdPoliza");
		}

		function getMovementsPDV($myQuery)
		{
			$Movements = $this->query($myQuery);
			return $Movements;
		}
				 
		function InsertMovPDV($IdPoliza,$Contador,$idVenta,$Monto,$IdCliente,$Activar,$Referencia,$CargoAbono,$Cuenta)
		{

				/*if($es_impuesto)
				{
					$Cuenta=7;
				}
				else
				{	
					$Cuenta=78;
				}*/
			$Monto = number_format($Monto,2,'.','');
			$myQuery = "INSERT INTO cont_movimientos(
				IdPoliza,
				NumMovto,
				IdSucursal,
				Cuenta,
				TipoMovto,
				Importe,
				Referencia,
				Concepto,
				Activo,
				FechaCreacion,
				Persona) VALUES(
				$IdPoliza,
				$Contador,
				(SELECT idSucursal FROM venta WHERE idVenta = $idVenta),
				$Cuenta,
				'$CargoAbono',
				$Monto,
				'$Referencia',
				'Venta PDV ($idVenta)',
				$Activar,
				DATE_SUB(NOW(), INTERVAL 6 HOUR),
				'1-$IdCliente')";
			$this->query($myQuery);
		}
		function GetNamePerson($Person)
		{
			$Per = explode('-',$Person);
			
			if($Per[1] == 0)
			{
				return 'Incognito';
			}
			switch($Per[0])
			{
				case 1: $tabla = 'comun_cliente';break;
				case 2: $tabla = 'proveedor';break;	
			}
			
			$myQuery = "SELECT nombre FROM $tabla WHERE id=$Per[1]";
			$NameCustomer = $this->query($myQuery);
			$NC = $NameCustomer->fetch_assoc();
			return $NC['nombre'];
		}

		function IsActive()
		{
			$myQuery = "SELECT Id FROM cont_ejercicios LIMIT 1";
			$active = $this->query($myQuery);
			$active = $active->fetch_assoc();
			return $active['Id'];
		}
		public function getVentaValues($id)
		{
			$sql  = "SELECT ";
			$sql .= "	v.monto AS monto_venta, ";
			$sql .= "	v.montoimpuestos AS monto_iva, ";
			$sql .= "	c.idCliente, ";
			$sql .= "	cl.nombre, ";
			$sql .= "	v.idVenta ";
			$sql .= "FROM ";
			$sql .= "	cxc c ";
			$sql .= "	INNER JOIN venta v ON c.idVenta = v.idVenta ";
			$sql .= "	INNER JOIN comun_cliente cl ON c.idCliente = cl.id ";
			$sql .= "WHERE ";
			$sql .= "c.idCxc = " . $id . ";";
			$result = $this->query($sql);
			$result = $result->fetch_assoc();
			return $result;
		}
		public function savePolizaCxC( $Fecha, $IdEmp, $Activar,$tipoPoliza )
		{
			$fecha_amd = explode('-',$Fecha);
			$periodo = intval($fecha_amd[1]);
			$sql  = "INSERT INTO ";
			$sql .= "cont_polizas ";
			$sql .= "( ";
			$sql .= "idorganizacion, ";
			$sql .= "idejercicio, ";
			$sql .= "idperiodo, ";
			$sql .= "idtipopoliza, ";
			$sql .= "referencia, ";
			$sql .= "concepto, ";
			$sql .= "fecha, ";
			$sql .= "fecha_creacion, ";
			$sql .= "activo, ";
			$sql .= "eliminado, ";
			$sql .= "pdv_aut ";
			$sql .= ") ";
			$sql .= "VALUES";
			$sql .= "( ";
			$sql .= "1, ";
			$sql .= "(SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '" . $fecha_amd[0] . "'), ";
			$sql .= $periodo . ", ";
			$sql .= $tipoPoliza . ", ";
			$sql .= "(SELECT nombreusuario FROM administracion_usuarios WHERE idadmin = " . ($IdEmp-1) . "), ";
			$sql .= "'Cuentas Por Cobrar PDV', ";
			$sql .= "'" . $Fecha . "', ";
			$sql .= "NOW(), ";
			$sql .= $Activar . ", ";
			$sql .= "0, ";
			$sql .= "1 ";
			$sql .= ");";
			$this->query( $sql );
			$ultPol = $this->getLastNumPoliza();
			return  $ultPol['id'];
		}
		function InsertMovCxC( $IdPoliza, $Contador, $idVenta, $Monto, $IdCliente, $Activar, $Referencia, $CargoAbono, $Cuenta )
		{

			// Inicia seccion de cuentas por default
				$CuentaClientes = 6;//Cuenta de clientes por default
				$CuentaVentas = 78;//Cuenta de ventas por default
				$CuentaIVA = 7;//Cuenta de IVA por default
				$CuentaIEPS = 0;//Cuenta de IEPS por default
				$CuentaCaja = 3;//Cuenta de Caja por default
				$CuentaTR = 80;//Cuenta de Tarjetas de regalo por default
				$CuentaBancos = 65;//Cuenta de Bancos por default

				$line = '';

				switch ($Cuenta) 
				{
					case 'Clientes':
						$line = "IF( (SELECT CuentaClientes FROM cont_config LIMIT 1) = -1, " . $CuentaClientes . ", (SELECT CuentaClientes FROM cont_config LIMIT 1) )";
						break;
					case 'Caja':
						$line = "IF( (SELECT CuentaVentas FROM cont_config LIMIT 1) = -1, " . $CuentaVentas . ", (SELECT CuentaVentas FROM cont_config LIMIT 1) )";
						break;
					case 'Banco':
						$line = "IF( (SELECT CuentaBancos FROM cont_config LIMIT 1) = -1, " . $CuentaBancos . ", (SELECT CuentaBancos FROM cont_config LIMIT 1) )";
						break;
					case 'Tarjetas':
						$line = "IF( (SELECT CuentaTR FROM cont_config LIMIT 1) = -1, " . $CuentaTR . ", (SELECT CuentaTR FROM cont_config LIMIT 1) )";
						break;
				}
			// Termina seccion de cuentas por default

			$Monto = number_format($Monto,2,'.','');

			$sql  = "INSERT INTO cont_movimientos ";
			$sql .= "( ";
			$sql .= "	IdPoliza, ";
			$sql .= "	NumMovto, ";
			$sql .= "	IdSucursal, ";
			$sql .= "	Cuenta, ";
			$sql .= "	TipoMovto, ";
			$sql .= "	Importe, ";
			$sql .= "	Referencia, ";
			$sql .= "	Concepto, ";
			$sql .= "	Activo, ";
			$sql .= "	FechaCreacion, ";
			$sql .= "	Persona ";
			$sql .= ") ";
			$sql .= "	VALUES ";
			$sql .= "( ";
			$sql .= "	" . $IdPoliza . ", ";
			$sql .= "	" . $Contador . ", ";
			$sql .= "	(SELECT idSucursal FROM venta WHERE idVenta = $idVenta), ";
			$sql .= "	" . $line . ", ";
			$sql .= "	'" . $CargoAbono . "', ";
			$sql .= "	" . $Monto . ", ";
			$sql .= "	'" . $Referencia . "', ";
			$sql .= "	'Venta CxC (" . $idVenta . ")', ";
			$sql .= "	" . $Activar . ", ";
			$sql .= "	NOW(), ";
			$sql .= "	'" . ($IdCliente - 1) . "' ";
			$sql .= ");";

			$this->query( $sql );
		}

		function getAccountsConfig()
		{
			$Cuentas = $this->query("SELECT CuentaClientes, CuentaVentas, CuentaIVA, CuentaCaja, CuentaTR, CuentaBancos FROM cont_config WHERE id = 1");
			$Cuentas = $Cuentas->fetch_assoc();
			if($Cuentas == '')
			{
				$Cuentas = array('CuentaClientes' => -1, 'CuentaVentas' => -1, 'CuentaIVA' => -1, 'CuentaCaja' => -1, 'CuentaTR' => -1, 'CuentaBancos' => -1);
			}
			return $Cuentas;
		}

		public function getProviders()
		{
			$myQuery = "SELECT idPrv,razon_social FROM mrp_proveedor order by razon_social asc";
			$Providers = $this->query($myQuery);
			return $Providers;
		}

		public function getProviderList($idPoliza)
		{
			$myQuery = "SELECT pp.id, p.idPrv, p.razon_social, pp.ivaRetenido, pp.isrRetenido, pp.importe, pp.importeBase, (SELECT valor FROM cont_tasaPrv WHERE id=pp.tasa) AS tasa, pp.otrasErogaciones, pp.periodoAcreditamiento, pp.ejercicioAcreditamiento FROM mrp_proveedor p
			RIGHT JOIN cont_rel_pol_prov pp ON pp.idProveedor = p.idPrv WHERE pp.idPoliza = $idPoliza AND pp.activo = 1 ORDER BY p.razon_social ASC";
			$Providers = $this->query($myQuery);
			return $Providers;
		}

		public function getProviderInfo($IdPrv)
		{
			$myQuery = "SELECT p.ivaretenido, p.isretenido, p.idTasaPrvasumir FROM mrp_proveedor p WHERE p.idPrv=".$IdPrv;
			$ProvidersInfo = $this->query($myQuery);
			$ProvidersInfo = $ProvidersInfo->fetch_assoc();
			return $ProvidersInfo;
		}

		public function getProviderTax($IdPrv)
		{
			$myQuery = "SELECT id,tasa,valor FROM cont_tasaPrv WHERE idPrv=".$IdPrv." ORDER BY valor DESC";
			$ProvidersTax = $this->query($myQuery);
			return $ProvidersTax;
		}

		public function getProviderTaxDefault($IdPrv)
		{
			$myQuery = "SELECT tp.valor AS Valor, tp.tasa AS Tasa FROM cont_tasaPrv tp INNER JOIN mrp_proveedor p ON p.idTasaPrvasumir=tp.id WHERE tp.idPrv=".$IdPrv;
			$idTax = $this->query($myQuery);
			$idTax = $idTax->fetch_assoc();
			return $idTax;
		}

		public function getProviderTaxDefaultSaved($Id)
		{
			$myQuery = "SELECT t.valor AS Valor, t.tasa AS Tasa FROM cont_tasaPrv t INNER JOIN cont_rel_pol_prov r ON r.tasa = t.id WHERE r.id=".$Id;
			$idTax = $this->query($myQuery);
			$idTax = $idTax->fetch_assoc();
			return $idTax;
		}

		public function CuentaSaldosConfigurado()
		{
			$myQuery = "SELECT CuentaSaldos FROM cont_config";
			$saldos = $this->query($myQuery);
			$saldos = $saldos->fetch_assoc();
			return $saldos['CuentaSaldos'];
		}

		public function CuentaSaldos()
		{
			$myQuery = "SELECT account_id, description, manual_code FROM cont_accounts WHERE removed = 0 AND main_father = ".$this->CuentaSaldosConfigurado();
			return $this->query($myQuery);
		}

		public function ActualizaProveedores($Idr,$Poliza,$IdPrv,$Referencia,$Tasa,$Importe,$ImporteBase,$OtrasErogaciones,$IVARetenido,$ISRRetenido,$IvaPagadoNoAcreditable,$Aplica,$Ejercicio,$ietu,$acreditaietu,$periodoAcreditamiento=0)
		{
			$myQuery = "UPDATE cont_rel_pol_prov SET 
			idProveedor = $IdPrv, 
			referencia = '$Referencia', 
			tasa = $Tasa, 
			importe = $Importe, 
			importeBase = $ImporteBase, 
			otrasErogaciones = $OtrasErogaciones,
			ivaRetenido = $IVARetenido,
			isrRetenido = $ISRRetenido,
			ivaPagadoNoAcreditable = $IvaPagadoNoAcreditable,
			aplica = $Aplica,
			idietu = $ietu,
			acreditableIETU = $acreditaietu,
			periodoAcreditamiento = $periodoAcreditamiento
			WHERE id = $Idr";
			$this->query($myQuery);
		}

		public function InsertaProveedores($Idr,$Poliza,$IdPrv,$Referencia,$Tasa,$Importe,$ImporteBase,$OtrasErogaciones,$IVARetenido,$IsrRetenido,$IvaPagadoNoAcreditable,$Aplica,$Ejercicio,$ietu,$acreditaietu,$periodoAcreditamiento=0)
		{
			$myQuery = "INSERT INTO cont_rel_pol_prov(idPoliza,idProveedor,referencia,tasa,importe,importeBase,otrasErogaciones,ivaRetenido,isrRetenido,ivaPagadoNoAcreditable,aplica,activo,ejercicioAcreditamiento,idietu,acreditableIETU,periodoAcreditamiento) 
			VALUES($Poliza,$IdPrv,'$Referencia',$Tasa,$Importe,$ImporteBase,$OtrasErogaciones,$IVARetenido,$IsrRetenido,$IvaPagadoNoAcreditable,$Aplica,1,$Ejercicio,$ietu,$acreditaietu,$periodoAcreditamiento)";
			$this->query($myQuery);
		}

		public function getProviderAllInfo($Idr)
		{
			$myQuery = "SELECT * FROM cont_rel_pol_prov WHERE id = $Idr";
			$Apply = $this->query($myQuery);
			return $Apply;
		}

		public function eliminaProv($Id)
		{
			$myQuery = "UPDATE cont_rel_pol_prov SET activo = 0 WHERE id=$Id";
			$this->query($myQuery);
		}

		public function actualizaPeriodoAcreditamiento($IdPoliza, $NuevoPeriodo, $NuevoEjercicio)
		{
			$myQuery = "UPDATE cont_rel_pol_prov SET periodoAcreditamiento = $NuevoPeriodo, ejercicioAcreditamiento = $NuevoEjercicio WHERE idPoliza=$IdPoliza";
			$this->query($myQuery);
		}

		public function total13($idEjercicio,$TipoMovto,$numPoliza,$segmento,$sucursal)
		{
			$myQuery = "SELECT 
SUM(m.Importe) AS Imp
FROM cont_movimientos m
INNER JOIN cont_polizas p ON p.id = m.IdPoliza
WHERE 
m.TipoMovto = '$TipoMovto' 
AND p.idperiodo = 13
AND p.idejercicio = $idEjercicio
AND p.id = $numPoliza
AND m.Activo = 1
AND m.IdSegmento = $segmento 
AND m.IdSucursal = $sucursal;";
			$res = $this->query($myQuery);
			$res = $res->fetch_array();
			//echo $myQuery;
			return $res['Imp'];
		}

		public function getExerciseId($NombreEjercicio)
		{
			$myQuery = "SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '$NombreEjercicio'";
			$name = $this->query($myQuery);
			$name = $name->fetch_array();
			return $name['Id'];
		}

		public function getCausacionData($IdPoliza)
		{
			$myQuery = "SELECT * FROM cont_rel_desglose_iva WHERE idPoliza = $IdPoliza";
			$data = $this->query($myQuery);
			$data = $data->fetch_row();
			return $data;
		}

		public function guardaCausacion($vars)
		{
			
			$iva = explode('-',$vars['ivaRetenido']);
			$isr = explode('-',$vars['isrRetenido']);

			if($vars['Existe'])
			{
				 if(intval($vars['nombreejer'])<2014){//ganzo
					$acreditableietu = $vars['acreditaietu'];
					$tipoietu = $vars['tipoietu'];				
				 }else{
				 	$acreditableietu = 0;
					$tipoietu = 0;	
				 }
				$myQuery = "UPDATE cont_rel_desglose_iva 
							SET tasa16 =  '".$vars['tasa16']."',
							tasa11 =  '".$vars['tasa11']."',
							tasa0 =  '".$vars['tasa0']."',
							tasaExenta =  '".$vars['tasaExenta']."',
							tasa15 =  '".$vars['tasa15']."',
							tasa10 =  '".$vars['tasa10']."',
							otrasTasas =  '".$vars['tasaOtras']."',
							ivaRetenido =  ".$iva[0].",
							isrRetenido =  ".$isr[0].",
							otros =  ".$vars['otros'].",
							aplica =  ".$vars['aplica'].",
							periodoAcreditamiento =  ".$vars['periodoAc'].",
							ejercicioAcreditamiento =  ".$vars['ejercicioAc'].",
							acreditableIETU = ".$vars['acreditaietu'].",
							conceptoIETU = ".$vars['tipoietu']."
							WHERE idPoliza = ".$vars['IdPoliza'];
			}
			else
			{
				$myQuery = "INSERT INTO cont_rel_desglose_iva(idPoliza,tasa16,tasa11,tasa0,tasaExenta,tasa15,tasa10,otrasTasas,ivaRetenido,isrRetenido,otros,aplica,periodoAcreditamiento,ejercicioAcreditamiento,acreditableIETU,conceptoIETU)
														VALUES(	".$vars['IdPoliza'].",
																'".$vars['tasa16']."',
																'".$vars['tasa11']."',
																'".$vars['tasa0']."',
																'".$vars['tasaExenta']."',
																'".$vars['tasa15']."',
																'".$vars['tasa10']."',
																'".$vars['tasaOtras']."',
																".$iva[0].",
																".$isr[0].",
																".$vars['otros'].",
																".$vars['aplica'].",
																".$vars['periodoAc'].",
																".$vars['ejercicioAc'].",
																".$vars['acreditaietu'].",
																".$vars['tipoietu'].")";
			}
			
			$data = $this->query($myQuery);
			return $data;
		}
		public function getCargosBancos($IdPoliza,$Tipo)
		{
			$myQuery = "SELECT SUM(m.Importe) AS Cargos
						FROM cont_movimientos m
						INNER JOIN cont_accounts a ON a.account_id = m.Cuenta
						WHERE m.IdPoliza = $IdPoliza AND m.TipoMovto = '$Tipo' AND m.Activo = 1 AND a.account_code LIKE CONCAT((SELECT account_code FROM cont_accounts RIGHT JOIN cont_config ON CuentaBancos = account_id), '%')";
			$importe = $this->query($myQuery);
			$importe = $importe->fetch_object();
			if($importe->Cargos === NULL)
			{
				return '0.00';
			}
			else
			{
				return $importe->Cargos;
			}
		}

		public function getAbonosBancos($IdPoliza)
		{
			$myQuery = "SELECT SUM(m.Importe) AS AbonosBancos
						FROM cont_movimientos m
						INNER JOIN cont_accounts a ON a.account_id = m.Cuenta
						WHERE m.IdPoliza = $IdPoliza AND m.TipoMovto = 'Abono' AND m.Activo = 1 AND a.account_code LIKE CONCAT((SELECT account_code FROM cont_accounts RIGHT JOIN cont_config ON CuentaBancos = account_id), '%')";
			$importe = $this->query($myQuery);
			$importe = $importe->fetch_object();
			if($importe->AbonosBancos === NULL)
			{
				return '0.00';
			}
			else
			{
				return $importe->AbonosBancos;
			}
		}

		public function sumaAbonosBancos($IdPoliza)
		{
			$myQuery = "SELECT SUM(m.Importe) AS AbonosBancos FROM cont_movimientos m WHERE m.IdPoliza = $IdPoliza AND m.Cuenta IN(SELECT account_id FROM cont_accounts WHERE main_father = (SELECT CuentaBancos FROM cont_config WHERE id=1))";
			$importe = $this->query($myQuery);
			$importe = $importe->fetch_object();
			return $importe->AbonosBancos;
		}

		public function exercisesList()
		{
			$myQuery = "SELECT Id,NombreEjercicio FROM cont_ejercicios";
			return $this->query($myQuery);
		}
		
		public function bancos(){
			// $sql=$this->query("select * from cont_accounts c where c.`father_account_id` =(select cu.father_account_id from cont_config co,cont_accounts cu where cu.account_id=co.CuentaBancos)");
			$sql=$this->query("select c.* from cont_accounts c,cont_config co where  c.affectable=1 and c.main_father = co.CuentaBancos || c.main_father = co.CuentaCaja");
			return $sql;
			
		}
		public function clientes1(){//
			//$sql=$this->query("SELECT account_id, description  FROM cont_accounts WHERE status=1 AND removed=0 AND affectable=1 AND father_account_id = (select c.father_account_id from cont_accounts c, cont_config co where c.account_id=co.CuentaClientes)"); hermanos
			$sql=$this->query("SELECT c.account_id, c.description,c.manual_code  FROM cont_accounts c,cont_config co WHERE c.status=1 AND c.currency_id=1 AND c.removed=0 AND c.affectable=1 AND c.main_father =co.CuentaClientes"); // hijos
			
			return $sql;
			
		}
		public function clientes(){
			//$sql=$this->query("select * from cont_accounts c, cont_config co where c.`father_account_id`=co.CuentaClientes");
			$sql=$this->query("select cli.id,cli.nombre from comun_cliente cli order by cli.nombre asc");
			
			return $sql;
			
		}
		public function conf(){
			$sql=$this->query("select co.CuentaClientes,co.CuentaProveedores,co.CuentaClientes from cont_config co");
			return $sql;
		}
		function savePoliza2($idorg,$ejercicio,$periodo,$tipo,$concepto,$fecha,$beneficiario,$numero,$rfc,$idbanco,$numtarjcuent,$bancoorigen,$tipobeneficiario=0)
		{
				$myQuery = "INSERT INTO cont_polizas(idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,concepto,fecha,fecha_creacion,activo,eliminado,beneficiario,numero,rfc,idbanco,numtarjcuent,idCuentaBancariaOrigen,tipoBeneficiario,usuario_creacion,usuario_modificacion,fecha_modificacion) VALUES($idorg,$ejercicio,$periodo,".($this->UltimoNumPol($periodo,$ejercicio,$tipo)+1).",".$tipo.",'".$concepto."','$fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR),1,0,$beneficiario,'$numero','$rfc',$idbanco,'$numtarjcuent',$bancoorigen,".intval($tipobeneficiario).",".$_SESSION['accelog_idempleado'].",".$_SESSION['accelog_idempleado'].",DATE_SUB(NOW(), INTERVAL 6 HOUR))";
				if(!$this->query($myQuery)){
					return 1;
				}else{
					$this->transaccion('Crea Poliza',$myQuery);
					 return 0;
				}
		}
		function savePolizaGasto($idorg,$ejercicio,$periodo,$tipo,$concepto,$fecha,$beneficiario,$numero,$rfc,$idbanco,$numtarjcuent,$bancoorigen,$idanticipo)
		{
				$myQuery = "INSERT INTO cont_polizas(idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,concepto,fecha,fecha_creacion,activo,eliminado,beneficiario,numero,rfc,idbanco,numtarjcuent,idCuentaBancariaOrigen,idAnticipo,usuario_creacion,usuario_modificacion,fecha_modificacion) VALUES($idorg,$ejercicio,$periodo,".($this->UltimoNumPol($periodo,$ejercicio,$tipo)+1).",".$tipo.",'".$concepto."','$fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR),1,0,$beneficiario,'$numero','$rfc',$idbanco,'$numtarjcuent',$bancoorigen,$idanticipo,".$_SESSION['accelog_idempleado'].",".$_SESSION['accelog_idempleado'].",DATE_SUB(NOW(), INTERVAL 6 HOUR))";
				if(!$this->query($myQuery)){
					return 1;
				}else{
					$this->transaccion('Crea Poliza Gasto',$myQuery);
					 return 0;
				}
		}
		function InsertMov2($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,$TipoMovto,$Importe,$concepto,$persona,$xml,$referencia,$fomapago)
		{

			$myQuery = "INSERT INTO cont_movimientos(IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona,FormaPago) VALUES($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,'$TipoMovto',$Importe,'$referencia','$concepto',1,DATE_SUB(NOW(), INTERVAL 6 HOUR),'$xml','".$persona."',$fomapago)";
			
			if($this->query($myQuery))
			{
				return true;
			}
			else
			{
				return false;
			}

		}
		function cuentaproviciones($like,$like2){
			if($like2){
				$sql=$this->query(" SELECT co.account_id, co.description,co.manual_code FROM cont_accounts co
			WHERE co.status=1 AND co.removed=0 AND co.affectable=1 AND ( co.account_code like '4.2%' || co.account_code like '1.1%' || co.account_code like '1.2%')
			and co.main_father!=(SELECT CuentaBancos FROM cont_config WHERE id=1) and co.main_father not in ((SELECT CuentaBancos FROM cont_config WHERE id=1),(SELECT CuentaCaja FROM cont_config WHERE id=1),
			(SELECT CuentaDeudores FROM cont_config WHERE id=1)	,(SELECT CuentaClientes FROM cont_config WHERE id=1) ) and
			co.account_id not in (
			(SELECT CuentaIVAPendientePago FROM cont_config WHERE id=1) ,(SELECT CuentaIVApagado FROM cont_config WHERE id=1),
			(SELECT CuentaIEPSpagado FROM cont_config WHERE id=1) ,(SELECT CuentaIEPSPendientePago FROM cont_config WHERE id=1))");
				return $sql;
			}else{
				$sql=$this->query(" SELECT co.account_id, co.description,co.manual_code FROM cont_accounts co
				WHERE co.status=1 AND co.removed=0 AND co.affectable=1 AND co.account_code like '".$like."%'");
				return $sql;
			}
		}
		function cuentaivas(){//acorde alo dicho por isis en las cuentas de iva iran  solo las afectables de pasivo y activo
			$sql=$this->query(" SELECT co.account_id, co.description,co.manual_code
			 FROM cont_accounts co
			 WHERE co.status=1 
			 AND co.removed=0 
			 AND co.affectable=1 
			 AND co.account_code like '1%' 
			 union SELECT co.account_id, 
			 co.description,co.manual_code FROM cont_accounts co 
			 WHERE co.status=1 AND co.removed=0 
			 AND co.affectable=1 AND co.account_code like '2%'");
			 return $sql;
		}
		function cuentasivaegre(){//solo activo circulante
			$sql=$this->query("SELECT co.account_id, 
			 co.description,co.manual_code FROM cont_accounts co 
			 WHERE co.status=1 AND co.removed=0 
			 AND co.affectable=1 AND co.account_code like '1.1%'");
			 return $sql;
		}
		function iepsish(){
			$sql=$this->query("SELECT co.account_id, co.description,co.manual_code
			 FROM cont_accounts co
			 WHERE co.status=1 
			 AND co.removed=0 
			 AND co.affectable=1 
			 AND co.account_code like '1.1%' 
			 union SELECT co.account_id, 
			 co.description,co.manual_code FROM cont_accounts co 
			 WHERE co.status=1 AND co.removed=0 
			 AND co.affectable=1 AND co.account_code like '4.2%'");
			 return $sql;
		}
		function proveedor(){
			$sql=$this->query("SELECT cuenta,idPrv,razon_social FROM mrp_proveedor where cuenta!='' and cuenta!=0 and cuenta!='-1' order by razon_social asc ");
			return $sql;
		}
		
		function proveedor2(){//cuentas que no estan en el padron para mostrar
			$sql=$this->query("select cu.account_id,cu.description,cu.manual_code from cont_accounts cu,cont_config c where  cu.`affectable`=1 and cu.removed=0 and cu.status=1 and cu.account_id not in (
			SELECT cuenta FROM mrp_proveedor where cuenta!='' and cuenta!=0 and cuenta!='-1') and cu.main_father=(select c.CuentaProveedores from cont_config c WHERE id=1)");
			return $sql;
		}
		function proveedorparacaptura(){//todos los provee q esten en el padron antes estaba q solo los q tenias datos fiscales
			//$sql=$this->query("SELECT cuenta,idPrv,razon_social FROM mrp_proveedor where cuenta!='' and cuenta!=0 order by razon_social asc ");
			$sql=$this->query("SELECT cuenta,idPrv,razon_social FROM mrp_proveedor  order by razon_social asc ");
			return $sql;
		}
		function receptorcliente($rfc,$nombre){
			$sql=$this->query('select * from comun_cliente where rfc="'.$rfc.'" and nombre="'.$nombre.'"');
			return $sql;
		}
		function agregareceptorcliente($nombre,$direccion,$colonia,$cp,$idEstado,$idMunicipio,$rfc,$cuenta){
			if($rfc==""){ $rfc='XAXX010101000'; }
			$sql="insert into comun_cliente (nombre,direccion,colonia,cp,idEstado,idMunicipio,rfc,cuenta) values ('".$nombre."','".$direccion."','".$colonia."','".$cp."',".$idEstado." ,".$idMunicipio.",'".$rfc."',".$cuenta.");";
			if(!$this->query($sql)){
					return 1;
				}else{
					 return 0;
				}
		}
		function consultamuniesta($muni){
			$sql=$this->query('select idestado,idmunicipio from municipios where municipio="'.$muni.'"');
			if(!$sql){
				return 0;
			}else{
				if($e=$sql->fetch_array()){
					return $e['idestado'].'/'.$e['idmunicipio'];
				}
			}
		}
		function ultimo($opc){
			if($opc==1){
				$sql=$this->query("select id from comun_cliente  ORDER BY id DESC LIMIT 1");
				if($es=$sql->fetch_assoc()){
					return $es['id'];
				}
			}else if($opc==2){
				$sql=$this->query("select idPrv from mrp_proveedor  ORDER BY idPrv DESC LIMIT 1");
				if($es=$sql->fetch_assoc()){
					return $es['idPrv'];
				}
			}
			
		}
		
		function emisorprove($rfc,$razon_social){
			$sql=$this->query("select idPrv,cuenta from mrp_proveedor where rfc='".$rfc."'");
			return $sql;
		}
								//$nombreemisor,$rfcemisor, $calleemisor." ".$noExterioremisor,$idestado,$idmunicipio,$idcuentaproveedores);
		function agregaremisorprove($razon_social,$rfc,$domicilo,$idestado,$idmunicipio,$cuenta){
			if($rfc==""){$rfc='XAXX010101000';}
			$sql="insert into mrp_proveedor (razon_social,rfc,domicilio,idestado,idmunicipio,cuenta) values ('".$razon_social."','".$rfc."','".$domicilo."','".$idestado."','".$idmunicipio."',".$cuenta." );";
			if(!$this->query($sql)){
					return 1;
				}else{
					 return 0;
				}
		}
		function porvision(){
			$sql=$this->query("select Cuentaprovision from cont_config");
			if($es=$sql->fetch_assoc()){
					return $es['Cuentaprovision'];
				}
		}
		function cuentasprove(){//todas als cuentas d provee 
			$sql=$this->query("select cu.account_id,cu.description,cu.manual_code from cont_accounts cu,cont_config c where  cu.`affectable`=1 and cu.removed=0 and cu.status=1 and cu.currency_id=1 and cu.main_father=(select c.CuentaProveedores from cont_config co  where c.id=1)");
			return $sql;
		}
		function ietu(){
			$sql=$this->query("select * from cont_IETU");
			return $sql;
		}
		function ejercio(){
			$sql=$this->query("SELECT EjercicioActual FROM cont_config WHERE id=1");
			if($es=$sql->fetch_assoc()){
					return $es['EjercicioActual'];
				}
		}
		function consultaejer($ejer){
			$sql=$this->query("select NombreEjercicio from cont_ejercicios where id=".$ejer);
			if($es=$sql->fetch_assoc()){
					return $es['NombreEjercicio'];
				}
		}
		function idIETUprv($idpr,$opc,$poli){
			if($opc==0){
				$sql=$this->query("SELECT idIETU FROM mrp_proveedor where idPrv=".$idpr);
				if($sql->num_rows>0){
					if($es=$sql->fetch_assoc()){
						return $es['idIETU'];
					}
				}else{
					return 0;
				}
			}else{
				$sql=$this->query("select idietu,acreditableIETU from cont_rel_pol_prov where idProveedor=".$idpr." and idPoliza=".$poli);
				if($sql->num_rows>0){
					if($es=$sql->fetch_assoc()){
						return $es['idietu']."//".$es['acreditableIETU'];
					}
				}else{
					return 0;
				}
			}
			
		}
		
		function cuentaextr($idcuenta){
			$sql=$this->query("select m.description as moneda,c.description,m.coin_id from cont_accounts c,cont_coin m where m.coin_id=c.currency_id and account_id=".$idcuenta);
				if($es=$sql->fetch_assoc()){
					if($es['moneda']!="Peso Mexicano"){
						return ($es['moneda']."/".$es['coin_id']);
					}else{
						return 0;
					}
				}
		}
		
		function consulcambio($idmodena,$fecha){
			$sql=$this->query("select * from cont_tipo_cambio where fecha='$fecha' and moneda=".$idmodena);
			if($sql->num_rows>0){
				return $sql;
			}else{
				return "0";
			}
		}
		
		function consultaprovi($idcuenta,$periodo,$ejer){
			$sql=$this->query("select c.* 
from cont_movimientos m,cont_polizas p,cont_accounts c
where   m.cuenta=c.account_id and  c.currency_id!=1   and m.IdPoliza=p.id and p.idperiodo=".$periodo." and p.idtipopoliza=3 and p.idejercicio=".$ejer." and p.activo=1 and c.account_id=".$idcuenta);
			if($sql->num_rows>0){
				return 1;
			}else{
				return 0;
			}
			
		}
		
		function formapago(){
			$sql=$this->query("select * from forma_pago WHERE claveSat != '' ORDER BY claveSat");
			return $sql;
		}
		function consultaprovisiones($ejer,$cuenta,$poli,$peri,$polirelacion){
			// $sql=$this->query("select p.* from cont_polizas p,cont_movimientos m,cont_accounts c 
			// where p.idtipopoliza=3 and p.activo=1 and p.eliminado=0 and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
			// and m.IdPoliza=p.id and m.Cuenta=c.`account_id` and c.currency_id!=1 and p.relacionExt=0 
			// and p.idejercicio=".$ejer." and m.Cuenta=".$cuenta." and p.id!=".$poli." and p.idperiodo=".$peri." group by p.id
			// ");
			$sql=$this->query("select p.* from cont_polizas p,cont_movimientos m,cont_accounts c 
			where p.idtipopoliza=3 and p.activo=1 and p.eliminado=0 and p.concepto!='POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA'
			and m.IdPoliza=p.id and m.Cuenta=c.`account_id` and c.currency_id!=1 and p.relacionExt=0 and saldado=0  
			and p.idejercicio=".$ejer." and m.Cuenta=".$cuenta." and p.id!=".$poli." or p.id=".$polirelacion." group by p.id");
				return $sql;
			
		}
		function moviextranjeros($ejer,$poli){
			$sql=$this->query("select m.* from cont_polizas p,cont_movimientos m,cont_accounts c,cont_config con 
			where m.IdPoliza=p.id and m.Cuenta=c.`account_id` and c.currency_id!=1 and m.Activo=1 
			and p.idtipopoliza!=3  and p.idejercicio=".$ejer." and c.main_father!=con.CuentaBancos and p.id=".$poli);
			
				return $sql;
			
		}
		function validacuentaclientes($idcliente){
			$sql=$this->query("select cuenta from comun_cliente where id=".$idcliente);
			if($sql->num_rows>0){
					if($es=$sql->fetch_assoc()){
						if($es['cuenta']>0){
							return $es['cuenta'];
						}
						else{
							return 0;
						}
					}
			}
		}
		function actualizacliente($cuenta,$idcliente){
			$sql=$this->query("update comun_cliente set cuenta=".$cuenta."  where id=".$idcliente);
		}
		
		function consultacliente($rfc){
			$sql=$this->query("select * from comun_cliente where rfc='".$rfc."'");
			return $sql;
		}
		function actulizaprove($cuenta,$idprove){
			$sql=$this->query("update mrp_proveedor set cuenta=".$cuenta." where idPrv=".$idprove);
		}
		
		function verificaext($idmov){
			$sql=$this->query("select m.NumMovto,m.IdPoliza from cont_movimientos m,cont_accounts c where m.cuenta=c.account_id and c.`currency_id`!=1 and m.Id=".$idmov);
			if($sql->num_rows>0){
				if($es=$sql->fetch_assoc()){
					return $es['NumMovto']."//".$es['IdPoliza'];
				}
					
			}else{
				return 0;
			}
			
		}
		function deletemovext($mov,$poli){
			$sql=$this->query(" update  cont_movimientos  set Activo=0 where NumMovto=".$mov." and IdPoliza=".$poli." and (TipoMovto='Cargo M.E.' || TipoMovto='Abono M.E')");
		}
		function deletemovexttodo($mov,$poli){
			$sql=$this->query(" update  cont_movimientos  set Activo=0 where NumMovto=".$mov." and IdPoliza=".$poli);
		}
		function edicionpolizaext($cuenta,$poli){
			$sql=$this->query("select Importe from cont_movimientos where IdPoliza=".$poli." and Cuenta=".$cuenta." and (TipoMovto='Cargo M.E.' || TipoMovto='Abono M.E' )");
				if($es=$sql->fetch_assoc()){
					return $es['Importe'];
				}
		}
		
		function consultarejer($ejer){
			$sql=$this->query("select * from cont_ejercicios where Id=".$ejer);
			if($sql->num_rows>0){
				if($es=$sql->fetch_assoc()){
					return $es['NombreEjercicio'];
				}
					
			}else return 0;
		}
		function buscabancos($idprv){
			$sql=$this->query("select b.nombre,p.idbanco,p.id from cont_bancosPrv p,cont_bancos b where b.idbanco=p.idbanco and idPrv=".$idprv);
			if($sql->num_rows>0){
				return $sql;
			}else{ return  0; }
		}
		function listabancos(){
			return $sql = $this->query("select * from cont_bancos");
		}
		function numbancos($id){
			$sql = $this->query("select numCT from cont_bancosPrv where id=".$id);
			if($sql->num_rows>0){
				if($es=$sql->fetch_assoc()){
					return $es['numCT'];
				}
			}else{ return  0; }
		}
		
		function rfc($prove){
			return $sql=$this->query("select * from mrp_proveedor where idPrv=".$prove);
		}
		function ActualizaMovimiento($pago,$poli){
			$this->query("update cont_movimientos set FormaPago=".$pago."  where IdPoliza=".$poli);
			
		} 
		function formapagoparalistado($poli){
			$sql=$this->query("select FormaPago from cont_movimientos where  FormaPago>0 and IdPoliza=".$poli);
			if($consult = $sql->fetch_array()){
				return $consult['FormaPago'];
			}
		}

		function getBancos()
		{
			$myQuery = "SELECT CuentaBancos FROM cont_config WHERE id=1";
			$cuenta = $this->query($myQuery);
			$cuenta = $cuenta->fetch_object();
			return $cuenta->CuentaBancos;
		}
		function consultaprove($idpoli,$opc){
			if($opc == 1){
				$sql = $this->query("select * from cont_rel_pol_prov where activo=1 and idPoliza=".$idpoli);
				if($sql->num_rows>0){
					return 1;
				}else{
					return 0;
				}
			}else{
				$sql = $this->query("update cont_rel_pol_prov set activo=0 where idPoliza=".$idpoli);
			}
		}
		function consultadesglose($idpoli,$opc){
			if($opc == 1){
				$sql = $this->query("select * from cont_rel_desglose_iva where aplica=1 and idPoliza=".$idpoli);
				if($sql->num_rows>0){
					return 1;
				}else{
					return 0;
				}
			}else{
				$sql = $this->query("update cont_rel_desglose_iva set aplica=0 where idPoliza=".$idpoli);
				return 1;
			}
		}
		function manualnumpol()
		{
			$myQuery = "SELECT NumPol FROM cont_config WHERE id=1";
			$n = $this->query($myQuery);
			$n = $n->fetch_object();
			return $n->NumPol;
		}
		function numPolSis($numPoliza)
		{
			$myQuery = "SELECT numpol FROM cont_polizas WHERE id=$numPoliza";
			$n = $this->query($myQuery);
			$n = $n->fetch_object();
			return $n->numpol;
		}

		function ExisteNumPol($Periodo,$Ejercicio,$TipoPol,$NumPol,$Id)
		{
			$return = 0;
			$myQuery = "SELECT numpol FROM cont_polizas WHERE activo = 1 AND idperiodo = $Periodo AND idejercicio = $Ejercicio AND idtipopoliza = $TipoPol AND numpol = $NumPol AND id != $Id";
			$n = $this->query($myQuery);
			$n = $n->fetch_object();
			if(isset($n->numpol))
			{
				$return = 1;
			}
			return $return;
		} 
		function monedacodigo($idcuenta){
			$sql="select m.codigo from cont_accounts c, cont_coin m where m.coin_id=c.currency_id and account_id=".$idcuenta;
			$cuenta = $this->query($sql);
			$cuenta = $cuenta->fetch_object();
			return $cuenta->codigo;
			 
		}
		function saldado($idpoliza,$idpolianterior){
			
				$sql="
					update cont_polizas set saldado=0 where id=".$idpolianterior.";
					update cont_polizas set saldado=1 where id=".$idpoliza.";";
				$this->multi_query($sql);
		}
		function sinsaldar($idpoliza,$idpolianterior){
				$sql="
					update cont_polizas set saldado=0 where id=".$idpolianterior.";
					update cont_polizas set saldado=0 where id=".$idpoliza.";";
				$this->multi_query($sql);
			
		}
	function relacionext($idpoliza){
		$sql=$this->query("select relacionExt from cont_polizas where id=".$idpoliza);
		if($sql->num_rows>0){
			if($m=$sql->fetch_array()){
				return $m['relacionExt'];
			}
		}else{
			return 0;
		}
		
	}
	function verificasaldado($idpoliza){
		$sql=$this->query("select saldado from cont_polizas where id=".$idpoliza);
		if($m=$sql->fetch_array()){
			return $m['saldado'];
		}
	}
	function cuentasbancarias($cuentacontable){
		$sql=$this->query("select c.idbancaria,b.idbanco,b.nombre,c.cuenta,c.account_id from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id and c.account_id=".$cuentacontable);
		return $sql;
	}
	function cuentasbancariaslista(){
		$sql=$this->query("select c.*,b.nombre,cc.manual_code,cc.description from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id ");
		return $sql;
	}
	function infobancariaid($idbancaria){
		$sql=$this->query("select c.*,b.nombre,cc.manual_code,cc.description from bco_cuentas_bancarias c,cont_bancos b,cont_accounts cc where c.idbanco=b.idbanco and c.account_id=cc.account_id and c.idbancaria=".$idbancaria);
		return $sql->fetch_array();
	}
	function cuentasconf(){
		$sql=$this->query("select * from cont_config ");
		return $sql;
	}
	function buscacuenta($idcuenta){
		$sql=$this->query("select account_id, description from cont_accounts where account_id=".$idcuenta);
		if($sql->num_rows>0){
			if($r=$sql->fetch_array()){
				return $r['account_id']."//".$r['description'];
			}
		}else{
			return '//ASIGNE CUENTA';
		}
	}
	function getAccountGastos($code)
		{
			$myQuery = "SELECT c.account_id, c.description, c.".$code." FROM cont_accounts c, cont_config f
			WHERE c.status=1 AND c.removed=0 AND c.affectable=1 AND c.account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0) and  (c.main_father =f.CuentaDeudores || c.main_father =f.CuentaCaja || c.main_father =f.CuentaBancos) ;
			";
			$ListaCuentas = $this->query($myQuery);
			return $ListaCuentas;	
		}
	function anticiposlista(){//anticipos que tienen movimientos
		$sql = $this->query("select p.id,m.Cuenta from cont_polizas p,cont_movimientos m,cont_accounts c where 
		p.Anticipo=1 and p.activo=1 and p.eliminado=0 and p.id=m.idPoliza and m.Activo=1 and m.Cuenta=c.account_id and m.TipoMovto like 'Cargo%'
		group by p.id");
		$ids = "0";
		while($r = $sql->fetch_assoc()){
			$deudor = $this->deudorNombre($r['id']);
			$cargodeudor = $this->cargosDeudor($r['id'], $r['Cuenta']);
			$abonodeudor = $this->abonosDeudor($r['id'], $r['Cuenta']);
			$totalabono = $abonodeudor['abono'] + $deudor['cargo'];
			if(number_format($cargodeudor['cargo'],2) < number_format($totalabono,2) ){
				$total = $totalabono-$cargodeudor['cargo'];
			}else {$total =0; }
			if($total!=0 || ($abonodeudor['abono']==0 and $cargodeudor['cargo']==0)){
				$ids.=",".$r['id'];
			}
		}
		$sql2 = $this->query("select p.*,c.description from cont_polizas p,cont_movimientos m,cont_accounts c where 
		p.Anticipo=1 and p.activo=1 and p.eliminado=0 and p.id=m.idPoliza and m.Activo=1 and m.Cuenta=c.account_id and m.TipoMovto like 'Cargo%'
		and p.id in ($ids)");
		return $sql2;
	}
	function deudorNombre($idpoliza){
		$sql = $this->query("select c.description,m.Cuenta,sum(m.Importe)cargo,m.IdSegmento,m.IdSucursal from cont_movimientos m, cont_accounts c where idPoliza=".$idpoliza." and c.account_id=m.Cuenta and m.TipoMovto like 'Cargo%'");
		return $sql->fetch_assoc();
	}
	function cargosDeudor($idpoliza,$cuenta){
		$sql = $this->query("select sum(m.Importe)cargo from cont_movimientos m,cont_polizas p where p.idAnticipo=".$idpoliza." and m.idPoliza=p.id and  m.TipoMovto like 'Cargo%' and m.Cuenta!=".$cuenta);
		return $sql->fetch_assoc();
	}
	function abonosDeudor($idpoliza,$cuenta){
		$sql = $this->query("select sum(m.Importe)abono from cont_movimientos m,cont_polizas p where p.idAnticipo=".$idpoliza." and m.idPoliza=p.id and  m.TipoMovto like 'Abono%' and m.Cuenta!=".$cuenta);
		return $sql->fetch_assoc();
	}
	function rfcOrganizacion(){
		$sql=$this->query("select RFC from organizaciones ");
		return $sql->fetch_assoc();
	}
	function polizasActivas($eje){
		$sql = $this->query("SELECT id,p.numpol,
			(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS tipopoliza,
			p.concepto,fecha
			FROM cont_polizas p
			where p.idejercicio =".$eje." and p.activo=1
			ORDER BY p.idtipopoliza,p.numpol"
			);
		return $sql;
	}
		function polizasActivasFecha($desde,$hasta){
		$sql = $this->query("SELECT id,p.numpol,
			(SELECT titulo FROM cont_tipos_poliza WHERE id=p.idtipopoliza) AS tipopoliza,
			p.concepto,fecha
			FROM cont_polizas p
			where p.activo=1 and fecha between '$desde' and '$hasta'
			ORDER BY p.idtipopoliza,p.numpol"
			);
		return $sql;
	}
	
	function copiCompleta($idpoliza,$periodo,$ejercicio,$idtipopoliza,$fecha,$conceptocopy){//pendiente copiar
		$numpol = ($this->UltimoNumPol($periodo,$ejercicio,$idtipopoliza)+1);
		$sql = ("
			insert into cont_polizas 
			(idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,referencia,concepto,fecha,fecha_creacion,activo,eliminado,pdv_aut,relacionExt,beneficiario,numero,rfc,idbanco,numtarjcuent,saldado,idCuentaBancariaOrigen,Anticipo,idAnticipo )
	(select 
			idorganizacion,$ejercicio,$periodo,$numpol,$idtipopoliza,referencia,'$conceptocopy','".$fecha."',NOW(),activo,eliminado,pdv_aut,relacionExt,beneficiario,numero,rfc,idbanco,numtarjcuent,saldado,idCuentaBancariaOrigen,Anticipo,idAnticipo
 			from cont_polizas where id=".$idpoliza.")");
 	
	 	
	 		
	 		$sql2=("insert into cont_movimientos (IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Concepto,Activo,FechaCreacion,Persona,FormaPago) (
			select ".$this->insert_id($sql).",NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,'$conceptocopy',Activo,NOW(),Persona,FormaPago from 
			cont_movimientos where IdPoliza=".$idpoliza."
			);");
		
	 	if($this->query($sql2)){
	 		return 1;
	 	}else{
	 		return 0;
	 	}
 	
 			
	}
	function copyMov($idpolizadestino,$NumMovto,$idmov){
		$sql=" insert into cont_movimientos 
		(IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Concepto,Activo,FechaCreacion,Persona,FormaPago) 
		( select $idpolizadestino,$NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Concepto,Activo,NOW(),Persona,FormaPago from cont_movimientos where id=".$idmov." );";
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function usuarios(){
		$sql = $this->query("select u.idempleado,u.usuario from accelog_usuarios u, accelog_usuarios_per p where p.idempleado = u.idempleado and p.idperfil=32 order by usuario asc");
		return $sql;
	}
	function anticiposlistauser($user){//anticipos que tienen movimientos
		$sql = $this->query("select p.id,m.Cuenta from cont_polizas p,cont_movimientos m,cont_accounts c where 
		p.Anticipo=1 and p.activo=1 and p.eliminado=0 and p.id=m.idPoliza and m.Activo=1 and m.Cuenta=c.account_id and m.TipoMovto like 'Cargo%' and p.idUser=".$user."
		group by p.id");
		$ids = "0";
		while($r = $sql->fetch_assoc()){
			$deudor = $this->deudorNombre($r['id']);
			$cargodeudor = $this->cargosDeudor($r['id'], $r['Cuenta']);
			$abonodeudor = $this->abonosDeudor($r['id'], $r['Cuenta']);
			$totalabono = $abonodeudor['abono'] + $deudor['cargo'];
			if( number_format($cargodeudor['cargo'],2,'.','') < number_format($totalabono,2,'.','') ){
				$total = $totalabono-$cargodeudor['cargo'];
			}else {$total =0; }
			if($total!=0 || ($abonodeudor['abono']==0 and $cargodeudor['cargo']==0)){
				$ids.=",".$r['id'];
			}
		}
		$sql2 = $this->query("select p.*,IFNULL((SELECT SUM(Importe) FROM cont_movimientos WHERE TipoMovto ='Cargo' AND IdPoliza = p.id AND Activo=1),0) AS cargos,c.description,m.Cuenta,
		(select (case p.beneficiario when 0 then 0 else prv.razon_social end) from mrp_proveedor prv where prv.idPrv=p.beneficiario) as prove
		from cont_polizas p,cont_movimientos m,cont_accounts c where 
		p.Anticipo=1 and p.activo=1 and p.eliminado=0 and p.id=m.idPoliza and m.Activo=1 and m.Cuenta=c.account_id and m.TipoMovto like 'Cargo%'  and p.idUser=".$user."
		and p.id in ($ids)");
		if($sql2->num_rows>0){
			return $sql2;
		}else{
			return 0;
		}
		
	}
	function infousuario($user){
		$sql = $this->query("select usuario from accelog_usuarios where idempleado=".$user);
		$nombre =  $sql->fetch_assoc();
		return $nombre['usuario'];
	}
	
	function agregaNoDeducibles($query){
		if(!$this->multi_query($query)){
			return 1;
		}else{
			return 0;
		}
	}
	function listaNoDeducible($idanticipo){
		$sql = $this->query("select * from cont_nodeducible where idAnticipo=".$idanticipo." and status = 1 ");
		return $sql;
	}
	function borraListVieja($idAnticipo){
		$sql = "delete from cont_nodeducible where idAnticipo=".$idAnticipo." and status=1";
		if(!$this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function NoDeducibleEnPoliza($id){
		$sql=$this->query("update cont_nodeducible set status=0 where id=".$id);
	}
	function deleteViejoMov($poli,$mov){
		$sql="delete from cont_movimientos where IdPoliza=".$poli." and NumMovto=".$mov;
		if(!$this->query($sql)){
			return 0;
		}else{
			return 1;
		}
	}
	function datosPrv($prove){
		$sql=$this->query("select * from mrp_proveedor where idPrv=".$prove);
		return $sql->fetch_array();
	}
	function cuentaXMLimporte($xml,$cuenta,$mov,$tipopoli){
		$sql = $this->query("select sum(m.Importe) monto,m.TipoMovto,m.cuenta  
					from cont_movimientos m,cont_polizas p 
					where  p.id=m.idPoliza and m.Activo=1 and p.activo=1 and p.idtipopoliza=$tipopoli 
					and m.factura='".$xml."' 
					and m.cuenta=".$cuenta." and m.TipoMovto='$mov'");
		if($sql->num_rows>0){
			return $sql->fetch_assoc();
		}else{
			return 0;
		}
	}
	
	function empleadosRegistrados(){
		$sql = $this->query("select * from nomi_empleados");
		return $sql;
	}
	function datosempleados($idempleado)
	{
		$sql = $this->query("select * from nomi_empleados where activo=-1 and idEmpleado=".$idempleado);
		return $sql->fetch_assoc();
	}
	function tipoCambio($idmoneda,$fecha){
		
		$sql = $this->query("select c.*,m.codigo from cont_tipo_cambio c,cont_coin m where m.coin_id=c.moneda and c.moneda=".$idmoneda." and c.fecha like '$fecha%'");
		return $sql;
	}

	function tipoinstancia()
	 {
	 	$myQuery = "SELECT tipoinstancia FROM organizaciones WHERE idorganizacion=1;";
	 	$id = $this->query($myQuery);
		$id = $id->fetch_assoc();
		return $id['tipoinstancia'];
	 }
	 function compruebaRelacionExt($id){
	 	$sql =$this->query( "select 
	 				p.relacionext,c.currency_id 
	 			from 
	 				cont_polizas p,cont_movimientos m,cont_accounts c
				where 
					m.IdPoliza=p.id 
					and m.Cuenta=c.`account_id` 
					and c.currency_id!=1 
					and m.Activo=1 
					and p.id=$id 
				group by p.id");
		return $sql;
	 }
	 
	function validaBancos(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=1932");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}
	function idDocumentoBancario($idpoliza){
		$sql = $this->query("SELECT p.idDocumento,d.idDocumento tipo FROM cont_polizas p,bco_documentos d WHERE p.id=$idpoliza and d.id=p.idDocumento");
		if($sql->num_rows>0){
			$id = $sql->fetch_assoc();
			return $id['idDocumento']."/".$id['tipo'];
		}else{
			return 0;
		}
	}
	function cuentaBancos($idpoliza){
		$sql = $this->query("
					select 
						c.account_id 
					from 
						bco_documentos d, 
						cont_accounts c,
						bco_cuentas_bancarias b,
						cont_polizas p  
					where 
						b.idbancaria=d.idbancaria 
						and c.account_id=b.account_id 
						and p.id=$idpoliza 
						and p.idDocumento=d.id
					UNION
						select 
						c.account_id 
					from 
						bco_documentos d, 
						cont_accounts c,
						bco_cuentas_bancarias b,
						cont_polizas p  
					where 
						b.idbancaria=d.idbancaria 
						and c.account_id=b.account_id 
						and p.id=$idpoliza
						and d.idtraspaso=p.idDocumento");
		
			return $sql;
			//return $id['account_id'];
		
	}

	function buscaClienteProveedor($rfc,$nombre,$tipo_fac,$domicilio_fiscal)
	{
		$arrSTRIP = array('Š'=>'S','š'=>'s','Ž'=>'Z','ž'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','þ'=>'b','ÿ'=>'y','.'=>'');

		//CLIENTE
		if(intval($tipo_fac) == 1)
		{
			if(!intval($domicilio_fiscal['nomina']))
			{
				$existe = $this->query("SELECT id FROM comun_cliente WHERE rfc = '$rfc' AND borrado = 0");
				$existe = $existe->num_rows;
				if(!intval($existe))
				{

					/*$domicilio_fiscal['estado'] = trim(strtoupper(strtr($domicilio_fiscal['estado'], $arrSTRIP)));
					$domicilio_fiscal['municipio'] = trim(strtoupper(strtr($domicilio_fiscal['municipio'], $arrSTRIP)));

					$estado = $this->query("SELECT idestado FROM estados WHERE UPPER(estado) = '".$domicilio_fiscal['estado']."'");
					$estado = $estado->fetch_assoc();
					$estado = $estado['idestado'];
					if(!intval($estado))
						$estado = 0;

					$municipio = $this->query("SELECT idmunicipio FROM municipios WHERE UPPER(municipio) = '".$domicilio_fiscal['municipio']."' AND idestado = $estado;");
					$municipio = $municipio->fetch_assoc();
					$municipio = $municipio['idmunicipio'];

					if(!intval($municipio))
						$municipio = 0;*/
						//$ret = $this->insert_id("INSERT INTO comun_cliente(nombre,rfc,direccion,num_ext,colonia,idEstado,idMunicipio,cp) VALUES('$nombre','$rfc','".$domicilio_fiscal['calle']."','".$domicilio_fiscal['noExt']."','".$domicilio_fiscal['colonia']."',$estado,$municipio,'".$domicilio_fiscal['cp']."');

					$ret = $this->insert_id("INSERT INTO comun_cliente(nombre,rfc) VALUES('$nombre','$rfc');");

					//$this->query("INSERT INTO comun_facturacion(nombre,rfc,razon_social,domicilio,num_ext,cp,colonia,estado,municipio,cliPro) VALUES($ret,'$rfc','$nombre','".$domicilio_fiscal['calle']."','".$domicilio_fiscal['noExt']."', '".$domicilio_fiscal['cp']."', '".$domicilio_fiscal['colonia']."',$estado,'".$domicilio_fiscal['municipio']."',1);");

				    $this->query("INSERT INTO comun_facturacion(nombre,rfc,razon_social,cliPro) VALUES($ret,'$rfc','$nombre',1);");
				}
			}
			else
			{
					$existe = $this->query("SELECT idEmpleado FROM nomi_empleados WHERE rfc = '$rfc' AND activo = -1");
					$existe = $existe->num_rows;
					if(!intval($existe))
					{
						$this->query("INSERT INTO nomi_empleados(idEmpleado, codigo, nombreEmpleado, idestado, idmunicipio, rfc) VALUES(0,'".$domicilio_fiscal['NumEmpleado']."','$nombre', 14, 0, '$rfc');");
					}
			}
		}

		//PROVEEDOR
		if(!intval($tipo_fac))
		{
			$existe = $this->query("SELECT idPrv FROM mrp_proveedor WHERE rfc = '$rfc' AND status != 0");
			$existe = $existe->num_rows;
			if(!intval($existe))
			{
				
				/*$domicilio_fiscal['estado'] = trim(strtoupper(strtr($domicilio_fiscal['estado'], $arrSTRIP)));
				$domicilio_fiscal['municipio'] = trim(strtoupper(strtr($domicilio_fiscal['municipio'], $arrSTRIP)));

				$estado = $this->query("SELECT idestado FROM estados WHERE UPPER(estado) = '".$domicilio_fiscal['estado']."'");
				$estado = $estado->fetch_assoc();
				$estado = $estado['idestado'];
				if(!intval($estado))
					$estado = 0;

				$municipio = $this->query("SELECT idmunicipio FROM municipios WHERE UPPER(municipio) = '".$domicilio_fiscal['municipio']."' AND idestado = $estado;");
				$municipio = $municipio->fetch_assoc();
				$municipio = $municipio['idmunicipio'];

				if(!intval($municipio))
					$municipio = 0;
				$cp = '0';
				if($domicilio_fiscal['cp'] != '')
					$cp = $domicilio_fiscal['cp'];

				$no_ext = '0';
				if(intval($domicilio_fiscal['noExt']))
					$no_ext = $domicilio_fiscal['noExt'];*/

					//$ret = $this->insert_id("INSERT INTO mrp_proveedor(razon_social,rfc,idestado,idmunicipio,idtipotercero,idtipoperacion,idTasaPrvasumir,cp,calle,no_ext,colonia) VALUES('$nombre','$rfc',$estado,$municipio,1,1,0,'$cp','".$domicilio_fiscal['calle']."','$no_ext','".$domicilio_fiscal['colonia']."')");

				$ret = $this->insert_id("INSERT INTO mrp_proveedor(razon_social,rfc,idtipotercero,idtipoperacion,idTasaPrvasumir) VALUES('$nombre','$rfc',1,1,0)");

				//$this->query("INSERT INTO comun_facturacion(nombre,rfc,razon_social,domicilio,num_ext,cp,colonia,estado,municipio,cliPro) VALUES($ret,'$rfc','$nombre','".$domicilio_fiscal['calle']."','".$domicilio_fiscal['noExt']."', '$cp','".$domicilio_fiscal['colonia']."',$estado,'".$domicilio_fiscal['municipio']."',2);");

				$this->query("INSERT INTO comun_facturacion(nombre,rfc,razon_social,cliPro) VALUES($ret,'$rfc','$nombre',2);");
			}
		}
		return $ret;
	}

	function getAllExercises(){
		$myQuery = "SELECT nombreEjercicio FROM cont_ejercicios;";
		$Resultado = $this->query($myQuery);
		return $Resultado;
	}

	function Confirmar($vars)
	{
		$rechazado = '';
		if(!intval($vars['tipo']))
		{
			$rechazado = "'Rechazado por: ',";;
			$update = "confirmado1 = -1, confirmado2 = -1";
		}

		$validador = "CONCAT($rechazado(SELECT usuario FROM accelog_usuarios WHERE idempleado = ".$_SESSION['accelog_idempleado']."),' / ',".$_SESSION['accelog_idempleado'].",' / '".",DATE_SUB(NOW(), INTERVAL 6 HOUR))";		

		if(intval($vars['tipo']) == 1)
			$update = "confirmado1 = ".$vars['valor'].", confirmado2 = 0";

		if(intval($vars['tipo']) == 2)
		{
			$update = "confirmado2 = ".$vars['valor'];
			$validador = "CONCAT(validador,',',".$validador.")";
		}

		
		$myQuery = "UPDATE cont_polizas SET $update, validador=$validador WHERE id = ".$vars['idpol'];
		if($this->query($myQuery))
		{
			$res = $this->query("SELECT validador FROM cont_polizas WHERE id = ".$vars['idpol']);
			$res = $res->fetch_assoc();
			return '1'."*/*".$res['validador'];
		}
		else
			return '0'."*/*0";
		
	}

	function nombreImpuesto($imp)
	{
		$return = array();
		for($i=0;$i<=count($imp)-1;$i++)
		{
			$res = $this->query("SELECT descripcion FROM cont_impuestos WHERE claveSat = '".$imp[$i]."'");
			$res = $res->fetch_assoc();
			array_push($return, $res['descripcion']);
		}
		
		return $return;
	}
	function nombreImpuestoIndividual($imp)
	{
		
		$res = $this->query("SELECT descripcion FROM cont_impuestos WHERE claveSat = '".$imp."'");
		$res = $res->fetch_assoc();
		return $res['descripcion'];
		
	}

	function periodoAbierto(){
		$myQuery = "SELECT PeriodosAbiertos FROM cont_config";
		$Result = $this->query($myQuery);
		return $Result;
	}
	//anticipo ticket
	function ticektAnticipo(){
		$sql = $this->query("		
		Select d.id idnodedu,p.id idpoliza,p.numpol,p.concepto as conceptopoliza,u.usuario, ca.nombre as nombrecategoria,
		 d.* 
		from cont_polizas p
		inner join cont_movimientos m on m.idPoliza = p.id and m.Activo=1
		inner join cont_nodeducible d on d.idAnticipo = p.id and d.status!=0
		inner join accelog_usuarios u on u.idempleado = p.idUser
		inner join cont_comprobante_categoria ca on ca.id = d.idCategoria
		where p.Anticipo = 1 and p.activo=1  group by d.id");
		if($sql ->num_rows>0){
			return $sql; 
		}else{
			return 0;
		}
	}
	function almacenaFactNodedu($idnodedu,$xml,$status){
		$sql = "update cont_nodeducible set xml = '$xml', status = $status where id=".$idnodedu;
		if($this->query($sql)){
			return 1;
		}else{
			return 0;
		}
	}
	function xmlNodeducible($idnodedu){
		$sql = $this->query("select xml from cont_nodeducible where id=".$idnodedu);
		if($sql->num_rows>0){
			$xml = $sql->fetch_object();
			return $xml->xml;
		}else{
			return 0;
		}
	}
	function buscaTicket($xml){
		$sql = $this->query("select id from cont_nodeducible where xml='$xml';");
		if($sql->num_rows>0){
			$ids = $sql->fetch_object();
			$this->NoDeducibleEnPoliza($ids->id);
		}else{
			echo 0;
		}
	}

	function guardaFactura($nombreArchivo,$vars,$pago)
	{
		$myQuery = "INSERT INTO cont_facturas VALUES(0,'".$vars['folio']."','".$vars['uuid']."','".$vars['er']."','".$vars['tipo']."','".$vars['serie']."','".$vars['emisor']."','".$vars['receptor']."',".$vars['importe'].",'".$vars['moneda']."','".$vars['rfc']."','".$vars['fecha']."',DATE_SUB(NOW(), INTERVAL 6 HOUR),'".$nombreArchivo."','".$vars['version']."',".$vars['cancelada'].",'".$vars['json']."',".$vars['temporal'].",1);";
		$id = $this->insert_id($myQuery);
		if($id && $vars['TipoDeComprobante'] == "P")
		{
			$i = 0;
			if(is_array($pago['IdDocumento'])){
				for($i=0;$i<=count($vars['IdDocumento'])-1;$i++)
					$this->insertarRelacion($vars,$i,1,$pago);
			}
			elseif($pago['IdDocumento'])
				$this->insertarRelacion($vars,$i,0,$pago);	
		}
		
		return $id;
	}

	function insertarRelacion($vars,$i,$arr,$pago)
	{
		if($arr)
			$myQuery = "INSERT IGNORE INTO cont_facturas_relacion VALUES('".$vars['uuid']."','".$pago['IdDocumento'][$i]."',".$pago['ImpPagado'][$i].",".$pago['ImpSaldoAnt'][$i].",".$pago['ImpSaldoInsoluto'][$i].",'".$pago['MonedaDR'][$i]."','".$pago['MetodoDePagoDR'][$i]."',".$pago['NumParcialidad'][$i].");";
		else
			$myQuery = "INSERT IGNORE INTO cont_facturas_relacion VALUES('".$vars['uuid']."','".$pago['IdDocumento']."',".$pago['ImpPagado'].",".$pago['ImpSaldoAnt'].",".$pago['ImpSaldoInsoluto'].",'".$pago['MonedaDR']."','".$pago['MetodoDePagoDR']."',".$pago['NumParcialidad'].");";

			$this->query($myQuery);
	}

	function obtener_datos_tabla($tabla, $estatus){
		$myQuery = "SELECT * FROM $tabla";
		if ($estatus == 1) {
			$myQuery .= ' WHERE estatus = 1;';
		}
		$Result = $this->query($myQuery);
		return $Result;
	}

	function agregar_campo_tabla($tabla, $campo){
		$myQuery = "INSERT INTO $tabla(nombre) VALUES('$campo');";
		$Result = $this->query($myQuery);
		return $Result;
	}

	function modificar_registro($tabla, $campo, $activo, $id){
		$myQuery = "UPDATE $tabla SET nombre = '$campo', estatus = $activo WHERE id = $id;";
		$Result = $this->query($myQuery);
		return $Result;
	}

	function canceladas($uuid)
	{
		$this->query("UPDATE cont_facturas SET cancelada = 1 WHERE uuid = '$uuid'");
	}
	function listaTemporalesBD($vars)
	{
		if($vars['fechas'] == '')
			{
				$buscar = $vars['folio_uuid'];
				if($buscar == '*')
					$buscar = '';
				
				//Si es por Folio
				if(!intval($vars['tipo_busqueda']))
					$where = "f.folio LIKE '%$buscar%'";
				
				//Si es por UUID
				if(intval($vars['tipo_busqueda']) == 1)
					$where = "f.uuid LIKE '%$buscar%'";
				
				//Si es por Razon social
				if(intval($vars['tipo_busqueda']) == 2)
				{
					$where = "f.emisor LIKE '%$buscar%' OR f.receptor LIKE '%$buscar%'";
				}
			}
			else
			{
				$fechas = explode(' / ',$vars['fechas']);
				$where = "fecha BETWEEN '$fechas[0] 00:00:00' AND '$fechas[1] 23:59:59'";
			}

			if($vars['moneda'] == 'Dlls')
				$where .= " AND (moneda LIKE '%DOLAR%' || moneda LIKE '%DLLS%' || moneda LIKE '%USD%' || moneda LIKE '%AMER%' || moneda = '2') ";

			return $this->query("SELECT f.* FROM cont_facturas f WHERE $where AND f.temporal = 1 AND f.cancelada = 0;");
	}

	function getUUIDfechas($inicial, $final){
		$myQuery = "SELECT uuid FROM cont_facturas WHERE fecha BETWEEN '$inicial' AND '$final';";
		$Result = $this->query($myQuery);
		return $Result;
	}

	function facturaRename($xml)
	{
		if(strpos($xml, "_") === false)
			$uuid = str_replace('.xml', '', $xml);
		else
		{
			$uuid = explode('_', $xml);
			$uuid = str_replace('.xml', '', $uuid[2]);
		}

		$this->query("UPDATE cont_facturas SET xml = '$xml' WHERE uuid = '$uuid';");
		
	}
}
?>
