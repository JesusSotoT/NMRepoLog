var cuentasAfectables = "";
var cuentasEmpleadolista = "";
var totalcuentasPrevias =0 ;
var pagadorvalor = $("#pagador").val();

$(document).ready(function(){
	$("#importe").number(true,2);
	pagadorvalor = $("#pagador").val();
	
	$( "#loadcuenta" ).on( "click", function( e ) {
       $("#update3").addClass("fa-spin");
       $.post("ajax.php?c=Ingresos&f=actualizacionCatalogos",{
       	opc:3
       },function(resp){
       		$("#cuenta").html(resp).selectpicker('refresh');
       		listatipocambio();
       		$("#update3").removeClass("fa-spin");

       });
    });
});
function dias_periodo(NombreEjercicio,v,bancos)
{
	$.post("ajax.php?c=Cheques&f=InicioEjercicio",
 		 {
    		NombreEjercicio: NombreEjercicio
  		 },
  		 function(data)
  		 {
  		 	if (v==1){
  		 		data=NombreEjercicio+"-01-01";
  		 	}
  		 	if(bancos){ 
  		 		data=bancos;
  		 	}
  		 	var cad = data.split("-");
			var fin;
			if($('#Periodo').val() == 13)
			{
				$('#inicio_mes').html('31-12-'+cad[0]);
				$('#fin_mes').html('31-12-'+cad[0]);
			}
			else
			{
				$('#inicio_mes').html(moment(cad[0]+'-'+cad[1]+'-'+cad[2]).add('months', $('#Periodo').val()-1).format('DD-MM-YYYY'));
				fin = moment(cad[0]+'-'+cad[1]+'-'+cad[2]).add('months', $('#Periodo').val()).format('YYYY-MM-DD');
				fin = moment(fin).subtract('days',1).format('DD-MM-YYYY');
				$('#fin_mes').html(fin);
			}
  		 	
  		 });
	
}	
function cambioPeriodo(per,NameEjercicio)
{
	var cuenta = $("#cuenta").val().split("//");
	if(!cuenta[0] || cuenta[0] == 0){
		alert("Debe seleccionar una cuenta, ya que se validan los periodos conciliados");
		
	}else{
		$.post("ajax.php?c=Cheques&f=CambioEjerciciosession",
	 		 {
	    		Periodo: per,
	    		NameEjercicio: NameEjercicio,
	    		idbancaria:cuenta[0]
	  		 },
	  		 function(resp)
	  		 {
	  		 	if(resp==1){
	  		 		alert("No puede crear Documentos en periodos Conciliados");
	  		 	}else{
	  		 		location.reload();
	  		 	}
	  		 });
	}
}

