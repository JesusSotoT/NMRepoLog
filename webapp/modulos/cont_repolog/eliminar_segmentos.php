<script language='javascript'>
$(document).ready(function(){
if(parseInt($("#movsucs").val()))
{
    $("#i1686").after('<label>El segmento tiene movimientos y no se puede inactivar</label>')
    $("#i1686").hide()
}
});
</script>
<?php
if(intval($_GET['a']) == 0)
{
    $suc = $_GET['sw'];
    $suc = explode('\'',$suc);
    $myQuery = "SELECT SUM(Activo) AS Num FROM cont_movimientos WHERE Activo=1 AND IdSegmento = ".$suc[1];
    $suc = $conexion->consultar($myQuery);  
    $suc = mysql_fetch_array($suc);
    $suc = intval($suc['Num']);
    echo "<input type='hidden' id='movsucs' value='$suc'";
}
?>
