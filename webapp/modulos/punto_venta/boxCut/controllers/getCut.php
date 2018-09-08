<?php
	require '../models/boxCut.php';
	$boxCut = new BoxCut();
	$data = $boxCut->getCut( $_POST['init'], $_POST['end'], true , $_POST['iduser']);
	echo "data = " . $data . ";";
?>