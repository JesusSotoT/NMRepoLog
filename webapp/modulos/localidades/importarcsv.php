
<?php

$fila = 1;
if (($gestor = fopen("clientes2.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
     //   $numero = count($datos);
     // echo "<p> $numero de campos en la línea $fila: <br /></p>\n";  
      //  for ($c=0; $c < $numero; $c++){
      //  echo $datos[$c] . "<br />\n";
      //  }
        if($fila>1)
        {
			$idestado=$datos[0];
			$idruta=$datos[1];
			$idpromotor=$datos[2];
			$tienda=$datos[3];
			$dueno=$datos[4];
			$idgiro=$datos[5];
			$idrubro=$datos[6];
			$idtipotienda=$datos[7];
			$direccion=$datos[8];
			$colonia=$datos[9];			
			$idmunicipio=$datos[10];
			$cp=$datos[11];
			$correo=$datos[12];
			$celular=$datos[13];
            
			/*
			echo "Estado:".$datos[0] . "<br />\n";
            echo "Ruta:".$datos[1] . "<br />\n";
            echo "Promotor:".$datos[2] . "<br />\n";
            echo "tienda:".$datos[3] . "<br />\n";
            echo "Dueno:".$datos[4] . "<br />\n";
            echo "Giro:".$datos[5] . "<br />\n";
            echo "Rubro:".$datos[6] . "<br />\n";
			echo "Tipo tienda:".$datos[7] . "<br />\n";
            echo "Direccion:".$datos[8] . "<br />\n";
            echo "Colonia:".$datos[9] . "<br />\n";
            
            echo "municipio:".$datos[10] . "<br />\n";
			echo "codigopostal:".$datos[11] . "<br />\n";
            echo "correo:".$datos[12] . "<br />\n";
            echo "Celular:".$datos[13] . "<br />\n";
			*/
			$query="INSERT INTO `comun_cliente` (`nombre`, `nombretienda`, `direccion`, `colonia`, `idTipotienda`, `idRubro`, `idGiro`, `idPromotor`, `idRuta`, `email`, `celular`, `cp`, `idEstado`, `idMunicipio`)"; 
			$query.=" VALUES ('".$dueno."', '".$tienda."', '".$direccion."', '".$colonia."',".$idtipotienda.", ".$idrubro.", ".$idgiro.",".$idpromotor.",".$idruta.",'".$correo."','".$celular."','".$cp."','".$idestado."','".$idmunicipio."');";
			
			echo $query. "<br /><br />\n";
			//exit;
			//mysql_query("INSERT INTO codigospostales (`id`, `cp`, `municipio`, `estado`, `ciudad`, `idestado`, `idmunicipio`, `idciudad`) VALUES (NULL, '".$datos[0]."', '".$datos[3]."', '".$datos[4]."', '".$datos[5]."', '".$datos[7]."', '".$datos[11]."', '".$datos[14]."');");
     	//exit;
		}
     $fila++;        
    }
    fclose($gestor);
}
?>