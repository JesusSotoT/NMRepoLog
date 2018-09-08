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
$sheet = $objPHPExcel->getActiveSheet(); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = 'Y'; //$sheet->getHighestColumn();

//$i=$mysqli->query("INSERT INTO constru_presupuesto (nombre,id_obra,archivo) VALUES ('$presunom','$id_obra','$file');");



$cad='';
$limit=1000;
$multi=0;
$x=1;
$start=0;
$listaimp=array();
for ($row = 0; $row <= $highestRow; $row++){ 
    $rowData = $sheet->rangeToArray('A'.$row.':'. $highestColumn . $row, NULL, TRUE, FALSE);

    $estatus =  $rowData[0][0];

    if($start>1 && $estatus!=''){
        $f_captura = $rowData[0][1];
        $f_ingreso = $rowData[0][2];

        $discredito =  $rowData[0][3];
        $limcredito =  $rowData[0][4];
        $tipo_alta =  $rowData[0][5];
        $razon_social_sp =  $rowData[0][6];
        $rfc_sp =  $rowData[0][7];
        $calle_sp =  $rowData[0][8];
        $colonia_sp =  $rowData[0][9];
        $cp_sp =  $rowData[0][10];
        $municipio_sp =  $rowData[0][11];
        $estado_sp =  $rowData[0][12];
        $tel_emp_sp =  $rowData[0][13];
        $paterno_sp =  $rowData[0][14];
        $materno_sp =  $rowData[0][15];
        $nombres_sp =  $rowData[0][16];
        $tel_personal_sp =  $rowData[0][17];
        $correo_sp =  $rowData[0][18];

        


        $importeContrato =  $rowData[0][19];
        $ade1 =  $rowData[0][20];
        $ade2 =  $rowData[0][21];
        $ade3 =  $rowData[0][22];
        $anticipo =  $rowData[0][23];
        $fondo =  $rowData[0][24];

        $mysqli->query("INSERT INTO constru_altas (id_obra, id_tipo_alta, estatus, f_captura, f_ingreso, id_responsable, id_agrupador, id_especialidad, id_area, id_partida, id_depto, tipo_alta, oc_inst, id_familia, id_categoria) VALUES ('$id_obra',4,'$estatus','$f_captura','$f_ingreso',0,0,0,0,0,0,'$tipo_alta','',0,0);");

        $id_alta = $mysqli->insert_id;
        if($id_alta>0){

            $mysqli->query("INSERT INTO constru_info_sp (id_alta, razon_social_sp, rfc_sp, calle_sp, colonia_sp, cp_sp, municipio_sp, estado_sp, tel_emp_sp, paterno_sp, materno_sp, nombres_sp, tel_personal_sp, correo_sp, dias_credito, limite_credito,imp_cont,ade1,ade2,ade3,anticipo,por_fondo_garantia) VALUES ('$id_alta','$razon_social_sp','$rfc_sp','$calle_sp','$colonia_sp','$cp_sp','$municipio_sp','$estado_sp','$tel_emp_sp','$paterno_sp','$materno_sp','$nombres_sp','$tel_personal_sp','$correo_sp','$discredito','$limcredito','$importeContrato','$ade1','$ade2','$ade3','$anticipo','$fondo');");

        }

    }

$start++;
}



echo 1;


?>