<?php

class claccess {
	
	
	//Sí debug es verdadero aparecerán los mensajes para depurar en
	//el log de php.
	function nmerror_log($dato){
	
		/// IMPRESION DEL DEBUG
	
			$debug=false;
	
	///////////////////////
		
		$out = "\n";
		$out.= "///////////////////////////////";
		$out.= "[NMERROR_LOG | INICIO] \n";
		$out.= $dato;
		$out.= "\n\n\n";
		$out.= "////////////////////////////////";
		$out.= "[NMERROR_LOG | FIN]";
		$out.= "\n";	

		if($debug) error_log($out,3,"/Applications/MAMP/logs/php_error.log");
	}




	function get_cut_url($url,$partederecha=false){
	
		//Parte izquierda
		//$posicion_url = strpos($url,"/mlog");
		$posicion_url = strpos($url,"/webapp");
		$url = substr($url, $posicion_url); 

		if($partederecha){
			//Parte derecha
			$ult_diagonal=0;
			for($c = 0; $c<=strlen($url)-1; $c++){
				if(substr($url,$c,1)=="/"){
					$ult_diagonal = $c;
				}
			}
			
			//Cortando la parte derecha
			$url = substr($url,0,$ult_diagonal);
		}	
		
		return $url;
	}		

	function get_full_url(){
    	$s = &$_SERVER;
    	$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    	$sp = strtolower($s['SERVER_PROTOCOL']);
    	$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    	$port = $s['SERVER_PORT'];
    	$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    	$host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
    	return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
	}

	function raise_404(){
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		header("Status: 404 Not Found");
		$_SERVER['REDIRECT_STATUS'] = 404;
		$salida = readfile("http://www.netwarmonitor.mx/404.php");
		echo $salida;
		exit;
	}


	// Permisos especiales
	/*
		Estos permisos son para las urls del netwareLog que son suburls para administrar
		el sistema, si la url contiene parte de eso entonces la validación será si tiene
		acceso al módulo completo: CataLog, DocLog ó RepoLog 
	*/	
	function especial_url($url,$seccion,$url_a_validar){
		if(!(strpos($url,$seccion)===false)){
			return $url_a_validar;
		}
		return "NA";
	}
	function especial_security_netwarelog($url){

		$url_regresa = "NA";

		// CataLog
		if(!(strpos($url,"catalog")===false)){
			$url_a_validar =  "/webapp/netwarelog/catalog/admin/index.php";
			if($url_regresa=="NA") $url_regresa = $this->especial_url($url,"catalog/admin/campo",$url_a_validar);
			if($url_regresa=="NA") $url_regresa = $this->especial_url($url,"catalog/admin/dependencia",$url_a_validar);
			if($url_regresa=="NA") $url_regresa = $this->especial_url($url,"catalog/admin/estructura",$url_a_validar);
		}
		
		// DocLog
		if(!(strpos($url,"doclog")===false)){
			if($url_regresa=="NA") $url_a_validar =  "/webapp/netwarelog/doclog/admin/index.php";
			if($url_regresa=="NA") $url_regresa = $this->especial_url($url,"doclog/admin/documento",$url_a_validar);
		}

		// RepoLog
	    	if(!(strpos($url,"repolog")===false)){
			if($url_regresa=="NA") $url_a_validar =  "/webapp/netwarelog/repolog/admin/index.php";
			if($url_regresa=="NA") $url_regresa = $this->especial_url($url,"repolog/admin/instalacion",$url_a_validar);
			if($url_regresa=="NA") $url_regresa = $this->especial_url($url,"repolog/admin/parametros",$url_a_validar);
			if($url_regresa=="NA") $url_regresa = $this->especial_url($url,"repolog/admin/reporte",$url_a_validar);
		}	
		
		return $url_regresa;
	}


