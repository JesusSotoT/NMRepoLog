var pendiente = {
    init: function()
    {
        $('#btnGenerar').click(function() {
            var ids = $('#ventas').val();
            pendiente.generar(ids);
        });
    },
    generar: function(ids)
    {
        $('#btnGenerar').unbind('click');
        $.ajax({
            url: 'ajax.php?c=caja&f=simulaFactura',
            type: 'POST',
            dataType: 'json',
            data: {
                ids: ids
            }
        })
                .done(function(resp) {
                    alert('Ventas enviadas a facturacion.');
                    window.location.reload();
                })
                .fail(function() {
                    console.log("error");
                });

    }
}