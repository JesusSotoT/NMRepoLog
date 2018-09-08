<?php
	
	$formula="";
	if($reg{'formula'}!=""&&$reg{'formula'}!=" "){
	

		$campo="";
		$iniciacampo=false;
		//$iniciacampodetalle=false;
		$formulaa = $reg{'formula'};
		$c=0;
		
		
		$formula=" document.getElementById('i".$reg{'idcampo'}."').value = ";
		//$formula=" alert('";
		
		//Inicia el examinado de la fórmula
		for($c=0;$c<=strlen($formulaa);$c++){
			
			//Obtiene caracter actual
			$caracter=substr($formulaa,$c,1);
			
			//Comparando caracter
			if($caracter==="{"){
				
				$iniciacampo=true;
				
			}else{
				
				if($caracter==="}"){					
					
					$sql = "select idcampo from catalog_campos where nombrecampo='".$campo."' and idestructura=".$idestructura;
					$rscatalogcampos = $conexion->consultar($sql);
					if($regrscatalogcampos = $conexion->siguiente($rscatalogcampos)){
						//$formula.=" ((double)document.getElementById('i".$regrscatalogcampos{'idcampo'}."').value.toString().replace(',','')) ";
						if($reg{'tipo'}=="varchar"){
							$formula.=" document.getElementById('i".$regrscatalogcampos{'idcampo'}."').value+'' ";	
						} else {
							//$formula.=" (Math.round(regresanumero(document.getElementById('i".$regrscatalogcampos{'idcampo'}."').value)*100)/100) ";
						    $formula.=" regresanumero(document.getElementById('i".$regrscatalogcampos{'idcampo'}."').value) ";
							//$formula.=" (Math.truncate(regresanumero(document.getElementById('i".$regrscatalogcampos{'idcampo'}."').value)*100)/100) ";
						}
						
					} else {
						$formula.=" 0 ";
					}
					$conexion->cerrar_consulta($rscatalogcampos);
					$campo="";
					$iniciacampo=false;
				
				
				
				} else if($caracter==="["||$caracter==="]") {
				
					//Inicia la parte para los campos en detalle
						if($caracter==="["){
							
							$iniciacampo=true;

						}else if($caracter==="]"){					

								$sql = "select idcampo from catalog_campos where nombrecampo='".$campo."' and idestructura=".$_SESSION['idestructuradetalle'];
								//echo " | SQL ".$sql." | ";
								$rscatalogcampos = $conexion->consultar($sql);
								if($regrscatalogcampos = $conexion->siguiente($rscatalogcampos)){
									if($reg{'tipo'}=="varchar"){
										$formula.=" suma_campo_detalles('".$regrscatalogcampos{'idcampo'}."')+'' ";	
									} else {
										$formula.=" regresanumero( suma_campo_detalles('".$regrscatalogcampos{'idcampo'}."') ) ";
									}						
								} else {
									$formula.=" 0 ";
								}
								$conexion->cerrar_consulta($rscatalogcampos);
								$campo="";
								$iniciacampo=false;

						} //if($caracter==="]")
					///////////
				
				} else {
					
					if($iniciacampo){
						$campo.=$caracter;
					} else {
						$formula.=$caracter;
					}	
					
				} //if($caracter==="}")
					
			} //if($caracter==="{")	 finaliza la parte para los campos de título
				
		} //for($c=0;$c<=strlen($formulaa);$c++)
		
		
		$formula.="; \n";
		if($reg{'tipo'}!="varchar"){
			if($decimales){
				$formula.="document.getElementById('i".$reg{'idcampo'}."').value=";
				$formula.="cuantos_decimales(document.getElementById('i".$reg{'idcampo'}."').value";
				$formula.=", ".$cuantos_decimales."); \n ";
			} else {
				if(strlen($formato)!=0){ 
					$formula.="document.getElementById('i".$reg{'idcampo'}."').value=sindecimales(document.getElementById('i".$reg{'idcampo'}."').value);\n";
				}
			}
			$formula.="document.getElementById('i".$reg{'idcampo'}."').value = agregacomas(document.getElementById('i".$reg{'idcampo'}."').value); \n ";				
		}
		
	
 	} //if($reg{'formula'}!=""){ 
	
	$script_calculaformulas.=$formula;
	
?>
