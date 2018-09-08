var dias;
function listadoNominasxPeriodo(idtipo,nombre){

	$("#contenidop").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" ></i>');
	$.post("ajax.php?c=Catalogos&f=listadoNominasxPeriodo",{
		idtipop:	idtipo
	},function(resp){
		$("#periodo").html("Nominas del Periodo "+nombre);
		$("#contenidop").html(resp);
	}
	);
}
$(document).ready(function(){

	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	


});

function alimentaDatos(idNomina){
	$("#fechainicio").val('').datepicker("refresh");
	$("#fechafin").val('').datepicker("refresh");
	
	$("#i"+idNomina).show();
	$.post("ajax.php?c=Catalogos&f=editaPeriodo",{
		idNomina:idNomina
	},function(resp){ 
		var val = resp.split("->");
		$("#idnomina").val( val[0] );
		$("#numero").val( val[1]);
		$("#fechainicio").val( val[2]);
		$("#fechafin").val( val[3]);
		$("#diaspago").val( val[4]);
		if(val[5]==1){
			$("#inimes").prop("checked",true);
		}else{
			$("#inimes").prop("checked",false);
		}
		
		if(val[6]==1){
			$("#inibimestre").prop("checked",true);
		}else{
			$("#inibimestre").prop("checked",false);
		}
		
		if(val[7]==1){
			$("#iniejer").prop("checked",true);
		}else{
			$("#iniejer").prop("checked",false);
		}
		
		if(val[8]==1){
			$("#finmes").prop("checked",true);
		}else{
			$("#finmes").prop("checked",false);
		}
		
		if(val[9]==1){
			$("#finbimestre").prop("checked",true);
		}else{
			$("#finbimestre").prop("checked",false);
		}
		
		if(val[10]==1){
			$("#finejer").prop("checked",true);
		}else{
			$("#finejer").prop("checked",false);
		}
		
		$("#inimes").val( val[5]);
		$("#inibimestre").val( val[6]);
		$("#iniejer").val( val[7]);
		$("#finmes").val( val[8]);
		$("#finbimestre").val( val[9]);
		$("#finejer").val( val[10])
		$("#diasperiodo").val( val[11]);
		dias = val[11];
		$("#i"+idNomina).hide();	
		

		$("#fechainicio").datepicker({
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			onSelect: function(selected) {
				var	fecha		= new Date(selected);
				var	fechamin		= new Date(selected);

				fecha.setDate(fecha.getDate() +  parseInt(dias));
				fechamin.setDate(fechamin.getDate() + 1);

				$("#fechafin").datepicker("option", "maxDate",fecha);
				$("#fechafin").datepicker("option", "minDate",fechamin);
				$("#fechafin").datepicker("setDate",fecha);
			}

		});

		$("#fechafin").datepicker({
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			onSelect: function(selected) {
				var	fecha = new Date($("#fechainicio").val());
				fecha.setDate(fecha.getDate() + 1);
				$("#fechafin").datepicker("option", "minDate",fecha);
			}
		});
		$("#fechafin").val( val[3]);
		$("#fechainicio").val( val[2]);		
		var	fecha		= new Date( val[2]);
		var	fechamin	= new Date( val[2]);

		fecha.setDate(fecha.getDate() +  parseInt(dias));
		fechamin.setDate(fechamin.getDate() + 1);

		$("#fechafin").datepicker("option", "maxDate",fecha);
		$("#fechafin").datepicker("option", "minDate",fechamin);

	});
}
function cambiocheck(check){
	if( $("#"+check).is(":checked") ){
		$("#"+check).val(1);
	}else{
		$("#"+check).val(0);
	}
}

$(function() {

	$('#load').on('click', function() { 
	
		var btnguardar = $(this);
		btnguardar.button("loading");
		$.post("ajax.php?c=Catalogos&f=almacenaedicion",{
			idnomina:$("#idnomina").val(),
			numero:$("#numero").val(),
			fechainicio : $("#fechainicio").val(),
			fechafin : $("#fechafin").val(),
			diaspago:$("#diaspago").val(),
			inimes:$("#inimes").val(),
			inibimestre :$("#inibimestre").val(),
			iniejer:$("#iniejer").val(),
			finmes:$("#finmes").val(),
			finbimestre:$("#finbimestre").val(),
			finejer:$("#finejer").val()
		},function(resp){ 
			// alert(resp);
			if (resp==1) {
				alert("Guardado.");
				location.reload();
			}else if (resp==2){
				alert("No fue posible modificar el periodo porque existen movimientos relacionados.");

			}else{
				alert("No se pudo actualizar, intente de nuevo.");
			}
			btnguardar.button('reset');
		});
	});
});

