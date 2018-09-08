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

	foreach($contactos as $contacto){
		if($contacto["vinculados"]==1){
		?>
			<tr>
				<td>
				<?php 
					$mensaje = "Conversar";
					if($contacto["vinculados"]==4) $mensaje = "Confirmar"; 
					?>
						<input	
							id="btn_<?php echo $contacto["nombre_db"]; ?>" 
							type="button" 
							value="<?php echo $mensaje; ?>"
							onclick="btnconversacion_click('<?php echo $contacto["rfc"]; ?>','<?php echo $contacto["nombre_db"]; ?>')">
				</td>
				<td><?php echo $contacto["nombre"]; ?></td>
				<td><?php echo $contacto["razon"]; ?></td>
				<td><?php echo $contacto["instancia"]; ?></td>
			</tr>
		<?php
		}
	}
	
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
