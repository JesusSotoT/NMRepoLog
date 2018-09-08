<?php 
    $R1            = $_GET["R1"];
    $hasta         = $_GET["hasta"];
    $desde         = $_GET["desde"];
    //$hasta = "2016-05-23";
    if($hasta == null){
        $hasta = "3017-06-14";
    }
    if($desde == null){
        $desde = "1000-01-01";
    }
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ordenes de Compra</title>
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
           <h3>Kardex</h3>
        </div>
    </div>
    <div class="row col-md-12">                     <input type="hidden" value="<?php echo $R1; ?>" id="reporte"/> 
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Rango de Fechas Desde</label><br>
                        <div id="datetimepicker1" class="input-group date">
                            <input id="desde" class="form-control" type="text" placeholder="Fecha de Entrega">
                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                            </span> 
                        </div>
                        

                        <label>Productos</label><br>
                        <select id="producto" class="form-control">
                            <option value="0">-Todos-</option> 
                            <?php 
                                foreach ($inventarioActual2['productos'] as $key1 => $value1) {
                                    echo '<option value="'.$value1['codigo'].'">'.$value1['codigo'].'/'.$value1['nombre'].'</option>';
                                }
                            ?>                         
                        </select><br>
                        <label>Departamento</label><br>
                        <select id="departamento" class="form-control">
                            <option value="0">-Todos-</option> 
                            <?php 
                                foreach ($inventarioActual2['departamentos'] as $key1 => $value1) {
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
                        <label>Hasta</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Fecha de Entrega">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>
                        <label>Sucursal</label><br>
                        <select id="sucursal" class="form-control">
                            <option value="0">-Todas-</option>                           
                        </select><br>
                        <label>Almacen</label><br>
                        <select id="almacen" class="form-control">
                            <option value="0">-Todos-</option>
                            <?php 
                                foreach ($inventarioActual2['almacenes'] as $key2 => $value2) {
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
                            <input type="radio" name="rep3" id="R3movimientos" value="movimientos">Solo con moviminetos  <button class="btn btn-default" onclick="procesar1();">Procesar</button><br> 
                        </div>
                    </div>
                </div><br>
            </div>    
        </div>
    </div>
</div>

<?php 
                            /// ARREGLO CON LOS TRASPASOS ///
                            foreach ($inventarioActual2['grid00'] as $key => $value) { // Recorre el array principal para traspasos 
                                $tipoTraspaso1 = $value['tipo_traspaso'];             // podria ser des MYSQL solo con lo necesario
                                if(($tipoTraspaso1 == 2) and ($value['fecha'] <= $hasta." 23:59:59")){
                                    $array[] = array(
                                                        /// SE ALMACENAN LOS TRASPASOS EN UN ARRAY
                                                        idmov       => $value['id'],
                                                        idalmacen   => $value['almacen'],
                                                        fecha       => $value['fecha'],
                                                        producto    => $value['nombre'],
                                                        idproducnto => $value['codigo'],
                                                        cantidad    => $value['cantidad'],
                                                        importe     => $value['importe'],
                                                        origen      => $value['idorigen'],
                                                        destino     => $value['iddestino'],
                                                    );          
                                }
                            }
                            //echo json_encode($array); ///MUESTRA TODOS LOS TRASPASOS
                            //echo '<br><br><br>';
                            $inventarioIni =0;
                            $inventarioIniI =0;


                            /////////////////////////////////////////////////////////////////////////////////////////////////////////
                            foreach ($inventarioActual2['grid0'] as $key => $val) { // Recorre el array principal para traspasos 
                                $id = $val['id'];
                                $fecha = $val['fecha'];
                                $cantidad = $val['cantidad'];
                                $importe = $val['importe'];
                                $almacen = $val['almacen'];
                                $almacenO = $val['idorigen'];
                                $almacenD = $val['iddestino'];
                                $codigo = $val['codigo'];
                                $tipo_traspaso = $val['tipo_traspaso'];

                                    if($almacen != $almacenAnt or $codigo != $codigoAnt){
                                        $inventarioIni = 0;
                                        $inventarioIniI = 0;
                                    }
                                    if($fecha < $desde){

                                        if($tipo_traspaso == 0){//salida
                                            $inventarioIni = $inventarioIni - $cantidad;
                                            $inventarioIniI = $inventarioIniI - $importe;
                                        }
                                        if($tipo_traspaso == 1){//entrada
                                            $inventarioIni = $inventarioIni + $cantidad;
                                            $inventarioIniI = $inventarioIniI + $importe;
                                        }
                                        if(($tipo_traspaso == 2) and ($almacen == $almacenD)){//traspaso
                                            $inventarioIni = $inventarioIni + $cantidad;
                                            $inventarioIniI = $inventarioIniI + $importe;

                                        }

                                                    
                                        $arrExs[] = array(
                                            id               => $id,
                                            fecha            => $fecha,
                                            fechahasta       => $hasta,
                                            cantidad         => $cantidad,
                                            almacen          => $almacen,
                                            almacenO         => $almacenO,
                                            almacenD         => $almacenD,
                                            codigo           => $codigo,
                                            inventarioIni    => $inventarioIni,
                                            inventarioIniI   => $inventarioIniI,
                                            tipo_traspaso    => $tipo_traspaso,
                                        ); 
                                    }

                                    
                                $fechaAnt = $val['fecha'];
                                $codigoAnt = $val['codigo'];
                                $almacenAnt = $val['almacen'];
                                
                            }
                            //echo json_encode($arrExs);
                            //echo '<br><br>';
                            //echo json_encode($arrTrasOrigen);

                            $arrExsR = array_reverse($arrExs);
                            //echo '<br><br>';
                            //echo json_encode($arrExsR);
                            $auxExs = 1;
                            foreach($arrExsR as $valR){

                                $id = $valR['id'];
                                $fecha = $valR['fecha'];
                                $cantidad = $valR['cantidad'];
                                $almacen = $valR['almacen'];
                                $almacenO = $valR['almacenO'];
                                $almacenD = $valR['almacenD'];
                                $codigo = $valR['codigo'];
                                $inventarioIni = $valR['inventarioIni'];
                                $inventarioIniI = $valR['inventarioIniI'];
                                $tipo_traspaso = $valR['tipo_traspaso'];
                                $save = 0;
                                if(($codigo != $codigoAnt) or ($almacen != $almacenAnt) or ($auxExs == 1)){
                                    $save =1;
                                }
                                else{
                                    $save = 0;
                                }
                                $auxExs = 0;
                                if($save == 1){

                                        $arrExs1[] = array(
                                            id               => $id,
                                            //fecha            => $fecha,
                                            //fechahasta       => $hasta,
                                            cantidad         => $cantidad,
                                            almacen          => $almacen,
                                            almacenO         => $almacenO,
                                            almacenD         => $almacenD,
                                            codigo           => $codigo,
                                            inventarioIni    => $inventarioIni,
                                            inventarioIniI   => $inventarioIniI,
                                            tipo_traspaso    => $tipo_traspaso,
                                        ); 
                                }
                                
                                $codigoAnt = $valR['codigo'];
                                $almacenAnt = $valR['almacen'];
                            }
                            //echo "<br><br><br>";
                            $arrExs1F = array_reverse($arrExs1);
                            //echo "EXISTENCIAS <br>";
                            //echo json_encode($arrExs1F); /// este array contiene las existencias de cada produc hasta la fecha asignada
                            /// falta recorrer el arreglo de traspasos origen para restar la existencia si es el caso 
                            //echo "<br>TRASPASOS <br>";
                            //echo json_encode($arrTrasOrigen);

                            //echo "<br>TRASPASOS 2<br>";
                            //echo json_encode($array);


                            ///
                            $conut =1;
                            $conut2 =1;
                            foreach ($inventarioActual2['grid'] as $key => $value) { // Recorre el array principal para traspasos 
                                
                                $almacen = $value['almacen'];
                                $codigo = $value['codigo'];
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

                                    $grupos1[] = array( // SE CREAN UN ARREGLO DE LOS MOVIMINENTOS AGREGANDO UN CONTADOR AGRUPANDO POR ALMACEN Y PRODUCTO
                                                        /// Se almacenan todos los traspasos en un array
                                                        idmov       => $value['id'],
                                                        idalmacen   => $value['almacen'],
                                                        producto    => $value['nombre'],
                                                        idproducnto => $value['codigo'],
                                                        count       => "".$conut."", // cuenta productos iguales del mismo almacen
                                                        count2      => "".$conut2."", // cuenta todos los productos del mismo almacen
                                                    );          

                                $almacenAnt = $value['almacen'];
                                $codigoAnt = $value['codigo'];
                            }
                            //echo json_encode($grupos1); ///MUESTRA TODOS LOS MOVIMIENTOS CON SU CONTADOR
                            $reversed = array_reverse($grupos1); // SE INVIERTE EL ARREGLO PARA TOMAR EL MAX DEL CONTADOR

                            foreach ($reversed as $value) {
                                // SE RECORRE EL ARRAY INVERTIDO
                                $almacen = $value['idalmacen'];
                                $codigo = $value['idproducnto'];
                                $count = $value['count'];

                                if ($count >= $countAnt){
                                                $grupos2[] = array( // SE CREAR UN ARRAY CON LOS MAXIMOS CONTADORES AGRUPADOS
                                                        idmov       => $value['idmov'],
                                                        idalmacen   => $value['idalmacen'],
                                                        producto    => $value['producto'],
                                                        idproducnto => $value['idproducnto'],
                                                        count       => $value['count'],
                                                        count2      => $value['count2'],    
                                                    );
                                }
                                
                                $almacenAnt = $value['idalmacen'];
                                $codigoAnt = $value['idproducnto'];
                                $countAnt = $value['count'];

                            }
                            //echo json_encode($grupos2); ///MUESTRA LOS MAXIMOS AGRUPADOS A LA INVERSA
                            $grupos3 = array_reverse($grupos2); // SE INVIERTE EL ARRAY PARA DEJARLO ORDENADO ASC
                            //echo json_encode($grupos3);

                            function fechaF($fecha){
                                $ano = substr($fecha, 0, 4);
                                $mes = substr($fecha, 5, 2);
                                $dia = substr($fecha, 8, 2);

                                $fechaF = $dia.'/'.$mes.'/'.$ano;
                                return $fechaF;
                            }
      
$auxPrimer = 1; 
$auxPrimerI = 1;                   
 ?>

<div class="container">
<div id="divcantidades">
    <h1>Cantidades</h1>
    <?php 
        $i=0; 
        $j=0; 
        $existenciaTras = 0;
        $existenciaEF = 0;
        $almacenAnt =0;
        $h=0;
        $totalAlmacen = 0;
        $totalEntrada = 0;
        $totalSalida = 0;
        

        foreach ($inventarioActual2['grid'] as $key => $value) { // Recorre el array principal 
                                
                                $almacenDestino = $value['almacen']; // campo creado en la consulta
                                $almacenR = $value['almacen'];
                                $codigoR = $value['codigo'];
                                $almacenNombre = $value['almacenNombre'];
                                $codigoR = $value['codigo'];
                                $tipoTraspaso1 = $value['tipo_traspaso'];
                                $almacenOrigen = $value['idorigen'];
                                $idmovimiento = $value['id'];

                                if($almacenR != $almacenAnt || $codigoR != $codigoAnt){ // SI CAMBIA DE ALMACEN Y DE PRODUCTO
                                    $existencia = 0;
                                    $existenciaI = 0;
                                    $entradasT = 0;
                                    $salidasT = 0;
                                    $movs = 1;
                                    $h=0;
                                    
 
                                }

                                if($almacenR != $almacenAnt){  // CREA LOS RUPOS POR ALAMCEN 

                                    $totalAlmacen = 0;
                                    $totalEntrada = 0;
                                    $totalSalida = 0;
                                    $movs2 = 1;

                                    $idtable =  $almacenR.$codigoR; 

                                    //echo'</table>';
                                    echo "<br>";
                                    echo '<div id="divtable'.$almacenR.'" class="panel-body">
                                            <table class="table table-hover" id="'.$idtable.'">
                                            <h2>Empresa</h2>
                                            <h3>Kardex</h3>
                                            <h4>Tipo: Unidades</h4>
                                            <h4>Almacen: '.$almacenNombre.'</h4>
                                            <h4>Periodo: DEl '.$desde.' AL '.$hasta.'</h4>
                                            <h4>Moneda: MXN</h4>
                                                <thead style="border-top:5px solid black; background:Lightgrey">
                                                    <tr>
                                                        <th width = "10">Fecha</th>
                                                        <th>Folio</th>
                                                        <th>Concepto</th>
                                                        <th>Almacen</th>
                                                        <th>Entradas</th>
                                                        <th>Salidas</th>
                                                        <th>Existencias</th>
                                                    </tr>
                                                </thead>';
                                }

                                if($value['tipo_traspaso']==0){       // salida
                                    $tipoTraspaso = '<span class="label label-warning">Salida</span>';
                                    
                                    $salida = $value['cantidad']*1;
                                    $salidaR = $value['cantidad']*1;
                                    
                                    $entrada = "";
                                    $entradaR = 0;
                                    
                                    $traspaso = "";
                                }elseif($value['tipo_traspaso']==1){  // entrada
                                    $tipoTraspaso = '<span class="label label-success">Entrada</span>';
                                    
                                    $entrada = $value['cantidad']*1;
                                    $entradaR = $value['cantidad']*1;
                                    
                                    $salida = "";
                                    $salidaR = 0;
                                    
                                    $traspaso = "";
                                }else{                                // traspaso
                                    $tipoTraspaso = '<span class="label label-primary">Traspaso</span>';
                                    $traspaso = $value['cantidad']+1;
                                    
                                    $salida = "";
                                    $salidaR = 0;
                                    
                                    $entrada = "";
                                    $entradaR = 0;
                                }

                                ////////INVENTARIO ACTUAL /////////////////////////////////
                                    foreach($arrExs1F as $val) /// busca si exsite relacion en tre traspasos y el almacen (x producto)
                                    {
                                        $almacen = $val['almacen'];
                                        $codigo = $val['codigo'];

                                        if(($almacenR == $almacen) and ($codigoR == $codigo)){
                                            $existenciaEF = $val['inventarioIni'];
                                            break;
                                        }else{
                                            $existenciaEF = 0;
                                            //break;
                                        }
                                    }
                                ////////INVENTARIO ACTUAL FIN/////////////////////////////////

                                ////////TRASPASOS /////////////////////////////////
                                    $traspasosSum = 0;
                                    foreach($array as $val) /// busca si exsite relacion en tre traspasos y el almacen (x producto)
                                    {

                                        $almacenOrigenT = $val['origen'];
                                        $codigoT = $val['idproducnto'];
                                        $cantidadT = $val['cantidad'];
                                        $idmovT = $val['idmov'];


                                        if(($almacenR == $almacenOrigenT) and ($codigoR == $codigoT)){
                                            $traspasosSum = $traspasosSum + $cantidadT;
                                        }
                                        

                                    }
                                    //$existenciaEF = $existenciaEF - $traspasosSum;
                                ////////INVENTARIO ACTUAL FIN/////////////////////////////////

                                /// --- EXISTENCIAS --- ///
                                //ENTRADA
                                
                                $h = $h +1;
                                if($h == 1){
                                    $inventarioInicial = $existenciaEF;
                                }else{
                                    $inventarioInicial = 0;
                                }
                                //echo $h."cont".$existenciaEF."<br><br>";
                                if($tipoTraspaso1 == 1  and $almacenDestino == $almacenR){
                                    $existencia += $value['cantidad'] - $existenciaTras;
                                    $existencia = $existencia + $inventarioInicial;
                                    $entradasT += $value['cantidad'];
                                    $existenciaI = $existenciaI + $entradaT;
                                }//SALIDA
                                if($tipoTraspaso1 == 0  and $almacenDestino  == $almacenR){
                                    $existencia -= $value['cantidad'] - $existenciaTras;
                                    $existencia = $existencia + $inventarioInicial;
                                    $salidasT += $value['cantidad'];
                                    $existenciaI = $existenciaI - $salidaT;
                                }// TRASPASO
                                if($tipoTraspaso1 == 2  and $almacenDestino  == $almacenR){
                                    $existencia += $value['cantidad'] - $existenciaTras;
                                    $existencia = $existencia + $inventarioInicial;
                                    $traspasoI = $value['cantidad'];
                                    $existenciaI = $existenciaI + $traspasoI;
                                }
                                $inventarioInicialF =  $existenciaEF - $traspasosSum;
                                
                                
                                    if($almacenR != $almacenAnt || $codigoR != $codigoAnt){ // CREA UN SEGUNDO ENCABEZADO X CADA PRODUCTO
                                        echo '<thead> 
                                                    <tr>
                                                        <th width = "10">Producto</th>
                                                        <td align="center"><b>'.$value['codigo'].'</b></td>
                                                        <th>'.$value['nombre'].'</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>';
                                        echo '
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Inventario Inicial</b></td>
                                                        <td><b>'.$almacenNombre.'</b></td>
                                                        <td align="center"><b>'.$inventarioInicialF.'</b></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>';
                                     }
                                    
                                    foreach($grupos3 as $valor) /// RECORRE EL ARRAY DE CONDATORES MAXIMOS 
                                    {  
                                        $almacengroup = $valor['idalmacen'];
                                        $idproducnto = $valor['idproducnto'];
                                        $count = $valor['count'];
                                        if($almacengroup == $almacenR and $idproducnto == $codigoR){ // DA VALOR A NUEVO CONTADOR
                                            $count = $valor['count'];
                                            $count1 = $count;
                                            //echo "almacen ".$almacengroup."almacenR".$almacenR."count ".$count1."<br>";
                                        }
                                        if($almacengroup == $almacenR){ // DA VALOR A NUEVO CONTADOR
                                            $count2 = $valor['count2'];
                                            $count12 = $count2;
                                            //echo "almacen ".$almacengroup."almacenR".$almacenR."count ".$count1."<br>";
                                        }
                                    }
                                    
                                    $fecha = $value['fecha'];
                                    $fechaF = fechaF($fecha);

                                    echo '<tr>';
                                    echo '<td>'.$fechaF.'</td>';
                                    echo '<td align="center">'.$value['id'].'</td>';
                                    echo '<td>'.$tipoTraspaso.'</td>';
                                    echo '<td>'.$value['destino'].'</td>';
                                    echo '<td align="center">'.$entrada.'</td>';
                                    echo '<td align="center">'.$salida.'</td>';
                                    echo '<td align="center">'.$existencia.'</td>';
                                    //echo '<td>'.$movs.'</td>';
                                    //echo '<td>'.$entradasT.'</td>';
                                    //echo '<td>'.$salidasT.'</td>';
                                    //echo '<td>'.$count1.'</td>';
                                    echo '</tr>';
                                    
                                    if($movs == $count1){ /// SE COMPARAN LOS CONTADORES PARA INSERTAR PIE DE TABLA
                                        $totalAlmacen += $existencia;
                                        $totalEntrada += $entradasT; 
                                        $totalSalida  += $salidasT;
                                        echo '  
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Total</b></td>
                                                        <td align="center"><b>'.$entradasT.'</b></td>
                                                        <td align="center"><b>'.$salidasT.'</b></td>
                                                        <td align="center"><b>'.$existencia.'</b></td>
                                                    <tr>
                                                ';
                                    }

                                $movs = $movs+1;                                     
                                $count1 ="";

                                $movs2 = $movs2 + $auxPrimer; // aux para el pie
                                if($movs2 == $count12){ /// SE COMPARAN LOS CONTADORES PARA INSERTAR PIE DE TABLA
                                        
                                        echo '
                                                    <tr style="border-bottom:5px solid black; background:Lightgrey">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Total General</b></td>
                                                        <td align="center"><b>'.$totalEntrada.'</b></td>
                                                        <td align="center"><b>'.$totalSalida.'</b></td>
                                                        <td align="center"><b>'.$totalAlmacen.'</b></td>
                                                    <tr>
                                                ';
                                    }
                                $auxPrimer = 0; // auxiliar que imprima el primer total general
                                $movs2 = $movs2+1;                                     
                                $count12 ="";
                                    
                                $almacenAnt =  $value['almacen'];
                                $codigoAnt = $value['codigo'];

                                if($almacenR != $almacenAnt){ 
                                    echo'</table>';
                                    echo '</div>';
                                }


                            }  
     //echo json_encode($grupos3);
     //echo json_encode($reversed);
    //var_dump($almacenArr); 
    //unset($array[0]); // para eliminar la fila del array
    //echo json_encode($array);
    //echo json_encode($arrayComplete); 
                            //echo json_encode($arrayExis1F);

     ?>

        </table>
    </div>
    </div>

<div  id="divimporte">
    <h1>Importe</h1>
    <?php
     
        $i=0; 
        $j=0; 
        $existenciaTras = 0;
        $existenciaEF = 0;
        $almacenAnt =0;
        $h=0;
        $totalAlmacen = 0;
        $totalEntrada = 0;
        $totalSalida = 0;

        foreach ($inventarioActual2['grid'] as $key => $value) { // Recorre el array principal 
                                
                                $almacenDestino = $value['almacen']; // campo creado en la consulta
                                $almacenR = $value['almacen'];
                                $codigoR = $value['codigo'];
                                $almacenNombre = $value['almacenNombre'];
                                $codigoR = $value['codigo'];
                                $tipoTraspaso1 = $value['tipo_traspaso'];
                                $almacenOrigen = $value['idorigen'];
                                $idmovimiento = $value['id'];

                                if($almacenR != $almacenAnt || $codigoR != $codigoAnt){ // SI CAMBIA DE ALMACEN Y DE PRODUCTO
                                    $existencia = 0;
                                    $existenciaI = 0;
                                    $entradasT = 0;
                                    $salidasT = 0;
                                    $movs = 1;
                                    $h=0;
 
                                }

                                if($almacenR != $almacenAnt){  // CREA LOS RUPOS POR ALAMCEN 

                                    $totalAlmacen = 0;
                                    $totalEntrada = 0;
                                    $totalSalida = 0;
                                    $movs2 = 1;

                                    $idtable =  $almacenR.$codigoR; 

                                    //echo'</table>';
                                    echo "<br>";
                                    echo '<div id="divtable'.$almacenR.'" class="panel-body">
                                            <table class="table table-hover" id="'.$idtable.'">
                                            <h2>Empresa</h2>
                                            <h3>Kardex</h3>
                                            <h4>Tipo: Unidades</h4>
                                            <h4>Almacen: '.$almacenNombre.'</h4>
                                            <h4>Periodo: DEl '.$desde.' AL '.$hasta.'</h4>
                                            <h4>Moneda: MXN</h4>
                                                <thead style="border-top:5px solid black; background:Lightgrey">
                                                    <tr>
                                                        <th width = "10">Fecha</th>
                                                        <td align="center"><b>Folio</b></td>
                                                        <th>Concepto</th>
                                                        <th>Almacen</th>
                                                        <th>Entradas</th>
                                                        <th>Salidas</th>
                                                        <th>Existencias</th>
                                                    </tr>
                                                </thead>';
                                }

                                if($value['tipo_traspaso']==0){       // salida
                                    $tipoTraspaso = '<span class="label label-warning">Salida</span>';
                                    
                                    $salida = $value['importe']*1;
                                    $salidaR = $value['importe']*1;
                                    
                                    $entrada = "";
                                    $entradaR = 0;
                                    
                                    $traspaso = "";
                                }elseif($value['tipo_traspaso']==1){  // entrada
                                    $tipoTraspaso = '<span class="label label-success">Entrada</span>';
                                    
                                    $entrada = $value['importe']*1;
                                    $entradaR = $value['importe']*1;
                                    
                                    $salida = "";
                                    $salidaR = 0;
                                    
                                    $traspaso = "";
                                }else{                                // traspaso
                                    $tipoTraspaso = '<span class="label label-primary">Traspaso</span>';
                                    $traspaso = $value['importe']+1;
                                    
                                    $salida = "";
                                    $salidaR = 0;
                                    
                                    $entrada = "";
                                    $entradaR = 0;
                                }

                                ////////INVENTARIO ACTUAL /////////////////////////////////
                                    foreach($arrExs1F as $val) /// busca si exsite relacion en tre traspasos y el almacen (x producto)
                                    {
                                        $almacen = $val['almacen'];
                                        $codigo = $val['codigo'];

                                        if(($almacenR == $almacen) and ($codigoR == $codigo)){
                                            $existenciaEF = $val['inventarioIniI'];
                                            break;
                                        }else{
                                            $existenciaEF = 0;
                                            //break;
                                        }
                                    }
                                ////////INVENTARIO ACTUAL FIN/////////////////////////////////

                                ////////TRASPASOS /////////////////////////////////
                                    
                                    $traspasosSum = 0;
                                    foreach($array as $val) /// busca si exsite relacion en tre traspasos y el almacen (x producto)
                                    {

                                        $almacenOrigenT = $val['origen'];
                                        $codigoT = $val['idproducnto'];
                                        $cantidadT = $val['importe'];
                                        $idmovT = $val['idmov'];


                                        if(($almacenR == $almacenOrigenT) and ($codigoR == $codigoT)){
                                            
                                            $traspasosSum = $traspasosSum + $cantidadT;


                                        }
                                        

                                    }
                                    //$existenciaEF = $existenciaEF - $traspasosSum;
                                ////////INVENTARIO ACTUAL FIN/////////////////////////////////

                                /// --- EXISTENCIAS --- ///
                                //ENTRADA
                                
                                $h = $h +1;
                                if($h == 1){
                                    $inventarioInicial = $existenciaEF;
                                }else{
                                    $inventarioInicial = 0;
                                }
                                //echo $h."cont".$existenciaEF."<br><br>";
                                if($tipoTraspaso1 == 1  and $almacenDestino == $almacenR){
                                    $existencia += $value['importe'] - $existenciaTras;
                                    $existencia = $existencia + $inventarioInicial;
                                    $entradasT += $value['importe'];
                                    $existenciaI = $existenciaI + $entradaT;
                                }//SALIDA
                                if($tipoTraspaso1 == 0  and $almacenDestino  == $almacenR){
                                    $existencia -= $value['importe'] - $existenciaTras;
                                    $existencia = $existencia + $inventarioInicial;
                                    $salidasT += $value['importe'];
                                    $existenciaI = $existenciaI - $salidaT;
                                }// TRASPASO
                                if($tipoTraspaso1 == 2  and $almacenDestino  == $almacenR){
                                    $existencia += $value['importe'] - $existenciaTras;
                                    $existencia = $existencia + $inventarioInicial;
                                    $traspasoI = $value['importe'];
                                    $existenciaI = $existenciaI + $traspasoI;
                                }

                                $entradaF = number_format($entrada,2);
                                $salidaF = number_format($salida,2);
                                $entradasTF = number_format($entradasT,2);
                                $salidasTF = number_format($salidasT,2);
                                $existenciaF = number_format($existencia,2);
                                $inventarioInicialF =  $existenciaEF - $$traspasosSum;

                                $inventarioInicialFF = number_format($inventarioInicialF,2);
                                
                                    if($almacenR != $almacenAnt || $codigoR != $codigoAnt){ // CREA UN SEGUNDO ENCABEZADO X CADA PRODUCTO
                                        echo '<thead> 
                                                    <tr>
                                                        <th width = "10">Producto</th>
                                                        <td align="center"><b>'.$value['codigo'].'</b></td>
                                                        <th>'.$value['nombre'].'</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>';
                                        echo '
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Inventario Inicial</th>
                                                        <th>'.$almacenNombre.'</th>
                                                        <td align="center"><b>'.$inventarioInicialFF.'</b></td>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>';
                                     }
                                    
                                    foreach($grupos3 as $valor) /// RECORRE EL ARRAY DE CONDATORES MAXIMOS 
                                    {  
                                        $almacengroup = $valor['idalmacen'];
                                        $idproducnto = $valor['idproducnto'];
                                        $count = $valor['count'];
                                        if($almacengroup == $almacenR and $idproducnto == $codigoR){ // DA VALOR A NUEVO CONTADOR
                                            $count = $valor['count'];
                                            $count1 = $count;
                                            //echo "almacen ".$almacengroup."almacenR".$almacenR."count ".$count1."<br>";
                                        }
                                        if($almacengroup == $almacenR){ // DA VALOR A NUEVO CONTADOR
                                            $count2 = $valor['count2'];
                                            $count12 = $count2;
                                            //echo "almacen ".$almacengroup."almacenR".$almacenR."count ".$count1."<br>";
                                        }
                                    }

                                    $fecha = $value['fecha'];
                                    $fechaF = fechaF($fecha); 

                                    echo '<tr>';
                                    echo '<td>'.$fechaF.'</td>';
                                    echo '<td align="center">'.$value['id'].'</td>';
                                    echo '<td>'.$tipoTraspaso.'</td>';
                                    echo '<td>'.$value['destino'].'</td>';
                                    echo '<td align="center">'.$entradaF.'</td>';
                                    echo '<td align="center">'.$salidaF.'</td>';
                                    echo '<td align="center">'.$existenciaF.'</td>';
                                    //echo '<td>'.$movs.'</td>';
                                    //echo '<td>'.$entradasT.'</td>';
                                    //echo '<td>'.$salidasT.'</td>';
                                    //echo '<td>'.$count1.'</td>';
                                    echo '</tr>';
                                    
                                    if($movs == $count1){ /// SE COMPARAN LOS CONTADORES PARA INSERTAR PIE DE TABLA
                                        $totalAlmacen += $existencia;
                                        $totalEntrada += $entradasT; 
                                        $totalSalida  += $salidasT;

                                        $totalAlmacenF = number_format($totalAlmacen,2);
                                        $totalEntradaF = number_format($totalEntrada,2);
                                        $totalSalidaF = number_format($totalSalida,2);
                                        echo '
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>Total</td>
                                                        <td align="center"><b>'.$entradasTF.'</b></td>
                                                        <td align="center"><b>'.$salidasTF.'</b></td>
                                                        <td align="center"><b>'.$existenciaF.'</b></td>
                                                    <tr>
                                                ';
                                    }

                                   
                                $movs = $movs+1;                                     
                                $count1 ="";

                                $movs2 = $movs2 + $auxPrimerI; // para el primer pie
                                if($movs2 == $count12){ /// SE COMPARAN LOS CONTADORES PARA INSERTAR PIE DE TABLA
                                        echo '
                                                    <tr style="border-bottom:5px solid black; background:Lightgrey">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Total General</b></td>
                                                        <td align="center"><b>'.$totalEntradaF.'</b></td>
                                                        <td align="center"><b>'.$totalSalidaF.'</b></td>
                                                        <td align="center"><b>'.$totalAlmacenF.'</b></td>
                                                    <tr>
                                                ';
                                    }
                                $auxPrimerI = 0;
                                $movs2 = $movs2+1;                                     
                                $count12 ="";
                                    
                                $almacenAnt =  $value['almacen'];
                                $codigoAnt = $value['codigo'];

                                if($almacenR != $almacenAnt){ 
                                    echo'</table>';
                                    echo '</div>';
                                }



                            }  
     //echo json_encode($grupos3);
     //echo json_encode($reversed);
    //var_dump($almacenArr); 
    //unset($array[0]); // para eliminar la fila del array
    //echo json_encode($array);
    //echo json_encode($arrayComplete); 
                            //echo json_encode($arrayExis1F);

     ?>

        </table>
    </div>
</div>         
</body>
</html>
<script>
   $(document).ready(function() {

        $("#divcantidades").hide();
        $("#divimporte").hide();

        reporte = $("#reporte").val();
        if ("unidades" == reporte){
                $("#divimporte").hide();
                $("#divfiltro").hide();
                $("#divcantidades").show();
            }
        if ("importe" == reporte){
                $("#divfiltro").hide();
                $("#divcantidades").hide();
                $("#divimporte").show();
            }
        if ("ambos" == reporte){
                $("#divfiltro").hide();
                $("#divcantidades").show();
                $("#divimporte").show();
            }

        
        //$('#tableKardex').DataTable();
        $('#producto, #departamento, #familia, #linea, #caracteristicas, #sucursal, #almacen, #unidad, #selunidad').select2();
        $('#desde').datepicker({
            format: "yyyy-mm-dd",
        });
        $('#hasta').datepicker({
            format: "yyyy-mm-dd",
        });
 
        $("#divfamilia").hide();
        $("#divlinea").hide();
        $("#divcaract").hide();

        //$("#divtable").hide();

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