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

	function ver_app(){
		$app = $_POST['app'];
		$app = $this->ConfiguracionModel->ver_app($app);
		echo json_encode($app);
	}

	function configuracion()
	{

		////$instancias  = $this->ConfiguracionModel->instancias();
		////echo json_encode($instancias);
		require('views/configuracion/configuracion.php');
	}
	
	function reload_pasos(){
		$id_app 	= $_POST['id_app'];
		$reload_pasos  = $this->ConfiguracionModel->reload_pasos($id_app);
		echo json_encode($reload_pasos);
	}

	function reload_app(){
		$apps  = $this->ConfiguracionModel->reload_app();
		echo json_encode($apps);
	}
	function datos_act(){
		$id_act 	= $_POST['id_act'];
		$datos_act  = $this->ConfiguracionModel->datos_act($id_act);
		echo json_encode($datos_act);
	}


	function reload_actividades(){
		$id_app 	= $_POST['id_app'];
		$id_paso 	= $_POST['id_paso'];
		$reload_actividades  = $this->ConfiguracionModel->reload_actividades($id_app,$id_paso);
		echo json_encode($reload_actividades);
	}
	
	function save_app(){
		$nombre 	= $_POST['nombre'];
		$solucion 	= $_POST['solucion'];
		$desc 		= $_POST['desc'];
		$save_app  	= $this->ConfiguracionModel->save_app($nombre,$solucion,$desc);
		echo $save_app;

	}
	function edit_pasos(){
		$paso 		= $_POST['paso'];
		$nombre 	= $_POST['nombre'];		
		$link 		= $_POST['link'];
		$desc_larga = $_POST['desc_larga'];
		$id_app 	= $_POST['id_app'];
		$edit_pasos = $this->ConfiguracionModel->edit_pasos($paso,$nombre,$link,$desc_larga,$id_app);
		echo $edit_pasos;
	}
	function save_pasos(){
		$paso 		= $_POST['paso'];
		$nombre 	= $_POST['nombre'];		
		$link 		= $_POST['link'];
		$desc_larga = $_POST['desc_larga'];
		$id_app 	= $_POST['id_app'];
		$id_paso = $this->ConfiguracionModel->save_pasos($paso,$nombre,$link,$desc_larga,$id_app);
		echo $id_paso;

	}
	function save_actividad(){
		$nombre 	= $_POST['nombre'];		
		$menu 		= $_POST['menu'];		
		$desc 		= $_POST['desc'];
		$link 		= $_POST['link'];
		$estatus 	= $_POST['estatus'];
		$opcional 	= $_POST['opcional'];
		$idpasoR 	= $_POST['idpasoR'];
		$save_actividad = $this->ConfiguracionModel->save_actividad($nombre,$menu,$desc,$link,$opcional,$estatus,$idpasoR);
		echo $save_actividad;

	}
	function edit_actividad(){
		$nombre 	= $_POST['nombre'];		
		$menu 		= $_POST['menu'];		
		$desc 		= $_POST['desc'];
		$link 		= $_POST['link'];
		$estatus 	= $_POST['estatus'];
		$opcional 	= $_POST['opcional'];
		$idpasoR 	= $_POST['idpasoR'];
		$id_act 	= $_POST['id_act'];
		$edit_actividad = $this->ConfiguracionModel->edit_actividad($nombre,$menu,$desc,$link,$opcional,$estatus,$idpasoR,$id_act);
		echo $edit_actividad;
	}

	function estatus_app(){
		$id_app 	= $_POST['id_app'];
		$estatus 	= $_POST['estatus'];

		$estatus_app  	= $this->ConfiguracionModel->estatus_app($id_app,$estatus);
		echo $estatus_app;
	}

	function estatus_act(){
		$id_act 	= $_POST['id_act'];
		$estatus 	= $_POST['estatus'];

		$estatus_act  	= $this->ConfiguracionModel->estatus_act($id_act,$estatus);
		echo $estatus_act;
	}

	function pasos()
	{
		require('views/configuracion/pasos.php');
	}

	function select_menu(){
		$menus  = $this->ConfiguracionModel->select_menu();
		echo json_encode($menus);
	}
}



?>