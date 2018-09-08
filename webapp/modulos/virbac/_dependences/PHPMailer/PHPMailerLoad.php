<?php
require_once('class.phpmailer.php');
require_once('class.smtp.php');

$mail = new PHPMailer;

$mail->SMTPDebug = false;                               // Enable verbose debug output
$mail->isSMTP();                                        // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';                         // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                                 // Enable SMTP authentication
$mail->Username = 'mailer@netwarmonitor.com';           // SMTP username
$mail->Password = 'mailer2468';                         // SMTP password
$mail->SMTPSecure = 'ssl';                              // Enable SSL encryption, `ssl` also accepted
$mail->Port = 465;                                      // TCP port to connect to
$mail->CharSet = 'UTF-8';