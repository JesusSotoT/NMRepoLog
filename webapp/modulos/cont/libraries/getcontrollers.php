<?php
//esta libreria genera y carga el controlador que se manda llamar desde la url, el archivo php y el controlador deben llamarse igual, 
//si no existe genera un controlador que carga el archivo de la pagina por default

@$controller_file=strtolower($_GET['c']);
if (isset($_GET['c']) && file_exists( 'controllers/'.$controller_file.'.php')) 
{
	require_once 'controllers/'.$controller_file.'.php';
	$controller = new $_GET['c']();
}
else
{
	require_once 'controllers/index.php';
	$controller = new Index();
	echo "<b style='color:red;'>El controlador no existe.</b>";
}