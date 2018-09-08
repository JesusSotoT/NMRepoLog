<?php

	require "controllers/common_father.php";
	require_once("models/seguridad.php");

	class Common extends CommonFather
	{

		protected $Seguridad;
		private $Renovar;

		function __construct($validar = true)
	    {
	    	if($validar){
		        $this->Seguridad = new Seguridad();
		        $this->Seguridad->connect();
		        if(@$_GET['c'] == 'usuario' && @$_GET['f'] == 'iniciarSesionUsuario'){
			        
			    }else if(@$_GET['c'] == 'imagen' && @$_GET['f'] == 'obtener'){
			        
			    }else{
			    	if(!isset($_POST["identificador"]) || !isset($_POST["llave"])){
			    		echo json_encode($this->Seguridad->tokenInvalido());
			            exit;
			    	}

			    	$seguridad = $this->Seguridad->validaToken($_POST["identificador"], $_POST["llave"]);
			    	if(!is_null($seguridad) && empty($seguridad)){
			            echo json_encode($this->Seguridad->tokenInvalido());
			            exit;
			        }

			        $validador = strtoupper($_GET['f']);
			        $validacion = $this->Seguridad->validaParametros($_REQUEST, $this::$$validador);
		            if(gettype($validacion) == "array" || !$validacion){
		                echo json_encode($validacion);
		                exit;
		            }
		            
			        $this->Renovar = (is_null($seguridad)) ? null : $seguridad;
			    }
			}
	    }

	    function __destruct()
	    {
	    	if($this->Seguridad != null) $this->Seguridad->close();
	    }

	    function responder($respuesta, $renovar = true){
	    	if(!is_null($this->Renovar) && $renovar){
	    		$respuesta["renovar"] = $this->Renovar;
	    	}
	    	if(isset($respuesta["fields"])) unset($respuesta["fields"]);
	    	echo json_encode($respuesta);
	    }

		function mainPageIndex()
		{
			require('views/principal.php');
		}

		function mainPageFunction()
		{
			echo "<b style='color:red;'>La función no existe.</b>";
		}
	}
?>