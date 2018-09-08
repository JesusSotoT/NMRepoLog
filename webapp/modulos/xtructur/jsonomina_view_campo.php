<?php
  $id_des=$_POST['nomi'];

  function week_bounds( $date, &$start, &$end ) {

    $date = strtotime( $date );
    // Find the start of the week, working backwards
    $start = $date;
    while( date( 'w', $start ) > 1 ) {
      $start -= 86400; // One day
    }
    // End of the week is simply 6 days from the start
    $end = date( 'Y-m-d', $start + ( 6 * 86400 ) );
    $start = date( 'Y-m-d', $start );
}
    $semana = strftime('%V');
    $ano = date('Y');
    week_bounds(date('Y-m-d'), $start, $end);

    include('conexiondb.php');
    if(!isset($_COOKIE['xtructur'])){
      echo 323; exit();
    }else{
        $cookie_xtructur = unserialize($_COOKIE['xtructur']);
        $id_obra = $cookie_xtructur['id_obra'];
    }

    $SQL = "SELECT id, cc FROM constru_cuentas_cc ORDER by id;";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
      while($row = $result->fetch_array() ) {
        $cc[]=$row;
      }
    }else{
      $cc=0;
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



$SQL = "SELECT correo_can from constru_config where id_obra='$id_obra';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
      $row = $result->fetch_array();
        $correocan=$row['correo_can'];
      }

    $SQL = "SELECT a.id, c.cargo, concat(e.nombre,' ',e.apellidos) as aut, a.estatus FROM constru_bit_nominaca a 
LEFT JOIN constru_altas b on b.id=a.id_aut 
LEFT JOIN constru_cuentas_cargo c on c.id=a.id_cc 
LEFT JOIN administracion_usuarios e on e.idempleado=a.id_aut 
LEFT JOIN constru_info_tdo d on d.id_alta=b.id where a.id_obra='$id_obra' AND a.id='$id_des';";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $cargo=$row['cargo'];
    $aut=$row['aut'];
  }else{
    $cargo='No hay cuenta de costo';
  }

