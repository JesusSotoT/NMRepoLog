
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
function generar(){
    var tipoM       = $("#tipoM").val();
    var tipoA       = $("#tipoA").val();
    var cliente     = $("#cliente").val();
    var desde       = $("#desde").val();
    var hasta       = $("#hasta").val();

    if(desde > hasta || desde == '' || hasta == ''){
        alert('Debe Selecionar un Rango Correcto');
        return false;
    }

    $("#divtable").empty();
    $.ajax({
            url: 'ajax.php?c=reportear&f=generar',
            type: 'post', 
            data:{tipoM:tipoM,tipoA,tipoA,cliente:cliente,desde:desde,hasta:hasta}           
    })
    .done(function(data) {

        $("#divtable").append(data);
        $("#divtable").hide();
        $("#divtable").show();
        
        $('#tableReportear').DataTable({dom: 'Bfrtip',
                                                            buttons: [  
                                                                        'excel'
                                                                    ],                                                                                                                    
                                                            destroy: true,
                                                            searching: true,
                                                            paginate: true,
                                                            filter: true,
                                                            sort: true,
                                                            info: true,
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
                                    });
                                    
    })


}


function formRetiro(){

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
}
 function retira(){

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
        mensaje('Procesando...');
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
                    eliminaMensaje();

                    $('#modalformRetiro').modal('hide')
                    $('#cantidad').val('');
                    $('#concepto').val('');
                    $('.trtablita').empty()

                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
}
function formAbono(){

    $('#cantidadAbono').val('');
    $('#conceptoAbono').val('');

    $('#modalformAbono').modal({
                show:true,
            });

}
function buscaCargos(){
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
}
 function abona(){
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
    mensaje('Procesando...');
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
        eliminaMensaje();
        $('#modalformAbono').modal('hide');

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}

function mensaje(mensaje) {
    $('#lblMensajeEstado').text(mensaje);
    $('#modalMensajes').modal({
        show:true,
        keyboard: false,
    });
}   
function eliminaMensaje() {
    $('#modalMensajes').modal('hide');
}