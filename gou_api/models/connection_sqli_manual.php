<?php

	//Esta es la clase de coneccion Padre que hereda los atributos a los modelos
	class Connection
	{
		public $connection;

		//Conecta a la base de datos
		public function connect($tienda = false)
		{
			//Cuidado con estas líneas de terror			
			require("webconfig.php");

			if($tienda){
				$bd = "netwarstore";
				$usuariobd = "nmdevel";
				$clavebd = "nmdevel";
			}

			try {
                $this->connection = new PDO('mysql:host='. $servidor .';dbname='. $bd .';charset=utf8', $usuariobd, $clavebd);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "<br><b style='color:red;'>Error al tratar de conectar</b><br>";
                die();
            }
		}

		//funcion que cierra la coneccion
		public function close()
		{
			$this->connection = null;
		}

		//Funcion que genera las consultas genericas a la base de datos
		public function query($query, $args)
		{
			$result = null;
			try{
				$result = $this->connection->prepare($query);
	            $result->execute($args);
	        }catch(PDOException $e){
	        	$result = $e->getMessage();
	        }
			return $result;
		}
		
		public function multi_query($query, $args)
		{
			return $this->query($query, $args);
		}

		public function insert_id($query, $args)
		{
			if(stristr($query, 'insert'))
			{
				$result = $this->query($query, $args);
				if(gettype($result) == "boolean" && $result)
					return $this->connection->lastInsertId;
				else
					return $result;
			}
			else
			{
				return "La consulta no incluye un INSERT.";
			}
		}

        public function queryArray($sql, $args, $relational = true)
        {
            try{
                if (empty($sql)){
                    throw new Exception("empty SQL");
                }

                $this->sql = $sql;

                $result = $this->query($sql, $args);

                if (!$result || gettype($result) == "string") {
                    return array("status"=>false, "total" => $sql,"msg"=>" Favor de contactar con el area de soporte y facilitar el mensaje de error que esta entre parentesis.( ". $result ." )");
                }

                $this->affectedRows = $result->rowCount();

                $fields = array();
                while ($finfo = $result->getColumnMeta()) {
                    $fields[] = $finfo->name;
                }

                $rows = array();

                if(stristr($sql, "select")){
	                if  ($relational) {
	                    while ($row = $result->fetch(PDO::FETCH_ASSOC)){
	                        $rows[] = $row;
	                    }
	                }else {
	                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	                        foreach ($row as $key => $value){
	                            $rows[$key][] = $value;
	                        }
	                    }
	                }
	            }
                $insert_id = $this->connection->lastInsertId();
                
                return array("status" => true, "total" =>  $this->affectedRows, "fields" => $fields, "rows" => $rows, "insertId" => $insert_id);
            }catch(Exception $e){
                return array("status" => false, "msg" => $e->getMessage());
            }
        }
        
	}
?>
