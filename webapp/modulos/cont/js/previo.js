/**
 * @author Carmen Gutierrez
 */
$(document).ready(function(){
	if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
	$("#fecha").val($("#ejercicio").val()+'-'+fec+'-01').attr('readonly','readonly');
	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fecha").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
    });
    $("#CuentaProveedores,#CuentaClientes").select2({width : "150px"});
    
    
    
});
function fecha(){
	if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
	$("#fecha").val($("#ejercicio").val()+'-'+fec+'-01').attr('readonly','readonly');
	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fecha").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
    });
}
function guardaprovi(){// ver q pasa con los totales y con cuenta proeveedorrs
	var isr=$('#ISR').val();
	var iva=$('#IVA').val();
	if($('#ivaingre').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA pendiente de cobro");  return false;}
	if($('#ieps').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS pendiente de cobro");  return false;}
	//if($('#ish').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas ISH");  return false;}
	
	
	if($('#iepsegre').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS pendiente de pago");  return false;}
	if($('#ishegre').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas ISH");  return false;}
	if($('#ivaegre').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA pendiente de pago");  return false;}
	
	// if($('#IVA').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA");  return false;}
	// if($('#ISR').val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas ISR");  return false;}

	var isr=$('#ISR').val();
	var iva=$('#IVA').val();
	if(!$('#ISR').val()){ isr=0; }
	if(!$('#IVA').val()){ iva=0;}
	var ieps=0;var impieps=0;
	var ish=0; var impish=0;
	
	var totalisr=($('#totalISR').html());
	var totaliva=($('#totalIVA').html());
	var ivaingre =-1;
	var ivaegre = -1;
	if(!$('#totalISR').html()){  totalisr=0;}
	if(!$('#totalIVA').html()){ totaliva=0;}
	
		var UUID=$('#UUID').val();
		var referencia=$('#referencia').val();
	    var comprobante=$('#comprobante').val();
	    var fecha=$('#fecha').val();
	    
		var abonoingre	=$('#subtotal').html(); 
		var abonoingre2= $('#importe').html(); 
		var cargoingre=	$('#total').html();
		
		var cargoegre	=$('#subtotalegre').html(); 
		var cargoegre2= $('#importeegre').html(); 
		var abonoegre=	$('#totalegre').html();
		
		var rfcemisor=$('#rfcemisor').val(); 
		var nombreemisor=$('#nombreemisor').val();
		var municipioemisor=$('#municipioemisor').val(); 
		var calleemisor = $('#calleemisor').val(); 
		var noExterioremisor = $('#noExterioremisor').val();
		
		var rfcreceptor = $('#rfcreceptor').val(); 
		var nombrereceptor = $('#nombrereceptor').val();
		var municipiorecep = $('#municipiorecep').val(); 
		var callerecep = $('#callerecep').val(); 
		var noExteriorrecep = $('#noExteriorrecep').val(); 
		var coloniarecep = $('#coloniarecep').val();
		var codigoPostalrecep = $('#codigoPostalrecep').val();

		var cuentaingre = $('#cuentaingre').val(); 
		if($('#ivaingre').val()){ ivaingre = $('#ivaingre').val(); }else{ abonoingre2=0;}
		 
		var cuentaegre = $('#cuentaegre').val(); 
		if($('#ivaegre').val()){ivaegre = $('#ivaegre').val();}else { cargoegre2=0;}
		
		var CuentaClientes=$('#CuentaClientes').val();
		var CuentaProveedores=$('#CuentaProveedores').val();
		var sucursal;
		var segmento;
		
		
		if(comprobante==1){
			if($('#ieps').val()){ ieps=$('#ieps').val(); impieps=$('#importeieps').html();}
			if($('#ish').val()){ ish=$('#ish').val(); impish=$('#importeish').html();}
			
			 sucursal = $('#sucursal').val();
			 segmento = $('#segmento').val();
			if(CuentaClientes=='-1'){ alert("Elija una cuenta");  return false;}
			
			//valida cuentas asignadas
			
			var abono = parseFloat(abonoingre)	+ parseFloat(abonoingre2) + parseFloat(impieps) + parseFloat(impish);
			var cargo = parseFloat(cargoingre) + parseFloat(totalisr) + parseFloat(totaliva) ;
			
			if(abono.toFixed(2)!=cargo.toFixed(2)){ alert("La poliza no esta cuadrada");  }
		}else if(comprobante==2){
			if($('#iepsegre').val()){ ieps=$('#iepsegre').val();impieps=$('#importeiepsegre').html(); }
			if($('#ishegre').val()){ ish=$('#ishegre').val(); impish=$('#importeishegre').html();}

			 sucursal = $('#sucursalegre').val();
			 segmento = $('#segmentoegre').val();
			if(CuentaProveedores=='-1') { alert("Elija una cuenta");  return false;}
			var cargo =  parseFloat(cargoegre)	+ parseFloat(cargoegre2) + parseFloat(impieps) + parseFloat(impish);
			var abono = parseFloat(abonoegre) + parseFloat(totalisr) + parseFloat(totaliva);
			if(abono.toFixed(2)!=cargo.toFixed(2)){ alert("La poliza no esta cuadrada");  }
			
		}
		
		var fecha=$('#fecha').val();
		if(fecha!=""){
			var fec;
			var sep=fecha.split('-');
			if($('#idperio').val()<10){ fec=0+$('#idperio').val(); }else{ fec=$('#idperio').val(); }
		
			if(fec==sep[1] && sep[0]==$("#ejercicio").val()){
			$("#load").show(); $("#guardar").hide();
				$.post('index.php?c=CaptPolizas&f=guardaprovi',{
					comprobante:comprobante,
					abonoingre:abonoingre,
					abonoingre2:abonoingre2,
					cargoingre:cargoingre,
					cargoegre:cargoegre,
					cargoegre2:cargoegre2,
					abonoegre:abonoegre,
					rfcemisor:rfcemisor,
					nombreemisor:nombreemisor,
					municipioemisor:municipioemisor,
					calleemisor:calleemisor,
					noExterioremisor:noExterioremisor,
					rfcreceptor:rfcreceptor,
					nombrereceptor:nombrereceptor,
					municipiorecep:municipiorecep,
					callerecep:callerecep,
					noExteriorrecep:noExteriorrecep,
					coloniarecep:coloniarecep,
					codigoPostalrecep:codigoPostalrecep,
					cuentaingre:cuentaingre,
					ivaingre:ivaingre,
					cuentaegre:cuentaegre,
					ivaegre:ivaegre,
					UUID:UUID,
					referencia:referencia,
					CuentaClientes:CuentaClientes,
					CuentaProveedores:CuentaProveedores,
					fecha:fecha,
			    	totalisr:totalisr,
					totaliva:totaliva,
					isr:isr,
				    iva:iva,
					sucursal:sucursal,
					segmento:segmento,
					ieps:ieps,//cuenta
					impieps:impieps,
					ish:ish,//cuenta
					impish:impish,
					xml:$('#xml').html()
				},function (resp){
				  var r=resp.split('-_-');
					if(r[1]==0){  
						 $("#load").hide();  $("#guardar").show();
						 var id=0;
						 if (confirm("Poliza generada Correctamente!. \n Desea ver la poliza?")){
						 	$.post('ajax.php?c=CaptPolizas&f=consultaultima',{},
						 		function (idpoli){ 
						 			window.location="index.php?c=CaptPolizas&f=verprovision";
						 			window.parent.preguntar=false;
						 			window.parent.quitartab("tb0",0,"Polizas");
						 			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id='+idpoli+'&im=3','Polizas','',0);
									window.parent.preguntar=true;
						 		});
						 }else{
						 	window.location="index.php?c=CaptPolizas&f=verprovision";
						 }
						 
					}else if (r[1]==1){
						 
						 alert("Fallo al general poliza!."); window.location="index.php?c=CaptPolizas&f=verprovision";
						$("#load").hide(); $("#guardar").show();
					}
				});
		}else{
			alert("Elija una fecha acorde al periodo y ejercicio actual (mes:"+mes($('#idperio').val())+",año:"+$("#ejercicio").val()+")");
			 $('#fecha').css("border-color","red");
			
		}
	 }else{
		 alert("Elija una fecha para la poliza");
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
  
  function iracuenta(){
	window.parent.agregatab('../../modulos/cont/index.php?c=AccountsTree','Cuentas','',145)
	//window.location='../../modulos/cont/index.php?c=AccountsTree';
}

function actualizaCuentas()
{
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=actualizaprovprovecuentas",
			function(datos)
			{
					$('#CuentaProveedores').html(datos);
					$("#CuentaProveedores").select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});

						 //alert(datos)

}  
function actualizaCuentascli()
{
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=actualizaprovclicuentas",
			function(datos)
			{
					$('#CuentaClientes').html(datos);
					$("#CuentaClientes").select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});

						 //alert(datos)

}
function mandaasignarcuenta(){
	window.parent.agregatab("../../modulos/cont/index.php?c=Config&f=configAccounts","Asignación de Cuentas","",1647);
}