Array.prototype.last = function(){return (isNaN(this[this.length-1])) ? this[this.length-1] : parseFloat(this[this.length-1],10);};
$(document).ready(function(){
$('#blanca').show()
$('#subircuentas').hide()
$('#mensajes,#saving').hide()
$('#titulo_captura').text('Nueva Cuenta')

$("#subcuentade,#type,#nature,#main,#coins,#status,#sucursal,#oficial").select2({
			width : '100%'
		});
		//	INICIA VALIDACION DE CODIGO DE CUENTA MANUAL
			// jshint ignore:start
			<?php
				if (strtoupper($accountMode) == "M" )
				{
					echo "$('#accountNumber').show().mask('" . $inputMask . "');\n$('label[for=accountNumber]').show();";
				}
				else
				{
					echo "$('#accountNumber').hide().attr('readonly',true);\n$('label[for=accountNumber]').hide();";
				}
			?>
			// jshint ignore:end
		//	TERMINA VALIDACION DE CODIGO DE CUENTA MANUAL
	
	// INICIA DEFINICION DE DATOS, PLUGINS E INTERFACES
		var posX = 0;
		var posY = 0;
		var posY_interfaz = 0;
		var array = [];
		clear();
		var cpy = [];


		// INICIA GENERACION DE BUSQUEDA
			$("#search").bind("keyup focus", function(evt){
				switch(evt.type)
				{
					case "focus":
						$('ul').css('display','block');
						break;
					case "keyup":
							var selector = $("#cont span:contains('" + $(this).val().trim().toUpperCase() + "')");
							selector.css({display : 'inline-block'});
							selector.siblings("img").css({display: 'inline-block'});

							if($(this).val().length === 0)
							{
								$("#cont span").css({
									display: 'inline-block'
								}).siblings("img").css({display: 'inline-block'});
							}
							else
							{
								var notSelector = $("#cont span").not(":contains('" + $(this).val().trim().toUpperCase() + "')");
								notSelector.css('display','none').siblings("img").css('display','none');
								selector.siblings("ul").children('span').css('display','inline-block');
							}
						break;
				}
			});
		// TERMINA GENERACION DE BUSQUEDA		
		// INICIAN  EVENTOS DE LISTA (Modificacion de Cuentas y SlideToggle de cuentas, click derecho)
		$('body').delegate("img",'click',function(){
			$(this).siblings("span").click();
		});

		$('body').delegate("li>span",'click dblclick contextmenu',function(event){
			switch(event.type)
			{
				case 'click':
					if( $(".movable").length === 0 )
					{
						if( $(this).parent("li").children("ul").children("li").length > 0 )
						{
							$(this).parent("li").children("ul").slideToggle();
						}
					}
					else
					{
						$('.selected').removeClass("selected");
						$(this).addClass("selected");
						$("#context2").show().css({
							top:posY,
							left:posX
						});
					}
					
					break;
				case 'dblclick':
					$.post('ajax.php?c=arbol&f=datosCuenta', 
						{
							idCuenta: $(this).parent().attr('data-id-acc')
						}, 
						function(data) 
						{
							var datos = jQuery.parseJSON(data)
							$('#titulo_captura').text('Modificar Cuenta')
							$("#idcuenta").val(datos.account_id)
							$("#accountNumber").val(datos.manual_code)
							$("#nombre_cuenta").val(datos.description)
							$("#nombre_cuenta_idioma").val(datos.sec_desc)
							subcuentas(datos.father_account_id,datos.account_id)
		 					$("#nature").select2("val", datos.account_nature);
		 					$("#coins").select2("val", datos.currency_id);
		 					$("#type").select2("val", datos.main_account);
		 					$("#oficial").select2("val", datos.cuentaoficial);
		 					if(datos.status == '0') datos.status = '2';
		 					$("#status").select2("val", datos.status);
		 					$('#guardar').text('Modificar');
		 					if(datos.description_father == null) datos.description_father='Ninguna'
		 					$("#sbcta_label").text(datos.description_father)
		 					$("#sbcta_hidden").val(datos.father_account_id)
		 					$('#eliminar').show()
		 					$('#cerrar').hide()
		 					$('#abrir').hide()
		 					if(!parseInt(datos.removable))
		 					{
		 						//$("#sbcta").hide()
		 						$("#sbcta_label").show().attr("muestra","1")
		 						$("#subcuentade,#s2id_subcuentade,#eliminar").hide();
		 						//if(!parseInt($("#tipoinstancia").val()))
		 							$("#guardar,#cancelar").hide();

		 					}
		 					else
		 					{
		 						//$("#sbcta").show()
		 						$("#sbcta_label").hide().attr("muestra","0")
		 						$("#subcuentade,#s2id_subcuentade,#eliminar,#guardar,#cancelar").show();
		 					}
		 					cerrar(2)
							
							
						});
					
					break;
			}
		});
	// TERMINAN EVENTOS DE LISTA
	cargaCuentas()
subcuentas(0,0)
$('#eliminar').hide()
$('#cerrar').hide()
$('#abrir').show()
$("#captura").hide();
$('#blanca').hide()
$('body').bind("keyup", function(evt)
{
    if (evt.ctrlKey==1)
    {
     	if(evt.keyCode == 67)
      	{
      		alert("CUIDADO! Los cambios generados por esta funcion afectaran todas las cuentas y los reportes.")
      		$('.bs-corregir-modal-sm').modal('show');
      	}
      }
 });
});

