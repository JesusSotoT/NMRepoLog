var option;
var banderita=0;
var $total=0;
 
$(function(){
	var seleccionado = $("#tipopro option:selected").val();
	var numcliks=0;
	$( "#tipopro" ).click(function() {
		if(numcliks===0) {
			numcliks=1;
			return;
		} else {
			numcliks=0;

			var tipo = $("#tipopro").val();
			idproducto = $('#id').val();
			if(idproducto==''){
				idproducto=0;
			}
			
			if(seleccionado == 2 && tipo != seleccionado){
				if(banderita == 0){
					var confirmacion = confirm("¿Deseas borrar insumos para cambiar tipo de producto?");
						if(confirmacion == true){
							elimina_insumos(idproducto);
							$('#tabla_agregados').html('');
							$("#margen_ganancia").hide('slow');
							$(".etaps").empty();
							$(".processes").empty();
							//$("#www").html("");
							$("#contenido_etapas").hide();
							elimina_etapas(idproducto);
							banderita = 1;
						
						}else{
							tipo = seleccionado;
							$("#margen_ganancia").show('slow');
							$("#tipopro").val(2);
						}
				}
			}
			
			if(seleccionado ==  4 && tipo != seleccionado){
				if(banderita == 0){
					var confirmacion = confirm("¿Deseas borrar insumos del kid para cambiar de producto?");
					if(confirmacion == true){
						elimina_insumos(idproducto);
						$('#tabla_agregados').html('');
						$("#margen_ganancia").hide('slow');
						$(".etaps").empty();
						$(".processes").empty();
						//ajax que elimina de base
						elimina_etapas(idproducto);
						banderita = 1;
					}else{
						tipo = seleccionado;
						$("#margen_ganancia").show('slow');
						$("#tipopro").val(4);
					}
				}
				
			}
			/*if(tipo!=2 || tipo!=4){
				$('#margen_ganancia').hide();
				if(confirmacion){}
			}
*/
			if(tipo!=5) {
				$("#vendible").prop("checked",true);
				$("#vendible").attr("disabled",false);
				if(tipo==6) {
					$("#stock").hide('slow'); 
					$("#etaps").hide('slow');
				//$("#inicial").val('1');
				} else {
				$("#stock").show('slow');
				$("#etaps").show('slow');
				//$("#inicial").val('0');
			}
			if(tipo==2 || tipo==4){
				$(".tableCosto").show('slow');
				
				$("#contenido_etapas").show('slow');
				} else {
					$(".tableCosto").hide('slow');
				$("#contenido_etapas").hide('slow');
			}	
			} else {
			$("#vendible").attr("disabled",true);
			$("#vendible").attr("checked",false);
		}

		xx=window.location.href;
			if(xx.match(/formNuevo\/[0-9]{1,}/) ) {
			baseUrl='../../../';
			} else {
			baseUrl='../../';
		}
		url=baseUrl+'index.php/product/listaMateriales/'+tipo;

		$.ajax({
			type: 'POST',
			url:url,
			data: {producto:idproducto,
				baseurl:baseUrl
			},
			success: function(resp){  
					//$('#listaRows').empty();
					/*if(resp=='dif'){
					if(tipo==2)
						alert('No puedes cambiar el tipo de producto, elimina los materiales de la opcion "kit de productos"');
					if(tipo==4)
							alert('No puedes cambiar el tipo de producto, elimina los materiales de la opcion "Producir productos"');
					return false;
				}*/
			/*if(tipo==3){
				$('.dialogLista').dialog({
						modal: true,
						draggable: true,
						resizable: true,
						title:"Unidad de venta",
						width:480,
						height:300,
						dialogClass:"mydialog",
						open: function(){$(this).empty().append(resp);
						
						$(".numeric").numeric();
						},
						buttons:{
							"Agregar unidades": function(){
							// $("#tipopro option[value='4']").attr('disabled', 'disabled' ); 
							 if(typeof($("#num_materiales").val())  === "undefined") 
							{
								alert("Aún no has agregado ningun material al producto , selecciona el material presiona el boton '+' para agregarlo");
								return false;
							}
							
							$("#btonlistamateriales").html(" Materiales seleccionados:"+$("#num_materiales").val());
							
							$(this).dialog('close');	
								},
							"Salir": function(){
								
									$(this).dialog('close');
								}
						}
					}).height('auto');
}else*/
					if(tipo==2){
						$(".tableCosto").show('slow');
						$("#margen_ganancia").show('slow');
						$("#lista").empty().append(resp);
						
	/*$(".numeric").numeric();
						
	$('.dialogLista').dialog({
		modal: true,
		draggable: true,
		resizable: true,
		title:"Lista de materiales",
		width:500,
		height:400,
		dialogClass:"mydialog",
							open: function(){
								$(this).empty().append(resp);
			$(".numeric").numeric();
		},
		buttons:{
			"Ok": function(){
									$('#listaRows').empty();
									var mat_response = peticionProdMateriales(idproducto,tipo,1);
									if((typeof($("#num_materiales").val())  === "undefined") || (!mat_response)) 
							{
								alert("Aún no has agregado ningun material al producto , selecciona el material presiona el boton '+' para agregarlo");
								return false;
							}
							
							$("#btonlistamateriales").html(" Materiales seleccionados:"+$("#num_materiales").val());
							
									
							$(this).dialog('close');	
						},
						"Salir": function(){
							$(this).dialog('close');
						}
					}
				}).height('auto');*/

					} else if(tipo==4) {
						$(".tableCosto").show('slow');
						$("#margen_ganancia").show('slow');
						$("#lista").empty().append(resp);
/*
	$('.dialogLista').dialog({
		modal: true,
		draggable: true,
		resizable: true,
		title:"Lista de Productos",
		width:500,
		height:400,
		dialogClass:"mydialog",
		open: function(){$(this).empty().append(resp);

			$(".numeric").numeric();
		},
		buttons:{
			"Agregar productos": function(){

				if(typeof($("#num_materiales").val())  === "undefined") 
				{
					alert("Aún no has agregado ningun producto al kit , selecciona el producto presiona el boton '+' para agregarlo");
					return false;
				}

				$("#btonlistamateriales").html(" Productos seleccionados:"+$("#num_materiales").val());
				$(this).dialog('close');	
			},
			"Salir": function(){

				$(this).dialog('close');
			}
		}
	}).height('auto');
*/
}
}

});
}
});
$(".numeric").numeric();
$(".float").numeric({allow:"."});

jQuery.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}

var options = { 
	beforeSend: function() 
	{
	    	/*
			$("#progress").show();
	    	//clear everything
	    	$("#bar").width('0%');
	    	$("#message").html("");
			$("#percent").html("0%");
			*/
		},
		uploadProgress: function(event, position, total, percentComplete) 
		{
	    	//$("#bar").width(percentComplete+'%');$("#percent").html(percentComplete+'%');
	    },
	    success: function() 
	    {
	        //$("#bar").width('100%');	$("#percent").html('100%');
	    },
	    complete: function(response) 
	    {
	    	$("#imagen").val(response.responseText);
	    	$("#imagen-producto").html('<img width="225" height="250" src="'+baseUrl+response.responseText+'">');
	    },
	    error: function()
	    {
	    	alert("Ocurrio un error al agregar la imagen");
			//$("#message").html("<font color='red'> ERROR: No se pudo adjuntar el archivo</font>");
		}

	};

	$("#myForm").ajaxForm(options);
	$('#codigo').show().mask('*?************************',{placeholder:"_"});

});
/////////////////////////////////////////////  INICIO FUNCION ELIMINA ETAPAS //////////////////////////////////////
function elimina_etapas(idProducto){
	$.ajax({
		url:baseUrl+'index.php/product/elimina_etapas',
		type:'POST',
		data:{
			idProducto:idProducto,
		},
		success: function(response)
		{
			alert('Se eliminaron las etapas');
			//$('#etaps').remove();
		}
	});
}
///////////////////////////////////////////// INICIO FUNCION ELIMINA INSUMOS //////////////////////////////////////
function elimina_insumos(idProducto){
	
	$.ajax({
		url:baseUrl+'index.php/product/eliminar_insumos/',
		type:'POST',
		data:{
			idProducto:idProducto,
		},
		success: function(response)
		{
			alert("Se eliminaron los insumos de este producto");
			$("#margen_ganancia").hide();
			
			//alert(response);
		}
	});
}
//////////////////////////////////////////////INICIO FUNCION ALIMINAR INSUMOS KIT//////////////////////////////////
function elimina_insumos_kit(idProducto){
	ajax({
		url:baseUrl+'index.php/product/eliminar_insumos_kit/',
		type:'POST',
		data:{
			idProducto:idProducto,
		},
		success:function(response)
		{
			alert('Se eliminaron los insumos del Kit de este producto');
			$('#margen_ganancia').hide();
		}
	});
	
}

/////////////////////  ***************       	editar_lista	     ***************  /////////////////////////////

	// Crea un listado con los productos agregados en el array de sesion
	// Como parametros recibe:
		// idProducto-> id del producto
		// tipo-> tipo de producto(0,1,2).Donde 0-> normal, 1->opcional, 2->extra
		
