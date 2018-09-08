<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones 
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class ImagenModel extends Connection
    {
        
        public function obtener($imagen, $ancho = 0, $alto = 0){
            $imagen = end(explode("/", $imagen));
            $imagen = '../webapp/modulos/pos/images/productos/'. $imagen;
            if(!file_exists($imagen) || is_dir($imagen)){
                //$imagen = '../webapp/modulos/pos/noimage.jpeg';
            }
            $informacion = pathinfo($imagen);
            list($ancho_inicial, $alto_inicial) = getimagesize($imagen);

            header("Content-Type: image/". $informacion['extension']);

            if($ancho == 0 && $alto == 0 && ($ancho_inicial > 900 || $alto_inicial > 900)){
                $ancho_final = $ancho_inicial;
                $alto_final = $alto_inicial;
                do{
                    $ancho_final = round(((50 * $ancho_final) / 100));
                    $alto_final = round(((50 * $alto_final) / 100));
                }while ($ancho_final > 900 || $alto_final > 900);
            }else if($ancho > 0 && $alto > 0){
                $ancho_final = $ancho;
                $alto_final = $alto;
            }else{
                $ancho_final = $ancho_inicial;
                $alto_final = $alto_inicial;
            }
            
            $r = $ancho_inicial / $alto_inicial;
            if ($cortar) {
                if ($ancho_inicial > $alto_inicial) {
                    $ancho_inicial = ceil($ancho_inicial - ($ancho_inicial * abs($r - $ancho_final / $alto_final)));
                } else {
                    $alto_inicial = ceil($alto_inicial - ($alto_inicial * abs($r - $ancho_final / $alto_final)));
                }
                $ancho_nuevo = $ancho_final;
                $alto_nuevo = $alto_final;
            } else {
                if ($ancho_final / $alto_final > $r) {
                    $ancho_nuevo = $alto_final * $r;
                    $alto_nuevo = $alto_final;
                } else {
                    $alto_nuevo = $ancho_final / $r;
                    $ancho_nuevo = $ancho_final;
                }
            }
            $imagen = imagecreatefromstring(file_get_contents($imagen));
            $destino = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
            imagealphablending($destino, false);
            imagesavealpha($destino, true);
            imagecopyresampled($destino, $imagen, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_inicial, $alto_inicial);

            switch ($informacion['extension']) {
                case 'jpg':
                    
                case 'jpeg':
                    imagejpeg($destino, NULL, 100);
                    break;
                case 'png':
                    imagepng($destino, NULL, 0);
                    break;
                case 'gif':
                    imagegif($destino);
                    break;
            }
        }

    }

?>
