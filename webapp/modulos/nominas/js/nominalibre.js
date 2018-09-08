var trper = trded = trotro = inca = horaexcabe = horaex = '';
$(document).ready(function(){

	 $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	   $("#finicio").datepicker({
	   	dateFormat: 'yy-mm-dd',
	   	onSelect: function(selected) {
	   		$("#fin").val( selected);
	        $("#fin").datepicker("option","minDate", selected);
	        
	        $("#fpago").val( selected);
	        $("#fpago").datepicker("option","minDate", selected);
			empleadosPeriodo(selected);
	   		diastranscurridos();
	   	}
	
	  });
	    	$("#fin").datepicker({
	    		dateFormat: 'yy-mm-dd',
	    		onSelect: function(selected) {
	    			diastranscurridos();
	    			empleadosPeriodo(selected);
	    		}
	    });
	    $("#fpago").datepicker({
    			dateFormat: 'yy-mm-dd',
    		onSelect: function(selected) {
    			//diastranscurridos();
    		}
    });
  

	$(function(){
		// $("#agregar").on('click', function(){
// 			
			// $("#tabla").append("<div id=''>"+ trper +"</div>");
			// $(".percepciones").selectpicker("refresh");
// 			
// 			
		// });
		
		$(document).on("click",".eliminar",function(){//percepciones
			var parent = $(this).parents().get(2);
			$(parent).remove();
			mostrarelementos();
			totalsueldoypercepciones();
		});
		
		// $("#agregar2").on('click', function(){
			// $("#tabla2").append(trded);
			// $(".deducciones").selectpicker('refresh');
		// });
		$(document).on("click",".eliminar2",function(){//deducciones
			var parent = $(this).parents().get(2);
			$(parent).remove();
			totaldeduccionesGlobal();
		});
		// $("#agregar3").on('click', function(){
			// $("#tabla3").append(trotro);
			// $(".otros").selectpicker('refresh');
		// });
		$(document).on("click",".eliminar3",function(){//otros pagos
			var parent = $(this).parents().get(2);
			$(parent).remove();
			totalotrospagosglobal();
		});
		
		$(document).on("click",".eliminar4",function(){//incapacidades
			var parent = $(this).parents().get(2);
			$(parent).remove();
		});
		$(document).on("click",".eliminarh",function(){//horas extras
			var parent = $(this).parents().get(2);
			$(parent).remove();
		});
	
	});
}); 
 $(function(){
    $('#TimbraNomina').on('click', function() { 
    		$(this).button('loading');
    		$("#xmlnomina").submit();
    	}); 
});
function empleadosPeriodo(fecha){
	$.post("ajax.php?c=Nominalibre&f=empleadosNomina",{
		fecha: fecha,
		},function(resp){
			$("#empleado").html(resp).selectpicker("refresh");
			
		}
	);
}
function diastranscurridos(){ 
	var aFecha1 = $("#finicio").val().split('-'); 
	var aFecha2 = $("#fin").val().split('-'); 
	var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1],aFecha1[2]-1); 
	var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1],aFecha2[2]); 
	var dif = fFecha2 - fFecha1;
	var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
    	$("#dpago").val( dias);
}
function totalseparacion(){
	 var total = totaln =  0;
	$(".peexento").each(function (index) {
		if($(this).attr('data-value') == 1){ //las q tienen 1 son las separacion indemnizacion antiguedad
		
	
		    if (isNaN(parseFloat($(this).val()) )) {
		
		      total += 0;
		
		    } else {
		
		      total += parseFloat($(this).val());
		
		    }
		}
 	});
 	$(".pegravada").each(function (index) {
		if($(this).attr('data-value') == 1){ //las q tienen 1 son las separacion indemnizacion antiguedad
		
	
		    if (isNaN(parseFloat($(this).val()) )) {
		
		      totaln += 0;
		
		    } else {
		
		      totaln += parseFloat($(this).val());
		
		    }
		}
 	});
 	$("#importetotalseparacion").val( (total + totaln).toFixed(2) );
}
function totaljubilacion(){
	 var total = totaln =  0;
	$(".peexento").each(function (index) {
		if($(this).attr('data-value') == 2){ 
	
		    if (isNaN(parseFloat($(this).val()) )) {
		
		      total += 0;
		
		    } else {
		
		      total += parseFloat($(this).val());
		
		    }
		}
 	});
 	$(".pegravada").each(function (index) {
		if($(this).attr('data-value') == 2){
		    if (isNaN(parseFloat($(this).val()) )) {
		
		      totaln += 0;
		
		    } else {
		
		      totaln += parseFloat($(this).val());
		
		    }
		}
 	});
 	$("#importetotaljubiliacionetc").val( (total + totaln).toFixed(2) );
}

