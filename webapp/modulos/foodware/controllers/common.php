<?php

    require "controllers/common_father.php";
    require "models/seguridad.php";

	class Common extends CommonBase
	{

		function __construct($default = false)
	    {
            parent::__construct();
            $this->Seguridad = new SeguridadModel();
	    	if(!(@$_REQUEST['c'] == 'seguridad' && @$_REQUEST['f'] == 'login')){
            	if(!$default) $this->validarParametros();
            }
	    }

        function __destruct()
        {
            parent::__destruct();
        }

        //Responder al cliente la información generada por el modelo
        function responder($respuesta)
        {
            echo json_encode($respuesta);
            exit();
        }

        function error($funcion = false)
        {
            echo json_encode((!$funcion) ? SeguridadModel::$CONTROLADOR_INEXISTENTE : SeguridadModel::$FUNCION_INEXISTENTE);
            exit();
        }

	}
	
?>