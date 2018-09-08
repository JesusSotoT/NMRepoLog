$(document).ready(function(){
	$("#formapago,#listabancoorigen,#beneficiario,#listabanco,#xml").select2({width : "130px"});
});
function fechadefault(){
	if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
	//$("#fecha").val($("#ejercicio").val()+'-'+fec+'-01').attr('readonly','readonly');
	     
   $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fecha").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        defaultDate:$("#ejercicio").val()+'-'+fec+'-01'
        
    });
}
function datosEmpleado(idEmpleado){
	$.post('ajax.php?c=Nomina&f=datosEmpleado',{
		idEmpleado:idEmpleado,
	},function (resp){
		var parseo = resp.split("//");
		$("#rfc").val(parseo[0]);
		$("#listabanco").val(parseo[2]);
		$("#numtarje").val(parseo[1]);
		$("#listabanco").select2({ width : "150px"});
	});
	
}
function numeroCuentaOrigen(){
	var banco = $("#listabancoorigen").val();
	$.post('ajax.php?c=CaptPolizas&f=nuemrocuenta',{
		banco:banco
		},function (resp){
			$("#numorigen").val(resp);
		});
}
function actcuentasbancarias(){
	$("#cargando-mensaje").show();
	$.post('index.php?c=CaptPolizas&f=actcuentasbancarias',{}
	,function (resp){
		var r = resp.split('-_-');
		$('#listabancoorigen').html(r[1]);
		$("#cargando-mensaje").hide();
	});
}
function mandacuentabancaria(){
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=280&ticket=testing","Cuentas Bancarias","",1804);
}
function borra(cont,tipo){
	$.post('ajax.php?c=CaptPolizas&f=borraprovision',{
		cont:cont,
		tipo:tipo},
		function(respues) {
			window.location.reload();
	});
}	
function cancela(){
	if(confirm("Esta seguro de eliminar los datos capturados?")){
		$.post('index.php?c=Nomina&f=cancelaPago',{},function(){ window.location.reload();});
	}
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
function actualizaListaBanco(cont)
{
	$('#cargando'+cont).css('display','inline');
	$.post("ajax.php?c=Nomina&f=actualizaListaBanco",
			function(datos)
			{
					$('#banco'+cont).html(datos);
					$("#banco"+cont).select2({
					 width : "150px"
					});
					$('#cargando'+cont).css('display','none');
			});

}
function iracuenta(){
	window.parent.agregatab('../../modulos/cont/index.php?c=AccountsTree','Cuentas','',145);
}
function antesdeguardar(cont,statusxpagar){
	var i=0;var status=0;
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
			banco : $("#banco"+i).val(),
			array:"pagonomina"
		 },function(resp){
		 	status+=1;
	  			
	  			if(status==cont ){
	  				$("#agrega").click();
	  			}
		 });
	}
	

}
function guardaPago(){
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
			$("#guardar,#agregaprevio,#cancela").hide();
			$("#load2").show();
			$.post('ajax.php?c=Nomina&f=creaPagoNomina',{
				fecha:fecha,
				conceptopoliza:$("#concepto").val(),
				formapago:$("#formapago").val(),
				numero:$("#numero").val(),
				listabancoorigen:$("#listabancoorigen").val(),
				numorigen:$("#numorigen").val(),
				beneficiario:$("#beneficiario").val(),
				rfc:$("#rfc").val(),
				listabanco:$("#listabanco").val(),
				numtarje:$("#numtarje").val()
				},function (resp){
					if (confirm("Poliza generada Correctamente!. \n Desea ver la poliza?")){
					 	$.post('ajax.php?c=CaptPolizas&f=consultaultima',{},
					 		function (idpoli){ 
					 			$("#load2").hide();
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
function iraEmpleados(){
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=301&ticket=testing","Empleados","",1891);
}
function actualizaListaEmpleado(){
	$("#mensajeb").show();
	$.post('ajax.php?c=Nomina&f=ActualizaListaEmppleado',{},function(resp){
		$('#beneficiario').html(resp);
		$("#mensajeb").hide();
	});
}			