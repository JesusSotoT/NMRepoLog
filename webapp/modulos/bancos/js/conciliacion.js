$(document).ready(function(){
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);

$("#fechainicio").datepicker({
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fechafin").datepicker("option","minDate", selected);
          listatipocambio();
        }

    });
	   
    $("#fechafin").datepicker({
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#fechainicio").datepicker("option","maxDate", selected);
        }
    });
});