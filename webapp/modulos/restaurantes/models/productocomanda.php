<?php 

require("models/connection_sqli.php"); // funciones mySQLi

class productoComandaModel extends Connection {

	function borrar($codigo)
	{
		$borrar = " DELETE ";
		$borrar .= " FROM mrp_producto ";
		$borrar .= " WHERE codigo = '".$codigo."' ";

		$result = $this->query($borrar);

        return json_encode($result);
	}	
}
?>