<?php
include("gridClass.php");

$filtro=1;if(strlen($_POST["filtro"])>3){$filtro=$_POST["filtro"];}
$elimina=0;if(is_numeric($_POST["elimina"])){$elimina=$_POST["elimina"];}


$grid=new Grid($_POST["paginacion"],$filtro,"Productos",$elimina);

/*
$consulta="SELECT 
		p.idProducto,
		p.codigo,
		p.nombre,
		p.maximo,
		p.minimo,
		sum(s.cantidad)  as cantidad ,
		s.idAlmacen almacen
		FROM mrp_stock s right join mrp_producto p on p.idProducto=s.idProducto
		group by  p.nombre 
		order by p.nombre,cantidad";

$campos=array("ID","Codigo","Nombre","Maximo","Minimo","Cantidad");
*/
$consulta="select idProducto,p.codigo,p.nombre from mrp_producto p where ".$filtro." order by nombre";
$campos=array("ID","Nombre","Producto");


$grid->query($consulta);
$grid->setHeader($campos);
if(is_numeric($_POST["page"]))
{
	echo $grid->render($_POST["page"]);;
}
else {?>	

<html>
<head>	
<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />	
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="grid.js"></script>	
</head>
<body>
	
	<div height="20">
                <div class="descripcion">Producto</div>
                <br>
                <input class='button' type='button' onclick='abrir(1,0,0)' value='Agregar registro' /> 
                <input class='button' type='button' onclick='paginacionGridProductos(0,1,0);' value='Modificar registro' /> 
                <input class='button' type='button' onclick='paginacionGridProductos(0,1,1);' value='Eliminar registro' />                 
    </div>
	<div id="grid_producto"><?php echo $grid->render(1); ?></div>
</body>
</html>
<?php } ?>
