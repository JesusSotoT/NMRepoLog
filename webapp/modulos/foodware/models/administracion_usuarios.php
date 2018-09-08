<?php

    //Cargar la clase de conexión padre para el modelo
    require_once("models/model_father.php");
    //Cargar los archivos necesarios

    class AdministracionUsuariosModel extends Model
    {
        //Definir los atributos de la clase
        public $idadmin = null;
        public $nombre = null;
        public $apellidos = null;
        public $nombreusuario = null;
        public $clave = null;
        public $confirmaclave = null;
        public $correoelectronico = null;
        public $foto = null;
        public $idperfil = null;
        public $idempleado = null;
        public $idorganizacion = null;
        public $idpuesto = null;
        public $tipo = null;
        public $idSuc = null;
        public $id = null;
        public $mostrar_comanda = null;

        function __construct($id = null)
        {
            parent::__construct($id);
        }

        function __destruct()
        {

        }

    }

?>
