<?php
class Compra extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
	/*OBTENER LA UNIDAD MINIMA*/
	public function unidadMinima($idProducto)
	{
		$this->load->database();
		//echo 'SELECT idunidad FROM mrp_producto WHERE idProducto='.$idProducto.';<br>';
		$query = $this->db->query('SELECT idunidad FROM mrp_producto WHERE idProducto='.$idProducto); 
		if ($query->num_rows() > 0 )
		{
		 				
				$row = $query->row();
				if($row->idunidad!=0){
				$compuesto=$this->conversionMinima($row->idunidad);		
				return $compuesto;
				
		}
		
		else { 
		 
		  return "Sin asignar Unidad"; 
			}
		}
	}
	
	
	public function conversionMinima($unidad)
	{
			$this->load->database();
			//echo 'SELECT conversion,unidad,compuesto from mrp_unidades where idUni='.$unidad.';<br>';
			$query = $this->db->query('SELECT conversion,unidad,compuesto from mrp_unidades where idUni='.$unidad); 
			$row = $query->row();
			$compuesto=$row->compuesto;
			
			if($row->unidad!=-1)
			{
						
				if($row->unidad!=$unidad)
				{	
					$compuesto=$this->conversionMinima($row->unidad);
					//var_dump($compuesto);
				}
				 
			}
			  	
			return $compuesto;	
	}
	
	/*end */
	
	/*Funcion que obtiene los componentes de un producto*/
	public function conversion($cantidad,$unidad)
	{
			$this->load->database();
			$conversion=1;
			$query = $this->db->query('SELECT conversion, unidad from mrp_unidades where idUni='.$unidad); 
			$row = $query->row();
			if($row->unidad!=-1)
			{		
				if($row->unidad!=$unidad)
				{
					if($row->conversion!=0)
					{	
					$conversion=$this->conversion($row->conversion,$row->unidad);
					}
				}
			}
			
			return $cantidad*$conversion;	
	}
	
	
	public function componentes($idproducto)
	{
		/*
			$this->load->database();
		$query = $this->db->query('SELECT 
		poc.idPrOr id,
		poc.cantidad,
		u.unidad,
		p.nombre,
		poc.ultCosto 
		FROM mrp_producto_orden_compra poc,mrp_unidades u,mrp_producto p
       WHERE poc.idUnidad=u.idUni and poc.idProducto=p.idProducto and poc.idOrden='.$id.' order by p.nombre'); 
		return $query->result();		
	*/
	}
	
	/*Funcion que inserta guarda la información de compra y su detalle*/
	public function guardar($datos,$fact,$xml,$factura,$orden,$sucursal,$comentario)
	{		
			date_default_timezone_set('America/Mexico_City'); 
			$dia = date("Y-m-d");
			$this->load->database();
			$this->db->trans_start();
		
			$query = $this->db->query("INSERT INTO mrp_compra (`id`, `factura`, `archivo`, `idOrden`, `fecha`,`fact`,`xml`) VALUES (NULL, '".$factura."', '', '".$orden."', Now(), '".$fact."', '".$xml."');");
			$idCompra=mysql_insert_id();
			foreach($datos as $dato)
			{

			list($idProductoOrden,$cantidad,$costo)=explode("_",$dato);
				$costo=str_replace(",","",$costo);
				$cantidad=str_replace(",","",$cantidad);
				
			//echo 'idProducto='.$idProductoOrden;	
			$querym = $this->db->query('select idUnidad, idProveedor, idProducto from mrp_producto_orden_compra poc inner Join mrp_orden_compra oc on  oc.idOrd=poc.idOrden
where idPrOr='.$idProductoOrden);
			$rowm = $querym->row();
			//echo $rowm->idProducto.'deded';
			//echo $rowm->idUnidad.'X';

			$queryx = $this->db->query('select idunidad,nombre from mrp_producto where idProducto='.$rowm->idProducto);
			$uni = $queryx->row();
			//$querycon = $this->db->query('SELECT idunidad as idUnidad from mrp_producto WHERE idProducto='$rowm->idProducto);
			//$y = $querycon->row();
			//echo 'unidad='.$uni->idunidad.',cantidad='.$cantidad;
			//exit();

			if($rowm->idUnidad == $uni->idunidad){
				$cantidad_convertida=$cantidad;
			}else{
				//$cantidad_convertida=$this->conversion($cantidad,$uni->idunidad);
				//unidad de stock o venta
				$query = $this->db->query('select conversion from mrp_unidades where idUni='.$uni->idunidad);
				$unS = $query->row();

				//unidad de compra
				$query = $this->db->query('select conversion from mrp_unidades where idUni='.$rowm->idUnidad);
				$unC = $query->row();

			    $cantidad_convertida = ($cantidad*($unC->conversion/$unS->conversion));

			}
			 //echo 'uniCompra='.$rowm->idUnidad. 
			// $cantidad_convertida=($cantidad_convertida/1000);

	
		$query = $this->db->query("INSERT INTO mrp_detalle_compra (id,idCompra,idProductoOrdenCompra,cantidad,costo,stock) 
		VALUES (NULL, '".$idCompra."', '".$idProductoOrden."', '".$cantidad."','".($costo)."',0);");	
		$id_detalle=$this->db->insert_id();
		

		
		$query1 = $this->db->query('SELECT poc.idProducto,oc.idProveedor FROM mrp_detalle_compra dc ,mrp_producto_orden_compra poc,mrp_orden_compra oc WHERE  poc.idPrOr=dc.idProductoOrdenCompra and oc.idOrd=poc.idOrden and dc.idProductoOrdenCompra='.$idProductoOrden);
		
		$row1 = $query1->row();
	
		$query2 = $this->db->query('SELECT s.cantidad stock,s.idProducto,oc.idProveedor FROM mrp_detalle_compra dc ,mrp_producto_orden_compra poc,mrp_stock s,mrp_orden_compra oc WHERE  
		poc.idPrOr=dc.idProductoOrdenCompra and poc.idProducto=s.idProducto and oc.idOrd=poc.idOrden and dc.idProductoOrdenCompra='.$idProductoOrden.'  and s.idAlmacen='.$sucursal.'   group by dc.idProductoOrdenCompra');


if ($query2->num_rows() > 0)
		{	
			$row2 = $query2->row();
			$query3 = $this->db->query('UPDATE mrp_stock set cantidad="'.($cantidad_convertida+$row2->stock).'" where idProducto='.$row2->idProducto.' and idAlmacen='.$sucursal);	
			$nuevostock=$cantidad_convertida+$row2->stock;	
			
			$query4 = $this->db->query('UPDATE  mrp_producto_proveedor set costo='.$costo.' where idProducto='.$row2->idProducto.' and idPrv='.$row2->idProveedor);	
			
		}
		else
		{
			$nuevostock=$cantidad_convertida;  	
			$query3 = $this->db->query("INSERT INTO mrp_stock (`id`, `idProducto`,`cantidad`, `idAlmacen`,`idUnidad`) VALUES (NULL,'".$row1->idProducto."','".$cantidad_convertida."',".$sucursal.",1);");
			$query4 = $this->db->query('UPDATE  mrp_producto_proveedor set costo="'.$costo.'" where idProducto='.$row1->idProducto.' and idPrv='.$row1->idProveedor);		 
		}		
						
			$query5 = $this->db->query('UPDATE mrp_detalle_compra set stock="'.$nuevostock.'" where id='.$id_detalle);	

			/* ---- Lotes de Productos-----*/
			$queryLoteSql = "INSERT INTO mrp_lote (idOrdeCom,idProducto,cantidad,idunidad,fecha_recibido) VALUES ('".$idCompra."','".$row1->idProducto."','".$cantidad."','".$uni->idunidad."','".$dia."')";
			//echo $queryLoteSql;
			
			$querylote = $this->db->query($queryLoteSql);
			/* -------------------------------------------*/		
	
	}//end foreach

			
			$query6 = $this->db->query('UPDATE mrp_orden_compra set estatus="Cerrada" where idOrd='.$orden);	
			$query7 = $this->db->query('UPDATE mrp_orden_compra set comentario="'.$comentario.'" where idOrd='.$orden);	
			$this->db->trans_complete();			
	}

	

/*****************************/
public function actualizar($id,$fact,$xml,$factura)
{
	$this->load->database();	
	$query5 = $this->db->query('UPDATE mrp_compra set factura="'.$factura.'",fact="'.$fact.'",xml="'.$xml.'" where id='.$id);		
}	

/*****************************/
	public function factura($idOrden)
	{
	$this->load->database();
		$query = $this->db->query('SELECT id,factura,fact,xml FROM mrp_compra c,mrp_orden_compra oc WHERE oc.idOrd=c.idOrden 							and c.idOrden='.$idOrden.' LIMIT 1');
	if ($query->num_rows() > 0)
	{
		return $query->row();
		
	}
	else{ return "";}
	}
	
	/*Funcion que obtiene el detalle de una orden de compra*/
	public function detalle($id)
	{
		$this->load->database();
		$query = $this->db->query('SELECT 
		poc.idPrOr id,
		poc.cantidad,
		u.unidad,
		u.compuesto,
		p.nombre,
		poc.ultCosto 
		FROM mrp_producto_orden_compra poc,mrp_unidades u,mrp_producto p
       WHERE poc.idUnidad=u.idUni and poc.idProducto=p.idProducto and poc.idOrden='.$id.' order by p.nombre'); 
		return $query->result();
	}
	/*Funcion que obtiene datos generales de una orden de compra*/
	public function get($id)
	{
		$this->load->database();
		$query = $this->db->query('Select 
		oc.idOrd Id,
		p.razon_social Proveedor,
		oc.fecha_pedido Fecha_pedido,
		oc.fecha_entrega Fecha_de_entrega,
		oc.elaborado_por Elaboro,
		s.nombre Sucursal,
		s.idSuc idSucursal,
		a.nombre Almacen,
		a.idAlmacen idAlmacen,
		oc.autorizado_por Autorizacion, 
		oc.estatus Estatus,
		oc.idOrdPro, 
		oc.comentario Comentario
		from mrp_orden_compra oc, mrp_sucursal s ,mrp_proveedor p ,almacen a
		where  a.idAlmacen=oc.idAlmacen and s.idSuc=oc.idSuc and p.idPrv=oc.idProveedor and oc.idOrd='.$id); 
		return $query->result();
	}
	/*Funcion que obtiene las ordenes de compra*/
	public function obtenOrdenes()
	{
		$this->load->database();
		$result=0;
		//$query = $this->db->query('SELECT name, title, email FROM my_table');
		$query = $this->db->get('mrp_orden_compra');
		if ($query->num_rows() > 0)
		{
   			$result=$query->result();
	    }
		return $result;
	}
	/*Funcion que ayuda a crear el grid de ordenes de compra*/
	public function grid($pagina="1",$filtro=1,$paginacion=10)
	{
	if($filtro!=1){ list($valor,$campo)=explode("_",$filtro); $filtro=$campo." like '%".$valor."%'"; }	
		
	$this->load->database();
	if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}
	$query = $this->db->query('Select 
	oc.idOrd Id,
	p.razon_social Proveedor,
	oc.fecha_pedido Fecha_pedido,
	oc.fecha_entrega Fecha_de_entrega,
	oc.elaborado_por Elaboro,
	a.nombre Almacen,
	oc.autorizado_por Autorizacion,
	oc.estatus Estatus,
	oc.idOrdPro Orden_de_produccion 
from mrp_orden_compra oc, mrp_sucursal s ,mrp_proveedor p ,almacen a
where a.idAlmacen=oc.idAlmacen and s.idSuc=oc.idSuc and p.idPrv=oc.idProveedor and '.$filtro.' order by oc.idOrd desc  LIMIT '.$begin.','.$paginacion);
	
	$q=$this->db->query('SELECT idOrd from  mrp_orden_compra');
	$paginas=($q->num_rows()/$paginacion);if($q->num_rows()%$paginacion!=0){$paginas++;}
	
	$grid=array();
	$grid["data"]=$query->result_array();
	$grid["nombre"]='Ordenes de compra';
	$grid["paginas"]=$paginas;			
	return $grid;
	}
	////////////////////////////////////////////////
	public function cxp($idorden,$total){
	
		$total=str_replace(',', '', $total);
	    echo '('.$total.'-'.$idorden.')';
 		$this->load->database();
 		date_default_timezone_set('America/Mexico_City'); 
		$dia = date("Y-m-d");
 		
//echo $nuevafecha;
 		$sql = $this->db->query('SELECT idProveedor from mrp_orden_compra where idOrd='.$idorden);
 		$prove = $sql->row();

 		$sql1 = $this->db->query('SELECT diascredito from mrp_proveedor where idPrv='.$prove->idProveedor);
 		$dias = $sql1->row();
		$fechavencimiento = strtotime ( '+'.$dias->diascredito.' day' , strtotime ( $dia ) ) ;
		$fechavencimiento = date ( 'Y-m-d' , $fechavencimiento );

 		$this->db->query('INSERT into cxp (fechacargo,fechavencimiento,concepto,monto,saldoabonado,saldoactual,estatus,idProveedor,idOrCom) values ("'.$dia.'","'.$fechavencimiento.'","Orden de compra '.$idorden.'","'.$total.'","0","'.$total.'","0","'.$prove->idProveedor.'","'.$idorden.'")');

 		//echo 'INSERT into cxp (fechacargo,fechavencimiento,concepto,monto,saldoabonado,saldoactual,estatus,idProveedor) values ("'.$dia.'","'.$fechavencimiento.'","Orden de compra '.$idorden.'","'.$total.'","0","'.$total.'","0","'.$prove->idProveedor.'")';
 		return true;
	}











}