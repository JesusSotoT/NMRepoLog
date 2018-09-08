<?php
/* 
 * Este modulo toma el reporte y lo convierte en PDF 
 */



	$pf=$_POST["cmbescala"];
	if($pf=="") $pf="100";
	$porcentaje_fuente = 12 * ($pf/100);
	
	//Vertical
	$orientation=$_POST["cmborientacion"];
	$right_margin="10";
	
  //Horizontal
	//$orientation="L";
	//$right_margin="15";
	


	$html_contenido_reporte=$_POST["contenido"];

	session_start();

	//ob_start(); //inicia la lectura de html2pdf


	//Inicia la página y utiliza el contenido recibido del reporte 

		$html= '<page orientation="'.$orientation.'" backtop="0mm" backbottom="0mm" 
									backleft="0mm" backright="0mm"> 
        <page_header> 
        </page_header> 
        <page_footer> 
        </page_footer>';
 
		$html.="<!doctype html>";
		$html.= "<html>";
		$html.= "<head>";
		$html.= "<title>".$_SESSION["nombrereporte"]."</title>";
		$html.= "<meta http-equiv='Content-Type' content='application/pdf; charset=utf-8'>";
		//$html.= "<meta charset='utf-8' />";	
		$html.= "
				
				<style type='text/css'>
	
					html{
						margin: auto;
					}
	
					body{
						font-family: Tahoma,'Trebuchet MS', Arial;
						font-size:".$porcentaje_fuente."px;
						horizontal-aligment: center;
						text-align:center;
					}

					div.fechahora{
					    text-align:right;
					    font-family: tahoma;
					    font-size:".$porcentaje_fuente."px;
					    color:gray;
					}

					table.impresionhora tr td {    
					    font-size:11px;
					}

					table.reporte{
						border:none;
						color:gray;
						font-size:".$porcentaje_fuente."px;
					  border:2px;
						border-spacing:4px;
					}

					tr.trencabezado {
						border:none;
					}

					tr.trencabezado td{
					    padding:10px;
						color:white;        
						background-color: black;
						/*height:20px;*/
						border:solid #555555;
						border-width:2px;
						text-align:center;
					  font-size:".$porcentaje_fuente."px;
						font-weight:bold;
					}

					tr.trsubtotal{
					    vertical-align: top;	
					    border:none;
						color:#555555;
					    font-weight:bold;
						height:20px;
						background-color: gray;	
						background-repeat: repeat-x;		
					}

					table.reporte tr td{
						padding:5px;
						border:solid gray;
						border-width:1px;
					}

					td.tdmoneda{
					    text-align:right;
					}

				
				</style>";

		$html.= "</head>";
		$html.= "<body>";
		



		$html_contenido_reporte=str_ireplace("\\\"","\"",$html_contenido_reporte);
		$html.=$html_contenido_reporte;

		//$html.="<textarea>";
		//$html.=$html_contenido_reporte;
		//$html.="</textarea>";
		
		//$html.="</font>";
		//$html.="</page_header>";
		//$html.="</page>";



		$html.="</body>";
		$html.="</html>";
		$html.="</page>";

		$nombre_archivo = $_SESSION["nombrereporte"]."-".date('Y-m-d--h-i-s-A').".pdf";



		// GENERA PDFNATIVO
			//header('Content-type: application/pdf');
			//$nombre_archivo=$_SESSION["nombrereporte"]."-".date('Y-m-d--h-i-s-A').".pdf";
			//header('Content-Disposition: attachment; filename="'.$nombre_archivo.'"');
			//readfile('original.pdf');
			//echo $html;
			//echo $html;

	/*	
		//GENERA DOMPDF
			include("parametros.php");
			require_once("dompdf-0.5.1/dompdf_config.inc.php");
			//require_once("dompdf/dompdf_config.inc.php");

			ini_set("memory_limit",$tamano_buffer);  // Configurable webconfig
			$dompdf = new DOMPDF();
			$dompdf->set_paper('letter', 'landscape');
			$dompdf->load_html($html);
			//$dompdf->load_html(utf8_decode($html));
			//$dompdf->load_html("
			//	<html><body><table border=\"1\" width=\"20\"><tr></tr><tr><th
			//	colspan=2>prueba con
			//	th y colspan</th><td>otra columna un tr sin
			//	col</td></tr><tr><td>hola</td></tr></table></body></html>");
			$dompdf->render();
			$dompdf->stream($nombre_archivo);
		/////
	*/	

		//echo "<textarea>".$html."</textarea>";
		//echo $html;
		//break;

		//echo $html;
			
		// GENERA HTML2PDF 
		//$html = ob_get_clean();
		require_once("html2pdf/html2pdf.class.php");	
		//$html2pdf = new HTML2PDF('P','A4','en', true, 'UTF-8', array(mL, mT, mR, mB)); 	
		//$html2pdf = new HTML2PDF('P','LETTER','es');
		$html2pdf = new HTML2PDF($orientation,'LETTER','es', true, 'UTF-8', array(5, 5, $right_margin, 5));
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
