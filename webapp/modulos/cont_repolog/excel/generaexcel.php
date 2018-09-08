<?php
require_once("excel.php");
require_once("excel-ext.php");
session_start();
include("../../../netwarelog/webconfig.php");
$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$resEmp=$connection->query($_SESSION['excel']);
$totEmp = $resEmp->num_rows;
$connection->close();
// Creamos el array con los datos
while($datatmp = $resEmp->fetch_array(MYSQLI_ASSOC)) {
    $data[] = $datatmp;
}
// Generamos el Excel 
createExcel($_GET['nombreseccion']." ".date('Y-m-d h-i-s A').".xls", $data);
exit;
?>