function percepcioneselect(clavesat,id){
	if(clavesat == "014"){
		$("#incapa").val(1);
		$("#incapacidad").show();
	}else{
		$("#incapa").val(0);
		$("#incapacidad").hide();
	}
	$("#clave"+id).val(clavesat);
	$("#concepto"+id).val($("#percepciones"+id+" option[value="+clavesat+"]").text());
	
	if(clavesat == "022" || clavesat == "023" ||  clavesat == "025" ){
		$("#pg"+id).attr("data-value","1");
		$("#pe"+id).attr("data-value","1");
		//$("#separacion").show();
	}else {
		if(clavesat == "039" || clavesat == "044" ){
		}else{
			$("#pg"+id).attr("data-value","0");
			$("#pe"+id).attr("data-value","0");
		}
		//$("#separacion").hide();
	}
	
	if(clavesat == "039" || clavesat == "044" ){
		$("#pg"+id).attr("data-value","2");
		$("#pe"+id).attr("data-value","2");
	}else{
		if(clavesat == "022" || clavesat == "023" ||  clavesat == "025" ){
		}else{
			$("#pg"+id).attr("data-value","0");
			$("#pe"+id).attr("data-value","0");
		}
	}
	if(clavesat == "019" ){
		$("#agregarhview"+id+",#divhorasextras"+id).show();
		
	}else{
		$("#agregarhview"+id+",#divhorasextras"+id).hide();
	}
	
	if(clavesat == "045" ){//AccionesOTitulos
		$("#AccionesOTitulos").show();
		
	}else{
		$("#AccionesOTitulos").hide();
	}
	
	//totalseparacion();
	mostrarelementos(id);
	totalsueldoypercepciones();
	subtotal();
}
function mostrarelementos(id){
	var array = new Array(); ;
	$(".percepciones").each(function (index) {
		if($(this).val()){
			array.push(parseInt($(this).val()));
		}
	});
	if((array.indexOf(22))>=0 || (array.indexOf(23))>=0 || (array.indexOf(25))>=0){//
		$("#separacion").show();
	}else{
		$("#separacion").hide();
	}
	if((array.indexOf(39))>=0 || (array.indexOf(44))>=0 ){//con q exista una jubilacion se mostrara el apartado
		$("#jubilacion").show();
		if((array.indexOf(39))>=0){
			$("#totalparcialidadesp,#montodiariop").hide();
			$("#unasolaexibicionp").show();
		}
		if((array.indexOf(44))>=0){
			$("#totalparcialidadesp,#montodiariop").show();
			$("#unasolaexibicionp").hide();
		}
	}else{
		$("#jubilacion").hide();
	}
	if((array.indexOf(39))>=0 && (array.indexOf(44))>=0 ){//si estan dos jubilaciones
		alert('No es posible agregar otra percepcion de "Jubilaciones, pensiones o\nhaberes de retiro" debido a que ya existe una.');
		if(id){
			$("#pg"+id).attr("data-value","0");
			$("#pe"+id).attr("data-value","0");
			$("#percepciones"+id).val("");
			$("#clave"+id).val("");
			$("#concepto"+id).val("");
		}
	}
	
	
	if((array.indexOf(14))>=0){//incapacidad
		$("#incapacidad").show();
	}else{
		$("#incapacidad").hide();
	}
	if((array.indexOf(45))>=0){//AccionesOTitulos
		$("#AccionesOTitulos").show();
	}else{
		$("#AccionesOTitulos").hide();
	}
}
/*percepciones
 * TotalSueldos Validar El valor de este atributo debe ser 
 * igual a la suma de los atributos ImporteGravado e ImporteExento 
 * donde la clave expresada en el atributo TipoPercepcion sea distinta
 *  de 022 Prima por Antigüedad, 023 Pagos por separación, 
 * 025 Indemnizaciones, 039 Jubilaciones, pensiones o haberes 
 * de retiro en una exhibición y 044 Jubilaciones, pensiones 
 * o haberes de retiro en parcialidades.
 */
