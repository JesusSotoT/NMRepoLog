<?php
require_once("excel.php"); 
require_once("excel-ext.php"); 

$conEmp = mysql_connect("localhost", "userDB", "passDB");
mysql_select_db("sampleDB", $conEmp);
$queEmp = "SELECT nombre, direccion, telefono FROM empresa";
$resEmp = mysql_query($queEmp, $conEmp) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$data[] = $datatmp; 
}  
createExcel("excel-mysql.xls", $data);
exit;
?>