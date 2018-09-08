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
$highestColumn = 'S'; //$sheet->getHighestColumn();


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

        $f_captura = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][1])); 
        $f_ingreso = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][2])); 

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

        $imp_cont =  $rowData[0][19];
        $ade1 =  $rowData[0][20];
        $ade2 =  $rowData[0][21];
        $ade3 =  $rowData[0][22];
        $por_ant =  $rowData[0][23];
        $por_fond =  $rowData[0][24];

        if($tipo_alta=='Proveedor' || $tipo_alta=='Proveedores'){
            $ta=5;
        }else{
            $ta=4;
        }


        $mysqli->query("INSERT INTO constru_altas (id_obra, id_tipo_alta, estatus, f_captura, f_ingreso, id_responsable, id_agrupador, id_especialidad, id_area, id_partida, id_depto, tipo_alta, oc_inst, id_familia, id_categoria) VALUES ('$id_obra','$ta','$estatus','$f_captura','$f_ingreso',0,0,0,0,0,0,'$tipo_alta','',0,0);");

        $id_alta = $mysqli->insert_id;
        if($id_alta>0){

            $mysqli->query("INSERT INTO constru_info_sp (id_alta, razon_social_sp, rfc_sp, calle_sp, colonia_sp, cp_sp, municipio_sp, estado_sp, tel_emp_sp, paterno_sp, materno_sp, nombres_sp, tel_personal_sp, correo_sp, dias_credito, limite_credito, imp_cont, ade1,ade2,ade3,anticipo,por_fondo_garantia) VALUES ('$id_alta','$razon_social_sp','$rfc_sp','$calle_sp','$colonia_sp','$cp_sp','$municipio_sp','$estado_sp','$tel_emp_sp','$paterno_sp','$materno_sp','$nombres_sp','$tel_personal_sp','$correo_sp','$discredito','$limcredito','$imp_cont','$ade1','$ade2','$ade3','$por_ant','$por_fond');");


            $codigo='PROV-'.$id_alta;
        //$pais=1; //n
        //$estado=1; //n
        $municipios=1; //n
        $tipoTercero=0;
        $tipoTerceroOperacion=0;
        //$cuenta=1; //n
        $numidfiscal='';
        $nombrextranjero='';
        $nacionalidad='';
        $ivaretenido=0;
        $isretenido=0;
        $idtipoiva=0;
        $tipo=1;
        $beneficiario=0;
        $cuentaCliente=0;
        $nombre_contacto=$nombres_sp.' '.$paterno_sp.' '.$materno_sp;
        $nombre_contacto=trim($nombre_contacto);
        $nombre_comercial=$razon_social_sp;
        $tipoClas=0;
        $no_ext='';
        $no_int='';
        $saldo=0;
        $ciudad='';

        $dias_credito=0;
        $limite_credito=0;


        $mysqli->query("INSERT INTO mrp_proveedor (codigo,razon_social,rfc,telefono,email,web,diascredito,idtipotercero,idtipoperacion,numidfiscal,nombrextranjero,nacionalidad,ivaretenido,isretenido,idTasaPrvasumir,idtipoiva,idtipo,beneficiario_pagador,cuentacliente,nombre,nombre_comercial,clasificacion,limite_credito,status,calle,no_ext,no_int,cp,saldo,colonia,ciudad,id_xtructur) values ('".$codigo."','".$razon_social_sp."','".$rfc_sp."','".$tel_emp_sp."','".$correo_sp."','','".$dias_credito."','".$tipoTercero."','".$tipoTerceroOperacion."','".$numidfiscal."','".$nombrextranjero."','".$nacionalidad."','".$ivaretenido."','".$isretenido."','0','".$idtipoiva."','".$tipo."','".$beneficiario."','".$cuentaCliente."','".$nombre_contacto."','".$nombre_comercial."','".$tipoClas."','".$limite_credito."','-1','".$calle_sp."','".$no_ext."','".$no_int."','".$cp_sp."','".$saldo."','".$colonia_sp."','".$ciudad."','".$id_alta."');");



        }

    }

$start++;
}



echo 1;


?>