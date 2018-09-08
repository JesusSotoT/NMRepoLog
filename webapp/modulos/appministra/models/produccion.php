<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

    class ProduccionModel extends Connection{

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
          

            $myQuery = "SELECT a.id, a.codigo, if(a.descripcion_corta='',a.nombre,a.descripcion_corta) as descripcion_corta, a.precio as costo, x.clave, a.tipo_producto,if(a.minimoprod is null,0,a.minimoprod) as minimo, a.factor FROM app_productos a
                INNER join app_unidades_medida x on x.id=a.id_unidad_venta
                WHERE a.id='$idProducto'  group by a.id;";

            $producto = $this->query($myQuery);
            return $producto;
        }

        function getProductos5()
        {
            $myQuery = "SELECT id, nombre FROM app_productos WHERE (tipo_producto='8' or tipo_producto='9') ORDER BY nombre;";
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

           
            $myQuery = "SELECT a.id_sucursal as idSuc, b.nombre as nombre from app_almacenes a
             LEFT JOIN mrp_sucursal b on b.idSuc=a.id_sucursal group by a.id_sucursal order by b.nombre;";
            $nreq = $this->query($myQuery);
            return $nreq;
        }


  function activar($id){
         $myQuery = "UPDATE app_requisiciones set pr=1 where idprereq='$id';";
                $this->queryArray($myQuery);
        }

        function savePaso2($idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');


            $myQuery = "SELECT * from prd_utilizados WHERE id_oproduccion='$idop' LIMIT 1;";
            $rr = $this->queryArray($myQuery);
            if($rr['total']>0){
                $last_id=$rr['rows'][0]['id'];
            }else{
                $myQuery = "INSERT INTO prd_utilizados (id_oproduccion,fecha_registro,id_usuario) VALUES ('$idop','$creacion',3);";
                $last_id = $this->insert_id($myQuery);
            }
         
            $myQuery = "DELETE FROM prd_utilizados_detalle WHERE id_utilizado='$last_id';";
            $query = $this->query($myQuery);
            

            if($last_id>0){
                $cad='';
                $productos = explode('___', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idPadre=$exp[0];
                    $idHijo=$exp[1];
                    $cant=$exp[2];
                    
                    $cad.="('".$last_id."','".$idHijo."','".$cant."'),";

                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO prd_utilizados_detalle (id_utilizado,id_insumo,cantidad) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo $last_id;

        }

        function savePaso15($costo15_adicional,$costo15_terminado,$idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');


            $myQuery = "DELETE FROM prd_costo_produccion WHERE id_oproduccion='$idop';";
            $query = $this->query($myQuery);

            
            $myQuery = "INSERT INTO prd_costo_produccion (id_oproduccion,fecha_registro,id_usuario,id_paso,costo_adicional,costo_total) VALUES ('$idop', '$creacion', '$paso', 15,'$costo15_adicional','$costo15_terminado');";
            $this->query($myQuery);


            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo 1;

        }
        
        function savePaso6($lote6_nolote,$lote6_fechafab,$lote6_fechacad,$idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

            $myQuery = "SELECT id from app_producto_lotes WHERE no_lote='$lote6_nolote' LIMIT 1;";
            $rr = $this->queryArray($myQuery);
            if($rr['total']>0){
                $last_id=$rr['rows'][0]['id'];
                $myQuery = "UPDATE app_producto_lotes SET fecha_fabricacion='$lote6_fechafab', fecha_caducidad='$lote6_fechacad' WHERE id='$last_id';";
                $this->query($myQuery);
            }else{
                $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$lote6_nolote','$lote6_fechafab','$lote6_fechacad');";
                $last_id = $this->insert_id($myQuery);
            }

            $myQuery = "DELETE FROM prd_lote_detalles WHERE id_oproduccion='$idop';";
            $query = $this->query($myQuery);

            
            $myQuery = "INSERT INTO prd_lote_detalles (id_oproduccion,id_lote,id_usuario) VALUES ('$idop','$last_id',3);";
            $this->query($myQuery);


            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo $last_id;

        }

        function savePaso9($accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

            $myQuery = "UPDATE prd_orden_produccion SET estatus='10', fecha_f='$creacion' WHERE id='$idop';";
            $query = $this->query($myQuery);

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo 1;

        }


        function savePaso1($idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

            $myQuery = "UPDATE prd_orden_produccion SET estatus='9', fecha_p='$creacion' WHERE id='$idop';";
            $query = $this->query($myQuery);

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo 1;

        }

        function savePaso5($idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

                $productos = explode('___', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idMaq=$exp[0];
                    $maquinaria=$exp[1];

                    $myQuery = "UPDATE prd_personal_detalle SET maquinaria='$maquinaria' WHERE id='$idMaq';";
                    $query = $this->query($myQuery);
                }

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);
 

            echo 1;

        }

        function savePaso4($idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');


            $myQuery = "SELECT * from prd_personal WHERE id_oproduccion='$idop' LIMIT 1;";
            $rr = $this->queryArray($myQuery);
            if($rr['total']>0){
                $last_id=$rr['rows'][0]['id'];
            }else{
                $myQuery = "INSERT INTO prd_personal (id_oproduccion,fecha_registro,id_usuario) VALUES ('$idop','$creacion',3);";
                $last_id = $this->insert_id($myQuery);
            }
         
            $myQuery = "DELETE FROM prd_personal_detalle WHERE id_personal='$last_id';";
            $query = $this->query($myQuery);
            


            if($last_id>0){
                $cad='';
                $productos = explode('___', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idEmpleado=$exp[0];
                    $maq=$exp[1];

                    
                    $cad.="('".$last_id."','".$idEmpleado."','".$maq."'),";

                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO prd_personal_detalle (id_personal,id_empleado,maquinaria) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo $last_id;

        }

        function savePaso3($idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');


            $myQuery = "SELECT * from prd_peso WHERE id_oproduccion='$idop' LIMIT 1;";
            $rr = $this->queryArray($myQuery);
            if($rr['total']>0){
                $last_id=$rr['rows'][0]['id'];
            }else{
                $myQuery = "INSERT INTO prd_peso (id_oproduccion,fecha_registro,id_usuario) VALUES ('$idop','$creacion',3);";
                $last_id = $this->insert_id($myQuery);
            }
         
            $myQuery = "DELETE FROM prd_peso_detalle WHERE id_peso='$last_id';";
            $query = $this->query($myQuery);
            

            if($last_id>0){
                $cad='';
                $productos = explode('___', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idPadre=$exp[0];
                    $idHijo=$exp[1];
                    $cant=$exp[2];
                    
                    $cad.="('".$last_id."','".$idHijo."','".$cant."'),";

                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO prd_peso_detalle (id_peso,id_insumo,peso) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo $last_id;

        }

        function savePaso14($idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');


            $myQuery = "SELECT * from prd_merma WHERE id_oproduccion='$idop' LIMIT 1;";
            $rr = $this->queryArray($myQuery);
            if($rr['total']>0){
                $last_id=$rr['rows'][0]['id'];
            }else{
                $myQuery = "INSERT INTO prd_merma (id_oproduccion,fecha_registro,id_usuario) VALUES ('$idop','$creacion',3);";
                $last_id = $this->insert_id($myQuery);
            }
         
            $myQuery = "DELETE FROM prd_merma_detalle WHERE id_merma='$last_id';";
            $query = $this->query($myQuery);
            

            if($last_id>0){
                $cad='';
                $productos = explode('___', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idPadre=$exp[0];
                    $idHijo=$exp[1];
                    $cant=$exp[2];
                    
                    $cad.="('".$last_id."','".$idHijo."','".$cant."'),";

                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO prd_merma_detalle (id_merma,id_insumo,cantidad) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo $last_id;

        }

        function savePaso7($idsProductos,$accion,$idop,$paso){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');


            $myQuery = "SELECT * from prd_batch WHERE id_oproduccion='$idop' LIMIT 1;";
            $rr = $this->queryArray($myQuery);
            if($rr['total']>0){
                $last_id=$rr['rows'][0]['id'];
            }else{
                $myQuery = "INSERT INTO prd_batch (id_oproduccion,fecha_registro,id_usuario) VALUES ('$idop','$creacion',3);";
                $last_id = $this->insert_id($myQuery);
            }
         
            $myQuery = "DELETE FROM prd_batch_detalle WHERE id_batch='$last_id';";
            $query = $this->query($myQuery);
            

            if($last_id>0){
                $cad='';
                $productos = explode('___', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idPadre=$exp[0];
                    $idHijo=$exp[1];
                    $cant=$exp[2];
                    
                    $cad.="('".$last_id."','".$idHijo."','".$cant."'),";

                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO prd_batch_detalle (id_batch,id_insumo,cantidad) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            $myQuery = "INSERT INTO prd_ini_proceso (id_oproduccion,id_paso,id_accion,fecha_guardado) VALUES ('$idop','$paso','$accion','$creacion');";
            $query = $this->query($myQuery);

            echo $last_id;

        }

        function saveOP($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op,$ttt,$sol){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

            $myQuery = "INSERT INTO prd_orden_produccion (id_usuario,id_sucursal,fecha_registro,fecha_inicio,fecha_entrega,estatus,observaciones,prioridad,solicitante) VALUES ('$iduserlog','$sucursal','$creacion','$fecha_registro','$fecha_entrega','1','".$this->nl2brCH($obs)."','$prioridad','$sol');";

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

        function saveUsar($id_op,$iduserlog){
            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');

            $myQuery = "UPDATE prd_orden_produccion SET estatus=4 WHERE id='".$id_op."';";
            $query = $this->query($myQuery);

            echo 1;

        }

        function savePre($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op,$ttt,$orden,$sol){
            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s');


            $o=explode('--c--', $idsProductos);

             
            $arraypro=array();

            foreach ($o as $key => $value) {

                $q=explode('>', $value);
                $idpro=$q[0];
if($idpro!=0){ 

                if (array_key_exists($idpro, $arraypro)) {
                    $arraypro[$q[0]][]=array('idpadre'=>$q[1], 'idproducto'=>$q[2], 'cantidad'=>$q[3],'precio'=>$q[4]);
                }else{

                    $arraypro[$q[0]][]=array('idpadre'=>$q[1], 'idproducto'=>$q[2], 'cantidad'=>$q[3],'precio'=>$q[4]);

                }
            }
                

            
            }


    $myQuery3="SELECT fecha_inicio,fecha_entrega from prd_orden_produccion where id='$id_op'";

                   $result = $this->query($myQuery3);
  
               $row = $result->fetch_array();

               $fecha=$row['fecha_inicio'];
               $fecha_entrega=$row['fecha_entrega'];

       
            foreach ($arraypro as $k => $v) {

   
                $myQuery = "INSERT INTO prd_prerequisicion (id_op,id_usuario,id_proveedor,observaciones_pre,fecha_creacion,activo,subtotal,total) VALUES ('$id_op','$iduserlog','$k','".$this->nl2brCH($obs)."','$creacion','1','0','0');";

                $last_id = $this->insert_id($myQuery);

   
                $myQuery2="SELECT id_moneda from app_productos a 
                 JOIN prd_orden_produccion_detalle b on b.id_orden_produccion='$id_op'
                where a.id=b.id_producto limit 1";

                   $result = $this->query($myQuery2);
  
               $row = $result->fetch_array();
               $moneda=$row['id_moneda'];
               if($moneda==null || $moneda=='' || $moneda==0){
                    $moneda=1;
               }

               if ($orden==1){
                    $au=1;
               }else{
                    $au=0;
               }
            $myQuery = "INSERT INTO app_requisiciones (id_solicito,id_tipogasto,id_almacen,id_moneda,id_proveedor,urgente,inventariable,observaciones,fecha,fecha_entrega,activo,tipo_cambio,pr,subtotal,total,id_usuario,fecha_creacion,idoproduccion,idprereq) VALUES ('$sol','7','1','$moneda','$k','0','1','".$this->nl2brCH($obs)."','$fecha','$fecha_entrega','$au','0','2','$ttt','$ttt','$iduserlog','$creacion','$id_op','$last_id');";

                $last_id2 = $this->insert_id($myQuery);

                if ($orden==1){

 $myQuery = "INSERT INTO app_ocompra (id_proveedor,id_usrcompra,observaciones,fecha,fecha_entrega,activo,id_requisicion,subtotal,total,id_almacen,id_usuario,fecha_creacion,tipo) VALUES ('$k','1','".$this->nl2brCH($obs)."','$fecha','$fecha_entrega','1','$last_id2','$ttt','$ttt','1','$iduserlog','$creacion','1');";

                $last_id3 = $this->insert_id($myQuery);

                }



                if($last_id>0){
                    $cad='';
                    $cad2='';
                      $cad3='';
                    $ptotal=0;
                    foreach ($arraypro[$k] as $k2 => $v2) {
                                   $ptotal+=($v2['precio']*$v2['cantidad']);
                                   $costo=$v2['precio'];
                        $cad.="('".$last_id."','".$v2['idproducto']."','1','1','".$v2['cantidad']."','".$v2['idpadre']."'),";
                        $cad2.="('".$last_id2."','".$v2['idproducto']."','sestemp','1','1','".$v2['cantidad']."','0'),";
                          $cad3.="('".$last_id3."','".$v2['idproducto']."','sestemp','1','1','".$v2['cantidad']."','".$costo."','0','0'),";


                    }
                    $cadtrim = trim($cad, ',');
                     $cadtrim2 = trim($cad2, ',');
                         $cadtrim3 = trim($cad3, ',');
                    $myQuery = "INSERT INTO prd_prerequisicion_datos (id_prerequisicion,id_producto,estatus,activo,cantidad,id_producto_padre) VALUES ".$cadtrim.";";
                    $query = $this->query($myQuery);


                     $myQuery = "INSERT INTO app_requisiciones_datos (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,caracteristica) VALUES ".$cadtrim2.";";
                    $query = $this->query($myQuery);

                      if ($orden==1){
                         $myQuery = "INSERT INTO app_ocompra_datos (id_ocompra,id_producto,ses_tmp,estatus,activo,cantidad,costo,impuestos,caracteristica) VALUES ".$cadtrim3.";";
                    $query = $this->query($myQuery);

                      }

                    $myQuery = "UPDATE app_requisiciones SET subtotal='$ptotal',total='$ptotal' WHERE id='".$last_id2."';";
                    $query = $this->query($myQuery);

     if ($orden==1){
                    $myQuery = "UPDATE app_ocompra SET subtotal='$ptotal',total='$ptotal' WHERE id='".$last_id3."';";
                    $query = $this->query($myQuery);}

                }

            }
           



            $myQuery = "UPDATE prd_orden_produccion SET estatus='2' WHERE id='".$id_op."';";
            $query = $this->query($myQuery);





            echo 'p';

        }

        function modifyOP($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op,$ttt,$sol){

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 

            $myQuery = "UPDATE prd_orden_produccion SET id_usuario='$iduserlog', id_sucursal='$sucursal', fecha_registro='$creacion', fecha_inicio='$fecha_registro', fecha_entrega='$fecha_entrega', solicitante='$sol',observaciones='".$this->nl2brCH($obs)."', prioridad='$prioridad' WHERE id='$id_op'  ";
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


function autorizar($id){

 $myQuery = "UPDATE prd_orden_produccion set autorizado=1 where id='$id'";



            $resultb = $this->query($myQuery);


           return $resultb;
}


        function bandera(){


 $myQuery = "SELECT aut_ord_prod,genoc_sinreq FROM prd_configuracion ;";



            $resultb = $this->query($myQuery);
             

            $row = $resultb->fetch_array();

            return $row;
        }

        function getEmpleados()
        {
            $myQuery = "SELECT a.idEmpleado as idempleado, concat(a.nombreEmpleado,' ',a.apellidoPaterno,' ',a.apellidoMaterno) as nombre, b.nombre as nomarea FROM nomi_empleados a
            left join app_area_empleado b on b.id=a.id_area_empleado ORDER BY a.nombreEmpleado;";
            $empleados = $this->query($myQuery);
            return $empleados;
        }


        function listaOrdenesP(){
            $myQuery = "SELECT a.id, SUBSTRING(a.fecha_registro,1,10) as fr, SUBSTRING(a.fecha_inicio,1,10) as fi, SUBSTRING(a.fecha_entrega,1,10) as fe,d.nombre as sucursal, concat(b.nombre,' ',b.apellidos) as usuario, a.estatus, a.autorizado
            FROM prd_orden_produccion a
            INNER JOIN administracion_usuarios b on b.idempleado=a.id_usuario
            INNER JOIN mrp_sucursal d on d.idSuc=a.id_sucursal 
            ORDER BY a.id desc;";



            $listaReq = $this->query($myQuery);

            return $listaReq;

        }

        function listaOrdenesPre(){
            $myQuery = 'SELECT a.id_op, a.id,   SUBSTRING(a.fecha_creacion,1,10) as fc, d.razon_social,
               case
               when a.activo=1
               then "<span class=\"label label-primary\" style=\"cursor:pointer;\">En espera de insumos </span>"
               when a.activo=3
               then "<span class=\"label label-success\" style=\"cursor:pointer;\">Insumos recibidos</span>"
               end as label,
            case
            when c.pr=1 
            then concat("<button style= \" margin-top:4px;\" disabled=\"true\" onclick=\"activar(", a.id ,");\" class=\"btn btn-primary btn-xs\"><span class=\"glyphicon glyphicon-edit\"></span> Activar</button>")
            else concat("<button style= \" margin-top:4px;\" onclick=\"activar(", a.id ,");\" class=\"btn btn-primary btn-xs\"><span class=\"glyphicon glyphicon-edit\"></span> Activar</button>")
            end as boton

            FROM prd_prerequisicion a
            left JOIN app_requisiciones c on c.idprereq=a.id
            INNER JOIN mrp_proveedor d on d.idPrv=a.id_proveedor 
            ORDER BY a.id desc;';



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }


        function listaOrdenes(){

            $myQuery = '(SELECT a.id,concat("Pasos (",(select count(*) from prd_pasos_producto where id_producto=c.id),")") as pasos,a.fecha_registro,a.fecha_p,a.fecha_f,c.nombre,b.cantidad,d.clave,e.nombre,f.nombre,concat(g.nombreEmpleado," ",g.apellidoPaterno," ",g.apellidoMaterno) as sol,  ((select count(*) from prd_ini_proceso where id_oproduccion=a.id)*100)/count(i.id_paso) as porcentaje,
case a.estatus
when 0 then "<span class=\"label label-danger\" style=\"cursor:pointer;\">Orden eliminada</span>"
when 1 then "<span class=\"label label-default\" style=\"cursor:pointer;\">Registro inicial</span>"

when 2 then "<span class=\"label label-warning\" style=\"cursor:pointer;\">En espera de insumos</span>"
when 3 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 4 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 9 then "<span class=\"label label-info\" style=\"cursor:pointer;\">Produccion iniciada</span>"
when 10 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Produccion finalizada</span>"
end as estatus,concat("<button class=\"btn btn-primary btn-xs\" onClick=seg(",a.id,") style=margin-top:4px;><span class=glyphicon glyphicon-edit></span>Seguimiento</button><br><button class=\"btn btn-primary btn-xs\" onClick=seg2(",a.id,") style=margin-top:4px;><span class=glyphicon glyphicon-edit></span>Seguimiento Material Proceso</button><br><button class=\"btn btn-primary btn-xs\" onClick=segl(",a.id,") style=margin-top:4px;><span class=glyphicon glyphicon-edit></span>Seguimiento ligero</button>") as acciones

            from prd_orden_produccion a
            left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join app_unidades_medida d on d.id=c.id_unidad_venta
            left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
            
            group by a.id
            ORDER BY a.id desc)
            
            union all
            (select a.id,h.descripcion as pasos,"","","","","","","","","",(((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id)) as porcentaje,"",""
            from prd_orden_produccion a
                left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
               left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
                group by a.id,h.id
            ORDER BY a.id desc,h.id asc) order by 1 desc
            ;';





            $listaReq = $this->query($myQuery);
        
            return $listaReq;

        }
                function listaOrdenesf($ffin,$fini,$prod,$suc,$sol,$est){
                   

if($ffin!=''&&$fini!=''){
$filtro=$filtro.' and date(a.fecha_registro) between '.str_replace("-","",$fini).' and '.str_replace("-","",$ffin).' ';}
                    
$prod2=explode(",",$prod);
$sol2=explode(",",$sol);
$suc2=explode(",",$suc);
$est2=explode(",",$est);


                    if(!in_array("null", $prod2)){$filtro=$filtro." and c.id IN (".$prod.") ";}
  if(!in_array("null", $sol2)){$filtro=$filtro." and g.idEmpleado IN (".$sol.") ";

}
   if(!in_array("null", $suc2)){$filtro=$filtro." and e.idSuc IN (".$suc.") ";}
   if(!in_array("null", $est2)){$filtro=$filtro." and a.estatus IN (".$est.") ";}


            $myQueryf = '(SELECT a.id,concat("Pasos (",(select count(*) from prd_pasos_producto where id_producto=c.id),")") as pasos,a.fecha_registro,a.fecha_p,a.fecha_f,c.nombre,b.cantidad,d.clave,e.nombre,f.nombre,concat(g.nombreEmpleado," ",g.apellidoPaterno," ",g.apellidoMaterno) as sol,  ((select count(*) from prd_ini_proceso where id_oproduccion=a.id)*100)/count(i.id_paso) as porcentaje,
case a.estatus
when 0 then "<span class=\"label label-danger\" style=\"cursor:pointer;\">Orden eliminada</span>"
when 1 then "<span class=\"label label-default\" style=\"cursor:pointer;\">Registro inicial</span>"

when 2 then "<span class=\"label label-warning\" style=\"cursor:pointer;\">En espera de insumos</span>"
when 3 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 4 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 9 then "<span class=\"label label-info\" style=\"cursor:pointer;\">Produccion iniciada</span>"
when 10 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Produccion finalizada</span>"
end as estatus,concat("<button class=\"btn btn-primary btn-xs\" onClick=seg(",a.id,") style=margin-top:4px;><span class=glyphicon glyphicon-edit></span>Seguimiento</button><br><button class=\"btn btn-primary btn-xs\" onClick=seg2(",a.id,") style=margin-top:4px;><span class=glyphicon glyphicon-edit></span>Seguimiento Material Proceso</button><br><button class=\"btn btn-primary btn-xs\" onClick=segl(",a.id,") style=margin-top:4px;><span class=glyphicon glyphicon-edit></span>Seguimiento ligero</button>") as acciones

            from prd_orden_produccion a
            left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join app_unidades_medida d on d.id=c.id_unidad_venta
            left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
            where 1=1 '.$filtro.'
            group by a.id
            ORDER BY a.id desc)
            
            union all
            (select a.id,h.descripcion as pasos,"","","","","","","","","",(((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id)) as porcentaje,"",""
            from prd_orden_produccion a
                left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
               left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
            where 1=1 '.$filtro.'
                group by a.id,h.id
            ORDER BY a.id desc,h.id asc) order by 1 desc
            ;';

            $listaReq = $this->query($myQueryf);
            return $listaReq;

        }

         function seg($id){

            $myQuery = '(SELECT concat("Pasos (",(select count(*) from prd_pasos_producto where id_producto=c.id),")") as pasos,a.id,c.nombre,b.cantidad,case 
when sum(k.cantidad*l.costo) is null then 0
when sum(k.cantidad*l.costo) is not null then sum(k.cantidad*l.costo)
end as costo,((select count(*) from prd_ini_proceso where id_oproduccion=a.id)*100)/count(i.id_paso) as porcentaje,
case a.estatus
when 0 then "<span class=\"label label-danger\" style=\"cursor:pointer;\">Orden eliminada</span>"
when 1 then "<span class=\"label label-default\" style=\"cursor:pointer;\">Registro inicial</span>"

when 2 then "<span class=\"label label-warning\" style=\"cursor:pointer;\">En espera de insumos</span>"
when 3 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 4 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 9 then "<span class=\"label label-info\" style=\"cursor:pointer;\">Produccion iniciada</span>"
when 10 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Produccion finalizada</span>"
end as estatus,SEC_TO_TIME( SUM( TIME_TO_SEC(i.tiempo)))

            from prd_orden_produccion a
            left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join app_unidades_medida d on d.id=c.id_unidad_venta
            left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
            left join prd_prerequisicion j on j.id_op=a.id
            left join prd_prerequisicion_datos k on k.id_prerequisicion=j.id
            left join app_costos_proveedor l on l.id_producto=k.id_producto and l.id_proveedor=j.id_proveedor
            where a.id='.$id.'
            group by a.id
            ORDER BY a.id desc)
            
            union all
            (select h.descripcion as pasos,a.id,"","","",(((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id)) as porcentaje,
 case 
when 
          (((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))=0 then "<span class=\"label label-default\" style=\"cursor:pointer;\">No iniciado</span>"

when ((((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))<100 and (((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))>0) then "<span class=\"label label-warning\" style=\"cursor:pointer;\">Iniciado</span>"
when 
            (((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))=100 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Finalizado</span>"
end as estatus


                ,SEC_TO_TIME( SUM( TIME_TO_SEC(i.tiempo)))
            from prd_orden_produccion a
                left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
               left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
               where a.id='.$id.'
                group by a.id,h.id
            ORDER BY a.id desc,h.id asc) order by 1 desc
            ;';





            $listaReq = $this->query($myQuery);
        
            return $listaReq;

        }


function seg2($id){

            $myQuery = '(SELECT concat("Pasos (",(select count(*) from prd_pasos_producto where id_producto=c.id),")") as pasos,a.id,c.nombre,b.cantidad,case 
when sum(k.cantidad*l.costo) is null then 0
when sum(k.cantidad*l.costo) is not null then sum(k.cantidad*l.costo)
end as costo,((select count(*) from prd_ini_proceso where id_oproduccion=a.id)*100)/count(i.id_paso) as porcentaje,
case a.estatus
when 0 then "<span class=\"label label-danger\" style=\"cursor:pointer;\">Orden eliminada</span>"
when 1 then "<span class=\"label label-default\" style=\"cursor:pointer;\">Registro inicial</span>"

when 2 then "<span class=\"label label-warning\" style=\"cursor:pointer;\">En espera de insumos</span>"
when 3 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 4 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 9 then "<span class=\"label label-info\" style=\"cursor:pointer;\">Produccion iniciada</span>"
when 10 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Produccion finalizada</span>"
end as estatus,SEC_TO_TIME( SUM( TIME_TO_SEC(i.tiempo))),"","","","","","1" as orden,""

            from prd_orden_produccion a
            left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join app_unidades_medida d on d.id=c.id_unidad_venta
            left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
            left join prd_prerequisicion j on j.id_op=a.id
            left join prd_prerequisicion_datos k on k.id_prerequisicion=j.id
            left join app_costos_proveedor l on l.id_producto=k.id_producto and l.id_proveedor=j.id_proveedor
            where a.id='.$id.'
            group by a.id
            ORDER BY a.id desc)
            
            union all
            (select h.descripcion as pasos,a.id,"","","",(((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id)) as porcentaje,
 case 
when (((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))=0 then "<span class=\"label label-default\" style=\"cursor:pointer;\">No iniciado</span>"

when ((((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))<100 and (((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))>0) then "<span class=\"label label-warning\" style=\"cursor:pointer;\">Iniciado</span>"
when (((select count(*) from prd_ini_proceso where id_oproduccion=a.id and id_paso=h.id)*100)/count(i.id))=100 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Finalizado</span>"
end as estatus


                ,SEC_TO_TIME( SUM( TIME_TO_SEC(i.tiempo))),"","","","","","2" as orden,h.id        
                from prd_orden_produccion a
                left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
               left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
               where a.id='.$id.'
                group by a.id,h.id
            ORDER BY a.id desc,h.id asc)
                 union all
       (select alias,a.id,"","","","","",tiempo,"",pieza,"","","","3" as orden,h.id
            from prd_orden_produccion a
                 left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join prd_pasos_producto h on h.id_producto=c.id
            left join prd_pasos_acciones_producto i on i.id_paso=h.id
               where a.id='.$id.'
                
            ORDER BY a.id desc,h.id asc
            ) order by 2 desc,15 asc,14 asc
             
            ;
          ';





            $listaReq = $this->query($myQuery);
        
            return $listaReq;

        }

              function segl($id){

            $myQuery = '(select a.id,c.nombre,b.cantidad,case 
when sum(n.cantidad*o.costo) is null then 0
when sum(n.cantidad*o.costo) is not null then sum(n.cantidad*o.costo)
end as costo,
case a.estatus
when 0 then "<span class=\"label label-danger\" style=\"cursor:pointer;\">Orden eliminada</span>"
when 1 then "<span class=\"label label-default\" style=\"cursor:pointer;\">Registro inicial</span>"

when 2 then "<span class=\"label label-warning\" style=\"cursor:pointer;\">En espera de insumos</span>"
when 3 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 4 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Lista para producir</span>"
when 9 then "<span class=\"label label-info\" style=\"cursor:pointer;\">Produccion iniciada</span>"
when 10 then "<span class=\"label label-success\" style=\"cursor:pointer;\">Produccion finalizada</span>"
end as estatus,sum(i.cantidad),sum(j.cantidad),l.clave

            from prd_orden_produccion a
            left join prd_orden_produccion_detalle b on b.id_orden_produccion=a.id
            left join app_productos c on c.id=b.id_producto
            left join app_unidades_medida d on d.id=c.id_unidad_venta
            left join mrp_sucursal e on e.idSuc=a.id_sucursal
            left join almacen f on f.idAlmacen=e.idAlmacen
            left join  nomi_empleados g on g.idEmpleado=a.solicitante
             left join prd_utilizados h on h.id_oproduccion=a.id
             left join prd_utilizados_detalle i on i.id_utilizado=h.id
                  left join prd_merma k on k.id_oproduccion=a.id
             left join prd_merma_detalle j on j.id_merma=k.id
         left join app_unidades_medida l on l.id=c.id_unidad_venta
                left join prd_prerequisicion m on m.id_op=a.id
            left join prd_prerequisicion_datos n on n.id_prerequisicion=m.id
               left join app_costos_proveedor o on o.id_producto=n.id_producto and o.id_proveedor=m.id_proveedor
            where a.id='.$id.'
            group by a.id
            ORDER BY a.id desc)
            union all
            (SELECT
                90 as ids, p.nombre, 
               "","","",i.cantidad,j.cantidad, uni.clave
                FROM app_productos p 
                INNER JOIN app_producto_material m ON p.id=m.id_material 
                LEFT JOIN app_unidades_medida u ON u.id=p.id_unidad_compra 
                LEFT JOIN app_costos_proveedor pro ON pro.id_producto=p.id
                    left join prd_utilizados h on h.id_oproduccion='.$id.'
             left join prd_utilizados_detalle i on i.id_utilizado=h.id and i.id_insumo=p.id
                  left join prd_merma k on k.id_oproduccion='.$id.'
             left join prd_merma_detalle j on j.id_merma=k.id and j.id_insumo=p.id
             left join app_unidades_medida uni on uni.id=p.id_unidad_venta
                WHERE
                p.status=1
                AND
                m.id_producto in (SELECT id_producto FROM prd_orden_produccion_detalle WHERE id_orden_produccion='.$id.') group by p.id) order by 1 asc;';





            $listaReq = $this->query($myQuery);
        
            return $listaReq;

        }

        function editarordenp($idop){

            $myQuery = "SELECT a.id, SUBSTRING(a.fecha_inicio,1,10) as fi, SUBSTRING(a.fecha_entrega,1,10) as fe, d.idSuc as idsuc, a.solicitante as idsol,d.nombre as sucursal, concat(b.nombre,' ',b.apellidos) as username, a.estatus, a.prioridad, a.observaciones, b.idempleado,a.solicitante as idsol FROM prd_orden_produccion a 
            INNER JOIN administracion_usuarios b on b.idempleado=a.id_usuario
            INNER JOIN mrp_sucursal d on d.idSuc=a.id_sucursal 
            WHERE a.id='$idop';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function productosOp($idop,$m){
                    
            if($m==1){

                   $myQuery="SELECT a.*, c.id, c.codigo, c.nombre as nomprod, c.series, c.lotes, c.pedimentos, c.precio as precioorig, x.clave,c.minimos as minimos
                    from prd_orden_produccion_detalle a
                    INNER JOIN app_productos c on c.id = a.id_producto
                    INNER join app_unidades_medida x on x.id=c.id_unidad_venta
                    WHERE a.id_orden_produccion='$idop' group by a.id;";

            }else{
                  $myQuery="SELECT c.id, c.codigo, c.nombre as nomprod, a.cantidad, c.series, c.lotes, c.pedimentos, if(a.precio is null,0,a.precio) as costo,  if(sum(ee.cantidad) is null,0,sum(ee.cantidad)) as cantidadr, a.id_lista, c.precio as precioorig, x.clave, a.caracteristica, c.tipo_producto,c.minimos as minimos from app_requisiciones_datos_venta a
                    INNER JOIN app_productos c on c.id = a.id_producto
                    left join app_envios_datos ee on ee.id_envio='$idEnv'
                     INNER join app_unidades_medida x on x.id=c.id_unidad_venta
                    WHERE a.id_requisicion='$idReq' group by a.id;";
            }

            

            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function sqlPaso2($idop,$idproducto){

                $myQuery="SELECT if(b.cantidad is null,0,b.cantidad) as cantUti
                FROM prd_utilizados a 
                INNER JOIN prd_utilizados_detalle b ON b.id_utilizado=a.id
                WHERE a.id_oproduccion='$idop' AND b.id_insumo='$idproducto';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function sqlPaso3($idop,$idproducto){

                $myQuery="SELECT if(b.peso is null,0,b.peso) as pesoUti
                FROM prd_peso a 
                INNER JOIN prd_peso_detalle b ON b.id_peso=a.id
                WHERE a.id_oproduccion='$idop' AND b.id_insumo='$idproducto';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function sqlPaso14($idop,$idproducto){

                $myQuery="SELECT if(b.cantidad is null,0,b.cantidad) as merma
                FROM prd_merma a 
                INNER JOIN prd_merma_detalle b ON b.id_merma=a.id
                WHERE a.id_oproduccion='$idop' AND b.id_insumo='$idproducto';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function listar_pasos_op($idop){
            $sql="SELECT a.id as id_paso, a.descripcion as nombre_paso, a.id_producto, b.id as id_accion_producto, c.id as id_accion, if(b.alias='',c.nombre,b.alias) as nombre_accion, c.tiempo_hrs, d.nombre, if(e.id is null,0,1) as pasorealizado, b.tipo 
                from prd_pasos_producto a
                inner join prd_pasos_acciones_producto b on b .id_paso=a.id
                inner join prd_acciones c on c.id=b.id_accion
                inner join app_productos d on d.id=a.id_producto
                left join prd_ini_proceso e on e.id_oproduccion='$idop' and e.id_paso=b.id_paso and e.id_accion=c.id
                where a.id_producto in (SELECT id_producto FROM prd_orden_produccion_detalle WHERE id_orden_produccion='$idop') order by b.id asc, c.id asc;";
            // return $sql;
            $result = $this->queryArray($sql);

            return $result;
    }


        function sqlPaso7($idop,$idproducto){

                $myQuery="SELECT if(b.cantidad is null,0,b.cantidad) as cbatch
                FROM prd_batch a 
                INNER JOIN prd_batch_detalle b ON b.id_batch=a.id
                WHERE a.id_oproduccion='$idop' AND b.id_insumo='$idproducto';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function sqlPaso6($idop){

                $myQuery="SELECT b.no_lote, b.fecha_fabricacion, b.fecha_caducidad
                FROM prd_lote_detalles a 
                INNER JOIN app_producto_lotes b ON b.id=a.id_lote
                WHERE a.id_oproduccion='$idop';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function sqlPaso15($idop){

                $myQuery="SELECT *
                FROM prd_costo_produccion 
                WHERE id_oproduccion='$idop';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function sqlPaso4($idop){

                $myQuery="SELECT c.idEmpleado, concat(c.nombreEmpleado,' ',c.apellidoPaterno) as nombre, maquinaria, a.id as idmaq
                FROM prd_personal_detalle a 
                INNER JOIN prd_personal b ON b.id=a.id_personal
                INNER JOIN nomi_empleados c ON c.idEmpleado=a.id_empleado
                WHERE b.id_oproduccion='$idop';";
            $prodsReq = $this->query($myQuery);
            return $prodsReq;


        }

        function productosOpExplosion($idop){
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
                m.id_producto in (SELECT id_producto FROM prd_orden_produccion_detalle WHERE id_orden_produccion='$idop') group by p.id;";

            }

            

            $prodsReq = $this->queryArray($myQuery);
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
FROM app_almacenes a WHERE a.activo = 1 and a.id=1
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

        function getExistenciasT($idProducto,$caracteristicas)
        {
            $caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
            if($caracteristicas != '0'){
                    $carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
            }else{
                $carac='';
            }

            


                 $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := sum((SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac."  AND id_pedimento = 0 AND id_lote = 0  )) AS entradas,
@s := sum((SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac."   AND id_pedimento = 0 AND id_lote = 0  )) AS salidas,
(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
FROM app_almacenes a WHERE a.activo = 1 and a.id=1
ORDER BY a.codigo_sistema;";

                $totpedis = $this->queryArray($myQuery2);
                // $cant=0;
                // foreach ($totpedis['rows'] as $k2 => $v2) {
                //     //$cant+=$v2['cantidad'];

                //     if($v2['cantidad']>0){
                //         $arrPedis[]=array('idAlmacen'=>$v2['id'].'-'.$v2['cantidad'].'-#*-'.$v2['nombre'], 'cantidad'=>$v2['cantidad'], 'almacen'=>$v2['nombre']);
                //     }
                // }

                $cantidad = $totpedis['rows'][0]['entradas']-$totpedis['rows'][0]['salidas'];

            
            return $cantidad;

        }



    }
?>