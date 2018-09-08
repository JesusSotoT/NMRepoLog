<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
include 'Classes/PHPExcel/IOFactory.php';
$file =$_POST['file'];
$idpro =$_POST['idpro'];
$idpre =$_POST['idpre'];
$inputFileName = 'uploads/'.$file;

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
$highestColumn = 'G'; //$sheet->getHighestColumn();

include('conexiondb.php');
$mysqli->query("set names 'utf8'");

$cad='';
$limit=1000;
$multi=0;
$x=1;
$start=0;
$listaimp=array();
for ($row = 0; $row <= $highestRow; $row++){ 
    $rowData = $sheet->rangeToArray('A'.$row.':'. $highestColumn . $row, NULL, TRUE, FALSE);
    if($start>0){
        $xsan1=str_replace("'","",$rowData[0][3]);
        $xsanf=str_replace(",","",$xsan1);

        $ysan1=str_replace("'","",$rowData[0][4]);
        $ysanf=str_replace(",","",$ysan1);

        $cad.="('$id_obra','Catalogo','".$mysqli->real_escape_string($rowData[0][0])."','".$mysqli->real_escape_string($rowData[0][1])."','".$mysqli->real_escape_string($rowData[0][2])."','".$mysqli->real_escape_string($xsanf)."','".$mysqli->real_escape_string($ysanf)."'),";

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
    //var_dump($listaimp);
    //exit();
//$e = substr($cad, 0, -1);
foreach ($listaimp as $key => $e) {
    //echo "INSERT INTO constru_insumos (id_obra, naturaleza, clave, descripcion, unidtext, unidad, precio) VALUES ".$e['string'].";";
    $mysqli->query("INSERT INTO constru_insumos (id_obra, naturaleza, clave, descripcion, unidtext, unidad, precio) VALUES ".$e['string'].";");
}
?>