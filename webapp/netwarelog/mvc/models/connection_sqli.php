<?php

	//Esta es la clase de coneccion Padre que hereda los atributos a los modelos
	class Connection
	{
		public $connection;

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

		//Funcion que genera las multiples consultas a la base de datos
		public function multi_query($query)
		{
			$this->connect();
			$result = $this->connection->multi_query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);
			$this->close();
			return $result;
		}

		public function insert_id($query)
		{
			if(stristr($query, 'insert'))
			{
				$this->connect();
				$result = $this->connection->query($query) or die("<b style='color:red;'>Error en la consulta.</b><br /><br />".$this->connection->error."<be>Error:<br>".$query);
				$result = $result->insert_id;
				$this->close();
				return $result;
			}
			else
			{
				echo "La consulta no incluye un INSERT.";
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
                        $this->connection->query($sqltabla);
			//mysql_query($sql, $this->cbase);
                

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
                $this->connection->query($sql);

								//Insertar Fecha de Acceso en la BD Transversal
								$arrInstanciaG = $_SESSION["accelog_nombre_instancia"];
                                //echo $arrInstanciaG;
								$fechaultimoacceso=date('Y-m-d H:i:00', time());
								$servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
								$objCon = mysqli_connect($servidor, "nmdevel", "nmdevel", "netwarstore");
								$strSql = "update customer set fechaultimoacceso='".$fechaultimoacceso."' where instancia='".$arrInstanciaG."'";
								mysqli_query($objCon,$strSql);
								mysqli_query($objCon,$strSql); 
								mysqli_close($objCon);

        }
	}
?>
