
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        $("#listaProductos,#listaSucursales,#listaAlmacenes,#listatipo").select2({'width':'100%'});
        $("#divseries, #divlotped, #divcaducos").hide();
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $('#f_ini,#f_fin').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
        $("#resultados").hide();
    
        
    });

    function listaAlmacenesSuc()
	{
	     $.post('ajax.php?c=inventarios&f=listaAlmacenesSuc', 
	            {
	                idSuc : $("#listaSucursales").val()
	            }, 
	            function(data)
	            {
	                $("#listaAlmacenes").html(data).trigger('change');
	                
	            });
	}

    function generar_reporte()
    {   
        var opc = $("#listaOpciones").val();
        if($("#f_ini").val() && $("#f_fin").val())
        {
            $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                            url: 'ajax.php?c=inventarios&f=slp2',
                            type: 'post',
                            dataType: 'json',
                            data: {f_ini:$("#f_ini").val(),
                                   f_fin:$("#f_fin").val(),
                                   idprod:$("#listaProductos").val(),
                                   opc:opc,
                                   tipoM:$("#listatipo").val(),
                                   id_alm:$("#listaAlmacenes").val()},

                    })
            .done(function(data) {
                if(opc==1){
                    $("#modalMensajes").modal('show');
                    $("#divlotped, #divcaducos").hide();
                    var table = $('#table_series').DataTable({dom: 'Bfrtip',
                                                                buttons: [
                                                                            {
                                                                                extend: 'excel',
                                                                                text: 'Exportar'                                                                           
                                                                            },
                                                                            {
                                                                                extend: 'print',
                                                                                text: 'Imprimir'
                                                                            },                                                                                                                                     
                                                                        ],
                                                                sort:false,
                                                                destroy: true,
                                                                "columnDefs": [
                                                                    {
                                                                        "targets": [ 0 ],
                                                                        "visible": false,
                                                                        "searchable": true
                                                                    }
                                                                ],
                                                                paginate: false,
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
                                                                         }});
                        table.clear().draw();
                    var x ='';
                    var FolioE ='';
                    var FolioS ='';
                    var cliente ='';

                    $.each(data, function(index, val) {
                        if(val.FolioE == '0' || val.FolioE == null){ FolioE = '' }else{ FolioE = val.FolioE }
                        if(val.FolioS == '0' || val.FolioE == null){ FolioS = '' }else{ FolioS = val.FolioS }
                        if(val.cliente == null){ cliente = '' }else{ cliente = val.cliente }

                        if(val.aux == 'H'){
                            x ='<tr style="background-color:#d3d3d3">'+                                                                        
                                        '<td></td>'+
                                        '<td align="center" colspan = "10"><b>'+val.Producto+'</b></td>'+                                    
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                    '</tr>';  
                            table.row.add($(x)).draw();
                        }
                        if(val.aux == 'B'){
                            x ='<tr>'+                                    
                                        '<td align="left">'+val.Producto+'</td>'+
                                        '<td align="left">'+val.serie+'</td>'+
                                        '<td align="left">'+val.estatus+'</td>'+
                                        '<td align="left">'+val.concepto+'</td>'+                                    
                                        '<td align="left">'+val.AlmacenE+'</td>'+
                                        '<td align="left">'+val.AlmacenS+'</td>'+
                                        '<td align="right">'+val.fechaE+'</td>'+
                                        '<td> <a href="../../modulos/appministra/index.php?c=compras&f=ordenes&id_oc='+FolioE+'&v=1" target="_blank">'+FolioE+'</a></td>'+
                                        '<td align="right">'+val.fechaS+'</td>'+
                                        '<td> <a href="../../modulos/appministra/index.php?c=ventas&f=ordenes&id_oventa='+FolioS+'&v=1" target="_blank">'+FolioS+'</a></td>'+
                                        '<td align="right">'+cliente+'</td>'+
                                    '</tr>';  
                            table.row.add($(x)).draw(); 
                        }

                    });
                    $("#modalMensajes").modal('hide');
                    $("#divseries").show();
                }
                if(opc==2 || opc==3){
                    $("#modalMensajes").modal('show');
                    $("#divseries, #divcaducos").hide();              
                    var table = $('#table_lotesPedi').DataTable({dom: 'Bfrtip',
                                                                buttons: [
                                                                            {
                                                                                extend: 'excel',
                                                                                text: 'Exportar'                                                                           
                                                                            },
                                                                            {
                                                                                extend: 'print',
                                                                                text: 'Imprimir'
                                                                            },                                                                                                                                     
                                                                        ],
                                                                sort:false,
                                                                destroy: true,
                                                                "columnDefs": [
                                                                    {
                                                                        "targets": [ 0 ],
                                                                        "visible": false,
                                                                        "searchable": true
                                                                    }
                                                                ],
                                                                paginate: false,
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
                                                                         }});
                        table.clear().draw();
                    var x ='';
                    $.each(data, function(index, val) {
                       if(val.aux == 'H'){
                            x ='<tr style="background-color:#d3d3d3">'+                                                                        
                                        '<td></td>'+
                                        '<td align="center" colspan = "8">'+val.Producto+'</td>'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                    '</tr>';  
                            table.row.add($(x)).draw();
                        }
                        if(val.aux == 'B'){
                            x ='<tr>'+                                    
                                        '<td align="left">'+val.Producto+'</td>'+ 
                                        '<td align="left">'+val.concepto+'</td>'+                                   
                                        '<td align="left">'+val.AlmacenS+'</td>'+
                                        '<td align="left">'+val.AlmacenE+'</td>'+
                                        '<td align="right">'+val.fechaE+'</td>'+
                                        '<td align="right">'+val.FolioE+'</td>'+
                                        '<td align="right">'+val.fechaS+'</td>'+
                                        '<td align="right">'+val.FolioS+'</td>'+
                                        '<td align="right">'+val.cantidad+'</td>'+                                    
                                    '</tr>';  
                            table.row.add($(x)).draw(); 
                        }
                    });
                    $("#modalMensajes").modal('hide');
                    $("#divlotped").show();
                }
                if(opc==4){
                    $("#modalMensajes").modal('show');
                    $("#divseries, #divlotped").hide();
                    var table = $('#table_caducos').DataTable({dom: 'Bfrtip',
                                                                buttons: [
                                                                            {
                                                                                extend: 'excel',
                                                                                text: 'Exportar'                                                                           
                                                                            },
                                                                            {
                                                                                extend: 'print',
                                                                                text: 'Imprimir'
                                                                            },                                                                                                                                     
                                                                        ],
                                                                sort:false,
                                                                destroy: true,
                                                                paginate: false,
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
                                                                         }});
                        table.clear().draw();
                    var x ='';
                     $.each(data, function(index, val) {
                       if(val.aux == 'H'){
                            x ='<tr style="background-color:#d3d3d3">'+                                                                        
                                        '<td align="center" colspan = "6">'+val.almacen+'</td>'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                        '<td style="display: none;">'+
                                    '</tr>';  
                            table.row.add($(x)).draw();
                        }
                        if(val.aux == 'B'){
                            x ='<tr>'+                                    
                                        '<td align="left">'+val.codigo+'</td>'+                                   
                                        '<td align="left">'+val.nombre+'</td>'+
                                        '<td align="left">'+val.no_lote+'</td>'+
                                        '<td align="right">'+val.fecha_caducidad+'</td>'+
                                        '<td align="right">'+val.fecha_fabricacion+'</td>'+
                                        '<td align="right">'+val.disponibles+'</td>'+                                  
                                    '</tr>';  
                            table.row.add($(x)).draw(); 
                        }
                        if(val.aux == 'F1'){
                            x ='<tr style="background-color:#d3d3d3">'+                                    
                                        '<td align="left"></td>'+                                   
                                        '<td align="left"></td>'+
                                        '<td align="left"></td>'+
                                        '<td align="right"></td>'+
                                        '<td align="right">Total en: '+val.Totalen+'</td>'+
                                        '<td align="right">'+val.totalAlmacen+'</td>'+                                  
                                    '</tr>';  
                            table.row.add($(x)).draw(); 
                        }
                        if(val.aux == 'F2'){
                            x ='<tr style="background-color:#d3d3d3">'+                                    
                                        '<td align="left"></td>'+                                   
                                        '<td align="left"></td>'+
                                        '<td align="left"></td>'+
                                        '<td align="right"></td>'+
                                        '<td align="right">Total en todos los almacenes</td>'+
                                        '<td align="right">'+val.Totaltodos+'</td>'+                                  
                                    '</tr>';  
                            table.row.add($(x)).draw(); 
                        }
                    });
                    $("#modalMensajes").modal('hide');
                    $("#divcaducos").show();
                }
            });
        }
        else
            alert("Agregue un rango de fechas.")
       
       /* 
        if($("#f_ini").val() && $("#f_fin").val())
        {
        

             //$("#ver_movs:checked").length
           $("#resultados").show();

            $.post('ajax.php?c=inventarios&f=slp', 
            {
                f_ini   : $("#f_ini").val(),
                f_fin   : $("#f_fin").val(),
                idprod  : $("#listaProductos").val(),
                opc     : $("#listaOpciones").val(),
                id_alm  : $("#listaAlmacenes").val()
               // imp   : $(".imp:checked").val()
            }, 
            function(data) 
            {
                //alert(data)
                $("#res_rep").html(data);
                var anchor  = '#resultados';
                        $('html, body').stop().animate({
                            scrollTop: jQuery(anchor).offset().top
                        }, 1000);
                        return false;
                
            });
       
        }
        else
            alert("Agregue un rango de fechas.")
         */
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

