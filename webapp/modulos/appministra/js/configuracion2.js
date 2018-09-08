$(function()
 {
    if(parseInt($("#pestania").val()))
    {
        $('#myTabs a').eq($("#pestania").val()).click();

    }
    
    if(parseInt($("#pestania_prod").val()))
    {
        $('#myTabs a').eq($("#pestania_prod").val()).click();

    }
    $("#blanco").hide();
 });



$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

//INICIAN FUNCIONES DEL MODULO CLASIFICACIONES DE PRODUCTO
function inicializa_lista_car_prod(tipo)
{
    $.post('ajax.php?c=configuracion&f=lista_car_prod&tipo='+tipo, 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                if(tipo == 'gral')
                {
                    $('#tabla-gral').DataTable( {
                        dom: 'Bfrtip',
                        buttons: [ 'pageLength', 'excel' ],
                        language: {
                            buttons: {
                                pageLength: "Mostrar %d filas"
                            },
                            search: "Buscar:",
                            lengthMenu:"Mostrar _MENU_ elementos",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            }
                         },
                         data:datos,
                         columns: [
                            { data: 'id' },
                            { data: 'nombre' },
                            { data: 'mod' },
                            { data: 'status' }
                        ]
                    });
                    $('#tabla-gral_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                    $("#tabla-gral").before($("#boton_virtual1").html());
                    $("#boton_virtual1").hide();
                }

                 if(tipo == 'esp')
                    {
                        $('#tabla-esp').DataTable( {
                            dom: 'Bfrtip',
                            buttons: [ 'pageLength', 'excel' ],
                            language: {
                                buttons:{
                                    pageLength: "Mostrar %d filas"
                                },
                                search: "Buscar:",
                                lengthMenu:"Mostrar _MENU_ elementos",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                }
                             },
                             data:datos,
                             columns: [
                                { data: 'id' },
                                { data: 'nombre' },
                                { data: 'general' },
                                { data: 'mod' },
                                { data: 'status' }
                            ]
                        });
                        $('#tabla-esp_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                        $("#tabla-esp").before($("#boton_virtual2").html());
                        $("#boton_virtual2").hide();
                    }
            });
}

function nueva_car(tipo)
{
    $("#padre").html("<option value='0'>Ninguno</option>");
    $("#nombre").val('')
    if(tipo == 'gral')
    {
        $("h4").text('Nueva Caracteristica General')
        $("#id").val('0');
        $("#id_label").hide();
        $("#padre").val('0').hide();
        $("#padre_label").hide();
        $("#guardar").attr('onclick',"guardar_car_prod('gral')");
    }

    if(tipo == 'esp')
    {
        $.post('ajax.php?c=configuracion&f=lista_generales',  
            function(data)
            {
                $("h4").text('Nueva Característica Específica')
                $("#id").val('0');
                $("#id_label").hide();
                $("#padre").append(data);
                $("#padre_label").show();
                $("#padre").show().val('0');
                $("#guardar").attr('onclick',"guardar_car_prod('esp')"); 
            });
    }
    $("#status").val(1).show();
    $("#label-warning").hide();
}

function cancelar_car_prod()
{
    $('.bs-example-modal-sm').modal('hide');
    nueva_car('gral');
}

function guardar_car_prod(tipo)
{
    if(tipo == 'gral')
    {
        if($("#nombre").val() != '')
        {
            $.post('ajax.php?c=configuracion&f=guardar_car_prod&tipo=gral', 
            {
                id: $("#id").val(),
                nombre: $("#nombre").val(),
                status: $("#status").val()
            }, 
            function(data)
            {
             //   alert(data)
                if(parseInt(data))
                {
                    cancelar_car_prod();  
                    window.location = 'index.php?c=configuracion&f=caracteristicasProd&p=0';
                }
                else
                {
                    alert("No se puede completar la accion debido a que tiene caracteristicas hijo activos.")
                }
            });
        }
        else
        {
            $("#label-warning").fadeIn(500);
        }
    }

    if(tipo == 'esp')
    {
        if($("#nombre").val() != '' && $("#padre").val() != '0')
        {
            $.post('ajax.php?c=configuracion&f=guardar_car_prod&tipo=esp', 
            {
                id: $("#id").val(),
                nombre: $("#nombre").val(),
                padre: $("#padre").val(),
                status: $("#status").val()
            }, 
            function(data)
            {
                //alert(data)
                if(parseInt(data))
                {
                    cancelar_car_prod();  
                    window.location = 'index.php?c=configuracion&f=caracteristicasProd&p=1';
                }
                else
                {
                    alert("No se puede completar la accion debido a que tiene caracteristicas padre inactivos.")
                }
            });
        }
        else
        {
            $("#label-warning").fadeIn(500);
        }
    }
}

