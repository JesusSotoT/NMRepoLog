<?php 

//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/retiro/retiro.php");

class Retiro extends Common {

    public $retiroModel;

    function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->retiroModel = new retiroModel();
    }

        function imprimeretiro() {
            
             require('views/retiro/retiro.php');

        }   

        function agregaretiro(){
        
            $cantidad=$_POST['cantidad'];
            $concepto = $_POST['concepto'];

            $resultado = $this->retiroModel->agregaretiro($cantidad,$concepto);
            //var_dump($resultado);
            echo json_encode($resultado);
        }
        function pintatabla(){
            
            $resultado = $this->retiroModel->pintatabla();
            //print_r($resultado);
            //exit();
            echo json_encode($resultado);
        }
        function usuarios(){

            $result = $this->retiroModel->usuarios();
            echo json_encode($result);
        }
        function filtra(){
            $desde = $_POST['desde'];
            $hasta = $_POST['hasta'];
            $user = $_POST['user'];

            $result = $this->retiroModel->filtra($desde,$hasta,$user);
            echo json_encode($result);
        }





}




















?>