
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        $("#listaClientes").select2({'width':'100%'});
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $('#f_ini,#f_fin').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
        $("#resultados").hide();
        
    });
    function generar_reporte()
    {
    //$("#ver_movs:checked").length
        var rango = $("#rango").prop('checked') ? 1 : 0;
        var sigue = 1;
        var listaProv = $("#listaClientes").val();
    
        if(rango)
        {
            if(!listaProv[1])
            {
                sigue = 0;
                alert("Se necesitan 2 clientes cuando se trata de rango")
            }
        }
        
        if(sigue)
        {
           $("#resultados").show();

            $.post('ajax.php?c=reportes_cuentas&f=generar_reporte_cobrar', 
            {
                ids: $("#listaClientes").val(),
                rango: $("#rango:checked").length,
                f_ini: $("#f_ini").val(),
                f_fin: $("#f_fin").val(),
                todos_cli: $(".imprimir:checked").val()
            }, 
            function(data) 
            {
                $("#res_rep").html(data);
                if($("#ver_movs:checked").length)
                    $("#movimientos").show()
                else
                    $(".movimientos").hide()

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

.linea_cli
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
        <div class="col-xs-12 col-md-12"><h3>Resumen por de Saldos por Clientes.</h3></div>
    </div>
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Cliente(es):
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listaClientes' multiple>
                <option value='0'>Todos</option>
                <?php
                    while($l = $listaClientes->fetch_assoc())
                        echo "<option value='".$l['id']."'>(".$l['codigo'].") ".$l['nombre']."</option>";
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
            Imprimir Clientes
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='radio' id='r1' value='0' name='imprimir' class='imprimir' checked> Todos<br /><input type='radio' id='r2' value='1' name='imprimir' class='imprimir'> Solo con movimientos
        </div>
    </div>    

    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
            Imprimir Movimientos
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='checkbox' id='ver_movs' checked="true">
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
                    <tr><th>Fecha Pago</th><th>Concepto</th><th>Importe</th><th>Saldo</th></tr>
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