//	clear() - Sirve para limpiar la Interfaz - 
function clear()
{
	$("input:text").val('');
	$("#group_dig").val(0);
	$("#form,.layer").fadeOut();
	if($('.selected').length !== 0)
	{
		$(".selected").removeClass('selected');
	}
	$('#sve').removeAttr("disabled");
}



// sendObject()	- Ejecuta transacciones
function sendObject(object, type)
{
	var ajaxSend = null;
	var sending = true;
	var destination = "";
	if(Object.prototype.toString.call(object) == "[object Object]" || Object.prototype.toString.call(object) == "[object Array]" || (object == 'init'))// Validando si es un objeto...
	{
		destination = "ajax.php?c=accountsTree&f=" + type;
	}
	else
	{
		sending = false;
		alert('sendObject: El elemento no es un Objeto.');
	}

	if (sending)
	{
		ajaxSend = $.ajax({
			type     : 'post',
			url      : destination,
			// dataType : 'text',
			async : false,
			data     : {data:object},
			beforeSend: function(){
			}
		});

		ajaxSend.done(function(data){
			return data;
		});
		
		//console.log(" [ " + type + "]" + ajaxSend.responseText);
		ajaxSend.fail(function(){
			alert("El envio de datos ha fallado.");
		});
		return ajaxSend.responseText;
	}
	else{
		return ajaxSend;
	}
}


// setIcons	- Genera la asignacion de iconos segun su nivel.
function setIcons()
{
	$('img').each(function(){
		var parent = $(this).parent('li').attr("data_type");
		switch(parent)
		{
			case '1':
				$(this).attr("src",'images/4.gif');
				break;
			case '2':
				$(this).attr("src",'images/1.gif');
				break;
			case '3':
				$(this).attr("src",'images/3.gif');
				break;
		}
	});
}


function exportar(){
window.open("ajax.php?c=AccountsTree&f=cvs");
}

