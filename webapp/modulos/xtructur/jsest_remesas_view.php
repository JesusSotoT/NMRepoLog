<?php
    $sestmp=time();
    $semana = strftime('%V');

    $ano=$_POST['ano'];
    $username_global=$_POST['username_global'];
    $id_username_global=$_POST['id_username_global'];


  $sema=$_POST['sema'];
  $idrem=$_POST['id'];
  include('conexiondb.php');

  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }

  $SQL = "SELECT a.*, concat('RT-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$id_obra' and a.borrado=0 AND a.id_tipo_alta=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $tecnicos[]=$row;
    }
  }else{
    $tecnicos=0;
  }

  $SQL = "SELECT a.*, concat('PROV-',b.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$id_obra' and a.borrado=0 AND a.id_tipo_alta=5;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
  }




$SQL = "SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1'
UNION ALL
SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
WHERE a.id_obra='$id_obra' AND estatus='1'
UNION ALL
SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_estimaciones_bit_indirectos a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id_prov>0
UNION ALL
SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_bit_nominadest a
WHERE a.id_obra='$id_obra' AND estatus='1'
UNION ALL
SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND estatus='1'
UNION ALL
SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_estimaciones_bit_chica a
WHERE a.id_obra='$id_obra' AND estatus='1';";

  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $pasivos=0;
    while($row = $result->fetch_array() ) {
      $pasivos+=$row['total'];
    }
  }else{
    $pasivos=0;
  }

?>

<div class="row">
  <div class="col-sm-6">
    <div class="row">
      <div class="col-sm-4">
        <label>Total de pasivos:</label>
        <input class="form-control" id="ttp" value="<?php echo $pasivos; ?>" disabled="disabled">
      </div>
      <div class="col-sm-4">
        <label>Monto:</label>
        <input class="form-control" id="monta" value="0.00" disabled="disabled">
        <input type="hidden" id="montah" value="0.00">
      </div>
      <!--
      <div class="col-sm-4">
        <label>Remesa autorizada:</label>
        <input class="form-control" id="rea" value="0.00" disabled="disabled">
      </div>
      <div class="col-sm-4">
        <label>Faltante por pagar:</label>
        <input class="form-control" id="reaf" value="0.00"  disabled="disabled">
      </div>
      -->
    </div>
  </div>
</div>

<script> 
        $(document).ready(function () {
          $('#rea').keyup(function() {
            $('#reaf').val( $(this).val() );

          });
          

          $('#ttp').currency();
$('#preload').css('display','none');
$('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 

            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_remesa.php?sema=<?php echo $sema; ?>',
                datatype: "json",
                colModel: [
                    { label: 'Proveedor', name: 'Proveedor',  width: 70, sortable:false },
                    { label: 'Fecha', name: 'fecha',  width: 70, sortable:false },
                    { label: 'Estimacion', name: 'estimacion',  width: 70, sortable:false },
                    { label: 'No. Factura', name: 'no_factura',  width: 40, sortable:false },
                   // { label: 'Importe', name: 'importe',  width: 60, sortable:false },
                    //{ label: 'Precio de concurso', name: 'precio',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { 
                        label: 'Importe', 
                        name: 'Importe',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        formatoptions: { decimalPlaces: 4 },
                        sortable:false,
                        summaryTpl: "{0}", // set the summary template to show the group summary
                        summaryType:'sum'
                    },
                    { 
                        label: 'Pagado remesas', 
                        name: 'Pagado_en_emesas',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        formatoptions: { decimalPlaces: 4 },
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Saldo por pagar', 
                        name: 'Saldo_por_pagar',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        formatoptions: { decimalPlaces: 4 },
                        sortable:false,
                        summaryType:'sum'
                    },
                    { label: 'Importe Sem: <?php echo $sema; ?>', name: 'impsem__',  width: 100, sortable:false},
                    { label: 'FormaPago', name: 'FormaPago',  width: 60, sortable:false}
                    /*
                    { label: 'No de cheque', name: 'estimacion',  width: 70, sortable:false },
                    { label: 'Validacion', name: 'estimacion',  width: 70, sortable:false },
                    { label: 'Banco', name: 'estimacion',  width: 70, sortable:false },
                    { label: 'Estatus cheque', name: 'estimacion',  width: 70, sortable:false },
                    { label: 'Estatus factura', name: 'estimacion',  width: 70, sortable:false }*/
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



                      //ra=$('#rea').val();

                      //queda=ra-sema;
                      $('#monta').val(sema);
                      $('#montah').val(sema);
                      $('#monta').currency();
                      /*if( (ra*1)<sema ){
                        alert('La remesa autorizada no puede ser menor al importe capturado');
                        $(this).val(0);
                      }
                      */
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
        footerrow: true,
        userDataOnFooter: true,
                groupingView: {
                    groupField: ["Proveedor"],
                    groupColumnShow: [false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "<b>{0}</b>"
          ],
                    groupOrder: ["desc"],
                    groupSummary : [true],
                    //groupSummary: [true], // will use the "summaryTpl" property of the respective column
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"remesas",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });


        });

    </script>

<div class="row">
    <div class="col-xs-12 tablaResponsiva">
      <div class="table-responsive" id="dtabla">
          <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
          <table id="jq_arbol"></table>
          <div id="jqp_arbol"></div>
      </div>
    </div>
  </div> 

<div class="row">&nbsp;</div>

<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Datos del pago</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
      <div class="row">
    <div class="col-sm-8">
      <div class="row">
      <div class="col-sm-2">
          <label>Solicito:</label>
          <div>
            <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
          </div>
          <input type='hidden' id="iduserlog" value='<?php echo $id_username_global; ?>'>

        </div>
      <div class="col-sm-4" style='display:none;'>
          <label>Semana:</label>
          <select class="form-control" id="desta">
            <option selected="selected" value="0">Seleccione una semana</option>
            <?php 
            for($x=1; $x<=$ano; $x++){ ?>
              <option value="<?php echo $x; ?>">Semana <?php echo $x; ?></option>
           <?php } ?>
          </select>
        </div>
        

        <div class="col-sm-4">
          <label>&nbsp;</label>
           <button style="width:100%" id="btngenrem"  class="btn btn-primary btn-xm pull-right" onclick="generaRem(<?php echo $sestmp; ?>,0,0);"> Generar Pago</button>

        </div>
      </div>
    </div>
  </div>
      
  </div><!-- ENd panel body -->
</div>

  

