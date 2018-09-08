<?php
if(intval($_POST['i1676'] == 2) AND !intval($_POST['i1990']))
{
    include("../../netwarelog/webconfig.php");
    $conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
    $query = "UPDATE bco_clasificador SET activo = 0 WHERE idNivel = 1 AND cuentapadre = ".$_POST['id_h'];
    $conection->query($query);
    //echo "Consulta: ".$query;
}

?>