<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/configuracion.php");

class Configuracion extends Common
{
	public $ConfiguracionModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ConfiguracionModel = new ConfiguracionModel();
		$this->ConfiguracionModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ConfiguracionModel->close();
	}

	//Funcion que genera la vista inicial donde se presentan las polizas del periodo
	function general()
	{
		$ejercicios = $this->ConfiguracionModel->ejercicios();
		if(!$ejercicios->num_rows)
		{
			$esteAnio = date('Y');
			$select = "<option value='$esteAnio'>$esteAnio</option>";
			for($i=1;$i<=6;$i++)
			{
				$select .= "<option value='".(intval($esteAnio)-$i)."'>".(intval($esteAnio)-$i)."</option>";
			}
			require('views/configuracion/ejercicio_inicial.php');
		}
		else
		{
			$infoConf 			= $this->ConfiguracionModel->configuracion();
			$actual 			= $infoConf['id_ejercicio_actual'];
			$actualPeriodo 		= $infoConf['id_periodo_actual'];
			$periodosAbiertos 	= $infoConf['periodos_abiertos'];
			$periodos 			= $this->ConfiguracionModel->periodos($actual);
			$lista_costeo 		= $this->ConfiguracionModel->lista_costeo();
			$existencias 		= $infoConf['salidas_sin_existencia'];
			$idcosteosalida 	= $infoConf['id_costeo_salida'];
			$mod_costo_compras 	= $infoConf['mod_costo_compras'];
			$pol_aut 			= $infoConf['pol_aut'];
			$permitir_cerrados 	= $infoConf['permitir_cerrados'];
			$not_ventas 		= $infoConf['not_ventas'];
			$not_compras 		= $infoConf['not_compras'];
			$not_cortes 		= $infoConf['not_cortes'];
			$dias_canc 			= $infoConf['factura_cancelacion'];
			$dias_emit	 		= $infoConf['factura_emision'];
			require('views/configuracion/general.php');
		}
	}

	function guardaInicial()
	{
		$ejercicio = $_POST['inicial'];
		if($this->ConfiguracionModel->guardaNuevoEjercicio($ejercicio))
		{
			$ejercicio++;
			if($this->ConfiguracionModel->guardaNuevoEjercicio($ejercicio))
				echo $this->ConfiguracionModel->guardaConfInicial();

		}
	}

	function cambiaActual()
	{
		$this->ConfiguracionModel->cambiaActual($_POST['idejercicio']);
	}

	function cambiaActualPeriodo()
	{
		$this->ConfiguracionModel->cambiaActualPeriodo($_POST['idperiodo']);
	}

	function cerrarEjercicio()
	{
		if(intval($_POST['idejercicio']) == 1)
			$anterior = 1;
		else
			$anterior = $this->ConfiguracionModel->cerroAnterior($_POST['idejercicio']);
		
		if($anterior)
		{
			$ejercicio = $_POST['ejerNombre'];
			$this->ConfiguracionModel->cerrarEjercicio($_POST['idejercicio']);
			$ejercicio = $ejercicio+2;
			$this->ConfiguracionModel->guardaNuevoEjercicio($ejercicio);
			echo 1;
		}
		else
		{
			echo 0;
		}
	}

	function cerrarPeriodo()
	{
		if(intval($_POST['idperiodo']) == 1)
			$anterior = 1;
		else
			$anterior = $this->ConfiguracionModel->cerroAnteriorPeriodo($_POST['idperiodo']);
		
		if($anterior)
		{
			$this->ConfiguracionModel->cerrarPeriodo($_POST['idperiodo']);
			echo 1;
		}
		else
		{
			echo 0;
		}
	}

	function periodosAbiertos()
	{
		$this->ConfiguracionModel->periodosAbiertos($_POST['abiertos']);
	}

	function guardar()
	{
		switch ($_GET['t']) 
		{
			case '1':
				$this->ConfiguracionModel->guardar1($_POST['idcosteo'],$_POST['boolexis'],$_POST['idexistencia'],$_POST['mod_costo_compras']);
				break;
			case '2':
				$this->ConfiguracionModel->guardar2($_POST['iva'],$_POST['ieps'],$_POST['ish'],$_POST['ret_iva'],$_POST['ret_isr']);
				break;	
			case '3':
				$this->ConfiguracionModel->guardar3($_POST['compras'],$_POST['ventas'],$_POST['cortes']);
				break;
			case '4':
				$this->ConfiguracionModel->guardar4($_POST['dias_canc'],$_POST['dias_emit']);
				break;									
		}
	}

	function pol_aut()
	{
		$this->ConfiguracionModel->pol_aut($_POST['pol_aut']);
	}

	function ej_cerrados()
	{
		$this->ConfiguracionModel->ej_cerrados($_POST['ej_cer']);
	}

	function reiniciar()
	{
		$conservar = $_POST['conservar'];
		$this->ConfiguracionModel->reiniciar($conservar);
	}

	
	//INICIAN FUNCIONES DE CLASIFICADORES
	function clasificadores()
	{
		$padres = $this->ConfiguracionModel->lista_padres_clas();
		require('views/configuracion/clasificadores.php');
	}

	function listaClas()
	{
		$listaClas = $this->ConfiguracionModel->listaClas();
		$datos=array(); 
		while($l = $listaClas->fetch_object())
		{
			$checked = "";
			$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
			if(intval($l->activo))
			{
				$checked = "checked";
				$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
			}
			switch($l->tipo)
			{
				case 1: $tipo_n = 'Clientes';break;
				case 2: $tipo_n = 'Proveedores';break;
				case 3: $tipo_n = 'Empleados';break;
				case 4: $tipo_n = 'Almacenes';break;

			}
			array_push($datos,array(
				'nombre' => $l->nombre,
				'clave' => $l->clave,
				'npadre' => $l->npadre,
				'tipo' => $tipo_n,
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_clas($l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => "$activo"
				));
		}
		echo json_encode($datos);
	}

	function datos_clas()
	{
		$datos = $this->ConfiguracionModel->datos_clas($_POST['id']);
		$datos = $datos->fetch_assoc();
		echo $datos['nombre']."Ω".$datos['clave']."Ω".$datos['padre']."Ω".$datos['tipo']."Ω".$datos['activo'];
	}

	function guardar_clas()
	{
		echo $this->ConfiguracionModel->guardar_clas($_POST['idclas'],$_POST['nombreclas'],$_POST['claveclas'],$_POST['padreclas'],$_POST['tipoclas'],$_POST['status']);	
	}


	function busca_hijos_clas()
	{
		echo $this->ConfiguracionModel->busca_hijos_clas($_POST['idclas']);
	}

	function busca_padre_clas()
	{
		echo $this->ConfiguracionModel->busca_padre_clas($_POST['id_padre_clas']);
	}
	//TERMINAN FUNCIONES DE CLASIFICADORES
	//INICIAN FUNCIONES DE CLASIFICADORES DE PRODUCTO
	

	function clasificadoresProd()
	{
		require('views/configuracion/clasificadores_prod.php');
	}

	function lista_clas_prod()
	{
		$lista = $this->ConfiguracionModel->lista_clas_prod($_GET['tipo']);
		$datos=array(); 

		if($_GET['tipo'] == 'dep')
		{
			while($l = $lista->fetch_object())
			{
				array_push($datos,array(
					'id' => $l->id,
					'nombre' => $l->nombre,
					'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_clas_prod($l->id,\"dep\")'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
					));
			}
		}

		if($_GET['tipo'] == 'fam')
		{
			while($l = $lista->fetch_object())
			{
				array_push($datos,array(
					'id' => $l->id,
					'nombre' => $l->nombre,
					'departamento' => $l->departamento,
					'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_clas_prod($l->id,\"fam\")'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
					));
			}
		}

		if($_GET['tipo'] == 'lin')
		{
			while($l = $lista->fetch_object())
			{
				$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
				if(intval($l->activo))
					$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
				array_push($datos,array(
					'id' => $l->id,
					'nombre' => $l->nombre,
					'familia' => $l->familia,
					'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_clas_prod($l->id,\"lin\")'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
					'status' => $activo
					));
			}
		}
		echo json_encode($datos);
	}

	function guardar_clas_prod()
	{
		$depende = $id = $status = 0;
		if(isset($_POST['depende'])) $depende = $_POST['depende'];
		if(isset($_POST['id'])) $id = $_POST['id'];
		if(isset($_POST['status'])) $status = $_POST['status'];

		echo $this->ConfiguracionModel->guardar_clas_prod($_POST['nombre'],$_GET['tipo'],$depende,$id,$status);
	}

	function lista_departamentos()
	{
		$opciones = '';
		$lista = $this->ConfiguracionModel->lista_departamentos();
		while($l = $lista->fetch_assoc())
		{
			$opciones .= "<option value='".$l['id']."'>".$l['nombre']."</option>";
		}
		echo $opciones;
	}

	function lista_familias()
	{
		$opciones = '';
		$lista = $this->ConfiguracionModel->lista_familias();
		while($l = $lista->fetch_assoc())
		{
			$opciones .= "<option value='".$l['id']."'>".$l['nombre']."</option>";
		}
		echo $opciones;
	}

	function datos_clas_prod()
	{
		$datos = $this->ConfiguracionModel->datos_clas_prod($_POST['id'],$_POST['tipo']);
		$datos = $datos->fetch_assoc();
		$echo = $datos['id']."Ω".$datos['nombre'];

		if($_POST['tipo'] == "fam")
			$echo .= "Ω".$datos['id_departamento'];

		if($_POST['tipo'] == "lin")
			$echo .= "Ω".$datos['id_familia']."Ω".$datos['activo'];

		echo $echo;
	}
	//TERMINAN FUNCIONES DE CLASIFICADORES DE PRODUCTO

	//INICIAN LAS FUNCIONES DE CARACTERISTICAS DE PRODUCTOS

	function caracteristicasProd()
	{
		require('views/configuracion/caracteristicas_prod.php');
	}
	function lista_car_prod()
	{
		$lista = $this->ConfiguracionModel->lista_car_prod($_GET['tipo']);
		$datos=array(); 

		if($_GET['tipo'] == 'gral')
		{
			while($l = $lista->fetch_object())
			{
				$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
				if(intval($l->activo))
					$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
				array_push($datos,array(
					'id' => $l->id,
					'nombre' => $l->nombre,
					'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_car_prod($l->id,\"gral\")'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
					'status' => $activo
					));
			}
		}

		if($_GET['tipo'] == 'esp')
		{
			while($l = $lista->fetch_object())
			{
				$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
				if(intval($l->activo))
					$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
				array_push($datos,array(
					'id' => $l->id,
					'nombre' => $l->nombre,
					'general' => $l->general,
					'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_car_prod($l->id,\"esp\")'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
					'status' => $activo
					));
			}
		}

		echo json_encode($datos);
	}

	function guardar_car_prod()
	{
		$padre = $id = 0;
		if(isset($_POST['padre'])) $padre = $_POST['padre'];
		if(isset($_POST['id'])) $id = $_POST['id'];

		if($_GET['tipo'] == 'esp')
		{
			//Si el padre esta activo ejecuta
			if(intval($this->ConfiguracionModel->busca_padre_car($padre)))
				$guarda = 1;
			else
				$guarda = 0;
		}
		else
		{
			if(!intval($_POST['status']))
			{
				if(!intval($this->ConfiguracionModel->busca_hijos_car($id)))
					$guarda = 1;
				else
					$guarda = 0;
			}
			else
			{
				$guarda = 1;
			}
		}
		if($guarda)
			echo $this->ConfiguracionModel->guardar_car_prod($_POST['nombre'],$_GET['tipo'],$padre,$id,$_POST['status']);
		else
			echo 0;
	}

	function lista_generales()
	{
		$opciones = '';
		$lista = $this->ConfiguracionModel->lista_generales();
		while($l = $lista->fetch_assoc())
		{
			$opciones .= "<option value='".$l['id']."'>".$l['nombre']."</option>";
		}
		echo $opciones;
	}


	function datos_car_prod()
	{
		$datos = $this->ConfiguracionModel->datos_car_prod($_POST['id'],$_POST['tipo']);
		$datos = $datos->fetch_assoc();
		$echo = $datos['id']."Ω".$datos['nombre']."Ω".$datos['activo'];

		if($_POST['tipo'] == "esp")
			$echo .= "Ω".$datos['id_caracteristica_padre'];

		echo $echo;
	}

	//TERMINAN LAS FUNCIONES DE CARACTERISTICAS DE PRODUCTOS
	//INICIAN FUNCIONES DE TIPOS DE CREDITO
	function credito()
	{
		require('views/configuracion/credito.php');
	}

	function listaCred()
	{
		$listaCred = $this->ConfiguracionModel->listaCred();
		$datos=array(); 
		while($l = $listaCred->fetch_object())
		{
			$checked = "";
			$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
			if(intval($l->activo))
			{
				$checked = "checked";
				$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
			}
		
			array_push($datos,array(
				'id' => utf8_encode($l->id),
				'nombre' => utf8_encode($l->nombre),
				'clave' => utf8_encode($l->clave),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_cred($l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => "$activo"
				));
		}
		echo json_encode($datos);
	}

	function datos_cred()
	{
		$datos = $this->ConfiguracionModel->datos_cred($_POST['id']);
		$datos = $datos->fetch_assoc();
		echo $datos['nombre']."Ω".$datos['clave']."Ω".$datos['activo'];
	}

	function guardar_cred()
	{
		echo $this->ConfiguracionModel->guardar_cred($_POST['idcred'],$_POST['nombrecred'],$_POST['clavecred'],$_POST['status']);	
	}

	//TERMINAN FUNCIONES DE TIPOS DE CREDITO

	//INICIAN FUNCIONES DE LISTAS DE PRECIO
	function listas_precio()
	{
		require('views/configuracion/listas_precio.php');
	}

	function listaPrec()
	{
		$listaPrec = $this->ConfiguracionModel->listaPrec();
		$datos=array(); 
		while($l = $listaPrec->fetch_object())
		{
			$checked = "";
			$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
			if(intval($l->activo))
			{
				$checked = "checked";
				$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
			}
			if(intval($l->descuento))
				$l->descuento = "Si";
			else
				$l->descuento = "No";
		
			array_push($datos,array(
				'id' => utf8_encode($l->id),
				'nombre' => utf8_encode($l->nombre),
				'clave' => utf8_encode($l->clave),
				'porcentaje' => utf8_encode($l->porcentaje),
				'descuento' => utf8_encode($l->descuento),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_listaprec($l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => "$activo",
				'tipo' => (utf8_encode($l->tipo) == 2 ) ? "Manual" : "Calculado"
				));
		}
		echo json_encode($datos);
	}

	function datos_listaprec()
	{
		$datos = $this->ConfiguracionModel->datos_listaprec($_POST['id']);
		$datos = $datos->fetch_assoc();
		echo $datos['nombre']."Ω".$datos['clave']."Ω".$datos['porcentaje']."Ω".$datos['descuento']."Ω".$datos['activo']."Ω". ($datos['tipo'] == 2 ? 2 : 1) ;
	}

	function guardar_listaprec()
	{
		echo $this->ConfiguracionModel->guardar_listaprec($_POST['idlistaprec'],$_POST['nombrelistaprec'],$_POST['clavelistaprec'],$_POST['porcentaje'],$_POST['descuento'],$_POST['status'],$_POST['tipo']);	
	}

	//TERMINAN FUNCIONES DE LISTAS DE PRECIO
	//INICIAN FUNCIONES DE MEDIDAS Y PESO
	function medida()
	{
		require('views/configuracion/medida.php');
	}

	function listaMedida()
	{
		$listaMedida = $this->ConfiguracionModel->listaMedida();
		$datos=array(); 
		while($l = $listaMedida->fetch_object())
		{
			$checked = "";
			$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
			if(intval($l->activo))
			{
				$checked = "checked";
				$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
			}
			
			if($l->unidad_n == '')
				$l->unidad_n = '---';
			array_push($datos,array(
				'clave' => utf8_encode($l->clave),
				'nombre' => utf8_encode($l->nombre),
				'factor' => utf8_encode($l->factor),
				'unidad_n' => utf8_encode($l->unidad_n),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_medida($l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => "$activo"
				));
		}
		echo json_encode($datos);
	}

	function datos_medida()
	{
		$datos = $this->ConfiguracionModel->datos_medida($_POST['id']);
		$datos = $datos->fetch_assoc();
		echo $datos['clave']."Ω".$datos['nombre']."Ω".$datos['factor']."Ω".$datos['unidad_base']."Ω".$datos['activo']."Ω".$datos['codigo_sat'];
	}

	function guardar_medida()
	{
		echo $this->ConfiguracionModel->guardar_medida($_POST['idmedida'],$_POST['clavemedida'],$_POST['nombremedida'],$_POST['factor'],$_POST['unidad_base'],$_POST['status'],$_POST['calve_sat']);	
	}

	function lista_unidades_base()
	{
		$opciones = '';
		$lista = $this->ConfiguracionModel->lista_unidades_base();
		while($l = $lista->fetch_assoc())
		{
			$opciones .= "<option value='".$l['id']."'>".$l['clave']." / ".$l['nombre']."</option>";
		}
		echo $opciones;
	}

	//TERMINAN FUNCIONES DE MEDIDAS Y PESO
	//INICIAN FUNCIONES DE IMPUESTOS
	function impuestos()
	{
		require('views/configuracion/impuestos.php');
	}

	function listaImpuestos()
	{
		$listaImpuestos = $this->ConfiguracionModel->listaImpuestos();
		$datos=array(); 
		while($l = $listaImpuestos->fetch_object())
		{
			$checked = "";
			$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
			if(intval($l->activo))
			{
				$checked = "checked";
				$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
			}
			
			array_push($datos,array(
				'nombre' => utf8_encode($l->nombre),
				'valor' => utf8_encode($l->valor),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_impuesto($l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => "$activo"
				));
		}
		echo json_encode($datos);
	}

	function datos_impuesto()
	{
		$datos = $this->ConfiguracionModel->datos_impuesto($_POST['id']);
		$datos = $datos->fetch_assoc();
		echo $datos['nombre']."Ω".$datos['valor']."Ω".$datos['activo'];
	}

	function guardar_impuesto()
	{
		echo $this->ConfiguracionModel->guardar_impuesto($_POST['idimpuesto'],$_POST['nombre'],$_POST['valor'],$_POST['status']);	
	}
	//TERMINAN FUNCIONES DE IMPUESTOS
	//INICIAN FUNCIONES DE PROVEEDORES
	function proveedores()
	{
		require('views/configuracion/proveedores.php');
	}

	function listaProveedores()
	{
		$listaProveedores = $this->ConfiguracionModel->listaProveedores();
		$datos=array(); 
		while($l = $listaProveedores->fetch_object())
		{
			$activo = "<span class='label label-danger' id='span-$l->id'>*Inactivo</span>";
			if(intval($l->estatus))
			{
				$activo = "<span class='label label-default' id='span-$l->id'>*Activo</span>";
			}
			
			array_push($datos,array(
				'codigo' => utf8_encode($l->codigo),
				'razon_social' => utf8_encode($l->razon_social),
				'rfc' => utf8_encode($l->rfc),
				'municipio' => utf8_encode($l->municipio),
				'estado' => utf8_encode($l->estado),
				'telefono' => utf8_encode($l->telefono),
				'email' => utf8_encode($l->email),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar_proveedor($l->idPrv)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => "$activo"
				));
		}
		echo json_encode($datos);
	}

	function datos_proveedor()
	{
		$datos = $this->ConfiguracionModel->datos_medida($_POST['id']);
		$datos = $datos->fetch_assoc();
		echo $datos['clave']."Ω".$datos['nombre']."Ω".$datos['factor']."Ω".$datos['unidad_base']."Ω".$datos['activo'];
	}

	function guardar_proveedor()
	{
		echo $this->ConfiguracionModel->guardar_medida($_POST['idmedida'],$_POST['clavemedida'],$_POST['nombremedida'],$_POST['factor'],$_POST['unidad_base'],$_POST['status']);	
	}
	//TERMINAN FUNCIONES DE PROVEEDORES

	function listaClasificacionesProv()
	{
		$listaClas = $this->ConfiguracionModel->listaClas2(2);
		$listado = "<option value='0'>Ninguno</option>";
		while($l = $listaClas->fetch_assoc())
		{
			$listado .= "<option value='".$l['id']."'>(".$l['clave'].") ".$l['nombre']."</option>";
		}

		echo $listado;
	}

	function listaClasificacionesEmp()
	{
		$listaClas = $this->ConfiguracionModel->listaClas2(3);
		$listado = "<option value='0'>Ninguno</option>";
		while($l = $listaClas->fetch_assoc())
		{
			$listado .= "<option value='".$l['id']."'>(".$l['clave'].") ".$l['nombre']."</option>";
		}

		echo $listado;
	}

	function polizas()
	{
		$infoConf = $this->ConfiguracionModel->configuracion();
		$checked = "";
		if(intval($infoConf['pol_autorizacion']))
			$checked = "checked";
		$tiene_bancos = $this->ConfiguracionModel->tiene_bancos();
		$tiene_conf = $this->ConfiguracionModel->configuracion();
		if($tiene_conf['id'])
			require('views/configuracion/polizas.php');	
		else
			echo "<script language='javascript'>function ira(){window.parent.agregatab('../../modulos/appministra/index.php?c=configuracion&f=general&p=0','Configuracion Avanzada','',145)}</script><br /><hr><center><b style='color:red;'>Se requiere completar la configuración avanzada para poder configurar este modulo.</b><br /><a href='javascript:ira()'>Ir a Configuracion Avanzada</a></center><hr>";
	}

	function polizas_pagos()
	{
		require('views/configuracion/polizas_pagos.php');	
	}

	function guardar_gral_pol()
	{
		echo $this->ConfiguracionModel->guardar_gral_pol($_POST);
	}

	function getCuentas()
	{
		echo $this->ConfiguracionModel->getCuentas();
	}

	function getCuentasAsoc()
	{
		$datos=array();
		$pagos = 0; 
		if(isset($_POST['pagos']))
			$pagos = 1;
		$res = $this->ConfiguracionModel->getCuentasAsoc($_POST['tipo'],$pagos);
		while($r = $res->fetch_assoc())
		{
			$vinculado = $r['nom_dato']." ".$r['nombre_impuesto'];
			$abono = "";
            $cargo = "";
			if(intval($r['tipo_movto']) == 1)
            {
                $abono = "<center><span class='glyphicon glyphicon-ok'></span></center>";
            }

            if(intval($r['tipo_movto']) == 2)
            {
                $cargo = "<center><span class='glyphicon glyphicon-ok'></span></center>";
            }
            $r['description'] = htmlspecialchars($r['description'], ENT_NOQUOTES, "UTF-8");
			array_push($datos,array(
				'manual_code' => utf8_encode($r['manual_code']),
				'description' => $r['description'],
				'cargo' => utf8_encode($cargo),
				'abono' => utf8_encode($abono),
				'vinculado' => utf8_encode($vinculado),
				'modificar' => "<center><span class='mano glyphicon glyphicon-pencil' onclick='modificar(".$r['id'].",".$_POST['tipo'].")'></span></center>",
				'eliminar' => "<center><span class='mano glyphicon glyphicon-trash' onclick='eliminar(".$r['id'].",".$_POST['tipo'].")'></span></center>"
				));
		}
		echo json_encode($datos);
	}

	function getPolizasComprasLista()
	{
		$datos=array(); 
		$res = $this->ConfiguracionModel->getPolizasComprasLista($_POST['n']);
		while($r = $res->fetch_assoc())
		{
			$automatica = 'Manual';
			if(intval($r['automatica']))
				$automatica = 'Automatica';

			$por_mov= 'Por rango de dias';
			if(intval($r['poliza_por_mov']) == 1)
				$por_mov= 'Por documento';

         
			array_push($datos,array(
				'nombre_poliza' => utf8_encode($r['nombre_poliza']),
				'tipo_poliza' => utf8_encode($r['tipo_poliza']),
				'gasto' => utf8_encode($r['gasto']),
				'automatica' => utf8_encode($automatica),
				'por_mov' => utf8_encode($por_mov),
				'dias' => utf8_encode($r['dias']),
				'modificar' => "<center><span class='mano glyphicon glyphicon-pencil' onclick='abrir_polizas_compras(".$r['id'].")'></span></center>",
				'eliminar' => "<center><span class='mano glyphicon glyphicon-trash' onclick='eliminar_pc(".$r['id'].")'></span></center>"
				));
		}
		echo json_encode($datos);
	}

	function agregar_cuenta()
	{
		echo $this->ConfiguracionModel->agregar_cuenta($_POST);
	}

	function getDatosVinc()
	{
		echo $this->ConfiguracionModel->getDatosVinc();
	}

	function getImpuestos()
	{
		echo $this->ConfiguracionModel->getImpuestos();
	}

	function eliminar_cuenta()
	{
		$pagos = 0;
		if(isset($_POST['pagos']))
			$pagos = 1;
		echo $this->ConfiguracionModel->eliminar_cuenta($_POST['id'],$pagos);
	}

	function datos_cuenta()
	{
		$pagos = 0;
		if(isset($_POST['pagos']))
			$pagos = 1;
		$res = $this->ConfiguracionModel->datos_cuenta($_POST['id'],$pagos);
		$res = $res->fetch_object();
		echo "$res->id_cuenta**/**$res->tipo_movto**/**$res->id_dato**/**$res->nombre_impuesto";
	}

	function guardar_poliza()
	{
		echo $this->ConfiguracionModel->guardar_poliza($_POST);
	}

	function tipoGastos()
	{
		$res = $this->ConfiguracionModel->tipoGastos();
		$select = "<option value='0'>No Asociar</option>";
		while($r = $res->fetch_object())
		{	
			$select .= "<option value='$r->id'>($r->codigo) $r->nombreclasificador</option>";
		}
		echo $select;
	}

	function getInfoPoliza()
	{
		$res = $this->ConfiguracionModel->getInfoPoliza($_POST['tipo']);
		$res = $res->fetch_assoc(); 
		echo $res['id']."**/**".$res['id_tipo_poliza']."**/**".$res['id_gasto']."**/**".$res['nombre_poliza']."**/**".$res['automatica']."**/**".$res['poliza_por_mov']."**/**".$res['dias'];
	}

	function getInfoPolizaPagos()
	{
		$res = $this->ConfiguracionModel->getInfoPolizaPagos($_POST['tipo']);
		$res = $res->fetch_assoc(); 
		echo $res['id']."**/**".$res['id_tipo_poliza']."**/**".$res['id_gasto']."**/**".$res['nombre_poliza']."**/**".$res['automatica']."**/**".$res['poliza_por_mov']."**/**".$res['dias'];
	}

	function nuevaPoliza()
	{
		echo $this->ConfiguracionModel->nuevaPoliza();
	}

	function eliminar_poliza()
	{
		echo $this->ConfiguracionModel->eliminar_poliza($_POST['id']);
	}


	//FUNCIONES DE LAS POLIZAS MANUALES
	function polizas_manuales()
	{
		$conexion = $this->ConfiguracionModel->conexion_acontia();
		$conexion = $conexion->fetch_assoc();
		if(intval($conexion['conectar_acontia']))
		{
			$gastos = $this->ConfiguracionModel->tipo_gastos();
			$segs = $this->ConfiguracionModel->segmentos();
			$clientes = $this->ConfiguracionModel->clientes();
			$clientes_cxc = $this->ConfiguracionModel->clientes();
			$proveedores = $this->ConfiguracionModel->proveedores();
			$proveedores_cxp = $this->ConfiguracionModel->proveedores();
			require('views/configuracion/polizas_manuales.php');	
		}
		else
		{
			echo "<b style='color:red;'>No tienes conexion con el modulo de Acontia.</b>";
		}
		
	}

	function getFacturasVentasCompras()
	{
		$datos=array(); 
		$tipo = $_POST['tipo'];
		/*if(intval($_POST['tipo_venta']) == 2)
			$tipo = 1;*/



		$automatica = $this->ConfiguracionModel->es_manual($tipo,$_POST['gasto']);
        $automatica = $automatica->fetch_assoc();
        if($automatica['automatica'] == '')
        	$automatica['automatica'] = 1;
        //Si es automatica y se genera por documento
        if(!intval($automatica['automatica']))
        {
        	if(intval($_POST['tipo']) == 1)
        		$res = $this->ConfiguracionModel->getFacturasVentas($_POST['clienteProv'],$_POST['tipo_venta'],$_POST['rango']);

        	if(intval($_POST['tipo']) == 2)
        		$res = $this->ConfiguracionModel->getFacturasCompras($_POST['gasto'],$_POST['clienteProv'],$_POST['rango']);

			while($r = $res->fetch_assoc())
			{
				//Ventas
				if(intval($_POST['tipo']) == 1)
				{
					$ventas_compras = $r['idSale'];
					if(!intval($r['idSale']))
					{
						$ventas_compras = $this->ConfiguracionModel->ids_ventas($r['id']);
					}
					$chk = 'vts';
					$class = 'ventas';
					$folio = $r['folio'];
				}
				//Compras
				if(intval($_POST['tipo']) == 2)
				{
					$ventas_compras = $r['id_oc'];
					$chk = 'cps';
					$class = 'compras';
					$folio = explode('_',$r['folio']);
					if($folio[2])
						$folio = $folio[2];
					else
						$folio = $folio[1];
					
					$folio = str_replace('.xml', '', $folio);
				}
				
				array_push($datos,array(
					'nombre' => utf8_encode($r['ClienteProv']),
					'folio' => utf8_encode($folio),
					'fecha' => utf8_encode($r['fecha']),
					'ventas' => utf8_encode($ventas_compras),
					'seleccionar' => "<center><input type='checkbox' id='chk_$chk-".$r['id']."' class='check_$class'></center>"
					));
			}
        }
		echo json_encode($datos);
	}

	function getTodosDemas()
	{
		$tipo = $_POST['tipo'];
		$datos=array(); 
		$automatica = $this->ConfiguracionModel->es_manual($tipo);
        $automatica = $automatica->fetch_assoc();

        //Si es automatica y se genera por documento
        if(!intval($automatica['automatica']))
        {
        	$res = $this->ConfiguracionModel->getTodosDemas($tipo,$_POST['clienteProv'],$_POST['rango']);
			while($r = $res->fetch_assoc())
			{
				if(intval($tipo) == 3 || intval($tipo) == 4)
				{
					if($r['id_tipo'])
						$docu = $r['id_documento'] . " (Facturada)";
					else
						$docu = $r['id_documento'] . " (Cargo)";

					$uno 	= utf8_encode($r['id']." / ".$r['concepto']);
					$dos 	= utf8_encode($r['fecha_pago']);
					$tres 	= $r['monto'];
					$cuatro	= $docu;
					$a 		= "cxp";
					if(intval($tipo) == 4)
						$a = "cxc";
				}

				if(intval($tipo) == 5 || intval($tipo) == 6 || intval($tipo) == 7)
				{
					$uno 	= utf8_encode($r['id']." / ".$r['referencia']);
					$dos 	= utf8_encode($r['producto']);
					$tres 	= utf8_encode($r['importe']);
					$cuatro	= utf8_encode($r['fecha']);
					$a 		= "entrada";
					if(intval($tipo) == 6)
						$a = "salida";
					if(intval($tipo) == 7)
						$a = "traspaso";
				}

				if(intval($tipo) == 8 || intval($tipo) == 9)
				{
					$uno 	= utf8_encode($r['id']);
					$dos 	= utf8_encode($r['folio']);
					$tres 	= utf8_encode($r['fecha']);
					$ventas = $r['idSale'];
					if(!intval($r['idSale']))
					{
						$ventas = $this->ConfiguracionModel->ids_ventas($r['id']);
					}
					$cuatro	= utf8_encode($ventas);
					$a 		= "cancelacion";
					if(intval($tipo) == 9)
						$a = "devolucion";
				}
				
				array_push($datos,array(
					'uno' => $uno,
					'dos' => $dos,
					'tres' => $tres,
					'cuatro' => $cuatro,
					'seleccionar' => "<center><input type='checkbox' id='chk_$a-".$r['id']."' class='check_$a'></center>"
					));
			}
        }
		echo json_encode($datos);
	}

	function guardar_poliza_manual()
	{
		echo $this->ConfiguracionModel->guardar_poliza_manual($_POST);
	}

	// === CH@
	function implementacionInicial(){
		$nombre_instacia = 'NetwarMonitor';
        require('views/configuracion/implementacion_inicial.php'); 
    }
	// === CH@ FIN
}


?>
