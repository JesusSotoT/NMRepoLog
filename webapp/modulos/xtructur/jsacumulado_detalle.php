<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_jscacumulado_detalle.php',
                datatype: "json",
                colModel: [
                    { label: 'Costo_Proyecto', name: 'Costo_Proyecto', width: 150, sortable:false },
                    { label: 'Centro_Costo', name: 'Centro_Costo',  width: 80, sortable:false },
                    { label: 'Cuenta de costo', name: 'Costo', width: 130, sortable:false },
                    { label: 'Cuenta de cargo', name: 'Cargo', width: 140, sortable:false },
                    { label: 'Concepto', name: 'Concepto', width: 140, sortable:false },
                    { label: 'Info', name: 'Info', width: 170, sortable:false },
                    { label: 'Fecha', name: 'Fecha', width: 100, sortable:false },
                    { label: 'Monto', name: 'Monto', width: 50, sorttype:"float", formatter:"number", sortable:false, summaryType:'sum'
                    },
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
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
                sortname: 'cpid',
                pager: "#jqp_arbol",
                grouping: true,
                groupingView: {
                    groupField: ["Costo_Proyecto", "Centro_Costo","Costo","Cargo","Concepto"],
                    groupColumnShow: [false, false, false, true, true],
                    groupText: [
                    //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Costo_Proyecto: <b>{0}</b>",
                        "Centro de costo: <b>{0}</b>",
                        "Cuenta de costo: <b>{0}</b>",
                        "Cuenta de cargo: <b>{0}</b>",
                        "Concepto: <b>{0}</b>",
                        "Monto: <b>{0}</b>"
                    ],
                    groupOrder: ["asc", "asc"],
                    groupSummary : [false, false, false, false, true],
                    groupSummaryPos: [],
                    groupCollapse: true
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });
        });
    </script>

    <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Costo acumulado a detalle</div>
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



<div class="row">
    <div class="col-sm-3">
        <button id="btn_gra_acum" style="width:100%;" class="btn btn-primary btn-xm" onclick="graficar_acu('acumulado',<?php echo $idses_obra?>)"> Graficar Acumulado</button>
    </div>
</div>


