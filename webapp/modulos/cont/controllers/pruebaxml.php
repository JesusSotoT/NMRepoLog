<?php
	$xml = "<?xml version='1.0' encoding='utf-8'?>
				<nodo>
					<datos id_xml='001'>Aqui van los datos</datos> 
					</nodo>";
	$nombre = "archivoxml.xml";
	$archivo = fopen($nombre, "w+");					
	fwrite($archivo,$xml);
	fclose($archivo);

?>