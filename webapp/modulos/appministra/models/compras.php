<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

    class ComprasModel extends Connection
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
            $myQuery = "SELECT if(MAX(id) is NULL,1,MAX(id)+1) as id from app_requisiciones;";
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

        function modCostoCompras(){
            $myQuery = "SELECT mod_costo_compras from app_configuracion where id=1;";
            $nreq = $this->query($myQuery);
            //session_destroy();
            return $nreq;
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

        function getProveedores()
        {
            $myQuery = "SELECT idPrv, razon_social FROM mrp_proveedor ORDER BY razon_social;";
            $proveedores = $this->query($myQuery);
            return $proveedores;
        }
        function getProductos()
        {
            $myQuery = "SELECT idProducto, nombre FROM mrp_producto ORDER BY nombre;";
            $proveedores = $this->query($myQuery);
            return $proveedores;
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
ORDER BY a.coin_id;";
            $monedas = $this->query($myQuery);
            return $monedas;
        }
        function addProductoReq($idProducto, $idProveedor)
        {
            $myQuery = "SELECT a.id, a.codigo, if(a.descripcion_corta='',a.nombre,a.descripcion_corta) as descripcion_corta, b.costo, c.clave FROM app_productos a
            INNER JOIN app_costos_proveedor b on b.id_producto=a.id AND b.id_proveedor='$idProveedor'
            INNER join app_unidades_medida c on c.id=a.id_unidad_compra
            WHERE a.id='$idProducto';";
            $producto = $this->query($myQuery);
            return $producto;
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
        function getProvProducto($idProveedor,$idmoneda)
        {
            $myQuery = "SELECT a.id_producto, b.id, concat(b.nombre,' (',b.codigo,') ') as descripcion_corta, count(d.id) as tc FROM app_producto_proveedor a 
            INNER JOIN app_productos b on b.id=a.id_producto
            inner JOIN cont_coin c on c.coin_id=b.id_moneda
            left join app_producto_caracteristicas d on d.id_producto=b.id
            WHERE a.id_proveedor='$idProveedor' and b.id_moneda='$idmoneda' and b.status=1 group by b.id;";
            $producto = $this->query($myQuery);
            return $producto;
        }
        function getTipoGasto()
        {
            $myQuery = "SELECT id,CONCAT(nombreclasificador,' (',codigo,')') as nombreclasificador FROM bco_clasificador WHERE idtipo=2 AND idNivel=1 and activo='-1' ORDER BY nombreclasificador";
            $tipoGasto = $this->query($myQuery);
            return $tipoGasto;
        }

        function getAlmacen()
        {
            $myQuery = "SELECT id,nombre FROM app_almacenes WHERE (id_almacen_tipo=1 OR id_almacen_tipo=5) AND activo = 1 ORDER BY id;";
            $almacenes = $this->query($myQuery);
            return $almacenes;
        }

        function getAlmacenes()
        {
            $myQuery = "SELECT * FROM app_almacenes WHERE es_consignacion= 0 AND activo = 1 ORDER BY id_almacen_tipo asc, codigo_sistema asc;";
            $almacenes = $this->queryArray($myQuery);
            return $almacenes;
        }

        function deleteReq($idReq)
        {
            $myQuery = "UPDATE app_requisiciones SET activo=2 WHERE id='$idReq' AND id!='1';";
            $update = $this->query($myQuery);
            return $update;
        }

        function solacla($idReq)
        {
            $myQuery = "UPDATE app_requisiciones SET activo=6 WHERE id in (SELECT id_requisicion FROM app_ocompra WHERE id='$idReq');";
            $update = $this->query($myQuery);

            $myQuery = "UPDATE app_ocompra SET activo=6 WHERE id='$idReq';";
            $update = $this->query($myQuery);
            return $update;
        }

        function productosTicket($idprod){
            $myQuery = "SELECT a.nombre, a.codigo, a.id FROM app_productos a where id='$idprod';";
            $producto = $this->queryArray($myQuery);
            return $producto['rows'][0];
        }

        function guardaXmlAdju($fac_folio,$fac_fecha,$fac_total,$fac_uuid,$fac_concepto,$xmlfile,$idoc,$subtotal)
        {

            date_default_timezone_set("Mexico/General");
            $fecha_subida=date('Y-m-d H:i:s'); 

            $myQuery = "INSERT INTO app_recepcion_xml (id_oc,fecha_factura,imp_factura,xmlfile,concepto,fecha_subida) VALUES ('$idoc','$fac_fecha','$fac_total','$xmlfile','$fac_concepto','$fecha_subida');";
            $last_id = $this->insert_id($myQuery);

            if($last_id>0){

                $myQuery = "SELECT a.id_recepcion from app_recepcion_datos a
                        inner join app_recepcion b on b.id=a.id_recepcion
                        where b.id_oc=".$idoc.";";
                $resultque = $this->queryArray($myQuery);

                if($resultque['total']>0){
                    foreach ($resultque['rows'] as $k => $v) {
                        $myQuery2 = "DELETE FROM app_pagos where concepto='Recepcion-".$v['id_recepcion']."' ";
                        $this->query($myQuery2);
                    }
                }

                ///////////////////////ACONTIA///////////////////////////////
                ////////////////////////////////////////////////////////////

                //Si tiene acontia y esta conectado
                $conexion_acontia = $this->query("SELECT conectar_acontia, pol_autorizacion FROM app_configuracion WHERE id = 1");
                $conexion_acontia = $conexion_acontia->fetch_assoc();
                
                if(intval($conexion_acontia['conectar_acontia']))
                {
                    //Buscar el tipo de gasto
                    $tipo_gasto = $this->query("SELECT rq.id_tipogasto, c.id_proveedor, rq.tipo_cambio FROM app_requisiciones rq 
                                                INNER JOIN app_ocompra c ON c.id_requisicion = rq.id WHERE c.id = $idoc");
                    $tipo_gasto = $tipo_gasto->fetch_assoc();
                    $id_proveedor = $tipo_gasto['id_proveedor'];
                    $tipo_cambio = $tipo_gasto['tipo_cambio'];
                    if(!intval($tipo_cambio))
                        $tipo_cambio = 1;
                    $tipo_gasto = $tipo_gasto['id_tipogasto'];

                    //Si la compra esta relacionada a un tipo de gasto continua
                    if(intval($tipo_gasto))
                    {
                        //Busca si es poliza automatica HACE UN LIMIT POR SI EXISTE MAS DE UNA TOMARA LA ULTIMA CONFIGURACION
                        $automatica = $this->query("SELECT* FROM app_tpl_polizas WHERE id > 9 AND id_gasto = $tipo_gasto ORDER BY id DESC LIMIT 1");
                        $automatica = $automatica->fetch_assoc();
                        $idpol = $automatica['id'];

                        //Si es automatica y se genera por documento CONTINUA
                        if(intval($automatica['automatica']) && intval($automatica['poliza_por_mov']) == 1)
                        {
                            $fecha = explode('-',$fac_fecha);

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

                            //Genera la poliza
                            $id_poliza_acontia = $this->insert_id("INSERT INTO cont_polizas(idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, referencia, concepto, fecha, fecha_creacion, activo, eliminado, pdv_aut, usuario_creacion, usuario_modificacion)
                                 VALUES(1,$ejercicio,".intval($fecha[1]).",$numpol,".$automatica['id_tipo_poliza'].",'Poliza Fac. $fac_uuid','".$automatica['nombre_poliza']." $fac_concepto','$fac_fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR), $activo, 0, 0, ".$_SESSION["accelog_idempleado"].", 0)");
                            $cont = 0;//Contador de movimientos
                            
                            $cuentas_poliza = $this->query("SELECT id_cuenta, tipo_movto, id_dato, nombre_impuesto FROM app_tpl_polizas_mov WHERE activo = 1 AND id_tpl_poliza = $idpol");
                            
                            $ruta   = "../cont/xmls/facturas/";//Ruta donde se copiara
                            //Genera Movimientos de la poliza
                            while($cp = $cuentas_poliza->fetch_assoc())
                            {
                                $cont++;
                                //Cargo o abono
                                if(intval($cp['tipo_movto']) == 1)
                                    $tipo_movto = "Abono";
                                if(intval($cp['tipo_movto']) == 2)
                                    $tipo_movto = "Cargo";

                                //dependiendo el tipo de dato sera el valor que tomara.
                                if(intval($cp['id_dato']) == 2)
                                {
                                    //Si es el subtotal
                                    $importe = $subtotal;
                                }
                                elseif(intval($cp['id_dato']) == 3)
                                {
                                    $importe = 0;
                                    if($cp['nombre_impuesto'])
                                    {
                                        $impu = str_replace('%', '', $cp['nombre_impuesto']);
                                        $impu = explode(' ', $impu);
                                        //Si es el impuesto
                                        $aa = simplexml_load_file($ruta.'temporales/'.$xmlfile);
                                        if($namespaces = $aa->getNamespaces(true))
                                        {
                                            $child = $aa->children($namespaces['cfdi']);
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
                                        //unset($aa);
                                    }
                                }
                                else
                                {
                                    //Si es total, cliente o proveedor agrega el total en el importe
                                    $importe = $fac_total;
                                }

                                

                                $id_mov = $this->insert_id("INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura, FormaPago, tipocambio) 
                                VALUES($id_poliza_acontia, $cont, 1, 1, ".$cp['id_cuenta'].", '$tipo_movto', $importe, '','".$automatica['nombre_poliza']." $fac_concepto $impuesto', $activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), '$xmlfile', 1, $tipo_cambio)");
                                $ids_movs .= $id_mov.",";

                                //Crear carpeta y copiar xml de la factura, ya se que esta no es el controlador pero no quedaba de otra, asi que hare una excepcion.
                                if(!file_exists($ruta.$id_poliza_acontia))//Si no existe la carpeta de ese poliza la crea
                                {
                                    mkdir ($ruta.$id_poliza_acontia, 0777);
                                }
                                copy($ruta.'temporales/'.$xmlfile, $ruta.$id_poliza_acontia."/".$xmlfile);    
                                

                            }
                            $this->query("UPDATE app_recepcion_xml SET id_poliza_mov = '$ids_movs' WHERE id = $last_id");
                            $ids_movs = '';
                        }
                    }
                }


                //Termina conexion con acontia
                ////////////////////////////////////////////////////////////
                ////////////////////////////////////////////////////////////

            }

            return $last_id;

        }

        function saveRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$almacen,$obs,$ist,$it,$iduserlog)
        {

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 
 

            $myQuery = "INSERT INTO app_requisiciones (id_solicito,id_tipogasto,id_almacen,id_moneda,id_proveedor,urgente,inventariable,observaciones,fecha,fecha_entrega,activo,tipo_cambio,pr,subtotal,total,id_usuario,fecha_creacion) VALUES ('$solicitante','$tipogasto','$almacen','$moneda','$proveedor','$urgente','$inventariable','".$this->nl2brCH($obs)."','$fechahoy','$fechaentrega',0,'$moneda_tc',1,'$ist','$it',$iduserlog,'$creacion');";
            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $caracteristica=$exp[2];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$caracteristica."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            return $last_id;

        }

        function modifyRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$idrequi,$almacen,$obs,$ist,$it,$iduserlog)
        {

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 
            $myQuery = "UPDATE app_requisiciones SET id_solicito='$solicitante', id_tipogasto='$tipogasto', id_almacen='$almacen', id_moneda='$moneda', id_proveedor='$proveedor', urgente='$urgente', inventariable='$inventariable' , observaciones='$obs', fecha='$fechahoy', fecha_entrega='$fechaentrega', activo=0, tipo_cambio='$moneda_tc', subtotal='$ist', total='$it', id_usuario='$iduserlog', fecha_creacion='$creacion' WHERE id='$idrequi'  ";
            $this->query($myQuery);

            $myQuery = "DELETE FROM app_requisiciones_datos WHERE id_requisicion='$idrequi';";
            $this->query($myQuery);

            $last_id = $idrequi;
            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $caracteristica=$exp[2];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$caracteristica."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            return $idrequi;

        }

        function modifyOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo,$obs,$ist,$it,$cadimps,$iduserlog)
        {

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 

            session_start();
            $idusr = $_SESSION['accelog_idempleado'];


            $myQuery = "UPDATE app_requisiciones SET id_solicito='$solicitante', id_tipogasto='$tipogasto', id_almacen='$almacen', id_moneda='$moneda', id_proveedor='$proveedor', urgente='$urgente', inventariable='$inventariable' , observaciones='$obs', fecha='$fechahoy', fecha_entrega='$fechaentrega', activo='$idactivo', tipo_cambio='$moneda_tc', subtotal='$ist', total='$it' WHERE id='$idrequi'  ";
            $this->query($myQuery);

            $myQuery = "DELETE FROM app_requisiciones_datos WHERE id_requisicion='$idrequi';";
            $this->query($myQuery);

            $last_id = $idrequi;
            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=$exp[0];
                    $cant=$exp[1];
                    $caracteristica=$exp[3];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$caracteristica."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }


            $myQuery = "SELECT id from app_ocompra WHERE id_requisicion='$idrequi';";
            $res = $this->query($myQuery);

            if($res->num_rows>0){
                $row = $res->fetch_array();
                $last_id2=$row['id'];
                $myQuery = "UPDATE app_ocompra SET id_proveedor='$proveedor',id_usrcompra=1,observaciones='$obs', fecha='$fechahoy', fecha_entrega='$fechaentrega',activo='$idactivo',subtotal='$ist',total='$it', id_almacen='$almacen', id_usuario='$idusr', fecha_creacion='$creacion' WHERE id_requisicion='$idrequi';";
                $this->query($myQuery);

            }else{
                $myQuery = "INSERT INTO app_ocompra (id_proveedor,id_usrcompra,observaciones,fecha,fecha_entrega,activo,id_requisicion,subtotal,total,id_almacen,id_usuario,fecha_creacion) VALUES ('$proveedor',1,'$obs','$fechahoy','$fechaentrega','$idactivo','$last_id','$ist','$it','$almacen','$idusr','$creacion');";
                $last_id2 = $this->insert_id($myQuery);

            }

            $myQuery = "DELETE FROM app_ocompra_datos WHERE id_ocompra='$last_id2';";
            $this->query($myQuery);

            if($last_id2>0){
                $expcadimps = explode('|', $cadimps);
                $exorig=array();
                foreach ($expcadimps as $sk => $vk) {
                    $carcadimpsexp=explode(',', $vk);
                    foreach ($carcadimpsexp as $sk2 => $vk2) {
                        array_push($exorig,$vk2);
                    }
                }
                $cad='';
                $productos = explode(',#', $idsProductos);
                $t=0;
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=trim($exp[0]);
                    $cant=trim($exp[1]);
                    $costo=trim($exp[2]);
                    $caracteristica=trim($exp[3]);
                    $cad.="('".$last_id2."','".$idprod."','sestmp','1','1','1','".$cant."','".$costo."','".$exorig[$idprod.'-'.$caracteristica]."','".$caracteristica."'),";
                    $t++;
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_ocompra_datos (id_ocompra,id_producto,ses_tmp,estatus,activo,almacen,cantidad,costo,impuestos,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }

            return $last_id2;

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

                   // if(array_key_exists($prod[0], $sinimps)){
                       // $impuestos[$prod[0]]=$sinimps[$prod[0]];
                   // }
                        

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

                    if($car!='0'){
                        $resultCaras = $this->caracteristicaReq($car);
                    }else{
                        $resultCaras = '';
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
                            $impuestos[$idProducto.'-'.$car]['nombre']=$resSI['rows'][0]['nameprod'].' '.$resultCaras;
                            $impuestos[$idProducto.'-'.$car]['descripcion']=$resSI['rows'][0]['larga'];
                            $impuestos[$idProducto.'-'.$car]['medida']=$resSI['rows'][0]['medida'];
                            $impuestos[$idProducto.'-'.$car]['cantidad']=$cantidad;
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
                                $impuestos[$idProducto.'-'.$car]['nombre']=$valueImpuestos["nameprod"].' '.$resultCaras;
                                $impuestos[$idProducto.'-'.$car]['descripcion']=$valueImpuestos["descprod"].' '.$resultCaras;
                                $impuestos[$idProducto.'-'.$car]['unidad']=$valueImpuestos["id_unidad_venta"];
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

        function editarRequisicionEnvio($idOc,$pr)
        {
            if($pr=='req'){
                $add='';
            }
            $myQuery = "SELECT a.*, a.id as idreq, d.razon_social as nombre, c.no_factura, c.fecha_factura, c.imp_factura, d.domicilio as direccion, d.email, e.nombreorganizacion, e.domicilio, e.logoempresa, b.subtotal as st, b.total as tt, a.subtotal as rst, a.total as rtt, m.codigo as moneda, concat(n.nombre,' ',n.apellido1) as username1, concat(nn.nombre,' ',nn.apellido1) as username2, if(a.fecha_creacion is null,'',a.fecha_creacion) as fecha_creacion1, if(b.fecha_creacion is null,'',b.fecha_creacion) as fecha_creacion2 FROM app_requisiciones a 
left join app_ocompra b on b.id_requisicion = a.id
left join app_recepcion c on c.id_oc = b.id
left join mrp_proveedor d on d.idPrv = a.id_proveedor
left join organizaciones e on e.idorganizacion=1
left join cont_coin m on m.coin_id=a.id_moneda
left join empleados n on n.idempleado=a.id_usuario
left join empleados nn on nn.idempleado=b.id_usuario
WHERE a.id='$idOc';";


   
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function saveOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo,$obs,$ist,$it,$cadimps,$iduserlog, $num_fact, $tipo_compra)
        {

            date_default_timezone_set("Mexico/General");
            $creacion=date('Y-m-d H:i:s'); 

            session_start();
            $idusr = $_SESSION['accelog_idempleado'];

            $myQuery = "INSERT INTO app_requisiciones (id_solicito,id_tipogasto,id_almacen,id_moneda,id_proveedor,urgente,inventariable,observaciones,fecha,fecha_entrega,activo,tipo_cambio,subtotal,total,id_usuario,fecha_creacion) VALUES ('$solicitante','$tipogasto','$almacen','$moneda','$proveedor','$urgente','$inventariable','".$this->nl2brCH($obs)."','$fechahoy','$fechaentrega','$idactivo','$moneda_tc','$ist','$it','$idusr','$creacion');";
            $last_id = $this->insert_id($myQuery);
      
            if($last_id>0){
                $cad='';
                $productos = explode(',#', $idsProductos);
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=trim($exp[0]);
                    $cant=$exp[1];
                    $caracteristica=$exp[3];
                    $cad.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."','".$caracteristica."'),";
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_requisiciones_datos (id_requisicion,id_producto,ses_tmp,estatus,activo,cantidad,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            
            $myQuery = "INSERT INTO app_ocompra (id_proveedor,id_usrcompra,observaciones,fecha,fecha_entrega,activo,id_requisicion,subtotal,total,id_almacen,id_usuario,fecha_creacion, tipo, num_factura) VALUES ('$proveedor',1,'$obs','$fechahoy','$fechaentrega','$idactivo','$last_id','$ist','$it','$almacen','$idusr','$creacion','$tipo_compra', '$num_fact');";
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
                    $caracteristica=trim($exp[3]);
                    $cad.="('".$last_id2."','".$idprod."','sestmp','1','1','1','".$cant."','".$costo."','".$exorig[$idprod.'-'.$caracteristica]."','".$caracteristica."'),";
                    $t++;
                }
                $cadtrim = trim($cad, ',');
                $myQuery = "INSERT INTO app_ocompra_datos (id_ocompra,id_producto,ses_tmp,estatus,activo,almacen,cantidad,costo,impuestos,caracteristica) VALUES ".$cadtrim.";";
                $query = $this->query($myQuery);
            }
            return $last_id2;

        }

        function saveDevolucion2($sessprods,$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$id_rec)
        {

            //var_dump($sessprods);
            date_default_timezone_set("Mexico/General");
            $date_venta=date('Y-m-d H:i:s'); 
            $hoy=date('Y-m-d H:i:s'); 
            
            /*
            $myQuery = "INSERT INTO app_envios (id_oventa,id_encargado,observaciones,estatus,fecha_envio,activo,no_factura,fecha_factura,imp_factura,id_factura,xmlfile,subtotal,total,facturo,forma_pago, desc_concepto) VALUES ('$idOC','$solicitante','Observaciones',1,'$date_venta','$activo','$nofactrec','$date_recepcion','$impfactrec','6969','$xmlfile','$ist','$it','$facturo','$fp','$concept');";
            $last_id = $this->insert_id($myQuery);
            */

            $myQuery = "INSERT INTO app_devolucionpro (id_oc,id_rec,id_encargado,observaciones,estatus,fecha_devolucion,activo,no_factura,fecha_factura,imp_factura,id_factura,xmlfile,desc_concepto,subtotal,total,id_consignacion) VALUES ('$idOC','$id_rec',1,'Observaciones',1,'$date_recep','$activo','$nofactrec','$date_venta','$impfactrec','666','$xmlfile','$desc_concepto','$ist','$it',$esconsig);";

            $last_id = $this->insert_id($myQuery);

            if($last_id>0){

                $myQuery = "INSERT INTO app_pagos (cobrar_pagar,id_prov_cli,cargo,abono,fecha_pago,concepto,id_forma_pago,id_moneda,tipo_cambio,origen) VALUES ('1','$proveedor','0','$impfactrec','$hoy','Devolucion a cliente-".$last_id."','99','1','1',1);";
                $query = $this->query($myQuery);


                $cadlotes='';
                $cadmodo='';
                $productos = explode(',#', $idsProductos);
                $cardexmulti='';
                $cadrd='';
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
                    $idprod=trim($exp[0]);
                    $cant=trim($exp[1]);
                    $idalmacen=trim($exp[2]);
                    $especial=trim($exp[3]);
                    $caracteristica=trim($exp[5]);
                    $last1=0;
                    $last2=0;
                    $upseries=array();
                    $seriereemp=9990000;
                    

                    $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                    $caracteristicareplace=addslashes($caracteristicareplace);

                    if($cant<=0){
                        continue;
                    }
                    if($especial==0){
                        $myQuerycost = "SELECT costo from app_ocompra_datos where id_ocompra='$idOC' and id_producto='$idprod' and caracteristica='$caracteristica';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);
         
                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantsexistencias']);
                        foreach ($ciclo as $kk => $vv) {
   
                            $desgl_ex=explode('-', $vv);
                            if($desgl_ex[2]!=0){

                                /*$esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_ex[0]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                    $addcons='';
                                }*/

                                $elcost = ($elunit*1)*($desgl_ex[2]*1);
                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','0','0','".$desgl_ex[2]."','".$elcost."','".$desgl_ex[0]."','0','".$hoy."','3','0','".$elunit."','Devolucion de compra / devolucion -".$last_id."','1'),";

                                //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_ex[2]."','0','0',1,'".$desgl_ex[0]."','".$caracteristica."'),";
                                $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$desgl_ex[2]."','0','0',1,'".$desgl_ex[0]."','".$caracteristica."'),";
                            }
                        }

        

                        


                    }elseif($especial==1){


                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod' and caracteristica='$caracteristica';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);



                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantslotes']);

                        foreach ($ciclo as $kk => $vv) {
                            $desgl_cl=explode('-', $vv);
                            if($desgl_cl[2]!=0){

                                /*
                                $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_cl[1]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }
                                */

                                $elcost = ($elunit*1)*($desgl_cl[2]*1);
                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','0','".$desgl_cl[0]."','".$desgl_cl[2]."','".$elcost."','".$desgl_cl[1]."','0','".$hoy."','3','0','".$elunit."','Devolucion de compra / devolucion -".$last_id."','1'),";

                                $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$desgl_cl[2]."','".$desgl_cl[0]."','0',1,'".$desgl_cl[1]."','".$caracteristica."'),";

                                //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_cl[2]."','".$desgl_cl[0]."','0',1,'".$desgl_cl[1]."','".$caracteristica."'),";
                            }
                        }

                        


                    }elseif($especial==4){
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod' and caracteristica='$caracteristica';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);



                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantspedimentos']);

                        foreach ($ciclo as $kk => $vv) {
                            $desgl_cl=explode('-', $vv);
                            if($desgl_cl[2]!=0){
                                $elcost = ($elunit*1)*($desgl_cl[2]*1);
                                /*
                                $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_cl[1]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }
                                */

                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','".$desgl_cl[0]."','0','".$desgl_cl[2]."','".$elcost."','".$desgl_cl[1]."','0','".$hoy."','3','0','".$elunit."','Devolucion de compra / devolucion -".$last_id."','1'),";

                                //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_cl[2]."','0','".$desgl_cl[0]."',1,'".$desgl_cl[1]."','".$caracteristica."'),";

                                $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$desgl_cl[2]."','0','".$desgl_cl[0]."',1,'".$desgl_cl[1]."','".$caracteristica."'),";

                            }
                        }

                        

                    }elseif($especial==5){
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod' and caracteristica='$caracteristica';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);



                        $ciclo=explode(',', $sessprods[$especial][$idprod][$caracteristica]['cantspedimentos']);
                        foreach ($ciclo as $kk => $vv) {

                            $desgl_cl=explode('-', $vv);
                            $pedlote=explode('#', $desgl_cl[0]);
                            if($desgl_cl[2]!=0){
                                $elcost = ($elunit*1)*($desgl_cl[2]*1);
                                /*
                                $esconsig = "SELECT count(*) as es from app_almacenes where id='".$desgl_cl[1]."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }
                                */
                                $cardexmulti.="('".$idprod."','".$caracteristicareplace."','".$pedlote[0]."','".$pedlote[1]."','".$desgl_cl[2]."','".$elcost."','".$desgl_cl[1]."','0','".$hoy."','3','0','".$elunit."','Orden de venta / envio -".$last_id." ".$addcons."','1'),";

                                $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$desgl_cl[2]."','".$pedlote[1]."','".$pedlote[0]."',1,'".$desgl_cl[1]."','".$caracteristica."'),";

                                //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$desgl_cl[2]."','".$pedlote[1]."','".$pedlote[0]."',1,'".$desgl_cl[1]."','".$caracteristica."'),";
                            }
                        }

                       

                    }elseif($especial==2){
                        
                        
                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod' and caracteristica='$caracteristica';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
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

                            $upseries[]=array('idprod'=>$idprod ,
                                'idalmacen'=>$desgl_ser[1],
                                'idserie'=>$desgl_ser[0],
                                'pedsss'=>0,
                                'id'=>$last_prodserie);
                        }

                        $setrim = trim($seriessolas, ',');
                        $myQuery = "UPDATE app_producto_serie SET id_venta='$last_id', estatus=1 WHERE id_producto='$idprod' AND id in (".$setrim."); ";
                        $query = $this->query($myQuery);

                        foreach ($sarray as $kk => $vv) {
                            $elcost = ($elunit*1)*($vv*1);
                            /*
                            $esconsig = "SELECT count(*) as es from app_almacenes where id='".$kk."' AND es_consignacion='1';";
                                $esconresult = $this->queryArray($esconsig);
                                $es = $esconresult['rows'][0]['es'];
                                if($es>0){
                                    $addcons='rcon-'.$last_id;
                                }else{
                                   $addcons='';
                                }
                                */
                            //$desgl_ped=explode('-', $vv);
                            $cardexmulti.="('".$idprod."','".$caracteristicareplace."','0','0','".$vv."','".$elcost."','".$kk."','0','".$hoy."','3','0','".$elunit."','Devolucion de compra / devolucion -".$last_id."','1'),";

                            $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$vv."','0','0',1,'".$kk."','".$caracteristica."'),";

                            
                        
                            //$cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$vv."','0','0',1,'".$kk."','".$caracteristica."'),";
                        }

                        
                        

                    }elseif($especial==3){


                        $myQuerycost = "SELECT costo from app_oventa_datos where id_oventa='$idOC' and id_producto='$idprod';";
                        $costprods = $this->queryArray($myQuerycost);
                        $elunit = $costprods['rows'][0]['costo'];
                        $elcost = ($elunit*1)*($cant*1);
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

                                    $upseries[]= array('idprod'=>$idprod,
                                        'idalmacen'=>$alma,
                                        'idserie'=>$desgl_ser[0],
                                        'pedsss'=>$elpedi2,
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


                            $cardexmulti.="('".$idprod."','".$caracteristicareplace."','".$elpedi1."','0','".$cantpedseri."','".$elcost."','".$desgl_ped[1]."','0','".$hoy."','3','0','".$elunit."','Devolucion de compra / devolucion -".$last_id."','1'),";


                            $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$cantpedseri."','0','".$elpedi1."',1,'".$desgl_ped[1]."','".$caracteristica."'),";

        
                        }

                        $setrim = trim($sers, ',');
                        $myQuery = "UPDATE app_producto_serie SET id_venta='$last_id', estatus=1 WHERE id_producto='$idprod' AND id in (".$setrim."); ";
                        $query = $this->query($myQuery);

                        


                    }

                    


                }

                $cadrdtrim = trim($cadrd, ',');
                $myQuery = "INSERT INTO app_devolucionpro_datos (id_oc,id_rec,id_producto,id_devolucion,cantidad,id_lote,id_pedimento,estatus,id_almacen,caracteristica) VALUES ".$cadrdtrim.";";
                $query = $this->query($myQuery);

                $cadrdcardextrim = trim($cardexmulti, ',');
                $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) VALUES ".$cadrdcardextrim.";";

                $query = $this->query($myQuery);

                foreach ($upseries as $ks => $vs) {
                    $myQuery = "UPDATE app_producto_serie_rastro SET id_mov =(SELECT id FROM app_inventario_movimientos WHERE referencia='Devolucion de compra / devolucion -".$last_id."' AND id_producto='".$vs['idprod']."' AND id_almacen_origen='".$vs['idalmacen']."' AND id_pedimento='".$vs['pedsss']."' ) WHERE id='".$vs['id']."';";
                    $query = $this->query($myQuery);
                }

              
                
                
            }

            $myQuery = "UPDATE app_requisiciones_venta SET activo='$activo' WHERE id in (SELECT id_requisicion FROM app_oventa WHERE id='$idOC');";
            $this->query($myQuery);


            $myQuery = "UPDATE app_oventa SET activo='$activo' WHERE id='$idOC';";
            $this->query($myQuery);

            return $last_id;
            
        
        }

        function saveDevolucion($sessprods,$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$id_rec)
        {
            
            //echo $idOC;
            date_default_timezone_set("Mexico/General");
            $date_recep=$date_recep.' '.date('H:i:s');
            $hoy=date('Y-m-d H:i:s'); 
            
            $myQuery = "INSERT INTO app_devolucionpro (id_oc,id_rec,id_encargado,observaciones,estatus,fecha_devolucion,activo,no_factura,fecha_factura,imp_factura,id_factura,xmlfile,desc_concepto,subtotal,total,id_consignacion) VALUES ('$idOC','$id_rec',1,'Observaciones',1,'$date_recep','$activo','$nofactrec','$date_recepcion','$impfactrec','666','$xmlfile','$desc_concepto','$ist','$it',$esconsig);";

            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                $cadlotes='';
                $cadmodo='';
                $productos = explode(',#', $idsProductos);
                $cadrdcardex='';
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
                    $upseries=array();
                    $seriereemp=9990000;
                    //$cadseriesrastro='';

                    if($especial==0){
                        //$cadrd.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."'),";
                    }elseif($especial==1){

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];


                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==4){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                    }elseif($especial==5){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==2){
                        
                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."'),";
                            $cadseriesrastro.="('".$seriereemp."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$t,'seriereemp'=>$seriereemp);
                            $seriereemp++;
                        }
                        $cadseriestrim = trim($cadseries, ',');
                        $myQuery = "INSERT INTO app_producto_serie (id_producto,id_ocompra,id_recepcion,id_venta,estatus,serie,id_almacen) VALUES ".$cadseriestrim."; ";
                        $query = $this->query($myQuery);

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }elseif($especial==3){

                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."','".$last2."'),";
                            $cadseriesrastro.="('".$seriereemp."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$t,'seriereemp'=>$seriereemp);
                            $seriereemp++;
                        }
                        $cadseriestrim = trim($cadseries, ',');
                        $myQuery = "INSERT INTO app_producto_serie (id_producto,id_ocompra,id_recepcion,id_venta,estatus,serie,id_almacen,id_pedimento) VALUES ".$cadseriestrim."; ";
                        $query = $this->query($myQuery);

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }

                    $cadrd.="('".$idOC."','".$id_rec."','".$idprod."','".$last_id."','".$cant."','".$last1."','".$last2."',1,'".$idalmacen."','".$caracteristica."'),";

                    $myQuerycost = "SELECT costo from app_ocompra_datos where id_ocompra='$idOC' and id_producto='$idprod';";
                    $costprods = $this->queryArray($myQuerycost);
                    $elunit = $costprods['rows'][0]['costo'];
                    $elcost = ($elunit*1)*($cant*1);

                    if($inventariable==1){
                        if($tipp==1){
                        $myQueryUnid = "SELECT b.id, b.clave, b.factor as fo, c.clave, c.factor as fd from app_productos a 
                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
                            where a.id='$idprod';";
                        $costunids = $this->queryArray($myQueryUnid);
                        $cantidadreal=(($cant*1)*$costunids['rows'][0]['fo'])/$costunids['rows'][0]['fd'];

                        $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                        $caracteristicareplace=addslashes($caracteristicareplace);
                        $cadrdcardex.="('".$idprod."','".$caracteristicareplace."','".$last2."','".$last1."','".$cantidadreal."','".$elcost."','".$idalmacen."',0,'".$date_recep."','3','0','".$elunit."','Devolucion de compra / devolucion -".$last_id."','1'),";
                        }
                    }
                    date_default_timezone_set("Mexico/General");
                    $ffecha=date('Y-m-d H:i:s');
                    $upd= "UPDATE app_costos_proveedor set costo='$elunit', fecha='$ffecha' WHERE id_proveedor='$proveedor' AND id_producto='$idprod';";
                    $query = $this->query($upd);

                }

                $cadrdtrim = trim($cadrd, ',');
                $myQuery = "INSERT INTO app_devolucionpro_datos (id_oc,id_rec,id_producto,id_devolucion,cantidad,id_lote,id_pedimento,estatus,id_almacen,caracteristica) VALUES ".$cadrdtrim.";";
                $query = $this->query($myQuery);

                if($inventariable==1){
                    $cadrdcardextrim = trim($cadrdcardex, ',');
                    $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) VALUES ".$cadrdcardextrim.";";
                    $query = $this->query($myQuery);
                    //$lastmov = $this->insert_id($myQuery);
                }
                foreach ($upseries as $ks => $vs) {
                    $myQuery = "UPDATE app_producto_serie_rastro SET id_mov =(SELECT id FROM app_inventario_movimientos WHERE referencia='Orden de compra / recepcion -".$last_id."' AND id_producto='".$vs['idprod']."' ), id_serie=(SELECT id FROM app_producto_serie WHERE id_producto='".$vs['idprod']."' AND id_ocompra='".$idOC."' AND id_recepcion='".$last_id."' AND serie='".$vs['serie']."') WHERE id_serie='".$vs['seriereemp']."' and id_mov=0 and id_almacen='".$vs['idalmacen']."' and fecha_reg='".$hoy."' ;";
                    $query = $this->query($myQuery);
                }



            }

            $myQuery = "UPDATE app_requisiciones SET activo='$activo' WHERE id in (SELECT id_requisicion FROM app_ocompra WHERE id='$idOC');";
            $this->query($myQuery);


            $myQuery = "UPDATE app_ocompra SET activo='$activo' WHERE id='$idOC';";
            $this->query($myQuery);

            return $last_id;
            
        
        }

        function verificarPagos($idoc){
            $myQuery = "SELECT a.id_recepcion from app_recepcion_datos a
                        inner join app_recepcion b on b.id=a.id_recepcion
                        where b.id_oc=".$idoc.";";
            $resultque = $this->queryArray($myQuery);

            if($resultque['total']>0){
                $tpagos=0;
                foreach ($resultque['rows'] as $k => $v) {
                    $myQuery2 = "SELECT count(*) as pagos from app_pagos a
                    inner join app_pagos_relacion b on b.id_documento=a.id
                    where concepto='Recepcion-".$v['id_recepcion']."';";
                    $r2 = $this->queryArray($myQuery2);
                    $tp =  $r2['rows'][0]['pagos']*1;
                    $tpagos+=$tp;
                }
            }

            if($tpagos>0){
                echo 1;
            }else{
                echo 0;
            }
        }

        function saveRecepcion($sessprods,$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$moneda,$moneda_tc,$cxp)
        {
            
            //echo $idOC;
            date_default_timezone_set("Mexico/General");
            $date_recep=$date_recep.' '.date('H:i:s');
            $hoy=date('Y-m-d H:i:s'); 

            session_start();
            $idusr = $_SESSION['accelog_idempleado'];
            
            $myQuery = "INSERT INTO app_recepcion (id_oc,id_encargado,observaciones,estatus,fecha_recepcion,activo,no_factura,fecha_factura,imp_factura,id_factura,xmlfile,desc_concepto,subtotal,total,id_consignacion,id_usuario) VALUES ('$idOC',1,'Observaciones',1,'$date_recep','$activo','$nofactrec','$date_recepcion','$impfactrec','666','$xmlfile','$desc_concepto','$ist','$it',$esconsig,'$idusr');";
            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                if ($cxp == 1) {
                    $myQuery2 = "INSERT INTO app_pagos (cobrar_pagar,id_prov_cli,cargo,abono,fecha_pago,concepto,id_forma_pago,id_moneda,tipo_cambio,origen) VALUES (1,'$proveedor','$it',0,'$date_recep','Recepcion-".$last_id."',1,'$moneda','$moneda_tc',1);";
                    $query = $this->query($myQuery2);
                }
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

                    if($especial==0){
                        //$cadrd.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."'),";
                    }elseif($especial==1){

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];


                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==4){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                    }elseif($especial==5){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==2){
                        
                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."'),";
                            $cadseriesrastro.="('".$seriereemp."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$t,'seriereemp'=>$seriereemp);
                            $seriereemp++;
                        }
                        $cadseriestrim = trim($cadseries, ',');
                        $myQuery = "INSERT INTO app_producto_serie (id_producto,id_ocompra,id_recepcion,id_venta,estatus,serie,id_almacen) VALUES ".$cadseriestrim."; ";
                        $query = $this->query($myQuery);

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }elseif($especial==3){

                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."','".$last2."'),";
                            $cadseriesrastro.="('".$seriereemp."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$t,'seriereemp'=>$seriereemp);
                            $seriereemp++;
                        }
                        $cadseriestrim = trim($cadseries, ',');
                        $myQuery = "INSERT INTO app_producto_serie (id_producto,id_ocompra,id_recepcion,id_venta,estatus,serie,id_almacen,id_pedimento) VALUES ".$cadseriestrim."; ";
                        $query = $this->query($myQuery);

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }

                    $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','".$last1."','".$last2."',1,'".$idalmacen."','".$caracteristica."'),";

                    $myQuerycost = "SELECT costo from app_ocompra_datos where id_ocompra='$idOC' and id_producto='$idprod';";
                    $costprods = $this->queryArray($myQuerycost);
                    $elunit = $costprods['rows'][0]['costo'];
                    $elcost = ($elunit*1)*($cant*1);


                    if($inventariable==1){
                        if($tipp!=2){
                        $myQueryUnid = "SELECT b.id, b.clave, b.factor as fo, c.clave, c.factor as fd from app_productos a 
                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
                            where a.id='$idprod';";
                        $costunids = $this->queryArray($myQueryUnid);
                        $cantidadreal=(($cant*1)*$costunids['rows'][0]['fo'])/$costunids['rows'][0]['fd'];

                        $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                        $caracteristicareplace=addslashes($caracteristicareplace);

                            if($cantidadreal!='0'){

                                $elunitreal=($elcost*1)/($cantidadreal*1);
                                
                                $cadrdcardex.="('".$idprod."','".$caracteristicareplace."','".$last2."','".$last1."','".$cantidadreal."','".$elcost."',0,'".$idalmacen."','".$date_recep."','3','1','".$elunitreal."','Orden de compra / recepcion -".$last_id."','1'),";
                            } 
                        }
                    }
                    date_default_timezone_set("Mexico/General");
                    $ffecha=date('Y-m-d H:i:s');
                    $upd= "UPDATE app_costos_proveedor set costo='$elunit', fecha='$ffecha' WHERE id_proveedor='$proveedor' AND id_producto='$idprod';";
                    $query = $this->query($upd);

                }

                $cadrdtrim = trim($cadrd, ',');
                $myQuery = "INSERT INTO app_recepcion_datos (id_oc,id_producto,id_recepcion,cantidad,id_lote,id_pedimento,estatus,id_almacen,caracteristica) VALUES ".$cadrdtrim.";";
                $query = $this->query($myQuery);

                if($inventariable==1 && $cadrdcardex!=''){
                    $cadrdcardextrim = trim($cadrdcardex, ',');
                    $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) VALUES ".$cadrdcardextrim.";";


                    $query = $this->query($myQuery);
                    //$lastmov = $this->insert_id($myQuery);
                }

                foreach ($upseries as $ks => $vs) {
                    $sqlMov = "SELECT id FROM app_inventario_movimientos WHERE referencia='Orden de compra / recepcion -".$last_id."' AND id_producto='".$vs['idprod']."'";

                    $sqlMov = $this->queryArray($sqlMov);

                    foreach ($sqlMov['rows'] as $key => $value) {
                        $myQuery = "UPDATE app_producto_serie_rastro SET id_mov ='". $value['id'] ."', id_serie=(SELECT id FROM app_producto_serie WHERE id_producto='".$vs['idprod']."' AND id_ocompra='".$idOC."' AND id_recepcion='".$last_id."' AND serie='".$vs['serie']."') WHERE id_serie='".$vs['seriereemp']."' and id_mov=0 and id_almacen='".$vs['idalmacen']."' and fecha_reg='".$hoy."' ;";

                        $query = $this->query($myQuery);
                    }
                    
                }



            }

            $myQuery = "UPDATE app_requisiciones SET activo='$activo' WHERE id in (SELECT id_requisicion FROM app_ocompra WHERE id='$idOC');";
            $this->query($myQuery);


            $myQuery = "UPDATE app_ocompra SET activo='$activo' WHERE id='$idOC';";
            $this->query($myQuery);


            $sq = "SELECT idoproduccion,idprereq FROM app_requisiciones WHERE idoproduccion!=0 and id in (SELECT id_requisicion FROM app_ocompra WHERE id='$idOC');";
            $sqr = $this->queryArray($sq);

            if($sqr['total']>0){
                $idoproduccion=$sqr['rows'][0]['idoproduccion'];
                $idprereq=$sqr['rows'][0]['idprereq'];

                $myQuery = "UPDATE prd_prerequisicion SET activo=3 WHERE id='$idprereq';";
                $this->query($myQuery);

                $sq2 = "SELECT id FROM prd_prerequisicion WHERE id_op='$idoproduccion' AND activo=1;";
                $sqr2 = $this->queryArray($sq2);
                if($sqr2['total']==0){
                    $myQuery = "UPDATE prd_orden_produccion SET estatus=3 WHERE id='$idoproduccion';";
                    $this->query($myQuery); 
                }

            }

            return $last_id;
            
        
        }

        function saveRecepcion2($sessprods,$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$moneda,$moneda_tc,$almacen,$cxp)
        {
            //echo '/'.$idsProductos.'/';
            //echo $idOC;
            date_default_timezone_set("Mexico/General");
            $date_recep=$date_recep.' '.date('H:i:s');
            $hoy=date('Y-m-d H:i:s'); 

            session_start();
            $idusr = $_SESSION['accelog_idempleado'];
            
            $myQuery = "INSERT INTO app_recepcion (id_oc,id_encargado,observaciones,estatus,fecha_recepcion,activo,no_factura,fecha_factura,imp_factura,id_factura,xmlfile,desc_concepto,subtotal,total,id_consignacion,id_usuario) VALUES ('$idOC',1,'Observaciones',1,'$hoy','$activo','$nofactrec','$date_recepcion','$impfactrec','666','$xmlfile','$desc_concepto','$ist','$it',$esconsig,'$idusr');";
            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                if($cxp == 1){
                    $myQuery2 = "INSERT INTO app_pagos (cobrar_pagar,id_prov_cli,cargo,abono,fecha_pago,concepto,id_forma_pago,id_moneda,tipo_cambio,origen) VALUES (1,'$proveedor','$it',0,'$hoy','Recepcion-".$last_id."',1,'$moneda','1',1);";
                    $query = $this->query($myQuery2);
                }
                $cadlotes='';
                $cadmodo='';
                $productos = explode(',#', $idsProductos);
                $cadrdcardex='';
                $upseries=array();
                foreach ($productos as $k => $v) {
                    $exp=explode('>#', $v);
//var_dump($exp);
                    $idprod=trim($exp[0]);
                    $cant=$exp[1];
                    $idalmacen=$almacen;
//$especial=$exp[3];
                    $tipp=$exp[4];
                    $caracteristica=$exp[3];
                    $last1=0;
                    $last2=0;
                    //echo 'carac='.$especial.' ';
                    $seriereemp=9990000;
                    //$cadseriesrastro='';

                    if($especial==0){
                        //$cadrd.="('".$last_id."','".$idprod."','sestmp','1','1','".$cant."'),";
                    }elseif($especial==1){

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];


                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==4){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                    }elseif($especial==5){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==2){
                        
                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."'),";
                            $cadseriesrastro.="('".$seriereemp."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$t,'seriereemp'=>$seriereemp);
                            $seriereemp++;
                        }
                        $cadseriestrim = trim($cadseries, ',');
                        $myQuery = "INSERT INTO app_producto_serie (id_producto,id_ocompra,id_recepcion,id_venta,estatus,serie,id_almacen) VALUES ".$cadseriestrim."; ";
                        $query = $this->query($myQuery);

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }elseif($especial==3){

                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."','".$last2."'),";
                            $cadseriesrastro.="('".$seriereemp."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$t,'seriereemp'=>$seriereemp);
                            $seriereemp++;
                        }
                        $cadseriestrim = trim($cadseries, ',');
                        $myQuery = "INSERT INTO app_producto_serie (id_producto,id_ocompra,id_recepcion,id_venta,estatus,serie,id_almacen,id_pedimento) VALUES ".$cadseriestrim."; ";
                        $query = $this->query($myQuery);

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }
                    //echo '$'.$caracteristica.'$';
                    $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','".$last1."','".$last2."',1,'".$almacen."','".$caracteristica."'),";

                    $myQuerycost = "SELECT costo from app_ocompra_datos where id_ocompra='$idOC' and id_producto='$idprod';";
                    $costprods = $this->queryArray($myQuerycost);
                    $elunit = $costprods['rows'][0]['costo'];
                    $elcost = ($elunit*1)*($cant*1);


                    if($inventariable==1){
                        if($tipp!=2){
                        $myQueryUnid = "SELECT b.id, b.clave, b.factor as fo, c.clave, c.factor as fd from app_productos a 
                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
                            where a.id='$idprod';";
                        $costunids = $this->queryArray($myQueryUnid);
                        $cantidadreal=(($cant*1)*$costunids['rows'][0]['fo'])/$costunids['rows'][0]['fd'];

                        $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                        $caracteristicareplace=addslashes($caracteristicareplace);

                            if($cantidadreal!='0'){

                                $elunitreal=($elcost*1)/($cantidadreal*1);
                                
                                $cadrdcardex.="('".$idprod."','".$caracteristicareplace."','".$last2."','".$last1."','".$cantidadreal."','".$elcost."',0,'".$idalmacen."','".$hoy."','3','1','".$elunitreal."','Orden de compra / recepcion -".$last_id."','1'),";
                            } 
                        }
                    }
                    date_default_timezone_set("Mexico/General");
                    $ffecha=date('Y-m-d H:i:s');
                    $upd= "UPDATE app_costos_proveedor set costo='$elunit', fecha='$ffecha' WHERE id_proveedor='$proveedor' AND id_producto='$idprod';";
                    $query = $this->query($upd);

                }

                $cadrdtrim = trim($cadrd, ',');
                $myQuery = "INSERT INTO app_recepcion_datos (id_oc,id_producto,id_recepcion,cantidad,id_lote,id_pedimento,estatus,id_almacen,caracteristica) VALUES ".$cadrdtrim.";";
                $query = $this->query($myQuery);

                if($inventariable==1 && $cadrdcardex!=''){
                    $cadrdcardextrim = trim($cadrdcardex, ',');
                    $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) VALUES ".$cadrdcardextrim.";";

