<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi
ini_set("display_error", 1);
error_reporting("E_ALL");

class TrimestralModel extends Connection
{

    public function reporteTrimestral($folio, $fecha_inicial, $fecha_final, $consultor){
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);

        foreach ($respuesta["rows"] as $value) {

            if($value["correoelectronico"] == $consultor){
                $consultor_respuesta = $this->inadem($value["correoelectronico"]);
                $contador = 0;
                foreach ($consultor_respuesta as $valor) {
                    $contador = $contador + 1;
                    $url = $valor["ReportePDF"];
                    
                    $texto = fopen("views/plantilla/reporteTrimestral/reporte/texto.txt", "a+") or die("No se pudo abrir el archivo!");
                    fwrite($texto, $contador.".- ".$url."\n");
                    if(strpos($url, ".pdf") !== false){
                        fwrite($texto, "fue a descargar el ". $contador."\n");

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSLVERSION,3);
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch); 
                        curl_close ($ch);

                        if(strpos($respuesta, "Error") === false){
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$valor["rfc"]."_reporte.pdf";
                            //echo $destino;
                        
                            $archivo = fopen($destino, "w+");
                            fputs($archivo, $respuesta);
                            fclose($archivo);
                        }else {
                            //echo "regreso error";
                        }
                    }else{
                        //echo "no trae pdf";
                    }
                    fclose($texto);
                    
                }

            }
            
        }

        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en.'/reportePDF.zip';

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "pdf") || strpos($file, "txt")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                }
                
            }
            $zip->close();
            closedir($handle);
            $zip->close();
        
        }
    }

    //Descargas desde INADEM ->

    public function reporteMicroMercado($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        
        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);

        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["correoelectronico"] == $consultor){
                //se hace una peticion a la funcion inadem para qu regrese los datos del servidor de descifra
                $consultor_respuesta = $this->inadem($value["correoelectronico"]);
                $contador = 0;

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                foreach ($consultor_respuesta as $valor) {

                    //se valida que el rfc de la respuesta de inadem pertenesca al folio que se esta descargando
                    if($folio != $this->obtenerFolio($valor)){
                        continue;
                    }

                    $contador = $contador + 1;
                    $url = $valor["ReportePDF"];

                    //se evalua que la direccion si contenga un archivo valido
                    if(strpos($url, ".pdf") !== false){
            
                        //se inicia la descarga del archivo 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSLVERSION,3);
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch); 
                        curl_close ($ch);

                        //se evalua si el archivo se descargo correctamente
                        if(strpos($respuesta, "Error") === false){
                            //se inicia la construccion de los datos del archivo Excel 
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $valor["nombre"]."_".$valor["rfc"]."_reporteMicroMercado.pdf");
                            //se asigna la direccion de guardado del archivo descargado
                            $destino = "views/plantilla/reporteTrimestral/reporte/".$valor["nombre"]."_".$valor["rfc"]."_reporteMicroMercado.pdf";
                            //se guardael archivo fisicamente
                            $archivo = fopen($destino, "w+");
                            fputs($archivo, $respuesta);
                            fclose($archivo);
                        }else {
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                        }
                    }else{
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                    }
                }
            }
        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-reporteMicroMercado.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "pdf") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function analisisMicroMercado($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        
        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);

        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["correoelectronico"] == $consultor){
                //se hace una peticion a la funcion inadem para qu regrese los datos del servidor de descifra
                $consultor_respuesta = $this->inadem($value["correoelectronico"]);
                $contador = 0;

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                foreach ($consultor_respuesta as $valor) {
                    //se valida que el rfc de la respuesta de inadem pertenesca al folio que se esta descargando
                    if($folio != $this->obtenerFolio($valor)){
                        continue;
                    }

                    $contador = $contador + 1;
                    $url = $valor["ANEXO2"];

                    //se evalua que la direccion si contenga un archivo valido
                    if(strpos($url, ".pdf") !== false){
            
                        //se inicia la descarga del archivo 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSLVERSION,3);
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch); 
                        curl_close ($ch);

                        //se evalua si el archivo se descargo correctamente
                        if(strpos($respuesta, "Error") === false){
                            //se inicia la construccion de los datos del archivo Excel 
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $valor["nombre"]."_".$valor["rfc"]."_AnalisisMicroMercado.pdf");
                            //se asigna la direccion de guardado del archivo descargado
                            $destino = "views/plantilla/reporteTrimestral/reporte/".$valor["nombre"]."_".$valor["rfc"]."_AnalisisMicroMercado.pdf";
                            //se guardael archivo fisicamente
                            $archivo = fopen($destino, "w+");
                            fputs($archivo, $respuesta);
                            fclose($archivo);
                        }else {
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                        }
                    }else{
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                    }
                }
            }
        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-AnalisisMicroMercado.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "pdf") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        }
    }

    public function fotosMicroEmpresario($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        
        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);

        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["correoelectronico"] == $consultor){
                //se hace una peticion a la funcion inadem para qu regrese los datos del servidor de descifra
                $consultor_respuesta = $this->inadem($value["correoelectronico"]);
                $contador = 0;

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                foreach ($consultor_respuesta as $valor) {
                    //se valida que el rfc de la respuesta de inadem pertenesca al folio que se esta descargando
                    if($folio != $this->obtenerFolio($valor)){
                        continue;
                    }

                    $contador = $contador + 1;
                    $url = $valor["foto1"];
                    $url2 = $valor["FOTO2"];

                    //se evalua que la direccion si contenga un archivo valido
                    if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                        
                        //se obtiene el tipo de foto
                        //$extension = pathinfo($url, PATHINFO_EXTENSION);
                        //se inicia la descarga del archivo 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSLVERSION,3);
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch); 
                        curl_close ($ch);

                        //se evalua si el archivo se descargo correctamente
                        if(strpos($respuesta, "Error") === false){
                            //se inicia la construccion de los datos del archivo Excel 
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $valor["nombre"]."_".$valor["rfc"]."_foto1.jpg");
                            //se asigna la direccion de guardado del archivo descargado
                            $destino = "views/plantilla/reporteTrimestral/reporte/".$valor["nombre"]."_".$valor["rfc"]."_foto1.jpg";
                            //se guardael archivo fisicamente
                            $archivo = fopen($destino, "w+");
                            fputs($archivo, $respuesta);
                            fclose($archivo);
                        }else {
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                        }
                    }else{
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                    }

                    $contador = $contador + 1;
                    //se evalua que la direccion si contenga un archivo valido
                    if((strpos($url2, ".png") !== false) || (strpos($url2, ".jpeg") !== false) || (strpos($url2, ".jpg") !== false)){
                        
                        //se obtiene el tipo de foto
                        //$extension = pathinfo($url, PATHINFO_EXTENSION);
                        //se inicia la descarga del archivo 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url2);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSLVERSION,3);
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch); 
                        curl_close ($ch);

                        //se evalua si el archivo se descargo correctamente
                        if(strpos($respuesta, "Error") === false){
                            //se inicia la construccion de los datos del archivo Excel 
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $valor["nombre"]."_".$valor["rfc"]."_foto2.jpg");
                            //se asigna la direccion de guardado del archivo descargado
                            $destino = "views/plantilla/reporteTrimestral/reporte/".$valor["nombre"]."_".$valor["rfc"]."_foto2.jpg";
                            //se guardael archivo fisicamente
                            $archivo = fopen($destino, "w+");
                            fputs($archivo, $respuesta);
                            fclose($archivo);
                        }else {
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                        }
                    }else{
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                    }
                }
            }
        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-fotos.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function analisisFinanciero($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        
        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);

        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["correoelectronico"] == $consultor){
                //se hace una peticion a la funcion inadem para qu regrese los datos del servidor de descifra
                $consultor_respuesta = $this->inadem($value["correoelectronico"]);
                $contador = 0;

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                foreach ($consultor_respuesta as $valor) {
                    //se valida que el rfc de la respuesta de inadem pertenesca al folio que se esta descargando
                    if($folio != $this->obtenerFolio($valor)){
                        continue;
                    }

                    $contador = $contador + 1;
                    $url = $valor["ANEXO1"];

                    //se evalua que la direccion si contenga un archivo valido
                    if(strpos($url, ".pdf") !== false){
            
                        //se inicia la descarga del archivo 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSLVERSION,3);
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch); 
                        curl_close ($ch);

                        //se evalua si el archivo se descargo correctamente
                        if(strpos($respuesta, "Error") === false){
                            //se inicia la construccion de los datos del archivo Excel 
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $valor["nombre"]."_".$valor["rfc"]."_AnalisisFinanciero.pdf");
                            //se asigna la direccion de guardado del archivo descargado
                            $destino = "views/plantilla/reporteTrimestral/reporte/".$valor["nombre"]."_".$valor["rfc"]."_AnalisisFinanciero.pdf";
                            //se guardael archivo fisicamente
                            $archivo = fopen($destino, "w+");
                            fputs($archivo, $respuesta);
                            fclose($archivo);
                        }else {
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                        }
                    }else{
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                    }
                }
            }
        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-AnalisisFinanciero.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "pdf") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function planAccion($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        
        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);

        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["correoelectronico"] == $consultor){
                //se hace una peticion a la funcion inadem para qu regrese los datos del servidor de descifra
                $consultor_respuesta = $this->inadem($value["correoelectronico"]);
                $contador = 0;

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                foreach ($consultor_respuesta as $valor) {
                    //se valida que el rfc de la respuesta de inadem pertenesca al folio que se esta descargando
                    if($folio != $this->obtenerFolio($valor)){
                        continue;
                    }

                    $contador = $contador + 1;
                    $url = $valor["ANEXO3"];

                    //se evalua que la direccion si contenga un archivo valido
                    if(strpos($url, ".pdf") !== false){
            
                        //se inicia la descarga del archivo 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSLVERSION,3);
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch); 
                        curl_close ($ch);

                        //se evalua si el archivo se descargo correctamente
                        if(strpos($respuesta, "Error") === false){
                            //se inicia la construccion de los datos del archivo Excel 
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $valor["nombre"]."_".$valor["rfc"]."_PlanAccion.pdf");
                            //se asigna la direccion de guardado del archivo descargado
                            $destino = "views/plantilla/reporteTrimestral/reporte/".$valor["nombre"]."_".$valor["rfc"]."_PlanAccion.pdf";
                            //se guardael archivo fisicamente
                            $archivo = fopen($destino, "w+");
                            fputs($archivo, $respuesta);
                            fclose($archivo);
                        }else {
                            $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                            $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                        }
                    }else{
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $valor["nombre"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                    }
                }
            }
        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-PlanAccion.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "pdf") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    //Descargas desde INOVEKIA ->

    public function ife($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p24a, ifu.f1p24b, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p24a"] != "noArchivo" && $value["f1p24a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $contador = $contador + 1;
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p24a"];
                $url2 = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p24b"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_foto1.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_foto1.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }

                $contador = $contador + 1;
                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url2, ".png") !== false) || (strpos($url2, ".jpeg") !== false) || (strpos($url2, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url2);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_foto2.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_foto2.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }     
            }else{
                $contador = $contador +1;
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-ife.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function autoEmpleo($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p23a, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            $contador = $contador + 1;
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p23a"] != "noArchivo" && $value["f1p23a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p23a"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_AutoEmpleo.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_AutoEmpleo.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }
            }else{
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-AutoEmpleo.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function reciboLuz($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p29a, ifu.f1p29b, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p29a"] != "noArchivo" && $value["f1p29a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $contador = $contador + 1;
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p29a"];
                $url2 = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p29b"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ReciboLuz1.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ReciboLuz1.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }

                $contador = $contador + 1;
                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url2, ".png") !== false) || (strpos($url2, ".jpeg") !== false) || (strpos($url2, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url2);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ReciboLuz2.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ReciboLuz2.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }     
            }else{
                $contador = $contador +1;
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-ReciboLuz.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function listaRayaHombre($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p25a, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            $contador = $contador + 1;
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p25a"] != "noArchivo" && $value["f1p25a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p25a"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ListaRayaHombre.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ListaRayaHombre.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }
            }else{
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-ListaRayaHombre.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function ifeEmpleadoHombre($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p26a, ifu.f1p26b, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p26a"] != "noArchivo" && $value["f1p26a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $contador = $contador + 1;
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p26a"];
                $url2 = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p26b"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ife_foto1.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ife_foto1.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }

                $contador = $contador + 1;
                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url2, ".png") !== false) || (strpos($url2, ".jpeg") !== false) || (strpos($url2, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url2);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ife_foto2.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ife_foto2.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }     
            }else{
                $contador = $contador +1;
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-IfeEmpleadoHombre.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function listaRayaMujer($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p27a, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            $contador = $contador + 1;
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p27a"] != "noArchivo" && $value["f1p27a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p27a"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ListaRayaMujer.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ListaRayaMujer.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }
            }else{
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-ListaRayaMujer.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function ifeEmpleadoMujer($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p28a, ifu.f1p28b, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p26a"] != "noArchivo" && $value["f1p26a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $contador = $contador + 1;
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p28a"];
                $url2 = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p28b"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ife_foto1.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ife_foto1.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }

                $contador = $contador + 1;
                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url2, ".png") !== false) || (strpos($url2, ".jpeg") !== false) || (strpos($url2, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url2);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_ife_foto2.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_ife_foto2.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }     
            }else{
                $contador = $contador +1;
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-IfeEmpleadoMujer.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public function rfcActualizada($folio, $fecha_inicial, $fecha_final, $consultor){
        //se cargan las librerias para la creacion del Excel
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        //se crean las columnas con el nombre de los elementos a llenar y se asigna un nombre
        $phpExcel->getActiveSheet()->SetCellValue('A1', 'Empresario');
        $phpExcel->getActiveSheet()->SetCellValue('B1', 'Archivo');
       
        //select utilizado para el filtrado de empresarios
        $sql = "SELECT ief.id_empresario, ifu.f1p5a, ifu.f1p5b, ifu.f1p5c, ie.razon FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor 
        INNER JOIN netwarstore.inovekia_formulario_uno as ifu on ief.id_empresario = ifu.id_empresario
        INNER JOIN netwarstore.inovekia_empresario as ie on ifu.id_empresario = ie.id  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico = '".$consultor."';";
        $respuesta = $this->queryArray($sql);

        //se borra cualquier archivo contenido en la capeta reporteTrimestral y su subcarpete reporte
        $this->delTree("views/plantilla/reporteTrimestral/reporte");
        $this->delTree("views/plantilla/reporteTrimestral");
        
        //se crea la carpeta reporteTrimestral y su subcarpeta reporte
        mkdir("views/plantilla/reporteTrimestral", 0700);
        mkdir("views/plantilla/reporteTrimestral/reporte", 0700);
        $contador = 0;
        foreach ($respuesta["rows"] as $value) {
            //se evalua y se filtra que solo se descarguen archivos pertenencientes a un consultor seleccionado
            if($value["f1p5a"] != "noArchivo" && $value["f1p5a"] != "noAplica"){

                //para cada uno de los registros en la consulta hacia descifra se descarga su archivo
                $contador = $contador + 1;
                $url = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p5a"];
                $url2 = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p5b"];
                $url3 = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/".$value["f1p5c"];

                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url, ".png") !== false) || (strpos($url, ".jpeg") !== false) || (strpos($url, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_rfc_actualizada1.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_rfc_actualizada1.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                }

                $contador = $contador + 1;
                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url2, ".png") !== false) || (strpos($url2, ".jpeg") !== false) || (strpos($url2, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url2);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_rfc_actualizada2.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_rfc_actualizada2.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                } 

                $contador = $contador + 1;
                //se evalua que la direccion si contenga un archivo valido
                if((strpos($url3, ".png") !== false) || (strpos($url3, ".jpeg") !== false) || (strpos($url2, ".jpg") !== false)){
                    
                    //se obtiene el tipo de foto
                    //$extension = pathinfo($url, PATHINFO_EXTENSION);
                    //se inicia la descarga del archivo 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url3);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION,3);
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch); 
                    curl_close ($ch);

                    //se evalua si el archivo se descargo correctamente
                    if(strpos($respuesta, "Error") === false){
                        //se inicia la construccion de los datos del archivo Excel 
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), $value["razon"]."_rfc_actualizada3.jpg");
                        //se asigna la direccion de guardado del archivo descargado
                        $destino = "views/plantilla/reporteTrimestral/reporte/".$value["razon"]."_rfc_actualizada3.jpg";
                        //se guardael archivo fisicamente
                        $archivo = fopen($destino, "w+");
                        fputs($archivo, $respuesta);
                        fclose($archivo);
                    }else {
                        $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                        $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se pudo descargar el archivo");
                    }
                }else{
                    $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                    $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No se encontro archivo");
                } 

            }else{
                $contador = $contador +1;
                $phpExcel->getActiveSheet()->SetCellValue('A' . ($contador+1), $value["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . ($contador+1), "No aplica, o no se encontro el archivo");
            }    

        }

        //se finaliza la creacion del Excel y se guarda junto con los demas archivos
        $destino = "views/plantilla/reporteTrimestral/reporte/registros.xls";            
        $archivo = fopen($destino, "w+");
        $phpExcel->getActiveSheet()->setTitle('Registros');
        $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $phpExcel->save($destino);

        //se inicia el proceso de compresion de la carpeta que contiene todos los archivos
        $directorio_comprimir = "views/plantilla/reporteTrimestral/reporte";
        $compimir_en = "views/plantilla/reporteTrimestral";
        $zip_file = $compimir_en."/del_".$fecha_inicial."_al_".$fecha_final."-rfcAztualizada.zip";

        if ($handle = opendir($directorio_comprimir)){
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$zip_file>\n");
            }

            while (($file = readdir($handle)) !== false) {
                if(strpos($file, "jpg") || strpos($file, "xls")){
                    $zip->addFile($directorio_comprimir.'/'.$file, $file);
                } 
            }
            $zip->close();
            closedir($handle);
            $zip->close();
            echo $zip_file;
        
        }
    }

    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function inadem($correo){
        try{
            $url = 'http://www.descifrainadem.mx/INOVEKIA/ServicioDatosInadem.asmx/ObtenerMicroempresariosPorMailConsultor';
            $encabezados = array(
                "Content-Type: application/x-www-form-urlencoded"
            );
            $email = array('MailConsultor' => $correo);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($email));
            $resultado = curl_exec($ch);

            if ($resultado === false) {
                $resultado = curl_error($ch);
                curl_close($ch);
                throw new Exception($resultado, 1);
            }
            curl_close($ch);
            if(strpos($resultado, "DataTable") === false) throw new Exception("No se encontro informacion para el consultor", 1);
            $xml_cuerpo = simplexml_load_string($resultado, 'SimpleXMLElement', LIBXML_NOCDATA);
            $xml_cuerpo = $xml_cuerpo->xpath('//Respuesta');
            $xml_cuerpo = json_decode(json_encode((array)$xml_cuerpo), TRUE);
            if(count($xml_cuerpo) <= 0) throw new Exception("No existe ningun registro", 1);
            $encabezados = array();
            
            $rfcs = array();
            foreach ($xml_cuerpo as $item) {
                $rfcs[] = $item["rfc"];
            }
            $rfcs = array_unique($rfcs);
            //print_r($rfcs);

            $emresarios = array();
            foreach ($rfcs as $value) {
                $ultimo = null;
                foreach ($xml_cuerpo as $array) {
                    if($value == $array["rfc"]){
                        //aqui evaluar lo de la fecha
                        $ultimo = $array;
                    }
                }
                $empresarios[] = $ultimo;
            }
            return $empresarios;
        }catch(Exception $e){
            return "fallo";
        }
    }


    public function obtenerFolio($valor){
        $queryA = "SELECT folio FROM netwarstore.inovekia_empresario_folio as ief 
                    INNER JOIN netwarstore.inovekia_empresario as ie on ief.id_empresario = ie.id
                    WHERE ie.rfc = '".$valor["rfc"]."' and ie.creado >= '2017-01-30';";

        $folioA = $this->queryArray($queryA);
        return $folioA["rows"][0]["folio"];
    }

    public function obtenerFolios(){
        $sql = "SELECT folio FROM netwarstore.inovekia_empresario_folio GROUP BY folio;";
        $sql = $this->queryArray($sql);
        return $sql["rows"];
    }

    public function obtenerConsultorPorFolio($folio){
        $sql = "SELECT e.idempleado AS id, e.nombre, au.correoelectronico AS email FROM netwarstore.inovekia_empresario_folio AS ief 
                INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_empresario = ief.id_empresario
                INNER JOIN _dbmlog0000010592.empleados AS e ON e.idempleado = iec.id_consultor 
                INNER JOIN _dbmlog0000010592.administracion_usuarios AS au ON au.idempleado = e.idempleado
                WHERE folio = '$folio' GROUP BY e.idempleado;";
        $sql = $this->queryArray($sql);
        return array("status" => true, "consultores" => $sql["rows"]);
    }

}

?>