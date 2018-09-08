<?php

include_once("../../netwarelog/catalog/conexionbd.php");

$id = $_POST['id'];
$funcion = $_POST['funcion'];

call_user_func($funcion, $id);


function buscaEstado($idEstado)
{	
	if(is_numeric($idEstado)){
	try
	{
		imprimeMunicipio($idEstado);
	}
	catch(Exception $e){ echo 0;}
	}
	else{imprimeMunicipio($idEstado);}
}

function imprimeMunicipio($idEst)
{
	$query_municipio = mysql_query("SELECT idmunicipio, municipio FROM municipios WHERE idestado = ".$idEst." ORDER by municipio");
	echo "<div><label>Municipio:</label><br /><select id='i390'>";
	echo "<option value=''>Selecciona municipio</option>";
	if(mysql_num_rows($query_municipio)>0)
	{
		while ($row = mysql_fetch_array($query_municipio)) 
		{
			$id=$row["idmunicipio"];
			$nombre=$row["municipio"];
			
			echo "<option value='".$id."'>".$nombre."</option>";
		}
	}
	echo "</select></div>";
}

?>

