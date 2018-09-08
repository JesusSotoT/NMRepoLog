<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/destino.php");

class Destino extends Common
{
    public $DestinoModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->DestinoModel = new DestinoModel();
        $this->DestinoModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->DestinoModel->close();
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
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("destinos_temp.xls" ) )) 

                    {
                        echo "Validando archivo...<br/>";
                        include($directorio."import_destinos.php");
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
        echo $this->DestinoModel->inactivarLay($_POST['id'],$_POST['num']);
    }

    function reactivarLay()
    {
        echo $this->DestinoModel->reactivarLay($_POST['id'],$_POST['num']);
    }

    function confirmarLay()
    {
        $this->DestinoModel->confirmar($_POST['num']);
        $this->DestinoModel->borrar(98);
    }

    function indexGridDestinos(){
        $hotelesGrid = $this->DestinoModel->mostrar($limit);
        
        require('views/destino/gridDestinos.php');
    }

    function guardar() {
        $args = array(
            'clave'     => FILTER_SANITIZE_STRING,
            'nombre'    => FILTER_SANITIZE_STRING,
            'estatus'   => FILTER_SANITIZE_NUMBER_INT
        );

        $params = filter_input_array(INPUT_POST, $args);

        echo json_encode( $this->DestinoModel->guardar( $params['clave'] , $params['nombre'] , $params['estatus'] ) );
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

        echo json_encode( $this->DestinoModel->editar( $params['id'] , $params['clave'] , $params['nombre'] , $params['estatus'] ) );
    }
}
?>
