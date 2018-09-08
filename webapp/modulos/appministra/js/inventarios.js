$(function() {
    $("#blanco").hide();
    window.memoseries = '';
    window.memolotes = '';
    window.memopedimentos = '';
    window.caracteristicasg = '';
    window.pedimentos_gv = 0;
    window.lotes_gv = 0;
    window.series_gv = 0;
    window.destino = $("#almacen_destino").html();
 });

//INICIAN FUNCIONES DEL MODULO ENTRADAS
function inicializa_movimientos() {
    $.post('ajax.php?c=inventarios&f=listaMovimientos', 
        function(data) {
            var datos = jQuery.parseJSON(data);
            $('#tabla-data').DataTable( {
                dom: 'Bfrtip',
                buttons: ['excel'],
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
                 "order": [[ 0, "desc" ]],
                 data:datos,
                 columns: [
                    { data: 'id' },
                    { data: 'fecha' },
                    { data: 'producto' },
                    { data: 'cantidad' },
                    { data: 'importe' },
                    { data: 'almacen_origen' },
                    { data: 'almacen_destino' },
                    { data: 'empleado' },
                    { data: 'tipo_traspaso' },
                    { data: 'referencia' },
                    { data: 'accion' }
                ]
            });
             $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
            $("#tabla-data").before($("#boton_virtual").html());
            $("#boton_virtual").remove();
            $(".rojo").parent().parent().css("color","red")
           
           if(datos[0]['id'])
               $(".lay").hide();
        });
}

//INICIAN FUNCIONES DEL MODULO TRASPASOS
function inicializa_movimientos2()
{
    $.post('ajax.php?c=inventarios&f=listaTraspasos&t=0', 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: ['excel'],
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
                     "order": [[ 0, "desc" ]],
                     data:datos,
                     columns: [
                        { data: 'clave' },
                        { data: 'fecha' },
                        { data: 'origen' },
                        { data: 'destino' },
                        { data: 'solicitante' },
                        { data: 'referencia' },
                        { data: 'accion' }
                    ]
                });
                 $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                $("#tabla-data").before($("#boton_virtual").html());
                $("#boton_virtual").remove();
                $(".rojo").parent().parent().css("color","red")
               
               if(datos[0]['id'])
                   $(".lay").hide();
            });
}

function inicializa_movimientos3()
{
    $.post('ajax.php?c=inventarios&f=listaTraspasos&t=1', 
            function(data) 
            {
                var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: ['excel'],
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
                     "order": [[ 0, "asc" ]],
                     data:datos,
                     columns: [
                        { data: 'clave' },
                        { data: 'fecha' },
                        { data: 'origen' },
                        { data: 'destino' },
                        { data: 'solicitante' },
                        { data: 'referencia' },
                        { data: 'accion' }
                    ]
                });
                 $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                $("#tabla-data").before($("#boton_virtual").html());
                $("#boton_virtual").remove();
                $(".rojo").parent().parent().css("color","red")
               
               if(datos[0]['id'])
                   $(".lay").hide();
            });
}

function nuevo_movimiento()
{

    $("#producto").val(0).trigger("change")
    $("#cantidad").val('1')
    $("#importe").val(0)
    $("#almacen_origen").val(0).trigger("change")

    if(parseInt($("#tipo").val()) != 2)
        $("#almacen_destino").val(0).trigger("change")

    $("#importe").val(0)
    $("#referencia").val('')
    if(parseInt($("#id_traslado").val()))
    {
        $("#almacen_destino_final").val($("#almacen_destino_traslado").val())
        $("#referencia").val($("#referencia_tras").val())
        $("#fecha_mov").val($("#fecha_tras").val())
    }

}

function nuevo_traslado()
{
    $("#nuevo_modificar").val(0)
    $("#cancelar_tras").css("display","inline")
    $("#almacen_origen_final").val(0).trigger("change").attr("disabled",false)
    $("#almacen_destino_traslado").val(0).attr("disabled",false)
    $("#fecha_tras").val('').attr("disabled",false)
    $("#referencia_tras").val('')

    $("#mov_productos").html("");

    if(parseInt($("#tipo").val()) != 2)
        $("#almacen_destino_final").val(0).trigger("change")

    $("#referencia").val('')

    //Genera un traslado en ceros
     $.post('ajax.php?c=inventarios&f=genera_traslado', 
            {},
            function(data)
            {
                var datos = data;
                datos = datos.split("**/**");
                $("#num_traslado").html("<b>"+datos[0]+"</b>")
                $("#id_traslado").val(datos[1])
            });

}

function modificar_traspaso(idtras)
{
    $("#nuevo_modificar").val(1)
    $("#cancelar_tras").css("display","none")
    $("#id_traslado").val(idtras)
    $.post('ajax.php?c=inventarios&f=info_traspaso_mod', 
            {
                idtras : idtras
            },
            function(data)
            {
                var datos = data;
                datos = datos.split("**/**");
                $("#num_traslado").html("<b>"+datos[0]+"</b>")
                $("#almacen_origen_final").val(datos[1]).attr("disabled",true)
                $("#almacen_destino_traslado").val(datos[2]).attr("disabled",true)
                $("#fecha_tras").val(datos[3]).attr("disabled",true)
                $("#referencia_tras").val(datos[4])
                $.post('ajax.php?c=inventarios&f=tras_prods', 
                            {
                                tras    :   idtras
                            },
                            function(data2)
                            {
                                if(data2)
                                    $("#mov_productos").html(data2);
                                else
                                    $("#mov_productos").html("");
                            });
            });
}

