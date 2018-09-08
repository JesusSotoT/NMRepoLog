/**
 * @author Carmen Gutierrez
 */

$(document).ready(function(){
	// $("#moneda,#cuenta,#pagador,#clasificador,#tipodocumento,#paguese2,#tipoPoliza,#listaconcepto").select2({
     // width : "150px"
    // });
     // $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    // $("#fecha").datepicker({
	 	// maxDate: 365,
	 	// dateFormat: 'yy-mm-dd',
        // numberOfMonths: 1
//         
    // });
    
  /// ACTUALIZAR CATALOGOS
   
   $( "#loadtipoducumento" ).on( "click", function( e ) {
       $("#update1").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:1,
       	tipo:2
       },function(resp){
       		$("#tipodocumento").html(resp).selectpicker('refresh');;
       		$("#update1").removeClass("fa-spin");

       });
    }); 
    
    // no depositado
    $( "#loadtipoducumentonodep" ).on( "click", function( e ) {
       $("#update1").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:1,
       	tipo:3
       },function(resp){
       		$("#tipodocumento").html(resp).selectpicker('refresh');;
       		$("#update1").removeClass("fa-spin");

       });
    }); 
    // fin no depositado
    
    $( "#clasificadorload" ).on( "click", function( e ) {
       $("#update2").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:2,
       	tipocla:2//tipo 1 para los ingresos
       },function(resp){
       		$("#clasificador").html(resp).selectpicker('refresh');;
       		$("#update2").removeClass("fa-spin");

       });
    });
    
    
    $( "#loadpagador" ).on( "click", function( e ) {
       $("#update4").addClass("fa-spin");
       var empl = 0;
       if( $("#listaempleado").is(":checked")){
       	 empl = 1;
       }
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:4,//pagador
       	empleado:empl
       },function(resp){
       		$("#pagador").html(resp).selectpicker('refresh');;
       		$("#update4").removeClass("fa-spin");

       });
    });  
    
 /// FIN ACTUALIZAR CATALOGOS
    
    $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fechasaldo").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
        
    });
    
   pagadorvalor = $("#pagador").val();
});
  function letra(){
	var separa = $("#moneda").val().split("/");
	$.post('ajax.php?c=Cheques&f=letra',
	{importe:$('#importe').val(),moneda:separa[1],simbolo:separa[2]},
	function (resp){
		$('#letra').html("( "+resp+" )");
	});
}

