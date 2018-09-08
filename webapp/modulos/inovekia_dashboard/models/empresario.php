<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi 

class EmpresarioModel extends Connection
{
   
    public function grid($consultor){
        $registros = array();

        if($consultor > 0){
            $empresarios = 'SELECT ie.id, ie.razon, iec.id_consultor 
                        FROM netwarstore.inovekia_empresario AS ie 
                        LEFT JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_empresario = ie.id 
                        WHERE ie.activo = 1 AND (iec.id_empresario IS NULL OR iec.id_consultor = '. $consultor .') AND ie.creado >= "2017-03-15" 
                        ORDER BY ie.creado DESC 
                        LIMIT 1000;';
            $empresarios = $this->queryArray($empresarios);
            $empresarios = $empresarios['rows'];
            foreach ($empresarios as $empresario) {
                $item = array();
                $item[0] = $empresario['razon'];
                if(is_null($empresario['id_consultor'])){
                    $item[1] = "<a href='javascript:seleccionarEmpresario(\"". $consultor ."\", \"". $empresario['id'] ."\");'><i class='fa fa-check'></i></a>";
                    $item[2] = '';
                    /*$item[3] = '';
                    $item[4] = '';*/
                } else {
                    $item[1] = "<a href='javascript:eliminarEmpresario(\"". $consultor ."\", \"". $empresario['id'] ."\");'><i class='fa fa-close'></i></a>";
                    $item[2] = "<a href='javascript:visita(\"". $consultor ."\", \"". $empresario['id'] ."\");'><i class='fa fa-calendar'></i></a>";
                    /*$item[3] = "<a href='javascript:seguimiento(\"". $consultor ."\", \"". $empresario['id'] ."\");'><i class='fa fa-tablet'></i></a>";
                    $item[4] = "<a href='javascript:formularios(\"". $consultor ."\", \"". $empresario['id'] ."\");'><i class='fa fa-file-text'></i></a>";*/
                }
                $registros[] = $item;
            }
        } else {
            $empresarios = 'SELECT ie.id, ie.razon, ief.folio 
                            FROM netwarstore.inovekia_empresario AS ie 
                            LEFT OUTER JOIN netwarstore.inovekia_empresario_folio AS ief 
                            ON ief.id_empresario = ie.id 
                            WHERE ie.activo = 1 
                            ORDER BY ie.creado DESC 
                            LIMIT 1000;';
            $empresarios = $this->queryArray($empresarios);
            $empresarios = $empresarios['rows'];
            foreach ($empresarios as $empresario) {
                $item = array();
                $item[0] = $empresario['razon'];
                $item[1] = "<a href='javascript:mostrarFolio(\"". $empresario['id'] ."\");'><i class='fa fa-vcard'></i></a>";
                $registros[] = $item;
            }
        }

        return array("status" => true, "registros" => $registros);
    }

    public function empresario($consultor, $cliente){
        if($cliente == "inovekia"){
            $registros = array();

            $empresarios = 'SELECT ie.id, ie.razon, iec.id_consultor 
                            FROM netwarstore.inovekia_empresario AS ie 
                            LEFT JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_empresario = ie.id 
                            WHERE ie.activo = 1 AND iec.id_consultor = '. $consultor .';';
            $empresarios = $this->queryArray($empresarios);
            $empresarios = $empresarios['rows'];
            return array("status" => true, "registros" => $empresarios);
        } else {
            return array("status" => true, "registros" => array()); 
        }
    }

