<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ImplementaModel extends Connection
{
  function configuracion($solucion){
    $app = "SELECT id_app, nombre, solucion, desc_corta, desc_larga, fechaInicio, fechaFinal FROM scala_app where solucion in ($solucion) and estatus = 1;";
    $resultApp = $this->queryArray($app);    

    $pasos = "SELECT ap.solucion, p.id_paso, p.paso, p.nombre, p.link, p.desc_larga 
              from scala_pasos p left join scala_app ap on ap.id_app = p.id_app 
              where ap.solucion in ($solucion) and ap.estatus = 1 order by solucion, paso";
    $resultpasos = $this->queryArray($pasos);

    $pasosR = '';
    foreach ($resultpasos['rows'] as $key => $value) {
    	$pasosR .=  $value['id_paso'].',';
    }
    $pasosR =  rtrim($pasosR, ',');

    $act = "SELECT a.menu, a.id_actividad, a.nombre, a.desc_larga, a.link, a.opcional, a.estatus, a.id_paso, p.paso 
            from scala_actividades a
            left join scala_pasos p on p.id_paso = a.id_paso 
            where a.id_paso in ($pasosR) and a.estatus > 0 group by a.menu;";
    // estatus 0 - Desactivado   // estatus 1 - Activado  // estatus 2 - Terminado
    $resultact = $this->queryArray($act);

    return array('app' => $resultApp['rows'], 'pasos' => $resultpasos['rows'], 'act' => $resultact['rows']);
  }

  function menu($menu){
  	$sql = "SELECT url, nombre, idmenu from accelog_menu where idmenu = $menu;";
  	$result = $this->queryArray($sql);
  	return $result['rows'];
  }
  function updateProgress($menu){

  	$sql = "UPDATE scala_actividades SET estatus = 2 where menu = $menu;";
  	$result = $this->queryArray($sql);
  	return $result['rows'];
  }

  function validaAppministra(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=1959");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}


	function saveIncio($solucion){
		$fecha = date("Y").'-'.date("m").'-'.date("d");
		$sql = "UPDATE scala_app SET fechaInicio = '$fecha' where solucion in ($solucion);";
	  	$result = $this->queryArray($sql);
	  	//return $result['rows'];
      return $fecha;
	}

  function saveFin($solucion){
    $fecha = date("Y").'-'.date("m").'-'.date("d");
    $sql = "UPDATE scala_app SET fechaFinal = '$fecha' where solucion in ($solucion);";
      $result = $this->queryArray($sql);
      //return $result['rows'];
      
    $sql2 = "UPDATE accelog_menu SET omision = 0 where idmenu = 2232";
    $result2 = $this->queryArray($sql2);
    return $fecha;

  }
  function fechas($solucion){
    $sql = "SELECT fechaInicio, fechaFinal from scala_app;";
    $result = $this->queryArray($sql);
    return $result['rows'];
  }
	

}
?>
