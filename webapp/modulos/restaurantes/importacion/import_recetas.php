<html>
    <head>
<!-- ** /////////////////////////- -                 CSS                --///////////////////// **-->
        
    <!-- Iconos font-awesome -->
        <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <!-- bootstrap min CSS -->
        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <!-- bootstrap-select -->
        <link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
    <!-- DataTables  -->
        <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
        <!-- <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css"> -->
        
<!-- ** //////////////////////////- -               FIN CSS                 --///////////////////// **-->
    
<!-- ** //////////////////////////- -               JS              --///////////////////// **-->

    <!-- JQuery -->
        <script src="../../libraries/jquery.min.js"></script>
    <!-- bootstrap -->
        <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- bootstrap-select  -->
        <script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
    <!-- Notify  -->
        <script src="../../libraries/notify.js"></script>
    <!-- DataTables  -->
        <script src="../../libraries/dataTable/js/datatables.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
        <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
        <script src="../../libraries/export_print/buttons.html5.min.js"></script>
        <script src="../../libraries/export_print/jszip.min.js"></script>
        
    <!-- Sistema -->
        <script src="js/recetas/recetas.js"></script>

<!-- ** //////////////////////////- -               FIN JS              --///////////////////// **-->

        <title>Recetas</title>
    </head>
    <body>      
        <div class="row" id="loader" style="margin: 0; text-align:center; width:100%; font-size: 20px; font-weight: bold">
            Importando...
            <div style="margin-top: 10px" align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span style="color:black" class="sr-only">Importando...</span>
            </div>
        </div>
        <div class="row" id="respuesta" style="display: none; margin: 0; margin-top: 100px; text-align: center;">
            <div style="width:50%; margin-left:25%;">
                <p id="mensaje"></p>
                <a style="float:right;" class="btn btn-default" href='index.php?c=recetas&f=vista_recetas'>Regresar</a>
            </div>
        </div>
    </body>
</html>
<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

