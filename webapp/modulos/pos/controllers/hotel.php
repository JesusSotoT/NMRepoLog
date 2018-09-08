<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/hotel.php");

class Hotel extends Common
{
    public $ProductoModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ProductoModel = new HotelModel();
        $this->ProductoModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ProductoModel->close();
    } 
    function indexImportarProductos(){
        require('views/producto/importar_products.php');
    }

    function subeLayout()
    {
        $directorio = "importacion/";
        if (isset($_FILES["layout"])) 
        {
                if($_FILES['layout']['name'])
                {
                    //die($directorio.basename("hoteles_temp.xls" ));
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("hoteles_temp.xls" ) )) 

                    {
                        echo "Validando archivo...<br/>";
                        include($directorio."import_hoteles.php");
                    } 
                    else 
                    {
                        echo "No se subio el archivo de Productos <br/>";
                    }
                }
        }
    }

    function inactivarHotelLay()
    {
        echo $this->ProductoModel->inactivarHotelLay($_POST['id'],$_POST['num']);
    }

    function reactivarHotelLay()
    {
        echo $this->ProductoModel->reactivarHotelLay($_POST['id'],$_POST['num']);
    }

    function confirmarHotelLay()
    {
        $this->ProductoModel->confirmar($_POST['num']);
        $this->ProductoModel->borrar(98);
    }

    function indexGridHoteles(){
        $hotelesGrid = $this->ProductoModel->mostrar($limit);
        
        require('views/hotel/gridHoteles.php');
    }

    function guardar() {
        $args = array(
            'clave'     => FILTER_SANITIZE_STRING,
            'nombre'    => FILTER_SANITIZE_STRING,
            'estatus'   => FILTER_SANITIZE_NUMBER_INT
        );

        $params = filter_input_array(INPUT_POST, $args);

        echo json_encode( $this->ProductoModel->guardar( $params['clave'] , $params['nombre'] , $params['estatus'] ) );
    }

    function editar()
    {
        $args = array(
            'id'        => FILTER_SANITIZE_NUMBER_INT,
            'clave'     => FILTER_SANITIZE_STRING,
            'nombre'    => FILTER_SANITIZE_STRING,
            'estatus'   => FILTER_SANITIZE_NUMBER_INT
        );

        $params = filter_input_array(INPUT_POST, $args);

        echo json_encode( $this->ProductoModel->editar( $params['id'] , $params['clave'] , $params['nombre'] , $params['estatus'] ) );
    }
}
?>
