<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reorden.php");

class Reorden extends Common
{
    public $ReordenModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ReordenModel = new ReordenModel();
        $this->ReordenModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ReordenModel->close();
    }

    function reorden(){
        $productos = $this->ReordenModel->productos();
        $sucursales = $this->ReordenModel->sucursales();
        require('views/reorden/reorden.php');
    }

    function productos(){
        $tipo = $_POST['tipo'];
        $productos = $this->ReordenModel->productos($tipo);
        echo json_encode($productos);
    }

    function generar(){
        $tipo       = $_POST['tipo'];
        $producto   = $_POST['producto'];
        $sucRR      = $_POST['suc'];
        $dias       = $_POST['dias'];

        $desde       = $_POST['desde'];
        $hasta       = $_POST['hasta'];
        $datetime1 = new DateTime($desde);
        $datetime2 = new DateTime($hasta);

        $interval = $datetime1->diff($datetime2);
        $diasRango = $interval->format('%R%a')*1;

        $ventas = $this->ReordenModel->ventas($desde,$hasta,$producto);

        $contV = $exisV = 0;

        foreach ($ventas as $ke => $va) {
            $pro    = $va['id_producto'];
            $suc    = $va['id_sucursal'];
            $cant   = $va['cantidad']*1;

            if($pro != $proAnt || $suc != $sucAnt){
                $contV = 0;
                $exisV = 0;
            }

            $contV++;
            $exisV = $exisV + $cant;

            $arrayVentas[] = array(
                'suc' => $suc,
                'pro' => $pro,
                'cant' => $cant,
                'contV' => $contV,
                'exisV' => $exisV,
            );

            $sucAnt = $va['id_sucursal'];
            $proAnt = $va['id_producto'];
        }
       
        $arrayVentasR = array_reverse($arrayVentas);

        $auxPro = $auxSuc = 0;
        foreach ($arrayVentasR as $key => $val) {
            $proR    = $val['pro'];
            $sucR    = $val['suc'];
            $cantR    = $val['cant'];
            $contVR    = $val['contV'];
            $exisVR    = $val['exisV'];

            if($proR != $proAntR){
                $auxPro = 1;
            }
            if($sucR != $sucAntR){
                $auxSuc = 1;
            }

            $prom = $exisVR / $diasRango; 

            if($auxPro == 1 || $auxSuc == 1){
                $arrayVentas2[] = array(
                    'suc' => $sucR,
                    'pro' => $proR,
                    'cant' => $cantR,
                    'contV' => $contVR,
                    'exisV' => $exisVR,
                    'auxPro' => $auxPro,
                    'auxSuc' => $auxSuc,
                    'diasRango' => $diasRango,
                    'prom' => $prom,
                );
           }

            $auxPro = $auxSuc = 0;

            $proAntR  = $val['pro'];
            $sucAntR  = $val['suc'];
        }

        $arrayProm = array_reverse($arrayVentas2);

        $movimientos = $this->ReordenModel->movimientos($producto,$sucRR,$tipo);

        $tablaphp = '<table id="tableReorden" class="table table-striped table-bordered sizeprint" cellspacing="0" width="90%">'.
                            '<thead>'.
                            '<tr>'.
                                '<th width="90">Código</th>'.
                                '<th>Nombre</th>'.
                                '<th width="40">Sucursal</th>'.
                                '<th width="30">Existencia Actual</th>'.
                                '<th width="30">Consumo Promedio Diario</th>'.
                                '<th width="30">Mínimo</th>'.                                
                                '<th width="30">Punto de reorden</th>'.

                          '</tr>'.
                        '</thead>';

        $existencia = 0;
        $last = 0;
        foreach ($movimientos as $k => $v) {
            $suc2        = $v['id_sucursal'];
            $pro2        = $v['codigo'];
            $cantidad   = $v['cantidad'];

            if($suc2 != $sucAnt || $pro2 != $proAnt){
                $existencia = 0;
            }

            if($v['traspasoaux'] == 1){ // entrada
                $existencia = $existencia + $cantidad;
            }else{                      // salida
                $existencia = $existencia - $cantidad;
            }

            $arrayMov[] = array(
                    'pro'           => $v['id_producto'],
                    'codigo'        => $v['codigo'],
                    'nombre'        => $v['nombre'],
                    'suc'           => $v['id_sucursal'],
                    'sucursal'      => $v['sucursal'],
                    'minimos'       => $v['minimos'],
                    'dias'          => $dias,
                    'cantidad'      => $v['cantidad'],
                    'traspasoaux'   => $v['traspasoaux'],
                    'existencia'    => $existencia,

                );

            $last = 0;
            $proAnt = $v['codigo'];
            $sucAnt = $v['id_sucursal'];
        } 

        $arrayMovR = array_reverse($arrayMov);
        $promR = 0;
        foreach ($arrayMovR as $key => $value) {
            $proM = $value['pro'];
            $sucM = $value['suc'];

            $auxProM = $auxSucM = 0;

            if($proM != $proMAnt){
                $auxProM = 1;
            }
            if($sucM != $sucMAnt){
                $auxSucM = 1;
            }

            if($auxProM == 1 || $auxSucM == 1){
                foreach ($arrayProm as $kk => $vv) {
                    $sucP = $vv['suc'];
                    $proP = $vv['pro'];
                    $prom = $vv['prom'];

                    if(($sucP == $sucM && $proP == $proM)){
                        $promR = $prom;
                        break;
                    }else{
                        $promR = 0;
                    }
                }
                
                //reorden
                $reorden = ($promR * $dias) + $value['minimos'];
                //reorden fin

                $arrayMov2[] = array(
                    'suc'       => $value['suc'],
                    'sucursal'  => $value['sucursal'],
                    'pro'       => $value['pro'],
                    'cant'      => $value['cantidad'],                    
                    'codigo'    => $value['codigo'],
                    'nombre'    => $value['nombre'],
                    'minimos'   => $value['minimos'],
                    'exis'      => $value['existencia'],
                    'prom'      => $promR,
                    'reorden'   => $reorden,
                );
           }

            $auxProM = $auxSucM = 0;

            $sucMAnt = $value['suc'];
            $proMAnt = $value['pro'];
        }

        $arrayMovF = array_reverse($arrayMov2);
        /// ordenar array por producto
        foreach($arrayMovF as $val){ // ordenamiento
                $auxCo[] = $val['codigo'];
            }
        array_multisort($auxCo, SORT_ASC, $arrayMovF);
        /// ordenar array por producto fin
        $auxS = 1;
        $sumExs = 0;
        $sumPro = 0;
        $sumMin = 0;
        $sumReo = 0;
        $cont=0;
        foreach ($arrayMovF as $kkk => $vvv) {

            $cont++;
            $codigo = $vvv['codigo'];            

            if($codigo != $codigoAnt || $auxS == 1){
                $cont=0;
                $sumExs = 0;
                $sumPro = 0;
                $sumMin = 0;
                $sumReo = 0;
                $auxS = 0;
            }

            $sumExs += $vvv['exis']*1;
            $sumPro += $vvv['prom']*1;
            $sumMin += $vvv['minimos']*1;
            $sumReo += $vvv['reorden']*1;

            $arrayReorden[] = array(
                    'suc'       => $vvv['suc'],
                    'sucursal'  => $vvv['sucursal'],
                    'pro'       => $vvv['pro'],
                    'cant'      => $vvv['cantidad'],                    
                    'codigo'    => $vvv['codigo'],
                    'nombre'    => $vvv['nombre'],
                    'minimos'   => $vvv['minimos'],
                    'exis'      => $vvv['exis'],
                    'prom'      => $vvv['prom'],
                    'reorden'   => $vvv['reorden'],
                    'sumExs'    => $sumExs,
                    'sumPro'    => $sumPro,
                    'sumMin'    => $vvv['minimos'],                
                    'sumReo'    => $sumReo,
                    'cont'      => $cont, 
                );
            
            $codigoAnt = $vvv['codigo'];
        }
        $aux2 = 0;
        $arrayReordenR = array_reverse($arrayReorden);
        foreach ($arrayReordenR as $ke => $ve) {
            $cont = $ve['cont'];
            $codigo2 = $ve['codigo'];

            if($cont > $contAnt){
                $auxCon = 1;
            }else{
                $auxCon = 0;
            }

            $sumExisF   = number_format($ve['sumExs'],2);
            $sumProF    = number_format($ve['sumPro'],2);
            $sumMinF    = number_format($ve['sumMin'],2);
            $sumReoF    = number_format($ve['sumReo'],2);

            if($auxCon == 1){
                $aux2 = 1;
                $y ='<tr>'.
                                '<td><b>'.$ve['codigo'].'</b></td>'.
                                '<td><b>'.$ve['nombre'].'</b></td>'.
                                '<td></td>'.
                                '<td align="center">'.$sumExisF.'</td>'.
                                '<td align="center">'.$sumProF.'</td>'.
                                '<td align="center">'.$sumMinF.'</td>'.                                
                                '<td align="center"><a target="_blank" href="../../modulos/appministra/index.php?c=compras&f=ordenes&re=1">'.$sumReoF.'</a></td>'.
                                '<td>'.$ve['codigo'].'</td>'.
                                '<td>'.$ve['nombre'].'</td>'.
                        '</tr>';
                if($sumReoF > 0){
                    $tablaphp .= $y;
                }
                
            }

            $exisF      = number_format($ve['exis'],2);
            $promF      = number_format($ve['prom'],2);
            $minimosF   = number_format($ve['minimos'],2);
            $reordenF   = number_format($ve['reorden'],2);

            if($codigo2 == $codigoAnt2 || $aux2 == 1){
                $aux2 = 0;
                $y ='<tr>'.
                                '<td><b></b></td>'.
                                '<td><b></b></td>'.
                                '<td align="center">'.$ve['sucursal'].'</td>'.
                                '<td align="center">'.$exisF.'</td>'.
                                '<td align="center">'.$promF.'</td>'.
                                '<td align="center">'.$minimosF.'</td>'.                                
                                '<td align="center"><a target="_blank" href="../../modulos/appministra/index.php?c=compras&f=ordenes&re=1">'.$reordenF.'</a></td>'.
                                '<td>'.$ve['codigo'].'</td>'.
                                '<td>'.$ve['nombre'].'</td>'.
                        '</tr>';
            }else{
                $aux2 = 0;
                $y ='<tr>'.
                                '<td><b>'.$ve['codigo'].'</b></td>'.
                                '<td><b>'.$ve['nombre'].'</b></td>'.
                                '<td align="center">'.$ve['sucursal'].'</td>'.
                                '<td align="center">'.$exisF.'</td>'.
                                '<td align="center">'.$promF.'</td>'.
                                '<td align="center">'.$minimosF.'</td>'.
                                '<td align="center"><a target="_blank" href="../../modulos/appministra/index.php?c=compras&f=ordenes&re=1">'.$reordenF.'</a></td>'.
                                '<td>'.$ve['codigo'].'</td>'.
                                '<td>'.$ve['nombre'].'</td>'.
                        '</tr>';
            }
            
            if($reordenF > 0){
               $tablaphp .= $y; 
            }
            
            
            $codigoAnt2 = $ve['codigo'];
            $contAnt = $ve['cont'];
        }                            
        echo $tablaphp;
    }
}

?>