$(document).ready(function(){
	$("#cuentabancaria,#periodo,#idejercicio").select2({width : "150px"});
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
    
   $("#desde,#desde2").datepicker({
	 	maxDate: 0,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#hasta,#hasta2").datepicker("option","minDate", selected);
        }
    });
    
    $("#hasta,#hasta2").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:365,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#desde,#desde2").datepicker("option","maxDate", selected);
        }
    });
	$(".number").number(true,2);
	
	$("#buscarvalor").keyup(function(){ console.log($(this).val());
		if( $(this).val() != "")
		{
			$("#tmovbancosd tbody>tr").hide();
			$("#tmovbancosd td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			$("#tmovbancosd tbody>tr").show();
		}
	});
	$("#buscarvalor2").keyup(function(){ console.log($(this).val());
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
	
	
	
		$.extend($.expr[":"], 
	{
	    "contains-ci": function(elem, i, match, array) 
		{
			return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
		}
	});
});

function abririmportacion(){
	window.parent.agregatab('../../modulos/bancos/index.php?c=importarEstadoCuenta&f=verImport','Importar Estado Bancario','',1808);
}
function conciliar(){
	$("#load2").show();
	$.post("ajax.php?c=conciliacionAcontia&f=borrarDatos",{},
	function callback(){
		$.post("ajax.php?c=conciliacionAcontia&f=conciliados",{
		idbancaria:$("#cuentabancaria").val(),
		periodo:$("#periodo").val(),
		ejercicio:$("#idejercicio").val()
		},function (resp){
			if(resp==1){
				if(confirm("La conciliacion ya fue finalizada\nDesea consultar el Reporte?")){
					window.parent.agregatab("../../modulos/cont/index.php?c=conciliacionAcontia&f=ReporteConciliacion&periodo="+$("#periodo").val()+"&cuentabancaria="+$("#cuentabancaria").val()+"&ejercicio="+$("#idejercicio").val(),'Reporte Conciliacion','',1915);
					$.post("ajax.php?c=conciliacionAcontia&f=borrarDatos",{},function(){
						window.location.reload();
					});
				}else{
					$.post("ajax.php?c=conciliacionAcontia&f=borrarDatos",{},function(){
						window.location.reload();
					});
				}
				
			}else{
				window.location.reload();
			}
		});
	});
	
		
	
}


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

$(function () { 
    $("#conciliarmov").click(function () 
    {
    		if(confirm("Esta seguro de conciliar los datos agrupados?")){
    			var max = $("#numregistros").val();
    			var arrayidBancos = Array();
    			//console.log("max",max);
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
		         	 $.post("ajax.php?c=conciliacionAcontia&f=conciliaMovimientos",{
		        			idMovPoliza:$(this).val(),
		        			idMovBanco:idmovBanco
		        		},function callback(){
		        			 pro++;
		        			if( pro==numDivs){
		        				max -=1;
		        			}
		        				if(max==0  && pro==numDivs){
		        					$.post("ajax.php?c=conciliacionAcontia&f=verificaMontosConciliados",{
		        						idMovBancos:arrayidBancos
		        					},function callback(r){
		        						if(r!=''){
		        							$("#load").hide();
		        							alert("La suma de los siguientes Mov. Bancarios no cuadraron.\n"+r);
		        							conciliar();
		        						}else{
		        							conciliar();
		        						}
		        					});
		        				}
		        		});
		        	});
		        
		       }else{ 
		       	 	max -=1;
		       		if(max ==0){
	        				conciliar();
	        			}
		        }
		      
		     });
    		}
    		
    });
});
function capturapoli(nameejer){
	$("#load").show();
	$.post("ajax.php?c=CaptPolizas&f=CambioEjerciciosession",{
		Periodo:$("#periodo").val(),
		NameEjercicio:nameejer
	},function (resp){
		$.post("ajax.php?c=CaptPolizas&f=CreateNewPoliza",{
	    		Organizacion: 1,
	    		Ejercicio: $("#idejercicio").val(),
	    		Periodo: $("#periodo").val()
	  		 },
	  		 function(data)
	  		 {
	  		 	if(parseInt(data))
	  		 	{	$("#load").hide();
	  		 		window.parent.preguntar=false;
					window.parent.quitartab("tb143",143,"Captura");
	  		 		window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=Capturar','Captura','',143);
					window.parent.preguntar=true;

	  		 	}
	  		 	else
	  		 	{	$("#load").hide();
	  		 		alert("Para generar la poliza del periodo 13 es necesario configurar la cuenta de saldos en la pantalla de Asignación de Cuentas.");
	  		 	}
  		 });
  		 
  	 });
}
function saldoInicial(val){//com este restarle
	$("#load3").show();
	$.post("ajax.php?c=conciliacionAcontia&f=calculaSaldoEmpresa",{
		saldo:val,
		idbancaria:$("#cuentabancaria").val(),
		periodo:$("#periodo").val(),
		ejercicio:$("#idejercicio").val()
	},function (resp){
		$("#saldoEmpresa").html(resp);
		$("#load3").hide();
	});
	
}
$(function () {
    var totalcheque=0;var totalmideposito=0;
        $("#chequescircula tbody tr").each(function (index) 
        {
            $(this).children("td").each(function (index2) 
            {
                switch (index2) //indice
                {
                    case 2: totalcheque += parseFloat($(this).text().replace(/,/gi,''));
                           
                }
            });
        });
          $("#totalcheque").html(totalcheque.toFixed(2));
          $("#chequessaldo").html(totalcheque.toFixed(2));
           $("#chequessaldo,#totalcheque").number(true,2);
           
            $("#totalcheque2").html(totalcheque.toFixed(2));
          $("#chequessaldo2").html(totalcheque.toFixed(2));
           $("#chequessaldo2,#totalcheque2").number(true,2);
    // mis depositos
         
           $("#misdepositos tbody tr").each(function (index) 
        {
            $(this).children("td").each(function (index2) 
            {
                switch (index2) //indice
                {
                    case 2: totalmideposito += parseFloat( replaceAll( $(this).text(),',',''));
                           
                }
            });
        });
          $("#totalmideposito").html(totalmideposito.toFixed(2));
          $("#depositossaldo").html(totalmideposito.toFixed(2));
          $("#depositossaldo,#totalmideposito").number(true,2);
          
           $("#totalmideposito2").html(totalmideposito.toFixed(2));
          $("#depositossaldo2").html(totalmideposito.toFixed(2));
          $("#depositossaldo2,#totalmideposito2").number(true,2);
       // sus cargos
       var totalsuscargos=0;
          $("#cargosbanco tbody tr").each(function (index) 
        {
            $(this).children("td").each(function (index2) 
            {
                switch (index2) //indice
                {
                    case 2: totalsuscargos += parseFloat( replaceAll($(this).text(),',','') );
                           
                }
            });
        });
          $("#totalcargosbanco").html(totalsuscargos.toFixed(2));
          $("#totalcargosbancosaldo").html(totalsuscargos.toFixed(2));
           $("#totalcargosbancosaldo,#totalcargosbanco").number(true,2);
           
           $("#totalcargosbanco2").html(totalsuscargos.toFixed(2));
          $("#totalcargosbancosaldo2").html(totalsuscargos.toFixed(2));
           $("#totalcargosbancosaldo2,#totalcargosbanco2").number(true,2);
       // banco depositos no registrados
       var totalbanosdepositos=0;
          $("#bancodepositos tbody tr").each(function (index) 
        {
            $(this).children("td").each(function (index2) 
            {
                switch (index2) //indice
                {
                    case 2: totalbanosdepositos += parseFloat( replaceAll($(this).text(),",","") );
                           
                }
            });
        });
          $("#bancodepositostotal").html(totalbanosdepositos.toFixed(2));
          $("#totalbancodepositosaldo").html(totalbanosdepositos.toFixed(2));
          $("#totalbancodepositosaldo,#bancodepositostotal").number(true,2);
          
          $("#bancodepositostotal2").html(totalbanosdepositos.toFixed(2));
          $("#totalbancodepositosaldo2").html(totalbanosdepositos.toFixed(2));
          $("#totalbancodepositosaldo2,#bancodepositostotal2").number(true,2);
     var operacionsaldo=0;
	//nuestro saldo
	var saldoEmpresa = parseFloat(  replaceAll($("#saldoEmpresa").html(),",","") );
	var cargosbanco = parseFloat(  replaceAll($("#totalcargosbancosaldo").html(),",","") );
	var depositosbanco = parseFloat(  replaceAll($("#totalbancodepositosaldo").html(),",","") );
	operacionsaldo = saldoEmpresa - cargosbanco + depositosbanco;
	if(!operacionsaldo){operacionsaldo=0;}
	$("#totalnuestrosaldo").html(operacionsaldo.toFixed(2));
	$('#totalnuestrosaldo').number( true, 2 );
	$("#totalnuestrosaldo2").html(operacionsaldo.toFixed(2));
	$('#totalnuestrosaldo2').number( true, 2 );
	
//saldo banco	
	var operacionbancos=0;
	var saldobanc = parseFloat( replaceAll($("#saldoEstadoCuenta").html(),",","") );
	var cheques = parseFloat( replaceAll($("#chequessaldo").html(),",","") );
	var nuestrodeposito = parseFloat(  replaceAll($("#depositossaldo").html(),",","") );
	operacionbancos = saldobanc - cheques + nuestrodeposito;
	if(!operacionbancos){ operacionbancos=0;}
	$("#saldobancototal").html(operacionbancos.toFixed(2));
	$("#saldobancototal").number(true,2);
	$("#saldobancototal2").html(operacionbancos.toFixed(2));
	$("#saldobancototal2").number(true,2);
         
});
function Salir(){
		$.post("ajax.php?c=conciliacionAcontia&f=borrarDatos",{
		},function(){
			window.parent.quitartab("tb1907",1907,"Realizar Conciliacion");
		});
	
}
function descartar(){
	$("#finconciliacion2").hide();
	$("#loaddescar2").show();
	var copiar = [];
	for(var i = 1 ; i<=$(".descartar").length; i++)
	{
		if($("#descartar-"+i).is(':checked'))
		{
			// alert($("#descartar-"+i).val());
			copiar.push($("#descartar-"+i).val());
		}
		
	}
	$.post("ajax.php?c=conciliacionAcontia&f=descartarMov",{
		descartar:copiar
	},function(resp){
		if(resp==1){
			conciliar();
		}else{
			alert("Ocurrió un problema con tu conexión descarta de nuevo");
		}
		
	});

}

