<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class UnidadesTree extends CI_Controller {

	public function index()
	{
		$this->load->model('Unidades_tree');	
		$result = $this->Unidades_tree->unidadesBasicas(0);
		
		$this->load->view('unidadesTree/tree',array('uniBasicas'=>$result));
	}

	public function desglozadas()
	{
		$ids = $_POST["seleccionado"];
		$this->load->model('Unidades_tree');	
		$result = $this->Unidades_tree->unidades($ids);

		if($result[1]==true)
		{
			$result[1] = $this->Unidades_tree->unidadesBasicas(1);
		}
		
		echo json_encode($result);
	
	}
	public function quitar()
	{
		$id = $_POST["id"];
		$unidad = $_POST["unidad"];

		$this->load->model('Unidades_tree');	
		$result = $this->Unidades_tree->quitar($id,$unidad);

		$basicas = $this->Unidades_tree->unidadesBasicas(0);

		echo json_encode(array($result,$basicas));
	}

	public function agregar()
	{
		$id = $_POST["id"];
		$unidad = $_POST["unidad"];

		$this->load->model('Unidades_tree');	
		$result = $this->Unidades_tree->agregar($id,$unidad);

		$basicas = $this->Unidades_tree->unidadesBasicas(0);

		echo json_encode(array($result,$basicas));
	}

	public function nueva()
	{
		$nombre = $_POST['nombre'];

		$this->load->model('Unidades_tree');	
		$result = $this->Unidades_tree->nueva($nombre);

		$basicas = $this->Unidades_tree->unidadesBasicas(0);

		echo json_encode(array($result,$basicas));
	}

	public function modifica()
	{
		$nombre = $_POST["nombre"];
		$id = $_POST["id"];

		$this->load->model('Unidades_tree');
		$result = $this->Unidades_tree->modifica($id,$nombre);

		echo json_encode($result);
	}

	public function eliminar()
	{
		$nombre = $_POST["nombre"];
		$id = $_POST["id"];

		$this->load->model('Unidades_tree');
		$result = $this->Unidades_tree->eliminar($id,$nombre);

		echo json_encode($result);
	}

	public function uBase()
	{
		$tree = $_POST["tree"];
		$id = $_POST["id"];

		$this->load->model('Unidades_tree');
		$result = $this->Unidades_tree->uBase($tree,$id);

		echo json_encode($result);
	}
}

