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
		require('views/configuracion/catalogos.php');
	}

	function lista_altas()
	{
		$lista = $this->ConfiguracionModel->lista_altas_bajas('altas');
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			if(!intval($l->activo))
				$activo = "<span class='label label-danger'>Inactivo</span>";
			else
				$activo = "<span class='label label-success'>Activo</span>";
			
			array_push($datos,array(
				'id' => utf8_encode($l->id),
				'codigo' => utf8_encode($l->codigo),
				'nombre' => utf8_encode($l->nombre),
				'activo' => $activo,
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar(\"altas\",$l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function lista_bajas()
	{
		$lista = $this->ConfiguracionModel->lista_altas_bajas('bajas');
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			if(!intval($l->activo))
				$activo = "<span class='label label-danger'>Inactivo</span>";
			else
				$activo = "<span class='label label-success'>Activo</span>";

			array_push($datos,array(
				'id' => utf8_encode($l->id),
				'codigo' => utf8_encode($l->codigo),
				'nombre' => utf8_encode($l->nombre),
				'activo' => $activo,
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar(\"bajas\",$l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function lista_bienes()
	{
		$lista = $this->ConfiguracionModel->lista_bienes();
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			if(!intval($l->activo))
				$activo = "<span class='label label-danger'>Inactivo</span>";
			else
				$activo = "<span class='label label-success'>Activo</span>";

			array_push($datos,array(
				'id' => utf8_encode($l->id),
				'codigo' => utf8_encode($l->codigo),
				'nombre' => utf8_encode($l->nombre),
				'activo' => $activo,
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar(\"bienes\",$l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function lista_formulas()
	{
		$lista = $this->ConfiguracionModel->lista_formulas();
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			if(!intval($l->activo))
				$activo = "<span class='label label-danger'>Inactivo</span>";
			else
				$activo = "<span class='label label-success'>Activo</span>";

			array_push($datos,array(
				'id' => utf8_encode($l->id),
				'codigo' => utf8_encode($l->nombre),
				'nombre' => utf8_encode($l->formula),
				'activo' => $activo,
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar(\"formulas\",$l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function lista_inpc()
	{
		$lista = $this->ConfiguracionModel->lista_inpc();
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			if(!intval($l->activo))
				$activo = "<span class='label label-danger'>Inactivo</span>";
			else
				$activo = "<span class='label label-success'>Activo</span>";

			array_push($datos,array(
				'id' => utf8_encode($l->anio),
				'codigo' => utf8_encode($l->mes),
				'nombre' => utf8_encode($l->indice),
				'activo' => $activo,
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar(\"inpc\",$l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function lista_depr()
	{
		$lista = $this->ConfiguracionModel->lista_depr();
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			if(!intval($l->activo))
				$activo = "<span class='label label-danger'>Inactivo</span>";
			else
				$activo = "<span class='label label-success'>Activo</span>";

			array_push($datos,array(
				'id' => utf8_encode($l->id),
				'codigo' => utf8_encode($l->nombre),
				'nombre' => utf8_encode($l->porciento),
				'activo' => $activo,
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-example-modal-sm' onclick='modificar(\"depr\",$l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function guardar_registro()
	{
		if(!intval($_POST['id_reg']))
			echo $this->ConfiguracionModel->guardar_registro($_POST);
		else
			echo $this->ConfiguracionModel->actualizar_registro($_POST);
	}

	function get_data_reg()
	{
		$datos = $this->ConfiguracionModel->get_data_ref($_POST);
		$return = $datos[0]."**//**".$datos[1]."**//**".$datos[2];
		if($_POST['tipo'] == "inpc")
			$return .= "**//**".$datos[4]."**//**".$datos[3];
		else
			$return .= "**//**".$datos[3];
		echo $return;
	}

}


?>
