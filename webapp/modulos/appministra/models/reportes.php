<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ReportesModel extends Connection
{

    public function ventas2($dia1,$dia2,$dia3,$dia4,$dia5){
        
        
        $dia1D = $dia1." 00:00:01";
        $dia1H = $dia1." 23:59:59";

        //$dia1D = '2017-06-9 00:00:01';
        //$dia1H = '2017-06-9 23:59:59';

        $dia2D = $dia2." 00:00:01";
        $dia2H = $dia2." 23:59:59";

        $dia3D = $dia3." 00:00:01";
        $dia3H = $dia3." 23:59:59";

        $dia4D = $dia4." 00:00:01";
        $dia4H = $dia4." 23:59:59";

        $dia5D = $dia5." 00:00:01";
        $dia5H = $dia5." 23:59:59";


        if($dia1D!='' && $dia1H!=''){
            $filtro1 ='v.fecha BETWEEN "'.$dia1D.'" and "'.$dia1H.'"';
        }

        if($dia2D!='' && $dia2H!=''){
            $filtro2 ='v.fecha BETWEEN "'.$dia2D.'" and "'.$dia2H.'"';
        }

        if($dia3D!='' && $dia3H!=''){
            $filtro3 ='v.fecha BETWEEN "'.$dia3D.'" and "'.$dia3H.'"';
        }

        if($dia4D!='' && $dia4H!=''){
            $filtro4 ='v.fecha BETWEEN "'.$dia4D.'" and "'.$dia4H.'"';
        }

        if($dia5D!='' && $dia5H!=''){
            $filtro5 ='v.fecha BETWEEN "'.$dia5D.'" and "'.$dia5H.'"';
        }
        /*
            $sql1 = "SELECT sum(importe) importe, fecha, 
                        SUBSTRING_INDEX(fecha,' ',1) fecha2, 
                        sum(costo) costo, referencia, 
                        if(origen = 2,SUBSTRING_INDEX(referencia,' ',-1),if(origen=1,SUBSTRING_INDEX(referencia,'-',-1),referencia)) referencia2, 
                        (sum(importe) - sum(costo)) ganancia, 
                        (((sum(importe) - sum(costo)) * 100) / sum(importe)) gananciaPor
                    FROM app_inventario_movimientos 
                    WHERE referencia LIKE '%venta%' AND tipo_traspaso = 0 AND (".$filtro1.") group by fecha2;";
            $result1 = $this->queryArray($sql1);
        */

        $sql1 = "SELECT v.idVenta AS folio, v.fecha AS fecha, SUBSTRING_INDEX(v.fecha,' ',1) fecha2, v.envio AS envio, 
                CASE WHEN c.nombre IS NOT NULL THEN c.nombre ELSE 'Publico general'END AS cliente, 
                e.usuario AS empleado, s.nombre AS sucursal,
                CASE WHEN v.estatus =1 THEN 'Activa'ELSE 'Cancelada' END AS estatus, 
                v.montoimpuestos AS iva, ROUND((v.subtotal),2) AS importe,
                
                sum(im.costo*im.cantidad) costo, v.estatus

                FROM app_pos_venta v 
                LEFT JOIN comun_cliente c ON c.id=v.idCliente 
                INNER JOIN accelog_usuarios e ON e.idempleado=v.idEmpleado 
                INNER JOIN mrp_sucursal s ON s.idSuc=v.idSucursal 
                LEFT JOIN com_comandas com ON com.id_venta = v.idVenta 
                LEFT JOIN com_mesas m ON m.id_mesa = com.idmesa
                left JOIN app_inventario_movimientos im on if(im.origen = 2,SUBSTRING_INDEX(im.referencia,' ',-1),0) = v.idVenta AND SUBSTRING_INDEX(im.referencia,' ',1) = 'Venta'
                left join app_productos p on p.id = im.id_producto
                WHERE  1 and  (".$filtro1.") "./*"and v.estatus=1".*/"
                group by v.idVenta  
                ORDER BY folio DESC;";
                $result1 = $this->queryArray($sql1);
//var_dump($sql1);
//die();        
                $cancelaciones = 0;
                $devoluciones = 0;
                foreach ($result1['rows'] as $k1 => $v1) {

                    $sql = "SELECT  fecha_devolucion, id_ov, subtotal
                            FROM    app_devolucioncli
                            WHERE   id_ov = '{$v1['folio']}';";
                    $resDev = $this->queryArray($sql);
                    $devoluciones = ( ($resDev['total'] != 0 ) ? ($devoluciones + ($resDev['rows'][0]['subtotal'])) : $devoluciones );
                    $sql = "SELECT SUM(im.importe) cancelacion FROM app_inventario_movimientos im WHERE SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion' AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) = '{$v1['folio']}';";
                    $resCan = $this->queryArray($sql);
                    $cancelaciones = ( ($resCan['total'] != 0 ) ? ($cancelaciones + ($resCan['rows'][0]['cancelacion'])) : $cancelaciones );
                   

                    $sql = "SELECT  SUM(im.costo*im.cantidad) devolucion 
                            FROM    app_inventario_movimientos im 
                            WHERE   (SUBSTRING_INDEX(im.referencia,' ',1) = 'Devolucion' OR SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion') AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) =  '{$v1['folio']}';";
                    $resDevCan = $this->queryArray($sql);
                    $CostoDevCan = ( ($resDevCan['total'] != 0 ) ? ( ($resDevCan['rows'][0]['devolucion'])) : 0 );

                    $sql = "SELECT  subtotal
                            FROM    app_devolucioncli
                            WHERE   id_ov = '{$v1['idVenta']}';";
                    $resDev = $this->queryArray($sql);
                    $devoluciones = ( ($resDev['total'] != 0 ) ? ($devoluciones + ($resDev['rows'][0]['subtotal'])) : $devoluciones );


                
                   $importeS1 = $importeS1 + $v1['importe'];
                   $costoS1 += ($v1['costo'] - $CostoDevCan);

                   $ganancia1 = $importeS1 - $devoluciones - $cancelaciones - $costoS1;
                   $gananciaPor1 = (($importeS1 - $devoluciones - $cancelaciones - $costoS1) * 100) / ($importeS1 - $devoluciones - $cancelaciones);

                   $array1[]=array(
                            //fecha       => $v1['fecha'],
                            //fecha2      => $v1['fecha2'],
                            cancelaciones => $cancelaciones,
                            devoluciones => $devoluciones,
                            devoluciones => $devoluciones,
                            importe     => $importeS1,
                            costo       => $costoS1,
                            ganancia    => $ganancia1,
                            gananciaPor => $gananciaPor1,
                    );
                }

                $arra1R = array_reverse($array1);
                foreach ($arra1R as $k11 => $v11) {
                    $array11[]=array(
                            //fecha       => $v11['fecha'],
                            //fecha2      => $v11['fecha2'],
                            cancelaciones => $v11['cancelaciones'],
                            devoluciones => $v11['devoluciones'],
                            devoluciones => $v11['devoluciones'],
                            importe     => $v11['importe'],
                            costo       => $v11['costo'],
                            ganancia    => $v11['ganancia'],
                            gananciaPor => $v11['gananciaPor'],
                    );
                    break;
                }

        /*
            $sql2 = "SELECT sum(importe) importe, fecha, 
                        SUBSTRING_INDEX(fecha,' ',1) fecha2, 
                        sum(costo) costo, referencia, 
                        if(origen = 2,SUBSTRING_INDEX(referencia,' ',-1),if(origen=1,SUBSTRING_INDEX(referencia,'-',-1),referencia)) referencia2, 
                        (sum(importe) - sum(costo)) ganancia, 
                        (((sum(importe) - sum(costo)) * 100) / sum(importe)) gananciaPor
                    FROM app_inventario_movimientos 
                    WHERE referencia LIKE '%venta%' AND tipo_traspaso = 0 AND (".$filtro2.") group by fecha2;";
            $result2 = $this->queryArray($sql2);
        */
        $sql2 = "SELECT v.idVenta AS folio, v.fecha AS fecha, SUBSTRING_INDEX(v.fecha,' ',1) fecha2, v.envio AS envio, 
                CASE WHEN c.nombre IS NOT NULL THEN c.nombre ELSE 'Publico general'END AS cliente, 
                e.usuario AS empleado, s.nombre AS sucursal,
                CASE WHEN v.estatus =1 THEN 'Activa'ELSE 'Cancelada' END AS estatus, 
                v.montoimpuestos AS iva, ROUND((v.subtotal),2) AS importe,
                
                sum(im.costo*im.cantidad) costo, v.estatus

                FROM app_pos_venta v 
                LEFT JOIN comun_cliente c ON c.id=v.idCliente 
                INNER JOIN accelog_usuarios e ON e.idempleado=v.idEmpleado 
                INNER JOIN mrp_sucursal s ON s.idSuc=v.idSucursal 
                LEFT JOIN com_comandas com ON com.id_venta = v.idVenta 
                LEFT JOIN com_mesas m ON m.id_mesa = com.idmesa
                left JOIN app_inventario_movimientos im on if(im.origen = 2,SUBSTRING_INDEX(im.referencia,' ',-1),0) = v.idVenta AND SUBSTRING_INDEX(im.referencia,' ',1) = 'Venta'
                left join app_productos p on p.id = im.id_producto
                WHERE  1 and  (".$filtro2.") "./*"and v.estatus=1".*/"
                group by v.idVenta  
                ORDER BY folio DESC;";
                $result2 = $this->queryArray($sql2);

                $cancelaciones = 0;
                $devoluciones = 0;
                foreach ($result2['rows'] as $k2 => $v2) {

                    $sql = "SELECT  fecha_devolucion, id_ov, subtotal
                            FROM    app_devolucioncli
                            WHERE   id_ov = '{$v2['folio']}';";
                    $resDev = $this->queryArray($sql);
                    $devoluciones = ( ($resDev['total'] != 0 ) ? ($devoluciones + ($resDev['rows'][0]['subtotal'])) : $devoluciones );
                    $sql = "SELECT SUM(im.importe) cancelacion FROM app_inventario_movimientos im WHERE SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion' AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) = '{$v2['folio']}';";
                    $resCan = $this->queryArray($sql);
                    $cancelaciones = ( ($resCan['total'] != 0 ) ? ($cancelaciones + ($resCan['rows'][0]['cancelacion'])) : $cancelaciones );
                   

                    $sql = "SELECT  SUM(im.costo*im.cantidad) devolucion 
                            FROM    app_inventario_movimientos im 
                            WHERE   (SUBSTRING_INDEX(im.referencia,' ',1) = 'Devolucion' OR SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion') AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) =  '{$v2['folio']}';";
                    $resDevCan = $this->queryArray($sql);
                    $CostoDevCan = ( ($resDevCan['total'] != 0 ) ? ( ($resDevCan['rows'][0]['devolucion'])) : 0 );
                
                   $importeS2 = $importeS2 + $v2['importe'];
                   $costoS2 += ($v2['costo'] - $CostoDevCan);

                   $ganancia2 = $importeS2 - $devoluciones - $cancelaciones - $costoS2;
                   $gananciaPor2 = (($importeS2  - $devoluciones - $cancelaciones - $costoS2) * 100) / ($importeS2 - $devoluciones - $cancelaciones);

                   $array2[]=array(
                            //fecha       => $v1['fecha'],
                            //fecha2      => $v1['fecha2'],
                            cancelaciones => $cancelaciones,
                            devoluciones => $devoluciones,
                            importe     => $importeS2,
                            costo       => $costoS2,
                            ganancia    => $ganancia2,
                            gananciaPor => $gananciaPor2,
                    );
                }
                $arra2R = array_reverse($array2);
                foreach ($arra2R as $k22 => $v22) {
                    $array22[]=array(
                            //fecha       => $v11['fecha'],
                            //fecha2      => $v11['fecha2'],
                            cancelaciones => $v22['cancelaciones'],
                            devoluciones => $v22['devoluciones'],
                            importe     => $v22['importe'],
                            costo       => $v22['costo'],
                            ganancia    => $v22['ganancia'],
                            gananciaPor => $v22['gananciaPor'],
                    );
                    break;
                }

        /*
            $sql3 = "SELECT sum(importe) importe, fecha, 
                        SUBSTRING_INDEX(fecha,' ',1) fecha2, 
                        sum(costo) costo, referencia, 
                        if(origen = 2,SUBSTRING_INDEX(referencia,' ',-1),if(origen=1,SUBSTRING_INDEX(referencia,'-',-1),referencia)) referencia2, 
                        (sum(importe) - sum(costo)) ganancia, 
                        (((sum(importe) - sum(costo)) * 100) / sum(importe)) gananciaPor
                    FROM app_inventario_movimientos 
                    WHERE referencia LIKE '%venta%' AND tipo_traspaso = 0 AND (".$filtro3.") group by fecha2;";
            $result3 = $this->queryArray($sql3);
        */
        $sql3 = "SELECT v.idVenta AS folio, v.fecha AS fecha, SUBSTRING_INDEX(v.fecha,' ',1) fecha2, v.envio AS envio, 
                CASE WHEN c.nombre IS NOT NULL THEN c.nombre ELSE 'Publico general'END AS cliente, 
                e.usuario AS empleado, s.nombre AS sucursal,
                CASE WHEN v.estatus =1 THEN 'Activa'ELSE 'Cancelada' END AS estatus, 
                v.montoimpuestos AS iva, ROUND((v.subtotal),2) AS importe,
                
                sum(im.costo*im.cantidad) costo, v.estatus

                FROM app_pos_venta v 
                LEFT JOIN comun_cliente c ON c.id=v.idCliente 
                INNER JOIN accelog_usuarios e ON e.idempleado=v.idEmpleado 
                INNER JOIN mrp_sucursal s ON s.idSuc=v.idSucursal 
                LEFT JOIN com_comandas com ON com.id_venta = v.idVenta 
                LEFT JOIN com_mesas m ON m.id_mesa = com.idmesa
                left JOIN app_inventario_movimientos im on if(im.origen = 2,SUBSTRING_INDEX(im.referencia,' ',-1),0) = v.idVenta AND SUBSTRING_INDEX(im.referencia,' ',1) = 'Venta'
                left join app_productos p on p.id = im.id_producto
                WHERE  1 and  (".$filtro3.") "./*"and v.estatus=1".*/"
                group by v.idVenta  
                ORDER BY folio DESC;";
                $result3 = $this->queryArray($sql3);

                $cancelaciones = 0;
                $devoluciones = 0;
                foreach ($result3['rows'] as $k3 => $v3) {

                    $sql = "SELECT  fecha_devolucion, id_ov, subtotal
                            FROM    app_devolucioncli
                            WHERE   id_ov = '{$v3['folio']}';";
                    $resDev = $this->queryArray($sql);
                    $devoluciones = ( ($resDev['total'] != 0 ) ? ($devoluciones + ($resDev['rows'][0]['subtotal'])) : $devoluciones );
                    $sql = "SELECT SUM(im.importe) cancelacion FROM app_inventario_movimientos im WHERE SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion' AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) = '{$v3['folio']}';";
                    $resCan = $this->queryArray($sql);
                    $cancelaciones = ( ($resCan['total'] != 0 ) ? ($cancelaciones + ($resCan['rows'][0]['cancelacion'])) : $cancelaciones );
                   

                    $sql = "SELECT  SUM(im.costo*im.cantidad) devolucion 
                            FROM    app_inventario_movimientos im 
                            WHERE   (SUBSTRING_INDEX(im.referencia,' ',1) = 'Devolucion' OR SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion') AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) =  '{$v3['folio']}';";
                    $resDevCan = $this->queryArray($sql);
                    $CostoDevCan = ( ($resDevCan['total'] != 0 ) ? ( ($resDevCan['rows'][0]['devolucion'])) : 0 );
                
                   $importeS3 = $importeS3 + $v3['importe'];
                   $costoS3  += ($v3['costo'] - $CostoDevCan);

                   $ganancia3 = $importeS3 - $devoluciones - $cancelaciones - $costoS3;
                   $gananciaPor3 = (($importeS3 - $devoluciones - $cancelaciones - $costoS3) * 100) / ($importeS3 - $devoluciones - $cancelaciones);

                   $array3[]=array(
                            //fecha       => $v1['fecha'],
                            //fecha2      => $v1['fecha2'],
                            cancelaciones => $cancelaciones,
                            devoluciones => $devoluciones,
                            importe     => $importeS3,
                            costo       => $costoS3,
                            ganancia    => $ganancia3,
                            gananciaPor => $gananciaPor3,
                    );
                }
                $arra3R = array_reverse($array3);
                foreach ($arra3R as $k33 => $v33) {
                    $array33[]=array(
                            //fecha       => $v11['fecha'],
                            //fecha2      => $v11['fecha2'],
                            cancelaciones => $v33['cancelaciones'],
                            devoluciones => $v33['devoluciones'],
                            importe     => $v33['importe'],
                            costo       => $v33['costo'],
                            ganancia    => $v33['ganancia'],
                            gananciaPor => $v33['gananciaPor'],
                    );
                    break;
                }

        /*
            $sql4 = "SELECT sum(importe) importe, fecha, 
                        SUBSTRING_INDEX(fecha,' ',1) fecha2, 
                        sum(costo) costo, referencia, 
                        if(origen = 2,SUBSTRING_INDEX(referencia,' ',-1),if(origen=1,SUBSTRING_INDEX(referencia,'-',-1),referencia)) referencia2, 
                        (sum(importe) - sum(costo)) ganancia, 
                        (((sum(importe) - sum(costo)) * 100) / sum(importe)) gananciaPor
                    FROM app_inventario_movimientos 
                    WHERE referencia LIKE '%venta%' AND tipo_traspaso = 0 AND (".$filtro4.") group by fecha2;";
            $result4 = $this->queryArray($sql4);
        */
        $sql4 = "SELECT v.idVenta AS folio, v.fecha AS fecha, SUBSTRING_INDEX(v.fecha,' ',1) fecha2, v.envio AS envio, 
                CASE WHEN c.nombre IS NOT NULL THEN c.nombre ELSE 'Publico general'END AS cliente, 
                e.usuario AS empleado, s.nombre AS sucursal,
                CASE WHEN v.estatus =1 THEN 'Activa'ELSE 'Cancelada' END AS estatus, 
                v.montoimpuestos AS iva, ROUND((v.subtotal),2) AS importe,
                
                sum(im.costo*im.cantidad) costo, v.estatus

                FROM app_pos_venta v 
                LEFT JOIN comun_cliente c ON c.id=v.idCliente 
                INNER JOIN accelog_usuarios e ON e.idempleado=v.idEmpleado 
                INNER JOIN mrp_sucursal s ON s.idSuc=v.idSucursal 
                LEFT JOIN com_comandas com ON com.id_venta = v.idVenta 
                LEFT JOIN com_mesas m ON m.id_mesa = com.idmesa
                left JOIN app_inventario_movimientos im on if(im.origen = 2,SUBSTRING_INDEX(im.referencia,' ',-1),0) = v.idVenta AND SUBSTRING_INDEX(im.referencia,' ',1) = 'Venta'
                left join app_productos p on p.id = im.id_producto
                WHERE  1 and  (".$filtro4.") "./*"and v.estatus=1".*/"
                group by v.idVenta  
                ORDER BY folio DESC;";
                $result4 = $this->queryArray($sql4);

                $cancelaciones = 0;
                $devoluciones = 0;
                foreach ($result4['rows'] as $k4 => $v4) {

                    $sql = "SELECT  fecha_devolucion, id_ov, subtotal
                            FROM    app_devolucioncli
                            WHERE   id_ov = '{$v4['folio']}';";
                    $resDev = $this->queryArray($sql);
                    $devoluciones = ( ($resDev['total'] != 0 ) ? ($devoluciones + ($resDev['rows'][0]['subtotal'])) : $devoluciones );
                    $sql = "SELECT SUM(im.importe) cancelacion FROM app_inventario_movimientos im WHERE SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion' AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) = '{$v4['folio']}';";
                    $resCan = $this->queryArray($sql);
                    $cancelaciones = ( ($resCan['total'] != 0 ) ? ($cancelaciones + ($resCan['rows'][0]['cancelacion'])) : $cancelaciones );
                   

                    $sql = "SELECT  SUM(im.costo*im.cantidad) devolucion 
                            FROM    app_inventario_movimientos im 
                            WHERE   (SUBSTRING_INDEX(im.referencia,' ',1) = 'Devolucion' OR SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion') AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) =  '{$v4['folio']}';";
                    $resDevCan = $this->queryArray($sql);
                    $CostoDevCan = ( ($resDevCan['total'] != 0 ) ? ( ($resDevCan['rows'][0]['devolucion'])) : 0 );
                
                   $importeS4 = $importeS4 + $v4['importe'];
                   $costoS4 += ($v4['costo'] - $CostoDevCan);

                   $ganancia4 = $importeS4 - $devoluciones - $cancelaciones- $costoS4;
                   $gananciaPor4 = (($importeS4  - $devoluciones - $cancelaciones - $costoS4) * 100) / ($importeS4  - $devoluciones - $cancelaciones);

                   $array4[]=array(
                            //fecha       => $v1['fecha'],
                            //fecha2      => $v1['fecha2'],
                            cancelaciones => $cancelaciones,
                            devoluciones => $devoluciones,
                            importe     => $importeS4,
                            costo       => $costoS4,
                            ganancia    => $ganancia4,
                            gananciaPor => $gananciaPor4,
                    );
                }
                $arra4R = array_reverse($array4);
                foreach ($arra4R as $k44 => $v44) {
                    $array44[]=array(
                            //fecha       => $v11['fecha'],
                            //fecha2      => $v11['fecha2'],
                            cancelaciones => $v44['cancelaciones'],
                            devoluciones => $v44['devoluciones'],
                            importe     => $v44['importe'],
                            costo       => $v44['costo'],
                            ganancia    => $v44['ganancia'],
                            gananciaPor => $v44['gananciaPor'],
                    );
                    break;
                }

        /*
            $sql5 = "SELECT sum(importe) importe, fecha, 
                        SUBSTRING_INDEX(fecha,' ',1) fecha2, 
                        sum(costo) costo, referencia, 
                        if(origen = 2,SUBSTRING_INDEX(referencia,' ',-1),if(origen=1,SUBSTRING_INDEX(referencia,'-',-1),referencia)) referencia2, 
                        (sum(importe) - sum(costo)) ganancia, 
                        (((sum(importe) - sum(costo)) * 100) / sum(importe)) gananciaPor
                    FROM app_inventario_movimientos 
                    WHERE referencia LIKE '%venta%' AND tipo_traspaso = 0 AND (".$filtro5.") group by fecha2;";
            $result5 = $this->queryArray($sql5);
        */

        $sql5 = "SELECT im.referencia, v.idVenta AS folio, v.fecha AS fecha, SUBSTRING_INDEX(v.fecha,' ',1) fecha2, v.envio AS envio, 
                CASE WHEN c.nombre IS NOT NULL THEN c.nombre ELSE 'Publico general'END AS cliente, 
                e.usuario AS empleado, s.nombre AS sucursal,
                CASE WHEN v.estatus =1 THEN 'Activa'ELSE 'Cancelada' END AS estatus, 
                v.montoimpuestos AS iva, ROUND((v.subtotal),2) AS importe,

                sum(im.costo*im.cantidad) costo, v.estatus

                FROM app_pos_venta v 
                LEFT JOIN comun_cliente c ON c.id=v.idCliente 
                INNER JOIN accelog_usuarios e ON e.idempleado=v.idEmpleado 
                INNER JOIN mrp_sucursal s ON s.idSuc=v.idSucursal 
                LEFT JOIN com_comandas com ON com.id_venta = v.idVenta 
                LEFT JOIN com_mesas m ON m.id_mesa = com.idmesa
                left JOIN app_inventario_movimientos im on if(im.origen = 2,SUBSTRING_INDEX(im.referencia,' ',-1),0) = v.idVenta AND SUBSTRING_INDEX(im.referencia,' ',1) = 'Venta'
                left join app_productos p on p.id = im.id_producto
                WHERE  1 and  (".$filtro5.") "./*"and v.estatus=1".*/"
                group by v.idVenta  
                ORDER BY folio DESC;";
                $result5 = $this->queryArray($sql5);

                $cancelaciones = 0;
                $devoluciones = 0;
                foreach ($result5['rows'] as $k5 => $v5) {

                    $sql = "SELECT  fecha_devolucion, id_ov, subtotal
                            FROM    app_devolucioncli
                            WHERE   id_ov = '{$v5['folio']}';";
                    $resDev = $this->queryArray($sql);
                    $devoluciones = ( ($resDev['total'] != 0 ) ? ($devoluciones + ($resDev['rows'][0]['subtotal'])) : $devoluciones );
                    $sql = "SELECT SUM(im.importe) cancelacion FROM app_inventario_movimientos im WHERE SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion' AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) = '{$v5['folio']}';";
                    $resCan = $this->queryArray($sql);
                    $cancelaciones = ( ($resCan['total'] != 0 ) ? ($cancelaciones + ($resCan['rows'][0]['cancelacion'])) : $cancelaciones );
                   

                    $sql = "SELECT  SUM(im.costo*im.cantidad) devolucion 
                            FROM    app_inventario_movimientos im 
                            WHERE   (SUBSTRING_INDEX(im.referencia,' ',1) = 'Devolucion' OR SUBSTRING_INDEX(im.referencia,' ',1) = 'Cancelacion') AND SUBSTRING_INDEX( SUBSTRING_INDEX(im.referencia,' ',4) , ' ', -1) =  '{$v5['folio']}';";
                    $resDevCan = $this->queryArray($sql);
                    $CostoDevCan = ( ($resDevCan['total'] != 0 ) ? ( ($resDevCan['rows'][0]['devolucion'])) : 0 );
//echo $sql;
                    $importeS5 = $importeS5 + $v5['importe'];
                    $costoS5 += ($v5['costo'] - $CostoDevCan);
//echo $costoS5 ."=". $v5['costo'] ."-". $CostoDevCan."->\n";
//die();
                    $ganancia5 = $importeS5 - $devoluciones - $cancelaciones - $costoS5 ;
                    $gananciaPor5 = (($importeS5 - $devoluciones - $cancelaciones - $costoS5) * 100) / ($importeS5 - $devoluciones - $cancelaciones);

                   $array5[]=array(
                            //fecha       => $v1['fecha'],
                            //fecha2      => $v1['fecha2'],
                            cancelaciones => $cancelaciones,
                            devoluciones => $devoluciones,
                            importe     => $importeS5,
                            costo       => $costoS5,
                            ganancia    => $ganancia5,
                            gananciaPor => $gananciaPor5,
                    );
                }
                $arra5R = array_reverse($array5);
                foreach ($arra5R as $k55 => $v55) {
                    $array55[]=array(
                            //fecha       => $v11['fecha'],
                            //fecha2      => $v11['fecha2'],
                            cancelaciones => $v55['cancelaciones'],
                            devoluciones => $v55['devoluciones'],
                            importe     => $v55['importe'],
                            costo       => $v55['costo'],
                            ganancia    => $v55['ganancia'],
                            gananciaPor => $v55['gananciaPor'],
                    );
                    break;
                }
        

        //return array('dia1' => $result1['rows'] , 'dia2' => $result2['rows'], 'dia3' => $result3['rows'], 'dia4' => $result4['rows'], 'dia5' => $result5['rows']);
                return array('dia1' => $array11 , 'dia2' => $array22, 'dia3' => $array33, 'dia4' => $array44, 'dia5' => $array55);
                //echo json_encode($array11);

    }

    //////////MI ORGANIZACION////////////
    public function estados($idpais){
        $sql = "SELECT idestado, estado from estados where idpais = ".$idpais.";";
        $estados = $this->queryArray($sql);
        return $estados["rows"];
    }
    //////////MI ORGANIZACION FIN////////////
    public function caract(){
        $myquery = "SELECT * from app_caracteristicas_padre;";
        $result = $this->queryArray($myquery);

        $myquery1 = "SELECT * from app_caracteristicas_hija;";
        $result1 = $this->queryArray($myquery1);

        return array('padre' => $result['rows'] , 'hija' => $result1['rows']);                               
    }
    public function listarProductosCP($idProducto,$idUnidad,$idMoneda,$lote,$series,$pedi,$carac,$act,$tipoPro){

        $filtro = '1 = 1';

        $tipoPro1         = implode(',', $tipoPro);
        $idProducto1      = implode(',', $idProducto);
        $idUnidad1        = implode(',', $idUnidad);
        $idMoneda1        = implode(',', $idMoneda);
    
        
        if($tipoPro1!=""){
            if($tipoPro1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.tipo_producto IN ('.$tipoPro1.'))';
            }
        }
        

        if($idProducto1!=""){
            if($idProducto1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.id IN ('.$idProducto1.'))';
            }
        }

        if($idUnidad1!=""){
            if($idUnidad1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.id_unidad_venta IN ('.$idUnidad1.'))';
            }
        }

        if($idMoneda1!=""){
            if($idMoneda1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.id_moneda IN ('.$idMoneda1.'))';
            }
        }

        if($lote==3){
            $filtro .='';
        }
        if($lote==1 or $lote==0){
            $filtro .=' and (p.lotes="'.$lote.'")';
        }

        if($series==3){
            $filtro .='';
        }
        if($series==1 or $series==0){
            $filtro .=' and (p.series="'.$series.'")';
        }

        if($pedi==3){
            $filtro .='';
        }
        if($pedi==1 or $pedi==0){
            $filtro .=' and (p.pedimentos="'.$pedi.'")';
        }

        if($act==3){
            $filtro .='';
        }
        if($act==1 or $act==0){
            $filtro .=' and (p.status="'.$act.'")';
        }

        if($carac==3){
            $filtro .='';
        }
        if($carac==1){
            $filtro .=" and ( if(pc.id_producto is not null, 'SI', 'NO') = 'SI')";
        }
        if($carac==0){
            $filtro .=" and ( if(pc.id_producto is not null, 'SI', 'NO') = 'NO')";
        }

        /*
        $myquery ="SELECT DISTINCT a.id, a.codigo, a.nombre producto, a.id_unidad_venta, b.nombre unidad, 
                    if(a.lotes = 1, 'SI','NO') as lotes, 
                    if(a.series = 1, 'SI','NO') as series, 
                    if(a.pedimentos = 1, 'SI','NO') as pedimentos,
                    a.tipo_producto, if(a.tipo_producto = 1, 'Producto',if(tipo_producto = 2,'Servicio','NA')) as tipo,  
                    a.id_moneda, c.codigo moneda, if(d.id_producto is not null, 'SI', 'NO') as caracteristicas 
                    from app_productos a
                    left join app_unidades_medida b on b.id = a.id_unidad_venta
                    left join cont_coin c on c.coin_id = a.id_moneda
                    left join app_producto_caracteristicas d on d.id_producto = a.id
                    where ".$filtro.";";
        */   // TIPO -> 1-Producto 2-Servicioi 3-Insumo 4-Insumo preparado 5-Receta 6-Kit                    

        $myquery = "SELECT  p.id, p.codigo, p.nombre producto, p.id_unidad_venta, um.nombre unidad, 
                    if(p.lotes = 1, 'SI','NO') as lotes, 
                    if(p.series = 1, 'SI','NO') as series, 
                    if(p.pedimentos = 1, 'SI','NO') as pedimentos,
                    p.tipo_producto, if(p.tipo_producto = 1, 'Producto',
                    if(tipo_producto = 2,'Servicio','NA')) as tipo,  
                    p.id_moneda, c.codigo moneda, 
                    if(pc.id_producto is not null, 'SI', 'NO') as caracteristicas, 
                    GROUP_CONCAT(DISTINCT im.nombre, ' ', im.valor SEPARATOR ', ') impuestos, p.tipo_producto tipoPro
                    from app_productos p
                    left join app_unidades_medida um on um.id = p.id_unidad_venta
                    left join cont_coin c on c.coin_id = p.id_moneda
                    left join app_producto_caracteristicas pc on pc.id_producto = p.id
                    left join app_producto_impuesto pi on pi.id_producto = p.id
                    left join app_impuesto im on im.id = pi.id_impuesto
                    where ".$filtro." group by p.codigo;";                    
        $productos = $this->queryArray($myquery);
        return $productos["rows"];
    }
    public function selectProductosM($tipo){
        $filtro = 'status = 1';

        $tipo1      = implode('","', $tipo);

        if($tipo1!=""){
            if($tipo1=='0'){
                $filtro .=' and tipo_producto != 6 ';
            }else{
                $filtro .=' and (tipo_producto IN ("'.$tipo1.'"))';
            }
        } else {
            $filtro .=' and tipo_producto != 6 ';
        }

        $myquery = "SELECT id, nombre from app_productos where $filtro";
        $productos = $this->queryArray($myquery);
        return $productos["rows"];
    }
    public function selectProves(){
        $myquery = "SELECT idPrv, razon_social,codigo from mrp_proveedor";
        $productos = $this->queryArray($myquery);
        return $productos["rows"];
    }
    public function listarProducto($id){
        $myquery = "SELECT a.id, a.codigo, a.nombre, a.ruta_imagen, a.id_moneda, if(a.tipo_producto = 1, 'Producto', if(a.tipo_producto = 2, 'Seivicio', 'OTRO')) as tipo, a.tipo_producto, c.codigo moneda, a.series, a.lotes, a.pedimentos, un.nombre unidad
                    from app_productos a
                    left join  cont_coin c on c.coin_id = a.id_moneda
                    left join app_unidades_medida un on un.id = a.id_unidad_venta 
                    where a.id = '$id';";
        $producto = $this->queryArray($myquery);
        return $producto["rows"];
    }
    public function textAreaCPM($id){
        /// CARACTERISTICAS
        /*  optimiza la funcion 
        $myquery = "SELECT a.id_producto, GROUP_CONCAT(DISTINCT cp.nombre SEPARATOR ", ") carac 
                    FROM app_producto_caracteristicas a
                    left join app_caracteristicas_padre cp on cp.id = a.id_caracteristica_padre 
                    where a.id_producto = '$id'
                    group by a.id_producto;";
        */            
        $myquery = "SELECT a.*, cp.nombre 
                    FROM app_producto_caracteristicas a
                    left join app_caracteristicas_padre cp on cp.id = a.id_caracteristica_padre 
                    where a.id_producto = '$id';";
        $result = $this->queryArray($myquery);
        /// LOTES
        $myquery1 = "SELECT DISTINCT a.id, CONCAT('Lote: ', a.`no_lote`, ' - Fabricacion: ', SUBSTRING(a.fecha_fabricacion,1,11), ' - Caducidad: ', SUBSTRING(a.fecha_caducidad,1,11)) as lotes 
                    FROM app_producto_lotes a
                    left join app_inventario_movimientos im on im.id_lote = a.id 
                    where im.id_producto = '$id';";
        $result1 = $this->queryArray($myquery1);
        /// SERIE
        $myquery2 = "SELECT a.id, CONCAT('Serie: ', a.serie, ' // Pedimento: ', p.no_pedimento, ' - ', p.aduana, ' - ', SUBSTRING(p.fecha_pedimento,1,11)) as serie
                    FROM app_producto_serie a 
                    left join app_producto_pedimentos p on p.id = a.id_pedimento
                    where id_producto = '$id' and estatus = 0;";
        $result2 = $this->queryArray($myquery2);
        /// PEDIMENTOS
        $myquery3 = "SELECT DISTINCT a.id, CONCAT('Pedimento: ', a.no_pedimento, ' - ', a.aduana, ' - ', SUBSTRING(a.fecha_pedimento,1,11)) as pedimento 
                    FROM app_producto_pedimentos a
                    left join app_inventario_movimientos im on im.id_pedimento = a.id
                    where im.id_producto = '$id';";
        $result3 = $this->queryArray($myquery3);

        return array('caract' => $result['rows'] , 'lotes' => $result1['rows'], 'serie' => $result2['rows'], 'pedimen' => $result3['rows']);                               
    }
    public function selectUnidadesM(){
        $myquery = "SELECT id, clave, nombre FROM app_unidades_medida";
        $productos = $this->queryArray($myquery);
        return $productos["rows"];
    }
    public function selectMonedasM(){
        $myquery = "SELECT coin_id, codigo, description FROM cont_coin";
        $productos = $this->queryArray($myquery);
        return $productos["rows"];
    }
    public function selectMVV(){
        $myquery = "SELECT DISTINCT en.id_encargado idVendedor ,CONCAT(em.nombreEmpleado, ' ',em.apellidoPaterno, ' ', em.apellidoMaterno) vendedor 
                    from app_envios en 
                    left join nomi_empleados em on em.idEmpleado = en.id_encargado;";
        $result = $this->queryArray($myquery);
        $myquery2 = "SELECT  DISTINCT cc.id, cc.nombre nombrecliente 
                    FROM app_envios en                  
                    left join app_oventa ov on ov.id = en.id_oventa            
                    left join comun_cliente cc on cc.id = ov.id_cliente;";
        $result2 = $this->queryArray($myquery2);
        return array('vendedor' => $result['rows'] , 'cliente' => $result2['rows']);
    }
    public function selectMDP(){
        $myquery = "SELECT DISTINCT  em.idEmpleado id, CONCAT(em.nombreEmpleado, ' ',em.apellidoPaterno, ' ', em.apellidoMaterno) encargado 
                        from app_devolucionpro dp     
                        left join nomi_empleados em on em.idEmpleado = dp.id_encargado;";
        $result = $this->queryArray($myquery);
        $myquery2 = "SELECT DISTINCT  mpr.idPrv id, mpr.razon_social proveedor
                        from app_devolucionpro dp                    
                        left join app_ocompra oc on oc.id = dp.id_oc
                        left join mrp_proveedor mpr on mpr.idPrv = oc.id_proveedor;";
        $result2 = $this->queryArray($myquery2);
        return array('encargado' => $result['rows'] , 'proveedor' => $result2['rows']);
    }
    public function listarDevolucionesPro($desde,$hasta,$proveedor,$producto,$sucursal,$almacen,$empleado){
        $filtro = '1 = 1';

        $desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

        $proveedor1     = implode('","', $proveedor);
        $producto1      = implode('","', $producto);
        $empleado1      = implode('","', $empleado);

        if($producto1!=""){
            if($producto1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (pr.id IN ("'.$producto1.'"))';
            }
        }
        if($proveedor1!=""){
            if($proveedor1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (mpr.idPrv IN ("'.$proveedor1.'"))';
            }
        }
        if($empleado1!=""){
            if($empleado1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (em.idEmpleado IN ("'.$empleado1.'"))';
            }
        }
        if($sucursal!=""){
            if($sucursal =='0'){
                $filtro .='';
            }else{
                $filtro .= ' and ((al.id_sucursal = "'.$sucursal.'"))';
            } 
        }
        if($almacen!=""){
            if($almacen =='0'){
                $filtro .='';
            }else{
                $filtro .= ' and ((al.codigo_sistema like "'.$almacen.'%"))';
            } 
        }
        
        if($desde!='' && $hasta!=''){
            $filtro .=' and dp.fecha_devolucion BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }

        $myquery = "SELECT dp.*, dpd.*, em.nombreEmpleado encargado, pr.nombre producto, al.nombre almacen, mpr.razon_social proveedor, un.clave unidad
                        from app_devolucionpro dp                      
                        left join nomi_empleados em on em.idEmpleado = dp.id_encargado
                        left join app_devolucionpro_datos dpd on dpd.id_devolucion = dp.id
                        left join app_productos pr on pr.id = dpd.id_producto
                        left join app_unidades_medida un on un.id = pr.id_unidad_venta
                        left join app_almacenes al on al.id = dpd.id_almacen
                        left join app_ocompra oc on oc.id = dp.id_oc
                        left join mrp_proveedor mpr on mpr.idPrv = oc.id_proveedor
                        where ".$filtro."
                        order by mpr.idPrv, pr.id, dp.fecha_devolucion;";
        $devolucionespro = $this->queryArray($myquery);
        return $devolucionespro["rows"];       
    }
    public function listarDevoluciones($desde,$hasta,$cliente,$producto,$sucursal,$almacen,$empleado){
        $filtro = '1 = 1';

        $desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

        $cliente1       = implode('","', $cliente);
        $producto1      = implode('","', $producto);
        $empleado1      = implode('","', $empleado);
    
        if($producto1!=""){
            if($producto1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (pr.id IN ("'.$producto1.'"))';
            }
        }
        if($cliente1!=""){
            if($cliente1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (cl.id IN ("'.$cliente1.'"))';
            }
        }
        if($empleado1!=""){
            if($empleado1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (em.idEmpleado IN ("'.$empleado1.'"))';
            }
        }
        if($sucursal!=""){
            if($sucursal =='0'){
                $filtro .='';
            }else{
                $filtro .= ' and ((al.id_sucursal = "'.$sucursal.'"))';
            } 
        }
        if($almacen!=""){
            if($almacen =='0'){
                $filtro .='';
            }else{
                $filtro .= ' and ((al.codigo_sistema like "'.$almacen.'%"))';
            } 
        }
        
        if($desde!='' && $hasta!=''){
            $filtro .=' and dc.fecha_devolucion BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }

        $myquery = "SELECT dc.*, dcd.*, em.nombreEmpleado encargado, pr.nombre producto, al.nombre almacen, cl.nombre nombrecliente, un.clave unidad
                        from app_devolucioncli dc
                        left join nomi_empleados em on em.idEmpleado = dc.id_encargado
                        left join app_devolucioncli_datos dcd on dcd.id_devolucion = dc.id
                        left join app_productos pr on pr.id = dcd.id_producto
                        left join app_unidades_medida un on un.id = pr.id_unidad_venta
                        left join app_almacenes al on al.id = dcd.id_almacen
                        left join app_oventa ov on ov.id = dc.id_ov
                        left join comun_cliente cl on cl.id = ov.id_cliente
                        where ".$filtro."
                        order by cl.id, pr.id, dc.fecha_devolucion;";
        $devoluciones = $this->queryArray($myquery);
        return $devoluciones["rows"];
    }
    public function graficarDevoluciones($desde,$hasta,$proveedor,$producto,$sucursal,$almacen,$empleado) {
        $filtro = '1 = 1';

        $desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

        $proveedor1       = implode('","', $proveedor);
        $cliente1       = implode('","', $cliente);
        $producto1      = implode('","', $producto);
        $empleado1      = implode('","', $empleado);

        if($proveedor1!=""){
            if($proveedor1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (mpr.idPrv IN ("'.$proveedor1.'"))';
            }
        }
        if($producto1!=""){
            if($producto1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (pr.id IN ("'.$producto1.'"))';
            }
        }
        if($cliente1!=""){
            if($cliente1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (cl.id IN ("'.$cliente1.'"))';
            }
        }
        if($empleado1!=""){
            if($empleado1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (em.idEmpleado IN ("'.$empleado1.'"))';
            }
        }
        if($sucursal!=""){
            if($sucursal =='0'){
                $filtro .='';
            }else{
                $filtro .= ' and ((al.id_sucursal = "'.$sucursal.'"))';
            } 
        }
        if($almacen!=""){
            if($almacen =='0'){
                $filtro .='';
            }else{
                $filtro .= ' and ((al.codigo_sistema like "'.$almacen.'%"))';
            } 
        }
        
        if($desde!='' && $hasta!=''){
            $filtro .=' and dp.fecha_devolucion BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }

        $sql = "SELECT pr.nombre label, SUM(cantidad) value
                        from app_devolucionpro dp                      
                        left join nomi_empleados em on em.idEmpleado = dp.id_encargado
                        left join app_devolucionpro_datos dpd on dpd.id_devolucion = dp.id
                        left join app_productos pr on pr.id = dpd.id_producto
                        left join app_unidades_medida un on un.id = pr.id_unidad_venta
                        left join app_almacenes al on al.id = dpd.id_almacen
                        left join app_ocompra oc on oc.id = dp.id_oc
                        left join mrp_proveedor mpr on mpr.idPrv = oc.id_proveedor
                        where ".$filtro."
                        group by id_producto
                        order by value DESC;";
        $donut = $this->queryArray($sql);

        $sql = "SELECT fecha_factura y, cantidad a
                        from app_devolucionpro dp                      
                        left join nomi_empleados em on em.idEmpleado = dp.id_encargado
                        left join app_devolucionpro_datos dpd on dpd.id_devolucion = dp.id
                        left join app_productos pr on pr.id = dpd.id_producto
                        left join app_unidades_medida un on un.id = pr.id_unidad_venta
                        left join app_almacenes al on al.id = dpd.id_almacen
                        left join app_ocompra oc on oc.id = dp.id_oc
                        left join mrp_proveedor mpr on mpr.idPrv = oc.id_proveedor
                        where ".$filtro."
                        order by fecha_factura DESC;";
        $line = $this->queryArray($sql);

        return array('dona' => $donut['rows'] , 'linea' => $line['rows']);
    }
    public function listarventasVendedor($desde,$hasta,$vendedor,$cliente,$documento){


        $vendedor1      = implode('","', $vendedor);
        $cliente1       = implode('","', $cliente);

        $filtro = '1 = 1';

        if($desde!='' && $hasta!=''){
            $filtro .=' and (en.fecha_envio BETWEEN "'.$desde.' 00:00:01" and "'.$hasta.' 23:59:59")';                            
        }

            
            if($documento=='0'){ // todos
                //$filtro .=' and if((rf.idFact > 0 or pf.facturado = 1),1,0) = 0 and en.facturo = 0 or if((rf.idFact > 0 or pf.facturado = 1),1,0) = 1';
                $filtro .= ' and (if((rf.idFact > 0 or pf.facturado = 1),1,0) = 0 or if((rf.idFact > 0 or pf.facturado = 1),1,0) = 1)';
            }
            if($documento=='1'){ // factura
                $filtro .=' and if((rf.idFact > 0 or pf.facturado = 1),1,0) = 1';
            }
            if($documento=='2'){ // tiket
                $filtro .=' and if((rf.idFact > 0 or pf.facturado = 1),1,0) = 0 and pf.facturado = 0';
            }
            if($documento=='3'){ // nota
                $filtro .=' and rf.tipoComp = "C"';
            }
                  

        if($vendedor1!=""){
            if($vendedor1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (en.id_encargado IN ("'.$vendedor1.'"))';
            }
        }

        if($cliente1!=""){
            if($cliente1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (cc.id IN ("'.$cliente1.'"))';
            }
        }

                                                                            /// ov.id
        $myquery = "SELECT en.id iden, en.id_encargado idVendedor ,CONCAT(em.nombreEmpleado, ' ',em.apellidoPaterno, ' ', em.apellidoMaterno) vendedor, 
                    cc.id, cc.nombre nombrecliente, en.fecha_envio fecha, en.id_oventa folio, 
                    en.facturo,
                    TRUNCATE(en.subtotal,2) importe, 
                    TRUNCATE((en.total - en.subtotal),2) imp, 
                    TRUNCATE(en.total,2) total,if((rf.idFact > 0 or pf.facturado = 1),1,0) factura, 
                    if(pa.abono is null,-1,pa.abono) abono, 
                    (select sum(abono) from app_pagos_relacion where id_tipo = 0 and id_documento = SUBSTRING_INDEX(pa.concepto,'-',-1)) abonoT 
                    from app_envios en
                    left join app_respuestaFacturacion rf on rf.idSale = en.id
                    left join app_pendienteFactura pf on pf.id_sale = en.id
                    left join nomi_empleados em on em.idEmpleado = en.id_encargado
                    left join app_oventa ov on ov.id = en.id_oventa
                    left join comun_cliente cc on cc.id = ov.id_cliente
                    left join app_pagos pa on SUBSTRING_INDEX(pa.concepto,'-',-1) = en.id and pa.origen = 1
                    where ".$filtro."
                    group by en.id
                    order by en.id_encargado, en.fecha_envio;";
        $ventas = $this->queryArray($myquery);

        /*
        $myquery = "SELECT  en.id_encargado idVendedor ,CONCAT(em.nombreEmpleado, ' ',em.apellidoPaterno, ' ', em.apellidoMaterno) vendedor, 
                    cc.id, cc.nombre nombrecliente, en.fecha_envio fecha, en.id_oventa folio, TRUNCATE(en.subtotal,2) importe, TRUNCATE((en.total - en.subtotal),2) imp, TRUNCATE(en.total,2) total 
                    from app_envios en                    
                    left join nomi_empleados em on em.idEmpleado = en.id_encargado
                    left join app_oventa ov on ov.id = en.id_oventa
                    left join comun_cliente cc on cc.id = ov.id_cliente
                    where ".$filtro."
                    order by en.id_encargado, en.fecha_envio;";
        $ventas = $this->queryArray($myquery);
        */

        /*
        $myquery2 = "SELECT ovd.id_oventa, p.codigo, p.nombre,  ovd.cantidad, truncate(ovd.costo,2) costo, truncate((ovd.cantidad * ovd.costo),2) importe, truncate(SUBSTRING_INDEX(ovd.impuestos,'-',-1),2) iva, truncate((SUBSTRING_INDEX(ovd.impuestos,'-',-1) + (ovd.cantidad * ovd.costo)),2) total
                    from app_oventa_datos ovd
                    left join app_productos p on p.id = ovd.id_producto;";
        */
        $myquery2 = "SELECT ovd.id_oventa, p.codigo, p.nombre,  ovd.cantidad, 
                        truncate(ov.costo,2) costo, 
                        truncate((ovd.cantidad * ov.costo),2) importe,
                        truncate( (((truncate(SUBSTRING_INDEX(ov.impuestos,'-',-2),2)) * (ovd.cantidad * ov.costo))/100),2) iva, 
                        en.total
                    from app_envios_datos ovd
                    left join app_productos p on p.id = ovd.id_producto
                    left join app_oventa_datos ov on ov.id_producto = ovd.id_producto and ov.id_oventa = ovd.id_oventa
                    left join app_envios en on en.id_oventa = ov.id_oventa
                    group by en.id_oventa, p.id;   ";
    
        $ventasD = $this->queryArray($myquery2);

        return array('ventas' => $ventas['rows'] , 'ventasD' => $ventasD['rows']);
    }
    public function listVentasM($vendedor,$desde,$hasta){

        $desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

        $filtro = '1 = 1';
        
        if($vendedor!=""){
            if($vendedor=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (a.id_encargado="'.$vendedor.'")';
            }  
        }
        
        if($desde!='' && $hasta!=''){
            $filtro .=' and a.fecha_envio BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }


        $myquery ="SELECT id, id_oventa, observaciones, fecha_envio, no_factura, fecha_factura, imp_factura, id_factura, subtotal, total,
                    (SELECT nombre FROM empleados WHERE idempleado = a.id_encargado) AS encargado,
                    (SELECT nombre FROM forma_pago WHERE claveSat = a.forma_pago COLLATE utf8_general_ci) AS pago
                    from app_envios a
                    where ".$filtro.";";
        $ventas = $this->queryArray($myquery);
        return $ventas["rows"];
    }
    public function selectVendedorM(){
        $myquery ="SELECT idempleado, concat(nombre,' ',apellido1,' ',apellido2) nombre FROM empleados";
        $vendedor = $this->queryArray($myquery);
        return $vendedor["rows"];
    }
    public function listarProductos(){
        $myquery = "SELECT * from app_productos where status = 1";
        $productos = $this->queryArray($myquery);
        return $productos["rows"];
    }
    public function listarAlmacen($idSuc){

        if($idSuc!=""){
            if($idSuc=='0'){
                $filtro .='';
            }else{
                $filtro .=' (id_sucursal ="'.$idSuc.'") and';
            }
        }

        $myquery = "SELECT * from app_almacenes where $filtro codigo_sistema not like '%.%' and codigo_sistema != 999 and activo = 1;";
        $almacen = $this->queryArray($myquery);
        return $almacen["rows"];
    }
    public function listarSucursal(){
        $myquery = "SELECT idSuc, nombre from mrp_sucursal;";
        $sucursal = $this->queryArray($myquery);
        return $sucursal["rows"];
    }
    public function listarCostoEst(){    
        
        /*
        $query1 = "SELECT a.id, a.codigo, a.nombre, a.id_tipo_costeo, a.series, a.lotes, a.pedimentos,
                    c.nombre,
                    cp.costo, cp.fecha
                    from app_productos a
                    left join app_costeo c on c.id = a.id_tipo_costeo
                    left join app_costos_proveedor cp on cp.id_producto = a.id
                    where a.id_tipo_costeo = 6 and cp.fecha > '0000-00-0000'
                    GROUP BY a.id;";
        */
        $query1 = "SELECT a.codigo, a.id_tipo_costeo, a.precio from app_productos a where a.id_tipo_costeo = 6;";                   

        $espesifico = $this->queryArray($query1);
        return $espesifico["rows"];  
    }
    public function listarUltimoCosto(){
        $query1 = "SELECT m.id_producto, m.costo from app_inventario_movimientos m 
                where m.fecha = (select max(fecha) from app_inventario_movimientos where id_producto = m.id_producto)
                group by m.id_producto;";
        $espesifico = $this->queryArray($query1);
        return $espesifico["rows"]; 
    }

    public function listarCostoEsp(){
        $query1 = "SELECT ps.id, ps.serie, m.costo FROM app_inventario_movimientos m 
                    RIGHT JOIN app_producto_serie_rastro s ON s.id_mov = m.id
                    INNER JOIN app_producto_serie ps ON ps.id = s.id_serie
                    WHERE m.tipo_traspaso = 1;";
        $costoS = $this->queryArray($query1);
        
        $query2 = "SELECT costo, id_lote 
                    FROM app_inventario_movimientos
                    WHERE  tipo_traspaso = 1 AND estatus = 1 AND costo != 0 ORDER BY id ASC LIMIT 1; ";
        $costoL = $this->queryArray($query2);

        $query3 = "SELECT costo, id_pedimento 
                    FROM app_inventario_movimientos
                    WHERE   tipo_traspaso = 1 AND estatus = 1 AND costo != 0 ORDER BY id ASC LIMIT 1;";
        $costoP = $this->queryArray($query3);
        
        return array('costoS' => $costoS['rows'], 'costoL' => $costoL['rows'], 'costoP' => $costoP['rows']);                               
    }

    public function selectedDepartamento(){
        $query = "SELECT * from app_departamento;";
        $result = $this->queryArray($query);
        return $result['rows'];
    }
    public function listarFamilia($iddepartamento){
        $query = "SELECT * from app_familia where id_departamento = $iddepartamento";
        $result = $this->queryArray($query);
        return $result['rows'];
    }
    public function listarLinea($idfamilia){
        $query = "SELECT * from app_linea where id_familia = $idfamilia and activo = 1";
        $result = $this->queryArray($query);
        return $result['rows'];
    }
    public function movCart($desde,$hasta){

        $filtro = 'm.estatus = 1';
        
        if($desde!='' && $hasta!=''){
            $filtro .=' and (m.fecha BETWEEN "'.$desde.' 00:00:01" and "'.$hasta.' 23:59:59")'; /// se podira cambiar $desde a 00:00:01
        }  

        /*
        $query1 = "SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo, SUBSTRING(oo.codigo_sistema,1,1) idorigenS, SUBSTRING(dd.codigo_sistema,1,1) iddestinoS,
                    if(dd.id is null, oo.id, dd.id) as almacen, 
                    if(dd.nombre is null, oo.nombre, dd.nombre) as almacenNombre,
                    if(dd.nombre is null, oo.codigo_sistema, dd.codigo_sistema) as codigoSistema,
                    if(m.id is not null, '$almacen', '$almacen') almacenRR, 
                        cc.codigo moneda, un.nombre unidad, m.id_producto_caracteristica caract
                    from app_inventario_movimientos m
                    left join app_almacenes oo on oo.id = m.id_almacen_origen
                    left join app_almacenes dd on dd.id = m.id_almacen_destino
                    left join app_productos p on p.id = m.id_producto
                    left join accelog_usuarios u on u.idempleado = m.id_empleado
                    left join app_unidades_medida un on un.id = p.id_unidad_venta
                    left join cont_coin cc on cc.coin_id = p.id_moneda
                    where ".$filtro."
                    order by p.codigo, m.fecha;";
                    */

       $query1 = "SELECT m.id, m.id_producto_caracteristica caract from app_inventario_movimientos m 
       where  ".$filtro."
       order by m.id_producto, m.fecha;";


        $kardex = $this->queryArray($query1);
        return $kardex["rows"];/// solo para el arreglo de caracteristicas
    }
    public function ubicCaractMov($desde,$hasta,$tipo,$producto,$tipoProIA,$unid,$unidades,$provedor,$consigna=2){ /// todos los mov incluyendo traspasos
        
        $filtro = 'and m.estatus = 1';
        $filtro .= ' and p.status = 1';

        $producto1      = implode('","', $producto);
        $tipoProIA1      = implode('","', $tipoProIA);
        $unidades1      = implode('","', $unidades);
        $provedor1      = implode('","', $provedor);


        if($tipo == 'movs')
        {
            if($desde!='' && $hasta!=''){
            $filtro .=' and m.fecha <= "'.$hasta.' 23:59:59" ';
            } 
        }
        if($tipo == 'exis')
        {
            if($desde!=''){ 
                $filtro .=' and m.fecha <= "'.$desde.' 00:00:01" ';
            }
        }

        if($tipoProIA1!=""){
            if($tipoProIA1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.tipo_producto IN ("'.$tipoProIA1.'"))';
            }
        }

        if($producto1!=""){
            if($producto1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.id IN ("'.$producto1.'"))';
            }
        }

        if($unidades1!=""){
            if($unidades1=='0'){
                $filtro .='';
            }else{
                if($unid == 1){// compra
                    $filtro .=' and (p.id_unidad_compra IN ("'.$unidades1.'"))';
                }else{// venta
                    $filtro .=' and (p.id_unidad_venta IN ("'.$unidades1.'"))';
                }
                
            }
        }

        $leftPro = '';
        if($provedor1!=""){
            if($provedor1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (pprv.id_proveedor IN ("'.$provedor1.'"))';
                $leftPro = ' left join app_producto_proveedor pprv on m.id_producto=pprv.id_producto ';
            }
        }

        if($consigna!=2){
            $filtro.=' and p.consigna='.$consigna;
        }
        

    $query1 = "(SELECT m.id, p.nombre, p.codigo, m.cantidad, m.costo*m.cantidad importe, m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo, m.id_almacen_destino as aux, 0 as traspasoaux, rr.codigo_sistema, unv.clave unidad, unc.clave unidadC, unc.factor converC, m.id_producto_caracteristica caract, alr.nombre almacenUbicacion, alr.id idubicacion, p.id_tipo_costeo costeo, cc.codigo moneda, (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP
                        from app_inventario_movimientos m
                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
                                            left join app_almacenes rr on rr.id = oo.id
                                            left join app_productos p on p.id = m.id_producto
                                            left join accelog_usuarios u on u.idempleado = m.id_empleado
                                            left join app_unidades_medida unv on unv.id = p.id_unidad_venta
                                            left join app_unidades_medida unc on unc.id = p.id_unidad_compra
                                            left join cont_coin cc on cc.coin_id = p.id_moneda
                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                            ".$leftPro."

                        where m.tipo_traspaso = 0 ".$filtro.")
                        union all
                        (SELECT m.id, p.nombre, p.codigo, m.cantidad, m.costo*m.cantidad importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo, m.id_almacen_origen as aux, 1 as traspasoaux, rr.codigo_sistema, unv.clave unidad, unc.clave unidadC, unc.factor converC, m.id_producto_caracteristica caract, alr.nombre almacenUbicacion, alr.id idubicacion, p.id_tipo_costeo costeo, cc.codigo moneda, (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP
                        from app_inventario_movimientos m
                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
                                            left join app_almacenes rr on rr.id = dd.id
                                            left join app_productos p on p.id = m.id_producto
                                            left join accelog_usuarios u on u.idempleado = m.id_empleado
                                            left join app_unidades_medida unv on unv.id = p.id_unidad_venta
                                            left join app_unidades_medida unc on unc.id = p.id_unidad_compra
                                            left join cont_coin cc on cc.coin_id = p.id_moneda
                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                            ".$leftPro."

                        where m.tipo_traspaso = 1 ".$filtro.")
                        union all
                        (SELECT m.id, p.nombre, p.codigo, m.cantidad, m.costo*m.cantidad importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo, m.id_almacen_origen as aux, 0 as traspasoaux, rr.codigo_sistema, unv.clave unidad, unc.clave unidadC, unc.factor converC, m.id_producto_caracteristica caract, alr.nombre almacenUbicacion, alr.id idubicacion, p.id_tipo_costeo costeo, cc.codigo moneda, (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP
                        from app_inventario_movimientos m 
                                            left join app_almacenes rr on rr.id = m.id_almacen_origen
                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
                                            left join app_productos p on p.id = m.id_producto
                                            left join accelog_usuarios u on u.idempleado = m.id_empleado
                                            left join app_unidades_medida unv on unv.id = p.id_unidad_venta
                                            left join app_unidades_medida unc on unc.id = p.id_unidad_compra
                                            left join cont_coin cc on cc.coin_id = p.id_moneda
                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                            ".$leftPro."

                        where m.tipo_traspaso = 2 ".$filtro.")
                        union all
                        (SELECT m.id, p.nombre, p.codigo, m.cantidad, m.costo*m.cantidad importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo, m.id_almacen_destino as aux, 1 as traspasoaux, rr.codigo_sistema, unv.clave unidad, unc.clave unidadC, unc.factor converC, m.id_producto_caracteristica caract, alr.nombre almacenUbicacion, alr.id idubicacion, p.id_tipo_costeo costeo, cc.codigo moneda, (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP
                        from app_inventario_movimientos m
                                            left join app_almacenes rr on rr.id = m.id_almacen_destino
                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
                                            left join app_productos p on p.id = m.id_producto
                                            left join accelog_usuarios u on u.idempleado = m.id_empleado
                                            left join app_unidades_medida unv on unv.id = p.id_unidad_venta
                                            left join app_unidades_medida unc on unc.id = p.id_unidad_compra
                                            left join cont_coin cc on cc.coin_id = p.id_moneda
                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                            ".$leftPro."
                         
                        where m.tipo_traspaso = 2 ".$filtro.")
                        ORDER BY codigo, fecha, almacenRR ASC;";
                        //echo $query1.'<br><br>';
                        //exit();
                        //print_r($query1);

        $invActUni = $this->queryArray($query1);
        return $invActUni["rows"];
    }
    public function ubicCaractMovMI($desde,$hasta,$producto){ /// todos los mov incluyendo traspasos
        
        $filtro = 'and m.estatus = 1';  // estatus del movimiento
        $filtro .= ' and p.status = 1'; // estatus del producto
        $filtro2 = 'and m.estatus = 1';
        $filtro2 .= ' and p.status = 1';

        $producto1      = implode('","', $producto);

        if($desde!='' && $hasta!=''){
            $filtro .=' and m.fecha <= "'.$hasta.' 23:59:59" ';
            $filtro2 .=' and m.fecha <= "'.$hasta.' 23:59:59" ';
        } 

        if($producto1!=""){
            if($producto1=='0'){
                $filtro .='';
                $filtro2 .='';
            }else{
                $filtro .=' and (p.id IN ("'.$producto1.'"))';
                $filtro2 .=' and (m.id_producto IN ("'.$producto1.'"))';
            }
        }
        
        $query1 = "(SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, 
                            m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo, 
                            m.id_almacen_destino as aux, 0 as traspasoaux, rr.codigo_sistema, un.clave unidad, 
                            m.id_pedimento, m.id_lote, pe.no_pedimento, pe.fecha_pedimento, pe.no_aduana, pe.tipo_cambio, 
                            lo.no_lote, lo.fecha_caducidad, lo.fecha_fabricacion, m.costo precioVenta, m.importe importeVenta, 
                            if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'') idMove, 
                            if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'/',1),'') concepMove, mrc.razon_social, ccv.nombretienda,
                            (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen,
                            (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id idubicacion, alr.nombre almacenUbicacion,
                            (select SUBSTRING_INDEX(m.referencia,' ',-1)) idMovepos,
                            posc.nombretienda nombretiendapos, mrcp.razon_social razon_socialpos, m.origen
                            from app_inventario_movimientos m
                                left join app_almacenes oo on oo.id = m.id_almacen_origen
                                left join app_almacenes dd on dd.id = m.id_almacen_destino
                                left join app_almacenes rr on rr.id = oo.id
                                left join app_productos p on p.id = m.id_producto
                                left join accelog_usuarios u on u.idempleado = m.id_empleado
                                left join app_unidades_medida un on un.id = p.id_unidad_venta
                                left join cont_coin cc on cc.coin_id = p.id_moneda
                                left join app_producto_pedimentos pe on pe.id = m.id_pedimento
                                left join app_producto_lotes lo on lo.id = m.id_lote
                                left join app_departamento de on de.id = p.departamento
                                left join app_recepcion re on re.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_ocompra occ on occ.id = re.id_oc
                                left join mrp_proveedor mrc on mrc.idPrv = occ.id_proveedor
                                left join app_envios en on en.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_oventa ov on ov.id = en.id_oventa
                                left join comun_cliente ccv on ccv.id = ov.id_cliente
                                left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
                                left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                left join app_pos_venta posv on posv.idVenta = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join comun_cliente posc on posc.id = posv.idCliente
                                left join app_ocompra occp on occp.id = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join mrp_proveedor mrcp on mrcp.idPrv = occp.id_proveedor
                            where m.tipo_traspaso = 0 ".$filtro.")
                            union all
                        (SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, 
                            m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo,
                            m.id_almacen_origen as aux, 1 as traspasoaux, rr.codigo_sistema, un.clave unidad, 
                            m.id_pedimento, m.id_lote, pe.no_pedimento, pe.fecha_pedimento, pe.no_aduana, pe.tipo_cambio, 
                            lo.no_lote, lo.fecha_caducidad, lo.fecha_fabricacion, m.costo precioVenta, m.importe importeVenta, 
                            if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'') idMove, 
                            if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'/',1),'') concepMove, mrc.razon_social, ccv.nombretienda,
                            (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen,
                            (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id idubicacion, alr.nombre almacenUbicacion,
                            (select SUBSTRING_INDEX(m.referencia,' ',-1)) idMovepos,
                            posc.nombretienda nombretiendapos, mrcp.razon_social razon_socialpos, m.origen
                            from app_inventario_movimientos m
                                left join app_almacenes oo on oo.id = m.id_almacen_origen
                                left join app_almacenes dd on dd.id = m.id_almacen_destino
                                left join app_almacenes rr on rr.id = dd.id
                                left join app_productos p on p.id = m.id_producto
                                left join accelog_usuarios u on u.idempleado = m.id_empleado
                                left join app_unidades_medida un on un.id = p.id_unidad_venta
                                left join cont_coin cc on cc.coin_id = p.id_moneda
                                left join app_producto_pedimentos pe on pe.id = m.id_pedimento
                                left join app_producto_lotes lo on lo.id = m.id_lote
                                left join app_departamento de on de.id = p.departamento
                                left join app_recepcion re on re.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_ocompra occ on occ.id = re.id_oc
                                left join mrp_proveedor mrc on mrc.idPrv = occ.id_proveedor
                                left join app_envios en on en.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_oventa ov on ov.id = en.id_oventa
                                left join comun_cliente ccv on ccv.id = ov.id_cliente                                          
                                left join app_almacenes x on x.id = left(rr.codigo_sistema,1)  
                                left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                left join app_pos_venta posv on posv.idVenta = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join comun_cliente posc on posc.id = posv.idCliente
                                left join app_ocompra occp on occp.id = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join mrp_proveedor mrcp on mrcp.idPrv = occp.id_proveedor                       
                            where m.tipo_traspaso = 1 ".$filtro.")
                            union all
                        (SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, 
                            m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo, 
                            m.id_almacen_origen as aux, 0 as traspasoaux, rr.codigo_sistema, un.clave unidad, 
                            m.id_pedimento, m.id_lote, pe.no_pedimento, pe.fecha_pedimento, pe.no_aduana, pe.tipo_cambio, 
                            lo.no_lote, lo.fecha_caducidad, lo.fecha_fabricacion, m.costo precioVenta, m.importe importeVenta, 
                            if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'') idMove, 
                            if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'/',1),'') concepMove, mrc.razon_social, ccv.nombretienda,
                            (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen,
                            (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id idubicacion, alr.nombre almacenUbicacion,
                            (select SUBSTRING_INDEX(m.referencia,' ',-1)) idMovepos,
                            posc.nombretienda nombretiendapos, mrcp.razon_social razon_socialpos, m.origen
                            from app_inventario_movimientos m 
                                left join app_almacenes rr on rr.id = m.id_almacen_origen
                                left join app_almacenes oo on oo.id = m.id_almacen_origen
                                left join app_almacenes dd on dd.id = m.id_almacen_destino
                                left join app_productos p on p.id = m.id_producto
                                left join accelog_usuarios u on u.idempleado = m.id_empleado
                                left join app_unidades_medida un on un.id = p.id_unidad_venta
                                left join cont_coin cc on cc.coin_id= p.id_moneda
                                left join app_producto_pedimentos pe on pe.id = m.id_pedimento
                                left join app_producto_lotes lo on lo.id = m.id_lote
                                left join app_departamento de on de.id = p.departamento
                                left join app_recepcion re on re.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_ocompra occ on occ.id = re.id_oc
                                left join mrp_proveedor mrc on mrc.idPrv = occ.id_proveedor
                                left join app_envios en on en.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_oventa ov on ov.id = en.id_oventa
                                left join comun_cliente ccv on ccv.id = ov.id_cliente                                            
                                left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
                                left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                left join app_pos_venta posv on posv.idVenta = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join comun_cliente posc on posc.id = posv.idCliente
                                left join app_ocompra occp on occp.id = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join mrp_proveedor mrcp on mrcp.idPrv = occp.id_proveedor                      
                            where m.tipo_traspaso = 2 ".$filtro.")
                            union all
                        (SELECT m.id, p.nombre, p.codigo, m.cantidad, m.importe , m.fecha, u.usuario, m.tipo_traspaso, m.costo, m.referencia, 
                            m.id_producto, oo.id idorigen, oo.nombre origen, dd.id iddestino, dd.nombre destino, p.id_tipo_costeo costeo,
                             m.id_almacen_destino as aux, 1 as traspasoaux, rr.codigo_sistema, un.clave unidad, 
                             m.id_pedimento, m.id_lote, pe.no_pedimento, pe.fecha_pedimento, pe.no_aduana, pe.tipo_cambio, 
                             lo.no_lote, lo.fecha_caducidad, lo.fecha_fabricacion, m.costo precioVenta, m.importe importeVenta, 
                             if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'') idMove, 
                            if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'/',1),'') concepMove, mrc.razon_social, ccv.nombretienda,
                            (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen,
                            (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id idubicacion, alr.nombre almacenUbicacion,
                            (select SUBSTRING_INDEX(m.referencia,' ',-1)) idMovepos,
                            posc.nombretienda nombretiendapos, mrcp.razon_social razon_socialpos, m.origen
                            from app_inventario_movimientos m
                                left join app_almacenes rr on rr.id = m.id_almacen_destino
                                left join app_almacenes oo on oo.id = m.id_almacen_origen
                                left join app_almacenes dd on dd.id = m.id_almacen_destino
                                left join app_productos p on p.id = m.id_producto
                                left join accelog_usuarios u on u.idempleado = m.id_empleado
                                left join app_unidades_medida un on un.id = p.id_unidad_venta
                                left join cont_coin cc on cc.coin_id = p.id_moneda
                                left join app_producto_pedimentos pe on pe.id = m.id_pedimento 
                                left join app_producto_lotes lo on lo.id = m.id_lote
                                left join app_departamento de on de.id = p.departamento
                                left join app_recepcion re on re.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_ocompra occ on occ.id = re.id_oc
                                left join mrp_proveedor mrc on mrc.idPrv = occ.id_proveedor
                                left join app_envios en on en.id = if(SUBSTRING_INDEX(m.referencia,'-',-1),SUBSTRING_INDEX(m.referencia,'-',-1),'')
                                left join app_oventa ov on ov.id = en.id_oventa
                                left join comun_cliente ccv on ccv.id = ov.id_cliente                                                                                       
                                left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
                                left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
                                left join app_pos_venta posv on posv.idVenta = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join comun_cliente posc on posc.id = posv.idCliente
                                left join app_ocompra occp on occp.id = (select SUBSTRING_INDEX(m.referencia,' ',-1))
                                left join mrp_proveedor mrcp on mrcp.idPrv = occp.id_proveedor
                            where m.tipo_traspaso = 2 ".$filtro.")
                        ORDER BY codigo, fecha, almacenRR ASC;";                        
        $movs = $this->queryArray($query1);
        
        $query2 = "SELECT m.id, GROUP_CONCAT(ps.serie SEPARATOR ', ') series from app_inventario_movimientos m 
                        left join app_producto_serie_rastro psr on psr.id_mov = m.id
                        left join app_producto_serie ps on ps.id  = psr.id_serie
                        where ps.serie is not null
                        group by m.id; ";
        $series = $this->queryArray($query2); 

        $query3 = "SELECT m.id_pedimento, m.cantidad from app_inventario_movimientos m where m.id_pedimento > 0 ".$filtro2." and m.tipo_traspaso = 1  group by m.id_pedimento order by m.fecha asc;";
        $pediT = $this->queryArray($query3);

        $query4 = "SELECT m.id_lote, m.cantidad from app_inventario_movimientos m where m.id_lote > 0 ".$filtro2." and m.tipo_traspaso = 1  group by m.id_lote order by m.fecha asc;";
        $loteT = $this->queryArray($query4);

        return array('movs' => $movs['rows'], 'series' => $series['rows'], 'pediT' => $pediT['rows'], 'loteT' => $loteT['rows']);                               
    }

    public function unidades(){
        $sql = "SELECT id, clave from app_unidades_medida;";
        $unidades = $this->queryArray($sql);
        return $unidades["rows"];
    }




    function getProducts($idDeparment = 0, $idFamily = 0, $idLine = 0, $limite = 0, $sucursal) {
        if (!empty($sucursal)) {
            $sucursal = $sucursal;
        } else {
            session_start();
            $sucursal = "   SELECT 
                                mp.idSuc AS id 
                            FROM 
                                administracion_usuarios au 
                            INNER JOIN 
                                    mrp_sucursal mp 
                                ON 
                                    mp.idSuc = au.idSuc 
                            WHERE 
                                au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
                            LIMIT 1";
            $sucursal = $this -> queryArray($sucursal);
            $sucursal = $sucursal['rows'][0]['id'];
        }
    // Filtra por departamento si existe
        if ($idDeparment)
            $condicion .= " AND p.departamento=$idDeparment ";

    // Filtra por familia si existe
        if ($idFamily)
            $condicion .= " AND p.familia=$idFamily ";

    // Filtra por linea si existe
        if ($idLine)
            $condicion .= " AND p.linea=$idLine ";
        
        $limite = (!empty($limite)) ? ' LIMIT '.$limite : ' LIMIT 0, 100' ;
        $sql = "select * from app_producto_sucursal limit 1";
        $total = $this -> queryArray($sql);
        if($total['total'] > 0){
            $sql = "SELECT
                    p.id AS idProducto, p.nombre, ROUND(p.precio, 2) AS precioventa, p.ruta_imagen AS imagen, 
                    IF((SELECT 
                            COUNT(id)
                        FROM
                            app_producto_material
                        WHERE
                            id_producto = p.id) > 0, 1, 0) materiales, departamento AS idDep, 
                    f.h_ini AS inicio, f.h_fin AS fin, f.dias, p.formulaieps AS formula
                FROM
                    app_productos p
                LEFT JOIN
                        app_campos_foodware f
                    ON
                        p.id=f.id_producto
                LEFT JOIN 
                        app_linea l
                    ON 
                        p.linea=l.id
                LEFT JOIN 
                        app_familia fa
                    ON 
                        p.familia=fa.id
                LEFT JOIN 
                        app_departamento d
                    ON 
                        p.departamento=d.id
                INNER JOIN app_producto_sucursal aps 
                    ON 
                        aps.id_producto = p.id 
                    AND 
                        aps.id_sucursal = ".$sucursal."
                WHERE
                    p.status = 1
                AND
                    tipo_producto != 3 
                AND
                    tipo_producto != 6
                AND
                    tipo_producto != 7
                AND
                    tipo_producto != 8 " . 
                $condicion . "
                GROUP BY
                    p.id
                ORDER BY
                    f.rate DESC".
                $limite;
        } else {
            
        $sql = "SELECT
                    p.id AS idProducto, p.nombre, ROUND(p.precio, 2) AS precioventa, p.ruta_imagen AS imagen, 
                    IF((SELECT 
                            COUNT(id)
                        FROM
                            app_producto_material
                        WHERE
                            id_producto = p.id) > 0, 1, 0) materiales, departamento AS idDep, 
                    f.h_ini AS inicio, f.h_fin AS fin, f.dias, p.formulaieps AS formula
                FROM
                    app_productos p
                LEFT JOIN
                        app_campos_foodware f
                    ON
                        p.id=f.id_producto
                LEFT JOIN 
                        app_linea l
                    ON 
                        p.linea=l.id
                LEFT JOIN 
                        app_familia fa
                    ON 
                    p.familia=fa.id
                LEFT JOIN 
                        app_departamento d
                    ON 
                        p.departamento=d.id
                WHERE
                    p.status = 1
                AND
                    tipo_producto != 3 
                AND
                    tipo_producto != 6
                AND
                    tipo_producto != 7
                AND
                    tipo_producto != 8 " . 
                $condicion . "
                GROUP BY
                    p.id
                ORDER BY
                    f.rate DESC".
                $limite;
            }
        $productsComanda = $this -> queryArray($sql);
        return $productsComanda;
    }

    function listar_sucursales($objeto) {
    // Si viene el id del empleado Filtra por empleado
        $condicion .= (!empty($objeto['id'])) ? ' AND idSuc = \'' . $objeto['id'] . '\'' : '';

    // Ordena si existe, si no ordena por ID descendente
        $orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' idSuc DESC';

        $sql = "SELECT
                    idSuc AS id, nombre
                FROM 
                    mrp_sucursal
                WHERE
                    1 = 1" . 
                $condicion . "
                ORDER BY " . 
                    $orden;
        // return $sql;
        $result = $this -> queryArray($sql);
        return $result;
    }

    function listar_utilidades2($ini, $end,  $sucursal, $producto)
    {
        $sql = "SELECT  s.nombre sucursal, p.nombre producto, sum(cantidad) cantidad, sum(importe) importe, sum(costo) costo, sum(importe)-sum(costo) utilidad
                FROM    (
                        SELECT  id_producto, 
                                CASE WHEN (SUBSTRING_INDEX(referencia,' ',3) = 'Devolución de venta' ) THEN SUBSTRING_INDEX(SUBSTRING_INDEX(referencia,' ',4),' ',-1)
                                ELSE (SUBSTRING_INDEX(referencia,' ',-1)) 
                                END id_venta,
                                CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN (cantidad*1)
                                ELSE (cantidad*-1)
                                END cantidad,
                                CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN ((importe)*1)
                                ELSE ((importe)*-1)
                                END importe,
                                CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN ((costo*cantidad)*1)
                                ELSE ((costo*cantidad)*-1)
                                END costo,
                                CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN ((importe)-(costo*cantidad))
                                ELSE ( ((importe)-(costo*cantidad))*-1 )
                                END utilidad 
                        FROM    app_inventario_movimientos 
                        WHERE   SUBSTRING_INDEX(referencia,' ',1) = 'Venta' OR
                                (SUBSTRING_INDEX(referencia,' ',2) = 'Cancelacion Venta') OR
                                (SUBSTRING_INDEX(referencia,' ',3) = 'Devolución de venta' )
                        ) im
                INNER JOIN  app_pos_venta v ON im.id_venta = v.idVenta
                INNER JOIN  mrp_sucursal s ON v.idSucursal = s.idSuc
                INNER JOIN  app_productos p ON im.id_producto = p.id
                WHERE       (fecha BETWEEN '$ini' AND '$end 23:59:59') AND s.idSuc LIKE '%$sucursal%' AND im.id_producto LIKE '%$producto%'
                GROUP BY    s.idSuc, im.id_producto;";
        return  $this -> queryArray($sql);
    }     
}
?>