function totalsueldoypercepciones(){
	
	 var total = totaln =  0;
	$(".peexento").each(function (index) {
		if($(this).attr('data-value') == 0){ //las q tienen 0 son las q no son ninguna de las condiciones antiguedad etc
		
	
		    if (isNaN(parseFloat($(this).val()) )) {
		
		      total += 0;
		
		    } else {
		
		      total += parseFloat($(this).val());
		
		    }
		}else{// los conceptos q no son antiguedad y demas
			if (isNaN(parseFloat($(this).val()) )) {
		
		      totaln += 0;
		
		    } else {
		
		      totaln += parseFloat($(this).val());
		
		    }
		}
 	});
 	
 	$("#pexenta").val( total + totaln );
 	
  var total2 = total2n = 0;

//  $(".pegravada").each(function() {
	$(".pegravada").each(function (index) {
		if($(this).attr('data-value') == 0){
		    if (isNaN(parseFloat($(this).val()))) {
		
		      total2 += 0;
		
		    } else {
		
		      total2 += parseFloat($(this).val());
		
		    }
		}else{
			if (isNaN(parseFloat($(this).val()))) {
		
		      total2n += 0;
		
		    } else {
		
		      total2n += parseFloat($(this).val());
		
		    }
		}
 	 });
 	var totalgra = (total2 + total2n ).toFixed(2);
 	var totalsuel = (total2 + total ).toFixed(2);
 	var totalperc = (total + total2 + totaln +total2n  ).toFixed(2);
  $("#pgravadas").val(totalgra);
  $("#percepxsueldos").val(totalsuel);
  $("#totalpercepciones").val(totalperc);
  totalseparacion();
  totaljubilacion();
  subtotal();
}

function completaDeduc(clavesat,id){
	$("#dclave"+id).val(clavesat);
	$("#dconcepto"+id).val( $("#deducciones"+id+" option[value="+clavesat+"]").text());
	
	if(clavesat == "002"){
		$("#dimporte"+id).attr("data-value","1");
		
	}else{
		$("#dimporte"+id).attr("data-value","0");
	}
	totaldeduccionesGlobal();
}
function totaldeduccionesGlobal(){
	var total2 = total2n = 0;
	$(".deduccionesglobal").each(function (index) {
		if($(this).attr('data-value') == 0){//las que tienen 0 son las q no son isr
		    if (isNaN(parseFloat($(this).val()))) {
		
		      total2 += 0;
		
		    } else {
		
		      total2 += parseFloat($(this).val());
		
		    }
		}else{
			if (isNaN(parseFloat($(this).val()))) {
		
		      total2n += 0;
		
		    } else {
		
		      total2n += parseFloat($(this).val());
		
		    }
		}
 	 });
 	 
 	 $("#otrasdedu").val(total2.toFixed(2));
 	 $("#impuestosretenidos").val(total2n.toFixed(2));
 	 $("#totaldeducciones").val(  (total2n + total2).toFixed(2) );
 	 $("#descuento").val( (total2n + total2).toFixed(2) );
 	 subtotal();
}




function totalotrospagosglobal(){
	var total = 0;

  $(".totalotrospagosglobal").each(function() {

    if (isNaN(parseFloat($(this).val()))) {

      total += 0;

    } else {

      total += parseFloat($(this).val());

    }

  });
  $("#totalotrospagos").val( total.toFixed(2));
  subtotal();
}
function conceptoOtros(clavesat,id){
	$("#oclave"+id).val(clavesat);
	$("#oconcepto"+id).val($("#otros"+id+" option[value="+clavesat+"]").text());
	if(clavesat == 002){
		$(".subsidiocausado"+id).show();
		$(".saldofavorotro"+id).hide();
	}
	else if(clavesat == 004){
		$(".subsidiocausado"+id).hide();
		$(".saldofavorotro"+id).show();
	}else{
		$(".subsidiocausado"+id).hide();
		$(".saldofavorotro"+id).hide();
	}
}


function solonumeriviris(e, field){
	 key = e.keyCode ? e.keyCode : e.which; 
        if (key == 8 || key ==9 || key ==37 || key ==39 || key ==127)  return true; 

        if ((key > 47 && key < 58)  || key == 46) {
          if (field.value == "" && key != 46) return true; 
          var  regexp = /^[0-9]+((\.)|(\.[0-9]{1,2}))?$/; 
          return (regexp.test(field.value + String.fromCharCode(key)));

        }

        return false;
}

function subtotal(){
	$("#subtotal").val( (parseFloat($("#totalotrospagos").val()) + parseFloat($("#totalpercepciones").val()) ).toFixed(2) );
	$("#detopagar").val( parseFloat( $("#subtotal").val() ) - parseFloat($("#descuento").val()) );
}

