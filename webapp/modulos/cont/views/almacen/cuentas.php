<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	busca(0);
	$("#cp").select2();
	$("#nueva_cuenta").select2({'width':'100%'});
	$.fn.modal.Constructor.prototype.enforceFocus = function () {};

});

function editar(tipo,cp,id,idcuenta)
{
	$("#nueva_cuenta").val(idcuenta).trigger('change');
	$('.bs-cuentas-modal-sm').modal('show');
	$("#tipo_i").val(tipo)
	$("#cp_i").val(cp)
	$("#id_i").val(id)
}

function guardar()
{
	if(confirm("Esta seguro que desea guardar esta configuracion?"))
	{
		$.post('ajax.php?c=almacen&f=guardar_cta', 
		{
			idcuenta: $("#nueva_cuenta").val(),
			tipo 	: $("#tipo_i").val(),
			cp 		: $("#cp_i").val(),
			id 		: $("#id_i").val()
		}, 
		function(data) 
		{
			console.log("guardar(): "+data)
			if(parseInt(data))
			{
				buscaCuentas($("#cp_i").val())
				cancelar();
			}
			else
				alert("Ocurrió un error."+data)
		});
	}
}

function cancelar()
{
	$("#nueva_cuenta").val(0).trigger('change');
	$('.bs-cuentas-modal-sm').modal('hide');
}

function busca(num)
{
	if(!parseInt(num))
	{
		$.post('ajax.php?c=almacen&f=lista_cp', 
		{
			tipo: $("#tipo").val()
		}, 
		function(data) 
		{
			$("#cp").html(data).val(num).trigger('change');
			buscaCuentas(num)
		});
	}
	else
		buscaCuentas($("#cp").val())
}

function buscaCuentas(cp)
{
	$.post('ajax.php?c=almacen&f=cuentas_busca', 
		{
			tipo: $("#tipo").val(),
			cp 	:cp
		}, 
		function(data) 
		{
			$("#lista_cuentas").html(data);
		});
}
</script>
<div class="col-xs-12 col-md-8 col-md-offset-2">
	<div class='row' style='height:35px;line-height:35px;padding-left:10px;color:white;background-color:#337ab7;font-weight:bold;font-size:17px;'>
		Cuentas Contables
	</div>
	<div class='row' style='margin-top:20px;'>
		<div class='col-xs-12 col-md-4'>
			<select id='tipo' class='form-control' onchange='busca(0)'>
				<option value='1'>Clientes</option>
				<option value='2'>Proveedores</option>
			</select>
		</div>
		<div class='col-xs-12 col-md-4'>
			<select id='cp' class='form-control' onchange='busca(1)'>
			</select>
		</div>
		<div class='col-xs-12 col-md-12' style='margin-top:25px;'>
			<table class='table table-striped'>
				<tr><th>Tipo Cuenta</th>
				<th>Cuenta</th>
				<th></th></tr>
				<tbody id='lista_cuentas'>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal fade bs-cuentas-modal-sm" tabindex="-1" role="dialog" aria-labelledby="cuentas" id="cuentas_modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Asignar Cuentas</h4>
      </div> 
      <div class="modal-body well">
        <div class='row' id='row_tipo_cambio'>
          <div class='col-xs-12 col-md-12'>
          	<input type='hidden' id='tipo_i'>
          	<input type='hidden' id='cp_i'>
          	<input type='hidden' id='id_i'>
            <select id='nueva_cuenta' class='form-control'>
            	<option value='0'>Usar Default</option>
            	<?php
            	while($c = $cuentas->fetch_assoc())
            		echo "<option value='".$c['account_id']."'>(".$c['manual_code'].") ".$c['description']."</option>";
            	?>
            </select>
          </div> 
        </div> 

        <div class="modal-footer">
          <button class='btn btn-default btn-sm' onclick="guardar()" id="btn_guardar_pago">Guardar</button>
          <button class='btn btn-default btn-sm' onclick="cancelar()" id="btn_cancelar_pago">Cancelar</button>
        </div> 
      </div> 
    </div> 
  </div> 
</div> 