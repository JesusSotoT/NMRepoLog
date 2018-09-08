<?php
function dias_transcurridos($fecha_i,$fecha_f)
{
  $dias = (strtotime($fecha_i)-strtotime($fecha_f))/86400;
  $dias   = abs($dias); $dias = floor($dias);   
  return $dias;
}

  $mes=$_POST['mes'];
   $mesn=$_POST['mesn'];
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

    <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Datos del contrato</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
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
          
      </div><!-- ENd panel body -->
    </div>



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
      jQuery("#row_proforma").jqGrid({
        url:'sql_jscontrol_indirectos_rep.php?mes=<?php echo $mes; ?>&q=3&option='+option,
        datatype: "json",
        colNames:['Cuenta de cargo','Importe', 'Al <?php echo $mesn; ?>', 'Proyeccion Costo acumulado','Por ejercer'],
        colModel:[
        {name:'Cuenta de cargo',index:'Cuenta de cargo', width:200, sortable:false,editable:true},
          {name:'Importe', index:'Importe', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number",summaryType:'sum'},
          {name:'Mes',index:'<?php echo $mesn; ?>', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number",summaryType:'sum'},
          {name:'Costo_acumulado', index:'Costo acumulado', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number",summaryType:'sum'},
          {name:'Por_ejercer',index:'Por ejercer', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number",summaryType:'sum'}

        ],
        gridComplete: function(){
          var s1 = $("#row_proforma").jqGrid('getCol','Importe',false,'sum');
          var s2 = $("#row_proforma").jqGrid('getCol','Mes',false,'sum');
          var s3 = $("#row_proforma").jqGrid('getCol','Costo_acumulado',false,'sum');
          var s4 = $("#row_proforma").jqGrid('getCol','Por_ejercer',false,'sum');
          $("#row_proforma").jqGrid('footerData','set',{Importe:s1,Mes:s2,Costo_acumulado:s3,Por_ejercer:s4});

        },
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,
        pager: '#prow_proforma',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        footerrow: true,
        multiselect:false,
        editurl: "sql_jscontrol_indirectos_rep.php?mes=<?php echo $mes; ?>"
        

               
      });

      jQuery("#row_proforma").jqGrid('navGrid',"#prow_proforma",
        {edit:false,add:false,del:false,search:false},
        formEditingOptions,
        {},
        {},
        {closeAfterSearch:true}
      ).jqGrid('navButtonAdd', '#prow_proforma', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#row_proforma").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
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

