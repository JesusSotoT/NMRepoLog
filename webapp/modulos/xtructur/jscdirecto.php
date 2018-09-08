<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_jscdirecto.php',
                datatype: "json",
                colModel: [
                    { label: 'Costo_Proyecto', name: 'Costo_Proyecto', width: 150, sortable:false },
                    { label: 'Centro_Costo', name: 'Centro_Costo',  width: 80, sortable:false },
                    { label: 'Costo', name: 'Costo', width: 130, sortable:false },
                    { label: 'Cargo', name: 'Cargo', width: 170, sortable:false }
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
                grouping: false,
                groupingView: {
                    groupField: ["Costo_Proyecto", "Centro_Costo","Costo","Cargo"],
                    groupColumnShow: [false, false, false, true],
                    groupText: [
                        "Costo_Proyecto: <b>{0}</b>",
                        "Centro_Costo: <b>{0}</b>",
                        "Costo: <b>{0}</b>",
                        "Cargo: <b>{0}</b>"
                    ],
                    groupOrder: ["asc", "asc"],
                    groupSummary : [false, false, false, false],
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
                {width: 480},
                {}
              ).jqGrid('navButtonAdd', '#jqp_arbol', {
                caption: "Exportar Excel",
                buttonicon: "ui-icon-export",
                  onClickButton: function() {
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
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
              <div class="navbar-brand" style="color:#333;">Costo directo</div>
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

    

