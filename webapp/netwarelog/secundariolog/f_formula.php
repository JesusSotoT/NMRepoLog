<?php
	
	$formula="";
	if($reg{'formula'}!=""&&$reg{'formula'}!=" "){
	
		$campo="";
		$iniciacampo=false;
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
					
				} else {
					
					if($iniciacampo){
						$campo.=$caracter;
					} else {
						$formula.=$caracter;
					}	
					
				} //if($caracter==="}"){
					
			} //if($caracter==="{"){						
				
		} //for($c=0;$c<=strlen($formulaa);$c++)
		
		$formula.="; \n";
		if($reg{'tipo'}!="varchar"){
			if($decimales){
				$formula.="document.getElementById('i".$reg{'idcampo'}."').value = dosdecimales(document.getElementById('i".$reg{'idcampo'}."').value); \n ";
			} else {
				$formula.="document.getElementById('i".$reg{'idcampo'}."').value = sindecimales(document.getElementById('i".$reg{'idcampo'}."').value); \n ";
			}
			$formula.="document.getElementById('i".$reg{'idcampo'}."').value = agregacomas(document.getElementById('i".$reg{'idcampo'}."').value); \n ";				
		}
		
	
 	} //if($reg{'formula'}!=""){ 
	
	$script_calculaformulas.=$formula;
	
?>