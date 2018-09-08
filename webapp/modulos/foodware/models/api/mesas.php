<?php

    //Cargar la clase de conexión padre para el modelo
    require_once("models/model_father.php");
    //Cargar los archivos necesarios

    class MesasModel extends Model
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

        public static function obtenerMesas($request)
        {
            /*$sql = "SELECT u.idempleado AS id, usuario, permisos, p.idperfil AS perfil FROM accelog_usuarios u INNER JOIN administracion_usuarios a ON u.idempleado = a.idempleado LEFT JOIN com_meseros m ON m.id_mesero = u.idempleado LEFT JOINm accelog_usuarios_per p ON p.idempleado = u.idempleado WHERE  u.idempleado = " . $request["id_mesero"]; $permisos = DB::queryArray($sql, array()); */

            $sucursal = "   SELECT mp.idSuc AS id FROM administracion_usuarios au 
                            INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
                            WHERE au.idempleado = " . $request['id_mesero'] . " 
                            LIMIT 1";

            $queryMesero =  "SELECT * FROM com_meseros 
                            WHERE id_mesero = '".$request["id_mesero"]."'";

            $sucursal = DB::queryArray($sucursal, array());
            $sucursal = $sucursal["registros"][0]["id"];

            $mesero = DB::queryArray($queryMesero, array());
            $mesero = $mesero["registros"][0];

            $objeto["noJuntas"] = 1;  

            $objeto['f_ini'] = $_SESSION['f_ini'];
            $objeto['f_fin'] = $_SESSION['f_fin'];       

            //Se valida que el mesero tenga Permisos y Asignaciones
            if($mesero["permisos"] != "" && $mesero['asignacion'] != "")
            {
                //Filtro de departamento
                $condicion = ' AND a.idDep="' . $request['id'] . '"';
                // Filtro de los permisos de Mesero
                $condicion .= ' AND (a.id_mesa IN(' . $mesero["permisos"] . ') OR a.tipo != 0)';
                // Filtra por las asignaciones del mesero
                $condicion .= ' AND a.id_mesa IN(' . $mesero['asignacion'] . ')';
                // Filtra para que no se muestren las mesas de servicio a domicilio y para llevar
                $condicion .= ($objeto['asignar'] == 1) ? ' AND a.tipo=0' : '';
            }else
            {
                return array("status" => true, "registros" => array(array()));
            }
            

            if(!$objeto["noJuntas"]){
            
            $sql = "SELECT a.id_mesa AS mesa, res.id as id_res, a.x, a.y, a.width as width_barra, s.nombre as sucursal, a.id_area, a.height as height_barra, b.nombre, b.idDep, e.personas, a.status as mesa_status, a.tipo, a.domicilio, a.idempleado, ad.nombreusuario AS mesero, a.notificacion, tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen,
                IF(GROUP_CONCAT(c.idmesa) is NULL, a.nombre, 
                (SELECT GROUP_CONCAT(d.nombre) FROM com_mesas d
                INNER JOIN com_union c ON c.idmesa=d.id_mesa
                WHERE c.idprincipal = a.id_mesa)) nombre_mesa,
                        if(GROUP_CONCAT(c.idmesa) is NULL,'',GROUP_CONCAT(c.idmesa)) 
                            idmesas, 
                        if(GROUP_CONCAT(d.personas) is NULL,'',GROUP_CONCAT(d.personas)) 
                            mpersonas, 
                        if(e.id is NULL,0,e.id) 
                            idcomanda FROM com_mesas a
                LEFT JOIN administracion_usuarios ad ON ad.idempleado = a.idempleado
                LEFT JOIN mrp_departamento b ON b.idDep = a.idDep 
                LEFT JOIN mrp_sucursal s ON s.idSuc = a.idSuc 
                LEFT JOIN com_union c ON c.idprincipal = a.id_mesa 
                LEFT JOIN com_mesas d ON d.id_mesa = c.idmesa 
                LEFT JOIN com_comandas e ON e.idmesa = a.id_mesa 
                AND e.status = 0 
                LEFT JOIN com_reservaciones res ON res.mesa = a.id_mesa 
                AND res.activo = 1
                AND (res.inicio >= '".$objeto['f_ini']."' 
                    AND res.inicio <= '".$objeto['f_fin']."')
                    JOIN com_tipo_mesas tm ON a.tipo_mesa = tm.id
                    WHERE a.status = 1
                    AND (a.id_mesa NOT IN(select idmesa from com_union) 
                    OR a.id_mesa IN(select idprincipal from com_union))" . $condicion . " 
                    AND a.id_dependencia = 0
                    AND a.idSuc = " . $sucursal ."
                    OR a.status = 4
                    AND (a.id_mesa NOT IN(select idmesa from com_union) 
                    OR a.id_mesa IN(select idprincipal from com_union))" . $condicion . " 
                    AND a.id_dependencia = 0
                    AND a.idSuc = " . $sucursal ."
                    GROUP BY a.id_mesa 
                    ORDER BY a.id_mesa asc";
        } else {
            $sql = "SELECT a.id_mesa AS mesa, a.x, a.y, a.width as width_barra, s.nombre as sucursal, a.id_area, a.height as height_barra, b.nombre, e.personas, a.status as mesa_status, a.domicilio, a.idempleado, ad.nombreusuario AS mesero, a.notificacion, a.nombre as nombre_mesa, if(e.id is NULL,0,e.id) idcomanda, tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen 
                FROM com_mesas a
                LEFT JOIN administracion_usuarios ad ON ad.idempleado = a.idempleado
                LEFT JOIN mrp_departamento b ON b.idDep = a.idDep 
                LEFT JOIN mrp_sucursal s ON s.idSuc = a.idSuc 
                LEFT JOIN com_mesas d ON d.id_mesa = a.id_mesa 
                LEFT JOIN com_comandas e ON e.idmesa = a.id_mesa 
                AND e.status = 0 JOIN com_tipo_mesas tm ON a.tipo_mesa = tm.id
                WHERE a.status = 1 
                AND a.tipo = 0 
                AND a.id_dependencia = 0
                " . $condicion . " 
                AND a.idSuc = " . $sucursal ."
                OR a.status = 4 
                and a.tipo = 0 
                AND a.id_dependencia = 0
                " . $condicion . " 
                AND a.idSuc = " . $sucursal ."
                GROUP BY a.id_mesa 
                ORDER BY a.id_mesa asc";
        }
        $resultado = DB::queryArray($sql, array());

        //cambiando los nulos para iOS
        $index = 0;
        foreach ($resultado["registros"] as $value) {
            if($value["personas"] == null){
                $resultado["registros"][$index]["personas"] = "0"; 
            }
            if($value["nombre_mesa"] == null){
                $resultado["registros"][$index]["nombre_mesa"] = ""; 
            }
            $index = $index + 1;
        }

        return $resultado;
        }

        public static function insertarComanda($request)
        {
            //print_r($request); exit();
            $idmesa = $request["id_mesa"];
            $iddeparment = $request["id_departamento"];
            $usuario = $request["id_mesero"];
    
            // Inserta la comanda en la BD
            date_default_timezone_set('America/Mexico_City');
            
            $fecha = date('Y-m-d H:i:s');
            $sql = "INSERT INTO com_comandas(id, idmesa, personas, status, tipo, codigo, timestamp, abierta, idempleado) 
                    VALUES ('','$idmesa','0','0','$iddeparment','','" . $fecha . "','3','" . $usuario . "');";
            $comanda = DB::queryArray($sql, array());
            //*************insert_id???????????

            // ** Consulta si es la comanda de la reservacion
            $sql = "SELECT * FROM com_reservaciones WHERE 1 = 1 AND '" . $fecha . "' BETWEEN inicio AND fin AND activo = 1;";
            $reservaciones = DB::queryArray($sql, array());

            // Si es la comanda actualiza la reservacion
            if (!empty($reservaciones['rows'])) {
                $sql = "UPDATE com_reservaciones SET activo = 1 WHERE id=" . $reservaciones['rows'][0]['id'];
                $update = DB::queryArray($sql, array());
            }
            // ** FIN Consulta si es la comanda de la reservacion
            // Agrega el codigo al a comanda
            if ($comanda["status"] && $comanda["total"] >= 1) {
                $size = 5 - strlen($comanda["id_insertado"]);
                $string = "";

                for ($i = 0; $i < $size; $i++)
                    $string .= "0";
                $string .= $comanda["id_insertado"];
                $sql = "UPDATE com_comandas SET codigo='COM" . $string . "' WHERE id = " . $comanda["id_insertado"];
                DB::queryArray($sql, array());
            }

            //** Guarda la actividad
            $sql = "INSERT INTO com_actividades (id, empleado, accion, fecha)
                    VALUES (''," . $usuario . ",'Abre comanda', '" . $fecha . "')";
            $actividad = DB::queryArray($sql, array());
       

            if($comanda["status"] && $comanda["total"] >= 1){
                $comanda["status"] = true;
                $comanda["registros"] =  json_decode('[{"id_comanda":"'. $comanda["id_insertado"] .'"}]');
            }else{
                $comanda["status"] = false;
                $comanda["mensaje"] = "No tiene opcionales";
            }

            return $comanda;
        }

        public static function insertarComensal($request)
        { 
            $idcomanda = $request["id_comanda"];
            $usuario = $request["id_mesero"];
            // ** inserta el primer comensal por default
            $sql = "UPDATE com_comandas SET personas = personas + 1, comensales = personas WHERE id=" . $idcomanda;
                    DB::queryArray($sql, array());

            //Obtiene el numero de personas
            $sql = "SELECT npersona FROM com_pedidos WHERE idcomanda = " . $idcomanda . " ORDER BY npersona DESC LIMIT 1";
            $persons = DB::queryArray($sql, array());
     
            $idperson = 0;
            if ($persons["total"] > 0) {
                $row = $persons["registros"]; 
                $sql = "INSERT INTO com_pedidos (id, idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales) 
                        VALUES (null, '$idcomanda', '0', '0', '" . ($row[0]['npersona'] + 1) . "', '0', '0', '', '')";
                $resultado  = DB::queryArray($sql, array());
              //print_r($row[0]['npersona']);exit();
                $idperson = ($row[0]['npersona'] + 1);
            } else {
                $sql = "INSERT INTO com_pedidos (id, idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales) 
                        VALUES (null,'$idcomanda','0','0','1','0','0','','')";
                $resultado = DB::queryArray($sql, array());
                $idperson = 1;
            }

            //** Guarda la actividad
            $fecha = date('Y-m-d H:i:s');

            $sql = "INSERT INTO com_actividades (id, empleado, accion, fecha)
                    VALUES (''," . $usuario . ",'Agrega persona', '" . $fecha . "')";
            $actividad = DB::queryArray($sql, array());


            if($resultado["status"] && $resultado["total"] >= 1){
                $resultado["status"] = true;
                $resultado["registros"] =  json_decode('[{"id_comensal":"'. $idperson .'"}]');
            }else{
                $resultado["status"] = false;
                $resultado["mensaje"] = "No inserto el comensal";
            }

            return $resultado;
        }

        public static function borrarComensal($request)
        { 
            $idcomanda = $request["id_comanda"];
            $usuario = $request["id_mesero"];
            $persons = $request["id_comensal"];
            // Actualiza la cantidad de personas en la mesa
            $sql = "UPDATE com_comandas SET personas = (personas-" . count(explode(',', $persons)) . ") WHERE id=" . $idcomanda;
            $person = DB::queryArray($sql, array());
            // Elimina los pedidos de la persona
            $sql = "DELETE FROM com_pedidos WHERE idcomanda = " . $idcomanda . " AND npersona in(" . $persons . ")";
            $person = DB::queryArray($sql, array());
            //** Guarda la actividad
            $fecha = date('Y-m-d H:i:s');
            // Valida que exista el empleado si no agrega un cero como id
            $sql = "INSERT INTO com_actividades (id, empleado, accion, fecha) 
                    VALUES (''," . $usuario . ",'Elimina persona', '" . $fecha . "')";
            $actividad = DB::queryArray($sql, array());

            return $person;
        }

        public static function cerrarComanda($request)
        {
            $idComanda = $request["id_comanda"];
            $rbandera = $request["id_bandera"];
            $usuario = $request["id_mesero"];
    
            // Inserta la comanda en la BD
            date_default_timezone_set('America/Mexico_City');
            
            $fecha = date('Y-m-d H:i:s');

            // Actualiza el estatus de la comanda para marcar como cerrada
            $sql = "UPDATE com_comandas SET status = 2, fin = '$fecha', individual = '" . $rbandera . "' WHERE id = " . $idComanda;
            $status = DB::queryArray($sql, array());

            // actividades
            $sql = "INSERT INTO com_actividades (id, empleado, accion, fecha)
                    VALUES (''," . $usuario . ",'Cierra comanda', '" . $fecha . "')";
            $actividad = DB::queryArray($sql, array());

            return $status;
        }

        public static function obtenerComensales($request){
            $idComanda = $request["id_comanda"];

            $sql = "SELECT npersona, COUNT(npersona) AS num_personas FROM com_pedidos 
                    WHERE idcomanda = " . $idComanda . " AND origen = 1 GROUP BY npersona ORDER BY npersona ASC";
            // return $sql;
            $comensales = DB::queryArray($sql, array());

            return $comensales;
        }

    }

?>
