<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/tarjeta.php");

class Tarjeta extends Common
{
    public $TarjetaModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->TarjetaModel = new TarjetaModel();
        $this->TarjetaModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->TarjetaModel->close();
    } 
/*    function indexImportarProductos(){
        require('views/producto/importar_products.php');
    }
*/
    function subeLayout()
    {
        $directorio = "importacion/";
        if (isset($_FILES["layout"])) 
        {
                if($_FILES['layout']['name'])
                {
                    //die($directorio.basename("hoteles_temp.xls" ));
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("tarjetas_temp.xls" ) )) 

                    {
                        echo "Validando archivo...<br/>";
                        include($directorio."import_tarjetas.php");
                    } 
                    else 
                    {
                        echo "No se subio el archivo de Productos <br/>";
                    }
                }
        }
    }

    function inactivarLay()
    {
        echo $this->TarjetaModel->inactivarLay($_POST['id'],$_POST['num']);
    }

    function reactivarLay()
    {
        echo $this->TarjetaModel->reactivarLay($_POST['id'],$_POST['num']);
    }

    function confirmarLay()
    {
        $this->TarjetaModel->confirmar($_POST['num']);
        $this->TarjetaModel->borrar(98);
    }

    function indexGridTarjetas(){
        $tarjetasGrid = $this->TarjetaModel->mostrar($limit);
        
        require('views/tarjeta/gridTarjetas.php');
    }

    function guardar() {
        $args = array(
            'clave'     => FILTER_SANITIZE_STRING,
            'banco'    => FILTER_SANITIZE_STRING,
            'estatus'   => FILTER_SANITIZE_NUMBER_INT
        );

        $params = filter_input_array(INPUT_POST, $args);

        echo json_encode( $this->TarjetaModel->guardar( $params['clave'] , $params['banco'] , $params['estatus'] ) );
    }

    function editar()
    {
        $args = array(
            'id'        => FILTER_SANITIZE_NUMBER_INT,
            'clave'     => FILTER_SANITIZE_STRING,
            'banco'    => FILTER_SANITIZE_STRING,
            'estatus'   => FILTER_SANITIZE_NUMBER_INT
        );

        $params = filter_input_array(INPUT_POST, $args);

        echo json_encode( $this->TarjetaModel->editar( $params['id'] , $params['clave'] , $params['banco'] , $params['estatus'] ) );
    }
}
?>
