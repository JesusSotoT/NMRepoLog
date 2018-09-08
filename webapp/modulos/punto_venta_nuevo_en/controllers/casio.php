<?php

//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/casio/casio.php");

class Casio extends Common {

    public $casioModel;

    function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->casioModel = new casioModel();
    }
    function principal(){
        require('views/casio/casio.php');
    }
    function readFile(){

        $target_dir = "ventasCasio/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 1;
            }
        }
        //echo 'Direccion='.$target_file;
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
                //echo $target_file;
                $result = $this->casioModel->readFile($target_file);
    }
    function cargaCliente(){

        $result = $this->casioModel->cargaCliente();
        echo json_encode($result);
    }
}

?>