<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ReporteModel extends Connection
{
   
    public function indexGridProductos(){
        $query = "SELECT * from app_productos order by id asc";
        $rest = $this->queryArray($query);

        return $rest['rows'];
    }
    public function buscaClientes($term) {
        /*obtiene los clientes*/
        $queryClientes = "SELECT  id,nombre ";
        $queryClientes .= " FROM comun_cliente ";
        $queryClientes .= " WHERE nombre like '%" . $term . "%' order by nombre desc ";

        $result = $this->queryArray($queryClientes);
        //print_r($result["rows"]);
        return $result["rows"];

    }
    public function filtros(){
        $query1 = "SELECT * from mrp_sucursal";
        $res1 = $this->queryArray($query1);

        return array('sucursales' => $res1['rows']);
    }
    public function repProductos($desde,$hasta,$sucursal,$orden){
        $inicio = $desde;
        $fin = $hasta;
        //$filtro=1;
        //echo 'inicioi='.$inicio.' hasta='.$fin;
        if($fin!="")
        {
            list($a,$m,$d)=explode("-",$fin);
            $fin=$a."-".$m."-".((int)$d+1);
        }


        if($inicio!="" && $fin=="")
        {
            $filtro.=" and fecha >= '".$inicio."' ";   
        }
        if($fin!="" && $inicio=="")
        {
            $filtro.=" and fecha <= '".$fin."' ";
        }
        if($inicio!="" && $fin!="")
        {
            $filtro.=" and fecha <= '".$fin."' and   fecha >= '".$inicio."' "; 
        }


        if($inicio!="" && $fin=="")
        {
            $filtro2.=" and fecha >= '".$inicio."' ";   
        }
        if($fin!="" && $inicio=="")
        {
            $filtro2.=" and fecha <= '".$fin."' ";
        }
        if($inicio!="" && $fin!="")
        {
            $filtro2.=" and fecha <= '".$fin."' and   fecha >= '".$inicio."' "; 
        }

        if($sucursal!=0){
            $filtro.=' and v.idSucursal="'.$sucursal.'"';
            $filtro2.=' and v.idSucursal="'.$sucursal.'"';
        }

        $sel = 'SELECT p.nombre as label , sum(cantidad) as value, sum(vp.subtotal) as subtotal, sum(vp.impuestosproductoventa) as impuestos, sum(vp.total) as total, s.nombre as sucursal';
        $sel.= ' from app_pos_venta_producto vp';
        $sel.= ' INNER JOIN app_productos p ON p.id = vp.idProducto';
        $sel.= ' INNER JOIN app_pos_venta v on v.idVenta=vp.idVenta';
        $sel.= ' INNER JOIN accelog_usuarios u on u.idempleado = v.idEmpleado';
        $sel.= ' inner join mrp_sucursal s on s.idSuc=v.idSucursal';
        $sel.= ' where v.estatus=1 '.$filtro;
        $sel.= ' group by idProducto';
        $sel.= ' order by total desc';
        //$sel.= ' limit 10';
    
        $resGra = $this->queryArray($sel);

        ///monto total de ventas 
        $selectVentas ="SELECT 
        v.idVenta as folio,
        v.fecha as fecha,
        v.envio as envio, 
        CASE WHEN c.nombre IS NOT NULL 
        THEN c.nombre
        ELSE 'Publico general'
        END AS cliente,
        e.usuario as empleado,
        s.nombre as sucursal,
        CASE WHEN v.estatus =1 
        THEN 'Activa'
        ELSE 'Cancelada'
        END AS estatus,
        v.montoimpuestos as iva,
        ROUND((vp.total),2) as monto 
        from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  accelog_usuarios e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal LEFT JOIN   app_pos_venta_producto AS vp ON vp.idVenta = v.idVenta where  v.estatus =1  ".$filtro." order by folio desc" ;
       
        $resultVentas = $this->queryArray($selectVentas);


        $sel1 = 'SELECT p.nombre as label , sum(cantidad) as value, sum(vp.subtotal) as subtotal, sum(vp.impuestosproductoventa) as impuestos, ROUND(ROUND(sum(vp.total),2),2) as value2';
        $sel1.= ' from app_pos_venta_producto vp';
        $sel1.= ' INNER JOIN app_productos p ON p.id = vp.idProducto';
        $sel1.= ' INNER JOIN app_pos_venta v on v.idVenta=vp.idVenta';
        $sel1.= ' INNER JOIN accelog_usuarios u on u.idempleado = v.idEmpleado';
        $sel1.= ' inner join mrp_sucursal s on s.idSuc=v.idSucursal';
        $sel1.= ' where v.estatus=1 '.$filtro;
        $sel1.= ' group by idProducto';
        $sel1.= ' order by value desc';
        $sel1.= ' limit 10';
        //echo $sel1;
        $resGraD = $this->queryArray($sel1);

        //exit();
        $sel2 = 'SELECT v.fecha as y, ROUND(sum(ROUND(v.monto,2)),2) as a';
        $sel2.= ' from app_pos_venta v';
        //$sel2.= ' INNER JOIN app_pos_venta_producto vp on v.idVenta=vp.idVenta';
        $sel2.= ' where v.estatus=1 '.$filtro2.' ';
        $sel2.= ' group by '.$orden.'(v.fecha)';
        //echo $sel2;
        //exit();
        $resGra2 = $this->queryArray($sel2);


        return array('productos' => $resGra['rows'], 'dona' => $resGraD['rows'], 'linea' => $resGra2['rows'], 'ventasTotal'=> $resultVentas['rows'] );
    }


    public function repFormaDePago($desde,$hasta,$sucursal,$orden){
        $inicio = $desde;
        $fin = $hasta;
        //$filtro=1;
        //echo 'inicioi='.$inicio.' hasta='.$fin;
        if($fin!="")
        {
            list($a,$m,$d)=explode("-",$fin);
            $fin=$a."-".$m."-".((int)$d+1);
        }


        if($inicio!="" && $fin=="")
        {
            $filtro.=" and fecha >= '".$inicio."' ";   
        }
        if($fin!="" && $inicio=="")
        {
            $filtro.=" and fecha <= '".$fin."' ";
        }
        if($inicio!="" && $fin!="")
        {
            $filtro.=" and fecha <= '".$fin."' and   fecha >= '".$inicio."' "; 
        }


        if($inicio!="" && $fin=="")
        {
            $filtro2.=" and fecha >= '".$inicio."' ";   
        }
        if($fin!="" && $inicio=="")
        {
            $filtro2.=" and fecha <= '".$fin."' ";
        }
        if($inicio!="" && $fin!="")
        {
            $filtro2.=" and fecha <= '".$fin."' and   fecha >= '".$inicio."' "; 
        }

        if($sucursal!=0){
            $filtro.=' and v.idSucursal="'.$sucursal.'"';
            $filtro2.=' and v.idSucursal="'.$sucursal.'"';
        }

        $sel ='SELECT v.fecha,fp.nombre as label , IF(fp.claveSat = "01", ROUND(sum((p.monto - v.cambio)),2) , ROUND(sum(p.monto ),2)) as value, s.nombre as sucursal';
        $sel.=' from app_pos_venta_pagos as p';
        $sel.=' INNER JOIN app_pos_venta v on v.idVenta=p.idVenta';
        $sel.=' INNER JOIN accelog_usuarios u on u.idempleado = v.idEmpleado';
        $sel.=' inner join forma_pago fp on fp.idFormapago=p.idFormapago';
        $sel.=' inner join mrp_sucursal s on s.idSuc=v.idSucursal';
        $sel.=' where v.estatus=1'.$filtro;
        $sel.=' group by fp.nombre';
        $sel.=' order by value desc';
        //$sel.= ' limit 10';
        //echo $sel.'///';
        //exit();
        $resGra = $this->queryArray($sel);

       /* $sel1 = 'SELECT p.nombre as label , sum(cantidad) as value2, sum(vp.subtotal) as subtotal, sum(vp.impuestosproductoventa) as impuestos, ROUND(ROUND(sum(vp.total),2),2) as value';
        $sel1.= ' from app_pos_venta_producto vp';
        $sel1.= ' INNER JOIN app_productos p ON p.id = vp.idProducto';
        $sel1.= ' INNER JOIN app_pos_venta v on v.idVenta=vp.idVenta';
        $sel1.= ' INNER JOIN accelog_usuarios u on u.idempleado = v.idEmpleado';
        $sel1.= ' inner join mrp_sucursal s on s.idSuc=v.idSucursal';
        $sel1.= ' where v.estatus=1 '.$filtro;
        $sel1.= ' group by idProducto';
        $sel1.= ' order by total desc';
        $sel1.= ' limit 10';
        //echo $sel1;
        $resGraD = $this->queryArray($sel1); */
                ///monto total de ventas 
        $selectVentas ="SELECT 
        v.idVenta as folio,
        v.fecha as fecha,
        v.envio as envio, 
        CASE WHEN c.nombre IS NOT NULL 
        THEN c.nombre
        ELSE 'Publico general'
        END AS cliente,
        e.usuario as empleado,
        s.nombre as sucursal,
        CASE WHEN v.estatus =1 
        THEN 'Activa'
        ELSE 'Cancelada'
        END AS estatus,
        v.montoimpuestos as iva,
        ROUND((vp.total),2) as monto 
        from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  accelog_usuarios e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal LEFT JOIN   app_pos_venta_producto AS vp ON vp.idVenta = v.idVenta where  v.estatus =1  ".$filtro." order by folio desc" ;
       
        $resultVentas = $this->queryArray($selectVentas);

        //exit();
        $sel2 = 'SELECT v.fecha as y,fp.nombre  , sum(p.monto) as a, s.nombre as sucursal ';
        $sel2.= ' from app_pos_venta_pagos as p'; 
        $sel2.= ' INNER JOIN app_pos_venta v on v.idVenta=p.idVenta'; 
        $sel2.= ' INNER JOIN accelog_usuarios u on u.idempleado=v.idEmpleado'; 
        $sel2.= ' inner join forma_pago fp on fp.idFormapago=p.idFormapago'; 
        $sel2.= ' inner join mrp_sucursal s on s.idSuc=v.idSucursal'; 
        $sel2.= ' where v.estatus=1 '.$filtro2; 
       // $sel2.= ' group by fp.nombre'; 
        $sel2.= ' group by '.$orden.'(v.fecha)';
     
        //echo $sel2;
        //exit();
        /*$sel2 = 'SELECT v.fecha as y, ROUND(sum(ROUND(v.monto,2)),2) as a';
        $sel2.= ' from app_pos_venta v';
        //$sel2.= ' INNER JOIN app_pos_venta_producto vp on v.idVenta=vp.idVenta';
        $sel2.= ' where v.estatus=1 '.$filtro2.' ';
        $sel2.= ' group by '.$orden.'(v.fecha)'; */
        //echo $sel2;
        //exit();
        $resGra2 = $this->queryArray($sel2);


        return array('formasPago' => $resGra['rows'], 'dona' => $resGra['rows'], 'linea' => $resGra2['rows'], 'ventasTotal'=> $resultVentas['rows'] );
    }

    public function repEmpleadoVenta($desde,$hasta,$sucursal,$orden){
        $inicio = $desde;
        $fin = $hasta;
        //$filtro=1;
        //echo 'inicioi='.$inicio.' hasta='.$fin;
        if($fin!="")
        {
            list($a,$m,$d)=explode("-",$fin);
            $fin=$a."-".$m."-".((int)$d+1);
        }


        if($inicio!="" && $fin=="")
        {
            $filtro.=" and fecha >= '".$inicio."' ";   
        }
        if($fin!="" && $inicio=="")
        {
            $filtro.=" and fecha <= '".$fin."' ";
        }
        if($inicio!="" && $fin!="")
        {
            $filtro.=" and fecha <= '".$fin."' and   fecha >= '".$inicio."' "; 
        }


        if($inicio!="" && $fin=="")
        {
            $filtro2.=" and fecha >= '".$inicio."' ";   
        }
        if($fin!="" && $inicio=="")
        {
            $filtro2.=" and fecha <= '".$fin."' ";
        }
        if($inicio!="" && $fin!="")
        {
            $filtro2.=" and fecha <= '".$fin."' and   fecha >= '".$inicio."' "; 
        }

        if($sucursal!=0){
            $filtro.=' and v.idSucursal="'.$sucursal.'"';
            $filtro2.=' and v.idSucursal="'.$sucursal.'"';
        }

        $sel = 'SELECT u.usuario as label, round(sum(v.monto),2) as value, v.fecha as y, round(sum(v.monto),2) as a
                from app_pos_venta v
                left join accelog_usuarios u on u.idempleado=v.idEmpleado
                where v.estatus=1 '.$filtro.'
                 group by u.usuario;';        
        $resGra = $this->queryArray($sel);

        $selectVentas ="SELECT 
        v.idVenta as folio,
        v.fecha as fecha,
        v.envio as envio, 
        CASE WHEN c.nombre IS NOT NULL 
        THEN c.nombre
        ELSE 'Publico general'
        END AS cliente,
        e.usuario as empleado,
        s.nombre as sucursal,
        CASE WHEN v.estatus =1 
        THEN 'Activa'
        ELSE 'Cancelada'
        END AS estatus,
        v.montoimpuestos as iva,
        ROUND((vp.total),2) as monto 
        from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  accelog_usuarios e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal LEFT JOIN   app_pos_venta_producto AS vp ON vp.idVenta = v.idVenta where  v.estatus =1  ".$filtro." order by folio desc" ;
       
        $resultVentas = $this->queryArray($selectVentas); 

        //exit();
        /*$sel2 = 'SELECT v.fecha as y,fp.nombre  , sum(p.monto) as a, s.nombre as sucursal ';
        $sel2.= ' from app_pos_venta_pagos as p'; 
        $sel2.= ' INNER JOIN app_pos_venta v on v.idVenta=p.idVenta'; 
        $sel2.= ' INNER JOIN accelog_usuarios u on u.idempleado=v.idEmpleado'; 
        $sel2.= ' inner join forma_pago fp on fp.idFormapago=p.idFormapago'; 
        $sel2.= ' inner join mrp_sucursal s on s.idSuc=v.idSucursal'; 
        $sel2.= ' where v.estatus=1 '.$filtro2; 
       // $sel2.= ' group by fp.nombre'; 
        $sel2.= ' group by '.$orden.'(v.fecha)';

        $resGra2 = $this->queryArray($sel2); */


        return array('empleadoVenta' => $resGra['rows'], 'dona' => $resGra['rows'], 'linea' => $resGra['rows'], 'ventasTotal'=> $resultVentas['rows'] );
    }    

    private static function filtrarFecha($campo, $inicio, $fin) {
        $filtro = "";

        if(preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$inicio) && 
            !preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$fin))
        {
            $filtro.="$campo >= $inicio";   
        }
        elseif(!preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$inicio) && 
            preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$fin))
        {
            $filtro.="$campo <= $fin";
        }
        elseif(preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$inicio) && 
            preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$fin))
        {
            $filtro.="$campo BETWEEN '$inicio' AND '$fin'"; 
        }
        elseif(!preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$inicio) && 
            !preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$fin))
        {
            $filtro.="0 = 0"; 
        }
        return $filtro;
    }

    private static function filtrarSucursal($campo, $nombre) {
        $filtro ="";

        if($nombre != 0) {
            $filtro = "$campo = '$nombre'";
        }
        else{
            $filtro = "0 = 0";
        }
        return $filtro;
    }

    public function repDepartamento($desde, $hasta, $sucursal, $orden){
        $fecha = ReporteModel::filtrarFecha("v.fecha", $desde, $hasta);
        $suc = ReporteModel::filtrarSucursal("v.idSucursal", $sucursal);

        $sql = "SELECT  IF(d.nombre  IS NOT NULL, d.nombre, 'Sin departamento') AS label, 
                        SUM(vp.total) AS value 
                FROM    app_pos_venta AS v 
                LEFT JOIN   app_pos_venta_producto AS vp ON vp.idVenta = v.idVenta
                LEFT JOIN   app_productos AS p ON vp.idProducto = p.id
                LEFT JOIN   app_departamento AS d ON p.departamento = d.id
                WHERE   v.estatus = 1 AND
                        ( $suc ) AND 
                        ( $fecha )
                GROUP BY    label
                ORDER BY    v.fecha";

        return $this->queryArray($sql);
    }

    public function repFamilia($desde, $hasta, $sucursal, $orden){
        $fecha = ReporteModel::filtrarFecha("v.fecha", $desde, $hasta);
        $suc = ReporteModel::filtrarSucursal("v.idSucursal", $sucursal);

        $sql = "SELECT  IF(f.nombre  IS NOT NULL, f.nombre, 'Sin familia') AS label, 
                        SUM(vp.total) AS value 
                FROM    app_pos_venta AS v 
                LEFT JOIN   app_pos_venta_producto AS vp ON vp.idVenta = v.idVenta
                LEFT JOIN   app_productos AS p ON vp.idProducto = p.id
                LEFT JOIN   app_familia AS f ON p.departamento = f.id 
                WHERE   v.estatus = 1 AND
                        ( $suc ) AND 
                        ( $fecha )
                GROUP BY    label
                ORDER BY    v.fecha";

        return $this->queryArray($sql);
    }

    public function repLinea($desde, $hasta, $sucursal, $orden){
        $fecha = ReporteModel::filtrarFecha("v.fecha", $desde, $hasta);
        $suc = ReporteModel::filtrarSucursal("v.idSucursal", $sucursal);

        $sql = "SELECT  IF(l.nombre  IS NOT NULL, l.nombre, 'Sin linea') AS label, 
                        SUM(vp.total) AS value 
                FROM    app_pos_venta AS v 
                LEFT JOIN   app_pos_venta_producto AS vp ON vp.idVenta = v.idVenta
                LEFT JOIN   app_productos AS p ON vp.idProducto = p.id
                LEFT JOIN   app_linea AS l ON p.departamento = l.id 
                WHERE   v.estatus = 1 AND 
                        ( $suc ) AND 
                        ( $fecha )
                GROUP BY    label
                ORDER BY    v.fecha";

        return $this->queryArray($sql);
    }

