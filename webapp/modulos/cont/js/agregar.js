
 $(function()
 {
 	//Parche que resuelve el problema de los select2 con bootstrap
 	$.fn.modal.Constructor.prototype.enforceFocus = function () {};
	$.ui.dialog.prototype._allowInteraction = function(e) {
		return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-drop').length;
};
Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};
var mascara;
$('#cuenta').on('select2-open',function(e){
if($("#tipo_cuentas").val()=='manual_code')
{
	mascara = $("#estruc").val();
}
else
{
	mascara = "9.9.9.9.9.9.9.9.9.9"
}
$('.select2-input').mask(mascara, {'translation': {
                                        A: {pattern: /[0-9]/}  
                                      }
                                })
    $('.select2-input').keypress(function(event) {
    	if(event.which <48 || event.which > 57)
    	{
    		$('.select2-input').unmask($("#estruc").val())
    	}
    });
});
$('#numpolload').hide();
$('#facturaSelect').change(function () {
	if(!$("#sel_multiple:checked").val())
	{
		
		var facsel = $('#facturaSelect option:selected').attr('datos');
		//alert(facsel)
		if(facsel != '-')
		{

		facsel = facsel.split('_');
		facsel = facsel[2].split('.');
	    $('#referencia_mov').val(facsel[0]);
		}
		else
		{
			$('#referencia_mov').val(facsel);	
		}
	}
	else
	{
		$('#referencia_mov').val('Grupo de facturas');	
	}
  });

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
				IdPoliza: $('#idpoliza').val()
			},
			function(data2)
			{
				
				$('#listaFacturas').html(data2)
				
			});
			$('#factura').val('')
			actualizaListaFac();
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

	actualizaListaMov();
	actualizaListaMovFacturas();
	dias_periodo();
	$('#cargando-mensaje').css('display','none');
	//---------------------------------------------------- Comienza Habilita y desabilita proveedores ----------------************
	if($('#tipoPoliza').val() == 1)
	{
	 //$('#botonProveedores').attr('disabled','disabled');
	// $('#botonProveedores').css('display','none');
	$('#botonClientes').css('display','inline');
	}
	
	if($('#tipoPoliza').val() == 2)
	{
		//$('#botonProveedores').removeAttr('disabled');
		$('#botonProveedores').css('display','inline');
		//$('#botonClientes').css('display','none');
	}

	if($('#tipoPoliza').val() == 3)
	{
		//$('#botonProveedores').removeAttr('disabled');
		$('#botonProveedores').css('display','inline');
		//$('#botonClientes').css('display','none');
	}


	$('#tipoPoliza').change(function()
	{
		if($('#tipoPoliza').val() == 1)
		{
	 		//$('#botonProveedores').attr('disabled','disabled');
	 		//$('#botonProveedores').css('display','none');
	 		$('#botonClientes').css('display','inline');
	 		//alert('Ingresos')
		}
		else
		{
			$('#botonProveedores').css('display','inline');
			//$('#botonClientes').css('display','none');
			//alert('Otros')
		}
		$('#numpol').hide();
		$('#numpolload').show();
		$.post("ajax.php?c=CaptPolizas&f=UltimoNumPol",
 		 {
    		Periodo: $("#periodos").val(),
    		Ejercicio: $("#IdExercise").val(),
    		TipoPol: $("#tipoPoliza").val()
  		 },
  		 function(data)
  		 {
  		 	//alert("el ultimo es: "+data)
  			 var numtipo = $("#numtipo").val().split('-')
			if($('#tipoPoliza').val() == numtipo[0])
			{
				$("#numpol").val(numtipo[1])
			}
			else
			{
				$('#numpol').val(data);
			}
  		 	$('#numpolload').hide();
  		 	$('#numpol').show();
  		 });


	
	});
	//---------------------------------------------------- Termina Habilita y desabilita proveedores ----------------************
