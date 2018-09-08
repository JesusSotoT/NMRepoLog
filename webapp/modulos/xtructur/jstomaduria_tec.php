<?php
$cookie_xtructur = unserialize($_COOKIE['xtructur']);
$obra_ini = $cookie_xtructur['obra_ini'];
$obra_fin = $cookie_xtructur['obra_fin'];

function periodosfecha($ano,$sem){
  if(strlen($sem)==1){
    $sem='0'.$sem;
  }
  $date1 = date( "Y-m-d", strtotime($ano."W".$sem."1") ); // First day of week
  $date2 = date( "Y-m-d", strtotime($ano."W".$sem."7") ); // Last day of week
  return $date1." - ".$date2.' |  Semana: '.$sem;
}



/**
 * Return the first day of the Week/Month/Quarter/Year that the
 * current/provided date falls within
 *
 * @param string   $period The period to find the first day of. ('year', 'quarter', 'month', 'week')
 * @param DateTime $date   The date to use instead of the current date
 *
 * @return DateTime
 * @throws InvalidArgumentException
 */
function firstDayOf($period, DateTime $date = null)
{
    $period = strtolower($period);
    $validPeriods = array('year', 'quarter', 'month', 'week');

    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

    $newDate = ($date === null) ? new DateTime() : clone $date;

    switch ($period) {
        case 'year':
            $newDate->modify('first day of january ' . $newDate->format('Y'));
            break;
        case 'quarter':
            $month = $newDate->format('n') ;

            if ($month < 4) {
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('first day of april ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('first day of october ' . $newDate->format('Y'));
            }
            break;
        case 'month':
            $newDate->modify('first day of this month');
            break;
        case 'week':
            $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
            break;
    }

    return $newDate;
}
$date = firstDayOf('week');
//echo 'The first day of the current week is: ' . $date->format('Y-m-d') . "\n";

    $semana = strftime('%V');
    $ano = NumeroSemanasTieneUnAno(date('Y'));
/*
for($x=1; $x<=$ano; $x++){
  echo periodosfecha(date('Y'),$x);
  echo '<br>';
}
*/

week_bounds(date('Y-m-d'), $start, $end);

$timestamp = mktime( 0, 0, 0, 1, 1,  date('Y') ) + ( 1 * 7 * 24 * 60 * 60 );
    //  echo   $timestamp_for_monday = $timestamp - 86400 * ( date( 'N', $timestamp ) - 1 );
      //echo   $date_for_monday = date( 'Y-m-d', $timestamp_for_monday );


$SQL = "SELECT a.*, concat('DEST-',a.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=2;";
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
      <div class="panel-title">Control de Asistencia Tec-Admon</div>
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
        <label>Tecnico:</label>
        <select class="form-control" id="desta">
          <option selected="selected" value="0">Seleccione</option>
          <option value="1">Tecnicos oficina central</option>
          <option value="2">Tecnicos oficina campo</option>
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
          <div class="col-md-6 col-md-offset-6">


             <button class="btn btn-primary btn-xm pull-right" onclick="vernominatec();"> Ver tomaduria</button>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-5" id="esph" style="visibility:hidden;">
    <h5>&nbsp;</h5>
    <div class="row">
      <div class="col-md-12">
        <label>Cuenta de costo:</label><br>
        <div id="esptd"></div>
      </div>
    </div>
  </div>
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


