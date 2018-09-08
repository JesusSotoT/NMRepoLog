
<?php include("../../netwarelog/catalog/conexionbd.php");

$q=mysql_query("Select archivo from mrp_proveedor_autorizaciones where idProveedor=".$_POST["proveedor"]." and tipo=".$_POST["tipo"]);
if(mysql_num_rows($q)>0)
{
	echo "<b>Archivos adjuntos:</b>";
while($row=mysql_fetch_array($q))
{
		
	echo "<a href='../../modulos/mrp/autorizaciones/".$row["archivo"]."'>".$row["archivo"]."</a><br><br>";
}
}
?>

<form id="myForm" action="../../modulos/mrp/uploadautorizaciones.php" method="post" enctype="multipart/form-data">
<input name="a" type="file"><br>
<input name="b" type="file"><br>
<input name="c" type="file"><input type="hidden" id="archivos" ><br>
<input id="opcion"  type="hidden"><br>
</form>