?>
<script>     
    $(function() {
    $.ajax({
        url:'ajax.php',
        type: 'POST',
        //dataType: 'JSON',
        data: {opcion:'empleados',id_des:'<?php echo $id_des; ?>',edif:'<?php echo $id_edif; ?>'},
        success: function(r){
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','320px');
            $('.FormGrid select').css('width','328px');


            $('#diast').prop('disabled', true);
            $('#importedt').prop('disabled', true);
            //$('#importehr').prop('disabled', true);
            $('#subtotal1').prop('disabled', true);
            $('#totalpago').prop('disabled', true);
            $('#unidad').numeric(); 
            $('#startDate').prop('disabled', true);
            $('#endDate').prop('disabled', true);
            $('#startDate2').prop('disabled', true);
            $('#endDate2').prop('disabled', true);

            $( "input" ).each(function( index ) {
              attr = $( this ).attr('disabled');
            if (typeof attr !== typeof undefined && attr !== false) {
                $( this ).css('background','#e5e5e5');
            }
            });


          },
          closeAfterEdit:true,
        width: 500,
        recreateForm: true
      };

      option='proforma';
      jQuery("#row_proforma").jqGrid({
        url:'sql_jsonomina_campo.php?id_des=<?php echo $id_des; ?>',
        datatype: "json",
        colNames:['','Inicio','Fin','Empleado','Dias trabajados','Horas extras','Importe DT','Importe Hrs Extra','Descuento Inf','Finiquitos','Subtotal 1','Total de Pago'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:true},
          {name:'ano', index:'ano', width:80, sortable:false,editable:false, editoptions: {defaultValue: '<?php echo $ano; ?>'},editrules: {edithidden:false}, hidden:false},
          {name:'semana', index:'semana', width:90, sortable:false,editable:false, editoptions: {defaultValue: '<?php echo $semana; ?>'},editrules: {edithidden:false}, hidden:false},
          {name:'id_empleado',index:'id_empleado',stype: 'select', width:100, sortable:false,editable:false,edittype:"select",editoptions:{value:''},searchoptions:{sopt:['eq'], value:'' },
            editrules: {edithidden:false},
            hidden:false
          }, 
          {name:'diast', index:'diast', width:80, sortable:false,editable:true, 
            editoptions: {},
            editrules: {edithidden:false}, hidden:false
          },

          {name:'hre', index:'hre', width:80, sortable:false,editable:true, 
            editoptions: {},
            editrules: {edithidden:false}, hidden:false
          },

          {name:'importedt',index:'importedt', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'importehr',index:'importehr', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'descinf',index:'descinf', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'fini',index:'fini', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'subtotal1',index:'subtotal1', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'totalpago',index:'totalpago', width:100, sortable:false,editable:true,formatter:"number", summaryType:'sum'}

        ],

        gridComplete: function(){
          var ids = jQuery("#row_proforma").jqGrid('getDataIDs');
          var totalpago = $("#row_proforma").jqGrid('getCol','totalpago',false,'sum');
          $("#row_proforma").jqGrid('footerData','set',{subtotal1:'Total:',totalpago:totalpago});
          $("#total").val(totalpago);

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
        multiselect:true,
        editurl: 'sql_jsprenomina_tec1.php?sd=<?php echo $sd; ?>&ed=<?php echo $ed; ?>&q=3&sema=<?php echo $sema; ?>&id_des=<?php echo $id_des; ?>&sem=<?php echo $semana; ?>&edif=<?php echo $id_edif; ?>',

               
      });

      jQuery("#row_proforma").jqGrid('navGrid',"#prow_proforma",
        {edit:false,add:false,del:false,search:false},
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
    }
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

<div class="row">&nbsp;</div>

<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Datos del la nomina</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <div class="row">
  <div class="col-sm-3">
    <label>Solicito:</label><br>
    <?php echo $aut; ?>
  </div>
  <div class="col-sm-3">
    <label>Cuenta de cargo:</label><br>
    <?php echo $cargo; ?>
  </div>
  <?php if ($row['estatus']==0){ ?>
    <div class="col-sm-3">
     <button style="width:100%" class="btn btn-primary btn-xm pull-right" onclick="autorizarest('nomot',1);"> Autorizar</button>
    </div>
    <div class="col-sm-3">
      <?php if($correocan==0){ ?>
    <button style="width:100%" class="btn btn-danger btn-xm pull-right" onclick="autorizarest('nomot',2,0);"> Cancelar</button>
     <?php } ?>

     <?php if($correocan==1){ ?>

     <input type="button" value="Cancelar" style="width:100%" class="btn btn-danger btn-xm pull-right" data-toggle="modal" data-target="#mailmodal" >
     <?php } ?>

    </div>
  <?php } ?>
  <?php if ($row['estatus']==1){ ?>
    <div class="col-sm-6">
      <b><font color="green">Estimacion aceptada</font></b>
    </div>
  <?php } ?>
  <?php if ($row['estatus']==2){ ?>
    <div class="col-sm-6">
      <b><font color="#ff0000">Estimacion rechazada</font></b>
    </div>
  <?php } ?>
</div>
          
      </div><!-- ENd panel body -->
    </div>
    <div class="modal fade" id="mailmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancelación</h4>
      </div>
      <div class="modal-body">
        Motivo de Cancelación:<br>
        <textarea rows="4" cols="50" id='jus'></textarea>
        <input type='hidden' id='ide'>

         
      </div>
      <div class="modal-footer">
        <br><label id='lenvio' hidden='true'>'Enviando ...'</label>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      
<button onclick="autorizarest('nomot',2,1);"> Enviar y Cancelar</button>

      </div>
    </div>

     </div>
    </div>





