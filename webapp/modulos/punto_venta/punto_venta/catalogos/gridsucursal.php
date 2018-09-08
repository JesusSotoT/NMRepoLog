<?php
include("gridClass.php");
$filtro=1;if(strlen($_POST["filtro"])>3){$filtro=$_POST["filtro"];}
$elimina=0;
if(is_numeric($_GET["elimina"])){$elimina=$_GET["elimina"];}
if(is_numeric($_POST["elimina"])){$elimina=$_POST["elimina"];}
/*PARAMETRIZAR*/
$grid=new Grid("idSuc",$_POST["paginacion"],$filtro,$elimina);

$consulta="select a.idSuc,a.nombre,a.direccion,e.estado,m.municipio,a.cp,a.tel_contacto,a.contacto,aa.nombre as almacen from  mrp_sucursal a left join estados e on e.idestado=a.idEstado left join municipios m on m.idmunicipio=a.idmunicipio inner join almacen aa on aa.idAlmacen=a.idAlmacen
  where ".$filtro." order by a.nombre";
  
// echo $consulta; 
$campos=array("ID","Nombre","Dirección","Estado","Municipio","Código postal","Télefono contacto","Contacto","Almacen");

$mensaje="";
switch($_POST["funcion"])
{
	case "elimina": $mensaje=$grid->elimina($_POST["id"],'mrp_sucursal','idSuc',
	array(
		 'Ventas'=>'venta-idSucursal',
		 'Reporte de ventas'=>'inicio_caja-idSucursal',
		 'Reporte de movimientos de mercancia'=>'ingreso_mercancia-idSuc',
		 'Almacenes'=>'almacen_sucursal-idSucursal',
		 'Usuarios'=>'administracion_usuarios-idSuc'	
	));
	break;
}
/*END PARAMETRIZAR*/
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
		<script type="text/javascript" src="js/sucursal.js"></script>	
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