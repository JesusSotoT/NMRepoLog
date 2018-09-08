<?php
		ini_set('session.cookie_httponly',1);
		include 'support.php';
		include dirname(__FILE__).'/../webconfig.php';
		include 'clases/clconexion.php';

		//$conexion = new conexion($servidor,$usuariobd,$clavebd,$bd,$instalarbase,$tipobd);

		$conexion = new conexion($servidor,$usuariobd,$clavebd,$bd,$instalarbase,$tipobd);
		
?>
