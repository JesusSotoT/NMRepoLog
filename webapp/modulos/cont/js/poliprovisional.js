/**
 * @author Carmen Gutierrez
 */
    
$(document).ready(function(){

	
	// $("#cuentaingre").select2({
         // width : "150px"
    // });
  	// $("#ivaingre").select2({
         // width : "150px"
    // });
    // $("#cuentaegre").select2({
         // width : "150px"
    // });
  	// $("#ivaegre").select2({
         // width : "150px"
    // });
//     
  /*$( "#loadalmacen" ).on( "click", function( e ) {
       $("#update1").addClass("fa-spin");
       $.post("ajax.php?c=CaptPolizas&f=listaTemporalesProvision",
	 	{
	
		},
		function(callback)
		{
			$(".listado").html(callback);
			$("#update1").removeClass("fa-spin");
	
		});
	     	
     });*/    

 $('#cancela').on('click', function() { 
 		var btncancelar = $(this);
		btncancelar.button("loading");
    		//$(this).button('loading');
    		if(confirm("Esta seguro de eliminar los datos capturados?")){
			$.post('index.php?c=CaptPolizas&f=cancela',{},function(){btncancelar.button('reset'); window.location.reload();});
			$("#comprobante").attr("disabled",false);
		}
    		
    	});
});
function cambio(){
	if($('#comprobante').val()==1){
		
		$('#ingresos').show();
		$('#egresos').hide();
		$('#xml').show();
		
	}if($('#comprobante').val()==2){
		
		$('#ingresos').hide();
		$('#egresos').show();
		$('#xml').show();
	}
	if($('#comprobante').val()==0){
		
		$('#ingresos').hide();
		$('#egresos').hide();
		$('#xml').hide();
	}
}
function comprueba_extension(formulario, archivo) { 
	$("#load").show();
   if (!archivo) {  $("#load").hide();
      	 alert("No ha seleccionado ningun archivo"); 
   }else{ 
      extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
      if ('.xml' == extension) {
      	var formData = new FormData($("#formulario")[0]);
        $.ajax({
            url: 'index.php?c=CaptPolizas&f=validaxml',  
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                var separa = data.split("-_-");
                if(separa[1]==0){ $("#load").hide();
                	alert("El XMl esta cancelado");
               }else if(separa[1]!=1){ $("#load").hide();
                	alert("XML no valido (Estructura contra esquema incorrecta)");
               }else if(separa[1]==1){ $("#load").hide();
              		alert("XML  validado (Estructura correcta)");
                	$('#envia').click(); 
                }
            }
            
        });
      } 
      else{ $("#load").hide();
      	 alert("Compruebe la extension del archivo. \nSolo se pueden subir archivos XML"); 
      } 
   }  
}
function cuentaingresosact(cont)
{
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=cuentaingresosact",
			function(datos)
			{
					$('#cuentaingre'+cont).html(datos);
					$("#cuentaingre"+cont).select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});

						 //alert(datos)

}
function cuentaegresosact(cont)
{
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=cuentaegresosact",
			function(datos)
			{
					$('#cuentaingre'+cont).html(datos);
					$("#cuentaingre"+cont).select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});

						 //alert(datos)

}
function cuentaegresosdeducible()
{
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=cuentaegresosact",
			function(datos)
			{
					$('#deducible').html(datos);
					$("#deducible").select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});
}
function iracuenta(){
	window.parent.agregatab('../../modulos/cont/index.php?c=AccountsTree','Cuentas','',145);
	//window.location='../../modulos/cont/index.php?c=AccountsTree';
}
function borra(cont,tipo){
	$.post('ajax.php?c=CaptPolizas&f=borraprovision',{
		cont:cont,
		tipo:tipo},
		function(respues) {
			window.location.reload();
	});
}

// function cancela(){
// 	
// }
function mandaasignarcuenta(){
	window.parent.agregatab("../../modulos/cont/index.php?c=Config&f=configAccounts","Asignación de Cuentas","",1647);
} 

