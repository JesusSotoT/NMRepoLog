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
		        if(@$_REQUEST['c'] == 'registro' && @$_REQUEST['f'] == 'agregarRegistro'){
			        $servidor = explode("/", $_SERVER['REQUEST_URI']);
					$cliente = $servidor[array_search('clientes', $servidor) + 1];
					if($cliente != "appministra_srpago"){
						echo json_encode($this->Seguridad->tokenInvalido());
			            exit;
					}
			    }else if(@$_REQUEST['c'] == 'venta' && @$_REQUEST['f'] == 'venta'){
			        
			    }else{
			    	if(!isset($_REQUEST["identificador"]) || !isset($_REQUEST["llave"])){
			    		echo json_encode($this->Seguridad->tokenInvalido());
			            exit;
			    	}

			    	$seguridad = $this->Seguridad->validaToken($_REQUEST["identificador"], $_REQUEST["llave"]);
			    	if(!is_null($seguridad) && empty($seguridad)){
			            echo json_encode($this->Seguridad->tokenInvalido());
			            exit;
			        }

			        $validador = strtoupper($_REQUEST['f']);
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