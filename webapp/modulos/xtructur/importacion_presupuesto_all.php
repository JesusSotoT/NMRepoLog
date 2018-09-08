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
$highestColumn = 'J'; //$sheet->getHighestColumn();

$i=$mysqli->query("INSERT INTO constru_presupuesto (nombre,id_obra,archivo) VALUES ('$presunom','$id_obra','$file');");

if($i==1){
    $id_presupuesto=$mysqli->insert_id;
}else{

    exit();
}

$cad='';
$limit=1000;
$multi=0;
$x=1;
$start=0;
$listaimp=array();
$arreglo=array();
$arr_cad=array();


$mysqli->query("UPDATE constru_agrupador SET borrado=1 WHERE id_obra='$id_obra';");
$mysqli->query("UPDATE constru_especialidad SET borrado=1 WHERE id_obra='$id_obra';");
$mysqli->query("UPDATE constru_area SET borrado=1 WHERE id_obra='$id_obra';");
$mysqli->query("UPDATE constru_partida SET borrado=1 WHERE id_obra='$id_obra';");
$mysqli->query("DELETE FROM constru_asignaciones WHERE id_obra='$id_obra';");
$mysqli->query("DELETE FROM constru_vol_tope WHERE id_obra='$id_obra';");


$cad='';
for ($row = 0; $row <= $highestRow; $row++){ 
    $rowData = $sheet->rangeToArray('A'.$row.':'. $highestColumn . $row, NULL, TRUE, FALSE);
    if($start>0){
        $agrupador=$mysqli->real_escape_string($rowData[0][0]);
        $area=$mysqli->real_escape_string($rowData[0][1]);
        $especialidad=$mysqli->real_escape_string($rowData[0][2]);
        $partida=$mysqli->real_escape_string($rowData[0][3]);
        
        if($agrupador!=''){
            if (array_key_exists($agrupador, $arreglo)) {
                if (array_key_exists($area, $arreglo[$agrupador])) {
                    if (array_key_exists($especialidad, $arreglo[$agrupador][$area])) {
                        if (array_key_exists($partida, $arreglo[$agrupador][$area][$especialidad])) {

                            $last_id2=$arreglo[$agrupador][$area]['SQL_ID_SQL'];
                            $last_id3=$arreglo[$agrupador][$area][$especialidad]['SQL_ID_SQL'];
                            $last_id4=$arreglo[$agrupador][$area][$especialidad][$partida]['SQL_ID_SQL'];

                            $cad="(0,0,1,'".$mysqli->real_escape_string($rowData[0][4])."','".$mysqli->real_escape_string($rowData[0][5])."','".$mysqli->real_escape_string($rowData[0][6])."','".$rowData[0][7]."','".$rowData[0][8]."','".$rowData[0][8]."',".$id_presupuesto.",".$id_obra.")";

                            $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$cad.";");
                            $last_idclave = $mysqli->insert_id;

                            $mysqli->query("INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida,id_area) VALUES ('$id_obra','$last_idclave','$last_id4','$last_id2'); ");


                            $mysqli->query("INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,id_partida,vol_tope) VALUES ('$id_obra','$last_idclave','$last_id2','$last_id4','".$rowData[0][7]."'); ");


                            //$arreglo[$agrupador][$area][$especialidad][$partida][]=array();
                            $arreglo[$agrupador][$area][$especialidad][$partida][]=array('4'=>$mysqli->real_escape_string($rowData[0][4]), '5'=>$mysqli->real_escape_string($rowData[0][5]), '6'=>$mysqli->real_escape_string($rowData[0][6]), '7'=>$rowData[0][7], '8'=>$rowData[0][8], '9'=>$rowData[0][8]);
                        }else{


                            $last_id2=$arreglo[$agrupador][$area]['SQL_ID_SQL'];
                            $last_id3=$arreglo[$agrupador][$area][$especialidad]['SQL_ID_SQL'];

                            $mysqli->query("INSERT INTO constru_cat_partidas (partida, borrado, id_obra) VALUES ('$partida','0','$id_obra');");
                            $last_id_catarea = $mysqli->insert_id;
                            
                            $mysqli->query("INSERT INTO constru_partida (id_area, nombre, codigo, borrado, id_cat_partida, id_obra) VALUES ('$last_id3','$partida','44','0','$last_id_catarea','$id_obra');");
                            $last_id4 = $mysqli->insert_id;
                            $codigo='PRT-'.$last_id4;
                            $mysqli->query("UPDATE constru_partida SET codigo='$codigo' WHERE id='$last_id4';");

                            $arreglo[$agrupador][$area][$especialidad]['SQL_ID_SQL']=$last_id3;
                            $arreglo[$agrupador][$area][$especialidad][$partida]['SQL_ID_SQL']=$last_id4;

                            $cad="(0,0,1,'".$mysqli->real_escape_string($rowData[0][4])."','".$mysqli->real_escape_string($rowData[0][5])."','".$mysqli->real_escape_string($rowData[0][6])."','".$rowData[0][7]."','".$rowData[0][8]."','".$rowData[0][8]."',".$id_presupuesto.",".$id_obra.")";

                            $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$cad.";");
                            $last_idclave = $mysqli->insert_id;

                            $mysqli->query("INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida,id_area) VALUES ('$id_obra','$last_idclave','$last_id4','$last_id2'); ");


                            $mysqli->query("INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,id_partida,vol_tope) VALUES ('$id_obra','$last_idclave','$last_id2','$last_id4','".$rowData[0][7]."'); ");

                            //$arreglo[$agrupador][$area][$especialidad][$partida][]=array();
                            $arreglo[$agrupador][$area][$especialidad][$partida][]=array('4'=>$mysqli->real_escape_string($rowData[0][4]), '5'=>$mysqli->real_escape_string($rowData[0][5]), '6'=>$mysqli->real_escape_string($rowData[0][6]), '7'=>$rowData[0][7], '8'=>$rowData[0][8], '9'=>$rowData[0][8]);
                        }
                    }else{

                        $last_id2=$arreglo[$agrupador][$area]['SQL_ID_SQL'];


                        $mysqli->query("INSERT INTO constru_cat_especialidad (especialidad, borrado, id_obra) VALUES ('$especialidad','0','$id_obra');");
                        $last_id_catesp = $mysqli->insert_id;
                        
                        $mysqli->query("INSERT INTO constru_area (id_especialidad, nombre, codigo, borrado, id_cat_especialidad) VALUES ('$last_id2','$especialidad','33','0','$last_id_catesp');");
                        $last_id3 = $mysqli->insert_id;
                        $codigo='ESP-'.$last_id3;
                        $mysqli->query("UPDATE constru_area SET codigo='$codigo' WHERE id='$last_id3';");

                        $mysqli->query("INSERT INTO constru_cat_partidas (partida, borrado, id_obra) VALUES ('$partida','0','$id_obra');");
                        $last_id_catarea = $mysqli->insert_id;
                        
                        $mysqli->query("INSERT INTO constru_partida (id_area, nombre, codigo, borrado, id_cat_partida, id_obra) VALUES ('$last_id3','$partida','44','0','$last_id_catarea','$id_obra');");
                        $last_id4 = $mysqli->insert_id;
                        $codigo='PRT-'.$last_id4;
                        $mysqli->query("UPDATE constru_partida SET codigo='$codigo' WHERE id='$last_id4';");

        
                        $arreglo[$agrupador][$area][$especialidad]['SQL_ID_SQL']=$last_id3;
                        $arreglo[$agrupador][$area][$especialidad][$partida]['SQL_ID_SQL']=$last_id4;

                        $cad="(0,0,1,'".$mysqli->real_escape_string($rowData[0][4])."','".$mysqli->real_escape_string($rowData[0][5])."','".$mysqli->real_escape_string($rowData[0][6])."','".$rowData[0][7]."','".$rowData[0][8]."','".$rowData[0][8]."',".$id_presupuesto.",".$id_obra.")";

                        $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$cad.";");
                        $last_idclave = $mysqli->insert_id;

                        $mysqli->query("INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida,id_area) VALUES ('$id_obra','$last_idclave','$last_id4','$last_id2'); ");


                        $mysqli->query("INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,id_partida,vol_tope) VALUES ('$id_obra','$last_idclave','$last_id2','$last_id4','".$rowData[0][7]."'); ");

                        $arreglo[$agrupador][$area][$especialidad][$partida][]=array('4'=>$mysqli->real_escape_string($rowData[0][4]), '5'=>$mysqli->real_escape_string($rowData[0][5]), '6'=>$mysqli->real_escape_string($rowData[0][6]), '7'=>$rowData[0][7], '8'=>$rowData[0][8], '9'=>$rowData[0][8]);
                    }
                }else{

                    $last_id=$arreglo[$agrupador]['SQL_ID_SQL'];

                    $mysqli->query("INSERT INTO constru_especialidad (id_agrupador, nombre, codigo, borrado) VALUES ('$last_id','$area','22','0');");
                    $last_id2 = $mysqli->insert_id;
                    $codigo='AR-'.$last_id2;
                    $mysqli->query("UPDATE constru_especialidad SET codigo='$codigo' WHERE id='$last_id2';");

                    $mysqli->query("INSERT INTO constru_cat_especialidad (especialidad, borrado, id_obra) VALUES ('$especialidad','0','$id_obra');");
                    $last_id_catesp = $mysqli->insert_id;
                    
                    $mysqli->query("INSERT INTO constru_area (id_especialidad, nombre, codigo, borrado, id_cat_especialidad) VALUES ('$last_id2','$especialidad','33','0','$last_id_catesp');");
                    $last_id3 = $mysqli->insert_id;
                    $codigo='ESP-'.$last_id3;
                    $mysqli->query("UPDATE constru_area SET codigo='$codigo' WHERE id='$last_id3';");

                    $mysqli->query("INSERT INTO constru_cat_partidas (partida, borrado, id_obra) VALUES ('$partida','0','$id_obra');");
                    $last_id_catarea = $mysqli->insert_id;
                    
                    $mysqli->query("INSERT INTO constru_partida (id_area, nombre, codigo, borrado, id_cat_partida, id_obra) VALUES ('$last_id3','$partida','44','0','$last_id_catarea','$id_obra');");
                    $last_id4 = $mysqli->insert_id;
                    $codigo='PRT-'.$last_id4;
                    $mysqli->query("UPDATE constru_partida SET codigo='$codigo' WHERE id='$last_id4';");

       
                    $arreglo[$agrupador][$area]['SQL_ID_SQL']=$last_id2;
                    $arreglo[$agrupador][$area][$especialidad]['SQL_ID_SQL']=$last_id3;
                    $arreglo[$agrupador][$area][$especialidad][$partida]['SQL_ID_SQL']=$last_id4;


                    $cad="(0,0,1,'".$mysqli->real_escape_string($rowData[0][4])."','".$mysqli->real_escape_string($rowData[0][5])."','".$mysqli->real_escape_string($rowData[0][6])."','".$rowData[0][7]."','".$rowData[0][8]."','".$rowData[0][8]."',".$id_presupuesto.",".$id_obra.")";

                    $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$cad.";");
                    $last_idclave = $mysqli->insert_id;

                    $mysqli->query("INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida,id_area) VALUES ('$id_obra','$last_idclave','$last_id4','$last_id2'); ");


                    $mysqli->query("INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,id_partida,vol_tope) VALUES ('$id_obra','$last_idclave','$last_id2','$last_id4','".$rowData[0][7]."'); ");

                    $arreglo[$agrupador][$area][$especialidad][$partida][]=array('4'=>$mysqli->real_escape_string($rowData[0][4]), '5'=>$mysqli->real_escape_string($rowData[0][5]), '6'=>$mysqli->real_escape_string($rowData[0][6]), '7'=>$rowData[0][7], '8'=>$rowData[0][8], '9'=>$rowData[0][8]);

                }
            }else{

                $mysqli->query("INSERT INTO constru_agrupador (id_presupuesto, nombre, codigo, id_obra) VALUES ('$id_presupuesto','$agrupador','11','$id_obra');");
                $last_id = $mysqli->insert_id;
                $codigo='A-'.$last_id;
                $mysqli->query("UPDATE constru_agrupador SET codigo='$codigo' WHERE id='$last_id';");

                $mysqli->query("INSERT INTO constru_especialidad (id_agrupador, nombre, codigo, borrado) VALUES ('$last_id','$area','22','0');");
                $last_id2 = $mysqli->insert_id;
                $codigo='AR-'.$last_id2;
                $mysqli->query("UPDATE constru_especialidad SET codigo='$codigo' WHERE id='$last_id2';");


                $mysqli->query("INSERT INTO constru_cat_especialidad (especialidad, borrado, id_obra) VALUES ('$especialidad','0','$id_obra');");
                $last_id_catesp = $mysqli->insert_id;
                
                $mysqli->query("INSERT INTO constru_area (id_especialidad, nombre, codigo, borrado, id_cat_especialidad) VALUES ('$last_id2','$especialidad','33','0','$last_id_catesp');");
                $last_id3 = $mysqli->insert_id;
                $codigo='ESP-'.$last_id3;
                $mysqli->query("UPDATE constru_area SET codigo='$codigo' WHERE id='$last_id3';");

                $mysqli->query("INSERT INTO constru_cat_partidas (partida, borrado, id_obra) VALUES ('$partida','0','$id_obra');");
                $last_id_catarea = $mysqli->insert_id;
                
                $mysqli->query("INSERT INTO constru_partida (id_area, nombre, codigo, borrado, id_cat_partida, id_obra) VALUES ('$last_id3','$partida','44','0','$last_id_catarea','$id_obra');");
                $last_id4 = $mysqli->insert_id;
                $codigo='PRT-'.$last_id4;
                $mysqli->query("UPDATE constru_partida SET codigo='$codigo' WHERE id='$last_id4';");

                $arreglo[$agrupador]['SQL_ID_SQL']=$last_id;
                $arreglo[$agrupador][$area]['SQL_ID_SQL']=$last_id2;
                $arreglo[$agrupador][$area][$especialidad]['SQL_ID_SQL']=$last_id3;
                $arreglo[$agrupador][$area][$especialidad][$partida]['SQL_ID_SQL']=$last_id4;

                $cad="(0,0,1,'".$mysqli->real_escape_string($rowData[0][4])."','".$mysqli->real_escape_string($rowData[0][5])."','".$mysqli->real_escape_string($rowData[0][6])."','".$rowData[0][7]."','".$rowData[0][8]."','".$rowData[0][8]."',".$id_presupuesto.",".$id_obra.")";

                $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$cad.";");
                $last_idclave = $mysqli->insert_id;

                $mysqli->query("INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida,id_area) VALUES ('$id_obra','$last_idclave','$last_id4','$last_id2'); ");


                $mysqli->query("INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,id_partida,vol_tope) VALUES ('$id_obra','$last_idclave','$last_id2','$last_id4','".$rowData[0][7]."'); ");


                $arreglo[$agrupador][$area][$especialidad][$partida][]=array('4'=>$mysqli->real_escape_string($rowData[0][4]), '5'=>$mysqli->real_escape_string($rowData[0][5]), '6'=>$mysqli->real_escape_string($rowData[0][6]), '7'=>$rowData[0][7], '8'=>$rowData[0][8], '9'=>$rowData[0][8]);

            }




        }


        // $cad.="(0,0,1,'".$mysqli->real_escape_string($rowData[0][0])."','".$mysqli->real_escape_string($rowData[0][1])."','".$mysqli->real_escape_string($rowData[0][2])."','".$rowData[0][3]."','".$rowData[0][4]."','".$rowData[0][4]."',".$id_presupuesto.",".$id_obra."),";

        if($x==$limit*($multi+1)){
            $cad = substr($cad, 0, -1);
            $listaimp[$multi]['string']=$cad;
            $multi++;
            $cad='';
        }
        $x++;
    }

    if(strtoupper(trim($rowData[0][0]))=='AGRUPADOR'){
        $start=1;
    }

}


echo 1;
exit();

foreach ($listaimp as $key => $e) {
    //$mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$e['string'].";");
}


// $SQL = "INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida,id_area) VALUES ".$cad."; ";
//     mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

//     $SQL = "INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,id_partida,vol_tope,borrado) VALUES ".$cadtope."; ";
//     mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

$cad = substr($cad, 0, -1);
$mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, codigo, descripcion, unidtext, unidad, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ".$cad.";");

echo json_encode($arreglo);
exit();

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
}else{
    $mysqli->query("DELETE FROM constru_presupuesto WHERE id_presupuesto='id_presupuesto';");
    echo 0;
}

?>