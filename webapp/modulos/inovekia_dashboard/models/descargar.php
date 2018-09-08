<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class DescargarModel extends Connection
{
   
    public function relacion(){
        try {
            $registros = array();
            $sql = "  SELECT io.nombre AS Organismo, e.nombre AS Consultor, au.usuario AS ConsultorUsuario, ie.nombre AS Empresario, 'Admin1' As EmpresarioUsuario, ief.folio AS Folio, ie.creado AS Creado FROM netwarstore.inovekia_empresario_consultor AS iec 
                                INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = iec.id_empresario 
                                LEFT OUTER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = ie.id 
                                INNER JOIN empleados AS e ON e.idempleado = iec.id_consultor 
                                INNER JOIN accelog_usuarios AS au ON au.idempleado = e.idempleado
                                INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = au.idempleado 
                                INNER JOIN netwarstore.inovekia_organismo AS io ON io.id = ioc.id_organismo 
                                ORDER BY Organismo, Consultor, Empresario, Creado ASC;";
            $sql = $this->queryArray($sql);
            if($sql["status"]){
                $registros = $sql["rows"];
            } else {
                throw new Exception($sql["mensaje"], 1);
            }
            $json = array("status" => true, "registros" => $registros);
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

    public function formularioUno(){
        try {
            $registros = array();
            $sql = "SELECT io.nombre AS organismo, ief.folio AS folio, c.id AS id_empresario, c.nombre AS nombre, c.rfc AS rfc, e.nombre AS consultor, ief.folio AS folio, c.razon AS razon, 
                    IF(if1.f1p5a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p5a)) AS rfcH1, 
                    IF(if1.f1p5b = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p5b)) AS rfcH2, 
                    IF(if1.f1p5c = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p5c)) AS rfcH3, 
                    if1.f1p6a AS telefono, if1.f1p7a AS correo, if1.f1p8a AS tamano, if1.f1p9a AS giro, if1.f1p10a AS rubro, if1.f1p11a AS tipo_vialidad, if1.f1p12a AS vialidad, if1.f1p13a AS num_ext, if1.f1p14a AS tipo_asentamiento, if1.f1p15a AS asentamiento, if1.f1p16a AS cp, if1.f1p17a AS localidad, if1.f1p18a AS municipio, if1.f1p19a AS estado, if1.f1p20a AS entre, if1.f1p21a AS posterior, if1.f1p22a AS descripcion, 
                    IF(if1.f1p23a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p23a)) AS caeH1, 
                    IF(if1.f1p24a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p24a)) AS ifeH1, 
                    IF(if1.f1p24b = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p24b)) AS ifeH2, 
                    IF(if1.f1p25a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p25a)) AS lrhH1, 
                    IF(if1.f1p26a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p26a)) AS ifehH1, 
                    IF(if1.f1p26b = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p26b)) AS ifehH2, 
                    IF(if1.f1p27a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p27a)) AS lrmH1, 
                    IF(if1.f1p28a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p28a)) AS ifemH1, 
                    IF(if1.f1p28b = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p28b)) AS ifemH2, 
                    IF(if1.f1p29a = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p29a)) AS reciboH1, 
                    IF(if1.f1p29b = 'noAplica', 'N.A.', CONCAT('https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/archivos/formularios/', '', if1.f1p29b)) AS reciboH2, 
                    if1.f1p30a AS empleados_hombre, if1.f1p30b AS empleados_mujer, if1.f1p30c AS empleados_indigena, if1.f1p30d AS empleados_discapacitado, if1.f1p31a AS ventas_anterior, if1.f1p32a AS ventas_actual, if1.f1p33a AS nomina, if1.f1p34a AS activos, if1.f1p35a AS email, if1.f1p36a AS contrasena 
                    FROM netwarstore.inovekia_organismo AS io 
                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_organismo = io.id 
                    INNER JOIN empleados AS e ON e.idempleado = ioc.id_consultor 
                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                    INNER JOIN netwarstore.customer AS c ON c.id = iec.id_empresario 
                    LEFT OUTER JOIN netwarstore.inovekia_formulario_uno AS if1 ON if1.id_empresario = c.id 
                    WHERE io.activo = 1 AND ioc.activo = 1 AND iec.activo = 1";
            $sql = $this->queryArray($sql);
            if($sql["status"]){
                $registros = $sql["rows"];
            } else {
                throw new Exception($sql["mensaje"], 1);
            }
            $json = array("status" => true, "registros" => $registros);
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

    public function seguimiento(){
        try {
            $registros = array();
            $sql = "SELECT io.nombre AS organismo, ief.folio AS folio, c.id AS id_empresario, c.nombre AS nombre, c.rfc AS rfc, e.nombre AS consultor FROM netwarstore.inovekia_organismo AS io 
                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_organismo = io.id 
                    INNER JOIN empleados AS e ON e.idempleado = ioc.id_consultor 
                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                    INNER JOIN netwarstore.customer AS c ON c.id = iec.id_empresario 
                    WHERE io.activo = 1 AND ioc.activo = 1 AND iec.activo = 1";
            $sql = $this->queryArray($sql);
            if(!$sql["status"]) throw new Exception("Error Processing Request", 1);
        
            foreach ($sql["rows"] as $empresario) {
                $sql = "SELECT * FROM (SELECT * FROM netwarstore.inovekia_seguimiento WHERE id_empresario = ". $empresario["id_empresario"] ." ORDER BY ultimo_slide DESC) AS sub GROUP BY sub.id_curso;";
                $sql = $this->queryArray($sql);
                if(!$sql["status"]) throw new Exception("Error Processing Request", 1);
                $seguimientos = array();
                $cursos = array("atencion_clientes", "operaciones_contables", "inventarios", "hardware_software", "capacidad_endeudamiento");
                foreach ($sql["rows"] as $seguimiento) {
                    $seguimientos[$cursos[((int)$seguimiento["id_curso"])-1]] = $seguimiento["ultimo_slide"];
                }
                foreach ($cursos as $curso) {
                    if(!isset($seguimientos[$curso])) $seguimientos[$curso] = "N.A.";
                }
                $formularios =  array();
                $formularios["sesion_inicial"] = "enviado";
                $formularios["factibilidad_comercial"] = "enviado";
                $formularios["diagnostico_madurez"] = "enviado";
                $formularios["plan_financiero"] = "enviado";
                $formularios["micro_mercado"] = "enviado";

                $query_f1 = "SELECT * FROM netwarstore.inovekia_formulario_uno WHERE id_empresario = ". $empresario["id_empresario"] ." ORDER BY id DESC"; 
                $result_query_f1 = $this->queryArray($query_f1);
                
                if($result_query_f1["total"] != 0){
                    $datos = $result_query_f1["rows"][0];
                    foreach ($datos as $key => $value) {
                        if($value == ""){
                            $formularios["sesion_inicial"] = "guardado";
                        }
                    }
                }else{
                    $formularios["sesion_inicial"] = "NoContestado";
                } 

                $query_f6 = "SELECT * FROM netwarstore.inovekia_formulario_seis WHERE id_empresario = ". $empresario["id_empresario"] ." ORDER BY id DESC"; 
                $result_query_f6 = $this->queryArray($query_f6);
                
                if($result_query_f6["total"] != 0){
                    $datos = $result_query_f6["rows"][0];
                    foreach ($datos as $key => $value) {
                        if(($key != "f6p5a")||($key != "f6p7a")||($key != "f6p12a")||($key != "f6p18a")){
                            if($value == ""){
                                $formularios["factibilidad_comercial"] = "guardado";
                            }
                        }
                    }
                }else{
                    $formularios["factibilidad_comercial"] = "NoContestado";
                } 

                $query_f4 = "SELECT * FROM netwarstore.inovekia_formulario_cuatro WHERE id_empresario = ". $empresario["id_empresario"] ." ORDER BY id DESC"; 
                $result_query_f4 = $this->queryArray($query_f4);
                
                if($result_query_f4["total"] != 0){
                    $datos = $result_query_f4["rows"][0];
                    foreach ($datos as $key => $value) {
                        if($value == ""){
                            $formularios["diagnostico_madurez"] = "guardado";
                        }
                    }
                }else{
                    $formularios["diagnostico_madurez"] = "NoContestado";
                }

                $query_f8 = "SELECT * FROM netwarstore.inovekia_formulario_ocho WHERE id_empresario = ". $empresario["id_empresario"] ." ORDER BY id DESC"; 
                $result_query_f8 = $this->queryArray($query_f8);
                
                if($result_query_f8["total"] != 0){
                    $datos = $result_query_f8["rows"][0];
                    foreach ($datos as $key => $value) {
                        if($value == ""){
                            $formularios["plan_financiero"] = "guardado";
                        }
                    }
                }else{
                    $formularios["plan_financiero"] = "NoContestado";
                }

               $query_f9 = "SELECT * FROM netwarstore.inovekia_formulario_nueve WHERE id_empresario = ". $empresario["id_empresario"] ." ORDER BY id DESC"; 
                $result_query_f9 = $this->queryArray($query_f9);
                
                if($result_query_f9["total"] != 0){
                    $datos = $result_query_f9["rows"][0];
                    foreach ($datos as $key => $value) {
                        if($value == ""){
                            $formularios["micro_mercado"] = "guardado";
                        }
                    }
                }else{
                    $formularios["micro_mercado"] = "NoContestado";
                }
                $registros[] = array("empresario" => $empresario, "seguimientos" => $seguimientos, "formularios" => $formularios);
            }
            $json = array("status" => true, "registros" => $registros);
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

}

?>