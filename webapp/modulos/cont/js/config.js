$(document).ready(function() {

	$('#nmloader_div',window.parent.document).hide();
	$('.date-pick').datePicker({startDate:'01/01/2000'});
	cambia_fecha();
	if($('#period').val() == 'm')
	{
		cambia_periodo();
	}
	estructura_vacia();

	setTimeout(function(){
		$('#nmloader_div',window.parent.document).hide();
	}, 2000);
	$("#cl_num").select2({
				 width : "150px"
				});
	$("#carga3").click(function() {
		$('#lev_manual').click();
	});
	$('#txtMascara').change(function() {
		$('#structure').val($(this).val())
	});
	//--------------------------------Comienza Facturas-----------------------***
/*$("#Facturas").dialog(
	 {
			 autoOpen: false,
			 width: 700,
			 height: 510,
			 modal: true,
			 show:
			 {
				effect: "clip",
				duration: 500
			 },
				hide:
			 {
				effect: "clip",
				duration: 500
			 },
			 buttons: 
			{
				"Cerrar": function () 
				{
				 $("#Facturas").dialog('close')
				}
			}
		});*/
//--------------------------------Termina Facturas-----------------------***
$( '#fac' )
  .submit( function( e ) {
	$('#verif').css('display','inline');
	$.ajax( {
	  url: 'ajax.php?c=CaptPolizas&f=subeFacturaZip',
	  type: 'POST',
	  data: new FormData( this ),
	  processData: false,
	  contentType: false
	} ).done(function( data1 ) {
		//$("#Facturas").dialog('refresh')
			$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
			{
				IdPoliza: 'temporales'
			},
			function(data2)
			{
				
				$('#listaFacturas').html(data2)
				
			});
			$('#factura').val('')
			data1 = data1.split('-/-*');
			$('#verif').css('display','none');

			if(parseInt(data1[0]))
			{
				if(parseInt(data1[3]))
				{
					alert('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4])
				}

				if(parseInt(data1[1]))
				{
					alert(data1[1]+' Archivos Validados: \n'+data1[2])
				}
				if(parseInt(data1[5])){
					alert("Para copiar archivos repetidos debe hacerlo desde almacen");
				}
			}
			else
			{
				alert("El archivo zip no cumple con el formato correcto\nDebe llamarse igual que la carpeta que contiene los xmls.\nSólo debe contener una carpeta.\nEl nombre de la carpeta no debe contener espacios en blanco.");
			}	
	
	});
	e.preventDefault();
  } );
});

function validaSeparador()
{
	var mascara = $('#txtMascara').val();
	var separador = $('#txtSeparador').val();

	if (mascara.indexOf(separador) == -1) {
		alert("El separador no corresponde con la mascara");
		$('#txtSeparador').val('');
		$('#txtSeparador').focus();
	}
}

function changeStatus()
{
	if($('.rdNormal:checked').val() == 1)
	{
		$('#structure').removeAttr('readonly');
	}else
	{
		$('#structure').attr('readonly', '');
	}
}

function estructura_vacia()
{
	if($('#structure').val() == '')
	{
		$('#structure').val('999.9999');
	}
}

function cambia_periodo()
{
	var cadena=$('.date-pick').val();
	var cad = cadena.split("-");
	var fin;
	if($('#current_period').val() == 13)
	{
		$('#inicio_mes').html('31-12-'+cad[2]);
		$('#fin_mes').html('31-12-'+cad[2]);
	}
	else
	{
		$('#inicio_mes').html(moment(cad[2]+'-'+cad[1]+'-'+cad[0]).add('months', $('#current_period').val()-1).format('DD-MM-YYYY'));
		fin = moment(cad[2]+'-'+cad[1]+'-'+cad[0]).add('months', $('#current_period').val()).format('YYYY-MM-DD');
		fin = moment(fin).subtract('days',1).format('DD-MM-YYYY');
		$('#fin_mes').html(fin);
	}
}
function cambia_fecha()
{
	var cadena=$('.date-pick').val();
	var cad = cadena.split("-");
	var dias;

	if(moment([parseInt(cad[2])+1]).isLeapYear() && parseInt(cad[1])>=3 || moment([parseInt(cad[2])]).isLeapYear() && parseInt(cad[1])<3)
		{//Validacion de año bisiesto
			dias=365;		
		}
		else
		{
			dias = 364;
		}
		
		$('#fecha_fin').val(moment(cad[2]+'-'+cad[1]+'-'+cad[0]).add('days', dias).format('DD-MM-YYYY'))
		cambia_periodo();
}

	function regresar()
	{
		window.location = 'index.php?c=Config&f=mainPage';
	}

