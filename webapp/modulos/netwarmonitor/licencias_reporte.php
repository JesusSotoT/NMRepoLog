<?php
	//ini_set("display_errors",1);
	include('../../netwarelog/webconfig.php');
	include "../../netwarelog/catalog/conexionbd.php";
	$conexion->cerrar();

	include "../../modulos/hazbizne/clases.php";
	$netwarstorep = new clnetwarstore_p();
	$licencias_reporte = $netwarstorep->reporte_licencias();
//	$netwarstorep->disconnect();
?>

<!DOCTYPE html>
<html>
	<head>
    	<meta charset="UTF-8">
        <?php include('../../netwarelog/design/css.php');?>
        <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    	<script type="text/javascript" src="../../netwarelog/catalog/js/jquery.js"></script>
	</head>
	<body>
		<table>
	    	<?php
			foreach ($licencias_reporte as $key => $licencia) {
				?>
				<tr>
					<td><?php echo $licencia["distribuidor"]?></td>
					<td><?php echo $licencia["total"]?></td>
					<td><?php echo $licencia["aplicacion"]?></td>
				</tr>
				<?php
				$detalles_licencia = $netwarstorep->detalle_reporte_licencias($licencia["id_distribuidor"], $licencia["salesman"]);
				foreach ($detalles_licencia as $key => $info) {
					?>
					<tr>
						<td><?php echo $info["codigo"]?></td>
						<td><?php echo $info["fecha"]?></td>
						<td><?php echo $info["initdate"]?></td>
						<?php 
							if ($info["initdate"] <> ""){
								$date1 = str_replace('-', '/', $info["initdate"]);
								$due_date = date('Y-m-d',strtotime($date1 . "+15 days"));
								?><td><?php echo "[" . $due_date . " VS " . date("Y-m-d") . "]"?></td><?php
							}
							
							if (strtotime($due_date) < strtotime(date("Y-m-d"))){
								?><td><a id="<?php echo $info["codigo"]?>" class="desactiva" href="#">Desactivar licencia<a/></td><?php	
							}
								
						?>
					</tr>
					<?php
				}
			}
			?>
		</table>
		
	</body>
	<script type="text/javascript">
		$('a').click(function(){ desactiva_licencias(this.id); return false; });
		
		function desactiva_licencias(codigo){
			desactivar_licencia = $.ajax({
				type: "POST",
				url: "desactiva_licencias.php",
				async: true,
				data: {code:codigo}
			}).done(function(response){
				alert("Licencia desactivada con éxito");
				window.location.reload();
			});
		}

	</script>
</html>
