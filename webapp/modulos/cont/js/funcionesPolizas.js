var tipocambioglobal=0;
Number.prototype.format = function() {
      return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
  };
function agregarMov()
        {
        	
                $('button').attr('disabled','disabled')
                $('#cuenta').attr('disabled','disabled')
                $('#cargando').css('display','block')
                $impC = $('#cargo').val().split(',').join('');
                $impC = $impC.split(' ').join('');
                $impC = $impC.split('$').join('');
                $impA = $('#abono').val().split(',').join('');
                $impA = $impA.split(' ').join('');
                $impA = $impA.split('$').join('');
                
                $impCext = $('#cargoext').val().split(',').join('');
                $impCext = $impCext.split(' ').join('');
                $impCext = $impCext.split('$').join('');
                $impAext = $('#abonoext').val().split(',').join('');
                $impAext = $impAext.split(' ').join('');
                $impAext = $impAext.split('$').join('');
                if(parseFloat($impA)!=0)
                {
                  var TipoMov = "Abono";
                  $imp = $impA;
                }
                if(parseFloat($impC)!=0)
                {
                  var TipoMov = "Cargo";
                  $imp = $impC;
                }

                if (parseFloat($impC) == 0 && parseFloat($impA) == 0) {
                  var TipoMov = "Cargo";
                  $imp = 0;
                }
                
                if(parseFloat($impAext))
                {
                  var TipoMovext = "Abono M.E";
                  $impext = $impAext;
                }
                
                if(parseFloat($impCext))
                {
                	var TipoMovext = "Cargo M.E.";
                  $impext = $impCext;
                }
                if(parseFloat($impAext)==0 && parseFloat($impCext)==0){
                	$impext=-1;
                }
                var idmov;
                var sel_mult = 0;
                if($('#sel_multiple:checked').val() != undefined)
                {
                  sel_mult = 1;
                }
                
                 $.post("ajax.php?c=CaptPolizas&f=InsertMov",
                  {
                    IdPoliza: $('#idpoliza').val(),
                    Movto: $('#movto').val(),
                    IdMov: parseInt($('#movto').attr('idmov')),
                    Cuenta: $('#cuenta').val(),
                    TipoMovto: TipoMov,
    		            Importe: $imp,
    		            Referencia: $('#referencia_mov').val(),
    		            Concepto: $('#concepto_mov').val(),
    		            Sucursal: $('#sucursal').val(),
    		            Segmento: $('#segmento').val(),
                    Sel_Multiple: sel_mult,
    		            Factura: $('#facturaSelect').val(),
    		            Nuevo: $('#idr').val(),
    		            TipoMovtoext: TipoMovext,
    		            Importeext: $impext,
    		            tipocambio:$("#cambio").val() 
                  }, 
                  function(data)
                   { 
                     if(data)
                     {
                       $('button').removeAttr('disabled')
                       $('#cuenta').removeAttr('disabled')
                       $('#cargando').css('display','none')
                       setInterval(actualizaListaMov(),1000);
                       setInterval(actualizaListaMovFacturas(),400);
                       if($('#idr').val() == '0')
                       {
                         $("#capturaMovimiento").modal('hide');
                       }
                       $('#movto').val(parseInt($('#movto').val())+1);
                       //alert(data)
                       actuali();
                     }
                     else
                     {
                       alert('Ocurrio un error: No se guardo el registro.');
                     }
                   });
                
                 //Revisar porque no guarda algunos registros, hacer que no pueda capturar otro hasta que guarde el registro anterior.
       
       
        }
