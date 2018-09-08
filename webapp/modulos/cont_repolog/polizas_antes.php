<?php
$empresa = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
$empresa = $conexion->consultar($empresa);	
$empresa = mysql_fetch_array($empresa);
echo "<table border=0 id='empresa' style='width:550px'><tr style='width:550px'><td style='text-align:center;width:550px'><b style='text-align:center;'>".$empresa['nombreorganizacion']."</b></td></tr></table>";


$pos = strpos($sql, "AND SSaaldo=0");
if($pos === false)
{
    $sql = str_replace('AND p.idperiodo != 13', '', $sql);
    $sql = str_replace("AND SSaaldo=1", '', $sql);
}
$sql = str_replace("AND SSaaldo=0", '', $sql);
//echo "pppppppp".$pos."<br />";



//echo $sql
?>



