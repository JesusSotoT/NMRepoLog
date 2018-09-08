<?php
/* 
 * Este modulo arma el sql de un id dado
 */


	include("../webconfig.php");	
	
	$pf="100";
	$email = $_GET["a"];
	
	$html_contenido_reporte=$_POST["reporte"];

	session_start();

	//Inicia la página y utiliza el contenido recibido del reporte 

		$html="<!doctype html>";
		$html.= "<html>";
		$html.= "<head>";
		$html.= "<title>".$_SESSION["nombrereporte"]."</title>";
		$html.= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		//$html.= "<meta charset='utf-8' />";	
		$html.= "
				
				<style type='text/css'>
		
					body{
						font-family: Tahoma,'Trebuchet MS', Arial;
						font-size:12px;
					}

					div.fechahora{
					    text-align:right;
					    font-family: tahoma;
					    font-size:12px;
					    color:gray;
					}

					table.impresionhora tr td {    
					    font-size:11px;
					}

					table.reporte{
						border:none;
						color:gray;
						font-size:12px;
					        border:2px;
						border-spacing:4px;
					}

					tr.trencabezado td{
					    padding:10px;
						color:white;        
						background-color: black;
						height:20px;
						border:solid #555555;
						border-width:2px;
						text-align:center;
					    font-size:12.5px;
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

		$html.= str_ireplace("\\\"","\"",$html_contenido_reporte);
		
		//$html.="</font>";
		$html.="</body>";
		$html.="</html>";
		
		//ENVIO DE MAIL
			include("phpmailer/class.phpmailer.php");
			include("phpmailer/class.smtp.php");

			$mail = new PHPMailer();	
			$mail->CharSet='UTF-8';
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "ssl";
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			$mail->Username = $netwarelog_correo_usu;
			$mail->Password = $netwarelog_correo_pwd;


			$mail->From = $netwarelog_correo_usu;
			$mail->FromName = "NetwareMonitor";
			$mail->Subject = $_SESSION["nombrereporte"].", Generado con Repolog";
			$mail->AltBody = "Este reporte fue enviado desde NetwareLog";
			$mail->MsgHTML($html);
			$mail->AddAddress($email, $email);
			
			
			if(!$mail->Send()) {
				 echo "Error: " . $mail->ErrorInfo;
			} else {
				echo "<center><font size=2 color=#eeeeee>";
				echo "Informe enviado al correo electrónico:<br><br>";
				echo "</font>";
				echo "<b><font size=3 color=white> ".$email." </b></font></b>";
				echo "<br>";
				echo "<br><br><input type='button' value='Cerrar' autofocus onclick='cerrarloading();'></center>";
			}

?>

