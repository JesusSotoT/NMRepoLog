var table;

function check_file()
{
  	var ext = $('#factura').val();
  	var spaces = ext;
  	ext = ext.split('.');
  	ext = ext.slice(-1)[0];
 		console.log('extension archivo: '+ext)
  	if(ext != 'zip' && ext != 'xml')
  	{
  		alert("Archivo Inválido \nEl archivo debe tener una extensión xml o zip.");
  		$("#factura").val('');
  	}
  	if(spaces.indexOf(' ') >= 0)
  	{
  		alert("Archivo Inválido \nEl nombre del archivo y/o la carpeta no deben tener espacios en blanco. \n"+spaces);
  		$("#factura").val('');	
  	}

}

function poliza_manual()
{
	window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=Ver','Captura','',0);
}

function buscar(prov)
{
	$("#normales").show();
	$("#pagos").hide();
	
	if ($('#asignadas').val() == 3) {
		if (confirm("¿Desea validar las canceladas?")) {
			canceladas();
		}
	}

	$('#buscar').attr("disabled",true);
	$('#buscar').html("<i class='material-icons spin' style='font-size:1.2em;'>sync</i>");
	var asignadas = 0;
	var tipo_facturas = 0;
	var rfc = 0;

	if($("#asignadas").length)
		asignadas = $("#asignadas").val()
	if($("#tipo_facturas").length)
		tipo_facturas = $("#tipo_facturas").val()
	if($("#rfc").length && $("#rfc").val() != '')
		rfc = $("#rfc").val()

	if(parseInt(tipo_facturas))
		asignadas = 1;
	if(parseInt(tipo_facturas) == 4)
		asignadas = 4;

	$.post("ajax.php?c=Almacen&f=listaFacturas2",
	{
		inicial 	:$("#inicial").val(),
		final 		:$("#final").val(),
		asignadas 	:asignadas,
		tipo_facturas :tipo_facturas,
		rfc 		:rfc,
		prov        : prov
	},
	function(data)
	{
		var datos = jQuery.parseJSON(data);
		console.log(datos);
		var last_index = (datos.length-1);
		$('#totales').html("$ "+(datos[last_index]['total_final']).format());
		datos.pop();
		$('#tabla-data').DataTable().destroy();
    table = $('#tabla-data').DataTable({
    	//"paging": false,
        language: {
          search: "Buscar:",
          lengthMenu:"Mostrar _MENU_ elementos",
          zeroRecords: "No hay datos.",
          infoEmpty: "No hay datos que mostrar.",
          info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
          paginate: {
            first:    "Primero",
            previous: "Anterior",
            next:     "Siguiente",
            last:     "Último"
          }
        },
				columnDefs: [
					{ orderable: false, targets: [4, 17, 18] },
					{ visible: false, targets: [19,20] }
				],
        "order": [[ 0, "asc" ]],
        data:datos,
        columns: [
          { data: 'fecha' },
          { data: 'rfc' },
          { data: 'emisor' },
          { data: 'receptor' },
          { data: 'links' },
          { data: 'tipo' },
          { data: 'pago' },
          { data: 'metodo' },
          { data: 'moneda' },
          { data: 'subtotal' },
          { data: 'ivas' },
          { data: 'total' },
          { data: 'serie' },
          { data: 'folio' },
          { data: 'uuid' },
          { data: 'fecha_sub' },
          { data: 'version' },
          { data: 'estatus' },
          { data: 'check' },
          { data: 'domicilio_emisor' },
          { data: 'domicilio_receptor' }
        ]
    });

    new $.fn.dataTable.Buttons(table, {
      buttons:[{
         extend: 'excel',
         footer: true,
         exportOptions: {
       			//Va a exportar todas las columnas menos la de los iconos y los checkbox
       			//incluye las columnas ocultas.
            columns: [0,1,2,3,5,6,7,8,9,10,11,12,13,14,15,16,17,19,20]
          }
       }]
    });
 
    table.buttons(0, null).container().prependTo(
        table.table().container()
    );
    //console.log(table.rows({ 'search': 'applied' }).nodes());
    $("#checkAll").on('click', function() {
    	var rows = table.rows({ 'search': 'applied' }).nodes();
  		$('input[type="checkbox"]', rows).prop('checked', this.checked);
		});
    var cantidad = 0;
    $(".importes").each(function(index)
		{
			cantidad += parseFloat($(this).attr('cantidad'))
		});
		$('#buscar').removeAttr("disabled");
		$('#buscar').html("Buscar");
		//alert(cantidad)
		$("#boton_generar").show();
	});
}

