<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="css/dialogo.css" type="text/css">
<style>
  @media print{
    #imprimir,#filtros,#excel, #botones
    {
      display:none;
    }
    #logo_empresa
    {
      display:block;
    }
    .table-responsive{
      overflow-x: unset;
    }
    #imp_cont{
      width: 100% !important;
    }
  }
  .btnMenu{
    border-radius: 0; 
    width: 100%;
    margin-bottom: 0.3em;
    margin-top: 0.3em;
  }
  .row
  {
      margin-top: 0.5em !important;
  }
  .titulo, h4, h3{
      background-color: #eee;
      padding: 0.4em;
  }
  .modal-title{
    background-color: unset !important;
    padding: unset !important;
  }
  .nmwatitles, [id="title"] {
    padding: 8px 0 3px !important;
    background-color: unset !important;
  }
  .select2-container{
    width: 100% !important;
  }
  .select2-container .select2-choice{
    background-image: unset !important;
    height: 31px !important;
  }
  .twitter-typeahead{
    width: 100% !important;
  }
  .tablaResponsiva{
      max-width: 100vw !important; 
      display: inline-block;
  }
  /*
  .table tr, .table td{
    border: none !important;
  }
  */
  .ms-container{
    width: 100% !important;
  }
  .ms-selectable, .ms-selection{
    margin-top: 1em;
  }

  .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: #fff;
}

.table-striped > tbody > tr:nth-child(2n) > td, .table-striped > tbody > tr:nth-child(2n) > th {
   background-color: #f7f7f7;
}
</style>
<?php
$servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $usuariobd = "nmdevel";
        $clavebd = "nmdevel";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
$strSqlG ="Select * from constru_notilog order by fecha desc;";
$objConG = mysqli_connect($servidor,$usuariobd , $clavebd,$bd );
      $result = mysqli_query($objConG, $strSqlG);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $vernoti[]=$row;
        }
      }else{
        $vernoti=0;
      }    
?>

<body>
  <div class="container" style="width:100%">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Log de actualizaciones</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <table>
              <tr>
                <td width="50">Inicio:</td>
                <td width="200">
                  <div id="jrangex" >
                  <input class="form-control" id="jrange" type="text">
                  </div>
                </td>
                <td width="40" style="padding-left:10px;">Fin:</td>
                <td width="200">
                  <div id="jrange2x" >
                  <input class="form-control" id="jrange2" type="text">
                  </div>
                </td>
              <td width="200" style="padding-left: 10px;">

                <button id="btngenl"  class="btn btn-primary btn-xm" onclick="buscanoti();"> Buscar</button>
              </td>
            </tr>
</table>
<div id='resultados' style="margin-top:20px;">
<table style="border: 1px solid #ccc;" id='notifi' class="table table-striped">
  <tr><th>Fecha</th><th>Observaciones</th><th>Modulo</th></tr>
<?php 
      if($vernoti!=0){
          foreach ($vernoti as $k => $v) {
                $v['fecha']=substr($v['fecha'],0,10);
           ?>
            <tr><td><?php echo $v['fecha']; ?></td><td><?php echo $v['observaciones'];?></td><td><?php echo $v['modulo'];?></td></tr>
          <?php } }?>
</table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
    	  


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
  $('#jrange').datepicker({ dateFormat: "yy-mm-dd" }); 
  $('#jrange2').datepicker({ dateFormat: "yy-mm-dd" }); 
   var cur = -1, prv = -1;
   $('#jrange div')
      .datepicker({
           
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
                     
                     $('#jrange input').val( d1 );
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

$(function() {
   var cur = -1, prv = -1;
   $('#jrange2 div')
      .datepicker({
           
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
                     $('#jrange2 input').val( dateText );
                  } else {
                     d1 = $.datepicker.formatDate( 'yy-mm-dd', new Date(Math.min(prv,cur)), {} );
                     
                     $('#jrange2 input').val( d1 );
                  }
               },
            onChangeMonthYear: function ( year, month, inst ) {
                  //prv = cur = -1;
               },
            onAfterUpdate: function ( inst ) {
                  $('<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" data-handler="hide" data-event="click">Done</button>')
                     .appendTo($('#jrange div .ui-datepicker-buttonpane'))
                     .on('click', function () { $('#jrange2 div').hide(); });
               }
         })
      .position({
            my: 'left top',
            at: 'left bottom',
            of: $('#jrange2 input')
         })
      .hide();

   $('#jrange2 input').on('focus', function (e) {
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
            $('#jrange2 div').datepicker('setDate', new Date(cur));
         $('#jrange2 div').datepicker('refresh').show();
      });
});

  </script> 
