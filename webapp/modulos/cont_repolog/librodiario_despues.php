

<script src="../../modulos/cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function(){
$("#tabla_reporte tr").each(function(index)
	{
		$("td:contains('%')",this).css('text-align','left');
	});

$("td [title='CARGO']").css('text-align','right');
$("td [title='ABONO']").css('text-align','right');
$("th:contains('Subtotal')").next().css('text-align','right')
$("th:contains('Subtotal')").next().next().css('text-align','right')
$("th:contains('TOTAL')").next().css('text-align','right')
$("th:contains('TOTAL')").next().next().css('text-align','right')

$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='../../modulos/cont/images/images.jpg' width='35px'></a></td>")
});
function generaexcel()
			{
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Libro de Diario')").html(), 'name': 'Libro de Diario de ' + $('#empresa tr td').html()});
			}
</script>

