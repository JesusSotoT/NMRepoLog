<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../catalog/conexionbd.php");
	include("clases/clseguridad.php");		

		if(session_id()=='') session_start();

	//Obteniendo el id del documento
	$iddocumento = mysql_real_escape_string($_GET['iddocumento']);
	
	//Obteniendo los datos del documento
	$sql=" select * from doclog_titulos where iddocumento=".$iddocumento;
	$result_doclog = $conexion->consultar($sql);
	
	$nombredocumento = "";
	if($rs = $conexion->siguiente($result_doclog)){
		$_SESSION['iddocumento'] = $iddocumento;
		$_SESSION['nombredocumento'] = $rs{"nombredocumento"};
		$_SESSION['idestructuratitulo'] = $rs{"idestructuratitulo"};
		
		//Obteniendo la tabla detalles
		$sqld = " select * from doclog_detalles where iddocumento=".$iddocumento;
		$result_doclogd = $conexion->consultar($sqld);		
		if($rsd = $conexion->siguiente($result_doclogd)){
			$_SESSION['idestructuradetalle']=$rsd{"idestructuradetalle"};
			
			//Nombre de la tabla...
				$sqlestdetalle = "	select nombreestructura
									from catalog_estructuras
									where idestructura=".$rsd{"idestructuradetalle"}."
									";
				$resultestdetalle = $conexion->consultar($sqlestdetalle);
				$nombreestructuradetalle="";
				if($rsestdetalle = $conexion->siguiente($resultestdetalle)){
					$_SESSION['nombreestructuradetalle'] = $rsestdetalle{"nombreestructura"};
				}
				$conexion->cerrar_consulta($resultestdetalle);
			////
			
			
			//Nombres de los campos folio y idlinea ...
				$sqlcamposdetalle = " select nombrecampo 
									  from catalog_campos where idestructura='".$rsd{"idestructuradetalle"}."' 
									  order by orden";
				$resultcamposdetalle = $conexion->consultar($sqlcamposdetalle);				
				$nocampo = 0;
				while($rscdetalle = $conexion->siguiente($resultcamposdetalle)){
					$nocampo+=1;
					if($nocampo==1){
						$_SESSION['campofolio'] = $rscdetalle{"nombrecampo"};						
					} else if($nocampo==2){
						$_SESSION['campoidlinea'] = $rscdetalle{"nombrecampo"};
					} else {
						break;
					}
				}
				$conexion->cerrar_consulta($resultcamposdetalle);							
			/////
							
			
			//echo $_SESSION['idestructuradetalle'];
		}
		$conexion->cerrar_consulta($result_doclogd);						
		
	}
	
	$conexion->cerrar_consulta($result_doclog);
	$conexion->cerrar();


	$url_doclog_catalog_gestor = "/webapp/netwarelog/doclog/gestor.php?idestructura=".$_SESSION['idestructuratitulo'];
	$accelog_access->nmerror_log($url_doclog_catalog_gestor);
	$accelog_access->add_url($url_doclog_catalog_gestor);

	$url_doclog_catalog_gestor = "gestor.php?idestructura=".$_SESSION['idestructuratitulo'];
	header("location: ".$url_doclog_catalog_gestor);


?>
