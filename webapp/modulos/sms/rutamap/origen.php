<?php

	$origen=str_replace(" ","+",$_REQUEST['origen']);
	$array = array();

	$ori= 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='.$origen;
	$resultado = file_get_contents($ori);
	$resultado = json_decode($resultado, TRUE);
 
    $latitud = $resultado['results'][0]['geometry']['location']['lat'];
    $longitud = $resultado['results'][0]['geometry']['location']['lng'];

    exit($latitud.",".$longitud);
?>