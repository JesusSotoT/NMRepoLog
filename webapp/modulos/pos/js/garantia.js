$(document).ready(function() {

    function clonar(obj) {
        if (null == obj || "object" != typeof obj) return obj;
        var copy = obj.constructor();
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
        }
        return copy;
    }

    $('#btn-nueva-garantia').on('click', function(event) {
        var pathname = window.location.pathname;
        var host = document.location.host;
        window.location = window.location.protocol + '//'+ host + pathname +'?c=garantia&f=nueva';
    });

    var table = $('#tableGrid').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel'],
        language: {
            search: "Buscar:",
            lengthMenu:"",
            zeroRecords: "No hay datos.",
            infoEmpty: "No hay datos que mostrar.",
            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Último"
            },
        },
        aaSorting : [[0,'desc' ]],
        allowClear: true
    });

    function refrescarTablaCortesias() { 
        $.ajax({
            type: "GET",                                            
            url: "ajax.php?c=garantia&f=obtener",  
            dataType : 'json',                                       
            timeout: 1500,                                          
            beforeSend: function(data) {                                 
            },
            complete: function(data) {                  
            },
            success: function(data) { 
                table.clear().draw(); 
                $.each( data, function(index, value) {
                    var btnModific = `<a class="btn btn-primary btn-xs active modificar" >
                                        <span class="glyphicon glyphicon-edit"></span> Editar
                                    </a>`;
                    fila = `
                    <tr>
                    <td class="idcort">`+ value.id +`</td>
                    <td>`+ value.nombre +`</td>
                    <td>`+ value.tipo +`</td>
                    <td>` + value.duracion + `</td>
                    <td>`+ btnModific +`</td>
                    </tr>
                    `; 
                    table.row.add($(fila)).draw(); 
                }); 

                $('.modificar').on('click', function(event) {
                    var pathname = window.location.pathname;
                    var host = document.location.host;

                    var idGarantia = (($(this).parent().parent())).find(".idcort").text();
                    window.location = window.location.protocol + '//'+ host + pathname +'?c=garantia&f=nueva&idGarantia=' + idGarantia;
                });

            },
            error: function() {                                     
                alert("Error al cargar tabla de garantías");
            }
        }); 
    }
    refrescarTablaCortesias();

