<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/viajes.php");

class Viajes extends Common
{
    public $ViajesModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ViajesModel = new ViajesModel();
        $this->ViajesModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ViajesModel->close();
    }
    function viajes(){
        $moneda = $this->ViajesModel->moneda();
        $formasDePago = $this->ViajesModel->formasDePago();
        $cliente = $this->ViajesModel->ventasIndex();
        
        $clientes = $this->ViajesModel->clientes();
        require('views/viajes/viajes.php');
    }
    
    function generar(){

    }
    function  indexviajes(){
        $vendedores = $this->ViajesModel->vendedores();
        $sucursales = $this->ViajesModel->sucursales();

        require('views/viajes/viajes.php');
    }
    function buscar()
    {
        $res = $this->ViajesModel->buscar( $_GET['desde'] , $_GET['hasta'] , $_GET['sucursal'] , $_GET['vendedor'] );
        echo json_encode($res);
    }
}

?>