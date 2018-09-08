<?php	

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buy_order extends CI_Controller {

	var $pdf;
	
	public function index()
	{
		$data['impresion'] = "Hola mundo";
		$this->load->view('buy_order/index', $data);
	}
	
		public function cargaalmacenes()
	{
		$this->load->model('Orden_produccion');	
		$select_suc='<select style="width: 80%;" id="almacen" class="nminputselect" >';
		$select_suc.='<option value="">-Seleccione un almacen-</option>';
		foreach($this->Orden_produccion->almacenes($_POST["id"]) as $almacen)
		{
			$select_suc.='<option value="'.$almacen->idAlmacen.'">'.utf8_decode($almacen->nombre).'</option>';
		}
		$select_suc.='</select>';	
		
		echo $select_suc;	
	}
	
	
	
	
		public function cargaalmacenes2()
	{
		$this->load->model('Orden_produccion');	
		//$select_suc='<select style="width: 80%;" id="almacen" onchange="VerExistencias(this.value);">';
		$select_suc='<select style="width: 80%;" id="almacen" class="nminputselect" >';
		
		$select_suc.='<option value="">-Seleccione un almacen-</option>';
		foreach($this->Orden_produccion->almacenes($_POST["id"]) as $almacen)
		{
			$select_suc.='<option value="'.$almacen->idAlmacen.'">'.($almacen->nombre).'</option>';
		}
		$select_suc.='</select>';	
		
		echo $select_suc;	
	}
///////////////////////////////////////////////////	 
	// function cargaproductos($id=0)
// {
	// if($id!=0){$filtro=" where idLinea=".$id." and vendible=1";}else{$filtro="where vendible=1";}	
// 		
	// $cbo='<select id="producto" name="producto" onchange="cargaProducto(this.value);">';
	  // $cbo.='<option value="">-Seleccione-</option>';
	// $query = mysql_query("select idProducto id,nombre  from mrp_producto ".$filtro);
    // while ($row = mysql_fetch_array($query))
    // {
		// $cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	// }
	// $cbo.='</select>';
	// return $cbo;	
// }
 // function cargafamilias2($iddep=0)
// {
	// $filtro="";
	// if($iddep!=0){$filtro=" where idDep=".$iddep;}
// 		
	// $cbo='<select id="familia" name="familia" onchange="cargaLineas2('.$iddep.',this.value); loadproductos('.$iddep.',this.value,0);">';
	// $cbo.='<option value="0">-Todos-</option>';
	// $query = mysql_query("select idFam id,nombre  from mrp_familia ".$filtro);
    // while ($row = mysql_fetch_array($query))
    // {
		// $cbo.='<option value="'.$row["id"].'">'.utf8_decode($row["nombre"]).'</option>';
	// }
	// $cbo.='</select>';
	// return $cbo;	
// }
 // function cargalineas2($iddep=0,$idfam=0)
// {
	// $filtro="";
	// if($idfam!=0){$filtro=" where idFam=".$idfam;}
	// else
		// if($iddep!=0){$filtro=" where idFam IN (SELECT idFam from mrp_familia where idDep=$iddep)";}
// 
// 		
	// $cbo='<select id="linea" name="linea" onchange="loadproductos('.$iddep.','.$idfam.',this.value);">';
	// $cbo.='<option value="0">-Todos-</option>';
	// $query = mysql_query("select idLin id,nombre  from mrp_linea ".$filtro);
    // while ($row = mysql_fetch_array($query))
    // {
		// $cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	// }
	// $cbo.='</select>';
	// return $cbo;	
// }
// function productosexistencias($iddepa=0,$idfamilia=0,$idlinea=0)
// {
	// $filtro="";
	// if($idlinea!=0){$filtro="and  mp.idLinea=".$idlinea;}
	// else
		// if($idfamilia!=0){$filtro="and mp.idLinea IN (select idLin from mrp_linea where idFam=".$idfamilia.")";}
		// else
			// if($iddepa!=0){$filtro="and  mp.idLinea IN (select idLin from mrp_linea where idFam IN (select idFam from mrp_familia where idDep=".$iddepa."))";}
// 
// 		
	// $cbo='<select id="producto" name="producto" >';
	  // $cbo.='<option value="">-Seleccione-</option>';
	// $query = mysql_query("select mp.idProducto id,mp.nombre from mrp_producto mp,mrp_stock ms where ms.idProducto=mp.idProducto and ms.idAlmacen=".$_SESSION['idalmacen']." ".$filtro." ORDER BY nombre asc");
    // while ($row = mysql_fetch_array($query))
    // {
		// $cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	// }
	// $cbo.='</select>';
	// return $cbo;	
// }
//   
	 
/////////////////////////////////////////////////////////	 
	public function buscaUsuarios(){
		//echo 'XXXXXXXXXXXXXXXXXXXXXX';
 		$term=$_POST['term'];
		$this->load->model('Orden_compra');
		$return = array();
		foreach($this->Orden_compra->buscaUsuarios($term) as $usu):
			array_push($return, array('id' => $usu["idempleado"],'label' => $usu["usuario"]));
		endforeach;

		echo json_encode($return);
	} 
	 
	 
	public function form()
	{
		if(!isset($_SESSION)) 
		{
     		session_start();
		}
		$this->load->model('Orden_compra');
		
		//Carga el catalogo de proveedores para colocarse en el combo Proveedor
		$select_proveedor = "<div><select id='prv'>";
		$select_proveedor .= "<option value=''>----------</option>";
		foreach($this->Orden_compra->buscaProveedor() as $prv):
			$select_proveedor .= '<option value="'.$prv->idPrv.'">'.($prv->razon_social).'</option>';
		endforeach;
		$select_proveedor .= "</select></div>";	
		
		$data['prv'] = $select_proveedor;
		//Cargando lista de proveedores para imprimirlos en proveedores adicionales sección proveedor
		//Asignación de proveedores al array data desde la función buscaProveedor
		$data['proveedores'] = $this->Orden_compra->buscaProveedor();
		//Carga el catalogo de unidades para colocarse en el combo Unidad
		$select_unidad = "<div><select id='uni'>";
		$select_unidad .= "<option value=''>Selecciona primero proveedor</option>";
		$select_unidad .= "</select></div>";	
		
		$data['uni'] = $select_unidad;	
		
		//Carga el catalogo de sucursales para colocarse en el combo
		$select_sucursal = "<div><select id='sucursal_solicita' style='width: 80%;' onchange='cargaalmacenes(this.value);' class='nminputselect'>";
		$select_sucursal .= "<option value='' selected>----------</option>";
		foreach($this->Orden_compra->buscaSucursal() as $suc):
			if (isset($_SESSION['sucursal_solicita_temporal']) == $suc->idSuc)
			{
				$select_sucursal .= '<option value="'.$suc->idSuc.'" >'.($suc->nombre).'</option>';
			}
			else
			{
				$select_sucursal .= '<option value="'.$suc->idSuc.'">'.($suc->nombre).'</option>';
			}
		endforeach;
		$select_sucursal .= "</select></div>";	
		
		$data['suc'] = $select_sucursal;
		//krmn
		//Carga el catalogo de departamentos para filtrar producto
		$select_departamento2 = "<div><select id='departamento' onchange='cargaFamilias2(this.value);cargaLineas2(this.value,0);loadproductos(this.value,0,0);' class='nminputselect'>";
		$select_departamento2 .= "<option value='0'>Todos</option>";
		foreach($this->Orden_compra->buscaDepartamento() as $dep_prod):
			$select_departamento2 .= '<option value="'.$dep_prod->idDep.'">'.($dep_prod->nombre).'</option>';
		endforeach;
		$select_departamento2 .= "</select></div>";	
		
		$data['dep_prod'] = $select_departamento2; 
		
		//Carga el catalogo de familias para filtrar productos
		$select_familia = "<div><select id='familia' onchange='cargaLineas2(0,this.value); loadproductos(0,this.value,0);' class='nminputselect'>";
		$select_familia .= "<option value='0'>Todos</option>";
		foreach($this->Orden_compra->carbuscafamilia() as $dep_fam):
			$select_familia .= '<option value="'.$dep_fam->idFam.'">'.($dep_fam->nombre).'</option>';
		endforeach;
		$select_familia .= "</select></div>";	
		
		$data['fam'] = $select_familia;	
		
		//Carga el catalogo de lineas para filtrar productos
		$select_linea = "<div><select id='linea' onchange='loadproductos(0,0,this.value);' class='nminputselect'>";
		$select_linea .= "<option value='0'>Todos</option>";
		foreach($this->Orden_compra->carbuscalinea() as $dep_lin):
			$select_linea .= '<option value="'.$dep_lin->idLin.'">'.($dep_lin->nombre).'</option>';
		endforeach;
		$select_linea .= "</select></div>";	
		
		$data['lin'] = $select_linea;	
		
		//Carga el catalogo de proveedores del producto
		$select_prod_prov = "<div><select id='proveedor_producto' style='width: 100%;' class='nminputselect'>";
		$select_prod_prov .= "<option value=''>Selecciona primero un producto</option>";
		$select_prod_prov .= "</select></div>";	
		
		$data['proveedor_producto'] = $select_prod_prov;			
		
		//krmn
		//Carga el catalogo de productos sin filtro
		$select_producto = "<div><select id='producto' style='width: 100%;' onchange='cargaexistencias(this.value);' class='nminputselect'>";
		$select_producto .= "<option value=''>Selecciona un Producto</option>";
		foreach($this->Orden_compra->buscaProductoSinFiltro() as $pro):
		$select_producto .= '<option value="'.$pro->id.'">'.($pro->nombre."->".$pro->cantidad).'</option>';
		endforeach;
		$select_producto .= "</select></div>";	
		
		$data['pro'] = $select_producto;
		
		//Carga el catalogo de componentes sin filtro
		$select_componente = "<div><select id='componente' style='width: 100%;' class='nminputselect'>";
		$select_componente .= "<option value=''>Selecciona o usa los filtros</option>";
		foreach($this->Orden_compra->buscaComponenteSinFiltro() as $com):
			$select_componente .= '<option value="'.$com->id.'">'.($com->nombre."->".$com->cantidad).'</option>';
		endforeach;
		$select_componente .= "</select></div>";	
		
		$data['com'] = $select_componente;
		
		$this->load->view('buy_order/form', $data);
	}

	function verComponente()
	{

		//session_start();
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$cantidad = $_POST['cantidad'];	
		
		return $this->verComponente2($id, $nombre, $cantidad);
 	}
	
	function verComponente2($id, $nombre, $cantidad)
	{	
		$this->load->helper('url');
		$base_url=str_replace("modulos/mrp/","",base_url());
		
		$this->load->model('Orden_compra');

		$data = $this->Orden_compra->unidadCompra($id);

		$grid_preview= "	
								<div class='acordeon' style='background-color: #CD0E0E;'>
								<h3 style='text-align: left;'>".$nombre."</h3>
						  		<div>
								<table cellpadding='0' cellspacing='0'>
										<tr>
										<th width=10% style='border-bottom: 1px solid #006efe;'>Cantidad</th>
										<th width=25% style='border-bottom: 1px solid #006efe;'>Producto</th>
										<th width=30% style='border-bottom: 1px solid #006efe;'>Proveedor</th>
										<th width=20% style='border-bottom: 1px solid #006efe;'>Ultimo precio</th>
										<th width=15% style='border-bottom: 1px solid #006efe;'>Unidad Compra</th>
										</tr>";
			
		$grid_preview .= "				<tr>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><label value='".$cantidad."' id='cantidad_compuesto'>".$cantidad."</label></td>
										<input type='hidden' id='producto_id' value=".$id.">
										<input type='hidden' id='nombre_producto' value='".$nombre."'>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px;'>".$nombre."</td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px; padding-right: 10px;'><center>".$this->proveedorFiltroProducto($id)."</center></td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><img class='preloader_preview_elemento' id='preloader_preview_componente_costo' src='".base_url()."/images/preloader.gif'><center>".$this->cargaUltimoCostoPreview($id)."</center></td>
									 	<td style='border-bottom: 1px solid #EEEEEE; text-align: right; '><img class='preloader_preview_elemento' id='preloader_preview_componente_unidad' src='".base_url()."/images/preloader.gif'><center>".$this->cargaUnidadesPreview($id)."</center></td>
										</tr>"; 
		 
		$grid_preview .= '	
								</table>
								<input id="pr_conversion" type="hidden" name="" value="'.$data["conversion"].'">
								<input id="pr_orden" type="hidden" name="" value="'.$data["orden"].'">
								<input id="pr_compuesto" type="hidden" name="" value="'.$data["compuesto"].'">
								</div>
								</div>
								
								<input type="button" id="agregar_componente" value="Agregar a la orden" onClick="agregar_componente()" class="nminputbutton" />
								<br>(Asegúrese de haber rellenado la información de proveedor y unidad para el producto).
							';
		echo $grid_preview;
	}
	 
	public function proveedorFiltroProducto($id)
	{
		$this->load->model('Orden_compra');
		
		
		if ($this->Orden_compra->buscaProductoProveedor($id) == false)
		{
			return "<div id='proveedor_producto'>No hay un proveedor para este producto</div>";	
		}
		else
		{
			$select =  "<div><select id='proveedor_producto' style='width: 100%;' onchange='filtraUltimoCostoYUnidadPreview(this.value, document.getElementById(\"producto_id\").value);' class='nminputselect'>";
			//$select .= '<option value="" selected>Selecciona proveedor</option>';
		
			$sugerido = $this->Orden_compra->ProveedorSugerido($id);
		//	if ($this->Orden_compra->ProveedorSugerido($id) == false)
		//	{
				//No habra un proveedor preseleccionado
				$select .= '<option value="" selected >Selecciona proveedor</option>';
		//	}
			foreach($this->Orden_compra->buscaProductoProveedor($id) as $prv):
			
				//Si es diferente de false si hay un proveedor sugerido, por lo que entrara a comprobar cual es y pondra uno
				//seleccionado por defecto. Si no hay algun proveedor aventara todos los proveedores sin alguno seleccionado
				if ($this->Orden_compra->ProveedorSugerido($id) != false)
				{
					if ($prv->ID == $sugerido->ID)
					{
						$select .= '<option value="'.$prv->ID.'">'.utf8_decode($prv->Nombre).'</option>';
					}
					else
					{
						$select .= '<option value="'.$prv->ID.'">'.utf8_decode($prv->Nombre).'</option>';
					}
				}
				else
				{
					$select .= '<option value="'.$prv->ID.'">'.utf8_decode($prv->Nombre).'</option>';
				}
			endforeach;
			
			$select .= "</select></div>";
			return $select;
		}
	}
	 
	 function cargaUltimoCostoPreview($id)
	 {
	 	$this->load->model('Orden_compra');
		//var_dump($this->Orden_compra->buscaCostoDeProveedorPorFechaDeCompra($id));
		
		if ($this->Orden_compra->buscaProductoProveedor($id) == false)
		{
			return "<div id='ultimo_costo'>Registre primero un proveedor para el producto</div>";	
		}
		else
		{
			if ($this->Orden_compra->ProveedorSugerido($id) == false)
			{
				return '<input maxlength="8" class="numeric nminputtext" type="text" id="ultimo_costo" style="width: 60%" value="">';
			}
			else
			{
				$row=$this->Orden_compra->ProveedorSugerido($id);
				return '<input maxlength="8" class="numeric nminputtext" type="text" id="ultimo_costo" style="width: 60%" value="'.$row->Cos.'">';
			}
		}
		
	 	//return $row[0]; 
	 } 
	 
	  function cargaUnidadesPreview($id)
	 {
	 	$this->load->model('Orden_compra');
		//var_dump($this->Orden_compra->buscaCostoDeProveedorPorFechaDeCompra($id));
		if ($this->Orden_compra->ProveedorSugerido($id) != false)
		{	
			$row = $this->Orden_compra->ProveedorSugerido($id);
			$select_unidad = "<div id='uni'><div><select id='unidad_producto' class='nminputselect'>";
			$select_unidad .= "<option value=''>----------</option>";
			foreach($this->Orden_compra->buscaUnidad($id, $row->ID) as $uni=>$idUni):
				//print_r($piezas);
				$piezas = explode("_", $idUni);
				$select_unidad .= '<option conversion="'.$piezas[3].'" orden="'.$piezas[2].'" value="'.$piezas[0].'">'.$piezas[1].'</option>';
		endforeach;
			$select_unidad .= "</select></div>";

			$select_unidad .= '<input id="hdnConversion" type="hidden" value="">';
		$select_unidad .= '<input id="hdnUnidad" type="hidden" value=""></div>';
		}
		else
		{
			if ($this->Orden_compra->buscaProductoProveedor($id) != false)
			{
				return "<div id='uni'>Selecciona primero proveedor</div>";
			}
			else 
			{
				return "<div id='uni'>Este producto no tiene unidades</div>";
			}
		}
		
		return $select_unidad;
	 } 

	function agregaProducto()
	{
		session_start();
		$cantidad = $_POST['cantidad'];
		$unidad = $_POST['unidad'];
		$nombre = $_POST['nombre'];
		$proveedor = $_POST['proveedor'];
		$costo = $_POST['costo'];
		$unidad_texto = $_POST['unidad_texto'];
		$nombre_texto = $_POST['nombre_texto'];
		$proveedor_texto = $_POST['proveedor_texto'];
		
		$ya_existente = false;		
		
		if (!isset($_SESSION["cantidad_array"])) {
		    $_SESSION["cantidad_array"] = array();
		}
		if (!isset($_SESSION["unidad_array"])) {
		    $_SESSION["unidad_array"] = array();
		}
		if (!isset($_SESSION["nombre_array"])) {
		    $_SESSION["nombre_array"] = array();
		}
		if (!isset($_SESSION["proveedor_array"])) {
		    $_SESSION["proveedor_array"] = array();
		}
		if (!isset($_SESSION["costo_array"])) {
		    $_SESSION["costo_array"] = array();
		}
		if (!isset($_SESSION["subtotal_array"])) {
		    $_SESSION["subtotal_array"] = array();
		}
		if (!isset($_SESSION["unidad_texto"])) {
		    $_SESSION["unidad_texto"] = array();
		}
		if (!isset($_SESSION["nombre_texto"])) {
		    $_SESSION["nombre_texto"] = array();
		}
		if (!isset($_SESSION["proveedor_texto"])) {
		    $_SESSION["proveedor_texto"] = array();
		}
		
		for($i=0; $i<count($_SESSION['nombre_array']); $i++)
		{
			if($_SESSION['nombre_array'][$i] == $nombre && $_SESSION['proveedor_array'][$i] == $proveedor)
			{
				$_SESSION['nombre_array'][$i] = $nombre;
				$_SESSION['proveedor_array'][$i] = $proveedor;
				$_SESSION["cantidad_array"][$i] = $cantidad;
				$_SESSION["unidad_array"][$i] = $unidad;
				$_SESSION["costo_array"][$i] = $costo;
				$_SESSION["subtotal_array"][$i] = $cantidad*$costo;
				$_SESSION["unidad_texto"][$i] = $unidad_texto;
				$_SESSION["nombre_texto"][$i] = $nombre_texto;
				$_SESSION["proveedor_texto"][$i] = $proveedor_texto;
				$ya_existente = true;
			}
		}
		
		if(!$ya_existente)
		{
			array_push($_SESSION["cantidad_array"], $cantidad);
			array_push($_SESSION["unidad_array"], $unidad);
			array_push($_SESSION["nombre_array"], $nombre);
			array_push($_SESSION["proveedor_array"], $proveedor);
			array_push($_SESSION["costo_array"], $costo);
			array_push($_SESSION["subtotal_array"], $cantidad*$costo);
			array_push($_SESSION["unidad_texto"], $unidad_texto);
			array_push($_SESSION["nombre_texto"], $nombre_texto);
			array_push($_SESSION["proveedor_texto"], $proveedor_texto);
		}
 
		$this->load->model('Orden_compra');
		//$data['query_productos'] = $this->Orden_compra->agregaProducto($cantidad, $unidad, $nombre);
		$grid_productos= "<form action='../../../../../webapp/modulos/mrp/index.php/buy_order/form' type='GET'>
							<h3 style='color: #006efe;'>Productos a ordenar</h3>
							<table cellpadding='0' cellspacing='0' width=85%>
							<tr>
								<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #006efe;'>Borrar</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Cantidad</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Unidad</th>
								<th class='nmcatalogbusquedatit' width=35% style='border-bottom: 1px solid #006efe;'>Producto</th>
								<th class='nmcatalogbusquedatit' width=20% style='border-bottom: 1px solid #006efe;'>Proveedor</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Costo unitario</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Subtotal</th>
							</tr>";
		
		
		for($i=0; $i<count($_SESSION["nombre_array"]); $i++)
		{
			
		
			$grid_productos .= "<tr>";
			$grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><center><input type='checkbox' name='chk".$i."' value='1'></center></td>";
			$grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["cantidad_array"][$i]."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["unidad_texto"][$i]."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".utf8_decode($_SESSION["nombre_texto"][$i])."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".utf8_decode($_SESSION["proveedor_texto"][$i])."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; text-align: right; '> $".$_SESSION["costo_array"][$i]."</td>";
			$grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; text-align: right; '>$ ".$_SESSION["subtotal_array"][$i]."</td>";
			$grid_productos .= "</tr>";
		}
			  
			$grid_productos .= "<tr><td colspan='2' style='text-align: left; border-top: 1px solid #777777;'><input type='submit' id='borrar' value='Borrar seleccionados' onClick='erase_loading();' class='nminputbutton'/></td>";
			$grid_productos .= "<td colspan='5' style='text-align: right; border-top: 1px solid #777777;'>Total:  <label style='color: #006efe;'>$ ".array_sum($_SESSION["subtotal_array"])."</label></td></tr>";
			
		$grid_productos .= "</table></form>";
		echo $grid_productos;
		
	}

	public function imprimeGrid()
	{
		session_start();
		
		$grid_productos= "<form action='../../../../../webapp/modulos/mrp/index.php/buy_order/form' type='GET'>";
		$grid_productos.= "<h3 style='color: #006efe;'>Productos a ordenar</h3>
							<table cellpadding='0' cellspacing='0' width=85%>
							<tr>
								<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #006efe;'>Borrar</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Cantidad</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Unidad</th>
								<th class='nmcatalogbusquedatit' width=35% style='border-bottom: 1px solid #006efe;'>Producto</th>
								<th class='nmcatalogbusquedatit' width=20% style='border-bottom: 1px solid #006efe;'>Proveedor</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Costo unitario</th>
								<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #006efe;'>Costo compuesto</th>
							</tr>";
		
		
		
		for($i=0; $i<count($_SESSION["nombre_array"]); $i++)
		{
			$grid_productos .= "<tr>";
			$grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><center><input type='checkbox' name='chk".$i."' value='1'></center></td>";
			$grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["cantidad_array"][$i]."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["unidad_texto"][$i]."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".utf8_decode($_SESSION["nombre_texto"][$i])."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".utf8_decode($_SESSION["proveedor_texto"][$i])."</td>";
            $grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; text-align: right;'>$ ".$_SESSION["costo_array"][$i]."</td>";
			$grid_productos .= "<td style='border-bottom: 1px solid #EEEEEE; text-align: right; '>$ ".$_SESSION["subtotal_array"][$i]."</td>";
			$grid_productos .= "</tr>";
		} 
			
			$grid_productos .= "<tr><td colspan='2' style='text-align: left; border-top: 1px solid #777777;'><input type='submit' id='borrar' value='Borrar seleccionados' onClick='erase_loading();' class='nminputbutton' /></td>";
			$grid_productos .= "<td colspan='5' style='text-align: right; border-top: 1px solid #777777;'>Total:  <label style='color: #006efe;'>$ ".array_sum($_SESSION["subtotal_array"])."</label></td></tr>";
			
		$grid_productos .= "</table></form>";
		echo $grid_productos;
	}
	
	public function familia()
	{
	
		$idDep = $_POST['id'];
		$this->load->model('Orden_compra');		
		$data['query_familia'] = $this->Orden_compra->buscaFamilia($idDep);
        
		echo "<div><select id='fam' onchange='buscaLinea(this.value);' class='nminputselect'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach($data['query_familia'] as $fam):
		echo '<option value="'.$fam->idFam.'">'.utf8_decode($fam->nombre).'</option>';
		endforeach;
		echo "</select></div>";
		
	}
	
	 public function familiaFiltroProveedor()
	{
	
		$idDep = $_POST['id'];
		$idPrv = $_POST['idPrv'];
		$idOrd = $_POST['idOrd'];
		
		$this->load->model('Orden_compra');		
		$data['query_familia'] = $this->Orden_compra->buscaFamiliaFiltroProveedor($idDep, $idPrv);
        
		echo "<div><select id='fam' onchange='buscaLineaFiltroProveedor(".$idOrd.", this.value, ".$idPrv.");' class='nminputselect'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach($data['query_familia'] as $fam):
		echo '<option value="'.$fam->idFam.'">'.utf8_decode($fam->nombre).'</option>';
		endforeach;
		echo "</select></div>";
		
	}
	
	public function lineaFiltroProveedor()
	{
	
		$idFam = $_POST['id'];
		$idPrv = $_POST['idPrv'];
		$idOrd = $_POST['idOrd'];
		
		$this->load->model('Orden_compra');		
		$data['query_linea'] = $this->Orden_compra->buscaLineaFiltroProveedor($idFam, $idPrv);
         
		echo "<div><select id='lin' onchange='buscaProductoFiltroLineaProveedor(".$idOrd.", this.value, ".$idPrv.");' class='nminputselect'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach($data['query_linea'] as $lin):
		echo '<option value="'.$lin->idLin.'">'.utf8_decode($lin->nombre).'</option>';
		endforeach;
		echo "</select></div>";
		
	}
	
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
	
	public function productoFiltroDepartamentoProveedor()
	{
	
		$idDep = $_POST['id'];
		$idPrv = $_POST['idPrv'];
		$idOrd = $_POST['idOrd'];
		
		$this->load->model('Orden_compra');		
		$data['query'] = $this->Orden_compra->buscaProductoFiltroDepartamentoProveedor($idOrd, $idDep, $idPrv);
        
		echo "<div><select id='producto' onchange='filtraUltimoCostoYUnidad(document.getElementById(\"id_proveedor\").value, this.value);' class='nminputselect'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach($data['query'] as $pro):
		echo '<option value="'.$pro->idProducto.'">'.utf8_decode($pro->nombre).'</option>';
		endforeach;
		echo "</select></div>";
			
	}

	public function productoFiltroFamiliaProveedor()
	{
	
		$idFam = $_POST['id'];
		$idPrv = $_POST['idPrv'];
		$idOrd = $_POST['idOrd'];
		
		$this->load->model('Orden_compra');		
		$data['query'] = $this->Orden_compra->buscaProductoFiltroFamiliaProveedor($idOrd, $idFam, $idPrv);
        
		echo "<div><select id='producto' onchange='filtraUltimoCostoYUnidad(document.getElementById(\"id_proveedor\").value, this.value);' class='nminputselect'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach($data['query'] as $pro):
		echo '<option value="'.$pro->idProducto.'">'.utf8_decode($pro->nombre).'</option>';
		endforeach;
		echo "</select></div>";
		
	}
	
	public function productoFiltroLineaProveedor()
	{
	
		$idLin = $_POST['id'];
		$idPrv = $_POST['idPrv'];
		$idOrd = $_POST['idOrd'];
		
		$this->load->model('Orden_compra');		
		$data['query'] = $this->Orden_compra->buscaProductoFiltroLineaProveedor($idOrd, $idLin, $idPrv);
        
		echo "<div><select id='producto' onchange='filtraUltimoCostoYUnidad(document.getElementById(\"id_proveedor\").value, this.value);' class='nminputselect'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach($data['query'] as $pro):
		echo '<option value="'.$pro->idProducto.'">'.utf8_decode($pro->nombre).'</option>';
		endforeach;
		echo "</select></div>";
		
	}
 
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
	
	public function linea()
	{
	
		$idFam = $_POST['id'];
		$this->load->model('Orden_compra');		
		$data['query_linea'] = $this->Orden_compra->buscaLinea($idFam);
        
		echo "<div><select id='lin' onchange='filtraLinea(this.value);' class='nminputselect'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach($data['query_linea'] as $lin):
		echo '<option value="'.$lin->idLin.'">'.utf8_decode($lin->nombre).'</option>';
		endforeach;
		echo "</select></div>";		
		
	}
	
	public function productoFiltroDepartamento()
	{
		$idDep = $_POST['id'];
		$this->load->model('Orden_compra');		
		
		echo "<div><select id='producto' style='width: 100%;' onchange='filtraProveedor(this.value);' class='nminputselect'>";
		echo '<option value="">Selecciona o filtra por Familia</option>';
		foreach($this->Orden_compra->buscaProductoFiltroDepartamento($idDep) as $pro):
			echo '<option value="'.$pro->ID.'">'.utf8_decode($pro->Nombre).'->'.number_format($pro->cantidad - $pro->nDevoluciones).'</option>';
		endforeach;
		echo "</select></div>";
	}
	
	public function componenteFiltroDepartamento()
	{
		$idDep = $_POST['id'];
		$this->load->model('Orden_compra');		
		
		echo "<div><select id='componente' style='width: 100%;' onchange='filtraProveedor(this.value);' class='nminputselect'>";
		echo '<option value="">Selecciona o filtra por Familia</option>';
		foreach($this->Orden_compra->buscaComponenteFiltroDepartamento($idDep) as $com):
			echo '<option value="'.$com->ID.'">'.utf8_decode($com->Nombre).'</option>';
		endforeach;
		echo "</select></div>";
	}
	
	public function productoFiltroFamilia()
	{
		$idFam = $_POST['id'];
		$this->load->model('Orden_compra');		
		
		echo "<div><select id='producto' style='width: 100%;' onchange='filtraProveedor(this.value);' class='nminputselect'>";
		echo '<option value="">Selecciona o filtra por Linea</option>';
		foreach($this->Orden_compra->buscaProductoFiltroFamilia($idFam) as $pro):
			echo '<option value="'.$pro->ID.'">'.utf8_decode($pro->Nombre).'->'.number_format($pro->cantidad - $pro->nDevoluciones).'</option>';
		endforeach;
		echo "</select></div>";
	}

	public function componenteFiltroFamilia()
	{
		$idFam = $_POST['id'];
		$this->load->model('Orden_compra');		
		
		echo "<div><select id='componente' style='width: 100%;' onchange='filtraProveedor(this.value);' class='nminputselect'>";
		echo '<option value="">Selecciona o filtra por Linea</option>';
		foreach($this->Orden_compra->buscaComponenteFiltroFamilia($idFam) as $com):
			echo '<option value="'.$com->ID.'">'.utf8_decode($com->Nombre).'</option>';
		endforeach;
		echo "</select></div>";
	}
	
	public function productoFiltroLinea()
	{
		$idLin = $_POST['id'];
		$this->load->model('Orden_compra');		
		
		echo "<div><select id='producto' style='width: 100%;' onchange='filtraProveedor(this.value);' class='nminputselect'>";
		echo '<option value="">Selecciona un producto</option>';
		foreach($this->Orden_compra->buscaProductoFiltroLinea($idLin) as $pro):
			echo '<option value="'.$pro->ID.'">'.utf8_decode($pro->Nombre).'->'.number_format($pro->cantidad - $pro->nDevoluciones).'</option>';
		endforeach;
		echo "</select></div>";
	}

	public function componenteFiltroLinea()
	{
		$idLin = $_POST['id'];
		$this->load->model('Orden_compra');		
		
		echo "<div><select id='componente' style='width: 100%;' onchange='filtraProveedor(this.value);' class='nminputselect'>";
		echo '<option value="">Selecciona un producto</option>';
		foreach($this->Orden_compra->buscaComponenteFiltroLinea($idLin) as $com):
			echo '<option value="'.$com->ID.'">'.utf8_decode($com->Nombre).'</option>';
		endforeach;
		echo "</select></div>";
	}
	
	public function filtraUltimoCosto()
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];
		$this->load->model('Orden_compra');		
		
		foreach($this->Orden_compra->buscaUltimoCosto($idPrv, $idPro) as $cos):
			echo $cos->Precio;
		endforeach;
	}
	
	//Carga el catalogo de unidades para colocarse en el combo Unidad
	public function filtraUnidad()
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];
		
		$this->load->model('Orden_compra');

		$data = $this->Orden_compra->buscaUnidad($idPro, $idPrv);
		
		$select_unidad = "<div><select id='unidad_producto' class='nminputselect'>";
		$select_unidad .= "<option value=''>----------</option>";
		foreach( $data[0] as $uni=>$idUni):
			$piezas = explode("_", $idUni);
			$select_unidad .= '<option conversion="'.$piezas[3].'" orden="'.$piezas[2].'" value="'.$piezas[0].'">'.$piezas[1].'</option>';
		endforeach;

		$select_unidad .= '<input id="hdnConversion" type="hidden" value="">';
		$select_unidad .= '<input id="hdnUnidad" type="hidden" value="">';
		
		echo json_encode(array($select_unidad,$data[1]));
		//echo $this->Orden_compra->buscaUnidad($idPro, $idPrv);
		//echo "ok";
	}
	
	public function registraOrden()
	{
		date_default_timezone_set('America/Mexico_City'); 
		$hora = date("H:i:s");
		
		$almacen = $_POST['almacen'];
		$sucursal_solicita = $_POST['sucursal_solicita'];
		$fecha_pedido = $_POST['fecha_pedido'];
		$fecha_entrega = $_POST['fecha_entrega'];
		$elaborado_por = $_POST['elaborado_por'];
		
		$this->load->model('Orden_compra');		
		$this->Orden_compra->registraOrden($almacen,$sucursal_solicita, $fecha_pedido, $hora, $fecha_entrega, $elaborado_por);
		if(!isset($_SESSION)) {
		     session_start();
		}
		
		unset( $_SESSION["cantidad_array"]);
		unset( $_SESSION["unidad_array"]);
		unset( $_SESSION["nombre_array"]);
		unset( $_SESSION["proveedor_array"]);
		unset( $_SESSION["costo_array"]);
		unset( $_SESSION["subtotal_array"]);
		unset( $_SESSION["unidad_texto"]);
		unset( $_SESSION["nombre_texto"]);
		unset( $_SESSION["proveedor_texto"]);
		echo $this->grid();
	}
	
	public function registraProductoInterfaceEdicion()
	{
		if(!isset($_SESSION)) 
		{
     		session_start();
		}
		
		$_SESSION['autorizado_por'] = $_POST['autorizador'];
		$_SESSION['fecha_entrega_edicion'] = $_POST['entrega'];
		
		$cantidad = $_POST['cantidad'];
		$unidad = $_POST['unidad'];
		$nombre = $_POST['nombre'];
		$costo = $_POST['costo'];
		$id = $_POST['id'];
		
		$this->load->model('Orden_compra');		
		if ($this->Orden_compra->registraProductoInterfaceEdicion($id, $cantidad, $unidad, $nombre, $costo))
		{
			//echo $cantidad . " " . $unidad . " " .  $nombre . " " . $costo . " " . $id;
			echo $this->orden($id);
		}
	}
	
	public function editaOrden()
	{
		$autorizado_por = $_POST['autorizado_por'];
		$arreglo_costos = $_POST['arreglo_costos'];
		$arreglo_ids = $_POST['arreglo_ids'];
		$fecha_entrega = $_POST['fecha_entrega'];
		$id_orden = $_POST['id_orden'];
		$arreglo_cantidades = $_POST['arreglo_cantidades'];

		$this->load->model('Orden_compra');
		
		if ($this->Orden_compra->editaOrden($id_orden, $autorizado_por, $arreglo_costos, $arreglo_ids, $arreglo_cantidades, $fecha_entrega))
		{
			echo $this->orden($id_orden);
		}
		else
		{
			echo "Hubo un error al editar la orden. Intentelo de nuevo";
		}
	}
	
	function recursiva($id)
	{
		$arreglo=array();
		$this->load->model('Orden_compra');
		foreach($this->Orden_compra->compruebaProductoCompuesto($id) as $comp):
			$arreglo[$comp->idMaterial."_".$comp->Nom] = $this->recursiva($comp->idMaterial);
		endforeach;	
		
		return $arreglo;
		
	}
	
