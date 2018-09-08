
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<style type="text/css">
	.select2-container {
	    width: 100% !important;
	}
	.btnMenu{
	    border-radius: 0; 
	    width: 100%;
	    margin-bottom: 1em;
	}
	.row
	{
	    margin-top: 1em !important;
	}
</style>
 
<div class="container">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 text-center">
					<b>CLASIFICACION NIF DE CUENTAS / <a href='index.php?c=AccountsTree&f=cuentasNIF&da=1'>CLASIFICACION DIGITO AGRUPADOR OFICIAL</a></a></b>
				</div>
			</div>
			<div class="row" style="background-color: #eee;">
				<div class="col-md-2">
					<label class="text-center"><strong>Numero</strong></label>
				</div>
				<div class="col-md-3">
					<label class="text-center"><strong>Descripción</strong></label>
				</div>
				<div class="col-md-7">
					<label class="text-center"><strong>Asignar a cuenta</strong></label>
				</div>
			</div>
			<?php
			$select = "<option value='0'>Ninguno</option>";
			while($n = $nif->fetch_object())
			{
				$select .= "<option value='$n->id'>$n->clasificacion / $n->nivel</option>";
			}
			$cont=1;
			while($cm = $cuentas_mayor->fetch_object())
			{
				echo "	<div class='row niftr' cont=". $cont .">
							<div class='col-md-2' title='Numero'>
								<div class='form-group'>
									". $cm->manual_code ."
								</div>
							</div>
							<div class='col-md-3' title='Descripción'>
								<div class='form-group'>
									". $cm->description . "
								</div>
							</div>
							<div class='col-md-7' title='Asignar a cuenta'>
								<div class='form-group'>
									<input type='hidden' id='lbl_" . $cont . "' value='" . $cm->nif . "'>
									<select class='selects' id='sel_" . $cont . "' onchange='cambia(" . $cont . ", " . $cm->account_id . ")'>
										" . $select . "
									</select>
								</div>
							</div>
						</div>
					";
				$cont++;
			}
			?>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>

<script language='javascript'>
$(document).ready(function()
{
	$(".niftr").each(function()
	{
		var cont = $(this).attr('cont');
		$("#sel_"+cont).val($("#lbl_"+cont).val())
	});
	$(".selects").select2({
        	 width : "550px"
        });
});
function cambia(s,c)
{
	$.post("ajax.php?c=AccountsTree&f=UpdateNif",
                  {
                	IdCuenta: c,
                	Valor: $("#sel_"+s).val(),
                    Tipo: 1
                  });
}
</script>