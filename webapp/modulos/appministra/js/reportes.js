/// FUNC GENERALES
function formatFecha(fecha){ //ej 2016-12-12
    var dd = fecha.substr(-2,2);
    var mm = fecha.substr(5,2);
    var yyyy = fecha.substr(0,4);
    var fechaF = dd+'/'+mm+'/'+yyyy;   // 12/12/2016
    return fechaF;
}
function formatFechaUS(fecha){ //ej 2016-12-12 00:00:01 para quitar Hora
    var dd = fecha.substr(8,2);
    var mm = fecha.substr(5,2);
    var yyyy = fecha.substr(0,4);
    var fechaF = yyyy+'-'+mm+'-'+dd;  // 2016-12-12 
    return fechaF;
}
function formatFechaL(fecha){ //ej 2016-12-12 00:00:01
    var dd = fecha.substr(8,2);
    var mm = fecha.substr(5,2);
    var yyyy = fecha.substr(0,4);
    var fechaF = dd+'/'+mm+'/'+yyyy;   // 12/12/2016
    return fechaF;
}
function printIA(div){
    $("#"+div+"").hide();
    window.print();
    //alert("printing");
    $("#"+div+"").show();
}
function hoy(){
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1; //hoy es 0!
    var yyyy = hoy.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 
    if(mm<10) {
        mm='0'+mm
    } 

    return hoy = dd+'/'+mm+'/'+yyyy;
}
function hoy2(){
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1; //hoy es 0!
    var yyyy = hoy.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 
    if(mm<10) {
        mm='0'+mm
    } 

    return hoy = yyyy+'-'+mm+'-'+dd;
}
function mesA(){
    var fecha=new Date();
    var mesA=new Date(fecha.getTime() - (24*60*60*1000)*30);
    var dd = mesA.getDate();
    var mm = mesA.getMonth()+1;
    var yyyy = mesA.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 
    if(mm<10) {
        mm='0'+mm
    } 
    return mesA = yyyy+'-'+mm+'-'+dd;
} 
/// FUNC FIN 