function editar_lista(idProducto,tipo, $actual){
	// var producto=idProducto;
	
// Si la funcion es llamada de cualquier parte que no sea eliminar 
	if(!idProducto){
		$actual=1;
	}
	
	$.ajax({
		url:baseUrl+'index.php/product/costoMateriales',
		type:'POST',
		dataType: "json",
		data:{
			idProducto:idProducto,
			baseUrl:baseUrl,
			tipo:tipo,
			actual: $actual,
		},
		success: function(response){
			
			console.log(response);
			
			if(response){
				$('#listaRows').html('');
				
				var respuesta = response;
				var arrayMateriales = respuesta.arrayMateriales;
				var materialesCosto = respuesta.costoTotal;
				
				contadorMateriales = respuesta.contadorMateriales;
			
			// Recorre el arreglo de los materiales por nombre para agregarlos a la tabla
				for(var i=0; i<arrayMateriales.length; i++){
					var rowMaterial='';
					
				// Valores del material
					var productoMaterial = arrayMateriales[i];
				
				// Material normal. Si existe un material normal lo carga a la tabla.
					if(productoMaterial.cantidad>0){
						rowMaterial += '<tr id="'+productoMaterial.idMaterial+'">';
						rowMaterial += '	<td>'+productoMaterial.cantidad+'</td>';
						rowMaterial += '	<td>'+productoMaterial.nombre_material+'</td>';
						rowMaterial += '	<td>'+productoMaterial.compuesto+'</td>';
						rowMaterial += '	<td>'+productoMaterial.costo_normal+'</td>';
						rowMaterial += '	<td>'+productoMaterial.opcional_normal+'</td>';
						rowMaterial += '	<td class="button_delete"><input type="button" value="-" class="nminputbutton" onclick="Removematerial(\''+productoMaterial.idMaterial+"_normal\','"+productoMaterial.nombre_material+"','"+baseUrl+"'); editar_lista(\'"+productoMaterial.idMaterial+"_normal\',"+tipo+");\"></td>'";
						rowMaterial += '</tr>';
					}
				
				// Material opcional. Si existe un material opcional lo carga a la tabla.
					if(productoMaterial.cantidad2>0){
						rowMaterial += '<tr id="'+productoMaterial.idMaterial+'_opcional">';
						rowMaterial += '	<td>'+productoMaterial.cantidad2+'</td>';
						rowMaterial += '	<td>'+productoMaterial.nombre_material+'</td>';
						rowMaterial += '	<td>'+productoMaterial.compuesto+'</td>';
						rowMaterial += '	<td>'+productoMaterial.costo_opcional+'</td>';
						rowMaterial += '	<td>'+productoMaterial.opcional_opcional+'</td>';
						rowMaterial += '	<td class="button_delete"><input type="button" value="-" class="nminputbutton" onclick="Removematerial(\''+productoMaterial.idMaterial+"_opcional\','"+productoMaterial.nombre_material+"','"+baseUrl+"'); editar_lista(\'"+productoMaterial.idMaterial+"_opcional\',"+tipo+");\"></td>'";
						rowMaterial += '</tr>';
					}
				
				// Material extra. Si existe un material extra lo carga a la tabla.
					if(productoMaterial.cantidad3>0){
						rowMaterial += '<tr id="'+productoMaterial.idMaterial+'_extra">';
						rowMaterial += '	<td>'+productoMaterial.cantidad3+'</td>';
						rowMaterial += '	<td>'+productoMaterial.nombre_material+'</td>';
						rowMaterial += '	<td>'+productoMaterial.compuesto+'</td>';
						rowMaterial += '	<td>'+productoMaterial.costo_extra+'</td>';
						rowMaterial += '	<td>'+productoMaterial.opcional_extra+'</td>';
						rowMaterial += '	<td class="button_delete"><input type="button" value="-" class="nminputbutton" onclick="Removematerial(\''+productoMaterial.idMaterial+"_extra\','"+productoMaterial.nombre_material+"','"+baseUrl+"'); editar_lista(\'"+productoMaterial.idMaterial+"_extra\',"+tipo+");\"></td>'";
						rowMaterial += '</tr>';
					}
				
				// Carga los materiales a la tabla
					$('#listaRows').append(rowMaterial);
				}
			
			// Crea el final de la tabla con el total
				var finalRow = '<tr>';
					finalRow+='		<td  class="costo"></td>';
					finalRow+='		<td  class="costo">Costo Total</td>';
					finalRow+='		<td  class="costo" style="text-align:right;">$</td>';
					finalRow+='		<td  class="costo">';
					finalRow+='			<input  id="costo_total" readonly="" value="'+materialesCosto+'" type="text">';
					finalRow+='		</td>';
					finalRow+='	</tr>';
			
			// Agrega el total a la tabla
				$('#listaRows').append(finalRow);
			}
		
		// Agrega el numero de materiales a la div
			$("#num_materiales").val(respuesta.contadorMateriales);
		
		// Arroja una alerta si no se ha seleccionado ningun material
			if((typeof($("#num_materiales").val())  === "undefined")){
				alert("Aún no has agregado ningun material al producto , selecciona el material presiona el boton '+' para agregarlo");
			}

			$("#btonlistamateriales").html(" Materiales seleccionados:"+$("#num_materiales").val());
		}
	});
}
/////////////////////  ***************       	FIN editar_lista	     ***************  /////////////////////////////