    public function seleccionar($consultor, $empresario){
        $seleccionar = "INSERT INTO netwarstore.inovekia_empresario_consultor VALUES(null, ". $empresario .", ". $consultor .", 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."');";
        $seleccionar = $this->queryArray($seleccionar);
        if($seleccionar['insertId'] > 0){
            $respuesta = array("status" => true);
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        return $respuesta;
    }

    public function eliminar($consultor, $empresario){
        $eliminar = "DELETE FROM netwarstore.inovekia_calendario_consultor WHERE id_empresario = ". $empresario ." AND id_consultor = ". $consultor .";";
        $eliminar = $this->queryArray($eliminar);
        if($eliminar['status']){
            $eliminar = "DELETE FROM netwarstore.inovekia_empresario_consultor WHERE id_empresario = ". $empresario ." AND id_consultor = ". $consultor .";";
            $eliminar = $this->queryArray($eliminar);
            if($eliminar['status']){
                $respuesta = array("status" => true);
            } else {
                $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
            }
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        return $respuesta;
    }

    public function visita($visita){
        $visita_consultor = "SELECT id FROM netwarstore.inovekia_calendario_consultor WHERE id_consultor = ". $visita['consultor'] ." AND id_empresario = ". $visita['empresario'] .";";
        $visita_consultor = $this->queryArray($visita_consultor);
        if($visita_consultor['total'] > 0){
            $visita_consultor = "UPDATE netwarstore.inovekia_calendario_consultor SET fecha = '". $visita['fecha'] ."', hora = '". $visita['hora'] ."', latitud = ". $visita['latitud'] .", longitud = ". $visita['longitud'] .", modificado = '". date("Y-m-d H:i:s") ."' WHERE id_consultor = ". $visita['consultor'] ." AND id_empresario = ". $visita['empresario'] .";";
        } else {
            $visita_consultor = "INSERT INTO netwarstore.inovekia_calendario_consultor VALUES(null, ". $visita['consultor'] .", ". $visita['empresario'] .", '". $visita['fecha'] ."', '". $visita['hora'] ."', ". $visita['latitud'] .", ". $visita['longitud'] .", 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."');";
        }
        $visita_consultor = $this->queryArray($visita_consultor);
        if($visita_consultor['status']){
            $respuesta = array("status" => true);
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        return $respuesta;
    }

    public function seguimiento($consultor, $empresario){
        $registros = array();

        $seguimientos = 'SELECT ic.nombre, i.creado, i.ultimo_slide, i.seguimiento FROM netwarstore.inovekia_seguimiento AS i INNER JOIN netwarstore.inovekia_curso AS ic ON ic.id = i.id_curso WHERE i.id_empresario = '. $empresario .' ORDER BY i.ultimo_slide DESC;';
        $seguimientos = $this->queryArray($seguimientos);
        $seguimientos = $seguimientos['rows'];

        foreach ($seguimientos as $seguimiento) {
            $item = array();
            $item[0] = $seguimiento['creado'];
            $item[1] = $seguimiento['ultimo_slide'];
            $scorm = json_decode($seguimiento['seguimiento'], true);
            $item[2] = $scorm["viewDuration"];
            $registros[] = $item;
        }

        return array("status" => true, "registros" => $registros);
    }

    public function seguimientoResumen($consultor, $empresario){
        $registros = array();

        $seguimientos = 'SELECT ic.nombre, i.creado, i.ultimo_slide, i.seguimiento FROM netwarstore.inovekia_seguimiento AS i INNER JOIN netwarstore.inovekia_curso AS ic ON ic.id = i.id_curso WHERE i.id_empresario = '. $empresario .' ORDER BY i.ultimo_slide DESC;';
        $seguimientos = $this->queryArray($seguimientos);
        $seguimientos = $seguimientos['rows'];

        foreach ($seguimientos as $seguimiento) {
            $item = array();
            $item[0] = $seguimiento['nombre'];
            $item[0] = $seguimiento['creado'];
            $item[1] = $seguimiento['ultimo_slide'];
            $scorm = json_decode($seguimiento['seguimiento'], true);
            $item[2] = $scorm["viewDuration"];
            $registros[] = $item;
            break;
        }

        return array("status" => true, "registros" => $registros);
    }

    public function folio($empresario, $folio){
        $registro = "SELECT id FROM netwarstore.inovekia_empresario_folio WHERE id_empresario = ". $empresario;
        $registro = $this->queryArray($registro);
        if(count($registro["rows"]) > 0){
            $registro = "UPDATE netwarstore.inovekia_empresario_folio SET folio = '". $folio ."' WHERE id = ". $registro["rows"][0]["id"] .";";
        } else {
            $registro = "INSERT INTO netwarstore.inovekia_empresario_folio VALUES(NULL, ". $empresario .", '". $folio ."', 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."')";
        }
        $registro = $this->queryArray($registro);
        if($registro['status']){
            $respuesta = array("status" => true);
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        return $respuesta;
    }

    public function mostrarFolio($empresario){
        $registro = "SELECT folio FROM netwarstore.inovekia_empresario_folio WHERE id_empresario = ". $empresario;
        $registro = $this->queryArray($registro);
        if(count($registro["rows"]) > 0){
            $folio = $registro["rows"][0]["folio"];
        } else {
            $folio = "";
        }

        $folios = array();
        $empresarios = 'SELECT ie.id, ie.razon, ief.folio 
                        FROM netwarstore.inovekia_empresario AS ie 
                        LEFT OUTER JOIN netwarstore.inovekia_empresario_folio AS ief 
                        ON ief.id_empresario = ie.id 
                        WHERE ie.activo = 1 
                        ORDER BY ie.creado DESC 
                        LIMIT 1000;';
        $empresarios = $this->queryArray($empresarios);
        $empresarios = $empresarios['rows'];
        foreach ($empresarios as $empresario) {
            $folios[] = $empresario['folio'];
        }
        $folios = array_values(array_unique(array_diff($folios, [null])));

        $respuesta = array("status" => true, "folio" => $folio, "folios" => $folios);
        return $respuesta;
    }

}

?>