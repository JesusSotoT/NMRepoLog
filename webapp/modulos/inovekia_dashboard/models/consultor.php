<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ConsultorModel extends Connection
{
   
    public function grid($organismo = 0){
        $registros = array();
        if($organismo == 0){
            $consultores = 'SELECT e.idempleado, e.nombre, u.usuario, au.correoelectronico FROM empleados AS e 
                            INNER JOIN accelog_usuarios AS u ON u.idempleado = e.idempleado 
                            INNER JOIN administracion_usuarios AS au ON au.idempleado = u.idempleado 
                            INNER JOIN accelog_usuarios_per AS up ON up.idempleado = au.idempleado 
                            WHERE e.visible = -1;';
            $consultores = $this->queryArray($consultores);
            $consultores = $consultores['rows'];

            foreach ($consultores as $consultor) {
                $item = array();
                $item[0] = $consultor['nombre'];
                $item[1] = $consultor['usuario'];
                $item[2] = "<a href='javascript:mostrarEmpresarios(\"". $consultor['idempleado'] ."\");'><i class='fa fa-address-book'></i></a>";
                $item[3] = "<a href='javascript:mostrarInadem(\"". $consultor['idempleado'] ."\",\"". $consultor['correoelectronico'] ."\");'><i class='fa fa-area-chart'></i></a>";
                $registros[] = $item;
            }
            return array("status" => true, "registros" => $registros);

        }else{
            $consultores = 'SELECT e.idempleado, e.nombre, iso.id_organismo 
                            FROM empleados AS e 
                            INNER JOIN accelog_usuarios AS u ON u.idempleado = e.idempleado 
                            INNER JOIN administracion_usuarios AS au ON au.idempleado = u.idempleado 
                            INNER JOIN accelog_usuarios_per AS up ON up.idempleado = au.idempleado 
                            LEFT JOIN netwarstore.inovekia_organismo_consultor AS iso ON iso.id_consultor = e.idempleado
                            WHERE e.visible = -1 AND (iso.id_consultor IS NULL OR iso.id_organismo = '. $organismo .')
                            LIMIT 100;';

            $consultores = $this->queryArray($consultores);
            $consultores = $consultores['rows'];

            foreach ($consultores as $consultor) {
                $item = array();
                $item[0] = $consultor['nombre'];

                if(is_null($consultor['id_organismo'])){
                    $item[1] = "<a href='javascript:seleccionarConsultor(\"". $organismo ."\", \"". $consultor['idempleado'] ."\");'><i class='fa fa-check'></i></a>";            
                }else{
                    $item[1] = "<a href='javascript:eliminarConsultor(\"". $organismo ."\", \"". $consultor['idempleado'] ."\");'><i class='fa fa-close'></i></a>";
                }

                $registros[] = $item;
            }
        }
        return array("status" => true, "registros" => $registros);
    }

    public function seleccionar($organismo, $consultor){
        $seleccionar = "INSERT INTO netwarstore.inovekia_organismo_consultor VALUES(null, ". $consultor .", ". $organismo .", 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."');";
        $seleccionar = $this->queryArray($seleccionar);
        if($seleccionar['insertId'] > 0){
            $respuesta = array("status" => true);
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        return $respuesta;
    }

    public function eliminar($organismo, $consultor){
        $eliminar = "DELETE FROM netwarstore.inovekia_organismo_consultor WHERE id_organismo = ". $organismo ." AND id_consultor = ". $consultor .";";
        $eliminar = $this->queryArray($eliminar);
        if($eliminar['status']){
            $respuesta = array("status" => true);
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        
        return $respuesta;
    }

    public function inadem($consultor, $correo){
        try{
            $update = "UPDATE administracion_usuarios SET correoelectronico = '". $correo ."' WHERE idempleado = ". $consultor .";";
            $update = $this->queryArray($update);

            if($update["status"]){
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
                foreach ($xml_cuerpo as $item) {
                    $encabezados = array_merge($encabezados, array_keys($item));
                }
                $encabezados = array_unique($encabezados);
                natcasesort($encabezados);
                $encabezados = array_values($encabezados);
                foreach ($xml_cuerpo as &$item) {
                    foreach ($encabezados as $encabezado) {
                        if(!isset($item[$encabezado])) $item[$encabezado] = "";
                    }
                    foreach ($item as &$informacion) {
                        if(strpos($informacion, "http://") !== false) {
                            $informacion = "<i onclick='javascript:window.open(\"". $informacion ."\", \"_blank\");' class='fa fa-file-o'></i>";
                        }
                    }
                    uksort($item, 'strcasecmp');
                    $item = array_values($item);
                }
                $json = array("status" => true, "encabezados" => $encabezados, "xml" => $xml_cuerpo);
            } else {
                throw new Exception("No se encontro un email para el consultor", 1);
            }
        }catch(Exception $e){
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

}

?>