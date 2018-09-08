<?php

//Carga la funciones comunes top y footer
require('common.php');
//Carga el modelo para este controlador
require_once("models/recalculoinventario.php");

class RecalculoInventarioControllers extends Common
{
	private $model;

    function __construct()
    {
        $this->model = new RecalculoInventarioModels();
        $this->model->connect();
    }

    function __destruct()
    {
        $this->model->close();
    }

    public function index()
    {
    	require('views/inventario/recalculoinventario.php');
    }
}