
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        $("#listaVendedores,#tipo_doc,#clientes").select2({'width':'100%'});
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $('#f_ini,#f_fin').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
        $("#resultados").hide();
        Number.prototype.format = function() {
        return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
         };

        $(".caracs").hide();
        $(".clasifs").show();
        
    });
    function generar_reporte()
    {
    //$("#ver_movs:checked").length
       $("#resultados").show();

        $.post('ajax.php?c=reportes_ventas&f=cobranza_vendedor_reporte', 
        {
            idVds: $("#listaVendedores").val(),
            rango: $("#rango:checked").length,
            f_ini: $("#f_ini").val(),
            f_fin: $("#f_fin").val(),
            tipo_doc: $("#tipo_doc").val(),
            cliente: $("#clientes").val(),
            status_doc: $("#status_doc").val()
        }, 
        function(data) 
        {

            $("#res_rep").html(data);
           

            var anchor  = '#resultados';
                    $('html, body').stop().animate({
                        scrollTop: jQuery(anchor).offset().top
                    }, 1000);
                    return false;
            
        });         
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
        <div class="col-xs-12 col-md-12"><h3>Cobranza por vendedor.</h3></div>
    </div>
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-1'>
            Fecha Inicial
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='text' id='f_ini' class='form-control'>
        </div>
         <div class='col-xs-12 col-md-2'>
            Tipo de Documento
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='tipo_doc'>
                <option value='0'>Todos</option>
                <option value='F'>Factura</option>
                <option value='C'>Ticket</option>
            </select>
        </div>
    </div> 
     <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-1'>
            Fecha Final
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='text' id='f_fin' class='form-control'>
        </div>
         <div class='col-xs-12 col-md-2'>
            Status
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='status_doc' class='form-control'>
                <option value='0'>Todos</option>
                <option value='1'>Cobrada</option>
                <option value='2'>Pendiente de cobro</option>
            </select>
        </div>
    </div> 
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-1'>
            Vendedor(es):
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listaVendedores' multiple>
                <option value='0'>Todos</option>
                <?php
                    while($l = $listaVendedores->fetch_assoc())
                        echo "<option value='".$l['idEmpleado']."'>".$l['Usuario']."</option>";
                ?>
            </select>
        </div>
        
    </div>

    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-1'>
            Es Rango
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='checkbox' id='rango' value='1'>
        </div>
    </div>
    <div class='row'>
        <div class='col-xs-12 col-md-2 col-md-offset-1'>
            Clientes:
        </div>
         <div class='col-xs-12 col-md-3'>
            <select id='clientes'>
                <option value='0'>Todos</option>
                <?php
                while($l = $listaClientes->fetch_assoc())
                    echo "<option value='".$l['id']."'>".$l['nombre']."</option>";
                ?>
            </select>
        </div>

    </div>
   
    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-4'>
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
                    <tr><th>Fecha</th><th>Serie</th><th>Folio</th><th>Importe</th><th>Cobro</th><th>Fecha abono</th><th>Saldo</th></tr>
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

