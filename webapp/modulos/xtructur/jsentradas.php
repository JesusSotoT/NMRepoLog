<?php
  $sestmp=time();
  $SQL = "SELECT a.*, concat('RT-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $tecnicos[]=$row;
    }
  }else{
    $tecnicos=0;
  }
?>
<script> 
        $(document).ready(function () {

$('#fecente').datepicker({ dateFormat: "yy-mm-dd", 
  onSelect: function(datetext){
   var d = new Date(); // for now

                      var h = d.getHours();
                      h = (h < 10) ? ("0" + h) : h ;

                      var m = d.getMinutes();
                      m = (m < 10) ? ("0" + m) : m ;

                      var s = d.getSeconds();
                      s = (s < 10) ? ("0" + s) : s ;

                      datetext = datetext + " " + h + ":" + m + ":" + s;

                      $('#fecente').val(datetext);}




}); 

            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_entrada.php',
                datatype: "json",
                colModel: [
                    { label: 'Orden', name: 'Orden', width: 255, sortable:false },
                    { label: 'Solicito', name: 'Solicito',  width: 70, sortable:false },
                    { label: 'Requisicion', name: 'Requisicion',  width: 70, sortable:false },
                    { label: 'Proveedor', name: 'prov',  width: 70, sortable:false },
                    { label: 'Clave', name: 'clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 40, sortable:false },

                    { label: 'Cantidad Req.', name: 'Cant_Requisicion',  width: 60, sortable:false },
                    { label: 'Cant. entrada', name: 'Cant_entrada',  width: 50, sortable:false},
                    /*{ label: 'PU compra', name: 'PU_compra',  width: 40, sortable:false },
                    { 
                        label: 'Importe', 
                        name: 'importe',
                        width: 50,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },*/
                    { label: 'Capturar entrada', name: 'Entrada__',  width: 60, sortable:false}
                ],
                loadComplete: function() {
                   

                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");


                    $("tr #null").remove();
                    $('input[class^=quis_]').numeric("-");


                    $('input[class^=quis_]').prop('disabled',true);
                    $('input.ccbox').on('change', function() {
                        id_oc = $(this).val();
                        $('input[class^=quis_]').prop('disabled',true);

                        if(!$(this).is(':checked') ){
                          $('.quis_'+id_oc+'_').prop('disabled',true);
                        }else{
                          $('.quis_'+id_oc+'_').prop('disabled',false);
                        }
                        $('input.ccbox').not(this).prop('checked', false);  
                        
                       
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
        sortname: 'ocid',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["ocid","Orden", "Solicito", "Requisicion"],
                    groupColumnShow: [false,false, false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Orden: <b>{0}</b>",
                    "Solicito: <b>{0}</b>",
                    "Requisicion: <b>{0}</b>"
          ],
                    groupOrder: ["desc", "asc", "desc", "asc"],
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
              <div class="navbar-brand" style="color:#333;">Entradas de almacen</div>
          </div>
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




  <div class="row">&nbsp;</div>
  <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Datos de la entrada</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        <div class="row">
        <div class="col-sm-4">
        <label>Almacenista:</label>
        <div>
        <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
        </div>
        <input type='hidden' id="iduserlog" value='<?php echo $id_username_global; ?>'>
        
      </div>
      <div class="col-sm-4">
        <label>Fecha de entrada:</label>
        <input class="form-control" id="fecente" type="text">
      </div>
      
      <div class="col-sm-4">
        <label>Observaciones:</label>
        <textarea id="obs" style="width:100%; height: 34px;"></textarea>
      </div>
      

      <div class="col-sm-12" style="padding-top: 15px;">
              

              <button id="btngenl" class="btn btn-primary btn-xm pull-right" onclick="generaEnt(<?php echo $sestmp; ?>);"> Generar Entrada</button>

            </div>
    </div>

      </div>
  </div>

  </body>

    

    