function guardar()
{
	var ejecuta=1;
	var mensaje='';
	if($("#accountNumber").val() == '' && $("#accountNumber").attr('readonly') != 'readonly')
	{
		mensaje += 'Agregue un numero de cuenta.';
		ejecuta = 0
	}

	if($("#nombre_cuenta").val() == '')
	{
		mensaje += '\nAgregue un nombre a la cuenta.';
		ejecuta = 0
	}

	if(!parseInt($("#subcuentade").val()) && !parseInt($("#idcuenta").val()))
	{
		if(!confirm("Si no asocia una cuenta padre, la agregará como cuenta de orden."))
		{
			mensaje += '\nAgregue una cuenta padre.';
			ejecuta = 0
		}
	}

	if(ejecuta)
	{
		$("#guardar,#cancelar").attr("disabled",true)
		$('#mensajes').hide()
		$("#saving").show()
		var sub = $("#subcuentade").val();
		if($("#sbcta_label").attr("muestra") == "1")
			sub = $("#sbcta_hidden").val();


		$.post('ajax.php?c=arbol&f=guardaCuenta', 
		{
			numero: $("#accountNumber").val(),
			nombre: $("#nombre_cuenta").val(),
			nombre_idioma: $("#nombre_cuenta_idioma").val(),
			subcuentade: sub,
			naturaleza: $("#nature").val(),
			moneda: $("#coins").val(),
			clasificacion: $("#type").val(),
			digito: $("#oficial").val(),
			estatus: $("#status").val(),
			idcuenta: $("#idcuenta").val(),
			tipoinstancia: $("#tipoinstancia").val()

		}, 
		function(data)
		 {
		 	$('#saving').hide()
		 	//alert(data)
			if(!parseInt(data))
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("Hubo un problema. ");

		 	if(parseInt(data) == 5)
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("Ya existe una cuenta con ese numero. ");

		 	if(parseInt(data) == 1)
		 	{
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("No se puede guardar porque el padre no existe o fue modificado por otro usuario.");
		 		subcuentas(0,0)
		 		cargaCuentas()
		 	}

			if(parseInt(data) == 2)
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("No se puede guardar la cuenta debido a que ya existe una cuenta de mayor para esta rama del arbol.");

		 	if(parseInt(data) == 6)
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("No se puede guardar la cuenta afectable debido a que no existe una cuenta de mayor para esta rama del arbol.");

		 	if(parseInt(data) == 3)
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("No se puede convertir a afectable porque ya tiene hijos.");

		 	if(parseInt(data) == 4)
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("No se puede cambiar el tipo cuenta porque tiene movimientos.");

		 	if(parseInt(data) == 10)
		 	{
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"#A5DF00","color":"white"}).text("Cuenta guardada satisfactoriamente.");
		 		if(!parseInt($("#idcuenta").val()))
		 		{
		 			$("#mensajes").fadeOut( 7000, "linear");
		 			var seleccionada = $("#subcuentade").val()
			 		subcuentas(seleccionada,0)
			 		$("#nombre_cuenta").val('')
			 		$("#nombre_cuenta_idioma").val('')
			 		$("#sbcta").show()
			 		$("#sbcta_label").hide().attr("muestra","0")
		 		}
		 		else
		 		{
		 			cancelar()
		 			subcuentas(0,0)
		 		}
		 		cargaCuentas()
		 		//location.reload();
		 	}
		 	
			$("#guardar,#cancelar").removeAttr("disabled")
		});
	}
	else
	{
		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text(mensaje);
	}
}

function cancelar()
{
	$("#mensajes").fadeOut( 1000, "linear");
	$('#titulo_captura').text('Nueva Cuenta');
	$("#idcuenta").val('0')
	$("#accountNumber").val('')
	$("#nombre_cuenta").val('')
	$("#nombre_cuenta_idioma").val('')
	subcuentas(0,0)
	$("#nature").select2("val", 1);
	$("#coins").select2("val", 1);
	$("#type").select2("val", 1);
	$("#oficial").select2("val", 0);
	$("#status").select2("val", 1);
	$('#guardar').text('Guardar');
	$('#eliminar').hide()
	$('#cerrar').show()
	$("#sbcta").show()
	$("#sbcta_label").hide().attr("muestra","0")

}

function eliminar()
{
	var sure = confirm("Esta seguro que desea eliminar esta cuenta: "+$("#nombre_cuenta").val()+"?");
	if(sure)
	{
		$.post('ajax.php?c=arbol&f=eliminarCuenta', 
		{
			idcuenta: $("#idcuenta").val()
		}, 
		function(data)
		 {
		 	if(!parseInt(data))
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("Hubo un problema y no se pudo eliminar!");

		 	if(parseInt(data) == 1)
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("Esta cuenta no es eliminable!");

		 	if(parseInt(data) == 2)
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"red","color":"white"}).text("Esta cuenta o sus decendientes tiene(n) movimientos y no puede(n) eliminarse!");

		 	if(parseInt(data) == 10)
		 	{
		 		$("#mensajes").hide().fadeIn( 1000, "linear").css({"background-color":"#A5DF00","color":"white"}).text("Se ha eliminado esta cuenta y todos sus decendientes!");
		 		cancelar();
		 		cargaCuentas()
		 	}

		 });
	}
}