function fechaactu(){
	if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
	$("#fecha").val($("#ejercicio").val()+'-'+fec+'-01').attr('readonly','readonly');
	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fecha").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
    });
}
function fechadefault(){
	if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
	//$("#fecha").val($("#ejercicio").val()+'-'+fec+'-01');
	     
   $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fecha").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        defaultDate:$("#ejercicio").val()+'-'+fec+'-01'
        
    });
}
function actualizaCuentas(cont)
{
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=actualizaprovprovecuentas",
			function(datos)
			{
					$('#CuentaProveedores'+cont).html(datos);
					$("#CuentaProveedores"+cont).select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});

						 //alert(datos)

}  
function actualizaCuentascli(cont)
{
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=actualizaprovclicuentas",
			function(datos)
			{
					$('#CuentaClientes'+cont).html(datos);
					$("#CuentaClientes"+cont).select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});

						 //alert(datos)

}
function mes(periodo){
 	var p1;
    if(periodo==1 ){ p1='Enero'; }
    if(periodo==2){ p1='Febrero'; }	
	if(periodo==3){ p1='Marzo';  }
    if(periodo==4){ p1='Abril';}
    if(periodo==5){ p1='Mayo';}
    if(periodo==6){ p1='Junio';}
    if(periodo==7){ p1='Julio';}
    if(periodo==8){ p1='Agosto'; }
    if(periodo==9){ p1='Septiembre'; }
    if(periodo==10){ p1='Octubre';  }
    if(periodo==11){ p1='Noviembre';  }
    if(periodo==12){ p1='Diciembre';}
     return p1;
}
  function guardaprovimultiple(){// ver q pasa con los totales y con cuenta proeveedorrs
		$("#comprobante").attr("disabled",false);
		$("#agregaprevio").hide();
		$("#cancela").hide();
		$("#load2").show();
		if($("#dife").html()>0 || $("#dife").html()<0){
			if(!confirm("La poliza no esta cuadrada desea guardar de todos modos?")){
				$("#load,#load2").hide();
				$("#agregaprevio").show();
				$("#cancela").show();
				return false;
			}
		}
		
		var fecha=$('#fecha').val();
		if(fecha!=""){
			var fec;
			var sep=fecha.split('-');
			if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
		
			if(fec==sep[1] && sep[0]==$("#ejercicio").val()){
			$("#load").show(); $("#guardar").hide();
			$("#agregaprevio").hide();
			$("#cancela").hide();
			$("#load2").show();
				$.post('ajax.php?c=CaptPolizas&f=guardaprovimultiple',{
					fecha:fecha,
					conceptopoliza:$("#conceptopoliza").val(),
					detalle:$("#detalle").val()
				},function (resp){
					if(resp==0){  
						 var id=0;
						 if (confirm("Poliza generada Correctamente!. \n Desea ver la poliza?")){
						 	$.post('ajax.php?c=CaptPolizas&f=consultaultima',{},
						 		function (idpoli){ 
						 			$("#load2").hide();
						 			//window.location="index.php?c=CaptPolizas&f=provisionmultiple";
						 			window.location.reload();
						 			window.parent.preguntar=false;
						 			window.parent.quitartab("tb0",0,"Polizas");
						 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id='+idpoli+'&im=3','Polizas','',0);
									window.parent.preguntar=true;
						 		});
						 }else{
						 	$("#load2").hide();
						 	//window.location="index.php?c=CaptPolizas&f=provisionmultiple";
						 	window.location.reload();
						 }
						 
					}if (resp==1){
						 alert("Fallo al general poliza!."); window.location.reload();
						$("#load2").hide();
					}
				});
		}else{
			alert("Elija una fecha acorde al periodo y ejercicio actual (mes:"+mes($('#idperio').val())+",año:"+$("#ejercicio").val()+")");
			 $("#load2").hide();$("#agregaprevio").show();$("#cancela").show();
			 $('#fecha').css("border-color","red");
			
		}
	 }else{
		 alert("Elija una fecha para la poliza");
		 $("#load2").hide();$("#agregaprevio").show();$("#cancela").show();
		 $('#fecha').css("border-color","red");
	 }
			
}

