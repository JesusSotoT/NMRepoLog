<?php
  $idremesa=$_POST['idremesa'];
?>
  <script>     
    $(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','210px');
           // $('.FormGrid input').numeric(); 
          },
        closeAfterEdit:true,
        width: 600,
        recreateForm: true
      };

      idrem=$('#idrem').val();
      option='proforma';
      jQuery("#grid").jqGrid('navGrid','#pcrud',{});
      jQuery("#row_proforma").jqGrid({
        url:'sql_remesas.php?q=3&idremesa='+idrem+'&option='+option,
        datatype: "json",
        colNames:['No. de cheque','Validacion de cheques', 'Banco', 'Fecha de expedicion','Estatus cheque','Estatus factura'],
        colModel:[
          {name:'no_cheque',index:'no_cheque', width:160, sortable:false,editable:true},
          {name:'validacion_cheque', index:'validacion_cheque', width:160, sortable:false,editable:true},
          {name:'banco',index:'banco', width:160, sortable:false,editable:true},
          {name:'fecha_expedicon', index:'fecha_expedicon', width:160, sortable:false,editable:true},
          {name:'estatus_cheque',index:'estatus_cheque', width:160, sortable:false,editable:true},
          {name:'estatus_factura',index:'estatus_factura', width:160, sortable:false,editable:true}

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
        width: "928",
        footerrow: true,
        multiselect:true,
        editurl: "sql_remesas.php?idremesa="+idrem+"",
        caption:"Proforma",
        shrinkToFit: false
               
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

    });
  </script>
  <input id="idrem" type="hidden" value="<?php echo $idremesa; ?>">
<div id="dtabla" style="float:left; width:700px; font-size:10px; ">
    <table id="row_proforma"></table>
    <div id="prow_proforma"></div>
</div>

