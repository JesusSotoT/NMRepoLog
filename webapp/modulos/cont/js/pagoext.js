
$(document).ready(function(){
$("#proveedor,#moneda,#tipocambio,#xml").select2({
         width : "150px"
        });
        $("#banco").select2({
         width : "150px"
        });
        
});
function fechadefault(){
	if($('#idperiodo').val()<10){ fec=0+$('#idperiodo').val(); }else{ fec=$('#idperiodo').val(); }
	//$("#fecha").val($("#ejercicio").val()+'-'+fec+'-01');
	   
   $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fecha").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        defaultDate:$("#ejercicio").val()+'-'+fec+'-01'
        
    });
}
function checa() {  
		var req_vi = $("input[name='radio']:checked").val();
		if(req_vi==1){
			$('#xmlsube').hide();
			$('#xml').show();
		}else if(req_vi==2){
			$('#xml').hide();
			$('#xmlsube').show();

		}
    }
   
// function agregacli(){
	// var banco=$("#banco").val();
	// var prove=$('#prove').val();
	// var importe=$('#importe').val();
	// var concepto=$('#concepto').val();
	// var xml=$('#provision').val();
	// //alert(banco);
	// $.post('index.php?c=CaptPolizas&f=tablaprov',{
		// banco:banco,
		// proveedor:prove,
		// importe:importe,
		// concepto:concepto,
		// xml:xml},
		// function(respues) {
// window.location.reload();			// var r=respues.split('-_-');
			// // //alert(r[1]);
			// // $('#datos tbody').html(r[1]);
	// });
// }
function borra(cont){
	$.post('index.php?c=CaptPolizas&f=borra2',{
		cont:cont},
		function(respues) {
window.location.reload();
	});
}
function guarda(){
		var formapago = $("#formapago").val();
		var beneficiario = $("#beneficiario").val();
		var numero = $("#numero").val();
		var rfc = $("#rfc").val();
		var numtarje = $("#numtarje").val();
		var listabanco = $("#listabanco").val();
		var bancoorigen = $('#listabancoorigen').val();
		
		var periodoactual=$('#idperiodo').val();
		var fecha=$('#fecha').val();
		if(fecha!=""){
			var fec;
			var sep=fecha.split('-');
			if($('#idperiodo').val()<10){ fec=0+$('#idperiodo').val(); }else{ fec=$('#idperiodo').val(); }
		
			if(fec==sep[1] && sep[0]==$("#ejercicio").val()){$("#load").show(); $("#agregaprevio").hide();
			$.post('index.php?c=automaticasMonedaExt&f=guardaPago',{
				fecha:fecha,
				formapago:formapago,
				beneficiario:beneficiario,
				numero:numero,
				rfc:rfc,
				numtarje:numtarje,
				listabanco:listabanco,
				bancoorigen:bancoorigen,
				unsolobanco:$("#unsolobanco").val()

				},
				function(respues) {
					var r=respues.split('-_-');
					 if(r[1]==1){ $("#load").hide();$("#agregaprevio").show();
						 
						 if (confirm("Poliza generada Correctamente!. \n Desea ver la poliza?")){
						 	$.post('ajax.php?c=CaptPolizas&f=consultaultima',{},
						 		function (idpoli){ 
						 			window.location.reload();
						 			window.parent.preguntar=false;
						 			window.parent.quitartab("tb0",0,"Polizas");
						 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id='+idpoli+'&im=2&prv='+beneficiario,'Polizas','',0);
									window.parent.preguntar=true;
						 		});
						 }else{
						 	window.location.reload();
						 }
					}else if(r[1]==2){
						alert("Error al generar Poliza"); 
						$("#load").hide();$("#agregaprevio").show();
				}else if(r[1]==0){ 
						 alert("No se pueden generar polizas automaticas en el periodo 13.");
						 $("#load").hide();$("#agregaprevio").show();
					}
			});
		}else{
			
			alert("Elija una fecha acorde al periodo y ejercicio actual (mes:"+mes($('#idperiodo').val())+",año:"+$("#ejercicio").val()+")");
			 $('#fecha').css("border-color","red");
		}
	}else{
		alert("Elija una fecha para la poliza");
		$('#fecha').css("border-color","red");
	}
}

// function valida(e) { // 1 solo numeros
		// tecla = (document.all) ? e.keyCode : e.which; // 2
		// if (tecla==8) return true; // 3
	// patron = /\d/; // Solo acepta números 4
		// te = String.fromCharCode(tecla); // 5
		// return patron.test(te); // 6
	// }
var nav4 = window.Event ? true : false; 

