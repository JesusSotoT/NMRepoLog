<?php
include("gridClass.php");
$filtro=1;if(strlen(@$_POST["filtro"])>3){$filtro=@$_POST["filtro"];}
$elimina=0;
if(is_numeric(@$_GET["elimina"])){$elimina=@$_GET["elimina"];}
if(is_numeric(@$_POST["elimina"])){$elimina=@$_POST["elimina"];}
/*PARAMETRIZAR*/
$grid=new Grid("idCol",@$_POST["paginacion"],$filtro,$elimina);
$consulta="select c.idCol,c.color  from mrp_color c where ".$filtro." order by c.color";
$campos=array("ID","Color");
/*END PARAMETRIZAR*/
$mensaje="";
switch(@$_POST["funcion"])
{
	case "elimina": $mensaje=$grid->elimina(@$_POST["id"],'mrp_color','idCol',
	array(
		 'Productos'=>'mrp_producto-color'
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
		<script type="text/javascript" src="js/color.js"></script>	
		<script  type="text/javascript" src="js/jTPS.js"></script>
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