//echo $myQuery;
                    $query = $this->query($myQuery);
                    //$lastmov = $this->insert_id($myQuery);
                }

                foreach ($upseries as $ks => $vs) {
                    $sqlMov = "SELECT id FROM app_inventario_movimientos WHERE referencia='Orden de compra / recepcion -".$last_id."' AND id_producto='".$vs['idprod']."'";

                    $sqlMov = $this->queryArray($sqlMov);

                    foreach ($sqlMov['rows'] as $key => $value) {
                        $myQuery = "UPDATE app_producto_serie_rastro SET id_mov ='". $value['id'] ."', id_serie=(SELECT id FROM app_producto_serie WHERE id_producto='".$vs['idprod']."' AND id_ocompra='".$idOC."' AND id_recepcion='".$last_id."' AND serie='".$vs['serie']."') WHERE id_serie='".$vs['seriereemp']."' and id_mov=0 and id_almacen='".$vs['idalmacen']."' and fecha_reg='".$hoy."' ;";

                        $query = $this->query($myQuery);
                    }
                    
                }



            }

            $myQuery = "UPDATE app_requisiciones SET activo='$activo' WHERE id in (SELECT id_requisicion FROM app_ocompra WHERE id='$idOC');";
            $this->query($myQuery);


            $myQuery = "UPDATE app_ocompra SET activo='$activo' WHERE id='$idOC';";
            $this->query($myQuery);

            return $last_id;
            
        
        }

        function saveConsignacion($sessprods,$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$almacen_origen)
        {
            
            date_default_timezone_set("Mexico/General");
            $date_recep=$date_recep.' '.date('H:i:s');
            $hoy=date('Y-m-d H:i:s'); 
            
            $myQuery = "INSERT INTO app_consignacion (id_recepcion,id_encargado,observaciones,estatus,fecha_compra,activo,no_factura,fecha_factura,imp_factura,xmlfile,desc_concepto) VALUES ('$idOC',1,'',1,'$hoy','$activo','$nofactrec','$date_recepcion','$impfactrec','$xmlfile','$desc_concepto');";
            $last_id = $this->insert_id($myQuery);

            if($last_id>0){
                $cadlotes='';
                $cadmodo='';
                $productos = explode(',#', $idsProductos);
                $cadrdcardex='';
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
                    $upseries=array();
                    $seriereemp=9990000;
                    //$cadseriesrastro='';

                    echo $myQuerycost = "SELECT costo from app_ocompra_datos where id_ocompra in (SELECT id_oc FROM app_recepcion WHERE id='$idOC') and id_producto='$idprod';";
                    $costprods = $this->queryArray($myQuerycost);
                    $elunit = $costprods['rows'][0]['costo'];
                    $elcost = ($elunit*1)*($cant*1);

                    echo $myQueryUnid = "SELECT b.id, b.clave, b.factor as fo, c.clave, c.factor as fd from app_productos a 
                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
                            where a.id='$idprod';";
                        $costunids = $this->queryArray($myQueryUnid);
                        echo 'aaaa'.$cantidadreal=(($cant*1)*$costunids['rows'][0]['fo'])/$costunids['rows'][0]['fd'];

                    if($especial==0){
                        $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                        $caracteristicareplace=addslashes($caracteristicareplace);
                        $cadrdcardex="('".$idprod."','".$caracteristicareplace."','0','0','".$cantidadreal."','".$elcost."','".$almacen_origen."','".$idalmacen."','".$date_recep."','3','2','".$elunit."','Compra de consignacion -".$last_id."')";
                        

                        echo $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia) VALUES ".$cadrdcardex.";";
                        $lastmovi = $this->insert_id($myQuery);

                    }elseif($especial==1){

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];


                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==4){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                    }elseif($especial==5){
                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $nlote= $sessprods[$especial][$idprod][$caracteristica]['nolote'];
                        $flote= $sessprods[$especial][$idprod][$caracteristica]['datelotefab'];
                        $flotec= $sessprods[$especial][$idprod][$caracteristica]['datelotecad'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $myQuery = "INSERT INTO app_producto_lotes (no_lote,fecha_fabricacion,fecha_caducidad) VALUES ('$nlote','$flote','$flotec') ";
                        $last1 = $this->insert_id($myQuery);

                    }elseif($especial==2){

                        $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                        $caracteristicareplace=addslashes($caracteristicareplace);
                        $cadrdcardex="('".$idprod."','".$caracteristicareplace."','0','0','".$cantidadreal."','".$elcost."','".$almacen_origen."','".$idalmacen."','".$date_recep."','3','2','".$elunit."','Compra de consignacion -".$last_id."')";
                        

                        echo $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia) VALUES ".$cadrdcardex.";";
                        $lastmovi = $this->insert_id($myQuery);

                        echo '3423432';

                        echo $myQuery = "SELECT a.*, b.nombre, b.id as ida from app_producto_serie a 
            inner join app_almacenes b on b.id= a.id_almacen
            where a.id_producto='$idprod' AND a.estatus=0 and id_almacen='$almacen_origen';";
                        $resultque = $this->queryArray($myQuery);

                        foreach ($resultque['rows'] as $key => $rserie) {
                            echo $myQuery = "UPDATE app_producto_serie SET id_almacen='$idalmacen' WHERE id='".$rserie['id']."'; ";
                            $query = $this->query($myQuery);

                            echo $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ('".$rserie['id']."','$idalmacen','$hoy','$lastmovi'); ";
                            $query = $this->query($myQuery);
                        }
                        

                    }elseif($especial==3){

                        $npedi= $sessprods[$especial][$idprod][$caracteristica]['nopedimento'];
                        $aduana= $sessprods[$especial][$idprod][$caracteristica]['aduanatext'];
                        $naduana= $sessprods[$especial][$idprod][$caracteristica]['noaduana'];
                        $tcambio= $sessprods[$especial][$idprod][$caracteristica]['tipcambio'];
                        $fpedi= $sessprods[$especial][$idprod][$caracteristica]['datepedimento'];

                        $myQuery = "INSERT INTO app_producto_pedimentos (no_pedimento,aduana,no_aduana,tipo_cambio,fecha_pedimento) VALUES ('$npedi','$aduana','$naduana','$tcambio','$fpedi') ";
                        $last2 = $this->insert_id($myQuery);

                        $nseries= $sessprods[$especial][$idprod][$caracteristica]['nseries'];
                        $nseriesp= $sessprods[$especial][$idprod][$caracteristica]['seriesprods'];

                        $expnsp=explode(',', $nseriesp);
                        $cadseries='';
                        $cadseriesrastro='';
                        foreach ($expnsp as $r => $t) {
                            $cadseries.="('".$idprod."','".$idOC."','".$last_id."','0','0','".$t."','".$idalmacen."','".$last2."'),";
                            $cadseriesrastro.="('".$seriereemp."','".$idalmacen."','".$hoy."','0'),";
                            $upseries[]= array('idprod'=>$idprod ,'idalmacen'=>$idalmacen,'serie'=>$t,'seriereemp'=>$seriereemp);
                            $seriereemp++;
                        }
                        $cadseriestrim = trim($cadseries, ',');
                        $myQuery = "INSERT INTO app_producto_serie (id_producto,id_ocompra,id_recepcion,id_venta,estatus,serie,id_almacen,id_pedimento) VALUES ".$cadseriestrim."; ";
                        $query = $this->query($myQuery);

                        $cadseriestrimr = trim($cadseriesrastro, ',');
                        $myQuery = "INSERT INTO app_producto_serie_rastro (id_serie,id_almacen,fecha_reg,id_mov) VALUES ".$cadseriestrimr."; ";
                        $query = $this->query($myQuery);

                    }