function modifica(id)
{
        //$('#cuenta').attr('disabled','disabled')
        //$('#segmento').attr('disabled','disabled')
        //$('#sucursal').attr('disabled','disabled')
        $('#cuenta').val('');
        $('#referencia_mov').val('');
        $('#concepto_mov').val('');
        $('#abono').val('0.00');
        $('#cargo').val('0.00');
        $('#movto').val('');
        $("#sucursal option[value='1']").attr("selected","selected");  
        $("#segmento option[value='1']").attr("selected","selected");  
        $("#facturaSelect option[value='-']").attr("selected","selected");  

	      var idR = $("#row-"+id).offset();
        var idPos = idR.top;
        /*$('#capturaMovimiento').dialog({position:['center',idPos-100]});
        $('#capturaMovimiento').dialog('open');*/
        $("#capturaMovimiento").modal("show");
 		
 		    $.post("ajax.php?c=CaptPolizas&f=DatosMov",
 		     {
    		    Id: id,
  		   },
  		    function(data)
  		    {
  		    	
  		 	    var datos = data.split("*/separacion/*");


            //INICIA CONSULTA EXT POLI////////////////////////////////

            $.post('ajax.php?c=CaptPolizas&f=consultaextedicionpoli',{
              idcuenta:datos[1],
              poli:$("#idpoliza").val()
            },function(resp){
                if(resp!=0){
                  //e la funcion buscacuentaext comente unas lineas para q rtomara el importe ext 
                  buscacuentaext(datos[1]); 
                  if(datos[2] == 'Abono')

                  {
                
                    $("#abonoext").val(resp);
                  
                    $("#cargoext").val('0.00');
                
                  }
                
                
                  if(datos[2] == 'Cargo')
                
                  {
                    $("#abonoext").val('0.00');
                  
                    $("#cargoext").val(resp);
                
                  }
                }else{
                		$("#extra").hide();
                		$("#cambio").val('0.0000');
                		$("#tipocambio").val(0);
                		$("#tipocambio2").val("");
                }
               
            });

            //TERMINA CONSULTA EXT POLI////////////////////////////////


  		 	    $("#movto").val(datos[0]);
            $("#movto").attr('idmov',id);
            $("#cuenta").select2({
            width : "150px"
          }).select2("val", datos[1]);

       
  		 	
  		 	$("#referencia_mov").val(datos[3]);
  		 	$("#concepto_mov").val(datos[4]);

  		 	if(datos[2] == 'Abono')
  		 	{
  		 	$("#abono").val(datos[5]);
  		 	$("#cargo").val('0.00');
  		 	}

  		 	if(datos[2] == 'Cargo')
  		 	{
  		 	$("#abono").val('0.00');
  		 	$("#cargo").val(datos[5]);
  		 	}
  		 		 	
  		 	$("#idr").val('0');

        
        $("#segmento").select2({
            width : "150px"
          }).select2("val", datos[7]);

        $("#sucursal").select2({
            width : "150px"
          }).select2("val", datos[6]);        

        $("#facturaSelect option").prop("selected",false); 
        
        if(parseInt(datos[10]))
        {
          $("#facturaSelect option[value='-']").attr('disabled',true)
          $("#facturaSelect option[value='-']").attr('selected',false)
          $("#sel_multiple").prop('checked',true)
          $("#facturaSelect").prop('multiple',true)
          var facturasMultiples = datos[11].split('//@//');
          for(ii=0;ii<=parseInt(facturasMultiples.length)-1;ii++)
          {
            $("#facturaSelect option[value='"+facturasMultiples[ii]+"']").prop("selected",true); 
          }
        }
        else
        {
          $("#facturaSelect option[value='-']").attr('disabled',false)
          $("#sel_multiple").prop('checked',false)
          $("#facturaSelect").prop('multiple',false)
          $("#facturaSelect").val(datos[8]); 
          //alert(datos[8])
        }

        //$("#formapago option[value='"+datos[9]+"']").attr("selected","selected");  
        //$('#cuenta').removeAttr('disabled')
        //$('#segmento').removeAttr('disabled')
        //$('#sucursal').removeAttr('disabled')
  		 });
}

function actualizaListaMovFacturas()
{
  $.post("ajax.php?c=CaptPolizas&f=MovFacturas",
     {
        IdPoliza: $('#idpoliza').val()
       },
       function(data)
       {
       
        $('#listaMovsFacturas').html(data);
       });
}

