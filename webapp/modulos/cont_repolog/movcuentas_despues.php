<script src="../../modulos/cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function(){
	
	$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='../../modulos/cont/images/images.jpg' width='35px'></a></td>")

	//$("th:contains('Subtotal [')").text('Subtotal').next().next().after("<th style='border:solid 1px;background-color:#efefef;text-align:left;font-size:12px;'></th>");
	//$("th:contains('TOTAL')").text('Total de la Cuenta').next().next().after("<th style='border:solid 1px;background-color:#efefef;text-align:left;font-size:12px;'></th>");
});	
function generaexcel()
			{
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Reporte de Movimientos por Cuentas')").html(), 'name': 'Reporte de Movimientos por Cuentas'});
			}
</script>
<?php

$where = explode('where',$sql);
$manual = strpos($where[1],"a.manual_code = ''");
$account = strpos($where[1],"a.account_code = ''");
if($manual ==! FALSE AND $account === FALSE)
{
	$nuevoWhere = " where ".str_replace("(a.manual_code = '' OR","(",$where[1]);
}
if($manual === FALSE AND $account ==! FALSE)
{
	$nuevoWhere = " where ".str_replace("OR a.account_code = '')",")",$where[1]);
}
if($manual ==! FALSE AND $account ==! FALSE)
{
	$a = " where ".str_replace("(a.manual_code = '' OR","",$where[1]);
	$nuevoWhere = str_replace("a.account_code = '') AND","",$a);
}
if($manual === FALSE AND $account === FALSE)
{
	$nuevoWhere = " where ".$where[1];
}

$sql = $where[0].$nuevoWhere;
//echo $nuevoWhere;
?>
