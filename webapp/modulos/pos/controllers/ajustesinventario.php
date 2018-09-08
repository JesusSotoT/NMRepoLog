<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/ajustesinventario.php");

class AjustesInventario extends Common
{
    public $AjustesInventarioModel;

    function __construct()
    {
        $this->AjustesInventarioModel = new AjustesInventarioModel();
        $this->AjustesInventarioModel->connect();
    }

    function __destruct()
    {
        $this->AjustesInventarioModel->close();
    } 
    function indexGrid(){
        $movimientos = $this->AjustesInventarioModel->ajustes();
        require('views/ajustesinventario/gridAjustesInventario.php');
    }
    function movimientos() {
        $fecha = $_GET['fecha'];
        $movimientos = $this->AjustesInventarioModel->movimientos($fecha);

        $caracteristicas = $this->AjustesInventarioModel->getCaracteristicas();
        foreach ($movimientos as $key => $value) {
            if( $value['id_producto_caracteristica'] != '\'0\'' )
                $movimientos[$key]['nombre'] = $movimientos[$key]['nombre'] . " [ " . $this->caract2nombre($caracteristicas ,$value['id_producto_caracteristica']) . " ]";
        }
        echo json_encode($movimientos);
    }



    function caract2id($caract){
        return preg_replace(["/=>/", "/,/", "/'/" ], ["H", "_", ""], $caract);
    }
    function id2caract($id)
    {
        return preg_replace(['/H/','/_/','/(\d+)/' ], [ '=>',',', "'\${1}'"], $id);
        //return preg_replace(["/H/","/^/", "/$/", "/_/"], ["'=>'", "'", "'", "','"], $id);
    }
    function caract2nombre($caracteristicas ,$caract)
    {
        $caract = $this->caract2id($caract);
        $caracteristicasProductoTmp = explode( '_' , $caract );
        $caracteristicasEtiqueta = '';
        foreach ($caracteristicasProductoTmp as $key => $val) {
            $caracteristica = explode( 'H' , $val);
            $caracteristica = $this->buscarCaracteristica($caracteristicas , $caracteristica);

            $caracteristicasEtiqueta .= "{$caracteristica['CRARACTERISTICA_PADRE']}:{$caracteristica['CARACTERISTICA_HIJA']} ," ;
        }
        return $caracteristicasEtiqueta;
    }
    function buscarCaracteristica($caracteristicas , $caracteristica)
    {
        foreach ($caracteristicas as $key => $value) {
            if($value['ID_P'] == $caracteristica[0] && $value['ID_H'] == $caracteristica[1] )
                return $value;
        }
    }
}
?>
