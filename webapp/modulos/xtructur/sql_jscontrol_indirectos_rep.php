<?php

$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
    $obra_ini = $cookie_xtructur['obra_ini'];
      $obra_fin = $cookie_xtructur['obra_fin'];
}

$oper = $_POST['oper'];
$mes = $_GET['mes'];
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;

include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){
    $id = $_POST['id'];
    $mysqli->query("UPDATE constru_proforma2 SET borrado=1 WHERE id in ($id);");
    exit();

}

if(isset($oper) && $oper=='add'){
    $por_utilidad = $_POST['por_utilidad'];
    $de_utilidad = $_POST['de_utilidad'];
    $por_indirecto = $_POST['por_indirecto'];
    $de_indirecto = $_POST['de_indirecto'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];

    $mysqli->query("INSERT INTO constru_proforma2 (id_obra,por_utilidad,de_utilidad,por_indirecto,de_indirecto,factor_salario) VALUES ('$id_obra','$por_utilidad','$de_utilidad','$por_indirecto','$de_indirecto','$factor_salario');");
    exit();
}

if(isset($oper) && $oper=='edit'){
    $id = $_POST['id'];
    $costo_directo = $_POST['costo_directo'];
    $costo_directo_p = $_POST['costo_directo_p'];
    $indirecto_campo = $_POST['indirecto_campo'];
    $indirecto_campo_p = $_POST['indirecto_campo_p'];
    $indirecto_oc = $_POST['indirecto_oc'];
    $indirecto_oc_p = $_POST['indirecto_oc_p'];
    $utilidad = $_POST['utilidad'];
    $utilidad_p = $_POST['utilidad_p'];
    $importe_pres = $_POST['importe_pres'];
    $importe_presu_p = $_POST['importe_presu_p'];
    $factor_salario = $_POST['factor_salario'];

    $mysqli->query("UPDATE constru_proforma2 SET costo_directo='$costo_directo', costo_directo_p='$costo_directo_p', indirecto_campo='$indirecto_campo', indirecto_campo_p='$indirecto_campo_p', indirecto_oc='$indirecto_oc', indirecto_oc_p='$indirecto_oc_p', utilidad='$utilidad', utilidad_p='$utilidad_p', importe_pres='$importe_pres', importe_presu_p='$importe_presu_p', factor_salario='$factor_salario' WHERE id='$id';");
    exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_proforma2 WHERE id_obra='$id_obra' AND borrado=0;";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$count = $row['count'];


if( $count >0 ) {
    $total_pages = ceil($count/$limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
if($start<0) $start=0;
if($_search=='true'){
    $soper=$_GET['searchOper'];
    $searchField='a.'.$searchField;
    if($soper=='eq'){
        $cad=" AND ".$searchField."='".$search."' ";
    }elseif($soper=='ne'){
        $cad=" AND ".$searchField."!='".$search."' ";
    }elseif($soper=='cn'){
        $cad=" AND ".$searchField." LIKE  '%".$search."%' ";
    }elseif($soper=='nc'){
        $cad=" AND ".$searchField." NOT LIKE  '%".$search."%' ";
    }elseif($soper=='lt'){
        $cad=" AND ".$searchField." <  ".$search." ";
    }elseif($soper=='gt'){
        $cad=" AND ".$searchField." >  ".$search." ";
    }else{
        echo 'Operador de busqueda incorrecto';
        exit();
    }
}else{
    $cad='';
}

$year = date('Y');
$mesnum=$mes*1;


$beg = (int) date('W', strtotime("first monday of $year-$mes"));
$end = (int) date('W', strtotime("last  monday of $year-$mes"));

$weeks = join(', ', range($beg, $end));

$start2 = new DateTime($obra_ini);
$start2->modify('first day of this month');
$end2= new DateTime($obra_fin);
$end2->modify('first day of next month');

$fff = new DateTime($mes);
$fff->modify('last day of this month');

$FI= $start2->format('Y-m-d');
$FF= $fff->format('Y-m-d');

$interval2= DateInterval::createFromDateString('1 month');
$period2= new DatePeriod($start2, $interval2, $end2);
$mesact=0;
$mesfin=0;
foreach ($period2 as $dt2) {
    $mesfin=$mesfin+1;
    if($dt2->format("Y-m")==$mes){$mesact=$mesfin;}

}


$difmes=$mesfin-$mesact;


$ejerecer=0;
//por ejerecer seria la diferencia del importe menos el costo acumulado
//El cosoto acumulado seria lo que se acumulo del mes 1 hasta el que se selecciono y repetir el ultimo mes hasta el fin de la obra mes 17
//Nominas tecnicos ambos, estimaciones indirectos

/*

*/



 $SQL = "SELECT a.id as ida, b.cargo, a.importe, sum(e6.val_fact) as monto 
FROM constru_desgloce a
left join constru_cuentas_cargo b on b.id=a.id_cc
left join constru_estimaciones_bit_chica dd on dd.estatus=1 and dd.id_obra='$id_obra' and substr('$mes',5,2)=MONTH(fecha) AND substr('$mes',1,4)=YEAR(fecha)
left join constru_estimaciones_chica e6 on e6.id_cc=b.id and e6.id_bit_chica in (dd.id) and e6.val_fact>0 and e6.val_fact is not null
WHERE  1=1 AND a.id_obra='$id_obra' ".$cad."  group by a.id  ORDER BY a.id LIMIT $start , $limit";


$SQL = "SELECT a.importe, b.cargo, b.id as idcargo, a.id as ida FROM constru_desgloce a
left join constru_cuentas_cargo b on b.id=a.id_cc
WHERE a.id_obra='$id_obra' group by a.id";

$result = $mysqli->query($SQL);


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {

    /*
    SELECT  e4.total as monto FROM constru_bit_nominaca e4
    WHERE e4.id_cc=".$row['idcargo']." aND e4.id_obra='$id_obra' and e4.estatus=1
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
left join constru_bit_nominaca e4 on e4.id_cc=d.id aND e4.id_obra='$id_obra' and e4.estatus=1
where d.id>0 and e4.total>0 and e4.total is not null  group by e4.id 

*/

      $SQL3 = "SELECT  sum(e4.total) as monto FROM constru_bit_nominaca e4
    WHERE e4.id_cc=".$row['idcargo']." aND e4.id_obra='$id_obra' and e4.estatus=1 
    AND fecha between '".$FI."' AND '".$FF."'
    union all
    SELECT e2.imp_estimacion as monto  
FROM constru_estimaciones_bit_indirectos e2 WHERE e2.id_cc=".$row['idcargo']." AND e2.id_obra='$id_obra' and e2.estatus=1
AND fecha between '".$FI."' AND '".$FF."'
union all
SELECT sum(e6.val_fact) as monto 
FROM constru_estimaciones_bit_chica dd
left join constru_estimaciones_chica e6 on e6.id_cc=".$row['idcargo']." and e6.id_bit_chica in (dd.id)
WHERE dd.estatus=1 and dd.id_obra='$id_obra'
AND fecha between '".$FI."' AND '".$FF."';";

    $result3 = $mysqli->query($SQL3);
    $row3 = $result3->fetch_array();

    $weeks = join(', ', range(1, $end));


     $SQL2 = "SELECT  sum(e4.total) as montoacu FROM constru_bit_nominaca e4
    WHERE e4.id_cc=".$row['idcargo']." aND e4.id_obra='$id_obra' and e4.estatus=1
    AND fecha between '".$FI."' AND '".$FF."'
    union all
    SELECT e2.imp_estimacion as montoacu 
FROM constru_estimaciones_bit_indirectos e2 WHERE e2.id_cc=".$row['idcargo']." AND e2.id_obra='$id_obra' and e2.estatus=1
AND fecha between '".$FI."' AND '".$FF."'
union all
SELECT sum(e6.val_fact) as montoacu 
FROM constru_estimaciones_bit_chica dd
left join constru_estimaciones_chica e6 on e6.id_cc=".$row['idcargo']." and e6.id_bit_chica in (dd.id)
WHERE dd.estatus=1 and dd.id_obra='$id_obra' AND fecha between '".$FI."' AND '".$FF."'; ";
    $result2 = $mysqli->query($SQL2);
    $row2 = $result2->fetch_array();
    $row2['montoacu']+=$difmes*$row2['montoacu'];
    $ejerecer=$row['importe']-$row2['montoacu'];
    //otro paso, quitar ceros
    //agregar el gran total en las remesas generadas
    $responce->rows[$i]['ida']=$row['ida'];
    $responce->rows[$i]['cell']=array($row['cargo'],$row['importe'],$row3['monto'],$row2['montoacu'],$ejerecer);
    $i++;
} 
echo json_encode($responce);
?>