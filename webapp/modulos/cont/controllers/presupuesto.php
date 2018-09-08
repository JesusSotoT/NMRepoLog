<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/presupuesto.php");

class Presupuesto extends Common
{
	public $PresupuestoModel;

	function __construct(){
	$this->PresupuestoModel = new PresupuestoModel();
	$this->PresupuestoModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->PresupuestoModel->close();
	}

	function creaPresupuesto()
	{
		$segmentos = $this->PresupuestoModel->listaSegmentoSucursal(0);
		$segmentos1 = $this->PresupuestoModel->listaSegmentoSucursal(0);
		$sucursales = $this->PresupuestoModel->listaSegmentoSucursal(1);
		$sucursales1 = $this->PresupuestoModel->listaSegmentoSucursal(1);
		
		$listaCuentas = $this->PresupuestoModel->getAccounts();
		$listaCuentas1 = $this->PresupuestoModel->getAccounts();

		$ejercicios = $this->PresupuestoModel->listaEjercicios();
		$tipo_cuenta = $this->PresupuestoModel->tipoCuenta();

		$ex = $this->PresupuestoModel->ejercicioActual();
		$ex = $ex->fetch_object();

		session_start();
		if(!isset($_SESSION['idejercicio_actual']))
		{
			$_SESSION['idejercicio_actual'] = $ex->IdEx;
		}

		require('views/presupuestal/index.php');
	}

	function guardaPresupuesto()
	{
		echo $this->PresupuestoModel->guardaPresupuesto($_POST['IdEjercicio'],$_POST['IdCuenta'],$_POST['IdSegmento'],$_POST['IdSucursal'],$_POST['Anual'],$_POST['Meses'],$_POST['Act'],$_POST['Id']);
	}

	function listaPresupuestos()
	{
		session_start();
		$_SESSION['idejercicio_actual'] = $_POST['IdEjercicio'];

		$datos = $this->PresupuestoModel->listaPresupuesto($_POST['IdEjercicio'],$_POST['TipoCuenta']);
		$pinta = "";
		while($d = $datos->fetch_object())
		{
			$pinta .= "<tr style='border-bottom:1px solid gray;height:30px;background-color:#EEEEEE;' class='listar' ondblclick='cambiar($d->id)'><td><a href='javascript:eliminar($d->id)'><img src='images/eliminado.png' title='Eliminar' style='width:20px;'></a></td><td id='row-$d->id'>$d->cuenta</td><td>$d->segmento</td><td>$d->sucursal</td><td></td><td style='text-align:right;' class='anual' cantidad='$d->anual'>".number_format($d->anual,2)."</td><td></td>";
			$mes = explode("|",$d->meses);
			for($m = 0; $m<=11; $m++)
			{
				$pinta .= "<td style='text-align:right;' class='mes-$m' cantidad='".$mes[$m]."'>".number_format($mes[$m],2)."</td>";
			}
			$pinta .= "</tr>";
		}
		$pinta .= " <tr style='background-color:#BDBDBD;color:white;font-weight:bold;height:30px;'><td colspan='5'>TOTAL PRESUPUESTO ANUAL</td><td id='total-anual'></td><td></td><td id='total-0'></td><td id='total-1'></td><td id='total-2'></td><td id='total-3'></td><td id='total-4'></td><td id='total-5'></td><td id='total-6'></td><td id='total-7'></td><td id='total-8'></td><td id='total-9'></td><td id='total-10'></td><td id='total-11'></td></tr>";
		
		echo $pinta;
	}

	function eliminaPresupuesto()
	{
		$this->PresupuestoModel->eliminaPresupuesto($_POST['Id']);
	}
	function presupuestoxSaldos()
	{
		$cuenta 	= 	$_POST['cuenta'];
		$segmento 	= 	$_POST['segmento'];
		$sucursal 	= 	$_POST['sucursal'];
		$idejercicio=	$_POST['idejercicio'];
		$sumar		=	$_POST['sumar'];
		$prc 		= 	0;
		if(strpos($sumar, '%'))
			{
				$prc = 1;
				$sumar = str_replace("%", "", $sumar);

				$sumar = $sumar / 100;
				
				$sumar += 1;
				
			}
			else
			{
				if(intval($_POST['distrib']))
				{
					$sumar = floatval($sumar);
				}
				else
				{
					$sumar = floatval($sumar)/12;
				}
			}
		$meses='';
		$total=0;
		$cantidad = 0;
		$ejercicio = $this->PresupuestoModel->NombreEjercicio($idejercicio);
		$ejercicio = intval($ejercicio)-1;
		for($m=1;$m<=12;$m++)
		{
			$saldo = $this->PresupuestoModel->saldoCuenta($cuenta,"$ejercicio-".sprintf('%02d', (intval($m)))."-31",'Presupuesto',1,$segmento,0,$sucursal);
			
			if($m!=1)
				$meses .= "|";

			if(intval($prc))
			{
				if(!floatval($saldo))
				{
					$cantidad = 0;
				}
				else
				{
					$cantidad = $saldo * $sumar;
				}
			}
			else
			{
				$cantidad = $saldo + $sumar;
			}
			$meses .= $cantidad;
			$total += floatval($cantidad);

		}
		echo $total."/".$meses;
		//$this->PresupuestoModel->guardaPresupuesto($idejercicio,$cuenta,$segmento,$sucursal,$total,$meses);
	}

