$(document).ready(function(){
	$("#cuentabancaria,#periodo,#ejercicio").select2({width : "130px"});
});
function valida(){
	$("#load").show();$("#antessubmit").hide();
	$.post("ajax.php?c=importarEstadoCuenta&f=validaEstado",{
		cuentabancaria:$("#cuentabancaria").val(),
		periodo:$("#periodo").val(),
		ejercicio:$("#ejercicio").val()
	},function(resp){
		if(resp==1){
			if(confirm("El estado de cuenta ya existe desea reemplazarlo?\nSe borraran los documentos conciliados ")){
				$.post("ajax.php?c=importarEstadoCuenta&f=borraDatosPrevios",{
					cuentabancaria:$("#cuentabancaria").val(),
					periodo:$("#periodo").val(),
					ejercicio:$("#ejercicio").val()
				},function(resp2){
					if(resp2==1){//si se borro
						$("#submit").click();
					}
				});
			}else{
				$("#load").hide();$("#antessubmit").show();
			}
		}
		if(resp==0){
			$("#submit").click();
		}
		if(resp==2){
			alert("El estado de cuenta de ese periodo ya fue conciliado");
			$("#load").hide();$("#antessubmit").show();
		}
	});
	
	
	
}

$(function() {
   
	$('#antessubmitbancos').click(function() {
		var btn = $(this);
		btn.button('loading');
		
		
		$.post("ajax.php?c=importarEstadoCuenta&f=primerConciliacionB",{
			cuentabancaria:$("#cuentabancaria").val()
			},function(request){
				if(request==0){
					btn.button('reset');
					if(confirm("Es la primera conciliacion de la cuenta :)\nEsta seguro que el ejercicio y periodo sera el primero en conciliar?\nYa no podra conciliar los anteriores a este." ) ){
						$("#submit").submit();
					}
				}else{
						$.post("ajax.php?c=importarEstadoCuenta&f=validaEstadoBancos",{
						cuentabancaria:$("#cuentabancaria").val(),
						periodo:$("#periodo").val(),
						ejercicio:$("#ejercicio").val()
						},function(resp){
							if(resp==1){
								if(confirm("El estado de cuenta ya existe desea reemplazarlo?\nSe borraran los documentos conciliados ")){
									$.post("ajax.php?c=importarEstadoCuenta&f=borraDatosPreviosBancos",{
										cuentabancaria:$("#cuentabancaria").val(),
										periodo:$("#periodo").val(),
										ejercicio:$("#ejercicio").val()
									},function(resp2){
										if(resp2==1){//si se borro
											btn.button('reset');
											$("#submit").submit();
										}
									});
								}else{
									$('#antessubmitbancos').attr("disabled",false);
									btn.button('reset');
								}
							}
							if(resp==0){
								btn.button('reset');
								$("#submit").submit();
								
								
								
							}
							if(resp==2){
								alert("El estado de cuenta de ese periodo ya fue conciliado");
								btn.button('reset');
							}
							// if(resp==3){
								// alert("Aun no ha finalizado una conciliación, debe finalizar para continuar.\nNOTA: recuerde que no puede conciliar periodos anteriores ni futuros");
								// btn.button('reset');
							// }
							if(resp==3){
								alert("No se puede importar el estado.\nNOTA: recuerde que no puede conciliar periodos anteriores ni futuros");
								btn.button('reset');
							}
							
						});
					}
				});
					 
		 //$(this).button('reset');
	});
	
	
	$('#conciliamanual').on('click', function() { 
		$(this).button('loading');
		if(confirm("Esta seguro de conciliar los datos agrupados?")){
    			var max = $("#numregistros").val();
    			var arrayidBancos = Array();
    			$("#load").show();
    			var cont=0; 
    			$("div[data-role=movbancos] ").each(function (index) { //div saco id
	          var idmovBanco = $(this).attr("data-value");
	          
	          if($("div[data-value="+idmovBanco+"] li").val()){
	          	var numDivs = (($("div[data-value="+idmovBanco+"] .out").length));
	          	//max -=1; 
	          	var pro=0;
	          	
	          	arrayidBancos.push(idmovBanco);
	          	
		         $("div[data-value="+idmovBanco+"] li").each(function (index) {
		         	 $.post("ajax.php?c=importarEstadoCuenta&f=conciliaMovimientosDocumentos",{
		        			idDoc:$(this).val(),
		        			idMovBanco:idmovBanco
		        		},function callback(){
		        			 pro++;
		        			if( pro==numDivs){
		        				max -=1;
		        			}
		        				if(max==0  && pro==numDivs){
		        					$.post("ajax.php?c=importarEstadoCuenta&f=verificaMontosConciliadosB",{
		        						idMovBancos:arrayidBancos
		        					},function callback(request){
		        						if(request==1){
		        							window.location='index.php?c=importarEstadoCuenta&f=verImport';
		        						}else{
		        							alert("La suma de los siguientes Mov. Bancarios no cuadraron.\n"+request);
		        							window.location='index.php?c=importarEstadoCuenta&f=verImport';
		        						}
		        					});
		        				}
		        		});
		        	});
		        
		       }else{ 
		       	 	max -=1;
		       		if(max ==0){
	        				window.location='index.php?c=importarEstadoCuenta&f=verImport';
	        			}
		        }
		      
		     });
    		}
	});
	
	
	$("#conciliarmovsuma").click(function () 
    {
    		if(confirm("Esta seguro de conciliar los datos agrupados?")){
    			var max = $("#numregistrossuma").val();
    			var arrayidBancos = Array();
    			var arrayidDocs = Array();
    			
    			//console.log("max",max);
    			$("#loadsuma").show();$("#conciliarmovsuma").hide();
    			var cont=0; 
    			$("div[data-role=movdoc] ").each(function (index) { //div saco id
	          var idDoc = $(this).attr("data-value");
	          
	          if($("div[data-value="+idDoc+"] li").val()){
	          	var numDivs = (($("div[data-value="+idDoc+"] .out").length));
	          	//max -=1; 
	          	var pro=0;
	          	arrayidDocs.push(idDoc);
		         $("div[data-value="+idDoc+"] li").each(function (index) {
		         		arrayidBancos.push($(this).val());
		         	 $.post("ajax.php?c=importarEstadoCuenta&f=conciliaMovimientosDocumentos",{
		        			idDoc:idDoc,
		        			idMovBanco:$(this).val()
		        		},function callback(){
		        			 pro++;
		        			if( pro==numDivs){
		        				max -=1;
		        			}
		        				if(max==0  && pro==numDivs){
		        					//console.log("concilia");
		        					$.post("ajax.php?c=importarEstadoCuenta&f=verificaMontosConciliadosDocumentos",{
		        						idMovBancos:arrayidDocs
		        					},function callback(r){
		        						if(r!=1){
		        							$("#loadsuma").hide();$("#conciliarmovsuma").show();
		        							alert("La suma de los siguientes Mov. Bancarios no cuadraron.\n"+r);
		        							window.location='index.php?c=importarEstadoCuenta&f=verImport';
		        						}else{
		        							window.location='index.php?c=importarEstadoCuenta&f=verImport';
		        						}
		        					});
		        				}
		        		});
		        	});
		        
		       }else{ 
		       	 	max -=1;
		       		if(max ==0){
		       			window.location='index.php?c=importarEstadoCuenta&f=verImport';
	        			}
		        }
		      
		     });
    		}
    		
    });
	
	$('#fin').on('click', function() {
		var btn = $(this);
		btn.button("loading");
		$.post("ajax.php?c=importarEstadoCuenta&f=finaliza",{
			idbancaria:$("#cuentabancaria").val(),
			periodo:$("#periodo").val(),
			ejercicio:$("#ejercicio").val()
		},function(resp){
			if(resp==1){
				alert("Conciliacion Finalizada!");
				//btn.button("reset");
				
				if($("#acontia").val()==1){
					if(confirm("Desea conciliar Contabilidad(Acontia)?")){
						$.post("ajax.php?c=importarEstadoCuenta&f=ConciliaAcontia",{
							idbancaria:$("#cuentabancaria").val(),
							periodo:$("#periodo").val(),
							ejercicio:$("#ejercicio").val()
						},function(request){
							if(request==1){
								alert("No se pudieron conciliar todos los movimientos,\n Deberá realizar conciliación de Acontia manual.");
								
							}else if(request==2){
								alert("Conciliacion Acontia Finalizada!");
							}else if(request==0){
								alert("Error al finalizar. Finalice manualmente en Realizar conciliación de Acontia .");
							}
							
							btn.button("reset");
							window.location.reload();
						});
					}
				}else{
					window.location.reload();
					btn.button("reset");
				}
				
				
			}else{
				alert("Error al Finalizar");
				btn.button("reset");
				window.location.reload();
			}
		});
	}); 
	$('#consultapre').on('click', function() {
		var btn = $(this);
		btn.button("loading");
		$.post("ajax.php?c=importarEstadoCuenta&f=validaEstadoBancos",{
		cuentabancaria:$("#cuentabancaria").val(),
		periodo:$("#periodo").val(),
		ejercicio:$("#ejercicio").val()
		},function(resp){
			if(resp==1){
				$.post("ajax.php?c=importarEstadoCuenta&f=consultaPrevios",{
					idbancaria:$("#cuentabancaria").val(),
					periodo:$("#periodo").val(),
					ejercicio:$("#ejercicio").val()
				},function(resp2){btn.button("reset"); window.location='index.php?c=importarEstadoCuenta&f=verImport';});
			}
			if(resp==2){
				alert("El estado de cuenta de ese periodo ya fue conciliado");
				btn.button("reset");
			}
			if(resp==0){
				alert("Aun no se sube el estado de cuenta de ese periodo");
				btn.button("reset");
			}
		});
	});
	
});

function dragStart(event) {
    event.dataTransfer.setData("Text", event.target.id);
}

function dragging(event) {
}

function allowDrop(event) {
    event.preventDefault();
}

function drop(event,id) {//al soltarlo
    event.preventDefault();
    var data = event.dataTransfer.getData("Text");
    event.target.appendChild(document.getElementById(data));
    //$("#"+id).attr("class","agrega"+id);
}
// suma documentos a un mov bancario //
function sumaDoc(){
	$('#sumamov').modal('show');
}
$(document).ready(function(){

 $("#buscar").keyup(function(){
		if( $(this).val() != "")
		{
			$("#tmovbancos tbody>tr").hide();
			$("#tmovbancos td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			$("#tmovbancos tbody>tr").show();
		}
	});
	
	$("#buscar2").keyup(function(){
		if( $(this).val() != "")
		{
			$("#tmovbancosinverso tbody>tr").hide();
			$("#tmovbancosinverso td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			$("#tmovbancosinverso tbody>tr").show();
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
