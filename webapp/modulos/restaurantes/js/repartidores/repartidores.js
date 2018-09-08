function autoload() {
    listarpedidosRep();
   setTimeout('autoload()',13000);
}
function autoload2() {
    reloadRep();
   setTimeout('autoload2()',13000);
}
function listarpedidosRep(){
        $.ajax({
                url: 'ajax.php?c=repartidores&f=listpedidosRep',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            console.log("table");
            console.log(data);
            var table = $('#table_pedidos').DataTable({                                                            
                            order: [[ 0, "desc" ]],
                            destroy: true,
                            language: { 
                                buttons: {
                                    print: 'Imprimir'
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
                        });                                 
            table.clear().draw();
            var x ='';
            var boton ='';
            var fecha ='';
             $.each(data, function(index, val) {

                var sta = val.estatus;
                if(sta == 1){ /// estatus asignado
                    boton = '<button type="button" class="btn btn-success btn-lg" onclick=entregado('+val.id_comanda+');>Entregado</button>  <button type="button" class="btn btn-danger btn-lg" onclick=noentregado('+val.id_comanda+');>No Entregado</button>';
                }
                if(sta == 2){ // entregado
                    boton = '<button type="button" class="btn btn-success btn-lg disabled">Entregado</button>';
                }
                if(sta == 4){ // no entregado
                    boton = '<button type="button" class="btn btn-danger btn-lg disabled">No Entregado</button>';
                }
                if(val.fecha_pedido_entregado != null){ 
                    fecha = val.fecha_pedido_entregado;
                }else{ fecha = ''; } 


                x ='<tr turno="'+val.turno+'"'

                if ($('#turnos').val() != '') {
                    if(val.turno != $('#turnos').val()){
                        x = x + ' style="display:none;"';
                    }
                } 

                x = x + '>'+
                            '<td align="center">'+val.id_comanda+'</td>'+
                            '<td align="center">'+val.nombre+'</td>'+
                            '<td align="center">'+fecha +'</td>'+
                            '<td align="center" width="30%">'+boton+'</td>'+
                        '</tr>';  
                    table.row.add($(x)).draw(); 

             });
        }) 
}
function entregado(id_comanda){
    $.ajax({
                url: 'ajax.php?c=repartidores&f=entregado',
                type: 'post',
                dataType: 'json',
                data:{id_comanda:id_comanda},
        })
        .done(function(data) {
            listarpedidosRep();
        }) 
}

function noentregado(id_comanda){
        $.ajax({
                url: 'ajax.php?c=repartidores&f=noentregado',
                type: 'post',
                dataType: 'json',
                data:{id_comanda:id_comanda},
        })
        .done(function(data) {
            listarpedidosRep();
        }) 
}

function reloadRep(){
    var idRep = $('#repartidor').val();
	$.ajax({
                url: 'ajax.php?c=repartidores&f=listpedidosRep',
                type: 'post',
                dataType: 'json',
                data:{idRep:idRep},
        })
        .done(function(data) {
        	var table = $('#table_ambos').DataTable({dom: 'Bfrtip',
                                                            buttons: [  
                                                                        {
                                                                            extend: 'print',
                                                                            title: $('h2').text(),
                                                                            customize: function ( win ) {
                                                                                $(win.document.body)
                                                                                    .prepend(
                                                                                        '<h3>Repartidores</h3><br>'
                                                                                    );                                                     
                                                                            }
                                                                        },
                                                                        'excel',
                                                                    ],
                                                            destroy: true,
                                                            language: { 
                                                                buttons: {
                                                                    print: 'Imprimir'
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
                                    });
                                    

            table.clear().draw();
            var x ='';
            var fechaA ='';
            var fechaE ='';
            var fechaP ='';
            var status ='';
            var class2 ='';
            var total = 0;
             $.each(data, function(index, val) {

             	if(val.fecha_pedido_asignado != null){ 
             		fechaA = val.fecha_pedido_asignado;
             	}else{ fechaA = ''; } 
             	
             	if(val.fecha_pedido_entregado != null){ 
             		fechaE = val.fecha_pedido_entregado;
             	}else{ fechaE = ''; } 
             	
             	if(val.fecha_pedido_pagado != null){ 
             		fechaP = val.fecha_pedido_pagado;
             	}else{ fechaP = ''; } 
             	

             	if(val.estatus == 1){ status = 'Asignado';    class2 = 'warning';}
             	if(val.estatus == 2){ status = 'Entregado';   class2 = 'info';}
             	if(val.estatus == 3){ status = 'Pagado';      class2 = 'success';}
             	if(val.estatus == 4){ status = 'No Entregado'; class2 = 'danger';}
                if(val.total == null) {val.total = '0.00';} 
                total = total + parseFloat(val.total);
             	x ='<tr id="tr'+val.id_comanda+'" class="'+class2+'">'+
                            '<td align="center">'+val.id_comanda+'</td>'+
                            '<td align="center">'+val.nombre+'</td>'+
                            '<td align="center">'+fechaA +'</td>'+
                            '<td align="center">'+fechaE +'</td>'+
                            '<td align="center">'+fechaP +'</td>'+
                            '<td align="center">'+val.total +'</td>'+
                            '<td align="center">'+status+'</td>'+
                        '</tr>';  
                    table.row.add($(x)).draw(); 
             });
            $("#total_rep").html("Total: $"+total.toFixed(2));
        }) 
}

var pedidos = Array();
var empleado = 0;
var comandas = {
///////////////// ******** ----         listar_mesas        ------ ************ //////////////////
    //////// Consulta las mesas y lo agrega a la div
        // Como parametros recibe:
            // empleado -> ID del empleado
            // asignar -> varoable para quitar las mesas de servicio a domicilio y para llevar
            // div -> div donde se cargara el contenido html

    listar_mesas : function($objeto) { 
        $.ajax({
            data : $objeto,
            url : 'ajax.php?c=repartidores&f=listar_mesas',
            type : 'GET',
            dataType : 'html',
            success : function(resp) {
                $('#' + $objeto['div']).html(resp);

            // Error: Manda un mensaje con el error
                if (!resp) {
                    var $mensaje = 'No existen pedidos.';
                    $('#' + $objeto['div']).notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'warning',
                    });
                }
            }
        });
    },

///////////////// ******** ----         FIN listar_mesas        ------ ************ //////////////////

///////////////// ******** ----                     modal_login             ------ ************ //////////////////
//////// Abre la modal de login, llena los campos y hace un focus
    // Como parametros puede recibir:
        // id-> ID del usuario
        // nombre-> nombre del usuario

    modal_login : function($objeto) {

        console.log('--------> objeto modal_login');
        console.log($objeto);
        $('#pass_empleado').focus();

    // llena los campos    
        setTimeout(function() {
            $('#empleado').val($objeto['empleado']);
            $('#id_empleado').val($objeto['id']);
            $('#pass_empleado').focus();
            comandas.listar_mesas({div:'contenedor', asignar:1});
        }, 500);
        $('#pass_empleado').focus();
    },
    
///////////////// ******** ----                 FIN modal_login             ------ ************ //////////////////

///////////////// ******** ----                     save_turn             ------ ************ //////////////////
//////// Abre la modal de login, llena los campos y hace un focus
    // Como parametros puede recibir:
        // id-> ID del usuario
        // nombre-> nombre del usuario

    save_turn : function($objeto) {
        console.log('--------> objeto save_turn');
        console.log($objeto);
        // Valida si viene turno
        if ($("#turn-"+$objeto['id']).val() == '') {
            var $mensaje = 'Introduce el turno';
            $.notify($mensaje, {
                position : "top center",
                autoHide : true,
                autoHideDelay : 5000,
                className : 'warn',
            });

            return 0;
        }
        $objeto['turno'] = $("#turn-"+$objeto['id']).val();
        $.ajax({
            data : $objeto,
            url : 'ajax.php?c=repartidores&f=save_turn',
            type : 'GET',
            dataType : 'json',
            success : function(resp) {
                console.log('--------> RESPONSE save_turn');
                console.log(resp);

            // Error :(
                if (resp['status'] == 0) {
                    //alert('pass');
                    //return false;
                    var $mensaje = 'No se pudo guardar el turno.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'warn',
                    });

                    return 0;
                }
                else {
                    var $mensaje = 'Turno guardado con exito';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'success',
                    });
                    $("#repar-"+$objeto['id']).attr('turno', $objeto['turno']);
                }
            }
        });
    },
    
