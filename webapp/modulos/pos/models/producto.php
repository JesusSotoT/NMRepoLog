<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ProductoModel extends Connection {

    function listar_unidades($objeto){
        $sql = "SELECT
                    *
                FROM
                    app_unidades_medida";
        // return $sql;
        $result = $this->queryArray($sql);

        return $result;
    }

    function listar_insumos($objeto){



    // // Filtra por el ID del insumo si existe
    //     $condicion.=(!empty($objeto['id']))?' AND p.id='.$objeto['id']:'';
    // // Filtra por el nombre y codigo de insumo del insumo si existe
    //     $condicion.=(!empty($objeto['nombre'])&&!empty($objeto['codigo']))?'
    //             AND
    //                 (p.nombre=\''.$objeto['nombre'].'\'
    //                     OR
    //                 p.codigo=\''.$objeto['codigo'].'\')':'';
    // // Filtra por el tipo de producto si existe
    //     $condicion.=(!empty($objeto['tipo_producto']))?' AND p.tipo_producto='.$objeto['tipo_producto']:'';

    // Si es insumo preparado toma "p.costo_servicio", si no toma el costo del proveedor y si es null lo cambia por 0
         $sql=" SELECT
                        p.id AS idProducto, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
                        p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad,
                        (SELECT
                            nombre
                        FROM
                            app_unidades_medida uni
                        WHERE
                            uni.id = p.id_unidad_venta) AS unidad,
                        (SELECT
                                id
                        FROM
                                app_unidades_medida uni
                        WHERE
                                uni.id = p.id_unidad_venta) AS unidad_codigo,
                        (SELECT
                                clave
                        FROM
                                app_unidades_medida uni
                        WHERE
                                uni.id = p.id_unidad_venta) AS unidad_clave, p.codigo, u.factor
                FROM
                        app_productos p
                    LEFT JOIN
                            app_campos_foodware f
                        ON
                            p.id=f.id_producto
                    LEFT JOIN
                            app_unidades_medida u
                        ON
                            u.id=p.id_unidad_compra
                    LEFT JOIN
                            app_costos_proveedor pro
                        ON
                            pro.id_producto=p.id
                    WHERE
                        status=1
                        AND (p.tipo_producto=3 or p.tipo_producto=8) AND p.id!='$objeto';";
                    //$condicion;
        // return $sql;
        $result = $this->queryArray($sql);

        return $result;
    }

    public function indexGridProductos($limit){
       // $query = "SELECT * from app_productos order by id asc";
/*  COMENTE ESTA CONSULTA PARA QUITAR EL CAMPO Y LA TABLA DE USUARIOS Y EMPLEADOS PARA HACER LA CONSULTA MAS RAPIDA ADEMAS DE LOS LIMITES
        $query = "SELECT us.idempleado ,c.id_proveedor as idProve,c.costo,p.*";
        $query .= "from app_productos p ";
        $query .= "left join app_costos_proveedor c on c.id_producto=p.id ";
        $query .= "left join accelog_usuarios us on us.idempleado=p.idempleado ";
        $query .= "group by p.id ";
        $query .= "order by p.id asc ".$limit;
*/        //echo $query;
        $query = "SELECT c.id_proveedor as idProve,c.costo,p.*";
        $query .= "from app_productos p ";
        $query .= "left join app_costos_proveedor c on c.id_producto=p.id ";
        $query .= "where p.status = 1 or p.status = 0 ";
        $query .= "group by p.id ";
        $query .= "order by p.id asc ";
        $rest = $this->queryArray($query);
        
        return array('productos' => $rest['rows'], 'total' => $rest['total'] );
    }
    public function almacenes(){
        $query = 'SELECT * FROM almacen';
        $almacenes = $this->queryArray($query);

        return $almacenes['rows'];
    }
    public function ListaProveedor(){
        $query = 'SELECT * FROM mrp_proveedor';
        $proves = $this->queryArray($query);
        //var_dump($proves);
        //$proves = $proves->fetch_assoc();
        
        return $proves['rows'];
        
    }
    public function unidades(){
        $query = "SELECT * from app_unidades_medida where activo=1";
        $uniRes = $this->queryArray($query);

        return $uniRes['rows'];
    }
    public function moneda(){
        $query = "SELECT * from cont_coin";
        $res = $this->queryArray($query);

        return $res['rows'];
    }
    public function sucursal(){
        $query = "SELECT * from mrp_sucursal";
        $res = $this->queryArray($query);

        return $res['rows'];
    }
    public function eliminaVinculacion($idProducto,$idSuc){
        $del = 'DELETE from app_producto_sucursal where id_producto="'.$idProducto.'" and id_sucursal="'.$idSuc.'";';
        $res = $this->queryArray($del);

        return  array('estatus' => true );
    }
    public function productosSuc($idSucursal){
        $pr  = 'Select id,nombre,codigo from app_productos where status=1';

        $pr = 'SELECT p.id,p.nombre,p.codigo, d.nombre depa ,f.nombre fam, l.nombre linea  from app_productos p
                left join app_departamento d on d.id=p.departamento
                left join app_familia f on f.id=p.familia
                left join app_linea l on l.id=p.linea
                WHERE status=1';
        $res = $this->queryArray($pr);
        $contenido = '';
        
       
        foreach ($res['rows'] as $key => $value) {
            $sel = 'SELECT s.idSuc, s.nombre from app_producto_sucursal ps, mrp_sucursal s where ps.id_sucursal=s.idSuc and ps.id_producto='.$value['id'];
            //echo $sel.'<br>'; 
            $sucRes = $this->queryArray($sel);
            $sucursales = '';
            if($sucRes > 0){

                foreach ($sucRes['rows'] as $key2 => $value2) {
                    if($value2['idSuc']==$idSucursal){
                        $checkedval = 'checked';
                    }
                    $sucursales.=$value2['nombre'].',';
                }
            }
            $res['rows'][$key]['sucursales'] = $sucursales;

                    $contenido .= '<tr>';
                    $contenido .='<td><input type="checkbox" class="checkPro" value="'.$value['id'].'" '.$checkedval.'></td>';
                    $contenido .='<td>'.$value['id'].'</td>'; 
                    $contenido .='<td>'.$value['codigo'].'</td>'; 
                    $contenido .='<td>'.$value['nombre'].'</td>'; 
                    $contenido .='<td>'.$res['rows'][$key]['sucursales'].'</td>';
                    $contenido .='<td>'.$value['depa'].'</td>'; 
                    $contenido .='<td>'.$value['fam'].'</td>'; 
                    $contenido .='<td>'.$value['linea'].'</td>'; 
                    $contenido .='<td><button onclick="esignaSuc('.$value['id'].');" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>'; 
                    $contenido .= '</tr>'; 
                    $checkedval = ''; 
        }   
        
        return $contenido;
        //echo 



    }
        public function productosSat($idSucursal){
        $pr  = 'Select id,nombre,codigo from app_productos where status=1';

        /*SELECT p.id,p.codigo,p.nombre,cl.c_claveprodserv clave_sat, d.nombre departamento, f.nombre familia, l.nombre linea 
                from app_productos p
                left join app_departamento d on p.departamento = d.id
                left join app_familia f on p.familia = f.id
                left join app_linea l on p.linea = l.id
                left join c_claveprodserv cl on p.clave_sat = cl.id
                where 1 */

        $pr = 'SELECT p.id,p.nombre,p.codigo, d.nombre depa ,f.nombre fam, l.nombre linea, p.clave_sat clave_sat from app_productos p
                left join app_departamento d on d.id=p.departamento
                left join app_familia f on f.id=p.familia
                left join app_linea l on l.id=p.linea
                WHERE status=1';
        $res = $this->queryArray($pr);
        $contenido = '';
        
       
        foreach ($res['rows'] as $key => $value) {
            $sel = 'SELECT s.idSuc, s.nombre from app_producto_sucursal ps, mrp_sucursal s where ps.id_sucursal=s.idSuc and ps.id_producto='.$value['id'];
            //echo $sel.'<br>'; 
            $sucRes = $this->queryArray($sel);
            $sucursales = '';
            if($sucRes > 0){

                foreach ($sucRes['rows'] as $key2 => $value2) {
                    if($value2['idSuc']==$idSucursal){
                        $checkedval = 'checked';
                    }
                    $sucursales.=$value2['nombre'].',';
                }
            }
            $res['rows'][$key]['sucursales'] = $sucursales;

                    $contenido .= '<tr>';
                    $contenido .='<td><input type="checkbox" class="checkPro" value="'.$value['id'].'" '.$checkedval.'></td>';
                    $contenido .='<td>'.$value['id'].'</td>'; 
                    $contenido .='<td>'.$value['codigo'].'</td>'; 
                    $contenido .='<td>'.$value['nombre'].'</td>'; 
                    //$contenido .='<td>'.$res['rows'][$key]['sucursales'].'</td>';
                    $contenido .='<td>'.$value['depa'].'</td>'; 
                    $contenido .='<td>'.$value['fam'].'</td>'; 
                    $contenido .='<td>'.$value['linea'].'</td>'; 
                    $contenido .='<td>'.$value['clave_sat'].'</td>'; 
                    //$contenido .='<td><button onclick="esignaSuc('.$value['id'].');" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>'; 
                    $contenido .= '</tr>'; 
                    $checkedval = ''; 
        }   
        
        return $contenido;
        //echo 



    }
    public function productosMonedero($idSucursal){
        $pr  = 'Select id,nombre,codigo from app_productos where status=1';

        /*SELECT p.id,p.codigo,p.nombre,cl.c_claveprodserv clave_sat, d.nombre departamento, f.nombre familia, l.nombre linea 
                from app_productos p
                left join app_departamento d on p.departamento = d.id
                left join app_familia f on p.familia = f.id
                left join app_linea l on p.linea = l.id
                left join c_claveprodserv cl on p.clave_sat = cl.id
                where 1 */

        $pr = 'SELECT p.id,p.nombre,p.codigo, d.nombre depa ,f.nombre fam, l.nombre linea,pt.nombre politica from app_productos p
                left join app_departamento d on d.id=p.departamento
                left join app_familia f on f.id=p.familia
                left join app_linea l on l.id=p.linea
                left join app_producto_politica ppt on ppt.id_producto = p.id
                left join app_politicas_tarjeta pt on pt.id = ppt.id_politica
                WHERE status=1;';
        $res = $this->queryArray($pr);
        $contenido = '';
        
       
        foreach ($res['rows'] as $key => $value) {
            $sel = 'SELECT s.idSuc, s.nombre from app_producto_sucursal ps, mrp_sucursal s where ps.id_sucursal=s.idSuc and ps.id_producto='.$value['id'];
            //echo $sel.'<br>'; 
            $sucRes = $this->queryArray($sel);
            $sucursales = '';
            if($sucRes > 0){

                foreach ($sucRes['rows'] as $key2 => $value2) {
                    if($value2['idSuc']==$idSucursal){
                        $checkedval = 'checked';
                    }
                    $sucursales.=$value2['nombre'].',';
                }
            }
            $res['rows'][$key]['sucursales'] = $sucursales;

                    $contenido .= '<tr>';
                    $contenido .='<td><input type="checkbox" class="checkPro" value="'.$value['id'].'" '.$checkedval.'></td>';
                    $contenido .='<td>'.$value['id'].'</td>'; 
                    $contenido .='<td>'.$value['codigo'].'</td>'; 
                    $contenido .='<td>'.$value['nombre'].'</td>'; 
                    //$contenido .='<td>'.$res['rows'][$key]['sucursales'].'</td>';
                    $contenido .='<td>'.$value['depa'].'</td>'; 
                    $contenido .='<td>'.$value['fam'].'</td>'; 
                    $contenido .='<td>'.$value['linea'].'</td>'; 
                    $contenido .='<td>'.$value['politica'].'</td>'; 
                    //$contenido .='<td><button onclick="esignaSuc('.$value['id'].');" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>'; 
                    $contenido .= '</tr>'; 
                    $checkedval = ''; 
        }   
        
        return $contenido;
        //echo 



    }
    public function vinculacionMasiva($cadena,$idSucursal,$monedero){
        //echo $cadena;
        $cadena=trim($cadena,',');
        $cadena = explode(',', $cadena);
        $delete = 'DELETE from app_producto_sucursal where id_sucursal='.$idSucursal;
        $this->queryArray($delete);
        foreach ($cadena as $key => $value) {
            $insert = 'INSERT into app_producto_sucursal(id_producto,id_sucursal) values("'.$value.'","'.$idSucursal.'")';
            //echo $insert;
            $this->queryArray($insert);
        }

        return  array('estatus' => true );

    }
    public function getSucuPro($id){
        $se = 'SELECT s.idSuc, s.nombre, pr.nombre as producto from app_producto_sucursal ps, mrp_sucursal s, app_productos pr where ps.id_sucursal=s.idSuc  and ps.id_producto=pr.id and ps.id_producto='.$id;
        $res = $this->queryArray($se);
        if($res['total'] > 0){
            return array('sucursales' => $res['rows'], 'producto' => $res['rows'][0]['producto'], 'idP' => $id);
        }else{
            $sel = 'SELECT id,codigo,nombre from app_productos where id='.$id;
            $res2 = $this->queryArray($sel);
            return array('sucursales' => '', 'producto' => $res2['rows'][0]['nombre'], 'idP' => $id);            
        }

        
    }
    public function agregaAsucursal($idProducto,$sucursal){
        $sel = 'SELECT id from app_producto_sucursal where id_producto="'.$idProducto.'" and id_sucursal="'.$sucursal.'"';
        $res = $this->queryArray($sel);

        if($res['total']>0){
            return  array('estatus' => false );
        }else{
            $inser = 'INSERT into app_producto_sucursal(id_producto,id_sucursal) values("'.$idProducto.'","'.$sucursal.'")';
            $re2 = $this->queryArray($inser);
         
            if($re2['insertId']>0){
                return  array('estatus' => true );
            }
        }
    }
    public function buscaFam($dep){
        $query1 = "SELECT * from app_familia where id_departamento=".$dep;
        $res = $this->queryArray($query1);

        return $res['rows'];
    }
    public function buscaLinea($fam){
        $query1 = "SELECT * from app_linea where activo=1 and id_familia=".$fam;
        $res = $this->queryArray($query1);

        return $res['rows'];
    }
    public function nombreProvedor($idProv){
        $query = 'SELECT * FROM mrp_proveedor where idPrv='.$idProv;
        $proves = $this->queryArray($query);
       // var_dump($proves);
        //$proves = $proves->fetch_assoc();
        
        return $proves['rows'];
    }
    public function desactiva($idProducto){
        $query = 'UPDATE app_productos set status=0 where id='.$idProducto;
        $res = $this->queryArray($query);

        return  array('estatus' => true );
    }
    public function activa($idProducto){
        $query = 'UPDATE app_productos set status=1 where id='.$idProducto;
        $res = $this->queryArray($query);

        return  array('estatus' => true );
    }
    public function guardaProducto($idProducto,$nombre,$codigo,$precio,$deslarga,$descorta,$departamento,$familia,$linea,$maximo,$minimo,$tipoProd,$costeo,$proveedores,$uniCompra,$uniVenta,$cartrt,$listPreciosStr,$preciosSucursal,$listaImpuestos,$comision,$moneda,$lotes,$series,$pedimentos,$tipoCom,$costoServicio,$imagen,$iepsForm,$configComision,$precioBaseComision,$porcentajeBaseComision,$tipoComision,$resena,$link,$edicion,$divisionSat,$grupoSat,$claseSat,$claveSat,$consigna,$box,$boxPeso,$boxAlto,$boxLargo,$boxAncho,$objeto){  
        session_start();

       /* echo 'x'.$listaImpuestos;
            
         exit();*/
         $selProCom = "";
         $resSelPro = "";
         
         $selProCom = "SELECT id from app_productos where nombre='".$nombre."' or codigo='".$codigo."'";
         $resSelPro = $this->queryArray($selProCom);


         if($resSelPro['total']>0){
            return array('status' => false, 'mensaje' => 'Ya existe un producto con ese mismo codigo o nombre.', 'idProducto' => '');
         }

        date_default_timezone_set("Mexico/General");
        $fechaactual = date("Y-m-d H:i:s");

        $status = 1;
        //$moneda = 1;




        if($objeto['factor']==null || $objeto['factor']==''){
            $factorrr=0;
            $cant_minnn=0;
        }else{
            $factorrr=$objeto['factor'];
            $cant_minnn=$objeto['cant_min'];
        }
        

        $queryInsert = "INSERT into app_productos (codigo,nombre,precio,descripcion_corta,descripcion_larga,ruta_imagen,tipo_producto,maximos,minimos,departamento,familia,linea,id_tipo_costeo,id_moneda,comision,tipo_comision,id_unidad_venta,series,lotes,pedimentos,status,id_unidad_compra,costo_servicio,formulaIeps, fecha_mod, resena, link,edicion,clave_sat,division_sat,grupo_sat,clase_sat,consigna,peso_dimension,pesokg,altocm,largocm,anchocm,factor,minimoprod) Values ('".$codigo."','".$nombre."','".$precio."','".$descorta."','".$deslarga."','".$imagen."','".$tipoProd."','".$maximo."','".$minimo."','".$departamento."','".$familia."','".$linea."','".$costeo."','".$moneda."','".$comision."','".$tipoCom."','".$uniVenta."','".$series."','".$lotes."','".$pedimentos."','".$status."','".$uniCompra."','".$costoServicio."','".$iepsForm."', '".$fechaactual."', '".$resena."', '".$link."','".$edicion."','".$claveSat."','".$divisionSat."','".$grupoSat."','".$claseSat."','".$consigna."','".$box."','".$boxPeso."','".$boxAlto."','".$boxLargo."','".$boxAncho."','".$factorrr."','".$cant_minnn."')";
       // echo $queryInsert;
        $resultInsert = $this->queryArray($queryInsert);
        $idProducto = $resultInsert['insertId'];



    /* Guarda Formulacion */

    if($tipoProd==8 or $tipoProd==9){
        $a_ids = array();
        
        foreach ($_SESSION['insumos_producto'] as $key => $value){
            if($value['cantidad']=='' || $value['cantidad']==null){
                $value['cantidad']=0;
            }
            array_push($a_ids, $value['id']);

             $sql = "INSERT INTO
                            app_producto_material (id_producto, cantidad, id_unidad, id_material, status)
                        VALUES
                            (" . $idProducto . ", " .$value['cantidad']. ", " .$value['idunidad']. ", " .$value['id']. ", 1)";

            $this->query($sql);
        }
        $ids_insumos = implode(",", $a_ids);
        $sql = "INSERT INTO
                            com_recetas (nombre, precio, ganancia, ids_insumos, status)
                        VALUES
                            ('" . $nombre . "', 0, 0, '" . $ids_insumos . "', 1)";
        $this->query($sql);

       
    }
	
	/* Guarda los campos para foodware
	============================================================================= */
	
		$vendible = ($tipoProd == 3) ? 0 : 1 ;
		$sql = "	INSERT INTO 
						app_campos_foodware(id_producto, vendible, rate)
					VALUES
						(".$idProducto.", ".$vendible.", 1)";
        $result_foodware = $this->query($sql);
        
	/* FIN
	============================================================================= */
		
        /*-----------Proveedores inserts------------*/
        $token =explode("/", $proveedores);
        //print_r($token);
        foreach ($token as $key => $value) {
            //echo '('.$value.')';
            if($value!='undefined-undefined'){
                if($value!=''){
                    //echo 'X';
                     $token2 = explode('-',$value);
                    $queryInserProd ="INSERT into app_producto_proveedor (id_producto,id_proveedor,id_unidad) values('".$idProducto."','".$token2[0]."','".$uniCompra."')";
                    //$queryInserProd.=" values('".$idProducto."','".$token2[0]."','1')";
                    //echo $queryInserProd;
                    $insertaproductoPR = $this->queryArray($queryInserProd);

                    $qInsertCostProve ="INSERT into app_costos_proveedor (id_proveedor,id_producto,id_moneda,costo,fecha) values('".$token2[0]."','".$idProducto."','".$moneda."','".$token2[1]."','".$fechaactual."')";
                    //$qInsertCostProve.=" values('".$token2[0]."','".$idProducto."','".$moneda."','".$token2[1]."','".$fechaactual."')";
                    //echo $qInsertCostProve;
                    $insertaproductoCosto = $this->queryArray($qInsertCostProve);
                
                }
            }
        }
        //exit();
        /*----------------------------------------------*/
        /*-----------Caracteristicas inserts------------*/
        $toki =explode("-", $cartrt);
       // print_r($toki);
        foreach ($toki as $key2 => $value2) {
            if($key2 > 0){
                if($value2!='undefined'){
                   // echo $value2.'X';
                    $inCaracter = "INSERT INTO app_producto_caracteristicas (id_producto,id_caracteristica_padre) values ('".$idProducto."','".$value2."')";
                    $inCaracterResult = $this->queryArray($inCaracter);
                }
            }
        }    
        /*-----------------------------------------------*/
        /*-----------Lista de precios inserts------------*/
        /*$toku =explode("-", $listPreciosStr);
       // print_r($toki);
        foreach ($toku as $key3 => $value3) {
            if($key3 > 0){
                if($value3!='undefined'){
                    
                    $inListPrice = "INSERT INTO app_lista_precio_prods (id_lista,id_producto) values ('".$value3."','".$idProducto."')";
                    //echo $inListPrice;
                    $inilistpre = $this->queryArray($inListPrice);
                }
            }
        } */

    /*   ch insert lista de precio con sucursal
        $toku =explode("-", $listPreciosStr);
        foreach ($toku as $key3 => $value3) {
            if($key3 > 0){
                $value3 =explode("*", $value3);
                $idLista = $value3[0]; 
                if($value3[0] !='undefined'){
                    $value4 =explode("|", $value3[1]);                                        
                     $inListPrice = "INSERT INTO app_lista_precio_prods (id_lista,id_producto,idsuc) values ('".$idLista."','".$idProducto."','".$value4[1]."')";
                    $inilistpre = $this->queryArray($inListPrice);
                }
               if($value3[1] !='undefined|undefined'){
                    $value4 =explode("|", $value3[1]);                
                    $inListPrice = "UPDATE  app_lista_precio_prods set precio = ".$value4[0]." where id_lista ='".$idLista."' AND id_producto = '".$idProducto."' AND idsuc = '".$value4[0]."'";
                    $inilistpre = $this->queryArray($inListPrice);
                }
            }
        } 
    */

        $toku =explode("-", $listPreciosStr);
       // print_r($toki);
        foreach ($toku as $key3 => $value3) {
            if($key3 > 0){
                $value3 =explode("*", $value3);
                $idLista = $value3[0];
                if($value3[0] !='undefined'){
                    $value4 =explode("|", $value3[1]);
                    //$inListPrice = "INSERT INTO app_lista_precio_prods (id_lista,id_producto) values ('".$value3[0]."','".$idProducto."')";
                    $inListPrice = "INSERT INTO app_lista_precio_prods (id_lista,id_producto,id_suc) values ('".$idLista."','".$idProducto."','".$value4[1]."')";
                    //echo $inListPrice;
                    $inilistpre = $this->queryArray($inListPrice);
                }
               if($value3[1] !='undefined|undefined'){
                    $value4 =explode("|", $value3[1]);
                    //$inListPrice = "UPDATE  app_lista_precio_prods set precio = ".$value3[1]." where id_lista ='".$value3[0]."' AND id_producto = '".$idProducto."'";
                    $inListPrice = "UPDATE  app_lista_precio_prods set precio = ".$value4[0]." where id_lista ='".$idLista."' AND id_producto = '".$idProducto."' AND id_suc = '".$value4[1]."'";
                    //echo $inListPrice;
                    $inilistpre = $this->queryArray($inListPrice);
                }
            }
        } 


        /*----- Precios base por sucursal --------*/
        foreach ($preciosSucursal as $key => $value) {
            $inSucPrice = "INSERT INTO app_precio_sucursal (producto,sucursal,precio) values ('".$idProducto."','".$value['sucursal']."','".$value['precio']."')";
                    //echo $inListPrice;
                    $iniSucPre = $this->queryArray($inSucPrice);
        }

        /*-------------------------------------------------*/
        /*-----------Lista de Impuestos inserts------------*/
        /*$teke =explode("-", $listaImpuestos);
        //print_r($teke);
        foreach ($teke as $key4 => $value4) {
            if($key4 > 0){
                if($value4!='undefined'){
                    $inImpu = "INSERT INTO app_producto_impuesto (id_producto,id_impuesto,formula) values ('".$idProducto."','".$value4."',)";
                    //echo $inImpu;
                    $inilistImp = $this->queryArray($inImpu);
                }
            }
        } */
        $teke =explode("-", $listaImpuestos);
        foreach ($teke as $key4 => $value4) {
            if($key4 > 0){
                if($value4 > 0 ){
                    $piki = explode('/',$value4);
                    if($piki[1]=='undefined'){
                        $piki[1] = 0;
                    }
                    $inImpu = "INSERT INTO app_producto_impuesto (id_producto,id_impuesto,formula) values ('".$idProducto."','".$piki[0]."','".$piki[1]."')";
                    //echo $inImpu;
                    $inilistImp = $this->queryArray($inImpu);
                }
            }
        }          
        /*------------------------------------------------*/ 
        /*------------- Actualiza Comisiones ------------------*/
        /*-----------------------------------------------*/
        $sql = "DELETE FROM app_comision_productos WHERE id_producto='$idProducto'";
        $this->queryArray($sql);
        //$configComision,$precioBaseComision,$porcentajeBaseComision,$tipoComision
        //var_dump($configComision);
                        //die();
        if( $configComision != "3" ) {
            if ( $configComision == "1" ) {
                $sql = "INSERT INTO app_comision_productos (config_comision, id_producto,  porcentaje_comision, id_tipo_comision) VALUES ('$configComision' , '$idProducto'  , '$porcentajeBaseComision' , '$tipoComision' )";
            }
            else {
                $sql = "INSERT INTO app_comision_productos (config_comision, id_producto, id_costo_proveedor_comision, porcentaje_comision, id_tipo_comision) VALUES ('$configComision' , '$idProducto' , '$precioBaseComision' , '$porcentajeBaseComision' , '$tipoComision' )";
            }
            
        }
        else {
            $sql = "INSERT INTO app_comision_productos (config_comision) VALUES ('$configComision')";
        }
        $this->queryArray($sql);
        /*-----------------------------------------------*/

        return array('status' => true, 'idProducto' => $idProducto);

    }
    public function depFamLin(){
        $queryD = "select * from app_departamento";
        $depa = $this->queryArray($queryD);

        $queryF = "select * from app_familia";
        $fam = $this->queryArray($queryF);

        $queryL = "select * from app_linea where activo=1";
        $lin = $this->queryArray($queryL);

        $queryLP = "select * from app_lista_precio where activo=1";
        $listaPre = $this->queryArray($queryLP);

        return array('dep' => $depa['rows'], 'fam' => $fam['rows'], 'lin' => $lin['rows'], 'listPre' => $listaPre['rows']);
    }
    public function costeoList(){
        $queryC = "SELECT * from app_costeo";
        $costeo = $this->queryArray($queryC);

        return $costeo['rows']; 
    }
    public function caracteristicas(){
        $queryCa = "SELECT * from app_caracteristicas_padre where activo=1";
        $carac = $this->queryArray($queryCa);

        return $carac['rows']; 
    }
    public function nombreCaracteristica($idC){
        $query = "SELECT * from app_caracteristicas_padre where id=".$idC;
        $caracT = $this->queryArray($query);

        return $caracT['rows'];
    }
    public function listaParametros($idLista){
        $queryLis = "SELECT * from app_lista_precio where id=".$idLista;
        $res = $this->queryArray($queryLis);
    
        return $res['rows'];
    }
    public function getImpuestos(){
        $queryIm = "SELECT * FROM impuesto";
        $resIm  = $this->queryArray($queryIm);

        return $resIm;
    }
    public function getNewImpuesto($idImp){
        $queryIm = "SELECT * FROM app_impuesto where id=".$idImp;
        $resIm  = $this->queryArray($queryIm);

        return $resIm['rows'];
    }
    public function impuestosConfig(){

        $queryconfig = "SELECT iva,ieps,ret_iva,ret_isr,ish from app_configuracion";
        $restConfig = $this->queryArray($queryconfig);
       
        $queryIva = "SELECT * from app_impuesto where id=".$restConfig['rows'][0]['iva'];
        $restIva = $this->queryArray($queryIva);

        $queryAllTaxes = "SELECT * from app_impuesto"; 
        $result = $this->queryArray($queryAllTaxes);
       
       //print_r($restIva['rows']);
      // exit();
        return array("impuestos" => $result['rows'], "IVA" => $restIva['rows']);

    }

    public function datosProducto($idProducto){
        //echo 'deeeeed';
        $query = "SELECT * from app_productos where id=".$idProducto;
        $result = $this->queryArray($query);

        $query2 = "SELECT a.*, b.*, s.idSuc, s.nombre sucursal FROM app_lista_precio a, app_lista_precio_prods b, mrp_sucursal s where b.id_lista=a.id  and a.activo=1 and s.idSuc = b.id_suc and b.id_producto=".$idProducto;
        $result2 = $this->queryArray($query2);

        $query22 = "SELECT p.*, s.nombre
                    FROM app_precio_sucursal p
                    LEFT JOIN mrp_sucursal s ON s.idSuc = p.sucursal
                    WHERE producto =".$idProducto;
        $result22 = $this->queryArray($query22);

        $query3 = "SELECT a.id,a.nombre FROM app_caracteristicas_padre a, app_producto_caracteristicas b where a.id=b.id_caracteristica_padre and a.activo=1 and b.id_producto=".$idProducto;
        $result3 = $this->queryArray($query3);

        $query4 = "SELECT p.idPrv, p.razon_social,c.costo from mrp_proveedor p, app_producto_proveedor prp, app_costos_proveedor c where p.idPrv=prp.id_proveedor and prp.id_producto=".$idProducto." and c.id_proveedor=prp.id_proveedor and c.id_producto=".$idProducto;
        $result4 = $this->queryArray($query4);

        $query5 = "SELECT i.id,i.nombre, i.valor, pi.formula from app_impuesto i, app_producto_impuesto pi where i.id=pi.id_impuesto and pi.id_producto=".$idProducto;
        $result5 = $this->queryArray($query5);

        $query6 = "SELECT a.cantidad, a.id_unidad from app_producto_material a where  a.id_producto=".$idProducto;
        $result6 = $this->queryArray($query6);


        



    $divisiones = $this->divisionesSat($result['rows'][0]['tipo_producto']);
    $grupos = $this->gruposSat($result['rows'][0]['division_sat']);
    $clase = $this->claseSat($result['rows'][0]['grupo_sat']);
    $clave = $this->claveSat($result['rows'][0]['clase_sat']);

        return array("basicos" => $result['rows'], "precios" => $result2['rows'], "preciosBase" => $result22['rows'], "caracteristicas" => $result3['rows'], 'proves' => $result4['rows'] ,'taxes' => $result5['rows'], 
            'division_sat' => $divisiones['divisiones'] , 'grupo_sat' => $grupos['grupos'] , 'clase_sat' => $clase['clases'] , 'clave_sat' => $clave['claves'], 'formulacion'=>$result6['rows'] );
       
    }
    public function updateProducto($idProducto,$nombre,$codigo,$precio,$deslarga,$descorta,$departamento,$familia,$linea,$maximo,$minimo,$tipoProd,$costeo,$proveedores,$uniCompra,$uniVenta,$cartrt,$listPreciosStr,$preciosSucursal,$listaImpuestos,$comision,$moneda,$lotes,$series,$pedimentos,$tipoCom,$costoServicio,$imagen,$iepsForm,$configComision,$precioBaseComision,$porcentajeBaseComision,$tipoComision,$resena,$link,$edicion,$divisionSat,$grupoSat,$claseSat,$claveSat,$consigna,$box,$boxPeso,$boxAlto,$boxLargo,$boxAncho,$objeto){

        session_start();

        date_default_timezone_set("Mexico/General");
        $fechaactual = date("Y-m-d H:i:s");

        $selProCom = "SELECT id from app_productos where (nombre='".$nombre."' or codigo='".$codigo."') and id <> ".$idProducto;
         $resSelPro = $this->queryArray($selProCom);

         if($resSelPro['total']>0){
            return array('status' => false, 'mensaje' => 'Ya existe un producto con ese mismo codigo o nombre.', 'idProducto' => '');
         }


        if($objeto['factor']==null || $objeto['factor']==''){
            $factorrr=0;
            $cant_minnn=0;
        }else{
            $factorrr=$objeto['factor'];
            $cant_minnn=$objeto['cant_min'];
        }


        if($tipoProd==8 or $tipoProd==9){
            $this->query("DELETE FROM app_producto_material WHERE id_producto='$idProducto';");
            //$this->query("DELETE FROM app_producto_material WHERE id_producto='$idProducto';");

            $a_ids = array();
            
            foreach ($_SESSION['insumos_producto'] as $key => $value){
                if($value['cantidad']=='' || $value['cantidad']==null){
                    $value['cantidad']=0;
                }
                array_push($a_ids, $value['id']);

                 $sql = "INSERT INTO
                                app_producto_material (id_producto, cantidad, id_unidad, id_material, status)
                            VALUES
                                (" . $idProducto . ", " .$value['cantidad']. ", " .$value['idunidad']. ", " .$value['id']. ", 1)";

                $this->query($sql);
            }
            $ids_insumos = implode(",", $a_ids);
            $sql = "INSERT INTO
                                com_recetas (nombre, precio, ganancia, ids_insumos, status)
                            VALUES
                                ('" . $nombre . "', 0, 0, '" . $ids_insumos . "', 1)";
            $this->query($sql);

           
        }
        
        $queryUpdate = "UPDATE app_productos set codigo='".$codigo."', nombre='".$nombre."',precio='".$precio."',descripcion_corta='".$descorta."', descripcion_larga='".$deslarga."', tipo_producto='".$tipoProd."', maximos='".$maximo."', minimos='".$minimo."', departamento='".$departamento."', familia='".$familia."', linea='".$linea."', id_tipo_costeo='".$costeo."', id_moneda='".$moneda."', id_unidad_venta='".$uniVenta."', comision='".$comision."', tipo_comision='".$tipoCom."', id_moneda='".$moneda."', series='".$series."', lotes='".$lotes."', pedimentos='".$pedimentos."', id_unidad_compra='".$uniCompra."', ruta_imagen='".$imagen."' , costo_servicio='".$costoServicio."', formulaIeps='".$iepsForm."', fecha_mod='".$fechaactual."', resena='".$resena."', link ='".$link."', edicion='".$edicion."', clave_sat='".$claveSat."', division_sat='".$divisionSat."', grupo_sat='".$grupoSat."', clase_sat='".$claseSat."', consigna='".$consigna."', peso_dimension='".$box."', pesokg='".$boxPeso."', altocm='".$boxAlto."', largocm='".$boxLargo."', anchocm='".$boxAncho."', factor='".$factorrr."', minimoprod='".$cant_minnn."' where id=".$idProducto;
        //echo $queryUpdate;
        $restUpdate = $this->queryArray($queryUpdate);

        /*----- Actualiza lista de precios --------*/
        $queryDelPrecios = "DELETE from app_lista_precio_prods where id_producto=".$idProducto;
        $resDelPre = $this->queryArray($queryDelPrecios);

        /*-----------Lista de precios inserts------------*/

        $toku =explode("-", $listPreciosStr);
       // print_r($toki);
        foreach ($toku as $key3 => $value3) {
            if($key3 > 0){
                $value3 =explode("*", $value3);
                $idLista = $value3[0];
                if($value3[0] !='undefined'){
                    $value4 =explode("|", $value3[1]);
                    //$inListPrice = "INSERT INTO app_lista_precio_prods (id_lista,id_producto) values ('".$value3[0]."','".$idProducto."')";
                    $inListPrice = "INSERT INTO app_lista_precio_prods (id_lista,id_producto,id_suc) values ('".$idLista."','".$idProducto."','".$value4[1]."')";
                    //echo $inListPrice;
                    $inilistpre = $this->queryArray($inListPrice);
                }
               if($value3[1] !='undefined|undefined'){
                    $value4 =explode("|", $value3[1]);
                    //$inListPrice = "UPDATE  app_lista_precio_prods set precio = ".$value3[1]." where id_lista ='".$value3[0]."' AND id_producto = '".$idProducto."'";
                    $inListPrice = "UPDATE  app_lista_precio_prods set precio = ".$value4[0]." where id_lista ='".$idLista."' AND id_producto = '".$idProducto."' AND id_suc = '".$value4[1]."'";
                    //echo $inListPrice;
                    $inilistpre = $this->queryArray($inListPrice);
                }
            }
        } 

        /*----- Actualiza precios base por sucursal --------*/
        $queryDelPrecios = "DELETE from app_precio_sucursal where producto=".$idProducto;
        $resDelPre = $this->queryArray($queryDelPrecios);

        foreach ($preciosSucursal as $key => $value) {

            $inSucPrice = "INSERT INTO app_precio_sucursal (producto,sucursal,precio) values ('".$idProducto."','".$value['sucursal']."','".$value['precio']."')";
                    //echo $inListPrice;
                    $iniSucPre = $this->queryArray($inSucPrice);
        }
        /*
        $toku =explode("-", $listPreciosStr);
        // print_r($toki);
        foreach ($toku as $key3 => $value3) {
            if($key3 > 0){
                $value3 =explode("*", $value3);
                if($value3[0] !='undefined'){
                    
                    $inListPrice = "INSERT INTO app_lista_precio_prods (id_lista,id_producto) values ('".$value3[0]."','".$idProducto."')";
                    //echo $inListPrice;
                    $inilistpre = $this->queryArray($inListPrice);
                }
               if($value3[1] !='undefined'){
                    
                    $inListPrice = "UPDATE  app_lista_precio_prods set precio = ".$value3[1]." where id_lista ='".$value3[0]."' AND id_producto = '".$idProducto."'";
                    //echo $inListPrice;
                    $inilistpre = $this->queryArray($inListPrice);
                }
            }
        } 
        */
        /*-------------------------------------------------*/
        /*----- Actualiza  Prtoveedores ---------*/
        /*-----------Proveedores inserts------------*/
        $queryDelProves = "DELETE from app_producto_proveedor where id_producto=".$idProducto;
        //echo $queryDelProves;
        $resDelProves = $this->queryArray($queryDelProves);
        $queryDelProves2 = "DELETE from app_costos_proveedor where id_producto=".$idProducto;
        //echo $queryDelProves2;
        $resDelProves2 = $this->queryArray($queryDelProves2);
        //7echo $proveedores;
        $token =explode("/", $proveedores);
        foreach ($token as $key => $value) {
            if($value!='undefined-undefined'){
                if($value!=''){
                    $token2 = explode('-',$value);
                    $queryInserProd ="INSERT into app_producto_proveedor (id_producto,id_proveedor,id_unidad) values('".$idProducto."','".$token2[0]."','1')";
                    //$queryInserProd.=" values('".$idProducto."','".$token2[0]."','1')";
                    //echo $queryInserProd;
                    $insertaproductoPR = $this->queryArray($queryInserProd);

                    $qInsertCostProve ="INSERT into app_costos_proveedor (id_proveedor,id_producto,id_moneda,costo,fecha) values('".$token2[0]."','".$idProducto."','".$moneda."','".$token2[1]."','".$fechaactual."')";
                    //$qInsertCostProve.=" values('".$token2[0]."','".$idProducto."','".$moneda."','".$token2[1]."','".$fechaactual."')";
                    //echo $qInsertCostProve;
                    $insertaproductoCosto = $this->queryArray($qInsertCostProve); 
                }
            }
        } 
        /*----------------------------------------------*/
        /*-------- Actualiza Cracteristicas --------------*/
        $queryDelCarac = "DELETE from app_producto_caracteristicas where id_producto=".$idProducto;
        $resDelCarac = $this->queryArray($queryDelCarac);
        /*-----------Caracteristicas inserts------------*/
        $toki =explode("-", $cartrt);
       // print_r($toki);
        foreach ($toki as $key2 => $value2) {
            if($key2 > 0){
                if($value2!='undefined'){
                   // echo $value2.'X';
                    $inCaracter = "INSERT INTO app_producto_caracteristicas (id_producto,id_caracteristica_padre) values ('".$idProducto."','".$value2."')";
                    $inCaracterResult = $this->queryArray($inCaracter);
                }
            }
        }    
        /*-----------------------------------------------*/
        /*------------- Actualzia Impuestos ------------------*/
        $queryDelImpue = "DELETE from app_producto_impuesto where id_producto=".$idProducto;
        $resDelImpue = $this->queryArray($queryDelImpue);
        /*-----------Lista de Impuestos inserts------------*/
       /* $teke =explode("-", $listaImpuestos);
        //print_r($teke);
        foreach ($teke as $key4 => $value4) {
            if($key4 > 0){
                if($value4!='undefined'){
                    $inImpu = "INSERT INTO app_producto_impuesto (id_producto,id_impuesto) values ('".$idProducto."','".$value4."')";
                    //echo $inImpu;
                    $inilistImp = $this->queryArray($inImpu);
                }
            }
        } */
        $teke =explode("-", $listaImpuestos);
        foreach ($teke as $key4 => $value4) {
            if($key4 > 0){
                if($value4 > 0 ){
                    $piki = explode('/',$value4);
                    if($piki[1]=='undefined'){
                        $piki[1] = 0;
                    }
                    $inImpu = "INSERT INTO app_producto_impuesto (id_producto,id_impuesto,formula) values ('".$idProducto."','".$piki[0]."','".$piki[1]."')";
                    //echo $inImpu;
                    $inilistImp = $this->queryArray($inImpu);
                }
            }
        }  
        /*------------------------------------------------*/  
       /*------------------------------------------------*/ 
        /*------------- Actualiza Comisiones ------------------*/
        /*-----------------------------------------------*/
        $sql = "DELETE FROM app_comision_productos WHERE id_producto='$idProducto'";
        $this->queryArray($sql);
        //$configComision,$precioBaseComision,$porcentajeBaseComision,$tipoComision
        
        if( $configComision != "3" ) {

            if ( $configComision == "1" ) {

                $sql = "INSERT INTO app_comision_productos (config_comision, id_producto,  porcentaje_comision, id_tipo_comision) VALUES ('$configComision' , '$idProducto'  , '$porcentajeBaseComision' , '$tipoComision' )";

            }
            else {
                $sql = "INSERT INTO app_comision_productos (config_comision, id_producto, id_costo_proveedor_comision, porcentaje_comision, id_tipo_comision) VALUES ('$configComision' , '$idProducto' , '$precioBaseComision' , '$porcentajeBaseComision' , '$tipoComision' )";
            }
            
        }
        else {
            $sql = "INSERT INTO app_comision_productos (config_comision) VALUES ('$configComision')";
        }
        $this->queryArray($sql);
        /*-----------------------------------------------*/

        return array('status' => true, 'idProducto' => $idProducto);      

    }
    public function tieneRegistros($idProducto){

        if($idProducto!=''){
           
            $selReg = "SELECT * from app_inventario_movimientos where id_producto=".$idProducto;
            $res = $this->queryArray($selReg);
            $editable = 1;

            if($res['total'] > 0){
                $editable = 0;
            }
        
            return array('editable' => $editable);
        }else{
          
            return array('editable' => 1);
        }



    }
    public function tieneCaract($idProducto){
        $sql = 'SELECT count(id) id from app_producto_caracteristicas WHERE id_producto = '.$idProducto.';';
        $res = $this->queryArray($sql);
        $count = $res['rows'][0]['id'];
        if($count > 0){
            return 1;
        }else{
            return 0;
        }
    }

    public function updatePrice($dato){
        //print_r($dato);
        $precio = str_replace('$','',$dato[4]);
        $dato[7] = str_replace('[','',$dato[7]);
        $dato[7] = str_replace(']','',$dato[7]);
      /*  $dato[1] = str_replace("'","&#39;",$dato[3]);
        $dato[1] = str_replace('"','&quot;',$dato[3]);
        $dato[2] = str_replace("'","&#39;",$dato[2]);
        $dato[2] = str_replace('"','&quot;',$dato[2]); */


        $updaPre = "UPDATE app_productos set precio='".str_replace(',', '', $precio)."', idempleado='".$dato[7]."' where id=".$dato[1];
        //echo $updaPre.'<br>';
        //exit();
        $update = $this->queryArray($updaPre);
        
        if($dato[6]!=''){
            $costo = str_replace('$','',$dato[5]);
            $updaCos = "UPDATE app_costos_proveedor set costo='".str_replace(',', '', $costo)."' where id_producto=".$dato[1]." and id_proveedor=".$dato[6];
            $update2 = $this->queryArray($updaCos);            
        }

    }
    public function cargaSaldosCxc($dato){

        date_default_timezone_set("Mexico/General");
        $fechaactual = date("Y-m-d H:i:s"); 

        $sel = "SELECT id from comun_cliente where nombre='".utf8_encode($dato[1])."';";
        //echo $sel.'<br>';
        $res = $this->queryArray($sel);

        if($res['total'] > 0){
            $inser = 'INSERT into cxc(fechacargo,fechavencimiento,idVenta,monto,saldoabonado,saldoactual,estatus,idCliente,concepto) values("'.$fechaactual.'","2017-12-31","1","'.$dato[2].'","0.00","0.00","0","'.$res['rows'][0]['id'].'","Carga de saldos")';
            echo $inser.'<br>';
            $resIn = $this->queryArray($inser);
        }
        //exit();
       return $resIn['insertId'];

    }

    public function guardarLay($dato)
    {
       /* $dato[1] = str_replace("'","&#39;",$dato[1]);
        $dato[1] = str_replace('"','&quot;',$dato[1]);
        $dato[2] = str_replace("'","&#39;",$dato[2]);
        $dato[2] = str_replace('"','&quot;',$dato[2]);
        $dato[4] = str_replace("'","&#39;",$dato[4]);
        $dato[4] = str_replace('"','&quot;',$dato[4]);
        $dato[5] = str_replace("'","&#39;",$dato[5]);
        $dato[5] = str_replace('"','&quot;',$dato[5]); */
        
       /* $myQuery2 = "ALTER TABLE app_productos AUTO_INCREMENT = 1";
        $this->query($myQuery2); */
        $insert  = 'INSERT into app_sat_divisiones(tipo,clave,descripcion_division) values("")';
        $this->queryArray($insert);
        $myQuery = "INSERT INTO `app_productos` (`id`, `codigo`, `nombre`, `precio`, `descripcion_corta`, `descripcion_larga`, `ruta_imagen`, `tipo_producto`, `maximos`, `minimos`, `departamento`, `familia`, `linea`, `id_tipo_costeo`, `id_moneda`, `id_clasificacion`, `inventariable`, `comision`, `tipo_comision`, `id_unidad_venta`, `series`, `lotes`, `pedimentos`, `status`, `id_unidad_compra`, `costo_servicio`, `formulaIeps`) VALUES (0, '".$dato[1]."', '".$dato[2]."', ".$dato[3].", '".$dato[4]."', '".$dato[5]."', 'images/productos/".$dato[6]."', ".$dato[7].", ".$dato[8].", ".$dato[9].", ".$dato[13].", ".$dato[14].", ".$dato[15].", ".$dato[16].", ".$dato[17].", 99, NULL, 0, 0, ".$dato[18].", ".$dato[19].", ".$dato[20].", ".$dato[21].", 1, '".$dato[22]."', ".$dato[23].", 0);
        ";



        if($idprod = $this->insert_id($myQuery))
        {
            if($dato[7] == 4 || $dato[7] == 5){
                $myQueryR = "INSERT INTO `com_recetas` (`id`, `nombre`, `status`) 
                VALUES ('".$idprod."', '".$dato[2]."', '1');";
                $this->query($myQueryR);
            }
			/* Guarda los campos para foodware
			============================================================================= */
			
				$vendible = ($dato[7] == 3) ? 0 : 1 ;
				$sql = "	INSERT INTO 
								app_campos_foodware(id_producto, vendible, rate)
							VALUES
								(".$idprod.", ".$vendible.", 1)";
		        $result_foodware = $this->query($sql);
		        
			/* FIN
			============================================================================= */
			
            //Guarda Impuestos
            if($dato[10] != '')
            {
                $myQuery = '';
                if(strpos($dato[10], ',') === false && intval($dato[10]))
                    $myQuery = "INSERT INTO app_producto_impuesto(id, id_producto, id_impuesto,formula) VALUES(0, $idprod, ".$dato[10].", 0);";
                if(strpos($dato[10], ',') !== false)
                {
                    $imps = explode(',',$dato[10]);
                    $myQuery = "INSERT INTO app_producto_impuesto(id, id_producto, id_impuesto,formula) VALUES";
                    for($i=0;$i<=count($imps)-1;$i++)
                    {
                        if($i>0)
                            $myQuery .= ",";
                        $myQuery .= "(0, $idprod, ".intval($imps[$i]).", 0)";
                    }
                    $myQuery .= ";";
                }
                if($myQuery != '')
                    $this->query($myQuery);
            }

            //Guarda Proveedores
            if($dato[11] != '')
            {
                $myQuery = '';
                if(strpos($dato[11], ',') === false && intval($dato[11]))
                {
                    $myQuery = "INSERT INTO app_producto_proveedor(id, id_producto, id_proveedor,id_unidad) VALUES(0, $idprod, ".$dato[11].", '".$dato[22]."');";
                    $myQuery2 = "INSERT INTO app_costos_proveedor(id,id_proveedor,id_producto,id_moneda,costo,fecha) VALUES(0,".$dato[11].",$idprod,".$dato[17].",".$dato[23].",'".date("Y-m-d H:i:s")."')";
                }
                if(strpos($dato[11], ',') !== false)
                {
                    $myQuery = "INSERT INTO app_producto_proveedor(id, id_producto, id_proveedor,id_unidad) VALUES";
                    $myQuery2 = "INSERT INTO app_costos_proveedor(id,id_proveedor,id_producto,id_moneda,costo,fecha) VALUES";
                    $provs = explode(',',$dato[11]);
                    for($i=0;$i<=count($provs)-1;$i++)
                    {
                        if($i>0)
                        {
                            $myQuery .= ",";
                            $myQuery2 .= ",";
                        }
                        $myQuery .= "(0, $idprod, ".intval($provs[$i]).", '".$dato[22]."')";
                        $myQuery2 .= "(0,".intval($provs[$i]).",$idprod,".$dato[17].",".$dato[23].",'".date("Y-m-d H:i:s")."')";
                    }
                    $myQuery .= ";";
                    $myQuery2 .= ";";
                }
                if($myQuery != '')
                {
                    $this->query($myQuery);
                }
                if($myQuery2 != '')
                {
                    $this->query($myQuery2);
                }
            }

            //Guarda Caracteristicas
            if($dato[12] != '')
            {
                $myQuery = '';
                if(strpos($dato[12], ',') === false && intval($dato[12]))
                    $myQuery = "INSERT INTO app_producto_caracteristicas(id, id_producto, id_caracteristica_padre) VALUES(0, $idprod, ".$dato[12].");";
                if(strpos($dato[12], ',') !== false)
                {
                    $myQuery = "INSERT INTO app_producto_caracteristicas(id, id_producto, id_caracteristica_padre) VALUES";
                    $caracs = explode(',',$dato[12]);
                    for($i=0;$i<=count($caracs)-1;$i++)
                    {
                        if($i>0)
                            $myQuery .= ",";
                        $myQuery .= "(0, $idprod, ".$caracs[$i].")";
                    }
                    $myQuery .= ";";
                }
                if($myQuery != '')
                    $this->query($myQuery);
            }


           // $this->multi_query($myQuery);
        }

        
    }

    public function unidad_medida($clave)
    {
        $clave = strtoupper($clave);
        $res = $this->query("SELECT id FROM app_unidades_medida WHERE clave = '$clave'");
        $res = $res->fetch_assoc();
        return $res['id'];
    }

    public function validarProductos($val)
    {
        $myQuery = "SELECT codigo, nombre FROM app_productos WHERE status = 1 GROUP BY codigo HAVING COUNT(codigo) > 1
                    UNION
                    SELECT codigo, nombre FROM app_productos WHERE status = 1 GROUP BY nombre HAVING COUNT(nombre) > 1;";
        $res = $this->query($myQuery);
        return $res;
    }

    public function borrar($val)
    {
        $myQuery = "DELETE FROM app_campos_foodware WHERE id_producto IN (SELECT id FROM app_productos WHERE id_clasificacion = $val);";
        if($this->query($myQuery))
        {

            $sql = "DELETE FROM app_producto_proveedor 
                    WHERE id_producto IN (SELECT id FROM app_productos WHERE id_clasificacion = $val);"; 
            $this->queryArray($sql);
            $sql = "DELETE FROM app_costos_proveedor 
                    WHERE id_producto IN (SELECT id FROM app_productos WHERE id_clasificacion = $val);";
            $this->queryArray($sql);
            $sql = "DELETE FROM app_producto_caracteristicas 
                    WHERE id_producto IN (SELECT id FROM app_productos WHERE id_clasificacion = $val);";
            $this->queryArray($sql);

            $sql = "DELETE FROM app_producto_impuesto 
                    WHERE id_producto IN (SELECT id FROM app_productos WHERE id_clasificacion = $val);";
            $this->queryArray($sql);

            $myQuery = "DELETE FROM app_productos WHERE id_clasificacion = $val;";
            $this->query($myQuery);

            $myQuery2 = "ALTER TABLE app_productos AUTO_INCREMENT = 1";
            $this->query($myQuery2);
        }
    }


    public function validaProveedor($prv)
    {
        $res = $this->query("SELECT idPrv FROM mrp_proveedor WHERE idPrv = $prv");
        return $res->num_rows;
    }

    public function traeCargados($clas)
    {
        $res = $this->query("SELECT id, codigo, nombre, precio, descripcion_corta FROM app_productos WHERE id_clasificacion = $clas");
        return $res;
    }

    public function inactivarProdLay($idProd,$num)
    {
        $myQuery = "UPDATE app_productos SET status = 0, id_clasificacion = $num WHERE id = $idProd";
        $this->query($myQuery);
        return $myQuery;
    }

    public function reactivarProdLay($idProd,$num)
    {
        $myQuery = "UPDATE app_productos SET status = 1, id_clasificacion = $num WHERE id = $idProd";
        $this->query($myQuery);
        return $myQuery;
    }

    public function confirmar($val)
    {
        $myQuery = "UPDATE app_productos SET id_clasificacion = NULL WHERE id_clasificacion = $val";
        $this->query($myQuery);
    }

    function buscarPrecioProveedor( $datos ){

        $sql = "SELECT  id_proveedor id, CONCAT( CONCAT(razon_social, '  $'), costo ) text
                FROM    app_costos_proveedor c
                INNER JOIN mrp_proveedor p ON c.id_proveedor = p.idPrv
                WHERE   id_producto = '{$datos['producto']}' AND razon_social LIKE '%{$datos['patronProveedor']}%'";

        $preciosProveedor = $this->queryArray($sql);

        return $preciosProveedor;
    }

    function comision( $idProducto ) {
        $sql = "SELECT * FROM app_comision_productos WHERE id_producto='$idProducto'";
        $res = $this->queryArray($sql);
        return $res['rows'][0];
    }
    public function politicas(){
        $sel = "SELECT * from app_politicas_tarjeta";
        $res = $this->queryArray($sel);
        return array('politicas' => $res['rows']);

    }
    public function divisionesSat($tipo){
        $sel = 'SELECT * from c_division where tipo='.$tipo;
        $res = $this->queryArray($sel);

        return array('divisiones' => $res['rows']);
    }
    public function gruposSat($div){
        $sel = 'SELECT * from c_grupo where iddiv='.$div;
        $res = $this->queryArray($sel);

        return array('grupos' => $res['rows']);
    }
    public function claseSat($div){
        /*$sql = "SELECT * from c_grupo where id_grupo=$div";
        $r = $this->queryArray($sql);*/
        $sel = 'SELECT * from c_clase where idgrupo='.$div;
        $res = $this->queryArray($sel);

        return array('clases' => $res['rows']);
    }
     public function claveSat($div){
        /*$sql = "SELECT * from c_clase where id_clase=$div";
        $r = $this->queryArray($sql);*/
        $sel = 'SELECT * from c_claveprodserv where idclase='.$div;
        $res = $this->queryArray($sel);

        return array('claves' => $res['rows']);
    }
    
    public function vinculacionMasivaSat($cadena,$clave,$division,$grupo,$clase){
        $cadena=trim($cadena,',');
        $cadena = explode(',', $cadena);
        //$delete = 'DELETE from app_producto_sucursal where id_sucursal='.$idSucursal;
        //$this->queryArray($delete);
        foreach ($cadena as $key => $value) {
            $update = 'UPDATE app_productos set clave_sat="'.$clave.'", division_sat="'.$division.'", grupo_sat="'.$grupo.'", clase_sat="'.$clase.'" where id='.$value;
            //echo $update;

            $this->queryArray($update);
        }

        return  array('estatus' => true );
    }
    public function vinculacionMasivaMonedero($cadena,$monedero){
        //echo $cadena;
        $cadena=trim($cadena,',');
        $cadena = explode(',', $cadena);
        $delete = 'DELETE from app_producto_politica where id_politica='.$monedero;
        $this->queryArray($delete);
        foreach ($cadena as $key => $value) {
            $insert = 'INSERT into app_producto_politica(id_producto,id_politica) values("'.$value.'","'.$monedero.'")';
            //echo $insert;
            $this->queryArray($insert);
        }

        return  array('estatus' => true );

    }
    public function prodDepa($depa,$familia,$linea){
        $filtro = '';
        if($depa!=0){
            $filtro .= ' and p.departamento='.$depa;
        }
        if($familia!=0){
            $filtro .= ' and p.familia='.$familia;
        }
        if($lina!=0){
            $filtro .= ' and p.linea='.$linea;
        }   

        $sel = "SELECT p.id,p.codigo,p.nombre,cl.c_claveprodserv clave_sat, d.nombre departamento, f.nombre familia, l.nombre linea 
                from app_productos p
                left join app_departamento d on p.departamento = d.id
                left join app_familia f on p.familia = f.id
                left join app_linea l on p.linea = l.id
                left join c_claveprodserv cl on p.clave_sat = cl.id
                where 1".$filtro;
                //echo $sel;
        $res = $this->queryArray($sel);
        
        return array('productos' => $res['rows'] );        
    }

    function explosion($idProducto){

        $myQuery="SELECT a.id_material as id, b.codigo, b.nombre, a.cantidad from app_producto_material a left join app_productos b on b.id=a.id_material
            where a.id_producto='$idProducto';";


        $prodsReq = $this->queryArray($myQuery);
        return $prodsReq;


    }
}
?>
