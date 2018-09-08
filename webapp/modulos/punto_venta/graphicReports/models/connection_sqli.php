<?php
	/*===========================================================================
	=            connection_sqli.php - Miguel Angel Velazco Martinez            =
	===========================================================================*/
	
	/**
	
		Nota Importante:
	    	- Esta clase modelo es utilizada por los reportes graficos y el corte de caja.
			- Es una copia exacta de la clase del sistema contable.
	
	**/
	
	/*-----  End of connection_sqli.php - Miguel Angel Velazco Martinez  ------*/
	
	class Connection
	{
		public $connection;

		public function connect()
		{
			//Cuidado con estas líneas de terror			
			require("../../../../netwarelog/webconfig.php");
			if(!$this->connection = mysqli_connect($servidor,$usuariobd,$clavebd,$bd))
			{
				echo "<br><b style='color:red;'>Error al tratar de conectar</b><br>";	
			}
			$this->connection->set_charset("utf8");
		}

		public function close()
		{
			$this->connection->close();
		}

		public function query($query)
		{
			$this->connect();
			$result = $this->connection->query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);
			$this->close();
			return $result;
		}

		public function indexQuery($query)
		{
			$this->connect();
			$this->connection->query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);
			$result = $this->connection->insert_id;
			$this->connection->close();
			return $result;
		}
		public function insert_id($query)
		{
			$this->connect();
			if(stristr($query, 'insert'))
			{
				$this->connection->query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);
				$insert_id = $this->connection->insert_id;
				$this->connection->close();
				return $insert_id;
			}
			else
			{
				$this->connection->close();
				return "La consulta no incluye un INSERT.";
			}
		}

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

	}
?>