/////////////////////////////////////////////

	public function orden($id)
	{
		$arreglo  = array();
		$i=0;$total=0; $contador_productos=0;
		
		$this->load->model('Orden_compra');
		$orden=$this->Orden_compra->get($id);
		$detalle_orden=$this->Orden_compra->detalle($id);
		$data["orden"]=$orden;
		$data["detalle_orden"]=$detalle_orden;

		//Carga el catalogo de unidades para colocarse en el combo Unidad
		$select_unidad = "<div><select id='unidad_producto' class='nminputselect'>";
		$select_unidad .= "<option value=''> Selecciona producto primero </option>";
		$select_unidad .= "</select></div>";	
		
		$data['uni'] = $select_unidad;
		
		//Carga el catalogo de departamentos para filtrar producto de la interface de edicion de ordenes
		$select_departamento2 = "<div><select id='dep_prod' onchange='buscaFamiliaFiltroProveedor(".$orden[0]->Id.", this.value, ".$orden[0]->ID_Proveedor.");' class='nminputselect'>";
		$select_departamento2 .= "<option value=''>----------</option>";
		foreach($this->Orden_compra->buscaDepartamentoFiltroProveedor($orden[0]->ID_Proveedor) as $dep_prod):
			$select_departamento2 .= '<option value="'.$dep_prod->idDep.'">'.utf8_decode($dep_prod->nombre).'</option>';
		endforeach;
		$select_departamento2 .= "</select></div>";	
		
		$data['dep_prod'] = $select_departamento2;
		
		//Carga el catalogo de familias para filtrar productos de la interface de edicion de ordenes
		$select_familia = "<div><select id='fam' onchange='buscaLinea(this.value);' class='nminputselect'>";
		$select_familia .= "<option value=''>----------</option>";
		$select_familia .= "</select></div>";	
		
		$data['fam'] = $select_familia;	
		
		//Carga el catalogo de lineas para filtrar productos de la interface de edicion de ordenes
		$select_linea = "<div><select id='lin' class='nminputselect'>";
		$select_linea .= "<option value=''>----------</option>";
		$select_linea .= "</select></div>";	
		
		$data['lin'] = $select_linea;	
		
		//Carga el catalogo de lineas para filtrar productos
		$select_prod_prov = "<div><select id='proveedor_producto' style='width: 100%;' class='nminputselect'>";
		$select_prod_prov .= "<option value=''>Selecciona primero un producto</option>";
		$select_prod_prov .= "</select></div>";	
		
		$data['proveedor_producto'] = $select_prod_prov;			
		
		
		//Carga el catalogo de productos filtrados por proveedor
		$select_producto = "<div><select id='producto' style='width: 100%;' onchange='filtraUltimoCostoYUnidad(document.getElementById(\"id_proveedor\").value, this.value);' class='nminputselect'>";
		$select_producto .= "<option value=''>Selecciona producto o usa los filtros de Departamento</option>";
		foreach($this->Orden_compra->buscaProductoFiltroProveedor($orden[0]->Id,  $orden[0]->ID_Proveedor) as $pro):
			$select_producto .= '<option value="'.$pro->ID.'">'.utf8_decode($pro->Nombre).'</option>';
		endforeach;
		$select_producto .= "</select></div>";	
		
		$data['pro'] = $select_producto;
		
		
		$this->load->view('buy_order/orden',$data);
	}
	
	public function eliminaOrden($id)
	{
		$this->load->model('Orden_compra');
		$resul = $this->Orden_compra->eliminaOrden($id);
		
		if ($resul === TRUE)
		{
      		echo "1";
			echo "!!!AAABBBCCC!!!";
      		echo $this->grid(2,1,1,true);
      	}
		else
		{
			echo "-1";
			echo "!!!AAABBBCCC!!!";
			echo $this->grid(2,1,1,true);
		}
	}


	/////////////////////////////////////////////	
	public function grid($modo="1",$filtro,$pagina=1,$elimina=false)
	{
		$filtro=$_REQUEST['filtro'];
		if($filtro==''){
			$filtro=1;}
		//echo $filtro."rrrr";
		$this->load->model('Orden_compra');
		$grid=$this->Orden_compra->grid($pagina,$filtro);
		$encabezado='';$busquedas='';  
		
		$filas='';
		$i=0;

		if($filtro != 1 && !isset($grid["data"][0]))
		{
			exit('false');
		}

		foreach($grid["data"] as $fila)
		{
			$encabezado='';$busquedas='';
			
			if($i%2==0)
				$filas.='<tr class="busqueda_fila">';
			else
				$filas.='<tr class="busqueda_fila2">';
			$e=0;
			foreach($fila as $campo=>$valor)
			{
				if($e==0)
					$id=$valor;
				if($e==2)
				{
					$cadena_hora_fecha = $valor;
					list($fecha, $hora) = explode(' ', $cadena_hora_fecha);
					list($ano, $mes, $dia) = explode('-', $fecha);
					list($hour, $minuto, $segundo) = explode(':', $hora);
					
					if($hour < 12)
						$indicador = "am";
					else
					{
						$indicador = "pm";
						$hour = $hour-12;
					}
						
					$valor = $dia . "/" . $mes . "/" . $ano . " " . $hour . ":" . $minuto . " " . $indicador; 
				}
				if($e==3)
				{
					$fecha = $valor;
					list($ano, $mes, $dia) = explode('-', $fecha);
					
					if ($ano == 0000 || $mes == 00 || $dia == 00)
						$valor = "Falta fecha de entrega";
					else 
						$valor = $dia . "/" . $mes . "/" . $ano;
				}
				
				$encabezado.='<td class="nmcatalogbusquedacont_1" align="center">'.str_replace("_"," ",$campo).'</td>';
				$busquedas.='<td><input class="input_filtro  nmcatalogbusquedainputtext" onkeyup="busqueda(event,this.value,\''.$campo.'\');"></td>';
				if($modo==1)	
					$filas.='<td class="nmcatalogbusquedacont_1"><a class="a_registro" href="../../../../../webapp/modulos/mrp/index.php/buy_order/orden/'.$id.'">'.utf8_decode($valor).'</a></td>';
				else if($modo==2)		
					$filas.='<td class="nmcatalogbusquedacont_1"><a class="a_registro" onclick="EliminaOrden('.$id.',\'mrp_orden_compra\');">'.utf8_decode($valor).'</a></td>';
				$e++;
			}
			$filas.='</tr>';
			$i++;
		}
		
		$encabezado=' <td class="nmcatalogbusquedatit" align="center" width="5%">Id</00td>';
		$encabezado.='<td class="nmcatalogbusquedatit" align="center" width="20%">Proveedor</td>';
		$encabezado.='<td class="nmcatalogbusquedatit" align="center" width="10%">Fecha pedido</td>';
		$encabezado.='<td class="nmcatalogbusquedatit" align="center" width="10%">Fecha de entrega</td>';
		$encabezado.='<td class="nmcatalogbusquedatit" align="center" width="20%">Elaborado por</td>';
		$encabezado.='<td class="nmcatalogbusquedatit" align="center" width="10%">Almacen</td>';
		$encabezado.='<td class="nmcatalogbusquedatit" align="center" width="15%">Autorizado por</td>';
		$encabezado.='<td class="nmcatalogbusquedatit" align="center" width="10%">Estatus</td>';
		
		if($i<1)
		{
			$i=10;
			if($i%2==0)
				$filas.='<tr class="busqueda_fila">';
			else
				$filas.='<tr class="busqueda_fila2">';

			$e=8;
			$i++;
			for($k=0;$k<=10;$k++)
			{
				if($i%2==0)
					$filas.='<tr class="busqueda_fila">';
				else
					$filas.='<tr class="busqueda_fila2">';
				for($j=0;$j<=$e-1;$j++)
					$filas.='<td></td>';
				$filas.='</tr>';
				$i++;
			}
		}		
		
		if ($pagina==1)
			$pag_anterior=1;
		else
			$pag_anterior=$pagina-1;
		
		if (($pagina+1)>$grid["paginas"])
			$pag_siguiente=$pagina;
		else
			$pag_siguiente=$pagina+1;
	  
		if ($modo == 1)
		{
			$link_anterior='../../../../modulos/mrp/index.php/buy_order/grid/'.$modo.'/'.$filtro.'/'.$pag_anterior;
			$link_siguiente='../../../../modulos/mrp/index.php/buy_order/grid/'.$modo.'/'.$filtro.'/'.$pag_siguiente;
		}
		else if ($modo == 2)
		{
			$link_anterior='../../../../../modulos/mrp/index.php/buy_order/grid/'.$modo.'/'.$filtro.'/'.$pag_anterior;
			$link_siguiente='../../../../../modulos/mrp/index.php/buy_order/grid/'.$modo.'/'.$filtro.'/'.$pag_siguiente;
		}
	
		$catalogo='<div class="tipo">
		<table><tbody><tr>
		<td><input type="button" value="<" onclick="paginacionGrid(\''.$link_anterior.'\');"></td>
		<td><input type="button" value=">" onclick="paginacionGrid(\''.$link_siguiente.'\');" ></td>
		<td><a href="javascript:window.print();">
		<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
		<td><b>'.$grid['nombre'].'</b></td></tr></tbody></table></div><br>';
						
		$catalogo.='<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
		<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
		<tr class="titulo_filtros" title="Segmento de búsqueda">'.$busquedas.'</tr>
		'.$filas.'</table>
		';	
				
		$data=array('grid'=>$catalogo,'pagina_anterior'=>$link_anterior,'pagina_siguiente'=>$link_siguiente);
		if(!isset($_POST["ajax"]) && !$elimina)
		{		  
			$this->load->view('grid',$data); 	
		}
		else
		{
			echo $catalogo;
		}  
	}

	public function verProducto()
	{
		session_start();
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$cantidad = $_POST['cantidad'];		
		$_SESSION['contador_productos'] = 0;
		
		//session_start();
		$_SESSION["materiales"]=array();
		$this->load->model('Orden_compra');
		
		if ($this->Orden_compra->compruebaProductoCompuesto($id) == false)
		{
			echo $this->verComponente2($id, $nombre, $cantidad);
		}
		else
		{
			
			$cadena_compuestos_anidados='
							
					  		<div>
							<table cellpadding="0" cellspacing="0">
							
							';
			$cadena_compuestos_anidados .= $this->reccomponentes($id, $cantidad, $nombre);
			$cadena_compuestos_anidados .='
							</table>
							</div>';
							
			$cadena_compuestos_anidados .= "<input type='hidden' id='contador_componentes' value=".$_SESSION['contador_productos'].">";
			$cadena_compuestos_anidados .= '<input type="button" id="agregar_componente" value="Agregar a la orden" onClick="agregar_producto()" />
			<br>(Asegúrese de haber rellenado la información de proveedor y unidad para cada producto).';
			
			unset($_SESSION['contador_productos']);
			echo $cadena_compuestos_anidados;
		}
	}
	///////////////////////////////////////////////////////////////////
	function reccomponentes($id, $cantidad, $nombre)
	{
		$this->load->helper('url');
		$base_url=str_replace("modulos/mrp/","",base_url());
		
		$cadena_compuestos_anidados = '';
		$producto=$this->Orden_compra->datosproducto($id);

		$re=$this->componentes($id);		
		if(count($re)>0) //Tiene mas de algun componente
		{
			$cadena_compuestos_anidados.='<td colspan="9">';
			$cadena_compuestos_anidados.='<div class="acordeon ">'; 	
  
  			$cadena_compuestos_anidados.="<h3>
  										".$cantidad." ".$nombre."
										</h3>";
							
   			$cadena_compuestos_anidados.='
   							
	  						<div>
				 			<table border="0" width="100%" align="center">';
				
			$cadena_compuestos_anidados.='	
							<tr>
								<th width=10% style="border-bottom: 1px solid #006efe;">Cantidad</th>
								<th width=25% style="border-bottom: 1px solid #006efe;">Producto</th>
								<th width=30% style="border-bottom: 1px solid #006efe;">Proveedor</th>
								<th width=20% style="border-bottom: 1px solid #006efe;">Ultimo precio</th>
								<th width=15% style="border-bottom: 1px solid #006efe;">Unidad</th>
					  		</tr>';
					
			foreach($re as $key=>$r)
			{
				list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
				$productop=$this->Orden_compra->datosproducto($idp);
				$cadena_compuestos_anidados .= $this->reccomponentes($idp,($cantidad*$cantidadp),$nombrep);
			}	
			$cadena_compuestos_anidados.='
							</table>
						  	</div>
						  	</div>';
		}
		else //No tiene mas componentes
		{
			$_SESSION['contador_productos'] ++;  		
			$cadena_compuestos_anidados.="<tr>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><label value='".$cantidad."' id='cantidad_compuesto_".$_SESSION['contador_productos']."'>".$cantidad."</label></td>
										<input type='hidden' id='producto_id_".$_SESSION['contador_productos']."' value=".$id.">
										<input type='hidden' id='nombre_producto_".$_SESSION['contador_productos']."' value='".$nombre."'>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px;'>".$nombre."</td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px; padding-right: 10px;'><center>".$this->proveedorFiltroProductoCompuesto($id)."</center></td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><img class='preloader_preview_elemento' id='preloader_preview_producto_costo_".$_SESSION['contador_productos']."' src='".base_url()."/images/preloader.gif'><center>".$this->cargaUltimoCostoPreviewComponente($id)."</center></td>
										<td style='border-bottom: 1px solid #EEEEEE; text-align: right; '><img class='preloader_preview_elemento' id='preloader_preview_producto_unidad_".$_SESSION['contador_productos']."' src='".base_url()."/images/preloader.gif'><center>".$this->cargaUnidadesPreviewComponente($id)."</center></td>
									 	</tr>";
		}
		return $cadena_compuestos_anidados;
	}		 
	///////////////////////////////////////////////////////////////////
	function componentes($id)
	{
		$arreglo=array();
		$this->load->model('Orden_compra');

		if ($this->Orden_compra->compruebaProductoCompuesto($id) != false)
		{
			foreach($this->Orden_compra->compruebaProductoCompuesto($id) as $comp):
				$arreglo[$comp->idMaterial."_".$comp->Nom."_".$comp->compuesto."_".$comp->cantidad] = $this->componentes($comp->idMaterial);
			endforeach;	
		}
			return $arreglo;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	public function proveedorFiltroProductoCompuesto($id)
	{
		$this->load->model('Orden_compra');
		
		
		if ($this->Orden_compra->buscaProductoProveedor($id) == false)
		{
			return "<div id='proveedor_producto_".$_SESSION['contador_productos']."'>No hay un proveedor para este producto</div>";	
		}
		else
		{
			$select =  "<div><select id='proveedor_producto_".$_SESSION['contador_productos']."' style='width: 100%;' onchange='filtraUltimoCostoYUnidadPreviewCompuesto(this.value, document.getElementById(\"producto_id_".$_SESSION['contador_productos']."\").value, ".$_SESSION['contador_productos'].");' class='nminputselect'>";
			$sugerido = $this->Orden_compra->ProveedorSugerido($id);
		 	if ($this->Orden_compra->ProveedorSugerido($id) == false)
			{
				//No habra un proveedor preseleccionado
				$select .= '<option value="" selected>Selecciona proveedor</option>';
			}
			foreach($this->Orden_compra->buscaProductoProveedor($id) as $prv):
			
				//Si es diferente de false si hay un proveedor sugerido, por lo que entrara a comprobar cual es y pondra uno
				//seleccionado por defecto. Si no hay algun proveedor aventara todos los proveedores sin alguno seleccionado
				if ($this->Orden_compra->ProveedorSugerido($id) != false)
				{
					if ($prv->ID == $sugerido->ID)
					{
						$select .= '<option value="'.$prv->ID.'" selected>'.utf8_decode($prv->Nombre).'</option>';
					}
					else
					{
						$select .= '<option value="'.$prv->ID.'">'.utf8_decode($prv->Nombre).'</option>';
					}
				}
				else
				{
					$select .= '<option value="'.$prv->ID.'">'.utf8_decode($prv->Nombre).'</option>';
				}
			endforeach;
			
			$select .= "</select></div>";
			return $select;
		}
	}
	
	 function cargaUltimoCostoPreviewComponente($id)
	 {
	 	$this->load->model('Orden_compra');
		//var_dump($this->Orden_compra->buscaCostoDeProveedorPorFechaDeCompra($id));
		
		if ($this->Orden_compra->buscaProductoProveedor($id) == false)
		{
			return "<div id='ultimo_costo_".$_SESSION['contador_productos']."'>Registre primero un proveedor para el producto</div>";	
		}
		else
		{
			if ($this->Orden_compra->ProveedorSugerido($id) == false)
			{
				return '<input class="numeric nminputtext" type="text" id="ultimo_costo_'.$_SESSION['contador_productos'].'" style="width: 60%" value="">';
			}
			else
			{
				$row=$this->Orden_compra->ProveedorSugerido($id);
				return '<input class="numeric nminputtext" type="text" id="ultimo_costo_'.$_SESSION['contador_productos'].'" style="width: 60%" value="'.$row->Cos.'">';
			}
		}
		
	 	//return $row[0]; 
	 }  
	 
	  function cargaUnidadesPreviewComponente($id)
	 {
	 	$this->load->model('Orden_compra');
		//var_dump($this->Orden_compra->buscaCostoDeProveedorPorFechaDeCompra($id));
		if ($this->Orden_compra->ProveedorSugerido($id) != false)
		{
			$row = $this->Orden_compra->ProveedorSugerido($id);
			$select_unidad = "<div id='uni_".$_SESSION['contador_productos']."'>
									<div>
										<select id='unidad_producto_".$_SESSION['contador_productos']."' class='nminputselect'>";
			$select_unidad .= "				<option value=''>----------</option>";
			foreach($this->Orden_compra->buscaUnidad($id, $row->ID) as $uni=>$idUni):
				$piezas = explode("_", $idUni);
				$select_unidad .= '			<option value="'.$piezas[0].'">'.$piezas[1].'</option>';
			endforeach;
			$select_unidad .= "			</select>
									</div>
								</div>";
		}
		else
		{
			if ($this->Orden_compra->buscaProductoProveedor($id) != false)
			{
				return "<div id='uni_".$_SESSION['contador_productos']."'>Selecciona primero proveedor</div>";
			}
			else 
			{
				return "<div id='uni_".$_SESSION['contador_productos']."'>Este producto no tiene unidades</div>";
			}
		}
		// echo $select_unidad."???";
		return $select_unidad;
	 } 
	
	
	
	public function filtraUltimoCostoProductoCompuesto()
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];
		
		$this->load->model('Orden_compra');		
		
		foreach($this->Orden_compra->buscaUltimoCosto($idPrv, $idPro) as $cos):
			echo $cos->Precio;
		endforeach;
	}
	
	//Carga el catalogo de unidades para colocarse en el combo Unidad
	public function filtraUnidadProductoCompuesto()
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];
		$contador = $_POST['contador'];
		
		$this->load->model('Orden_compra');
		
		$select_unidad = "<div>
								<select id='unidad_producto_".$contador."' class='nminputselect'>";
		$select_unidad .= "			<option value=''>----------</option>";
		$data = $this->Orden_compra->buscaUnidad($idPro, $idPrv);
		foreach($data[0] as $uni=>$idUni):
			$piezas = explode("_", $idUni);
			$select_unidad .= '		<option value="'.$piezas[0].'">'.$piezas[1].'</option>';
		endforeach;
		$select_unidad .= "		</select>
						 </div>";
		
		echo $select_unidad;
		//echo $this->Orden_compra->buscaUnidad($idPro, $idPrv);
		//echo "ok";
	}
	
	public function guardaTemporal()
	{
		$suc = $_POST['suc'];
		$fec = $_POST['fec'];
		$ent = $_POST['ent'];
		$aut = $_POST['aut'];
		
			if(!isset($_SESSION)) 
    		{

    			session_start();
			}
			
			$_SESSION['sucursal_solicita_temporal'] = $suc;
			$_SESSION['fecha_pedido_temporal'] = $fec;
			$_SESSION['fecha_entrega_temporal'] = $ent;
			$_SESSION['elaborado_por_temporal'] = $aut;			
			
		
	}	
	
	public function compruebaSesionProductos()
	{
		if(!isset($_SESSION)) 
    			session_start();
		
		if(isset($_SESSION)) 
		{
			if(isset($_SESSION["nombre_array"]))
			{
				if(count($_SESSION["nombre_array"]) > 0)
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
			}
			else
			{
				echo 0;
			}
		}
		else 
		{
			echo 0;
		}
	}	
