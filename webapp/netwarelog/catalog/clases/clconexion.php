<?php
class conexion{

	var $cbase;
	var $tipobd;


	function revisa_sesion(){

		if(session_id()=='') {
    	session_start();
		}

		if(!isset($_SESSION["accelog_idorganizacion"])||$_SESSION["accelog_idorganizacion"]==""){
			//echo "entrando...";
  		header("Location: ../../../index.php");
  	} else {


			//echo $_SESSION["accelog_idempempleado"]."  ".$SERVER_;
		}
	}

	function regresa_base(){
		return $this->cbase;
	}

	function tipobd(){
		return $this->tipobd;
	}

	function conexion($servidor,$usuariobd,$clavebd,$bd,$instalarbase,$tipobd,$nuevaconexion=false){

		$this->tipobd=$tipobd;

		if($tipobd=="mysql"){
			$this->cbase = mysql_connect($servidor,$usuariobd,$clavebd,$nuevaconexion);
			mysql_select_db($bd,$this->cbase);
			mysql_set_charset('utf8',$this->cbase); //Añadido el 5-11-2013 Omar, para ver si corrige el utf8

			if($instalarbase==1){
				$this->instalacion();
			}
		} else {
			$this->cbase = mssql_connect($servidor,$usuariobd,$clavebd);
			mssql_select_db($bd,$this->cbase);

			if($instalarbase==1){
				$this->instalacion();
			}
		}

	}

