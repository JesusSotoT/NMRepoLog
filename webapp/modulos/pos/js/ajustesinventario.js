$(document).ready(function() {
    $('.verMovimientos')
        .click(function(event) {
            fecha = $(this).parent().parent().find(':nth-child(1)').html();
            $.ajax({
                url: 'ajax.php?c=ajustesInventario&f=movimientos',
                type: 'GET',
                dataType: 'json',
                data: {fecha: fecha},
            })
            .done(function(movimientos) {
                $('#tablaMovimientos').empty();
                $('#fechaAjuste').empty().append(fecha);
                $(movimientos).each(function(index, el) {
                    if(el.serie) {
                        //for (var i = 0; i < el.cantidad; i++) {
                            $('#tablaMovimientos')
                            .append(`
                            <tr>
                                <td>1</td>
                                <td>${el.nombre}</td>
                                <td>${el.serie ? el.serie : ""}</td>
                                <td>${el.lote ? el.lote : ""}</td>
                                <td>${el.id_almacen_origen ? el.id_almacen_origen : "" }</td>
                                <td>${el.id_almacen_destino ? el.id_almacen_destino : ""}</td>
                            </tr>
                            `);
                        //}
                    } else {
                        $('#tablaMovimientos')
                        .append(`
                        <tr>
                            <td>${el.cantidad}</td>
                            <td>${el.nombre}</td>
                            <td>${el.serie ? el.serie : ""}</td>
                            <td>${el.lote ? el.lote : ""}</td>
                            <td>${el.id_almacen_origen ? el.id_almacen_origen : ""}</td>
                            <td>${el.id_almacen_destino ? el.id_almacen_destino : ""}</td>
                        </tr>
                        `);
                    }
                    
                });
                $('#modalMovimientos').modal();
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        });
});