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

$fecha=time();
$mimeType = 'application/excel';
header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename=expProv_'.$fecha.".xls");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');   
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');


$filaTH='';



    $filaTH='<th>Estatus</th>
    <th>Fecha de Captura</th>
    <th>Fecha de Ingreso</th>
    <th>Días de Credito</th>
    <th>Límite de Crédito</th>
    <th>Tipo</th>
    <th>Razón Social</th>
    <th>RFC</th>
    <th>Domicilio</th>
    <th>Colonia</th>
    <th>CP</th>
    <th>Municipio</th>
    <th>Estado</th>
    <th>Télefono Empresa</th>
    <th>Apellido Paterno</th>
    <th>Apellido Materno</th>
    <th>Nombres</th>
    <th>Teléfono Personal</th>
    <th>Correo</th>';

    $SQL = "SELECT concat('PROV-',a.id) as idpro, cpc.especialidad as especialidad, a.*, c.id as idc, c.razon_social_sp, c.rfc_sp, c.calle_sp, c.colonia_sp, c.cp_sp, c.municipio_sp, c.estado_sp, c.tel_emp_sp, c.paterno_sp, c.materno_sp, c.nombres_sp, c.tel_personal_sp, c.correo_sp, a.id_agrupador, pa.nombre nomagru, pb.nombre nomesp, pc.nombre nomare, pd.nombre nompar, c.dias_credito, c.limite_credito FROM constru_altas a 
LEFT JOIN constru_info_sp c on c.id_alta=a.id 
    LEFT JOIN constru_agrupador pa on pa.id=a.id_agrupador 
    LEFT JOIN constru_area pb on pb.id=a.id_especialidad
        LEFT JOIN constru_cat_especialidad cpc on cpc.id=a.oc_inst
    LEFT JOIN constru_especialidad pc on pc.id=a.id_area
    LEFT JOIN constru_partida pd on pd.id=a.id_especialidad
WHERE 1=1 AND a.id_obra='$id_obra' AND a.id_tipo_alta=5 AND a.borrado=0;";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    while($row = mysql_fetch_assoc($result)) {


 
        $bg=' style="background-color:#f8f8f8;" ';
        $cuerpo.='<tr>';
        $cuerpo.='<td>'.$row['estatus'].'</td>';
        $cuerpo.='<td style="mso-number-format:yyyy-mm-dd">'.$row['f_captura'].'</td>';
        $cuerpo.='<td style="mso-number-format:yyyy-mm-dd">'.$row['f_ingreso'].'</td>';
        $cuerpo.='<td>'.$row['dias_credito'].'</td>';
        $cuerpo.='<td>'.$row['limite_credito'].'</td>';
        $cuerpo.='<td>Proveedor</td>';
        $cuerpo.='<td>'.$row['razon_social_sp'].'</td>';
        $cuerpo.='<td>'.$row['rfc_sp'].'</td>';
        $cuerpo.='<td>'.$row['calle_sp'].'</td>';
        $cuerpo.='<td>'.$row['colonia_sp'].'</td>';
        $cuerpo.='<td>'.$row['cp_sp'].'</td>';
        $cuerpo.='<td>'.$row['municipio_sp'].'</td>';
        $cuerpo.='<td>'.$row['estado_sp'].'</td>';
        $cuerpo.='<td>'.$row['tel_emp_sp'].'</td>';
        $cuerpo.='<td>'.$row['paterno_sp'].'</td>';
        $cuerpo.='<td>'.$row['materno_sp'].'</td>';
        $cuerpo.='<td>'.$row['nombres_sp'].'</td>';
        $cuerpo.='<td>'.$row['tel_personal_sp'].'</td>';
        $cuerpo.='<td>'.$row['correo_sp'].'</td>';
        $cuerpo.='</tr>';

  
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
   <table table border=1 bordercolor=#bbbbbb>
        <tr>
            '.$filaTH.'
        </tr>
        '.$cuerpo.'
   <table>
</body></html>';

echo utf8_decode($xls);

//Nuevo Commit
?>
