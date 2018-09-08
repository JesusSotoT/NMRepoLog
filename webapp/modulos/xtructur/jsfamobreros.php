<script>     
$(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          afterSubmit: function(response, otro){
            if(response.statusText=='OK'){
              return [true];
            }else{
              return [false,' Error al editar el registro '];
            }
          },
          closeAfterEdit:true,
          width: 480
      };
    
      id_partida=45;
     
      jQuery("#jq_alta_familia").jqGrid({
        url:'sql_jsfamobreros.php?q=3',
        datatype: "json",
        colNames:['Clave familia','Familia'],
        colModel:[
          {name:'clave_familia',index:'clave_familia', width:40, editable:false,
            editrules: {edithidden:false},
            hidden:false
          },
          {name:'familia',index:'familia', width:70, editable:true,
            editrules: {edithidden:false},
            hidden:false
          }
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        pager: '#jqp_alta_familia',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        multiselect: true,
        gridComplete: function(){
          var ids = jQuery("#jq_alta_familia").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_alta_familia').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_alta_familia").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_jsfamobreros.php",
        onCellSelect: function(rowid, iRow, iCol, e) {
          $(this).jqGrid('editGridRow', rowid, formEditingOptions);
        }


      });

      jQuery("#jq_alta_familia").jqGrid('navGrid',"#jqp_alta_familia",
        {edit:true,add:true,del:false,search:true,
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          afterSubmit: function(response, otro){
            if(response.statusText=='OK'){
              return [true];
            }else{
              return [false,' Error al editar el registro '];
            }
          },
          closeAfterEdit:true,
          width: 480
        },
        
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          afterSubmit: function(response, otro){
            if(response.statusText=='OK'){
              return [true];
            }else{
              return [false,' Error al guardar el registro '];
            }
          },
          closeAfterAdd:true,
          width: 480
        },
        {}, // settings for delete
        {onSearch: function(data){
          $("#jq_alta_familia").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_alta_familia").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_alta_familia', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_alta_familia").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
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
              <div class="navbar-brand" style="color:#333;">Alta familia obreros</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_alta_familia"></table>
                <div id="jqp_alta_familia"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

</body>
  