<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

include 'Classes/PHPExcel/IOFactory.php';
$file =$_POST['file'];
$presunom =$_POST['presunom'];
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
$highestColumn = 'E'; //$sheet->getHighestColumn();

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
    if($start>0){
        $cad.="(0,0,1,'".$mysqli->real_escape_string($rowData[0][0])."','".$mysqli->real_escape_string($rowData[0][1])."','".$mysqli->real_escape_string($rowData[0][2])."','".$rowData[0][3]."','".$rowData[0][4]."','".$rowData[0][4]."',".$id_presupuesto.",".$id_obra."),";

        if($x==$limit*($multi+1)){
            $cad = substr($cad, 0, -1);
            $listaimp[$multi]['string']=$cad;
            $multi++;
            $cad='';
        }
        $x++;
    }

    if(strtoupper(trim($rowData[0][0]))=='CLAVE'){
        $start=1;
    }

}

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

?>