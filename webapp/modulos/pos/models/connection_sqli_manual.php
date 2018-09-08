<?php
	global $api_lite;
	if(!isset($api_lite)){
		if(!isset($_REQUEST["netwarstore"])) require "../../netwarelog/mvc/models/connection_sqli_manual.php";
		else require "../webapp/netwarelog/mvc/models/connection_sqli_manual.php";
	}
	else require $api_lite . "netwarelog/mvc/models/connection_sqli_manual.php";
?>
