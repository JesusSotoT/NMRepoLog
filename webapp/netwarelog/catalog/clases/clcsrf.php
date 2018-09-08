<?php 

	/* 
		NOTAS DE USO
		
		[ Página antes ]
				...
				session_start();
				...
				include "../catalog/clases/clcsrf.php";
				...
				<form ... method='post' ...>
					<?php 
						// Esta línea es opcional solo si se quieren usar nombres aleatorios de campos.
						$form_names = $csrf->form_names(array('txtusuario','txtclave'),true); 
					  // Imprime el token 	
						echo $csrf->input_token($token_id,$token_value);	 
					?>
					...
					<input type="text" name="<?php echo $form_names['txtusuario']; ?>">
					<input type="password" id="txtclave" name="<?php echo $form_names['txtclave']; ?>">

		[ Página después ]
				...
				session_start();
				...
				include "../catalog/clases/clcsrf.php";
				$form_names = $csrf->form_names(array('txtusuario','txtclave'),false);
				if(isset($_POST[$form_names['txtusuario']], $_POST[$form_names['txtclave']])){
					if($csrf->check_valid('post')){
						$txtusuario = $_POST[$form_names['txtusuario']];
						$txtclave = $_POST[$form_names['txtclave']];
					}
				}
	*/



	class csrf{
	
		public function reset_vars(){
			//error_log("RESETEO DE TOKENS");
			unset($_SESSION['token_id']);
			unset($_SESSION['token_value']);
		}
	
		public function get_token_id(){
			if(isset($_SESSION['token_id'])){
				return $_SESSION['token_id'];
			} else {	
				$token_id = $this->random(10);
				$_SESSION['token_id'] = $token_id;
				return $token_id;
			}
		}

		public function get_token(){
			if(isset($_SESSION['token_value'])) {
				return $_SESSION['token_value'];
			} else {	
				$token = hash('sha256',$this->random(500));
				$_SESSION['token_value'] = $token;
				return $token;
			}
		}

		public function check_valid($method) {
			if($method == 'post' || $method == 'get'){
				$post = $_POST;
				$get = $_GET;
				if(isset(${$method}[$this->get_token_id()]) && (${$method}[$this->get_token_id()] == $this->get_token())) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function form_names($names, $regenerate) {
    	$values = array();
    	foreach ($names as $n) {
      	if($regenerate == true) {
        	unset($_SESSION[$n]);
      	}
      	$s = isset($_SESSION[$n]) ? $_SESSION[$n] : $this->random(10);
      	$_SESSION[$n] = $s;
      	$values[$n] = $s;        
    	}
    	return $values;
		}

		private function random($len) {
    	if (@is_readable('/dev/urandom')) {
      	$f=fopen('/dev/urandom', 'r');
        $urandom=fread($f, $len);
        fclose($f);
      }
 
      $return='';
      for ($i=0;$i<$len;++$i) {
      	if (!isset($urandom)) {
        	if ($i%2==0) mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
        	$rand=48+mt_rand()%64;
      	} else $rand=48+ord($urandom[$i])%64;
 
      	if ($rand>57) $rand+=7;
      	if ($rand>90) $rand+=6;

      	if ($rand==123) $rand=52;
      	if ($rand==124) $rand=53;
      	$return.=chr($rand);
			}
    	return $return;
		}

		function input_token($token_id,$token_value){
			return "<input type='hidden' name='".$token_id."' value='".$token_value."' />";
		}
	}

	$csrf = new csrf();

	if($reset_vars) $csrf->reset_vars();

	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	//error_log("[clcsrf.php]\ntoken_id:".$token_id." \ntoken_value:".$token_value);

?>