function actualizaListaMov()
	{
		$.post("ajax.php?c=CaptPolizas&f=NumMovs",
 		 {
    		IdPoliza: $('#idpoliza').val(),
    		bancos:$("#bancos").val()
  		 },
  		 function(data)
  		 {
  		 
  		 	$('#lista').html(data);
  		 });

		$.post("ajax.php?c=CaptPolizas&f=SumAbonosCargos",
 		 {
    		IdPoliza: $('#idpoliza').val(),
        IdEjercicio:$('#IdExercise').val()
  		 },
  		 function(data)
  		 {
        //alert(data)
  		 	var AbonosCargos = data.split("-");
        var formatter = new Intl.NumberFormat('en-US', {
          style: 'currency',
         currency: 'USD',
         minimumFractionDigits: 3,
          });
        if(AbonosCargos[0] == '') AbonosCargos[0] = 0;
        if(AbonosCargos[1] == '') AbonosCargos[1] = 0;
  		 	$('#Abonos, #AbonosAgregar').html("Abonos: <b>"+formatter.format(AbonosCargos[0])+"</b>");
  		 	$('#Cargos, #CargosAgregar').html("Cargos: <b>"+formatter.format(AbonosCargos[1])+"</b>");
        var diferencia = parseFloat(AbonosCargos[1]) - parseFloat(AbonosCargos[0]);
  		 	$('#Cuadre, #CuadreAgregar').html("Diferencia: <b style='color:red;'>"+formatter.format(diferencia)+"</b>");

  		 });
	}
function deleteMov(id,con)
	{
		
		var confirmar = confirm("¿Esta seguro de eliminar este movimiento "+con+"?");

		if(confirmar)
		{
                $.ajaxSetup({
                async: false
                });
            $.get('ajax.php?c=CaptPolizas&f=buscaext&idmov='+id, function() {
 			 });
 			
			$.get( "ajax.php?c=CaptPolizas&f=ActMovActivo&id="+id, function() {
 			$("#tbl"+id).css("display","none");
 			actualizaListaMov();
      actualizaListaMovFacturas();
 			//location.reload()
			});
                $.ajaxSetup({
                async: true
                });
		}
	}

function CancelPoliza()
{
	
	var confirmar = confirm("¿Esta seguro de cancelar esta poliza?");
	if(confirmar)
		{
			window.location = "index.php?c=CaptPolizas&f=Ver";
			
			
		}
}
function CancelPolizaAnticipo(){
	var confirmar = confirm("¿Esta seguro de cancelar esta poliza?");
	if(confirmar)
		{
			window.location = "index.php?c=CaptPolizas&f=anticipo";
			
			
		}
}
function dias_periodo()
{
  $.post("ajax.php?c=CaptPolizas&f=InicioEjercicio",
     {
        NombreEjercicio: $('#NameExercise').val()
       },
       function(data)
       {
            var cad = data.split("-");
            var fin;
            if($('#periodos').val() == 13)
              {
                $('#inicio_mes').html('31-12-'+$('#NameExercise').val());
                $('#fin_mes').html('31-12-'+$('#NameExercise').val());
              }
            else
              {
                $('#inicio_mes').html(moment($("#NameExercise").val()+'-'+cad[1]+'-'+cad[2]).add('months', $('#periodos').val()-1).format('DD-MM-YYYY'));
                fin = moment($("#NameExercise").val()+'-'+cad[1]+'-'+cad[2]).add('months', $('#periodos').val()).format('YYYY-MM-DD');
                fin = moment(fin).subtract('days',1).format('DD-MM-YYYY');
                $('#fin_mes').html(fin);
                cal();
              }
        
       });
  
} 

