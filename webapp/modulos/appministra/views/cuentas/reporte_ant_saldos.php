
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        $("#listaProveedores").select2({'width':'100%'});
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $('#f_cor').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
        $("#resultados").hide();
        Number.prototype.format = function() {
        return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    };
        
    });
    function generar_reporte()
    {
    //$("#ver_movs:checked").length
    var rango = $("#rango").prop('checked') ? 1 : 0;
        var sigue = 1;
        var listaProv = $("#listaProveedores").val();
    
        if(rango)
        {
            if(!listaProv[1])
            {
                sigue = 0;
                alert("Se necesitan 2 proveedores cuando se trata de rango")
            }
        }
        
        if(sigue)
        {
           $("#resultados").show();

            $.post('ajax.php?c=reportes_cuentas&f=ant_saldos_reporte', 
            {
                idPrvs: $("#listaProveedores").val(),
                rango: $("#rango:checked").length,
                f_cor: $("#f_cor").val(),
                imp: $("#imp").val()
            }, 
            function(data) 
            {
                $("#res_rep").html(data);
                if(parseInt($(".imp:checked").val()))
                {
                    $(".linea_fac").show()
                }
                else
                {
                    $(".linea_fac").hide()
                }

                var saldos = saldosSin = s1_15 = s16_30 = s31_45 = sm45 = 0;
                $(".linea_final").each(function(index)
                {
                    saldos += parseFloat($("td:nth-child(3)",this).attr('cantidad'))
                    saldosSin += parseFloat($("td:nth-child(4)",this).attr('cantidad'))
                    s1_15 += parseFloat($("td:nth-child(5)",this).attr('cantidad'))
                    s16_30 += parseFloat($("td:nth-child(6)",this).attr('cantidad'))
                    s31_45 += parseFloat($("td:nth-child(7)",this).attr('cantidad'))
                    sm45 += parseFloat($("td:nth-child(8)",this).attr('cantidad'))
                });
                $("#saldoGn").text("$ "+saldos.format())
                $("#saldoSinGn").text("$ "+saldosSin.format())
                $("#saldoSinPc").text((saldosSin/saldos*100).format()+"%")

                $("#s1_15Gn").text("$ "+s1_15.format())
                $("#s1_15Pc").text((s1_15/saldos*100).format()+"%")

                $("#s16_30Gn").text("$ "+s16_30.format())
                $("#s16_30Pc").text((s16_30/saldos*100).format()+"%")

                $("#s31_45Gn").text("$ "+s31_45.format())
                $("#s31_45Pc").text((s31_45/saldos*100).format()+"%")
                
                $("#sm45Gn").text("$ "+sm45.format())
                $("#sm45Pc").text((sm45/saldos*100).format()+"%")

                $(".linea_porc").hide();


                var anchor  = '#resultados';
                        $('html, body').stop().animate({
                            scrollTop: jQuery(anchor).offset().top
                        }, 1000);
                        return false;
            });
        }
    }
    </script>
    <style>
.row
{
    margin-bottom:20px;
}
.container
{
    margin-top:20px;
}

.linea_prov
{
    background-color:#A4A4A4; 
}
.linea_final
{
    background-color:white; 
}

.linea_general,.linea_porc
{
    background-color:#D8D8D8; 
}
</style>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Antigüedad de saldos Proveedores</h3></div>
    </div>
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Proveedor(es):
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listaProveedores' multiple>
                <option value='0'>Todos</option>
                <?php
                    while($l = $listaProveedores->fetch_assoc())
                        echo "<option value='".$l['idPrv']."'>(".$l['codigo'].") ".$l['razon_social']."</option>";
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Es Rango
        </div>
        <div class='col-xs-12 col-md-5'>
            <input type='checkbox' id='rango' value='1'>
        </div>
    </div> 

    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Fecha de Corte
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='text' id='f_cor' class='form-control'>
        </div>
    </div>   

    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Imprimir
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='radio' id='imp' value='0' name='imp' checked="true" class='imp'> Global<br />
            <input type='radio' id='imp2' value='1' name='imp' class='imp'> Detalle
        </div>
    </div>    
    
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
        </div>
        <div class='col-xs-12 col-md-3'>
            <button id='generar' onclick="generar_reporte()" class='btn btn-primary'>Generar</button>
        </div>
    </div>    
</div>

<div class="container well" id='resultados'>
    <div class="row">
        <div class="col-xs-12 col-md-12 table-responsive">
            <table class="table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr><th>Folio(s)</th><th>Fecha Documento</th><th>Fecha Vencimiento</th><th>Dias Vencidos</th><th>Saldo</th><th>Sin vencer</th><th>1-15 días</th><th>16-30 días</th><th>31-45 días</th><th>+ de 45 días</th></tr>
                </thead>
                <tbody id='res_rep'>
                                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

