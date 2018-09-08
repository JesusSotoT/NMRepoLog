<?php


// Model: Connect to server to access to cross databases
abstract class clcn {
    var $cn;
    var $_serv2="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
    var $_usu2="nmdevel";
    var $_pwd2="nmdevel";
    var $_bd2;
    var $error;

    function connect() {
        $this->cn = new mysqli($this->_serv2,$this->_usu2,$this->_pwd2,$this->_bd2);
        if($this->cn->connect_errno){
            $this->error =  $this->cn->connect_error;
            if($this->error!=""){
                error_log($this->error);
            }
        }
    }

    function disconnect(){
        mysqli_close($this->cn);
    }    
}

// Control nmdev_common database
class clnmdev extends clcn {

    function __construct(){
        //error_log("ENTRE_CONSTRUCTOR");
        $this->_bd2 = "nmdev";        
        $this->connect();
    }

    function get_connection(){
        return $this->cn;
    }

    function get_eventos(){
        $index = 0;
        $eventos = array();

        $sql = "select idEvento, strNombre_Evento from evt_eventos_test";

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $eventos[$index]["idEvento"] = $row["idEvento"];
            $eventos[$index]["strNombre_Evento"] = $row["strNombre_Evento"];
            $index = $index + 1;
        }
        $result->free();
        return $eventos;
    }


    function get_asistentes(){
        $index = 0;
        $asistentes = array();

        $sql = "select idUsuario as id, CONCAT(strNombres, ' ', strApellidoPaterno) as nombre from evt_usuario_test";

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $asistentes[$index]["id"] = $row["id"];
            $asistentes[$index]["nombre"] = $row["nombre"];
            $index = $index + 1;
        }
        $result->free();
        return $asistentes;
    }
    
    function get_empresas_factura(){
        $index = 0;
        $empresas = array();

        $sql = "select idCatalogoEmpresas, strEmpresa from evt_catalogo_empresas_test";

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $empresas[$index]["id"] = $row["idCatalogoEmpresas"];
            $empresas[$index]["nombre"] = $row["strEmpresa"];
            $index = $index + 1;
        }
        $result->free();
        return $empresas;
    }

    function registra_asistentes($idEvento, $idAsistente, $idEmpresa, $dateFechaRegistro){
        $sql = "insert into evt_eventos_asistentes_test (idEvento,idUsuario,idEmpresaFacturacion,dateFechaRegistro) values ('".$idEvento."','".$idAsistente."','".$idEmpresa."','".$dateFechaRegistro."')";
        $this->cn->query($sql);
                        
    }
}
