<?php 
ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/retiro.php");

class Retiro extends Common {

    public $retiroModel;

    function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->retiroModel = new retiroModel();
        $this->retiroModel->connect();
    }

        function imprimeretiro() {
            $retiros = $this->retiroModel->pintatabla();
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
        function indexAbonos(){
            $abonos = $this->retiroModel->pintatablaAbonos();
            $clientes = $this->retiroModel->clientes();
            $formaPago = $this->retiroModel->formaPagos();
            $moneda = $this->retiroModel->moneda();                                                                                                                                   
            require('views/retiro/abono.php');
        }
        function agregaAbono(){
            $cliente = $_POST['cliente'];
            $importe = $_POST['importe'];
            $concepto = $_POST['concepto'];
            $moneda = $_POST['moneda'];
            $formaPago = $_POST['formaPago'];
            $cargo = $_POST['cargo'];

            $res = $this->retiroModel->guardaAbono($cliente,$importe,$concepto,$moneda,$formaPago,$cargo);
            echo json_encode($res);
            
        }
        function buscaCargos(){
            $cliente = $_POST['cliente'];
            $res = $this->retiroModel->buscaCargos($cliente);
            echo json_encode($res);
        }





}




















?>