function borra_recepcion()
{
    $("#id_traspaso").val(0)
    $("#num_traspaso").html("");
    $("#id_traspaso").html("");
    $("#origen").html("");
    $("#destino").html("");
    $("#id_destino").val(0);
    $("#fecha_tras_r").html("");
    $("#solicitante_r").html("");
    $("#referencia_tras_r").html("");
    $("#tabla").html("");
}
function cerrar_recepcion()
{
    borra_recepcion();
    $('.bs-recepcion-modal-lg').modal('hide');
}

function pad (n, length) {
    var  n = n.toString();
    while(n.length < length)
         n = "0" + n;
    return n;
}

function recibir_traspaso(id_tras)
{
    borra_recepcion();
    $("#id_traspaso").val(id_tras);
    $.post('ajax.php?c=inventarios&f=info_traslado', 
            {
                id_tras : id_tras
            },
            function(data)
            {
                var datos = data;
                //alert(datos)
                datos = datos.split("**/**");
                $("#num_traspaso").html("<b>"+datos[1]+"</b>");
                $("#id_traspaso").html("<b>"+datos[0]+"</b>");
                $("#origen").html(datos[3]);
                $("#destino").html(datos[4]);
                $("#id_destino").val(datos[2]);
                $("#fecha_tras_r").html(datos[6]);
                $("#solicitante_r").html(datos[5]);
                $("#referencia_tras_r").html(datos[7]);
                var date = new Date();
                $('#fecha_recepcion').val(date.getFullYear()+"-"+pad(date.getMonth()+1 , 2)+"-"+pad(date.getDate() , 2));
            });

    $.post('ajax.php?c=inventarios&f=info_traslado_movimientos', 
            {
                id_tras : id_tras
            },
            function(data)
            {
                $("#tabla").html(data);
                $(".fal").attr("readonly",true)
            });
}

function guardar_recepcion()
{
    var valores = '';
    var idMov;
    $(".rec").each(function(i,selected)
    {
        idMov = selected.id;
        idMov = idMov.replace('rec-','')
        valores += idMov+":"+selected.value+"**/**";
    });
    $.ajax({
                    async:false,
                    url:"ajax.php?c=inventarios&f=guardar_recepcion",
                    type: 'POST',
                    data:
                    {
                       idrec        : $("#id_traspaso").val(),
                       idsMovs      : valores,
                       origen       : $("#id_origen_real").val(),
                       destino      : $("#id_destino").val(),
                       fecha        : $("#fecha_recepcion").val(),
                       comentario   : $("#comentario").val()


                    },
                    success: function(data)
                    {
                        //alert(data)
                        if(data)
                        {
                            alert("Se realizó la recepcion exitosamente.")
                            location.reload();
                        }
                        else
                            alert("Ocurrio un error, revise porfavor.")
                    }
                });
}

function faltantes(id)
{
    var cantidad = $("#cant-"+id).text();
    var recibidas = $("#rec-"+id).val();
    var faltantes = parseFloat(cantidad) - parseFloat(recibidas)
    
    $("#fal-"+id).val(faltantes)
    if(faltantes < 0 )
    {
        alert("La cantidad recibida no puede ser mayor a la cantidad enviada");
        $("#fal-"+id).val(0);
        $("#rec-"+id).val($("#cant-"+id).text());
    }
}

function guardar_movimiento_traslado()
{
    $("#almacen_origen_final,#almacen_destino_traslado").prop('disabled',true)
    guardar_movimiento($("#id_traslado").val());
}

function cancelar_movto(id)
{
    
    if(confirm("Esta seguro que desea cancelar este movimiento?"))
    {
        $.post('ajax.php?c=inventarios&f=cancelar_movto', 
                {
                    id : id
                },
                function(data)
                {
                    if(data)
                    {
                        console.log("Movimiento cancelado: "+id)
                        $.post('ajax.php?c=inventarios&f=tras_prods', 
                            {
                                tras    :   data
                            },
                            function(data2)
                            {
                                if(data2)
                                    $("#mov_productos").html(data2);
                                else
                                    $("#mov_productos").html("");
                            });
                    }
                    else
                    {
                        alert("Hubo un error.")
                    }
                });
    }
}

function guardar_traslado()
{
    $.post('ajax.php?c=inventarios&f=guardar_traslado', 
            {
                tras            : $("#id_traslado").val(),
                origen          : $("#almacen_origen_final").val(),
                destino         : $("#almacen_destino_traslado").val(),
                fecha           : $("#fecha_tras").val(),
                referencia      : $("#referencia_tras").val(),
                nuevo_modificar : $("#nuevo_modificar").val()
            },
            function(data)
            {
                //alert(data)
                if(parseInt(data))
                    location.reload();
                else
                    alert("Hubo un error, revise su informacion.")
            });
}