public function existenciasSucursal($idProducto)
{
$q=mysql_query("select 
idSuc,
sucursal,
producto.idAlmacen,
CASE WHEN cantidad IS NOT NULL 
       THEN cantidad
       ELSE 0
END AS cantidad
from (select s.idSuc,s.nombre sucursal, a.idAlmacen from mrp_sucursal s inner join almacen a on s.idAlmacen=a.idAlmacen ) as sucursales
left join
(
select p.idProducto,p.nombre producto, s.cantidad,s.idAlmacen from mrp_producto p left join mrp_stock s on p.idProducto=s.idProducto where p.idProducto=".$idProducto."
) producto on sucursales.idAlmacen=producto.idAlmacen GROUP BY sucursal ORDER BY idSuc");

$ex='<table  border="0" width="100%" class="busqueda" align="center">
    		<tr  class="busqueda_fila">
    			<th align="center">Sucursal</th>
    			<th align="center">Cantidad</th> 
    		</tr>';
while($r=mysql_fetch_object($q))
{
		$ex.='
    		<tr>
    			<td align="center">'.$r->sucursal.'</td>
    			<td align="center">'.$r->cantidad.'</td>
    		</tr>';
}
		$ex.='</table>';
return $ex;	
}


	public function enviarprueba(){

		$id=$_POST['orden'];
		$this->load->model('Orden_compra');
		$orden=$this->Orden_compra->get2($id);
		$detalle_orden=$this->Orden_compra->detalle($id);
   
	echo $orden[0]->correo.'/';
		//print_r($detalle_orden);
	//	print_r($orden);
		$tabla='<table>';
		$tabla.='<tr>';
		$tabla.='<td>Cantidad</td>';
		$tabla.='<td>Unidad</td>';
		$tabla.='<td>Producto</td>';
		$tabla.='<td>Costo unitario</td>';
		$tabla.='<td>Subtotal</td>';
		$tabla.='</tr>';
	/*	foreach ($detalle_orden as $orden => $producto) {
			# code...
			$tabla.='<tr>';
			$tabla.='<td>'.$producto->cantidad.'</td>';
			$tabla.='<td>'.$producto->compuesto.'</td>';
			$tabla.='<td>'.$producto->nombre.'</td>';
			$tabla.='<td>'.$producto->ultCosto.'</td>';
			$tabla.='<td>'.($producto->ultCosto*$producto->cantidad).'</td>';
			$tabla.='</tr>';
		}*/
		$tabla.='</table>';
		
		$totalimpuesto=0;
		$impues=array();
		$impues['IVA']=0;
		$impues['ISR']=0;
		$impues['IEPS']=0;

			foreach ($detalle_orden as $orden2 => $producto2) {
					
				$impuestos=$this->Orden_compra->productoImpuesto($producto2->idProducto);
				
				$subtotal=$producto2->ultCosto*$producto2->cantidad;
				
				//$producto_impuesto=0;
				print_r($impuestos);			
				foreach ($impuestos as $impuesto => $impus) {
						
					$nomImpuesto=$impus->nombre;
					//echo $nomImpuesto;
					//$producto_impuesto = (($subtotal) * $impus->valor / 100);
						if($nomImpuesto='IVA'){
							$producto_impuesto = (($subtotal) * $impus->valor / 100);
							$impues['IVA']+= $producto_impuesto;
						}else{
							$producto_impuesto = (($subtotal) * $impus->valor / 100);
							$impues['ISR']+= $producto_impuesto;
						}
				}
				
			}
			print_r($impues);
					$tabla.='<table border=1>';

			foreach ($impues as $key =>$value) {
				if($value!=0){
					$tabla.='<tr>';
					$tabla.='<td>'.$key.'</td>';
					$tabla.='<td>'.$value.'</td>';
					$tabla.='</tr>';
				}		
			} 
			$tabla.='</table>';
			/*	$tabla.='<table>';
					$tabla.='<tr>';
					//$tabla.='	<td >'.$impus->nombre.' ('.$impus->valor.'%)</td>';
					$tabla.='	<td >'.$totalimpuesto.' MXP</td>';
					$tabla.='</tr>'; 
				$tabla.='</table>'; */
		echo $tabla;


	}

	public function enviar(){
 //aqui genera el pdf
		   	ob_start();
		    $id=ob_get_clean();
		    $id=$_GET['orden'];
		    $tabla=ob_get_clean();
			$this->load->model('Orden_compra');
			$orden=$this->Orden_compra->get2($id);
			$detalle_orden=$this->Orden_compra->detalle($id);
			$cliente=$this->Orden_compra->organizacion();
			$autorizoOrden = $orden[0]->Autorizacion;
		    $Email=$orden[0]->correo;

		    	//echo '<script> alert("'.$orden[0]->Autorizacion.'"); </script>';
			//$Email='ovazquez@netwarmonitor.com';
			$imagen='../../netwarelog/archivos/1/organizaciones/'.$cliente[0]->logoempresa;
			$imagesize=getimagesize($imagen);
			$porcentaje=0;
			if($imagesize[0]>300 && $imagesize[1]>150){
				if($imagesize[0]>$imagesize[1]){
					$porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
					$imagesize[0]=300;
					$imagesize[1]=(($porcentaje*300)/100);
				}else{
					$porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
					$imagesize[0]=300;
					$imagesize[1]=(($porcentaje*300)/100);	
				}
			}
			//"../../netwarelog/archivos/1/organizaciones/'.$cliente[0]->logoempresa.'"
			$src="";
			if($imagen!="" && file_exists($imagen))
				$src='<img src="'.$imagen.'" style="width:'.$imagesize[0].'px;height:'.$imagesize[1].'px"/>';
						//echo $src.'X';
		if($Email!=''){
			//$Email=$orden[0]->correo;
		    $tabla2='';	
			$tabla2.='<style>
						.nmcatalogbusquedatit {
						  
						    border-radius: 5px;
						    padding: 5px 3px 5px 3px;
						    color:#e5e5e5;
						    text-shadow: 0px 1px 0px #101010;
						    font-size:14px;
						    font-weight:normal;
						}
						#contenido{
							background-color: #e9e9e9;
						}
						</style>
			<table>
		<tr>
			<td>
				<div style="width:300px;height:150px">
					'.$src.'
				</div>
			</td>
			<td>
				<div style="margin-left:76px">
					<table>
						<tr>
							<td>
								<div style="margin-left:12px">
									<table>
										<tr>
											<td style="font-weight:bold;font-size:11px">Ordende compra</td>
										</tr>
										<tr>
											<td style="font-size:8px;color:#ff0000">'.$id.'</td>
										</tr>
										<tr>
											<td style="font-weight:bold;font-size:11px">Fecha y Hora de emision</td>
										</tr>
										<tr>
											<td style="font-size:8px">'.$orden[0]->Fecha_pedido.'</td>
										</tr>
										<tr>
											<td style="font-weight:bold;font-size:11px">Fecha de entrega</td>
										</tr>
										<tr>
											<td style="font-size:8px">'.$orden[0]->Fecha_de_entrega.'</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	<div style="font-size:10px;margin-bottom:15px"><h1 align="center">Orden de compra</h1></div>
		<div style="font-weight:bold;font-size:11px;background:#D8D8D8">Cliente</div>
	<div>
		<table style="border:1px #D8D8D8">
			<tr>
				<td style="font-size:10px">Razon Social:</td>
				<td style="width:550px;font-size:10px">'.$cliente[0]->nombreorganizacion.'</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">R.F.C:</td>
				<td style="width:90px;font-size:11px">'.$cliente[0]->RFC.'</td>
			</tr>
		</table>
	</div>
	<div>
		<table style="border:1px #D8D8D8">
			<tr>
				<td style="font-size:10px">Domicilio:</td>
				<td style="width:218px;font-size:10px">'.$cliente[0]->domicilio.'</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Estado:</td>
				<td style="width:138px;font-size:10px">'.$cliente[0]->estado.'</td> 
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Municipio:</td>
				<td style="width:240px;font-size:10px">'.$cliente[0]->municipio.'</td>
			</tr>
		</table>
	</div>
<!--	<div>
		<table style="border:1px #D8D8D8">
			<tr>
				<td style="font-size:10px">Delegacion:</td>
				<td style="width:180px;font-size:10px">COQUIMATLAN</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Estado:</td>
				<td style="width:153px;font-size:10px">'.$cliente[0]->estado.'</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Codigo postal:</td>
				<td style="width:25px;font-size:10px">28400</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Pais:</td>
				<td style="width:178px;font-size:10px">MEXIdeedeCO</td>
			</tr>
		</table>
	</div> -->
	<div style="font-weight:bold;font-size:11px;background:#D8D8D8">Proveedor</div>
	<div>
		<table style="border:1px #D8D8D8">
			<tr>
				<td style="font-size:10px">Razon Social:</td>
				<td style="width:550px;font-size:10px">'.$orden[0]->Proveedor.'</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">R.F.C:</td>
				<td style="width:90px;font-size:11px">'.$orden[0]->rfc.'</td>
			</tr>
		</table>
	</div>
	<div>
		<table style="border:1px #D8D8D8">
			<tr>
				<td style="font-size:10px">Domicilio:</td>
				<td style="width:698px;font-size:1px">'.$orden[0]->domicilio.'</td>
		<!--		<td style="border-left:1px solid #D8D8D8;font-size:10px">Ciudad:</td>
				<td style="width:138px;font-size:10px">COQUIMATLAN</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Colonia:</td>
				<td style="width:221px;font-size:10px">CENTRO</td> -->
			</tr>
		</table>
	</div>
	<div>
		<table style="border:1px #D8D8D8">
			<tr>
			<!--	<td style="font-size:10px">Delegacion:</td>
				<td style="width:180px;font-size:10px">COQUIMATLAN</td> -->
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Estado:</td>
				<td style="width:230px;font-size:10px">'.$orden[0]->estado.'</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Municipio:</td>
				<td style="width:420px;font-size:10px">'.$orden[0]->municipio.'</td>
			<!--	<td style="border-left:1px solid #D8D8D8;font-size:10px">Codigo postal:</td>
				<td style="width:25px;font-size:10px">28400</td>
				<td style="border-left:1px solid #D8D8D8;font-size:10px">Pais:</td>
				<td style="width:178px;font-size:10px">MEeeXICO</td> -->
			</tr>
		</table>
	</div>
<div id="contenido">
	<div>
		<table>
			<tr>
				<td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:76px; background-color: #5F5F5F;" class="nmcatalogbusquedatit">Cantidad</td>
				<td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:79px; background-color: #5F5F5F;" class="nmcatalogbusquedatit">Unidad</td>
				<td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:377px; background-color: #5F5F5F;" class="nmcatalogbusquedatit">Descripcion</td>
				<td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:72px; background-color: #5F5F5F;" class="nmcatalogbusquedatit">Precio</td>
				<td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:80px; background-color: #5F5F5F;" class="nmcatalogbusquedatit">Importe</td>
			</tr>
		</table>
	</div>
	<div>
		<table style="border-collapse:collapse">';
		foreach ($detalle_orden as $orden => $producto) {
			$tabla2.='<tr>';	
			$tabla2.='<td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:81px;text-align:right;padding-right:3px">'.$producto->cantidad.'</td>';
			$tabla2.='<td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:81px;padding-left:3px">'.$producto->compuesto.'</td>';
			$tabla2.='<td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:383px">
						<div style="width:381px;padding-top:-2px">'.$producto->nombre.'</div>
					</td>';
			$tabla2.='<td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:75px;text-align:right;padding-right:3px">$'.$producto->ultCosto.'</td>';
			$tabla2.='<td style="font-size:11px;border-bottom:1px dashed #D8D8D8;width:93px;text-align:right">$'.($producto->ultCosto*$producto->cantidad).'</td>';
			$tabla2.='</tr>';
			$total+=$producto->cantidad*$producto->ultCosto;
		}	
	$tabla2.='</table>
	</div>
	<div style="margin-left:540px;margin-top:20px">
		<table>
			<tr>
				<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left;width:100px">Subtotal</td>
				<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right;width:100px">$'.$total.'MXP</td>
			</tr>';
	/*	$totalimpuesto=0;
		$impues=array();
		$impues['IVA']=0;
		$impues['ISR']=0;
		$impues['IEPS']=0;

			foreach ($detalle_orden as $orden2 => $producto2) {
					
				$impuestos=$this->Orden_compra->productoImpuesto($producto2->idProducto);
				
				$subtotal=$producto2->ultCosto*$producto2->cantidad;
				
				//$producto_impuesto=0;
				
				foreach ($impuestos as $impuesto => $impus) {
						
					$nomImpuesto=$impus->nombre;
					$producto_impuesto = (($subtotal) * $impus->valor / 100);
						if($nomImpuesto='IVA'){
							$producto_impuesto = (($subtotal) * $impus->valor / 100);
							$impues['IVA']+= $producto_impuesto;
						}else{
							$producto_impuesto = (($subtotal) * $impus->valor / 100);
							$impues['ISR']+= $producto_impuesto;
						}
				}
				
			}

			foreach ($impues as $key =>$value) {
				if($value!=0){
					$tabla2.='<tr>';
					$tabla2.='<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">'.$key.'</td>';
					$tabla2.='<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">'.$value.'</td>';
					$tabla2.='</tr>';
				}		
			} */
					$tabla2.='<tr>';
					$tabla2.='<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">IVA</td>';
					$tabla2.='<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">$'.($total*0.16).' MPX</td>';
					$tabla2.='</tr>';
			$tabla2.='<tr>
				<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">Total</td>
				<td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">$'.($total*1.16).' MXP</td>
			</tr>
		</table>
	</div>
</div>		
	<div style="font-weight:bold;font-size:11px;background:#D8D8D8;margin-top:10px">Autorizado por:</div>
	<div>
		<table style="border:0px #D8D8D8">
			<tr>
				<td style="font-size:10px">'.$autorizoOrden.'</td>
			</tr>
		</table>
	</div>
	<div style="font-weight:bold;font-size:11px;background:#D8D8D8;margin-top:10px">Observaciones</div>
	<div style="font-size:10px;border:1px solid #D8D8D8;padding:5px">
		<div style="width:381px;padding-top:-2px"></div>
	</div>
	<div style="font-size:8px;padding-left:100px">
		<table>
			<tr>
				<td style="padding-left:50px;border-collapse:collapse">Esta Orden de compra Fue Generada Utilizando Herramientas De Netwarmonitor</td>
			</tr>
		</table>
	</div>';		    				
				  	require_once(dirname(__FILE__).'/../../../SAT/PDF/html2pdf/html2pdf.class.php');
					try{
						    $pdf = new HTML2PDF('P','A4','es');
						   	//$pdf->setModeDebug();
						  	  //$this->pdf->AddPage();
						    $pdf->WriteHTML($tabla2);
							//ob_end_clean();
						    $pdf->Output(dirname(__FILE__).'/../../../facturas/OrdenDeCompra'.$id.'.pdf', 'F');

					}catch(HTML2PDF_exception $e) {
					        echo $e;
					        exit;
		    		} 
		    		
					require_once('../../modulos/phpmailer/sendMail.php');
				    
				    $mail->From = "mailer@netwarmonitor.com";
				    $mail->FromName = "NetwarMonitor";
				    $mail->Subject = "Oden de Compra";
				    $mail->AltBody = "NetwarMonitor";
				    $mail->MsgHTML('Orden de Compra');
				    //$mail->AddAttachment('../../modulos/facturas/'.$uid.".xml");
				    $mail->AddAttachment(dirname(__FILE__).'/../../../facturas/OrdenDeCompra'.$id.'.pdf');
				    $mail->AddAddress($Email,$Email);
				    
				    @$mail->Send(dirname(__FILE__).'/../../../facturas/OrdenDeCompra'.$id.'.pdf');
				    unlink(dirname(__FILE__).'/../../../facturas/OrdenDeCompra'.$id.'.pdf'); 

				    $estatus=$this->Orden_compra->cambiaestatusEnviada($id);

				    echo '<script> alert("Se envio la orden al proveedor."); </script>';
		}else{
			echo '<script> alert("Este proveedor no cuenta con correo electronico."); </script>';
		}

	}
	/*public function unidadx($idUnidad = , $idPrv = 10)
	{
		$idPro = $_POST['id'];
		$cantidad = $_POST['cantidad'];
		
		$this->load->model('Orden_compra');
		$select_unidad="";
		
		foreach($this->Orden_compra->buscaUnidadX(4, $idPrv, $cantidad) as $uni=>$idUni):
			$piezas = explode("_", $idUni);
			if($piezas[1] == $idUnidad)
				$select_unidad .= "Unidad: ". $piezas[1]. " (".$piezas[2].") equivalente: " . $piezas[0]. " unidades base<br>";
		endforeach;
						  
		echo $select_unidad;
		//echo $this->Orden_compra->buscaUnidad($idPro, $idPrv);
		//echo "ok";
	}*/
	
	
	
}