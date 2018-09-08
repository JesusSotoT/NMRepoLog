<script> 

'Clave','Descripcion','Unidad','Vol. Tope','PU destajo', 'Importe', 'Vol anterior', 'Vol. Estimacion','Vol acumulado','Vol ejecutar','Imp. Estimacion'

        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_arbolest_destajistas.php',
                datatype: "json",
                colModel: [
                    { label: 'Destajista', name: 'Destajista', width: 255, sortable:false },
                    { label: 'Estimacion', name: 'Estimacion',  width: 70, sortable:false },
                    { label: 'Clave', name: 'clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                    { label: 'Unidad', name: 'unidad',  width: 40, sortable:false },
                    { label: 'Vol. Tope', name: 'vol_tope',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'PU destajo', name: 'vol_tope',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Importe', name: 'vol_tope',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Vol. Anterior', name: 'vol_tope',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Vol. Estimacion', name: 'vol_tope',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Vol. Acumulado', name: 'vol_tope',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Vol. Ejecutar', name: 'vol_tope',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { 
                        label: 'Importe', 
                        name: 'importe',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    }
                ],
                loadComplete: function() {
                    
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr#null").remove();

                 /*   x=0;
                    groups = $(this).jqGrid("getGridParam", "groupingView").groups;
                    $.each( groups, function( k, v ) {
                        if(v.idx==0){
                            $('.jq_arbolghead_0 td:eq('+x+') td').append(' <input type="button" value="Cancelar" style="cursor:pointer;" onclick="cancelReq(\''+v.displayValue+'\');" > ');
                            x++;
                        }
                    });
*/



                },
        loadonce:true,
        viewrecords: true,
        rowNum:10,
        rowList:[10,20],
        width: "1523",
        height: "300",
        sortname: 'reqis',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["Requisicion", "Solicito"],
                    groupColumnShow: [false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Requisicion: <b>{0}</b>",
                    "Solicito: <b>{0}</b>"
          ],
                    groupOrder: ["asc", "asc"],
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"hojarequis",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });
        });

    </script>


<div style="float:left; width:700px;">
  <div id="dtabla" style="float:left; width:700px; font-size:10px;">
    <table id="jq_arbol"></table>
    <div id="jqp_arbol"></div>
  </div>
</div>