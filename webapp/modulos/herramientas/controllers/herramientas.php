<?php
require ('common.php');
if(array_key_exists("api", $_REQUEST)){		
	require ("../webapp/modulos/herramientas/models/herramientas.php");
} else {
	require ("models/herramientas.php");
}

class herramientas extends Common {
	public $herramientasModel; 

	function __construct() {
		$this -> herramientasModel = new herramientasModel();
	}
	
///////////////// ******** ---- 				vista_mudar							------ ************ //////////////////
//////// Carga la vista para mudar instancias
	// Como parametros recibe:
	
	function vista_mudar($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Cambia la BD
		session_start();
		$_SESSION['conexion_externa']['base'] = 'netwarstore';
		$_SESSION['conexion_externa']['usuario'] = 'nmdevel';
		$_SESSION['conexion_externa']['pass'] = 'nmdevel';
		$_SESSION['conexion_externa']['servidor'] = 'nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com';
		
	// Consulta los empleados y los regresa en un array
		$instancias = $this -> herramientasModel -> listar_instancias($objeto);
		$instancias = $instancias['rows'];
		
		$_SESSION['conexion_externa'] = '';
		unset($_SESSION['conexion_externa']);
		
		require ("views/vista_mudar.php");
	}

///////////////// ******** ---- 				FIN vista_mudar						------ ************ //////////////////

///////////////// ******** ---- 				mudar_instancia						------ ************ //////////////////
//////// Muda la informacion de una instancia vieja a una nueva
	// Como parametros puede recibir:
		// instancia_vieja -> ID de la intancia vieja
		// instancia_nueva -> ID de la intancia nueva
		// proveedores -> true si se den de mudar los proveedores, false si no
		// productos -> true si se den de mudar los productos, false si no
		// unidades -> true si se den de mudar las unidades de medida, false si no
	
	function mudar_instancia($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$resp['status'] = 1;
		
	// Cambia la BD
		session_start();
		$_SESSION['conexion_externa']['base'] = $objeto['instancia_nueva'];
		$_SESSION['conexion_externa']['usuario'] = 'nmdevel';
		$_SESSION['conexion_externa']['pass'] = 'nmdevel';
		$_SESSION['conexion_externa']['servidor'] = 'nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com';
		
		$sql = "SELECT * FROM {$objeto['instancia_vieja']}.netwarelog_version";
		$tmp = $this -> herramientasModel -> queryArray($sql);
		$objeto['version'] = $tmp['rows'][0]['version'];
		//var_dump($objeto);die;
	// Valida que la tabla de productos esta vacia
		if ($objeto['productos'] != "false") {
			$productos = $this -> herramientasModel -> listar_productos($objeto);
			
			if ($productos['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de productos ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de productos se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_productos($objeto);
			}
		}
		
	// Valida que la tabla de unidades de medida esta vacia
		if ($objeto['unidades'] != "false") {
			$unidades_medida = $this -> herramientasModel -> listar_unidades_medida($objeto);
			
			// if ($unidades_medida['total'] > 0) {
			// 	$resp['mensaje'].= 'w#La tabla unidades de medida ya contiene informacion<br>';
			// }else{
				$resp['mensaje'].= 's#La tabla unidades de medida se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_unidades($objeto);
			// }
			
			
		}
		
	// Valida que la tabla de proveedores esta vacia
		if ($objeto['proveedores'] != "false") {
			$proveedores = $this -> herramientasModel -> listar_proveedores($objeto);
			
			if ($proveedores['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de proveedores ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de proveedores se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_proveedores($objeto);
			}
			
			
		}

	// Valida que la tabla de clientes esta vacia
		if ($objeto['clientes'] != "false") {
			$clientes = $this -> herramientasModel -> listar_clientes($objeto);
			
			if ($clientes['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de clientes ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de clientes se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_clientes($objeto);
			}
			
			
		}
	// Valida que la tabla de cuentas por cobrar esta vacia
		if ($objeto['cxc'] != "false") {
			$cxc = $this -> herramientasModel -> listar_cxc($objeto);
			
			if ($cxc['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de cuentas por cobrar ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de cuentas por cobrar se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_cxc($objeto);
			}
			
			
		}
	// Valida que la tabla de cuentas por cobrar esta vacia
		if ($objeto['cxp'] != "false") {
			$cxc = $this -> herramientasModel -> listar_cxp($objeto);
			
			if ($cxc['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de cuentas por pagar ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de cuentas por pagar se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_cxp($objeto);
			}
			
			
		}
	//ch@
	// Valida que la tabla de sucursales esta vacia
		if ($objeto['suc'] != "false") {
			$suc = $this -> herramientasModel -> listar_suc($objeto);
			
			if ($suc['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de sucursales ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de sucursales se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_suc($objeto);
			}
			
			
		}
	// Valida que la tabla de almacenes esta vacia
		if ($objeto['alm'] != "false") {
			$alm = $this -> herramientasModel -> listar_alm($objeto);
			
			if ($alm['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de almacenes ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de almacenes se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_alm($objeto);
			}
			
			
		}
	// Valida que la tabla de movimientos esta vacia
		if ($objeto['exi'] != "false") {
			$exi = $this -> herramientasModel -> listar_exi($objeto);
			
			if ($exi['total'] > 0) {
				$resp['mensaje'].= 'w#La tabla de existencia ya contiene informacion<br>';
			}else{
				$resp['mensaje'].= 's#La tabla de existencia se migro correctamente<br>';
				$resp['status'] = $this -> herramientasModel -> mudar_exi($objeto);
			}
			
			
		}
	//ch@ fin
	
	// Limpia la conexion externa
		$_SESSION['conexion_externa'] = '';
		unset($_SESSION['conexion_externa']);
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN vista_mudar						------ ************ //////////////////

} // Fin clase
?>