function guardar_movimiento(t)
{
    //alert(t)
    var caracteristicas = '';
    var pedimentos = '';
    var lotes = '';
    var series = '';
    if(confirm("Esta seguro que desea guardarlo? ya no podrá modificarse."))
    {
        if(parseInt($('#tipo').val()) == 1)
        {
            if(parseInt(window.pedimentos_gv))
                pedimentos = $("#numero_pedimento").val()+"@|@"+$("#aduana").val()+"@|@"+$("#numero_aduana").val()+"@|@"+$("#cambio").val()+"@|@"+$("#fecha_pedimento").val();

            if(parseInt(window.lotes_gv))
                lotes = $("#numero_lote").val()+"@|@"+$("#fecha_fabricacion").val()+"@|@"+$("#fecha_caducidad").val();

            if(parseInt(window.series_gv))
            {
                for(i=1;i<=parseFloat($("#cantidad").val());i++)
                    series += $("#serie-"+i).val()+"@|@";
            }
        }
        else
        {
            if(parseInt(window.pedimentos_gv))
                pedimentos = $("#pedimentos").val()
            
            if(parseInt(window.lotes_gv))
                lotes = $("#lotes").val()

            if(parseInt(window.series_gv))
                $("#series :selected").each(function(i,selected)
                {
                    series += selected.value+"@|@";
                });
            //alert(series)
        }
            

        if ($("#numCarac").length) 
        {

            var padre,hijo;
            var cadena = '';
            for(i=1;i<=parseInt($("#numCarac").val());i++)
            {
                padre = $("#carac-"+i).attr("idpadre");
                hijo = $("#carac-"+i).val();
                if(i!=1)
                    cadena += ",";
                cadena += "'"+padre+"'=>'"+hijo+"'";
            }
            caracteristicas = cadena;
            //alert(caracteristicas)
        }

        

        var validaciones = 0;
        var mensaje = "";
        
        if(parseInt($("#producto").val()))
            validaciones++;
        else
            mensaje += "Ingrese un producto\n";
        
        if(parseInt($("#cantidad").val()))
            validaciones++;
        else
            mensaje += "Ingrese una cantidad\n";

        if(parseInt($("#importe").val()))
            validaciones++;
        else
            mensaje += "Ingrese un importe\n";

        if(parseInt($("#almacen_origen").val()) || parseInt($("#almacen_destino").val()))
            validaciones++;
        else
            mensaje += "Ingrese por lo menos un almacen\n";

        if($("#fecha_mov").val() != '')
            validaciones++;
        else
            mensaje += "Ingrese una fecha\n";

        var numVal = 5
        if(parseInt(window.pedimentos_gv) && parseInt($("#tipo").val()) == 1)
        {
            numVal++
            if($("#numero_pedimento").val() != '')
                validaciones++;
            else
                mensaje += "Ingrese el pedimento\n";
        }

        if(parseInt(window.lotes_gv) && parseInt($('#tipo').val()) == 1)
        {
            numVal++
            if($("#numero_lote").val() != '')
                validaciones++;
            else
                mensaje += "Ingrese el lote\n";
        }

        if(parseInt(window.series_gv) && parseInt($('#tipo').val()) == 1)
        {
            numVal++
            if($("#serie-1").val() != '')
                validaciones++;
            else
                mensaje += "Ingrese la(s) serie(s)\n";
        }

        var referencia = $("#referencia").val();
        if(parseInt($('#tipo').val()) == 2)
        {
            referencia = $("#referencia").val()+" Destino:"+$("#almacen_destino_final").val();
            numVal++;
            if(parseInt($("#almacen_origen").val()) && parseInt($("#almacen_destino_final").val()))
            {   
                validaciones++;
            }
            else
                mensaje += "Agregue un almacen de origen y otro de destino.";
            

        }

        if(validaciones == numVal)
        {        
             $.post('ajax.php?c=inventarios&f=guardar_movimiento', 
                {
                    idprod          : $("#producto").val(),
                    cantidad        : $("#cantidad").val(),
                    importe         : $("#importe").val(),
                    almacen_origen  : $("#almacen_origen").val(),
                    almacen_destino : $("#almacen_destino").val(),
                    tipo            : $("#tipo").val(),
                    costo           : $("#costo").val(),
                    fecha           : $("#fecha_mov").val(),
                    referencia      : referencia,
                    caracteristicas : caracteristicas,
                    pedimentos      : pedimentos,
                    lotes           : lotes,
                    series          : series,
                    costeo          : $("#producto option:selected").attr('id_costeo'),
                    tras            : t
                }, 
                function(data)
                {
                   if(parseInt(data))
                   {
                        $('.bs-example-modal-md').modal('hide');
                        //nuevo_clas();
                        if(!t)
                        {
                            if(parseInt($("#producto option:selected").attr('id_costeo')) == 6 && series != '' && !parseInt($("#tipo").val()))
                            {
                                alert("Se generará un movimiento por cada serie.")
                            }
                            else
                            {
                                $("#printer").attr("href", "index.php?c=inventarios&f=printer&idMov="+data).attr("target","_blank");
                                jQuery("#printer")[0].click();
                                $("#printer").removeAttr('href').removeAttr('target')
                            }
                            
                            location.reload();
                        }
                        else
                        {
                            $.post('ajax.php?c=inventarios&f=tras_prods', 
                            {
                                tras    :   t
                            },
                            function(data2)
                            {
                                //alert(data2)
                                $("#mov_productos").html(data2);
                            });
                        }
                   }
                   else
                    alert("Hubo un error, verifique sus datos.\n Error: "+data)
                });
        }
        else
        {
            alert(mensaje)
        }
    }
}

