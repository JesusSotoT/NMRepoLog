$(document).ready(function(){

	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$(".fechas").datepicker({
		dateFormat: 'yy-mm-dd'
	});

	$("#fechainiciovacaciones").datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(selected) {	
			$("#fechafinalvacaciones").val(selected);
			$("#fechafinalvacaciones").datepicker("option","minDate", selected);
			$("#fechadescanso").val("");
			$("#fechadescanso").datepicker("option","minDate", selected).datepicker("refresh");	
			diastranscurridos();
			$("#fechadescanso").datepicker("enable");

		}
	});

	$("#fechafinalvacaciones").datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(selected) {
			$("#fechadescanso").val("");
			$("#fechadescanso").datepicker("option","maxDate", selected).datepicker("refresh");
			diastranscurridos();
			$("#fechadescanso").datepicker("enable");

		}
	});
	$("#fechapagovacaciones").datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(selected) {
       //diastranscurridos();
}
});

	$("#fechainicioincapacidad").datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(selected) {
       //diastranscurridos();
}
});

	$("#fechadescanso").multiDatesPicker({ 
		dateFormat: 'yy-mm-dd',
		showOn: "button",      
		buttonImage: "images/cal.png",
		buttonImageOnly: true,
		defaultDate: null,

		onSelect: function(selectedDate){
			console.log(selectedDate);      	
		},
		onClose: function(selected,evnt) {  
			fechas = $(this).val().split(',');
			$("#diasdescansoseptimo").val(parseInt(fechas.length));   

			var acumulado = $("#vacacionesacumuladasrespaldo").val();
			$("#vacacionesacumuladas").val(parseInt(acumulado)- parseInt(fechas.length));
			var pendiente = $("#vacapendientevacacionesrespaldo").val();
			$("#vacapendientevacaciones").val(parseInt(pendiente)+ parseInt(fechas.length));


			if ($("#fechadescanso").val() == "" ){
				$("#diasdescansoseptimo").val(0);

			}else{

				diasdescanso(fechas.length);
			}

			if(!$(this).val()){
				$("#diasdescansoseptimo").val(0);
				$("#diasvacaciones").val($("#diasvacacionesrespaldo").val());
				$("#vacacionesacumuladas").val($("#vacacionesacumuladasrespaldo").val());
				$("#vacapendientevacaciones").val($("#vacapendientevacacionesrespaldo").val());
			}
		}
	});
});


function accionEliminarConceptoSobre(concepto){

	var confirma = confirm("¿Esta seguro que desea eliminar el concepto?");
	if (confirma == true) {

		$.post("ajax.php?c=Sobrerecibo&f=accionEliminarConceptoSobre",{
			empleado: $("#empleado").val(),
			concepto:concepto

		},function(request){

			if(request == 1 ){
				alert("Concepto eliminado.");
				datosEmp();	
			}
			else{
				alert("Error en el proceso,intente de nuevo.");
			} 
		});

		return true;
	}else{

		window.close();
	}
	$("#"+load).hide();
}


$(function() {

	$('#guardar').on('click', function(evt) { 

		var  idEmpleado = $("#empleado").val();
		if(idEmpleado!=0 &&($("#percepcion").val()!=0 || $("#deduccion").val()!=0)){
			$(this).button('loading');

			$.ajax({
				url:"ajax.php?c=Sobrerecibo&f=guardarPercDedu",
				type: 'POST',
				data:{
					empleado:    $("#empleado").val(),
					nominaactiva:$("#nominaactiva").val(),
					percepcion:  $("#percepcion").val(),
					deduccion:   $("#deduccion").val()
				},
				success: function(r){

					if(r=1){		
						alert("Guardado.");
						datosEmp();
						$("#guardar").button("reset");
						$('#percepcion').selectpicker('val', '0');
						$('#deduccion').selectpicker('val', '0');
					}
					else{	 
						alert("Error en el proceso intente de nuevo.");
					}   
				}
			});
		}else{
			alert("seleccione un concepto en percepciones o deducciones.")
		}
	});
});



function sumaPercep() {
	var sum = 0;
	$(".importePercepciones").each(function(){

		c = parseFloat($(this).html().replace(",",""));
		if (!isNaN(c)) {
			sum += parseFloat($(this).html().replace(",",""));
		}
	});
	$("#tdSumaPercepciones").html(numeral(sum).format('$0,0.00'));
};


function sumaDeduccion() {
	var sum = 0;
	$('.importeDeducciones').each(function() {
		c= parseFloat($(this).html().replace(",",""));
		if (!isNaN(c)) {
			sum += parseFloat($(this).html().replace(",",""));		
		}
	});

	$("#tdSumaDeducciones").html(numeral(sum).format('$0,0.00'));
	resta();
};

function resta(){

	var perce = $("#tdSumaPercepciones").html().replace(',','');
	var deduccion = $("#tdSumaDeducciones").html().replace(',','');
	var y = deduccion.replace('$', '');

	var number = numeral(perce),
	value = y;

	var difference = number.difference(value);

	$("#resta").html(numeral(difference).format('$0,0.00'));
}

function guardarinput(e,input){
	vali= $('#i_'+input).val();
	
	if(e.keyCode === 13){

		e.preventDefault();

		$.ajax({
			url:"ajax.php?c=Sobrerecibo&f=actpercepcionDeduccion",
			type: 'POST',
			data:{vali:vali,input:input},
			success: function(r){
				if(r == 1){
					$('#'+input).html('<td id="'+input+'" onclick="editar(\''+input+'\');">'+vali+'</td>');
					conceptos();
				}
			}
		});
		
	}else if(e.keyCode === 27){
		$('#'+input).html('<td id="'+input+'" onclick="editar(\''+input+'\');">'+vali+'</td>');
	}
}

