<?php
//print_r($_POST);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);
//============================================================+
// File name   : example_021.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 021 for TCPDF class
//               WriteHTML text flow
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+


// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo Acontia
        $image_file = K_PATH_IMAGES.'acontia.png';
        // Logo Empresa
        //$this->Image($_POST['logo'], 15, 7, '', 9, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        if($_POST['cmborientacion']=='P'){
          //  $this->Image($image_file, 157, 8, '', 8, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->Line(15,18,195,18);
        }else{
            //$this->Image($image_file, 244, 8, '', 8, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->Line(15,18,282,18); 
        }
    }

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('NetwarMonitor');
$pdf->SetTitle('Reporte');
$pdf->SetSubject('Reporte');
$pdf->SetKeywords('PDF, Reporte, Acontia');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 9);

if($_POST['nombreDocu'] != 'movimientos_cuentas')
{
    // Set some content to print
    $html = $_POST['contenido'];

    $orientacion=$_POST['cmborientacion'];
    $escala=$_POST['cmbescala'];

    // add a page
    $pdf->AddPage($orientacion);
    $pdf->Scale($escala,$escala,100,60);

    // Print text using writeHTMLCell()
    $pdf->writeHTML($html, true, 0, true, 0);


    // ---------------------------------------------------------
}
else
{
    //aqui el codigo

   // contenido html
    $info = $_POST['contenido'];

    $encabezado=explode("<!--Separador****|****-->",$info); //separamos encabezado

    $separada=explode("<!--Separador**|**-->",$info); //separamos cuentas
    $cuenta=count($separada);

    $orientacion=$_POST['cmborientacion'];
    $escala=$_POST['cmbescala'];
    // add some pages and bookmarks


for ($i = 1; $i < $cuenta; $i++) {
    $pdf->AddPage($orientacion);
    $pdf->Scale($escala,$escala,100,60);
    $html="$encabezado[0] $separada[$i] </tbody></table></td></tr></table>"; //combinamos encabezado con cuentas
    //$pdf->Bookmark($separada[$i].$i, 0, 0, '', 'B', array(0,64,128));
    //$pdf->Cell(0, 10, $separada[$i].$i, 0, 1, 'L');
    $pdf->writeHTML($html, true, 0, true, 0);
}

}


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($_POST['nombreDocu'].'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+