$(document).ready(function(){
	var id = $('#main-title').attr('idSuc');
	//Inicializamos el tooltip de bootstrap
	$('[data-toggle="tooltip"]').tooltip();
	if (!(typeof id !== typeof undefined && id !== false)) {
		obtenerMunicipios($('#estado').val(), 1);
	} else {
		//Asignamos los valores de la sucursal obtenida a los inputs
		obtenerSucursal(id);
	}
	// Escuchamos al select de estado para cambiar el select de municipio
	$('#estado').change(function (){
		obtenerMunicipios($('#estado').val(), 1);
	});
	$('#ver_sucursales').DataTable({
		dom: 'Bfrtip',
		buttons:[ 
		{
			text: 'Añadir Sucursal',
			className:'add-sucursal',
	        action: function ( e, dt, node, config ) {
	        	var url = 'index.php?c=sucursal&f=nuevaSucursal';
	            $(location).attr('href', url);
	        }
		}, 'excel'],
		language: 
		{
		    search: "Buscar:",
		    lengthMenu:"Mostrar _MENU_ elementos",
		    zeroRecords: "No hay datos.",
		    infoEmpty: "No hay datos que mostrar.",
		    info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
		    paginate: {
		    	first:     "Primero",
	        	previous:  "Anterior",
	        	next:      "Siguiente",
	        	last:      "Último"
	    	}
	 	}
	});
});

/*  Obtenemos los estados segun el id que se obtiene del select de estado
*		Se remueve el atributo de disabled para el select de municipio
*/
function obtenerMunicipios(id, seleccionado){
	$.post('ajax.php?c=sucursal&f=obtenerMunicipios',
	{
		idmunicipio: id,
		seleccionado: seleccionado
	},
	function(data){
		$('#municipio').html(data);
		$('#municipio').removeAttr('disabled');
	});	
}

function agregarSucursal(){
	//Declarar variables
	var clave, nombre, direccion, estado, municipio, 
	codigoPostal, telefono, contacto, organizacion, almacen, activo, error;

	//Inicializar variables
	clave 			= $('#clave').val();
	nombre 			= $('#nombre').val();
	estado 			= $('#estado').val();
	activo 			= $('#activo').val();
	almacen 		= $('#almacen').val();
	telefono 		= $('#telefono').val();
	contacto 		= $('#contacto').val();
	direccion 		= $('#direccion').val();
	municipio 		= $('#municipio').val();
	codigoPostal 	= $('#codigoPostal').val();
	organizacion 	= $('#organizacion').val();

	// Validamos que los campos de nombre, dirección y clave no vengan vacios.
	// De lo contrario informamos que hay un error y no prosiga a insertar en el servidor
	if (clave == '') {
		alert("El campo clave es requerido.");
		error = true;
	} else if (nombre == '') {
		alert("El campo nombre es requerido.");
		error = true;
	} else if (direccion == '') {
		alert("El campo direccion es requerido.");
		error = true;
	} else {
		error = false;
	}

	if (error) {
		alert('Debe llenar todos los campos para poder continuar');
	} else {
		$.post('ajax.php?c=sucursal&f=validarFormulario', 
		{
			clave: clave,
			nombre: nombre,
			estado: estado,
			activo: activo,
			almacen: almacen,
			telefono: telefono,
			contacto: contacto,
			direccion: direccion,
			municipio: municipio,
			codigoPostal: codigoPostal,
			organizacion: organizacion
		},
		function(data){
			if (data == 1) {
				alert("La sucursal se ha guardado exitosamente!.");
			} else {
				alert("No se pudo guardar la sucursal.");
			}
		}).done(function(){
			var url = "index.php?c=sucursal&f=verSucursales";
			$(location).attr('href', url);
		});
	}
}

function obtenerSucursal(){
	var id = $('#main-title').attr('idSuc');
	$.post('ajax.php?c=sucursal&f=obtenerSucursal',
	{
		id: id
	}, 
	function(data){
		//console.log(data);
		obtenerMunicipios(data.estado, data.municipio);
		$('#nombre').val(data.nombre);
		$('#direccion').val(data.direccion);
		$('#estado option[value='+data.estado+']').prop('selected', true);
		//$('#municipio option[value='+data.municipio+']').prop('selected', true);
		$('#codigoPostal').val(data.codigoPostal);
		$('#telefono').val(data.telefono);
		$('#contacto').val(data.contacto);
		$('#organizacion option[value='+data.organizacion+']').prop('selected', true);
		$('#clave').val(data.clave);
		$('#almacen option[value='+data.almacen+']').prop('selected', true);
		$('#activo option[value='+data.activo+']').prop('selected', true);
	}, "json");
}

function modificarSucursal(){
	//Declarar variables
	var clave, nombre, direccion, estado, municipio, codigoPostal, telefono, contacto, organizacion, almacen, activo, error, id;

	//Inicializar variables
	id = $('#main-title').attr('idSuc');
	clave = $('#clave').val();
	nombre = $('#nombre').val();
	estado = $('#estado').val();
	activo = $('#activo').val();
	almacen = $('#almacen').val();
	telefono = $('#telefono').val();
	contacto = $('#contacto').val();
	direccion = $('#direccion').val();
	municipio = $('#municipio').val();
	codigoPostal = $('#codigoPostal').val();
	organizacion = $('#organizacion').val();

	// Validamos que los campos de nombre, dirección y clave no vengan vacios.
	// De lo contrario informamos que hay un error y no prosiga a insertar en el servidor
	if (clave == '') {
		alert("El campo clave es requerido.");
		error = true;
	} else if (nombre == '') {
		alert("El campo nombre es requerido.");
		error = true;
	} else if (direccion == '') {
		alert("El campo direccion es requerido.");
		error = true;
	} else {
		error = false;
	}

	if (error) {
		alert('Debe llenar todos los campos para poder continuar');
	} else {
		$.post('ajax.php?c=sucursal&f=modificarSucursal', 
		{
			id: id,
			clave: clave,
			nombre: nombre,
			estado: estado,
			activo: activo,
			almacen: almacen,
			telefono: telefono,
			contacto: contacto,
			direccion: direccion,
			municipio: municipio,
			codigoPostal: codigoPostal,
			organizacion: organizacion
		},
		function(data){
			if (data == 1) {
				alert("La sucursal se ha modificado exitosamente!.");
			} else {
				console.log(data);
				alert('Hubo un error.');
			}
		}).done(function(){
			var url = "index.php?c=sucursal&f=verSucursales";
			$(location).attr('href', url);
		});
	}
}

function quitafooter(){
  $("div[style='text-align:center; width:100%; clear:both;']").hide()
}
