
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        $("#listaProveedores,#formaPago").select2({'width':'100%'});
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $('#f_ini,#f_fin').datepicker({
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

            $.post('ajax.php?c=reportes_cuentas&f=aux_movimientos_reporte', 
            {
                idPrvs: $("#listaProveedores").val(),
                rango: $("#rango:checked").length,
                f_ini: $("#f_ini").val(),
                f_fin: $("#f_fin").val(),
                formaPago: $("#formaPago").val()
            }, 
            function(data) 
            {
                $("#res_rep").html(data);

                var cargos = abonos = saldos = 0;
                $(".cargo").each(function(index)
                {
                    cargos += parseFloat($(this).attr('cantidad'))
                });
                $("#suma_cargos").html("$ "+cargos.format()).attr('cantidad',cargos);

                $(".abono").each(function(index)
                {
                    abonos += parseFloat($(this).attr('cantidad'))
                });
                $("#suma_abonos").html("$ "+abonos.format()).attr('cantidad',abonos);

                saldos = parseFloat($("#suma_cargos").attr("cantidad")) - parseFloat($("#suma_abonos").attr("cantidad"));
                $("#suma_saldos").html("$ "+saldos.format())

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

.linea_fac
{
    background-color:#D8D8D8; 
}
</style>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Auxiliar de Movimientos Cuentas por Pagar</h3></div>
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
            Fecha Inicial
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='text' id='f_ini' class='form-control'>
        </div>
    </div> 
     <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Fecha Final
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='text' id='f_fin' class='form-control'>
        </div>
    </div>    
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Forma de Pago
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='formaPago'>
            <option value='0'>Todos</option>
                <?php
                    while($l = $listaFormasPago->fetch_assoc())
                        echo "<option value='".$l['idFormapago']."'>(".$l['claveSat'].") ".$l['nombre']."</option>";
                ?>
            </select>
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
                    <tr><th>Fecha Documento</th><th>Folio(s)</th><th>Concepto</th><th>Cargos</th><th>Abonos</th><th>Saldo</th><th>Fecha Vencimiento</th></tr>
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

