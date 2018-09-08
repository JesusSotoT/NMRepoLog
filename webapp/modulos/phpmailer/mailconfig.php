<?php

	include_once dirname(__DIR__) .'/perfil/models/mailing.php';
	$configuracion = new MailingModel();

	$strMailCharSet='UTF-8';
	$blnMailSMTPAuth = $configuracion->servidor->autentificacion;
	$strMailSMTPSecure = $configuracion->servidor->metodo;
	$strMailHost = $configuracion->servidor->url;
	$intMailPort = $configuracion->servidor->puerto;
	$strMailUsername = $configuracion->correo;
	$strMailPassword = $configuracion->clave;
	$strDebug = false;

?>