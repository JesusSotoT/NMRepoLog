$(document).ready(function() {

	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#fechabaja").datepicker({
		dateFormat : 'yy-mm-dd',
		onSelect : function(selected) {
			diastranscurridos(selected);
			
		}
	});
	 $("#finiquito").on('click', function() {
		var separa = $("#fechabaja").val().split("-");
		
		$.post("ajax.php?c=Prenomina&f=validaExtraordinario",{
			ejercicio:separa[0]
		},function (resp){
			if(resp>0){
				var btnguardar = $(this);
 				btnguardar.button("loading");
 				$("#formfiniquito").submit();
			}else{
				alert("No puede procesar el finiquito sin crear un periodo extraordinario");
			}
		});
		
      	
		 //btnguardar.button('reset');
	});
});
function diastranscurridos(fechafin) {

	$.post("ajax.php?c=Prenomina&f=DatosLaboradosEmpleadoFiniquito", {
		fechafiniquito : fechafin,
		fechaAlta : $("#fechaalta").val()
	}, function(resp) {
		var res = JSON.parse(resp);
		$("#diasano").val(res.laboradosenAno);
		$("#antiguedad").val(res.antiguedad);
		causasConceptos( $("#causa").val() );
	});
	
}

function datosEmp() {
	var idEmpleado = $("#empleado").val();
	$("#nombreempleado").val($("#empleado option:selected").html());
	if (idEmpleado != 0) {
		$("#loade").show();
		$.post("ajax.php?c=Prenomina&f=datosEmpleadoFiniquito", {
			idEmpleado : idEmpleado,
		}, function(resp) {
			var separa = resp.split("/");
			$("#sueldo,#salariobase").val(separa[1]);
			$("#contrato").val(separa[0]);
			$("#fechaalta").val(separa[2]);
			$("#sdi").val(separa[3]);
			$("#loade").hide();
			
			if($("#fechabaja").val()){
				diastranscurridos($("#fechabaja").val());
			}
		});
	} else {
		$("#sueldo,#salariobase").val("");
		$("#contrato").val("");
		$("#fechaalta").val("");
	}
	
}

function check(idcheck) {
	var estatus = $("#label" + idcheck).hasClass("active");
	
	if (estatus) {
		$("#check" + idcheck).val(0);
		$("#label" + idcheck).animate({
			borderWidth : "0px"
		});
	} else {
		$("#check" + idcheck).val(1);
		$("#label" + idcheck).animate({
			borderWidth : "1px"
		});
	}
}

function causasConceptos(idcausa) {
	$("#causanombre").val( $("#causa option:selected").html());
	$(".diascheck").removeClass('active').animate({
		borderWidth : "0px"
	});
	//$(".checkcausa").val(0);
	$.post("ajax.php?c=Prenomina&f=calculoProporcionFiniquito", {
		idcausa : idcausa,
		fechabaja : $("#fechabaja").val(),
		fechaalta : $("#fechaalta").val(),
		idEmpleado : $("#empleado").val(),
		antiguedad : $("#antiguedad").val(),
		diaslaborados : $("#diasano").val(),
		sueldo:$("#sueldo").val()
	}, function(resp) {
		var res = JSON.parse(resp); 
		$.each(res, function(i, item) {
			$("#input" + res[i].id).val(res[i].diastotal);
			
			$("#check" + res[i].id).attr("checked",true);
			$("input[name='aplica["+res[i].idactivos+"]']").val(1);
			$("#label" + res[i].idactivos).addClass("active").animate({
				borderWidth : "1px"
			});
			
		});
	});
}


