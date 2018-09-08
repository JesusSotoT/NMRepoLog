<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ConfiguracionModel extends Connection
{
    /*
    public function instancias(){
       
        //$conexion= mysqli_connect('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com','nmdevel','nmdevel','netwarstore');
        //$myQuery =  "SELECT * FROM almacenes";  
        //$instancias = mysqli_query($conexion, $myQuery);  
        //$row = $instancias->fetch_array();
        //return $row;
    }
    */

    public function ver_app($app){
        $sql = "SELECT id_app from scala_app where solucion = '$app'";
        $app = $this->queryArray($sql);
        return $app["rows"][0]['id_app'];
    }
    public function reload_app(){
        $sql = "SELECT id_app, nombre, 
                    if(solucion = 1001, 'Appministra - POS Emprendedor',
                    if(solucion = 1002, 'Foodware - Emprendedor',     
                    if(solucion = 1004, 'Acontia - Emprendedor',
                    if(solucion = 1011, 'Xtructur - Negocio',   
                    if(solucion = 1014, 'Appministra - POS Negocio',
                    if(solucion = 1015, 'Appministra - POS Empresarial',
                    if(solucion = 1016, 'Appministra - Comercial',
                    if(solucion = 1019, 'Foodware - Negocio',
                    if(solucion = 1020, 'Foodware - Empresarial',
                    if(solucion = 1021, 'Acontia - Negocio',
                    if(solucion = 1022, 'Acontia - Empresarial',
                    if(solucion = 1023, 'Xtructur - Negocio Plus',
                    if(solucion = 2024, 'Xtructur - Empresarial',
                    if(solucion = 2025, 'Xtructur - Corporativo',2)))))))))))))) as solucion, 
                    desc_corta, desc_larga, estatus FROM scala_app;";
        $apps = $this->queryArray($sql);
        return $apps["rows"];
    }
    public function reload_actividades($id_app,$id_paso){
       $sql = "SELECT *  FROM scala_actividades where id_paso = '$id_paso';";
        $apps = $this->queryArray($sql);
        return $apps["rows"]; 
    }

    public function reload_pasos($id_app){
        $sql = "SELECT * FROM scala_pasos where id_app = '$id_app';";
        $apps = $this->queryArray($sql);
        return $apps["rows"];
    }

    public function save_app($nombre,$solucion,$desc){
        $sql = "INSERT INTO scala_app(nombre, solucion, desc_corta, desc_larga, estatus) VALUES('$nombre', '$solucion', '$desc', '', 1)";
        return $this->query($sql);
    }
    public function save_pasos($paso,$nombre,$link,$desc_larga,$id_app){
        $sql = "INSERT INTO scala_pasos(paso, nombre, link, desc_larga, id_app) VALUES('$paso', '$nombre', '$link', '$desc_larga', '$id_app')";
        $id_paso =  $this->queryArray($sql);
        return $id_paso["insertId"];
    }
    public function save_actividad($nombre,$menu,$desc,$link,$opcional,$estatus,$idpasoR){
        $sql = "INSERT INTO scala_actividades(nombre, menu, desc_larga, link, opcional, estatus, id_paso) VALUES('$nombre', '$menu', '$desc', '$link', '$opcional', '$estatus', '$idpasoR')";
        return $this->query($sql);
    }
    public function edit_actividad($nombre,$menu,$desc,$link,$opcional,$estatus,$idpasoR,$id_act){
        $sql = "UPDATE scala_actividades SET nombre='$nombre', menu='$menu', desc_larga='$desc', link='$link', opcional='$opcional', estatus='$estatus', id_paso='$idpasoR'  WHERE id_actividad = '$id_act'";
        return $this->query($sql);
    }
    public function edit_pasos($paso,$nombre,$link,$desc_larga,$id_app){
        $sql = "UPDATE scala_pasos SET nombre='$nombre', link='$link', desc_larga='$desc_larga' WHERE id_app = '$id_app' and paso = '$paso';";
        return $this->query($sql);
    }

    public function estatus_app($id_app,$estatus){
        $sql = "UPDATE scala_app SET estatus = $estatus WHERE id_app = $id_app";
        return $this->query($sql);
    }
    public function estatus_act($id_act,$estatus){
        $sql = "UPDATE scala_actividades SET estatus = $estatus WHERE id_actividad = $id_act";
        return $this->query($sql);
    }
    public function select_menu(){
        $sql = "SELECT idmenu, nombre, url  FROM accelog_menu;";
        $menus = $this->queryArray($sql);
        return $menus["rows"];
    }
    public function datos_act($id_act){
        $sql = "SELECT *  FROM scala_actividades where id_actividad = '$id_act';";
        $act = $this->queryArray($sql);
        return $act["rows"]; 
    }

}

?>