$('#numpol').change(function () 
{
$.post("ajax.php?c=CaptPolizas&f=ExisteNumPol",
        {
            Periodo:    $("#periodos").val(),
            Ejercicio:  $("#IdExercise").val(),
            TipoPol:    $("#tipoPoliza").val(),
            NumPol:     $("#numpol").val(),
            Id:         $('#idpoliza').val()
        },
        function(existe)
        {
          //alert('existe: '+existe)
            if(parseInt(existe))
            {
              	alert("El numero de poliza ya existe"); 
              	$("#guardarpolizaboton2").attr('disabled',true)
              	$("#actualizarboton2").attr('disabled',true)
            }
            else
            {
            	$("#guardarpolizaboton2").removeAttr('disabled')
            	$("#actualizarboton2").removeAttr('disabled')
            }
        });
});
	 $("#cuenta").select2({
				 width : "150px"
				});

	 $("#ProveedoresSelect").select2({
				 width : "150px"
				});

	$("#agregar_movimientos_btn").on("click", function(){
		var todos = 0; 
		if($('#cuenta').val() == "" || $('#cuenta').val() == "0.0.0.0")
		{
			todos += 1;
		}

		if($('#concepto_mov').val() == "")
		{
			alert("El campo de concepto esta vacío.");
			todos += 1;
		}

		if(($('#abono').val() == "" && $('#cargo').val() == "") /*|| ($('#abono').val() == "0.00" && $('#cargo').val() == "0.00") || ($('#abono').val() == 0 && $('#cargo').val() == 0)*/)
		{
			todos += 1;
		}


		 if(todos==0)
		 {
			 agregarMov();
			 //setInterval(function(){actualizaListaMov()},1000);
			 //actualizaListaMov();
			 $('#cuenta').val('');
			 //$('#referencia_mov').val('');
			 //$('#concepto_mov').val($('#concepto').val());
			 $('#abono').val('0.00');
			 $('#cargo').val('0.00');
			 $("#abono").removeAttr("readonly");
			 $("#cargo").removeAttr("readonly");
		}else
		{
			alert("No se puede guardar el registro, revise si la informacion es correcta. ")
		}
	});

	$('#agregar').click(function(){

		$('#capturaMovimiento').modal('show');
		$('#capturaMovimiento :input:last').on('keydown', function (e) { 
		    if ($("this:focus") && (e.which == 9)) {
		        e.preventDefault();
		        $('#capturaMovimiento :input:first').focus();
		    }
		});
		$("#sel_multiple").prop('checked',false)
		$("#facturaSelect").removeAttr('multiple')	
		$("#facturaSelect option[value='-']").removeAttr('disabled')
		actuali();
		/*$('#capturaMovimiento').dialog({position:['center',200]});
		$('#capturaMovimiento').dialog('open');*/
		$('button').attr('disabled','disabled');
		$('#cuenta').prop('disabled', 'disabled');
		$.post("ajax.php?c=CaptPolizas&f=UltimoMov",
	 	{
			IdPoliza: $('#idpoliza').val(),
		},
		function(data)
		{
			if(data)
			{
				$("#movto").val(parseInt(data)+1);
			}
			else
			{
				$("#movto").val('1');
			}
			$('button').removeAttr('disabled');
			$('#cuenta').prop('disabled', false);
			buscacuentaext($('#cuenta').val());
		});
		$('#referencia_mov').val('');
		$("#movto").removeAttr('idmov');
		$('#concepto_mov').val($('#concepto').val());
		$('#abono').val('0.00');
		$('#cargo').val('0.00');
		$('#movto').val(parseInt($('#movto').val())+1);	
		$("#idr").val('1');
		$("#abono").removeAttr("readonly");
		$("#cargo").removeAttr("readonly");
		$("#sucursal option[value='1']").attr("selected","selected");  
		$("#segmento option[value='1']").attr("selected","selected");  
		$("#facturaSelect option[value='-']").attr("selected","selected");
	});

	$('body').bind("keyup", function(evt){
    if (evt.ctrlKey==1)
    {
     	if(evt.keyCode == 13)
      	{
        	$('#guardarpolizaboton').click();
        	$('#actualizarboton').click();
        	$('#nuevapolizaboton').click();
      	}
      	if(evt.keyCode == 88)
    	{
      		$('#cancelarpolizaboton').click();
    	}
    	if(evt.keyCode == 73 && $('#tipoPoliza').val() != 1)
    	{
      		$('#botonProveedores').click();
    	}
    	if(evt.keyCode == 73 && $('#tipoPoliza').val() == 1)
    	{
      		$('#botonClientes').click();
    	}
    	if(evt.keyCode == 77)
      	{
      		$('#agregar').click();
      	}
    }

    if (evt.altKey==1)
    {
    	if(evt.keyCode == 38)
      	{
      		$('#CuadreAgregar').click();
      	}

      	if(evt.keyCode == 75)
      	{
      		$('#asignar_facturas').click();
      	}
    }
  });
