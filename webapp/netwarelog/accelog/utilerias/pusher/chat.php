<?php

require('Pusher.php');

$strChanel = $_REQUEST['chanel'];
$intEvent = $_REQUEST['event'];
$intSender = $_REQUEST['sender'];
$strMessage = $_REQUEST['message'];
//###### cuenta gmorales@netwarmonitor.com ######
//$app_id = '126440';
//$app_key = '4778f84c6988839df270';
//$app_secret = '845ee02f0f32d1b81150';
//###### cuenta gmorales@netwarmonitor.com ######

//###### cuenta evesmax@netwaremonitor.com ######
$app_id = '129838';
$app_key = '952c596b256b51fc7cc6';
$app_secret = '9d393e5d57b16c7f5a28';
//###### cuenta evesmax@netwaremonitor.com ######

$pusher = new Pusher($app_key, $app_secret, $app_id);

$dteTimeStamp = date('Y-m-d h:i:s');

$data['message'] = $intSender . '||%%||<span>' . $dteTimeStamp . "</span><br />" . $strMessage;
$pusher->trigger($strChanel, $intEvent, $data);

echo "$dteTimeStamp";
?>