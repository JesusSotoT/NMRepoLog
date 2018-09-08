$(document).ready(function(){
	$("#considera_per").click(function(){
		if($(this).is(':checked')){
			$(this).val("1");

			$("#sel_ejercicio").prop('disabled','');
			$("#per_ini").prop('disabled','');
			//$("#per_fin").prop('disabled','');
			$("#fecha_ini").prop('disabled','disabled');
			$("#fecha_fin").prop('disabled','disabled');
			$("#mov").hide('slow');
			$("#eje").show('slow');
		}
		else {
			$(this).val("0");
			$("#sel_ejercicio").prop('disabled','disabled');
			$("#per_ini").prop('disabled','disabled');
			//$("#per_fin").prop('disabled','disabled');
			$("#fecha_ini").prop('disabled','');
			$("#fecha_fin").prop('disabled','');
			$("#eje").hide('slow');
			$("#mov").show('slow');
		}
		//alert($("#considera_per").val());
	});
});