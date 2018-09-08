<?php
include("gridClass.php");
$filtro=1;if(strlen(@$_POST["filtro"])>3){$filtro=@$_POST["filtro"];}
$elimina=0;
if(is_numeric(@$_GET["elimina"])){$elimina=@$_GET["elimina"];}
if(is_numeric(@$_POST["elimina"])){$elimina=@$_POST["elimina"];}
/*PARAMETRIZAR*/
$grid=new Grid("id",@$_POST["paginacion"],$filtro,$elimina);
$consulta="select c.id,c.rfc,c.nombre,c.nombretienda,c.direccion,c.colonia,c.cp,e.estado,m.municipio, c.email,c.celular,c.limite_credito,c.dias_credito  from comun_cliente c  left join estados e on e.idestado=c.idEstado left join municipios m on m.idmunicipio=c.idmunicipio  where ".$filtro." order by c.nombre";
$campos=array("ID","RFC","Nombre","Tienda","Dirección","Colonia","Código postal","Estado","Municipio","Email","Celular","Limite crédito","Dias crédito");
/*END PARAMETRIZAR*/
$mensaje="";
switch(@$_POST["funcion"])
{
	case "elimina": $mensaje=$grid->elimina(@$_POST["id"],'comun_cliente','id',
	array(
		 'Ventas'=>'venta-idCliente'
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
		
		<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/view.css">
		<link type="text/css" rel="stylesheet" title="estilo" href="../../../netwarelog/catalog/css/estilo.css">
        <?php include('../../../netwarelog/design/css.php');?>
        <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

       <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script> 
		<script type="text/javascript" src="js/cliente.js"></script>	
		 <script  type="text/javascript" src="js/jTPS.js"></script>
    <link rel="stylesheet" type="text/css" href="../reportes/css/csstest.css">
    <!-- <link rel="stylesheet" type="text/css" media="all" href="../reportes/css/styles.css">
    		<script type="text/javascript" src="../js/tablesort.min.js"></script> -->
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
			<script type="text/javascript"> 
		$(document).ready(function() {

				$('#datos').jTPS({
					perPages : ['TODO'],
					scrollStep : 1,
					scrollDelay : 30,
					clickCallback : function() {
					}
				});

			}); 
</script>      
	</body>
</html>
<?php } ?>