<div class="modal fade" id="modalcancelreq" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cancelacion</h4>
        </div>
        <div class="modal-body">
             Motivo de Cancelación:<br>
        <input type="hidden" id="idReqModal" value="100">
         
             
                  
                
             
                    <textarea rows="4" cols="50" id="cancelObs" ></textarea>
                  </div>
                      <div class="modal-footer">
                         <br><label id='lenvio' hidden='true'>'Enviando ...'</label>

                        <button  class="btn btn-default" data-dismiss="modal">Cerrar</button>
  
                    <button  id="enviarb" onclick="cancelReq($('#idReqModal').val());">Enviar</button>
         
            
                    
                </div>
            </div>
        </div>
      </div>

<?php
    $SQL = "SELECT DISTINCT a.id, es.nombre FROM constru_requis a 
      LEFT JOIN constru_especialidad es on es.id=a.id_area
      where a.id_obra='$idses_obra' ORDER BY a.id DESC";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $verrequisicion[]=$row;
        }
      }else{
        $verrequisicion=0;
      }    
?>
<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_requit_a.php',
                datatype: "json",
                colModel: [
                    { label: 'Requisicion', name: 'Requisicion', width: 255, sortable:false },
                    { label: 'Solicito', name: 'Solicito',  width: 70, sortable:false },
                    { label: 'Clave', name: 'clave',  width: 70, sortable:false },

                    
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 40, sortable:false },
                    { label: 'Cantidad', name: 'cantidad',  width: 60, sortable:false },
                    { label: 'Precio de concurso', name: 'precio',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { 
                        label: 'Importe', 
                        name: 'importe',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryTpl: "<b>{0}</b>",
                        summaryType:'sum'

                    },
                    { 
                        label: 'Inventario', 
                        name: 'Inventario',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false

                    },
                    { 
                        label: 'Pendiente', 
                        name: 'Pendiente',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false

                    },
                    { label: 'estatus', name: 'estatus',  width: 60, sortable:false, editable:false,
                        editrules: {edithidden:true},
                        hidden:true 
                    },

                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr#null").remove();
                    $('input').numeric();


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
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        autowidth: true,
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
                    "Solicito: <b>{0}</b>",
                    ///chais///
                    "PDF"
                    ///chais///
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"hojarequis",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });
        });

    function modalcancelReq(idReq){
      $('#idReqModal').val(idReq);
      $('#modalcancelreq').modal({
          backdrop: 'static',
          keyboard: false, 
          show: true
      });
    }


    </script>

<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Autorizacion de requisiciones</div>
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




<div class="row">&nbsp;</div>
<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Generar PDF</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        <div class="row">
            <div class="col-sm-3 col-xs-8">
              <select class="form-control" id="reqver">
                  <option selected="selected" value="0">Selecciona un Requisicion</option>
                  <?php 
                  if($verrequisicion!=0){
                    foreach ($verrequisicion as $k => $v) { ?>
                      <option value="<?php echo $v['id']; ?>">REQ-<?php echo $v['id'];?> / Area: <?php echo $v['nombre']; ?></option>
                    <?php } ?>
                  <?php }else{ ?>
                    <option value="0">No hay Reuisisones</option>
                  <?php } ?>
                </select>
            </div>
            <div class="col-sm-1">
              

              <button id="btnpdfreq" class="btn btn-primary btn-xm pull-right" onclick="pdfrequisicion('req');"><span class="glyphicon glyphicon-download"></span> PDF</button>

            </div>
        </div>
      </div>
      </div>

</body>
