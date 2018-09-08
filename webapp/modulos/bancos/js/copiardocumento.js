
 function numerocopia(){
	$.post('ajax.php?c=Cheques&f=buscanumerocheque',
		{idbancaria:$('#cuentacopi').val()},
		function (resp){
			$("#numerocopi").val(resp);
			//validarangonumero();
		});
 }
 function guardacopi(){
 	$('#numerocopi').css("border-color","");
 	$.post('ajax.php?c=Cheques&f=consulNumeroCheque',{
 		idbancaria:$('#cuentacopi').val(),
 		numerocheque:$("#numerocopi").val()
 	},function (resp){
 		if(resp==1){
 			alert("El numero de folio ya fue expedido");
 			$('#numerocopi').val("");
 			$('#numerocopi').css("border-color","red");
 			return false;
 		}
 		if(resp==2){
 			alert("Folio invalido");
 			$('#numerocopi').css("border-color","red");
 			return false;
 		}
 		if(resp==0){ 
 			$.post('ajax.php?c=Cheques&f=guardaCopiaCheque',{
 				idDocumento:$("#idDocumento").val(),
 				numero:$('#numerocopi').val(),
		 		idbancaria:$('#cuentacopi').val()
 			},function (resp){
 				if(resp){
 					alert("Documento copiado con exito");
 					cierra();
 				}else{
 					alert("Error al copiar documento intente de nuevo");
 				}
 			});
 		}
 	});
 }
 function actualcopi(){
 	
	$.post('ajax.php?c=Cheques&f=buscanumerocheque',
		{idbancaria:$('#cuentacopi').val(),
		 },
		function (resp){
			$("#numerocopi").val(resp);
			//validarangonumero();
		});
 
 }