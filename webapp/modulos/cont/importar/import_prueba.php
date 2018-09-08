<?php

$objFile = file_get_contents(dirname(__FILE__).'/polizas.txt');
$arrRows = explode("\n",$objFile);

require_once dirname(__FILE__).'/Excel/reader.php';

//Establecemos las cabeceras para un archivo xls
/*header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=polizas.xls");
header("Pragma: no-cache");
header("Expires: 0");*/

/*echo "<pre>";
print_r($arrRows);
echo "</pre>";*/

    //foreach($arrRows as $row){
echo("<table>");
for ($i=0; $i < count($arrRows) ; $i++) { 
	echo("<tr>");
    /*$strFld00 = trim(substr($arrRows[$i],0,3)); //Tipo registro
    $strFld01 = trim(substr($arrRows[$i],3,31)); //Cuenta
    $strFld02 = strtoupper(trim(substr($arrRows[$i],34,51))); //Descripcion ESP
    $strFld03 = strtoupper(trim(substr($arrRows[$i],85,51))); //Descripcion ENG
    $strFld04 = trim(substr($arrRows[$i],136,31)); //Padre
    $strFld05 = trim(substr($arrRows[$i],167,2)); //Tipo Cuenta
    $strFld06 = trim(substr($arrRows[$i],169,2)); //Status
    $strFld07 = trim(substr($arrRows[$i],171,2)); //Mayor, Subcuenta, Argupadora
    $strFld08 = trim(substr($arrRows[$i],173,2)); //Bancos --- Brincada
    $strFld09 = trim(substr($arrRows[$i],175,9)); //Fecha creacion
    $strFld10 = trim(substr($arrRows[$i],184,2)); //11???????
    $strFld11 = trim(substr($arrRows[$i],186,5)); //Moneda
    $strFld12 = trim(substr($arrRows[$i],191,5)); //Digito Agrupador SAT
    $strFld13 = trim(substr($arrRows[$i],196,2)); //????
    $strFld14 = trim(substr($arrRows[$i],198,6)); //????


    if($strFld00=="C"){

    	for ($x=0; $x < 14; $x++) { 
    		if($x <= 9)
    		{
    			echo("<td>".${'strFld0'.$x} ."</td>");
    		}else
    		{
    			echo("<td>".${'strFld'.$x} ."</td>");
    		}
    	}
    }
    echo("</tr>");*/

    $contPoliza =0;
    $Tipo = trim(substr($arrRows[$i],0,2)); //Tipo registro

    if($Tipo=="P")
    {
    	echo("<td>P</td>");
        $strPoliza01 = trim(substr($arrRows[$i],3,8)); //Cuenta
        $strPoliza02 = trim(substr($arrRows[$i],15,1)); //Descripcion ESP
        $strPoliza03 = trim(substr($arrRows[$i],21,5)); //Descripcion ENG
        $strPoliza04 = trim(substr($arrRows[$i],27,1)); //Padre
        $strPoliza05 = trim(substr($arrRows[$i],29,5)); //Tipo Cuenta
        $strPoliza06 = trim(substr($arrRows[$i],40,44)); //Status
        $strPoliza07 = trim(substr($arrRows[$i],141,2)); //Mayor, Subcuenta, Argupadora
        $strPoliza08 = trim(substr($arrRows[$i],144,1)); //Mayor, Subcuenta, Argupadora
        $strPoliza09 = trim(substr($arrRows[$i],146,1)); //Mayor, Subcuenta, Argupadora
        $strPoliza10 = trim(substr($arrRows[$i],148,36)); //Mayor, Subcuenta, Argupadora

        $Organizacion = 1;
        $Ejercicio = 1;
        $Periodo = (int)substr($strPoliza01,4,2);
        $numPol = $contPoliza;
        $TipoPoliza = $strPoliza02;
        $Referencia = "Carga Inicial";
        $Concepto = $strPoliza06;
        $Cargos = "";
        $Abonos = "";
        $Ajuste = "";
        $Fecha = substr($strPoliza01,0,4) . "-" . substr($strPoliza01,4,2) . "-" . substr($strPoliza01,6,2);;
        $TimeStamp = date("Y-m-d");;
        $Activo = 1;
        $Eliminado = 0;
        $pdv_aut = 0;



        for ($x=0; $x < 10; $x++) { 
    		if($x <= 9)
    		{
    			echo("<td>".${'strPoliza0'.$x} ."</td>");
    		}else
    		{
    			echo("<td>".${'strPoliza'.$x} ."</td>");
    		}
    	}

        $contMovimiento = 1;
        $contPoliza++;
    }


    if($Tipo=="M")
    {
    	echo("<td>M</td>");
        $strMovimiento01 = trim(substr($arrRows[$i],3,7)); //Cuenta
        $strMovimiento02 = trim(substr($arrRows[$i],34,10)); //Descripcion ESP
        $strMovimiento03 = trim(substr($arrRows[$i],55,1)); //Descripcion ENG
        $strMovimiento04 = trim(substr($arrRows[$i],57,8)); //Padre
        $strMovimiento05 = trim(substr($arrRows[$i],75,5)); //Tipo Cuenta
        $strMovimiento06 = trim(substr($arrRows[$i],89,3)); //Status
        $strMovimiento07 = trim(substr($arrRows[$i],110,44)); //Mayor, Subcuenta, Argupadora
        $strMovimiento08 = trim(substr($arrRows[$i],208,50)); //Mayor, Subcuenta, Argupadora

        $idPoliza = $contPoliza-1;
        $NumMovto = $contMovimiento;
        $idSucursal = 1;
        $Cuenta = $strMovimiento01;
        $TipoMovto = $strMovimiento03;
        $Importe = $strMovimiento04;
        $Referencia = $strMovimiento02;
        $Concepto = $strMovimiento07;
        $Activo = 1;
        $Fecha = date("Y-m-d");
        $factura = "";
        $Persona = "1-";

        for ($x=0; $x < 8; $x++) { 
    		if($x <= 9)
    		{
    			echo("<td>".${'strMovimiento0'.$x} ."</td>");
    		}else
    		{
    			echo("<td>".${'strMovimiento'.$x} ."</td>");
    		}
    	}

        $contMovimiento++;
    }
}
echo("</table>");

