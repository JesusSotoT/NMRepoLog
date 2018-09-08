<?php include_once("../../netwarelog/catalog/conexionbd.php"); 
 $q = strtolower($_GET["term"]);
$return = array();
    $query = mysql_query("select  id,nombre from comun_cliente where nombre like '%$q%' order by nombre desc");
    while ($row = mysql_fetch_array($query)) {
    array_push($return,array('id'=>$row["id"],'label'=>utf8_encode($row['nombre'])));
}
echo(json_encode($return));
?>