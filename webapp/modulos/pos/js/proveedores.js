function newProve(){
	var pathname = window.location.pathname;
	window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=proveedores&f=index';
}

function back(){
	var pathname = window.location.pathname;
	window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=proveedores&f=indexGrid';   
}

// select depen
function estadosF(){
	var pais = $('#pais').val();
	$.ajax({
		url: 'ajax.php?c=proveedores&f=estados2',
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

function municipiosF(){
	var estado = $('#estado').val();
	$.ajax({
		url: 'ajax.php?c=proveedores&f=municipios',
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

function tipoTerceroOperacion2(){
	var tipoTercero = $('#tipoTercero').val();
	$.ajax({
		url: 'ajax.php?c=proveedores&f=tipoTerceroOperacion2',
		type: 'POST',
		dataType: 'json',
		data: {tipoTercero: tipoTercero},
	})
	.done(function(data) {
		console.log(data);
		$('#tipoTerceroOperacion').empty();
		$('#tipoTerceroOperacion').append('<option value="0">Seleccina un tipo de operacion</option>');
		$.each(data, function(index, val) {
			$('#tipoTerceroOperacion').append('<option value="'+val.id+'">'+val.tipoOperacion+'</option>');
		});
	})
}

/// CONTACTOS
function agregarContacto(){
	var idProveedor = $('#idProveedor').val();
	var idC = $('#idC').val();
	var nombreC = $('#nombreC').val();
	var cargoC = $('#cargoC').val();
	var emailC = $('#emailC').val();
	var telefonoC = $('#telefonoC').val();
	var celularC = $('#celularC').val();
	var aux = 1;

	if (nombreC == "") {
		alert("No es posible agregar un registro en blanco");
		$('#nombreC').focus();
		return 0;
	}

	$("#contacList tr").each(function (index) {
		nomb = $(this).attr('nombre');
		if(nombreC == nomb){
			alert('El nombre de contacto ya esta en la lista');
			aux = 0;
		}
	});
	if(aux==0){ return false; }
	var a = 1;
	$('#contacList tr:last').after('<tr id="cont_'+nombreC+'" idRel="0" nombre="'+nombreC+'" cargo="'+cargoC+'" email="'+emailC+'" telefono="'+telefonoC+'" celular="'+celularC+'">'+
		'<td><span class="glyphicon glyphicon-remove" onclick="removeProve(\''+nombreC+'\');"></span></span></td>'+
		'<td>'+nombreC+'</td>'+
		'<td>'+cargoC+'</td>'+
		'<td>'+emailC+'</td>'+
		'<td>'+telefonoC+'</td>'+
		'<td>'+celularC+'</td>'+
		'</tr>');
}

function removeProve(id){
	$('#cont_'+id).remove();
}

/*
function savelist(){
	var stringCont = '';
	$("#contacList tr").each(function (index) 
	{   

		idrel 		= $(this).attr('idrel');
		idProveedor = $("#idProveedor").val();

		nombre = $(this).attr('nombre');
		cargo = $(this).attr('cargo');
		email = $(this).attr('email');
		telefono = $(this).attr('telefono');
		celular = $(this).attr('celular');

		if(idrel == 0){ // solo gurarda los nuevos en la lista
			stringCont +='-'+nombre+'-'+cargo+'-'+email+'-'+telefono+'-'+celular+'#';
		}
	});

	var str = stringCont.split('#');
	//str.splice(0, 1); // elimina el primer obj
	str.splice(-1, 1); // elimina el primer obj
	//var str = stringCont.split('!');
	console.log(str);
}
*/	
/// CONTACOTOS FIN

/// BANCOS
function agregarBanco(){
	var idBanco = $('#selectBanco').val();
	var nombre = $("#selectBanco option:selected").text(); 
	var noCuentaBan = $("#noCuentaBan").val();
	var aux = 1;
	$("#bancoList tr").each(function (index) {   
		//idbanc = $(this).attr('idbanco');
		nctaBanc = $(this).attr('numct');
		if (noCuentaBan == nctaBanc) {
//		if(idBanco == idbanc){
			alert('El No. de cuenta y/o tarjeta ya esta en la lista');
			aux = 0;
		}
	});
	if(aux==0){
		return false;
	}
	$('#bancoList tr:last').after('<tr id="idBan_'+idBanco+'" idbanco="'+idBanco+'" idRel="0" numct="'+noCuentaBan+'"><td><span class="glyphicon glyphicon-remove" onclick="removeBanco('+idBanco+');"></span></td><td>'+nombre+'</td><td>'+noCuentaBan+'</td></tr>');
}

function removeBanco(id){
	$('#idBan_'+id).remove();
}
/*
function savebancos(){
	var stringCont = '';
	$("#bancoList tr").each(function (index) 
	{   
		idProveedor = $("#idProveedor").val();
		idrel 		= $(this).attr('idrel');
		idbanco 	= $(this).attr('idbanco');
		numct 		= $(this).attr('numct');

		if(idrel == 0){ // solo gurarda los nuevos en la lista
			stringCont +='-'+idbanco+'-'+numct+'#';
		}
		
	});
	var str = stringCont.split('#');
	//str.splice(0, 1); // elimina el primer obj
	str.splice(-1, 1); // elimina el primer obj
	//var str = stringCont.split('!');
	console.log(str);
}
*/	
/// BANCOS FIN

// DATOS FISCALES
function probar(){

	var tasaAsumir = 0;
	var tasa = '';
	var otra1 = $("#otra1").val();
	var otra2 = $("#otra2").val();

	if($('input:radio[name=ivasumir]:checked').val() == 1){
		tasaAsumir = 16;
		tasa = '16%';
	}
	if($('input:radio[name=ivasumir]:checked').val() == 2){
		tasaAsumir = 11;
		tasa = '11%';
	}
	if($('input:radio[name=ivasumir]:checked').val() == 3){
		tasaAsumir = 0;
		tasa = '0%';
	}
	if($('input:radio[name=ivasumir]:checked').val() == 4){
		tasaAsumir = 0;
		tasa = 'Exenta';
	}
	if($('input:radio[name=ivasumir]:checked').val() == 5){
		tasaAsumir = 15;
		tasa = '15%';
	}
	if($('input:radio[name=ivasumir]:checked').val() == 6){
		tasaAsumir = 10;
		tasa = '10%';
	}
	if($('input:radio[name=ivasumir]:checked').val() == 1234){		
		tasaAsumir = otra1;
		tasa = 'Otra tasa 1';
	}
	if($('input:radio[name=ivasumir]:checked').val() == 12345){			
		tasaAsumir = otra2;
		tasa = 'Otra tasa 1';
	}
	var selected = ''; 
			
	$('#ivas input[type=checkbox]').each(function(){
		if (this.checked) {
			if($(this).val() == 1){
				selected += '16-16%'+', ';
			}
			if($(this).val() == 2){
				selected += '11-11%'+', ';
			}
			if($(this).val() == 3){
				selected += '0-0%'+', ';
			}
			if($(this).val() == 4){
				selected += '0-Exenta'+', ';
			}
			if($(this).val() == 5){
				selected += '15-15%'+', ';
			}
			if($(this).val() == 6){
				selected += '10-10%'+', ';
			}
			if($(this).val() == 1234){
				selected += +otra1+'-Otra tasa 1, ';
			}
			if($(this).val() == 12345){
				selected += +otra2+'-Otra tasa 2, ';
			}
			
		}
	});
	
	//save pro
	console.log(tasaAsumir);
	//save tasa
	console.log(selected);
	//alert(tasaAsumir);
}
// DATOS FISCALES FIN

function isValidEmail(mail) {
	return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
}

function isValidRfc(rfc) {
	if(rfc.match(/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/i)){//Moral y Fisica
		return true;
	}else{
		return false;
	}
}

///// 
function guardaProveedor(aux) {
	// basicos
	var idProveedor 		= $("#idProveedor").val();
	var codigo 				= $("#codigo").val();
	var tipoClas 			= $("#tipoClas").val();
	var razon_social 		= $("#razon_social").val();
	var rfc 				= $("#rfc").val();
	var nombre_comercial 	= $("#nombre_comercial").val();
	var calle 				= $("#calle").val();
	var no_ext 				= $("#no_ext").val();
	var no_int 				= $("#no_int").val();
	var colonia 			= $("#colonia").val();
	var cp 					= $("#cp").val();
	var pais 				= $("#selectPais").val();
	var estado 				= $("#selectEstado").val();
	var municipios 			= $("#selectMunicipio").val();
	var ciudad 				= $("#ciudad").val();
	var nombre_contacto 	= $("#nombre_contacto").val();
	var email 				= $("#email").val();
	var telefono 			= $("#telefono").val();
	var web 				= $("#web").val();

	// \\\\\\\\\\\\\\      V A L I D A C I O N E S
	if (codigo=='') { alert('Código de proveedor requerido'); $("#codigo").focus(); return 0; }
	if (razon_social=='') { alert('Nombre y/o Razón social requerido'); $("#razon_social").focus(); return 0; }
	if (rfc=='') { alert('R.F.C. requerido'); $("#rfc").focus(); return 0; }
	if (pais==0) { alert('País requerido'); $("#pais").focus(); return 0; }
	if (estado==0) { alert('Estado requerido'); $("#estado").focus(); return 0; }
	

	// Expresion regular para validar el correo
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	
	// RFC
	if(rfc != ''){ if(isValidRfc(rfc) == false){ alert('RFC no valido!!'); $("#rfc").focus(); return 0; } }

    // email
    if(email != ''){
    	if (!regex.test(email.trim())) {
    		alert('Correo electrónico no valido!!'); $("#email").focus(); return 0;
    	}
    	//if(isValidEmail(email) == false){ alert('Email Basico no valido!!'); $("#emailemail").focus(); return 0; }
    }

    // contactos
    	var stringCont = '';
    	$("#contacList tr").each(function (index)
    	{   
				idrel 		= $(this).attr('idrel');
				nombre = $(this).attr('nombre');
				cargo = $(this).attr('cargo');
				emailC = $(this).attr('email');
				telefonoC = $(this).attr('telefono');
				celular = $(this).attr('celular');

				if(idrel == 0){ // solo gurarda los nuevos en la lista
					stringCont +='-'+nombre+'-'+cargo+'-'+emailC+'-'+telefonoC+'-'+celular+'#';
				}
			});

	// credito
		var diasCredito 		= $("#diasCredito").val();
		var saldo 				= $("#saldo").val();
		var limiteCredito 		= $("#limiteCredito").val();

	// datos fiscales
		var tipo 								 = $("#tipo").val();
		var cuenta 							 = $("#cuenta").val();
		var beneficiario 				 = $("#beneficiario").val();
		var cuentaCliente 			 = $("#cuentaCliente").val();
		var tipoTercero 				 = $("#tipoTercero").val();
		var tipoTerceroOperacion = $("#tipoTerceroOperacion").val();
		var numidfiscal 				 = $("#numidfiscal").val();
		var nombrextranjero 		 = $("#nombrextranjero").val();
		var nacionalidad 				 = $("#nacionalidad").val();
		var ivaretenido 				 = $("#ivaretenido").val();
		var isretenido 					 = $("#isretenido").val();
		var idtipoiva 					 = $("#idtipoiva").val();

		var prepolizas_provision = $('#prepolizas_provision').val();
		var prepolizas_pago 		 = $('#prepolizas_pago').val();
		var cuentas_gastos 			 = $('#cuentas_gastos').val();
	


		/////Facturacion
		var rfcFac = $('#rfcF').val();
		var razonSocialF = $('#razonSocialF').val();
		var emailFacturacion = $('#emailFacturacion').val();

		if ($("#cmbDatosF").val() == 1) {
			if (cuenta == 0) { alert('Cuenta inválida'); $("#cuenta").focus(); return 0; }
			if (tipoTercero == 0) { alert('El tipo tercero es inválido'); $("#tipoTercero").focus(); return 0; }
			if (tipoTerceroOperacion == 0) { alert('Tipo de operación de tercero inválido'); $("#tipoTerceroOperacion").focus(); return 0; }
		}

		var tasa = '';
			var tasaAsumir = 0;
			var otra1 = $("#otra1").val();
			var otra2 = $("#otra2").val();
			if($('input:radio[name=ivasumir]:checked').val() == 1){
				tasaAsumir = 16; tasa = '16%';
			}
			if($('input:radio[name=ivasumir]:checked').val() == 2){
				tasaAsumir = 11; tasa = '11%';
			}
			if($('input:radio[name=ivasumir]:checked').val() == 3){
				tasaAsumir = 0; tasa = '0%';
			}
			if($('input:radio[name=ivasumir]:checked').val() == 4){
				tasaAsumir = 0; tasa = 'Exenta';
			}
			if($('input:radio[name=ivasumir]:checked').val() == 5){
				tasaAsumir = 15; tasa = '15%';
			}
			if($('input:radio[name=ivasumir]:checked').val() == 6){
				tasaAsumir = 10; tasa = '10%';
			}
			if($('input:radio[name=ivasumir]:checked').val() == 1234){		
				tasaAsumir = otra1; tasa = 'Otra tasa 1';
			}
			if($('input:radio[name=ivasumir]:checked').val() == 12345){			
				tasaAsumir = otra2; tasa = 'Otra tasa 1';
			}

		var tasas = ''; 	
			$('#ivas input[type=checkbox]').each(function(){
				if (this.checked) {
					if($(this).val() == 1){
						tasas += '16-16%'+', ';
					}
					if($(this).val() == 2){
						tasas += '11-11%'+', ';
					}
					if($(this).val() == 3){
						tasas += '0-0%'+', ';
					}
					if($(this).val() == 4){
						tasas += '0-Exenta'+', ';
					}
					if($(this).val() == 5){
						tasas += '15-15%'+', ';
					}
					if($(this).val() == 6){
						tasas += '10-10%'+', ';
					}
					if($(this).val() == 1234){
						tasas += +otra1+'-Otra tasa 1, ';
					}
					if($(this).val() == 12345){
						tasas += +otra2+'-Otra tasa 2, ';
					}
					
				}
			});

	// bancos proveedores
		var stringBanco = '';
			$("#bancoList tr").each(function (index) 
			{   
				idrel 		= $(this).attr('idrel');
				idbanco 	= $(this).attr('idbanco');
				numct 		= $(this).attr('numct');

				if(idrel == 0){ // solo guarda los nuevos en la lista
					stringBanco +='-'+idbanco+'-'+numct+'#';
				}
			});

	// validacion
	var minimoPieza = $('#minimoPieza').val();
	var minimoImportePedido = $('#minimoImportePedido').val();
	var lugarEntrega = $('#lugarEntrega').val();

	// ajax
	console.log(stringCont);
	console.log(stringBanco);

	$.ajax({
		url: 'ajax.php?c=proveedores&f=saveProveedor',
		type: 'POST',
		dataType: 'json',
		data: {idProveedor:idProveedor,
				codigo:codigo,
				tipoClas:tipoClas,
				razon_social:razon_social,
				rfc:rfc,
				nombre_comercial:nombre_comercial,
				calle:calle,
				no_ext:no_ext,
				no_int:no_int,
				colonia:colonia,
				cp:cp,
				pais:pais,
				estado:estado,
				municipios:municipios,
				ciudad:ciudad,
				nombre_contacto:nombre_contacto,
				email:email,
				telefono:telefono,
				web:web,
				stringCont:stringCont,
				diasCredito:diasCredito,
				saldo:saldo,
				limiteCredito:limiteCredito,
				tipo:tipo,
				cuenta:cuenta,
				beneficiario:beneficiario,
				cuentaCliente:cuentaCliente,
				tipoTercero:tipoTercero,
				tipoTerceroOperacion:tipoTerceroOperacion,
				numidfiscal:numidfiscal,
				nombrextranjero:nombrextranjero,
				nacionalidad:nacionalidad,
				ivaretenido:ivaretenido,
				isretenido:isretenido,
				idtipoiva:idtipoiva,
				tasa:tasa,
				tasaAsumir:tasaAsumir,
				tasas:tasas,
				stringBanco:stringBanco,
				aux:aux,
				minimoPieza : minimoPieza,
				minimoImportePedido : minimoImportePedido,
				lugarEntrega : lugarEntrega,
				prepolizas_provision: prepolizas_provision,
				prepolizas_pago: prepolizas_pago,
				cuentas_gastos: cuentas_gastos,
				rfcFac : rfcFac,
			 	razonSocialF: razonSocialF,
			 	emailFacturacion : emailFacturacion,
			 	
		},
	})
	.done(function(data) {
		console.log(data);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		var pathname = window.location.pathname;
		$('#modalSuccess').modal('show');
		window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=proveedores&f=indexGrid'; 

	}); 
}

function tasa(){
	var idProveedor = $('#idProveedor').val();
	var idtasaAsumir = $('#idtasaAsumir').val();
	//alert(idtasaAsumir);
	$.ajax({
		url: 'ajax.php?c=proveedores&f=tasas',
		type: 'POST',
		dataType: 'json',
		data: {idProveedor:idProveedor,idtasaAsumir:idtasaAsumir},
	})
	.done(function(data) {

		$.each(data.tasas, function(index, val) {
			 var tasa = val.tasa;
			 if(tasa == '16%'){
				$('input[ch=1]').attr('checked',true); 
				$('input[ra=1]').attr('disabled',false);
			 }
			 if(tasa == '11%'){
				$('input[ch=2]').attr('checked',true); 
				$('input[ra=2]').attr('disabled',false);
			 }
			 if(tasa == '0%'){
				$('input[ch=3]').attr('checked',true); 
				$('input[ra=3]').attr('disabled',false);
			 }
			 if(tasa == 'Exenta'){
				$('input[ch=4]').attr('checked',true);
				$('input[ra=4]').attr('disabled',false);
			 }
			 if(tasa == '15%'){
				$('input[ch=5]').attr('checked',true);
				$('input[ra=5]').attr('disabled',false);
			 }
			 if(tasa == '10%'){
				$('input[ch=6]').attr('checked',true);
				$('input[ra=6]').attr('disabled',false);
			 }
			 if(tasa == 'Otra Tasa 1'){
				$('input[ch=1234]').attr('checked',true);
				$('input[ra=1234]').attr('disabled',false);
			 }
			 if(tasa == 'Otra Tasa 2'){
				$('input[ch=12345]').attr('checked',true);
				$('input[ra=12345]').attr('disabled',false);
			 }
		});

		$.each(data.tasasAsumir, function(index, val) {
			var tasa = val.tasa
			if(tasa == '16%'){                 	
				$('input:radio[name=ivasumir]:nth(0)').prop('checked',true);
			 }
			 if(tasa == '11%'){  
				$('input:radio[name=ivasumir]:nth(1)').prop('checked',true);
			 }
			 if(tasa == '0%'){                 	
				$('input:radio[name=ivasumir]:nth(2)').prop('checked',true);             
			 }
			 if(tasa == 'Exenta'){                 	
				$('input:radio[name=ivasumir]:nth(3)').prop('checked',true);                 	
			 }
			 if(tasa == '15%'){                 	
				$('input:radio[name=ivasumir]:nth(4)').prop('checked',true);                 	
			 }
			 if(tasa == '10%'){                 	
				$('input:radio[name=ivasumir]:nth(5)').prop('checked',true);            
			 }
			 if(tasa == 'Otra Tasa 1'){                 	
				$('input:radio[name=ivasumir]:nth(6)').prop('checked',true);            	
			 }
			 if(tasa == 'Otra Tasa 2'){                 	
				$('input:radio[name=ivasumir]:nth(7)').prop('checked',true);              	
			 }
		});

	})
}

function borraProve(id){
	var txt;
	var r = confirm("¿Deseas desactivar a este proveedor?");
	if (r == true) {

		$.ajax({
			url: 'ajax.php?c=proveedores&f=borraProve',
			type: 'post',
			data: {id: id},
		})
		.done(function(resp) {
			console.log(resp);

			if(resp==1){
				alert('Se desactivo al proveedor con exito!');
				var pathname = window.location.pathname;
				window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=proveedores&f=indexGrid';
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





function activaProve(id){
	var txt;
	var r = confirm("¿Deseas activar al proveedor?");
	if (r == true) {

		$.ajax({
			url: 'ajax.php?c=proveedores&f=activaProve',
			type: 'post',
			data: {id: id},
		})
		.done(function(resp) {
			console.log(resp);

			if(resp==1){
				alert('Se activo este proveedor con exito!');
				var pathname = window.location.pathname;
				window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=proveedores&f=indexGrid';
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



function transferirDat(){
    var nombre = $('#nombre_contacto').val();
    $('#nombreC').val(nombre);

    var email = $('#email').val();
    $('#emailC').val(email);


    var phone = $('#telefono').val();
    $('#telefonoC').val(phone);
}


function borraContactoProve(id){
	alert(id);
	var txt;
	var r = confirm("¿Deseas eliminar el contacto?");
	if (r == true) {

		$.ajax({
			url: 'ajax.php?c=proveedores&f=borraContactoProve',
			type: 'post',
			data: {id: id},
		})
		.done(function(resp) {
			console.log(resp);

			if(resp==1){
				alert('Se eliminó el contacto con éxito!');
				var pathname = window.location.pathname;
				window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=proveedores&f=indexGrid';
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



function cuentasP(id){
    window.parent.agregatab("../../modulos/appministra/index.php?c=cuentas&f=cuentasxpagar&id="+id+"","Aplicar pago","",2081); 
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
                $("#selectPais").empty();
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
                $("#selectEstado").empty();
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
                $("#selectMunicipio").empty();
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
	            	alert("Siempre");
	            },
	            success: function(data) {
	                alert("Exito");
	            },
	            error: function() {  
	                alert("Error");                                
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
	            	alert("Siempre");
	            },
	            success: function(data) {
	                alert("Exito");
	            },
	            error: function() {  
	                alert("Error");                                
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
	            	alert("Siempre");
	            },
	            success: function(data) {
	                alert("Exito");
	            },
	            error: function() {  
	                alert("Error");                                
	            }
	        });
    	}
    	else {
    		alert("No puedes dejar el campos vacios");
    	} 
    });

}
