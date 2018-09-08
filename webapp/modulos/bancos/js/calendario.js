$(document).ready(function(){
	
		$("#cuenta,#moneda").select2({ width: '150px' });

	
    $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	
	 $("#fechainicio").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#final").datepicker("option","minDate", selected);
        }
    });
    $("#fechafin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:365,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#inicial").datepicker("option","maxDate", selected);
        }
    });
 });
function cuentasPorMoneda(cuenta){
	$("#progres").show();
	$.post("ajax.php?c=Flujo&f=cuentasPorMoneda",{
		idmoneda:$("#moneda").val()
	},function (resp){
		$("#cuenta").html(resp);
		if(cuenta){
			$("#cuenta").val(cuenta).select2({width : "150px"});
		}else{
			$("#cuenta").select2({width : "150px"});
		}
		$("#progres").hide();
	});
}

$(function() {
   
$('#load').on('click', function() { 
   
   $(this).button('loading');
   var status = true;
   
   if( !$("#fechafin").val() || !$("#fechainicio").val()){
   	alert("Seleccione la fecha");
   	status = false; 
   	$(this).button('reset');
   }
   if($("#moneda").val() == 0){
   	alert("Seleccione una moneda");
   	status = false;
   	$(this).button('reset');
   }
   if(status){
   	$("#form").submit();
   }
   
 });
});  
  function edicion(documento,id){
		var link = "";
		if(documento=="Cheques"){
			link = "index.php?c=Cheques&f=vercheque&editar="+id;
		}else if(documento=="Egresos"){
			link = "index.php?c=Cheques&f=verEgresos&editar="+id;	
		}else if(documento == "Ingresos"){
			link = "index.php?c=Ingresos&f=verIngreso&editar="+id;
		}
		else if(documento == "IngresosP"){
			link = "index.php?c=Ingresos&f=verIngresoNodep&editar="+id;
		}
		else if(documento == "depositos"){
			link = "index.php?c=Ingresos&f=verDeposito&editar="+id;
		}
		window.parent.preguntar=false;
		window.parent.quitartab('tb1',1,"Edicion");
		window.parent.agregatab("../../modulos/bancos/"+link,'Edicion','',1);
		window.parent.preguntar=true;
	}
	function nuevo(documento){
		var link = "";
		if(documento=="Cheques"){
			link = "index.php?c=Cheques&f=vercheque";
		}else if(documento=="Egresos"){
			link = "index.php?c=Cheques&f=verEgresos";	
		}else if(documento == "Ingresos"){
			link = "index.php?c=Ingresos&f=verIngreso";
		}
		else if(documento == "IngresosP"){
			link = "index.php?c=Ingresos&f=verIngresoNodep";
		}
		else if(documento == "depositos"){
			link = "index.php?c=Ingresos&f=verDeposito";
		}
		window.parent.preguntar=false;
		window.parent.quitartab('tb1',1,"Nuevo");
		window.parent.agregatab("../../modulos/bancos/"+link,'Nuevo','',1);
		window.parent.preguntar=true;
	}
  