function modificar_car_prod(id,tipo)
{
    $("#label-warning").hide();
    $("#blanco").show()
    //alert(tipo)
    
        nueva_car(tipo)
        
        $.post('ajax.php?c=configuracion&f=datos_car_prod', 
        {
            id: id,
            tipo:tipo
        }, 
        function(data)
        {
            if(tipo == 'gral')
                $("h4").text("Modificar Característica General")
            if(tipo == 'esp')
                $("h4").text("Modificar Característica Específica")
            
            var datos = data.split('Ω');
            $("#id").val(datos[0])
            $("#nombre").val(datos[1])
            $("#status").val(datos[2])
            if(tipo != "gral")
                $("#padre").val(datos[3])
            $("#guardar").attr("onclick","guardar_car_prod('"+tipo+"')")
            $("#blanco").hide()
        });
}

//TERMINAN FUNCIONES DEL MODULO CLASIFICACIONES DE PRODUCTO
//INICIAN FUNCIONES DEL MODULO TIPO DE CREDITO
function inicializa_listacred()
{
    $.post('ajax.php?c=configuracion&f=listaCred', 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [ 'pageLength', 'excel' ],
                    language: {
                        buttons: {
                            pageLength: "Mostrar %d filas"
                        },
                        search: "Buscar:",
                        lengthMenu:"Mostrar _MENU_ elementos",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     },
                     data:datos,
                     columns: [
                        { data: 'id' },
                        { data: 'nombre' },
                        { data: 'clave' },
                        { data: 'mod' },
                        { data: 'elim' }
                    ]
                });
                 $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                $("#tabla-data").before($("#boton_virtual").html());
                $("#boton_virtual").hide();
            });
}

function modificar_cred(id)
{
    $("#blanco").show()
    $("#label-warning").hide();
    $("h4").text("Modificar Tipo de Crédito")
    $.post('ajax.php?c=configuracion&f=datos_cred', 
        {
            id: id
        }, 
        function(data)
        {
            var datos = data.split('Ω');
            $("#idcred").val(id)
            $("#nombrecred").val(datos[0])
            $("#clavecred").val(datos[1])
            $("#status").val(datos[2])
            $("#blanco").hide()
        });
}

function nuevo_cred()
{
    $("h4").text("Nuevo Tipo de Crédito")
    $("#idcred").val(0)
    $("#nombrecred").val('')
    $("#clavecred").val('')
    $("#status").val(1).show()
}

function guardar_cred()
{
    var tipocred;

    //alert(validar)
    if($("#nombrecred").val() != '' && $("#clavecred").val() != '')
    {        
         $.post('ajax.php?c=configuracion&f=guardar_cred', 
            {
                idcred: $("#idcred").val(),
                nombrecred: $("#nombrecred").val(),
                clavecred: $("#clavecred").val(),
                status:$("#status").val()
            }, 
            function()
            {
                //alert(data)
                $('.bs-example-modal-sm').modal('hide');
                //nuevo_clas();
                location.reload();
            });
    }
    else
    {
        $("#label-warning").fadeIn(500);
    }
}

function cancelar_cred()
{
    $('.bs-example-modal-sm').modal('hide');
    nuevo_cred();
}
//TERMINAN FUNCIONES DEL MODULO TIPOS DE CREDITO
//INICIAN FUNCIONES DEL MODULO LISTAS DE PRECIO
function inicializa_listaprec()
{
    $.post('ajax.php?c=configuracion&f=listaPrec', 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [ 'pageLength', 'excel' ],
                    language: {
                        buttons: {
                            pageLength: "Mostrar %d filas"
                        },
                        search: "Buscar:",
                        lengthMenu:"Mostrar _MENU_ elementos",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     },
                     data:datos,
                     columns: [
                        { data: 'id' },
                        { data: 'tipo' },
                        { data: 'nombre' },
                        { data: 'clave' },
                        { data: 'porcentaje' },
                        { data: 'descuento' },
                        { data: 'mod' },
                        { data: 'elim' }
                    ]
                });
                 $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                $("#tabla-data").before($("#boton_virtual").html());
                $("#boton_virtual").hide();
            });
}

