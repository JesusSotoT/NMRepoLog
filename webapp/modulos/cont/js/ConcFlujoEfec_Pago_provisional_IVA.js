$(document).ready(function(){
	$("#cuenta_Ini").select2({
         width : "150px"
        });
	$("#cuenta_Fin").select2({
         width : "150px"
        });
	$("input[name=radio_tipo]").change(function(){
		if($("input[name=radio_tipo]:checked").val()==1){
			$("#cuenta_Ini").attr('disabled', false);
			$("#cuenta_Fin").attr('disabled', false);
			
		}
		if($("input[name=radio_tipo]:checked").val()==0){
			$("#cuenta_Ini").attr('disabled', true);
			$("#cuenta_Fin").attr('disabled', true);
		}
	});
	
});
function reporte_post(){
	if($("#rad_cau").is(':checked')){
		$cuenta_Ini=0;
		$cuenta_Fin=0;
	}else{	
		$cuenta_Ini = $("#cuenta_Ini").val();
		$cuenta_Fin = $("#cuenta_Fin").val();
	}
	if($("#aplica").is(':checked')){
		$aplica=1;
	}else{
		$aplica=0;
	}
	// alert($cuenta_Fin);
	// alert($cuenta_Ini);
	$fecha_ini =$("#fecha_ini").val();
	$fecha_fin =$("#fecha_fin").val();
	if($fecha_ini=='' || $fecha_fin==''){
		return alert("Introduzca la Fecha.");
		
	}
	$radio_tipo = $("input[name=radio_tipo]:checked").val();
	$toexcel = "0";
	$toexcel = $("#toexcel:checked").val();
	//alert("si");
	if($toexcel==1){
		$url = 'ajax.php?c=ConcFlujoEfec_Pago_provisional_IVA&f=reporte&fecha_ini='+$fecha_ini+'&fecha_fin='+$fecha_fin+'&radio_tipo='+$radio_tipo+'&cuenta_Ini='+$cuenta_Ini+'&cuenta_Fin='+$cuenta_Fin+'&aplica='+$aplica+'&toexcel='+$toexcel;
		window.open($url, '_blank');
	}
	else{
		$.ajax({
			type: 'POST',
			url:'ajax.php?c=ConcFlujoEfec_Pago_provisional_IVA&f=reporte',
			data:{fecha_ini:$fecha_ini,fecha_fin:$fecha_fin,radio_tipo:$radio_tipo,cuenta_Ini:$cuenta_Ini,cuenta_Fin:$cuenta_Fin,aplica:$aplica,toexcel:"0"},
			success: function(resp){
				$("#div_reporte").html(resp);
			}});
	}
}