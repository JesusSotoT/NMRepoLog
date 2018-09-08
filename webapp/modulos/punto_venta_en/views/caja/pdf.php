<?php
ini_set('memory_limit', '2048M');
set_time_limit(1020);
/* 
 * Este modulo toma el reporte y lo convierte en PDF 
 */



	//Inicia la página y utiliza el contenido recibido del reporte 

		$html= '<page orientation="L" backtop="0mm" backbottom="0mm" 
									backleft="0mm" backright="0mm"> 
        <page_header> 
        Header
        </page_header> 
        Footer
        <page_footer> 
        </page_footer>';
 
		$html.="<!doctype html>";
		$html.= "<html>";
		$html.= "<head>";
		$html.= "<title>Reporte</title>";
		$html.= "<meta http-equiv='Content-Type' content='application/pdf; charset=utf-8'>";
		$html.= "<style type='text/css'>
	
					html{
						margin: auto;
					}
	
					body{
						font-family: Tahoma,'Trebuchet MS', Arial;
						font-size:12px;
						horizontal-aligment: center;
						text-align:center;
					}
					table
					{
						text-align:center;
					}
					

				</style>";
		$html.= "</head>";
		$html.= "<body><h2>Corte de Caja</h2>";
		



		$vars=str_replace("<center>", "", $_REQUEST['cont']);
		$vars=str_replace("</center>", "", $vars);
		//$vars=str_replace("\"", "'", $vars);
		$html.=utf8_decode($vars);
		$html.="NetwarMonitor";
		$html.="</body>";
		$html.="</html>";
		$html.="</page>";

		//echo $html;

		$nombre_archivo = $_REQUEST['name']."-".date('Y-m-d--h-i-s-A').".pdf";
			
		// GENERA HTML2PDF 
		//$html = ob_get_clean();
		require_once("../../../../netwarelog/repolog/html2pdf/html2pdf.class.php");	
		//$html2pdf = new HTML2PDF('P','A4','en', true, 'UTF-8', array(mL, mT, mR, mB)); 	
		//$html2pdf = new HTML2PDF('P','LETTER','es');
		$html2pdf = new HTML2PDF('L','A4','es', true, 'UTF-8', array(5, 5, 1, 5));
		//$html2pdf->pdf->SetDisplayMode('real');	
		$html2pdf->pdf->SetDisplayMode('fullpage');
		//$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->WriteHTML($html);
		//$html2pdf->WriteHTML("<html><body>Hola Mundo</body></html>");
		//$html2pdf->SetDisplayMode('real');
		//echo "prueba";	
		//$html2pdf->Output($nombre_archivo,'D');
		$html2pdf->Output($nombre_archivo,false);


?>