function editardias(label){
	//$('#i_'+iddiv).focus().val("").val(valortd);

	
	$('#i_'+label).show();
	$('#i_'+label).focus().val("").val( $('#'+label).text() );	  
	$('#'+label).hide();
	//$('#'+label).prop('onclick',null).off('click');
}
function guardaDias(e,campo){
	
	if(e.keyCode === 13){

		var valor = $('#i_'+campo).val();
		e.preventDefault();

		$.ajax({
			url:"ajax.php?c=Sobrerecibo&f=actualizarDias",
			type: 'POST',
			data:{
				idnomp:$("#idnompenvi").val(),
				valor:valor,
				idempleado:$("#empleado").val(),
				campo:campo
			},
			success: function(r){
				if(r == 1){
					$('#i_'+campo).hide();
					$('#'+campo).show();
					$('#'+campo).text(valor);
				}
			}
		});
	}else if(e.keyCode === 27 || e.code === 'Escape' ){
		$('#i_'+campo).hide();
		$('#'+campo).show();
	}
}

function editar(iddiv){
	valortd= $('#'+iddiv).text();	  
	$('#'+iddiv).html('<input id="i_'+iddiv+'" title="Presione ENTER para guardar y ESC para salir" onkeydown="guardarinput(event,\''+iddiv+'\');"  style="width:100%;" type="text" value="'+valortd+'">');
	$('#i_'+iddiv).focus().val("").val(valortd);
	$('#'+iddiv).prop('onclick',null).off('click');
}



function diastranscurridos(){ 
	var aFecha1 = $("#fechainiciovacaciones").val().split('-'); 
	var aFecha2 = $("#fechafinalvacaciones").val().split('-'); 
	var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1],aFecha1[2]-1); 
	var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1],aFecha2[2]); 
	var dif = fFecha2 - fFecha1;
	var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
	$("#diasvacaciones").val( dias);
	$("#diasvacacionesrespaldo").val(dias);
	calculaVaciones(dias);
}

function datosEmp(){


	var  idnomp = $("#empleado option:selected").attr("idnomp");

	$("#idnompenvi").val(idnomp);

// alert(idnomp);


$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);$("#ediciondatos").val(0);
$('.nav-tabs a[href="#'+$("#tabvalor").val()+'"]').tab('show').click();	
limpiar($("#tabvalor").val());
var  idEmpleado = $("#empleado").val();

$("#empleadosdos").val(idEmpleado);	
var nombre=$("#nombre").val();


if(idEmpleado!=0){
    
    $("#muestradiv").hide();
	$("#loade").show();

	$.post("ajax.php?c=Sobrerecibo&f=datosEmpleado",{

		idEmpleado:	idEmpleado,
		idnomp: $("#empleado option:selected").attr("idnomp"),

	},function(resp){
		var separa = resp.split("/");
		$("#idEmpleado").text(separa[0]);
		$("#dep").text(separa[1]);
		$("#diaspagados").text(separa[2]);
		$("#diaslaborados").text(separa[3]);
		$("#codigo").text(separa[4]);	
		$("#rfc").text(separa[5]);
		$("#nomina").text(separa[6]);
		$("#fechainicio").text(separa[7]);
		$("#curp").text(separa[8]);
		$("#nss").text(separa[9]);
		$("#emple").text(separa[10]);
		$("#contrato").val(separa[11]);
		$("#sueldo").val(separa[12]);
		$("#salario").text(separa[13]);
		$("#numnomina").text(separa[15]);
		$("#horas").text(separa[16]);
		$("#diaslabproporcion").text(separa[17]);
		$("#diasvac").text(separa[18]);
		$("#diasfestivo").text(separa[19]);

		$("#loade").hide();
		$("#muestradiv").show();
		 });

	if($("#tabvalor").val() == "general"){

				conceptos();

	} else if ($("#tabvalor").val()=='infona') { 
		infonavitEmpleado();
	}else  if ($("#tabvalor").val()=='vaca') { 
		vacacionesEmpleado();
	}

}else{
	$("#sueldo").val("");
	$("#contrato").val("");
}
}

function ContPercepciones(idEmpleado){
	var idEmpleado = $("#empleado").val();
	$.post("ajax.php?c=Sobrerecibo&f=cargaPercepcion",{
		idEmpleado:idEmpleado,
		idnomp:$("#empleado option:selected").attr("idnomp")

	},function (request){
		$("#contPerce").html(request);
		sumaPercep();

	});
}

function ContDeducciones(idEmpleado){	

	var idEmpleado = $("#empleado").val();
	$.post("ajax.php?c=Sobrerecibo&f=cargaDeduccion",{
		idEmpleado:idEmpleado,
		idnomp:$("#empleado option:selected").attr("idnomp")
	},function (request){

		$("#contDeduccion").html(request);
		sumaDeduccion();

	});
}
function conceptos(){
	if( $("#empleado").val()!= 0 ){
		$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);$("#ediciondatos").val(0);
		$("#tabvalor").val('general');
		ContPercepciones($("#empleado").val());
		ContDeducciones($("#empleado").val());
		$("#contDeduccion").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>');
		$("#contPerce").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>'); 

             		
	}else{
		$("#contPerce,#contDeduccion,#tdSumaPercepciones,#tdSumaDeducciones,#resta").html('');
	}

		
//infonavitEmpleado();
//fonacotEmpleado();
//incapacidadEmpleado();
}


function metodo(){

if($("#calculoretencion").val()==1){//Importe Fijo
	$("#tipoimporte").html("Importe Fijo");
}else{//Proporcion a dias trabajados
	$("#tipoimporte").html("Proporcion a dias trabajados");
}
}

