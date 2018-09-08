<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ConfiguracionModel extends Connection
{
	public function ejercicios()
	{
		$myQuery = "SELECT* FROM app_ejercicios";
		$resultados = $this->query($myQuery);
		return $resultados;
	}

	public function periodos($idejercicio)
	{
		$myQuery = "SELECT* FROM app_periodos WHERE id_ejercicio = $idejercicio";
		$resultados = $this->query($myQuery);
		return $resultados;
	}

	public function guardaNuevoEjercicio($ejercicio)
	{
		$myQuery = "INSERT INTO app_ejercicios(nombre) SELECT* FROM (SELECT $ejercicio) AS ejer WHERE NOT EXISTS(SELECT nombre FROM app_ejercicios WHERE nombre = $ejercicio) LIMIT 1";
		
		if($id = $this->insert_id($myQuery))
		{
			$myQuery = "";
			for($i=1;$i<=12;$i++)
			{
				$myQuery = "INSERT INTO app_periodos(id,id_ejercicio,num_mes,cerrado) VALUES(0,$id,$i,0);";
				$this->query($myQuery);
			}
			return 1;
		}
		else
			return 0;

	}

	public function tiene_bancos()
	{
		$tiene_bancos = $this->query("SELECT * FROM accelog_perfiles_me WHERE idmenu=1932");
		return $tiene_bancos->num_rows;
	}

	public function guardaConfInicial()
	{
		$myQuery = "INSERT IGNORE INTO app_configuracion(id, id_ejercicio_actual, id_periodo_actual, periodos_abiertos, id_costeo_general, salidas_sin_existencia, id_costeo_salida, iva, ieps, ret_iva, ret_isr, ish) 
					VALUES(1, 1, 1, 1, 0, 0, 0, 1, 0, 4, 0, 0);";
		return $this->query($myQuery);
	}

	public function configuracion()
	{
		$myQuery = "SELECT* FROM app_configuracion WHERE id=1";
		$actual = $this->query($myQuery);
		$actual = $actual->fetch_assoc();
		return $actual;
	}

	public function cambiaActual($idejercicio)
	{
		$myQuery = "UPDATE app_configuracion SET id_ejercicio_actual = $idejercicio WHERE id=1";
		$this->query($myQuery);
	}

	public function cambiaActualPeriodo($idperiodo)
	{
		$myQuery = "UPDATE app_configuracion SET id_periodo_actual = $idperiodo WHERE id=1";
		$this->query($myQuery);
	}

	public function cerrarEjercicio($idejercicio)
	{
		$myQuery = "UPDATE app_ejercicios SET cerrado=1 WHERE id=$idejercicio;";
		$this->query($myQuery);

		$myQuery = "UPDATE app_periodos SET cerrado=1 WHERE id_ejercicio=$idejercicio;";
		$this->query($myQuery);
	}

	public function cerrarPeriodo($idperiodo)
	{
		$myQuery = "UPDATE app_periodos SET cerrado=1 WHERE id=$idperiodo;";
		$this->query($myQuery);
	}

	public function cerroAnterior($idejercicio)
	{
		$myQuery = "SELECT id FROM app_ejercicios WHERE cerrado = 1 AND id=".($idejercicio-1);
		$cerrado = $this->query($myQuery);
		return $cerrado->num_rows;
	}

	public function cerroAnteriorPeriodo($idperiodo)
	{
		$myQuery = "SELECT id FROM app_periodos WHERE cerrado = 1 AND id=".($idperiodo-1);
		$cerrado = $this->query($myQuery);
		return $cerrado->num_rows;
	}

	public function periodosAbiertos($si)
	{
		$myQuery = "UPDATE app_configuracion SET periodos_abiertos = $si WHERE id=1";
		$this->query($myQuery);
	}

	public function lista_costeo()
	{
		return $this->query("SELECT* FROM app_costeo");
	}

	public function guardar1($idcosteo,$boolexis,$idexistencia,$mod_costo_compras)
	{
		$myQuery = "UPDATE app_configuracion SET id_costeo_general = $idcosteo, salidas_sin_existencia = $boolexis, id_costeo_salida = $idexistencia, mod_costo_compras = $mod_costo_compras WHERE id=1;";
		$this->query($myQuery);
	}

	public function guardar2($iva,$ieps,$ish,$ret_iva,$ret_isr)
	{
		$myQuery = "UPDATE app_configuracion SET iva = $iva, ieps = $ieps, ish = $ish, ret_iva = $ret_iva, ret_isr = $ret_isr WHERE id=1;";
		$this->query($myQuery);
	}

	public function guardar3($compras,$ventas,$cortes)
	{
		$myQuery = "UPDATE app_configuracion SET not_ventas = '$ventas', not_compras = '$compras', not_cortes = '$cortes' WHERE id=1;";
		$this->query($myQuery);
	}

	public function guardar4($cancelar,$emitir)
	{
		$myQuery = "UPDATE app_configuracion SET factura_cancelacion = $cancelar, factura_emision = $emitir WHERE id=1;";
		$this->query($myQuery);
	}

	public function pol_aut($pol_aut)
	{
		$myQuery = "UPDATE app_configuracion SET pol_aut = $pol_aut WHERE id=1;";
		$this->query($myQuery);
	}

	public function ej_cerrados($ej_cer)
	{
		$myQuery = "UPDATE app_configuracion SET permitir_cerrados = $ej_cer WHERE id=1;";
		$this->query($myQuery);
	}

	public function reiniciar($conservar) {
		$myQuery = "";

		if ($conservar == "Si") {
			$myQuery .= "
				DELETE FROM mrp_proveedor;
				ALTER TABLE mrp_proveedor AUTO_INCREMENT=1;
				DELETE FROM comun_cliente;
				ALTER TABLE comun_cliente AUTO_INCREMENT=1;
				DELETE FROM comun_facturacion;
				ALTER TABLE comun_facturacion AUTO_INCREMENT=1;
				DELETE FROM nomi_empleados;
				ALTER TABLE nomi_empleados AUTO_INCREMENT=1;
			";
		}

/*		$myQuery .= "TRUNCATE TABLE app_almacenes;
		TRUNCATE TABLE app_area_empleado;
		TRUNCATE TABLE app_caracteristicas_hija;
		TRUNCATE TABLE app_caracteristicas_padre;
		TRUNCATE TABLE app_clasificadores;
		TRUNCATE TABLE app_configuracion;
		TRUNCATE TABLE app_consignacion;
		TRUNCATE TABLE app_consignacion_datos;
		TRUNCATE TABLE app_costos_proveedor;
		TRUNCATE TABLE app_departamento;
		TRUNCATE TABLE app_devolucioncli;
		TRUNCATE TABLE app_devolucioncli_datos;
		TRUNCATE TABLE app_devolucionpro;
		TRUNCATE TABLE app_devolucionpro_datos;
		TRUNCATE TABLE app_ejercicios;
		TRUNCATE TABLE app_envios;
		TRUNCATE TABLE app_envios_datos;
		TRUNCATE TABLE app_familia;
		TRUNCATE TABLE app_inventario;
		TRUNCATE TABLE app_inventario_movimientos;
		TRUNCATE TABLE app_linea;
		TRUNCATE TABLE app_lista_precio;
		TRUNCATE TABLE app_lista_precio_prods;
		TRUNCATE TABLE app_merma;
		TRUNCATE TABLE app_merma_datos;
		TRUNCATE TABLE app_ocompra;
		TRUNCATE TABLE app_ocompra_datos;
		TRUNCATE TABLE app_oventa;
		TRUNCATE TABLE app_oventa_datos;
		TRUNCATE TABLE app_pagos;
		TRUNCATE TABLE app_pagos_relacion;
		TRUNCATE TABLE app_pendienteFactura;
		TRUNCATE TABLE app_periodos;
		TRUNCATE TABLE app_pos_venta;
		TRUNCATE TABLE app_pos_venta_pagos;
		TRUNCATE TABLE app_pos_venta_producto;
		TRUNCATE TABLE app_pos_venta_producto_impuesto;
		TRUNCATE TABLE app_pos_venta_suspendida;
		TRUNCATE TABLE app_producto_caracteristicas;
		TRUNCATE TABLE app_producto_impuesto;
		TRUNCATE TABLE app_producto_lotes;
		TRUNCATE TABLE app_producto_material;
		TRUNCATE TABLE app_producto_pedimentos;
		TRUNCATE TABLE app_producto_proveedor;
		TRUNCATE TABLE app_producto_serie;
		TRUNCATE TABLE app_producto_serie_rastro;
		TRUNCATE TABLE app_recepcion;
		TRUNCATE TABLE app_recepcion_datos;
		TRUNCATE TABLE app_recepcion_xml;
		TRUNCATE TABLE app_requisiciones;
		TRUNCATE TABLE app_requisiciones_datos;
		TRUNCATE TABLE app_requisiciones_datos_venta;
		TRUNCATE TABLE app_requisiciones_venta;
		TRUNCATE TABLE app_respuestaFacturacion;
		TRUNCATE TABLE app_tipo_credito;
		TRUNCATE TABLE app_tipo_producto;
		TRUNCATE TABLE app_tpl_polizas_mov;
		TRUNCATE TABLE app_campos_foodware;
		DELETE FROM app_unidades_medida;
		ALTER TABLE app_unidades_medida AUTO_INCREMENT=1;
		DELETE FROM app_productos;
		ALTER TABLE app_productos AUTO_INCREMENT=1;
		DELETE FROM com_recetas;
		ALTER TABLE com_recetas AUTO_INCREMENT = 1;
		INSERT IGNORE INTO app_almacenes (id, codigo_sistema, codigo_manual, nombre, id_padre, id_sucursal, id_estado, id_municipio, direccion, id_almacen_tipo, id_empleado_encargado, telefono, ext, es_consignacion, id_clasificador, activo) VALUES(1, '1', 'al-1', 'Almacen General', 0, 1, 14, 539, '', 1, 0, '', '12', 0, 0, 1);
";
*/

		$myQuery .= "TRUNCATE TABLE app_almacenes;
		TRUNCATE TABLE app_area_empleado;
		TRUNCATE TABLE app_caracteristicas_hija;
		TRUNCATE TABLE app_caracteristicas_padre;
		TRUNCATE TABLE app_clasificadores;
		TRUNCATE TABLE app_configuracion;
		TRUNCATE TABLE app_consignacion;
		TRUNCATE TABLE app_consignacion_datos;
		TRUNCATE TABLE app_costos_proveedor;
		TRUNCATE TABLE app_departamento;
		TRUNCATE TABLE app_devolucioncli;
		TRUNCATE TABLE app_devolucioncli_datos;
		TRUNCATE TABLE app_devolucionpro;
		TRUNCATE TABLE app_devolucionpro_datos;
		TRUNCATE TABLE app_ejercicios;
		TRUNCATE TABLE app_envios;
		TRUNCATE TABLE app_envios_datos;
		TRUNCATE TABLE app_familia;
		TRUNCATE TABLE app_inventario;
		TRUNCATE TABLE app_inventario_movimientos;
		TRUNCATE TABLE app_linea;
		TRUNCATE TABLE app_lista_precio;
		TRUNCATE TABLE app_lista_precio_prods;
		TRUNCATE TABLE app_merma;
		TRUNCATE TABLE app_merma_datos;
		TRUNCATE TABLE app_ocompra;
		TRUNCATE TABLE app_ocompra_datos;
		TRUNCATE TABLE app_oventa;
		TRUNCATE TABLE app_oventa_datos;
		TRUNCATE TABLE app_pagos;
		TRUNCATE TABLE app_pagos_relacion;
		TRUNCATE TABLE app_pendienteFactura;
		TRUNCATE TABLE app_periodos;
		TRUNCATE TABLE app_pos_venta;
		TRUNCATE TABLE app_pos_venta_pagos;
		TRUNCATE TABLE app_pos_venta_producto;
		TRUNCATE TABLE app_pos_venta_producto_impuesto;
		TRUNCATE TABLE app_pos_venta_suspendida;
		TRUNCATE TABLE app_producto_caracteristicas;
		TRUNCATE TABLE app_producto_impuesto;
		TRUNCATE TABLE app_producto_lotes;
		TRUNCATE TABLE app_producto_material;
		TRUNCATE TABLE app_producto_pedimentos;
		TRUNCATE TABLE app_producto_proveedor;
		TRUNCATE TABLE app_producto_serie;
		TRUNCATE TABLE app_producto_serie_rastro;
		TRUNCATE TABLE app_recepcion;
		TRUNCATE TABLE app_recepcion_datos;
		TRUNCATE TABLE app_recepcion_xml;
		TRUNCATE TABLE app_requisiciones;
		TRUNCATE TABLE app_requisiciones_datos;
		TRUNCATE TABLE app_requisiciones_datos_venta;
		TRUNCATE TABLE app_requisiciones_venta;
		TRUNCATE TABLE app_respuestaFacturacion;
		TRUNCATE TABLE app_tipo_credito;
		TRUNCATE TABLE app_tipo_producto;
		TRUNCATE TABLE app_tpl_polizas_mov;
		TRUNCATE TABLE app_campos_foodware;
		TRUNCATE TABLE app_pos_abono_caja;
		TRUNCATE TABLE app_pos_retiro_caja;
		TRUNCATE TABLE app_pos_corte_caja;
		TRUNCATE TABLE app_comision_productos;
		TRUNCATE TABLE app_pos_comision_producto;
		TRUNCATE TABLE app_inventario_traslados;
		TRUNCATE TABLE app_inventario_traslados_movimientos;
		TRUNCATE TABLE com_promociones;
		TRUNCATE TABLE com_promocionesXproductos;
		TRUNCATE TABLE app_politicas_tarjeta;
		TRUNCATE TABLE tarjeta_regalo;
		TRUNCATE TABLE app_producto_sucursal;
		TRUNCATE TABLE com_kits;
		TRUNCATE TABLE com_kitsXproductos;
		DELETE FROM app_productos;
		ALTER TABLE app_productos AUTO_INCREMENT=1;
		DELETE FROM com_recetas;
		ALTER TABLE com_recetas AUTO_INCREMENT = 1;
		INSERT IGNORE INTO app_almacenes (id, codigo_sistema, codigo_manual, nombre, id_padre, id_sucursal, id_estado, id_municipio, direccion, id_almacen_tipo, id_empleado_encargado, telefono, ext, es_consignacion, id_clasificador, activo) VALUES(1, '1', 'al-1', 'Almacen General', 0, 1, 14, 539, '', 1, 0, '', '12', 0, 0, 1);
";

		$this->multi_query($myQuery);
	}

	//INICIAN FUNCIONES CLASIFICADORES

	public function listaClas()
	{
		return $this->query("SELECT c1.*, (SELECT CONCAT(nombre,' / ',clave) FROM app_clasificadores WHERE id = c1.padre) AS npadre FROM app_clasificadores c1 ORDER BY c1.padre");
	}

	public function listaClas2($t)
	{
		return $this->query("SELECT c1.* FROM app_clasificadores c1 WHERE c1.tipo = $t AND c1.padre != 0 ORDER BY c1.padre");
	}

	public function datos_clas($id)
	{
		return $this->query("SELECT* FROM app_clasificadores WHERE id=$id;");
	}

	public function lista_padres_clas()
	{
		return $this->query("SELECT id,nombre,tipo FROM app_clasificadores WHERE padre = 0");
	}

	public function guardar_clas($idclas,$nombreclas,$claveclas,$padreclas,$tipoclas,$status)
	{
		if(!intval($idclas))
			$myQuery = "INSERT INTO app_clasificadores(id, nombre, clave, padre, tipo, activo) VALUES(0, '$nombreclas', '$claveclas', $padreclas, $tipoclas, $status)";
		else
			$myQuery = "UPDATE app_clasificadores SET nombre ='$nombreclas', clave = '$claveclas', padre = $padreclas, tipo = $tipoclas, activo = $status WHERE id = $idclas";
		return $this->query($myQuery);
	}


	public function busca_hijos_clas($idclas)
	{
		$myQuery = "SELECT id FROM app_clasificadores WHERE padre = $idclas AND activo = 1";
		$res = $this->query($myQuery);
		return $res->num_rows;
	}

	public function busca_padre_clas($id_padre_clas)
	{
		$myQuery = "SELECT id FROM app_clasificadores WHERE id = $id_padre_clas AND activo = 0";
		$res = $this->query($myQuery);
		return $res->num_rows;
	}

	//TERMINAN FUNCIONES CLASIFICADORES

	//INICIAN FUNCIONES CLASIFICADORES DE PRODUCTOS

	public function lista_clas_prod($tipo)
	{
		switch($tipo)
		{
			case "dep": $myQuery="SELECT id, nombre FROM app_departamento";break;
			case "fam": $myQuery="SELECT f.id, f.nombre, (SELECT nombre FROM app_departamento WHERE id=f.id_departamento) AS departamento FROM app_familia f";break;
			case "lin": $myQuery="SELECT l.id, l.nombre, l.activo, (SELECT nombre FROM app_familia WHERE id=l.id_familia) AS familia FROM app_linea l";break;
		}
		return $this->query($myQuery);
	}

	public function guardar_clas_prod($nombre=null,$tipo=null,$depende=null,$id=null,$status=null)
	{
		if(!intval($id))
		{
			if($tipo == 'dep')
			{
				$busca = "SELECT id,nombre FROM app_departamento WHERE nombre = '$nombre';";
				$myQuery = "INSERT INTO app_departamento(id,nombre) VALUES(0,'$nombre')";
			}

			if($tipo == 'fam')
			{
				$busca = "SELECT id,nombre FROM app_familia WHERE nombre = '$nombre';";
				$myQuery = "INSERT INTO app_familia(id,nombre,id_departamento) VALUES(0,'$nombre',$depende)";
			}

			if($tipo == 'lin')
			{
				$busca = "SELECT id,nombre FROM app_linea WHERE nombre = '$nombre' AND activo = 1;";
				$myQuery = "INSERT INTO app_linea(id,nombre,id_familia,activo) VALUES(0,'$nombre',$depende,$status)";
			}
		}
		else
		{
			if($tipo == 'dep')
			{
				$busca = "SELECT id,nombre FROM app_departamento WHERE nombre = '$nombre' AND id != $id;";
				$myQuery = "UPDATE app_departamento SET nombre = '$nombre' WHERE id=$id";
			}

			if($tipo == 'fam')
			{
				$busca = "SELECT id,nombre FROM app_familia WHERE nombre = '$nombre' AND id != $id;";
				$myQuery = "UPDATE app_familia SET nombre = '$nombre', id_departamento = $depende WHERE id=$id";
			}

			if($tipo == 'lin')
			{
				$busca = "SELECT id,nombre FROM app_linea WHERE nombre = '$nombre' AND activo = 1 AND id != $id ;";
				$myQuery = "UPDATE app_linea SET nombre = '$nombre', id_familia = $depende, activo = $status WHERE id=$id";
			}
		}
		$b = $this->query($busca);
		$b = $b->fetch_assoc();
		if(!intval($b['id']))
			return $this->query($myQuery);
		else 
			return 0;
	}

	public function lista_departamentos()
	{
		return $this->query("SELECT* FROM app_departamento");
	}

	public function lista_familias()
	{
		return $this->query("SELECT* FROM app_familia");
	}

	public function datos_clas_prod($id,$tipo)
	{
		if($tipo == "dep")
			$tabla = "app_departamento";
		
		if($tipo == "fam")
			$tabla = "app_familia";
		
		if($tipo == "lin")
			$tabla = "app_linea";
		return $this->query("SELECT* FROM $tabla WHERE id=$id");
	}

	//TERMINAN FUNCIONES DE CLASIFICADORES DE PRODUCTOS

	//INICIAN FUNCIONES CARACTERISTICAS DE PRODUCTOS

	public function lista_car_prod($tipo)
	{
		switch($tipo)
		{
			case "gral": $myQuery="SELECT id, nombre, activo FROM app_caracteristicas_padre";break;
			case "esp": $myQuery="SELECT h.id, h.nombre, activo, (SELECT nombre FROM app_caracteristicas_padre WHERE id=h.id_caracteristica_padre) AS general FROM app_caracteristicas_hija h";break;
		}
		return $this->query($myQuery);
	}

	public function guardar_car_prod($nombre=null,$tipo=null,$padre=null,$id=null,$status=null)
	{
		if(!intval($id))
		{
			if($tipo == 'gral')
				$myQuery = "INSERT INTO app_caracteristicas_padre(id,nombre,activo) VALUES(0,'$nombre',$status)";

			if($tipo == 'esp')
				$myQuery = "INSERT INTO app_caracteristicas_hija(id,id_caracteristica_padre,nombre,activo) VALUES(0,$padre,'$nombre',$status)";
		}
		else
		{
			if($tipo == 'gral')
				$myQuery = "UPDATE app_caracteristicas_padre SET nombre = '$nombre', activo = $status WHERE id=$id";

			if($tipo == 'esp')
				$myQuery = "UPDATE app_caracteristicas_hija SET nombre = '$nombre', id_caracteristica_padre = $padre, activo = $status WHERE id=$id";
		}

		return $this->query($myQuery);
	}

	public function lista_generales()
	{
		return $this->query("SELECT* FROM app_caracteristicas_padre");
	}


	public function datos_car_prod($id,$tipo)
	{
		if($tipo == "gral")
			$tabla = "app_caracteristicas_padre";
		
		if($tipo == "esp")
			$tabla = "app_caracteristicas_hija";
		
		return $this->query("SELECT* FROM $tabla WHERE id=$id");
	}

	public function busca_padre_car($padre)
	{
		$myQuery = "SELECT id FROM app_caracteristicas_padre WHERE id = $padre AND activo = 1";
		$res = $this->query($myQuery);
		return $res->num_rows;
	}

	public function busca_hijos_car($id)
	{
		$myQuery = "SELECT id FROM app_caracteristicas_hija WHERE id_caracteristica_padre = $id AND activo = 1";
		$res = $this->query($myQuery);
		return $res->num_rows;
	}

	//TERMINAN FUNCIONES DE CLASIFICADORES DE PRODUCTOS
	//INICIAN FUNCIONES TIPOS DE CREDITO

	public function listaCred()
	{
		return $this->query("SELECT* FROM app_tipo_credito");
	}

	public function datos_cred($id)
	{
		return $this->query("SELECT* FROM app_tipo_credito WHERE id=$id;");
	}

	public function guardar_cred($idcred,$nombrecred,$clavecred,$status)
	{
		if(!intval($idcred))
			$myQuery = "INSERT INTO app_tipo_credito(id, nombre, clave, activo) VALUES(0, '$nombrecred', '$clavecred', $status)";
		else
			$myQuery = "UPDATE app_tipo_credito SET nombre ='$nombrecred', clave = '$clavecred', activo = $status WHERE id = $idcred";
		return $this->query($myQuery);
	}

	//TERMINAN FUNCIONES TIPOS DE CREDITO
	//INICIAN FUNCIONES LISTAS DE PRECIO

	public function listaPrec()
	{
		return $this->query("SELECT* FROM app_lista_precio");
	}

	public function datos_listaprec($id)
	{
		return $this->query("SELECT* FROM app_lista_precio WHERE id=$id;");
	}

	public function guardar_listaprec($idlistaprec,$nombrelistaprec,$clavelistaprec,$porcentaje,$descuento,$status,$tipo)
	{
		if(!intval($idlistaprec))
			$myQuery = "INSERT INTO app_lista_precio(id, nombre, clave, porcentaje, descuento, activo, tipo) VALUES(0, '$nombrelistaprec', '$clavelistaprec', $porcentaje, $descuento, $status, $tipo)";
		else
			$myQuery = "UPDATE app_lista_precio SET nombre ='$nombrelistaprec', clave = '$clavelistaprec', porcentaje = $porcentaje, descuento = $descuento, activo = $status , tipo = $tipo WHERE id = $idlistaprec";
		return $this->query($myQuery);
	}

	//TERMINAN FUNCIONES LISTAS DE PRECIO
	//INICIAN FUNCIONES UNIDADES DE MEDIDA Y PESO

	public function listaMedida()
	{
		return $this->query("SELECT a.*, (SELECT clave FROM app_unidades_medida WHERE id = a.unidad_base) AS unidad_n FROM app_unidades_medida a");
	}

	public function datos_medida($id)
	{
		return $this->query("SELECT* FROM app_unidades_medida WHERE id=$id;");
	}

	public function guardar_medida($idmedida,$clavemedida,$nombremedida,$factor,$unidad_base,$status,$claveSat)
	{
		$clavemedida = strtoupper($clavemedida);
		$claveSat = strtoupper($claveSat);
		if(!intval($idmedida))
			$myQuery = "INSERT INTO app_unidades_medida(id, clave, nombre, factor, unidad_base, activo, codigo_sat) VALUES(0, '$clavemedida', '$nombremedida', $factor, $unidad_base, $status,'$claveSat')";
		else
			$myQuery = "UPDATE app_unidades_medida SET clave ='$clavemedida', nombre = '$nombremedida', factor = $factor, unidad_base = $unidad_base, activo = $status, codigo_sat = '$claveSat' WHERE id = $idmedida";
		return $this->query($myQuery);
	}

	public function lista_unidades_base()
	{
		return $this->query("SELECT id,clave,nombre FROM app_unidades_medida WHERE unidad_base = 0");
	}
	//TERMINAN FUNCIONES UNIDADES DE MEDIDA Y PESO
	//INICIAN FUNCIONES UNIDADES DE IMPUESTOS

	public function listaImpuestos()
	{
		return $this->query("SELECT * FROM app_impuesto");
	}

	public function datos_impuesto($id)
	{
		return $this->query("SELECT* FROM app_impuesto WHERE id=$id;");
	}

	public function guardar_impuesto($idimpuesto,$nombre,$valor,$status)
	{
		if(!intval($idimpuesto))
			$myQuery = "INSERT INTO app_impuesto(id, nombre, valor, activo) VALUES(0, '$nombre', $valor, $status)";
		else
			$myQuery = "UPDATE app_impuesto SET nombre = '$nombre', valor = $valor, activo = $status WHERE id = $idimpuesto";
		return $this->query($myQuery);
	}
	//TERMINAN FUNCIONES UNIDADES DE IMPUESTOS
	//INICIAN FUNCIONES PROVEEDORES

	public function listaProveedores()
	{
		return $this->query("SELECT p.idPrv, p.codigo, p.razon_social, p.rfc, (SELECT municipio FROM municipios WHERE idmunicipio = p.idmunicipio) AS municipio, (SELECT estado FROM estados WHERE idestado = p.idestado) AS estado, p.telefono, p.email, p.status AS estatus FROM mrp_proveedor p ORDER BY estatus, p.codigo, p.idPrv");
	}

	public function datos_proveedor($id)
	{
		return $this->query("SELECT* FROM app_unidades_medida WHERE id=$id;");
	}

	public function guardar_proveedor($idmedida,$clavemedida,$nombremedida,$factor,$unidad_base,$status)
	{
		if(!intval($idmedida))
			$myQuery = "INSERT INTO app_unidades_medida(id, clave, nombre, factor, unidad_base, activo) VALUES(0, '$clavemedida', '$nombremedida', $factor, $unidad_base, $status)";
		else
			$myQuery = "UPDATE app_unidades_medida SET clave ='$clavemedida', nombre = '$nombremedida', factor = $factor, unidad_base = $unidad_base, activo = $status WHERE id = $idmedida";
		return $this->query($myQuery);
	}

	public function guardar_gral_pol($vars)
	{
		$myQuery = "UPDATE app_configuracion SET conectar_acontia = ".$vars['conectar'].", conectar_bancos = ".$vars['conectar_bco'].",pol_autorizacion = ".$vars['autorizacion']." WHERE id = 1";
		return $this->query($myQuery);
	}

	public function getCuentas()
	{
		$myQuery = "SELECT account_id, account_code, manual_code, description FROM cont_accounts WHERE main_account = 3 AND removed = 0  ORDER BY account_type, manual_code";
		$res = $this->query($myQuery);
		$lista = "";
		while($r = $res->fetch_assoc())
		{
			$lista .= "<option value='".$r['account_id']."'>(".$r['manual_code'].") ".$r['description']."</option>";
		}
		return $lista;
	}

	public function getDatosVinc()
	{
		$myQuery = "SELECT * FROM app_tpl_datos";
		$res = $this->query($myQuery);
		$lista = "";
		while($r = $res->fetch_assoc())
		{
			$lista .= "<option value='".$r['id']."'>".$r['nombre']."</option>";
		}
		return $lista;
	}

	public function getImpuestos()
	{
		$myQuery = "SELECT * FROM app_impuesto";
		$res = $this->query($myQuery);
		$lista = "";
		while($r = $res->fetch_assoc())
		{
			$lista .= "<option value='".$r['nombre']."'>".$r['nombre']."</option>";
		}
		return $lista;
	}

	public function getCuentasAsoc($t,$p)
	{
		if(!$p)
			$tabla = "app_tpl_polizas_mov";
		else
			$tabla = "app_tpl_polizas_pagos_mov";

		$myQuery = "SELECT m.id, a.manual_code, a.description, m.tipo_movto, m.id_dato, (SELECT nombre FROM app_tpl_datos WHERE id = m.id_dato) AS nom_dato, nombre_impuesto
					FROM $tabla m
					INNER JOIN cont_accounts a ON a.account_id = m.id_cuenta
					WHERE m.id_tpl_poliza = $t AND m.activo != 0";
		return $this->query($myQuery);
	}

	 public function getPolizasComprasLista($n)
	{
		$myQuery = "SELECT id, nombre_poliza, automatica, poliza_por_mov, dias, (SELECT titulo FROM cont_tipos_poliza WHERE id = id_tipo_poliza) AS tipo_poliza, (SELECT CONCAT('(',codigo,') ',nombreclasificador) FROM bco_clasificador WHERE id = id_gasto) AS gasto 
			FROM app_tpl_polizas WHERE id >= $n";
		return $this->query($myQuery);
	}

	public function agregar_cuenta($vars)
	{
		if(isset($vars['pagos']))
		{
			if(intval($vars['pagos']))
				$tabla = "app_tpl_polizas_pagos_mov";
		}
		else
			$tabla = "app_tpl_polizas_mov";

		//Es abono o Cargo?
		if(intval($vars['abono']) && !intval($vars['cargo']))
			$tipo_movto = 1;
		if(!intval($vars['abono']) && intval($vars['cargo']))
			$tipo_movto = 2;
		
		//Si se trata de un impuesto agregar el tipo de impuesto
		$nombre_impuesto = "";
		if(intval($vars['vincular']) == 3)
			$nombre_impuesto = $vars['impuesto'];

		//Si no existe la cuenta asociada entonces es un registro nuevo, sino se tratará de una actualizacion
		if(!intval($vars['existe']))
			$myQuery = "INSERT INTO $tabla (id, id_tpl_poliza, id_cuenta, tipo_movto, id_dato, nombre_impuesto, activo) VALUES(0, ".$vars['tipo'].", ".$vars['cuenta'].", $tipo_movto, ".$vars['vincular'].", '$nombre_impuesto',2);";
		else
			$myQuery = "UPDATE $tabla SET id_cuenta = ".$vars['cuenta'].", tipo_movto = ".$tipo_movto.", id_dato = ".$vars['vincular'].", nombre_impuesto = '$nombre_impuesto' WHERE id = ".$vars['existe'];

		//Guardar
		return $this->query($myQuery);
	}

	public function eliminar_cuenta($id,$p)
	{
		if($p)
			$myQuery = "UPDATE app_tpl_polizas_pagos_mov SET activo = 0 WHERE id = $id";
		else
			$myQuery = "UPDATE app_tpl_polizas_mov SET activo = 0 WHERE id = $id";
		return $this->query($myQuery);
	}

	public function datos_cuenta($id,$p)
	{
		if($p)
			$myQuery = "SELECT* FROM app_tpl_polizas_pagos_mov WHERE id = $id";
		else
			$myQuery = "SELECT* FROM app_tpl_polizas_mov WHERE id = $id";
		return $this->query($myQuery);
	}

	public function guardar_poliza($vars)
	{
		if(intval($vars['pagos']))
		{
			$tabla1 = "app_tpl_polizas_pagos_mov";
			$tabla2 = "app_tpl_polizas_pagos";
		}
		else
		{
			$tabla1 = "app_tpl_polizas_mov";
			$tabla2 = "app_tpl_polizas";
		}
			

		//Activa las cuentas relacionadas
		$this->query("UPDATE $tabla1 SET activo = 1 WHERE activo = 2 AND id_tpl_poliza = ".$vars['tipo']);
		$automatica = 0;
		$por_mov = 1;
		$dias = 1;
		if(intval($vars['aut']) && !intval($vars['man']))
		{
			$automatica = intval($vars['aut']);
			$por_mov = $vars['por_mov'];
			$dias = $vars['dias'];
		}
		$myQuery = "UPDATE $tabla2 SET id_tipo_poliza = ".$vars['tipo_pol'].", id_gasto = ".$vars['gasto'].", nombre_poliza = '".$vars['concepto']."', automatica = $automatica, poliza_por_mov = $por_mov, dias = $dias WHERE id = ".$vars['tipo'];

		//Actualiza el template de la poliza
		return $this->query($myQuery);
	}

	public function tipoGastos()
	{
		return $this->query("SELECT id, codigo, nombreclasificador FROM bco_clasificador WHERE activo = -1;");
	}

	public function getInfoPoliza($tipo)
	{
		//Elimina las cuentas no confirmadas
		$this->query("UPDATE app_tpl_polizas_mov SET activo = 0 WHERE activo = 2 AND id_tpl_poliza = $tipo");
		//Busca los datos y los regresa al controlador    
		return $this->query("SELECT* FROM app_tpl_polizas WHERE id = ".$tipo);
	}

	public function getInfoPolizaPagos($tipo)
	{
		//Elimina las cuentas no confirmadas
		$this->query("UPDATE app_tpl_polizas_pagos_mov SET activo = 0 WHERE activo = 2 AND id_tpl_poliza = $tipo");
		//Busca los datos y los regresa al controlador    
		return $this->query("SELECT* FROM app_tpl_polizas_pagos WHERE id = ".$tipo);
	}

	public function nuevaPoliza()
	{
		 $myQuery = "INSERT INTO app_tpl_polizas (id, nombre_documento, id_tipo_poliza, id_gasto, nombre_poliza, automatica, poliza_por_mov, dias) VALUES(0, 'Compras', 2, 0, 'Poliza de Compras', 1, 1, 1);";
		return $this->insert_id($myQuery);

	}

	public function eliminar_poliza($id)
	{
		if($this->query("UPDATE app_tpl_polizas_mov SET activo = 0 WHERE id_tpl_poliza = $id"))
			return $this->query("DELETE FROM app_tpl_polizas WHERE id = $id");
		else
			return 0;
	}

	//TERMINAN FUNCIONES PROVEEDORES
	//FUNCIONES DE LAS POLIZAS MANUALES
	public function getFacturasVentas($cliente,$tipo_venta,$rango)
	{
		

		if(intval($tipo_venta) == 1)
		{
			$cliente_venta = "v.id_cliente";
			$tabla = "  LEFT JOIN app_envios e ON e.id = rf.idSale
						LEFT JOIN app_oventa v ON v.id = e.id_oventa";
		}
		if(intval($tipo_venta) == 2)
		{
			$cliente_venta = "pv.idCliente";
			$tabla = "LEFT JOIN app_pos_venta pv ON pv.idVenta = rf.idSale ";
		}

		$where = '';
		if($cliente)
			$where = "AND $cliente_venta = $cliente";

		$rango = explode(' / ',$rango);
		$inicial = trim($rango[0]);
		$final = trim($rango[1]);
		

		$myQuery = "SELECT rf.id, rf.idSale, rf.folio, rf.fecha, rf.xmlfile, (SELECT nombre FROM comun_cliente WHERE id = $cliente_venta) AS ClienteProv 
					FROM app_respuestaFacturacion rf
					$tabla 
					WHERE rf.tipoComp = 'F'
					AND rf.id_poliza_mov = '0' AND rf.xmlfile != '' AND rf.fecha_cancelacion IS NULL AND rf.origen = $tipo_venta $where
					AND rf.fecha BETWEEN '$inicial 00:00:00' AND '$final 23:59:59'  
					ORDER BY rf.fecha, rf.id, rf.idSale";
		return $this->query($myQuery);            
	}

	public function getFacturasCompras($id_gasto,$proveedor,$rango)
	{
		$where = '';
		if($proveedor)
			$where = "AND c.id_proveedor = $proveedor";

		$rango = explode(' / ',$rango);
		$inicial = trim($rango[0]);
		$final = trim($rango[1]);

		$myQuery = "SELECT x.id, c.id AS id_oc,  x.xmlfile AS folio, x.fecha_factura AS fecha ,(SELECT razon_social FROM mrp_proveedor WHERE idPrv = c.id_proveedor) AS ClienteProv 
					FROM app_recepcion_xml x
					INNER JOIN app_ocompra c ON c.id = x.id_oc
					INNER JOIN app_requisiciones rq ON rq.id = c.id_requisicion
					WHERE x.id_poliza_mov = '0' AND id_tipogasto = $id_gasto $where AND x.fecha_factura BETWEEN '$inicial 00:00:00' AND '$final 23:59:59' ";
		return $this->query($myQuery);            
	}

	public function getTodosDemas($tipo,$clienteProv,$rango)
	{
		$where = '';
		$rango = explode(' / ',$rango);
		$inicial = trim($rango[0]);
		$final = trim($rango[1]);
	
		if(intval($tipo) == 3 || intval($tipo) == 4)
		{
			if($clienteProv)
				$where = "AND id_prov_cli = $clienteProv";

			$tipo_cp = 0;
			if(intval($tipo) == 3)
				$tipo_cp = 1;

			$myQuery = "SELECT r.id, p.concepto, p.fecha_pago, (r.abono*p.tipo_cambio) AS monto, r.id_documento, r.id_tipo 
					FROM app_pagos_relacion r
					INNER JOIN app_pagos p ON p.id = r.id_pago
					WHERE r.id_poliza_mov = 0 AND p.cobrar_pagar = $tipo_cp $where AND fecha_pago BETWEEN '$inicial 00:00:00' AND '$final 23:59:59'
					";
		}

		if(intval($tipo) == 5 || intval($tipo) == 6 || intval($tipo) == 7)
		{
			switch(intval($tipo))
			{
				case 5: $tipo_est = 1;break;
				case 6: $tipo_est = 0;break;
				case 7: $tipo_est = 2;break;
			}
			$traspaso = '';
			if(intval($tipo) == 7)
				$traspaso = "AND id_almacen_origen = (SELECT id FROM app_almacenes WHERE codigo_sistema = '999')";

			$myQuery = "SELECT id, referencia, (SELECT CONCAT('(',codigo,') ',nombre) FROM app_productos WHERE id = id_producto) AS producto, importe, fecha 
						FROM app_inventario_movimientos
						WHERE estatus = 1 AND id_poliza_mov = '0' AND fecha BETWEEN '$inicial 00:00:00' AND '$final 23:59:59' AND tipo_traspaso = $tipo_est $traspaso  
						";
		}

		if(intval($tipo) == 8)
		{
			$myQuery = "SELECT id, idSale, folio, fecha, xmlfile 
						FROM app_respuestaFacturacion
						WHERE borrado = 3 AND tipoComp ='F' AND fecha_cancelacion != '' AND id_poliza_mov != '0' AND id_poliza_mov NOT LIKE '*%' AND fecha BETWEEN '$inicial 00:00:00' AND '$final 23:59:59'";
		}

		if(intval($tipo) == 9)
		{
			$myQuery = "SELECT id, idSale, folio, fecha, xmlfile 
						FROM app_respuestaFacturacion
						WHERE tipoComp ='C' AND xmlfile != '' AND id_poliza_mov = '0' AND fecha BETWEEN '$inicial 00:00:00' AND '$final 23:59:59'";
		}
		
		return $this->query($myQuery);            
	}
					
	public function ids_ventas($idFact)
	{
		$myQuery = "SELECT id_sale FROM app_pendienteFactura WHERE id_respFact = '$idFact' AND tipoComp = 'F'";
		$res = $this->query($myQuery);
		$ids = '';
		while($r = $res->fetch_assoc())
			$ids .= $r['id_sale'].",";
		return $ids;

	}

	public function segmentos()
	{
		$myQuery = "SELECT* FROM cont_segmentos WHERE activo = -1";
		return $this->query($myQuery);
	}

	public function tipo_gastos()
	{
		$myQuery = "SELECT id, codigo, nombreclasificador FROM bco_clasificador WHERE activo = -1";
		return $this->query($myQuery);
	}

	public function conexion_acontia()
	{
		return $this->query("SELECT conectar_acontia, pol_autorizacion, conectar_bancos FROM app_configuracion WHERE id = 1");
	}

	public function es_manual($tipo,$gasto=null)
	{
		if(intval($tipo) == 2)
		{
			return $this->query("SELECT* FROM app_tpl_polizas WHERE id > 10 AND id_gasto = $gasto ORDER BY id DESC LIMIT 1 ");
		}
		else
			return $this->query("SELECT* FROM app_tpl_polizas WHERE id = $tipo");
	}

	public function guardar_poliza_manual($vars)
	{
		$automatica = $this->es_manual($vars['tipo'],$vars['gasto']);
		$automatica = $automatica->fetch_assoc();
		$fecha = explode('-',$vars['fecha']);
		//Busca el id del ejercicio, si no existe, busca el ultimo y le suma al id para sacar el ejercicio
		$ejercicio = $this->query("SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = ".$fecha[0]);
		$ejercicio = $ejercicio->fetch_assoc();
		$ejercicio = $ejercicio['Id'];

		//Si no existe calcula el Id
		if(!intval($ejercicio))
		{
			$ejercicioAntes = $this->query("SELECT * FROM cont_ejercicios ORDER BY Id DESC LIMIT 1");
			$ejercicioAntes = $ejercicioAntes->fetch_assoc();
			$nuevoEj = intval($fecha[0]) - intval($ejercicioAntes['NombreEjercicio']);
			$ejercicio = intval($ejercicioAntes['Id']) + $nuevoEj;
		}
		if($ejercicio>0)
		{
			$numpol = $this->query("SELECT pp.numpol+1 FROM cont_polizas pp WHERE pp.idtipopoliza = ".$automatica['id_tipo_poliza']." AND pp.activo = 1 AND pp.idejercicio = $ejercicio AND pp.idperiodo = ".intval($fecha[1])." ORDER BY pp.numpol DESC LIMIT 1");
			$numpol = $numpol->fetch_assoc();
			$numpol = $numpol['numpol'];
			if(!intval($numpol))
				$numpol = 1;
			$activo = 1;
			$conexion_acontia = $this->conexion_acontia();
			$conexion_acontia = $conexion_acontia->fetch_assoc();
			if(intval($conexion_acontia['pol_autorizacion']))
				$activo = 0;


			$id_poliza_acontia = $this->insert_id("INSERT INTO cont_polizas(idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, concepto, fecha, fecha_creacion, activo, eliminado, pdv_aut, usuario_creacion, usuario_modificacion)
					 VALUES(1,$ejercicio,".intval($fecha[1]).",$numpol,".$automatica['id_tipo_poliza'].",'".$automatica['nombre_poliza']." ".$vars['concepto']."','$fecha[0]-$fecha[1]-$fecha[2]',DATE_SUB(NOW(), INTERVAL 6 HOUR), $activo, 0, 0, ".$_SESSION["accelog_idempleado"].", 0)");
			if(intval($id_poliza_acontia))
			{
				switch(intval($vars['tipo']))
				{
					case 1: $this->movs_ventas_compras($id_poliza_acontia,$vars,$activo,$automatica['id']);break;
					case 2: $this->movs_ventas_compras($id_poliza_acontia,$vars,$activo,$automatica['id']);break;
					case 3: $this->movs_CXPC($id_poliza_acontia,$vars,$activo,1);break;
					case 4: $this->movs_CXPC($id_poliza_acontia,$vars,$activo,0);break;
					case 5: $this->movs_inventarios($id_poliza_acontia,$vars,$activo,1);break;
					case 6: $this->movs_inventarios($id_poliza_acontia,$vars,$activo,0);break;
					case 7: $this->movs_inventarios($id_poliza_acontia,$vars,$activo,2);break;
					case 8: $this->movs_cancelaciones($id_poliza_acontia,$vars,$activo);break;
					case 9: $this->movs_ventas_compras($id_poliza_acontia,$vars,$activo,$automatica['id']);break;
					case 10: $this->movs_ventas_compras($id_poliza_acontia,$vars,$activo,$automatica['id']);break;
				}
			}            
			
			return $id_poliza_acontia;
		}
		else
			return 0;
	}

	public function movs_ventas_compras($id_poliza_acontia,$vars,$activo,$id_conf_poliza)
	{

		$ruta  = "../cont/xmls/facturas/";//Ruta donde se copiara
		$ids = explode(',',$vars['ids']);
		$cont = 0;
	
		for($i=0;$i<=count($ids)-2;$i++)
		{
			$tipo = $vars['tipo'];
			if(intval($vars['tipo']) == 2)
				$tipo = $id_conf_poliza;


			$cuentas_poliza = $this->query("SELECT id_cuenta, tipo_movto, id_dato, nombre_impuesto FROM app_tpl_polizas_mov WHERE activo = 1 AND id_tpl_poliza = $tipo");
			if(intval($vars['tipo']) != 2)
			{
				//Informacion de la factura si es venta
				$info = $this->query("SELECT idSale,xmlfile FROM app_respuestaFacturacion WHERE id = ".$ids[$i]);
				$info = $info->fetch_assoc();
			}
			else
			{
				//Informacion de la factura si es compra
				$info = $this->query("SELECT id_oc,xmlfile FROM app_recepcion_xml WHERE id = ".$ids[$i]);
				$info = $info->fetch_assoc();   
			}
			

			while($cp = $cuentas_poliza->fetch_assoc())
			{
				$cont++;
				$importe = 0;
				//Cargo o abono
				if(intval($cp['tipo_movto']) == 1)
					$tipo_movto = "Abono";
				if(intval($cp['tipo_movto']) == 2)
					$tipo_movto = "Cargo";
				//Abre factura
				if(file_exists($ruta.'temporales/'.$info['xmlfile']))
					$aa = simplexml_load_file($ruta.'temporales/'.$info['xmlfile']);
				else
					$aa = simplexml_load_file($ruta.$id_poliza_acontia."/".$info['xmlfile']);

				if($namespaces = $aa->getNamespaces(true))
				{
					$child = $aa->children($namespaces['cfdi']);
				}
								
				$total = 0;
				$subtotal = 0;
				$metodo_pago = 0;

				//Sacar el total y subtotal
				foreach($aa->attributes() AS $a => $b)
				{
					if($a == 'total')
					{
						$total = $b;
					}

					if($a == 'subTotal')
					{
						$subtotal = $b;
					}

					if($a == 'metodoDePago')
					{
						$metodo_pago = intval($b);
					}
				}
				 //dependiendo el tipo de dato sera el valor que tomara.
				if(intval($cp['id_dato']) == 2)
				{
					$importe = $subtotal;
				}
				elseif(intval($cp['id_dato']) == 3)
				{
					if($cp['nombre_impuesto'])
					{
						$impu = str_replace('%', '', $cp['nombre_impuesto']);
						$impu = explode(' ', $impu);

						for($j=0;$j<=(count($child->Impuestos->Traslados->Traslado)-1);$j++)
						{
							$bandera1 = $bandera2 = $cantidad = 0;
							foreach($child->Impuestos->Traslados->Traslado[$j]->attributes() AS $a => $b)
							{
								if($a == 'impuesto' && strtoupper($b) == $impu[0])
									$bandera1 = 1;
														
								if($impu[1] != 'EXENTO')
								{
									if($a == 'tasa' && floatval($b) == floatval($impu[1]))
										$bandera2 = 1;
								}
								else
								{
									if($a == 'tasa' && $b == $impu[1])
										$bandera2 = 1;
								}
														
								if($a == 'importe')
									$cantidad = $b;

								if($bandera1 && $bandera2 && $cantidad)
									$importe = $cantidad;
							}
						}
					}   
				}
				else
				{
					//Si es total, cliente o proveedor agrega el total en el importe
					$importe = $total;
				}
				if(intval($vars['tipo']) == 1 || intval($vars['tipo']) == 9)
				{
					if(intval($info['idSale']))
					{
						if(intval($vars['tipo']) == 1)
						{
							$myQuery = "SELECT IdSucursal AS id_sucursal, tipo_cambio, idCliente AS id_cliente FROM app_pos_venta WHERE idVenta = ".$info['idSale'];
						}
						else
						{
							$myQuery = "SELECT forma_pago, (SELECT id_sucursal FROM app_almacenes WHERE id = v.id_almacen) AS id_sucursal, tipo_cambio, v.id_cliente  
										FROM app_envios e
										INNER JOIN app_oventa v ON v.id = e.id_oventa
										INNER JOIN app_requisiciones_venta rq ON rq.id = v.id_requisicion
										WHERE e.id = ".$info['idSale'];
						}
						$info_venta = $this->query($myQuery);
						$info_venta = $info_venta->fetch_assoc();
						if(!intval($info_venta['id_sucursal']))
							$info_venta['id_sucursal'] = 1;
						if(!$info_venta['tipo_cambio'])
							$info_venta['tipo_cambio'] = 1;

						//Si el cliente tiene una cuenta asignada entonces no toma en cuenta la cuenta configurada
						if(intval($cp['id_dato']) == 4)
						{
							$cuentaCliProv = $this->query("SELECT cuenta FROM comun_cliente WHERE id = ".$info_venta['id_cliente']);
							$cuentaCliProv = $cuentaCliProv->fetch_assoc();
							if(intval($cuentaCliProv['cuenta']))
								$cp['id_cuenta'] = $cuentaCliProv['cuenta'];
						}
					}
					else
					{
						$info_venta['id_sucursal'] = 1;
						$info_venta['tipo_cambio'] = 1;
					}
				}

				if(intval($vars['tipo']) == 2)
				{
					 $info_venta = $this->query("SELECT c.id_proveedor, rq.tipo_cambio 
												FROM app_requisiciones rq 
												INNER JOIN app_ocompra c ON c.id_requisicion = rq.id WHERE c.id = ".$info['id_oc']);
						$info_venta = $info_venta->fetch_assoc();
						
						$info_venta['id_sucursal'] = 1;
						if(!$info_venta['tipo_cambio'])
							$info_venta['tipo_cambio'] = 1;
						//Si el cliente tiene una cuenta asignada entonces no toma en cuenta la cuenta configurada
						if(intval($cp['id_dato']) == 5)
						{
							$cuentaCliProv = $this->query("SELECT cuenta FROM mrp_proveedor WHERE idPrv = ".$info_venta['id_proveedor']);
							$cuentaCliProv = $cuentaCliProv->fetch_assoc();
							if(intval($cuentaCliProv['cuenta']))
								$cp['id_cuenta'] = $cuentaCliProv['cuenta'];
						}
				}
				
				$id_mov = $this->insert_id("INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura, FormaPago, tipocambio, IdVenta) 
								VALUES($id_poliza_acontia, $cont, ".$vars['segmento'].", ".$info_venta['id_sucursal'].", ".$cp['id_cuenta'].", '$tipo_movto', $importe, '".$info['xmlfile']."','Id Fact: ".$ids[$i]."', $activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), '".$info['xmlfile']."', $metodo_pago, ".$info_venta['tipo_cambio'].", ".$ids[$i].")");
				$ids_movs .= $id_mov.",";

				//Crear carpeta y copiar xml de la factura, ya se que esta no es el controlador pero no quedaba de otra, asi que hare una excepcion.
				if(!file_exists($ruta.$id_poliza_acontia))//Si no existe la carpeta de ese poliza la crea
				{
					mkdir ($ruta.$id_poliza_acontia, 0777);
				}
				copy($ruta.'temporales/'.$info['xmlfile'], $ruta.$id_poliza_acontia."/".$info['xmlfile']);
			}
			if(intval($vars['tipo']) == 1 || intval($vars['tipo']) == 9)
				$this->query("UPDATE app_respuestaFacturacion SET id_poliza_mov = '$ids_movs' WHERE id = ".$ids[$i]);
			if(intval($vars['tipo']) == 2)
				$this->query("UPDATE app_recepcion_xml SET id_poliza_mov = '$ids_movs' WHERE id = ".$ids[$i]);
			
			
			$ids_movs = '';
		}
	}
	public function movs_CXPC($id_poliza_acontia,$vars,$activo,$tipo)
	{
		$id_tipo = 4;
		if($tipo)
			$id_tipo = 3;
			
		$ids = explode(',',$vars['ids']);

		for($i=0;$i<=count($ids)-2;$i++)
		{
			//Buscar si es una cuenta por pagar o por cobrar
			$cobrar_pagar = $this->query("SELECT * FROM app_pagos WHERE id = (SELECT id_pago FROM app_pagos_relacion WHERE id = ".$ids[$i].")");
			$cobrar_pagar = $cobrar_pagar->fetch_assoc();

            $monto = $this->query("SELECT abono,id_tipo FROM app_pagos_relacion WHERE id = ".$ids[$i]);
            $monto = $monto->fetch_assoc();
            if(!intval($monto['id_tipo']))
                $id_tipo = 11;
            $monto = $monto['abono'];
            
			$cuentas_poliza = $this->query("SELECT id_cuenta, tipo_movto, id_dato FROM app_tpl_polizas_mov WHERE activo = 1 AND id_tpl_poliza = $id_tipo");
			$cont = 0;

			while($cp = $cuentas_poliza->fetch_assoc())
				{
					$cont++;
					//Cargo o abono
					if(intval($cp['tipo_movto']) == 1)
						$tipo_movto = "Abono";
					if(intval($cp['tipo_movto']) == 2)
						$tipo_movto = "Cargo";
					//dependiendo el tipo de dato sera el valor que tomara, en este caso solo existe el total del pago.
					$importe = 0;
					switch(intval($cp['id_dato']))
					{
						case 1 : $importe = $monto; break;
						case 2 : $importe = $monto; break;
						case 3 : $importe = 0; break;
						case 4 : $importe = $monto; break;
						case 5 : $importe = $monto; break;
					}
					//Si tiene cuenta de clientes busca si el id del cliente esta vinculado a una cuenta, si no es asi lo asignara a la cuenta configurada.
					if(intval($cp['id_dato']) == 4 && intval($tipo) == 0)
					{
						$cuentaCliProv = $this->query("SELECT cuenta FROM comun_cliente WHERE id = ".$cobrar_pagar['id_prov_cli']);
						$cuentaCliProv = $cuentaCliProv->fetch_assoc();
						if(intval($cuentaCliProv['cuenta']))
							$cp['id_cuenta'] = $cuentaCliProv['cuenta'];
					}

					//Si tiene cuenta de proveedor busca si el id del proveedor esta vinculado a una cuenta, si no es asi lo asignara a la cuenta configurada.
					if(intval($cp['id_dato']) == 5 && intval($tipo) == 1)
					{
						$cuentaCliProv = $this->query("SELECT cuenta FROM mrp_proveedor WHERE idPrv = ".$cobrar_pagar['id_prov_cli']);
						$cuentaCliProv = $cuentaCliProv->fetch_assoc();
						if(intval($cuentaCliProv['cuenta']))
							$cp['id_cuenta'] = $cuentaCliProv['cuenta'];
					}

					$id_mov = $this->insert_id("INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, FormaPago, tipocambio) 
								VALUES($id_poliza_acontia, $cont, 1, 1, ".$cp['id_cuenta'].", '$tipo_movto', $importe, '','".$vars['concepto']." Doc: $ids[$i]', $activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), ".$cobrar_pagar['id_forma_pago'].", ".$cobrar_pagar['tipo_cambio'].")");
					$ids_movs .= $id_mov.",";
				}
				$this->query("UPDATE app_pagos_relacion SET id_poliza_mov = '$ids_movs' WHERE id = $ids[$i]");
				$ids_movs = '';

		}
	}

	public function movs_inventarios($id_poliza_acontia,$vars,$activo,$tipo)
	{
		
		if(!intval($tipo))
			$id_tipo = 6;
		if(intval($tipo) == 1)
			$id_tipo = 5;
		if(intval($tipo) == 2)
			$id_tipo = 7; 

		$ids = explode(',',$vars['ids']);

		for($i=0;$i<=count($ids)-2;$i++)
		{
			$cuentas_poliza = $this->query("SELECT id_cuenta, tipo_movto, id_dato FROM app_tpl_polizas_mov WHERE activo = 1 AND id_tpl_poliza = $id_tipo");
			$cont = 0;
			$monto = $this->query("SELECT importe FROM app_inventario_movimientos WHERE id = ".$ids[$i]);
			$monto = $monto->fetch_assoc();
			$monto = $monto['importe'];
			while($cp = $cuentas_poliza->fetch_assoc())
			{
				 $cont++;
				//Cargo o abono
				if(intval($cp['tipo_movto']) == 1)
					$tipo_movto = "Abono";
				if(intval($cp['tipo_movto']) == 2)
					$tipo_movto = "Cargo";
				//dependiendo el tipo de dato sera el valor que tomara, en este caso solo existe el total del pago.
				$importe = $monto;
				//dependiendo el tipo de dato sera el valor que tomara, en este caso solo existe el total del pago.
				if(intval($cp['id_dato']) == 3)
				{
					$importe = 0;
				}
				$id_mov = $this->insert_id("INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, FormaPago, tipocambio) 
								VALUES($id_poliza_acontia, $cont, 1, 1, ".$cp['id_cuenta'].", '$tipo_movto', $importe, '','".$vars['concepto']." Doc: $ids[$i]', $activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), 1, 1)");
				$ids_movs .= $id_mov.",";
			}
			$this->query("UPDATE app_inventario_movimientos SET id_poliza_mov = '$ids_movs' WHERE id = $ids[$i]");
			$ids_movs = '';
		}
	}

	public function fac_movs($id)
	{
		$res = $this->query("SELECT id_poliza_mov FROM app_respuestaFacturacion WHERE id = $id");
		$res = $res->fetch_assoc();
		return $res['id_poliza_mov'];
	}

	public function movs_cancelaciones($id_poliza_acontia,$vars,$activo)
	{
		$ids = explode(',',$vars['ids']);


		for($i=0;$i<=count($ids)-2;$i++)
		{
			$mov = explode(",",$this->fac_movs($ids[$i]));
			for($j=0;$j<=count($mov)-2;$j++)
			{
				$id_mov = $this->insert_id("INSERT INTO cont_movimientos (Id, IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura, FormaPago, tipocambio) 
										  SELECT '', $id_poliza_acontia, NumMovto, ".$vars['segmento'].", IdSucursal, Cuenta, TipoMovto, Importe*-1, Referencia, 'Factura Cancelada: ".$ids[$i]."', Activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), '', FormaPago, tipocambio FROM cont_movimientos WHERE Id = ".$mov[$j]);
				$ids_movs .= $id_mov.",";
			}
			$this->query("UPDATE app_respuestaFacturacion SET id_poliza_mov = '*$ids_movs' WHERE id = ".$ids[$i]);
			$ids_movs = '';
		}
	}

	public function clientes()
	{
		return $this->query("SELECT id, nombre, rfc FROM comun_cliente WHERE borrado = 0;");

	}

	public function proveedores()
	{
		return $this->query("SELECT idPrv, razon_social, rfc FROM mrp_proveedor WHERE status = -1;");

	}

}
?>
