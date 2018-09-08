<?php
  $sestmp=time();
  $sema=$_POST['sema'];
  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
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
  $SQL = "SELECT a.*, concat('RT-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$id_obra' and a.borrado=0 AND a.id_tipo_alta=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $tecnicos[]=$row;
    }
  }else{
    $tecnicos=0;
  }

   $SQL = "SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='0'
UNION ALL
SELECT if(sum(a.total) is null,0, sum(a.total)) total
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
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
<div style="float:left; width:800px;">
  <div style="float:left; width:390px;">
  <fieldset style="width: 380px; height:80px; border-color: #ffffff;margin:0 0 10px; -1px;">

      <table style="font-size:12px;">
        <tr>
          <td width="150">Total de pasivos:</td>
          <td width="150">Remesa autorizada:</td>
          <td width="150">Faltante por pagar:</td>
        </tr>
        <tr>
          <td><input id="ttp" value="<?php echo $pasivos; ?>" disabled="disabled"></td>
          <td><input id="rea" value="0.00"></td>
          <td><input id="reaf" value="0.00"  disabled="disabled"></td>
        </tr>
      </table>
  </fieldset>
  </div>
</div>
<script> 
        $(document).ready(function () {
          $('#rea').keyup(function() {
            $('#reaf').val( $(this).val() );

           /*   var sema = 0;
              $('input[class^=quis__]').each(function() {
                  sema += Number($(this).val());
              });

              $('#reaf').val(sema);

              ra=$('#rea').val();
              if( (ra*1)<sema ){
                alert('La remesa autorizada no puede ser menor al importe capturado');
                $(this).val(0);
              }
            });*/

          });
          

          $('#ttp').currency();
$('#preload').css('display','none');
$('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 

            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_remesa2.php?sema=<?php echo $sema; ?>',
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
                        name: 'importe',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Pagado remesas', 
                        name: 'pagado',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Saldo por pagar', 
                        name: 'saldop',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { label: 'Importe Sem: <?php echo $sema; ?>', name: 'impsem',  width: 60, sortable:false}
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
        width: "1000",
        height: "450",
        sortname: 'pedis',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["Proveedor"],
                    groupColumnShow: [false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "<b>{0}</b>"
          ],
                    groupOrder: ["desc"],
                    groupSummary : [true],
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
<div style="float:left; width:700px;">
    <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
<div id="dtabla" style="float:left; width:700px; font-size:10px;">
    <table id="jq_arbol"></table>
    <div id="jqp_arbol"></div>
</div>
<div style="float:left; width:910px; margin:10px 0 0 -1px;">
  <fieldset style="width: 910px;">
    <legend>Guardar remesa:</legend>
    <table style="font-size:11px;">
      <tr>
        <td>Solicito:</td>
        <td>
          <select id="val_solicito">
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
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td> <input id="btngenrem"  type="button" value="Generar Remesa" style="cursor:pointer;" onclick="generaRem(<?php echo $sestmp; ?>,<?php echo $sema; ?>);"></td>
      </tr>

    </table>
  </fieldset>
</div>
</div>