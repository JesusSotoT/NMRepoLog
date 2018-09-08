<?php

class clNotificaciones{

	function addNotif($idempleado, $notificacion, $idmenu, $idreferencia, $link, $leido, $fechanotificacion, $fechalectura,$conexion){
            $sqlInsert = "
                            INSERT INTO notificaciones 
                                    (idempleado, notificacion, idmenu, idreferencia, link, leido, fechanotificacion, fechalectura) 
                            VALUES  
                                    (".$idempleado.", '".$notificacion."', ".$idmenu.", ".$idreferencia.", '".$link."', '".$leido."', '".$fechanotificacion."', '".$fechalectura."')
                    ";
//echo $sqlInsert;
		$conexion -> consultar($sqlInsert);
		$id_insertado = $conexion->insert_id();
	
		if ($id_insertado <> 0)
			return $id_insertado;
		else 				
			return 0;
    }
	
	function delNotif($idnotificacion,$conexion){
		$sqlSelect = 	"
							SELECT * FROM notificaciones 
							WHERE idnotificacion = '".$idnotificacion."' 
						";
		$result = $conexion -> consultar($sqlSelect); 				
		
		if (mysql_numrows($result) <> 0) {
			$sqlDelete = 	"
							DELETE FROM notificaciones 
							WHERE idnotificacion = '".$idnotificacion."' 
							LIMIT 1 
						";							
			$conexion -> consultar($sqlDelete);	
			return 1;
		}
		else 
			return 0;
		$conexion -> cerrar_consulta($result);
	}
	
	function showNotif($idempleado, $numeronotificaciones, $conexion) {
		$sqlSelect = 	"
							SELECT * FROM notificaciones 
							WHERE idempleado = '".$idempleado."' 
							ORDER BY idnotificacion DESC
							LIMIT ".$numeronotificaciones."
						";	
		$result = $conexion -> consultar($sqlSelect);
		
		$html="<TABLE id=nombretabla>";
		while($rs = $conexion->siguiente($result)){
            //$html.="<TR><TD>".$rs{"notificacion"}."</TD><TD>".$rs("link")."</TD><TD>".$rs("leido")."</TD><TD>".$rs("fechanotificacion")."</TD><TD>".$rs("idmenu")."</TD></TR>";
			$html.="<tr><td>".$rs{"notificacion"}."</td><td>".$rs{"link"}."</td><td>".$rs{"leido"}."</td><td>".$rs{"fechanotificacion"}."</td><td>".$rs{"idmenu"}."</td></tr>";
        }
		$html.="</TABLE>";
		echo $html; 
		
	}

    
}



?>