/////// CP CATAPRODUCTOS //////////////////

    function reloadtableCP(){

        $("#modalMensajes").modal('show');

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

                var act = "";
                  if ($('#Ract3').prop('checked')) {
                    act = $('#Ract3').val();
                  }
                  if ($('#Ract1').prop('checked')) {
                    act = $('#Ract1').val();
                  }
                  if ($('#Ract0').prop('checked')) {
                    act = $('#Ract0').val();
                  }

                  var producto  = $('#productoS').val();
                  var tipoPro   = $('#tipoPro').val();

                  var unidad    = $('#unidadS').val();
                  var moneda    = $('#monedaS').val();

                $.ajax({
                    url: 'ajax.php?c=reportes&f=listProductosCP',
                    type: 'post',
                    dataType: 'html',
                    data:{producto:producto,unidad:unidad,moneda:moneda,lotes:lotes,series:series,pedi:pedi,caract:caract,act:act,tipoPro:tipoPro},
                })
                .done(function(data) { 
                    var tablas = data.split('ª');

                    var tablaP = tablas[0];
                    $('#divp').html('');
                    $('#divp').append(tablaP);
                    $('#tablepro').DataTable( {
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'print',
                                        title: $('h1').text(),
                                        customize: function ( win ) {
                                            $(win.document.body)
                                            .css( 'font-size', '10pt' )
                                            .prepend(
                                            '<h3>Catalogo de Productos</h3>'
                                            );                                                     
                                        }
                                    },
                                    'excel',
                                ],
                                destroy: true,
                                searching: true,
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
                                 }
                            });

                    var tablaS = tablas[1];
                    $('#divs').html('');
                    $('#divs').append(tablaS);
                    $('#tableser').DataTable( {
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'print',
                                        title: $('h1').text(),
                                        customize: function ( win ) {
                                            $(win.document.body)
                                            .css( 'font-size', '10pt' )
                                            .prepend(
                                            '<h3>Catalogo de Productos</h3>'
                                            );                                                     
                                        }
                                    },
                                    'excel',
                                ],
                                destroy: true,
                                searching: true,
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
                                 }
                            });

                    $("#divcp, #divcs").show();
                    $("#modalMensajes").modal('hide');
                }) 
    }
    function reloadselectCP(){
        // Select Productos
        $.ajax({
                url: 'ajax.php?c=reportes&f=selectProductos',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $('#productoS').html('');
            $.each(data, function(index, val) {
                  $('#productoS').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        }) 

        // Select Unidades
        $.ajax({
                url: 'ajax.php?c=reportes&f=selectUnidades',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                  $('#unidadS').append('<option value="'+val.id+'">'+val.clave+' - '+val.nombre+'</option>');  
            });
        })

        // Select Monedas
        $.ajax({
                url: 'ajax.php?c=reportes&f=selectMonedas',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                  $('#monedaS').append('<option value="'+val.coin_id+'">'+val.codigo+' - '+val.description+'</option>');  
            });
        })  
    }
    function limpiarCP(){
        $("#codigo, #nombre, #tipo, #unidad, #moneda").val('').attr('readonly', true);
        $("#caract, #lotes, #series, #pedimen").text('').attr('readonly', true);
    }
    function verProducto(id,iddiv,det,caract1,lotes1,series1,pedimen1){
            $.ajax({
                    url: 'ajax.php?c=reportes&f=listProducto',
                    type: 'post',
                    dataType: 'json',
                    data:{id:id},
            })
            .done(function(data) {
                limpiarCP();
                
                $.each(data, function(index, val) {
                    var codigo = val.codigo; 
                    var nombre = val.nombre; 
                    var imagen = val.ruta_imagen; 
                    var moneda = val.moneda;
                    var unidad = val.unidad;
                    var tipo   = val.tipo_producto;

                    if(tipo == 1){
                        tipoR = 'PRODUCTO';
                    }
                    if(tipo == 2){
                        tipoR = 'SERVICIO';
                    }
                    if(tipo == 3){
                        tipoR = 'INSUMO';
                    }
                    if(tipo == 4){
                        tipoR = 'INSUMO-PREPARADO';
                    }
                    if(tipo == 5){
                        tipoR = 'RECETA';
                    }

                    $("#codigo").val(codigo);
                    $("#nombre").val(nombre);
                    $("#tipo").val(tipoR);
                    $("#unidad").val(unidad);
                    $("#moneda").val(moneda);

                    $("#imagen").attr('src', '../pos/'+imagen);

                    if(det == 1){

                        $.ajax({
                            url: 'ajax.php?c=reportes&f=textAreaCP',
                            type: 'post',
                            dataType: 'json',
                            data:{id:id},
                        })
                        .done(function(data) {
                            if(caract1 == 'SI'){
                                var caract = '';
                                $.each(data.caract, function(index, val) {
                                    caract = val.nombre + '\n' + caract;
                                    $("#caract").text(caract);
                                });
                                $('#divcarac').show();
                            }else{
                                $('#divcarac').hide();
                            }
                            if(lotes1 == 'SI'){
                                var lotes = '';
                                $.each(data.lotes, function(index, val) {
                                    lotes = val.lotes + '\n' + lotes;
                                    $("#lotes").text(lotes);
                                });
                                $('#divlotes').show();
                            }else{
                                $('#divlotes').hide();
                            }
                            if(series1 == 'SI'){
                                var serie = '';
                                $.each(data.serie, function(index, val) {
                                    serie = val.serie + '\n' + serie;
                                    $("#series").text(serie);
                                });
                                $('#divseries').show();
                            }else{
                                $('#divseries').hide();
                            }
                            if(pedimen1 == 'SI'){
                                var pedimen = '';
                                $.each(data.pedimen, function(index, val) {
                                    pedimen = val.pedimento + '\n' + pedimen;
                                    $("#pedimen").text(pedimen);
                                });
                                $('#divpedimen').show();
                            }else{
                                $('#divpedimen').hide();
                            }
                            $('#'+iddiv).modal('show'); // show bootstrap modal
                            //console.log(data.caract);
                        });
                }
                if(det == 1){

                }else{
                    $('#'+iddiv).modal('show'); // show bootstrap modal
                    $('#divpedimen').hide();
                    $('#divseries').hide();
                    $('#divlotes').hide();
                    $('#divcarac').hide();
                } 
                });
            });
    }
/////// CP CATAPRODUCTOS FIN///////////////


