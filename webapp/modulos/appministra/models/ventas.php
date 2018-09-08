<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

    class VentasModel extends Connection
    {
        function nl2brCH($string)
        {
            return preg_replace('/\R/u', '<br/><br/>', $string);
        }
        function getLastNumRequisicion()
        {
            /* CHRIS - COMENTARIOS
            =============================*/
            
            //Query para obtener el numero de requisicion nuevo (ultimo id + 1)
            $myQuery = "SELECT if(MAX(id) is NULL,1,MAX(id)+1) as id from app_requisiciones_venta;";
            $nreq = $this->query($myQuery);
            return $nreq;
        }

        function getUsuario(){
            session_start();
            $idusr = $_SESSION['accelog_idempleado'];

            $myQuery = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
            $nreq = $this->query($myQuery);
            //session_destroy();
            return $nreq;
        }

        function configDesc(){
            $myQuery = "SELECT * FROM app_config_ventas";                        
            $result = $this->queryArray($myQuery);
            $config = $result["rows"];
            return $config;
        }


        function getLastNumOV()
        {
            /* CHRIS - COMENTARIOS
            =============================*/
            
            //Query para obtener el numero de requisicion nuevo (ultimo id + 1)
            $myQuery = "SELECT if(MAX(id) is NULL,1,MAX(id)+1) as id from app_oventa;";
            $nreq = $this->query($myQuery);
            return $nreq;
        }

        function datosImpresion($idCliente)
        {

            $myQuery = "SELECT a.nombre as nombre, a.direccion as direccion, a.email, e.nombreorganizacion, e.domicilio, e.logoempresa  FROM comun_cliente a left join organizaciones e on e.idorganizacion=1 where a.id='$idCliente';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function rfcOrganizacion(){
            $sql=$this->query("select RFC from organizaciones ");
            return $sql->fetch_assoc();
        }

        function getReqsAutorizar()
        {
            $myQuery = "SELECT count(*) as reqs FROM app_requisiciones WHERE (activo=0 or activo=3);";
            $reqs = $this->query($myQuery);
            return $reqs;
        }

        function getClientes()
        {
            $myQuery = "SELECT id, nombre FROM comun_cliente ORDER BY nombre;";
            $clientes = $this->query($myQuery);
            return $clientes;
        }

        function getProveedores()
        {
            $myQuery = "SELECT idPrv, razon_social FROM mrp_proveedor ORDER BY razon_social;";
            $proveedores = $this->query($myQuery);
            return $proveedores;
        }
        function getProductos()
        {
            $myQuery = "SELECT id, nombre FROM app_productos ORDER BY nombre;";
            $proveedores = $this->query($myQuery);
            return $proveedores;

            /*
            $myQuery = "SELECT a.id, a.nombre, if(b.cmas is null,0,b.cmas) as cmas, if(c.cmin is null,0,c.cmin) as cmin from app_productos a
left join (SELECT aa.id_producto, sum(aa.cantidad) as cmas FROM app_inventario_movimientos aa WHERE aa.id_almacen_destino!=0 GROUP BY aa.id_producto) b on b.id_producto=a.id
left join (SELECT aa.id_producto, sum(aa.cantidad) as cmin FROM app_inventario_movimientos aa WHERE aa.id_almacen_destino=0 GROUP BY aa.id_producto) c on c.id_producto=a.id
where cmas>0;";
            $proveedores = $this->query($myQuery);
            return $proveedores;
            */
        }
        public function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = $this->object_to_array($value);
                }
                return $result;
            }
        return $data;
        }

        function checkNoti()
        {
            $myQuery = "SELECT not_ventas FROM app_configuracion order by id asc limit 1;";
            $result = $this->queryArray($myQuery);
            $not = $result["rows"][0]["not_ventas"];
            return $not; 
        }


        function verFactura($idFact)
        {
            $myQuery = "SELECT xmlfile FROM app_respuestaFacturacion WHERE id='$idFact';";
            $fact = $this->query($myQuery);
            return $fact;
        }
        function verFacturaPdf($idFact)
        {
            $myQuery = "SELECT folio FROM app_respuestaFacturacion WHERE id='$idFact';";
            $fact = $this->query($myQuery);
            return $fact;
        }
        function getEmpleados()
        {
            $myQuery = "SELECT a.idEmpleado as idempleado, concat(a.nombreEmpleado,' ',a.apellidoPaterno,' ',a.apellidoMaterno) as nombre, b.nombre as nomarea FROM nomi_empleados a
            left join app_area_empleado b on b.id=a.id_area_empleado ORDER BY a.nombreEmpleado;";
            $empleados = $this->query($myQuery);
            return $empleados;
        }
        function getMonedas()
        {
            $myQuery = "SELECT a.coin_id, a.codigo, if(b.tipo_cambio is null,'',b.tipo_cambio) as tc
                FROM cont_coin a 
                LEFT join (SELECT * from cont_tipo_cambio order by fecha desc limit 1) b on b.moneda=a.coin_id
                inner join app_productos pr on pr.id_moneda = a.coin_id
                GROUP BY a.coin_id
                ORDER BY a.coin_id;";
            $monedas = $this->query($myQuery);
            return $monedas;
        }
        function getFormaPago()
        {
            $myQuery = "SELECT * FROM forma_pago_caja ORDER BY idFormapago;";
            $fp = $this->query($myQuery);
            return $fp;
        }
        function getSeriesProd($idProducto)
        {
            $myQuery = "SELECT a.*, b.nombre, b.id as ida from app_producto_serie a 
            inner join app_almacenes b on b.id= a.id_almacen
            where a.id_producto='$idProducto' AND a.estatus=0";
                $series = $this->queryArray($myQuery);
                if($series['total']>0){
                    foreach ($series['rows'] as $k2 => $v2) {
                        $arrSeries[]=array('idSerie'=>$v2['id'].'-'.$v2['ida'], 'serie'=>'Serie: '.$v2['serie'].' ('.$v2['nombre'].')', 'serie2' => $v2['serie']);
                    }
                }else{

                }

            return $arrSeries;

        }

        function getLotes($idProducto,$caracteristicas)
        {
            $caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
            if($caracteristicas != '0'){
                    $carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
            }else{
                $carac='';
            }

            $myQuery = "SELECT a.id,a.no_lote from app_producto_lotes a
                inner join app_inventario_movimientos b on b.id_lote=a.id
                WHERE b.id_producto='$idProducto'
                group by a.id;";

            $pedimentos = $this->queryArray($myQuery);

            $arrPedis=array();
            foreach ($pedimentos['rows'] as $k => $v) {
 

                $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac." AND id_pedimento = 0 AND id_lote = ".$v['id']."  ) AS entradas,
@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac." AND id_pedimento = 0 AND id_lote = ".$v['id']."  ) AS salidas,
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
            
            return $arrPedis;

        }

        function getExistencias($idProducto,$caracteristicas)
        {
            $caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
            if($caracteristicas != '0'){
                    $carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
            }else{
                $carac='';
            }

            


                 $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac."  AND id_pedimento = 0 AND id_lote = 0  ) AS entradas,
@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac."   AND id_pedimento = 0 AND id_lote = 0  ) AS salidas,
(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
FROM app_almacenes a WHERE a.activo = 1
ORDER BY a.codigo_sistema;";

                $totpedis = $this->queryArray($myQuery2);
                $cant=0;
                foreach ($totpedis['rows'] as $k2 => $v2) {
                    //$cant+=$v2['cantidad'];

                    if($v2['cantidad']>0){
                        $arrPedis[]=array('idAlmacen'=>$v2['id'].'-'.$v2['cantidad'].'-#*-'.$v2['nombre'], 'cantidad'=>$v2['cantidad'], 'almacen'=>$v2['nombre']);
                    }
                }

                
            
            
            return $arrPedis;

        }

        function getPedimentos($idProducto,$caracteristicas)
        {

            $caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
            if($caracteristicas != '0'){
                    $carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
            }else{
                $carac='';
            }


            $myQuery = "SELECT a.id,a.no_pedimento from app_producto_pedimentos a
                inner join app_inventario_movimientos b on b.id_pedimento=a.id
                WHERE b.id_producto='$idProducto'
                group by a.id;";

            $pedimentos = $this->queryArray($myQuery);

            $arrPedis=array();
            foreach ($pedimentos['rows'] as $k => $v) {
 

                 $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac."  AND id_pedimento = ".$v['id']." AND id_lote = 0  ) AS entradas,
@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac."  AND id_pedimento = ".$v['id']." AND id_lote = 0  ) AS salidas,
(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
FROM app_almacenes a WHERE a.activo = 1
ORDER BY a.codigo_sistema;";

                $totpedis = $this->queryArray($myQuery2);
                $cant=0;
                foreach ($totpedis['rows'] as $k2 => $v2) {
                    //$cant+=$v2['cantidad'];

                    if($v2['cantidad']>0){
                        $arrPedis[]=array('idPedimento'=>$v['id'].'-'.$v2['id'].'-'.$v2['cantidad'].'-#*-'.$v['no_pedimento'].' ('.$v2['nombre'].')', 'cantidad'=>$v2['cantidad'], 'numero'=>'Pedimento: '.$v['no_pedimento'].' - '.$v2['nombre']);
                    }
                }

                
            }
            
            return $arrPedis;

        }

        function getPedimentosLotes($idProducto,$caracteristicas)
        {

            $caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
            if($caracteristicas != '0'){
                    $carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
            }else{
                $carac='';
            }


            $myQuery = "SELECT a.id,a.no_pedimento,c.no_lote,c.id as idlote from app_producto_pedimentos a
                inner join app_inventario_movimientos b on b.id_pedimento=a.id
                inner join app_producto_lotes c on c.id=b.id_lote
                WHERE b.id_producto='$idProducto'
                group by a.id;";

            $pedimentos = $this->queryArray($myQuery);

            $arrPedis=array();
            foreach ($pedimentos['rows'] as $k => $v) {
 

                 $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac."  AND id_pedimento = ".$v['id']." AND id_lote = ".$v['idlote']."  ) AS entradas,
@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac."  AND id_pedimento = ".$v['id']." AND id_lote = ".$v['idlote']."  ) AS salidas,
(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
FROM app_almacenes a WHERE a.activo = 1
ORDER BY a.codigo_sistema;";

                $totpedis = $this->queryArray($myQuery2);
                $cant=0;
                foreach ($totpedis['rows'] as $k2 => $v2) {
                    //$cant+=$v2['cantidad'];

                    if($v2['cantidad']>0){
                        $arrPedis[]=array('idPedimentoLote'=>$v['id'].'#'.$v['idlote'].'-'.$v2['id'].'-'.$v2['cantidad'].'-#*-'.$v['no_pedimento'].'/'.$v['no_lote'].' ('.$v2['nombre'].')', 'cantidad'=>$v2['cantidad'], 'numero'=>'Pedimento/Lote: '.$v['no_pedimento'].'/'.$v['no_lote'].' - '.$v2['nombre']);
                    }
                }

                
            }
            
            return $arrPedis;

        }

        function getPedimentosProd4($idProducto,$caracteristicas)
        {
            $caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
            if($caracteristicas != '0'){
                    $carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
            }else{
                $carac='';
            }

            $myQuery = "SELECT a.id,a.aduana, b.id_producto, b.id_almacen, b.id_recepcion, b.id_lote, a.no_pedimento from app_producto_pedimentos a
                inner join app_recepcion_datos b on b.id_pedimento=a.id
                inner join app_productos c on c.id=b.id_producto
                WHERE b.id_producto='$idProducto' and c.series=1
                group by a.id;";

            $pedimentos = $this->queryArray($myQuery);

            $arrPedis=array();
            foreach ($pedimentos['rows'] as $k => $v) {
 

                $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac." AND id_pedimento = ".$v['id']." AND id_lote = 0  ) AS entradas,
@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac." AND id_pedimento = ".$v['id']." AND id_lote = 0  ) AS salidas,
(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
FROM app_almacenes a WHERE a.activo = 1
ORDER BY a.codigo_sistema;";

                $totpedis = $this->queryArray($myQuery2);
                $cant=0;
                foreach ($totpedis['rows'] as $k2 => $v2) {
                    //$cant+=$v2['cantidad'];

                    if($v2['cantidad']>0){
                        $arrPedis[]=array('idPedimento'=>$v['id'].'-'.$v2['id'].'-'.$v2['cantidad'], 'cantidad'=>$v2['cantidad'], 'numero'=>'Pedimento: '.$v['no_pedimento'].' - '.$v2['nombre']);
                    }
                }

                
            }
            
            return $arrPedis;

        }
        function getPedimentosProd($idProducto,$caracteristicas)
        {
            $caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
            if($caracteristicas != '0'){
                    $carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
            }else{
                $carac='';
            }

            $myQuery = "SELECT a.id,a.aduana, b.id_producto, a.no_pedimento from app_producto_pedimentos a
                inner join app_inventario_movimientos b on b.id_pedimento=a.id
                inner join app_productos c on c.id=b.id_producto
                WHERE b.id_producto='$idProducto' and c.series=1
                group by a.id;";

            $pedimentos = $this->queryArray($myQuery);

            $arrPedis=array();
            foreach ($pedimentos['rows'] as $k => $v) {
 

                $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac." AND id_pedimento = ".$v['id']." AND id_lote = 0  ) AS entradas,
@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac." AND id_pedimento = ".$v['id']." AND id_lote = 0  ) AS salidas,
(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
FROM app_almacenes a WHERE a.activo = 1
ORDER BY a.codigo_sistema;";

                $totpedis = $this->queryArray($myQuery2);
                $cant=0;
                foreach ($totpedis['rows'] as $k2 => $v2) {
                    //$cant+=$v2['cantidad'];

                    if($v2['cantidad']>0){
                        $arrPedis[]=array('idPedimento'=>$v['id'].'-'.$v2['id'].'-'.$v2['cantidad'], 'cantidad'=>$v2['cantidad'], 'numero'=>'Pedimento: '.$v['no_pedimento'].' - '.$v2['nombre']);
                    }
                }

                
            }
            
            return $arrPedis;
        }
        function getSeriesPed($idProd,$pedimentos)
        {
            
            $pedis='';
            $alms='';
            $aserie=array();
            $arrSeries=array();
            foreach ($pedimentos as $k => $v) {
                $expguion=explode('-', $v);
                $pedis=$expguion[0];
                $alms=$expguion[1];

                $myQuery = "SELECT a.*, b.nombre from app_producto_serie a 
                inner join app_almacenes b on b.id='$alms'
                where a.id_producto='$idProd' and a.id_almacen='$alms' and a.estatus=0 and a.id_pedimento='$pedis'";
                $series = $this->queryArray($myQuery);
                if($series['total']>0){
                    foreach ($series['rows'] as $k2 => $v2) {
                        $arrSeries[]=array('idSerie'=>$v2['id'].'-'.$pedis.'-'.$alms.'|', 'serie'=>'Serie: '.$v2['serie'].' ('.$v2['nombre'].')');
                    }
                }else{

                }

            }

       

             return $arrSeries;
            
        }
        function addProductoReq($idProducto, $idProveedor)//Eliminar
        {
            $myQuery = "SELECT a.id, a.codigo, if(a.descripcion_corta='',a.nombre,a.descripcion_corta) as descripcion_corta, b.costo, c.codigo as moneda FROM app_productos a
            INNER JOIN app_costos_proveedor b on b.id_producto=a.id AND b.id_proveedor='$idProveedor'
            INNER JOIN cont_coin c on c.coin_id=b.id_moneda
            WHERE a.id='$idProducto';";

            $myQuery = "SELECT a.id, a.codigo, if(a.descripcion_corta='',a.nombre,a.descripcion_corta) as descripcion_corta, c.codigo as moneda, if(d.costo is null,b.costo,d.costo) as costo FROM app_productos a
            INNER JOIN app_costos_proveedor b on b.id_producto=a.id AND b.id_proveedor='$idProveedor'
            INNER JOIN cont_coin c on c.coin_id=b.id_moneda

            left join (Select d.id_producto, d.costo from app_ocompra_datos d
                               left join app_ocompra e on e.id=d.id_ocompra AND e.id_proveedor='$idProveedor' 
                               WHERE (e.activo=1 OR e.activo=4 OR e.activo=5) ORDER BY d.id desc) d on d.id_producto=a.id
            WHERE a.id='$idProducto' group by a.id;";

            $producto = $this->query($myQuery);
            return $producto;
        }
        function addListasPrecio($idProducto,$idCliente)
        {
            $myQuery = "SELECT if(d.id is null,0,d.id) as tienelista
, d.nombre, d.clave, d.porcentaje, d.descuento, d.id as idlista, if(c.id is null,0,c.id) as prodvalido, a.precio,
if(d.descuento=1,a.precio-((a.precio)*(d.porcentaje/100)),a.precio+((a.precio)*(d.porcentaje/100))) as valorpre
 FROM app_productos a
left join app_lista_precio_prods c on c.id_producto=a.id
left join app_lista_precio d on d.id=c.id_lista and d.activo=1
left join comun_cliente b on b.id='$idCliente' and d.id=b.id_lista_precios
WHERE a.id='$idProducto';";

            $addslistas = $this->query($myQuery);
            return $addslistas;

        }
        function addProductoVenta($idProducto,$idCliente)
        {
          

            $myQuery = "SELECT a.id, a.codigo, if(a.descripcion_corta='',a.nombre,a.descripcion_corta) as descripcion_corta, a.precio as costo, if(d.id is null,0,d.id) as tienelista, d.nombre, d.clave, d.porcentaje, d.descuento, d.id as idlista, if(c.id is null,0,c.id) as prodvalido, x.clave, a.tipo_producto FROM app_productos a
left join comun_cliente b on b.id='$idCliente'
left join app_lista_precio d on d.id=b.id_lista_precios and d.activo=1
left join app_lista_precio_prods c on c.id_lista=d.id and c.id_producto=a.id
INNER join app_unidades_medida x on x.id=a.id_unidad_venta
WHERE a.id='$idProducto'  group by a.id;";


            /*$myQuery= "SELECT a.id, a.codigo, a.descripcion_corta, a.precio as costo, if(d.id is null,0,d.id) as tienelista
, d.nombre, d.clave, d.porcentaje, d.descuento, d.id as idlista, if(c.id is null,0,c.id) as prodvalido
, x.clave FROM app_productos a
left join comun_cliente b on b.id='$idCliente'
left join app_lista_precio d on d.id=b.id_lista_precios and d.activo=1
left join app_lista_precio_prods c on c.id_lista=d.id and c.id_producto=a.id
INNER join app_unidades_medida x on x.id=a.id_unidad_venta
WHERE a.id='$idProducto'
union all
SELECT a.id, a.codigo, a.descripcion_corta, a.precio as costo, if(d.id is null,0,d.id) as tienelista
, d.nombre, d.clave, d.porcentaje, d.descuento, d.id as idlista, if(c.id is null,0,c.id) as prodvalido
, x.clave FROM app_productos a
left join app_lista_precio_prods c on c.id_producto=a.id
left join app_lista_precio d on d.id=c.id_lista and d.activo = 1
INNER join app_unidades_medida x on x.id=a.id_unidad_venta
WHERE a.id='$idProducto'
;";*/

            $producto = $this->query($myQuery);
            return $producto;
        }
        function getCaracteristicasProdH($cp){
            $myQuery = "SELECT id,nombre FROM app_caracteristicas_hija
            WHERE id_caracteristica_padre='$cp' order by id;";
            $producto = $this->query($myQuery);
            return $producto;
        }
        function getCaracteristicasProdP($idProducto)
        {
            $myQuery = "SELECT e.id as idcp, e.nombre as nombrecp
            FROM  app_producto_caracteristicas d
            LEFT JOIN app_caracteristicas_padre e on e.id=d.id_caracteristica_padre
            WHERE d.id_producto='$idProducto' order by idcp;";
            $producto = $this->query($myQuery);
            return $producto;
        }
        function getProvProducto($idProveedor)
        {
            $myQuery = "SELECT a.id_producto, b.id, b.nombre as descripcion_corta FROM app_producto_proveedor a 
            INNER JOIN app_productos b on b.id=a.id_producto
            WHERE a.id_proveedor='$idProveedor'";
            $producto = $this->query($myQuery);
            return $producto;
        }
        function a_getProdMoneda($idmoneda)
        {
            $myQuery = "SELECT a.id, CONCAT(a.codigo, ' - ', a.nombre) as descripcion_corta, count(d.id) as tc FROM app_productos a
            inner JOIN cont_coin c on c.coin_id=a.id_moneda
            left join app_producto_caracteristicas d on d.id_producto=a.id
            WHERE a.id_moneda='$idmoneda' and a.status=1  group by a.id;";
            $producto = $this->query($myQuery);
            return $producto;
        }
        function getTipoGasto()
        {
            $myQuery = "SELECT id,CONCAT(nombreclasificador,' (',codigo,')') as nombreclasificador FROM bco_clasificador WHERE idtipo=1 AND idNivel=1 and activo='-1' ORDER BY nombreclasificador";
            $tipoGasto = $this->query($myQuery);
            return $tipoGasto;
        }

        function getAlmacen()
        {
            $myQuery = "SELECT id,nombre FROM app_almacenes WHERE (id_almacen_tipo=1 OR id_almacen_tipo=5)  ORDER BY id";
            $almacenes = $this->query($myQuery);
            return $almacenes;
        }

        function getAlmacenes()
        {
            $myQuery = "SELECT * FROM app_almacenes ORDER BY id_almacen_tipo asc, codigo_sistema asc;";
            $almacenes = $this->queryArray($myQuery);
            return $almacenes;
        }

        function getAlmacenes2()
        {
            $myQuery = "SELECT id,nombre FROM app_almacenes where es_consignacion=0 ORDER BY id_almacen_tipo asc, codigo_sistema asc;";
            $almacenes = $this->queryArray($myQuery);
            return $almacenes;
        }

        function deleteReq($idReq)
        {
            $myQuery = "UPDATE app_requisiciones_venta SET activo=2 WHERE id='$idReq' AND id!='1';";
            $update = $this->query($myQuery);
            return $update;
        }

        function solacla($idReq)
        {
            $myQuery = "UPDATE app_requisiciones SET activo=6 WHERE id='$idReq';";
            $update = $this->query($myQuery);

            $myQuery = "UPDATE app_ocompra SET activo=6 WHERE id_requisicion='$idReq';";
            $update = $this->query($myQuery);
            return $update;
        }

        function productosTicket($idprod){
            $myQuery = "SELECT a.nombre, a.codigo, a.id FROM app_productos a where id='$idprod';";
            $producto = $this->queryArray($myQuery);
            return $producto['rows'][0];
        }

        function saveDevoCliente($sessprods,$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$id_rec,$cliente)
        {
            
            //echo $idOC;
            date_default_timezone_set("Mexico/General");
            $date_recep=$date_recep.' '.date('H:i:s');
            $hoy=date('Y-m-d H:i:s'); 
            
            $myQuery = "INSERT INTO app_devolucioncli (id_ov,id_envio,id_encargado,observaciones,estatus,fecha_devolucion,activo,xmlfile,desc_concepto,subtotal,total,id_consignacion) VALUES ('$idOC','$id_rec',1,'Observaciones',1,'$hoy','1','','$desc_concepto','$ist','$it',0);";

            $last_id = $this->insert_id($myQuery);

            if($last_id>0){

                $myQuery = "INSERT INTO app_pagos (cobrar_pagar,id_prov_cli,cargo,abono,fecha_pago,concepto,id_forma_pago,id_moneda,tipo_cambio,origen) VALUES ('0','$cliente','0','$it','$hoy','Devolucion a proveedor-".$last_id."','99','1','1',1);";
                $query = $this->query($myQuery);


                $cadlotes='';
                $cadmodo='';
                $productos = explode(',#', $idsProductos);
                $cadrdcardex='';
                $upseries=array();
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=trim($exp[0]);
                    $cant=$exp[1];
                    $idalmacen=$exp[2];
                    $especial=$exp[3];
                    $tipp=$exp[4];
                    $caracteristica=$exp[5];
                    $last1=0;
                    $last2=0;
                    
                    $seriereemp=9990000;
                    //$cadseriesrastro='';

                    if($cant<=0){
                        continue;
                    }

                    if($especial==0){
                        //$cadrd.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."'),";
                    }elseif($especial==1){

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];


                        $myQuery = "SELECT id FROM app_producto_lotes WHERE no_lote='$nlote' and fecha_fabricacion='".$flote." 00:00:00' and fecha_caducidad='".$flotec." 00:00:00';";
                        $resultlote = $this->queryArray($myQuery);
                        $last1 = $resultlote['rows'][0]['id'];

                    }elseif($especial==4){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "SELECT id FROM app_producto_pedimentos WHERE no_pedimento='$npedi' and aduana='$aduana' and no_aduana='$naduana' and tipo_cambio='$tcambio' and fecha_pedimento='".$fpedi." 00:00:00' ";
                        $resultpedi = $this->queryArray($myQuery);
                        $last2 = $resultpedi['rows'][0]['id'];

                    }elseif($especial==5){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];

                        $myQuery = "SELECT id FROM app_producto_pedimentos WHERE no_pedimento='$npedi' and aduana='$aduana' and no_aduana='$naduana' and tipo_cambio='$tcambio' and fecha_pedimento='".$fpedi." 00:00:00' ";
                        $resultpedi = $this->queryArray($myQuery);
                        $last2 = $resultpedi['rows'][0]['id'];


                        $myQuery = "SELECT id FROM app_producto_lotes WHERE no_lote='$nlote' and fecha_fabricacion='".$flote." 00:00:00' and fecha_caducidad='".$flotec." 00:00:00';";
                        $resultlote = $this->queryArray($myQuery);
                        $last1 = $resultlote['rows'][0]['id'];
                        //$last1 = $this->insert_id($myQuery);

                    }elseif($especial==2){
                        
                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];


                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $myQuerycost = "SELECT id from app_producto_serie where serie='$t' and id_producto='$idprod' and id_venta='$id_rec' and id_pedimento=0;";
                            $sid = $this->queryArray($myQuerycost);
                            $idser = $sid['rows'][0]['id'];
                         
                            //$cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."'),";
                            $cadseriesrastro.="('".$idser."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$idser,'seriereemp'=>$seriereemp);
                            $seriereemp++;

                            $myQuery = "UPDATE app_producto_serie SET id_venta=0, estatus=0 WHERE id='".$idser."' ";
                            $query = $this->query($myQuery);

                        }

                    

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }elseif($especial==3){

                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "SELECT id FROM app_producto_pedimentos WHERE no_pedimento='$npedi' and aduana='$aduana' and no_aduana='$naduana' and tipo_cambio='$tcambio' and fecha_pedimento='".$fpedi." 00:00:00' ";
                        $resultpedi = $this->queryArray($myQuery);
                        $last2 = $resultpedi['rows'][0]['id'];

                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                           $myQuerycost = "SELECT id from app_producto_serie where serie='$t' and id_producto='$idprod' and id_venta='$id_rec' and id_pedimento='$last2';";
                            $sid = $this->queryArray($myQuerycost);
                            $idser = $sid['rows'][0]['id'];
                            //$cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."','".$last2."'),";
                            $cadseriesrastro.="('".$idser."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$idser,'seriereemp'=>$seriereemp);
                            $seriereemp++;

                            $cadseriestrim = trim($cadseries, ',');
                            $myQuery = "UPDATE app_producto_serie SET id_venta=0, estatus=0 WHERE id='".$idser."' ";
                            $query = $this->query($myQuery);
                        }

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);
                        

                    }

                    $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$cant."','".$last1."','".$last2."',1,'".$idalmacen."','".$caracteristica."'),";

                    $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                    $costprods = $this->queryArray($myQuerycost);
                    $elunit = $costprods['rows'][0]['costo'];
                    $elcost = ($elunit*1)*($cant*1);

                    //if($inventariable==1){
                      //  if($tipp==1){
                        /*$myQueryUnid = "SELECT b.id, b.clave, b.factor as fo, c.clave, c.factor as fd from app_productos a 
                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
                            where a.id='$idprod';";
                        $costunids = $this->queryArray($myQueryUnid);
                        $cantidadreal=(($cant*1)*$costunids['rows'][0]['fo'])/$costunids['rows'][0]['fd'];
                        */

                        $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                        $caracteristicareplace=addslashes($caracteristicareplace);
                        $cadrdcardex.="('".$idprod."','".$caracteristicareplace."','".$last2."','".$last1."','".$cant."','".$elcost."',0,'".$idalmacen."','".$hoy."','3','1','".$elunit."','Devolucion de venta / devolucion -".$last_id."','1'),";
                      //  }
                    //}
                    

                }

                $cadrdtrim = trim($cadrd, ',');
                $myQuery = "INSERT INTO app_devolucioncli_datos (id_ov,id_envio,id_producto,id_devolucion,cantidad,id_lote,id_pedimento,estatus,id_almacen,caracteristica) VALUES ".$cadrdtrim.";";
                $query = $this->query($myQuery);

                //if($inventariable==1){
                    $cadrdcardextrim = trim($cadrdcardex, ',');
                    $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) VALUES ".$cadrdcardextrim.";";
                    $query = $this->query($myQuery);
                    //$lastmov = $this->insert_id($myQuery);
                //}
                foreach ($upseries as $ks => $vs) {
                    $myQuery = "UPDATE app_producto_serie_rastro SET id_mov =(SELECT id FROM app_inventario_movimientos WHERE referencia='Devolucion de venta / devolucion -".$last_id."' AND id_producto='".$vs['idprod']."' ) WHERE id_serie='".$vs['serie']."' and id_mov=0 and id_almacen='".$vs['idalmacen']."' and fecha_reg='".$hoy."' ;";
                    $query = $this->query($myQuery);
                }



            }

            $myQuery = "UPDATE app_requisiciones SET activo='$activo' WHERE id in (SELECT id_requisicion FROM app_ocompra WHERE id='$idOC');";
            $this->query($myQuery);


            $myQuery = "UPDATE app_ocompra SET activo='$activo' WHERE id='$idOC';";
            $this->query($myQuery);

            return $last_id;
            
        
        }

        function saveRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$almacen,$obs,$cliente,$ist,$it,$iduserlog,$total,$monto_desc,$descc)        
        {

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 


            $myQuery = "INSERT INTO app_requisiciones_venta (id_cliente,id_solicito,id_tipogasto,id_almacen,id_moneda,id_proveedor,urgente,inventariable,observaciones,fecha,fecha_entrega,activo,tipo_cambio,pr,subtotal,total,id_usuario,fecha_creacion,total_sin_desc,monto_desc,descc) VALUES ('$cliente','$solicitante','$tipogasto','$almacen','$moneda','$proveedor','$urgente','$inventariable','".$this->nl2brCH($obs)."','$fechahoy','$fechaentrega',0,'$moneda_tc',1,'$ist','$it','$iduserlog','$creacion','$total','$monto_desc','$descc');";

            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $precio=$exp[2];
                    $idlista=$exp[3];
                    $caracteristica=$exp[4];
                    $oldp=$exp[5];                
                    $tipodesc=$exp[6];                    
                    $montod=$exp[7];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."','".$oldp."','".$tipodesc."','".$montod."'),";
                    //$cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica,precio_sin_desc,tipo_desc,monto_desc) VALUES ".$cadtrim.";";
                //$myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            /// enviar corro con pdf
            return $last_id;

        }

        function saveRequisicionCaja($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$almacen,$obs,$cliente,$ist,$it,$iduserlog,$total,$monto_desc,$descc)        
        {

   

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 


            $myQuery = "INSERT INTO app_requisiciones_venta (id_cliente,id_solicito,id_tipogasto,id_almacen,id_moneda,id_proveedor,urgente,inventariable,observaciones,fecha,fecha_entrega,activo,tipo_cambio,pr,subtotal,total,id_usuario,fecha_creacion,total_sin_desc,monto_desc,descc) VALUES ('$cliente','$solicitante','$tipogasto','$almacen','$moneda','$proveedor','$urgente','$inventariable','".$this->nl2brCH($obs)."','$fechahoy','$fechaentrega',6,'$moneda_tc',1,'$ist','$it','$iduserlog','$creacion','$total','$monto_desc','$descc');";

            $last_id = $this->insert_id($myQuery);

            if($last_id>0){


                $myQuery = "INSERT INTO cotpe_pedido (idCliente,total,idempleado,fecha, idCotizacion, observaciones, status, idMoneda, tipo_cambio, descuentoGeneral, descCant, origen) VALUES ('$cliente', '$it', '$id_usuario', '$fechahoy', '$last_id', '$obs', '1', '$moneda', '$moneda_tc',  '$descc', '$monto_desc', 1);";
                $last_id_cotpe = $this->insert_id($myQuery);


                $cadcotpe='';
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $precio=$exp[2];
                    $idlista=$exp[3];
                    $caracteristica=$exp[4];
                    $oldp=$exp[5];                
                    $tipodesc=$exp[6];                    
                    $montod=$exp[7];
                    if($tipodesc==1){
                        $eldesc='%';
                    }else{
                        $eldesc='$';
                    }
                    $cadcotpe.="('".$last_id_cotpe."', '".$idprod."', '".$cant."', '1', '".$precio."', '".$cant*$precio."', '".$caracteristica."', '".$eldesc."' ),";

                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."','".$oldp."','".$tipodesc."','".$montod."'),";
                    //$cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."'),";
                }
                $cadtrim = trim($cad, ',');
                $cadtrim2 = trim($cadcotpe, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica,precio_sin_desc,tipo_desc,monto_desc) VALUES ".$cadtrim.";";
                //$myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);

                $myQuery = "INSERT INTO cotpe_pedido_producto (idPedido,idProducto,cantidad,idunidad,precio,importe,caracteristicas,tipoDes) VALUES ".$cadtrim2.";";
                //$myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            /// enviar corro con pdf
            return $last_id;

        }

        function getSerieVenta($idprod,$idventa){
            
            $myQuery = "SELECT serie from app_producto_serie where id_venta='$idventa' and id_producto='$idprod' order by id desc;";
            $series = $this->queryArray($myQuery);

            if($series["total"] > 0) {
                $r='';
                foreach ($series['rows'] as $k => $v) {
                   $r.=$v['serie'].',';
                }
                $r=trim($r,',');
                $final=' Series: ('.$r.')';
            }else{
                $final='';
            }
                   
            return $final;
        }

        function caracteristicaReq($array){
            $exparray=explode(',', $array);
            $caras='';
            foreach ($exparray as $k => $v) {
                $expv=explode('=>', $v);
                $ip=$expv[0];
                $ih=$expv[1];
                $myQuery = "SELECT concat('( ',a.nombre,': ',b.nombre,' )') as dcar FROM app_caracteristicas_padre a
                LEFT JOIN app_caracteristicas_hija b on b.id='$ih'
                WHERE a.id='$ip';";
                $producto = $this->queryArray($myQuery);
                $caras.= $producto['rows'][0]['dcar'];
                
            }
            
            return $caras;
        }

        function modifyRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$idrequi,$almacen,$obs,$cliente,$ist,$it,$iduserlog,$total,$monto_desc,$descc)
        {

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 

            $myQuery = "UPDATE app_requisiciones_venta SET id_cliente='$cliente', id_solicito='$solicitante', id_tipogasto='$tipogasto', id_almacen='$almacen', id_moneda='$moneda', id_proveedor='$proveedor', urgente='$urgente', inventariable='$inventariable' , observaciones='$obs', fecha='$fechahoy', fecha_entrega='$fechaentrega', activo=0, tipo_cambio='$moneda_tc', subtotal='$ist', total='$it', id_usuario='$iduserlog', fecha_creacion='$creacion', total_sin_desc='$total', monto_desc='$monto_desc', descc='$descc' WHERE id='$idrequi'  ";
            $this->query($myQuery);

            $myQuery = "DELETE FROM app_requisiciones_datos_venta WHERE id_requisicion='$idrequi';";
            $this->query($myQuery);

            $last_id = $idrequi;
            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $precio=$exp[2];
                    $idlista=$exp[3];
                    $caracteristica=$exp[4];
                    $oldp=$exp[5];                
                    $tipodesc=$exp[6];                    
                    $montod=$exp[7];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."','".$oldp."','".$tipodesc."','".$montod."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica,precio_sin_desc,tipo_desc,monto_desc) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            return $idrequi;

        }



        function modifyOrden($idsProductos,$solicitante,$tipogasto,$moneda,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$idactivo,$obs,$ist,$it,$cadimps,$cliente,$iduserlog,$total,$monto_desc,$descc)
        {

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 

            session_start();
            $idusr = $_SESSION['accelog_idempleado'];

            $myQuery = "UPDATE app_requisiciones_venta SET id_solicito='$solicitante', id_tipogasto='$tipogasto', id_almacen='$almacen', id_moneda='$moneda', id_proveedor='$proveedor', urgente='$urgente', inventariable='$inventariable' , observaciones='$obs', fecha='$fechahoy', fecha_entrega='$fechaentrega', activo='$idactivo', tipo_cambio='$moneda_tc', id_cliente='$cliente', subtotal='$ist', total_sin_desc='$it', total_sin_desc='$total', monto_desc='$monto_desc', descc='$descc'  WHERE id='$idrequi'  ";
            $this->query($myQuery);

            $myQuery = "DELETE FROM app_requisiciones_datos_venta WHERE id_requisicion='$idrequi';";
            $this->query($myQuery);

            $last_id = $idrequi;
            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $precio=$exp[2];
                    $idlista=$exp[3];
                    $caracteristica=$exp[4];
                    $oldp=$exp[5];                
                    $tipodesc=$exp[6];                    
                    $montod=$exp[7];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."','".$oldp."','".$tipodesc."','".$montod."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica,precio_sin_desc,tipo_desc,monto_desc) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }


            $myQuery = "SELECT id from app_oventa WHERE id_requisicion='$idrequi';";
            $res = $this->query($myQuery);

            if($res->num_rows>0){
                $row = $res->fetch_array();
                $last_id2=$row['id'];
                $myQuery = "UPDATE app_oventa SET id_cliente='$cliente',id_usrcompra='$solicitante',observaciones='$obs',fecha_entrega='$fechaentrega',activo='$idactivo',subtotal='$ist',total='$it', fecha='$fechahoy', id_usuario='$idusr', fecha_creacion='$creacion' WHERE id_requisicion='$idrequi';";
                $this->query($myQuery);

            }else{
                $myQuery = "INSERT INTO app_oventa (id_cliente,id_usrcompra,observaciones,fecha_entrega,activo,id_requisicion,subtotal,total,fecha,id_usuario,fecha_creacion) VALUES ('$cliente','$solicitante','$obs','$fechaentrega',0,'$last_id','$ist','$it','$fechahoy','$idusr','$creacion');";
                $last_id2 = $this->insert_id($myQuery);

            }

            $myQuery = "DELETE FROM app_oventa_datos WHERE id_oventa='$last_id2';";
            $this->query($myQuery);

            if($last_id2>0){
                $exorig=array();
                $expcadimps = explode('|', $cadimps);
                foreach ($expcadimps as $aa => $bb) {
                    $id_prod=explode('#', $bb);
                    @$exorig[$id_prod[0]]=$id_prod[1];
                }

                $cad='';
                $productos = explode(',#', $idsProductos);
                $t=0;
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=trim($exp[0]);
                    $cant=trim($exp[1]);
                    $costo=trim($exp[2]);
                    $idlista=trim($exp[3]);
                    $caracteristica=trim($exp[4]);
                    $cad.="('".$last_id2."','".$idprod."','sestmp','1','1','1','".$cant."','".$costo."','".$exorig[$idprod.'-'.$caracteristica]."','".$idlista."','".$caracteristica."'),";
                    $t++;
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_oventa_datos (id_oventa,id_producto,ses_tmp,estatus,activo,almacen,cantidad,costo,impuestos,id_lista,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            return $idrequi;

        }

        function ordenVentaInv($idsProductos){
            $productos = explode(',#', $idsProductos);
            $arreglo=array();
            $listaprods='';
            $totalp=0;
            foreach ($productos as $k => $v) {
                $totalp++;
                $exp=explode('>#', $v);
                $idprod=$exp[0];
                $cant=$exp[1];
                $precio=$exp[2];
                $idlista=$exp[3];
                $caracteristica=$exp[4];

                
                //$cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."'),";

                $idprod=trim($idprod);
                



                $nreq = $this->queryArray("SELECT series,lotes,pedimentos,tipo_producto from app_productos where id='$idprod';");
                $ser = $nreq['rows'][0]['series'];
                $lot = $nreq['rows'][0]['lotes'];
                $ped = $nreq['rows'][0]['pedimentos']; 
                $tp = $nreq['rows'][0]['tipo_producto']; 

                if($tp==2){
                    continue;
                }        

                if($ser==0 && $lot==0 && $ped==0){
                    $rst = $this->getExistencias($idprod,$caracteristica);
                    $totalcant=0;
                    foreach ($rst as $k2 => $v2) {
                        $totalcant+=$v2['cantidad'];
                    }
                    //echo 'xxx-'.$totalcant;
                    if($cant*1>$totalcant*1){
                        $listaprods.=$idprod.',';
                        $cantdif=($cant*1)-($totalcant*1);
                        $arreglo['data'][]=array('idProd'=>$idprod,
                            'cantov'=>$cant,
                            'cantinv'=>$totalcant,
                            'cantdif'=>$cantdif,
                            'car'=>$caracteristica);
                    }

                }
                if($ser==0 && $lot==0 && $ped==1){
                    $rst = $this->getPedimentos($idprod,$caracteristica);
                    $totalcant=0;
                    foreach ($rst as $k2 => $v2) {
                        $totalcant+=$v2['cantidad'];
                    }
                    //echo 'ppp-'.$totalcant;
                    if($cant*1>$totalcant*1){
                        $listaprods.=$idprod.',';
                        $cantdif=($cant*1)-($totalcant*1);
                        $arreglo['data'][]=array('idProd'=>$idprod,
                            'cantov'=>$cant,
                            'cantinv'=>$totalcant,
                            'cantdif'=>$cantdif,
                            'car'=>$caracteristica);
                    }
                }
                if($ser==0 && $lot==1 && $ped==0){
                    $rst = $this->getLotes($idprod,$caracteristica);
                    $totalcant=0;
                    foreach ($rst as $k2 => $v2) {
                        $totalcant+=$v2['cantidad'];
                    }
                    //echo 'lll-'.$totalcant;
                    if($cant*1>$totalcant*1){
                        $listaprods.=$idprod.',';
                        $cantdif=($cant*1)-($totalcant*1);
                        $arreglo['data'][]=array('idProd'=>$idprod,
                            'cantov'=>$cant,
                            'cantinv'=>$totalcant,
                            'cantdif'=>$cantdif,
                            'car'=>$caracteristica);
                    }
                }
                if($ser==0 && $lot==1 && $ped==1){
                    $rst = $this->getPedimentosLotes($idprod,$caracteristica);
                    $totalcant=0;
                    foreach ($rst as $k2 => $v2) {
                        $totalcant+=$v2['cantidad'];
                    }
                    //echo '2l2p-'.$totalcant;
                    if($cant*1>$totalcant*1){
                        $listaprods.=$idprod.',';
                        $cantdif=($cant*1)-($totalcant*1);
                        $arreglo['data'][]=array('idProd'=>$idprod,
                            'cantov'=>$cant,
                            'cantinv'=>$totalcant,
                            'cantdif'=>$cantdif,
                            'car'=>$caracteristica);
                    }
                }
                if($ser==1 && $lot==0 && $ped==0){
                    $rst = $this->getSeriesProd($idprod);
                    $totalcant=0;
                    foreach ($rst as $k2 => $v2) {
                        $totalcant+=1;
                    }
                    //echo 'serprod-'.$totalcant;
                    if($cant*1>$totalcant*1){
                        $listaprods.=$idprod.',';
                        $cantdif=($cant*1)-($totalcant*1);
                        $arreglo['data'][]=array('idProd'=>$idprod,
                            'cantov'=>$cant,
                            'cantinv'=>$totalcant,
                            'cantdif'=>$cantdif,
                            'car'=>$caracteristica);
                    }
                }

                if($ser==1 && $lot==0 && $ped==1){
                    $rst = $this->getSeriesProd($idprod);
                    $totalcant=0;
                    foreach ($rst as $k2 => $v2) {
                        $totalcant+=1;
                    }
                    //echo 'serprod-'.$totalcant;
                    if($cant*1>$totalcant*1){


                        $rst = $this->getPedimentos($idprod,$caracteristica);
                        $totalcant=0;
                        foreach ($rst as $k2 => $v2) {
                            $totalcant+=$v2['cantidad'];
                        }
                        //echo 'ppp-'.$totalcant;
                        if($cant*1>$totalcant*1){
                            $listaprods.=$idprod.',';
                            $cantdif=($cant*1)-($totalcant*1);
                            $arreglo['data'][]=array('idProd'=>$idprod,
                                'cantov'=>$cant,
                                'cantinv'=>$totalcant,
                                'cantdif'=>$cantdif,
                                'car'=>$caracteristica);
                        }
                        /*
                        $listaprods.=$idprod.',';
                        $cantdif=($cant*1)-($totalcant*1);
                        $arreglo['data'][]=array('idProd'=>$idprod,
                            'cantov'=>$cant,
                            'cantinv'=>$totalcant,
                            'cantdif'=>$cantdif,
                            'car'=>$caracteristica);
                            */
                    }
                }

                
           /*     if($ser==1 && $lot==0 && $ped==1){
                    $rst = $this->getSeriesProd($idprod);
                    var_dump($rst);
                    $totalcant=0;
                    foreach ($rst as $k2 => $v2) {
                        $totalcant+=1;
                    }
                    echo 'serprod-'.$totalcant;
                }   */     
            }

            $listaprods=trim($listaprods,',');
            $listaprodssql=explode(',', $listaprods);
            $resultprodsqlcount = array_unique($listaprodssql);
            $resultprodsql = implode(',', $resultprodsqlcount);
            $totalp=count($resultprodsqlcount);

                $provs = $this->queryArray("SELECT b.idPrv, b.razon_social from app_producto_proveedor a 
            inner join mrp_proveedor b on b.idPrv=a.id_proveedor
            where a.id_producto in ($listaprods)
            group by a.id_proveedor
            having count(distinct a.id_producto) = ".$totalp.";");
                if($provs["total"] > 0) {
                    $provss='<option value="0">Seleccione</option>';
                    $arrayjsons=array();
                    foreach ($provs['rows'] as $kx => $vx) {
                        $provss.='<option value="'.$vx['idPrv'].'">'.$vx['razon_social'].'</option>';
                    }
                    $arreglo['prov']=$provss;
                    
                }else{
                    $arreglo['prov']='0';
                }
            $arreglo['ids']=$listaprods;
            
            return $arreglo;
        }

        function costosCompraExpres($idProv,$ids){
            $prods=explode(',', $ids);
            $costos=array();
            foreach ($prods as $k => $v) {
                $myQuery = "SELECT b.costo as costo FROM app_productos a
                INNER JOIN app_costos_proveedor b on b.id_producto=a.id AND b.id_proveedor='$idProv'
                WHERE a.id='$v' group by a.id order by b.id desc limit 1;";
                $costo = $this->queryArray($myQuery);
                $prodcosto = $costo['rows'][0]['costo'];
                $costos[]=array('idprod'=>$v,'idprov'=>$idProv,'costo'=>$prodcosto);
            }

            return $costos;
        }

        function saveOrden($idsProductos,$solicitante,$tipogasto,$moneda,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$idactivo,$obs,$ist,$it,$cadimps,$cliente,$iduserlog,$total,$monto_desc,$descc)
        {
            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 

            session_start();
            $idusr = $_SESSION['accelog_idempleado'];

    
            $myQuery = "INSERT INTO app_requisiciones_venta (id_solicito,id_tipogasto,id_almacen,id_moneda,id_proveedor,urgente,inventariable,observaciones,fecha,fecha_entrega,activo,tipo_cambio,id_cliente,subtotal,total,id_usuario,fecha_creacion,total_sin_desc,monto_desc,descc) VALUES ('$solicitante','$tipogasto','$almacen','$moneda','$proveedor','$urgente','$inventariable','".$this->nl2brCH($obs)."','$fechahoy','$fechaentrega','$idactivo','$moneda_tc','$cliente','$ist','$it','$idusr','$creacion','$total','$monto_desc','$descc');";
            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $precio=$exp[2];
                    $idlista=$exp[3];
                    $caracteristica=$exp[4];
                    $oldp=$exp[5];                
                    $tipodesc=$exp[6];                    
                    $montod=$exp[7];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$precio."','".$idlista."','".$caracteristica."','".$oldp."','".$tipodesc."','".$montod."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos_venta (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,precio,id_lista,caracteristica,precio_sin_desc,tipo_desc,monto_desc) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            
            $myQuery = "INSERT INTO app_oventa (id_cliente,id_usrcompra,observaciones,fecha_entrega,activo,id_requisicion,subtotal,total,fecha,id_usuario,fecha_creacion) VALUES ('$cliente','$solicitante','$obs','$fechaentrega','$idactivo','$last_id','$ist','$it','$fechahoy','$idusr','$creacion');";
            $last_id2 = $this->insert_id($myQuery);



            if($last_id2>0){
                $exorig=array();
                $expcadimps = explode('|', $cadimps);
                foreach ($expcadimps as $aa => $bb) {
                    $id_prod=explode('#', $bb);
                    @$exorig[$id_prod[0]]=$id_prod[1];
                }

                $cad='';
                $productos = explode(',#', $idsProductos);
                $t=0;
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=trim($exp[0]);
                    $cant=trim($exp[1]);
                    $costo=trim($exp[2]);
                    $idlista=trim($exp[3]);
                    $caracteristica=trim($exp[4]);
                    $upseries=array();
                    $cad.="('".$last_id2."','".$idprod."','sestmp','1','1','1','".$cant."','".$costo."','".$exorig[$idprod.'-'.$caracteristica]."','".$idlista."','".$caracteristica."'),";
                    $t++;
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_oventa_datos (id_oventa,id_producto,ses_tmp,estatus,activo,almacen,cantidad,costo,impuestos,id_lista,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            return $last_id;

        }

        function tipoCosteoProd($idProd){
            //Query para obtener el numero de requisicion nuevo (ultimo id + 1)
            $myQuery = "SELECT id_tipo_costeo from app_productos where id='$idProd';";
            $nreq = $this->queryArray($myQuery);
            $tc = $nreq['rows'][0]['id_tipo_costeo'];
            return $tc;
        }

        public function costosSeries($idSerie,$idProd){
            $myQuery = "SELECT ps.id, ps.serie, m.costo FROM app_inventario_movimientos m 
                        RIGHT JOIN app_producto_serie_rastro s ON s.id_mov = m.id
                        INNER JOIN app_producto_serie ps ON ps.id = s.id_serie
                        WHERE s.id_serie='$idSerie' and m.id_producto='$idProd' AND m.tipo_traspaso = 1";
            $nreq = $this->queryArray($myQuery);
            $sc = $nreq['rows'][0]['costo'];          
            return $sc;
        }

        public function costoL($idlote,$idProd){
            $myQuery = "SELECT costo 
                        FROM app_inventario_movimientos
                        WHERE id_lote = '$idlote' AND id_producto='$idProd' AND tipo_traspaso = 1 AND estatus = 1 AND costo != 0 ORDER BY id ASC LIMIT 1";
            $res = $this->query($myQuery);
            $res = $res->fetch_assoc();
            return $res['costo'];            
        }

        public function costoLP($idlote,$idPed,$idProd){
            $myQuery = "SELECT costo 
                        FROM app_inventario_movimientos
                        WHERE id_lote = '$idlote' AND id_pedimento = '$idPed' AND id_producto='$idProd' AND tipo_traspaso = 1 AND estatus = 1 AND costo != 0 ORDER BY id ASC LIMIT 1";
            $res = $this->query($myQuery);
            $res = $res->fetch_assoc();
            return $res['costo'];            
        }

        public function costoP($idPed,$idProd){
            $myQuery = "SELECT costo 
                        FROM app_inventario_movimientos
                        WHERE id_pedimento = '$idPed' AND id_producto='$idProd'  AND tipo_traspaso = 1 AND estatus = 1 AND costo != 0 ORDER BY id ASC LIMIT 1";
            $res = $this->query($myQuery);
            $res = $res->fetch_assoc();
            return $res['costo'];            
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
        

        function saveEnvio($sessprods,$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$ist,$it,$fp,$facturo,$obs,$solicitante,$concept,$cadimps,$cliente,$moneda,$moneda_tc,$fptext)
        {
            if($moneda==1){
                $moneda_tc=1;
            }



            date_default_timezone_set("Mexico/General");
            $date_venta=date('Y-m-d H:i:s'); 
            $hoy=date('Y-m-d H:i:s'); 
            
            $myQuery = "INSERT INTO app_envios (id_oventa,id_encargado,observaciones,estatus,fecha_envio,activo,no_factura,fecha_factura,imp_factura,id_factura,xmlfile,subtotal,total,facturo,forma_pago, desc_concepto) VALUES ('$idOC','$solicitante','Observaciones',1,'$date_venta','$activo','$nofactrec','$date_recepcion','$impfactrec','6969','$xmlfile','$ist','$it','$facturo','$fp','$concept');";
            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                if($fptext=='Credito'){
                    $myQuery2 = "INSERT INTO app_pagos (cobrar_pagar,id_prov_cli,cargo,abono,fecha_pago,concepto,id_forma_pago,id_moneda,tipo_cambio,origen) VALUES (0,'$cliente','$it',0,'$date_venta','Ticket Venta-".$last_id."',6,'$moneda','$moneda_tc',1);";
                    $query = $this->query($myQuery2);
                }

                $cadlotes='';
                $cadmodo='';
                $productos = explode(',#', $idsProductos);
                $cardexmulti='';
                $cadrd='';
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=trim($exp[0]);
                    $idProd=$idprod;
                    $cant=$exp[1];
                    $idalmacen=$exp[2];
                    $especial=$exp[3];
                    $caracteristica=$exp[4];
                    $sinexi=$exp[5];
                    $tp=$exp[6];
                    $last1=0;
                    $last2=0;

                    if($cant==0){
                        continue;
                    }
                     //solo lote 1
                     //solo series 2
                     //series y pedimentos 3
                     //solo pedimentos 4
                     //sin nada 0
                     //lotes pedimentos 5
 
                    //Verificar tipo costeo producto id1=prod, id6=especifico
                    $idtipocosto = $this->tipoCosteoProd($idprod);


                    $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                    $caracteristicareplace=addslashes($caracteristicareplace);

                    if($sinexi==1 || $tp==2){
                        $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','0','0',1,'0','".$caracteristica."'),";
                        $cardexmulti.="";
                        continue;
                    }

                    if($especial==0){
                        /*$myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);
                        */

                        if($idtipocosto==1){
                            $elunit = $this->costeoProd($idProd);
                        }else if($idtipocosto==3){
                            $elunit = $this->costeoUltimoCosto($idProd);
                        }else{
                            $elunit = $this->costeoProd($idProd);
                        }
                        $elcost = ($elunit*1)*($cant*1);

                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantsexistencias']);
                        //var_dump($ciclo);
                        foreach ($ciclo as $kk => $vv) {
                            $desgl_ex=explode('-', $vv);
                            if($desgl_ex[2]!=0){

        /*
                                $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_ex[0]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                    $addcons='';
                                }
                                */

                                $elcost = ($elunit*1)*($desgl_ex[2]*1);
                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','0','0','".$desgl_ex[2]."','".$elcost."','".$desgl_ex[0]."','0','".$date_venta."','3','0','".$elunit."','Orden de venta / envio -".$last_id." ".$addcons."','1'),";

                                $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_ex[2]."','0','0',1,'".$desgl_ex[0]."','".$caracteristica."'),";
                            }
                        }

                        //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','0','0',1,'".$idalmacen."','".$caracteristica."'),";


                    }elseif($especial==1){

                        /*
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);
                        */

                        


                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantslotes']);

                        foreach ($ciclo as $kk => $vv) {
                            $desgl_cl=explode('-', $vv);
                            if($desgl_cl[2]!=0){

                                $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_cl[1]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }

                                if($idtipocosto==1){
                                    $elunit = $this->costeoProd($idProd);
                                }else if($idtipocosto==6){
                                    $elunit = $this->costoL($desgl_cl[0],$idProd);
                                }else{
                                    $elunit = $this->costeoProd($idProd);
                                }
                                $elcost = ($elunit*1)*($cant*1);
                                $elcost = ($elunit*1)*($desgl_cl[2]*1);

                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','0','".$desgl_cl[0]."','".$desgl_cl[2]."','".$elcost."','".$desgl_cl[1]."','0','".$date_venta."','3','0','".$elunit."','Orden de venta / envio -".$last_id." ".$addcons."','1'),";

                                $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_cl[2]."','".$desgl_cl[0]."','0',1,'".$desgl_cl[1]."','".$caracteristica."'),";
                            }
                        }

                        //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','0','0',1,'".$idalmacen."','".$caracteristica."'),";


                    }elseif($especial==4){
                        /*
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);
                        */


                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantspedimentos']);

                        foreach ($ciclo as $kk => $vv) {
                            $desgl_cl=explode('-', $vv);
                            if($desgl_cl[2]!=0){
                                

                                $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_cl[1]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }

                                if($idtipocosto==1){
                                    $elunit = $this->costeoProd($idProd);
                                }else if($idtipocosto==6){
                                    $elunit = $this->costoP($desgl_cl[0],$idProd);
                                }else{
                                    $elunit = $this->costeoProd($idProd);
                                }
                                $elcost = ($elunit*1)*($cant*1);
                                $elcost = ($elunit*1)*($desgl_cl[2]*1);

                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','".$desgl_cl[0]."','0','".$desgl_cl[2]."','".$elcost."','".$desgl_cl[1]."','0','".$date_venta."','3','0','".$elunit."','Orden de venta / envio -".$last_id." ".$addcons."','1'),";

                                $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_cl[2]."','0','".$desgl_cl[0]."',1,'".$desgl_cl[1]."','".$caracteristica."'),";

                            }
                        }

                        //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','0','0',1,'".$idalmacen."','".$caracteristica."'),";

                    }elseif($especial==5){
                        /*
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);

                        */


                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantspedimentos']);
                        foreach ($ciclo as $kk => $vv) {

                            $desgl_cl=explode('-', $vv);
                            $pedlote=explode('#', $desgl_cl[0]);
                            if($desgl_cl[2]!=0){
                                

                                $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_cl[1]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }

                                if($idtipocosto==1){
                                    $elunit = $this->costeoProd($idProd);
                                }else if($idtipocosto==6){
                                    $elunit = $this->costoLP($pedlote[1],$pedlote[0],$idProd);
                                }else{
                                    $elunit = $this->costeoProd($idProd);
                                }
                                $elcost = ($elunit*1)*($cant*1);
                                $elcost = ($elunit*1)*($desgl_cl[2]*1);

                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','".$pedlote[0]."','".$pedlote[1]."','".$desgl_cl[2]."','".$elcost."','".$desgl_cl[1]."','0','".$date_venta."','3','0','".$elunit."','Orden de venta / envio -".$last_id." ".$addcons."','1'),";

                                $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_cl[2]."','".$pedlote[1]."','".$pedlote[0]."',1,'".$desgl_cl[1]."','".$caracteristica."'),";
                            }
                        }

                        //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','0','0',1,'".$idalmacen."','".$caracteristica."'),";

                    }elseif($especial==2){
                        
                        /*
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);

                        */

                        if($idtipocosto==1){
                            $elunit = $this->costeoProd($idProd);
                        }else if($idtipocosto==6){

                        }else{
                            $elunit = $this->costeoProd($idProd);
                        }
                        $elcost = ($elunit*1)*($cant*1);

                        $sarray=array();
                        $seriessolas='';
                        foreach ($sessprods[$especial][$idprod][$caracteristica]['series'] as $kk => $vv) {
                            $desgl_ser=explode('-', $vv);

                            if (array_key_exists($desgl_ser[1], $sarray)) {
                                $sarray[$desgl_ser[1]]=($sarray[$desgl_ser[1]]*1)+1;
                            }else{
                                $sarray[$desgl_ser[1]]=1;
                            }
                            $seriessolas.=$desgl_ser[0].',';
                            $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ('".$desgl_ser[0]."','".$desgl_ser[1]."','".$hoy."','0');";
                            $last_prodserie = $this->insert_id($myQuery);

                            if($idtipocosto==1){
                                $elunit = $this->costeoProd($idProd);
                            }else if($idtipocosto==6){
                                $elunit = $this->costosSeries($desgl_ser[0],$idprod);
                            }else{
                                $elunit = $this->costeoProd($idProd);
                            }
                            $elcost = ($elunit*1)*($cant*1);

                            $upseries[]=array('idprod'=>$idprod ,
                                'idalmacen'=>$desgl_ser[1],
                                'idserie'=>$desgl_ser[0],
                                'pedsss'=>0,
                                'unit'=>$elunit,
                                'id'=>$last_prodserie);
                        }

                        $setrim = trim($seriessolas, ',');
                        $myQuery = "UPDATE app_producto_serie SET id_venta='$last_id', estatus=1 WHERE id_producto='$idprod' AND id in (".$setrim."); ";
                        $query = $this->query($myQuery);
                       
                        foreach ($sarray as $kk => $vv) {


                           

                            $elcost = ($elunit*1)*($vv*1);

                            $esconsig = "SELECT count(*) as es from app_almacenes where id='".$kk."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }
                            //$desgl_ped=explode('-', $vv);

                            $cardexmulti.="('".$idprod."','".$caracteristicareplace."','0','0','".$vv."','".$elcost."','".$kk."','0','".$date_venta."','3','0','".$elunit."','Orden de venta / envio -".$last_id." ".$addcons."','1'),";
                        
                            $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$vv."','0','0',1,'".$kk."','".$caracteristica."'),";
                        }

                        //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','0','0',1,'".$idalmacen."','".$caracteristica."'),";
                        

                    }elseif($especial==3){

                        /*
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);
                        */


                        $sers='';
                        foreach ($sessprods[$especial][$idprod][$caracteristica]['pedimentos'] as $kk => $vv) {
                            $desgl_ped=explode('-', $vv);
                            $elpedi1=trim($desgl_ped[0]);
                            
                            $cantpedseri=0;
                            foreach ($sessprods[$especial][$idprod][$caracteristica]['series'] as $kk => $vv) {
                                $desgl_ser=explode('-', $vv);
                                $elpedi2=trim($desgl_ser[1]);
                                $alma=trim($desgl_ser[2],'|');
                                //
                                if($elpedi1==$elpedi2){
                                    $sers.=$desgl_ser[0].',';
                                    $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ('".$desgl_ser[0]."','".$alma."','".$hoy."','0') ;";
                                    $last_prodserie2 = $this->insert_id($myQuery);

                                    if($idtipocosto==1){
                                        $elunit = $this->costeoProd($idProd);
                                    }else if($idtipocosto==6){
                                        $elunit = $this->costosSeries($desgl_ser[0],$idprod);
                                    }else{
                                        $elunit = $this->costeoProd($idProd);
                                    }
                                    $elcost = ($elunit*1)*($cant*1);

                                    $upseries[]= array('idprod'=>$idprod,
                                        'idalmacen'=>$alma,
                                        'idserie'=>$desgl_ser[0],
                                        'pedsss'=>$elpedi2,
                                        'unit'=>$elunit,
                                        'id'=>$last_prodserie2);

                                    $cantpedseri++;
                                }
                            }
                            $elcost = ($elunit*1)*($cantpedseri*1);

                            $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_ped[1]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }


                            $cardexmulti.="('".$idprod."','".$caracteristicareplace."','".$elpedi1."','0','".$cantpedseri."','".$elcost."','".$desgl_ped[1]."','0','".$date_venta."','3','0','".$elunit."','Orden de venta / envio -".$last_id." ".$addcons."','1'),";

                    

                            $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cantpedseri."','0','".$elpedi1."',1,'".$desgl_ped[1]."','".$caracteristica."'),";

                        }

                        $setrim = trim($sers, ',');
                        $myQuery = "UPDATE app_producto_serie SET id_venta='$last_id', estatus=1 WHERE id_producto='$idprod' AND id in (".$setrim."); ";
                        $query = $this->query($myQuery);

                        //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','0','0',1,'".$idalmacen."','".$caracteristica."'),";


                    }

                }

                $cadrdtrim = trim($cadrd, ',');
                    $myQuery = "INSERT INTO app_envios_datos (id_oventa,id_producto,id_envio,cantidad,id_lote,id_pedimento,estatus,id_almacen,caracteristica) VALUES ".$cadrdtrim.";";
                    $query = $this->query($myQuery);

                if($cardexmulti!=''){
                    $cadrdcardextrim = trim($cardexmulti, ',');
                    $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) VALUES ".$cadrdcardextrim.";";

                    $query = $this->query($myQuery);
                }
                foreach ($upseries as $ks => $vs) {
                    $myQuery = "UPDATE app_producto_serie_rastro SET id_mov =(SELECT id FROM app_inventario_movimientos WHERE referencia='Orden de venta / envio -".$last_id."' AND id_producto='".$vs['idprod']."' AND id_almacen_origen='".$vs['idalmacen']."' AND id_pedimento='".$vs['pedsss']."' ) WHERE id='".$vs['id']."';";
                    $query = $this->query($myQuery);

                    $myQuery = "UPDATE app_inventario_movimientos SET costo='".$vs['unit']."' WHERE referencia='Orden de venta / envio -".$last_id."' AND id_producto='".$vs['idprod']."' AND id_almacen_origen='".$vs['idalmacen']."' AND id_pedimento='".$vs['pedsss']."' ;";
                    $query = $this->query($myQuery);
                }

              
                
                
            }

            $myQuery = "UPDATE app_requisiciones_venta SET activo='$activo' WHERE id in (SELECT id_requisicion FROM app_oventa WHERE id='$idOC');";
            $this->query($myQuery);


            $myQuery = "UPDATE app_oventa SET activo='$activo' WHERE id='$idOC';";
            $this->query($myQuery);

            return $last_id;
            
        
        }

        function listaRequisiciones()
        {
            $myQuery = "SELECT a.id, SUBSTRING(a.fecha,1,10), cc.nombre, b.nombreEmpleado, TRUNCATE(a.total,2) as importe
, a.urgente, a.activo, a.aceptada, a.cadenaCoti, sum(xxx.nuevo) as cnuevos, cp.id as idcotpe, cp.status
            FROM app_requisiciones_venta a
            left join app_requisiciones_venta_comentarios xxx on xxx.id_coti=a.id
            INNER JOIN nomi_empleados b on b.idEmpleado=a.id_solicito
            left join comun_cliente cc on cc.id=a.id_cliente
            LEFT JOIN app_area_empleado c on c.id=b.id_area_empleado
            left JOIN (SELECT a2.precio, a2.cantidad, a2.id as fff, a2.id_requisicion
                       FROM app_requisiciones_datos_venta a2
                       inner JOIN app_productos b2 on b2.id=a2.id_producto) as s2 on s2.id_requisicion=a.id

            left join cotpe_pedido cp on cp.idCotizacion=a.id and cp.origen=1
            WHERE a.pr=1
            GROUP BY a.id
            ORDER BY a.id desc;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function listaOrdenesCompra()
        {
            $myQuery = "SELECT a.id, b.id, SUBSTRING(a.fecha,1,10), dd.nombre, bb.nombreEmpleado, TRUNCATE(b.total,2) as importe, a.urgente, a.activo, a.id as idreq from app_oventa b
inner join app_requisiciones_venta a on a.id=b.id_requisicion
INNER JOIN nomi_empleados bb on bb.idempleado=a.id_solicito
left join app_area_empleado cc on cc.id=bb.id_area_empleado
left join comun_cliente dd on dd.id=a.id_cliente
             WHERE  (a.activo=1 OR a.activo=4 OR a.activo=5 OR a.activo=6)
             GROUP BY a.id
            ORDER BY a.id desc;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function listadoFacturasReporte($fini,$ffin,$t)
        {
            $fini=$fini.' 00:00:01'; 
            $ffin=$ffin.' 23:59:59'; 
            if($t==1){
            $myQuery = "SELECT a.id, a.fecha_envio, c.nombre,  a.subtotal, a.total, e.facturado, e.id_respFact, f.xmlfile as xmlmasivo, d.xmlfile, d.borrado, d.id as idsale, if(d.folio is null,f.folio,d.folio) as folio, if(d.xmlfile is null,f.xmlfile,d.xmlfile) as xfile,  if(f.id is null,d.id,f.id) as idfact, f.borrado as borrado2, if(d.tipoComp is null,f.tipoComp,d.tipoComp) as tcomp  from app_envios a
left join app_oventa b on b.id=a.id_oventa
left join comun_cliente c on c.id=b.id_cliente
left join app_respuestaFacturacion d on d.idSale=a.id and d.origen=1
left join app_pendienteFactura e on e.id_sale=a.id
left join app_respuestaFacturacion f on f.id=e.id_respFact and f.origen=1
WHERE a.id>0 and a.fecha_envio between '".$fini."' AND '".$ffin."' order by id desc;";
            }else if($t==2){
                $myQuery = "SELECT a.id, a.fecha_envio, c.nombre,  a.subtotal, a.total, e.facturado, e.id_respFact, f.xmlfile as xmlmasivo, d.xmlfile, d.borrado, d.id as idsale, if(d.folio is null,f.folio,d.folio) as folio, if(d.xmlfile is null,f.xmlfile,d.xmlfile) as xfile,  if(f.id is null,d.id,f.id) as idfact, f.borrado as borrado2, if(d.tipoComp is null,f.tipoComp,d.tipoComp) as tcomp  from app_envios a
left join app_oventa b on b.id=a.id_oventa
left join comun_cliente c on c.id=b.id_cliente
left join app_respuestaFacturacion d on d.idSale=a.id and d.origen=1
left join app_pendienteFactura e on e.id_sale=a.id
left join app_respuestaFacturacion f on f.id=e.id_respFact and f.origen=1
WHERE a.id>0 and (d.idOs=0 or f.idOs=0) AND (f.xmlfile is not null or d.xmlfile is not null) AND (d.borrado!=3 or f.borrado!=3)  and a.fecha_envio between '".$fini."' AND '".$ffin."' order by id desc;";
            }else if($t==3){
                $myQuery = "SELECT a.id, a.fecha_envio, c.nombre,  a.subtotal, a.total, e.facturado, e.id_respFact, f.xmlfile as xmlmasivo, d.xmlfile, d.borrado, d.id as idsale, if(d.folio is null,f.folio,d.folio) as folio, if(d.xmlfile is null,f.xmlfile,d.xmlfile) as xfile,  if(f.id is null,d.id,f.id) as idfact, f.borrado as borrado2, if(d.tipoComp is null,f.tipoComp,d.tipoComp) as tcomp  from app_envios a
left join app_oventa b on b.id=a.id_oventa
left join comun_cliente c on c.id=b.id_cliente
left join app_respuestaFacturacion d on d.idSale=a.id and d.origen=1
left join app_pendienteFactura e on e.id_sale=a.id
left join app_respuestaFacturacion f on f.id=e.id_respFact and f.origen=1
WHERE a.id>0 AND f.xmlfile is null and d.xmlfile is null  and a.fecha_envio between '".$fini."' AND '".$ffin."' order by id desc;";
            }else if($t==4){
                $myQuery = "SELECT a.id, a.fecha_envio, c.nombre,  a.subtotal, a.total, e.facturado, e.id_respFact, f.xmlfile as xmlmasivo, d.xmlfile, d.borrado, d.id as idsale, if(d.folio is null,f.folio,d.folio) as folio, if(d.xmlfile is null,f.xmlfile,d.xmlfile) as xfile,  if(f.id is null,d.id,f.id) as idfact, f.borrado as borrado2, if(d.tipoComp is null,f.tipoComp,d.tipoComp) as tcomp  from app_envios a
left join app_oventa b on b.id=a.id_oventa
left join comun_cliente c on c.id=b.id_cliente
left join app_respuestaFacturacion d on d.idSale=a.id and d.origen=1
left join app_pendienteFactura e on e.id_sale=a.id
left join app_respuestaFacturacion f on f.id=e.id_respFact and f.origen=1
WHERE a.id>0 AND (d.borrado=3 or f.borrado=3)  and a.fecha_envio between '".$fini."' AND '".$ffin."' order by id desc;";

            }


            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function listadoFacturas($fini,$ffin)
        {
            $myQuery = "SELECT concat(b.id,'-',c.id) as idcli,b.id, b.fecha_envio, c.nombre, if(b.facturo=0,'Ticket','Pendiente por facturar') as tipo, b.subtotal,concat(b.total,' ',cc.codigo) as total, d.concepto, count(e.abono) as tieneabono from app_pendienteFactura a
inner join app_envios b on b.id=a.id_sale
left join app_oventa ov on ov.id=b.id_oventa
left join app_requisiciones_venta rv on rv.id=ov.id_requisicion
left join cont_coin cc on cc.coin_id=rv.id_moneda
left join comun_cliente c on c.id=a.id_cliente
left join app_pagos d on d.origen=1 and d.concepto = concat('Ticket Venta-',b.id)
left join app_pagos_relacion e on e.id_tipo=0 and e.id_documento=d.id
where a.facturado=0 and b.fecha_envio between '".$fini."' and '".$ffin."'
group by a.id;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function listaRecepciones($idoc)
        {
            if($idoc>0){
                $add=' WHERE a.id_oc='.$idoc.' ';
            }else{
                $add='';
            }
            $myQuery = "SELECT b.id, a.id as idr, SUBSTRING(a.fecha_recepcion,1,10) as fechar, a.no_factura, SUBSTRING(a.fecha_factura,1,10) as fechaf, a.imp_factura, a.estatus, a.activo FROM app_recepcion a
 inner join app_ocompra b on b.id=a.id_oc ".$add.";";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function a_change_idoc_idreq($idoc)
        {

            $myQuery = "SELECT id_requisicion FROM app_oventa WHERE id='$idoc';";
            $datosReq = $this->queryArray($myQuery);
            return $datosReq;

        }

        function listaVentas($idoc)
        {
            if($idoc>0){
                $add=' and a.id_oventa='.$idoc.' ';
            }else{
                $add='';
            }
                $myQuery = "SELECT b.id, a.id as idr, SUBSTRING(a.fecha_envio,1,10) as fechar, SUBSTRING(c.fecha,1,10) as fechaf, TRUNCATE(a.total,2), a.estatus, a.activo, if(c.id is null,0,c.id) as folio, c.borrado, a.facturo, if(d.id_cliente is null,0,d.id_cliente) as idFact, a.total, if(d.id_respFact is null,0,d.id_respFact) as idrespf, e.borrado as borrado2 FROM app_envios a
inner join app_envios_datos aa on aa.id_envio=a.id
 inner join app_oventa b on b.id=a.id_oventa 
 left join app_respuestaFacturacion c on c.idSale=a.id and c.cancelre=0  -- and c.tipoComp='F'
  left join app_pendienteFactura d on d.id_sale=a.id
    left join app_respuestaFacturacion e on e.id=d.id_respFact and e.cancelre=0 -- and c.tipoComp='F'
  where 1=1 ".$add."  group by a.id;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        

        function listaRequisicionesCompra()
        {
            $myQuery = "SELECT a.id, if(ov.id is null,'',ov.id) as oventa, SUBSTRING(a.fecha,1,10), cc.nombre, b.nombreEmpleado, TRUNCATE(a.total,2) as importe, a.urgente, a.activo
            FROM app_requisiciones_venta a
            INNER JOIN nomi_empleados b on b.idEmpleado=a.id_solicito
            left join comun_cliente cc on cc.id=a.id_cliente
            LEFT JOIN app_area_empleado c on c.id=b.id_area_empleado
            LEFT JOIN app_oventa ov on ov.id_requisicion=a.id
            left JOIN (SELECT a2.precio, a2.cantidad, a2.id as fff, a2.id_requisicion
                       FROM app_requisiciones_datos_venta a2
                       inner JOIN app_productos b2 on b2.id=a2.id_producto) as s2 on s2.id_requisicion=a.id
            GROUP BY a.id
            ORDER BY a.id desc;";


            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function sinexist(){
            $myQuerySalidas="SELECT salidas_sin_existencia FROM app_configuracion;";
            $salidassin = $this->queryArray($myQuerySalidas);
            return $salidassin['rows'][0]['salidas_sin_existencia'];
        }

        function editarRequisicion($idReq,$pr)
        {
            if($pr=='req'){
                $add='';
            }
            $myQuery = "SELECT a.*, d.nombre, c.no_factura, c.fecha_factura, c.imp_factura, d.limite_credito, b.id as idoc, concat(n.nombre,' ',n.apellido1) as username, idempleado, cadenaCoti, aceptada  FROM app_requisiciones_venta a 
left join app_oventa b on b.id_requisicion = a.id
left join app_recepcion c on c.id_oc = b.id
left join comun_cliente d on d.id = a.id_cliente
left join empleados n on n.idempleado=a.id_usuario
WHERE a.id='$idReq';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function editarRequisicionEnvio($idReq,$pr)
        {
            if($pr=='req'){
                $add='';
            }
            $myQuery = "SELECT a.*, d.nombre, c.no_factura, c.fecha_factura, c.imp_factura, d.direccion, d.email, e.nombreorganizacion, e.domicilio, e.logoempresa, b.subtotal as st, b.total as tt, a.subtotal as rst, a.total as rtt, m.codigo as moneda, concat(n.nombre,' ',n.apellido1) as username1, concat(nn.nombre,' ',nn.apellido1) as username2, if(a.fecha_creacion is null,'',a.fecha_creacion) as fecha_creacion1, if(b.fecha_creacion is null,'',b.fecha_creacion) as fecha_creacion2, b.id as idOV   FROM app_requisiciones_venta a 
left join app_oventa b on b.id_requisicion = a.id
left join app_recepcion c on c.id_oc = b.id
left join comun_cliente d on d.id = a.id_cliente
left join organizaciones e on e.idorganizacion=1
left join cont_coin m on m.coin_id=a.id_moneda
left join empleados n on n.idempleado=a.id_usuario
left join empleados nn on nn.idempleado=b.id_usuario
WHERE a.id='$idReq';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function getSeriesActivas($venta,$idProd){
            $myQuery = "SELECT group_concat(serie) as seriesx, group_concat(id) as idsx from app_producto_serie where id_producto='$idProd' and id_venta='$venta';";
            $datosReq = $this->query($myQuery);
            return $datosReq;
        }

        function editarRequisicionRec($idRec,$idOc)
        {
            $myQuery = "SELECT c.*, a.no_factura, a.fecha_factura, a.imp_factura, c.id as idreq, c.id_tipogasto, if(d.id is null,0,d.id) as facturaid, d.xmlfile, e.id as id_cliente, a.total as totventa, d.fecha as fecha_fact, d.folio, if(f.rfc is null,0,f.rfc) as idrfc  from app_envios a
inner join app_oventa b on b.id=a.id_oventa
inner join app_requisiciones_venta c on c.id=b.id_requisicion
left join app_respuestaFacturacion d on d.idSale=a.id and d.borrado=0 and d.idOs=0
left join comun_cliente e on e.id=c.id_cliente
left join comun_facturacion f on f.nombre=d.idFact
where a.id='$idRec';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function getEnviados($envio,$prod,$cadcar){
            $myQuery="SELECT if(sum(cantidad) is null,0,sum(cantidad)) as cantdev from app_devolucioncli_datos where id_envio='$envio' and id_producto='$prod' and caracteristica='$cadcar';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;

        }

        function productosRequisicion($idReq,$idCliente,$m,$mod,$idEnv)
        {
            $w= ' 1=1 ';
            if($m==1){
                $w=' e.activo=3 ';
            }
            if($m==2){
                $w=' (e.activo=1 or e.activo=3 or e.activo=4 or e.activo=5) ';
            }

            if($mod==4){
                
            }
//******
            $w= ' 1=1 ';
            $lj='';
            $costo=" if(b.costo is null,b.costo,b.costo) as costo ";
            if($m==1){
                $w=' e.activo=3 ';
            }
            if($m==2){
                $w=' (e.activo=1 or e.activo=3 or e.activo=4 or e.activo=5) ';
                $lj="LEFT join (Select d.id_producto, d.costo, e.id as idoc  from app_ocompra_datos d
                               left join app_ocompra e on e.id=d.id_ocompra AND e.id_proveedor='$idProveedor' AND e.id_requisicion='$idReq' WHERE e.id is not null ORDER BY d.id desc) d on d.id_producto=c.id
                    left join app_recepcion r on r.id_oc=d.idoc
                    left join app_recepcion_datos t on t.id_recepcion=r.id and t.id_producto=d.id_producto";
                $costo=" if(d.costo is null,b.costo,d.costo) as costo, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr, t.id_almacen, if(t.cantidad is null,0,t.cantidad) as recibidorec ";
            }

            if($mod==4){
                
            }

//******


            $myQuery = "SELECT cc.clave, c.id, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, ".$costo."
                        from app_requisiciones_datos_venta a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = b.id_producto
                    INNER join app_unidades_medida cc on cc.id=c.id_unidad_compra
                    ".$lj."
                    WHERE a.id_requisicion='$idReq' group by c.id;";

                    
            if($m==1){

                  $myQuery="SELECT c.id, c.codigo, c.nombre as nomprod, a.cantidad, c.series, c.lotes, c.pedimentos, if(a.precio is null,0,a.precio) as costo,  if(sum(ee.cantidad) is null,0,sum(ee.cantidad)) as cantidadr, a.id_lista, c.precio as precioorig, x.clave, a.caracteristica, a.tipo_desc, a.monto_desc
                  /*, if(vd.tipo_descuento is null,0,vd.tipo_descuento) as tipoDescuento, sum(vd.descuento) as totDesc,
group_concat(vd.tipo_descuento,'-',vd.valor,'-',vd.descuento) as cadenadesc */ from app_requisiciones_datos_venta a
                    INNER JOIN app_productos c on c.id = a.id_producto
                    left join app_envios_datos ee on ee.id_envio='$idEnv'
                    INNER join app_unidades_medida x on x.id=c.id_unidad_venta
                    -- left join app_ventas_descuentos vd on vd.id_cotizacion=a.id_requisicion and vd.id_producto=c.id
                    WHERE a.id_requisicion='$idReq' group by a.id;";

            }else{
                  $myQuery="SELECT c.id, c.codigo, c.nombre as nomprod, a.cantidad, c.series, c.lotes, c.pedimentos, if(a.precio is null,0,a.precio) as costo,  if(sum(ee.cantidad) is null,0,sum(ee.cantidad)) as cantidadr, a.id_lista, c.precio as precioorig, x.clave, a.caracteristica, c.tipo_producto from app_requisiciones_datos_venta a
                    INNER JOIN app_productos c on c.id = a.id_producto
                    left join app_envios_datos ee on ee.id_envio='$idEnv'
                     INNER join app_unidades_medida x on x.id=c.id_unidad_venta
                    WHERE a.id_requisicion='$idReq' group by a.id;";
            }

            if($m==3){
                  $myQuery = "SELECT cc.clave, c.id, c.series, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, c.tipo_producto
,  a.costo as costo, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr
, t.id_almacen, if(sum(t.cantidad is null),0,sum(t.cantidad)) as recibidorec  , c.nombre as nomprod, a.caracteristica,
ped.no_pedimento, ped.aduana, ped.no_aduana, ped.tipo_cambio, SUBSTRING(ped.fecha_pedimento,1,10) fecha_pedimento,
lot.no_lote, SUBSTRING(lot.fecha_fabricacion,1,10) fecha_fabricacion, SUBSTRING(lot.fecha_caducidad,1,10) fecha_caducidad -- , if(sum(dp.cantidad) is null,0,sum(dp.cantidad)) as cantdev , group_concat(se.serie) as seriesx

                    from app_oventa_datos a
                   
                    INNER JOIN app_productos c on c.id = a.id_producto
                    INNER join app_unidades_medida cc on cc.id=c.id_unidad_compra
                    left join app_envios_datos t on t.id_oventa=a.id_oventa and t.id_producto=a.id_producto and t.caracteristica=a.caracteristica
                    left join app_producto_pedimentos ped on ped.id=t.id_pedimento
                    left join app_producto_lotes lot on lot.id=t.id_lote
                   -- left join app_devolucioncli_datos dp on dp.id_envio='$idEnv' and dp.id_producto=c.id and dp.caracteristica=a.caracteristica
                   -- left join app_producto_serie se on se.id_venta='42' and se.id_producto=c.id
                    WHERE a.id_oventa in (select id from app_oventa where id_requisicion='$idReq') group by a.id ;";
            }
            
            /*$myQuery = "SELECT c.id, c.codigo, c.nombre, b.costo, a.cantidad, c.series, c.lotes, c.pedimentos  from app_requisiciones_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = b.id_producto
                    WHERE a.id_requisicion='$idReq';";
*/
            
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function getPeriodoFecha()
        {
            $myQuery = "SELECT b.nombre as ano,a.id_periodo_actual as mes,b.cerrado, a.periodos_abiertos, a.permitir_cerrados FROM app_configuracion a
            LEFT JOIN app_ejercicios b on b.id=a.id_ejercicio_actual;";
            $periodoFecha = $this->query($myQuery);
            return $periodoFecha;
        }

        function getRfcCliente($idcliente)
        {
            $myQuery = "SELECT id, rfc FROM comun_facturacion WHERE nombre='$idcliente';";
            $rfcs = $this->query($myQuery);
            return $rfcs;
        }

        function getRfcFact($idFact)
        {
            $myQuery = "SELECT id, rfc FROM comun_facturacion WHERE id='$idFact';";
            $rfcs = $this->query($myQuery);
            return $rfcs;
        }

        function factProceso($idcliente,$productos)
        {
            $iddd=explode(',', $productos);
            $idsprods='';
            foreach ($iddd as $k => $v) {
                $ola=explode('>', $v);
                $idsprods.=$ola[0].',';
            }
            $idsprods=trim($idsprods,',');
            $myQuery = "SELECT * FROM app_productos WHERE id in ($idsprods);";
            $prodsdatos = $this->query($myQuery);

            return $prodsdatos;
        }

        function getLoteProd($idReq)
        {
            $myQuery = " SELECT a.*, b.* FROM app_ocompra a
             inner join app_recepcion_datos b on b.id_oc=a.id
             inner join app_producto_lotes c on c.id=b.id_lote
             WHERE a.id_requisicion='$idReq';";
            $periodoFecha = $this->query($myQuery);
            return $periodoFecha;
        }

        function getSPProd($idReq)
        {
            $myQuery = " SELECT a.*, b.* FROM app_ocompra a
             inner join app_recepcion_datos b on b.id_oc=a.id
             inner join app_producto_lotes c on c.id=b.id_lote
             WHERE a.id_requisicion='$idReq';";
            $periodoFecha = $this->query($myQuery);
            return $periodoFecha;
        }

        function listaProdsFactMasiva($ids,$idComunFactu){
            $idVenta=$ids;
            $idFact=0;

            $myQuery="SELECT cadenaOriginal from app_pendienteFactura where facturado=0 and id_sale in (".$ids."); ";

            $resImpues = $this->queryArray($myQuery);
            if($resImpues["total"] > 0) {
                $arrayjsons=array();
                foreach ($resImpues['rows'] as $k => $v) {
                   $transform = base64_decode($v['cadenaOriginal']);
                   $transform = json_decode($transform);
                   $transform = $this->object_to_array($transform);
                   $arrayjsons[]=$transform;


                }
            }
       
            $subtotal=0;
            $total=0;

            $azurian=$arrayjsons[0];
            foreach ($arrayjsons as $kf => $vf) {
                $subtotal+=($vf['Basicos']['subTotal']*1);
                $total+=($vf['Basicos']['total']*1);
            }



            $qrpac = "SELECT pac, lugar_exp FROM pvt_configura_facturacion WHERE id=1;";
            $respac = $this->queryArray($qrpac);
            $pac = $respac["rows"][0]["pac"]; 
            $lugexp = $respac["rows"][0]["lugar_exp"]; 


            $total=number_format($total,2,'.','');
            $azurian['Observacion']['Observacion']='Facturacion masiva - Ventas id: '.$ids;
            $azurian['Correo']['Correo']='';
            $azurian['Basicos']['metodoDePago']='99';
            $azurian['Basicos']['LugarExpedicion']=$lugexp;
            $azurian['Basicos']['formaDePago']='Pago en una sola exhibicion';
            $azurian['Basicos']['tipoDeComprobante']='ingreso';
            $azurian['Basicos']['total']=$total;
            $subtotal=$total/1.16;
            $subtotal=number_format($subtotal,2,'.','');
            $agregado=$total-$subtotal;
            $agregado=number_format($agregado,2,'.','');
            $azurian['Basicos']['subTotal']=$subtotal;


            $azurian['nn']['nn']['IVA'][16]=$agregado;
            $azurian['nnf']['nnf']['IVA'][16]['Valor']=$agregado;

            $azurian['tipoFactura']='factura';
            $azurian['Conceptos']['conceptos']="<cfdi:Concepto cantidad='1' unidad='1' descripcion='Facturacion masiva' valorUnitario='".$subtotal."' importe='".$subtotal."'/>";
            $azurian['Conceptos']['conceptosOri']="|1|1|Facturacion masiva|".$subtotal."|".$subtotal."";

            $azurian['Impuestos']['totalImpuestosIeps']="0";
            $azurian['Impuestos']['isr']="";
            $azurian['Impuestos']['iva']="|IVA|16.00|".$agregado."|".$agregado."";
            $azurian['Impuestos']['totalImpuestosRetenidos']="0.00";
            $azurian['Impuestos']['totalImpuestosTrasladados']="".$agregado."";
            $azurian['Impuestos']['ivas']="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16.00' importe='".$agregado."' /></cfdi:Traslados>";
            //$newarray['Basicos']['subTotal']=$subtotal;
            
            
        require_once('../../modulos/SAT/config.php');
        date_default_timezone_set("Mexico/General");
        $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));


        

    
            $queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
            $returnConfiguracion = $this->queryArray($queryConfiguracion);
            if ($returnConfiguracion["total"] > 0) {
                $r = (object) $returnConfiguracion["rows"][0];

                /* DATOS OBLIGATORIOS DEL EMISOR
                ================================================================== */
                $rfc_cliente = $r->rfc;

                $parametros['EmisorTimbre'] = array();
                $parametros['EmisorTimbre']['RFC'] = $r->rfc;
                $parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
                $parametros['EmisorTimbre']['Pais'] = $r->pais;
                $parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
                $parametros['EmisorTimbre']['Calle'] = $r->calle;
                $parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
                $parametros['EmisorTimbre']['Colonia'] = $r->colonia;
                $parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
                $parametros['EmisorTimbre']['Municipio'] = $r->municipio;
                $parametros['EmisorTimbre']['Estado'] = $r->estado;
                $parametros['EmisorTimbre']['CP'] = $r->cp;
                $cer_cliente = $pathdc . '/' . $r->cer;
                $key_cliente = $pathdc . '/' . $r->llave;
                $pwd_cliente = $r->clave;
            } else {

                $JSON = array('success' => 0,
                    'error' => 1001,
                    'mensaje' => 'No existen datos de emisor.');
                echo json_encode($JSON);
                exit();
            }

        /* Datos Receptor
        ============================================================== */
        if($idComunFactu>0){

            $selComFac = "SELECT * FROM comun_facturacion WHERE id=".$idComunFactu;
            $result = $this->queryArray($selComFac);
            //Estado
            $selEstado ="SELECT estado from estados where idestado=".$result['rows'][0]['estado'];
            $resultEstado = $this->queryArray($selEstado);
            

            $idCliente=$rs{'nombre'};
            $azurian['Receptor']['rfc']=strtoupper($result['rows'][0]['rfc']);
            $azurian['Receptor']['nombre']=strtoupper($result['rows'][0]['razon_social']);
            $azurian['DomicilioReceptor']['calle']=$result['rows'][0]['domicilio'];
            $azurian['DomicilioReceptor']['noExterior']=$result['rows'][0]['num_ext'];
            $azurian['DomicilioReceptor']['colonia']=$result['rows'][0]['colonia'];
            $azurian['DomicilioReceptor']['localidad']=$result['rows'][0]['ciudad'];
            $azurian['DomicilioReceptor']['municipio']=$result['rows'][0]['municipio'];
            $azurian['DomicilioReceptor']['estado']=$resultEstado['rows'][0]['estado'];
            $azurian['DomicilioReceptor']['pais']=$result['rows'][0]['pais'];
            $azurian['DomicilioReceptor']['codigoPostal']=$result['rows'][0]['cp'];
            $azurian['Correo']['Correo'] = $result['rows'][0]['correo'];
            
        }else{
            $idCliente='';
            $azurian['Receptor']['rfc']='XAXX010101000';
            $azurian['Receptor']['nombre']='Factura generica';
            $azurian['DomicilioReceptor']['calle']='';
            $azurian['DomicilioReceptor']['noExterior']='';
            $azurian['DomicilioReceptor']['colonia']='';
            $azurian['DomicilioReceptor']['localidad']='';
            $azurian['DomicilioReceptor']['municipio']='';
            $azurian['DomicilioReceptor']['estado']='';
            $azurian['DomicilioReceptor']['pais']='';
            $azurian['DomicilioReceptor']['codigoPostal']='';
            $azurian['Correo']['Correo'] = '';
        }


        $serFol = "SELECT * FROM pvt_serie_folio WHERE id=1";
        $rs3 = $this->queryArray($serFol);

        $selecLogo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
        $rs4 = $this->queryArray($selecLogo);

        $azurian['org']['logo'] = $rs4['rows'][0]['logoempresa'];

        /* Datos serie y folio
        ============================================================== */
        $azurian['Basicos']['serie']=$rs3['rows'][0]['serie']; //No obligatorio
        $azurian['Basicos']['folio']=$rs3['rows'][0]['folio'];

        /* Datos Emisor
        ============================================================== */
        $azurian['Emisor']['rfc']=strtoupper($parametros['EmisorTimbre']['RFC']);
        $azurian['Emisor']['nombre']=strtoupper($parametros['EmisorTimbre']['RazonSocial']);
        /* Datos Fiscales Emisor
        ============================================================== */
        $azurian['FiscalesEmisor']['calle']=$parametros['EmisorTimbre']['Calle'];
        $azurian['FiscalesEmisor']['noExterior']=$parametros['EmisorTimbre']['NumExt'];
        $azurian['FiscalesEmisor']['colonia']=$parametros['EmisorTimbre']['Colonia'];
        $azurian['FiscalesEmisor']['localidad']=$parametros['EmisorTimbre']['Ciudad'];
        $azurian['FiscalesEmisor']['municipio']=$parametros['EmisorTimbre']['Municipio'];
        $azurian['FiscalesEmisor']['estado']=$parametros['EmisorTimbre']['Estado'];
        $azurian['FiscalesEmisor']['pais']=$parametros['EmisorTimbre']['Pais'];
        $azurian['FiscalesEmisor']['codigoPostal']=$parametros['EmisorTimbre']['CP'];

        /* Datos Regimen
        ============================================================== */
        $azurian['Regimen']['Regimen']=$parametros['EmisorTimbre']['RegimenFiscal'];

        /* Fecha Factura
        ============================================================== */
        $azurian['Basicos']['fecha']=$fecha;

        /* Impuestos
        ============================================================== */
        $tisr=$azurian['Impuestos']['totalImpuestosRetenidos'];
        $tiva=$azurian['Impuestos']['totalImpuestosTrasladados'];
        $tieps=$azurian['Impuestos']['totalImpuestosIeps']; 

    //    $positionPath='../../webapp/modulos';
        $appministra_nuevo=1;
        $no_permitidas=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
        $permitidas=array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
        $rzst = str_replace($no_permitidas, $permitidas ,$azurian['Receptor']['nombre']);
    
        //var_dump($azurian);
       // exit();

        if($pac==2){
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){

            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');  
        }

        }

        public function calculaImpuestosFact($stringTaxes){
            //echo $stringTaxes.'Z';
            //unset($_SESSION['prueba']);
            //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';

           
            $impuestos = array();
            $productos = explode('/', $stringTaxes);

            $ppii=array();
            $f=0;

            foreach ($productos as $key => $value) {
                $prod = explode('-', $value);
                if($prod[0]!=''){
                    //$impuestos[$prod[0]]=$sinimps[$prod[0]];


                    $idProducto = $prod[0];
                    $precio = $prod[1];
                    $cantidad = $prod[2];
                    $car = $prod[4];
                    $formula = 1;//desc o asc 1 = ieps de los vinos , 2 = ieps de la gasolina
                    $subtotal = $precio * $cantidad;
                    $subtotalVenta +=$subtotal;
                    //echo 'Subtotal='.$subtotal;
                    if($formula==2){
                        $ordenform = 'ASC';
                    }else{
                        $ordenform = 'DESC';
                    }


                    

                   /* echo 'id='.$idProducto.'<br>';
                    echo 'precio='.$precio.'<br>';
                    echo 'cantidad='.$cantidad.'<br>';
                    echo 'formula='.$formula; */
                    $queryImpuestos = "select um.clave as medida, p.id_unidad_venta, p.descripcion_larga as descprod, p.nombre as nameprod, p.codigo as codeprod, p.id,p.precio, i.valor, i.clave,pi.formula,i.nombre";
                    $queryImpuestos .= " from app_impuesto i, app_productos p ";
                    $queryImpuestos .= " left join app_producto_impuesto pi on p.id=pi.id_producto ";
                    $queryImpuestos .= " left join app_unidades_medida um on p.id_unidad_venta=um.id ";
                    $queryImpuestos .= " where p.id=" . $idProducto . " and i.id=pi.id_impuesto ";
                    $queryImpuestos .= " Order by pi.id_impuesto ".$ordenform;

                    $resImpues = $this->queryArray($queryImpuestos);
                    //print_r($resImpues['rows']);
                    if($resImpues["total"] <= 0) {
                        $lalala = "SELECT um.clave as medida, a.nombre as nameprod, a.descripcion_larga as larga from app_productos a left join app_unidades_medida um on a.id_unidad_venta=um.id where a.id='$idProducto';";
                        $resSI = $this->queryArray($lalala);
                        if($resSI["total"] > 0) {
                            $impuestos[$idProducto.'-'.$car]['nombre']=$resSI['rows'][0]['nameprod'];
                            $impuestos[$idProducto.'-'.$car]['descripcion']=$resSI['rows'][0]['larga'];
                            $impuestos[$idProducto.'-'.$car]['medida']=$resSI['rows'][0]['medida'];
                            $impuestos[$idProducto.'-'.$car]['cantidad']=$cantidad;
                            $impuestos[$idProducto.'-'.$car]['unidad']=$resSI['rows'][0]['medida'];
                            $impuestos[$idProducto.'-'.$car]['precio']=$precio;
                            $impuestos[$idProducto.'-'.$car]['importe']=$subtotal;
                        }
                    }else{
                        foreach ($resImpues['rows'] as $key => $valueImpuestos) {
                                //echo 'Clave='.$valueImpuestos["clave"].'<br>';
                            /*
                                if ($valueImpuestos["clave"] == 'IEPS') {
                                    //echo 'Y'.$producto_impuesto;
                                    $producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
                                } else {
                                    if ($ieps != 0) {   
                                        $producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);                          
                                    } else {
                                        $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);                              
                                    }
                                }
                            */

                                
                            if ($valueImpuestos["clave"] == 'IEPS') {
                                //echo 'Y'.$producto_impuesto;
                                $producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
                                $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
                            } elseif($valueImpuestos["clave"]=='IVAR' || $valueImpuestos["clave"]=='ISR' || $valueImpuestos["clave"]=='RTP'){



                                $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                                $producto_impuestoR = (($subtotal) * $valueImpuestos["valor"] / 100);
                                $producto_impuestoR += (($subtotal) * $valueImpuestos["valor"] / 100);
                                $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);

                            }else {
                                
                                if ($ieps != 0) {   
                                    //echo 'tiene iepswowkowkdokwdkowdkwkdowkdowdowdowkokwdodokwokdokwooo';
                                    $producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
                              
                                } else {
                                    
                                        $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                                        $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
                                    //}                                 
                                }
                            }

                                $ppii[$idProducto.'-'.$car][]=$valueImpuestos["nombre"].'-'.$valueImpuestos["valor"].'-'.$producto_impuesto;
                                //echo $valueImpuestos["nombre"].' '.$valueImpuestos["valor"].'='.$producto_impuesto.'<br>';

                                $impuestos[$idProducto.'-'.$car]['idProducto']=$valueImpuestos["id"];
                                $impuestos[$idProducto.'-'.$car]['codigo']=$valueImpuestos["codeprod"];
                                $impuestos[$idProducto.'-'.$car]['nombre']=$valueImpuestos["nameprod"];
                                $impuestos[$idProducto.'-'.$car]['descripcion']=$valueImpuestos["descprod"];
                                $impuestos[$idProducto.'-'.$car]['unidad']=$valueImpuestos["medida"];
                                $impuestos[$idProducto.'-'.$car]['medida']=$valueImpuestos["medida"];
                                $impuestos[$idProducto.'-'.$car]['idunidad']=$valueImpuestos["id_unidad_venta"];
                                $impuestos[$idProducto.'-'.$car]['precio']=$precio;
                                $impuestos[$idProducto.'-'.$car]['cantidad']=$cantidad;
                                $impuestos[$idProducto.'-'.$car]['ruta_imagen']='';
                                $impuestos[$idProducto.'-'.$car]['importe']=$subtotal;
                                
                                $impuestos[$idProducto.'-'.$car]['impuesto'] = str_replace(",", "", $impuestos[$idProducto.'-'.$car]['impuesto']) + $producto_impuesto ;
                                $impuestos[$idProducto.'-'.$car]['suma_impuestos'] += $suma_impuestos;
                                $impuestos[$idProducto.'-'.$car]['cargos'][$valueImpuestos["nombre"]] = $producto_impuesto;

                                




                                $totalImpestos += $producto_impuesto;
                                $impuestos['cargos']['impuestos'][$valueImpuestos["clave"]] = $impuestos['cargos']['impuestos'][$valueImpuestos["clave"]] + $producto_impuesto;
                                $impuestos['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] = $impuestos['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] + $producto_impuesto;

                                $impuestos['cargos']['impuestosFactura'][$valueImpuestos["clave"]][$valueImpuestos["valor"]] = $impuestos['cargos']['impuestosFactura'][$valueImpuestos["clave"]][$valueImpuestos["valor"]] + $producto_impuesto;

                                $impuestos['cargos']['impuestosPdf'][$valueImpuestos["clave"]][$valueImpuestos["valor"]]['Valor'] = $impuestos['cargos']['impuestosPdf'][$valueImpuestos["clave"]][$valueImpuestos["valor"]]['Valor'] + $producto_impuesto;

                        }
                        $ieps='0';
                    }
                    //echo 'total='.($subtotal+$producto_impuesto).'<br>';
                }
                $f++;
            }
            

            //print_r($impuestos);
            return $impuestos;
            //print_r($_SESSION['prueba']);
            //echo json_encode($_SESSION['prueba']);
            //unset($_SESSION['prueba');
        }


        public function guardarFacturacion($UUID, $noCertificadoSAT, $selloCFD, $selloSAT, $FechaTimbrado, $idComprobante,$idFact, $idVenta, $noCertificado, $tipoComp, $monto, $cliente, $trackId, $idRefact, $azurian, $estatus, $xmlfile,$fp='1',$numpago) {

  
            $azurianjsondec=json_decode($azurian);
            $azurian = base64_encode($azurian);

            $moneda = $azurianjsondec->Basicos->Moneda;


            $fechaactual = preg_replace('/T/', ' ', $FechaTimbrado);
            if ($idRefact == 'c') {
                $tipoComp = 'C';
                $queryRespuesta = "UPDATE app_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta' AND origen=1 AND idOs=0";
                $this->queryArray($queryRespuesta);
            }


            if($trackId=='refact2'){
                $queryRespuesta = "UPDATE app_respuestaFacturacion SET cancelre=1 WHERE idSale='$idVenta' AND origen=1 and tipoComp='F';";
                $this->queryArray($queryRespuesta);
            }

            if($tipoComp=='C'){
                $lala = "SELECT id_ov FROM app_devolucioncli where id='$idVenta'";
                $rlala = $this->queryArray($lala);
                $id_ov = $rlala['rows'][0]['id_ov'];
            }else{
                $id_ov=0;
            }

            $insertRespuestaFacturacion = "INSERT INTO app_respuestaFacturacion "
            . "(idSale,idOs,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal,xmlfile) VALUES "
            . "('" . $idVenta . "','".$id_ov."','" . $idFact . "','" . $UUID . "','" . $trackId . "','" . $noCertificadoSAT . "','" . $noCertificado . "','" . $selloSAT . "','" . $selloCFD . "','" . $fechaactual . "',0,'" . $tipoComp . "','" . $idComprobante . "','" . $azurian . "','".$xmlfile."');";

            $resultInsert = $this->queryArray($insertRespuestaFacturacion);
            $insertedId = $resultInsert["insertId"];


            $this->queryArray("UPDATE app_envios set forma_pago='$fp', numpago='$numpago' where id='$idVenta'");


            if (is_numeric($insertedId)) {
                $queryUpdateContador = "DELETE FROM app_pagos WHERE origen=1 and concepto='Ticket Venta-".$idVenta."' ";
                $this->queryArray($queryUpdateContador);

                $queryUpdateContador = "UPDATE pvt_contadorFacturas set total=total+1 where id=1";
                $this->queryArray($queryUpdateContador);

                $ContadorLicencias = "UPDATE comun_parametros_licencias set valor=valor-1 where parametro='Facturas'";
                $this->queryArray($ContadorLicencias);

                if ($tipoComp == "R") {
                    $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio_r=folio_r+1 where id=1";
                } else {
                    $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio=folio+1 where id=1";
                }
                $this->queryArray($queryUpdateFolo); 
            }

            if (preg_match('/all/', $idRefact)) {
                $idRefact = preg_replace('/all/', '', $idRefact);
                $updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale in (" . $idRefact . ")";
                $this->queryArray($updatePendienteFactura);
            }

            if ($idRefact > 0 && $idRefact != 'c') {
                $updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'";
                $this->queryArray($updatePendienteFactura);
            }
            //$queryEnvio = "UPDATE app_pos_venta set envio=2 where idVenta=".$idVenta;
            //$this->queryArray($queryEnvio);

            

            $this->creaPoliza($idVenta,$insertedId,$monto,$xmlfile,$moneda,$fechaactual,$tipoComp,0);
            
            return $insertedId;
        }

        public function guardarFacturacionAll($UUID, $noCertificadoSAT, $selloCFD, $selloSAT, $FechaTimbrado, $idComprobante,$idFact, $idVenta, $noCertificado, $tipoComp, $monto, $cliente, $trackId, $idRefact, $azurian, $estatus, $xmlfile) {
            $azurian = base64_encode($azurian);
            $fechaactual = preg_replace('/T/', ' ', $FechaTimbrado);
            

            $insertRespuestaFacturacion = "INSERT INTO app_respuestaFacturacion "
            . "(idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal,xmlfile) VALUES "
            . "('0','" . $idFact . "','" . $UUID . "','" . $trackId . "','" . $noCertificadoSAT . "','" . $noCertificado . "','" . $selloSAT . "','" . $selloCFD . "','" . $fechaactual . "',0,'" . $tipoComp . "','" . $idComprobante . "','" . $azurian . "','".$xmlfile."');";

            $resultInsert = $this->queryArray($insertRespuestaFacturacion);
            $insertedId = $resultInsert["insertId"];


            if (is_numeric($insertedId)) {
                $queryUpdateContador = "UPDATE pvt_contadorFacturas set total=total+1 where id=1";
                $this->queryArray($queryUpdateContador);

                $ContadorLicencias = "UPDATE comun_parametros_licencias set valor=valor-1 where parametro='Facturas'";
                $this->queryArray($ContadorLicencias);

                if ($tipoComp == "R") {
                    $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio_r=folio_r+1 where id=1";
                } else {
                    $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio=folio+1 where id=1";
                }
                $this->queryArray($queryUpdateFolo); 
            }

            
                $updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1, id_respFact='$insertedId' WHERE id_sale in (".$idRefact.")";
                $this->queryArray($updatePendienteFactura);

                $recorredelpag=explode(',', $idRefact);
                foreach ($recorredelpag as $rk => $rv) {
                    $ddq = "DELETE FROM app_pagos WHERE origen=1 and concepto='Ticket Venta-".$rv."' ";
                    $this->queryArray($ddq);
                }
                $this->creaPoliza($idVenta,$insertedId,$monto,$xmlfile,$moneda,$fechaactual,'F',1);
 

            
            return $insertedId;
        }

        public function creaPoliza($idVenta,$idFactura,$monto,$xmlfile,$moneda,$fechaactual,$tipoComp,$multiples)
        {
            ///////////////////////ACONTIA///////////////////////////////
            ////////////////////////////////////////////////////////////
            //Si tiene acontia y esta conectado
            $conexion_acontia = $this->query("SELECT conectar_acontia, pol_autorizacion FROM app_configuracion WHERE id = 1");
            $conexion_acontia = $conexion_acontia->fetch_assoc();
            
            if(intval($conexion_acontia['conectar_acontia']))
            {
                if($tipoComp == 'C')
                {
                    $fact_nota = "Id Nota";
                    $idpol = 9;//Busca la poliza de ventas
                }
                else
                {
                    $fact_nota = "Id Fact";
                    $idpol = 1;//Busca la poliza de devoluciones
                }

                //Busca si es poliza automatica
                $automatica = $this->query("SELECT* FROM app_tpl_polizas WHERE id = $idpol");
                $automatica = $automatica->fetch_assoc();

                //Si es automatica y se genera por documento
                if(intval($automatica['automatica']) && intval($automatica['poliza_por_mov']) == 1)
                {
                    $fechaactual = explode(' ',$fechaactual);
                    $fecha = explode('-',$fechaactual[0]);

                    //Busca el id del ejercicio, si no existe, busca el ultimo y le suma al id para sacar el ejercicio
                    $ejercicio = $this->query("SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = ".$fecha[0]);
                    $ejercicio = $ejercicio->fetch_assoc();
                    $ejercicio = $ejercicio['Id'];

                    //Si no existe calcula el Id
                    if(!intval($ejercicio))
                    {
                        $ejercicioAntes = $this->query("SELECT * FROM cont_ejercicios ORDER BY Id DESC LIMIT 1");
                        $ejercicioAntes = $ejercicioAntes->fetch_assoc();
                        $nuevoEj = intval($fecha[0]) - intval($ejercicioAntes['NombreEjercicio']);
                        $ejercicio = intval($ejercicioAntes['Id']) + $nuevoEj;
                    }

                    $numpol = $this->query("SELECT pp.numpol+1 FROM cont_polizas pp WHERE pp.idtipopoliza = ".$automatica['id_tipo_poliza']." AND pp.activo = 1 AND pp.idejercicio = $ejercicio AND pp.idperiodo = ".intval($fecha[1])." ORDER BY pp.numpol DESC LIMIT 1");
                    $numpol = $numpol->fetch_assoc();
                    $numpol = $numpol['numpol'];
                    if(!intval($numpol))
                        $numpol = 1;
                    $activo = 1;
                    if(intval($conexion_acontia['pol_autorizacion']))
                        $activo = 0;
                    $id_poliza_acontia = $this->insert_id("INSERT INTO cont_polizas(idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, concepto, fecha, fecha_creacion, activo, eliminado, pdv_aut, usuario_creacion, usuario_modificacion)
                 VALUES(1,$ejercicio,".intval($fecha[1]).",$numpol,".$automatica['id_tipo_poliza'].",'".$automatica['nombre_poliza']." $fact_nota: $idFactura','$fecha[0]-$fecha[1]-$fecha[2]',DATE_SUB(NOW(), INTERVAL 6 HOUR), $activo, 0, 0, ".$_SESSION["accelog_idempleado"].", 0)");
                    $cont = 0;//Contador de movimientos
                    $genera_poliza = 1;

                        $cuentas_poliza = $this->query("SELECT id_cuenta, tipo_movto, id_dato, nombre_impuesto FROM app_tpl_polizas_mov WHERE activo = 1 AND id_tpl_poliza = $idpol");
                        $ruta   = "../cont/xmls/facturas/";//Ruta donde se copiara
                            //Genera Movimientos de la poliza
                         //Genera Movimientos de la poliza
                            while($cp = $cuentas_poliza->fetch_assoc())
                            {
                                $cont++;
                                $importe = 0;
                                //Cargo o abono
                                if(intval($cp['tipo_movto']) == 1)
                                    $tipo_movto = "Abono";
                                if(intval($cp['tipo_movto']) == 2)
                                    $tipo_movto = "Cargo";

                                //Abre factura
                                $aa = simplexml_load_file($ruta.'temporales/'.$xmlfile);
                                if($namespaces = $aa->getNamespaces(true))
                                {
                                    $child = $aa->children($namespaces['cfdi']);
                                }
                                
                                $total = 0;
                                $subtotal = 0;
                                $metodo_pago = 0;

                                //Sacar el total y subtotal
                                    foreach($aa->attributes() AS $a => $b)
                                    {
                                        if($a == 'total')
                                        {
                                            $total = $b;
                                        }

                                        if($a == 'subTotal')
                                        {
                                            $subtotal = $b;
                                        }

                                        if($a == 'metodoDePago')
                                        {
                                            $metodo_pago = intval($b);
                                        }
                                    }

                                    

                                //dependiendo el tipo de dato sera el valor que tomara.
                                if(intval($cp['id_dato']) == 2)
                                {
                                    $importe = $subtotal;
                                }
                                elseif(intval($cp['id_dato']) == 3)
                                {
                                    if($cp['nombre_impuesto'])
                                    {
                                        $impu = str_replace('%', '', $cp['nombre_impuesto']);
                                        $impu = explode(' ', $impu);

                                        for($j=0;$j<=(count($child->Impuestos->Traslados->Traslado)-1);$j++)
                                        {
                                            $bandera1 = $bandera2 = $cantidad = 0;
                                            foreach($child->Impuestos->Traslados->Traslado[$j]->attributes() AS $a => $b)
                                            {
                                                if($a == 'impuesto' && strtoupper($b) == $impu[0])
                                                    $bandera1 = 1;
                                                        
                                                if($impu[1] != 'EXENTO')
                                                {
                                                    if($a == 'tasa' && floatval($b) == floatval($impu[1]))
                                                        $bandera2 = 1;
                                                }
                                                else
                                                {
                                                    if($a == 'tasa' && $b == $impu[1])
                                                        $bandera2 = 1;
                                                }
                                                        
                                                if($a == 'importe')
                                                    $cantidad = $b;

                                                if($bandera1 && $bandera2 && $cantidad)
                                                    $importe = $cantidad;
                                            }
                                        }
                                    }   
                                }
                                else
                                {
                                    //Si es total, cliente o proveedor agrega el total en el importe
                                    $importe = $total;
                                }

                                //Si se trata de una facturacion normal
                                if(!intval($multiples))
                                {
                                    $info_venta = $this->query("SELECT forma_pago, (SELECT id_sucursal FROM app_almacenes WHERE id = v.id_almacen) AS id_sucursal, tipo_cambio, v.id_cliente  
                                    FROM app_envios e
                                    INNER JOIN app_oventa v ON v.id = e.id_oventa
                                    INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion
                                    WHERE e.id = $idVenta
                                                ");
                                    $info_venta = $info_venta->fetch_assoc();
                                    if(!intval($info_venta['id_sucursal']))
                                        $info_venta['id_sucursal'] = 1;
                                    if(!$info_venta['tipo_cambio'])
                                        $info_venta['tipo_cambio'] = 1;

                                    //Si el cliente tiene una cuenta asignada entonces no toma en cuenta la cuenta configurada
                                    if(intval($cp['id_dato']) == 4)
                                    {
                                        $cuentaCliProv = $this->query("SELECT cuenta FROM comun_cliente WHERE id = ".$info_venta['id_cliente']);
                                        $cuentaCliProv = $cuentaCliProv->fetch_assoc();
                                        if(intval($cuentaCliProv['cuenta']))
                                            $cp['id_cuenta'] = $cuentaCliProv['cuenta'];
                                    }
                                }
                                else
                                {
                                    $info_venta['id_sucursal'] = 1;
                                    $info_venta['tipo_cambio'] = 1;
                                }



                                $id_mov = $this->insert_id("INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura, FormaPago, tipocambio) 
                                VALUES($id_poliza_acontia, $cont, 1, ".$info_venta['id_sucursal'].", ".$cp['id_cuenta'].", '$tipo_movto', $importe, '$xmlfile','$fact_nota: $idFactura', $activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), '$xmlfile', $metodo_pago, ".$info_venta['tipo_cambio'].")");
                                $ids_movs .= $id_mov.",";

                                //Crear carpeta y copiar xml de la factura, ya se que esta no es el controlador pero no quedaba de otra, asi que hare una excepcion.
                                if(!file_exists($ruta.$id_poliza_acontia))//Si no existe la carpeta de ese poliza la crea
                                {
                                    mkdir ($ruta.$id_poliza_acontia, 0777);
                                }
                                copy($ruta.'temporales/'.$xmlfile, $ruta.$id_poliza_acontia."/".$xmlfile);                             
                                
                            }
                            $this->query("UPDATE app_respuestaFacturacion SET id_poliza_mov = '$ids_movs' WHERE id = $idFactura");
                            $ids_movs = '';
                }
            }


        }

        public function envioFactura($uid, $Email, $azurian, $doc) {

        
            //$azurian=json_decode($azurian);
        $azurian = ventasModel::object_to_array($azurian);
        $datosTimbrado = $azurian['datosTimbrado'];
        $moneda=$azurian['Basicos']['Moneda'];

        if ($azurian['FiscalesEmisor']['noExterior'] == '') {
            $nemi = '';
        } else {
            $nemi = ' #' . $azurian['FiscalesEmisor']['noExterior'];
        }

        if ($azurian['DomicilioReceptor']['noExterior'] == '') {
            $nrec = '';
        } else {
            $nrec = ' #' . $azurian['DomicilioReceptor']['noExterior'];
        }
        include "../../modulos/SAT/PDF/CFDIPDF.php";

        $obj = new CFDIPDF( );

        if ($doc == 3) {
            $doc = "recibo";
        } else {
            $doc = "";
        }
        $azurian['Conceptos']['conceptosOri'] = preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptosOri']);
        $azurian['Conceptos']['conceptosOri'] = preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptosOri']);
            //$obj->ponerColor('#333333');
        $obj->datosCFD($datosTimbrado['UUID'], $azurian['Basicos']['serie'] . ' ' . $azurian['Basicos']['folio'], $datosTimbrado['noCertificado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['noCertificadoSAT'], $azurian['Basicos']['formaDePago'], $azurian['Basicos']['tipoDeComprobante'], $doc,$moneda);
        $obj->lugarE($azurian['Basicos']['LugarExpedicion']);
        $obj->datosEmisor($azurian['Emisor']['nombre'], $azurian['Emisor']['rfc'], $azurian['FiscalesEmisor']['calle'] . $nemi, $azurian['FiscalesEmisor']['localidad'], $azurian['FiscalesEmisor']['colonia'], $azurian['FiscalesEmisor']['municipio'], $azurian['FiscalesEmisor']['estado'], $azurian['FiscalesEmisor']['codigoPostal'], $azurian['FiscalesEmisor']['pais'], $azurian['Regimen']['Regimen']);
        $obj->datosReceptor($azurian['Receptor']['nombre'], $azurian['Receptor']['rfc'], $azurian['DomicilioReceptor']['calle'] . $nrec, $azurian['DomicilioReceptor']['localidad'], $azurian['DomicilioReceptor']['colonia'], $azurian['DomicilioReceptor']['municipio'], $azurian['DomicilioReceptor']['estado'], $azurian['DomicilioReceptor']['codigoPostal'], $azurian['DomicilioReceptor']['pais']);
        $obj->agregarConceptos($azurian['Conceptos']['conceptosOri']);
        $obj->agregarTotal($azurian['Basicos']['subTotal'], $azurian['Basicos']['total'], $azurian['nnf']['nnf']);
        $obj->agregarMetodo($azurian['Basicos']['metodoDePago'], '', $moneda);
        $obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
        $obj->agregarObservaciones($azurian['Observacion']['Observacion']);
        $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $azurian['org']['logo'] . "", 0);

/*        if ($azurian['org']['logo'] != "")
                $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $azurian['org']['logo'] . "", 0);
            else
                $obj->generar("", 0);
*/
        $obj->borrarConcepto();

        $queryIdReceptor = "SELECT nombre from comun_facturacion where rfc='".$azurian['Receptor']['rfc']."' order by nombre desc";
        $resultOne = $this->queryArray($queryIdReceptor);

        /*$queryCupon = "SELECT cupon from comun_cliente_inadem where idCliente=".$resultOne['rows'][0]['nombre'];
        if($this->queryArray($queryCupon)){
            $resultTwo = $this->queryArray($queryCupon);
            $cuponInadem = $resultTwo['rows'][0]['cupon'];
        }else{
           $resultTwo = '';
           $cuponInadem = '';
        }  */
       

        $cuponInadem = '';
        if ($Email != '') {

            require_once('../../modulos/phpmailer/sendMail.php');

            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "NetwareMonitor";
            $mail->Subject = "Factura Generada";
            $mail->AltBody = "NetwarMonitor";
            $mail->MsgHTML('Factura Generada');
            if($cuponInadem==null || $cuponInadem==''){
            $mail->AddAttachment('../../modulos/facturas/'. $uid .'.xml');
            $mail->AddAttachment('../../modulos/facturas/'. $uid .'.pdf');
            }else{
            $mail->AddAttachment('../../modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.xml');
            $mail->AddAttachment('../../modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.pdf');
            } 

            $mail->AddAddress($Email, $Email);


            @$mail->Send();
        }
        //$cuponInadem='';
       if($cuponInadem ==null || $cuponInadem==''){
        return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => false);
       }else{
        return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => $cuponInadem);
       } 
    }

    public function actualizaCancelFact($idFact){
        date_default_timezone_set("Mexico/General");
        $fecha = date('Y-m-d H:i:s');
        $mySql = "UPDATE app_respuestaFacturacion set borrado=3, fecha_cancelacion='$fecha'  where id='$idFact';";
        $this->query($mySql);
    }

    public function revisaPagosFactura($idFact){
        $myQuery = "SELECT count(*) as pagos from app_respuestaFacturacion a
                    inner join app_pagos_relacion b on b.id_documento=a.id
                    where a.id='".$idFact."';";
        $resultque = $this->queryArray($myQuery);
        $tpagos =  $resultque['rows'][0]['pagos'];

        if($tpagos>0){
            return 1;
        }else{
            return 0;
        }
        
    }

    public function acuse($idFact){
        date_default_timezone_set("Mexico/General");
        $myQuery = "SELECT b.rfc, a.fecha_cancelacion, a.folio, a.borrado, a.selloDigitalSat from app_respuestaFacturacion a
                    inner join pvt_configura_facturacion b on b.id=1
                    where a.id='$idFact';";
        $resultque = $this->queryArray($myQuery);
        $fechas =  $resultque['rows'][0]['fecha_cancelacion'];
        $uuid =  $resultque['rows'][0]['folio'];
        $rfc =  $resultque['rows'][0]['rfc'];
        $estatus='Cancelado';
        $sello = $resultque['rows'][0]['selloDigitalSat'];

        $JSON = array('success' =>1, 
            'fecha'=>$fechas, 
            'uuid'=>$uuid, 
            'rfc'=>$rfc, 
            'estatus'=>$estatus, 
            'sello'=>$sello);
            echo json_encode($JSON);
            exit();
        
    }

    public function revisaDiasCancelacion($idFact){
        date_default_timezone_set("Mexico/General");
        $myQuery = "SELECT b.fecha_envio, c.factura_cancelacion from app_respuestaFacturacion a
                    inner join app_envios b on b.id=a.idSale
                    inner join app_configuracion c on c.id=1
                    where a.id='".$idFact."';";
        $resultque = $this->queryArray($myQuery);
        $fechafactura =  $resultque['rows'][0]['fecha_envio'];
        $diasCancelacion =  $resultque['rows'][0]['factura_cancelacion'];
        $fechaActual = date('Y-m-d');

        if($diasCancelacion==0){
            $date1 = new DateTime($fechafactura);
            $date2 = new DateTime($fechaActual);
            if ($date1->format('Y-m') === $date2->format('Y-m')) {
                return -1;
            }else{
                return 1;
            }
        }else{
            $fechaMaximaCancelar = strtotime ( '+'.$diasCancelacion.' day' , strtotime ( $fechafactura ) ) ;
            $fechaMaximaCancelar = date ( 'Y-m-d' , $fechaMaximaCancelar );

            $datetime1 = new DateTime($fechafactura);
            $datetime2 = new DateTime($fechaMaximaCancelar);
            $datetime3 = new DateTime($fechaActual);

            $interval = $datetime2->diff($datetime3);
            $tienedias = $interval->format('%R%a');
            return $tienedias*1;
        }
        
        
    }

    public function revisaVentaTieneNota($idFact){
        date_default_timezone_set("Mexico/General");
        $myQuery = "SELECT  b.id, b.id_ov from app_respuestaFacturacion a
        left join app_devolucioncli b on b.id_envio=a.idSale
        where a.id='".$idFact."';";

        $resultque = $this->queryArray($myQuery);
        $tienenotas=0;
        if($resultque["total"] > 0){
            foreach ($resultque["rows"] as $k => $v) {
                $idDevo =  $v['id'];
                $idOventa =  $v['id_ov'];
                $myQuery2 = "SELECT  count(*) as total from app_respuestaFacturacion WHERE idSale='".$idDevo."' AND idOs='".$idOventa."' AND tipoComp='C';";

                $resultque2 = $this->queryArray($myQuery2);
                $tienenotas = $tienenotas+($resultque2['rows'][0]['total']*1);

            }
   
        }else{
            $tienenotas=0;
        }

        return $tienenotas;

    }

    public function cancelaFactura($idFact){

        require_once('../../modulos/SAT/config.php');
        date_default_timezone_set("Mexico/General");
        $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));

        $SeleCad = "SELECT a.folio, a.idComprobante, a.idSale, b.rfc, b.cer, b.llave, b.clave, a.serieCsdEmisor, a.id_poliza_mov FROM app_respuestaFacturacion a 
        inner join pvt_configura_facturacion b on b.id=1
        WHERE a.id=".$idFact;
        $cadenaOri = $this->queryArray($SeleCad);
        $cancelFac=1;
        $sigue = 0;
        if($cadenaOri["total"] > 0) {
            $rfccancel=  $cadenaOri['rows'][0]['rfc'];
            $cancelFolio=  $cadenaOri['rows'][0]['folio'];
            $cancelID= $cadenaOri['rows'][0]['idComprobante'];
            $elkey= $cadenaOri['rows'][0]['llave'];
            $elcer= $cadenaOri['rows'][0]['cer'];
            $clave= $cadenaOri['rows'][0]['clave'];
            $serieCsdE= $cadenaOri['rows'][0]['serieCsdEmisor'];
            $sigue = 1;
        }else{
            $JSON = array('success' =>0, 
            'error'=>200, 
            'mensaje'=>'No se encontro la factura a cancelar.');
            echo json_encode($JSON);
            exit();
        }

        $strUUID = $cancelFolio;

        $cer_cliente = $pathdc . '/' .$elcer;
        $key_cliente = $pathdc . '/' .$elkey;
        $pwd_cliente = $clave;
        $idFact=$idComunFactu;
        if(!isset($positionPath))
        $positionPath="../../modulos";


        if($serieCsdE=='3'){
            include ($positionPath.'/wsinvoice/cancelInvoice.php');
        }else{

            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');   
        }
        if($sigue && $cadenaOri['rows'][0]['id_poliza_mov'] != '0')
            $this->cancelar_poliza($cadenaOri['rows'][0]['id_poliza_mov']);
    }

    public function cancelar_poliza($id_poliza_mov)
    {
        //Busca si es poliza automatica
        $automatica = $this->query("SELECT* FROM app_tpl_polizas WHERE id = 1");
        $automatica = $automatica->fetch_assoc();

        //Si es automatica y se genera por documento
        if(intval($automatica['automatica']) && intval($automatica['poliza_por_mov']) == 1)
        {
            $mov = explode(",",$id_poliza_mov);
            $id_poliza_original = $this->query("SELECT IdPoliza, Concepto FROM cont_movimientos WHERE Id = ".$mov[0]);
            $id_poliza_original = $id_poliza_original->fetch_assoc();
            $idFact = $id_poliza_original['Concepto'];
            $id_poliza_original = $id_poliza_original['IdPoliza'];
            $idFact = explode(': ',$idFact);
            $idFact = $idFact[1];
            $id_poliza_nueva = $this->insert_id("INSERT INTO cont_polizas (id, idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, referencia, concepto, cargos, abonos, ajuste, fecha, fecha_creacion, activo, eliminado, usuario_creacion) 
                                                SELECT '', idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, referencia, 'Poliza de factura Cancelada: $idFact', cargos, abonos, ajuste, fecha, DATE_SUB(NOW(), INTERVAL 6 HOUR), activo, eliminado, ".$_SESSION["accelog_idempleado"]." FROM cont_polizas WHERE id = $id_poliza_original;");
            $ids_movs = '';
            for($i=0;$i<=count($mov)-2;$i++)
            {
                $id_mov = $this->insert_id("INSERT INTO cont_movimientos (Id, IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura, FormaPago, tipocambio) 
                                          SELECT '', $id_poliza_nueva, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe*-1, Referencia, 'Factura Cancelada: $idFact', Activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), '', FormaPago, tipocambio FROM cont_movimientos WHERE Id = ".$mov[$i]);
                $ids_movs .= $id_mov.",";
            }
            $this->query("UPDATE app_respuestaFacturacion SET id_poliza_mov = '*$ids_movs' WHERE id = $idFact");
            $ids_movs = '';
        }
    }

    public function oneFact($idComunFactu,$idVenta,$txtobs){

        require_once('../../modulos/SAT/config.php');
        date_default_timezone_set("Mexico/General");
        $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));

        $SeleCad = "SELECT cadenaOriginal FROM app_pendienteFactura WHERE origen=1 and id_sale=".$idVenta;
        $cadenaOri = $this->queryArray($SeleCad);
        //echo $cadenaOri['rows'][0]['cadenaOriginal'];
        $azurian=base64_decode($cadenaOri['rows'][0]['cadenaOriginal']);

        $azurian = str_replace("\\", "", $azurian);
        if($azurian!=''){ 
            $azurian=json_decode($azurian); 
        }
        $azurian = $this->object_to_array($azurian);

        $qrpac = "SELECT pac FROM pvt_configura_facturacion WHERE id=1;";
        $respac = $this->queryArray($qrpac);
        $pac = $respac["rows"][0]["pac"]; 

            $queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
            $returnConfiguracion = $this->queryArray($queryConfiguracion);
            if ($returnConfiguracion["total"] > 0) {
                $r = (object) $returnConfiguracion["rows"][0];

                /* DATOS OBLIGATORIOS DEL EMISOR
                ================================================================== */
                $rfc_cliente = $r->rfc;
                //$pac = $r->pac;

                $parametros['EmisorTimbre'] = array();
                
                $parametros['EmisorTimbre']['RFC'] = $r->rfc;
                $parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
                $parametros['EmisorTimbre']['Pais'] = $r->pais;
                $parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
                $parametros['EmisorTimbre']['Calle'] = $r->calle;
                $parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
                $parametros['EmisorTimbre']['Colonia'] = $r->colonia;
                $parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
                $parametros['EmisorTimbre']['Municipio'] = $r->municipio;
                $parametros['EmisorTimbre']['Estado'] = $r->estado;
                $parametros['EmisorTimbre']['CP'] = $r->cp;
                $parametros['EmisorTimbre']['LugarExp'] = $r->lugar_exp;
                
                $cer_cliente = $pathdc . '/' . $r->cer;
                $key_cliente = $pathdc . '/' . $r->llave;
                $pwd_cliente = $r->clave;
                

            } else {

                $JSON = array('success' => 0,
                    'error' => 1001,
                    'mensaje' => 'No existen datos de emisor.');
                echo json_encode($JSON);
                exit();
            }

        /* Datos Receptor
        ============================================================== */
        if($idComunFactu>0){

            $selComFac = "SELECT * FROM comun_facturacion WHERE id=".$idComunFactu;
            $result = $this->queryArray($selComFac);
            //Estado
            $selEstado ="SELECT estado from estados where idestado=".$result['rows'][0]['estado'];
            $resultEstado = $this->queryArray($selEstado);
            

            $idCliente=$rs{'nombre'};
            $azurian['Receptor']['rfc']=strtoupper($result['rows'][0]['rfc']);
            $azurian['Receptor']['nombre']=strtoupper($result['rows'][0]['razon_social']);
            $azurian['DomicilioReceptor']['calle']=$result['rows'][0]['domicilio'];
            $azurian['DomicilioReceptor']['noExterior']=$result['rows'][0]['num_ext'];
            $azurian['DomicilioReceptor']['colonia']=$result['rows'][0]['colonia'];
            $azurian['DomicilioReceptor']['localidad']=$result['rows'][0]['ciudad'];
            $azurian['DomicilioReceptor']['municipio']=$result['rows'][0]['municipio'];
            $azurian['DomicilioReceptor']['estado']=$resultEstado['rows'][0]['estado'];
            $azurian['DomicilioReceptor']['pais']=$result['rows'][0]['pais'];
            $azurian['DomicilioReceptor']['codigoPostal']=$result['rows'][0]['cp'];
            $azurian['Correo']['Correo'] = $result['rows'][0]['correo'];
            
        }else{
            $idCliente='';
            $azurian['Receptor']['rfc']='XAXX010101000';
            $azurian['Receptor']['nombre']='Factura generica';
            $azurian['DomicilioReceptor']['calle']='';
            $azurian['DomicilioReceptor']['noExterior']='';
            $azurian['DomicilioReceptor']['colonia']='';
            $azurian['DomicilioReceptor']['localidad']='';
            $azurian['DomicilioReceptor']['municipio']='';
            $azurian['DomicilioReceptor']['estado']='';
            $azurian['DomicilioReceptor']['pais']='';
            $azurian['DomicilioReceptor']['codigoPostal']='';
            $azurian['Correo']['Correo'] = '';
        }       


        $serFol = "SELECT * FROM pvt_serie_folio WHERE id=1";
        $rs3 = $this->queryArray($serFol);

        $selecLogo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
        $rs4 = $this->queryArray($selecLogo);

        $azurian['org']['logo'] = $rs4['rows'][0]['logoempresa'];

        /* Datos serie y folio
        ============================================================== */
        $azurian['Basicos']['serie']=$rs3['rows'][0]['serie']; //No obligatorio
        $azurian['Basicos']['folio']=$rs3['rows'][0]['folio'];
        $azurian['Basicos']['LugarExpedicion'] = $parametros['EmisorTimbre']['LugarExp'];

        /* Datos Emisor
        ============================================================== */
        $azurian['Emisor']['rfc']=strtoupper($parametros['EmisorTimbre']['RFC']);
        $azurian['Emisor']['nombre']=strtoupper($parametros['EmisorTimbre']['RazonSocial']);
        /* Datos Fiscales Emisor
        ============================================================== */
        $azurian['FiscalesEmisor']['calle']=$parametros['EmisorTimbre']['Calle'];
        $azurian['FiscalesEmisor']['noExterior']=$parametros['EmisorTimbre']['NumExt'];
        $azurian['FiscalesEmisor']['colonia']=$parametros['EmisorTimbre']['Colonia'];
        $azurian['FiscalesEmisor']['localidad']=$parametros['EmisorTimbre']['Ciudad'];
        $azurian['FiscalesEmisor']['municipio']=$parametros['EmisorTimbre']['Municipio'];
        $azurian['FiscalesEmisor']['estado']=$parametros['EmisorTimbre']['Estado'];
        $azurian['FiscalesEmisor']['pais']=$parametros['EmisorTimbre']['Pais'];
        $azurian['FiscalesEmisor']['codigoPostal']=$parametros['EmisorTimbre']['CP'];

        /* Datos Regimen
        ============================================================== */
        $azurian['Regimen']['Regimen']=$parametros['EmisorTimbre']['RegimenFiscal'];

        /* Fecha Factura
        ============================================================== */
        $azurian['Basicos']['fecha']=$fecha;

        /* Impuestos
        ============================================================== */
        $tisr=$azurian['Impuestos']['totalImpuestosRetenidos'];
        $tiva=$azurian['Impuestos']['totalImpuestosTrasladados'];
        $tieps=$azurian['Impuestos']['totalImpuestosIeps']; 

    //    $positionPath='../../webapp/modulos';
        $appministra_nuevo=1;
        $no_permitidas=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
        $permitidas=array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
        $rzst = str_replace($no_permitidas, $permitidas ,$azurian['Receptor']['nombre']);
        $idFact=$idComunFactu;


        if($pac==2){
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){
            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');  
        }


        //if($txtobs=='f123.'){

            //$azurian['Emisor']['rfc']='AAA010101AAA'; #Desarrollo
            
            /* Desarrollo
            $cer_cliente = $pathdc . '/' .'CSD01_AAA010101AAA.cer';
            $key_cliente = $pathdc . '/' .'CSD01_AAA010101AAA.key';
            $pwd_cliente = '12345678a';
            */

            // Produccion
            /*
            $cer_cliente = $pathdc . '/' .'00001000000307601732.cer';
            $key_cliente = $pathdc . '/' .'CSD_MATRIZ_IHA000314A38_20150716_115157.key';
            $pwd_cliente = 'H4G4G2015';
            
            require_once('../../modulos/SAT/funcionesSAT2.php');
            */



        //}else{
          //  require_once('../../modulos/lib/nusoap.php');
            //require_once('../../modulos/SAT/funcionesSAT.php'); 
            //require_once('../../modulos/wsinvoice/lib/nusoap.php');     
        //}
    }

    public function oneFact2($idComunFactu,$idVenta,$txtobs){

        require_once('../../modulos/SAT/config.php');
        date_default_timezone_set("Mexico/General");
        $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));

        $SeleCad = "SELECT cadenaOriginal FROM app_respuestaFacturacion WHERE origen=1 AND tipoComp='F' AND borrado=3 AND idSale=".$idVenta;
        $cadenaOri = $this->queryArray($SeleCad);
        //echo $cadenaOri['rows'][0]['cadenaOriginal'];
        $azurian=base64_decode($cadenaOri['rows'][0]['cadenaOriginal']);

        $azurian = str_replace("\\", "", $azurian);
        if($azurian!=''){ 
            $azurian=json_decode($azurian); 
        }
        $azurian = $this->object_to_array($azurian);

        $qrpac = "SELECT pac FROM pvt_configura_facturacion WHERE id=1;";
        $respac = $this->queryArray($qrpac);
        $pac = $respac["rows"][0]["pac"]; 

            $queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
            $returnConfiguracion = $this->queryArray($queryConfiguracion);
            if ($returnConfiguracion["total"] > 0) {
                $r = (object) $returnConfiguracion["rows"][0];

                /* DATOS OBLIGATORIOS DEL EMISOR
                ================================================================== */
                $rfc_cliente = $r->rfc;
                //$pac = $r->pac;

                $parametros['EmisorTimbre'] = array();
                
                $parametros['EmisorTimbre']['RFC'] = $r->rfc;
                $parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
                $parametros['EmisorTimbre']['Pais'] = $r->pais;
                $parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
                $parametros['EmisorTimbre']['Calle'] = $r->calle;
                $parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
                $parametros['EmisorTimbre']['Colonia'] = $r->colonia;
                $parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
                $parametros['EmisorTimbre']['Municipio'] = $r->municipio;
                $parametros['EmisorTimbre']['Estado'] = $r->estado;
                $parametros['EmisorTimbre']['CP'] = $r->cp;
                
                $cer_cliente = $pathdc . '/' . $r->cer;
                $key_cliente = $pathdc . '/' . $r->llave;
                $pwd_cliente = $r->clave;
                

            } else {

                $JSON = array('success' => 0,
                    'error' => 1001,
                    'mensaje' => 'No existen datos de emisor.');
                echo json_encode($JSON);
                exit();
            }

        /* Datos Receptor
        ============================================================== */
        if($idComunFactu>0){

            $selComFac = "SELECT * FROM comun_facturacion WHERE id=".$idComunFactu;
            $result = $this->queryArray($selComFac);
            //Estado
            $selEstado ="SELECT estado from estados where idestado=".$result['rows'][0]['estado'];
            $resultEstado = $this->queryArray($selEstado);
            

            $idCliente=$rs{'nombre'};
            $azurian['Receptor']['rfc']=strtoupper($result['rows'][0]['rfc']);
            $azurian['Receptor']['nombre']=strtoupper($result['rows'][0]['razon_social']);
            $azurian['DomicilioReceptor']['calle']=$result['rows'][0]['domicilio'];
            $azurian['DomicilioReceptor']['noExterior']=$result['rows'][0]['num_ext'];
            $azurian['DomicilioReceptor']['colonia']=$result['rows'][0]['colonia'];
            $azurian['DomicilioReceptor']['localidad']=$result['rows'][0]['ciudad'];
            $azurian['DomicilioReceptor']['municipio']=$result['rows'][0]['municipio'];
            $azurian['DomicilioReceptor']['estado']=$resultEstado['rows'][0]['estado'];
            $azurian['DomicilioReceptor']['pais']=$result['rows'][0]['pais'];
            $azurian['DomicilioReceptor']['codigoPostal']=$result['rows'][0]['cp'];
            $azurian['Correo']['Correo'] = $result['rows'][0]['correo'];
            
        }else{
            $idCliente='';
            $azurian['Receptor']['rfc']='XAXX010101000';
            $azurian['Receptor']['nombre']='Factura generica';
            $azurian['DomicilioReceptor']['calle']='';
            $azurian['DomicilioReceptor']['noExterior']='';
            $azurian['DomicilioReceptor']['colonia']='';
            $azurian['DomicilioReceptor']['localidad']='';
            $azurian['DomicilioReceptor']['municipio']='';
            $azurian['DomicilioReceptor']['estado']='';
            $azurian['DomicilioReceptor']['pais']='';
            $azurian['DomicilioReceptor']['codigoPostal']='';
            $azurian['Correo']['Correo'] = '';
        }       


        $serFol = "SELECT * FROM pvt_serie_folio WHERE id=1";
        $rs3 = $this->queryArray($serFol);

        $selecLogo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
        $rs4 = $this->queryArray($selecLogo);

        $azurian['org']['logo'] = $rs4['rows'][0]['logoempresa'];

        /* Datos serie y folio
        ============================================================== */
        $azurian['Basicos']['serie']=$rs3['rows'][0]['serie']; //No obligatorio
        $azurian['Basicos']['folio']=$rs3['rows'][0]['folio'];

        /* Datos Emisor
        ============================================================== */
        $azurian['Emisor']['rfc']=strtoupper($parametros['EmisorTimbre']['RFC']);
        $azurian['Emisor']['nombre']=strtoupper($parametros['EmisorTimbre']['RazonSocial']);
        /* Datos Fiscales Emisor
        ============================================================== */
        $azurian['FiscalesEmisor']['calle']=$parametros['EmisorTimbre']['Calle'];
        $azurian['FiscalesEmisor']['noExterior']=$parametros['EmisorTimbre']['NumExt'];
        $azurian['FiscalesEmisor']['colonia']=$parametros['EmisorTimbre']['Colonia'];
        $azurian['FiscalesEmisor']['localidad']=$parametros['EmisorTimbre']['Ciudad'];
        $azurian['FiscalesEmisor']['municipio']=$parametros['EmisorTimbre']['Municipio'];
        $azurian['FiscalesEmisor']['estado']=$parametros['EmisorTimbre']['Estado'];
        $azurian['FiscalesEmisor']['pais']=$parametros['EmisorTimbre']['Pais'];
        $azurian['FiscalesEmisor']['codigoPostal']=$parametros['EmisorTimbre']['CP'];

        /* Datos Regimen
        ============================================================== */
        $azurian['Regimen']['Regimen']=$parametros['EmisorTimbre']['RegimenFiscal'];

        /* Fecha Factura
        ============================================================== */
        $azurian['Basicos']['fecha']=$fecha;

        /* Impuestos
        ============================================================== */
        $tisr=$azurian['Impuestos']['totalImpuestosRetenidos'];
        $tiva=$azurian['Impuestos']['totalImpuestosTrasladados'];
        $tieps=$azurian['Impuestos']['totalImpuestosIeps']; 

    //    $positionPath='../../webapp/modulos';
        $appministra_nuevo=1;
        $no_permitidas=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
        $permitidas=array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
        $rzst = str_replace($no_permitidas, $permitidas ,$azurian['Receptor']['nombre']);
        $idFact=$idComunFactu;

  

        if($pac==2){
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){
            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');  
        }


    }

        public function facturar($idFact, $idVenta, $bloqueo, $mensaje,$consumo, $fp, $devo,$txtobs) {
        
        $monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
        $impuestos = 0;

        $arraytmp = (object) $_SESSION['caja'];
        foreach ($arraytmp as $key => $producto) {
            if ($key != 'cargos') {
                $impuestos = 0;
                foreach ($producto->cargos as $key2 => $value2) {
                    $impuestos += $value2;
                }
            }
        }

        if ($memsaje != false || $mensaje != '') {
            $updateVenta = $this->queryArray("UPDATE app_envios set observacion = '" . $mensaje . "' where idVenta =" . $idVenta);
        }

        $folios = "SELECT serie,folio FROM pvt_serie_folio LIMIT 1";
        $data = $this->queryArray($folios);
        if ($data["total"] > 0) {
            $data = $data["rows"][0];
        }

        $formpag = "SELECT claveSat,nombre FROM forma_pago_caja where idFormapago='".$fp."';";
        $datafp = $this->queryArray($formpag);
        if ($datafp["total"] > 0) {
            $fp=$datafp["rows"][0]["claveSat"];
            $fpnombre=$datafp["rows"][0]["nombre"];
        }else{
            $fp='01';
            $fpnombre='Efectivo';
        }

        $monedas = "SELECT d.codigo from app_envios a
inner join app_oventa b on b.id=a.id_oventa
inner join app_requisiciones_venta c on c.id=b.id_requisicion
inner join cont_coin d on d.coin_id=c.id_moneda
where a.id='$idVenta';";
        $mdata = $this->queryArray($monedas);
        if ($mdata["total"] > 0) {
            $mdata = $mdata["rows"][0];
            $moncode=$mdata['codigo'];
        }else{
            $moncode='MXN';
        }


        
        // Receptor
        //===============================================================

        $parametros['Receptor'] = array();
        if ($idFact == 0) {

            $parametros['Receptor']['RFC'] = "XAXX010101000";
            $parametros['Receptor']['RazonSocial'] = 'Factura Generica';
        } else {
            $df = (object) $this->datosFacturacion($idFact);
            $parametros['Receptor']['RFC'] = $df->rfc;
            $parametros['Receptor']['RazonSocial'] = utf8_decode($df->razon_social);
            $parametros['Receptor']['Pais'] = utf8_decode($df->pais);
            $parametros['Receptor']['Calle'] = utf8_decode($df->domicilio);
            $parametros['Receptor']['NumExt'] = $df->num_ext;
            $parametros['Receptor']['Colonia'] = utf8_decode($df->colonia);
            $parametros['Receptor']['Municipio'] = utf8_decode($df->municipio);
            $parametros['Receptor']['Ciudad'] = utf8_decode($df->ciudad);
            $parametros['Receptor']['CP'] = $df->cp;
            $parametros['Receptor']['Estado'] = utf8_decode($df->estado);
            $parametros['Receptor']['Email1'] = $df->correo;
        }
        //Obteniendo la descripcion de la forma de pago***--***
        /*
        $formapago = "";
        $queryFormaPago = " SELECT nombre,referencia from app_pos_venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;
        $resultqueryFormaPago = $this->queryArray($queryFormaPago);
        foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
            if (strlen($pagosValue["referencia"]) > 0) {
                $formapago .= $pagosValue['nombre'] . " Ref:" . $pagosValue['referencia'] . ",";
            } else {
                $formapago .= $pagosValue['nombre'] . ",";
            }
        }
        $formapago = substr($formapago, 0, strlen($formapago) - 1); 
        if ($formapago == "") {
            $formapago = ".";
        }        
        //echo 'Forma de pago='.$formapago;
        */
        $Email = $df->correo;

        $parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
        $parametros['DatosCFD']['MetododePago'] = utf8_decode($fpnombre).' ('.$fp.')';
        $parametros['DatosCFD']['Moneda'] = $moncode;
        $parametros['DatosCFD']['Subtotal'] = str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["subtotal"],2));
       // $parametros['DatosCFD']['Subtotal'] = $parametros['DatosCFD']['Subtotal'] - 0.01;
        $parametros['DatosCFD']['Total'] = str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["total"],2));
       // $parametros['DatosCFD']['Total'] = $parametros['DatosCFD']['Total'] - 0.01;
        $parametros['DatosCFD']['Serie'] = $data['serie'];
        $parametros['DatosCFD']['Folio'] = $data['folio'];

        $parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
        if($devo=='s'){
            $parametros['DatosCFD']['TipodeComprobante'] = "C"; //F o C
        }
        $parametros['DatosCFD']['MensajePDF'] = "";
        $parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

        $x = 0;
        $textodescuento = "";
        //Empieza a llenar los conceptos
        foreach ($_SESSION['caja'] as $key => $producto) {
            if ($key != 'cargos') {
                $producto = (object) $producto;
                $descuentogeneral = 0;
                ///desceuntos
                //echo "( descuento -> ".$producto->descuento_cantidad.")";
               /* if ($producto->tipodescuento == "%") {
                    $descuentogeneral = (($producto->precioventa * str_replace(",", "", $producto->descuento)) / 100) * $producto->cantidad;
                    if ($producto->descuento > 0) {
                        $textodescuento.=" - " . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . " %";
                    }
                }
                if ($producto->tipodescuento == "$") {
                    $descuentogeneral = $producto->descuento;
                    if ($producto->descuento > 0) {
                        $textodescuento.=" - $" . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . "";
                    }
                } */
                $conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
                $conceptosDatos[$x]["Unidad"] = $producto->unidad;
                $conceptosDatos[$x]["Precio"] = $producto->precio;
                if ($producto->descripcion != '') {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);
                } else {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->nombre . " " . $textodescuento);
                }
                $textodescuento = '';
                //$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio - str_replace(",", "", $producto->descuento) );
                $conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio);
                $consumoTotal +=  $conceptosDatos[$x]['Importe']*1;
                $x++;


            }//fin del if del ciclo
        }//fin del cilo de llenar conceptos

        $nn2 = $_SESSION['caja']['cargos']['impuestosFactura'];
         $nnf = $_SESSION['caja']['cargos']['impuestosPdf'];

        /* FACTURACION AZURIAN
        ============================================================== */
        require_once('../../modulos/SAT/config.php');

        date_default_timezone_set("Mexico/General");
        $fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-7 minute"));


        $logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
        $logo = $this->queryArray($logo);
        $r3 = $logo["rows"][0];

        $qrpac = "SELECT pac FROM pvt_configura_facturacion WHERE id=1;";
        $respac = $this->queryArray($qrpac);
        $pac = $respac["rows"][0]["pac"]; 

        $azurian = array();
        //echo $bloqueo.'??';
        if ($bloqueo == 0) {
            //echo 'entro a bloqueo';
            $queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
            $returnConfiguracion = $this->queryArray($queryConfiguracion);
            if ($returnConfiguracion["total"] > 0) {
                $r = (object) $returnConfiguracion["rows"][0];

                /* DATOS OBLIGATORIOS DEL EMISOR
                ================================================================== */
                $rfc_cliente = $r->rfc;
                //$pac = $r->pac;

                $parametros['EmisorTimbre'] = array();
                $parametros['EmisorTimbre']['LugarExp'] = $r->lugar_exp;
                $parametros['EmisorTimbre']['RFC'] = $r->rfc;
                $parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
                $parametros['EmisorTimbre']['Pais'] = $r->pais;
                $parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
                $parametros['EmisorTimbre']['Calle'] = $r->calle;
                $parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
                $parametros['EmisorTimbre']['Colonia'] = $r->colonia;
                $parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
                $parametros['EmisorTimbre']['Municipio'] = $r->municipio;
                $parametros['EmisorTimbre']['Estado'] = $r->estado;
                $parametros['EmisorTimbre']['CP'] = $r->cp;
                $cer_cliente = $pathdc . '/' . $r->cer;
                $key_cliente = $pathdc . '/' . $r->llave;
                $pwd_cliente = $r->clave;
            } else {

                $JSON = array('success' => 0,
                    'error' => 1001,
                    'mensaje' => 'No existen datos de emisor.');
                echo json_encode($JSON);
                exit();
            }
        }
         /* Observaciones pdf */
        $azurian['Observacion']['Observacion'] = $mensaje;
        /* IMPUESTOS
        ============================================================== */
        if ($nn2 == '') {
            $nn2["IVA"]["0.0"]["Valor"] = 0.00;
        }
        if ($nnf == '') {
            $nnf["IVA"]["0.0"]["Valor"] = 0.00;
        }
        $nn = $nn2;
        $azurian['nn']['nn'] = $nn;
        $azurian['nnf']['nnf'] = $nnf;
        $azurian['org']['logo'] = $r3["logoempresa"];

        /* CORREO RECEPTOR
        ============================================================== */
        $azurian['Correo']['Correo'] = $Email;

        /* Datos Basicos
        ============================================================== */
        $azurian['Basicos']['Moneda'] = $parametros['DatosCFD']['Moneda'];
        $azurian['Basicos']['metodoDePago'] = $parametros['DatosCFD']['MetododePago'];
        $azurian['Basicos']['LugarExpedicion'] = $parametros['EmisorTimbre']['LugarExp'];
        $azurian['Basicos']['version'] = '3.2';
        $azurian['Basicos']['serie'] = $parametros['DatosCFD']['Serie']; //No obligatorio
        $azurian['Basicos']['folio'] = $parametros['DatosCFD']['Folio']; //No obligatorio
        $azurian['Basicos']['fecha'] = $fecha;
        $azurian['Basicos']['sello'] = '';
        $azurian['Basicos']['formaDePago'] = $parametros['DatosCFD']['FormadePago'];
        $azurian['Basicos']['tipoDeComprobante'] = 'ingreso';
        if($devo=='s'){
            $azurian['Basicos']['tipoDeComprobante'] = 'egreso';
        }
        $azurian['tipoFactura'] = 'factura';
        if($devo=='s'){
            $azurian['tipoFactura'] = 'Nota de credito';
        }
        $azurian['Basicos']['noCertificado'] = '';
        $azurian['Basicos']['certificado'] = '';
        $str_subtotal = number_format($parametros['DatosCFD']['Subtotal'], 2);
        $azurian['Basicos']['subTotal'] = str_replace(",", "", $str_subtotal);
        $str_total = number_format($parametros['DatosCFD']['Total'], 2);
        $str_total = str_replace(',', '',$str_total);
        //$str_total = $str_total - 0.01;
        //$str_total = number_format($str_total,0).'.00';  //Comente para que Salgan Decimales Normalmente
        $str_total = number_format($str_total,2);
        $azurian['Basicos']['total'] = str_replace(",", "", $str_total); 

        /* Datos Emisor
        ============================================================== */

        $azurian['Emisor']['rfc'] = strtoupper($parametros['EmisorTimbre']['RFC']);
        $azurian['Emisor']['nombre'] = strtoupper($parametros['EmisorTimbre']['RazonSocial']);

        /* Datos Fiscales Emisor
        ============================================================== */

        $azurian['FiscalesEmisor']['calle'] = $parametros['EmisorTimbre']['Calle'];
        $azurian['FiscalesEmisor']['noExterior'] = $parametros['EmisorTimbre']['NumExt'];
        $azurian['FiscalesEmisor']['colonia'] = $parametros['EmisorTimbre']['Colonia'];
        $azurian['FiscalesEmisor']['localidad'] = $parametros['EmisorTimbre']['Ciudad'];
        $azurian['FiscalesEmisor']['municipio'] = $parametros['EmisorTimbre']['Municipio'];
        $azurian['FiscalesEmisor']['estado'] = $parametros['EmisorTimbre']['Estado'];
        $azurian['FiscalesEmisor']['pais'] = $parametros['EmisorTimbre']['Pais'];
        $azurian['FiscalesEmisor']['codigoPostal'] = $parametros['EmisorTimbre']['CP']; 
        /* Datos Regimen
        ============================================================== */

        $azurian['Regimen']['Regimen'] = $parametros['EmisorTimbre']['RegimenFiscal'];

        /* Datos Receptor
        ============================================================== */

        $azurian['Receptor']['rfc'] = strtoupper($parametros['Receptor']['RFC']);
        $azurian['Receptor']['nombre'] = strtoupper($parametros['Receptor']['RazonSocial']);

        /* Datos Domicilio Receptor
        ============================================================== */

        $azurian['DomicilioReceptor']['calle'] = $parametros['Receptor']['Calle'];
        $azurian['DomicilioReceptor']['noExterior'] = $parametros['Receptor']['NumExt'];
        $azurian['DomicilioReceptor']['colonia'] = $parametros['Receptor']['Colonia'];
        $azurian['DomicilioReceptor']['localidad'] = $parametros['Receptor']['Ciudad'];
        $azurian['DomicilioReceptor']['municipio'] = $parametros['Receptor']['Municipio'];
        $azurian['DomicilioReceptor']['estado'] = $parametros['Receptor']['Estado'];
        $azurian['DomicilioReceptor']['pais'] = $parametros['Receptor']['Pais'];
        $azurian['DomicilioReceptor']['codigoPostal'] = $parametros['Receptor']['CP'];

        $conceptosOri = '';
        $conceptos = '';
        //se emepiza a llenar los conceptos en el arreglo de azurian
        foreach ($conceptosDatos as $key => $value) {
            $value['Descripcion'] = preg_replace("/'/", "&apos;", $value['Descripcion']);
            $value['Descripcion'] = preg_replace('/"/', "&quot;", $value['Descripcion']); 
           // $value['Descripcion'] = preg_replace('("|\')', "&apos;", $value['Descripcion']);
            $value['Descripcion'] = eregi_replace("[\n|\r|\n\r]", " ", $value['Descripcion']);
            $value['Descripcion'] = trim($value['Descripcion']); 

            $conceptosOri.='|' . $value['Cantidad'] . '|';
            $conceptosOri.=$value['Unidad'] . '|';
            $conceptosOri.=$value['Descripcion'] . '|';
            $conceptosOri.=str_replace(",", "", number_format($value['Precio'],2)) . '|';
            $conceptosOri.=str_replace(",", "", number_format($value['Importe'],2));
            $conceptos.="<cfdi:Concepto cantidad='" . $value['Cantidad'] . "' unidad='" . $value['Unidad'] . "' descripcion='" . $value['Descripcion'] . "' valorUnitario='" . str_replace(",", "", number_format($value['Precio'],2)) . "' importe='" . str_replace(",", "", number_format($value['Importe'],2)) . "'/>";
        }
        //////////impuestos azurian
        $ivas = '';
        $tisr = 0.00;
        $tiva = 0.00;
        $tieps = 0.00;

        $oriisr = '';
        $oriiva = '';

        $isr = '';
        $iva = '';
        $rtp = '';
        $azurian['Conceptos']['conceptos'] = $conceptos;
        $azurian['Conceptos']['conceptosOri'] = $conceptosOri;

        $traslads = '';
        $retenids = '';
        $haytras = 0;
        $hayret = 0;
        $trasladsimp = 0.00;
        $retenciones = 0.00;
        $trasxml = '';
        $retexml = '';

        //add rtp
        $complementortp='';
        $hayrtp = 0;
        $tisr2 = 0.00;
        $retexml2 = '';
        $retenids2='';
        //aqui
        foreach ($nn as $clave => $imm) {
            if ($clave == 'IEPS' || $clave == 'IVA') {

                $haytras = 1;
                foreach ($nn[$clave] as $clavetasa => $val) {
                    if($clavetasa=='0.0'){$val=0;}
                    if ($clave == 'IEPS') {
                        $tieps+=number_format($val, 2, '.', '');
                    }
                    if ($clave == 'IVA') {
                       $tiva+=number_format($val, 2, '.', '');
                    }
                    $traslads.='|' . $clave . '|';
                   // $traslads.='' . $clavetasa . '|';
                    $traslads.='' . number_format($clavetasa,2) . '|';
                    $traslads.=number_format($val, 2, '.', '');
                    $trasladsimp+=number_format($val, 2, '.', '');
                    $trasxml.="<cfdi:Traslado impuesto='" . $clave . "' tasa='" . number_format($clavetasa,2) . "' importe='" . number_format($val, 2, '.', '') . "' />";
                }
            } elseif ($clave == 'ISR' || $clave == 'IVAR') {
                $hayret = 1;

                foreach ($nn[$clave] as $clavetasa => $val) {
                    if($clavetasa=='0.0'){$val=0;}
                    if($clave == 'IVAR'){
                        $clave = substr($clave, 0, -1);
                        $king = 1;
                    } 
                    $tisr+=number_format($val, 2, '.', '');
                    $retenids.='|' . $clave . '|';
                    $retenidsT.='' . number_format($val, 2, '.', '') . '|';
                    $retenids.=number_format($val, 2, '.', '');
                    $retenciones+=number_format($val, 2, '.', '');
                    $retexml.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val, 2, '.', '') . "' />";
                    /*if($king ==1){
                        $clave = 'IVAR';
                        $king = 0;
                    } */
                }
                //add rtp
            }elseif ($clave =='RTP'){
                $hayrtp = 1;


     
                foreach ($nn[$clave] as $clavetasa => $val) {
                    if($clavetasa=='0.0'){$val=0;}
                    if($clave == 'IVAR'){
                        $clave = substr($clave, 0, -1);
                        //$king = 1;
                    } 
                    $tisr2+=number_format($val, 2, '.', '');
                    $retenids2.='|' . $clave . '|'.$clavetasa.'|';
                   // $retenidsT2.='' . number_format($val, 2, '.', '') . '|';
                    $retenids2.=number_format($val, 2, '.', '');
                   // $retenciones2+=number_format($val, 2, '.', '');
                    //$retexml2.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val, 2, '.', '') . "' />";
                    /*if($king ==1){
                        $clave = 'IVAR';
                        $king = 0;
                    } */
                    $retexml2.="<implocal:RetencionesLocales ImpLocRetenido='".$clave."' Importe='".number_format($val, 2, '.', '')."' TasadeRetencion='".$clavetasa."' />";


                }

            }
            //aqui
        }////fin del foreach nn

        $azurian['Impuestos']['totalImpuestosIeps'] = $tieps;
        if ($haytras == 1) {
            $iva.='<cfdi:Traslados>' . $trasxml . '</cfdi:Traslados>';
        } else {
            $traslads.='|IVA|';
            $traslads.='0.00|';
            $traslads.='0.00';
            $trasladsimp = '0.00';
            $iva.="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='0.00' importe='0.00' /></cfdi:Traslados>";
        }
        if ($hayret == 1) {
            $isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';
        }
          if($hayret == 1){
            $cadRet = '|'.str_replace(',', '', number_format($tisr,2));
          }else{
            $cadRet = '';
          } 
          //add rtp
        if ($hayrtp == 1) {
            $rtp.='<implocal:ImpuestosLocales version="1.0" TotaldeTraslados="0.00" TotaldeRetenciones="'.$tisr2.'">'.$retexml2.'</implocal:ImpuestosLocales>';

            $rtploc=$retenids2;
            $azurian['Impuestos']['rtp_cad'] = '|1.0|'.$tisr2.'|0.00'.$rtploc;
            //$isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';
        }
        else {
            $azurian['Impuestos']['rtp_cad'] = '';
        }
        //aqui

          $azurian['Impuestos']['isr'] = $retenids.$cadRet;
          $azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');

          $azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
          $azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

          
          $azurian['Impuestos']['rtp'] =$rtp;

        $ivas.=$isr . $iva;

        $azurian['Impuestos']['ivas'] = $ivas;
        /*       
        print_r($azurian); 
        echo json_encode($azurian);
        exit();
        */
        unset($_SESSION['pagos-caja']);
        unset($_SESSION['caja']);

        

        $appministra_nuevo=1;
        $no_permitidas=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
        $permitidas=array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
        $rzst = str_replace($no_permitidas, $permitidas ,$parametros['Receptor']['RazonSocial']);
        
        /*
        if($txtobs=='f123.'){
            $azurian['Emisor']['rfc']='AAA010101AAA';
            //IHA000314A38
            $cer_cliente = $pathdc . '/' .'CSD01_AAA010101AAA.cer';
            $key_cliente = $pathdc . '/' .'CSD01_AAA010101AAA.key';
            $pwd_cliente = '12345678a';
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else{
            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');    
        }
        */

        if($pac==2){
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){

            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');  
        }

        //if($txtobs=='f123.'){

            //$azurian['Emisor']['rfc']='AAA010101AAA'; #Desarrollo
            
            /* Desarrollo
            $cer_cliente = $pathdc . '/' .'CSD01_AAA010101AAA.cer';
            $key_cliente = $pathdc . '/' .'CSD01_AAA010101AAA.key';
            $pwd_cliente = '12345678a';
            */

            // Produccion
            /*
            $cer_cliente = $pathdc . '/' .'00001000000307601732.cer';
            $key_cliente = $pathdc . '/' .'CSD_MATRIZ_IHA000314A38_20150716_115157.key';
            $pwd_cliente = 'H4G4G2015';
            
            require_once('../../modulos/SAT/funcionesSAT2.php');
            */
        //}else{
          //     
        //}


        }//fin funcion facturar();

        public function pendienteFacturacion($idFacturacion, $monto, $cliente, $idventa, $trackId, $azurian, $documento, $devo) {
    
            $azurian = base64_encode($azurian); 
            $fechaactual = date('Y-m-d H:i:s');
            $tipo = ($documento = 2 ? 'F' : 'R');
            if($devo=='s'){
                $tipo='C';
            }
            if (is_numeric($cliente)) {
                echo $query = "INSERT into app_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "'," . $cliente . ",'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "',0,1);";
                $resultquery = $this->queryArray($query);

                    //echo $query;
                return array("status" => true, "type" => 1);
            } else {
                echo $query = "INSERT into app_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "',NULL,'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "',0,1);";
                    //echo $query;
                $resultquery = $this->queryArray($query);
                return array("status" => true, "type" => 2);
            }
        }

        public function datosFacturacion($id) {
            if ($id != '') {
                $datosFacturacion = "SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
                e.estado estado,ciudad,municipio,regimen_fiscal
                from comun_facturacion cf left join estados e on  e.idestado=cf.estado
                where  id=" . $id;

                $result = $this->queryArray($datosFacturacion);

                if ($result["total"] > 0) {
                    return $result["rows"][0];
                }
            } else {
                return false;
            }
        }

    
        function imgProducto($pro){
            $sql = "SELECT ruta_imagen FROM app_productos where nombre = '$pro';";
            $result = $this->queryArray($sql);
            return $result["rows"][0]['ruta_imagen'];
        }
        
        function descProd($idcoti,$producto){
            $sql = "SELECT dv.precio_sin_desc, dv.tipo_desc, dv.monto_desc, dv.precio, p.nombre from app_requisiciones_datos_venta dv
                    left join app_productos p on p.id = dv.id_producto
                    where dv.id_requisicion = ".$idcoti." and p.nombre = '".$producto."';";
            $result = $this->queryArray($sql);
            $psd = number_format($result["rows"][0]['precio_sin_desc'],2);
            
            $tipo = $result["rows"][0]['tipo_desc'];
            $monto = number_format($result["rows"][0]['monto_desc'],2);
            $precio = number_format($result["rows"][0]['precio'],2);

            if($monto > 0){
                if($tipo == 1){
                   return '- $'.$psd.' Desc('.$monto.'%)'; 
               }else{
                $x =  100 - ($precio * 100) / $psd;
                $xF = number_format($x,2);
                return '- $'.$psd.' Desc('.$xF.'%)';
               } 
            }else{
                return '';
            }
        }
        
        function save($idCliente,$observacion,$idcoti,$print,$op,$moneda,$observaciones,$tiporc,$notcorreo='',$msjcorreo='',$desc,$descCant){
        //$_SESSION['cotiza'] = $this->object_to_array($_SESSION['cotiza']);
            //$r=idenvio del envio;
            
            $idOV=$_SESSION['idOV'];

            if($idOV=='' || $idOV==null || $idOV==0){
                $idOV=0;
            }

            $x=0;
        foreach ($_SESSION['caja'] as $key => $producto) {
            if ($key != 'cargos') {
                $producto = (object) $producto;
                $descuentogeneral = 0;
                $conceptosDatos[$x]["Producto"] = $this->imgProducto($producto->nombre);
                $conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
                $conceptosDatos[$x]["Unidad"] = $producto->medida;
                $conceptosDatos[$x]["Precio"] = $producto->precio;


                if ( trim($producto->descripcion) != '') {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion).$this->descProd($idcoti,$producto->nombre);
                } else {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->nombre).$this->descProd($idcoti,$producto->nombre);
                }

                $conceptosDatos[$x]["Descripcion"].= $this->getSerieVenta($producto->idProducto,$idcoti);
                //$conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);

                $textodescuento = '';
                //$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio - str_replace(",", "", $producto->descuento) );
                $conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio);
                $consumoTotal +=  $conceptosDatos[$x]['Importe']*1;
                $x++;


            }//fin del if del ciclo
        }//fin del cilo de llenar conceptos

        $conceptosOri = '';
        //$conceptosOri2 = ''; // para pdf
        $conceptos = '';


        //se emepiza a llenar los conceptos en el arreglo de azurian
        foreach ($conceptosDatos as $key => $value) {
            $value['Descripcion'] = preg_replace("/'/", "&apos;", $value['Descripcion']);
            $value['Descripcion'] = preg_replace('/"/', "&quot;", $value['Descripcion']); 
           // $value['Descripcion'] = preg_replace('("|\')', "&apos;", $value['Descripcion']);
            $value['Descripcion'] = eregi_replace("[\n|\r|\n\r]", " ", $value['Descripcion']);
            $value['Descripcion'] = trim($value['Descripcion']); 

            // PDF
            
            if($value['Producto'] == 'images/productos/' || $value['Producto'] == '' ){
                $value['Producto'] = '';
            }
            
            $conceptosOri2.='|' . $value['Producto'] . '|';
            $conceptosOri2.=$value['Cantidad'] . '|';
            //$conceptosOri.='|' . $value['Cantidad'] . '|';
            $conceptosOri2.=$value['Unidad'] . '|';
            $conceptosOri2.=$value['Descripcion'] . '|';
            $conceptosOri2.=str_replace(",", "", number_format($value['Precio'],2)) . '|';
            $conceptosOri2.=str_replace(",", "", number_format($value['Importe'],2));

            // XML
            $conceptosOri.='|' . $value['Cantidad'] . '|';
            $conceptosOri.=$value['Unidad'] . '|';
            $conceptosOri.=$value['Descripcion'] . '|';
            $conceptosOri.=str_replace(",", "", number_format($value['Precio'],2)) . '|';
            $conceptosOri.=str_replace(",", "", number_format($value['Importe'],2));
            $conceptos.="<cfdi:Concepto cantidad='" . $value['Cantidad'] . "' unidad='" . $value['Unidad'] . "' descripcion='" . $value['Descripcion'] . "' valorUnitario='" . str_replace(",", "", number_format($value['Precio'],2)) . "' importe='" . str_replace(",", "", number_format($value['Importe'],2)) . "'/>";
        }

        $vd=1;

        $nn2 = $_SESSION['caja']['cargos']['impuestosFactura'];
        $nnf = $_SESSION['caja']['cargos']['impuestosPdf'];

       /* if ($nn2 == '') {
            $nn2["IVA"]["0.0"]["Valor"] = 0.00;
        }
        if ($nnf == '') {
            $nnf["IVA"]["0.0"]["Valor"] = 0.00;
        }*/
    
        $idEmpleado=$_SESSION["accelog_idempleado"];
    
       ///////DATOS RECEPTOR

                
        
        $queryClient="SELECT c.nombre, c.direccion, c.colonia, c.email, c.cp, e.estado, m.municipio, c.rfc, c.num_ext, c.num_int ";
        $queryClient.=" from comun_cliente c ";
        $queryClient.=" left join estados e on e.idestado = c.idEstado ";
        $queryClient.=" left join municipios m on m.idmunicipio = c.idMunicipio ";
        $queryClient.=" where c.id=".$idCliente;
        
        //echo $queryClient;
        $result = $this->queryArray($queryClient);
        $Email = $result["rows"][0]["email"]; 

      ////////DATOS EMISOR

        $queryOganizacion="SELECT o.nombreorganizacion,o.RFC,r.descripcion as regimen,o.domicilio,e.estado,m.municipio,o.cp,o.colonia,o.paginaweb,o.logoempresa  
                        from organizaciones o
                        left join nomi_regimenfiscal r on o.idregfiscal = r.idregfiscal 
                        inner join estados e on o.idestado=e.idestado
                        inner join municipios m on o.idmunicipio=m.idmunicipio ";



        $result2 = $this->queryArray($queryOganizacion);

      ////////DATOS COTIZACION-ORDEN VENTA
        $querydatos = "SELECT id, (select concat(nombreEmpleado, ' ', apellidoPaterno, ' ', apellidoMaterno)  from nomi_empleados where idEmpleado = id_solicito) solicito, fecha_entrega  from app_requisiciones_venta where id = ".$idcoti.";";
        $result8 = $this->queryArray($querydatos);
        $solicito = $result8["rows"][0]["solicito"];
        $fecha_entrega = $result8["rows"][0]["fecha_entrega"]; 

            

