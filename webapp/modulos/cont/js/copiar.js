 $(document).ready(function(){

 $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fechacopy").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
        
    });
    $("#desde").datepicker({
	 	maxDate: 0,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#hasta").datepicker("option","minDate", selected);
        }
    });
    
    $("#hasta").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:365,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#desde").datepicker("option","maxDate", selected);
        }
    });
  });
function copia(){
	if($("#ele").val()==2){
		$.post('ajax.php?c=CaptPolizas&f=movListado',{ 
			idPoliza:$("#idpoliza").val()
			},function (resp){
				$("#movi").html(resp);
			});
			$.post('ajax.php?c=CaptPolizas&f=polizasCopy',{ 
				Ejercicio:$("#IdExercise").val()
				},function (resp2){
					$("#idpolicopy").html(resp2);
			}); 
		$("#movi,#txtc,#selectpoliza").show();
		$("#conceptocopy,#fechacopy,#fechaco,#conc,#slm").hide();
		
	}else{
		$("#conceptocopy,#fechacopy,#fechaco,#conc,#slm").show();
		$("#movi,#selectpoliza,#txtc").hide();
	}
}
function copiarPoliza(){ 
	$("#copiarPoliza").modal('show');
	$("#ele").val(1);
	copia();
	$("#idpolicopy").select2({
    	width : "150px"
    });
}
function filtrapolizas(){
	$.post('ajax.php?c=CaptPolizas&f=polizasCopyFecha',{ 
				desde:$("#desde").val(),
				hasta:$("#hasta").val()
				},function (resp2){
					$("#idpolicopy").html(resp2);
			}); 
}