function calculasaldofonacot(){ 
	if( parseFloat($("#pagohechosotros").val()) <= parseFloat($("#importecreditofonacot").val()) ){

		$("#saldofonacot").val( $("#importecreditofonacot").val() - $("#pagohechosotros").val() ).number(true,2);

	}else if(!$("#pagohechosotros").val() || $("#pagohechosotros").val()==0){
		$("#saldofonacot").val( $("#importecreditofonacot").val() ).number(true,2);
	}
	else{

		$("#saldofonacot").val( $("#pagohechosotros").val() - $("#importecreditofonacot").val() ).number(true,2);
	}
}
function tipoconcepto(){
	$.post("ajax.php?c=Sobrerecibo&f=listaconceptotipo",{
		tipo:$("#tipoconcepto").val()
	},function(request){
		$("#concepto").html(request).selectpicker("refresh");
	});
}
function ramoincapacidad(){
	if( $("#ramoincapacidad").val() == 1 ){
		$("#tiporiesgoincapacidad").attr("disabled",false).selectpicker("refresh");
		$("#porcentajeincapacidad").attr("readonly",false);
	}else{
		$("#tiporiesgoincapacidad").val(3).attr("disabled",true).selectpicker("refresh");
		$("#porcentajeincapacidad").val(0).attr("readonly",true);
	}
}
function tipocaptura(){
	if( $("#tipocapturavacaciones").val() == 1 ){

		$("#fechafinalvacaciones").val( $("#fechainiciovacaciones").val() ).datepicker("disable").attr("readonly",true);
		$("#diasdescansoseptimo").val(0).attr("readonly",true);
		$("#diasvacaciones").val(0);

	}else{

		$("#fechafinalvacaciones").val("").datepicker("enable").attr("readonly",false);
		$("#diasdescansoseptimo").val(0).attr("readonly",false);
		$("#diasvacaciones").val(0);
	}
}
function diasdescanso(diasseptimo){

	$("#diasvacaciones").val($("#diasvacacionesrespaldo").val());
	if(parseInt( diasseptimo) > parseInt($("#diasvacacionesrespaldo").val()) && diasseptimo){
		alert("Los días de descanso no pueden ser mayor a los días de vacaciones ");
		$("#diasdescansoseptimo").val(0);
	}else{
		if(diasseptimo == 0 || !diasseptimo){
			diastranscurridos();
		}else{
			$("#diasvacaciones").val( parseInt( $("#diasvacaciones").val()) - parseInt(diasseptimo));
		}
	}
}


/* F U N C I O N E S   P O R    S O B R E    R E C I B O */

function permanentesEmpleado(){$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);$("#ediciondatos").val(0);
limpiar("perma");
$("#tabvalor").val('perma');
if( $("#empleado").val()!= 0 ){
	$.post("ajax.php?c=Sobrerecibo&f=permanentesEmpleado",{
		idempleado:$("#empleado").val()
	},function (request){
		$("#listaMovPermanentes").html(request);

	});
}

}
function permanentesEdicion(idmovper){$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);
$.post("ajax.php?c=Sobrerecibo&f=permanentesEdicion",{
	idempleado:$("#empleado").val(),
	idmovper:idmovper
},function (request){
	var dataJson = eval(request);

	for(var i in dataJson){

		$("#descripcion").val(dataJson[i].descripcion).attr("disabled",true);
		$("#tipoconcepto").val(dataJson[i].idtipo).attr("disabled",true).selectpicker("refresh");
		$("#concepto").val(dataJson[i].idconcepto).attr("disabled",true).selectpicker("refresh");
		$("#fechaaplicacionpermanente").val(dataJson[i].fechainicio);
		$("#importeOvalor").val(dataJson[i].importeOvalor).selectpicker("refresh");
		$("#imporvalor").val(dataJson[i].imporvalor);
		$("#vecesaplica").val(dataJson[i].vecesaplica);
		$("#vecesaplicadopermanente").val(dataJson[i].vecesaplicado);
		$("#montolimite").val(dataJson[i].montolimite);
		$("#montoacumulado").val(dataJson[i].montoacumulado);
		$("#fecharegistropermanente").val(dataJson[i].fecharegistro);
		$("#numcontrol").val(dataJson[i].numerocontrol);
		$("#estatuspermanente").val(dataJson[i].estatus).selectpicker("refresh");

		$("#ediciondatos").val(dataJson[i].idmovper);
		$("#edicionp").html("E D I C I O N ! "+dataJson[i].descripcion);
		$("#edicionp").show();

	}

});
}
// FIN PERMANENTES //

//		INFONAVIT 		//
function infonavitEdicion(idmovper){
	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);
	$.post("ajax.php?c=Sobrerecibo&f=infonavitEdicion",{
		idempleado:$("#empleado").val(),
		idmovper:idmovper
	},function (request){
		var dataJson = eval(request);

		for(var i in dataJson){

			$("#numinfonavit").val( dataJson[i].numinfonavit);
			$("#tipocreditoinfonavit").val(dataJson[i].tipocredito).attr("disabled",true).selectpicker("refresh");
			$("#descripcioninfonavit").val(dataJson[i].descripcion),
			$("#fechaaplicacioninfonavit").val(dataJson[i].fechaaplicacion);
			$("#montoacumuladoinfonavit").val(dataJson[i].montoacumulado);
			$("#vecesaplicadoinfonavit").val(dataJson[i].vecesaplicado);
			$("#fecharegistroinfonavit").val(dataJson[i].fecharegistro);
			$("#pagodeseguro").val(dataJson[i].pagodeseguro).selectpicker("refresh");
			$("#factormensual").val(dataJson[i].factormensual);
			$("#estatusinfonavit").val(dataJson[i].numinfonavit).selectpicker("refresh");

			$("#ediciondatos").val(dataJson[i].idinfonavit);
			$("#edicioni").html("E D I C I O N ! "+dataJson[i].descripcion);
			$("#edicioni").show();

		}

	});

}
function infonavitEmpleado(idmovper){
	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);$("#ediciondatos").val(0);
	limpiar('infonavit');
	$("#tabvalor").val('infona');
	if( $("#empleado").val()!= 0 ){
		$.post("ajax.php?c=Sobrerecibo&f=infonavitEmpleado",{
			idempleado:$("#empleado").val()
		},function (request){
			$("#listaInfonavit").html(request);

		});
	}

}
//	FIN	INFONAVIT 		//


