<?php
$cookie_xtructur = unserialize($_COOKIE['xtructur']);
$obra_ini = $cookie_xtructur['obra_ini'];
$obra_fin = $cookie_xtructur['obra_fin'];


    $semana = strftime('%V');

week_bounds(date('Y-m-d'), $start, $end);

$SQL = "SELECT a.*, concat('DEST-',a.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=2 AND (a.estatus='Alta' OR a.estatus='Incapacitado');";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $maestros[]=$row;
    }
  }else{
    $maestros=0;
  }

/*
  $SQL = "SELECT a.id, concat('DEST-',a.id_dest,' ',b.nombre,' ',b.paterno,' / ',a.fecha,' / Semana: ',a.semana) as dest from constru_bit_nominadest a
left join constru_info_tdo b on b.id_alta=a.id_dest

where a.id_obra='$idses_obra' and a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $estimaciones[]=$row;
    }
  }else{
    $estimaciones=0;
  }
*/
 
?>
<div class="row">&nbsp;</div>
<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Elaboracion nomina de obreros</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        
        <div class="row">
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-6">
        <label>Periodo:</label><br>
        <?php echo $start; ?> al <?php echo $end; ?> | <?php echo 'Semana: '.$semana; ?>
      </div>
      <div class="col-md-6">
        <label>Destajista:</label>
        <select class="form-control" id="desta">
          <option selected="selected" value="0">Seleccione un maestro</option>
          <?php 
          if($maestros!=0){
             include('securityfilter.php');
             $sec=new CI_Security();
            $maestros = $sec->xss_clean($maestros);
            foreach ($maestros as $k => $v) {?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php }?>
          <?php }else{ ?>
            <option value="0">No hay maestros dados de alta</option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6" style="padding-top: 8px;">
        <label>Selecciona semana:</label><br>
        <div class="week-picker" style="width:100% !important;"></div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <label>Inicio:</label>
            <input class="form-control" id="startDate">
          </div>
          <div class="col-md-6">
            <label>Fin:</label>
            <input class="form-control" id="endDate">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-md-offset-6" style="padding-top: 10px;">
            <button class="btn btn-primary btn-xm pull-right" onclick="verprenomina();"> Ver nomina</button>
          </div>
        </div>
      </div>
    </div>
  </div>
 
</div>
          
      </div><!-- ENd panel body -->
    </div>



<!--
<h4>Ver nominas generadas</h4>
<div class="row">
  <div class="col-sm-3">
    <label>&nbsp;</label>
    <select class="form-control" id="destaver" onchange="cmbpnom()">
      <option selected="selected" value="0">Selecciona un maestro</option>
      <?php 
      if($maestros!=0){
        foreach ($maestros as $k => $v) { ?>
          <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
        <?php } ?>
      <?php }else{ ?>
        <option value="0">No hay maestros dados de alta</option>
      <?php } ?>
    </select>
  </div>
  <div class="col-sm-3">
    <label>&nbsp;</label>
    <select class="form-control" id="nomi">
      <option selected="selected" value="0">Selecciona la nomina</option>
    </select>
  </div>
  <div class="col-sm-3">
    <label>&nbsp;</label>
    <input class="btn btn-primary btnMenu" type="button" value="Ver estimacion" onclick="vernomgeneradas();" style="cursor:pointer;">
  </div>
</div>
-->
<div class="row">
  <div class="col-sm-12" id="vernomina">
    
  </div>
</div>

<script>     
    $(function() {
    var startDate;
    var endDate;
    
    var selectCurrentWeek = function() {
        window.setTimeout(function () {
            $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }
$('.week-picker').datepicker( {
        minDate: '<?php echo $obra_ini; ?>',
        maxDate: '<?php echo $obra_fin; ?>',
        showWeek: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        firstDay: 1,
        dateFormat: "yy-mm-dd",
        onSelect: function(dateText, inst) { 
            var myGrid = $('#row_proforma'),
            id = myGrid.jqGrid ('getGridParam', 'selrow');

            var date = $(this).datepicker('getDate');
          //  console.log(date.getDate()+0);
            
            if(date.getDay()==0){
              gd=7;
            }else{
              gd=date.getDay();
            }
           // console.log(gd);
            startDate = new Date(date.getFullYear(), date.getMonth(), (date.getDate()+0) - gd+1);
           // console.log(startDate);
            endDate = new Date(date.getFullYear(), date.getMonth(), (date.getDate()+0) - gd+7);
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;

            st=$.datepicker.formatDate( dateFormat, startDate, inst.settings );
            fn=$.datepicker.formatDate( dateFormat, endDate, inst.settings );
            $('#startDate').val(st);
            $('#endDate').val(fn);

            selectCurrentWeek();
        },
        beforeShowDay: function(date) {
            var cssClass = '';
          /*  console.log(date);
            console.log(startDate);
            console.log(endDate);
*/
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';

            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
        /*,
        beforeShowDay: function(date) {
      //    alert(8);
var day = date.getDay();
return [day != 0,''];
}
*/
  });
});
  </script> 

