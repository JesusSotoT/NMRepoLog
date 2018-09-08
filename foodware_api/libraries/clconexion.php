<?php

	class conection {

		var $cbase;
		var $tipobd;

		function revisa_sesion(){
			if(session_id() == '') {
	    		session_start();
			}
			if(!isset($_SESSION["accelog_idorganizacion"]) || $_SESSION["accelog_idorganizacion"] == ""){
				header("Location: ../../../index.php");
	  		} else {

			}
		}

		function regresa_base(){
			return $this->cbase;
		}

		function tipobd(){
			return $this->tipobd;
		}

		function conection($servidor, $usuariobd, $clavebd, $bd, $tipobd){
			$this->tipobd = $tipobd;
			if($tipobd == "mysql"){
				$this->cbase = mysql_connect($servidor, $usuariobd, $clavebd, false);
				mysql_select_db($bd,$this->cbase);
				mysql_set_charset('utf8',$this->cbase); //Añadido el 5-11-2013 Omar, para ver si corrige el utf8
			} else {
				$this->cbase = mssql_connect($servidor, $usuariobd, $clavebd);
				mssql_select_db($bd, $this->cbase);
			}
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

		function insert_id(){
			if($this->tipobd=="mysql" || $this->tipobd == "MYSQL"){
				return mysql_insert_id();
			} else {
				return mssql_insert_id();
			}
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

	}

?>
