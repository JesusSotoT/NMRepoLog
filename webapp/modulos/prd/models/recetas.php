<?php
/**
 * @author Fer De La Cruz
 */

require("models/connection_sqli.php"); // funciones mySQLi

class recetasModel extends Connection{
///////////////// ******** ---- 			guardar_producto				------ ************ //////////////////
//////// Inserta un registro en la tabla app_productos con los datos de la receta
	// Como parametros recibe:
		// nombre -> Nombre de la receta o insumo preparado
		// codigo -> Codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> Comentarios sobre la receta o insumo preparado
		// precio_venta -> Precio de venta
		// margen_ganancia -> Margen de ganancia

	function guardar_producto($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}

	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
	$tipo = ($datos['tipo']==1) ? 1 : 4 ;




	// Guarda la receta y regresa el ID
		$sql="	INSERT INTO
					app_productos
						(codigo, nombre, precio, linea, costo_servicio, id_unidad_venta, id_unidad_compra,
							tipo_producto
						)
				VALUES
					('".$datos['codigo']."','".$datos['nombre']."',".$datos['precio_venta'].",
						1, ".$datos['costo'].", ".$datos['unidad_venta'].", ".$datos['unidad_compra'].", ".$tipo."
					)";
		// return $sql;
		$result =$this->insert_id($sql);

	// Guarda los campos de foodware
		$sql="	INSERT INTO
					app_campos_foodware
						(id_producto, ganancia)
				VALUES
					('".$result."', '".$datos['margen_ganancia']."')";
		// return $sql;
		$result_foodware =$this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN guardar_producto		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_producto			------ ************ //////////////////
//////// Modifa el registro en la tabla app_productos con los datos de la receta
	// Como parametros recibe:
		// id_receta -> ID de la receta
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia

	function actualizar_producto($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}

	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo'] == 1) ? 5 : 4 ;

	// Guarda la receta y regresa el ID
		$sql = "UPDATE app_productos SET codigo = '" . $datos['codigo'] . "', nombre = '" . $datos['nombre'] . "', minimos = " . $datos['cant_min'] . ", factor= '".$datos['factor']."' WHERE id=".$datos['id_receta'];
		// return $sql;
		$result = $this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_producto		------ ************ //////////////////

///////////////// ******** ---- 			guardar_receta			------ ************ //////////////////
//////// Inserta un registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia

	function guardar_receta($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}

	// Guarda la receta y regresa el ID
		$sql="	INSERT INTO
					com_recetas
						(id, nombre, precio, ganancia, ids_insumos, ids_insumos_preparados, preparacion)
				VALUES
					(".$datos['id_receta'].", '".$datos['nombre']."',".$datos['precio_venta'].",
						".$datos['margen_ganancia'].",
						'".$datos['ids']."','".$datos['ids_preparados']."','".$datos['preparacion']."'
					)";
		// return $sql;
		$result =$this->insert_id($sql);

	// Guarda la actividad
		$fecha=date('Y-m-d H:i:s');

		$texto = ($datos['tipo']==1) ? 'receta' : 'insumo preparado' ;
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['accelog_idempleado'])) ?$_SESSION['accelog_idempleado'] : 0 ;
		$sql="	INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					('',".$usuario.",'Agrega ".$texto."', '".$fecha."')";
		$actividad=$this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN guardar_receta			------ ************ //////////////////

///////////////// ******** ---- 				validar				------ ************ //////////////////
//////// Valida si existe la receta
	// Como parametros recibe:
		// id_receta ID de la receta

	function validar($objeto){
		$sql = "SELECT
					id
				FROM
					com_recetas
				WHERE
					id = ".$objeto['id_receta'];
		// return $sql;
		$result =$this->queryArray($sql);

		return $result;
	}
///////////////// ******** ---- 			FIN validar				------ ************ //////////////////

///////////////// ******** ---- 		actualizar_receta			------ ************ //////////////////
//////// Modifa el registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// id_receta -> ID de la receta
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
		// ids -> ID´s de los insumos
		// ids_preparados -> ID´s de los insumos preparados

	function actualizar_receta($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}

	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo']==1) ? 5 : 4 ;

	// Actualiza los datos de la receta
		$sql= "UPDATE com_recetas SET nombre = '" . $datos['nombre'] . "', ids_insumos = '" . $datos['ids']."' WHERE id = " . $datos['id_receta'];
		//return $sql;
		$result =$this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_receta		------ ************ //////////////////

///////////////// ******** ---- 		guarda_receta_sin_insumos			------ ************ //////////////////
//////// Modifa el registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// id_receta -> ID de la receta
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
		// ids -> ID´s de los insumos
		// ids_preparados -> ID´s de los insumos preparados

	function guarda_receta_sin_insumos($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}

	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo']==1) ? 5 : 4 ;

	// Actualiza los datos de la receta
		$sql= "INSERT INTO com_recetas (id, nombre, ids_insumos, status) VALUES (" . $datos['id_receta'] . ", '" . $datos['nombre'] . "', '" . $datos['ids'] . "', 1)";
		//return $sql;
		$result =$this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN guarda_receta_sin_insumos		------ ************ //////////////////


