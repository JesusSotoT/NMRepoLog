<?php
include("../../netwarelog/webconfig.php");
include("../../netwarelog/repolog/phpmailer/class.phpmailer.php");
include("../../netwarelog/repolog/phpmailer/class.smtp.php");

if(isset($_POST["m"])){
		
			
	$varRespuesta="";
	$emailv='';
	$cadena=$_POST["cadena"];
	$ciclocorreos = explode('##.##', $cadena);
	foreach ($ciclocorreos as $key => $value) {
		if($value==''){
			continue;
		}
        $nombreXML=$_POST["xml"];
		$_REQUEST['name']=$nombreXML;
	    $_REQUEST['id']='temporales';
	    $_REQUEST['nominas']=1;
		$partir=explode('#.#', $value);
		$email=$partir[0];
		$nombreXML=$partir[1];
		$nombemp=$partir[2];
		$fechaini=$partir[3];
		$fechafin=$partir[4];

		
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
		$mail->Subject = "CFDI de recibo electrónico.";
		//$mail->MsgHTML("Has recibido un XML de nominas timbradas");
        //$mail->AddAttachment("../cont/xmls/facturas/temporales/".$nombreXML);
		//$mail->Subject = "Envio de XML";
		$mail->MsgHTML("Servicio de entrega de CFDI de recibos electrónicos del periodo "." ".$fechaini." "."al"." ".$fechafin.".");

		$mail->AddAttachment("../cont/xmls/facturas/temporales/".$nombreXML);
		$nombreXML=str_replace('.xml','.pdf',$nombreXML);
		$mail->AddAttachment("../cont/xmls/facturas/temporales/pdfnominas/".$nombreXML);
		$mail->AddAddress($email, $email);
		if(!$mail->Send()) {
			if($email==''){
				$email=$nombemp;
			}
			$emailerror.=" ".$nombemp.' <br>';
// echo "Error: " . $mail->ErrorInfo;
		} else {
			$emailv.=' '.$nombemp.' <br>';
		}
		unset($mail);
	}
	
	$varRespuesta.= "<center><font size=2 color=#eeeeee>";
	if($emailv==''){
		
		$varRespuesta.= "Error al enviar recibos a:<br><br>";
		$varRespuesta.= "</font>";
		$varRespuesta.= "<b><font size=3 color=white> ".$emailerror." </b></font></b>";
				
	}else{
		
		$varRespuesta.= "Recibos enviados correctamente a:<br><br>";
		$varRespuesta.= "</font>";
		$varRespuesta.= "<b><font size=3 color=white> ".$emailv." </b></font></b>";
	}
	
	$varRespuesta.= "<br>";
	$varRespuesta.= "<br><br><input type='button' value='Cerrar' autofocus onclick='cerrarloading();'></center>";

	echo $varRespuesta;

}else{
			$pf="100";
			$email = $_GET["a"];

			$nombreXML=$_POST["xml"];
            $fechaini = $_POST['fechaini'];
            $fechafin = $_POST['fechafin'];
			$_REQUEST['name']=$nombreXML;
			$_REQUEST['id']='temporales';
			$_REQUEST['nominas']=1;

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
			$mail->Subject = "CFDI de recibo electrónico";
			//$mail->MsgHTML("Has recibido un XML de nominas timbradas.");
				$mail->MsgHTML("Servicio de entrega de CFDI de recibos electrónicos del periodo "." ".$fechaini." "."al"." ".$fechafin.".");
			$mail->AddAttachment("../cont/xmls/facturas/temporales/".$nombreXML);
			$nombreXML=str_replace('.xml','.pdf',$nombreXML);
			$mail->AddAttachment("../cont/xmls/facturas/temporales/pdfnominas/".$nombreXML);
			$mail->AddAddress($email, $email);
			if(!$mail->Send()) {
				$status = "Error al enviar recibos a:  ";
				$msj = $email."<br>Estatus: ".$mail->ErrorInfo ;
			}else{
				$status = "Recibos enviados al correo electrónico:<br><br>";
				$msj = $email;
			}
				echo "<center><font size=2 color=#eeeeee>";
				echo $status;
				echo "</font>";
				echo "<b><font size=3 color=white> ".$msj." </b></font></b>";
				echo "<br>";
				echo "<br><br><input type='button' value='Cerrar' autofocus onclick='cerrarloading();'></center>";
			

}

?>
