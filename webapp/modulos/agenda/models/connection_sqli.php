<?php

	//Esta es la clase de coneccion Padre que hereda los atributos a los modelos
class Connection
{
	public $connection;
	public $affectedRows = 0;

		//Conecta a la base de datos
	private function connect()
	{
			//Cuidado con estas líneas de terror			
		require("../../netwarelog/webconfig.php");

		if(!$this->connection = mysqli_connect($servidor,$usuariobd,$clavebd,$bd))
		{
			echo "<br><b style='color:red;'>Error al tratar de conectar</b><br>";	
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
			$result = $this->connection->query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);

			$this->close();
			return $result;
		}

		public function queryArray($sql, $relational = true){
			try{
				if (empty($sql)){
					throw new Exception("empty SQL");
				}
				$this->sql = $sql;
				$this->connect();

				$result = $this->connection->query($sql) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$sql);

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
	}
	?>
