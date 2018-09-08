<?php
if(isset($_GET['tipo']))
{
	//Genera y lo descarga como ZIP	
	$files = "../".$_GET['ruta']."/".$_GET['nombre'];
	
	$nombre = explode('.',$_GET['nombre']);

	$zipname = "../zips/".$nombre[0].".zip";
	$zip = new ZipArchive;
	$zip->open($zipname, ZipArchive::CREATE);
 	$zip->addFile($files,$_GET['nombre']);
	$zip->close();

 	
 	$file = $zipname;
 	
 	header("Content-Description: Descargar ZIP");
 	header("Content-Disposition: attachment; filename=".$nombre[0].".zip");
 	header("Content-Type: application/force-download");
 	header("Content-Length: " . filesize($file));
 	header("Content-Transfer-Encoding: binary");
 	readfile($file);
 	unlink($file);

}
else
{
	//Descarga XML
 	$file = "../".$_GET['ruta']."/".$_GET['nombre'];
 	
 	header("Content-Description: Descargar XML");
 	header("Content-Disposition: attachment; filename=".$_GET['nombre']);
 	header("Content-Type: application/force-download");
 	header("Content-Length: " . filesize($file));
 	header("Content-Transfer-Encoding: binary");
 	readfile($file);
}
 
?>