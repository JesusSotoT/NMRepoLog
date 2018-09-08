<?php
  $id_des=$_POST['id_des'];
  $sd=$_POST['sd'];
  $ed=$_POST['ed'];
  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }

  $SQL = "SELECT d.departamento, if(a.oc_inst=2,'Instalaciones','Obra civil') as especialidad FROM constru_altas a
  LEFT JOIN constru_deptos d on d.id=a.id_depto 
  where a.id='$id_des' AND a.id_obra='$id_obra' and a.borrado=0 ORDER BY a.id desc LIMIT 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $par=utf8_encode($row['departamento']);
    $esp=utf8_encode($row['especialidad']);
  }else{
    $par='No se selecciono departamento';
    $esp='No se selecciono especialidad';
  }
  
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
?>
<script>
scrollPosition = 0;     
    $(function() {
      $('#esph').css('visibility','visible');
      $('#esptd').html('<b><?php echo $par; ?></b>');
      $('#partd').html('<b><?php echo $esp; ?></b>');
    $.ajax({
        url:'ajax.php',
        type: 'POST',
        //dataType: 'JSON',
        data: {opcion:'empleados',id_des:'<?php echo $id_des; ?>'},
        success: function(r){
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','120px');
            $('.FormGrid select').css('width','128px');
            $('#startDate').prop('disabled', true);
            $('#endDate').prop('disabled', true);
            $('#startDate2').prop('disabled', true);
            $('#endDate2').prop('disabled', true);
            $('#unidad').numeric(); 

          },
          afterSubmit : function (resp, postdata){
            scrollPosition = jQuery("#row_proforma").closest(".ui-jqgrid-bdiv").scrollTop();
            return [true,"",""];
            //$("#griditems").trigger("reloadGrid");
          },
          beforeSubmit: function(postdata, formid){

            s = jQuery("#row_proforma").jqGrid('getGridParam','selarrrow');
            postdata.multipleids=s;

            return [true,''];
            
          },
          closeAfterEdit:true,
        width: 500,
        recreateForm: true
      };

      option='proforma';
      jQuery("#row_proforma").jqGrid({
        url:'sql_jstomaduria.php?sd=<?php echo $sd; ?>&ed=<?php echo $ed; ?>&q=3&id_des=<?php echo $id_des; ?>&sem=<?php echo $semana; ?>',
        datatype: "json",
        colNames:['','Sem. Inicio','Sem. Fin','Area','Empleado','Lunes','Martes', 'Miercoles', 'Jueves','Viernes','Sabado'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:true},
          {name:'startDate2', index:'startDate2', width:80, sortable:false,editable:true, editrules: {edithidden:false}, hidden:false},
          {name:'endDate2', index:'endDate2', width:90, sortable:false,editable:true, editrules: {edithidden:false}, hidden:false},
          {name:'area', index:'area', width:90, sortable:false,editable:false, 
            editrules: {edithidden:false}, hidden:false},
          {name:'id_empleado',index:'id_empleado',stype: 'select', width:400, sortable:false,editable:false,edittype:"select",editoptions:{value:r},searchoptions:{sopt:['eq'], value:r },
            editrules: {edithidden:false},
            hidden:false
          }, 
          {name:'lun', index:'lun', width:80, sortable:false,editable:true,
            edittype: 'checkbox', editoptions: { value: "1:0", }, 
            formatter: "checkbox", formatoptions: { disabled: true} 
          },
          {name:'mar',index:'mar', width:80, sortable:false,editable:true,
            edittype: 'checkbox', editoptions: { value: "1:0" }, 
            formatter: "checkbox", formatoptions: { disabled: true} 
          },
          {name:'mie', index:'mie', width:80, sortable:false,editable:true,
            edittype: 'checkbox', editoptions: { value: "1:0" }, 
            formatter: "checkbox", formatoptions: { disabled: true} 
          },
          {name:'jue',index:'jue', width:80, sortable:false,editable:true,
            edittype: 'checkbox', editoptions: { value: "1:0" }, 
            formatter: "checkbox", formatoptions: { disabled: true} 
          },
          {name:'vie',index:'vie', width:80, sortable:false,editable:true,
            edittype: 'checkbox', editoptions: { value: "1:0" }, 
            formatter: "checkbox", formatoptions: { disabled: true} 
          },
          {name:'sab',index:'sab', width:80, sortable:false,editable:true,
            edittype: 'checkbox', editoptions: { value: "1:0" }, 
            formatter: "checkbox", formatoptions: { disabled: true} 
          }

        ],
        gridComplete: function(){
          jQuery("#row_proforma").closest(".ui-jqgrid-bdiv").scrollTop(scrollPosition);
        },
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
        autowidth: true,
        footerrow: true,
        multiselect:true,
        editurl: 'sql_jstomaduria.php?sd=<?php echo $sd; ?>&ed=<?php echo $ed; ?>&q=3&id_des=<?php echo $id_des; ?>&sem=<?php echo $semana; ?>',

               
      });
      
      //$("#mygrid").trigger("reloadGrid",[{current:true}]);
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

