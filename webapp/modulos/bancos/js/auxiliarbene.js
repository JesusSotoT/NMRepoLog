$(document).ready(function(){
	$("#cuenta,#moneda").select2({ width: '160px' });	
	$("#clicheck").attr("checked",true);
	muestracli();
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
    
function muestracli(){
	if($("#clicheck").is(":checked")){
		$("#provecheck,#emplecheck").prop("checked",false);
		$("#prove,#empleado").select2("destroy").hide();
		$("#cliente").find('option').removeAttr("selected");
		$("#cliente").show();
		$("#cliente").select2({ width: '150px' });
		$("#beneficiario").val(5);
	}else{
		$("#provecheck").prop("checked",true);
		$("#cliente,#empleado").select2("destroy").hide();
		$("#prove").find('option').removeAttr("selected");
		$("#prove").show();
		$("#prove").select2({ width: '150px' });
		$("#beneficiario").val(1);
	}
}
function muestrapro(){
	if($("#provecheck").is(":checked")){
		$("#clicheck,#emplecheck").prop("checked",false);
		$("#cliente,#empleado").select2("destroy").hide();
		$("#prove").find('option').removeAttr("selected");
		$("#prove").show();
		$("#prove").select2({ width: '150px' });
		$("#beneficiario").val(1);
	}else{
		$("#clicheck").prop("checked",true);
		$("#prove,#empleado").select2("destroy").hide();
		$("#cliente").find('option').removeAttr("selected");
		$("#cliente").show();
		$("#cliente").select2({ width: '160px' });
		$("#beneficiario").val(5);
	}
}
function muestraempleados(){
	if($("#emplecheck").is(":checked")){
		$("#clicheck,#provecheck").prop("checked",false);
		$("#cliente,#prove").select2("destroy").hide();
		$("#prove,#cliente").find('option').removeAttr("selected");
		$("#empleado").show();
		$("#empleado").select2({ width: '150px' });
		$("#beneficiario").val(2);
	}else{
		$("#clicheck").prop("checked",true);
		$("#prove,#empleado").select2("destroy").hide();
		$("#cliente,#empleado").find('option').removeAttr("selected");
		$("#cliente").show();
		$("#cliente").select2({ width: '160px' });
		$("#beneficiario").val(5);
	}
}

$(function() {
   
	$('#load').on('click', function() { 
	   
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
		   if(!$("#prove").val() && !$("#cliente").val() && !$("#empleado").val() ){
		   	alert("Seleccione un Beneficiario/Pagador");
		   	status = false;
		   	$(this).button('reset');
		   }
		   if(status){
		   	$("#filtro").submit();
		   }
	 });  
 });
 function cuentasPorMoneda(){
	$("#progres").show();
	$.post("ajax.php?c=Flujo&f=cuentasPorMoneda",{
		idmoneda:$("#moneda").val()
	},function (resp){
		$("#cuenta").html(resp);
		$("#cuenta").select2({ width: '160px' });
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
function anular(select){ 
	$("#"+select+" option:selected").each(function(){
		if($(this).attr('value')==0){
			$("#"+select).find('option').removeAttr("selected");
			$("#"+select).val(0).select2({ width: '160px' });
		}
		
	});
}
