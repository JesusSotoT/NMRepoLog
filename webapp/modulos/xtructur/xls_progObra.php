<?php
include_once("../../netwarelog/webconfig.php");
$db = mysql_connect($servidor, $usuariobd, $clavebd)
or die("Connection Error: " . mysql_error());
mysql_select_db($bd) or die("Error conecting to db.");
mysql_query("set names 'utf8'");

if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$SQL = "SELECT obra FROM constru_generales WHERE id='$id_obra';";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_assoc($result); 
$nombreobra= $row['obra'];


$fecha=time();
$mimeType = 'application/excel';
header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename=progObra_'.$fecha.".xls");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');   
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');


$option=$_GET['id'];
$filaTH='';
$fila=3;
if($option==1){
    $por='rendimiento';
    $filaTH='<th>NATURALEZA</th>
    <th>ID</th>
    <th>CLAVE</th>
    <th colspan="4">DESCRIPCIÓN</th>
    <th>UNIDAD</th>
    <th>CANTIDAD</th>
    <th>FECHA DE INICIO</th>
    <th>RENDIMIENTO (JORNAL) </th>
    <th>DURACIÓN (DIAS) </th>
    <th>P. U. </th><th>IMPORTE</th>';

    $SQL = "SELECT a.id id0, b.id id1, c.id id2, d.id id3, da.partida as prtnom, ba.especialidad as espnom, a.codigo Agrupador, a.nombre anom, b.codigo Area, b.nombre arnom, c.codigo Especialidad,  d.codigo Partida, d.nombre prtnomv, e.*, (e.unidad*e.precio_venta) as importet, e.pu_destajo as pdes, e.pu_subcontrato as psub, a.id as agrid, b.id as espid, c.id as areid, d.id as parid, e.id as recid, g.id as idasign
FROM constru_agrupador a 
left join constru_especialidad b on b.id_agrupador=a.id
left join constru_area c on c.id_especialidad=b.id
left join constru_cat_especialidad ba on ba.id=c.id_cat_especialidad
left join constru_partida d on d.id_area=c.id
left join constru_cat_partidas da on da.id=d.id_cat_partida
left join constru_asignaciones g on g.id_partida=d.id AND g.id_obra='$id_obra'
left join constru_recurso e on e.id=g.id_recurso AND e.id_obra='$id_obra'
where 1=1  AND a.id_obra='$id_obra' AND a.borrado=0 AND c.borrado=0 AND d.borrado=0 AND da.borrado=0 AND e.borrado=0 
ORDER BY a.id, b.id, c.id, d.id asc, g.id, e.id;";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $cuerpo='';
    
    $operacion1='';
    $opercionimporte=0;

    $escabeza=array();
    $escabeza[0]=0;
    $escabeza[1]=0;
    $escabeza[2]=0;
    $escabeza[3]=0;
    while($row = mysql_fetch_assoc($result)) {
        /*
        if($row['unidtext']==''){
            $bg=' style="background-color:#e3e3e3;" ';
            $row['unidad']='';
            $row['precio_costo']='';
            $opercionimporte='';
            $operacion1='';

        }else{
            $bg=' style="background-color:#f8f8f8;" ';
            if($row['unidad']>0){
                $operacion1='=(H'.$fila.'*J'.$fila.')';
                $opercionimporte='=(H'.$fila.'*L'.$fila.')';
            }
        }
*/
        $bg=' style="background-color:#e3e3e3;" ';
        

        if($escabeza[0]!=$row['id0']){
            $escabeza[0]=$row['id0'];

            $cuerpo.='<tr style="background-color:#b3b3b3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Agrupador: '.$row['anom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        if($escabeza[1]!=$row['id1']){
            $escabeza[1]=$row['id1'];

            $cuerpo.='<tr style="background-color:#c3c3c3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Area: '.$row['arnom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        if($escabeza[2]!=$row['id2']){
            $escabeza[2]=$row['id2'];

            $cuerpo.='<tr style="background-color:#d3d3d3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Especialidad: '.$row['espnom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        if($escabeza[3]!=$row['id3']){
            $escabeza[3]=$row['id3'];

            $cuerpo.='<tr style="background-color:#e3e3e3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Partida: '.$row['prtnom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        $operacion1='=(I'.$fila.'*K'.$fila.')';
        $opercionimporte='=(I'.$fila.'*M'.$fila.')';

        $bg=' style="background-color:#f8f8f8;" ';
        $cuerpo.='<tr '.$bg.' >';
        $cuerpo.='<td >'.$row['naturaleza'].'</td>';
        $cuerpo.='<td>'.$row['idasign'].'</td>';
        $cuerpo.='<td>'.$row['codigo'].'</td>';
        $cuerpo.='<td colspan="4">'.$row['descripcion'].'</td>';
        $cuerpo.='<td>'.$row['unidtext'].'</td>';
        $cuerpo.='<td>'.$row['unidad'].'</td>';
        $cuerpo.='<td style="mso-number-format:yyyy-mm-dd"></td>';
        $cuerpo.='<td></td>';
        $cuerpo.='<td>'.$operacion1.'</td>';
        $cuerpo.='<td>'.$row['precio_costo'].'</td>';
        $cuerpo.='<td>'.$opercionimporte.'</td>';
        $cuerpo.='</tr>';

        $fila++;
    }

}else{
    $por='duracion';
    $filaTH='<th>NATURALEZA</th>
    <th>ID</th>
    <th>CLAVE</th>
    <th colspan="4">DESCRIPCIÓN</th>
    <th>UNIDAD</th>
    <th>CANTIDAD</th>
    <th>FECHA DE INICIO</th>
    <th>DURACIÓN (DIAS) </th>
    <th>RENDIMIENTO (JORNAL) </th>
    <th>P. U. </th>
    <th>IMPORTE</th>';

    $SQL = "SELECT a.id id0, b.id id1, c.id id2, d.id id3, da.partida as prtnom, ba.especialidad as espnom, a.codigo Agrupador, a.nombre anom, b.codigo Area, b.nombre arnom, c.codigo Especialidad,  d.codigo Partida, d.nombre prtnomv, e.*, (e.unidad*e.precio_venta) as importet, e.pu_destajo as pdes, e.pu_subcontrato as psub, a.id as agrid, b.id as espid, c.id as areid, d.id as parid, e.id as recid, g.id as idasign
FROM constru_agrupador a 
left join constru_especialidad b on b.id_agrupador=a.id
left join constru_area c on c.id_especialidad=b.id
left join constru_cat_especialidad ba on ba.id=c.id_cat_especialidad
left join constru_partida d on d.id_area=c.id
left join constru_cat_partidas da on da.id=d.id_cat_partida
left join constru_asignaciones g on g.id_partida=d.id AND g.id_obra='$id_obra'
left join constru_recurso e on e.id=g.id_recurso AND e.id_obra='$id_obra'
where 1=1  AND a.id_obra='$id_obra' AND a.borrado=0 AND c.borrado=0 AND d.borrado=0 AND da.borrado=0 AND e.borrado=0 
ORDER BY a.id, b.id, c.id, d.id asc, g.id, e.id;";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $cuerpo='';

    $operacion1='';
    $opercionimporte=0;

    $escabeza=array();
    $escabeza[0]=0;
    $escabeza[1]=0;
    $escabeza[2]=0;
    $escabeza[3]=0;

    while($row = mysql_fetch_assoc($result)) {
        /*
        if($row['unidtext']==''){
            $bg=' style="background-color:#e3e3e3;" ';
            $row['unidad']='';
            $row['precio_costo']='';
            $opercionimporte='';
            $operacion1='';

        }else{
            $bg=' style="background-color:#f8f8f8;" ';
            if($row['unidad']>0){
                $operacion1='=(J'.$fila.'/H'.$fila.')';
                $opercionimporte='=(H'.$fila.'*L'.$fila.')';
            }
        }
        */

        $bg=' style="background-color:#e3e3e3;" ';
        

        if($escabeza[0]!=$row['id0']){
            $escabeza[0]=$row['id0'];

            $cuerpo.='<tr style="background-color:#b3b3b3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Agrupador: '.$row['anom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        if($escabeza[1]!=$row['id1']){
            $escabeza[1]=$row['id1'];

            $cuerpo.='<tr style="background-color:#c3c3c3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Area: '.$row['arnom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        if($escabeza[2]!=$row['id2']){
            $escabeza[2]=$row['id2'];

            $cuerpo.='<tr style="background-color:#d3d3d3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Especialidad: '.$row['espnom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        if($escabeza[3]!=$row['id3']){
            $escabeza[3]=$row['id3'];

            $cuerpo.='<tr style="background-color:#e3e3e3;" >';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td colspan="4">Partida: '.$row['prtnom'].'</td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='<td></td>';
            $cuerpo.='</tr>';

            $fila++;

        }

        $operacion1='=(K'.$fila.'/I'.$fila.')';
        $opercionimporte='=(I'.$fila.'*M'.$fila.')';
        $bg=' style="background-color:#f8f8f8;" ';

        $cuerpo.='<tr '.$bg.' >';
        $cuerpo.='<td>'.$row['naturaleza'].'</td>';
        $cuerpo.='<td>'.$row['idasign'].'</td>';
        $cuerpo.='<td>'.$row['codigo'].'</td>';
        $cuerpo.='<td colspan="4">'.$row['descripcion'].'</td>';
        $cuerpo.='<td>'.$row['unidtext'].'</td>';
        $cuerpo.='<td>'.$row['unidad'].'</td>';
        $cuerpo.='<td style="mso-number-format:yyyy-mm-dd"></td>';
        $cuerpo.='<td></td>';
        $cuerpo.='<td>'.$operacion1.'</td>';
        $cuerpo.='<td>'.$row['precio_costo'].'</td>';
        $cuerpo.='<td>'.$opercionimporte.'</td>';
        $cuerpo.='</tr>';

        $fila++;
    }
}


$xls = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>

<body>
   <table border=1 bordercolor=#bbbbbb>
   <tr>
   <th colspan="13" height="50">Programa de obra por '.$por.' - '.$nombreobra.'</th>
   </tr>
        <tr>
            '.$filaTH.'
        </tr>
        '.$cuerpo.'
   <table>
</body></html>';

echo utf8_decode($xls);

//Nuevo Commit
?>
