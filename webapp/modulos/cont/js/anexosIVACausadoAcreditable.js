$(document).ready(function(){
	$("#considera_per").click(function(){
		if($(this).is(':checked')){
			$(this).val("1");
			$("#sel_ejercicio").prop('disabled','');
			$("#per_ini").prop('disabled','');
			$("#per_fin").prop('disabled','');
			$("#fecha_ini").prop('disabled','disabled');
			$("#fecha_fin").prop('disabled','disabled');
			$("#mov").hide('slow');
			$("#eje").show('slow');
		}
		else {
			$(this).val("0");
			$("#sel_ejercicio").prop('disabled','disabled');
			$("#per_ini").prop('disabled','disabled');
			$("#per_fin").prop('disabled','disabled');
			$("#fecha_ini").prop('disabled','');
			$("#fecha_fin").prop('disabled','');
			$("#eje").hide('slow');
			$("#mov").show('slow');
		}
	});
});
function reporte_post(){
	$cons_per = $("#considera_per").val();
	$ejercicio = $("#sel_ejercicio").val();
	$per_ini = $("#per_ini").val();
	$per_fin = $("#per_fin").val();
	$fecha_ini =$("#fecha_ini").val();
	$fecha_fin =$("#fecha_fin").val();
	if($("#acr_iva_ret").is(':checked')){
		$acr_iva_ret = 1;	
	}else{
		$acr_iva_ret = 0;	
	}
	
	//alert($fecha_fin);
	$toexcel = "0";
	$toexcel = $("#toexcel:checked").val();
	//alert("si");
	if($cons_per==0 && ($fecha_ini=="" || $fecha_fin=="")){alert("las fechas no pueden estar vacias");}
	else{
		if($toexcel==1){
			if($("#considera_per").is(':checked')){
				$url = 'ajax.php?c=anexosIVACausadoAcreditable&f=reporte&per=1&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&per_fin='+$per_fin+'&acr_iva_ret='+$acr_iva_ret+'&toexcel:1';
			}
			else{
				$url = 'ajax.php?c=anexosIVACausadoAcreditable&f=reporte&per=0&fecha_ini='+$fecha_ini+'&fecha_fin='+$fecha_fin+'&acr_iva_ret='+$acr_iva_ret+'&toexcel:1';
			}
			window.open($url, '_blank');
		}
		else{
			$("#img").show();
			$.ajax({
				type: 'POST',
				url:'ajax.php?c=anexosIVACausadoAcreditable&f=reporte',
				data:{per:$cons_per,ejercicio:$ejercicio,per_ini:$per_ini,per_fin:$per_fin,fecha_ini:$fecha_ini,fecha_fin:$fecha_fin,toexcel:"0",acr_iva_ret:$acr_iva_ret},
				success: function(resp){
					$("#img").hide();
					$("#cuerpo").hide();
					$("#div_reporte").show();
					$("#div_reporte").html(resp);
					//$("#div_reporte").append("<br><input type='button' value='Regresar'  class='nminputbutton' onclick='regreso();'  >");
				}
			});
		}
	}
	
}
function regreso(){
	$("#cuerpo").show();
	$("#div_reporte").hide();
}
function excelreport(){
	$cons_per = $("#considera_per").val();
	$ejercicio = $("#sel_ejercicio").val();
	$per_ini = $("#per_ini").val();
	$per_fin = $("#per_fin").val();
	$fecha_ini =$("#fecha_ini").val();
	$fecha_fin =$("#fecha_fin").val();
	if($("#acr_iva_ret").is(':checked')){
		$acr_iva_ret = 1;	
	}else{
		$acr_iva_ret = 0;	
	}
	$url = 'ajax.php?c=anexosIVACausadoAcreditable&f=reporte&per=1&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&per_fin='+$per_fin+'&acr_iva_ret='+$acr_iva_ret+'&toexcel:1';
	window.open($url, '_blank');
}
