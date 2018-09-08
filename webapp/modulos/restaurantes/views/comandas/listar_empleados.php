<table class="table table-bordered table-striped">
	<tr>
		<td align="center"><strong>ID</strong></td>
		<td><strong>Mesero</strong></td>
		<td><strong>Asignaciones</strong></td>
		<td><strong>Permisos</strong></td>
		<td><strong>Ver en comanda</strong></td>
	</tr><?php
	foreach ($_SESSION['permisos']['empleados'] as $key => $value) { ?> 
		<tr>
			<td align="center"><?php echo $value['id'] ?></td>
			<td 
				data-toggle="modal" 
				data-target="#modal_mesas" 
				style="cursor: pointer"
				onclick="comandas.listar_asignacion({id:<?php echo $value['id'] ?>})">
				<?php echo $value['usuario'] ?>
			</td>
			<td><?php echo $value['mesas_asignacion'] ?></td>
			<td><?php echo $value['mesas_permisos'] ?></td>
			<td align="center">
				<select 
					id="select_<?php echo $value['id'] ?>"
					class="selectpicker" 
					data-width="90%" 
					onchange="comandas.editar_empleado({id: <?php echo $value['id'] ?>, mostrar_comanda: $(this).val()})">
					<option value="1">Si</option>
					<option value="2">No</option>
				</select>
				<script>
					$("#select_<?php echo $value['id'] ?>").val(<?php echo $value['mostrar_comanda'] ?>);
				</script>
			</td>
		</tr><?php
	} ?>
</table>
<script type="text/javascript">
// Cambia el texto del select cuando esta vacio
	$('.selectpicker').selectpicker({
		noneSelectedText : 'Todos'
	}); 
</script>