/*
                    $cadrd.="('".$idOC."','".$idprod."','".$last_id."','".$cant."','".$last1."','".$last2."',1,'".$idalmacen."','".$caracteristica."'),";

                    $myQuerycost = "SELECT costo from app_ocompra_datos where id_ocompra='$idOC' and id_producto='$idprod';";
                    $costprods = $this->queryArray($myQuerycost);
                    $elunit = $costprods['rows'][0]['costo'];
                    $elcost = ($elunit*1)*($cant*1);

                    if($inventariable==1){
                        if($tipp==1){
                        $myQueryUnid = "SELECT b.id, b.clave, b.factor as fo, c.clave, c.factor as fd from app_productos a 
                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
                            where a.id='$idprod';";
                        $costunids = $this->queryArray($myQueryUnid);
                        $cantidadreal=(($cant*1)*$costunids['rows'][0]['fo'])/$costunids['rows'][0]['fd'];

                        $caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
                        $caracteristicareplace=addslashes($caracteristicareplace);
                        $cadrdcardex.="('".$idprod."','".$caracteristicareplace."','".$last2."','".$last1."','".$cantidadreal."','".$elcost."',0,'".$idalmacen."','".$date_recep."','3','1','".$elunit."','Orden de compra / recepcion -".$last_id."'),";
                        }
                    }
                    date_default_timezone_set("Mexico/General");
                    $ffecha=date('Y-m-d H:i:s');
                    $upd= "UPDATE app_costos_proveedor set costo='$elunit', fecha='$ffecha' WHERE id_proveedor='$proveedor' AND id_producto='$idprod';";
                    $query = $this->query($upd);

                    */

                }

