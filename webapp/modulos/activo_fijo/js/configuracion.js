$(function()
 {
	if(parseInt($("#pestania").val()))
	{
		$('#myTabs a').eq($("#pestania").val()).click();

	}
	
	$("#blanco").hide();
 });


$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

function inicializa_lista_cat(tipo)
{
	$.post('ajax.php?c=configuracion&f=lista_'+tipo, 
			function(data) 
			{
				//alert(data)
				var datos = jQuery.parseJSON(data);
				
					$('#tabla-'+tipo).DataTable( {
						dom: 'Bfrtip',
						buttons: [],
						language: {
							buttons: {
								pageLength: 'Mostrar %d filas'
							},
							search: "Buscar:",
							lengthMenu:"Mostrar _MENU_ elementos",
							zeroRecords: "No hay datos.",
							infoEmpty: "No hay datos que mostrar.",
							info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
							paginate: {
								first:      "Primero",
								previous:   "Anterior",
								next:       "Siguiente",
								last:       "Último"
							}
						 },
						 data:datos,
						 columns: [
							{ data: 'id' },
							{ data: 'codigo' },
							{ data: 'nombre' },
							{ data: 'activo' },
							{ data: 'mod' }
						]
					});
					$('#tabla-'+tipo+'_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
					if(tipo == 'altas')
					{
						$("#tabla-"+tipo).before($("#boton_virtual1").html());
						$("#boton_virtual1").hide();
					}
					if(tipo == 'bajas')
					{
						$("#tabla-"+tipo).before($("#boton_virtual2").html());
						$("#boton_virtual2").hide();
					}

					if(tipo == 'bienes')
					{
						$("#tabla-"+tipo).before($("#boton_virtual3").html());
						$("#boton_virtual3").hide();
					}

					if(tipo == 'formulas')
					{
						$("#tabla-"+tipo).before($("#boton_virtual4").html());
						$("#boton_virtual4").hide();
					}

					if(tipo == 'inpc')
					{
						$("#tabla-"+tipo).before($("#boton_virtual5").html());
						$("#boton_virtual5").hide();
					}

					if(tipo == 'depr')
					{
						$("#tabla-"+tipo).before($("#boton_virtual6").html());
						$("#boton_virtual6").hide();
					}
			});
}


function nuevo_cat(vari)
{
	var texto,primero,segundo;
	$("#indice_inpc").hide();
	switch(vari)
	{
		case 'altas':texto = "Nuevo Concepto de Alta"; primero = 'Codigo'; segundo = 'Nombre'; break;
		case 'bajas':texto = "Nuevo Concepto de Baja"; primero = 'Codigo'; segundo = 'Nombre'; break;
		case 'bienes':texto = "Nueva Categoria de Bienes"; primero = 'Codigo'; segundo = 'Nombre'; break;
		case 'formulas':texto = "Nueva Formula"; primero = 'Nombre'; segundo = 'Formula'; break;
		case 'inpc':texto = "Nuevo Indice"; primero = 'Año'; segundo = 'Mes'; $("#indice_inpc").show(); break;
		case 'depr':texto = "Nuevo Porcentaje de depreciacion"; primero = 'Nombre'; segundo = 'Valor'; break;
	}
	if(vari != 'inpc')
	{
		if(!$("#primero").is('input'))
		{
			$("#primero").after("<input type='text' id='primero' class='form-control original'>");
			$("#segundo").after("<input type='text' id='segundo' class='form-control original'>");
			$(".selects").remove();
			$("#primero,#segundo,#indice").val('')
		}
		
	}
	else
	{
		if(!$("#primero").is('select'))
		{
			var anios;
			var anioInicial = new Date().getFullYear()

			anios = "<select id='primero' class='form-control selects'>";
			for(i=anioInicial;i>=(parseInt(anioInicial)-7);i--)
				anios += "<option value='"+i+"'>"+i+"</option>";
			anios += "</select>";
			var meses = "<select id='segundo' class='form-control selects'><option value='1' selected>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select>";
			$("#primero").after(anios);
			$("#segundo").after(meses);
			$(".original").remove();
			$("#primero").val(anioInicial).trigger('change')
			$("#segundo").val(1).trigger('change')
		}
	}
	$("h4").text(texto)
	$("#tipo_reg").val(vari)
	$("#id_reg").val(0);
	$("#primero_b").text(primero)
	$("#segundo_b").text(segundo)
	$("#indice").val('')
	$("#estatus").val(1)
}

function cancelar_cat()
{
	$('.bs-example-modal-sm').modal('hide');
	nuevo_cat('altas');
}

function guardar()
{
	var sigue = 0;
	var indice=0;
	if($("#primero").val() != '' && $("#segundo").val() != '')
		sigue = 1;

	if(sigue)
	{
		if($("#tipo_reg").val() == 'inpc')
			indice = $("#indice").val();

		$.post('ajax.php?c=configuracion&f=guardar_registro', 
			{
				id_reg 	: $("#id_reg").val(),
				tipo 	: $("#tipo_reg").val(),
				primero : $("#primero").val(),
				segundo : $("#segundo").val(),
				indice 	: indice,
				estatus : $("#estatus").val()
			}, 
			function(data) 
			{
				//alert(data)
				$('#tabla-'+$("#tipo_reg").val()).DataTable().destroy();
				inicializa_lista_cat($("#tipo_reg").val());
				cancelar_cat();
			});
	}
	else
		alert("Existen campos vacios, por favor revise.")
}

function modificar(tipo,id)
{
	nuevo_cat(tipo)
	$.post('ajax.php?c=configuracion&f=get_data_reg', 
			{
				tipo:tipo,
				id 	:id
			},
			function(data) 
			{
				var datos = data.split("**//**");
				$("#id_reg").val(datos[0]);
				$("#primero").val(datos[1]);
				$("#segundo").val(datos[2]);
				$("#estatus").val(datos[3]);
				if(tipo == 'inpc')
					$("#indice").val(datos[4]);

			});

}