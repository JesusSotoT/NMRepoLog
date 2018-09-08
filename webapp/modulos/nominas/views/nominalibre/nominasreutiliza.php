<script>	
$(document).ready(function(){	
	<?php 
	if($datos){
	while($datospre = $datos->fetch_object()){  
			if($datospre->idtipo == 1){//percpeciones?>
				var random = Math.floor(Math.random()*100);
				trper = '<div id="'+random+'" class="row">';
				trper +='<div class="col-md-2">';
				trper +=	'<div class="form-group">';
				trper +='<input type="hidden" value='+random+' id="hi'+random+'" name="hi[]"/>';			
				trper +=	'<select name="percepciones[]" id="percepciones'+random+'" onchange="percepcioneselect(this.value,'+random+')" class="percepciones" data-width="100%" data-live-search="true" >';
				trper +=	'<option>Ninguno</option>';
				<?php $plista="";
				foreach($percepcionesarray as $val){ if($datospre->claveconcepto == $val['clave']){ $p = "selected";}else{$p="";}
					$plista .=  "<option value='".$val['clave']."' ".$p.">".$val['descripcion']."(".$val['clave'].")</option>";
					
				}?>	
				trper +="<?php echo $plista;?>";
				trper +=	'</select></div></div>';
				trper +=	'<div class="col-md-2">';
						
				trper +=	'<div class="form-group">';
				trper +=	'	<input type="text" id="clave'+random+'" name="clave[]" class="form-control" value='+<?php echo $datospre->claveconcepto;?>+'/>';
				trper +=	'	</div></div>';
				trper +=	'	<div class="col-md-2">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<input type="text" id="concepto'+random+'" maxlength=100 name="concepto[]" class="form-control" />';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-2">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<input onkeyup="totalsueldoypercepciones()"  value='+<?php echo $datospre->gravado;?>+' type="text" onkeypress="return solonumeriviris(event,this)" data-value="0" id="pg'+random+'" name="pgravada[]"  class="form-control pegravada"  />';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-2">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<input type="text" onkeyup="totalsueldoypercepciones()"  value='+<?php echo $datospre->exento;?>+' onkeypress="return solonumeriviris(event,this)" data-value="0" id="pe'+random+'" name="pexento[]" class="form-control peexento" />';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-1">';
				trper +=	'		<div class="form-group">';
				trper +=	'				<input type="button" style="display:none;" title="Agregar hora extra" id="agregarhview'+random+'" value="Hora +" class="btn btn-primary btnMenu" onclick="horasextras('+random+');"/>';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +=	'	<div class="col-md-1">';
				trper +=	'		<div class="form-group">';
				trper +=	'			<button type="button" title="Eliminar percepcion" class="btn btn-danger btnMenu eliminar">Eliminar</button>';
				trper +=	'		</div>';
				trper +=	'	</div>';
				trper +='	<div class="col-md-12"  style="display:none" id="AccionesOTitulos">';
				trper +='   		<div class="col-md-6">';
				trper +='		</div>';
				trper +='   		<div class="col-md-2">';
				trper +='			Valor de mercado <b style="color: red">*</b>';
				trper +='			<input type="text" id="valormercado" name="valormercado[]" class="form-control" value="0.00"/>';
				trper +='		</div>';
				trper +='   		<div class="col-md-2">';
				trper +='			Precio al otorgarse <b style="color: red">*</b>';
				trper +='			<input type="text" id="preciootorgarse" name="preciootorgarse[]" class="form-control" value="0.00"/>';
				trper +='		<br></div>';
				trper +='	</div>';
				
				
					
				trper +='	<div class="col-md-12" id="divhorasextras'+random+'" style="display:none">';
				trper +='		<div class="col-md-2"></div>';
				trper +='		<div class="col-md-2">';
				trper +='			Dias<b style="color: red">*</b>';
				trper +='		</div>';
				trper +='		<div class="col-md-2">';
				trper +='			Tipo de horas <b style="color: red">*</b>';
				trper +='		</div>';
				trper +='		<div class="col-md-2">';
				trper +='			Num. Horas extras<b style="color: red">*</b>';
				trper +='		</div>';
				trper +='		<div class="col-md-2">';
				trper +='			Importe pagado<b style="color: red">*</b>';
				trper +='		</div>';
				trper +='		<div class="col-md-1">';
				trper +='		</div>';
				trper +='	<section id="tablahoras'+random+'"></section>';
				
				trper +=	'</div>';
				$("#tabla").append( trper );
				$(".percepciones").selectpicker("refresh");
				percepcioneselect('<?php echo $datospre->claveconcepto; ?>',random);
		<?php }
		else if($datospre->idtipo == 2){?>
				var random = Math.floor(Math.random()*101);
				trded = '<div id="'+random+'" class="row ">';
					
				trded +=' 	<div class="col-md-3">';
				trded +='		<div class="form-group">';
							
				trded +='			<select name="deducciones[]" id="deducciones'+random+'" onchange="completaDeduc(this.value,'+random+')"  class="deducciones" data-width="100%" data-live-search="true">';
				trded +='				<option>Ninguno</option>';
				<?php $plista=""; 
				foreach($deduccionesarray as $val){ if($datospre->claveconcepto == $val['clave']){ $p = "selected";}else{$p="";}
					$plista .=  "<option value='".$val['clave']."' ".$p.">".$val['descripcion']."(".$val['clave'].")</option>";
					
				}?>	
				trded +="<?php echo $plista;?>";
				trded +='			</select>';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-2">';
						
				trded +='		<div class="form-group">';
				trded +='			<input type="text" id="dclave'+random+'" name="dclave[]" class="form-control" />';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-3">';
				trded +='		<div class="form-group">';
				trded +='			<input type="text" id="dconcepto'+random+'" onkeypress="return solonumeriviris(event,this)" name="dconcepto[]" class="form-control" />';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-3">';
				trded +='		<div class="form-group">';
				trded +='			<input type="text" id="dimporte'+random+'" value='+<?php echo $datospre->importe;?>+' maxlength=100 onkeypress="return solonumeriviris(event,this)" onkeyup="totaldeduccionesGlobal()" data-value=0 name="dimporte[]" class="form-control deduccionesglobal" />';
				trded +='		</div>';
				trded +='	</div>';
				trded +='	<div class="col-md-1">';
				trded +='		<div class="form-group">';
				trded +='			<button type="button" class="btn btn-danger btnMenu eliminar2">Eliminar</button>';
				trded +='		</div>';
				trded +='	</div>';
				trded +='</div>';
				
				$("#tabla2").append( trded );
				$(".deducciones").selectpicker("refresh");
				completaDeduc('<?php echo $datospre->claveconcepto; ?>',random);
	<?php }
		else if($datospre->idtipo == 4){?>
			var random = Math.floor(Math.random()*102);
				trotro = '<div id="'+random+'" class="row">';
				trotro += '	<div class="col-md-3">';
				trotro += '		<div class="form-group">';
				trotro += '			<select name="otros[]" id="otros'+random+'" onchange="conceptoOtros(this.value,'+random+')" class="otros" data-width="100%" data-live-search="true" >';
				trotro += '				<option>Ninguno</option>';
				<?php $plista=""; 
				foreach($otrosarray as $val){ if($datospre->claveconcepto == $val['clave']){ $p = "selected";}else{$p="";}
					$plista .=  "<option value='".$val['clave']."' ".$p.">".$val['descripcion']."(".$val['clave'].")</option>";
					
				}?>	
				trotro +="<?php echo $plista;?>";
				trotro += '			</select>';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '	<div class="col-md-2">';
				trotro += '		<div class="form-group">';
				trotro += '			<input type="text" id="oclave'+random+'" name="oclave[]" class="form-control" />';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '	<div class="col-md-3">';
				trotro += '		<div class="form-group">';
				trotro += '			<input type="text" id="oconcepto'+random+'" maxlength=100 name="oconcepto[]" class="form-control" />';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '	<div class="col-md-2">';
				trotro += '		<div class="form-group">';
				trotro += '			<input type="text" onkeyup="totalotrospagosglobal()" value='+<?php echo $datospre->importe; ?>+' onkeypress="return solonumeriviris(event,this)"  data-value=0 id="oimporte'+random+'" name="oimporte[]" class="form-control totalotrospagosglobal" />';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '				<div class="col-md-8 subsidiocausado'+random+'" id="" style="display:none">';
				trotro += '				</div>';
				trotro += '				<div class="col-md-2 subsidiocausado'+random+'" id="" style="display:none;font-weight: bold;">';
				trotro += '					Subsidio causado<b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" onkeypress="return solonumeriviris(event,this)" data-value="0" id="subsidio'+random+'" name="subsidio[]" class="form-control peexento" />';
				trotro += '				<br></div>';
				trotro += '				<div class="col-md-3 saldofavorotro'+random+'"  style="display:none">';
				trotro += '				</div>';
				trotro += '				<div class="col-md-2 saldofavorotro'+random+'" style="display:none;font-weight: bold;">';
				trotro += '					Saldo a favor <b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" onkeypress="return solonumeriviris(event,this)" data-value="0" id="saldofavor'+random+'" name="saldofavor[]" class="form-control peexento" />';
				trotro += '			</div>';
				trotro += '			<div class="col-md-3 saldofavorotro'+random+'" style="display:none;font-weight: bold;">';
				trotro += '					Remanente del saldo a favor <b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" onkeypress="return solonumeriviris(event,this)" data-value="0" id="remanente'+random+'" name="remanente[]" class="form-control peexento" />';
				trotro += '				</div>';	
				trotro += '			<div class="col-md-2 saldofavorotro'+random+'" style="display:none;font-weight: bold;">';
				trotro += '					Año <b style="color:red">*</b>';
				trotro += '					<input type="text" onkeyup="" title="El valor de este atributo debe ser menor que el año en curso" onkeypress="return solonumeriviris(event,this)" data-value="0" id="anosubsidio'+random+'" name="anosubsidio[]" class="form-control peexento" />';
				trotro += '			<br></div>';
				trotro += '	<div class="col-md-1">';
				trotro += '		<div class="form-group">';
				trotro += '			<button type="button" class="btn btn-danger btnMenu eliminar3">Eliminar</button>';
				trotro += '		</div>';
				trotro += '	</div>';
				trotro += '</div>';
				
				$("#tabla3").append(trotro);
				$(".otros").selectpicker('refresh');
				conceptoOtros('<?php echo $datospre->claveconcepto; ?>',random);
				totalotrospagosglobal();
	<?php	}
	}		
}?>
});			
</script>