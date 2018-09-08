<?php

include_once("../../netwarelog/catalog/conexionbd.php");

$id = $_POST['id'];
$funcion = $_POST['funcion'];

call_user_func($funcion, $id);


function buscaDepartamento($idDepartamento)
{	
	if(is_numeric($idDepartamento)){
	try
	{
		imprimeFamilia($idDepartamento);
	}
	catch(Exception $e){ echo 0;}
	}
	else{imprimeFamilia($idDepartamento);}
}

function imprimeFamilia($idDep)
{
	$query_familia = mysql_query("SELECT idFam, nombre FROM mrp_familia WHERE idDep = ".$idDep);
	echo "<div><label>Familia:</label><br /><select id=i363'>";
	if(mysql_num_rows($query_familia)>0)
	{
		while ($row = mysql_fetch_array($query_familia)) 
		{
			$id=$row["idFam"];
			$nombre=$row["nombre"];
			
			echo "<option value='".$id."'>".$nombre."</option>";
		}
	}
	echo "</select></div>";
}

?>

