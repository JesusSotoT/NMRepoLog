$(document).ready(function(){
	$("#considera_per").click(function(){
		if($(this).is(':checked')){
			$(this).val("1");
			$("#sel_ejercicio").prop('disabled','');
			$("#per_ini").prop('disabled','');
			$("#per_fin").prop('disabled','');
			$("#fecha_ini").prop('disabled','disabled');
			$("#fecha_fin").prop('disabled','disabled');
		}
		else {
			$(this).val("0");
			$("#sel_ejercicio").prop('disabled','disabled');
			$("#per_ini").prop('disabled','disabled');
			$("#per_fin").prop('disabled','disabled');
			$("#fecha_ini").prop('disabled','');
			$("#fecha_fin").prop('disabled','');
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
	$fac_acr = $("#fac_acr").val();
	$toexcel = "";
	$toexcel = $("#toexcel:checked").val();
	
	if($cons_per==0 && ($fecha_ini=="" || $fecha_fin=="")){alert("las fechas no pueden estar vacias");}
	else{
		if($toexcel==1){
			if($("#considera_per").is(':checked')){
				$url = 'ajax.php?c=generacionIVA&f=reporte&per=1&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&per_fin='+$per_fin+'&to_excel='+$toexcel+'&fac_acr='+$fac_acr;
			}
			else{
				$url = 'ajax.php?c=generacionIVA&f=reporte&per=1&fecha_ini='+$fecha_ini+'&fecha_fin='+$fecha_fin+'&to_excel='+$toexcel+'&fac_acr='+$fac_acr;
			}
			window.open($url, '_blank');
		}
		else{
			$.ajax({
				type: 'POST',
				url:'ajax.php?c=generacionIVA&f=reporte',
				data:{per:$cons_per,ejercicio:$ejercicio,per_ini:$per_ini,per_fin:$per_fin,fecha_ini:$fecha_ini,fecha_fin:$fecha_fin,toexcel:"0",fac_acr:$fac_acr},
				success: function(resp){
					$("#div_reporte").html(resp);
				}
			});
		}
	}
}
