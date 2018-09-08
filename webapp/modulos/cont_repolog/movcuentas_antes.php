<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<script src="../cont/js/select2/select2.min.js"></script>
<script type="text/javascript" src="../../../webapp/netwarelog/repolog/js/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="../../../webapp/netwarelog/catalog//css/view.css" />
<link rel="stylesheet" type="text/css" href="../../../webapp/netwarelog/utilerias/css_repolog/estilo-1.css" />
<link rel="stylesheet" type="text/css" href="../cont/js/select2/select2.css" />
<script languahe='javascript'>
$(function()
 {
 	 $("#cuentas").select2({
				 width : "150px"
				});
 });
</script>
<?php
$sql='select* from cont_config';
?>
<font size="3" color="gray"><b>Reporte de Movimientos por Cuentas</b></font><br /><br />
<?php
if(isset($_POST['envia']))
{
echo "Hola";
}
else
{
?>
<form name='mov' method='post' action=''>
	<table class='reporte'>
		<tbody>
		<tr class='trcontenido'><td class='tdcontenido'>Numero_Cuenta</td><td class='tdcontenido'><select name='cuentas' id='cuentas'></select></td></tr>
		<tr class='trcontenido'><td class='tdcontenido'>Del</td><td class='tdcontenido'><input id="f3_2" name="f3_2" title="Día" size="2" maxlength="2" value="30" type="text"> / <input id="f3_1" name="f3_1" title="Mes" size="2" maxlength="2" value="07" type="text"> / <input id="f3_3" name="f3_3" title="Año" size="4" maxlength="4" value="2014" type="text">&nbsp;<img id="f3_img" class="datepicker" src="../../../webapp/netwarelog/repolog/img/calendar.gif" alt="Seleccione una fecha." title="Haga clic para seleccionar una fecha."></td></tr>
		<tr class='trcontenido'><td class='tdcontenido'>Al</td><td class='tdcontenido'><input id="f4_2" name="f4_2" title="Día" size="2" maxlength="2" value="30" type="text"> / <input id="f4_1" name="f4_1" title="Mes" size="2" maxlength="2" value="07" type="text"> / <input id="f4_3" name="f4_3" title="Año" size="4" maxlength="4" value="2014" type="text">&nbsp;<img id="f4_img" class="datepicker" src="../../../webapp/netwarelog/repolog/img/calendar.gif" alt="Seleccione una fecha." title="Haga clic para seleccionar una fecha."></td></tr>
		<tr><th colspan='2' align='right'><input type='submit' name='envia'></th></tr>
	</tbody>
	</table>
</form>
<script type="text/javascript">
															Calendar.setup({
																inputField	 : 'f3_3',
																baseField    : 'f3',
																displayArea  : 'f3_area',
																button		 : 'f3_img',
																ifFormat	 : '%B %e, %Y',
																onSelect	 : selectDate
															});

															Calendar.setup({
																inputField	 : 'f4_3',
																baseField    : 'f4',
																displayArea  : 'f4_area',
																button		 : 'f4_img',
																ifFormat	 : '%B %e, %Y',
																onSelect	 : selectDate
															});
														</script>
<?php
}
?>