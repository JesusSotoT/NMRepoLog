<?php
require("../../modulos/cont/models/tipo_cambio.php");
$TipoCambioModel = new TipoCambioModel();
$header = 0;
//Traemos el ultimo registro de los tipos de cambio
$ultimo_cambio = $TipoCambioModel->ultimo_registro(2);
if(!$ultimo_cambio)
{
    $ultimo_cambio = "2004-12-31";
    $header = 1;
}
$cambios_faltantes = $TipoCambioModel->cambios_faltantes(2,$ultimo_cambio);
if($cambios_faltantes->num_rows)
{
    $myQuery = "";
    while($cf = $cambios_faltantes->fetch_assoc())
    {
        $myQuery .= "INSERT IGNORE INTO cont_tipo_cambio(id,fecha,tipo,tipo_cambio,moneda) VALUES(".$cf['id'].",'".$cf['fecha']."','".$cf['tipo']."',".$cf['valor'].",".$cf['moneda']."); ";
    }
    $callback = $TipoCambioModel->insertar_faltantes($myQuery);
    if($callback && $header)
    {
        echo "<meta http-equiv='refresh' content='12'>";
    }

}
?>
