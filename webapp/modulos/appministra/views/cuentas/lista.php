<?php
if(!isset($_GET['moneda']))
    $_GET['moneda'] = 0;
?>
<!-- Modificaciones RC -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"> </script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<style>
th
{
    text-align:center;
}
</style>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
    	carga_lista(<?php echo $_REQUEST['t'];?>);
        Number.prototype.format = function() {
        return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
        };
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $("#lista_cli_prov").select2({'width':'100%'});
        $("#monedas").val(<?php echo $_REQUEST['moneda'];?>)
    });
    function carga_lista(t)
    {
    	var prov_cli,elementos;
    	if(!t)
        {
    		prov_cli = 'lista_cli';
            $("#clioprov").text('Cliente');
            $("#tit_saldo").text('cobrar')
            $("#titulo").text('Cuentas por cobrar')
            $("#agregar_cargo").text("Agregar cuenta por cobrar")
            $("#pc").text("Cliente")
            elementos = "clientes";
        }
    	else
        {
    		prov_cli = 'lista_prov';
            $("#clioprov").text('Proveedor');
            $("#tit_saldo").text('pagar')
            $("#titulo").text('Cuentas por pagar')
            $("#agregar_cargo").text("Agregar cuenta por pagar")
            $("#pc").text("Proveedor")
            elementos = "proveedores";
        }


    	$.post('ajax.php?c=cuentas&f='+prov_cli,
    		{
    			f_cor: $("#fecha_corte").val(),
                id_moneda: $("#monedas_input").val()
    		}, 
            function(data) 
            {
                if(!t)
                {

                    var datos_pars = jQuery.parseJSON(data);
                    datos = datos_pars.clientes_validos;
                    /* ===== MOD CHRIS - Clientes sin credito ===== */
                    cliente_no_validos = datos_pars.clientes_no_validos;
                    novolver = datos_pars.novolver;

                    total_cnv=Object.keys(cliente_no_validos).length;
                    if(novolver==0){
                        $('#clientesSD').html('');
                        if(total_cnv>0){
                            cad='<b>Clientes:</b><br><br>';
                            $.each(cliente_no_validos, function( i, v ) {
                                cad+='<div style="font-size:11px;margin:4px;">'+v+'</div>';

                            });
                            cad+='<br><p style="font-size: 11px; text-align: center; color: rgb(223, 64, 64);">Estos clientes tienen saldos pendientes pero no se mostraran en tus cuentas por cobrar, es necesario que configures a tus clientes dentro del catalogo.</p>';
                            $('#clientesSD').html(cad);
                            $('#delmodal').modal('show');
                        }
                    }
                    /* ===== FIN MOD ===== */
                }
                else
                    var datos = jQuery.parseJSON(data);

                var total_saldos=0;
                $('#tabla-data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                    language: {
                        search: "Buscar:",
                        lengthMenu:"Mostrar _MENU_ elementos",
                        zeroRecords: "No hay coincidencias.",
                        infoEmpty: "No hay coincidencias que mostrar.",
                        infoFiltered: "",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ "+elementos,
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     },
                     "order": [[ 0, "asc" ]],
                     data:datos,
                     columns: [
                        { data: 'prov' },
                        { data: 'retrasado' },
                        { data: '0-30' },
                        { data: '31-60' },
                        { data: '61-90' },
                        { data: '91omas' },
                        { data: 'estatus' },
                        { data: 'total' }
                    ]
                });
                //console.log(data)
                for(i=0;i<=datos.length-1;i++)
                {
                    if(datos[i].actual_im != null)
                        total_saldos += parseFloat(datos[i].actual_im);
                }
                
                $("#total_saldos").val("$ "+total_saldos.format());
                $(".dt-buttons,#tabla-data_filter").css("margin-top","14px")
                var retrasado = s30 = s60 = s90 = s91 = 0;
                cont = 0;
                $("#tabla-data tr").each(function(index)
                {
                    if(cont)
                    {
                        retrasado += parseFloat($('td:nth-child(2) span', this).attr('cantidad'));
                        s30 += parseFloat($('td:nth-child(3) span', this).attr('cantidad'));
                        s60 += parseFloat($('td:nth-child(4) span', this).attr('cantidad'));
                        s90 += parseFloat($('td:nth-child(5) span', this).attr('cantidad'));
                        s91 += parseFloat($('td:nth-child(6) span', this).attr('cantidad'));
                    }
                    cont++;
                });
                $("#tit-mont").attr('title',retrasado.format())
                $("#tit-30").attr('title',s30.format())
                $("#tit-60").attr('title',s60.format())
                $("#tit-90").attr('title',s90.format())
                $("#tit-91").attr('title',s91.format())

                $("#tabla-data").before($("#agregar_cargo"));
                $("#cargando").hide();
                $("#pantalla").show();
            });
    }

    function redirecciona()
    {
        var pc = 'pagar';
        if(!parseInt(<?php echo $_REQUEST['t'];?>))
            pc = 'cobrar';
        window.location = "index.php?c=cuentas&f=cuentasx"+pc+"&id="+$("#lista_cli_prov").val();
    }
    function reload()
    {
        window.location = "index.php?c=cuentas&f=lista&t="+<?php echo $_REQUEST['t'];?>+"&moneda="+$("#monedas").val();
    }

    /* ===== MOD CHRIS - Clientes sin credito ===== */
    function novolverp(){
        $.ajax({
          url:"ajax.php?c=cuentas&f=novolver",
          type: 'POST'
        });
    }
    /* ===== FIN MOD ===== */
