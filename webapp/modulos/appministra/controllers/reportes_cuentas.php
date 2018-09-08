<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reportes_cuentas.php");

class Reportes_Cuentas extends Common
{
	public $ReportesCuentasModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ReportesCuentasModel = new ReportesCuentasModel();
		$this->ReportesCuentasModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ReportesCuentasModel->close();
	}

	function resumen_saldos()
	{
		$listaProveedores = $this->ReportesCuentasModel->listaProveedores();
		require("views/cuentas/resumen_saldos.php");
	}

	function generar_reporte()
	{
		$tabla = "";
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->ReportesCuentasModel->generar_reporte($_POST);
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $provAnterior)
			{
				if($cont > 0)
					$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td><b>$ ".number_format($saldos,2)."</b></td></tr>";

				$saldos = $this->ReportesCuentasModel->saldoInicial($d->id_prov_cli,$_POST['f_ini']);
				$tabla .= "<tr class='linea_prov'><td colspan='2'>$d->nombre_proveedor</td><td>Saldo Inicial</td><td><b>$ ".number_format($saldos,2)."</b></td></tr>";
			}
			
			if($d->Folio != $facAnterior)
			{

				if(floatval($d->ImporteDoc)!=0)
				{
					if(strtotime($d->fecha_documento) >= strtotime($_POST['f_ini']." 00:00:00") && strtotime($d->fecha_documento) <= strtotime($_POST['f_fin']." 23:59:59"))
					{
						$saldos += $d->ImporteDoc;
						$ss = $saldos;
					}
					else
						$ss = 0;

					$foliosFac = $d->Folio;
					if(intval($d->Fac))
						$foliosFac = $this->ReportesCuentasModel->foliosFac($d->Folio);

					if(strtotime($d->fecha_documento) >= strtotime($_POST['f_ini']." 00:00:00") && strtotime($d->fecha_documento) <= strtotime($_POST['f_fin']." 23:59:59"))
						$tabla .= "<tr class='linea_fac'><td><b>Folio(s)/UUID:</b>$foliosFac</td><td>$d->SacarConcepto</td><td>$ ".number_format($d->ImporteDoc,2)."</td><td>$ ".number_format($ss,2)."</td></tr>";

					if(strtotime($d->fecha_documento) <= strtotime($_POST['f_ini']." 00:00:00") && intval($d->id_relacion))
						$tabla .= "<tr class='linea_fac'><td><b>Folio(s)/UUID:</b>$foliosFac</td><td>$d->SacarConcepto</td><td>$ ".number_format($d->ImporteDoc,2)."</td><td>$ ".number_format($ss,2)."</td></tr>";
				}
			}
			$saldos -= $d->abono;
			$cheque = "";
			if(floatval($d->abono))
			{
				if($d->numero_cheque)
					$cheque = "(Cheque: $d->numero_cheque)";
				$tabla .= "<tr class='movimientos'><td><b>Pago</b> $d->fecha_pago</td><td>$d->concepto $cheque</td><td>$ ".number_format($d->abono,2)."</td><td>$ ".number_format($saldos,2)."</td></tr>";
			}

			$provAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td><b>$ ".number_format($saldos,2)."</b></td></tr>";
		echo $tabla;
	}

	function aux_mov_cxp()
	{
		$listaProveedores = $this->ReportesCuentasModel->listaProveedores();
		$listaFormasPago = $this->ReportesCuentasModel->listaFormasPago();
		require("views/cuentas/aux_movimientos.php");
	}

	function aux_movimientos_reporte()
	{
		$tabla = "";
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->ReportesCuentasModel->aux_movimientos_reporte($_POST);
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $provAnterior)
			{
				if($cont > 0)
					$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td>$ $saldos</td></tr>";

				$infoProv = explode("*/*",$d->info_proveedor);
				$tabla .= "<tr class='linea_prov'><td colspan='7'>".$infoProv[0]."</td></tr>";
				$tabla .= "<tr class='linea_dias'><td>Dias de Credito: </td><td colspan='6'>".$infoProv[1]."</td></tr>";
			}
			
			if($d->Folio != $facAnterior)
			{
				if(floatval($d->ImporteDoc)!=0)
				{
					$foliosFac = $d->Folio;
					if(intval($d->Fac))
						$foliosFac = $this->ReportesCuentasModel->foliosFac($d->Folio);

					$saldos = floatval($d->ImporteDoc) - floatval($this->ReportesCuentasModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$_POST['f_ini'],1));
					$fecha_venc = date("Y-m-d", strtotime("$d->fecha_documento + ".$infoProv[1]." days"));
					$tabla .= "<tr class='linea_fac'><td>$d->fecha_documento</td><td>$foliosFac</td><td>$d->SacarConcepto</td><td class='cargo' cantidad='$d->ImporteDoc'>$ ".number_format($d->ImporteDoc,2)."</td><td></td><td>$ ".number_format($saldos,2)."</td><td>$fecha_venc</td></tr>";
				}
				
			}
			$saldos -= $d->abono;
			if($d->abono)
				$tabla .= "<tr class='movimientos'><td>$d->fecha_pago</td><td>$d->Folio</td><td>$d->concepto</td><td></td><td class='abono' cantidad='$d->abono'>$ ".number_format($d->abono,2)."</td><td>$ ".number_format($saldos,2)."</td><td></td></tr>";

			$provAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td id='suma_cargos'></td><td id='suma_abonos'></td><td id='suma_saldos'></td><td></td></tr>";
		echo $tabla;
	}

	function ant_saldos()
	{
		$listaProveedores = $this->ReportesCuentasModel->listaProveedores();
		require("views/cuentas/reporte_ant_saldos.php");
	}

	function ant_saldos_reporte()
	{
		$tabla = "";
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->ReportesCuentasModel->ant_saldos_reporte($_POST);
		$vencer = "Vencido";
		if(isset($_POST['pronos']))
			$vencer = "Por Vencer";
		$saldosTotal = $saldosSin = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $provAnterior)
			{
				if($cont > 0)
					$tabla .= "<tr class='linea_final'><td colspan='3'></td><td>Saldo Final</td><td cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</td><td cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</td><td cantidad='$s1_15'>$ ".number_format($s1_15,2)."</td><td cantidad='$s16_30'>$ ".number_format($s16_30,2)."</td><td cantidad='$s31_45'>$ ".number_format($s31_45,2)."</td><td cantidad='$sm45'>$ ".number_format($sm45,2)."</td></tr>";

				$saldosSin = $saldosTotal = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
				$infoProv = explode("*/*",$d->info_proveedor);
				$tabla .= "<tr class='linea_prov'><td colspan='2'>".$infoProv[0]."</td><td colspan='4'>Dias de Credito: ".$infoProv[1]."</td><td colspan='4'>$vencer</td></tr>";
			}
			
			if($d->Folio != $facAnterior)
			{
				$hasta = $_POST['f_cor'];
				if(!isset($_POST['f_cor']))
					$hasta = $_POST['f_fin'];

				if($d->id_tipo != 'XXYY')
					$saldos = floatval($d->ImporteDoc) - floatval($this->ReportesCuentasModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$hasta,1));
				else
					$saldos = $d->ImporteDoc;
				
				if(floatval(number_format($saldos,2)) != 0)
				{
					if($d->id_tipo != 'XXYY')
					{
						$fecha_venc = date("Y-m-d", strtotime("$d->fecha_documento + ".$infoProv[1]." days"));
						$diasVencidos	= ((strtotime('-7 hour',strtotime($_POST['f_cor']))-strtotime($fecha_venc))/86400)+1;
		
						$diasVencidos = floor($diasVencidos);	

						if(isset($_POST['pronos']) && intval($diasVencidos))
							$diasVencidos = $diasVencidos*-1;
					}
					else
						$diasVencidos = $fecha_venc = '---';
						

						$foliosFac = $d->Folio;
						if(intval($d->Fac))
							$foliosFac = $this->ReportesCuentasModel->foliosFac($d->Folio);
						$tabla .= "<tr class='linea_fac'><td>$foliosFac</td><td>$d->fecha_documento</td><td>$fecha_venc</td><td>$diasVencidos</td><td>$ ".number_format($saldos,2)."</td>";
						$saldosTotal += $saldos;

						if($d->id_tipo == 'XXYY')
						{
							$tabla .= "<td></td><td></td><td></td><td></td><td></td>";
						}
						if(intval($diasVencidos)<1 && $d->id_tipo != 'XXYY')
						{
							$tabla .= "<td>$ ".number_format($saldos,2)."</td><td></td><td></td><td></td><td></td>";
							$saldosSin += $saldos;
						}

						if(intval($diasVencidos)>=1 && intval($diasVencidos)<=15 && $d->id_tipo != 'XXYY')
						{
							$tabla .= "<td></td><td>$ ".number_format($saldos,2)."</td><td></td><td></td><td></td>";
							$s1_15 += $saldos;
						}

						if(intval($diasVencidos)>=16 && intval($diasVencidos)<=30 && $d->id_tipo != 'XXYY')
						{
							$tabla .= "<td></td><td></td><td>$ ".number_format($saldos,2)."</td><td></td><td></td>";
							$s16_30 += $saldos;
						}

						if(intval($diasVencidos)>=31 && intval($diasVencidos)<=45 && $d->id_tipo != 'XXYY')
						{
							$tabla .= "<td></td><td></td><td></td><td>$ ".number_format($saldos,2)."</td><td></td>";
							$s31_45 += $saldos;
						}

						if(intval($diasVencidos)>45 && $d->id_tipo != 'XXYY')
						{
							$tabla .= "<td></td><td></td><td></td><td></td><td>$ ".number_format($saldos,2)."</td>";
							$sm45 += $saldos;
						}

							$tabla .= "</tr>";
				}
			}
			

			$provAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		$tabla .= "<tr class='linea_final'><td colspan='3'></td><td>Saldo Final</td><td cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</td><td cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</td><td cantidad='$s1_15'>$ ".number_format($s1_15,2)."</td><td cantidad='$s16_30'>$ ".number_format($s16_30,2)."</td><td cantidad='$s31_45'>$ ".number_format($s31_45,2)."</td><td cantidad='$sm45'>$ ".number_format($sm45,2)."</td></tr>";
		$tabla .= "<tr class='linea_general'><td colspan='3'></td><td>Saldo General</td><td id='saldoGn'></td><td id='saldoSinGn'></td><td id='s1_15Gn'></td><td id='s16_30Gn'></td><td id='s31_45Gn'></td><td id='sm45Gn'></td></tr>";
		$tabla .= "<tr class='linea_porc'><td colspan='3'></td><td>Porcentaje</td><td id='saldoPc'>100%</td><td id='saldoSinPc'></td><td id='s1_15Pc'></td><td id='s16_30Pc'></td><td id='s31_45Pc'></td><td id='sm45Pc'></td></tr>";
		echo $tabla;
	}

	function pronos_pagos()
	{
		$listaProveedores = $this->ReportesCuentasModel->listaProveedores();
		require("views/cuentas/pronos_pagos.php");
	}

	//------CUENTAS POR COBRAR----------------
	function resumen_saldos_cobrar()
	{
		$listaClientes = $this->ReportesCuentasModel->listaClientes();
		require("views/cuentas/resumen_saldos_cobrar.php");
	}

	function generar_reporte_cobrar()
	{
		$tabla = "";
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->ReportesCuentasModel->generar_reporte_cobrar($_POST);
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $provAnterior)
			{
				if($cont > 0)
					$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td><b>$ ".number_format($saldos,2)."</b></td></tr>";

				$saldos = $this->ReportesCuentasModel->saldoInicialCobrar($d->id_prov_cli,$_POST['f_ini']);
				$tabla .= "<tr class='linea_prov'><td colspan='2'>$d->nombre_cliente</td><td>Saldo Inicial</td><td><b>$ ".number_format($saldos,2)."</b></td></tr>";
			}
			
			if($d->Folio != $facAnterior)
			{

				if(floatval($d->ImporteDoc)!=0)
				{
					if(strtotime($d->fecha_documento) >= strtotime($_POST['f_ini']." 00:00:00") && strtotime($d->fecha_documento) <= strtotime($_POST['f_fin']." 23:59:59"))
					{
						$saldos += $d->ImporteDoc;
						$ss = $saldos;
					}
					else
						$ss = 0;

					if(strtotime($d->fecha_documento) >= strtotime($_POST['f_ini']." 00:00:00") && strtotime($d->fecha_documento) <= strtotime($_POST['f_fin']." 23:59:59"))
						$tabla .= "<tr class='linea_fac'><td><b>Folio/UUID:</b>$d->Folio</td><td>$d->SacarConcepto</td><td>$ ".number_format($d->ImporteDoc,2)."</td><td>$ ".number_format($ss,2)."</td></tr>";

					if(strtotime($d->fecha_documento) <= strtotime($_POST['f_ini']." 00:00:00") && intval($d->id_relacion))
						$tabla .= "<tr class='linea_fac'><td><b>Folio/UUID:</b>$d->Folio</td><td>$d->SacarConcepto</td><td>$ ".number_format($d->ImporteDoc,2)."</td><td>$ ".number_format($ss,2)."</td></tr>";
				}
			}
			$saldos -= $d->abono;
			if(floatval($d->abono))
				$tabla .= "<tr class='movimientos'><td><b>Pago</b> $d->fecha_pago</td><td>$d->concepto</td><td>$ ".number_format($d->abono,2)."</td><td>$ ".number_format($saldos,2)."</td></tr>";

			$provAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td><b>$ ".number_format($saldos,2)."</b></td></tr>";
		echo $tabla;
	}

	function aux_mov_cxc()
	{
		$listaClientes = $this->ReportesCuentasModel->listaClientes();
		$listaFormasPago = $this->ReportesCuentasModel->listaFormasPago();
		require("views/cuentas/aux_movimientos_cobrar.php");
	}

	function aux_movimientos_cxc_reporte()
	{
		$tabla = "";
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->ReportesCuentasModel->aux_movimientos_reporte_cobrar($_POST);
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $cliAnterior)
			{
				if($cont > 0)
					$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td>$ ".number_format($saldos,2)."</td></tr>";

				$infoCli = explode("*/*",$d->info_cliente);
				if(!intval($infoCli[1]))
					$infoCli[1] = 1;

				$tabla .= "<tr class='linea_prov'><td colspan='7'>".$infoCli[0]."</td></tr>";
				$tabla .= "<tr class='linea_dias'><td>Dias de Credito: </td><td colspan='6'>".$infoCli[1]."</td></tr>";
			}
			
			if($d->Folio != $facAnterior)
			{
				if(floatval($d->ImporteDoc)!=0)
				{
					$saldos = floatval($d->ImporteDoc) - floatval($this->ReportesCuentasModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$_POST['f_ini'],0));
					$fecha_venc = date("Y-m-d", strtotime("$d->fecha_documento + ".$infoCli[1]." days"));
					$tabla .= "<tr class='linea_fac'><td>$d->fecha_documento</td><td>$d->Folio</td><td>$d->SacarConcepto</td><td class='cargo' cantidad='".floatval($d->ImporteDoc)."'>$ ".number_format($d->ImporteDoc,2)."</td><td></td><td>$ ".number_format($saldos,2)."</td><td>$fecha_venc</td></tr>";
				}
			}
			$saldos -= $d->abono;
			if($d->abono)
				$tabla .= "<tr class='movimientos'><td>$d->fecha_pago</td><td>$d->Folio </td><td>$d->concepto</td><td></td><td class='abono' cantidad='$d->abono'>$ ".number_format($d->abono,2)."</td><td>$ ".number_format($saldos,2)."</td><td></td></tr>";

			$cliAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		$tabla .= "<tr class='linea_final'><td colspan='2'></td><td>Saldo Final</td><td id='suma_cargos'></td><td id='suma_abonos'></td><td id='suma_saldos'></td><td></td></tr>";
		echo $tabla;
	}

	function ant_saldos_cxc()
	{
		$listaClientes = $this->ReportesCuentasModel->listaClientes();
		require("views/cuentas/reporte_ant_saldos_cxc.php");
	}

	function ant_saldos_reporte_cxc()
	{
		$tabla = "";
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->ReportesCuentasModel->ant_saldos_reporte_cxc($_POST);
		$vencer = "Vencido";
		if(isset($_POST['pronos']))
			$vencer = "Por Vencer";
		$saldosTotal = $saldosSin = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $cliAnterior)
			{
				if($cont > 0)
					$tabla .= "<tr class='linea_final'><td colspan='3'></td><td>Saldo Final</td><td cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</td><td cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</td><td cantidad='$s1_15'>$ ".number_format($s1_15,2)."</td><td cantidad='$s16_30'>$ ".number_format($s16_30,2)."</td><td cantidad='$s31_45'>$ ".number_format($s31_45,2)."</td><td cantidad='$sm45'>$ ".number_format($sm45,2)."</td></tr>";

				$saldosSin = $saldosTotal = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
				$infoCli = explode("*/*",$d->info_cliente);
				$tabla .= "<tr class='linea_cli'><td colspan='2'>".$infoCli[0]."</td><td colspan='4'>Dias de Credito: ".$infoCli[1]."</td><td colspan='4'>$vencer</td></tr>";
			}
			
			if($d->Folio != $facAnterior)
			{
				$hasta = $_POST['f_cor'];
				if(!isset($_POST['f_cor']))
					$hasta = $_POST['f_fin'];

				if($d->id_tipo != '2')
					$saldos = floatval($d->ImporteDoc) - floatval($this->ReportesCuentasModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$hasta,0));
				else
					$saldos = $d->ImporteDoc;
				
				if(floatval(number_format($saldos,2)) != 0)
				{
					if($d->id_tipo != '2')
					{
						$fecha_venc = date("Y-m-d", strtotime("$d->fecha_documento + ".$infoCli[1]." days"));
						$diasVencidos	= ((strtotime('-7 hour',strtotime($_POST['f_cor']))-strtotime($fecha_venc))/86400)+1;
						//$diasVencidos	= (strtotime('-7 hour',strtotime(date('Y-m-d H:i:s')))-strtotime($fecha_venc))/86400;
		
						$diasVencidos = floor($diasVencidos);	

						if(isset($_POST['pronos']) && intval($diasVencidos))
							$diasVencidos = $diasVencidos*-1;
					}
					else
						$diasVencidos = $fecha_venc = '---';
						


						$tabla .= "<tr class='linea_fac'><td>$d->Folio</td><td>$d->fecha_documento</td><td>$fecha_venc</td><td>$diasVencidos</td><td>$ ".number_format($saldos,2)."</td>";
						$saldosTotal += $saldos;

						if($d->id_tipo == '2')
						{
							$tabla .= "<td></td><td></td><td></td><td></td><td></td>";
						}
						if(intval($diasVencidos)<1 && $d->id_tipo != '2')
						{
							$tabla .= "<td>$ ".number_format($saldos,2)."</td><td></td><td></td><td></td><td></td>";
							$saldosSin += $saldos;
						}

						if(intval($diasVencidos)>=1 && intval($diasVencidos)<=15 && $d->id_tipo != '2')
						{
							$tabla .= "<td></td><td>$ ".number_format($saldos,2)."</td><td></td><td></td><td></td>";
							$s1_15 += $saldos;
						}

						if(intval($diasVencidos)>=16 && intval($diasVencidos)<=30 && $d->id_tipo != '2')
						{
							$tabla .= "<td></td><td></td><td>$ ".number_format($saldos,2)."</td><td></td><td></td>";
							$s16_30 += $saldos;
						}

						if(intval($diasVencidos)>=31 && intval($diasVencidos)<=45 && $d->id_tipo != '2')
						{
							$tabla .= "<td></td><td></td><td></td><td>$ ".number_format($saldos,2)."</td><td></td>";
							$s31_45 += $saldos;
						}

						if(intval($diasVencidos)>45 && $d->id_tipo != '2')
						{
							$tabla .= "<td></td><td></td><td></td><td></td><td>$ ".number_format($saldos,2)."</td>";
							$sm45 += $saldos;
						}

							$tabla .= "</tr>";
				}
			}
			

			$cliAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		$tabla .= "<tr class='linea_final'><td colspan='3'></td><td>Saldo Final</td><td cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</td><td cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</td><td cantidad='$s1_15'>$ ".number_format($s1_15,2)."</td><td cantidad='$s16_30'>$ ".number_format($s16_30,2)."</td><td cantidad='$s31_45'>$ ".number_format($s31_45,2)."</td><td cantidad='$sm45'>$ ".number_format($sm45,2)."</td></tr>";
		$tabla .= "<tr class='linea_general'><td colspan='3'></td><td>Saldo General</td><td id='saldoGn'></td><td id='saldoSinGn'></td><td id='s1_15Gn'></td><td id='s16_30Gn'></td><td id='s31_45Gn'></td><td id='sm45Gn'></td></tr>";
		$tabla .= "<tr class='linea_porc'><td colspan='3'></td><td>Porcentaje</td><td id='saldoPc'>100%</td><td id='saldoSinPc'></td><td id='s1_15Pc'></td><td id='s16_30Pc'></td><td id='s31_45Pc'></td><td id='sm45Pc'></td></tr>";
		echo $tabla;
	}

	function pronos_cobros()
	{
		$listaClientes = $this->ReportesCuentasModel->listaClientes();
		require("views/cuentas/pronos_cobros.php");
	}
}
?>
