function irConfiguracion(){
	window.parent.agregatab('../../modulos/nominas/index.php?c=Catalogos&f=configuracion','Configuracion','',2257);
	window.parent.preguntar=true;
 }
 
$(document).ready(function(){
	 $("#auto").on('click', function() {

      	var btnguardar = $(this);
		btnguardar.button("loading");
		if(confirm("¿Este proceso actualiza los acumulados y cambiala nomina de trabajo, esta seguro de continuar?")){
			$.post("ajax.php?c=Prenomina&f=verificaPagoEmpleado",{
				idnomina:$("#idnomina").val(),
				fechafin:$("#fechafin").val(),
				fechainicio:$("#fechainicio").val(),
				idtipoperiodo:$("#idtipoperiodo").val(),
				numnomina:$("#numnomina").val()
			},function (request){
				if(request == 2){
					alert("No puede realizar la autorizacion!\nFaltan empleados de calcular en este periodo.");
					btnguardar.button("reset");
					
				}else if(request == 1){
					alert("Nomina Autorizada");
					btnguardar.button("reset");
					window.location.reload();
					
				}else{
					alert("Error en proceso de autorizacion, intente de nuevo");
					btnguardar.button("reset");
				}
			});
		}else{
			btnguardar.button("reset");
		}
		 
	});
});