<?php

	/*
		Clase padre de los modelos, se encarga de validar cuestiones de seguridad y responder la información generada por el modelo
	*/

	require "controllers/common_father.php";
    require "models/api/seguridad.php";

	class Common extends CommonBase
	{

        public $Renovar;

        function __construct($default = false)
        {
            parent::__construct();
            if(isset($_SESSION)) session_start();
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $this->Seguridad = new SeguridadModel();

            if(!array_key_exists("dispositivo", $_REQUEST) || !array_key_exists("llave", $_REQUEST)){
                echo json_encode(SeguridadModel::$TOKEN_INVALIDO);
                exit;
            }

            if(!(@$_REQUEST['c'] == 'seguridad' && @$_REQUEST['f'] == 'login')){
                if($this->Seguridad->logueado($this->Renovar)){
                    $this->validarParametros();
                }else{
                    $this->responder(SeguridadModel::$TOKEN_INVALIDO);
                }
            } else {
                $this->validarParametros();
            }
        }

        function __destruct()
        {
            parent::__destruct();
        }

        //Responder al cliente la información generada por el modelo
        function responder($respuesta, $renovar = true)
        {
            $respuesta["sesion"] = base64_encode(serialize($_SESSION));
            if(!is_null($this->Renovar) && $renovar){
                $respuesta["renovar"] = $this->Renovar;
            }
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