function cancelar_movimiento()
{
    $('.bs-example-modal-md').modal('hide');
    nuevo_movimiento();
}

function cancelar_traspaso(idtras)
{
    var c = confirm("Esta seguro que quiere cancelar el traspaso?")
    if(c)
    {
         $.post('ajax.php?c=inventarios&f=cancelar_traspaso', 
            {
                idtras        : idtras
            },
            function(data)
            {
                console.log(data);
                if(parseInt(data))
                    location.reload();
                else
                    alert("Hubo un error y no se pudo cancelar.")
            });
         return 1
    }
    return 0
}

function cancelar_traslado()
{
    var cierra = cancelar_traspaso($("#id_traslado").val());
    if(cierra)
        $('.bs-traslado-modal-lg').modal('hide');
}

function inv(e)
{
    $("#blanco").show();
    $("#almacen_origen").val(0);
    if(parseInt($("#tipo").val()) != 2)
        $("#almacen_destino").val(0);

    if(parseInt($("#producto").val()))
    {
        $("#unidad").text($("#producto option:selected").attr('unidad'))
        $("#moneda").text($("#producto option:selected").attr('moneda'))
    }

    console.log($("#producto option:selected").attr('precio'));
    $("#costo").val($("#producto option:selected").attr('precio'));

    var caracteristicas = '';

    if($("#numCarac").length)
    {
        for(i=$("#numCarac").val();i>=1;i--)
        {
            caracteristicas += $("#carac-"+i).attr('idpadre')+"'=>'"+$("#carac-"+i).val()+"|";
        }
    }
    window.caracteristicasg = caracteristicas
    if(!parseInt($("#producto").val()))
    {
        $("#otrascarac").hide();
        $("#blanco").hide();
    }

    if(parseInt($("#producto").val()) != 0 && parseInt(e))
    {
         $.ajax({
                    async:false,
                    url:"ajax.php?c=inventarios&f=caracteristicasProd",
                    type: 'POST',
                    data:
                    {
                       idprod : $("#producto").val()
                    },
                    success: function(data)
                    {
                         //alert(data)
                        if(data != '0')
                        {
                            $("#caracteristicas").show();
                            $("#listaCaracteristicas").html(data);
                        }
                        else
                        {
                            $("#caracteristicas").hide();
                            $("#listaCaracteristicas").html('');   
                        }
                        $("#carac-1").trigger('change');
                    }
                });
           
             $.ajax({
                    async:false,
                    url:"ajax.php?c=inventarios&f=otrasCarac",
                    type: 'POST',
                    data:{
                       idprod : $("#producto").val()
                    },
                    success: function(data)
                    {
                        var armadoHTML = "";
                        $("#otrascarac").html("");
                        var datos = data.split('|');
                        
                        if(parseInt(datos[0]) || parseInt(datos[1]) || parseInt(datos[2]))
                            $("#otrascarac").show();
                        else
                            $("#otrascarac").hide();
                        //Si tiene pedimentos
                        if(parseInt(datos[2]))
                        {
                            window.pedimentos_gv = 1;
                            //si se trata de entrada
                            if(parseInt($("#tipo").val()) == 1)
                            {
                                //Agregar boton de captura de pedimento
                                armadoHTML = "<div class='col-xs-4 col-md-offset-1 col-md-4'></div><div class='col-xs-12 col-md-7'><button id='pedimentos' class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-pedimentos-modal-md' style='width:250px;'>Pedimentos</button></div>";
                                $("#otrascarac").append(armadoHTML);
                            }
                            else//si es una salida o traspaso
                            {
                                //hacer consulta que traiga la lista de pedimentos
                                $.ajax({
                                    async:false,
                                    url:"ajax.php?c=inventarios&f=pls",
                                    type: 'POST',
                                    data:
                                    {
                                       idprod: $("#producto").val(),
                                        pls:'2'
                                    },
                                    success: function(data)
                                    {
                                            armadoHTML = "<div class='col-xs-4 col-md-offset-1 col-md-4'><b>Pedimentos:</b></div><div class='col-xs-4 col-md-7'><select id='pedimentos' style='width:250px;' class='form-control' onchange='invBusca(),buscaSeries(),getLotesPedimentosCosto()'>";
                                            armadoHTML += data;
                                            armadoHTML += "</select></div>";
                                            $("#otrascarac").append(armadoHTML);
                                            $("#pedimentos").val($("#pedimentos option:first").val());
                                            $("#pedimentos").trigger("change");
                                    
                                    }
                                });
                            }
                        }
                        else
                        {
                            $("#pedimentos").remove()
                             window.pedimentos_gv = 0;
                        }

                        //Si tiene lotes
                        if(parseInt(datos[1]))
                        {
                            window.lotes_gv = 1;
                            //si se trata de entrada
                            if(parseInt($("#tipo").val()) == 1)
                            {
                                //Agregar boton de captura de lotes
                                armadoHTML = "<div class='col-xs-4 col-md-offset-1 col-md-4'></div><div class='col-xs-12 col-md-7'><button id='lotes' class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-lotes-modal-md' style='width:250px;'>Lotes</button></div>";
                                $("#otrascarac").append(armadoHTML);
                            }
                            else//si es una salida o traspaso
                            {
                                //alert($("#producto").val())
                                //hacer consulta que traiga la lista de lotes
                                 $.ajax({
                                    async:false,
                                    url:"ajax.php?c=inventarios&f=pls",
                                    type: 'POST',
                                    data:
                                    {
                                       idprod: $("#producto").val(),
                                        pls:'1'
                                    },
                                    success: function(data)
                                    {
                                        //alert(data)
                                                armadoHTML = "<div class='col-xs-4 col-md-offset-1 col-md-4'><b>Lotes:</b></div><div class='col-xs-4 col-md-7'><select id='lotes' style='width:250px;' class='form-control' onchange='invBusca();getLotesPedimentosCosto()'>";
                                                armadoHTML += data;
                                                armadoHTML += "</select></div>";
                                                $("#otrascarac").append(armadoHTML);
                                                $("#lotes").val($("#lotes option:first").val());
                                                $("#lotes").trigger("change");
                                    
                                    }
                                });
                            }
                        }
                        else 
                            {
                                $("#lotes").remove()
                                 window.lotes_gv = 0;
                            }

                         //Si tiene series
                        if(parseInt(datos[0]))
                        {
                            window.series_gv = 1;
                            //si se trata de entrada
                            if(parseInt($("#tipo").val()) == 1)
                            {
                                //Agregar boton de captura de series
                                armadoHTML = "<div class='col-xs-4 col-md-offset-1 col-md-4'></div><div class='col-xs-12 col-md-7'><button id='series' class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-series-modal-md' style='width:250px;' onclick='series()'>Series</button></div>";
                                $("#otrascarac").append(armadoHTML);
                                $("#blanco").hide();
                            }
                            else//si es una salida o traspaso
                            {
                                //hacer consulta que traiga la lista de traspasos en un multiselect
                                 $.ajax({
                                    async:false,
                                    url:"ajax.php?c=inventarios&f=pls",
                                    type: 'POST',
                                    data:
                                    {
                                       idprod: $("#producto").val(),
                                        pls:'0',
                                        idped:$("#pedimentos").val()
                                    },
                                    success: function(data)
                                    {
                                                armadoHTML = "<div class='col-xs-4 col-md-offset-1 col-md-4'><b>Series:</b></div><div class='col-xs-4 col-md-7'><select id='series' onchange='getSeriesCosto()' multiple style='width:250px;' class='form-control'>";
                                                armadoHTML += data;
                                                armadoHTML += "</select></div>";
                                                $("#otrascarac").append(armadoHTML);
                                                $("#series").select2(); 
                                                $("#series").val($("#series option:first").val());
                                                $("#series").trigger("change"); 
                                                $("#almacen_origen").attr("onchange","disponibilidad(),buscaSeries()")
                                    }
                                });
                            }
                        }
                        else
                        {
                            $("#series").remove()
                             window.series_gv = 0;
                             $("#almacen_origen").attr("onchange","disponibilidad()")
                        }
                   
                    }
                });
        $("#importe").val('0').attr('readonly',false)
        $("#cantidad").attr('readonly',false)
        $("#sercost").remove();
        if(parseInt($("#tipo").val()) != 1 && parseInt($("#producto").val()))
        {
            var id_costeo = $("#producto option:selected").attr('id_costeo');
            
            if(parseInt(id_costeo) == 1)
            {
                $.ajax({
                    async:false,
                    url:"ajax.php?c=inventarios&f=costeoProd",
                    type: 'POST',
                    data:
                    {
                       idprod : $("#producto").val()
                    },
                    success: function(data)
                    {
                        if(!data)
                            data = 0;

                        var costo = parseFloat(data);
                        var importe = parseFloat(costo) * parseFloat($("#cantidad").val());
                        $("#costo").val(costo);
                        $("#importe").val(importe);
                    }
                });
            }

            if(window.series_gv)
            {
                $("#cantidad").attr('readonly',true)
            }
            if(parseInt(id_costeo) == 6 && window.series_gv)
            {
                $("#costo").val('espec').attr('readonly',true)
                $("#importe").attr('readonly',true)    
                $("#costo").after("<span id='sercost'></span>");
            }

            if(parseInt(id_costeo) == 6 && window.lotes_gv)
            {
                $("#lotes").trigger('change');
            }

            if(parseInt(id_costeo) == 6 && window.pedimentos_gv)
            {
                $("#pedimentos").trigger('change');
            }
        }

    }
    $("#blanco").hide();
    invBusca()
}

