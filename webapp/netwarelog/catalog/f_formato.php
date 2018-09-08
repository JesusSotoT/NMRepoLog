<?php

	$mascara="";
	$class="";
	$alt="";
	$decimales=false;
	$cuantos_decimales=0;

	if(($reg{'tipo'}=="varchar")||($reg{'tipo'}=="bigint")||($reg{'tipo'}=="int")||($reg{'tipo'}=="double")){
		
		$formato=trim($reg{'formato'});
		
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
				$class=" class='iMask form-control' value='".$valor."' ";
				$alt=" alt='{ type:\"number\", groupSymbol:\",\", groupDigits:3, decDigits:0, decSymbol:\"\", stripMask:false }' ";				
				$f_found=true;
			}

			if(($formato==="0.00")||($formato==="0.000")||($formato==="0.0000")){
				$decimales=true;
				if(!is_numeric($valor)) $valor=0;
				$cuantos_decimales = strlen($formato)-2;	
				$valor = number_format($valor, $cuantos_decimales, '.', ',');
				$class=" class='iMask form-control' value='".$valor."' ";
				$alt=" alt='{ type:\"number\", groupSymbol:\",\", ";
				$alt.=" groupDigits:3, decDigits:".$cuantos_decimales.", ";
				$alt.=" decSymbol:\".\", stripMask:false }' ";
				$f_found=true;
			}

			if(($formato==="$.00")||($formato==="$.000")||($formato==="$.0000")){
				$decimales=true;				
				if(!is_numeric($valor)) $valor=0;	
				$cuantos_decimales = strlen($formato)-2;	
				$valor = number_format($valor, $cuantos_decimales, '.', ',');
				$class=" class='iMask form-control' value='".$valor."' ";
				$alt=" alt='{ type:\"number\", groupSymbol:\",\", ";
				$alt.=" groupDigits:3, decDigits:".$cuantos_decimales.", ";
				$alt.=" decSymbol:\".\", stripMask:false }' ";
				$simbolo_pesos="$";
				$f_found=true;
			}

			if($formato==="$"){
				if(!is_numeric($valor)) $valor="0";
				$valor = number_format($valor, 0, '.', ',');
				$class=" class='iMask form-control' value='".$valor."' ";
				$alt=" alt='{ type:\"number\", groupSymbol:\",\", groupDigits:3, decDigits:0, decSymbol:\"\", stripMask:false }' ";
				$simbolo_pesos="$";
				$f_found=true;
			}

			if($formato==="#"){
				//Campo tipo contraseña.
				$f_found=true;
			}

			if(!$f_found){
				//CAMPOS Q NO SON DE NUMERO ESPECIFICO
				$mascara=" $('#i".$reg{'idcampo'}."').mask('".$reg{'formato'}."'); \n";
			}
	
		}
	}
	
	if(($reg{'tipo'}=="time")||($reg{'tipo'}=="datetime")){	
		$mascara=" $('#i".$reg{'idcampo'}."t').mask('99:99'); \n";
	}
	
	if(($reg{'tipo'}=="datetime_seg")||($reg{'tipo'}=="datetime_seg_hr")){	
		$mascara=" $('#i".$reg{'idcampo'}."t').mask('99:99:99'); \n";
	}

	
	$script_inputmask.=$mascara;

//////


?>
