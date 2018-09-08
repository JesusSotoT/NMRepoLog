<?php
$mysqli->query("SET NAMES utf8");
  $SQL = "SELECT a.* FROM constru_famat a WHERE a.borrado=0 ORDER by nomfam asc;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $familias[]=$row;
    }
  }else{
    $familias=0;
  }
?>
<script>     
    $(function() {
    $.ajax({
    url:'ajax.php',
    type: 'POST',
    data: {opcion:'asign_fams'},
    success: function(r){
      jQuery("#jq_presupuesto").jqGrid({
        url:'sql_jsasignarfam.php?q=3',
        datatype: "json",
        colNames:['Naturaleza','Clave','Descripcion', 'U.M.', 'Cantidad', 'PU concurso', 'Importe', 'Familia'],
        colModel:[

          {name:'naturaleza',index:'naturaleza',stype: 'select', width:75, sortable:false,editable:true,edittype:"select",
            editoptions:{ value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional;No cobrable:No cobrable'
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional;No cobrable:No cobrable' }
          },
          {name:'codigo_clave',index:'codigo_clave', width:100, editable:true},
          {name:'descripcion',index:'descripcion', width:370, sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}, 
          {name:'unidtext',index:'unidtext', width:50, sortable:false,editable:true /*,formatter:verificaPres*/ }, 
          {name:'unidad',index:'unidad', width:100, sortable:false,editable:true},
          {name:'precio_costo',index:'precio_costo', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'total_costo',index:'(unidad*precio_costo)', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'familia',index:'id_familia', width:100, sortable:false,editable:true /*,formatter:verificaPres*/,stype: 'select', edittype:"select", searchoptions:{sopt:['eq'], value:'0:No asignada;'+r } }
        ],
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#jqp_presupuesto',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        multiselect:true,
        footerrow: true,
        gridComplete: function(){
          var ids = jQuery("#jq_presupuesto").jqGrid('getDataIDs');
          $("#jq_presupuesto").jqGrid('setColProp', 'precio_costo', { formatter:"text", });
          $("#jq_presupuesto").jqGrid('setColProp', 'precio_venta', { formatter:"text", });
          var sum_costo = $("#jq_presupuesto").jqGrid('getCol','total_costo',false,'sum');
          var sum_venta = $("#jq_presupuesto").jqGrid('getCol','total_venta',false,'sum');
          $("#jq_presupuesto").jqGrid('footerData','set',{precio_costo:'Total costo:',total_costo:sum_costo,precio_venta:'Total venta:',total_venta:sum_venta});
          for (var i = 0; i < ids.length; i++) 
          {
              var rowId = ids[i];
              var rowData = jQuery('#jq_presupuesto').jqGrid ('getRowData', rowId);
              if(rowData.unidtext==''){
                $('#jq_presupuesto').jqGrid('setRowData', rowId, {unidtext:' ',unidad:' ',precio_costo:' ',total_costo:' ' });
                $('tr#'+rowId).find('input').replaceWith(' ');
              }
             
          }
        },
        beforeSelectRow: function(rowid, e)
        {
          return(true);
          /*
            var rowData = $(this).jqGrid('getRowData', rowid);
           if(rowData.naturaleza=='Catalogo'){
              return false;
           }else{
              jQuery("#jq_presupuesto").jqGrid('resetSelection');
              return(true);
           }
           */
        },
        editurl: "sql_jsexplosion.php",
        
      });

      jQuery("#jq_presupuesto").jqGrid('navGrid',"#jqp_presupuesto",
        {edit:false,add:false,del:false,search:true},
        {},
        {},
        {},
        {onSearch: function(data){
          $("#jq_presupuesto").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_presupuesto").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         },
         width: 600
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_presupuesto', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_presupuesto").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
            },
            position: "last"
        });
 }
  });
     

    });

  </script>

  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Asignar familias a explosion de insumos</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_presupuesto"></table>
                <div id="jqp_presupuesto"></div>
            </div>
          </div>
        </div>
        <h4>Asignar insumos a familias</h4>
        <div class="row">
            <div class="col-sm-3 col-xs-8">
              <select id="val_fam" class="form-control">
                <option selected="selected" value="0">Seleccione familia</option>
                <?php 
                if($familias!=0){
                  foreach ($familias as $k => $v) { ?>
                    <option value="<?php echo $v['id']; ?>"><?php echo $v['nomfam']; ?></option>
                  <?php } ?>
                <?php }else{ ?>
                  <option value="0">No hay familias dadas de alta</option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-2 col-xs-4">
              <input  type="button" value="Guardar" style="cursor:pointer;" onclick="savemat();" class="btn btn-primary">
            </div>
        </div>
      </div>
    </div>

</body>

  
