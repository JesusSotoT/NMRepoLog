<?php

class fechas{
	
	
	var $poneri = true;
	var $yalainclui = false;
	
	
	function noponeri(){
		$this->poneri = false;
	}
	
	function get_yalainclui(){
		return $this->yalainclui;
	}
	
	
	//SETTES
	function regresaobjetofecha($idcampo,$incluirhora,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado,$incluirsegundos,$segundos){ 
		
		if($this->poneri) $idcampo = "i".$idcampo;
		$this->yalainclui=true;
		
		$objeto="		
							<table border=0 cellpadding=1 cellspacing=0>
							<tr>
							<td>
								<table border=0 cellpadding=0 cellspacing=0>
									<tr>
										<td class='fecha'>
							
								<input id='".$idcampo."_2' name='".$idcampo."_2' onchange='campo_onchange(this,true)'
									title='Día' onkeypress='return solofecha(event)' class='fecha' ".$deshabilitado."
								    size='2' maxlength='2' value='".$dia."' type='text'> /
							
								<input id='".$idcampo."_1' name='".$idcampo."_1' onchange='campo_onchange(this,true)'
									title='Mes' onkeypress='return solofecha(event)' class='fecha' ".$deshabilitado."
								    size='2' maxlength='2' value='".$mes."' type='text'> /
							
				 				<input id='".$idcampo."_3' name='".$idcampo."_3' onchange='campo_onchange(this,true)'
				 					title='Año' onkeypress='return solofecha(event)' class='fecha' ".$deshabilitado."
				 				    size='4' maxlength='4' value='".$anual."' type='text'>
							</td>
							<td>

								&nbsp;<img id='".$idcampo."_img' class='datepicker' src='img/calendar.gif' ".$deshabilitado."
								alt='Seleccione una fecha.' title='Haga clic para seleccionar una fecha.'>&nbsp;

				
										</td>
									</tr>
								</table>
							
																	
								<script type='text/javascript'>
									Calendar.setup({ 
										inputField	 : '".$idcampo."_3',
										baseField    : '".$idcampo."',
										displayArea  : '".$idcampo."_area',
										button		 : '".$idcampo."_img',
										ifFormat	 : '%B %e, %Y',
										onSelect	 : selectDate
									});
								</script>												    					    
							</td>						
		";		

		if($incluirsegundos&&$incluirhora){

			if($ampm=="0"){
					$objeto.="
						<td>
							".$this->regresaobjetohora_seg_hr($idcampo,$hora,$minutos,$segundos,$deshabilitado)."
						</td>
					";												
			} else {
					$objeto.="
						<td>
							".$this->regresaobjetohora_seg($idcampo,$hora,$minutos,$segundos,$ampm,$deshabilitado)."
						</td>
					";								
			}		
			
		} else {
		
			if($incluirhora){
				$objeto.="
							<td>
								".$this->regresaobjetohora2($idcampo,$hora,$minutos,$ampm,$deshabilitado)."
							</td>
						";
			}
		
		}
		

		$objeto.="</tr></table>";
		$this->yalainclui=false;
		
		return $objeto;
	}
	
	function regresaobjetohora($idcampo){

		if($this->poneri&&(!$this->get_yalainclui())) $idcampo = "i".$idcampo;		

		$hora = date("h");
		$minutos = date("i");
		$ampm = date("A");

		//HORA
		$objeto = "<select class='hora' id='".$idcampo."h' >";
		$sel = "";
		for($i=1;$i<=12;$i++){
			if($i==$hora) { $sel="selected"; } else { $sel=""; }			
			$e="0".$i;
			if($i>=10) $e=$i;
			$objeto.= "<option value=".$i." ".$sel." >".$e."</option>";
		}
		$objeto.= "</select> : ";

		//MINUTOS
		$objeto.= "<select class='hora' id='".$idcampo."m' >";
		$sel = "";
		for($i=0;$i<=59;$i++){
			if($i==$minutos) { $sel="selected"; } else { $sel=""; }
			$e="0".$i;
			if($i>=10) $e=$i;
			$objeto.= "<option value=".$i." ".$sel." >".$e."</option>";
		}
		$objeto.= "</select> ";
		
		//AM-PM
		$objeto.= "<select class='hora' id='".$idcampo."ampm' >";
			if($ampm=="AM") $sel="selected"; $sel="";			
			$objeto.= "<option value='am' ".$sel.">AM</option>";
			if($ampm=="PM") $sel="selected"; $sel="";			
			$objeto.= "<option value='pm' ".$sel.">PM</option>";
		$objeto.= "</select>";				
				
		return $objeto;
		
	}
	
	function regresaobjetohora2($idcampo,$hora,$minutos,$ampm,$deshabilitado){
		
		if($this->poneri&&(!$this->get_yalainclui())) $idcampo = "i".$idcampo;
		
		$objeto = "
				   <input id='".$idcampo."t' ".$deshabilitado."
				       name='".$idcampo."t' 
				       type='text' 
					   size='6' 
					   maxlength='5' 
					   value='".$hora.":".$minutos."' 
					   class='hora' 
					   onchange='campo_onchange(this,true)' 
			  	   />
				  ";				
				
		//AM-PM
		$objeto.= "<select class='hora' id='".$idcampo."ampm' name='".$idcampo."ampm' onchange='campo_onchange(this,true)' ".$deshabilitado." >";
			
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
	
	function regresaobjetohora_seg($idcampo,$hora,$minutos,$segundos,$ampm,$deshabilitado){
		
		if($this->poneri&&(!$this->get_yalainclui())) $idcampo = "i".$idcampo;
		
		$objeto = "
				   <input id='".$idcampo."t' ".$deshabilitado."
				       name='".$idcampo."t' 
				       type='text' 
					   size='9' 
					   maxlength='8' 
					   value='".$hora.":".$minutos.":".$segundos."' 
					   class='hora' 
					   onchange='campo_onchange(this,true)' 
			  	   />
				  ";				
				
		//AM-PM
		$objeto.= "<select class='hora' id='".$idcampo."ampm' name='".$idcampo."ampm' onchange='campo_onchange(this,true)' ".$deshabilitado." >";
			
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


	function regresaobjetohora_seg_hr($idcampo,$hora,$minutos,$segundos,$deshabilitado){
		
		if($this->poneri&&(!$this->get_yalainclui())) $idcampo = "i".$idcampo;
		
		$objeto = "
				   <input id='".$idcampo."t' ".$deshabilitado."
				       name='".$idcampo."t' 
				       type='text' 
					   size='9' 
					   maxlength='8' 
					   value='".$hora.":".$minutos.":".$segundos."' 
					   class='hora' 
					   onchange='campo_onchange(this,true)' 
			  	   />
				  ";							
		
		return $objeto;	

	}		
	
	
	
}	
	
?>