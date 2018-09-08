<?php
include("gridClass.php");
$filtro=1;if(strlen($_POST["filtro"])>3){$filtro=$_POST["filtro"];}
$elimina=0;
if(is_numeric($_GET["elimina"])){$elimina=$_GET["elimina"];}
if(is_numeric($_POST["elimina"])){$elimina=$_POST["elimina"];}
/*PARAMETRIZAR*/
$grid=new Grid("idTal",$_POST["paginacion"],$filtro,$elimina);
$consulta="select t.idTal,t.talla from mrp_talla t where ".$filtro." order by t.talla";
$campos=array("ID","Talla");
/*END PARAMETRIZAR*/
$mensaje="";
switch($_POST["funcion"])
{
	case "elimina": $mensaje=$grid->elimina($_POST["id"],'mrp_talla','idTal',
	array(
		 'Productos'=>'mrp_producto-talla'
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
		<script type="text/javascript" src="js/talla.js"></script>	
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