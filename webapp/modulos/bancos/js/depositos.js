/**
 * @author Carmen Gutierrez
 */
var contenido ="";
$(document).ready(function(){
	
	$( "#loadtipoducumento" ).on( "click", function( e ) {
       $("#update1").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:1,
       	tipo:4
       },function(resp){
       		$("#tipodocumento").html(resp).selectpicker('refresh');;
       		$("#update1").removeClass("fa-spin");

       });
    }); 
	$( "#loadcuentadepo" ).on( "click", function( e ) {
       $("#update3").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:3
       },function(resp){
       		$("#cuenta").html("<option value='t'>Traspasos</option>");
       		$("#cuenta").append(resp).selectpicker('refresh');
       		ListanoDepo();
       		$("#update3").removeClass("fa-spin");

       });
    });
	
	
	
contenido = $("#nodepo > tbody").html();
$(function() {
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
// 	
	 // $("#fechainicio").datepicker({
	 	// maxDate: 365,
	 	// dateFormat: 'yy-mm-dd',
        // numberOfMonths: 1,
        // onSelect: function(selected) {
          // $("#final").datepicker("option","minDate", selected);
        // }
    // });
    // $("#fechafin").datepicker({ 
    	// dateFormat: 'yy-mm-dd',
        // maxDate:365,
        // numberOfMonths: 1,
        // onSelect: function(selected) {
           // $("#inicial").datepicker("option","maxDate", selected);
        // }
    // });
    $( "#tabs" ).tabs();
    
  });
  // $("#cuenta,#formadeposito,#listanodepositado,#tipodocumento,#listaconcepto,#tipoPoliza,#tipocambio,#moneda").select2({
     // width : "150px"
    // });
    
    
    $("#buscar").keyup(function(){
		if( $(this).val() != "")
		{
			$("#nodepo tbody>tr").hide();
			$("#nodepo td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			$("#nodepo tbody>tr").show();
		}
	});
	
	
	$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});
 
 
});
function calculo(){
	var suma = 0;var bancaria=0;
	var cont =new Array();
	$('.listacheck:checked').each(
    function () {
    		
    		var parse = $(this).val().split('/');
    		if(parse[2]!=0){
    			cont.push(parse[2]);
    		}
     	suma += parseFloat(parse[0]);
    }
	);
	
	cont.reverse();
	for(var i=1;i<=cont.length-1;i++){
		
		if(cont[i]!=cont[0]){
			alert("La cuenta no es igual a las otras");
			$(".listacheck[data-value="+cont[i]+"]").prop('checked',false);
			var parse =$(".listacheck[data-value="+cont[i]+"]").val().split('/');
	
			suma -= parseFloat(parse[0] );
		
		}else{
			
		}
	}
	$("#importe").val(suma).number(true,2);
}
function conceptos(){
	$('#textarea').val("");
	//$('#area').hide();
	$('#buscarconcepto').hide();
	$('#ocultaconcepto').show();
	$('#selectconceptos').show();
}
function ocultalista(){
	$('#area').show();
	$('#buscarconcepto').show();
	$('#ocultaconcepto').hide();
	$('#selectconceptos').hide();
	$('#listaconcepto').val(0);
	 $("#listaconcepto").selectpicker('refresh');
}
function conceptext(){
	$('#textarea').val($('#listaconcepto').val());
	ocultalista();
} 
function guardaDeposito(auto){ 
	var cuenta = $("#cuenta").val().split('//');
	if(!$("#importe").val() || !$("#fecha").val() || $("#cuenta").val()==0 || !$("#tipodocumento").val() || $("#importe").val()<=0){
		alert("Faltan registros para Guardar el Documento");
		return false;
	}else{
		if(!validafecha($("#fecha").val())){
			alert("Seleccione una fecha acorde al periodo y ejercicio actual");
			status = false;
		}
		if($("#automatica").val()==1){
			if(( $("#tipoPoliza").val()==0 || !$("#tipoPoliza").val()) && $("#cuenta").val()!="t"){
				alert("Seleccione el Tipo de Poliza");
				return false;
			}
		}
		if(parseInt(cuenta[2])!=1){
			if(!$("#cambio").val()){
				alert("Seleccione o Introduzca el T.C.");
				return false;
			}else{
				if(auto==1){
					validaCuentaProyectados(1);//el uno es cuando es automatico
				}else{
					$("#datos").submit();
				}
			}
		}else{
			if(auto==1){
				validaCuentaProyectados(1);//el uno es cuando es automatico
			}else{
				$("#datos").submit();
			}
		}
		
	}
}
function ListanoDepo(){
	if($("#cuenta").val()!=0){
		$("#consul").show();
		var fecha = "";
		$("#importe").val(0);
		
		//$('.listacheck').prop('checked', false);
		//if($("#fecha").val()){ fecha = $("#fecha").val();}
		var separa = $("#cuenta").val().split('//');
		if($("#cuenta").val()=="t"){
			$("#tipoPoliza").selectpicker('hide');
			$("#tdpoliza").hide();
			 $("#tdmoneda").show();
			 if(!$("#moneda").val() || $("#moneda").val()==0){
			 	alert("Seleccione una moneda para los No depositados");
			 	$("#consul").hide();
			 	return false;
			 }else{
			 	listatipocambio();
				 $.post("ajax.php?c=Ingresos&f=ActualizaNodepostados",
					{	idbancaria:separa[0],
						moneda:$("#moneda").val(),
						traspaso:1
						
					},
					function (resp){
						$("#nodepo > tbody").html(resp);
						$("#consul").hide();
					});
			}
		}else{listatipocambio(); 
			if($("#automatica").val()==1){
				$("#tipoPoliza").selectpicker('show');
				$("#tdpoliza").show();
			}
			
			 $("#tdmoneda").hide();
			 $.post("ajax.php?c=Ingresos&f=ActualizaNodepostados",
				{	idbancaria:separa[0],
					moneda:separa[2]
					
					
				},
				function (resp){
					$("#nodepo > tbody").html(resp);
					$("#consul").hide();
				}); 
		}
		
		
	}else{
		$("#nodepo > tbody").html(contenido);
	}
}
function validaCuentaProyectados(auto){
	var idsproy = []; var envia=false;
	$('.listacheck:checked').each(function() {  
      	idsproy.push( $( this ).val() );
    });
    $("#load").show();
	$.post("ajax.php?c=Ingresos&f=validaProyectados",
	{ Nodepositado:idsproy },
	function (resp){
		if(resp!=0){
			$("#sincuenta").dialog({
				 autoOpen: false,
				 width: 500,
				 height: 310,
				 modal: true,
				 
				 show:
				 {
					effect: "clip",
					duration: 500
				 },
					hide:
				 {
					effect: "clip",
					duration: 500
				 },
				  buttons: 
				{"Guardar": function (){
					$("#load").show();
					var cuentaslista=[];
					var ids=[];
					$(".clasecuen").each(function() {  
						if($( this ).val() ){
							cuentaslista.push( $( this ).val() );
						}
				      	
				    });
				    $(".idsp").each(function() {  
				      	ids.push( $( this ).val() );
				    });
					
					$.post("ajax.php?c=Ingresos&f=asociaCuentasClientes",
					{ cuentasp:cuentaslista,
					  ids:ids}
					,function callback(){
						$("#load").hide();
						$('#sincuenta').dialog('close');	
						if(auto==1){
							validaCuentaProyectados(1);
						}else{
							validaCuentaProyectados(2);
						}
					});
				 }
				}
			});
			
		$('#sincuenta').dialog({position:['center',200]});
		$('#sincuenta').dialog('open');	
		$("#load").hide();
		$('#cuentaProyectados > tbody').html(resp);
      	$(".clasecuen").selectpicker('refresh');
    		
		}else {
			if(auto==1){
				
				$("#datos").submit();
			}else{
				//creaPoliza();
				$("#datos").submit();
			}
		}
	});
		
}
function fechadefauldepo(){
	var d = new Date();
	var diadefault = d.getDate();
	if($('#idperiodo').val()<10){ fec=0+$('#idperiodo').val(); }else{ fec=$('#idperiodo').val(); }
	  $.datepicker.setDefaults($.datepicker.regional['es-MX']);
   
   $("#fecha").datepicker({
	 	dateFormat: 'dd-mm-yy',
        numberOfMonths: 1,
         defaultDate:diadefault+'-'+fec+'-'+$("#ejercicio").val(),
         minDate: '01'+'-'+fec+'-'+$("#ejercicio").val(), 
        onSelect: function(selected) {
          $("#fechaaplicacion").datepicker("option","minDate", selected);
          listatipocambio();
        }

    });
    // $("div.ui-datepicker-header a.ui-datepicker-prev,div.ui-datepicker-header a.ui-datepicker-next").hide();

	   
    $("#fechaaplicacion").datepicker({
	 	dateFormat: 'dd-mm-yy',
        numberOfMonths: 1,
         defaultDate:diadefault+'-'+fec+'-'+$("#ejercicio").val(),
         minDate: '01'+'-'+fec+'-'+$("#ejercicio").val(), 
        onSelect: function(selected) {
           $("#fecha").datepicker("option","maxDate", selected);
        }
    });

}

