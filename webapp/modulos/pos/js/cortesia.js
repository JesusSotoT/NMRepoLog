
$(document).ready(function() {

    $('#desde').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
    
    $('#hasta').datepicker({
        format: "yyyy-mm-dd",
        language: "es"
    });

    function clonar(obj) {
        if (null == obj || "object" != typeof obj) return obj;
        var copy = obj.constructor();
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
        }
        return copy;
    }

    $('#btn-nuevaC').on('click', function(){
        limpiarFormulario();
        $('#modalEditar').modal({
            show:true,
        });
    }); 

    $("#producto").select2({
        placeholder: "Selecciona los productos",
        minimumInputLength: 1,
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=cortesia&f=buscarProducto',
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


   $('#btn-agregar').on('click', function() {
        var repetido = false;
        if((($('#select2-producto-container').text()).split("|"))[1] != "Selecciona los productos"){
            $('#tabla-productos tbody tr').each( function() { 
                if ($(this).children('td:nth-child(1)').text() == (($('#select2-producto-container').text()).split("|"))[0]) {
                    repetido = true;
                }   
            });
            if(!repetido) {
                $('#tabla-productos tbody').append(`
                <tr>
                <td>` + (($('#select2-producto-container').text()).split("|"))[0] + `</td>
                <td>` + (($('#select2-producto-container').text()).split("|"))[1] + `</td>
                <td> <button type="button" class="btn-warning"> Eliminar </button> </td>
                </tr>
                `);
                $('#tabla-productos button').off('click',  "**" );
                $('#tabla-productos button').on('click', function() {
                    $(this).parent().parent().remove();
                });
                $('#producto').select2('val', '');
            }
            else {
                alert("Producto repetido.");
            }
        }
        else {
            alert("Elige un prducto valido");
        }
        
    });


    function obtenerDatos(){
        var datos = { };
        datos.id = $('#idd').val();
        datos.nombre = $('#nombre').val();
        datos.desde = validaFecha( $('#desde').val() ) ? $('#desde').val() : "";
        datos.hasta = validaFecha( $('#hasta').val() ) ? $('#hasta').val() : "";
        datos.hasta = validarFechas( $('#desde').val() , $('#hasta').val() ) ? $('#hasta').val() : "";
        datos.productos = [];
        $('#tabla-productos tbody tr').each( function() { 
            let temp = {  };
            temp.id = ($(this).children('td:nth-child(1)').text());
            temp.nombre = ($(this).children('td:nth-child(2)').text());
            datos.productos.push( clonar(temp) );
        });
        return datos;
    }
    function validaFecha(el) {
        var valid = /^(\d{2}\/\d{2}\/\d{4})|(\d{4}-\d{2}-\d{2})$/.test(el);
        if (!valid) {
            alert("Porfavor introduce un fechas validas");
        }
        return valid;
    }
    function validarFechas(f1, f2) {
        var f1 = new Date(f1); 
        var f2 = new Date(f2);       
        if(f1 > f2){
            alert("Revisa que el rango de fechas sea correcto")
            return false;
        }
        return true;
    }
    function limpiarFormulario(){
        $('#idd').val("");
        $('#nombre').val("");
        $('#desde').val("");
        $('#hasta').val("");
        $('#tabla-productos tbody tr').each( function() { 
            $(this).remove();
        });
    }
    $('#save').on('click', function(){
        var datos = obtenerDatos();
        var continuar = true;
        $.each( datos, function( key, value ) {
            if((value === "" || value === undefined) && key != "id")
                continuar = false;
        });

        if(continuar) {
            var content = $('#modalEditar .modal-footer');
            $(content).empty();
            $.ajax({
                type: "POST",                                            
                url: "ajax.php?c=cortesia&f=agregar",
                data: datos,                                          
                timeout: 2000,                                          
                beforeSend: function() {                                 
                    content.append('<div id="load">Loading</div>');      
                },
                complete: function() {                                  
                    $('#load').remove();                                  
                },
                success: function(data) {
                    //if(data.status == true){
                        content.html( '<div class="succes"> Actualizado exitosamente. </div>' ).hide().fadeIn(400);
                    /*}
                    else {
                        content.html( '<div class="succes"> Ocurrio error en la base de datos. </div>' ).hide().fadeIn(400);
                    }   */                         

                    limpiarFormulario();
                    refrescarTablaCortesias();
                    $('#modalEditar').modal('hide');
                },
                error: function() {                                     
                    content.html('<div class="error"> Ocurrio un error, porfavor intenta mas tarde.</div>');
                }
            });
        }
        else {
            alert("Verifica que todos los campos esten correctamente");
        }

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
            url: "ajax.php?c=cortesia&f=obtener",  
            dataType : 'json',                                       
            timeout: 1500,                                          
            beforeSend: function(data) {                                 
            },
            complete: function(data) {                  
            },
            success: function(data) { 
                table.clear().draw(); 
                $.each( data, function(index, value) {

                    status = (value.estatus==1) ? `
                    <span class="label label-success">Activa</span>
                    ` : `
                    <span class="label label-danger">Inactiva</span>
                    `;
                    btnModific = (value.estatus==1) ? `
                    <a class="btn btn-primary btn-xs active modificar" >
                    <span class="glyphicon glyphicon-edit"></span>
                    Editar
                    </a>
                    <a class="btn btn-danger btn-xs active desactivar" href="#" >
                    <span class="glyphicon glyphicon-remove"></span>
                    Desactivar
                    </a>
                    ` : `
                    <a class="btn btn-primary btn-xs active modificar" >
                    <span class="glyphicon glyphicon-edit"></span>
                    Editar
                    </a>
                    <a class="btn btn-success btn-xs active activar" >
                    <span class="glyphicon glyphicon-ok"></span>
                    Activar
                    </a>
                    `;

                    fila = `
                    <tr>
                    <td class="idcort">`+ value.id +`</td>
                    <td>`+ value.nombre +`</td>
                    <td>`+ status +`</td>
                    <td>`+ btnModific +`</td>
                    </tr>
                    `; 
                    table.row.add($(fila)).draw(); 
                }); 

                $('.activar').on('click', function() {
                    var idCortesia = (($(this).parent().parent())).find(".idcort").text();
                    $.ajax({
                        type: "GET",                                            
                        url: "ajax.php?c=cortesia&f=activar",  
                        dataType : 'json', 
                        data: { "id" : idCortesia },                                      
                        timeout: 1500,                                          
                        beforeSend: function(data) {                                 
                        },
                        complete: function(data) {                  
                        },
                        success: function(data) { 
                            refrescarTablaCortesias();
                        },
                        error: function() {                                     
                            alert("Error al activar cortesía")
                        }
                    }); 
                });
                $('.desactivar').on('click', function() {
                    var idCortesia = (($(this).parent().parent())).find(".idcort").text();
                    $.ajax({
                        type: "GET",                                            
                        url: "ajax.php?c=cortesia&f=desactivar",  
                        dataType : 'json', 
                        data: { "id" : idCortesia },                                      
                        timeout: 1500,                                          
                        beforeSend: function(data) {                                 
                        },
                        complete: function(data) {                  
                        },
                        success: function(data) { 
                            refrescarTablaCortesias();
                        },
                        error: function() {                                     
                            alert("Error al desactivar cortesía")
                        }
                    }); 
                });

                $('.modificar').on('click', function(){
                    var idCortesia = (($(this).parent().parent())).find(".idcort").text();
                    $.ajax({
                        type: "GET",                                            
                        url: "ajax.php?c=cortesia&f=obtenerUno",  
                        dataType : 'json', 
                        data: { "id" : idCortesia },                                      
                        timeout: 1500,                                          
                        beforeSend: function(data) {                                 
                        },
                        complete: function(data) {                  
                        },
                        success: function(data) { 
                            $('#idd').val(data.id);
                            $('#nombre').val(data.nombre);
                            $('#desde').val(data.fecha_inicio);
                            $('#hasta').val(data.fecha_fin);
                            $('#tabla-productos tbody tr').each( function() { 
                                $(this).remove();
                            });
                            $(data.productos).each(function(index, el) {
                                $('#tabla-productos tbody').append(`
                                    <tr>
                                    <td>` + el.id + `</td>
                                    <td>` + el.nombre + `</td>
                                    <td> <button type="button" class="btn-warning"> Eliminar </button> </td>
                                    </tr>
                                    `
                                    );
                            });
                            $('#tabla-productos button').off('click',  "**" );
                            $('#tabla-productos button').on('click', function() {
                                $(this).parent().parent().remove();
                            });

                        },
                        error: function() {                                     
                            alert("Error al cargar tabla de cortesías")
                        }
                    }); 

                    $('#modalEditar').modal({
                        show:true,
                    });
                }); 
            },
            error: function() {                                     
                alert("Error al modifiar cortesía");
            }
        }); 
    }
    refrescarTablaCortesias();

});

