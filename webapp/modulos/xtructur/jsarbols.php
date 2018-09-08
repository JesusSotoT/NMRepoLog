<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_jsarbols.php',
                datatype: "json",
                colModel: [
                    { label: 'Agrupador', name: 'Agrupador', width: 255, sortable:false },
                    { label: 'Area', name: 'Area',  width: 70, sortable:false },
                    { label: 'Especialidad', name: 'Especialidad', width: 150, sortable:false },
                    { label: 'Partida', name: 'Partida', width: 150, sortable:false },
                    { label: ' ', name: ' ', width: 1429, sortable:false }
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
                sortname: 'a.id',
                pager: "#jqp_arbol",
                grouping: true,
                groupingView: {
                    groupField: ["Agrupador", "Area","Especialidad","Partida"],
                    groupColumnShow: [false, false, false, false],
                    groupText: [
                        "Agrupador: <b>{0}</b>",
                        "Area: <b>{0}</b>",
                        "Especialidad: <b>{0}</b>",
                        "Partida: <b>{0}</b>"
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
              <div class="navbar-brand" style="color:#333;">Arbol de planeacion</div>
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
        <div class="row">

            <div class="col-sm-3">
                <input class="btn btn-primary btnMenu" type="button" value="Mostrar recursos" onclick="arbolcon();" style="cursor:pointer;">
            </div>
        </div>
      </div>
    </div>

</body>