///////////////// ******** ---- 		actualizar_conceptos_lab			------ ************ //////////////////
//////// Modifa el registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// id_concepto -> ID del concepto de laboratorio
		// parametro -> nombre del concepto de laboratorio
		// codigo -> codigo de la receta o insumo preparado
		// is_numeric -> Especifica si el concepto es numérico
		// unidad -> Unidad de medida del concepto de laboratorio

	function actualizar_conceptos_lab($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}


	// Actualiza los datos de la receta
		$sql= "UPDATE prd_lab_conceptos SET parametro = '" . $datos['parametro'] . "', is_numeric = " . $datos['is_numeric'] . ", unidad = " . $datos['unidad'] . " WHERE id = " . $datos['id_concepto'];
		//return $sql;
		$result =$this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_conceptos_lab		------ ************ //////////////////


///////////////// ******** ---- 		guardar_insumo				------ ************ //////////////////
//////// Inserta un registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia

	function guardar_insumo($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}

	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo']==1) ? 5 : 4 ;

	// Guarda la receta y regresa el ID
		$sql="	INSERT INTO
					app_producto_material
						(id_producto, cantidad, id_unidad, id_material, opcionales)
				VALUES
					('".$datos['id_receta']."',".$datos['cantidad'].",".$datos['id_unidad'].",
						'".$datos['id']."','".$datos['opcionales']."'
					)";
		// return $sql;
		$result =$this->insert_id($sql);

		return $result;
	}

	function guardar_producto_proceso3($lasesion,$idFamilia){

		$sql="SELECT a.id,a.familia,b.nombre, a.nombre FROM app_productos a
		inner join app_familia b on b.id=a.familia
		where a.familia='$idFamilia';";

		$r = $this->queryArray($sql);
		if($r['total']>0){
			foreach ($r['rows'] as $llave => $val) {
				$idProducto=$val['id'];

				$sqlr="SELECT id FROM prd_ini_proceso WHERE id_oproduccion in (SELECT id_orden_produccion FROM prd_orden_produccion_detalle WHERE id_producto='$idProducto');";
				$rr=$this->queryArray($sqlr);
				if($rr['total']>0){
					continue;
				}


				foreach ($lasesion as $k => $v) {
					$paso=str_replace('_', ' ', $k);
					$sql="INSERT INTO prd_pasos_producto (descripcion, id_producto) VALUES ('$paso','$idProducto');";
					$lastid =$this->insert_id($sql);
					$cad='';
					foreach ($v as $kk => $vv) {
						if($vv['actividad']==1){
							$cad.="('".$lastid."','".$vv['idAccion']."','".$vv['alias']."','".$vv['alias_hr']."','".$vv['actividad']."','0','".$vv['tipo']."','".$vv['estatus']."'),";
						}
						if($vv['actividad']==2){
							$cad.="('".$lastid."','".$vv['idAccion']."','".$vv['alias']."','0','".$vv['actividad']."','".$vv['alias_hr']."','".$vv['tipo']."','".$vv['estatus']."'),";
						}
						//cad.="('".$lastid."','".$vv['idAccion']."'),";
					}
					$cadtrim = trim($cad, ',');
					$sql2="INSERT INTO prd_pasos_acciones_producto (id_paso, id_accion,alias,tiempo,actividad,pieza,tipo,estatus) VALUES  ".$cadtrim." ;";
					$this->query($sql2);
				}

			}
			echo 1;
		}else{
			echo 0;
		}

/* Falta ciclo por familia */
/*
		if($sel_ciclo==1){
			$sql="INSERT INTO prd_pasos_producto (descripcion, id_producto) VALUES ('Paso 1','$idProducto');";
			$lastid =$this->insert_id($sql);
			$sql2="INSERT INTO prd_pasos_acciones_producto (id_paso, id_accion,alias,tiempo,actividad,pieza,tipo,estatus) VALUES 
			('$lastid', 1, 'Formulación de Insumos', '00:00:00', 1, 0, 1, 1),
			('$lastid', 2, 'Registro de insumos utilizados', '00:00:00', 1, 0, 1, 1),
			('$lastid', 14, 'Registro de merma', '00:00:00', 1, 0, 1, 1), 
			('$lastid', 15, 'Costos de produccion', '00:00:00', 1, 0, 1, 1),
			('$lastid', 9, 'Fin de producción', '00:00:00', 1, 0, 1, 1);";
			$this->query($sql2);
			echo 1;

		}

		if($sel_ciclo==2){
			$sql="INSERT INTO prd_pasos_producto (descripcion, id_producto) VALUES ('Paso 1','$idProducto');";
			$lastid =$this->insert_id($sql);
			$sql2="INSERT INTO prd_pasos_acciones_producto (id_paso, id_accion,alias,tiempo,actividad,pieza,tipo,estatus) VALUES 
			('$lastid', 1, 'Formulación de Insumos', '00:00:00', 1, 0, 1, 1),
			('$lastid', 2, 'Registro de insumos utilizados', '00:00:00', 1, 0, 1, 1),
			('$lastid', 9, 'Fin de producción', '00:00:00', 1, 0, 1, 1);";
			$this->query($sql2);
			echo 1;

		}

		*/
		

	}


	function guardar_producto_proceso2($lasesion,$idProducto,$modi,$sel_ciclo){


		$sqlr="SELECT id FROM prd_ini_proceso WHERE id_oproduccion in (SELECT id_orden_produccion FROM prd_orden_produccion_detalle WHERE id_producto='$idProducto');";
			$rr=$this->queryArray($sqlr);
			if($rr['total']>0){
				echo 'ini';
				exit();
			}


		if($modi==1){
 

			$sql2="SELECT id FROM prd_pasos_producto WHERE id_producto='$idProducto';";
			$r=$this->queryArray($sql2);
			if($r['total']>0){
				foreach ($r['rows'] as $k => $v) {
					$sql3="DELETE FROM prd_pasos_acciones_producto WHERE id_paso='".$v['id']."';";
					$this->query($sql3);
				}
			}
			$sql2="DELETE FROM prd_pasos_producto WHERE id_producto='$idProducto';";
			$this->query($sql2);
		}

		if($sel_ciclo==0){

			foreach ($lasesion as $k => $v) {
				$paso=str_replace('_', ' ', $k);
				$sql="INSERT INTO prd_pasos_producto (descripcion, id_producto) VALUES ('$paso','$idProducto');";
				$lastid =$this->insert_id($sql);
				$cad='';
				foreach ($v as $kk => $vv) {
					if($vv['actividad']==1){
						$cad.="('".$lastid."','".$vv['idAccion']."','".$vv['alias']."','".$vv['alias_hr']."','".$vv['actividad']."','0', '".$vv['tipo']."', '".$vv['estatus']."'),";
					}
					if($vv['actividad']==2){
						$cad.="('".$lastid."','".$vv['idAccion']."','".$vv['alias']."','0','".$vv['actividad']."','".$vv['alias_hr']."', '".$vv['tipo']."', '".$vv['estatus']."'),";
					}
				}
				$cadtrim = trim($cad, ',');
				$sql2="INSERT INTO prd_pasos_acciones_producto (id_paso, id_accion,alias,tiempo,actividad,pieza,tipo,estatus) VALUES  ".$cadtrim." ;";
				$this->query($sql2);
			}

			echo 1;

		}


		if($sel_ciclo==1){
			$sql="INSERT INTO prd_pasos_producto (descripcion, id_producto) VALUES ('Paso 1','$idProducto');";
			$lastid =$this->insert_id($sql);
			$sql2="INSERT INTO prd_pasos_acciones_producto (id_paso, id_accion,alias,tiempo,actividad,pieza,tipo,estatus) VALUES 
			('$lastid', 1, 'Formulación de Insumos', '00:00:00', 1, 0, 1, 1),
			('$lastid', 2, 'Registro de insumos utilizados', '00:00:00', 1, 0, 1, 1),
			('$lastid', 14, 'Registro de merma', '00:00:00', 1, 0, 1, 1), 
			('$lastid', 15, 'Costos de produccion', '00:00:00', 1, 0, 1, 1),
			('$lastid', 9, 'Fin de producción', '00:00:00', 1, 0, 1, 1);";
			$this->query($sql2);
			echo 1;

		}

		if($sel_ciclo==2){
			$sql="INSERT INTO prd_pasos_producto (descripcion, id_producto) VALUES ('Paso 1','$idProducto');";
			$lastid =$this->insert_id($sql);
			$sql2="INSERT INTO prd_pasos_acciones_producto (id_paso, id_accion,alias,tiempo,actividad,pieza,tipo,estatus) VALUES 
			('$lastid', 1, 'Formulación de Insumos', '00:00:00', 1, 0, 1, 1),
			('$lastid', 2, 'Registro de insumos utilizados', '00:00:00', 1, 0, 1, 1),
			('$lastid', 9, 'Fin de producción', '00:00:00', 1, 0, 1, 1);";
			$this->query($sql2);
			echo 1;

		}

	}

