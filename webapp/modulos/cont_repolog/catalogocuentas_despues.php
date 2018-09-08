<script src="../../netwarelog/catalog/js/jquery.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="../../modulos/cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function(){
	$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='../../modulos/cont/images/images.jpg' width='35px'></a></td>")
});
function generaexcel()
			{
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Catalogo de Cuentas')").html(), 'name': 'Catalogo de Cuentas'});
			}
</script>