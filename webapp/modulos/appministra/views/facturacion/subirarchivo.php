<?php
//$targetFolder = '../uploads/'; // Relative to the root
$targetFolder = '../../../SAT/cliente/'; // Relative to the root

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) /* && $_POST['token'] == $verifyToken */) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('cer', 'key'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		//$targetFile;
		$output = array("success" => true, "archivo" => $targetFile, "message" => "Archivo cargado con exito");
	} else {
		$output = array("success" => false, "message" => "Tipo de archivo inválido");
	}

	
}else{
	$output = array("success" => false, "message" => "Seleccione un archivo válido");
}

echo json_encode($output);
?>