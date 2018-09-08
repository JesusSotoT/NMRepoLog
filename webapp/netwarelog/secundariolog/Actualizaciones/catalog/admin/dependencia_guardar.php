<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/cldependencia.php");		
		
		session_start();
		
		$idestructura = $_SESSION['idestructura'];
		
		$dependencia = new dependencia();
		
		$dependencia->setidcampo($_REQUEST['txtidcampo']);
		$dependencia->settipodependencia($_REQUEST['cmbtipodependencia']);
		$dependencia->setdependenciatabla($_REQUEST['cmbdependenciatabla']);
		$dependencia->setdependenciacampovalor($_REQUEST['cmbvalor']);
		$dependencia->setdependenciacampodescripcion($_REQUEST['cmbdescripcion']);
		
		
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
		$dependencia->guardar($conexion);
		
?>