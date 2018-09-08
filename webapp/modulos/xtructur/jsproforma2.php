  <script>     
    $(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','210px');
            $('.FormGrid input').numeric(); 
            $('#importe_pres').attr('disabled', true);
            $('#importe_presu_p').attr('disabled', true);

            $('#costo_directo').attr('disabled', true).currency();
            $('#indirecto_campo').attr('disabled', true).currency();
            $('#indirecto_oc').attr('disabled', true).currency();
            $('#utilidad').attr('disabled', true).currency();

            
            imppres=$('#importe_pres').val();

            $( "#costo_directo_p" ).keyup(function() {
                porval=$(this).val()/100;
                $("#costo_directo").val(porval*imppres).currency();
            });

            $( "#indirecto_campo_p" ).keyup(function() {
                porval=$(this).val()/100;
                $("#indirecto_campo").val(porval*imppres).currency();
            });

            $( "#indirecto_oc_p" ).keyup(function() {
                porval=$(this).val()/100;
                $("#indirecto_oc").val(porval*imppres).currency();
            });

            $( "#utilidad_p" ).keyup(function() {
                porval=$(this).val()/100;
                $("#utilidad").val(porval*imppres).currency();
            });
          },
        closeAfterEdit:true,
        width: 600,
        recreateForm: true
      };


      option='proforma';
      
      jQuery("#row_proforma").jqGrid({
        url:'sql_jsproforma2.php?q=3&option='+option,
        datatype: "json",
        colNames:['Costo directo','Costo directo %', 'Costo indirecto campo', 'Costo indirecto campo %','Costo indirecto OC','Costo indirecto OC %','Utilidad','Utilidad %','Importe presupuesto contractual (Sin IVA)','Importe presupuesto contractual (Sin IVA) %','Factor de salario real'],
        colModel:[
          {name:'costo_directo',index:'costo_directo', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'costo_directo_p', index:'costo_directo_p', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'indirecto_campo',index:'indirecto_campo', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'indirecto_campo_p', index:'indirecto_campo_p', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'indirecto_oc',index:'indirecto_oc', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'indirecto_oc_p',index:'indirecto_oc_p', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'utilidad',index:'utilidad', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"}, 
          {name:'utilidad_p',index:'utilidad_p', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"}, 
          {name:'importe_pres',index:'importe_pres', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"}, 
          {name:'importe_presu_p',index:'importe_presu_p', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"}, 
          {name:'factor_salario',index:'factor_salario', width:160, sortable:false,editable:true,sorttype:"float", formatter:"number"}

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
        multiselect:true,
        editurl: "sql_jsproforma2.php",
        
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

  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Proforma</div>
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
      </div>
    </div>

</body>

  
