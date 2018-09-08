<?php
$cookie_xtructur = unserialize($_COOKIE['xtructur']);
$obra_ini = $cookie_xtructur['obra_ini'];
$obra_fin = $cookie_xtructur['obra_fin'];

function week_from_monday($date) {
    // Assuming $date is in format DD-MM-YYYY
    list($day, $month, $year) = explode("-", $date);

    // Get the weekday of the given date
    $wkday = date('l',mktime('0','0','0', $month, $day, $year));

    switch($wkday) {
        case 'Monday': $numDaysToMon = 0; break;
        case 'Tuesday': $numDaysToMon = 1; break;
        case 'Wednesday': $numDaysToMon = 2; break;
        case 'Thursday': $numDaysToMon = 3; break;
        case 'Friday': $numDaysToMon = 4; break;
        case 'Saturday': $numDaysToMon = 5; break;
        case 'Sunday': $numDaysToMon = 6; break;   
    }

    // Timestamp of the monday for that week
    $monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);

    $seconds_in_a_day = 86400;

    // Get date for 7 days from Monday (inclusive)
    for($i=0; $i<7; $i++)
    {
        $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
    }

    return $dates;
}

$rr= week_from_monday('01-01-2015');


    $anocurso=date('Y');
    $semana = strftime('%V');
    week_bounds(date('Y-m-d'), $start, $end);
    $semanascurso = NumeroSemanasTieneUnAno(date('Y'));

$SQL = "SELECT a.*, concat('DEST-',a.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=2 AND (a.estatus='Alta' OR a.estatus='Incapacitado');";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $maestros[]=$row;
    }
  }else{
    $maestros=0;
  }

?>
<div class="row">&nbsp;</div>
<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Control de asistencia de obreros</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <div class="row">
  <div class="col-md-7">

    <div class="row">
      <div class="col-md-6">
        <label>Periodo actual:</label><br>
        <?php echo $start; ?> al <?php echo $end; ?> | <?php echo 'Semana: '.$semana; ?>
      </div>
      <div class="col-md-6">
        <label>Destajista:</label>
        <select class="form-control" id="desta">
          <option selected="selected" value="0">Seleccione un maestro</option>
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
            

             <button class="btn btn-primary btn-xm pull-right" onclick="vernomina();"> Ver tomaduria</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--
  <div class="col-md-5" id="esph" style="visibility:hidden;">

    <div class="row">

    </div>
    <div class="row">
      <div class="col-md-12">
        <label>Especialidad:</label>
        <div id="partd">&nbsp;</div>
      </div>
    </div>
  </div>
  -->
</div>
          
      </div><!-- ENd panel body -->
    </div>



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
/*
            $.ajax({
              url:"ajax.php",
              type: 'POST',
              dataType:'JSON',
              data:{opcion:'tomatime',st:st,fn:fn,id:id},
              success: function(r){
                if(r.success==1){
                  if(r.datos[0].lun==1){ $('#lun').prop('checked', true); }else{ $('#lun').prop('checked', false); }
                  if(r.datos[0].mar==1){ $('#mar').prop('checked', true); }else{ $('#mar').prop('checked', false); }
                  if(r.datos[0].mie==1){ $('#mie').prop('checked', true); }else{ $('#mie').prop('checked', false); }
                  if(r.datos[0].jue==1){ $('#jue').prop('checked', true); }else{ $('#jue').prop('checked', false); }
                  if(r.datos[0].vie==1){ $('#vie').prop('checked', true); }else{ $('#vie').prop('checked', false); }
                  if(r.datos[0].sab==1){ $('#sab').prop('checked', true); }else{ $('#sab').prop('checked', false); }
                }else{
                  $('#lun').prop('checked', false);
                  $('#mar').prop('checked', false);
                  $('#mie').prop('checked', false);
                  $('#jue').prop('checked', false);
                  $('#vie').prop('checked', false);
                  $('#sab').prop('checked', false);
                }
              }
            });
*/

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