function cambioEjercicio(per,ej)
{
	
	
	$.post("ajax.php?c=Cheques&f=CambioEjerciciosession",
 		 {
 		 	Periodo: per,
    		NameEjercicio: ej
  		 },
  		 function()
  		 {
  		 	if(resp==1){
  		 		alert("No puede crear Documentos en periodos Conciliados");
  		 	}else{
  		 		location.reload();
  		 	}
  		 });
	
}
function periodoactual(){
	$.post("ajax.php?c=Cheques&f=ejercicioactual",
 		 {},
  		 function ()
  		 {
  		 	alert("Establecido");
  		 	window.location.reload();
  		 });
	
}
function fechadefault(){
	if($('#idperiodo').val()<10){ fec=0+$('#idperiodo').val(); }else{ fec=$('#idperiodo').val(); }
	//$("#fecha").val($("#ejercicio").val()+'-'+fec+'-'+diadefault);//.attr('readonly','readonly');
		var d = new Date();
		var diadefault = d.getDate();

   $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    	$("#fecha").datepicker({
    		dateFormat: 'dd-mm-yy',
    		defaultDate:diadefault+'-'+fec+'-'+$("#ejercicio").val(),
    		minDate: '01'+'-'+fec+$("#ejercicio").val(),
    		onSelect: function(selected) {
          listatipocambio();
        }  
    });
    $("div.ui-datepicker-header a.ui-datepicker-prev,div.ui-datepicker-header a.ui-datepicker-next").hide();

}
function validafecha(fecha){
if($('#idperiodo').val()<10){ fec=0+$('#idperiodo').val(); }else{ fec=$('#idperiodo').val(); }

 var fecha = $("#fecha").val().split('-');
 if(fec == fecha[1] && $("#ejercicio").val() == fecha[2]){
 	return true;
 }else{
 	return false;
 }
 

}
function verPoliza(){ 
	$.post('ajax.php?c=Cheques&f=verficaPoliza',{
		idDocumento:$('#id').val()
	},function(idpoli){
		if(idpoli!=0){
			window.parent.preguntar=false;
			window.parent.quitartab("tb0",0,"Polizas");
			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id='+idpoli,'Polizas','',0);
			window.parent.preguntar=true;
		}else{
			alert("El Documento no tiene una poliza.");
		}
	});
}
function cambiaintro(){
	$("#int2").show();
	$("#tipocambio").val(0);
	$("#int").hide();
	$(".t1").selectpicker("hide");
	$(".t2").show();
	
}
function listadoin(){
    $("#tipocambio2").val("");
	$("#int").show();
	$("#int2").hide();
	$(".t1").selectpicker("show");
	$(".t2").hide();
}
function tipoc(e){
	$("#cambio").val(e);
	if($("#appministra").val()==1){
		
			
	
		if ( $(".trappministra").is(":visible")) {
				if(confirm("Los pagos de CXP que haya generado con el antiguo tipo de cambio se eliminaran desea continuar?")){
					var parsea = $('#pagador').val().split('/');var cuentasepara = $('#cuenta').val().split('//');
					$("#importe").val(0);
					totalcuentasPrevias = 0;
					$(".trappministra").hide("slow");
					pagadorvalor = $('#pagador').val();
					$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val());
				}else{
					$('#pagador').val(pagadorvalor).selectpicker("refresh");
				}
			}else{
				var parsea = $('#pagador').val().split('/');var cuentasepara = $('#cuenta').val().split('//');
				//$("#importe").val(0);
				//totalcuentasPrevias = 0;
				$(".trappministra").hide("slow");
				pagadorvalor = $('#pagador').val();
				$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val());
				
			}

		}
	
}
function beneficiarioCuenta(ext){//si es 1 esq si va traer todo afectables sino es q solo las de pesos
	$.post('ajax.php?c=Cheques&f=proveedorMoneda',{
		idbancaria:$("#cuenta").val(),
		ext:ext
	},function(resp){
		// var separa = resp.split('-_-');
		// $("#pagador,#paguese2").empty();
		// $("#pagador").html(separa[0]);
		
		$("#paguese2").empty();
		$("#paguese2").html(resp);
		$("#paguese2").selectpicker('refresh');
	});
}
function listatipocambio(){
	var fecha = ""; var fecha1="";
	if($('#fecha').val()){
		fecha  = $('#fecha').val();
		fecha1=$('#fecha').val().split('-');
	}else{
		var fechaperiodo = $("#inicio_mes").html().split('-');
		fecha =  fechaperiodo[2]+'-'+fechaperiodo[1]+'-'+fechaperiodo[0];
		fecha1 = fecha.split('-');
		
	}
 	listaTraspaso();
	//var fecha=fecha1[2]+"-"+fecha1[1]+"-"+fecha1[0];
	var cuentasepara = $('#cuenta').val().split('//');
	
	
		if(cuentasepara[2]==1 || $('#cuenta').val()==0 || $("#moneda").val()==1){ 
			//|| (!cuentasepara[2] && ($("#moneda").val()==1 || !$("#moneda").val()) ) 
				beneficiarioCuenta(0);//solo las de pesos
				$("#extra").hide();$("#brinquito").show();
				$("#cambio").val('0.0000');
				$("#tipoPoliza option[value='3']").remove();
				$("#tipoPoliza").append('<option value="3">Sin Provision</option>');
				$("#tipoPoliza").selectpicker('refresh');
				if($("#cuenta").val()=='t'){ 
					$("#tipoPoliza").selectpicker('hide');
					$("#tdpoliza").hide(); 
				}
				
		}else{
			if($("#cuenta").val()=='t'){ 
				cuentasepara[2]=$("#moneda").val(); 
				$("#tipoPoliza").selectpicker('hide');
				$("#tdpoliza").hide(); 
			}
			$("#tipoPoliza").val(0);
			$("#tipoPoliza option[value='3']").remove();
			$("#tipoPoliza").selectpicker('refresh');
			$("#extra").show();$("#brinquito").hide();
			beneficiarioCuenta(1);//todas afectable y de todos las monedas
			$("#consul").show();
			$.post('ajax.php?c=Cheques&f=consulcambio',{ idmoneda:cuentasepara[2],fecha:fecha}
			,function(tcambio){
				if(tcambio!=0){
					$.post('ajax.php?c=Cheques&f=tipoCambio',{ idmoneda:cuentasepara[2],fecha:fecha1[2]+"-"+fecha1[1]}
					,function(c){
						$(".t1").show();
						$("#tipocambio").html(c);
						$('#tipocambio option:contains("' + fecha + '")').prop('selected', true);
						$("#tipocambio").selectpicker('refresh');
						$("#consul").hide();
						$("#tipocambio2").hide();
						$("#cambio").val(tcambio);
					});
				}else{
					if(confirm("No tiene capturado un tipo de cambio del dia,\nDesea Capturarlo?")){
						window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=257&ticket=testing", "Tipos de Cambio",'',1675);
						$("#consul").hide();
					}else{
						$.post('ajax.php?c=Cheques&f=tipoCambio',{ idmoneda:cuentasepara[2],fecha:fecha1[2]+"-"+fecha1[1]}
						,function(c){
							$("#tipocambio").html(c);
							$(".t1").show();
							$("#tipocambio2").hide();
							$("#consul").hide();
						});
					}
				}
				
				
				
			});
			
		}
		if($("#appministra").val()==1){
			if($("#idDocumento").val() != 3){
				if ( $(".trappministra").is(":visible")) {
					if(confirm("Los pagos de CXP que haya generado con el antiguo tipo de cambio se eliminaran desea continuar?")){
						var parsea = $('#pagador').val().split('/');var cuentasepara = $('#cuenta').val().split('//');
						$("#importe").val(0);
						totalcuentasPrevias = 0;
						$(".trappministra").hide("slow");
						pagadorvalor = $('#pagador').val();
						$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val());
					}else{
						$('#pagador').val(pagadorvalor).selectpicker("refresh");
					}
				}else{
					var parsea = $('#pagador').val().split('/');var cuentasepara = $('#cuenta').val().split('//');
					//$("#importe").val(0);
					//totalcuentasPrevias = 0;
					$(".trappministra").hide("slow");
					pagadorvalor = $('#pagador').val();
					$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val());
					
				}
			}else{
				/* si es no depositado tendra es diferente porq
				 * ahi nose pide el tc y no podra convertir
				 */
				ingrePendientescxc();
			}
		}
	
}