function modificar_listaprec(id)
{
    $("#blanco").show()
    $("#label-warning").hide();
    $("h4").text("Modificar Lista de Precio")
    $.post('ajax.php?c=configuracion&f=datos_listaprec', 
        {
            id: id
        }, 
        function(data)
        {
            var datos = data.split('Ω');
            $("#idlistaprec").val(id)
            $("#nombrelistaprec").val(datos[0])
            $("#clavelistaprec").val(datos[1])
            $("#porcentaje").val(datos[2])
            $("#descuento").val(datos[3])
            $("#status").val(datos[4])
            $("#selectTipoPrecio").val(datos[5])
            $("#blanco").hide()
            if( $('#selectTipoPrecio').val() == 1){
                $('#rowPorcentaje , #rowDescuento').show();
            }else  {
                $('#rowPorcentaje , #rowDescuento').hide();
                $("#porcentaje").val(0);
                $('#descuento').val(0);
            }
        });
}

function nuevo_listaprec()
{
    $("h4").text("Nueva Lista de Precios")
    $("#idlistaprec").val(0)
    $("#nombrelistaprec").val('')
    $("#clavelistaprec").val('')
    $("#porcentaje").val('')
    $("#descuento").val(1)
    $("#status").val(1).show()
    $("#selectTipoPrecio").val("1")
    $("#porcentaje").val(0)
    $('#descuento').val(0)
    $('#rowPorcentaje , #rowDescuento').show()
}

function guardar_listaprec()
{
    var tipolistaprec;

    //alert(validar)
    if($("#nombrelistaprec").val() != '' && $("#clavelistaprec").val() != '' && $("#porcentaje").val() != '')
    {        
         $.post('ajax.php?c=configuracion&f=guardar_listaprec', 
            {
                idlistaprec: $("#idlistaprec").val(),
                nombrelistaprec: $("#nombrelistaprec").val(),
                clavelistaprec: $("#clavelistaprec").val(),
                porcentaje: $("#porcentaje").val(),
                descuento: $("#descuento").val(),
                status:$("#status").val(),
                tipo:$("#selectTipoPrecio").val()
            }, 
            function()
            {
                //alert(data)
                $('.bs-example-modal-sm').modal('hide');
                //nuevo_clas();
                location.reload();
            });
    }
    else
    {
        $("#label-warning").fadeIn(500);
    }
}

function cancelar_listaprec()
{
    $('.bs-example-modal-sm').modal('hide');
    nuevo_listaprec();
}

function changeTipoPrecio(){
    if( $('#selectTipoPrecio').val() == 1){
        $('#rowPorcentaje , #rowDescuento').show();
    }else {
        $('#rowPorcentaje , #rowDescuento').hide();
        $("#porcentaje").val(0);
        $('#descuento').val(0);
    }
}
//TERMINAN FUNCIONES DEL MODULO LISTAS DE PRECIO

//INICIAN FUNCIONES DEL MODULO UNIDADES DE MEDIDA Y PESO
function inicializa_medida()
{
    $.post('ajax.php?c=configuracion&f=listaMedida', 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [ 'pageLength', 'excel' ],
                    language: {
                        buttons: {
                            pageLength: "Mostrar %d filas"
                        },
                        search: "Buscar:",
                        lengthMenu:"Mostrar _MENU_ elementos",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     },
                     data:datos,
                     columns: [
                        { data: 'clave' },
                        { data: 'nombre' },
                        { data: 'factor' },
                        { data: 'unidad_n' },
                        { data: 'mod' },
                        { data: 'elim' }
                    ]
                });
                 $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                $("#tabla-data").before($("#boton_virtual").html());
                $("#boton_virtual").hide();
            });
}

function modificar_medida(id)
{
    $("#base").html("<option value='0'>Ninguno</option>");
    $("#label-warning").hide();
    $.post('ajax.php?c=configuracion&f=lista_unidades_base',  
        function(data)
        {
            $("h4").text("Nueva Unidad de medida o peso")
            $("#idmedida").val(0)
            $("#clavemedida").val('')
            $("#nombremedida").val('')
            $("#factor").val(0)
            $("#base").append(data);
            $("#base").val('0');
            $("#status").val(1).show();



            $("#label-warning").hide();
            $("#blanco").show()
            $("h4").text("Modificar Unidad de medida o peso")
            $.post('ajax.php?c=configuracion&f=datos_medida', 
                {
                    id: id
                }, 
                function(data)
                {
                    //alert(data)
                    var datos = data.split('Ω');
                    $("#idmedida").val(id)
                    $("#clavemedida").val(datos[0])
                    $("#nombremedida").val(datos[1])
                    $("#factor").val(datos[2])
                    $("#base").val(datos[3])
                    $("#status").val(datos[4])
                    $("#clavemedidaSat").val(datos[5])
                    $("#blanco").hide()

            });
    });

/*    nueva_medida()
    $("#label-warning").hide();
    $("#blanco").show()
    $("h4").text("Modificar Unidad de medida o peso")
    $.post('ajax.php?c=configuracion&f=datos_medida', 
        {
            id: id
        }, 
        function(data)
        {
            //alert(data)
            var datos = data.split('Ω');
            $("#idmedida").val(id)
            $("#clavemedida").val(datos[0])
            $("#nombremedida").val(datos[1])
            $("#factor").val(datos[2])
            $("#base").val(datos[3])
            $("#status").val(datos[4])
            $("#blanco").hide()

        });*/
}