/////// IA INVENTARIO ACTUAL   /////////////
    function rowU(codigo){

        if($(".rowU"+codigo+"").hasClass('rowhide') == true){
            $(".rowU"+codigo+"").removeClass('rowhide');
            $("#iU"+codigo+"").removeClass('glyphicon-chevron-down');
            $("#iU"+codigo+"").addClass('glyphicon-chevron-up'); 
        }else{
            $(".rowU"+codigo+"").addClass('rowhide'); 
            $("#iU"+codigo+"").removeClass('glyphicon-chevron-up');
            $("#iU"+codigo+"").addClass('glyphicon-chevron-down'); 
            $("."+codigo+"").addClass('rowhide'); /// oculta los rows hijos
        }      
    }
    function rowU2(codigo){

        if($(".rowU2"+codigo+"").hasClass('rowhide') == true){
            $(".rowU2"+codigo+"").removeClass('rowhide');
            $("#iU2"+codigo+"").removeClass('glyphicon-chevron-down');
            $("#iU2"+codigo+"").addClass('glyphicon-chevron-up'); 
        }else{
            $(".rowU2"+codigo+"").addClass('rowhide');
            $("#iU2"+codigo+"").removeClass('glyphicon-chevron-up');
            $("#iU2"+codigo+"").addClass('glyphicon-chevron-down'); 
        }  
    }
    function reloadselectIA(pref){
        //PRODUCTOS
        $.ajax({
                url: 'ajax.php?c=reportes&f=selectProductos',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $('#producto'+pref+'').html('');
            $.each(data, function(index, val) {
                  $('#producto'+pref+'').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
            //reloadtableIA();
            //reloadtableIAIm();
        })
        //SUCURSAL
        $.ajax({
                url: 'ajax.php?c=reportes&f=listSucursal',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $('#sucursal'+pref+'').html('');
            $('#sucursal'+pref+'').append('<option value="0">-Todos-</option>');
            $.each(data, function(index, val) {
                  $('#sucursal'+pref+'').append('<option value="'+val.idSuc+'">'+val.nombre+'</option>');  
            });
            $('#sucursal'+pref+'').change(function()
            {   
                $('#almacen'+pref+'').html('');
                $('#almacen'+pref+'').append('<option selected="selected" value="0">-Todos-</option>');
                idSuc = $('#sucursal'+pref+'').val();

                    $.ajax({ 
                        data : {idSuc:idSuc},
                        url: 'ajax.php?c=reportes&f=listAlmacen',
                        type: 'post',
                        dataType: 'json',
                    })
                    .done(function(data) {
                        $('#almacen'+pref+'').select2("val", '');
                        $.each(data, function(index, val) {
                              $('#almacen'+pref+'').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                        });
                    }) 
            });
        })
        //ALMACEN
        $.ajax({ 
                url: 'ajax.php?c=reportes&f=listAlmacen',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                $('#almacen'+pref+'').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        })   
    }
    function procesarIA(){

        var as = $("#almacenIA").select2().val();
        if(as == null){
            alert("Seleccione un Almacen");
            return false
        }
        
        //$('#btnprocesarIA').attr('disabled', true);
        //$('#btnprocesarIA').text('Procesando...');

        var almacenSelect = $('#almacenIA option:selected').text();
        $("#lbalmacen, #lbalmacenI").text(almacenSelect);
     
         var sucursalSelect = $('#sucursalIA option:selected').text();
        $("#lbsucursal, #lbsucursalI").text(sucursalSelect);

        var productoSelect = $('#productoIA option:selected').text();
        $("#lbproducto, #lbproductoI").text(productoSelect);
 
        //var desde = $("#desde").val();
        var desde = '2000-01-01';
        var hasta = $("#hasta").val();

        if((desde != '' && hasta == '') || (desde == '' && hasta != '')){
            alert('Debe selecionar un Rango de Fecha');
            $('#btnprocesarIA').attr('disabled', false);
            $('#btnprocesarIA').text('Procesar');
            return false;
        }
        desdeF =  formatFecha(desde);
        hastaF =  formatFecha(hasta);
        if(desde == '' && hasta ==''){
            var periodoF = 'Sin Rango';
        }else{
            var periodoF = 'Del '+desdeF+' al '+hastaF;
        }
        $("#lbperiodo, #lbperiodoI").text(periodoF);

        if(hasta == ''){
            hasta = '2199-01-01';
        }else{
            hasta = hasta;
        }
        if(desde == ''){
            desde = '1199-01-01';
        }else{
            desde = desde;
        }

        var tipoProIA   = $("#tipoProIA").val();
        var productoIA  = $("#productoIA").val();
        var sucursalIA  = $("#sucursalIA").val();
        var almacenIA   = $("#almacenIA").val();
        var unidades    = $("#unidadesIA").val();
        var provedor = $('#provedor').val();


        var consigna = $('#consigna').val();
       

        $("#modalMensajes").modal('show');

        var rep = 2;
        if ($('#R2det').prop('checked')) {
            rep = 1; // det
        }
        if ($('#R2rap').prop('checked')) {
            rep = 2; // rap
        }

        var unid = 2;
        if ($('#unidC').prop('checked')) {
            unid = 1; // compra
        }
        if ($('#unidV').prop('checked')) {
            unid = 2; // venta
        }


        if ($('#R1ambosIA').prop('checked')) {
            //reloadtableIA(1,periodoF,0);
            reloadtableIAUD(desde,hasta,productoIA,sucursalIA,almacenIA,periodoF,tipoProIA,'uni',rep,unid,unidades,provedor,consigna); // unidades
            reloadtableIAID(desde,hasta,productoIA,sucursalIA,almacenIA,periodoF,tipoProIA,'imp',rep,unid,unidades,provedor,consigna); // importe

            //reloadtableIAIm();
            $("#divunidades").show();
            $("#divimporte").show();
        }

        if ($('#R1IAUD').prop('checked')) {

            reloadtableIAUD(desde,hasta,productoIA,sucursalIA,almacenIA,periodoF,tipoProIA,'uni',rep,unid,unidades,provedor,consigna);

            $("#divunidades").show();
            $("#divimporte").hide();
        }
        if ($('#R1IAID').prop('checked')) {

            reloadtableIAID(desde,hasta,productoIA,sucursalIA,almacenIA,periodoF,tipoProIA,'imp',rep,unid,unidades,provedor,consigna);

            $("#divunidades").hide();
            $("#divimporte").show();
        }
    }
    function reloadtableIAUD(desde,hasta,productoIA,sucursalIA,almacenIA,periodoF,tipoProIA,tipo2,rep,unid,unidades,provedor,consigna){


        var sucursalSelect = $('#sucursalIA option:selected').text();

        var nomSuc = $('#sucursalIA option:selected').text();
        var nomAl = $('#almacenIA option:selected').text();
        var productos = $('#productoIA option:selected').text();
        if(rep == 1){var dtype = 'json';}
        if(rep == 2){var dtype = 'html';}


            $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                    url: 'ajax.php?c=reportes&f=listInvActMov',
                    type: 'post',
                    dataType: dtype,
                    data: {producto:productoIA,desde:desde,hasta:hasta,tipo:'movs',almacen:almacenIA,sucursal:sucursalIA,tipoProIA:tipoProIA,tipo2:tipo2,rep:rep,unid:unid,unidades:unidades,provedor:provedor,consigna:consigna}, 
            }) 

            .done(function(data) {
                if(rep == 1){
                    $("#resultadoU").html('');
                    $("#resultadoI").html('');
                    $("#divtableU").show();
                    var table   = $('#table_unidaIA').DataTable( {
                                                                        dom: 'Bfrtip',
                                                                        buttons: [
                                                                        {
                                                                            extend: 'print',
                                                                            title: $('h1').text(),
                                                                            customize: function ( win ) {
                                                                                $(win.document.body)
                                                                                    .css( 'font-size', '10pt' )
                                                                                    .prepend(
                                                                                        '<h3>Inventario Actual</h3><br><h5>Tipo: <label>Unidades </label> <br> Sucursal: <label>'+nomSuc+'</label><br> Almacen: <label>'+nomAl+'</label> <br> Periodo: <label>'+periodoF+'</label> <br> Productos: <label >'+productos+'</label></h5>'
                                                                                    );                                                     
                                                                            }
                                                                        },
                                                                        'excel',
                                                                        ],
                                                                        destroy: true,
                                                                        searching: true,
                                                                        deferRender: true,
                                                                        paginate: false,
                                                                        filter: false,
                                                                        sort: false,
                                                                        info: false,
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
                                                                         }
                                        });

                    table.clear().draw();
                    var c ='';

                    $.each(data, function(index, val) {
                        c = val.row;
                    
                    table.row.add($(c)).draw();
                    });
                    $("#modalMensajes").modal('hide');
                }
                if(rep == 2){
                    $("#divtableU").hide();
                    $("#modalMensajes").modal('hide');
                    $("#resultadoU").html('');
                    $("#resultadoU").append(data);
                }  
            })     
          
    }
    function reloadtableIAID(desde,hasta,productoIA,sucursalIA,almacenIA,periodoF,tipoProIA,tipo2,rep,unid,unidades){

        var sucursalSelect = $('#sucursalIA option:selected').text();
        var nomSuc = $('#sucursalIA option:selected').text();
        var nomAl = $('#almacenIA option:selected').text();
        var productos = $('#productoIA option:selected').text();
        if(rep == 1){var dtype = 'json';}
        if(rep == 2){var dtype = 'html';}


            $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                    url: 'ajax.php?c=reportes&f=listInvActMov',
                    type: 'post',
                    dataType: dtype,
                    data: {producto:productoIA,desde:desde,hasta:hasta,tipo:'movs',almacen:almacenIA,sucursal:sucursalIA,tipoProIA:tipoProIA,tipo2:tipo2,rep:rep,unidades:unidades}, 
            })
            .done(function(data) {
                if(rep == 1){
                    $("#resultadoI").html('');
                    $("#resultadoU").html('');
                    $("#divtableI").show();
                    var table   = $('#table_ImporteIA').DataTable( {
                                                                            dom: 'Bfrtip',
                                                                            buttons: [
                                                                            {
                                                                                extend: 'print',
                                                                                title: $('h1').text(),
                                                                                customize: function ( win ) {
                                                                                    $(win.document.body)
                                                                                        .css( 'font-size', '10pt' )
                                                                                        .prepend(
                                                                                            '<h3>Inventario Actual</h3><br><h5>Tipo: <label>Unidades </label> <br> Sucursal: <label>'+nomSuc+'</label><br> Almacen: <label>'+nomAl+'</label> <br> Periodo: <label>'+periodoF+'</label> <br> Productos: <label >'+productos+'</label></h5>'
                                                                                        );                                                     
                                                                                }
                                                                            },
                                                                            'excel',
                                                                            ],
                                                                            destroy: true,
                                                                            searching: true,
                                                                            deferRender: true,
                                                                            paginate: false,
                                                                            filter: false,
                                                                            sort: false,
                                                                            info: false,
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
                                                                             }
                                            });

                        table.clear().draw();
                    var c ='';

                    $.each(data, function(index, val) {
                        c = val.row;
                    
                    table.row.add($(c)).draw();
                    });
                    $("#modalMensajes").modal('hide');
                }
                if(rep == 2){
                    $("#divtableI").hide();
                    $("#modalMensajes").modal('hide');
                    $("#resultadoI").html('');
                    $("#resultadoI").append(data);
                }
            })
          
    }
