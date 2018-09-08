$(document).ready(function(){

	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
    	$("#fecha").datepicker({
    		dateFormat: 'yy-mm-dd',
    		
    });
 });
 
$(function() {
   
	$('#load').on('click', function() { 
   
	   $(this).button('loading');
	   var status = true;
	   if(	!$("#fecha").val() || $("#idregfiscal").val() == 0 ){
	    		status = false;
	    		alert("Faltan campos obligatorios");
	    		$(this).button('reset');
	   }
	   if($("#curp").val()){
	   		var ok = validacurp($("#curp").val());
	   		if(ok==0){
	   			status = false;
	    			alert("La curp es invalida");
	    			$(this).button('reset');
	   		}
	   }
	   
	   if(status){
	   	//alert("envia");
	   	$("#formconfig").submit();
	   }
   
	});
}); 

function marcas(){
	if($("#sellos").is(":checked") ){
		$("#sellos").val(1);
	}else{
		$("#sellos").val(0);
	}
	if($("#anteriores").is(":checked") ){
		$("#anteriores").val(1);
	}else{
		$("#anteriores").val(0);
	}
	if($("#futuros").is(":checked") ){
		$("#futuros").val(1);
	}else{
		$("#futuros").val(0);
	}
}
function irOrganizacion(){
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=1&ticket=testing","Organizacion","",2);
}
function validacurp(curp){
	var curp = curp.replace(/\s*[\r\n][\r\n \t]*/g, "");
	var valid = /^([A-Z]{4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM](AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[A-Z]{3}[0-9A-Z]\d)$/i;
	if(curp.length <18){
	 	return 0;
	}
	var validcurp=new RegExp(valid);
	var matchArray=curp.match(validcurp);
	if (matchArray==null){
		return 0;
	}else{ 
		return 1;
	}	
}
