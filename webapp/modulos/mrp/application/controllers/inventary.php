<?php	

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventary extends CI_Controller {

	public function historic()
	{
		$this->load->model('Inventario');
		$this->load->model('Producto');			
		
		
		$selec_productos='<select id="producto" class="form-control">';
		$selec_productos.='<option value="">-Todos los productos-</option>';
		foreach($this->Producto->listaProductos(0) as $producto)
		{
			$selec_productos.='<option value="'.$producto->idProducto.'">'.utf8_decode($producto->nombre).'</option>';
		}
		$selec_productos.='</select>';
		$data["productos"]=$selec_productos;
		
		$select_suc='<select id="sucursal" class="form-control" onchange="cargaalmacenes2(this.value);">';
		$select_suc.='<option value="">-Todas las sucursales-</option>';
		foreach($this->Inventario->sucursales() as $suc)
		{
			$select_suc.='<option value="'.$suc->idSuc.'">'.utf8_decode($suc->nombre).'</option>';
		}
		$select_suc.='</select>';

		$data["sucursales"]=$select_suc;
		
		
		$string_mov='<table border="0" style="width:100%" align="center" id="orden">
		<thead>
		<tr class="tit_tabla_buscar">
		<td class="nmcatalogbusquedatit" align="center" sort="fecha">Fecha</td>
		<td class="nmcatalogbusquedatit" align="center" sort="codigo">Código</td>
		<td class="nmcatalogbusquedatit" align="center" sort="descrip">Descripción</td>
		<td class="nmcatalogbusquedatit" align="center" sort="alma">Almacen</td>
		<td class="nmcatalogbusquedatit" align="center" sort="uni">Unidad</td>
		<td class="nmcatalogbusquedatit" align="center" sort="exist">Existencia</td>
		</tr> </thead><tbody>';

		if(isset($_POST["producto"])){$producto=$_POST["producto"];}else{$producto='';}
		if(isset($_POST["almacen"])){$almacen=$_POST["almacen"];}else{$almacen='';}
		if(isset($_POST["inicio"])){$inicio=$_POST["inicio"];}else{$inicio='';}
		if(isset($_POST["fin"])){$fin=$_POST["fin"];}else{$fin='';}

		$i=0;
		foreach($this->Inventario->movimientos($producto,'',$almacen,$inicio,$fin) as $entrada)
		{
			if($i%2==0){$string_mov.='<tr class="nmcatalogbusquedacont_1">';} else{ $string_mov.='<tr class="nmcatalogbusquedacont_2">';}
			
			list($fecha, $hora) = explode(' ', $entrada->fecha);
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
			
			
			$this->load->model('Compra');
			$selec_unidad=$this->Compra->unidadMinima($entrada->idProducto);
			
			$string_mov.='
			<td align="center">'.$valor.'</td>
			<td align="center">'.$entrada->codigo.'</td>
			<td align="center">'.$entrada->nombre.'</td>
			<td align="center">'.utf8_decode($entrada->almacen).'</td>
			<td align="center">'.$selec_unidad.'</td>
			<td align="center">'.$entrada->stock.'</td>
			</tr>';
			$i++;
		}
		
		if($i<12)
		{
			for($j=$i;$j<=12;$j++)
			{
				if($j%2==0){$string_mov.='<tr class="nmcatalogbusquedacont_1" style="height:20px">';} else{ $string_mov.='<tr class="nmcatalogbusquedacont_2" style="height:20px">';}
				$string_mov.='
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				</tr>';
			}
		}	
		
		
		$string_mov.='</tbody>
		<tfoot class="nav"><tr align="right">
		<td colspan=7>
		<div class="pagination"></div>
		<div class="paginationTitle">Pagina</div>
		<div class="selectPerPage"></div>
		
		</td>
		</tr>
		
		</tfoot>
		</table>';
		
		if(isset($_POST["producto"])){
			echo $string_mov;}
			else{
				$data["existencias"]=$string_mov;
				$this->load->view('inventary/historic',$data);
			}
		}
	/////////////////////////////////////////////////
		public function index()
		{
			
			$this->load->model('Inventario');
			$this->load->model('Producto');	
			$this->load->model('Orden_produccion');			
			
			
			$selec_productos='<select id="producto" class="form-control">';
			$selec_productos.='<option value="">-Todos los productos-</option>';
			foreach($this->Producto->listaProductos(0) as $producto)
			{
				$selec_productos.='<option value="'.$producto->idProducto.'">'.utf8_decode($producto->nombre).'</option>';
			}
			$selec_productos.='</select>';
			$data["productos"]=$selec_productos;
			
			$select_suc='<select id="sucursal" class="form-control" onchange="cargaalmacenes(this.value);">';
			$select_suc.='<option value="">-Todas las sucursales-</option>';
			foreach($this->Inventario->sucursales() as $suc)
			{
				$select_suc.='<option value="'.$suc->idSuc.'">'.utf8_decode($suc->nombre).'</option>';
			}
			$select_suc.='</select>';

			$data["sucursales"]=$select_suc;
			
			$select_pro='<select id="proveedor" class="form-control">';
			$select_pro.='<option value="">-Todas los proveedores-</option>';
			foreach($this->Inventario->proveedores() as $pro)
			{
				$select_pro.='<option value="'.$pro->idPrv.'">'.utf8_decode($pro->razon_social).'</option>';
			}
			$select_pro.='</select>';

			$data["proveedores"]=$select_pro;
			
			
			$string_mov='<table class="table" style="font-size: 10px; border: none;" border="1" align="center" id="orden">
			<thead>
			<tr class="tit_tabla_buscar">
			<td class="nmcatalogbusquedatit" align="center" rowspan="1">Fecha</td>
			<td class="nmcatalogbusquedatit" align="center" rowspan="1">Código</td>
			<td class="nmcatalogbusquedatit" align="center" rowspan="1">Descripción</td>
			<td class="nmcatalogbusquedatit" align="center" rowspan="1">Precio unitario</td>
			<td class="nmcatalogbusquedatit" align="center" rowspan="1">Unidad</td>
			<td class="nmcatalogbusquedatit" align="center" rowspan="1">Almacen</td>
			<td class="nmcatalogbusquedatit" align="center" rowspan="1">Proveedor</td>
			<td class="nmcatalogbusquedatit" align="center" colspan="2" >Entrada</td>
			<td class="nmcatalogbusquedatit" align="center" colspan="3">Existencia</td>
			</tr>

			<tr class="nmsubtitle">
			<td align="center">Cantidad</td>
			<td align="center">Subtotal</td>
			<td align="center">Unidad</td>
			<td align="center">Cantidad</td>
			<td align="center">Subtotal</td>
			</tr><thead><tbody>';

			if(isset($_POST["producto"])){$producto=$_POST["producto"];}else{$producto='';}
			if(isset($_POST["proveedor"])){$proveedor=$_POST["proveedor"];}else{$proveedor='';}
			if(isset($_POST["almacen"])){$almacen=$_POST["almacen"];}else{$almacen='';}
			if(isset($_POST["inicio"])){$inicio=$_POST["inicio"];}else{$inicio='';}
			if(isset($_POST["fin"])){$fin=$_POST["fin"];}else{$fin='';}

			$i=0;
			foreach($this->Inventario->movimientos($producto,$proveedor,$almacen,$inicio,$fin) as $entrada)
			{
				if($i%2==0){$string_mov.='<tr class="nmcatalogbusquedacont_1">';} else{ $string_mov.='<tr class="nmcatalogbusquedacont_2">';}
				
				list($fecha, $hora) = explode(' ', $entrada->fecha);
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
				
				
				
				$this->load->model('Compra');
				$selec_unidad=$this->Compra->unidadMinima($entrada->idProducto);
				
				$string_mov.='
				<td align="center">'.$valor.'</td>
				<td align="center">'.$entrada->codigo.'</td>
				<td align="center">'.$entrada->nombre.'</td>
				<td align="center">$'.number_format($entrada->costo,2,".",",").'</td>
				<td align="center">'.$entrada->compuesto.'</td>
				<td align="center">'.utf8_decode($entrada->almacen).'</td>
				<td align="center">'.utf8_decode($entrada->proveedor).'</td>
				<td align="center">'.$entrada->cantidad.'</td>
				<td align="center">$'.number_format($entrada->cantidad*$entrada->costo,2,".",",").'</td>
				<td align="center">'.$selec_unidad.'</td>
				<td align="center">'.$entrada->stock.'</td>
				<td align="center">$'.number_format($entrada->stock*$entrada->costo,2,".",",").'</td>
				</tr>';
				$i++;
			}
			
			if($i<12)
			{
				for($j=$i;$j<=12;$j++)
				{
					if($j%2==0){$string_mov.='<tr class="nmcatalogbusquedacont_1">';} else{ $string_mov.='<tr class="nmcatalogbusquedacont_2">';}
					$string_mov.='
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					</tr>';
				}
			}	
			
			
			$string_mov.='</tbody>
			<tfoot class="nav"><tr align="right">
			<td colspan=7>
			<div class="pagination"></div>
			<div class="paginationTitle">Pagina</div>
			<div class="selectPerPage"></div>
			
			</td>
			</tr>
			
			</tfoot></table>';
			
			if(isset($_POST["producto"])){
				echo $string_mov;}
				else{
					$data["movimientos"]=$string_mov;
					
					$hijos=$this->Orden_produccion->damehijos(9);
					$data["hijos"]=$hijos;
					
					$this->load->view('inventary/index',$data);
				}
			}

			public function stock(){

				$this->load->model('Inventario');	
				$this->load->model('Producto');	


			$string_stock="";
			$string_stock='<tr class="nmcatalogbusquedacont_1"><td align="center">No hay datos</td></tr>';

				$select_suc='<select id="sucursal" class="form-control"  onchange="cargaalmacenes3(this.value);" >';
				$select_suc.='<option value="0" selected>-Todas las sucursales-</option>';
				foreach($this->Inventario->sucursales() as $suc)
				{
					$select_suc.='<option value="'.$suc->idSuc.'">'.utf8_decode($suc->nombre).'</option>';
				}
				$select_suc.='</select>';
				
				$data["inventario"]='';
				$data["sucursales"]=$select_suc;
				
				
				$this->load->view('inventary/stock',$data);



			}
			

	////////////////////////////////////////////
			public function stockexis()
			{
	//$almacen=$_POST["almacen"];
	//$post_producto=$_POST["producto"];
	//$existencia=$_POST["existencia"];
				
				if($_POST["idalmacen"]!=0 || $_POST["idalmacen"]!=''){
					$almacen=$_POST["idalmacen"];
					}else{ 
						$almacen=0;
						}if(isset($_POST["producto"])){
							$post_producto=$_POST["producto"];
						}//else{$post_producto='';}
						if(isset($_POST["existencia"])){
							$existencia=$_POST["existencia"];
				}//else{$existencia='';}
	//if(isset($_POST["registros"])){$registros=$_POST["registros"];}else{$registros=20;}
				$this->load->model('Inventario');	
				$this->load->model('Producto');			
	
		/*
		//$selec_productos='<select id="producto" class="long-input" onchange="VerExistencias();">';
		$selec_productos='<select id="producto" class="long-input" >';
		
		$selec_productos.='<option value="">-Todos los productos-</option>';
		/*
		foreach($this->Producto->listaProductos(0) as $producto)
		{
			if(!$this->Inventario->tienemateriales($producto->idProducto))
			{	
				$selec_productos.='<option value="'.$producto->idProducto.'">'.utf8_decode($producto->nombre).'</option>';
			}
		}
		
		  $selec_productos.='</select>';
		
		  $data["productos"]=$selec_productos;
	*/
		  
	// if($existencia==1){$valor=" and s.cantidad!=0 ";}else{$valor='';}
		// if($existencia==2){$valor= " and (s.cantidad IS NULL OR s.cantidad=0 )";}else{$valor='';}
		// //if($existencia=='' || $existencia==0){$valor='';}
		// if($almacen==0){$alma=''; $campos=''; $tabla=' '; $relacion='';}
		// else{$campos=',
		// s.cantidad  as cantidad ,
		// s.idAlmacen almacen'; $alma='s.idAlmacen='.$almacen.' and'; $tabla=',mrp_stock s '; $relacion='p.idProducto=s.idProducto and';   }
		// $this->load->database();
		// echo $query =("SELECT 
		// p.idProducto,
		// p.codigo,
		// p.nombre,
		// p.maximo,
		// p.minimo ".$campos."
		// FROM mrp_producto p ".$tabla."
		// where ".$alma."  ".$relacion." p.nombre like '%$producto%'  ".$valor."
		// order by p.nombre limit 0,100");
// 	

		//if( $almacen!='' || $post_producto!='' || $existencia!=''  ){// no carga datos iniciales
		  if($this->Inventario->stock($almacen,$post_producto,$existencia,$registros)){
		  	
		  	foreach($this->Inventario->stock($almacen,$post_producto,$existencia) as $producto)
		  	{
		  		
		  		//echo $producto->idProducto."<br>";
		//if(!$this->Inventario->tienemateriales($producto->idProducto))
		//{	
		  		$inventario[$producto->idProducto][$producto->almacen]=$producto;
		  		/*if(array_key_exists($producto->idProducto,$inventario))
		  		{
		  			
		  			if($almacen==$producto->almacen)
		  			{
		  				$inventario[$producto->idProducto]=$producto;
		  			}
		  		}
		  		else
		  		{		
		  			$inventario[$producto->idProducto]=$producto;
		  		}*/
		  		
		  //}//if
	}//foreach	
	
	//	}// end no carga datos iniciales
	$string_stock="";
	// $string_stock='<table class="busqueda" border="1" width="95%" align="center" id="orden">
	// <thead>
	// <tr class="tit_tabla_buscar">
	// <td align="center" sort="codigo">Código</td>
	// <td align="center" sort="descript">Descripción</td>
	// <td align="center" sort="min">Minimo</td>
	// <td align="center" sort="max">Máximo</td>
	// <td align="center" sort="uni">Unidad</td>
	// <td align="center" sort="exist">Existencia</td>';
	// //$string_stock.='<td align="center" ></td>';
	// $string_stock.='</tr>  </thead><tbody>';

	$i=0;
	foreach($inventario as $almacenArray)
	{		
		foreach ($almacenArray as $key => $producto) {

				if(is_null($producto->cantidad)){
					$cantidad=0;
				}else{
					$cantidad=$producto->cantidad;
				}

	// if($existencia==1)
		// {
			// if($cantidad==0){continue;}	
		// }
// 		
			$selec_unidad='..';


			$this->load->model('Compra');
			$selec_unidad=$this->Compra->unidadMinima($producto->idProducto);



		//var_dump($selec_unidad);
			/*unidades */

			if($i%2==0){$string_stock.='<tr class="nmcatalogbusquedacont_1">';} else{ $string_stock.='<tr class="nmcatalogbusquedacont_2">';}
			$string_stock.='
			<td align="center">'.$producto->codigo.'</td>
			<td align="center">'.$producto->nombre.'</td>
			<td align="center">'.$producto->almacen.'</td>
			<td align="center">'.$producto->minimo.'</td>
			<td align="center">'.$producto->maximo.'</td>
			<td align="center">'.$selec_unidad.'</td>
			<td align="center"><span id="conversion_unidad">'.$cantidad.'</span></td>';

	//echo $selec_unidad."<br><br>";
	//$string_stock.='<td align="center"><input type="Button" value="Agregar a orden de compra" onclick="AgregaraOrden('.$producto->idProducto.',\''.$producto->nombre.'\');" ></td>';

			$string_stock.='</tr>';
			$i++;
		}
	}//END FOREACH
	
}else{
	$string_stock='<tr class="nmcatalogbusquedacont_1"><td align="center">No hay datos</td></tr>';
}

	// if($i<12)
	// {
			// for($j=$i;$j<=12;$j++)
			// {
				// if($j%2==0){$string_stock.='<tr class="busqueda_fila">';} else{ $string_stock.='<tr class="busqueda_fila2">';}
	// $string_stock.='
	// <td align="center"></td>
	// <td align="center"></td>
	// <td align="center"></td>
	// <td align="center"></td>
	// <td align="center"></td>
	// <td align="center"></td>
	// <td align="center"></td>
	// </tr>';
			// }
	// }


	// $string_stock.='</tbody>
			// <tfoot class="nav"><tr align="right">
                        // <td colspan=7>
                                // <div class="pagination"></div>
                                // <div class="paginationTitle">Pagina</div>
                                // <div class="selectPerPage"></div>
//                                 
                        // </td>
                // </tr>
//        
			 // </tfoot></table>';

if(isset($_POST["almacen"]) || isset($_POST["producto"]) ){
	echo $string_stock;
}else{
	
/*	$select_suc='<select id="sucursal" class="long-input nminputselect"  onchange="cargaalmacenes3(this.value);" >';
	$select_suc.='<option value="0" selected>-Todas las sucursales-</option>';
	foreach($this->Inventario->sucursales() as $suc)
	{
		$select_suc.='<option value="'.$suc->idSuc.'">'.utf8_decode($suc->nombre).'</option>';
	}
	$select_suc.='</select>'; */
	
	$data["inventario"]=$string_stock;
	//$data["sucursales"]=$select_suc;
	
	
	$this->load->view('inventary/stock',$data);
} 


}

	////////////////////////////////////////////

	//onchange="VerExistencias();"


}
