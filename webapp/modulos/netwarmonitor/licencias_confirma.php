<?php
	//ini_set("display_errors",1);
	include('../../netwarelog/webconfig.php');
	include "../../netwarelog/catalog/conexionbd.php";
	$conexion->cerrar();

	include "../../modulos/hazbizne/clases.php";
	$netwarstorep = new clnetwarstore_p();
	$licenses = $netwarstorep->get_licencias_distintas();

?>

<!DOCTYPE html>
<html>
	<head>
    	<meta charset="UTF-8">
        <?php include('../../netwarelog/design/css.php');?>
        <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
	</head>
	<body>
		<form action="licenciasdistribuidor.php" method="post">
    		<table>
	    		<?php
				foreach ($licenses as $key => $licencia) {
					if (!empty($_POST[$licencia["licenciaId"]])){
						$licencias_entregadas = $netwarstorep->asignar_licencias($licencia["licenciaId"], $_POST[$licencia["licenciaId"]], $_POST["distribuidor"]);
						echo $_POST[$licencia["licenciaId"]] . " licencia(s) de " . $licencia["appname"] . "(" . $licencia["licenciaId"] . ") asignada(s) exitosamente. <BR>";
					}
				}
				?>
			</table>
			<input type="submit" value="Asignar más licencias">
			
		</form>
	</body>
</html>
