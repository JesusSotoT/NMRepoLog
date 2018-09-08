<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script>
$(function(){
	balance('Activo');
	balance('Pasivo');
	balance('Resultados');
});

function balance(tipo)
{
	$.post("ajax.php?c=Reports&f=BalanceGral",
 		 {
    		tipo: tipo
  		 },
  		 function(data)
  		 {
  		 	$('#'+tipo).html(data);
  		 });
}
</script>
<div class="nmwatitles" >Bsalance General</div>
<?php
/*while($d = $Cuentas->fetch_array())
{
	$arrayCuenta = explode(".",$d['CodigoCuenta']);
	$otroArr = '';
	for($i=0;$i<=count($arrayCuenta)-1;$i++)
	{
		$otroArr .= $arrayCuenta[$i];
		echo $this->ReportsModel->accountName($otroArr)."<br />";
		$otroArr .= ".";
	}
	echo $d['Importe']."<br /><hr />";
	
	
}*/
?>
<div class='lateral' style='width:810px;margin-right:10px;'>
	<div class='nmsubtitle'>Activos</div>
	<div  id='Activo'></div>
</div>
<div class='lateral' style='width:810px;margin-right:10px;'>
	<div class='nmsubtitle'>Pasivos</div>
	<div  id='Pasivo'></div>
</div>

<div class='lateral' style='width:810px;margin-right:10px;'>
	<div class='nmsubtitle'>Resultados</div>
	<div  id='Resultados'></div>
</div>