//INICIAN FUNCIONES DEL MODULO PROVEEDORES
function inicializa_proveedores()
{
    $.post('ajax.php?c=configuracion&f=listaProveedores', 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    language: {
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
                        { data: 'codigo' },
                        { data: 'razon_social' },
                        { data: 'rfc' },
                        { data: 'municipio' },
                        { data: 'estado' },
                        { data: 'telefono' },
                        { data: 'email' },
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
    nueva_medida()
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

        });
}

function nuevo_proveedor()
{
    $("#blanco").hide();
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

function cancelar_medida()
{
    $('.bs-example-modal-sm').modal('hide');
    nueva_medida();
}
//TERMINAN FUNCIONES DEL MODULO PROVEEDORES