//--------------------------------Comienza Proveedores----------------------***
	/*$("#ProveedoresLista").dialog(
	 {
			 autoOpen: false,
			 width: 700,
			 height: 400,
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
				"Nuevo": function () 
				{
					abreProveedores(0,0);
				},
				"Cerrar": function () 
				{
					 $("#ProveedoresLista").dialog('close')
				}
			}
		});*/
	/*$("#Proveedores").dialog(
	 {
			 autoOpen: false,
			 width: 400,
			 height: 600,
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
				"Guardar": function () 
				{
				 
						if($('#importe').val() > 0 && $('#ProveedoresSelect').val() != '0' && $('.iva:checked').val() && parseFloat($('#IVANoAcreditable').val()) <= parseFloat($('#importeIVA').val()))
						{
							guardarProveedores($('#idx').val(),$('#idr').val());
							abreProveedoresLista($('#idpoliza').val());
							$("#Proveedores").dialog('close');
							//alert($('#aplica').prop('checked'))
						}
						else
						{
							alert('Hay un error en la captura, \n\nCausas:\n\n- Agregue un provedor. \n\n- Agregue un importe.\n\n- Seleccione un IVA. \n\n- La retencion del IVA no puede ser mayor al importe del IVA\n\n- El IVA no acreditable no puede ser mayor al importe del IVA.');
						}


				}
			}
		});*/
//--------------------------------Termina Proveedores----------------------***
//--------------------------------Comienza Causacion-----------------------***
/*$("#Causacion").dialog(
	 {
			 autoOpen: false,
			 width: 750,
			 height: 590,
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
				"Guardar": function () 
				{
				 
				 var guardar;
					var Cargos = $('#Cargos b').html();
							Cargos = Cargos.replace('$','').replace(/,/g, '');

					
						if(parseFloat($('#totalesImporteTotalHidden').val()) <= parseFloat(Cargos))
						{
								guardar = 1;
						}
						else
						{
								var acepta = confirm("El Total de 'Importe Total' es mayor a los Cargos.\nTotales: "+$('#totalesImporteTotalHidden').val()+"\nCargos: "+Cargos+"\nAun asi desea continuar?");
								if(acepta)
								{
										guardar=1;
								}
								else
								{
										guardar = 0;
								}
						}

						if(guardar)
						{
							guardaCausacion($('#idp').val());
						}

				}
			}
		});*/