//Validación de proveedores y costos de proveedores adicionales
function validarAdicionales(urlguardar)
{	//revisión de contenido
	//console.log(urlguardar);
	func(urlguardar);
}
function Remove_me(e){
	$('#'+e).remove();
}
//////////////////////////////////Regla de tres para margen de ganancia///////////////////////////
function generar_margen($objeto){
	var costo_total =parseFloat($objeto['costo_total']);
	var margen=parseFloat($objeto['margen']);
	
	if($objeto['margen']<=0){
		alert('Debes ingresar una cantidad valida');
		return 0;
	}
	
	// var costo_total = parseFloat($('#total_agregados').html());
	// var margen = parseFloat($('#porcentaje_ganancia').val());
	console.log($objeto);
	
	var margen_ganancia = (margen * costo_total)/100;
	var margen_aplicado = costo_total + margen_ganancia;
	
	console.log(margen_ganancia);
	console.log(margen_aplicado);
	
	var confirmado = confirm('Esta sería tu ganancia: $'+margen_ganancia+' Deseas aplicarlo al total?');
	
	if(confirmado == true){
		$('#div_margen_venta').show('slow');
		$('#venta_margen').val(margen_aplicado);
	}
	
}
////////////////////////////////////////
/////////////////////////////////////////INICIO DE FUNCION APLICA MARGEN
function aplicar_margen(){
	confirmacion = confirm('¿El precio con ganancia se aplicará al precio de venta?');
	if(confirmacion){
		var precio_margen = $('#venta_margen').val();
		$('#preciov').val(precio_margen);
		alert('Se cambió tu precio de venta a: $ '+precio_margen);
		$('#div_margen_venta').hide('slow');
		calcula_neto();
	}else{
	}
	
}
//////////////////////////////////////////////
function func(urlguardar) { //Toma los valores en el formulario y comprueba que no esten vac�os
//	console.log(urlguardar);
	fields = new Array();
	fieldsp = new Array();
	fieldsp['pronombre']= new Array();
	fieldsp['prodesc']= new Array();
	fieldsp['produra']= new Array();
	fieldsp['proorden']= new Array();
	var x=$("#www").serializeArray();
	var y=$('#listaProvedores').serializeArray();
	var z=$('#preciosnuevos').serializeArray();
	var costo_produccion =parseFloat($("#total_agregados").html());
	console.log(costo_produccion);
	console.log(x);
	var descx = 0;

	if($('#descx').is(':checked')){
		descx=0;
	}else{
		descx=1;
	}
	//alert(y);
	//return;
	//fields.etapa = new Array();
	$( "input[name=labeletapa]" ).each(function() {
		etapa = $(this).val();
		fields[etapa]='';
		$( "#listaprocesos_"+etapa+" input[name=pronombre]" ).each(function() {
			pronombre=$(this).val();
			fieldsp['pronombre'].push(pronombre);
		});
		$( "#listaprocesos_"+etapa+" textarea[name=prodesc]" ).each(function() {
			prodesc=$(this).val();
			fieldsp['prodesc'].push(prodesc);
		});
		$( "#listaprocesos_"+etapa+" input[name=produra]" ).each(function() {
			produra=$(this).val();
			fieldsp['produra'].push(produra);
		});
		$( "#listaprocesos_"+etapa+" input[name=proorden]" ).each(function() {
			proorden=$(this).val();
			fieldsp['proorden'].push(proorden);
		});

		fields[etapa]=fieldsp;
		//sfieldsp['pronombre']=fieldspd;
		//console.log(fieldsp);

		fieldsp = new Array();
		fieldsp['pronombre']= new Array();
		fieldsp['prodesc']= new Array();
		fieldsp['produra']= new Array();
		fieldsp['proorden']= new Array();

		qwe=new Array();
		qwe[0]=fields;
		console.log(qwe)
		//console.log(x);
	});
	//var fields = $("#www").serializeArray($("#divetapas").serializeArray($('input[name=check_imp]').serializeArray()))
	//alert(fields);


	var tipo_prod = $("#tipopro").val();
	var id = $("#id").val();
	var linea = $("#lin").val();
	var nombre = $("#name").val();
	var descripcion_corta = $("#desc").val();
	var descripcion_larga = $("#desl").val();
	var descripcion_cenefa = $("#descen").val();
	var color = $("#col").val();
	var talla = $("#tal").val();
	var maximo = $("#maximo").val();
	var minimo = $("#minimo").val();
	var inicial = '';
	
	if( $("#hdConversion").val() == ''){
		inicial = $("#inicial").val();
	}else{
		inicial = $("#hdConversion").val();
	}
	
	var materiales = $("#listamateriales").val();
	//alert(materiales);
	var imagen = $("#imagen").val();
	var codigo = $("#codigo").val();
	var cadena_alert = "";
	var consumo = 0;
	var vendible = 0;
	var esreceta = 0;
	var eskit = 0;
	var unidad=$("#cboUVenta").val();
	var unidadCompra = $('#cboUCompra').val();
	var hayImpuestosVacios = false;
	var margen=$('#porcentaje_ganancia').val();
	
	console.log(margen);
	
	if($("#alerta_nombre").html() != "" || $("#alerta_clave").html() != "" || $("#alerta_corta").html() != "" || $("#alerta_larga").html() != "" || $("#alerta_cenefa").html() != "")
	{
		cadena_alert += " - Algunos campos no se rellenaron correctamente.\n";
	}
	
	if(imagen == "")
	{
		imagen = "images/noimage.jpeg";
	}
	
	var contador_impuestos = $("#contador_impuestos").val();
	var impuestos_ids = new Array();
	var impuestos_valores = new Array();
	
	for(var i=0; i<contador_impuestos; i++)
	{
		if ($('#chk_'+i).is(':checked'))
		{
			impuestos_ids.push($('#chk_'+i).val());
			impuestos_valores.push($('#impuesto_'+i).val());
			
			if(impuestos_valores[i] == "")
			{
				hayImpuestosVacios = true;
			}
		}
	}
	
	var preciov = $("#preciov").val();
	var preciom = $("#preciom").val();
	var preciol= $("#preciol").val();
	
	if (hayImpuestosVacios)
		cadena_alert += " - Quedaron impuestos vacíos.\n";
	if (codigo == '')
		cadena_alert += " - No escribiste el codigo.\n";
	if (linea == '')
		cadena_alert += " - No seleccionaste ninguna linea.\n";
	if (nombre == '')
		cadena_alert += " - No escribiste nada en el campo de nombre. \n";
	/*if (proveedor == '')
		cadena_alert += " - No escribiste nada en el campo de proveedor. \n";
	if (costo_proveedor == '')
		cadena_alert += " - No escribiste nada en el campo de costo. \n";
	*/
	if (preciov == '')
		cadena_alert += " - No escribiste el precio de venta del producto.\n";
	
	/*$(".proveedor_adicional").each(function(){
		if($(this).val() == "") {
			cadena_alert += "Falta llenar algún campo de proveedor adicional";
			return false;
		}
		
	});  */
	/*if (preciov == 0 && (tipo_prod!=3 || tipo_prod!=5)) //si el tipo de producto es igual a de consumo o inusmo
		cadena_alert += " - No escribiste el precio de venta del productooooooooooo.\n";	*/	
	///////////////////////////
	if (unidad == '')
		cadena_alert += " - No seleccionaste una unidad para el producto.\n";
	
	////////////////////////
	if (document.getElementById('esreceta').checked) {
		var esreceta= 1;
	} else {
		var esreceta = 0;
	}
	if (document.getElementById('eskit').checked) {
		var eskit= 1;
	} else {
		var eskit = 0;
	}

	if (document.getElementById('consumo').checked) {
		var consumo = 1;
	} else {
		var consumo = 0;
	}
	if (document.getElementById('vendible').checked) {
		var vendible= 1;
	} else {
		var vendible = 0;
	}

 /*   if(consumo == 0 && vendible == 0)
    {
    	cadena_alert += " - No especificaste si el producto es vendible o de consumo. \n";
    } */
	var proveedor = $("#proveedor").val();
	var costo_proveedor = $("#costo_proveedor").val();
    
    if (maximo == '')
    {
    	cadena_alert += " - No escribiste el maximo. \n";
    }

    if (minimo == '')
    {
    	cadena_alert += " - No escribiste el minimo. \n";
    }
    if (  proveedor!==""  && costo_proveedor==''  )
    {
    	cadena_alert += " - Debes seleccionar un costo para el producto. \n";
    }
	
    if (costo_proveedor!='' && (parseFloat(costo_proveedor) >= parseFloat(preciov)) && document.getElementById('vendible').checked  )
    {
    	cadena_alert += " - El precio de venta no debe ser menor o igual al costo. \n";
    }
	$('.descri').each(function () {
		if($(this).val()==''){
			//alert('- No puedes dejar la descripcion del precio vacia.');
			cadena_alert += '- No puedes dejar la descripcion del precio vacia.';

			return ;
		}
	});

	$('.preci').each(function () {
		if($(this).val()=='' || $(this).val() <= 0){
			//alert('- No puedes dejar precios vacios o en 0 en la lista de precios.');
			cadena_alert += '- No puedes dejar precios vacios o en 0 en la lista de precios.';
			return ;
		}
	});


	$('.contenedor_div').each(function() {
		//console.log($(this).find('.proveedor_added').val());
		//console.log($(this).find('.costo_proveedor_added').val());
		
		var proveedor_added = $(this).find('.proveedor_added').val();
		var costo_proveedor_added = $(this).find('.costo_proveedor_added').val();
			if(costo_proveedor_added == '' && proveedor_added != '' ){
				cadena_alert += '- Debes llenar costo para el proveedor: '+option+'\n' ;
				//console.log(proveedor_added);
				//console.log(costo_proveedor_added);
				return ;
			}
			
			if (parseFloat(preciov) <= parseFloat(costo_proveedor_added) && document.getElementById('vendible').checked ){
				cadena_alert += 'El costo del producto no debe ser mayor o igual al precio de venta';
				return ;
				
			}
			if(parseFloat(costo_proveedor_added) == 0 && document.getElementById('vendible').checked ){
				cadena_alert += 'El costo del producto no debe ser cero';
				return ;
				
			}
			
	});
	

   /* if ( costo_proveedor=='' && (proveedor!="")   )
    {
    	cadena_alert += " - Debes ingresar un costo. \n";
    } */

	//Si se gener� algo en cadena_alert quiere decir que hay alguno vac�o
	if (cadena_alert != "")
	{		
		//alerta de los campos vac�os
		alert (cadena_alert);
	}
	else
	{
				//Si no hay campos vac�os se procede a registrar el producto en la BD
				$("#send").attr("disabled","disabled");
				$("#loader").show();
				qwe= new Array();
				qwe[0]={};
				qwe[0]['etapa']='e1';
				qwe[0]['pr']={};
				qwe[0]['pr'][0]='xxx';
				qwe[0]['pr'][1]='yyy';
console.log(qwe);
$.ajax({
	url:urlguardar+'modulos/mrp/index.php/product/registraProducto',
	type: 'POST',
	data: {
		proveedor:proveedor,
		costo:costo_proveedor,
		preciov:preciov,
		preciom:preciom,
		preciol:preciol,
		esreceta:esreceta,
		consumo: consumo,
		vendible: vendible, 
		id:id,
		linea: linea, 
		nombre: nombre,
		des_cor: descripcion_corta, 
		des_lar: descripcion_larga,
		des_cen: descripcion_cenefa,
		color: color, 
		talla: talla,
		materiales:materiales,
		maximo:maximo,
		minimo:minimo,
		inicial: inicial,
		imagen:imagen,
		codigo:codigo, 
		impuestos_ids: impuestos_ids, 
		impuestos_valores: 
		impuestos_valores,
		inicial: inicial, 
		eskit: eskit,
		unidad:unidad,
		tipo_prod:tipo_prod,
		x:x,
		unidadCompra : unidadCompra,
		y:y,
		costo_produccion:costo_produccion,
		margen:margen,
		z:z,
		descx:descx,
	},
	success: function(callback){
		console.log(callback);
		
		if(isNaN(callback)){
			alert(callback);	
		}else{
		//alert("Producto registrado con exito id:"+callback);
			alert("Producto registrado con éxito");
							
			window.location = urlguardar+"modulos/punto_venta/catalogos/gridproducto.php";	
		}
						
		$("#loader").hide();
		
		$("#send").removeAttr("disabled");
						// parent.window.location=urlguardar+'modulos/mrp/index.php/product/grid';*/
		}
	});
}
}
/////////////////////////////////////////////////////////////////////////////////////////////	
function buscaFamilia(idDepartamento)
{
	$.ajax({
		url:baseUrl+'index.php/product/familia',
		type: 'POST',
		data: {id:idDepartamento},
		success: function(callback)
		{	
			$("#fam_div").html(callback);
		}
	});
}		
/////////////////////////////////////////////////////////////////////////////////////////////	
function buscaLinea(idFamilia)
{
	
	$.ajax({
		url:baseUrl+'index.php/product/linea',
		type: 'POST',
		data: {id:idFamilia},
		success: function(callback)
		{	
			$("#lin_div").html(callback);
		}
	});
	
}
/////////////////////////////////////////////////////////////////////////////////////////////	


function cPCalcular(){
	calcula_venta();
}

function cPGuardar(){
	calcula_venta();
	$contador_impuestos = $("#contador_impuestos").val();
	for(var i=0; i<$contador_impuestos; i++)
	{
		if ($('#chkCal_'+i).is(':checked')){
			$("#chk_"+i).prop('checked',true);
		}
		else {
			$("#chk_"+i).prop('checked',false);
		}
	}
	$("#preciov").val($("#preciovCal").val());
	$("#precio_neto").val($("#precioNetoCalc").val());
	$('.dialogLista').modal('hide');	
}

function calculaPrecio(){
	$contador_impuestos=$("#contador_impuestos").val();
	//alert($contador_impuestos);
	
	$("#dialogListaHeader").empty().html("Calcular precio neto");
	$("#dialogListaBody").empty().append(contenidoCalculaPrecio($contador_impuestos));
	$("#dialogListaFooterCalcular").show();
	calcula_venta();
	$('input[name=impus]').click(function(){
		$("input:checkbox."+$(this).attr('class')).not($(this)).prop("checked",false);
		calcula_neto();
	});
	$(".dialogLista").modal('show');
}

function contenidoCalculaPrecio(contador_impuestos){
	$codigo = '<div class="row"><div class="col-md-12"><div class="table-responsive">';
	$codigo += '<table class="table">';
	$codigo += '	<tr>';
	$codigo += '		<td>';
	$codigo += '			<label>Precio Neto</label>';
	$codigo += '		</td>';
	$codigo += '		<td>';
	$codigo += '			<label>Impuestos</label>';
	$codigo += '		</td>';
	$codigo += '	</tr>';
	$codigo += '	<tr>';
	$codigo += '		<td>';
	$codigo += '			<input type="text" class="float" id="precioNetoCalc" value="'+$("#precio_neto").val()+'">';
	//$codigo += '<input type="text" id="precioNetoCalc"';
	$codigo += '		</td>';
	$codigo += '		<td rowspan='+contador_impuestos+'>';
	for($i=0;$i<contador_impuestos;$i++){
		$codigo += '			<div style="display: table; width: 100%;">';
		$codigo += '				<div style="display: table-cell; width: 50%;" >';
		$codigo += $("#hideImp_"+$i).val()+'<input type="hidden" value="'+$("#hideImp_"+$i).val()+'" id="hideImpbox_'+$i+'">';
		$codigo += '				</div>';
		$codigo += '				<div style="display: table-cell; width: 30%;" >';
		$codigo += '					<input type="hidden" id="hideimpuesto_'+$i+'"  value="'+$("#impuesto_"+$i).val()+'">';
		$codigo += 						$("#impuesto_"+$i).val();
		$codigo += '				</div>';
		$codigo += '				<div style="display: table-cell; width: 20%;" >';
		$codigo += '					<input type="checkbox" name="impus" class="calc_'+$("#hideImp_"+$i).val()+'" id="chkCal_'+$i+'" value="'+$i+'" ';
		if($("#chk_"+$i).is(':checked')){
			$codigo += ' checked="checked" ';
		}
		$codigo += '>';
		$codigo += '				</div>';
		$codigo += '			</div>';
	}
	$codigo += '		</td>';
	$codigo += '	</tr>';
	$codigo += '	<tr>';
	$codigo += '		<td>';
	$codigo += '			Precio sin impuestos';
	$codigo += '		</td>';
	$codigo += '	</tr>';
	$codigo += '	<tr>';
	$codigo += '		<td>';
	$codigo += '			$<label id="preciovCal" value="0">0.00</label>';
	$codigo += '		</td>';
	$codigo += '	</tr>';
	$codigo += '</table>';
	$codigo += '</div></div></div>';

	return $codigo;

}