	function instalacion(){


		//Instalación de las tablas del sistemas necesarias para catalog:
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE TABLE IF NOT EXISTS catalog_estructuras(
			  idestructura INT NOT NULL AUTO_INCREMENT ,
			  nombreestructura VARCHAR(50) NULL ,
			  descripcion VARCHAR(80) NULL ,
			  fechacreacion DATETIME NULL ,
			  fechamodificacion DATETIME NULL ,
			  estatus CHAR NULL ,
                          utilizaidorganizacion TINYINT NULL DEFAULT '0',
                          linkproceso VARCHAR(200) NULL,
			  PRIMARY KEY (idestructura) )
			";
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1
			    FROM INFORMATION_SCHEMA.TABLES
			    WHERE TABLE_TYPE='BASE TABLE'
			    AND TABLE_NAME='catalog_estructuras')
				CREATE TABLE catalog_estructuras(
				  idestructura  INT IDENTITY(1,1) ,
				  nombreestructura VARCHAR(50) NULL ,
				  descripcion VARCHAR(80) NULL ,
				  fechacreacion DATETIME NULL ,
				  fechamodificacion DATETIME NULL ,
				  estatus CHAR NULL ,
                                  utilizaidorganizacion INT NULL ,
                                  linkproceso VARCHAR(200) NULL,
				  PRIMARY KEY (idestructura) )
			";
			mssql_query($sql, $this->cbase);
		}
		/////////


		//TABLA DE CAMPOS
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE TABLE IF NOT EXISTS catalog_campos (
			  idcampo INT NOT NULL AUTO_INCREMENT ,
			  idestructura INT NULL ,
			  nombrecampo VARCHAR(50) NULL ,
			  nombrecampousuario VARCHAR(80) NULL ,
			  descripcion VARCHAR(255) NULL ,
			  longitud INT NULL ,
			  tipo VARCHAR(45) NULL ,
			  valor VARCHAR(45) NULL ,
			  formula VARCHAR(300) NULL ,
			  requerido TINYINT(1) NULL ,
			  formato VARCHAR(45) NULL ,
			  orden INT NULL ,
			  llaveprimaria TINYINT NULL DEFAULT '0',
			  PRIMARY KEY (idcampo) ,
			  INDEX eted_estructuraid (idestructura ASC) ,
			  CONSTRAINT eted_estructuraid
			    FOREIGN KEY (idestructura )
			    REFERENCES catalog_estructuras (idestructura )
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1
			    FROM INFORMATION_SCHEMA.TABLES
			    WHERE TABLE_TYPE='BASE TABLE'
			    AND TABLE_NAME='catalog_campos')
			CREATE TABLE catalog_campos (
			  idcampo INT IDENTITY(1,1) ,
			  idestructura INT NULL ,
			  nombrecampo VARCHAR(50) NULL ,
			  nombrecampousuario VARCHAR(80) NULL ,
			  descripcion VARCHAR(255) NULL ,
			  longitud INT NULL ,
			  tipo VARCHAR(45) NULL ,
			  valor VARCHAR(45) NULL ,
			  formula VARCHAR(300) NULL ,
			  requerido INT NULL ,
			  formato VARCHAR(45) NULL ,
			  orden INT NULL ,
			  llaveprimaria INT NULL ,
			  PRIMARY KEY (idcampo) ,
			  CONSTRAINT eted_estructuraid
			    FOREIGN KEY (idestructura )
			    REFERENCES catalog_estructuras (idestructura )
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";
			mssql_query($sql, $this->cbase);
		}
		/////////



		//TABLA DE DEPENDENCIAS
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE  TABLE IF NOT EXISTS catalog_dependencias (
			  idcampo INT NOT NULL ,
			  tipodependencia CHAR NULL ,
			  dependenciatabla VARCHAR(50) NULL ,
			  dependenciacampovalor VARCHAR(50) NULL ,
			  dependenciacampodescripcion VARCHAR(80) NULL ,
			  PRIMARY KEY (idcampo) ,
			  INDEX eddds_campoid (idcampo ASC) ,
			  CONSTRAINT eddds_campoid
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_campos (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1
			    FROM INFORMATION_SCHEMA.TABLES
			    WHERE TABLE_TYPE='BASE TABLE'
			    AND TABLE_NAME='catalog_dependencias')
			CREATE TABLE catalog_dependencias (
			  idcampo INT NOT NULL ,
			  tipodependencia CHAR NULL ,
			  dependenciatabla VARCHAR(50) NULL ,
			  dependenciacampovalor VARCHAR(50) NULL ,
			  dependenciacampodescripcion VARCHAR(80) NULL ,
			  PRIMARY KEY (idcampo) ,
			  CONSTRAINT eddds_campoid
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_campos (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";
			mssql_query($sql, $this->cbase);
		}
		/////////


		//TABLA DE DEPENDENCIASFILTROS
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE  TABLE IF NOT EXISTS catalog_dependenciasfiltros (
			  idcampo INT NOT NULL ,
			  nombrecampo VARCHAR(50) NOT NULL ,
			  INDEX dfd_dependencias (idcampo ASC) ,
			  PRIMARY KEY (idcampo, nombrecampo) ,
			  CONSTRAINT dfd_dependencias
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_dependencias (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1
			    FROM INFORMATION_SCHEMA.TABLES
			    WHERE TABLE_TYPE='BASE TABLE'
			    AND TABLE_NAME='catalog_dependenciasfiltros')
			CREATE  TABLE catalog_dependenciasfiltros (
			  idcampo INT NOT NULL ,
			  nombrecampo VARCHAR(50) NOT NULL ,
			  PRIMARY KEY (idcampo, nombrecampo) ,
			  CONSTRAINT dfd_dependencias
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_dependencias (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";
			mssql_query($sql, $this->cbase);
		}
		/////////

	}

	function cerrar(){
		if($this->tipobd=="mysql"){
			mysql_close($this->cbase);
		} else {
			mssql_close($this->cbase);
		}

	}

	function consultar($sql,$regresar_result=true){
		if($this->tipobd=="mysql"){
			$result = mysql_query($sql,$this->cbase);
			//error_log($sql);
			if($regresar_result){
				return $result;
			}
		} else {
			$result = mssql_query($sql,$this->cbase);
			if($regresar_result){
				return $result;
			}
		}
	}

	function count_rows($result){
		if($this->tipobd=="mysql"){
			$nr = mysql_num_rows($result);
			return $nr;
		} else {
			$nr = mssql_num_rows($result);
			return $nr;
		}
	}

	function siguiente($result){
		if (false === $result) {
			echo mysql_error();
		}else{
			if($this->tipobd=="mysql"){
				$reg=mysql_fetch_array($result,MYSQL_ASSOC);
				return $reg;
			} else {
				$reg=mssql_fetch_array($result);
				return $reg;
			}
		}
	}

	function cerrar_consulta($result){
		if (false === $result) {
			echo mysql_error();
		}else{
			if($this->tipobd=="mysql"){
				mysql_free_result($result);
			} else {
				mssql_free_result($result);
			}
		}

	}

	function fechamx($dato){
		return date("d/m/Y H:i:s",strtotime($dato));
	}

	function existe($sql){
		$existedato=false;
		$result = $this->consultar($sql);
		if($reg=$this->siguiente($result)){
			$existedato=true;
		}
		$this->cerrar_consulta($result);
		return $existedato;
	}

	function existetabla($nombretabla){
		if($this->tipobd=="mysql"){
			$Table = mysql_query("show tables like '" . $nombretabla . "'");
			if(mysql_fetch_row($Table) === false){
				return(false);
			} else {
				return(true);
			}
		} else {
			$Table = mssql_query("
					SELECT 1
					FROM INFORMATION_SCHEMA.TABLES
					WHERE TABLE_TYPE='BASE TABLE' AND TABLE_NAME='".$nombretabla."' ",$this->cbase);
			if(mssql_fetch_row($Table) === false){
				return(false);
			} else {
				return(true);
			}
		}
	}

				function nstore_admin() {

					if($_SERVER['SERVER_NAME']=="edu.netwarmonitor.com"){
					        $servidortr ="unmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
					        $usuariobdtr="unmdev";
					        $clavebdtr="&=98+69unmdev";
					        $bdtr = "nmdev";
					}elseif($_SERVER['SERVER_NAME']=="localhost"){
					        $servidortr ="192.168.1.11";
					        $usuariobdtr="nmdevel";
					        $clavebdtr="nmdevel";
					        $bdtr = "nmdev";
					}else{
					        $servidortr  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
					        $usuariobdtr = "nmdevel";
					        $clavebdtr = "nmdevel";
					        $bdtr = "nmdev";
					}

					//Recupera Nombre Instancia
					$instancia = $_SESSION["accelog_nombre_instancia"];
					//echo $arrInstanciaG;
					$fechaultimoacceso=date('Y-m-d H:i:00', time());
					$servidor  = $servidortr;
					$objCon = mysqli_connect($servidor, $usuariobdtr, $clavebdtr, "netwarstore");
					//Recupera Base Datos
					$strSql="Select nombre_db from customer where instancia='$instancia'";
					$rstCustomer = mysqli_query($objCon, $strSql);
					while($objCustomer=mysqli_fetch_array($rstCustomer)){
						$basedatos=$objCustomer['nombre_db'];
					}
					mysqli_free_result($rstCustomer);
					unset($rstCustomer);


					if ($_SESSION['version']<2) {
					//Sql Actualizacion Datos Tienda Version Anterior
							//Productos
							$sqlup="update customer set productos=(select count(idproducto) from $basedatos.mrp_producto) where instancia='$instancia';";
							mysqli_query($objCon,$sqlup);
							//Ventas
							$sqlup="update customer set ventas=(select count(idventa) from $basedatos.venta) where instancia='$instancia';";
							mysqli_query($objCon,$sqlup);
							//Facturas
							$sqlup="update customer set facturas=(select count(id) cuantos from $basedatos.pvt_respuestaFacturacion where not folio is null and folio<>'') where instancia='$instancia';";
							//$sqlup="update customer set facturas=".$_SESSION['version']." where instancia='$instancia';";
							mysqli_query($objCon,$sqlup);
					}else{
						//Sql Actualizacion Datos Tienda Version Nueva
								//Productos
								$sqlup="update customer set productos=(select count(id) from $basedatos.app_productos) where instancia='$instancia';";
								mysqli_query($objCon,$sqlup);
								//Ventas POS
								$sqlup="update customer set ventas=(select count(idVenta) from $basedatos.app_pos_venta)+(select ifnull(count(id),0) from $basedatos.app_oventa) where instancia='$instancia';";
								mysqli_query($objCon,$sqlup);

								//Facturas
								$sqlup="update customer set facturas=(select count(id) cuantos from $basedatos.app_respuestaFacturacion where not folio is null) where instancia='$instancia';";
								mysqli_query($objCon,$sqlup);
				}

						//Polizas
						$sqlup="update customer set polizas=(select count(id) cuantas from $basedatos.cont_polizas) where instancia='$instancia';";
						mysqli_query($objCon,$sqlup);

					//Insertar Fecha de Acceso en la BD Transversal
					//echo $arrInstanciaG;
					$fechaultimoacceso=date('Y-m-d H:i:00', time());
					$strSql = "update customer set fechaultimoacceso='".$fechaultimoacceso."' where instancia='".$instancia."'";
					mysqli_query($objCon,$strSql);
					mysqli_close($objCon);

				}

        function transaccion($nombreproceso,$sql){
						date_default_timezone_set('America/Mexico_City');
            $fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
            $fecha_s1 = strtotime("31-12-".(date("Y")-1)." 00:00:00"); //SEMESTRE 1
            $fecha_s2 = strtotime("30-06-".date("Y")." 23:59:59"); //SEMESTRE 2

            //echo "31-12-".(date("Y")-1)." 00:00:00"."<br>";
            //echo "30-06-".date("Y")." 00:00:00"."<br>";
            //echo " $fecha_actual > $fecha_s1 ".($fecha_actual > $fecha_s1)."<br>";
            //echo " $fecha_actual<=$fecha_s2 ".($fecha_actual<=$fecha_s2)."<br>";

            $nombretabla_transacciones = "netwarelog_transacciones_".date("Y")."_";

            if(($fecha_actual > $fecha_s1)&&($fecha_actual<=$fecha_s2)){
                //echo "PRIMER SEMESTRE";
                $nombretabla_transacciones.="s1";
            }else{
                //echo "SEGUNDO SEMESTRE";
                $nombretabla_transacciones.="s2";
            }


                //SE CREA LA TABLA EN CASO DE NO EXISTIR
                if(!$this->existetabla($nombretabla_transacciones)){
												$sqltabla = "
												CREATE  TABLE IF NOT EXISTS ".$nombretabla_transacciones." (
												  fecha datetime NOT NULL ,
												  usuario VARCHAR(255) NOT NULL ,
												  nombreproceso VARCHAR(500) NOT NULL ,
												  sqlproceso VARCHAR(5000) NULL,
													ip VARCHAR(100) NOT NULL )
												";
												$sqltabla.="ENGINE = InnoDB;";
                        //echo $sql;
                        $this->consultar($sqltabla);
												//mysql_query($sql, $this->cbase);
                }
			                $usuario = "N/A"; //Puede existir un proceso donde aún el usuario no se haya logeado.
			                if(isset($_SESSION["accelog_login"])){
			                    $usuario = $_SESSION["accelog_login"];
                }
        				$sql = str_replace("'", "\"", $sql);


                //echo $_SERVER['SERVER_ADDR'];
                $sql  = "insert into ".$nombretabla_transacciones."
                             (fecha, usuario, nombreproceso, sqlproceso, ip)
                             values
                             (now(), '".$usuario."','".$nombreproceso."','".$sql."','".$_SERVER["REMOTE_ADDR"]."') ";
                $this->consultar($sql);

        }


		function insert_id(){
			if($this->tipobd=="mysql" || $this->tipobd == "MYSQL"){
				return mysql_insert_id();
			} else {
				return mssql_insert_id();
			}
		}

		//Actualización Everardo 2011-03-18
		function extraerdeentre($TheStr, $sLeft, $sRight){
        		$pleft = strpos($TheStr, $sLeft, 0);
                if ($pleft !== false){
                                $pright = strpos($TheStr, $sRight, $pleft + strlen($sLeft));
                                If ($pright !== false) {
                                                return (substr($TheStr, $pleft + strlen($sLeft), ($pright - ($pleft + strlen($sLeft)))));
                                }
                }
                return '';
        }

		function not_regresa_numero($conexion_2){
			if(!isset($_SESSION["accelog_idempleado"])) return -1;
			$sql = "select count(idnotificacion) as cuenta from notificaciones
				where leido = 0 and idempleado = ".$_SESSION["accelog_idempleado"];

		            $result = $conexion_2->consultar($sql);
		            while($rs=$conexion_2->siguiente($result)){
						 $cuenta=$rs{"cuenta"};
		            }

            		$conexion_2->cerrar_consulta($result);

			return $cuenta;
		}


		// Esta función esta ligada a los procesos del instanciador
		// en caso de modificaciones deberán realizarse también
		// en dichos procesos.
		function fencripta($pwd,$salt){
			$resultado = crypt($pwd,$salt);
			//echo $resultado;
			return $resultado;
		}



		////// XSS ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

		function escapalog_leve($data){
			$data = str_replace(";","",$data);
			$data = str_replace("-","",$data);
			return $data;
		}

		// El objetivo de esta función es proteger contra inyección de sql
		// Así como también de XSS.
		function escapalog($data,$repolog=false,$urlmenu=false){
				/* Referencia: http://stackoverflow.com/questions/1336776/xss-filtering-function-in-php */
 				/* Referencia: http://www.forosdelweb.com/f18/funcion-para-evitar-xss-sql-injection-958648 */

				//URL MENU
				if($urlmenu){
					$data = mysql_real_escape_string($data);
					//error_log("[clconexion.php]\nEl dato quedo como:".$data);
					return $data;
				}



				// Fix &entity\n;
				$data = urldecode($data);
				$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
				$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
				$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
				$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

				// Remove any attribute starting with "on" or xmlns
				$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

				// Remove javascript: and vbscript: protocols
				$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
				$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
				$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

				// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
				$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
				$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
				$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

				// Remove namespaced elements (we do not need them)
				//$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
				//error_log($data);
				//$data = preg_replace("#","",$data);
				$data = str_ireplace("<","",$data);
				$data = str_ireplace(">","",$data);
				$data = str_ireplace("/","",$data);
				$data = str_ireplace("\\","",$data);
				//$data = preg_replace("^","",$data);
			  //error_log($data);

				do
				{
					// Remove really unwanted tags
					$old_data = $data;
					$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
				}
				while ($old_data !== $data);


				// Revisando palabras reservadas:
       	$palabras1 = array(
           	'javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link',
           	'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer',
           	'layer', 'bgsound', 'title', 'base'
       	);
       	$palabras2 = array(
           	'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate',
           	'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste',
           	'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange',
           	'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut',
           	'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate',
           	'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop',
           	'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout',
           	'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture',
           	'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover',
           	'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange',
           	'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter',
           	'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange',
           	'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload', 'alert(', ');', 'function'
       	);
       	$palabrasreservadas = array_merge($palabras1, $palabras2);
				foreach($palabrasreservadas as $pr){
					//error_log($pr);
					$data = str_replace($pr,"  ",$data);
				}
				if(!$repolog) $data = str_replace("'"," ",$data);

				// we are done...

				$data = strip_tags($data);
				$data = mysql_real_escape_string($data);
				return $data;

		} //fin escapalog($data)

		////// FIN XSS ///////////////////////////////////////////////////////////////////////////////////////////////////////////////



		// valida el archivo con las extensiones permitidas.
		function valida_archivo($archivo){

			//Validar tipo de archivos
			// /mlog/webapp/netwarelog/archivos/1/organizaciones
			//echo "ENTRE pos:".strpos($archivo,"organizaciones")." --- ";
			if( strpos($archivo,"organizaciones") >= 10){
				$extensiones_validas = array(
					'.jpg','.png','.gif'
				);
			} else {
				$extensiones_validas = array(
					'.jpg','.png','.gif','.jpeg','.tiff','.bmp','.pdf',
					'.doc','.docx','.ppt','.pptx','.xls','.xlsx',
					'.cer','.key','.pages','.numbers',
					'.xml','.odt','.ods','.odp'
				);
			}

			$archivo_valido = false;
			$archivo_a_revisar = basename($archivo);
			foreach($extensiones_validas as $extension){
				$extension_archivo_a_revisar = substr($archivo_a_revisar,(strlen($extension)*-1));
				error_log(" Comparando: ".$extension_archivo_a_revisar." == ".$extension." ? ");
				if($extension_archivo_a_revisar==$extension){
					$archivo_valido=true;
					break;
				}
			}
			error_log(" Archivo válido: ".$archivo_valido);
			return $archivo_valido;

		}


}

?>
