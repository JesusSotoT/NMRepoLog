/**
 * @author Carmen Gutierrez
 */
var rangoninicial = 0;
var rangofinal = 0;
var actual = 0;
var 	fechadefaultcxp;

$(document).ready(function(){
	// $("#tipo,#cuenta,#pagador,#paguese2,#categoria,#moneda,#listaconcepto,#clasificador,#creados,#tipodocumento,#bancodestino,#formapago,#tipoPoliza,#listatraspaso,#listadoctraspaso").select2({
     // width : "150px"
    // });
   
    
   // $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    // $("#fecha").datepicker({
	 	// maxDate: 365,
	 	// dateFormat: 'yy-mm-dd',
        // numberOfMonths: 1
//         
    // });
    
    // actualizacion de catalogos
    $( "#loadtipoducumento" ).on( "click", function( e ) {
       // var $icon = $( this ).find( ".icon-refresh" ),
       $("#update1").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:1,
       	tipo:1
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
    // fin actualizacion de catalogos
    
    
    
    
    
    $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fechasaldo").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
        
    });
     
  $(function() {
    $( "#tabs" ).tabs();
  });
});
function letra(){
	//cambiar eso de moneda le puse fijo pero debe ser del tipo q maneja la cuenta
	$.post('ajax.php?c=Cheques&f=letra',
	{importe:$('#importe').val(),moneda:"Pesos",simbolo:"M.N."},
	function (resp){
		$('#letra').html("( "+resp+" )");
	});
}
function buscanumerocheque(){
	
	//limpiar(); 
	if($('#cuenta').val()==0){
		$("#creados").empty();$("#letra").empty();
		return false;
	}
	var c = $('#cuenta').val().split('//');
	$.post('ajax.php?c=Cheques&f=buscanumerocheque',
		{idbancaria:c[0]},
		function (respuesta){
			var resp = respuesta.split('/');
		 	$("#finchequera").html(resp[1]);
			$("#numero").val(resp[0]);
			//validarangonumero();
		});
	if(c[3]==-1){//si la cuenta tiene numero automatico nose edita el numero
		$("#numero").attr("readonly",true);
		$("#numactual").hide();
	}else{
		$("#numero").attr("readonly",false);
		$("#numactual").show();
	}
   listatipocambio();
		saldofecha();
}
function saldofecha(){
	var c = $('#cuenta').val().split('//');
	var fecha = $('#fecha').val();
	if(!$("#fecha").val()){
		var d = new Date();
		var diadefault = d.getDate();
		if($('#idperiodo').val()<10){ fec=0+$('#idperiodo').val(); }else{ fec=$('#idperiodo').val(); }
		fecha = $("#ejercicio").val()+'-'+fec+'-'+diadefault;
	}
	$.post('ajax.php?c=Cheques&f=saldocuenta',
		{idbancaria:c[0],
		 fecha:fecha,
		 cuenta:c[1]},
		function (resp){
			if(resp<=0){
				alert('¡No tiene fondos en la cuenta!');
			}
			//$('#saldobancario').html(separa[1]);
			$("#saldo").html(resp).number(true,2);
		});
}
function verificacuenta(){
	var parsea = $('#paguese').val().split('/');
		if($('#paguese').val()!=0){
			if (parsea[0]>0) {//proveedores en general
				$('#vercuentasprv').hide();
			}else{
				$('#vercuentasprv').show();
			}
		}
}
function validarangonumero(){//nose usa
	var numeroactual = $('#numero').val();
	var c = $('#cuenta').val().split('//');
	$.post('ajax.php?c=Cheques&f=validarangonumero',{
		idbancaria:c[0]
	},function (resp){
		var separa = resp.split('//');
		if(separa[0]==1){//si es 1 esq es de rango
			rangoninicial = separa[1];
			rangofinal = separa[2];
			actual = separa[3];
		}else{
			actual = separa[1];
			//$('#numero').attr('readonly',true);
		}
		
	});
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
function cancelar(){
	var cuentasepara = $('#cuenta').val().split('//');
	var idbancaria = cuentasepara[0];
	var cuentacontable = cuentasepara[1];
	if($('#pagador').is(":visible")){
		var separa = $('#pagador').val().split('/');
		var cuentabeneficiario = 0;
		var beneficiario = separa[1];
		if(separa[0]=="" || separa[0]==0 || separa[0]==-1){
			cuentabeneficiario = $('#paguese2').val();
		}else{
			cuentabeneficiario = separa[0];
		}
	}
	if( $('#cancelado').is(":visible") ){
		alert("No puede quitar la marca de cancelacion");
		// if(confirm('Esta seguro de quitar la marca de cancelacion?')){
			// $.post('ajax.php?c=Cheques&f=cancela',{
				// opc:1,
				// numerocheque:$('#numero').val(),
				// idbancaria:idbancaria
				// },function (resp){
					// if(resp){
						// $('#cancelado').hide();
// 						
					// }else{
						// alert("Error al activar Documento intente de nuevo.");
					// }
// 					
				// });
// 			
		// }
	}else{ 
		if($("input[name='forma']:checked").val()!=1){
			alert('El Documento debe estar impreso');
		}else{
			if(!$("#cobrado").is(":checked")){
				if(confirm('Esta seguro de cancelar el cheque?')){
					
					$.post('ajax.php?c=Cheques&f=cancela',{
						opc:2,
						idDocumento:$("#id").val()
					},function (resp){
						if(resp==1){
						
						$.post('ajax.php?c=Cheques&f=verficaPoliza',{
							idDocumento:$('#id').val()
						},function(resp){
							if(resp!=0){
							
							 	$.post("ajax.php?c=Cheques&f=inactivapoliza",{
								idDocumento:$('#id').val(),
								},function (r){ 
									if(r==1){
										alert("Documento y Poliza Cancelados");
										$("#guardarimg").hide();
										$('#cancelado').show();
									}else{
										alert("Error al Cancelar poliza intente de nuevo");
										
									}
								});
							}else{
								alert("Documento Cancelado.");
								$("#guardarimg").hide();
								$('#cancelado').show();
							}	 
						});
						if($("#appministra").val()==1){
							$.post("ajax.php?c=Cheques&f=cambiaBeneficiario",{
								idDoc:$('#id').val(),
							});
						}

					}
					else{
						alert("Error al cancelar Documento intente de nuevo.");
						$('#cancelado').hide();
						$("#guardarimg").show();
					}
					
				});
				
			}
		  }//visible
		  else{
		  	alert("Los cheques cobrados no se pueden Cancelar");
		  }
		}//if visible
	}
}

function devolver(){
	$("#img").show();
	$("#devo").hide();
	
	var d = new Date();
	var diadefault = d.getDate();
	if($('#idperiodo').val()<10){ fec=0+$('#idperiodo').val(); }else{ fec=$('#idperiodo').val(); }
	var fecha = diadefault+'-'+fec+'-'+$("#ejercicio").val();//.attr('readonly','readonly');
	
	var cuentasepara = $('#cuenta').val().split('//');
	var idbancaria = cuentasepara[0];
	var cuentacontable = cuentasepara[1];
	if($('#pagador').is(":visible")){
		var separa = $('#pagador').val().split('/');
		var cuentabeneficiario = 0;
		var beneficiario = separa[1];
		if(separa[0]=="" || separa[0]==0 || separa[0]==-1){
			cuentabeneficiario = $('#paguese2').val();
		}else{
			cuentabeneficiario = separa[0];
		}
	}
	$.post('ajax.php?c=Cheques&f=numeroDevoluciones',{
		idDocumento:$('#id').val()
	},function(numDevolucion){
	if(numDevolucion==3){
		$("#img").hide();
		$("#devo").show();
		alert("Ha excedido el numero de devoluciones permitidas.");
		return false;
		
	}else{
		
	if( $('#devuelto').is(":visible") ){
		if(confirm('Esta seguro de quitar la marca de DEVOLUCION?')){
			//$("#guardarimg").show();
	 		$.post('ajax.php?c=Cheques&f=devolverdocumento',{
	 			status:1,
				idDocumento:$('#id').val()
				},function (resp){
					if(resp){
						//desactiva el mov inverso
						if($("#acontia").val()==1){
							$.post('ajax.php?c=Cheques&f=verficaPolizaDevolucion',{
							idDocumento:$('#id').val()
							},function(idpoli){
								if(idpoli!=0){
									creaPolizaDevolucion(resp);// si quita la marca de devolucion es forzoso hacer la poliza?
								}else{
									
									$("#img").hide();
									$('#devuelto').hide();	
									$('#guardarimg,#devo').show();
									
									
								}
							});
						}else{
							$('#devuelto').hide();	
							$('#guardarimg').show();
						}
						
						if($("#appministra").val()==1){
							$.post("ajax.php?c=Cheques&f=devolucionescxp",{
								opc:1,
								idDoc:$('#id').val()
							});
						}
						//$('#devuelto').hide();	
						//$('#guardarimg').show();
						//$("#poliza").attr("onclick","return creaPoliza()");
					}else{
						$("#img").hide();
						$("#devo").show();
						alert("Error al activar Documento intente de nuevo.");
						$('#devuelto').show();$('#guardarimg').hide();
					}
				});
		}else{
			$("#img").hide();
			$("#devo").show();
		}
	}else{
		if($("input[name='forma']:checked").val()!=1){
			alert('El Documento debe estar impreso');
			$("#img").hide();
			$("#devo").show();
		}else{
			if(!$("#cobrado").is(":checked")){
				if( $('#cancelado').is(":visible") ){
					alert("No puedes devolver un documento cancelado!");
					$("#img").hide();
					$("#devo").show();
				}else{
				if(confirm('Esta seguro que desea devolver el Documento?')){
					//$("#imgproceso").show();
					
							$.post('ajax.php?c=Cheques&f=verficaPolizaDevolucion',{
							idDocumento:$('#id').val()
							},function(idpoli){
								if(idpoli!=0){
									if($("#automatica").val()==0){
								 		if(confirm("Desea realizar un movimiento inverso en la poliza")){
								 			var fechadefault = prompt("Introduzca la fecha de devolucion",fecha);
											if(fechadefault){
											fechadefaultcxp=fechadefault;
												if(confirm("La fecha de devolucion es correcta?\n"+fechadefault)){
													$.post('ajax.php?c=Cheques&f=movInverso',{
														idDocumento:$('#id').val(),
														idbancaria:idbancaria,
														concepto:$('#textarea').val(),
														importe:$('#importe').val(),
														cuentabeneficiario:cuentabeneficiario,
														beneficiario:beneficiario,
														numerocheque:$('#numero').val(),
														referencia:$('#referencia').val(),
														cuentacontable:cuentacontable,
														idbeneficiario:separa[2],
														tc:$("#cambio").val(),
														idpoli:idpoli,
														fecha:fechadefault,
														numDevolucion:numDevolucion
													},function(resp){
														if(resp>0){
															devolucion(resp,0);
														}else{
															alert("Error al crear intente de nuevo.");
															
														}
														$("#img").hide();
														$("#devo").show();
													});
												}else{
													$("#img").hide();
													$("#devo").show();
												}
											}else{
												$("#img").hide();
												$("#devo").show();
											}
										 }else{
										 	
										 	
										 	// $.post('ajax.php?c=Cheques&f=almacenaInversoSinPoliza',{
												// idDocumento:$('#id').val(),
												// numDevolucion:numDevolucion
											// },function(){
												devolucion(0,numDevolucion); 
												$("#img").hide();
												$("#devo").show();
											// });
										 }
									}else{//si es automatica
										var fechadefault = prompt("Introduzca la fecha de devolucion",fecha);
										if(fechadefault){
											fechadefaultcxp=fechadefault;
											if(confirm("La fecha de devolucion es correcta?\n"+fechadefault)){
												$.post('ajax.php?c=Cheques&f=movInverso',{
													idDocumento:$('#id').val(),
													idbancaria:idbancaria,
													concepto:$('#textarea').val(),
													importe:$('#importe').val(),
													cuentabeneficiario:cuentabeneficiario,
													beneficiario:beneficiario,
													numerocheque:$('#numero').val(),
													referencia:$('#referencia').val(),
													cuentacontable:cuentacontable,
													idbeneficiario:separa[2],
													tc:$("#cambio").val(),
													idpoli:idpoli,
													fecha:fechadefault,
													numDevolucion:numDevolucion
												},function(resp){
													if(resp>0){
														devolucion(resp,0);
													}else{
														alert("Error al crear intente de nuevo.");
													}
													$("#img").hide();
													$("#devo").show();
												});
											}else{
												$("#img").hide();
												$("#devo").show();
											}
										}else{
												$("#img").hide();
												$("#devo").show();
											}
									}
									
									
								}//if si existe poliza
								else{
									var fechadefault = prompt("Introduzca la fecha de devolucion",fecha);
									if(fechadefault){
										if(confirm("La fecha de devolucion es correcta?\n"+fechadefault)){
											fechadefaultcxp=fechadefault;
											// $.post('ajax.php?c=Cheques&f=almacenaInversoSinPoliza',{
												// idDocumento:$('#id').val(),
												// numDevolucion:numDevolucion
											// },function(){
												devolucion(0,numDevolucion);
												$("#img").hide();
												$("#devo").show();
											// });
										}else{
											$("#img").hide();
											$("#devo").show();
										}
									}
								}
									
							});
					
				}//Esta seguro que desea devolver el Documento?
				else{
					$("#img").hide();
					$("#devo").show();
				}
			}//
		  }//if de cobrado
		  else{
		  	alert("Los cheques cobrados no se pueden Devolver");
		  	$('#devuelto').hide();
		  	$("#img").hide();
			$("#devo").show();
		  }
		}//del if visible
	}
}//else de 3 devueltos

});


}
function devolucion(idpoliza,numDevolucion){
	$.post('ajax.php?c=Cheques&f=devolverdocumento',{
	status:4,
	idDocumento:$('#id').val(),
	fecha:fechadefaultcxp
	},function (resp){
		if(resp){
			if(numDevolucion!=0 || !numDevolucion){
				$.post('ajax.php?c=Cheques&f=almacenaInversoSinPoliza',{
					idDocumento:$('#id').val(),
					numDevolucion:numDevolucion,
					inverso:resp
				},function(iddevolucion){});
			}
			if($("#appministra").val()==1){
				$.post("ajax.php?c=Cheques&f=devolucionescxp",{
					opc:0,
					idDoc:$('#id').val(),
					fecha:fechadefaultcxp
				});
			}
			
			$('#devuelto').show();$('#guardarimg').hide();
			$("#poliza").attr("onclick","return verPoliza()");
			if(idpoliza>0){
				if(confirm('Documento devuelto! desea ver el movimiento inverso de la Poliza?')){
					window.parent.preguntar=false;
	 				window.parent.quitartab('tb0',0,'Polizas');
	 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1a&id='+idpoliza,'Polizas','',0);
					window.parent.preguntar=true;
					window.location.reload(); 
				}else{
					window.location.reload(); 
				}
			}else{
				alert("Documento devuelto.!");
			}
		}else{
			alert("Error al devolver Documento intente de nuevo.");
			$('#devuelto').hide();$('#guardarimg').show();
		}
		$("#imgproceso").hide();
	});
}
// function poliza(){
		// if($("input[name='tipo']:checked").val()==1){
			// alert("No puede generar poliza si el documento no esta autorizado");
			// return false;
		// }else{
			// var cuentasepara = $('#cuenta').val().split('//');
			// var idbancaria = cuentasepara[0];
			// var cuentacontable = cuentasepara[1];
			// var separa = $('#pagador').val().split('/');
			// var cuentabeneficiario = 0;
			// var beneficiario = separa[1];
			// if(separa[0]=="" || separa[0]==0){
				// cuentabeneficiario = $('#paguese2').val();
			// }else{
				// cuentabeneficiario = separa[0];
			// }
			// $.post('ajax.php?c=Cheques&f=verficaPoliza',{
				// idDocumento:$('#idDocumento').val()
			// },function(idpoli){
				// if(idpoli!=0){
					// if(confirm("La poliza ya fue creada desea reemplazarla?\nO seleccione cancelar para visualizarla")){
						// $.post("ajax.php?c=Cheques&f=eliminaPoliza",{
							// idpoli:idpoli
						// },function(re){
							// if(re==1){
								// $.post('ajax.php?c=Cheques&f=crearpoliza',{
									// idbancaria:idbancaria,
									// concepto:$('#textarea').val(),
									// fecha:$('#fecha').val(),
									// importe:$('#importe').val(),
									// cuentabeneficiario:cuentabeneficiario,
									// beneficiario:beneficiario,
									// numerocheque:$('#numero').val(),
									// referencia:$('#referencia').val(),
									// cuentacontable:cuentacontable,
									// idDocumento:$('#idDocumento').val(),
									// bancodestino:0,
									// cuentadestino:'',
									// formapago:2
								// },function(resp){
									// if(resp==1){
										// alert("Poliza Generada Correctamente !");
										// $('#img').hide();
									// }else{
										// alert('Error al generar Poliza');
										// $('#img').hide();
									// }
								// });
							// }else{
								// alert("Error al reemplazar poliza");
							// }
						// });
					// }else{
						// window.parent.preguntar=false;
			 			// window.parent.quitartab("tb0",0,"Polizas");
			 			// window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id='+idpoli+'&im=1','Polizas','',0);
						// window.parent.preguntar=true;
// 						
					// }
				// }if(idpoli==0){
					// $.post('ajax.php?c=Cheques&f=crearpoliza',{
						// idbancaria:idbancaria,
						// concepto:$('#textarea').val(),
						// fecha:$('#fecha').val(),
						// importe:$('#importe').val(),
						// cuentabeneficiario:cuentabeneficiario,
						// beneficiario:beneficiario,
						// numerocheque:$('#numero').val(),
						// referencia:$('#referencia').val(),
						// cuentacontable:cuentacontable,
						// idDocumento:$('#idDocumento').val(),
						// bancodestino:0,
						// cuentadestino:'',
						// formapago:2
					// },function(resp){
						// if(resp==1){
							// alert("Poliza Generada Correctamente !");
							// $('#img').hide();
						// }else{
							// alert('Error al generar Poliza');
							// $('#img').hide();
						// }
					// });
				// }
			// });
// 			
// 			
		// }
// 	
 // }
 
function creaPoliza(){
	
if( $('#cancelado').is(":visible") ){
	alert("No puede Generar Poliza de Documento Cancelado");
	return false;
}
	var cuentasepara = $('#cuenta').val().split('//');
	var idbancaria = $('#cuenta').val();
	var cuentacontable = cuentasepara[1];
	var separa = $('#pagador').val().split('/');
	var paguese2 = $('#paguese2').val().split('/');
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
function creaPolizaDevolucion(idDocumento){
	var cuentasepara = $('#cuenta').val().split('//');
	var idbancaria = $('#cuenta').val();
	var cuentacontable = cuentasepara[1];
	var separa = $('#pagador').val().split('/');
	var paguese2 = $('#paguese2').val().split('/');
	var cuentabeneficiario = 0;
	var beneficiario = separa[1];
	var tc = $("#cambio").val();
	var tipopoliza = $("#tipoPoliza").val();
	if($("#statustraspaso").val()==0){
		if((separa[0]=="" || separa[0]==0 || separa[0]==-1) && $("#paguese2").val()==0  ){
			
			if( $("#vercuentasprv").is(":visible") ){
			}else{
				$("#vercuentasprv").show();
				$('#devuelto').show();	
				alert("Seleccione cuenta para el Pagador");
				return false;
			}
			
			cuentabeneficiario = $('#paguese2').val();
		}else{
			cuentabeneficiario = separa[0]+'/1';
		}
	}else{
		tipopoliza = 1;
		if($("#listatraspaso").val()==0){
			alert("Seleccione la cuenta destino");
			$('#devuelto').show();	
			return false;
		}else{
			var lis = $("#listatraspaso").val().split("/");
			cuentabeneficiario = lis[3]+'/'+lis[4];//cuenta contable
		}
	}
	
	$.post('ajax.php?c=Cheques&f=crearpoliza',{
		idbancaria:idbancaria,
		concepto:"Reactivacion del cheque No."+$('#numero').val(),
		fecha:$('#fecha').val(),
		importe:$('#importe').val().replace(/,/gi,''),
		cuentabeneficiario:cuentabeneficiario,
		beneficiario:beneficiario,
		numerocheque:$('#numero').val(),
		referencia:$('#referencia').val(),
		cuentacontable:cuentacontable,
		idDocumento:idDocumento,
		bancodestino:$("#bancodestino").val(),
		cuentadestino:$("#numcuentadestino").val(),
		formapago:$("#formapago").val(),
		idbeneficiario:separa[2],
		tc:tc,
		tipopoliza:tipopoliza
		
	},function(resp){
		var request = resp.split('/');
		if(request[0]>0){
			if(request[1]==0){
				if(tipopoliza==3 || tipopoliza==0){
					$('#devuelto').hide();	
					$('#guardarimg').show();
					if(confirm('Documento Activado!, desea completar la poliza?')){
						window.parent.preguntar=false;
		 				window.parent.quitartab('tb0',0,'Polizas');
		 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+request[0],'Polizas','',0);
						window.parent.preguntar=true;
						window.location.reload(); 
					}else{
						window.location.reload(); 
					}
				}else if(tipopoliza==2){
					$('#devuelto').hide();	
					$('#guardarimg').show();
					 var iva = "";
					if(separa[1]!=2){ iva = "+&im=2&prv="+separa[2]; }
					if(confirm('Documento Activado!, desea ver la poliza?')){
						window.parent.preguntar=false;
		 				window.parent.quitartab('tb0',0,'Polizas');
		 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+request[0]+iva,'Polizas','',0);
						window.parent.preguntar=true;
						window.location.reload(); 
					}else{
						window.location.reload(); 
					}
				}
				else if(tipopoliza==1){
					$('#devuelto').hide();	
					$('#guardarimg').show();
					if(confirm('Documento Activado!, desea ver la poliza?')){
						window.parent.preguntar=false;
		 				window.parent.quitartab('tb0',0,'Polizas');
		 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+request[0],'Polizas','',0);
						window.parent.preguntar=true;
						window.location.reload(); 
					}else{
						window.location.reload(); 
					}
				}
				
				$("#poliza").show();
			}else{
				$('#devuelto').hide();	
				$('#guardarimg').show();
				alert('No tiene las cuentas de IVA asignadas');
				if(confirm('Desea agregarlos a la poliza manualmente?')){
					window.parent.preguntar=false;
	 				window.parent.quitartab('tb0',0,'Polizas');
	 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+request[0],'Polizas','',0);
					window.parent.preguntar=true;
					window.location.reload(); 
				}else{
					window.location.reload(); 
				}
				
			}
		}else{
			$('#devuelto').show();	
			alert('Error al activar Documento');
			
		}
	});
}
 function guardar(){
	var impreso = 2;
	var proceso  = 2;//autorizado
	$('#img').show();
	if($("input[name='forma']:checked").val()==1){
		impreso = $("input[name='forma']:checked").val();
	}
	if($("input[name='tipo']:checked").val()==1){
		proceso = 1;
		
	}
	
	var cuentasepara = $('#cuenta').val().split('//');
	var idbancaria = cuentasepara[0];
	var cuentacontable = cuentasepara[1];
	var separa = $('#pagador').val().split('/');
	var cuentabeneficiario = 0;
	var beneficiario = separa[1];
	if(separa[0]=="" || separa[0]==0 || separa[0]==-1){
		cuentabeneficiario = $('#paguese2').val();
	}else{
		cuentabeneficiario = separa[0];
	}
	
 	$.post('ajax.php?c=Cheques&f=crearcheque',{
 		idbancaria:idbancaria,
		concepto:$('#textarea').val(),
		fecha:$('#fecha').val(),
		importe:$('#importe').val(),
		cuentabeneficiario:cuentabeneficiario,
		beneficiario:beneficiario,
		numerocheque:$('#numero').val(),
		referencia:$('#referencia').val(),
		cuentacontable:cuentacontable,
		clasificador:$('#clasificador').val(),
		proceso:proceso
 	},function(resp){
		if(resp==1){
			alert("Documento Guardado!");
			window.location.reload();
			$('#img').hide();
		}else{
			alert('Error al generar Documento');
			$('#img').hide();
		}
 	});
 }
 function updateimprime(){
 	if($("input[name='tipo']:checked").val()==1){
		alert("El documento debe estar autorizado");
		return false;
		
	}
 	var cuentasepara = $('#cuenta').val().split('//');
 	$.post('ajax.php?c=Cheques&f=impreso',{
		idDocumento:$("#id").val()
		},function (resp){
			if(resp==0){
				alert("Error al Imprimir");
			}else{
				$(".apcance").hide();
				$('#impreso').prop('checked', true);
				$('#reimprime').show();
				$('#imprime').hide();
				window.print();
			}
			//$('#cancelado').hide();
		});
 }
 function editar(){
 	if($("#creados").val()!=0){
	 	$.post('ajax.php?c=Cheques&f=editar',{
	 		idDocumento:$('#creados').val()
	 	},function(resp){
	 		limpiar();
	 		$('#guardarimg').hide();//oculta el de guardar por primer avez
	 		var array = resp.split('//');
	 		$("#idDocumento").val(array[14]);
	 		
	 		$('#fecha').val(array[0]);
	 		$('#numero').val(array[2]);
	 		$('#importe').val(array[3]);
	 		$('#referencia').val(array[4]);
	 		$('#textarea').val(array[5]);
	 		$('#pagador').val(array[6]);
	 		if(array[7]==2){//cancelado
	 			$("#cancelado").show();
	 		}if(array[7]==4){//devuelto
	 			$("#devuelto").show();
	 		}
	 		if(array[7]==1){//activo
	 			$("#devuelto").hide();
	 			$("#cancelado").hide();
	 		}
	 		if(array[8]==1){//conciliado
	 			$('#conciliado').prop('checked', true);
	 		}if(array[9]==1){//si esta impreso
	 			$('#impreso').prop('checked', true);
				$('#reimprime').show();
				$('#imprime').hide();
				$('#numactual').hide();
				$('#paguese').attr("disabled",true);
				$("#clasificador").attr("disabled",true);
				//no podra editar nada del cheque si ya esta impreso
				
				$("#guardaredicion").hide();//guarda edicion
				
				$("#numero").attr("readonly",true);
				$("#referencia").attr("readonly",true);
				$("#textarea").attr("readonly",true);
				$("#importe").attr("readonly",true);
				$("#fecha").attr("disabled",true);
				$("input[name='tipo']").prop("disabled",true);
	 		}else{
	 			$('#impreso').prop('checked', false);
				$('#reimprime').hide();
				$('#imprime').show();
				
				//$('#guardarimg').show();
				$("#guardaredicion").show();
				$('#numactual').show();
				$("#numero").attr("readonly",false);
				$("#referencia").attr("readonly",false);
				$("#textarea").attr("readonly",false);
				$("#importe").attr("readonly",false);
				$("#fecha").attr("disabled",false);
				$("input[name='tipo']").prop("disabled",false);
	 		}
	 		
	 		if(array[10]==1){//asociado
	 			$('#asociado').prop('checked', true);
	 		}else{
	 			$('#asociado').prop('checked', false);
	 		}
	 		
	 		if(array[11]==1){//proyectado
	 			$('#proyectado').attr('checked', true);
	 		}
	 		if(array[11]==2){//autorizado
	 			$('#autorizado').attr('checked', true);
	 		}
	 		
	 		$("#clasificador").val(array[12]);    //idclasificador
	 	
	 	
		 	$("#paguese").selectpicker('refresh');
		    $("#clasificador").selectpicker('refresh');
		    letra();	
	 	});
	 }else{
	 	limpiar();
	 }
 }
 
 function verificanumero(){
 	$('#numero').css("border-color","");
 	var idbancaria = $('#cuenta').val().split('//');
 	$.post('ajax.php?c=Cheques&f=consulNumeroCheque',{
 		idbancaria:idbancaria[0],
 		numerocheque:$("#numero").val()
 	},function (resp){
 		if(resp==1){
 			alert("El numero de folio ya fue expedido");
 			$('#numero').val("");
 			$('#numero').css("border-color","red");
 			return false;
 		}
 		else if(resp==2){
 			alert("Folio invalido");
 			$('#numero').css("border-color","red");
 			return false;
 		}
 		else if(resp==0){
 			guardar();
 		}
 	});
 }
 function borrar(){
 	if($("#id").val()==0){
 		alert("El documento no esta almacenado");
 		return false;
 	}
 	if($("input[name='forma']:checked").val()==1){
		alert("No puedes borrar un cheque impreso");
	}else{
		
		$.post('ajax.php?c=Cheques&f=borrar',{
			idDocumento:$("#id").val()
		},function (resp){
			if(resp){
				alert("Documento deshabilitado!");
				window.location.reload();
			}else{
				alert("Error al deshabilitar Documento intente de nuevo...");
			}
		});
		
	}
 	
 	
 }
 function numeroActual(){
 	
 	var c = $('#cuenta').val().split('//');
	$.post('ajax.php?c=Cheques&f=buscanumerocheque',
		{idbancaria:c[0]},
		function (respuesta){
			var resp = respuesta.split('/');
			$("#numero").val(resp[0]);
			$("#finchequera").html(resp[1]);
			
			//validarangonumero();
		});
 }
 // function limpiar(){
 	// $("#idDocumento").val(0);
 	// $("#clasificador").val(0);
 	// $("#creados").val(0);
 	// $('#asociado').prop('checked', false);
	// $('#fecha').val("");
	// $('#numero').val("");
	// $('#importe').val("");
	// $('#referencia').val("");
	// $('#textarea').val("");
	// $('#paguese').val(0);
	// $('#impreso').prop('checked', false);
	// $('#reimprime').hide();
	// $('#imprime').show();