function buscar_pagos()
{
	$("#normales").hide()
	$("#pagos").show()
	$('#buscar').attr("disabled",true);
	$('#buscar').html("<i class='material-icons spin' style='font-size:1.2em;'>sync</i>");
	$.post("ajax.php?c=Almacen&f=listaFacturasPagos",
	{
		inicial 	:$("#inicial").val(),
		final 		:$("#final").val(),
		asignadas   :4 
	},
	function(data)
	{
		var datos = jQuery.parseJSON(data);
		console.log(datos);
		var last_index = (datos.length-1);
		$('#totales').html("$ "+(datos[last_index]['total_final']).format());
		datos.pop();
		$('#tabla-data-pagos').DataTable().destroy();
    table = $('#tabla-data-pagos').DataTable({
    	//"paging": false,
        language: {
          search: "Buscar:",
          lengthMenu:"Mostrar _MENU_ elementos",
          zeroRecords: "No hay datos.",
          infoEmpty: "No hay datos que mostrar.",
          info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
          paginate: {
            first:    "Primero",
            previous: "Anterior",
            next:     "Siguiente",
            last:     "Último"
          }
        },
        "order": [[ 0, "asc" ]],
        data:datos,
        columns: [
          { data: 'uuid' },
          { data: 'fecha' },
          { data: 'rfc' },
          { data: 'emisor' },
          { data: 'receptor' },
          { data: 'links' },
          { data: 'tipo' },
          { data: 'serie' },
          { data: 'folio' },
          { data: 'uuid_doc' },
          { data: 'pago' },
          { data: 'moneda' },
          { data: 'saldo_ant' },
          { data: 'saldo_inso' },
          { data: 'importe' },
          { data: 'parcialidad' },
          { data: 'fecha_sub' }
        ]
    });

    new $.fn.dataTable.Buttons(table, {
      buttons:[{
         extend: 'excel',
         footer: true,
         exportOptions: {
       			//Va a exportar todas las columnas menos la de los iconos y los checkbox
       			//incluye las columnas ocultas.
            columns: [0,1,2,3,4,6,7,8,9,10,11,12,13,14,15,16]
          }
       }]
    });

 
    table.buttons(0, null).container().prependTo(
        table.table().container()
    );
    //console.log(table.rows({ 'search': 'applied' }).nodes());
    $("#checkAll").on('click', function() {
    	var rows = table.rows({ 'search': 'applied' }).nodes();
  		$('input[type="checkbox"]', rows).prop('checked', this.checked);
		});
    var cantidad = 0;
    $(".importes").each(function(index)
		{
			cantidad += parseFloat($(this).attr('cantidad'))
		});
		//alert(cantidad)
		$('#buscar').removeAttr("disabled");
		$('#buscar').html("Buscar");
	});
}

function canceladas()
{
	$(".btn").attr('disabled',true)
	$("a,input:file,img").hide();
	$('#canc_load').css('display','block');
	var inicial = $("#inicial").val() + " 00:00:00";
	var final   = $("#final").val() + " 23:59:59";
	$.post("ajax.php?c=CaptPolizas&f=canceladas",
	{
		inicial 	:inicial,
		final 		:final
	},
	function(data)
	{
		console.log('Canceladas: ',data);
		$('#canc_load').css('display','none');
		if(parseInt(data))
		{
			alert('Hubieron '+data+' cancelados');
			//location.reload();
			$(".btn").attr('disabled',false);
		}
		else
		{
			alert('No hubo cancelados')
			$(".btn").attr('disabled',false)
			$("a,input:file,img").show();
		}
	});
}

