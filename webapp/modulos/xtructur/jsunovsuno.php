<script>     
    $(function() {
      /*
        var gridData = getGridData(1000);
            $("#testGrid").jqGrid({
                data: gridData,
                height: 'auto',
                datatype: 'local',
                gridview: true,
*/

      jQuery("#jq_presupuesto").jqGrid({
        //url:'sql_jsonp.php?callback=?&qwery=longorders&idpre='+id_presupuesto,
        url:'sql_jsunovsuno.php?q=3',
        datatype: "json",
        colNames:['Naturaleza','Clave','Descripcion', 'U.M.', 'Cant. contractual', 'Cant. Estimada', 'Cant. Pagada', 'Diferencia'],
        colModel:[
          {name:'naturaleza',index:'naturaleza',stype: 'select', width:75, sortable:false,editable:true,edittype:"select"
          },
          {name:'codigo_clave',index:'codigo_clave', width:100, editable:true},
          {name:'descripcion',index:'descripcion', width:400, sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}
          }, 
          {name:'unidtext',index:'unidtext', width:50, sortable:false,editable:true /*,formatter:verificaPres*/ }, 

          {name:'Cant. contractual',index:'Cant. contractual', width:100, sortable:false,editable:true},
          
          {name:'Cant. Estimada',index:'Cant. Estimada', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Cant. Pagada',index:'Cant. Pagada', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Diferencia',index:'Diferencia', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"}
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#jqp_presupuesto',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        width: "1035",
        height: "300",
        footerrow: false,
       gridview: true,
       hoverrows: false,
        caption:"Reporte uno vs uno",

      });
      //$("div.ui-jqgrid-sdiv").after($("div.ui-jqgrid-bdiv"));
      jQuery("#jq_presupuesto").jqGrid('navGrid',"#jqp_presupuesto",
        {edit:false,add:false,del:false,search:true},
        {},
        {},
        {},
        {}, // settings for delete
        {onSearch: function(data){
          $("#jq_presupuesto").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_presupuesto").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_presupuesto', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_presupuesto").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
            },
            position: "last"
        });


});


  </script>

  <div class="row">
    <div class="col-xs-12 tablaResponsiva">
      <div class="table-responsive" id="dtabla">
          <table id="jq_presupuesto"></table>
          <div id="jqp_presupuesto"></div>
      </div>
    </div>
  </div> 
