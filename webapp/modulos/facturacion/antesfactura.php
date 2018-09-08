<script src="js/jquery-1.10.2.min.js"></script>
<script>
	$(document).ready(function() {
	$('#send').hide();
	var lwpButton = $("<input>", { 
		id: "send2", 
		type:"button",
		value:"Guardar",
		css: { "padding": "2px", "cursor": "pointer" }, 
		title: "guardar", 
		alt: "guardar", 
		 click: function (e) {
		 	}
		 	  });
		 	   $("body").append(lwpButton);
		 	});
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


$(document).ready(function() {
		 $('#send2').click(function() {

		
  
  if(validaRfc($('#i1236').val())==0){
		alert("El RFC es incorrecto");
		return false;
	}else{ $('#send').click();	
	}
	
	});	
});


</script>