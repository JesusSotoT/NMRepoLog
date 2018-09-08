$(document).ready(function(){
	
		$("#cuenta,#moneda").select2({ width: '150px' });

	
    $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	
	 $("#fechainicio").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fechafin").datepicker("option","minDate", selected);
        }
    });
    $("#fechafin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:365,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#fechainicio").datepicker("option","maxDate", selected);
        }
    });
 });
$(function() {
   
$('.btn').on('click', function() { 
   
   $(this).button('loading');
   var status = true;
   
   if(!$("#fechainicio").val() || !$("#fechafin").val()){
   	alert("Seleccione las fechas");
   	status = false; 
   	$(this).button('reset');
   }
   if($("#moneda").val() == 0){
   	alert("Seleccione una moneda");
   	status = false;
   	$(this).button('reset');
   }
   if(status){
   	$("#filtro").submit();
   }
   
   
   
       // $this.button('reset');
  
});

});
function cuentasPorMoneda(){
	$("#progres").show();
	$.post("ajax.php?c=Flujo&f=cuentasPorMoneda",{
		idmoneda:$("#moneda").val()
	},function (resp){
		$("#cuenta").html(resp);
		$("#cuenta").select2({width : "150px"});
		$("#progres").hide();
	});
}
function cambiacheck(){
	if($("#checkpro").is(":checked")){
		$("#proyectados").val(1);
	}else{
		$("#proyectados").val(0);
	}
}
function cambiacobro(){
	if($("#checkcobro").is(":checked")){
		$("#cobrados").val(0);
	}else{
		$("#cobrados").val(1);
	}
}

$(function() {
   
$('.btnposicion').on('click', function() { 
   
   $(this).button('loading');
   var status = true;
   
   if( !$("#fechafin").val()){
   	alert("Seleccione la fecha");
   	status = false; 
   	$(this).button('reset');
   }
   if($("#moneda").val() == 0){
   	alert("Seleccione una moneda");
   	status = false;
   	$(this).button('reset');
   }
   if(status){
   	$("#filtro").submit();
   }
   
   
   
       // $this.button('reset');
  
});

});