//		FONACOT 		//
function fonacotEdicion(idmovper){
	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);
	$.post("ajax.php?c=Sobrerecibo&f=fonacotEdicion",{
		idempleado:$("#empleado").val(),
		idmovper:idmovper
	},function (request){
		var dataJson = eval(request);

		for(var i in dataJson){

			$("#numcreditofonacot").val(dataJson[i].numcredito);
			$("#descripcionfonacot").val(dataJson[i].descripcion);
			$("#mesfonacot").val(dataJson[i].mes).selectpicker("refresh");
			$("#ejerciciofonacot").val(dataJson[i].ejercicio);
			$("#calculoretencion").val(dataJson[i].calculoretencion).selectpicker("refresh");
			$("#importecreditofonacot").val(dataJson[i].importecredito);
			$("#retencionmensual").val(dataJson[i].retencionmensual);
			$("#pagohechosotros").val(dataJson[i].pagohechosotros);
			$("#retenidoacumulado").val(dataJson[i].montoacumuladoretenido);
			$("#saldofonacot").val(dataJson[i].saldo);
			$("#estatusfonacot").val(dataJson[i].estatus).selectpicker("refresh");
			$("#observacionesfonacot").val(dataJson[i].obervaciones);
			$("#ediciondatos").val(dataJson[i].idfonacotsobre);

			$("#edicionf").html("E D I C I O N ! "+dataJson[i].numcredito);
			$("#edicionf").show();

		}

	});

}
function fonacotEmpleado(){
	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);$("#ediciondatos").val(0);
	limpiar("fonacot");
	$("#tabvalor").val('fonacot');
	if( $("#empleado").val()!= 0 ){
		$.post("ajax.php?c=Sobrerecibo&f=fonacotEmpleado",{
			idempleado:$("#empleado").val()
		},function (request){
			$("#listafonacot").html(request);

		});
	}

}
//	FIN	FONACOT 		//

//		INCAPACIDADES 		//
function incapacidadEdicion(idmovper){
	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);
	$.post("ajax.php?c=Sobrerecibo&f=incapacidadesEdicion",{
		idempleado:$("#empleado").val(),
		idmovper:idmovper
	},function (request){
		var dataJson = eval(request);

		for(var i in dataJson){

			$("#folioincapacidad").val(dataJson[i].folio).attr("readonly",true);
			$("#tipoincidenciaincapacidad").val(dataJson[i].idtipoincidencia).selectpicker("refresh");
			$("#diasautorizadosincapacidad").val(dataJson[i].diasautorizados);
			$("#ramoincapacidad").val(dataJson[i].ramoseguro).selectpicker("refresh");
			$("#fechainicioincapacidad").val(dataJson[i].fechainicio);
			$("#tiporiesgoincapacidad").val(dataJson[i].tiporiesgo).selectpicker("refresh");
			$("#porcentajeincapacidad").val(dataJson[i].porcentajeincapacidad);
			$("#controlincapacidad").val(dataJson[i].idcontrol).selectpicker("refresh");
			$("#hechosincapacidad").val(dataJson[i].descripcion);
			$("#secuelaincapacidad").val(dataJson[i].idsecuela).selectpicker("refresh");

			$("#ediciondatos").val(dataJson[i].idincapacidadsobre);
			$("#edicioninc").html("E D I C I O N ! "+ dataJson[i].folio);
			$("#edicioninc").show();
			ramoincapacidad();
		}

	});

}
function incapacidadEmpleado(){
	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);$("#ediciondatos").val(0);
	limpiar('incapa');
	$("#tabvalor").val('incapa');
	if( $("#empleado").val()!= 0 ){
		$.post("ajax.php?c=Sobrerecibo&f=incapacidadesEmpleado",{
			idempleado:$("#empleado").val()
		},function (request){
			$("#listaincapacidad").html(request);

		});
	}

}
//	FIN	INCAPACIDADES 		//

//		VACACIONES 		//
function vacacionesEdicion(idmovper){

	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);
	$.post("ajax.php?c=Sobrerecibo&f=vacacionesEdicion",{
		idempleado:$("#empleado").val(),
		idmovper:idmovper
	},function (request){

		var dataJson = eval(request);

		for(var i in dataJson){

			$("#tipocapturavacaciones").val(dataJson[i].tipocaptura).selectpicker("refresh");
			$("#fechainiciovacaciones").val(dataJson[i].fechainicial);
			$("#fechafinalvacaciones").val(dataJson[i].fechafinal).datepicker('disable');;
			$("#fechapagovacaciones").val(dataJson[i].fechapago);
			$("#diasdescansoseptimo").val(dataJson[i].diasdescansoseptimo).attr("readonly",true);
			$("#diasvacaciones").val(dataJson[i].diasvacaciones);
			$("#diasvacprimavac").val(dataJson[i].diasvacprimavac);
			$("#vacacionesacumuladas,#vacacionesacumuladasrespaldo").val(dataJson[i].vacacionesacumuladas);
			$("#primaacumuladovacaciones").val(dataJson[i].diasprimaacumulado);
			$("#vacapendientevacaciones,#vacapendientevacacionesrespaldo").val(dataJson[i].vacacionespendientes);
			$("#diaprimapendientevacaciones").val(dataJson[i].diasprimapendiente);

			$("#ediciondatos").val(dataJson[i].idvacasobrerecibo);
			$("#edicionvaca").html("E D I C I O N !  "+ dataJson[i].fechainicial + " | "+dataJson[i].fechafinal);
			$("#edicionvaca").show();

		}

	});

}
function vacacionesEmpleado(){
	$(".nuevo,.edicion").hide();$("#nuevosdatos").val(0);$("#ediciondatos").val(0);
	limpiar('vaca');
	$("#tabvalor").val('vaca');
	if( $("#empleado").val()!= 0 ){
		$.post("ajax.php?c=Sobrerecibo&f=vacacionesEmpleado",{
			idempleado:$("#empleado").val()
		},function (request){
//alert(request);
$("#listavacaciones").html(request);

});
	}

}
//	FIN	VACACIONES 		//

function nuevo(menu,etiqueta){
	$("#ediciondatos").val(0);
	$(".edicion").hide();
	if( $("#empleado").val()!= 0 ){
		if(menu == "perma"){

			$("#"+etiqueta).show();
			limpiar("perma");
			$("#nuevosdatos").val(1);

///		///	//	/	/	/	/		/	///
}else if(menu == "infonavit"){

	$("#"+etiqueta).show();
	limpiar("infonavit");
	$("#nuevosdatos").val(1);

}else if(menu == "fonacot"){

	$("#"+etiqueta).show();
	limpiar("fonacot");
	$("#nuevosdatos").val(1);

}
else if(menu == "incapa"){

	$("#"+etiqueta).show();
	limpiar("incapa");
	$("#nuevosdatos").val(1);

}
else if(menu == "vaca"){

	$("#"+etiqueta).show();
	limpiar("vaca");
	$("#nuevosdatos").val(1);

}
}else{
	alert("Debe seleccionar un empleado primero!");
}

}


