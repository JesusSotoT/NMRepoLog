<script>    
    $(function() {

      jQuery("#grid").jqGrid('navGrid','#pcrud',{});
      jQuery("#jq_asignacion").jqGrid({
        //url:'sql_jsonp.php?callback=?&qwery=longorders&idpre='+id_presupuesto,
        url:'sql_jsvisualizarpcontrol.php?q=3',
        datatype: "json",
        colNames:['Codigo','Naturaleza','Clave','Descripcion', 'U.M.', 'Cantidad', 'Precio unitario', 'Importe','PU Destajo','PU Subcontrato'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:true, editoptions:{defaultValue:0} },
          {name:'naturaleza',index:'naturaleza',stype: 'select', width:75, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional;No cobrable:No cobrable'},searchoptions:{sopt:['eq'], value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional;No cobrable:No cobrable' }
          },
          {name:'codigo_clave',index:'codigo_clave', width:100, editable:true},
          {name:'descripcion',index:'descripcion', width:380, sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}
          }, 
          {name:'unidtext',index:'unidtext', width:50, sortable:false,editable:true /*,formatter:verificaPres*/ }, 

          {name:'unidad',index:'unidad', width:100, sortable:false,editable:true},
        //  {name:'corto',index:'corto', width:130, sortable:false,editable:true},
          
          {name:'precio_costo',index:'precio_costo', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'total_costo',index:'(unidad*precio_costo)', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'pu_destajo',index:'pu_destajo', width:120, sortable:false,editable:true,formatter:"number", summaryType:'sum'}, 
          {name:'pu_subcontrato',index:'pu_subcontrato', width:120, sortable:false,editable:false,formatter:"number", summaryType:'sum'}

         // {name:'precio_venta',index:'precio_venta', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
         // {name:'total_venta',index:'(unidad*precio_venta)', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'}  
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,
        pager: '#jqp_asignacion',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        width: "928",
        height: "300",
        footerrow: true,
        multiselect: true,

        gridComplete: function(){
          var ids = jQuery("#jq_asignacion").jqGrid('getDataIDs');

          
         /* for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            be = '<a href="../../modulos/construccion/proy_recurso.php?id_partida=0"><img src="jqgrid/images/folder_open.png" style="cursor:pointer;" ></a>'; 
            jQuery("#rowed2").jqGrid('setRowData',ids[i],{icon:be});
          }
          */
          $("#jq_asignacion").jqGrid('setColProp', 'precio_costo', { formatter:"text", });
          $("#jq_asignacion").jqGrid('setColProp', 'precio_venta', { formatter:"text", });
          var sum_costo = $("#jq_asignacion").jqGrid('getCol','total_costo',false,'sum');
          var sum_venta = $("#jq_asignacion").jqGrid('getCol','total_venta',false,'sum');
          var sum_pu_destajo = $("#jq_asignacion").jqGrid('getCol','pu_destajo',false,'sum');
          var sum_pu_subcontrato = $("#jq_asignacion").jqGrid('getCol','pu_subcontrato',false,'sum');
          $("#jq_asignacion").jqGrid('footerData','set',{precio_costo:'Total costo:',total_costo:sum_costo,precio_venta:'Total venta:',total_venta:sum_venta,pu_destajo:sum_pu_destajo,pu_subcontrato:sum_pu_subcontrato});

          for (var i = 0; i < ids.length; i++) 
          {
              var rowId = ids[i];
              

              var rowData = jQuery('#jq_asignacion').jqGrid ('getRowData', rowId);
              if(rowData.unidtext==''){
                $('#jq_asignacion').jqGrid('setRowData', rowId, {unidtext:' ',unidad:' ',precio_costo:' ',total_costo:' ' });
                $('tr#'+rowId).find('input').replaceWith(' ');
              }
          }
        },
        editurl: "sql_jsvisualizarpcontrol.php",
        caption:"Recurso",
        onCellSelect: function(id) {
          var rowData = $("#jq_asignacion").jqGrid('getRowData',id); 
          var desc = rowData['descripcion'];
          $("#rdesc").html(desc);
          $("#descripcion").css('display','block');
        }
      });
      //$("div.ui-jqgrid-sdiv").after($("div.ui-jqgrid-bdiv"));
      jQuery("#jq_asignacion").jqGrid('navGrid',"#jqp_asignacion",
        {edit:false,add:false,del:false,search:true},
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          width: 400
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();
          },
          closeAfterAdd:true,
          width: 480
        },
        {}, // settings for delete
        {onSearch: function(data){
          $("#jq_asignacion").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_asignacion").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_asignacion', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_asignacion").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
            },
            position: "last"
        });


});


  </script> 


<div style="float:left; width:700px;">
  <div id="dtabla" style="float:left; width:700px; font-size:10px;">
    <table id="jq_asignacion"></table>
    <div id="jqp_asignacion"></div>
  </div>
</div>

