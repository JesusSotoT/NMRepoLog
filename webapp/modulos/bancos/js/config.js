$(document).ready(function(){
	$("#ejercicio").select2({
     width : "200px"
    });
});
function acontiaConfig(){
	
	if($("#acontia").is(':checked')==true){
     	$("#sinAcontia").hide();
     	$("#acontiaconf").val(1);
	}
	else{
	    $("#sinAcontia").show();
	    $("#acontiaconf").val(0);
	}
}
function reiniciar()
{
	var primeraPregunta = confirm("Esta seguro que desea reiniciar la Configuracion? \nEsto borrara toda la informacion de documentos y conciliacion, NO PODRA RECUPERARLOS.");
	var contrasena;

	if(primeraPregunta)
	{
			contrasena = prompt('Es necesario la contraseña del administrador para continuar con esta operacion');

			if(contrasena)
			{ $("#load2").show();
				$.post("ajax.php?c=Configuracion&f=passAdmin",
					{
						Pass: contrasena
					},
					function(data)
			 		{
						if(data)
						{
							$.post("ajax.php?c=Configuracion&f=reiniciar",{},
			 				function(resp)
			 				{
			 					if(resp){
			 						alert('Se ha eliminado toda la informacion :(');
			 						window.location.reload();
			 					}else{
			 						alert("Problema al reiniciar, intente de nuevo");
			 					}
			 					$("#load2").hide();
			 				});
						}
						else
						{
							alert('Contraseña Incorrecta');$("#load2").hide();
						}
					});
			}
	}
}
function updateInfo(){
	if(!$("#vigente").val() || !$("#rfc").val()){
		alert("Tiene datos vacios");
		return false;
	}
	var periodosabiertos; var polizaAu;
	if($("#periodosabiertos").is(':checked')==true){
		periodosabiertos=1;
	}else{
		periodosabiertos=0;
	}
	if($("#polizaAu").is(':checked')==true){
		polizaAu=1;
	}else{
		polizaAu=0;
	}
	$.post("ajax.php?c=Configuracion&f=updateConfiguracion",{
		rfc:$("#rfc").val(),
		vigente:$("#vigente").val(),
		periodosabiertos:periodosabiertos,
		polizaAu:polizaAu
	},function(resp){
		if(resp){
			window.location.reload();
		}
	});
}
function updatePolizaAuto(){
	var polizaAu;
	if($("#polizaAu").is(':checked')==true){
		polizaAu=1;
	}else{
		polizaAu=0;
	}
	$.post("ajax.php?c=Configuracion&f=updatePolizaAuto",{
		polizaAu:polizaAu
	},function(resp){
		window.location.reload();
	});
}
function validaDatos(){
	if( $("#sinAcontia").is(":visible") ){
		if(!$("#vigente").val() || !$("#rfc").val()){
			alert("Tiene datos vacios");
			return false;
		}
	}
}