///////////////// ******** ----                 FIN save_turn             ------ ************ //////////////////

///////////////// ******** ----     iniciar_sesion      ------ ************ //////////////////
//////// Inicia la sesion para el empleado y carga la vista con los filtros solo para el usuario
    // Como parametros puede recibir:
        //  pass -> contraseña a bsucar
        // empleado -> ID del empleado

    iniciar_sesion : function($objeto) {

        console.log('--------> objeto Iniciar sesion');
        console.log($objeto);
        
    // ** Validaciones
    // Valida si se debe de pedir el pass o no
        if($objeto['pedir_pass'] != 2){
            if (!$objeto['pass']) {
                var $mensaje = 'Introduce el pass';
                $('#pass_empleado').notify($mensaje, {
                    position : "top center",
                    autoHide : true,
                    autoHideDelay : 5000,
                    className : 'warn',
                });
    
                return 0;
            }
        }
    // ** Fin validaciones

        $.ajax({
            data : $objeto,
            url : 'ajax.php?c=repartidores&f=iniciar_sesion',
            type : 'GET',
            dataType : 'json',
            success : function(resp) {
                console.log('--------> RESPONSE Iniciar sesion');
                console.log(resp);
            
            // Limpia el campo de pass
                $('#pass_empleado').val('');

            // Error :(
                if (resp['status'] == 0) {
                    //alert('pass');
                    //return false;
                    var $mensaje = 'Contraseña incorrecta';
                    $('#pass_empleado').notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'warn',
                    });

                    return 0;
                }

            // Cierra la ventana modal y filtra por los permisos del empleado
                if (resp['status'] == 1) {
                // Cierra la ventana de pass
                    $('#btn_cerrar_pass').click();
                    
                // Abre la ventana de mesas
                    $("#modal_mesas").modal();

                // Lista las asignaciones del empleado
                    comandas.listar_asignacion({
                        id : $objeto['empleado']
                    });
                    
                    return 0;
                }

            // Cierra la ventana modal y trae todas las mesas
                if (resp['status'] == 2) {

                // Cierra la ventana de pass
                    $('#btn_cerrar_pass').click();

                // Abre la ventana de mesas
                    $("#modal_mesas").modal();

                // Lista las asignaciones del empleado
                    comandas.listar_asignacion({
                        id : $objeto['empleado']
                    });
                    return 0;
                }
            }
        });
    },