function getSeriesCosto()
{
    if($("#costo").val() == "espec")
    {
        var series = '';
                if(parseInt(window.series_gv))
                {
                    $("#series :selected").each(function(i,selected)
                    {
                        series += selected.value+"@|@";
                    });

                    if(series != '')
                    {
                        $.ajax({
                            async:false,
                            url:"ajax.php?c=inventarios&f=costoS",
                            type: 'POST',
                            data:
                            {
                               series     : series
                            },
                            success: function(data)
                            {
                                data = data.split("*|||*");
                                $("#sercost").html(data[0])
                                $("#importe").val(data[1])
                                $("#cantidad").val(data[2])
                            }
                        });
                    }
                    else
                    {
                        $("#importe").val(0)
                        $("#sercost").html('')
                        $("#cantidad").val(0)
                    }
                }  
    }
    else
    {
        $("#cantidad").val($("#series :selected").length);
    }
      
}

function getLotesPedimentosCosto()
{
    var id_lote = $("#lotes").val();
    var id_pedimento = $("#pedimentos").val();
    var sig=0;
    if(parseInt($("#producto option:selected").attr('id_costeo')) == 6 && !parseInt(window.series_gv))
    {
        if(parseInt(id_lote) && parseInt(window.lotes_gv))
        {
                $.ajax({
                    async:false,
                    url:"ajax.php?c=inventarios&f=costoLP",
                    type: 'POST',
                    data:
                    {
                       id      : id_lote,
                       tipo    : 0
                    },
                    success: function(data)
                    {
                        if(!data)
                            data = 0;

                        var costo = parseFloat(data);
                        var importe = parseFloat(costo) * parseFloat($("#cantidad").val());
                        $("#costo").val(costo.toFixed(2));
                        $("#importe").val(importe.toFixed(2));
                        sig = 1;
                    }
                });
        }
        if(parseInt(id_pedimento) && parseInt(window.pedimentos_gv) && !sig)
        {
             $.ajax({
                    async:false,
                    url:"ajax.php?c=inventarios&f=costoLP",
                    type: 'POST',
                    data:
                    {
                       id      : id_pedimento,
                       tipo    : 1
                    },
                    success: function(data)
                    {
                        //alert(data)
                        if(!data)
                            data = 0;

                        var costo = parseFloat(data);
                        var importe = parseFloat(costo) * parseFloat($("#cantidad").val());
                        $("#costo").val(costo.toFixed(2));
                        $("#importe").val(importe.toFixed(2));
                    }
                });
        }
    }
    
}

