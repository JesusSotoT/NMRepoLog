<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>
$(function()
{
	get_relaciones()
});

function get_relaciones()
{
	$("#agregar").hide()
	$("#resul").html('');
	if(!parseInt($("#empleado").val()))
		$("#table-resul").hide();
	else
	{
		if(parseInt($("#empleado").val())>=3)
			$("#agregar").show()
		$.post('ajax.php?c=edu&f=get_relaciones',
		{
			empleado:$("#empleado").val()
		},
		function(data)
		 {
		 	$("#resul").html(data);
		 	$("#table-resul").show();
		 });
	}
}

function elimina(id)
{
	if(confirm("Esta seguro que desea eliminar esta relacion?"))
	{
		$.post('ajax.php?c=edu&f=elimina_rel_emp',
		{
			idrel:id
		},
		function()
		 {
		 	get_relaciones()
		 });
	}
}

function agregar()
{
	$("#usuario_input").val($("#empleado option:selected").text())
}

function cancelar()
{
	$('.bs-relac-modal-md').modal('hide');
	$("#instancia").val(0)
}

function guardar()
{
	if(confirm("Esta seguro que quiere guardar esta relacion(es)?"))
	{
		$.post('ajax.php?c=edu&f=guarda_rel_emp',
		{
			empleado : $("#empleado").val(),
			inst_rel: $("#instancia").val()
		},
		function(data)
		 {
		 	if(data==true)
		 	{
		 		get_relaciones()
		 		$('.bs-relac-modal-md').modal('hide');
		 	}
		 	else
		 		alert(data)
		 });
	}
}
</script>

<div class="col-xs-12 col-md-6 col-md-offset-3 well" style='font-weight:bold;font-size:16px;'>
	<a href='index.php?c=Edu&f=panel_inst' class='btn btn-default' role='button'>Regresar</a><br /><br />
	Asociar Usuario a Instancias dependientes.
</div>
<div class="col-xs-12 col-md-6 col-md-offset-3 well">
	<select id='empleado' class='form-control' onchange='get_relaciones()'>
		<option value='0'>Seleccione un usuario</option>
		<?php 
			while($le = $lista_empleados->fetch_assoc())
				echo "<option value='".$le['idempleado']."'>".$le['usuario']."</option>";
		 ?>
	</select>
</div>

<div class="col-xs-12 col-md-6 col-md-offset-3 well" id='table-resul'>
	<button class='btn btn-primary' id='agregar' data-toggle="modal" data-target=".bs-relac-modal-md" onclick="agregar()">Agregar</button><br />
	<table class='table table-striped'>
		<tr><th>Instancia</th><th>Eliminar</th></tr>
		<tbody id='resul'>
		</tbody>
	</table>
</div>

<!--Series**************************-->
<div class="modal fade bs-relac-modal-md" tabindex="-1" role="dialog" aria-labelledby="relac">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
				<h4 id="modal-label">Agregar relacion</h4>
			</div>
			<div class="modal-body well">
				<b>Usuario:</b> 
				<input type='text' id='usuario_input' readonly="readonly" class='form-control'><br /><br />
				<b>Instancia(s):</b> 
				<select id='instancia' class='form-control' multiple>
					<?php
					while($li = $lista_instancias->fetch_assoc())
						echo "<option value='".$li['idrel']."'>".$li['instancia']."</option>";
					?>
				</select>
			</div>
			<div class="modal-footer">
				<button class='btn btn-default btn-sm' onclick="guardar()">Guardar</button><button class='btn btn-default btn-sm' onclick="cancelar()">Cancelar</button>
			</div>
		</div>
	</div>
</div>