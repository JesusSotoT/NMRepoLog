<style>
.wrapper {

}
#jrange input {
   width: 200px;
}
#jrange div {
   font-size: 9pt;
}
.date-range-selected > .ui-state-active,
.date-range-selected > .ui-state-default {
   background: none;
   background-color: #fff;
}
</style>

<?php 
$semana = strftime('%V');
week_bounds(date('Y-m-d'), $start, $end);

$SQL = "SELECT a.*, concat('DEST-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=2;";
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
      <div class="panel-title">Elaboracion Nomina Tec-Admon Oficina Campo</div>
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
        <label>Tecnico:</label>
        <select class="form-control" id="desta">
          <option selected="selected" value="2">Tecnicos oficina campo</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6" style="padding-top: 7px;">
        <label>Selecciona la quincena:</label><br>
        <div class="wrapper" style="width:100% !important;">
          <div id="jrange" class="dates">
            <input class="form-control" style="width: 100%; margin-bottom: 1em;" />
            <div></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
          <!--
            <label>Inicio:</label>
            <input class="form-control" id="startDate">
            -->
          </div>
          <div class="col-md-6">
          <!--
            <label>Fin:</label>
            <input class="form-control" id="endDate">
            -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-md-offset-6" style="padding-top: 20px;">
           <button class="btn btn-primary btn-xm pull-right" onclick="verprenominatec();" > Ver nomina</button>
          </div>
        </div>
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
   $.datepicker._defaults.onAfterUpdate = null;
var datepicker__updateDatepicker = $.datepicker._updateDatepicker;
$.datepicker._updateDatepicker = function( inst ) {
   datepicker__updateDatepicker.call( this, inst );
   var onAfterUpdate = this._get(inst, 'onAfterUpdate');
   if (onAfterUpdate)
      onAfterUpdate.apply((inst.input ? inst.input[0] : null),
         [(inst.input ? inst.input.val() : ''), inst]);
}
$(function() {
   var cur = -1, prv = -1;
   $('#jrange div')
      .datepicker({
            minDate: '<?php echo $obra_ini; ?>',
            maxDate: '<?php echo $obra_fin; ?>',
            //numberOfMonths: 3,
            changeMonth: false,
            changeYear: false,
            showButtonPanel: false,
            dateFormat: "yy-mm-dd",
            beforeShowDay: function ( date ) {
                  return [true, ( (date.getTime() >= Math.min(prv, cur) && date.getTime() <= Math.max(prv, cur)) ? 'date-range-selected' : '')];
               },
            onSelect: function ( dateText, inst ) {
                  var d1, d2;
                  prv = cur;
                  cur = (new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay)).getTime();
                  if ( prv == -1 || prv == cur ) {
                     prv = cur;
                     $('#jrange input').val( dateText );
                  } else {
                     d1 = $.datepicker.formatDate( 'yy-mm-dd', new Date(Math.min(prv,cur)), {} );
                     d2 = $.datepicker.formatDate( 'yy-mm-dd', new Date(Math.max(prv,cur)), {} );
                     $('#jrange input').val( d1+' / '+d2 );
                  }
               },
            onChangeMonthYear: function ( year, month, inst ) {
                  //prv = cur = -1;
               },
            onAfterUpdate: function ( inst ) {
                  $('<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" data-handler="hide" data-event="click">Done</button>')
                     .appendTo($('#jrange div .ui-datepicker-buttonpane'))
                     .on('click', function () { $('#jrange div').hide(); });
               }
         })
      .position({
            my: 'left top',
            at: 'left bottom',
            of: $('#jrange input')
         })
      .hide();
   $('#jrange input').on('focus', function (e) {
         var v = this.value,
             d;
         try {
            if ( v.indexOf(' / ') > -1 ) {
               d = v.split(' / ');
               prv = $.datepicker.parseDate( 'yy-mm-dd', d[0] ).getTime();
               cur = $.datepicker.parseDate( 'yy-mm-dd', d[1] ).getTime();
            } else if ( v.length > 0 ) {
               prv = cur = $.datepicker.parseDate( 'yy-mm-dd', v ).getTime();
            }
         } catch ( e ) {
            cur = prv = -1;
         }
         if ( cur > -1 )
            $('#jrange div').datepicker('setDate', new Date(cur));
         $('#jrange div').datepicker('refresh').show();
      });
});

  </script> 