function buscaSeries()
{
    var armadoHTML = "";
    $("#series").html('')
    $.ajax({
        async:false,
        url:"ajax.php?c=inventarios&f=pls",
        type: 'POST',
        data:
        {
            idprod: $("#producto").val(),
            pls:'0',
            idped:$("#pedimentos").val(),
            idalmacen: $("#almacen_origen").val()
        },
        success: function(data)
        {
            armadoHTML += data;        
            $("#series").html(armadoHTML);
            $("#series").trigger('change')                                         
        }
    });
}

function invBusca()
{
   console.log("Pedimentos: "+window.pedimentos_gv+"Lotes: "+window.lotes_gv+"Series: "+window.series_gv)
   var pedimentos = 0;
   if(parseInt(window.pedimentos_gv))
        pedimentos = $("#pedimentos").val()

    var lotes = 0;
       if(parseInt(window.lotes_gv))
            lotes = $("#lotes").val()

   var series = [];
   if(parseInt(window.series_gv))
   {
        $("#series :selected").each(function(i,selected){
            series[i] = selected.value;
        });
    }

    if(parseInt($("#producto").val()) != 0 && parseInt($("#tipo").val()) != 1)
    {

        //Proceso que llena el select
            $.post('ajax.php?c=inventarios&f=listaAlmacenesInv', 
            {
                idprod : $("#producto").val(),
                caracteristicas: window.caracteristicasg,
                pedimentos : pedimentos,
                lotes : lotes,
                series : series
            }, 
            function(data)
            {
                //alert(data)
                $("#almacen_origen").html("<option value='0'>Ninguno</option>")
                $("#almacen_origen").append(data)
                if(parseInt($("#id_traslado").val()))
                {
                    $("#almacen_origen").prop('disabled',true)
                    $("#almacen_origen").val($("#almacen_origen_final").val()).trigger('change')
                }
            });
    }
    else
    {
        //Proceso que elimina todo del select excepto la opcion ninguno
        $("#almacen_origen").html("<option value='0'>Ninguno</option>")
    }

    if(parseInt($("#producto").val()) != 0 && !parseInt($("#tipo").val()))
    {
        $("#almacen_destino").html("<option value='0'>Ninguno</option>")
    }
    else
    {
        $("#almacen_destino").html(window.destino)   
    }
}


function disponibilidad()
{
    if(parseInt($("#producto").val()) != 0)
    {
        if(parseFloat($("#cantidad").val()) > parseFloat($("#almacen_origen option:selected").attr("cantidad")) && !parseInt($("#sinexistencias").val()))
        {
            alert("La cantidad a extraer del almacen origen es superior al de la cantidad disponible en ese almacen.")
            $("#almacen_origen").val(0)
        }       
        else
        {
            if(parseInt($("#producto option:selected").attr('id_costeo')) == 1)
            {
                var importe = parseFloat($("#costo").val()) * parseFloat($("#cantidad").val());
                $("#importe").val(importe.toFixed(2));
            }
        }
    }        
}

function cancelar_pls(t)
{
    $(".bs-"+t+"-modal-md").modal('hide');

}

