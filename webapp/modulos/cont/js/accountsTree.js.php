Array.prototype.last = function(){return (isNaN(this[this.length-1])) ? this[this.length-1] : parseFloat(this[this.length-1],10);};
$(document).ready(function(){
$('#subircuentas').hide()
	/**
		Detalles posiblemente utiles, despues:
		- .selected aplica a los spans que contienen el texto de los li
		- .new aplica a todos los li generados nuevamente
		- .movable aplica a el li que sera movido
		- 
	**/
	// INICIA DEFINICION DE DATOS, PLUGINS E INTERFACES
		var posX = 0;
		var posY = 0;
		var posY_interfaz = 0;
		var array = [];
		clear();
		var cpy = [];

		//INICIA OBTENCION DE ARBOL CONTABLE
			var data = sendObject('init','getAccounts');
			jsonTree = $.parseJSON(data);
			var liArray = null;
			if (jsonTree.length > 0)
			{
				for ( var i = 0 ; i < jsonTree.length ; i++ )
				{
					var contentLi = "<li data-father ='" + jsonTree[i].father_account_id + "' data-id-acc='" + jsonTree[i].account_id + "' data-manual='"+ jsonTree[i].manual_code +"'>";
					contentLi += "<img /><span code='" + jsonTree[i].account_type + "'>( " + jsonTree[i].manual_code + " ) " + jsonTree[i].description + "</span><ul></ul></li>";

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

		// INICIA HIDE/SHOW DE AYUDA
			$('.help').click(function(){
				$('.tooltip').fadeIn().css('display','inline-block');
			});
			$('.tooltip span').click(function(){
				$('.tooltip').fadeOut();
			});
		// TERMINA HIDE/SHOW DE AYUDA

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

		$("#group_dig").val(0).mask('9');
		$("#type,#nature,#main,#coin,#status,#sucursal,#oficial").select2({
			width : '100%'
		});
		$("#type").select2("readonly" , true);
	
		$(document).mousemove(function(e){
			posX =  e.pageX;
			posY = e.pageY;
			$('.movable').css({
				top : posY,
				left: posX + 3
			});
		});
	// TERMINA DEFINICION DE DATOS, PLUGINS E INTERFACES

	// INICIAN EVENTOS DE MENU CONTEXTUAL 1
		$("#context,#context2").bind("click mouseleave",function(evt){
			$(this).hide();
		});

		$("#context>span").click(function(){
			var setDisabled = false;
			txt = $(this).text();
			var cla = $('.selected').parent("li").attr("class");
			var imgSrc = $('.selected').siblings("img").attr("src");
			var sib = $(".selected").parent().siblings().length + 1;// Hermanos de .selected
			var sons = $('.selected').parent().children('ul').children("li").length +1;// Hijos de .selected
			var x = cla.replace('x','');
			switch(txt)
			{
				case "Nuevo":
					$('#accountNumberId').hide();
					$('#numaut').hide();
					$('.selected').parents("li").each(function(index){
						if($(this).children("span").data().main_account == 1 && index > 1)
						{//busqueda hacia arriba por cuentas de mayor...
							setDisabled = true;
						}
					});
					getLastBro(1);
					setTypeByName();
					$('.selected').parent("li").after('<li class="x' + x + ' new"><img src="' + imgSrc + '" /><ul></ul></li>').removeClass("selected");
					$("#form,.layer").fadeIn();
					$('#search').val("");
					$("#cont span").css({
							display: 'inline-block'
						}).siblings("img").css({display: 'inline-block'});
					$("#sve").removeClass('mod').addClass("reg");
					$('body,html',window.parent.document).animate({ scrollTop: 0 }, 500);
					break;
				case "Agregar Hijo":
					if(parseInt($('.selected').data().activity,10) === 0)
					{
						$('#accountNumberId').hide();
						$('#numaut').hide();
						$('.selected').parents("li").each(function(index){// si contiene cuentas de mayor hacia arriba...
							if($(this).children("span").data().main_account == 1)
							{
								setDisabled = true;
							}
						});
						
						getLastChildren(1);
						setTypeByName();
						var y = parseInt(x,10) + 1;
						$('.selected').siblings("ul").append('<li class="x' + y + ' new"><img /><ul></ul></li>').removeClass('selected');
						setIcons();
						if(parseInt($('.selected').data().main_father,10) !== 0)
						{
							setDisabled = true;
						}
						$("#form,.layer").fadeIn();
						$('#search').val("");
						$("#cont span").css({
							display: 'inline-block'
						}).siblings("img").css({display: 'inline-block'});
						$("#sve").removeClass('mod').addClass("reg");
						$('body,html',window.parent.document).animate({ scrollTop: 0 }, 500);
					}
					else
					{
						alert("No es posible registrar hijos debido a que esta cuenta posee movimientos. Realice las modificaciones necesarias.");
					}
					break;
				case "Eliminar":
					var deletionId = [];
					var activitySum = 0;
					$(".selected").parent("li").find("span").each(function(index)
					{
						deletionId.push($(this).data().account_id);
						activitySum += parseInt($(this).data().activity,10);
					});
					deletionId = deletionId.join(",");

					if($(".selected").data().removable == 1 && activitySum === 0 )
					{
						if ( confirm( "Realmente desea Eliminar la cuenta: "+ $(".selected").text() ) )
						{
							$(".selected").data().account_id = deletionId;
							var data = $(".selected").data();
							
							if(sendObject(data,'deleteAccount') == true)
							{
							$(".selected").parent("li").remove();
							}
							else
							{
							alert("La transaccion no pudo ser realizada.");
							}
						}
					}
					else
					{
						alert("Esta cuenta no puede ser eliminada. Posiblemente posee movimientos o es parte de la estructura basica del arbol contable.");
					}
					break;
				case 'Mover':
					unMovableList = [];
					unconfirm = "";
					activitySum = 0;
					$('.selected').parent('li').find('span').each(function(){
						if($(this).data().main_account == 1)
						{
							setDisabled = true;
							unMovableList.push($(this).text());
							activitySum += $(this).data().activity;
						}
					});
					if(setDisabled === true )
					{
						if (unMovableList.length === 1)
						{
							unconfirm = "Debido a que la cuenta " + unMovableList.join(", ") + " Es de mayor. Esta cuenta y su descendencia no pueden ser movidas.";
						}
						else
						{
							unconfirm = "Debido a que las cuentas " + unMovableList.join(", ") + " son de mayor. Esta cuenta y su descendencia no pueden ser movidas.";
						}
					}

					if (unconfirm.length  > 1)
					{
						alert(unconfirm.toUpperCase());
					}
					else
					{
						if (confirm('\u00BFRealmente desea Mover ' + $(".selected").text() + "?\nEsta accion puede afectar los resultados en futuros documentos contables."))
						{
							$(".selected").parent('li').css({
								position : 'absolute'
							}).addClass("movable").removeClass("selected");
						}
					}
					
					break;
				case "Copiar":
					cpy.push($('.selected').data());
					$('.selected').siblings('ul').find('span').each(function(){
						cpy.push($(this).data());
					});
					console.log(cpy);
			}

			if (setDisabled === true && txt != 'Mover')//solo caso de nuevo y nuevo hijo...
			{// si tiene ancestros de mayor...
				$("#main>option[value='1']").attr("disabled","disabled");
				$("#main").val(2);
				$("#main").select2({width:"100%"}).select2('val', 2);
			}
			else
			{
				$("#main>option[value='1']").removeAttr("disabled");
				$("#main").val($('.selected').data().main_account);
				$("#main").select2({width:"100%"});
			}
		});
	// TERMINAN EVENTOS DE MENU CONTEXTUAL 1
	
	// INICIAN EVENTOS DE MENU CONTEXTUAL 2
		$("#context2 > span").click(function(){
			var text = $(this).text();
			var newFatherId = null;
			var newAccountId = null;
			if( $(".movable > span ").data().account_code[0] == $(".selected").data().account_code[0] )
			{
				switch(text)
				{
					case "A nivel de...":
							account_id = getLastBro(0);//Generacion de nuevos codigos...
							if( setNewId(account_id) == true )
							{
								$(".selected").parent("li").parent("ul").append($('.movable'));

							}
							else
							{
								alert("La transaccion ha fallado.");
							}

						break;
					case "Como Hijo de...":

						account_id = getLastChildren(0);//Generacion de nuevos codigos...
						if( setNewId(account_id) == true )
						{
							$(".selected").siblings("ul").append($(".movable"));
						}
						else
						{
							alert("La transaccion ha fallado.");
						}
						
						break;
				}
			}
			else
			{
				alert("Debido a que no pertenecen al mismo tipo, esta cuenta no puede ser unida.");
			}
			
			$(".movable").removeClass("movable").removeAttr('style');
		});
	// TERMINAN EVENTOS DE MENU CONTEXTUAL 2

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
					$('#accountNumberId').show();
					$('#numaut').show();
					loadForm($(this).data());
					$(this).addClass("selected");
					$("#form,.layer").fadeIn();
					$('#search').val("");
					$("#cont span").css({
							display: 'inline-block'
						}).siblings("img").css({display: 'inline-block'});

					var unsetMain = false;

					$('.selected').parent("li").find("span").each(function(){//recorre en busca de cuentas de mayor dentro de selected...
						if(parseInt($(this).data().main_father, 10)  > 0 )
						{
							unsetMain = true;
						}
					});
					
					$("#sve").removeClass('reg').addClass("mod");
					posY_interfaz = $(window.parent.document).scrollTop();
					$('body,html',window.parent.document).animate({ scrollTop: 0 }, 500);
					
					break;
				case 'contextmenu':

					event.preventDefault();
					if($('.movable').length === 0 )
					{
						$('.selected').removeClass('selected');
						// if($('#context').css('display') == 'none')
						// {
						//	switch($(this).parent("li").attr("class"))
						//	{
						//		case 'x0':
						//			$(".sn,.dl,.nw").hide();
						//			$(".nt").show();
						//			break;
						//		case 'x1':
						//			$(".sn,.dl,.nw").hide();
						//			$(".nt").show();
						//			break;
						//		case 'x2':
						//			$(".sn,.nw").show();
						//			$(".nt,.dl").hide();
						//			break;
						//		default:
						//			$(".sn,.dl,.nw").show();
						//			$(".nt").hide();
						//			break;
						//	}
							$(this).addClass("selected");
							$("#context").css({top:posY,left:posX}).show();
						//}
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
			}
		});
	// TERMINAN EVENTOS DE LISTA

	// INICIAN EVENTOS DE FORMULARIO
		$("#sve").click(function(){
			//INICIA OBTENCION DE DATOS
				var alt = false;//INDEPENDIENTE
				var autoCode = $('#auto').val();
				var name = $("#accountName").val().trim().toUpperCase();
				var secondName = $("#secondName").val().trim().toUpperCase();
				var number = $("#accountNumber").val();// Codigo de Cuenta Manual
				var status = $('#status').val();
				var fatherAccount = $("#fatherAccount").val();
				var nature = $("#nature").val();
				var type = $("#type").val();
				var main = $("#main").val();
				var coin = $("#coin").val();
				var group = $("#group_dig").val();
				var sucursal = $("#sucursal").val();
				var cuentaoficial = $("#oficial").val();
				var id = $("#yd").val();
				var main_father = 0;
				if( $('.selected').parents("li").length > 1 )// Los de nivel 1 solo tienen un padre li
				{
					main_father = $('.selected').parent('li').parent('ul').siblings('span').data().main_account;
				}
				
				$('.validate').each(function(){
					var id = $(this).attr("id");
					var val = $(this).val().trim();
					switch(id)
					{
						case "accountNumber":
							if($("#sve").hasClass('mod'))
							{
								duplicated = checkDuplicatedItems($('.selected').data().account_id, val);
							}else if($("#sve").hasClass('reg')){
								duplicated = checkDuplicatedItems(-1,val);
							}
							if(duplicated !== false){
								alert("Este ID de cuenta ya ha sido ocupado por la cuenta: " + duplicated);
								$('#accountNumber').focus().css('background','red');
								alt = true;
							}
							break;
						case "accountName":

							break;
					}

					if ($('.selected').length === 0)
					{// Validacion de seguridad
						alt = true;
					}

					if($(this).val().length === 0 || $(this).val().parseInt === 0)
					{
						$(this).css("background",'red');
						alt = true;
					}
				});
			//TERMINA OBTENCION DE DATOS
			
			if(alt === true)
			{
				alert("Hay datos incorrectos en el formulario. Revise por favor.");
			}
			else
			{
				$(this).attr("disabled",'disabled');
				var data = null;
				switch($(this).attr("class"))
				{
					case 'mod':
						var firstMain = parseInt($('.selected').data().main_account,10);
						var affectable = true;
						var childNum = $(".selected").siblings("ul").children("li").length;
						if(childNum > 0)
							affectable = false;

						obj = $(".selected").data();
						obj.manual_code    = $("#accountNumber").val();
						obj.description    = $("#accountName").val().trim().toUpperCase();
						obj.sec_desc       = $("#secondName").val().trim().toUpperCase();
						obj.account_type   = $("#type").val();
						obj.account_nature = $("#nature").val();
						obj.main_account   = ($("#main").val()) ? $("#main").val() : obj.main_account ;
						obj.currency_id    = $("#coin").val();
						obj.status         = $("#status").val();
						obj.cuentaoficial = $("#oficial").val();

						obj.affectable = ( parseInt(obj.main_account,10) === 1 || parseInt(obj.main_account,10) === 2) ? false : true; // EN ACTUALIZACION, SI ES DE MAYOR, NO DEBE SER AFECTABLE

						if(obj.main_account === 0 || obj.main_account === null)
						{
							alert( "Existe un error sobre la cuenta de mayor, su valor es: " + obj.main_account );
							data = false;
						}else
						{
							data = sendObject(obj, "updateAccount");
						}

						if(data == true)
						{
							if(firstMain != $('.selected').data().main_account && ( firstMain === 1 || parseInt($('.selected').data().main_account,10) === 1 ) )// Si cambio de o hacia cuenta principal
							{
								switch(firstMain)
								{
									case 1:
										var result = setNewMainFather(0);
										if( result == true )//
										{
											alert("Transaccion Satisfactoria.");
										}
										else
										{
											console.log(result);
											alert("La modificacion ha fallado.");
										}
										break;
									default:
										var result = setNewMainFather( $('.selected').data().account_id );
										if( result == true )
										{
											alert("Transaccion Satisfactoria");
										}
										else
										{
											console.log(result);
											alert("La modificacion ha fallado");
										}

										break;
								}
							}
							else
							{
								alert("Modificacion Satisfactoria");
							}
							$('.selected').text( '( '+ obj.manual_code +' ) '+ obj.description.toUpperCase() );// Cambio de Texto...
						}
						else
						{
							alert("La modificacion ha fallado.");
						}
						
						break;
					case 'reg':
						obj = {
							account_id : id,
							account_code : autoCode,
							manual_code : number,
							description : name,
							sec_desc : secondName,
							account_type : type,
							status : status,
							main_account : main,
							currency_id : coin,
							group_dig : group,
							id_sucursal : sucursal,
							father_account_id : fatherAccount,
							account_nature : nature,
							affectable : true,
							main_father : 0,
							cuentaoficial : cuentaoficial,
							activity : 0,
							removable : 1
						};
						
						if(obj.main_account != 1)
						{
							if( $('.new').siblings().children('.selected').length == 1 )// si fue seleccionado como hermano
							{
								obj.main_father = $('.new').parent().siblings('span').data().main_father;
							}
							else// si fue seleccionado como hijo
							{
								obj.main_father = $(".selected").data().main_father;
							}
						}
						else
							obj.affectable = ( parseInt(obj.main_account, 10)  === 1 || parseInt(obj.main_account, 10) === 2 ) ? false : true; // EN REGISTRO, SI ES DE MAYOR, NO DEBE SER AFECTABLE

						data = sendObject(obj,'insertAccount');
						console.log(data);
						if ( isNaN(data) )
						{
							alert("No ha sido posible realizar el registro. Intente nuevamente.");
							console.log(data);
							$("#cancel").click();
						}
						else
						{
							IdCurrent = {
											account_id : data
											};
							newNumber = sendObject(IdCurrent,'getManualCode');
							console.log(newNumber);
							if(!parseInt(newNumber))
								newNumber = number;
							$(".new > img").after("<span>( "+newNumber+" ) " +name+"</span>");
							$(".new").attr({'data-id-acc':data,'data-father':obj.father_account_id});
							$(".new > span").data(obj);
							$(".new > span").data('account_id',parseInt(data,10));
							if( obj.main_account == 1 )
								$('.new > span').data('main_father', $('.new > span').data().account_id );// asigno main_father
							$('.new').removeClass("new");
							setIcons();
							alert("Registro Satisfactorio.");
						}
					break;
				}
				$('body,html',window.parent.document).animate({ scrollTop: posY_interfaz }, 500);
				clear();
			}
		});

		$('#newSon, #newBro').click(function(){
			// El hijo puede ser creado por modificacion o por creacion de cuenta nueva...
			// Es necesario conocer la clase de #sve ( Permite saber si fue elegido por creacion o modificacion )
			// El .selected es importante para saber 
			switch($(this).attr("id"))
			{
				case "newSon":
					
					alert("Aqui se agregara un nuevo hijo a la cuenta seleccionada.");

					break;
				case "newBro":

					alert('Aqui se agregara un nuevo hermano a la cuenta seleccionada.');

					break;
			}
			if($("#sve").hasClass("mod"))
			{
				alert("Fue seleccionado por medio de un doble click!!!");
			}
			if($("#sve").hasClass("reg"))
			{
				alert("Fue seleccionado despues de un registro de Hijo/Hermano!!!");
			}		
		});

		$('#cancel').click(function(){
			clear();
			$(".new").remove();
			setTimeout(function(){
				$(".validate").css('background-color','white');
			},1000);
			$('body,html',window.parent.document).animate({ scrollTop: posY_interfaz }, 500);
		});

		$(".layer").click(function(){
			$("#cancel").click();
		});
	// TERMINAN EVENTOS DE FORMULARIO

	// INICIAN EVENTOS DE VALIDACION DE FORMULARIO
		$("#accountName,#accountNumber").bind("blur",function(){
			var id = $(this).attr("id");
			var val = $(this).val();
			if($(this).css("background-color") == 'rgb(255, 0, 0)')
			{
				switch(id)
				{
					case 'accountNumber':
						if(val.length > 0)
						{
							// INICIA SECCION QUE VALIDA DUPLICADOS
								if($("#sve").hasClass('mod'))
								{
									duplicated = checkDuplicatedItems($('.selected').data().account_id, val);
								}else if($("#sve").hasClass('reg')){
									duplicated = checkDuplicatedItems(-1,val);
								}
								if(duplicated !== false){
									alert("Este ID de cuenta ya ha sido ocupado por la cuenta: " + duplicated);
									$('#accountNumber').focus();//.css('background-color','yellow');
									return false;
								}

							// TERMINA SECCION QUE VALIDA DUPLICADOS
							// INICIA SECCION QUE ELIMINA COMILLA SIMPLE Y DOBLE
								var prevVal = $(this).val();
								prevVal = prevVal.split('"').join("");
								prevVal = prevVal.split("'").join("");
								$(this).val(prevVal);
							// TERMINA SECCION QUE ELIMINA COMILLA SIMPLE Y DOBLE
							// INICIA SECCION QUE ELIMINA SEPARADORES
								for (var i = 0; i < val.length; i++)
								{
									if (isNaN(val[i])) val[i] = '';
								}
							// TERMINA SECCION QUE ELIMINA SEPARADORES
							$(this).css("background",'white');							
						}
						break;
					case 'accountName':
						if(val.length > 0)
						{
							$(this).css('background','white');
						}
						break;
				}
			}
			else {
				if(id === 'accountNumber'){
					if($("#sve").hasClass('mod'))
					{
						duplicated = checkDuplicatedItems($('.selected').data().account_id, val);
					}else if($("#sve").hasClass('reg')){
						duplicated = checkDuplicatedItems(-1,val);
					}
					if(duplicated !== false){
						alert("Este ID de cuenta ya ha sido ocupado por la cuenta: " + duplicated);
						$(this).focus().css('background', 'red');
					}
				}
			}
		});

		$("#group_dig").blur(function(){
			if($(this).val().length === 0)
			{
				$(this).val(0);
			}
		});
	// TERMINAN EVENTOS DE VALIDACION DE FORMULARIO

	// INICIA CREACION DE SHORTCUT PARA OCULTAR MODAL
		$(document).keyup(function(event) {
			if (($('#form').css('display') != 'none' || $('.layer').css('display') != 'none') && event.keyCode === 27)
				$("#cancel").click();
		});
	// INICIA CREACION DE SHORTCUT PARA OCULTAR MODAL
});
// checkDuplicatedItems() - Analiza si existen codigos de cuenta iguales al argumento recibido en el arbol 
function checkDuplicatedItems(code, id)
{
	var duplicated = false;
	codeLists = [];
	$('span').each(function() {
		if($(this).data() !== {})
		{
			if( code === -1 )
			{
				if( parseInt($(this).data().removed, 10) === 0)
				{
					temp = $(this).data().description + " (" + $(this).data().account_code + ")";
					codeLists.push([$(this).data().manual_code, temp]);
				}	
			}
			else
			{
				if( parseInt($(this).data().removed, 10) === 0 && $(this).data().account_id !== code)
				{
					temp = $(this).data().description + " (" + $(this).data().account_code + ")";
					codeLists.push([$(this).data().manual_code, temp]);
				}
			}
			
		}
	});
	for (var i = codeLists.length - 1; i >= 0; i--) {
		if(codeLists[i][0] == id)
			duplicated = (duplicated === false ) ? codeLists[i][1] : duplicated + ', ' + codeLists[i][1] ;
	}
	return duplicated;
}
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
// getLastBro()	- Genera el codigo de cuenta para un nuevo hermano
//					Asigna la cuenta padre al objeto y formulario
//					La opcion 1 la muestra al formulario. Cualquier otra solamente lo retorna...
function getLastBro(type)
{
	$("#fatherAccountId").text($('.selected').parent('li').parent().siblings('span').text());
	$("#fatherAccount").val($('.selected').parent('li').parent().siblings('span').data().account_id);

	var broAccountId = [];
	var pattern = $('.selected').data().account_code.split(".");
	pattern.pop();
	pattern = pattern.join(".");
	broAccountId.push(parseInt($('.selected').data().account_code.split(".").last(),10));

	$(".selected").parent().siblings().each(function(i){
		element = parseInt($("span",this).data().account_code.split(".").last(),10);
		broAccountId.push(element);
	});

	broAccountId.sort(sortNumber);
	maxNum = (broAccountId.length > 0) ? (parseFloat(broAccountId.last(), 10) + 1) : 1;
	var lastArray = pattern + "." + maxNum;
	
	if (type == 1)
	{
		$("#accountNumberId").text(lastArray);
		$("#auto").val(lastArray);

		if( $('#accountNumber').css('display') == 'none')
			$('#accountNumber').val(lastArray);
		else
			$('#accountNumber').val( setNewManualCode(lastArray) );
	}
	else
		return lastArray;
}
// getLastChildren()	- Genera el codigo de cuenta para un nuevo hijo
//						Asigna la cuenta padre al objeto y formulario
//						La opcion 1 la muestra al formulario. Cualquier otra solamente lo retorna...
function getLastChildren(type)
{
	$("#fatherAccountId").text($(".selected").text());
	$("#fatherAccount").val($('.selected').data().account_id);
	var pattern = $(".selected").data().account_code;
	var childrenAccountId = [];
	$('.selected').parent().children('ul').children("li").each(function(){
		element = $('span',this).data().account_code.split(".").last();
		childrenAccountId.push( element );
	});
	childrenAccountId.sort(sortNumber);
	var maxNum = (childrenAccountId.length > 0) ? parseFloat(childrenAccountId.last(),10) + 1 : 1;
	var lastArray = pattern + "." + maxNum;
	if (type == 1)
	{
		$("#accountNumberId").text(lastArray);
		$("#auto").val(lastArray);
		
		if( $('#accountNumber').css('display') == 'none')
			$('#accountNumber').val(lastArray);
		else
			$('#accountNumber').val( setNewManualCode(lastArray) );
	}
	else
		return lastArray;
}
// implode	- Metodo de implosion de array equivalente al de PHP
function implode(data,separator)
{
	finalData = '';
	for (var i = 0; i < data.length ; i++) {
		if( i === data.length -1 ){
			finalData += data[i];
		}
		else
		{
			finalData += data[i]+separator;
		}
	}
	return finalData;
}
//	loadForm()	- Carga el Formulario para la modificacion - 
function loadForm(data)
{
	mainChildrens = false; // Bandera mainChildrens: sirve para saber si la cuenta posee algun descendiente de mayor
	$('li > span').each(function(){
		if( $(this).data().account_id === data.account_id )
		{
			$(this).siblings("ul").find("span").each(function(argument) {
				if ($(this).data().main_account == 1 && $(this).data().removed != 1) // IMPORTANTE ANALIZAR SI FUE ELIMINADO O NO
				{
					mainChildrens = true;
					return false;// detiene ciclo $.each
				}
			});
			return false;// detiene ciclo $.each
		}
	});
	// Si su padre de mayor es diferente a su ID y su padre de mayor es diferente de 0 y mainChildrens = false
	if( ( parseInt(data.main_father,10) > 0 && data.main_father != data.account_id && parseInt( data.main_account , 10) !== 0 ) || ( mainChildrens  === true) )
	{
		$("#main>option[value='1']").attr("disabled","disabled");
		$("#main").select2({width:"100%"}).select2('val',data.main_account);
		
		if( parseInt(data.main_account,10) === 1 )
		{
			$("#s2id_main > a:nth-child(1)").text("DE MAYOR");
			$("#main").val(1);
		}
	}
	else
	{
		$("#main").select2({width:"100%"}).select2('val', data.main_account);
		$("#main>option[value='1']").removeAttr("disabled");
	}

	$("#fatherAccountId").text($("li[data-id-acc='"+ data.father_account_id +"'] > span").text());
	$("#accountNumberId").text(data.account_code);

	//	$("#").val(data.account_code);		para carga requiere validacion de la configuracion del sistema...
	$("#yd").val(data.account_id);
	$("#accountNumber").val(data.manual_code);
	$("#auto").val(data.account_code);
	$("#accountName").val(data.description);
	$("#secondName").val(data.sec_desc);
	$("#type").val(data.account_type);
	
	$("#type").select2({width:"100%"}).select2('val', data.account_type).select2('readonly',true);
	$("#nature").select2({width:"100%"}).select2('val', data.account_nature);
	$("#status").select2({width:"100%"}).select2('val', data.status);
	
	
	$("#coin").select2({width:"100%"}).select2('val', data.currency_id);
	$("#sucursal").select2({width:"100%"}).select2('val', data.id_sucursal);
	$("#group_dig").val(data.group_dig);
	$("#fatherAccount").val(data.father_account_id);
	$("#oficial").select2({width:"100%"}).select2('val', data.cuentaoficial);
	

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
// Proceso de Asignacion de el padre de Mayor
function setNewMainFather(data)
{
	var changesArray = [];
	$( '.selected' ).parent( "li" ).find( "span" ).each(function(){
		$(this).data('main_father', data);
		changesArray.push($(this).data());
	});
	return sendObject(changesArray, "changeFather");
}
// Proceso de generacion de codigo manual (basado en el codigo automatico)
function setNewManualCode(lastArray)
{

		lastArray = lastArray.split('.').join('');
		var placeHolder = $("#accountNumber").attr("placeholder");
		var newLastArray = '';
		var j = 0;
		for(var i = 0; i < placeHolder.length; i++)
		{
			if(placeHolder[i] == '*')
			{
				
				if (j >= lastArray.length )
				{
					newLastArray += "0";
				}
				else
				{
					newLastArray += lastArray[j];
				}
				j++;
			}
			else
			{
				newLastArray += placeHolder[i];
			}
		}
		return newLastArray;
}
// setNewId	- Genera el cambio de cuenta de mayor, codigo de cuenta
function setNewId(data)
{
	var changesArray = [];
	$(".movable span").each(function(index){
        if($(this).data() == $(".movable").children("span").data())
        {
			$(this).data('account_code',data);
			$(this).data('main_father',$('.selected').data().main_father);
			$(this).data('father_account_id',$(".selected").data().account_id);
        }
        else
        {
			var newAccount_code = $(this).parent("li").parent("ul").siblings("span").data().account_code + "." + ($(this).parent("li").index() + 1 );
			$(this).data("account_code", newAccount_code);
			$(this).data('main_father',$('.selected').data().main_father);
			//$(this).data('father_account_id',$(".selected").data().account_id);
        }
        changesArray.push($(this).data());
    });
    //$('.selected').text( '('+ $('.selected').data().manual_code +') '+ $('.selected').data().description.toUpperCase() );// Cambio de Texto...
    return sendObject(changesArray, "changeFather");
}
// setIcons	- Genera la asignacion de iconos segun su nivel.
function setIcons()
{
	$('img').each(function(){
		var parent = $(this).parent('li').attr("class");
		switch(parent)
		{
			case 'x0':
				$(this).attr("src",'images/0.gif');
				break;
			case 'x1':
				$(this).attr("src",'images/1.gif');
				break;
			case 'x2':
				$(this).attr("src",'images/2.gif');
				break;
			case 'x3':
				$(this).attr("src",'images/3.gif');
				break;
			case 'x4':
				$(this).attr("src",'images/4.gif');
				break;
			default:
				$(this).attr("src",'images/4.gif');
				break;
		}
	});
}
// setTypeByName()	- Asigna el tipo de Cuenta (Activo, Pasivo, Capital, etc...) Segun su ultimo ancestro
function setTypeByName()
{
	var typeName = $('.selected').parents('li').last().children('span').text();
	var typeCode = $('.selected').parents('li').last().children('span').attr('code');
	//alert(typeName)
	typeName = typeName.split(') ')
	typeName = typeName[1];

	$("#type > option").each(function(){

	switch(typeCode)
	{
		case '1': typeName = "ACTIVO";break;
		case '2': typeName = "PASIVO";break;
		case '3': typeName = "CAPITAL";break;
		case '4': typeName = "RESULTADOS";break;
		DEFAULT: typeName = "DE ORDEN";
	}
	
		
		if($(this).text() == typeName)
		{
			$("#type").select2({width:"100%"}).select2('val', $(this).val()).select2('readonly',true);
		}
	});
}
// sortNumber() - Metodo de ordenacion numerica ( por default la ordenacion es alfabetica)
function sortNumber (a,b) {
	return a - b;
}
function exportar(){
window.open("ajax.php?c=AccountsTree&f=cvs");
}
