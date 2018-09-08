<?php 
    $R1            = $_GET["R1"];
    $hasta         = $_GET["hasta"];
    $nomAl         = $_GET["nomAl"];
    //$hasta = "2016-05-23";
    if($hasta == null){
        $hasta = "Actual";
    }
    if($nomAl == null){
        $nomAl = "Todos";
    }
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/inventarios.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>

    <!--Data Tables 
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
   -->
<body> 
<br> 
<div class="container well" id="divfiltro">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Existencias</h3>
        </div>
    </div>
    <div class="row col-md-12">                     <input type="hidden" value="<?php echo $R1; ?>" id="reporte"/> 
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6"> 

                        <label>Hasta la fecha de:</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Fecha de Entrega">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>

                        <label>Productos</label><br>
                        <select id="producto" class="form-control">
                            <option value="0">-Todos-</option> 
                            <?php 
                                foreach ($existencias['productos'] as $key1 => $value1) {
                                    echo '<option value="'.$value1['codigo'].'">'.$value1['codigo'].'/'.$value1['nombre'].'</option>';
                                }
                            ?>                         
                        </select><br>
                        <label>Departamento</label><br>
                        <select id="departamento" class="form-control">
                            <option value="0">-Todos-</option> 
                            <?php 
                                foreach ($existencias['departamentos'] as $key1 => $value1) {
                                    echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'</option>';
                                }
                            ?>                          
                        </select><br>

                        <div id="divfamilia">
                            <label>Familia</label><br>
                            <select id="familia" class="form-control">
                                <option value="0">-Todas-</option>                          
                            </select><br>
                        </div>
                        <div id="divlinea">
                            <label>Linea</label><br>
                            <select id="linea" class="form-control">
                                <option value="0">-Todas-</option>                          
                            </select><br> 
                        </div>
                        <div id="divcaract">
                            <label>Caracteristicas</label><br>
                            <select id="caracteristicas" class="form-control">
                                <option value="0">-Todas-</option>                          
                            </select>
                        </div>
   
                    </div>
                    <div class="col-sm-6">
                        <label>Sucursal</label><br>
                        <select id="sucursal" class="form-control">
                            <option value="0">-Todas-</option>                           
                        </select><br>
                        <label>Almacen</label><br>
                        <select id="almacen" class="form-control">
                            <option value="0">-Todos-</option>
                            <?php 
                                foreach ($existencias['almacenes'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['codigo_sistema'].'">'.$value2['codigo_sistema'].' '.$value2['nombre'].'</option>';
                                }
                            ?>                           
                        </select><br>
                        <label>Unidad</label><br>
                        <select id="unidad" class="form-control">
                            <option value="0">-Unidad Base-</option>                          
                        </select><br>
                        <label>Seleccion de Unidad</label><br>
                        <select id="selunidad" class="form-control">
                            <option value="0">-PZA-</option>
                      
                        </select><br>
                        <div class="col-sm-6">
                            <label>Reporte</label><br>
                            <input type="radio" name="rep" id="R1unidades" value="unidades" checked="checked">En Unidades<br>
                            <input type="radio" name="rep" id="R1importe" value="importe">En Importe<br>
                            <input type="radio" name="rep" id="R1ambos" value="ambos">Ambos
                        </div>
                         <div class="col-sm-6">
                            <label></label><br>
                            <br>
                            <input type="radio" name="rep2" id="R2global" value="global" checked="checked">Global<br>
                            <input type="radio" name="rep2" id="R2detalle" value="detalle">A detalle
                        </div>
                        <div class="col-sm-12">
                            <label>Imprimir Productos</label><br>
                            <input type="radio" name="rep3" id="R3todos" value="todos" checked="checked">Todos<br>
                            <input type="radio" name="rep3" id="R3movimientos" value="movimientos">Solo con moviminetos  <button class="btn btn-default" onclick="procesarExs();">Procesar</button><br> 
                        </div>
                    </div>
                </div><br>
            </div>    
        </div>
    </div>
</div>

<?php

                            foreach ($existencias['grid'] as $key => $val) { // Recorre el array principal para traspasos 
                                $id                 = $val['id'];
                                $nombre             = $val['nombre'];
                                $fecha              = $val['fecha'];
                                $cantidad           = $val['cantidad'];
                                $costo              = $val['costo'];
                                $importe            = $val['importe'];
                                $almacen            = $val['almacen'];
                                $almacenO           = $val['idorigen'];
                                $almacenD           = $val['iddestino'];
                                $codigo             = $val['codigo'];
                                $tipo_traspaso      = $val['tipo_traspaso'];
                                $unidad             = $val['unidad'];
                                $moneda             = $val['moneda'];
                                $almacenNombre      = $val['almacenNombre'];
                
                                 if($tipo_traspaso == 0){
                                     $arrEST[] = array(
                                            id               => $id,
                                            nombre           => $nombre,
                                            fecha            => $fecha,
                                            cantidad         => $cantidad,
                                            costo            => $costo,
                                            importe          => $importe,
                                            almacen          => $almacen,
                                            almacenO         => $almacenO,
                                            almacenD         => $almacenD,
                                            codigo           => $codigo,
                                            tipo_traspaso    => $tipo_traspaso,
                                            tipo_traspasoaux => 0, // entrada aparente
                                            unidad           => $unidad,
                                            moneda           => $moneda,
                                            almacenNombre    => $almacenNombre,
                                        );

                                 }
                                 if($tipo_traspaso == 1){

                                  
                                    $arrEST[] = array(
                                            id               => $id,
                                            nombre           => $nombre,
                                            fecha            => $fecha,
                                            cantidad         => $cantidad,
                                            costo            => $costo,
                                            importe          => $importe,
                                            almacen          => $almacen,
                                            almacenO         => $almacenO,
                                            almacenD         => $almacenD,
                                            codigo           => $codigo,
                                            tipo_traspaso    => $tipo_traspaso,
                                            tipo_traspasoaux => 1, // entrada aparente
                                            unidad           => $unidad,
                                            moneda           => $moneda,
                                            almacenNombre    => $almacenNombre,
                                        ); 
                                }

                                if($tipo_traspaso == 2){

                                    $arrEST[] = array(
                                            id               => $id,
                                            nombre           => $nombre,
                                            fecha            => $fecha,
                                            cantidad         => $cantidad,
                                            costo            => $costo,
                                            importe          => $importe,
                                            almacen          => $almacen,
                                            almacenO         => $almacenO,
                                            almacenD         => $almacenD,
                                            codigo           => $codigo,
                                            tipo_traspaso    => $tipo_traspaso,
                                            tipo_traspasoaux => 1, // entrada aparente
                                            unidad           => $unidad,
                                            moneda           => $moneda,
                                            almacenNombre    => $almacenNombre,
                                        ); 

                                    $arrEST[] = array(
                                            id               => $id,
                                            nombre           => $nombre,
                                            fecha            => $fecha,
                                            cantidad         => $cantidad,
                                            costo            => $costo,
                                            importe          => $importe,
                                            almacen          => $almacenO,
                                            almacenO         => $almacenD, // se invierten
                                            almacenD         => $almacenO, // se invierten
                                            codigo           => $codigo,
                                            tipo_traspaso    => $tipo_traspaso,
                                            tipo_traspasoaux => 0, //salida aparente
                                            unidad           => $unidad,
                                            moneda           => $moneda,
                                            almacenNombre    => $almacenNombre,
                                        );
                                }
                            }
                            foreach($arrEST as $val){ // ordenamiento
                                $auxAl[] = $val['almacen'];
                            }
                            foreach($arrEST as $val){ // ordenamiento
                                $auxCo[] = $val['codigo'];
                            }
                            foreach($arrEST as $val){ // ordenamiento
                                $auxFe[] = $val['fecha'];
                            }
                            //var_dump($arrEST);
                            array_multisort($auxAl, SORT_ASC, $auxCo, SORT_ASC, $auxFe, SORT_ASC, $arrEST);
                            //print_r($arrEST); 
                            //echo json_encode($arrEST);
                            //echo json_encode($aux);
                            $existencia = 0;
                            foreach ($arrEST as $value) {
                                
                                $id                 = $value['id'];
                                $nombre             = $value['nombre'];
                                $fecha              = $value['fecha'];
                                $cantidad           = $value['cantidad'];
                                $costo              = $value['costo'];
                                $importe            = $value['importe'];
                                $almacen            = $value['almacen'];
                                $almacenO           = $value['almacenO'];
                                $almacenD           = $value['almacenD'];
                                $codigo             = $value['codigo'];
                                $tipo_traspaso      = $value['tipo_traspaso'];
                                $tipo_traspasoaux   = $value['tipo_traspasoaux'];
                                $unidad             = $value['unidad'];
                                $moneda             = $value['moneda'];
                                $almacenNombre      = $value['almacenNombre'];

                                if($almacen != $almacenAnt or $codigo != $codigoAnt){
                                    $existencia = 0;
                                }
                                if($tipo_traspasoaux == 0){//salida
                                    $existencia = $existencia - $cantidad;
                                }
                                if($tipo_traspasoaux == 1){//entrada
                                    $existencia = $existencia + $cantidad;
                                }

                                if($almacen == $almacenAnt and  $codigo == $codigoAnt){
                                    $conut++;
                                }else{
                                    $conut=1;
                                }
                                if($almacen == $almacenAnt){
                                    $conut2++;
                                }else{
                                    $conut2=1;
                                }

                                $arrExs[] = array(
                                            id                  => $id,
                                            nombre              => $nombre,
                                            fecha               => $fecha,
                                            cantidad            => $cantidad,
                                            costo               => $costo,
                                            importe             => $importe,
                                            almacen             => $almacen,
                                            almacenO            => $almacenO,
                                            almacenD            => $almacenD,
                                            codigo              => $codigo,
                                            tipo_traspaso       => $tipo_traspaso,
                                            tipo_traspasoaux    => $tipo_traspasoaux,
                                            existencia          => $existencia,
                                            count               => "".$conut."",// cuenta x productos iguales del mismo almacen
                                            count2              => "".$conut2."",// cuenta x todos los productos del mismo almacen
                                            unidad              => $unidad,
                                            moneda              => $moneda,
                                            almacenNombre       => $almacenNombre,
                                        ); 
                                    
                                $almacenAnt            = $value['almacen'];
                                $codigoAnt             = $value['codigo'];                           
                            }
                            //echo json_encode($arrExs);
                            $arrExsR = array_reverse($arrExs);
                            //echo json_encode($arrExsR);

                            ///////// ARREGLO CREADO PARA OBTENER LOS MAXIMOS CONTADORES PARA IMPRIMIR LOS PIES DE TABLA /////
                            foreach ($arrExsR as $value) {
                                // SE RECORRE EL ARRAY INVERTIDO
                                $almacen = $value['almacen'];
                                $codigo = $value['codigo'];
                                $count2 = $value['count2'];

                                if ($count2 >= $count2Ant){
                                                $arrMaxCountR[] = array( // SE CREAR UN ARRAY CON LOS MAXIMOS CONTADORES AGRUPADOS
                                                        id          => $value['id'],
                                                        almacen     => $value['almacen'],
                                                        producto    => $value['producto'],
                                                        codigo      => $value['codigo'],
                                                        count       => $value['count'],
                                                        count2      => $value['count2'],    
                                                    );
                                }
                                
                                $almacenAnt = $value['almacen'];
                                $codigoAnt = $value['codigo'];
                                $count2Ant = $value['count2'];
                            }
                            $arrMaxCount = array_reverse($arrMaxCountR);
                            //echo json_encode($arrMaxCount);
                            ////FIN///// ARREGLO CREADO PARA OBTENER LOS MAXIMOS CONTADORES PARA IMPRIMIR LOS PIES DE TABLA /////

                            foreach ($arrExsR as $value) {
                                // SE RECORRE EL ARRAY INVERTIDO
                                $almacen = $value['almacen'];
                                $codigo = $value['codigo'];
                                $count = $value['count'];

                                if ($count >= $countAnt){
                                                $arrExsR2[] = array( // SE CREAR UN ARRAY CON LOS MAXIMOS CONTADORES AGRUPADOS
                                                        id                  => $value['id'],
                                                        nombre              => $value['nombre'],
                                                        fecha               => $value['fecha'],
                                                        cantidad            => $value['cantidad'],
                                                        costo               => $value['costo'],
                                                        importe             => $value['importe'],
                                                        almacen             => $value['almacen'],
                                                        almacenO            => $value['almacenO'],
                                                        almacenD            => $value['almacenD'],    
                                                        codigo              => $value['codigo'],
                                                        tipo_traspaso       => $value['tipo_traspaso'],
                                                        tipo_traspasoaux    => $value['tipo_traspasoaux'],
                                                        existencia          => $value['existencia'],
                                                        count               => $value['count'],
                                                        count2              => $value['count2'],
                                                        unidad              => $value['unidad'],
                                                        moneda              => $value['moneda'],
                                                        almacenNombre       => $value['almacenNombre'],

                                                    );
                                }
                                
                                $almacenAnt = $value['almacen'];
                                $codigoAnt = $value['codigo'];
                                $countAnt = $value['count'];

                            }
                            $arrExsR1 = array_reverse($arrExsR2);
                            //echo json_encode($arrExsR1);
                            /////////////////////////////////////////////////////////////////////////////////////////////////////////
                            
 ?>
<div class="container well" id="divambos">
    <?php
        $contador        = 0;
        $sumaAlmacen     = 0;
        $importeAlmacen  = 0;
        $almacenAnt      = -1; /// auxiliar para que imprima el primer almacen
        $count2Ant       = -1;
        $sumaAlmacenT    = 0;
        $importeAlmacenT = 0;


                echo "<br><h1>Reporte de Existencias</h1>";
                echo '<div id="divtable'.$almacen.'" class="panel-body">
                <table class="table table-hover">
                <h2>Cantidad  e Importe</h2>
                <h4>A la fecha de: '.$hasta.'</h4>
                <h4>Sucursal: '.$suc.'</h4>
                <h4>Almacen: '.$nomAl.'</h4>
                <h4>Productos: '.$pro.'</h4>
                <h4>Moneda: MXN</h4>
                <thead style="border-top:5px solid black; background:Lightgrey">
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Existencias Unidades</th>
                        <th>Unidad Medida</th>
                        <th>Costo Unitario</th>
                        <th>Importe</th>
                        <th>Moneda</th>
                    </tr>
                </thead>';

        foreach ($arrExsR1 as $value) {
                $id                 = $value['id'];
                $nombre             = $value['nombre'];
                $fecha              = $value['fecha'];
                $cantidad           = $value['cantidad'];
                $costo              = $value['costo'];
                $importe            = $value['importe'];
                $almacen            = $value['almacen'];
                $almacenO           = $value['almacenO'];
                $almacenD           = $value['almacenD'];
                $codigo             = $value['codigo'];
                $tipo_traspaso      = $value['tipo_traspaso'];
                $tipo_traspasoaux   = $value['tipo_traspasoaux'];
                $existencia         = $value['existencia'];
                $unidad             = $value['unidad'];
                $moneda             = $value['moneda'];
                $almacenNombre      = $value['almacenNombre'];
                $count              = $value['count'];
                $count2             = $value['count2'];
            
            if($almacen != $almacenAnt){  // CREA LOS RUPOS POR ALAMCEN 

                $contador       = 0;
                $sumaAlmacen    = 0;
                $importeAlmacen = 0;
                echo '<thead>
                        <tr>
                            <th width = "10" height ="80">Almacen</th>
                            <th height ="80"><b>'.$almacenNombre.'</b></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr></thead>';         
            }

                //suma por almacen
                $sumaAlmacen = $sumaAlmacen + $existencia;
                $importeAlmacen = $importeAlmacen + $importe;
                //format
                $existenciaF = number_format($existencia,2);
                $costoF = number_format($costo,2);
                $importeF = number_format($importe,2);
                 
                
                echo '<tr>';
                echo '<td>'.$codigo.'</td>';
                echo '<td>'.$nombre.'</td>';
                echo '<td align="right">'.$existenciaF.'</td>';
                echo '<td>'.$unidad.'</td>';
                echo '<td align="right">$'.$costoF.'</td>';
                echo '<td align="right">$'.$importeF.'</td>';
                echo '<td>'.$moneda.'</td>';
                //echo '<td>'.$sumaAlmacen.'</td>';
                //echo '<td>'.$count2.'</td>';
                //echo '<td>'.$tipo_traspasoaux.'</td>';
                //echo '<td>'.$existencia.'</td>';
                echo '</tr>';

            foreach ($arrMaxCount as $val) {

                    $almacengroup = $val['almacen'];
                    $idproducnto = $val['codigo'];
                    $count = $val['count2'];
                    
                    if($almacengroup == $almacen){ // DA VALOR A NUEVO CONTADOR
                        $count = $val['count2'];
                        $countmax = $count;
                            //echo "almacen ".$almacengroup."almacenR".$almacen."count ".$countmax." ".$count2."<br>";
                        }
              }

              if($count2 == $countmax)
              {
                
                //suma por todos los almacenes
                $sumaAlmacenT = $sumaAlmacenT + $sumaAlmacen;
                $importeAlmacenT = $importeAlmacenT + $importeAlmacen;

                $sumaAlmacenF = number_format($sumaAlmacen,2);
                $importeAlmacenF = number_format($importeAlmacen,2);

                echo '
                        <tr>
                            <td></td>
                            <td></td>
                            <td height ="80" align="right"><b>'.$sumaAlmacenF.'</b></td>
                            <td></td>
                            <td></td>
                            <td height ="80" align="right"><b>$'.$importeAlmacenF.'</b></td>
                            <td></td>
                        </tr>'; 
              }
              
            $count2Ant          = $value['count2'];;
            $codigoAnt          = $value['codigo'];
            $almacenAnt         = $value['almacen'];
        }
            $sumaAlmacenTF = number_format($sumaAlmacenT,2);
            $importeAlmacenTF = number_format($importeAlmacenT,2);
        echo '
                        <tr>
                            <td><b>Total Amacenes</b></td>
                            <td></td>
                            <td height ="80" align="right"><b>'.$sumaAlmacenTF.'</b></td>
                            <td></td>
                            <td></td>
                            <td height ="80" align="right"><b>$'.$importeAlmacenTF.'</b></td>
                            <td></td>
                        </tr>';
        echo '</table>';
        echo '</div>';
     ?>
</div>

<div class="container well" id="divcantidades">
    <?php
        $sumaAlmacen     = 0;
        $almacenAnt      = -1; /// auxiliar para que imprima el primer almacen
        $count2Ant       = -1;
        $sumaAlmacenT    = 0;



                echo "<br><h1>Reporte de Existencias</h1>";
                echo '<div id="divtable'.$almacen.'" class="panel-body">
                <table class="table table-hover">
                <h2>Cantidades</h2>
                <h4>A la fecha de: '.$hasta.'</h4>
                <h4>Sucursal: '.$suc.'</h4>
                <h4>Almacen: '.$nomAl.'</h4>
                <h4>Productos: '.$pro.'</h4>
                <h4>Moneda: MXN</h4>
                <thead style="border-top:5px solid black; background:Lightgrey">
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Existencias Unidades</th>
                        <th>Unidad Medida</th>
                    </tr>
                </thead>';

        foreach ($arrExsR1 as $value) {
                $id                 = $value['id'];
                $nombre             = $value['nombre'];
                $fecha              = $value['fecha'];
                $cantidad           = $value['cantidad'];
                $costo              = $value['costo'];
                $importe            = $value['importe'];
                $almacen            = $value['almacen'];
                $almacenO           = $value['almacenO'];
                $almacenD           = $value['almacenD'];
                $codigo             = $value['codigo'];
                $tipo_traspaso      = $value['tipo_traspaso'];
                $tipo_traspasoaux   = $value['tipo_traspasoaux'];
                $existencia         = $value['existencia'];
                $unidad             = $value['unidad'];
                $moneda             = $value['moneda'];
                $almacenNombre      = $value['almacenNombre'];
                $count              = $value['count'];
                $count2             = $value['count2'];
            
            if($almacen != $almacenAnt){  // CREA LOS RUPOS POR ALAMCEN 

                $contador       = 0;
                $sumaAlmacen    = 0;

                echo '<thead>
                        <tr>
                            <th width = "10" height ="80">Almacen</th>
                            <th height ="80"><b>'.$almacenNombre.'</b></th>
                            <th></th>
                            <th></th>
                        </tr></thead>';         
            }

                //suma por almacen
                $sumaAlmacen = $sumaAlmacen + $existencia;
                $importeAlmacen = $importeAlmacen + $importe;
                //format
                $existenciaF = number_format($existencia,2);
                                 
                echo '<tr>';
                echo '<td>'.$codigo.'</td>';
                echo '<td>'.$nombre.'</td>';
                echo '<td align="right">'.$existenciaF.'</td>';
                echo '<td>'.$unidad.'</td>';
                echo '</tr>';

            foreach ($arrMaxCount as $val) {

                    $almacengroup = $val['almacen'];
                    $idproducnto = $val['codigo'];
                    $count = $val['count2'];
                    
                    if($almacengroup == $almacen){ // DA VALOR A NUEVO CONTADOR
                        $count = $val['count2'];
                        $countmax = $count;
                            //echo "almacen ".$almacengroup."almacenR".$almacen."count ".$countmax." ".$count2."<br>";
                        }
              }

              if($count2 == $countmax)
              {
                
                //suma por todos los almacenes
                $sumaAlmacenT = $sumaAlmacenT + $sumaAlmacen;

                $sumaAlmacenF = number_format($sumaAlmacen,2);

                echo '
                        <tr>
                            <td></td>
                            <td></td>
                            <td height ="80" align="right"><b>'.$sumaAlmacenF.'</b></td>
                            <td></td>
                        </tr>'; 
              }
              
            $count2Ant          = $value['count2'];;
            $codigoAnt          = $value['codigo'];
            $almacenAnt         = $value['almacen'];
        }
            $sumaAlmacenTF = number_format($sumaAlmacenT,2);
        echo '
                        <tr>
                            <td><b>Total Amacenes</b></td>
                            <td></td>
                            <td height ="80" align="right"><b>'.$sumaAlmacenTF.'</b></td>
                            <td></td>
                        </tr>';
        echo '</table>';
        echo '</div>';
     ?>
</div>

<div class="container well" id="divimporte">
    <?php
        $importeAlmacen  = 0;
        $almacenAnt      = -1; /// auxiliar para que imprima el primer almacen
        $count2Ant       = -1;
        $importeAlmacenT = 0;


                echo "<br><h1>Reporte de Existencias</h1>";
                echo '<div id="divtable'.$almacen.'" class="panel-body">
                <table class="table table-hover">
                <h2>Importe</h2>
                <h4>A la fecha de: '.$hasta.'</h4>
                <h4>Sucursal: '.$suc.'</h4>
                <h4>Almacen: '.$nomAl.'</h4>
                <h4>Productos: '.$pro.'</h4>
                <h4>Moneda: MXN</h4>
                <thead style="border-top:5px solid black; background:Lightgrey">
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Importe</th>
                        <th>Moneda</th>
                    </tr>
                </thead>';

        foreach ($arrExsR1 as $value) {
                $id                 = $value['id'];
                $nombre             = $value['nombre'];
                $fecha              = $value['fecha'];
                $cantidad           = $value['cantidad'];
                $costo              = $value['costo'];
                $importe            = $value['importe'];
                $almacen            = $value['almacen'];
                $almacenO           = $value['almacenO'];
                $almacenD           = $value['almacenD'];
                $codigo             = $value['codigo'];
                $tipo_traspaso      = $value['tipo_traspaso'];
                $tipo_traspasoaux   = $value['tipo_traspasoaux'];
                $existencia         = $value['existencia'];
                $unidad             = $value['unidad'];
                $moneda             = $value['moneda'];
                $almacenNombre      = $value['almacenNombre'];
                $count              = $value['count'];
                $count2             = $value['count2'];
            
            if($almacen != $almacenAnt){  // CREA LOS RUPOS POR ALAMCEN 

                $importeAlmacen = 0;

                echo '<thead>
                        <tr>
                            <th width = "10" height ="80">Almacen</th>
                            <th height ="80"><b>'.$almacenNombre.'</b></th>
                            <th></th>
                            <th></th>
                        </tr></thead>';         
            }

                //suma por almacen
                $importeAlmacen = $importeAlmacen + $importe;
                //format
                $importeF = number_format($importe,2);
                 
                
                echo '<tr>';
                echo '<td>'.$codigo.'</td>';
                echo '<td>'.$nombre.'</td>';
                echo '<td align="right">$'.$importeF.'</td>';
                echo '<td>'.$moneda.'</td>';
                echo '</tr>';

            foreach ($arrMaxCount as $val) {

                    $almacengroup = $val['almacen'];
                    $idproducnto = $val['codigo'];
                    $count = $val['count2'];
                    
                    if($almacengroup == $almacen){ // DA VALOR A NUEVO CONTADOR
                        $count = $val['count2'];
                        $countmax = $count;
                            //echo "almacen ".$almacengroup."almacenR".$almacen."count ".$countmax." ".$count2."<br>";
                        }
              }

              if($count2 == $countmax)
              {
                
                //suma por todos los almacenes
                $importeAlmacenT = $importeAlmacenT + $importeAlmacen;

                $importeAlmacenF = number_format($importeAlmacen,2);

                echo '
                        <tr>
                            <td></td>
                            <td></td>
                            <td height ="80" align="right"><b>$'.$importeAlmacenF.'</b></td>
                            <td></td>
                        </tr>'; 
              }
              
            $count2Ant          = $value['count2'];;
            $codigoAnt          = $value['codigo'];
            $almacenAnt         = $value['almacen'];
        }

            $importeAlmacenTF = number_format($importeAlmacenT,2);
        echo '
                        <tr>
                            <td><b>Total Amacenes</b></td>
                            <td></td>
                            <td height ="80" align="right"><b>$'.$importeAlmacenTF.'</b></td>
                            <td></td>
                        </tr>';
        echo '</table>';
        echo '</div>';
     ?>
</div>

     
</body>
</html>
<script>
   $(document).ready(function() {

        $("#divambos").hide();
        $("#divcantidades").hide();
        $("#divimporte").hide();

        reporte = $("#reporte").val();
        if ("unidades" == reporte){
                $("#divambos").hide();
                $("#divfiltro").hide();
                $("#importe").hide();
                $("#divcantidades").show();
            }
        if ("importe" == reporte){
                $("#divfiltro").hide();
                $("#divcantidades").hide();
                $("#divambos").hide();
                $("#divimporte").show();
            }
        if ("ambos" == reporte){
                $("#divfiltro").hide();
                $("#divcantidades").hide();
                $("#divimporte").hide();
                $("#divambos").show();
            }

        $('#producto, #departamento, #familia, #linea, #caracteristicas, #sucursal, #almacen, #unidad, #selunidad').select2();

        $('#hasta').datepicker({
            format: "yyyy-mm-dd",
        });
 
        $("#divfamilia").hide();
        $("#divlinea").hide();
        $("#divcaract").hide();

        $('#departamento').change(function()
        {
            var iddepartamento = $("#departamento").val();
            $.ajax({
                url: 'ajax.php?c=inventario&f=listFamilia',
                type: 'post',
                dataType: 'json',
                data: {iddepartamento: iddepartamento,},
            })
            .done(function(data) {
                $('#divfamilia').show(500);

                $('#familia').html('');
                $('#familia').html('<option selected="selected" value="0">-Todas-</option>');
                $('#familia').prop('selected',true);
                

                $('#linea').html('');
                $('#linea').html('<option selected="selected" value="0">-Todas-</option>');
                $('#linea').prop('selected',true);

                $.each(data, function(index, val) {
                    $('#familia').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
                });
            })
        });

        $('#familia').change(function()
        {
            var idfamilia = $("#familia").val();
            $.ajax({
                url: 'ajax.php?c=inventario&f=listLinea',
                type: 'post',
                dataType: 'json',
                data: {idfamilia: idfamilia,},
            })
            .done(function(data) {
                $('#divlinea').show(500);
                $('#linea').html('');
                $('#linea').html('<option selected="selected" value="0">-Todas-</option>');
                $.each(data, function(index, val) {
                    $('#linea').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
                });
            })
        });
   });
   </script>


