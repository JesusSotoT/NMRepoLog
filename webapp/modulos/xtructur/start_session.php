<?php
	include('conexiondb.php');
	$id_obra=$_POST['id_obra'];

    $SQL = "SELECT id,obra,inicio,termino FROM constru_generales WHERE borrado=0 AND id='$id_obra';";
	$result = $mysqli->query($SQL);

	if($result->num_rows>0) {
		$array=array();
		$row = $result->fetch_array();

		$array['xtructur']['id_obra']=$row['id'];
		$array['xtructur']['obra']=$row['obra'];

		$array['xtructur']['obra_ini']=$row['inicio'];
		$array['xtructur']['obra_fin']=$row['termino'];

		$array['xtructur']['instancias_acontia']=array();
		array_push($array['xtructur']['instancias_acontia'],"urban","mlog","xtructurpruebas","newpruebas");


		$array['xtructur']['time']=time();

		setcookie("xtructur",serialize($array['xtructur']),time()+86400*1, '/'); 

		$JSON = array('success' =>1);
	}else{
		$JSON = array('success' =>0);
	}

	echo json_encode($JSON);
?>