function tcvalida(e, valor){
	 key = e.keyCode ? e.keyCode : e.which;
  if (key == 8) return true;
  if (key == 9) return true;//tabulador
  if (key == 37 || key== 39) return true; 

  if (key > 47 && key < 58) {
    if (valor.value == "") return true;
    regexp = /.[0-9]{4}$/;
    return !(regexp.test(valor.value));
  }
  if (key == 46) {
    if (valor.value == "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(valor.value);
  }
  return false;
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

function bancoDestino(){
	var parsea = $('#pagador').val().split('/');
		if($('#pagador').val()!=0){
			if (parsea[0]>0 || parsea[3]==4) {//proveedores en general
				$('#vercuentasprv').hide();
			}else{
				$('#vercuentasprv').show();
			}
		}else{ 
			$('#vercuentasprv').hide();
		}
	//var idprv =$("#pagador").val().split('/');
	if(parsea[2]!=2){
		$.post("ajax.php?c=Cheques&f=bancoDestino",{
		idprove:parsea[1],
		idbeneficiario:parsea[2]
		},function (resp){
			$("#numcuentadestino").val("");
			$("#bancodestino").val(" ");
			if (resp!=0) {
				$("#bancodestino").html(resp);
				$("#bancodestino").selectpicker('refresh');
				//$("#beneficiario").attr("readonly","");
			}else{
				$("#bancodestino").html('<option value=0>--No tiene Bancos--</option>').selectpicker('refresh');
				//alert("El Beneficiario no tiene bancos asociados");
				$("#pagador").val(0);
				$("#numcuentadestino").val("");
				$("#pagador").selectpicker('refresh');
				if(confirm("El Pagador no tiene bancos asociados, desea agregarlos?")){
					window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=275&ticket=testing","Bancos de Proveedor","",1706);
				}

			}
		});
	}else{
		$.post("ajax.php?c=Cheques&f=bancoDestinoEmpleado",{
		idprove:parsea[1]
		},function (resp){
			var option = resp.split('-_-');
			var separa = option[1].split('/');
			if(separa[0]!=0){
				$("#numcuentadestino").val(separa[1]);
				$("#bancodestino").html(option[0]).val(separa[0]).selectpicker('refresh');
			}else{
				$("#pagador").val(0);
				$("#numcuentadestino").val("");
				$("#bancodestino").html(option[0]).val(0).selectpicker('refresh');
				if(confirm("El empleado no tiene un banco asignado, desea agregarlo?") ){
					window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=301&ticket=testing","Empleados","",1706);
				}
			}
		});
	}
	var cuentasepara = $('#cuenta').val().split('//');
	if($("#appministra").val()==1){
		if ( $(".trappministra").is(":visible")) {
			if(confirm("Los pagos de CXP que haya generado con el antiguo pagador se eliminaran desea continuar?")){
				$("#importe").val(0);
				totalcuentasPrevias = 0;
				$(".trappministra").hide("slow");
				pagadorvalor = $('#pagador').val();
				$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val());
			}else{
				$('#pagador').val(pagadorvalor).selectpicker("refresh");
			}
		}else{
			//$("#importe").val(0);
			//totalcuentasPrevias = 0;
			$(".trappministra").hide("slow");
			pagadorvalor = $('#pagador').val();
			$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+parsea[1]+"&mone="+cuentasepara[2]+"&cobrar_pagar="+$("#cobrarpagar").val()+"&cambio="+$("#cambio").val());
		}
		
		

	}
}
/* traspaso cuenta */
function bancodestinotraspaso(){
	var datos = $("#listatraspaso").val().split('/');
	var c = $('#cuenta').val().split('//');
	var fecha = ""; var fecha1="";
	if($('#fecha').val()){
		fecha  = $('#fecha').val();
		fecha1=$('#fecha').val().split('-');
	}else{
		var fechaperiodo = $("#inicio_mes").html().split('-');
		fecha =  fechaperiodo[2]+'-'+fechaperiodo[1]+'-'+fechaperiodo[0];
		fecha1 = fecha.split('-');
		
	}
	$("#bancodestino").val(datos[1]).selectpicker('refresh');
	$("#numcuentadestino").val(datos[2]);
	if(datos[4]!=1){
		$("#extra").show();
		$.post('ajax.php?c=Cheques&f=consulcambio',{ idmoneda:datos[4],fecha:fecha}
		,function(tcambio){
			if(tcambio!=0){
				$.post('ajax.php?c=Cheques&f=tipoCambio',{ idmoneda:datos[4],fecha:fecha1[2]+"-"+fecha1[1]}
				,function(c){
					$("#tipocambio").html(c);
					$("#tipocambio").val(tcambio);
					$("#tipocambio").selectpicker('refresh');
					$("#consul").hide();
					$("#tipocambio2").hide();
					$("#cambio").val(tcambio);
				});
			}else{
				if(confirm("No tiene capturado un tipo de cambio del dia,\nDesea Capturarlo?")){
					window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=257&ticket=testing", "Tipos de Cambio",'',1675);
					$("#consul").hide();
				}else{
					$.post('ajax.php?c=Cheques&f=tipoCambio',{ idmoneda:datos[4],fecha:fecha1[2]+"-"+fecha1[1]}
					,function(c){
						$("#tipocambio").html(c);
						$("#tipocambio2").hide();
						$("#consul").hide();
					});
				}
			}
			
		});
	}else{
		if(c[2]==1){
			$("#extra").hide();
		}
	}
}
/* fin traspaso cuenta */
function numerocuent(){
	var idprv =$("#pagador").val().split('/');
	var sepabanco = $("#bancodestino").val().split('/');
	var banco = sepabanco[1];
	if(banco!=93){
		$.post('ajax.php?c=Cheques&f=numcuenta',{
			prove:idprv[1],
			banco:banco,
			beneficiario:idprv[2]
			},function (resp){
				
				if(resp!=0){
					$("#numcuentadestino").val(resp);
				}else{
					$("#numcuentadestino").val(0);
				}
			});
	}else{// si el banco es N/A
		$("#numcuentadestino").val(0);
	}
}

