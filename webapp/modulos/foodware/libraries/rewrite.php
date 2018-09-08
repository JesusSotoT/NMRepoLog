<?php
	
	if(isset($_REQUEST['ruta']))
	{
		@$parametros = explode("/", $_REQUEST['ruta']);
		$parametros = array_diff($parametros, array(""));
		@$_REQUEST['c'] = $parametros[0];
		@$_REQUEST['f'] = (isset($parametros[1]) && !is_null($parametros[1]) && $parametros[1] != "" ) ? $parametros[1] : "index";
		for($i = 2; $i < count($parametros); $i++){
			$pre = $i;
			++$i;
			if($i >= count($parametros)) $parametros[$i] = null;
			$_REQUEST[$parametros[$pre]] = $parametros[$i]; 
		}
	}

?>