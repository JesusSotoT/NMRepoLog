<script>    
    $(function() {
      var um = $.ajax({
        url: "ajax.php?funcion=um",
        async: false,
        datatype: 'json'
      }).responseText;


$('#ingprecio').numeric();
      
      jQuery("#jq_asignacion").jqGrid({
        //url:'sql_jsonp.php?callback=?&qwery=longorders&idpre='+id_presupuesto,
        url:'sql_jspusubcontratos.php?q=3',
        datatype: "json",
        colNames:['Codigo','Naturaleza','Clave','Descripcion', 'U.M.', 'Cantidad', 'Precio unitario', 'Importe','PU destajos','PU subcontratos'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:false, editoptions:{defaultValue:0} },
          {name:'naturaleza',index:'naturaleza',stype: 'select', width:75, sortable:false,editable:false,edittype:"select",editoptions:{value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional'},searchoptions:{sopt:['eq'], value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional' }
          },
          {name:'codigo_clave',index:'codigo_clave', width:100, editable:false},
          {name:'descripcion',index:'descripcion', width:300, sortable:false,editable:false,edittype:"textarea", editoptions:{rows:"2",cols:"20"}
          }, 
          {name:'unidtext',index:'unidtext', width:50, sortable:false,editable:false /*,formatter:verificaPres*/ }, 

          {name:'unidad',index:'unidad', width:100, sortable:false,editable:false},
        //  {name:'corto',index:'corto', width:130, sortable:false,editable:true},
          
          {name:'precio_costo',index:'precio_costo', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number"},
          {name:'total_costo',index:'(unidad*precio_costo)', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'pu_destajo',index:'pu_destajo', width:120, sortable:false,editable:false,formatter:"number", summaryType:'sum'}, 
          {name:'pu_subcontrato',index:'pu_subcontrato', width:120, sortable:false,editable:true,formatter:"number", summaryType:'sum' }, 

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
        autowidth: true,
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
          //var sum_venta = $("#jq_asignacion").jqGrid('getCol','total_venta',false,'sum');
          $("#jq_asignacion").jqGrid('footerData','set',{precio_costo:'Total costo:',total_costo:sum_costo,precio_venta:'Total venta:',total_venta:sum_venta,pu_destajo:sum_pu_destajo,pu_subcontrato:sum_pu_subcontrato});

          for (var i = 0; i < ids.length; i++) 
          {
              var rowId = ids[i];
              

              var rowData = jQuery('#jq_asignacion').jqGrid ('getRowData', rowId);
              if(rowData.unidtext==''){
                $('#jq_asignacion').jqGrid('setRowData', rowId, {unidtext:'',unidad:' ',precio_costo:' ',total_costo:' ' });
                $('tr#'+rowId).find('input').replaceWith(' ');
              }
          }
        },
        beforeSelectRow: function(rowid, e)
        {
           var rowData = $(this).jqGrid('getRowData', rowid);
           if(rowData.unidtext==''){
              return false;
           }else{
              jQuery("#jq_presupuesto").jqGrid('resetSelection');
              return(true);
           }
           
        },
        editurl: "sql_jspusubcontratos.php",
        
        onCellSelect: function(id) {
          var rowData = $("#jq_asignacion").jqGrid('getRowData',id); 
          var desc = rowData['descripcion'];
          $("#rdesc").html(desc);
          $("#descripcion").css('display','block');
        }
      });
      //$("div.ui-jqgrid-sdiv").after($("div.ui-jqgrid-bdiv"));
      jQuery("#jq_asignacion").jqGrid('navGrid',"#jqp_asignacion",
        {edit:true,add:false,del:false,search:true},
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          width: 400,
          closeAfterEdit:true
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

  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Captura de PU Subcontratos autorizados</div>
          </div>
        </div>
        
          <div class="row">
            <div class="col-xs-12 tablaResponsiva">
              <div class="table-responsive" id="dtabla">
                  <table id="jq_asignacion"></table>
                  <div id="jqp_asignacion"></div>
              </div>
            </div>
          </div>
          <div class="row">&nbsp;</div>
          <div class="panel panel-default" >
          <!-- Panel Heading -->
          <div class="panel-heading">
          <div class="panel-title">Asignar precio</div>
          </div><!-- End panel heading -->

          <!-- Panel body -->
          <div class="panel-body" >
            <div class="row">
                <div class="col-sm-3 col-xs-8">
                  <input id="ingprecio" value="0" class="form-control">
                </div>
                <div class="col-sm-2 col-xs-4">
                  <input type="button" id="esdes" value="Asignar" style="cursor:pointer;" onclick="savepu(2);" class="btn btn-primary">
                </div>
            </div>
          </div>
          </div>


          
      </div>
    </div>

</body>

