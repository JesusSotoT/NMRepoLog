$(function()
 {
        $('body').bind("keyup", function(evt)
        {
            if (event.ctrlKey==1)
            {
                if(evt.keyCode == 80)
                {
                    $("#pagar").click()
                }
            }        
        });
 });

function facturas(t)
{
    console.log(t.value)
    $.post('ajax.php?c=cuentas&f=listaFacturas', 
        {
            idPrv: $("#listaProveedores").val()
        }, 
        function(data) 
        {
            $("#trs_esp").html(data);
        });
}

function pagar(t)
{
    var chks = 0;
    var inputs = '';
    var split = '';
    $("input[type='checkbox']:checked").each(function()
        {
            split = $(this).attr('id');
            split = split.split('-');
            inputs += "<div class='col-xs-6 col-md-4'>"+$("#folio-"+split[1]).text()+"</div><div class='col-xs-6 col-md-6'><input type='text' class='pagos_lista' id='"+t+"-"+split[1]+"' value='"+$("#saldo-"+split[1]).attr('saldo')+"'></div>";
            $("#inputPagos").html(inputs);
            chks++;
        });
    
    if(chks)
    {
        $('.bs-pagos-modal-md').modal('show');
        $("#radio_pago1").click()
    }

}

function bloq_pagos(s)
{
    if(parseInt(s))
        $(".pagos_lista").attr("readonly",true)
    else
        $(".pagos_lista").removeAttr("readonly")
}

function cancelar_pagos()
{
    $('.bs-pagos-modal-md').modal('hide');
    $("#inputPagos").html('')
}

function guardar_pagos()
{
    alert("Aqui se guarda")
}
