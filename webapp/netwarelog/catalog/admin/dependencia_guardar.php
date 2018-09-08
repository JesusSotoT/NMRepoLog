<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/cldependencia.php");		
		
		//session_start();

		//CSRF
		$reset_vars = false;
		include("../clases/clcsrf.php");	
		if(!$csrf->check_valid('post')){
				$accelog_access->raise_404(); 
				exit();
		}

		$idestructura = $_SESSION['idestructura'];
		
		$dependencia = new dependencia();
		
		$dependencia->setidcampo($conexion->escapalog($_REQUEST['txtidcampo']));
		$dependencia->settipodependencia($conexion->escapalog($_REQUEST['cmbtipodependencia']));
		$dependencia->setdependenciatabla($conexion->escapalog($_REQUEST['cmbdependenciatabla']));
		$dependencia->setdependenciacampovalor($conexion->escapalog($_REQUEST['cmbvalor']));


		$dependenciacampodescripcion = "";
		//error_log($_REQUEST['cmbdescripcion']);
		foreach($_REQUEST['cmbdescripcion'] as $dep){
			if($dependenciacampodescripcion!="") $dependenciacampodescripcion.=",";
			$dependenciacampodescripcion.=" ".$conexion->escapalog($dep)." ";
		}
		//error_log($dependenciacampodescripcion);
		
	  $dependencia->setdependenciacampodescripcion($dependenciacampodescripcion);
		
		
		//Dependencia Compuesta
		if($dependencia->gettipodependencia()=="C"){
			
			$sql = "
				select nombrecampo 
				from catalog_campos
				where idestructura='".$idestructura."'
				";
			$result = $conexion->consultar($sql);

			$filtros = array();
			while($reg=$conexion->siguiente($result)){
				if(!empty($_REQUEST["chk".$reg{'nombrecampo'}])){
					$filtros[]=$reg{'nombrecampo'};
				}				
			}
			$conexion->cerrar_consulta($result);	
			
			$dependencia->setfiltros($filtros);
		}
		
		
		//Dependencia Compuesta con título
		$campostitulos = array();
		if(isset($_REQUEST["lstcampostitulos"])){
			$campostitulos = $_REQUEST["lstcampostitulos"];
		}		
		$dependencia->setcampostitulos($campostitulos);
		
		
		$dependencia->guardar($conexion);
		
?>
