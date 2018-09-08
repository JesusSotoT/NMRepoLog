<?php
	//ini_set("display_errors",1);
	include('../../netwarelog/webconfig.php');
	include "../../netwarelog/catalog/conexionbd.php";
	$conexion->cerrar();

	include "../../modulos/hazbizne/clases.php";
	$netwarstorep = new clnetwarstore_p();
	$licenses = $netwarstorep->get_licencias_distintas();
	$nombre_distribuidor = $netwarstorep->get_nombre_distribuidor($_POST["distribuidor"]);
?>

<!DOCTYPE html>
<html>
	<head>
    	<meta charset="UTF-8">
        <?php include('../../netwarelog/design/css.php');?>
        <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
		<style type="text/css">
			.back_button {
				display:block;
				width:100px;
				height:30px;
				text-align:center;
				background-color:yellow;
				border:1px solid #000000;
			}
		</style>
	</head>
	<body>
		<form action="licencias_confirma.php" method="post">
    		<table>
	    		<tr>
	    			<td>ID Distribuidor:</td><td><input type="text" name="distribuidor" value="<?php echo $_POST["distribuidor"]?>" readonly></input></td>
	    		<tr>
	    		<tr>
	    			<td>Nombre Distribuidor:</td><td><input type="text" name="n_distribuidor" value="<?php echo $nombre_distribuidor?>" readonly></input></td>
	    		<tr>
	    		
	    		<?php
				foreach ($licenses as $key => $licencia) {
					if (!empty($_POST[$licencia["licenciaId"]])){
						?>
						<tr>
							<td><?php echo $licencia["appname"] . "(" . $licencia["licenciaId"] . "): "?></td><td><input type="text" name="<?php echo $licencia["licenciaId"]?>" value="<?php echo $_POST[$licencia["licenciaId"]]?>" readonly></input></td>
						</tr>
						<?php
					}
				}
				?>
			</table>
			<input type="button" value="Corregir" onclick="javascript:history.back(1)">
			<input type="submit" value="Confirmar">
			
		</form>
	</body>
</html>
