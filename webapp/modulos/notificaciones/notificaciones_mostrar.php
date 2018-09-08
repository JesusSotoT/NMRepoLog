<?php
    
    include("parametros.php");

$idempresa = mysql_real_escape_string($_GET["i"]);
$nombre_empresa = mysql_real_escape_string($_GET["n"]);
$idregimen = mysql_real_escape_string($_GET["r"]);

?>

<!doctype html>
<html lang="sp">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">		

		<LINK href="notificaciones.css" title="estilo 2" rel="stylesheet" type="text/css" />        	

		
		<script type="text/javascript" src="<?php echo $link_catalog_local; ?>/js/jquery.js"></script> 
		<script type="text/javascript">

			$(document).ready(function(){
				$("#id_loading").hide();
			});

			function compartir_empresa(){
				$("#id_btncompartirempresa").hide();
				$("#id_loading").fadeIn(100);

				$.ajax({

				}).done(function(){
					
				});
			}
		</script>

	</head>
	<body>
		<table width="100%" id="table_divcomp">
			<tbody>
				<tr valign='top'>
					<td>Compartir: <?php echo $nombre_empresa; ?></td>
					<td align='right'><a href="javascript:parent.cerrardiv();">x</a></td>
				</tr>		
			</tbody>
		</table>
		<hr>
		
		<br>
		<section class="" >			
			<header>Los usuarios arriba mencionados tendrán permiso de utilizar:</header>
			<br>
			<table id="notificaciones" cellspacing="0" cellpadding="5">
				<tbody>
					
			<?php
				$sql = "select leido, notificacion, link, fechanotificacion from notificaciones ";
				//echo $sql;
				//exit;
				$result=$conexion->consultar($sql);
				$col=0;				
				while($reg = $conexion->siguiente($result)){
					if ($reg{"leido"} == 0)
					{
						$read = "unread";	
					}
					else
					{
						$read = "read";
					}
					echo "<tr class='".$read."'><td align='left'>".$reg{"notificacion"}."</td><td><a target='blank' href='".$reg{"link"}."'>".$reg{"link"}."</td><td>".$reg{"fechanotificacion"}."</td></tr>";
				} 
			?>	
						
				</tbody>
			</table>	
		</section>		
		<br>
		
	</body>
</html>