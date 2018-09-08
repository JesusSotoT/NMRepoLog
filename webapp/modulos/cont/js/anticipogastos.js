$(document).ready(function(){
	$("#anticipolista,#deducible,#sucursalde,#segmentode,#usuarios").select2({
         width : "150px"
    });
	
 });

 function deudoranticipo(){ 
 	if($("#anticipolista").val()!=0){
	 	$("#load3").show();
	 	$.post("ajax.php?c=CaptPolizas&f=deudorAnticipo",{
	 		idpoliza: $("#anticipolista").val()
	 	},function(resp){
	 		window.location.reload();
	 	});
 	}
 }
function cancelaComprobacion(){
	if(confirm("Esta seguro de eliminar los datos capturados?")){
		$.post('index.php?c=CaptPolizas&f=cancelaComprobacion',{},function(){ window.location.reload();});
		$("#comprobante").attr("disabled",false); $("#usuarios").val(0);
		$("#usuarios").select2({width : "150px"});
	}
}
function guardacomprobacion(){// ver q pasa con los totales y con cuenta proeveedorrs
		$("#agregaprevio").hide();
		$("#cancela").hide();
		$("#load2").show();
		// if($("#dife").html()>0 || $("#dife").html()<0){
			// if(!confirm("La poliza no esta cuadrada desea guardar de todos modos?")){
				// $("#load").hide();
				// $("#agregaprevio").show();
				// $("#cancela").show();
				// return false;
			// }
		// }
		var fecha=$('#fecha').val();
		if(fecha!=""){
			var fec;
			var sep=fecha.split('-');
			if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
		
			if(fec==sep[1] && sep[0]==$("#ejercicio").val()){
			$("#load").show(); $("#guardar").hide();
			var cargodiferencia = 0; 
			var cuentadiferencia=0;
			var abonodiferencia = 0;
			
			if($("#cuentadiferencia").val()){
				cuentadiferencia = $("#cuentadiferencia").val();
				
				if($("#cargo").html()>0){
					cargodiferencia=$("#cargo").html();
				}
				if($("#abono").html()>0){
					abonodiferencia=$("#abono").html();
				}
			}
				$.post('ajax.php?c=CaptPolizas&f=guardacomprobacion',{
					fecha:fecha,
					cuentadiferencia:cuentadiferencia,
					abonodiferencia:abonodiferencia,
					cargodiferencia:cargodiferencia
					
				},function (resp){
					if(resp==0){  
						 var id=0;
						 if (confirm("Poliza generada Correctamente!. \n Desea ver la poliza?")){
						 	$.post('ajax.php?c=CaptPolizas&f=consultaultima',{},
						 		function (idpoli){ 
						 			$("#load2").hide();
						 			window.location="index.php?c=CaptPolizas&f=comprobacion";
						 			window.parent.preguntar=false;
						 			window.parent.quitartab("tb0",0,"Polizas");
						 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id='+idpoli+'&im=2&prv=0','Polizas','',0);
									window.parent.preguntar=true;
						 		});
						 }else{
						 	$("#load2").hide();
						 	window.location="index.php?c=CaptPolizas&f=comprobacion";
						 }
						 
					}if (resp==1){
						 alert("Fallo al general poliza!."); window.location="index.php?c=CaptPolizas&f=comprobacion";
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

function noDeducibleAgregar(){
	if(!$("#cargodeducible").val()){
		alert("Agrege el monto Cargo");
	}else{
		$.post("index.php?c=CaptPolizas&f=agregatr",{
			cuenta:$("#deducible").val(),
			conceptode : $("#conceptode").val(),
			cargodeducible : $("#cargodeducible").val(),
			abonodeducible : $("#abonodeducible").val(),
			segmentode : $("#segmentode").val(),
			sucursalde :$("#sucursalde").val()
		},function(resp){
			alert("Agregado!");
			window.location='index.php?c=CaptPolizas&f=comprobacion';
		});
	}
}

function agregadeducible(){

	$("#capturaNoDeducible").modal("show");

}

function buscaAnticipo(){
	$("#load3").show();
	$.post('ajax.php?c=CaptPolizas&f=buscaAnticipo',{
		user:$("#usuarios").val()
	},function(resp){$("#load3").hide();
		window.location.reload();
	});
}
// function agregadeducible2(){
	// $("#datos tfoot tr:eq(0)").clone().removeClass('fila_base').appendTo("#datos tfoot");
// }

// $(document).on("click",".eliminar",function(){
		// var parent = $(this).parents().get(0);
		// $(parent).remove();
	// });