/////////////////////////////////////////////////////////////////////////////////////////////	
function ListaMateriales_carga(idproducto,baseUrl)
{
	$.ajax({
		type: 'POST',
		url:baseUrl+'modulos/mrp/index.php/product/listaMateriales',
		data: {producto:idproducto,baseurl:baseUrl},
		success: function(resp){}
	});
}  

function ListaMateriales(idproducto,baseUrl)
{
	$.ajax({
		type: 'POST',
		url:baseUrl+'modulos/mrp/index.php/product/listaMateriales',
		data: {producto:idproducto,baseurl:baseUrl},
		success: function(resp){    

			$('.dialogLista').dialog({
				modal: true,
				draggable: true,
				resizable: true,
				title:"Lista de materiales",
				width:480,
				height:300,
				dialogClass:"mydialog",
				open: function(){$(this).empty().append(resp);

					$(".numeric").numeric();
				},
				buttons:{
					"Agregar materiales": function(){

						if(typeof($("#num_materiales").val())  === "undefined") 
						{
							alert("Aún no has agregado ningun material al producto , selecciona el material presiona el boton '+' para agregarlo");
							return false;
						}

						$("#btonlistamateriales").html(" Materiales seleccionados:"+$("#num_materiales").val());

						$(this).dialog('close');	
					},
					"Salir": function(){

						$(this).dialog('close');
					}
				}
			}).height('auto');


		}});
}
/////////////////////////////////////////////////////////////////////////////////////////////	
function Agregarmaterial(baseUrl,producto,tipo)
{
	console.log(baseUrl);
	if($("#cantidadm").val()=="" || $("#cantidadm").val()==0 ){alert("Debes ingresar una cantidad");return false;};
	if($("#material").val()==""){alert("Debes seleccionar un material");return false;};
	if($("#unidad").val()==""){alert("Debes seleccionar la unidad");return false;};
	
	$.ajax({
		type: 'POST',
		url:baseUrl+'index.php/product/agregarmaterial/'+tipo,
		data:{
			baseurl:baseUrl,
			producto:producto,
			material_nombre:$("#material option:selected").html(),
			material:$("#material").val(),
			cantidad:$("#cantidadm").val(),
			unidad:$("#unidad option:selected").html(),
			idUnidad:$("#unidad").val(),
			opcional:$("#opcional").val()
		},
		success: function(callback){
			$("#listaproductos").html(callback);
			$("#cantidadm").val('');
			
			$("#material").val('').change(function() {
				//alert($(this).val());
				unidadesMateriales($(this).val());
			});
			
			console.log($(this).val());
			
			$("#opcional").val('');
			$("#btonlistamateriales").html("");
		}
	});
}

function unidadesMateriales(elemento){ alert('ddwdwdwdwd');
	valor=elemento;

	xx=window.location.href;
	if( xx.match(/formNuevo\/[0-9]{1,}/) ){
		baseUrl='../../../';
	}else{
		baseUrl='../../';
	}


	$.ajax({
		type: 'POST',
		url:baseUrl+'index.php/product/material',
		dataType: 'json',
		data: { 
			valor:valor
		},
		success : function(vale){
			console.log(vale);
			
			if(vale!=''){
				$("#unidad").empty();
						//alert(vale);
				$.each(vale, function(index, x) {
					$(document.createElement('option')).attr({'value':x['idUni']}).html(x['compuesto']).appendTo($("#unidad"));
				
				
				});
			}
		}
	});
	
	
	$('#example').append('<option value="foo" selected="selected"></option>');
	
	
	 
}

function quitaEtapa(netapa){
	//alert(netapa);
	$('#e_'+netapa+'_e').remove();
	$('#listaprocesos_'+netapa).remove();

}
function quitaPro(pro,netapa,pron){
	
	tiempo=$("#t_"+netapa+"_"+pron+"_t").val();
	tiempoactual=$("#total_"+netapa+"_hid").val();
	//	alert(tiempo);
	//	alert(tiempoactual);
	explo1=tiempoactual.split(':');
	explo=tiempo.split(':');
	
	var	anos=parseInt(explo1[0])-parseInt(explo[0]);
	var	meses=parseInt(explo1[1])-parseInt(explo[1]);
	var	dias=parseInt(explo1[2])-parseInt(explo[2]);
	var	horas=parseInt(explo1[3])-parseInt(explo[3]);
	var	minutos=parseInt(explo1[4])-parseInt(explo[4]); 
	var	segundos=parseInt(explo1[5])-parseInt(explo[5]);
	var	duracion=(anos+':'+meses+':'+dias+':'+horas+':'+minutos+':'+segundos);

	$("#total_"+netapa+"_hid").val(duracion);

	$('#'+pro).remove();
}