function limpiar(opc){

	if(opc == "perma"){
		$("#descripcion").val("").attr("disabled",false);
		$("#tipoconcepto").val(0).attr("disabled",false).selectpicker("refresh");;
		$("#concepto").val("").attr("disabled",false).selectpicker("refresh");
		$("#fechaaplicacionpermanente").val("");
		$("#importeOvalor").val("");
		$("#imporvalor").val("");
		$("#vecesaplica").val("");
		$("#vecesaplicadopermanente").val("");
		$("#montolimite").val("");
		$("#montoacumulado").val("");
		$("#fecharegistropermanente").val("");
		$("#numcontrol").val("");
		$("#estatuspermanente").val(1).selectpicker("refresh");


	}else if(opc == 'infonavit'){
		$("#numinfonavit").val("");
		$("#tipocreditoinfonavit").val(0).attr("disabled",false).selectpicker("refresh");
		$("#fechaaplicacioninfonavit").val("");
		$("#montoacumuladoinfonavit").val("");
		$("#vecesaplicadoinfonavit").val("");
		$("#fecharegistroinfonavit").val("");
		$("#pagodeseguro").val(0).selectpicker("refresh");
		$("#estatusinfonavit").val(0).selectpicker("refresh");
		$("#descripcioninfonavit").val("");
		$("#factormensual").val("");

	}
	else if( opc == 'fonacot' ){
		$("#numcreditofonacot").val("");
		$("#descripcionfonacot").val("");
		$("#mesfonacot").val(0).selectpicker("refresh");
		$("#ejerciciofonacot").val("2017");
		$("#calculoretencion").val(1).selectpicker("refresh");
		$("#importecreditofonacot").val("");
		$("#retencionmensual").val("");
		$("#pagohechosotros").val("");
		$("#retenidoacumulado").val("");
		$("#saldofonacot").val("");
		$("#estatusfonacot").val(1).selectpicker("refresh");
		$("#observacionesfonacot").val("");
		$("#importecreditofonacot").val("");
	}
	else if( opc == 'incapa' ){
		$("#folioincapacidad").val("").attr("readonly",false);
		$("#tipoincidenciaincapacidad").val(0).attr("disabled",false).selectpicker("refresh");
		$("#diasautorizadosincapacidad").val("").attr("disabled",false);
		$("#ramoincapacidad").val(1).selectpicker("refresh");
		$("#fechainicioincapacidad").val("");
		$("#tiporiesgoincapacidad").val(1).selectpicker("refresh");
		$("#porcentajeincapacidad").val("");
		$("#controlincapacidad").val(1).selectpicker("refresh");
		$("#hechosincapacidad").val("");
		$("#secuelaincapacidad").val(1).selectpicker("refresh");
		ramoincapacidad();
	}
	else if( opc == 'vaca' ){

		$("#tipocapturavacaciones").val(2).selectpicker("refresh");
		$("#fechainiciovacaciones").val("");
		$("#fechafinalvacaciones").val("").datepicker('enable');
		$("#fechapagovacaciones").val("");
		$("#diasdescansoseptimo").val("").attr("readonly",false);
		$("#diasvacaciones").val(0);
		$("#diasvacprimavac").val("");
		$("#vacacionesacumuladas").val("");
		$("#primaacumuladovacaciones").val("");
		$("#vacapendientevacaciones").val("");
		$("#diaprimapendientevacaciones").val("");

	}

}

