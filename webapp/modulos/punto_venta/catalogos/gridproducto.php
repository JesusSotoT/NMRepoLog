<?php
	include("gridClass.php");
	$filtro=1; if(strlen(@$_POST["filtro"])>3){$filtro=@$_POST["filtro"];}
	$elimina=0;
	if(is_numeric(@$_GET["elimina"])){$elimina=@$_GET["elimina"];}
	if(is_numeric(@$_POST["elimina"])){$elimina=@$_POST["elimina"];}

	/*PARAMETRIZAR*/
	$grid=new Grid("idProducto",@$_POST["paginacion"],$filtro,$elimina);
	$consulta="SELECT
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
		where estatus=1 and ".$filtro." order by p.nombre asc";
	$campos=array("ID","Código","Nombre","Departamento","Familia","Linea","Precio");
	$mensaje="";

	switch(@$_POST["funcion"]) {
		case "elimina": $mensaje=$grid->elimina(
			@$_POST["id"],'mrp_producto','idProducto', 
			array(
				'Ventas'=>'venta_producto-idProducto',
				'Inventario'=>'mrp_stock-idProducto',
				'Proveedores'=>'mrp_producto_proveedor-idProducto',
				'Ordenes de compra'=>'mrp_producto_orden_compra-idProducto',
				'Otros productos(esta compuesto por otros productos)'=>'mrp_producto_material-idMaterial',
				'Otros productos( forma parte de otro producto )'=>'mrp_producto_material-idProducto',
				'Reportes movimientos mercancia'=>'movimientos_mercancia-idProducto'
			));
		break;
	}
	/*END PARAMETRIZAR*/

	$grid->query(stripslashes($consulta));
	$grid->setHeader($campos);
	if(is_numeric(@$_POST["page"])) {
		echo $grid->render(@$_POST["page"]);
	} else{ 
		?>
		<html>
			<head>
				<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/view.css">
				<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/estilo.css">
				<?php include('../../../netwarelog/design/css.php');?>
				<LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
				<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
				<script type="text/javascript" src="js/producto.js"></script>
				<script  type="text/javascript" src="js/jTPS.js"></script>

				<link rel="stylesheet" type="text/css" href="../reportes/css/csstest.css">
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

				<script type="text/javascript">
					<?php
						if(strlen($mensaje)>5) {
							?>
							alert('<?php echo $mensaje;?>');
							<?php
						}
					?>
				</script>
			</head>

			<body>
				<div id="grid">
					<?php echo $grid->render(1); ?>
				</div>

				<script type="text/javascript">
					$(document).ready(function() {
						$('#datos').jTPS({
							perPages : ['TODO'],
							scrollStep : 1,
							scrollDelay : 30,
							clickCallback : function() { 	}
						});
					});
				</script>
			</body>
		</html>
	<?php }
?>