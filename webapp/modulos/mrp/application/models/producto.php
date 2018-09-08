<?php
	class Producto extends CI_Model {
		//////////////////////////////////////////
		function __construct() {
			parent::__construct();
		}

		////////////////////////////////////////////////////
		function elimina($id) {
			$this->load->database();
			$query = $this->db->query('DELETE FROM producto_impuesto WHERE idProducto='.$id);
			$query = $this->db->query('DELETE FROM mrp_stock WHERE idProducto='.$id);
			$query = $this->db->query('DELETE FROM inventarios_movimientos WHERE idProducto='.$id);
			$query = $this->db->query('DELETE FROM mrp_producto_proveedor WHERE idProducto='.$id);
			$query = $this->db->query('DELETE FROM mrp_producto WHERE idProducto='.$id);
		}

		//////////////////////////////////////////////// INICIA ELIMINAR INSUMOS ////////////////////////////////////
		function eliminar_insumos($idProducto) {
			$this->load->database();
			$query = $this->db->query('DELETE from mrp_producto_material where idProducto ='.$idProducto);
			session_start();
			$_SESSION["materiales_selecionados"]='';
			unset($_SESSION["materiales_selecionados"]);
			//return $query->result();
		}

		/////////////////////////////////////////////INICIA////////////////////////////////////////////////////////////
		function grid($pagina="1",$filtro=1,$paginacion=10) {
			if ($filtro!=1) {
				list($valor,$campo) = explode("_",$filtro);
				$filtro=$campo." like '%".$valor."%'";
			}

			$this->load->database();
			if ($pagina==1) {
				$begin=0;
			} else {
				$begin=($paginacion*$pagina)-$paginacion;
			}

			$query = $this->db->query('SELECT
					p.idProducto ID,
					p.codigo Codigo,
					p.nombre Nombre,
					d.nombre Departamento,
					f.nombre Familia,
					l.nombre Linea,
					p.precioventa Precio
				FROM mrp_producto p inner Join
					mrp_linea l on p.idLinea=l.idLin inner Join
					mrp_familia f on f.idFam=l.idFam inner Join
					mrp_departamento d on d.idDep=f.idDep left Join
					mrp_color c on c.idCol=p.color left Join
					mrp_talla t on p.talla=t.idTal
				where '.$filtro.' order by p.idProducto  LIMIT '.$begin.','.$paginacion
			);

			/*
			,c.color Color,
			t.talla Talla,
			p.maximo Máximo,
			p.minimo Minimo
			*/

			$q=$this->db->query('SELECT idProducto from  mrp_producto');
			$paginas=($q->num_rows()/$paginacion);if($q->num_rows()%$paginacion!=0){$paginas++;}

			$grid=array();
			$grid["data"]=$query->result_array();
			$grid["nombre"]='Producto';
			$grid["paginas"]=$paginas;
			return $grid;
		}

		///////////////////////////////////////////////
		public function materiales($producto) {
			$this->load->database();
			$q=$this->db->query('SELECT pm.idProducto,pm.cantidad,pm.idUnidad,pm.idMaterial,pm.opcional,p.nombre,u.compuesto,p.costo,p.tipo_producto
				from mrp_producto_material pm, mrp_producto p, mrp_unidades u
				where pm.idMaterial=p.idProducto and  u.idUni=pm.idUnidad and pm.idProducto='.$producto);
			$lista_materiales=$q->result();

			if($q->num_rows()>0) {
				session_start();
				$_SESSION["materiales_selecionados"]='';

				foreach($lista_materiales as $material) {
					$_SESSION["materiales_selecionados"][$material->nombre]=$material->idMaterial."_".$material->compuesto."_".$material->cantidad."_".$material->idUnidad."_".$material->opcional."_".$material->costo."_".$material->tipo_producto;
				}
			}
		}

		public function encuentraCosto($idMaterial) {
			$this->load->database();
			$q=$this->db->query('SELECT costo from mrp_producto where idProducto ='.$idMaterial);
			$costo = $q->result();
			return $costo[0]->costo;
		}

		public function unidadConversion($idUnidad) {
			$this->load->database();
			//echo 'SELECT conversion from mrp_unidades where idUni ='.$idUnidad;
			$q=$this->db->query('SELECT conversion from mrp_unidades where idUni ='.$idUnidad);
			$conversion = $q->result();
			return $conversion[0]->conversion;
		}

		public function unidadVentaConver($idMaterial) {
			$this->load->database();
			//echo 'SELECT conversion from mrp_unidades where idUni ='.$idUnidad;
			$q=$this->db->query('SELECT idunidadCompra from mrp_producto where idProducto ='.$idMaterial);
			$uni = $q->result();
			return $uni[0]->idunidadCompra;
		}

		public function selunidades() {
			$this->load->database();
			$q=$this->db->query('SELECT * from mrp_unidades');
			$lista_unidades=$q->result();
			if($q->num_rows()>0) {
				return $lista_unidades;
			} else {
				return 0;
			}
		}

		//////////////////////////////////////////
		function giveProducto($id) {
			$this->load->database();
			$query = $this->db->query(' SELECT p.idProveedor,p.costo,p.precioventa,p.preciomayoreo,p.precioliquidacion, p.esreceta, p.eskit, p.vendible, p.consumo, p.tipo_producto,
					d.idDep ,f.idFam,p.idProducto,p.codigo,p.nombre,p.deslarga,p.descorta, p.descenefa,p.color,p.talla,p.idunidad,p.idunidadCompra, p.vendible,p.consumo,p.idLinea,
					p.barcode,p.maximo,p.minimo,p.imagen, p.stock_inicial AS inicial, p.margen_ganancia, p.descu
				FROM mrp_producto p inner Join
					mrp_linea l on p.idLinea=l.idLin inner Join
					mrp_familia f on f.idFam=l.idFam inner Join
					mrp_departamento d on d.idDep=f.idDep
				where p.idProducto='.$id);
			return $query->result();
		}

		function consultaStock($id){
			$this->load->database();
			if (!isset($_SESSION['almacen'])) {
				$strSql = " SELECT au.idSuc,mp.nombre ";
				$strSql .= " FROM administracion_usuarios au,mrp_sucursal mp ";
				$strSql .= " WHERE mp.idSuc=au.idSuc AND au.idempleado=" . $_SESSION['accelog_idempleado'];

				$q = $this->db->query($strSql);

				if ($q->result_array["total"] > 0) {
					$_SESSION["sucursal"] = $q->result_array["rows"][0]["idSuc"];
				} else {
					$_SESSION["sucursal"] = 1;
				}

				$strSql = "SELECT s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen ";
				$strSql .= " FROM mrp_sucursal s, almacen a ";
				$strSql .= " WHERE s.idAlmacen=a.idAlmacen AND s.idSuc=" . $_SESSION["sucursal"];

				$qsuc = $this->db->query($strSql);
				if ($q->result_array["total"] > 0) {
					$_SESSION["almacen"] = $qsuc->result_array["rows"][0]['idAlmacen'];
				} else {
					$_SESSION["almacen"] = 1;
				}
			}
			$query = $this->db->query('SELECT cantidad from mrp_stock where idProducto='.$id.' and idAlmacen='.$_SESSION["almacen"]);
			return $query->result_array();
		}

		function ordenesCompra($id) {
			$this->load->database();
			$query= $this->db->query('SELECT  sum(cantidad) as total from mrp_producto_orden_compra where idProducto='.$id);
			return $query->result();
		}

		//////////////////////////////////////////
		function listaProductos($idprodcto) {
			$this->load->database();
			$query = $this->db->query('SELECT idProducto,nombre,idunidad FROM mrp_producto where idProducto<>'.$idprodcto.' and estatus=1');
			return $query->result();
		}

		function listaProductosNN($idprodcto) {
			$this->load->database();
			/*$query = $this->db->query('SELECT idProducto,nombre,tipo_producto FROM mrp_producto where idProducto<>'.$idprodcto.''); */
			$query = $this->db->query('SELECT idProducto,nombre,tipo_producto,idunidad FROM mrp_producto where idProducto<>'.$idprodcto.' AND (tipo_producto=2 OR tipo_producto=1) and estatus=1');
			return $query->result();
		}

		function listaProductosProduccion($idprodcto) {
			$this->load->database();
			/*$query = $this->db->query('SELECT idProducto,nombre,tipo_producto FROM mrp_producto where idProducto<>'.$idprodcto.''); */
			//$query = $this->db->query('SELECT idProducto,nombre,tipo_producto,idunidad FROM mrp_producto where estatus=1 and idProducto<>'.$idprodcto.' AND (tipo_producto=2 OR tipo_producto=4)');
			$query = $this->db->query('SELECT idProducto,nombre,tipo_producto,idunidad FROM mrp_producto where estatus=1 and idProducto<>'.$idprodcto.' AND tipo_producto=2');
			return $query->result();
		}

		//////////////////////////////////////////
		function listaUnidadesCombo($id) {
			$this->load->database();
			$query = $this->db->query('SELECT unidades.idUni, unidades.compuesto from mrp_producto as producto, mrp_unidades as unidades where producto.idunidad=unidades.idUni and producto.idProducto='.$id);
			//echo 'SELECT unidades.idUni, unidades.compuesto from mrp_producto as producto, mrp_unidades as unidades where producto.idunidad=unidades.idUni and producto.idProducto='.$id;
			return $query->result_array();
		}

		function listaUnidades() {
			//echo $id;
			$this->load->database();
			$query = $this->db->query('SELECT idUni, compuesto FROM mrp_unidades' );
			return $query->result();
		}

		function listaUnidadesConversion() {
			$this->load->database();
			$query = $this->db->query('SELECT id, tipo compuesto,identificadores FROM unid_generica');
			return $query->result();
		}

		//////////////////////////////////////////
		function buscaColor() {
			$this->load->database();
			$query = $this->db->query('SELECT idCol, color FROM mrp_color');
			return $query->result();
		}

		//////////////////////////////////////////
		function buscaTalla() {
			$this->load->database();
			$query = $this->db->query('SELECT idTal, talla FROM mrp_talla');
			return $query->result();
		}

		//////////////////////////////////////////
		function buscaDepartamento() {
			$this->load->database();
			$query = $this->db->query('SELECT idDep, nombre FROM mrp_departamento');
			return $query->result();
		}

		//////////////////////////////////////////
		function buscaProveedor() {
			$this->load->database();
			$query = $this->db->query('SELECT idPrv, razon_social FROM mrp_proveedor');
			return $query->result();
		}

		function buscaProveedorEdit($id){
			$this->load->database();
			if($id!=0){
				$query1 = $this->db->query('SELECT idProveedor from mrp_producto where idProducto='.$id);
				$idprov=$query1->result();
				$query = $this->db->query('SELECT idPrv, razon_social FROM mrp_proveedor where idPrv='.$idprov[0]->idProveedor);
				return $query->result();
			} else {
				$query = $this->db->query('SELECT idPrv, razon_social FROM mrp_proveedor where idPrv='.$id);
				return $query->result();
			}
		}

		function consultaProbesmasivos($id) {
			$this->load->database();
			$query = $this->db->query('SELECT p.idPrv, p.razon_social, pp.costo from mrp_proveedor as p, mrp_producto_proveedor as pp where p.idPrv=pp.idPrv and pp.idProducto='.$id);
			return $query->result();
		}

		function consultaPrecios($id) {
			$this->load->database();
			$query = $this->db->query('SELECT id,descripcion,precio,orden from mrp_lista_precios where idProducto='.$id);
			return $query->result();
		}

		//////////////////////////////////////////
		function buscaFamilia($idDep) {
			$this->load->database();
			if(is_numeric($idDep)) {
				$query = $this->db->query('SELECT idFam, nombre FROM mrp_familia WHERE idDep = "'.$idDep.'"');
			} else {
				$query = $this->db->query('SELECT idFam, nombre FROM mrp_familia');
			}
			return $query->result();
		}

		//////////////////////////////////////////
		function buscaLinea($idFam) {
			$this->load->database();
			if(is_numeric($idFam)) {
				$query = $this->db->query('SELECT distinct nombre FROM mrp_linea WHERE idFam = "'.$idFam.'"');
			} else {
				$query = $this->db->query('SELECT idLin, nombre FROM mrp_linea');
			}
			return $query->result();
		}

		///////////////////////////////////////////// INICIO FUNCION ELIMINAR ETAPAS /////////////////////////////////////////
		/*function eliminar_etapa($idProducto) {
			$this->load->database();
			$query = $this->db->query('SELECT id FROM mrp_etapas WHERE id_producto ='.$idProducto);
			while($row = mysql_fetch_array($query)) {
				$id = $row['id'] = 
				$query1 = $this->db->query('DELETE FROM mrp_etapas WHERE id_producto = '.$idProducto);
			}
		}*/

		function eliminar_proceso($idProducto) {
			$this->load->database();
			$query = $this->db->query('DELETE a.*, b.* FROM mrp_etapas a LEFT JOIN mrp_procesos b ON b.id_etapa = a.id WHERE a.id_producto ='.$idProducto);
		}

		///////////////// ******** ---- 		 registraProducto		------ ************ //////////////////
		// Guarda el producto en la BD
		function registraProducto($proveedor,$costo,$preciov,$preciom,$preciol,$id,	$nombre, $des_lar, $des_cor, $des_cen, $color,
								$talla, $linea,$materiales,$maximo,$minimo,$imagen,$codigo, $consumo, $vendible,$esreceta, $impuestos_ids,
								$impuestos_valores, $inicial, $eskit,$unidad,$tipop,$etapas,$unidadCompra,$provesmasiv,$costo_produccion,
								$margen,$listaprecios,$descx) {
			/////traduce la linea al idLinea
			//$linea=5;

			// Conexion a la BD
			$this->load->database();
			$this->db->trans_start();
			$cadena;

			//** Valida que no exista el codigo antes de registrar
			if(!is_numeric($id)){//si no es edicion
				$qrepetido=mysql_query("Select nombre from mrp_producto where estatus=1 and strcmp('".$nombre."',nombre)=0 OR strcmp('".$codigo."',codigo)=0");
				$error['qrepetido']=$qrepetido;
				
				if (mysql_num_rows($qrepetido)>0) {
					return "El nombre o el código del producto ya existe en la base de datos";
				}
			} else {
				$editarepe=mysql_query('select * from mrp_producto where idProducto!='.$id.' and codigo="'.$codigo.'" and nombre <> "'.$nombre.'"');
				$qunidad = $editarepe['idUnidad'];
				$error['editarepe']=$editarepe;

				if (mysql_num_rows($editarepe)>0) {
					return "El código del producto ya existe en la base de datos";
				}
			}

			//** Valida el color
			if(!is_numeric($color)) {
				$color="NULL";
			}

			//** Valida la talla
			if(!is_numeric($talla)){
				$talla="NULL";
			}

			// ** valida la existencia
			if ($tipop==6 && $inicial > 0) {
				return "Los productos catalogados como SERVICIO, no deben llevar cantidad inicial";
			}

			// Si no viene el ID es que es un nuevo producto, crear un nuevo registro en la DB
			if(!is_numeric($id)) {
				$sql='INSERT INTO mrp_producto (	
						nombre,codigo, deslarga, descorta, descenefa, color, talla, vendible, consumo,
						idLinea,maximo,minimo,imagen,barcode,esreceta,precioventa, preciomayoreo,
						precioliquidacion,idProveedor,costo, stock_inicial, eskit,idunidad,tipo_producto,
						idunidadCompra,costo_produccion,margen_ganancia,descu
					) values (
						"'.$nombre.'","'.$codigo.'", "'.$des_lar.'", "'.$des_cor.'", "'.$des_cen.'", '.$color.', 
						'.$talla.', "'.$vendible.'", "'.$consumo.'","'.$linea.'", "'.$maximo.'", "'.$minimo.'", 
						"'.$imagen.'","001002003001", "'.$esreceta.'","'.$preciov.'","'.$preciom.'","'.$preciol.'",
						"'.$proveedor.'","'.$costo.'", '.$inicial.', "'.$eskit.'","'.$unidad.'","'.$tipop.'",
						"'.$unidadCompra.'","'.$costo_produccion.'","'.$margen.'","'.$descx.'"
					)';

				$query = $this->db->query($sql);
				$error['insert producto']=$query;
				$idProducto=mysql_insert_id();

				$query2 = $this->db->query('DELETE FROM producto_impuesto WHERE idProducto= '.$idProducto);
				$error['DELETE FROM producto_impuesto']=$query2;
				for($i = 0; $i<count($impuestos_ids); $i++) {
					$query3 = $this->db->query('INSERT INTO producto_impuesto (idProducto, idImpuesto, valor) VALUES ('.$idProducto.', '.$impuestos_ids[$i].', '.$impuestos_valores[$i].');');
					$error['$impuestos_ids'][$i]=$query3;
				}
				// Si viene el ID es que se trata de una edicion
			} else {
				// validar la existencia
				$nomodifica = $this->db->query('SELECT a.tipo_producto, b.cantidad, a.stock_inicial, a.idunidad FROM mrp_producto as a Inner Join mrp_stock as b On a.idProducto = b.idProducto where a.idProducto='.$id);
				$nomodifica = $nomodifica->result();
				$cantidad = $nomodifica[0]->cantidad;
				$stInicial = $nomodifica[0]['stock_inicial'];
				$id_Unidad = $nomodifica[0]['idunidad'];
				return "Verificando la unidad ".$id_Unidad." con la de ".$unidad." además del stock ".$stInicial;

				if ($cantidad > 0 && $tipop==6) {
					return "No es posible cambiar el tipo de producto a SERVICIO, puesto que ya cuenta con existencia previa";
				}

				// Actualiza el registro del producto
				$sql='	UPDATE mrp_producto 
					SET 
						costo="'.$costo.'", idProveedor="'.$proveedor.'", precioventa="'.$preciov.'",
						preciomayoreo="'.$preciom.'",precioliquidacion="'.$preciol.'", nombre="'.$nombre.'",
						codigo="'.$codigo.'", deslarga="'.$des_lar.'", descorta="'.$des_cor.'",
						descenefa="'.$des_cen.'", color='.$color.',esreceta="'.$esreceta.'",eskit="'.$eskit.'",
						talla='.$talla.', vendible="'.$vendible.'", consumo="'.$consumo.'", idLinea="'.$linea.'",
						maximo="'.$maximo.'",minimo="'.$minimo.'", imagen="'.$imagen.'",barcode="001002003001",
						stock_inicial="'.$inicial.'" , idunidad="'.$unidad.'",idunidadCompra = "'.$unidadCompra.'"
						,tipo_producto="'.$tipop.'", costo_produccion = "'.$costo_produccion.'", descu="'.$descx.'"
					where idProducto='.$id;

				$query = $this->db->query($sql);
				$error['UPDATE mrp_producto']=$query;

				// Elimina los materiales del producto
				$query = $this->db->query('DELETE FROM mrp_producto_material where idProducto='.$id);
				$idProducto=$id;
				$error['DELETE FROM mrp_producto_material']=$query;

				// Elimina los impuestos del producto
				$query2 = $this->db->query('DELETE FROM producto_impuesto WHERE idProducto= '.$idProducto);
				$error['DELETE FROM producto_impuesto']=$query2;

				// Agrega los impuestos del producto
				for($i = 0; $i<count($impuestos_ids); $i++) {
					$query3 = $this->db->query(' INSERT INTO 
							producto_impuesto  (idProducto, idImpuesto, valor)
						VALUES 
							('.$idProducto.', '.$impuestos_ids[$i].', '.$impuestos_valores[$i].');');
					$error['INSERT INTO producto_impuesto']=$query3;
				}

				// Valida el stock inicial
				if($r_stock->stock_inicial>0) {
					$inicial=0;
				}

				// Elimina las etapas del producto si es que las tiene(receta)
				for($i = 0; $i<count($etapas); $i++) {
					// Consulta si tiene procesos
					if($etapas[$i]['name']=='labeletapa') {
						$sql='	SELECT
								id 
							FROM 
								mrp_etapas 
							WHERE 
								id_producto='.$idProducto;
						$query=$this->db->query($sql);
						$error['SELECT id FROM mrp_etapas'][$i]=$query;

						// Si tiene los elimina
						if($query->num_rows()>0) {
							foreach ($query->result_array() as $key => $row) {
								$sql='	DELETE FROM
										mrp_procesos 
									WHERE 
										id_etapa='.$row['id'];
								$query1=$this->db->query($sql);
								$error['SELECT id FROM mrp_etapas'][$i]['procesos'][$key]=$query1;
							}
						}

						// Elimina as etapas de la receta
						$sql='	DELETE FROM
								mrp_etapas
							WHERE
								id_producto='.$idProducto;
						$query2=$this->db->query($sql);
						$error['SELECT id FROM mrp_etapas'][$i]['DELETE FROM mrp_etapas']=$query2;

						// Obtiene el ID de la consulta
						$contEtapa=mysql_insert_id();
						$error['SELECT id FROM mrp_etapas'][$i]['$contEtapa']=$contEtapa;
					}
				}
			} //fin else

			// Guarda los diferentes precios del producto
			for($i = 0; $i<count($listaprecios); $i++) {
				if ($listaprecios[$i]['name']=='descripcion') {
					$sql='	INSERT INTO
							mrp_lista_precios (idProducto,descripcion,precio,orden)
						VALUES
							("'.$idProducto.'","'.$listaprecios[$i]['value'].'","'.$listaprecios[$i+1]['value'].'","'.$listaprecios[$i+2]['value'].'");';
					$query=$this->db->query($sql);
					$error['INSERT INTO mrp_lista_precios'][$i]=$query;
				}
			}

			// Guarda los proveedores
			for($i = 0; $i<count($provesmasiv); $i++) {
				if($provesmasiv[$i]['name']=='idProveedor') {
					$sql='	INSERT INTO
							mrp_producto_proveedor (idProducto,idPrv,costo,idUni)
						VALUES
							("'.$idProducto.'","'.$provesmasiv[$i]['value'].'","'.$provesmasiv[$i+1]['value'].'","'.$unidadCompra.'");';
					$query=$this->db->query($sql);
					$error['INSERT INTO mrp_producto_proveedor'][$i]=$query;
				}
			}

			// Guarda las etapas del producto(receta)
			for($i = 0; $i<count($etapas); $i++) {
				if ($etapas[$i]['name']=='labeletapa') {
					$sql='	INSERT INTO
							mrp_etapas (id_producto,etapa,duracion_eta)
						VALUES
							("'.$idProducto.'","'.$etapas[$i]['value'].'","'.$etapas[$i+2]['value'].'");';
					$query=$this->db->query($sql);
					$error['INSERT INTO mrp_etapas'][$i]=$query;
					$contEtapa=mysql_insert_id();
					$error['INSERT INTO mrp_etapas'][$i]['$contEtapa']=$contEtapa;
				}

				if($etapas[$i]['name']=='pronombre') {
					$sql='	INSERT INTO
							mrp_procesos (id_etapa,proceso,descripcion,duracion,orden)
						VALUES
							("'.$contEtapa.'","'.$etapas[$i]['value'].'","'.$etapas[$i+2]['value'].'","'.$etapas[$i+3]['value'].'","'.$etapas[$i+5]['value'].'"	);';
					$query=$this->db->query($sql);
					$error['INSERT INTO mrp_procesos'][$i]=$query;
				}
			}

			// Obtiene la sucursal
			$sql="	SELECT
					au.idSuc,mp.nombre
				FROM
					administracion_usuarios au,mrp_sucursal mp
				WHERE
					mp.idSuc=au.idSuc
					AND 
					au.idempleado=".$_SESSION['accelog_idempleado'];
			$q=mysql_query($sql);
			$error['sucursal']=$q;

			// Si existe toma los ID
			if(mysql_num_rows($q)>0){
				while ($r=mysql_fetch_object($q)) {
					$sucursal_operando=$r->nombre;
					$sucursal_id=$r->idSuc;
				}
			}

			// Obtiene el almacen
			$sql="	SELECT
					idAlmacen 
				FROM 
					mrp_sucursal 
				WHERE 
					idSuc=".$sucursal_id." limit 1";
			$q=mysql_query($sql);
			$error['idAlmacen']=$q;

			// Toma los ID
			while($r=mysql_fetch_object($q)){
				$almacen=$r->idAlmacen;
			}

			// Obtiene la cantidad del producto por almacen
			$sql="	SELECT
					cantidad 
				FROM
					mrp_stock
				WHERE
					idProducto=".$idProducto."
					AND
					idAlmacen=".$almacen." limit 1";
			$e=mysql_query($sql);
			$error['cantidad']=$e;

			// Si ya tiene una cantidad compara si ya se terminaron las existencias, si se terminaron a agrega las nuevas
			if(mysql_num_rows($e)>0){
				while($re=mysql_fetch_object($e)){
					$vcantidad=$re->cantidad;
				}

				// Obtiene la cantidad inicial del producto
				$sql="	SELECT 
						stock_inicial
					FROM
						mrp_producto
					WHERE 
						idProducto=".$idProducto."";
				$stockinproductoquery=mysql_query($sql);
				$stockinproducto=mysql_fetch_object($stockinproductoquery);
				$error['stock_inicial']=$stockinproducto;

				// Compara que no qeden existencias
				if($vcantidad==$inicial || $vcantidad==0 && $stockinproducto->stock_inicial){
					//$update_stock=mysql_query("update mrp_stock set cantidad=".($inicial)." where idProducto=".$idProducto." and idAlmacen=".$almacen);
					//$error['$update_stock']=$update_stock;
				}

				// Si no tiene agrega la cantidad inicial en stock
			} else {
				$insert_stock=mysql_query("Insert into mrp_stock values('',".$idProducto.",".$inicial.",".$almacen.",".$unidad.",0)");
				$error['$insert_stock']=$insert_stock;
			}
			$fechaactual=date("Y-m-d H:i:s");

			// Optine la cantidad inicial con la que se creo el producto
			$sql="	SELECT
					stock_inicial
				FROM
					mrp_producto
				WHERE idProducto=".$idProducto."";
			$stx=mysql_query($sql);
			$stxvalor=mysql_fetch_object($stx);
			$error['$insert_stock']=$stxvalor;

			// Agrega los ingresos de mercancia por proveedor
			if(is_numeric($proveedor)){
				if($stxvalor->stock_inicial <=0){
					$sql="	INSERT INTO 
							ingreso_mercancia 
						VALUES('','".$fechaactual."','".$idProducto."','".$proveedor."',".$inicial.",'".$sucursal_id."','".$costo."');";
					$query0 = $this->db->query($sql);
					$error['ingreso_mercancia']=$query0;
				}
			} else {
				if($stxvalor->stock_inicial <=0) {
					$sql="	INSERT INTO
							ingreso_mercancia
						VALUES('','".$fechaactual."','".$idProducto."',NULL,".$inicial.",'".$sucursal_id."','".$costo."');";
					$query0 = $this->db->query($sql);
					$error['ingreso_mercancia si no']=$query0;
				}
			}

			// Agrega los productos al proveedor
			if(is_numeric($proveedor)){
				$sql='	SELECT
						id 
					FROM 
						mrp_producto_proveedor 
					WHERE 
						idPrv='.$proveedor.' 
						AND idProducto='.$idProducto;
				$query4 = $this->db->query($sql);
				$idproductoproveedor = $query4->result();
				$error['mrp_producto_proveedor']=$query4;

				// Elimina los produtos para limpiar la tabla
				if ($query4->num_rows() > 0) {
					$query3 = $this->db->query('DELETE FROM mrp_producto_proveedor WHERE id = '.$idproductoproveedor[0]->id);
					$error['DELETE FROM mrp_producto_proveedor']=$query3;
				}

				// Agrega los productos
				$sql='	INSERT INTO
						mrp_producto_proveedor (idProducto, idPrv, costo, idUni)
					VALUES
						('.$idProducto.','.$proveedor.',"'.$costo.'",'.$unidadCompra.')';
				$query2 = $this->db->query($sql);
				$error['INSERT INTO mrp_producto_proveedor --']['resul']=$query2;
				$error['INSERT INTO mrp_producto_proveedor --']['sql']=$sql;
			}

			// Materiales Extra, normales y opcionales
			// ** Agrega los materiales extra, opcionañles y normales al producto
			session_start();

			if ($_SESSION["materiales_agregados"]) {
				foreach ($_SESSION["materiales_agregados"] as $k => $material_seleccionado) {
					$sql = "	INSERT IGNORE INTO
							mrp_producto_material  (id,idProducto,cantidad,idUnidad,idMaterial,opcional)
						VALUES ('',
							" . $idProducto . ",
							" . $material_seleccionado -> cantidad . ",
							" . $material_seleccionado -> idunidad . ",
							" . $material_seleccionado -> idProducto . ",
							" . $material_seleccionado -> tipo . ")";
					// $sql="SELECT
							// *
						// FROM
							// mrp_producto_material
						// WHERE
							// idProducto=29;";
					$result=$this->db->query($sql);
					$error['materiales'][$k]['sql'] = $sql;
					$error['materiales'][$k]['result'] = $result;
				}
				unset($_SESSION["materiales_agregados"]);
			}

			$this -> db -> trans_complete();
			// return $error;
			return $idProducto;
		}
		///////////////// ******** ---- 		FIN registraProducto		------ ************ //////////////////

		function consultaImpuestos() {
			$this->load->database();
			$query = $this->db->query("SELECT id, nombre, valor FROM impuesto;");
			return $query->result();
		}

		function consultaProductoImpuesto($idProducto, $idImpuesto) {
			$this->load->database();
			$query = $this->db->query("SELECT valor FROM producto_impuesto WHERE idProducto = ".$idProducto." AND idImpuesto = ".$idImpuesto.";");

			if ($query->num_rows() > 0) {
				$impuestovalor = $query->result();
				return array('si' => 1, 'valImpues'=> $impuestovalor[0]->valor );
			} else {
				return array('si' => 0, 'valImpues'=> 0 );
				//return 0;
			}
		}

		function consultaetapa($id){
			$this->load->database();
			$query=$this->db->query('SELECT id, etapa, duracion_eta from mrp_etapas where id_producto='.$id);
			$etapas=array();
			if($query->num_rows() >0) {
				foreach ($query->result_array() as $key => $row) {
					//echo $row['duracion_eta']."kkkkkkkkkkk";
					$etapas[$row['etapa']]['dura']=$row['duracion_eta'];
					$query1=$this->db->query('SELECT * from mrp_procesos where id_etapa='.$row['id'].' ');

					if($query1->num_rows() >0){
						foreach ($query1->result_array() as $key1 => $row1) {
							$etapas[$row['etapa']][]=$row1;
						}
					}
				}
			}
			//var_dump($etapas);
			return $etapas;
		}

		function consultaprocesos() {
			$this->load->database();
			$query1=$this->db->query('SELECT proceso,descripcion,duracion,orden from mrp_procesos where id_etapa=');
			return $query1->result();
		}

		function eliminaProve2($idProducto,$idPrv) {
			$this->load->database();
			$query=$this->db->query('DELETE from mrp_producto_proveedor where idProducto='.$idProducto.' and idPrv='.$idPrv);
			return $query->result();
		}

		function eliminaPrecio2($id) {
			$this->load->database();
			$query=$this->db->query('DELETE from mrp_lista_precios where id='.$id);
			return $query->result();
		}

		function cambiaprecio($idprecio,$precio,$descripcion,$descuento) {
			$this->load->database();
			$query=$this->db->query('UPDATE mrp_lista_precios set precio="'.$precio.'", descripcion="'.$descripcion.'", orden="'.$descuento.'" where id='.$idprecio);
			return $query->result();
		}

		/*function updatePrecio($idPrecio,$precio,$descuento,$descripcion){
			$this->load->database();
			//$query = $this->db->query('UPDATE mrp_lista_precios set precio="'.$precio.'", orden="'.$descuento.'", descripcion="'.$descripcion.'" where id='.$idPrecio);
			//echo 'UPDATE mrp_lista_precios set precio='.$precio.', orden='.$descuento.', descripcion="'.$descripcion.'" where id='.$idPrecio;
			return $query->result();
		} */

		function unidadesCompra($ids) {
			try {
				$this->load->database();
				$unidadesBasicas = '1,24,176,26,25,179,181,177,180,178,182,183';

				if($ids == '000') {
					$identificadores = "Select identificadores from unid_generica ";
					$identificadores = $this->db->query($identificadores);
					$ids = '';

					foreach ($identificadores->result_array() as $key => $value) {
						if($value["identificadores"] != '') {
							$ids .= $value["identificadores"].",";
						}
					}

					if($ids != '') {
						$ids = substr($ids, 0, -1);
					} else {
						$ids = '\'\'';
					}
					$result = $this->db->query('Select idUni,compuesto,conversion,unidad,orden from mrp_unidades where idUni not in('.$ids.')');
				} else {
					$result = $this->db->query("Select idUni,compuesto,conversion,unidad,orden from mrp_unidades where idUni in(".$ids.")");
				}

				if($result->num_rows() > 0) {
					return array("status"=>true,"rows"=>$result->result_array());
				} else {
					throw new Exception("No se encontraron registros", 1);
				}
			} catch(Exception $e) {
				return array("status"=>false,"msg"=>$e->getMessage());
			}
		}

		////////////////////// ************* --		listar_productos	--***************** ////////////////////////////////
		// Consulta los productos existentes en mrp_producto
		function listar_materiales($objeto) {
			$condicion='';
			$this->load->database();
			$condicion.=' and  p.estatus=1';

			// Si existe el "id" lo Filtra por el id del producto
			$condicion.=(!empty($objeto['id']))?' and  p.idProducto='.$objeto['id']:'';

			// Si el "tipo de producto" es igual a 4 lo Filtra por:
			// "producto"(1), "producir producto"(2)
			// "Material de produccion"(3) y "Producto de consumo"(5)
			$condicion.=($objeto['tipo_producto']==4)?' AND ( p.tipo_producto=1 OR  p.tipo_producto=2 OR  p.tipo_producto=3 OR  p.tipo_producto=5)':'';

			// Si el "tipo de producto" es igual a 2 lo Filtra por:
			// "Material de produccion"(3) y "Producto de consumo"(5)
			$condicion.=($objeto['tipo_producto']==2)?' AND ( p.tipo_producto=3 OR  p.tipo_producto=5)':'';

			$sql='SELECT p.idProducto, p.nombre, p.idunidad, p.idunidadCompra, p.costo, u.compuesto as unidad
				FROM mrp_producto p, mrp_unidades u
				WHERE 1=1 
				AND p.idunidad=u.idUni'.$condicion;
			$query = $this->db->query($sql);

			// return $sql;
			return $query->result();
		}
		////////////////////// ************* --		FIN listar_productos	--***************** ////////////////////////////

		////////////////////// ************* --		buscar_unidad	--***************** ////////////////////////////////
		// Consulta el id y el nombre del compuesto del producto(gramos, kilos, unidade, metros, etc. )
		function buscar_unidad($objeto) {
			$condicion='';
			$this->load->database();

			// Si existe el "id" lo Filtra por el id del producto
			$condicion.=(!empty($objeto['id']))?' and producto.idProducto='.$objeto['id']:'';
			$this->load->database();

			$sql='SELECT unidades.idUni, unidades.compuesto
				FROM mrp_producto as producto, mrp_unidades as unidades 
				WHERE producto.idunidad=unidades.idUni'.$condicion;
			$query = $this->db->query($sql);

			// return $sql;
			return $query->result();
		}
		////////////////////// ************* --		FIN buscar_unidad	--***************** ////////////////////////////////

		public function listar_materiales_agregados($objeto) {
			$condicion='';
			$this->load->database();

			// Si existe el "id" lo Filtra por el id del producto
			$condicion.=(!empty($objeto['id']))?' and pm.idProducto='.$objeto['id']:'';
			$sql='SELECT pm.idProducto,pm.cantidad,pm.idUnidad,pm.idMaterial,pm.opcional,p.nombre,u.compuesto,p.costo,p.tipo_producto
				FROM mrp_producto_material pm, mrp_producto p, mrp_unidades u
				WHERE pm.idMaterial=p.idProducto 
				AND  u.idUni=pm.idUnidad'.$condicion;
			$query = $this->db->query($sql);

			// return $sql;
			return $query->result();
		}
	}
?>