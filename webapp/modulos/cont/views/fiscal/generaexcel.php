<?php
$_REQUEST['cont'] = str_replace("<img id=\"logo_empresa\" src=\"", "<b style='color:#FFFFFF;'>", $_REQUEST['cont']);
$_REQUEST['cont'] = str_replace("\" height=\"55\">", "</b>", $_REQUEST['cont']);

if(!$_REQUEST['name']){
	$_REQUEST['name']="reporte";
}else{
	$_REQUEST['name'] = str_replace(" ","",$_REQUEST['name']);
	$_REQUEST['name'] = str_replace(".","",$_REQUEST['name']);
}

$mimeType = 'application/excel';
header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename='.$_REQUEST['name'].".xls");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');   
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
$_REQUEST['cont'] = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
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
   ' . $_REQUEST['cont'] . '
</body></html>';

echo utf8_decode($_REQUEST['cont']);

//Nuevo Commit
?>
