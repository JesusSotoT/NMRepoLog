<?php

	include("../../../netwarelog/webconfig.php");

	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$idruta=$_REQUEST['idruta'];
	$longitud=$_REQUEST['lon'];
	$latitud=$_REQUEST['lat'];
	$opt=$_REQUEST['opt'];
	$origen=$_REQUEST['origen'];
	$array = array();
	$direcciones=array();
	$principio=$origen;

	$ori=str_replace(" ","+",$origen);
	$ori= 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='.$ori;
	$resultado = file_get_contents($ori);
	$resultado = json_decode($resultado, TRUE);
 
    $latitud = $resultado['results'][0]['geometry']['location']['lat'];
    $longitud = $resultado['results'][0]['geometry']['location']['lng'];

    $latitudR=$latitud;
    $longitudR=$longitud;

	$dato=$conection->query("select cc.nombre,cc.cp,cc.direccion,cc.colonia,
	e.estado,m.municipio from sms_ruta_oferta_cliente roc, 
	sms_oferta_cliente oc,comun_cliente cc, estados e, municipios m
	where roc.idRutaOferta=".$idruta." and oc.id=roc.idOfertacliente and 
	cc.id=oc.idCliente and e.idestado=cc.idEstado and m.idmunicipio=cc.idMunicipio and oc.contesto=1");

	$num=0;
	while($datos=$dato->fetch_array(MYSQLI_ASSOC)){
		$calle=$datos['direccion'];
		$cp=$datos['cp'];
		$municpio=$datos['municipio'];
		$estado=$datos['estado'];
		$nombre=$datos['nombre'];
	 	
		$direccion_google =str_replace(" ","+",($calle.",+".$municpio.",+".$estado));
		$dir=$direccion_google;
		//$direccion_google ="62+Texcoco,+zapopan,+jalisco";
		$dire= 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='.$dir;
		$resultado = file_get_contents($dire);
		$resultado = json_decode($resultado, TRUE);
	 
	    $lat = $resultado['results'][0]['geometry']['location']['lat'];
	    $lng = $resultado['results'][0]['geometry']['location']['lng'];

	    echo ' LAT: '.$lat.' LONG: '.$lng.'  ---<br> ';

		$direcciones[$num]=$calle.", ".$municpio.", ".$estado."¿?".$lat."¿?".$lng;

		$lat ="{$lat}";
		$lon=   "{$lng}";
	  
	    $num++;    
	} 

	//ordenar distancias 
	$table="<table style='font-family:verdana;font-size:11px'><tr><td>Origen</td><td style='padding-left:4px'>Destino</td><td style='padding-left:4px'>Distancia</td><td style='padding-left:4px'>Tiempo</td></tr>";

	$contador=count($direcciones);
	$posicion=0;
	while($contador>0){

		$anterior = 0;
		$normal=0;
		$direccion="";
		$elemento=0;

		//obteniendo distancias y tiempos
		for($i=0;$i<$contador;$i++){
			$coords=explode("¿?",$direcciones[$i]);
			$dire= 'http://maps.googleapis.com/maps/api/distancematrix/json?origins='.$latitudR.','.$longitudR.'&destinations='.$coords[1].','.$coords[2].'&sensor=false';
			$resultado = file_get_contents($dire);
			$resultado = json_decode($resultado, TRUE);

			$dis=$resultado['rows'][0]['elements'][0]['distance']['value'];
			$dur=$resultado['rows'][0]['elements'][0]['duration']['value'];

			if($opt=="0")
				$normal=$dis;
			else
				$normal=$dur;

			if($anterior>$normal || $anterior==0){
				$anterior=$normal;
				$direccion=$direcciones[$i]."¿?".$resultado['rows'][0]['elements'][0]['distance']['text']."¿?".$resultado['rows'][0]['elements'][0]['duration']['text'];
				$elemento=$i;
			}
		}
		$array[$posicion]=$direccion;
		$latlng=explode("¿?",$direccion);
		$latitudR=$latlng[1];
		$longitudR=$latlng[2];
		$puntoslat[$posicion]=$latlng[1];
		$puntoslon[$posicion]=$latlng[2];
		$table.="<tr><td>".$principio."</td><td>".$latlng[0]."</td><td>".$latlng[3]."</td><td>".$latlng[4]."</td></tr>";
		$principio=$latlng[0];
		array_splice($direcciones, $elemento, 1);
		$posicion++;
		//

		$contador--;
	}
	//

	echo $table."</table>";
	echo "{sep}";

	$dato=$conection->query("select stu.tipo tipo from sms_ruta_oferta_cliente sroc 
									inner join sms_ruta_oferta sro on sro.id=sroc.idRutaOferta
									inner join sms_transporte st on st.id=sro.idTransporte
									inner join sms_tipo_unidad stu on stu.idtipo=st.idtipo
									where sroc.idRutaOferta=".$idruta." limit 1");

	$strMotor="Motorcar";
	if($datos=$dato->fetch_array(MYSQLI_ASSOC)){
		switch($datos['tipo']){

			case "Mercancias Pesadas":
				$strMotor="hvg";
			break;

			case "Automovil":
				$strMotor="motorcar";
			break;
		}
	}

	//borrar archivos
	$dir=opendir(".");
	while($archivo=readdir($dir)){
	    if(is_file($archivo))
	    	if(pathinfo($archivo, PATHINFO_EXTENSION)=="txt")
	    		if(date("d-m-Y",filectime($archivo))<date("d-m-Y"))
	    			@unlink($archivo);
	}
	//

	$file='trazo'.time().'.txt';

	for($l=0;$l<count($puntoslat);$l++){

		if(count($puntoslat)==1){//para si solo es una pocicion osea sera punto a punto
			$url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=".$strMotor."&fast=0&layer=mapnik&flon=".$longitud."&flat=". $latitud."&tlon=".$puntoslon[$l]."&tlat=". $puntoslat[$l]."";
			$pagina_inicio = file_get_contents($url);
			$fp = fopen($file,'w');
			fwrite($fp, $pagina_inicio);
			fclose($fp);
			echo $file;
		} 

		if($l==0 && count($puntoslat)!=1){//si no e ssolo un punto
			$url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=".$strMotor."&fast=0&layer=mapnik&flon=".$longitud."&flat=". $latitud."&tlon=".$puntoslon[$l]."&tlat=". $puntoslat[$l]."";
			$pagina_inicio = file_get_contents($url);
			$fp = fopen($file,'w');
			fwrite($fp, $pagina_inicio);
			fclose($fp);
			$conte=file_get_contents($file);
			//{ "type": "LineString", "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } }, "coordinates": [ 

			$maximo= strlen($conte);
			$ide= "{";
			$ide2= '],';
			$total= strpos($conte,$ide);
			$total2= stripos($conte,$ide2);

			$total3= ($maximo-$total2-5);
			$final= substr ($conte,$total,-$total3);
			$final=str_replace('"', '', $final);
			$final=str_replace('],', '', $final);
			$final=str_replace('"', '', $final);
			$fp = fopen($file,'w');
			fwrite($fp,  $final);
			fclose($fp);
			//  
		}

		if($l!=0 && $l!=(count($puntoslat)-1) && count($puntoslat)!=1 ){//si no es solo el punto y este no no coincideen para q no cierre la ruta
			
			$url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=".$strMotor."&fast=0&layer=mapnik&flon=".$puntoslon[$l-1]."&flat=". $puntoslat[$l-1]."&tlon=".$puntoslon[$l]."&tlat=". $puntoslat[$l]."";
			// 	
			$cadena =file_get_contents($url); 
			$subcadena = "["; 
			$posicionsubcadena = strpos ($cadena, $subcadena); 
			$dominio = substr ($cadena, ($posicionsubcadena+1)); 

			$maximo= strlen ($dominio);
			$ide= "[";
			$ide2= '],';
			$total= strpos($dominio,$ide);
			$total2= stripos($dominio,$ide2);

			$total3= ($maximo-$total2-5);
			$final= substr ($dominio,$total,-$total3);
			$final=str_replace('],', '', $final);
			$final=str_replace('"', '', $final); 
			$corde=",".$final;
			$fp = fopen($file,'a');
			fwrite($fp, $corde);
			fclose($fp);	  
		}

		if($l==count($puntoslat)-1 && count($puntoslat)!=1){//para si el ultimo valor del array, y para ver q no entre cuando solo sea un punto

			$url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=".$strMotor."&fast=0&layer=mapnik&flon=".$puntoslon[$l-1]."&flat=". $puntoslat[$l-1]."&tlon=".$puntoslon[$l]."&tlat=". $puntoslat[$l]."";  
			$cadena =file_get_contents($url);
			$subcadena = "["; 
			// localicamos en que posición se haya la $subcadena, en nuestro caso la posicion es "7"
			$posicionsubcadena = strpos ($cadena, $subcadena); 
			// eliminamos los caracteres desde $subcadena hacia la izq, y le sumamos 1 para borrar tambien el @ en este caso
			$dominio = substr ($cadena, ($posicionsubcadena+1));  
			$dominio=",".$dominio;
			$fp = fopen($file,'a');
			fwrite($fp, $dominio);
			fclose($fp);
			$co = file_get_contents($file);

			$re=str_replace('type', '"type"', $co);
			$re=str_replace('LineString', '"LineString"', $re);
			$re=str_replace('crs', '"crs"', $re);
			$re=str_replace('name', '"name"', $re);
			$re=str_replace('properties', '"properties"', $re);
			$re=str_replace(':"crs":', ':crs:', $re);
			$re=str_replace('urn', '"urn', $re);
			$re=str_replace('CRS84', 'CRS84"', $re);
			$re=str_replace('coordinates', '"coordinates"', $re);
			$re=str_replace('""properties""', '"properties"', $re);
			$fp = fopen($file,'w');
			fwrite($fp, $re);
			fclose($fp);
			echo $file;
			// 	 
			// // 
			unset($cadena);	
		}
	}
?>