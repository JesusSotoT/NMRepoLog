<?php

    require "../../netwarelog/mvc/controllers/common_father.php";

	class CommonBase extends CommonFather
	{
		protected $Seguridad;

		function __construct()
	    {
	       
	    }

        function __destruct()
        {

        }

        //Validar los parámetros de la petición entrante
        protected function validarParametros(){
            $validador = strtoupper($_REQUEST['f']);
            if(property_exists($this, $validador)){
                $validacion = $this->Seguridad->validaParametros($_REQUEST, $this::$$validador);
                if(gettype($validacion) == "array" || !$validacion){
                    $this->responder($validacion);
                }
            } else {
                $this->error(true);
            }
        }

	}
	
?>