function guarda(menu,load,normal){

	if( $("#nuevosdatos").val() == 1 ){
		var status = true;
		if(menu == "perma"){
/// PERMANENETES //
$("#"+load).show();
$("#"+normal).hide();
$.post("ajax.php?c=Sobrerecibo&f=almacenaPermanentes",{
	idempleado					: $("#empleado").val(),
	descripcion					: $("#descripcion").val(),
	tipoconcepto				: $("#tipoconcepto").val(),
	concepto					: $("#concepto").val(),
	fechaaplicacionpermanente	: $("#fechaaplicacionpermanente").val(),
	importeOvalor				: $("#importeOvalor").val(),
	imporvalor					: $("#imporvalor").val(),
	vecesaplica					: $("#vecesaplica").val(),
	vecesaplicadopermanente		: $("#vecesaplicadopermanente").val(),
	montolimite					: $("#montolimite").val(),
	montoacumulado				: $("#montoacumulado").val(),
	fecharegistropermanente		: $("#fecharegistropermanente").val(),
	numcontrol					: $("#numcontrol").val(),
	estatuspermanente			: $("#estatuspermanente").val(),
	nominaactiva				: $("#nominaactiva").val(),
	opc:1
},function (request){
	if(parseInt(request ) == 1){
		alert("Registro almacenado");
		permanentesEmpleado();
	}else{
		alert("Error en el proceso intente de nuevo");
	}
	$("#"+load).hide();
	$("#"+normal).show();
});
}
/// FIN PERMANENETES //

// infonavit //
else if(menu == "infonavit"){
	$("#"+load).show();
	$("#"+normal).hide();
	$("#tipocreditoinfonavit").attr("disabled",false);
	$.post("ajax.php?c=Sobrerecibo&f=almacenaInfonavit",{
		idempleado				    : $("#empleado").val(),
		numinfonavit				: $("#numinfonavit").val(),
		descripcioninfonavit		: $("#descripcioninfonavit").val(),
		tipocreditoinfonavit		: $("#tipocreditoinfonavit").val(),
		fechaaplicacioninfonavit	: $("#fechaaplicacioninfonavit").val(),
		montoacumulado			    : $("#montoacumuladoinfonavit").val(),
		vecesaplicadoinfonavit	    : $("#vecesaplicadoinfonavit").val(),
		fecharegistroinfonavit	    : $("#fecharegistroinfonavit").val(),
		pagodeseguro				: $("#incluirpagoseguro").val(),
		estatusinfonavit			: $("#estatusinfonavit").val(),
		factormensual			    : $("#factormensual").val(),
		nominaactiva				: $("#nominaactiva").val(),
		opc:1
	},function (request){
		if(parseInt(request ) == 1){
			alert("Registro almacenado");
			infonavitEmpleado();
		}else{
			alert("Error en el proceso intente de nuevo");
		}
		$("#"+load).hide();
		$("#"+normal).show();
	});
}
// fin infonavit //

// fonacot //
else if(menu == "fonacot"){

	if( !$("#numcreditofonacot").val() ){
		alert("El numero de credito no puede ir nulo");
		estatus = false;
	}
	if( $("#retencionmensual").val() == 0 || !$("#retencionmensual").val()  ){
		alert("La Retencion Mensual o Importe Fijo no puede ser 0");
		status = false;
	}

	if(status === true ){
		$("#"+load).show();
		$("#"+normal).hide();
		$.post("ajax.php?c=Sobrerecibo&f=almacenaFonacot",{
			idempleado			    : $("#empleado").val(),
			numcreditofonacot	    : $("#numcreditofonacot").val(),
			descripcionfonacot	    : $("#descripcionfonacot").val(),
			mesfonacot			    : $("#mesfonacot").val(),
			ejerciciofonacot		: $("#ejerciciofonacot").val(),
			calculoretencion		: $("#calculoretencion").val(),
			importecreditofonacot   : $("#importecreditofonacot").val(),
			retencionmensual		: $("#retencionmensual").val(),
			pagohechosotros		    : $("#pagohechosotros").val(),
			retenidoacumulado	    : $("#retenidoacumulado").val(),
			saldofonacot			: $("#saldofonacot").val(),
			estatusfonacot		    : $("#estatusfonacot").val(),
			observacionesfonacot    : $("#observacionesfonacot").val(),
			nominaactiva			: $("#nominaactiva").val(),
			opc:1

		},function (request){
			if(parseInt(request ) == 1){
				alert("Registro almacenado");
				fonacotEmpleado();
			}else{
				alert("Error en el proceso intente de nuevo");
			}
			$("#"+load).hide();
			$("#"+normal).show();
		});
	}
}
// fin fonacot //

// INCAPACIDAD //
else if(menu == "incapa"){
	if( $("#folioincapacidad").val() == 0 || !$("#folioincapacidad").val() ){
		alert("El folio no puede ser nulo, o no tiene un formao valido");
		status = false;
	}

	if(status === true ){
		$("#"+load).show();
		$("#"+normal).hide();

		if(	!$("#porcentajeincapacidad").val()	){
			$("#porcentajeincapacidad").val(0);
		}
		$.post("ajax.php?c=Sobrerecibo&f=almacenaIncapacidad",{
			idempleado					: $("#empleado").val(),
			folioincapacidad			: $("#folioincapacidad").val(),
			tipoincidenciaincapacidad	: $("#tipoincidenciaincapacidad").val(),
			diasautorizadosincapacidad	: $("#diasautorizadosincapacidad").val(),
			ramoincapacidad				: $("#ramoincapacidad").val(),
			fechainicioincapacidad		: $("#fechainicioincapacidad").val(),
			tiporiesgoincapacidad		: $("#tiporiesgoincapacidad").val(),
			porcentajeincapacidad		: $("#porcentajeincapacidad").val(),
			controlincapacidad			: $("#controlincapacidad").val(),
			secuelaincapacidad			: $("#secuelaincapacidad").val(),
			hechosincapacidad			: $("#hechosincapacidad").val(),
			nominaactiva				: $("#nominaactiva").val(),
			opc:1

		},function (request){
			if(parseInt(request ) == 1){
				alert("Registro almacenado");
				incapacidadEmpleado();
			}else{
				alert("Error en el proceso intente de nuevo");
			}
			$("#"+load).hide();
			$("#"+normal).show();
		});
	}
}
// fin INCAPACIDAD //

// VACACIONES //
else if(menu == "vaca"){
	$("#"+load).show();
	$("#"+normal).hide();
// 						if((Date.parse(fech1)) > (Date.parse(fech2))){
// alert("La fecha inicial no puede ser mayor que la fecha final");
// }
$.post("ajax.php?c=Sobrerecibo&f=almacenaVacaciones",{
	idempleado				    : $("#empleado").val(),
	tipocapturavacaciones	    : $("#tipocapturavacaciones").val(),
	fechainiciovacaciones	    : $("#fechainiciovacaciones").val(),
	fechafinalvacaciones	    : $("#fechafinalvacaciones").val(),
	fechapagovacaciones		    : $("#fechapagovacaciones").val(),
	diasdescansoseptimo		    : $("#diasdescansoseptimo").val(),
	diasvacaciones			    : $("#diasvacaciones").val(),
	diasvacprimavac			    : $("#diasvacprimavac").val(),
	nominaactiva				: $("#nominaactiva").val(),
	fechaactiva                 : $("#fechaactiva").val(),
	vacacionesacumuladas		: $("#vacacionesacumuladas").val(),
	primaacumuladovacaciones	: $("#primaacumuladovacaciones").val(),
	vacapendientevacaciones	    : $("#vacapendientevacaciones").val(),
	diaprimapendientevacaciones : $("#diaprimapendientevacaciones").val(),


	opc:1

},function (request){
//alert(request);
if(parseInt(request ) == 1){
	alert("Registro almacenado");
	vacacionesEmpleado();
}else if (parseInt(request) == -1){
	alert("No tiene dada de alta vacaciones.");
}else{
	alert("Error en el proceso intente de nuevo");
}
$("#"+load).hide();
$("#"+normal).show();
});
}
// fin VACACIONES //
}else{
	if( $("#ediciondatos").val() != 0 ){
/// PERMANENETES //
if(menu == "perma"){
	$("#"+load).show();
	$("#"+normal).hide();
	$.post("ajax.php?c=Sobrerecibo&f=almacenaPermanentes",{
		idempleado					: $("#empleado").val(),
		descripcion					: $("#descripcion").val(),
		tipoconcepto				: $("#tipoconcepto").val(),
		concepto					: $("#concepto").val(),
		fechaaplicacionpermanente	: $("#fechaaplicacionpermanente").val(),
		importeOvalor				: $("#importeOvalor").val(),
		imporvalor					: $("#imporvalor").val(),
		vecesaplica					: $("#vecesaplica").val(),
		vecesaplicadopermanente		: $("#vecesaplicadopermanente").val(),
		montolimite					: $("#montolimite").val(),
		montoacumulado				: $("#montoacumulado").val(),
		fecharegistropermanente		: $("#fecharegistropermanente").val(),
		numcontrol					: $("#numcontrol").val(),
		estatuspermanente			: $("#estatuspermanente").val(),
		idmovper					: $("#ediciondatos").val(),
		nominaactiva				: $("#nominaactiva").val(),
		opc:2


	},function (request){
		if(parseInt(request ) == 1){
			alert("Registro Actualizado");
			permanentesEmpleado();
		}else{
			alert("Error en el proceso intente de nuevo");
		}
		$("#"+load).hide();
		$("#"+normal).show();
	});
}
// FIN PERMANENTES //
else if(menu == "infonavit"){
	$("#"+load).show();
	$("#"+normal).hide();
	$("#tipocreditoinfonavit").attr("disabled",false);
	$.post("ajax.php?c=Sobrerecibo&f=almacenaInfonavit",{
		idempleado				    : $("#empleado").val(),
		numinfonavit				: $("#numinfonavit").val(),
		descripcioninfonavit		: $("#descripcioninfonavit").val(),
		tipocreditoinfonavit		: $("#tipocreditoinfonavit").val(),
		fechaaplicacioninfonavit	: $("#fechaaplicacioninfonavit").val(),
		montoacumulado			    : $("#montoacumuladoinfonavit").val(),
		vecesaplicadoinfonavit	    : $("#vecesaplicadoinfonavit").val(),
		fecharegistroinfonavit	    : $("#fecharegistroinfonavit").val(),
		pagodeseguro				: $("#incluirpagoseguro").val(),
		estatusinfonavit			: $("#estatusinfonavit").val(),
		factormensual			    : $("#factormensual").val(),
		nominaactiva				: $("#nominaactiva").val(),
		opc:2,
		idinfonavit				    : $("#ediciondatos").val()
	},function (request){
		if(parseInt(request ) == 1){
			alert("Registro Actualizado");
			infonavitEmpleado();
		}else{
			alert("Error en el proceso intente de nuevo");
		}
		$("#"+load).hide();
		$("#"+normal).show();
	});
}
// fonacot //
else if(menu == "fonacot"){
	$("#"+load).show();
	$("#"+normal).hide();
	$.post("ajax.php?c=Sobrerecibo&f=almacenaFonacot",{
		idempleado			   : $("#empleado").val(),
		numcreditofonacot	   : $("#numcreditofonacot").val(),
		descripcionfonacot	   : $("#descripcionfonacot").val(),
		mesfonacot			   : $("#mesfonacot").val(),
		ejerciciofonacot	   : $("#ejerciciofonacot").val(),
		calculoretencion	   : $("#calculoretencion").val(),
		importecreditofonacot  : $("#importecreditofonacot").val(),
		retencionmensual	   : $("#retencionmensual").val(),
		pagohechosotros		   : $("#pagohechosotros").val(),
		retenidoacumulado	   : $("#retenidoacumulado").val(),
		saldofonacot		   : $("#saldofonacot").val(),
		estatusfonacot		   : $("#estatusfonacot").val(),
		observacionesfonacot   : $("#observacionesfonacot").val(),
		idfonacot			   : $("#ediciondatos").val(),
		nominaactiva		   : $("#nominaactiva").val(),
		opc:2

	},function (request){
		if(parseInt(request ) == 1){
			alert("Registro Actualizado");
			fonacotEmpleado();
		}else{
			alert("Error en el proceso intente de nuevo");
		}
		$("#"+load).hide();
		$("#"+normal).show();
	});
}
// fin fonacot //

// INCAPACIDAD //
else if(menu == "incapa"){
	$("#"+load).show();
	$("#"+normal).hide();

	if(	!$("#porcentajeincapacidad").val()	){
		$("#porcentajeincapacidad").val(0);
	}

//alert($("#empleado").val());

$.post("ajax.php?c=Sobrerecibo&f=almacenaIncapacidad",{
	idempleado					: $("#empleado").val(),
	folioincapacidad			: $("#folioincapacidad").val(),
	tipoincidenciaincapacidad	: $("#tipoincidenciaincapacidad").val(),
	diasautorizadosincapacidad	: $("#diasautorizadosincapacidad").val(),
	ramoincapacidad				: $("#ramoincapacidad").val(),
	fechainicioincapacidad		: $("#fechainicioincapacidad").val(),
	tiporiesgoincapacidad		: $("#tiporiesgoincapacidad").val(),
	porcentajeincapacidad		: $("#porcentajeincapacidad").val(),
	controlincapacidad			: $("#controlincapacidad").val(),
	secuelaincapacidad			: $("#secuelaincapacidad").val(),
	hechosincapacidad			: $("#hechosincapacidad").val(),
	idincapacidadsobre			: $("#ediciondatos").val(),
	nominaactiva				: $("#nominaactiva").val(),
	opc:2

},function (request){
//alert(request);
if(parseInt(request ) == 1){
	alert("Registro Actualizado.");
	incapacidadEmpleado();
}else{
	alert("Error en el proceso intente de nuevo.");
}
$("#"+load).hide();
$("#"+normal).show();
});
}
// fin INCAPACIDAD //



// VACACIONES //
else if(menu == "vaca"){
	$("#"+load).show();
	$("#"+normal).hide();
	$.post("ajax.php?c=Sobrerecibo&f=almacenaVacaciones",{
		idempleado				    : $("#empleado").val(),
		tipocapturavacaciones	    : $("#tipocapturavacaciones").val(),
		fechainiciovacaciones	    : $("#fechainiciovacaciones").val(),
		fechafinalvacaciones		: $("#fechafinalvacaciones").val(),
		fechapagovacaciones		    : $("#fechapagovacaciones").val(),
		diasdescansoseptimo		    : $("#diasdescansoseptimo").val(),
		diasvacaciones			    : $("#diasvacaciones").val(),
		diasvacprimavac			    : $("#diasvacprimavac").val(),
		idvacasobrerecibo		    : $("#ediciondatos").val(),
		nominaactiva			    : $("#nominaactiva").val(),		
		vacacionesacumuladas		: $("#vacacionesacumuladas").val(),
		primaacumuladovacaciones	: $("#primaacumuladovacaciones").val(),
		vacapendientevacaciones	    : $("#vacapendientevacaciones").val(),
		diaprimapendientevacaciones : $("#diaprimapendientevacaciones").val(),

		opc:2

	},function (request){
//alert(request);
if(parseInt(request ) == 1){

	alert("Registro Actualizado");
	vacacionesEmpleado();    
}

else if (request == 2) {
	alert("Existen dias aplicados en el periodo vacacional.");


}else{
	alert("Error en el proceso.");
}

$("#"+load).hide();
$("#"+normal).show();
});
}
// fin VACACIONES //

}else{
	alert("Debe estar en modo de Agregar Nuevo");
}
}

}
function traerFechas(idnomina,fechainicio, fechafin, autorizado, editable,periodosfuturos){

	var table = $('#tablaincidencias').DataTable();
	table.destroy();
	var cantidadDias = 0;
$.post("../../modulos/nominas/ajax.php?c=registroincidencias&f=rangofechas&fi="+fechainicio +"&ff="+fechafin,//select llena tipo operacion
	function(data) 
	{
var arreglodias =  JSON.parse(data);// parsea los datos dentro de un arreglo 
cantidadDias = arreglodias.length;
$("#trHeader").html("");//encabezados para el rango de fechas
var htmltable   =  '<th><b>CÓDIGO EMPLEADO</b></th>' + '<th><b>NOMBRE EMPLEADO</b></th>';
for (var i=0; i< arreglodias.length; i++){
htmltable = htmltable + '<th><b>' + arreglodias[i] + '</b></th>';	//Me agrega todo el rango de fechas en el periodo seleccionado.
}
$("#trHeader").append(htmltable);   
$("#tdp").attr("colspan", cantidadDias+2);/*encabezado de periodos*/
//contenidoPrenomina(idnomina,fechainicio,fechafin, editable,periodosfuturos, cantidadDias); 
$("#contenidop").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" ></i>');
$.ajax({
	async:false,
	type: 'POST',
	url: 'ajax.php?c=registroincidencias&f=empleadosNomina',
	data: {fechaIni:fechainicio,
		fechaFin:fechafin,
		cantDias:cantidadDias,
		idnomp: idnomina, 
		editable: editable},
		success: function(request) {
			$("#").val("");
			$("#hdnfechainicio").val(fechainicio);
			$("#hdnfechafin").val(fechafin);
			$("#contenidop").html(request);
			$("#hdneditable").val(editable); 
			$("#hdneditable").val(editable);  
			$("#periodo").html("PERIODO "+fechainicio+" - "+fechafin);
			$('#tablaincidencias').DataTable( {
				"language": {
					"url": "js/Spanish.json"
				}
			} );
		}
	});
});	
}

