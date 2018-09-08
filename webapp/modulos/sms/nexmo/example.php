<?php

ini_set("display_errors",1);

	include ( "NexmoMessage.php" );


	/**
	 * To send a text message.
	 *
	 */

	// Step 1: Declare new NexmoMessage.
	$nexmo_sms = new NexmoMessage('1a62e892', 'e26f0771');

	// Step 2: Use sendText( $to, $from, $message ) method to send a message. 

//Omar
//$info = $nexmo_sms->sendText( '+523316729440', 'Netwar', 'Responde al 525549998487 si quieres una brasileira!!!' );
//Eves1
//$info = $nexmo_sms->sendText( '+523315201581', 'Netwar', 'Responde al 525549998487 si quieres una brasileira!!!' );
//Eves2
//$info = $nexmo_sms->sendText( '+523316729466', 'Netwar', 'Responde al 525549998487 si quieres una brasileira!!!' );
//Mario
//$info = $nexmo_sms->sendText( '+525530099046', 'Netwar', 'Responde al 525549998487 si quieres una brasileira!!!' );
//Yop
$info = $nexmo_sms->sendText( '+523317386220', 'Netwar', 'Responde al 525549998487 si quieres una brasileira!!!' );

	// Step 3: Display an overview of the message
	echo $nexmo_sms->displayOverview($info);

	// Done!

?>