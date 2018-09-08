<?php
 include("../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
    $idalmacen=$_REQUEST['almacen'];
	//$_SESSION['idalmacen']=$idalmacen;
		
	
		$sql=$conection->query('select  mrp_producto.idProducto id,mrp_producto.nombre,
CASE WHEN mrp_stock.cantidad  IS NOT NULL 
       THEN mrp_stock.cantidad 
       ELSE 0 END AS cantidad from  mrp_stock right join mrp_producto  on mrp_producto.idProducto=mrp_stock.idProducto		
 WHERE vendible = 1 and idAlmacen='.$idalmacen.' order by nombre');
			
		// $select_pro='<select id="producto" style="width: 100%;"></select>';
		if($sql->num_rows>0){
		@$select_pro.='<option value="">-Seleccione un Producto-</option>';
		 while($r=$sql->fetch_array(MYSQLI_ASSOC))
		{
		$select_pro.='<option value="'.$r['id'].'">'.($r['nombre']."->".$r['cantidad']).'</option>';
		 }
		}else{
	$select_pro.='<option>No hay productos en el Almacen</option>';	
		}
		 // $select_pro.='</select>';	
// // 		
	echo $select_pro;	
	$conection->close();
	//Funcion que busca un departament
	 
?>