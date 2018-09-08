
$(document).ready(function(){
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	$("#fechapago").datepicker({
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			onSelect: function(selected) {
				var	fecha = new Date($("#fechainicio").val());
				fecha.setDate(fecha.getDate() + 1);
				$("#fechapago").datepicker("option", "minDate",fecha);
			}
		});
		
 $("#timbra").on('click', function() {
 	
 	if( !$("#idnomina").val() ){
 		alert("Debe seleccionar una nomina!");
 	}
 	else{
 		var sepa2 = $("#idnomina").val().split("/");
		var btnguardar = $(this);
		btnguardar.button("loading");
		$.post("ajax.php?c=Prenomina&f=xmlNomina",{
			idnomp:sepa2[0],
			fechapago: $("#fechapago").val(),
			
		},function(resp){
			btnguardar.button("reset");
		});
	}
	});	
		
});
function iniciocalendario(valor){
	var sepa = valor.split("/");
	$("#fechainicio").val(sepa[1]);
	$("#fechapago").val( sepa[1]);
		
	var	fecha2 = new Date($("#fechainicio").val());
	fecha2.setDate(fecha2.getDate() + 1);
	$("#fechapago").datepicker("option", "minDate",fecha2);
	
		
}