///////////////// ******** ----         FIN iniciar_sesion      ------ ************ //////////////////

///////////////// ******** ----         listar_asignacion       ------ ************ //////////////////
    //////// Obtien los permisos del empleado y palome los checks correspodientes
        // Como parametros recibe:
            // id -> ID del empleado

    listar_asignacion : function($objeto) {

        
        console.log('------------> $objeto listar_asignacion');
        console.log($objeto);


        empleado = $objeto['id'];
        $('#id_empleado').val(empleado);

        /// sin pass
        comandas.listar_mesas({div:'contenedor', asignar:1});
        //

        $.ajax({
            data : $objeto,
            url : 'ajax.php?c=repartidores&f=listar_asignacion',
            type : 'GET',
            dataType : 'json',
            success : function(resp) {
                console.log('------------> response listar_asignacion');
                console.log(resp);

            // Error: Manda un mensaje con el error
                if (resp['status'] == 0) {
                    console.log('vacio');
                    var $mensaje = 'Error: \n Error al obtener las asignaciones';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'error',
                    });

                    return 0;
                }

            // Empleado CON permisos
                if (resp['status'] == 1) {
                // Desmarca las mesas
                    $.each(resp['mesas'], function(index, val) {
                        $('#btn_' + val['idcomanda']).removeClass("btn-success");
                    });

                // Marca solo las mesas asignadas al empleado
                    $.each(resp['permisos'], function(index, val) {
                        $('#btn_' + val).addClass('btn-success');
                    });

                    return 0;
                }

            // Empleado SIN permisos
                if (resp['status'] == 2) {
                // Desmarca las mesas
                    $.each(resp['mesas'], function(index, val) {
                        $('#btn_' + val['idcomanda']).removeClass("btn-success");
                    });

                    return 0;
                }
            }
        });
    },

