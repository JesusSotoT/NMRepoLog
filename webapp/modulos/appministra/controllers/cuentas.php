<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/cuentas.php");

class Cuentas extends Common
{
	public $CuentasModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->CuentasModel = new CuentasModel();
		$this->CuentasModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->CuentasModel->close();
	}

		//INICIAN FUNCIONES DE ENTRADAS
	function lista()
	{
		if(intval($_GET['t']))
			$lis_cli_prov = $this->CuentasModel->listaProveedores();
		else
			$lis_cli_prov = $this->CuentasModel->listaClientes();

		$monedas = $this->CuentasModel->listaMonedas();
		require("views/cuentas/lista.php");
	}

	function lista_cli()
	{

		$clientesAparecen = array();
		$ngrid = array();

		$tabla = "";
		$grid = array();
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->CuentasModel->listaClientesAntiguedad($_POST);
		$registros = $datos->num_rows;//Tiene registros
		$saldosTotal = $saldosSin = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $cliAnterior)
			{
				if($cont > 0)
				{
					
						if(floatval($registros))
						{
							$infoCliAnt = explode("*/*",$info_cli_anterior);
						
							if(floatval($saldosSin))
								$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";
							elseif(floatval($s1_15))
								$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por Vencer</span></center>";
							else
								$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";

							$nombre_cliProv = $infoCliAnt[0];
							if($infoCliAnt[0] == '')
								$nombre_cliProv = "(".$cliAnterior.") Cliente Borrado";


							if(intval($infoCliAnt[1]))
							{
								array_push($grid,array(
								'prov' => "<a href='index.php?c=cuentas&f=cuentasxcobrar&id=".$cliAnterior."'>".$infoCliAnt[0]."</a>",
								'retrasado' => "<span cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</span>",
								'0-30' => "<span cantidad='$s1_15'>$ ".number_format($s1_15,2)."</span>",
								'31-60' => "<span cantidad='$s16_30'>$ ".number_format($s16_30,2)."</span>",
								'61-90' => "<span cantidad='$s31_45'>$ ".number_format($s31_45,2)."</span>",
								'91omas' => "<span cantidad='$sm45'>$ ".number_format($sm45,2)."</span>",
								'estatus' => utf8_encode($estatus_m),
								'total' => "<span class='saldo_total' cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</span>",
								'actual_im' => $saldosTotal
								));
							}else{
								$clientesAparecen[$cliAnterior]=$infoCliAnt[0];
							}
						}
					
					$saldosSin = $saldosTotal = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
				}

				
				
			}
			$infoCli = explode("*/*",$d->info_cliente);
			if($d->Folio != $facAnterior)
			{
				$hasta = $_POST['f_cor'];

				if($d->id_tipo != '2')
					$saldos = floatval($d->ImporteDoc) - floatval($this->CuentasModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$hasta,0));
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

						if(intval($diasVencidos))
							$diasVencidos = $diasVencidos*-1;
					}
					else
						$diasVencidos = $fecha_venc = '---';
						
						$saldosTotal += $saldos;

						if(intval($diasVencidos)<1 && $d->id_tipo != '2')
						{
							$saldosSin += $saldos;
						}

						if(intval($diasVencidos)>=1 && intval($diasVencidos)<=30 && $d->id_tipo != '2')
						{
							$s1_15 += $saldos;
						}

						if(intval($diasVencidos)>=31 && intval($diasVencidos)<=60 && $d->id_tipo != '2')
						{
							$s16_30 += $saldos;
						}

						if(intval($diasVencidos)>=61 && intval($diasVencidos)<=90 && $d->id_tipo != '2')
						{
							$s31_45 += $saldos;
						}

						if(intval($diasVencidos)>91 && $d->id_tipo != '2')
						{
							$sm45 += $saldos;
						}

				}
			}
			
			$info_cli_anterior = $d->info_cliente;
			$cliAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		
		
			if(floatval($registros))
			{
				$infoCli = explode("*/*",$info_cli_anterior);
				if(floatval($saldosSin))
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";
				elseif(floatval($s1_15))
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por Vencer</span></center>";
				else
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";
				$nombre_cliProv = $infoCli[0];
							if($infoCli[0] == '')
								$nombre_cliProv = "(".$cliAnterior.") Cliente Borrado";

				if(intval($infoCli[1]))
				{
					array_push($grid,array(
							'prov' => "<a href='index.php?c=cuentas&f=cuentasxcobrar&id=".$cliAnterior."'>".$nombre_cliProv."</a>",
							'retrasado' => "<span cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</span>",
							'0-30' => "<span cantidad='$s1_15'>$ ".number_format($s1_15,2)."</span>",
							'31-60' => "<span cantidad='$s16_30'>$ ".number_format($s16_30,2)."</span>",
							'61-90' => "<span cantidad='$s31_45'>$ ".number_format($s31_45,2)."</span>",
							'91omas' => "<span cantidad='$sm45'>$ ".number_format($sm45,2)."</span>",
							'estatus' => utf8_encode($estatus_m),
							'total' => "<span class='saldo_total' cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</span>",
							'actual_im' => $saldosTotal
							));
				}else{
					$clientesAparecen[$cliAnterior]=$nombre_cliProv;
				}
			}

		session_start();

    	if(isset($_SESSION['novolver'])){
    		$ngrid['novolver']=1;
    	}else{
    		$ngrid['novolver']=0;
    	}
		$ngrid['clientes_validos']=$grid;
		$ngrid['clientes_no_validos']=$clientesAparecen;
		echo json_encode($ngrid);
	}

	function lista_prov()
	{
	
		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->CuentasModel->listaProveedoresAntiguedad($_POST);
		$grid = array();
		$registros = $datos->num_rows;//Tiene registros
		
		$saldosTotal = $saldosSin = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $provAnterior)
			{
				if($cont > 0)
					if(floatval($registros))
						{
							$infoProvAnt = explode("*/*",$info_prov_anterior);
						
							if(floatval($saldosSin))
								$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";
							elseif(floatval($s1_15))
								$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por Vencer</span></center>";
							else
								$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";
							$nombre_cliProv = $infoProvAnt[0];
							if($infoProvAnt[0] == '')
								$nombre_cliProv = "(".$provAnterior.") Proveedor Borrado";
							if(intval($infoProvAnt[1]))
							{
								array_push($grid,array(
								'prov' => "<a href='index.php?c=cuentas&f=cuentasxpagar&id=".$provAnterior."'>".$nombre_cliProv."</a>",
								'retrasado' => "<span cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</span>",
								'0-30' => "<span cantidad='$s1_15'>$ ".number_format($s1_15,2)."</span>",
								'31-60' => "<span cantidad='$s16_30'>$ ".number_format($s16_30,2)."</span>",
								'61-90' => "<span cantidad='$s31_45'>$ ".number_format($s31_45,2)."</span>",
								'91omas' => "<span cantidad='$sm45'>$ ".number_format($sm45,2)."</span>",
								'estatus' => utf8_encode($estatus_m),
								'total' => "<span class='saldo_total' cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</span>",
								'actual_im' => $saldosTotal
								));
							}
						}

				$saldosSin = $saldosTotal = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
				
			}
			
			$infoProv = explode("*/*",$d->info_proveedor);
			if($d->Folio != $facAnterior)
			{
				$hasta = $_POST['f_cor'];

				if($d->id_tipo != 'XXYY')
					$saldos = floatval($d->ImporteDoc) - floatval($this->CuentasModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$hasta,1));
				else
					$saldos = $d->ImporteDoc;
				
				if(floatval(number_format($saldos,2)) != 0)
				{
					if($d->id_tipo != 'XXYY')
					{
						$fecha_venc = date("Y-m-d", strtotime("$d->fecha_documento + ".$infoProv[1]." days"));
						$diasVencidos	= ((strtotime('-7 hour',strtotime($_POST['f_cor']))-strtotime($fecha_venc))/86400)+1;
		
						$diasVencidos = floor($diasVencidos);	

						if(intval($diasVencidos))
							$diasVencidos = $diasVencidos*-1;
					}
					else
						$diasVencidos = $fecha_venc = '---';
						
						$saldosTotal += $saldos;

						if(intval($diasVencidos)<1 && $d->id_tipo != 'XXYY')
						{
							$saldosSin += $saldos;
						}

						if(intval($diasVencidos)>=1 && intval($diasVencidos)<=30 && $d->id_tipo != 'XXYY')
						{
							$s1_15 += $saldos;
						}

						if(intval($diasVencidos)>=31 && intval($diasVencidos)<=60 && $d->id_tipo != 'XXYY')
						{
							$s16_30 += $saldos;
						}

						if(intval($diasVencidos)>=61 && intval($diasVencidos)<=90 && $d->id_tipo != 'XXYY')
						{
							$s31_45 += $saldos;
						}

						if(intval($diasVencidos)>91 && $d->id_tipo != 'XXYY')
						{
							$sm45 += $saldos;
						}

				}
			}
			

			$info_prov_anterior = $d->info_proveedor;
			$provAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}

		if(floatval($registros))
			{
				$infoProv = explode("*/*",$info_prov_anterior);
				if(floatval($saldosSin))
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";
				elseif(floatval($s1_15))
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por Vencer</span></center>";
				else
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";
				$nombre_cliProv = $infoProv[0];
							if($infoProv[0] == '')
								$nombre_cliProv = "(".$provAnterior.") Proveedor Borrado";
				if(intval($infoProv[1]))
				{
					array_push($grid,array(
							'prov' => "<a href='index.php?c=cuentas&f=cuentasxpagar&id=".$provAnterior."'>".$nombre_cliProv."</a>",
							'retrasado' => "<span cantidad='$saldosSin'>$ ".number_format($saldosSin,2)."</span>",
							'0-30' => "<span cantidad='$s1_15'>$ ".number_format($s1_15,2)."</span>",
							'31-60' => "<span cantidad='$s16_30'>$ ".number_format($s16_30,2)."</span>",
							'61-90' => "<span cantidad='$s31_45'>$ ".number_format($s31_45,2)."</span>",
							'91omas' => "<span cantidad='$sm45'>$ ".number_format($sm45,2)."</span>",
							'estatus' => utf8_encode($estatus_m),
							'total' => "<span class='saldo_total' cantidad='$saldosTotal'>$ ".number_format($saldosTotal,2)."</span>",
							'actual_im' => $saldosTotal
							));
				}
			}
			
		echo json_encode($grid);
		
	}

	function cuentasxpagar()
	{
		//$listaProveedores = $this->CuentasModel->listaProveedores();
		$listaFormasPago = $this->CuentasModel->listaFormasPago();
		$listaMonedas = $this->CuentasModel->listaMonedas();
		$hayMovs = $this->CuentasModel->hayMovs(1);

		require("views/cuentas/cuentasxpagar2.php");
	}

	function cuentasxcobrar()
	{
		//$listaClientes = $this->CuentasModel->listaClientes();
		$listaFormasPago = $this->CuentasModel->listaFormasPago();
		$listaMonedas = $this->CuentasModel->listaMonedas();
		$hayMovs = $this->CuentasModel->hayMovs(0);

		require("views/cuentas/cuentasxcobrar.php");
	}

	function datosClienteProv()
	{
		$datos = $this->CuentasModel->datosClienteProv($_POST['tipo'],$_POST['idcli'],$_POST['d']);
		echo json_encode($datos);
	}

	function listaFacturas()
	{
		global $xp;
		$datos = '';
		$total = 0;
		$listaFacturas = $this->CuentasModel->listaFacturas($_POST['idPrvCli'],$_POST['cobrar_pagar']);
		while($l = $listaFacturas->fetch_assoc())
		{
			if(intval($_POST['cobrar_pagar']))
				$foliosFac = $this->CuentasModel->foliosFac($l['id_oc']);
			//$file 	= "../cont/xmls/facturas/temporales/".$l['xmlfile'];
			//$texto 	= file_get_contents($file);
			//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			//$xml 	= new DOMDocument();
			//$xml->loadXML($texto);
			//$xp = new DOMXpath($xml);
			//$desc = $this->getpath("//@descripcion");
			$desc = $l['desc_concepto'];
			$vencimiento = new DateTime($l['fecha_factura']);
			if(intval($l['diascredito']))
				$vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));

			$estilo = '';
			if(strtotime($vencimiento->format('Y-m-d')) < strtotime(date()))
				$estilo = "style='color:red;'";


				$nuevoImp = floatval($l['importe_pesos']);


			$saldo = $nuevoImp - floatval($l['pagos']);
			if(round(floatval($saldo),2) > 0)
			{
				$moneda = $l['Moneda'];
				if(intval($_POST['cobrar_pagar']))
				{
					$datos 	.= "<tr><td><input type='checkbox' id='chkbx-".$l['id']."' moneda='$moneda' class='chk' onchange='habilita_input(".$l['id'].",\"\")'></td><td><a href='index.php?c=compras&f=ordenes&id_oc=".$l['id_oc']."&v=1' target='_blank'>".$l['id_oc']."</a></td><td id='folio-".$l['id']."'>".$foliosFac."</td><td>".$desc."</td><td>".$l['fecha_factura']."</td><td $estilo>".$vencimiento->format('Y-m-d')."</td><td>$ ".number_format($l['imp_factura'],2)." ".$moneda."</td><td id='saldo-".$l['id']."' saldo='$saldo'>$ ".number_format($saldo,2)."</td><td><input type='text' class='pagos_lista_f form-control' id='orel-".$l['id']."' orig='$saldo' value='0.00' onkeypress='return NumCheck(event, this)' onchange='act_suma(0)' disabled></td></tr>";
				}
				else
				{
					if(intval($l['origen']) == 1)
						$url = "index.php?c=ventas&f=ordenes&id_oventa=".$l['id_oventa']."&v=1";
					if(intval($l['origen']) == 2)
						$url = "../pos/ticket.php?idventa=".$l['id_oventa']."&print=0";

					$datos 	.= "<tr><td><input type='checkbox' id='chkbx-".$l['idres']."' moneda='$moneda' class='chk' onchange='habilita_input(".$l['id'].",\"\")'></td><td><a href='$url' target='_blank'>".$l['id_oventa']."</a></td><td id='folio-".$l['idres']."'>".$l['folio']."</td><td>".$desc."</td><td>".$l['fecha_factura']."</td><td $estilo>".$vencimiento->format('Y-m-d')."</td><td>$ ".number_format($l['imp_factura'],2)." ".$moneda."</td><td id='saldo-".$l['idres']."' saldo='$saldo'>$ ".number_format($saldo,2)."</td><td><input type='text' class='pagos_lista_f form-control' id='orel-".$l['id']."' orig='$saldo' value='0.00' onkeypress='return NumCheck(event, this)' onchange='act_suma(0)' disabled></td></tr>";
				}
			}

			$total += $saldo;

		}
		$datos .= "<tr style='font-weight:bold;'><td colspan='7'></td><td>$ ".number_format($total,2)."</td><td id='saldo_pag_0' saldo='0.00'>$ 0.00</td></tr>";
		$datos .= "<tr style='font-weight:bold;'><td colspan='7'></td><td>Diferencia</td><td id='dif_rel_0'></td></tr>";
		echo $datos;
	}

	function getpath($qry)
	{
		global $xp;
		$prm = array();
		$nodelist = $xp->query($qry);
		foreach ($nodelist as $tmpnode)
		{
    		$prm[] = trim($tmpnode->nodeValue);
    	}
		$ret = (sizeof($prm)<=1) ? $prm[0] : $prm;
		return($ret);
	}

	//---------------------------------------------------------------------
	function pagosSinAsignar()
	{
		$listaPagos = $this->CuentasModel->pagosCobrosSinAsignar($_POST['idPrv'],1);
		$datos = '';
		while($p = $listaPagos->fetch_object())
		{
			$saldoPesos = floatval($p->saldo) * floatval($p->tipo_cambio);
			if($saldoPesos > 0.009)
			{
				$button = "<i style='font-size:10px;'>*Aplicar desde el modulo de bancos.</i>";
				if(intval($p->origen) != 3)
					$button = "<button class='btn btn-primary' onclick='aplicar($p->id,\"$p->Moneda\")'>Aplicar</button>";
				$datos .= "<tr><td>$p->fecha_pago</td><td>$p->concepto</td><td>$ $p->abono $p->Moneda</td><td id='saldo_pago-$p->id' class='saldoApl' saldo='$saldoPesos'>$ ".number_format($saldoPesos,2)."</td><td>$p->fp</td><td>$button</td></tr>";
			}
		}
		echo $datos;
	}

	function cobrosSinAsignar()
	{
		$listaCobros = $this->CuentasModel->pagosCobrosSinAsignar($_POST['id'],0);
		$datos = '';
		while($p = $listaCobros->fetch_object())
		{
			$saldoPesos = floatval($p->saldo) * $p->tipo_cambio;
			if($saldoPesos > 0.009)
			{
				$button = "<i style='font-size:10px;'>*Aplicar desde el modulo de bancos.</i>";
				if(intval($p->origen) != 3)
					$button = "<button class='btn btn-primary' onclick='aplicar($p->id,\"$p->Moneda\")'>Aplicar</button>";
				$datos .= "<tr><td>$p->fecha_pago</td><td>$p->concepto</td><td>$ $p->abono $p->Moneda</td><td id='saldo_pago-$p->id' class='saldoApl' saldo='$saldoPesos'>$ ".number_format($saldoPesos,2)."</td><td>$p->fp</td><td>$button</td></tr>";
			}
		}
		echo $datos;
	}

	function guardarPagos()
	{
		echo $this->CuentasModel->guardarPagos($_POST);
	}

	function listaFolios()
	{
		$lista = $this->CuentasModel->listaFolios($_POST['idPrv']);
	}

	function listaCargos()
	{
		$datos = '';
		$total = 0;
		$listaCargos = $this->CuentasModel->listaCargos($_POST['idPrvCli'],$_POST['cobrar_pagar']);
		while($l = $listaCargos->fetch_assoc())
		{
			if(round(floatval($l['saldo']),2) > 0)
				$datos 	.= "<tr><td><input type='checkbox' id='chkbxCar-".$l['id']."' moneda='".$l['moneda']."' tipo_cambio='".$l['tipo_cambio']."' class='chkCar' onchange='habilita_input(".$l['id'].",\"Car\")'></td><td>".$l['id']."</td><td>".$l['concepto']."</td><td>".$l['fecha_pago']."</td><td>$ ".number_format($l['cargo'],2)." ".$l['moneda']."</td><td id='saldoC-".$l['id']."' saldo='".$l['saldo']."'>$ ".number_format($l['saldo'],2)."</td><td><input type='text' class='pagos_lista_c form-control' id='orel-".$l['id']."' orig='".$l['saldo']."' value='0.00' onkeypress='return NumCheck(event, this)' onchange='act_suma(1)' disabled></td></tr>";

			$total += $l['saldo'];
		}
		$datos .= "<tr style='font-weight:bold;'><td colspan='5'></td><td>$ ".number_format($total,2)."</td><td id='saldo_pag_1' saldo='0.00'>$ 0.00</td></tr>";
		$datos .= "<tr style='font-weight:bold;'><td colspan='5'></td><td>Diferencia</td><td id='dif_rel_1'></td></tr>";
		echo $datos;
	}

	function listaCargosFacturas()
	{
		$datos = array();

		$listaCargos = $this->CuentasModel->listaCargos($_POST['idPrvCli'],$_POST['cobrar_pagar']);
		while($l = $listaCargos->fetch_assoc())
		{
			$vencimiento = new DateTime($l['fecha_pago']);
			if(intval($l['diascredito']))
				$vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));

			$abonado = (floatval($l['cargo']) * floatval($l['tipo_cambio'])) - floatval($l['saldo']);
			
			$datetime1 = new DateTime(date('Y-m-d'));
				$datetime2 = $vencimiento;
				$interval = $datetime1->diff($datetime2);
				$difer = $interval->format('%R%a');

				if(intval($difer) >= 61)//Al corriente
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";

				if(intval($difer) <= 60 && intval($difer) >= 0)//por vencer
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por vencer</span></center>";

				if(intval($difer) < 0)//vencido
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";		

				if(number_format($l['saldo'],2) <= 0)//saldada
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-default'>Cuenta Saldada</span></center>";							

			array_push($datos,array(
						'fech_cargo' => $l['fecha_pago'],
						'fecha_venc' => $vencimiento->format('Y-m-d'),
						'concepto' => "<a href='index.php?c=cuentas&f=detalle&id=".$l['id']."&t=c&cp=".$_POST['cobrar_pagar']."'>".$l['concepto']."</a>",
						'folio' => '---',
						'moneda' => $l['moneda'],
						'monto' => "$ ".number_format($l['cargo'],2),
						'abonado' => "$ ".number_format($abonado,2),
						'actual' => "<span class='actual' cantidad='".$l['saldo']."'>$ ".number_format($l['saldo'],2)."</span>",
						'estatus' => $estatus_m,
						'ov' => '-',
						'actual_im' => $l['saldo']
							));
		}

		$listaFacturas = $this->CuentasModel->listaFacturas($_POST['idPrvCli'],$_POST['cobrar_pagar']);
		while($l = $listaFacturas->fetch_assoc())
		{
			//$foliosFac = $this->CuentasModel->foliosFac($l['id_oc']);
			//$file 	= "../cont/xmls/facturas/temporales/".$l['xmlfile'];
			//$texto 	= file_get_contents($file);
			//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			//$xml 	= new DOMDocument();
			//$xml->loadXML($texto);
			//$xp = new DOMXpath($xml);
			//$desc = $this->getpath("//@descripcion");
			$vencimiento = new DateTime($l['fecha_factura']);
			if(intval($l['diascredito']))
				$vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));
			$desc = $l['desc_concepto'];
			$datetime1 = new DateTime(date('Y-m-d'));
				$datetime2 = $vencimiento;
				$interval = $datetime1->diff($datetime2);
				$difer = $interval->format('%R%a');

				if(intval($difer) >= 61)//Al corriente
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";

				if(intval($difer) <= 60 && intval($difer) >= 0)//por vencer
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por vencer</span></center>";

				if(intval($difer) < 0)//vencido
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";


			$estilo = '';
			if(strtotime($vencimiento->format('Y-m-d')) < strtotime(date()))
				$estilo = "style='color:red;'";


				$nuevoImp = floatval($l['importe_pesos']);


			$saldo = $nuevoImp - floatval($l['pagos']);
			if(number_format($saldo,2) <= 0)//saldada
					$estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-default'>Cuenta Saldada</span></center>";		
															
			

				$abonado = floatval($nuevoImp) - floatval($saldo);
				
				if(intval($_POST['cobrar_pagar']))
				{
					if(intval($l['origen']) == 4)
					{
						$url = "";
						$idovc = "-";
					}
					else
					{
						$url = "index.php?c=compras&f=ordenes&id_oc=".$l['id_oc']."&v=1";
						$idovc = "<a href='$url' target='_blank'>".$l['id_oc']."</a>";
					}
				}
				else
				{
					if(intval($l['origen']) == 1)
					{
						$url = "index.php?c=ventas&f=ordenes&id_oventa=".$l['id_oventa']."&v=1";
						$idovc = "<a href='$url' target='_blank'>".$l['id_oventa']."</a>";
					}
					if(intval($l['origen']) == 2)
					{
						$url = "../pos/ticket.php?idventa=".$l['id_oventa']."&print=0";
						$idovc = "<a href='$url' target='_blank'>".$l['id_oventa']."</a>";
					}
					if(intval($l['origen']) == 4)
					{
						$url = "";
						$idovc = "-";
					}
					
				}
					
				if(!isset($l['folio']))
				{
					$fac = $l['xmlfile'];
					$fac = explode('_',$fac);
					if($fac[0] != '')
						$l['folio'] = $fac[0];
					else
						$l['folio'] = $fac[2];
				}

				$fol = "(".$l['folio'].") ";
				if(intval($l['origen']) == 4)
					$fol = '';

				array_push($datos,array(
							'fech_cargo' => $l['fecha_factura'],
							'fecha_venc' => $vencimiento->format('Y-m-d'),
							'concepto' => "<a href='index.php?c=cuentas&f=detalle&id=".$l['id']."&t=f&cp=".$_POST['cobrar_pagar']."&ori=".$l['origen']."'>$fol $desc</a>",
							'folio' => $l['folio_fac'],
							'moneda' => $l['Moneda'],
							'monto' => "$ ".number_format($l['imp_factura'],2),
							'abonado' => "$ ".number_format($abonado,2),
							'actual' => "<span class='actual' cantidad='$saldo'>$ ".number_format(round($saldo,2),2)."</span>",
							'estatus' => $estatus_m,
							'ov' => $idovc,
							'actual_im' => $saldo
								));
			
		}
		echo json_encode($datos);
	}

	function detalle()
	{
		$listaFormasPago = $this->CuentasModel->listaFormasPago();
		$listaMonedas = $this->CuentasModel->listaMonedas();
		$datos_cli_prov = $this->CuentasModel->info_car_fac($_GET['id'],$_GET['t'],$_GET['cp'],$_GET['ori']);

		require("views/cuentas/detalle.php");
	}

	function pagos_detalle()
	{
		$tabla = '';
		$datos = $this->CuentasModel->pagos_detalle($_POST['id'],$_POST['t']);
		$saldo = $this->CuentasModel->info_car_fac($_POST['id'],$_POST['t'],$_POST['cp'],$_POST['ori']);
		$saldo = $saldo['cargo'];

		while($d = $datos->fetch_assoc())
		{
			$saldo_final = floatval($saldo) - floatval($d['abono']);
			$d['fecha_pago'] = explode(" ",$d['fecha_pago']);
			if(intval($d['origen']) == 3)
				$origen = "Bancos";
			else
				$origen = "Appministra";

			$tabla .= "<tr><td>".$d['fecha_pago'][0]."</td><td cantidad='".$d['abono']."'>$ ".number_format(round($d['abono'],2),2)." MXN</td><td>$origen</td><td>".$d['forma_pago']."</td><td>$ ".number_format(round($saldo,2),2)." MXN</td><td cantidad='".round($saldo_final,2)."'>$ ".number_format(round($saldo_final,2),2)." MXN</td><td><a href='javascript:printer_s(".$d['id_pago'].",\"".$_POST['t']."\",".$_POST['cp'].",".$d['id_rel'].",".$_POST['ori'].")'><span class='glyphicon glyphicon-print'></span></a></td></tr>";
			$saldo = $saldo_final;
		}
		echo $tabla;
	}

	function guardar_relacion()
	{
		echo $this->CuentasModel->guardar_relacion($_POST['idpago'],$_POST['idrelaciones'],$_POST['tipo'],$_POST['valores'],$_POST['monedas'],$_POST['monedaPago']);
	}

	function guardar_relacion_ret()
	{
		$idrelaciones = $_POST['docu']."@|@";
		$cantidad = $_POST['cantidad']."@|@";
		$monedas = "1@|@";
		$tipo = 0;
		if($_POST['tipo'] == 'f')
			$tipo = 1;
		echo $this->CuentasModel->guardar_relacion($_POST['idpago'],$idrelaciones,$tipo,$cantidad,$_POST['monedas'],$_POST['monedaPago']);
	}

	function info_pago()
	{
		$datos = $this->CuentasModel->info_pago2($_POST['idpago']);
		
		$datos['fecha_pago'] = str_replace(' ', 'T', $datos['fecha_pago']);

		$array = array('fecha_pago' => $datos['fecha_pago'],
						'forma_pago' => $datos['forma_pago'],
						'moneda' => $datos['moneda'],
						'monto' => $datos['abono']);
		echo json_encode($array);

	}

	function printer()
	{
		$organizacion = $this->CuentasModel->organizacion();
		$info_pago = $this->CuentasModel->info_pago($_POST['idpago'],$_POST['cp']);
		$cajero = $this->CuentasModel->usuario($_SESSION["accelog_idempleado"]);
		if($_POST['cp'])
		{
			$tipo_comp = "Pago";
			$tit_cli_prov = "Proveedor:";
		}
		else
		{
			$tipo_comp = "Cobro";
			$tit_cli_prov = "Cliente:";
		}
		if($_POST['proc_final'])
		{
			$idrelaciones = explode("@|@",$_POST['idrelaciones']);
			$valores = explode("@|@",$_POST['valores']);
			$monedas = explode("@|@",$_POST['monedas']);
			$monedaPago = explode("@|@",$_POST['monedaPago']);
			$limite = count($idrelaciones)-2;
		}
		else
		{
			$info_rel = $this->CuentasModel->info_rel($_POST['idrel'],$_POST['cp']);
			$idrelaciones[0] = $info_rel['id_documento'];
			$valores[0] = floatval($info_rel['abono']) * floatval($info_rel['tipo_cambio']);
			$monedas[0] = $info_rel['id_moneda'];
			$monedaPago[0] = $info_rel['monedaPago'];
			$limite = 0;
		}
		
		$pagos = "";
		$total = 0;
        for($i=0;$i<=$limite;$i++)
        {
        	$pagos .= "<tr><td>".$this->CuentasModel->concepto_docu($idrelaciones[$i],$_POST['tipo'],$_POST['cp'],$_POST['ori'])."</td><td style='text-align:right;'>$ ".number_format(round($valores[$i],2),2)."</td></tr>";
        	$total += floatval($valores[$i]);
        }

		require("views/cuentas/ticket.php");
	}

	function saldoGral()
	{
		echo $this->CuentasModel->saldoGral($_POST);
	}

	function tipo_cambio()
	{
		echo $this->CuentasModel->tipo_cambio($_POST['fecha']);
	}

	function subeLayout()
    {
        $directorio = "importacion/";
        if (isset($_FILES["layout"]))
        {
                if($_FILES['layout']['name'])
                {
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("cuentas".$_GET['t']."_temp.xls")))
                    {
                        echo "Validando archivo...<br/>";
                        include($directorio."import_cuentas.php");
                    }
                    else
                    {
                        echo "No se subio el archivo de Cuentas <br/>";
                    }
                }
        }
    }

    function cuentas_sis_anterior()
    {
    	echo $this->CuentasModel->cuentas_sis_anterior($_POST);
    }

    function novolver()
    {
    	session_start();

    	$_SESSION['novolver']=true;
    }

    function info_rel2()
    {
    
    	$datos = $this->CuentasModel->info_rel2($_POST['idrel']);
    	
    	$json = json_decode($datos['json']);
		$json = $this->object_to_array($json);

		if($datos['version'] == '3.3')
			$metodo_pago = $json['Comprobante']['@MetodoPago'];
		else
		{
			$metodo_pago = $json['Comprobante']['@formaDePago'];
			if(strpos($metodo_pago,'exhi') !== FALSE || strpos($metodo,'EXHI') !== FALSE)
				$metodo_pago = "PUE";
			if(strpos($metodo,'parcial') !== FALSE || strpos($metodo,'PARCIAL') !== FALSE)
				$metodo_pago = "PPD";
		}
		
		if($datos['info_ult_rel'] != '0')
		{
			$info_ult_rel = explode('*|*',$datos['info_ult_rel']);
			$parcialidad = $info_ult_rel[0];
			$ImpSaldoAnt = $info_ult_rel[1];
		}
		else
		{
			$parcialidad = 1;
			$ImpSaldoAnt = 0;
		}

    	$array = array('uuid' => $datos['uuid'],
						'folio' => $datos['folio'],
						'moneda' => $datos['moneda'],
						'metodo_pago' => $metodo_pago,
						'parcialidad' => $parcialidad,
						'ImpSaldoAnt' => $ImpSaldoAnt,
						'version' => $datos['version']);
		echo json_encode($array);
    }

   	function object_to_array($data) {
		if (is_array($data) || is_object($data)) {
			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
		return $data;
	}
}
?>
