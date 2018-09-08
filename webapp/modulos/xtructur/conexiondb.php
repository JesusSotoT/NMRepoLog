<?php
    include_once("../../netwarelog/webconfig.php");
	$mysqli = new mysqli($servidor , $usuariobd, $clavebd, $bd);
	mysqli_set_charset( $mysqli, 'utf8');
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
?>