<?php

class fechas{
	
	//SETTES
	function regresaobjetofecha($idcampo,$incluirhora,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado,$incluirsegundos,$segundos){ 
				
		$objeto="		
							<table class='nmtabledate' border=0 cellpadding=0 cellspacing=0>
							<tr valign='top'>
							<td>
								<table border=0 cellpadding=0 cellspacing=0>
									<tr>
								    	<td class='form-inline'>
											
								    		<div class='input-group'>
				
												<input id='i".$idcampo."_2' name='i".$idcampo."_2' onchange='campo_onchange(this,true)'
												title='Día' onkeypress='return solofecha(event)' class='form-control nminputdate' ".$deshabilitado."
								    			size='2' maxlength='2' value='".$dia."' type='text' placeholder='Día'
								    			style='border-right:none;'>
								    			<span style='padding:0px;border-left:none;' class='input-group-addon'></span>
								    					
								    			<input id='i".$idcampo."_1' name='i".$idcampo."_1' onchange='campo_onchange(this,true)'
												title='Mes' onkeypress='return solofecha(event)' class='form-control nminputdate' ".$deshabilitado."
								    			size='2' maxlength='2' value='".$mes."' type='text' placeholder='Mes'
												style='border-left:none;border-right:none;'>
								    			<span style='padding:0px;border-left:none;' class='input-group-addon'></span>
	
				 								<input id='i".$idcampo."_3' name='i".$idcampo."_3' onchange='campo_onchange(this,true)'
				 								title='Año' onkeypress='return solofecha(event)' class='form-control nminputdate' ".$deshabilitado."
				 				    			size='4' maxlength='4' value='".$anual."' type='text' placeholder='Año'
								    			style='border-left:none;'>
				 				    			<span class='input-group-addon nmcalendarbutton' id='i".$idcampo."_img' ".$deshabilitado."
				 				    				title='Haga click para seleccionar una fecha.'>
				 				    				<i class='fa fa-calendar'></i>	
				 				    			</span>
				 				    		</div>
										</td>
										<!--
				 				    	<td>
											&nbsp;<img id='i".$idcampo."_img' class='datepicker' src='img/calendar.gif' ".$deshabilitado."
											alt='Seleccione una fecha.' title='Haga clic para seleccionar una fecha.'>&nbsp;
										</td>
										-->
									</tr>
													
									<!--<tr>
										<td align='center'><font size=1>día</font></td>
										<td align='center'><font size=1>mes</font></td>
										<td align='center'><font size=1>año &nbsp; &nbsp; </font></td>
									</tr>-->
													
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

		if($incluirsegundos&&$incluirhora){


			if($ampm=="0"){
					$objeto.="
						<td class='nmtabledate_hour' align='center'>
							".$this->regresaobjetohora_seg_hr($idcampo,$hora,$minutos,$segundos,$deshabilitado)."
						</td>
					";												
			} else {
					$objeto.="
						<td class='nmtabledate_hour' align='center'>
							".$this->regresaobjetohora_seg($idcampo,$hora,$minutos,$segundos,$ampm,$deshabilitado)."
						</td>
					";								
			}			
			
		} else {
		
			if($incluirhora){
				$objeto.="
							<td class='nmtabledate_hour' align='center'>
								".$this->regresaobjetohora2($idcampo,$hora,$minutos,$ampm,$deshabilitado)."
							</td>
						";
			}
		
		}
		

		$objeto.="</tr></table>";
		
		
		return $objeto;
	}
	
	function regresaobjetohora($idcampo){

		$hora = date("h");
		$minutos = date("i");
		$ampm = date("A");

		//HORA
		$objeto = "<select class='form-control nmselecthour ' id='i".$idcampo."h' >";
		$sel = "";
		for($i=1;$i<=12;$i++){
			if($i==$hora) { $sel="selected"; } else { $sel=""; }			
			$e="0".$i;
			if($i>=10) $e=$i;
			$objeto.= "<option value=".$i." ".$sel." >".$e."</option>";
		}
		$objeto.= "</select> : ";

		//MINUTOS
		$objeto.= "<select class='form-control nmselecthour ' id='i".$idcampo."m' >";
		$sel = "";
		for($i=0;$i<=59;$i++){
			if($i==$minutos) { $sel="selected"; } else { $sel=""; }
			$e="0".$i;
			if($i>=10) $e=$i;
			$objeto.= "<option value=".$i." ".$sel." >".$e."</option>";
		}
		$objeto.= "</select> ";
		
		//AM-PM
		$objeto.= "<select class='form-control nmselecthour' id='i".$idcampo."ampm' >";
			if($ampm=="AM") $sel="selected"; $sel="";			
			$objeto.= "<option value='am' ".$sel.">AM</option>";
			if($ampm=="PM") $sel="selected"; $sel="";			
			$objeto.= "<option value='pm' ".$sel.">PM</option>";
		$objeto.= "</select>";				
				
		return $objeto;
		
	}
	
	function regresaobjetohora2($idcampo,$hora,$minutos,$ampm,$deshabilitado){
		
		$objeto = "<div class='input-group'>";
		$objeto.= "
				   <input id='i".$idcampo."t' ".$deshabilitado."
				       name='i".$idcampo."t' 
				       type='text' 
					   size='6' 
					   maxlength='5' 
					   value='".$hora.":".$minutos."' 
					   class=' form-control nminputdate ' 
					   onchange='campo_onchange(this,true)' 
					   style='border-right:none;'
			  	   />								
				   <span style='padding:3px;' class='input-group-addon'><i class='fa fa-clock-o'></i></span>
				  ";				
				
		//AM-PM
		$objeto.= "<select class='form-control nmselecthour ' id='i".$idcampo."ampm' name='i".$idcampo."ampm' onchange='campo_onchange(this,true)' ".$deshabilitado." >";
			
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
		$objeto.= "</div>";
		
		return $objeto;	

	}
	
	function regresaobjetohora_seg($idcampo,$hora,$minutos,$segundos,$ampm,$deshabilitado){
		
		$objeto = "
				   <input id='i".$idcampo."t' ".$deshabilitado."
				       name='i".$idcampo."t' 
				       type='text' 
					   size='9' 
					   maxlength='8' 
					   value='".$hora.":".$minutos.":".$segundos."' 
					   class=' form-control nminputdate ' 
					   onchange='campo_onchange(this,true)' 
					   style='border-right:none;'
			  	   />
				   <span class='input-group-addon'><i class='fa fa-clock-o'></i></span>	
				  ";				
				
		//AM-PM
		$objeto.= "<select class='form-control nmselecthour ' id='i".$idcampo."ampm' name='i".$idcampo."ampm' onchange='campo_onchange(this,true)' ".$deshabilitado." >";
			
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
		
		$objeto = "<div class='input-group'>";
		$objeto.= "
				   <input id='i".$idcampo."t' ".$deshabilitado."
				       name='i".$idcampo."t' 
				       type='text' 
					   size='9' 
					   maxlength='8' 
					   value='".$hora.":".$minutos.":".$segundos."' 
					   class=' form-control nminputdate ' 
					   onchange='campo_onchange(this,true)' 
			  	   />
					<span class='input-group-addon'><i class='fa fa-clock-o'></i></span>	
				  ";	
		$objeto.="</div>";
		
		return $objeto;	

	}	
	

	
}	
	
?>