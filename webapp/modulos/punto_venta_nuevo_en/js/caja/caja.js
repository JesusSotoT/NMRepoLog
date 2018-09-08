var obsResp = null;
var idPrd = null;
var arrExt = null;
var respg = null;
var caja = {
    currentRequest: null,
    currentRequestP: null,
    meses: new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"),
    diasSemana: new Array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado"),
    data: new Array(),
    init: function()
    {
        $('#frameComprobante').prop('src', false);
        $('#frameComprobante').removeAttr('src');
        $('#search-producto').trigger('click');
        caja.printTime();
        caja.autocomplete();
        $.ajax({
            url: 'ajax.php?c=caja&f=pintaRegistros',
            type: 'GET',
            dataType: 'json',
            success: function(data) {

                $('#search-producto').focus();

                if (data.status)
                {
                    caja.pintaResultados(data, false);
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
        })
        $.ajax({
            url: 'ajax.php?c=caja&f=buscaFoodwear',
            type: 'POST',
            dataType: 'json',
        })
        .done(function(data) {
            if(data.food==false){
                $('#consumo').css('display','none');
            }else{
                $('#consumo option[value=1]').attr('selected','selected');
            }

        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {

        });


    },
    autocomplete: function()
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
                            //$('#cliente-caja').addClass('loader');
                        },
                        success: function(data) {
                            //$('#cliente-caja').removeClass('loader');
                            return process(data);
                        },
                        error: function(data)
                        {
                            //$('#cliente-caja').removeClass('loader');
                        }
                    })
                } else
                {
                    $('#cliente-caja').removeClass('loader');
                    if (caja.currentRequest != null) {
                        caja.currentRequest.abort();
                    }
                }
            }
        }).on('typeahead:selected', function(event, data) {
            $('#hidencliente-caja').val(data.id);
            caja.checatimbres(data.id);
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
                            //$('#search-producto').addClass('loader');
                        },
                        success: function(data) {

                            var result = caja.inicioCaja(data);

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
                    })
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
    caja.agregaProducto(data.id);
});
},
checatimbres: function(idCliente) {
    //$('#cliente-caja').addClass('loader');
    $.ajax({
        url: 'ajax.php?c=caja&f=cargaRfcs',
        type: 'POST',
        dataType: 'json',
        data: {idCliente: idCliente},
        success: function(data)
        {
            if (data.status)
            {
                $.each(data.rfc, function(index, value) {
                    var option = $(document.createElement('option')).attr({'value': value.id}).html(value.rfc).appendTo($('#rfc'));
                });
                //alert(data.inadem);
                ///inadem
                if(data.inadem=='1' || data.inadem==1){
                  //  alert('EEEEEEE');
                    caja.init();
                    $('#documento option[value=2]').attr('selected','selected');
                    $("#labelrfc").show();
                    $("#selectrfc").show();
                    $('#cliente-caja').removeClass('loader');
                }

            }
        }
    });
},
agregaProducto: function(id)
{
    var cantidad = $('#cantidad-producto').val();
    caja.mensaje("Adding product");
    $.ajax({
        url: 'ajax.php?c=caja&f=agregaProducto',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            cantidadInicial: cantidad
        },
        beforeSend: function() {
            if (caja.currentRequestP != null) {
                caja.currentRequestP.abort();
            }
            //$('#search-producto').addClass('loader');
        },
        success: function(data)
        {
            caja.eliminaMensaje();
            caja.pintaResultados(data, false);
            $('#cantidad-producto').val('1');
            $('#search-producto').val('').typeahead('clearHint').focus();
        },
        error: function(data)
        {
            $('#cantidad-producto').val('1');
            caja.eliminaMensaje();
            $('#search-producto').removeClass('loader');
        }
    });

},

