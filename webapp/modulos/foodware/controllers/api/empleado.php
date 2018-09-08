<?php

	//Cargar la clase padre para este controlador
    require_once("controllers/api/common.php");
    //Cargar el modelo para este controlador
    require_once("models/api/empleado.php");
    //Cargar los archivos necesarios

	class Empleado extends Common
	{
		//Definir los filtros sobre los parametros que ingresen a la peticion, en caso de no necesitar parametros, dejar un array vacio
        public static   $INDEX = array();
        public static   $SELECTORVISUAL = array();

        function __construct(){
        	parent::__construct();
        }

        function __destruct(){
        	parent::__destruct();
        }

		public function index()
		{
			
		}

        public function selectorVisual()
        {
            parent::responder(EmpleadoModel::selectorVisual(array("ocultar_empleados" => 1, "sucursal" => $this->Seguridad->Sucursal)));
        }

	}

?>