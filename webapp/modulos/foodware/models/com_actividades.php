<?php

    //Cargar la clase de conexión padre para el modelo
    require_once("models/model_father.php");
    //Cargar los archivos necesarios

    class ComActividadesModel extends Model
    {
        //Definir los atributos de la clase
        public $id = null;
        public $empleado = null;
        public $accion = null;
        public $descripcion = null;
        public $id_sucursal = null;
        public $fecha = null;

        function __construct($id = null)
        {
            parent::__construct($id);
        }

        function __destruct()
        {

        }

    }

?>