function valida(e) { // 1
		var key = nav4 ? e.which : e.keyCode;  
return (key <= 13 || (key >= 48 && key <= 57 || key==46)); 
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
function beneficiari(){
	var proveedor = $("#proveedor").val();
	if(proveedor!=0){
		var parsea = proveedor.split('/');
		if (parsea[0].indexOf('-')==-1) {
			$('#beneficiario').val(parsea[1]);
				//$("#beneficiario").val(parsea[1]);
				cuentarbolbenefi();
		}else{
				alert("Para provedores del Arbol de Cuentas elija un Beneficiario");
				//$("#beneficiario").val(0);
				$('#beneficiario option[value=0]').attr('selected','');
				//$("#beneficiario").select2({width : "150px"});
				//$("#beneficiario").attr("disabled",false);
				$("#numtarje").val("");
				$("#rfc").val("");
			
			
		}
	}else{
		$('#beneficiario').val(0);
		$("#numtarje").val("");
		$("#rfc").val("");
	}
// 	
	
	
}
function cuentarbolbenefi(){
		var idprv =$("#beneficiario").val();
		
	$.post('index.php?c=CaptPolizas&f=bancosprove',{
		idprove:idprv
		},function (resp){
			$("#numtarje").val("");
			
			//$("#beneficiario").attr("disabled",true);
			
			var separa = resp.split("-_-");
			$("#listabanco").val(" ");
			if (separa[1].indexOf('*')==-1) {
				$("#listabanco").html(separa[1]);
				$("#beneficiario").val(idprv);
				//$("#beneficiario").attr("readonly","");
			}else{
				$("#listabanco").html(separa[1]);
				alert("El proveedor no tiene bancos asociados");
				$("#beneficiario").val(0);
				mandabancos();
			}
			//$("#listabanco").val(0);
			//$("#beneficiario").val(idprv);
			$.post("ajax.php?c=CaptPolizas&f=datosprove",{
			idprove:idprv
			},function (resp){
				if(resp!=0){
					$("#rfc").val(resp);
					
				}
				numerocuent();
			});
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
function mandabancos(){
	$("#beneficiario").val(0);
	$("#numtarje").val("");
	$("#rfc").val("");
	$("#proveedor").val(0);
	$("#numero").val("");
	//$("#beneficiario").select2({width : "150px"});
	$("#proveedor").select2({width : "150px" });
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=275&ticket=testing","Bancos de Proveedor","",1706);

}
function cuentabancarias(){
	
	var cuentacontable = $('#banco').val().split('/');
	$.post('index.php?c=CaptPolizas&f=cuentasbancarias',
	{ cuentacontable:cuentacontable[0] },
	function (resp){
		var separaprimero = resp.split('-_-');
		if(separaprimero[1]!=0){
			var separa = separaprimero[1].split('->');
			$('#listabancoorigen').html(separa[0]);
			$('#numorigen').val(separa[1]);
		}else{
			if(cuentacontable!=0){
				alert("La Cuenta Contable no tiene una Cuenta bancaria por favor agreguela...");
				mandacuentabancaria();
			}else{
				$('#listabancoorigen').val(0);
				$('#numorigen').val('');
			}
		}
	});
}

function validacampos(){
	if($('#xml').val()==0 || !$('#xml').val()){
		alert('Seleccione una factura');
		return false;
	}
	if($('#banco').val()==0){
		alert('Elija un banco');
		return false;
	}
	if($('#tipocambio').val()==0 && $('#tipocambio2').val()=="" ){
		alert('Seleccione o Escriba un tipo de Cambio');
		return false;
	}
	if($('#proveedor').val()==0){
		alert('Elija un proveedor');
		return false;
	}
}
function mandacuentabancaria(){
	$('#listabancoorigen').val(0);
	$('#numorigen').val('');
	$("#banco").val(0);
	$("#banco").select2({width : "150px" });

	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=280&ticket=testing","Cuentas Bancarias","",1804);
}
function mandaasignarcuenta(){
	window.parent.agregatab("../../modulos/cont/index.php?c=Config&f=configAccounts","Asignación de Cuentas","",1647);
} 
function rellena(relleno,valor){
	$("#"+relleno).val($("#"+valor).val());
}
function calculaIVAIEPS(inputallenar,cont,tipocambio){
		//alert($("#"+valor).val());
		var calculoiva = (($("#imporinput2"+cont).val())/1.16)* .16;
		$("#ivacobradoinput"+cont).val(calculoiva.toFixed(2));
		$("#ivapendienteinput"+cont).val(calculoiva.toFixed(2));
		$("#ivamxnintro"+cont).html(( (calculoiva).toFixed(2) * tipocambio).toFixed(2) );
		$("#iva2mxnintro"+cont).html(( (calculoiva).toFixed(2) * tipocambio).toFixed(2) );

		
		
		var calculoieps = (($("#imporinput2"+cont).val())/1.08)* .08;
		$("#icobradoinput"+cont).val(calculoieps.toFixed(2));
		$("#ipendienteinput"+cont).val(calculoieps.toFixed(2));
		$("#iepsmxnintro"+cont).html(( (calculoieps).toFixed(2) * tipocambio).toFixed(2) );
		$("#ieps2mxnintro"+cont).html(( (calculoieps).toFixed(2) * tipocambio).toFixed(2) );
		
		
		$("#imporinput"+cont).val($("#imporinput2"+cont).val());
		$("#impormxnintro"+cont).html( ($("#imporinput2"+cont).val() * tipocambio).toFixed(2) );
		$("#impor2mxnintro"+cont).html( ($("#imporinput2"+cont).val() * tipocambio).toFixed(2) );
		
	}
	
function consultaTipoCambio(id){
	if(id!=1){
		$("#consul").show();
		periodo = $("#inicio_mes").html().split('-');
		$.post("ajax.php?c=automaticasMonedaExt&f=consultaTipoCambioPago",{
			idmoneda:id,
			periodot:periodo[2]+"-"+periodo[1]
		},function (resp){
			$("#consul").hide();
			window.location.reload();
		});
	}
	
}	
function cancela(){
	if(confirm("Esta seguro de eliminar los datos capturados?")){
		$.post('ajax.php?c=automaticasMonedaExt&f=cancelaPago',{},function(){ window.location.reload();});
	}
}
function cambiaintro(){
	$("#int2").show();
	$("#tipocambio").val(0);
	$("#int").hide();
	$(".t1").hide();
	$(".t2").show();
	
}
function listadoin(){
	$("#int").show();
	$("#int2").hide();
	$(".t1").show();
	$(".t2").hide();
}
function numeros(e){
  var keynum = window.event ? window.event.keyCode : e.which;
if ((keynum == 8) || (keynum == 46))
return true;
 
return /\d/.test(String.fromCharCode(keynum));
}