function cargaCuentas()
		{
			//INICIA OBTENCION DE ARBOL CONTABLE
			$('#cont>ul').html('')
			var data = sendObject('init','getAccounts');
			jsonTree = $.parseJSON(data);
			var liArray = null;
			if (jsonTree.length > 0)
			{
				var descrip;
				for ( var i = 0 ; i < jsonTree.length ; i++ )
				{
					var digito = "";
					//Validamos que el digito agrupador no venga vacío y lo guardamos en la variable digito
					if (!(jsonTree[i].codigo_agrupador == undefined || jsonTree[i].codigo_agrupador == "")) {
						digito = "title='Digito: "+ jsonTree[i].codigo_agrupador +"'";
					}
					descrip = jsonTree[i].description;
					var contentLi = "<li data_type = '"+jsonTree[i].main_account+"' data-father ='" + jsonTree[i].father_account_id + "' data-id-acc='" + jsonTree[i].account_id + "' data-manual='"+ jsonTree[i].manual_code +"'>";
					contentLi += "<img/><span "+digito+" code='" + jsonTree[i].account_type + "'>( " + jsonTree[i].manual_code + " ) " + descrip.toUpperCase() + "</span><ul></ul></li>";

					$('#cont>ul').append(contentLi);
					$("li[data-id-acc='" + jsonTree[i].account_id + "'] > span").data(jsonTree[i]);
				}	
			}
			else
			{
				$('body').html("<div><h1 style='text-align:center;position:absolute;top:45%;width:100%;'>No existen cuentas. Seleccione un tipo de catalogo en la configuracion para poder operar.");
			}
			

			$("#cont li").each(function()
			{
				var thiss = $(this);
				if($(this).children("span").data().removed == 1 ){
					$(this).css('display','none');
				}
				var father = $(this).attr("data-father");
				if( father > 0 )
				{
					$("li[data-id-acc='"+father+"' ]").children("ul").append(thiss);
				}
				var numClass = $(this).parents('ul').length - 1;
				$(this).addClass("x"+numClass);
			});
			
			setIcons();

			//$("li").tsort('span',{data:'father'},{data:'manual'});
			
			var toggleSorting = false;
			
			$(".sort").click(function(){
				if(!toggleSorting)
				{
					$(this).text("Ordenar por ID de cuenta");
					$("li").tsort();
					toggleSorting = true;
				}
				else
				{
					$(this).text('Ordenar Alfabeticamente');
					$("li").tsort('span',{data:'father'},{data:'id-acc'});
					$("li").tsort('span',{data:'id-acc'},{data:'father'});
					toggleSorting = false;
				}
			});
			//TERMINA OBTENCION DE ARBOL CONTABLE
		}
		function subcuentas(sel,excepto)
		{
			$.post('ajax.php?c=arbol&f=subcuentas',
			{
				idcuenta : excepto 
			},
		function(data)
		 {
		 	$("#subcuentade").html("<option value='0'>Ninguna</option>")
		 	$("#subcuentade").append(data)
		 	$("#subcuentade option[value='"+sel+"']").attr("selected","selected");  
		 	$("#subcuentade").select2("val", sel);
		 	if(excepto)
		 		$("#subcuentade option[value='"+excepto+"']").remove();

		 });
		}

		function cerrar(d)
		{
			$("#mensajes").hide();
			if(parseInt(d))
			{
				$("#captura").fadeIn(500,'linear');
				$("#modifica").css({"border":"1px solid gray","background-color":"#F2F2F2"})
				$("#cerrar").show()
				$("#abrir").hide()
				if(parseInt(d) == 1)
				{
					cancelar()
					$("#guardar,#cancelar").show();
				}
				//alert($("#idcuenta").val())
			}
			else
			{
				$("#captura").fadeOut(1000,'linear');
				$("#modifica").css({"border":"1px solid white","background-color":"white"})
  				$("#cerrar").hide()
  				$("#abrir").show()
			}
		}

		function abrir_co()
		{
			$('.bs-corregir-modal-sm').modal('show');
		}

		function cerrar_co()
		{
			$('.bs-corregir-modal-sm').modal('hide');
		}

		function corregir()
		{
			if(confirm("Esta usted seguro que quiere corregir el arbol y la informacion es correcta?"))
			{
				$.post('ajax.php?c=arbol&f=corregirEstructura',
				{
					activo 	: $("#activo_co").val(),
					pasivo 	: $("#pasivo_co").val(),
					capital : $("#capital_co").val(),
					ingreso : $("#ingreso_co").val(),
					egreso 	: $("#egreso_co").val(),
					orden 	: $("#orden_co").val()
				},
				function(data)
				 {
				 	if(parseInt(data))
				 		window.location = 'index.php?c=arbol&f=index';
				 	else
				 		alert("Hubo un error. "+data)
				 });
			}
		}
