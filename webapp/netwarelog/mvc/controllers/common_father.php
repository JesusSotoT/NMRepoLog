<?php

	class CommonFather
	{

		//Genera el contenido cambiante, donde $f es la variable que contiene el nombre del controlador que va a cargar
		//si el controlador existe lo carga caso contrario lo que cargara sera un controlador por default que contiene
		//la pagina default principal
		function content($f)
		{  
			$current_page = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
			$per = 0; //Permiso por default NO
			

			global $bloqueo;
			if((strpos($current_page,"index.php?c=") || (array_key_exists("_tipo", $_REQUEST) && $_REQUEST["_tipo"] == "index")) AND !intval($bloqueo)) //Si la url es del index
			{
			   $per = 1;
			}

			if(strpos($current_page,"index.php?c=auxiliar_impuestos&f=reporte&considera_per=")) //Si la url es del index
			{
			   $per = 1;
			}

			
			if(strpos($current_page,"ajax.php?c=")) //Si la url es del ajax da acceso
			{
				if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest")
					$per = 1;
				if(strpos($current_page,"ajax.php?c=AccountsTree&f=cvs"))
					$per = 1;
				if(strpos($current_page,"ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza="))
					$per = 1;
				if(strpos($current_page,"ajax.php?c=declaracionR21&f=reporte&ejercicio="))
					$per = 1;
			}

			if(array_key_exists("_tipo", $_REQUEST) && ($_REQUEST["_tipo"] == "ajax" || $_REQUEST["_tipo"] == "api"))
				$per = 1;

			if($per) //Si tiene permisos entonces llama al metodo
			{
				if(isset($f))
				{
					$this->$f();
				}
				else
				{
					$this->mainPage();
				}
			}
			else
			{
				$this->noAccess();
			}
				  
		}

		function noAccess()
		{
			echo "<b style='color:red;'>No tienes acceso </b>";
		}

		function path($ruta=null)
		{
			if(is_null($ruta))
				$path = "";
			else
				$path = $ruta;
			//path para hacer pruebas en local simulando una carpeta externa a la instancia.
			//$path = "../../../../mlog/webapp/modulos/cont/";
			if(isset($_COOKIE['inst_lig']))
				$path = "../../../../".$_COOKIE['inst_lig']."/webapp/modulos/cont/";
			return $path;
		}

		function obtener_ruta_pdfs($archivo){
			if (!is_null($archivo)) {
				//path para hacer pruebas en local simulando una carpeta externa a la instancia.
				//$path = "../../../../mlog/webapp/modulos/cont/notificaciones/".$archivo;
				$path = "../../../../appcontia/webapp/modulos/cont/notificaciones/".$archivo;
				return $path;
			}
			return false;
		}
		
	}

?>