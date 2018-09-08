<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/dash.php");

class Dash extends Common
{
	public $DashModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->DashModel = new DashModel();
		$this->DashModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->DashModel->close();
	}

	function dash()
	{
		require('views/dash/dash.php');
	}
	function cunetas_pagar(){
        $cunetas_pagar = $this->DashModel->cunetas_pagar();
        echo json_encode($cunetas_pagar);
    }

	function ant_saldos(){
		require("views/dash/reporte_ant_saldos.php");
	}
	function ant_saldos_reporte(){
		date_default_timezone_set('America/Mexico_City');
        $hoy = date('Y-m-d');
        $_POST['f_cor'] = $hoy;
        $saldoFinal = $saldoSinV = $saldo1_15 = $saldo16_30 = $saldo31_45 = $saldo45mas = '';

		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->DashModel->ant_saldos_reporte($_POST);

		$vencer = "Vencido";
		if(isset($_POST['pronos']))
			$vencer = "Por Vencer";

		$saldosTotal = $saldosSin = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $provAnterior)
			{
				if($cont > 0)
				$saldosSin = $saldosTotal = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
				$infoProv = explode("*/*",$d->info_proveedor);
			}
			
			if($d->Folio != $facAnterior)
			{
				$hasta = $hoy;
				//if(!isset($hoy))
				//	$hasta = $_POST['f_fin'];
				if($d->id_tipo != 'XXYY')
					$saldos = floatval($d->ImporteDoc) - floatval($this->DashModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$hasta,1));
				else
					$saldos = $d->ImporteDoc;
				
				if(floatval(number_format($saldos,2)) != 0)
				{
					if($d->id_tipo != 'XXYY')
					{
						$fecha_venc = date("Y-m-d", strtotime("$d->fecha_documento + ".$infoProv[1]." days"));
						$diasVencidos	= ((strtotime('-7 hour',strtotime($hoy))-strtotime($fecha_venc))/86400)+1;
		
						$diasVencidos = floor($diasVencidos);	

						if(isset($_POST['pronos']) && intval($diasVencidos))
							$diasVencidos = $diasVencidos*-1;
					}
					else

						$foliosFac = $d->Folio;
						if(intval($d->Fac))
							$foliosFac = $this->DashModel->foliosFac($d->Folio);
						
						//$saldosTotal += $saldos;
						if(intval($diasVencidos)<1 && $d->id_tipo != 'XXYY')
						{													
							$saldoSinV += $saldos;
						}
						if(intval($diasVencidos)>=1 && intval($diasVencidos)<=15 && $d->id_tipo != 'XXYY')
						{													
							$saldo1_15 += $saldos;
						}
						if(intval($diasVencidos)>=16 && intval($diasVencidos)<=30 && $d->id_tipo != 'XXYY')
						{									
							$saldo16_30 += $saldos;
						}
						if(intval($diasVencidos)>=31 && intval($diasVencidos)<=45 && $d->id_tipo != 'XXYY')
						{							
							$saldo31_45 += $saldos;
						}
						if(intval($diasVencidos)>45 && $d->id_tipo != 'XXYY')
						{				
							$saldo45mas += $saldos;
						}
				}
			}
			
			$saldoFinal += $saldos;
			$provAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}

		//echo $saldoFinal.' '.$saldoSinV.' '.$saldo1_15.' '.$saldo16_30.' '.$saldo31_45.' '.$saldo45mas;

		$saldoFinal = ($saldoFinal == '') ? '' : '$'.number_format($saldoFinal,2);
		$saldoSinV = ($saldoSinV == '') ? '' : '$'.number_format($saldoSinV,2);
		$saldo1_15 = ($saldo1_15 == '') ? '' : '$'.number_format($saldo1_15,2);
		$saldo16_30 = ($saldo16_30 == '') ? '' : '$'.number_format($saldo16_30,2);
		$saldo31_45 = ($saldo31_45 == '') ? '' : '$'.number_format($saldo31_45,2);
		$saldo45mas = ($saldo45mas == '') ? '' : '$'.number_format($saldo45mas,2);

		$cuentasPagar[] = array(
                        saldoFinal   => $saldoFinal,
                        saldoSinV    => $saldoSinV,
                        saldo1_15    => $saldo1_15,
                        saldo16_30   => $saldo16_30,
                        saldo31_45   => $saldo31_45,
                        saldo45mas   => $saldo45mas,
                        );
		echo json_encode($cuentasPagar);
	}

	/////////////////////////

	function ant_saldos_cxc(){
		require("views/dash/reporte_ant_saldos_cxc.php");
	}
	function ant_saldos_reporte_cxc(){
		date_default_timezone_set('America/Mexico_City');
        $hoy = date('Y-m-d');
        $_POST['f_cor'] = $hoy;
        $saldoFinal = $saldoSinV = $saldo1_15 = $saldo16_30 = $saldo31_45 = $saldo45mas = '';

		$d->id_prov_cli = "0";
		$cont = 0;
		$datos = $this->DashModel->ant_saldos_reporte_cxc($_POST);

		$vencer = "Vencido";
		if(isset($_POST['pronos']))
			$vencer = "Por Vencer";

		$saldosTotal = $saldosSin = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
		while($d = $datos->fetch_object())
		{
			if($d->id_prov_cli != $cliAnterior)
			{
				if($cont > 0)					
				$saldosSin = $saldosTotal = $s1_15 = $s16_30 = $s31_45 = $sm45 = 0;
				$infoCli = explode("*/*",$d->info_cliente);				
			}
			
			if($d->Folio != $facAnterior)
			{
				$hasta = $hoy;
				if(!isset($_POST['f_cor']))
					$hasta = $_POST['f_fin'];

				if($d->id_tipo != '2')
					$saldos = floatval($d->ImporteDoc) - floatval($this->DashModel->saldoInicialFactura($d->id_documento,$d->id_tipo,$hasta,0));
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
												
						$saldosTotal += $saldos;
						$saldoFinal += $saldos;
					
						if(intval($diasVencidos)<1 && $d->id_tipo != '2')
						{
							$saldoSinV += $saldos;
						}

						if(intval($diasVencidos)>=1 && intval($diasVencidos)<=15 && $d->id_tipo != '2')
						{
							$saldo1_15 += $saldos;
						}

						if(intval($diasVencidos)>=16 && intval($diasVencidos)<=30 && $d->id_tipo != '2')
						{
							$saldo16_30 += $saldos;
						}

						if(intval($diasVencidos)>=31 && intval($diasVencidos)<=45 && $d->id_tipo != '2')
						{
							$saldo31_45 += $saldos;
						}

						if(intval($diasVencidos)>45 && $d->id_tipo != '2')
						{
							$saldo45mas += $saldos;
						}	
				}
			}
			
			$cliAnterior = $d->id_prov_cli;
			$facAnterior = $d->Folio;
			$cont++;
		}
		

		//echo $saldoFinal.' - '.$saldoSinV.' - '.$saldo1_15.' - '.$saldo16_30.' - '.$saldo31_45.' - '.$saldo45mas;

		$saldoFinal = ($saldoFinal == '') ? '' : '$'.number_format($saldoFinal,2);
		$saldoSinV = ($saldoSinV == '') ? '' : '$'.number_format($saldoSinV,2);
		$saldo1_15 = ($saldo1_15 == '') ? '' : '$'.number_format($saldo1_15,2);
		$saldo16_30 = ($saldo16_30 == '') ? '' : '$'.number_format($saldo16_30,2);
		$saldo31_45 = ($saldo31_45 == '') ? '' : '$'.number_format($saldo31_45,2);
		$saldo45mas = ($saldo45mas == '') ? '' : '$'.number_format($saldo45mas,2);

		$cuentasCobrar[] = array(
                        saldoFinal   => $saldoFinal,
                        saldoSinV    => $saldoSinV,
                        saldo1_15    => $saldo1_15,
                        saldo16_30   => $saldo16_30,
                        saldo31_45   => $saldo31_45,
                        saldo45mas   => $saldo45mas,
                        );
		echo json_encode($cuentasCobrar);
	}


}


?>