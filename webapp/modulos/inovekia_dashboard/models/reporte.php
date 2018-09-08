<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ReporteModel extends Connection
{
   
    public function cursosOrganismo(){
        try{
            $sql_organismo = "SELECT * FROM netwarstore.inovekia_organismo WHERE activo = 1 ". ((isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0) ? " AND id = ". $_REQUEST["id_organismo"] : "") .";";
            $sql_organismo = $this->queryArray($sql_organismo);
            if($sql_organismo["status"]){
                $organismos = array();
                foreach ($sql_organismo["rows"] as $organismo) {
                    $sql_informacion = "SELECT iec.id FROM netwarstore.inovekia_organismo AS io 
                                        INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_organismo = io.id 
                                        INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                                        INNER JOIN empleados AS e ON e.idempleado = ioc.id_consultor 
                                        INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = iec.id_empresario 
                                        WHERE io.id = ". $organismo["id"] ." AND ie.creado >= '2017-03-15';";
                    $sql_informacion = $this->queryArray($sql_informacion);
                    if($sql_informacion["status"]){
                        $total = array();
                        $total["total"] = $sql_informacion["total"] * 5;
                        $total["completo"] = $total["iniciado"] = 0;
                        if($total["total"] > 0){
                            $sql_informacion = "SELECT e.nombre AS consultor, ie.razon AS empresario, ic.nombre AS curso,
                                                (
                                                    IF(ies.id_curso = 1, 
                                                        IF(MAX(ies.ultimo_slide) = 75, 'COMPLETO', 'INICIADO'), 
                                                        IF(ies.id_curso = 2, 
                                                            IF(MAX(ies.ultimo_slide) = 77, 'COMPLETO', 'INICIADO'), 
                                                            IF(ies.id_curso = 3, 
                                                                IF(MAX(ies.ultimo_slide) = 53, 'COMPLETO', 'INICIADO'), 
                                                                IF(ies.id_curso = 4, 
                                                                    IF(MAX(ies.ultimo_slide) = 72, 'COMPLETO', 'INICIADO'), 
                                                                    IF(ies.id_curso = 5, 
                                                                        IF(MAX(ies.ultimo_slide) = 52, 'COMPLETO', 'INICIADO'), 
                                                                        'INDEFINIDO'
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                ) AS estatus 
                                                FROM netwarstore.inovekia_seguimiento AS ies 
                                                INNER JOIN netwarstore.inovekia_curso AS ic ON ic.id = ies.id_curso 
                                                INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = ies.id_empresario 
                                                INNER JOIN empleados AS e ON e.idempleado = ies.id_consultor 
                                                INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                                WHERE ioc.id_organismo = ". $organismo["id"] ." AND ie.creado >= '2017-03-15'
                                                GROUP BY consultor, empresario, curso 
                                                ORDER BY consultor ASC, empresario ASC, curso ASC;";
                            $sql_informacion = $this->queryArray($sql_informacion);
                            if($sql_informacion["status"]){
                                foreach ($sql_informacion["rows"] as $registro) {
                                    ($registro["estatus"] == "COMPLETO") ? $total["completo"]++ : $total["iniciado"]++;
                                }
                                $total["no"] = $total["total"] - ($total["completo"] + $total["iniciado"]);
                                $total["completo"] = ( $total["completo"] * 100) / $total["total"];
                                $total["iniciado"] = ( $total["iniciado"] * 100) / $total["total"];
                                $total["no"] = ( $total["no"] * 100) / $total["total"];
                                $total["total"] = $total["total"] / 5;
                                $organismos[$organismo["nombre"]] = $total;
                            } else {
                                throw new Exception($sql_informacion["msg"], 1);
                            }
                        } else {
                            $total["completo"] = "N/A";
                            $total["iniciado"] = "N/A";
                            $total["no"] = "N/A";
                            $organismos[$organismo["nombre"]] = $total;
                        }
                    } else {
                        throw new Exception($sql_informacion["msg"], 1);
                    }
                }

                $sql_s_organismo = "SELECT id, nombre FROM netwarstore.inovekia_organismo WHERE activo = 1 ORDER BY nombre ASC;";
                $sql_s_organismo = $this->queryArray($sql_s_organismo);
                if($sql_s_organismo["status"]){
                    $html = "<option value='0'>Selecciona un organismo</option>";
                    foreach ($sql_s_organismo["rows"] as $organismo) {
                        $html .= "<option value='". $organismo["id"] ."'>". $organismo["nombre"] ."</option>";
                    }
                    $json["f_organismo"] = $html;
                } else {
                    throw new Exception($sql_s_organismo["msg"], 1);
                }

                $json["organismos"] = $organismos;
                $json["status"] = true;
            } else {
                throw new Exception($sql_informacion["msg"], 1);
            }
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

    public function cursosFolio(){
        try{
            $sql_folio = "  SELECT ief.* FROM netwarstore.inovekia_empresario_folio AS ief 
                            INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_empresario = ief.id_empresario 
                            INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = iec.id_consultor 
                            WHERE ief.activo = 1 
                            ". ((isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0) ? " AND ioc.id_organismo = ". $_REQUEST["id_organismo"] : "") ." 
                            ". ((isset($_REQUEST["id_consultor"]) && $_REQUEST["id_consultor"] > 0) ? " AND ioc.id_consultor = ". $_REQUEST["id_consultor"] : "") ." 
                            ". ((isset($_REQUEST["folio"]) && $_REQUEST["folio"] != '') ? " AND ief.folio = '". $_REQUEST["folio"] ."'" : "") ." 
                            GROUP BY ief.folio;";
            $sql_folio = $this->queryArray($sql_folio);
            if($sql_folio["status"]){
                $folios = array();
                foreach ($sql_folio["rows"] as $folio) {
                    $sql_informacion = "SELECT * FROM netwarstore.inovekia_empresario_folio WHERE folio = '". $folio["folio"] ."' AND activo = 1;";
                    $sql_informacion = $this->queryArray($sql_informacion);
                    if($sql_informacion["status"]){
                        $total = array();
                        $total["total"] = $sql_informacion["total"] * 5;
                        $total["completo"] = $total["iniciado"] = 0;
                        if($total["total"] > 0){
                            $sql_informacion = "SELECT e.nombre AS consultor, ie.razon AS empresario, ic.nombre AS curso,
                                                (
                                                    IF(ies.id_curso = 1, 
                                                        IF(MAX(ies.ultimo_slide) = 75, 'COMPLETO', 'INICIADO'), 
                                                        IF(ies.id_curso = 2, 
                                                            IF(MAX(ies.ultimo_slide) = 77, 'COMPLETO', 'INICIADO'), 
                                                            IF(ies.id_curso = 3, 
                                                                IF(MAX(ies.ultimo_slide) = 53, 'COMPLETO', 'INICIADO'), 
                                                                IF(ies.id_curso = 4, 
                                                                    IF(MAX(ies.ultimo_slide) = 72, 'COMPLETO', 'INICIADO'), 
                                                                    IF(ies.id_curso = 5, 
                                                                        IF(MAX(ies.ultimo_slide) = 52, 'COMPLETO', 'INICIADO'), 
                                                                        'INDEFINIDO'
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                ) AS estatus 
                                                FROM netwarstore.inovekia_seguimiento AS ies 
                                                INNER JOIN netwarstore.inovekia_curso AS ic ON ic.id = ies.id_curso 
                                                INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = ies.id_empresario 
                                                INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = ie.id 
                                                INNER JOIN empleados AS e ON e.idempleado = ies.id_consultor 
                                                WHERE ief.folio = '". $folio["folio"] ."' AND ie.creado >= '2017-03-15'
                                                GROUP BY empresario, curso 
                                                ORDER BY consultor ASC, empresario ASC, curso ASC;";
                            $sql_informacion = $this->queryArray($sql_informacion);
                            if($sql_informacion["status"]){
                                foreach ($sql_informacion["rows"] as $registro) {
                                    ($registro["estatus"] == "COMPLETO") ? $total["completo"]++ : $total["iniciado"]++;
                                }
                                $total["no"] = $total["total"] - ($total["completo"] + $total["iniciado"]);
                                $total["completo"] = ( $total["completo"] * 100) / $total["total"];
                                $total["iniciado"] = ( $total["iniciado"] * 100) / $total["total"];
                                $total["no"] = ( $total["no"] * 100) / $total["total"];
                                $total["total"] = $total["total"] / 5;
                                $folios[$folio["folio"]] = $total;
                            } else {
                                throw new Exception($sql_informacion["msg"], 1);
                            }
                        } else {
                            $total["completo"] = "N/A";
                            $total["iniciado"] = "N/A";
                            $total["no"] = "N/A";
                            $folios[$folio["folio"]] = $total;
                        }
                    } else {
                        throw new Exception($sql_informacion["msg"], 1);
                    }
                }

                $sql_s_organismo = "SELECT id, nombre FROM netwarstore.inovekia_organismo WHERE activo = 1 ORDER BY nombre ASC;";
                $sql_s_organismo = $this->queryArray($sql_s_organismo);
                if($sql_s_organismo["status"]){
                    $html = "<option value='0'>Selecciona un organismo</option>";
                    foreach ($sql_s_organismo["rows"] as $organismo) {
                        $html .= "<option value='". $organismo["id"] ."'>". $organismo["nombre"] ."</option>";
                    }
                    $json["f_organismo"] = $html;
                } else {
                    throw new Exception($sql_s_organismo["msg"], 1);
                }

                if(isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0){
                    $sql_s_consultor = "SELECT e.idempleado, e.nombre FROM empleados AS e 
                                        INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                        WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY e.nombre ASC;";
                    $sql_s_consultor = $this->queryArray($sql_s_consultor);
                    if($sql_s_consultor["status"]){
                        $html = "<option value='0'>Selecciona un consultor</option>";
                        foreach ($sql_s_consultor["rows"] as $consultor) {
                            $html .= "<option value='". $consultor["idempleado"] ."'>". $consultor["nombre"] ."</option>";
                        }
                        $json["f_consultor"] = $html;
                    } else {
                        throw new Exception($sql_s_consultor["msg"], 1);
                    }

                    $sql_s_folio = "SELECT ief.folio FROM empleados AS e 
                                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                                    WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY ief.folio ASC;";
                    $sql_s_folio = $this->queryArray($sql_s_folio);
                    if($sql_s_folio["status"]){
                        $html = "<option value=''>Selecciona un folio</option>";
                        $sql_s_folio["rows"] = array_unique($sql_s_folio["rows"]);
                        foreach ($sql_s_folio["rows"] as $folio) {
                            $html .= "<option value='". $folio["folio"] ."'>". $folio["folio"] ."</option>";
                        }
                        $json["f_folio"] = $html;
                    } else {
                        throw new Exception($sql_s_folio["msg"], 1);
                    }
                }

                $json["folios"] = $folios;
                $json["status"] = true;
            } else {
                throw new Exception($sql_informacion["msg"], 1);
            }
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

    public function cursosConsultor(){
        try{
            $sql_consultor = "  SELECT * FROM empleados AS e 
                                INNER JOIN accelog_usuarios AS au ON au.idempleado = e.idempleado 
                                LEFT OUTER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = e.idempleado
                                LEFT OUTER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = iec.id_consultor 
                                LEFT OUTER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario
                                WHERE e.visible = -1 
                                ". ((isset($_REQUEST["id_consultor"]) && $_REQUEST["id_consultor"] > 0) ? " AND e.idempleado = ". $_REQUEST["id_consultor"] : "") ."
                                ". ((isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0) ? " AND ioc.id_organismo = ". $_REQUEST["id_organismo"] : "") ." 
                                ". ((isset($_REQUEST["folio"]) && $_REQUEST["folio"] != '') ? " AND ief.folio = '". $_REQUEST["folio"] ."'" : "") ." ;";
            $sql_consultor = $this->queryArray($sql_consultor);
            if($sql_consultor["status"]){
                $consultores = array();
                foreach ($sql_consultor["rows"] as $consultor) {
                    $sql_informacion = "SELECT * FROM netwarstore.inovekia_empresario_consultor WHERE id_consultor = ". $consultor["idempleado"] .";";
                    $sql_informacion = $this->queryArray($sql_informacion);
                    if($sql_informacion["status"]){
                        $total = array();
                        $total["total"] = $sql_informacion["total"] * 5;
                        $total["completo"] = $total["iniciado"] = 0;
                        if($total["total"] > 0){
                            $sql_informacion = "SELECT e.nombre AS consultor, ie.razon AS empresario, ic.nombre AS curso,
                                                (
                                                    IF(ies.id_curso = 1, 
                                                        IF(MAX(ies.ultimo_slide) = 75, 'COMPLETO', 'INICIADO'), 
                                                        IF(ies.id_curso = 2, 
                                                            IF(MAX(ies.ultimo_slide) = 77, 'COMPLETO', 'INICIADO'), 
                                                            IF(ies.id_curso = 3, 
                                                                IF(MAX(ies.ultimo_slide) = 53, 'COMPLETO', 'INICIADO'), 
                                                                IF(ies.id_curso = 4, 
                                                                    IF(MAX(ies.ultimo_slide) = 72, 'COMPLETO', 'INICIADO'), 
                                                                    IF(ies.id_curso = 5, 
                                                                        IF(MAX(ies.ultimo_slide) = 52, 'COMPLETO', 'INICIADO'), 
                                                                        'INDEFINIDO'
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                ) AS estatus 
                                                FROM netwarstore.inovekia_seguimiento AS ies 
                                                INNER JOIN netwarstore.inovekia_curso AS ic ON ic.id = ies.id_curso 
                                                INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = ies.id_empresario 
                                                INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_empresario = ie.id
                                                INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = iec.id_consultor 
                                                LEFT OUTER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = ie.id 
                                                INNER JOIN empleados AS e ON e.idempleado = ies.id_consultor 
                                                WHERE e.idempleado = ". $consultor["idempleado"] ." AND ie.creado >= '2017-03-15'
                                                ". ((isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0) ? " AND ioc.id_organismo = ". $_REQUEST["id_organismo"] : "") ." 
                                                ". ((isset($_REQUEST["folio"]) && $_REQUEST["folio"] != '') ? " AND ief.folio = '". $_REQUEST["folio"] ."'" : "") ." 
                                                GROUP BY consultor, empresario, curso 
                                                ORDER BY consultor ASC, empresario ASC, curso ASC;";
                            $sql_informacion = $this->queryArray($sql_informacion);
                            if($sql_informacion["status"]){
                                foreach ($sql_informacion["rows"] as $registro) {
                                    ($registro["estatus"] == "COMPLETO") ? $total["completo"]++ : $total["iniciado"]++;
                                }
                                $total["no"] = $total["total"] - ($total["completo"] + $total["iniciado"]);
                                $total["completo"] = ( $total["completo"] * 100) / $total["total"];
                                $total["iniciado"] = ( $total["iniciado"] * 100) / $total["total"];
                                $total["no"] = ( $total["no"] * 100) / $total["total"];
                                $total["total"] = $total["total"] / 5;
                                $consultores[$consultor["nombre"] . " [ ". $consultor["usuario"] ." ] "] = $total;
                            } else {
                                throw new Exception($sql_informacion["msg"], 1);
                            }
                        } else {
                            $total["completo"] = "N/A";
                            $total["iniciado"] = "N/A";
                            $total["no"] = "N/A";
                            $consultores[$consultor["nombre"] . " [ ". $consultor["usuario"] ." ] "] = $total;
                        }
                    } else {
                        throw new Exception($sql_informacion["msg"], 1);
                    }
                }

                $sql_s_organismo = "SELECT id, nombre FROM netwarstore.inovekia_organismo WHERE activo = 1 ORDER BY nombre ASC;";
                $sql_s_organismo = $this->queryArray($sql_s_organismo);
                if($sql_s_organismo["status"]){
                    $html = "<option value='0'>Selecciona un organismo</option>";
                    foreach ($sql_s_organismo["rows"] as $organismo) {
                        $html .= "<option value='". $organismo["id"] ."'>". $organismo["nombre"] ."</option>";
                    }
                    $json["f_organismo"] = $html;
                } else {
                    throw new Exception($sql_s_organismo["msg"], 1);
                }

                if(isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0){
                    $sql_s_consultor = "SELECT e.idempleado, e.nombre FROM empleados AS e 
                                        INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                        WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY e.nombre ASC;";
                    $sql_s_consultor = $this->queryArray($sql_s_consultor);
                    if($sql_s_consultor["status"]){
                        $html = "<option value='0'>Selecciona un consultor</option>";
                        foreach ($sql_s_consultor["rows"] as $consultor) {
                            $html .= "<option value='". $consultor["idempleado"] ."'>". $consultor["nombre"] ."</option>";
                        }
                        $json["f_consultor"] = $html;
                    } else {
                        throw new Exception($sql_s_consultor["msg"], 1);
                    }

                    $sql_s_folio = "SELECT ief.folio FROM empleados AS e 
                                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                                    WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY ief.folio ASC;";
                    $sql_s_folio = $this->queryArray($sql_s_folio);
                    if($sql_s_folio["status"]){
                        $html = "<option value=''>Selecciona un folio</option>";
                        $sql_s_folio["rows"] = array_unique($sql_s_folio["rows"]);
                        foreach ($sql_s_folio["rows"] as $folio) {
                            $html .= "<option value='". $folio["folio"] ."'>". $folio["folio"] ."</option>";
                        }
                        $json["f_folio"] = $html;
                    } else {
                        throw new Exception($sql_s_folio["msg"], 1);
                    }
                }

                $json["consultores"] = $consultores;
                $json["status"] = true;
            } else {
                throw new Exception($sql_informacion["msg"], 1);
            }
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

    public function cursosEmpresario(){
        try{
            $sql_empresario =   "SELECT * FROM netwarstore.inovekia_empresario AS ie 
                                INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_empresario = ie.id 
                                INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = iec.id_consultor 
                                INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario
                                WHERE ie.activo = 1  AND ie.creado >= '2017-03-15' 
                                ". ((isset($_REQUEST["id_consultor"]) && $_REQUEST["id_consultor"] > 0) ? " AND iec.id_consultor = ". $_REQUEST["id_consultor"] : "") ."
                                ". ((isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0) ? " AND ioc.id_organismo = ". $_REQUEST["id_organismo"] : "") ." 
                                ". ((isset($_REQUEST["id_empresario"]) && $_REQUEST["id_empresario"] > 0) ? " AND ie.id = ". $_REQUEST["id_empresario"] : "") ." 
                                ". ((isset($_REQUEST["folio"]) && $_REQUEST["folio"] != '') ? " AND ief.folio = '". $_REQUEST["folio"] ."'" : "") ." ;";
                                
            $sql_empresario = $this->queryArray($sql_empresario);
            if($sql_empresario["status"]){
                $empresarios = array();
                foreach ($sql_empresario["rows"] as $empresario) {
                    $total = array();
                    $total["total"] = 5;
                    $total["completo"] = $total["iniciado"] = 0;
                    if($total["total"] > 0){
                        $sql_informacion = "SELECT e.nombre AS consultor, ie.razon AS empresario, ic.nombre AS curso,
                                            (
                                                IF(ies.id_curso = 1, 
                                                    IF(MAX(ies.ultimo_slide) = 75, 'COMPLETO', 'INICIADO'), 
                                                    IF(ies.id_curso = 2, 
                                                        IF(MAX(ies.ultimo_slide) = 77, 'COMPLETO', 'INICIADO'), 
                                                        IF(ies.id_curso = 3, 
                                                            IF(MAX(ies.ultimo_slide) = 53, 'COMPLETO', 'INICIADO'), 
                                                            IF(ies.id_curso = 4, 
                                                                IF(MAX(ies.ultimo_slide) = 72, 'COMPLETO', 'INICIADO'), 
                                                                IF(ies.id_curso = 5, 
                                                                    IF(MAX(ies.ultimo_slide) = 52, 'COMPLETO', 'INICIADO'), 
                                                                    'INDEFINIDO'
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            ) AS estatus 
                                            FROM netwarstore.inovekia_seguimiento AS ies 
                                            INNER JOIN netwarstore.inovekia_curso AS ic ON ic.id = ies.id_curso 
                                            INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = ies.id_empresario 
                                            INNER JOIN empleados AS e ON e.idempleado = ies.id_consultor 
                                            WHERE ie.id = ". $empresario["id"] ." AND ie.creado >= '2017-03-15'
                                            GROUP BY consultor, empresario, curso 
                                            ORDER BY consultor ASC, empresario ASC, curso ASC;";
                        $sql_informacion = $this->queryArray($sql_informacion);
                        if($sql_informacion["status"]){
                            foreach ($sql_informacion["rows"] as $registro) {
                                ($registro["estatus"] == "COMPLETO") ? $total["completo"]++ : $total["iniciado"]++;
                            }
                            $total["no"] = $total["total"] - ($total["completo"] + $total["iniciado"]);
                            $total["completo"] = ( $total["completo"] * 100) / $total["total"];
                            $total["iniciado"] = ( $total["iniciado"] * 100) / $total["total"];
                            $total["no"] = ( $total["no"] * 100) / $total["total"];
                            $empresarios[$empresario["razon"]] = $total;
                        } else {
                            throw new Exception($sql_informacion["msg"], 1);
                        }
                    } else {
                        $total["completo"] = "N/A";
                        $total["iniciado"] = "N/A";
                        $total["no"] = "N/A";
                        $empresarios[$empresario["razon"]] = $total;
                    }
                }

                $sql_s_organismo = "SELECT id, nombre FROM netwarstore.inovekia_organismo WHERE activo = 1 ORDER BY nombre ASC;";
                $sql_s_organismo = $this->queryArray($sql_s_organismo);
                if($sql_s_organismo["status"]){
                    $html = "<option value='0'>Selecciona un organismo</option>";
                    foreach ($sql_s_organismo["rows"] as $organismo) {
                        $html .= "<option value='". $organismo["id"] ."'>". $organismo["nombre"] ."</option>";
                    }
                    $json["f_organismo"] = $html;
                } else {
                    throw new Exception($sql_s_organismo["msg"], 1);
                }

                if(isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0){
                    $sql_s_consultor = "SELECT e.idempleado, e.nombre FROM empleados AS e 
                                        INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                        WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY e.nombre ASC;";
                    $sql_s_consultor = $this->queryArray($sql_s_consultor);
                    if($sql_s_consultor["status"]){
                        $html = "<option value='0'>Selecciona un consultor</option>";
                        foreach ($sql_s_consultor["rows"] as $consultor) {
                            $html .= "<option value='". $consultor["idempleado"] ."'>". $consultor["nombre"] ."</option>";
                        }
                        $json["f_consultor"] = $html;
                    } else {
                        throw new Exception($sql_s_consultor["msg"], 1);
                    }

                    $sql_s_folio = "SELECT ief.folio FROM empleados AS e 
                                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                                    WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY ief.folio ASC;";
                    $sql_s_folio = $this->queryArray($sql_s_folio);
                    if($sql_s_folio["status"]){
                        $html = "<option value=''>Selecciona un folio</option>";
                        $sql_s_folio["rows"] = array_unique($sql_s_folio["rows"]);
                        foreach ($sql_s_folio["rows"] as $folio) {
                            $html .= "<option value='". $folio["folio"] ."'>". $folio["folio"] ."</option>";
                        }
                        $json["f_folio"] = $html;
                    } else {
                        throw new Exception($sql_s_folio["msg"], 1);
                    }

                    $sql_s_empresario = "SELECT ie.id, ie.razon FROM empleados AS e 
                                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                                    INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = ief.id_empresario 
                                    WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY ie.razon ASC;";
                    $sql_s_empresario = $this->queryArray($sql_s_empresario);
                    if($sql_s_empresario["status"]){
                        $html = "<option value=''>Selecciona un empresario</option>";
                        $sql_s_empresario["rows"] = array_unique($sql_s_empresario["rows"]);
                        foreach ($sql_s_empresario["rows"] as $empresario) {
                            $html .= "<option value='". $empresario["id"] ."'>". $empresario["razon"] ."</option>";
                        }
                        $json["f_empresario"] = $html;
                    } else {
                        throw new Exception($sql_s_empresario["msg"], 1);
                    }
                }

                $json["empresarios"] = $empresarios;
                $json["status"] = true;
            } else {
                throw new Exception($sql_informacion["msg"], 1);
            }
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

    public function cursosCurso(){
        try{
            $sql_empresario = "SELECT * FROM netwarstore.inovekia_empresario WHERE activo = 1  AND creado >= '2017-03-15';";
            $sql_empresario = $this->queryArray($sql_empresario);
            if($sql_empresario["status"]){
                $cursos = array();
                $sql_curso = "SELECT * FROM netwarstore.inovekia_curso WHERE activo = 1";
                $sql_curso = $this->queryArray($sql_curso);
                if($sql_curso["status"]){
                    foreach ($sql_curso["rows"] as $curso) {
                        $total = array();
                        $total["total"] = $sql_empresario["total"] ;
                        $total["completo"] = $total["iniciado"] = 0;
                        $sql_informacion = "SELECT e.nombre AS consultor, ie.razon AS empresario, ic.nombre AS curso,
                                            (
                                                IF(ies.id_curso = 1, 
                                                    IF(MAX(ies.ultimo_slide) = 75, 'COMPLETO', 'INICIADO'), 
                                                    IF(ies.id_curso = 2, 
                                                        IF(MAX(ies.ultimo_slide) = 77, 'COMPLETO', 'INICIADO'), 
                                                        IF(ies.id_curso = 3, 
                                                            IF(MAX(ies.ultimo_slide) = 53, 'COMPLETO', 'INICIADO'), 
                                                            IF(ies.id_curso = 4, 
                                                                IF(MAX(ies.ultimo_slide) = 72, 'COMPLETO', 'INICIADO'), 
                                                                IF(ies.id_curso = 5, 
                                                                    IF(MAX(ies.ultimo_slide) = 52, 'COMPLETO', 'INICIADO'), 
                                                                    'INDEFINIDO'
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            ) AS estatus 
                                            FROM netwarstore.inovekia_seguimiento AS ies 
                                            INNER JOIN netwarstore.inovekia_curso AS ic ON ic.id = ies.id_curso 
                                            INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = ies.id_empresario 
                                            INNER JOIN empleados AS e ON e.idempleado = ies.id_consultor 
                                            WHERE ies.id_curso = ". $curso["id"] ."  AND ie.creado >= '2017-03-15'
                                            GROUP BY consultor, empresario, curso 
                                            ORDER BY consultor ASC, empresario ASC, curso ASC;";
                        $sql_informacion = $this->queryArray($sql_informacion);
                        if($sql_informacion["status"]){
                            if($total["total"] > 0){
                                foreach ($sql_informacion["rows"] as $registro) {
                                    ($registro["estatus"] == "COMPLETO") ? $total["completo"]++ : $total["iniciado"]++;
                                }
                                $total["no"] = $total["total"] - ($total["completo"] + $total["iniciado"]);
                                $total["completo"] = ( $total["completo"] * 100) / $total["total"];
                                $total["iniciado"] = ( $total["iniciado"] * 100) / $total["total"];
                                $total["no"] = ( $total["no"] * 100) / $total["total"];
                                $cursos[$curso["nombre"]] = $total;
                            } else {
                                $total["completo"] = "N/A";
                                $total["iniciado"] = "N/A";
                                $total["no"] = "N/A";
                                $cursos[$curso["nombre"]] = $total;
                            }
                        } else {
                            throw new Exception($sql_informacion["msg"], 1);
                        }
                    }
                }else{
                    throw new Exception($sql_informacion["msg"], 1);
                }

                $sql_s_organismo = "SELECT id, nombre FROM netwarstore.inovekia_organismo WHERE activo = 1 ORDER BY nombre ASC;";
                $sql_s_organismo = $this->queryArray($sql_s_organismo);
                if($sql_s_organismo["status"]){
                    $html = "<option value='0'>Selecciona un organismo</option>";
                    foreach ($sql_s_organismo["rows"] as $organismo) {
                        $html .= "<option value='". $organismo["id"] ."'>". $organismo["nombre"] ."</option>";
                    }
                    $json["f_organismo"] = $html;
                } else {
                    throw new Exception($sql_s_organismo["msg"], 1);
                }

                if(isset($_REQUEST["id_organismo"]) && $_REQUEST["id_organismo"] > 0){
                    $sql_s_consultor = "SELECT e.idempleado, e.nombre FROM empleados AS e 
                                        INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                        WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY e.nombre ASC;";
                    $sql_s_consultor = $this->queryArray($sql_s_consultor);
                    if($sql_s_consultor["status"]){
                        $html = "<option value='0'>Selecciona un consultor</option>";
                        foreach ($sql_s_consultor["rows"] as $consultor) {
                            $html .= "<option value='". $consultor["idempleado"] ."'>". $consultor["nombre"] ."</option>";
                        }
                        $json["f_consultor"] = $html;
                    } else {
                        throw new Exception($sql_s_consultor["msg"], 1);
                    }

                    $sql_s_folio = "SELECT ief.folio FROM empleados AS e 
                                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                                    WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY ief.folio ASC;";
                    $sql_s_folio = $this->queryArray($sql_s_folio);
                    if($sql_s_folio["status"]){
                        $html = "<option value=''>Selecciona un folio</option>";
                        $sql_s_folio["rows"] = array_unique($sql_s_folio["rows"]);
                        foreach ($sql_s_folio["rows"] as $folio) {
                            $html .= "<option value='". $folio["folio"] ."'>". $folio["folio"] ."</option>";
                        }
                        $json["f_folio"] = $html;
                    } else {
                        throw new Exception($sql_s_folio["msg"], 1);
                    }

                    $sql_s_empresario = "SELECT ie.id, ie.razon FROM empleados AS e 
                                    INNER JOIN netwarstore.inovekia_organismo_consultor AS ioc ON ioc.id_consultor = e.idempleado 
                                    INNER JOIN netwarstore.inovekia_empresario_consultor AS iec ON iec.id_consultor = ioc.id_consultor 
                                    INNER JOIN netwarstore.inovekia_empresario_folio AS ief ON ief.id_empresario = iec.id_empresario 
                                    INNER JOIN netwarstore.inovekia_empresario AS ie ON ie.id = ief.id_empresario 
                                    WHERE e.visible = -1 AND ioc.id_organismo = ". $_REQUEST["id_organismo"] ." ORDER BY ie.razon ASC;";
                    $sql_s_empresario = $this->queryArray($sql_s_empresario);
                    if($sql_s_empresario["status"]){
                        $html = "<option value=''>Selecciona un empresario</option>";
                        $sql_s_empresario["rows"] = array_unique($sql_s_empresario["rows"]);
                        foreach ($sql_s_empresario["rows"] as $empresario) {
                            $html .= "<option value='". $empresario["id"] ."'>". $empresario["razon"] ."</option>";
                        }
                        $json["f_empresario"] = $html;
                    } else {
                        throw new Exception($sql_s_empresario["msg"], 1);
                    }
                }
                
                $json["cursos"] = $cursos;
                $json["status"] = true;
            } else {
                throw new Exception($sql_informacion["msg"], 1);
            }
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }

}

?>