<?php
 include('conexiondb.php');


    $idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $username_global=$row['username'];
  $id_username_global=$row['idempleado'];

  $id_des=$_POST['id_des'];
  $id_edif=0;
  $sd=$_POST['sd'];
  $ed=$_POST['ed'];
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

     $SQL = "SELECT id FROM constru_bit_nominadest where estatus!=2 and per_ini='$sd' AND per_fin='$ed' AND id_dest='$id_des' AND id_obra='$id_obra' limit 1;";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
      echo 'EXIST';
      exit();
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
?>
<script>  
totpago=1;   
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

      totale=$('#totale').val();
      
      jQuery("#row_proforma").jqGrid({
        url:'sql_jsprenomina.php?sd=<?php echo $sd; ?>&ed=<?php echo $ed; ?>&q=3&id_des=<?php echo $id_des; ?>&sem=<?php echo $semana; ?>&edif=<?php echo $id_edif; ?>',
        datatype: "json",
        colNames:['','Sem. Inicio','Sem. Fin','Area','Empleado','Dias trabajados','Horas extras','Dias fondo','Importe DT','Importe Hrs Extra','Importe DF','Descuento Inf','Finiquitos','Subtotal 1','Dif destajo','Total de Pago'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:true},
          {name:'startDate2', index:'startDate2', width:80, sortable:false,editable:true, editrules: {edithidden:false}, hidden:false},
          {name:'endDate2', index:'endDate2', width:90, sortable:false,editable:true, editrules: {edithidden:false}, hidden:false},
          {name:'area', index:'area', width:90, sortable:false,editable:false,editrules: {edithidden:false}, hidden:false},
          {name:'id_empleado',index:'id_empleado',stype: 'select', width:100, sortable:false,editable:false,edittype:"select",editoptions:{value:r},searchoptions:{sopt:['eq'], value:r },
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
          {name:'diasf', index:'diasf', width:80, sortable:false,editable:true, 
            editoptions: {},
            editrules: {edithidden:false}, hidden:false
          },

          {name:'importedt',index:'importedt', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'importehr',index:'importehr', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'impdf',index:'impdf', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'descinf',index:'descinf', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'fini',index:'fini', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'subtotal1',index:'subtotal1', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'difd',index:'difd', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'totalpago',index:'totalpago', width:100, sortable:false,editable:true,formatter:"number", summaryType:'sum'}
        ],
        gridComplete: function(){
          var ids = jQuery("#row_proforma").jqGrid('getDataIDs');
          var totalpago = $("#row_proforma").jqGrid('getCol','totalpago',false,'sum');
          var totaldd = $("#row_proforma").jqGrid('getCol','difd',false,'sum');
          var totalid = $("#row_proforma").jqGrid('getCol','importedt',false,'sum');
          var totalsub = $("#row_proforma").jqGrid('getCol','subtotal1',false,'sum');
          $("#row_proforma").jqGrid('footerData','set',{difd:totaldd,totalpago:totalpago,importedt:totalid,subtotal1:totalsub});
          $("#total").val(totalpago);
          totpago=totalpago;


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
        editurl: 'sql_jsprenomina.php?sd=<?php echo $sd; ?>&ed=<?php echo $ed; ?>&q=3&id_des=<?php echo $id_des; ?>&sem=<?php echo $semana; ?>&edif=<?php echo $id_edif; ?>&totpago='+totpago+'&totale='+totale,
  
               
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
    <div class="panel-title">Datos de la nomina</div>
    </div><!-- End panel heading -->

    <!-- Panel body -->
    <div class="panel-body" >
        
        <div class="row">
    <div class="col-sm-6">
 
      <div class="row">
        <div class="col-sm-12">
          <label>Solicito:</label>
          <div>
            <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
            </div>
            <input type='hidden' id="val_solicito" value='<?php echo $id_username_global; ?>'>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      
      <div class="row">
        <div class="col-sm-6">
          <label>Cuenta:</label>
          <select class="form-control" id="cmbcc" onchange="chcc();">
            <option selected="selected"  value="0">Selecciona</option>
            <?php 
            if($cc!=0){
              foreach ($cc as $k => $v) { ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['cc']; ?></option>
              <?php } ?>
            <?php }else{ ?>
              <option value="0">No hay cuentas dadas de alta</option>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-6">
          <label>Cuenta de costo:</label>
          <select class="form-control" id="chcosto" onchange="chcosto1();">
            <option selected="selected" value="0">Selecciona una cuenta de costo</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <label>Cuenta de cargo:</label>
          <select class="form-control" id="ccosto">
            <option selected="selected" value="0">Selecciona una cuenta de cargo</option>
          </select>
        </div>
        <div class="col-sm-6">
         
          <input id="total" type="hidden" value="0.00">
          

           <button style="width:100%" class="btn btn-primary btn-xm pull-right" onclick="generaNomina(<?php echo $id_des; ?>,'<?php echo $sd; ?>','<?php echo $ed; ?>',<?php echo $id_edif; ?>);"> Generar Nomina</button>


        </div>
      </div>
    </div>
  </div>
        
    </div><!-- ENd panel body -->
  </div>

  
