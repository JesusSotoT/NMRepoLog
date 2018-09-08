$(document).ready(function(){

});

modalbaja = function(idempleado, accion){

	var fechaminima=$("#fechainic").val();
	$("#txtfecha").val("");

	if (fechaminima) {
		$('#fecha').datetimepicker({
			format: 'YYYY-MM-DD',
			minDate:fechaminima,
			ignoreReadonly: true,
			useCurrent: false,
			locale: 'es'
		});
	}else{

		$('#fecha').datetimepicker({
			format: 'YYYY-MM-DD',
			ignoreReadonly: true,
			useCurrent: false,
			locale: 'es'
		});
	}



	$("#fecha").on("dp.change", function (e) {

		if ($("#nominas").val()==1) {
			var date = e.date.format("YYYY-MM-DD");  
			$.post("ajax.php?c=Catalogos&f=periodoactual",{
				fecha:date 
			},
			function(resp){  

				if(resp=="false"){ 
					$("#txtfecha").val(""); 
					alert("La fecha no está dentro del periodo actual.");
					$("#txtfecha").val("");
				} 
			});
		}
	}); 


	$('#btnbaja').on('click', function() { 

		if ($("#txtfecha").val() ==""){
			alert("Seleccione una fecha."); 

		}
		else {

			var btnguardar = $(this);
			btnguardar.button("loading");  
			var fecha = $("#txtfecha").val();
			//alert(idempleado + '-' + fecha );  
			var status = true;  
			$.post("ajax.php?c=Catalogos&f=accionEmpleado",{
			fecha:fecha ,
			accion:accion,
			idempleado:idempleado
			},
	function(resp){
		alert(resp);
		// if(resp=="false"){ 
		// 	alert("Ocurrió un error al guardar"); 
		// } 
		});
		btnguardar.button('reset');	
		window.location.reload();
		}
	}); 

};











