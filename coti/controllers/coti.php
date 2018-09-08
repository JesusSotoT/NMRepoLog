<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require_once('common.php');

//Carga el modelo para este controlador
require_once("models/coti.php");

class Coti extends Common
{
    public $CotiModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->CotiModel = new CotiModel();
        $this->CotiModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->CotiModel->close();
    } 

    function indexCaja(){
    
    }

    function aceptarCoti(){
        $coti = $_POST['coti'];
        $this->CotiModel->aceptarCoti($coti);
    }


    function comentar(){
        $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $coti = $_POST['coti'];
        $com = $_POST['com'];
        $p = $_POST['p'];
        $this->CotiModel->comentar($coti,$com,$p);
    }

    function conversacion($cot){
        $conversacion = $this->CotiModel->conversacion($cot);
        return $conversacion;
    }

    function estatusCotizacion($a,$b){
        $estatus = $this->CotiModel->estatusCotizacion($a,$b);
        return $estatus;
    }
    /*
    function envioFactura(){
        $uid = $_POST['uid'];
        $correo = $_POST['correo'];

        $resultado = $this->CajaModel->envioFactura($uid, $correo, $azurian,$doc);

        echo json_encode($resultado);
    }

    function datosorganizacion(){
        $res = $this->CajaModel->datosorganizacion();
        return $res;
    }
*/
    
}


?>