function series()
{
    $("#inputSeries").html("")
    if(parseFloat($("#cantidad").val()))
    {
        if(window.memoseries != '')
        {
            var ms = window.memoseries 
            ms = ms.split("Ω|Ω")
        }
        for(i=1;i<=$("#cantidad").val();i++)
        {
            $("#inputSeries").append("<div class='col-xs-4 col-md-5'>Serie "+i+"</div><div class='col-xs-4 col-md-6'><input type='text' id='serie-"+i+"' class='form-control'></div>");
            if(window.memoseries != '')
                $("#serie-"+i).val(ms[i-1])

        }
    }
    else
    {
        alert("Agregue una cantidad")
        cancelar_pls('series');
    }
}

function genera_series()
{
    var repetidos = 0;
    var a,b;
    for(g=1;g<=parseInt($("#cantidad").val());g++)
    {
        a = $("#serie-"+g).val()
        for(h=1;h<=parseInt($("#cantidad").val());h++)
        {
            b = $("#serie-"+h).val()
            if(a == b)
                repetidos++;
        }
    }
    
    if(repetidos == parseInt($("#cantidad").val()))
    {
        var cadenaSeries = '';
        for(i=1;i<=parseInt($("#cantidad").val());i++)
            cadenaSeries += $("#serie-"+i).val()+"Ω|Ω";

        window.memoseries = cadenaSeries;
        $('.bs-series-modal-md').modal('hide');
        //alert(window.memoseries)
    }
    else
        alert("Hay series repetidas.");
}

function genera_pedimentos()
{
    window.memopedimentos = $("#numero_pedimento").val()+"Ω|Ω"+$("#aduana").val()+"Ω|Ω"+$("#numero_aduana").val()+"Ω|Ω"+$("#cambio").val()+"Ω|Ω"+$("#fecha_pedimento").val()+"Ω|Ω"+$("#cantidad_pedimento").val();
    $('.bs-pedimentos-modal-md').modal('hide');

}
function genera_lotes()
{
    window.memolotes = $("#numero_lote").val()+"Ω|Ω"+$("#fecha_fabricacion").val()+"Ω|Ω"+$("#fecha_caducidad").val();
    $('.bs-lotes-modal-md').modal('hide');
}

function costo(t)
{
    if(t.id == 'importe')
        $("#costo").val((parseFloat($("#importe").val())/parseFloat($("#cantidad").val())).toFixed(2))
    else
        $("#importe").val((parseFloat($("#costo").val())*parseFloat($("#cantidad").val())).toFixed(2))
}

function cancelar_accion(id)
{
    if(confirm("Esta seguro que desea cancelar el movimiento? \n Si lo cancela ya no podrá restaurarlo y podria afectar negativamente a los reportes."))
    {
         $.post('ajax.php?c=inventarios&f=cancelar_accion', 
                {
                    idmov : id
                }, 
                function()
                {
                    $("#butt_"+id).hide().after("<span class='label label-danger'>Cancelado</span>");
                });
    }
}

//TERMINAN FUNCIONES DEL MODULO ENTRADAS