$(function(){
$.extend($.expr[":"], {
	"containsIN": function(elem, i, match, array) {
	return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
	});
$("#busqueda").bind("keyup", function(evt){
	if(evt.type == 'keyup')
	{
		$(".listado tr:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
		$(".listado tr:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
		$(".listado tr:containsIN('*1_-{}*')").css('display','table-row');
		if($(this).val().trim() === '')
		{
			$(".listado tr").css('display','table-row');
		}
	}

});


$('#retencion').on('click',function(){
             
    $('#divretencion').html('loading');
    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=Cheques&f=verRetencion&tipo='+$("#documentoTip").val(),
        data:{},
        success: function(data) {
          $('#divretencion').html(data);
        },
        error:function(err){
            alert("error"+JSON.stringify(err));
        }
         });
        });
});
function buttonclick(v)
	{
		$("."+v).click();
	}
function buttondesclick(v)
	{
		$("."+v).attr('checked',false);
	}
function abrefacturas(){
	//$("#Facturas").show();
	
	$("#idDocfactemp").val($("#idtemporal").val());
	$.post("ajax.php?c=Cheques&f=facturas_dialog",
		 	{
				idDoc: $('#id').val(),
				idDoctemp: $('#idtemporal').val()
			 },
			 function(data)
			 {
				
			 	$('#listaFacturas').html(data);
				
			 });
			 $("#Facturas").modal('show');	
	 // $("#Facturas").dialog(
	 // {
			 // autoOpen: false,
			 // width: 900,
			 // height: 510,
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
			 // },
			 // buttons: 
			// {
				// "Cerrar": function (){
// 					
					// $('#Facturas').dialog('close');		
				// }
			// }
		// });

	//$('#Facturas').dialog({position:['center',200]});
	//$('#Facturas').dialog('open');	
}
function actualizaListaFac()
{
$.post("ajax.php?c=Cheques&f=listaFacturas",
		 	{
				IdPoliza: $('#id').val(),
			 },
			 function(data)
			 {
				
			 	$('#facturaSelect').html(data);
				
			 });
}
$(function()
 {
$( '#subexml' )
  .submit( function( e ) { 
  	$('#verif').css('display','inline');
    $.ajax( {
      url: 'ajax.php?c=Cheques&f=subeFactura',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } ).done(function( data1 ) {
    	//$("#Facturas").dialog('refresh')
    		$.post("ajax.php?c=Cheques&f=facturas_dialog",
		 	{
				idDoc: $('#id').val(),
				idDoctemp: $('#idtemporal').val()
			},
			function(data2)
			{
				
			 	$('#listaFacturas').html(data2);
				
			});
			$('#factura').val('');
			actualizaListaFac();
			data1 = data1.split('-/-*');
			$('#verif').css('display','none');
			if(parseInt(data1[2]))
			{
				alert('Los siguientes archivos no son validos: \n'+data1[3]);
			}

			if(parseInt(data1[0]))
			{
				alert('Archivos Validados: \n'+data1[1]);
			}
    
  	});
    e.preventDefault();
  });
});
function variossub(){
	if( $("#clasificador").val()==0){
		$("#subclasificador").show();
	}else{
		$("#subclasificador").hide();
	}
	
}
function incluir(){
	if( $("#inluirdocumento").is(":checked")){
		$("#incluirdoc").val(1);
	}else{
		$("#incluirdoc").val(0);
	}
}
function listaEm(){
	if( $("#listaempleado").is(":checked")){
		$("#pagador").html(listaempleado);
		$("#pagador").val(0).selectpicker('refresh');
		$("#tipoPoliza").val(0);
		$("#tipoPoliza option[value='2']").remove();
		$("#tipoPoliza").selectpicker('refresh');

		
	}else{
		
		$("#pagador").html(listaprv);
		$("#tipoPoliza option[value='2']").remove();
		$("#tipoPoliza").append('<option value="2">Poliza con Provision con IVA</option>');
		$("#pagador").val(0).selectpicker('refresh');
		$("#tipoPoliza").selectpicker('refresh');
	}
	
}
/* traspaso */
function listaTraspaso(){
	var c = $('#cuenta').val().split('//');
	if($("#checktraspaso").is(":checked")){
		$("#checktanticipo").attr("disabled",true);
		// if($("#cuenta").val()==0){
			// $("#checktraspaso").attr("checked",false);
			// alert("Seleccione cuenta Origen!");
			// return false;
		// }else{
			$.post("ajax.php?c=Cheques&f=bancoDestinoEmpleado",{
			},function (respbanco){
				var option = respbanco.split('-_-');
				$("#bancodestino").html(option[0]).selectpicker('refresh');
			});
			$("#pagador,#tipoPoliza").val(0);
			
			$("#pagador").selectpicker('hide');
			$("#tipoPoliza").selectpicker('hide');
			$("#paguese,#tdpoliza").hide();
			//$("#pagador").selectpicker('hide');
			$('#vercuentasprv').hide();
			$("#statustraspaso").val(1);
			$("#progrestraspaso,#tdtraspaso,#tddoctraspaso").show();
			$.post("ajax.php?c=Cheques&f=cuentasTraspaso",{
				idbancaria:c[0]
			},function (request){
				if(request==0){
					alert("No tiene otras cuentas bancarias");
				}else{
					$("#bancodestino").val(0).selectpicker('refresh');
					$("#numcuentadestino").val("");
					$("#listatraspaso").html(request).selectpicker('refresh');
				}
				$("#progrestraspaso").hide();
			});
		//}
	}else{ 
		$("#checktanticipo").attr("disabled",false);

		$("#statustraspaso").val(0);
		$("#pagador").selectpicker('show');
		$("#paguese").show();
		if(($("#statuscomision").val()==0 || !$("#statuscomision").val()) && $("#statusanticipo").val()==0){
			if($("#automatica").val()==1){
				$("#tdpoliza").show();
				$("#tipoPoliza").selectpicker('show');
			}
		}
		$("#tdtraspaso,#tddoctraspaso").hide();
		
	}
}
function irDocumento(idDoc,tipodoc){
	var view = "";
	window.parent.preguntar=false;
	window.parent.quitartab("tb0",0,"Documento Destino");
	if(tipodoc==3){ view = "verIngresoNodep";}else{view = "verDeposito";}
	window.parent.agregatab('../../modulos/bancos/index.php?c=Ingresos&f='+view+'&editar='+idDoc,'Documento Destino','',0);
	window.parent.preguntar=true;
}
/* fin traspaso */
function irClientes(){
	 window.parent.agregatab("../../modulos/pos/index.php?c=cliente&f=indexGrid","Clientes","",2049);
}
function irProveedores(){
	window.parent.agregatab("../../modulos/punto_venta/catalogos/proveedor.php","Beneficiarios/Proveedores","",123);
}
function mandacuentabancaria(){
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=280&ticket=testing","Cuentas Bancarias","",1804);
}
/* complemento de pago */
function ValidaComplemento(value){
	if(value == 19){//Enajenacion de acciones u operaciones en bolsa de valores
		$("#enajenacion").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro,#hipotecarios,#otrotipo,#derivados").hide().find('input, textarea, button, select').attr('disabled','disabled');
	}else if(value == 25){//Otro tipo de retenciones
		$("#otrotipo").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro,#hipotecarios,#derivados").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}else if(value == 24){//Operaciones Financieras Derivadas de Capital
		$("#derivados").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}
	else if(value == 23){//Intereses reales deducibles por creditos hipotecarios
		$("#hipotecarios").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}
	else if(value == 22){//Planes personales de retiro
		$("#retiro").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}else if(value == 21){//Fideicomisos que no realizan actividades empresariales
		$("#fideicomiso").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}else if(value == 20){//Obtencion de premios
		$("#premios").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#fideicomiso,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}
	else if(value == 18){//Pagos realizados a favor de residentes en el extranjero
		$("#pagoextranjero").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#premios,#fideicomiso,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}
	else if(value == 17){//Arrendamiento en fideicomiso
		$("#arrendamiento").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#fideicomiso,#pagoextranjero,#premios,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}else if(value == 16){//Intereses
		$("#intereses").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}
	else if(value == 14){//Dividendos o utilidades distribuidas
		$("#dividendos").show().find('input, textarea, button, select').attr('disabled',false);	$('.selectpicker').selectpicker('refresh');
		$("#derivados,#otrotipo,#enajenacion,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}else{ //Los demas el sat no publica los complementos
		$("#derivados,#otrotipo,#enajenacion,#dividendos,#intereses,#arrendamiento,#pagoextranjero,#premios,#fideicomiso,#retiro,#hipotecarios").hide().find('input, textarea, button, select').attr('disabled','disabled');

	}



} 
/* fin complemento */
/*		 CXC Y CXP 		*/
function cxc(tipo){
	var separa = $("#pagador").val().split("/");
	var separacuenta = $("#cuenta").val().split("//");
	$.post("ajax.php?c=Cheques&f=cxccxcp",{
		id:separa[1],
		tipo:tipo,
		moneda:separacuenta[2]
	},function (resp){
		$("#cxc tbody").html(resp);
	});
}

function calculocxc(){
	var suma = 0;
	$("#importe").attr("readonly",false);
	$(".txtmonto").attr("readonly",false);
	$('.listacheck:checked').each(
    function () {
    		
    		var parse = $(this).val().split('/');
    		$("#txt"+parse[1]).attr("readonly",true);
    		$("#importe").attr("readonly",true);
     	suma += parseFloat(parse[0]);
    }
	);
	$('.listacheckfac:checked').each(
    function () {
    		
    		var parse = $(this).val().split('/');
    		$("#txtfac"+parse[1]).attr("readonly",true);
    		$("#importe").attr("readonly",true);
     	suma += parseFloat(parse[0]);
    }
	);
	$("#importe").val(totalcuentasPrevias+suma).number(true,2);
	porcentajecal(totalcuentasPrevias+suma);

}
function montosaldo(monto,id){
	if(!monto){monto=0;}
	var previo = $(".listacheck[data-value="+id+"]").val().split('/');
	var id = previo[1];
	var saldoOriginal = previo[2];
	if(parseFloat(monto)>parseFloat(saldoOriginal)){
		alert("El monto es mayor al saldo");
		monto = 0;
		$("#txt"+id).val(0);
		$(".listacheck[data-value="+id+"]").val(monto+"/"+id+"/"+saldoOriginal);
	}else{
		$(".listacheck[data-value="+id+"]").val(monto+"/"+id+"/"+saldoOriginal);
	}
}
function montosaldofac(monto,id){
	if(!monto){monto=0;}
	var previo = $(".listacheckfac[data-value='fac"+id+"']").val().split('/');
	var id = previo[1];
	var xml = previo[2];
	var saldoOriginal = previo[3];
	if(parseFloat(monto)>parseFloat(saldoOriginal)){
		alert("El monto es mayor al saldo");
		monto = 0;
		$("#txtfac"+id).val(0);
		$(".listacheckfac[data-value='fac"+id+"']").val(monto+"/"+id+"/"+xml+"/"+saldoOriginal);

	}else{
		$(".listacheckfac[data-value='fac"+id+"']").val(monto+"/"+id+"/"+xml+"/"+saldoOriginal);
	}
}

function eliminaPagoApp(idpago,idPagorela,monto){
	var importeantes = $("#importe").val();
	if(confirm("Esta seguro de eliminar este pago?\nse afectara el saldo en el Modulo Appministra")){
		$.post("ajax.php?c=Cheques&f=eliminaPagoApp",{
			idpagorelacion:idPagorela,
			idpago:idpago
		},function(request){
			if(request==1){
				totalcuentasPrevias-=monto;
				$("#importe").val(importeantes-monto);
				alert("Pago eliminado");
				$("#tr"+idpago).hide("slow");
				porcentajecal(importeantes-monto);
			}
		});
	}
	
}
/* 			FIN CXC CXP			*/
//max 6 decimales para complementos
function decimalescomplementos(e, valor){
	 key = e.keyCode ? e.keyCode : e.which;
//escapar teclas validas
  if (key == 8) return true;//Backspace o delete 
  if (key == 9) return true;//tabulador 
  if (key == 37 || key== 39) return true; //navegacion derec-izq

//
   if (key > 47 && key < 58) {//numeros
    if (valor.value == "") return true;
    regexp = /.[0-9]{6}$/;
    return !(regexp.test(valor.value));
  }
  
  if (key == 46) {
    if (valor.value == "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(valor.value);
  }
  return false;
} 
/*
 * la expresio esta adaptada acorde al SAT
 */
function validarfc(rfc){
	var rfc = rfc.replace(/\s*[\r\n][\r\n \t]*/g, "");
	var valid = /^([A-Z,Ñ,&]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/;

	if(rfc.length <12){
	 	return 0;
	}
	var validRfc=new RegExp(valid);
	var matchArray=rfc.match(validRfc);
	if (matchArray==null){
		return 0;
	}else{
		return 1;
	}	
}
/*el sat la marca la patro asi
	//[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][AZ]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9
	de la manera que se puso cubre posibles errores ya que al 
	verificar la clave de estado 
	que seria esto (AS|BC|BS|CC|CL|CM|CS|CH|D.......
	evitamos errores al momento de timbrar y en la
	expresion del sat no limita esto
*/	
function validacurp(curp){
	var curp = curp.replace(/\s*[\r\n][\r\n \t]*/g, "");
	var valid = /^([A-Z]{4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM](AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[A-Z]{3}[0-9A-Z]\d)$/i;
	if(curp.length <18){
	 	return 0;
	}
	var validcurp=new RegExp(valid);
	var matchArray=curp.match(validcurp);
	if (matchArray==null){
		return 0;
}else{ 
		return 1;
	}	
}
function porcentajecal(valor){
	if(valor.toString().indexOf(',') != -1){
		valor = valor.replace(/,/g, '');
	}
	var porce = new Array();
	var importes = new Array();
	$(".porcentajesuma").each(function(){
		if($(this).val()){
			 porce.push($(this).val());
		}
	});
	$(".impsuma").each(function(){
		importes.push($(this).data("value"));
	});
	for(var i=0;i<porce.length;i++){
		$("label[data-value="+importes[i]+"]").attr( "data-importe",(valor * ( porce[i] / 100)).toFixed(2) );
		$("label[data-value="+importes[i]+"]").html( (valor * ( porce[i] / 100)).toFixed(2) );
	
	}
	
}
/*anticipo de gastos debera cargar los usuarios
 * deudores en el campo de paguese
 */
function anticipo(){ 
	if( $("#checktanticipo").is(":checked")){
		$("#divusuarios").show("fold", 900);
		$("#statusanticipo").val(1);
		$("#tdpoliza").hide("slow");
		$("#checktraspaso,#checkcomision").attr("disabled",true);
	}else{
		$("#statusanticipo").val(0);
		$("#divusuarios").hide( "slow");
		$("#checktraspaso,#checkcomision").attr("disabled",false);
		if($("#statuscomision").val()==0 || !$("#statuscomision").val()){
			if($("#automatica").val()==1){
				$("#tdpoliza").show("fold", 900);
				$("#tipoPoliza").selectpicker('show');
			}
		}
		

	}
	
	
}