/////// IA INVENTARIO ACTUAL   FIN  /////////////


//// MOVIMIENOTS INVENTARIO   ////////////
    function reloadselectMI(){
        $.ajax({
                url: 'ajax.php?c=reportes&f=selectProductos',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                  $('#productoMI').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        })
        /*
        $.ajax({
                url: 'ajax.php?c=reportes&f=selectDepartamento',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                  $('#departamentoMI').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        })
        */
        $.ajax({
                url: 'ajax.php?c=reportes&f=listSucursal',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $('#sucursalMI').html('');
            $('#sucursalMI').append('<option value="0">-Todos-</option>');
            $.each(data, function(index, val) {
                  $('#sucursalMI').append('<option value="'+val.idSuc+'">'+val.nombre+'</option>');  
            });
            $('#sucursalMI').change(function()
            {   
                $('#almacenMI').html('');
                $('#almacenMI').append('<option selected="selected" value="0">-Todos-</option>');
                idSuc = $('#sucursalMI').val();

                    $.ajax({ 
                        data : {idSuc:idSuc},
                        url: 'ajax.php?c=reportes&f=listAlmacen',
                        type: 'post',
                        dataType: 'json',
                    })
                    .done(function(data) {
                        $("#almacenMI").select2("val", '');
                        $.each(data, function(index, val) {
                              $('#almacenMI').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                        });
                    }) 
                
            });
        })
        $.ajax({ 
                url: 'ajax.php?c=reportes&f=listAlmacen',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                $('#almacenMI').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        })   
    }
    function procesarMI() {

        var sucursalSelect = $('#sucursalMI option:selected').text();
        $("#lbsucursal").text(sucursalSelect);

        var almacenSelect = $('#almacenMI option:selected').text();
        $("#lbalmacen").text(almacenSelect);

        var desde = $("#desde").val();
        var hasta = $("#hasta").val();

        if((desde != '' && hasta == '') || (desde == '' && hasta != '')){
            alert('Debe selecionar un Rango de Fecha');
            return false;
        }
        desdeF =  formatFecha(desde);
        hastaF =  formatFecha(hasta);
        if(desde == '' && hasta ==''){
            var periodo = 'Sin Rango';
        }else{
            var periodo = 'Del '+desdeF+' al '+hastaF;
        }
        $("#lbperiodo").text(periodo);
        $("#divreporte").hide();


        var desde = $("#desde").val();
        if(desde==''){
            desde='2000-12-12';
        }else{
            desde = desde;
        }
        var hasta = $("#hasta").val();
        if(hasta==''){
            hasta='2200-12-12';
        }else{
            hasta = hasta;
        }
        var sucursal     = $('#sucursalMI').val();
        var almacen      = $('#almacenMI').val();
        var producto     = $('#productoMI').val();
        var departamento = $('#departamentoMI').val();

        $("#modalMensajes").modal('show');

        reloadtbableMI2(desde,hasta,sucursal,almacen,producto,departamento,periodo);
    }
    function reloadtbableMI2(desde,hasta,sucursal,almacen,producto,departamento,periodo){

        var nomSuc = $('#sucursalMI option:selected').text();
        var nomAl = $('#almacenMI option:selected').text();
        var productos = $('#productoMI option:selected').text();

        $.ajax({
            url: 'ajax.php?c=reportes&f=listMovInvMov',
            type: 'post',
            dataType: 'json',
            data:{desde:desde,hasta:hasta,sucursal:sucursal,almacen:almacen,producto:producto,departamento:departamento},
        })
        .done(function(data) {

            var table   = $('#table_mov').DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                                                    {
                                                                        extend: 'print',
                                                                        title: $('h1').text(),
                                                                        customize: function ( win ) {
                                                                            $(win.document.body)
                                                                                .css( 'font-size', '10pt' )
                                                                                .prepend(
                                                                                    '<h3>Moviminetos Inventario</h3><br><h5>Tipo: <label>Unidades </label> <br> Sucursal: <label>'+nomSuc+'</label><br> Almacen: <label>'+nomAl+'</label> <br> Periodo: <label>'+periodo+'</label> <br> Productos: <label >'+productos+'</label></h5>'
                                                                                );                                                     
                                                                        }
                                                                    },
                                                                    'excel',
                                                                    ],
                                language: { 
                                    buttons: {
                                        print: 'Imprimir'
                                    },search: "Buscar:",
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
                                "bPaginate": false,
                                "bLengthChange": false,
                                "bFilter": false,
                                "bInfo": false,
                                "sort": false,
                                "bAutoWidth": false,
                                "bDestroy": true /// permite destruit al volver a recargar
                            });
            
            table.clear().draw();
            var x ='', h ='', ser = '', ped = '', lot = '', exi = '', xC = '', xU = '';
            var codigoAnt       = 'ttt';
            var almacenRRAnt    = 'ttt';
            var existenciaR     = 0;
            var clientProv      = '';
            var precioVentaF    = '';
            var importeVentaF   = '';
            var costoUniF       = '';
            var importeTComF    = '';

            $.each(data.kardexF, function(index, val) {

                var nombreAlmacen    = val.nombreAlmacen;
                var nombre           = val.nombre;
                var costeo           = val.costeo;
                var unidad           = val.unidad;
                var codigo           = val.codigo;
                var almacenRR        = val.almacenRR;
                var tipo_traspaso    = val.tipo_traspaso;
                var fecha            = val.fecha;
                var fechaF           = formatFechaL(fecha);
                var folio            = val.id;
                var cantidad         = val.cantidad*1;
                var idMove           = val.idMove;
                var concepMove       = val.concepMove;
                var nombretienda     = val.nombretienda; 
                var razon_social     = val.razon_social;
                var count2           = val.count2;
                var traspasoaux      = val.traspasoaux;
                var fechaUS          = formatFechaUS(fecha);
                var caract           = val.caract;
                var auxP             = val.auxP;
                var auxC             = val.auxC;
                var exisUcar         = val.exisUcar;
                var exisU            = val.exisU;
                var auxU             = val.auxU;
                var almacenUbicacion = val.almacenUbicacion;
                var cantidadI        = val.cantidadI*1;
                var costoU           = cantidadI/cantidad;

                var nombretiendapos  = val.nombretiendapos;
                var razon_socialpos  = val.razon_socialpos;
                var origen           = val.origen*1;


                if(caract == ''){
                    caract = 'Sin Caracteristica';
                }else{
                    caract = caract;
                }
               
                if(almacenRR != almacenRRAnt || codigo != codigoAnt){
                    existenciaR = 0;
                }

                if(traspasoaux == 1){
                    existenciaR += cantidad*1;
                    var concepto        = '<b>Entrada/Compra</b>';
                    if(tipo_traspaso == 2){
                        concepto = concepto + '(Traspaso) '+ caract;
                    }else{
                        concepto = concepto+' '+caract;
                    }
                        costoUniF       = '$'+costoU.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                        importeTComF    = '$'+cantidadI.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                    //if(concepMove == "Orden de compra " || concepMove == "Devolucion de venta "){ 
                    if(origen == 1 || (origen == 0 && concepMove == "Orden de compra ") || (origen == 0 && concepMove == "Devolucion de venta ") ){                    
                        clientProv      = razon_social; 
                        if(clientProv == null){
                            clientProv = '';/// no proveedor
                        }
                    }else if(origen == 2){
                        clientProv      = razon_socialpos;
                        if(clientProv == null){
                            clientProv = '';/// no proveedor
                        }
                    }else{
                        clientProv      = '';
                    }
                }else{
                    costoUniF        = '';
                    importeTComF     = '';
                }

                if(traspasoaux == 0){
                    existenciaR -= cantidad*1;
                    var concepto        = '<b>Salida/Venta</b>';
                    if(tipo_traspaso == 2){
                        concepto = concepto + '(Traspaso)' + caract;
                    }else{
                        concepto = concepto +' '+caract;
                    }

                        precioVentaF    = '$'+costoU.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');            
                        importeVentaF   = '$'+cantidadI.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                        //if(concepMove == "Orden de venta " || concepMove == "Devolucion de compra "){
                        if(origen == 1 || (origen == 0 && concepMove == "Orden de venta ") || (origen == 0 && concepMove == "Devolucion de compra ")){
                           clientProv      = nombretienda; 
                           if(clientProv == null){
                            clientProv = '';// no cliente
                           }
                        }else if(origen == 2){
                            clientProv      = nombretiendapos;
                            if(clientProv == null){
                                clientProv = 'Público en General';// no cliente
                            }
                        }
                        else{
                            clientProv      = '';
                        }
                }else{
                    precioVentaF    = '';
                    importeVentaF   = '';
                }

                var id_pedimento        = val.id_pedimento;
                var no_pedimento        = val.no_pedimento;
                var fecha_pedimento     = val.fecha_pedimento;
                var no_aduana           = val.no_aduana;
                var tipo_cambio         = val.tipo_cambio;
                var id_lote             = val.id_lote;
                var no_lote             = val.no_lote;
                var fecha_caducidad     = val.fecha_caducidad;
                var fecha_fabricacion   = val.fecha_fabricacion;
                var serie               = val.series;


                if(almacenRR != almacenRRAnt || codigo != codigoAnt){
                    if(costeo == 1){
                        var costeoF = 'Costo Promedio';
                    }
                    if(costeo == 2){
                        var costeoF = 'Costo Promedio por Almacen';
                    }
                    if(costeo == 3){
                        var costeoF = 'Último Costo';
                    }
                    if(costeo == 4){
                        var costeoF = 'UEPS';
                    }
                    if(costeo == 5){
                        var costeoF = 'PEPS';
                    }
                    if(costeo == 6){
                        var costeoF = 'Costo Especifico';
                    }
                    if(costeo == 7){
                        var costeoF = 'Costo Estandar';
                    }
                    h ='<tr class="trhead">'+
                            '<td>Almacen:<br/>Producto:<br/>Costeo:<br/>Unidad:</td>'+
                            '<td colspan="9">'+nombreAlmacen+'<br>'+nombre+'<br>'+costeoF+'<br>'+unidad+'</td><br>'+
                            '<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>'+
                        '</tr><br>';
                }
                x ='<tr>'+
                        '<td>'+fechaF+'</td>'+
                        '<td align="center">'+folio+'</td>'+
                        '<td>'+concepto+'</td>'+ 
                        '<td align="center">'+clientProv+'</td>'+ 
                        '<td align="center">'+cantidad+'</td>'+
                        '<td align="right">'+costoUniF+'</td>'+                                                   
                        '<td align="right">'+importeTComF+'</td>'+
                        '<td align="right">'+precioVentaF+'</td>'+
                        '<td align="right">'+importeVentaF+'</td>'+
                        '<td></td>'+
                    '</tr>';

                if(auxC == 1){
                    xC ='<tr>'+
                        '<td align="center" colspan = "2"> <b>Total Caracteristica </b></td>'+
                        '<td style="display: none;"></td>'+
                        '<td colspan = "2"><b>'+caract+'</b></td>'+ 
                        '<td style="display: none;"></td>'+
                        '<td align="center"><b>'+exisUcar+'</b></td>'+                 
                        '<td></td>'+                                                   
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                    '</tr>';
                }
                if(auxU == 1){
                    xU ='<tr>'+
                        '<td align="center" colspan = "2"> <b>Total Ubicacion </b></b></td>'+
                        '<td style="display: none;"></td>'+
                        '<td colspan = "2"><b>'+almacenUbicacion+'</b></td>'+ 
                        '<td style="display: none;"></td>'+
                        '<td align="center"><b>'+exisU+'</b></td>'+                     
                        '<td></td>'+                                                   
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                    '</tr>';
                }

                if(serie != ''){
                  ser ='<tr>'+
                        '<td class="trh" height ="13px">No. Serie</td>'+
                        '<td class="trh" align="center" colspan="9">'+serie+'</td>'+
                        '<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>'+
                    '</tr>';  
                }
                
                if(id_pedimento > 0){
                    var cant = 0;
                    $.each(data.pediT, function(index, val) {
                        var idPedi = val.id_pedimento;
                        if(idPedi == id_pedimento){
                            cant = val.cantidad;
                        }
                    });
                    pedimentoF = formatFechaL(fecha_pedimento);
                    tipo_cambio = tipo_cambio*1;
                    tipo_cambioF     = tipo_cambio.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                   ped ='<tr>'+
                        '<td class="trh">Pedimento</td>'+
                        '<td class="trh" align="center">'+no_pedimento+'</td>'+
                        '<td class="trh">Fecha Pedimento</td>'+
                        '<td class="trh" align="center">'+pedimentoF+'</td>'+
                        '<td class="trh" align="center">Aduana</td>'+
                        '<td class="trh" align="center">'+no_aduana+'</td>'+
                        '<td class="trh" align="center">Cantidad</td>'+
                        '<td class="trh">'+cant+'</td>'+
                        '<td class="trh">TC</td>'+
                        '<td class="trh" align="right">$'+tipo_cambioF+'</td>'+
                    '</tr>'; 
                    cant = 0;
                }
                
                if(id_lote > 0){
                    var cant = 0;
                    $.each(data.loteT, function(index, val) {
                        var idLote = val.id_lote;
                        if(idLote == id_lote){
                            cant = val.cantidad;
                        }
                    });
                    caducidadF = formatFechaL(fecha_caducidad);
                    fabricacionF = formatFechaL(fecha_fabricacion);
                    lot ='<tr class="trh">'+
                        '<td class="trh">No. Lote</td>'+
                        '<td class="trh" align="center">'+no_lote+'</td>'+
                        '<td class="trh">Caducidad</td>'+
                        '<td class="trh" align="center">'+caducidadF+'</td>'+
                        '<td class="trh" align="center">Fabricacion</td>'+
                        '<td class="trh" align="center">'+fabricacionF+'</td>'+
                        '<td class="trh" align="center">Cantidad</td>'+
                        '<td class="trh">'+cant+'</td>'+
                        '<td class="trh"></td>'+
                        '<td class="trh"></td>'+
                    '</tr>';
                    cant = 0;
                }

                if(codigo != codigoAnt || almacenRR != almacenRRAnt){
                    table.row.add($(h)).draw();
                } 
                table.row.add($(x)).draw(); 
                
                if(serie != ''){
                    table.row.add($(ser)).draw();
                }
                if(id_pedimento > 0){
                  table.row.add($(ped)).draw();  
                }
                if(id_lote > 0){
                  table.row.add($(lot)).draw();
                }

                if(auxC == 1){
                    table.row.add($(xC)).draw(); 
                }
                if(auxU == 1){
                    table.row.add($(xU)).draw(); 
                }
                if(auxP == 1){
                    existenciaRF     = existenciaR.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');                
                    exi ='<tr class="trf" height="22px">'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>Existencia</b></td>'+
                                '<td align="center"><b>'+existenciaRF+'</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr><br><br>';
                        table.row.add($(exi)).draw();
                }            
                almacenRRAnt        = val.almacenRR;
                codigoAnt         = val.codigo;

                $("#divreporte").show();
            });

            $("#modalMensajes").modal('hide');
        });
    }
//// MOVIMIENOTS INVENTARIO   /////FIN////