pintaResultados: function(data, empty)
{

    if (data.status)
    {
        caja.data = data;
        //Quitamos el mensaje de la caja que dice que no hay productos.
        //$('.noProducts').remove();
        /*Pintamos los productos con jquery*/

        var contenedorGeneral = $('#contenedorGeneralTablaCuerpo');
        var contImpuestos = $('.impuestosCaja');

        if (empty)
        {
            contenedorGeneral.empty();
            contImpuestos.empty().css({'display': 'none'});
            alert("Empty");
        }

        var subtotalmuestra = 0;
        var timp=Number(0);
        var c = 0;
        $('#timp').val(0*1);
        //Pintamos los productos que hay en la caja
        $.each(data.rows, function(index, value) {
            //Contador
            c = c+1;
            if (index != 'cargos')
            {
                
                if ($('#R' + index).length)
                {
                    $('#R' + index).remove();
                }

                if (value.unidad == null)
                {
                    value.unidad = 'Sin unidad';
                }

                if(value.nombre == null)
                {
                    value.nombre = "Sin nombre";
                }

                if (((value.nombre).length > 22 && (value.nombre).length < 44) || ((value.unidad).length > 20 && (value.unidad).length < 40) || ((value.precioventa).length >= 7 && (value.precioventa).length < 14) || ((value.descuento).length >= 7 && (value.descuento).length < 14) || ((value.subtotal).length >= 7 && (value.subtotal).length < 14)) {
                    css = '56';
                    margin = '28';
                } else if (((value.nombre).length > 44) || ((value.unidad).length > 40) || ((value.precioventa).length > 14) || ((value.descuento).length > 14) || ((value.subtotal).length > 14)) {
                    css = '84';
                    margin = '52';
                } else {
                    css = '28';
                    margin = '0';
                }

                // They where here
                timp=Number(timp)+Number(value.impuesto);
                $('#timp').val(timp*1);
                
                subtotalmuestra = (((value.precioventa*1) * (value.cantidad*1))-(value.descuento*1)) + (value.impuesto*1);

                value.precioventa = caja.redondeo(value.precioventa);
                value.impuesto = caja.redondeo(value.impuesto);
                value.descuento = caja.redondeo(value.descuento);
                value.subtotal = caja.redondeo(subtotalmuestra);

                alreadyclicked = false;
                var contRegistro =  $(document.createElement('tr')).attr({'id': 'R' + index, 'style' : 'cursor:pointer'}).bind('click',function(){
                                        var el=$(this);
                                        if (alreadyclicked)
                                        {
                                            alreadyclicked=false; // reset
                                            clearTimeout(alreadyclickedTimeout); // prevent this from happening
                                            // do what needs to happen on double click.
                                            caja.modificaCantidad(this);
                                        }
                                        else
                                        {
                                            alreadyclicked=true;
                                            alreadyclickedTimeout=setTimeout(function(){
                                                alreadyclicked=false; // reset when it happens
                                                // do what needs to happen on single click.
                                                // use el instead of $(this) because $(this) is
                                                // no longer the element
                                            },300); // <-- dblclick tolerance here
                                        }
                                        return false;
                                    }).addClass('registroCaja col-xs-13').appendTo(contenedorGeneral);

                var contAccion = $(document.createElement('td')).appendTo(contRegistro).css({'text-align' : 'center'});
                var accion = $(document.createElement('img')).css({'margin': 'unset !important'}).addClass('imgDelete').attr({'src': 'img/bor.png'}).appendTo(contAccion);
                var codigo = $(document.createElement('td')).attr({'id': 'Cod' + index}).html(value.codigo).appendTo(contRegistro);
                var descripcion = $(document.createElement('td')).attr({'id': 'Desc' + index}).addClass('textWrap').html(value.nombre).appendTo(contRegistro);
                var cantidad = $(document.createElement('td')).attr({'id': 'Cant' + index}).html(value.cantidad + ' ' + value.unidad).appendTo(contRegistro);
                var precio = $(document.createElement('td')).attr({'id': 'Prec' + index}).html("$ " + caja.addCommas(value.precioventa)).appendTo(contRegistro);
                var impuestos = $(document.createElement('td')).attr({'id': 'Imp' + index}).html("$ " + caja.addCommas(value.impuesto)).appendTo(contRegistro);
                var descuento = $(document.createElement('td')).attr({'id': 'Desc' + index}).html("$ " + caja.addCommas(value.descuento)).appendTo(contRegistro);
                var subTotal = $(document.createElement('td')).attr({'id': 'SubT' + index}).html("$ " + caja.addCommas(value.subtotal)).appendTo(contRegistro);


                accion.bind('click', function() {
                    caja.eliminarProducto(index)
                });
            }
        });

        //Despues de pintar los productos pintamos los impuestos, subtotal y total
        contImpuestos.empty().css({'display': 'block'});
        //var contImpuestos = $(document.createElement('div')).addClass('well col-xs-12').appendTo(contImpuestos);
        var rowImpuestos1 = $(document.createElement('div')).addClass('row').appendTo(contImpuestos);
        var colSucursal = $(document.createElement('div')).addClass('col-md-4').appendTo(rowImpuestos1);
        colSucursal.html('<label style="font-size: 2em !important;">Branch:</label></br>' + data.sucursal);
        var colVendedor = $(document.createElement('div')).addClass('col-md-4').appendTo(rowImpuestos1);
        colVendedor.html('<label style="font-size: 2em !important;">Salesclerk:</label></br>' + data.empleado);
        var colImpuestos = $(document.createElement('div')).addClass('col-md-4').appendTo(rowImpuestos1);
        var itemsImpuestos = "";
        if (data["cargos"]["impuestos"] != undefined)
        {
            $.each(data["cargos"]["impuestos"], function(index, val) {
                if (index != 'suma' && val != 0)
                {   val = caja.redondeo($('#timp').val());
                    itemsImpuestos +=   "<div><label style='font-size: 1em !important;'>" +
                                            index + ":</label> $" + caja.addCommas(val) +
                                        "</div>";
                }
            });
        }



        colImpuestos.html('<label style="font-size: 3em !important;">Taxes:</label>' + itemsImpuestos);

        var rowImpuestos2 = $(document.createElement('div')).addClass('row').appendTo(contImpuestos);
        data["cargos"]["subtotal"]=caja.redondeo(data["cargos"]["subtotal"]);
        var subTotal = $(document.createElement('div')).addClass('col-md-4').appendTo(rowImpuestos2);
        subTotal.html('<label style="font-size: 3em !important;">Subtotal: </label></br><label style="font-size: 2em !important">$' + caja.addCommas(data["cargos"]["subtotal"])+'</label>');

        if (data["opciones"] != null && data["opciones"][6] == true)
        {
            caja.propina(data["opciones"], data["cargos"]["total"]);
        }

        if (data["opciones"] != null && data["opciones"][3] != '')
        {
            $('#codigo').val(data["opciones"][3]);
            $('#propina').val(data["opciones"][4]);
        }

        data["cargos"]["total"] = caja.redondeo(data["cargos"]["total"]);
        var total = $(document.createElement('div')).addClass('col-md-4').appendTo(rowImpuestos2);
        total.html('<label style="font-size: 4em !important;">Total: </label></br><label style="font-size: 3em !important">$' + caja.addCommas(data["cargos"]["total"])+'</label>');

        var dbtnPagar = $(document.createElement('div')).addClass('col-md-4').appendTo(rowImpuestos2);
        var btnPagar = $(document.createElement('button')).addClass('btn btn-success col-md-8 col-xs-8 btnMenu').html('Pay now').bind('click', function() {
            caja.modalPagar();
        }).appendTo(dbtnPagar);
        

    } else
    {
        alert(data.msg)
    }
    $('#search-producto').removeClass('loader');
},

