<?php

                
                $sQuery="";
                
                $sQuery = "SELECT clave FROM accelog_usuarios WHERE idempleado=$catalog_id_utilizado";
                $result = $conexion->consultar($sQuery);
                while($rs = $conexion->siguiente($result)){
                    $clave=md5($rs{"clave"});
		}
		$conexion->cerrar_consulta($result);
                
                //Actualiza Clave
                $sQuery="Update accelog_usuarios set clave='$clave' where idempleado=$catalog_id_utilizado";
                //echo "<br><br>$sQuery<br><br>";
                
                $conexion->consultar($sQuery);
                
?>
