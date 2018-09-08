<script src="js/jquery-1.10.2.min.js"></script>
<script>

function validaRfc(rfcStr) {
	var strCorrecta;
	strCorrecta = rfcStr;	
	if (rfcStr.length == 12){
		var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}else{
		var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}
	var validRfc=new RegExp(valid);
	var matchArray=strCorrecta.match(validRfc);
	if (matchArray==null) {
		return 0;
	}
	else
	{
		return 1;
	}
	
}


$('#frm').submit(function() {

	var errores=0;
	var texto="Porfavor llene los siguientes campos:\n";
	if($("#i554").val()==""){
  		texto+="* RFC\n";
  		errores++;
  	}	
  	if($("#i566").val()==""){
  		texto+="* Pais\n";
  		errores++;
  	}
  	if($("#i556").val()==""){
  		texto+="* Razon Social\n";
  		errores++;
  	}
  	
  	if($("#i559").val()==""){
  		texto+="* Correo o correos";
  		errores++;
  	}
  	
  	if(errores>0){
  		alert(texto)
  		return false;
  	}
  	
	if(validaRfc($('#i554').val())==0){
		alert("El RFC es incorrecto");
		return false;
	}
	
	var bandera=false;
	var correos= $("#i559").val().split(";");
	for(var cont=0;cont<correos.length;cont++){
		if(correos[cont].indexOf('@', 0) == -1 || correos[cont].indexOf('.', 0) == -1) {
			bandera=true;
			break;
		}
	}
	if(bandera){
		alert("Los correos son incorrecto");
		return false;	
	}
});

$(document).ready(function() {
    $('#frm').removeAttr('onsubmit').submit(function(e){});
});
</script>