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
function iracuenta(){
	window.parent.agregatab('../../modulos/cont/index.php?c=AccountsTree','Cuentas','',145);
}
function mandaasignarcuenta(clavep,claved,claveo){
	window.parent.preguntar=false;
	window.parent.quitartab("tb1647",1647,"Asignación de Cuentas");
	window.parent.agregatab("../../modulos/cont/index.php?c=Config&f=configAccounts&clavep="+clavep+"&claved="+claved+"&claveo="+claveo,"Asignación de Cuentas","",1647);
	window.parent.preguntar=true;

} 
function cancela(){
	if(confirm("Esta seguro de eliminar los datos capturados?")){
		$.post('index.php?c=Nomina&f=cancelaProvision',{},function(){ window.location.reload();});
	}
}
function borra(cont,tipo){
	$.post('ajax.php?c=CaptPolizas&f=borraprovision',{
		cont:cont,
		tipo:tipo},
		function(respues) {
			window.location.reload();
	});
}
function antesdeguardar(cont,statusxpagar,detalle,otracuenta){
	var i=0;var status=0; var d=0; var status2=0;
	for(d;d<=detalle;d++){
	
		if($("#percepcion"+d).val()=='ASIGNE CUENTA'){alert("Seleccione una cuenta en Asignacion de Cuentas Percepciones");  return false;}
		if($("#deduccion"+d).val()=='ASIGNE CUENTA'){alert("Seleccione una cuenta en Asignacion de Cuentas Deducciones");  return false;}
		if($("#otrosp"+d).val()=='ASIGNE CUENTA'){alert("Seleccione una cuenta en Asignacion de Cuentas Otros Pagos");  return false;}
	}
	for(i;i<cont;i++){
		if(statusxpagar==1){
			if($("#cuentasueldo").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas Sueldo por Pagar ");  return false;}
		}
		$("#load2").show();
		$("#agregaprevio").hide();
		$("#cancela").hide();
		$.post('ajax.php?c=Nomina&f=cuentaValorNomina',{
  			cont : i,
			cuentasaldo : $("#cuentasueldo"+i).val(),
			segmento : $("#segmento"+i).val(),
			sucursal : $("#sucursal"+i).val(),
			array:"provisionNomina"
		 },function callback(resp){
		 	status+=1;
	  			
	  			if(status==cont ){
	  				//$("#agrega").click();
	  				if(otracuenta){

		  				for(i=0;i<cont;i++){ 
		  				var contador = $("#contador"+i).val();
						 	for(var c=0;c<contador;c++){
							 	$.post('ajax.php?c=Nomina&f=cuentaValorNomina',{
							 		cont : i,
							 		cuentapercepciones:$("#"+i+"cuentaOtrosPer"+c).val(),
							 		cuentadeducciones:$("#"+i+"cuentaOtrosDedu"+c).val(),
									array:"provisionNomina"
							 	},function(){
							 		status2+=1;
				  					if(status2==otracuenta ){
				  						$("#agrega").click();
									}
							 	});
				 			}
		 				}
		 			}else{
		 				$("#agrega").click();
		 			}
	 				
	 				
	 				
	  			}
		 });
	}
	
}
function guardaProvision(){
	if($("#dife").html()>0 || $("#dife").html()<0){
			if(!confirm("La poliza no esta cuadrada desea guardar de todos modos?")){
				$("#load2").hide();
				$("#cancela,#agregaprevio").show();
				return false;
			}
		}
	var fecha=$('#fecha').val();
	if(fecha!=""){
		var fec;
		var sep=fecha.split('-');
		if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
		if(fec==sep[1] && sep[0]==$("#ejercicio").val()){
		$("#load2").show();
		$("#agregaprevio").hide();
		$("#cancela").hide();
			$.post('ajax.php?c=Nomina&f=creaProvisionNomina',{
				fecha:fecha,
				conceptopoliza:$("#concepto").val(),
				},function (resp){
					$("#load2").hide();
					if(resp!=0){
						if (confirm("Poliza generada Correctamente!. \n Desea ver la poliza?")){
						 	$.post('ajax.php?c=CaptPolizas&f=consultaultima',{},
						 		function (idpoli){ 
						 			
						 			window.location.reload();
						 			window.parent.preguntar=false;
						 			window.parent.quitartab("tb0",0,"Polizas");
						 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id='+idpoli+'&im=3','Polizas','',0);
									window.parent.preguntar=true;
						 		});
						 }else{
						 	$("#load2").hide();
						 	window.location.reload();
						 }
					}else{
						alert("Error al crear Poliza");
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
function cuentaingresosact(cont)
{
	$('#cargando-mensaje'+cont).css('display','inline');
	$.post("ajax.php?c=Nomina&f=actualizaListaSueldo",
			function(datos)
			{
					$('#cuentasueldo'+cont).html(datos);
					$("#cuentasueldo"+cont).select2({
					 width : "150px"
					});
					$('#cargando-mensaje'+cont).css('display','none');
			});

}

function abreFacturasPrev(){
	var copiar = [];
		for(var i = 0 ; i<=$(".copiar").length; i++)
		{
			if($("#copiar-"+i).is(':checked'))
			{
				copiar.push($("#copiar-"+i).val());
			}
		}
	$.post("index.php?c=Nomina&f=visualizaXMLalmacen",{
		xml: copiar,
		fecha:$("#fecha").val()
		},function(r){
			window.location.reload();
		});
}

function abrefacturas(){
	
	 if(parseInt($("#todas_facturas").val()))
	{
		$("#tipo_busqueda").val(1)
		$("#busqueda").attr('type','hidden').val('*').trigger("change")
		$("#tipo_busqueda,#titulo_tipo_busqueda").hide();
		$("#busqueda2").show()
	}
	else
	{
		$("#tipo_busqueda").show();
		$("#busqueda,#titulo_tipo_busqueda").attr('type','text')
		$("#busqueda2").hide()
	}
	$('#almacen').modal('show');	
}

function listaTemporales()
{
	$("#buscando_text").show()
	$.post("ajax.php?c=CaptPolizas&f=listaTemporalesProvisionBD",
		 	{
				folio_uuid:$("#busqueda").val(),
		 		tipo_busqueda:$("#tipo_busqueda").val()
			},
			function(callback)
			{
				$("#buscando_text").hide()
				$(".listado").html(callback);
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
		$("."+v).attr('checked',false);
	}
		