function cal()
{
		var fi = $("#fin_mes").html();
  		 	var fecha_inicial = fi.split('-');	
        if($("#periodos").val() == 13)
        {
          $("#datepicker").val($("#fin_mes").html()).attr('readonly','readonly');
           $("#concepto").val('Poliza de Ajuste, Ejercicio '+fecha_inicial[2]).attr('readonly','readonly');
        }
        else
        {
			$( "#datepicker" ).datepicker(
			{ 
				dateFormat: "dd-mm-yy",
				monthNames: [ "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
				dayNamesMin: [ "Do","Lu","Ma","Mi","Ju","Vi","Sa"],
				minDate: new Date(fecha_inicial[2], $("#periodos").val() - 1, 1),
				maxDate: new Date(fecha_inicial[2], $("#periodos").val() - 1, fecha_inicial[0])
			});
      }
$("#aux").css("display","none")
}	

function validaciones(f)
{
  if(f.concepto.value == "")
  {
  	if($("#bancos").val()!=1){
	    alert("El Campo Concepto esta vacio.");
	    f.concepto.focus();
	    return false;
   	}
  }

   if(f.fecha.value == "")
  {
    alert("El Campo Fecha esta vacio.");
    f.fecha.focus();
    return false;
  }
  var fch = f.fecha.value;
  fch = fch.split('-');
  if(f.periodos.value != 13)
  {
    if(parseInt(fch[1]) != parseInt(f.periodos.value) || parseInt(fch[2]) != parseInt(f.NameExercise.value))
    {
   	 	if($("#bancos").val()!=1){
    	  		alert("Escoje una fecha acorde al periodo y ejercicio actual.");
      		f.fecha.focus();
      		return false;
     	}
    }
  }
    else
    {
      if(parseInt(fch[1])+1 != parseInt(f.periodos.value) || parseInt(fch[2]) != parseInt(f.NameExercise.value))
      {
      	if($("#bancos").val()!=1){
	        alert("Escoje una fecha acorde al periodo y ejercicio actual.");
	        f.fecha.focus();
	        return false;
       	}
      }
    }

    if(f.numpol.value == "")
    {
      alert("Agregue un numero de poliza");
      f.numpol.focus();
      return false;
    }
	if($('#formapago').val()==2){
  		if($('#numero').val()==""){
  			alert("La forma de pago en Cheque requiere que proporcione un numero");
  			$('#numero').css("border-color","red");
  			return false;
  		}
  	}	
  }


function abonoscargos()
{
  if($("#abono").val() == "" || $("#abono").val() == "0.00" || $("#abono").val() == "0" )
  {
    $("#cargo").removeAttr("readonly");
  }
  else
  {
  	$("#cargo").val("0.00");
  	$("#cargo").attr("readonly","readonly");
  }

  if($("#cargo").val() == "" || $("#cargo").val() == "0.00" || $("#cargo").val() == "0" )
  {
    $("#abono").removeAttr("readonly");
  }
  else
  {
    $("#abono").val("0.00");
  	$("#abono").attr("readonly","readonly");
  }
}
function abonoscargosext()
{ 
  if($("#abonoext").val() == "" || $("#abonoext").val() == "0.00" || $("#abonoext").val() == "0" )
  {
    $("#cargoext").removeAttr("readonly");
    $("#abono").val("0.00");
  }
  else
  { 
  	$("#abono").val((parseFloat( $("#abonoext").val() ) * parseFloat( $("#cambio").val() )).toFixed(2));
  	$("#cargoext").val("0.00");$("#cargo").val("0.00");
  	$("#cargoext").attr("readonly","readonly");
  	$("#cargo").attr("readonly","readonly");
  }

  if($("#cargoext").val() == "" || $("#cargoext").val() == "0.00" || $("#cargoext").val() == "0" )
  {
    $("#abonoext").removeAttr("readonly");
    $("#cargo").val("0.00");
  }
  else
  {//
  	$("#cargo").val((parseFloat( $("#cargoext").val() ) * parseFloat( $("#cambio").val() )).toFixed(2));
    $("#abonoext").val("0.00");$("#abono").val("0.00");
  	$("#abonoext").attr("readonly","readonly");
  	$("#abono").attr("readonly","readonly");
  }
}
function selCuenta()
{
  alert($('#select2-input').val());
}

function buscacuentaext(idcuenta){
	var avansa=false;
	var provi=1;
	var idejer=$("#IdExercise").val();
	
	
	
$.post('ajax.php?c=CaptPolizas&f=consultaext',{
		idcuenta:idcuenta
		},function(resp){
			if(resp!=0){
				$("#load").show();
				// if($('#tipoPoliza').val()==1 || $('#tipoPoliza').val()==2){
					// $.post('ajax.php?c=Ajustecambiario&f=cargacuentas',{
						// idejer:idejer,
						// idcuenta:idcuenta
					// },function(cuent){
						// if(cuent!=0){
							// $('#relacion').show();
							// $('#relacionextra').html(cuent);
						// }
					// });
				// }
				var separa = resp.split('/');	
				if($('#datepicker').val()==""){
					alert("Elija una fecha para identificar el cambio del dia");  $("#capturaMovimiento").modal('hide'); return false;
				}
			var fecha1=$('#datepicker').val().split('-');
			var fecha=fecha1[2]+"-"+fecha1[1]+"-"+fecha1[0];
			var mesenviar;
			
			if(fecha1[1]<10){ mesenviar=parseInt(fecha1[1]);}else{mesenviar=fecha1[1];}
			
			//alert("Recuerde que en fin de semana el tipo de cambio se recorre al viernes NO se tomara en cuenta cambios de sabado o domingo");
				
					//$.post('ajax.php?c=CaptPolizas&f=consultacuenta',{
					
					 	//no diario y 0 provision
					
						 	$.post('ajax.php?c=CaptPolizas&f=consulcambio',{ idmoneda:separa[1],fecha:fecha}
								,function(tcambio){
						//alert(tcambio);
									if(tcambio==0){
										if(confirm("No tiene capturado un tipo de cambio del dia,\nDesea Capturarlo?")){
										//alert("No tiene capturado un tipo de cambio del dia");
											window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=257&ticket=testing", "Tipos de Cambio",'',1675);
											actuali();
										}else{
											if($('#tipoPoliza').val()!=3){
												$("#extra").show();
												$("#tipocambio2").val("");
												tipocambioglobal = tcambio;
												$.post('ajax.php?c=CaptPolizas&f=tipoCambio',{ idmoneda:separa[1],fecha:fecha1[2]+"-"+fecha1[1]}
												,function(c){
													$("#tipocambio").html(c);
												});
											}
										}
									}else{
										$('#cambio').val(tcambio);
										if($('#tipoPoliza').val()!=3){
											$("#extra").show();
											$("#tipocambio2").val("");
											tipocambioglobal = tcambio;
											$.post('ajax.php?c=CaptPolizas&f=tipoCambio',{ idmoneda:separa[1],fecha:fecha1[2]+"-"+fecha1[1]}
											,function(c){
												$("#tipocambio").html(c);
											});
										}
									}
						
									abonoscargosext();
		
							}); 
			 					$("#load").hide();
								$('#c').hide();
								$('#a').hide();
								//comente esto cuando iva ser la edicion en las polizas
								// $("#abonoext").val($("#abono").val());
								// $("#cargoext").val($("#cargo").val());
								$('#muestraextca').show();
								$('#muestraextab').show();
								$('#carext').html(separa[0]+" a PESOS");
								$('#abext').html(separa[0]+" a PESOS");
						 
						
					
			
		
			//if(avansa){//ver q pasa q ya no entra aki aunq tenga provision
	
					
					
				//}
		}else{
			$("#extra").hide();
			$("#tipocambio").val(0);
            $("#tipocambio2").val("");
			$("#load").hide();
			$('#relacion').hide();
			$('#c').show();
			$('#a').show();
			$('#cambio').val('0.0000');
			$("#abonoext").val(0);
			$("#cargoext").val(0);
			$('#muestraextca').hide();
			$('#muestraextab').hide();
			$('#carext').hide;
			$('#abext').hide;
		}
		
		
});
 
}
function tipopoliza(){ 
	var idpoliza = $('#idpoli').val();
  
	if($('#tipoPoliza').val()>2)//si es de diario o de orden oculta todo
  {
		$('#datospago').hide();
		$("#formapago").val(0);
		$("#beneficiario").val(0);
		$("#numero").val("");
		$("#numtarje").val("");
		$("#rfc").val("");
	}
  else
  {
    $('#datospago').show();//por default muestra todas las opciones
    $(".display").show(); 
    if(parseInt($("#tipoPoliza").val()) == 1)//Si es ingreso solo muestra la forma de pago
    {
      $(".display").hide(); 
      $("#contenedor-numero").show();
      $("#beneficiario").val(0);
      $("#numtarje").val("");
      $("#rfc").val("");
    }
  }
		//$('#formap').show();
	
		$.post("ajax.php?c=CaptPolizas&f=consultarelaciones",{
		idpoliza:idpoliza,
		tipopoliza:$('#tipoPoliza').val(),
		opc:1
		},function (resp){
			if(resp == 1){
				if(confirm("Si cambia el tipo de poliza se borraran las relaciones y desgloses realizados, desea continuar?")){
					$.post("ajax.php?c=CaptPolizas&f=consultarelaciones",{
					idpoliza:idpoliza,
					tipopoliza:$('#tipoPoliza').val(),
					opc:2
					},function (resp){  });
				}
			}
		});

	
}
function mandabancos(){
	$('#beneficiario').val(0);
	$("#numtarje").val("");
	$("#rfc").val("");
	$("#proveedor").val(0);
	$("#numero").val("");
	$("#beneficiario").select2({
				 width : "150px"
				});
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=275&ticket=testing","Bancos de Proveedor","",1706);
}
function mandacuentabancaria(){
	$('#numorigen').val("");
	$('#listabancoorigen').val(0);
	$("#listabancoorigen").select2({
				 width : "150px"
				});
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=280&ticket=testing","Cuentas Bancarias","",1804);

}

function cuentarbolbenefi(){
		var idprv =$("#beneficiario").val();
	if( $("#tipoBeneficiario").val() == 1){	
		$.post('index.php?c=CaptPolizas&f=bancosprove',{
			idprove:idprv
			},function callback(resp){
				$("#numtarje").val("");
				
				//$("#beneficiario").attr("disabled",true);
				
				var separa = resp.split("-_-");
				$("#listabanco").val(" ");
				if (separa[1].indexOf('*')==-1) {
					
					$("#listabanco").html(separa[1]);
					$("#beneficiario").val(idprv);
					$.post("ajax.php?c=CaptPolizas&f=datosprove",{
					idprove:idprv
					},function (resp){
						if(resp!=0){
							$("#rfc").val(resp);
						}
						numerocuent();
					});
				}else{
					//$("#listabanco").html(separa[1]);
					alert("El proveedor no tiene bancos asociados");
					$("#mandabanco").click();
					$("#beneficiario").val(0);
					
				}
				//$("#listabanco").val(0);
				
			});
		
		}else{//empleados banco
			$.post("ajax.php?c=CaptPolizas&f=bancoDestinoEmpleado",{
			idprove:idprv
			},function (resp){
				var option = resp.split('-_-');
				var separa = option[1].split('/');
				if(separa[0]!=0){
					$("#numtarje").val(separa[1]);
					$("#listabanco").html(option[0]).val(separa[0]);
				}else{
					$("#beneficiario").val(0);
					$("#numtarje").val("");
					$("#listabanco").html(option[0]).val(0);
					if(confirm("El empleado no tiene un banco asignado, desea agregarlo?") ){
						window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=301&ticket=testing","Empleados","",1706);
					}
				}
			});
		}
	
}
function cuentArbolBenefiAnticipo(){
	cuentarbolbenefi();
	$.post("ajax.php?c=CaptPolizas&f=CuentaPrvAgregaAuto",{
		prv:$("#beneficiario").val(),
		IdPoliza:$('#idpoli').val(),
		Movto:1,
		TipoMovto:"Cargo",
		importe:$('#importeprevio').val(),
		concepto:$("#concepto").val(),
		persona:"2-"+$("#beneficiario").val(),
		referencia:$("#referencia").val(),
		fomapago:$("#formapago").val()
	},function (resp){
		if(resp==1){
			alert("Error al generar movimiento automatico");
		}if(resp==0){
			 setInterval(actualizaListaMov(),1000);
		}if(resp==2){
			alert("El beneficiario no tiene cuenta asociada \n No se puede generar movimiento automatico");
		}
	});
	
	
}
function numerocuent(){
	var banco = $("#listabanco").val();
	var beneficiario = $("#beneficiario").val();
	$.post('ajax.php?c=CaptPolizas&f=numcuenta',{
		prove:beneficiario,
		banco:banco
		},function (resp){
			
			if(resp!=0){
				$("#numtarje").val(resp);
			}else{
				$("#numtarje").val(0);
			}
		});
}
function numerocuentorigen(){
	var banco = $("#listabancoorigen").val();
	$.post('ajax.php?c=CaptPolizas&f=nuemrocuenta',{
		banco:banco
		},function (resp){
			$("#numorigen").val(resp);
		});
}
function numerocuentorigenanticipo(){
	numerocuentorigen();
	$.post("ajax.php?c=CaptPolizas&f=CuentaAgregaAuto",{
		cuenta:$("#listabancoorigen").val(),
		IdPoliza:$('#idpoli').val(),
		Movto:2,
		TipoMovto:"Abono",
		importe:$('#importeprevio').val(),
		concepto:$("#concepto").val(),
		persona:"2-"+$("#beneficiario").val(),
		referencia:$("#referencia").val(),
		fomapago:$("#formapago").val()
	},function (resp){
		if(resp){
			alert("Error al generar movimiento automatico");
		}else{
			 setInterval(actualizaListaMov(),1000);
		}
	});
}
$(document).ready(function() {
	$("#beneficiario,#usuarios").select2({
				 width : "100px"
				});
	$("#listabancoorigen,#formapago").select2({
	 width : "100px"
	});
	
	
});

function antesdemandar(){
	
	var formatter = new Intl.NumberFormat('en-US', {
          style: 'currency',
         currency: 'USD',
         minimumFractionDigits: 3,
          });
	if($('#tipoPoliza').val()==3){
		$('#relacionn').hide();
		$('#relacionextra').html("<option value=0 selected></option>");  
	}
	if(($('#tipoPoliza').val()!=3) ){ 
		$.post('ajax.php?c=Ajustecambiario&f=moviextranjeros2',{
		idejer:$("#IdExercise").val(),
		idpoliza:$('#idpoli').val(),
		idperido:$('#periodos').val()
		},function (resp){
			if(resp==0){
				alert("No existe ninguna provision de la cuenta debe realizar la provision para continuar");
				return false;
			}else if(resp!=0 && resp!='no'){
				if(!$('#relacionextra').val() || $('#relacionextra').val()==0){
					alert('Debe relacionar una provision para el ajuste a fin de mes');
					$('#relacionn').show();
					$('#relacionextra').html(resp);
					return false;
				}
				
			}else if(resp=="no"){
				$('#relacion').hide();
				$('#relacionextra').html("<option value=0 selected></option>");
				//$('#actualizarboton').click();	
			}
			$.post('ajax.php?c=CaptPolizas&f=SumAbonosCargos',{
					IdPoliza:$("#idpoli").val(),
					IdEjercicio:$("#IdExercise").val()
				},function(resp){
					
					var re=resp.split('-');  
          if(re[0] == '') re[0] = 0;
          if(re[1] == '') re[1] = 0;

           
           var diferencia = parseFloat(re[1]) - parseFloat(re[0]);
            diferencia = diferencia.toFixed(3)
            //alert(diferencia)
           if(diferencia > 0 || diferencia < 0){
						 if(confirm('La poliza no esta cuadrada desea guardar de todos modos? \nDiferencia: '+diferencia))
             {
							$('#actualizarboton').click();
							$('#guardarpolizaboton').click();	
						 }
					}else{
						$('#actualizarboton').click();	
						$('#guardarpolizaboton').click();	
					}
				});
		});	
		//return false;
	}else{
		$('#relacion').hide();
	$.post('ajax.php?c=CaptPolizas&f=SumAbonosCargos',{
					IdPoliza:$("#idpoli").val(),
					IdEjercicio:$("#IdExercise").val()
				},function(resp){
					
			 		 var re=resp.split('-');
           var diferencia = parseFloat(re[0]).toFixed(3) - parseFloat(re[1]).toFixed(3)
					 if(diferencia > 0 || diferencia < 0){
						 if(confirm('La poliza no esta cuadrada desea guardar de todos modos? \n Diferencia: '+formatter.format(diferencia)))
             {
							$('#actualizarboton').click();
							$('#guardarpolizaboton').click();		
						 }
					}else{
						$('#actualizarboton').click();	
						$('#guardarpolizaboton').click();	
					}
				});
			//}
	}	
}
function irapadron() { 
	window.parent.agregatab("../../modulos/punto_venta/catalogos/proveedor.php",'Proveedores','',145);
	
}
function actualizaprove(){
	$.post('index.php?c=CaptPolizas&f=actulizabenifi',{},function(resp){
		$('#beneficiario').html(resp);
	});
}

function cuadraPoliza()
{
  var cuadre = $("#CuadreAgregar").text()
  cuadre = cuadre.replace("Diferencia:","").replace("$","").replace(/,/g, '')

  if($("#cargo").val() == '' || $("#cargo").val() == ' ')
    $("#cargo").val(0)

  if($("#abono").val() == '' || $("#abono").val() == ' ')
    $("#abono").val(0)
  
  switch(true)
  {
    case (cuadre > 0):
                cuadre = parseFloat(cuadre)
                if(parseInt($("#cargo").val()) == 0)
                {
                      $("#abono").val(parseFloat($("#abono").val())+cuadre).focus();
                      $("#cargo").val('0.00');
                }
                else
                {
                      $("#abono").val('0.00');
                      $("#cargo").val(parseFloat($("#cargo").val())-cuadre).focus();
                }
                break;
    case (cuadre < 0):
                cuadre = cuadre.replace("-","").replace(" ","")
                cuadre = parseFloat(cuadre)
                if(parseInt($("#abono").val()) == 0)
                {
                      $("#abono").val('0.00');
                      $("#cargo").val(parseFloat($("#cargo").val())+cuadre).focus();
                }
                else
                {
                      $("#abono").val(parseFloat($("#abono").val())-cuadre).focus();
                      $("#cargo").val('0.00');
                }
                break;
    case (cuadre == 0):
                alert('No hay diferencia entre cargos y abonos.');
                break;
  }

}
function polizadiario(){
	if($("#saldado").is(":checked")) {
		$("#saldado").val(1);
	}else{
		$("#saldado").val(0);
	}
}
function actcuentasbancarias(){
	$.post('index.php?c=CaptPolizas&f=actcuentasbancarias',{}
	,function (resp){
		var r = resp.split('-_-');
		$('#listabancoorigen').html(r[1]);
	});
}
$(document).ready(function(){
	$("#segmento").select2({
     width : "150px"
    });
    $("#sucursal,#tipocambio").select2({
    	width : "150px"
    });
});
    
function cambiaintro(){
	$("#int2").show();
	$("#tipocambio").val(0);
	$("#int").hide();
	$(".t1").hide();
	$(".t2").show();
	
}
function listadoin(){
    $("#tipocambio2").val("");
	$("#int").show();
	$("#int2").hide();
	$(".t1").show();
	$(".t2").hide();
}
function tipoc(e){
	if(!e ){
		e=tipocambioglobal;
	}
	$("#cambio").val(e);
	abonoscargosext();
	
}
function listaEm(){
	if( $("#listaempleado").is(":checked")){
		$("#beneficiario").html(listaempleado);
		$("#beneficiario").val(0).select2({ 	width : "150px" });
		$("#tipoBeneficiario").val(2);
	}else{
		
		$("#beneficiario").html(listaprv);
		$("#beneficiario").val(0).select2({ 	width : "150px" });
		$("#tipoBeneficiario").val(1);

	}
	
}
