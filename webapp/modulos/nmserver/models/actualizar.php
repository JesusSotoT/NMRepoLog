<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ActualizarModel extends Connection
{
   
    public function grid(){
        if(isset($_SESSION)); session_start();
        $registros = array();
        $actualizaciones = 'SELECT * FROM nmserver;';
        $actualizaciones = $this->queryArray($actualizaciones);
        $actualizaciones = $actualizaciones['rows'];

        foreach ($actualizaciones as $actualizacion) {
            $item = array();
            $item[0] = $actualizacion['version'];
            $item[1] = $actualizacion['fecha'];
            $item[2] = ($actualizacion['activo'] == 1) ? "Activo" : "Obsoleto";
            $item[3] = "<a href='https://www.netwarmonitor.com/clientes/". $_SESSION['accelog_nombre_instancia'] ."/webapp/modulos/nmserver/". $actualizacion["url_android"] ."'><i class='fa fa-download'></i></a>";
            $item[4] = "<a href='https://www.netwarmonitor.com/clientes/". $_SESSION['accelog_nombre_instancia'] ."/webapp/modulos/nmserver/". $actualizacion["url_windows"] ."'><i class='fa fa-download'></i></a>";
            $registros[] = $item;
        }
        return array("status" => true, "registros" => $registros);
    }

    public function actualizar($datos){
        $nombre_android = date("YmdHis") .".". pathinfo($_FILES["zip_android"]['name'], PATHINFO_EXTENSION);
        $nombre_windows = date("YmdHis") .".". pathinfo($_FILES["zip_windows"]['name'], PATHINFO_EXTENSION);
        $directorio_android = "empaquetados/android/". $nombre_android;
        $directorio_windows = "empaquetados/windows/". $nombre_windows;
        try{
            if (!move_uploaded_file($_FILES["zip_android"]["tmp_name"], $directorio_android)) throw new Exception("No fue posible subir la nueva version, intentalo nuevamente", 1);
            if (!move_uploaded_file($_FILES["zip_windows"]["tmp_name"], $directorio_windows)) throw new Exception("No fue posible subir la nueva version, intentalo nuevamente", 2);
            $sql = "UPDATE nmserver SET activo = 0;";
            $sql = $this->queryArray($sql);
            if(!$sql["status"]) throw new Exception("No fue posible subir la nueva version, intentalo nuevamente", 3);
            $sql = "INSERT INTO nmserver VALUES(null, '". $datos["version"] ."', '". date("Y-m-d H:i:s") ."', '". $directorio_android ."', '". $directorio_windows ."', 1);";
            $sql = $this->queryArray($sql);
            if(!$sql["status"]) throw new Exception("No fue posible subir la nueva version, intentalo nuevamente", 4);


            $instancias = "SELECT instancia FROM netwarmonitor.customer WHERE status_instancia = 1;";
            $instancias = $this->queryArray($instancias);
            if(!$instancias["status"]) throw new Exception("No fue posible notificar la nueva version, intentalo nuevamente", 5);
            foreach ($instancias["rows"] as $instancia) {
                $url = "https://www.netwarmonitor.com/clientes/". $instancia["instancia"] ."/hibrido_api/ajax.php?c=actualizar&f=actualizar";
                $parametros = array (
                    'url' => "https://www.netwarmonitor.com/clientes/". $_SESSION['accelog_nombre_instancia'] ."/webapp/modulos/nmserver/". $directorio_android,
                );
                $parametros = json_encode($parametros);
                $encabezados = array (
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
                $resultado = curl_exec($ch);
                curl_close($ch);
            }

            $json = array("status" => true);
        }catch(Exception $e){
            unlink($directorio_android);
            unlink($directorio_windows);
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

}

?>