/*
***************************************************************************************************
*/

    function regresar(){
        var pathname = window.location.pathname;
        var host = document.location.host;
        window.location = window.location.protocol + '//'+ host + pathname +'?c=garantia&f=index';
    }
    $('#btnRegresar').on('click', function(event) {
        regresar();
    });

    function obtenerDatos(){
        var datos = { };
        datos.id = $('#idd').val();
        datos.nombre = $('#nombreGarantia').val();
        datos.tipoGarantia = ( $('#tipoGarantia').val() == "1" || $('#tipoGarantia').val() == "2" ) ? $('#tipoGarantia').val() : "";
        datos.duracionGarantia =  $('#duracionGarantia').val()  ? $('#duracionGarantia').val() : "";
        datos.politica = $('#idPolitica').val();
        datos.derechoGarantia = $('#derechoGarantia').val();
        datos.tabla = [ ];
        if (datos.tipoGarantia == "1") {
            $('#tablaProductosClasificados tbody tr').each( function() { 
                let temp = {  };
                let clasificador;
                switch(($(this).children('td:nth-child(1)').text())){
                    case "Departamento": clasificador = "1"; break;
                    case "Familia": clasificador = "2"; break;
                    case "Linea": clasificador = "3"; break;
                    default:
                }
                temp.idTipClas = clasificador;
                temp.idClas = ($(this).children('td:nth-child(2)').text());
                datos.tabla.push( clonar(temp) );
            });
        }
        else {
            $('#tablaProductos tbody tr').each( function() { 
                let temp = {  };
                temp.idProducto = ($(this).children('td:nth-child(1)').text());
                datos.tabla.push( clonar(temp) );
            });
        }
        console.log( datos );
        return datos;
    }

    $('#btnGuargar').on('click', function(event) {
        var datos = obtenerDatos();
        var continuar = true;
        $.each( datos, function( key, value ) {
            if((value === "" || value === undefined) && key != "id")
                continuar = false;
        });
        if(datos.tabla.length == 0) {
            alert("No se puede tener ningun objeto en configuración");
            continuar = false;
        }

        if(continuar) {
            $.ajax({
                type: "POST",                                            
                url: "ajax.php?c=garantia&f=agregar",
                data: datos,                                          
                timeout: 2000,   
                dataType: 'json',                                       
                beforeSend: function() {                                 
     
                },
                complete: function() {                                  
                                 
                },
                success: function(data) {
                    if(data.status == true){
                        alert("Garantía registrada exitosamente");
                        regresar();
                    }
                    else
                        alert("Hubó un error al registrar garantía");
                },
                error: function() {  
                alert("Error al registrar garantía");                                   
                }
            });
        }
        else {
            alert("Verifica que todos los campos esten correctamente");
        }

    });

    /*
    Básicos
    */

    $('#tipoGarantia').on('change', function(event) {
        if( $(this).val() == "1") {
            $('.garantia2').hide();
            $('.garantia1').show();
        }
        else {
            $('.garantia1').hide();
            $('.garantia2').show();
        }
    });

    /*
    Políticas
    */

    $('#buscadorPoliticas').select2({
        placeholder: "Selecciona política",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=garantia&f=buscarPoliticas',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { 
                    patron: params.term };
            },

            processResults: function (data) {
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return state.id + "|" + state.text;
        },
        templateSelection: function format(state) {
            return state.id + "|" + state.text;
        }
    })
    .on('change', function() {
        var id = (($('#select2-buscadorPoliticas-container').text()).split("|"))[0];
        $.ajax({
            type: "GET",                                            
            url: "ajax.php?c=garantia&f=descripcionPolitica",
            data: { "id" : id },                                          
            timeout: 2000, 
            dataType: 'json',                                         
            beforeSend: function() {                                      
            },
            complete: function() {                                                                 
            },
            success: function(data) {
                $('#idPolitica').val(data.id);
                $('#terminosGarantia').val(data.descripcion);
            },
            error: function() {  
                alert("Error al cargar política");                                   
            }
        });
        
    });

    $('#btnGuargarPolitica').on('click',function() {
        var nombre = $('#nombreNuevaPolitica').val();
        var descripcion = $('#terminosGarantia').val();

        if(nombre != "" && descripcion != ""){
             $.ajax({
                type: "POST",                                            
                url: "ajax.php?c=garantia&f=agregarPolitica",
                data: { "nombre" : nombre ,
                        "descripcion" : descripcion
                     },                                          
                timeout: 2000, 
                dataType: 'json',                                         
                beforeSend: function() {                                      
                },
                complete: function() {                                                                 
                },
                success: function(data) {
                    $('#idPolitica').val(idPolitica);
                    alert("Tu política se ha guardado con éxito, ahora la puedes utilizar");
                },
                error: function() {  
                    alert("Error al guardar política");                                   
                }
            });
        }
        else {
            alert("No dejes campos vacios");
        }

       

    });
    /*
    Configuración
    */
    $('#clasificador').on('change', function(event) {
        switch( $(this).val() ){
            case "1":
                $('#lblBuscadorClasificado').text('Departameto');
                break;
            case "2":
                $('#lblBuscadorClasificado').text('Familia');
                break;
            case "3":
                $('#lblBuscadorClasificado').text('Linea');
                break;
            default:
                $('#lblBuscadorClasificado').text('Error al seleccionar clasificador . . .');
        }
    });

    $("#buscadorClasificado").select2({
        placeholder: "Selecciona clasificador",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=garantia&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : $('#clasificador').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                $('#buscadorClasificado').empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return state.id + "|" + state.text;
        },
        templateSelection: function format(state) {
            return state.id + "|" + state.text;
        }
    });

    $('#btnAgregarClasificado').on('click', function() {
        var repetido = false;
        if((($('#select2-buscadorClasificado-container').text()).split("|"))[1] != "Selecciona clasificador"){
            $('#tablaProductosClasificados tbody tr').each( function() {
                    let clasificador;
                    switch(($(this).children('td:nth-child(1)').text())){
                        case "Departamento": clasificador = 1; break;
                        case "Familia": clasificador = 2; break;
                        case "Linea": clasificador = 3; break;
                        default:
                    }
                    if ( clasificador == $('#clasificador').val() && 
                        $(this).children('td:nth-child(2)').text() == (($('#select2-buscadorClasificado-container').text()).split("|"))[0]) {
                        repetido = true;
                }  
            });

            if(!repetido) {
                $.ajax({
                    type: "GET",                                            
                    url: "ajax.php?c=garantia&f=existeClasificador",
                    data: { "idTipoClasificador" : $('#clasificador').val() ,
                            "idClasificador" : (($('#select2-buscadorClasificado-container').text()).split("|"))[0]
                     },                                          
                    timeout: 2000, 
                    dataType: 'json',                                         
                    beforeSend: function() {
                                                              
                    },
                    complete: function() {                                                                 
                    },
                    success: function(data) {

                        if ( data.total != "0" ) repetido = true;

                        if(!repetido) {
                            let clasificado;
                            switch( $('#clasificador').val() ) {
                                case "1": clasificado = "Departamento"; break;
                                case "2": clasificado = "Familia"; break;
                                case "3": clasificado = "Linea"; break;
                                default:
                            }

                            $('#tablaProductosClasificados tbody').append(`
                            <tr>
                            <td>` + clasificado + `</td>
                            <td>` + (($('#select2-buscadorClasificado-container').text()).split("|"))[0] + `</td>
                            <td>` + (($('#select2-buscadorClasificado-container').text()).split("|"))[1] + `</td>
                            <td> <button type="button" class="btn-warning"> Eliminar </button> </td>
                            </tr>
                            `);
                            $('#tablaProductosClasificados button').off('click',  "**" );
                            $('#tablaProductosClasificados button').on('click', function() {
                                $(this).parent().parent().remove();
                            });
                        }
                        else {
                            alert("El clasificador ya esta asignado en garantía con el ID:" + data.rows[0].id_garantia);
                        }
                        $('#buscadorClasificado').select2('val', '');
                    },
                    error: function() {  
                        alert("Error en el servidor, porfavor vuelve a intentar");                                   
                    }
                });
            }
            else {
                alert("Clasificador repetido");
            }
        }
        else {
            alert("Elige un clasificador válido");
        }
        
    });

    $("#buscadorProducto").select2({
        placeholder: "Selecciona los productos",
        minimumInputLength: 1,
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=garantia&f=buscarProducto',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { patron: params.term };
            },

            processResults: function (data) {
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return state.id + "|" + state.text;
        },
        templateSelection: function format(state) {
            return state.id + "|" + state.text;
        }
    });

    $('#btnAgregarProducto').on('click', function() {
        var repetido = false;
        if((($('#select2-buscadorProducto-container').text()).split("|"))[1] != "Selecciona los productos"){
            $('#tablaProductos tbody tr').each( function() { 
                if ($(this).children('td:nth-child(1)').text() == (($('#select2-buscadorProducto-container').text()).split("|"))[0]) {
                    repetido = true;
                }
            });

            if(!repetido) {
                $.ajax({
                    type: "GET",                                            
                    url: "ajax.php?c=garantia&f=existeProducto",
                    data: { "idProducto" : (($('#select2-buscadorProducto-container').text()).split("|"))[0] },                                          
                    timeout: 2000, 
                    dataType: 'json',                                         
                    beforeSend: function() {
                                                              
                    },
                    complete: function() {                                                                 
                    },
                    success: function(data) {
                        var repetido = false;
                        if ( data.total != "0" ) repetido = true;

                        if( !repetido ) {
                            $('#tablaProductos tbody').append(`
                            <tr>
                            <td>` + (($('#select2-buscadorProducto-container').text()).split("|"))[0] + `</td>
                            <td>` + (($('#select2-buscadorProducto-container').text()).split("|"))[1] + `</td>
                            <td> <button type="button" class="btn-warning"> Eliminar </button> </td>
                            </tr>
                            `);
                            $('#tablaProductos button').off('click',  "**" );
                            $('#tablaProductos button').on('click', function() {
                                $(this).parent().parent().remove();
                            });
                            $('#buscadorProducto').select2('val', '');
                        }
                        else {
                            alert("El producto ya esta asignado a la garantía con el ID: " + data.rows[0].id_garantia);
                        }
                    },
                    error: function() {  
                        alert("Error en el servidor, porfavor vuelve a intentar");                                   
                    }
                });
            }
            else {
                alert("Clasificador repetido");
            }
        }
        else {
            alert("Elige un producto válido");
        }
        
    });

});

