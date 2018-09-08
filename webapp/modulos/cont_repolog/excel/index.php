<?php
if(isset($_POST['crea'])){
require_once("excel.php");
require_once("excel-ext.php");
// Consultamos los datos desde MySQL
$conEmp = mysql_connect("localhost", "root", "root");
mysql_select_db("prueba", $conEmp);
$queEmp = "SELECT* FROM accelog_usuarios";
$resEmp = mysql_query($queEmp, $conEmp) or die(mysql_error());
mysql_close($conEmp);
$totEmp = mysql_num_rows($resEmp);
// Creamos el array con los datos
while($datatmp = mysql_fetch_assoc($resEmp)) {
    $data[] = $datatmp;
}
// Generamos el Excel 
createExcel($_POST['titulo'].".xls", $data);
echo "Se ha creado el Excel.";

exit;
}else{
?>
shaka
<form action="" method="post">
<input type="text" name="titulo">
<input type="submit" name="crea" value="Convertir a Excel">
</form><?php }?>