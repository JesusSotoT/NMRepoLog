<?php

	//Esta es la clase de coneccion Padre que hereda los atributos a los modelos
class Connection{
	public $connection;
	public $affectedRows = 0;

		//Conecta a la base de datos
	private function connect()
	{
			//Cuidado con estas líneas de terror			
		// require("../webapp/netwarelog/webconfig.php");	
		require("webconfig.php");

		if(!$this->connection = mysqli_connect($servidor,$usuariobd,$clavebd,$bd))
		{
			echo "Error al tratar de conectar";	
		}
			$this->connection->set_charset('utf8');// Previniendo errores con SetCharset
		}

		//funcion que cierra la coneccion
		private function close()
		{
			$this->connection->close();
		}

		//Funcion que genera las consultas genericas a la base de datos
		public function query($query)
		{
			$this->connect();
			$result = $this->connection->query($query) or die("Error en la consulta".$this->connection->error."Error:".$query);

			$this->close();
			return $result;
		}
		
		public function multi_query($query){
			$result = $this->connection->multi_query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);
			return $result;
		}

		public function insert_id($query){
			$this->connect();
			if(stristr($query, 'insert')){
				$this->connection->query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);
				
				return $this->connection->insert_id;
				$this->close();
			}else{
				$this->close();
				return "La consulta no incluye un INSERT.";
			}
		}
		
		public function queryArray($sql, $relational = true){
			try{
				if (empty($sql)){
					throw new Exception("empty SQL");
				}
				$this->sql = $sql;
				$this->connect();

				$result = $this->connection->query($sql) or die("Error en la consulta.".$this->connection->error."Error:".$sql);

				$this->affectedRows = mysqli_num_rows($result);

				$fields = array();
				while ($finfo = mysqli_fetch_field($result)) {
					$fields[] = $finfo->name;
				}

				$rows = array();

				if	($relational) {
					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
						$rows[] = $row;
					}

				}else {
					while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
						foreach ($row as $key => $value){
							$rows[$key][] = $value;
						}
					}
				}
				$this->close();
				return array("status" => true, "total" =>  $this->affectedRows, "fields" => $fields, "rows" => $rows);

			}catch(Exception $e){
				$this->close();
				return array("status" => false, "msg" => $e->getMessage());
			}
		}

		

		public function setTree($type)
		{
			if( $type == true )
			{
				
			}
			else
			{

			}
		}

		//Metodo para generar transaccion con la base de datos
		public function dataTransact($data)
		{
			$this->connect();
			$this->connection->autocommit(false);
			if($this->connection->query('BEGIN;'))
			{
				if($this->connection->multi_query($data))
				{
					do {
						/* almacenar primer juego de resultados */
						if ($result = $this->connection->store_result()) {
							while ($row = $result->fetch_row()) {
								echo $row[0];
							}
							$result->free();
						}

					} while ($this->connection->more_results() && $this->connection->next_result());

					$this->connection->commit();
					$this->connection->close();
					return true;
				}
				else
				{
					$error = $this->connection->error;
					$this->connection->rollback();
					$this->connection->close();
					return $error;
				}		
			}
			else
			{
				$error = $this->connection->error;
				$this->connection->rollback();
				$this->connection->close();
				return $error;
			}
		}

		public function transact($query)
		{
			$this->connect();
			$this->connection->autocommit(false);
			if($this->connection->query('BEGIN;'))
			{
				if($this->connection->multi_query($query))
				{
					$this->connection->commit();
					$this->connection->close();
					return true;
				}
				else
				{
					$error = $this->connection->error;
					$this->connection->rollback();
					$this->connection->close();
					return false;
				}		
			}
			else
			{
				$error = $this->connection->error;
				$this->connection->rollback();
				$this->connection->close();
				return false;
			}
		}
		//Genera el tipo de nivel de configuracion automaticos o manuales.
		public function getAccountMode()
		{
			$sql = "SELECT TipoNiveles FROM cont_config LIMIT 1;";
			$result = $this->query($sql);
			$data = $result->fetch_array(MYSQLI_ASSOC);
			return $data['TipoNiveles'];
		}

///////////////// ******** ---- 	escapalog		------ ************ //////////////////
// Escapa las cadenas de caracteres para evitar posible ataques a la base de datos o el sistema
	// Como parametros puede recibir:
		// data -> cadena a validar
		
		function escapalog($data,$repolog=false,$urlmenu=false){
			/* Referencia: http://stackoverflow.com/questions/1336776/xss-filtering-function-in-php */
 			/* Referencia: http://www.forosdelweb.com/f18/funcion-para-evitar-xss-sql-injection-958648 */
			// return $data;
			$this->connect();
				
		//URL MENU
			if($urlmenu){
				$data = mysqli_real_escape_string($this->connection, $data);
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
			$data = str_ireplace("<","",$data);
			$data = str_ireplace(">","",$data);
			$data = str_ireplace("/","",$data);
			$data = str_ireplace("\\","",$data);
			
			do{
			// Remove really unwanted tags
				$old_data = $data;
				$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
			}while ($old_data !== $data);

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
				$data = str_replace($pr,"  ",$data);
			}
			
			if(!$repolog) 
				$data = str_replace("'"," ",$data);
		// we are done...
			$data = strip_tags($data);
			$data = mysqli_real_escape_string($this->connection, $data);
			
			$this->close();
			
			return $data;
		}
	
///////////////// ******** ---- 	FIN escapalog		------ ************ //////////////////

///////////////// ******** ---- 	fencripta		------ ************ //////////////////
	// Como parametros puede recibir
		// $pwd -> contraseña a encritar
		// $salt -> variable que se crea al iniciar sesion en el sistema
			
		function fencripta($pwd,$salt){
			$resultado = crypt($pwd,$salt);
			
			return $resultado;
		}
		
///////////////// ******** ---- 	FIN fencripta		------ ************ //////////////////
} ?>