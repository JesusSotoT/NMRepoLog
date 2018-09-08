<?php
$fecha = date('Y-m-d H:i:s');
echo "Fecha sin zona horarios: <br>";
echo $fecha;

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');
echo "<br><br>Fecha con zona horarios: <br>";
echo $fecha;

?>