///////////////// ******** ---- 			listar_ventas_cliente_producto			------ ************ //////////////////
//////// Lista las ventas del cliente por los productos
	// Como parametros puede recibir:
		// f_ini -> Fecha de inicio
		// f_fin -> Fecha final
		// sucursal -> ID de las sucursal
		// graficar -> 1 -> dia, 2 -> semana, 3 -> mes, 4 -> año

	function listar_ventas_cliente_producto($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= (!empty($objeto['sucursal'])) ? 
			' AND v.idSucursal = ' . $objeto['sucursal']: '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND v.fecha BETWEEN \'' . $objeto['f_ini'] . ' 00:01:00\' AND \'' . $objeto['f_fin'] . ' 23:59:00\'' : '';
	// Agrupa la consulta por los parametros indicados si existe, si no la agrupa por id
		$condicion .= (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] : ' GROUP BY c.id, pv.idProducto';

		$sql = "SELECT
					IF(c.nombre IS NOT NULL, c.nombre, 'Publico General') nombre, COUNT(v.idVenta) AS ventas, SUM(pv.cantidad) AS cantidad, p.nombre AS producto, 
					SUM(pv.total) AS monto, v.fecha, s.nombre AS sucursal
				FROM
                    app_pos_venta v
                    
                LEFT JOIN
                    comun_cliente c
                    ON
                        v.idCliente = c.id
				LEFT JOIN
						app_pos_venta_producto pv
					ON
						pv.idVenta = v.idVenta
				LEFT JOIN
						mrp_sucursal s
					ON
						s.idSuc = v.idSucursal
				LEFT JOIN
						app_productos p
					ON
						p.id = pv.idProducto
				WHERE
					1 = 1 and v.estatus = 1 ".
				$condicion."
				ORDER BY
					c.nombre, cantidad DESC, producto";
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN listar_ventas_cliente_producto		------ ************ //////////////////
    
} ///fin de la clase
?>