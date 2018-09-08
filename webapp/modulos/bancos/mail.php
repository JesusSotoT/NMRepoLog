<?php
	include("../../netwarelog/webconfig.php");	
	
	$pf="100";
	$email = $_GET["a"];
	
	$nombreXML=$_POST["xml"];

			include("../../netwarelog/repolog/phpmailer/class.phpmailer.php");
			include("../../netwarelog/repolog/phpmailer/class.smtp.php");

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
			$mail->FromName = "NetwarMonitor";
			$mail->Subject = "Retencion e Inf. de Pagos";
			$mail->MsgHTML("Has recibido un CFDI de Retenciones e Informacion de Pagos");
			$mail->AddAttachment("../cont/xmls/facturas/temporales/".$nombreXML);
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

