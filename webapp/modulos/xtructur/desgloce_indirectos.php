<?php
  $SQL = "SELECT indirecto_campo FROM constru_proforma2 where id_obra='$idses_obra' and borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $cic2=$row['indirecto_campo'];
    $cic=number_format($row['indirecto_campo']);
  }else{
    $cic='No hay dato';
  }
?>
  <script>     
    $(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','320px');
            $('.FormGrid select').css('width','328px');
            $('#unidad').numeric(); 
          },
        afterSubmit: function(response, otro){
            if(response.responseText=='RP'){
              return [false,' &nbsp; El monto es mayor al costo indirecto de la proforma  '];
            }else{
              if(response.statusText=='OK'){
                return [true];
              }else{
                return [false,' &nbsp; Error al editar el registro '];
              }
            }
          },
          closeAfterEdit:true,
        width: 500,
        recreateForm: true
      };

      option='Desgloce indirectos';
      
      jQuery("#row_proforma").jqGrid({
        url:'sql_desgloce_indirectos.php?q=4',
        datatype: "json",
        colNames:['clave','Descripción','Porcentaje','Importe'],
        colModel:[
          
          {name:'clave',index:'clave', width:120, sortable:false,editable:false},
          {name:'descripcion', index:'descripcion', width:120, sortable:false,editable:false},
          {name:'porcentaje', index:'porcentaje', width:120, sortable:false,editable:false},
          {name:'importe',index:'importe', width:120, sortable:false,editable:true,sorttype:"float", formatter:"number", summaryType:'sum'},
          

        ],
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
        editurl: "sql_desgloce_indirectos.php",
        
        gridComplete: function(){
          var sum = $("#row_proforma").jqGrid('getCol','importe',false,'sum');
          $("#row_proforma").jqGrid('footerData','set',{descripcion:'Total: ',importe:sum});
        },
        
               
      });

      jQuery("#row_proforma").jqGrid('navGrid',"#prow_proforma",
        {edit:false,add:false,del:false,search:true},
        formEditingOptions,
        {closeAfterAdd:true},// settings for adding
        {},// settings for delete
        {closeAfterSearch:true} // settings for search
      ).jqGrid('navButtonAdd', '#prow_proforma', {
                caption: "Exportar a Excel",
                buttonicon: "ui-icon-export",
                  onClickButton: function() {
                        $("#row_proforma").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });

    });

    function cambiapor(id){
      indc='<?php echo $cic2; ?>';
      por=$('#cant_'+id).val();

      nv = (por/100)*indc;
      $('#'+id+' td:eq(3)').text(nv).currency();

      sumatotind();

      
     // alert(por);''
    }

    function sumatotind(){

      suma=0;
      sumap=0;
      $("#row_proforma tr").each(function() {
        id=$(this).attr('id');
        
        if(id>0){
          valin = $(this).find('#cant_'+id).val();
          valin=valin*1;
          sumap+=valin;
        }

        adf= $(this).find('td:eq(3)').text();
        adf=uf(adf);
        adf=adf*1;
        suma+=adf;

        
      });
      $('.ui-jqgrid-ftable tbody').find('tr td:eq(2)').text('%'+sumap).currency();
      $('.ui-jqgrid-ftable tbody').find('tr td:eq(3)').text(suma).currency();
    }
  </script>


  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Desgloce de indirectos</div>
          </div>
        </div>
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
        <div class="panel-title">Proforma</div>
        </div><!-- End panel heading -->

        <!-- Panel body -->
        <div class="panel-body" >
          <div class="row">
          <div class="col-sm-12">
            Costo indirecto de campo: <b>$<?php echo $cic; ?></b>
          </div>
          </div>
          <div class="row" style="padding: 5px 0 0 0;">
          <div class="col-sm-12">

            <button id="btngenped"  class="btn btn-primary " onclick="guardaDesglo(<?php echo $cic2; ?>)">Guardar desgloce</button>

          </div>
          </div>
          </div><!-- ENd panel body -->
        </div>

        
      </div>
    </div>

</body>


  
  

