<?php
//ini_set('display_errors', 1);
require("models/connection_sqli.php"); // funciones mySQLi

class casioModel extends Connection {
     
    public function __construct() {
        session_start();
      /*  casiocionModel::simple();
        casiocionModel::propina();
        casiocionModel::sessiooon();
        //unset($_SESSION["sucursal"]);
        //unset($_SESSION["caja"]);
        //unset($_SESSION["simple"]);
        //$_SESSION["simple"] = true; */
    }   
        function readFile($url){
            unset($_SESSION['caja']);
            $codigoProducto = '';
            $cantidad = 0.00;
            $read = fopen($url, 'r') or die ('erro al leer');
            while (!feof($read)) {
                $linea=fgets($read);
                $jump=nl2br($linea);

                $x=substr($jump,1,31);
                $codigoProducto=substr($x,0,20);
                $cantidad=substr($x,20);
                echo '('.$codigoProducto.'-'.$cantidad.')';
                $xy = $this->agregaProducto($codigoProducto, $cantidad);
            
                //echo 'Codigo='.$codigoProducto;
               // echo '<br />';
                //echo '  cantida='.$catidad;
             /*   echo '<br />';
                $selectIdPro = "SELECT * from mrp_producto where codigo='".$codigoProducto."'";
                
                $result1=$this->queryArray($selectIdPro);
                echo 'idProducto='.$result1['rows'][0]['idProducto'];
                $idProducto = $result1['rows'][0]['idProducto'];
          

            $queryProducto = "SELECT p.idProducto, p.nombre, p.precioventa,p.idunidad,u.compuesto ";
            $queryProducto .=" FROM mrp_producto p,mrp_unidades u WHERE p.idunidad=u.idUni and p.idProducto=".$idProducto;
          
            $result = $this->queryArray($queryProducto);

                /*if (!isset($_SESSION['casio'])) {
                    session_start();
                } */
          /*  $E=$cantidad;
            $importe=$result["rows"][0]["precioventa"]*$cantidad;
           /* $_SESSION['casio'] = $this->object_to_array($_SESSION['casio']);
            foreach ($_SESSION['casio'] as $key => $value) {
                if($key==$idProducto){
                    $E=$value['cantidad']+$cantidad;
                    $importe=$value['precio']*$E;
                }

            } */
     
           // echo $E;
          /*  $arraySession = new stdClass();

            $arraySession->cantidad = $E;
            $arraySession->idProducto = $result["rows"][0]["idProducto"];
            $arraySession->nombre = $result["rows"][0]["nombre"];
            $arraySession->idunidad = $result["rows"][0]["idunidad"];
            $arraySession->unidad = $result["rows"][0]["compuesto"];
            $arraySession->precio = $result["rows"][0]["precioventa"];
            $arraySession->importe = $importe;
            $arraySession->impuesto = array();

            $_SESSION['casio'][$result["rows"][0]["idProducto"]]=$arraySession; */
           // print_r($_SESSION['casio']);
            }
            fclose($read);
            print_r($_SESSION['caja']);
          /*  $x=$this->calculaImpuestos();

            ///inserciones 
            date_default_timezone_set("Mexico/General");
            $fecha = date("Y-m-d H:i:s");

            $monto = $_SESSION['casio']['charges']['Tot'];
            $montoimpuestos = $_SESSION['casio']['charges']['taxesTot'];
            echo 'monrto='.$monto;
            echo 'impuesto'.$montoimpuestos;

            $insertVenta = "INSERT INTO venta(idCliente,monto,idEmpleado,documento,fecha,cambio,montoimpuestos,idSucursal)";
            $insertVenta .=" values('".$idCliente."','".$monto."','".$idEmpleado."','1','".$fecha."','0.00','".$montoimpuestos."','".$sucursal."')";
            $resultVinsert = $this->queryArray($insertVenta);
            $idVenta = $resultVinsert["insertId"];
            echo $insertVenta;  

            $_SESSION['casio'] = $this->object_to_array($_SESSION['casio']);
            //print_r($_SESSION['casio']);
            $montoimpuestosProducto = 0.00;
            $total = 0.00;
            foreach ($_SESSION['casio'] as $key => $value) {
                if($key!='charges'){

                    foreach ($_SESSION['casio'][$key]['impuesto'] as $key2 => $value2) {
                        $montoimpuestosProducto += $value2;
                    }
                    $total = $value['importe']+$montoimpuestosProducto;
                    /*---- Insert producto de las ventas*/
                  /*  $insertVePro = "INSERT INTO venta_producto(idProducto,cantidad,preciounitario,subtotal,idVenta,impuestoproductoventa,total)";
                    $insertVePro .=" VALUES('".$key."','".$value['cantidad']."','".$value['precio']."','".$value['importe']."','".$idVenta."'.'".$montoimpuestosProducto."','".$total."')";
                    $restInsertBePro = $this->queryArray($insertVePro);
                    $idVentaProd = $restInsertBePro["insertId"];

                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre,i.id";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=".$value['idProducto']." and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto asc ";

                    $result = $this->queryArray($queryImpuestos);
                    foreach ($result['rows'] as $key3 => $value3) {

                        $insertVeProImp = "INSERT INTO venta_producto_impuesto(idVentaproducto,idImpuesto,porcentaje)";
                        $insertVeProImp .=" VALUES('".$idVentaProd."','".$value3['id']."','".$value3['valor']."')";
                        $resultVPI = $this->queryArray($insertVeProImp);
                    }

                                    
                }

            } 
                /*--- Insert de los pagos y forma de pago */
                //$insertPago = "INSERT INTO venta_pagos(idVenta,idFormapago,monto) VALUES('".."','".."','".."')";
          // print_r($_SESSION['casio']);
        }

