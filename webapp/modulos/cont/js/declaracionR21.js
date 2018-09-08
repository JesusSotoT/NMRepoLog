function reporte_post(){
	$error=0;
	if($('#acr_iva').is(':checked')){
		$acr_iva=0;
	}else{
		$acr_iva=1;
	}
	$ejercicio = $("#sel_ejercicio").val();
	$per_ini = $("#per_ini").val();
	$per_fin = $("#per_fin").val();
	// = $("#inc_iva").val();
	$prop_select = $("#prop_select").val();
	$prop = $("#prop").val();
	$cant1 = $("#cant1").val();
	$cant2 = $("#cant2").val();
	$cant3 = $("#cant3").val();
	$cant4 = $("#cant4").val();
	$cant5 = $("#cant5").val();
	$cant6 = $("#cant6").val();
	$cant7 = $("#cant7").val();
	$retenidocontri=$("#retenidocontri").val();
	$toexcel = "";
	$toexcel = $("#toexcel:checked").val();
	//alert($inc_iva);
	if($('#inc_iva').is(':checked')){
		$inc_iva=0;
	}else{ $inc_iva=1;}
	
	if($prop<0 || $prop>=1){
		$error=1;
		alert("La proporcion debe estar entre 0 y 0.9999");
	}
	if ($per_ini>$per_fin) {
		$error=1;
		alert("El periodo inicial no puede ser mayor al final");
	}
	if ($error===0) {
		if($toexcel==1){
			$url = 'index.php?c=declaracionR21&f=reporte&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&acr_iva='+$acr_iva+'&inc_iva='+$inc_iva+'&prop_select='+$prop_select+'&prop='+$prop+'&cant1='+$cant1+'&cant2='+$cant2+'&cant3='+$cant3+'&cant4='+$cant4+'&cant5='+$cant5+'&cant6='+$cant6+'&cant7='+$cant7+'&toexcel='+$toexcel+'&retenidocontri='+$retenidocontri;
			window.open($url, '_blank');
		}
		else{
   window.location='index.php?c=declaracionR21&f=reporte&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&acr_iva='+$acr_iva+'&inc_iva='+$inc_iva+'&prop_select='+$prop_select+'&prop='+$prop+'&cant1='+$cant1+'&cant2='+$cant2+'&cant3='+$cant3+'&cant4='+$cant4+'&cant5='+$cant5+'&cant6='+$cant6+'&cant7='+$cant7+'&toexcel=0&retenidocontri='+$retenidocontri;
		}
	}

}
function check(){
	if($('#acr_iva').is(':checked')){
		//$('#acr_iva').val(0);
		$('#cant3').attr("disabled",true);
		$('#cant3').val(0);
		$('#cant4').attr("disabled",true);
		$('#cant4').val(0);
		$('#retenidocontri').attr("disabled",true);
		$('#retenidocontri').val(0);
		$('#ai').hide('slow');
		$('#ai1').hide('slow');

	}else{
		//$('#acr_iva').val(1);	
		$('#cant3').attr("disabled",false);
		$('#cant4').attr("disabled",false);
		$('#retenidocontri').attr("disabled",false);
		$('#ai').show('slow');
		$('#ai1').show('slow');


	}
}
