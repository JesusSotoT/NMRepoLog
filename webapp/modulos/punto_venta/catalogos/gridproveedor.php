<?php
include("gridClass.php");
$filtro=1;if(strlen(@$_POST["filtro"])>3){$filtro=@$_POST["filtro"];}
$elimina=0;
if(is_numeric(@$_GET["elimina"])){$elimina=@$_GET["elimina"];}
if(is_numeric(@$_POST["elimina"])){$elimina=@$_POST["elimina"];}
/*PARAMETRIZAR*/
$grid=new Grid("idPrv",@$_POST["paginacion"],$filtro,$elimina);
$consulta="select c.idPrv,c.rfc,c.razon_social,t.tipo,c.domicilio,e.estado,m.municipio,c.telefono,c.email,c.web from mrp_proveedor c  left join estados e on e.idestado=c.idEstado left join municipios m on m.idmunicipio=c.idmunicipio  left join tipo_proveedor t on t.idtipo=c.idtipo where ".$filtro." order by c.razon_social";
$campos=array("ID","RFC","Razón social","Tipo","Domicilio","Estado","Municipio","Teléfono","Email","Página web");
/*END PARAMETRIZAR*/
$mensaje="";
switch(@$_POST["funcion"])
{
	case "elimina": $mensaje=$grid->elimina(@$_POST["id"],'mrp_proveedor','idPrv',
	array(
			'Productos'=>'mrp_producto_proveedor-idPrv',
			'Ordenes de compra'=>'mrp_orden_compra-idProveedor'
));
	break;
}

$grid->query(stripslashes($consulta));
$grid->setHeader($campos);
if(is_numeric(@$_POST["page"]))
{echo $grid->render(@$_POST["page"]);}
else{?>	
<html>
	<head>
		<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/vsiew.css">
		<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/estilo.css">
        <?php include('../../../netwarelog/design/css.php');?>
        <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
        <script type="text/javascript" src="../../../libraries/jquery-1.9.1.js"></script> 
	    <script type="text/javascript" src="js/proveedor.js"></script>
        <link rel="stylesheet" type="text/css" href="../reportes/css/csstest.css">
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