	function let_url($url){
			
			$debug="\n\n----------------------------------";
			$debug.="\n".date("l jS \of F Y h:i:s A");
			$debug.="\n[LET_URL]";
			$debug.="\n[RECIBIO] <<<".$this->get_cut_url($url).">>>";

			if((strpos($url,"netwarelog")===false)&&($url!="")){
				$debug.="\n[URL FUERA DEL NETWARELOG] >>> CONCEDIDO \n";
				$this->nmerror_log($debug);
				return true;
			}

			//Permisos especiales --- netwarelog
			$url_a_validar = $this->especial_security_netwarelog($url);
			if($url_a_validar!="NA"){
			    $debug.="\n[ESPECIAL URL DETECTED] >>> ".$url;	
				$url = $url_a_validar;
			} 	
		
			$debug.="\n[URL A VALIDAR] >>> ".$url;

			$let = false;
			$url_acceso_especial = $this->get_cut_url($url);
			//Verificando si esta en las url autorizadas:
			foreach($_SESSION["accelog_urls"] as $url_permitida){	
				if($this->get_cut_url($url_permitida!="")){
					$debug.="\n[COMPARANDO] ? ".$this->get_cut_url($url_permitida)." == ".$this->get_cut_url($url);
					if($this->get_cut_url($url_permitida)==$this->get_cut_url($url))
					{
						$let=true;
						$debug.="\n[ACCESO CONCEDIDO]\n";
						break;
					} else {
						$debug.="\n[COMPARANDO] ? ".$this->get_cut_url($url_permitida)." == ".$this->get_cut_url($url)."&ticket=testing";
						if ($this->get_cut_url($url_permitida)==$this->get_cut_url($url)."&ticket=testing") {
							$let=true;
							$debug.="\n[ACCESO CONCEDIDO]\n";
							break;
						}						
					}
				}
			}
			
			
			if(!$let) $debug.="\n[ACCESO DENEGADO]\n";
			$this->nmerror_log($debug);

			return $let;
			//return true;
	}

	
	//Agrega la url a las permitidas de la sesión
	function add_url($url){

			//echo " -entre- ".$url."<br>";

			// Valida si la sesión esta lista
			if(!isset($_SESSION["accelog_urls"])){
				$_SESSION["accelog_urls"] = array();
			}
			
			// Revisa si la url no fue
			// agregada previamente
			$agregar_accelog_urls=true;
			foreach($_SESSION["accelog_urls"] as $url_permitida){
				if($url_permitida==$url){
					$agregar_accelog_urls=false;
					break;
				}
			}
			// Si la url no esta entonces se
			// agrega al arreglo.
			if($agregar_accelog_urls) $_SESSION["accelog_urls"][] = $url;		
	}


	function envia_clave_temporal($nombreusuario,$accelog_salt,$conexion,$correo,$netwarelog_correo_usu,$netwarelog_correo_pwd){
			//RANDOM
				$length=15;
    		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    		$randomString = '';
    		for ($i = 0; $i < $length; $i++) {
        	$randomString .= $characters[rand(0, strlen($characters) - 1)];
   			 }
    		$clavetemporal = $randomString;
			//////

			//ENCRIPTA	
				$clavenueva = $conexion->fencripta($clavetemporal,$accelog_salt);
			/////


    	//CAMBIANDO CLAVE EN LA BASE DE DATOS
        $sql = " update accelog_usuarios set clave = '".$clavenueva."' where usuario ='".$nombreusuario."' ";
        $conexion->consultar($sql);
    	///
			

			//ENVIANDO POR CORREO
				$email = $correo;
				include("../repolog/phpmailer/class.phpmailer.php");
				include("../repolog/phpmailer/class.smtp.php");

				$mail = new PHPMailer();	
				$mail->CharSet='UTF-8';
				$mail->IsSMTP();
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = "ssl";
				$mail->Host = "smtp.gmail.com";
				$mail->Port = 465;
				$mail->Username = $netwarelog_correo_usu;
				$mail->Password = $netwarelog_correo_pwd;	
	
				$mail->From = $netwarelog_correo_usu;
				$mail->FromName = "NetwareMonitor";
				$mail->Subject = "Netwarmonitor: Recuperación de contraseña";
				$html ="Este correo ha sido enviado porque lo ha solicitado en nuestro servicio de recuperación de contraseñas, ";
				$html.="en caso de que no recuerde haber solicitado la recuperación de su clave por favor comuniquese de inmediato ";
				$html.="a nuestras oficinas al teléfono: 01800 APPS 321 (01800 2777 321).<br/><br/>";
				$html.="Su contraseña temporal asignada es: <b>".$clavetemporal."</b><br/><br/>";
				$html.="Por favor acceda y cambiela lo más pronto posible.";
				$html.="<br/><br/>";
				$html.="Atentamente<br/><br/>Soporte Técnico";
				$mail->AltBody = "Recuperación de Contraseña"; 
				$mail->MsgHTML($html);
				//error_log($html);
				$mail->AddAddress($email, $email);	
			////
			$enviado = $mail->Send();
			//error_log("Error?: ".$mail->ErrorInfo);

			return $enviado;
		
	}




}

?>
