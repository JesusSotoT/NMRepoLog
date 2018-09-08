<?php
 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ReordenModel extends Connection
{
	public function productos($tipo){
		$filtro = '';
        $tipoPro1         = implode(',', $tipo);
		
		if($tipoPro1!=""){
            if($tipoPro1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (tipo_producto IN ('.$tipoPro1.'))';
            }
        }

		$sql = "SELECT id, codigo, nombre from app_productos where status = 1 ".$filtro.";";
		$result = $this->queryArray($sql);
		return $result["rows"];
	}
	
	public function sucursales(){
		$sql = "SELECT idSuc, nombre from mrp_sucursal;";
		$result = $this->queryArray($sql);
		return $result["rows"];
	}

	public function ventas($desde,$hasta,$producto,$sucursal){
		$filtro = "1 = 1 ";
		$desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

		if($desde!='' && $hasta!=''){
            $filtro .=' and pv.fecha BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }

        $producto1      = implode('","', $producto);
		if($producto1!=""){
            if($producto1=='0'){
                $filtro .='';               
            }else{
                $filtro .=' and (pvp.idProducto IN ("'.$producto1.'"))';                
            }
        }

        $sucursal1      = implode('","', $sucursal);
		if($sucursal1!=""){
            if($sucursal1=='0'){
                $filtro .='';               
            }else{
                $filtro .=' and (pv.idSucursal IN ("'.$sucursal1.'"))';                
            }
        }
        /*
		echo $sql = "SELECT e.fecha_envio, ed.id_producto, ed.cantidad, ed.id_almacen, al.id_sucursal FROM app_envios e
				LEFT JOIN app_envios_datos ed on ed.id_envio = e.id
				LEFT JOIN app_almacenes al on al.id = ed.id_almacen
				WHERE ".$filtro."
				ORDER BY al.id_sucursal, ed.id_producto, e.fecha_envio; ";
		*/

		$sql = "SELECT pv.fecha, pvp.idProducto id_producto, pvp.cantidad, pv.idSucursal id_sucursal 
		FROM app_pos_venta_producto pvp		
		left join app_pos_venta pv on pv.idVenta = pvp.idVenta
		WHERE ".$filtro."
		ORDER BY pv.idSucursal, pvp.idProducto, pv.fecha; ";

		$result = $this->queryArray($sql);
		return $result["rows"];
	}

	public function movimientos($producto,$suc,$tipo){

		$filtro = '';
		$suc1      = implode('","', $suc);
		if($suc1!=""){
            if($suc1=='0'){
                $filtro .='';               
            }else{
                $filtro .=' and (alr.id_sucursal IN ("'.$suc1.'"))';                
            }
        }


		$producto1      = implode('","', $producto);
		if($producto1!=""){
            if($producto1=='0'){
                $filtro .='';               
            }else{
                $filtro .=' and (p.id IN ("'.$producto1.'"))';                
            }
        }
		
		$tipoPro1         = implode(',', $tipo);
		if($tipoPro1!=""){
            if($tipoPro1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (p.tipo_producto IN ('.$tipoPro1.'))';
            }
        }


		$sql = "(SELECT m.id, p.nombre, p.codigo,  m.fecha, m.cantidad, m.id_producto, oo.id idorigen, dd.id iddestino, m.id_almacen_destino as aux, 0 as traspasoaux, rr.codigo_sistema,   
			(select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, 
			(select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP, p.minimos, s.nombre sucursal
		                        from app_inventario_movimientos m
		                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
		                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
		                                            left join app_almacenes rr on rr.id = oo.id
		                                            left join app_productos p on p.id = m.id_producto
		                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
		                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
		                                            left join mrp_sucursal s on s.idSuc = alr.id_sucursal
		                         
		                        where m.tipo_traspaso = 0 ".$filtro.")
		union all                     
		(SELECT m.id, p.nombre, p.codigo,  m.fecha, m.cantidad, m.id_producto, 
		                        oo.id idorigen, dd.id iddestino, m.id_almacen_origen as aux, 1 as traspasoaux, rr.codigo_sistema,    
		                        (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, 
		             (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP, p.minimos, s.nombre sucursal
		                        from app_inventario_movimientos m
		                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
		                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
		                                            left join app_almacenes rr on rr.id = dd.id
		                                            left join app_productos p on p.id = m.id_producto
		                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
		                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
		                                            left join mrp_sucursal s on s.idSuc = alr.id_sucursal
		                         
		                        where m.tipo_traspaso = 1 ".$filtro.")
		union all
		(SELECT m.id, p.nombre, p.codigo,  m.fecha, m.cantidad, m.id_producto, 
		                        oo.id idorigen, dd.id iddestino, m.id_almacen_origen as aux, 0 as traspasoaux, rr.codigo_sistema,  
		                        (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, 
		                   (select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP, p.minimos, s.nombre sucursal
		                        from app_inventario_movimientos m                                             
		                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
		                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
		                                            left join app_almacenes rr on rr.id = m.id_almacen_origen
		                                            left join app_productos p on p.id = m.id_producto
		                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
		                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
		                                            left join mrp_sucursal s on s.idSuc = alr.id_sucursal
		                         
		                        where m.tipo_traspaso = 2 ".$filtro.")
		union all
		(SELECT m.id, p.nombre, p.codigo,  m.fecha,  m.cantidad, m.id_producto, 
		                        oo.id idorigen,  dd.id iddestino, m.id_almacen_destino as aux, 1 as traspasoaux, rr.codigo_sistema,    
		                        (select nombre from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) nombreAlmacen, 
		               			(select id from app_almacenes where codigo_sistema = (SUBSTRING_INDEX(rr.codigo_sistema,'.',1))) almacenRR, alr.id_sucursal, substring(alr.codigo_sistema,1,5) almP, p.minimos, s.nombre sucursal
		                        from app_inventario_movimientos m                                            
		                                            left join app_almacenes oo on oo.id = m.id_almacen_origen
		                                            left join app_almacenes dd on dd.id = m.id_almacen_destino
		                                            left join app_almacenes rr on rr.id = m.id_almacen_destino
		                                            left join app_productos p on p.id = m.id_producto
		                                            left join app_almacenes x on x.id = left(rr.codigo_sistema,1)
		                                            left join app_almacenes alr on alr.codigo_sistema = rr.codigo_sistema
		                                            left join mrp_sucursal s on s.idSuc = alr.id_sucursal
		                         
		                        where m.tipo_traspaso = 2 ".$filtro.")
                        ORDER BY id_sucursal, codigo, fecha ASC;";
		$result = $this->queryArray($sql);
		return $result["rows"];
	}

} 
?>