<?php
date_default_timezone_set('America/Mexico_City');
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

include 'Classes/PHPExcel/IOFactory.php';
$file =$_POST['file'];
$selpobra =$_POST['selpobra'];
$inputFileName = 'uploads/'.$file;


include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = 'M'; //$sheet->getHighestColumn();

//$i=$mysqli->query("INSERT INTO constru_presupuesto (nombre,id_obra,archivo) VALUES ('$presunom','$id_obra','$file');");

$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
if($result->num_rows>0) {
    $row = $result->fetch_array();
    $id_presupuesto=$row['id'];
}else{
    exit();
}


$cad='';
$limit=1000;
$multi=0;
$x=1;
$start=0;
$listaimp=array();
for ($row = 0; $row <= $highestRow; $row++){ 
    $rowData = $sheet->rangeToArray('A'.$row.':'. $highestColumn . $row, NULL, TRUE, FALSE);

    $id =  $rowData[0][1];
    $fecha_inicio =  $rowData[0][9];



    if($id>0){
        $fecha_inicio = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($rowData[0][9]));

        if($selpobra=='d'){
            $duracion =  $rowData[0][10];
            $rendimiento =  $rowData[0][11];
        }
        if($selpobra=='r'){
            $duracion =  $rowData[0][11];
            $rendimiento =  $rowData[0][10];
        }

        
        $SQL= "UPDATE constru_asignaciones SET po_fecha='$fecha_inicio', po_dias='$duracion', po_rendimiento='$rendimiento' WHERE id='$id' AND id_obra='$id_obra';";
        $mysqli->query($SQL);

    }


}

$fecha=date('Y-m-d H:i:s');

$mysqli->query("INSERT INTO constru_uploadPO (presupuestoxls, opcion, fecha, id_obra) VALUES ('$inputFileName','$selpobra','$fecha','$id_obra');");

echo 1;

/*
    $cad = substr($cad, 0, -1);
    $listaimp[$multi]['string']=$cad;


foreach ($listaimp as $key => $e) {
    $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$e['string'].";");
}

$result = $mysqli->query("SELECT count(*) total FROM constru_recurso WHERE id_presupuesto='$id_presupuesto';");
$row = $result->fetch_array();
$total=$row['total'];

if($total>0){
    echo 1;
}
*/
?>