propinaAceptar: function(){
    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=agregarPropina',
        dataType: 'json',
        data: {idArticulo: respg[4], cantidad: $('#txtPropina').val()},
        success: function(data) {
            caja.pintaResultados(data, false);
        }
    }); //end ajax

    $('#modalPropina').modal('hide');
},
propinaCancelar: function(){
    $('#modalPropina').modal('hide');
},

    propina: function(resp,total)
    {
        respg = resp;
        $.ajax({
            url: 'ajax.php?c=caja&f=configuraPropina',
            type: 'get',
            dataType: 'json'

        })
        .done(function(data) {

            if(data.status==1 || data.status=='1'){
                $('#totalComanda').val(total);
                $('#txtPropina').val(resp[5]);
                $('#modalPropina').modal({backdrop: 'static'});
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });

    },
    busquedaXcodigo: function(e)
    {
        if (window.event)
            keyCode = window.event.keyCode;
        else if (e)
            keyCode = e.which;
        var producto = $('#search-producto').val();
        if (keyCode == 13 && producto != '')
        {
            caja.agregaProducto(producto);
        }
    },
    printTime: function()
    {
        var f = new Date();
        var dia = caja.diasSemana[f.getDay()];
        var fecha = dia + ", " + f.getDate() + " de " + caja.meses[f.getMonth()] + " de " + f.getFullYear();
        var hora = "";
        var hours = f.getHours();
        if (hours > 12) {
            hours -= 12;
        } else if (hours === 0) {
            hours = 12;
        }
        hora += hours;
        if (f.getMinutes() < 10)
            hora += ':0' + f.getMinutes();
        else
            hora += ':' + f.getMinutes() + ':' + f.getSeconds();
        $('#fecha-caja').html(fecha);
        $('#liveclock').html(hora);
        setTimeout(function() {
            caja.printTime()
        }, 1000);
    },
    eliminarProducto: function(idProducto)
    {
        $.ajax({
            url: 'ajax.php?c=caja&f=eliminaProducto',
            type: 'POST',
            dataType: 'json',
            data: {'id': idProducto},
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
                    $('#R' + idProducto).hide('slow', function() {
                        $('#R' + idProducto).empty();
                        if (data.count < 2)
                        {
                            caja.cancelarCaja();
                        } else
                        {
                            caja.pintaResultados(data, false);
                        }
                    });
                }
            }
        })
    },
    cancelarCaja: function(msg)
    {

        if (msg)
        {
            if (!confirm('Do you want to cancel transaction?'))
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
                    $('#cliente-caja').val('').typeahead('clearHint');
                }
            }
        });
},
modificaCantidadAceptar: function(){
    var cantidad = $('#txtCantidadProducto').val();
    var precionuevo = $('#selectPrecios').val();
    var idProducto = idPrd;
    var arrayExistencia = arrExt;
    cantidad = cantidad.replace(',', '');

    cantidad = parseFloat(cantidad);

    var tipo = $('#cboDescuentoProducto').val();
    var descuento = $('#txtDescuentoProducto').val();

    if (caja.data.simple == true && caja.data["rows"][idProducto]["tipo_producto"] != 6)
    {
        var existencia = parseFloat($('#lblexistenciaProducto').text());

        if (cantidad > arrayExistencia["Original"])
        {
            alert("No puedes agregar una cantidad mayor a las existentes.");
            $('#txtDescuentoProducto').val(existencia);
            return;
        }
    }
    if (tipo == '%' && descuento > 100)
    {
        alert("El descuento no puede ser mayor al 100% del precio.");
        $('#txtDescuentoProducto').val('').focus();
        return;
    }
    var t = parseFloat(caja.data["rows"][idProducto]["subtotal"]) * cantidad;

    descuento = descuento *1;
    //var t =  parseFloat(caja.data["rows"][idProducto]["subtotal"])+parseFloat(caja.data["rows"][idProducto]["impuesto"]);
    //if (tipo == '$' && descuento > (parseFloat(caja.data["rows"][idProducto]["subtotal"]) + parseFloat(caja.data["rows"][idProducto]["impuesto"])) * cantidad)
    if (tipo == '$' && descuento > (parseFloat(caja.data["rows"][idProducto]["subtotal"]) * cantidad))
    {
        alert("El descuento no puede ser mayor al importe de la venta.");
        $('#txtDescuentoProducto').val('').focus();
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=cambiaCantidad',
        dataType: 'json',
        data: {
            idArticulo: idProducto,
            cantidad: cantidad,
            tipo: tipo,
            descuento: descuento,
            comentario: $('#txtareacomentariosProducto').val(),
            precionuevo:precionuevo,
        },
        success: function(data) {
            caja.pintaResultados(data, false);
            $('#search-producto').focus();

        }
                        }); //end ajax

    $('#modalCambioCantidad').modal("hide");
},
modificaCantidadCancelar: function(){
    $('#txtCantidadProducto').val('');
    $('#lblDescripcionProducto').text('');
    $('#lblPrecioProducto').text('');
    $('#txtDescuentoProducto').text('');
    $('#modalCambioCantidad').modal("hide");
},
modificaCantidad: function(element)
{
    var idElement = element.id;
    var idProducto = idElement.replace("R", "");
    idPrd = idProducto;

    $.ajax({
        url: 'ajax.php?c=caja&f=checaPrecioVenta',
        type: 'POST',
        dataType: 'json',
        async: false,
        data: {'id': idProducto},
        success: function(data) {
            precioventa = parseFloat(data.rows[0].precioventa, 10);
            precioventa = precioventa;

            if(data.rows[0].descu==1 || data.rows[0].descu=='1'){
                var desx = 0;
                $('#divDescuento').hide();
            }else{
                var desx = 1;
                $('#divDescuento').show();
            }
            //alert(desx);
            ////bloque que estaba fuera del succes
               $('#listaprecios').empty();
                   $.ajax({
                    url: 'ajax.php?c=caja&f=checaPrecios',
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    data: {'id': idProducto},
                    success: function(data) {
                        var precios = $(document.createElement('select')).addClass('form-control').attr({'id': 'selectPrecios', 'onchange':'hideDescuento();', 'class':'form-control'}).css({'width': '100%', 'margin-top': '2%'}).appendTo('#listaprecios');
                        $(document.createElement('option')).attr({'value': precioventa, 'class':desx}).html(precioventa+'/Precio Regular').appendTo('#selectPrecios');
                        $.each(data.rows, function(index, val) {
                            var selectprecios = $(document.createElement('option')).attr({'value': val.precio, 'class': val.orden }).html(val.precio+'/'+val.descripcion).appendTo('#selectPrecios');
                        });
                        $("#selectPrecios").val(caja.data["rows"][idProducto]["precioventa"]);

                    }
                });
            ///////fin del bloque
        }
    });

  /* $('#listaprecios').empty();
       $.ajax({
        url: 'ajax.php?c=caja&f=checaPrecios',
        type: 'POST',
        dataType: 'json',
        async: false,
        data: {'id': idProducto},
        success: function(data) {
            var precios = $(document.createElement('select')).addClass('form-control').attr({'id': 'selectPrecios', 'onchange':'hideDescuento();', 'class':'nminputselect'}).css({'width': '100%', 'margin-top': '2%'}).appendTo('#listaprecios');
            $(document.createElement('option')).attr({'value': precioventa, 'class':'1'}).html(precioventa+'/Precio Regular').appendTo('#selectPrecios');
            $.each(data.rows, function(index, val) {
                var selectprecios = $(document.createElement('option')).attr({'value': val.precio, 'class': val.orden }).html(val.precio+'/'+val.descripcion).appendTo('#selectPrecios');
            });
            $("#selectPrecios").val(caja.data["rows"][idProducto]["precioventa"]);

        }
    });  */




    $('#txtCantidadProducto').val(caja.data["rows"][idProducto]["cantidad"]);
    $('#lblDescripcionProducto').text(caja.data["rows"][idProducto]["nombre"]);
    $('#lblPrecioProducto').text(caja.data["rows"][idProducto]["precioventa"]);
    $('#imagenProducto').attr({'src': '../mrp/' + caja.data["rows"][idProducto]["imagen"]});


    $('#txtDescuentoProducto').text('');
    $('#divExistenciasProducto').css({'display': 'none'});

        /*$('#cboDescuentoProducto').bind('change', function() {
         if ($(this).val() != '')
         {
         $('#txtDescuentoProducto').attr('disabled', false);
         } else
         {
         $('#txtDescuentoProducto').attr('disabled', true);
         }

     });*/
if (caja.data.simple && caja.data["rows"][idProducto]["tipo_producto"] != 6)
{
    var arrayExistencia = new Array();
    $.ajax({
        url: 'ajax.php?c=caja&f=checaExistencias',
        type: 'POST',
        dataType: 'json',
        async: false,
        data: {'id': idProducto},
        beforeSend: function() {
            if (caja.currentRequestP != null) {
                caja.currentRequestP.abort();
            }
            if (caja.currentRequest != null) {
                caja.currentRequest.abort();
            }
        },
        success: function(data) {
            arrayExistencia = data;
            if (data.status == true)
            {
                $('#divExistenciasProducto').css({'display': 'block'});
                $('#lblexistenciaProducto').text(data["rows"][0]["cantidad"]).removeClass('success warning danger');

                if (parseInt(data["rows"][0]["cantidad"]) > 2)
                {
                    $('#lblexistenciaProducto').addClass('success');
                } else if (parseInt(data["rows"][0]["cantidad"]) == 2)
                {
                    $('#lblexistenciaProducto').addClass('warning');
                } else
                {
                    $('#lblexistenciaProducto').addClass('danger');
                }
            } else
            {
                return;
            }
        }
    });
}

if (caja.data["rows"][idProducto]["tipo_producto"] == 6)
{
    $('#divComentariosProducto').css({'display': 'block'});
} else {
    $('#divComentariosProducto').css({'display': 'none'});
}

$('#txtDescuentoProducto').val('');
arrExt = arrayExistencia;
$('#modalCambioCantidad').modal({backdrop: 'static'});
},
ajusteAceptar: function(){
    var ajuste = $('#ajusteTotal').val();
    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=ajustaTotal',
        dataType: 'json',
        data: {ajuste:ajuste},
        success: function(data) {
            caja.init();
        }
    }); //end ajax

    $('#modalAjuste').modal("hide");
},
ajusteCancelar: function(){
    $('#modalAjuste').modal("hide");
},
ajustarTotal : function(){
    $('#modalAjuste').modal({backdrop: 'static'});
},
modalPagar: function()
{
    caja.checaPagos();

    $('#lblTotalxPagar').text(caja.data["cargos"]["total"]);
    $('#btnAgregarPago').unbind('click').bind('click', function() {

        var tipostr = $('#cboMetodoPago option:selected').text();
        var tipo = $('#cboMetodoPago').val();
        var pago = ($('#txtCantidadPago').val()).replace(",",'');
        var txtReferencia = $('#txtReferencia').val();

        caja.metodoPago(tipo, tipostr, pago, txtReferencia);
    });
    $('#cboMetodoPago').unbind('change').bind('change', function() {
        caja.muestraReferenciaPago($(this).val());
    });
    $('#modalPago').modal({backdrop: 'static'});
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
        alert("Check number must be specified");
        return false;
    }

    if ($("#cboMetodoPago").val() == 7 && $("#txtReferencia").val() == "")
    {
        //alert("Debes ingresar la txtReferencia de la transferencia para registrar el pago");
        alert("Wire transfer reference must be specified")
        return false;
    }


    if ($("#cboMetodoPago").val() == 8 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar la txtReferencia SPEI para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 3 && $("#txtReferencia").val() == "")
    {
        //alert("Debes ingresar el número de la tarjeta de regalo para registrar el pago");
        alert("Gift card number must be specified");
        return false;
    }

    if ($("#cboMetodoPago").val() == 4 && $("#txtReferencia").val() == "")
    {
        alert("Credit card voucher number must be specified");
        return false;
    }

    if ($("#cboMetodoPago").val() == 5 && $("#txtReferencia").val() == "")
    {
        alert("Debit card voucher number must be specified");
        return false;
    }


    if ($("#cboMetodoPago").val() == 6 && $("#hidencliente-caja").val() == "")
    {
        alert("Customer must be specified");
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
        {
            caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);
        }


    },
    agregarPago: function(tipo, tipostr, cantidad, txtReferencia)
    {
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

        $.ajax({
            type: 'POST',
            url: 'ajax.php?c=caja&f=agregaPago',
            dataType: 'json',
            data: {
                tipo: tipo,
                tipostr: tipostr,
                cantidad: cantidad,
                txtReferencia: txtReferencia
            },
            success: function(data) {

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
                        var regCantidad = $(document.createElement('td')).attr({'id': 'cantidad' + data.tipo}).html(data.cantidad).appendTo(registroCaja);
                        var regAccion = $(document.createElement('td')).css({'text-align' : 'center'}).appendTo(registroCaja);
                        var accion = $(document.createElement('img')).addClass('imgDelete').attr({'src': 'img/bor.png'}).appendTo(regAccion);

                        accion.bind('click', function() {
                            caja.eliminarPago(data.tipo);
                        });
                    }

                    $('#lblAbonoPago').text("$ " + data.abonado);
                    $('#lblPorPagar').text("$ " + data.porPagar);
                    $('#lblCambio').text("$ " + data.cambio);

                    $('#txtCantidadPago').val('');
                    $('#txtReferencia').val('');
                }
            }});
},
muestraReferenciaPago: function(valor)
{
    var elemento = $('#divReferenciaPago');
    var elTexto = $('#lblReferencia');
    $('#txtReferencia').val('');

    elemento.css({'display': 'block'});

    switch (parseInt(valor))
    {
        case 2 :
        $('#txtCantidadPago').val(caja.data["cargos"]["total"]);
        elTexto.text('Check number:');
        break;
        case 3:
        case 4:
        case 5:
        $('#txtCantidadPago').val(caja.data["cargos"]["total"]);
        elTexto.text('Card number:');
        break;
        case 6:
        $('#txtCantidadPago').val(caja.data["cargos"]["total"]);
        elTexto.text('Comments:');
        break;
        case 7 :
        $('#txtCantidadPago').val(caja.data["cargos"]["total"]);
        elTexto.text('Wire transfer reference:');
        break;
        case 8 :
        $('#txtCantidadPago').val(caja.data["cargos"]["total"]);
        elTexto.text('SPEI reference:');
        break;
        case 21 :
        $('#txtCantidadPago').val(caja.data["cargos"]["total"]);
        elTexto.text('Reference:');
        break;
        default :
        elemento.css({'display': 'none'});
        break;
    }
},
limpiaPago: function()
{

    $('#abonosPagos').html('<div class="form-control registroCaja nopagos"><div class="col-xs-12 text-center">No hay pagos</div></div>');
    $('#cboMetodoPago').val(1);
    $('#txtCantidadPago').val('');
    $('#lblTotalxPagar').text('');
    $('#lblAbonoPago').text('0.00');
    $('#lblReferencia').text('');
    $('#txtReferencia').text('');

    $('#lblAbonoPago').text('0.00');
    $('#lblPorPagar').text('0.00');
    $('#lblCambio').text('0.00');


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

                $.each(data.pagos, function(index, value) {

                    var abonosPagos = $('#divDesglosePagoTablaCuerpo');

                    var registroCaja = $(document.createElement('tr')).attr({'id': 'Pago' + index}).appendTo(abonosPagos);
                    var regTipo = $(document.createElement('td')).html(value.tipostr).appendTo(registroCaja);
                    var regCantidad = $(document.createElement('td')).attr({'id': 'cantidad' + index}).html(value.cantidad).appendTo(registroCaja);
                    var regAccion = $(document.createElement('td')).css({'text-align' : 'center'}).appendTo(registroCaja);
                    var accion = $(document.createElement('img')).addClass('imgDelete').attr({'src': 'img/bor.png'}).appendTo(regAccion);

                    accion.bind('click', function() {
                        caja.eliminarPago(index);
                    });

                });

                $('#lblAbonoPago').text(data.abonado);
                $('#lblPorPagar').text(data.porPagar);
                $('#lblCambio').text(data.cambio);
            } else if (data.statusInicio == false)
            {
                $('#modalPago').modal('hide');
                caja.inicioCaja(data);
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

    var codigo = $('#codigo').val();
    var propina = $('#propina').val();

    if ($('.nopagos').length)
    {
        alert('Debes saldar la deuda.');
        $('#txtCantidadPago').focus();
        return;
    }

    if ($('#lblPorPagar').text() != '0.00' && $('#lblPorPagar').text() != '$ 0.00')
    {
        alert('No has cubierto el total de la deuda.');
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
        })
    }


    $.ajax({
        url: 'ajax.php?c=caja&f=guardarVenta',
        type: 'POST',
        dataType: 'json',
        async: true,
        data: {
            idFact: $("#rfc").val(),
            documento: $("#documento").val(),
            cliente: $("#hidencliente-caja").val(),
            suspendida: $("#s_cliente").val(),
            propina: propina,
            comentario: $('#txtareacomentariosProducto').val(),
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
                caja.mensaje("Saving transaction");
            },
            success: function(resp) {
                if (resp.status)
                {
                 if($('#documento').val() == 2)
                 {
                    $('#lblComentarioE').html('la Factura.');
                }else{
                    $('#lblComentarioE').html('el Recibo de Ingresos.');
                }
                caja.observacionesFactura(resp);
            } else
            {
                caja.eliminaMensaje();
                alert(resp.msg);
            }
        },
        error: function(data) {
            caja.eliminaMensaje();
            alert(data.msg);
        }
    });
},
comprobante: function(resp, mensaje) {
   /* var rfc = $('#rfc').val();
    alert(rfc);
    $.ajax({
        url: 'ajax.php?c=caja&f=findAddenda',
        type: 'POST',
        dataType: 'json',
        data : {rfc:rfc,}
    })
    .done(function(data) {
        //console.log(data.adenda[0].xml);
        //alert(data.adenda[0].xml);
        $('#addenda').text(data.adenda[0].xml);
                $('#modalAdenda').dialog({ width:800,
                                           heigth:600,
                    buttons:
                    [
                    {
                        text: "Aceptar",
                        class: 'btn btn-success col-xs-5',
                        click: function()
                        {

                            $.ajax({
                                type: 'POST',
                                url: 'ajax.php?c=caja&f=agregarPropina',
                                dataType: 'json',
                                data: {idArticulo: resp[4], cantidad: $('#txtPropina').val()},
                                success: function(data) {
                                    caja.pintaResultados(data, false);
                                            }}); //end ajax

                            $(this).modal('hide');
                        }
                    },
                    {
                        text: "Cancelar",
                        class: 'btn btn-danger col-xs-5 pull-right',
                        click: function()
                        {
                            $(this).modal('hide');
                        }
                    }
                    ],
                    position: 'top',
                    modal: true
                });
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    return; */

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
        },
        beforeSend: function() {



        },
        success: function(resp) {

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
            if (resp.success == '3') {
                alert('Venta realizada con exito.');

                caja.modalComprobante("../../modulos/punto_venta_nuevo_en/ticket.php?idventa=" + resp.idVenta, true)

                    //window.location.reload();
                    return false;
                }
                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                ================================================ */
                if (resp.success == 0 || resp.success == 5) {
                    if (resp.success == 0) {
                        alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                    }

                    caja.modalComprobante("../../modulos/punto_venta_nuevo_en/ticket.php?idventa=" + resp.idVenta, true);

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
                            caja.mensaje("Saving Invoice 2");
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
                            doc: $('#documento').val()
                        },
                        beforeSend: function() {
                            if($('#documento').val() == 2)
                            {
                                caja.mensaje("Saving invoice");
                            }else if($('#documento').val() == 3)
                            {
                                caja.mensaje("Saving income receipt");
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
                                    caja.mensaje("Sending invoice");
                                },
                                success: function(resp) {
                                    caja.eliminaMensaje();
                                    if(resp.cupon==false){
                                        caja.modalComprobante('../../modulos/facturas/'+uid+'.pdf', false);
                                    }else{
                                        caja.modalComprobante('../../modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false);
                                    }
                                    //window.open('../../modulos/facturas/' + uid + '.pdf');
                                    //window.location.reload();
                                },
                                error: function() {
                                    caja.eliminaMensaje();
                                }
                            });

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
                //window.location.reload();
            }
        });
},
observacionesEnviar: function(){
    $('#modal_Observaciones').modal("hide");
    if($('#documento').val() == 2)
    {
        caja.mensaje("Generating Invoice");
    }else{
        caja.mensaje("Generating Income receipt");
    }
    caja.comprobante(obsResp, $('#txtareaObservaciones').val());
},
observacionesFactura: function(resp) {
    obsResp = resp;
    if ($('#documento').val() == 1)
    {
        caja.comprobante(resp, false);
        caja.mensaje("Generating Ticket");
    } else {
        $('#modal_Observaciones').modal({backdrop: 'static'});
    }
},
tipoDocumento: function(id)
{
    if (id == 2 || id == 3)
    {
        $.ajax({
            type: 'POST',
            url: 'ajax.php?c=caja&f=checatimbres',
            success: function(resp) {
             /*   if (resp == 0)
                { */
                    $("#labelrfc").show();
                    $("#selectrfc").show();
                    $("#selectConsumo").show();
               // }
             /*   else
                {
                    alert("No tienes timbres de factura disponibles, se han hagotado");
                    $('#documento option[value=1]').prop('selected', 'selected');
                    //  $("#documento option[value=1]").attr("selected",true);
                } */
            }});
    }
    else
    {
        $("#labelrfc").hide();
        $("#selectrfc").hide();
        $("#selectConsumo").hide();
    }
},
isNumberKey: function(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    return (charCode <= 13 || (charCode >= 48 && charCode <= 57) || charCode == 46);
},
suspender: function() {

    if ($("#hidencliente-caja").val() == "") {
        alert("Nesesita seleccionar un cliente para suspender la venta!");
        return false;
    }

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
    $.ajax({
        type: 'POST',
        url: 'ajax.php?c=caja&f=cargarSuspendida',
        dataType: 'json',
        data: {
            id_susp: $('#s_cliente').val()
        },
        success: function(data) {
            if (data.status)
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
eliminarSuspendida: function() {

        //caja.eliminaMensaje();
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
    mensaje: function(mensaje) {

        $('#lblMensajeEstado').text(mensaje);
        $('#modalEstadoMensaje').modal("show");/*.dialog({
            //position: 'top',
            modal: true,
            dialogClass: 'no-close',
            resizable: false,
            draggable: false
        });*/
    },
    eliminaMensaje: function() {

        $('#modalEstadoMensaje').modal('hide');
    },
    modalComprobante: function(src, ticket) {

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
    addCommas: function(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
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

        $.ajax({
            type: 'POST',
            url: 'ajax.php?c=caja&f=Iniciarcaja',
            data: {
                sucursal: $("#sucursalId").val(),
                monto: $("#iniciocaja").val()
            },
            success: function(resp) {
                $('#inicio_caja').modal("hide");
            }
        });//end ajax
    },
    cajaCancelar: function(){
        $('#inicio_caja').modal("hide");
    },
    inicioCaja: function(data)
    {
        if (data.inicio !== undefined && data.inicio != false)
        {
            var contenedor = $('#divContSucursal');
            contenedor.empty();
            switch (data.inicio.status)
            {
                case 1:

                var sucursalOperando = $(document.createElement('label')).addClass('text-left control-label col-xs-7 pull-left translated').text('Operation brand').appendTo(contenedor);
                var sucursalNombre = $(document.createElement('label')).addClass('text-left control-label col-xs-5').text(data.inicio.sucursalNombre).appendTo(contenedor);
                var sucursalid = $(document.createElement('hidden')).attr({'id': 'sucursalId'}).val(data.inicio.sucursalId).appendTo(contenedor);

                $('#lblSaldo').text('Saldo actual en caja');
                $('#saldocaja').text(data.inicio.saldo);

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
  redondeo: function(data)
    {
        //console.log(data);
       var ex = data * 1;
       var numero = ex.toFixed(2);
      /* numero = ''+data;
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
        return j[0]+'.'+t; */
        return numero;
        //return ex;
    },
}
function hideDescuento(){

 if($('#selectPrecios').find('option:selected').hasClass('0')){
            $('#divDescuento').hide();
            }else{
            $('#divDescuento').show();
            }
}
function limpiaFrame(){

    $('#frameComprobante').prop('src', false);
    $('#frameComprobante').removeAttr('src');
    window.location.reload();
}
