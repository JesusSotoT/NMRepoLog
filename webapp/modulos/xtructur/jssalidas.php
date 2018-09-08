<?php
  $sestmp=time();
  $SQL = "SELECT id, cc FROM constru_cuentas_cc ORDER by id;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $cc[]=$row;
    }
  }else{
    $cc=0;
  }
  
  $SQL = "SELECT a.*, concat('RT-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $tecnicos[]=$row;
    }
  }else{
    $tecnicos=0;
  }

  $SQL = "SELECT a.*, concat('DEST-',a.id,' - ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=2
  UNION ALL SELECT a.*, concat('SUBC-',a.id,' - ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=4;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $recibio[]=$row;
    }
  }else{
    $recibio=0;
  }

  $SQL = "SELECT a.id, a.nombre FROM constru_agrupador a where a.id_obra='$idses_obra' AND a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $agrupadores[]=$row;
    }
  }else{
    $agrupadores=0;
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

                      $('#fecente').val(datetext);} }); 

            $("#jq_arbol").jqGrid({
                url:'sql_jssalida.php',
                datatype: "json",
                colModel: [
                    { label: 'Orden', name: 'Orden', width: 255, sortable:false },
                    { label: 'Solicito', name: 'Solicito',  width: 70, sortable:false },
                    { label: 'Requisicion', name: 'Requisicion',  width: 70, sortable:false },
                    { label: 'Clave', name: 'clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 40, sortable:false },
                    { label: 'Cant. Req', name: 'Cant. Req',  width: 40, sortable:false },
                    { label: 'Inventario', name: 'Inventario',  width: 60, sortable:false },
                    //{ label: 'Precio de concurso', name: 'precio',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    /*{ 
                        label: 'Importe', 
                        name: 'importe',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },*/
                    { label: 'Cantidad salida', name: 'Cantidad_salida',  width: 60, sortable:false},
                    { label: 'Capturar salida', name: 'salida__',  width: 60, sortable:false}
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
                    groupField: ["Orden", "Solicito", "Requisicion"],
                    groupColumnShow: [false, false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Orden: <b>{0}</b>",
                    "Solicito: <b>{0}</b>",
                    "Requisicion: <b>{0}</b>"
          ],
                    groupOrder: ["desc", "desc", "asc"],
                    groupSummary : [false, false, true],
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
              <div class="navbar-brand" style="color:#333;">Salidas de almacen</div>
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
      <div class="panel-title">Datos de la salida</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        <div class="row">
      <div class="col-sm-4">
        <label>Fecha de salida:</label>
        <input class="form-control" id="fecente" type="text">
      </div>
      <div class="col-sm-4">
        <label>Observaciones:</label>
        <textarea id="obs" style="width:100%;"></textarea>
      </div>
      <div class="col-sm-4">
        <label>Recibio:</label>
        <select class="form-control" id="val_recibio">
            <option selected="selected" value="0">Seleccione</option>
            <?php 
            if($recibio!=0){
              foreach ($recibio as $k => $v) { ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
              <?php } ?>
            <?php }else{ ?>
              <option value="0">No hay destajistas o subcontratistas dados de alta</option>
            <?php } ?>
          </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">
        <label>Entrego:</label>
        <select class="form-control" id="val_entrego">
          <option selected="selected" value="0">Seleccione</option>
          <?php 
          if($tecnicos!=0){
            foreach ($tecnicos as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay tecnicos dados de alta</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-4">
        <label>Autorizo:</label>
        <div>
        <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
        </div>
        <input type='hidden' id="iduserlog" value='<?php echo $id_username_global; ?>'>
        <!--
        <select class="form-control" id="val_autorizo">
          <option selected="selected" value="0">Seleccione</option>
          <?php 
          if($tecnicos!=0){
            foreach ($tecnicos as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay tecnicos dados de alta</option>
          <?php } ?>
        </select>
        -->
      </div>
      <div class="col-sm-4"></div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <h5>Planeacion</h5>
        <div class="row">
          <div class="col-sm-6">
            <label>Agrupador:</label>
            <select class="form-control" id="cargaagr" onchange="chagru2();">
              <option selected="selected" value="0">Seleccione un agrupador</option>
              <?php 
              if($agrupadores!=0){
                foreach ($agrupadores as $k => $v) { ?>
                  <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
                <?php } ?>
              <?php }else{ ?>
                <option value="0">No hay agrupadores dados de alta</option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-6">
            <label>Area:</label>
            <select class="form-control" id="cargaesp" onchange="chesp2();">
              <option selected="selected" value="0">Selecciona un area</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <label>Especialidad:</label>
            <select class="form-control" id="cargaare" onchange="charea2();">
              <option selected="selected" value="0">Selecciona una especialidad</option>
            </select>
          </div>
          <div class="col-sm-6">
            <label>Partida:</label>
            <select class="form-control" id="cargapart">
              <option selected="selected" value="0">Selecciona una partida</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <h5>Cuenta de costo</h5>
        <div class="row">
          <div class="col-sm-6">
            <label>Cuenta:</label>
            <select class="form-control" id="cmbcc" onchange="chcc();">
              <option selected="selected"  value="0">Selecciona</option>
              <?php 
              if($cc!=0){
                foreach ($cc as $k => $v) { ?>
                  <option value="<?php echo $v['id']; ?>"><?php echo $v['cc']; ?></option>
                <?php } ?>
              <?php }else{ ?>
                <option value="0">No hay cuentas dadas de alta</option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-6">
            <label>Cuenta de costo:</label>
            <select class="form-control" id="chcosto" onchange="chcosto1();">
              <option selected="selected" value="0">Selecciona una cuenta de costo</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <label>Cuenta de cargo:</label>
            <select class="form-control" id="ccosto">
              <option selected="selected" value="0">Selecciona una cuenta de cargo</option>
            </select>
          </div>
          <div class="col-sm-6" style="padding-top: 25px;">
             <button id="btngenl"  class="btn btn-primary btn-xm pull-right" onclick="generaSal(<?php echo $sestmp; ?>);"> Generar vale de salida</button>

           
          </div>
        </div>
      </div>
    </div>

      </div>
  </div>

  </body>


    
    
    
    
