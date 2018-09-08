<?php
	class Inventario extends CI_Model {
		function __construct() {
        parent::__construct();
    }
	
	function tienemateriales($id)
	{
		$this->load->database();
		$query = $this->db->query('select idMaterial from mrp_producto_material where idProducto='.$id);
		if ($query->num_rows() > 0)
		{
				return true;
		}else{return false;}	
	}
	
	function proveedores()
	{
		$this->load->database();
		$query = $this->db->query('SELECT idPrv, razon_social FROM mrp_proveedor order by razon_social');
		return $query->result();
	}
	
	function sucursales()
	{
		$this->load->database();
		$query = $this->db->query('SELECT 
		s.idSuc,
		s.nombre
		FROM mrp_sucursal s order by s.nombre');
		return $query->result();
	}
	
	function stock($almacen,$producto,$existencia)
	{ 	
		//if(is_numeric($almacen)){$order=' s.idAlmacen ,';  }else{$order=''; }
		if($existencia==1){
			$valor=" and s.cantidad!=0 ";
		}else{
			if($existencia==2){
				$valor= " and (s.cantidad IS NULL OR s.cantidad=0 )";
			}else{
				$valor='';
			}
		}
		//if($existencia=='' || $existencia==0){$valor='';}
		if($almacen==0){
			$alma=' s.idAlmacen<>0 and '; 
			//$campos=' , s.cantidad as cantidad, a.nombre almacen '; 
			//$tabla=' ,mrp_stock s almacen a '; 
			//$relacion=' p.idProducto=s.idProducto and ';
		}
		else{
			$alma='s.idAlmacen='.$almacen.' and';
			//$campos=', s.cantidad  as cantidad , a.nombre almacen';
			//$tabla=',mrp_stock s, almacen a '; 
			//$relacion='p.idProducto=s.idProducto and';   
		}
		$campos=' , s.cantidad as cantidad, a.nombre almacen '; 
		$this->load->database();
		$query = $this->db->query("SELECT 
		p.idProducto,
		p.codigo,
		p.estatus,
		p.nombre,
		p.maximo,
		p.minimo ".$campos."
		FROM mrp_producto p, mrp_stock s, almacen a 
		where p.idProducto=s.idProducto and s.idAlmacen=a.idAlmacen and p.estatus = 1 and ".$alma."  (p.nombre LIKE '%$producto%' OR p.codigo LIKE '%$producto%')  ".$valor."
		order by p.nombre");
	/*
		var_dump("SELECT 
		p.idProducto,
		p.codigo,
		p.nombre,
		p.maximo,
		p.minimo,
		sum(s.cantidad)  as cantidad ,
		s.idAlmacen almacen
		FROM mrp_producto p left join mrp_stock s on p.idProducto=s.idProducto where p.nombre like '%$producto%'  ".$valor."
		group by ".$order." p.nombre 
		order by p.nombre,cantidad limit 0,".$registros);
	*/
	if($query->num_rows()>0){
		
		return $query->result();
	}
		return false;
	}
	
	function movimientos($producto,$proveedor,$almacen,$inicio,$fin)
	{
		$filtro=' WHERE 1 ';
		if(is_numeric($producto)){$filtro.=' AND poc.idProducto='.$producto;}
		if(is_numeric($proveedor)){$filtro.=' AND oc.idProveedor='.$proveedor;}
		if(is_numeric($almacen)){$filtro.=' AND oc.idAlmacen='.$almacen;}
		
	
		
		if(($inicio!='' && $fin!='' ))
		{
			list($ano,$mes,$dia)=explode("-",$fin);
			$dias=date("d",mktime(0,0,0,$mes+1,0,$ano));
			//var_dump($dias);
			if(($dia+1)>$dias)
			{
				$mes=$mes+1;
				$dia="01";
			}
			else
			{
				$dia=$dia+1;
			}
			
			$fin=$ano."-".$mes."-".$dia;	
			$filtro.=' AND c.fecha between "'.$inicio.'" AND "'.$fin.'" ';
		}
		
	$this->load->database();
	$query = $this->db->query('SELECT 
	p.idProducto,
	p.codigo, 
	p.nombre, 
	c.fecha, 
	pr.razon_social proveedor, 
	dc.costo, 
	dc.cantidad,
	u.compuesto,
	dc.stock, 
	s.nombre sucursal,
	a.nombre almacen 
FROM mrp_detalle_compra dc
INNER JOIN mrp_compra c ON dc.idCompra = c.id
INNER JOIN mrp_orden_compra oc ON oc.idOrd = c.idOrden
INNER JOIN mrp_proveedor pr ON pr.idPrv = oc.idProveedor
INNER JOIN mrp_producto_orden_compra poc ON dc.idProductoOrdenCompra = poc.idPrOr
INNER JOIN mrp_producto p ON p.idProducto = poc.idProducto
INNER JOIN mrp_sucursal s ON s.idSuc = oc.idSuc 
INNER JOIN almacen a ON a.idAlmacen = oc.idAlmacen  
INNER JOIN mrp_unidades u on  poc.idUnidad=u.idUni '.$filtro.'
order by c.fecha desc');



	return $query->result();
	}
	
}