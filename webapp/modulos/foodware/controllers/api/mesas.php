<?php

    //ini_set("display_errors", 1); error_reporting(E_ALL);
	//Cargar la clase padre para este controlador
    require_once("controllers/api/common.php");
    //Cargar el modelo para este controlador
    require_once("models/api/mesas.php");
    //Cargar los archivos necesarios

	class Mesas extends Common
	{
		//Definir los filtros sobre los parametros que ingresen a la peticion, en caso de no necesitar parametros, dejar un array vacio
        public static   $INDEX = array();
        public static   $OBTENERMESAS = array();
        public static   $INSERTARCOMANDA = array();
        public static   $INSERTARCOMENSAL = array();
        public static   $BORRARCOMENSAL = array();
        public static   $CERRARCOMANDA = array();
        public static   $OBTENERCOMENSALES = array();

        function __construct(){
        	parent::__construct();
        }

        function __destruct(){
        	parent::__destruct();
        }

		public function index()
		{
			
		}

        public function obtenerMesas()
        {
            parent::responder(MesasModel::obtenerMesas($_REQUEST));
        }

        public function insertarComanda()
        {
            parent::responder(MesasModel::insertarComanda($_REQUEST));
        }

        public function insertarComensal()
        {
            parent::responder(MesasModel::insertarComensal($_REQUEST));
        }

        public function borrarComensal()
        {
            parent::responder(MesasModel::borrarComensal($_REQUEST));
        }

        public function cerrarComanda()
        {
            parent::responder(MesasModel::cerrarComanda($_REQUEST));
        }

         public function obtenerComensales()
        {
            parent::responder(MesasModel::obtenerComensales($_REQUEST));
        }

	}

?>