///////////////// ******** ---- 	FIN	guardar_insumo			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_producto_proceso				------ ************ //////////////////
//////// Inserta un registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia

	function guardar_producto_proceso($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}

	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		//$tipo = ($datos['tipo']==1) ? 5 : 4 ;

	// Guarda la receta y regresa el ID
		$sql="	INSERT INTO
					prd_productos_procesos
						(id_producto, id_proceso)
				VALUES
					(".$datos['id_producto'].",".$datos['id_proceso'].")";
	 	//return $sql;
		$result =$this->insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	guardar_insumo			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_lab_varias				------ ************ //////////////////

	function guardar_lab_varias($objeto){

		switch ($objeto['tipo']){
				case "lab_conceptos":
					//echo "Guardar switch lab_conceptos";
					$sql = "INSERT INTO
									prd_lab_conceptos (id_tipo, parametro, is_numeric, unidad)
									VALUES
									(".$objeto['tipo_concepto'] . ", '" .$objeto['parametro']. "'," . $objeto['is_numeric'] . "," . $objeto['unidad'] . ")";
					break;
		}


		$result =$this->insert_id($sql);

		return $result;


	}

///////////////// ******** ---- 	FIN	guardar_lab_varias			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_lab_registro				------ ************ //////////////////

	function guardar_lab_registro($objeto){

		$sql = "INSERT INTO
						prd_lab_registro (id_producto, id_orden_produccion, num_mezclas, fecha_elaboracion, fecha_recepcion, fecha_liberacion,
						fecha_caducidad, fecha_analisis, lote_analisis, lote_fabricacion, lote_produccion)
						VALUES
							(".$objeto['producto'] . ", '" .$objeto['orden_produccion']. "', " . $objeto['numero_mezclas'] . ", '" .
							$objeto['fecha_elaboracion'] . "', '" . $objeto['fecha_recepcion'] . "', '" . $objeto['fecha_liberacion'] . "', '" .
							$objeto['fecha_caducidad'] . "', '" . $objeto['fecha_analisis'] . "', '" . $objeto['lote_analisis'] . "', '" .
							$objeto['lote_fabricacion'] . "', '" . $objeto['lote_produccion'] . "')";

		$result =$this->insert_id($sql);
		return $result;


	}

///////////////// ******** ---- 	FIN	guardar_lab_registro			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_lab_detalle				------ ************ //////////////////

	function guardar_lab_detalle($id_repote, $id_concepto, $valor_num, $valor_alfa){

		$sql = "INSERT INTO
						prd_lab_registro_detalle (id_reporte, id_concepto, valor_num, valor_alfa)
						VALUES
							(" . $id_repote . ", " . $id_concepto . ", " .
							$valor_num . ", '" . $valor_alfa . "')";

		$result =$this->insert_id($sql);
		return $result;


	}

///////////////// ******** ---- 	FIN	guardar_lab_detalle			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_app_productos				------ ************ //////////////////

	function guardar_app_productos($nombre, $codigo, $cant_min, $unidad, $factor){

		$sql = "INSERT INTO
							app_productos (nombre, codigo, minimos, tipo_producto, id_unidad_compra, id_unidad_venta,factor)
						VALUES
							('" . $nombre . "', '" . $codigo . "', " . $cant_min . ", 5, " . $unidad . ", " . $unidad .  ",  ".$factor." )";

		$result =$this->insert_id($sql);
		return $result;


	}

///////////////// ******** ---- 	FIN	guardar_app_productos			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_com_recetas				------ ************ //////////////////

	function guardar_com_recetas($id, $nombre, $ids_insumos){

		$sql = "INSERT INTO
							com_recetas (id, nombre, precio, ganancia, ids_insumos, status)
						VALUES
							(" . $id . ", '" . $nombre . "', 0, 0, '" . $ids_insumos . "', 1)";

		$result =$this->insert_id($sql);
		return $result;
	}

///////////////// ******** ---- 	FIN	guardar_com_recetas			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_app_producto_material				------ ************ //////////////////

	function guardar_app_producto_material($id_app_prd, $cant_min, $unidad_codigo, $id_material, $status){

		$sql = "INSERT INTO
							app_producto_material (id_producto, cantidad, id_unidad, id_material, status)
						VALUES
							(" . $id_app_prd . ", " . $cant_min . ", " . $unidad_codigo . ", " . $id_material . ", 1)";

		$result =$this->insert_id($sql);
		return $result;
	}

///////////////// ******** ---- 	FIN	guardar_app_producto_material			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_insumos			------ ************ ///////////////////////////////////
	//////// Consulta los insumos y los regresa en un array
		// Como parametros recibe:

	function listar_insumos($objeto){
	// Filtra por el ID del insumo si existe
		$condicion.=(!empty($objeto['id']))?' AND p.id='.$objeto['id']:'';
	// Filtra por el nombre y codigo de insumo del insumo si existe
		$condicion.=(!empty($objeto['nombre'])&&!empty($objeto['codigo']))?'
				AND
					(p.nombre=\''.$objeto['nombre'].'\'
						OR
					p.codigo=\''.$objeto['codigo'].'\')':'';
	// Filtra por el tipo de producto si existe
		$condicion.=(!empty($objeto['tipo_producto']))?' AND p.tipo_producto='.$objeto['tipo_producto']:'';

	// Si es insumo preparado toma "p.costo_servicio", si no toma el costo del proveedor y si es null lo cambia por 0
         $sql="	SELECT
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
						status=1".
					$condicion;
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_insumos		------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar				------ ************ ///////////////////////////////////
//////// Consulta las recetas y los regresa en un array
	// Como parametros puede recibir:
		// id -> ID de receta
		// insumos_preparados -> IDs de los insumos preparados
		// tipo -> tipo de producto
		// orden -> orden de la consulta


	function listar2(){
		
		$sql = "SELECT c.id, c.codigo, c.nombre from prd_pasos_producto a
		inner join prd_pasos_acciones_producto b on b.id_paso=a.id
		inner join app_productos c on c.id=a.id_producto
		group by a.id_producto;";

		return $this->queryArray($sql);
	}

	function listar($objeto){
	// Filtra por el ID de la receta si existe
		$condicion.=(!empty($objeto['id']))?' AND r.id='.$objeto['id']:'';
	// Filtra por los insumos preparados
		$condicion.=(!empty($objeto['insumos_preparados']))?' AND ids_insumos_preparados!=\'\'':'';
	// Filtra por tipo
		$condicion.=(!empty($objeto['tipo'])) ? ' AND p.tipo_producto = '.$objeto['tipo'] : '';
	// Filtros
		$condicion.=(!empty($objeto['filtro']) && $objeto['filtro'] == 'insumos_preparados_formula') ? ' AND (p.tipo_producto = 8 OR p.tipo_producto = 9) ' : '';


	// Ordena  la consulta si existe
		$condicion.=(!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:'';

		$sql = "SELECT
					p.id AS idProducto, p.nombre, p.costo_servicio AS costo,
					p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, p.factor as multiplo,
					(SELECT
						nombre
					FROM
						app_unidades_medida uni
					WHERE
						uni.id=p.id_unidad_venta) AS unidad, u.factor, p.tipo_producto,
					r.ids_insumos_preparados AS insumos_preparados, r.ids_insumos AS insumos,
					r.preparacion, r.ganancia, ROUND(p.precio, 2) AS precio, p.codigo, p.minimos
				FROM
					app_productos p
				LEFT JOIN
						app_campos_foodware f
					ON
						p.id = f.id_producto
				LEFT JOIN
						com_recetas r
					ON
						r.id = p.id
				LEFT JOIN
						app_unidades_medida u
					ON
						u.id = p.id_unidad_compra
				WHERE
					p.status = 1 AND (p.tipo_producto = 8 OR p.tipo_producto = 9)";

		return $this->queryArray($sql);
	}

///////////////// ******** ---- 		FIN listar			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_productos_proceso				------ ************ ///////////////////////////////////
//////// Lista todos aquellos productos que tengan Procesos de Producción asociados.
/// Se utiliza para el módulo Procesos de Producción - Editar


	function listar_productos_proceso(){

		$sql = "SELECT
							distinct b.id, b.codigo, b.nombre
						FROM
							prd_productos_procesos a
						INNER JOIN
							app_productos b on a.id_producto = b.id
						ORDER BY b.nombre";

		$result = $this->queryArray($sql);
		return $result;
	}

///////////////// ******** ---- 		FIN listar_productos_proceso			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_productos_conceptos_lab				------ ************ ///////////////////////////////////
//////// Lista todos aquellos productos que tengan Conceptos de Laboratorio asociados.
/// Se utiliza para el módulo Conceptos Lab- Productos - Editar


	function listar_productos_conceptos_lab(){

		$sql = "SELECT
							distinct b.id, b.codigo, b.nombre
						FROM
							prd_lab_conceptos_productos a
							INNER JOIN app_productos b ON a.id_producto = b.id
						WHERE
							a.id_producto NOT IN
							(SELECT DISTINCT id_producto FROM prd_lab_registro) AND
							(b.tipo_producto = 4 OR b.tipo_producto = 5)";

		$result = $this->queryArray($sql);
		return $result;
	}

///////////////// ******** ---- 		FIN listar_productos_conceptos_lab			------ ************ ///////////////////////////////////


///////////////// ******** ---- 		listar_procesos_por_producto				------ ************ ///////////////////////////////////

	function listar_procesos_por_producto($id_producto){
		$sql = "SELECT
							b.id, b.nombre, b.tiempo_hrs
						FROM
							prd_productos_procesos a
						INNER JOIN
							prd_procesos b on a.id_proceso = b.id
						WHERE
							a.id_producto = " . $id_producto;
		return $this->queryArray($sql);
	}

///////////////// ******** ---- 		FIN listar_procesos_por_producto			------ ************ ///////////////////////////////////



///////////////// ******** ---- 		listar_conceptos_lab_por_producto				------ ************ ///////////////////////////////////
	function listar_conceptos_lab_por_producto($id_producto){
		$sql = "SELECT 	a.id as id_lab_cpt_prd, a.id_producto, a.id_lab_concepto as id, a.lim_inf, a.lim_sup,
										a.referencia, b.parametro, b.is_numeric, c.descripcion as unidad
						FROM		prd_lab_conceptos_productos a
										INNER JOIN prd_lab_conceptos b on a.id_lab_concepto = b.id
										INNER JOIN prd_lab_unidades c on b.unidad = c.id
						WHERE 	id_producto = " . $id_producto . "
						ORDER BY a.id";

		return $this->queryArray($sql);
	}

///////////////// ******** ---- 		FIN listar_conceptos_lab_por_producto			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_procesos				------ ************ ///////////////////////////////////
//////// Consulta los procesos y los regresa en un array
	// Como parametros puede recibir:
		// id -> ID de receta
		// insumos_preparados -> IDs de los insumos preparados
		// tipo -> tipo de producto
		// orden -> orden de la consulta

	function listar_procesos($objeto){

		$sql = "SELECT
							id, nombre, tiempo_hrs
						FROM
							prd_procesos
						ORDER BY id";

		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_procesos			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_acciones_procesos				------ ************ ///////////////////////////////////
//////// Consulta los procesos y los regresa en un array
	// Como parametros puede recibir:
		// id -> ID de receta
		// insumos_preparados -> IDs de los insumos preparados
		// tipo -> tipo de producto
		// orden -> orden de la consulta

	function listar_acciones_procesos($objeto){

		$sql = "SELECT
							id, nombre, tiempo_hrs
						FROM
							prd_acciones
						ORDER BY id";

		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_acciones_procesos			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_familias				------ ************ ///////////////////////////////////
//////// Consulta las familias de productos
	// No recibe parametros

	function listar_familias($objeto){

		$sql = "SELECT
							id, nombre
						FROM
							app_familia
						WHERE id in
							(
								select
									distinct familia
								from
									app_productos
								where
									status = 1 and
									familia is not null and
									familia <>0
							)
						ORDER BY id";

		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_familias			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_lab_general				------ ************ ///////////////////////////////////
//////// Consulta generica que entrega id y descripcion de varias tablas
// Como parametros recibe:
	// $consulta['campos'] -> Campos usados por la cláusula SELECT
	// $consulta['from'] -> Campo usado por la cláusula FROM

	function listar_lab_general($campos, $from){

		$sql = "SELECT " .
							$campos . "
						FROM ".
							$from . "
						ORDER BY id";

		//return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_lab_unidades			------ ************ ///////////////////////////////////



///////////////// ******** ---- 		listar_lab_unidades				------ ************ ///////////////////////////////////
//////// Consulta las unidades en pruebas de laboratorio
	// No recibe parametros

	function listar_lab_conceptos($opcion){

		$sql = "SELECT
							a.id as id, b.descripcion as tipo, a.parametro as parametro, c.id as id_unidad, c.descripcion as unidad, a.is_numeric as is_numeric
						FROM
							prd_lab_conceptos a
						INNER JOIN
							prd_lab_tipos b on a.id_tipo = b.id
						INNER JOIN
							prd_lab_unidades c on a.unidad = c.id";

		if ($opcion == "utilizado")
				$sql = $sql . " WHERE a.id NOT IN (SELECT DISTINCT id_lab_concepto FROM prd_lab_conceptos_productos) ";

		// return $sql;
		$result = $this->queryArray($sql);
		return $result;
	}

///////////////// ******** ---- 		FIN listar_lab_unidades			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_lab_conceptos_productos			------ ************ ///////////////////////////////////

	function guardar_lab_conceptos_productos($objeto, $producto_sel) {

		$lim_inf = -1;
		$lim_sup = -1;
		$referencia = null;

		if ($objeto['is_numeric'] == 1){
			$lim_inf = (is_null($objeto['lim_inf']) ? "0" : $objeto['lim_inf']);
			$lim_sup = (is_null($objeto['lim_sup']) ? "0" : $objeto['lim_sup']);
		} else {
			$referencia = $objeto['referencia'];
		}

		$sql = "INSERT INTO
						prd_lab_conceptos_productos (id_producto, id_lab_concepto, lim_inf, lim_sup, referencia)
						VALUES
							(" . $producto_sel . ", " . $objeto['id'] . ", " . $lim_inf . ", " .
							$lim_sup . ", '" . $referencia . "')";

		$result = $this->queryArray($sql);
		return $result;

	}


///////////////// ******** ---- 		FIN guardar_lab_conceptos_productos			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_insumos_producto			------ ************ ///////////////////////////////////

	function guardar_insumos_producto($objeto, $producto_sel) {

		// com_recetas
		$sql = "INSERT INTO
						prd_lab_conceptos_productos (id_producto, id_lab_concepto, lim_inf, lim_sup, referencia)
						VALUES
							(" . $producto_sel . ", " . $objeto['id'] . ", " . $lim_inf . ", " .
							$lim_sup . ", '" . $referencia . "')";

		$result = $this->queryArray($sql);
		return $result;
	}


///////////////// ******** ---- 		FIN guardar_insumos_producto			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		cargar_formulario_lab				------ ************ ///////////////////////////////////

	function cargar_formulario_lab($producto){
		$sql = "select
							b.id, b.parametro, d.descripcion as unidad, b.is_numeric, a.lim_inf, a.lim_sup, a.referencia
						from
							prd_lab_conceptos_productos a
						inner join
							prd_lab_conceptos b on a.id_lab_concepto = b.id
						inner join
							prd_lab_tipos c on b.id_tipo = c.id
						inner join
							prd_lab_unidades d on b.unidad = d.id
						where
							a.id_producto = " . $producto . "
						order by c.id, a.id";

		// return $sql;
		$result = $this->queryArray($sql);
		return $result;
	}

///////////////// ******** ---- 		FIN cargar_formulario_lab				------ ************ ///////////////////////////////////


///////////////// ******** ---- 		listar_producots_por_familia				------ ************ ///////////////////////////////////
//////// Consulta las familias de productos
	// No recibe parametros

	function listar_productos_por_familia($id_producto){

		$sql = "SELECT
							id, nombre
						FROM
							app_productos
						WHERE
							familia = " . $id_producto . " and status = 1
						ORDER BY id";

		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_familias			------ ************ ///////////////////////////////////


///////////////// ******** ---- 	listar_conversion		------ ************ ///////////////////////////////////
	//////// Consulta las conversiones y las regresa en un array
		// Como parametros recibe:
			// unidad -> id de la unidad

	function listar_conversion($objeto){
		$sql="	SELECT
					factor AS conversion
				FROM
					app_unidades_medida
				WHERE
					id =".$objeto['unidad'];
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_conversion	------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_materiales	------ ************ ///////////////////////////////////
	//////// Consulta los insumos y los regresa en un array
		// Como parametros recibe:

	function listar_pasos($idProd){
		$sql="SELECT a.id as id_paso, a.descripcion as nombre_paso, a.id_producto, b.id as id_accion_producto, c.id as id_accion, c.nombre as nombre_accion, c.tiempo_hrs, d.nombre, b.alias, b.tiempo, b.actividad, b.pieza  from prd_pasos_producto a
inner join prd_pasos_acciones_producto b on b .id_paso=a.id
inner join prd_acciones c on c.id=b.id_accion
inner join app_productos d on d.id=a.id_producto
where a.id_producto='$idProd' order by a.id asc;";
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

	function listar_materiales($objeto){
	// Filtra por el ID del insumo si existe
		$condicion.=(!empty($objeto['id']))?' AND p.id='.$objeto['id']:'';
	// Filtra por los IDs de los insumo si existe
		$condicion.=(!empty($objeto['ids']))?' AND p.id IN ('.$objeto['ids'].')':'';

	// Filtra por el nombre del insumo si existe
		$condicion.=(!empty($objeto['tipo_producto']))?' AND p.tipo_producto='.$objeto['tipo_producto']:'';

		$sql = "SELECT
					p.id AS idProducto, p.nombre, IF(p.tipo_producto=4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
					p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad,
					(SELECT
						nombre
					FROM
						app_unidades_medida uni
					WHERE
						uni.id=p.id_unidad_venta) AS unidad,
						(SELECT
							clave
						FROM
							app_unidades_medida uni
						WHERE
							uni.id=p.id_unidad_venta) AS unidad_clave, p.codigo, u.factor, m.cantidad, m.opcionales AS opcionales
				FROM
					app_productos p
				INNER JOIN
						app_producto_material m
					ON
						p.id=m.id_material
				LEFT JOIN
						app_unidades_medida u
					ON
						u.id=p.id_unidad_compra
				LEFT JOIN
						app_costos_proveedor pro
					ON
						pro.id_producto=p.id
				WHERE
					p.status=1
				AND
					m.id_producto = ".$objeto['id_receta'].
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_materiales		------ ************ /////////////////////////////

///////////////// ******** ---- 		eliminar_insumos			------ ************ ////////////////////////////
	//////// Elimina los materiales de la receta o insumo preparado
		// Como parametros recibe:
			// id_receta -> id de la receta

	function eliminar_insumos($objeto){
		$sql="	DELETE FROM
					 app_producto_material
				WHERE
					id_producto =".$objeto['id'];
		// return $sql;
		$result = $this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN eliminar_insumos		------ ************ ///////////////////////////

///////////////// ******** ---- 		eliminar_procesos_produccion			------ ************ ////////////////////////////
	//////// Elimina los materiales de la receta o insumo preparado
		// Como parametros recibe:
			// id_receta -> id de la receta

	function eliminar_procesos_produccion($producto){
		$sql = "DELETE FROM prd_productos_procesos WHERE id_producto = " . $producto;
		$result = $this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN eliminar_procesos_produccion		------ ************ ///////////////////////////

///////////////// ******** ---- 		eliminar_lab_conceptos_productos			------ ************ ////////////////////////////
	//////// Elimina los materiales de la receta o insumo preparado
		// Como parametros recibe:
			// id_receta -> id de la receta

	function eliminar_lab_conceptos_producto($producto){
		$sql = "DELETE FROM prd_lab_conceptos_productos WHERE id_producto = " . $producto;
		$result = $this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN eliminar_lab_conceptos_productos		------ ************ ///////////////////////////


///////////////// ******** ---- 				eliminar			------ ************ //////////////////////////
//////// Elimina una receta o insumo preparado, el producto y sus materiales
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado

	function eliminar($objeto){
		$sql="	UPDATE
					com_recetas
				SET
					status = 2
				WHERE
					id = ".$objeto['id'].";

				UPDATE
					app_productos
				SET
					status = 0
				WHERE
					id = ".$objeto['id'].";

				UPDATE
					app_producto_material
				SET
					status = 0
				WHERE
					id_producto = ".$objeto['id'].";";
		// return $sql;
		$result = $this->dataTransact($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN eliminar		------ ************ ///////////////////////////

///////////////// ******** ---- 			restaurar_precio			------ ************ //////////////////
//////// Busca el precio actual del producto y lo agrega al campo precio_venta
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado
		// btn -> boton del loader

	function restaurar_precio($objeto){
		$sql="	SELECT
					ROUND(precio, 2) AS precio
				FROM
					app_productos
				WHERE
					id =".$objeto['id'];
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN restaurar_precio		------ ************ ///////////////////////////////////

///////////////// ******** ---- 			listar_unidades				------ ************ ///////////////////
	//////// Consulta las unidades de medida y los regresa en un array
		// Como parametros recibe:

	function listar_unidades($objeto){
		$sql = "SELECT
					*
				FROM
					app_unidades_medida";
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN listar_unidades			------ ************ ///////////////////

///////////////// ******** ---- 			preparar_insumo				------ ************ //////////////////
//////// Guarda en la BD la preparacion del insumo
	// Como parametros recibe:
		// id -> ID del preparado
		// cantidad -> Cantidad que se debe preparar del insumo
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final

	function preparar_insumo($objeto){
		$sql = "INSERT INTO
					com_preparaciones(id_producto, cantidad, f_ini)
				VALUES
					(".$objeto['id_producto'].", ".$objeto['cantidad'].", '".$objeto['f_ini']."')";
		// return $sql;
		$result = $this->insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN preparar_insumo			------ ************ //////////////////

///////////////// ******** ---- 			descontar_insumo			------ ************ //////////////////
//////// Descuenta del inventario el insumo del producto
	// Como parametros recibe:
		// id -> ID del insumo
		// id_producto -> ID del producto

	function descontar_insumo($objeto) {
		session_start();
	// Obtiene el almacen
		$almacen = "SELECT
						a.id
					FROM
						administracion_usuarios au
					LEFT JOIN
							app_almacenes a
						ON
							a.id_sucursal = au.idSuc
					WHERE
						au.idempleado = " . $_SESSION['accelog_idempleado'] . "
					LIMIT 1";
		$almacen = $this -> queryArray($almacen);
		$almacen = $almacen['rows'][0]['id'];

	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;

		$sql = "INSERT INTO
					app_inventario_movimientos
						(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
						tipo_traspaso, costo, referencia)
					VALUES
						('" . $objeto['idProducto'] . "', '" . $objeto['cantidad'] . "', '" . $objeto['importe'] . "',
						'" . $almacen . "', '" . $objeto['fecha'] . "', '" . $_SESSION['accelog_idempleado'] . "', 0,
						'" . $objeto['costo'] . "', 'Preparacion " . $objeto['id_preparacion'] . "')";
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN descontar_insumo			------ ************ //////////////////

///////////////// ******** ---- 				terminar_insumo				------ ************ //////////////////
//////// Actualiza el inventario y el insumo preparado en la BD
	// Como parametros recibe:
		// id -> ID del insumo preparado
		// id_preparacion -> ID de la preparacion
		// cantidad -> Cantidad que se debe preparar del insumo

	function terminar_insumo($objeto){
		session_start();
	// Obtiene el almacen
		$almacen = "SELECT
						a.id
					FROM
						administracion_usuarios au
					LEFT JOIN
							app_almacenes a
						ON
							a.id_sucursal = au.idSuc
					WHERE
						au.idempleado = " . $_SESSION['accelog_idempleado'] . "
					LIMIT 1";
		$almacen = $this -> queryArray($almacen);
		$almacen = $almacen['rows'][0]['id'];

	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;

	// Actualiza el inventario y el insumo preparado en la BD
		$sql = "UPDATE
					com_preparaciones
				SET
					f_fin = '".$objeto['f_fin']."'
				WHERE
					id = ".$objeto['id_preparacion'].";

				INSERT INTO
					app_inventario_movimientos
					(id_producto, cantidad, importe, id_almacen_destino, fecha, id_empleado,
					tipo_traspaso, costo, referencia)
				VALUES
					('" . $objeto['id'] . "', '" . $objeto['cantidad'] . "', '" . $objeto['importe'] . "',
					'" . $almacen . "', '" . $objeto['f_fin'] . "', '" . $_SESSION['accelog_idempleado'] . "', 1,
					'" . $objeto['precio'] . "', 'Preparacion " . $objeto['id_preparacion'] . "');";
		// return $sql;
		$result = $this->dataTransact($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN	terminar_insumo				------ ************ //////////////////
} ?>
