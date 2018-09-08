<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

//Sacar lista de años semanas
$SQL = "SELECT e.id,concat('Estimacion-',e.id,' ',e.xxano) as semana
 from constru_estimaciones_bit_chica e
  where e.id_obra=$id_obra";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $vercompras[]=$row;
        }
      }else{
        $vercompras=0;
      }    
///chais


?>

<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_histest.php',
                datatype: "json",
                colModel: [

               // d.codigo, d.descripcion, d.unidtext, d.pu_destajo, e.vol_tope*d.pu_destajo as total, c.vol_anterior, c.vol_est

                   { label: 'Tiempo', name: 'tiempo',  width: 70, sortable:false },
                   { label: 'Monto', name: 'total',  width: 70, sortable:false },

                    { label: 'Proveedor/Subcontratista', name: 'Proveedor',  width: 70, sortable:false },

                    { label: 'Autorizo', name: 'aut',  width: 70, sortable:false },
                     { label: 'Fecha autorizacion', name: 'faut',  width: 40, sortable:false },
                    { label: 'Hora autorizacion', name: 'taut',  width: 40, sortable:false },

                   
                    { label: 'Solicito', name: 'sol',  width: 70, sortable:false },
                   { label: 'Fecha solicitud', name: 'fsol',  width: 40, sortable:false },
                   { label: 'Hora solicitud', name: 'tsol',  width: 40, sortable:false },

                   
                    //{ label: 'estimacion', name: 'estimacion',  width: 40, sortable:false },

                    { label: 'Maestro2', name: 'Proveedor2',  width: 70, sortable:false },
                     { label: 'categoria', name: 'cat',  width: 70, sortable:false },

                      { label: 'estimacion', name: 'estimacion',  width: 70, sortable:false }, 
                    


                    

                    
                    
                      

                   
                   
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
        sortname: 'Proveedor',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["cat","Proveedor2"],
                    groupColumnShow: [false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                   "<b>{0}</b>",
                    "<b>{0}</b>"
                    
          ],
                    groupOrder: ["asc"],
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"hojarequisComp_dest",formato:"excel"});
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
              <div class="navbar-brand" style="color:#333;">Historial de movimientos - Estimaciones</div>
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