require_once '../../libraries/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/recetas_temp.xls');
$flag_chk = 0;
$dato = array();
$sigue = 1;
$recetas = [];
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
{
    
    $dato['codigo'] = trim($data->sheets[0]["cells"][$i][1]); //Codigo
    $dato['nombre'] = trim($data->sheets[0]["cells"][$i][2]); //Nombre producto
    $dato['tipo_producto'] = trim($data->sheets[0]["cells"][$i][3]); //Tipo producto
    $dato['tipo_modificador'] = trim($data->sheets[0]["cells"][$i][4]); //Tipo modificador
    $dato['unidad_compra'] = $this->recetasModel->unidad_medida(trim($data->sheets[0]["cells"][$i][5]));  //Unidad de compra
    $dato['unidad_venta'] = $this->recetasModel->unidad_medida(trim($data->sheets[0]["cells"][$i][6])); //Unidad de venta
    $dato['cantidad'] = trim($data->sheets[0]["cells"][$i][7]); //Cantidad 
    $dato['costeo'] = trim($data->sheets[0]["cells"][$i][8]); //Costeo
    $dato['ganancia'] = trim($data->sheets[0]["cells"][$i][9]); //Ganancia
    $dato['preparacion'] = trim($data->sheets[0]["cells"][$i][10]); //Preparacion
    $dato['precio'] = trim($data->sheets[0]["cells"][$i][11]); //Precio
 
    if($dato['codigo'] != '' && $dato['nombre'] != '' && $dato['tipo_producto'] != '' && $dato['unidad_compra'] != '' 
        && $dato['unidad_venta'] != '' && $dato['cantidad'] != ''){
        if ($dato['tipo_producto'] != 'Receta' && $dato['tipo_modificador'] == ''  && $dato['costeo'] == '') {
            echo '<script>$("#loader").hide(); $("#respuesta").show(); $("#mensaje").html("Existen registros con campos obligatorios vacios y no se guardaron los registros, revise su layout en la linea '.$i.'.");</script>';
            $recetas = [];
            break;
        } else {
            if($dato['precio'] == ''){
                $dato['precio'] = 0;
            }
            if ($dato['tipo_producto'] == 'Receta') {
                $dato['precio_costeo'] = 0;
                $recetas[] = $dato;
            } else if ($dato['tipo_producto'] == 'Insumo') {
                $dato['id_producto'] = $this->recetasModel->id_producto_codigo($dato['codigo']); // id producto
                if($dato['id_producto']){
                    $aux_modi = '';
                    if(strpos($dato['tipo_modificador'], 'ormal')){
                        $aux_modi .= '0'.',';
                    }
                    if(strpos($dato['tipo_modificador'], 'in')){
                        $aux_modi .= '1'.',';
                    }
                    if(strpos($dato['tipo_modificador'], 'xtra')){
                        $aux_modi .= '2'.',';
                    }
                    if(strpos($dato['tipo_modificador'], 'pcional')){
                        $aux_modi .= '3'.',';
                    }
                    $dato['tipo_modificador'] = $aux_modi;
                    $dato['tipo_modificador'] = substr($dato['tipo_modificador'], 0, -1);
                    if($dato['costeo'] == 'si'){
                        $dato['costeo'] = 1;
                        $objeto['id'] = $dato['id_producto'];
                        $costo = $this->recetasModel->listar_insumos($objeto);
                        $dato['precio'] = $costo['rows'][0]['costo'];
                        $recetas[count($recetas)-1]['precio_costeo'] = $recetas[count($recetas)-1]['precio_costeo'] + $dato['precio'];
                    } else {
                        $dato['costeo'] = 0;
                    }
                    $recetas[count($recetas)-1]['insumos_ids'] .= $dato['id_producto'] . ',';
                    $recetas[count($recetas)-1]['insumos'][] = $dato;
                }else {
                    echo '<script>$("#loader").hide(); $("#respuesta").show(); $("#mensaje").html("El producto '.$dato['codigo'].' '.$dato['nombre'].' no existe para poder insertarlo en la receta.");</script>';
                    $recetas = [];
                    break;
                }
            } else if ($dato['tipo_producto'] == 'Insumo Elaborado') {
                $dato['id_producto'] = $this->recetasModel->id_producto_codigo($dato['codigo']); // id producto
                if ($dato['id_producto']) {
                    $aux_modi = '';
                    if(strpos($dato['tipo_modificador'], 'ormal')){
                        $aux_modi .= '0'.',';
                    }
                    if(strpos($dato['tipo_modificador'], 'in')){
                        $aux_modi .= '1'.',';
                    }
                    if(strpos($dato['tipo_modificador'], 'xtra')){
                        $aux_modi .= '2'.',';
                    }
                    if(strpos($dato['tipo_modificador'], 'pcional')){
                        $aux_modi .= '3'.',';
                    }
                    $dato['tipo_modificador'] = $aux_modi;
                    if($dato['costeo'] == 'si'){
                        $dato['costeo'] = 1;
                        $objeto['id'] = $dato['id_producto'];
                        $costo = $this->recetasModel->listar_insumos($objeto);
                        $dato['precio'] = $costo['rows'][0]['costo'];
                        $recetas[count($recetas)-1]['precio_costeo'] = $recetas[count($recetas)-1]['precio_costeo'] + $dato['precio'];
                    } else {
                        $dato['costeo'] = 0;
                    }
                    $recetas[count($recetas)-1]['insumos_elaborados_ids'] .= $dato['id_producto'] . ',';
                    $recetas[count($recetas)-1]['insumos_elaborados'][] = $dato;
                }else {
                    echo '<script>$("#loader").hide(); $("#respuesta").show(); $("#mensaje").html("El producto '.$dato['codigo'].' '.$dato['nombre'].' no existe para poder insertarlo en la receta.");</script>';
                    $recetas = [];
                    break;
                }
            }else{
                $flag_chk = 1;
                   echo '<script>$("#loader").hide(); $("#respuesta").show(); $("#mensaje").html("Existen registros con campos obligatorios vacios y no se guardaron los registros.");</script>';
            $recetas = [];
            break;
            }
            
            
        }
        
    } else {
        //$this->recetasModel->borrar(99);
            echo '<script>$("#loader").hide(); $("#respuesta").show(); $("#mensaje").html("Existen registros con campos obligatorios vacios y no se guardaron los registros, revise su layout en la linea '.$i.'.");</script>';
            $recetas = [];

            break;

    }

    
}

if($flag_chk == 1 ){
    echo '<script>$("#loader").hide(); $("#respuesta").show(); $("#mensaje").html("El tipo de producto no es correcto, revise su layout en la linea '.$i.'.");</script>';
   $recetas = [];
    break;


}

if(!empty($recetas)){
    $resp = $this->recetasModel->guardarLay($recetas);
    if($resp['status'] == 1){
        $this->recetasModel->activar_recetas(99);
    } else {
        $this->recetasModel->borrar(99);
    }
    echo '<script>$("#loader").hide(); $("#respuesta").show(); $("#mensaje").html("'.$resp['mensaje'].'");</script>';
}
?>
