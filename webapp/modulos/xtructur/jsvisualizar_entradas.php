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


  ///chais
    $SQL = "SELECT DISTINCT id_oc FROM constru_bit_entradas where id_obra='$idses_obra' ORDER BY id_oc DESC";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $verentradas[]=$row;
        }
      }else{
        $verentradas=0;
      }    
///chais

?>
<script> 
        $(document).ready(function () {

$('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 

            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_entrada2.php',
                datatype: "json",
                colModel: [
                    { label: 'Orden', name: 'Orden', width: 255, sortable:false },
                    { label: 'Requisicion', name: 'Requisicion',  width: 70, sortable:false },
                    { label: 'Entrada', name: 'Entrada',  width: 70, sortable:false },
                    { label: 'Clave', name: 'clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 90, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 30, sortable:false },
                    { label: 'Cantidad', name: 'cantidad',  width: 50, sortable:false },
                    { label: 'Cantidad entrada', name: 'llego',  width: 60, sortable:false},
                    /*{ label: 'PU compra', name: 'puc',  width: 40, sortable:false },
                    { 
                        label: 'Importe', 
                        name: 'importe',
                        width: 50,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },*/
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();
                    $('input[class^=quis_]').numeric();
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
                    groupField: ["ocid", "Orden", "Requisicion", "Entrada"],
                    groupColumnShow: [false, false, false, false],
                    groupText: [
                    "Entrada: <b>{0}</b>",
                    "Orden: <b>{0}</b>",
                    "Requisicion: <b>{0}</b>"
          ],
                    groupOrder: ["desc", "desc", "desc", "desc"],
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
              <div class="navbar-brand" style="color:#333;">Visualizar entradas de almacen</div>
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
      <div class="panel-title">Generar PDF</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        <div class="row">
    <div class="col-sm-3 col-xs-4">
      <select class="form-control" id="entver" onchange="cmbent('des')">
        <option selected="selected" value="0">Selecciona una OC</option>
        <?php 
        if($verentradas!=0){
          foreach ($verentradas as $k => $v) { ?>
            <option value="<?php echo $v['id_oc']; ?>">OC-<?php echo $v['id_oc'];?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay OC /option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-2 col-xs-4">
      <select class="form-control" id="entrada_num">
        <option selected="selected" value="0">...</option>
      </select>

      

    </div>
    <div class="col-sm-1">
      <button onclick="pdfentradas('entradas');" class="btn btn-primary btn-xm pull-right" id="btnpdfent"><span class="glyphicon glyphicon-download"></span> PDF</button>
    </div>
</div>

      </div>
  </div>

  <div class="modal fade" id="delmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Desea borrar esta Entrada?</h4>
      </div>
      <div class="modal-body">
        Atención: Si usted borra este registro no podra revertir los cambios<br>
        Contraseña: <input type='password' id='pass'>
        <input type='hidden' id='ide'>

         
      </div>
      <label id='error'  style="margin-left:15px"></label>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id='enviarb' class="btn btn-default" onclick="delent();">Aceptar</button>
      
        <!--<input type="button" id='enviarb' value="Aceptar" style="cursor:pointer" onclick="delent();">-->

      </div>
    </div>

     </div>
    </div>

    <script>
$('#delmodal').on('show.bs.modal', function(e) {
    $('#error').text('');
  $('#ide').val(e.relatedTarget.dataset.eid);
});


</script>

  </body>





