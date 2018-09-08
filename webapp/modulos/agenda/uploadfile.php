<?php
include_once("../../netwarelog/catalog/conexionbd.php");
$output_dir = "expedientes/";


if(isset($_FILES["myfile"]))
{
	//Filter the file types , if you want.
	if ($_FILES["myfile"]["error"] > 0)
	{
	  echo "Error: " . $_FILES["file"]["error"] . "<br>";
	}
	else
	{
		//move the uploaded file to uploads folder;
    	move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $_FILES["myfile"]["name"]);
    
	
	try{
	$queryupload=mysql_query("INSERT INTO `expediente` (`id` ,`nombre`)VALUES (NULL , '".$_FILES["myfile"]["name"]."');");
	$idexpediente=mysql_insert_id();
	
	$queryupload=mysql_query("INSERT INTO `agenda_expediente` (`id` ,`idAgenda` ,`idExpediente`)VALUES (NULL , '".$_POST["id"]."', '".$idexpediente."');");
	
		 echo "Archivo adjuntado correctamente:".$_FILES["myfile"]["name"];	
	}
	catch(Exception $e){   echo "Error: " . $_FILES["file"]["error"] . "<br>"; }
	//echo $_POST["id"];
	}

}
?>