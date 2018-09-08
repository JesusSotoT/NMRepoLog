<?php

class Orden_compra extends CI_Model {

//Funciones de busqueda sin algun filtro en especial. 
//Normalmente son los que se muestran cuando el usuario abre la pagina.
    function __construct()
    {
        parent::__construct();
    }
	
	function buscaDepartamento()
	{
		$this->load->database();

		$query = $this->db->query('SELECT idDep, nombre FROM mrp_departamento order by nombre asc');
		return $query->result();
	}
	
	function carbuscafamilia()
	{
		$this->load->database();

		$query = $this->db->query('select idFam,nombre from mrp_familia order by nombre asc');
		return $query->result();
	}
	function carbuscalinea()
	{
		$this->load->database();

		$query = $this->db->query('select idLin,nombre from mrp_linea order by nombre asc');
		return $query->result();
	}
	function buscaProveedor()
	{
		$this->load->database();

		$query = $this->db->query('SELECT idPrv, razon_social FROM mrp_proveedor order by razon_social');
		return $query->result();
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function desglosaUnidad($id)//4,9,13
	{
					
		$this->load->database();
		$query = $this->db->query('SELECT idUni,compuesto,orden,conversion FROM mrp_unidades WHERE idUni='.$id[0]->idUni);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
		
				 $hijos=$this->damehijos($id[0]->idUni,true);
				 $padres=$this->damepadres($id[0]->idUni,true);
				 $result = array_merge((array)$hijos, (array)$padres);
				 $result[]=$row->idUni."_".$row->compuesto."_".$row->orden."_".$row->conversion;

				 return ($result);
		}
	}
	///////////////////////////////
	public function damehijos($id,$raiz=false)
	{
		$this->load->database();
		if($raiz){$r=',(SELECT compuesto FROM mrp_unidades WHERE idUni='.$id.') as raiz';}else{$r='';}
		$query = $this->db->query('select idUni,compuesto,conversion,unidad,orden '.$r.' from mrp_unidades where idUni=(
		SELECT unidad FROM mrp_unidades WHERE idUni='.$id.')'); 
		
		$hijosindemiatos=array();
		foreach ($query->result() as $row)
		{
			if($row->idUni!=$id )
			{
				$hijosindemiatos[]=$row->idUni."_".$row->compuesto."_".$row->orden."_".$row->conversion;
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)$this->damehijos($row->idUni));
				
			}
		}//foreach
		$hijosindemiatos=array_unique($hijosindemiatos);
		
		return $hijosindemiatos;
		
	}
	/////////////////////////////////////////
	public function damepadres($id,$raiz=false)
	{
		$this->load->database();
		$query = $this->db->query('SELECT idUni,unidad,compuesto,orden,conversion FROM mrp_unidades WHERE unidad='.$id); 
		$hijosindemiatos=array();
		foreach ($query->result() as $row)
		{
			if($row->idUni!=$id)
			{
				$hijosindemiatos[]=$row->idUni."_".$row->compuesto."_".$row->orden."_".$row->conversion;
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)$this->damepadres($row->idUni));
				
			}
		}//foreach
		$hijosindemiatos=array_unique($hijosindemiatos);
		
		return $hijosindemiatos;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	function buscaUsuarios($term){

		$this->load->database();
		$query=$this->db->query("SELECT idempleado,usuario from accelog_usuarios where  usuario like '%".$term."%'");
		return  $query->result_array();
	}
	

	
	function buscaSucursal()
	{
		$this->load->database();

		$query = $this->db->query('SELECT idSuc, nombre FROM mrp_sucursal order by nombre');
		return $query->result();
	} 
	
	function buscaProductoSinFiltro()
	{
		
		// $q=mysql_query("select au.idSuc,mp.nombre,mp.idAlmacen from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=".$_SESSION['accelog_idempleado']);		
	// if(mysql_num_rows($q)>0)
	// {
		// //krmn
		// // $q2=mysql_query("
// // select am.idAlmacenSucursal, au.idSuc,am.idAlmacen from almacen_sucursal am,administracion_usuarios au
// // where am.idSucursal=au.idSuc and au.idempleado=".);
		// while($r=mysql_fetch_object($q))
		// {
			// $sucursal_operando=$r->nombre;
			// $sucursal_id=$r->idSuc;
			// $almacen_id=$r->idAlmacen;
		// }	
	// }	
					$q=mysql_query("select au.idSuc,mp.nombre,mp.idAlmacen from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=".$_SESSION['accelog_idempleado']);		
	if(mysql_num_rows($q)>0)
	{
		while($r=mysql_fetch_object($q))
		{
			$sucursal_operando=$r->nombre;
			$sucursal_id=$r->idSuc;
			$almacen_id=$r->idAlmacen;
		}	
	}
		$this->load->database();
		$query = $this->db->query('SELECT  mrp_producto.idProducto id,mrp_producto.nombre,
		CASE WHEN sum(mrp_stock.cantidad)  IS NOT NULL 
		THEN sum(mrp_stock.cantidad) 
		ELSE 0 END AS cantidad from  mrp_stock right join mrp_producto  on mrp_producto.idProducto=mrp_stock.idProducto
		WHERE vendible = 1 and mrp_producto.estatus=1  group by mrp_producto.nombre order by nombre');
		
		return $query->result();
	}
	
	function buscaComponenteSinFiltro()
	{
			$q=mysql_query("SELECT au.idSuc,mp.nombre,mp.idAlmacen from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=".$_SESSION['accelog_idempleado']);		
	if(mysql_num_rows($q)>0)
	{
		while($r=mysql_fetch_object($q))
		{
			$sucursal_operando=$r->nombre;
			$sucursal_id=$r->idSuc;
			$almacen_id=$r->idAlmacen;
		}	
	}	
		$this->load->database();
		$query = $this->db->query('SELECT  mrp_producto.idProducto id,mrp_producto.nombre,
		CASE WHEN sum(mrp_stock.cantidad)  IS NOT NULL 
       	THEN sum(mrp_stock.cantidad) 
      	ELSE 0 END AS cantidad from  mrp_stock right join mrp_producto  on mrp_producto.idProducto=mrp_stock.idProducto 
		WHERE (mrp_producto.tipo_producto=3 or mrp_producto.tipo_producto=5) and mrp_producto.estatus=1 and idAlmacen='.$almacen_id.' group by mrp_producto.nombre  order by nombre');
		
		return $query->result();
	}
	
	//Funcion que busca un departamento en base al proveedor seleccionado. El departamento es surtido con productos del proveedor
	function buscaDepartamentoFiltroProveedor($idPrv)
	{
		$this->load->database();

		$query = $this->db->query('	SELECT Distinct(d.idDep), d.nombre
									FROM mrp_departamento d
									INNER JOIN mrp_familia f ON d.idDep = f.idDep
									INNER JOIN mrp_linea l ON f.idFam = l.idFam
									INNER JOIN mrp_producto p ON p.idLinea = l.idLin
									INNER JOIN mrp_producto_proveedor r ON r.idProducto = p.idProducto
									INNER JOIN mrp_proveedor v ON v.idPrv = r.idPrv
									WHERE v.idPrv = '.$idPrv.';');
		return $query->result();
	}
	
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	//wdqdqwfewkgheroig;vergvre
	function buscaFamiliaFiltroProveedor($idDep, $idPrv)
	{
		$this->load->database();

		$query = $this->db->query('	SELECT Distinct(f.idFam), f.nombre
									FROM mrp_familia f
									INNER JOIN mrp_linea l ON f.idFam = l.idFam
									INNER JOIN mrp_producto p ON p.idLinea = l.idLin
									INNER JOIN mrp_producto_proveedor r ON r.idProducto = p.idProducto
									INNER JOIN mrp_proveedor v ON v.idPrv = r.idPrv
									WHERE f.idDep = '.$idDep.';');
									
		return $query->result();
	}
	
	function buscaLineaFiltroProveedor($idFam, $idPrv)
	{
		$this->load->database();

		$query = $this->db->query('	SELECT Distinct(l.idLin), l.nombre
									FROM mrp_linea l
									INNER JOIN mrp_producto p ON p.idLinea = l.idLin
									INNER JOIN mrp_producto_proveedor r ON r.idProducto = p.idProducto
									INNER JOIN mrp_proveedor v ON v.idPrv = r.idPrv
									WHERE l.idFam = '.$idFam.';');
									
		return $query->result();
	}	
	
	//Busca productos en base a un proveedor. Esto en la interface de edicion de ordenes de compra.
	//Solo se cargan los productos que ese proveedor surte
	function buscaProductoFiltroProveedor($idOrd, $idPrv)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto ID, p.nombre Nombre FROM mrp_producto p 
									INNER JOIN mrp_producto_proveedor d ON p.idProducto = d.idProducto
									WHERE d.idPrv = '.$idPrv.' AND
									p.idProducto NOT IN (SELECT poc.idProducto FROM mrp_producto_orden_compra poc WHERE poc.idOrden = '.$idOrd.');');
		return $query->result();
	}

	function buscaProductoFiltroDepartamentoProveedor($idOrd, $idDep, $idPrv)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto, p.nombre FROM mrp_producto p 
									INNER JOIN mrp_producto_proveedor v ON p.idProducto = v.idProducto
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									INNER JOIN mrp_familia f ON f.idFam = l.idFam
									INNER JOIN mrp_departamento d ON d.idDep = f.idDep
									WHERE v.idPrv = '.$idPrv.' AND d.idDep = '.$idDep.' AND
									p.idProducto NOT IN (SELECT poc.idProducto FROM mrp_producto_orden_compra poc WHERE poc.idOrden = '.$idOrd.');');
		return $query->result();
	}
	
	function buscaProductoFiltroFamiliaProveedor($idOrd, $idFam, $idPrv)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto , p.nombre
									FROM mrp_producto p 
									INNER JOIN mrp_producto_proveedor v ON p.idProducto = v.idProducto									
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									INNER JOIN mrp_familia f ON f.idFam = l.idFam
									WHERE v.idPrv = '.$idPrv.' AND l.idFam = '.$idFam.' AND
									p.idProducto NOT IN (SELECT poc.idProducto FROM mrp_producto_orden_compra poc WHERE poc.idOrden = '.$idOrd.');');
		return $query->result();
	}
	
	function buscaProductoFiltroLineaProveedor($idOrd, $idLin, $idPrv)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto, p.nombre FROM mrp_producto p 
									INNER JOIN mrp_producto_proveedor v ON p.idProducto = v.idProducto
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									WHERE v.idPrv = '.$idPrv.' AND l.idLin = '.$idLin.' AND
									p.idProducto NOT IN (SELECT poc.idProducto FROM mrp_producto_orden_compra poc WHERE poc.idOrden = '.$idOrd.');'
									);
		return $query->result();
	}















	 
	
	//Agrega un producto a la orden de compra y devuelve la tabla actualizada con el nuevo producto.
	function agregaProducto($cantidad, $unidad, $nombre)
	{
		$this->load->database();
		$this->db->query('INSERT INTO mrp_producto_orden_compra (cantidad, unidad, producto) values	("'.$cantidad.'", "'.$unidad.'", "'.$nombre.'")');
		
		//Devuelve la tabla ya actualizada con el producto
		$query = $this->db->query('SELECT idPrOr, cantidad, unidad, producto FROM mrp_producto_orden_compra');
		return $query->result();
	}
	
	//Busca las familias correspondientes a un departamento seleccionado (esto como filtro para encontrar productos a agregar a una orden de compra)
	function buscaFamilia($idDep)
	{
		$this->load->database();
		if(is_numeric($idDep))
		{
			$query = $this->db->query('SELECT idFam, nombre FROM mrp_familia WHERE idDep = "'.$idDep.'"');
		}
		else
		{
			$query = $this->db->query('SELECT idFam, nombre FROM mrp_familia');	
		}
		return $query->result();
	}

	//Busca las lineas correspondientes a una familia seleccionada (esto como filtro para encontrar productos a agregar a una orden de compra)
	function buscaLinea($idFam)
	{
		$this->load->database();
		if(is_numeric($idFam))
		{
			$query = $this->db->query('SELECT idLin, nombre FROM mrp_linea WHERE idFam = "'.$idFam.'"');
		}
		else
		{
			$query = $this->db->query('SELECT idLin, nombre FROM mrp_linea');
		}
		return $query->result();
	}

	//Busca un producto en base a un departamento dado como filtro al momento de agregar productos a una orden de compra
	
	function buscaProductoFiltroDepartamento($idDep)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto ID, p.nombre Nombre, s.cantidad, mdr.nDevoluciones
									FROM mrp_producto p
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									INNER JOIN mrp_familia f ON f.idFam = l.idFam
									INNER JOIN mrp_departamento d ON d.idDep = f.idDep
									INNER JOIN mrp_stock s ON s.idProducto = p.idProducto
									LEFT JOIN mrp_devoluciones_reporte mdr ON mdr.idProducto = s.idProducto
									WHERE d.idDep = '.$idDep.' AND p.vendible = 1;');
		return $query->result();
	}
	
	//Busca un compuesto en base a un departamento dado como filtro al momento de agregar compuestos a una orden de compra
	
	function buscaComponenteFiltroDepartamento($idDep)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto ID, p.nombre Nombre 
									FROM mrp_producto p
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									INNER JOIN mrp_familia f ON f.idFam = l.idFam
									INNER JOIN mrp_departamento d ON d.idDep = f.idDep
									WHERE d.idDep = '.$idDep.' AND p.consumo = 1;');
		return $query->result();
	}
	
	//Busca un producto en base a una familia dada como filtro al momento de agregar productos a una orden de compra
	function buscaProductoFiltroFamilia($idFam)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto ID, p.nombre Nombre, s.cantidad, mdr.nDevoluciones
									FROM mrp_producto p
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									INNER JOIN mrp_familia f ON f.idFam = l.idFam
									INNER JOIN mrp_stock s ON s.idProducto = p.idProducto
									LEFT JOIN mrp_devoluciones_reporte mdr ON mdr.idProducto = s.idProducto
									WHERE l.idFam = '.$idFam.' AND p.vendible = 1;');
		return $query->result();
	}
	
	//Busca un compuesto en base a una familia dada como filtro al momento de agregar compuestos a una orden de compra
	function buscaComponenteFiltroFamilia($idFam)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto ID, p.nombre Nombre
									FROM mrp_producto p
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									INNER JOIN mrp_familia f ON f.idFam = l.idFam
									WHERE l.idFam = '.$idFam.' AND p.consumo = 1;');
		return $query->result();
	}

	//Busca un producto en base a una linea dada como filtro al momento de agregar productos a una orden de compra
	function buscaProductoFiltroLinea($idLin)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto ID, p.nombre Nombre, s.cantidad, mdr.nDevoluciones
									FROM mrp_producto p
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									INNER JOIN mrp_stock s ON s.idProducto = p.idProducto
									LEFT JOIN mrp_devoluciones_reporte mdr ON mdr.idProducto = s.idProducto
									WHERE l.idLin = '.$idLin.' AND p.vendible = 1;');
		return $query->result();
	}
	
	//Busca un compuesto en base a una linea dada como filtro al momento de agregar compuestos a una orden de compra
	function buscaComponenteFiltroLinea($idLin)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idProducto ID, p.nombre Nombre
									FROM mrp_producto p
									INNER JOIN mrp_linea l ON p.idLinea = l.idLin
									WHERE l.idLin = '.$idLin.' AND p.consumo = 1;');
		return $query->result();
	}
	
	//Busca los proveedores que surten cierto producto.
	function buscaProductoProveedor($idPro)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.idPrv ID, p.razon_social Nombre
									FROM mrp_proveedor p
									INNER JOIN mrp_producto_proveedor l ON p.idPrv = l.idPrv
									INNER JOIN mrp_producto f ON f.idProducto = l.idProducto
									WHERE l.idProducto = '.$idPro.';');
		if ($query->num_rows()<1)
		{
			return false;
		}
		return $query->result();
	}
	
	
	function buscaUnidad($idPro, $idPrv)
	{
		$this->load->database();
		
		$resul = $this->db->query("SELECT idUni FROM mrp_producto_proveedor WHERE idPrv = ".$idPrv." AND idProducto = ".$idPro);
		$query = $this->desglosaUnidad($resul->result());
		
		if ($resul->num_rows() > 0) {

			$queryUnidad = 'SELECT idunidad,orden,conversion,compuesto';
			$queryUnidad .= ' FROM mrp_producto p, mrp_unidades u';
			$queryUnidad .= ' WHERE idProducto ='.$idPro.' and p.idunidad=u.idUni';


			$unidadCompra = $this->db->query($queryUnidad);
			$unidadCompra = $unidadCompra->result_array();


			return array($query,$unidadCompra[0]);
		}
		else {
			return false;
		}
	}
	
	function unidadCompra($id)
	{
		$this->load->database();

		$queryUnidad = 'SELECT idunidad,orden,conversion,compuesto';
		$queryUnidad .= ' FROM mrp_producto p, mrp_unidades u';
		$queryUnidad .= ' WHERE idProducto ='.$id.' and p.idunidad=u.idUni';


		$unidadCompra = $this->db->query($queryUnidad);
		$unidadCompra = $unidadCompra->result_array();

		return $unidadCompra[0];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//Con esta funcion se obtiene el ultimo costo de un producto por parte del proveedor.
	//Este se usa cuando se agrega un nuevo producto a una orden de compra
	function buscaUltimoCosto($idPrv, $idPro)
	{
		$this->load->database();
		$query = $this->db->query('	SELECT p.costo Precio
									FROM mrp_producto_proveedor p
									WHERE p.idProducto = '.$idPro.' AND p.idPrv = '.$idPrv.';');
		if ($query->num_rows()<1)
		{
			return false;
		}
		return $query->result();
	}
	
	function ProveedorSugerido($id)
	{
		$this->load->database();
		
		$query = $this->db->query(' SELECT pr.razon_social Nombre, oc.idProveedor ID, c.fecha, dc.costo Cos, poc.idUnidad Uni, u.compuesto Com	
									FROM mrp_detalle_compra dc, mrp_compra c, mrp_producto_orden_compra poc, 
										mrp_producto p, mrp_unidades u, mrp_orden_compra oc, mrp_proveedor pr
									WHERE dc.idCompra = c.id
									AND dc.idProductoOrdenCompra = poc.idPrOr
									AND p.idProducto = poc.idProducto
									AND u.idUni = poc.idUnidad
									AND oc.idOrd = poc.idOrden
									AND oc.idProveedor = pr.idPrv
									AND p.idProducto = '.$id.'
									ORDER BY c.fecha DESC, dc.costo ASC
									LIMIT 0,1;');
		
		return $query->row();
	}

	//Registra un nuevo producto en la interface de orden de compra.
	function registraProductoInterfaceEdicion($id, $cantidad, $unidad, $nombre, $costo)
	{
		$this->load->database();
		$this->db->trans_start();
		$this->db->query ("set foreign_key_checks=0;");
		
		$query = $this->db->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$id.', '.$cantidad.', '.$unidad.', '.$nombre.', '.$costo.')');
		
		$this->db->query ("set foreign_key_checks=1;");	
		$this->db->trans_complete();
		
		return true;	
	}
























	
	
	//Registra la orden de compra en la base de datos. 
	//Campos como la fecha de entrega y autorizado por se pueden cambiar despues
	function registraOrden($almacen,$sucursal_solicita, $fecha_pedido, $hora, $fecha_entrega, $elaborado_por)
	{	
		//Adicion de hora a la fecha
		$fecha_pedido = $fecha_pedido . " " . $hora;
		
		$cadena = "";
		//Inicializa la sesion para poder obtener los datos de los arreglos
		session_start();
		//Inicializa la transaccion y la base de datos
		$this->load->database();
		$this->db->trans_start();
		
		//Se asignan los arreglos de sesion a arreglos locales para no meterse con variables de sesion directamente
		$cantidad = $_SESSION["cantidad_array"];
		$unidad = $_SESSION["unidad_array"];
		$nombre = $_SESSION["nombre_array"];
		$proveedor = $_SESSION["proveedor_array"];
		$costo = $_SESSION["costo_array"];

		//Se ordenan los arreglos que contienen los productos de las ordenes de compra en relacion al arreglo de IDs de proveedor
		array_multisort($proveedor, SORT_NUMERIC, $cantidad, $unidad, $nombre, $costo);
				
		$id = 0;
		$query = $this->db->query ("set foreign_key_checks=0;");
		
		for ($i=0;$i<count($proveedor);$i++)
		{
			//Esta condicion solo se ejecutara una vez. Se agregara una orden de compra con el proveedor en la posicion 0 (sea cual sea el proveedor).
			//Esto debido a que las ordenes de compra se agrupan por proveedor segun las exigencias del MRP.
			if ($i == 0)
			{
				//Se ingresan los datos requeridos de la orden de compra
				$query = $this->db->query("INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor,idAlmacen) values ('".$fecha_pedido."', '".$elaborado_por."', '".$sucursal_solicita."', '".$fecha_entrega."', ".$proveedor[$i].",'".$almacen."')");        
				$cadena .= "INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor) values ('".$fecha_pedido."', '".$elaborado_por."', '".$sucursal_solicita."', '".$fecha_entrega."', ".$proveedor[$i].")";                    
				// Se obtiene el ID insertado para saber a que orden de compra pertenerceran los siguientes productos registrados en caso de pertenecer al mismo proveedor
				$id = mysql_insert_id();
				$sqlquery= $this->db->query('SELECT idunidadCompra from mrp_producto where idProducto='.$nombre[$i]);
				$unicompra = $sqlquery->row();
				// $unicompra->idunidadCompra unidad de compra
				//Se inserta el producto a la orden de compra con el ID especificado. Ahora este producto pertenece a una orden de compra con un unico proveedor
				$query = $this->db->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$id.', '.$cantidad[$i].', '.$unidad[$i].', '.$nombre[$i].', '.$costo[$i].')');
				$cadena .= 'INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$id.', '.$cantidad[$i].', '.$unidad[$i].', '.$nombre[$i].', '.$costo[$i].')';
			}
			//Del segundo barrido en adelante se comprobara si el ID del proveedor del anterior barrido es igual al actual.
			//Si es igual, se ingresara el producto en la misma orden, si es diferente quiere decir que es otro proveedor y por lo tanto, pertenece a otra orden de compra.
			else 
			{
				//Comprueba con el ID del proveedor anterior con base a los indices de los arreglos
				if ($proveedor[$i] != $proveedor[$i-1])
				{
					//Si es diferente, abrira una nueva orden de compra con los nuevos datos que pide el MRP
					$query = $this->db->query("INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor,idAlmacen) values ('".$fecha_pedido."', '".$elaborado_por."', '".$sucursal_solicita."', '".$fecha_entrega."', ".$proveedor[$i].",'".$almacen."')");                            
					//Se obtiene el ID del nuevo proveedor para indicar que es una nueva orden de compra
					$id = mysql_insert_id();
					$cadena .= "INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor) values ('".$fecha_pedido."', '".$elaborado_por."', '".$sucursal_solicita."', '".$fecha_entrega."', ".$proveedor[$i].")";
				}
				//Este query se ejecutara a partir del segundo barrido hasta el final. No improtando si es el mismo proveedor que el anterior barrido o no.
				//Si es una nueva orden de compra eso se indicara con la variable $id. Si es el anterior proveedor, se registrara en la misma orden con normalidad
				$query = $this->db->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$id.', '.$cantidad[$i].', '.$unidad[$i].', '.$nombre[$i].', '.$costo[$i].')');	
				$cadena .= 'INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$id.', '.$cantidad[$i].', '.$unidad[$i].', '.$nombre[$i].', '.$costo[$i].')';
			}
			
			
		}
		
		$query = $this->db->query ("set foreign_key_checks=1;");
		//Temina la transaccion y devuelve un true
		$this->db->trans_complete();
		return true;
	}
	
	//Funcion usada en la edicion de ordenes de compra
	function editaOrden($id_orden, $autorizado_por, $arreglo_costos, $arreglo_ids, $arreglo_cantidades, $fecha_entrega)
	{
		//Ordena ambos los arreglos de IDS de productos y costos, de manera que el de costos coincida con el de IDS
		array_multisort($arreglo_ids, SORT_NUMERIC, $arreglo_costos, $arreglo_cantidades);
				
		//Carga la base de datos e inicializa una transaccion
		$this->load->database();
		$this->db->trans_start();
				
		for($i=0; $i<count($arreglo_ids); $i++)
		{
			$arreglo_costos[$i] = str_replace(",", "", $arreglo_costos[$i]);	
			//Este query actualiza el registro de un producto dentro de la orden. Actualiza costo por si se acordo un nuevo costo con el proveedor
			$this->db->query('UPDATE mrp_producto_orden_compra SET cantidad = "'.$arreglo_cantidades[$i].'", ultCosto = "'.$arreglo_costos[$i].'" WHERE idPrOr = "'.$arreglo_ids[$i].'";');		
		}
		//Este query actualiza toda la orden de compra con una nueva fecha de entrega y un autorizador de la orden
		$this->db->query('UPDATE mrp_orden_compra SET fecha_entrega = "'.$fecha_entrega.'", autorizado_por = "'.$autorizado_por.'" WHERE idOrd = '.$id_orden.';');
		 
		//Finaliza la transaccion
		$this->db->trans_complete();
		return true;
	}
 
 /*public function agregaCxp($idOrden,$costo){

 		$this->load->database();
 		date_default_timezone_set('America/Mexico_City'); 
		$dia = date("Y-m-d");
 		$sql = $this->db->query('SELECT idProveedor from mrp_orden_compra where idOrd='.$idOrden);
 		$prove = $sql->row();

 		$this->db->query('INSERT into cxp (fechacargo,fechavencimiento,concepto,monto,saldoabonado,estatus,idProveedor) values ("'.$dia.'","2014-01-14","Orden de compra '.$idOrden.'","'.$costo.'","0","0","'.$prove->idProveedor.'")');
 		//echo 'INSERT into cxp (fechacargo,fechavencimiento,concepto,monto,idProveedor) values ("'.$dia.'","2015-03-01","Orden de compra '.$idOrden.'","'.$costo.'","'.$prove->idProveedor.'")';
 		//$prove= $
 		return true;
 } */
 
 
 
 
 
 
 
 
 
 
 
 
