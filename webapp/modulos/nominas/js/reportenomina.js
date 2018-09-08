$(document).ready(function(){

	$('.selectpicker').selectpicker({
		size: 4
	});

	$('#tablanominas').DataTable( { 
		"scrollX": true,
		"language": {
			"url": "js/Spanish.json"
		}
	});

	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#fechainicial").datepicker({
		maxDate: 365,
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onSelect: function(selected) {
			$("#final").datepicker("option","minDate", selected);
		}
	});
	$("#fechafinal").datepicker({ 
		dateFormat: 'yy-mm-dd',
		maxDate:365,
		numberOfMonths: 1,
		onSelect: function(selected) {
			$("#inicial").datepicker("option","maxDate", selected);
			$("#empleado").prop('disabled', true);
		}
	});  
});

function cancelarFactura(uuid,idNominatimbre){

	if(confirm("¿Esta seguro de cancelar esta Factura?")){
		$("#cargando"+idNominatimbre).show();
		$("#cancela"+idNominatimbre).hide();
		$.post("ajax.php?c=Nominalibre&f=cancelaReciboNomina",
		{
			uuid:uuid,
			idNominatimbre:idNominatimbre
		},
		function(resp){
			alert(resp);

			$("#cargando"+idNominatimbre).hide();
			$("#cancela"+idNominatimbre).show();
			$("#load").click();
		});
	}
}


function envioCorreos(){

	x=0;
	cadena='';
	$( "#tablanominas tr" ).each(function(index) {

		if($(this).attr('idemp')){
			xml=$(this).attr('xml');
			cadena+=$(this).attr('idemp')+'#.#'+$(this).attr('xml')+'#.#'+$(this).attr('nomemp')+'#.#'+$(this).attr('fechaini')+'#.#'+$(this).attr('fechafin')+'##.##';
			$.post("../cont/controllers/visorpdf.php",{
				name:xml,
				id:"temporales",
				nominas:1
			},function callback () {
			});
		}
	});
	$("#loading").fadeIn(500);
	$("#divmsg").load("mail.php", {cadena:cadena,m:1});
}

$(function() {

	$("#habilitar").click(function(event) {
		$("#fechainicial").prop('disabled', false);
		$("#fechafinal").prop('disabled', false);
	});

	$('#load').on('click', function() { 
		$(this).button('loading');
		if(!$("#fechainicial").val() || !$("#fechafinal").val()){
			alert("Seleccione una fecha.");
			$(this).button('reset'); 
		} else{
			$("#formfecha").submit();
		}  
	});
});


function mailNominas(xml,correo,fechaini,fechafin){

	if (correo=="") {
		correo="@netwarmonitor.com";
	}else{
		correo=correo;
	}
	var msg = "Registre el correo electrónico a quién desea enviarle el XML:";
	var a = prompt(msg,correo);
	if(a!=null){
		$.post("../cont/controllers/visorpdf.php",{
			name:xml,
			id:"temporales",

			nominas:1
		},function (resp){
			$("#loading").fadeIn(500);
			$("#divmsg").load("mail.php?a="+a, {xml:xml,fechaini:fechaini,fechafin:fechafin});
		});
	}
}
function reutilizaFactura(idNominatimbre){
	window.parent.preguntar=false;
	window.parent.quitartab("tb2356",2356,"  Nomina Manual ");
	window.parent.agregatab("../../modulos/nominas/index.php?c=Nominalibre&f=viewNomina&idnomina="+idNominatimbre,"  Nomina Manual ","",2356);
	window.parent.preguntar=true;
}
