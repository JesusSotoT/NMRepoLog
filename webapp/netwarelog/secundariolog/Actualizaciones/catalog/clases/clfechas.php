<?php

class fechas{
	
	//SETTES
	function regresaobjetofecha($idcampo,$incluirhora,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado){ 
				
		$objeto="		
							<table border=0 cellpadding=1 cellspacing=0>
							<tr>
							<td>
								<table border=0 cellpadding=0 cellspacing=0>
									<tr>
										<td class='fecha'>
							
								<input id='i".$idcampo."_2' name='i".$idcampo."_2' onchange='campo_onchange(this,true)'
									title='Día' onkeypress='return solofecha(event)' class='fecha' ".$deshabilitado."
								    size='2' maxlength='2' value='".$dia."' type='text'> /
							
								<input id='i".$idcampo."_1' name='i".$idcampo."_1' onchange='campo_onchange(this,true)'
									title='Mes' onkeypress='return solofecha(event)' class='fecha' ".$deshabilitado."
								    size='2' maxlength='2' value='".$mes."' type='text'> /
							
				 				<input id='i".$idcampo."_3' name='i".$idcampo."_3' onchange='campo_onchange(this,true)'
				 					title='Año' onkeypress='return solofecha(event)' class='fecha' ".$deshabilitado."
				 				    size='4' maxlength='4' value='".$anual."' type='text'>
							</td>
							<td>

								&nbsp;<img id='i".$idcampo."_img' class='datepicker' src='img/calendar.gif' ".$deshabilitado."
								alt='Seleccione una fecha.' title='Haga clic para seleccionar una fecha.'>&nbsp;

				
										</td>
									</tr>
								</table>
							
																	
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
							</td>						
		";		

		if($incluirhora){
			$objeto.="
						<td>
							".$this->regresaobjetohora2($idcampo,$hora,$minutos,$ampm,$deshabilitado)."
						</td>
					";
		}

		$objeto.="</tr></table>";
		
		
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
	
	function regresaobjetohora2($idcampo,$hora,$minutos,$ampm,$deshabilitado){
		
		$objeto = "
				   <input id='i".$idcampo."t' ".$deshabilitado."
				       name='i".$idcampo."t' 
				       type='text' 
					   size='6' 
					   maxlength='5' 
					   value='".$hora.":".$minutos."' 
					   class='hora' 
					   onchange='campo_onchange(this,true)' 
			  	   />
				  ";				
				
		//AM-PM
		$objeto.= "<select class='hora' id='i".$idcampo."ampm' name='i".$idcampo."ampm' onchange='campo_onchange(this,true)' ".$deshabilitado." >";
			
			if($ampm=="AM"||$ampm=="am"){
				$sel="selected";
			} else {
				$sel="";				
			}
			$objeto.= "<option value='am' ".$sel.">AM</option>";
			
			if($ampm=="PM"||$ampm=="pm"){
				$sel="selected";
			} else{
				$sel="";	
			} 
			$objeto.= "<option value='pm' ".$sel.">PM</option>";
			
		$objeto.= "</select>";				
		
		return $objeto;	

	}
	
	

	
}	
	
?>