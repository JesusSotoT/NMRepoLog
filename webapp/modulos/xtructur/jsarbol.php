<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_jsarbol.php',
                datatype: "json",
                colModel: [
                    { label: 'Agrupador', name: 'Agrupador', width: 255, sortable:false },
                    { label: 'Area', name: 'Area',  width: 70, sortable:false },
                    { label: 'Especialidad', name: 'Especialidad', width: 150, sortable:false },
                    { label: 'Partida', name: 'Partida', width: 150, sortable:false },
                    { label: 'Naturaleza', name: 'naturaleza', width: 150, sortable:false },
                    { label: 'Clave', name: 'codigo', width: 150, sortable:false },
                    { label: 'Descripcion', name: 'descripcion', width: 150, sortable:false },
                    { label: 'U.M', name: 'um', width: 90, sortable:false },
                    { label: 'Cantidad', name: 'unidad', width: 150,sorttype:"float", formatter:"number", sortable:false },
                    { label: 'Precio unitario', name: 'precio_costo', width: 150,sorttype:"float", formatter:"number", sortable:false },
                    { label: 'Importe', name: 'importe', width: 150, sorttype:"float", formatter:"number", sortable:false, summaryType:'sum', search : false, summaryRound: 2 },
                    { label: 'PU Destajo', name: 'pdes', width: 150, sortable:false, sorttype:"float", formatter:"number",summaryType:'sum',search : false },
                    { label: 'PU Subcontrato', name: 'psub', width: 150, sortable:false, sorttype:"float", formatter:"number",summaryType:'sum',search : false },
                    { label: 'Vol. Tope', name: 'vtope',  width: 160, sortable:false,search : false}
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();

    $( ".nmn" ).each(function( index ) {
        $(this).text('$'+number_format($(this).text(),2));

});
                    





                },
                loadonce:false,
                viewrecords: true,
                rowNum:1000000,
                rowList: [], 
                pgbuttons: false,
                pgtext: null,  
                autowidth: true,
                height: "300",
                sortname: 'id',
                pager: "#jqp_arbol",
                grouping: true,
                groupingView: {
                    groupField: ["Agrupador", "Area","Especialidad","Partida"],
                    groupColumnShow: [false, false, false, false],
                    groupText: [
                    "Agrupador: <b>{0} - Importe Total <span class='nmn'>{importe} </span></b>",
                        "Area: <b>{0} - Importe Total <span class='nmn'>{importe} </span></b>",
                        "Especialidad: <b>{0} - Importe Total <span class='nmn'>{importe} </span></b>",
                        "Partida: <b>{0} - Importe Total <span class='nmn'>{importe} </span></b>"
                    ],
                    groupOrder: ["asc", "asc"],
                    groupSummary : [false, false, false, true],
                    groupSummaryPos: [],
                    groupCollapse: false
                }
            });
          
            jQuery("#jq_arbol").jqGrid('navGrid',"#jqp_arbol",
                {
                  edit:false,
                  add:false,
                  del:false,
                  search:true
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"Arbolp",formato:"excel"});
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
            <div class="col-sm-12">
                <button class="btn btn-primary" onclick="guardarvt()"> Guardar volumenes tope</button>
                <button class="btn btn-warning" onclick="arbolsin()"> Ocultar recursos</button>
                
            </div>
        </div>
      </div>
    </div>

</body>



