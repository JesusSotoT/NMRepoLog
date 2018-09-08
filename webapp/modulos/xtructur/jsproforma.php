  <script>     
    $(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','320px');
            $('.FormGrid select').css('width','328px');
            $('#unidad').numeric(); 
          },
          closeAfterEdit:true,
        width: 500,
        recreateForm: true
      };

      option='proforma';
      jQuery("#grid").jqGrid('navGrid','#pcrud',{});
      jQuery("#row_proforma").jqGrid({
        url:'sql_jsproforma.php?q=3&option='+option,
        datatype: "json",
        colNames:['','% De utilidad','$ de utilidad', '% De indirecto', '$ de indirecto','Factor de salario real'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:true},
          {name:'por_utilidad',index:'por_utilidad', width:120, sortable:false,editable:true},
          {name:'de_utilidad', index:'de_utilidad', width:120, sortable:false,editable:true},
          {name:'por_indirecto',index:'por_indirecto', width:120, sortable:false,editable:true},
          {name:'de_indirecto', index:'de_indirecto', width:120, sortable:false,editable:true},
          {name:'factor_salario',index:'factor_salario', width:120, sortable:false,editable:true}, 

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
        editurl: "sql_jsproforma.php",
        caption:"Proforma"
               
      });

      jQuery("#row_proforma").jqGrid('navGrid',"#prow_proforma",
        {edit:true,add:false,del:false,search:true},
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
<div id="dtabla" style="float:left; width:700px; font-size:10px; ">
    <table id="row_proforma"></table>
    <div id="prow_proforma"></div>
</div>

