function estadosF(){
	var pais = $('#pais').val();

	$.ajax({
		url: 'ajax.php?c=cliente&f=estados2',
		type: 'POST',
		dataType: 'json',
		data: {pais: pais},
	})
	.done(function(data) {
		console.log(data);
		$('#estado').empty();
		$('#municipios').empty();
		$('#estado').append('<option value="0">-Selecciona un estado</option>');
		$('#municipios').append('<option value="0">-Selecciona un municipio--</option>');
		$.each(data, function(index, val) {
		   $('#estado').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
		});
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function estadosFc(){
	var pais = $('#paisFact2').val();

	var paisT = $("#paisFact2 option:selected").text();
	//alert(paisT);
	$('#paisFact').val(paisT);

	$.ajax({
		url: 'ajax.php?c=cliente&f=estados2',
		type: 'POST',
		dataType: 'json',
		data: {pais: pais},
	})
	.done(function(data) {
		console.log(data);
		$('#estadoFact').empty();
		$('#municipiosFact').empty();
		$('#estadoFact').append('<option value="0">-Selecciona un estado</option>');
		$('#municipiosFact').append('<option value="0">-Selecciona un municipio--</option>');
		$.each(data, function(index, val) {
		   $('#estadoFact').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
		});
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function municipiosF(){
	var estado = $('#estado').val();

	$.ajax({
		url: 'ajax.php?c=cliente&f=municipios',
		type: 'POST',
		dataType: 'json',
		data: {estado: estado},
	})
	.done(function(data) {
		console.log(data);
		$('#municipios').empty();

		$.each(data, function(index, val) {
			$('#municipios').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
		});
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function municipiosFc(){
	var estado = $('#estadoFact').val();

	$.ajax({
		url: 'ajax.php?c=cliente&f=municipios',
		type: 'POST',
		dataType: 'json',
		data: {estado: estado},
	})
	.done(function(data) {
		console.log(data);
		$('#municipiosFact').empty();

		$.each(data, function(index, val) {
			$('#municipiosFact').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
		});
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

function isValidEmail(mail)
{
	return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
}
function isValidRfc(rfc)
{
		if(rfc.match(/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/i)){//Moral y Fisica
			return true;
		}else{
			return false;
		}
}

function isValidCurp(curp)
{
		if(curp.match(/^([a-z]{4})([0-9]{6})([a-z]{6})([0-9]{2})$/i)){
			return true;
		}else{
			return false;
		}

}

function guardaCliente(){
	//Datos Obligatorios

	var codigo =  $('#codigo').val();
	var nombre =  $('#nombre').val();
	var pais =  $('#selectPais').val();
	if( codigo == "" || nombre == "" || pais == ""){
		alert("Verifica haber llenado todos los comapos oblicatorios (*)");
		return;
	}
	//Datos Basicos
	var idCliente =  $('#idCliente').val();
	var tienda =  $('#tienda').val();
	var mumint =  $('#numint').val();
	var numext =  $('#numext').val();
	var direccion =  $('#direccion').val();
	var colonia =  $('#colonia').val();
	var cp =  $('#cp').val();
	var estado =  $('#selectEstado').val();
	var municipio =  $('#selectMunicipio').val();
	var email =  $('#email').val();
	var celular =  $('#celular').val();
	var tel1 =  $('#tel1').val();
	var tel2 =  $('#tel2').val();
	var ciudad = $('#ciudad').val();
	var cumpleanos = $('#cumpleanos').val();
	/// Datos de Facturacion
	var idComunFact = $('#idComunFact').val();
	var rfc =  $('#rfc').val();
	var curp =  $('#curp').val();
	var razonSocial = $('#razonSocial').val();
	var emailFacturacion = $('#emailFacturacion').val();
	var direccionFact = $('#direccionFact').val();
	var numextFact = $('#numextFact').val();
	var numintFact = $('#numintFact').val();
	var coloniaFact = $('#coloniaFact').val();
	var cpFact = $('#cpFact').val();
	var paisFact2 = $('#paisFact2').val();
	var estadoFact = $('#estadoFact').val();
	var municipiosFact = $('#municipiosFact').val();
	var ciudadFact = $('#ciudadFact').val();
	var paisFact = $('#paisFact').val();
	var regimenFact = $('#regimenFact').val();


	var vacios = false;
	var llenos = false;

	// Expresion regular para validar el correo
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	// VALIDACIONES
	// rfc
	if(rfc != ''){
		if(isValidRfc(rfc) == false){ alert('RFC no valido!!'); $("#rfc").focus(); return 0; }
	}
	// email
	if(email != ''){
		if (!regex.test(email.trim())) {
			alert('Email de facturación no valido!!'); $("#email").focus(); return 0;
		}
		//if(isValidEmail(email) == false){ alert('Email Basico no valido!!'); $("#emailemail").focus(); return 0; }
	}
	if(emailFacturacion != ''){
		if (!regex.test(emailFacturacion.trim())) {
			alert('Email de facturación no valido!!'); $("#emailFacturacion").focus(); return 0;
		}
		//if(isValidEmail(emailFacturacion) == false){ alert('Email de facturación no valido!!'); $("#emailFacturacion").focus(); return 0; }
	}

	// TODOS O NINGUNO
	if(razonSocial == '' && rfc == '' && emailFacturacion == '' && direccionFact == '' && numextFact == '' && coloniaFact == '' && cpFact == '' && estadoFact == '0' && municipiosFact == '0' && ciudadFact == '' && paisFact2 == '0'){
		//TODOS VACIOS
		vacios = true;
	}

	if(razonSocial != '' && rfc != '' && emailFacturacion != '' && direccionFact != '' && numextFact != '' && coloniaFact != '' && cpFact != '' && estadoFact != '0' && municipiosFact != '0' && ciudadFact != '' && paisFact2 != '0' && vacios == false) {
		llenos = true;
	}

	if (vacios == false && llenos == false ) {
		alert('Todos los datos de Facturación son requeridos');
		return 0;
	}

	//Datos Credito
	var tipoDeCredito = $('#tipoDeCredito').val();
	var diasCredito =  $('#diasCredito').val();
	var limiteCredito =  $('#limiteCredito').val();
	var moneda =  $('#moneda').val();
	var listaPrecio =  $('#listaPrecio').val();
	var descuentoPP = $('#descuentoPP').val();
	var interesesMoratorios = $('#interesesMoratorios').val();
	   if($('#checkVc').is(':checked')){
			perVenCre = 1
	   }else{
			perVenCre = 0;
	   }
	   if($('#checkLc').is(':checked')){
			perExLim = 1
	   }else{
			perExLim = 0;
	   }
	var banco = $('#banco').val();
	var numCuenta = $('#cuentaBanc').val();
	//Datos Comision
	var comisionVenta = $('#comisionVenta').val();
	var comisionCobranza =  $('#comisionCobranza').val();
	var empleado = $('#vendedor').val();
	//Datos de Envio
	var enviosDom = $('#enviosDom').val();

	var tipoClas = $('#tipoClas').val();
	var cuentaCont = $('#cuentaCont').val();

	if(codigo==''){
		alert('No puedes dejar el codigo vacio.');
		return false;
	}
	if(nombre==''){
		alert('No puedes dejar el Nombre vacio.');
		return false;
	}

	//alert('guardado');
	//return 0;

	$.ajax({
		url: 'ajax.php?c=cliente&f=guardaCliente',
		type: 'POST',
		dataType: 'json',
		data: {idCliente: idCliente,
				codigo : codigo,
				nombre : nombre,
				tienda : tienda,
				mumint : mumint,
				numext : numext,
				direccion: direccion,
				colonia : colonia,
				cp : cp,
				pais : pais,
				estado : estado,
				municipio: municipio,
				email : email,
				celular : celular,
				tel1 : tel1,
				tel2 : tel2,
				rfc : rfc,
				curp : curp,
				diasCredito : diasCredito,
				limiteCredito: limiteCredito,
				moneda : moneda,
				listaPrecio : listaPrecio,
				razonSocial : razonSocial,
				emailFacturacion : emailFacturacion,
				direccionFact : direccionFact,
				numextFact : numextFact,
				numintFact : numintFact,
				coloniaFact : coloniaFact,
				cpFact : cpFact,
				paisFact : paisFact2,
				estadoFact : estadoFact,
				municipiosFact : municipiosFact,
				ciudadFact : ciudadFact,
				tipoDeCredito : tipoDeCredito,
				descuentoPP : descuentoPP,
				interesesMoratorios : interesesMoratorios,
				perVenCre : perVenCre,
				perExLim : perExLim,
				comisionVenta : comisionVenta,
				comisionCobranza : comisionCobranza,
				empleado : empleado,
				enviosDom : enviosDom,
				tipoClas : tipoClas,
				idComunFact : idComunFact,
				regimenFact : regimenFact,
				banco : banco,
				numCuenta : numCuenta,
				cuentaCont : cuentaCont,
				ciudad : ciudad,
				cumpleanos : cumpleanos
			},
	})
	.done(function(data) {
		console.log(data);
		if(data.idClienteInser!=''){
			$('#modalSuccess').modal({
				show:true,
			});
		}else{
			alert('Algo Paso');
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function newClient(){
	var pathname = window.location.pathname;
	window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=cliente&f=index';
}
function back(){
   var pathname = window.location.pathname;
	window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=cliente&f=indexGrid';
}
function trans(){
	$('#razonSocial').val($('#nombre').val());
	$('#emailFacturacion').val($('#email').val());
	$('#direccionFact').val($('#direccion').val());
	var ext = $('#numext').val();
	var int = $('#numint').val();
	var x = '';
	if(int!=''){
		x = ext+' Int.'+int;
	}else{
		x = ext;
	}
	$('#numextFact').val(x);
	$('#numintFact').val($('#numint').val());
	$('#coloniaFact').val($('#colonia').val());
	$('#cpFact').val($('#cp').val());

	$('#estadoFact > option[value="'+$('#estado').val()+'"]').attr('selected', 'selected');
	$('#municipiosFact > option[value="'+$('#municipios').val()+'"]').attr('selected', 'selected');

}
function borraCliente(id){


		var txt;
		var r = confirm("Deseas desactivar al cliente?");
		if (r == true) {

			$.ajax({
				url: 'ajax.php?c=cliente&f=borraCliente',
				type: 'post',
				dataType: 'json',
				data: {id: id},
			})
			.done(function(resp) {
				console.log(resp);
				if(resp.estatus==true){
					alert('Se desactivo el Cliente');
					var pathname = window.location.pathname;
					window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=cliente&f=indexGrid';
				}

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		} else {
			//txt = "You pressed Cancel!";
		}



}
function activaCliente(id){
		var txt;
		var r = confirm("Deseas activar al cliente?");
		if (r == true) {

			$.ajax({
				url: 'ajax.php?c=cliente&f=activaCliente',
				type: 'post',
				dataType: 'json',
				data: {id: id},
			})
			.done(function(resp) {
				console.log(resp);
				if(resp.estatus==true){
					alert('Se activo el Cliente');
					var pathname = window.location.pathname;
					window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=cliente&f=indexGrid';
				}

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		} else {
			//txt = "You pressed Cancel!";
		}
}
window.onload = function() {
	$("#selectPais, #selectPais2, #selectPais3").select2({
        placeholder: "Selecciona País",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=cliente&f=buscarLocalizacion',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { idLoc : 1,
                    patron: params.term };
            },

            processResults: function (data) {
                //$("#selectPais").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    })
    .on("change", function(e) {
        $("#selectEstado").empty().trigger('change');
        $("#selectMunicipio").empty().trigger('change');
    });
    $("#selectEstado, #selectEstado3").select2({
        placeholder: "Selecciona Estado",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=cliente&f=buscarLocalizacion',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
            	if($(this).attr('id') == "selectEstado")
            		pais = $('#selectPais').val();
            	else
            		pais = $('#selectPais3').val();
                return { idLoc : 2,
                    pais : pais,
                    patron: params.term };
            },

            processResults: function (data) {
                //$("#selectEstado").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    })
    .on("change", function(e) {
        $("#selectMunicipio").empty().trigger('change');
    });;
    $("#selectMunicipio").select2({
        placeholder: "Selecciona Municipio",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=cliente&f=buscarLocalizacion',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { idLoc : 3,
                    estado : $('#selectEstado').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                //$("#selectMunicipio").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    });

    $('#btnNuevoPais').on('click', () => {
    	if( $('#inputNuevoPais').val() != "" ){
    		datos = {};
    		datos.nombre = $('#inputNuevoPais').val();
	    	$.ajax({
            	type: "POST",
	            url: 'ajax.php?c=cliente&f=nuevoPais',
	            data: datos,
	            timeout: 2000,
	            dataType: 'json',
	            complete: function() {

	            },
	            success: function(data) {
	                alert("Se ha agregado nuevo país");
	            },
	            error: function() {
	                alert("Ha ocurrido un error al procesar");
	            }
	        });
    	}
    	else {
    		alert("No puedes dejar el campos vacios");
    	}
    });
    $('#btnNuevoEstado').on('click', () => {
    	if( $('#inputNuevoEstado').val() != "" && $('#selectPais2').val() != ""  ) {
    		datos = {};
    		datos.nombre = $('#inputNuevoEstado').val();
    		datos.idPais = $('#selectPais2').val();
	    	$.ajax({
            	type: "POST",
	            url: 'ajax.php?c=cliente&f=nuevoEstado',
	            data: datos,
	            timeout: 2000,
	            dataType: 'json',
	            complete: function() {

	            },
	            success: function(data) {
	                alert("Se ha agregado nuevo estado sin problema alguno");
									$('#inputNuevoEstado').val('');
							},
	            error: function() {
	                alert("Ha ocurrido un error al procesar");
	            }
	        });
    	}
    	else {
    		alert("No puedes dejar el campos vacios");
			}
    });
    $('#btnNuevoMunicipio').on('click', () => {
    	if( $('#inputNuevoMunicipio').val() != "" && $('#selectPais3').val() != "" && $('#selectEstado2').val() != "" ){
    		datos = {};
    		datos.nombre = $('#inputNuevoMunicipio').val();
    		datos.idEstado = $('#selectEstado3').val();
	    	$.ajax({
            	type: "POST",
	            url: 'ajax.php?c=cliente&f=nuevoMunicipio',
	            data: datos,
	            timeout: 2000,
	            dataType: 'json',
	            complete: function() {

	            },
	            success: function(data) {
	                alert("Se ha agregado nuevo municipio");
									$('#inputNuevoMunicipio').val('');
	            },
	            error: function() {
	                alert("Ha ocurrido un error al procesar");
	            }
	        });
    	}
    	else {
    		alert("No puedes dejar el campos vacios");
    	}
    });
}
