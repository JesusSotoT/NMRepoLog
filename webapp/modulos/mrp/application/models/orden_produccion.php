<?php
	class Orden_produccion extends CI_Model {
		function __construct() {
        parent::__construct();
    }
	
	
	public function almacenes($id)
	{
		$this->load->database();
		$query = $this->db->query('select a.idAlmacen, a.nombre from almacen_sucursal as als, almacen a where als.idAlmacen=a.idAlmacen and als.idSucursal='.$id.' 
 Union
 select a.idAlmacen, a.nombre from mrp_sucursal s, almacen a where a.idAlmacen=s.idAlmacen and s.idSuc='.$id);
		return $query->result();
	}
	public function datosMaterial($idProductomaterial){
		$this->load->database();
		$query = $this->db->query("SELECT SUM(cantidad) from mrp_detalle_orden_produccion where idProducto=".$idProductomaterial);
		return $query->result();
	}
	public function cantAlma($idProducto){
		$this->load->database();
		$query = $this->db->query('SELECT cantidad, ocupados from mrp_stock where idProducto='.$idProducto.' and idAlmacen='.$_SESSION["almacen"]);
		return $query->result();
	}
	
	
	/*Funcion que ayuda a crear el grid de ordenes de compra*/
	public function grid($pagina="1",$filtro=1,$paginacion=20)
	{
		//echo $filtro."YYY";
	if($filtro!=1){ //list($valor,$campo)=explode("_",$filtro); $filtro=$campo." like '%".$valor."%'"; 
}	
		
	$this->load->database();
	if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}
	$query = $this->db->query('select op.id ID,op.fecha Fecha, op.fecha_inicio Fecha_Produccion , op.fecha_fin Fecha_Finalizacion, op.generada_por Orden_generada_por, a.nombre Almacen ,
	CASE
	    WHEN op.estatus=0 THEN "Registrada" 
	    WHEN op.estatus=1 THEN CONCAT("<div style=\"float:left; color: #F47F0B; width:80px;\">En Proceso</div><div style=\"float:left; width:20px;\" ><img class=\"ttline\" clave=\"",ID,"\" src=\"'.base_url().'images/clock.png \"></div>")
	    WHEN op.estatus=2 THEN CONCAT("<div style=\"float:left; color: green; width:80px;\">Terminada</div><div style=\"float:left; width:20px;\"><img class=\"ttline\"  clave=\"",ID,"\" src=\"'.base_url().'images/clock.png \"></div>")
	    WHEN op.estatus=3 THEN CONCAT("<div style=\"float:left; color: red; width:80px;\">Cancelada</div><div style=\"float:left; width:20px;\"><img class=\"ttline\" clave=\"",ID,"\" src=\"'.base_url().'images/clock.png \"></div>")
	END as Estatus
from mrp_orden_produccion op inner join mrp_sucursal s on s.idSuc=op.idSuc  
inner join almacen a on a.idAlmacen=op.idAlmacen

	where 1 and '.$filtro.' order by op.fecha desc LIMIT '.$begin.','.$paginacion);
	

	
	$q=$this->db->query('SELECT id from  mrp_orden_produccion');
	$paginas=($q->num_rows()/$paginacion);if($q->num_rows()%$paginacion!=0){$paginas++;}
	
	$grid=array();
	$grid["data"]=$query->result_array();
	$grid["nombre"]='Ordenes de producción';
	$grid["paginas"]=$paginas;			
	return $grid;
	}
	////////////////////////////////////////////////
	public function giveOrdenProduccion($id)
	{
		$this->load->database();
		$query=$this->db->query('select op.fecha,op.idSuc ,s.nombre sucursal,op.estatus,a.nombre almacen, a.idAlmacen from mrp_orden_produccion op inner join mrp_sucursal s on s.idSuc=op.idSuc inner join almacen a on a.idAlmacen=op.idAlmacen  where id='.$id);
		return $query->row();		
	}

	public function desgloce($id){

		$this->load->database();
		$query=$this->db->query("SELECT group_concat(distinct(b.id_producto)) as ides FROM mrp_detalle_orden_produccion a
	left join mrp_etapas b on b.id_producto=a.idProducto
WHERE a.idOrdPro='$id' order by b.id;");

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
/*		
		
*/
	}

	public function procesos($id,$cadti,$idord)
	{
		$this->load->database();
		$query=$this->db->query("SELECT a.*, b.fecha FROM mrp_procesos a, mrp_orden_produccion b WHERE a.id_etapa='$id' AND b.id='$idord';");

		
		$query=$this->db->query("SELECT a.*, b.fecha FROM mrp_procesos a, mrp_orden_produccion b WHERE a.id_etapa='$id' AND b.id='$idord';");
		$etapas=array();
		$cadtiempo='';
		if ($query->num_rows() > 0){
			$ano=0; $mes=0; $dia=0; $hora=0; $minuto=0; $segundo=0;  $addp='P'; $addt='T';
			foreach ($query->result_array() as $row2){
				if($cadti!=''){
					$fecha = new DateTime($row2['fecha']);
					$fecha->add(new DateInterval($cadti));
					$row2['fecha'] = $fecha->format('Y-m-d H:i:s');
				}
				if($cadtiempo!='PT' && $cadtiempo!=''){
					$fecha = new DateTime($row2['fecha']);
					$fecha->add(new DateInterval($cadtiempo));
					$row2['dta'] = $fecha->format('Y-m-d H:i:s');
				}else{
					$row2['dta']=$row2['fecha'];
				}
				$duracion='';
				$aduracion=explode(':', $row2['duracion']);
				if($aduracion[0]>0){ $duracion.= $aduracion[0].' Años '; $ano+=$aduracion[0]; }
				if($aduracion[1]>0){ $duracion.= $aduracion[1].' Meses '; $mes+=$aduracion[1]; }
				if($aduracion[2]>0){ $duracion.= $aduracion[2].' Dias '; $dia+=$aduracion[2]; }
				if($aduracion[3]>0){ $duracion.= $aduracion[3].' Horas '; $hora+=$aduracion[3]; }
				if($aduracion[4]>0){ $duracion.= $aduracion[4].' Minutos '; $minuto+=$aduracion[4]; }
				if($aduracion[5]>0){ $duracion.= $aduracion[5].' Segundos '; $segundo+=$aduracion[5]; }

				$addp.=$aduracion[0].'Y'; 
				$addp.=$aduracion[1].'M'; 
				$addp.=$aduracion[2].'D'; 

				$addt.=$aduracion[3].'H'; 
				$addt.=$aduracion[4].'M'; 
				$addt.=$aduracion[5].'S'; 


				$cadtiempo=$addp.$addt;

				if($cadtiempo!='PT'){
					$fecha = new DateTime($row2['dta']);
					$fecha->add(new DateInterval($cadtiempo));
					$row2['dt']=$fecha;
				}else{
					$row2['dt']='';
				}

				$addp='P'; $addt='T'; $fecha='';
				$row2['duracion']=$duracion;
				$etapas[]=$row2;

			}
				$addp='P'; $addt='T';
				if($ano>0){ $addp.=$ano.'Y'; }
				if($mes>0){ $addp.=$mes.'M'; }
				if($dia>0){ $addp.=$dia.'D'; }

				if($hora>0){ $addt.=$hora.'H'; }
				if($minuto>0){ $addt.=$minuto.'M'; }
				if($segundo>0){ $addt.=$segundo.'S'; }

				$cadtiempo=$addp.$addt;

				if($cadtiempo!='PT'){
					$fecha = new DateTime('2000-01-01');
					$fecha->add(new DateInterval($cadtiempo));
					$etapas['dt']=$fecha;
				}else{
					$etapas['dt']='';
				}
//var_dump($etapas);
		return $etapas;
		}
	}

	public function etapas($id)
	{
		if($id==''){
			$id='999999999';
		}
		$this->load->database();
		$query=$this->db->query("SELECT id,etapa,duracion_eta FROM mrp_etapas WHERE id_producto='$id' order by id; ");
		
		if ($query->num_rows() > 0){
			$arr=array();
			$ano=0; $mes=0; $dia=0; $hora=0; $minuto=0; $segundo=0;  $addp='P'; $addt='T';
			$duracion='';
			$cadtiempo='';
			foreach ($query->result_array() as $row2){

				
				$row2['cadti']=$cadtiempo;
				$arr[]=$row2;
				
				$aduracion=explode(':', $row2['duracion_eta']);
				if($aduracion[0]>0){ $duracion.= $aduracion[0].' Años '; $ano+=$aduracion[0]; }
				if($aduracion[1]>0){ $duracion.= $aduracion[1].' Meses '; $mes+=$aduracion[1]; }
				if($aduracion[2]>0){ $duracion.= $aduracion[2].' Dias '; $dia+=$aduracion[2]; }
				if($aduracion[3]>0){ $duracion.= $aduracion[3].' Horas '; $hora+=$aduracion[3]; }
				if($aduracion[4]>0){ $duracion.= $aduracion[4].' Minutos '; $minuto+=$aduracion[4]; }
				if($aduracion[5]>0){ $duracion.= $aduracion[5].' Segundos '; $segundo+=$aduracion[5]; }

				$addp.=$aduracion[0].'Y'; 
				$addp.=$aduracion[1].'M'; 
				$addp.=$aduracion[2].'D'; 

				$addt.=$aduracion[3].'H'; 
				$addt.=$aduracion[4].'M'; 
				$addt.=$aduracion[5].'S'; 


				$addp='P'; $addt='T';
				if($ano>0){ $addp.=$ano.'Y'; }
				if($mes>0){ $addp.=$mes.'M'; }
				if($dia>0){ $addp.=$dia.'D'; }

				if($hora>0){ $addt.=$hora.'H'; }
				if($minuto>0){ $addt.=$minuto.'M'; }
				if($segundo>0){ $addt.=$segundo.'S'; }

				$cadtiempo=$addp.$addt;
				
				//$arr[]=$cadtiempo;
				/*if($cadtiempo!='PT'){
					$fecha = new DateTime($row2['dta']);
					$fecha->add(new DateInterval($cadtiempo));
					$row2['dt']=$fecha;
				}else{
					$row2['dt']='';
				}*/
			}
				
			return $arr;
		}

	}
	
	//////////
	
	////////////////////////////////////////////////
	public function giveproductosOrden($id)
	{
		$this->load->database();
		$query=$this->db->query('select op.idProducto,op.idUnidad,op.cantidad,u.compuesto from mrp_detalle_orden_produccion op inner join mrp_unidades u on u.idUni=op.idUnidad  where op.idOrdPro='.$id);
		return $query->result();		
	}
	
	
	public function createorder($productos,$ordenes,$elaboro,$sucursal,$almacen,$fecha_inicio,$x,$fecha_fin)
	{	
		//var_dump($ordenes);
		$this->load->database();
		$this->db->trans_start();
		date_default_timezone_set('America/Mexico_City'); 
		$horadia = date("Y-m-d H:i");

		$query = $this->db->query("INSERT INTO mrp_orden_produccion (id,fecha, generada_por, idSuc, estatus, idAlmacen,fecha_inicio,fecha_fin) VALUES (NULL, '".$horadia."', '".$elaboro."', '".$sucursal."','".$x."', '".$almacen."','".$fecha_inicio."','".$fecha_fin."');");
		//echo "INSERT INTO mrp_orden_produccion (id, fecha, generada_por, idSuc, estatus, idAlmacen,fecha_inicio,fecha_fin) VALUES (NULL, '".$horadia."', '".$elaboro."', '".$sucursal."','".$x."', '".$almacen."','".$fecha_inicio."','".$fecha_fin."');";	

		$idOrdenProduccion = mysql_insert_id();
		//var_dump($idOrdenProduccion);
		foreach($productos as $idProducto=>$prod)
		{
		list($cantidad,$unidad,$textounidad)=explode("_",$prod);
		$query = $this->db->query("INSERT INTO mrp_detalle_orden_produccion (id,idProducto,idUnidad,idOrdPro,cantidad) VALUES (NULL, '".$idProducto."', '".$unidad."', '".$idOrdenProduccion."', '".$cantidad."');");
		//echo "INSERT INTO mrp_detalle_orden_produccion (id,idProducto,idUnidad,idOrdPro,cantidad) VALUES (NULL, '".$idProducto."', '".$unidad."', '".$idOrdenProduccion."', '".$cantidad."');";
		}
	
		$proveedores=array(); /// ordenes de compra
		
		foreach($ordenes as $orden)
		{		
			list($producto,$proveedor,$unidad,$costo,$cantidad)=explode("_",$orden);
			
			if(!array_key_exists($proveedor,$proveedores))
			{
				$query = $this->db->query("INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor,idOrdPro,idAlmacen) values ('".$horadia."', '".$elaboro."', '".$sucursal."', '', ".$proveedor.",".$idOrdenProduccion.", '".$almacen."')"); 
				//echo "INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor,idOrdPro,idAlmacen) values ('".$horadia."', '".$elaboro."', '".$sucursal."', '', ".$proveedor.",".$idOrdenProduccion.", '".$almacen."')";    
				$idOrden = mysql_insert_id();
				$proveedores[$proveedor]=$idOrden; 			
			}
			else
			{
				$idOrden=$proveedores[$proveedor];
			}
			
			$query = $this->db->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$cantidad.', '.$unidad.', '.$producto.', '.str_replace(",","",str_replace("$","",$costo)).')');	
			//echo 'INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$cantidad.', '.$unidad.', '.$producto.', '.str_replace(",","",str_replace("$","",$costo)).')';		
			
		}  ///           
		 
		$this->db->trans_complete();
	}

	public function createordercompra($productos,$ordenes,$elaboro,$sucursal,$almacen,$fecha_inicio,$x,$fecha_fin)
	{	
		//var_dump($ordenes);
		$this->load->database();
		$this->db->trans_start();
		date_default_timezone_set('America/Mexico_City'); 
		$horadia = date("Y-m-d H:i");

	/*	$query = $this->db->query("INSERT INTO mrp_orden_produccion (id,fecha, generada_por, idSuc, estatus, idAlmacen,fecha_inicio,fecha_fin) VALUES (NULL, '".$horadia."', '".$elaboro."', '".$sucursal."','".$x."', '".$almacen."','".$fecha_inicio."','".$fecha_fin."');");
		//echo "INSERT INTO mrp_orden_produccion (id, fecha, generada_por, idSuc, estatus, idAlmacen,fecha_inicio,fecha_fin) VALUES (NULL, '".$horadia."', '".$elaboro."', '".$sucursal."','".$x."', '".$almacen."','".$fecha_inicio."','".$fecha_fin."');";	

		$idOrdenProduccion = mysql_insert_id();
		//var_dump($idOrdenProduccion);
		foreach($productos as $idProducto=>$prod)
		{
		list($cantidad,$unidad,$textounidad)=explode("_",$prod);
		$query = $this->db->query("INSERT INTO mrp_detalle_orden_produccion (id,idProducto,idUnidad,idOrdPro,cantidad) VALUES (NULL, '".$idProducto."', '".$unidad."', '".$idOrdenProduccion."', '".$cantidad."');");
		//echo "INSERT INTO mrp_detalle_orden_produccion (id,idProducto,idUnidad,idOrdPro,cantidad) VALUES (NULL, '".$idProducto."', '".$unidad."', '".$idOrdenProduccion."', '".$cantidad."');";
		}*/
	
		$proveedores=array(); /// ordenes de compra
		
		foreach($ordenes as $orden)
		{		
			list($producto,$proveedor,$unidad,$costo,$cantidad)=explode("_",$orden);
			
			if(!array_key_exists($proveedor,$proveedores))
			{
				$query = $this->db->query("INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor,idAlmacen) values ('".$horadia."', '".$elaboro."', '".$sucursal."', '', ".$proveedor.",'".$almacen."')"); 
				//echo "INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, fecha_entrega, idProveedor,idOrdPro,idAlmacen) values ('".$horadia."', '".$elaboro."', '".$sucursal."', '', ".$proveedor.",".$idOrdenProduccion.", '".$almacen."')";    
				$idOrden = mysql_insert_id();
				$proveedores[$proveedor]=$idOrden; 			
			}
			else
			{
				$idOrden=$proveedores[$proveedor];
			}
			
			$query = $this->db->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$cantidad.', '.$unidad.', '.$producto.', '.str_replace(",","",str_replace("$","",$costo)).')');	
			//echo 'INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$cantidad.', '.$unidad.', '.$producto.', '.str_replace(",","",str_replace("$","",$costo)).')';		
			
		}  ///           
		 
		$this->db->trans_complete();
	}	
	
	public function cotizacion($idProducto,$idProveedor)
	{
		$this->load->database();
		$query=$this->db->query('SELECT u.idUni as idunidad,pr.idPrv,pr.razon_social,u.compuesto,pp.costo,p.nombre,p.idProducto FROM mrp_producto_proveedor pp,mrp_proveedor pr , mrp_unidades u ,mrp_producto p
WHERE pp.idPrv=pr.idPrv and u.idUni=pp.idUni and p.idProducto=pp.idProducto and pp.idProducto='.$idProducto.' and pr.idPrv='.$idProveedor);
		return $query->row();	
	}
	
	public function mejorproveedor($idProducto)
	{
	$this->load->database(); 
	$query=$this->db->query('SELECT pr.razon_social,oc.idProveedor,u.compuesto,poc.idUnidad,p.nombre ,poc.idProducto,c.fecha,dc.costo,u.conversion 
FROM mrp_detalle_compra dc,mrp_compra c,mrp_producto_orden_compra  poc ,mrp_producto p,mrp_unidades u, mrp_orden_compra oc ,mrp_proveedor pr
WHERE dc.idCompra=c.id and dc.idProductoOrdenCompra=poc.idPrOr and p.idProducto=poc.idProducto and u.idUni=poc.idUnidad 
and oc.idOrd=poc.idOrden and oc.idProveedor=pr.idPrv  and p.idProducto='.$idProducto.'  order by c.fecha desc,dc.costo asc limit 1');
		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
	$query=$this->db->query('SELECT pr.idPrv idProveedor,pr.razon_social,u.compuesto,pp.costo,p.nombre,p.idProducto,u.idUni idUnidad,pp.costo
FROM mrp_producto_proveedor pp,mrp_proveedor pr , mrp_unidades u ,mrp_producto p
WHERE pp.idPrv=pr.idPrv and u.idUni=pp.idUni and p.idProducto=pp.idProducto and pp.idProducto='.$idProducto.'  
Order by pp.costo asc limit 1');
return $query->row();	
		}
	}
	
	 public function listaproveedores($idProducto)
	{
		$this->load->database();
		$query=$this->db->query('SELECT p.idPrv,p.razon_social,pp.costo,u.compuesto,pr.nombre 
		FROM mrp_proveedor p, mrp_producto_proveedor pp,mrp_unidades u,mrp_producto pr where 
		pp.idPrv=p.idPrv and u.idUni=pp.idUni and pr.idProducto=pp.idProducto and pp.idProducto='.$idProducto.' GROUP BY p.idPrv');
		
		return $query->result();		
	}
	
	public function existencia($id,$sucursal,$almacen)
	{
		$this->load->database();
		$query=$this->db->query('SELECT s.cantidad-s.ocupados cantidad FROM mrp_stock s WHERE s.idProducto='.$id.' and idAlmacen='.$almacen);
		if ($query->num_rows() > 0)
		{
		$row=$query->row();
		return $row->cantidad;
		}
		else return 0;
	}
	
	public function datosproducto($id)
	{
		$this->load->database();
		$query=$this->db->query('SELECT p.idProducto,p.codigo,p.nombre,p.maximo,p.minimo FROM mrp_producto p WHERE p.idProducto='.$id);
		
		
		return $query->row();
	}
	public function esMaterial($idp){
		$this->load->database();
		$query = $this->db->query('SELECT tipo_producto from mrp_producto where idProducto='.$idp);
		return $query->row();
	}
	public function conversion($unidad)
	{
		$this->load->database();
		$query=$this->db->query('SELECT conversion FROM mrp_unidades WHERE compuesto="'.$unidad.'"');
		
		
		return $query->row();
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function unidades($idProducto)//4,9,13
	{			
		$this->load->database();
		
		$query0 = $this->db->query('SELECT idUni FROM mrp_producto_proveedor WHERE idProducto='.$idProducto); 
		
		if ($query0->num_rows() > 0)
		{
		
		$row0 = $query0->row();
		$id=$row0->idUni;
		
		$query = $this->db->query('SELECT idUni,compuesto FROM mrp_unidades WHERE idUni='.$id); 
		$row = $query->row();
		
				 $hijos=$this->damehijos($id,true);
				 $padres=$this->damepadres($id,true);
				 $result = array_merge((array)$hijos, (array)$padres);
				 $result[]=$row->idUni."_".$row->compuesto;
				 return ($result);
		}
		else { 
		 //$result[]="1_Unidad"; 
		 $queryx = $this->db->query('SELECT idUni, compuesto FROM mrp_unidades');
		   foreach ($queryx->result() as $rowx){
		   		$result[] = $rowx->idUni."_".$rowx->compuesto;
  			}
		 return $result; 
		}
	}
	
	///////////////////////////////
	public function damehijos($id,$raiz=false)
	{
		$this->load->database();
		if($raiz){$r=',(SELECT compuesto FROM mrp_unidades WHERE idUni='.$id.') as raiz';}else{$r='';}
		$query = $this->db->query('select idUni,compuesto,conversion,unidad '.$r.' from mrp_unidades where idUni=(
		SELECT unidad FROM mrp_unidades WHERE idUni='.$id.')'); 
		
		$hijosindemiatos=array();
		foreach ($query->result() as $row)
		{
			if($row->idUni!=$id)
			{
				$hijosindemiatos[]=$row->idUni."_".$row->compuesto;
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)$this->damehijos($row->idUni));
				
			}
		}//foreach
		$hijosindemiatos=array_unique($hijosindemiatos);
		
		return $hijosindemiatos;
		
	}
	////////////////////////////////////////////////
	public function termina($idp,$cantidad,$cantidadp,$almacen,$idProducto,$x){
		$this->load->database();
		switch($x){
			case 1:
		//	echo "Update mrp_stock set cantidad=cantidad-".($cantidad*$cantidadp)." where  idAlmacen=".$almacen." and idProducto=".$idp;
		//	echo "Update mrp_stock set ocupados=ocupados-".($cantidad*$cantidadp)." where  idAlmacen=".$almacen." and idProducto=".$idp;
			$query=$this->db->query("Update mrp_stock set cantidad=cantidad-".($cantidad*$cantidadp)." where  idAlmacen=".$almacen." and idProducto=".$idp);
			$query=$this->db->query("Update mrp_stock set ocupados=ocupados-".($cantidad*$cantidadp)." where  idAlmacen=".$almacen." and idProducto=".$idp);
			break;
			case 2:
				$query1=$this->db->query("select s.cantidad cantidad from mrp_stock s where  s.idProducto=".$idProducto." and  s.idAlmacen=".$almacen);
				if($query1->num_rows()>0){
			//	echo "Update mrp_stock set cantidad=cantidad+".$cantidad." where  idAlmacen=".$almacen." and idProducto=".$idProducto;
					$query=$this->db->query("Update mrp_stock set cantidad=cantidad+".$cantidad." where  idAlmacen=".$almacen." and idProducto=".$idProducto);

				}else{
			//		echo "insert into mrp_stock(idProducto,cantidad,idAlmacen,idUnidad) values(".$idProducto.",".$cantidad.",".$almacen.")";
				//$query=$this->db->query("insert into mrp_stock values('',".$idProducto.",".$cantidad.",".$almacen.",1)");
				$query=$this->db->query("insert into mrp_stock(idProducto,cantidad,idAlmacen,idUnidad) values(".$idProducto.",".$cantidad.",".$almacen.",1)");
			    }
			break;    
		}
	    return;
	} 
	//////////////////////////////////////////////////////////
	public function cancelado($idp,$cantidad,$cantidadp,$almacen){

		//echo "Update mrp_stock set ocupados=ocupados-".($cantidad*$cantidadp)." where  idAlmacen=".$almacen." and idProducto=".$idp;
		$this->load->database();
		//echo "Update mrp_stock set ocupados=ocupados-".($cantidad*$cantidadp)." where  idAlmacen=".$almacen." and idProducto=".$idp;
		$query=$this->db->query("Update mrp_stock set ocupados=ocupados-".($cantidad*$cantidadp)." where  idAlmacen=".$almacen." and idProducto=".$idp);

		return;

	}
	///////////////////////////////////////////
	public function cambiaestatus($id,$estatus)
	{
		$this->load->database();
		$query = $this->db->query('Update mrp_orden_produccion set estatus='.$estatus.' WHERE id='.$id);
		
		//var_dump('Update mrp_orden_produccion set estatus='.$estatus.' WHERE id='.$id);
		
		return 1;	
	}
	/////////////////////////////////////////
	public function damepadres($id,$raiz=false)
	{
		$this->load->database();
		$query = $this->db->query('SELECT idUni,unidad,compuesto FROM mrp_unidades WHERE unidad='.$id); 
		$hijosindemiatos=array();
		foreach ($query->result() as $row)
		{
			if($row->idUni!=$id)
			{
				$hijosindemiatos[]=$row->idUni."_".$row->compuesto;
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)$this->damepadres($row->idUni));
				
			}
		}//foreach
		$hijosindemiatos=array_unique($hijosindemiatos);
		
		return $hijosindemiatos;
	}
	
	///
}