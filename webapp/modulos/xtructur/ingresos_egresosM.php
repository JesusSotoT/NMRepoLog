<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_ingresos_egresosM.php',
                datatype: "json",
                colModel: [
                    { label: 'Mes', name: 'Mes',  width: 70, sortable:false },
                    { 
                        label: 'Remesa autorizada', 
                        name: 'Remesa_autorizada',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Egresos acumulados', 
                        name: 'Egresos_acumulados',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Anticipos con iva', 
                        name: 'Anticipos_iva',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Estimaciones', 
                        name: 'Estimaciones',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Total ingresos', 
                        name: 'Total_ingresos',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Ingresos acumulados', 
                        name: 'Ingresos_acumulados',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Diferencia acumulada', 
                        name: 'Diferencia_acumulada',
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
                    $("tr #null").remove();
                    $('input[class^=quis__]').numeric();
                    $('input[class^=quis__]').keyup(function() {

                      var sema = 0;
                      $('input[class^=quis__]').each(function() {
                          sema += Number($(this).val());
                      });



                      ra=$('#rea').val();

                      queda=ra-sema;
                      $('#reaf').val(queda);

                      if( (ra*1)<sema ){
                        alert('La remesa autorizada no puede ser menor al importe capturado');
                        $(this).val(0);
                      }
                    });
                    //$('input[class^=quis_]').prop('disabled',true);
                    $('input.ccbox').on('change', function() {
                        id_oc = $(this).val();
                        //$('input[class^=quis_]').prop('disabled',true);

                        if(!$(this).is(':checked') ){
                          $('.quis_'+id_oc+'_').prop('disabled',true);
                        }else{
                          $('.quis_'+id_oc+'_').prop('disabled',false);
                        }
                       // $('input.ccbox').not(this).prop('checked', false);  
                        
                      
                    });

                },

        cellEdit: true,
        loadonce:true,
        viewrecords: true,
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        autowidth: true,
        height: "450",
        sortname: 'pedis',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["Mes"],
                    groupColumnShow: [false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Mes: <b>{0}</b>"
          ],
                    groupOrder: ["desc"],
                    groupSummary : [false],
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

<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Ingresos vs Egresos</div>
          </div>
        </div>
        <div style="padding: 0px 0 5px 0;">
            <button class="btn btn-primary btn-xs" onclick="ie_agrupador('S');"> Semanal</button> 
            <button class="btn btn-primary btn-xs" onclick="ie_agrupador('M');"> Mensual</button>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
                <table id="jq_arbol"></table>
                <div id="jqp_arbol"></div>
            </div>
          </div>
        </div> 
      </div>
    </div>

</body>

<script>
    function ie_agrupador(por){
        if(por=='S'){
            window.location='index.php?modulo=ingresos_egresos';
        }
        if(por=='M'){
            window.location='index.php?modulo=ingresos_egresosM';
        }
    }
</script>