////////////////PDF
        $insertedCotId=$idcoti;
        $fechaactual = date('Y-m-d H:i:s');
        include "../../modulos/SAT/PDF/COTIZACIONESPDF2.php";
        $obj = new CFDIPDF( );
        $nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
            $obj->datosCFD($insertedCotId, $fechaactual, $tiporc, $moneda, $idOV, $solicito, $fecha_entrega); //ch@ tipo cotizacion o orden de venta para nuevo formato
            $obj->lugarE('MEXICO');

            $obj->datosEmisor($result2["rows"][0]["nombreorganizacion"], $result2["rows"][0]["RFC"], $result2["rows"][0]["domicilio"], $result2["rows"][0]["estado"], $result2["rows"][0]["colonia"], $result2["rows"][0]["municipio"], $result2["rows"][0]["estado"], $result2["rows"][0]["cp"], "Mexico", '');
           
            $obj->datosReceptor($result["rows"][0]["nombre"], $result["rows"][0]["rfc"], $result["rows"][0]["direccion"] . $nrec, $result["rows"][0]["municipio"], $result["rows"][0]["colonia"], $result["rows"][0]["municipio"], $result["rows"][0]["estado"], $result["rows"][0]["cp"], 'Mexico');
            
            
            $nevo=array();
            foreach ($nn2 as $o => $p) {
                foreach ($p as $i => $n) {
                    $nevo[$o][$o.' '.$i]=$n;
                }
            }

            $nevo=$nnf; //Cambio en impuestos que hizo Omar
            if($nevo==null){
                $nevo=array();
            }
            $obj->agregarConceptos('.'.$conceptosOri2);
            $obj->agregarTotal($_SESSION['caja']['cargos']['subtotal'], $_SESSION['caja']['cargos']['total'], $nevo);
            $obj->agregarMetodo('', '', $moneda);
            //$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
            $obj->agregarObservaciones($observaciones);
            $obj->descuento($desc,$descCant); //ch@

            //$obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);            
            
            if ($result2["rows"][0]["logoempresa"] != "")
                $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
            else
                $obj->generar("", 0);




            $obj->borrarConcepto();        

            if($msjcorreo==''){
                $msjcorreohtml = 'Estimado Cliente, envio la cotizacion,';
            }else if($msjcorreo=='1'){
                $msjcorreohtml = 'Estimado Usuario, tiene una cotizacion pendiente por autorizar.';
            }else{
                $msjcorreohtml = '';
            }

            $idcotizaci=$insertedCotId;
            if($idOV=='' || $idOV==null || $idOV==0){
                $insertedCotId = 'cotizacion_'.$insertedCotId;
            }else{
                $insertedCotId = 'Oventa_'.$idOV;
            }

            $unrand=rand(10000,99999);
            $cadrand=$unrand.'-'.$insertedCotId;
            $md5rand=md5($cadrand);
            $md5rand=$md5rand.'.'.$insertedCotId;

            if($idOV==null ){

                $msjcot='<br><br> Para acceder a tu cotizacion entra a <a href="https://www.netwarmonitor.com/clientes/'.$_SESSION['accelog_nombre_instancia'].'/coti/index.php?c='.$md5rand.'">Ver mi cotizacion</a><br><br>Si hay un cambio a su pedido, favor de indicarnos';

                $sese = "UPDATE app_requisiciones_venta SET cadenaCoti='$md5rand' WHERE id='$idcotizaci';";
                $this->query($sese);

            }else{
                $msjcot='';
            }
            
            if($print==0){
                if($notcorreo!=''){
                   $Email=$notcorreo;
                }
                if ($Email != '') {
                      
                        require_once('../../modulos/phpmailer/sendMail.php');

                        $mail->From = "mailer@netwarmonitor.com";
                        $mail->FromName = "NetwarMonitor";
                        $mail->Subject = "Cotizacion";
                        $mail->AltBody = "NetwarMonitor";
                        $mail->MsgHTML($msjcorreohtml.' '.$msjcot.' <br><br>Saludos.');
                        //$mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/Oventa_'.$insertedCotId.".pdf");
                        $mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/'.$insertedCotId.".pdf");
                        $mail->AddAddress($Email, $Email);


                     @$mail->Send();

                            unset($_SESSION['cotiza']);
                            return array('status' => true);
                }else{
                    //echo 'entro al else';
                        unset($_SESSION['cotiza']);
                        return array('status' => false);
                }
            }
                
        }
    }
?>
