<?php

    //Cargar la clase de conexión padre para el modelo
    require_once("models/model_father.php");
    //Cargar los archivos necesarios
    //ini_set("display_errors", 1); error_reporting(E_ALL);

    class ProductosModel extends Model
    {
        function __construct($id = null)
        {
            parent::__construct($id);
        }

        function __destruct()
        {

        }
        
        public static function index()
        {
            return array("status" => true, "registros" => array());
        }

        public static function obtenerDepartamentos()
        {
            $sql = "SELECT id, nombre FROM app_departamento;";
            $deparments = DB::queryArray($sql, array());

            return $deparments;
        }

        public static function obtenerFamilias($departamento) 
        {
            $sql = "SELECT id, nombre FROM app_familia 
                    WHERE id_departamento = '" . $departamento["id_departamento"]. "';";
            $familias = DB::queryArray($sql, array());

            return $familias;
        }
        public static function obtenerLineas($familia) {
            $sql = "SELECT id, nombre FROM app_linea WHERE id_familia = '" . $familia["id_familia"]. "' AND activo=1;";
            $lineas = DB::queryArray($sql, array());

            return $lineas;
        }
        public static function obtenerProductos($request){
            $sucursal = "   SELECT mp.idSuc AS id FROM administracion_usuarios au 
                            INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                            WHERE au.idempleado = " . $request['id_mesero'] . " 
                            LIMIT 1";

            $sucursal = DB::queryArray($sucursal, array());
            $sucursal = $sucursal["registros"][0]["id"];

            $idDeparmento = $request["id_departamento"];
            $idFamilia = $request["id_familia"];
            $idLinea = $request["id_linea"];
            $texto = $request["texto"];
            $condicion = "";

            // Filtra por departamento si existe
                if ($idDeparmento != 0)
                    $condicion = " AND p.departamento=$idDeparmento ";

            // Filtra por familia si existe
                if ($idFamilia != 0)
                    $condicion .= " AND p.familia=$idFamilia ";

            // Filtra por linea si existe
                if ($idLinea != 0)
                    $condicion .= " AND p.linea=$idLinea ";
            // Filtra por nombre si existe si se indica
                if(!empty($texto))
                    $condicion .= ' AND p.nombre LIKE \'%' . $texto . '%\'';

            $sql = "SELECT * FROM app_producto_sucursal LIMIT 1";
            $total = DB::queryArray($sql, array());

            if($total['total'] > 0){
                $sql = "SELECT p.id AS idProducto, p.nombre, 
                        ROUND(p.precio, 2) AS precioventa, 
                        p.ruta_imagen AS imagen, 
                        IF((SELECT COUNT(id) FROM app_producto_material WHERE id_producto = p.id) > 0, 1, 0) materiales, 
                        departamento AS idDep, 
                        f.h_ini AS inicio, 
                        f.h_fin AS fin, 
                        f.dias, 
                        p.formulaieps AS formula
                        FROM app_productos p
                        LEFT JOIN app_campos_foodware f ON p.id=f.id_producto
                        LEFT JOIN app_linea l ON p.linea=l.id
                        LEFT JOIN app_familia fa ON p.familia=fa.id
                        LEFT JOIN app_departamento d ON p.departamento=d.id
                        INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id 
                        AND aps.id_sucursal = ".$sucursal."
                        WHERE p.status = 1 AND tipo_producto != 3 
                        AND tipo_producto != 6 AND tipo_producto != 7
                        AND tipo_producto != 8 " . $condicion . "
                        GROUP BY p.id ORDER BY f.rate DESC";
            } else {          
                $sql = "SELECT p.id AS idProducto, p.nombre, ROUND(p.precio, 2) AS precioventa, p.ruta_imagen AS imagen, 
                        IF((SELECT COUNT(id) FROM app_producto_material WHERE id_producto = p.id) > 0, 1, 0) materiales, departamento AS idDep, f.h_ini AS inicio, f.h_fin AS fin, f.dias, p.formulaieps AS formula
                        FROM app_productos p
                        LEFT JOIN app_campos_foodware f ON p.id=f.id_producto
                        LEFT JOIN app_linea l ON p.linea=l.id
                        LEFT JOIN app_familia fa ON p.familia=fa.id
                        LEFT JOIN app_departamento d ON p.departamento=d.id
                        WHERE p.status = 1 AND tipo_producto != 3 
                        AND tipo_producto != 6 AND tipo_producto != 7
                        AND tipo_producto != 8 " . $condicion . " 
                        GROUP BY p.id ORDER BY f.rate DESC";
            }
            $resultado = DB::queryArray($sql, array());
            
            $index = 0;
            foreach ($resultado["registros"] as $value) {
                if($value["idDep"] == null){
                    $resultado["registros"][$index]["idDep"] = "0";
                }
                $index ++;
                
            }
            return $resultado;

        }

        public static function detalleProducto($request) 
        {
            $sql = "SELECT b.id AS idProducto, b.nombre, a.opcionales, ROUND(b.precio, 2) AS precioventa 
                    FROM app_producto_material a 
                    INNER JOIN app_productos b 
                    ON b.id = a.id_material 
                    WHERE a.id_producto = " . $request["id_producto"];
                    
            $resultado = DB::queryArray($sql, array());

            if($resultado["status"] && $resultado["total"] >= 1){
                $resultado = DB::queryArray($sql, array());
            }else{
                $resultado["status"] = false;
                $resultado["mensaje"] = "No tiene opcionales";
            }
            return $resultado;
        }  

        public static function guardarPedido($idcomanda, $usuario, $idproduct,  $idperson, $opcionales, $extras, $sin, $iddep, 
            $nota_opcional , $nota_extra, $nota_sin, $cantidad,  $id_promocion, $is_promocion = 0, $comentario, $dependencia_promocion = 0) 
        {


            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d H:i:s');

            $sucursal = "   SELECT mp.idSuc AS id FROM administracion_usuarios au 
                            INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                            WHERE au.idempleado = " . $usuario . " 
                            LIMIT 1";

            $sucursal = DB::queryArray($sucursal, array());
            $idSuc = $sucursal["registros"][0]["id"];
                    
                if($is_promocion == 0){
                    $queryproduct = 'SELECT tipo_producto, (CASE WHEN (Select precio from app_precio_sucursal where sucursal = 17 and producto = b.id limit 1) IS NULL THEN ROUND(precio, 2) ELSE ROUND((Select precio from app_precio_sucursal where sucursal = 17 and producto = b.id limit 1), 2) END) as precioventa FROM app_productos b WHERE id = ' . $idproduct;
                    $tipro = DB::queryArray($queryproduct, array());
                    $row = $tipro["registros"];
                    $tipo_producto = $row[0]['tipo_producto'];
                    $precio = $row[0]['precioventa'];
                    /* Impuestos del producto ===================================================================== */
                    $impuestos_comanda = 0;
                    $objeto['id'] = $idproduct;
                    $impuestos = ProductosModel::listar_impuestos($objeto);

                    if ($impuestos['total'] > 0) {
                        foreach ($impuestos['registros'] as $k => $v) {
                            if ($v["clave"] == 'IEPS') {
                                $producto_impuesto = $ieps = (($precio) * $v["valor"] / 100);
                            } else {
                                if ($ieps != 0) {
                                    $producto_impuesto = ((($precio + $ieps)) * $v["valor"] / 100);
                                } else {
                                    $producto_impuesto = (($precio) * $v["valor"] / 100);
                                }
                            }
                            // Precio actualizado
                            $precio += $producto_impuesto;
                            $precio = round($precio, 2);
                            $impuestos_comanda += $producto_impuesto;
                        }
                    }
                    /* FIN Impuestos del producto ================================================================= */
                    // Obtiene los costos de los productos extra si existen
                    if (!empty($extras)) {
                        $sql = 'SELECT ROUND(b.precio, 2) AS precioventa, id FROM app_productos b
                        WHERE id in(' . $extras . ')';
                        $precios_extra = DB::queryArray($sql, array());
                        // Recorre los costos y los agrega al precio
                        foreach ($precios_extra['registros'] as $key => $value) {
                            $objeto['id'] = $value['id'];
                            $impuestos = ProductosModel::listar_impuestos($objeto);
                            if ($impuestos['total'] > 0) {
                                foreach ($impuestos['registros'] as $k => $v) {
                                    if ($v["clave"] == 'IEPS') {
                                        $producto_impuesto = $ieps = (($value['precioventa']) * $v["valor"] / 100);
                                    } else {
                                        if ($ieps != 0) {
                                            $producto_impuesto = ((($value['precioventa'] + $ieps)) * $v["valor"] / 100);
                                        } else {
                                            $producto_impuesto = (($value['precioventa']) * $v["valor"] / 100);
                                        }
                                    }
                                    // Precio actualizado
                                    $precio += $producto_impuesto + $value['precioventa'];
                                    $precio = round($precio, 2);

                                    $impuestos_comanda += $producto_impuesto; 
                                }
                            }
                        }
                    }// ** FIN de precio de extras
                    // Actualiza el total y los impuestos de la comanda
                    $sql = 'UPDATE com_comandas SET total = total + ' . $precio . ' WHERE id = ' . $idcomanda;
                    $precio = DB::queryArray($sql, array());

                    /* Guarda la actividad ============================================================================= */
                    $sql = "INSERT INTO com_actividades (id, empleado, accion, fecha)
                            VALUES (''," . $usuario . ",'Agrega producto', '" . $fecha . "')";
                    $actividad = DB::queryArray($sql, array());
                    /* FIN Guarda la actividad ========================================================================= */

                    // Aumenta el rating del producto
                    $sql = "UPDATE app_campos_foodware SET rate = rate + 1
                            WHERE id_producto = " . $idproduct;
                    $rate = DB::queryArray($sql, array());

                    // Si es Producir producto obtiene los materiales
                    if ($tipo_producto == 5) {
                        $querycompuesto = 'SELECT p.id, p.id_producto AS idProducto, p.cantidad, p.id_unidad AS idUnidad, p.id_material AS idMaterial, l.nombre Nom FROM app_producto_material p INNER JOIN app_productos l 
                            ON p.id_material = l.id WHERE p.id_producto = ' . $idproduct;
                        $productocompuesto = DB::queryArray($querycompuesto, array());

                        // Si no tiene ningun material solamente inserta el registro
                        if (!$productocompuesto['registros']) {
                            //echo "insert 1";
                            
                            $sql = "INSERT INTO com_pedidos (idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, notap) 
                                VALUES('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras', '$sin','$nota_opcional','$nota_extra', '$nota_sin', '$comentario')";
                            $product = DB::queryArray($sql, array());

                            $respuesta = $product;
                           
                        }
                        // Recorre los materiales y checa si hay suficientes para hacer el pedido
                        foreach ($productocompuesto['registros'] as $value) {
                            $stock = 999999;
                            //se obtiene la cantidad del producto en las comandas
                            $querycomanda = 'SELECT count(*) as cantidad_comanda FROM com_pedidos 
                                    WHERE idProducto=' . $idproduct . ' AND status';
                            $cancom = DB::queryArray($querycomanda, array());
                            $row = $cancom["registros"];
                            $cantidadcomandas = $row[0]['cantidad_comanda'];

                            // Valida que se pueda crear el producto
                            if (($cantidadcomandas * $value['cantidad']) >= $stock) {
                                return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
                                exit();
                            } else {
                                //echo "insert 2";
                                
                                $sql = "INSERT INTO com_pedidos (idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, notap) 
                                    VALUES ('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',
                                        '$nota_opcional','$nota_extra', '$nota_sin', '$comentario')";
                                $product = DB::queryArray($sql, array());

                                $respuesta = $product;
                               
                                break;
                            }
                        }
                    }// FIN de producir 
                    else {
                        $stock = 999999;
                        //se obtiene la cantida del producto en las comandas
                        $querycomanda = 'SELECT count(*) as cantidad_comanda FROM com_pedidos 
                                WHERE idProducto = ' . $idproduct . ' AND status';
                        $cancom = DB::queryArray($querycomanda, array());
                        $row = $cancom["registros"];
                        $cantidadcomandas = $row[0]['cantidad_comanda'];
                        if ($cantidadcomandas >= $stock) {
                            return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
                            exit();
                        } else {
                            //echo "insert 3";
                            
                            $sql = "INSERT INTO com_pedidos (idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, notap) 
                                VALUES ('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',       
                                '$nota_opcional','$nota_extra','$nota_sin', '$comentario')";
                            $result = DB::queryArray($sql, array());
                            $respuesta = $result;
                            
                        }
                    }

                }//if principal
                else {
                    //inserta la promocion
                    $sql = "INSERT INTO com_pedidos (idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion) 
                        VALUES('$idcomanda','$idproduct','1','$idperson','0','-1','','', '','','', '', '$id_promocion', '$dependencia_promocion')";
                    $product  = DB::queryArray($sql, array());
                    $respuesta = $product;  
                }
           
           return $respuesta['id_insertado'];
        }  

        public function enviarPedido($request){
            $idcomanda = $request["id_comanda"];
            $usuario = $request["id_mesero"];
            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d H:i:s');

            $sucursal = "   SELECT mp.idSuc AS id FROM administracion_usuarios au 
                            INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                            WHERE au.idempleado = " . $usuario . " 
                            LIMIT 1";

            $sucursal = DB::queryArray($sucursal, array());
            $idSuc = $sucursal["registros"][0]["id"];

            $sql = "SELECT pe.*, p.tipo_producto FROM com_pedidos pe LEFT JOIN app_productos p ON p.id = pe.idproducto
                    WHERE pe.status = '-1' AND pe.origen = 1 AND idcomanda =" . $idcomanda;
            $pedidos = DB::queryArray($sql, array());

            if (!empty($idSuc)) {
                // Obtiene el almacen
                $almacen = "SELECT a.id FROM administracion_usuarios au LEFT JOIN app_almacenes a ON a.id_sucursal = au.idSuc
                            WHERE au.idSuc = " . $idSuc . " AND a.activo = 1 LIMIT 1";
                $almacen = DB::queryArray($almacen, array());
                $row = $almacen["registros"];
                $almacen = $row[0]['id'];
            }else{
                // Obtiene el almacen
                $almacen = "SELECT a.id FROM administracion_usuarios au LEFT JOIN app_almacenes a ON a.id_sucursal = au.idSuc
                            WHERE au.idempleado = " . $usuario . " AND a.activo = 1 LIMIT 1";
                $almacen = DB::queryArray($almacen, array());
                $row = $almacen["registros"];
                $almacen = $row[0]['id'];
            }
            // Valida que exista el almacen
            $almacen = (empty($almacen)) ? 1 : $almacen;

            /* Actualiza el inventario =================================================================== */
            foreach ($pedidos['registros'] as $key => $value) {
                // Obtiene los insumos normales
                $sql = "SELECT p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
                        FROM app_productos p INNER JOIN app_producto_material m ON m.id_material = p.id
                        WHERE m.id_producto = " . $value['idproducto'] ." AND m.opcionales LIKE '%0%'";
                // return $sql;
                $normales =  DB::queryArray($sql, array());
                // Actualiza el inventario por cada insumo
                foreach ($normales['registros'] as $k => $v) {
                    $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
                                tipo_traspaso, costo, referencia)
                            VALUES ('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', '" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
                    $result_opcional = DB::queryArray($sql, array());
                }
                // Opcionales
                if (!empty($value['opcionales'])) {
                    // Filtra solo por los opcionales seleccionados
                    $condicion = (!empty($value['opcionales'])) ? " AND p.id IN(" . $value['opcionales'] . ")" : "";
                    
                    // Obtiene los productos
                    $sql = "SELECT p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
                            FROM app_productos p INNER JOIN app_producto_material m ON m.id_material = p.id
                            WHERE m.id_producto = " . $value['idproducto'] . $condicion;
                    // return $sql;
                    $opcionales = DB::queryArray($sql, array());
                    
                    // Actualiza el inventario por cada producto
                    foreach ($opcionales['registros'] as $k => $v) {
                        $sql = "INSERT INTO app_inventario_movimientos(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,tipo_traspaso, costo, referencia)
                            VALUES('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "','" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
                        $result_opcional = DB::queryArray($sql, array());
                    }
                }
                // Sin
                if (!empty($value['sin'])) {
                    // Excluye los insumos sin del inventario
                    $condicion = (!empty($value['sin'])) ? " AND p.id NOT IN(" . $value['sin'] . ")" : "";
                    
                    // Obtiene los productos
                    $sql = "SELECT p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
                            FROM app_productos p INNER JOIN app_producto_material m ON m.id_material = p.id
                            WHERE m.id_producto = " . $value['idproducto'] . $condicion;
                    // return $sql;
                    $sin = DB::queryArray($sql, array());
                    
                    // Actualiza el inventario por cada producto
                    foreach ($sin['registros'] as $k => $v) {
                        $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,tipo_traspaso, costo, referencia)
                            VALUES('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', '" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
                        $result_opcional = DB::queryArray($sql, array());
                    }
                }
                // Extras
                if (!empty($value['adicionales'])) {
                    $sql = "SELECT p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
                            FROM app_productos p INNER JOIN app_producto_material m ON m.id_material = p.id
                            WHERE p.id IN(" . $value['adicionales'] . ") AND m.id_producto = " . $value['idproducto'];
                    $adicionales = DB::queryArray($sql, array());

                    // Actualiza el inventario por cada producto
                    foreach ($adicionales['registros'] as $k => $v) {
                        $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,tipo_traspaso, costo, referencia)
                            VALUES ('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', '" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
                        $result_adicionales = DB::queryArray($sql, array());
                    }
                }
                // Complementos
                if (!empty($value['complementos'])) {
                    $sql = "SELECT p.id, c.cantidad, ROUND(p.precio, 2) AS precio, c.cantidad AS importe, p.formulaieps AS formula
                            FROM com_complementos c LEFT JOIN app_productos p ON p.id = c.id_producto
                            LEFT JOIN app_costos_proveedor pro ON pro.id_producto = p.id
                            WHERE c.id_producto IN(" . $value['complementos'] . ")";
                    $complementos = DB::queryArray($sql, array());

                // Actualiza el inventario por cada producto
                    foreach ($complementos['registros'] as $k => $v) {
                        $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado, tipo_traspaso, costo, referencia)
                            VALUES ('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', '" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
                        $result_adicionales = DB::queryArray($sql, array());
                    }
                }
                // Receta(Crea una entrada al incentario si es receta)
                if ($value['tipo_producto'] == 5) {
                    $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_destino, fecha, id_empleado,
                                tipo_traspaso, costo, referencia)
                            VALUES ('" . $value['idproducto'] . "', '" . $value['cantidad'] . "', '" . $value['cantidad'] . "', '" . $almacen . "', '" . $fecha . "', '" . $usuario . "', 1, '" . $value['cantidad'] . "', 'Pedido " . $value['id'] . "')";
                    $result_receta = DB::queryArray($sql, array());
                }
               /* $value['sucursal'] = $sucursal;
                // Kit
                    if ($value['tipo_producto'] == 6) {
                        $result = $this -> actualizar_inventario_kit($value);
                    }
                */

                // Combo
                    if ($value['tipo_producto'] == 7) {
                       // $precio = $value['precio'];
                       // $objeto['id'] = $value['idproducto'];
                       // $impuestos = ProductosModel::listar_impuestos($objeto);
                        $value["usuario"] = $usuario;
                        $value["sucursal"] = $idSuc;
                        $result = ProductosModel::actualizar_inventario_combo($value);
                    }
            }// fin foreach
            /* FIN Actualiza el inventario ========================================================================= */
            //** Guarda la actividad
            $sql = "INSERT INTO com_actividades (id, empleado, accion, fecha)
                    VALUES (''," . $usuario . ",'Procesa pedidos', '" . $fecha . "')";
            $actividad = DB::queryArray($sql, array());
            // Actuliza el estado del pedido para indicar que se dio de alta (status='0')
            $sql = "UPDATE com_pedidos SET status = '0' WHERE status = '-1' AND idcomanda = " . $idcomanda;
            $result = DB::queryArray($sql, array());

            // Consulta el tipo de operacion y lo devuelve
            $sql = "SELECT tipo_operacion FROM com_configuracion";
            $result = DB::queryArray($sql, array());
            return $result;
        }

        public function listarProductos($request){
            $idcomanda = $request["id_comanda"];
            $usuario = $request["id_mesero"];
            $request['lista_comensales'] = json_decode($request['lista_comensales'], true);

            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d H:i:s');

            $sucursal = " SELECT mp.idSuc AS id FROM administracion_usuarios au 
                        INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                        WHERE au.idempleado = " . $usuario . " LIMIT 1";

            $sucursal = DB::queryArray($sucursal, array());
            $idSuc = $sucursal["registros"][0]["id"];

            $resultado['status'] = false;

            $arrayComensales = array();
            

            for ($i=0; $i < count($request['lista_comensales']) ; $i++) { 
                $array = array();
                
                $idComensal = $request["lista_comensales"][$i];
                $sql = "SELECT a.id, a.idproducto, SUM(a.cantidad) AS cantidad, 
                        b.nombre, 
                        (CASE WHEN (SELECT precio FROM app_precio_sucursal WHERE sucursal = ".$idSuc." and producto = a.idproducto limit 1) IS NULL THEN ROUND(b.precio, 2)ELSE ROUND((Select precio from app_precio_sucursal where sucursal = ".$idSuc." and producto = a.idproducto limit 1), 2) END) as precio, 
                        opcionales, 
                        adicionales,  
                        nota_sin, 
                        nota_extra, 
                        nota_opcional, 
                        sin, a.status, 
                        a.complementos, 
                        a.id_promocion, 
                        (CASE a.id_promocion WHEN 0 THEN 'producto' ELSE a.id END) as tipin 
                        FROM com_pedidos a 
                        LEFT JOIN app_productos b ON b.id = a.idproducto 
                        WHERE a.dependencia_promocion = 0 AND cantidad > 0 AND origen = 1 AND a.npersona = ".$idComensal." AND a.idcomanda = " . $idcomanda . " AND a.status != -1  GROUP BY tipin, status, a.idproducto, a.opcionales, a.adicionales, a.complementos;";

                $productsComanda = DB::queryArray($sql, array());
                //$array = Array("registros");
                $contador = 0;

                // Recorre los registros para formar una cadena de lo opcionales, extra y sin  $pedidos as $key => $value
                foreach ($productsComanda['registros'] as $llave => $value) {
                    /* Impuestos del producto ======================================================================= */
                    $precio = $value['precio'];
                    $objeto['id'] = $value['idproducto'];
                    $impuestos = ProductosModel::listar_impuestos($objeto);

                    if ($impuestos['total'] > 0) {
                        foreach ($impuestos['registros'] as $k => $v) {
                            if ($v["clave"] == 'IEPS') {
                                $producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
                            } else {
                                if ($ieps != 0) {
                                    $producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
                                } else {
                                    $producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
                                }
                            }
                            // Precio actualizado
                            $precio += $producto_impuesto;
                            $precio = round($precio, 2);
                        }
                    }
                    /* FIN Impuestos del producto =================================================================== */
                    $items = "";
                    // Opcionales
                    if ($value['opcionales'] != "") {
                        $sql = "SELECT CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre FROM app_productos 
                                WHERE id IN(" . $value['opcionales'] . ")";
                        $itemsProduct = DB::queryArray($sql, array());
         
                        if (count($itemsProduct['registro']) > 0){
                            if($value['nota_opcional'] != ''){
                                $items .= "(" . $row[0]['nombre'] . ",".$value[0]['nota_opcional'].")";
                            } else {
                                $items .= "(" . $row[0]['nombre'] . ")";
                            }
                        } else if($value['nota_opcional'] != '') {
                            $items .= "(" . $value['nota_opcional'] . ")";
                        }

                    } else if($value['nota_opcional'] != '') {
                        $items .= "(" . $value['nota_opcional'] . ")";
                    }

                    // Adicionales
                    if ($value['adicionales'] != "") {
                    $sql = "SELECT CONCAT('Extra: ',GROUP_CONCAT(nombre)) nombre, id FROM app_productos 
                            WHERE id in(" . $value['adicionales'] . ")";
                    $itemsProduct = DB::queryArray($sql, array());
                    
                    if (count($itemsProduct['registros']) > 0) {
                        foreach ($itemsProduct['registros'] as $key5 => $value5) {
                            if($value['nota_extra'] != ''){
                                $items .= "(" . $value5['nombre'] . ",".$value['nota_extra'].")";
                            } else {
                                $items .= "(" . $value5['nombre'] . ")";
                            }
                        }
                    }else if($value['nota_extra'] != '') {
                        $items .= "(" . $value['nota_extra'] . ")";
                    }
                    
                    foreach ($itemsProduct['registros'] as $k => $v) {
                    /* Impuestos del producto
                    ============================================================================= */
                        $objeto['id'] = $v['id'];

                        $impuestos = ProductosModel::listar_impuestos($objeto);
                        if ($impuestos['total'] > 0) {
                            foreach ($impuestos['registros'] as $kk => $vv) {
                                if ($vv["clave"] == 'IEPS') {
                                    $producto_impuesto = $ieps = (($vv["precio"]) * $vv["valor"] / 100);
                                } else {
                                    if ($ieps != 0) {
                                        $producto_impuesto = ((($vv["precio"] + $ieps)) * $vv["valor"] / 100);
                                    } else {
                                        $producto_impuesto = (($vv["precio"]) * $vv["valor"] / 100);
                                    }
                                }

                                // Precio actualizado
                                $precio += $producto_impuesto + $vv["precio"];
                                $precio = round($precio, 2);
                            }
                        }
                    /* FIN Impuestos del producto
                    ============================================================================= */    
                    }

                    } else if($value['nota_extra'] != '') {
                        $items .= "(" . $value['nota_extra'] . ")";
                    }

                    // Sin
                    if ($value['sin'] != "") {
                        $sql = "SELECT CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre FROM app_productos 
                                WHERE id in(" . $value['sin'] . ")";
                        $itemsProduct = DB::queryArray($sql, array());

                        if (count($itemsProduct['registro']) > 0){
                            if($value['nota_sin'] != ''){
                                $items .= "(" . $row[0]['nombre'] . ",".$value['nota_sin'].")";
                            } else {
                                $items .= "(" . $row[0]['nombre'] . ")";
                            }
                        } else if($value['nota_opcional'] != '') {
                            $items .= "(" . $value['nota_sin'] . ")";
                        }
                    } else if($value['nota_opcional'] != '') {
                        $items .= "(" . $value['nota_sin'] . ")";
                    }


                    $array[$contador] = array(  'id' => $value['id'], 
                                                'idproducto' => $value['idproducto'], 
                                                'status' => $value['status'], 
                                                'cantidad' => $value['cantidad'], 
                                                'nombre' => $value['nombre'] . " $items", 
                                                'precio' => $precio."", 
                                                'complementos' => $value['complementos'], 
                                                'id_promocion' => $value['id_promocion']);

                $array[$contador]['extra'] = $items;
                $contador++;

                }// FIN foreach
            $arrayComensales[$idComensal] = $array;

            }// FIN for comensales 
            $resultado['status'] = true;
            $resultado['registros'][] = $arrayComensales;
            return $resultado;

        }

        public function listarCombos($request){
            //$idcomanda = $request["id_comanda"];
            $usuario = $request["id_mesero"];
            $dia = $request["dia"];
            $hora = $request["hora"];
          
            $sucursal = "   SELECT mp.idSuc AS id FROM administracion_usuarios au 
                            INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                            WHERE au.idempleado = " . $usuario . " 
                            LIMIT 1";

            $sucursal = DB::queryArray($sucursal, array());
            $idSuc = $sucursal["registros"][0]["id"];

            $sql = "SELECT * FROM app_producto_sucursal LIMIT 1";
            $total = DB::queryArray($sql, array());
            if($total['total'] > 0){
                /*// Filtra por el ID del producto si existe
                $condicion .= (!empty($objeto['id']))?' AND c.id = '.$objeto['id']:'';
                // Filtra por el status del combo
                $condicion.=(!empty($objeto['status']))?' AND c.status = '.$objeto['status']:' AND c.status = 1';   
                // Ordena la consulta si existe
                $condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY c.id DESC';*/
                
                $sql = "SELECT c.id AS id_combo, c.nombre, p.codigo, p.precio, c.dias, c.inicio, c.fin, p.costo_servicio AS costo, 
                        p.ruta_imagen AS imagen, departamento AS id_departamento
                        FROM com_combos c
                        LEFT JOIN app_productos p ON p.id = c.id
                        INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$idSuc."
                        WHERE 1 = 1 
                        AND dias like '%".$dia."%' 
                        AND '".$hora."' between inicio and fin ". $condicion;
            }else {
                /*// Filtra por el ID del producto si existe
                $condicion .= (!empty($objeto['id']))?' AND c.id = '.$objeto['id']:'';
                // Filtra por el status del combo
                $condicion.=(!empty($objeto['status']))?' AND c.status = '.$objeto['status']:' AND c.status = 1';     
                 // Ordena la consulta si existe
                $condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY c.id DESC';*/                
                $sql = "SELECT c.id AS id_combo, c.nombre, p.codigo, p.precio, c.dias, c.inicio, c.fin, p.costo_servicio AS costo,
                        p.ruta_imagen AS imagen, departamento AS id_departamento
                        FROM com_combos c
                        LEFT JOIN app_productos p ON p.id = c.id
                        WHERE 1 = 1 AND dias like '%".$dia."%' AND '".$hora."' between inicio and fin ". $condicion;
                // return $sql;
            }
            $result = DB::queryArray($sql, array());
        
            return $result;
        }

        public function listarProductosPromos($request){
            $condicion = "";
            // Filtra por el ID del producto si existe
            $condicion .= (!empty($request['id']))?' AND p.id = '.$request['id']:'';
            // Filtra por el ID del kit si existe
            $condicion .= (!empty($request['id_kit']))?' AND k.id_kit = '.$request['id_kit']:'';
            // Filtra por el ID del kit si existe
            $condicion .= (!empty($request['id_combo']))?' AND c.id_combo = '.$request['id_combo']:'';
            // Filtra por promocion si existe
            $condicion .= (!empty($request['id_promocion']))?' AND pro.id_promocion = '.$request['id_promocion']:'';
            // Filtra por tipo
            $condicion .= (!empty($request['tipo']))?' AND p.tipo_producto = '.$request['tipo']:'';
            // Ordena la consulta si existe
            $condicion .= (!empty($request['orden']))?' ORDER BY '.$request['orden']:'';     
            // Agrupa la consulta si existe, default ID
            $condicion .= (!empty($request['agrupar']))?' GROUP BY '.$request['agrupar']:' GROUP BY p.id';

            //$idcomanda = $request["id_comanda"];
            $usuario = $request["id_mesero"];
            $sucursal = "   SELECT mp.idSuc AS id FROM administracion_usuarios au 
                            INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                            WHERE au.idempleado = " . $usuario . " 
                            LIMIT 1";

            $sucursal = DB::queryArray($sucursal, array());
            $idSuc = $sucursal["registros"][0]["id"];

            $sql = "SELECT * FROM app_producto_sucursal LIMIT 1";
            $total = DB::queryArray($sql, array());
            if($total['total'] > 0){
                $sql = "SELECT p.id AS idProducto, 
                        p.nombre, p.costo_servicio AS costo, 
                        p.ruta_imagen AS imagen, 
                        p.id_unidad_compra AS idunidadCompra, 
                        p.id_unidad_venta AS idunidad, 
                        (SELECT nombre FROM app_unidades_medida uni WHERE uni.id=p.id_unidad_venta) AS unidad, 
                        u.factor, p.tipo_producto, 
                        ROUND(p.precio, 2) AS precio, 
                        p.codigo, 
                        IF(p.linea > 0, CONCAT('l-', p.linea), 
                            IF(p.familia > 0, CONCAT('f-', p.familia), 
                                IF(p.departamento > 0, CONCAT('d-', p.departamento), '#')
                            )
                        ) AS parent, 
                        p.id AS id,  
                        CONCAT(p.nombre, ' $', ROUND(p.precio, 2)) AS text, 
                        'fa fa-cutlery' AS icon, 
                        k.cantidad,
                        IF((SELECT COUNT(id) FROM app_producto_material WHERE id_producto = p.id) > 0, 1, 0) materiales, departamento AS id_departamento, 
                        c.grupo, c.cantidad_grupo, pro.recibir, pro.comprar
                        FROM app_productos p
                        LEFT JOIN app_campos_foodware f ON p.id = f.id_producto
                        LEFT JOIN com_promocionesXproductos pro ON pro.id_producto = p.id
                        LEFT JOIN app_unidades_medida u ON u.id = p.id_unidad_compra
                        LEFT JOIN app_linea l ON p.linea = l.id
                        LEFT JOIN app_familia fa ON p.familia = fa.id
                        LEFT JOIN app_departamento d ON p.departamento = d.id
                        LEFT JOIN com_kitsXproductos k ON k.id_producto = p.id
                        LEFT JOIN com_combosXproductos c ON c.id_producto = p.id
                        INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$idSuc."
                        WHERE p.status = 1 and p.tipo_producto != 3".$condicion;
            }else{
                $sql = "SELECT p.id AS idProducto, p.nombre, p.costo_servicio AS costo, p.ruta_imagen AS imagen, 
                        p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
                        (SELECT nombre FROM app_unidades_medida uni WHERE uni.id=p.id_unidad_venta) AS unidad, 
                        u.factor, p.tipo_producto, ROUND(p.precio, 2) AS precio, p.codigo, 
                        IF(p.linea > 0, CONCAT('l-', p.linea), 
                            IF(p.familia > 0, CONCAT('f-', p.familia), 
                                IF(p.departamento > 0, CONCAT('d-', p.departamento), '#')
                            )
                        ) AS parent, 
                        p.id AS id,  
                        CONCAT(p.nombre, ' $', ROUND(p.precio, 2)) AS text, 
                        'fa fa-cutlery' AS icon, 
                        k.cantidad,
                        IF((SELECT COUNT(id) FROM app_producto_material WHERE id_producto = p.id) > 0, 1, 0) materiales, 
                        departamento AS id_departamento, c.grupo, c.cantidad_grupo, pro.recibir, pro.comprar,
                        (CASE WHEN ids_normales LIKE CONCAT('%',(select ids_insumos from com_recetas where id = p.id),'%') THEN 1 ELSE 0 END) ins, (CASE WHEN ids_normales LIKE CONCAT('%',(select ids_insumos_preparados from com_recetas where id = p.id),'%') THEN 1 ELSE 0 END) inse 
                        FROM app_productos p
                        LEFT JOIN app_campos_foodware f ON p.id = f.id_producto
                        LEFT JOIN com_promocionesXproductos pro ON pro.id_producto = p.id
                        LEFT JOIN app_unidades_medida u ON u.id = p.id_unidad_compra
                        LEFT JOIN app_linea l ON p.linea = l.id
                        LEFT JOIN app_familia fa ON p.familia = fa.id
                        LEFT JOIN app_departamento d ON p.departamento = d.id
                        LEFT JOIN com_kitsXproductos k ON k.id_producto = p.id
                        LEFT JOIN com_combosXproductos c ON  c.id_producto = p.id
                        LEFT JOIN com_recetas r ON r.id = p.id 
                        WHERE p.status = 1 and p.tipo_producto != 3". $condicion;
            }
            //echo $sql;
            $result = DB::queryArray($sql, array());
            return $result;

        }

        public function guardarPedidoCombo($idpedido, $idcomanda, $idproduct,  $idperson, $opcionales, $extras, $sin, $iddep, $nota_opcional,$nota_extra, $nota_sin, $cantidad){

            $sql = "INSERT INTO com_pedidos_combo(id_pedido, id_comanda, id_producto, persona, status, opcionales, extras, sin, nota_opcional, nota_extra, nota_sin, cantidad_pedidos)
                VALUES ('$idpedido', '$idcomanda', '$idproduct', '$idperson', -1, '$opcionales', '$extras', '$sin', '$nota_opcional',
                  '$nota_extra',  '$nota_sin',  $cantidad)";
            $result = DB::queryArray($sql, array());


            return $result;    
        }

        public function listarPromociones($request){
            $usuario = $request["id_mesero"];
            $dia = $request["dia"];
            $hora = $request["hora"];

            $condicion = "";
            // Filtra por el ID del producto si existe
            $condicion .= (!empty($objeto['id']))?' AND id = '.$objeto['id']:'';
            // Filtra por el status del combo
            $condicion.=(!empty($objeto['status']))?' AND status = '.$objeto['status']:' AND status = 1';
            // Filtra por nombre si existe si se indica
            $condicion .= (!empty($objeto['texto'])) ? ' AND nombre LIKE \'%' . $objeto['texto'] . '%\'' : '';
            // Ordena la consulta si existe
            $condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY id DESC';
            
            $sql = "SELECT id AS id_promocion, nombre, tipo, cantidad, cantidad_descuento, inicio, fin, precio_fijo 
                    FROM com_promociones
                    WHERE 1 = 1 
                    AND dias like '%".$dia."%' AND '".$hora."' between inicio and fin ". $condicion; 
                    //print_r($sql);
            // return $sql;
            $result = DB::queryArray($sql, array());

            //print_r($sql);
            return $result;
        }


        public function guardarPedidoPromocion($idproduct, $idperson, $idcomanda, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin, $id_promocion, $dependencia_promocion){

            $queryproduct ='SELECT tipo_producto, ROUND(b.precio, 2) AS precioventa
                            FROM app_productos b
                            WHERE id = ' . $idproduct;
            $tipro = DB::queryArray($queryproduct, array());               
            $row = $tipro["registros"];
            $tipo_producto = $row[0]['tipo_producto'];
            $precio = $row[0]['precioventa'];
            /* Impuestos del producto ===================================================================== */
            $impuestos_comanda = 0;
            $objeto['id'] = $idproduct;
            $impuestos = ProductosModel::listar_impuestos($objeto);

            if ($impuestos['total'] > 0) {
                foreach ($impuestos['registros'] as $k => $v) {
                    if ($v["clave"] == 'IEPS') {
                        $producto_impuesto = $ieps = (($precio) * $v["valor"] / 100);
                    } else {
                        if ($ieps != 0) {
                            $producto_impuesto = ((($precio + $ieps)) * $v["valor"] / 100);
                        } else {
                            $producto_impuesto = (($precio) * $v["valor"] / 100);
                        }
                    }
                    // Precio actualizado
                    $precio += $producto_impuesto;
                    $precio = round($precio, 2);
                    $impuestos_comanda += $producto_impuesto;
                }
            }
            /* FIN Impuestos del producto ================================================================= */
            // Obtiene los costos de los productos extra si existen
            if (!empty($extras)) {
                $sql = 'SELECT ROUND(b.precio, 2) AS precioventa, id FROM app_productos b
                WHERE id in(' . $extras . ')';
                $precios_extra = DB::queryArray($sql, array());
                // Recorre los costos y los agrega al precio
                foreach ($precios_extra['registros'] as $key => $value) {
                    $objeto['id'] = $value['id'];
                    $impuestos = ProductosModel::listar_impuestos($objeto);
                    if ($impuestos['total'] > 0) {
                        foreach ($impuestos['registros'] as $k => $v) {
                            if ($v["clave"] == 'IEPS') {
                                $producto_impuesto = $ieps = (($value['precioventa']) * $v["valor"] / 100);
                            } else {
                                if ($ieps != 0) {
                                    $producto_impuesto = ((($value['precioventa'] + $ieps)) * $v["valor"] / 100);
                                } else {
                                    $producto_impuesto = (($value['precioventa']) * $v["valor"] / 100);
                                }
                            }
                            // Precio actualizado
                            $precio += $producto_impuesto + $value['precioventa'];
                            $precio = round($precio, 2);

                            $impuestos_comanda += $producto_impuesto; 
                        }
                    }
                }
            }// ** FIN de precio de extras
            // Actualiza el total y los impuestos de la comanda
            $sql = 'UPDATE com_comandas SET total = total + ' . $precio . ' WHERE id = ' . $idcomanda;
            $precio = DB::queryArray($sql, array());

            /* Guarda la actividad ============================================================================= */
            $sql = "INSERT INTO com_actividades (id, empleado, accion, fecha)
                    VALUES (''," . $usuario . ",'Agrega producto', '" . $fecha . "')";
            $actividad = DB::queryArray($sql, array());
            /* FIN Guarda la actividad ========================================================================= */

            // Aumenta el rating del producto
            $sql = "UPDATE app_campos_foodware SET rate = rate + 1
                    WHERE id_producto = " . $idproduct;
            $rate = DB::queryArray($sql, array());

            // Si es Producir producto obtiene los materiales
            if ($tipo_producto == 5) {
                $querycompuesto = 'SELECT p.id, p.id_producto AS idProducto, p.cantidad, p.id_unidad AS idUnidad, p.id_material AS idMaterial, l.nombre Nom FROM app_producto_material p INNER JOIN app_productos l 
                    ON p.id_material = l.id WHERE p.id_producto = ' . $idproduct;
                $productocompuesto = DB::queryArray($querycompuesto, array());

                // Si no tiene ningun material solamente inserta el registro
                if (!$productocompuesto['registros']) {
                    //echo "insert 1";
                    $sql = "INSERT INTO com_pedidos (idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion) 
                            VALUES('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras', '$sin','$nota_opcional','$nota_extra', '$nota_sin', '0', 
                            '$dependencia_promocion')";
                    $product = DB::queryArray($sql, array());

                    $respuesta = $product;
                   
                }
                // Recorre los materiales y checa si hay suficientes para hacer el pedido
                foreach ($productocompuesto['registros'] as $value) {
                    $stock = 999999;
                    //se obtiene la cantidad del producto en las comandas
                    $querycomanda = 'SELECT count(*) as cantidad_comanda FROM com_pedidos 
                            WHERE idProducto=' . $idproduct . ' AND status';
                    $cancom = DB::queryArray($querycomanda, array());
                    $row = $cancom["registros"];
                    $cantidadcomandas = $row[0]['cantidad_comanda'];

                    // Valida que se pueda crear el producto
                    if (($cantidadcomandas * $value['cantidad']) >= $stock) {
                        return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
                        exit();
                    } else {
                        //echo "insert 2";
                        $sql = "INSERT INTO com_pedidos (idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion) 
                            VALUES('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras', '$sin','$nota_opcional','$nota_extra', '$nota_sin', '0', 
                            '$dependencia_promocion')";
                        $product = DB::queryArray($sql, array());

                        $respuesta = $product;
                       
                        break;
                    }
                }
            }// FIN de producir 
            else {
                $stock = 999999;
                //se obtiene la cantidad del producto en las comandas
                $querycomanda = 'SELECT count(*) as cantidad_comanda FROM com_pedidos 
                        WHERE idProducto = ' . $idproduct . ' AND status';
                $cancom = DB::queryArray($querycomanda, array());
                $row = $cancom["registros"];
                $cantidadcomandas = $row[0]['cantidad_comanda'];
                if ($cantidadcomandas >= $stock) {
                    return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
                    exit();
                } else {
                    //echo "insert 3";
                    $sql = "INSERT INTO com_pedidos (idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion) 
                            VALUES('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras', '$sin','$nota_opcional','$nota_extra', '$nota_sin', '0', 
                            '$dependencia_promocion')";
                    $result = DB::queryArray($sql, array());
                    $respuesta = $result;
                    
                }
            }

            return $result['id_insertado'];    
        }

        public function listar_impuestos($objeto) {
            $orden = ($objeto['formula'] == 2) ? ' ASC' : ' DESC';

            $sql = "SELECT p.id, p.precio, i.valor, i.clave, pi.formula, i.nombre
                    FROM app_impuesto i, app_productos p 
                    LEFT JOIN app_producto_impuesto pi ON p.id = pi.id_producto 
                    WHERE p.id = " . $objeto['id'] . " AND i.id = pi.id_impuesto 
                    ORDER BY pi.id_impuesto " . $orden;
            // return $sql;
            $result = DB::queryArray($sql, array());

            return $result;
        } 

        function get_promocion($id_promocion) {
            $sql = "SELECT nombre, tipo, cantidad, cantidad_descuento, descuento, precio_fijo
                    FROM com_promociones 
                    WHERE id = " . $id_promocion . " AND status = 1";
            // return $sql;
            $promocion = DB::queryArray($sql, array());

            return $promocion['registros'][0];
        }

        function get_promociones($id_dependencia, $id_promocion) {
            $sql = "SELECT a.id, (CASE a.dependencia_promocion WHEN 0 THEN '0' ELSE c.comprar END) as comprar, c.recibir, a.idproducto, SUM(a.cantidad) AS cantidad, b.nombre, 
                    ROUND(b.precio, 2) AS precio, opcionales, adicionales, sin, a.status, 
                    a.complementos, a.id_promocion 
                    FROM com_pedidos a 
                    LEFT JOIN app_productos b ON b.id = a.idproducto 
                    LEFT JOIN com_promocionesXproductos c on a.idproducto = c.id_producto AND c.id_promocion = ".$id_promocion."
                    WHERE a.dependencia_promocion = ".$id_dependencia." 
                    AND cantidad > 0
                    GROUP BY status, a.id, a.opcionales, a.adicionales, a.complementos
                    ORDER BY comprar desc, b.precio desc, b.id asc;";
            //print_r($sql); exit();
            // return $sql;
            $productsComanda = DB::queryArray($sql, array());
            $array = Array("registros");

            $contador = 0;

            // Recorre los registros para formar una cadena de lo opcionales, extra y sin
            foreach ($productsComanda['registros'] as $value) {
                /* Impuestos del producto
                 ============================================================================= */

                $precio = $value['precio'];
                $objeto['id'] = $value['idproducto'];

                $impuestos = ProductosModel::listar_impuestos($objeto);
                if ($impuestos['total'] > 0) {
                    foreach ($impuestos['registros'] as $k => $v) {
                        if ($v["clave"] == 'IEPS') {
                            $producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
                        } else {
                            if ($ieps != 0) {
                                $producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
                            } else {
                                $producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
                            }
                        }

                        // Precio actualizado
                        $precio += $producto_impuesto;
                        $precio = round($precio, 2);
                    }
                }

                /* FIN Impuestos del producto
                 ============================================================================= */

                $items = "";

                // Opcionales
                 if ($value['opcionales'] != "") {
                    $sql = "SELECT CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
                            FROM app_productos 
                            WHERE id IN(" . $value['opcionales'] . ")";
                    $itemsProduct = DB::queryArray($sql, array());

                    if (count($itemsProduct['registro']) > 0)
                        $items .= "(" . $row['nombre'] . ")";
                }

                // Adicionales
                if ($value['adicionales'] != "") {
                    $sql = "SELECT  CONCAT('Extra: ',GROUP_CONCAT(nombre)) nombre, id
                            FROM app_productos 
                            WHERE id in(" . $value['adicionales'] . ")";
                    $itemsProduct = DB::queryArray($sql, array());

                    foreach ($itemsProduct['registros'] as $k => $v) {
                    /* Impuestos del producto
                    ============================================================================= */
                        $objeto['id'] = $v['id'];

                        $impuestos = ProductosModel::listar_impuestos($objeto);
                        if ($impuestos['total'] > 0) {
                            foreach ($impuestos['registros'] as $kk => $vv) {
                                if ($vv["clave"] == 'IEPS') {
                                    $producto_impuesto = $ieps = (($vv["precio"]) * $vv["valor"] / 100);
                                } else {
                                    if ($ieps != 0) {
                                        $producto_impuesto = ((($vv["precio"] + $ieps)) * $vv["valor"] / 100);
                                    } else {
                                        $producto_impuesto = (($vv["precio"]) * $vv["valor"] / 100);
                                    }
                                }

                            // Precio actualizado
                                $precio += $producto_impuesto + $vv["precio"];
                                $precio = round($precio, 2);
                            }
                        }

                    /* FIN Impuestos del producto
                    ============================================================================= */

                        $items .= "(" . $v['nombre'] . ")";
                    }
                }

                // Sin
                if ($value['sin'] != "") {
                    $sql = "SELECT CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
                            FROM app_productos 
                            WHERE id in(" . $value['sin'] . ")";
                    $itemsProduct = DB::queryArray($sql, array());
                    if (count($itemsProduct['registros']) > 0)
                        $items .= "(" . $row['nombre'] . ")";
                }
                $array['registros'][$contador] = Array('id' => $value['id'], 'idproducto' => $value['idproducto'], 'status' => $value['status'], 'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 'precio' => $precio, 'complementos' => $value['complementos'], 'id_promocion' => $value['id_promocion'], 'recibir' => $value['recibir'], 'comprar' => $value['comprar']);
                $contador++;
            }

            return $array;
        }

        public function actualizar_inventario_combo($objeto){

            $usuario = $objeto["id_mesero"];

            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d H:i:s');


            $sql = "SELECT pe.*, p.tipo_producto, p.departamento, (1 * ROUND(p.precio, 2)) AS importe, p.precio
                    FROM com_pedidos_combo pe
                    LEFT JOIN app_productos p ON p.id = pe.id_producto
                    WHERE id_pedido = " . $objeto['id']." AND pe.status = -1";
            $result['pedidos']['result_opcionales'] = $sql;
            $pedidos = DB::queryArray($sql, array());

            if (!empty($objeto['sucursal'])) {
                $almacen = "SELECT a.id
                            FROM administracion_usuarios au
                            LEFT JOIN app_almacenes a ON a.id_sucursal = au.idSuc
                            WHERE au.idSuc = " . $objeto['sucursal'] . " AND a.activo = 1 LIMIT 1";
                $almacen = DB::queryArray($almacen, array());
                $almacen = $almacen['registros'][0]['id'];
            } else {
                // Obtiene el almacen
                $almacen = "SELECT a.id
                            FROM administracion_usuarios au
                            LEFT JOIN app_almacenes a ON a.id_sucursal = au.idSuc
                            WHERE au.idempleado = " . $usuario . " LIMIT 1";
                $almacen = DB::queryArray($almacen, array());
                $almacen = $almacen['registros'][0]['id'];
            }

            // Valida que exista el almacen
            $almacen = (empty($almacen)) ? 1 : $almacen;

            foreach ($pedidos['registros'] as $key => $value) {
                $sql = "INSERT INTO com_pedidos (id, idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, nota_opcional, nota_extra, nota_sin, origen) 
                    VALUES  (null, ".$value['id_comanda'].", ".$value['id_producto'].", '".$value['cantidad_pedidos']."', ".$value['persona'].", '".$value['departamento']."', '0', '".$value['opcionales']."', '".$value['extras']."', '".$value['sin']."', '".$value['nota_opcional']."','".$value['nota_extra']."', 
                        '".$value['nota_sin']."', 2)";
                $product = DB::queryArray($sql, array());

                /* Actualiza el inventario ============================================================= */
        
                $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado, tipo_traspaso, costo, referencia)
                        VALUES ('" . $value['id_producto'] . "', '1', '" . $value['importe'] . "', '" . $almacen . "', '" . $fecha . "', '" . $usuario . "', 0, 
                        '" . $value['precio'] . "', 'Pedido " . $value['id'] . "')";
                $result_inventario = DB::queryArray($sql, array());

                // Opcionales
                if (!empty($value['opcionales'])) {
                    // Filtra solo por los opcionales seleccionados
                    $condicion = (!empty($value['opcionales'])) ? " AND p.id IN(" . $value['opcionales'] . ")" : "";
                
                    // Obtiene los productos
                    $sql = "SELECT p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
                            FROM app_productos p
                            INNER JOIN app_producto_material m ON m.id_material = p.id
                            WHERE m.id_producto = " . $value['id_producto'] . $condicion;
                    // return $sql;
                    $opcionales = DB::queryArray($sql, array());
                
                    // Actualiza el inventario por cada producto
                    foreach ($opcionales['rows'] as $k => $v) {
                        $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado, tipo_traspaso, costo, referencia)
                            VALUES ('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', '" . $usuario . "', 0, 
                            '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
                        $result_opcional = DB::queryArray($sql, array());
                    }
                }

                // Sin
                if (!empty($value['sin'])) {
                    // Excluye los insumos sin del inventario
                    $condicion = (!empty($value['sin'])) ? " AND p.id NOT IN(" . $value['sin'] . ")" : ""; 
                    // Obtiene los productos
                    $sql = "SELECT p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
                            FROM app_productos p
                            INNER JOIN app_producto_material m ON m.id_material = p.id
                            WHERE m.id_producto = " . $value['id_producto'] . $condicion;
                    // return $sql;
                    $sin = DB::queryArray($sql, array());
                
                    // Actualiza el inventario por cada producto
                    foreach ($sin['rows'] as $k => $v) {
                        $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,tipo_traspaso, costo, referencia)
                            VALUES ('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
                                '" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id']  . "')";
                        $result_opcional = DB::queryArray($sql, array());
                    }
                }
                // Extras
                if (!empty($value['extras'])) {
                    $sql = "SELECT p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
                            FROM app_productos p
                            INNER JOIN app_producto_material m ON m.id_material = p.id
                            WHERE p.id IN(" . $value['extras'] . ") AND m.id_producto = " . $value['id_producto'];
                    // $result[$value['id_producto']]['extras'] = $sql;
                    $adicionales = DB::queryArray($sql, array());

                    // Actualiza el inventario por cada producto
                    foreach ($adicionales['rows'] as $k => $v) {
                        $sql = "INSERT INTO app_inventario_movimientos (id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado, tipo_traspaso, costo, referencia)
                            VALUES ('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
                                '" . $usuario. "', 0, '" . $v['importe'] . "', 'Combo " . $objeto['idproducto'] . "')";
                        $result_adicionales = DB::queryArray($sql, array());
                    }
                }
                /* FIN Actualiza el inventario ======================================================================= */

            }//FIN foreach
            // Actuliza el estado del pedido para indicar que se dio de alta (status='0')
            $sql = "UPDATE com_pedidos_combo SET status = '0' 
                    WHERE status = '-1' AND id_pedido = " . $objeto['id'];
            $result = DB::queryArray($sql, array());
        
            return $result;  
        }

        function listar_complementos($objeto, $usuario){
            //$usuario = $objeto["id_mesero"];
            $condicion = "";
            $sucursal = "   SELECT mp.idSuc AS id FROM administracion_usuarios au 
                            INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                            WHERE au.idempleado = " . $usuario . " 
                            LIMIT 1";

            $sucursal = DB::queryArray($sucursal, array());
            $idSuc = $sucursal["registros"][0]["id"];
        
            $sql = "SELECT * FROM app_producto_sucursal LIMIT 1";
            $total = DB::queryArray($sql, array());
            if($total['total'] > 0){
                // Filtra por los complementos si existe
                $condicion .= (!empty($objeto['complementos'])) ? ' AND c.id_producto IN('.$objeto['complementos'].')' : '';
                
                $sql = "SELECT p.id, c.cantidad, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo, p.tipo_producto, p.ruta_imagen AS imagen, ROUND(p.precio, 2) AS precio, c.id AS id_complemento
                        FROM com_complementos c
                        LEFT JOIN app_productos p ON p.id = c.id_producto
                        LEFT JOIN app_costos_proveedor pro ON pro.id_producto = p.id
                        INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$idSuc." WHERE 1 = 1 ". $condicion . " GROUP BY p.id";
            } else {
                // Filtra por los complementos si existe
                $condicion .= (!empty($objeto['complementos'])) ? ' AND c.id_producto IN('.$objeto['complementos'].')' : '';
                
                $sql = "SELECT p.id, c.cantidad, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo, p.tipo_producto, p.ruta_imagen AS imagen, ROUND(p.precio, 2) AS precio, c.id AS id_complemento
                        FROM com_complementos c
                        LEFT JOIN app_productos p ON p.id = c.id_producto
                        LEFT JOIN app_costos_proveedor pro ON pro.id_producto = p.id
                        WHERE 1 = 1 ". $condicion . " GROUP BY p.id";
            }
            // return $sql;
            $result = DB::queryArray($sql, array());
            
            return $result;
        }   
    }
?>