//--------------------------------Termina Causacion-----------------------***
//--------------------------------Comienza Facturas-----------------------***
/*$("#Facturas").dialog(
	 {
			 autoOpen: false,
			 width: 900,
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

//EXTENDIENDO LA FUNCION CONTAINS PARA HACERLO CASE-INSENSITIVE
	$.extend($.expr[":"], {
"containsIN": function(elem, i, match, array) {
return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
}
});
//-------------------------------------------------------------

	// INICIA GENERACION DE BUSQUEDA
			$("#buscar").bind("keyup", function(evt){
				//console.log($(this).val().trim());
				if(evt.type == 'keyup')
				{
					$("#lista tr:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
					$("#lista tr:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
					$("#lista tr:containsIN('*1*')").css('display','table-row');
					if($(this).val().trim() === '')
					{
						$("#lista tr").css('display','table-row');
					}
				}

			});
		// TERMINA GENERACION DE BUSQUEDA
 actualizaListaFac()
 if(parseInt($("#periodos").val()) == 13)
	{
		//$("#guardarpolizaboton2").hide();
		//alert("Para poder guardar la poliza, asigne una cuenta a saldos")
		actualizaCuentas(1)
		$("#cuenta").change(function(event) {
			if($("#concepto_mov").val() == 'Poliza de Cierre (Saldo)')
			{
				$("#guardarpolizaboton2").show();
			}
		});
	}
});
function actualizaListaFac()
{
$.post("ajax.php?c=CaptPolizas&f=listaFacturas",
		 	{
				IdPoliza: $('#idpoliza').val()
			 },
			 function(data)
			 {
				
			 	$('#facturaSelect').html(data)
				
			 });
}

function facturas()
{

	$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
		 	{
				IdPoliza: $('#idpoliza').val()
			 },
			 function(data)
			 {
				
			 	$('#listaFacturas').html(data)
				
			 });
	$('#Facturas').modal("show");
}
function actualizaCuentas(n)
{
	if(typeof n === 'undefined')
		n=0;
	$('#cargando-mensaje').css('display','inline');
	$.post("ajax.php?c=CaptPolizas&f=actualizaCuentas",
		{
				resultados : n
		},
			function(datos)
			{
					$('#cuenta').html(datos)
					$("#cuenta").select2({
					 width : "150px"
					});
					$('#cargando-mensaje').css('display','none');
			});
			//buscacuentaext($('#cuenta').val());

						 //alert(datos)

}

function iracuenta(){
	window.parent.agregatab('../../modulos/cont/index.php?c=arbol&f=index','Cuentas','',145)
	//window.location='../../modulos/cont/index.php?c=AccountsTree';
}

function actuali(){
		actualizaCuentas();
		$('#c').show();
			$('#a').show();
			$("#abonoext").val(0);
			$("#cargoext").val(0);
			$('#muestraextca').hide();
			$('#muestraextab').hide();
			$('#carext').hide;
			$('#abext').hide;
			$('#relacion').hide();
	}
function aritmetica(i)
{
		//Toma el str del value del elemento
		var s = $("#"+i.id).val();

		//Si contiene el signo = entonces es operacion aritmetica
		if (s.indexOf('=') != -1) 
		{
			
			//Quita el signo de = en el string
			s = s.replace('=','')

			//Instancia el objeto de la clase BigEval (Plugin)
		 	var Obj = new BigEval();

		 	//Guarda el string en la variable total
		 	var total = Obj.exec(s)
		 	total = parseFloat(total)

		 	//Muestra resultado aritmetico
		 	$("#"+i.id).val(total.toFixed(2))
		}
}	
function sel_multiple(e)
	{
		if(e.checked)
		{
			$("#facturaSelect").attr('multiple','true')
			$("#facturaSelect option[value='-']").attr('disabled','true')

		}
		else
		{
			$("#facturaSelect").removeAttr('multiple')	
			$("#facturaSelect option[value='-']").removeAttr('disabled')
		}
	}

	function listafaccheck(c)
 {
 	if(c.checked)
 	{
 		$("#listaMovsFacturas").attr('style','display:in-line;')
 	}
 	else
 	{
 		$("#listaMovsFacturas").attr('style','display:none;')
 	}
 }

function ver_factura(){
	var factura = $('#facturaSelect').val()
		, carpeta = $('#idpoliza').val()
		, dir = 'xmls/facturas/'+carpeta+'/'+factura;
	window.open('controllers/visorpdf.php?dir='+dir, '_blank');
}

function obtener_selects(){
	$('#oficial').html("<option value='0'>Ninguna</option>");
	$('#subcuentade').html("<option value='0'>Ninguna</option>");
	$.post('ajax.php?c=arbol&f=selects_agregar_cuenta', {},
	function(data){
		console.log(data);
		$('#accountNumber').attr('placeholder', data.mask);
		$('#nature').html(data.naturaleza);
		$('#coins').html(data.moneda);
		$('#type').html(data.tipo);
		$('#oficial').append(data.oficial);
		$('#status').html(data.estatus);
		$('#subcuentade').append(data.subcuenta);
		$('#tipo_instancia').val(data.instancia);
	}, "JSON");
}

function guardar_cuenta(){
	$.post('ajax.php?c=arbol&f=guardaCuenta',
		{
			numero: $('#accountNumber').val(),
			nombre: $('#nombre_cuenta').val(),
			nombre_idioma: $('#nombre_cuenta_idioma').val(),
			subcuentade: $('#subcuentade').val(),
			naturaleza: $('#nature').val(),
			moneda: $('#coins').val(),
			clasificacion: $('#type').val(),
			digito: $('#oficial').val(),
			estatus: $('#status').val(),
			idcuenta: 0,
			tipoinstancia: $('#tipo_instancia').val()
		},
		function(data){
			if (data == 10) {
				alert("La cuenta se agrego exitosamente.");
				mostrar_agregar_movimientos();
				actualizaCuentas();
			} else {
				alert("Hubo un error y no se agrego la cuenta.");
			}
		});
}

function mostrar_agregar_cuenta(){
	$('#agregar_movimientos_btn').hide();
	$('#agregar_cuenta_btn').show();
	$('#volver_movimientos').show();
	$('#agregar_movimiento').hide();
	$('#agregar_cuenta').fadeIn();
	$('.modal-title').html("Agregar Cuenta");
}

function mostrar_agregar_movimientos(){
	$('.modal-title').html("Agregar Movimientos");
	$('#volver_movimientos').hide();
	$('#agregar_cuenta_btn').hide();
	$('#agregar_movimientos_btn').show();
	$('#agregar_cuenta').hide();
	$('#agregar_movimiento').fadeIn();
}	