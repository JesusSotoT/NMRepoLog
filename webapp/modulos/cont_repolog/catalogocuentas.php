<?php
	$empresa = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
$empresa = $conexion->consultar($empresa);	
$empresa = mysql_fetch_array($empresa);
echo "<table border=0 id='empresa' style='width:550px'><tr style='width:550px'><td style='text-align:center;width:550px'><b style='text-align:center;'>".$empresa['nombreorganizacion']."</b></td></tr></table>";
//echo $sql;
$eliminados=0;

//Si la naturaleza es 0 entonces busca todas
if (preg_match("/account_nature=0/i", $sql)) 
{
	//Reemplaza la cadena por una vacia
	$sql = str_replace('a.account_nature=0 AND', "", $sql);

	//Suma una cadena eliminada
	$eliminados+=1;
}
//Se el tipo es 0 entonces busca todos
if (preg_match("/type_id=0/i", $sql)) 
{
	//Reemplaza la cadena por una vacia
	$sql = str_replace('AND t.type_id=0', "", $sql);
	$sql = str_replace('t.type_id=0', "", $sql);

	//Suma una cadena eliminada
	$eliminados+=1;
}

//Si suma 2 o mas cadenas eliminadas entonces se elimina el where
if($eliminados>=2)
{
	$sql = str_replace('where', "", $sql);
}
//echo "<hr>".$sql;

?>
<style>
td.tdmoneda
{
	text-align:left;
}
</style>