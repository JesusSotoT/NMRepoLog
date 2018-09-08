<?php

//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/lote/lote.php");

class Lote extends Common {

    public $loteModel;

    function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->loteModel = new loteModel();
    }
    function imprimeGrid(){
        $lotes = $this->loteModel->imprimeGrid();
        require('views/lote/lote.php');
    }
    function loteForm(){
        $idLote = $_GET['pe'];
        //$lotes = $this->loteModel->imprimeGrid();   
        $datosLote = $this->loteModel->datosLote($idLote);
        $series = $this->loteModel->series($idLote);
        require('views/lote/loteForm.php');
    }
    function addSerie(){
        $idLote=$_POST['idLote'];
        $idProducto = $_POST['idProducto'];
        $serie = $_POST['serie'];
        $cantidad = $_POST['cantidad'];

        $addSerie = $this->loteModel->addSerie($idLote,$idProducto,$serie,$cantidad);
        echo json_encode($addSerie);

    }
    function cargaSelects(){
        $selects = $this->loteModel->cargaSelects();
        echo json_encode($selects);
    }   
    function busca(){
        $idLote = $_POST['lote'];
        $idProducto = $_POST['producto'];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];

        $result = $this->loteModel->busca($idLote,$idProducto,$desde,$hasta);
        echo json_encode($result);
    }
}

?>