function creaPoliza(){
	var cuentasepara = $('#cuenta').val().split('//');
	var idbancaria = $('#cuenta').val();
	var cuentacontable = cuentasepara[1];
	var tc = $("#cambio").val();

	var idsproy = []; var envia=false;
	$('.listacheck:checked').each(function() {  
      	idsproy.push( $( this ).val() );
    });
	$("#poliza").hide();$('#img').show();
	$.post('ajax.php?c=Cheques&f=verficaPoliza',{
		idDocumento:$('#id').val()
	},function(idpoli){
		if(idpoli!=0){
			if(confirm("La poliza ya fue creada desea reemplazarla?\nO seleccione cancelar para visualizarla")){
				$.post("ajax.php?c=Cheques&f=eliminaPoliza",{
					idpoli:idpoli
				},function(re){
					if(re==1){ 
						$.post('ajax.php?c=Ingresos&f=creaPolizaAutomaticaIngresosManual',{
							idbancaria:idbancaria,
							concepto:$('#textarea').val(),
							fecha:$('#fecha').val(),
							importe:$('#importe').val().replace(/,/gi,''),
							referencia:$('#referencia').val(),
							cuentacontable:cuentacontable,
							idDocumento:$('#id').val(),
							proyectados:idsproy,
							deposito:1,
							idBeneficiario:5,
							tc:tc
						},function(resp){
							if(resp!=0){
								alert("Poliza Generada Correctamente !");
								if(confirm('Desea ver la poliza?')){
									window.parent.preguntar=false;
									window.parent.quitartab('tb0',0,'Polizas');
									window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+resp,'Polizas','',0);
									window.parent.preguntar=true;
								}
								$('#img').hide();$("#load").hide();
							}else{
								alert('Error al generar Poliza');
								$('#img').hide();$("#load").hide();$("#poliza").show();
							}
							$("#poliza").show();
						});
					}else{
						alert("Error al reemplazar poliza");
						$('#img').hide();$("#poliza").show();$("#load").hide();
					}
				  });
				}else{
					window.parent.preguntar=false;
		 			window.parent.quitartab("tb0",0,"Polizas");
		 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+idpoli,'Polizas','',0);
					window.parent.preguntar=true;
					$('#img').hide();$("#poliza").show();
				}
		}if(idpoli==0){
			$.post('ajax.php?c=Ingresos&f=creaPolizaAutomaticaIngresosManual',{
				idbancaria:idbancaria,
				concepto:$('#textarea').val(),
				fecha:$('#fecha').val(),
				importe:$('#importe').val().replace(/,/gi,''),
				referencia:$('#referencia').val(),
				cuentacontable:cuentacontable,
				idDocumento:$('#id').val(),
				proyectados:idsproy,
				deposito:1,
				idBeneficiario:5,
				tc:tc
			},function(resp){
				if(resp!=0){
					alert("Poliza Generada Correctamente !");
					if(confirm('Desea ver la poliza?')){
						window.parent.preguntar=false;
						window.parent.quitartab('tb0',0,'Polizas');
						window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+resp,'Polizas','',0);
						window.parent.preguntar=true;
					}
					$('#img').hide();$("#load").hide();
				}else{
					alert('Error al generar Poliza');
					$('#img').hide();$("#load").hide();
				}
				$("#poliza").show();
			});
		}
	});	
}

