<?php

	//esta libreria genera y carga el controlador que se manda llamar desde la url, el archivo php y el controlador deben llamarse igual, 
	//si no existe genera un controlador que carga el archivo de la pagina por default

	@$controller_file = strtolower($_REQUEST['c']);
	if (isset($_REQUEST['c']) && file_exists('controllers/'. $controller_file .'.php')) 
	{
		require_once 'controllers/'. $controller_file .'.php';
		$controller = new $_REQUEST['c']();
	}
	else
	{	
		require_once 'controllers/commonapi.php';
		$controller = new Commonapi(false);
	    $controller->mainPageIndex();
	}

?>