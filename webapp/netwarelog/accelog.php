<?php


// Control de acceso

//Funciones para el control de acceso 
require_once "accelog_claccess.php";
$accelog_access = new claccess();

//Valida páginas que no requieren login

	$url_acceso_especial = $accelog_access->get_full_url();
	//echo $url_acceso_especial."---".strpos($url_acceso_especial,"accelog/validapwd.php")."<br>";
	$acceso = false;
	// Ventanas que no requieren control de acceso 
	$acceso = !(strpos($url_acceso_especial,"accelog/index.php")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"accelog/validapwd.php")===false);
// begin - habilita acceso via webservice
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"accelog/fromws.php")===false);
// end - habilita acceso via webservice
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"accelog/menu.php")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"accelog/ecl.php")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"descarga_archivo_fisico.php")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/facturar/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/kiosko/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/appministra_api/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/hibrido_api/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/gou_api/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/checador_api/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/netwarmonitor/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/foodware_api/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/inovekia_consultor/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/inovekia_empresario/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/restaurantes_externo/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"/coti/")===false);
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"visorpdf.php")===false);
	//error_log("Especial:".$url_acceso_especial." acceso:".$acceso);

// begin - habilita acceso a pag de pruebas para implementar css
	if(!$acceso) $acceso = !(strpos($url_acceso_especial,"accelog/newmenu.php")===false);
// end - habilita acceso a pag de pruebas para implementar css

////

// VERIFICAR QUE SEGUIMOS SOBRE LA MISMA INSTANCIA [CSRF]
	//error_log("substr: ".substr($url_acceso_especial,-21));

// begin - habilita acceso via webservice se agrega &&(!(substr(strtok($url_acceso_especial,'?'),-18)=="accelog/fromws.php"))
//	if((!(substr($url_acceso_especial,-21)=="accelog/validapwd.php"))&&(!(substr($url_acceso_especial,-15)=="accelog/ecl.php"))){
	if((!(substr($url_acceso_especial,-21)=="accelog/validapwd.php"))&&(!(substr(strtok($url_acceso_especial,'?'),-18)=="accelog/fromws.php"))&&(!(substr($url_acceso_especial,-15)=="accelog/ecl.php"))){
// end - habilita acceso via webservice se agrega &&(!(substr(strtok($url_acceso_especial,'?'),-18)=="accelog/fromws.php"))

		//error_log("checando si seguimos sobre la misma instancia ".$url_acceso_especial);
		
		if(session_id()=='') session_start();
		//$url_request = explode("/",$_SERVER["PHP_SELF"]);
		//error_log(" \n -- ".dirname(__FILE__)."  ".basename(__FILE__));
		$directorio_de_trabajo = dirname(__FILE__);
		$directorio_de_trabajo = str_replace('\\','/',$directorio_de_trabajo);
		$dir_file = explode("/",$directorio_de_trabajo);
		$i_nombre_instancia=0;
		$c=0;
		foreach($dir_file as $item){
				if($item=="webapp") $i_nombre_instancia=$c-1;
				$c++;
		}
		$nombre_instancia = $dir_file[$i_nombre_instancia];
		//error_log("INSTANCIA: ".$nombre_instancia);
	
		$salir = true;	
		if(isset($_SESSION["accelog_nombre_instancia"])){
			//error_log("comparando ¿'".$_SESSION["accelog_nombre_instancia"]."' = '".$nombre_instancia."' ?");
			$salir = !($_SESSION["accelog_nombre_instancia"] == $nombre_instancia);
		}
		//error_log($_SESSION["accelog_nombre_instancia"]."  ==  ".$nombre_instancia."  SALIR:".$salir);
	
		//error_log("SALIR: ".$salir);
		if($salir){
			session_destroy();
			//error_log("llamando a salir");
			header('Location: ../accelog/salir.php');			
		} 
	}
/////










// Si no fue página que no requiere acceso entonces ...
	//error_log("ACCESO:".$acceso);
	if(!$acceso){
	
		if(session_id()=='') session_start();

		// Primer validación es si el usuario esta logeado ...
  		if(!isset($_SESSION["accelog_idorganizacion"])){
			$accelog_access->raise_404();
  		}

		//// EN DESARROLLO
		$acceso = $accelog_access->let_url($url_acceso_especial);		
		if(!$acceso){
			$accelog_access->nmerror_log("\n<<< VALIDANDO HTTP_REFERER >>>");
			$acceso = $accelog_access->let_url($_SERVER["HTTP_REFERER"]);		
		}


	}

	if(!$acceso){	
		$accelog_access->raise_404();
	}

////
	
?>
