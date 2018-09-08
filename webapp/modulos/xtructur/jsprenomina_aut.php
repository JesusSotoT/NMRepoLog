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
<!--
<h4>Prenomina de obreros</h4>
<div class="row">
  <div class="col-md-7">
    <h5>Filtros</h5>
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
      <div class="col-md-6">
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
            <input class="btn btn-primary btnMenu" type="button" value="Ver nomina" onclick="verprenomina();" style="cursor:pointer;">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-5" id="infoest" style="display:none;">
    <h5>Datos de estimacion del destajista</h5>
    <div class="row">
      <div class="col-md-12">
        <label>Semana:</label><br>
        <div id="idsema"></div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <label>Importe de estimacion:</label>
        <input id="totale" type="hidden" value="0.00"></td>
        <div id="idimp">&nbsp;</div>
      </div>
    </div>
  </div>
</div>
-->
<div class="row">&nbsp;</div>
<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Autorizacion nomina de obreros</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <div class="row" style="padding-bottom: 8px;">
  <div class="col-sm-3">

    
      <select class="form-control" id="destaver" onchange="cmbpnom();">
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




    <!-- <select class="form-control" id="destaver" onchange="cmbpnom()">
      <option selected="selected" value="0">Selecciona un maestro</option>
      <?php 
      if($maestros!=0){
        foreach ($maestros as $k => $v) { ?>
          <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
        <?php } ?>
      <?php }else{ ?>
        <option value="0">No hay maestros dados de alta</option>
      <?php } ?>
    </select> -->



  </div>
  <div class="col-sm-3">

    <select class="form-control" id="nomi">
      <option selected="selected" value="0">Selecciona la nomina</option>
    </select>
  </div>
  <div class="col-sm-3">
    
  

     <button class="btn btn-primary btn-xm" onclick="vernomgeneradas();"> Ver estimacion</button>


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

