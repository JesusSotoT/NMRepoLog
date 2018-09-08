<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
 
include('conexiondb.php');

$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$id_presupuesto=$row['id'];

@$oper = $_POST['oper'];
$id_partida = 0;
@$_search = $_GET['_search'];
@$searchField = $_GET['searchField'];
@$search = $_GET['searchString'];


$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database

$mysqli->query("SET NAMES utf8");
if(isset($oper) && $oper=='add'){

    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $codigo = $_POST['codigo'];
    $unidtext = $_POST['unidtext'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $unidad = $_POST['unidad'];

    $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, unidtext, codigo, unidad, descripcion, precio_costo, precio_venta) VALUES ('$id',1,'$unidtext','$codigo','$unidad','$descripcion','$precio_costo','$precio_venta');");
    exit();
}

if(isset($oper) && $oper=='edit'){

    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $codigo = $_POST['codigo'];
    $id_um = $_POST['id_um'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $unidad = $_POST['unidad'];
    $corto = $_POST['corto'];

    $mysqli->query("UPDATE constru_recurso SET id_um='$id_um', codigo='$codigo', unidad='$unidad', corto='$corto', descripcion='$descripcion', precio_costo='$precio_costo', precio_venta='$precio_venta' WHERE id='$id';");
    exit();
}

//$SQL = "SELECT COUNT(*) AS count FROM constru_recurso WHERE borrado=0 AND id_presupuesto='$id_presupuesto';";
//$result = $mysqli->query($SQL);
//$row = $result->fetch_array();
$count = 76;

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

$SQL = "
SELECT  a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Estimacion Subcontratista' as Concepto, e1.imp_estimacion, concat('Semana: ',e1.semana,' ESTSUB-',e1.id,' - ',f1.razon_social_sp) as info , e1.fecha FROM constru_cuentas_cp a
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
left join constru_estimaciones_bit_subcontratista e1 on e1.id_cc=d.id aND e1.id_obra='$id_obra' and e1.estatus=1
left join constru_info_sp f1 on f1.id_alta=e1.id_subcontratista
where d.id>0 and e1.imp_estimacion>0 and e1.imp_estimacion is not null group by e1.id
union all
SELECT  a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Estimacion Indirectos' as Concepto, e2.imp_estimacion, concat('Semana: ',e2.semana,' Factura: ',e2.factura) as info , e2.fecha FROM constru_cuentas_cp a
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
left join constru_estimaciones_bit_indirectos e2 on e2.id_cc=d.id aND e2.id_obra='$id_obra' and e2.estatus=1
where d.id>0 and e2.imp_estimacion>0 and e2.imp_estimacion is not null  group by e2.id

union all
SELECT  a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Nomina Destajista' as Concepto, e3.total, concat('Semana: ',e3.semana,' NOMI-',e3.id,' - ',f3.nombre,' ',f3.paterno,' ',f3.materno ) as info,e3.fecha FROM constru_cuentas_cp a
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
left join constru_bit_nominadest e3 on e3.id_cc=d.id aND e3.id_obra='$id_obra' and e3.estatus=1
left join constru_info_tdo f3 on f3.id_alta=e3.id_dest
where d.id>0 and e3.total>0 and e3.total is not null  group by e3.id 

union all
SELECT  a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Nomina Tecnicos' as Concepto, e4.total,concat('NOMITEC-',e4.id),e4.fecha FROM constru_cuentas_cp a
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
left join constru_bit_nominaca e4 on e4.id_cc=d.id aND e4.id_obra='$id_obra' and e4.estatus=1
where d.id>0 and e4.total>0 and e4.total is not null  group by e4.id 

union all
SELECT  a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Salida Materiales' as Concepto, sum(xb.salio*xd.precio_compra),concat('ID SALIDA-',e5.id),e5.fecha FROM constru_cuentas_cp a
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
left join constru_bit_salidas e5 on e5.id_cc=d.id aND e5.id_obra='$id_obra'
left join constru_salida_almacen xb on xb.id_bit_salida=e5.id
left join constru_requis xc on xc.id=xb.id_req
left join constru_requisiciones xd on xd.id_requi=xc.id AND xd.id_clave=xb.id_insumo
where d.id>0 group by e5.id 

union all
SELECT  a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Caja Chica' as Concepto, e6.val_fact as total,concat('Semana: ',dd.semana,' ESTCHICA-',e6.id,' ',e6.concepto,' Factura: ',e6.factura),dd.fecha FROM constru_cuentas_cp a
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
left join constru_estimaciones_chica e6 on e6.id_cc=d.id aND e6.id_obra='$id_obra' and e6.id_bit_chica>0
left join constru_estimaciones_bit_chica dd on dd.id=e6.id_bit_chica and dd.estatus=1
where d.id>0 and e6.val_fact>0 and e6.val_fact is not null AND dd.id is not null  group by e6.id 

order by cpid, ccid, costoid, cargoid, Concepto, fecha desc;";
$result = $mysqli->query($SQL);

@$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

$idantes=0;
while($row = $result->fetch_array()) {

    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['Costo_Proyecto'],$row['Centro_Costo'],$row['Costo'],$row['Cargo'],$row['Concepto'],$row['info'],substr($row['fecha'],0,10),$row['imp_estimacion']);
    $i++;
}        
echo json_encode($responce);
?>