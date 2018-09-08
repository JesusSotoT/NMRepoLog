<?php

ini_set("display_errors", 1); error_reporting(E_ALL);
require('common.php');

require("models/pinpad.php");

class pinpadc extends Common
{
  public $pinpadcModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->pinpadcModel = new pinpadcModel();
        $this->pinpadcModel->connect();



    }

    	function pinpadc(){



           $forma = $_POST['tipo'];
        $monto = $_POST['cantidad'];
        $tipostr=$_POST['tipostr'];
        $txtReferencia=$_POST['txtReferencia'];
        $result=$this->pinpadcModel->pinpad($monto,$forma);

            echo json_encode($result);
        

    	}

}
?>