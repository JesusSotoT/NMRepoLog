<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

    class ConfigModel extends Connection{

        function nl2brCH($string)
        {
            return preg_replace('/\R/u', '<br/><br/>', $string);
        }
        
        function getSeriesProd($idProducto){
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

        function addProductoProduccion($idProducto){
          

            $myQuery = "SELECT a.id, a.codigo, if(a.descripcion_corta='',a.nombre,a.descripcion_corta) as descripcion_corta, a.precio as costo, x.clave, a.tipo_producto FROM app_productos a
                INNER join app_unidades_medida x on x.id=a.id_unidad_venta
                WHERE a.id='$idProducto'  group by a.id;";

            $producto = $this->query($myQuery);
            return $producto;
        }

        function getProductos5()
        {
            $myQuery = "SELECT id, nombre FROM app_productos WHERE tipo_producto='5' ORDER BY nombre;";
            $productos = $this->query($myQuery);
            return $productos;


        }

        function getUsuario(){
            session_start();
            $idusr = $_SESSION['accelog_idempleado'];

            $myQuery = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
            $nreq = $this->query($myQuery);
            //session_destroy();
            return $nreq;
        }

        function getLastOrden()
        {
            $myQuery = "SELECT if(MAX(id) is NULL,1,MAX(id)+1) as id from prd_orden_produccion;";
            $nreq = $this->query($myQuery);
            return $nreq;
        }

        function getSucursales()
        {
            $myQuery = "SELECT idSuc, nombre from mrp_sucursal order by nombre;";
            $nreq = $this->query($myQuery);
            return $nreq;
        }

        function saveConfig($opcion,$gap,$apop,$notc,$hereda,$insdir,$ocop,$ocsinr,$deaalm,$salins,$capaso){
            if($opcion==1){
                $myQuery = "UPDATE prd_configuracion SET gen_aut_ped='$gap', aut_ord_prod='$apop', not_correo='$notc', heredar_op='$hereda', req_insumos='$insdir'  WHERE id='1';";
                $this->query($myQuery);
            }

            if($opcion==2){
                $myQuery = "UPDATE prd_configuracion SET oc_seareq='$ocop', genoc_sinreq='$ocsinr'  WHERE id='1';";
                $this->query($myQuery);
            }

            if($opcion==3){
                $myQuery = "UPDATE prd_configuracion SET designar_almacen='$deaalm', salida_autinsumos='$salins'  WHERE id='1';";
                $this->query($myQuery);
            }

            if($opcion==4){
                $myQuery = "UPDATE prd_configuracion SET capaso='$capaso' WHERE id='1';";
                $this->query($myQuery);
            }

        }

        function loadConfig(){
            $myQuery = "SELECT * FROM prd_configuracion WHERE id=1;";
            $resultModel = $this->query($myQuery);
            return $resultModel;
        }

        function saveOP($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

            $myQuery = "INSERT INTO prd_orden_produccion (id_usuario,id_sucursal,fecha_registro,fecha_inicio,fecha_entrega,estatus,observaciones,prioridad) VALUES ('$iduserlog','$sucursal','$creacion','$fecha_registro','$fecha_entrega','1','".$this->nl2brCH($obs)."','$prioridad');";

            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                $cad='';
                $productos = explode('--c--', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    
                    $cad.="('".$last_id."','".$idprod."','".$cant."'),";

                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO prd_orden_produccion_detalle (id_orden_produccion,id_producto,cantidad) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            return $last_id;

        }

        function savePre($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op){
            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

            $o=explode('--c--', $idsProductos);


            $arraypro=array();

            foreach ($o as $key => $value) {

                $q=explode('>', $value);
                $idpro=$q[0];

                if (array_key_exists($idpro, $arraypro)) {
                    $arraypro[$q[0]][]=array('idpadre'=>$q[1], 'idproducto'=>$q[2], 'cantidad'=>$q[3]);
                }else{

                    $arraypro[$q[0]][]=array('idpadre'=>$q[1], 'idproducto'=>$q[2], 'cantidad'=>$q[3]);

                }
                

            
            }


            foreach ($arraypro as $k => $v) {
   
                $myQuery = "INSERT INTO prd_prerequisicion (id_op,id_usuario,id_proveedor,observaciones_pre,fecha_creacion,activo,subtotal,total) VALUES ('$id_op','$iduserlog','$k','".$this->nl2brCH($obs)."','$creacion','1','0','0');";

                $last_id = $this->insert_id($myQuery);

                if($last_id>0){
                    $cad='';
                    foreach ($arraypro[$k] as $k2 => $v2) {
                        $cad.="('".$last_id."','".$v2['idproducto']."','1','1','".$v2['cantidad']."','".$v2['idpadre']."'),";
                    }
                    $cadtrim = trim($cad, ',');
                    $myQuery = "INSERT INTO prd_prerequisicion_datos (id_prerequisicion,id_producto,estatus,activo,cantidad,id_producto_padre) VALUES ".$cadtrim.";";
                    $query = $this->query($myQuery);
                }

            }

            $myQuery = "UPDATE prd_orden_produccion SET estatus='2' WHERE id='".$id_op."';";
            $query = $this->query($myQuery);

            echo 'p';

        }

        function modifyOP($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 

            $myQuery = "UPDATE prd_orden_produccion SET id_usuario='$iduserlog', id_sucursal='$sucursal', fecha_registro='$creacion', fecha_inicio='$fecha_registro', fecha_entrega='$fecha_entrega', observaciones='".$this->nl2brCH($obs)."', prioridad='$prioridad' WHERE id='$id_op'  ";
            $this->query($myQuery);

            $myQuery = "DELETE FROM prd_orden_produccion_detalle WHERE id_orden_produccion='$id_op';";
            $this->query($myQuery);

            $last_id = $id_op;
            if($last_id>0){
                $cad='';
                $productos = explode('--c--', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    
                    $cad.="('".$last_id."','".$idprod."','".$cant."'),";

                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO prd_orden_produccion_detalle (id_orden_produccion,id_producto,cantidad) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            return $id_op;

        }

        function listaOrdenesP(){
            $myQuery = "SELECT a.id, SUBSTRING(a.fecha_registro,1,10) as fr, SUBSTRING(a.fecha_inicio,1,10) as fi, SUBSTRING(a.fecha_entrega,1,10) as fe,d.nombre as sucursal, concat(b.nombre,' ',b.apellidos) as usuario, a.estatus
            FROM prd_orden_produccion a
            INNER JOIN administracion_usuarios b on b.idempleado=a.id_usuario
            INNER JOIN mrp_sucursal d on d.idSuc=a.id_sucursal 
            ORDER BY a.id desc;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function listaOrdenesPre(){
            $myQuery = "SELECT a.id_op, a.id,   SUBSTRING(a.fecha_creacion,1,10) as fc, d.razon_social
            FROM prd_prerequisicion a
            INNER JOIN mrp_proveedor d on d.idPrv=a.id_proveedor 
            ORDER BY a.id desc;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function editarordenp($idop){

            $myQuery = "SELECT a.id, SUBSTRING(a.fecha_inicio,1,10) as fi, SUBSTRING(a.fecha_entrega,1,10) as fe, d.idSuc as idsuc, d.nombre as sucursal, concat(b.nombre,' ',b.apellidos) as username, a.estatus, a.prioridad, a.observaciones, b.idempleado FROM prd_orden_produccion a 
            INNER JOIN administracion_usuarios b on b.idempleado=a.id_usuario
            INNER JOIN mrp_sucursal d on d.idSuc=a.id_sucursal 
            WHERE a.id='$idop';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function productosOp($idop,$m){
                    
            if($m==1){

                   $myQuery="SELECT a.*, c.id, c.codigo, c.nombre as nomprod, c.series, c.lotes, c.pedimentos, c.precio as precioorig, x.clave
                    from prd_orden_produccion_detalle a
                    INNER JOIN app_productos c on c.id = a.id_producto
                    INNER join app_unidades_medida x on x.id=c.id_unidad_venta
                    WHERE a.id_orden_produccion='$idop' group by a.id;";

            }else{
                  $myQuery="SELECT c.id, c.codigo, c.nombre as nomprod, a.cantidad, c.series, c.lotes, c.pedimentos, if(a.precio is null,0,a.precio) as costo,  if(sum(ee.cantidad) is null,0,sum(ee.cantidad)) as cantidadr, a.id_lista, c.precio as precioorig, x.clave, a.caracteristica, c.tipo_producto from app_requisiciones_datos_venta a
                    INNER JOIN app_productos c on c.id = a.id_producto
                    left join app_envios_datos ee on ee.id_envio='$idEnv'
                     INNER join app_unidades_medida x on x.id=c.id_unidad_venta
                    WHERE a.id_requisicion='$idReq' group by a.id;";
            }

            

            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function productosOpExplosion($idop,$idproducto){
            $m=1;
            if($m==1){

                $myQuery="SELECT
                p.id AS idProducto, p.nombre, IF(p.tipo_producto=4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
                p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, p.tipo_producto, p.descripcion_corta,
                (SELECT nombre FROM app_unidades_medida uni WHERE uni.id=p.id_unidad_venta) AS unidad,
                (SELECT clave FROM app_unidades_medida uni WHERE uni.id=p.id_unidad_venta) AS unidad_clave, p.codigo, u.factor, m.cantidad, m.opcionales AS opcionales,  GROUP_CONCAT(pro.id) as idcostoprovs
                FROM app_productos p INNER JOIN app_producto_material m ON p.id=m.id_material LEFT JOIN app_unidades_medida u ON u.id=p.id_unidad_compra LEFT JOIN app_costos_proveedor pro ON
                pro.id_producto=p.id
                WHERE
                p.status=1
                AND
                m.id_producto ='$idproducto' group by p.id;";

            }

            

            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function proveedoresCostoOP($proveedores){

       

            $myQuery = "SELECT a.costo, a.id_proveedor, b.razon_social FROM app_costos_proveedor a inner join mrp_proveedor b on b.idPrv=a.id_proveedor where a.id in($proveedores);";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function delOP($idop){
            $myQuery = "UPDATE prd_orden_produccion SET estatus=0 WHERE id='$idop';";
            $update = $this->query($myQuery);
            return $update;
        }

    }
?>