function nueva_medida()
{
    $("#base").html("<option value='0'>Ninguno</option>");
    $("#label-warning").hide();
    $.post('ajax.php?c=configuracion&f=lista_unidades_base',  
            function(data)
            {
                $("h4").text("Nueva Unidad de medida o peso")
                $("#idmedida").val(0)
                $("#clavemedida").val('')
                $("#nombremedida").val('')
                $("#factor").val(0)
                $("#base").append(data);
                $("#base").val('0')
                $("#status").val(1).show()
            });

}

function guardar_medida()
{


    //alert(validar)
    if($("#clavemedida").val() != '' && $("#nombremedida").val() != '' && $("#factor").val() != '0')
    {        
         $.post('ajax.php?c=configuracion&f=guardar_medida', 
            {
                idmedida: $("#idmedida").val(),
                clavemedida: $("#clavemedida").val(),
                nombremedida: $("#nombremedida").val(),
                factor: $("#factor").val(),
                unidad_base: $("#base").val(),
                status:$("#status").val(),
                calve_sat:$('#clavemedidaSat').val()
            }, 
            function()
            {
                //alert(data)
                $('.bs-example-modal-sm').modal('hide');
                //nuevo_clas();
                location.reload();
            });
    }
    else
    {
        $("#label-warning").fadeIn(500);
    }
}

function cancelar_medida()
{
    $('.bs-example-modal-sm').modal('hide');
    nueva_medida();
}
//TERMINAN FUNCIONES DEL MODULO UNIDADES DE MEDIDA Y PESO
//INICIAN FUNCIONES DEL MODULO IMPUESTOS
function inicializa_impuestos()
{
    $.post('ajax.php?c=configuracion&f=listaImpuestos', 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [ 'pageLength', 'excel' ],
                    language: {
                        buttons: {
                            pageLength: "Mostrar %d filas"
                        },
                        search: "Buscar:",
                        lengthMenu:"Mostrar _MENU_ elementos",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     },
                     data:datos,
                     columns: [
                        { data: 'nombre' },
                        { data: 'valor' },
                        { data: 'mod' },
                        { data: 'elim' }
                    ]
                });
                 $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                $("#tabla-data").before($("#boton_virtual").html());
                $("#boton_virtual").hide();
            });
}

function modificar_impuesto(id)
{
    nuevo_impuesto()
    $("#label-warning").hide();
    $("#blanco").show()
    $("h4").text("Modificar Impuesto")
    $.post('ajax.php?c=configuracion&f=datos_impuesto', 
        {
            id: id
        }, 
        function(data)
        {
            //alert(data)
            var datos = data.split('Ω');
            $("#idimpuesto").val(id)
            $("#nombre").val(datos[0])
            $("#valor").val(datos[1])
            $("#status").val(datos[2])
            $("#blanco").hide()

        });
}

function nuevo_impuesto()
{
    $("#base").html("<option value='0'>Ninguno</option>");
    $("#label-warning").hide();
    
    $("h4").text("Nuevo Impuesto")
    $("#idimpuesto").val(0)
    $("#nombre").val('')
    $("#valor").val('')
    $("#status").val(1)
$("#blanco").hide();
}

function guardar_impuesto()
{


    //alert(validar)
    if($("#nombre").val() != '' && $("#valor").val() != '')
    {        
         $.post('ajax.php?c=configuracion&f=guardar_impuesto', 
            {
                idimpuesto: $("#idimpuesto").val(),
                nombre: $("#nombre").val(),
                valor: $("#valor").val(),
                status:$("#status").val()
            }, 
            function()
            {
                //alert(data)
                $('.bs-example-modal-sm').modal('hide');
                //nuevo_clas();
                location.reload();
            });
    }
    else
    {
        $("#label-warning").fadeIn(500);
    }
}

function cancelar_impuesto()
{
    $('.bs-example-modal-sm').modal('hide');
    nuevo_impuesto();
}
//TERMINAN FUNCIONES DEL MODULO IMPUESTOS
