<?php
//$targetFolder = '../uploads/'; // Relative to the root
$targetFolder = '../../temp_archivos/'; // Relative to the root
$name = $_GET['name'];
$name = $targetFolder.$name.'.xml';

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) /* && $_POST['token'] == $verifyToken */) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	// Validate the file type
	$fileTypes = array('xml'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {		
		move_uploaded_file($tempFile,$name);
		//$targetFile;
		$output = array("success" => true, "archivo" => $targetFile, "message" => "Archivo cargado con exito");

	} else {
		$output = array("success" => false, "message" => "Tipo de archivo invalido");
	}

	
}else{
	$output = array("success" => false, "message" => "Seleccione un archivo xml");
}

echo json_encode($output);
?>