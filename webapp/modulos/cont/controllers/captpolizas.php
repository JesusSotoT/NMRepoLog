<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/captpolizas.php");

class CaptPolizas extends Common
{
	public $CaptPolizasModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->CaptPolizasModel = new CaptPolizasModel();
		$this->CaptPolizasModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->CaptPolizasModel->close();
	}

	//Funcion que genera la vista inicial donde se presentan las polizas del periodo
	function Ver()//papy
	{
		$Exercise       = $this->CaptPolizasModel->getExerciseInfo();
		$Ex             = $Exercise->fetch_assoc();
		$firstExercise  = $this->CaptPolizasModel->getFirstLastExercise(0);
		$lastExercise   = $this->CaptPolizasModel->getFirstLastExercise(1);
		$Suc            = $this->CaptPolizasModel->getSegmentoInfo();
		$allExercises   = $this->CaptPolizasModel->getAllExercises();
		unset($_SESSION['anticipo']);
		unset($_SESSION['menuver']);

		//Vemos si se declaro periodos abiertos en la configuracion
		$periodos_abiertos = $this->CaptPolizasModel->periodoAbierto();
		$pa = $periodos_abiertos->fetch_assoc();

		//echo "Sesiones: ".$_SESSION['ejercicio']."/".$_SESSION['idejercicio']."/".$_SESSION['periodo']."<br />";
		//echo "Variables: ".$Ex['EjercicioActual']."/".$Ex['IdEx']."/".$Ex['PeriodoActual']."<br />";
		if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
		{
			//Si las variables de sesion estan vacias las carga con los valores de la configuracion, sino las variables toman el valor de la sesion
			$botonReestablecer="";
			if(isset($_COOKIE['ejercicio']) AND isset($_COOKIE['periodo']))
			{
				//Almacena el valor de la sesion con los valores originales de la configuracion
				$Ex['EjercicioActual'] 		= $_COOKIE['ejercicio'];
				$Ex['IdEx'] 				= $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
				$Ex['PeriodoActual'] 		= $_COOKIE['periodo'];
				$botonReestablecer 			= "<a href='javascript:valoresConf()'>Reestablecer periodo y ejercicio de la configuración</a>";
			}

			/////////////////////////

			//echo "<br />Sesiones: ".$_SESSION['ejercicio']."/".$_SESSION['idejercicio']."/".$_SESSION['periodo']."<br />";
			//echo "Variables: ".$Ex['EjercicioActual']."/".$Ex['IdEx']."/".$Ex['PeriodoActual'];

		 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$tipoinstancia = $this->CaptPolizasModel->tipoinstancia();
			require('views/captpolizas/verpolizas.php');

		}
		else
		{
			echo "Segmentos: ".$Suc;
			echo "<br /><center><b style='color:red;font-size:20px;'>Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.</b></center>";
			echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config&f=mainPage','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
		}
	}

	function ListaSinConfirmar()
	{
		if($this->CaptPolizasModel->confirm())
		{
			$Exercise 		= $this->CaptPolizasModel->getExerciseInfo();
			$Ex 			= $Exercise->fetch_assoc();
			$firstExercise 	= $this->CaptPolizasModel->getFirstLastExercise(0);
			$lastExercise 	= $this->CaptPolizasModel->getFirstLastExercise(1);
			$Suc 			= $this->CaptPolizasModel->getSegmentoInfo();
			$allExercises = $this->CaptPolizasModel->getAllExercises();
			unset($_SESSION['anticipo']);
			unset($_SESSION['menuver']);
			//echo "Sesiones: ".$_SESSION['ejercicio']."/".$_SESSION['idejercicio']."/".$_SESSION['periodo']."<br />";
			//echo "Variables: ".$Ex['EjercicioActual']."/".$Ex['IdEx']."/".$Ex['PeriodoActual']."<br />";
			if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
			{
				//Si las variables de sesion estan vacias las carga con los valores de la configuracion, sino las variables toman el valor de la sesion
				$botonReestablecer="";
				if(isset($_COOKIE['ejercicio']) AND isset($_COOKIE['periodo']))
				{
					//Almacena el valor de la sesion con los valores originales de la configuracion
					$Ex['EjercicioActual'] 		= $_COOKIE['ejercicio'];
					$Ex['IdEx'] 				= $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
					$Ex['PeriodoActual'] 		= $_COOKIE['periodo'];
					$botonReestablecer 			= "<a href='javascript:valoresConf()'>Reestablecer periodo y ejercicio de la configuración</a>";
				}

				/////////////////////////

				//echo "<br />Sesiones: ".$_SESSION['ejercicio']."/".$_SESSION['idejercicio']."/".$_SESSION['periodo']."<br />";
				//echo "Variables: ".$Ex['EjercicioActual']."/".$Ex['IdEx']."/".$Ex['PeriodoActual'];

			 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx'],1);
				$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx'],1);
				$tipoinstancia = $this->CaptPolizasModel->tipoinstancia();
				require('views/captpolizas/verpolizasconfirm.php');

			}
			else
			{
				echo "Segmentos: ".$Suc;
				echo "<br /><center><b style='color:red;font-size:20px;'>Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.</b></center>";
				echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config&f=mainPage','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
			}
		}
	}

	function Confirmar()
	{
		echo $this->CaptPolizasModel->Confirmar($_POST);
	}

	//Funcion que crea la pantalla de captura de polizas
	function Capturar()
	{
		$numPoliza 	=	$this->CaptPolizasModel->getLastNumPoliza();
		$Exercise 	= $this->CaptPolizasModel->getExerciseInfo();
		$estructura = $this->CaptPolizasModel->getExerciseInfo();
		$estructura = $estructura->fetch_assoc();
		$todas_facturas = $estructura['TodasFacturas'];
		$estructura = $estructura['Estructura'];
		$Ex = $Exercise->fetch_assoc();

		if ($Ex['siete_dimensiones'] == 1) {
			$mostrar_dim = 'block';
		} else {
			$mostrar_dim = 'none';
		}

		if(isset($_COOKIE['ejercicio']) AND isset($_COOKIE['periodo']))
			{
				//Almacena el valor de la sesion con los valores originales de la configuracion
				$Ex['EjercicioActual'] 		= $_COOKIE['ejercicio'];
				$Ex['IdEx'] 				= $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
				$Ex['PeriodoActual'] 		= $_COOKIE['periodo'];
			}

		if($Ex['PeriodoActual'] == 13)//Si se trata del periodo 13
		{
			$this->CaptPolizasModel->MovimientosCierreEjercicio($numPoliza['id'],$Ex['IdEx'],$Ex['EjercicioActual']);
		}

		$ListaTiposPolizas 	=	$this->CaptPolizasModel->ListaTiposPoliza();
		$ListaSegmentos 		=	$this->CaptPolizasModel->ListaSegmentos();
		$ListaSucursales 	=	$this->CaptPolizasModel->ListaSucursales();
		$type_id_account 	=	$this->CaptPolizasModel->CuentaTipoCaptura();
		if(isset($_SESSION['anticipo'])){
			$Accounts	=	$this->CaptPolizasModel->getAccountGastos($type_id_account);
		} else{
			$Accounts =	$this->getAccounts($type_id_account);
		}
		$Providers =	$this->CaptPolizasModel->getProviders();
		$ExercisesList = $this->CaptPolizasModel->exercisesList();
		$formapago = $this->CaptPolizasModel->formapago();
		$forma_pago	=	$this->CaptPolizasModel->formapago();
		$ietucli = 	$this->CaptPolizasModel->ietu();
		$listabancos = $this->CaptPolizasModel->listabancos();
		$beneficiario	=	$this->CaptPolizasModel->proveedorparacaptura();
		$empleados =	$this->CaptPolizasModel->empleadosRegistrados();
		$manualnumpol	=	$this->CaptPolizasModel->manualnumpol();
		$numpol	=	$this->CaptPolizasModel->numPolSis($numPoliza['id']);
		$listacuentasbancarias = $this->CaptPolizasModel->cuentasbancariaslista();
		$usuarios = $this->CaptPolizasModel->usuarios();//usuarios deudores
		$usuario_creacion = $this->CaptPolizasModel->infousuario($_SESSION["accelog_idempleado"]);
		$usuario_modificacion = $this->CaptPolizasModel->infousuario($_SESSION["accelog_idempleado"]);
		$beneficiariolist = $this->CaptPolizasModel->proveedorparacaptura();
		$empleadoslist	= $this->CaptPolizasModel->empleadosRegistrados();
		$tablas = $this->obtener_selects_virbac();

		require('views/captpolizas/capturapolizas.php');
	}

	//Funcion que crea la pantalla de modificacion de poliza
	function ModificarPoliza()
	{//ganzo
		$Exercise 	= $this->CaptPolizasModel->getExerciseInfo();
		$estructura = $this->CaptPolizasModel->getExerciseInfo();
		$estructura = $estructura->fetch_assoc();
		$todas_facturas = $estructura['TodasFacturas'];
		//echo "c:".$todas_facturas;
		$estructura = $estructura['Estructura'];
		$Ex 		= $Exercise->fetch_assoc();

		if(isset($_COOKIE['ejercicio']) AND isset($_COOKIE['periodo']))
			{
				//Almacena el valor de la sesion con los valores originales de la configuracion
				$Ex['EjercicioActual'] 		= $_COOKIE['ejercicio'];
				$Ex['IdEx'] 				= $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
				$Ex['PeriodoActual'] 		= $_COOKIE['periodo'];
			}

		$PolizaInfo 			= $this->CaptPolizasModel->GetAllPolizaInfo($_GET['id']);
		if($PolizaInfo['relacionExt']){
			$relacion 			= $this->CaptPolizasModel->GetAllPolizaInfo($PolizaInfo['relacionExt']);
		}
		$relacion2				= $this->moviextranjeros2($Ex['IdEx'],$_GET['id'],$Ex['PeriodoActual']);

		$usuario_creacion = $this->CaptPolizasModel->infousuario($PolizaInfo['usuario_creacion']);
		$usuario_modificacion = $this->CaptPolizasModel->infousuario($PolizaInfo['usuario_modificacion']);

		if($PolizaInfo['Anticipo']==1){
			$_SESSION['anticipo']=1;
		}else{ unset($_SESSION['anticipo']);}
		$_SESSION['menuver']=1;
		$ListaTiposPolizas 	= $this->CaptPolizasModel->ListaTiposPoliza();
		$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
		$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
		$type_id_account = $this->CaptPolizasModel->CuentaTipoCaptura();
		$Accounts = $this->getAccounts($type_id_account);
		$Providers = $this->CaptPolizasModel->getProviders();
		$ExercisesList = $this->CaptPolizasModel->exercisesList();
		$formapago = $this->CaptPolizasModel->formapago();
		$ietucli = $this->CaptPolizasModel->ietu();
		$forma_pago	= $this->CaptPolizasModel->formapago();

		$listabancos = $this->CaptPolizasModel->listabancos();//IBM
		$beneficiario	= $this->CaptPolizasModel->proveedorparacaptura();
		$empleados = $this->CaptPolizasModel->empleadosRegistrados();
		$idformapago = $this->CaptPolizasModel->formapagoparalistado($_GET['id']);
		$manualnumpol	= $this->CaptPolizasModel->manualnumpol();
		$numpol	= $this->CaptPolizasModel->numPolSis($_GET['id']);
		$numeroorigen = $this->CaptPolizasModel->infobancariaid($PolizaInfo['idCuentaBancariaOrigen']);
		$listacuentasbancarias = $this->CaptPolizasModel->cuentasbancariaslista();
		$usuarios = $this->CaptPolizasModel->usuarios();
		$bancos = $this->CaptPolizasModel->validaBancos();
		$beneficiariolist = $this->CaptPolizasModel->proveedorparacaptura();
		$empleadoslist = $this->CaptPolizasModel->empleadosRegistrados();

		if(!isset($_REQUEST['bancos'])){
			if($bancos==1){
				if($PolizaInfo['idDocumento']>0){
					$_REQUEST['bancos']=1;
				}
			}
		}

		require('views/captpolizas/actpolizas.php');//ibm

	}

	function GuardaPolXls()
	{
		//Carga de datos de contpaq
			$target_dir = "importar/";

			if (isset($_FILES["polizas_xls"]))
			{

				if($_FILES['polizas_xls']['name'])
				{
					if (move_uploaded_file($_FILES['polizas_xls']['tmp_name'], $target_dir.basename("polizas2.xls" ) ))
					{
						echo "El archivo se subio al sistema para validarse<br/>";
					}
					else
					{
						echo "No se subio el archivo<br/>";
					}
				}
				$info = $this->CaptPolizasModel->cuentasconf();
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

	    		include($target_dir."validaciones_polizas.php");

				if($banderaxmls == '' && $bandera_cuentas_inexistentes == '' && $bandera_cuentas_mayor == '' && $bandera_segmentos == '')
				{
					include($target_dir."import_polizas.php");
					echo "<script type='text/javascript'>alert('Se cargó correctamente'); window.location = 'index.php?c=CaptPolizas&f=Ver'</script>";
				}
				else
				{
					$mensaje = '';
					if($banderaxmls != '')
					{
						$mensaje .= "-Los siguientes xmls no existen en el sistema: ".$banderaxmls."<br />";
					}
					if($bandera_cuentas_inexistentes != '')
					{
						$mensaje .= "-Los siguientes cuentas no existen en el sistema: ".$bandera_cuentas_inexistentes."<br />";
					}
					if($bandera_cuentas_mayor != '')
					{
						$mensaje .= "-Las siguientes cuentas de mayor o de título no puede ser afectadas: ".$bandera_cuentas_mayor."<br />";
					}

					if($bandera_segmentos != '')
					{
						$mensaje .= "-Los siguientes segmentos no existen: ".$bandera_segmentos;
					}

					echo "<a href='javascript:window.print();'' id='imprimir'><img class='nmwaicons' src='../../netwarelog/design/default/impresora.png' border='0' title='Imprimir'></a><br /><b style='color:red;'>EL ARCHIVO TIENE ERRORES:</b><br />".$mensaje."<br /><a href='index.php?c=CaptPolizas&f=Ver'>Regresar a polizas</a>";
					//Resultados negativos de la validacion
				}

				unlink('importar/polizas2.xls');
			}
			else
			{
				echo "<script type='text/javascript'>alert('Ocurrio un error al cargar los datos');window.location = 'index.php?c=CaptPolizas&f=Ver'</script>";
			}
	}

	function buscaejercicio(){
		echo $ejer = $this->CaptPolizasModel->consultarejer($_REQUEST['idejer']);
	}
	function actualizaCuentas()
	{
		$type_id_account 	= $this->CaptPolizasModel->CuentaTipoCaptura();
		if(!isset($_SESSION['anticipo'])){
			$Accounts 			= $this->getAccounts($type_id_account,$_POST['resultados']);
		}else{
			$Accounts 			= $this->CaptPolizasModel->getAccountGastos($type_id_account);
		}
		while($Cuentas = $Accounts->fetch_assoc())
		{
			echo "<option value='".$Cuentas['account_id']."'>".$Cuentas['description']."(".$Cuentas[$type_id_account].")</option>";
		}

	}

	function getAccounts($type,$res=0)
	{
		return $this->CaptPolizasModel->getAccounts($type,$res);
	}
	//Guarda la informacion de la poliza que se ha actualizado
	function ActualizarPoliza()
	{
		$relacion=0;
		$idPrvBan = explode('/', $_REQUEST['listabanco']);
		$_REQUEST['listabanco'] = $idPrvBan[0];
		if($_REQUEST['relacionextra']){ $relacion= $_REQUEST['relacionextra']; }

			$sitiene = $this->CaptPolizasModel->relacionext($_GET['p']);

		if($_POST['tipoPoliza']!=2){
			$this->CaptPolizasModel->ActualizarPoliza($_GET['p'],$_POST['tipoPoliza'],$_POST['periodos'],$_POST['fecha'],$_POST['numpol'],$_POST['referencia'],$_POST['concepto'],$relacion,0,$_POST['numero'],"",0,"",0,0);
			if($_POST['tipoPoliza'] == 1 && $_REQUEST['formapago'])
				$forma_pago = intval($_REQUEST['formapago']);
			else
				$forma_pago = 0;
			
			if($forma_pago)//Que actualize solo si existe una forma de pago
				$this->CaptPolizasModel->ActualizaMovimiento($forma_pago,$_GET['p']);

		}else if($_POST['tipoPoliza']==2){
			if(isset($_SESSION['anticipo'])){
				$usuarioanticipo = $_REQUEST['usuarios'];
			}else{
				$usuarioanticipo = 0;
			}
			$this->CaptPolizasModel->ActualizarPoliza($_GET['p'],$_POST['tipoPoliza'],$_POST['periodos'],$_POST['fecha'],$_POST['numpol'],$_POST['referencia'],$_POST['concepto'],$relacion,$_REQUEST['beneficiario'],$_REQUEST['numero'],$_REQUEST['rfc'],$_REQUEST['listabanco'],$_REQUEST['numtarje'],$_REQUEST['listabancoorigen'],$usuarioanticipo,$_REQUEST['tipoBeneficiario']);
			if(intval($_REQUEST['formapago']))//Que actualize solo si existe una forma de pago
				$this->CaptPolizasModel->ActualizaMovimiento($_REQUEST['formapago'],$_GET['p']);
		}

		if(isset($_REQUEST['saldado'])){
			$this->CaptPolizasModel->saldado($relacion,$sitiene);
		}else{

			$this->CaptPolizasModel->sinsaldar($relacion,$sitiene);
		}
		if(!isset($_SESSION['anticipo'])){
			echo "<script >window.location = 'index.php?c=CaptPolizas&f=Ver';</script>";
		}else{
			if(isset($_SESSION['menuver'])){
				echo "<script>window.location = 'index.php?c=CaptPolizas&f=Ver'</script>";
			}else{
				echo "<script >window.location = 'index.php?c=CaptPolizas&f=anticipo';</script>";
			}
		}
	}

	//Metodo que devuelve el numero de periodos para esta configuracion
	function NumPeriodos()
	{
		$NumPeriodos = $this->CaptPolizasModel->NumPeriodos($_GET['id']);
		echo $NumPeriodos;
	}

	//Metodo que cambia el estatus de la poliza de activo a inactivo asi como los movimientos dependientes
	function ActActivo()
	{
		$this->CaptPolizasModel->ActActivo($_GET['id']);
	}

	//Metodo que guarda la informacion del movimiento creado
	function InsertMov()
	{//falta borrar el mov para q lo agrege desd 0 en cuando ext
		$this->CaptPolizasModel->BorraGrupoFacturas($_POST['IdPoliza'],$_POST['Movto']);
		if(intval($_POST['Sel_Multiple']))
		{
			$this->CaptPolizasModel->GrupoFacturas($_POST['Factura'],$_POST['IdPoliza'],$_POST['Movto']);
			$_POST['Factura'] = '-';
		}
		$nuevo=$_POST['Nuevo'];
		$delete=$this->CaptPolizasModel->deletemovext($_POST['Movto'],$_POST['IdPoliza']);
		if($_REQUEST['Importeext']>0){
			$nuevo=1;

			$delete=$this->CaptPolizasModel->deletemovexttodo($_POST['Movto'],$_POST['IdPoliza']);
			 $this->CaptPolizasModel->InsertMov($_POST['IdPoliza'],$_POST['Movto'],0,$_POST['Cuenta'],$_POST['TipoMovtoext'],$_POST['Importeext'],$_POST['Referencia'],$_POST['Concepto'],$nuevo,$_POST['Segmento'],$_POST['Sucursal'],$_POST['Factura'],0,$_POST['Sel_Multiple'],$_POST['tipocambio']);
		}
		if(!$_POST['IdMov']) $_POST['IdMov'] = 0;
		$resultado = $this->CaptPolizasModel->InsertMov($_POST['IdPoliza'],$_POST['Movto'],$_POST['IdMov'],$_POST['Cuenta'],$_POST['TipoMovto'],$_POST['Importe'],$_POST['Referencia'],$_POST['Concepto'],$nuevo,$_POST['Segmento'],$_POST['Sucursal'],$_POST['Factura'],0,$_POST['Sel_Multiple'],$_POST['tipocambio']);

		echo $resultado;
	}

	//Metodo que devuelve los movimientos de la poliza
	function NumMovs()
	{
		$NumMovs = $this->CaptPolizasModel->NumMovs($_POST['IdPoliza']);
		$tabla = "<tr style='background-color:#BDBDBD;color:white;font-weight:bold;'><td style='width:50px;'># Movto</td><td>Concepto</td><td>Cuenta</td><td>Cargos</td><td>Abonos</td><td>Segmento de Negocio</td><td>Referencia</td><td class='mye'><b style='visibility:hidden;'>*1*</b></td></tr>";

		while($Mov = $NumMovs->fetch_assoc())
				{
					$codigomoneda = $this->CaptPolizasModel->monedacodigo($Mov['account_id']);
					$MovS = str_replace('"', "", $Mov['Concepto']);
					$MovS = str_replace("'", "", $MovS);
					$accion = "<a href='javascript:modifica(".$Mov['Id'].")' class='bot' id='row-".$Mov['Id']."'>Modificar</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href='javascript:deleteMov(".$Mov['Id'].",\"".$MovS."\")' class='bot'>Eliminar</a>";
					if($_REQUEST['bancos']>0){
						echo "entro bancosss";
						$bancos = $this->CaptPolizasModel->validaBancos();
						 if($bancos == 1){
							 $idCuenta = $this->CaptPolizasModel->cuentaBancos($_POST['IdPoliza']);
							if($idCuenta->num_rows>0){
								while($row = $idCuenta->fetch_assoc()){
									if($Mov['account_id']==$row['account_id']){
										$accion = "";
									}
								}
							}
						 }
					}

					if($Mov['TipoMovto'] == "Cargo")
					{
						$CargosAbonos = "<td title='Cargos'>$".number_format($Mov['Importe'],3)."</td><td title='Abonos'>$0.000</td>";
					}
					if($Mov['TipoMovto'] == "Abono")
					{
						$CargosAbonos = "<td title='Cargos'>$0.000</td><td title='Abonos'>$".number_format($Mov['Importe'],3)."</td>";
					}

					if($Mov['TipoMovto'] == "Cargo M.E.")
					{
						$CargosAbonos = "<td title='Cargos' style='background: #585858;color: #FAFAFA'>".$codigomoneda." ".number_format($Mov['Importe'],3)."</td><td title='Abonos' style='background: #585858;color: #FAFAFA'>".$codigomoneda." 0.00</td>";
					}
					if($Mov['TipoMovto'] == "Abono M.E")
					{
						$CargosAbonos = "<td title='Cargos' style='background: #585858;color: #FAFAFA'>".$codigomoneda." 0.00</td><td title='Abonos' style='background: #585858;color: #FAFAFA'>".$codigomoneda." ".number_format($Mov['Importe'],3,'.','')."</td>";
					}

					if($Mov['TipoMovto']=="Cargo M.E." || $Mov['TipoMovto']=="Abono M.E"){
						$tabla .= "<tr onMouseOut=\"this.className='out'\" id='tbl".$Mov['Id']."'><td style='width:50px;' title='# Movto'>".$Mov['NumMovto']."</td><td title='Concepto'></td><td title='Cuenta'>".mb_strtoupper($Mov['Cuenta'],'UTF-8')." (".$Mov['manual_code'].")</td>$CargosAbonos<td title='segmento'>".mb_strtoupper($Mov['seg'],'UTF-8')."</td><td title='tipocambio'>T.C. ".$Mov['tipocambio']."</td><td></td></tr>";
					}
					else{
						$tabla .= "<tr onMouseOver=\"this.className='over'\" onMouseOut=\"this.className='out'\" id='tbl".$Mov['Id']."' ><td style='width:50px;' title='# Movto'>".$Mov['NumMovto']."</td><td title='Concepto'>".mb_strtoupper($Mov['Concepto'],'UTF-8')."</td><td title='Cuenta'>".mb_strtoupper($Mov['Cuenta'],'UTF-8')." (".$Mov['manual_code'].")</td>$CargosAbonos<td title='segmento'>".mb_strtoupper($Mov['seg'],'UTF-8')."</td><td title='referencia'>".mb_strtoupper($Mov['Referencia'],'UTF-8')."</td><td class='mye'>$accion</td></tr>";
					}
				}
				echo $tabla;

	}

	function MovFacturas()
	{
		$facturasRel = $this->CaptPolizasModel->MovFacturas($_POST['IdPoliza']);
		$tabla .= "<tr><td class='nmcatalogbusquedatit' style='text-align:left;' colspan='2'>Grupo de Facturas Relacionadas</td></tr>";
		$tabla .= "<tr style='background-color:#BDBDBD;'><td style='text-align:left;width:200px;'>Movimiento</td><td style='text-align:left;'>Factura</td></tr>";
		while($fr = $facturasRel->fetch_object())
		{
			$fac = explode('_',$fr->Factura);
			$fac = str_replace('.xml', '', $fac);
			$tabla .= "<tr><td style='text-align:left;'>$fr->NumMovto</td><td style='text-align:left;'>".$fac[2]."<td></td>";
		}
		echo $tabla;
	}

	//Metodo que devuelve las polizas activas del periodo
	function GetPolizas()
	{
		$conf = 0;
		if(isset($_POST['Conf']))
			$conf = 1;

		$Polizas = $this->CaptPolizasModel->getActivePolizas($_POST['Ejercicio'],$_POST['Periodo'],$conf,$_POST['Inicio'],$_POST['Final']);
		while($pol = $Polizas->fetch_assoc())
				{
					$usuario_creacion = $this->CaptPolizasModel->infousuario($pol['usuario_creacion']);
					$usuario_modificacion = $this->CaptPolizasModel->infousuario($pol['usuario_modificacion']);
					//$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Poliza',$pol['id'],$_POST['Ejercicio']);
					//$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Poliza',$pol['id'],$_POST['Ejercicio']);
					$Cuadre = number_format($pol['Abonos'],3,'.','') - number_format($pol['Cargos'],3,'.','');
					//$Cuadre = floatval($pol['Abonos']) - floatval($pol['Cargos']);

					//echo $Cuadre;

					if($Cuadre!=0)
					{
						$cuadra = "color:red;";
					}else{
						$cuadra = "color:black;";
					}
					$fecha = explode('-',$pol['fecha']);
					$relacionExt = $this->CaptPolizasModel->compruebaRelacionExt($pol['id']);
					if($relacionExt->num_rows>0){
						$row = $relacionExt->fetch_assoc();
						$color = ($row['relacionext']==0) ?  "background:#F2F5A9" : "" ;
						$title = ($row['relacionext']==0) ?  "Falta relacionar provision a poliza extranjera" : "" ;
					}else{
						$color = $title = "";
					}
					$men_val = "";
					if($this->CaptPolizasModel->confirm())
					{
						if(!intval($pol['confirmado1']))
						{
							if(!$conf)
							{
								$color = "background-color:#E0F2F7;";
								$men_val = "(No Revisada)";
							}
						}
						if(intval($pol['confirmado1']) == 1 && !intval($pol['confirmado2']))
						{
							if(!$conf)
							{
								$color = "background-color:#F7F2E0;";
								$men_val = "(No Validada)";
							}
						}

						if(intval($pol['confirmado1']) == -1 && intval($pol['confirmado2']) == -1)
						{
							if(!$conf)
							{
								$color = "background-color:#F8E0E0;";
								$men_val = "<b style='color:red;'>(Rechazada)</b>";
							}
						}
					}
					
					$bancos = $this->CaptPolizasModel->validaBancos();
					$edita = "<a href='index.php?c=CaptPolizas&f=ModificarPoliza&id=".$pol['id']."' class='bot' title='Creado: ".$pol['fecha_creacion']." por $usuario_modificacion\nModificado: ".$pol['fecha_modificacion']." por $usuario_modificacion'>Modificar</a>";
					$inactivar = "<a href=\"javascript:deletePoliza(".$pol['id'].",'".$pol['concepto']."')\" class='bot'>Inactivar</a>";
					$imprimir = "<a href='index.php?c=polizasImpresion&f=VerReporte&pol_id=".$pol['id']."' class='bot'>Imprimir</a>";
					if($bancos == 1){
						$idDoc = $this->CaptPolizasModel->idDocumentoBancario($pol['id']);
						if($idDoc!=0){
							$separa = explode("/", $idDoc);
							$link = $this->mandaBancos($separa[1], $separa[0]);
							$edita = "<a onclick=\"irdocumento('$link');\" class='bot' title='Creado: ".$pol['fecha_creacion']." por $usuario_modificacion\nModificado: ".$pol['fecha_modificacion']." por $usuario_modificacion'>Ir Documento</a>";
							$inactivar = "";
							$imprimir = "<a href='index.php?c=polizasImpresion&f=VerReporte&pol_id=".$pol['id']."' class='bot'>Imprimir</a>";
						}else{
						}
					}
					if(!$conf)
						$td = "<td id='edicion'>$edita&nbsp;/$imprimir&nbsp;/&nbsp;$inactivar</td>";
					else
					{
						$check1 = $check2 = "";
						if(intval($pol['confirmado1']) == 1)
							$check1 = "disabled";
						else
							$check2 = "disabled";

						
						$td = "<td>
						<button id='ckbx0-".$pol['id']."' onclick='checkclick(0,".$pol['id'].")' class='btn btn-danger btn-xs'>Rechazar</button>
						<button id='ckbx1-".$pol['id']."' $check1 onclick='checkclick(1,".$pol['id'].")' class='btn btn-warning btn-xs'>Revisar</button>
						<button id='ckbx2-".$pol['id']."' $check2 onclick='checkclick(2,".$pol['id'].")' class='btn btn-success btn-xs'>Validar</button></td>";
						$td .= "<td id='fech-".$pol['id']."'>".$pol['validador']."</td>";
						$td .= "<td id='edicion'>$edita&nbsp;/&nbsp;$inactivar</td>";
						


						
					}
					$tabla .= "
					<tr id='tr".$pol['id']."' title='".$title."' style='".$color."'>
						<td title='Numero de Poliza'><i style='".$cuadra."'>".$pol['numpol']."</i></td>
						<td title='Tipo de Poliza'><i style='".$cuadra."'>".$pol['tipopoliza']." $men_val</i></td>
						<td title='Concepto'><i style='".$cuadra."'>".$pol['concepto']."</i></td>
						<td title='Fecha'>
							<i style='".$cuadra."'>".$fecha[2]."-".$fecha[1]."-".$fecha[0]."</i>
						</td>
						<td title='Cargo'><i style='".$cuadra."'>".number_format($pol['Cargos'],3)."</i></td>
						<td title='Abono'><i style='".$cuadra."'>".number_format($pol['Abonos'],3)."</i></td>
						$td
					</tr>";
				}

				echo $tabla;
	}
	function mandaBancos($tipo,$idDoc){
		switch ($tipo) {
			case 1://cheque
				return "index.php?c=Cheques&f=vercheque&editar=".$idDoc;
			break;
			case 2://ingreso
				return "index.php?c=Ingresos&f=verIngreso&editar=".$idDoc;
			break;
			case 4://deposito
				return "index.php?c=Ingresos&f=verDeposito&editar=".$idDoc;
			break;
			case 5://egreso
				return "index.php?c=Cheques&f=verEgresos&editar=".$idDoc;
			break;
		}
	}
	//Metodo que crea la prepoliza(poliza generada antes de la captura de la informacion, pero permanece inactiva hasta ser capturada)
	function CreateNewPoliza()
	{
		if($_POST['Periodo'] != 13)
		{
			$this->CaptPolizasModel->savePoliza($_POST['Organizacion'],$_POST['Ejercicio'],$_POST['Periodo'],0,1);
			echo 1;
		}
		elseif($this->CaptPolizasModel->CuentaSaldosConfigurado() != -1)
		{
			$this->CaptPolizasModel->savePoliza($_POST['Organizacion'],$_POST['Ejercicio'],$_POST['Periodo'],0,1);
			echo 1;
		}
		else
		{
			echo 0;
		}
		$_SESSION['saldos'] = $_POST['saldos'];
		unset($_SESSION['anticipo']);
		unset($_SESSION['menuver']);
	}

	function cuentaSaldos()
	{
		$cuentas = $this->CaptPolizasModel->CuentaSaldos();
		$select = "<option value='0'>Ninguno</option>";
		while($c = $cuentas->fetch_object())
		{
			$select .= "<option value='$c->account_id'>($c->manual_code) $c->description</option>";
		}
		echo $select;
	}

	//Metodo que devuelve las sumas de abonos y cargos de la poliza
	function SumAbonosCargos()
	{
		$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Poliza',$_POST['IdPoliza'],$_POST['IdEjercicio']);
		$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Poliza',$_POST['IdPoliza'],$_POST['IdEjercicio']);
		echo $Abonos['Cantidad']."-".$Cargos['Cantidad'];
	}

	//Metodo que cambia el estatus del movimiento de activo a inactivo
	function ActMovActivo()
	{
		$this->CaptPolizasModel->ActMovActivo($_GET['id']);
	}
	function buscaext(){
		$cuentaext=$this->CaptPolizasModel->verificaext($_REQUEST['idmov']);
		if($cuentaext){
			$separa=explode("//",$cuentaext);
			$delete=$this->CaptPolizasModel->deletemovext($separa[0], $separa[1]);
		}
	}
	//Funcion que devuelve la fecha de inicio del ejercicio
	function InicioEjercicio()
	{
		$InicioEjercicio = $this->CaptPolizasModel->InicioEjercicio();
		echo $InicioEjercicio['InicioEjercicio'];
	}


	//Metodo que devuelve el ultimo movimiento
	function UltimoMov()
	{
		$UltimoMov = $this->CaptPolizasModel->UltimoMov($_POST['IdPoliza']);
		echo $UltimoMov;
	}

	//Datos del movimiento
	function DatosMov()
	{
		$DatosMov = $this->CaptPolizasModel->DatosMov($_POST['Id']);
		//$valida=$this->consultaextedicionpoli($DatosMov['Cuenta']);
		$GrupoFacturas = '';
		if(intval($DatosMov['MultipleFacturas']))
		{
			$Multiples = $this->CaptPolizasModel->MultiplesFacturas($DatosMov['IdPoliza'],$DatosMov['NumMovto']);
			$cont=0;
			while($Mult = $Multiples->fetch_object())
			{
				if($cont>0) $GrupoFacturas .= "//@//";
				$GrupoFacturas .= $Mult->Factura;
				$cont++;
			}

		}
		echo $DatosMov['NumMovto']."*/separacion/*".$DatosMov['Cuenta']."*/separacion/*".$DatosMov['TipoMovto']."*/separacion/*".$DatosMov['Referencia']."*/separacion/*".$DatosMov['Concepto']."*/separacion/*".$DatosMov['Importe']."*/separacion/*".$DatosMov['IdSucursal']."*/separacion/*".$DatosMov['IdSegmento']."*/separacion/*".$DatosMov['Factura']."*/separacion/*".$DatosMov['FormaPago']."*/separacion/*".$DatosMov['MultipleFacturas']."*/separacion/*".$GrupoFacturas;
	}

	//Genera la lista de polizas eliminadas y los pinta en la vista
	function ListaPolizasEliminadas()
	{
		$ListaPolizasEliminadas = $this->CaptPolizasModel->ListaPolizasEliminadas(0);
		$PDV = $this->CaptPolizasModel->conectado();
		require('views/captpolizas/listapolizaseliminadas.php');
	}

	//Metodo que restaura la poliza a su condicion anterior, la reactiva
	function RestaurarPoliza()
	{
		$this->CaptPolizasModel->RestaurarPoliza($_POST['IdPoliza'],$_POST['PDV']);
	}

	//Elimina definitivamente la poliza y ya no se puede recuperar
	function EliminarPoliza()
	{
		$this->CaptPolizasModel->EliminarPoliza($_POST['IdPoliza']);
		$n = 3;

	    if(isset($_COOKIE['inst_lig']))
	    	$n = 11;
		$dir = $this->path()."xmls/facturas/".$_POST['IdPoliza'];
		foreach(glob($dir . "/*") as $archivos)
		{

			$separa = explode("/", $archivos);
			copy($archivos, $this->path()."xmls/facturas/temporales/".$separa[$n]);
			/*if(mb_stristr($separa[3], "Cobro")){
				copy($archivos, "xmls/facturas/temporales/".$separa[3]);

			}if(mb_stristr($separa[3], "Pago")){
				copy($archivos, "xmls/facturas/temporales/".$separa[3]);
			}*/
			unlink($archivos);
		}
		unlink($dir.'/.DS_Store');
 	   	rmdir($dir."/");
	}

	//Genera la lista de los movimientos de la poliza eliminada que se esta viendo
	function MovimientosPolizasEliminadas()
	{
		$NumMovs = $this->CaptPolizasModel->MovimientosPolizasEliminadas($_POST['IdPoliza']);
		echo "<table><tr style='background-color:#BDBDBD;color:white;font-weight:bold;'><td># Movimiento</td><td>Concepto</td><td>Cuenta</td><td>Tipo Movto</td><td>Importe</td></tr>";
		while($Mov = $NumMovs->fetch_assoc())
				{
					echo "<tr onMouseOver=\"this.className='over'\" onMouseOut=\"this.className='out'\" id='tbl".$Mov['Id']."'><td>".$Mov['NumMovto']."</td><td>".$Mov['Concepto']."</td><td>".$Mov['Cuenta']."</td><td>".$Mov['TipoMovto']."</td><td>$".number_format($Mov['Importe'],2)."</td></tr>";
				}
				echo "</table>";
	}

	//Genera la lista de polizas creadas desde el punto de venta
	function ListaPolizasPDV()
	{
		$ListaPolizasPDV =  $this->CaptPolizasModel->ListaPolizasEliminadas(1);
		require('views/captpolizas/listapolizaspdv.php');
	}

	//Genera la lista de los movimientos de la poliza creada desde el punto de venta
	function MovimientosPolizasPDV()
	{
		$NumMovs = $this->CaptPolizasModel->MovimientosPolizasEliminadas($_POST['IdPoliza']);
		echo "<table><tr style='background-color:#BDBDBD;color:white;font-weight:bold;'><td>Persona</td><td>Cuenta</td><td>Tipo Movto</td><td>Importe</td><td>Confirmar</td></tr>";
		while($Mov = $NumMovs->fetch_assoc())
				{
					$checked='';//Si el movimiento esta activo cambia el checked del checkbox
					if($Mov['Activo']==1)
					{
						$checked='checked';
					}
					$GetNamePerson = $this->CaptPolizasModel->GetNamePerson($Mov['Persona']);
					echo "<tr onMouseOver=\"this.className='over'\" onMouseOut=\"this.className='out'\" id='tbl".$Mov['Id']."'><td onClick=\"checking('cb_".$Mov['Id']."')\">".$GetNamePerson."</td><td onClick=\"checking('cb_".$Mov['Id']."')\">".$Mov['Cuenta']."</td><td onClick=\"checking('cb_".$Mov['Id']."')\">".$Mov['TipoMovto']."</td><td onClick=\"checking('cb_".$Mov['Id']."')\">$".number_format($Mov['Importe'],2)."</td><td><input type='checkbox' id='cb_".$Mov['Id']."' $checked onClick='actualiza(".$Mov['Id'].")'></td></tr>";
				}
				echo "</table>";
	}

	function ActivaMovPDV()
	{
		$this->CaptPolizasModel->ActivaMovPDV($_POST['Id'],$_POST['Activo']);
	}

	//Metodo que guarda la informacion de la poliza y movimientos creados desde el punto de venta
	function InsertPolMovPDV()
	{
		session_start();
		$Fecha = explode(' ',$_POST['Fecha']);

		//Pregunta si el sistema contable esta activo si es asi cada poliza debe ser autorizada, si no se activan automaticamente
		$activo = $this->CaptPolizasModel->IsActive();
		$Activar = 0;	//La poliza y los movimientos son inactivos

		if(empty($activo))
		{
			$Activar = 1;
		}


		//Abre sesion y obtiene los movimientos del corte de caja
		$Movimientos 	= $this->CaptPolizasModel->getMovementsPDV($_SESSION['cont_query']);
		$CuentasConfig 	= $this->CaptPolizasModel->getAccountsConfig();//Obtiene a que cuentas se van a almacenar

		//******************* INICIA ASIGNACION DE CUENTAS ********************************************************************//

		$CuentaClientes = 	($CuentasConfig['CuentaClientes'] != -1) 	? $CuentasConfig['CuentaClientes'] : 6;//Cuenta de clientes por default
		$CuentaVentas 	= 	($CuentasConfig['CuentaVentas'] != -1) 		? $CuentasConfig['CuentaVentas'] : 78;//Cuenta de ventas por default
		$CuentaIVA 		= 	($CuentasConfig['CuentaIVA'] != -1) 		? $CuentasConfig['CuentaIVA'] : 7;//Cuenta de IVA por default
		$CuentaIEPS 	= 	($CuentasConfig['CuentaIEPS'] != -1) 		? $CuentasConfig['CuentaIEPS'] : 0;//Cuenta de IEPS por default
		$CuentaCaja 	= 	($CuentasConfig['CuentaCaja'] != -1) 		? $CuentasConfig['CuentaCaja'] : 3;//Cuenta de Caja por default
		$CuentaTR 		= 	($CuentasConfig['CuentaTR'] != -1) 			? $CuentasConfig['CuentaTR'] : 80;//Cuenta de Tarjetas de regalo por default
		$CuentaBancos 	= 	($CuentasConfig['CuentaBancos'] != -1) 		? $CuentasConfig['CuentaBancos'] : 65;//Cuenta de Bancos por default

		//******************* TERMINA ASIGNACION DE CUENTAS *******************************************************************//

		//Crea la poliza y obtiene el id
		$last_id = $this->CaptPolizasModel->savePolizaPDV($Fecha[0],$_SESSION["accelog_idempleado"],$Activar,3);//Poliza de diario

		$Cuenta=1;//lleva la cuenta de movimientos

		while($Movs = $Movimientos->fetch_assoc())//Movimientos poliza de diario
				{
					$Importe = $Movs['montoventa'] + $Movs['montoimpuestos'];

					$this->CaptPolizasModel->InsertMovPDV($last_id,$Cuenta,$Movs['idVenta'],number_format($Importe,2,'.',''),$Movs['idCliente'],$Activar,$Movs['referencia'],'Cargo',$CuentaClientes);//Clientes
					$Cuenta++;
					$this->CaptPolizasModel->InsertMovPDV($last_id,$Cuenta,$Movs['idVenta'],number_format($Movs['montoventa'],2,'.',''),$Movs['idCliente'],$Activar,$Movs['referencia'],'Abono',$CuentaVentas);//Ventas
					$Cuenta++;
					$this->CaptPolizasModel->InsertMovPDV($last_id,$Cuenta,$Movs['idVenta'],number_format($Movs['montoimpuestos'],2,'.',''),$Movs['idCliente'],$Activar,$Movs['referencia'],'Abono',$CuentaIVA);//IVA
					$Cuenta++;
				}

		$Movimientos = $this->CaptPolizasModel->getMovementsPDV($_SESSION['cont_query']);
		//Crea la poliza y obtiene el id
		$last_id = $this->CaptPolizasModel->savePolizaPDV($Fecha[0],$_SESSION["accelog_idempleado"],$Activar,1);//Poliza de ingresos
		$HuboMovimiento = 0;

		$Cuenta=1;//lleva la cuenta de movimientos

		while($Movs = $Movimientos->fetch_assoc())//Movimientos poliza de ingresos
				{
					if($Movs['idFormapago'] != 6)//Si la venta NO es a credito se guarda
					{
						switch($Movs['idFormapago'])
						{
							case 1 : $BC = $CuentaCaja;//Caja
							break;

							case 3 : $BC = $CuentaTR;//Tarjetas de Regalo
							break;

							default : $BC = $CuentaBancos;//Todas las demas se van a Bancos: Cheque, Tarj Credito, Tarj Debito, Transferencia y Spei
						}
						$Importe = $Movs['montoventa'] + $Movs['montoimpuestos'];
						$this->CaptPolizasModel->InsertMovPDV($last_id,$Cuenta,$Movs['idVenta'],number_format($Importe,2,'.',''),$Movs['idCliente'],$Activar,$Movs['referencia'],'Cargo',$BC);//Banco o Caja
						$Cuenta++;
						$this->CaptPolizasModel->InsertMovPDV($last_id,$Cuenta,$Movs['idVenta'],number_format($Importe,2,'.',''),$Movs['idCliente'],$Activar,$Movs['referencia'],'Abono',$CuentaClientes);//Clientes
						$Cuenta++;
						$HuboMovimiento++;
					}
				}
			if(!$HuboMovimiento)
			{
				$this->CaptPolizasModel->updatePolizaPDV($last_id,0);//Eliminar Poliza
			}

		//*********** VERSION ANTERIOR *********************

		/*while($Movs = $Movimientos->fetch_assoc())//Movimientos poliza de diario
				{
					if($Movs['idFormapago'] != 3 AND $Movs['idFormapago'] != 6)//Si no es tarjeta de regalo ni credito
					{
						if($Movs['idFormapago'] == 1)
						{
							$Monto = $Movs['monto'] - $Movs['cambio'];
						}
						else
						{
							$Monto = $Movs['monto'];
						}
						$Porcentaje = $Monto / $Movs['montoventa'];
						$MontoImpuestos = $Movs['montoimpuestos'] * $Porcentaje;

						//Guarda los movimientos del corte en movimientos contables
						$Monto = $Monto - $MontoImpuestos;
						$this->CaptPolizasModel->InsertMovPDV($last_id,$Cuenta,$Movs['idVenta'],number_format($Monto,2,'.',''),$Movs['idCliente'],$Activar,$Movs['idFormapago'],$Movs['referencia'],0);
						$Cuenta++;

						if($MontoImpuestos)
						{
							$this->CaptPolizasModel->InsertMovPDV($last_id,$Cuenta,$Movs['idVenta'],number_format($MontoImpuestos,2,'.',''),$Movs['idCliente'],$Activar,$Movs['idFormapago'],$Movs['referencia'],1);
							$Cuenta++;
						}
					}

				}*/
			//*********** VERSION ANTERIOR *********************
	}

	public function getCXCValues()
	{

		if (!isset($_SESSION))
		session_start();
		$montoVenta   = 0;
		$ivaVenta     = 0;
		$idCliente    = 0;
		$porcentVenta = 0;
		$parcialIva   = 0;
		$idPoliza     = 0;
		$fechaAbono   = 0;
		$abono        = 0;
		$textPago     = 0;
		$idPago       = 0;
		$referencia   = 0;
		$idCxC        = 0;
		$paymentsArr  = array();
		// $dataArray = json_decode($_POST['data']);

		// Generando la poliza
		$idPoliza = $this->CaptPolizasModel->savePolizaCxC( $_POST['data'][0][0], $_SESSION['accelog_idempleado'], 0, 3 );// Generar una poliza de diario

		for ($i=0; $i < count($_POST['data']) ; $i++) // por cada pago
		{
			$fechaAbono = $_POST['data'][$i][0];
			$abono      = $_POST['data'][$i][1];
			$textPago   = $_POST['data'][$i][2];
			$idPago     = $_POST['data'][$i][3];
			$referencia = $_POST['data'][$i][4];
			$idCxC      = $_POST['data'][$i][5];

			if ($i == 0)
			{
				$result = $this->CaptPolizasModel->getVentaValues($idCxC);
				$montoVenta    = $result['monto_venta'];
				$ivaVenta      = $result['monto_iva'];
				$idCliente     = $result['idCliente'];
				$nombreCliente = $result['nombre'];
				$idVenta       = $result['idVenta'];
			}

			$this->CaptPolizasModel->insertMovCxC( $idPoliza, $i, $idVenta, $abono, $idCliente, 0, $referencia, "Cargo", "Clientes" );// Cargo a cliente

			if( $idPago == 1 )
			{
				$this->CaptPolizasModel->insertMovCxC( $idPoliza, $i, $idVenta, $abono, $idCliente, 0, $referencia, "Abono", "Caja" );// Abono a Caja
			}
			elseif ( $idPago == 3 )
			{
				$this->CaptPolizasModel->insertMovCxC( $idPoliza, $i, $idCxC, $abono, $idCliente, 0, $referencia, "Abono", "Tarjetas" );// Abono a Tarjetas de Regalo
			}
			else
			{
				$this->CaptPolizasModel->insertMovCxC( $idPoliza, $i, $idCxC, $abono, $idCliente, 0, $referencia, "Abono", "Banco" );// Abono a Bancos
			}

		}
		echo json_encode($paymentsArr);
	}

	public function reglaDeTres($total,$parcial)
	{
		$final = ( $parcial * 100 )/ $total;
		$final = $final * 0.01;
		return $final;
	}

	function getProviderList()
	{
		$ProvidersList = $this->CaptPolizasModel->getProviderList($_POST['IdPoliza']);
		$content = "<table><tr style='background-color:#BDBDBD;color:white;font-weight:bold;'><td width='200'>Razon Social</td><td>Importe Base</td><td>IVA</td><td>Subtotal</td><td>IVA Retenido</td><td>ISR Retenido</td><td>Total Erogacion</td><td></td></tr>";
		while($List = $ProvidersList->fetch_assoc())
				{
					$ImporteIVA = $List['importeBase'] * ($List['tasa'] / 100);
					$Subtotal = $List['importeBase'] + $ImporteIVA;
					$Erogaciones = $List['importeBase'] + $ImporteIVA + $List['otrasErogaciones'] - $List['ivaRetenido'] - $List['isrRetenido'];
					$content .= "<tr onMouseOver=\"this.className='over'\" onMouseOut=\"this.className='out'\" ondblclick='abreProveedores(".$List['idPrv'].",".$List['id'].")' id='idR-".$List['id']."'><td title='Razon Social'>".$List['razon_social']."</td><td title='ImporteBase' style='text-align:right;'>$".number_format($List['importeBase'],2)."</td><td style='text-align:right;' title='IVA'>$".number_format($ImporteIVA,2)."</td><td style='text-align:right;' title='Subtotal'>$".number_format($Subtotal,2)."</td><td style='text-align:right;' title='IVA Retenido'>$".number_format($List['ivaRetenido'],2)."</td><td title='ISR Retenido' style='text-align:right;'>$".number_format($List['isrRetenido'],2)."</td><td title='Erogaciones' style='text-align:right;'>$".number_format($Erogaciones,2)."</td><td style='text-align:right;'><a style='color:red; text-decoration:none; font-weight:bold;' href='javascript:eliminaProv(".$List['id'].")'>X</a></td></tr>";
					$importe += $Erogaciones;
					$tieneRegistros = $List['periodoAcreditamiento'];
					$tieneRegistrosEj = $List['ejercicioAcreditamiento'];
				}
				$content .= "<tr><td colspan='8' style='font-weight:bold;text-align:right;border-top:1px solid #BDBDBD;'>Total Erogaciones: $".number_format($importe,2)."<input type='hidden' id='suma-importes' value='".number_format($importe,2,'.','')."'><input type='hidden' id='suma-abonos-bancos' value='".$this->CaptPolizasModel->sumaAbonosBancos($_POST['IdPoliza'])."'></td></tr>";
				$content .= "<tr><td colspan='1'><input type='hidden' name='tieneEjercicioAcreditamiento' id='tieneEjercicioAcreditamiento' value='$tieneRegistrosEj'>Ejercicio de Acreditamiento:</td><td colspan='1'><select name='ejercicio_acreditamiento' id='ejercicio_acreditamiento' onchange='actualizaPeriodoAcreditamiento(".$_POST['IdPoliza'].")'><option value='0'>No tiene</option>";

				$EjerciciosLista = $this->CaptPolizasModel->exercisesList();
				while($ListEjercicios = $EjerciciosLista->fetch_assoc())
				{
					$content .= "<option value='".$ListEjercicios['Id']."'>".$ListEjercicios['NombreEjercicio']."</option>";
				}
				$content .= "</select></td></tr>";
				$content .= "<tr><td colspan='1'><input type='hidden' name='tienePeriodoAcreditamiento' id='tienePeriodoAcreditamiento' value='$tieneRegistros'>Periodo de Acreditamiento:</td><td colspan='1'><select name='periodo_acreditamiento' id='periodo_acreditamiento' onchange='actualizaPeriodoAcreditamiento(".$_POST['IdPoliza'].")'><option value='0'>No tiene</option><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td></tr>";
				echo $content;

	}

	function getProviderInfo()
	{
		$ProvidersInfo = $this->CaptPolizasModel->getProviderInfo($_POST['IdPrv']);
		echo $ProvidersInfo['ivaretenido']."/".$ProvidersInfo['isretenido']."/".$ProvidersInfo['idTasaPrvasumir'];
		//echo $this->truncate($ProvidersInfo['ivaretenido'],2)."/".$ProvidersInfo['isretenido']."/".$ProvidersInfo['idTasaPrvasumir'];
	}
	function truncate($val, $f="0")
	{
    	if(($p = strpos($val, '.')) !== false)
    	{
        	$val = floatval(substr($val, 0, $p + 1 + $f));
    	}
    	return $val;
	}
	function getProviderTax()
	{
		if($_POST['Idr'])// si tiene un valor entonces no es nuevo y actualiza
		{
			$ProviderTaxDefault = $this->CaptPolizasModel->getProviderTaxDefaultSaved($_POST['Idr']);
		}
		else// si devuelve 0 (cero) entonces no tiene un id por lo tanto es nuevo
		{
			$ProviderTaxDefault = $this->CaptPolizasModel->getProviderTaxDefault($_POST['IdPrv']);
		}

		$ProviderTax = $this->CaptPolizasModel->getProviderTax($_POST['IdPrv']);
		while($Tax = $ProviderTax->fetch_assoc())
		{
			if($Tax['tasa'] == $ProviderTaxDefault['Tasa'])
			{
				$checked = 'checked=""';
			}
			else
			{
				$checked = '';
			}
			echo "<input type='radio' name='iva' class='iva' value='".$Tax['valor']."' idbd='".$Tax['id']."' $checked onclick='modificaImpuestos()'>".$Tax['tasa']."<br />";
		}
	}

	function GuardarProveedores()
	{

  		if($_POST['Idr'])
  		{
   			$tipoOperacion = 'ActualizaProveedores';
   			$message = "La informacion se actualizo correctamente";
  		}
  		else
  		{
    		$tipoOperacion = 'InsertaProveedores';
    		$message = "El registro se inserto correctamente.";
  		}
		$this->CaptPolizasModel->$tipoOperacion($_POST['Idr'],$_POST['Poliza'],$_POST['IdPrv'],$_POST['Referencia'],$_POST['Tasa'],$_POST['Importe'],$_POST['ImporteBase'],$_POST['OtrasErogaciones'],$_POST['IVARetenido'],$_POST['IsrRetenido'],$_POST['IvaPagadoNoAcreditable'],$_POST['Aplica'],$_POST['Ejercicio'],$_POST['ietu'],$_POST['acreditaietu'],$_REQUEST['periodo_acreditamiento']);
		echo $message;
	}

	function getProviderAllInfo()
	{
		$Apply = $this->CaptPolizasModel->getProviderAllInfo($_POST['Idr']);
		$Apply = $Apply->fetch_object();
		echo $Apply->referencia."/".$Apply->importe."/".$Apply->importeBase."/".$Apply->otrasErogaciones."/".$Apply->ivaRetenido."/".$Apply->isrRetenido."/".$Apply->ivaPagadoNoAcreditable."/".$Apply->aplica;
	}
	function eliminaProv()
	{
		$this->CaptPolizasModel->eliminaProv($_POST['Id']);
	}

	function actualizaPeriodoAcreditamiento()
	{
		$this->CaptPolizasModel->actualizaPeriodoAcreditamiento($_POST['IdPoliza'],$_POST['NuevoPeriodo'],$_POST['NuevoEjercicio']);
	}

	function getCausacionData()
	{
		$datosBD = $this->CaptPolizasModel->getCausacionData($_POST['IdPoliza']);

		if(!$datosBD)
			{
				echo 0;
			}
			else{
					$datos[] = array();
					for($i=1;$i<=16;$i++)
					{

						$data = explode('-',$datosBD[$i]);

						array_push($datos,array(	'ImporteTotal' => utf8_encode(@$data[0]),
													'ImporteBase' => utf8_encode(@$data[1]),
													'IVA' => utf8_encode(@$data[2]),
													'IvaPagadoNoAcreditable' => utf8_encode(@$data[3])
												));
					}
					echo json_encode($datos);
				}

	}

	function guardaCausacion()//ganzo
	{
		echo $this->CaptPolizasModel->guardaCausacion($_POST);
	}

	function getCargosBancos()
	{
		if($this->CaptPolizasModel->getBancos() != -1)
		{
			echo $this->CaptPolizasModel->getCargosBancos($_POST['IdPoliza'],$_GET['Tipo']);
		}
		else
		{
			echo "No";
		}
	}

	function getAbonosBancos()
	{
		echo $this->CaptPolizasModel->getAbonosBancos($_POST['IdPoliza']);
	}

	function listaFacturas()
	{
		global $xp;

		$ruta = $this->path()."xmls/facturas/".$_POST['IdPoliza'];
		echo "<option value='-' uuid='-' datos='-'>Ninguna</option>";
		if($directorio = opendir($ruta))
		{
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store')
				{

					$texto 	= file_get_contents($ruta."/".$archivo);
					//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
					//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
					$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
                    $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
					$xml 	= new DOMDocument();
					$xml->loadXML($texto);
					$xp = new DOMXpath($xml);
					//COMIENZA VERSION---------------------------------------
					if($this->getpath("//@version"))
			            $data['version'] = $this->getpath("//@version");
			        else
			        	$data['version'] = $this->getpath("//@Version");

					$version = $data['version'];
					//TERMINA VERSION---------------------------------------
					if($version[0] == '3.3')
					{
						$UUID = $this->getpath("//@UUID");
						//$UUID = $UUID[1];
						if(is_array($UUID)){
							$UUID = $UUID[1];
						}
						$folio = $this->getpath("//@Folio");
						$nombre = $this->getpath("//@Nombre");
					}
					else
					{
						$UUID = $this->getpath("//@UUID");
						$folio = $this->getpath("//@folio");
						$nombre = $this->getpath("//@nombre");
					}
					

	   				echo "<option value='$archivo' datos='".$folio."_".$nombre[0]."_".$UUID.".xml'>".$folio."_".$nombre[0]."</option>";
				}
			}
		}
	}
	function facturas_dialog()
	{
		$cont=0;global $xp;
		
		$ruta = $this->path()."xmls/facturas/".$_POST['IdPoliza'];
		if($directorio = opendir($ruta))
		{
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file' AND $archivo != 'ziptempo' AND $archivo != '.file.rtf' AND $archivo != 'pdfnominas' AND $archivo != 'tempo.zip')
				{
					$archivo_str = explode('_',$archivo);
					$cont++;
					$texto 	= file_get_contents($ruta."/".$archivo);
					//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
					//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
					$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
                    $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
					$xml 	= new DOMDocument();
					$xml->loadXML($texto);

					$xp = new DOMXpath($xml);

					//COMIENZA VERSION---------------------------------------
					if($this->getpath("//@version"))
	                	$data['version'] = $this->getpath("//@version");
	                else
	                	$data['version'] = $this->getpath("//@Version");

					$version = $data['version'];
					//TERMINA VERSION---------------------------------------
					if($version[0] == '3.3')
					{
						$data['rfc']           = $this->getpath("//@Rfc");
						$data['total']         = $this->getpath("//@Total");
						$data['nombre']        = $this->getpath("//@Nombre");
						$data['unidad']        = $this->getpath("//@Unidad");
						$data['importe']       = $this->getpath("//@Importe");
						$data['cantidad']      = $this->getpath("//@Cantidad");
						$data['subtotal']      = $this->getpath("//@SubTotal");
						$data['descuento']     = $this->getpath("//@Descuento");
						$data['metodoDePago']  = $this->getpath("//@MetodoPago");
						$data['descripcion2']  = $this->getpath("//@Descripcion");
						$data['nomina']        = $this->getpath("//@NumEmpleado");
						$data['descripcion']   = $this->getpath("//@Descripcion");
						$data['valorUnitario'] = $this->getpath("//@ValorUnitario");
						$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
						$data['impuesto']      = $this->CaptPolizasModel->nombreImpuesto($this->getpath("//@Impuesto"));
					}
					else
					{
						$data['rfc']           = $this->getpath("//@rfc");
						$data['total']         = $this->getpath("//@total");
						$data['nombre']        = $this->getpath("//@nombre");
						$data['unidad']        = $this->getpath("//@unidad");
						$data['importe']       = $this->getpath("//@importe");
						$data['impuesto']      = $this->getpath("//@impuesto");
						$data['subtotal']      = $this->getpath("//@subTotal");
						$data['cantidad']      = $this->getpath("//@cantidad");
						$data['descuento']     = $this->getpath("//@descuento");
						$data['descripcion']   = $this->getpath("//@descripcion");
						$data['descripcion2']  = $this->getpath("//@descripcion");
						$data['nomina']        = $this->getpath("//@NumEmpleado");
						$data['metodoDePago']  = $this->getpath("//@metodoDePago");
						$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
						$data['valorUnitario'] = $this->getpath("//@valorUnitario");
					}

					if(is_array($data['descripcion']))
					{
						$data['descripcion'] = $data['descripcion'][0];
					}

					$rfc = $this->CaptPolizasModel->rfcOrganizacion();

					if($data['rfc'][0] == $rfc['RFC'])
					{
						$tipoDeComprobante = "Ingreso";
					}
					elseif($data['rfc'][1] == $rfc['RFC'])
					{
						$tipoDeComprobante = "Egreso";
					}
					else
					{
						$tipoDeComprobante = "Otro";
					}
					if($data['nomina']){ $tipoDeComprobante = "Nomina";}
					$data['tipocomprobante']= $tipoDeComprobante;
	   				//echo "<tr style='text-align:center;height:50px;'><td style='font-size:8px;'>$cont</td><td><img src='xmls/imgs/xml.jpg' width=30></td><td><b>". $archivo_str[0] . "_" . $archivo_str[1] ."</b></td><td width=100> <a href='$ruta/$archivo' target='_blank'>Ver</a> </td><td width=100> <a href='xmls/funciones/descargaXML.php?ruta=facturas/".$_POST['IdPoliza']."&nombre=".$archivo."' target='_blank'>Descargar</a></td><td>Creado  o modificado el: ".date ("d/m/Y",filemtime($ruta."/".$archivo))."</td><td><a href='javascript:eliminar(\"".$ruta."/".$archivo."\")'><img src='images/eliminado.png' title='Eliminar'></a></td><td><b style='color:green;'>Validado</b></td></tr>";
	   				$listaFacturas .= "
						<tr style='text-align:center;height:50px;'>
		   				<td style='font-size:8px;'>$cont</td>
		   				<td style='font-size:11px;'>
								<img src='xmls/imgs/xml.jpg' width=30>
							</td>
		   				<td>". $archivo_str[0] . "_" . $archivo_str[1] ."</td>
		   				<td width='100'>
								<a href='views/captpolizas/visor.php?data=".urlencode(serialize($data))."' target='_blank'>Ver</a>
							</td>
		   				<td style='font-size:11px;'>".$data['descripcion']."</td>
		   				<td width='60'><center>".$tipoDeComprobante."</center></td>
		   				<td width=200 style='color:orange;'>
								<b>".number_format($data['total'],2,'.',',')."</b>
							</td>
		   				<td width=200>".$data['metodoDePago']."</td>
		   				<td style='font-size:11px'>".$data['FechaTimbrado']."</td>
		   				<td>
							<a href='javascript:eliminar(\"".$ruta."/".$archivo."\")'><img src='images/eliminado.png' title='Eliminar'></a>
							</td>
	   				</tr>";
						$sum = $sum + floatval($data['total']);
				}
				$total = "
					<tr style='border-top: 1px solid #ddd; background-color: #e9e9e9;'>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align='right'>
							<h4>Total:</h4>
						</td>
						<td align='center'>
							<h4 style='margin: 0; color:orange;'>$".number_format($sum,2,'.',',')."</h4>
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>";
			}
			echo $listaFacturas . $total;
		}
		if($cont==0)
		{
			echo "<tr><td>No hay archivos</td></tr>";
		}

	}
	function subeFacturaZip()
	{
			$nn=0;
			$rfcOrganizacion = $this->CaptPolizasModel->rfcOrganizacion();
			global $xp;
			$facturasNoValidas = $facturasValidas = '';
			$numeroInvalidos = $numeroValidos = $no_hay_problema = 0;
			$nombre = "";
			$maximo = count($_FILES['factura']['name']);
			$maximo = (intval($maximo)-1);

			$ruta = $this->path()."xmls/facturas/temporales/";
			$ruta2 = $ruta;
			if(isset($_POST['plz']) && intval($_POST['plz']))
			{
				$ruta2 = $this->path()."xmls/facturas/" . $_POST['plz'];//Ruta donde se guardara
				if(!file_exists($ruta2))//Si no existe la carpeta de ese periodo la crea
				{
					mkdir ($ruta2,0777);
				}
			}

			$extension = end(explode('.', $_FILES['factura']['name'][0]));
			if($extension == "zip")
			{
				$zipoxml = "tempo.zip";
			}
			if($extension == "xml")
			{
				$zipoxml = "tempo.xml";
			}

			if(move_uploaded_file($_FILES["factura"]["tmp_name"][0], $ruta.$zipoxml))
			{
				$zip = new ZipArchive;
				if($extension == "xml")
				{
					mkdir($ruta."tempo/", 0777);
					copy($ruta.$zipoxml,$ruta."tempo/".$zipoxml);
					unlink($ruta.$zipoxml);
					$zip->open($ruta.'tempo.zip', ZipArchive::CREATE);
				 	$zip->addFile($ruta."tempo/".$zipoxml,"tempo/".$zipoxml);
					$zip->close();
					unlink($ruta."tempo/".$zipoxml);
					rmdir($ruta."tempo/");
				}
				mkdir($ruta."ziptempo/", 0777);

				if ($zip->open($ruta."tempo.zip") === TRUE)
				{
				    $zip->extractTo($ruta."ziptempo/");
				    $zip->close();
				    //unlink($ruta."tempo.zip");

				    if($extension == "xml")
					{
						$foldername = "tempo";
					}

					if($extension == "zip")
					{
						$foldername = $_FILES['factura']['name'][0];
				    	$foldername = str_replace('.zip', '', $foldername);
					}

				    if($directorio = opendir($ruta."ziptempo/$foldername/"))
					{
						while ($archivo = readdir($directorio))
						{
							if(is_dir($ruta."ziptempo/$foldername/$archivo"))
							{
								rmdir($ruta."ziptempo/$foldername/$archivo/");
							}
							elseif($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file')
							{
								//Comienza obtener UUID---------------------------
								$file 	= $ruta."ziptempo/$foldername/".$archivo;
								$texto 	= file_get_contents($file);
								//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
								//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
								$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            					$texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
								$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '', $texto);
                        		$texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '', $texto);
								$xml 	= new DOMDocument();
								$xml->loadXML($texto);

								$xp = new DOMXpath($xml);
								$data['uuid'] 	= $this->getpath("//@UUID");
								//COMIENZA VERSION---------------------------------------

								if($this->getpath("//cfdi:Comprobante/@Version"))
		                            $data['version'] = $this->getpath("//cfdi:Comprobante/@Version");
		                        else
		                            $data['version'] = $this->getpath("//cfdi:Comprobante/@version");

		                        $version = $data['version'];
								//TERMINA VERSION---------------------------------------
								$data['NumEmpleado'] = '';
								if($version[0] == '3.3')
								{
									//$data['uuid'] 	= $data['uuid'][1];
									if(is_array($data['uuid'])){
										$data['uuid'] 	= $data['uuid'][1];
									}
									$data['folio'] 	= $this->getpath("//@Folio");
									if($this->getpath("//@TipoDeComprobante") == 'P')
									{
										$data['folio'] = $data['folio'][0];
										$data['pago']['IdDocumento'] = $this->getpath("//@IdDocumento");
										$data['pago']['ImpPagado'] = $this->getpath("//@ImpPagado");
										$data['pago']['ImpSaldoAnt'] = $this->getpath("//@ImpSaldoAnt");
										$data['pago']['ImpSaldoInsoluto'] = $this->getpath("//@ImpSaldoInsoluto");
										$data['pago']['MonedaDR'] = $this->getpath("//@MonedaDR");
										$data['pago']['MetodoDePagoDR'] = $this->getpath("//@MetodoDePagoDR");
										$data['pago']['IdDocumento'] = $this->getpath("//@IdDocumento");

										if($this->getpath("//@NumParcialidad"))
											$data['pago']['NumParcialidad'] = $this->getpath("//@NumParcialidad");
									}

									$data['emisor'] = $this->getpath("//@Nombre");
									$data['total'] = $this->getpath("//@Total");
									$data['rfc'] = $this->getpath("//@Rfc");

									$data['calle'] = $this->getpath("//@Calle");
									$data['noExt'] = $this->getpath("//@NoExterior");
									$data['colonia'] = $this->getpath("//@Colonia");
									$data['municipio'] = $this->getpath("//@Municipio");
									$data['estado'] = $this->getpath("//@Estado");
									$data['cp'] = $this->getpath("//@CodigoPostal");

									$data['FechaPago'] = $this->getpath("//@FechaPago");
									$data['NumEmpleado'] = $this->getpath("//@NumEmpleado");
									$subtotal = $this->getpath("//@SubTotal");
									if(!$totalImpuestosTrasladados = $this->getpath("//@TotalImpuestosTrasladados"))
							        	$totalImpuestosTrasladados = 0;
							        if(!$totalRetenciones = $this->getpath("//@TotalImpuestosRetenidos"))
							        	if(!$totalRetenciones = $this->getpath("//@MontoTotOperacion"))
							        		$totalRetenciones = 0;

							        if(!$descuento_f = $this->getpath("//@Descuento"))
							        	$descuento_f = 0;
								}
								else
								{
									$data['folio'] 	= $this->getpath("//@folio");
									$data['emisor'] = $this->getpath("//@nombre");
									$data['total'] = $this->getpath("//@total");
									$data['rfc'] = $this->getpath("//@rfc");

									$data['calle'] = $this->getpath("//@calle");
									$data['noExt'] = $this->getpath("//@noExterior");
									$data['colonia'] = $this->getpath("//@colonia");
									$data['municipio'] = $this->getpath("//@municipio");
									$data['estado'] = $this->getpath("//@estado");
									$data['cp'] = $this->getpath("//@codigoPostal");

									$data['FechaPago'] = $this->getpath("//@FechaPago");
									$data['NumEmpleado'] = $this->getpath("//@NumEmpleado");
									$subtotal = $this->getpath("//@subTotal");
									if(!$totalImpuestosTrasladados = $this->getpath("//@totalImpuestosTrasladados"))
							        	$totalImpuestosTrasladados = 0;
							        if(!$totalRetenciones = $this->getpath("//@totalImpuestosRetenidos"))
							        	if(!$totalRetenciones = $this->getpath("//@montoTotOperacion"))
							        		$totalRetenciones = 0;

							        if(!$descuento_f = $this->getpath("//@descuento"))
							        	$descuento_f = 0;
								}

						        $totalConceptos = floatval($subtotal) + floatval($totalImpuestosTrasladados) - floatval($totalRetenciones) - floatval($descuento_f);

								$tipo = explode('.',$archivo);
								//Termina obtener UUID---------------------------
								$domicilio_fiscal;
								if($data['rfc'][0] == $rfcOrganizacion['RFC'])
								{
									$nombre = $data['emisor'][1];
									$rfc = $data['rfc'][1];
									$tipo_fac = 1;//Ingresos
									$domicilio_fiscal['calle'] = $data['calle'][1];
									$domicilio_fiscal['noExt'] = $data['noExt'][1];
									$domicilio_fiscal['cp'] = $data['cp'][1];
									$domicilio_fiscal['colonia'] = $data['colonia'][1];
									$domicilio_fiscal['municipio'] = $data['municipio'][1];
									$domicilio_fiscal['estado'] = $data['estado'][1];

								}
								elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
								{
									$nombre = $data['emisor'][0];
									$rfc = $data['rfc'][0];
									$tipo_fac = 0;//Egresos
									$domicilio_fiscal['calle'] = $data['calle'][0];
									$domicilio_fiscal['noExt'] = $data['noExt'][0];
									$domicilio_fiscal['cp'] = $data['cp'][0];
									$domicilio_fiscal['colonia'] = $data['colonia'][0];
									$domicilio_fiscal['municipio'] = $data['municipio'][0];
									$domicilio_fiscal['estado'] = $data['estado'][0];
								}
								$domicilio_fiscal['nomina'] = 0;
								$domicilio_fiscal['NumEmpleado'] = $data['NumEmpleado'];
								if($data['NumEmpleado'] != '')
									$domicilio_fiscal['nomina'] = 1;

								//$dif = $totalConceptos - floatval($data['total']);
								/* && $dif <= 0.1 && $dif >= -0.1*/
								if($this->valida_xsd($version[0],$xml) && strtolower($tipo[1]) == "xml")
								{
									if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1])
									{
										$noOrganizacion = 0;
										$numeroInvalidos++;
										$facturasNoValidas .= $archivo."(RFC no de Organizacion),\n";
									}
									else
									{
										$noOrganizacion = 1;
									}
									$data['folio'] = str_replace('-', '', $data['folio']);
									$nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
									if($noOrganizacion)
									{
										$almacen= $this->path()."xmls/facturas/repetidos/";
										$validaexiste = $this->existeXML($this->quitar_tildes($nombreArchivo));
										$repetidos=0;
										if($validaexiste){
											$numeroInvalidos++;
											$noOrganizacion=0;
											$facturasNoValidas .= $archivo."Ya existe en $validaexiste.\n";
											 $repetidos=1;
											 mkdir ($almacen,0777);
											rename($file, $almacen.$this->quitar_tildes($nombreArchivo));

										}else{ $noOrganizacion = 1; }
									}
									if($noOrganizacion)
									{
										copy($ruta."ziptempo/$foldername/".$archivo,$ruta2."/".$this->quitar_tildes($nombreArchivo));

										$numeroValidos++;
										$ret = $this->CaptPolizasModel->buscaClienteProveedor($rfc,$nombre,$tipo_fac,$domicilio_fiscal);
										$facturasValidas .= $archivo.",g: $ret\n";
										$this->guardaFactura($ruta2,$this->quitar_tildes($nombreArchivo),$data['uuid'],$rfcOrganizacion['RFC'],$data['pago'],$data['NumEmpleado']);

									}
									unlink($ruta."ziptempo/$foldername/".$archivo);

								}
								else
								{
									unlink($ruta."ziptempo/$foldername/".$archivo);
									$numeroInvalidos++;
									$dif_mensaje = '';
									/*if($dif > 0.1 || $dif < -0.1)
										$dif_mensaje = " (No coinciden importes totales) ".$totalConceptos ." vs ". $data['total']." $dif";*/
									$facturasNoValidas .= $data['uuid'].$dif_mensaje.",\n";
								}
							}
						}
						$folder_invalido = 0;
						$files = glob($ruta."ziptempo/$foldername/*/*");
								foreach($files as $file)
								{
								  if(is_file($file))
								    unlink($file);
								  elseif(is_dir($file))
								  	rmdir($file);
								  $folder_invalido++;
								}
						$files = glob($ruta."ziptempo/$foldername/*");
								foreach($files as $file)
								{
								  if(is_file($file))
								    unlink($file);
								  elseif(is_dir($file))
								  	rmdir($file);
								}
						//rmdir($ruta."ziptempo/$foldername/$foldername/");
						rmdir($ruta."ziptempo/$foldername/.DS_Store");
						rmdir($ruta."ziptempo/$foldername/");
						rmdir($ruta."ziptempo/");
						unlink($ruta."tempo.zip");
						if(!intval($folder_invalido))
							$funciono = 1;
						else
							$funciono = 0;
					}
					else
					{
						unlink($ruta."tempo.zip");
						$files = glob($ruta.'ziptempo/*/*');
						foreach($files as $file)
						{
						  if(is_file($file))
						    unlink($file);
						}
						$files = glob($ruta.'ziptempo/*');
						foreach($files as $file)
						{
						  if(is_file($file))
						    unlink($file);
						}
						if($directorio = opendir($ruta."ziptempo/"))
						{
							while ($archivo = readdir($directorio))
							{
								if(is_dir($ruta."ziptempo/$archivo"))
								{
									rmdir($ruta."ziptempo/$archivo/");
								}
							}
						}

						rmdir($ruta."ziptempo/");
						$funciono = 0;
					}
				}
			}
			echo $funciono."-/-*".$numeroValidos."-/-*".$facturasValidas."-/-*".$numeroInvalidos."-/-*".$facturasNoValidas."-/-*".$repetidos;
	}
	function subeFactura()
	{
		global $xp;
		$facturasNoValidas = $facturasValidas = '';
		$numeroInvalidos = $numeroValidos = $no_hay_problema = $noOrganizacion = 0;
		$maximo = count($_FILES['factura']['name']);
		$maximo = (intval($maximo)-1);

		for($i = 0; $i <= $maximo; $i++)
		{

			if($_FILES["factura"]["size"][$i] > 0)
			{

				//Comienza obtener UUID---------------------------
				$file 	= $_FILES['factura']['tmp_name'][$i];
				$texto 	= file_get_contents($file);
				//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
				//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
				$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            	$texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
				$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '', $texto);
                $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '', $texto);
				$xml 	= new DOMDocument();
				$xml->loadXML($texto);

				$xp = new DOMXpath($xml);
				$data['uuid'] 	= $this->getpath("//@UUID");
				//COMIENZA VERSION---------------------------------------
				if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");

				$version = $data['version'];
				//TERMINA VERSION---------------------------------------
				if($version[0] == '3.3')
				{
					//$data['uuid'] 	= $data['uuid'][1];
					if(is_array($data['uuid'])){
						$data['uuid'] 	= $data['uuid'][1];
					}
					$data['folio'] 	= $this->getpath("//@Folio");
					$data['emisor'] = $this->getpath("//@Nombre");
					$data['total'] = $this->getpath("//@Total");
					$data['rfc'] = $this->getpath("//@Rfc");
				}
				else
				{
					$data['folio'] 	= $this->getpath("//@folio");
					$data['emisor'] = $this->getpath("//@nombre");
					$data['total'] = $this->getpath("//@total");
					$data['rfc'] = $this->getpath("//@rfc");
				}
				
				//$data['rfc_receptor'] = utf8_decode($rfc[1]);
				//Termina obtener UUID---------------------------
				$rfcOrganizacion= $this->CaptPolizasModel->rfcOrganizacion();
				if($data['rfc'][0] == $rfcOrganizacion['RFC']){
					$nombre = $data['emisor'][1];
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC']){
					$nombre = $data['emisor'][0];
				}
				else{
					$nombre = $data['emisor'][1];
				}

				if($this->valida_xsd($version[0],$xml) && $_FILES['factura']['type'][$i] == "text/xml")
				{

					if($version[0] == '3.2')
					{
						$no_hay_problema = $this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']);
					}
					else
					{
						$no_hay_problema = 1;
					}
					if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1]){
						$noOrganizacion = 0;
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['factura']['name'][$i]."(RFC no de Organizacion),\n";
					}else{ $noOrganizacion = 1; }

					$data['folio'] = str_replace('-', '', $data['folio']);
					$nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
					$nombreArchivo = $this->quitar_tildes($nombreArchivo);
					if($noOrganizacion){
						$validaexiste = $this->existeXML($nombreArchivo);
						if($validaexiste){
							$noOrganizacion = 0;
							$numeroInvalidos++;
							$facturasNoValidas .= $_FILES['factura']['name'][$i]."Ya existe en $validaexiste.\n";
						}else{ $noOrganizacion = 1; }
					}
					if($noOrganizacion){
						if($no_hay_problema)
						{

							
							$ruta = $this->path()."xmls/facturas/" . $_POST['plz'];//Ruta donde se guardara

							if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
							{
								mkdir ($ruta,0777);
							}

							if(!file_exists($ruta."/".$nombreArchivo))
							{
								if(move_uploaded_file($_FILES["factura"]["tmp_name"][$i], $ruta."/".$nombreArchivo))
								{
									$numeroValidos++;
									$facturasValidas .= $_FILES['factura']['name'][$i].",\n";
								}
							}
							else
							{
								$numeroInvalidos++;
								$facturasNoValidas .= $_FILES['factura']['name'][$i]."(Ya existe),\n";
							}
						}
						else
						{
							$numeroInvalidos++;
							$facturasNoValidas .= $_FILES['factura']['name'][$i]."(Cancelada),\n";
						}
					}
				}
				else
				{
					$numeroInvalidos++;
					$facturasNoValidas .= $_FILES['factura']['name'][$i]."(Estructura incorrecta),\n";
				}
			}
		}
		echo $numeroValidos."-/-*".$facturasValidas."-/-*".$numeroInvalidos."-/-*".$facturasNoValidas." PARA AGREGAR FACTURAS EXISTENTES REALIZARLO DESDE ALMACEN";
	}

	function valida_xsd($version,$xml)
	{

		libxml_use_internal_errors(true);
		switch ($version)
		{
  			case "2.0":
    			$ok = $xml->schemaValidate("xmls/valida_xmls/xsds/cfdv2complemento.xsd");
    			break;
  			case "2.2":
    			$ok = $xml->schemaValidate("xmls/valida_xmls/xsds/cfdv22complemento.xsd");
    			break;
  			case "3.0":
    			$ok = $xml->schemaValidate("xmls/valida_xmls/xsds/cfdv3complemento.xsd");
    			break;
  			case "3.2":
    			$ok = $xml->schemaValidate("xmls/valida_xmls/xsds/cfdv32.xsd");
    			break;
			case "3.3":
    			$ok = $xml->schemaValidate("xmls/valida_xmls/xsds/cfdv33.xsd");
    			break;    			
  			default:
    			$ok = 0;
		}
		return $ok;
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

	function valida_en_sat($rfc,$rfc_receptor,$total,$uuid)
	{
    	error_reporting(E_ALL);
    	require_once('xmls/valida_xmls/nusoap/nusoap.php');
    	error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING|E_DEPRECATED));
    	$url = "https://consultaqr.facturaelectronica.sat.gob.mx/consultacfdiservice.svc?wsdl";

    	$soapclient = new nusoap_client($url,$esWSDL=true);
    	$soapclient->soap_defencoding = 'UTF-8';
    	$soapclient->decode_utf8 = false;

    	$rfc_emisor = utf8_encode($rfc);
    	$rfc_receptor = utf8_encode($rfc_receptor);
    	$impo = (double)$total;
    	$impo=sprintf("%.6f", $impo);
    	$impo = str_pad($impo,17,"0",STR_PAD_LEFT);

    	$uuid = strtoupper($uuid);

    	$factura = "?re=$rfc_emisor&rr=$rfc_receptor&tt=$impo&id=$uuid";

    	$prm = array('expresionImpresa'=>$factura);

    	$buscar=$soapclient->call('Consulta',$prm);

    	//echo "Status del C&oacute;digo: ".$buscar['ConsultaResult']['CodigoEstatus']."<br>";
    	//echo "Status: ".$buscar['ConsultaResult']['Estado']."<br>";
    	if($buscar['ConsultaResult']['Estado'] == "Cancelado")
    	{
    		return 0;
    	}
    	else
    	{
    		return 1;
    	}

	}

	function verpolicli()
	{
		$forma_pago=$this->CaptPolizasModel->formapago();
		$periodo=$this->CaptPolizasModel->getExerciseInfo();
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientecobro = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$ivapendientecobro  = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$iepscobrado        = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPScobrado']);
			$ivacobrado         = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAcobrado']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
		}
		$iepspendientecobro = explode("//", $iepspendientecobro);
		$ivapendientecobro = explode("//", $ivapendientecobro);
		$iepscobrado = explode("//", $iepscobrado);
		$ivacobrado = explode("//", $ivacobrado);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);

		$cuentas=$this->CaptPolizasModel->cuentaivas();
		$listadoivaieps="";
		while($campo=$cuentas->fetch_array()){
			$listadoivaieps .= "<option value=".$campo['account_id'].">".$campo['description']."(".$campo['manual_code'].")</option>";
		}

		if($p=$periodo->fetch_array()){
			$ejercicio = $p['NombreEjercicio'];
			if($p['PeriodoActual']!=13){
				$idperiodo=$p['PeriodoActual'];
				$sql=$this->CaptPolizasModel->bancos();
				$sqlcli=$this->CaptPolizasModel->clientes();
				$sqlcliarbol=$this->CaptPolizasModel->clientes1();//
				$sqlcliarbol2=$this->CaptPolizasModel->clientes1();//
				$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
				$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
				$Exercise = $this->CaptPolizasModel->getExerciseInfo();
				$Ex = $Exercise->fetch_assoc();
				$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
				$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
				$Suc = $this->CaptPolizasModel->getSegmentoInfo();

				if($sql->num_rows>0){
					$bancos=$sql;
				}else{
					$bancosno="Por favor elija una cuenta de bancos en el menu de configuracion o agregue cuentas al arbol ";
				}
				if($sqlcli->num_rows>0 || $sqlcliarbol->num_rows>0){
					$clientes=$sqlcli;
					$sqlcli2=$sqlcliarbol;
					$cuentasinarbol=$sqlcliarbol2;

				}else{
					$clientesno="Por favor elija una cuenta de clientes en el menu de configuracion o agregue cuentas al arbol ";
				}
				if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
				{
				 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					include('views/captpolizas/polizacliente.php');
				}
				else
				{
					echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config&f=mainPage','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
				}


			}else{
				echo '<script> alert("No puedes generar polizas automaticas en el perido 13");</script>';
			}
		}
	}
	function verpoliprove()
	{
		$periodo=$this->CaptPolizasModel->getExerciseInfo();
		$forma_pago=$this->CaptPolizasModel->formapago();
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientepago  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientepago   = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$CuentaIVApagado = $this->CaptPolizasModel->buscacuenta($row['CuentaIVApagado']);
			$CuentaIEPSpagado  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSpagado']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);

		}
		$ivapendientepago = explode("//", $ivapendientepago);
		$CuentaIVApagado = explode("//", $CuentaIVApagado);
		$iepspendientepago = explode("//", $iepspendientepago);
		$CuentaIEPSpagado = explode("//", $CuentaIEPSpagado);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);

		$cuentas=$this->CaptPolizasModel->cuentaivas();
		$listadoivaieps="";
		while($campo=$cuentas->fetch_array()){
			$listadoivaieps .= "<option value=".$campo['account_id'].">".$campo['description']."(".$campo['manual_code'].")</option>";
		}
		if($p=$periodo->fetch_array()){
			$ejercicio = $p['NombreEjercicio'];
			$idperiodo=$p['PeriodoActual'];
			if($p['PeriodoActual']!=13){
				$sql=$this->CaptPolizasModel->bancos();
				$sqlprov=$this->CaptPolizasModel->proveedor();//proveedores del padron asociados a cuenta contable
				$sqlprov2=$this->CaptPolizasModel->proveedor2();//proveedores del arbol no asociados auna cuenta
				$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
				$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
				$beneficiario=$this->CaptPolizasModel->proveedor();//proveedores del padron asociados a cuenta contable
				$listabancos = $this->CaptPolizasModel->listabancos();//IBM
				$Exercise = $this->CaptPolizasModel->getExerciseInfo();
				$Ex = $Exercise->fetch_assoc();
				$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
				$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
				$Suc = $this->CaptPolizasModel->getSegmentoInfo();
				$listacuentasbancarias = $this->CaptPolizasModel->cuentasbancariaslista();
				//bet
				if($sql->num_rows>0){
					$bancos=$sql;
				}else{
					$bancosno="Por favor elija una cuenta de bancos en el menu de configuracion o agrege hijos a la cuenta";
				}
				if($sqlprov->num_rows>0 || $sqlprov2->num_rows>0){
					$proveedores2=$sqlprov2;
					$proveedores=$sqlprov;
				}else{
					$proveedoresno="No hay proveedores registrados o asociados";
				}

				if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
				{
				 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					include('views/captpolizas/polizaproveedor.php');
				}
				else
				{
					echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config&f=mainPage','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
				}



			}else{
				echo '<script> alert("No puedes generar polizas automaticas en el perido 13");</script>';
			}
		}
	}

	function tabla(){//cobro

		$cuentaconf = $this->CaptPolizasModel->cuentasconf();
		$cuentaimp = $cuentaconf->fetch_object();
			if($_REQUEST['radio']==2){
				// $xmld = simplexml_load_file($_FILES['xmlsube']['tmp_name']);
				// $ns = $xmld -> getNamespaces(true);
				// $xmld -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
				// $xmld -> registerXPathNamespace('t', $ns['tfd']);
				// foreach ($xmld->xpath('//t:TimbreFiscalDigital') as $tfd) {
  					// $uuid= $tfd['UUID'];
				// }
				// foreach ($xmld->xpath('//cfdi:Comprobante') as $cfdiComprobante){
				  // $folio=$cfdiComprobante['folio'];
				  // $total+=number_format(floatval($cfdiComprobante['total']),2,'.','');
				// }
				 // foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
   					 // $emisor=$Emisor['nombre'];
    			// }
				 // $ieps=0; $iva=0;
				// $tabla[$_REQUEST['cliente']]['IVA']=0;
				// $tabla[$_REQUEST['cliente']]['IEPS']=0;
				// foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Trasla){
//
					// if($Trasla['impuesto']=="IVA"){
				  		// if($Trasla['importe']>0){
				  			// $iva+=number_format(floatval($Trasla['importe']),2,'.','');
						 // }
					 // }
					 // if($Trasla['impuesto']=="IEPS"){
						 // if($Trasla['importe']>0){
							  // $ieps+=number_format(floatval($Trasla['importe']),2,'.','');
						 // }
			 		 // }
//
			 		// // echo '<script>alert("'.$ieps.'");</script>';
				// }
				 // $xml=$this->quitar_tildes($folio."-Cobro"."_".$emisor."_".$uuid.".xml");
		 		// move_uploaded_file($_FILES['xmlsube']['tmp_name'],'xmls/facturas/temporales/'.utf8_encode($xml));

			}else{
				$maximo = count($_POST['xml']);
				$maximo = (intval($maximo)-1);
				//$leer=file_get_contents($_REQUEST['xml']);
				for($i=0;$i<=$maximo;$i++){
					$cade="";$xml="";$uuid="";$total=0.00;$tabla=array();
					$xml = $_POST['xml'][$i];

				        
					$xmld = simplexml_load_file($this->path().'xmls/facturas/temporales/'.$_POST['xml'][$i]);
					$ns = $xmld -> getNamespaces(true);
					$xmld -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
					$xmld -> registerXPathNamespace('t', $ns['tfd']);
					foreach ($xmld->xpath('//t:TimbreFiscalDigital') as $tfd) {
	  					$uuid= $tfd['UUID'];
					}
					foreach ($xmld->xpath('//cfdi:Comprobante') as $cfdiComprobante){
						if(!$cfdiComprobante['fecha']){
					  		$cfdiComprobante['fecha'] = $cfdiComprobante['Fecha'];
						}
						if(!$cfdiComprobante['folio']){
					  		$cfdiComprobante['folio'] = $cfdiComprobante['Folio'];
						}
						$fecha= $cfdiComprobante['fecha'];
					  	$folio=$cfdiComprobante['folio'];
						if(!$cfdiComprobante['total']){
							$cfdiComprobante['total'] = $cfdiComprobante['Total'];
						}
					  $total+=number_format(floatval($cfdiComprobante['total']),2,'.','');
					}
					 foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
					 	if($Emisor['nombre']){
	   						$emisor=$Emisor['nombre'];
						}else{
							$emisor=$Emisor['Nombre'];
						}
	    				}
					$ieps=0; $iva=0;
					$tabla[$_REQUEST['cliente']]['IVA']=0;
					$tabla[$_REQUEST['cliente']]['IEPS']=0;
					foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Trasla){
						if(!$Trasla['impuesto']){
							$Trasla['impuesto'] = $this->CaptPolizasModel->nombreImpuestoIndividual($Trasla['Impuesto']);
							$Trasla['importe'] = $Trasla['Importe'];	
							if($Trasla['impuesto']=="IVA"){
					  		if($Trasla['importe']>0){
					  			$iva=number_format(floatval($Trasla['Importe']),2,'.','');
							 }
							$tasaiva=$Trasla['tasa'];
						 }
						 if($Trasla['impuesto']=="IEPS"){
							 if($Trasla['importe']>0){
								  $ieps=number_format(floatval($Trasla['importe']),2,'.','');
							 }
							 $tasaieps = $Trasla['tasa'];
				 		 }
						}
						else{
						if($Trasla['impuesto']=="IVA"){
					  		if($Trasla['importe']>0){
					  			$iva+=number_format(floatval($Trasla['importe']),2,'.','');
							 }
							$tasaiva=$Trasla['tasa'];
						 }
						 if($Trasla['impuesto']=="IEPS"){
							 if($Trasla['importe']>0){
								  $ieps+=number_format(floatval($Trasla['importe']),2,'.','');
							 }
							 $tasaieps = $Trasla['tasa'];
				 		 }
						}
				 		// echo '<script>alert("'.$ieps.'");</script>';
					}


				//pato

				$tabla[$_REQUEST['cliente']]['cliente']		= $_REQUEST['cliente'];
				$tabla[$_REQUEST['cliente']]['cuentacliente']= $_REQUEST['clientesincuenta'];
				$tabla[$_REQUEST['cliente']]['banco']		= $_REQUEST['banco'];
				$tabla[$_REQUEST['cliente']]['concepto']		= $_REQUEST['concepto'];
				$tabla[$_REQUEST['cliente']]['segmento']		= $_REQUEST['segmento'];
				$tabla[$_REQUEST['cliente']]['sucursal']		= $_REQUEST['sucursal'];
				$tabla[$_REQUEST['cliente']]['xml']			= $xml;
				$tabla[$_REQUEST['cliente']]['formapago']	= $_REQUEST['formapago'];
				$tabla[$_REQUEST['cliente']]['numeroformapago']	= $_REQUEST['numeroformapago'];

				if(!mb_stristr($xml, "Parcial")){
					$tabla[$_REQUEST['cliente']]['importe']	= number_format(floatval($total),2,'.','');
					$tabla[$_REQUEST['cliente']]['IVA']		= number_format(floatval($iva),2,'.','');
					$tabla[$_REQUEST['cliente']]['IEPS']		= number_format(floatval($ieps),2,'.','');
					$tabla[$_REQUEST['cliente']]['MontoParcial']=0;
				}else{
					$idcuenta=0;
					if(strrpos($_REQUEST['cliente'],"-")){
						$c=explode('-',$_REQUEST['cliente']);
						$idcuenta = $c[0];
					}else{
						$client=explode('/',$_REQUEST['cliente']);//abono
						$verifica=$this->CaptPolizasModel->validacuentaclientes($client[0]);
						if($verifica==0){
							$idcuenta=$cliente['cuentacliente'];
						}else{
							$idcuenta=$verifica;

						}
					}
					$tasaiva = $tasaiva/100;
					$tasaieps = $tasaieps/100 ;
					$archivo=str_replace("Parcial-", "", $xml);
					$consultaCobros = $this->CaptPolizasModel->cuentaXMLimporte($archivo, $idcuenta,"Abono",1);
					$nuevototal = $total - $consultaCobros['monto'];
					$tabla[$_REQUEST['cliente']]['importe']	= number_format(floatval($nuevototal),2,'.','');
					$tabla[$_REQUEST['cliente']]['IVA']	= number_format(floatval(($nuevototal/($tasaiva+1))*$tasaiva),2,'.','');
					$tabla[$_REQUEST['cliente']]['IEPS']	= number_format(floatval(($nuevototal/($tasaieps+1))*$tasaieps),2,'.','');
					$tabla[$_REQUEST['cliente']]['MontoParcial']=1;

				 }//asigna


				$fec = explode("T", $fecha);
				if($_REQUEST['fecha']){
					$_SESSION['fechacli']=$_REQUEST['fecha'];
				}else{
					$_SESSION['fechacli']=$fec[0]."";
				}


				$_SESSION['tabla'][]=$tabla;
			}
		}
			// echo $_SESSION['tabla'];
			echo '<script>window.location="index.php?c=CaptPolizas&f=verpolicli"; </script>';
		}
		function tablaprov(){

				//$formapago= $_REQUEST['formapago'];


			if($_REQUEST['radio']==2){
				// $xmld = simplexml_load_file($_FILES['xmlsube']['tmp_name']);
				// $ns = $xmld -> getNamespaces(true);
				// $xmld -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
				// $xmld -> registerXPathNamespace('t', $ns['tfd']);
				// foreach ($xmld->xpath('//t:TimbreFiscalDigital') as $tfd) {
  					// $uuid= $tfd['UUID'].".xml";
				// }
				// foreach ($xmld->xpath('//cfdi:Comprobante') as $cfdiComprobante){
					// $folio=$cfdiComprobante['folio'];
					// $total+=$cfdiComprobante['total'];
				// }
				 // foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
   					 // $emisor=$Emisor['nombre'];
    			// }
				  // $ieps=0; $iva=0;
				// $tabla[$_REQUEST['proveedor']]['IVA']=0;
				// $tabla[$_REQUEST['proveedor']]['IEPS']=0;
				// foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Trasla){
//
					// if($Trasla['impuesto']=="IVA"){
				  		// if($Trasla['importe']>0){
				  			// $iva+=number_format(floatval($Trasla['importe']),2,'.','');
						 // }
					 // }
					 // if($Trasla['impuesto']=="IEPS"){
						 // if($Trasla['importe']>0){
							  // $ieps+=number_format(floatval($Trasla['importe']),2,'.','');
						 // }
			 		 // }
//
			 		// // echo '<script>alert("'.$ieps.'");</script>';
				// }
				// $xml=$this->quitar_tildes($folio."-Pago"."_".$emisor."_".$uuid.".xml");
		 		// move_uploaded_file($_FILES['xmlsube']['tmp_name'],'xmls/facturas/temporales/'.utf8_encode($xml));
//
			}else{
				$maximo = count($_POST['xml']);
				$maximo = (intval($maximo)-1);
			for($i=0;$i<=$maximo;$i++){
				$xml="";$uuid=""; $total=0.00;
				$xml=$_POST['xml'][$i];

				$xmld = simplexml_load_file($this->path().'xmls/facturas/temporales/'.$_POST['xml'][$i]);
				$ns = $xmld -> getNamespaces(true);
				$xmld -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
				$xmld -> registerXPathNamespace('t', $ns['tfd']);
				foreach ($xmld->xpath('//t:TimbreFiscalDigital') as $tfd) {
  					$uuid= $tfd['UUID'];
				}
				foreach ($xmld->xpath('//cfdi:Comprobante') as $cfdiComprobante){
					if(!$cfdiComprobante['fecha']){
				 		$fecha= $cfdiComprobante['Fecha'];
					}
				  	if(!$cfdiComprobante['folio']){
				  		$folio=$cfdiComprobante['Folio'];
					}
					if(!$cfdiComprobante['total']){
						$cfdiComprobante['total'] = $cfdiComprobante['Total'];
					}
				  $total+=number_format(floatval($cfdiComprobante['total']),2,'.','');
				}
				 foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
				 	if(!$Emisor['nombre']){
				 		$Emisor['nombre'] = $Emisor['Nombre'];
				 	}
   					 $emisor=$Emisor['nombre'];
    				}
				 $ieps=0; $iva=0;
				$tabla[$_REQUEST['proveedor']]['IVA']=0;
				$tabla[$_REQUEST['proveedor']]['IEPS']=0;
				foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Trasla){
					
					if(!$Trasla['impuesto']){
						 $tasa=$Traslado['Tasa'];
						$Trasla['impuesto'] = $this->CaptPolizasModel->nombreImpuestoIndividual($Trasla['Impuesto']);
						$Trasla['importe'] = $Trasla['Importe'];	
						if($Trasla['impuesto']=="IVA"){
							$iva = number_format(floatval($Trasla['Importe']),2,'.','');
						}
						 if($Trasla['impuesto']=="IEPS"){
						 	 $ieps+=number_format(floatval($Trasla['Importe']),2,'.','');
						 }
					}else{
					if($Trasla['impuesto']=="IVA"){
				  		if($Trasla['importe']>0){
				  			$iva+=number_format(floatval($Trasla['importe']),2,'.','');
						 }
						$tasaiva=$Trasla['tasa'];
					 }
					 if($Trasla['impuesto']=="IEPS"){
						 if($Trasla['importe']>0){
							  $ieps+=number_format(floatval($Trasla['importe']),2,'.','');
						 }
						 $tasaieps=$Trasla['tasa'];
			 		 }
					}
			 		// echo '<script>alert("'.$ieps.'");</script>';
				}

				$tabla=array();
				$tabla[$_REQUEST['proveedor']]['proveedor']=$_REQUEST['proveedor'];
				$tabla[$_REQUEST['proveedor']]['banco']=$_REQUEST['banco'];
				$tabla[$_REQUEST['proveedor']]['concepto']=$_REQUEST['concepto'];
				$tabla[$_REQUEST['proveedor']]['xml']=$xml;
				$tabla[$_REQUEST['proveedor']]['segmento']=$_REQUEST['segmento'];
				$tabla[$_REQUEST['proveedor']]['sucursal']=$_REQUEST['sucursal'];
				$tabla[$_REQUEST['proveedor']]['beneficiario']=$_REQUEST['beneficiario'];
				$tabla[$_REQUEST['proveedor']]['numero'] = $_REQUEST['numero'];
				$tabla[$_REQUEST['proveedor']]['rfc'] =$_REQUEST['rfc'];
				$tabla[$_REQUEST['proveedor']]['numtarje'] = $_REQUEST['numtarje'];
				$tabla[$_REQUEST['proveedor']]['listabanco']=$_REQUEST['listabanco'];
				$tabla[$_REQUEST['proveedor']]['formapago']=$_REQUEST['formapago'];
				$tabla[$_REQUEST['proveedor']]['listabancoorigen']=$_REQUEST['listabancoorigen'];
				$tabla[$_REQUEST['proveedor']]['numorigen']=$_REQUEST['numorigen'];//bet
				if(!mb_stristr($xml, "Parcial")){
					$tabla[$_REQUEST['proveedor']]['IVA']=number_format(floatval($iva),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['IEPS']=number_format(floatval($ieps),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['importe']=number_format(floatval($total),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['MontoParcial']=0;

				 }else{
					$idcuenta=0;
					if(strrpos($_REQUEST['proveedor'],"-")){
						$c=explode('-',$_REQUEST['proveedor']);
						$idcuenta=$c[0];
					}else{
						$provee=explode('/',$_REQUEST['proveedor']);//cargo
						$idcuenta=$provee[0];

					}
 					$tasaiva = $tasaiva/100;
					$tasaieps = $tasaieps/100 ;
					$archivo=str_replace("Parcial-", "", $xml);
					$consultaCobros = $this->CaptPolizasModel->cuentaXMLimporte($archivo, $idcuenta,"Cargo",2);
					$nuevototal = $total - $consultaCobros['monto'];
					$tabla[$_REQUEST['proveedor']]['importe']	= number_format(floatval($nuevototal),2,'.','');
					$tabla[$_REQUEST['proveedor']]['IVA']		= number_format(floatval(($nuevototal/($tasaiva+1))*$tasaiva),2,'.','');
					$tabla[$_REQUEST['proveedor']]['IEPS']		= number_format(floatval(($nuevototal/($tasaieps+1))*$tasaieps),2,'.','');
					$tabla[$_REQUEST['proveedor']]['MontoParcial']=1;

				 }//asigna

				$fec = explode("T", $fecha);
				if($_REQUEST['fecha']){
					$_SESSION['fechaprove']=$_REQUEST['fecha'];
				}else{
					$_SESSION['fechaprove']=$fec[0]."";
				}


				$_SESSION['proveedor'][]=$tabla;
			}
		  }
			echo '<script> window.location="index.php?c=CaptPolizas&f=verpoliprove"; </script>';
		}

	function borra(){//para los clientes
		if($_REQUEST['cont']==0 && count($_SESSION['tabla'])==1){
			 unset($_SESSION['tabla']);
		}
		else{
			unset($_SESSION['tabla'][$_REQUEST['cont']]);
		}
		$_SESSION['tabla']=array_values($_SESSION['tabla']);//elimino los espacios en blanco
	}

	function borra2(){//para los proveedores
		if($_REQUEST['cont']==0 && count($_SESSION['proveedor'])==1){
			 unset($_SESSION['proveedor']);
		}
		else{
			unset($_SESSION['proveedor'][$_REQUEST['cont']]);
		}
		$_SESSION['proveedor']=array_values($_SESSION['proveedor']);//elimino los espacios en blanco

	}
	function borraprovision(){//para los clientes
		if($_REQUEST['cont']==0 && count($_SESSION[$_REQUEST['tipo']])==1){
			 unset($_SESSION[$_REQUEST['tipo']]);
		}
		else{
			unset($_SESSION[$_REQUEST['tipo']][$_REQUEST['cont']]);
		}
		$_SESSION[$_REQUEST['tipo']]=array_values($_SESSION[$_REQUEST['tipo']]);//elimino los espacios en blanco
	}
	function guarda(){
		// if($_POST['Periodo'] != 13)
		// {

		$unsolobanco = $_REQUEST['unsolobanco'];
		$fecha=$_REQUEST['fecha'];
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$cuenta = $this->CaptPolizasModel->conf();
		if($Ex=$Exercise->fetch_assoc()){
		$idorg=$Ex['IdOrganizacion'];
		$idejer=$Ex['IdEx'];
		$idperio=$Ex['PeriodoActual'];}
		if(isset($_COOKIE['ejercicio'])){
			$idejer = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
		}if(isset($_COOKIE['periodo'])){
			$idperio = $_COOKIE['periodo'];
		}
		$error=false;
		$carpeta=false;
		// if($idperio != 13)
		// {
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientecobro = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$ivapendientecobro  = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$ishh				= $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$iepscobrado        = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPScobrado']);
			$ivacobrado         = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAcobrado']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA			= $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);

		}
		$iepspendientecobro = explode("//", $iepspendientecobro);
		$ivapendientecobro = explode("//", $ivapendientecobro);
		$iepscobrado = explode("//", $iepscobrado);
		$ivacobrado = explode("//", $ivacobrado);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);


		//pasar los valores a insertar
			$poli=$this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,1,'Cobro a cliente',$fecha,0,$_REQUEST['numeroforma'],"",0,"",0,0);
			if($poli==0){
				$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
			    
					if(mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
						$carpeta=true;
					}
				//krmn aki cambia clientes
				$UltimoMov=1;
					foreach($_SESSION['tabla'] as $cli){
						foreach($cli as $cliente){
							if(strrpos($cliente['cliente'],"-")){
								$ccliente="";
								$c=explode('-',$cliente['cliente']);
								$idcuenta = $c[0];
							}else{
								$client=explode('/',$cliente['cliente']);//abono
								$ccliente = $client[0];
								$verifica=$this->CaptPolizasModel->validacuentaclientes($client[0]);
								if($verifica==0){
								//if($row=$cuenta->fetch_assoc()){
									$idcuenta=$cliente['cuentacliente'];
									$updatecliente=$this->CaptPolizasModel->actualizacliente($idcuenta, $ccliente);
								}else{
									$idcuenta=$verifica;
								}
							 	////hacer prueba con los demas clientes
								//}
							}
							if($statusIVAIEPS==0){
								$iepspendientecobro[0] = $cliente['cuentaiepspendiente'];
								$ivapendientecobro[0] = $cliente['cuentaivapendiente'];
								$iepscobrado[0] = $cliente['cuentaiepscobrado'];
								$ivacobrado[0] = $cliente['cuentaivacobrado'];
							}
							$banco=explode('/',$cliente['banco']);//cargo
							 $cbanco=$banco[0];


							 // $UltimoMov = $this->CaptPolizasModel->UltimoMov($numPoliza['id']);
							 // if($UltimoMov==""){
							 	// $UltimoMov=1;
							 // }else{
							 	// $UltimoMov++;
							 // }
							$separa=explode('_',$cliente['xml']);
							$referencia=str_replace(".xml"," ",$separa[2]);
							$segment = explode('//',$cliente['segmento']);
							$sucu = explode('//',$cliente['sucursal']);
							 //pato2

							$xmlrelacion= str_replace("Parcial-", "", $cliente['xml']);
							if($cliente['concepto']==""){ $cliente['concepto']="Cobro a Clientes";}

							$abono=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$idcuenta,"Abono",number_format($cliente['importe'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
							 if($abono==true){
							 	if($unsolobanco==0){
							 		$UltimoMov+=1;
							 		$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Cargo",number_format($cliente['importe'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
							 	}
								else{
									$si = true;
									$conceptoBancos = $cliente['concepto'];
									$totalbancos += $cliente['importe'];
								}
								$UltimoMov+=1;
								if($statusIVA==1){
							 		if($cliente['IVA']>0){

							 			$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivapendientecobro[0],"Cargo",number_format($cliente['IVA'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
										$UltimoMov+=1;
										$insertaivacobrado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivacobrado[0],"Abono",number_format($cliente['IVA'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
										$UltimoMov+=1;
									}
								}
								if($statusIEPS==1){
									if($cliente['IEPS']>0){//
							 			$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepspendientecobro[0],"Cargo",number_format($cliente['IEPS'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
										$UltimoMov+=1;
										$insertaivacobrado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepscobrado[0],"Abono",number_format($cliente['IEPS'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
										$UltimoMov+=1;
									}
								}
						 		if($si==false){
						 			$error=true;
						 		}else{
						 			 if($carpeta==true){

						 			 	$rutaOrigen = $this->path()."xmls/facturas/temporales/".$cliente['xml'];
										 //str_replace("Parcial", "", $cliente['xml'])
						 			 	if($cliente['parcial']==0){//cobro
											rename($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion );
										}
										else{
											copy($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion );
											rename($rutaOrigen, $this->path()."xmls/facturas/temporales/Parcial-".$xmlrelacion);
											$this->CaptPolizasModel->facturaRename("Parcial-".$xmlrelacion);
										}
									}
								 }
							 }else{
							 	$error=true;
							 }
						}
					}
					if($unsolobanco==1){
				 		$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Cargo",number_format($totalbancos,2,'.',''),$conceptoBancos,'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
				 	}
			}//el de insertar poliza
			else{
				$error=true;
			}
  			if($error==false){
  				unset($_SESSION['tabla']);
				unset($_SESSION['fechacli']);


				echo '-_-1-_-';
  			}else{
  				echo '-_-2-_-';
  			}

		//}
		// else
		// {
			// echo '-_-0-_-';
		// }




	}
//////////
function guardaprove(){
		$fecha=$_REQUEST['fecha'];
		$beneficiario = $_REQUEST['beneficiario'];
		$numero = $_REQUEST['numero'];
		$rfc =$_REQUEST['rfc'];
		$numtarjcuent = $_REQUEST['numtarje'];
		$idbnPrv = explode('/', $_REQUEST['listabanco']);
		$idbanco = $idbnPrv[0];
		$f=explode("/",$_REQUEST['formapago']);
		$formapago=$f[0];
		$bancoorigen= $_REQUEST['bancoorigen'];
		$numorigen = $_REQUEST['numorigen'];
		$unsolobanco = $_REQUEST['unsolobanco'];
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$proveedores = $this->CaptPolizasModel->proveedor();
		// $cuenta = $this->CaptPolizasModel->conf();


		if($Ex=$Exercise->fetch_assoc()){
		$idorg=$Ex['IdOrganizacion'];
		$idejer=$Ex['IdEx'];
		$idperio=$Ex['PeriodoActual'];}
		if(isset($_COOKIE['ejercicio'])){
			$idejer = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
		}if(isset($_COOKIE['periodo'])){
			$idperio = $_COOKIE['periodo'];
		}
		$cuentas = $this->CaptPolizasModel->cuentasconf();

		if($row=$cuentas->fetch_array()){
			$iepspendientepago	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientepago	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$ish				    = $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$CuentaIVApagado 	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVApagado']);
			$CuentaIEPSpagado  	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSpagado']);
			$statusIVAIEPS      	= $row['statusIVAIEPS'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA			= $row['statusIVA'];
		}
			$iepspendientepago  = explode("//", $iepspendientepago);
			$ivapendientepago   = explode("//", $ivapendientepago);
			$ish				= explode("//", $ish);
			$CuentaIVApagado 	= explode("//", $CuentaIVApagado);
			$CuentaIEPSpagado	= explode("//", $CuentaIEPSpagado);
		$error=false;
		$carpeta=false;

			// if($idperio != 13)
			// {


			$poli=$this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,2,'Pago a proveedores',$fecha,$beneficiario,$numero,$rfc,$idbanco,$numtarjcuent,$bancoorigen,1);
			if($poli==0){

				$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
					if(mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
						$carpeta=true;
					}
					$UltimoMov=1;$totalbancos=0;
					foreach($_SESSION['proveedor'] as $cli){
						foreach($cli as $prove){


							if(strrpos($prove['proveedor'],"-")){
								$cprovee="";
								$c=explode('-',$prove['proveedor']);
								$idcuenta=$c[0];
							}else{
								$provee=explode('/',$prove['proveedor']);//cargo
								$cprovee=$provee[1];
								$idcuenta=$provee[0];

							}
							$banco=explode('/',$prove['banco']);//abono
							$cbanco=$banco[0];

							$separa=explode('_',$prove['xml']);
							 $referencia=str_replace(".xml"," ",$separa[2]);
							 // $f=explode("/",$prove['formapago']);
							 // $formapago=$f[0];
							 $segment = explode('//',$prove['segmento']);
							$sucu = explode('//',$prove['sucursal']);

							if($statusIVAIEPS==0){
								$iepspendientepago[0] = $prove['cuentaiepspendiente'];
								$ivapendientepago[0] = $prove['cuentaivapendiente'];
								$CuentaIEPSpagado[0] = $prove['cuentaiepscobrado'];
								$CuentaIVApagado[0] = $prove['cuentaivacobrado'];
							}

							$xmlrelacion= str_replace("Parcial-", "", $prove['xml']);

							if($prove['concepto']==""){ $prove['concepto']="Pago a Proveedores";}
							 $abono=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$idcuenta,"Cargo",number_format($prove['importe'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
							if($abono==true){
								if($unsolobanco==0){
									$UltimoMov+=1;
								  	$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Abono",number_format($prove['importe'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
								}else{
									$conceptoBancos = $prove['concepto'];
									$totalbancos+=$prove['importe'];
									$si=true;
								}
									if($si==true){ $UltimoMov+=1;

										if($statusIVA==1){
								 			if($prove['IVA']>0){
								 				$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivapendientepago[0],"Abono",number_format($prove['IVA'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												$UltimoMov+=1;
												$insertaivapagado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$CuentaIVApagado[0],"Cargo",number_format($prove['IVA'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												$UltimoMov+=1;
											}
										}
										if($statusIEPS==1){
											if($prove['IEPS']>0){//
									 			$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepspendientepago[0],"Abono",number_format($prove['IEPS'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												$UltimoMov+=1;
												$insertaivapagado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$CuentaIEPSpagado[0],"Cargo",number_format($prove['IEPS'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												$UltimoMov+=1;
											}
										}
							 		}
									 if($si==false){
							 			$error=true;
							 		}else{
							 			if($carpeta==true){

							 				$rutaOrigen = $this->path()."xmls/facturas/temporales/".$prove['xml'];
							 				if($prove['parcial']==0){
												//str_replace("Parcial-", "", $prove['xml'])
												rename($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion);
											}
											else{
												copy($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion);
												rename($rutaOrigen, $this->path()."xmls/facturas/temporales/Parcial-".$xmlrelacion);
												$this->CaptPolizasModel->facturaRename("Parcial-".$xmlrelacion);
											}
										}
							 		}
							 }else{
							 	$error=true;
							 }
						}
					}
					if($unsolobanco==1){
						$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Abono",number_format($totalbancos,2,'.',''),$conceptoBancos,'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
					}


			}//el de insertar poliza
			else{
				$error=true;
			}
  			if($error==false){
  				unset($_SESSION['proveedor']);
				unset($_SESSION['fechaprove']);
				echo '-_-1-_-';
  			}else{
  				echo '-_-2-_-';
  			}


		// }else
		// {
			// echo '-_-0-_-';
		// }
	}

	function verprovision(){
		//$valida=$this->
		$cuentaingresos=$this->CaptPolizasModel->cuentaproviciones('4.1',0);
		$cuentaegresos=$this->CaptPolizasModel->cuentaproviciones('4.2',1);
		$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
		$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$Ex = $Exercise->fetch_assoc();
		$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
		$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
		$Suc = $this->CaptPolizasModel->getSegmentoInfo();
		if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
		{
		 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			include('views/captpolizas/poliprovisional.php');
		}
		else
		{
			echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config&f=mainPage','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
		}
		//include('views/captpolizas/poliprovisional.php');

	}


	/////////////

	function ejercicioietu(){
		$idpr=$_REQUEST['IdPrv'];
		$acreditableIETU=0;
		if(!$_REQUEST['idpoli']){
			$idietu=$this->CaptPolizasModel->idIETUprv($idpr,0,"");
		}else{

		    $consul=$this->CaptPolizasModel->idIETUprv($idpr,1,$_REQUEST['idpoli']);
			$separa = explode('//',$consul);
			$idietu = $separa[0];
			$acreditableIETU = $separa[1];

		}
			$ietu=$this->CaptPolizasModel->ietu();
			$ejer=$this->CaptPolizasModel->consultaejer($_REQUEST['ejer']);
			if(intval($ejer)<2014 && intval($ejer)>2007 ){
				$cadena= $acreditableIETU."//";
				if($idietu==0){
					$cadena.= "<option value='0' selected>---</option>";
				}
					while( $listaietu = $ietu->fetch_object() ){
						if($listaietu->id===$idietu){

							$cadena.= "<option value=".$idietu." selected=selected>".$listaietu->nombre."</option>";

						}else{
							$cadena.= "<option value=".$listaietu->id.">".$listaietu->nombre."</option>";
						}
					}
					echo $cadena;
				//echo "<option value='0' selected>-Ninguno-</option>";

			}else{
				 echo 0;
			 }



	}


	function consultaext(){
		$idcuenta = $_REQUEST['idcuenta'];
		echo ($evalua = $this->CaptPolizasModel->cuentaextr($idcuenta));
	}

	function consulcambio(){
		$validafin=date('w', strtotime($_REQUEST['fecha']));
		$fecha=$_REQUEST['fecha'];
		// if($validafin==6){//valida si es sabado
			// $fecha1=strtotime('-1 days',strtotime ($fecha));
			// $fecha=date("Y-m-d", $fecha1);
		// }else if($validafin==7){//valida si es domingo si es algunos de estos entonces se recorre al viernes
			// $fecha1=strtotime('-2 days',strtotime ($fecha));
			// $fecha=date("Y-m-d", $fecha1);
		// }
		$cambio=$this->CaptPolizasModel->consulcambio($_REQUEST['idmoneda'],$fecha);
		if($cambio!='0'){
			if($t=$cambio->fetch_assoc()){
				echo ($t['tipo_cambio']);
			}
		}else{
			echo 0;
		}
	}
	function quitar_tildes($cadena) {
		$no_permitidas= array ("\n","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/","'","´",'"');
		$permitidas= array ("","a","e","i","o","u","A","E","I","O","U","n","N","A","A","I","O","U","A","A","A","A","A","A","c","C","A","e","A","A","A","A","AS","AZ","A","A","u","A","A","A","A","","O","A","A","A","","","","");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}

	function consultacuenta(){
		$cuentaprov=$this->CaptPolizasModel->consultaprovi($_REQUEST['idcuenta'],$_REQUEST['periodo'],$_REQUEST['idejer']);
		echo $cuentaprov;
	}

	function moviextranjeros2($idejer,$idpoliza,$peri){
		$option="";
		$mov=$this->CaptPolizasModel->moviextranjeros($idejer, $idpoliza);
		if($mov->num_rows>0){
			if($row2=$mov->fetch_array()){
				$sitiene = $this->CaptPolizasModel->relacionext($idpoliza);
				$cuentas=$this->CaptPolizasModel->consultaprovisiones($idejer, $row2['Cuenta'],$idpoliza,$peri,$sitiene);
				if($cuentas->num_rows>0){
					while($row=$cuentas->fetch_array()){
						if($sitiene==$row['id']){
							$option.= "<option value=".$row['id']." selected>Poliza:".$row['numpol'].",[".$row['fecha']."] Concepto:".$row['concepto']."</option>";
						}else{
							$option.= "<option value=".$row['id'].">Poliza:".$row['numpol'].",[".$row['fecha']."] Concepto:".$row['concepto']."</option>";
						}
					}
					return $option;
				}else{
					return 0;
				}
			}
		}else{ return 'no';}
	}




	function actualizaprovprovecuentas(){
		$listaprove=$this->CaptPolizasModel->cuentasprove();
		  	echo '<option selected="" value="0"> Elija una cuenta</option>';
		while ($li=$listaprove->fetch_array()){
			echo	"<option value=$li[account_id]>$li[description](".$li['manual_code'].")</option>";
		}
	}
	function actualizaprovclicuentas(){
		$listacli=$this->CaptPolizasModel->clientes1();
		  	echo '<option selected="" value="0"> Elija una cuenta</option>';
		while ($li=$listacli->fetch_array()){
			echo	"<option value=$li[account_id]>$li[description](".$li['manual_code'].")</option>";
		}
	}

	function cuentaingresosact(){
		$cuentaingresos=$this->CaptPolizasModel->cuentaproviciones('4.1',0);
		while($ingre=$cuentaingresos->fetch_array()){
			echo "<option value=$ingre[account_id]>$ingre[description](".$ingre['manual_code'].")</option>";
		}
	}
	function cuentaegresosact(){
		$cuentaegresos=$this->CaptPolizasModel->cuentaproviciones('4.2',1);
		while($egresos=$cuentaegresos->fetch_array()){
			echo "<option value=$egresos[account_id]>$egresos[description](".$egresos['manual_code'].")</option>";
		 }
	}
	function validacuenta(){
		echo $validacuenta=$this->CaptPolizasModel->validacuentaclientes($_REQUEST['cliente']);
	}
	function consultaextedicionpoli(){
		$idcuenta = $_REQUEST['idcuenta'];
		$poli = $_REQUEST['poli'];

		echo ($evalua = $this->CaptPolizasModel->edicionpolizaext($idcuenta,$poli));
	}
	//ya quedo cargando el xml falta que ago lo mismo elijiendo el xml
	function guardanewvalores(){

		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['parcial']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaivapendiente']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaivacobrado']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaiepspendiente']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaiepscobrado']=0;

		if($_REQUEST['ivapendiente']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaivapendiente']=$_REQUEST['ivapendiente'];
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaivacobrado']=$_REQUEST['ivacobrado'];
		}
		if($_REQUEST['iepspendiente']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaiepspendiente']=$_REQUEST['iepspendiente'];
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['cuentaiepscobrado']=$_REQUEST['iepscobro'];
		}
		if($_REQUEST['imporinput']>0){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['parcial']=1;//parcial 1 pagototal 0
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['importe']=$_REQUEST['imporinput'];
		}
		if($_REQUEST['ivacobradoinput']>0){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['IVA']=$_REQUEST['ivacobradoinput'];

		}
		if($_REQUEST['ipendienteinput']>0){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['idclien']]['IEPS']=$_REQUEST['ipendienteinput'];
		}
	}
function guardanewvaloresprovision(){

		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaivapendiente']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaiepspendiente']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaish']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaisr']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaiva']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['CuentaClientes']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['CuentaProveedores']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['segmento']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['sucursal']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentacompraventa']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['concepto']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['hayProrrateo']=0;
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['prorrateo']=0;

		if($_REQUEST['ivapendiente']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaivapendiente']=$_REQUEST['ivapendiente'];
		}
		if($_REQUEST['iepspendiente']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaiepspendiente']=$_REQUEST['iepspendiente'];
		}
		if($_REQUEST['isr']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaisr']=$_REQUEST['isr'];
		}
		if($_REQUEST['iva']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaiva']=$_REQUEST['iva'];

		}
		if($_REQUEST['ish']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentaish']=$_REQUEST['ish'];
		}
		if($_REQUEST['CuentaClientes']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['CuentaClientes']=$_REQUEST['CuentaClientes'];
		}
		if($_REQUEST['CuentaProveedores']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['CuentaProveedores']=$_REQUEST['CuentaProveedores'];
		}
		if($_REQUEST['segmento']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['segmento']=$_REQUEST['segmento'];
		}
		if($_REQUEST['sucursal']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['sucursal']=$_REQUEST['sucursal'];
		}
		if($_REQUEST['cuentacompraventa']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentacompraventa']=$_REQUEST['cuentacompraventa'];
		}
		if($_REQUEST['concepto']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['concepto']=$_REQUEST['concepto'];
		}
		if($_REQUEST['hayProrrateo']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['hayProrrateo']=$_REQUEST['hayProrrateo'];
		}
		if($_REQUEST['prorrateo']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['prorrateo']=$_REQUEST['prorrateo'];
		}



	}
function detalleMultipleProvision(){
	if($_REQUEST['cuentadetalle']){
		$_SESSION[$_REQUEST['array']][$_REQUEST['cont']][$_REQUEST['tipo']]['cuentadetalle'][]=$_REQUEST['cuentadetalle'];
	}
}
	function bancosprove(){
		$bancos=$this->CaptPolizasModel->buscabancos($_REQUEST['idprove']);
		if($bancos){
			echo "-_-";
			while($lista=$bancos->fetch_assoc()){
				echo "<option value=".$lista['idbanco']."/".$lista['id'].">".$lista['nombre']."</option>";
			}
			echo "-_-";
		}else{
			$listabancos = $this->CaptPolizasModel->listabancos();
			echo "-_-*";
			while($b=$listabancos->fetch_array()){
				echo "<option value=".$b['idbanco']."/0".">".$b['nombre']."(".$b['Clave'].") </option>";
			 }
			echo "-_-*";

		}
	}

	function numcuenta(){
		$idbnPrv = explode('/', $_REQUEST['banco']);
		echo $numcuenta = $this->CaptPolizasModel->numbancos($idbnPrv[1]);
	}
	function datosprove(){
		$rfc = $this->CaptPolizasModel->rfc($_REQUEST['idprove']);
		if($rfc->num_rows>0){
			if($es=$rfc->fetch_assoc()){
					echo $es['rfc'];
			}
		}else{ echo  0; }
	}
	function validaxml(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
  		global $xp;
				$file 	= $_FILES['xml']['tmp_name'];
				$texto 	= file_get_contents($file);
				//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
				//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
				$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
                $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
				$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '', $texto);
                $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '', $texto);
				$xml 	= new DOMDocument();
				$xml->loadXML($texto);

				$xp = new DOMXpath($xml);
				$data['uuid'] 	= $this->getpath("//@UUID");
				$data['folio'] 	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");

				$version = $data['version'];

				$data['total'] = $this->getpath("//@total");
				$rfc = $this->getpath("//@rfc");
				$data['rfc'] = utf8_decode($rfc[0]);
				$data['rfc_receptor'] = utf8_decode($rfc[1]);

				if($this->valida_xsd($version[0],$xml) && $_FILES['xml']['type'] == "text/xml")
				{
					if($version[0] == '3.2')
					{
						$resp =  $this->valida_en_sat($data['rfc'],$data['rfc_receptor'],$data['total'],$data['uuid']);
						echo "-_-".$resp."-_-";
					}
					else
					{
						echo "-_-1-_-";
					}
				}


		}else{
		    throw new Exception("Error en el proceso :P ", 1);
		}


	}
	function  actulizabenifi(){
		$beneficiario=$this->CaptPolizasModel->proveedorparacaptura();
		while($b=$beneficiario->fetch_array()){
			echo "<option value=".$b['idPrv']." >".($b['razon_social'])."</option>";
		}
	}
	function consultarelaciones(){
		$idpoliza = $_REQUEST['idpoliza'];
		if($_REQUEST['tipopoliza'] == 1){
			echo $desglose = $this->CaptPolizasModel->consultaprove($idpoliza,$_REQUEST['opc']);
		}
		else {
			echo $prove = $this->CaptPolizasModel->consultadesglose($idpoliza,$_REQUEST['opc']);
		}

	}

	function UltimoNumPol()
	{
		echo $this->CaptPolizasModel->UltimoNumPol($_POST['Periodo'],$_POST['Ejercicio'],$_POST['TipoPol'])+1;
	}

	function ExisteNumPol()
	{
		echo $this->CaptPolizasModel->ExisteNumPol($_POST['Periodo'],$_POST['Ejercicio'],$_POST['TipoPol'],$_POST['NumPol'],$_POST['Id']);
	}

	function CambioEjerciciosession(){
		// $_SESSION['periodo'] = $_REQUEST['Periodo'];
		// $_SESSION['ejercicio']=$_REQUEST['NameEjercicio'];
		// $_SESSION['idejercicio']=100;
		setcookie('periodo',$_REQUEST['Periodo']);
		setcookie('ejercicio',$_REQUEST['NameEjercicio']);
		setcookie('idejercicio',100);
	}
	function ejercicioactual(){
		// unset($_COOKIE['periodo']);
		// unset($_COOKIE['ejercicio']);
		// unset($_COOKIE['idejercicio']);
		setcookie('periodo', '', time() - 1000);
		setcookie('ejercicio', '', time() - 1000);
		setcookie('idejercicio', '', time() - 1000);
	}

	function consultaultima(){
		$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
		echo $numPoliza['id'];
	}
	function verificasaldado(){
		$idpoliza=$this->CaptPolizasModel->relacionext($_REQUEST['idpoliza']);//id dela relacion ext
		echo $saldado =$this->CaptPolizasModel->verificasaldado($idpoliza);
	}
	function cuentasbancarias(){
		$cuentasb=$this->CaptPolizasModel->cuentasbancarias($_REQUEST['cuentacontable']);
		$opt="";
		if($cuentasb->num_rows>0){
			while($row = $cuentasb->fetch_array()){
				$opt .= "<option value=".$row['idbancaria'].">".$row['nombre']."</option>";
				$opt .= "->".$row['cuenta']."->";
			}
			echo '-_-'.$opt.'-_-';
		}else{
			echo '-_-0-_-';
		}
	}
	function nuemrocuenta(){
		$numero = $this->CaptPolizasModel->infobancariaid($_REQUEST['banco']);
		echo $numero['cuenta'];
	}
	function actcuentasbancarias(){
		$contenido="";
		$listacuentasbancarias = $this->CaptPolizasModel->cuentasbancariaslista();
		while($b=$listacuentasbancarias->fetch_array()){
			$contenido.= "<option value=".$b['idbancaria']." >".$b['nombre']." (".$b['description']."[".$b['manual_code']."])"."</option>";
		}
		 echo '-_-'.$contenido.'-_-';
	}

	// A N T I C I P O  G A S T O S //
	function anticipo(){//mamy
		//$numPoliza 			=	 $this->CaptPolizasModel->getLastNumPoliza();
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$Ex = $Exercise->fetch_assoc();
		$_SESSION['anticipo']=1;
		if(isset($_COOKIE['ejercicio']) AND isset($_COOKIE['periodo']))
		{
			$Ex['EjercicioActual'] = $_COOKIE['ejercicio'];
			$Ex['IdEx'] = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
			$Ex['PeriodoActual'] = $_COOKIE['periodo'];
		}
		if($Ex['PeriodoActual'] != 13)
		{
			$this->CaptPolizasModel->savePoliza($Ex['IdOrganizacion'],$Ex['IdEx'],$Ex['PeriodoActual'],1,2);
			echo "<script>window.location = 'index.php?c=CaptPolizas&f=Capturar'</script>";
		}
		elseif($this->CaptPolizasModel->CuentaSaldosConfigurado() != -1)
		{
			$this->CaptPolizasModel->savePoliza($Ex['IdOrganizacion'],$Ex['Ejercicio'],$Ex['Periodo'],1,2);
			echo "<script>window.location = 'index.php?c=CaptPolizas&f=Capturar'</script>";
		}
		else
		{
			echo "Para generar la poliza del periodo 13 es necesario configurar la cuenta de saldos en la pantalla de Asignación de Cuentas.";
		}
	}

	function provisionmultiple(){
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$Ex = $Exercise->fetch_assoc();
		$todas_facturas = $Ex['TodasFacturas'];
		$cuentaingresos=$this->CaptPolizasModel->cuentaproviciones('4.1',0);
		$cuentaegresos=$this->CaptPolizasModel->cuentaproviciones('4.2',1);
		$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
		$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
		$Suc = $this->CaptPolizasModel->getSegmentoInfo();
		$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
		$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
		$cuentaivas = $this->CaptPolizasModel->cuentaivas();
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		$cuentalista = $this->CaptPolizasModel->clientes1();
		$cuentaprov = $this->CaptPolizasModel->cuentasprove();

		if($row=$cuentas->fetch_array()){
			$iepspendientecobro = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$iepspendientepago  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientecobro  = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$ivapendientepago   = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$ishh = $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$ivaretenido = $this->CaptPolizasModel->buscacuenta($row['IVAretenido']);
			$isrretenido = $this->CaptPolizasModel->buscacuenta($row['ISRretenido']);
			$statusIVAIEPS = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS = $row['statusIEPS'];
			$statusIVA = $row['statusIVA'];
			$CuentaIEPSgasto = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);

		}
		$iepspendientecobro = explode("//", $iepspendientecobro);
		$ivapendientecobro = explode("//", $ivapendientecobro);
		$iepspendientepago = explode("//", $iepspendientepago);
		$ivapendientepago = explode("//", $ivapendientepago);
		$ishh = explode("//", $ishh);
		$ivaretenido = explode("//", $ivaretenido);
		$isrretenido = explode("//", $isrretenido);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);
		$CuentaIVAgasto  = explode("//",$CuentaIVAgasto);

		if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
		{
		 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			if($_REQUEST['detalle']){
				include('views/captpolizas/provisionmultipledetallado.php');
			}else{
				include('views/captpolizas/provisionmultiple.php');
			}
		}
		else
		{
			echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config&f=mainPage','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
		}
			//include('views/captpolizas/poliprovisional.php');

	}
function guardaProvisionMultiple(){
	$error=false;
	global $xp;
	$comprobante = $_REQUEST['comprobante'];
	$facturasNoValidas = $facturasValidas = '';
	$numeroInvalidos = $numeroValidos = $no_hay_problema = $noOrganizacion = 0;
	$maximo = count($_FILES['xml']['name']);
	$maximo = (intval($maximo)-1);
	for($i = 0; $i <= $maximo; $i++){
		if($_FILES["xml"]["size"][$i] > 0){
	$retencion=array();$agregaprove = array(); $previo = array();
			$file 	= $_FILES['xml']['tmp_name'][$i];
			$texto 	= file_get_contents($file);
			//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
			$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '', $texto);
            $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '', $texto);

			$xml 	= new DOMDocument();
			$xml->loadXML($texto);

			$xp = new DOMXpath($xml);
			// $data['uuid'] 	= $this->getpath("//@UUID");
			// $data['folio'] 	= $this->getpath("//@folio");
			// $data['emisor'] = $this->getpath("//@nombre");
				if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");
			$version = $data['version'];
			
			if($version[0] == '3.3')
			{
				
				$data['rfc'] = $this->getpath("//@Rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@Fecha");
				$total = $this->getpath("//@Total");
				$subTotal = $this->getpath("//@SubTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				if(is_array($data['uuid'])){
					$data['uuid'] 	= $data['uuid'][1];
				}
				$folio 	= $this->getpath("//@Folio");
				$data['emisor'] = $this->getpath("//@Nombre");
				$descuento = $this->getpath("//@Descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
				}
			}else{
				$data['rfc'] = $this->getpath("//@rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@fecha");
			    $total=$this->getpath("//@total");
			    $subTotal= $this->getpath("//@subTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$folio 	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				
				$descuento = $this->getpath("//@descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
					//$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				}
			}
			
			// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
			    // $fecha= $cfdiComprobante['fecha'];
			    // $total=($cfdiComprobante['total']);
			    // $subTotal= $cfdiComprobante['subTotal'];
				// if($cfdiComprobante['descuento']){
					// $subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
					// //$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				// }
			// }
			
			
			
			
			//$rfc = $this->getpath("//@rfc");
			
			//$data['rfc_receptor'] = utf8_decode($rfc[1]);
			$rfcOrganizacion= $this->CaptPolizasModel->rfcOrganizacion();

			if($data['rfc'][0] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Ingreso";$nombre = $data['emisor'][1];
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Egreso";	$nombre = $data['emisor'][0];

				}

			//Termina obtener UUID---------------------------

			if($this->valida_xsd($version[0],$xml) && $_FILES['xml']['type'][$i] == "text/xml")
			{

				if($version[0] == '3.2'){
					$no_hay_problema = $this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']);
				}
				else{
					$no_hay_problema = 1;
				}
				if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1]){
					$noOrganizacion = 0;
					$numeroInvalidos++;
					$facturasNoValidas .= $_FILES['xml']['name'][$i]."(RFC no de Organizacion),\\n";
				}else{ $noOrganizacion = 1; }

				$nombreArchivo = $folio."_".$nombre."_".$data['uuid'].".xml";
				if($noOrganizacion){
					$validaexiste = $this->existeXML($nombreArchivo);
					if($validaexiste){
						$noOrganizacion = 0;
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['xml']['name'][$i].",Ya existe en $validaexiste.\\n";
					}else{ $noOrganizacion = 1; }
				}
				if($noOrganizacion){
					if($no_hay_problema)
					{


						$xml = simplexml_load_file($_FILES['xml']['tmp_name'][$i]);
						$ns = $xml -> getNamespaces(true);
						$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
						$xml -> registerXPathNamespace('t', $ns['tfd']);
				 		// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
						    // $fecha= $cfdiComprobante['fecha'];
						    // $total=($cfdiComprobante['total']);
						    // $subTotal= $cfdiComprobante['subTotal'];
							// if($cfdiComprobante['descuento']){
								// $subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
								// //$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
							// }
						// }
						$fec = explode("T", $fecha);

							if($_REQUEST['fecha']){
								$_SESSION['fechaprovi']=$_REQUEST['fecha'];
							}else{
								$_SESSION['fechaprovi']=$fec[0]."";
							}

						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
						   	if($version[0] == '3.3'){
						   		$rfcemisor= $Emisor['Rfc'];
						   		$nombreemisor= utf8_decode($Emisor['Nombre']);
						   		$nombreemisor2= ($Emisor['Nombre']);
						   	}else{
						   		$rfcemisor= $Emisor['rfc'];
						   		$nombreemisor= utf8_decode($Emisor['nombre']);
						   		$nombreemisor2= ($Emisor['nombre']);
							}
						   $agregaprove[]=$nombreemisor.""; $agregaprove[]=$rfcemisor."";
						}
						if($version[0] != '3.3'){
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
								
										
								   $paisemisor= $DomicilioFiscal['pais'];
								   $calleemisor= $DomicilioFiscal['calle'];
								   $estadoemisor= $DomicilioFiscal['estado'];
								   $coloniaemisor= $DomicilioFiscal['colonia'];
								   $municipioemisor= $DomicilioFiscal['municipio'];
								   $noExterioremisor= $DomicilioFiscal['noExterior'];
								   $codigoPostalemisor= $DomicilioFiscal['codigoPostal'];
								  
							  	
								 $agregaprove[]=$calleemisor." ".$noExterioremisor;
								 $agregaprove[]=$municipioemisor."";
							}
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
							if($version[0] == '3.3'){
								$rfcreceptor= $Receptor['Rfc'];
						  	 	$nombrereceptor=utf8_decode($Receptor['Nombre']);
							}else{
								$rfcreceptor= $Receptor['rfc'];
						  	 	$nombrereceptor=utf8_decode($Receptor['nombre']);
							}
						   
						}
						$receptorcliente = array();
						$receptorcliente[]=$nombrereceptor."";$receptorcliente[] = $rfcreceptor."";
						if($version[0] != '3.3'){
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){
							   $callerecep= $ReceptorDomicilio['calle'];
							   $estadorecep= $ReceptorDomicilio['estado'];
							   $coloniarecep= $ReceptorDomicilio['colonia'];
							   $municipiorecep= $ReceptorDomicilio['municipio'];
							   $noExteriorrecep= $ReceptorDomicilio['noExterior'];
							   $noInteriorrecep= $ReceptorDomicilio['noInterior'];
							   $codigoPostalrecep= $ReceptorDomicilio['codigoPostal'];
							   
							   $receptorcliente[] = $callerecep." ".$noExteriorrecep;
							   	$receptorcliente[] = $coloniarecep."";
							   	$receptorcliente[] = $codigoPostalrecep."";
							   	$receptorcliente[] = $municipiorecep."";
							}
						}
						
				//($nombrereceptor, $callerecep." ".$noExteriorrecep, $coloniarecep, $codigoPostalrecep, $idestado, $idmunicipio, $rfcreceptor,$idcuentacliente);

						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){
						   $tasa=$Traslado['tasa'];
							if($version[0] == '3.3'){
					  			$Traslado['importe']		= $Traslado['Importe'];
								$Traslado['impuesto'] 	= $this->CaptPolizasModel->nombreImpuestoIndividual($Traslado['Impuesto']);
							}
						  if($Traslado['impuesto']=="IVA"){
						  		
						  		if($Traslado['importe']>0){
						  				if($comprobante==1 || $comprobante==3){
											$previo['cliente']['abono2']=floatval($Traslado['importe']);
										}
										if($comprobante==2 || $comprobante==4){
							  				$previo['proveedor']['cargo2']=floatval($Traslado['importe']);
										}
									}
								}
								if($Traslado['impuesto']=="IEPS"){
									if($Traslado['importe']>0){
										if($comprobante==1 || $comprobante==3){
											$previo['cliente']['ieps']=floatval($Traslado['importe']);
										}
										if($comprobante==2 || $comprobante==4){
											$previo['proveedor']['ieps']=floatval($Traslado['importe']);
										}
									}
								}
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Traslad){
							if($version[0] == '3.3'){
								$importe=$Traslad['TotalImpuestosTrasladados'];
							}else{
								$importe=$Traslad['totalImpuestosTrasladados'];
							}
						}
						foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
				  			$UUID= $tfd['UUID'];
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $retenido){
							if($version[0] == '3.3'){
					  			$retenido['impuesto']	= $this->CaptPolizasModel->nombreImpuestoIndividual($retenido['Impuesto']);
								$retenido['importe'] 	= $retenido['Importe'];
							}
							$retencion["$retenido[impuesto]"]= number_format(floatval($retenido['importe']),2,'.','');

						}
						// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
								 // $folio=$cfdiComprobante['folio'];
						// }
						$conceptosdetalle=array();$precioimporte=array();
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){
							if($version[0] == '3.3'){
								$concepto['descripcion']= $concepto['Descripcion'];
								$concepto['importe']		= $concepto['Importe'];
							}
							$concepto['descripcion'] = str_replace("'", "", $concepto['descripcion']);
							$concepto['descripcion'] = str_replace("\"", "", $concepto['descripcion']);

							$conceptosdetalle[] = $concepto['descripcion']."";
							$precioimporte[] = $concepto['importe']."";
							$concepto = $concepto['descripcion']."";

						}
						if(!$ishimport){
							$ishimport=0;
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//implocal:ImpuestosLocales xmlns:implocal"//implocal:TrasladosLocales') as $ish){
						 		$ishimport=$ish['Importe'];
							}
						}
						$xmlreal = $this->quitar_tildes($folio."_".$nombre."_".$UUID.".xml");
						if($comprobante==1){
							$xmld=$this->quitar_tildes($folio."-Cobro"."_".$nombre."_".$UUID.".xml");
						}
						if($comprobante==2){
							$xmld=$this->quitar_tildes($folio."-Pago"."_".$nombre."_".$UUID.".xml");
						}
						if($comprobante==3 || $comprobante==4){
							$xmld=$this->quitar_tildes($folio."_".$nombre."_".$UUID.".xml");
						}
					if(move_uploaded_file($_FILES['xml']['tmp_name'][$i],$this->path().'xmls/facturas/temporales/'.($xmlreal))){
						$_SESSION['comprobante']=$comprobante;
					if($comprobante==1 || $comprobante==3){
							$previo['cliente']['agregacliente'] = $receptorcliente;
							$previo['cliente']['retenidos']=$retencion;
							$previo['cliente']['nombre']=utf8_encode($nombrereceptor);
							$previo['cliente']['abono']=number_format(floatval($subTotal),2,'.','');
							$previo['cliente']['concepto']=$concepto."";
							$previo['cliente']['cargo']=number_format(floatval($total),2,'.','');
							$previo['cliente']['xml']=($xmld);
							$previo['cliente']['xmlreal']=($xmlreal);
							$previo['cliente']['referencia']=$UUID."";
							$previo['cliente']['ish']=number_format(floatval($ishimport),2,'.','');
							$previo['cliente']['conceptodetalle']= $conceptosdetalle;
							$previo['cliente']['preciodetalle']= $precioimporte;
							$consultacliente=$this->CaptPolizasModel->consultacliente($rfcreceptor);
							$previo['cliente']['ordenador']=1;
							if($consultacliente->num_rows>0){
									if( $re = $consultacliente->fetch_array() ){
										if( $re['cuenta'] <= 0 ){//sino tienen cuenta
											$previo['cliente']['listacliente'] =0;

										}
									}
								}else{
									$previo['cliente']['listacliente'] =0;
								}
							$_SESSION['provisioncliente'][]=$previo;
					 }else if($comprobante==2 || $comprobante==4){
					 		$previo['proveedor']['agregaprovee'] = $agregaprove;
							$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');;
							$previo["proveedor"]['retenidos']=$retencion;
							$previo["proveedor"]['nombre']=utf8_encode($nombreemisor);
							$previo["proveedor"]['concepto']=$concepto."";
							$previo["proveedor"]['cargo']=number_format(floatval($subTotal),2,'.','');;
							$previo["proveedor"]['abono']=number_format(floatval($total),2,'.','');
							$previo["proveedor"]['xml']=($xmld);
							$previo["proveedor"]['xmlreal']=($xmlreal);
							$previo["proveedor"]['referencia']=$UUID."";
				 			$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');
							$previo['proveedor']['conceptodetalle']= $conceptosdetalle;
							$previo['proveedor']['preciodetalle']= $precioimporte;
							$previo['proveedor']['ordenador']=1;
							$emisor=$this->CaptPolizasModel->emisorprove($rfcemisor,$nombreemisor);
							if($emisor->num_rows>0){
								if( $re = $emisor->fetch_array() ){
								  if( $re['cuenta'] == " " || $re['cuenta'] == 0 || $re['cuenta'] == -1){//sino tienen cuenta
								  	$previo['proveedor']['listaprove'] = 0;
								  }
								}
							}else{
								$previo['proveedor']['listaprove'] = 0;
							}
							//PROVAR CON RETENCIONES EL PROVEEDORES
						 $_SESSION['poliprove'][]=$previo;
						}//comprobante 2
					}else{//else de si mueve el archivo
						$errorSubir=true;
						$this->CaptPolizasModel->transaccionController('Error al subir archivo Provision Multiple','' );
					}
						//move_uploaded_file($_FILES['xml']['tmp_name'][$i],'xmls/facturas/temporales/'.($xmld));

						$numeroValidos++;
						//$facturasValidas .= $_FILES['xml']['name'][$i].",\n";
					}
					else{
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['xml']['name'][$i]."(Cancelada),\\n";
					}
				}

			}
			else{
				$numeroInvalidos++;
				$facturasNoValidas .= $_FILES['xml']['name'][$i]."(Estructura invalida),\\n";
			}
		}
	}
		$cadena = "";
		if($errorSubir){
			$cadena = "alert('Error al subir factura intente de nuevo');";

		}else{
			if ($numeroInvalidos!=0){
				$cadena = 'alert("Facturas no validas:\n'.$facturasNoValidas.'PARA AGREGAR FACTURAS EXISTENTES REALIZARLO DESDE ALMACEN");';
			}else{
				$cadena = "alert('Facturas validas');";
			}
		}
		echo '<script>'.$cadena.' window.location="index.php?c=CaptPolizas&f=provisionmultiple&detalle='.$_REQUEST['detalle'].'"; </script>';
	}
	function guardaProvisionMultipleAlmacen(){
		$error=false;
		$comprobante = $_REQUEST['comprobante'];
		$maximo = count($_POST['xml']);
		$maximo = (intval($maximo)-1);
		for($i=0;$i<=$maximo;$i++){
			global $xp;
			$previo = array();$retencion=array(); $agregaprove = array();
			$archivo = $_POST['xml'][$i];
			$texto 	= file_get_contents($archivo);
			//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);

			$xmld 	= new DOMDocument();
			$xmld->loadXML($texto);
			$xp = new DOMXpath($xmld);
			
			if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");
			$version = $data['version'];
			if($version[0] == '3.3')
			{
				
				$data['rfc'] = $this->getpath("//@Rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@Fecha");
				$total = $this->getpath("//@Total");
				$subTotal = $this->getpath("//@SubTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				if(is_array($data['uuid'])){
					$data['uuid'] 	= $data['uuid'][1];
				}
				$folio 	= $this->getpath("//@Folio");
				$data['emisor'] = $this->getpath("//@Nombre");
				$descuento = $this->getpath("//@Descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
				}
			}else{
				$data['rfc'] = $this->getpath("//@rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@fecha");
			    $total=$this->getpath("//@total");
			    $subTotal= $this->getpath("//@subTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$folio 	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				
				$descuento = $this->getpath("//@descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
					//$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				}
			}

			$xml = simplexml_load_file($_POST['xml'][$i]);
			$ns = $xml -> getNamespaces(true);
			$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
			$xml -> registerXPathNamespace('t', $ns['tfd']);
	 		// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
			    // $fecha= $cfdiComprobante['fecha'];
			    // $total=($cfdiComprobante['total']);
			    // $subTotal= $cfdiComprobante['subTotal'];
				// if($cfdiComprobante['descuento']){
					// $subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
					// //$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				// }
			// }


			$fec = explode("T", $fecha);

			if($_REQUEST['fecha']){
					$_SESSION['fechaprovi']=$_REQUEST['fecha'];
				}else{
					$_SESSION['fechaprovi']=$fec[0]."";
				}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
				if($version[0] == '3.3'){
					$rfcemisor= $Emisor['Rfc'];
			   		$nombreemisor= utf8_decode($Emisor['Nombre']);
				}else{
			   		$rfcemisor= $Emisor['rfc'];
			  		$nombreemisor= utf8_decode($Emisor['nombre']);
				}
			   $agregaprove[]=$nombreemisor.""; $agregaprove[]=$rfcemisor."";
			}
			if($version[0] != '3.3'){
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
				
					
					   $paisemisor= $DomicilioFiscal['pais'];
					   $calleemisor= $DomicilioFiscal['calle'];
					   $estadoemisor= $DomicilioFiscal['estado'];
					   $coloniaemisor= $DomicilioFiscal['colonia'];
					   $municipioemisor= $DomicilioFiscal['municipio'];
					   $noExterioremisor= $DomicilioFiscal['noExterior'];
					   $codigoPostalemisor= $DomicilioFiscal['codigoPostal'];
					
				   $agregaprove[]=$calleemisor." ".$noExterioremisor;
				   $agregaprove[]=$municipioemisor."";
				}
			}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
				if($version[0] == '3.3'){
					$rfcreceptor= $Receptor['Rfc'];
			  	 	$nombrereceptor=utf8_decode($Receptor['Nombre']);
				}else{
			   		$rfcreceptor= $Receptor['rfc'];
			   		$nombrereceptor=utf8_decode($Receptor['nombre']);
				}
			}
			$receptorcliente = array();
			$receptorcliente[]=$nombrereceptor."";$receptorcliente[] = $rfcreceptor."";
			if($version[0] != '3.3'){
				foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){
				   	
				   $callerecep= $ReceptorDomicilio['calle'];
				   $estadorecep= $ReceptorDomicilio['estado'];
				   $coloniarecep= $ReceptorDomicilio['colonia'];
				   $municipiorecep= $ReceptorDomicilio['municipio'];
				   $noExteriorrecep= $ReceptorDomicilio['noExterior'];
				   $noInteriorrecep= $ReceptorDomicilio['noInterior'];
				   $codigoPostalrecep= $ReceptorDomicilio['codigoPostal'];
				
				   $receptorcliente[] = $callerecep." ".$noExteriorrecep;
				   $receptorcliente[] = $coloniarecep."";
				   $receptorcliente[] = $codigoPostalrecep."";
				   $receptorcliente[] = $municipiorecep."";
				}
			}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){
			   $tasa=$Traslado['tasa'];
			   if($version[0] == '3.3'){
			   		$Traslado['importe'] = $Traslado['Importe'];
				   $Traslado['impuesto'] =$this->CaptPolizasModel->nombreImpuestoIndividual( $Traslado['Impuesto']);
			   }
			  if($Traslado['impuesto'] == "IVA"){
			  			if($Traslado['importe']>0){
			  				if($comprobante==1 || $comprobante==3){
								$previo['cliente']['abono2']=floatval($Traslado['importe']);
							}
							if($comprobante==2 || $comprobante==4){
				  				$previo['proveedor']['cargo2']=floatval($Traslado['importe']);
							}
						}
					}
					if($Traslado['impuesto'] == "IEPS"){
						if($Traslado['importe']>0){
							if($comprobante==1 || $comprobante==3){
								$previo['cliente']['ieps']=floatval($Traslado['importe']);
							}
							if($comprobante==2 || $comprobante==4){
								$previo['proveedor']['ieps']=floatval($Traslado['importe']);
							}
						}
					}
			}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Traslad){
				if($version[0] == '3.3'){
					$importe=$Traslad['TotalImpuestosTrasladados'];
				}else{
					$importe=$Traslad['totalImpuestosTrasladados'];
				}
			}
			foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
	  			$UUID= $tfd['UUID'];
			}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $retenido){
				if($version[0] == '3.3'){
					$retenido["impuesto"] = $this->CaptPolizasModel->nombreImpuestoIndividual($retenido["Impuesto"]);
					$retenido['importe']	  = $retenido['Importe'];
				}
				$retencion["$retenido[impuesto]"]= number_format(floatval($retenido['importe']),2,'.','');

			}

			// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
					 // $folio=$cfdiComprobante['folio'];
			// }
			$conceptosdetalle=array();$precioimporte=array();
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){
				if($version[0] == '3.3'){
					$concepto['descripcion']	= $concepto['Descripcion'];
					$concepto['importe']		= $concepto['Importe'];			
				}
				$concepto['descripcion'] = str_replace("'", "", $concepto['descripcion']);
				$concepto['descripcion'] = str_replace("\"", "", $concepto['descripcion']);

				$conceptosdetalle[] = $concepto['descripcion']."";
				$precioimporte[] = $concepto['importe']."";

				$concepto = $concepto['descripcion']."";

			}
			if(!$ishimport){
				$ishimport=0;
				foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//implocal:ImpuestosLocales//implocal:TrasladosLocales') as $ish){
			 		$ishimport=$ish['Importe'];
				}
			}
			$archivopar = explode("/",$_POST['xml'][$i]);
			$xmlreal = $archivopar[3];
			$archivopar[3] = str_replace("-Cobro", "", $archivopar[3]);
			$archivopar[3] = str_replace("-Pago", "", $archivopar[3]);
			$archivopar[3] = str_replace("-Nomina", "", $archivopar[3]);

			$separa = explode("_",$archivopar[3]);

			if($comprobante==1){
				$xmld=$this->quitar_tildes($separa[0]."-Cobro_".$separa[1]."_".$separa[2]);
			}
			if($comprobante==2){
				$xmld=$this->quitar_tildes($separa[0]."-Pago_".$separa[1]."_".$separa[2]);
			}
			if($comprobante==3 || $comprobante==4){
				$xmld=$this->quitar_tildes($archivopar[3]);
			}

			$_SESSION['comprobante']=$comprobante;

			if($comprobante==1 || $comprobante==3){
				$previo['cliente']['agregacliente'] = $receptorcliente;
				$previo['cliente']['retenidos']=$retencion;
				$previo['cliente']['nombre']=utf8_encode($nombrereceptor);
				$previo['cliente']['abono']=number_format(floatval($subTotal),2,'.','');
				$previo['cliente']['concepto']=$concepto."";
				$previo['cliente']['cargo']=number_format(floatval($total),2,'.','');
				$previo['cliente']['xml']=($xmld);
				$previo['cliente']['xmlreal']=($xmlreal);
				$previo['cliente']['referencia']=$UUID."";
				$previo['cliente']['ish']=number_format(floatval($ishimport),2,'.','');
				$previo['cliente']['conceptodetalle']= $conceptosdetalle;
				$previo['cliente']['preciodetalle']= $precioimporte;
				$consultacliente=$this->CaptPolizasModel->consultacliente($rfcreceptor);
				if($consultacliente->num_rows>0){
					if( $re = $consultacliente->fetch_array() ){
						if( $re['cuenta'] <= 0 ){//sino tienen cuenta
							$previo['cliente']['listacliente'] =0;

						}
					}
				}else{
					$previo['cliente']['listacliente'] =0;
				}
			$_SESSION['provisioncliente'][]=$previo;
		 }else if($comprobante==2 || $comprobante==4){
		 		$previo['proveedor']['agregaprovee'] = $agregaprove;
				$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');;
				$previo["proveedor"]['retenidos']=$retencion;
				$previo["proveedor"]['nombre']=utf8_encode($nombreemisor);
				$previo["proveedor"]['concepto']=$concepto."";
				$previo["proveedor"]['cargo']=number_format(floatval($subTotal),2,'.','');;
				$previo["proveedor"]['abono']=number_format(floatval($total),2,'.','');
				$previo["proveedor"]['xml']=($xmld);
				$previo["proveedor"]['xmlreal']=($xmlreal);
				$previo["proveedor"]['referencia']=$UUID."";
	 			$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');
				$previo['proveedor']['conceptodetalle']= $conceptosdetalle;
				$previo['proveedor']['preciodetalle']= $precioimporte;
				$emisor=$this->CaptPolizasModel->emisorprove($rfcemisor,$nombreemisor);
				if($emisor->num_rows>0){
					if( $re = $emisor->fetch_array() ){
					  if( $re['cuenta'] == " " || $re['cuenta'] == 0 || $re['cuenta'] == -1){//sino tienen cuenta
					  	$previo['proveedor']['listaprove'] = 0;
					  }
					}
				}else{
					$previo['proveedor']['listaprove'] = 0;
				}
			 $_SESSION['poliprove'][]=$previo;
			}//comprobante 2
		//	rename($_POST['xml'][$i],'xmls/facturas/temporales/'.($xmlreal));
	}
}
	function cancela(){
		 if($_SESSION['comprobante']==1 || $_SESSION['comprobante']==3){
		 	foreach($_SESSION['provisioncliente'] as $cli){
		 		foreach($cli as $cliente){
		 			if($cliente['ordenador']){
		 				unlink($this->path()."xmls/facturas/temporales/".$cliente['xml']);
		 			}
				}
		 	}
		 }if($_SESSION['comprobante']==2 || $_SESSION['comprobante']==4){
		 	foreach($_SESSION['poliprove'] as $cli){
		 		foreach($cli as $cliente){
		 			if($cliente['ordenador']){
		 				unlink($this->path()."xmls/facturas/temporales/".$cliente['xml']);
					}
				}
		 	}
		 }
		unset($_SESSION['comprobante']);
		unset($_SESSION['provisioncliente']);
		unset($_SESSION['poliprove']);
		unset($_SESSION['fechaprovi']);
		unset($_SESSION['datosext']);

	}
	function cancelaComprobacion(){
	 	foreach($_SESSION['compruebagasto'] as $cli){
	 		foreach($cli as $cliente){
	 			if($cliente['ordenador']){
	 				unlink($this->path()."xmls/facturas/temporales/".$cliente['xml']);
				}
			}
	 	}
		unset($_SESSION['compruebagasto']);
		unset($_SESSION['deudornombre']);
		unset($_SESSION['fechaanticipo']);
		unset($_SESSION['usercompro']);
		unset($_SESSION["lista"]);
	}
	function guardaprovimultiple(){
		$error=false;
		$fecha = $_REQUEST['fecha'];
		$carpeta=false;
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientecobro 	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$iepspendientepago  	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientecobro  	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$ivapendientepago   	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$ish				    = $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$ivaretenido 		= $this->CaptPolizasModel->buscacuenta($row['IVAretenido']);
			$isrretenido			= $this->CaptPolizasModel->buscacuenta($row['ISRretenido']);
			$statusIVAIEPS      	= $row['statusIVAIEPS'];
			$statusRetencionISH 	= $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);

		}

			$iepspendientecobro 	= explode("//", $iepspendientecobro);
			$iepspendientepago  	= explode("//", $iepspendientepago);
			$ivapendientecobro  	= explode("//", $ivapendientecobro);
			$ivapendientepago   	= explode("//", $ivapendientepago);
			$ish					= explode("//", $ish);
			$ivaretenido 		= explode("//", $ivaretenido);
			$isrretenido			= explode("//", $isrretenido);
			$CuentaIEPSgasto			= explode("//", $CuentaIEPSgasto);
			$CuentaIVAgasto		= explode("//",$CuentaIVAgasto);
			$Exercise = $this->CaptPolizasModel->getExerciseInfo();
			if( $Ex = $Exercise->fetch_assoc() ){
				$idorg 	= $Ex['IdOrganizacion'];
				$idejer	= $Ex['IdEx'];
				$idperio	= $Ex['PeriodoActual'];
			}
			if( isset($_COOKIE['ejercicio']) ){
				$idejer = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
			}if( isset($_COOKIE['periodo']) ){
				$idperio = $_COOKIE['periodo'];
			}
			//$idcuentaproveedores=$cliente['CuentaProveedores'];

			// $receptorcliente[]=$nombrereceptor."";
			//$receptorcliente[] = $rfcreceptor."";
			// $receptorcliente[] = $callerecep." ".$noExteriorrecep;
		   // $receptorcliente[] = $coloniarecep."";
		   // $receptorcliente[] = $codigoPostalrecep."";

			if($_REQUEST['conceptopoliza']){
				$conceptopoliza=$_REQUEST['conceptopoliza'];
			}

