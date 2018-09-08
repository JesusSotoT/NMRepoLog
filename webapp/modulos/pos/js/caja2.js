var caja = {

// Inicializamos el array con los datos de la venta 
	info_venta : {
		"venta" : {},
		"ajustes": {},
		"propinas": [],
		'comanda': ''
	},
	
    //salir : 0,
    currentRequest: null,
    currentRequestP: null,
    meses: new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"),
    diasSemana: new Array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado"),
    data: new Array(),
    init: function()
    {   
        
        $('#search-producto').trigger('click');
        //caja.printTime();
        $('#frameComprobante').attr({'src': ''});
        $('#descuentoGeneral').val('');
        caja.autocomplete();
        //caja.autocomplete2();
        $.ajax({
            url: 'ajax.php?c=caja&f=pintaRegistros',
            type: 'GET',
            dataType: 'json',
            success: function(data) {

                $('#search-producto').focus();

                if (data.estatus)
                {
                    caja.pintaResultados(data, false);
                    //alert(data.descGeneral);
                }   

                if (data.suspendidas != '')
                {
                    $('#divSuspendidas').css({'display': 'block'});
                    $.each(data.suspendidas, function(key, value) {
                        var option = $(document.createElement('option')).attr({'value': value.id}).html(value.identi).appendTo($('#s_cliente'));
                    });
                }

                caja.inicioCaja(data);

            }
        });
    },autocomplete: function()
    {
        var clientes = $('#cliente-caja').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'id',
            displayKey: 'nombre',
            source: function(query, process) {
                if ($('#cliente-caja').val() != '')
                {
                    caja.currentRequest = $.ajax({
                        url: 'ajax.php?c=caja&f=buscaClientes',
                        type: 'GET',
                        dataType: 'json',
                        data: {term: query},
                        beforeSend: function() {
                            if (caja.currentRequest != null) {
                                caja.currentRequest.abort();
                            }
                            $('#cliente-caja').addClass('loader');
                        },
                        success: function(data) {
                            $('#cliente-caja').removeClass('loader');
                            return process(data);
                        },
                        error: function(data)
                        {
                            $('#cliente-caja').removeClass('loader');
                        }
                    });
                } else
                {
                    $('#cliente-caja').removeClass('loader');
                    if (caja.currentRequest != null) {
                        caja.currentRequest.abort();
                    }
                }
            }
        }).on('typeahead:selected', function(event, data) {
            /*if($('#hidencliente-caja').val()!=data.id && $('#totalDeProductosInput').val() > 0){
                alert('Estas cambiado al cliente, Tienes que borrar los productos');
               return false;
            } */

            $('#hidencliente-caja').val(data.id);
            caja.checatimbres(data.id);
        });


        var vendedores = $('#vendedor-caja').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'id',
            displayKey: 'nombre',
            source: function(query, process) {
                if ($('#vendedor-caja').val() != '')
                {
                    caja.currentRequest = $.ajax({
                        url: 'ajax.php?c=caja&f=buscaVendedores',
                        type: 'GET',
                        dataType: 'json',
                        data: {term: query},
                        beforeSend: function() {
                            if (caja.currentRequest != null) {
                                caja.currentRequest.abort();
                            }
                            $('#vendedor-caja').addClass('loader');
                        },
                        success: function(data) {
                            $('#vendedor-caja').removeClass('loader');
                            return process(data);
                        },
                        error: function(data)
                        {
                            $('#vendedor-caja').removeClass('loader');
                        }
                    });
                } else
                {
                    $('#vendedor-caja').removeClass('loader');
                    if (caja.currentRequest != null) {
                        caja.currentRequest.abort();
                    }
                }
            }
        }).on('typeahead:selected', function(event, data) {
            $('#hidenvendedor-caja').val( data.idempleado );
        });


        var productos = $('#search-producto').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'id',
            displayKey: 'label',
            source: function(query, process) {

                if ($('#search-producto').val() != '')
                {
                    caja.currentRequestP = $.ajax({
                        url: 'ajax.php?c=caja&f=buscaProductos',
                        type: 'GET',
                        dataType: 'json',
                        data: {term: query},
                        beforeSend: function() {
                            if (caja.currentRequestP != null) {
                                caja.currentRequestP.abort();
                            }
                            $('#search-producto').addClass('loader');
                        },
                        success: function(data) {

                            var result = false;

                            $('#search-producto').removeClass('loader');
                            if (result == false)
                            {
                                return process(data);
                            }
                        },
                        error: function(data)
                        {
                            $('#search-producto').removeClass('loader');
                        }
                    });
                } else
                {
                    $('#search-producto').removeClass('loader');
                    if (caja.currentRequestP != null) {
                        caja.currentRequestP.abort();
                    }
                }
                }
                }).on('typeahead:selected', function(event, data) {

                    $('#search-producto').val('').typeahead('clearHint');
                    //alert('entro en autocomplete');
                    caja.buscaCaracteristicas(data.id);
                    //caja.agregaProducto(data.id,'');
                });
},
autocomplete2: function()
    {
         
        var productos = $('#search-producto').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'id',
            displayKey: 'label',
            source: function(query, process) {

                if ($('#search-producto').val() != '')
                {
                    caja.currentRequestP = $.ajax({
                        url: 'ajax.php?c=caja&f=buscaProductos',
                        type: 'GET',
                        dataType: 'json',
                        data: {term: query},
                        beforeSend: function() {
                            if (caja.currentRequestP != null) {
                                caja.currentRequestP.abort();
                            }
                            $('#search-producto').addClass('loader');
                        },
                        success: function(data) {

                            var result = false;

                            $('#search-producto').removeClass('loader');
                            if (result == false)
                            {
                                return process(data);
                            }
                        },
                        error: function(data)
                        {
                            $('#search-producto').removeClass('loader');
                        }
                    });
} else
{
    $('#search-producto').removeClass('loader');
    if (caja.currentRequestP != null) {
        caja.currentRequestP.abort();
    }
}
}
}).on('typeahead:selected', function(event, data) {

    $('#search-producto').val('').typeahead('clearHint');
    //alert('entro en autocomplete');
    caja.buscaCaracteristicas(data.id);
    //caja.agregaProducto(data.id,'');
});
},
caracteristicasAutomaticas: "",
busquedaXcodigo: function(e)
    {

        if (window.event)
            keyCode = window.event.keyCode;
        else if (e)
            keyCode = e.which;
        var producto = ($('#search-producto').val()).split(" ") ;
        var myRe = /((\d)*H(\d)*(P)?)+/;
        if(producto.length == 2 && myRe.exec(producto[1]) ){
            caja.caracteristicasAutomaticas = (myRe.exec (producto[1] )) ? producto[1] : "";
        }
        else {
            producto[0] = $('#search-producto').val();
        }
        if (keyCode == 13 && producto[0] != '')
        {   
            //alert('entro por busquedaXcodigo');
            caja.buscaCaracteristicas(producto[0]);
            //caja.agregaProducto(producto,'');
        }
    },
    agregaProducto : function(id,caracteristicas,series,lotes,print){
		console.log('------> objeto agregar producto');
        console.log(id);
var cantidad = 0;
        if($('#series').val() != null && $('#series').val().length != 0 )
             cantidad = $('#series').val().length;
        else if($('#lotes').val() != null && $('#lotes').val().length != 0 ){
             cantidad = 0;
            $.each($('.divlotes input'), function(index, val) {
                cantidad += parseFloat($(val).val());
            });
        }
        else
             cantidad = $('#cantidad-producto').val();

        if(cantidad <= 0 || cantidad == ''){
            alert('La cantidad debe ser mayor a cero.');
            $('#modalMensajes').modal('hide');
            $('#cantidad-producto').val(1);
            return false;
        }

    
        caja.mensaje('Procesando...');
            var str = id;
            var res = str.substr(0, 3);

        $.ajax({
            url: 'ajax.php?c=caja&f=agregaProducto',
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                cantidad: cantidad,
                caracter : caracteristicas,
                cliente : $('#hidencliente-caja').val(),
                series : series,
                lotes : lotes,

            },
            beforeSend: function() {
                if (caja.currentRequestP != null) {
                    caja.currentRequestP.abort();
                }
                $('#search-producto').addClass('loader');

            },
            success: function(data){
                caja.caracteristicasAutomaticas = "";
				console.log('------> success agregar producto');
            	console.log(data);

                if(data.idPedido=='Vendido'){
                    caja.eliminaMensaje();
                    alert('Esta venta ya fue realizada');
                    return false;
                }
            
            // Guarda los datos de la venta en un array
            	caja.info_venta['venta'] = data;
            	
              	$('#idPedido').val(data.idPedido);
                $('#listaDePreciosClient').val(data.listaDePrecios);
			// La comanda ya fue pagada
				if(data['status']==3){
					alert("La comanda "+data['comanda']+" ya fue pagada. ID de la venta: "+data['id_venta']);
                	$('#search-producto').val('').typeahead('clearHint').focus();
				}
				
			// Comanda sin productos
				if(data['status']==4){
					alert("Esta comanda no tiene productos para cobrar");
					$('#search-producto').val('').typeahead('clearHint').focus();
				}
				
				caja.eliminaMensaje();
                if(data.estatus==false){
                    alert("El producto no existe");
                    $('#cantidad-producto').val('1');
                    $('#search-producto').val('').typeahead('clearHint').focus();
                    return false;
                }
                if(data.estatus==1000){
                    alert("El producto no cuenta con existencias.");
                    $('#cantidad-producto').val('1');
                    $('#search-producto').val('').typeahead('clearHint').focus();
                    return false;
                }
                //caja.eliminaMensaje();
                caja.pintaResultados(data);
                $('#cantidad-producto').val('1');
                $('#search-producto').val('').typeahead('clearHint').focus();
                if(res == 'PMP'){
                    window.location.reload();
                }
                
            // Si es comanda abre la modal para pagar la Comanda
            	if(data['comanda']){
            		caja.info_venta['comanda'] = data['comanda'];
                    if(print != 1){
                        $('#btn_pagar').click();
                    }else{
                        caja.preticket();
                    }
            		
            	}
            },
            error: function(data){
				console.log('------> error agregar producto');
            	console.log(data);
            	
                $('#series').val(null);
                $('#lotes').val(null);
                $('#cantidad-producto').val('1');
                caja.eliminaMensaje();
                $('#search-producto').removeClass('loader');
            }
        });
        
    },
    preticket: function(data){
        $.ajax({
            url: 'ajax.php?c=caja&f=escomanda',   
            dataType: 'html',
            async: false,                             
            success: function(resp){ 

                if(resp == 0){
                    alert('No es Comanda');
                    return false;
                }else{
                    // Ejecuta los scripts de la comanda
                        $("#div_ejecutar_scripts").html(resp);
                    //abrimos una ventana vacÃ­a nueva
                        var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=8px,rightmargin=8px');

                    $(ventana).ready(function() {

                        // Cargamos la vista ala nueva ventana
                        ventana.document.write(resp);
                        // Cerramos el documento
                        ventana.document.close();

                        setTimeout(closew, 3000);

                        function closew() {
                            ventana.print();
                            
                            ventana.close();

                            caja.cancelarCaja();

                        };
                    });
                }
                
            }
        });
    },
    pintaResultados: function(data){
        console.log(data);

        if(data.estatus==true){
            caja.data = data;
            caja.eliminaMensaje();
            var subtotal = 0.00;
            var impuestosVal = 0.00;
            var total = 0.00;
            var idProdCar = '';
            var importeX = 0;
            var precioX = 0;
            var totalProductosCiclo = 0;
            var configDescuentos = $('#configDescuentos').val();
            var onclickDes = '';
            var preciosPermiso = $('#modificaPrecios').val();
            var permiso = '';
            var totalPuntos = 0;
            var colorSat = '';
            var idProdCar2='';
            $('.filas').empty();
            var perfil= '';
            if($('#idPerfilUser').val()!='(2)'){
                perfil = '';
            } 
            console.log(data.productos);
            $.each(data.productos, function(index, val) {
                index = index.replace("+", "");
                if(index != 'cargos' && val.idProducto !='null' && index!='descGeneral' && index!='pedido' ){
                    if (val.idProducto == 0) {
                        idProdCar = 'prom-'+val.tipin;
                        idProdCar2 = 'prom-'+val.tipin;
                    } else if(val.caracteristicas!=''){
                        idProdCar = val.idProducto+'_'+val.caracteristicas;
                        idProdCar2 = val.idProducto+'_'+val.caracteristicas.split('=>').join('H').split('*').join('P')
                    } else {
                        idProdCar = val.idProducto;
                        idProdCar2 = val.idProducto;
                    }
                    importeX = parseFloat(val.importe);
                    precioX = parseFloat(val.precio);

                    if(configDescuentos!=1){
                        onclickDes = 'onclick="caja.descuentoParcial(\''+idProdCar+'\');"';
                    }else{
                        onclickDes = '';
                    }

                    if(preciosPermiso==0){
                        permiso = 'readonly';
                    }else{
                        permiso = '';
                    }
                    if($('#versionFacturacionHide').val()=='3.3'){
                        if(val.claveSat==null){
                            colorSat = ' bg-warning';
                            alert('El producto '+val.nombre+' no cuenta con una clave del SAT asignada, esto provocaria un error al facturar.');
                        }else{
                            colorSat = '';
                        }
                    }
                    
                    $('#productsTable2 tr:last').after(`
                    <tr class="filas" id="filaPro_${idProdCar}" prodCar="filaPro_${idProdCar}">
                        <td><input type="text" id="cant_${val.idProducto}" value="${val.cantidad}" onblur="caja.recalcula(\'cantidad\' , \'${idProdCar}\', 2);" style="width:100%" class="form-control numeros" cant="${idProdCar}" cant2="${idProdCar2}"></td>
                        <td> ${val.unidad}</td>
                        <td ${onclickDes}>${val.nombre}</td>
                        <td><input type="hidden" id="precio_${val.idProducto}" value="${precioX}" class="form-control span1 numeros" onblur="caja.recalcula(\'precio\' , \'${idProdCar}\', 2);" style="width:100%" precioReal="${idProdCar}" ${perfil}><input type="text" id="precio_${val.idProducto}" value="${precioX.toFixed(2)}" class="form-control span1 numeros" onblur="caja.recalcula(\'precio\' , \'${idProdCar}\', 2);" style="width:100%" precio="${idProdCar}" ${perfil}></td>
                        <td align="right"> $ ${ (val.suma_impuestos == "") ? 0 : val.suma_impuestos } </td>
                        <td align="right"> $ ${ (val.descuento == null) ? 0 : val.descuento }  </td>
                        <td align="right">$ ${ importeX.toFixed(2) }</td>
                        <td align="left"><span class="glyphicon glyphicon-trash" onclick="caja.eliminarProducto(\'${idProdCar}\');"></span></td>
                    </tr>
                    `);
                    
                    /*$('#productsTable1 tr:last').after('<tr class="filas" id="filaPro_'+idProdCar+'" prodCar="filaPro_'+idProdCar+'">'+
                                    '<td><input type="text" id="cant_'+val.idProducto+'" value="'+val.cantidad+'" onblur="caja.recalcula(\'cantidad\' , \''+idProdCar+'\', 1);" style="width:100%" class="form-control input-sm numeros" cant="'+idProdCar+'"></td>'+
                                    '<td '+onclickDes +'>'+val.nombre+'</td>'+
                                    '<td><input type="text" id="precio_'+val.idProducto+'" value="'+precioX.toFixed(2)+'" class="form-control input-sm numeros" onblur="caja.recalcula(\'precio\' , \''+idProdCar+'\', 1);" style="width:100%" precio="'+idProdCar+'" '+perfil+'></td>'+
                                    '<td>$'+importeX.toFixed(2)+'</td>'+
                                    '<td align="left"><span class="glyphicon glyphicon-trash" onclick="caja.eliminarProducto(\''+idProdCar+'\');"></span></td>'+
                                    '</tr>'); */
                    $('#productsTable1 tr:last').after('<tr class="filas '+colorSat+'" id="filaPro_'+idProdCar+'" prodCar="filaPro_'+idProdCar+'">'+
                                    '<td align="center"><input type="text" id="cant_'+val.idProducto+'" value="'+val.cantidad+'" onblur="caja.recalcula(\'cantidad\' , \''+idProdCar+'\', 1);" class="inpClass numeros" cant="'+idProdCar+'"  cant2="'+idProdCar2+'" lote="'+val.lotes+'" series="'+val.series_display+'" cpr="'+val.codigo+'"></td>'+
                                    '<td '+onclickDes +'>'+val.nombre+'</td>'+
                                    '<td align="center"><input type="hidden" id="precio_'+val.idProducto+'" value="'+precioX+'" class="inpClass2 numeros" onblur="caja.recalcula(\'precio\' , \''+idProdCar+'\', 1);"  precioReal="'+idProdCar+'" '+perfil+' '+permiso+'><input type="text" id="precio_'+val.idProducto+'" value="'+precioX.toFixed(2)+'" class="inpClass2 numeros" onblur="caja.recalcula(\'precio\' , \''+idProdCar+'\', 1);"  precio="'+idProdCar+'" '+perfil+' '+permiso+'></td>'+
                                    '<td align="center">$'+importeX.toFixed(2)+'</td>'+
                                    '<td align="left"><span class="glyphicon glyphicon-trash" onclick="caja.eliminarProducto(\''+idProdCar+'\');"></span></td>'+
                                    '</tr>');
                                subtotal += val.importe;
                    totalProductosCiclo +=parseFloat(val.cantidad); 
                    totalPuntos +=parseFloat(val.puntosVenta);         
                }

            }); 
            //alert(totalPuntos);
            $('#subtotalLabel').text('$'+data['cargos']['subtotal']);
            $('#totalLabel').text('$'+data['cargos']['total']);
            ///////descuento general
            $('#desDiven').empty();
            if (typeof data.descGeneral!== 'undefined') {
                 $('#desDiven').append('<div class="row">'+
                                   '<div class="col-sm-6" style="font-size:12px;">Descuento</div>'+
                                '<div class="col-sm-6" style="font-size:12px;">'+
                                    '<label>$'+data.descGeneral.toFixed(2)+'</label>'+
                                '</div>'+
                            '</div>');  
            }
            //alert(data.subtotal);
            $('#impestosDiv').empty();
            $.each(data.cargos.impuestosPorcentajes, function(index, val) {
                 $('#impestosDiv').append('<div class="row">'+
                               '<div class="col-sm-6" style="font-size:12px;">'+index+'</div>'+
                            '<div class="col-sm-6" style="font-size:12px;">'+
                                '<label>$'+val.toFixed(2)+'</label>'+
                            '</div>'+
                        '</div>');
                    impuestosVal += val; 
            });
            total = subtotal + impuestosVal;





    
            //$('#descuentoLabel').text('$'+data['cargos']['descGeneral'].toFixed(2));
            $('#subtotalLabel').text('$'+data['cargos']['subtotal'].toFixed(2));
            $('#totalLabel').text('$'+data['cargos']['total'].toFixed(2));
            $('#totalDeProductos').text(totalProductosCiclo);
            $('#totalDeProductosInput').val(totalProductosCiclo);
            $('#totalPuntos').text(totalPuntos);
            $('#totalPuntosInput').val(totalPuntos);
            $('.numeros').numeric();
        }else{
            //alert('Error - 1500 Favor de contactar a soporte');
        }

    },
    mensaje: function(mensaje) {

        $('#lblMensajeEstado').text(mensaje);
        $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
    },    
    eliminaMensaje: function() {

        $('#modalMensajes').modal('hide');
    },
    checatimbres: function(idCliente) {
    //$('#cliente-caja').addClass('loader');
        $.ajax({
            url: 'ajax.php?c=caja&f=cargaRfcs',
            type: 'POST',
            dataType: 'json',
            data: {idCliente: idCliente},
            success: function(data)
            {   console.log(data);
                if (data.status)
                {   
                    
                    $("#rfc option").remove();
                    $('#clienteName').text('');
                    $('#clienteName').text($('#cliente-caja').val());
                    $.each(data.rfc, function(index, value) {
                        var option = $(document.createElement('option')).attr({'value': value.id}).html(value.rfc).appendTo($('#rfc'));
                    });
                    if(data.moneda!='1'){
                        $('.coinDiv').show('slow');
                    }
                   
                        $("#labelrfc").show();
                        $("#selectrfc").show('slow');
                        $('#cliente-caja').removeClass('loader');  
                       
                    $.each(data.tarjeta, function(indexi, vali) {
                        
                        $('#puntosCheck').show('slow');
                        $('#pointsCardT').text(vali.puntos)
                        $('#pointsCardIn').val(vali.puntos)
                        $('#tpin').val(vali.numero);
                    });
                    /*if(data.estatus==true){
                       // alert(data.puntos);
                        $('#puntosCheck').show('slow');
                        $('#pointsCardT').text(data.puntos)
                        $('#pointsCardIn').val(data.puntos)
                    }  */              
                     
     
                }else{
                    $('.coinDiv').hide();
                    $('#clienteName').text('');
                    $('#clienteName').text($('#cliente-caja').val());
                    $("#rfc option").remove();
                    $.each(data.tarjeta, function(indexi, vali) {
                       
                        $('#puntosCheck').show('slow');
                        $('#pointsCardT').text(vali.puntos)
                        $('#pointsCardIn').val(vali.puntos)
                        $('#tpin').val(vali.numero);
                    });
                    var option = $(document.createElement('option')).attr({'value': 0}).html('XAXX010101000').appendTo($('#rfc'));
                }
            }
        });
    },
    modalPagar: function (){
            if($('#totalDeProductosInput').val() < .001){
                alert('Tienes que vender al menos un producto.');
                return false;
            }
            caja.checaPagos();
            //caja.checaPagos();
            console.log(caja.data);
            
            console.log('------> Info venta');
            console.log(caja.info_venta);
            
            if(caja.info_venta['ajustes']['switch_propina'] == 1){
	            var $porcentaje = (caja.info_venta['ajustes']['calculo_automatico'] / 100);
	            $porcentaje = $porcentaje.toFixed(2);
	            
	            if(caja.info_venta['ajustes']['aplicar_a'] == 1){
	           		var $monto = caja.data["cargos"]["total"].toFixed(2);
	            }else{
	            	var $monto = caja.data["cargos"]["subtotal"].toFixed(2);
	            }
	            
	            caja.info_venta['venta']['monto_total'] = $monto;
	            
	            $monto = $monto * $porcentaje;
	            $monto = $monto.toFixed(2);
	           	
	            $("#porcentaje_propina").val(caja.info_venta['ajustes']['calculo_automatico']);
	            $("#monto_propina").val($monto);
            }
            
            var points = $('#totalPuntosInput').val();
            //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
            $('#lblTotalxPagar').text(caja.data["cargos"]["total"].toFixed(2));
            $('#lblPuntosVenta').text(points);
            $('#btnAgregarPago').unbind('click').bind('click', function() {

                var tipostr = $('#cboMetodoPago option:selected').text();
                var tipo = $('#cboMetodoPago').val();
                var pago = ($('#txtCantidadPago').val()).replace(",",'');
                if(pago <= 0){
                    alert('El pago debe ser mayor a cero.');
                    return false;
                }
                var txtReferencia = $('#txtReferencia').val();

                caja.metodoPago(tipo, tipostr, pago, txtReferencia);
            });
            $('#cboMetodoPago').unbind('change').bind('change', function() {
                caja.muestraReferenciaPago($(this).val());
            });
            $('#modalPagar').modal({
                show:true,
            });
    },
    checaPagos: function() {

    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=checarPagos',
        dataType: 'json',
        success: function(data) {
            if (data.status)
            {
                $('#abonosPagos').empty();

                var abonosPagos = $('#abonosPagos');

                $.each(data.pagos, function(index, value) {
                    var registroCaja = $(document.createElement('div')).attr({'id': 'Pago' + index}).addClass('form-control registroCaja').appendTo(abonosPagos);
                    var regTipo = $(document.createElement('div')).addClass('col-xs-5').html(value.tipostr).appendTo(registroCaja);
                    var regCantidad = $(document.createElement('div')).attr({'id': 'cantidad' + index}).addClass('col-xs-5').html(value.cantidad).appendTo(registroCaja);
                    var regAccion = $(document.createElement('div')).addClass('col-xs-2').appendTo(registroCaja);
                    var accion = $(document.createElement('img')).addClass('imgDelete').attr({'src': 'img/bor.png'}).appendTo(regAccion);

                    accion.bind('click', function() {
                        caja.eliminarPago(index);
                    });
                });

                $('#lblAbonoPago').text(data.abonado);
                $('#lblPorPagar').text(data.porPagar);
                $('#lblCambio').text(data.cambio);
                $('#txtCantidadPago').val(data.porPagar);
            } else if (data.statusInicio == false)
            {
                $('#modalPago').dialog("close");
                caja.inicioCaja(data);
            }
        }});

    },
    metodoPago: function(tipo, tipostr, cantidad, txtReferencia) {

    if ($('#cboMetodoPago').val() == '')
    {
        return;
    }

    if ($("#txtCantidadPago").val() == "") {
        alert("Ingresa la cantidad para agregar el pago");
        $("#txtCantidadPago").focus();
        return false;
    }

    if ($("#cboMetodoPago").val() == 2 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el número de cheque para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 7 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar la txtReferencia de la transferencia para registrar el pago");
        return false;
    }


    if ($("#cboMetodoPago").val() == 8 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar la txtReferencia SPEI para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 3 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el número de la tarjeta de regalo para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 4 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el número de baucher para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 5 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el número de baucher para registrar el pago");
        return false;
    }


    if ($("#cboMetodoPago").val() == 6 && $("#hidencliente-caja").val() == "")
    {
        alert("Debes seleccionar el cliente para poder registrar un pago a credito");
        return false;
    }

        //Tejeta de regalo
        if ($("#cboMetodoPago").val() == 3)
        {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'ajax.php?c=caja&f=checatarjetaregalo',
                data: {
                    numero: txtReferencia,
                    monto: cantidad
                },
                success: function(response) {
                    if (response.status)
                    {   

                        caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);
                    } else
                    {
                        alert(response.msg);
                    }
                }});//end ajax
        } else if ($("#cboMetodoPago").val() == 6)//pago a credito
        {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'ajax.php?c=caja&f=checalimitecredito',
                data: {
                    cliente: $("#hidencliente-caja").val(),
                    monto: cantidad
                },
                success: function(resp) {
                    if (resp.status)
                    {   
                        caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);
                    } else
                    {
                        alert(resp.msg);
                    }

                }});//end ajax
        } else
        {   //alert(tipo+'-'+tipostr);
            if(tipo==5 || tipo==4){
                var instancia = $('#nombreDeInstancia').val();
                if(instancia =='mlog' || instancia == 'laespecial'){
                    caja.mensaje("Procesando el pago ...");
                    caja.validaTrans(tipo, tipostr, cantidad, txtReferencia);
                }else{
                    caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);  
                }
                
            }else{
               caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);     
            }
            
        }


    },
    validaTrans: function(tipo, tipostr, cantidad, txtReferencia){
     
        $.ajax({
            url: "../../modulos/pos/ajax.php?c=pinpadc&f=pinpadc",
            type: 'POST',
            dataType: 'json',
            data: {tipo:tipo,cantidad:cantidad,tipostr:tipostr,txtReferencia:txtReferencia},
        success: function(data) {

             console.log("kurt "+data);
            if(data==true){
                caja.eliminaMensaje();
                alert("Pago Autorizado");
             caja.agregarPago(tipo, tipostr, cantidad, txtReferencia); }

                     else{
 caja.eliminaMensaje();
                alert("Pago Fallido");

        }

        }




        })
        .done(function() {
            
        })
        .fail(function() {
            console.log("error");

        })
        .always(function() {
           
            console.log("complete");
        });
        
    },
    agregarPago: function(tipo, tipostr, cantidad, txtReferencia)
    {   

        //alert(tipo+'-'+tipostr+'-'+cantidad+'-'+txtReferencia);
        //alert(cantidad);
        /*var cambio = $("#pagar-cambio").html().replace("Cambio:$", "");
         var cambio = cambio.replace("$", "");
         if (cambio > 0)
         {
         alert("Con los pagos efectuados se puede completar la venta");
         return false;
     }*/

        /*if ($("#cboMetodoPago").val() > 3 && $("#cantidadpago").val() > $('#cantidad-recibida').val().replace('$', '').replace(',', ''))
         {
         alert('El pago no debe ser superior al total');
         return false;
         //alert($("#cboMetodoPago").val()+" / "+$("#cantidadpago").val()+" / "+$('#cantidad-recibida').val().replace('$','')); return false;

     }*/
 

     if ($('#Pago' + tipo).length)
     {
        cantidad = parseFloat(cantidad.replace(",", ""));
            //cantidad += parseFloat($('#cantidad' + tipo).html());
        }
        //alert($('input:radio[name=tarRadio]:checked').val());
       /* if(tipo!='1'){
            if(parseFloat(cantidad).toFixed(2) > parseFloat(caja.data["cargos"]["total"]).toFixed(2)){
                alert('La cantidad no puede ser mayor al total');
                return false;
            }
        } */

        $.ajax({
            type: 'POST',
            url: 'ajax.php?c=caja&f=agregaPago',
            dataType: 'json',
            data: {
                tipo: tipo,
                tipostr: tipostr,
                cantidad: cantidad,
                txtReferencia: txtReferencia,
                tarjeta : $('input:radio[name=tarRadio]:checked').val(),
            },
            success: function(data) {
			console.log("======> Pagos");
			console.log(data);
			
                if (data.status)
                {
                    if ($('.nopagos').length)
                    {
                        $('.nopagos').parent().empty();
                    }

                    if ($('#Pago' + data.tipo).length)
                    {
                        $('#cantidad' + data.tipo).html(data.cantidad);
                    } else
                    {
                        var abonosPagos = $('#divDesglosePagoTablaCuerpo');

                        var registroCaja = $(document.createElement('tr')).attr({'id': 'Pago' + data.tipo}).appendTo(abonosPagos);
                        var regTipo = $(document.createElement('td')).html(data.tipostr).appendTo(registroCaja);
                        var regCantidad = $(document.createElement('td')).attr({'id': 'cantidad' + data.tipo}).html(data.cantidad.toFixed(2)).appendTo(registroCaja);
                        var regAccion = $(document.createElement('td')).css({'text-align' : 'center'}).appendTo(registroCaja);
                        var accion = $(document.createElement('span')).addClass('glyphicon glyphicon-remove').appendTo(regAccion);

                        accion.bind('click', function() {
                            caja.eliminarPago(data.tipo);
                        });
                    }

                    $('#lblAbonoPago').text("$ " + data.abonado);
                    $('#lblPorPagar').text("$ " + data.porPagar);
                    $('#lblCambio').text("$ " + data.cambio);

                    $('#txtCantidadPago').val(data.porPagar);
                    $('#txtReferencia').val('');
                }
            }});
},
eliminarPago: function(pago) {

    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=eliminarPago',
        dataType: 'json',
        data: {
            pago: pago
        },
        success: function(data) {

            $('#Pago' + pago).hide('slow', function() {
                $(this).remove();

                if ($('#abonosPagos').html() == '')
                {
                    $('#abonosPagos').html('<div class="form-control registroCaja nopagos"><div class="col-xs-12 text-center">No hay pagos</div></div>');
                }
            });

            if (data.status)
            {
                $('#lblAbonoPago').text(data.abonado);
                $('#lblPorPagar').text(data.porPagar);
                $('#lblCambio').text(data.cambio);
            } else
            {
                $('#lblAbonoPago').text('0.00');
                $('#lblPorPagar').text('0.00');
                $('#lblCambio').text('0.00');
            }
        }});

},
pagar: function() {
    $('#pagarPagar').prop('disabled', true);
    
    var codigo = $('#codigo').val();
    var propina = $('#propina').val();
    var pedido = $('#idPedido').val();
 
    if ($('.nopagos').length)
    {
        alert('Debes saldar la deuda.');
        $('#txtCantidadPago').focus();
        $('#pagarPagar').prop('disabled', false);
        return;
    }

    if ($('#lblPorPagar').text() != '0.00' && $('#lblPorPagar').text() != '$ 0.00')
    {
        alert('No has cubierto el total de la deuda.');
        $('#pagarPagar').prop('disabled', false);
        return;
    }



    if (codigo != '')
    {
        $.ajax({
            url: '../restaurantes/ajax.php?c=productocomanda&f=borrarProductoTemporal',
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {'codigo': codigo},
            error: function(data) {
                alert('No se pudo borrar la comanda-p');
                return;
            }
        });
    }

    var totalPuntosInput = $('#totalPuntosInput').val();
    var usarPuntos = 0;
        if($('#usarPuntos').is(':checked')){
            usarPuntos = 1;
       }else{
            usarPuntos = 0;
       }

    $.ajax({
        url: 'ajax.php?c=caja&f=guardarVenta',
        type: 'POST',
        dataType: 'json',
        async: true,
        data: {
            idFact: $("#rfc").val(),
            propinas: caja.info_venta['propinas'],
            documento: $("#documento").val(),
            cliente: $("#hidencliente-caja").val(),
            vendedor: $("#hidenvendedor-caja").val(),
            suspendida: $("#s_cliente").val(),
            propina: propina,
            comentario: $('#txtareacomentariosProducto').val(),
            moneda: $('#monedaVenta').val(),
            tipocambio: $('#tpc').val(),
            usarPuntos : usarPuntos,
            totalPuntosInput : totalPuntosInput,
            tr : $('#tpin').val()
                //pagoautomatico: 1,
                //impuestos: $totalimpuestos,
                //sucursal: $("#caja-sucursal").val(),
                //almacen: $("#caja-almacen").val(),
                //cambio: 0,
                //monto: $total,
                //cliente: $("#hidencliente-caja").val(),
                //empleado: $("#idvendedor").val()
            },
            beforeSend: function() {
                caja.mensaje("Guardando Venta");
            },
            success: function(resp) {
            	console.log('----> success venta');
            	console.log(resp);
            	//alert(pedido);
                //alert(resp.idVenta);

                //ch
                $.ajax({
                    url: 'ajax.php?c=caja&f=datosventa2',
                    type: 'POST',
                    dataType: 'json',
                    data: {idVenta : resp.idVenta},
                })
                .done(function(data) {
                    $.each(data, function(index, val) { 
                        var emailCliente  = val.emailCliente; 
                        $("#emailTicket").val(emailCliente);
                    });                      
                });
                


                if (resp.status)
                {   $('#modalPagar').modal('hide');
                    /*if(pedido!=''){ 

                        //Cambia el estatus del pedido
                        $.ajax({
                            url: 'ajax.php?c=caja&f=estatusPedido',
                            type: 'POST',
                            dataType: 'json',
                            data: {idVenta : resp.idVenta,
                                   idPedido : pedido },
                        })
                        .done(function(resx) {
                            console.log(resx);

                        })
                        .fail(function() {
                            console.log("error");
                        })
                        .always(function() {
                            console.log("complete");
                        });
                        
                    } */
                 if($('#documento').val() == 2)
                 {
                    $('#lblComentarioE').html('la Factura.');
                }else if($('#documento').val() == 5){
                    $('#lblComentarioE').html('el Recibo de Honorarios.');
                }else{
                    $('#lblComentarioE').html('el Recibo de Ingresos.');
                }
                caja.observacionesFactura(resp);
            } else
            {
                caja.eliminaMensaje();
                alert(resp.msg);
            }
            //$('#pagarPagar').prop('disabled', false);
        },
        error: function(data) {
			console.log('----> error venta');
			console.log(data);
            	
            caja.eliminaMensaje();
            alert(data.msg);
        }
    });
},
observacionesFactura: function(resp) {
    obsResp = resp;

    if ($('#documento').val() == 1)
    {
        caja.comprobante(resp, false);
        caja.mensaje("Generando Ticket");
    } else if ($('#documento').val() == 4) {
        caja.comprobante(resp, false);
        caja.mensaje("Generando Recibo de pago");
    } else {
        caja.eliminaMensaje();
        $('#modal_Observaciones').modal({
                                        backdrop: 'static',
                                        keyboard: false, 
                                    });
    }
},
observacionesEnviar: function(){
    $('#modal_Observaciones').modal("hide");
    if($('#documento').val() == 2)
    {   caja.eliminaMensaje();
        caja.mensaje("Generando Factura");
    }else if($('#documento').val() == 5){
        caja.eliminaMensaje();
        caja.mensaje("Generando Recibo de Honorarios");
    }else{
        caja.eliminaMensaje();
        caja.mensaje("Generando Recibo de Ingresos");
    }
    caja.comprobante(obsResp, $('#txtareaObservaciones').val());
},
comprobante: function(resp, mensaje) {
 console.log(resp);
 console.log(mensaje);
 
var serie = $('#seriesCfdi').val();
var usoCfdi  = $('#usoCfdi').val();
var mpCat = $('#mpCat').val();
var relacion = $('#tipoRelacionCfdi').val();
var uuidRelacion = $('#cfdiUuidRelacion').val();

    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=facturar',
        dataType: 'json',
        data: {
            idFact: $("#rfc").val(),
            idVenta: resp.idVenta,
            doc: $('#documento').val(),
            mensaje: mensaje,
            consumo:$('#consumo').val(),
            moneda: $('#monedaVenta').val(),
            tipocambio: $('#tpc').val(),
            serie: serie,
            usoCfdi : usoCfdi,
            mpCat : mpCat,
            relacion : relacion,
            uuidRelacion : uuidRelacion


        },
        beforeSend: function() {



        },
        success: function(resp) {
            //alert('entro al success');
            //return false;
           console.log(resp);
            caja.eliminaMensaje();
            //resp.success = 500;
            /*if (resp.success == '500') {
                $.ajax({
                    url: 'ajax.php?c=caja&f=creaCxC',
                    type: 'POST',
                    dataType: 'json',
                    data: {idVenta: resp.idVenta},
                })
                .done(function() {
                    console.log("success");
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
                
                alert(resp.mensaje);
                //window.location.reload();
                
            } */
            if (resp.success == '-1') {
                alert('Ha ocurrido un error durante el proceso de venta y facturacion.');
                window.location.reload();
                return false;
            }
            if (resp.success == '3') {
                alert('Venta realizada con exito.');
                
                caja.modalComprobante("../../modulos/pos/ticket.php?idventa=" + resp.idVenta, true, resp.idVenta);

                    //window.location.reload();
                    return false;
                }
                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                ================================================ */
                if (resp.success == 0 || resp.success == 5) {
                    if (resp.success == 0) {
                        alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                            $.ajax({
                                url: 'ajax.php?c=caja&f=creaCxC',
                                type: 'POST',
                                dataType: 'json',
                                data: {idVenta: resp.idVenta},
                            })
                            .done(function() {
                                console.log("success");
                            })
                            .fail(function() {
                                console.log("error");
                            })
                            .always(function() {
                                console.log("complete");
                            });

                    }
                    //alert('esto es una prueba');
                    if ($('#documento').val() == 4) {
                        caja.modalComprobante("../../modulos/pos/reciboPdf.php?idventa=" + resp.idVenta, true, resp.idVenta);
                        $('#inputRecibo').val('4');
                    } else {
                        caja.modalComprobante("../../modulos/pos/ticket.php?idventa=" + resp.idVenta, true, resp.idVenta);
                        // caja.modalComprobante("ajax.php?c=caja&f=ticket&idVenta=" + resp.idVenta, true);
                        $('#inputRecibo').val('1');
                    }

                    console.log(resp);
                    $.ajax({
                        type: 'POST',
                        url:'ajax.php?c=caja&f=pendienteFacturacion',
                        data:{
                            azurian:resp.azurian,
                            idFact:$("#rfc").val(),
                            monto:(resp.monto),
                            cliente:$("#hidencliente-caja").val(),
                            trackId:resp.trackId,
                            idVenta:resp.idVenta,
                            doc: $('#documento').val()

                        },
                        beforeSend: function() {
                            caja.eliminaMensaje();
                            caja.mensaje("Guardando Factura 2");
                        },
                        success: function(resp){  
                            caja.eliminaMensaje();
                        }

                    });

                }

                if (resp.success == 1)
                {
                    azu = JSON.parse(resp.azurian);
                    uid = resp.datos.UUID;
                    correo = resp.correo;
                    obser = azu.Observacion.Observacion;
                    logo =  azu.org.logo;
                    $.ajax({
                        type: 'POST',
                        url: 'ajax.php?c=caja&f=guardarFacturacion',
                        dataType: 'json',
                        data: {
                            UUID: uid,
                            noCertificadoSAT: resp.datos.noCertificadoSAT,
                            selloCFD: resp.datos.selloCFD,
                            selloSAT: resp.datos.selloSAT,
                            FechaTimbrado: resp.datos.FechaTimbrado,
                            idComprobante: resp.datos.idComprobante,
                            idFact: resp.datos.idFact,
                            idVenta: resp.datos.idVenta,
                            noCertificado: resp.datos.noCertificado,
                            tipoComp: resp.datos.tipoComp,
                            trackId: resp.datos.trackId,
                            monto: (resp.monto),
                            cliente: $("#hidencliente-caja").val(),
                            idRefact: 0,
                            azurian: resp.azurian,
                            doc: $('#documento').val(),
                            serie: serie
                        },
                        beforeSend: function() {
                            if($('#documento').val() == 2)
                            {   
                                $('#labelTF').text("Factura");
                                //$('#emailTicketHide').hide();  
                                caja.mensaje("Guardando Factura");
                            }else if($('#documento').val() == 3)
                            {
                                caja.mensaje("Guardando Recibo de Ingresos");
                            }
                        },
                        success: function(resp) {
                           
                            caja.eliminaMensaje();
                            //window.open('../../modulos/facturas/'+uid+'.pdf');
                            if($('#versionFacturacionHide').val() == '3.3'){
                                openedWindow = window.open('../../modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo='+logo+'&id=temporales&caja=1&nominas=1&ob='+obser);
                               //openedWindow = window.open('../../modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo=f_de_foodware.png&id=temporales&caja=1&nominas=1&ob='+obser);
                                //openedWindow.close();
                            }
                          
                            setTimeout(function(){

                            	
                           	$.ajax({
                                async: true,
                                type: 'POST',
                                url: 'ajax.php?c=caja&f=envioFactura',
                                dataType: 'json',
                                data: {
                                    uid: uid,
                                    correo: correo,
                                    azurian: azu,
                                    doc: $('#documento').val()
                                },
                                beforeSend: function() {
                                    //caja.mensaje("Enviando Factura");
                                },
                                success: function(resp) {
                                    $('#modalFacturacion').modal('hide');
                                    $('#modalCodigoVenta').modal('hide');

                                    caja.eliminaMensaje();
                                    if(resp.cupon==false){
                                        if($('#versionFacturacionHide').val() == '3.3'){
                                            caja.modalComprobante('../../modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo='+logo+'&id=temporales&caja=1&nominas=1&ob='+obser, false, uid);
                                            //caja.modalComprobante('../../modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo=f_de_foodware.png&id=temporales&caja=10&nominas=1&ob='+obser, false, uid);
                                        }else{
                                            caja.modalComprobante('../../modulos/facturas/'+uid+'.pdf', false, uid);
                                        }
                                    }else{
                                        caja.modalComprobante('../../modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false, uid);
                                    }
                                    caja.eliminaMensaje();
                                    //window.open('../../modulos/facturas/' + uid + '.pdf');
                                    //window.location.reload();
                                },
                                error: function() {
                                    caja.eliminaMensaje();
                                }
                            });
                            },3000);

$("#loaderventa").hide();
$('#caja-dialog').modal('hide');
$("#boton-pagar").removeAttr("disabled");
alert('Has registrado la venta con exito');
                            //window.location.reload();
                        },
                        error: function() {
                            caja.eliminaMensaje();
                        }
                    });
}
			// Redirecciona al mapa de mesas si es comida rapida
                if(caja.info_venta.ajustes['tipo_operacion'] == 3){
					setTimeout(function() {
						var pestana = $("body", window.parent.document).find("#tb2156-1");
						var mapa = $("body", window.parent.document).find("#mnu_2156");
						mapa.trigger('click');
						pestana.trigger('click');
						window.location.reload();
					}, 500);
                }
            }
        });
},
modalComprobante: function(src, ticket, idVenta) {
    
    if($('#documento').val() == "2" ){
       /* $('#labelTF').text("Factura");
        caja.eliminaMensaje();
        $('#emailTicketHide').hide(); */
        $('.facSend').show();
 
    }else{
        
        $('.facSend').hide();
    }
        caja.eliminaMensaje();
        $('#idVentaTicket').val(idVenta);
        var sizeWidth = 0;
        var sizeheight = 0;

        if (ticket)
        {
            sizeWidth = 325;
            sizeheight = 450;
        } else {

            sizeWidth = $('#tb1238-u', window.parent.document).width() - 100;
            sizeheight = $('#tb1238-u', window.parent.document).height() - 50;
            
        }

        $('#modalPago').modal('hide');

        $('#frameComprobante').attr({'src': src});

        $('#modalComprobante').modal({backdrop: 'static'});
},
eliminarProducto: function(idProducto)
    {
        $.ajax({
            url: 'ajax.php?c=caja&f=eliminaProducto',
            type: 'POST',
            dataType: 'json',
            data: {'id': idProducto},
            beforeSend: function() {
               /* if (caja.currentRequestP != null) {
                    caja.currentRequestP.abort();
                }
                if (caja.currentRequest != null) {
                    caja.currentRequest.abort();
                } */
            },
            success: function(data) {
                ///Elimina por atributo con sus caracteristcas
                var x = 'tr[prodCar="filaPro_'+idProducto+'"]';
                //var x = 'filaPro_'+idProducto;
              
                if (data)
                {   
                    $(x).hide('slow', function() {
                        $(x).empty();
                        //$('#filaPro_' + idProducto).empty();
                        if (data.count < 2){   
                            caja.cancelarCaja();
                        } else{   
                            caja.pintaResultados(data, false);
                        }
                    });
                }
            }
        });
    },
    cancelarCaja: function(msg)
    {
         $('#modalVentasSuspendidas').modal('hide');
        if (msg)
        {
            if (!confirm('¿Deseas cancelar la la venta?'))
            {
                return;
            }
        }


        $.ajax({
            url: 'ajax.php?c=caja&f=cancelarCaja',
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                if (caja.currentRequestP != null) {
                    caja.currentRequestP.abort();
                }
                if (caja.currentRequest != null) {
                    caja.currentRequest.abort();
                }
            },
            success: function(data) {

                if (data)
                {
                    caja.limpiaPago();
                    $('.contenidoForm').empty().html('<div class="registroCaja noProducts"><label class="col-xs-12" style="text-align:center">No hay productos en la caja.</label></div>');
                    $('.impuestosCaja').empty().css({'display': 'none'});
                    $('#codigo').val('');

                    $('#documento').val(1).trigger('change');
                    $('#search-producto').val('').typeahead('clearHint');
                    //$('#cliente-caja').val('').typeahead('clearHint');
                    $('#cliente-caja option[value=""]').attr('selected','selected');
                    $('#totalDeProductos').text('0');
                    $('#totalDeProductosInput').val(0);
                    caja.pintaResultados();
                }
            }
        });
    },
    limpiaPago: function(){

        $('#cboMetodoPago').val(1);
        $('#txtCantidadPago').val('');
        $('#lblTotalxPagar').text('');
        $('#lblAbonoPago').text('0.00');
        $('#lblReferencia').text('');
        $('#txtReferencia').text('');

        $('#lblAbonoPago').text('0.00');
        $('#lblPorPagar').text('');
        $('#lblCambio').text('0.00');
        
            $('#subtotalLabel').text('$0.00');
            $('#totalLabel').text('$0.00');
            //alert(data.subtotal);
            $('#impestosDiv').empty();
            $('#desDiven').empty();
            $('.filas').empty();
            $('#divDesglosePagoTablaCuerpo').empty();
    },
    suspender: function() {

       /* if ($("#hidencliente-caja").val() == "") {
            alert("Nesesita seleccionar un cliente para suspender la venta!");
            return false;
        } */


        $.ajax({
            type: 'POST',
            url: 'ajax.php?c=caja&f=suspenderVenta',
            dataType: 'json',
            data: {
                idFact: $("#rfc").val(),
                documento: $("#documento").val(),
                cliente: $("#hidencliente-caja").val(),
                nombre: $("#cliente-caja").val(),
                suspendida: $('#s_cliente').val()
            },
            success: function(resp) {

                if (resp.status) {
                    window.location.reload();
                } else
                {
                    alert(resp.msg);
                }

            }});

    },
    cargarSuspendida: function() {
        
    $('#modalVentasSuspendidas').modal('hide');
    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=cargarSuspendida',
        dataType: 'json',
        data: {
            id_susp: $('#s_cliente').val()
        },
        success: function(data) {

            if (data.estatus)
            { 
                caja.pintaResultados(data, false);
                    //$('#hidencliente-caja').val(data.cliente);
                }
                else
                {   
                    
                    alert(data.msg);
                }
            }});
    },
    recalcula: function(field , idProducto, origen){
        //caja.tieneLS(idProducto);


        $('#search-producto').focus();
        //var cantidad = $('#cant_'+idProducto).val();
        //var precio = $('#precio_'+idProducto).val();
        if (origen == 1) {
            var x = (field == 'precio') ? '#productsTable1 input[precio="'+idProducto+'"]' : '#productsTable1 input[precioReal="'+idProducto+'"]';
            var y = '#productsTable1 input[cant="'+idProducto+'"]';
            var z = '#productsTable1 input[series="'+idProducto+'"]';
        }else{
            var x = (field == 'precio') ? '#productsTable2 input[precio="'+idProducto+'"]' : '#productsTable2 input[precioReal="'+idProducto+'"]';
            var y = '#productsTable2 input[cant="'+idProducto+'"]';
            var z = '#productsTable2 input[series="'+idProducto+'"]';
        }

        var precio = $(x).val();
        var cantidad = $(y).val();
        var series = $(y).attr('series');
        var lotes = $(y).attr('lote');
        var cpr = $(y).attr('cpr');
        if((series!='' || lotes!='') && field != "precio" ) {

            caja.buscaCaracteristicas(cpr,series);
            //caja.llenaSelectSeries(series);
            return false;
        }

        if(precio < 0){
            alert('No puedes utilizar precios negativos.');
            caja.pintaResultados(caja.data, false)
            return false;
        }   
        if(cantidad < 0){
            alert('No puedes utilizar cantidades negativas.');
            caja.pintaResultados(caja.data, false)
            return false;
        }   

            $.ajax({
                url: 'ajax.php?c=caja&f=recalcula',
                type: 'POST',
                dataType: 'json',
                data: {cantidad: cantidad,
                        precio : precio,
                        idProducto : idProducto,
                        field : field
                    },
            })
            .done(function(data) {
                console.log(data);
                if(data.estatus==true){
                    //alert('44444');
                     caja.data = data;
                     caja.pintaResultados(data, false);
                        $('#lblTotalxPagar').text(caja.data["cargos"]["total"].toFixed(2));
                        $('#btnAgregarPago').unbind('click').bind('click', function() {

                            var tipostr = $('#cboMetodoPago option:selected').text();
                            var tipo = $('#cboMetodoPago').val();
                            var pago = ($('#txtCantidadPago').val()).replace(",",'');
                            if(pago < 1){
                                alert('El pago debe ser mayor a cero.');
                                return false;
                            }
                            var txtReferencia = $('#txtReferencia').val();

                            caja.metodoPago(tipo, tipostr, pago, txtReferencia);
                        });
                        $('#cboMetodoPago').unbind('change').bind('change', function() {
                            caja.muestraReferenciaPago($(this).val());
                        });
                }else{
                    alert('No tienes Existencia del producto');
                    $('#search-producto').focus();
                    data.estatus=true;
                    caja.data = data;
                     caja.pintaResultados(data, false);
                        $('#lblTotalxPagar').text(caja.data["cargos"]["total"].toFixed(2));
                        $('#btnAgregarPago').unbind('click').bind('click', function() {

                            var tipostr = $('#cboMetodoPago option:selected').text();
                            var tipo = $('#cboMetodoPago').val();
                            var pago = ($('#txtCantidadPago').val()).replace(",",'');
                            if(pago < 1){
                                alert('El pago debe ser mayor a cero.');
                                return false;
                            }
                            var txtReferencia = $('#txtReferencia').val();

                            caja.metodoPago(tipo, tipostr, pago, txtReferencia);
                        });
                        $('#cboMetodoPago').unbind('change').bind('change', function() {
                            caja.muestraReferenciaPago($(this).val());
                        });
                    return false;
                }
                
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        
    },
    llenaSelectSeries: function(series){
        var xy = series.split(',')
        $.each(xy, function(index, val) {
            if(val!=''){
                $( "#series option[disp='"+val+"']" ).prop("selected",true);
            }
        });
    },eliminarSuspendida: function() {
        $('#modalVentasSuspendidas').modal('hide');
        //caja.eliminaMensaje();
        caja.cancelarCaja();
        $.ajax({
            type: 'POST',
            url: 'ajax.php?c=caja&f=eliminarSuspendida',
            dataType: 'json',
            data: {
                suspendida: $('#s_cliente').val()
            },
            success: function(data) {
                if (data.status)
                {
                    alert('Se elimino correctamente');
                    window.location.reload();
                }
                else
                {
                    alert(data.msg);
                }
            }});
    },
    agregaProTouch: function(id){
        var codigo = $(id).attr('codigoProTouch');
        //alert('entro porProtocuh');
        caja.buscaCaracteristicas(codigo);
        //caja.agregaProducto(codigo,'');

    },
    facturarButton: function(){
        $('#gridHidden').hide();
        $('#rfcMoldal').val('');
        $('#modalFacturacion').modal({
                show:true,
            });
    },
    clienteAddButton: function(){
        $('#modalCliente').modal({
                show:true,
            });
    },
    municipiosF: function(){
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
    },
    guardaCliente: function(){
        var idCliente =  $('#idCliente').val();
        var codigo =  $('#codigo').val();
        var nombre =  $('#nombre').val();
        var tienda =  $('#tienda').val();
        var numint =  $('#numint').val();
        var numext =  $('#numext').val();
        var direccion =  $('#direccion').val();
        var colonia =  $('#colonia').val(); 
        var cp =  $('#cp').val();
        var estado =  $('#estado').val();  
        var municipio =  $('#municipios').val();
        var email =  $('#email').val();
        var celular =  $('#celular').val();
        var tel1 =  $('#tel1').val();
        var tel2 =  $('#tel2').val();
        var rfc =  $('#rfc2').val();
        var curp =  $('#curp').val();
        var diasCredito =  $('#diasCredito').val();
        var limiteCredito =  $('#limiteCredito').val();
        var moneda =  $('#moneda').val();
        var listaPrecio =  $('#listaPrecio').val();

        var razonSocial = $('#razonSocial').val();
        var regimen = $('#regimen').val();
        var ciudad = $('#cdF').val();

        caja.mensaje("Guardando Cliente");
        $.ajax({
            url: 'ajax.php?c=cliente&f=guardaCliente',
            type: 'POST',
            dataType: 'json',
            data: {idCliente: idCliente,
                    codigo : codigo,
                    nombre : nombre,
                    tienda : tienda,
                    numint : numint,
                    numext : numext,
                    direccion: direccion,
                    colonia : colonia,
                    cp : cp,
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
                    regimenFact: regimen,
                    razonSocial : razonSocial,
                    ciudadFact : ciudad,
                    flag : 2

                    },
        })
        .done(function(data) {
            console.log(data);
            if(data.idClienteInser!=''){
                caja.eliminaMensaje();
                $('#modalSuccess').modal({
                    show:true,
                });
            }else{
                alert('Erro 1540 - Comunicate con el area de consultoria.');
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });

    },
    cierramodales: function (){
        $('#modalSuccess').modal('hide');
        $('#modalCliente').modal('hide');
        caja.init();
    },
    ventasButtonAccion: function(){
        caja.mensaje("Procesando...");
        $.ajax({
            url: 'ajax.php?c=caja&f=ventasCaja',
            type: 'post',
            dataType: 'json',
            //data: {param1: 'value1'},
        })
        .done(function(data) {
            /*console.log(data);


            var table = $('#tableSales').DataTable();
    
            //$('.filas').empty();
            table.clear().draw();
            var x ='';
            var estatus = '';
            $.each(data, function(index, val) {
                if(val.estatus=='Activa'){
                    estatus = '<span class="label label-success">Activa</span>';
                }else{
                    estatus = '<span class="label label-danger">Cancelada</span>';
                }
                x ='<tr class="filas">'+
                                '<td>'+val.folio+'</td>'+
                                '<td>'+val.fecha+'</td>'+
                                '<td>'+val.cliente+'</td>'+
                                '<td>'+val.empleado+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                                '<td>'+estatus+'</td>'+
                                '<td>$'+parseFloat(val.iva).toFixed(2)+'</td>'+
                                '<td>$'+parseFloat(val.monto).toFixed(2)+'</td>'+
                                '<td><button class="btn btn-primary btn-block" onclick="caja.ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            }); */

            console.log(data);
            var table = $('#tableSales').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            var monto = 0;
            var iva = 0;
            var total = 0;
            var docu = '';
            var xlink = '';
            var cad = '';
            $.each(data, function(index, val) {
                monto = parseFloat(val.monto);
                if(val.estatus=='Activa'){
                    estatus = '<span class="label label-success">Activa</span>';
                    total += parseFloat(monto.toFixed(2));  
                }else{
                    estatus = '<span class="label label-danger">Cancelada</span>';
                }

                if(val.documento==1){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);
                        if (typeof cad.Basicos.folio !== 'undefined') {
                             xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                        }else{
                             xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.Folio+'</a>';
                        }   

                        docu = 'Ticket Facturado('+xlink+')';
                    }else{
                        docu = 'Ticket';
                    }
                    
                }else if(val.documento==2){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        if (typeof cad.Basicos.folio !== 'undefined') {
                            xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                        }else{
                            xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.Folio+'</a>';
                        }

                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Factura('+xlink+')';
                }else if(val.documento==4){
                    docu = 'Recibo de pago';
                }else if(val.documento==5){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);
                        if (typeof cad.Basicos.folio !== 'undefined') {
                            xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                        }else{
                            xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.Folio+'</a>';
                        }
                        //xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Recibo de Honorarios('+xlink+')';
                } 

                if(val.devoluciones != 0)
                    estatus += '<br> <span class="label label-warning" > Con devoluciones </span>';
                iva = parseFloat(val.iva);
                x ='<tr class="filas">'+
                                '<td>'+val.folio+'</td>'+
                                '<td>'+docu+'</td>'+
                                '<td>'+val.fecha+'</td>'+
                                '<td>'+val.cliente+'</td>'+
                                '<td>'+val.empleado+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                                '<td>'+estatus+'</td>'+
                                '<td>$'+iva.toFixed(2)+'</td>'+
                                '<td>$'+monto.toFixed(2)+'</td>'+
                                '<td><button class="btn btn-primary btn-block" onclick="caja.ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>'+
                                '</tr>';  
                    table.row.add($(x)).draw();

                                         
            });  
            caja.eliminaMensaje();
            $('#modalVentasList').modal({
                show:true,
            });
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    },
    ventaDetalle: function (id){
  
        $.ajax({
            url: 'ajax.php?c=caja&f=detalleVenta',
            type: 'post',
            dataType: 'json',
            data: {idVenta: id},
        })
        .done(function(data) {
            console.log(data);
            $('#idFacPanel').text(id);
            $('#idVentaHidden').val(id);
            $('.rowsSale').remove();
            if(data.estatusVenta==0){
                $('#cancelButton').hide();
                $('#idComentarioDevolucion').hide();
                $('#idAlmacenDevolucion').hide();
                $('#devButton').hide();
            }else{
                $('#cancelButton').show();
                $('#idComentarioDevolucion').show();
                $('#idAlmacenDevolucion').show();
                $('#devButton').show();
            }
            var descDesc = '';

            $.each(data.products, function(index, val) {

                if(val.id==0){
                    val.nombre = val.comentario;
                    val.codigo = 'promo';
                }


                if(val.montodescuento > 0){
                    //descDesc  = '[Precio:$'+parseFloat(val.precio).toFixed(2)+',Descuento:$'+parseFloat(val.montodescuento).toFixed(2)+'/'+val.tipodescuento+''+val.descuento+']';
                    descDesc  = '[Descuento:$'+parseFloat(val.montodescuento).toFixed(2)+'/'+val.tipodescuento+''+val.descuento+']';
                
                }
                var devolver = `
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <input type="number" class="form-control inputCantidadDevolucion" value="0" min="0" max=${val.cantidad} />
                    </div>
                </div>
                `;
                    $('#tablaVenta').append('<tr class="rowsSale" id="detalle_'+val.id+'" ventaProducto="'+val.idventa_producto+'" json="" > '+
                                    '<td>'+val.codigo+'</td>'+
                                    '<td>'+val.nombre+descDesc+'</td>'+
                                    '<td align="center" class="cantidadProductos">'+val.cantidad+'</td>'+
                                    '<td>$'+parseFloat(val.preciounitario).toFixed(2)+'</td>'+
                                    '<td>$'+parseFloat(val.impuestosproductoventa).toFixed(2)+'</td>'+
                                    '<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                    '<td>' + devolver + '</td>'+
                                    '<th> <a class="btn"> <span class="label '+(val.devoluciones != 0 ? "label-warning" : "label-default" ) +'" '+(val.devoluciones != 0 ? 'onclick="caja.detalleMovimientoDevolucion(' + val.idventa_producto + ');"' : "" ) + '> Ver devoluciones </span> </a> </th>'+
                                    '<th class="idVentaProductoDevolucion" style="display: none;">' + val.idventa_producto + '</th>'+
                                    '</tr>');
                    descDesc = '';
            }); 

            $('.inputCantidadDevolucion')
            .on('click', function(event) {
                event.preventDefault();
                $(this).trigger('focus');
            })
            .on('focus', function()  {
                var idVentaProducto = $(this).parent().parent().parent().parent().find('.idVentaProductoDevolucion').text() ;
                
                $.ajax({
                    type: "GET",                                            
                    url: "ajax.php?c=caja&f=obtenerSeriesYLotes",
                    data: {
                        idVentaProducto : idVentaProducto
                    },                                          
                    timeout: 2000,   
                    dataType: 'json',                                       
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(data) {

                        //console.log(data);
                        if(data.series.length > 0) {
                            //console.log(data.series.substring(0,data.series.length-1).split(","));

                            //arraySeries = data.series.substring(0,data.series.length-1).split(",");

                            domSeries = '';
                            $.each(data.series, function(index, val) {
                                  domSeries += `
                                        <tr>
                                            <td> <input type="checkbox" name="serie_${val.id}" >  </td>
                                            <td> ${val.serie} </td>
                                        </tr>
                                `;
                            });
                            /*data.series.forEach( (s) => {
                                domSeries += `
                                        <tr>
                                            <td> <input type="checkbox" name="serie_${s.id}" >  </td>
                                            <td> ${s.serie} </td>
                                        </tr>
                                `;
                            }); */
                            $('#modalSeriesDevolucion table').attr('ventaProducto', idVentaProducto);
                            $('#aceptarDevolucionSeries').attr('ventaProducto', idVentaProducto);
                            $('#modalSeriesDevolucion tbody').empty().append(domSeries);
                            $('#modalSeriesDevolucion').modal({
                                show:true,
                            });
                            $('#aceptarDevolucionSeries').on('click' , function() {
                                seriesDevolver = [];
                                $('#modalSeriesDevolucion tbody input').each( function() {
                                    if( $(this).is(":checked") ){
                                        seriesDevolver.push($(this).attr('name'));
                                    }
                                });
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }] .inputCantidadDevolucion`).val( seriesDevolver.length ) ;
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }]`).attr( 'json', JSON.stringify(seriesDevolver) ) ;

                                // $('#modalSeriesDevolucion tbody input').each( function() {
                                //     console.log( $(this).attr('name') , $(this).is(":checked") ) ;
                                // });
                            });

                            
                            

                            
                        }

                        if(data.lotes.length > 0) {
                            //console.log(data.lotes.substring(0,data.lotes.length-3).split(","));

                            //arrayLotes = data.lotes.lotes.substring(0,data.lotes.length-3).split(",");
                            domLotes = '';
                            $.each(data.lotes, function(index, l) {
                                domLotes += `
                                        <tr>
                                            <td> <input type="number" max="${l[1]}" name="lote_${l[0]}" value=0 ></td>
                                            <td> ${l[1]} </td>
                                            <td> ${l[2]} </td>
                                        </tr>
                                `;
                            });
                           /* data.lotes.forEach( (l) => {
                                //loteCantidad = l.split("-");
                                domLotes += `
                                        <tr>
                                            <td> <input type="number" max="${l[1]}" name="lote_${l[0]}" value=0 ></td>
                                            <td> ${l[1]} </td>
                                            <td> ${l[2]} </td>
                                        </tr>
                                `;
                            }); */

                            $('#modalLotesDevolucion table').attr('ventaProducto', idVentaProducto);
                            $('#aceptarDevolucionLotes').attr('ventaProducto', idVentaProducto);
                            $('#modalLotesDevolucion tbody').empty().append(domLotes);
                            $('#modalLotesDevolucion tbody input').
                            on('change', function(event) {
                                if( parseFloat($(this).val()) > parseFloat($(this).attr('max')) ) {
                                    $(this).val($(this).attr('max'));
                                    alert("No puedes devolver una cantidad mayor a la de la venta");
                                }
                            });
                            $('#modalLotesDevolucion').modal({
                                show:true,
                            });

                            $('#aceptarDevolucionLotes').on('click' , function() {
                                lotesDevolver = [];
                                cantidadProductos = 0;
                                $('#modalLotesDevolucion tbody input').each( function() {
                                    if( $(this).val() ){
                                        let objLote = {};
                                        objLote[$(this).attr('name')] = $(this).val();
                                        lotesDevolver.push( objLote );
                                            cantidadProductos +=  parseInt( $(this).val() );
                                    }else {
                                        cantidadProductos +=  0;
                                    }
                                });
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }] .inputCantidadDevolucion`).val( cantidadProductos ) ;
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }]`).attr( 'json', JSON.stringify(lotesDevolver) ) ;
                                // $('#modalLotesDevolucion tbody input').each( function()  {
                                //     console.log( $(this).attr('name')  , $(this).val() ) ;
                                // });
                            });

                            
                        }
                    },
                    error: function() {                                    
                    }
                });
            })
            .on('change', function() {
                var cantidad = $(this).parent().parent().parent().parent().find('.cantidadProductos').text() ;

                if ( parseInt( $(this).val() ) > parseInt( cantidad )) {
                    $(this).val("0");
                    alert( "Introduce una cantidad menor a la cantidad en la venta." );
                }
                else if(parseInt( $(this).val() ) < 0){
                    $(this).val("0");
                    alert( "Introduce una cantidad válida." );
                }
                var thisself = $(this);
                var idVentaProductoDevolucion = $(this).parent().parent().parent().parent().find('.idVentaProductoDevolucion').text() ;
                $.ajax({
                    type: "GET",                                            
                    url: "ajax.php?c=caja&f=productosDevueltos",
                    data: {"id" : idVentaProductoDevolucion },                                          
                    timeout: 2000,   
                    dataType: 'json',                                       
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(data) {
                        if(data.status == true && data.rows[0].devueltos != null) {
                            var disponibles = cantidad - data.rows[0].devueltos;
                            if ( disponibles <  thisself.val()) {
                                alert("Introduce una cantidad menor a " + disponibles +  " (productos disponibles para devolución)");
                                thisself.val(disponibles);
                            }
                        }
                        
                    },
                    error: function() {  
//alert("Error al procesar productos en garantía");                                   
                    }
                });

            });

            $('#impuestosDiv').empty();
            $('.totalesDiv').empty();
            $('#pay').empty();
            var impuestosTotal = 0;
            $.each(data.taxes, function(index, val) {
                $('#impuestosDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><label>'+index+':</label></div>'+
                            '<div class="col-sm-6"><label>$'+parseFloat(val).toFixed(2)+'</label></div>'+
                            '</div>');   
                impuestosTotal += parseFloat(val);
            });
            $('#subtotalDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><h4>Subtotal:$'+Math.abs(parseFloat(data.total - impuestosTotal) ).toFixed(2)+'</h4></div>'+
                            '</div>');
            if(parseFloat(data.descuentoGeneral) > 0 ){
                $('#ddiv').append('<div class="row">'+
                            '<div class="col-sm-6"><h4>Descuento:$'+parseFloat(data.descuentoGeneral).toFixed(2)+'</h4></div>'+
                            '</div>');
            }
            $('#totalDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><h4>Total:$'+parseFloat(data.total).toFixed(2)+'</h4></div>'+
                            '</div>');

            /*$('#inputSubTotal').val(data.cargos.subtotal);
            $('#inputTotal').val(data.cargos.total); */
            $.each(data.pay, function(index, val) {
                $('#pay').append('<div class="row">'+
                            '<div class="col-sm-6"><label>'+val.nombre+':</label></div>'+
                            '<div class="col-sm-6"><label>$'+parseFloat(val.monto).toFixed(2)+'</label></div>'+
                            '</div>'); 
            });

            $('#modalVentasDetalle').modal({
                    show:true,
            });
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    },

    cancelaVenta : function(){
        var idVenta = $('#idVentaHidden').val();

         $.ajax({
            url: 'ajax.php?c=caja&f=puedeDevolverCancelar',
            type: 'GET',
            dataType: 'json',
        })
        .done(function(puedeDevolverCancelar) {
            var permiso = false;
            if ( !puedeDevolverCancelar ) {

                $('#modalPassCan').modal();
                
            } else {
                var r = confirm("¿Deseas cancelar la venta?");
                if (r == true) {
                    caja.mensaje('Procesando...');
                    var idVenta = $('#idVentaHidden').val();
                    $.ajax({
                        url: 'ajax.php?c=caja&f=cancelarVenta',
                        type: 'POST',
                        dataType: 'json',
                        data: {idVenta: idVenta},
                    })
                    .done(function(resca) {
                        console.log(resca);
                        caja.eliminaMensaje();
                        if(resca.estatus==true){
                            alert('Se Cancelo la Venta existosamente.');
                            
                            $('#modalVentasDetalle').modal('hide');
                            caja.ventasButtonAccion();
                        }
                        else {
                            alert("No se puede cancelar esta venta porque tiene factura activa");
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
                }
            }
            
            
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        

        
        
    },
    confirmaCancelacion: function (){
        $('#modalPassCan').modal('hide');
        password = $('#modPassCan').val();
        $('#modPassCan').val('');
        $.ajax({
            url: 'ajax.php?c=caja&f=autorizacionDevolverCancelar',
            type: 'GET',
            dataType: 'json',
            data: {password: password},
        })
        .done(function(autorizacion) {
            permiso = autorizacion;

            if ( permiso ) {//procesocancelacion

                var r = confirm("¿Deseas cancelar la venta?");
                if (r == true) {
                    caja.mensaje('Procesando...');
                    var idVenta = $('#idVentaHidden').val();
                    $.ajax({
                        url: 'ajax.php?c=caja&f=cancelarVenta',
                        type: 'POST',
                        dataType: 'json',
                        data: {idVenta: idVenta},
                    })
                    .done(function(resca) {
                        console.log(resca);
                        caja.eliminaMensaje();
                        if(resca.estatus==true){
                            alert('Se Cancelo la Venta existosamente.');
                            
                            $('#modalVentasDetalle').modal('hide');
                            caja.ventasButtonAccion();
                        }
                        else {
                            alert("No se puede cancelar esta venta porque tiene factura activa");
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
                }
            } else {
                alert("Contraseña incorrecta.")
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    },
    reImprimeticket: function (){
        var idVenta = $('#idVentaHidden').val();
        if($('#documento').val() == 4 ) {
            window.open("reciboPdf.php?idventa="+idVenta);
        } else {
            window.open("ticket.php?idventa="+idVenta);
        }
    },
    aplicaDescuento: function (){
        var descuento = $('#descuentoGeneral').val();

        if(descuento=='' || descuento < 1){
            alert('El descuento tiene que ser mayor a cero');
            return false;
        }
        if(descuento > 100){
            alert('El descuento no puede ser mayor al 100%.');
            return false;
        }   

            $.ajax({
                url: 'ajax.php?c=caja&f=configDatos',
                type: 'POST',
                dataType: 'json'
            })
            .done(function(resconfi) {
                console.log(resconfi);

                var limite  = resconfi[0].limit_global_p;
                var desc = descuento;
                
                if(parseFloat(limite) < parseFloat(desc)){
                    $('#modPass').val('');
                    $('#modalPassDes').modal();
                    var pass64 = btoa(resconfi[0].password);
                    $('#passhide').val(pass64);
                }else{
                     $.ajax({
                        url: 'ajax.php?c=caja&f=descuentoGeneral',
                        type: 'post',
                        dataType: 'json',
                        data: {descuento: descuento},
                    })
                    .done(function(data) {
                        console.log(data);
                        caja.pintaResultados(data);
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
                }   


            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

        

       /* $.ajax({
            url: 'ajax.php?c=caja&f=descuentoGeneral',
            type: 'post',
            dataType: 'json',
            data: {descuento: descuento},
        })
        .done(function(data) {
            console.log(data);
            caja.pintaResultados(data);
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        }); */
        
    },revisaRfc: function(){
        var rfc = $('#rfcMoldal').val();

        if(rfc==''){
            alert('Introduce un RFC.');
            return false;
        }
        caja.mensaje('Procesando...');
        $.ajax({
            url: 'ajax.php?c=caja&f=verificaRfcmodal',
            type: 'post',
            dataType: 'json',
            data: {rfc: rfc},
        })
        .done(function(data) {
            console.log(data);
            caja.eliminaMensaje();
            
            if(data.estatus==true){
            $('#gridHidden').show('slow');    
            $('.filasFormF').empty();
             $.each(data.datosFac, function(index, val) {
                $('#datosFactGrid tr:last').after('<tr class="filasFormF" id="filaId_'+val.id+'" >'+
                        '<td>'+val.rfc+'</td>'+
                        '<td>'+val.razon_social+'</td>'+
                        '<td>'+val.correo+'</td>'+
                        '<td>'+val.pais+'</td>'+
                        '<td>'+val.regimen_fiscal+'</td>'+
                        '<td>'+val.domicilio+'</td>'+
                        '<td>'+val.num_ext+'</td>'+
                        '<td>'+val.cp+'</td>'+
                        '<td>'+val.colonia+'</td>'+
                        '<td>'+val.estado+'</td>'+
                        '<td>'+val.municipio+'</td>'+
                        '<td>'+val.ciudad+'</td>'+
                        '<td><div style="float:left;"><button class="btn btn-success" type="button" onclick="caja.factButton('+val.id+');"><i class="fa fa-check" aria-hidden="true"></i></button></div></td>'+
                        '<td><div style="float:left;"><button class="btn btn-default" type="button" onclick="caja.edit('+val.id+');"><i class="fa fa-pencil" aria-hidden="true"></i></button></div></td>'+
                        '</tr>');
                                    
            });                 
            }else{
      
                $('#modalCuestion').modal({
                    show:true,
                });
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    },
    despliegaForm: function(){

        $('.formF').val('');

        $('#estadoFormF > option[value="0"]').attr('selected', 'selected');
        $('#municipioFormF > option[value="0"]').attr('selected', 'selected');
            
        $('#modalCuestion').modal('hide');
        $('#newOrUpd').empty();
        $('#newOrUpd').append('<span class="label label-default">Nuevo Registro</span>');


                var rfc2 = $('#rfcMoldal').val();
                $('#rfcFormF').val(rfc2);
                $('#rfcFormF').prop('readonly', true);
        $('#modalFormFact').modal({
            show:true,
        });
    },
    guardaFormF: function(){
        var idFac = $('#comFacId').val();
        var rfc = $('#rfcFormF').val();
        var razSoc = $('#razonSFormF').val();
        var email = $('#emailFormF').val();
        var pais = $('#paisFormF').val();
        var regimen = $('#regimenFormF').val();
        var domicilio = $('#domicilioFormF').val();
        var numero = $('#numeroFormF').val();
        var cp = $('#cpFormF').val();
        var col = $('#coloniaFormF').val();
        var estado = $('#estadoFormF').val();
        var municipio = $('#municipioFormF').val();
        var ciudad = $('#ciudadFormF').val();

        $('#but').hide();
        $('#butlo').show();
       
        $.ajax({
            url: 'ajax.php?c=caja&f=guardaClientFact',
            type: 'post',
            dataType: 'json',
            data: {idFac: idFac,
                    rfc: rfc,
                    razSoc: razSoc,
                    email : email,
                    pais : pais,
                    regimen : regimen,
                    domicilio : domicilio,
                    numero : numero,
                    cp : cp,
                    col : col,
                    estado : estado,
                    municipio : municipio,
                    ciudad : ciudad
                },
        })
        .done(function(datox) {
            console.log(datox);
            if(datox.estatus==true){
                caja.eliminaMensaje();
                $('#but').show();
                $('#butlo').hide();
                caja.revisaRfc();
                $('#modalFormFact').modal('hide');
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
        

    },
    edit: function(id){
        $.ajax({
            url: 'ajax.php?c=caja&f=datosFacturacionCliente',
            type: 'POST',
            dataType: 'json',
            data: {id: id},
        })
        .done(function(data) {
            console.log(data);
            $('#newOrUpd').empty();
            $('#newOrUpd').append('<span class="label label-warning">Editando</span>');
            $('#comFacId').val(data.Datafact[0].idFac);
            $('#rfcFormF').val(data.Datafact[0].rfc);
            $('#razonSFormF').val(data.Datafact[0].razon_social);
            $('#emailFormF').val(data.Datafact[0].correo);
            $('#paisFormF').val(data.Datafact[0].pais);
            $('#regimenFormF').val(data.Datafact[0].regimen_fiscal);
            $('#domicilioFormF').val(data.Datafact[0].domicilio);
            $('#numeroFormF').val(data.Datafact[0].num_ext);
            $('#cpFormF').val(data.Datafact[0].cp);
            $('#coloniaFormF').val(data.Datafact[0].colonia);
            $('#ciudadFormF').val(data.Datafact[0].ciudad);

            $('#estadoFormF > option[value="'+data.Datafact[0].idEstado+'"]').attr('selected', 'selected');
            $('#municipioFormF > option[value="'+data.Datafact[0].idMunicipio+'"]').attr('selected', 'selected');
            
            $('#modalFormFact').modal({
                show:true,
            });

        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    },    
    municipiosFact: function(){
        var estado = $('#estadoFormF').val();

            $.ajax({
                url: 'ajax.php?c=cliente&f=municipios',
                type: 'POST',
                dataType: 'json',
                data: {estado: estado},
            })
            .done(function(data) {
                console.log(data);
                $('#municipioFormF').empty();
                $.each(data, function(index, val) {
                    $('#municipioFormF').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                });
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
    },
    factButton: function(id){
        $('#codigoTicket').val('');
        $('#ticketDiv').attr({'src': ''});
        $('#facB').hide(); 
         $('#ticketHideDiv').hide();

        $('#idComunFactu').val(id);
        $('#modalCodigoVenta').modal({
            show:true,
        });
    },
    buscaTicket: function(){
        $('#ticketHideDiv').hide();
        var codigoTicket = $('#codigoTicket').val();
        if(codigoTicket==''){
            alert('Ingresa un codigo');
            return false;
        }
        src = "../../modulos/pos/ticket.php?idventa=" + codigoTicket + "&print=false";
        $('#ticketDiv').attr({'src': src});
        $('#ticketHideDiv').show('slow');
        $('#facB').show('slow');

    },
    prontipagosAccion: function(){
        var referencia = $('#pronti_referencia').val();
        var confirmarReferencia = $('#pronti_confirmar_referencia').val();
        var sku = $('#prodPronti').val();
        var monto = $("#pronti_monto").val(); //$('#prodPronti option:selected').attr('precio')
        var idProducto = $('#prodPronti option:selected').attr('idProducto');

        if(referencia!=confirmarReferencia){
            alert('Las referencias no coinciden');
            return false;
        }
        caja.mensaje('Procesando...');
        $.ajax({
            url: 'ajax.php?c=caja&f=enviaParaPronti',
            type: 'post',
            dataType: 'json',
            data: {referencia: referencia,
                    prodPronti: sku,
                    monto : monto
                    },
        })
        .done(function(resp) {
            console.log(resp);

            if(resp.respCode=='00'){
                alert('Transacción exitosa');
                $.ajax({
                    url: 'ajax.php?c=caja&f=guardaVentaPronti',
                    type: 'POST',
                    dataType: 'json',
                    data: { referencia: referencia,
                            idProducto : idProducto,
                            monto : monto},
                })
                .done(function() {
                    console.log("success");
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                }); 
                caja.eliminaMensaje();
            }else{
                if(resp.respCode !== undefined){
                    alert('Error! '+resp.respCode+' - '+resp.respMsj);
                } else {
                    alert("Un momento... " + resp.error);
                }
                caja.eliminaMensaje();
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    },
    factSale: function(){
        idComunFactu = $('#idComunFactu').val();
        venta = $('#codigoTicket').val();
        documento = 2;
        mensaje = '';
        consumo = '';
        caja.mensaje('Procesando...');
        $.ajax({
            url: 'ajax.php?c=caja&f=oneFact',
            type: 'POST',
            dataType: 'json',
            data: {idComunFactu: idComunFactu,
                    venta : venta
                },
        })
        .done(function(resp) {
            console.log(resp);
            caja.eliminaMensaje();
            if (resp.success == '500') {
                alert(resp.mensaje);
                window.location.reload();
                return false;
            }
            if (resp.success == '-1') {
                alert('Ha ocurrido un error durante el proceso de venta y facturacion.');
                window.location.reload();
                return false;
            }
                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                ================================================ */
                if (resp.success == 0 || resp.success == 5) {
                    if (resp.success == 0) {
                        alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                    }
                }
                if (resp.success == 1){
                    azu = JSON.parse(resp.azurian);
                    uid = resp.datos.UUID;
                    correo = resp.correo;
                    obser = azu.Observacion.Observacion;
                    logo = azu.org.logo;
                    $.ajax({
                        type: 'POST',
                        url: 'ajax.php?c=caja&f=guardarFacturacion',
                        dataType: 'json',
                        data: {
                            UUID: uid,
                            noCertificadoSAT: resp.datos.noCertificadoSAT,
                            selloCFD: resp.datos.selloCFD,
                            selloSAT: resp.datos.selloSAT,
                            FechaTimbrado: resp.datos.FechaTimbrado,
                            idComprobante: resp.datos.idComprobante,
                            idFact: resp.datos.idFact,
                            idVenta: resp.datos.idVenta,
                            noCertificado: resp.datos.noCertificado,
                            tipoComp: resp.datos.tipoComp,
                            trackId: resp.datos.trackId,
                            monto: (resp.monto),
                            cliente: 1268,
                            idRefact: 0,
                            azurian: resp.azurian,
                            doc: 2
                        },
                        beforeSend: function() {
                            if($('#documento').val() == 2)
                            {   $('#labelTF').text("Factura");
                                //$('#emailTicketHide').hide();
                                caja.mensaje("Guardando Factura");
                            }else if($('#documento').val() == 3)
                            {
                                caja.mensaje("Guardando Recibo de Ingresos");
                            }
                        },
                        success: function(resp) {
                           
                            caja.eliminaMensaje();
                            //window.open('../../modulos/facturas/'+uid+'.pdf');
                            $.ajax({
                                async: false,
                                type: 'POST',
                                url: 'ajax.php?c=caja&f=envioFactura',
                                dataType: 'json',
                                data: {
                                    uid: uid,
                                    correo: correo,
                                    azurian: azu,
                                    doc: $('#documento').val()
                                },
                                beforeSend: function() {
                                    //caja.mensaje("Enviando Factura");
                                },
                                success: function(resp) {
                                    ///Cierra los modales de facturacion , ticket y datos
                                    $('#modalFacturacion,').modal('hide');
                                    $('#modalCodigoVenta').modal('hide');
             
                                    caja.eliminaMensaje();
                                    if(resp.cupon==false){
                                        if($('#versionFacturacionHide').val() == '3.3'){
                                            caja.modalComprobante('../../modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo='+logo+'&id=temporales&caja=1&nominas=1&ob='+obser, false, uid);
                                            //caja.modalComprobante('../../modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo=f_de_foodware.png&id=temporales&caja=1&nominas=1&ob='+obser, false, uid);
                                        }else{
                                            caja.modalComprobante('../../modulos/facturas/'+uid+'.pdf', false, uid);
                                        }
                                    }else{
                                        caja.modalComprobante('../../modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false, uid);
                                    }
                                    caja.eliminaMensaje();
                                    //window.open('../../modulos/facturas/' + uid + '.pdf');
                                    //window.location.reload();
                                },
                                error: function() {
                                    caja.eliminaMensaje();
                                }
                            });

                            $("#loaderventa").hide();
                            $('#caja-dialog').modal('hide');
                           // $("#boton-pagar").removeAttr("disabled");
                            alert('Has registrado la venta con exito');
                            //window.location.reload();
                        },
                        error: function() {
                            caja.eliminaMensaje();
                        }
                    });
                }///fin del resp-success 1
                

        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    },
    basculaPeso:function(){
        /*$.ajax({
  type: "POST",
  url: "https://localhost/solicitar_peso.php",
       dataType: 'json',
  success: function(msg){
        alert( "Data Saved: " + msg );
  },
  error: function(jqXHR, textStatus, errorThrown) {
     console.log(jqXHR);
  }
});*/

        $.ajax({
            url: 'https://localhost/solicitar_peso.php',
            type: 'post',
            dataType: 'json',
            //data: {param1: 'value1'},
        })
        .done(function(resPeso) {
            console.log("success");
        
            $('#cantidad-producto').val(resPeso.peso);

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
        alert("1.- Verifica que la aplicación de la bascula este activa\n2.- Revisa que tienes permisos para acceder en tu navegador");  
        window.open('https://localhost/solicitar_peso.php');         

        })
        .always(function() {
            console.log("complete");
        });
        
    },
    inicioCaja: function(data)
    {   
        if (data.inicio !== undefined && data.inicio != false)
        {    
            $('#saldocajaInput').val('');
            $("#iniciocaja").val('');
            var contenedor = $('#divContSucursal');
            contenedor.empty();
            switch (data.inicio.status)
            {
                case 1:

                var sucursalOperando = $(document.createElement('label')).addClass('text-left control-label col-xs-7 pull-left').text('Sucursal que esta operando').appendTo(contenedor);
                var sucursalNombre = $(document.createElement('label')).addClass('text-left control-label col-xs-5').text(data.inicio.sucursalNombre).appendTo(contenedor);
                var sucursalid = $(document.createElement('hidden')).attr({'id': 'sucursalId'}).val(data.inicio.sucursalId).appendTo(contenedor);

                $('#lblSaldo').text('Saldo actual en caja');
                $('#saldocaja').text(data.inicio.saldo);
                var saldoInput = data.inicio.saldo.substr(1);

                $('#saldocajaInput').val(saldoInput.replace(',',''));


                break;

                case 2:

                var sucursalOperando = $(document.createElement('label')).addClass('text-left control-label col-xs-7 pull-left').text('Selecciona la sucursal que esta operando').appendTo(contenedor);
                var sucursales = $(document.createElement('select')).addClass('form-control').attr({'id': 'sucursalId'}).css({'width': '39%', 'margin-top': '2%'}).appendTo(contenedor);

                $.each(data.inicio.rows, function(index, val) {
                    var registrosSucursales = $(document.createElement('option')).attr({'value': val.id}).html(val.nombre).appendTo(sucursales);
                });


                    //$('#sucursalNombre').text(data.inicio.sucursalNombre);
                    $('#lblSaldo').text('Saldo actualmente en caja');
                    $('#saldocaja').text('$0.00');
                    break;
                }

                $('#inicio_caja').modal({backdrop: 'static'});
} else
{
    return false;
}
},  
cajaIniciar: function(){
        if ($("#iniciocaja").val() == "") {
            alert("Debes indicar con cuanto inicia caja, puede ser 0");
            return false;
        }
        if ($("#sucursalId").val() == "") {
            alert("Debes seleccionar que sucursal estas operando");
            return false;
        }
        var monto = parseFloat($("#iniciocaja").val()) + parseFloat($('#saldocajaInput').val());
        
        $.ajax({
            type: 'POST',
            url: 'ajax.php?c=caja&f=Iniciarcaja',
            data: {
                sucursal: $("#sucursalId").val(),
                monto: monto
            },
            success: function(resp) {
                $('#inicio_caja').modal("hide");
            }
        });//end ajax
    },
    changeMetProp: function(evt)
    {
        if($("#metodo_pago_propina").val() == 4 || $("#metodo_pago_propina").val() == 5){
            $("#divReferenciaPagoPro").show();
        } else {
            $("#divReferenciaPagoPro").hide();
        }
    },
    isNumberKey: function(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        return (charCode <= 13 || (charCode >= 48 && charCode <= 57) || charCode == 46);
    },
    muestraReferenciaPago: function(valor){
    
    var elemento = $('#divReferenciaPago');
    var elTexto = $('#lblReferencia');
    $('#txtReferencia').val('');

    elemento.css({'display': 'block'});

    switch (parseInt(valor))
    {
        case 2 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        $('#tarjetasRadios').hide();
        elTexto.text('Numero de cheque:');
        break;
        case 3:
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        $('#tarjetasRadios').hide();
        elTexto.text('Numero de tarjeta:');

        break;
        case 4:
        case 5:
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        $('#tarjetasRadios').show();
        elTexto.text('Numero de tarjeta:');
        break;
        case 6:
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Comentario:');
         $('#tarjetasRadios').hide();
        break;
        case 7 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Referencia transferencia:');
         $('#tarjetasRadios').hide();
        break;
        case 8 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Referencia spei:');
         $('#tarjetasRadios').hide();
        break;
        case 25 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Tarjeta de Vales:');
         $('#tarjetasRadios').hide();
        break;
        case 26 :
            //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
            caja.hazUnTruco();
        break;

        default :
        elemento.css({'display': 'none'});
        break;
    }
},
corteButtonAccion: function(){
    caja.mensaje('Procesando...');
    $('#desdeCut').val('');
    $('#hastaCut').val('');
    $('#desdeCutText').text('');
    $('#hastaCutText').text('');
    $('#saldo_inicial').val('');
    $('#monto_ventas').val('');
    $('#saldo_disponible').val('');
    $('#deposito_caja').val('');
    $('#retiro_caja').val('');
    $.ajax({
        url: 'ajax.php?c=caja&f=obtenCorte',
        type: 'post',
        dataType: 'json',
        data: {show: 0},
    })
    .done(function(resCor) {
        console.log(resCor);
        $('#desdeCut').val(resCor.desde);
        $('#hastaCut').val(resCor.hasta);
        $('#desdeCutText').text(resCor.desde);
        $('#hastaCutText').text(resCor.hasta);
        ///Llena la tabla de los pagos
        var cliente = '';
        var Efectivo =0; 
        var TCredito =0;
        var TDebito =0;
        var CxC =0;
        var Cheque  =0;
        var Trans =0; 
        var SPEI  =0;
        var TRegalo  =0;
        var Ni  =0;
        var cambio =0;
        var Impuestos =0;
        var Monto =0;
        var Importe = 0;
        var efectivoCambio2 = 0;
        var dess = 0;
        var TVales = 0;
        var Cortesia = 0;
        var Otros = 0;
        $('.cutRows').empty();
        $.each(resCor.ventas, function(index, val) {
            if(val.nombre==null){
                cliente = 'Publico General';
            }else{
                cliente = val.nombre;           
            }
            efectivoCambio = (val.Efectivo - val.cambio);
                    $('#gridPagosCut tr:last').after('<tr class="cutRows '+(val.estatus =="1" ? ( val.condevolucion ? "bg-warning" : "" ) : "bg-danger")+'">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+cliente+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>$'+val.Efectivo+'</td>'+
                                    '<td>$'+val.TCredito+'</td>'+
                                    '<td>$'+val.TDebito+'</td>'+
                                    '<td>$'+val.CxC+'</td>'+
                                    '<td>$'+val.Cheque+'</td>'+
                                    '<td>$'+val.Trans+'</td>'+
                                    '<td>$'+val.SPEI+'</td>'+
                                    '<td>$'+val.TRegalo+'</td>'+
                                    '<td>$'+val.Ni+'</td>'+
                                    '<td>$'+val.TVales+'</td>'+
                                    '<td>$'+val.Cortesia+'</td>'+
                                    '<td>$'+val.Otros+'</td>'+
                                    '<td>$'+val.cambio+'</td>'+
                                    '<td>$'+val.Impuestos+'</td>'+
                                    '<td>$'+val.Monto+'</td>'+
                                    '<td>$'+parseFloat(val.descuentoGeneral).toFixed(2)+'</td>'+
                                    '<td>$'+val.Importe+'</td>'+
                                    '<td>$'+parseFloat(efectivoCambio).toFixed(2)+'</td>'+
                                    '</tr>');
                    Efectivo +=  parseFloat(val.Efectivo);
                    TCredito += parseFloat(val.TCredito);
                    TDebito += parseFloat(val.TDebito);
                    CxC += parseFloat(val.CxC);
                    Cheque += parseFloat(val.Cheque);
                    Trans += parseFloat(val.Trans);
                    SPEI += parseFloat(val.SPEI);
                    TRegalo +=parseFloat(val.TRegalo);
                    Ni += parseFloat(val.Ni);
                    cambio += parseFloat(val.cambio);
                    Impuestos += parseFloat(val.Impuestos);
                    Monto += parseFloat(val.Monto);
                    Importe += parseFloat(val.Importe);
                    efectivoCambio2 += parseFloat(efectivoCambio); 
                    dess += parseFloat(val.descuentoGeneral);
                    TVales += parseFloat(val.TVales);
                     Cortesia += parseFloat(val.Cortesia);
                     Otros += parseFloat(val.Otros);
        });     
                $('#gridPagosCut tr:last').after('<tr class="cutRows">'+
                                    '<td colspan="3">Totales</td>'+
                                   
                                    '<td>$'+Efectivo.toFixed(2)+'</td>'+
                                    '<td>$'+TCredito.toFixed(2)+'</td>'+
                                    '<td>$'+TDebito.toFixed(2)+'</td>'+
                                    '<td>$'+CxC.toFixed(2)+'</td>'+
                                    '<td>$'+Cheque.toFixed(2)+'</td>'+
                                    '<td>$'+Trans.toFixed(2)+'</td>'+
                                    '<td>$'+SPEI.toFixed(2)+'</td>'+
                                    '<td>$'+TRegalo.toFixed(2)+'</td>'+
                                    '<td>$'+Ni.toFixed(2)+'</td>'+
                                    '<td>$'+TVales.toFixed(2)+'</td>'+
                                    '<td>$'+Cortesia.toFixed(2)+'</td>'+
                                    '<td>$'+Otros.toFixed(2)+'</td>'+
                                    '<td>$'+cambio.toFixed(2)+'</td>'+
                                    '<td>$'+Impuestos.toFixed(2)+'</td>'+
                                    '<td>$'+Monto.toFixed(2)+'</td>'+
                                    '<td>$'+dess.toFixed(2)+'</td>'+
                                    '<td style="background-color: #FFCCDD;">$'+Importe.toFixed(2)+'</td>'+
                                    '<td style="background-color: #a9f5a9;">$'+efectivoCambio2.toFixed(2)+'</td>'+
                                    '</tr>');
        ///Lena la tabla de tarjetas        
        $.each(resCor.tarjetas, function(index, val) {
        $('#gridTarjetas tr:last').after('<tr class="cutRows">'+
                                    '<td>'+val.tarjeta+'</td>'+
                                    '<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                    '</tr>');     
        });
        ///Llena la tabla de los productos
        var Cantidad = 0;
        var Descuento = 0;
        var Impuestos2 = 0;
        var Subtot = 0;
        $.each(resCor.productos, function(index, val) {
                    $('#gridProductosCut tr:last').after('<tr class="cutRows">'+
                                    '<td>'+val.codigo+'</td>'+
                                    '<td>'+val.nombre+'</td>'+
                                    '<td align="center">'+val.Cantidad+'</td>'+
                                    '<td>$'+val.preciounitario+'</td>'+
                                    '<td>$'+val.Descuento+'</td>'+
                                    '<td>$'+val.Impuestos+'</td>'+
                                    '<td>$'+val.Subtot+'</td>'+
                                    '</tr>');
                    //alert('antes='+val.Subtot);
                    Cantidad += parseFloat(val.Cantidad);
                    Descuento += parseFloat(val.Descuento);
                    Impuestos2 += parseFloat(val.Impuestos2);
                    Subtot += parseFloat(val.Subtot); 
                    //alert('sumado='+Subtot);
        }); 
                            $('#gridProductosCut tr:last').after('<tr class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+                  
                                    '<td>$'+Descuento.toFixed(2)+'</td>'+
                                    '<td>$'+Impuestos.toFixed(2)+'</td>'+
                                    '<td style="background-color: #FFCCDD;">$'+Subtot.toFixed(2)+'</td>'+
                                    '</tr>'); 
        ///Llena la Tabla de retiros
        var cantidad3 = 0;
        $.each(resCor.retiros, function(index, val) {
                    $('#gridRetirosCut tr:last').after('<tr idRetiro="'+val.id+'" class="cutRows">'+
                                    '<td>'+val.id+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>'+val.concepto+'</td>'+
                                    '<td>'+val.usuario+'</td>'+
                                    '<td>$'+val.cantidad+'</td>'+
                                    
                                    '</tr>');
                    cantidad3 += parseFloat(val.cantidad);
        }); 
           $('#gridRetirosCut tr:last').after('<tr class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
                                    '<td style="background-color: #FFCCDD;">'+cantidad3.toFixed(2)+'</td>'+
                                    '</tr>');
        ///Llena la Tabla de Abonos
        var cantidad4 = 0;
        $.each(resCor.abonos, function(index, val) {
                    $('#gridAbonosCut tr:last').after('<tr idRetiro="'+val.id+'" class="cutRows">'+
                                    '<td>'+val.id+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>'+val.concepto+'</td>'+
                                    '<td>'+val.usuario+'</td>'+
                                    '<td>$'+val.cantidad+'</td>'+
                                    
                                    '</tr>');
                    cantidad4 += parseFloat(val.cantidad);
        }); 
           $('#gridAbonosCut tr:last').after('<tr class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
                                    '<td style="background-color: #A9F5A9;">'+cantidad4.toFixed(2)+'</td>'+
                                    '</tr>');

        ///Llena la Tabla de Propinas
        var cantidad5 = 0;
        $.each(resCor.propinas, function(index, val) {
                    $('#gridPropinasCut tr:last').after('<tr idRetiro="'+val.id_venta+'" class="cutRows">'+
                                    '<td>'+val.id_venta+'</td>'+
                                    '<td>'+val.total+'</td>'+
                                    
                                    '</tr>');
                    cantidad5 += parseFloat(val.total);
        }); 
           $('#gridPropinasCut tr:last').after('<tr class="cutRows">'+
                                    '<td >Totales</td>'+
                                    '<td style="background-color: #A9F5A9;">'+cantidad5.toFixed(2)+'</td>'+
                                    '</tr>');

        ///Llena la Tabla de Devoluciones
        var cantidad6 = 0;
        $.each(resCor.devoluciones, function(index, val) {
                    $('#gridDevolucionesCut tr:last').after('<tr idRetiro="'+val.id_ov+'" class="cutRows">'+
                                    '<td>'+val.id_ov+'</td>'+
                                    '<td>'+val.total+'</td>'+
                                    
                                    '</tr>');
                    cantidad6 += parseFloat(val.total);
        }); 
           $('#gridDevolucionesCut tr:last').after('<tr class="cutRows">'+
                                    '<td >Totales</td>'+
                                    '<td style="background-color: #ffccdd;">'+cantidad6.toFixed(2)+'</td>'+
                                    '</tr>');

        ///Llena la Tabla de Cancelaciones
        var cantidad7 = 0;
        $.each(resCor.cancelaciones, function(index, val) {
                    $('#gridCancelacionesCut tr:last').after('<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.monto+'</td>'+
                                    
                                    '</tr>');
                    cantidad7 += parseFloat(val.monto);
        }); 
           $('#gridCancelacionesCut tr:last').after('<tr class="cutRows">'+
                                    '<td >Totales</td>'+
                                    '<td style="background-color: #ffccdd;">'+cantidad7.toFixed(2)+'</td>'+
                                    '</tr>');

        ///Llena la Tabla de Facturas
        var cantidad8 = 0;
        $.each(resCor.facturas, function(index, val) {
                    $('#gridFacturasCut tr:last').after('<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.monto+'</td>'+
                                    
                                    '</tr>');
                    cantidad8 += parseFloat(val.monto);
        }); 
           $('#gridFacturasCut tr:last').after('<tr class="cutRows">'+
                                    '<td >Totales</td>'+
                                    '<td style="background-color: #A9F5A9;">'+cantidad8.toFixed(2)+'</td>'+
                                    '</tr>');





        $('#saldo_inicial').val(resCor.montoInical);
        //$('#monto_ventas').val(resCor.monto_ventas);
        $('#monto_ventas').val(resCor.ventas_total);
        $('#saldo_disponible').val(resCor.saldoDisponible);
        
        caja.eliminaMensaje();
        $('#modalCorteDeCaja').modal({
            show:true,
        });
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    

},
newCut: function(){
   var fecha_inicio = $('#desdeCut').val();
   var fecha_final = $('#hastaCut').val();
   var inicial =  $('#saldo_inicial').val();
   var montoVentas = $('#monto_ventas').val();
   var disponible = $('#saldo_disponible').val();
   var retiroCaja = $('#retiro_caja').val();
   var deposito = $('#deposito_caja').val();
   var retiros = '';
   var arqueo = caja.obtenerDatosArqueo();
   var tipoCorte = $('#tipoCorte').val();
    
    if(montoVentas==''){
        alert('Tienes que tener al menos una venta para realizar el corte.');
        return false;
    }
    if(parseFloat(retiroCaja) > parseFloat(disponible)){
        alert('No puedes retirar mas de los disponible.');
        return false;
    }
    if(montoVentas==''){
        montoVentas=0;
    }
    $("#gridRetirosCut tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        idRetiro = $(this).attr('idRetiro');
        retiros += idRetiro+'-';
    });
    caja.mensaje('Procesando...');
    $.ajax({
        url: 'ajax.php?c=caja&f=crearCorte',
        type: 'POST',
        dataType: 'json',
        data: {fecha_inicio: fecha_inicio,
                fecha_fin : fecha_final,
                saldo_inicial : inicial,
                monto_ventas : montoVentas,
                saldo_disponible : disponible,
                retiro_caja : retiroCaja,
                deposito_caja : deposito,
                retiros : retiros,
                arqueo : arqueo,
                tipoCorte : tipoCorte
        },
    })
    .done(function(resCorte) {
        console.log(resCorte);
        if(resCorte.idCorte!=''){
            alert('Se Realizo el corte con Exito');
            caja.eliminaMensaje();
            $('#modalCorteDeCaja').modal('hide');
            //caja.init();

            caja.enviaCortePdf(resCorte.idCorte);

            if(resCorte.configPrint == 1){
                window.open("corteImpresoTicket.php?corte="+resCorte.idCorte);
            }
            var pathname = window.location.pathname;
            window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=caja&f=indexCaja2';

        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    


}, 
buscarVenta: function(){
    var idVenta = $('#inputidVenta').val();
    if(idVenta==''){
        alert('Ingresa un id de Venta');
        return false;
    }
        caja.mensaje('Procesando...');
        $.ajax({
            url: 'ajax.php?c=caja&f=buscaVentaCaja',
            type: 'post',
            dataType: 'json',
            data: {idVenta: idVenta},
        })
        .done(function(resVen) {
            console.log(resVen);
            var table = $('#tableSales');
    
            $('.filas').empty();
            //table.clear().draw();
            var x ='';
            var estatus = '';
            $.each(resVen.venta, function(index, val) {
                if(val.estatus=='Activa'){
                    estatus = '<span class="label label-success">Activa</span>';
                }else{
                    estatus = '<span class="label label-danger">Cancelada</span>';
                }

                if(val.documento==1){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                        docu = 'Ticket Facturado('+xlink+')';
                    }else{
                        docu = 'Ticket';
                    }
                    
                }else if(val.documento==2){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Factura('+xlink+')';
                }else if(val.documento==4){
                    docu = 'Recibo de pago';
                }else if(val.documento==5){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Recibo de Honorarios('+xlink+')';
                } 

                x ='<tr class="filas">'+
                                '<td>'+val.folio+'</td>'+
                                '<td>'+docu+'</td>'+
                                '<td>'+val.fecha+'</td>'+
                                '<td>'+val.cliente+'</td>'+
                                '<td>'+val.empleado+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                                '<td>'+estatus+'</td>'+
                                '<td>$'+parseFloat(val.iva).toFixed(2)+'</td>'+
                                '<td>$'+parseFloat(val.monto).toFixed(2)+'</td>'+
                                '<td><button class="btn btn-default btn-block" onclick="caja.ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                '</tr>';  
                    //table.row.add($(x)).draw();  
                    table.append(x);                   
            }); 
            caja.eliminaMensaje();
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    
},

enviarRecibo: function(){
    var emailTicket = $('#emailTicket').val();
    var idVenta = $('#idVentaTicket').val();

    // Expresion regular para validar el correo
    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

    // Se utiliza la funcion test() nativa de JavaScript
    if (regex.test(emailTicket.trim())) {
        caja.mensaje('Enviando Recibo...');
        $.ajax({
            url: 'ajax.php?c=caja&f=enviarRecibo',
            type: 'POST',
            dataType: 'json',
            data: {idVenta : idVenta,
                    correo : emailTicket},
        })
        .done(function(result) {
            console.log(result);
            if(result.estatus==true){
                caja.eliminaMensaje();
                alert('Se envio al correo Electronico');
            }
        })
        .fail(function() {
            console.log("error");
            alert("Existe un error interno, no es posible enviar la factura");
            caja.eliminaMensaje();
        })
        .always(function() {
            console.log("complete");
        });

    } else {
        alert('La direccón de correo no es valida');
        return false;
    }

    
},

enviarTicket: function(){
    var emailTicket = $('#emailTicket').val();
    var idVenta = $('#idVentaTicket').val();
    var enviarR = $('#inputRecibo').val();
    var asunto = $('#asuntoTicket').val();
    var mensaje = $('#mensajeTicket').val();


    // Expresion regular para validar el correo
    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
    // Se utiliza la funcion test() nativa de JavaScript
    if (regex.test(emailTicket.trim())) {
        caja.mensaje('Enviando...');

        if (enviarR == 4) {
            caja.eliminaMensaje();
            caja.enviarRecibo();
        } else {
            $.ajax({
                url: 'ajax.php?c=caja&f=enviarTicket',
                type: 'POST',
                dataType: 'json',
                data: {idVenta : idVenta,
                        correo : emailTicket,
                        asunto : asunto,
                        mensaje : mensaje
                    },
            })

            .done(function(result) {
                console.log(result);
                if(result.estatus==true){
                    caja.eliminaMensaje();
                    alert('Se envio al correo Electronico');
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        }

    } else {
        alert('La direccón de correo no es valida');
        return false;
    }
},


agregaCarac: function(){
    //alert('entro');
    var a = '';
    var idProducto = $('#carIdProddiv').val();
    $(".recr").each(function() {
        a += $(this).val()+'*';
    }); 
    var series = $('#series').val();
    //alert()
    /*if(series.length > 0){
        $('#cantidad-producto').val(series.length);
    } */
  
                    //existencias=$('#existencias').val();
                    modalcantrecibida=0;
                    cantsexistencias='';
                    $( ".quantity" ).each(function( index ) {
                        modalcantrecibida=modalcantrecibida+($(this).val()*1);
                        cantsexistencias+=$(this).attr('data')+'-'+$(this).val()+',';
                    });
     //alert(modalcantrecibida); 
     if(modalcantrecibida > 0){
        $('#cantidad-producto').val(modalcantrecibida);
     }             
      //alert(cantsexistencias);
    //var lotes = cantsexistencias;                
   /* var can1 = $('#cantidad-producto').val();
    var can = $('#exiCaracInput').val();
    if(parseFloat(can1)>parseFloat(can)){
        alert('No tienes la existencia suficiente.');
        return false;
    } */
    //alert(series);
    $('#modalCarac').modal('hide');

    // var i = 0, strLength = caja.caracteristicasAutomaticas.length;
    // for(i; i < strLength; i++) {
    //  caja.caracteristicasAutomaticas = caja.caracteristicasAutomaticas.replace("H", "=>");
    //  caja.caracteristicasAutomaticas = caja.caracteristicasAutomaticas.replace("P", "*");
    // }
    caja.agregaProducto(idProducto,a,series,cantsexistencias);
},
buscaCaracteristicas: function (id,seriesx='',print){
    //alert(id);
     $('#seriesDiv').hide();
     $('#lotesDiv').hide();
//caja.mensaje('Procesando...');
    $.ajax({
        url: 'ajax.php?c=caja&f=obtenCaracteristicas',
        type: 'POST',
        dataType: 'json',
        data: {id: id,
            cantidad: $('#cantidad-producto').val()},
    })
    .done(function(result) {
        console.log(result);
                if(result.tieneCar > 0) {
                    if(result.seriesSi == 1 || result.lotesSi == 1 ) $('#cantidad-producto').val(0);
                        $('#prodCarcDiv').empty();
                        $('#newlotes').empty();
                        $('#series').empty();
                        $('#lotes').empty();
                        var contenido = '';

var i = 0, strLength = caja.caracteristicasAutomaticas.length;
for(i; i < strLength; i++) {
 caja.caracteristicasAutomaticas = caja.caracteristicasAutomaticas.replace("H", "=>");
 //caja.caracteristicasAutomaticas = caja.caracteristicasAutomaticas.replace("P", "*");
}
caracteristicasDeProducto = (caja.caracteristicasAutomaticas).split("P");
                        $.each(result.cararc, function(index, val) {
                             //alert(index);
                             contenido += '<div class="row">';
                             //contenido += '<div class="col-sm-6">';
                             //contenido +='</div>';
                             contenido += '<div class="col-sm-12">';
                             contenido +' <label>'+index+'</label>';
                             contenido += '<select class="form-control recr xselc" onchange="caja.getExisCara();">';
                             $.each(val, function(index2, val2) {
                                  contenido +='<option value="'+val2.id_caracteristica_padre+'=>'+val2.id+'"'+( ( $.inArray(val2.id_caracteristica_padre+'=>'+val2.id , caracteristicasDeProducto) != -1 ) ? "selected" : "")+' >'+val2.nombre+'</option>';
                             });
                             contenido +='</select>';
                             contenido +='</div></div>';
                            $('.xselc').select2({ width: '100%' });
                        });
                   
                        contenido += '<div class="row"><div class="col-sm-6">';
                        contenido +='<label>Existencia:</label></div>';
                        contenido +='<div class="col-sm-6">';
                        contenido +='<label id="exiCaracText"></label>';
                        contenido +='<input type="hidden" id="exiCaracInput">';
                        contenido +='</div></div>';

                        $('#carIdProddiv').val(id);
                        $('#prodCarcDiv').append(contenido);
                        // $('#modalCarac').modal({
                        //     show:true,
                        // }); 

                        $('#divImagenPro').attr("src", '../pos/'+result.imagen);
                        $('#modal-labelCr').text(result.nombreProd);


                        if(result.seriesSi == 1){
                                $('#seriesDiv').show();
                                $.each(result.series, function(index, val) {
                                     $('#series').append('<option value="'+val.idSerie+'-'+val.serie2+'" disp="'+val.serie2+'">'+val.serie2+'</option>')
                                });
                                var xy = seriesx.split(',')
                                $.each(xy, function(index, val) {
                                    if(val!=''){
                                        $( "#series option[disp='"+val+"']" ).prop("selected",true);
                                    }
                                });
                            $("#series").select2({width:'100%'});
        $('#exiCaracInput').val(result.series.length);
        $('#exiCaracText').text(result.series.length);
                        }
                        else if(result.lotesSi == 1){
                                $('#lotesDiv').show();
                                var cantLot = 0;
                                $.each(result.lotes, function(index, val) {
                                     $('#lotes').append('<option value="'+val.idLote+'">'+val.numero+'('+val.cantidad+')</option>');
                                     cantLot += parseFloat( val.cantidad );
                                });
        $('#exiCaracInput').val(cantLot);
        $('#exiCaracText').text(cantLot);
                            $("#lotes").select2({width:'100%'});

                        }else {
                            caja.getExisCara();
                        }
                        

                        
//caja.eliminaMensaje();
                        //alert('prueba');

                        salir = 1;

                             $('.xselc').select2({ width: '100%' });
                        //alert('salir1='+salir);
                        ////lotes
                       /*     var contenido2 = '';
                            var options='';
                            $.each(result.lotes, function( k, v ) {
                                alert(v.idLote);
                                options+='<option value="'+v.idLote+'">'+v.numero+' ('+v.cantidad+')</option>';
                            });

                            contenido2 += '<div class="row"><div class="col-sm-6">';
                            contenido2 +='<label>Prosucto lote</label></div>';
                            contenido2 += '<div class="col-sm-6">';
                            contenido2 +='<select id="lotes" multiple="" class="selectpicker">';
                            contenido2 +=options+'</select>';
                            contenido2 +='</div></div>';
                        $('#prodCarcDiv').append(contenido2);
                        $('#modalCarac').modal({
                            show:true,
                        });
                        $('#lotes').select2({width : '100%'}); */
if( result.seriesSi != 1 && result.lotesSi != 1 ){

    if(caja.caracteristicasAutomaticas == '') {
        //alert('entro al if');
        $('#modalCarac').modal('show');
    } 
    else {
        //alert('entro al else');
        $('#btnAgregarProductoCaracteristicas').trigger('click');
        $('#search-producto').val("").typeahead('clearHint');
    }
}
else {
    $('#modalCarac').modal('show');
} 
                }else{
                    //alert('entro aqui');
                    caja.agregaProducto(id,'','','',print);
                }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
},
buscaProdCoin: function(){
    var moneda = $('#monedaVenta').val();
    caja.mensaje('Procesando...');
    $.ajax({
        url: 'ajax.php?c=caja&f=productosMoneda',
        type: 'POST',
        dataType: 'json',
        data: {coin: moneda},
    })
    .done(function(resmon) {
        console.log(resmon);
        caja.eliminaMensaje();
        //alert(resmon.respuesta);
        if(resmon.respuesta > 0){
            $('#containerTouch').empty();
            var nombre = '';
            var btnContent = '';
            var contador = 1;
            $.each(resmon.productos, function(index, val) {
               
                if(val.descripcion_corta!=''){
                    nombre = val.descripcion_corta;
                }else{
                    nombre = val.nombre;
                } 

                    btnContent += '<div class="pull-left" style="padding:2px;">';
                    btnContent += '  <button class="btn btn-default" codigoProTouch="'+val.codigo+'" onclick="caja.agregaProTouch(this)">';
                    btnContent += '    <div class="row">';
                    btnContent += '       <div style="width:90px;" class="wrapPro">';
                    btnContent += '          <label>'+nombre.substr(0,10)+'</label>';
                    btnContent += '       </div>';
                    btnContent += '    </div>';
                    btnContent += '    <div class="row">';
                    btnContent += '      <div style="height:70px; width:100px;">';
                    btnContent += '          <img src="'+val.ruta_imagen+'" alt="" style="height:70px; width:90px;">';
                    btnContent += '      </div>';
                    btnContent += '    </div>';
                    btnContent += '    <div class="row">';
                    btnContent += '      <label>$'+parseFloat()+'</label>';
                    btnContent += '    </div>';
                    btnContent += '  </button>';
                    btnContent += '</div>'; 
                    
                    contador++; 
            }); 
                $('#containerTouch').append(btnContent);
        }else{
            alert('No se encontraron productos asosciados a esa moneda.');
        }

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
},
selCliente: function(){
    var cliente = $('#cliente-caja').val();
    var clienteLista = $('#listaDePreciosClient').val();
    var clienteAnterior = $('#hidencliente-caja').val();
  alert(clienteAnterior);
    if(clienteLista > 0 && $('#totalDeProductosInput').val() > 0){
        alert('Estas cambiado al cliente, Tienes que borrar los productos');
        $('#cliente-caja option[value="'+clienteAnterior+'"]').attr('selected','selected');
        //$('#cliente-caja option:eq('+clienteAnterior+')').prop('selected', true)
        return false;
    }
    alert('le valio');
    $('#hidencliente-caja').val(cliente);
    caja.checatimbres(cliente);
},
getExisCara: function(){
    var a = '';
    var idProducto = $('#carIdProddiv').val();
    $(".recr").each(function() {
        a += $(this).val()+',';
    });
 
    $.ajax({
        url: 'ajax.php?c=caja&f=getExisCara',
        type: 'post',
        dataType: 'json',
        data: { a : a,
                producto : idProducto},
    })
    .done(function(respExisCar) {
       $('#exiCaracInput').val(respExisCar.cantidadExis);
       $('#exiCaracText').text(respExisCar.cantidadExis);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    }); 
    
},
descuentoParcial: function (id){
    $('#descProdUpdate').val('');
    $.ajax({    
        url: 'ajax.php?c=caja&f=getInfoProducto',
        type: 'post',
        dataType: 'json',
        data: {id: id},
    })
    .done(function(respDesC) {
        console.log(respDesC);
        $('#xProParc').val(id);
        $('#encabezadoNombre').text(respDesC.nombre);
        $('#encabezadoPrecio').text('$'+parseFloat(respDesC.precio).toFixed(2));
        $('#encabezadoPrecioInput').val(parseFloat(respDesC.precio).toFixed(2));
        $('#encabezadoImporte').text('$'+parseFloat(respDesC.importe).toFixed(2));
        $('#encabezadoImporteInput').val(parseFloat(respDesC.importe).toFixed(2));
        $("#tipoDescu option[value='N']").attr("selected",true);
        $("#tipoDescu").val("N");
        $('#descProdUpdate').val(respDesC.nombre);
        if(respDesC.edicion==0){
            $('#edicionProd').hide();
        }else{
            $('#edicionProd').show();
        }
        $('#selectListaPrecios').empty();
        listaPrecios = "";
        listaPrecios += `<option value="${parseFloat(respDesC.precioBaseLista).toFixed(2)}">${parseFloat(respDesC.precioBaseLista).toFixed(2)}</option>`;
        $(respDesC.listaPrecio).each(function(index, el) {
            descuento = (respDesC.precioBaseLista * el['porcentaje'] / 100);
            if( el['tipo'] == "2" ){
                precio = el['precio'];
            }else {
                precio = (el['descuento'] == 1) 
                ? (parseFloat(respDesC.precioBaseLista) - descuento)
                : (parseFloat(respDesC.precioBaseLista) + descuento);
            }
            listaPrecios += `<option value="${parseFloat(precio).toFixed(2)}">${el['nombre']} / ${parseFloat(precio).toFixed(2)}</option>`;
        });
        $('#selectListaPrecios').append(listaPrecios);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });

    $.ajax({
        url: 'ajax.php?c=caja&f=configDatos',
        type: 'POST',
        dataType: 'json'
    })
    .done(function(result) {
        console.log(result);
        $('#limite_porcentaje').val(result[0].limit_sin_pass_p);
        $('#limite_cantidad').val(result[0].limit_sin_pass_c);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    }); 
    
    
    $('#desCantidad').val('');
    $('#modalDescParcial').modal();
    
},
changeListaPrecio: function(){
    $('#encabezadoPrecio').text('$' + parseFloat( $('#selectListaPrecios').val() ).toFixed(2) );
    $('#encabezadoImporte').text('$' + (parseFloat( $('#selectListaPrecios').val() ).toFixed(2) * parseFloat($(`[cant2=${$('#xProParc').val().split('=>').join('H').split('*').join('P')}]` ).val() ) )  );
},
changeTipoDescuento: function(){
    if($('#tipoDescu').val() == 'N' || $('#tipoDescu').val() == 'C')
        $('#desCantidad').val("").attr('disabled','disabled');
    else
        $('#desCantidad').val("0").removeAttr('disabled');
},
resetModalDescuento: function(){
    $('#desCantidad').val("0").removeAttr('disabled');
    $('#tipoDescu').val('%');
},
aplicaDesParcial: function(){
    var id = $('#xProParc').val();
    var cantidad = $('#desCantidad').val();
    var tipoDes = $('#tipoDescu').val();
    var pre = $('#encabezadoImporteInput').val();
    var nombre = $('#descProdUpdate').val();

    if(parseFloat(cantidad) < 0){
        alert('La cantidad debe ser mayor a cero');
        return false;
    }

    if(tipoDes=='%'){
        if(parseFloat(cantidad) > 100 ){
            alert('El descuento no puede ser mayor al 100%');
            return false;
        }
    }
    if(tipoDes=='$'){
        if(parseFloat(cantidad) > parseFloat(pre)){
            alert('El descuento no puede ser mayor al precio del producto');
            return false;
        }
    } 

    caja.mensaje('Procesando...');
    $.ajax({
        url: 'ajax.php?c=caja&f=cambiaCantidad',
        type: 'POST',
        dataType: 'json',
        data: {id: id,
               cantidad : cantidad,
               tipo : tipoDes,
               nombre : nombre
            },
    })
    .done(function(data) {
        console.log(data);
        caja.data = data;
        caja.pintaResultados(data, false);
        $('#modalDescParcial').modal('hide');
        caja.eliminaMensaje();
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
},enviaCortePdf: function(idCorte){

    $.ajax({
        url: 'ajax.php?c=caja&f=enviaCortePdf',
        type: 'POST',
        dataType: 'json',
        data: {idCorte: idCorte},
    })
    .done(function(respEn) {
        console.log(respEn);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
  	
},cargarMas: function(){
    var rango = $('#rango').val(),
        departamento = $('#selectDepartamento').val(),
        familia = $('#selectFamilia').val(),
        linea = $('#selectLinea').val();

   caja.mensaje('Procesando...' + rango);
    $.ajax({
        url: 'ajax.php?c=caja&f=cargarMas',
        type: 'post',
        dataType: 'json',
        data: { departamento: departamento,
                familia : familia,
                linea : linea,
                rango: rango 
            }
    })
    .done(function(resp) {
        console.log(resp);
        var y = parseFloat(rango);
        var x = y + 100;
        $('#rango').val(x);
        var nombre = '';
        $('#botonCarga').remove();
        $.each(resp, function(index, val) {
            if (val.tipo_producto!=3) {
                if(val.descripcion_corta!=''){
       
                    nombre = val.descripcion_corta;  
                }else{
                    nombre = val.nombre;
                }

                $('#containerTouch').append(`
                    <div class="pull-left itemsProds" codigoProTouch="${val.codigo}" onclick="caja.agregaProTouch(this)" style="background-image: url('${val.ruta_imagen}');">
                        <label class="labelItemPrice">$${parseFloat(val.precio).toFixed(2)}</label>
                        <label class="labelItemName">${nombre.toLowerCase()}</label>
                    </div>
                `);
                
            } 
        });
        $('#containerTouch').append('<div class="row" id="botonCarga"><div class="col-sm-12"><button class="btn btn-default" onclick="caja.cargarMas();">Cargar mas</button></div></div>');
        caja.eliminaMensaje();

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
},validaPassDes: function(){
    var pass = $('#modPass').val();
    var passh = atob($('#passhide').val());
    var descuento = $('#descuentoGeneral').val();
    if(pass==passh){
        caja.mensaje('Procesando...');
         $.ajax({
            url: 'ajax.php?c=caja&f=descuentoGeneral',
            type: 'post',
            dataType: 'json',
            data: {descuento: descuento},
        })
        .done(function(data) {
            console.log(data);
            caja.pintaResultados(data);
            $('#modalPassDes').modal('hide');
            caja.eliminaMensaje();
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        }); 

    }else{
        alert('El password/contraseña es incorrecta.');
        $('#modPass').focus();
        return false;
    }

},validaPassDesPP: function(){
    var tipoDescu = $('#tipoDescu').val();
    var cantidad = $('#desCantidad').val();
    var lPorcentaje = $('#limite_porcentaje').val();
    var lCantidad = $('#limite_cantidad').val();

    switch(tipoDescu) {
    case '%':
            if(parseFloat(lPorcentaje) < parseFloat(cantidad)){
                caja.hazUnTruco();
            }else{
                caja.aplicaDesParcial();
            }
        break;
    case '$':
           
            if(parseFloat(cantidad) > parseFloat(lCantidad)){
                caja.hazUnTruco();
            }else{
                caja.aplicaDesParcial();
            }
        break;
    case 'C':
        
                caja.hazUnTruco();
        break;    
    case 'N':
                caja.hazUnTruco3();
        break;
    default:
        alert('Selecciona un tipo de decuento.');
        return false;
} 



   /* var pass = $('#modPass').val();
    var passh = atob($('#passDesc').val());
    var descuento = $('#descuentoGeneral').val();

    if(pass==passh){
        caja.mensaje('Procesando...');

    }else{
        alert('El password/contraseña es incorrecta.');
        $('#modPass').focus();
        return false;
    } */
},hazUnTruco : function(){

        $.ajax({
            url: 'ajax.php?c=caja&f=configDatos',
            type: 'POST',
            dataType: 'json'
        })
        .done(function(result) {
            console.log(result);
            $('#modPass2').val('');
            $('#contrasenaPP').val(btoa(result[0].password));
            $('#modalPassDesPP').modal();
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
},hazUnTruco2 : function(){
    var pass = $('#modPass2').val();
    var passh = atob($('#contrasenaPP').val());
    if(pass==passh){
        caja.mensaje('Procesando...');
        $('#modalPassDesPP').modal('hide');
        var tcort = $('#tipoDescu').val();
        var cortecia = $('#cboMetodoPago').val();

        if(cortecia ==  26){
            $.ajax({
                url: 'ajax.php?c=caja&f=obtenTotal',
                type: 'POST',
                dataType: 'json',
               
            })
            .done(function(resp) {
                console.log(resp);
                 caja.aplicaCortesiaGeneral(resp.total);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
           
        }else{                       
            if(tcort == 'C'){
       
                var idProducto = $('#xProParc').val();
                caja.aplicaCortesia(idProducto);
            }else{
               
                caja.aplicaDesParcial();

            }
        }

        
    }else{
        alert('El password/contraseña es incorrecta.');
        $('#modPass2').focus();
        return false;
    }
},hazUnTruco3 : function(){

        idProducto = $('#xProParc').val().split('H').join('=>').split('P').join('*');
        precioUnitario = ( parseFloat( $('#selectListaPrecios').val() ).toFixed(2) );
        cantidad =  ( parseFloat( $(`[cant2=${$('#xProParc').val().split('=>').join('H').split('*').join('P')}]` ).val() ) ) ;
        caja.aplicaPrecioDeLista(idProducto, precioUnitario, cantidad);
},aplicaPrecioDeLista : function(idProducto, precioUnitario, cantidad){
    
    $.ajax({
        url: 'ajax.php?c=caja&f=recalcula',
        type: 'POST',
        dataType: 'json',
        data: {
            idProducto: idProducto,
            precio: precioUnitario,
            cantidad: cantidad, 
            field:"precio"
        },
    })
    .done(function(res) {
        console.log(res);
        
        var nombre = $('#descProdUpdate').val();
        $.ajax({
            url: 'ajax.php?c=caja&f=cambiaCantidad',
            type: 'POST',
            dataType: 'json',
            data: {id: idProducto,
                    cantidad: 0.0,
                    tipo: 'N',
                    nombre:nombre},
        })
        .done(function(res ) {
            caja.data = res;
            caja.pintaResultados(res, false);
            $('#modalDescParcial').modal('hide');
            caja.eliminaMensaje();
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });

    
},aplicaCortesia : function(idProducto){
    
    $.ajax({
        url: 'ajax.php?c=caja&f=aplicaCortesiaPP',
        type: 'POST',
        dataType: 'json',
        data: {idProducto: idProducto},
    })
    .done(function(resCortesias) {
        console.log(resCortesias);
        caja.data = resCortesias;
        caja.pintaResultados(resCortesias, false);
        $('#modalDescParcial').modal('hide');
        caja.eliminaMensaje();
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
},aplicaCortesiaGeneral : function(total){
    caja.agregarPago(26,'Cortesia',total,'');

    $.ajax({
        url: 'ajax.php?c=caja&f=aplicaCortesiaGeneral',
        type: 'POST',
        dataType: 'json',
        //data: { 'value1'},
    })
    .done(function(resp) {
        console.log(resp);
        if(resp.corte==true){
            caja.pagar();
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
},eliminaDescuento: function(){
    var txt;
    var r = confirm("Deseas quitar el descuento?");
    if (r == true) {
        caja.mensaje('Procesando...')
        $.ajax({
            url: 'ajax.php?c=caja&f=eliminaDescuento',
            type: 'POST',
            dataType: 'json',
            //data: {param1: 'value1'},
        })
        .done(function(resEldes) {
            caja.eliminaMensaje();

            console.log(resEldes);
            caja.pintaResultados(resEldes);
            $('#modalDesgeneralBot').modal('hide');
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    } else {
        alert('no');
    }
},formRetiro: function(){

    $.ajax({
        url: 'ajax.php?c=caja&f=obtenCorte',
        type: 'POST',
        dataType: 'json',
        data: {show: 0},
    })
    .done(function(resCor) {
        console.log(resCor.saldoDisponible);
        $('#saldo_disponibleR').val(resCor.saldoDisponible);

        $('#cantidadRetiro').val('');
        $('#concepto').val('');

        $('#modalformRetiro').modal({
            show:true,
        });


    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
},retira: function (){
    $('#bRetira').prop('disabled',true);
    var cantidad = $('#cantidadRetiro').val();
    var concepto = $('#concepto').val();
    var disponible = $('#saldo_disponibleR').val();

    if(cantidad==0 || cantidad=='' || cantidad < 0){
        alert('Tienes que agregar una cantidad mayor a 0');
        return;
    }
    if(concepto==''){
        alert('El campo concepto no puede quedar vacio');
        return;
    }
    if(parseFloat(cantidad) > parseFloat(disponible)){
        alert('No puedes Retirar mas de lo disponible.');
        return;
    }
        caja.mensaje('Procesando...');
            $.ajax({
                url: 'ajax.php?c=retiro&f=agregaretiro',
                type: 'POST',
                dataType: 'json',
                data: {cantidad: cantidad,
                       concepto : concepto,
                },
            })
            .done(function(data) {
                console.log(data);
                if(data.status == true){
                    alert('Se realizo el retiro exitosamente.');
                    caja.eliminaMensaje();

                    $('#modalformRetiro').modal('hide')
                    $('#cantidad').val('');
                    $('#concepto').val('');
                    $('.trtablita').empty()
                    $('#bRetira').prop('disabled',false);
                    caja.reimprimeR(data.id);
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
},formAbono: function(){

    $('#cantidadAbono').val('');
    $('#conceptoAbono').val('');

    $('#modalformAbono').modal({
                show:true,
            });

},buscaCargos: function(){
    var cliente = $('#clienteAbono').val();

    $.ajax({
        url: 'ajax.php?c=retiro&f=buscaCargos',
        type: 'POST',
        dataType: 'json',
        data: {cliente: cliente},
    })
    .done(function(data) {
        console.log(data);
        /* $('#cargosAbono').empty();
        $.each(data, function(index, val) {
            $("#cargosAbono").append('<option value="'+val.id+'">'+val.concepto+'</option>');
        }); 

        $('#cargosAbono').select2({width:'100%'}); */
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
},abona: function(){
    $('#bAbona').prop('disabled',true);
    var cliente = $('#clienteAbono').val();
    var importe = $('#cantidadAbono').val();
    var concepto = $('#conceptoAbono').val();
    var formaPago = $('#formaPagoAbono').val();
    var moneda = $('#monedaAbono').val();
    //var cargo = $('#cargosAbono').val();

    if(importe =='' || importe < 0){
        alert('Tienes que ingresar un importe mayo a cero.');
        return false;
    }
    if(concepto==''){
        alert('Tienes que agregar un concepto.');
        return false;
    }

    if(cliente > 0){
       /* if(cargo > 0){
            alert('Debes de seleccionar un cargo al cual se le aplicar el abono.');
        } */
    }
    caja.mensaje('Procesando...');
    $.ajax({
        url: 'ajax.php?c=retiro&f=agregaAbono',
        type: 'post',
        dataType: 'json',
        data: {cliente: cliente,
               importe: importe,
               concepto: concepto,
               formaPago: formaPago,
               moneda: moneda,
               //cargo: cargo,
        },
    })
    .done(function(data) {

        alert('Se realizo el abono satisfactoriamente.');
        caja.eliminaMensaje();
        $('#modalformAbono').modal('hide');
        $('#bAbona').prop('disabled',false);

        caja.reimprimeA(data);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}, 

///////////////// ******** ---- 					listar_comandas				------ ************ //////////////////
//////// Carga la vista de las comandas
	// Como parametros recibe:
		// div -> Div donde se cargan los datos
			
	listar_comandas : function($objeto) {
		console.log('------------> objeto listar_comandas');
		console.log($objeto);
		
		if($objeto['json'] == 1){
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=caja&f=listar_comandas',
				type : 'GET',
				dataType : 'json',
			}).done(function(resp) {
				console.log('------------> done listar_comandas');
				console.log(resp);
		    
		    // Valida si hay coamndas o no
		    	if(resp['status'] == 1){
		    		$("#div_comandas_pendientes").show();
		    		$objeto['json'] = 0;
		    		caja.listar_comandas($objeto);
		    	}else{
		    		$("#div_comandas_pendientes").hide();
		    	}
			}).fail(function(resp) {
				console.log('---------> Fail listar_comandas');
				console.log(resp);
	
				var $mensaje = 'Error al cargar las  comandas';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
					arrowSize : 15
				});
			});
		}else{
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=caja&f=listar_comandas',
				type : 'GET',
				dataType : 'html',
			}).done(function(resp) {
				console.log('------------> done listar_comandas');
				console.log(resp);
				
		    	$('#' + $objeto['div']).html(resp);
			}).fail(function(resp) {
				console.log('---------> Fail listar_comandas');
				console.log(resp);
	
				var $mensaje = 'Error al cargar las  comandas';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
					arrowSize : 15
				});
			});
		}
	},
    avionForm:function(){
        $('#modalAvion').modal();
    },
    busForm:function(){
        $('#modalBus').modal();
    },

///////////////// ******** ---- 				FIN listar_comandas				------ ************ //////////////////

///////////////// ******** ---- 				mandar_comanda_caja				------ ************ //////////////////
//////// Manda la comanda al input de buscar
	// Como parametros recibe:
		// codigo -> codigo de la comanda
	
	mandar_comanda_caja : function($objeto) {        
		console.log('------------> objeto mandar_comanda_caja');
		console.log($objeto);

	// Selecciona el campo de busqueda
		var campoBuscar = $("#search-producto");
		campoBuscar.trigger("focus");

	// Agrega el codigo de la comanda y busca sus productos
		campoBuscar.val($objeto['codigo']);
		
		setTimeout(function() {
			caja.buscaCaracteristicas($objeto['codigo']);
		}, 500);
		
		$("#tr_"+$objeto['codigo']).hide();
	},


///////////////// ******** ---- 			FIN mandar_comanda_caja				------ ************ //////////////////

///////////////// ******** ---- 				mandar_comanda_caja				------ ************ //////////////////
//////// Manda la comanda al input de buscar
	// Como parametros recibe:
		// codigo -> codigo de la comanda
	
	mandar_comanda_caja : function($objeto) { // entra
		console.log('------------> objeto mandar_comanda_caja');
		console.log($objeto);

	// Selecciona el campo de busqueda
		var campoBuscar = $("#search-producto");
		campoBuscar.trigger("focus");

	// Agrega el codigo de la comanda y busca sus productos
		campoBuscar.val($objeto['codigo']);
		
		setTimeout(function() {
			caja.buscaCaracteristicas($objeto['codigo'],'',$objeto['print']);
		}, 500);
		
		$("#tr_"+$objeto['codigo']).hide();
	},


///////////////// ******** ---- 			FIN mandar_comanda_caja				------ ************ //////////////////

///////////////// ******** ---- 				calcular_propina				------ ************ //////////////////
//////// Calcula la propina y la escribe en la propina sugerida
	// Como parametros recibe:
		// porcentaje -> Porcentaje de calculo
	
	
	calcular_propina : function($objeto) {
		console.log('------------> objeto calcular_propina');
		console.log($objeto);

		var $porcentaje = ($objeto['porcentaje'] / 100);
		$porcentaje = $porcentaje.toFixed(2);
		
		var $monto = caja.info_venta['venta']['monto_total'];
		
		$monto = $monto * $porcentaje;
		$monto = $monto.toFixed(2);
	
	   $("#monto_propina").val($monto);
	},



///////////////// ******** ---- 			FIN calcular_propina				------ ************ //////////////////

///////////////// ******** ---- 				agregar_propina					------ ************ //////////////////
//////// Agrega el monto al array de propina o lo incrementa si ya existe
	// Como parametros recibe:
		// metodo_pago -> Metodo de pago
		// monto -> Monto de la propina
	
	/*agregar_propina : function($objeto) {
		console.log('------------> objeto agregar_propina');
		console.log($objeto);
		
	// Valida que el monto sea mayor a cero
		if ($objeto['monto'] <= 0 || !$objeto['monto']) {
			var $mensaje = 'Propina invalida';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			
			return 0;
		}
		
	// Si no existe el metodo de pago lo agrega
		if(!caja.info_venta['propinas'][$objeto['metodo_pago']]){
			caja.info_venta['propinas'][$objeto['metodo_pago']] = parseFloat($objeto['monto']);
	// Si existe el metodo de pago suma la propina
		}else{
			var $sub_total = parseFloat(caja.info_venta['propinas'][$objeto['metodo_pago']]);
			caja.info_venta['propinas'][$objeto['metodo_pago']] = parseFloat($objeto['monto']) + $sub_total;
		}
		
		
	// Calcula el total de la propina
		var $total_propina = 0;
        $.each(caja.info_venta['propinas'], function(key, value) {
			if(value){
				$total_propina += value;
			}
        });
		$total_propina = $total_propina.toFixed(2);
	
	// Escribe el total de la propina
		$("#txt_total_propina").html("$ "+$total_propina);
		
		console.log('------------> Done agregar_propina');
		console.log(caja.info_venta['propinas']);
	}, */
    agregar_propina : function($objeto) {
        console.log('------------> objeto agregar_propina');
        console.log($objeto);
        
    // Valida que el monto sea mayor a cero
        if ($objeto['monto'] <= 0 || !$objeto['monto']) {
            var $mensaje = 'Propina invalida';
            $.notify($mensaje, {
                position : "top center",
                autoHide : true,
                autoHideDelay : 5000,
                className : 'warn',
                arrowSize : 15
            });
            
            return 0;
        }

        if ($objeto['metodo_pago'] == 4 || $objeto['metodo_pago'] == 5) {
            if($("#txtReferenciaPro").val() == ''){
                var $mensaje = 'Favor de ingresar el numero de tarjeta';
                $.notify($mensaje, {
                    position : "top center",
                    autoHide : true,
                    autoHideDelay : 5000,
                    className : 'warn',
                    arrowSize : 15
                });
                
                return 0;
            }
            if($('input:radio[name=tarRadioPro]:checked').val() != 1 && $('input:radio[name=tarRadioPro]:checked').val() != 2 && $('input:radio[name=tarRadioPro]:checked').val() != 3){
                var $mensaje = 'Favor de seleccionar su tipo de tarjeta';
                $.notify($mensaje, {
                    position : "top center",
                    autoHide : true,
                    autoHideDelay : 5000,
                    className : 'warn',
                    arrowSize : 15
                });
                
                return 0;
            }
        }
        $objeto['num_tarjeta'] = $("#txtReferenciaPro").val();
        $objeto['tipo_tarjeta'] = $('input:radio[name=tarRadioPro]:checked').val();
        caja.info_venta['propinas'].push($objeto);
        var tipo_pa = 0;
        if ($objeto['metodo_pago'] == 1){
            tipo_pa = 'Efectivo';
        } else if ($objeto['metodo_pago'] == 2){
            tipo_pa = 'Cheque';
        } else if ($objeto['metodo_pago'] == 3){
            tipo_pa = 'Tarjeta de regalo';
        } else if ($objeto['metodo_pago'] == 4){
            tipo_pa = 'Tarjeta de crédito';
        } else if ($objeto['metodo_pago'] == 5){
            tipo_pa = 'Tarjeta de debito';
        } else if ($objeto['metodo_pago'] == 6){
            tipo_pa = 'Crédito'
        } else if ($objeto['metodo_pago'] == 7){
            tipo_pa = 'Transferencia';
        } else if ($objeto['metodo_pago'] == 8){
            tipo_pa = 'Spei';
        } else if ($objeto['metodo_pago'] == 9){
            tipo_pa = '-No Identificado-';
        } else if ($objeto['metodo_pago'] == 21){
            tipo_pa = 'Otros';
        } else if ($objeto['metodo_pago'] == 24){
            tipo_pa = 'NA';
        } 
        $("#divDesglosePagoTablaCuerpoPro").append('<tr id="Prop'+(caja.info_venta['propinas'].length-1)+'"><td>'+tipo_pa+'</td><td id="cantidad1">'+$objeto['monto']+'</td><td style="text-align: center;"><span onclick="caja.remove_pro('+(caja.info_venta['propinas'].length-1)+')" class="glyphicon glyphicon-remove"></span></td></tr>');
    
        
        
    // Calcula el total de la propina
        var $total_propina = 0;
        $.each(caja.info_venta['propinas'], function(key, value) {
            console.log('value');
            console.log(value['monto']);
            if(value['monto'] && value['remove'] != 1){
                $total_propina += parseFloat(value['monto']);
            }
        });
        console.log("prop_to_ "+$total_propina);
        $total_propina = $total_propina.toFixed(2);
    
    // Escribe el total de la propina
        $("#txt_total_propina").html("$ "+$total_propina);
        
        console.log('------------> Done agregar_propina');
        console.log(caja.info_venta['propinas']);
    },
///////////////// ******** ---- 			FIN agregar_propina					------ ************ //////////////////

garantiaButtonAction : function(){
    $('#modalGarantiaVenta').modal({
        show:true,
    });
},

buscarGarantiaVenta: function(){
    var idVenta = $('#idGarantiaVenta').val();
    if(idVenta==''){
        alert('Ingresa un id de Venta');
        return false;
    }
    $.ajax({
        type: "GET",                                            
        url: "ajax.php?c=caja&f=obtenerGarantiaVenta", 
        data: { "idVenta" : idVenta }, 
        dataType : 'json',                                       
        timeout: 1500,                                          
        beforeSend: function(data) {                                 
        },
        complete: function(data) {                  
        },
        success: function(data) { 
            $('#iddGarantiaVenta').val(data.venta.id_venta);
            $('#clienteGarantiaVenta').val(data.venta.cliente);
            $('#fechaGarantiaVenta').val(data.venta.fecha);

            $('#tablaGarantiaProducto').empty();
            $.each( data['rows'], function(index, value) {
                var derechoGarantia = "";
                switch (value.derecho_garantia) {
                    case "1": derechoGarantia = "Cambio"; break;
                    case "2": derechoGarantia = "Reparación"; break;
                    case "3": derechoGarantia = "Cambio & Reparación"; break;
                    default:
                }
                var vigencia = ( value.vigencia_garantia == "1") ? `
                <span class="label label-success ">Vigente</span>
                ` : `
                <span class="label label-danger">No vigente</span>
                `;
                var reclamar = `
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label for="idCantidadReclamo">Unidades</label>
                        <input type="number" class="form-control inputCantidadReclamo" min="0" max="` + 
                        value.cantidad + `" value="0" `+ (( value.vigencia_garantia == "1") ? "" : "disabled") +`/>
                    </div>
                </div>
                `;
                var fila = `
                <tr>
                    <th>` + value.codigo + `</th>
                    <th>` + value.nombre + `</th>
                    <th class="cantidadProductos">` + value.cantidad +  `</th>
                    <th>` + `$ `+ value.precio_producto + `</th>
                    <th>` + `$ `+ value.impuesto + `</th>
                    <th>` + `$ `+ value.subtotal+ `</th>
                    <th>` + derechoGarantia + `</th>
                    <th>` + vigencia + `</th>
                    <th>` + reclamar + `</th>
                    <th> <a class="btn"> <span class="label label-default" onclick="caja.detalleMovimientoGarantia(` + value.id_venta_producto + `);"> Detalles </span> </a> </th>
                    <th class="idVentaProductoGarantia" style="display: none;">` + value.id_venta_producto + `</th>
                </tr>
                `; 
                $('#tablaGarantiaProducto').append(fila);
            }); 

           $('.inputCantidadReclamo').on('change', function() {
                var cantidad = $(this).parent().parent().parent().parent().find('.cantidadProductos').text() ;
                if ( parseInt( $(this).val() ) > parseInt( cantidad ) ) {
                    $(this).val("0");
                    alert( "Introduce una cantidad menor a la cantidad en la venta." );
                }

                var thisself = $(this);
                var idVentaProductoGarantia = $(this).parent().parent().parent().parent().find('.idVentaProductoGarantia').text() ;
                $.ajax({
                    type: "GET",                                            
                    url: "ajax.php?c=caja&f=productosEnGarantia",
                    data: {"id" : idVentaProductoGarantia },                                          
                    timeout: 2000,   
                    dataType: 'json',                                       
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(data) {
                        if(data.status == true && data.rows[0].en_garantia != null) {
                            var disponibles = cantidad - data.rows[0].en_garantia;
                            if ( disponibles <  thisself.val()) {
                                alert("Introduce una cantidad menor a " + disponibles +  " (productos disponibles para reclamo de garantia)");
                                thisself.val(disponibles);
                            }
                        }
                        
                    },
                    error: function() {  
                        alert("Error al procesar productos en garantía");                                   
                    }
                });
           });

        },
        error: function() {                                     
            alert("Error al cargar tabla de garantías");
        }
    }); 
    
},

reclamarGarantia : function () {
    var datos = caja.obtenerDatosReclamoGarantia();
    var continuar = true;
    if(datos.comentario == "") {
        continuar = false;
    }
    if(datos.tablaVentaProducto.length == 0) {
        alert("La cantidad de productos a reclamar debe ser mayor a cero");
        continuar = false;
    }

    if(continuar) {
        $.ajax({
            type: "POST",                                            
            url: "ajax.php?c=caja&f=reclamarGarantia",
            data: datos,                                          
            timeout: 2000,   
            dataType: 'json',                                       
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(data) {
                if(data.status == true){
                    alert("Tu garantía se ha procesado exitosamente");
                    $('#iddGarantiaVenta').val("");
                    $('#idAlmacenGarantia').val("1");
                    $('#idComentarioGarantia').val("");
                    $('#tablaGarantiaProducto').empty();
                    $('#modalGarantiaVenta').modal({
                        show:false,
                    });
                }
                else
                    alert("Hubó un error al procesar tu reclamamo de garantía");
            },
            error: function() {  
                alert("Error al procesar tu reclamamo de garantía");                                   
            }
        });
    }
    else {
        //alert("Verifica que todos los campos esten correctamente");
    }

},

obtenerDatosReclamoGarantia : function(){
    var datos = { };

    datos.idVenta = $('#iddGarantiaVenta').val();
    datos.idAlmacen = $('#idAlmacenGarantia').val();
    datos.comentario = $('#idComentarioGarantia').val();

    datos.tablaVentaProducto = [];
    $('tbody#tablaGarantiaProducto tr').each( function() {
        let temp = {  };
        temp.idVentaProducto = $(this).children(':nth-child(11)').text();
        temp.codigo = $(this).children(':nth-child(1)').text();
        temp.nombre = $(this).children(':nth-child(2)').text();
        temp.cantidad = $(this).children(':nth-child(9)').find('input').val();
        switch ( $(this).children(':nth-child(7)').text() ) {
            case "Cambio": temp.tipoMovimiento = "1"; break;
            case "Reparación": temp.tipoMovimiento = "2"; break;
            case "Cambio & Reparación": temp.tipoMovimiento = "3"; break;
            default:
        }
        if ( temp.cantidad != "0" )
            datos.tablaVentaProducto.push( temp );
    });

    return datos;
},

detalleMovimientoGarantia : function(idVentaProducto) {
    $('#modalDetalleMovimientoGarantia').modal({
        show:true,
    });
    $('#idMovimientoGarantiaProducto').text( "Movimientos de garantía, producto:" + idVentaProducto );

    $.ajax({
        type: "GET",                                            
        url: "ajax.php?c=caja&f=detalleMovimientoGarantia", 
        data: { "idVentaProducto" : idVentaProducto }, 
        dataType : 'json',                                       
        timeout: 1500,                                          
        beforeSend: function(data) {                                 
        },
        complete: function(data) {                  
        },
        success: function(data) { 
            $('#tablaMovimientosGarantia').empty();

            $.each( data['rows'], function(index, value) {
                var derechoGarantia = "";
                switch (value.tipo_movimiento) {
                    case "1": derechoGarantia = "Cambio"; break;
                    case "2": derechoGarantia = "Reparación"; break;
                    case "3": derechoGarantia = "Cambio & Reparación"; break;
                    default:
                }
                var estatus = ( value.atendida == "1") ? `
                <span class="label label-success ">Atendida</span>
                ` : `
                <a class="btn"><span class="label label-danger" onclick="caja.atenderGarantia(`+ value.id_venta_producto +`);">Atender</span></a>
                `;
                var fila = `
                <tr>
                    <th>` + value.codigo + `</th>
                    <th>` + value.nombre + `</th>
                    <th class="cantidadProductos">` + value.cantidad +  `</th>
                    <th>` + value.id_almacen + `</th>
                    <th>` + derechoGarantia + `</th>
                    <th>` + value.comentario + `</th>
                    <th>` + value.fecha + `</th>
                    <th>` + estatus + `</th>
                </tr>
                `; 
                $('#tablaMovimientosGarantia').append(fila);
            }); 

        },
        error: function() {                                     
            alert("Error al cargar tabla de garantías");
        }
    }); 
},

atenderGarantia : function(idVentaProducto) {
    $.ajax({
        type: "POST",                                            
        url: "ajax.php?c=caja&f=atenderMovimientoGarantia", 
        data: { "idVentaProducto" : idVentaProducto }, 
        dataType : 'json',                                       
        timeout: 1500,                                          
        beforeSend: function(data) {                                 
        },
        complete: function(data) {                  
        },
        success: function(data) { 
            caja.detalleMovimientoGarantia(idVentaProducto);
        },
        error: function() {                                     
            alert("Error al cargar atender garantía");
        }
    }); 
},

arqueoButtonAction : function(){
    $('#disponibleArqueo').val( $('#saldo_disponible').val() );
},

validarArqueo : function( event ) {
    var total = 0.0;
    $('#peso1, #peso2, #peso5, #peso10, #peso20, #peso50, #peso100, #peso200, #peso500, #peso1000').each(function(index, el) {
        total += parseFloat( $(this).val() ) * parseFloat( ($(this).attr('id')).substring(4) );
    });
    $('#centavo5, #centavo10, #centavo20, #centavo50').each(function(index, el) {
        total += parseFloat( $(this).val() ) * ( parseFloat( ($(this).attr('id')).substring(7) ) * 0.01 ) ;
    });

    if(total > parseFloat( $('#disponibleArqueo').val() ) ) {
        alert("No puedes exceder el monto disponible en caja.");
        event.value = 0;
    }
    else {
        $('#totalArqueo').val( total );

        if(total.toFixed(1) == (parseFloat( $('#disponibleArqueo').val() )).toFixed(1) ){
            $('#aceptarArqueo').attr('disabled', false);
        }
        else{
            $('#aceptarArqueo').attr('disabled', true);
        }
    } 

},

obtenerDatosArqueo() {
    var datos = {};
    datos.pesos = {};
    $('#peso1, #peso2, #peso5, #peso10, #peso20, #peso50, #peso100, #peso200, #peso500, #peso1000').each(function(index, el) {
        datos.pesos[$(this).attr('id')] = $(this).val() ;
    });
    datos.centavos = {};
    $('#centavo5, #centavo10, #centavo20, #centavo50').each(function(index, el) {
        datos.centavos[$(this).attr('id')] = $(this).val() ;
    });
    datos.total = $('#totalArqueo').val();
    return datos;
},

pagoDenominacionButtonAction : function(){
    $('#aPagar').val( $('#lblTotalxPagar').text() );
},

changeMetodoPago : function(){
    if($('#cboMetodoPago').val() == 6)
        caja.changeCantidadPago();
    else if($('#cboMetodoPago').val() == 1)
        $('#btnDenominacionesPago').show();
    else
        $('#btnDenominacionesPago').hide();
},

validarPagoDenominacion : function( event ) {
    var total = 0.0;
    $('#pesoD1, #pesoD2, #pesoD5, #pesoD10, #pesoD20, #pesoD50, #pesoD100, #pesoD200, #pesoD500, #pesoD1000').each(function(index, el) {
        total += parseFloat( $(this).val() ) * parseFloat( ($(this).attr('id')).substring(5) );
    });
    $('#centavoD5, #centavoD10, #centavoD20, #centavoD50').each(function(index, el) {
        total += parseFloat( $(this).val() ) * ( parseFloat( ($(this).attr('id')).substring(8) ) * 0.01 ) ;
    });

    $('#totalPago').val( total );
    /*if(total > parseFloat( $('#aPagar').val() ) ) {
        alert("No puedes exceder el monto disponible en caja.");
        event.value = 0;
    }
    else {
        $('#totalPago').val( total );

        if(total.toFixed(1) == (parseFloat( $('#aPagar').val() )).toFixed(1) ){
            $('#aceptarPago').attr('disabled', false);
        }
        else{
            $('#aceptarPago').attr('disabled', true);
        }
    } */

},

aceptarPago : function(t) {
    $('#txtCantidadPago').val( $('#totalPago').val() );
},

detalleMovimientoDevolucion : function(idVentaProducto) {
    $('#modalDetalleMovimientoDevolucion').modal({
        show:true,
    });
    $('#idMovimientoDevolucionProducto').text( "Movimientos de venta, producto:" + idVentaProducto );

    $.ajax({
        type: "GET",                                            
        url: "ajax.php?c=caja&f=detalleMovimientoDevueltos", 
        data: { "idVentaProducto" : idVentaProducto }, 
        dataType : 'json',                                       
        timeout: 1500,                                          
        beforeSend: function(data) {                                 
        },
        complete: function(data) {                  
        },
        success: function(data) { 
            $('#tablaMovimientosDevolucion').empty();

            $.each( data['rows'], function(index, value) {

                var fila = `
                <tr>
                    <th>` + value.codigo + `</th>
                    <th>` + value.nombre + `</th>
                    <th class="cantidadProductos">` + value.cantidad +  `</th>
                    <th>` + value.almacen + `</th>
                    <th>` + value.comentario + `</th>
                    <th>` + value.fecha + `</th>
                </tr>
                `; 
                $('#tablaMovimientosDevolucion').append(fila);
            }); 

        },
        error: function() {                                     
            alert("Error al cargar tabla de devoluciones");
        }
    }); 
},

devolverVenta : function () {

    var datos = caja.obtenerDatosDevolucion();
    console.log(datos);
    var continuar = true;
    if(datos.comentario == "") {
        alert("Inserta un comentario");
        continuar = false;
    }
    if(datos.tablaVentaProducto.length == 0) {
        alert("La cantidad de productos a reclamar debe ser mayor a cero");
        continuar = false;
    }





    var idVenta = $('#idVentaHidden').val();

         $.ajax({
            url: 'ajax.php?c=caja&f=puedeDevolverCancelar',
            type: 'GET',
            dataType: 'json',
        })
        .done(function(puedeDevolverCancelar) {
            var permiso = false;
            if ( !puedeDevolverCancelar ) {

                $('#modalPassDev').modal();
                
                
            } else {
                
                var datos = caja.obtenerDatosDevolucion();
                console.log(datos);
                var continuar = true;
                if(datos.comentario == "") {
                    alert("Inserta un comentario");
                    continuar = false;
                }
                if(datos.tablaVentaProducto.length == 0) {
                    alert("La cantidad de productos a reclamar debe ser mayor a cero");
                    continuar = false;
                }

                var idVenta = $('#idVentaHidden').val();
                

                if ( $('#idAlmacenDevolucion').val() == "0" ) { //Merma
                    if (continuar) {
                        console.log(datos);
                        $.ajax({
                            url: 'ajax.php?c=caja&f=guardaMerma',
                            type: 'POST',
                            data: datos,                                          
                            timeout: 2000,   
                            dataType: 'json',
                            beforeSend: function() {
                                caja.mensaje("Procesando . . . ")
                            },
                            complete: function() {
                                caja.eliminaMensaje();
                            },
                            success: function(data) {
                                if(data.estatus == true){
                                    console.log(data);
                                    if(data.idMerma!=0){
                                        alert("Tu devolución a merma se ha procesado exitosamente");
                                        $('#idFacPanel').text("");
                                        $('#idAlmacenDevolucion').val("1");
                                        $('#idComentarioDevolucion').val("");
                                        $('#tablaVenta').empty();
                                        $('#modalVentasDetalle').modal('hide');
                                    }

                                    
                                }
                                else
                                    alert("No es posible devolver sobre el monto de venta a crédito");
                            },
                            error: function() {  
                                alert("Error al procesar tu devolución");                                   
                            }
                        });
                    }
                    
                }
                else {
                    

                    if(continuar) {
                        $.ajax({
                            type: "POST",                                            
                            url: "ajax.php?c=caja&f=devolucion",
                            data: datos,                                          
                            timeout: 5000,   
                            dataType: 'json',                                       
                            beforeSend: function() {
                                caja.mensaje("Procesando . . . ")
                            },
                            complete: function() {
                                caja.eliminaMensaje();
                            },
                            success: function(data) {

                                if(data.status == true){
                                    alert("Tu devolución se ha procesado exitosamente");
                                    $('#idFacPanel').text("");
                                    $('#idAlmacenDevolucion').val("1");
                                    $('#idComentarioDevolucion').val("");
                                    $('#tablaVenta').empty();
                                    $('#modalVentasDetalle').modal('hide');
                                }
                                else
                                    alert("No es posible devolver sobre el monto de venta a crédito");
                            },
                            error: function() {  
                                alert("Error al procesar tu devolución");                                   
                            }
                        });
                    }
                    else {
                        //alert("Verifica que todos los campos esten correctamente");
                    }
                }

            }
            
            
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });















    
    

},
confirmaDevolucion : function() {
    
    $('#modalPassDev').modal('hide');
        password = $('#modPassDev').val();
        $('#modPassDev').val('');

                $.ajax({
                    url: 'ajax.php?c=caja&f=autorizacionDevolverCancelar',
                    type: 'GET',
                    dataType: 'json',
                    data: {password: password},
                })
                .done(function(autorizacion) {
                    permiso = autorizacion;

                    if ( permiso ) { //procesodevolución

                        var datos = caja.obtenerDatosDevolucion();
                        console.log(datos);
                        var continuar = true;
                        if(datos.comentario == "") {
                            alert("Inserta un comentario");
                            continuar = false;
                        }
                        if(datos.tablaVentaProducto.length == 0) {
                            alert("La cantidad de productos a reclamar debe ser mayor a cero");
                            continuar = false;
                        }





                        var idVenta = $('#idVentaHidden').val();
                        

                        if ( $('#idAlmacenDevolucion').val() == "0" ) { //Merma
                            if (continuar) {
                                console.log(datos);
                                $.ajax({
                                    url: 'ajax.php?c=caja&f=guardaMerma',
                                    type: 'POST',
                                    data: datos,                                          
                                    timeout: 2000,   
                                    dataType: 'json',
                                    beforeSend: function() {
                                        caja.mensaje("Procesando . . . ")
                                    },
                                    complete: function() {
                                        caja.eliminaMensaje();
                                    },
                                    success: function(data) {
                                        if(data.estatus == true){
                                            console.log(data);
                                            if(data.idMerma!=0){
                                                alert("Tu devolución a merma se ha procesado exitosamente");
                                                $('#idFacPanel').text("");
                                                $('#idAlmacenDevolucion').val("1");
                                                $('#idComentarioDevolucion').val("");
                                                $('#tablaVenta').empty();
                                                $('#modalVentasDetalle').modal('hide');
                                            }

                                            
                                        }
                                        else
                                            alert("No es posible devolver sobre el monto de venta a crédito");
                                    },
                                    error: function() {  
                                        alert("Error al procesar tu devolución");                                   
                                    }
                                });
                            }
                            
                        }
                        else {
                            

                            if(continuar) {
                                $.ajax({
                                    type: "POST",                                            
                                    url: "ajax.php?c=caja&f=devolucion",
                                    data: datos,                                          
                                    timeout: 5000,   
                                    dataType: 'json',                                       
                                    beforeSend: function() {
                                        caja.mensaje("Procesando . . . ")
                                    },
                                    complete: function() {
                                        caja.eliminaMensaje();
                                    },
                                    success: function(data) {

                                        if(data.status == true){
                                            alert("Tu devolución se ha procesado exitosamente");
                                            $('#idFacPanel').text("");
                                            $('#idAlmacenDevolucion').val("1");
                                            $('#idComentarioDevolucion').val("");
                                            $('#tablaVenta').empty();
                                            $('#modalVentasDetalle').modal('hide');
                                        }
                                        else
                                            alert("No es posible devolver sobre el monto de venta a crédito");
                                    },
                                    error: function() {  
                                        alert("Error al procesar tu devolución");                                   
                                    }
                                });
                            }
                            else {
                                //alert("Verifica que todos los campos esten correctamente");
                            }
                        }
                    }
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
},

    remove_pro : function($objeto){
        console.log('------------> objeto remove_pro');
        console.log($objeto);
        $('#Prop'+$objeto).remove();
        caja.info_venta['propinas'][$objeto]['remove'] = 1;
        var $total_propina = 0;
        $.each(caja.info_venta['propinas'], function(key, value) {
            console.log('value');
            console.log(value['monto']);
            if(value['monto'] && value['remove'] != 1){
                $total_propina += parseFloat(value['monto']);
            }
        });
        $total_propina = $total_propina.toFixed(2);
    
    // Escribe el total de la propina
        $("#txt_total_propina").html("$ "+$total_propina);
    },

obtenerDatosDevolucion : function(){
    var datos = { };

    datos.idVenta = $('#idFacPanel').text();
    datos.idAlmacen = $('#idAlmacenDevolucion').val();
    datos.comentario = $('#idComentarioDevolucion').val();
    datos.subtotal = 0.0;
    datos.total = 0.0;

    datos.tablaVentaProducto = [];
    $('tbody#tablaVenta tr').each( function() {
        let temp = {  };
        temp.idVentaProducto = $(this).children(':nth-child(9)').text();
        temp.codigo = $(this).children(':nth-child(1)').text();
        temp.nombre = $(this).children(':nth-child(2)').text();
        temp.cantidad = $(this).children(':nth-child(7)').find('input').val();
        temp.precio = parseFloat( $(this).children(':nth-child(4)').text().substring(1) );
        temp.idVentaProducto = parseFloat( $(this).children(':nth-child(9)').text() );
        temp.seriesLotes = $(this).attr('json');

        datos.subtotal = parseFloat( $(this).children(':nth-child(4)').text().substring(1) ) * parseInt( $(this).children(':nth-child(7)').find('input').val() ) ;
        datos.total = parseFloat( $(this).children(':nth-child(5)').text().substring(1) ) / parseFloat( $(this).children(':nth-child(3)').text() ) * parseInt( $(this).children(':nth-child(7)').find('input').val() ) ;
        if ( temp.cantidad != "0" )
            datos.tablaVentaProducto.push( temp );
    });
    datos.total += datos.subtotal;

    return datos;
},

pintarProductos() {
    $('#rango').val(0);
    $('#containerTouch').empty();
    caja.cargarMas();

},

resetFilters(){
    $("#selectDepartamento").empty().trigger('change');
},
tipoCambio(){
    var moneda = $('#monedaVenta').val();
    if(moneda==1){
        $('#tpc').val(1.00);
    }else{
        $.ajax({
            url: 'ajax.php?c=caja&f=tipodecambio',
            type: 'POST',
            dataType: 'json',
            data: {moneda: moneda},
        })
        .done(function(resul) {
            console.log(resul);
            $('#tpc').val(resul.cambio);
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
},

cancelaCarac() {
    $('#series').val(null);
    $('#lotes').val(null);
    $('#cantidad-producto').val(1);
},

changeCantidadPago()  {

    var porPagar = parseFloat( $('#lblTotalxPagar').text() );
    var aPagar = parseFloat( $('#txtCantidadPago').val() );

    var metodoPago = $('#cboMetodoPago').val();
    if( porPagar < aPagar && metodoPago == 6 ) {
        alert("Has capturado una cantidad mayor a la de la compra");
        $('#txtCantidadPago').val(porPagar);
    }
},

 reimprimeR(id){
    window.open("../../modulos/pos/ticketRetiro.php?idretiro=" +id);
 },
 reimprimeA(id){
    window.open("../../modulos/pos/ticketAbono.php?idabono=" +id);
 },

 confirmaPuntos(){
    var totalaux = $('#lblTotalxPagar').text();
    var total = $('#modTarRegPuntos').val();
    
            
    total = total * 1;
    if( total <= 0 || total > totalaux ){
        $('#modTarRegPuntos').val(totalaux);
        return;
    }
    var puntosTar = $('#pointsCardIn').val();
   
    var cant = 0;
    if(total > puntosTar){
      
        cant = puntosTar;
        $('#pointsCardIn').val('0');
        $('#pointsCardT').text('0.00');
    }else{
        
        cant = total * 1;
        $('#pointsCardIn').val(puntosTar - (total * 1));
        $('#pointsCardT').text(puntosTar - (total * 1));
    }

    caja.agregarPago(3,'(99) Tarjeta de regalo',cant,$('#tpin').val());
    $('#modalTarjetaRegalo').modal('hide');
 }

};//fin de caja var

window.onload = function() {

    $('#prodPronti').change(function(){
        $("#pronti_monto").val($('#prodPronti option:selected').attr('precio'));
        $("#pronti_monto").attr("disabled", "disabled");
    });

    $("#pronti_editar").click(function(){
        $("#pronti_monto").removeAttr("disabled");
    });
    
    $("#selectDepartamento").select2({
        placeholder: "Departamento",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 1,
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectDepartamento").empty();
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
        $("#selectFamilia").empty().trigger('change');
    });
    $("#selectFamilia").select2({
        placeholder: "Familia",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 2,
                    departamento : $('#selectDepartamento').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectFamilia").empty();
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
        $("#selectLinea").empty().trigger('change');
    });
    $("#selectLinea").select2({
        placeholder: "Linea",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 3,
                    familia : $('#selectFamilia').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectLinea").empty();
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
        caja.pintarProductos();
    });;
};