//Funciones pendientes de comentar por Alex. Prefiero no comentar cosas que ni al caso.

	public function grid($pagina="1",$filtro=1,$paginacion=10)
	{
		if($filtro!=1){ //list($valor,$campo)=explode("_",$filtro); $filtro=$campo." like '%".$valor."%'";
		 }	else{ $filtro='';}
		
		$this->load->database();
		if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}
		$query = $this->db->query('SELECT 
										oc.idOrd Id,
										p.razon_social Proveedor,
										oc.fecha_pedido Fecha_pedido,
										oc.fecha_entrega Fecha_de_entrega,
										oc.elaborado_por Elaboro,
										a.nombre Almacen,
										
										oc.autorizado_por Autorizacion,
										oc.estatus Estatus 
									FROM mrp_orden_compra oc, mrp_sucursal s, almacen a,mrp_proveedor p 
									WHERE s.idSuc=oc.idSuc and p.idPrv=oc.idProveedor  AND a.idAlmacen=oc.idAlmacen 
										 '.$filtro.' order by oc.fecha_pedido DESC  LIMIT '.$begin.','.$paginacion);
	
		$q=$this->db->query('SELECT idOrd from  mrp_orden_compra');
		$paginas=($q->num_rows()/$paginacion);if($q->num_rows()%$paginacion!=0){$paginas++;}
		
		$grid=array();
		$grid["data"]=$query->result_array();
		$grid["nombre"]='Ordenes de compra';
		$grid["paginas"]=$paginas;			
		return $grid;
	}

	public function get($id)
	{
		$this->load->database();
		$query = $this->db->query('Select 
		oc.idOrd Id,
		p.idPrv ID_Proveedor,
		p.razon_social Proveedor,
		p.rfc rfc,
		p.domicilio domicilio,
		p.email correo,
		oc.fecha_pedido Fecha_pedido,
		oc.fecha_entrega Fecha_de_entrega,
		oc.elaborado_por Elaboro,
		s.nombre Sucursal,
		a.nombre Almacen,
		oc.autorizado_por Autorizacion,
		oc.estatus Estatus,
		oc.enviada Enviada 
		from mrp_orden_compra oc, mrp_sucursal s ,mrp_proveedor p ,almacen a
		where a.idAlmacen=oc.idAlmacen and s.idSuc=oc.idSuc and p.idPrv=oc.idProveedor and oc.idOrd='.$id); 
		return $query->result();
	}
	public function get2($id)
	{
		$this->load->database();
		$query = $this->db->query('Select 
		oc.idOrd Id,
		p.idPrv ID_Proveedor,
		p.razon_social Proveedor,
		p.rfc rfc,
		p.domicilio domicilio,
		p.email correo,
		e.estado estado,
		m.municipio municipio,
		oc.fecha_pedido Fecha_pedido,
		oc.fecha_entrega Fecha_de_entrega,
		oc.elaborado_por Elaboro,
		s.nombre Sucursal,
		a.nombre Almacen,
		oc.autorizado_por Autorizacion,
		oc.estatus Estatus 
		from mrp_orden_compra oc, mrp_sucursal s ,mrp_proveedor p ,almacen a, estados e, municipios m
		where m.idmunicipio=p.idmunicipio and e.idestado=p.idestado and a.idAlmacen=oc.idAlmacen and s.idSuc=oc.idSuc and p.idPrv=oc.idProveedor and oc.idOrd='.$id); 
		return $query->result();
	}

	public function organizacion(){

		$this->load->database();
		$query = $this->db->query('SELECT * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio');
		return $query->result();
	}

	public function detalle($id)
	{
		$this->load->database();
		$query = $this->db->query('SELECT 
		poc.cantidad,
		u.compuesto,
		p.nombre,
		poc.ultCosto,
		poc.idPrOr ,
		poc.idProducto
		FROM mrp_producto_orden_compra poc,mrp_unidades u,mrp_producto p
       		WHERE poc.idUnidad=u.idUni and poc.idProducto=p.idProducto and poc.idOrden='.$id.' order by p.nombre'); 
		return $query->result();
	}
	public function productoImpuesto($idProducto){
		$this->load->database();
		     /*$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
             $queryImpuestos .= " from impuesto i, mrp_producto p ";
             $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
             $queryImpuestos .= " where p.idProducto=" . $idProducto . " and i.id=pi.idImpuesto ";
             $queryImpuestos .= " Order by pi.idImpuesto DESC "; */
        $query = $this->db->query('SELECT p.idProducto,p.precioventa, pi.valor, i.nombre from impuesto i, mrp_producto p left join producto_impuesto pi on p.idProducto=pi.idProducto where p.idProducto=' . $idProducto . ' and i.id=pi.idImpuesto Order by pi.idImpuesto DESC');     
        return $query->result();
	}

	public function compruebaProductoCompuesto($id)
	{
		$this->load->database();
		$query = $this->db->query("SELECT p.id, p.idProducto, p.cantidad, p.idUnidad, p.idMaterial, l.nombre Nom , u.compuesto
									FROM mrp_producto_material p
									INNER JOIN mrp_producto l ON p.idMaterial = l.idProducto
									Inner join mrp_unidades u on u.idUni=p.idUnidad WHERE p.idProducto=".$id.";");

		if ($query->num_rows()==0)
		{
			return false;
		}
		return $query->result();
	}
	 
	function eliminaOrden($idOrd)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$this->db->query('DELETE FROM mrp_producto_orden_compra where idOrden='.$idOrd.'');
		$query = $this->db->query('DELETE FROM mrp_orden_compra where idOrd='.$idOrd.'');
		
		$this->db->trans_complete();
		
		if (!$query)
		{
			return false;
		}	
		return true;
	}

	public function datosproducto($id)
	{
		$this->load->database(); 
		$query=$this->db->query('SELECT p.idProducto, p.nombre FROM mrp_producto p WHERE p.idProducto='.$id);
		
		return $query->row();
	}

	public function cambiaestatusEnviada($id){

		$this->load->database();
		$query=$this->db->query('UPDATE mrp_orden_compra set enviada=1 where idOrd='.$id);

		return true;
	}



























	function buscaUnidadX($idPro, $idPrv, $cantidad)
	{
		$this->load->database();
		
		$resul = $this->db->query("SELECT idUni FROM mrp_producto_proveedor WHERE idPrv = ".$idPrv." AND idProducto = ".$idPro);
		$query = $this->desglosaUnidadX($resul->result(), $cantidad);
		
		if ($resul->num_rows() > 0) {
			return $query;
		}
		else {
			return false;
		}
	}

	public function desglosaUnidadX($id, $cantidad)
	{
					
		$this->load->database();
		$query = $this->db->query('SELECT idUni,compuesto,conversion FROM mrp_unidades WHERE idUni='.$id[0]->idUni); 
		
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
		
				 $hijos=$this->damehijosX($id[0]->idUni,true, $cantidad);
				 $padres=$this->damepadresX($id[0]->idUni,true, $cantidad);
				 
				 $result = array_merge((array)$hijos, (array)$padres);
				 $result[]=$cantidad."_".$row->idUni."_".$row->compuesto;
				 return ($result);
		}
	}
	///////////////////////////////
	public function damehijosX($id,$raiz=false, $cantidad)
	{
		$this->load->database();
		if($raiz)
		{
			$r=',(SELECT compuesto FROM mrp_unidades WHERE idUni='.$id.') as raiz';
		}
		else
		{
			$r='';
		}
		$query = $this->db->query('SELECT idUni,compuesto,conversion,unidad '.$r.' from mrp_unidades 
										WHERE idUni = (SELECT unidad FROM mrp_unidades WHERE idUni='.$id.')'); 
		
		$hijosindemiatos=array();
		foreach ($query->result() as $row)
		{
			if($row->idUni!=$id)
			{
				$cantidadx = $cantidad*$row->conversion;
				$hijosindemiatos[]=$cantidadx."_".$row->idUni."_".$row->compuesto;
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)$this->damehijosX($row->idUni,false, $cantidadx));
				
			}
		}
		$hijosindemiatos=array_unique($hijosindemiatos);
		
		return $hijosindemiatos;
		
	}
	/////////////////////////////////////////
	public function damepadresX($id,$raiz=false, $cantidad)
	{
		$this->load->database();
		$query = $this->db->query('SELECT idUni,unidad,compuesto,conversion FROM mrp_unidades WHERE unidad='.$id); 
		$hijosindemiatos=array();
		foreach ($query->result() as $row)
		{
			if($row->idUni!=$id)
			{
				$cantidadx = $cantidad*$row->conversion;
				$hijosindemiatos[]=$cantidadx."_".$row->idUni."_".$row->compuesto;
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)$this->damepadresX($row->idUni,false, $cantidadx));
				
			}
		}//foreach
		$hijosindemiatos=array_unique($hijosindemiatos);
		
		return $hijosindemiatos;
	}

}



?>