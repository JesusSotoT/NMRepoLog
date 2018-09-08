<?php
	
	//esta libreria genera y carga el controlador que se manda llamar desde la url, el archivo php y el controlador deben llamarse igual, 
	//si no existe genera un controlador que carga el archivo de la pagina por default

	function obtenerClase($clase)
    {
        $clase = explode("_", $clase);
        foreach ($clase as &$parte) {
        	$parte = ucfirst($parte);
        }
        $clase = implode("", $clase);
        return $clase;
    }

	@$controller_file = strtolower($_REQUEST['c']);
	$anexo = ($_REQUEST['_tipo'] == "api") ? "api/" : "";

	if (isset($_REQUEST['c']) && file_exists('controllers/'. $anexo . $controller_file .'.php') == true) 
	{
		$_REQUEST['f'] = (isset($_REQUEST['f'])) ? $_REQUEST['f'] : "index";
		require_once 'controllers/'. $anexo . $controller_file .'.php';
		if(isset($_REQUEST["_tipo"])) $c = obtenerClase($_REQUEST['c']);
		else $c = $_REQUEST['c'];
		$controller = new $c();
	}
	else
	{	
		require_once 'controllers/'. $anexo . 'common.php';
		$controller = new Common(isset($_REQUEST['c']) ? true : false);
	    if(method_exists($controller, "mainPageIndex")){
	    	$controller->mainPageIndex();
			echo "<b style='color:red;'>El controlador no existe.</b>";
		} else {
			$controller->error();
		}
	}

?>