/*
                $cadrdtrim = trim($cadrd, ',');
                $myQuery = "INSERT INTO app_recepcion_datos (id_oc,id_producto,id_recepcion,cantidad,id_lote,id_pedimento,estatus,id_almacen,caracteristica) VALUES ".$cadrdtrim.";";
                $query = $this->query($myQuery);

                if($inventariable==1){
                    $cadrdcardextrim = trim($cadrdcardex, ',');
                    $myQuery = "INSERT INTO app_inventario_movimientos (id_producto,id_producto_caracteristica,id_pedimento,id_lote,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia) VALUES ".$cadrdcardextrim.";";
                    $query = $this->query($myQuery);
                    //$lastmov = $this->insert_id($myQuery);
                }
                foreach ($upseries as $ks => $vs) {
                    $myQuery = "UPDATE app_producto_serie_rastro SET id_mov =(SELECT id FROM app_inventario_movimientos WHERE referencia='Orden de compra / recepcion -".$last_id."' AND id_producto='".$vs['idprod']."' ), id_serie=(SELECT id FROM app_producto_serie WHERE id_producto='".$vs['idprod']."' AND id_ocompra='".$idOC."' AND id_recepcion='".$last_id."' AND serie='".$vs['serie']."') WHERE id_serie='".$vs['seriereemp']."' and id_mov=0 and id_almacen='".$vs['idalmacen']."' and fecha_reg='".$hoy."' ;";
                    $query = $this->query($myQuery);
                }

*/

            }

