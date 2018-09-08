<?php
///chais


///chais
?>
<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_tentrada_all.php',
                datatype: "json",
                colModel: [

               // d.codigo, d.descripcion, d.unidtext, d.pu_destajo, e.vol_tope*d.pu_destajo as total, c.vol_anterior, c.vol_est

                  { label: 'Clave', name: 'clave',  width: 10, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 10, sortable:false },
                 
                     { label: 'Cantidad', name: 'cantidad',  width: 10, sortable:false },
                    { label: 'Unidad', name: 'Unidad',  width: 10, sortable:false },
                   
                    { label: 'Precio', name: 'Precio',  width: 10, sortable:false },
                    { label: 'Importe', name: 'Importe',  width: 10, sortable:false,sorttype:"float", formatter:"number",  summaryTpl: "<b>{0}</b>", summaryType:'sum' },

                      

                     { label: 'Estimacion', name: 'Estimacion',  width: 35, sortable:false },
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    //$(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    //$(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();

                },
        loadonce:true,
        viewrecords: true,
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        autowidth: true,
        height: "300",
        sortname: 'a.fecha_solicito',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["Estimacion"],
                    groupColumnShow: [false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Estimacion: <b>{0}</b>"
          ],
                    groupOrder: ["desc", "desc"],
                    groupSummary : [false, true],
          groupSummaryPos: [],
                    groupCollapse: false
                }
            });

            
            jQuery("#jq_arbol").jqGrid('navGrid',"#jqp_arbol",
                {
                  edit:false,
                  add:false,
                  del:false,
                  search:false
                },
                {},
                {beforeShowForm: function(form){ 
                    $('.FormGrid input').css('width','270px');
                    $('.FormGrid select').css('width','278px');
                    $('.FormGrid textarea').css('width','274px');
                    $('.FormGrid textarea').css('height','80px');
                    $('#unidad').numeric(); 
                    $('#precio_costo').numeric(); 
                    $('#precio_venta').numeric();
                  },
                  closeAfterAdd:true,
                  width: 600
                },
                {width: 480}, // settings for delete
                {} // search options
              ).jqGrid('navButtonAdd', '#jqp_arbol', {
                caption: "Exportar Excel",
                buttonicon: "ui-icon-export",
                  onClickButton: function() {
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"hojarequisComp_dest",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });
        });

    </script>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancelar traspaso</h4>
      </div>
      <div class="modal-body">
        Justificacion:<br>
        <textarea rows="4" cols="50" id='jus'></textarea>
        <input type='hidden' id='ide'>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      
<input type="button" value="Cancelar traspaso" style="cursor:pointer" onclick="autorizarestAll('tent',-1,2);">

      </div>
    </div>

     </div>
    </div>

    <script>
$('#myModal').on('show.bs.modal', function(e) {
  $('#ide').val(e.relatedTarget.dataset.eid);
});
</script>

<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Entrada de almacenes</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_arbol"></table>
                <div id="jqp_arbol"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

</body>
