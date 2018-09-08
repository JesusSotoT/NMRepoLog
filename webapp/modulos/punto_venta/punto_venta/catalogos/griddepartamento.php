<?php
include("gridClass.php");
$filtro=1;if(strlen($_POST["filtro"])>3){$filtro=$_POST["filtro"];}
$elimina=0;
if(is_numeric($_GET["elimina"])){$elimina=$_GET["elimina"];}
if(is_numeric($_POST["elimina"])){$elimina=$_POST["elimina"];}
/*PARAMETRIZAR*/
$grid=new Grid("idDep",$_POST["paginacion"],$filtro,$elimina);
$consulta="select p.idDep,p.nombre from mrp_departamento p where ".$filtro." order by p.nombre";
$campos=array("ID","Nombre");
/*END PARAMETRIZAR*/
$mensaje="";
switch($_POST["funcion"])
{
	case "elimina": $mensaje=$grid->elimina($_POST["id"],'mrp_departamento','idDep',
	array(
		 'Familias'=>'mrp_familia-idDep'
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
		<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
       <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script> 
		<script type="text/javascript" src="js/departamento.js"></script>	
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