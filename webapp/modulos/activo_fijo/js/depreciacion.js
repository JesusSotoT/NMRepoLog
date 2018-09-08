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

function inicializa_lista_activos()
{
	$.post('ajax.php?c=depreciacion&f=lista_activos', 
			function(data) 
			{
				//alert(data)
				var datos = jQuery.parseJSON(data);
				
					$('#tabla-activos').DataTable( {
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
							{ data: 'no_activo' },
							{ data: 'categoria' },
							{ data: 'descripcion' },
							{ data: 'modelo' },
							{ data: 'marca' },
							{ data: 'fecha' },
							{ data: 'moi' },
							{ data: 'ubicacion' },
							{ data: 'responsable' },
							{ data: 'concepto' },
							{ data: 'mod' }
						]
					});
					$('#tabla-activos_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
					$("#tabla-activos").before($("#boton_virtual1").html());
					$("#boton_virtual1").hide();
			});
}

function pestana(t)
{
	$("#num_factura,#proveedor,#fecha,#moi").attr('readonly',true);

	$(".pestanas").hide();
	$("#"+t).show()
	$(".link").css('background-color','white');
	$("#"+t+"-link").css('background-color','#E8E8E7');
	
	$(".fiscal_form").attr('readonly',false)
	$(".contable_form").attr('readonly',false)

	//Su se llena el fiscal se bloquea el contable y viceversa
	/*if($("#fecha_depr").val() != '')
	{
		$(".fiscal_form").attr('readonly',true)
		$(".contable_form").attr('readonly',false)
	}

	if($("#fecha_depr_2").val() != '')
	{
		$(".fiscal_form").attr('readonly',false)
		$(".contable_form").attr('readonly',true)
	}*/
}

function nuevo()
{
	
	$(".tit").text('Nuevo Activo')
	$("[type=text]").val('')
	$("#id_reg").val(0)
	$("#factura,#cuenta_asoc,#cuenta_activo,#cuenta_resultados,#no_deducible,#cuenta_orden_acr,#cuenta_orden_deu").val(0).trigger('change')
	$("#estatus").val(1)
	$("#factura_hidden2").attr('id','factura')
	$("#factura2").attr('id','factura_hidden')
	$("#factura_hidden").val(0)
	$("#factura").parent().show()
	$("#factura_hidden").parent().hide()
	$("#sel_fac").text("Selecciona Factura")
	
	pestana('general')
}

function cancelar()
{
	$('.bs-example-modal-lg').modal('hide');
	nuevo();
}

function imprime_facturas()
{
	$.post('ajax.php?c=depreciacion&f=lista_facturas', 
			function(data) 
			{
				$("#factura").html(data);
				$("#factura").val(0).trigger('change');
			});
}

function datos_factura()
{
	var num_factura;
	if($("#factura").val() != '0')
	{
		if($("#factura option:selected").attr('folio'))
			num_factura = $("#factura option:selected").attr('folio');
		else
			num_factura = $("#factura option:selected").attr('uuid');

		$("#num_factura").val(num_factura);
		$("#proveedor").val($("#factura option:selected").attr('proveedor'));
		$("#fecha").val($("#factura option:selected").attr('fecha_fac'));
		$("#moi").val($("#factura option:selected").attr('moi'));
		$("#moi_cont").val($("#factura option:selected").attr('moi'));
		$("#id_proveedor").val($("#factura option:selected").attr('id_proveedor'));
	}
	else
	{
		$("#num_factura").val('');
		$("#proveedor").val('');
		$("#fecha").val('');
		$("#moi").val('');
		$("#moi_cont").val('');
		$("#id_proveedor").val(0);
	}
	
}

