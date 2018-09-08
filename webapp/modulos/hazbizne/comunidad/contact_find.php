<?php

    include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();
	
	$invitaciones = mysql_escape_string($_POST["i"]);
	$contactos = array();

	include "../clases.php";
	$netwarstore = new clnetwarstore();
	$nombre = mysqli_real_escape_string($netwarstore->cn, $_POST["name"]);
	$pagina = mysqli_real_escape_string($netwarstore->cn, $_POST["pagina"]);
	$contactos = $netwarstore->get_contactos($nombre,$pagina,$invitaciones);
	$netwarstore->disconnect();

	$nmdev_common = new clnmdev_common();
	$contactos = $nmdev_common->check_contactos($contactos,$bd);
	$nmdev_common->disconnect();

	//echo "Localizado ... ".$nombre." -p: ".$pagina." Contactos:".$contactos;

	//echo "<table class='contactos'><tbody>";
	foreach($contactos as $contacto){
		?>
			<tr>
				<td width="90"><center>
				<?php 
					if($contacto["vinculados"]==1){
						?>
							<img 	
								id="img_<?php echo $contacto["nombre_db"]; ?>" 
								src='../img/form_ok.png' 
								height='20' title="Contacto">
						<?php	
					} else if(($contacto["vinculados"]==2)||($contacto["vinculados"]==4)){
						
						$mensaje = "Hazbizne";
						if($contacto["vinculados"]==4) $mensaje = "Confirmar"; 

						?>

							<input	
								id="btn_<?php echo $contacto["nombre_db"]; ?>" 
								type="button" 
								value="<?php echo $mensaje; ?>"
								onclick="btnhazbizne_click('<?php echo $contacto["correo"]; ?>','<?php echo $contacto["rfc"]; ?>','<?php echo $contacto["nombre_db"]; ?>')">

							<img 	
								id="img_<?php echo $contacto["nombre_db"]; ?>" 
								src='../../../netwarelog/repolog/img/loading.gif' 
								style="display:none"
								height='20'>

						<?php
					} else if($contacto["vinculados"]==3) {
						?>
							<img 	
								id="img_<?php echo $contacto["nombre_db"]; ?>" 
								src='../img/invitation.png' 
								height='20' title="En espera">
						<?php	
					} 
				?>
				</center></td>
				<td><?php echo $contacto["nombre"]; ?></td>
				<td><?php echo $contacto["razon"]; ?></td>
				<td><?php echo $contacto["instancia"]; ?></td>
				<!--<td><?php //echo $contacto["correo"]; ?></td>
				<td><?php //echo $contacto["rfc"]; ?></td>
				<td><?php //echo $contacto["vinculados"]; ?></td>-->

			</tr>
		<?php
	}
	//echo "</tbody></table>";
	
	if(count($contactos)>=10){
		?>
			<tr>
			<th colspan=4 align=left id="th<?php echo $pagina; ?>"   >

				<input
					type="button" 
					value="Más registros ..." 
					onclick="btnmas_click(<?php echo $pagina; ?>)"
				>
	
				<script type="text/javascript">
					function btnmas_click(pagina){						
						var thmas = document.getElementById("th"+pagina);
						thmas.style.display="none";
						get_contactos();
					}	
				</script>

			</th>
			</tr>	
		<?php
	}
	
	
?>
