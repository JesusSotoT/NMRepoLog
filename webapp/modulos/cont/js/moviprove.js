/**
 * @author karmen
 */
var fecha;
$(document).on("keydown",function(e){
	var code = (e.keyCode ? e.keyCode : e.which);
	 if (code ==121 ) { $('#ejecutar').click(); }
});
$(document).ready(function() {
	 $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	$("#inicio2").datepicker({
	 	maxDate: 0,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
    });
	
	 $("#inicio").datepicker({
	 	maxDate: 0,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fin").datepicker("option","minDate", selected);
        }
    });
    
    $("#fin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:365,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#inicio").datepicker("option","maxDate", selected);
        }
    });
			$('#inicio').attr("disabled",true);
		 	$('#fin').attr("disabled",true);
		
	$.post("ajax.php?c=RepPeriodoAcreditamiento&f=ejercicio", {
		}, function(respues) {
			$('#ejercicio').html(respues);
		}); 

  });	
  
  
   
function cambiarPestana(pestannas,pestanna) {
    
    // Obtiene los elementos con los identificadores pasados.
    pestanna = document.getElementById(pestanna.id);
    listaPestannas = document.getElementById(pestannas.id);
    
    // Obtiene las divisiones que tienen el contenido de las pestañas.
    cpestanna = document.getElementById('c'+pestanna.id);
    listacPestannas = document.getElementById('contenido'+pestannas.id);
    
    i=0;
    // Recorre la lista ocultando todas las pestañas y restaurando el fondo
    // y el padding de las pestañas.
    while (typeof listacPestannas.getElementsByTagName('div')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByTagName('div')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
        });
        i += 1;
    }
 
    $(document).ready(function(){
        // Muestra el contenido de la pestaña pasada como parametro a la funcion,
        // cambia el color de la pestaña y aumenta el padding para que tape el  
        // borde superior del contenido que esta juesto debajo y se vea de este
        // modo que esta seleccionada.
        $(cpestanna).css('display','');
        $(pestanna).css('background','dimgray');
        $(pestanna).css('padding-bottom','2px');
    });
 
}
///////////////////////////////////////////////////////////////////////////////
 function funcion()
	{ 	   	
		 if($('#acreditamiento').is(':checked')){	
		 	$('#delperiodo').attr("disabled",false);
		 	$('#alperiodo').attr("disabled",false);
		 	$('#ejercicio').attr('disabled',false);
		 	$('#inicio').attr("disabled",true);
		 	$('#fin').attr("disabled",true);
		 	$('#cred').val(1);
		 	
		 	}else{
		 		$('#delperiodo').attr("disabled",true);
		 		$('#alperiodo').attr("disabled",true);
		 		$('#ejercicio').attr('disabled',true);
		 		$('#inicio').attr("disabled",false);
		 		$('#fin').attr("disabled",false);
		 		$('#cred').val(0);
		 	}
  }	
  function muestra(){
  	if($('input:radio[value="2"]').is(':checked')){
  		$('#algunos').val('');
  		$('#algunos').show();
  		$('#label').show();
  	
  	}else{
  		$('#algunos').hide();
  		$('#label').hide();
  	}
  }
  (function(a){
  	a.fn.validCampo=function(b){a(this).on({keypress:function(a){var c=a.which,d=a.keyCode,e=String.fromCharCode(c).toLowerCase(),f=b;(-1!=f.indexOf(e)||9==d||37!=c&&37==d||39==d&&39!=c||8==d||46==d&&46!=c)&&161!=c||a.preventDefault()}})}})(jQuery);
  $(function(){   
      $('#algunos').validCampo('0123456789,-');    
   });

 function reporte(){
 	//$(document).ready(function(){
 		
 	$('#detallado').hide();
 	var aplica=0;
 	var algunos=$('#algunos').val();
 	var check=$('#cred').val(); 
 	var ejercicio=$('#ejercicio').val();
 	var delperiodo=$('#delperiodo').val();
 	var alperiodo=$('#alperiodo').val();
 	var inicio=$('#inicio').val();
 	var fin=$('#fin').val();
 	var fechaimprecion=$('#inicio2').val();
 	var excel=0;
	fecha=	fechaimprecion;	
	var proveedores=$('input:radio[name="prove"]:checked').val();
 	if(proveedores==2){ 
 		if($('#algunos').val()==''){
 			alert('Introduzca los Proveedores a Visualizar.'); return false; 
 		}else{
 			proveedores=algunos;
 		} 
 	}else{ proveedores='';}
	if($('#aplica').is(':checked')){
		aplica=1;
	}else{ 
		aplica=0;
	}
 	
 	//si se va a Considerar periodo de acreditamiento.
 		
 			
 				
 				if(check==1){
		 			//window.open('ajax.php?c=RepPeriodoAcreditamiento&f=verdeta&ejercicio='+ejercicio+'&delperiodo='+delperiodo+'&alperiodo='+alperiodo+'&proveedores'+proveedores+'&fechaimprecion='+fechaimprecion+'&excel=1');
						// if($('#excel').is(':checked')){
							 // window.open('ajax.php?c=RepPeriodoAcreditamiento&f=verdeta&ejercicio='+ejercicio+'&delperiodo='+delperiodo+'&alperiodo='+alperiodo+'&proveedores='+proveedores+'&fechaimprecion='+fechaimprecion);
// 		
						// }else{
							$('#confi').hide();
						 	$('#detallado').hide();
						 	$('#acredita').show();

							$('#acredita').load('ajax.php?c=RepPeriodoAcreditamiento&f=verdeta&ejercicio='+ejercicio+'&delperiodo='+delperiodo+'&alperiodo='+alperiodo+'&proveedores='+proveedores+'&fechaimprecion='+fechaimprecion+'&aplica='+aplica);
						//}
			
							// $.post("ajax.php?c=RepPeriodoAcreditamiento&f=reporteacredita",{
									// ejercicio:ejercicio,
									// delperiodo:delperiodo,
									// alperiodo:alperiodo,
									// proveedores:proveedores,
									// fechaimprecion:fechaimprecion
									// },
						 		 		// function(respues) {
						 		 			// var r=respues.split('//');
						 		 			// var r2=respues.split('->');
						 		 			// var r3=r2[1].split('<-');
						 		 			// $('#periodo').text(mes(delperiodo,alperiodo)+' '+r[0]);
						 		 			// $('#empresa').html(r3[0]);
						 		 			// $('#fech').html(r3[1]); 
						 		 			// $('#datos tbody').prepend(respues);
						 		 			// //$('#estilo').append(r3[2]);
						 		 		// });	
						
					}else{
						// if($('#excel').is(':checked')){
							 // window.open('ajax.php?c=RepPeriodoAcreditamiento&f=verdeta&ejercicio='+ejercicio+'&inico='+inicio+'&fin='+fin+'&proveedores='+proveedores+'&fechaimprecion='+fechaimprecion);
// 		
						// }else{
							$('#confi').hide();
						 	$('#detallado').hide();
						 	$('#acredita').show();

							$('#acredita').load('ajax.php?c=RepPeriodoAcreditamiento&f=verdeta&ejercicio='+ejercicio+'&inicio='+inicio+'&fin='+fin+'&proveedores='+proveedores+'&fechaimprecion='+fechaimprecion+'&aplica='+aplica);
						
						//}
					}
// 
// alert(check);
// 
// alert(ejercicio);
// alert(delperiodo);
// alert(alperiodo);
// alert(inicio);
// alert(fin);


 	
 	
 }
 
 
 function regreso(){
 	$(document).ready(function(){
 		$('#confi').show();
 		$('#detallado').hide();
 	$('#acredita').hide();
 		//$('#confi').load('views/fiscal/diot/MovimientosConProveedores.php');
 	});
 }
  function regreso2(){
 	$(document).ready(function(){
 		$('#detallado').hide();
 	$('#acredita').show();
 	});
 }
 
 function vistalink(idpoliza,idProveedor){
 	
 	$(document).ready(function(){
 		//$('#acredita').show();
 		//$('#confi').hide();
 	//$('#detallado').show();
 	////var url='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza='+idpoliza+'&idProveedor='+idProveedor;
 	////window.open(url,'ventana','width=700,heigth=700,left=200,top=100');
 	window.open('ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza='+idpoliza+'&idProveedor='+idProveedor+'&fecha='+fecha);
 	 });
 	 
 	  // $.post("ajax.php?c=RepPeriodoAcreditamiento&f=detallado", {
   		// idpoliza:idpoliza,
   		// idProveedor:idProveedor
		 // }, function(respues) {
		 	// //alert(respues);
			 // $('#detallado tbody').html(respues);
		 // });
 
 }
  function mes(alperiodo,delperiodo){
 	var p2,p1;
	 		 			    if(delperiodo==1 ){ p1='Enero'; }
	 		 			    if(delperiodo==2){ p1='Febrero'; }	
	 		 				if(delperiodo==3){ p1='Marzo';  }
	 		 			    if(delperiodo==4){ p1='Abril';}
	 		 			    if(delperiodo==5){ p1='Mayo';}
	 		 			    if(delperiodo==6){ p1='Junio';}
	 		 			    if(delperiodo==7){ p1='Julio';}
	 		 			    if(delperiodo==8){ p1='Agosto'; }
	 		 			    if(delperiodo==9){ p1='Septiembre'; }
	 		 			    if(delperiodo==10){ p1='Octubre';  }
	 		 			    if(delperiodo==11){ p1='Noviembre';  }
	 		 			    if(delperiodo==12){ p1='Diciembre';}
	 		 			    
	 		 			    if(alperiodo==1){  p2='Enero';}
	 		 			    if(alperiodo==2){  p2='Febrero';}	
	 		 				if(alperiodo==3){  p2='Marzo';}
	 		 			    if(alperiodo==4){  p2='Abril';}
	 		 			    if(alperiodo==5){ p2='Mayo';}
	 		 			    if(alperiodo==6){ p2='Junio';}
	 		 			    if(alperiodo==7){  p2='Julio';}
	 		 			    if(alperiodo==8){  p2='Agosto';}
	 		 			    if(alperiodo==9){  p2='Septiembre';}
	 		 			    if(alperiodo==10){ p2='Octubre';}
	 		 			    if(alperiodo==11){ p2='Noviembre';}
	 		 			    if(alperiodo==12){ p2='Diciembre';}
 		
 	return 'Periodo de Acreditamiento de '+p2+' a '+p1;	
}