function agregaetapa(){

	netap=$("#etapanombre").val();
	netapa=netap.replace(/ /g,'_');
	$( ".etaps" ).each(function( index ) {
		if( $( this ).attr('id')=='e_'+netapa+'_e' ){
			alert('Ya existe una etapa con el mismo nombre');
			$("#etapanombre").val('');
			return false;

		}
	});

	if($("#etapanombre").val()==""){
		alert("Ingresa un nombre a la etapa");
		return false;
	} 
	//

	$("#divetapa").append(''+
		'<section id="e_'+netapa+'_e" class="etaps">'+
			'<div class="row" id="xform">'+
				'<div class="col-md-6">'+
					'<input type="hidden" style="width: 100px;border: 1px solid #dddddd;margin-top: 0px;padding: 1px;" id="labe_'+netapa+'_letapa" name="labeletapa" readonly value="'+netapa.replace(/_/g,' ')+'" class="npr">'+
					'<input style="width: 100px;border: 1px solid #dddddd;margin-top: 0px;padding: 1px;" id="labe_'+netapa+'_letapa" class="npr nminputtext" name="labeletapausu" readonly value="'+netapa.replace(/_/g,' ')+'">'+
					'<input type="hidden" style="width: 100px;border: 1px solid #dddddd;margin-top: 1px;padding: 2px;" id="total_'+netapa+'_hid" name="total" readonly value="0:0:0:0:0:0">'+
				'</div>'+
				'<div class="col-md-6">'+
					'<button type="button" class="btn btn-default btnMenu" onclick="quitaEtapa(\''+netapa+'\'); " style="cursor: pointer;">Eliminar</button>'+
				'</div>'+
			'</div>'+
			'<div class="row">'+
				'<div class="col-md-4">'+
					'<input type="text" placeholder="Nombre del proceso" id="etaName'+netapa+'" class="letrasNumeros form-control">'+
				'</div>'+
				'<div class="col-md-4">'+
					'<textarea id="etaDesc'+netapa+'" placeholder="Descripcion" class="form-control" rows="5" ></textarea>'+
				'</div>'+
				'<div class="col-md-4">'+
					'<input type="hidden" id="etaDuracion'+netapa+'" value="" placeholder="Duracion" class="duracion" onclick="loquesea(\''+netapa+'\');" onkeyup="loquesea(\''+netapa+'\');">'+
					'<input type="text" id="etaDuracion_h'+netapa+'" value="" placeholder="Duracion" class="duracion form-control" onclick="loquesea(\''+netapa+'\');" onkeyup="loquesea(\''+netapa+'\');">'+
				'</div>'+
			'</div>'+
			'<div class="row">'+
				'<div class="col-md-4">'+
					'<input type="text" size="5" placeholder="Orden" id="etaOrden'+netapa+'" class="numero form-control" size="5">'+
				'</div>'+
				'<div class="col-md-4">'+
					'<button class="btn btn-primary btnMenu" onclick="AgregaProceso(\''+netapa+'\');">+</button>'+
				'</div>'+
				'<div class="col-md-4">'+
				'</div>'+
			'</div>'+
			'<section id="listaprocesos_'+netapa+'">'+
			'</section>'+
			'<div class="modal fade" tabindex="-1" id="my_'+netapa+'_Dialog" role="dialog">'+
				'<div class="modal-dialog">'+
					'<div class="modal-content">'+
						'<div class="modal-header">'+
							'<h4 class="modal-title">Tiempo del Proceso</h4>'+
						'</div>'+
						'<div class="modal-body">'+
							'<div class="row">'+
								'<div class="col-md-6">'+
									'<label>Años:</label>'+
									'<br>'+
			    					'<input type="text" id="anosin'+netapa+'" class="form-control" readonly value="0">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
								'<div class="col-md-4" id="anos'+netapa+'" style="margin: 5% 6%;">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
							'</div>'+
							'<div class="row">'+
								'<div class="col-md-6">'+
									'<label>Meses:</label>'+
									'<br>'+
			    					'<input type="text" id="mesesin'+netapa+'"  class="form-control" readonly value="0">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
								'<div class="col-md-4" id="meses'+netapa+'" style="margin: 5% 6%;">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
							'</div>'+
							'<div class="row">'+
								'<div class="col-md-6">'+
									'<label>Dias:</label>'+
									'<br>'+
			    					'<input type="text" id="diasin'+netapa+'" class="form-control" readonly value="0">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
								'<div class="col-md-4" id="dias'+netapa+'" style="margin: 5% 6%;">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
							'</div>'+
							'<div class="row">'+
								'<div class="col-md-6">'+
									'<label>Horas:</label>'+
									'<br>'+
			    					'<input type="text" id="horasin'+netapa+'" class="form-control" readonly value="0">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
								'<div class="col-md-4" id="horas'+netapa+'" style="margin: 5% 6%;">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
							'</div>'+
							'<div class="row">'+
								'<div class="col-md-6">'+
									'<label>Minutos:</label>'+
									'<br>'+
			    					'<input type="text" id="minutosin'+netapa+'" class="form-control" readonly value="0">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
								'<div class="col-md-4" id="minutos'+netapa+'" style="margin: 5% 6%;">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
							'</div>'+
							'<div class="row">'+
								'<div class="col-md-6">'+
									'<label>Segundos:</label>'+
			    					'<input type="text" id="segundosin'+netapa+'" class="form-control" readonly value="0">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
								'<div class="col-md-4" id="segundos'+netapa+'" style="margin: 5% 6%;">'+
								'</div>'+
								'<div class="col-md-1">'+
								'</div>'+
							'</div>'+
						'</div>'+
						'<div class="modal-footer">'+
							'<div class="row">'+
								'<div class="col-md-6">'+
									'<button class="btn btnMenu btn-primary" onclick="javascript:loqueseaAceptar('+netapa+');">Aceptar</button>'+
								'</div>'+
								'<div class="col-md-6">'+
									'<button class="btn btnMenu btn-danger" data-dismiss="modal">Salir</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>'+
		'</section>');
	
	//$(".numero").numeric();
	$('.letrasNumeros').validCampoFranz('0123456789abcdefghijklmnñopqrstuvwxyzáéiou ');
	$('.numero').validCampoFranz('0123456789');
	/*	tiempo=$("#etaduracion"+netapa).val();
	sumatiempo(tiempo); */

	$("#labe_"+netapa+"_letapa").val(netapa);
	$("#durae_"+netapa+"_durae").val(netapa);
	$("#xform").show('slow');


}
function sumatiempo(tiempo,netapa){
//alert(netapa);

tiempoactual=$("#total_"+netapa+"_hid").val();
explo1=tiempoactual.split(':');
explo=tiempo.split(':');

var	anos=parseInt(explo1[0])+parseInt(explo[0]);
var	meses=parseInt(explo1[1])+parseInt(explo[1]);
var	dias=parseInt(explo1[2])+parseInt(explo[2]);
var	horas=parseInt(explo1[3])+parseInt(explo[3]);
var	minutos=parseInt(explo1[4])+parseInt(explo[4]); 
var	segundos=parseInt(explo1[5])+parseInt(explo[5]);
var	duracion=(anos+':'+meses+':'+dias+':'+horas+':'+minutos+':'+segundos);

$("#total_"+netapa+"_hid").val(duracion);



}
function AgregaProceso(netapa){
 //alert(document.url);
 
 npros=$("#etaName"+netapa).val();
 npro=npros.replace(/ /g,'_');

 proceso=$("#etaName"+netapa).val();
 procesofor=proceso.replace(/_/g,' ');

 $( "#listaprocesos_"+netapa+" .pro" ).each(function( index ) {
		//alert( $( this ).val() );
		if( $( this ).attr('id')=='p_'+netapa+'_p' ){
			if( $( this ).val()==npro ){
				alert('ya exixte');
				$("#etaName"+netapa).val('');
				return false;

			}
		}
	});

 if($("#etaName"+netapa).val()==""){
 	return false;
 } 

 if($("#etaName"+netapa).val()==""){alert("no pudeeeeees dejar el campo vacio"); return false;}
 if($("#etaDesc"+netapa).val()==""){alert("no pudes dejar el campo vacio"); return false;}
 if($("#etaDuracion"+netapa).val()==""){alert("no pudes dejar el campo vacio"); return false;}
 if($("#etaOrden"+netapa).val().length<=0){alert("Debes ingresar un numero"); return false;}

	//if($("#etaOrden"+netapa).numeric($("#etaOrden"+netapa).val())==""){alert("no pudes dejar el campo vacio"); return false;}

	tiempo=$("#etaDuracion"+netapa).val();
	sumatiempo(tiempo,netapa); 

	var cont=$("#etaOrden").val();
	/*r arreglo= new Array($("#etaName").val(),$("#etaDesc").val(),$("#etaDuracion").val(),$("#etaOrden").val());
	var arreglo2= new Array() 
	console.log(arreglo); */
	//http://localhost/mlog/webapp/
	//http://localhost/mlog/webapp/modulos/mrp/
	//http://localhost/mlog/webapp/modulos/mrp/
	//baseUrle='http://localhost/mlog/webapp/modulos/mrp/';
	xx=window.location.href;
	if( xx.match(/formNuevo\/[0-9]{1,}/) ){
		baseUrl='../../../';
	}else{
		baseUrl='../../';
	}
	//alert(baseUrl);
	$.ajax({ 
		type: 'POST',
		url:baseUrl+'index.php/product/agregarproceso',
		data:{ netapa:netapa,
			proceso:npro,
			descripcion:$("#etaDesc"+netapa).val(),
			duracion:$("#etaDuracion"+netapa).val(),
			formato:$("#etaDuracion_h"+netapa).val(),
			orden:$("#etaOrden"+netapa).val(),
			formatoPro:procesofor
		},
		success:function(callback){
			//alert(callback);
					//var etapar=new Array($("#p_"+netapa+"_p").val(),$("d_"+netapa+"_d").val(),$("#t_"+netapa+"_t").val(),$("#o_"+netapa+"_o").val());
	//console.log(etapar);
			//console.log(callback);
			$("#listaprocesos_"+netapa).append(callback);
			$("#etaName"+netapa).val('');
			$("#etaDesc"+netapa).val('');
			$("#etaDuracion"+netapa).val('');
			$("#etaDuracion_h"+netapa).val('');
			$("#etaOrden"+netapa).val('');
		}});

}
//////////////////////////////////////////////
function Removematerial(id,nombre,baseUrl){		
	xx=window.location.href;
	
	if( xx.match(/formNuevo\/[0-9]{1,}/) ){
		urls='../../../../../';
	}else{
		urls='../../../../';
	}
	
	$("#c_"+id).remove();
	$("#p_"+id).remove();
	$("#b_"+id).remove();
	$("#u_"+id).remove();
	
	$.ajax({
		type:'POST',
		url:urls+'modulos/mrp/index.php/product/deletematerial',
		data:{
			nombre:nombre,
			id:id
		},
		success:function(resp){
			console.log('Response al remover el material: '+resp);
		}
	});
}

function ocultaDetallesProducto()
{
	if ($('#detalles').is(':checked'))
	{
		document.getElementById('detalles_div').style.visibility='visible';
	}
	else
	{
		document.getElementById('detalles_div').style.visibility='hidden';
		
	}
}

function impuestoOnOff(val)
{

	if ($('#chk_'+val).is(':checked'))
	{
		//document.getElementById("impuesto_"+val).readOnly = false;
	}
	else
	{
		document.getElementById("impuesto_"+val).readOnly = true;
	}
}

// function valorImpuesto(id){
// 	//alert($("#impuesto_"+id).val());
// 	$temp=$("#impuesto_"+id).val();

// 		//alert($("#impuesto_"+id).val());
// 		$("#impuesto_"+id).keypress(
// 							function (e)
// 								{
// 									//alert($("#impuesto_"+id).val());
// 								//alert("func");
// 									if($("#impuesto_"+id).val()>99){
// 										e.preventDefault();
// 										$("#impuesto_"+id).val($temp);
// 									}
// 								}
// 						);


// }

function compruebaInputNombre(cadena)
{
	var n = cadena.indexOf("'");
	var m = cadena.indexOf('"');
	
	if(n != -1 || m != -1)
	{
		$("#name").css("color", "#FF0000");
		$("#alerta_nombre").html("<div style='color: #FF0000'>El nombre no puede contener comillas</div>");
	}
	else
	{
		$("#name").css("color", "#000000");	
		$("#alerta_nombre").html("");
	}
}

function compruebaInputCorta()
{
	var cadena = document.getElementById("desc").value;
	var n = cadena.indexOf("'");
	var m = cadena.indexOf('"');
	
	if(n != -1 || m != -1)
	{
		$("#desc").css("color", "#FF0000");
		$("#alerta_corta").html("<div style='color: #FF0000'>La descripcion corta no puede contener comillas</div>")
	}
	else
	{
		$("#desc").css("color", "#000000");	
		$("#alerta_corta").html("")
	}
}

function compruebaInputLarga()
{
	var cadena = document.getElementById("desl").value;
	var n = cadena.indexOf("'");
	var m = cadena.indexOf('"');
	
	if(n != -1 || m != -1)
	{
		$("#desl").css("color", "#FF0000");
		$("#alerta_larga").html("<div style='color: #FF0000'>La descripcion larga no puede contener comillas</div>")
	}
	else
	{
		$("#desl").css("color", "#000000");	
		$("#alerta_larga").html("")
	}
}

function compruebaInputCenefa()
{
	var cadena = document.getElementById("descen").value;
	var n = cadena.indexOf("'");
	var m = cadena.indexOf('"');
	
	if(n != -1 || m != -1)
	{
		$("#descen").css("color", "#FF0000");
		$("#alerta_cenefa").html("<div style='color: #FF0000'>La descripcion de cenefa no puede contener comillas</div>")
	}
	else
	{
		$("#descen").css("color", "#000000");	
		$("#alerta_cenefa").html("")
	}
}
$(document).ready(function(){
	calcula_neto();


	
	$('input[name=check_imp]').click(function(){
		$("input:checkbox."+$(this).attr('class')).not($(this)).prop("checked",false);
		calcula_neto();
	}); 


});