.titulo
{
    background-color: #D8D8D8;
}

.prod_row
{
    background-color: #A4A4A4;   
}
</style>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Reporte de Series, pedimentos, lotes, productos caducos</h3></div>
    </div>

    <div class="row">
        <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Fecha Inicial
        </div>
       
        <div class='col-xs-12 col-md-3'>
            <input type='text' id='f_ini' class='form-control'>
        </div>
        <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Fecha Final
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='text' id='f_fin' class='form-control'>
        </div>

        
    </div> 
     <div class="row">
        <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Sucursales
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listaSucursales' onchange='listaAlmacenesSuc()'>
                <?php
                    echo $ls;
                ?>
            </select>
        </div>
         <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Almacenes
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listaAlmacenes'>
                <option value='0'>Todos</option>
            </select>
        </div>
    </div>    
    <div class="row">
        <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Productos
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listaProductos'>
                <?php
                    echo $lp;
                ?>
            </select>
        </div>
        <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Tipo de movimiento
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listatipo'>
                <option value='3'>Todos</option>
                <option value='1'>Entrada/Compra</option>
                <option value='0'>Salida/Venta</option>
                <option value='2'>Traspaso</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Opciones
        </div>
        <div class='col-xs-12 col-md-3'>
            <select id='listaOpciones' class='form-control'>
               <option value='1'>Series</option>
               <option value='2'>Lotes</option>
               <option value='3'>Pedimentos</option>
               <option value='4'>Productos Caducos</option>
            </select>
        </div>
    </div>

    <!--<div class="row">
        <div class='col-xs-12 col-md-1 col-md-offset-1'>
            Imprimir
        </div>
        <div class='col-xs-12 col-md-3'>
            <input type='radio' name='imp' class='imp' value='0' checked>Todos<br />
            <input type='radio' name='imp' class='imp' value='1'>Solo con movimientos
        </div>
    </div>-->

    <div class="row">
        <div class='col-xs-12 col-md-2 col-md-offset-3'>
        </div>
        <div class='col-xs-12 col-md-3'>
            <button id='generar' onclick="generar_reporte()" class='btn btn-primary'>Generar</button>
        </div>
    </div>    
