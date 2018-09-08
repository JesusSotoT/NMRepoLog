/**
 * @author Carmen Gutierrez
 */
$(document).ready(function(){
	// $("#moneda,#cuenta,#pagador,#clasificador,#tipodocumento,#formapago,#bancodestino,#paguese2,#listaconcepto,#tipoPoliza,#listatraspaso,#listadoctraspaso").select2({
     // width : "150px"
    // });
    // $("#complemento,#beneficiarior,#pInicial,#pFinal,#ejercicior,#tipocambio").select2({
     // width : "150px"
    // });
    
    
 /// ACTUALIZAR CATALOGOS
$( "#loadtipoducumento" ).on( "click", function( e ) {
       // var $icon = $( this ).find( ".icon-refresh" ),
       $("#update1").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:1,
       	tipo:5
       },function(resp){
       		$("#tipodocumento").html(resp).selectpicker('refresh');;
       		$("#update1").removeClass("fa-spin");

       });
    }); 
  $( "#clasificadorload" ).on( "click", function( e ) {
       $("#update2").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:2,
       	tipocla:1//tipo 1 para los egresos
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
       	opc:5,//pagador
       	empleado:empl
       },function(resp){
       		$("#pagador").html(resp).selectpicker('refresh');;
       		$("#update4").removeClass("fa-spin");

       });
    }); 
    
 /// FIN ACTUALIZAR CATALOGOS
 
    
  $("#fechar").datepicker({
    		dateFormat: 'yy-mm-dd',
    		defaultDate:$("#fecha").val()
    		
    });  
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
		 fecha:$('#fechasaldo').val(),
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
function guardarEgreso(){
	var status = true;
	if(!$("#importe").val() || !$("#fecha").val() || ($("#pagador").val()==0 && $("#statustraspaso").val()==0)|| !$("#tipodocumento").val() || $("#cuenta").val()==0 || !$("#numcuentadestino").val() || $("#bancodestino").val()==0 || !$("#bancodestino").val()){
		alert("Faltan registros para Guardar el Documento");
		status = false;
	}else{
		var cuenta = $("#cuenta").val().split('//');
		var separa = $('#pagador').val().split('/');
		if(!validafecha($("#fecha").val())){
			alert("Seleccione una fecha acorde al periodo y ejercicio actual");
			status = false;
		}
		if($("#automatica").val()==1){
			if($("#statuscomision").val()==1){
				$("#tipoPoliza").val(3);
			}else{
				if( ($("#tipoPoliza").val()==0 || !$("#tipoPoliza").val()) && $("#statustraspaso").val()==0 && $("#statusanticipo").val()==0){
					alert("Seleccione el Tipo de Poliza");
					status= false;
				}
			}
		}
		if(cuenta[2]!=1){
			if(!$("#cambio").val()){
				alert("Seleccione o Introduzca el T.C.");
				status= false;
			}
		}
		if((separa[0]=="" || separa[0]==0 || separa[0]==-1) && ($("#paguese2").val()==0 && $("#statustraspaso").val()==0) && $("#statuscomision").val()==0){
			if( $("#vercuentasprv").is(":visible") ){
				if($("#paguese2").val()==0){
					alert("Seleccione cuenta para el Pagador");
					status= false;
				}
			}else{
				$("#vercuentasprv").show();
				$("#paguese2").val(0);
				alert("Seleccione cuenta para el Pagador");
				status= false;
			}
		
		}
		if($("#statustraspaso").val()==1){
				if( $("#listatraspaso").val()==0 ){
					alert("Seleccione la cuenta destino para el traspaso");
					status = false;
				}
			}
		if( $("#subclasificador").is(":visible")){

			var sumaporc=0;
				$(".porcentajesuma").each(function(){
					if(!$(this).val()){
						 alert("El porcentaje no puede ir nulo");
						 status= false;
					}else{
						sumaporc+=parseFloat($(this).val());
					}
				});
			if(sumaporc<100){
				alert("El porcentaje no cubre el 100%");
				status = false;
			}
		}
	if( status){
		$('#submit').click();
	}	
		// if($("#creapoli").is(':checked')==true){
     		// $("#automatica").val(1);
		// }
		// else{
		    // $("#automatica").val(0);
		// }
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
function sumaPorciento(){
	// if(suma<100){
		// suma=0;
			// $("#agregar").show();
		// $(".porcentajesuma").each(function(){
			// if($(this).val()){ 
				// suma+=parseFloat($(this).val());
			// }
		// });
	// }
	
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
		// valor = valor.replace(/,/g, '');
	// }
	// console.log(valor);
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
	var paguese2 = $('#paguese2').val().split('/');
	var cuentabeneficiario = 0;
	var beneficiario = separa[1];
	var tc = $("#cambio").val();
	if((separa[0]=="" || separa[0]==0 || separa[0]==-1 ) && $("#paguese2").val()==0 ){
		
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
						$.post('ajax.php?c=Cheques&f=crearpoliza',{
							idbancaria:idbancaria,
							concepto:$('#textarea').val(),
							fecha:$('#fecha').val(),
							importe:$('#importe').val().replace(/,/gi,''),
							cuentabeneficiario:cuentabeneficiario,
							beneficiario:beneficiario,
							numerocheque:'',
							referencia:$('#referencia').val(),
							cuentacontable:cuentacontable,
							idDocumento:$('#id').val(),
							bancodestino:$("#bancodestino").val(),
							cuentadestino:$("#numcuentadestino").val(),
							formapago:$("#formapago").val(),
							idbeneficiario:separa[2],
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
					$('#img').hide(); $("#poliza").show();
					window.parent.preguntar=false;
		 			window.parent.quitartab("tb0",0,"Polizas");
		 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+idpoli,'Polizas','',0);
					window.parent.preguntar=true;
					
				}
		}if(idpoli==0){
			$.post('ajax.php?c=Cheques&f=crearpoliza',{
				idbancaria:idbancaria,
				concepto:$('#textarea').val(),
				fecha:$('#fecha').val(),
				importe:$('#importe').val().replace(/,/gi,''),
				cuentabeneficiario:cuentabeneficiario,
				beneficiario:beneficiario,
				numerocheque:'',
				referencia:$('#referencia').val(),
				cuentacontable:cuentacontable,
				idDocumento:$('#id').val(),
				bancodestino:$("#bancodestino").val(),
				cuentadestino:$("#numcuentadestino").val(),
				formapago:$("#formapago").val(),
				idbeneficiario:separa[2],
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


function abreretencion(){
	// $("#divretencion").dialog(
	 // {
			 // autoOpen: false,
			 // width: 930,
			 // height: 470,
			 // modal: true,
			 // show:
			 // {
				// effect: "clip",
				// duration: 500
			 // },
				// hide:
			 // {
				// effect: "clip",
				// duration: 500
			 // }
		// });
	// $('#divretencion').dialog({position:['center',200]});
	// $('#divretencion').load("ajax.php?c=Cheques&f=verRetencion");
	// $('#divretencion').dialog('open');	
	$('#divretencion').on('shown.bs.modal', function () {
		$('#divretencion').load("ajax.php?c=Cheques&f=verRetencion");
 	 $('#divretencion').focus();
	});
}
function actualizalistapaguese2(){
	var separa =$("#cuenta").val().split('//');
	var ext = 0;
	if(separa[2]!=1){
		ext=1;
	}
	$("#progres").show();
	$.post('ajax.php?c=Cheques&f=proveedorMoneda',{
		ext:ext
	},function(resp){
		$("#paguese2").empty();
		$("#paguese2").html(resp);
		$("#paguese2").selectpicker('refresh');
		$("#progres").hide();
	});
}
function comision(){
	if($("#checkcomision").is(":checked")){
		$("#statuscomision").val(1);
		$("#tdpoliza").hide("slow");
		$("#tipoPoliza").val(3);
		$("#checktanticipo").attr("disabled",true);
	}else{
		$("#checktanticipo").attr("disabled",false);
		$("#statuscomision").val(0);
		if($("#automatica").val()==1){
			$("#tdpoliza").show("fold", 900);
		}
		
	}
}
