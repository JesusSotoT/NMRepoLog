<?php

class usuario{
	var $agregar="-1";
	var $eliminar="-1";
	var $modificar="-1";
	
	//SETTES
	function setopciones($session_opciones, $idestructura){ 
		$opciones = array_values($session_opciones);
								
		//Checando los permisos específicos del usuario en la estructura que se almacenaron al validapwd.php en accelog
		//CONVENVION: Deben existir en la base de datos los permisos registrados ejemplo
		//permiso:  accelog_menuxeliminar => que no se puede eliminar
		//si algun perfil tiene esa opción entonces los usuarios de ese perfil no podrán eliminar
		//en catalog de esa estructura.

		
		//$opciones = $session_opciones;								
		for($i=0;$i<=count($opciones)-1;$i++){
			//echo $i."---".$opciones[$i]."..... catalog_".$idestructura."xa <br>";
			if($opciones[$i]=="catalog_".$idestructura."xa"){
				$this->agregar="0";
			}
			if($opciones[$i]=="catalog_".$idestructura."xe"){				
				$this->eliminar="0";
			}
			if($opciones[$i]=="catalog_".$idestructura."xm"){
				$this->modificar="0";
			}
		}
	}

	//GETTES
	function getticket($ticket){ return $this->ticket; }
	
	function getagregar(){
		return $this->agregar;
	}
	function getmodificar(){
		return $this->modificar;
	}
	function geteliminar(){
		return $this->eliminar;
	}
	
}	
	
?>