/*
            $myQuery = "UPDATE app_requisiciones SET activo='$activo' WHERE id in (SELECT id_requisicion FROM app_ocompra WHERE id='$idOC');";
            $this->query($myQuery);


            $myQuery = "UPDATE app_ocompra SET activo='$activo' WHERE id='$idOC';";
            $this->query($myQuery);

            return $last_id;
            */
        
        }

        function listaRequisiciones()
        {
            $myQuery = "SELECT a.id, SUBSTRING(a.fecha,1,10), pr.razon_social, b.nombreEmpleado as nombre, a.total, a.urgente, a.activo
            FROM app_requisiciones a
            INNER JOIN nomi_empleados b on b.idEmpleado=a.id_solicito
            left join mrp_proveedor pr on pr.idPrv=a.id_proveedor
            LEFT JOIN app_area_empleado c on c.id=b.id_area_empleado
            left JOIN (SELECT b2.costo, a2.cantidad, b2.id_producto, a2.id as fff, a2.id_requisicion, b2.id_proveedor
                       FROM app_requisiciones_datos a2
                       inner JOIN app_costos_proveedor b2 on b2.id_producto=a2.id_producto) as s2 on s2.id_requisicion=a.id and s2.id_proveedor=a.id_proveedor
            WHERE a.pr=1
            GROUP BY a.id
            ORDER BY a.id desc;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function listaOrdenesCompra()
        {
            $myQuery = "SELECT b.id, SUBSTRING(a.fecha,1,10), bb.nombreEmpleado as nombre, cc.nombre as nomarea,'Egreso', SUM(s2.cantidad*s2.costo) as importe, a.urgente, a.activo, a.id as idreq from app_ocompra b
inner join app_requisiciones a on a.id=b.id_requisicion
INNER JOIN nomi_empleados bb on bb.idEmpleado=a.id_solicito
LEFT JOIN app_area_empleado cc on cc.id=bb.id_area_empleado

left JOIN (SELECT b2.costo, a2.cantidad, b2.id_producto, a2.id as fff, a2.id_requisicion, b2.id_proveedor
                       FROM app_requisiciones_datos a2
                       inner JOIN app_costos_proveedor b2 on b2.id_producto=a2.id_producto) as s2 on s2.id_requisicion=a.id and s2.id_proveedor=a.id_proveedor
             WHERE  (a.activo=1 OR a.activo=4 OR a.activo=5 OR a.activo=6)
             GROUP BY a.id
            ORDER BY a.id desc;";
/*

<th>No. OC.</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Solicitante</th>
                        <th>Fecha entrega</th>
                        <th>Almacen</th>
                        <th>Total</th>
                        <th>Prioridad</th>
                        <th>Estatus</th>
                        <th class="no-sort" style="text-align: center;">Acciones</th>*/

            $myQuery = "SELECT d.idoc, SUBSTRING(a.fecha,1,10), pr.razon_social, b.nombreEmpleado as nombre, SUBSTRING(a.fecha_entrega,1,10) as fechaf, alm.nombre as almacen, if(d.total is null,TRUNCATE(a.total,2), TRUNCATE(d.total,2) ) as importe, a.urgente, a.activo, a.id as idreq
            FROM app_requisiciones a
            INNER JOIN nomi_empleados b on b.idEmpleado=a.id_solicito
            left join mrp_proveedor pr on pr.idPrv=a.id_proveedor
            left join app_almacenes alm on alm.id=a.id_almacen
            LEFT JOIN app_area_empleado c on c.id=b.id_area_empleado
            left JOIN (SELECT b2.costo, a2.cantidad, b2.id_producto, a2.id as fff, a2.id_requisicion
, b2.id_proveedor
                       FROM app_requisiciones_datos a2
                       inner JOIN app_costos_proveedor b2 on b2.id_producto=a2.id_producto) as s2 on
 s2.id_requisicion=a.id and s2.id_proveedor=a.id_proveedor
            LEFT join (Select r.total, r.id_requisicion, r.id as idoc from app_ocompra r) d on d.id_requisicion=a.id
            where (a.activo!=3 AND a.activo!=2) and a.activo!=0 and a.pr!=2
            GROUP BY a.id
            ORDER BY a.id desc;";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function datosImpresion($idProveedor)
        {

            $myQuery = "SELECT a.razon_social as nombre, a.domicilio as direccion, a.email, e.nombreorganizacion, e.domicilio, e.logoempresa  FROM mrp_proveedor a left join organizaciones e on e.idorganizacion=1 where a.idPrv='$idProveedor';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function listaXmlsCompra($idoc){
            $myQuery = "SELECT * from app_recepcion_xml where id_oc='$idoc' order by id;";
            $listaReq = $this->query($myQuery);
            return $listaReq;
        }

        function listaRecepcionesAdju($idoc)
        {
            if($idoc>0){
                $add=' WHERE a.id_oc='.$idoc.' ';
            }else{
                $add='';
            }
            $myQuery = "SELECT b.id, a.id as idr, SUBSTRING(a.fecha_recepcion,1,10) as fechar, a.no_factura, SUBSTRING(a.fecha_factura,1,10) as fechaf, a.imp_factura, a.estatus, a.activo, a.id_consignacion, a.total FROM app_recepcion a 
            inner join app_ocompra b on b.id=a.id_oc 
            ".$add.";";



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
            $myQuery = "SELECT b.id, a.id as idr, SUBSTRING(a.fecha_recepcion,1,10) as fechar,pr.razon_social, concat(n.nombre,' ',n.apellido1) as username, TRUNCATE(a.total,2),  a.estatus, a.activo, a.id_consignacion, if(c.id is null,0,1) as fin_consigna FROM app_recepcion a 
            inner join app_ocompra b on b.id=a.id_oc
            left join mrp_proveedor pr on pr.idPrv=b.id_proveedor
            left join empleados n on n.idempleado=a.id_usuario 
            left join app_consignacion c on c.id_recepcion=a.id
            ".$add.";";



            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function listaRequisicionesCompra()
        {
            $myQuery = "SELECT a.id, SUBSTRING(a.fecha,1,10), pr.razon_social, b.nombreEmpleado as nombre, SUBSTRING(a.fecha_entrega,1,10), alm.nombre as almacen,(CASE d.tipo 
                        WHEN 1 THEN
                            'Normal'
                        ELSE 'Directa' END) as tipo, if(d.total is null,TRUNCATE(a.total,2), TRUNCATE(d.total,2) ) as importe, a.urgente, a.activo, if(d.id is null,1,2) as modi
            FROM app_requisiciones a
            INNER JOIN nomi_empleados b on b.idEmpleado=a.id_solicito
            left join mrp_proveedor pr on pr.idPrv=a.id_proveedor
            left join app_almacenes alm on alm.id=a.id_almacen
            LEFT JOIN app_area_empleado c on c.id=b.id_area_empleado
            left JOIN (SELECT b2.costo, a2.cantidad, b2.id_producto, a2.id as fff, a2.id_requisicion
, b2.id_proveedor
                       FROM app_requisiciones_datos a2
                       inner JOIN app_costos_proveedor b2 on b2.id_producto=a2.id_producto) as s2 on
 s2.id_requisicion=a.id and s2.id_proveedor=a.id_proveedor
            LEFT join (Select r.total, r.id_requisicion, r.tipo, r.id from app_ocompra r) d on d.id_requisicion=a.id
            where a.pr!=2 
            GROUP BY a.id
            ORDER BY a.id desc;";

            $listaReq = $this->query($myQuery);
            return $listaReq;

        }

        function checkNoti()
        {
            $myQuery = "SELECT not_compras FROM app_configuracion order by id asc limit 1;";
            $result = $this->queryArray($myQuery);
            $not = $result["rows"][0]["not_compras"];
            return $not; 
        }

        function a_change_idoc_idreq($idoc)
        {
            $myQuery = "SELECT id_requisicion FROM app_ocompra WHERE id='$idoc';";
            $datosReq = $this->queryArray($myQuery);
            return $datosReq;
        }

        function a_get_idoc_idrec($idrec)
        {
            $myQuery = "SELECT id_oc FROM app_recepcion WHERE id='$idrec';";
            $datosReq = $this->queryArray($myQuery);
            return $datosReq;
        }

        function get_emisor()
        {
            $queryOganizacion="SELECT o.nombreorganizacion,o.RFC,r.descripcion as regimen,o.domicilio,e.estado,m.municipio,o.cp,o.colonia,o.paginaweb,o.logoempresa ";
            $queryOganizacion.=" from organizaciones o, estados e,municipios m, nomi_regimenfiscal r ";
            $queryOganizacion.=" where o.idestado=e.idestado and o.idmunicipio=m.idmunicipio and o.idregfiscal = r.idregfiscal";
           
            $result2 = $this->queryArray($queryOganizacion);
            return $result2['rows'][0];
        }

        function get_proveedor($idProveedor)
        {
            $queryClient="SELECT c.razon_social as nombre,c.domicilio as direccion,c.email,c.cp, e.estado,m.municipio
            ,c.rfc,c.no_ext as num_ext,c.no_int as num_int ";
            $queryClient.=" from mrp_proveedor c, estados e,municipios m ";
            $queryClient.=" where c.idestado=e.idestado and c.idmunicipio=m.idmunicipio and c.idPrv=".$idProveedor;
            $queryClient;
            $result = $this->queryArray($queryClient);
            return $result['rows'][0];
        }

        function get_datos_coti($id_coti)
        {
            $queryClient="select oc.id, oc.id_proveedor, oc.tipo, oc.num_factura, e.nombre from app_ocompra oc
            LEFT join empleados e ON e.idempleado = oc.id_usuario
            where oc.id_requisicion = ".$id_coti;
            $result = $this->queryArray($queryClient);
            return $result['rows'][0];
            
        }

        function editarRequisicion($idReq,$pr)
        {
            if($pr=='req'){
                $add='';
            }
            $myQuery = "SELECT a.*, c.no_factura, c.fecha_factura, b.tipo as tipo_compra, b.num_factura, c.imp_factura, d.es_consignacion, b.id_almacen as almafinal, d.nombre as nomalmacen, concat(n.nombre,' ',n.apellido1) as username, idempleado   FROM app_requisiciones a 
left join app_ocompra b on b.id_requisicion = a.id
left join app_recepcion c on c.id_oc = b.id
left join app_almacenes d on d.id=b.id_almacen
left join empleados n on n.idempleado=a.id_usuario
WHERE a.id='$idReq';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function editarRequisicionRec($idRec)
        {
            $myQuery = "SELECT c.*, a.xmlfile, a.no_factura, a.fecha_factura, a.imp_factura, c.id as idreq, SUBSTRING(a.fecha_recepcion,1,10) as fecha_recepcion, d.es_consignacion, b.id_almacen as almafinal, d.nombre as nomalmacen from app_recepcion a
inner join app_ocompra b on b.id=a.id_oc
inner join app_requisiciones c on c.id=b.id_requisicion
left join app_almacenes d on d.id=b.id_almacen
where a.id='$idRec';";
            $datosReq = $this->query($myQuery);
            return $datosReq;

        }

        function productosRequisicion($idReq,$idProveedor,$m,$mod,$idRec)
        {
            //$m=3;
            $w= ' 1=1 ';
            $lj='';
            $costo=" if(b.costo is null,b.costo,b.costo) as costo ";
            if($m==1){
                $w=' e.activo=3 ';
            }
            if($m==2){
                if($idRec==''){
                    $axand="";
                }else{
                    $axand=" and t.id_recepcion='$idRec' ";
                }
                $w=' (e.activo=1 or e.activo=3 or e.activo=4 or e.activo=5) ';
                $lj="LEFT join (Select d.id_producto, d.costo, e.id as idoc  from app_ocompra_datos d
                               left join app_ocompra e on e.id=d.id_ocompra AND e.id_proveedor='$idProveedor' AND e.id_requisicion='$idReq' WHERE e.id is not null ORDER BY d.id desc) d on d.id_producto=c.id
                    left join app_recepcion r on r.id_oc=d.idoc
                    left join app_recepcion_datos t on t.id_recepcion=r.id and t.id_producto=d.id_producto ".$axand." ";
                $costo=" if(d.costo is null,b.costo,d.costo) as costo, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr, t.id_almacen, if(t.cantidad is null,0,t.cantidad) as recibidorec ";
            }

            if($mod==4){
                
            }
            $myQuery = "SELECT c.id, c.codigo, c.nombre, b.costo, a.cantidad, c.series, c.lotes, c.pedimentos  from app_requisiciones_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = b.id_producto
                    WHERE a.id_requisicion='$idReq';";

            $myQuery = "SELECT c.id, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, if(d.costo is null,b.costo,d.costo) as costo, d.idoc, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr, t.id_almacen from app_requisiciones_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = b.id_producto
                    
                    left join app_recepcion r on r.id_oc=d.idoc
                    left join app_recepcion_datos t on t.id_recepcion=r.id and t.id_producto=d.id_producto
                    WHERE a.id_requisicion='$idReq' group by c.id;";



                  $myQuery = "SELECT cc.clave, c.id, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, c.tipo_producto, ".$costo." , c.nombre as nomprod, a.caracteristica
                        from app_requisiciones_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = b.id_producto
                    INNER join app_unidades_medida cc on cc.id=c.id_unidad_compra
                    ".$lj."
                    WHERE a.id_requisicion='$idReq'  group by a.id;";


            if($m==2){
                if($mod==1){
                    $myQuery = "SELECT cc.clave, c.id, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, c.tipo_producto, b.costo, a.caracteristica
/*
Modificacion que daba error
,  if(a.costo is null,b.costo,a.costo) as costo, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr
, t.id_almacen, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as recibidorec  , c.nombre as nomprod, a.caracteristica


                    from app_ocompra_datos a*/

                    from app_requisiciones_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = a.id_producto
                    INNER join app_unidades_medida cc on cc.id=c.id_unidad_compra
                    WHERE a.id_requisicion='$idReq' group by a.id;";
                    
                    /*
                    Modificacion que daba error
                    left join app_recepcion_datos t on t.id_oc=a.id_ocompra and t.id_producto=a.id_producto
 and t.caracteristica=a.caracteristica
                    WHERE a.id_ocompra in (select id from app_ocompra where id_requisicion='$idReq') group
 by a.id ;";*/
                }else{
                    $myQuery = "SELECT cc.clave, c.id, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, c.tipo_producto

,  if(a.costo is null,b.costo,a.costo) as costo, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr
, t.id_almacen, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as recibidorec  , c.nombre as nomprod, a.caracteristica

                    from app_ocompra_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = a.id_producto
                    INNER join app_unidades_medida cc on cc.id=c.id_unidad_compra
                    left join app_recepcion_datos t on t.id_oc=a.id_ocompra and t.id_producto=a.id_producto
 and t.caracteristica=a.caracteristica
                    WHERE a.id_ocompra in (select id from app_ocompra where id_requisicion='$idReq') group
 by a.id ;";
                }
                
            }

            if($m==3){
                $myQuery = "SELECT cc.clave, c.id, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, c.tipo_producto
,  if(a.costo is null,b.costo,a.costo) as costo, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr
, t.id_almacen, if(t.cantidad is null,0,t.cantidad) as recibidorec  , c.nombre as nomprod, a.caracteristica, if(sum(dp.cantidad) is null,0,sum(dp.cantidad)) as cantdev

                    from app_ocompra_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = a.id_producto
                    INNER join app_unidades_medida cc on cc.id=c.id_unidad_compra
                    left join app_recepcion_datos t on t.id_oc=a.id_ocompra and t.id_producto=a.id_producto and t.caracteristica=a.caracteristica and t.id_recepcion='$idRec'
                    left join app_devolucionpro_datos dp on dp.id_rec='$idRec' and dp.id_producto=c.id and dp.caracteristica=a.caracteristica
                    WHERE a.id_ocompra in (select id from app_ocompra where id_requisicion='$idReq') group by a.id ;";
            }
            if($m==4){
                $myQuery = "SELECT cc.clave, c.id, c.codigo, c.nombre, a.cantidad, c.series, c.lotes, c.pedimentos, c.tipo_producto
,  if(a.costo is null,b.costo,a.costo) as costo, if(sum(t.cantidad) is null,0,sum(t.cantidad)) as cantidadr
, t.id_almacen, if(t.cantidad is null,0,t.cantidad) as recibidorec  , c.nombre as nomprod, a.caracteristica, sum(x.cantidad) as cant_vendida, t.id_recepcion


                    from app_ocompra_datos a
                    INNER JOIN app_costos_proveedor b on b.id_producto=a.id_producto and b.id_proveedor='$idProveedor'
                    INNER JOIN app_productos c on c.id = a.id_producto
                    INNER join app_unidades_medida cc on cc.id=c.id_unidad_compra
                    left join app_recepcion_datos t on t.id_oc=a.id_ocompra and t.id_producto=a.id_producto and t.caracteristica=a.caracteristica and t.id_recepcion='$idRec'
                    left join app_inventario_movimientos x on x.id_producto=c.id and x.id_almacen_origen=t.id_almacen and x.id_almacen_destino=0 and x.tipo_traspaso=0
                    WHERE a.id_ocompra in (select id from app_ocompra where id_requisicion='$idReq') group by a.id ;";
            }
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

        public function calculaImpuestos($stringTaxes){
            //echo $stringTaxes.'Z';
            //unset($_SESSION['prueba']);
            //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';
            $impuestos = array();
            $productos = explode('/', $stringTaxes);

            $ppii=array();

            foreach ($productos as $key => $value) {
                $prod = explode('-', $value);
                if($prod[0]!=''){
                    $idProducto = $prod[0];
                    $precio = $prod[1];
                    $cantidad = $prod[2];
                    $ch = $prod[3];
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
                    $queryImpuestos = "select p.id,p.precio, i.valor, i.clave,pi.formula,i.nombre";
                    $queryImpuestos .= " from app_impuesto i, app_productos p ";
                    $queryImpuestos .= " left join app_producto_impuesto pi on p.id=pi.id_producto ";
                    $queryImpuestos .= " where p.id=" . $idProducto . " and i.id=pi.id_impuesto ";
                    $queryImpuestos .= " Order by pi.id_impuesto ".$ordenform;
                    //echo $queryImpuestos.'<br>';
                    $resImpues = $this->queryArray($queryImpuestos);
                    //print_r($resImpues['rows']);
                    //exit();
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

                            $ppii[$idProducto.'-'.$ch][]=$valueImpuestos["nombre"].'-'.$valueImpuestos["valor"].'-'.$producto_impuesto;
                            //echo $valueImpuestos["nombre"].' '.$valueImpuestos["valor"].'='.$producto_impuesto.'<br>';
                            $totalImpestos += $producto_impuesto;
                            $impuestos['cargos']['impuestos'][$valueImpuestos["clave"]] = $impuestos['cargos']['impuestos'][$valueImpuestos["clave"]] + $producto_impuesto;
                            $impuestos['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] = $impuestos['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] + $producto_impuesto;

                    }
                    $ieps='0';
                    //echo 'total='.($subtotal+$producto_impuesto).'<br>';
                }
                
            }
            //var_dump($ch);
            $impuestos['cargos']['ppii']=$ppii;
            $impuestos['cargos']['total']= ($totalImpestos + $subtotalVenta) - $producto_impuestoR;
            $impuestos['cargos']['subtotal'] = $subtotalVenta;

          //  $_SESSION['caja']['cargos']['subtotal'] += $subtotal;
            //    $_SESSION['caja']['cargos']['total'] += ($subtotal+$producto_impuesto2) - $producto_impuestoR;

            //print_r($impuestos);
            return $impuestos;
            //print_r($_SESSION['prueba']);
            //echo json_encode($_SESSION['prueba']);
            //unset($_SESSION['prueba');
        }

        function save($idProveedor,$observacion,$idcoti,$print,$op,$moneda,$observaciones,$tiporc,$notcorreo='',$msjcorreo=''){
        //$_SESSION['cotiza'] = $this->object_to_array($_SESSION['cotiza']);
           
            $x=0;
        
        foreach ($_SESSION['caja'] as $key => $producto) {

            if ($key != 'cargos') {
                $producto = (object) $producto;
                $descuentogeneral = 0;
                $conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
                $conceptosDatos[$x]["Unidad"] = $producto->medida;
                $conceptosDatos[$x]["Precio"] = $producto->precio;
                if ( $producto->nombre != '') {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->nombre);
                } else {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion);
                }

                //$conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);

                $textodescuento = '';
                //$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio - str_replace(",", "", $producto->descuento) );
                $conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio);
                $consumoTotal +=  $conceptosDatos[$x]['Importe']*1;
                $x++;


            }//fin del if del ciclo
        }//fin del cilo de llenar conceptos

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
        $queryClient="SELECT c.razon_social as nombre,c.domicilio as direccion,c.email,c.cp, e.estado,m.municipio
