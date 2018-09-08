<?php

	//Cargar la clase padre para este controlador
    require_once("controllers/api/common.php");
    //Cargar el modelo para este controlador
    require_once("models/api/mrp_departamento.php");
    //Cargar los archivos necesarios

	class MrpDepartamento extends Common
	{
		//Definir los filtros sobre los parametros que ingresen a la peticion, en caso de no necesitar parametros, dejar un array vacio
        public static   $INDEX = array();
        public static   $AREAS = array();

        function __construct(){
        	parent::__construct();
        }

        function __destruct(){
        	parent::__destruct();
        }

        public function areas()
        {
            parent::responder(MrpDepartamentoModel::areas());
        }

	}

?>