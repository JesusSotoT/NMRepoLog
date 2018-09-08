<?php
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ViajesModel extends Connection
{
	public function clientes(){
		$sql = "SELECT id, nombre FROM comun_cliente";
		$clientes = $this->queryArray($sql);
		return $clientes["rows"];
	}
	public function retiros($desde,$hasta){
		
		$filtro = "1 = 1 ";
		$desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

        if($desde!='' && $hasta!=''){
            $filtro .=' and r.fecha BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }

		$sql = "SELECT r.*, concat(e.nombre, ' ', e.apellido1, ' ', e.apellido2) empleado 
				FROM app_pos_retiro_caja r
				LEFT JOIN empleados e on e.idempleado = r.idempleado WHERE ".$filtro;
		$retiros = $this->queryArray($sql);
		return $retiros["rows"];
	}
	public function moneda(){
		$query = "SELECT * from cont_coin";
		$result = $this->queryArray($query);

		return $result['rows'];
	}
	public function formasDePago(){
		$query = "select * from forma_pago WHERE activo = 1 ORDER BY claveSat ASC ";
		$res = $this->queryArray($query);

		return array('formas' => $res['rows'] );
	}
	public function ventasIndex()
	{   
		//$result2 = $this->touchProducts();

		$result2  = '';
		$query3 = "SELECT * from accelog_usuarios";
		$result3 = $this->queryArray($query3);

		$query45 = "SELECT * from comun_cliente";
		$result5 = $this->queryArray($query45);

		//return $result['rows'];
		return array('productos' => $result2 ,  'usuarios' => $result3['rows'], 'clientes' => $result5['rows']);
							   

	}
	public function vendedores()
	{
		$sql = "SELECT idEmpleado idempleado, CONCAT( CONCAT ( CONCAT( CONCAT(nombre, ' ') , apellidos), ' | ') , nombreusuario) nombre
				FROM administracion_usuarios";
		$res = $this->queryArray($sql);
		return $res['rows'];
		# code...
	}
	public function sucursales()
	{
		$sql = "SELECT * from mrp_sucursal";
		$res = $this->queryArray($sql);
		return $res['rows'];
		# code...
	}
	public function buscar($desde, $hasta, $sucursal, $vendedor)
	{
		$sucursal = ($sucursal == 0 ? "" : $sucursal);
		$vendedor = ($vendedor == 0 ? "" : $vendedor);
		$sql = "SELECT	ve.idVenta ID, ve.fecha FECHA, ve.subtotal TARIFA, vi.comision COMISION, vi.cuota CUOTA, vi.rentabilidad MARCK_UP, (vi.rentabilidad + vi.comision + vi.cuota) BENEFICIO, ( (vi.rentabilidad + vi.comision + vi.cuota)/ve.subtotal ) PORCEN_BENEF, CONCAT( CONCAT ( CONCAT( CONCAT(nombre, ' ') , apellidos), ' | ') , nombreusuario) VENDEDOR
				FROM	app_viajes_reservacion vi
				INNER JOIN app_pos_venta ve ON vi.id_venta = ve.idVenta
				INNER JOIN administracion_usuarios em ON ve.idEmpleado = em.idempleado
				WHERE	em.idempleado LIKE '%$vendedor%' AND ve.idSucursal LIKE '%$sucursal%' AND ve.fecha BETWEEN '$desde' AND '$hasta';";
		$res = $this->queryArray($sql);
		return $res['rows'];
		# code...
	}
} 
?>