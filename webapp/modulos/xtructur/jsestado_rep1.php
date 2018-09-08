<?php
function dias_transcurridos($fecha_i,$fecha_f)
{
  $dias = (strtotime($fecha_i)-strtotime($fecha_f))/86400;
  $dias   = abs($dias); $dias = floor($dias);   
  return $dias;
}




  $mes=$_POST['mes'];
  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }
  $SQL = "SELECT * FROM constru_generales WHERE id='$id_obra' limit 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
  }else{

  }

$date1 = $row['inicio'];
$date2 = $row['termino'];

$ts1 = strtotime($date1);
$ts2 = strtotime($date2);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);

$month1 = date('m', $ts1);
$month2 = date('m', $ts2);

$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

?>

<div class="row">
  <div class="col-sm-3">
    <label>Importe de contrato:</label><br>
    $<?php echo number_format($row['presupuesto'], 2, '.',','); ?>
  </div>
  <div class="col-sm-3">
    <label>Fecha de inicio:</label><br>
    <?php echo substr($row['inicio'],0,10); ?>
  </div>
  <div class="col-sm-3">
    <label>Duracion dias:</label><br>
    <?php echo dias_transcurridos($row['inicio'],$row['termino']); ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-3">
    <label>Anticipo (<?php echo $row['anticipo']; ?> %):</label><br>
    $<?php echo number_format($row['presupuesto']*($row['anticipo']/100), 2, '.',','); ?>
  </div>
  <div class="col-sm-3">
    <label>Fecha de termino:</label><br>
    <?php echo substr($row['termino'],0,10); ?>
  </div>
  <div class="col-sm-3">
    <label>Duracion meses:</label><br>
    <?php echo $diff; ?>
  </div>
</div>
<h5>&nbsp;</h5>

  <script>     
    $(document).ready(function () {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','210px');
            $('.FormGrid input').numeric(); 
          },
        closeAfterEdit:true,
        width: 600,
        recreateForm: true
      };


      option='proforma';
      jQuery("#grid").jqGrid('navGrid','#pcrud',{});
      jQuery("#row_proforma").jqGrid({
        url:'sql_jsestado_rep1.php?mes=<?php echo $mes; ?>&q=3&option='+option,
        datatype: "json",
        colNames:['Pagadas','En caja', 'En proceso', 'Por generar','Total produccion'],
        colModel:[
          {name:'Pagadas',index:'Pagadas', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'En caja', index:'En caja', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'En proceso',index:'En proceso', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Por generar', index:'Por generar', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Total produccion',index:'Total produccion', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"}

        ],
        /*
        onCellSelect: function(rowid, iRow, iCol, e) {
         $(this).jqGrid('editGridRow', rowid, formEditingOptions);
         e.stopPropagation();
        },
        */
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,
        pager: '#prow_proforma',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        width: "1035",
        footerrow: true,
        multiselect:true,
        editurl: "sql_jsestado_rep1.php?mes=<?php echo $mes; ?>",
        caption:"Estimaciones al cliente",
        shrinkToFit: false
               
      });

      jQuery("#row_proforma").jqGrid('navGrid',"#prow_proforma",
        {edit:true,add:false,del:false,search:false},
        formEditingOptions,
        {},// settings for adding
        {},// settings for delete
        {closeAfterSearch:true} // settings for search
      ).jqGrid('navButtonAdd', '#prow_proforma', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#row_proforma").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
            },
            position: "last"
        });

    });
  </script>

<div class="row">
  <div class="col-xs-12 tablaResponsiva">
    <div class="table-responsive" id="dtabla">
        <table id="row_proforma"></table>
        <div id="prow_proforma"></div>
    </div>
  </div>
</div> 