function abreFacturasPrev(){
	if($('#comprobante').val()!=0){
		 var copiar = [];
			for(var i = 0 ; i<=$(".copiar").length; i++)
			{
				if($("#copiar-"+i).is(':checked'))
				{
					copiar.push($("#copiar-"+i).val());
				}
			}
			console.log(copiar)
		$.post("index.php?c=CaptPolizas&f=guardaProvisionMultipleAlmacen",{
			comprobante: $('#comprobante').val(),
			xml: copiar,
			fecha:$("#fecha").val()
			},function(r){
				window.location.reload();
			});
	}else{
		alert("Debe eligir un comprobante primero");
		$('#almacen').modal('hide');
	}
}
function abrefacturas(){
	if(parseInt($("#todas_facturas").val()))
	{
		$("#tipo_busqueda").val(1)
		//$("#busqueda").attr('type','hidden').val('*').trigger("change")
		$("#busqueda").attr('type','hidden')
		$("#tipo_busqueda,#titulo_tipo_busqueda").hide();
		$("#busqueda2").show()
		fechasPredefinidas();
		$("#fecha_f").show();
	}
	else
	{
		$("#tipo_busqueda").show();
		$("#busqueda,#titulo_tipo_busqueda").attr('type','text')
		$("#busqueda2").hide()
		$("#fecha_f").hide();
	}
	$("#almacen").modal('show');	
}
function listaTemporales()
{
	$("#buscando_text").show()
		$.post("ajax.php?c=CaptPolizas&f=listaTemporalesProvisionBD",
		 	{
		 		folio_uuid:$("#busqueda").val(),
		 		tipo_busqueda:$("#tipo_busqueda").val(),
		 		fechas : $("#fecha_f").val()
			},
			function(callback)
			{
				if(callback)
				{
					$("#buscando_text").hide()
					$(".listado").html(callback);
				}
			});
}
$(function(){
$.extend($.expr[":"], {
	"containsIN": function(elem, i, match, array) {
	return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
	});
$("#busqueda2").bind("keyup", function(evt){
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
$("#buscando_text").hide()
});
function buttonclick(v)
	{
		$("."+v).click();
	}
function buttondesclick(v)
	{
		$("."+v).prop('checked',false);
	}

function afcPrevisualizar(){
	 var copiar = [];
		for(var i = 0 ; i<=$(".copiar").length; i++)
		{
			if($("#copiar-"+i).is(':checked'))
			{
				copiar.push($("#copiar-"+i).val());
			}
		}
	$.post("index.php?c=CaptPolizas&f=comprobacionGastosAlmacen",{
		xml: copiar,
		fecha:$("#fecha").val()
		},function(r){
			window.location='index.php?c=CaptPolizas&f=comprobacion';
		});
}
function marcarfiltro(v){ 
	for(var i = 0 ; i<=$(".copiar").length; i++)
		{
			if($("#copiar-"+i).is(':visible'))
			{
				$("#copiar-"+i).prop('checked',true);
			}
		}
	
}
function abrefacturascomprobacion(){
	
	$.post("ajax.php?c=CaptPolizas&f=listaTemporalesProvision",
		 	{
		
			},
			function(callback)
			{
				$(".listado").html(callback);
			});
	

	$('#almacen').modal('show');	
}
var pro=1;
function porra(tabla,cont,monto){
	$("#segmento"+cont).select2('destroy');
	$("#sucursal"+cont).select2('destroy');
	$("#cuentaingre"+cont).select2('destroy');
	
	$("#prorrateo"+cont+",#segiva"+cont+",#sucuiva"+cont+",#sucunombre"+cont+",#segnombre"+cont+",#sucuieps"+cont+",#segieps"+cont+",#sucuish"+cont+",#segish"+cont+",#sucuIVA"+cont+",#segIVA"+cont+",#sucuISR"+cont+",#segISR"+cont).show();
	
	$("#"+tabla+" tbody tr:eq(1)").clone().removeClass('porrateo').appendTo("#"+tabla+" tbody");
	$("#montoprorra"+cont).attr("data-valor","v"+cont+"v"+pro);
	$("#input"+cont).html('<input  onkeyup="calculaprorrateo(this.value,'+monto+','+cont+','+pro+')" type="text" name="prorrateo'+cont+'[]" id="prorrateo'+cont+'" style="width:80px;color: black" class="inputtext'+cont+'" align="center" />');
	$("#"+tabla+" tbody td:last").after('<td class="eliminar" id="elimi"><img src="images/basura.png" title="Elimina Prorrateo" style="width: 20px;height: 20px" onclick="eliminame('+cont+','+monto+')"/></td>');
	$("#segmento"+cont+",#sucursal"+cont+",#cuentaingre"+cont).select2({'width':'300px'});	
	$("#hayProrrateo"+cont).val(1);
	$("#muestrameiva"+cont+",#muestrameIVA"+cont+",#muestrameISR"+cont+",#muestrameish"+cont+",#muestrameieps"+cont).hide();
	pro++;
}
$(document).on("click",".eliminar",function(){
	var parent = $(this).parents().get(0);
	$(parent).remove();
	
});
function eliminame(cont,monto){
	var array=[];
	$(".inputtext"+cont).each(function(){
       array.push(parseInt($(this).val()));
	 });
	 if(array.length>2){
	 	
	 }else{
	 	$("#montoprorra"+cont).html(monto).number(true,2);
	 	$("#prorrateo"+cont+",#segiva"+cont+",#sucuiva"+cont+",#sucunombre"+cont+",#segnombre"+cont+",#sucuieps"+cont+",#segieps"+cont+",#sucuish"+cont+",#segish"+cont+",#sucuIVA"+cont+",#segIVA"+cont+",#sucuISR"+cont+",#segISR"+cont).hide();
		$("#hayProrrateo"+cont).val(0);
		$("#muestrameiva"+cont+",#muestrameIVA"+cont+",#muestrameISR"+cont+",#muestrameish"+cont+",#muestrameieps"+cont).show();
	 }
}
function calculaprorrateo(valor,monto,cont,pro){
	if(valor>0){
		var porcen = valor/100;
		$("label[data-valor=v"+cont+"v"+pro+"]").html((monto * porcen).toFixed(2));
	}else{
		$("label[data-valor=v"+cont+"v"+pro+"]").html(monto);
		//$("#montoprorra"+cont).html(monto);
	}
	$("label[data-valor=v"+cont+"v"+pro+"]").number(true,2);
}

function fechasPredefinidas()
{
	var start = moment();
	var end = moment();
    var lado;
    
    
    	
    	$("#fecha_f").daterangepicker({
	    	locale: {
	      				format: 'YYYY-MM-DD',
	      				separator: ' / ',
	      				"applyLabel": "OK",
				        "cancelLabel": "Cancelar",
				        "fromLabel": "Desde",
				        "toLabel": "Hasta",
				        "customRangeLabel": "Rango de fechas",
				        "weekLabel": "S",
				        "daysOfWeek": [
				            "Do",
				            "Lu",
				            "Ma",
				            "Mi",
				            "Ju",
				            "Vi",
				            "Sa"
				        ],
				        "monthNames": [
				            "Enero",
				            "Febrero",
				            "Marzo",
				            "Abril",
				            "Mayo",
				            "Junio",
				            "Julio",
				            "Agosto",
				            "Septiembre",
				            "Octubre",
				            "Noviembre",
				            "Deciembre"
				        ],
	    			},
	        startDate: start,
	        endDate: end,
	        "opens": lado,
	        ranges: {
	           'Hoy': [moment(), moment()],
	           'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	           'Esta semana': [moment().startOf('week'), moment().endOf('week')],
	           '1 Semana atrás': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
	           '2 Semanas atrás': [moment().subtract(2, 'week').startOf('week'), moment().subtract(2, 'week').endOf('week')],
	           '3 Semanas atrás': [moment().subtract(3, 'week').startOf('week'), moment().subtract(3, 'week').endOf('week')],
	           'Este mes': [moment().startOf('month'), moment().endOf('month')],
	           'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
	           'Dos meses atrás': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')]
	        }
	    });
}

