<?php

	$mascara="";
	$class="";
	$alt="";
	$decimales=false;
	$cuantos_decimales=0;
	$imask_en_detalle = false;

	if(($rsd{'tipo'}=="varchar")||($rsd{'tipo'}=="bigint")||($rsd{'tipo'}=="int")||($rsd{'tipo'}=="double")){
		
		$formato=trim($rsd{'formato'});
		
		if(strlen($formato)!=0){
			
			/* VALORES
			\\n 1. 0.00 \\t -> Generará: \\t #,###,##0.00
			\\n 2. $.00 \\t -> Generará: \\t $ #,###,##0.00
			\\n 3. 0     \\t -> Generará: \\t #,###,##0
			\\n 4. $     \\t -> Generará: \\t $ #,###,##0
			*/

			$f_found=false;
		
			if($formato==="0"){
				if(!is_numeric($valor)) $valor="0";
				$valor = number_format($valor, 0, '.', ',');
				$class=" class='iMask' value='".$valor."' ";
				$alt=" alt='{ type:\\\"number\\\", groupSymbol:\\\",\\\", groupDigits:3, decDigits:0, decSymbol:\\\"\\\", stripMask:false }' ";				
				$f_found=true;
				$imask_en_detalle = true;
				$cuantos_formato+=1;
			}

			if(($formato==="0.00")||($formato==="0.000")||($formato==="0.0000")){
				$decimales=true;
				if(!is_numeric($valor)) $valor="0";
				$cuantos_decimales = strlen($formato)-2;
				$valor = number_format($valor, $cuantos_decimales, '.', ',');
				$class=" class='iMask' value='".$valor."' ";
				$alt=" alt='{ type:\\\"number\\\", groupSymbol:\\\",\\\", groupDigits:3, ";
				$alt.=" decDigits:".$cuantos_decimales.", decSymbol:\\\".\\\", stripMask:false }' ";
				$f_found=true;
				$imask_en_detalle = true;
				$cuantos_formato+=1;				
			}

			if(($formato==="$.00")||($formato==="$.000")||($formato==="$.0000")){
				$decimales=true;				
				if(!is_numeric($valor)) $valor="0";
				$cuantos_decimales = strlen($formato)-2;
				$valor = number_format($valor, $cuantos_decimales, '.', ',');
				$class=" class='iMask' value='".$valor."' ";
				$alt=" alt='{ type:\\\"number\\\", groupSymbol:\\\",\\\", groupDigits:3, ";
				$alt.=" decDigits:".$cuantos_decimales.", decSymbol:\\\".\\\", stripMask:false }' ";
				$simbolo_pesos="$ ";
				$f_found=true;
				$imask_en_detalle = true;
				$cuantos_formato+=1;				
			}

			if($formato==="$"){
				if(!is_numeric($valor)) $valor="0";
				$valor = number_format($valor, 0, '.', ',');
				$class=" class='iMask' value='".$valor."' ";
				$alt=" alt='{ type:\\\"number\\\", groupSymbol:\\\",\\\", groupDigits:3, decDigits:0, decSymbol:\\\"\\\", stripMask:false }' ";
				$simbolo_pesos="$ ";
				$f_found=true;
				$imask_en_detalle = true;
				$cuantos_formato+=1;				
			}

			if($formato==="#"){
				//Campo tipo contraseña.
				$f_found=true;
			}

			if(!$f_found){
				//CAMPOS Q NO SON DE NUMERO ESPECIFICO
				$mascara=" $('#i".$rsd{'idcampo'}."_'+fila+'').mask('".$rsd{'formato'}."'); \n";
			}
	
		}
	}
	
	if(($rsd{'tipo'}=="time")||($rsd{'tipo'}=="datetime")){	
		$mascara=" $('#i".$rsd{'idcampo'}."_'+fila+'t').mask('99:99'); \n";
	}
	
	if(($rsd{'tipo'}=="datetime_seg")||($rsd{'tipo'}=="datetime_seg_hr")){	
		$mascara=" $('#i".$rsd{'idcampo'}."_'+fila+'t').mask('99:99:99'); \n";
	}
	$valor="";
	
	$script_inputmask.=$mascara;

//////


?>