        function cargaCliente(){
            $query = "SELECT * from comun_cliente";
            $result = $this->queryArray($query);

            return array("cliente" => $result['rows'] );

        } 
        function guardaVenta(){
            date_default_timezone_set("Mexico/General");
            $fechaactual = date("Y-m-d H:i:s");

        }

        function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = $this->object_to_array($value);
                }
                return $result;
            }
            return $data;
        }        
    function calculaImpuestosX(){
        
        
        $_SESSION["casio"]["charges"]["taxes"]['IVA'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['IEPS'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['test'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['ISH'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['ISR'] = 0.0;
        $_SESSION['casio'] = $this->object_to_array($_SESSION['casio']);
            $ieps=0;
            
            foreach ($_SESSION['casio'] as $key => $value) {
                if($key!='charges'){
                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=".$value['idProducto']." and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto asc ";

                    $result = $this->queryArray($queryImpuestos);

                    $subtotal=$value['precio']*$value['cantidad'];

                    foreach ($result['rows'] as $key2 => $value2) {
                        if($value2['nombre']=='IEPS'){
                             $producto_impuesto += $ieps = (($subtotal) * $value2["valor"] / 100);

                        }else{
                            if($ieps != 0){
                                $producto_impuesto += ((($subtotal + $ieps)) * $value2["valor"] / 100);
                            }else{
                                $producto_impuesto += (($subtotal) * $value2["valor"] / 100);
                            }
                        }
                       
                        $_SESSION["casio"]["charges"]["taxes"][$value2["nombre"]]=$producto_impuesto;
                        $_SESSION["casio"][$key]['impuesto'][$value2["nombre"]]=$producto_impuesto;
                    }
                    /* $_SESSION["casio"][$key]->impuesto = 'ESto es algo';
                     foreach ($_SESSION['casio'][$key]['impuesto'] as $key => $value) {
                         
                     }*/
                }
            }

            foreach ($_SESSION["casio"] as $key => $value) {
                if($key!='charges'){
                    $subtot+=$value['importe'];
                }   
            }
            foreach ($_SESSION["casio"]["charges"]["taxes"] as $key => $value) {
                $taxesTot +=$value; 
                    
            }

            $_SESSION["casio"]["charges"]["sbtot"] = $subtot; 
            $_SESSION["casio"]["charges"]["taxesTot"] = $taxesTot;
            $_SESSION["casio"]["charges"]["Tot"] = $subtot + $taxesTot;

    } 

    function agregaProducto($idArticulo, $cantidadInicial) {
        /*print_r($_SESSION['mesa']);
        exit(); */
        //$_SESSION['mesa'] = 0;
        echo 'hola';
        try {   

           
        
            $selectId = "Select idProducto,codigo ";
            $selectId .= " from mrp_producto ";
            //$selectId .= " where strcmp(idProducto,'" . $idArticulo . "')=0 or strcmp(codigo,'" . $idArticulo . "')=0";
            $selectId .= " where codigo='".$idArticulo."'";
            $resultselectId = $this->queryArray($selectId);
            $idArticulo = $resultselectId["rows"][0]["idProducto"];
            $codigo = $resultselectId["rows"][0]["codigo"];

            /*
              En esta funcion buscamos toda la informacion del producto que se selecciona en el autocomplete,y despues
              hacemos los calculos de impuestos para despues subir el producto a la caja que se encuentra a session y devolverlo a la vista.
             */
              $comanda = false;
              $options = array();

              if (!isset($_SESSION['almacen'])) {
                $strSql = " SELECT au.idSuc,mp.nombre ";
                $strSql .= " FROM administracion_usuarios au,mrp_sucursal mp ";
                $strSql .= " WHERE mp.idSuc=au.idSuc AND au.idempleado=" . $_SESSION['accelog_idempleado'];

                $q = $this->queryArray($strSql);

                if ($q["total"] > 0) {
                    $_SESSION["sucursal"] = $q["rows"][0]["idSuc"];
                } else {
                    $_SESSION["sucursal"] = 1;
                }



                $strSql = "SELECT s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen ";
                $strSql .= " FROM mrp_sucursal s, almacen a ";
                $strSql .= " WHERE s.idAlmacen=a.idAlmacen AND s.idSuc=" . $_SESSION["sucursal"];

                $qsuc = $this->queryArray($strSql);

                if ($q["total"] > 0) {
                    $_SESSION["almacen"] = $qsuc["rows"][0]['idAlmacen'];
                } else {
                    $_SESSION["almacen"] = 1;
                }
            }


            /* $selectUnidadesPeso = "Select identificadores from unid_generica where tipo = 'Peso'";
            $resultUnidades = $this->queryArray($selectUnidadesPeso); */

            $tipo = "Select nombre from mrp_producto where strcmp(nombre,'" . $idArticulo . "')=0 OR  strcmp(codigo,'" . $idArticulo . "')=0 OR  strcmp(idProducto,'" . $idArticulo . "')=0 ";
            $tipo = $this->queryArray($tipo);

            $comanda = false;
            $pos = strpos($tipo["rows"][0]["nombre"], "comanda");
            if ($pos !== false) {
                $comanda = true;
            }

            if ($comanda == true || !is_numeric($idArticulo)) {


                /*
                  Comprobamos que el articulo sea o no  una comanda si lo es consultamos los productos de la comanda
                  y los agregamos a la caja.
                 */
                  $rows = array();

                  $idArticulo = strtoupper($idArticulo);
                  $idArticulo2=$idArticulo;
                  $pos = strpos($idArticulo, "COM");
                  if ($pos !== false || $comanda == true) {
                    $_SESSION['mesa'] = 0;
                    //Obtenermos el codigo de la comanda porque es $idArticulo es el id no el codigo
                    $sqlCodigoComanda = "select codigo from mrp_producto where idProducto=".$idArticulo;
                    $CodigoComanda = $this->queryArray($sqlCodigoComanda);
                    $idArticulo = $CodigoComanda["rows"][0]["codigo"];
                    $comanda = true;
                    $individual = strpos($idArticulo, "P");
                    
                    $sqlMesa = "SELECT idmesa from com_comandas where codigo='".$CodigoComanda["rows"][0]["codigo"]."'";
                    $idMesa = $this->queryArray($sqlMesa);

                    $_SESSION['mesa'] = $idMesa["rows"][0]["idmesa"];
//                    var_dump($_SESSION['mesa']);



                    /*
                      Validamos que la comanda sea pago completo o por persona, si el primer caracter es '#', la comanda se paga por persona.
                     */


                      if ($individual !== false) {
                        $arrayCodigo = explode("P", $idArticulo);
                        $idArticulo = $idArticulo2;
                        $codigo = "COM" . $arrayCodigo[0];

                        $comandastr = 'COM' . substr($codigo, 0, 5);
                        $persona = round(substr($codigo, -2));

                        $wherePersona = " AND npersona = " . $arrayCodigo[1] . " ";
                        $wherePersona2 = " npersona = " . $arrayCodigo[1] . " ";
                        $person = $arrayCodigo[1];
                    } else {
                        $wherePersona = " ";
                    }


                    $sqlExistencia = "select idUnidad,idProducto id,nombre,codigo,precioventa,imagen,mrp_producto.tipo_producto  from mrp_producto where strcmp(nombre,'" . $idArticulo . "')=0 OR  strcmp(codigo,'" . $idArticulo . "')=0 OR  strcmp(idProducto,'" . $idArticulo . "')=0  and mrp_producto.estatus = 1 ";

                    $resultExistencia = $this->queryArray($sqlExistencia);

                    if ($resultExistencia["total"] < 1) {
                        throw new Exception("No existe una comanda con ese codigo.", 1);
                    }



                    $idProductos = "Select mp.imagen,p.idProducto,sum(cantidad) cantidad,mp.idUnidad,mp.nombre,mp.precioventa,mp.tipo_producto,mp.esreceta,mp.eskit,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM mrp_unidades u where  u.idUni=mp.idunidad)unidad";
                    //$idProductos = "Select mp.imagen,p.idProducto,(select sum(cantidad) from com_pedidos where ".$wherePersona2." )cantidad,mp.idUnidad,mp.nombre,mp.precioventa,mp.tipo_producto,mp.esreceta,mp.eskit,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM mrp_unidades u where  u.idUni=mp.idunidad)unidad";

                    $idProductos .= " FROM com_pedidos p,com_comandas c ,mrp_producto mp ";
                    $idProductos .= " WHERE c.codigo = '".$codigo."'";
                    $idProductos .= " AND p.idProducto != 0 ";
                    $idProductos .= " AND c.id=p.idcomanda ";
                    $idProductos .= " AND p.idProducto=mp.idProducto ";
                    $idProductos .= $wherePersona . " and mp.estatus = 1 ";
                    $idProductos .= " group by mp.idProducto ";
                    //echo $idProductos;
                    $result = $this->queryArray($idProductos);

                    if ($result["total"] == 0) {
                        throw new Exception("No se encontraron productos en la comanda.", 1);
                    }

                    $options[3] = $codigo;

                    /*
                      Buscamos si esta configurado algun producto para funcionar como propina
                     */

                      $queryProductoPropina = " Select idproducto from com_productos_propina";

                      $propina = $this->queryArray($queryProductoPropina);

                      if ($propina["total"] > 0) {
                        $options[4] = $propina["rows"][0]["idproducto"];
                    }
                } else {
                    $queryn = "SELECT mp.idunidad,mp.deslarga,mp.tipo_producto,mp.imagen,mp.idProducto id,mp.codigo,mp.nombre,mp.precioventa,mp.idUnidad,mp.esreceta,mp.eskit,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM
                        mrp_unidades u where  u.idUni=mp.idunidad)unidad ";
$queryn .= " FROM mrp_producto mp ";
$queryn .= " where strcmp(mp.codigo,'" . $idArticulo . "')=0 OR  strcmp(mp.idProducto,'" . $idArticulo . "')=0";
$queryn .= " and vendible=1 and mp.estatus = 1";

                    //echo "1";
$result = $this->queryArray($queryn);

if ($result["total"] == 0) {
    throw new Exception("No existe un articulo con esa descripción o codigo", 1);
}

$result["rows"][0]["idProducto"] = $idArticulo;
                    //$result["rows"][0]["codigo"] = $idArticulo;
}

} else {
    if ($idArticulo != 0) {

        $queryn = "SELECT mp.estatus,mp.idunidad,mp.deslarga,mp.tipo_producto,mp.imagen,mp.idProducto id,mp.codigo,mp.nombre,mp.precioventa,mp.idUnidad,mp.esreceta,mp.eskit,mp.tipo_producto,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM
            mrp_unidades u where  u.idUni=mp.idunidad)unidad ";
$queryn .= " FROM mrp_producto mp ";
$queryn .= " where (strcmp(mp.codigo,'" . $idArticulo . "')=0 OR  strcmp(mp.idProducto,'" . $idArticulo . "')=0)";
$queryn .= " and vendible=1  and mp.estatus = 1";


$result = $this->queryArray($queryn);

if ($result["total"] == 0) {
    throw new Exception("No existe un articulo con esa descripción o codigo", 1);
}
$result["rows"][0]["idProducto"] = $idArticulo;
}
}


            /*
              Subimos la informacion a sesion por cada producto ya sea indivudual o todos los de la comanda
             */
            //unset($_SESSION['caja']);

              $cantidad = 0;

              if (isset($_SESSION["caja"][$idArticulo])) {
                $cantidad = $_SESSION["caja"][$idArticulo]->cantidad;
            }
            foreach ($result["rows"] as $key => $value) {
                  $selectProduccion = " select cantidad from mrp_detalle_orden_produccion where idProducto=" .$value["id"];
                  $resultProduccion = $this->queryArray($selectProduccion);
                        //echo $resultProduccion["rows"][0]["cantidad"].'de';
                        //exit();
                if ($value["tipo_producto"] == 4 || ($value["tipo_producto"] == 2 && $resultProduccion["rows"][0]["cantidad"]=='')) {
                    //echo 'si entroe al if ihdeffh';
                    /*
                      Si el producto es kit verficamos que tengamos existencia de los materiales que lo componen
                     */


                      $queryMateriales = "select mp.imagen,mpm.idMaterial,mpm.cantidad,mp.deslarga,mp.tipo_producto,";
                      $queryMateriales .= "(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM ";
                        $queryMateriales .= "mrp_unidades u where  u.idUni=mp.idunidad)unidad ";
$queryMateriales .= " from mrp_producto_material mpm,mrp_producto mp ";
$queryMateriales .= " where mp.idProducto=mpm.idProducto and (mp.tipo_producto=2 or mp.tipo_producto=4) and mpm.idProducto=" . $value["id"] . " and mp.estatus = 1
";
//echo $queryMateriales;
$materilaes = $this->queryArray($queryMateriales);
//echo $producto->id.'x';
                        $selectProduccion = " select cantidad from mrp_detalle_orden_produccion where idProducto=" .$value["id"];

//echo $selectMaterial;
                       //echo $selectProduccion;             

                        $resultProduccion = $this->queryArray($selectProduccion);
                       // echo $resultProduccion["rows"][0]["cantidad"].'dededede';
                        //exit();
if ($materilaes["total"] > 0 && $resultProduccion["rows"][0]["cantidad"]=='') {


                        //Recorremos los materiles del kit
    foreach ($materilaes["rows"] as $key => $materialValue) {

        if ($_SESSION["simple"] == true && $result["rows"][0]["tipo_producto"] != 6) {
            $queryExistencia = "select s.cantidad,s.ocupados ";
            $queryExistencia .= "from mrp_stock s ";
            $queryExistencia .= "where idAlmacen=" . $_SESSION["almacen"] . " and idProducto=" . $materialValue["idMaterial"];

            $resultqueryExistencia = $this->queryArray($queryExistencia);

            //if (((float) $materialValue["cantidad"] > (float) (($resultqueryExistencia["rows"][0]["cantidad"] - $cantidad) - $resultqueryExistencia["rows"][0]["ocupados"])) && ($materialValue["cantidad"] <= $cantidadInicial) || ((float) $resultqueryExistencia["rows"][0]["cantidad"] < (float) $cantidadInicial)) {
            if (((float) ($materialValue["cantidad"]*$cantidadInicial) > (float) (($resultqueryExistencia["rows"][0]["cantidad"] - $cantidad) - $resultqueryExistencia["rows"][0]["ocupados"])) || ((float) $resultqueryExistencia["rows"][0]["cantidad"] < (float) $cantidadInicial)) {

                if ($comanda == FALSE) {
                    throw new Exception("No hay suficientes materiales para ese producto.", 1);
                }
            }
        }

        $result["rows"][0]["cantidad"] = 1;

        $arrImpMateriales .= json_encode(array('idMaterial' => $materialValue["idMaterial"], "cantidad" => $materialValue["cantidad"]));
    }
}
} else {

                    /*
                      Si no es kit verificamos existencias tomando en cuenta las devoluciones.
                     */
                      if ($value["tipo_producto"] != 6) {

                        if ($value["id"] != '') {
                            $idP = $value["id"];
                        }
                        if ($value["idProducto"] != '') {
                            $idP = $value["idProducto"];
                        }

                        $existencias = "select s.cantidad-if(SUM(o.nDevoluciones) is null,0,SUM(o.nDevoluciones)) cantidad,ocupados ";
                        $existencias .= " from mrp_stock s left join mrp_devoluciones_reporte o on o.idProducto=s.idProducto and o.idAlmacen=s.idAlmacen and o.estatus=0  ";
                        $existencias .= " where s.idAlmacen=" . $_SESSION["almacen"] . " and s.idProducto=" . $idP;

                        $resultExistencias = $this->queryArray($existencias);

                       /*  echo "(" . $resultExistencias["rows"][0]["cantidad"] . ")";
                          echo "Cantidad -> (" . $cantidad . ")";
                          echo "Ocupados -> (" . $resultExistencias["rows"][0]["ocupados"] . ")";
                          echo "Cantidad Inicial -> (" . $cantidadInicial . ")"; */

                          if ((float) (($resultExistencias["rows"][0]["cantidad"] - $cantidad) - $resultExistencias["rows"][0]["ocupados"]) > 0 && (float) $resultExistencias["rows"][0]["cantidad"] < 1 && (float) $resultExistencias["rows"][0]["cantidad"] >= $cantidadInicial) {
                            $result["rows"][0]["cantidad"] = (float) $resultExistencias["rows"][0]["cantidad"];
                        } else if ((float) (($resultExistencias["rows"][0]["cantidad"] - $cantidad) - $resultExistencias["rows"][0]["ocupados"]) >= 1 && (float) $resultExistencias["rows"][0]["cantidad"] >= $cantidadInicial) {
                            $result["rows"][0]["cantidad"] = 1;
                        } else {
                            if ($_SESSION["simple"] == true) {
                                throw new Exception("No hay suficientes materiales para ese producto..", 1);
                            } else {
                                $result["rows"][0]["cantidad"] = 1;
                            }
                        }
                    } else {
                        $result["rows"][0]["cantidad"] = 1;
                    }
                }


                /*
                  Si es una comanda necesitamos traer la informacion de cada producto, de uno por uno..
                  y despues subirlo a sesion de lo contrario solo subimos la informacion a session por que ya
                  traemos la informacion del producto.
                 */

                  if ($comanda) {
                        if($person != ''){
                            $condicion = ' and npersona='.$person;
                        }

                    $datosProducto = 'Select mrp.idunidad,mrp.tipo_producto,mrp.deslarga,mrp.imagen,mrp.codigo,mrp.nombre,mrp.precioventa,mrp.esreceta,mrp.eskit,mrp.idunidad,sum(p.cantidad) cantidad ';
                    $datosProducto .= ' from mrp_producto mrp,com_pedidos p,com_comandas c';
                    $datosProducto .= ' where mrp.idProducto = ' . $value["idProducto"] . ' and c.codigo = \'' . $options[3] . '\' ';
                    $datosProducto .= ' and p.idProducto=mrp.idProducto  and p.idcomanda = c.id and estatus = 1'.$condicion;
                    //echo $datosProducto;
                    $datosProductoResult = $this->queryArray($datosProducto);

                    $value["precioventa"] = $datosProductoResult["rows"][0]["precioventa"];
                } else {
                    $datosProductoResult = $result;
                }

                //print_r($datosProductoResult);
                //Agregamos los impuestos a un array para despues subirlos a sesion
                if (isset($resultImpuestos["rows"][0]["precioventa"])) {
                    $arrImpProducto = json_encode(array('idProducto' => $value["idProducto"], 'precioVenta' => $resultImpuestos["rows"][0]["precioventa"]));
                }

                //cantidad
                //echo "(Producto -> ".$value['idProducto'].")";
                $cantidadAnterior = (isset($_SESSION["caja"][$value["idProducto"]])) ? $_SESSION["caja"][$value["idProducto"]]->cantidad : 0;
                if (isset($_SESSION["caja"][$value["idProducto"]])) {
                    $cantidad = $cantidadAnterior + $datosProductoResult["rows"][0]["cantidad"];
                } else {
                    $cantidad = $datosProductoResult["rows"][0]["cantidad"];
                }

                //asignamos a $subtotalGeneral lo que hay en sesion para que se sumen.
                if (isset($_SESSION["caja"]["cargos"]["subtotal"])) {
                    //echo $_SESSION["caja"]["cargos"]["subtotal"];
                    $subtotalGeneral = $_SESSION["caja"]["cargos"]["subtotal"];
                } else {
                    $subtotalGeneral = 0;
                }

                if (isset($_SESSION["caja"][$value["idProducto"]]->subtotal)) {
                    $subtotal = $_SESSION["caja"][$value["idProducto"]]->subtotal;
                }

                //asignamos a $total lo que hay en sesion para que se sumen.
                $total = '';
                if (isset($_SESSION["caja"]["cargos"]["total"])) {
                    $total = $_SESSION["caja"]["cargos"]["total"];
                }

                $total = str_replace(",", "", $total);

                $subtotalGeneral = str_replace(",", "", $subtotalGeneral);



                //Calculamos el subtotal               
                $subtotal = $value["precioventa"] * $cantidad;

                //echo 'precio='.$value["precioventa"];
                $subtotalGeneral += $value["precioventa"] * $cantidad;



                //calculamos el total de la venta
                if (isset($_SESSION["caja"]["cargos"]["impuestos"]["suma"])) {
                   // echo 'subtotal='.$subtotalGeneral;
                    $total = ($subtotalGeneral + $_SESSION["caja"]["cargos"]["impuestos"]["suma"]);
                } else {
                   // echo 'subtotal='.$subtotalGeneral;
                    $total = $subtotalGeneral;
                }

                //Se suben los impuestos a sesion
                $_SESSION["caja"]["cargos"]["subtotal"] = $subtotalGeneral;
                $_SESSION["caja"]["cargos"]["total"] = $total;


                /* Validamos si el producto ya esta en sesion
                  if(!isset($_SESSION['caja'][$value["idProducto"]]))
                  { */

                    if (!$comanda) {
                        $selectIdProducto = "Select idProducto, codigo from mrp_producto where strcmp(idProducto,'" . $idArticulo . "')=0  or codigo = '" . $idArticulo . "' and estatus = 1 ";
                        $resultidProducto = $this->queryArray($selectIdProducto);

                        $value["idProducto"] = $resultidProducto["rows"][0]["idProducto"];
                        $datosProductoResult["rows"][0]["codigo"] = $resultidProducto["rows"][0]["codigo"];
                    }
                    
                    $arraySession = new stdClass();

                    $producto_impuesto = (isset($_SESSION['caja'][$value["idProducto"]])) ? $_SESSION['caja'][$value["idProducto"]]->impuesto : 0;
                    $suma_impuestos = (isset($_SESSION['caja'][$value["idProducto"]])) ? $_SESSION['caja'][$value["idProducto"]]->suma_impuestos : 0;

                    $arraySession->id = $value["idProducto"];
                    $arraySession->nombre = $datosProductoResult["rows"][0]["nombre"];
                    $arraySession->descripcion = $datosProductoResult["rows"][0]["deslarga"];
                    $arraySession->imagen = $datosProductoResult["rows"][0]["imagen"];
                    $arraySession->codigo = $datosProductoResult["rows"][0]["codigo"];
                   /* if($_SESSION['caja'][$idArticulo]->precioventa=''){
                        $str_precioventa = number_format($datosProductoResult["rows"][0]["precioventa"], 2);

                    }else{
                        $str_precioventa = number_format($datosProductoResult["rows"][0]["precioventa"], 2);
                    } */
                    $str_precioventa = $datosProductoResult["rows"][0]["precioventa"];
                    $str_precioventa = str_replace(",", "", $str_precioventa);
                    $arraySession->precioventa = $str_precioventa;
                    $arraySession->esreceta = $datosProductoResult["rows"][0]["esreceta"];
                    $arraySession->eskit = $datosProductoResult["rows"][0]["eskit"];
                    $arraySession->cantidad = $cantidad;
                    $arraySession->unidad = $datosProductoResult["rows"][0]["unidad"];
                    $arraySession->idunidad = $datosProductoResult["rows"][0]["idunidad"];
                    if (isset($arrImpProducto) && $arrImpProducto != '') {
                        $array_kit .= json_encode($arrImpProducto);
                    } else if (isset($arrImpMateriales)) {
                        $array_kit .= json_encode($arrImpMateriales);
                    }
                    if (isset($array_kit)) {
                        $arraySession->arr_kit = $array_kit;
                    } else {
                        $arraySession->arr_kit = '';
                    }
                    if (isset($descuento)) {
                        $arraySession->descuento = $descuento;
                    } else {
                        $arraySession->descuento = 0.00;
                    }
                    $arraySession->tipodescuento = '$';
                    $arraySession->impuesto = $producto_impuesto;
                    $arraySession->suma_impuestos = $suma_impuestos;
                    $arraySession->subtotal = $subtotal;
                    $arraySession->tipo_producto = $datosProductoResult["rows"][0]["tipo_producto"];
                    

                    //$this->iniTrans();
                     /*   $selectImpuestos = "SELECT id_impuesto,valor from pvt_producto_impuestos where idProducto=".$value["idProducto"];
                        $pvt_impues = $this->queryArray($selectImpuestos);

                        $impuestosString = $pvt_impues["rows"][0]["id_impuesto"].'|'.$pvt_impues["rows"][1]["id_impuesto"].'|'.$pvt_impues["rows"][2]["id_impuesto"].'|'.$pvt_impues["rows"][3]["id_impuesto"];

                        $selectProd = "SELECT nombre_producto from pvt_ventas_test where id_sesion='".$_SESSION["sesionid"]."' and idProducto=".$value["idProducto"];
                

                        $rslt = $this->queryArray($selectProd);

                        $lengthArray = count($rslt["rows"]);
                        if ($lengthArray < 1) {
                            $insertProducto = 'insert into pvt_ventas_test (id_sesion,idProducto,nombre_producto,cantidad,precio,subtotal,impuestos,descripcion,unidad,arr_kit,tipo_producto)'
                            .' values ("'.$_SESSION["sesionid"].'","'.$value["idProducto"].'","'.$datosProductoResult["rows"][0]["nombre"].'","'.$cantidadInicial.'","'.$str_precioventa.'","'.($str_precioventa*$cantidadInicial).'","'.$impuestosString.'","'.$datosProductoResult["rows"][0]["deslarga"].'","'.$datosProductoResult["rows"][0]["unidad"].'","'.$array_kit.'","'.$datosProductoResult["rows"][0]["tipo_producto"].'")';
                            $insertProducto = $this->query($insertProducto);
                        }else{
                            $updateinsertProducto = 'UPDATE pvt_ventas_test set cantidad=cantidad+'.$cantidadInicial.' , subtotal=subtotal+'.($str_precioventa*$cantidadInicial).' where id_sesion="'.$_SESSION["sesionid"].'" and idProducto='.$value["idProducto"];
                            $updateinsertProducto = $this->query($updateinsertProducto);
                        }


                   // $this->commit(); 
                    $this->calculaImpuestosMysql($value["idProducto"],$cantidadInicial,$impuestosString); */
                    $_SESSION['caja'][$value["idProducto"]] = (object) $arraySession;

                //Datos de conversion
                    $arraySession->idUnidad = $datosProductoResult["rows"][0]["idUnidad"];


                /*
                 *  Calculamos los impuestos del producto
                 */
                $this->calculaImpuestos($value["idProducto"], $comanda);
            }

            /*
              Despues de subir los datos a sesion hay que pintarlos en la caja, y para eso se regresa un array a la vista y los productos se pintan con javascript...
             */
             // $this->propina();

            /*
              Valor de la propina en caso de que sea comanda
             */

            /* echo "(".$cantidadInicial.")";
              echo "(".$_SESSION["caja"][$value["idProducto"]]->cantidad.")";
              echo (float)$cantidadInicial+(float)$_SESSION["caja"][$value["idProducto"]]->cantidad; */
            //echo "(".$cantidadAnterior." - ".$cantidadInicial.")";

            /* echo "(Anterior => ".$cantidadAnterior.")";
            echo "(Inicial => ".$cantidadInicial.")"; */

            if ($comanda && $_SESSION["propina"]) {
                $options[5] = ($_SESSION["caja"]["cargos"]["total"]) * 10 / 100;
                if ($_SESSION["propina"] == true) {
                    $options[6] = true;
                } else {
                    $options[6] = false;
                }
            } else if ($cantidadAnterior != $cantidadInicial) {

                /* $patron = "/[".$value["idunidad"]."]?/";
                  $cambiarCantidad = 0;
                  preg_match($patron, $resultUnidades["rows"][0]["identificadores"],$encontrados);
                  if($encontrados[0] == ''){
                  $cambiaCantidad = round((float)$cantidadInicial+(float)$cantidadAnterior);
                  }else
                  {
                  $cambiaCantidad = (float)$cantidadInicial+(float)$cantidadAnterior;
              } */

              if ($_SESSION["caja"][$idProducto]->idunidad == 1 || $_SESSION["caja"][$idProducto]->unidad == 'unidad' || $_SESSION["caja"][$idProducto]->unidad == 'Unidad') {
                $cambiaCantidad = round((float) $cantidadInicial + (float) $cantidadAnterior);
            } else {
                $cambiaCantidad = (float) $cantidadInicial + (float) $cantidadAnterior;
            }

                //echo "(nueva -> ".$cambiaCantidad.")";
            if (!$comanda) {
                $this->cambiaCantidad($value["idProducto"], $cambiaCantidad, "$", "0.00", '');
            } 
        }   
        return;
        //return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "opciones" => $options, "simple" => $_SESSION["simple"], "sucursal" => $_SESSION["sucursalNombre"], "empleado" => $_SESSION["nombreEmpleado"]);
    } catch (Exception $e) {
        echo $e;
        return;
        //return array('status' => false, 'msg' => $e->getMessage());
    }
}
function calculaImpuestos($idProducto, $comanda, $descuento = 0.00, $precionuevo = 0.00) {

        if($precionuevo == 0){

            $selectPrecioVenta = "Select precioventa from mrp_producto where idProducto = " . $idProducto . " or codigo = '" . $idProducto . "' ";
            $resultPrecioV = $this->queryArray($selectPrecioVenta);

            $precionuevo = $resultPrecioV["rows"][0]["precioventa"];
        }
        /*
          Consultamos los impuestos del producto si los tiene.
         */

          $suma_impuestos = 0;
          $producto_impuesto = 0;
          $total = 0;
          $ieps = 0;
          //echo 'primer='.$ieps;
          $selectIdProducto = "Select idProducto, codigo from mrp_producto where idProducto = " . $idProducto . " or codigo = '" . $idProducto . "' ";
          $resultid = $this->queryArray($selectIdProducto);

          $idProducto = $resultid["rows"][0]["idProducto"];

          $subtotal = $_SESSION['caja'][$idProducto]->subtotal;
          $cantidad = ($comanda) ? $_SESSION['caja'][$idProducto]->cantidad : 1;


          $_SESSION['caja'][$idProducto]->id = $resultid["rows"][0]["idProducto"];
          $_SESSION['caja'][$idProducto]->codigo = $resultid["rows"][0]["codigo"];

        //print_r($_SESSION["caja"]);

          $_SESSION["caja"]["cargos"]["impuestos"]['IVA'] = 0.0;
          $_SESSION["caja"]["cargos"]["impuestos"]['IEPS'] = 0.0;
          $_SESSION["caja"]["cargos"]["impuestos"]['test'] = 0.0;
          //echo 'sgundo='.$ieps;
          foreach ($_SESSION["caja"] as $key => $value) {
            if ($key != 'cargos') {
                if ($key == $idProducto) {
            //        echo 'terccer='.$faieps;
                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=" . $idProducto . " and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto DESC ";
                    //echo $queryImpuestos;
                    //exit();
                    $resultImpuestos = $this->queryArray($queryImpuestos);

                    $_SESSION['caja'][$idProducto]->impuesto = 0.0;
                    //print_r($resultImpuestos["rows"]);
                    //exit();
                    //echo 'cuarto='.$ieps;
                    $max = sizeof($resultImpuestos["rows"]);
                    if($max==1){
                        $ieps=0;
                    }

                    foreach ($resultImpuestos["rows"] as $key => $valueImpuestos) {
                       //echo 'DDDD'.$key;
                       //exit();
                       if($precionuevo!=0){
                            $precio = $precionuevo;
                        }else{

                            $precio = $valueImpuestos["precioventa"];
                        } 

                       // echo 'cinco='.$ieps;


                        $descuento = str_replace(",", "", $descuento);
                        if($_SESSION['caja'][$idProducto]->cantidad!=''){
                            $cantidad=$_SESSION['caja'][$idProducto]->cantidad;
                        }
                        //$subtotal = $_SESSION['caja'][$idProducto]->subtotal = str_replace(",", "", number_format(($valueImpuestos["precioventa"] * $cantidad ) - $descuento, 2));
                        $subtotal = $_SESSION['caja'][$idProducto]->subtotal = str_replace(",", "", ($precio * $cantidad ) - $descuento);
           
                        $suma_impuestos = $valueImpuestos["valor"];
                        //echo 'seis='.$ieps;
                     /*   if(ieps=0)
                            ipes=0
                        else
                            ieps=precio*(ieps/100)

                        if(iva=0)
                            iva=0
                        else
                            iva=precio*(iva/100)

                        importetotal=precio+ieps+iva */

                        if ($valueImpuestos["nombre"] == 'IEPS') {
                            //echo 'siete='.$ieps;
                            //$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
                            //echo '?'.$producto_impuesto;
                           // exit();
                        } else {
                            if ($ieps != 0) {
                                /*if($ke==0){
                                    $ieps=0;
                                } */
                             //   echo 'F'.$valueImpuestos;
                               // echo 'ocho='.$ieps;
                                //echo 'iepssi';
                                //$producto_impuesto = ((($subtotal)) * $valueImpuestos["valor"] / 100);
                                //echo $key;
                               //echo "(".$subtotal.'+'.$ieps.')*'.$valueImpuestos["valor"].'/ 100';
                                $producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
                                //echo 'X'.$producto_impuesto;
                                //exit();
                            } else {
                                //echo 'nohayieps';
                                $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                               // echo 'Y'.$producto_impuesto;
                                //exit();
                            }
                        }

                        //echo "(".$subtotal ."-". $descuento ."*". $valueImpuestos["valor"].")";
                        //echo $producto_impuesto.'X';

                        $_SESSION['caja'][$idProducto]->impuesto = str_replace(",", "", $_SESSION['caja'][$idProducto]->impuesto) + $producto_impuesto;
                        $_SESSION['caja'][$idProducto]->suma_impuestos += $suma_impuestos;
                        $_SESSION['caja'][$idProducto]->cargos->$valueImpuestos["nombre"] = $producto_impuesto;

                        $_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]] = str_replace(",", "", $_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]]) + $producto_impuesto;
                    }
                } else {

                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=" . $key . " and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto DESC ";

                    $resultImpuestos = $this->queryArray($queryImpuestos);

                    $_SESSION['caja'][$key]->impuesto = 0.0;

                    foreach ($resultImpuestos["rows"] as $key2 => $valueImpuestos) {

                        if (!isset($_SESSION['caja'][$key]->descuento_neto) && $_SESSION['caja'][$key]->descuento_neto == '') {
                            $_SESSION['caja'][$key]->descuento_neto = 0.0;
                        }
                        
                        if($precionuevo!=0){

                            $precio = $precionuevo;
                        }else{
                            $precio = $valueImpuestos["precioventa"];
                        } 

                        //echo $_SESSION['caja'][$key]->descuento_neto;

                        $subtotal = $_SESSION['caja'][$key]->subtotal = str_replace(",", "", ($valueImpuestos["precioventa"] * $_SESSION['caja'][$key]->cantidad ) - str_replace(",", "", $_SESSION['caja'][$key]->descuento_neto));
                        //echo "(".$_SESSION['caja'][$key]->descuento_neto.")";
                        $suma_impuestos = $valueImpuestos["valor"];

                        if ($valueImpuestos["nombre"] == 'IEPS') {
                            $producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
                            //$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                        } else {
                            if ($ieps != 0) {
                                $producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
                                //$producto_impuesto = ((($subtotal)) * $valueImpuestos["valor"] / 100);
                            } else {
                                $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                            }
                        }

                        //echo "(".$subtotal ."-". $descuento ."*". $valueImpuestos["valor"].")";

                        $_SESSION['caja'][$key]->impuesto = str_replace(",", "", $_SESSION['caja'][$key]->impuesto) + $producto_impuesto;
                        $_SESSION['caja'][$key]->suma_impuestos += $suma_impuestos;
                        $_SESSION['caja'][$key]->cargos->$valueImpuestos["nombre"] = $producto_impuesto;

                        $_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]] = str_replace(",", "", $_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]]) + $producto_impuesto;
                    }
                }
            }
        }

        foreach ($_SESSION["caja"] as $key => $value) {
            if ($key != 'cargos') {
                $strimpuesto = str_replace(",", "", $_SESSION["caja"][$key]->impuesto);
                $total += $strimpuesto;
                $subtotalGeneral += str_replace(",", "", $_SESSION["caja"][$key]->subtotal);
            }
        }

        $str_subtotal = str_replace(",", "", $subtotalGeneral);
        $_SESSION["caja"]["cargos"]["subtotal"] = $str_subtotal;

        $str_total = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
        $_SESSION["caja"]["cargos"]["total"] = $str_subtotal + $total;


        //print_r($_SESSION["caja"]);
    } 
