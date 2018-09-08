	$(document).ready(function(){
	$("#cuenta_Trans").select2({
         width : "150px"
        });
	$("#cuenta_Acred").select2({
         width : "150px"
        });
	$("#considera_per").click(function(){
		if($(this).is(':checked')){
			$(this).val("1");
			$("#sel_ejercicio").prop('disabled','');
			$("#per_ini").prop('disabled','');
			$("#per_fin").prop('disabled','');
			$("#fecha_ini").prop('disabled','disabled');
			$("#fecha_fin").prop('disabled','disabled');
			$("#reliva").attr('disabled', 'disabled');
			$("#noreliva").attr('disabled', 'disabled');
			
		}
		else {
			$(this).val("0");
			$("#sel_ejercicio").prop('disabled','disabled');
			$("#per_ini").prop('disabled','disabled');
			$("#per_fin").prop('disabled','disabled');
			$("#fecha_ini").prop('disabled','');
			$("#fecha_fin").prop('disabled','');
			$("#reliva").prop('disabled','');
			$("#noreliva").prop('disabled','');
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
	$radio_mov = $("input[name=radio_mov]:checked").val();
	$radio_op = $("input[name=radio_op]:checked").val();
	$cuenta_trans = $("#cuenta_Trans").val();
	$cuenta_acred = $("#cuenta_Acred").val();
	$acred100 = $("#acred100").val();
	$tasa_sel = $("#tasa_sel").val();
	$sin_caus = $("#sin_caus").val();
	$totalxdia = $("#totalxdia").val();
	$toexcel = "0";
	$toexcel = $("#toexcel:checked").val();
	
	if($cons_per==0 && ($fecha_ini=="" || $fecha_fin=="")){alert("las fechas no pueden estar vacias");}
	else{
		if($toexcel==1){
			if($("#considera_per").is(':checked')){
				$url = 'ajax.php?c=Movimientos_aux_base_gravable&f=reporte&per=1&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&per_fin='+$per_fin+'&toexcel='+$toexcel+'&radio_mov='+$radio_mov+'&radio_op='+$radio_op+'&cuenta_trans='+$cuenta_trans+'&cuenta_acred='+$cuenta_acred+'&acred100='+$acred100+'&tasa_sel='+$tasa_sel+'&sin_caus='+$sin_caus+'&totalxdia='+$totalxdia;
			}
			else{
				$url = 'ajax.php?c=Movimientos_aux_base_gravable&f=reporte&per=0&fecha_ini='+$fecha_ini+'&fecha_fin='+$fecha_fin+'&toexcel='+$toexcel+'&radio_mov='+$radio_mov+'&radio_op='+$radio_op+'&cuenta_trans='+$cuenta_trans+'&cuenta_acred='+$cuenta_acred+'&acred100='+$acred100+'&tasa_sel='+$tasa_sel+'&sin_caus='+$sin_caus+'&totalxdia='+$totalxdia;
			}
			window.open($url, '_blank');
		}
		else{
			$.ajax({
				type: 'POST',
				url:'ajax.php?c=Movimientos_aux_base_gravable&f=reporte',
				data:{per:$cons_per,ejercicio:$ejercicio,per_ini:$per_ini,per_fin:$per_fin,fecha_ini:$fecha_ini,fecha_fin:$fecha_fin,toexcel:"0",radio_mov:$radio_mov,radio_op:$radio_op,cuenta_trans:$cuenta_trans,cuenta_acred:$cuenta_acred,acred100:$acred100,tasa_sel:$tasa_sel,sin_caus:$sin_caus,totalxdia:$totalxdia},
				success: function(resp){
					$("#div_reporte").html(resp);
				}
			});
		}
	}
}