</script>
<div id='cargando' class='col-xs-12 col-md-12' style='margin-bottom:20px;text-align: center;font-size:20px;color:#337ab7;'><b>Cargando...</b></div>
<div class="container well" id='pantalla' style='display:none;'>
	<div class="row">
	    <div class="col-xs-12 col-md-12"><h3 id='titulo'>Cuentas por Cobrar</h3></div>
        <input type='hidden' value='<?php echo date('Y-m-d') ?>' id='fecha_corte'>
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-12 table-responsive">
            <button style='margin-top:14px;' class='btn btn-primary' id='agregar_cargo' onclick="$('.bs-lista-modal-sm').modal('show');"></button>
            <div id='saldos_div' class='col-xs-12 col-md-12' style='text-align:center;'>
                <div class='col-xs-12 col-md-3 col-md-offset-3'>
                    <span style='color:red;font-size:14px;'>Saldo total de cuentas por <span id='tit_saldo'></span></span><br /><input type='text' id='total_saldos' readonly="readonly" style='text-align:center;font-weight:bold;font-size:16px;' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-3'>
                    <span style='font-size:14px;'><b>Tipo de Moneda</b></span><br />
                    <input type='hidden' id='monedas_input' value='<?php echo $_GET['moneda'] ?>'>
                    <select id='monedas' class='form-control' onchange='reload()'>
                        <option value='0'>Todas</option>
                        <?php
                        while($mo = $monedas->fetch_assoc())
                            echo "<option value='".$mo['coin_id']."'>(".$mo['codigo'].") ".$mo['description']."</option>";
                        ?>
                    </select>
                </div>
            </div>
    			<table id='tabla-data' class='table table-striped table-bordered table-hover' width="100%" style='text-align:center;'>
    				<thead>
                        <tr style='text-align:center;'><th id='clioprov'></th><th id='tit-mont' title=''>Monto Retrasado</th><th id='tit-30' title=''>0 - 30 Días</th><th id='tit-60' title=''>31 - 60 Días</th><th id='tit-90' title=''>61 - 90 Días</th><th id='tit-91' title=''>91 + Días</th><th>Estatus</th><th>Total</th></tr>
                    </thead>
                    <tbody></tbody>
    			</table>
		</div>
	</div>
</div>

<!--
/* ===== MOD CHRIS - Clientes sin credito ===== */
-->
<div class="modal fade" id="delmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style='background-color:#FCF8E3;'>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:#8A6D3B;"><strong>Atencion!</strong> Clientes sin configuración de crédito</h4>
      </div>
      <div id="clientesSD" class="modal-body">

      </div>
      <label id='error'  style="margin-left:15px"></label>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="novolverp();">No volver a mostrar</button>
        <button type="button" id='enviarb' class="btn btn-default" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
    </div>
</div>
<!--
/* ===== FIN MOD ===== */
-->

<!-- Modal Nuevo Pago -->
<div class="modal fade bs-lista-modal-sm" tabindex="-1" role="dialog" aria-labelledby="lista" id="lista">
  <!-- Modal Dialog -->
  <div class="modal-dialog modal-sm">
    <!-- Modal container -->
    <div class="modal-content">
      <!-- Header modal -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Selecciona un <label id='pc'></label></h4>
      </div> <!-- //header modal -->

      <!-- Body modal -->
      <div class="modal-body well">
        <div class='row'>
          <div class='col-xs-12 col-md-12'>
            <select id='lista_cli_prov' onchange='redirecciona();'>
                <option value='0'>Ninguno</option>
                <?php
                while($lis = $lis_cli_prov->fetch_assoc())
                    echo "<option value='".$lis['id']."'>(".$lis['codigo'].") ".$lis['nombre']."</option>";
                ?>
            </select>
          </div>
        </div> 
        <!-- Modal footer -->
      </div> <!-- // Modal body -->
    </div> <!-- // Modal content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal nuevo pago -->