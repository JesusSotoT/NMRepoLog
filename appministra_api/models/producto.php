<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class ProductoModel extends Connectionapi
    {
        
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

        public function obtenerProducto($filtros, $parametros, $impuestos = true){
            try{
                $select = "SELECT   id, 
                                    codigo, nombre, 
                                    descripcion_corta, if(descripcion_larga IS NULL, '' , descripcion_larga) as descripcion_larga, 
                                    precio, if(id_moneda IS NULL, 0, id_moneda) as id_moneda, 
                                    if(ruta_imagen!='', ruta_imagen, 'noimage.jpeg') as imagen, 
                                    if(tipo_producto IS NULL, 0, tipo_producto) as tipo_producto, if(departamento IS NULL, 0, departamento) as departamento, 
                                    id_unidad_venta AS medida, clave_sat 
                                    FROM app_productos  
                                    WHERE status = 1 AND ". $filtros ." 
                                    ORDER BY nombre ASC;";
                $resultSelect = $this->queryArray($select, $parametros);

                $array_productos = array();
                $ids = "";
                
                foreach ($resultSelect["rows"] as $item => &$producto) {
                    $ids .= $producto["id"] . ",";
                    $producto["impuestos"] = array();
                    $array_productos[$producto["id"]] = $producto;
                }
                $ids = trim($ids, ",");

                $pre_productos = "SELECT * FROM app_productos WHERE id IN($ids);";
                $pre_productos = $this->queryArray($pre_productos, array());

                $calcular = $this->calcularImpuestos($pre_productos["rows"], 0, false, true);
                foreach ($calcular as $producto_calculado) {
                    $array_productos[$producto_calculado["id"]]["precio"] = $producto_calculado["total"];
                }

                if($impuestos && $ids != ""){
                    $selectImpuesto = "SELECT pi.id_producto, pi.id_impuesto, i.nombre FROM app_producto_impuesto AS pi INNER JOIN app_impuesto AS i ON pi.id_impuesto = i.id WHERE pi.id_producto IN (". $ids .");";
                    $resultSelectImpuesto = $this->queryArray($selectImpuesto);
                    
                    foreach ($resultSelectImpuesto["rows"] as $impuesto) {
                        $array_productos[$impuesto["id_producto"]]["impuestos"][] = $impuesto;
                    }

                    $resultSelect["rows"] = array();

                    foreach ($array_productos as $producto) {
                        $resultSelect["rows"][] = $producto;
                    }
                }

                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function calcularImpuestos($productos, $descuento, $realizada = false, $redondear = false){
            try {  
                $lista_productos = array();         
                foreach ($productos as $key => &$producto) {
                    $total = $subtotal = $producto["precio"] * 1;
                    if($descuento > 0){
                        $descuentoGeneral += $producto["precio"] * ((float)$descuento / 100);
                        $total = $subtotal = $subtotal - ($subtotal * ((float)$descuento / 100));
                    }

                    $ieps = 0;
                    $producto_impuesto = 0;
                    $producto_impuesto2 = 0;
                    $producto_impuestoR = 0;
                    if($producto["formulaIeps"] == 2){
                        $ordenform = 'ASC';
                    }else{
                        $ordenform = 'DESC';
                    }

                    $obtener_impuestos = "SELECT p.precio, i.valor, i.clave, pi.formula, i.nombre, pi.id_impuesto AS id";
                    $obtener_impuestos .= " FROM app_impuesto i, app_productos p ";
                    $obtener_impuestos .= " LEFT JOIN app_producto_impuesto pi on p.id = pi.id_producto ";
                    $obtener_impuestos .= " WHERE p.id = ".$producto["id"] ." AND i.id = pi.id_impuesto ";
                    $obtener_impuestos .= " ORDER BY pi.id_impuesto ".$ordenform;

                    $parametros = array();
                    
                    $obtener_impuestos = $this->queryArray($obtener_impuestos, $parametros);

                    $impuestos = array();
                    foreach ($obtener_impuestos["rows"] as $key => $valueImpuestos) {
                        $impuesto = array();
                        if ($valueImpuestos["clave"] == 'IEPS') {
                            $producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
                        } elseif($valueImpuestos["clave"]=='IVAR' || $valueImpuestos["clave"]=='ISR'){
                            $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuestoR = (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuestoR += (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
                        } else {
                            if ($ieps != 0) {   
                                $producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);                     
                            } else {
                                $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100); 
                                $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);                               
                            }
                        }

                        $impuesto["id"] = $valueImpuestos["id"];
                        $impuesto["nombre"] = $valueImpuestos["nombre"];
                        $impuesto["porcentaje"] = $valueImpuestos["valor"];
                        $impuesto["clave"] = $valueImpuestos["clave"];

                        $impuesto["monto"] = ($redondear) ? round($producto_impuesto, 2) : $producto_impuesto;
                        $impuestos[] = $impuesto;

                        $monto_impuestos += $impuesto["monto"];
                        if(isset($array_impuestos[$impuesto["nombre"]]))
                            $array_impuestos[$impuesto["nombre"]]["monto"] += $producto_impuesto;
                        else
                            $array_impuestos[$impuesto["nombre"]]["monto"] = $producto_impuesto;
                        $array_impuestos[$impuesto["nombre"]]["id"] = $valueImpuestos["id"];
                        $array_impuestos[$impuesto["nombre"]]["clave"] = $valueImpuestos["clave"];
                        $array_impuestos[$impuesto["nombre"]]["porcentaje"] = $valueImpuestos["valor"];
                    }

                    $total = ($subtotal + $producto_impuesto2) - $producto_impuestoR;
                    $monto_total += $total;
                    $monto_subtotal += $subtotal;

                    if($redondear) $producto["precio"] = round($producto["precio"], 2);
                    $producto["subtotal"] = ($redondear) ? round($subtotal, 2) : $subtotal;
                    $producto["impuestos"] = $impuestos;
                    $producto["suma_impuestos"] = $producto_impuesto2 - $producto_impuestoR;
                    $producto["total"] = ($redondear) ? round($total, 2) : $total;
                    $lista_productos[] = $producto;
                }
                return $lista_productos;
            } catch (Exception $e) {
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener el detalle de la venta", "error" => $e->getMessage());
            }
        }

        public function agregarProducto($nombre, $codigo, $precio, $des_larga, $des_corta, $departamento, $tipo, $unidad, $impuestos, $moneda, $imagen){  
            try{
                $verifica = $this->obtenerProducto("codigo = :codigo", array("codigo"=>$codigo), false);
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){

                    $estatus = false;
                    $this->connection->beginTransaction();
                    try {
                    
                        $queryInsert = "INSERT INTO app_productos (codigo, nombre, precio, descripcion_corta, descripcion_larga, ruta_imagen, tipo_producto, departamento, id_moneda, id_unidad_venta, status, id_unidad_compra) ";
                    
                        $queryInsert .= "VALUES (:codigo, :nombre, :precio, :des_corta, :des_larga, :imagen, :tipo, :departamento, :moneda, :unidad, '1', :unidad);";

                        $parametros = array(
                                        "codigo" => $codigo,
                                        "nombre" => $nombre,
                                        "precio" => $precio,
                                        "des_larga" => $des_larga,
                                        "des_corta" => $des_corta,
                                        "departamento" => $departamento,
                                        "tipo" => $tipo,
                                        "unidad" => $unidad,
                                        "moneda" => $moneda,
                                        "imagen" => $imagen
                                        ); 

                        $result = $this->queryArray($queryInsert, $parametros);
                        if(!$result["status"]) throw new Exception("Producto no insertado", 1);
                        $producto_id = $result["insertId"];

                        //** Guarda los campos para foodware 
                        // valida si es vendible o no
                        $vendible = ($tipo == 3) ? 0 : 1 ;
                        $queryInsert = " INSERT INTO app_campos_foodware(id_producto, vendible, rate) VALUES (:producto_id, :vendible, 1);";
                        $parametros = array("producto_id" => $producto_id, "vendible" => $vendible);
                        $result = $this->queryArray($queryInsert, $parametros);
                        if(!$result["status"]) throw new Exception("Producto Foodware no insertado", 2);
                        
                        /*-------------------------------------------------*/
                        /*-----------------Lista de Impuestos-------------*/
                        foreach ($impuestos as $key => $impuesto) {
                            $queryInsert = " INSERT INTO app_producto_impuesto (id_producto, id_impuesto, formula) values (:producto_id, :impuesto_id, '0');";
                            $parametros = array("producto_id" => $producto_id, "impuesto_id" => $impuesto["id"]);
                            $result = $this->queryArray($queryInsert, $parametros);
                            if(!$result["status"]) throw new Exception("Impuesto no insertado", 2);
                        }

                        $this->connection->commit();
                        $estatus = true;
                    } catch (Exception $e) {
                        $this->connection->rollBack();
                        echo $e->getMessage(); exit();
                    }

                    $producto = $this->obtenerProducto("codigo = :codigo", array("codigo"=>$codigo), false);
                    $rowProducto = $producto["rows"][0];

                    return array("status" => $estatus, "rows" => array(), "insertId" => $rowProducto["id"], "extra" => array("imagen" => $rowProducto["imagen"]));
                }else{
                    return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Codigo ya ha sido registrado");
                }
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function editarProducto($id, $nombre, $codigo, $precio, $des_larga, $des_corta, $departamento, $tipo, $unidad, $impuestos, $moneda, $imagen){
            try{
                $verifica = $this->obtenerProducto("codigo = '". $codigo ."' AND id != ". $id);
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){

                    $estatus = false;
                    $this->connection->beginTransaction();
                    try {

                        $queryUpdate = "UPDATE app_productos SET ";
                        $queryUpdate .= "codigo = :codigo, nombre = :nombre, precio = :precio, descripcion_corta = :des_corta, descripcion_larga = :des_larga, tipo_producto = :tipo, departamento = :departamento, id_moneda = :moneda, id_unidad_venta = :unidad, id_unidad_compra = :unidad ";
                        $queryUpdate .= ($imagen != null) ? ", ruta_imagen = :imagen " : " ";
                        $queryUpdate .= "WHERE id = :id;";

                        $parametros = array(
                                        "id" => $id,
                                        "codigo" => $codigo,
                                        "nombre" => $nombre,
                                        "precio" => $precio,
                                        "des_larga" => $des_larga,
                                        "des_corta" => $des_corta,
                                        "departamento" => $departamento,
                                        "tipo" => $tipo,
                                        "unidad" => $unidad,
                                        "moneda" => $moneda,
                                        "imagen" => $imagen
                                        ); 

                        $result = $this->queryArray($queryUpdate, $parametros);
                        if(!$result["status"]) throw new Exception("Producto no modificado", 1);

                        /*-------------------------------------------------*/
                        /*-----------------Lista de Impuestos-------------*/
                        foreach ($impuestos as $key => $impuesto) {
                            if($impuesto["estatus"] == 1 || $impuesto["estatus"] == 2){
                                if($impuesto["estatus"] == 1)
                                    $queryUpdate = " INSERT INTO app_producto_impuesto (id_producto, id_impuesto, formula) values (:id, :impuesto_id, '0');";
                                else if($impuesto["estatus"] == 2) 
                                    $queryUpdate = " DELETE FROM app_producto_impuesto WHERE id_producto = :id AND id_impuesto = :impuesto_id;";
                                $parametros = array("id" => $id, "impuesto_id" => $impuesto["id"]);
                                $result = $this->queryArray($queryUpdate, $parametros);
                                if(!$result["status"]) throw new Exception($queryUpdate, 2);
                            }
                        }

                        $this->connection->commit();
                        $estatus = true;
                    } catch (Exception $e) {
                        $this->connection->rollBack();
                        echo $e->getMessage(); exit();
                    }

                    return array("status" => $estatus, "rows" => array(), "insertId" => 0);
                }else{
                    return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Codigo ya ha sido registrado");
                }
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible editar la información", "error" => $e->getMessage());
            }
        }

        public function eliminarProducto($id){
            try{
                $sel = 'UPDATE app_productos set status = 0 where id = :id';
                $parametros = array("id" => $id);
                $res = $this->queryArray($sel, $parametros);
                return  $res;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible editar la información", "error" => $e->getMessage());
            }
        }

    }

?>
