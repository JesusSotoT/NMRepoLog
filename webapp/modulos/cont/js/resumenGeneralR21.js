function reporte_post(){
	$error=0;
	$ejercicio = $("#sel_ejercicio").val();
	$per_ini = $("#per_ini").val();
	$per_fin = $("#per_fin").val();
	$tasa_sel = $("#tasa_sel").val();
	if($('#considera_per').is(':checked')){
		$acr_iva=0;
	}else{
		$acr_iva=1;
	}
	$prop = $("#prop").val();
	$use_prop = $("#use_prop").val();
	$toexcel = "";
	$toexcel = $("#toexcel:checked").val();
	$sel_rep = $("input[name=sel_rep]:checked").val();
	if($prop<0 || $prop >=1){
		$error=1;
		alert("La proporcion debe estar entre 0 y 0.9999");
	}
	if($per_ini>$per_fin){
		$error=1;
		alert("El periodo inicial no puede ser mayor al periodo final");
	}

	if($error===0){
		if($toexcel==1){
			$url = 'index.php?c=resumenGeneralR21&f=reporte&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&per_fin='+$per_fin+'&to_excel='+$toexcel+'&tasa_sel='+$tasa_sel+'&sel_rep='+$sel_rep+'&prop='+$prop+'&use_prop='+$use_prop+'&acr_iva='+$acr_iva;
			window.open($url,'_blank');
		}
		else{
		
			window.location='index.php?c=resumenGeneralR21&f=reporte&ejercicio='+$ejercicio+'&per_ini='+$per_ini+'&per_fin='+$per_fin+'&to_excel=0&tasa_sel='+$tasa_sel+'&sel_rep='+$sel_rep+'&prop='+$prop+'&use_prop='+$use_prop+'&acr_iva='+$acr_iva;
				//data:{ejercicio:$ejercicio,per_ini:$per_ini,per_fin:$per_fin,to_excel:"0",tasa_sel:$tasa_sel,sel_rep:$sel_rep,prop:$prop,use_prop:$use_prop},
				
		}
	}
		
}