////////////////////////////////// FUNCION CALCULA EL PRECIO NETO/////////////////////////
function calcula_neto(){
	//alert("si");
	$valor = $("#preciov").val();
	if($valor==""){$valor=0;}
	$impuestos = 0;
	$IVA=0;
	$ISR=0;
	$IEPS=0;
	$IVAR=0;
	$x = 0;
	$y = 0;
	var contador_impuestos = $("#contador_impuestos").val();
	for(var i=0; i<contador_impuestos; i++)
	{//alert("for");
if ($('#chk_'+i).is(':checked'))
		{//alert("check");
	if($("#impuesto_"+i).val()==""){$("#impuesto_"+i).val("0");}
			if($("#hideImp_"+i).val()=="IVA"){//alert("iva");
			$IVA = parseFloat($IVA) + parseFloat($("#impuesto_"+i).val());
		}
			if($("#hideImp_"+i).val()=="ISR"){//alert("isr");
			$y = 1;
			$ISR = parseFloat($ISR) + parseFloat($("#impuesto_"+i).val());
		}
			if($("#hideImp_"+i).val()=="IEPS"){//alert("ieps");
			$IEPS = parseFloat($IEPS) + parseFloat($("#impuesto_"+i).val());
		}
			if($("#hideImp_"+i).val()=="IVAR"){//alert("ieps");
			$x = 1;
			$IVAR = parseFloat($IVAR) + parseFloat($("#impuesto_"+i).val());
		}
			//$impuestos = parseFloat($impuestos) + parseFloat($("#impuesto_"+i).val());
		}
	}

	$ivarT = $valor * 0.10666677;
	$isrT = $valor * parseFloat('0.'+$ISR);
	
	$neto = (parseFloat($valor) * parseFloat(($IEPS/100)+1));// + parseFloat($valor);
	$neto = (parseFloat($neto) * parseFloat(($IVA/100)+1));// + parseFloat($neto);
	if($x==1 || $y==1){
		$neto = $neto - ($ivarT + $isrT);
	}
	

	var price = redondeo($neto);
	$("#precio_neto").val(price);
}

/*function calcula_neto(){
	//alert("si");
	$valor = $("#preciov").val();
	if($valor==""){$valor=0;}
	$impuestos = 0;
	$IVA=0;
	$ISR=0;
	$IEPS=0;
	var contador_impuestos = $("#contador_impuestos").val();
	for(var i=0; i<contador_impuestos; i++)
	{//alert("for");
		if ($('#chk_'+i).is(':checked'))
			{//alert("check");
				if($("#impuesto_"+i).val()==""){
					$("#impuesto_"+i).val("0");
				}
				if($("#hideImp_"+i).val()=="IVA"){//alert("iva");
					$IVA = parseFloat($IVA) + parseFloat($("#impuesto_"+i).val());
				}
				if($("#hideImp_"+i).val()=="ISR"){//alert("isr");
					$ISR = parseFloat($ISR) + parseFloat($("#impuesto_"+i).val());
				}
				if($("#hideImp_"+i).val()=="IEPS"){//alert("ieps");
					$IEPS = parseFloat($IEPS) + parseFloat($("#impuesto_"+i).val());
				}
			//$impuestos = parseFloat($impuestos) + parseFloat($("#impuesto_"+i).val());
			}
	}
	$impuestos = $IVA +$ISR + $IEPS; 
	$neto = (parseFloat($valor) * parseFloat(($impuestos/100)+1));// + parseFloat($valor);
	
	//$neto = (parseFloat($valor) * parseFloat(($IEPS/100)+1));// + parseFloat($valor);
	//$neto = (parseFloat($neto) * parseFloat(($IVA/100)+1));// + parseFloat($neto);
	$("#precio_neto").val($neto.toFixed(2));
}*/
////////////////////////////////// FIN DE LA FUNCION /////////////////////////

function calcula_venta(){
	//alert("si");
	$neto = $("#precioNetoCalc").val();
	if($neto==""){$neto=0;}
	$impuestos = 0;
	$IVA=0;
	$ISR=0;
	$IEPS=0;
	$contador_impuestos = $("#contador_impuestos").val();
	for(var i=0; i<$contador_impuestos; i++)
	{
		// if ($('#chkCal_'+i).is(':checked'))
		// {
		// 	if($("#hideimpuesto_"+i).val()==""){$("#hideimpuesto_"+i).val("0");}
		// 	$impuestos = parseFloat($impuestos) + parseFloat($("#hideimpuesto_"+i).val());
		// }
		if ($('#chkCal_'+i).is(':checked'))
		{
			if($("#hideimpuesto_"+i).val()==""){$("#hideimpuesto_"+i).val("0");}
			if($("#hideImpbox_"+i).val()=="IVA"){//alert("iva");
			$IVA = parseFloat($IVA) + parseFloat($("#hideimpuesto_"+i).val());
		}
			if($("#hideImpbox_"+i).val()=="ISR"){//alert("isr");
			$ISR = parseFloat($ISR) + parseFloat($("#hideimpuesto_"+i).val());
		}
			if($("#hideImpbox_"+i).val()=="IEPS"){//alert("ieps");
			$IEPS = parseFloat($IEPS) + parseFloat($("#hideimpuesto_"+i).val());
		}
			if($("#hideImpbox_"+i).val()=="ISR"){//alert("ieps");
			$ISR = parseFloat($ISR) + parseFloat($("#hideimpuesto_"+i).val());
		}
			if($("#hideImpbox_"+i).val()=="IVAR"){//alert("ieps");
			$IVAR = parseFloat($IVAR) + parseFloat($("#hideimpuesto_"+i).val());
		}
	}
}
	//alert($valor);
	$valor = parseFloat($neto) / (parseFloat(($IVA/100)+1));
	$valor = parseFloat($valor) / (parseFloat(($IEPS/100)+1));
	if($ISR!=0 && $IVAR!=0){
		
		$valor = $neto * 1.0489513;
	}
	$("#preciovCal").val($valor);
	$("#preciovCal").text($valor);
	$valor=0;
}
function redondeo(data){
	    numero = ''+data;
        if(numero.indexOf('.') == -1){
            numero = numero + '.00';
        } 
        //numero = ''+data;
        if(numero=='0' || numero==0){
            return '0.00';
        }
        var j = numero.split('.');
        var t = j[1].slice(0,2);
        if(t.length <= 1){
             t = t+'0';
        }
        return j[0]+'.'+t;
}
function formato(tiempodur){
//	alert(tiempodur);
explo=tiempodur.split(':');

if(explo[0]=='0'){anosf='';}else{if(explo[0]==1){anosf=explo[0]+' Año ';}else{anosf=explo[0]+' Años ';}	}
if(explo[1]=='0'){mesesf='';}else{if(explo[1]==1){mesesf=explo[1]+' Mes ';}else{mesesf=explo[1]+' Meses ';}	}
if(explo[2]=='0'){diasf='';}else{if(explo[2]==1){diasf=explo[2]+' Dia ';}else{diasf=explo[2]+' Dias ';}	}
if(explo[3]=='0'){horasf='';}else{if(explo[3]==1){horasf=explo[3]+' Hora ';}else{horasf=explo[3]+' Horas ';} }
if(explo[4]=='0'){minutosf='';}else{if(explo[4]==1){minutosf=explo[4]+' Minuto ';}else{minutosf=explo[4]+' Minutos ';}	}
if(explo[5]=='0'){segundosf='';}else{if(explo[5]==1){segundosf=explo[5]+' Segundo ';}else{segundosf=explo[5]+' Segundos ';}	}

if(anosf+mesesf+diasf+horasf+minutosf+segundosf==''){
	format='0 Segundos';
}else{
	format=anosf+mesesf+diasf+horasf+minutosf+segundosf;
	return format;
}


}

function unidadesCompra(type,selection) {
	return "me sali";
	var valor = $("#unidadProd").val();
	var identidicadores = $("#unidadProd option:selected").attr('identificadores');

	if(identidicadores != '') 
	{
		if(type == 1)
		{
			url = '../../../../modulos/mrp/index.php/product/unidadesCompra';
		}else
		{
			url = window.location+'/../../unidadesCompra';
		}

		$.ajax({
		//url: '../../../../modulos/mrp/index.php/product/unidadesCompra',
		url :url,
		type: 'POST',
		dataType: 'json',
		data: {
			ids: identidicadores
		},
		success : function(data){
			if(data.status)
			{
				$('#cboUCompra').empty();
				$('#cboUVenta').empty();
				$.each(data.rows, function(index, val) {

					//Combo de Compra
					var optionCompra = $(document.createElement('option')).attr({'value':val.idUni,'conversion':val.conversion,'orden':val.orden}).html(val.compuesto).appendTo($('#cboUCompra'));

					 //Combo de Venta
					 var optionVenta = $(document.createElement('option')).attr({'value':val.idUni,'conversion':val.conversion,'orden':val.orden}).html(val.compuesto).appendTo($('#cboUVenta'));
					});

				if(type == 1)
				{
					$('#cboUCompra [orden=1]').attr({'selected':'selected'});
					$('#cboUVenta [orden=1]').attr({'selected':'selected'});
				}else
				{
					selection = selection.split(',');
					if(identidicadores.indexOf(selection[0]) != -1)
					{
						$('#cboUCompra').val(selection[0]);
						$('#cboUVenta').val(selection[1]);
					}else
					{
						$('#cboUCompra [orden=1]').attr({'selected':'selected'});
						$('#cboUVenta [orden=1]').attr({'selected':'selected'});
					}
				}
			}else
			{
				alert(data.msg);
			}
		},
	//	error :function(data){
	//		alert('A ocurrido un error al consultar las unidades de compra.');
	//	}
	});
}else
{
	alert ("entre aqui..");
	$('#cboUCompra').empty();
	$('#cboUVenta').empty();
	var optionCompra = $(document.createElement('option')).attr({'value':1,'conversion':1,'orden':1}).html('Sin unidades').appendTo($('#cboUCompra'));
	var optionVenta = $(document.createElement('option')).attr({'value':1,'conversion':1,'orden':1}).html('Sin unidades').appendTo($('#cboUVenta'));
}

}