// 	
	// $('#cancelado').hide();
 	// $('#devuelto').hide();
 	// $("#numactual").show();
 	// $('#paguese').attr("disabled",false);
	// $("#clasificador").attr("disabled",false);
	// //$("#guardaredicion").show();
	// $("#numero").attr("readonly",false);
	// $("#referencia").attr("readonly",false);
	// $("#textarea").attr("readonly",false);
	// $("#importe").attr("readonly",false);
	// $("#fecha").attr("disabled",false);
	// $("input[name='tipo']").prop("disabled",false);
	// $("#paguese").select2({
     // width : "150px"
    // });
    // $("#clasificador").select2({
     // width : "150px"
    // });	
 // }
 function copiar(){
 	$("#fondo").show();
 	$('#copiarc').show();
 	$("#copiarc").load('ajax.php?c=Cheques&f=copiarcheque');
  	
 }
 function cierra(){
 	$("#fondo").hide();
 	$('#copiarc').hide();
 }
 
function proceso(){
	if($("#idDocumento").val()>0){
		
		$.post("ajax.php?c=Cheques&f=procesoUpdate",{
			id:$("#idDocumento").val(),
			proceso:$("input[name='tipo']:checked").val()
		},function (){
		
		});
	}

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
function antesdeGuardar(){
		var status=true;
	if(!$("#importe").val() || !$("#fecha").val() || ($("#pagador").val()==0 && $("#statustraspaso").val()==0)|| !$("#tipodocumento").val() || $("#cuenta").val()==0 || !$("#numcuentadestino").val() || $("#bancodestino").val()==0 || !$("#bancodestino").val()){
		alert("Faltan registros para Guardar el Documento");
		status =  false;
	}else{
		if(!validafecha($("#fecha").val())){
			alert("Seleccione una fecha acorde al periodo y ejercicio actual");
			status = false;
		}
		var cuenta = $("#cuenta").val().split('//');
		var separa = $('#pagador').val().split('/');
		var pagador2 = $("#paguese2").val().split('/');
		if($("#automatica").val()==1){
			if(( $("#tipoPoliza").val()==0 || !$("#tipoPoliza").val() ) && $("#statustraspaso").val()==0 && $("#statusanticipo").val()==0){
				alert("Seleccione el Tipo de Poliza");
				status = false;
			}
		}
		if(cuenta[2]!=1){
			if(!$("#cambio").val()){
				alert("Seleccione o Introduzca el T.C.");
				status = false;
			}
		}
		if((separa[0]=="" || separa[0]==0 || separa[0]==-1) && $("#paguese2").val()==0 && $("#statustraspaso").val()==0 ){ 
			if( $("#vercuentasprv").is(":visible") ){
				if($("#paguese2").val()==0){
					alert("Seleccione cuenta para el Pagador");
					status = false;
				}else{ status =true;}
			}else{
				$("#vercuentasprv").show();
				$("#paguese2").val(0);
				alert("Seleccione cuenta para el Pagador");
				status = false;
			}
		}else{
			if($("#statustraspaso").val()==1){
				if( $("#listatraspaso").val()==0 ){
					alert("Seleccione la cuenta destino para el traspaso");
					status = false;
				}
			}
			if( $("#subclasificador").is(":visible")){

				var sumaporc = 0;
				$(".porcentajesuma").each(function(){
					if(!$(this).val()){
						 alert("El porcentaje no puede ir nulo");
						 status = false;
					}else{
						sumaporc += parseFloat($(this).val());
					}
				});
				if(sumaporc < 100){
					alert("El porcentaje no cubre el 100%");
					status = false;
				}
			}
			if(status){
				if($("#id").val()){
					$('#numero').css("border-color","");
				 	var idbancaria = $('#cuenta').val().split('//');
				 	$.post('ajax.php?c=Cheques&f=numDocumentoEdicion',{
				 		idbancaria:idbancaria[0],
				 		numerocheque:$("#numero").val(),
				 		idDocumento:$('#id').val()
				 	},function callback(resp){
				 		if(resp==1){
				 			alert("El numero de folio ya fue expedido");
				 			$('#numero').val("");
				 			$('#numero').css("border-color","red");
				 			return false;
				 		}
				 		else if(resp==2){
				 			alert("Folio invalido");
				 			$('#numero').css("border-color","red");
				 			return false;
				 		}
						else if(resp==0){//si valido
							
							$("#submit").click();
						}
						
					});
				}else{
					$('#numero').css("border-color","");
				 	var idbancaria = $('#cuenta').val().split('//');
				 	$.post('ajax.php?c=Cheques&f=consulNumeroCheque',{
				 		idbancaria:idbancaria[0],
				 		numerocheque:$("#numero").val()
				 	},function callback(resp){
				 		
				 		if(resp==1){
				 			alert("El numero de folio ya fue expedido");
				 			$('#numero').val("");
				 			$('#numero').css("border-color","red");
				 			return false;
				 		}
				 		else if(resp==2){ 
				 			alert("Folio invalido");
				 			$('#numero').css("border-color","red");
				 			return false;
				 		}
				 		else if(resp==0){ 
				 			$("#submit").click();
				 		}
				 		
				 	});
				}
			}
		}
		
	}
}
/* traspaso mandara al menu de traspaso directo*/
function traspaso(){
	if($("input[name='forma']:checked").val()!=1){
		alert('El Documento debe estar impreso');
	}else{
		
	}
}
/* fin traspaso */

/* Cobrar cheque */
function cobrarcheque(){
	if(!$("#cobrado").is(":checked")){
		if(confirm("Esta seguro de quitar la marca,\nSe afectara el saldo en el calendario")){
			
					$.post("ajax.php?c=Cheques&f=cobrarCheque",{
					idDoc:$("#id").val(),
					status:0,
					fecha:fechadefault
					},function(resp){
						if(resp){
							alert("Hecho");
						}else{
							alert("Error al quitar marca");
							$("#cobrado").attr("checked",true);
						}
					});
				
		}
	}else{
		if($("input[name='forma']:checked").val()==1){
			if( $('#cancelado').is(":visible") ){
				alert("No puede cobrar un cheque cancelado.");
				$("#cobrado").attr("checked",false);
				return false;
			}else{
				if( $('#devuelto').is(":visible") ){
					alert("No puede cobrar un cheque devuelto.");
					$("#cobrado").attr("checked",false);
					return false;
				}else{
					if(confirm("La marca no se podra quitar esta seguro de continuar?")){
						var fechadefault = prompt("Introduzca la fecha de aplicacion",$("#fecha").val());
						if(fechadefault){
							if(confirm("La fecha de aplicacion es correcta?\n"+fechadefault)){
		
								$.post("ajax.php?c=Cheques&f=cobrarCheque",{
									idDoc:$("#id").val(),
									status:1,
									fecha:fechadefault
								},function(resp){
									if(resp){
										alert("Cheque Cobrado ;)");
										$(".apcance").hide();
										$("#cobrado").attr("checked",true);
										$("#cobrado").attr("disabled",true);
									}else{
										alert("Error al cobrar cheque");
										$("#cobrado").attr("checked",false);
									}
								});
							}else{
								$("#cobrado").attr("checked",false);
							}
						}else{
							$("#cobrado").attr("checked",false);
						}
					}else{
						$("#cobrado").attr("checked",false);
					}
				}
			}
			
		}else{
			alert("El cheque debe estar impreso");
			$("#cobrado").attr("checked",false);
		}
	}
}
/* fin cobro */