	function datosPresup()
	{
		$datos = $this->PresupuestoModel->datosPresup($_POST['idPresup']);
		$d = $datos->fetch_row();
		echo $d[0]."**//**".$d[1]."**//**".$d[2]."**//**".$d[3]."**//**".$d[4]."**//**".$d[5]."**//**".$d[6]; 
	}

	function cargaLay()
	{
		//Carga de datos de contpaq
			$target_dir = "importar/";

			if (isset($_FILES["presupuesto_xls"])) 
			{

				if($_FILES['presupuesto_xls']['name'])
				{
					if (move_uploaded_file($_FILES['presupuesto_xls']['tmp_name'], $target_dir.basename("presupuesto.xls" ) )) 
					{
						echo "El archivo se subio al sistema para validarse<br/>";
					} 
					else 
					{
						echo "No se subio el archivo<br/>";
					}
				}
				$info = $this->PresupuestoModel->cuentasconf();
				if($est=$info->fetch_object())
				{
					$strMask=$est->Estructura;
				}
				if(!mb_stristr($strMask, "-"))
				{
					$strSeparator = '.';
				}
				else
				{
					$strSeparator = '-';
				}
					//$strMask = '999.9999';
	    		
	    		include($target_dir."validaciones_presupuesto.php");	
	    		
				if($bandera_cuentas_inexistentes == '' && $bandera_segmentos == '' && $bandera_sucursales == '' && $bandera_suma == 0 && $bandera_repetidos == '')
				{
					$bandera_repetidos = '';
					$objCon = mysqli_connect($servidor,$usuariobd,$clavebd,$bd);
					for($i=1;$i<=count($listaPresup)-1;$i++)
					{
						$expl = explode('*',$listaPresup[$i]);
						$expl[0] = str_replace("<tr><td>", '', $expl[0]);
						$expl[1] = str_replace("</td><td>", '', $expl[1]);
						$expl[2] = str_replace("</td><td>", '', $expl[2]);
						$expl[2] = str_replace("</td></tr>", '', $expl[2]);

						if($ConfCuentas == 'm')
				        {
				            $expl[0] = maskAccount($expl[0],$strMask, $strSeparator);
				        }
						
						$existe = $this->PresupuestoModel->repetedBD($_POST['xls_ejercicio'],buscaIdCuenta($expl[0],$objCon),buscaIdSegmento($expl[1],$objCon),buscaIdSucursal($expl[2],$objCon));
						if(intval($existe))
							$bandera_repetidos .= $expl[0]."/".$expl[1]."/".$expl[2]."<br />";
					}
					mysqli_close($objCon);
					unset($objCon);
                  if($bandera_repetidos == '')
                  {
                  		include($target_dir."import_presupuesto.php");
						echo "<script type='text/javascript'>alert('Se cargó correctamente'); window.location = 'index.php?c=Presupuesto&f=creaPresupuesto'</script>";
                  }
                  else
                  {
                  		echo "<a href='javascript:window.print();'' id='imprimir'><img class='nmwaicons' src='../../netwarelog/design/default/impresora.png' border='0' title='Imprimir'></a><br /><b style='color:red;'>EL ARCHIVO TIENE ERRORES:</b><br />El layout tiene presupuestos que ya existen.<br />$bandera_repetidos<br /><a href='index.php?c=Presupuesto&f=creaPresupuesto'>Regresar a presupuestos</a>";
                  }
				}
				else
				{
					$mensaje = '';
					
					if($bandera_cuentas_inexistentes != '')
					{
						$mensaje .= "-Los siguientes cuentas no existen en el sistema: ".$bandera_cuentas_inexistentes."<br />";
					}
					

					if($bandera_segmentos != '')
					{
						$mensaje .= "-Los siguientes segmentos no existen: ".$bandera_segmentos."<br />";
					}

					if($bandera_sucursales != '')
					{
						$mensaje .= "-Las siguientes sucursales no existen: ".$bandera_sucursales."<br />";
					}

					if($bandera_suma != 0)
					{
						$mensaje .= "-La suma de todos los meses no coincide con el anual. Diferencia de $bandera_suma";
					}

					if($bandera_repetidos != "")
					{
						$mensaje .= "-Existen presupuestos repetidos en el layout:<br /><table><tr><th width=100>Cuenta</th><th width=100>Segmento</th><th width=100>Sucursal</th></tr>$bandera_repetidos</table>";
					}
		
					echo "<a href='javascript:window.print();'' id='imprimir'><img class='nmwaicons' src='../../netwarelog/design/default/impresora.png' border='0' title='Imprimir'></a><br /><b style='color:red;'>EL ARCHIVO TIENE ERRORES:</b><br />".$mensaje."<br /><a href='index.php?c=Presupuesto&f=creaPresupuesto'>Regresar a presupuestos</a>";
					//Resultados negativos de la validacion
				}

				unlink('importar/presupuesto.xls');
			}
			else
			{
				echo "<script type='text/javascript'>alert('Ocurrio un error al cargar los datos');window.location = 'index.php?c=Presupuesto&f=creaPresupuesto'</script>";
			}
	}
}
?>