,c.rfc,c.no_ext as num_ext,c.no_int as num_int ";
        $queryClient.=" from mrp_proveedor c, estados e,municipios m ";
        $queryClient.=" where c.idestado=e.idestado and c.idmunicipio=m.idmunicipio and c.idPrv=".$idProveedor;
        $queryClient;
        $result = $this->queryArray($queryClient);
        $Email = $result["rows"][0]["email"]; 

      ////////DATOS EMISOR
        $queryOganizacion="SELECT o.nombreorganizacion,o.RFC,r.descripcion as regimen,o.domicilio,e.estado,m.municipio,o.cp,o.colonia,o.paginaweb,o.logoempresa ";
        $queryOganizacion.=" from organizaciones o, estados e,municipios m, nomi_regimenfiscal r ";
        $queryOganizacion.=" where o.idestado=e.idestado and o.idmunicipio=m.idmunicipio and o.idregfiscal = r.idregfiscal";
        $result2 = $this->queryArray($queryOganizacion);
            
   
////////////////PDF
        $insertedCotId=$idcoti;
        $fechaactual = date('Y-m-d H:i:s');
        include "../../modulos/SAT/PDF/COTIZACIONESPDF.php";
        $obj = new CFDIPDF( );
        $nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
            $obj->datosCFD($insertedCotId, $fechaactual,$tiporc,$moneda);
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

            $obj->agregarConceptos('.'.$conceptosOri);
            $obj->agregarTotal($_SESSION['caja']['cargos']['subtotal'], $_SESSION['caja']['cargos']['total'], $nevo);
            $obj->agregarMetodo('', '',$moneda);
            //$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
            $obj->agregarObservaciones($observaciones);
            if ($result2["rows"][0]["logoempresa"] != "")
                $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
            else
                $obj->generar("", 0);
            $obj->borrarConcepto();        

            if($msjcorreo==''){
                $msjcorreohtml = 'Estimado Proveedor, envio la orden de compra, Saludos.';
            }else if($msjcorreo=='1'){
                $msjcorreohtml = 'Estimado Usuario, tiene una requisicion pendiente por autorizar.';
            }else{
                $msjcorreohtml = '';
            }
            
            if($print==0){
                if($notcorreo!=''){
                   $Email=$notcorreo;
                }
                if ($Email != '') {
                      
                        require_once('../../modulos/phpmailer/sendMail.php');

                        $mail->From = "mailer@netwarmonitor.com";
                        $mail->FromName = "NetwarMonitor";
                        $mail->Subject = " Requisición"; ///
                        $mail->AltBody = "NetwarMonitor";
                        $mail->MsgHTML($msjcorreohtml);
                        $mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/pedido_'.$insertedCotId.".pdf");
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