function conversion(){

	var valor = $('#inicial').val();
	var compra = $('#cboUCompra').val();	
	var venta = $('#cboUVenta').val();
	var conversion = 0;
	

	if(valor != 0){

		var conversionCompra = $('#cboUCompra'+' option:selected').attr('conversion');
		var ordenCompra = $('#cboUCompra'+' option:selected').attr('orden');

		var conversionVenta = $('#cboUVenta'+' option:selected').attr('conversion');
		var ordenVenta = $('#cboUVenta'+' option:selected').attr('orden');
		var unidad = $('#cboUVenta'+' option:selected').text();


		var conversionCompraOperacion = (valor * conversionCompra);
		var conversionVentaOperacion = (conversionCompraOperacion / conversionVenta);


		//alert('compra ->' +conversionCompraOperacion);

		if(ordenCompra <= ordenVenta)
		{
			console.log("1: "+conversionCompra +"*"+valor+'/'+ conversionVenta);
			conversion = parseFloat(conversionCompra * valor / conversionVenta);
			//alert( 'Venta -> '+parseFloat(conversionCompra / conversionVenta)+' '+unidad);
		}else
		{
			if(ordenVenta == 1)
			{
				console.log("2: "+valor +'*'+ conversionCompra);
				conversion = parseFloat(conversionCompraOperacion);
				//alert( 'Venta -> '+parseFloat(conversionCompraOperacion)+' '+unidad);
			}else
			{
				console.log("3: "+(conversionCompraOperacion + '/' + conversionVenta));
				conversion = parseFloat(conversionVentaOperacion);
				//alert( 'Venta -> '+parseFloat(conversionVentaOperacion)+' '+unidad);
			}
		}

		//alert(conversion+' '+unidad);

		$('#lblConversion').text(conversion+' '+unidad);
		$('#hdConversion').val(conversion);

		//alert(conversionCompra+' -- '+conversionVenta);
	}
}

var cont = 0;
function agregaProve(){
 var id=1;
	//alert('Por el momento no se encuentea disponible esta funcion');
	//$('#proveedor_div').show();

	
	/*$('#proveedor_div').clone().attr('id','provedor'+cont).appendTo("#listaProvedores");
	$('#provedor'+cont).val('0');
	$('#provedor'+cont).append('<div style="float:left; margin-top:22px; margin-left:-60px"><input type="button" value="x" onclick="eliminarProve(provedor'+cont+');"><div>')
	*/
	//Adiciona a listaProvedores el div que contiene
	$("#listaProvedores").append('<div  title="Proveedor" class="contenedor_div row">'
									+'<section id="provedor'+cont+'"  title="Proveedor">'
										+'<div title="Proveedor" class="col-md-6" >'
											+'<label>Proveedor: </label>'
											+'<br>'
											+$('#proveedores_select_hidden').html()
										+'</div>'
										+'<div title="Costo" class="col-md-6" id="proveedor_costo"">'
											+'<label>Costo proveedor: </label>'
											+'<input type="button" value="x" onclick="eliminarProve(provedor'+cont+');">'
											+'<input type="text" style="width: 50%;" class="float form-control costo_prov costo_proveedor_added" name="costo_proveedor" class="costo_proveedor" value="" required>'
										+'</div>'
									+'</section>'
								+'</div>');
	$('.proveedor_added').change(function(){
		var id_proveedor_added = $(this).attr('id');
		//console.log(id_proveedor_added);
	//	var valor_seleccionado = $('#provedor'+cont).val();
		if($(this).val() != ''){ //valida que no sea la opción vacía la que esté seleccionada
			option = $(this).find('option:selected').html();// Obtiene el texto del option seleccionado en el select
			//var next_input = $(this).find('input:text').attr('class','costo_proveedor_added').val();
			//console.log(next_input);
			//if($('.costo_proveedor_added').val() == ''){
					//console.log(option); Nombre del proveedor added seleccionado
					//var input_costo = $(this).find('input:text').attr('class','costo_proveedor_added');
					//console.log(input_costo);
				//console.log($('#'+id_proveedor_added).val());
		//	}

		}
	//	console.log(id_proveedor_added);
		
	});
		
	$("#provedor"+cont).find('select').val(""); //Encuentra el select que tenga el id mencionado y le coloca opcion posición '0'
	//$('#provedor'+cont).val('0');
	//$('#proveedor_div').hide();
//	alert($("#costo_proveedor"+cont-1).find('input[type=text]').val());

	cont++;
			//'application/controlers/product/proveedores'
/*	$.ajax({
		//url: '../../../../modulos/mrp/index.php/product/unidadesCompra',
		url : url,
		type: 'POST',
		dataType: 'json',
		data: {idproducto:idproducto},
		success : function(select_prv){
			alert(select_prv);
	/*	$('#listaProvedores').append('<div style="width: 55%; float:left;" title="Proveedor">\
			<div id="prv">'+select_prv+'</div>\
		</div>\
		<div style="width: 24%; float:left;" title="Costo">\
			<div>\
				<div style="display: table-cell;">\
				$\
				</div>\
				<div style="display: table-cell;">\
										<input value="" type="text" id="costo_proveedor" name="Costo proveedor" class="float nminputtext" style="width: 50%;">\
				</div>\
			</div>\
		</div>');
		}
	} */



}

function eliminarProve(divs){
	$(divs).remove();
}

function eliminarProve2(idPrv){
	//alert(idPrv);

	var	idproducto=$('#id').val();
	var confirmacion = confirm('¿Deseas eliminar al proveedor?');
	if (confirmacion == true){
		$('#'+idPrv).remove();
			xx=window.location.href;
			if( xx.match(/formNuevo\/[0-9]{1,}/) ){
				baseUrl='../../../';
			}else{
				baseUrl='../../';
			}
			url=baseUrl+'index.php/product/eliminaProve2';

			$.ajax({
				//url: '../../../../modulos/mrp/index.php/product/unidadesCompra',
				url : url,
				type: 'POST',
				data: {idproducto:idproducto,
					   idPrv:idPrv
					  },
				success : function(){
						alert('elimino');
				},
			});	
	}
	//alert(idproducto);
}
var cont2 = 0;
function agregaPrecios(){
	//alert('Agrega Precio');
	if($('#precionuevo_'+(cont2-1)).val()=='' || $('#desc_precionuevo_'+(cont2-1)).val()==''){
		return;
	}

	$('#preciosnuevos').append('' +
		'<div id="precio_'+cont2+'" class="row">' +
			'<div class="col-md-3">' +
				'<label>Descripcion: </label>' +
				'<input type="text" name="descripcion" class="form-control descri" id="desc_precionuevo_'+cont2+'">' +
			'</div>'+
			'<div class="col-md-3">' +
				'<label> Precio: $ </label>'+
				'<input type="text" name="precio" class="form-control numero preci" id="precionuevo_'+cont2+'">'+
			'</div>'+
			'<div class="col-md-3">' +
				'<label> Sujeto a descuento </label>' +
				'<select name="descuento" id="select_nuevo_'+cont2+'" class="form-control">' +
					'<option value="0">No</option>' +
					'<option value="1">Si</option>' +
				'</select>' +
			'</div>'+
			'<div class="col-md-3">' +
				'<input type="button" value="x" onclick="eliminaPrecio('+cont2+');" class="btn col-md-2 btnMenu delete">' +
			'</div>'+
		'</div>'+
	'');

	cont2++;

}

function eliminaPrecio(id){
	//alert(id);
	$('#precio_'+id).remove();
	cont2--;
}

function eliminaPrecio2(id){

	var confirmacion = confirm('¿Deseas eliminar el precio de la lista?');
	if (confirmacion == true){
		//$('#'+idPrv).remove();
			xx=window.location.href;
			if( xx.match(/formNuevo\/[0-9]{1,}/) ){
				baseUrl='../../../';
			}else{
				baseUrl='../../';
			}
			url=baseUrl+'index.php/product/eliminaPrecio2';

			$.ajax({
				//url: '../../../../modulos/mrp/index.php/product/unidadesCompra',
				url : url,
				type: 'POST',
				data: {id:id,
					
					  },
				success : function(){
						$('#precio_'+id).remove();
						alert('Ha sido eliminado');
				},
			});	
	}
}
function modificaPrecio(id){
	//alert(id);
	$('#desc_precionuevo_'+id).attr('readonly', false);
	$('#price_'+id).attr('readonly', false);
	$('.delete').hide();
	$('.edit').hide();
	$('#save_'+id).show();
	$('#descuento_'+id).hide();
	$('#select_descuento_'+id).show();
	$('#agregapre').hide();
	$('#send').hide();

}
function cambiaprecio(id){
	//alert(id);
	var descripcion = $('#desc_precionuevo_'+id).val();
	var precio = $('#price_'+id).val();
	var descuento = $('#select_descuento_'+id).val();	

	if(precio=='' || precio < 0){
		alert('No puedes tener un precio menor o igual a 0.');
		return;
	}
		if(descripcion==''){
		alert('No puedes dejar la descripcion vacia.');
		return;
	}

			xx=window.location.href;
			if( xx.match(/formNuevo\/[0-9]{1,}/) ){
				baseUrl='../../../';
			}else{
				baseUrl='../../';
			}
			url=baseUrl+'index.php/product/cambiaprecio';


			$.ajax({
				//url: '../../../../modulos/mrp/index.php/product/unidadesCompra',
				url : url,
				type: 'POST',
				data: {id:id,
					descripcion:descripcion,
					precio:precio,
					descuento:descuento,
					
					  },
				success : function(){
					if(descuento==0){ 
						x='No';
					}else{
						x='Si';
					} 
					$('#price_'+id).val(precio);
					$('#descuento_'+id).val(x);
					$('#price_'+id).attr('readonly', true);
					$('.delete').show();
					$('.edit').show();
					$('#save_'+id).hide();
					$('#descuento_'+id).show();
					$('#select_descuento_'+id).hide();
					$('#agregapre').show();
					$('#send').show();
					$('#desc_precionuevo_'+id).val(descripcion);
					$('#desc_precionuevo_'+id).attr('readonly', true);
						alert('Ha sido modificado.');
				},
			});	



}

function updatePrecio(id){

	//alert(id);

	var precio=$('#price_'+id).val();
	var descuento=$('#select_descuento_'+id).val();
	var descripcion=$('#desc_precionuevo_'+id).val();
	//alert(descripcion);
	if(precio=='' || precio < 0){
		alert('No puedes tener un precio menor o igual a 0.');
		return;
	}
			xx=window.location.href;
			if( xx.match(/formNuevo\/[0-9]{1,}/) ){
				baseUrl='../../../';
			}else{
				baseUrl='../../';
			}
			url=baseUrl+'index.php/product/updatePrecio';

			$.ajax({
				//url: '../../../../modulos/mrp/index.php/product/unidadesCompra',
				url : url,
				type: 'POST',
				data: {id:id,
					   descuento:descuento,
					   precio:precio,			
					   descripcion:descripcion,
					  },
				success : function(){


				/*	if(descuento==0){ 
						x='No';
					}else{
						x='Si';
					} 
					$('#price_'+id).val(precio);
					$('#descuento_'+id).val(x);
					$('#price_'+id).attr('readonly', true);
					$('.delete').show();
					$('.edit').show();
					$('#save_'+id).hide();
					$('#descuento_'+id).show();
					$('#select_descuento_'+id).hide();
					$('#agregapre').show();
					$('#send').show();
					$('#desc_precionuevo_'+id).val(descripcion);
					$('#desc_precionuevo_'+id).attr('readonly', true);
					*/
					alert('se modifico');
				},
			});	
}

