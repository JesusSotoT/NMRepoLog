<?php


$output_dir = "autorizaciones/";
$salida="";			
		//var_dump($_FILES);	
			if(count($_FILES)>0)
			{
				foreach($_FILES as $file)
				{	
					if ($file["error"] > 0)
					{
					  	//echo "Error: " . $file["error"] . "<br>";
					}
					else
					{
						move_uploaded_file($file["tmp_name"],$output_dir. $file["name"]);
						$salida.=$file["name"]."*";
					}
				}
			}
			 
echo $salida;
?>