$( '#fac' ).submit( function( e ) {
	e.preventDefault();
  	$('#verif').css('display','inline');
    $.ajax( {
      url: 'ajax.php?c=CaptPolizas&f=subeFacturaZip',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } ).done(function( data1 ) {
    	//alert(data1)
    	//$("#Facturas").dialog('refresh')
    	console.log(data1)

			$('#factura').val('')
			data1 = data1.split('-/-*');
			$('#verif').css('display','none');

			if(parseInt(data1[0]))
			{
				if(parseInt(data1[3]))
				{
					alert('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4])
					console.log('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4]);
				}

				if(parseInt(data1[1]))
				{
					alert(data1[1]+' Archivos Validados: \n'+data1[2])
					console.log(data1[1]+' Archivos Validados: \n'+data1[2]);
				}
				//alert(parseInt(data1[5]))
				if(parseInt(data1[5])){
					abrefacturasrepetidas();

				}else{
					location.reload();
				}
			}
			else
			{
				alert("El archivo zip no cumple con el formato correcto\nDebe llamarse igual que la carpeta que contiene los xmls.\nSólo debe contener una carpeta.\nEl nombre de la carpeta no debe contener espacios en blanco.");
			}
  	});
  });

$('body').bind("keyup", function(evt)
{
  if (evt.ctrlKey==1)//ctrl
  {
   	if(evt.keyCode == 85) //u  --- relaciona facturas fisicas y crea registro en bd
  	{
  		alert("Ejecutar funcion que recorre las facturas y si no se encuentran almacenadas en base de datos las registrará.");
  		if(confirm("Esta seguro que quiere correr esta funcion? puede tardar varios minutos en completarse."))
  		{
  			$.post("ajax.php?c=Almacen&f=buscaFacturas",
		{},
		function(data)
		{
			console.log(data)
			if(parseInt(data))
				alert("Proceso Finalizado exitosamente.")
		});
  		}
  	}

  	if(evt.keyCode == 87) //w --- recorre los xmls y busca en bd si esta asignada, entonces crea la carpeta fisica
  	{
  		alert("Ejecutar funcion que recorre las facturas y busca si esta asignada");
  		if(confirm("Esta seguro que quiere correr esta funcion? puede tardar varios minutos en completarse."))
  		{
  			$.post("ajax.php?c=Almacen&f=relacionaFacturasMovs",
		{},
		function(data)
		{
			console.log(data)
			if(parseInt(data))
				alert("Proceso Finalizado exitosamente.")
		});
  		}
  	}
  }
 });

function descargarXMLs(){
	var rows = table.rows({ 'search': 'applied' }).nodes();
	var arr_inputs = $('input:checked', rows);
	//Validamos que el usuario tenga seleccionado algun checkbox
	if (arr_inputs.length > 0) {
		//Si el usuario confirma que desea descargar los xmls...
		if (confirm('¿Desea descargar los XMLs?')) {
			var xmls = new Array();
			//Obtenemos los nombres de los xmls en registros de la tabla
			$.each(arr_inputs, function(index, input){
				xmls.push($(input).attr('xml'));
			});
			//Luego los descargamos por ajax
			$.post('ajax.php?c=backup&f=descargarXMLS',
			{
				xmls: xmls
			},
			function (data){
				console.log(data);
				$('#hiddenContainer').html(data.download);
				var linkZip = $('#zipPathXML').val();
				if(linkZip != undefined) {
					window.location = linkZip;
					$.post('ajax.php?c=backup&f=borrarZip', {link: linkZip});
				} else {
					alert("Hubo un error y no se genero la descarga.")
				}
			}, "JSON");
		}
	//Si no, le informamos que tiene que seleccionar un checkbox para poder descargar	 
	} else {
		alert("Seleccione al menos un registro para poder descargar.");
	}
}

function eliminar_xmls(xmls){
	console.log(xmls);
	$.post("ajax.php?c=backup&f=Eliminarxmls",
		{xmls: xmls},
		function(data){
			if (data == 1) {
				alert("Los registros se han borrado exitosamente.");
			}
		});
}

function eliminarSeleccionados() {
	var rows = table.rows({ 'search': 'applied' }).nodes();
	var arr_inputs = $('input:checked', rows);
	//Validamos que este seleccionada al menos un registro
	if (arr_inputs.length > 0) {
		//Validamos que el usuario sabe que esta borrando facturas
		if(confirm('¿Realmente desea eliminar los XMLs?')) {
			var xmls = new Array();
			//Obtenemos los nombres de los xmls en registros de la tabla
			$.each(arr_inputs, function(index, input){
				xmls.push($(input).attr('xml'));
			});
			eliminar_xmls(xmls);
		}
		location.reload();
	} else {
		alert("Debe seleccionar almenos un registro.");
	}
}

function agregar_funcion()
{
	var tipo = 0;
	
	if($("#asignadas").length)
		tipo = $("#asignadas").val()
	if($("#tipo_facturas").length)
		tipo = $("#tipo_facturas").val()

	if(parseInt(tipo) && parseInt(tipo) != 4)
		$("#buscar").attr('onclick','buscar()')
	else
		$("#buscar").attr('onclick','buscar_pagos()')
}

function generar_polizas()
{
	var rows = table.rows({ 'search': 'applied' }).nodes();
	var arr_inputs = $('input:checked', rows);
	//Validamos que este seleccionada al menos un registro
	if (arr_inputs.length > 0) {
		//Validamos que el usuario sabe que esta borrando facturas
		if(confirm('¿Quiere hacer las polizas de provision de estas facturas?')) {
			$('#generar_polizas').attr("disabled",true);
			$('#generar_polizas').html("<i class='material-icons spin' style='font-size:1.2em;'>sync</i>");
			$('#buscar').attr("disabled",true);
			$('#buscar').html("<i class='material-icons spin' style='font-size:1.2em;'>sync</i>");
			
			var xmls = new Array();
			//Obtenemos los nombres de los xmls en registros de la tabla
			$.each(arr_inputs, function(index, input){
				xmls.push($(input).attr('idfac'));
			});
			hacer_facturas(xmls);
		}
		//location.reload();
	} else {
		alert("Debe seleccionar almenos un registro.");
	}
}

function hacer_facturas(xmls)
{
	$.post("ajax.php?c=Almacen&f=hacer_facturas",
		{
			xmls: xmls,
			tipofac:$("#tipo_facturas").val()
		},
		function(data)
		{
			console.log(data)
			if(data) 
			{
				alert("Las siguientes "+data+" facturas se han creadado exitosamente.");
				$("#buscar").click();
			}
			else
				alert("No se generó ninguna poliza.")
			$('#generar_polizas').removeAttr("disabled");
			$('#generar_polizas').html("Generar Polizas");
			$('#buscar').removeAttr("disabled");
			$('#buscar').html("Buscar");
		});
}