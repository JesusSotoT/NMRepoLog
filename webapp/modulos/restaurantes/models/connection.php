<?php
class Connection
{
	public $connection;

	function connect()
	{
		if(!$this->connection = mysql_connect("localhost","root","root")){
			echo "<br><font color='red'>Error al tratar de conectar</font><br>";	
		}

		if(!mysql_select_db("prueba",$this->connection)){
			echo "<br><font color='red'>Error no se selecciono ninguna base de datos</font><br>";	
		}
	}

	function close()
	{
		mysql_close($this->connection);
	}

	function query($query)
	{
	$this->connect();
	 $result = mysql_query($query,$this->connection);	
	 $this->close();
	 return $result;
	}
}
?>