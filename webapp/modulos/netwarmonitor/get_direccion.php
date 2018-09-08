<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
include "../../netwarelog/catalog/conexionbd.php";
$conexion->cerrar();

include "../../modulos/hazbizne/clases.php";

$nmdev_common = new clnmdev_common();
$param = mysqli_real_escape_string($nmdev_common->cn, $_POST["direccion"]);
$direccion_array = $nmdev_common->get_direccion_distribuidor($param);

echo json_encode($direccion_array);


?>