function saldofecha(){
	$("#fechasaldobanco").val($('#fechasaldo').val());
	var c = $('#cuenta').val().split('//');
	$.post('ajax.php?c=Cheques&f=saldocuenta',
		{idbancaria:c[0],
		 loca:$('#fechasaldo').val(),
		 cuenta:c[1]},
		function (resp){
			var separa = resp.split("//");
			if(resp<=0.00){
				//alert('¡No tiene fondos en la cuenta!');
			}
			$('#saldobancario').html(separa[1]);
			$("#saldo").html(separa[0]);
		});
}
function guardarIngreso(){
	var status = true;
	if(!$("#importe").val() || !$("#fecha").val() || $("#pagador").val()==0 || !$("#tipodocumento").val() ||  $("#cuenta").val()==0){
		alert("Faltan registros para Guardar el Documento");
		status= false;
	}else{
		if(!validafecha($("#fecha").val())){
			alert("Seleccione una fecha acorde al periodo y ejercicio actual");
			status = false;
		}
		var cuenta = $("#cuenta").val().split('//');
		var separa = $('#pagador').val().split('/');
		if($("#automatica").val()==1){
			if($("#statusinteres").val()==1){
				$("#tipoPoliza").val(3);
			}else{
				if($("#tipoPoliza").val()==0 || !$("#tipoPoliza").val()){
					alert("Seleccione el Tipo de Poliza");
					status= false;
				}
			}
		}
		if(cuenta[2]!=1){
			if(!$("#cambio").val()){
				alert("Seleccione o Introduzca el T.C.");
				status = false;
			}
		}
		if((separa[0]=="" || separa[0]==0 || separa[0]==-1) && $("#paguese2").val()==0 && $("#statusinteres").val()==0){
			if( $("#vercuentasprv").is(":visible") ){
				if($("#paguese2").val()==0){
					alert("Seleccione cuenta para el Pagador");
					status = false;
				}
			}else{
				$("#vercuentasprv").show();
				$("#paguese2").val(0);
				alert("Seleccione cuenta para el Pagador");
				status = false;
			}
		
		}
		if( $("#subclasificador").is(":visible")){
			var sumaporc=0;
			$(".porcentajesuma").each(function(){
				if(!$(this).val()){
					 alert("El porcentaje no puede ir nulo");
					 status = false;
				}else{
					sumaporc+=parseFloat($(this).val());
				}
			});
			if(sumaporc<100){
				alert("El porcentaje no cubre el 100%");
				status = false;
			}
		}
		if(status){
			$("#submit").click();
		}
	}
	
}
function guardarIngresoNoDep(){
	var status = true;
	if(!$("#importe").val() || !$("#fecha").val() || $("#pagador").val()==0 || !$("#tipodocumento").val() ){
		alert("Faltan registros para Guardar el Documento");
		status = false;
	}else{
		var cuenta = $("#cuenta").val().split('//');
		if( $("#cuenta").val() == 0){
			if( !$("#sincumoneda").is(":visible") ){
				alert("Seleccione la moneda para el Ingreso sin cuenta");
				$("#sincumoneda").show();
				status = false;
			}
			
		}
		if(cuenta[2]!=1){
			if(!$("#cambio").val()){
				alert("Seleccione o Introduzca el T.C.");
				status = false;
			}
		}
		if( $("#subclasificador").is(":visible")){
			var sumaporc=0;
			$(".porcentajesuma").each(function(){
					if(!$(this).val()){
						 alert("El porcentaje no puede ir nulo");
						 status =  false;
					}else{
						sumaporc+=parseFloat($(this).val());
					}
					
				});
			if(sumaporc<100){
				alert("El porcentaje no cubre el 100%");
				status = false;
			}
		}
		if(status){
			$('#submit').click();
		}
	}
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
function tecleado(valor,contador){
	var tecla=0;
		$(".porcentajesuma").each(function(){
			if($(this).val()){
				tecla+=parseFloat($(this).val());
			}
		
		});
		$("label[data-value="+contador+"]").html( (valor * ( $("#importe").val().replace(/,/gi,'') / 100)).toFixed(2) );
		$("label[data-value="+contador+"]").attr( "data-importe",(valor * ( $("#importe").val().replace(/,/gi,'') / 100)).toFixed(2) );

		if(tecla>100){
			alert("Sobrepasa el 100%");
		}
	
}
// function porcentajecal(valor){
 // if(valor.toString().indexOf(',') != -1){
// 
	// valor = valor.replace(/,/g, '');//con la expresion regular podemos escapar todas las comas del importe
// }
	// var porce = new Array();
	// var importes = new Array();
	// $(".porcentajesuma").each(function(){
		// if($(this).val()){
			 // porce.push($(this).val());
		// }
	// });
	// $(".impsuma").each(function(){
		// importes.push($(this).data("value"));
	// });
	// for(var i=0;i<porce.length;i++){
		// $("label[data-value="+importes[i]+"]").attr( "data-importe",(valor * ( porce[i] / 100)).toFixed(2) );
		// $("label[data-value="+importes[i]+"]").html( (valor * ( porce[i] / 100)).toFixed(2) );
// 	
	// }
// 	
// }
function creaPoliza(){
	var cuentasepara = $('#cuenta').val().split('//');
			var idbancaria = $('#cuenta').val();
			var cuentacontable = cuentasepara[1];
			var separa = $('#pagador').val().split('/');
			var cuentabeneficiario = 0;
			var beneficiario = separa[1];
			var tc = $("#cambio").val();

			if((separa[0]=="" || separa[0]==0 || separa[0]==-1) && $("#paguese2").val()==0 ){
				if( $("#vercuentasprv").is(":visible") ){
				}else{
					$("#vercuentasprv").show();
					alert("Seleccione cuenta para el Pagador");
					return false;
				}
				cuentabeneficiario = $('#paguese2').val();
			}else{
				cuentabeneficiario = separa[0]+'/1';
			}
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
							cuentabeneficiario:cuentabeneficiario,
							beneficiario:beneficiario,
							referencia:$('#referencia').val(),
							cuentacontable:cuentacontable,
							idDocumento:$('#id').val(),
							idBeneficiario:separa[2],
							deposito:0,
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
								$('#img').hide();
							}else{
								alert('Error al generar Poliza');
								$('#img').hide();
							}
							$("#poliza").show();
						});
					}else{
						alert("Error al reemplazar poliza");
						$('#img').hide();$("#poliza").show();
					}
				  });
				}else{
					window.parent.preguntar=false;
		 			window.parent.quitartab("tb0",0,"Polizas");
		 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+idpoli,'Polizas','',0);
					window.parent.preguntar=true;
					$('#img').hide();$("#poliza").show();
				}
		}else if(idpoli==0){
			$.post('ajax.php?c=Ingresos&f=creaPolizaAutomaticaIngresosManual',{
				idbancaria:idbancaria,
				concepto:$('#textarea').val(),
				fecha:$('#fecha').val(),
				importe:$('#importe').val().replace(/,/gi,''),
				cuentabeneficiario:cuentabeneficiario,
				beneficiario:beneficiario,
				referencia:$('#referencia').val(),
				cuentacontable:cuentacontable,
				idDocumento:$('#id').val(),
				idBeneficiario:separa[2],
				deposito:0,
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
					$('#img').hide();
				}else{
					alert('Error al generar Poliza');
					$('#img').hide();
				}
				$("#poliza").show();
			});
		}
	});	
}
function validaCuenta(){
var cuentasepara = $('#cuenta').val().split('//');
var parsea = $('#pagador').val().split('/');

	if($("#appministra").val()==1){
		if ( $(".trappministra").is(":visible")) {
			if(confirm("Los pagos de CXC que haya generado con el antiguo pagador se eliminaran desea continuar?")){
				$("#importe").val(0);
				totalcuentasPrevias = 0;
				$(".trappministra").hide("slow");
				$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val());
				pagadorvalor = $('#pagador').val();
			}else{
				$('#pagador').val(pagadorvalor).selectpicker("refresh");
			}
		}else{
			//$("#importe").val(0);
			//totalcuentasPrevias = 0;
			$(".trappministra").hide("slow");
			$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val() );
			pagadorvalor = $('#pagador').val();
		}
		
	}
		if($('#pagador').val()!=0){
			if (parsea[0]>0 || parsea[3]==4) {//proveedores en general
				$('#vercuentasprv').hide();
			}else{
				$('#vercuentasprv').show();
			}
			if(parsea[2]==5){
				//cxc(0);//tipo 0 es para clientes
			}
		}
	
			// $.post("ajax.php?c=Cheques&f=cambiaBeneficiario",{
				// idDoc:$('#id').val(),
				// beneficiario:parsea[1]
			// },function(request){
				// if(request==1){
					// $("#importe").val(0);
					// totalcuentasPrevias = 0;
					// $(".trappministra").hide("slow");
				// }
			// });
			
		
}
function pagadorCuenta(){
	$.post('ajax.php?c=Cheques&f=proveedorMoneda',{
		idbancaria:$("#cuenta").val()
	},function(resp){
		$("paguese2").empty();
		$("#paguese2").html(resp);
		$("#paguese2").selectpicker('refresh');
	});
	if( $("#cuenta").val() == 0){
		$("#sincumoneda").show();
	}
}

function interes(){
	if($("#checkinteres").is(":checked")){
		$("#statusinteres").val(1);
		$("#tipoPoliza").val(3);
		$("#tdpoliza,#vercuentasprv").hide();
	}else{
		$("#statusinteres").val(0);
		if($("#automatica").val()==1){
			$("#tdpoliza").show();
		}
	}
}
function ingrePendientescxc(){
	var parsea = $('#pagador').val().split('/');
	var cuentasepara = $('#cuenta').val().split('//');
	if($("#appministra").val()==1){
		if ( $(".trappministra").is(":visible")) {	
			if(confirm("Los pagos de CXC que haya generado con el antiguo pagador se eliminaran desea continuar?")){
				pagadorvalor = $('#pagador').val();
				$("#importe").val(0);
				totalcuentasPrevias = 0;
				$(".trappministra").hide("slow");
				$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val()+"&doc=3" );

			}else{
				$('#pagador').val(pagadorvalor).selectpicker("refresh");
			}
		}else{
			pagadorvalor = $('#pagador').val();
			//$("#importe").val(0);
			//totalcuentasPrevias = 0;
			$(".trappministra").hide("slow");
			$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val()+"&doc=3" );
		}
	}
}
