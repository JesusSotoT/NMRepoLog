<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class InventarioModel extends Connection
{
    public function indexGrid()
    {   

        $query1 = "SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia,
                (Select aa.nombre from app_almacenes aa where aa.codigo_sistema=m.id_almacen_origen) as origen,
                (Select aa.nombre from app_almacenes aa where aa.codigo_sistema=m.id_almacen_destino) as destino 
                from app_inventario_movimientos m, app_productos p, accelog_usuarios u 
                where  m.id_producto = p.id and m.id_empleado = u.idempleado ";
        $result1 = $this->queryArray($query1);

        
        $query2 = "SELECT * from app_productos where status = 1";
        $result2 = $this->queryArray($query2);

        foreach ($result2['rows'] as $key => $value) {
            //echo $value['id'].'<br>';
            $imp = $this->calImpu($value['id'],$value['precio'],$value['formulaIeps']);
            //echo $value['nombre'].'='.$imp.'<br> ';
            $result2['rows'][$key]['precio']= $imp;
        }

        $query3 = "SELECT * from app_almacenes where activo = 1";
        $result3 = $this->queryArray($query3);

        //return $result['rows'];
        return array('grid' => $result1['rows'] , 'productos' => $result2['rows'], 'almacenes' => $result3['rows']);
                               

    }
    public function calImpu($idProducto,$precio,$formula){
                
                if($formula==2){
                    $ordenform = 'ASC';
                }else{
                    $ordenform = 'DESC';
                }
                $subtotal = $precio;

                $queryImpuestos = "select p.id,p.precio, i.valor, i.clave,pi.formula,i.nombre";
                $queryImpuestos .= " from app_impuesto i, app_productos p ";
                $queryImpuestos .= " left join app_producto_impuesto pi on p.id=pi.id_producto ";
                $queryImpuestos .= " where p.id=" . $idProducto . " and i.id=pi.id_impuesto ";
                $queryImpuestos .= " Order by pi.id_impuesto ".$ordenform;
                //echo $queryImpuestos.'<br>';
                $resImpues = $this->queryArray($queryImpuestos);

                foreach ($resImpues['rows'] as $key => $valueImpuestos) {
                    if($valueImpuestos["clave"] == 'IEPS'){
                        $producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
                    }else{
                        if($ieps!=0){
                            $producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
                        }else{
                            //echo '/'.$subtotal.'-X'.$valueImpuestos["valor"].'X/';
                            $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                            //echo '('.$producto_impuesto.')<br>';
                        }
                    }
                } 
                //echo $producto_impuesto.'<br>';
                $precioNeto = $subtotal + $producto_impuesto;

                return $precioNeto;

    }


    public function kardex($idProducto,$almacen,$desde,$hasta,$tipo){
        
        $filtro = '';
        if($idProducto!=0){
            $filtro .=' and m.id_producto='.$idProducto;
        }
        if($desde!='' && $hasta!=''){
            $filtro .=' and m.fecha BETWEEN "'.$desde.'" and "'.$hasta.'" ';
        }
        if($tipo!=''){
            $filtro .=' and m.tipo_traspaso='.$tipo;
        }

        $query1 = "SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia,
                (Select aa.nombre from app_almacenes aa where aa.codigo_sistema=m.id_almacen_origen) as origen,
                (Select aa.nombre from app_almacenes aa where aa.codigo_sistema=m.id_almacen_destino) as destino 
                from app_inventario_movimientos m, app_productos p, accelog_usuarios u 
                where  m.id_producto = p.id and m.id_empleado = u.idempleado ".$filtro;
        $result1 = $this->queryArray($query1);
        
        return $result1['rows'];

    }
    public function inventarioActual($idProducto,$idalmacen,$desde,$hasta,$R1,$iddep,$idfa,$idli){
       /* $idProducto = '';
        $idalmacen = '';
        $desde = '';
        $hasta = '';
        $R1 = '';
        $iddep = '';
        $idfa = '';
        $idli = ''; */

        $primera = $this->existenciasGrid($idProducto,$idalmacen,$desde,$hasta,$R1,$iddep,$idfa,$idli);
        //print_r($primera['grid']);
          foreach ($primera['grid'] as $key => $val) { // Recorre el array principal para traspasos 
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
                                $idProd             = $val['idProd'];
                
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
                                            idProd           => $idProd,

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
                                            idProd           => $idProd,
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
                                            idProd           => $idProd,
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
                                            idProd           => $idProd,
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
                                $idProd             = $value['idProd'];

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
                                            idProd              => $idProd,
                                        ); 
                                    
                                $almacenAnt            = $value['almacen'];
                                $codigoAnt             = $value['codigo'];                           
                            }
                            //echo json_encode($arrExs);
                            $arrExsR = array_reverse($arrExs);
                            //print_r($arrExsR);
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
                                                        idProd              => $value['idProd'],

                                                    );
                                }
                                
                                $almacenAnt = $value['almacen'];
                                $codigoAnt = $value['codigo'];
                                $countAnt = $value['count'];

                            }
                            $arrExsR1 = array_reverse($arrExsR2);
                            //print_r($arrExsR1);

                            /////////////////////////////////////////////////////////////////////////////////////////////////////////
                            return array('invent' => $arrExsR1);

   
    }
    public function productosEtiquetado(){
        $sql = "SELECT  p.id 
                FROM    app_productos p
                INNER JOIN app_producto_caracteristicas pc ON p.id = pc.id_producto
                WHERE   status=1
                ORDER BY p.id limit 50;";
        $result0 = $this->queryArray($sql);

        $query = "SELECT id,codigo,nombre,precio,descripcion_corta from app_productos where status=1 limit 50";
        $result1 = $this->queryArray($query);

        foreach ($result1['rows'] as $key => $value) {
            $imp = $this->calImpu($value['id'],$value['precio'],$value['formulaIeps']);
            $result1['rows'][$key]['precio']= $imp;
        }
    return  array( 'caracteristicas' => $result0['rows'] ,'productos' => $result1['rows']);

    }
    public function productos(){


        $sql = "SELECT  p.id 
                FROM    app_productos p
                INNER JOIN app_producto_caracteristicas pc ON p.id = pc.id_producto
                WHERE   status=1
                ORDER BY p.id;";
        $result0 = $this->queryArray($sql);

        $query = "SELECT * from app_productos where status=1";
        $result1 = $this->queryArray($query);

        foreach ($result1['rows'] as $key => $value) {
            //echo $value['id'].'<br>';
            $imp = $this->calImpu($value['id'],$value['precio'],$value['formulaIeps']);
            //echo $value['nombre'].'='.$imp.'<br> ';
            $result1['rows'][$key]['precio']= $imp;
        }

        $query2 = 'SELECT * FROM app_almacenes where id_almacen_tipo=1';
        $almacenes = $this->queryArray($query2);

        $query3 = 'SELECT * from accelog_usuarios';
        $result3 = $this->queryArray($query3);

        return  array( 'caracteristicas' => $result0['rows'] ,'productos' => $result1['rows'] ,'almacenes' => $almacenes['rows'], 'usuarios' => $result3['rows']);
    }

    public function productos2($filtrado, $sucursal, $departamento, $familia, $linea){


        $sql = "SELECT p.id ID_P, h.id ID_H, p.nombre CRARACTERISTICA_PADRE, h.nombre CARACTERISTICA_HIJA
                FROM    app_caracteristicas_padre p
                INNER JOIN  app_caracteristicas_hija h ON p.id = h.id_caracteristica_padre
                ORDER BY ID_P, ID_H;";
        $result0 = $this->queryArray($sql);

        $filtro = ( $filtrado == 1 ? " AND s.id_sucursal LIKE '%$sucursal%' AND p.departamento LIKE '%$departamento%' AND p.familia LIKE '%$familia%' AND p.linea LIKE '%$linea%'" : "");

        /*$query = "SELECT  i.id_almacen ALMACEN, s.idSuc ID_SUCURSAL, s.nombre SUCURSAL, p.id ID,  p.codigo CODIGO, p.nombre NOMBRE, p.precio PRECIO_GENERAL, ps.precio PRECIO_SUCURSAL, p.descripcion_corta DESC_CORTA, p.descripcion_larga DESC_LARGA, i.caracteristicas CARACTERISTICAS, i.cantidad, p.formulaIeps
                FROM    app_inventario i
                INNER JOIN app_productos p ON i.id_producto = p.id
                RIGHT JOIN mrp_sucursal s ON i.id_almacen = s.idAlmacen
                LEFT JOIN app_precio_sucursal ps ON s.idSuc = ps.sucursal AND p.id = ps.producto
                WHERE p.status='1' $filtro
                ORDER BY i.id_almacen, s.idSuc, p.id;";*/
        $query = "SELECT  i.id_almacen ALMACEN, s.id_sucursal ID_SUCURSAL, suc.nombre SUCURSAL, p.id ID,  p.codigo CODIGO, p.nombre NOMBRE, p.precio PRECIO_GENERAL, ps.precio PRECIO_SUCURSAL, p.descripcion_corta DESC_CORTA, p.descripcion_larga DESC_LARGA, i.caracteristicas CARACTERISTICAS, i.cantidad, p.formulaIeps
                FROM    app_inventario i
                INNER JOIN app_productos p ON i.id_producto = p.id
                RIGHT JOIN app_almacenes s ON i.id_almacen = s.id
                LEFT JOIN app_precio_sucursal ps ON s.id_sucursal = ps.sucursal AND p.id = ps.producto
                LEFT JOIN mrp_sucursal suc ON s.id_sucursal=suc.idSuc
                WHERE p.status='1' $filtro
                ORDER BY i.id_almacen, s.id_sucursal, p.id;";

        $result1 = $this->queryArray($query);

        foreach ($result1['rows'] as $key => $value) {
            $imp = $this->calImpu($value['ID' ], ( $value['PRECIO_SUCURSAL'] != NULL ? $value['PRECIO_SUCURSAL'] : $value['PRECIO_GENERAL'] ) , $value['formulaIeps']);
            $result1['rows'][$key]['precio']= $imp;
        }

        return  array( 'caracteristicas' => $result0['rows'] ,'productos' => $result1['rows'] );
    }
    public function buscarSucursales($patron) {
        $sql = "SELECT  idSuc id, nombre as text
                FROM    mrp_sucursal
                WHERE   nombre LIKE '%$patron%' ";

        $res = $this->queryArray($sql);
        return $res;
    }
    
    public function detalleMerma($idMerma){
        $selMerm = "SELECT mp.id_producto,pr.nombre as proName,mp.caracteristicas,mp.cantidad,mp.precio,u.usuario,a.nombre as almacen, mp.observaciones
                    from app_merma_datos mp
                    inner join app_productos pr on pr.id=mp.id_producto
                    inner join app_almacenes a on a.id=mp.almacen
                    inner join accelog_usuarios u on u.idempleado=mp.usuario
                    where mp.id_merma =".$idMerma;
		// return $selMerm;
        $resMerm = $this->queryArray($selMerm);

        foreach ($resMerm['rows'] as $key => $value) {
                        $carNom = $this->cartProducto($value['caracteristicas']);
                        $resMerm['rows'][$key]['proName'] =  $resMerm['rows'][$key]['proName'].$carNom;
                    }  
        $select = 'SELECT * from app_merma where id='.$idMerma;
        $resGene = $this->queryArray($select);            

        //return $resMerm['rows']; 
        return array('productos' =>  $resMerm['rows'], 'total' =>$resGene['rows'][0]['importe']);  
                 
    }
    public function agregaMerma($producto,$cantidad,$almacen,$precio,$usuario,$carac){
        $select1 = "SELECT p.id,p.nombre from app_productos p where id=".$producto;
        $result1 = $this->queryArray($select1);

        $select2 = "SELECT usuario from accelog_usuarios where idempleado=".$usuario;
        $result2 = $this->queryArray($select2);

        $select3 = "SELECT nombre, id from app_almacenes where id=".$almacen;
        $result4 = $this->queryArray($select3);

        $caracte = $this->cartProducto($carac);

        return array('producto' => $result1['rows'][0]['nombre'].' '.$caracte , 'usuario' => $result2['rows'][0]['usuario'], 'almacen' => $result4['rows'][0]['nombre']);
    }

    public function cartProducto($caracteristicas){
            if($caracteristicas!=''){
                $caracteristicas2 =  explode("*", $caracteristicas);
                foreach ($caracteristicas2 as $key => $value) {
                    $expv=explode('=>', $value);
                    $ip=$expv[0];
                    $ih=$expv[1];
                    $my = "SELECT concat('( ',a.nombre,': ',b.nombre,' )') as dcar FROM app_caracteristicas_padre a
                    LEFT JOIN app_caracteristicas_hija b on b.id=".$ih."
                    WHERE a.id=".$ip.";";
                    $producto = $this->queryArray($my);
                    $caras.= $producto['rows'][0]['dcar'];
                }
                return $caras;
            }else{
                return '';
            } 

    }

    public function guardaMerma($productos){
        $tipoTraspaso = 0;
        $contadorProductos = 0;
        $total = 0;

        date_default_timezone_set("Mexico/General");
        $fechaactual = date('Y-m-d H:i:s');

        $insert1 = "INSERT into app_merma(fecha,usuario,productos,importe) values ('".$fechaactual."','".$_SESSION['accelog_idempleado']."','0','0')";
        $result1 = $this->queryArray($insert1);
        $idMerma = $result1['insertId'];

        $token =explode("/", $productos);
        foreach ($token as $key => $value) {
            $token2 = explode('-',$value);
            if($token2[1]!='undefined' && $token2[2]!=''){


                /////Replace Caracteristicas
                // if($token2[6]!=''){
                //     $caracteristica = preg_replace('/\*/', ',', $token2[6]);
                //     $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                //     $caracteristicareplace=addslashes($caracteristicareplace);
                //     $caracteristicareplace = trim($caracteristicareplace, ',');
                // }else{
                //     $caracteristicareplace = "'0'";   
                // }
                
                $caracteristicareplace = preg_replace([ '/\*/', '/(\d+)/' ], [ ',', "\\'\${1}\\'"], $token2[6]);
               

                $queryInserProd = "INSERT into app_merma_datos (id_merma,id_producto,cantidad,precio,usuario,almacen,observaciones,caracteristicas) ";
                $queryInserProd .="values ('".$idMerma."','".$token2[0]."','".$token2[1]."','".$token2[2]."','".$token2[3]."','".$token2[4]."','".$token2[5]."','".$token2[6]."')";
                //echo $queryInserProd;
                $insertaproducto = $this->queryArray($queryInserProd);

                $importe = $token2[1] * $token2[2];
                $total +=$importe;



                $insertMovi = "INSERT into app_inventario_movimientos 
                					(id_producto, id_producto_caracteristica, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha,id_empleado,
                						tipo_traspaso,costo,referencia) 
                				VALUES ('".$token2[0]."', '".$caracteristicareplace."', '".$token2[1]."', '".$importe."', '".$token2[4]."', '',
                						'".$fechaactual."', '".$_SESSION['accelog_idempleado']."', '".$tipoTraspaso."', '".$token2[2]."', 
                						'Salida Merma ".$idMerma."')";
 //var_dump($insertMovi);
                //echo $insertMovi;
                // return $insertMovi;
                $resInsert = $this->queryArray($insertMovi); 
                $contadorProductos ++; 
            }
        }
        $updateCon = "UPDATE app_merma set productos=".$contadorProductos.", importe=".$total." where id=".$idMerma;
        $resUpdate = $this->queryArray($updateCon);

        return array('estatus' => true , 'idMerma' => $idMerma);
        
    }
    public function mermasList(){
        $select = "SELECT m.id,m.fecha,u.usuario,m.productos,m.importe from app_merma m, accelog_usuarios u where m.usuario=u.idempleado";
        $result = $this->queryArray($select);

        return $result['rows'];
    }

    public function existenciasGrid($idProducto,$idalmacen,$desde,$hasta,$R1,$iddep,$idfa,$idli){
       /* $idProducto = '';
        $idalmacen = '';
        $desde = '';
        $hasta = '';
        $R1 = '';
        $iddep = '';
        $idfa = '';
        $idli = ''; */

        $filtro = '1 = 1 and p.status = 1';
        
        if($idProducto!=""){
            if($idProducto=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.codigo="'.$idProducto.'")';
            }
            
        }
        if($iddep!=""){
            if($iddep=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.departamento="'.$iddep.'")';
            }
            
        }
        if($idfa!=""){
            if($idfa=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.familia="'.$idfa.'")';
            }
            
        }
        if($idli!=""){
            if($idli=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.linea="'.$idli.'")';
            }
            
        }
        if($idalmacen!=""){
            if($idalmacen =='0'){
                $filtro .='';
            }else{
                $filtro .=' and if (dd.codigo_sistema is null, oo.codigo_sistema, dd.codigo_sistema) LIKE "'.$idalmacen.'%"';
            }
            
        }
        if($hasta!=''){
            $filtro .=' and m.fecha <= "'.$hasta.'" ';
        }
        
        // obtine los movimientos segun el filtro(condicion)
        $query1 = "SELECT m.id, p.nombre, p.codigo,m.id_producto as idProd, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, if(dd.id is null, oo.id, dd.id) as almacen, if(dd.nombre is null, oo.nombre, dd.nombre) as almacenNombre, cc.codigo moneda, un.nombre unidad
                    from app_inventario_movimientos m
                    left join app_almacenes oo on oo.id = m.id_almacen_origen
                    left join app_almacenes dd on dd.id = m.id_almacen_destino
                    left join app_productos p on p.id = m.id_producto
                    left join accelog_usuarios u on u.idempleado = m.id_empleado
                    left join app_unidades_medida un on un.id = p.id_unidad_venta
                    left join cont_coin cc on cc.coin_id = p.id_moneda
                    where ".$filtro."
                    order by almacen asc, p.codigo, m.fecha;";

        $result1 = $this->queryArray($query1);


        return array('grid' => $result1['rows']);
                               
    }
    public function movsProducto($idProducto){

         $query1 = "SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia,
                (Select aa.nombre from app_almacenes aa where aa.codigo_sistema=m.id_almacen_origen) as origen,
                (Select aa.nombre from app_almacenes aa where aa.codigo_sistema=m.id_almacen_destino) as destino 
                from app_inventario_movimientos m, app_productos p, accelog_usuarios u 
                where  m.id_producto = p.id and m.id_empleado = u.idempleado and m.id_producto=".$idProducto;
        $result1 = $this->queryArray($query1);

        return array('movimientos' => $result1['rows'], 'producto' => $result1['rows'][0]['nombre']);
    }
    public function ajustarInve($idProducto,$idAlmacen,$tipoMovi,$cantidad,$costo,$obser){

        date_default_timezone_set("Mexico/General");
        $fechaactual = date('Y-m-d H:i:s');
        if($tipoMovi==0){
            $insertMovi = "INSERT into app_inventario_movimientos (id_producto,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia) values ('".$idProducto."','".$cantidad."','".$costo."','".$idAlmacen."','','".$fechaactual."','".$_SESSION['accelog_idempleado']."','".$tipoMovi."','".($costo*$cantidad)."','".$obser."')";
        }else{
            $insertMovi = "INSERT into app_inventario_movimientos (id_producto,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia) values ('".$idProducto."','".$cantidad."','".$costo."','','".$idAlmacen."','".$fechaactual."','".$_SESSION['accelog_idempleado']."','".$tipoMovi."','".($costo*$cantidad)."','".$obser."')";
        }
        
                //echo $insertMovi;
        $resInsert = $this->queryArray($insertMovi);

        $idMov = $resInsert['insertId'];

        if(is_numeric($idMov)){
            return array('estatus' => true);
        }else{
            return array('estatus' => false);
        }

    }

    public function obtenCaracteristicas($idProducto){

            $tieneAlgo = 0;
            $que = "SELECT id,ruta_imagen, nombre  from app_productos where id='".$idProducto."'";
            $res = $this->queryArray($que);
            $imagen = $res['rows'][0]['ruta_imagen'];
            $nombreP = $res['rows'][0]['nombre'];

            if($imagen==''){
                $imagen='noimage.jpeg';
            }
            ///Caracteristicas
            $myQuery = "SELECT e.id as idcp, e.nombre as nombrecp
            FROM  app_producto_caracteristicas d
            LEFT JOIN app_caracteristicas_padre e on e.id=d.id_caracteristica_padre
            WHERE d.id_producto='".$res['rows'][0]['id']."' order by idcp;";
            $producto = $this->queryArray($myQuery);
            
            if($producto['total'] > 0){
                foreach ($producto['rows'] as $key => $value) {
                    $selec = "SELECT id_caracteristica_padre,id,nombre from app_caracteristicas_hija where activo=1 and id_caracteristica_padre=".$value['idcp'];
                    $result = $this->queryArray($selec);

                    $carac[$value['nombrecp']] = $result['rows'];
                }
                $tieneAlgo++;
            }



            //lotes
            $arrPedis=array();
             $myQuery = "SELECT a.id,a.no_lote from app_producto_lotes a
                inner join app_inventario_movimientos b on b.id_lote=a.id
                WHERE b.id_producto='".$res['rows'][0]['id']."' group by a.id;";

            $pedimentos = $this->queryArray($myQuery);
            if($pedimentos['total']>0){
                foreach ($pedimentos['rows'] as $k => $v) {
     

                    $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
    @e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
     = ".$res['rows'][0]['id']." ".$carac." AND id_pedimento = 0 AND id_lote = ".$v['id']."  ) AS entradas,
    @s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
     = ".$res['rows'][0]['id']." ".$carac." AND id_pedimento = 0 AND id_lote = ".$v['id']."  ) AS salidas,
    (IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
    FROM app_almacenes a WHERE a.activo = 1
    ORDER BY a.codigo_sistema;";

                    $totpedis = $this->queryArray($myQuery2);
                    $cant=0;
                    foreach ($totpedis['rows'] as $k2 => $v2) {
                        //$cant+=$v2['cantidad'];

                        if($v2['cantidad']>0){
                            $arrPedis[]=array('idLote'=>$v['id'].'-'.$v2['id'].'-'.$v2['cantidad'].'-#*-'.$v['no_lote'].' ('.$v2['nombre'].')', 'cantidad'=>$v2['cantidad'], 'numero'=>'Lote: '.$v['no_lote'].' - '.$v2['nombre']);
                        }
                    }

                    
                }
                $tieneAlgo++;
            }
            

            
            //return $arrPedis;

                //print_r($arrPedis);
                //exit();



            $idtipocosto = $this->tipoCosteoProd($idProducto);
            if($idtipocosto==1){
                $elunit = $this->costeoProd($idProducto);
            }else if($idtipocosto==6){
                $elunit = $this->costeoProd($idProducto);
            }else{
                $elunit = $this->costeoProd($idProducto);
            }
            $elcost = ($elunit*1);

            return array('tieneCar' => $tieneAlgo, 'cararc' => $carac, 'lotes'=> $arrPedis, 'series'=> $series, 'imagen'=> $imagen, 'nombreProd'=> $nombreP, 'costo' => $elcost);
    }

    public function tipoCosteoProd($idProd){
            //Query para obtener el numero de requisicion nuevo (ultimo id + 1)
            $myQuery = "SELECT id_tipo_costeo from app_productos where id='$idProd';";
            //echo $myQuery.'<br>';
            $nreq = $this->queryArray($myQuery);
            $tc = $nreq['rows'][0]['id_tipo_costeo'];
            return $tc;
        }
        public function costeoProd($idprod){
            /*$myQuery = "SELECT id, SUM(costo*cantidad) AS t, SUM(cantidad) AS c 
                        FROM  app_inventario_movimientos 
                        WHERE id_producto = $idprod AND tipo_traspaso = 1 AND estatus = 1 AND costo != 0";
                        //echo $myQuery.'<br>';
            $res = $this->query($myQuery);
            $res = $res->fetch_assoc();
            return floatval($res['t']) / floatval($res['c']);*/

            /*$sql = "SELECT id, SUM(costo*cantidad * IF(tipo_traspaso=1,1,-1) ) AS t, SUM(cantidad * IF(tipo_traspaso=1,1,-1)) AS c , SUM(costo*cantidad * IF(tipo_traspaso=1,1,-1) ) / SUM(cantidad * IF(tipo_traspaso=1,1,-1)) costo_promedio
                        FROM  app_inventario_movimientos  
                        WHERE id_producto = '1' AND estatus = 1 AND costo != 0 ;";*/
            
            /*$sql ="SELECT id, sum(costo*cantidad * IF(id_almacen_destino=0 OR referencia NOT LIKE '%Recepcion%',-1,1) ) AS t, sum(cantidad * IF(id_almacen_destino=0 OR referencia NOT LIKE '%Recepcion%',-1,1)) AS c , sum(costo*cantidad * IF(id_almacen_destino=0 OR referencia NOT LIKE '%Recepcion%',-1,1) ) / sum(cantidad * IF(id_almacen_destino=0 OR referencia NOT LIKE '%Recepcion%',-1,1)) costo_promedio
FROM  app_inventario_movimientos  
WHERE id_producto = '$idprod' AND estatus = 1 AND costo != 0;";*/
            
            /*$sql ="SELECT id, IF(tipo_traspaso=1 OR referencia like '%Recepcion Movto:%',1,-1), sum(costo*cantidad * IF(tipo_traspaso=1 OR referencia like '%Recepcion Movto:%',1,-1) ) AS t, sum(cantidad * IF(tipo_traspaso=1 OR referencia like '%Recepcion Movto:%',1,-1) ) AS c , sum(costo*cantidad * IF(tipo_traspaso=1 OR referencia like '%Recepcion Movto:%',1,-1) ) / sum(cantidad * IF(tipo_traspaso=1 OR referencia like '%Recepcion Movto:%',1,-1) ) costo_promedio
                    FROM  app_inventario_movimientos  
                    WHERE id_producto = '$idprod' AND estatus = 1 AND costo != 0;";*/
            $sql = "SELECT  (sum(valor) / sum(cantidad) ) costo_promedio
                    FROM    app_inventario
                    WHERE   id_producto='1';";
            $res = $this->queryArray($sql);
            return $res['rows'][0][costo_promedio];
        } 
        public function costeoUltimoCosto($idProducto){
            /*Ultimo Costo*/
            $sql = "SELECT costo
                    FROM    app_inventario_movimientos
                    WHERE   id_producto = '$idProducto' AND referencia LIKE '%Orden de compra / recepcion%'
                    ORDER BY id DESC
                    LIMIT   1;";
            $res = $this->queryArray($sql);
            return $res['rows'][0][costo];
        }
        public function costeoPEPS($idProducto){
            /*PEPS*/
            /*$sql = "SELECT    SUM(cantidad) cantidad
                    FROM    app_inventario_movimientos
                    WHERE   id_producto = '1' AND (referencia LIKE '%Venta%')
                    ORDER BY id ASC;";*/
            $sql = "SELECT  SUM(cantidad) cantidad
                    FROM    app_inventario_movimientos
                    WHERE   id_producto = '1' AND tipo_traspaso=0
                    ORDER BY id ASC;";
            $resSalidas = $this->queryArray($sql);

            /*$sql = "SELECT    cantidad, costo
            FROM    app_inventario_movimientos
            WHERE   id_producto = '1' AND (referencia LIKE '%Orden de compra%' OR referencia LIKE 'Recepcion Movto' OR referencia LIKE '%Cancelacion%' OR referencia LIKE '%Devolución%')
            ORDER BY id ASC;";*/
            $sql = "SELECT  cantidad, costo
                    FROM    app_inventario_movimientos
                    WHERE   id_producto = '1' AND (tipo_traspaso=1 OR referencia like '%Recepcion Movto:%')
                    ORDER BY id ASC;";
            $resEntradas = $this->queryArray($sql);

            $sumatoriaCantidadEntradas = 0;
            foreach ($resEntradas['rows'] as $key => $value) {
                $sumatoriaCantidadEntradas += $value['cantidad'];
                if ($sumatoriaCantidadEntradas > $resSalidas['rows'][0]['cantidad']){
                    $costo = $value['costo'];
                    break;
                }
            }
            return $costo;
        }
        public function costeoUEPS($idProducto){
            /*UEPS*/
            /*$sql = "SELECT    SUM(cantidad) cantidad
                    FROM    app_inventario_movimientos
                    WHERE   id_producto = '1' AND (referencia LIKE '%Venta%')
                    ORDER BY id DESC;";*/
            $sql = "SELECT  SUM(cantidad) cantidad
                    FROM    app_inventario_movimientos
                    WHERE   id_producto = '1' AND tipo_traspaso=0
                    ORDER BY id DESC;";
            $resSalidas = $this->queryArray($sql);

            /*$sql = "SELECT    cantidad, costo
            FROM    app_inventario_movimientos
            WHERE   id_producto = '1' AND (referencia LIKE '%Orden de compra%' OR referencia LIKE 'Recepcion Movto' OR referencia LIKE '%Cancelacion%' OR referencia LIKE '%Devolución%')
            ORDER BY id DESC;";*/
            $sql = "SELECT  cantidad, costo
                    FROM    app_inventario_movimientos
                    WHERE   id_producto = '1' AND (tipo_traspaso=1 OR referencia like '%Recepcion Movto:%')
                    ORDER BY id DESC;";
            $resEntradas = $this->queryArray($sql);

            $sumatoriaCantidadEntradas = 0;
            foreach ($resEntradas['rows'] as $key => $value) {
                $sumatoriaCantidadEntradas += $value['cantidad'];
                if ($sumatoriaCantidadEntradas > $resSalidas['rows'][0]['cantidad']){
                    $costo = $value['costo'];
                    break;
                }
            }
            return $costo;
        }

        

        function getCaracteristicas()
        {
            $sql = "SELECT p.id ID_P, h.id ID_H, p.nombre CRARACTERISTICA_PADRE, h.nombre CARACTERISTICA_HIJA
                FROM    app_caracteristicas_padre p
                INNER JOIN  app_caracteristicas_hija h ON p.id = h.id_caracteristica_padre
                ORDER BY ID_P, ID_H;";
            $result = $this->queryArray($sql);

            return $result['rows'];
        }

        function valorInventario($almacen, $proveedor, $productos)
        {
            $sql = "SELECT  i.id_producto, p.codigo codigo, caracteristicas id_producto_caracteristica, lote lote, l.no_lote no_lote, p.series series,  p.nombre producto, valor t, cantidad c, IF(valor/cantidad, valor/cantidad, 0) costo_promedio
                    FROM    app_inventario i
                    LEFT JOIN app_productos p ON i.id_producto = p.id
                    LEFT JOIN app_producto_proveedor pp ON i.id_producto = pp.id_producto
                    LEFT JOIN app_producto_lotes l ON i.lote = l.id
                    WHERE id_almacen = $almacen AND pp.id_proveedor ". (($proveedor == "") ? "LIKE '%'" : "=$proveedor") . ( ( $productos == "(  )" || strpos( $productos, '-1')  )  ? "" : " AND p.id IN $productos" )."
                    GROUP BY id_producto, id_producto_caracteristica, lote;";
            //echo $sql; die;
            $inventario = $this->queryArray($sql);
            return $inventario['rows'];
        }
        function valorInventarioGrl($proveedor, $productos)
        {
            $sql = "SELECT  i.id_producto, p.codigo codigo, caracteristicas id_producto_caracteristica, lote lote, l.no_lote no_lote, p.series series,  p.nombre producto, sum(valor) t, sum(cantidad) c, IF(sum(valor)/sum(cantidad), sum(valor)/sum(cantidad), 0) costo_promedio
                    FROM    app_inventario i
                    LEFT JOIN app_productos p ON i.id_producto = p.id
                    LEFT JOIN app_producto_proveedor pp ON i.id_producto = pp.id_producto
                    LEFT JOIN app_producto_lotes l ON i.lote = l.id
                    WHERE pp.id_proveedor ". (($proveedor == "") ? "LIKE '%'" : "=$proveedor") . ( ( $productos == "(  )" || strpos( $productos, '-1')  )  ? "" : " AND p.id IN $productos" ) ."
                    GROUP BY id_producto, id_producto_caracteristica, lote;";
            $inventario = $this->queryArray($sql);
            return $inventario['rows'];
        }
        function obtenerSeries($id, $caracteristica) {
            $sql = "SELECT  s.id_almacen almacen, s.id_producto producto, m.id_producto_caracteristica caracteristicas, s.id id_serie, s.serie serie, s.estatus estatus  
                    FROM    app_producto_serie s
                    INNER JOIN app_producto_serie_rastro r ON s.id = r.id_serie
                    INNER JOIN app_inventario_movimientos m ON r.id_mov = m.id
                    WHERE   s.estatus = '0' AND s.id_producto = '$id' AND m.id_producto_caracteristica = '$caracteristica'
                    GROUP BY  s.id_almacen , s.id_producto , m.id_producto_caracteristica , s.id;";
            $series = $this->queryArray($sql);
            return  [ 'id_series' => array_column( $series['rows'] , 'id_serie')  , 
                        'series' => array_column( $series['rows'] , 'serie') ] ;
        }
        function obtenerAmacenes() {
            $sql = "SELECT * from app_almacenes where activo = 1;";
            $almacenes = $this->queryArray($sql);
            return $almacenes['rows'];
        }
        function obtenerProveedores() {
            $sql = "SELECT * from mrp_proveedor;";
            $almacenes = $this->queryArray($sql);
            return $almacenes['rows'];
        }

        function realizarAjusteExistencias($ajustesInventario) {
            $idEmpleado = $_SESSION['accelog_idempleado'];
            date_default_timezone_set("Mexico/General");
            $ahora = date("Y-m-d H:i:s");

            foreach ($ajustesInventario as $key => $value) {
                $value['caracteristicas'] = $this->id2caract($value['caracteristicas']);
                $value['caracteristicas'] = preg_replace("/'/", "\\'", $value['caracteristicas']);
                if($value['ajuste'] >= 0 ) {
                    
                    $sql = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                            VALUES 
                            ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', '{$value['ajuste']}', '{$value['costo']}',  0, {$value['almacen']}, '$ahora', '$idEmpleado', '1', {$value['costo']}, 'Ajuste existencias de inventario $ahora', '1', '2');";
                    $this->queryArray($sql);
                } else {
                    $value['ajuste'] = abs($value['ajuste']);
                    $sql = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                            VALUES 
                            ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', '{$value['ajuste']}', '{$value['costo']}', {$value['almacen']}, 0, '$ahora', '$idEmpleado', '0', {$value['costo']}, 'Ajuste existencias de inventario $ahora', '1', '2');";
                    $movimiento = $this->queryArray($sql);
//var_dump($value['series']['id_series']);
//var_dump($value['ajusteseries']);
                    $salidaseries = array_diff( $value['series']['id_series'] , $value['ajusteseries'] );
//var_dump($salidaseries);
                    if( count($salidaseries) ) {
                        $idMov = $movimiento['insertId'];
                        foreach ($salidaseries as $key => $value) {
                            $sql = "INSERT INTO app_producto_serie_rastro ( id_serie, id_almacen, fecha_reg, id_mov )
                                    VALUES
                                        ( $value, 1, '$ahora', $idMov);";
                            $this->queryArray($sql);
                            $sql = "UPDATE app_producto_serie
                                    SET estatus = '1'
                                    WHERE id = '$value';";
                            $this->queryArray($sql);

                        }
                    }
                }
                //$this->queryArray($sql);
            }
            return true;
        }

        function realizarAjusteCostos($ajustesInventario) {
            $idEmpleado = $_SESSION['accelog_idempleado'];
            date_default_timezone_set("Mexico/General");
            $ahora = date("Y-m-d H:i:s");

            foreach ($ajustesInventario as $key => $value) {
                $tmp = -$value['ajuste'];
                $value['ajuste'] =  abs($value['ajuste']) * $value['cantidad'];
                $value['caracteristicas'] = preg_replace("/'/", "\\'", $value['caracteristicas']);
                if($tmp < 0 ) {
                    $sql = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                            VALUES 
                            ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, '{$value['ajuste']}', 0, 1, '$ahora', '$idEmpleado', '1', {$value['ajuste']}, 'Ajuste costo de inventario $ahora', '1', '2');";


                     $sql2 = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                            VALUES 
                            ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, 0, 1, 0, '$ahora', '$idEmpleado', '0', 0, 'Ajuste costo de inventario $ahora', '1', '2');";
                } else {
                    $sql = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                            VALUES 
                            ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, '{$value['ajuste']}', 1, 0, '$ahora', '$idEmpleado', '0', {$value['ajuste']}, 'Ajuste costo de inventario $ahora', '1', '2');";


                    $sql2 = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                            VALUES 
                            ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, 0, 0, 1, '$ahora', '$idEmpleado', '1', 0, 'Ajuste costo de inventario $ahora', '1', '2');";
                }
                $this->queryArray($sql);
                $this->queryArray($sql2);
            }
            return true;
        }

        function realizarAjusteCostosGrl($ajustesInventario) {
            $idEmpleado = $_SESSION['accelog_idempleado'];
            date_default_timezone_set("Mexico/General");
            $ahora = date("Y-m-d H:i:s");

            foreach ($ajustesInventario as $key => $value) {
                $tmp = -$value['ajuste'];
                $value['caracteristicas'] = preg_replace("/'/", "\\'", $value['caracteristicas']);

                $sql3 = "SELECT *
                        FROM    app_inventario
                        WHERE   id_producto='{$value['id']}' AND caracteristicas='{$value['caracteristicas']}' AND lote='{$value['lote']}';";
                $res = $this->queryArray($sql3);

                foreach ($res['rows'] as $k => $v) {
                    $ajuste =  abs($value['ajuste']) * $v['cantidad'];

                    if($tmp < 0 ) {
                        $sql = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                                VALUES 
                                ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, '$ajuste', 0, '{$v['id_almacen']}', '$ahora', '$idEmpleado', '1', '$ajuste', 'Ajuste costo de inventario $ahora', '1', '2');";


                         $sql2 = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                                VALUES 
                                ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, 0, '{$v['id_almacen']}', 0, '$ahora', '$idEmpleado', '0', 0, 'Ajuste costo de inventario $ahora', '1', '2');";
                    } else {
                        $sql = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                                VALUES 
                                ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, '$ajuste', '{$v['id_almacen']}', 0, '$ahora', '$idEmpleado', '0', '$ajuste', 'Ajuste costo de inventario $ahora', '1', '2');";


                        $sql2 = "INSERT INTO app_inventario_movimientos ( id_producto, id_producto_caracteristica, id_lote, cantidad, importe, id_almacen_origen, id_almacen_destino, fecha, id_empleado, tipo_traspaso, costo, referencia, estatus, origen) 
                                VALUES 
                                ( '{$value['id']}', '{$value['caracteristicas']}', '{$value['lote']}', 1, 0, 0, '{$v['id_almacen']}', '$ahora', '$idEmpleado', '1', 0, 'Ajuste costo de inventario $ahora', '1', '2');";
                    }
                    $this->queryArray($sql);
                    $this->queryArray($sql2);
                }  
            }
            return true;
        }

        function caract2id($caract){
            return preg_replace(["/=>/", "/,/", "/'/" ], ["H", "_", ""], $caract);
        }
        function id2caract($id)
        {
            return preg_replace(['/H/','/_/','/(\d+)/' ], [ '=>',',', "'\${1}'"], $id);
        }
        function reporteConsignacion($desde, $hasta, $proveedor, $almacen) {
            $sql = "SELECT
                        p.id id,
                        p.codigo codigo, 
                        p.nombre nombre, 
                        c.costo costo, 
                        p.precio precio,
                        i.cantidad inventarioActual ,
                        sum( IF ( m.referencia LIKE 'Orden de compra / recepcion -%' , m.cantidad, 0) ) entradasOC,
                        sum( IF ( m.referencia LIKE 'Devolución de venta %' OR m.referencia LIKE 'Cancelacion Venta %' , m.cantidad, 0) ) devolucionesV, 
                        sum( IF ( m.referencia LIKE 'Devolucion de compra / devolucion -%', m.cantidad, 0) ) devolucionesOC,
                        (c.costo * sum( IF ( m.referencia LIKE 'Devolucion de compra / devolucion -%', m.cantidad, 0) )) costoDevOC,
                        sum( IF ( m.referencia LIKE 'Merma %', m.cantidad, 0) ) merma,
                        (p.precio * sum( IF ( m.referencia LIKE 'Merma %', m.cantidad, 0) ) )importeMerma,
                        sum( IF ( m.referencia LIKE 'Venta %', m.cantidad, 0) ) AS salidasV,
                        (p.precio * sum( IF ( m.referencia LIKE 'Venta %', m.cantidad, 0) ) ) importeV
                        
                    FROM    app_inventario_movimientos m
                    INNER JOIN  app_productos p ON m.id_producto = p.id
                    INNER JOIN  app_costos_proveedor c ON p.id = c.id_producto
                    INNER JOIN  app_inventario i ON i.id_producto = m.id_producto AND i.caracteristicas = m.id_producto_caracteristica
                    WHERE   m.consigna = '1' AND (m.id_almacen_origen = '$almacen' OR m.id_almacen_destino = '$almacen' ) AND c.id_proveedor = '$proveedor' AND m.fecha BETWEEN '$desde' AND ('$hasta'  + INTERVAL 1 DAY)
                    GROUP BY    m.id_producto, m.id_producto_caracteristica;";
            $res = $this->queryArray($sql);
            return $res['rows'];
        }


        function buscarProductos($patron, $proveedor) {
            $sql = "SELECT  p.id, p.nombre as text
                    FROM    app_producto_proveedor pp
                    LEFT JOIN app_productos p ON pp.id_producto = p.id
                    WHERE   p.nombre LIKE '%$patron%' AND pp.id_proveedor ".(($proveedor == "") ? "LIKE '%'" : "=$proveedor") .";" ;

            $res = $this->queryArray($sql);

            return json_encode( $res );
        }


}
?>