//------------------------- ** Validaciones ** ------------------------------

function validaciones(cont)
{  
	 // primera comprobación 
	 if(cont.rfc.value == '')
	 { 
		// informamos del error 
		alert('Escribe tu RFC');
		// seleccionamos el campo incorrecto 
		cont.rfc.focus();
		return false;
	}

	if(cont.periods.value == '13')
	{
		if(cont.current_period.value == '' || cont.current_period.value == 0 || cont.current_period.value > 13)
		{
			// informamos del error
			alert('Escribe un periodo vigente');
			// seleccionamos el campo incorrecto
			cont.current_period.focus();
			return false;
		}	 
	}

	if($("#reinicia").attr('activo') == 'no')
	{
		if(cont.tipoCarga.value == '3' && (cont.txtMascara.value == '' || cont.txtSeparador.value == '' || $("#archivo1").val() == ''))
		{
			alert('Para cargar de otros sistemas es necesario subir el archivo de cuentas, agregar una mascara y un separador.')
			$('#nmloader_div',window.parent.document).hide();
			return false;
		}
	}
}

function validar_let(e)
{ // 1
	tecla = (document.all) ? e.keyCode : e.which; // 2
	if (tecla==8) return true; // 3
	patron = /\d/; // Solo acepta números 4
	te = String.fromCharCode(tecla); // 5
	return patron.test(te); // 6
} 

function reiniciar(bancos)
{
	if(bancos==0){
		var primeraPregunta = confirm("Esta seguro que desea reiniciar la contabilidad? \nEsto borrara todas las cuentas y polizas de su historial y no podra recuperarlos.");
	}else{
		var primeraPregunta = confirm("Esta seguro que desea reiniciar los catalogos de Bancos? \nEsto borrara todo su historial y no podra recuperarlo.");
	}
	var contrasena;

	if(primeraPregunta)
	{
		contrasena = prompt('Es necesario la contraseña del administrador para continuar con esta operacion.');

			if(contrasena)
			{
				$.post("ajax.php?c=Config&f=passAdmin",
					{
						Pass: contrasena
					},
					function(data)
					{
						$('#nmloader_div',window.parent.document).show();
						//alert(data)
						if(data == 'OK')
						{	
							var conservar, segundaPregunta;
							segundaPregunta = confirm("¿Desea conservar su árbol de cuentas?");
							if (segundaPregunta) {
								conservar = 1;
							} else {
								conservar = 0;
							}
							$.post("ajax.php?c=Config&f=ReiniciarContabilidad",
							{
								conservarArbol: conservar
							},
							function(data)
							{
								if(data == "Listo")
								{
									if(bancos == 0){
										if (conservar == 1) {
											var mensaje = 'Se ha eliminado todo el registro de polizas y movimientos.';
										} else {
											var mensaje = 'Se ha eliminado todo el registro de polizas, movimientos y cuentas.'
										}
										alert(mensaje);
										window.location.replace("index.php?c=Config&f=mainPage");
									}else{//si tiene bancos 
										if(confirm("Se ha eliminado todo el registro de polizas, movimientos, cuentas y documentos\nDESEA ELIMINAR LOS CATALOGOS DE BANCOS?")){
											$.post("ajax.php?c=Config&f=ReiniciarBancos",{},
											function(data)
											{
													if(data == "Listo")
													{
														alert('Se ha eliminado todo el registro de Catalogos.');
														window.location.replace("index.php?c=Config&f=mainPage");
													}
													else
													{
														alert("Hubo un problema");
														$('#nmloader_div',window.parent.document).hide();
													}
												
											});
										}else{//si no reinicia bancos mandara para configurar acontia
											window.location.replace("index.php?c=Config&f=mainPage");
										}
										
									}
								}
								else
								{
									alert("Hubo un problema");
									$('#nmloader_div',window.parent.document).hide();
								}
							});
							
						}
						else
						{
							alert('***[Contraseña Incorrecta]***');
							$('#nmloader_div',window.parent.document).hide();
						}
					});
			}
	}
}

function facturasConf()
{
	$('#Facturas #buscadas').show();
	$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
			{
				IdPoliza: 'temporales'
			 },
			 function(data)
			 {
				console.log(data);
				$('#listaFacturas').html(data)
				
			 });
	$('#Facturas').modal('show'); 
	$('#Facturas #buscadas').hide();
}

