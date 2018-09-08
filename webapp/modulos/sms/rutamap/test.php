<?php
    include("../../../netwarelog/webconfig.php");

    $conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

    $idruta=$_REQUEST["ruta"];
    $dato=$conection->query("select cc.nombre,cc.cp,cc.direccion,cc.colonia,
    e.estado,m.municipio from sms_ruta_oferta_cliente roc, 
    sms_oferta_cliente oc,comun_cliente cc, estados e, municipios m
    where roc.idRutaOferta=".$idruta." and oc.id=roc.idOfertacliente and 
    cc.id=oc.idCliente and e.idestado=cc.idEstado and m.idmunicipio=cc.idMunicipio");

    $num=0;
    while($datos=$dato->fetch_array(MYSQLI_ASSOC)){
        $calle=$datos['direccion'];
        $cp=$datos['cp'];
        $municpio=$datos['municipio'];
        $estado=$datos['estado'];
        $nombre=$datos['nombre'];
     	
        $direccion_google =str_replace(" ","+",($calle.",+".$municpio.",+".$estado));
        $dir=$direccion_google;
        $dire= 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='.$dir;
        $resultado = file_get_contents($dire);
        $resultado = json_decode($resultado, TRUE);
         
        $lat = $resultado['results'][0]['geometry']['location']['lat'];
        $lng = $resultado['results'][0]['geometry']['location']['lng'];

        $lat ="{$lat}";
        $lon=   "{$lng}";
        echo $lon.",".$lat.",";
            
        $puntoslat[$num]=$lat;
        $puntoslon[$num]=$lon;
          
        $num++;    
    }  
?>