///////////////// ******** ----         FIN listar_asignacion       ------ ************ //////////////////

///////////////// ******** ----         search_turn     ------ ************ //////////////////
    //////// Agrega la mesa a los permisos del empleado
        // Como parametros recibe:
            // id -> ID del empleado
            // id_mesa -> ID de la mesa

    search_turn : function($objeto) {

        console.log('-------------> $objeto search_turn');
        console.log($objeto);
        if ($('#turnos').val() != '') {
            $(".repartidores").hide();
            $(".repartidores").each(function(){
                if($(this).attr('turno') == $('#turnos').val()){
                    $(this).show();
                }
            });
            $("#table_pedidos tr").each(function(){
                $(this).hide();
                if($(this).attr('turno') == $('#turnos').val() || $(this).attr('turno') == -1){
                    $(this).show();
                }
            });
            var tabla = $('#table_pedidos').DataTable();
            tabla.destroy();
            var table = $('#table_pedidos').DataTable({                                                            
                            order: [[ 0, "desc" ]],
                            destroy: true,
                            language: { 
                                buttons: {
                                    print: 'Imprimir'
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
                        });             
        } else {
            $(".repartidores").show();
            $("#table_pedidos tr").each(function(){
                $(this).show();
            });
        }
    },

///////////////// ******** ----         FIN search_turn     ------ ************ //////////////////

///////////////// ******** ----         asignar     ------ ************ //////////////////
    //////// Agrega la mesa a los permisos del empleado
        // Como parametros recibe:
            // id -> ID del empleado
            // id_mesa -> ID de la mesa

    asignar : function($objeto) {
        //alert(888);
        $objeto['id'] = empleado;

        console.log('-------------> $objeto asignar');
        console.log($objeto);

        $.ajax({
            data : $objeto,
            url : 'ajax.php?c=repartidores&f=asignar',
            type : 'GET',
            dataType : 'json',
            success : function(resp) {
                console.log('-------------> response asignar');
                console.log(resp);

            // Error: Manda un mensaje con el error
                if (resp['status'] == 0) {
                    var $mensaje = 'Error: \n Error al asignar la mesa';
                    $('#btn_' + $objeto['id_mesa']).notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'error',
                    });

                    return 0;
                }

            // Se agrego una mesa al mesero
                if (resp['status'] == 1) {
                    comandas.listar_asignacion({
                        id : empleado
                    });
                }
            }
        });
    },

///////////////// ******** ----         FIN asignar     ------ ************ //////////////////

///////////////// ******** ----         guardar_asignacion      ------ ************ //////////////////
    //////// Guarda los permisos de los empleados
        // Como parametros recibe:
            // empleado -> ID del empleado
            // Vista -> 1: Vista empleados, 2: Vista asignacion

    guardar_asignacion : function($objeto) {
        console.log('------------> objeto guardar_asignacion');
        console.log($objeto);

        
    // Loader en el boton Guardar
        var $btn = $('#btn_guardar');
        $btn.button('loading');

        $.ajax({
            data : $objeto,
            url : 'ajax.php?c=repartidores&f=guardar_asignacion',
            type : 'GET',
            dataType : 'json',
            success : function(resp) {
                console.log('-------------> response guardar_asignacion');
                console.log(resp);

            // Quita el loader
                $btn.button('reset');

            // Elimina el pass del campo
                $('#pass_empleado').val('');

            // Error: Manda un mensaje con el error
                if (resp['status'] == 0) {
                    var $mensaje = 'Error al guardar las asignaciones';
                    $('#btn_guardar').notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'error',
                    });

                    return 0;
                }

            // Se agregaron las mesas al mesero
                if (resp['status'] == 1) {
                // Cierra la ventana de las mesas
                    $('#btn_cerrar_mesas').click();
                    comandas.listar_mesas({div:'contenedor', asignar:1});
                    listarpedidosRep();
                }
            }
        });
    },

//////////////// ******** ----      FIN guardar_asignacion      ------ ************ //////////////////


}




