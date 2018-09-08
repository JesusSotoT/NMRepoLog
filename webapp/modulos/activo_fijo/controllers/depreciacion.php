<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/depreciacion.php");

class Depreciacion extends Common
{
	public $DepreciacionModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->DepreciacionModel = new DepreciacionModel();
		$this->DepreciacionModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->DepreciacionModel->close();
	}

	//Funcion que genera la vista inicial donde se presentan las polizas del periodo
	function listado()
	{
		$lista_altas = $this->DepreciacionModel->lista_altas();
		$categorias = $this->DepreciacionModel->categorias();
		$responsables = $this->DepreciacionModel->responsables();
		$segmentos = $this->DepreciacionModel->segmentos();
		$sucursales = $this->DepreciacionModel->sucursales();
		$tasa_depr = $this->DepreciacionModel->tasa_depr();
		$tasa_depr2 = $this->DepreciacionModel->tasa_depr();
		$cuentasGral = $this->DepreciacionModel->get_cuentas(0);
		$cuentasAc = $this->DepreciacionModel->get_cuentas(1);
		$cuentasRes = $this->DepreciacionModel->get_cuentas(4);
		$cuentasDed = $this->DepreciacionModel->get_cuentas(0);
		$cuentasOrAc = $this->DepreciacionModel->get_cuentas(5);
		$cuentasOrDe = $this->DepreciacionModel->get_cuentas(5);
		require('views/depreciacion/lista_depreciacion.php');
	}

	function lista_activos()
	{
		$lista = $this->DepreciacionModel->lista_activos();
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			if(!intval($l->activo))
				$activo = "<span class='label label-danger'>Inactivo</span>";
			else
				$activo = "<span class='label label-success'>Activo</span>";
			
			array_push($datos,array(
				'no_activo' => utf8_encode($l->codigo),
				'categoria' => utf8_encode($l->id_categoria),
				'descripcion' => utf8_encode($l->descripcion),
				'modelo' => utf8_encode($l->modelo),
				'marca' => utf8_encode($l->marca),
				'fecha' => utf8_encode($l->fecha_fact),
				'moi' => utf8_encode($l->moi),
				'ubicacion' => utf8_encode($l->ubicacion),
				'responsable' => utf8_encode($l->id_responsable),
				'concepto' => utf8_encode($l->id_concepto_alta),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar($l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function lista_facturas()
	{
		global $xp;
		$dir = "../cont/xmls/facturas/temporales/*.xml";
		$facturas = "<option value='0'>Ninguna</option>";
		// Abrir un directorio, y proceder a leer su contenido
		$archivos = glob($dir,GLOB_NOSORT);
		array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);
		foreach($archivos as $file) 
		{
			$texto 	= file_get_contents($file);
			$xml 	= new DOMDocument();
			$xml->loadXML($texto);
			$xp = new DOMXpath($xml);
			$data['total'] = $this->getpath("//@total");
			$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
			$data['rfc'] = $this->getpath("//@rfc");

			$soloarchivo = explode('temporales/',$file);
			$datos_fac = explode("_",$soloarchivo[1]);
			$uuid = str_replace('.xml', '', $datos_fac[2]);
			$info_prv = $this->DepreciacionModel->id_prv($data['rfc'][0]);
			$fecha = explode('T', $data['FechaTimbrado']);
			$fecha = $fecha[0];
			if($info_prv['razon_social'] == '')
			{
				$info_prv['razon_social'] = $this->getpath("//@nombre");
				$info_prv['razon_social'] = $info_prv['razon_social'][0];
			}

			$facturas .= "<option folio='$datos_fac[0]' proveedor='".$info_prv['razon_social']."' id_proveedor='".$info_prv['idPrv']."' uuid='$uuid' fecha_fac='$fecha' moi='".$data['total']."' value='".$soloarchivo[1]."'>".$soloarchivo[1]."</option>";
		}
		echo $facturas;
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

	function guardar()
	{
		echo $this->DepreciacionModel->guardar($_POST);
	}

	function guardar_cont()
	{
		echo $this->DepreciacionModel->guardar_cont($_POST);
	}

	function guardar_fiscal()
	{
		echo $this->DepreciacionModel->guardar_fiscal($_POST);
	}

	function getDatosActivo()
	{
		echo json_encode($this->DepreciacionModel->getDatosActivo($_POST['id_reg']));
	}

	function getDatosActivoContFisc()
	{
		echo json_encode($this->DepreciacionModel->getDatosActivoContFisc($_POST['id_reg'],$_POST['base']));
	}
}
?>
