function newConcepto(){
	window.location="index.php?c=Catalogos&f=conceptos";
}

function sat(){
		
	if($("#idtipo").val() == 1){
		if($("#idAgrupador").val() == 16){ //si del listado selecciona horas extras
			$("#idhora").attr("disabled",false).selectpicker("refresh");//mostrara el catalogo de horas extras
		}else{
			$("#idhora").val(0).attr("disabled",true).selectpicker("refresh");
		}
	}
}
function tipoconcepto(edicion){
		$("#idFormapago").val(0).selectpicker("refresh").attr("disabled",true);
		if($("#idtipo").val() ==  1){//percepcion
			$("#idAgrupador").attr("disabled",false).selectpicker("refresh");
			if( $("#especie").is(":checked") ){
				$("#idFormapago").attr("disabled",false).selectpicker("refresh");
			}else{
				$("#idFormapago").val(0).selectpicker("refresh").attr("disabled",true);
			}
			
		}else if($("#idtipo").val() == 2){//deduccion
			$("#idhora").val(0).selectpicker("refresh").attr("disabled",true);
			if($("#especie").is(":checked") ){
				$("#idAgrupador").val(0).selectpicker("refresh").attr("disabled",true);
			}else{
				$("#idAgrupador").attr("disabled",false).selectpicker("refresh");
			}
			
		}else if($("#idtipo").val() == 3){//obligacion
			$("#idhora").val(0).selectpicker("refresh").attr("disabled",true);//siempre deshabilitara horas extrar si no es percepcion
			$("#idAgrupador").val(0).selectpicker("refresh").attr("disabled",true);
		}
		if(edicion==0){
			listado_percepciones_deducciones();
		}
	}
function listado_percepciones_deducciones(){
	if($("#idtipo").val()!=3){
		$.post("../../modulos/nominas/ajax.php?c=Catalogos&f=listapercepdeduc&t="+$("#idtipo").val(),//select llena tipo operacion
         function(data) 
         {
         	$("#idAgrupador").empty();
            $("#idAgrupador").html("<option value='0'>-----</option>");
            $("#idAgrupador").append(data).selectpicker("refresh");
            
         });
    }
}
function especies(){
		if( $("#especie").is(":checked") ){
			$("#especie").val(1);
			if( $("#idtipo").val() == 1 ){
				$("#idAgrupador").attr("disabled",false).selectpicker("refresh");
				$("#idFormapago").attr("disabled",false).selectpicker("refresh");

				
			}else if($("#idtipo").val() == 2){//deduccion
				$("#idAgrupador").val(0).selectpicker("refresh").attr("disabled",true);
				$("#idhora").val(0).selectpicker("refresh").attr("disabled",true);
				$("#idFormapago").val(0).selectpicker("refresh").attr("disabled",true);
				
				
			}else if($("#idtipo").val() == 3){//obligacion
				$("#idAgrupador").val(0).selectpicker("refresh").attr("disabled",true);
				$("#idhora").val(0).selectpicker("refresh").attr("disabled",true);
				$("#idFormapago").val(0).selectpicker("refresh").attr("disabled",true);
			}
		}else{
			$("#especie").val(0);
			if( $("#idtipo").val() == 1 ){
				
				$("#idAgrupador").attr("disabled",false).selectpicker("refresh");
				$("#idFormapago").val(0).selectpicker("refresh").attr("disabled",true);
				
			}else if($("#idtipo").val() == 2){//deduccion
				$("#idAgrupador").attr("disabled",false).selectpicker("refresh");
				
			}else if($("#idtipo").val() == 3){//obligacion
				$("#idAgrupador").val(0).selectpicker("refresh").attr("disabled",true);
			}
		}
	}
function atraslistado(){
	window.location = "index.php?c=Catalogos&f=listaConceptos";
}
function liquiglobal(){
	if( $("#global").is(":checked") ){
		$("#global").val(1);
	}else{
		$("#global").val(0);
	}
	if( $("#liquidacion").is(":checked") ){
		$("#liquidacion").val(1);
	}else{
		$("#liquidacion").val(0);
	}
}
function Guardar(){
	$("#guarda").hide();
	$("#carga").show();
	if(	!$("#codigo").val()	|| !$("#descripcion").val()){
		alert("Faltan campos Obligatorios");
		$("#guarda").show();
		$("#carga").hide();
	}else{
		$("#formconceptos").submit();
	}
}


//empleados
function accionEmpleado(idempleado,accion){
        var d = new Date();
        var fecha = d.getDate();
        var confi = alerta = '';
        if( accion == 2){
          confi = "¿Esta seguro de dar de baja al empleado?";
          alerta = "dado de baja";
        }
        else if( accion == 3){
          confi = "Antes de reingresar al empleado asegurese de imprimir\n toda la informacion referente a la liquidacion del empleado\nDesea continuar con el reingreso del empleado?";
          alerta = "reingresado";
        }
        if(confirm (confi)){
          modalbaja(idempleado, accion);
          $('#myModal').find('.modal-title').html("El empleado será " + alerta +  " con esta fecha, ¿Desea continuar?")
          $('#myModal').modal('show');  
        }
      }

      function accionEliminarConcepto(idconcepto){
      	var confirma = confirm("¿Esta seguro que desea eliminar el concepto?");
      	if (confirma == true) {
      		$.post("ajax.php?c=Catalogos&f=accionEliminarConcepto",{
      			idconcepto:idconcepto,

      		},function(request){
			//alert(request);
			if(request == 1 ){
				alert("Datos eliminados.");
				window.location.reload();
			}

			else if (request == 2) {
				alert("No puede eliminar los conceptos acumulados.");
			}

			else{
				alert("Error en el proceso.");
			}	
		});

      		return true;
      	}else{

      		window.close();
      	}
      	$("#"+load).hide();

    }

// $(document).ready(function(){
// $('#tabla').DataTable({
	// dom: 'Bfrtip',
    // buttons: [ 'excel' ],
	// language: {
		// search: "Buscar Concepto:",
	    // lengthMenu:"Mostrar _MENU_ Conceptos",
	    // zeroRecords: "No hay Conceptos.",
	    // infoEmpty: "No hay Conceptos que mostrar.",
	    // info:"Mostrando del _START_ al _END_ de _TOTAL_ Conceptos",
        // infoFiltered: "( _TOTAL_ Conceptos Encontrados)",
	    // paginate: {
	        // first:      "Primero",
	        // previous:   "Anterior",
	        // next:       "Siguiente",
	        // last:       "Último"
	        // }
	// }
	// }); 
// });