function guardar()
{
	if($("#moi").val() != '' && $("#descripcion").val() != '' && $("#marca").val() != '')
	{
		var base_calculo = 0;
		var factura = 0;
		if($("#fecha_depr").val() != '')
		{
			base_calculo++;//contable
		}
		if($("#fecha_depr_2").val() != '')
		{
			base_calculo=base_calculo+2;//fiscal
		}

		//alert($("#id_reg").val())
		if(!parseInt($("#id_reg").val()))
			factura = $("#factura").val();
		else
			factura = $("#factura2").attr('fac');

		//alert(base_calculo)


		if(base_calculo)
		{
			$.post('ajax.php?c=depreciacion&f=guardar', 
			{
				id_reg			: $("#id_reg").val(),
				factura 		: factura,
				num_factura 	: $("#num_factura").val(),
				id_proveedor 	: $("#id_proveedor").val(),
				fecha 			: $("#fecha").val(),
				moi 			: $("#moi").val(),
				concepto_alta 	: $("#concepto_alta").val(),
				descripcion 	: $("#descripcion").val(),
				n_serie 		: $("#n_serie").val(),
				modelo 			: $("#modelo").val(),
				marca 			: $("#marca").val(),
				color 			: $("#color").val(),
				ubicacion 		: $("#ubicacion").val(),
				responsable 	: $("#responsable").val(),
				n_activo 		: $("#n_activo").val(),
				categoria		: $("#categoria").val(),
				segmento 		: $("#segmento").val(),
				sucursal 		: $("#sucursal").val(),
				barras 			: $("#barras").val(),
				cuenta_asoc 	: $("#cuenta_asoc").val(),
				etiqueta 		: $("#etiqueta").val(),
				base_calculo	: base_calculo,
				estatus 		: $("#estatus").val()
			}, 
			function(data) 
			{
				//alert(data)
				console.log("id activo: "+data);
				if(parseInt(data))
				{
					//Solo contable o ambas
					if(base_calculo==1 || base_calculo==3)
					{
						$.post('ajax.php?c=depreciacion&f=guardar_cont', 
						{
							id_reg			: data,
							fecha_depr 		:$("#fecha_depr").val(),
							moi_cont 		:$("#moi_cont").val(),
							tasa_depr 		:$("#tasa_depr").val(),
							porc_deducible 	:$("#porc_deducible").val(),
							fecha_ultima_depr :$("#fecha_ultima_depr").val(),
							depr_ejercicio 	:$("#depr_ejercicio").val(),
							depr_acumulada 	:$("#depr_acumulada").val(),
							cuenta_activo 	:$("#cuenta_activo").val(),
							cuenta_resultados :$("#cuenta_resultados").val(),
							no_deducible 	:$("#no_deducible").val(),
							base_calculo	:base_calculo
						}, 
						function(data) 
						{
							//alert(data)
							console.log("id activo contable: "+data);
							if(!parseInt(data))
								alert("Hubo un problema y no se guardo la base contable.");
							
						});
					}
					//Solo fiscal o ambas
					if(base_calculo==2 || base_calculo==3)
					{
						$.post('ajax.php?c=depreciacion&f=guardar_fiscal', 
						{
							id_reg	: data,
							fecha_depr :$("#fecha_depr_2").val(),
							deduccion_inmediata :$("#deduccion_inmediata").val(),
							tasa_depr :$("#tasa_depr_2").val(),
							porc_deducible :$("#porc_deducible_2").val(),
							fecha_ultima_depr :$("#fecha_ultima_depr_2").val(),
							depr_ejercicio :$("#depr_ejercicio_2").val(),
							depr_acumulada :$("#depr_acumulada_2").val(),
							cuenta_orden_acr :$("#cuenta_orden_acr").val(),
							cuenta_orden_deu :$("#cuenta_orden_deu").val(),
							base_calculo	:base_calculo
						}, 
						function(data) 
						{
							//alert(data)
							console.log("id activo fiscal: "+data);
							if(!parseInt(data))
								alert("Hubo un problema y no se guardo la base fiscal.");
						});
					}
					reinicializa();
					cancelar();
				}
				else
					alert("Hubo un problema y no se guardo el activo.");
			});
		}
		else
			alert("Seleccione una base de calculo fiscal o contable.")

		
	}
	else
		alert("Faltan datos por llenar o no se anexo una factura.")
	
}

function reinicializa()
{
	$('#tabla-activos').DataTable().destroy();
	inicializa_lista_activos();
}