// 	//////////////////////////////////////////////////////
		if( $_SESSION['comprobante'] == 1 || $_SESSION['comprobante'] == 3 ){// si es ingresos el cliente sera el receptor
			$idreceptor = 0;
			if(!$conceptopoliza){
				$conceptopoliza='Provision Ingresos';
				if($_SESSION['comprobante'] == 3){
					$conceptopoliza = 'Provision Nota de Credito Ingresos';
				}
			}
			////////////////////////////
			$poli = $this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,3,$conceptopoliza,$fecha,0,"","",0,"",0,0);
			if($poli == 0){
				$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
				if( mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
					$carpeta = true;
				}
			}
			$numov = 1;
			foreach( $_SESSION['provisioncliente'] as $cli){
				foreach($cli as $cliente){
					$idcuentacliente = $cliente['CuentaClientes'];
					$receptor = $this->CaptPolizasModel->consultacliente($cliente['agregacliente'][1]);
					if($receptor->num_rows>0){
						if($re=$receptor->fetch_array()){
						  $idreceptor=$re['id'];
							  if( $re['cuenta'] <=0){
							  	//$idcuentacliente = $idcuentacliente;
								$actualizacliente = $this->CaptPolizasModel->actualizacliente($idcuentacliente, $re['id']);
							  }else{
							  	$idcuentacliente = $re['cuenta'];
							  }
						}
					}else{
						$consul = $this->CaptPolizasModel->consultamuniesta($cliente['agregacliente'][5]);
						if($consul == 0){
							$idestado = 1;
							$idmunicipio = 1;
						}else{
							$separa = explode('/', $consul);
							$idestado = $separa[0];
							$idmunicipio = $separa[1];
						}
						$registroreceptor = $this->CaptPolizasModel->agregareceptorcliente($cliente['agregacliente'][0], $cliente['agregacliente'][2], $cliente['agregacliente'][3], $cliente['agregacliente'][4], $idestado, $idmunicipio, $cliente['agregacliente'][1],$idcuentacliente);
						if($registroreceptor == 0){
							$idreceptor = $this->CaptPolizasModel->ultimo(1);
						}
					}



					if( $statusIVAIEPS == 0 ){
						$ivapendientecobro[0]  = $cliente['cuentaivapendiente'];
						$iepspendientecobro[0] = $cliente['cuentaiepspendiente'];
					}
					if( $statusRetencionISH == 0 ){
						$ish	[0]			= $cliente['cuentaish'];
						$ivaretenido[0]	= $cliente['cuentaiva'];
						$isrretenido[0]	= $cliente['cuentaisr'];
					}

					$referencia 	= $cliente['referencia'];
					$segmento 	= $cliente['segmento'][0];
					$sucursal   	= $cliente['sucursal'][0];

					$abonocomprobante = "Abono";
					$cargocomprobante = "Cargo";
					if($_SESSION['comprobante'] == 3){//Si es nota de credito ingre los movimientos son contrarios
						$abonocomprobante = "Cargo";
						$cargocomprobante = "Abono";

					}
					$suma = 0; $suma2 =0;
					$suma += $cliente['ieps'] + $cliente['ish'];
					$suma += number_format($cliente['abono2'],2,'.','');//iva
					$suma2 = $cliente['cargo'] + $cliente['retenidos']['ISR'] + $cliente['retenidos']['IVA'];

					if($_REQUEST['detalle']){

						$maximo = count($cliente['conceptodetalle']);
						$maximo = (intval($maximo)-1);
						for($c=0;$c<=($maximo);$c++){

							$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cliente['cuentadetalle'][$c],"Abono",number_format($cliente['preciodetalle'][$c],2,'.',''),$cliente['conceptodetalle'][$c],'1-',$cliente['xml'],"$referencia",0);
							$numov++;
						}
					}else{

						if($cliente['hayProrrateo']==0){
							if($_REQUEST['conceptopoliza']){
								$cliente['concepto'][0]=$_REQUEST['conceptopoliza'];
							}
							$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cliente['cuentacompraventa'][0],$abonocomprobante,number_format($cliente['abono'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
							$numov++;
						}else{
							$p=0;
							$maximo = count($cliente['prorrateo']);$maximo = (intval($maximo)-1);
							for($p;$p<=($maximo);$p++){
								if($_REQUEST['conceptopoliza']){
									$cliente['conceptodetalle'][$c]=$_REQUEST['conceptopoliza'];
								}
								$porcen = $cliente['prorrateo'][$p] / 100;
								$monto = $cliente['abono'] * $porcen;
								$suma += number_format($monto,2,'.','');
								if($p == $maximo){
									$totaldecima = $suma- $suma2;
									if($suma>$suma2){
										$monto = $monto - abs(number_format($totaldecima,2,'.',''));
									}if($suma<$suma2){
										$monto = $monto + abs(number_format($totaldecima,2,'.',''));
									}

								}
								$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$cliente['cuentacompraventa'][$p],$abonocomprobante,number_format($monto,2,'.',''),$cliente['concepto'][$p],'1-',$cliente['xml'],"$referencia",0);
								$numov++;
							}
							$cliente['concepto'][0]="-";
						}
					}
					if( $abono == true ){
						if($_REQUEST['conceptopoliza']){
								$cliente['concepto'][0]=$_REQUEST['conceptopoliza'];
							}
						if($statusIEPS==0){
							$iepspendientecobro[0]=$CuentaIEPSgasto[0];
						}
						if($statusIVA==0){
							$ivapendientecobro[0]=$CuentaIVAgasto[0];
						}
						if($cliente['hayProrrateo']==0){



							if( $cliente['ieps'] > 0 ){
								$insertaieps = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$iepspendientecobro[0],$abonocomprobante,number_format($cliente['ieps'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++;
							}
							if( $cliente['ish'] > 0){
								$insertish = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ish[0],$abonocomprobante,number_format($cliente['ish'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++;
							}
						}else{
							if( $cliente['ieps'] > 0 ){
								$insertaieps = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$iepspendientecobro[0],$abonocomprobante,number_format($cliente['ieps'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++; $p++;
							}
							if( $cliente['ish'] > 0){
								$insertish = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$ish[0],$abonocomprobante,number_format($cliente['ish'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++; $p++;
							}
						}
						//nombre
						if($cliente['hayProrrateo']==0){
							if( $cliente['abono2'] > 0){//$ivaingre=$_REQUEST['ivaingre'];
								$abono2 = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivapendientecobro[0],$abonocomprobante,number_format($cliente['abono2'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++;
							}
						}else{
							if( $cliente['abono2'] > 0){//$ivaingre=$_REQUEST['ivaingre'];
								$abono2 = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$ivapendientecobro[0],$abonocomprobante,number_format($cliente['abono2'],2,'.',''),'-','1-',$cliente['xml'],"$referencia",0);
								$numov++; $p++;
							}
						}
						if($cliente['hayProrrateo']==0){
							$si = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$idcuentacliente,$cargocomprobante,number_format($cliente['cargo'],2,'.',''),$cliente['concepto'][0],'1-'.$idreceptor,$cliente['xml'],"$referencia",0);
							$numov++;
						}else{
							$si = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$idcuentacliente,$cargocomprobante,number_format($cliente['cargo'],2,'.',''),'-','1-'.$idreceptor,$cliente['xml'],"$referencia",0);
							$numov++; $p++;
						}
						if( $si != false ){
				 	  	//////retencion/////
							foreach ( $cliente['retenidos'] as $key => $value){
								if($cliente['hayProrrateo']==1){
									$segmento = $cliente['segmento'][$p];
									$sucursal = $cliente['sucursal'][$p];
									$p++;
								}
						 	  	if( $key == "ISR"){
						 	  		if( $value > 0 ){
						 	  			$si = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$isrretenido[0],$cargocomprobante,number_format($value,2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
						 	  			$numov++;
									}
								}
								if( $key == "IVA" ){
						 	  		if( $value > 0 ){
						 	  		 	$si = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivaretenido[0],$cargocomprobante,number_format($value,2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
										$numov++;
									}
								}
							}
				 	  	/////////////
						}else{
		 					$error = true;
		 	   			}

					 }else{
					 	$error = true;
					}

					 if($error==false){
						if($carpeta==true){

							if($_SESSION['comprobante'] == 3){
								rename($this->path()."xmls/facturas/temporales/".$cliente['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$cliente['xml']);
							}else{
								copy($this->path()."xmls/facturas/temporales/".$cliente['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$cliente['xml']);
								rename($this->path()."xmls/facturas/temporales/".$cliente['xmlreal'], $this->path()."xmls/facturas/temporales/".$cliente['xml']);
								$this->CaptPolizasModel->facturaRename($cliente['xml']);
							}

						}
					}
			}//segun foreach
		}//primer foreach
		if($error==false){
			unset($_SESSION['comprobante']);
			unset($_SESSION['provisioncliente']);
			unset($_SESSION['fechaprovi']);
			echo 0;
		}else{
			echo 1;
		}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}else if($_SESSION['comprobante']==2 || $_SESSION['comprobante'] == 4){//si es egresos el proveedor sera el emisor
			if(!$conceptopoliza){
				 $conceptopoliza='Provision Egresos';
				if($_SESSION['comprobante'] == 4){
					$conceptopoliza = 'Provision Nota de Credito Egresos';
				}
			}
			$poli=$this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,3,$conceptopoliza,$fecha,0,"","",0,"",0,0);

				if($poli==0){
					$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
					if(mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
						$carpeta=true;
					}
				}

			$numov=1;
			foreach( $_SESSION['poliprove'] as $cli){
				foreach($cli as $prove){
					$idcuentaproveedores = $prove['CuentaProveedores'];
					$emisor=$this->CaptPolizasModel->emisorprove($prove['agregaprovee'][1],$prove['agregaprovee'][0]);
					if($emisor->num_rows>0){
						if( $re = $emisor->fetch_array() ){
						  $idemisor = $re['idPrv'];
						  if( $re['cuenta'] == " " || $re['cuenta'] <=0){
						  	$idcuentaprove = $idcuentaproveedores;
							$actualizzaprove=$this->CaptPolizasModel->actulizaprove($idcuentaproveedores, $re['idPrv']);
						  }else{
						  	$idcuentaprove = $re['cuenta'];
						  }
						}
					}else{
						$idcuentaprove=$idcuentaproveedores;
						$consul=$this->CaptPolizasModel->consultamuniesta($prove['agregaprovee'][3]);
						$separa=explode('/', $consul);
						$idestado=$separa[0];
						$idmunicipio=$separa[1];//el proveedor es agregado con al cuenta seleccionada
						$registroemisor=$this->CaptPolizasModel->agregaremisorprove($prove['agregaprovee'][0],$prove['agregaprovee'][1], $prove['agregaprovee'][2],$idestado,$idmunicipio,$idcuentaproveedores);
						if($registroemisor==0){
							$idemisor=$this->CaptPolizasModel->ultimo(2);

						}
					}


					if( $statusIVAIEPS == 0 ){
						$ivapendientepago[0]  = $prove['cuentaivapendiente'];
						$iepspendientepago[0] = $prove['cuentaiepspendiente'];
					}
					if( $statusRetencionISH == 0 ){
						$ish	[0]			= $prove['cuentaish'];
						$ivaretenido[0]	= $prove['cuentaiva'];
						$isrretenido[0]	= $prove['cuentaisr'];
					}

					$referencia 	= $prove['referencia'];
					$segmento 	= $prove['segmento'][0];
					$sucursal   	= $prove['sucursal'][0];
					$abonocomprobante = "Abono";
					$cargocomprobante = "Cargo";
					if($_SESSION['comprobante'] == 4){//Si es nota de credito egre los movimientos son contrarios
						$abonocomprobante = "Cargo";
						$cargocomprobante = "Abono";
					}
					$suma = 0; $suma2 =0;
					$suma += $prove['ieps'] + $prove['ish'];
					$suma2 = $prove['abono'] + $prove['retenidos']['ISR'] + $prove['retenidos']['IVA'];
					$suma += number_format($prove['cargo2'],2,'.','');

					if($_REQUEST['detalle']){
						if(!$_REQUEST['conceptopoliza']){
							$prove['concepto']=$conceptopoliza;
						}
						$maximo = count($prove['conceptodetalle']);
						$maximo = (intval($maximo)-1);
						for($c=0;$c<=($maximo);$c++){
							$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentadetalle'][$c],"Cargo",number_format($prove['preciodetalle'][$c],2,'.',''),$prove['conceptodetalle'][$c],'2-',$prove['xml'],"$referencia",9);
							$numov++;
						}
					}else{
						if($prove['hayProrrateo']==0){
							$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentacompraventa'][0],$cargocomprobante,number_format($prove['cargo'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
							$numov++;
						}else{
							$p=0;
							$maximo = count($prove['prorrateo']);$maximo = (intval($maximo)-1);
							for($p;$p<=($maximo);$p++){
								$porcen = $prove['prorrateo'][$p] / 100;
								$monto = $prove['cargo'] * $porcen;
								$suma += number_format($monto,2,'.','');
								if($p == $maximo){
									$totaldecima = $suma- $suma2;
									if($suma>$suma2){
										$monto = $monto - abs(number_format($totaldecima,2,'.',''));
									}if($suma<$suma2){
										$monto = $monto + abs(number_format($totaldecima,2,'.',''));
									}

								}
								$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$prove['cuentacompraventa'][$p],$cargocomprobante,number_format($monto,2,'.',''),$prove['concepto'][$p],'2-',$prove['xml'],"$referencia",9);
								$numov++;
							}
							$prove['concepto'][0]="-";
						}
					}
					if($cargo==true){

						if($statusIEPS==0){
							$iepspendientepago[0]=$CuentaIEPSgasto[0];
						}
						if($statusIVA==0){
							$ivapendientepago[0]=$CuentaIVAgasto[0];
						}
						if($prove['hayProrrateo']==0){
							if($prove['ieps']>0){
								$insertiepss=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$iepspendientepago[0],$cargocomprobante,number_format($prove['ieps'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++;
							}
							if($prove['ish']>0){
								$ishinsert=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ish[0],$cargocomprobante,number_format($prove['ish'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",0);
								$numov++;
							}
							if($prove['cargo2']>0){
								$cargo2=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivapendientepago[0],$cargocomprobante,number_format($prove['cargo2'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++;
							}
						}else{
							if($prove['ieps']>0){
								$insertiepss=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$iepspendientepago[0],$cargocomprobante,number_format($prove['ieps'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++; $p++;
							}
							if($prove['ish']>0){
								$ishinsert=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$ish[0],$cargocomprobante,number_format($prove['ish'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",0);
								$numov++; $p++;
							}
							if($prove['cargo2']>0){
								$cargo2=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$ivapendientepago[0],$cargocomprobante,number_format($prove['cargo2'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++; $p++;
							}
						}
						if($prove['hayProrrateo']==0){
					  		$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$idcuentaprove,$abonocomprobante,number_format($prove['abono'],2,'.',''),$prove['concepto'][0],'2-'.$idemisor,$prove['xml'],"$referencia",9);
						}else{
							$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$idcuentaprove,$abonocomprobante,number_format($prove['abono'],2,'.',''),$prove['concepto'][0],'2-'.$idemisor,$prove['xml'],"$referencia",9);
							$p++;
						}
				 	 	if($si!=false){
				 	  		$numov++;
				 	  		foreach ( $prove['retenidos'] as $key => $value){
				 	  			if($prove['hayProrrateo']==1){
									$segmento = $prove['segmento'][$p];
									$sucursal = $prove['sucursal'][$p];
									$p++;
								}
					 	  		if($key=="ISR"){
					 	  			if($value > 0){
					 	  				$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$isrretenido[0],$abonocomprobante,number_format($value,2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
					 	  				$numov++;
									}
								}
								if($key == "IVA"){
									if($value > 0){
					 	  				$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivaretenido[0],$abonocomprobante,number_format($value,2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
					 	  				$numov++;
									}
								}
							}
				 	 	}else{$error=true;};


					}else{
				 		$error=true;
				 	}
					if($error==false){
						if($carpeta==true){//$archivopar[3]
							if($_SESSION['comprobante'] == 4){
								rename($this->path()."xmls/facturas/temporales/".$prove['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$prove['xml']);
							}else{
								copy($this->path()."xmls/facturas/temporales/".$prove['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$prove['xml']);
								rename($this->path()."xmls/facturas/temporales/".$prove['xmlreal'], $this->path()."xmls/facturas/temporales/".$prove['xml']);
								$this->CaptPolizasModel->facturaRename($prove['xml']);
							}
						}
					}

				}//foreach interno
			}//foreach principal
			if($error==false){
				unset($_SESSION['comprobante']);
				unset($_SESSION['poliprove']);
				unset($_SESSION['fechaprovi']);
				echo 0;
			}else{
				echo 1;
			}
		}//else del ==2



	}
	function comprobacion(){
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$Ex = $Exercise->fetch_assoc();
		$cuentaegresos=$this->CaptPolizasModel->cuentaproviciones('4.2',1);
		$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
		$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
		$Suc = $this->CaptPolizasModel->getSegmentoInfo();
		$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
		$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
		$cuentaivas = $this->CaptPolizasModel->cuentaivas();
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		$cuentaprov = $this->CaptPolizasModel->cuentasprove();
		$anticipos = $this->CaptPolizasModel->anticiposlista();
		$usuarios = $this->CaptPolizasModel->usuarios();
		$type_id_account 	= $this->CaptPolizasModel->CuentaTipoCaptura();
		$Accounts		=	$this->CaptPolizasModel->getAccountGastos($type_id_account);
		if($row=$cuentas->fetch_array()){
			$ivapagado = $this->CaptPolizasModel->buscacuenta($row['CuentaIVApagado']);
			$iepspago  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSpagado']);
			$ishh				= $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$ivaretenido 		= $this->CaptPolizasModel->buscacuenta($row['IVAretenido']);
			$isrretenido		= $this->CaptPolizasModel->buscacuenta($row['ISRretenido']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);

		}
		$ivapagado = explode("//", $ivapagado);
		$iepspago = explode("//", $iepspago);
		$ishh = explode("//", $ishh);
		$ivaretenido = explode("//", $ivaretenido);
		$isrretenido = explode("//", $isrretenido);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);
		$CuentaIVAgasto = explode("//", $CuentaIVAgasto);
		if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
		{
		 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			include('views/captpolizas/comprobacion.php');
		}
		else
		{
			echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config&f=mainPage','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
		}
	}
 function comprobacionGastos(){
 	$error=false;
	global $xp;
	$facturasNoValidas = $facturasValidas = '';
	$numeroInvalidos = $numeroValidos = $no_hay_problema = $noOrganizacion = 0;
	$maximo = count($_FILES['factura']['name']);
	$maximo = (intval($maximo)-1);
	for($i = 0; $i <= $maximo; $i++){
		if($_FILES["factura"]["size"][$i] > 0){
			//Comienza obtener UUID---------------------------
			$file 	= $_FILES['factura']['tmp_name'][$i];
			$texto 	= file_get_contents($file);
			//$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			//$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
			$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '', $texto);
            $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '', $texto);

			$xml 	= new DOMDocument();
			$xml->loadXML($texto);

			$xp = new DOMXpath($xml);
			//$data['uuid'] 	= $this->getpath("//@UUID");
			//$data['folio'] 	= $this->getpath("//@folio");
			//$data['emisor'] = $this->getpath("//@nombre");
				if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");
				
				$version = $data['version'];
			if($version[0] == '3.3')
			{
				
				$data['rfc'] = $this->getpath("//@Rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@Fecha");
				$total = $this->getpath("//@Total");
				$subTotal = $this->getpath("//@SubTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				if(is_array($data['uuid'])){
					$data['uuid'] 	= $data['uuid'][1];
				}
				$data['folio']  	= $this->getpath("//@Folio");
				$data['emisor'] = $this->getpath("//@Nombre");
				$descuento = $this->getpath("//@Descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
				}
			}else{
				$data['rfc'] = $this->getpath("//@rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@fecha");
			    $total=$this->getpath("//@total");
			    $subTotal= $this->getpath("//@subTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$data['folio']  	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				
				$descuento = $this->getpath("//@descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
					//$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				}
			}
				
			//$ishimport = $this->getpath("//@TotaldeTraslados");
			//$version = $data['version'];
			//$data['total'] = $this->getpath("//@total");
			//$data['rfc'] = $this->getpath("//@rfc");
			//Termina obtener UUID---------------------------
			$rfcOrganizacion= $this->CaptPolizasModel->rfcOrganizacion();

			if($data['rfc'][0] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Ingreso";$nombre = $data['emisor'][1];
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Egreso";	$nombre = $data['emisor'][0];

				}
			if($this->valida_xsd($version[0],$xml) && $_FILES['factura']['type'][$i] == "text/xml")
			{

				if($version[0] == '3.2'){
					$no_hay_problema = $this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']);
				}
				else{
					$no_hay_problema = 1;
				}
				if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1]){
					$noOrganizacion = 0;
					$numeroInvalidos++;
					$facturasNoValidas .= $_FILES['factura']['name'][$i]."(RFC no de Organizacion),\\n";
				}else{ $noOrganizacion = 1; }

				$nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
				if($noOrganizacion){
					$validaexiste = $this->existeXML($nombreArchivo);
					if($validaexiste){
						$noOrganizacion = 0;
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['factura']['name'][$i]."Ya existe en $validaexiste.\\n";
					}else{ $noOrganizacion = 1; }
				}
				if($noOrganizacion){
					if($no_hay_problema)
					{
						$previo = array();$retencion=array();
						$xml = simplexml_load_file($_FILES['factura']['tmp_name'][$i]);
						$ns = $xml -> getNamespaces(true);
						$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
						$xml -> registerXPathNamespace('t', $ns['tfd']);
						// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
						    // $fecha= $cfdiComprobante['fecha'];
						    // $total=($cfdiComprobante['total']);
						    // $subTotal= $cfdiComprobante['subTotal'];
							// if($cfdiComprobante['descuento']){
								// $subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
								// //$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
							// }
						// }
						$fec = explode("T", $fecha);
						if(!isset($_SESSION['fechaanticipo'])){
							$_SESSION['fechaanticipo']=$fec[0]."";
						}else{
							if($_REQUEST['fecha']){
								if($_REQUEST['fecha']!=$_SESSION['fechaanticipo']){
									$_SESSION['fechaanticipo']=$_REQUEST['fecha'];
								}
							}else{
								$_SESSION['fechaanticipo']=$fec[0]."";
							}

						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
							if(!$Emisor['rfc']){
								$Emisor['rfc'] = $Emisor['Rfc'];
							}
							if(!$Emisor['nombre']){
								$Emisor['nombre'] = $Emisor['Nombre'];
							}
						   $rfcemisor= $Emisor['rfc'];
						   $nombreemisor= utf8_decode($Emisor['nombre']);
						}
						// foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
						   // $paisemisor= $DomicilioFiscal['pais'];
						   // $calleemisor= $DomicilioFiscal['calle'];
						   // $estadoemisor= $DomicilioFiscal['estado'];
						   // $coloniaemisor= $DomicilioFiscal['colonia'];
						   // $municipioemisor= $DomicilioFiscal['municipio'];
						   // $noExterioremisor= $DomicilioFiscal['noExterior'];
						   // $codigoPostalemisor= $DomicilioFiscal['codigoPostal'];
// 
						// }

						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){
						   $tasa=$Traslado['tasa'];
							 if($version[0] == '3.3'){
						   		$Traslado['importe'] = $Traslado['Importe'];
							   $Traslado['impuesto'] = $this->CaptPolizasModel->nombreImpuestoIndividual($Traslado['Impuesto']);
						   }
						  if($Traslado['impuesto'] == "IVA"){
			  
					  			if($Traslado['importe']>0){
					  				$previo['proveedor']['cargo2']=floatval($Traslado['importe']);
								}
							}
							if($Traslado['impuesto']=="IEPS"){
								if($Traslado['importe']>0){
									$previo['proveedor']['ieps']=floatval($Traslado['importe']);
								}
							}
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Traslad){
							if($version[0] == '3.3'){
								$Traslad['totalImpuestosTrasladados'] = $Traslad['TotalImpuestosTrasladados'];
							}
							$importe=$Traslad['totalImpuestosTrasladados'];
						}
						foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
							$UUID= $tfd['UUID'];
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $retenido){
							if($version[0] == '3.3'){
								$retenido["impuesto"] = $this->CaptPolizasModel->nombreImpuestoIndividual($retenido["Impuesto"]);
								$retenido['importe']	  = $retenido['Importe'];
							}
							$retencion["$retenido[impuesto]"]= number_format(floatval($retenido['importe']),2,'.','');

						}

						foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
							if($version[0] == '3.3'){
								$cfdiComprobante['folio'] =  $cfdiComprobante['Folio'];
							}
								 $folio=$cfdiComprobante['folio'];
						}

						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){
							if($version[0] == '3.3'){
								$concepto['descripcion']	= $concepto['Descripcion'];
								$concepto['importe']		= $concepto['Importe'];			
							}
							$concepto['descripcion'] = str_replace("'", "", $concepto['descripcion']);
							$concepto['descripcion'] = str_replace("\"", "", $concepto['descripcion']);

							$concepto = $concepto['descripcion']."";
						}
						if(!$ishimport){
							$ishimport=0;
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//implocal:ImpuestosLocales//implocal:TrasladosLocales') as $ish){
						 		$ishimport=$ish['Importe'];
							}
						}
						$xmld=$this->quitar_tildes($nombreArchivo);

					if(move_uploaded_file($_FILES['factura']['tmp_name'][$i],$this->path().'xmls/facturas/temporales/'.($xmld))){
						$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');;
						$previo["proveedor"]['retenidos']=$retencion;
						$previo["proveedor"]['nombre']=utf8_encode($nombreemisor);
						$previo["proveedor"]['concepto']=$concepto."";
						$previo["proveedor"]['cargo']=number_format(floatval($subTotal),2,'.','');;
						$previo["proveedor"]['abono']=number_format(floatval($total),2,'.','');
						$previo["proveedor"]['xml']=($xmld);
						$previo["proveedor"]['referencia']=$UUID."";
						$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');
						$previo['proveedor']['ordenador']=1;
						$_SESSION['compruebagasto'][]=$previo;

						//move_uploaded_file($_FILES['factura']['tmp_name'][$i],'xmls/facturas/temporales/'.($xmld));

					}else{
						$errorSubir=true;
						$this->CaptPolizasModel->transaccionController('Error al subir archivo Comprobacion Gastos','' );
					}
						$numeroValidos++;
						$facturasValidas .= $_FILES['factura']['name'][$i].",\n";
					}
					else{
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['factura']['name'][$i]."(Cancelada),\\n";
					}
				}

			}
			else{
				$numeroInvalidos++;
				$facturasNoValidas .= $_FILES['factura']['name'][$i]."(Estructura invalida),\\n";
			}
		}
	}
		$cadena = "";
		if($errorSubir){
			$cadena = "alert('Error al Subir archivos intente de nuevo.');";
		}else{
			if ($numeroInvalidos!=0){
				$cadena = 'alert("Facturas no validas:\n'.$facturasNoValidas.' PARA AGREGAR FACTURAS EXISTENTES REALIZARLO DESDE ALMACEN");';
			}else{
				$cadena = "alert('Facturas validas');";
			}
		}
		echo " <script>$cadena window.location='index.php?c=CaptPolizas&f=comprobacion'; </script>";

 }
function comprobacionGastosAlmacen(){
 	$error=false;
	$maximo = count($_POST['xml']);
	$maximo = (intval($maximo)-1);
	for($i=0;$i<=$maximo;$i++){
		global $xp;
			$previo = array();$retencion=array();
			$archivo = $_POST['xml'][$i];
			$texto 	= file_get_contents($archivo);
			$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);

			$xmld 	= new DOMDocument();
			$xmld->loadXML($texto);
			$xp = new DOMXpath($xmld);
			if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");
			$version = $data['version'];
			

			if($version[0] == '3.3')
			{
				
				$data['rfc'] = $this->getpath("//@Rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@Fecha");
				$total = $this->getpath("//@Total");
				$subTotal = $this->getpath("//@SubTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				if(is_array($data['uuid'])){
					$data['uuid'] 	= $data['uuid'][1];
				}
				$folio 	= $this->getpath("//@Folio");
				$data['emisor'] = $this->getpath("//@Nombre");
				$descuento = $this->getpath("//@Descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
				}
			}else{
				$data['rfc'] = $this->getpath("//@rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@fecha");
			    $total=$this->getpath("//@total");
			    $subTotal= $this->getpath("//@subTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$folio 	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				
				$descuento = $this->getpath("//@descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
					//$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				}
			}
			$xml = simplexml_load_file($_POST['xml'][$i]);
			$ns = $xml -> getNamespaces(true);
			$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
			$xml -> registerXPathNamespace('t', $ns['tfd']);
			// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
			    // $fecha= $cfdiComprobante['fecha'];
			    // $total=($cfdiComprobante['total']);
			    // $subTotal= $cfdiComprobante['subTotal'];
				// if($cfdiComprobante['descuento']){
					// $subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
					// //$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				// }
			// }
			$fec = explode("T", $fecha);
			if(!isset($_SESSION['fechaanticipo'])){
				$_SESSION['fechaanticipo']=$fec[0]."";
			}else{
				if($_REQUEST['fecha']){
					if($_REQUEST['fecha']!=$_SESSION['fechaanticipo']){
						$_SESSION['fechaanticipo']=$_REQUEST['fecha'];
					}
				}else{
					$_SESSION['fechaanticipo']=$fec[0]."";
				}

			}
			// foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
			   // $rfcemisor= $Emisor['rfc'];
			   // $nombreemisor= utf8_decode($Emisor['nombre']);
			   // $nombreemisor2= ($Emisor['nombre']);
// 			   
			// }
			// foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
			   // $paisemisor= $DomicilioFiscal['pais'];
			   // $calleemisor= $DomicilioFiscal['calle'];
			   // $estadoemisor= $DomicilioFiscal['estado'];
			   // $coloniaemisor= $DomicilioFiscal['colonia'];
			   // $municipioemisor= $DomicilioFiscal['municipio'];
			   // $noExterioremisor= $DomicilioFiscal['noExterior'];
			   // $codigoPostalemisor= $DomicilioFiscal['codigoPostal'];
// 
			// }

			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){
			   $tasa=$Traslado['tasa'];
			  if($version[0] == '3.3'){
			   	$Traslado['importe'] = $Traslado['Importe'];
				$Traslado['impuesto'] = $this->CaptPolizasModel->nombreImpuestoIndividual($Traslado['Impuesto']);
			  }
			  if($Traslado['impuesto'] == "IVA"){
			  
		  			if($Traslado['importe']>0){
		  				$previo['proveedor']['cargo2']=floatval($Traslado['importe']);
					}
				}
				if($Traslado['impuesto']=="IEPS"){
					if($Traslado['importe']>0){
						$previo['proveedor']['ieps']=floatval($Traslado['importe']);
					}
				}
			}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Traslad){
				 if($version[0] == '3.3'){
				 	$Traslad['totalImpuestosTrasladados'] = $Traslad['TotalImpuestosTrasladados'];
				 }
				$importe=$Traslad['totalImpuestosTrasladados'];
			}
			foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
				$UUID= $tfd['UUID'];
			}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $retenido){
				if($version[0] == '3.3'){
					$retenido["impuesto"] = $this->CaptPolizasModel->nombreImpuestoIndividual($retenido["Impuesto"]);
					$retenido['importe']	  = $retenido['Importe'];
				}
				$retencion["$retenido[impuesto]"]= number_format(floatval($retenido['importe']),2,'.','');

			}

			foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
				if($version[0] == '3.3'){
					$cfdiComprobante['folio'] = $cfdiComprobante['Folio'];
				}
					 $folio=$cfdiComprobante['folio'];
			}

			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){
				if($version[0] == '3.3'){
					$concepto['descripcion']	= $concepto['Descripcion'];
					$concepto['importe']		= $concepto['Importe'];			
				}
				$concepto['descripcion'] = str_replace("'", "", $concepto['descripcion']);
				$concepto['descripcion'] = str_replace("\"", "", $concepto['descripcion']);

				$concepto = $concepto['descripcion']."";
			}
			if(!$ishimport){
				$ishimport=0;
				foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//implocal:ImpuestosLocales//implocal:TrasladosLocales') as $ish){
			 		$ishimport=$ish['Importe'];
				}
			}
			$archivopar = explode("/",$_POST['xml'][$i]);
			$xmld=$this->quitar_tildes($archivopar[3]);


			$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');;
			$previo["proveedor"]['retenidos']=$retencion;
			$previo["proveedor"]['nombre']=utf8_encode($nombreemisor);
			$previo["proveedor"]['concepto']=$concepto."";
			$previo["proveedor"]['cargo']=number_format(floatval($subTotal),2,'.','');;
			$previo["proveedor"]['abono']=number_format(floatval($total),2,'.','');
			$previo["proveedor"]['xml']=($xmld);
			$previo["proveedor"]['referencia']=$UUID."";
			$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');

			$_SESSION['compruebagasto'][]=$previo;
			rename($_POST['xml'][$i],$this->path().'xmls/facturas/temporales/'.($xmld));
			$this->CaptPolizasModel->facturaRename($xmld);

	}

 }

	function deudorAnticipo(){
		unset($_SESSION['compruebagasto']);
		unset($_SESSION['deudornombre']);
		$deudor = $this->CaptPolizasModel->deudorNombre($_REQUEST['idpoliza']);
		$cargodeudor = $this->CaptPolizasModel->cargosDeudor($_REQUEST['idpoliza'], $deudor['Cuenta']);
		$abonodeudor = $this->CaptPolizasModel->abonosDeudor($_REQUEST['idpoliza'], $deudor['Cuenta']);
		$totalabono = $abonodeudor['abono'] + $deudor['cargo'];

		if(number_format($cargodeudor['cargo'],2,'.','') < number_format($totalabono,2,'.','') ){
			$totaldeudor = $totalabono-$cargodeudor['cargo'];
		}else {$totaldeudor =0; }
		//agrega las las facturas q estan agregadas en los anticipos y los no deducibles
		$noDe = $this->CaptPolizasModel->listaNoDeducible($_REQUEST['idpoliza']);
		while($nodedu = $noDe->fetch_assoc()){
			$nodeducible = array();
			$nodeducible["proveedor"]['xml']	= "No deducible";
			$nodeducible["proveedor"]['cargo']				= $nodedu['importe'];
			$nodeducible["proveedor"]['concepto'] 			= $nodedu['concepto'];
			$nodeducible["proveedor"]['idnodeducible']		= $nodedu['id'];
			$_SESSION['compruebagasto'][] = $nodeducible;
		}
		if($directorio = opendir($this->path()."xmls/facturas/".$_REQUEST['idpoliza']))
		{
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store')
				{
					$previo = array();$retencion=array();
						$xml = simplexml_load_file($this->path()."xmls/facturas/".$_REQUEST['idpoliza']."/".$archivo);
						$ns = $xml -> getNamespaces(true);
						$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
						$xml -> registerXPathNamespace('t', $ns['tfd']);
						foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
							if(!$cfdiComprobante['fecha']){
								$cfdiComprobante['fecha'] = $cfdiComprobante['Fecha'];
							}
							if(!$cfdiComprobante['total']){
								$cfdiComprobante['total'] = $cfdiComprobante['Total'];
							}
							if(!$cfdiComprobante['subTotal']){
								$cfdiComprobante['subTotal'] = $cfdiComprobante['SubTotal'];
							}
							if(!$cfdiComprobante['descuento']){
								$cfdiComprobante['descuento'] = $cfdiComprobante['Descuento'];
							}
						    $fecha= $cfdiComprobante['fecha'];
						    $total=($cfdiComprobante['total']);
						    $subTotal= $cfdiComprobante['subTotal'];
							if($cfdiComprobante['descuento']){
								$subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
								//$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
							}
						}

						$fec = explode("T", $fecha);
						if(!isset($_SESSION['fechaanticipo'])){
							$_SESSION['fechaanticipo']=$fec[0]."";
						}else{
							if($_REQUEST['fecha']){
								if($_REQUEST['fecha']!=$_SESSION['fechaanticipo']){
									$_SESSION['fechaanticipo']=$_REQUEST['fecha'];
								}
							}else{
								$_SESSION['fechaanticipo']=$fec[0]."";
							}

						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
							if(!$Emisor['rfc']){
								$Emisor['rfc'] = $Emisor['Rfc'];
							}
							if(!$Emisor['nombre']){
								$Emisor['nombre'] = $Emisor['Nombre'];
							}
						   $rfcemisor= $Emisor['rfc'];
						   $nombreemisor= utf8_decode($Emisor['nombre']);
						}
						// foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
						   // $paisemisor= $DomicilioFiscal['pais'];
						   // $calleemisor= $DomicilioFiscal['calle'];
						   // $estadoemisor= $DomicilioFiscal['estado'];
						   // $coloniaemisor= $DomicilioFiscal['colonia'];
						   // $municipioemisor= $DomicilioFiscal['municipio'];
						   // $noExterioremisor= $DomicilioFiscal['noExterior'];
						   // $codigoPostalemisor= $DomicilioFiscal['codigoPostal'];
// 
						// }

						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){
						   $tasa=$Traslado['tasa'];
							if(!$Traslado['importe']	){
					  			$Traslado['importe']		= $Traslado['Importe'];
							}
							if(!$Traslado['impuesto']){
								$Traslado['impuesto'] 	= $this->CaptPolizasModel->nombreImpuestoIndividual($Traslado['Impuesto']);
							}
						  if($Traslado['impuesto']=="IVA"){
						  
					  			if($Traslado['importe']>0){
					  				$previo['proveedor']['cargo2']=floatval($Traslado['importe']);
								}
							}
							if($Traslado['impuesto']=="IEPS"){
								if($Traslado['importe']>0){
									$previo['proveedor']['ieps']=floatval($Traslado['importe']);
								}
							}
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Traslad){
							if(!$Traslad['totalImpuestosTrasladados']){
								$Traslad['totalImpuestosTrasladados'] = $Traslad['TotalImpuestosTrasladados'];
							}
							$importe=$Traslad['totalImpuestosTrasladados'];
						}
						foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
							$UUID= $tfd['UUID'];
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $retenido){
							if(!$retenido["impuesto"]){
								$retenido["impuesto"] = $this->CaptPolizasModel->nombreImpuestoIndividual($retenido["Impuesto"]);

							}
							if(!$retenido['importe']){
								$retenido['importe']	  = $retenido['Importe'];
							}
					
	
							$retencion["$retenido[impuesto]"]= number_format(floatval($retenido['importe']),2,'.','');

						}

						foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
							if($cfdiComprobante['folio']){
								$cfdiComprobante['folio'] = $cfdiComprobante['Folio'];
							}
								 $folio=$cfdiComprobante['folio'];
						}
						$ishimport=0;
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){
							if(!$concepto['descripcion']){
								$concepto['descripcion'] = $concepto['Descripcion'];
							}
							$concepto['descripcion'] = str_replace("'", "", $concepto['descripcion']);
							$concepto['descripcion'] = str_replace("\"", "", $concepto['descripcion']);

							$concepto = $concepto['descripcion']."";
						}
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//implocal:ImpuestosLocales//implocal:TrasladosLocales') as $ish){
					 		$ishimport=$ish['Importe'];
						}


						$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');;
						$previo["proveedor"]['retenidos']=$retencion;
						$previo["proveedor"]['nombre']=utf8_encode($nombreemisor);
						$previo["proveedor"]['concepto']=$concepto."";
						$previo["proveedor"]['cargo']=number_format(floatval($subTotal),2,'.','');;
						$previo["proveedor"]['abono']=number_format(floatval($total),2,'.','');
						$previo["proveedor"]['xml']=$archivo;
						$previo["proveedor"]['referencia']=$UUID."";
						$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');
						$previo['proveedor']['poliza']=$_REQUEST['idpoliza'];

						$_SESSION['compruebagasto'][]=$previo;
						//move_uploaded_file($archivo,'xmls/facturas/temporales/'.$archivo);


				}
			}
		}
		$_SESSION['deudornombre']=$deudor['description']."//".$deudor['Cuenta']."//".$totaldeudor."//".$deudor['IdSegmento']."//".$deudor['IdSucursal']."//".$_REQUEST['idpoliza'];
	}
	function guardacomprobacion(){
		$error = false;
		$fecha = $_REQUEST['fecha'];
		$carpeta = false;
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$ivapagado = $this->CaptPolizasModel->buscacuenta($row['CuentaIVApagado']);
			$iepspago  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSpagado']);
			$ish				= $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$ivaretenido 		= $this->CaptPolizasModel->buscacuenta($row['IVAretenido']);
			$isrretenido		= $this->CaptPolizasModel->buscacuenta($row['ISRretenido']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);

		}

		$ivapagado 	= explode("//", $ivapagado);
		$iepspago  	= explode("//", $iepspago);
		$ish					= explode("//", $ish);
		$ivaretenido 		= explode("//", $ivaretenido);
		$isrretenido			= explode("//", $isrretenido);
		$CuentaIEPSgasto		= explode("//", $CuentaIEPSgasto);
		$CuentaIVAgasto		= explode("//", $CuentaIVAgasto);
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		if( $Ex = $Exercise->fetch_assoc() ){
			$idorg 	= $Ex['IdOrganizacion'];
			$idejer	= $Ex['IdEx'];
			$idperio	= $Ex['PeriodoActual'];
		}
		if( isset($_COOKIE['ejercicio']) ){
			$idejer = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
		}if( isset($_COOKIE['periodo']) ){
			$idperio = $_COOKIE['periodo'];
		}
		$separa = explode("//", $_SESSION['deudornombre']);
		$poliza = $this->CaptPolizasModel->GetAllPolizaInfo($separa[5]);
		$poli=$this->CaptPolizasModel->savePolizaGasto($idorg,$idejer,$idperio,3,'Comprobacion Gastos '.$poliza['concepto'],$fecha,0,"","",0,"",0,$separa[5]);

				if($poli==0){
					$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
					if(mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
						$carpeta=true;
					}
				}
			$numov=1;
			foreach( $_SESSION['compruebagasto'] as $cli){
				foreach($cli as $prove){
					if( $statusIVAIEPS == 0 ){
						$ivapagado[0]  = $prove['cuentaivapendiente'];
						$iepspago[0] = $prove['cuentaiepspendiente'];
					}
					if( $statusRetencionISH == 0 ){
						$ish	[0]			= $prove['cuentaish'];
						$ivaretenido[0]	= $prove['cuentaiva'];
						$isrretenido[0]	= $prove['cuentaisr'];
					}

					$referencia 	= "";
					$segmento 	= $prove['segmento'];
					$sucursal   	= $prove['sucursal'];
					if(isset($prove['cuentanodeducible'])){
						$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentacompraventa'],"Cargo",number_format($prove['cargo'],2,'.',''),$prove['concepto'],'2-','',"$referencia",9);
						$numov++;
					}else{
						if(isset($prove['idnodeducible'])){
							$this->CaptPolizasModel->NoDeducibleEnPoliza($prove['idnodeducible']);
						}
						$referencia = $prove['referencia'];
						$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentacompraventa'],"Cargo",number_format($prove['cargo'],2,'.',''),$prove['concepto'],'2-',$prove['xml'],"$referencia",9);
						$numov++;
					}
					if($cargo==true){
						if($statusIVA==0){
							$ivapagado[0]=$CuentaIVAgasto[0];
						}
						if($prove['cargo2']>0){
							$cargo2=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivapagado[0],"Cargo",number_format($prove['cargo2'],2,'.',''),$prove['concepto'],'2-',$prove['xml'],"$referencia",9);
							$numov++;
						}
						if($statusIEPS==0){
							$iepspago[0]=$CuentaIEPSgasto[0];
						}
						if($prove['ieps']>0){
							$insertiepss=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$iepspago[0],"Cargo",number_format($prove['ieps'],2,'.',''),$prove['concepto'],'2-',$prove['xml'],"$referencia",9);
							$numov++;
						}
						if($prove['ish']>0){
							$ishinsert=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ish[0],"Cargo",number_format($prove['ish'],2,'.',''),$prove['concepto'],'2-',$prove['xml'],"$referencia",0);
							$numov++;
						}
					  	//$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$separa[1],"Abono",$prove['abono'],$prove['concepto'],'2-'.$idemisor,$prove['xml'],"$referencia",9);
				 	 	//if($si!=false){
				 	 		//$numov++;
				 	 		foreach ( $prove['retenidos'] as $key => $value){
					 	  		if($key=="ISR"){
					 	  			if($value > 0){
					 	  				$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$isrretenido[0],"Abono",number_format($value,2,'.',''),$prove['concepto'],'2-',$prove['xml'],"$referencia",9);
					 	  				$numov++;
									}
								}
								if($key == "IVA"){
									if($value > 0){
					 	  				$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivaretenido[0],"Abono",number_format($value,2,'.',''),$prove['concepto'],'2-',$prove['xml'],"$referencia",9);
					 	  				$numov++;
									}
								}
							}
				 	 	//}else{$error=true;};


					}else{
				 		$error=true;
				 	}
					if($error==false){
						if($carpeta==true){
							if(($prove['xml']=="No deducible")|| $prove['xml']==0){
							}else{
								$buscaTicket = $this->CaptPolizasModel->buscaTicket($prove['xml']);
								
								if($prove['poliza']>0){
									rename($this->path()."xmls/facturas/".$prove['poliza']."/".$prove['xml'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$prove['xml']);
								}else{
									rename($this->path()."xmls/facturas/temporales/".$prove['xml'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$prove['xml']);
								}
							}
						}
					}

				}//foreach interno
			}//foreach principal
			if($error==false){
				$nombredeudor = explode('//', $_SESSION['deudornombre']);
				$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$nombredeudor[3],$nombredeudor[4],$nombredeudor[1],"Abono",number_format($nombredeudor[2],2,'.',''),"Comprobacion gastos ".$poliza['concepto'],'2-','',"",9);
				$numov++;
				if($_REQUEST['cuentadiferencia']>0){
	 	 			if($_REQUEST['cargodiferencia']>0){
	 	 				$dife=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$nombredeudor[3],$nombredeudor[4],$_REQUEST['cuentadiferencia'],"Cargo",number_format($_REQUEST['cargodiferencia'],2,'.',''),"Comprobacion gastos ".$poliza['concepto'],'2-','',"",9);
					}
					if($_REQUEST['abonodiferencia']>0){
						$dife=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$nombredeudor[3],$nombredeudor[4],$_REQUEST['cuentadiferencia'],"Abono",number_format($_REQUEST['abonodiferencia'],2,'.',''),"Comprobacion gastos ".$poliza['concepto'],'2-','',"",9);
					}
	 	 		}
				unset($_SESSION['compruebagasto']);
				unset($_SESSION['deudornombre']);
				unset($_SESSION['fechaanticipo']);
				unset($_SESSION["lista"]);
				unset($_SESSION['usercompro']);
				echo 0;
			}else{
				echo 1;
			}
	}




	function agregatr(){
		$ListaSegmentos 		=	$this->CaptPolizasModel->ListaSegmentos();
		$ListaSucursales 	=	$this->CaptPolizasModel->ListaSucursales();
		$cuentaegresos		=	$this->CaptPolizasModel->cuentaproviciones('4.2',1);
		$segmento="";
		while($LS = $ListaSegmentos->fetch_assoc()){$select="";
			if($_REQUEST['segmentode']==$LS['idSuc']){ $select="selected";}
			$segmento .= "<option value=".$LS['idSuc']." $select>".$LS['nombre']."</option>";
		}
		$sucursal="";
		while($LS = $ListaSucursales->fetch_assoc()){$selec = "";
			if($_REQUEST['sucursalde']==$LS['idSuc']){ $selec="selected";}
			$sucursal .= "<option value=".$LS['idSuc']." $selec>".$LS['nombre']."</option>";
		}
		$cuentaegre="";
		while($ingre=$cuentaegresos->fetch_array()){$sele="";
		if($_REQUEST['cuenta'] == $ingre['account_id']){ $sele ="selected";}
			$cuentaegre .= "<option value=".$ingre['account_id']." $sele>".$ingre['description']."(".$ingre['manual_code'].")</option>";
		}

		$previo = array();
		$previo["proveedor"]['cuentanodeducible']	= $_REQUEST['cuenta'];
		$previo["proveedor"]['segmento']				= $_REQUEST['segmentode'];
		$previo["proveedor"]['sucursal'] 			= $_REQUEST['sucursalde'];
		$previo["proveedor"]['cargo']				= $_REQUEST['cargodeducible'];
		$previo["proveedor"]['concepto'] 			= $_REQUEST['conceptode'];
		$previo["proveedor"]['listacuenta']			= $cuentaegre;
		$previo["proveedor"]['listasegmento']		= $segmento;
		$previo["proveedor"]['listasucursal']		= $sucursal;

		$_SESSION['compruebagasto'][] = $previo;

	}

	function movListado(){
		$NumMovs = $this->CaptPolizasModel->NumMovs($_POST['idPoliza']);
		$optionc = "<optgroup label='CARGOS'>";
		$optiona = "<optgroup label='ABONOS'>";

		while( $row = $NumMovs->fetch_assoc() ){
			if($row['TipoMovto'] == "Cargo"){
				$optionc.="<option value=".$row['Id'].">(".$row['Cuenta'].") $".$row['Importe']."</option>";

			}
			if($row['TipoMovto'] == "Abono"){
				$optiona.="<option value=".$row['Id'].">(".$row['Cuenta'].")  $".$row['Importe']."</option>";

			}

		}
		echo $optionc.$optiona;
	}
	function polizasCopy(){
		$Polizas = $this->CaptPolizasModel->polizasActivas($_POST['Ejercicio']);
		while( $row = $Polizas->fetch_assoc() ){
			echo "<option value=".$row['id'].">Pol.".$row['numpol']." ".$row['tipopoliza']." (".$row['concepto'].")".$row['fecha']."</option>";
		}
	}
	function polizasCopyFecha(){
		$Polizas = $this->CaptPolizasModel->polizasActivasFecha($_POST['desde'],$_POST['hasta']);
		while( $row = $Polizas->fetch_assoc() ){
			echo "<option value=".$row['id'].">Pol.".$row['numpol']." ".$row['tipopoliza']." (".$row['concepto'].")".$row['fecha']."</option>";
		}
	}
	function copiaPoliza(){$completa=0;$mov=0;
		if($_REQUEST['ele'] == 1){//completa
			$fecha = explode('-',$_REQUEST['fechacopy']);//a-m-d
			$concepto = $_REQUEST['conceptocopy'];
			$idpoliza = $_REQUEST['idpoliza'];
			$idejer = $this->CaptPolizasModel->idex($fecha[0]);
			$poli = $this->CaptPolizasModel->GetAllPolizaInfo($idpoliza);
			if($fecha[1][0] == 0){
				$periodo = $fecha[1][1];
			}else{
				$periodo = $fecha[1];
			}
			$completa= $this->CaptPolizasModel->copiCompleta($idpoliza, $periodo, $idejer, $poli['idtipopoliza'], $_REQUEST['fechacopy'],$_REQUEST['conceptocopy']);
		}else{
			$movimientos = $_REQUEST['movimientoscopi'];
			$ultimo = $this->CaptPolizasModel->UltimoMov($_REQUEST['idpolicopy']);
			if(!$ultimo){ $ultimo=1;}else{ $ultimo++;}
			for ($i=0;$i<count($movimientos);$i++)
			{
				$insertm = $this->CaptPolizasModel->copyMov($_REQUEST['idpolicopy'], $ultimo,$movimientos[$i])  ;
				if($insertm){
					$ultimo ++;
					$mov=1;
				}else{
					$mov=0;
				}
			}
		}
	$reload = "window.location='index.php?c=CaptPolizas&f=ModificarPoliza&id=".$_REQUEST['idpoliza']."'";
		if($completa){
			 $numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
			echo "
			<script>
			if(confirm('Poliza generada Correctamente!. Desea ver la poliza?')){
	 			window.parent.preguntar=false;
	 			window.parent.quitartab('tb0',0,'Polizas');
	 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id=".$numPoliza['id']."&im=3','Polizas','',0);
				window.parent.preguntar=true;
				$reload
			}else{
				$reload
			}
			</script>
			";
		}
		if($mov){
			echo "
			<script>
	 		alert('Movimientos agregados');
			$reload
			</script>";
		}
	}

	function subecomprobantes(){
		$listaanticipos = $this->CaptPolizasModel->anticiposlistauser($_SESSION["accelog_idempleado"]);
		//$listaanticipos
		$relleno="";
		if($listaanticipos!=0){
			while($row = $listaanticipos->fetch_object()){
				$deudor = $this->CaptPolizasModel->deudorNombre($row->id);
				$cargodeudor = $this->CaptPolizasModel->cargosDeudor($row->id, $row->Cuenta);
				$abonodeudor = $this->CaptPolizasModel->abonosDeudor($row->id, $row->Cuenta);
				$totalabono = $abonodeudor['abono'] + $deudor['cargo'];
				if($cargodeudor['cargo'] <= $totalabono){
					$total = $totalabono-$cargodeudor['cargo'];
				}else {$total =0; }
			$relleno.=
				"<tr>
					<td><b>".$row->concepto."</b>
					<input type='hidden' name='tanticipo[]' id='tanticipo[]' value='".$row->id."'>
					</td>
					<td><b>".number_format($total,2,'.',',')."</b></td>
					<td><a  style='font-weight:bold;color:black;' href=\"javascript:agregadeducible(".$row->id.",'".$row->concepto."')\" >Agregar No Deducible</a></td>
					<td>
					<a href='javascript:facturas(".$row->id.")' style='font-weight:bold;color:black;' title='Ver Facturas' id='FacturasButton'>
						<img src='images/clip.png' style='vertical-align: middle;' width='40px' title='Subir Facturas'>Subir Facturas</a>
					</td>
					<td id='".$row->id."'>".number_format($total,2,'.',',')."</td>
				</tr>";
			}
		}else{
		$relleno.= '<tr>
					<td colspan="5"><b>No hay Anticipos</b></td>
				</tr>';
		}
		$nombre = $this->CaptPolizasModel->infousuario($_SESSION["accelog_idempleado"]);
		require('views/captpolizas/subecomprobante.php');
	}

	function nodeducibleUser(){
		$query="";
		for($i=1;$i<count($_REQUEST['importe']);$i++){
			$query.="insert into cont_nodeducible (concepto,importe,idAnticipo) values ('".$_REQUEST['concepto'][$i]."',".$_REQUEST['importe'][$i].",".$_REQUEST['idanticipo'].");";
		}
		$elimina = $this->CaptPolizasModel->borraListVieja($_REQUEST['idanticipo']);
		if($elimina==0){
			if($_REQUEST['importe'][1]){
				$insert = $this->CaptPolizasModel->agregaNoDeducibles($query);
			}
		}else{
			$insert = 1;
		}

		if($insert==0){
			$alerta = "";
		}else{
			$alerta = "alert('Error al agregar No deducibles intente de nuevo'); ";
		}
		echo "<script >$alerta window.location = 'index.php?c=CaptPolizas&f=subecomprobantes';</script>";

	}
	function muestraDeducible(){
		$node = $this->CaptPolizasModel->listaNoDeducible($_REQUEST['idanticipo']);
		while($n = $node->fetch_assoc()){
			$lista .='<tr>
			<td><input type="text" id="importe[]" name="importe[]" value="'.$n['importe'].'" onkeyup="calculaNoDeducible();"/></td>
			<td><input type="text" id="concepto[]" name="concepto[]" size="255" value="'.$n['concepto'].'"/></td>
			<td class="eliminar">Eliminar</td></tr>';
		}
		echo $lista;
	}
	function buscaAnticipo(){
		unset($_SESSION["lista"]);
		unset($_SESSION['compruebagasto']);
		unset($_SESSION['deudornombre']);
		unset($_SESSION['usercompro']);
		$anticipos = $this->CaptPolizasModel->anticiposlistauser($_REQUEST['user']);

		if($anticipos->num_rows>0){
			$_SESSION["lista"].= "<option value=0> Elija un anticipo</option>";

		while($an = $anticipos->fetch_assoc()){
			$_SESSION["lista"].=  "<option value=".$an['id']." >".$an['concepto']."(".$an['prove'].")</option>";
		 }
		}else{
			$_SESSION["lista"].= "<option value=0> No hay anticipos</option>";
		}
		$_SESSION['usercompro']=$_REQUEST['user'];
	}

	function CuentaAgregaAuto(){
		$ctaBanca = $this->CaptPolizasModel->infobancariaid($_REQUEST['cuenta']);
		$movViejo = $this->CaptPolizasModel->deleteViejoMov($_REQUEST['IdPoliza'], $_REQUEST['Movto']);
		if($movViejo){
			$resultado = $this->CaptPolizasModel->InsertMov2($_REQUEST['IdPoliza'], $_REQUEST['Movto'], 1, 1, $ctaBanca['account_id'], $_REQUEST['TipoMovto'], $_REQUEST['importe'], $_REQUEST['concepto'], $_REQUEST['persona'], "-", $_REQUEST['referencia'], $_REQUEST['fomapago']);
			if($resultado==false){
				echo 1;
			}
		}else{
			echo 1;
		}

	}
	function CuentaPrvAgregaAuto(){
		$ctaArbol = $this->CaptPolizasModel->datosPrv($_REQUEST['prv']);
		$movViejo = $this->CaptPolizasModel->deleteViejoMov($_REQUEST['IdPoliza'], $_REQUEST['Movto']);
		if($ctaArbol['cuenta']){
			if($movViejo){
				$resultado = $this->CaptPolizasModel->InsertMov2($_REQUEST['IdPoliza'], $_REQUEST['Movto'], 1, 1, $ctaArbol['cuenta'], $_REQUEST['TipoMovto'], $_REQUEST['importe'], $_REQUEST['concepto'], $_REQUEST['persona'], "-", $_REQUEST['referencia'], $_REQUEST['fomapago']);
				if($resultado==false){
					echo 1;
				}
			}else{
				echo 0;
			}
		}else{
			echo 2;
		}
	}
	function listaTemporalesProvision()
	{
		global $xp;
		$listaTemporales = "
		<tr>
			<td width='50' style='color:white;'>*1_-{}*</td>
			<td width='300'></td>
			<td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td>
			<td width='50' style='font-weight:bold;font-size:9px;text-align:center;'>
				<button id='' onclick='buttondesclick(\"copiar\")'>Desmarcar</button>
			</td>
			<td width='50' style='font-weight:bold;font-size:9px;text-align:center;'>
				<button id='' onclick='marcarfiltro(\"copiar\")'>Marcar Todos</button>
			</td>
		</tr>";

		$buscar = "{".$_POST['folio_uuid']."}";
			
		
		if(intval($_POST['tipo_busqueda']) == 1)
		{//Si es UUID
			if($archivos = glob($this->path()."xmls/facturas/temporales/*".strtoupper($buscar).".xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en mayusculas
			elseif($archivos = glob($this->path()."xmls/facturas/temporales/*".strtolower($buscar).".xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en minusculas
			else
			{
				$buscar = "*".$buscar;
				$r = 1;
			}
		}
		if(!intval($_POST['tipo_busqueda']))
		{//Si es Folio
			$buscar = $buscar."_*";	
			$r = 1;
		}

		if(intval($_POST['tipo_busqueda']) == 2)
		{//Si es Razon Social
			if($archivos = glob($this->path()."xmls/facturas/temporales/*_*".strtoupper($buscar)."*_*.xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en mayusculas
			elseif($archivos = glob($this->path()."xmls/facturas/temporales/*_*".strtolower($buscar)."*_*.xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en minusculas
			else
			{
				$r = 1;//Si no la encuentra en ninguna
				$buscar = "*_*$buscar*_*";
			}
		}

		
			
		$dir = $this->path()."xmls/facturas/temporales/$buscar.xml";
		//echo $dir;

		// Abrir un directorio, y proceder a leer su contenido
		if($r)
			$archivos = glob($dir,GLOB_BRACE);
		array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);

		$cont=1;
		foreach($archivos as $file)
		{
			if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file'){
				$texto 	= file_get_contents($file);
				$xml 	= new DOMDocument();
				$xml->loadXML($texto);
				$xp = new DOMXpath($xml);
			if($this->getpath("//@version"))
	            $data['version'] = $this->getpath("//@version");
	        else
	        		$data['version'] = $this->getpath("//@Version");

			$version = $data['version'];
				if($version[0] == '3.3')
			{
				$data['total'] = $this->getpath("//@Total");
				$data['descripcion'] = $this->getpath("//@Descripcion");
				$data['rfc'] = $this->getpath("//@Rfc");
				$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
				$data['impuesto'] = $this->CaptPolizasModel->nombreImpuesto($this->getpath("//@Impuesto"));
				$data['subtotal'] = $this->getpath("//@SubTotal");
				$data['descuento'] = $this->getpath("//@Descuento");
				$data['nombre'] = $this->getpath("//@Nombre");
				$data['descripcion2']=$this->getpath("//@Descripcion");
				$data['cantidad']=$this->getpath("//@Cantidad");
				$data['unidad']=$this->getpath("//@Unidad");
				$data['valorUnitario']=$this->getpath("//@ValorUnitario");
				$data['importe']=$this->getpath("//@Importe");
				$data['nomina']=$this->getpath("//@NumEmpleado");
				$data['metodoDePago']=$this->getpath("//@MetodoPago");
				$data['uuid'] = 	$this->getpath("//@UUID");
				if(is_array($data['uuid'])){
					$data['uuid'] 	= $data['uuid'][1];
				}
			}
			else
			{
				$data['total'] = $this->getpath("//@total");
				$data['descripcion'] = $this->getpath("//@descripcion");
				$data['rfc'] = $this->getpath("//@rfc");
				$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
				$data['impuesto'] = $this->getpath("//@impuesto");
				$data['subtotal'] = $this->getpath("//@subTotal");
				$data['descuento'] = $this->getpath("//@descuento");
				$data['nombre'] = $this->getpath("//@nombre");
				$data['descripcion2']=$this->getpath("//@descripcion");
				$data['cantidad']=$this->getpath("//@cantidad");
				$data['unidad']=$this->getpath("//@unidad");
				$data['valorUnitario']=$this->getpath("//@valorUnitario");
				$data['importe']=$this->getpath("//@importe");
				$data['nomina']=$this->getpath("//@NumEmpleado");
				$data['metodoDePago']=$this->getpath("//@metodoDePago");
				$data['uuid'] = 	$this->getpath("//@UUID");
			}
			

				$rfcOrganizacion= $this->CaptPolizasModel->rfcOrganizacion();
				if($data['rfc'][0] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Ingreso";
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Egreso";
				}
				if($data['nomina']){ $tipoDeComprobante = "Nomina";}
				$fec = explode("T", $data['FechaTimbrado'] );
				if(is_array($data['descripcion']))
					{
						$data['descripcion'] = $data['descripcion'][0];
					}
				$data['tipocomprobante']= $tipoDeComprobante;
				$name = explode('_',$file);
				$auto = explode('/', $name[0]);

				$listaTemporales .= "<tr>
				<td width='50'><img src='xmls/imgs/xml.jpg' width=30><b>".$data['folio']."</b></td>
				<td width='300'><b>".$auto[3]."</b> ".$name[1]."</td>
				<td width=300'><b>".$data['descripcion']."</b></td>
				<td width='60'>".$data['uuid']."</td>
				<td width='60'><center>".$tipoDeComprobante."</center></td>
				<td align='center' width='200'><b style='color:red'>".number_format($data['total'],2,'.',',')."</b></td>
				<td></td>
				<td width='200'><b>".$data['metodoDePago']."</b></td>
				<td width='200'><b>".$fec[0]."</b></td>
				<td width='50'><a href='views/captpolizas/visor.php?data=".urlencode(serialize($data))."' target='_blank'>Ver</a></td>
				<td width='50' style='text-align:center;'><input title='Sólo copiar' type='checkbox' name='radio-$cont' id='copiar-$cont' value='".htmlspecialchars($file)."' class='copiar'></td>
				</tr>";
				$cont++;
			}
		}

		echo $listaTemporales;
	}

	function listaTemporalesProvisionBD()
	{
		$datos = $this->CaptPolizasModel->listaTemporalesBD($_POST);
		$listaTemporales = "
		<tr>
			<th width='50' style='color:white;'>*1_-{}*</th>
			<th width='300'>Folio/Razon</th>
			<th width='50'>Descripcion</th><th width='50'><center>UUID</center></th><th width='50'><center>Tipo</center></th><th width='50'><center>Importe</center></th><th colspan=2>Forma Pago</th><th width='50'><center>Fecha</center></th>
			<td width='50' style='font-weight:bold;font-size:9px;text-align:center;'>
				<button id='' onclick='buttondesclick(\"copiar\")'>Desmarcar</button>
			</td>
			<td width='50' style='font-weight:bold;font-size:9px;text-align:center;'>
				<button id='' onclick='marcarfiltro(\"copiar\")'>Marcar Todos</button>
			</td>
		</tr>";
		$cont = 0;
		while($d = $datos->fetch_object())
		{
			$d->json = str_replace("\\", "", $d->json);
			$json = json_decode($d->json);
			$json = $this->object_to_array($json);

			$razon = $d->receptor;
			if($d->tipo == "Egreso")
				$razon = $d->emisor;

			if($d->version == '3.2')
			{
				$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@descripcion'];
				if(!$descripcion)
					$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0]['@descripcion'];
				$metodoPago = $json['Comprobante']['@metodoDePago'];
			}
			if($d->version == '3.3')
			{
				$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@Descripcion'];
				if(!$descripcion)
					$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0]['@Descripcion'];
				$metodoPago = $json['Comprobante']['@FormaPago'];
			}
			if(strpos($d->xml,'facturas'))
			{
				$xmlnopath = explode('/',$d->xml);
				$d->xml = $xmlnopath[3];
			}
			$url = $this->path()."xmls/facturas/temporales/";


			$listaTemporales .= "<tr>
				<td width='50'><img src='xmls/imgs/xml.jpg' width=30><b></b></td>
				<td width='300'><b>$d->folio</b>/".$razon."</td>
				<td width='300'><b>".$descripcion."</b></td>
				<td width='60'>".$d->uuid."</td>
				<td width='60'><center>".$d->tipo."</center></td>
				<td align='center' width='200'><b style='color:red'>".number_format($d->importe,2,'.',',')."</b></td>
				<td></td>
				<td width='200'><b>".$metodoPago."</b></td>
				<td width='200'><b>".$d->fecha."</b></td>
				<td width='50'><a href='index.php?c=factura&f=visor_factura&uuid=$d->uuid' target='_blank'>Ver</a></td>
				<td width='50' style='text-align:center;'><input title='Sólo copiar' type='checkbox' name='radio-$cont' id='copiar-$cont' value='$url".htmlspecialchars($d->xml)."' class='copiar'></td>
				</tr>";
				$cont++;
		}
		if($cont)
			echo $listaTemporales;
		else
			echo "No se encontraron registros";
	}

	public function object_to_array($data) {
		if (is_array($data) || is_object($data)) {
			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
		return $data;
	}

	function existeXML($nombreArchivo){
		$ruta = $this->path()."xmls/facturas/";
		$directorio = opendir($ruta);
		$rutas="";
		while($carpeta = readdir($directorio)){
			if($carpeta != '.' && $carpeta != '..' && $carpeta != '.file' && $carpeta !='.DS_Store'){
			    if (is_dir($ruta.$carpeta)){
	    				$dir = opendir($ruta.$carpeta);
	    				while($archivo = readdir($dir))
					{
						if($archivo != '.' && $archivo != '..' && $archivo != '.file' && $archivo !='.DS_Store' && $archivo != '.file.rtf'){
							$archivo = str_replace("-Cobro", "", $archivo);
							$archivo = str_replace("-Pago", "", $archivo);
							$archivo = str_replace("Parcial-", "", $archivo);
							$archivo = str_replace("-Nomina", "", $archivo);

							$archiv = $this->quitar_tildes($archivo."");
							$nombreArchiv= $this->quitar_tildes($nombreArchivo);
							$nombreArchiv = strtolower($nombreArchiv);
							$archiv = strtolower($archiv);
							//if (preg_match("/".$nombreArchiv."/i", $archiv)){//i para no diferenciar mayus y minus
							if(strcmp ($nombreArchiv , $archiv ) == 0){

							//if($nombreArchivo == $archivo){
								if($carpeta!="repetidos"){
									if($carpeta!="temporales" && $carpeta!="canceladas" && $carpeta!="documentosbancarios"){
										$poliza =  $this->CaptPolizasModel->GetAllPolizaInfoActiva($carpeta);
										if($poliza!=0){
											switch($poliza['idtipopoliza']){
												case 1: $p="Ingresos"; break;
												case 2: $p="Egresos"; break;
												case 3: $p="Diario"; break;
											}
											$rutas.= " (Poliza:".$poliza['numpol']." ".$p." ".$poliza['fecha'].")";
										}
									}else{
										$rutas.= " (Almacen)";
									}
								}
							}

						}
					}

	    			}
			}

    		}
		return $rutas ;
	}
	function copiaRepetidos(){
		if($_REQUEST['opc']==1){
			$maximo = count($_POST['xml']);
			$maximo = (intval($maximo)-1);
			for($i=0;$i<=$maximo;$i++){
				$name = explode('/', $_POST['xml'][$i]);
				rename($_POST['xml'][$i], $this->path()."xmls/facturas/temporales/".$name[3] );
			}
		}
		array_map('unlink', glob($this->path()."xmls/facturas/repetidos/*"));
		rmdir($this->path()."xmls/facturas/repetidos/");
	}
	function listaRepetidos()
	{
		$listaTemporales = "<tr><td  style='color:white;'>*1_-{}*</td><td></td><td style='font-weight:bold;font-size:9px;text-align:center;'><button id='' onclick='buttondesclick(\"copia\")'>Desmarcar</button></td></tr>";
		$dir = $this->path()."xmls/facturas/repetidos/*";
		$archivos = glob($dir,GLOB_NOSORT);
		array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);
		$cont=1;
		foreach($archivos as $file)
		{
			//if($archivos != '.' AND $archivos != '..' AND $archivo != '.DS_Store' AND $archivo != '.file'){
				$texto 	= file_get_contents($file);
				$name = explode('/', $file);
				$listaTemporales .= "<tr>
				<td><img src='xmls/imgs/xml.jpg' width=30></td>
				<td>".$name[3]."</td>
				<td style='text-align:center;'><input title='Sólo copiar' type='radio' name='radio-$cont' id='copia-$cont' value='".$file."' class='copia'></td>
				</tr>";
				$cont++;
			//}
		}
		echo $listaTemporales;
	}
	function filtroAutomaticas(){
		require('views/captpolizas/filtropolizasautomaticas.php');
	}
	function tipoCambio(){
		$lista=$this->CaptPolizasModel->tipoCambio($_REQUEST['idmoneda'], $_REQUEST['fecha']);
		$tipocambiolista="<option value='0'>Elija una Moneda</option>";
		while ($row = $lista->fetch_assoc()){
			$tipocambiolista.= "<option value='".$row['tipo_cambio']."'>".$row['fecha']." (".$row['tipo_cambio'].")</option>";
		}
		echo $tipocambiolista;
	}
	function bancoDestinoEmpleado(){
		$bancosLista = $this->CaptPolizasModel->listabancos();
		while($lista=$bancosLista->fetch_assoc()){
			echo "<option value=".$lista['idbanco'].">".$lista['nombre']."</option>";
		}
		$bancos  = $this->CaptPolizasModel->datosempleados($_REQUEST['idprove']);
		if($bancos){

			echo "-_-".$bancos['idbanco']."/".$bancos['numeroCuenta'];

		}else{
			echo '-_-0';
		}
	}

	function actualizaProveedores()
	{

		$prv = $this->CaptPolizasModel->getProviders();
		$select = "<option value='0'>---</option>";
		while($p = $prv->fetch_assoc())
		{
			$select .= "<option value='".$p['idPrv']."'>".$p['razon_social']."</option>";
		}
        echo $select;
	}

	function canceladas()
	{
		global $xp;

		$Result = $this->CaptPolizasModel->getUUIDfechas($_POST['inicial'], $_POST['final']);
		$cadenaUUIDS = '';

		while ($registro = $Result->fetch_assoc()) {
			$cadenaUUIDS .= $registro['uuid'].",";
		}

		$cadenaUUIDS = rtrim($cadenaUUIDS,",");
		$dir = $this->path()."xmls/facturas/temporales/*_{$cadenaUUIDS}.xml";

		$archivos = glob($dir,GLOB_BRACE);
		$contador = 0;
		foreach($archivos as $file)
		{
			$carpeta = explode('/',$file);
			$texto = file_get_contents($file);
			$xml = new DOMDocument();
			$xml->loadXML($texto);
			$xp = new DOMXpath($xml);
			$data['rfc'] = $this->getpath("//@rfc");
			$data['uuid'] = $this->getpath("//@UUID");
			$data['total'] = $this->getpath("//@total");
			if(!$this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']))
			{
				copy($this->path()."xmls/facturas/temporales/".basename($file),$this->path()."xmls/facturas/canceladas/".basename($file));
				unlink($this->path()."xmls/facturas/temporales/".basename($file));
				$this->CaptPolizasModel->canceladas($data['uuid']);
				$contador++;
			}
		}
		echo $contador;
	}
	function actualizaCatalogo(){
		switch ($_REQUEST['opc']) {
			case 1://bancos
				$bancos = $this -> CaptPolizasModel -> bancos();
				if($bancos->num_rows>0){
					echo '<option value="0">Seleccione un Banco</option>';
					while($b=$bancos->fetch_array()){ 
						echo "<option value=".$b["account_id"]."/". $b['description']." >".$b['description']."(".$b["manual_code"].") </option>";
					 }
				}else{
					echo 0;
				}
			break;
			
			case 2://provee
				$sqlprov=$this->CaptPolizasModel->proveedor();//proveedores del padron asociados a cuenta contable
				$sqlprov2=$this->CaptPolizasModel->proveedor2();//proveedores del arbol no asociados auna cuenta
				
				echo '<option value="0" >Seleccione un proveedor</option>';
				 while($b=$sqlprov->fetch_array()){  $razon_social=  str_replace('/', ' ', $b['razon_social']);$razon_social = str_replace('-', ' ', $razon_social); 
				
					echo "<option value=".$b['cuenta'].'/'. $b['idPrv'].'/'.$razon_social.">".$b['razon_social']."</option>";
		 		
		 		}while($b=$sqlprov2->fetch_array()){  $description = str_replace('-', ' ', $b['description']);  $description = str_replace('/', ' ', $description); 
					echo "<option value=".$b['account_id'].'-'.$description.">".$b['description']."(".$b['manual_code'].")"."</option>";
		 		
		  		} 
		
			break;
		} 
		
		
	}
	function viewComprobanteTicketAnticipo(){
		$listaanticipos = $this->CaptPolizasModel->ticektAnticipo();
			
		require('views/captpolizas/anticipoticket.php');
		
	}
	function almacenaFacNodeducible(){
		
		$this->CaptPolizasModel->almacenaFactNodedu($idnodedu, $xml);
	}
	function subeComproNodeducible(){
		global $xp; 
		$numeroInvalidos = $numeroValidos = $no_hay_problema = $noOrganizacion = 0;
		$ruta 	= "../cont/xmls/facturas/" . $_REQUEST['idpoli']."/";
		if(!file_exists($ruta))
		{
			mkdir ($ruta,0777);
		}
		if($_FILES["factura"]["size"] > 0){ 
			$file 	= $_FILES['factura']['tmp_name'];
			$texto 	= file_get_contents($file);
			$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
			$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '', $texto);
	        $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '', $texto);
			
			$xml 	= new DOMDocument();
			$xml->loadXML($texto);
			$xp = new DOMXpath($xml);
			if($this->getpath("//@version")){
	        	$data['version'] = $this->getpath("//@version");
	        }else{
	        	$data['version'] = $this->getpath("//@Version");
			}
			$version = $data['version'];
			if($version[0] == '3.3')
			{
				$data['uuid'] 	= $this->getpath("//@UUID");
				if(is_array($data['uuid'])){
					$data['uuid'] 	= $data['uuid'][1];
				}
				$data['folio'] 	= $this->getpath("//@Folio");
				$data['emisor'] = $this->getpath("//@Nombre");
				$data['version'] = $this->getpath("//@Version");
				$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
				$data['descripcion'] = $this->getpath("//@Descripcion");
				
				$data['total'] = $this->getpath("//@Total");
				//$rfc = $this->getpath("//@rfc");
				$data['rfc'] = $this->getpath("//@Rfc");
			}else{
				$data['uuid'] 	= $this->getpath("//@UUID");
				$data['folio'] 	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				$data['version'] = $this->getpath("//@version");
				$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
				$data['descripcion'] = $this->getpath("//@descripcion");
				
				$data['total'] = $this->getpath("//@total");
				//$rfc = $this->getpath("//@rfc");
				$data['rfc'] = $this->getpath("//@rfc");
			}
					
			$rfcOrganizacion= $this->CaptPolizasModel->rfcOrganizacion();
			if($data['rfc'][0] == $rfcOrganizacion['RFC']){
				$nombre = $data['emisor'][1];
			}
			elseif($data['rfc'][1] == $rfcOrganizacion['RFC']){
				$nombre = $data['emisor'][0];
			}
			else{
				$nombre = $data['emisor'][1];
			}
			if($this->valida_xsd($version[0],$xml) && $_FILES['factura']['type'] == "text/xml")
			{ 
				if($version[0] == '3.2'){
					$no_hay_problema = $this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']);
				}else{
					$no_hay_problema = 1;
				}
				if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1]){
					$noOrganizacion = 0;
					echo $_FILES['factura']['name']."(RFC no de Organizacion)";
				}else{ $noOrganizacion = 1; }
				
				$nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
				if($noOrganizacion){
					$validaexiste = $this->existeXML($nombreArchivo);
					if($validaexiste){
						$noOrganizacion = 0;
						echo $_FILES['factura']['name']."Ya existe en $validaexiste";
					}else{ $noOrganizacion = 1; }
				}
				if($noOrganizacion){
					if($no_hay_problema)
					{
						$xmlsubefac = $this->quitar_tildes($nombreArchivo);
						
						if(move_uploaded_file($_FILES["factura"]["tmp_name"], $ruta.$xmlsubefac))
						{
							$actualiza = $this->CaptPolizasModel->almacenaFactNodedu($_REQUEST['nodeducible'], $xmlsubefac);
							if($actualiza == 1){
								echo 1;
							}
						}else{
							echo 0;
						}
					}
					else
					{
						echo $_FILES['factura']['name']."(Cancelada)";
					}
				}
			}else{
				echo $_FILES['factura']['name']."(Estructura incorrecta)";
			}
			
		}
	}
	function facturasNodeducible(){
		global $xp;
		$xmlnodedu = $this->CaptPolizasModel->xmlNodeducible($_REQUEST['idNodeducible']);
		if($xmlnodedu){
		$archivo_str = explode('_',$xmlnodedu);
		$ruta = "../cont/xmls/facturas/" . $_REQUEST['idpoliza']."/".$xmlnodedu;
		$texto 	= file_get_contents($ruta);
		$texto = preg_replace('{<Addenda.*/Addenda>}is', '', $texto);
            $texto = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '', $texto);
		$xml 	= new DOMDocument();
		$xml->loadXML($texto);

		$xp = new DOMXpath($xml);

		//COMIENZA VERSION---------------------------------------
		if($this->getpath("//@version"))
        	$data['version'] = $this->getpath("//@version");
        else
        	$data['version'] = $this->getpath("//@Version");

		$version = $data['version'];
		echo $version[0];
		//TERMINA VERSION---------------------------------------
		if($version[0] == '3.3')
		{
			$data['rfc']           = $this->getpath("//@Rfc");
			$data['total']         = $this->getpath("//@Total");
			$data['nombre']        = $this->getpath("//@Nombre");
			$data['unidad']        = $this->getpath("//@Unidad");
			$data['importe']       = $this->getpath("//@Importe");
			$data['cantidad']      = $this->getpath("//@Cantidad");
			$data['subtotal']      = $this->getpath("//@SubTotal");
			$data['descuento']     = $this->getpath("//@Descuento");
			$data['metodoDePago']  = $this->getpath("//@MetodoPago");
			$data['descripcion2']  = $this->getpath("//@Descripcion");
			$data['nomina']        = $this->getpath("//@NumEmpleado");
			$data['descripcion']   = $this->getpath("//@Descripcion");
			$data['valorUnitario'] = $this->getpath("//@ValorUnitario");
			$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
			$data['impuesto']      = $this->CaptPolizasModel->nombreImpuesto($this->getpath("//@Impuesto"));
		}
		else
		{
			$data['rfc']           = $this->getpath("//@rfc");
			$data['total']         = $this->getpath("//@total");
			$data['nombre']        = $this->getpath("//@nombre");
			$data['unidad']        = $this->getpath("//@unidad");
			$data['importe']       = $this->getpath("//@importe");
			$data['impuesto']      = $this->getpath("//@impuesto");
			$data['subtotal']      = $this->getpath("//@subTotal");
			$data['cantidad']      = $this->getpath("//@cantidad");
			$data['descuento']     = $this->getpath("//@descuento");
			$data['descripcion']   = $this->getpath("//@descripcion");
			$data['descripcion2']  = $this->getpath("//@descripcion");
			$data['nomina']        = $this->getpath("//@NumEmpleado");
			$data['metodoDePago']  = $this->getpath("//@metodoDePago");
			$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
			$data['valorUnitario'] = $this->getpath("//@valorUnitario");
		}

		if(is_array($data['descripcion']))
		{
			$data['descripcion'] = $data['descripcion'][0];
		}

		$rfc = $this->CaptPolizasModel->rfcOrganizacion();

		if($data['rfc'][0] == $rfc['RFC'])
		{
			$tipoDeComprobante = "Ingreso";
		}
		elseif($data['rfc'][1] == $rfc['RFC'])
		{
			$tipoDeComprobante = "Egreso";
		}
		else
		{
			$tipoDeComprobante = "Otro";
		}
		if($data['nomina']){ $tipoDeComprobante = "Nomina";}
		$data['tipocomprobante']= $tipoDeComprobante;
		$listaFacturas = "
			<tr style='text-align:center;height:50px;'>
			<td style='font-size:11px;'>
					<img src='xmls/imgs/xml.jpg' width=30>
				</td>
			<td>". $archivo_str[1] ."</td>
			<td width='100'>
					<a href='views/captpolizas/visor.php?data=".urlencode(serialize($data))."' target='_blank'>Ver</a>
				</td>
			<td style='font-size:11px;'>".$data['descripcion']."</td>
			<td width='60'><center>".$tipoDeComprobante."</center></td>
			<td width=200 style='color:orange;'>
					<b>".number_format($data['total'],2,'.',',')."</b>
				</td>
			<td width=200>".$data['metodoDePago']."</td>
			<td style='font-size:11px'>".$data['FechaTimbrado']."</td>
			<td>
				<a href='javascript:eliminar(\"$ruta\")'><img src='images/eliminado.png' title='Eliminar'></a>
				</td>
		</tr>";
	
				
		echo $listaFacturas ;
		
		}else{
			echo "<tr><td>No hay archivos</td></tr>";
		}
		
	}
	function EliminafacturasNodeducible(){
		if(unlink($_REQUEST['archivo'])){
			$actualiza = $this->CaptPolizasModel->almacenaFactNodedu($_REQUEST['idNodeducible'], "");
			echo $actualiza;
		}else{
			echo 0;
		}
	}

	function guardaFactura($ruta,$nombreArchivo,$uuid,$rfcOrg,$pago,$NumEmpleado)
	{
		include_once("../../libraries/xml2json/xml2json.php");
		$archivo = $ruta."/".$nombreArchivo;
		$temporal = 1;
		if(strpos($ruta, 'temporales') === false)
			$temporal = 0;

		
			$cont_xml = simplexml_load_file($archivo);
	        $json = xmlToArray($cont_xml);


			$cancelada = 1;
			if(strpos($file,'canceladas') === false)
				$cancelada = 0;
				
				$vars['TipoDeComprobante'] = $json['Comprobante']['@TipoDeComprobante'];

				//Version 3.2
				if($json['Comprobante']['@version'])
				{
					if($rfcOrg == $json['Comprobante']['cfdi:Emisor']['@rfc'])
					{
						$er = "E";
						$tipo = "Ingresos";
						$rfc = $json['Comprobante']['cfdi:Receptor']['@rfc'];
					}

					if($rfcOrg == $json['Comprobante']['cfdi:Receptor']['@rfc'])
					{
						$er = "R";
						$tipo = "Egresos";
						$rfc = $json['Comprobante']['cfdi:Emisor']['@rfc'];
					}
					
					if($NumEmpleado != '')
						$tipo = "Nomina";

					$vars['folio'] = $json['Comprobante']['@folio'];
					$vars['serie'] = $json['Comprobante']['@serie'];
					$vars['emisor'] = $json['Comprobante']['cfdi:Emisor']['@nombre'];
					$vars['receptor'] = $json['Comprobante']['cfdi:Receptor']['@nombre'];
					$vars['importe'] = $json['Comprobante']['@total'];
					$vars['fecha'] = $json['Comprobante']['@fecha'];
					$vars['version'] = $json['Comprobante']['@version'];
				}
				//Version 3.3
				if($json['Comprobante']['@Version'])
				{
					if($rfcOrg == $json['Comprobante']['cfdi:Emisor']['@Rfc'])
					{
						$er = "E";
						$tipo = "Ingreso";
						$rfc = $json['Comprobante']['cfdi:Receptor']['@Rfc'];
					}

					if($rfcOrg == $json['Comprobante']['cfdi:Receptor']['@Rfc'])
					{
						$er = "R";
						$tipo = "Egreso";
						$rfc = $json['Comprobante']['cfdi:Emisor']['@Rfc'];
					}
					
					if($NumEmpleado != '')
						$tipo = "Nomina";

					$vars['folio'] = $json['Comprobante']['@Folio'];
					$vars['serie'] = $json['Comprobante']['@Serie'];
					$vars['emisor'] = $json['Comprobante']['cfdi:Emisor']['@Nombre'];
					$vars['receptor'] = $json['Comprobante']['cfdi:Receptor']['@Nombre'];
					$vars['importe'] = $json['Comprobante']['@Total'];
					$vars['fecha'] = $json['Comprobante']['@Fecha'];
					$vars['version'] = $json['Comprobante']['@Version'];
				}

				$vars['uuid'] = $uuid;
				$vars['er'] = $er;
				$vars['tipo'] = $tipo;
				$vars['moneda'] = $json['Comprobante']['@Moneda'];
				$vars['rfc'] = $rfc;
				$vars['xml'] = $archivo;
				$vars['cancelada'] = $cancelada;
				$vars['json'] = json_encode($json,JSON_HEX_APOS);
				$vars['temporal'] = $temporal;

				return $this->CaptPolizasModel->guardaFactura($nombreArchivo,$vars,$pago);
	}

	function agregar_registro(){
		require('views/captpolizas/control_dimensiones.php');
	}

	#Sirve para rellenar los selects en captura para virbac.
	function obtener_selects_virbac(){
		$arr = [];
		$tablas = array('proyecto', 'centro_costo', 'partner_pais', 'evento_contable', 'perdidas_ganancias');
		foreach ($tablas as $index => $valor) {
			$datos = $this->CaptPolizasModel->obtener_datos_tabla("cont_".$valor, 1);
			while ($registro = $datos->fetch_assoc()) {
				$arr[$valor][] = $registro;
			} # // while
		} # //foreach 
		return $arr;
	}

	function add_campo(){
		$tabla = $_POST['tabla'];
		$campo = $_POST['campo'];

		$Result = $this->CaptPolizasModel->agregar_campo_tabla($tabla, $campo);
		echo json_encode($Result);
	}

	function obtener_tabla(){
		$arr = [];
		$tabla = $_POST['tabla'];
		$Result = $this->CaptPolizasModel->obtener_datos_tabla($tabla, $estatus);
		
		while($registro = $Result->fetch_assoc()){
			$arr[] = $registro;
		}
		echo json_encode($arr);
	}

	function modificar_registro(){
		$tabla  = $_POST['tabla'];
		$campo  = $_POST['campo'];
		$activo = $_POST['activo'];
		$id     = $_POST['id'];

		$Result = $this->CaptPolizasModel->modificar_registro($tabla, $campo, $activo, $id);
		echo json_encode($Result);
	}

}
?>
