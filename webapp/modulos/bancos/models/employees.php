<?php
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli.php"); // funciones mySQLi

	class Employee extends Connection
	{
		function getEmployees()
		{	
			$myQuery = "SELECT username FROM employees";
			$employees = $this->query($myQuery);
			return $employees;
		}
	}
	$Employee = new Employee();
?>