function modificar(id)
{
	nuevo();
	$.post('ajax.php?c=depreciacion&f=getDatosActivo', 
	{
		id_reg	: id
	}, 
	function(data) 
	{
		console.log("info activo: "+data)
		var datosActivo = jQuery.parseJSON(data);
		console.log("base calculo: "+datosActivo.base_calculo)
		console.log("id reg: "+id)
		console.log("factura: "+datosActivo.factura)
			$("#id_reg").val(id)
			$("#factura_hidden").parent().show();
			$("#factura").attr('id','factura_hidden2')
			$("#factura_hidden").attr('id','factura2')
			$("#factura2").val(datosActivo.factura)
			$("#factura2").attr('fac',datosActivo.factura)
			$("#factura_hidden2").parent().hide();
			$("#sel_fac").text("Archivo Factura")
			$("#num_factura").val(datosActivo.num_fact)
			$("#proveedor").val(datosActivo.nombre_proveedor)
			$("#id_proveedor").val(datosActivo.id_proveedor)
			$("#fecha").val(datosActivo.fecha_fact)
			$("#moi").val(datosActivo.moi)
			$("#moi_cont").val(datosActivo.moi)
			$("#concepto_alta").val(datosActivo.id_concepto_alta)
			$("#descripcion").val(datosActivo.descripcion)
			$("#n_serie").val(datosActivo.n_serie)
			$("#modelo").val(datosActivo.modelo)
			$("#marca").val(datosActivo.marca)
			$("#color").val(datosActivo.color)
			$("#ubicacion").val(datosActivo.ubicacion)
			$("#responsable").val(datosActivo.id_responsable).trigger('change')
			$("#n_activo").val(id)
			$("#categoria").val(datosActivo.id_categoria)
			$("#segmento").val(datosActivo.id_segmento)
			$("#sucursal").val(datosActivo.id_sucursal)
			$("#barras").val(datosActivo.codigo_barras)
			$("#cuenta_asoc").val(datosActivo.id_cuenta).trigger('change')
			$("#etiqueta").val(datosActivo.codigo)
			$("#estatus").val(datosActivo.estatus)
		if(parseInt(datosActivo.base_calculo) == 1 || parseInt(datosActivo.base_calculo) == 3)
		{
			$.post('ajax.php?c=depreciacion&f=getDatosActivoContFisc', 
			{
				id_reg	: id,
				base 	: 1
			}, 
			function(data1) 
			{
					var datosContFisc = jQuery.parseJSON(data1);
					$("#fecha_depr").val(datosContFisc.fecha_inicio_depr)
					$("#moi_cont").val(datosContFisc.moi)
					$("#tasa_depr").val(datosContFisc.tasa_depr)
					$("#porc_deducible").val(datosContFisc.porc_deducible)
					$("#fecha_ultima_depr").val(datosContFisc.fecha_ult_depr)
					$("#depr_ejercicio").val(datosContFisc.depr_ejercicio)
					$("#depr_acumulada").val(datosContFisc.depr_acumulada)
					$("#cuenta_activo").val(datosContFisc.id_cuenta_activo).trigger('change')
					$("#cuenta_resultados").val(datosContFisc.id_cuenta_resultado).trigger('change')
					$("#no_deducible").val(datosContFisc.id_cuenta_no_deducible).trigger('change')
			});
		}

		if(parseInt(datosActivo.base_calculo) == 2 || parseInt(datosActivo.base_calculo) == 3)
		{
			$.post('ajax.php?c=depreciacion&f=getDatosActivoContFisc', 
			{
				id_reg	: id,
				base 	: 2
			}, 
			function(data1) 
			{
					var datosContFisc = jQuery.parseJSON(data1);
					$("#fecha_depr_2").val(datosContFisc.fecha_inicio_depr)
					$("#deduccion_inmediata").val(datosContFisc.deduccion_inmediata)
					$("#tasa_depr_2").val(datosContFisc.tasa_depr)
					$("#porc_deducible_2").val(datosContFisc.porc_deducible)
					$("#fecha_ultima_depr_2").val(datosContFisc.fecha_ult_depr)
					$("#depr_ejercicio_2").val(datosContFisc.depr_ejercicio)
					$("#depr_acumulada_2").val(datosContFisc.depr_acumulada)
					$("#cuenta_orden_acr").val(datosContFisc.id_cuenta_acreedora).trigger('change')
					$("#cuenta_orden_deu").val(datosContFisc.id_cuenta_deudora).trigger('change')
			});
		}						
		$('.bs-example-modal-lg').modal('show');
	});

}