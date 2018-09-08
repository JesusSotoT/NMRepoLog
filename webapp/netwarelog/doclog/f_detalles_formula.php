<?php
	
	$IDCAMPOF = "i".$rsd{'idcampo'}."_'+i";
	
	$formula="";
	if($rsd{'formula'}!=""&&$rsd{'formula'}!=" "){
	
		$campo="";
		$iniciacampo=false;
		$formulaa = $rsd{'formula'};
		$c=0;
		
		
		$formula=" if(document.getElementById('".$IDCAMPOF.")!=null){  ";
		
		$formula.=" document.getElementById('".$IDCAMPOF.").value = ";
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
					
					$sql = " select idcampo from catalog_campos where nombrecampo='".$campo."' and idestructura='".$_SESSION['idestructuradetalle']."'";
					//echo $sql;
					$rscatalogcampos = $conexion->consultar($sql);
					if($regrscatalogcampos = $conexion->siguiente($rscatalogcampos)){
						//$formula.=" ((double)document.getElementById('i".$regrscatalogcampos{'idcampo'}."').value.toString().replace(',','')) ";
						if($reg{'tipo'}=="varchar"){
							$formula.=" document.getElementById('i".$regrscatalogcampos{'idcampo'}."_'+i).value+'' ";	
						} else {
							//$formula.=" (Math.round(regresanumero(document.getElementById('i".$regrscatalogcampos{'idcampo'}."').value)*100)/100) ";
						    $formula.=" regresanumero(document.getElementById('i".$regrscatalogcampos{'idcampo'}."_'+i).value) ";
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
				$formula.="document.getElementById('".$IDCAMPOF.").value = ";
				$formula.="cuantos_decimales(document.getElementById('".$IDCAMPOF.").value";
				$formula.=", ".$cuantos_decimales."); \n ";
			} else {
				if(strlen($formato)!=0){
					$formula.="document.getElementById('".$IDCAMPOF.").value = sindecimales(document.getElementById('".$IDCAMPOF.").value); \n ";
				}
			}
			$formula.="document.getElementById('".$IDCAMPOF.").value = agregacomas(document.getElementById('".$IDCAMPOF.").value); \n ";				
		}
		
		$formula.="}; \n";
	
 	} //if($reg{'formula'}!=""){ 
	
	$script_calculaformulas.=$formula;
	
?>
