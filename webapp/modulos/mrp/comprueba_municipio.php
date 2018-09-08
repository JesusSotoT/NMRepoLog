<?php

include_once("../../netwarelog/catalog/conexionbd.php");

$funcion = $_POST['funcion'];
$funcion();

function compruebaEstadoMunicipio()
{
	$idPrv = $_POST['idPrv'];
	
	$query = mysql_query("SELECT idestado, idmunicipio FROM mrp_proveedor WHERE idPrv =".$idPrv);
	if(mysql_num_rows($query)>0)
	{
		if ($row = mysql_fetch_array($query)) 
		{
			$idestado=$row["idestado"];
			$idmunicipio=$row["idmunicipio"];
			echo $idestado."///$$$@@@".$idmunicipio;
		}
		else 
		{
			echo "false";
		}
	}
	else 
	{
		echo "false";
	}
}

function cargaEstadoMunicipio()
{
	$idEst = $_POST['idEst'];
	$idMun = $_POST['idMun'];
	
	$cadena;
	
	$query_estado = mysql_query("SELECT idestado, estado FROM estados ORDER by estado");
	$cadena .= "<div><label>Estado:</label><br /><select id='i389'>";
	$cadena .= "<option value=''>Selecciona estado</option>";
	if(mysql_num_rows($query_estado)>0)
	{
		while ($row = mysql_fetch_array($query_estado)) 
		{
			$id=$row["idestado"];
			$nombre=$row["estado"];
			
			if($id == $idEst)
			{
				$cadena .= "<option value='".$id."' selected>".$nombre."</option>";
			}
			else
			{
				$cadena .= "<option value='".$id."'>".$nombre."</option>";
			}
		}
	}
	
	$cadena .= "</select></div>";
	$cadena .= "///$$$@@@";
		
	$query_municipio = mysql_query("SELECT idmunicipio, municipio FROM municipios WHERE idestado = ".$idEst." ORDER by municipio");
	$cadena .=  "<div><label>Municipio:</label><br /><select id='i390'>";
	$cadena .=  "<option value=''>Selecciona municipio</option>";
	if(mysql_num_rows($query_municipio)>0)
	{
		while ($row = mysql_fetch_array($query_municipio)) 
		{
			$id=$row["idmunicipio"];
			$nombre=$row["municipio"];
			
			if($id == $idMun)
			{
				$cadena .= "<option value='".$id."' selected>".$nombre."</option>";
			}
			else
			{
				$cadena .= "<option value='".$id."'>".$nombre."</option>";
			}
		}
	}
	$cadena .=  "</select></div>";
	
	echo $cadena;
}

?>

