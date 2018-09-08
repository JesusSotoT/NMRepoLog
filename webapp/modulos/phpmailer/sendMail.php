<?php 
	require_once('mailconfig.php');
	require_once('class.phpmailer.php');
    require_once('class.smtp.php');

    $mail = new PHPMailer();

    $mail->CharSet = $strMailCharSet;
    $mail->IsSMTP();
    $mail->SMTPAuth = $blnMailSMTPAuth;
    $mail->SMTPSecure = $strMailSMTPSecure;
    $mail->Host = $strMailHost;
    $mail->Port = $intMailPort;
    $mail->Username = $strMailUsername;
    $mail->Password = $strMailPassword;
    $mail->SMTPDebug = $strDebug;

?>