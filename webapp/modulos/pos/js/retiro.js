
function retira(){
    var cantidad = $('#cantidad').val();
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
            $('#modalMensajes').modal({
                    show:true,
                    keyboard: false,
            });

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
                    $('#modalMensajes').modal('hide');
                    $('#modalSuccess').modal({
                         show:true,
                    });

                    $('#modalformRetiro').modal('hide')
                    $('#cantidad').val('');
                    $('#concepto').val('');
                    $('.trtablita').empty()
                    window.open("../../modulos/pos/ticketRetiro.php?idretiro=" +data.id);
                    pintatabla();

                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
    
      /*  $.ajax({
            url: 'ajax.php?c=caja&f=agregaretiro',
            type: 'POST',
            dataType: 'json',
            data: {id: '10'},
            success: function(data) {
                alert('success');
            }
        }); */

} 
 function pintatabla(){

            $.ajax({
                url: 'ajax.php?c=retiro&f=pintatabla',
                type: 'POST',
                dataType: 'json',
            })
            .done(function(data) {
                //console.log(data.rows[0]);

                var table = $('#tablita').DataTable();
        
                //$('.filas').empty();
                table.clear().draw();
                var x ='';
                $.each(data, function(index, val) {
                    var cantidad = val.cantidad;
                    cantidad = cantidad * 1;
                    x ='<tr class="trtablita">'+
                                    '<td>'+val.id+'</td>'+
                                    '<td>'+cantidad.toFixed(2)+'</td>'+
                                    '<td>'+val.concepto+'</td>'+
                                    '<td>'+val.usuario+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td><button class="btn btn-default" onclick="reimprime('+val.id+')"><i class="fa fa-print" aria-hidden="true"></i></button></td>'+
                                    '</tr>';  
                        table.row.add($(x)).draw();                          
                }); 

            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });    
 }
 function reimprime(id){
    window.open("../../modulos/pos/ticketRetiro.php?idretiro=" +id);
 }
 function datatables(){
    
    setTimeout(function(){ $('#tablita').DataTable(); }, 10000);
    
 }
 function usuarios(){

            $.ajax({
                url: 'ajax.php?c=retiro&f=usuarios',
                type: 'POST',
                dataType: 'json',
            })
            .done(function(data) {
                var option ='';
                option +='<option value="0">-Seleciona un usuario-</option>';
                $.each(data.rows, function(index, val) {
                   option +='<option value="'+val.idempleado+'">'+val.usuario+'</option>';
                }); 
                $('#usuario').append(option);
               // datatables();
            })
            .fail(function() {
                //console.log("error");
            })
            .always(function() {
                //console.log("complete");
            });     
 }
 function filtra(){
    
    var desde = $('#desde').val();
    var hasta = $('#hasta').val();
    var user = $('#usuario').val();

   

            $.ajax({
                url: 'ajax.php?c=retiro&f=filtra',
                type: 'POST',
                dataType: 'json',
                data: {desde: desde,
                       hasta : hasta,
                       user : user
                },
            })
            .done(function(data) {
                //console.log(data.rows[0]);
                $('.trtablita').empty()
                $.each(data.rows, function(index, val) {
                    var cantidad = val.cantidad;
                    cantidad = cantidad * 1;
                    $('#tablita tr:last').after('<tr class="trtablita">'+
                        '<td>'+val.id+'</td>'+
                        '<td>$'+cantidad.toFixed(2)+'</td>'+
                        '<td>'+val.concepto+'</td>'+
                        '<td>'+val.usuario+'</td>'+
                        '<td>'+val.fecha+'</td>'+
                        '<td><button class="btn btn-default" onclick="reimprime('+val.id+')"><i class="fa fa-print" aria-hidden="true"></i></button></td>'+
                        '</tr>');
                }); 
               // datatables();

            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            }); 


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



















