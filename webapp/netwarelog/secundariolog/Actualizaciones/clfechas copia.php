<?php

class fechas{
	
	//SETTES
	function regresaobjetofecha($idcampo){ 
		
		$dia = date("d");
		$mes = date("m");
		$anual = date("Y");
		
		$objeto="		

							<input id='i".$idcampo."_2' name='i".$idcampo."_2'
								title='Día' onkeypress='return solofecha(event)' class='fecha' 
							    size='2' maxlength='2' value='".$dia."' type='text'> /
							
							<input id='i".$idcampo."_1' name='i".$idcampo."_1'							
								title='Mes' onkeypress='return solofecha(event)' class='fecha' 
							    size='2' maxlength='2' value='".$mes."' type='text'> /
							
			 				<input id='i".$idcampo."_3' name='i".$idcampo."_3' 
			 					title='Año' onkeypress='return solofecha(event)' class='fecha' 
			 				    size='4' maxlength='4' value='".$anual."' type='text'>
						
							<img id='i".$idcampo."_img' class='datepicker' src='img/calendar.gif' '
							alt='Seleccione una fecha.' title='Haga clic para seleccionar una fecha.'>
						
							<script type='text/javascript'>
								Calendar.setup({
									inputField	 : 'i".$idcampo."_3',
									baseField    : 'i".$idcampo."',
									displayArea  : 'i".$idcampo."_area',
									button		 : 'i".$idcampo."_img',
									ifFormat	 : '%B %e, %Y',
									onSelect	 : selectDate
								});
							</script>												    					    
						
		";		
		return $objeto;
	}
	
	function regresaobjetohora($idcampo){

		$hora = date("h");
		$minutos = date("i");
		$ampm = date("A");

		//HORA
		$objeto = "<select class='hora' id='i".$idcampo."h' >";
		$sel = "";
		for($i=1;$i<=12;$i++){
			if($i==$hora) { $sel="selected"; } else { $sel=""; }			
			$e="0".$i;
			if($i>=10) $e=$i;
			$objeto.= "<option value=".$i." ".$sel." >".$e."</option>";
		}
		$objeto.= "</select> : ";

		//MINUTOS
		$objeto.= "<select class='hora' id='i".$idcampo."m' >";
		$sel = "";
		for($i=0;$i<=59;$i++){
			if($i==$minutos) { $sel="selected"; } else { $sel=""; }
			$e="0".$i;
			if($i>=10) $e=$i;
			$objeto.= "<option value=".$i." ".$sel." >".$e."</option>";
		}
		$objeto.= "</select> ";
		
		//AM-PM
		$objeto.= "<select class='hora' id='i".$idcampo."ampm' >";
			if($ampm=="AM") $sel="selected"; $sel="";			
			$objeto.= "<option value='am' ".$sel.">AM</option>";
			if($ampm=="PM") $sel="selected"; $sel="";			
			$objeto.= "<option value='pm' ".$sel.">PM</option>";
		$objeto.= "</select>";				
				
		return $objeto;
		
	}
	
	

	
}	
	
?>