////CH
function procesar1(){
    var idProducto = $('#producto').val();
    var almacen = $('#almacen').val();
    var desde = $('#desde').val();
    var hasta = $('#hasta').val();
    var iddepartamento = $('#departamento').val();
    var idfamilia = $('#familia').val();
    var idlinea = $('#linea').val();
    
    var R1 = "";
      if ($('#R1unidades').prop('checked')) {
        R1 = $('#R1unidades').val();
      }
      if ($('#R1importe').prop('checked')) {
        R1 = $('#R1importe').val();
      }
      if ($('#R1ambos').prop('checked')) {
        R1 = $('#R1ambos').val();
      }

    window.open('index.php?c=inventarios&f=kardex3&idProducto='+idProducto+'&idalmacen='+almacen+'&desde='+desde+'&hasta='+hasta+'&R1='+R1+'&iddep='+iddepartamento+'&idfa='+idfamilia+'&idli='+idlinea+'',"Kardex","width=800,height=800,resizable=no,scrollbars=1");
}
function procesarExs(){
    var idProducto = $('#producto').val();
    var almacen = $('#almacen').val();
    var desde = $('#desde').val();
    var hasta = $('#hasta').val();
    var iddepartamento = $('#departamento').val();
    var idfamilia = $('#familia').val();
    var idlinea = $('#linea').val();
    var nomAl = $('#almacen option:selected').text();
    
    var R1 = "";
      if ($('#R1unidades').prop('checked')) {
        R1 = $('#R1unidades').val();
      }
      if ($('#R1importe').prop('checked')) {
        R1 = $('#R1importe').val();
      }
      if ($('#R1ambos').prop('checked')) {
        R1 = $('#R1ambos').val();
      }

    window.open('index.php?c=inventarios&f=existencias&idProducto='+idProducto+'&idalmacen='+almacen+'&desde='+desde+'&hasta='+hasta+'&R1='+R1+'&iddep='+iddepartamento+'&idfa='+idfamilia+'&idli='+idlinea+'&nomAl='+nomAl+'',"KardexExis","width=800,height=800,resizable=no,scrollbars=1");
}
function reloadtable(){

            var lotes = "";
              if ($('#Rlotes3').prop('checked')) {
                lotes = $('#Rlotes3').val();
              }
              if ($('#Rlotes1').prop('checked')) {
                lotes = $('#Rlotes1').val();
              }
              if ($('#Rlotes0').prop('checked')) {
                lotes = $('#Rlotes0').val();
              }

              var series = "";
              if ($('#Rseries3').prop('checked')) {
                series = $('#Rseries3').val();
              }
              if ($('#Rseries1').prop('checked')) {
                series = $('#Rseries1').val();
              }
              if ($('#Rseries0').prop('checked')) {
                series = $('#Rseries0').val();
              }

              var pedi = "";
              if ($('#Rpedi3').prop('checked')) {
                pedi = $('#Rpedi3').val();
              }
              if ($('#Rpedi1').prop('checked')) {
                pedi = $('#Rpedi1').val();
              }
              if ($('#Rpedi0').prop('checked')) {
                pedi = $('#Rpedi0').val();
              }

              var caract = "";
              if ($('#Rcarac3').prop('checked')) {
                caract = $('#Rcarac3').val();
              }
              if ($('#Rcarac1').prop('checked')) {
                caract = $('#Rcarac1').val();
              }
              if ($('#Rcarac0').prop('checked')) {
                caract = $('#Rcarac0').val();
              }

              var producto  = $('#producto').val();
              var unidad    = $('#unidad').val();
              var moneda    = $('#moneda').val();

            $.ajax({
                url: 'ajax.php?c=inventarios&f=listProductos',
                type: 'post',
                dataType: 'json',
                data:{producto:producto,unidad:unidad,moneda:moneda,lotes:lotes,series:series,pedi:pedi,caract:caract},
            })
            .done(function(data) {
                

                  var table =  $('#table_listado').DataTable( {
                            destroy: true,
                            searching: true,
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
                             }
                        });

                     var table2   = $('#table_listado2').DataTable( {
                            destroy: true,
                            searching: true,
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
                             }
                        });
                            
                    table.clear().draw();
                    table2.clear().draw();
                    var x ='';
                    var x2 ='';

                            $.each(data, function(index, val) {

                                var codigo          = val.codigo;
                                var producto        = val.producto;
                                var unidad          = val.unidad;
                                var lotes           = val.lotes;
                                var caracteristicas = val.caracteristicas;
                                var series          = val.series;
                                var pedimentos      = val.pedimentos;
                                var moneda          = val.moneda;

                                if(unidad == null){
                                    unidad='N/A';
                                }
                                if(moneda == null){
                                    moneda='N/A';
                                }

                                if(val.tipo_producto == 1){
                                    x ='<tr>'+
                                                    '<td>'+codigo+'</td>'+
                                                    '<td>'+producto+'</td>'+
                                                    '<td>'+unidad+'</td>'+
                                                    '<td>'+caracteristicas+'</td>'+
                                                    '<td>'+lotes+'</td>'+
                                                    '<td>'+series+'</td>'+
                                                    '<td>'+pedimentos+'</td>'+
                                                    '<td>'+moneda+'</td>'+
                                        '</tr>';  
                                        table.row.add($(x)).draw();  
                                }
                                if(val.tipo_producto == 2){
                                    x2 ='<tr>'+
                                                    '<td>'+codigo+'</td>'+
                                                    '<td>'+producto+'</td>'+
                                                    '<td>'+unidad+'</td>'+
                                                    '<td>'+caracteristicas+'</td>'+
                                                    '<td>'+lotes+'</td>'+
                                                    '<td>'+series+'</td>'+
                                                    '<td>'+pedimentos+'</td>'+
                                                    '<td>'+moneda+'</td>'+
                                        '</tr>';  
                                        table2.row.add($(x2)).draw();  
                                }                          
                            });
            })                    
}
function reloadselect(){
    // Select Productos
    $.ajax({
            url: 'ajax.php?c=inventarios&f=selectProductos',
            type: 'post',
            dataType: 'json',
    })
    .done(function(data) {
        $.each(data, function(index, val) {
              $('#producto').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
        });
    }) 

    // Select Unidades
    $.ajax({
            url: 'ajax.php?c=inventarios&f=selectUnidades',
            type: 'post',
            dataType: 'json',
    })
    .done(function(data) {
        $.each(data, function(index, val) {
              $('#unidad').append('<option value="'+val.id+'">'+val.clave+' - '+val.nombre+'</option>');  
        });
    })

    // Select Monedas
    $.ajax({
            url: 'ajax.php?c=inventarios&f=selectMonedas',
            type: 'post',
            dataType: 'json',
    })
    .done(function(data) {
        $.each(data, function(index, val) {
              $('#moneda').append('<option value="'+val.coin_id+'">'+val.codigo+' - '+val.description+'</option>');  
        });
    })  
}

function NumCheck(e, field) {
  key = e.keyCode ? e.keyCode : e.which
  // backspace
  if (key == 8) return true
  // 0-9
  if (key > 47 && key < 58) {
    if (field.value == "") return true
    regexp = /.[0-9]{10}$/
    return !(regexp.test(field.value))
  }
  // .
  if (key == 46) {
    if (field.value == "") return false
    regexp = /^[0-9]+$/
    return regexp.test(field.value)
  }
  // other key
  return false
 
}

function valcoma(el)
{
    var a;
    a = $(el).val().replace(/,/g, '')
    $(el).val(a)
}

////TRASPASOS
