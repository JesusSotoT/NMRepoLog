<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class CompraModel extends Connection
{
	public function indexGrid()
	{
		/*$myQuery = "SELECT 
                                        oc.idOrd Id,
                                        p.razon_social Proveedor,
                                        oc.fecha_pedido Fecha_pedido,
                                        oc.fecha_entrega Fecha_de_entrega,
                                        oc.elaborado_por Elaboro,
                                        a.nombre Almacen,
                                        
                                        oc.autorizado_por Autorizacion,
                                        oc.estatus Estatus 
                                    FROM mrp_orden_compra oc, mrp_sucursal s, almacen a,mrp_proveedor p 
                                    WHERE s.idSuc=oc.idSuc and p.idPrv=oc.idProveedor  AND a.idAlmacen=oc.idAlmacen"; */
        $myQuery = "SELECT o.id, p.razon_social, u.usuario, o.fecha, o.fecha_entrega, o.total ,o.activo
                    from app_ocompra o, mrp_proveedor p, accelog_usuarios u
                    where o.id_usrcompra=u.idempleado  and o.id_proveedor=p.idPrv";                            
		$resultados = $this->queryArray($myQuery);
		return $resultados['rows'];
	}

    public function cuenta($nc)
    {
        $myQuery = "SELECT description FROM cont_accounts WHERE account_id = $nc";
        $cuenta = $this->query($myQuery);
        $cuenta = $cuenta->fetch_assoc();
        return $cuenta['description'];
    }
    public function almacenes(){
        $query = 'SELECT * FROM app_almacenes where id_almacen_tipo=1';
        $almacenes = $this->queryArray($query);

        return $almacenes['rows'];
    }
    public function usuarios(){
        $queryU = "SELECT * from accelog_usuarios";
        $res = $this->queryArray($queryU);

        return $res['rows'];
    }
    public function ListaProveedor(){
        $query = 'SELECT * FROM mrp_proveedor';
        $proves = $this->queryArray($query);
        //var_dump($proves);
        //$proves = $proves->fetch_assoc();
        
        return $proves['rows'];
        
    }
    public function productosProveedor($idPrv,$idAlmacen){

       /* $query = "SElECT p.idProducto,p.codigo, p.nombre,pro.costo, s.cantidad ";
        $query .="from mrp_producto p,mrp_producto_proveedor pro ";
        $query .="INNER JOIN mrp_stock s ON pro.idProducto = s.idProducto ";
        $query .="WHERE p.idProducto=pro.idProducto and pro.idPrv=".$idPrv." and s.idAlmacen=".$idAlmacen; */
        $query .='SElECT p.id,p.codigo, p.nombre, cp.costo, u.nombre as unidad, u.id as idUnidadC ';
        $query .=' from app_productos p,app_producto_proveedor pro, app_costos_proveedor cp, app_unidades_medida u';
        $query .=' where p.id=pro.id_producto and pro.id_proveedor='.$idPrv.' and cp.id_proveedor='.$idPrv.' and cp.id_producto = pro.id_producto and p.id_unidad_compra =  u.id'; 
        //echo 'X'.$query;
        $productos = $this->queryArray($query);
        return $productos['rows'];

    }

    public function buscaIdpro($codigo){
        $query = "SELECT id from app_productos where codigo='".$codigo."'";
        $productos = $this->queryArray($query);

        return array("idProducto" => $productos['rows'][0]['id']);
    }
    public function guardaOrden($idAlmacen,$idProvedor,$productos,$fecha_entrega,$subTotal,$total,$user){
        
        $fechaactual = date('Y-m-d H:i:s');
        $selctEmpleado = "SELECT usuario from accelog_usuarios where idempleado=".$_SESSION['accelog_idempleado'];
        $empleadoRes = $this->queryArray($selctEmpleado);
        $empleado = $empleadoRes['rows'][0]['usuario'];

        $idSuc = 1;
        $status ='Registrada';
        $user = $_SESSION['accelog_idempleado'];
       /*----- Total y subtotal -------------*/
       /* $token =explode("/", $productos);
        foreach ($token as $key => $value) {
            $token2 = explode('-',$value);
            if($token2[1]!='undefined' && $token2[2]!=''){
                $subtotal = $token2[1] * $token2[2];
                $total += $subtotal;  
                $subtotal=0;  
            }
        } */
       /* $queryInsert = "INSERT INTO mrp_orden_compra (fecha_pedido,elaborado_por,idSuc,idProveedor,estatus,idAlmacen)";
        $queryInsert.= " VALUES ('".$fechaactual."','".$empleado."','".$idSuc."','".$idProvedor."','".$status."','".$idAlmacen."');"; */
        $queryInsert = "INSERT INTO app_ocompra (id_proveedor,id_usrcompra,fecha,fecha_entrega,activo,subtotal, total,id_almacen) VALUES ";
        $queryInsert .="('".$idProvedor."','".$user."','".$fechaactual."','".$fecha_entrega."','1','".$subTotal."','".$total."','".$idAlmacen."')";
        //echo $queryInsert;
        $idOrCom = $this->queryArray($queryInsert);
        $idOrCom = $idOrCom['insertId'];
        
        $token =explode("/", $productos);
        foreach ($token as $key => $value) {
            $token2 = explode('-',$value);
            if($token2[1]!='undefined' && $token2[2]!=''){
                /*$queryInserProd ="INSERT into mrp_producto_orden_compra (idOrden,cantidad,idUnidad,idProducto,ultCosto)";
                $queryInserProd.=" values('".$idOrCom."','".$token2[1]."','1','".$token2[0]."','".$token2[2]."')"; */
                $queryInserProd = "INSERT into app_ocompra_datos (id_ocompra,id_producto,cantidad,costo) ";
                $queryInserProd .="values ('".$idOrCom."','".$token2[0]."','".$token2[1]."','".$token2[2]."')";
                //echo $queryInserProd;
                $insertaproducto = $this->queryArray($queryInserProd);
            }
        }

         return array('status' => true);
    }
    public function updateOrdenCompra($idOrden,$idAlmacen,$idProvedor,$productos,$fecha_entrega,$subTotal,$total,$user){
        //echo $productos;
        //echo '333XX'.$idAlmacen;
        $queryUpdate = "UPDATE app_ocompra set id_almacen='".$idAlmacen."', fecha_entrega='".$fecha_entrega."',subtotal='".$subTotal."', total='".$total."', id_usrcompra='".$user."', autorizo='".$user."' where id=".$idOrden;
        $resUpdate = $this->queryArray($queryUpdate); 

        $delete = "DELETE from app_ocompra_datos where id_ocompra=".$idOrden;
        $restDel = $this->queryArray($delete);

        $token =explode("/", $productos);
        foreach ($token as $key => $value) {
            $token2 = explode('-',$value);
            if($token2[1]!='undefined' && $token2[2]!=''){
                /*$queryInserProd ="INSERT into mrp_producto_orden_compra (idOrden,cantidad,idUnidad,idProducto,ultCosto)";
                $queryInserProd.=" values('".$idOrCom."','".$token2[1]."','1','".$token2[0]."','".$token2[2]."')"; */
                $queryInserProd = "INSERT into app_ocompra_datos (id_ocompra,id_producto,cantidad,costo) ";
                $queryInserProd .="values ('".$idOrden."','".$token2[0]."','".$token2[1]."','".$token2[2]."')";
                //echo $queryInserProd;
                $insertaproducto = $this->queryArray($queryInserProd);
            }
        }
        $subtotal = 0;
        $total = 0;
        /*----- Total y subtotal -------------*/
        $token =explode("/", $productos);
        foreach ($token as $key => $value) {
            $token2 = explode('-',$value);
            if($token2[1]!='undefined' && $token2[2]!=''){
                $subtotal = $token2[1] * $token2[2];  
 
                $total += $subtotal;  
                $subtotal=0;                                                         
            }
        }
        /*$upTota = "UPDATE app_ocompra set total='".$total."' , total='".$total."' where id=".$idOrden;
        $resUpT = $this->queryArray($upTota); */

         return array('status' => true);

    }
    public function ordenBasicos($idOdenCompra){
       // echo 'rrrr'.$idOdenCompra;
        //$queryOrde = "SELECT o.* , p.razon_social from mrp_orden_compra o, mrp_proveedor p where o.idProveedor=p.idPrv and o.idOrd=".$idOdenCompra;
        $queryOrde = " SELECT o.id, p.razon_social, u.usuario, o.fecha, o.fecha_entrega, o.total ,p.idPrv,o.id_almacen, o.activo,a.nombre as almacenNom, o.id_usrcompra, o.autorizo
                    from app_ocompra o, mrp_proveedor p, accelog_usuarios u, app_almacenes a
                    where o.id_usrcompra=u.idempleado and o.id_almacen=a.id and o.id_proveedor=p.idPrv and o.id=".$idOdenCompra;
                   
        $res = $this->queryArray($queryOrde);
        return $res['rows'];

    }
    public function productosOrden($idOdenCompra){
        
        //$queryProd = "SELECT o.idProducto,o.cantidad,o.ultCosto, p.codigo,p.nombre from mrp_producto p, mrp_producto_orden_compra o where o.idProducto=p.idProducto and idOrden=".$idOdenCompra;
        $queryProd = "SELECT o.id_producto,o.cantidad,o.costo, p.codigo,p.nombre,p.lotes,p.series, p.pedimentos, rec.cantidad as recibidos
                        from app_productos p, app_ocompra_datos o  
                        left join app_recepcion_datos rec on rec.id_producto = o.id_producto and rec.id_oc=o.id_ocompra
                        where o.id_producto=p.id  and o.id_ocompra=".$idOdenCompra;
        $res = $this->queryArray($queryProd);
        return $res['rows'];
    }
    public function produProve($idPrv){
        $query ='SELECT p.id, p.codigo, p.nombre from app_productos p, app_producto_proveedor pr where p.id=pr.id_producto and pr.id_proveedor='.$idPrv;
        //echo 'X'.$query;
        $productos = $this->queryArray($query);
        return $productos['rows'];
    }
    public function agregaMasProd($idProducto,$cantidad,$precio){

        $query = "SELECT * from app_productos where id=".$idProducto;
        $res = $this->queryArray($query);

        return $res['rows'];

    }
    public function recibeOrden($idOrden,$idAlmacen,$idProvedor,$productos,$fecha_entrega,$factura,$observaciones,$facturaImporte,$fecha_factura){
        $costo = 0;
        $importe = 0;
        $tipoTraspaso = 1;
        $completa = 1;
        $fechaactual = date('Y-m-d H:i:s');
        $token = explode("/", $productos);
        $idFactura = 0;
        $inserRecepcion = 'INSERT into app_recepcion(id_oc,id_encargado,observaciones,fecha_recepcion,no_factura,fecha_factura,imp_factura,id_factura)';
        $inserRecepcion .=' VALUES("'.$idOrden.'","'.$_SESSION['accelog_idempleado'].'","'.$observaciones.'","'.$fechaactual.'","'.$factura.'","'.$fecha_factura.'","'.$facturaImporte.'","'.$idFactura.'")';
        /*echo $inserRecepcion;
        exit(); */
        $resRecepInsert = $this->queryArray($inserRecepcion); 
        $idRecepcion = $resRecepInsert['insertId'];

        foreach ($token as $key => $value) {
            $token2 = explode('-',$value);
            if($token2[1]!='undefined' && $token2[2]!=''){
                if($token2[2] < $token2[1]){
                    $completa = 0;
                }

                $inserReceDatos = "INSERT into app_recepcion_datos (id_oc,id_producto,id_recepcion,cantidad,estatus,id_almacen) values ('".$idOrden."','".$token2[0]."','".$idRecepcion."','".$token2[2]."','0','".$idAlmacen."')";
                $inserDatosRecepcion = $this->queryArray($inserReceDatos);

                $importe = $token2[2] * $token2[3];

                $myQueryUnid = "SELECT b.id, b.clave, b.factor as fo, c.clave, c.factor as fd from app_productos a 
                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
                            where a.id=".$token2[0];
                $costunids = $this->queryArray($myQueryUnid);
                $cantidadreal=(($token2[2]*1)*$costunids['rows'][0]['fo'])/$costunids['rows'][0]['fd'];

                $insertMovi = "INSERT into app_inventario_movimientos (id_producto,cantidad,importe,id_almacen_origen,id_almacen_destino,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) values ('".$token2[0]."','".$cantidadreal."','".$importe."','','".$idAlmacen."','".$fechaactual."','".$_SESSION['accelog_idempleado']."','".$tipoTraspaso."','".$token2[3]."','Ingreso Orden ".$idOrden."','2')";
                //echo $insertMovi;
                $resInsert = $this->queryArray($insertMovi);    

            }
        }

        $updateStatus = "UPDATE app_ocompra set activo=4 , observaciones='".$observaciones."' where id=".$idOrden;
        $resUpdateStatus = $this->queryArray($updateStatus);

        return array('status' => true);
       
    }
    public function calculaImpuestos($stringTaxes){
        //echo $stringTaxes.'Z';
        //unset($_SESSION['prueba']);
        //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
        //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';
        $impuestos = array();
        $productos = explode('/', $stringTaxes);

        foreach ($productos as $key => $value) {
            $prod = explode('-', $value);
            if($prod[0]!=''){
                $idProducto = $prod[0];
                $precio = $prod[1];
                $cantidad = $prod[2];
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
                        //echo $valueImpuestos["nombre"].' '.$valueImpuestos["valor"].'='.$producto_impuesto.'<br>';
                        $totalImpestos += $producto_impuesto;
                        $impuestos['cargos']['impuestos'][$valueImpuestos["clave"]] = $impuestos['cargos']['impuestos'][$valueImpuestos["clave"]] + $producto_impuesto;
                        $impuestos['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] = $impuestos['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] + $producto_impuesto;
                }
                //echo 'total='.($subtotal+$producto_impuesto).'<br>';
                $ieps = 0;
            }
            

        }
        
        $impuestos['cargos']['total']= $totalImpestos + $subtotalVenta;
        $impuestos['cargos']['subtotal'] = $subtotalVenta;

        //print_r($impuestos);
        return $impuestos;
        //print_r($_SESSION['prueba']);
        //echo json_encode($_SESSION['prueba']);
        //unset($_SESSION['prueba');
    }
    public function recibidosPreviamente($idOdenCompra){
        
        $query1 = "SELECT * from app_recepcion where id_oc=".$idOdenCompra;
        $res1 = $this->queryArray($query1);

        $query2 = "SELECT id_producto, cantidad from app_recepcion_datos where id_oc=".$idOdenCompra."  and id_recepcion=".$res1['rows'][0]['id'];
        $res2 = $this->queryArray($query2);

        return $res2['rows'];
    }
    public function recepcionesDatosGenerales($idOdenCompra){

        $query1 = "SELECT * from app_recepcion where id_oc=".$idOdenCompra;
        $res1 = $this->queryArray($query1);
        return $res1['rows'];
    }   








}
?>