function elimina(menu,load,normal){
	if( $("#ediciondatos").val() != 0 ){
		$("#"+load).show();
		$("#"+normal).hide();
		if(menu == "perma" ){

			$.post("ajax.php?c=Sobrerecibo&f=eliminaMov",{
				idmovper:$("#ediciondatos").val(),
				opc:1
			},function(request){
				if( request == 1 ){
					alert("Datos eliminados. :(");
					permanentesEmpleado();

				}else{
					alert("Error en el proceso.");
				}
				$("#"+load).hide();
				$("#"+normal).show();
			});
		}else if(menu == "infonavit"){
			$.post("ajax.php?c=Sobrerecibo&f=eliminaMov",{
				idmovper:$("#ediciondatos").val(),
				opc:2
			},function(request){
				if( request == 1 ){
					alert("Datos eliminados. :(");
					infonavitEmpleado();

				}else{
					alert("Error en el proceso.");
				}
				$("#"+load).hide();
				$("#"+normal).show();
			});

		}else if(menu == "fonacot"){

			$.post("ajax.php?c=Sobrerecibo&f=eliminaMov",{
				idmovper:$("#ediciondatos").val(),
				opc:3
			},function(request){
				if( request == 1 ){
					alert("Datos eliminados. :(");
					fonacotEmpleado();

				}else{
					alert("Error en el proceso.");
				}
				$("#"+load).hide();
				$("#"+normal).show();
			});
		}
		else if(menu == "incapa"){

			$.post("ajax.php?c=Sobrerecibo&f=eliminaMov",{
				idmovper:$("#ediciondatos").val(),
				opc:4
			},function(request){
//alert(request);
if( request == 1 ){
	alert("Datos eliminados. :(");
	incapacidadEmpleado();
}

else if (request == 2) {
	alert("Existen dias aplicados en el periodo vacacional.");


}else{
	alert("Error en el proceso.");
}
$("#"+load).hide();
$("#"+normal).show();
});
		}
		else if(menu == "vaca"){
//alert($("#ediciondatos").val());
$.post("ajax.php?c=Sobrerecibo&f=eliminaMov",{
	idmovper:$("#ediciondatos").val(),
	opc:5
},function(request){
//alert(request);
if( request == 1 ){
	alert("Datos eliminados. :(");
	vacacionesEmpleado();

}
else if (request == 2) {
	alert("Existen dias aplicados en el periodo vacacional.");


}
else{
	alert("Error en el proceso.");
}
$("#"+load).hide();
$("#"+normal).show();
});
}

}else{
	alert("No ha selecionado ningún movimiento");
}
}

