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


      $startm    = new DateTime($obra_ini);
$startm->modify('first day of this month');
$endm      = new DateTime($obra_fin);
$endm->modify('first day of next month');
$intervalm = DateInterval::createFromDateString('1 month');
$periodm   = new DatePeriod($startm, $intervalm, $endm);


//Sacar lista de años semanas
    $a1=explode('-', $obra_ini);
    $a1=$a1[0]*1;

    $b1=explode('-', $obra_fin);
    $b1=$b1[0]*1;

    $ini_anos=array();

    $semana = strftime('%V');
    $elano=date('Y');
    week_bounds(date('Y-m-d'), $start, $end);

    $cmbsemanas=array();
    if($a1<$b1){
      for ($i=$a1; $i <= $b1; $i++) { 
        $ini_anos[]=$i;
      }

      $numanos = count($ini_anos);
      $x=1;
      foreach ($ini_anos as $key => $value) {
        if($key+1==1){
          $fsemanos = NumeroSemanasTieneUnAno($value);
          $fsemactual = getweek($obra_ini);
          for ($i=$fsemactual; $i <= $fsemanos; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
            $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
          }
        }else if ( $key+1>1 && $key+1!=$numanos ){
          $fsemanos = NumeroSemanasTieneUnAno($value);
          for ($i=1; $i <= $fsemanos; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
             $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
          }
        }else if ( $key+1==$numanos ) {
          $fsemanos = NumeroSemanasTieneUnAno($value);
          $fsemactual = getweek($obra_fin);
          for ($i=1; $i <= $fsemactual; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
             $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
          }
        }else{
          echo "Error en las fechas de inicio y fin de obra";
          exit();
        }
      }
    }else if($a1==$b1){
      $fsemanos = NumeroSemanasTieneUnAno($a1);
      $fsemaini = getweek($obra_ini);
      $fsemafin = getweek($obra_fin);
      for ($i=$fsemaini; $i <= $fsemafin; $i++) { 
        if(strlen($i)==1){
          $add='0'.$i;
        }else{
          $add=$i;
        }
        $lolo=getStartAndEndDate($add,$a1);
        $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';

      }
    }else{
      echo "Error en las fechas de inicio y fin de obra";
      exit();
    }
   
    $ano = NumeroSemanasTieneUnAno(date('Y'));
///chais
?>
<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_requit.php',
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
                    $('input[name=num]').numeric();


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
        loadonce:false,
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

<div class="col-sm-3 col-xs-8">
    <label>Semana:</label>
      <select class="form-control" id="filtro_semana" onchange="filtros('achica','sem');">
        <option selected="selected" value="0">Todas</option>
        <?php 
        if($cmbsemanas!=0){
          foreach ($cmbsemanas as $key => $value) { 
            $expano=explode('(', $value);
            $anoexact=$expano=explode('-', $expano[1]);
            $anoexact=$anoexact[0];

            $expsema=explode(' ', $value);
            $semaexact=$expsema[0];
            ?>
            <option value="<?php echo $anoexact.''.$semaexact; ?>">Semana <?php echo $value; ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Mes:</label>
      <select class="form-control" id="filtro_mes" onchange="filtros('achica','mes');">
        <option selected="selected" value="0">Todos</option>
        <?php 
        if($periodm!=0){
          foreach ($periodm as $dt) { ?>
            <option value="<?php echo $dt->format("Y-m"); ?>"><?php echo $dt->format("Y-m"); ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Estado:</label>
      <select class="form-control" id="filtro_estatus" onchange="filtros('achica','est');">
        <option selected="selected" value="x">Todos</option>
            <option value="1">Pendientes</option>
            <option value="3">Autorizadas</option>
            <option value="2">Canceladas</option>
      </select>
    </div>
  

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

       <div class="modal fade" id="passmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Introduzca Contraseña</h4>
      </div>
      <div class="modal-body">

        Contraseña: <input type='password' id='pass'>
     

         
      </div>
          <input type='hidden' id='ide2'>
      <label id='error'  style="margin-left:15px"></label>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id='enviarb' class="btn btn-default" onclick="can_aut_req();">Aceptar</button>
      
        <!--<input type="button" id='enviarb' value="Aceptar" style="cursor:pointer" onclick="delent();">-->

      </div>
    </div>

     </div>
    </div>


    <script>$('#passmodal').on('show.bs.modal', function(e) {
  $('#ide2').val(e.relatedTarget.dataset.eid);
});</script>
</body>
