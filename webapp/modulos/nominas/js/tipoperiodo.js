$(document).ready(function(){

 $('.solo-numero').keypress(function(e) {
	   	var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
	   	if (verified) {e.preventDefault();}
	   });

//$("#idajuste").attr('onchange','return tipoperiodos(this.value)');//tipo resportes
			
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
    	$("#fechainicio").datepicker({
    		dateFormat: 'yy-mm-dd',
    		onSelect: function(selected) {
    			if(!$("#diasperiodo").val() || $("#diasperiodo").val()==0){
				alert("Primero debe seleccionar los dias del periodo");
				$("#fechainicio").val("");
			}else{
				
				validarfechaperiodos();
			}
    		}
    });
    		
    //$('#idajuste').disabled =true;

});

function Guardar(){
	$("#guarda").hide();
	$("#carga").show();
	   if(	!$("#fechainicio").val()	|| !$("#nombre").val() || !$("#diasperiodo").val()	){
		alert("Faltan campos Obligatorios");
		$("#guarda").show();
		$("#carga").hide();
	}else{
		$("#formtipo").submit();
	}
}
function atraslistado(){
	window.location = "index.php?c=Catalogos&f=listaTiposperiodos";
}
function accionTipo(idtipo,accion){
	$.post("ajax.php?c=Catalogos&f=accionTipop",{
		idtipo:	idtipo,
		accion:accion
	},function(resp){
		//alert(resp);
		if (resp==1) {
	   		location.reload();
		}else if(resp==2){
			alert("El periodo ya tiene nominas autorizadas, no puede ser cambiado");
		}else if(resp == 0){
			alert("Ocurrio un error en el proceso");
		}
		// if(accion==0){ var msj = "Tipo periodo Inactivo :(";}
		// if(accion==1){ var msj = "Tipo periodo Activado :)";}
		// if(resp == 1){
			// alert(msj);
			// window.location.reload();
		// }else{
			// alert("Error en el proceso");
		// }
	});
}
function newTipo(){
	window.location="index.php?c=Catalogos&f=tipoPeriodoview";

}

//VALIDAR AJUSTE A CALENDARIO TIPO PERIODO
 function tipoperiodos(ajuste){
//alert(ajuste);
	if(ajuste != 1){
		$('#idajuste').val(1).selectpicker('refresh').attr('disabled', true);
		
	}else{
		$('#idajuste').attr('disabled',false).selectpicker('refresh');
		//$('#idajuste').attr('disabled', false);
	}	
		}
   //VALIDACION DE PERIODOS DE DIAS

function validarfechaperiodos(){
		
		var fechaInicioAnterior = $("#hdnFechaInicio").val(); //Obtenemos la fecha que habia antes de
															  //cambiar.
		// Ponemos la fecha nueva que seleccionamos en el datepicker
		var sFechaNueva = $("#fechainicio").val();
	
		//los dias que quieres aumentar
		var dias=$("#diasperiodo").val();

		//FechaConDias guarda la nueva fecha que tendremos después de agregar los días indicados.
		//a agregarDias le mandamos la fecha + los días que sumaremos
		var FechaConDias = agregarDias(fechaInicioAnterior, dias);
 //"2017-01-01"
 		//sFechaNueva la convertimos en un arreglo y luego la convertimos a un tipo de dato fecha
		sFechaNueva = sFechaNueva.split('-');
		sFechaNueva =  new Date(sFechaNueva[0],sFechaNueva[1]-1,sFechaNueva[2]);
	 	// alert(sFechaNueva );
		// alert(FechaConDias);
		// alert(fechaInicioAnterior);
		var msj = $("#diasperiodo").val()-1 ;	
		if(sFechaNueva >= FechaConDias  ||  sFechaNueva < agregarDias(fechaInicioAnterior, 0)) 
		{
			alert("La fecha de inicio del ejercicio no cae dentro del rango definido por la fecha de inicio historia de la empresa mas/menos" + " " + msj+ " dias de la fecha de inicio.");
			$("#fechainicio").val(fechaInicioAnterior);
		}
	
		return 0;

}
//   2017     01      01
function agregarDias(fecha, dias) { 
	var aFecha = fecha.split("-");  //cortamos la cadena de fecha, ej: '2017-01-01' en un arreglo de 3 posiciones
									//posicion 0: Año, posicion 1: Mes, posicion 2: Día.
	var result = new Date(aFecha[0], aFecha[1] -1, aFecha[2]); //new Date crea una variable de tipo Date en 
															   //javascript. a new Date se le manda:(Año,Mes,Dia)
						//por lo que mandamos lo que contiene aFecha en las posiciones 0(Año), 1(Mes)
						//2(Dia). En JS, los meses van de 0 a 11, por lo que al mes e restamos 1
    result.setDate(result.getDate() + parseInt(dias));	//Hacemos que la variable tipo fecha result,
    					//cambie su fecha a la que le indicamos. La fecha que indicaremos será la fecha
    					//que tenía almacenada antes + los días que le mandamos sumar.
    return result;		//regresamos la nueva fecha con la cantidad de dias sumados.
} 

//VALIDACION PARA CAMBIAR AL NÚMERO 365 DE MAXIMO DE DIAS DE PERIODOS
 function cambioDias(input) {
 	
    if (input.value < 0) input.value = 0;
    if (input.value > 365) input.value = 365;
    
  }

  function NumDec(e, field) { 
		key = e.keyCode ? e.keyCode : e.which; 
		  //alert(key);
		  // backspace , tab, left, right, delete
		  if (key == 8 || key ==9 || key ==37 || key ==39 || key ==127) return true; 
		  // 0-9 and "."
		  if ((key > 47 && key < 58)  || key == 46) {
		  	//if no text
		  	if (field.value == "" && key != 46) return true; 
		  	var  regexp = /^[0-9]+((\.)|(\.[0-9]{1,2}))?$/; 
		  	return (regexp.test(field.value + String.fromCharCode(key))); 
		  }

		  return false;
		}
function periodoExt(){
	if( $("#extra").is(":checked") ){
		$("#extrahidden,#idajuste").val(1);
		$("#diasperiodo").val(365);
		$("#idperiodicidad").val(11);
		$("#ajustemes").val(2);
		$(".pex").hide("linear");
		
	}else{
		$("#extrahidden").val(0);
		$(".pex").show("swing");
		$("#diasperiodo,#idperiodicidad").val("");
	}
}	