function cambiaCantidad($idProducto, $cantidad, $tipo, $descuento, $comentario, $precionuevo) {
    //echo 'ESD='.$entra;
 /* if($entra==0){
    $this->cambiaCantidadMysql($idProducto, $cantidad, $tipo, $descuento, $comentario, $precionuevo);
  } */
  //$this->cambiaCantidadMysql($idProducto, $cantidad, $tipo, $descuento, $comentario, $precionuevo);
   // echo 'cqntidad='.$cantidad.' descuento='.$descuento.' $precionuevo='.$precionuevo;
    if($precionuevo!=''){
            $precioventa = $_SESSION['caja'][$idProducto]->precioventa = $precionuevo;   
     }
     
    $cantidad = $_SESSION['caja'][$idProducto]->cantidad = $cantidad;

    $cantidad = str_replace(",", "", $cantidad);
    $_SESSION['caja'][$idProducto]->subtotal = $cantidad * $precioventa;
    $_SESSION['caja'][$idProducto]->descuento = 0.0;
    $_SESSION['caja'][$idProducto]->tipodescuento = $tipo;
    $_SESSION['caja'][$idProducto]->descuento_cantidad = $descuento;

    if ($tipo != '' && $descuento != 0.0) {
        if ($tipo == "%") {

            $_SESSION['caja'][$idProducto]->descuento = number_format(($_SESSION['caja'][$idProducto]->precioventa * $cantidad) * $descuento / 100, 2);
            $_SESSION['caja'][$idProducto]->descuento_neto = $_SESSION['caja'][$idProducto]->descuento;
        } else if ($tipo == "$") {
            $_SESSION['caja'][$idProducto]->descuento = number_format($descuento, 2);
            $_SESSION['caja'][$idProducto]->descuento_neto = $_SESSION['caja'][$idProducto]->descuento;
        }
    } else {
        $_SESSION['caja'][$idProducto]->descuento_neto = 0.0;
    }

    $_SESSION['caja'][$idProducto]->subtotal = number_format(($_SESSION['caja'][$idProducto]->precioventa * $cantidad) - str_replace(",", "", $_SESSION['caja'][$idProducto]->descuento), 2);

    foreach ($_SESSION["caja"] as $key => $value) {

        foreach ($value->cargos as $key2 => $value2) {

            $data = (array) $_SESSION["caja"][$key]->cargos;
            $impuestoxCant = $value2;
            $sumaImpuestos += $impuestoxCant;



            if ($descuento != '') {
                if ($tipo == "%") {
                    $impuestos = str_replace(",", "", ($sumaImpuestos * $_SESSION['caja'][$idProducto]->cantidad) * $descuento / 100);
                        //$_SESSION['caja'][$key]->impuesto
                } else if ($tipo == "$") {
                    $impuestos = str_replace(",", "", $sumaImpuestos);
                }
            } else {
                $impuestos = str_replace(",", "", $sumaImpuestos * $_SESSION['caja'][$idProducto]->cantidad);
            }
        }
            //$_SESSION['caja'][$key]->impuesto = number_format(str_replace(",","", $impuestos),2);
    }

    $this->calculaImpuestos($idProducto, true, $_SESSION['caja'][$idProducto]->descuento, $precioventa);


    return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "count" => count($_SESSION['caja']), "simple" => $_SESSION["simple"], "sucursal" => $_SESSION["sucursalNombre"], "empleado" => $_SESSION["nombreEmpleado"]);
}       

}