function buttonclick(v)
	{
		$("."+v).click();
	}
function buttondesclick(v)
	{
		$("."+v).attr('checked',false);
	}

function volverAdescartar(){
	$('#volverdescartar').modal('show');	
}
function conservar2(){
	$.post("ajax.php?c=conciliacionAcontia&f=borrarDatos",{
	},function(){
		conciliar();
	});
}

function sumaMov(){
	$('#sumamov').modal('show');
}
$(function () { 
    $("#conciliarmovsuma").click(function () 
    {
    		if(confirm("Esta seguro de conciliar los datos agrupados?")){
    			var max = $("#numregistrossuma").val();
    			var arrayidBancos = Array();
    			var arrayidPolizas = Array();
    			
    			//console.log("max",max);
    			$("#loadsuma").show();$("#conciliarmovsuma").hide();
    			var cont=0; 
    			$("div[data-role=movpolizas] ").each(function (index) { //div saco id
	          var idmovPoliza = $(this).attr("data-value");
	          
	          if($("div[data-value="+idmovPoliza+"] li").val()){
	          	var numDivs = (($("div[data-value="+idmovPoliza+"] .out").length));
	          	//max -=1; 
	          	var pro=0;
	          	arrayidPolizas.push(idmovPoliza);
		         $("div[data-value="+idmovPoliza+"] li").each(function (index) {
		         		arrayidBancos.push($(this).val());
		         	 $.post("ajax.php?c=conciliacionAcontia&f=conciliaMovimientos",{
		        			idMovPoliza:idmovPoliza,
		        			idMovBanco:$(this).val()
		        		},function callback(){
		        			 pro++;
		        			if( pro==numDivs){
		        				max -=1;
		        			}
		        				if(max==0  && pro==numDivs){
		        					//console.log("concilia");
		        					$.post("ajax.php?c=conciliacionAcontia&f=verificaMontosConciliadosPolizas",{
		        						idMovPolizas:arrayidPolizas
		        					},function callback(r){
		        						if(r!=''){
		        							$("#loadsuma").hide();$("#conciliarmovsuma").show();
		        							alert("La suma de los siguientes Mov. Bancarios no cuadraron.\n"+r);
		        							conciliar();
		        						}else{
		        							conciliar();
		        						}
		        					});
		        				}
		        		});
		        	});
		        
		       }else{ 
		       	 	max -=1;
		       		if(max ==0){
		       			//console.log("concilia fuera");
	        				conciliar();
	        			}
		        }
		      
		     });
    		}
    		
    });
});
function replaceAll( text, busca, reemplaza ){

  while (text.toString().indexOf(busca) != -1)

      text = text.toString().replace(busca,reemplaza);

  return text;

}
function verificaConciliacionBancos(){
	$.post("ajax.php?c=conciliacionAcontia&f=verificaConciliacionBancos",{
		idbancaria:$("#cuentabancaria").val(),
		periodo:$("#periodo").val(),
		ejercicio:$("#idejercicio").val()
	},function(resp){
		if(resp==1){
			alert("Aun no Finaliza la conciliacion en el Modulo de Bancos\nDebe Finalizar para continuar");
		}if(resp==2){
			conciliar();
		}if(resp==0){
			alert("No tiene un estado de cuenta de ese periodo");
		}
	});
}
