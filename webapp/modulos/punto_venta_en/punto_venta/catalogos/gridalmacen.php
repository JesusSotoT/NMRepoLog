<?php
include("gridClass.php");
$filtro=1;if(strlen($_POST["filtro"])>3){$filtro=$_POST["filtro"];}
$elimina=0;
if(is_numeric($_GET["elimina"])){$elimina=$_GET["elimina"];}
if(is_numeric($_POST["elimina"])){$elimina=$_POST["elimina"];}
/*PARAMETRIZAR*/
$grid=new Grid("idAlmacen",$_POST["paginacion"],$filtro,$elimina);
$consulta="select a.idAlmacen,a.nombre,a.direccion,e.estado,m.municipio,cp,tel_contacto,contacto  from almacen a  left join estados e on e.idestado=a.idEstado left join municipios m on m.idmunicipio=a.idmunicipio where ".$filtro." order by a.nombre";
$campos=array("ID","Nombre","Dirección","Estado","Municipio","Código postal","Teléfono contacto","Contacto");
/*END PARAMETRIZAR*/
$mensaje="";
switch($_POST["funcion"])
{
	case "elimina": $mensaje=$grid->elimina($_POST["id"],'almacen','idAlmacen',
	array(
		 'Sucursales'=>'mrp_sucursal-idAlmacen',
		 'Sucursales'=>'almacen_sucursal-idAlmacen',
		 'Inventario'=>'mrp_stock-idAlmacen',
		 'Ordenes de producción'=>'mrp_orden_produccion-idAlmacen',
		 'Ordenes de compra'=>'mrp_orden_compra-idAlmacen',
		 'Reporte movimientos de mercancia'=>'movimientos_mercancia-idAlmacenOrigen',
		 'Reporte movimientos de mercancia'=>'movimientos_mercancia-idAlmacenDestino'
	));
	break;
}

$grid->query($consulta);
$grid->setHeader($campos);
if(is_numeric($_POST["page"]))
{echo $grid->render($_POST["page"]);}
else{?>	
<html>
	<head>
		
		<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/view.css">
		<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/estilo.css">

       <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script> 
		<script type="text/javascript" src="js/almacen.js"></script>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript">
		<?php  
			if(strlen($mensaje)>5)
			{
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
	</body>
</html>
<?php } ?>