</div>

<!--
<div class="container well" id='resultados'>
    <div class="row">
        <div class="col-xs-12 col-md-12 table-responsive">
            <table class="table table-bordered" cellspacing="0" width="100%" id='res_rep'>
               
            </table>
        </div>
    </div>
</div>
-->

<div class="container well" id="divseries">
    <div>
    <table id="table_series" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th td width="30">Producto</th>
                    <th td width="30">N-º de Serie</th>
                    <th td width="30">Estado</th>
                    <th td width="30">Tipo de movimiento</th>
                    <th td width="30">Almacen Origen</th>
                    <th td width="30">Almacen Destino</th>
                    <th td width="30">Fecha entrada/compra</th>
                    <th td width="30">Folio entrada/compra</th>
                    <th td width="30">Fecha salida/venta</th>
                    <th td width="30">Folio salida/venta</th>
                    <th td width="30">Cliente</th>
                  </tr>
                </thead>

              </table>
    </div>
</div>

<div class="container well" id="divlotped">
    <div>
        <table id="table_lotesPedi" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th td width="30">Producto</th>
                        <th td width="30">Tipo movimiento</th>
                        <th td width="30">Almacen Origen</th>
                        <th td width="30">Almacen Destino</th>
                        <th td width="30">Fecha entrada</th>
                        <th td width="30">Folio entrada</th>
                        <th td width="30">Fecha salida</th>
                        <th td width="30">Folio salida</th>
                        <th td width="30">Cantidad</th>
                      </tr>
                    </thead>

        </table>
    </div>
</div>

<div class="container well" id="divcaducos">
    <div>
        <table id="table_caducos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th td width="30">Codigo</th>
                        <th td width="30">Producto</th>
                        <th td width="30">Lote</th>
                        <th td width="30">Caducidad</th>
                        <th td width="30">Fabricacion</th>
                        <th td width="30">Disponibles</th>
                      </tr>
                    </thead>

        </table>
    </div>
</div>


    <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-default">
            <div align="center"><label id="lblMensajeEstado"></label></div>
            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span class="sr-only">Loading...</span>
             </div>
        </div>
        </div>
      </div>
    </div>
  </div>
 
<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />


<!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

<!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

<!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