///////////////// ******** ---- 		select_buscador		------ ************ //////////////////

	//////// Cambia los select por select con buscador.
		// Recibe un array con los id de los select
		
function select_buscador ($objeto) {
// Recorre el arreglo y establece las propiedades del buscador
	$.each( $objeto, function( key, value ) {
		$("#"+value).select2({
			width : "500px"
		});
	});
}
///////////////// ******** ---- 		FIN select_buscador		------ ************ //////////////////


///////////////// ******** ---- 		buscar_unidad		------ ************ //////////////////

	//////// Busca la unidad del producto y la establece en el combo de unidad
		// Como parametros puede recibir:
			// id-> id del material
			
function buscar_unidad($objeto) {
	xx=window.location.href;
	
	if(xx.match(/formNuevo\/[0-9]{1,}/) ) {
		baseUrl='../../../';
	}else {
		baseUrl='../../';
	}
	
	url=baseUrl+'index.php/product/buscar_unidad';
	
	$.ajax({
		url: url,
		type: 'POST',
		data: $objeto,
		dataType: 'json',
		success: function(response){
			$material=response[0];
			$('#select_unidad').html('<option value="'+$material.idUni+'" selected="selected">'+$material.compuesto+'</option>');
			// console.log($material);
		
	//** Refrescamos el select para que el usuario visualice el cambio
			$objeto=[];
				
		// Creamos un arreglo con los id de los select
			$objeto[0]='select_unidad';
					
		// Mandamos llamar la funcion que crea el buscador
			select_buscador($objeto);
		}
	});
}
///////////////// ******** ---- 		FIN buscar_unidad		------ ************ //////////////////


///////////////// ******** ---- 		agregar_material		------ ************ //////////////////

	//////// Agrega un material a un array de sesion.
		// Como parametros puede recibir:
			// id-> id del material
			// cantidad-> cantidad de materiales necesarios
			// unidad-> gramo, kilo, tonelada, unidada, area, etc.
			// tipo-> normal, opcional o extra
			
function agregar_material($objeto) {
	// console.log($objeto);
// Validaciones
	if($objeto['id'].length==0){
		alert('Debes seleccionar un material');
		return 0;
	}
	
	if($objeto['cantidad']<=0){
		alert('Debes ingresar una cantidad valida');
		return 0;
	}
	
	if($objeto['unidad'].length==0){
		alert('Debes seleccionar una unidad');
		return 0;
	}
// FIN Validaciones

// Validamos en que localizacion se encuentra para saber a que url mandarlo
	xx=window.location.href;
	
	if(xx.match(/formNuevo\/[0-9]{1,}/) ) {
		baseUrl='../../../';
	}else {
		baseUrl='../../';
	}
	
	url=baseUrl+'index.php/product/agregar_material';
	
	$.ajax({
		url: url,
		type: 'POST',
		data: $objeto,
		dataType : 'json',
		success: function(response){
			$total_temp=0;
			console.log(response);
			
		// Creamos la estructura de las filas de la tabla
			$tabla='<tr>';
			$tabla+='	<td><strong>Cantidad</strong></td>';
			$tabla+='	<td><strong>Material</strong></td>';
			$tabla+='	<td><strong>Unidad</strong></td>';
			$tabla+='	<td><strong>Tipo</strong></td>';
			$tabla+='	<td><strong>Costo</strong></td>';
			$tabla+='	<td align="center"><span class="glyphicon glyphicon-trash"></span></td>';
			$tabla+='</tr>';
			
		// Recorre el array cono registrosy agrega una fila con los datos del producto
			$.each(response, function(key, value){
				if(value.tipo==0)
					tipo='Normal';
					
				if(value.tipo==1)
					tipo='Opcional';
					
				if(value.tipo==2)
					tipo='Extra';
				
				$tabla+='<tr id="tr_'+key+'">';
				$tabla+='	<td>'+value.cantidad+'</td>';
				$tabla+='	<td>'+value.material+'</td>';
				$tabla+='	<td>'+value.unidad+'</td>';
				$tabla+='	<td>'+tipo+'</td>';
				$tabla+='	<td>'+value.costo+'</td>';
				$tabla+='	<td align="center" onclick="eliminar_material({id:\''+key+'\'})"><a href="#"><span class="glyphicon glyphicon-trash"></span></a></td>';
				$tabla+='</tr>';

				$total_temp+=parseFloat(value.costo);
			});
			
			
			$total=parseFloat($total_temp);
			// $total=$total.toFixed(2);
			
			$tabla+='<tr>';
			$tabla+='	<td colspan="4" align="right"><strong>Total: $</strong></td>';
			$tabla+='	<td><strong id="total_agregados">'+$total+'</strong></td>';
			$tabla+='</tr>';
			
		// Dibujamos las filas en la tabla
			$('#tabla_agregados').html($tabla);
		
		// Mostramos la div del margen de ganancia
			$('#div_margen_ganancia').show();
			
		// Limpiamos los select y cantidad
			$('#cantidad').val(0);
			$('#select_material').val('');
			$('#select_unidad').val('');
			$('#select_unidad').html('<option value="" selected="selected">--Unidad--</option>');
			$('#select_tipo').val(0);
			
			$objeto=[];
				
		// Creamos un arreglo con los id de los select
			$objeto[0]='select_material';
			$objeto[1]='select_unidad';
			$objeto[2]='select_tipo';
			
		// Mandamos llamar la funcion que crea el buscador
			select_buscador($objeto);
		}
	});
}
///////////////// ******** ---- 	FIN	agregar_material		------ ************ //////////////////


///////////////// ******** ---- 		filtrar_tipo_producto		------ ************ //////////////////

	//////// Filtra el listado de materiales segun el tipo de producto.
		// Como parametros puede recibir:
			// tipo-> tipo de producto seleccionado:
				// producto(1), producir producto(2), material de produccion(3), kit de productos(4)
				// producto de consumo(5), servicios(6)
				
function filtrar_tipo_producto($objeto) {
	console.log($objeto);
// Si el tipo es "producir producto"(2) o "kit de producto"(4) muestra el margen de ganancia
	if($objeto['tipo_producto']==2 || $objeto['tipo_producto']==4){
	// Muestra la div oculta de margen de ganancia
		$("#margen_ganancia").show('slow');
	}else{
		$("#margen_ganancia").hide('slow');
	}
	
// Validamos en que localizacion se encuentra para saber a que url mandarlo
	xx=window.location.href;
	
	if(xx.match(/formNuevo\/[0-9]{1,}/) ) {
		baseUrl='../../../';
	}else {
		baseUrl='../../';
	}
	
	url=baseUrl+'index.php/product/listar_materiales';

// Consulta los materiales segun el tipo de producto y los agrega al select de materiales
	$.ajax({
		url: url,
		type: 'POST',
		data: $objeto,
		dataType : 'json',
		success: function(response){
			
		// Vacia el select para llenarlo con los nuevos registros
			$('#select_material').html('');
			$('#select_material').append('<option value="">--Material--</option>');
		
		// Recorre el array cono registros y los va agregando al select
			$.each(response, function( key, value){
				$('#select_material').append('<option value="'+value.idProducto+'">'+value.nombre+'</option>');
			});
		}
	});
}

///////////////// ******** ---- 		FIN filtrar_tipo_producto		------ ************ //////////////////


///////////////// ******** ---- 		eliminar_material		------ ************ //////////////////

	//////// Elimina un material del array de sesion y de la tabla de materiales agregados
	//////// Actualiza el total para el margen de ganancia
		// Como parametros puede recibir:
			// id-> id identificador del registro (0, 1, 2, 3...)
			
function eliminar_material($objeto) {
	xx=window.location.href;
	
	if(xx.match(/formNuevo\/[0-9]{1,}/) ) {
		baseUrl='../../../';
	}else {
		baseUrl='../../';
	}
	
	url=baseUrl+'index.php/product/eliminar_material';
	
	$.ajax({
		url: url,
		type: 'POST',
		data: $objeto,
		dataType: 'json',
		success: function(response){
			console.log(response);
		
		// Si se elimina el registro de la secion lo elimina de la tabla y actualiza el total
			if(response['mensaje']==1){
			// Elimina de la tabla la fila del registro
				$('#tr_'+$objeto.id).remove();
				
				$total=parseFloat($('#total_agregados').html());
				
				console.log($total);
				
			// Calcula el nuevo total
				$total-=parseFloat(response['restar']);
				$total=parseFloat($total);
				
			// Actualiza el total
				$('#total_agregados').html($total);
			
			// Calcula el numero de registros de la tabla
				tr=$('#tabla_agregados >tbody >tr').length;
			
			// Si el numero es menor a dos significa que solo se encuentra
			// el <tr> de cabecera y el de total, por lo tanto no hay registros de materiales
			// Por lo tanto igualamos el total a 0
				if (tr <=2){
				   $total=0;
				}
				
			// Si el total es menor a 0(no hay elemnetos en la tabla), oculta la tabla y el margen de ganancia
				if($total<=0){
				// Eliminamos la cabecera de la tabla
					$('#tabla_agregados').html('');
				
				// Ocultamos la div del margen de ganancia
					$('#div_margen_ganancia').hide();
					
				// Ocultamos la div del margen de ganancia
					$('#div_margen_venta').hide();
				}
		// Si no se elimina el registro de la secion manda un mensaje de error
			}else{
				alert(response['mensaje']);
			}
		}
	});
}
///////////////// ******** ---- 		FIN eliminar_material		------ ************ //////////////////
