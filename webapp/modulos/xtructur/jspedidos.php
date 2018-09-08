<?php
  $sestmp=time();
  $SQL = "SELECT a.*, concat('PROV-',b.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=5;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
  }
  $SQL = "SELECT a.*, concat('RT-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $tecnicos[]=$row;
    }
  }else{
    $tecnicos=0;
  }
?>
<script>     
$(function() {
  $('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 
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
sestmp=$('#sestmp').val();
  $.ajax({
    url:'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: {opcion:'proveedores_requis'},
    success: function(r){

      jQuery("#grid").jqGrid('navGrid','#pcrud',{});
      jQuery("#jq_pedidos").jqGrid({
        url:'sql_jspedidos.php?q=3&sestmp='+sestmp,
        datatype: "json",
        colNames:['Requisicion','Fecha de entrega requiscion'],
        colModel:[
          {name:'id_requisicion',index:'id_requisicion',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selecciona;'+r.requis },
            editoptions:{value:'0:Selecciona;'+r.requis
            }
          },
          {name:'fecha_entrega',index:'fecha_entrega',stype: 'select', width:70, sortable:true,editable:false
          }
         /* {name:"fecha_entrega",index:"fecha_entrega",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
            editoptions:{ 
              dataInit: function(el){ 
                setTimeout(function(){ 
                  $(el).datepicker({ dateFormat: "yy-mm-dd" }); 
                  
                }, 200); 
              }
            },
            editrules: {edithidden:false},
            hidden:false
          }*/
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#jqp_pedidos',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        width: "928",
        height: "300",
        multiselect: true,
        gridComplete: function(){
          var ids = jQuery("#jq_pedidos").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_pedidos').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_pedidos").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_jspedidos.php?sestmp="+sestmp,
        caption:"Pedidos"

      });

      jQuery("#jq_pedidos").jqGrid('navGrid',"#jqp_pedidos",
        {edit:false,add:true,del:true,search:true,
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();

            id_requi = $('#id_requisicion').val();
                  $('.FormData#lainfo_adic').remove();
                  if(id_requi>0){
                    $('.FormData#lainfo_adic_load_not').remove();
                    $('<tr rowpos="3" class="FormData" id="lainfo_adic_load">\
                        <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                        <td class="DataTD">&nbsp;\
                      Cargando...\
                        </td>\
                      </tr>').insertAfter('#tr_id_requisicion');

                    $.ajax({
                      url:'ajax.php',
                      type: 'POST',
                      dataType: 'JSON',
                      data: {opcion:'desc_requi',id_requi:id_requi},
                      success: function(r){
                        if(r.success==1){
                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Importe</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].importe+'">\
  </td>\
</tr>').insertAfter('#tr_id_requisicion');

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Descripcion</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].descripcion+'">\
  </td>\
</tr>').insertAfter('#tr_id_requisicion');
                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Cantidad</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].cantidad+'">\
  </td>\
</tr>').insertAfter('#tr_id_requisicion');

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Clave</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].clave+'">\
  </td>\
</tr>').insertAfter('#tr_id_requisicion');

                          $('.FormData#lainfo_adic_load').remove();


                        }else{

                          $('.FormData#lainfo_adic_load').remove();

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic_load_not">\
                              <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                              <td class="DataTD">&nbsp;\
                            Esta requisicion no tiene datos\
                              </td>\
                            </tr>').insertAfter('#tr_id_requisicion');

                          

                        }
                      }
                    });
                }

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
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          afterSubmit: function(response, otro){
            if(response.responseText=='RP'){
              return [false,' &nbsp; Esta requisicion ya esta agregada  '];
            }else{
              if(response.statusText=='OK'){
                return [true];
              }else{
                return [false,' &nbsp; Error al editar el registro '];
              }
            }
          },
          closeAfterAdd:true,
          width: 480
        },
        {width: 480}, // settings for delete
        {onSearch: function(data){
          $("#jq_pedidos").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_pedidos").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_pedidos', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_pedidos").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
            },
            position: "last"
        });

      function processAddEdit(response, postdata) {
          var success = true;
          var message = "aaa"
          var new_id = "1";
          return [success,message,new_id];
        }



    }
  });
});

  </script> 
<div style="float:left; width:700px;">
    <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
<div id="dtabla" style="float:left; width:700px; font-size:10px;">
    <table id="jq_pedidos"></table>
    <div id="jqp_pedidos"></div>
</div>
<div style="float:left; width:700px; margin:10px 0 0 -1px;">
  <fieldset style="width: 811px;">
    <legend>Generar pedido:</legend>
    <table style="font-size:11px;">
      <tr>
        <td>Fecha de entrega:</td>
        <td><input id="fecente" type="text"></td>
      </tr>
      <tr>
        <td>Proveedor:</td>
        <td>
          <select id="val_pro">
            <option selected="selected" value="0">Seleccione</option>
            
            <?php 
            if($proveedores!=0){
              foreach ($proveedores as $k => $v) { ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
              <?php } ?>
            <?php }else{ ?>
              <option value="0">No hay proveedores dados de alta</option>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Solicito:</td>
        <td>
          <select id="val_solicito">
            <option selected="selected" value="0">Seleccione</option>
            <?php 
            if($tecnicos!=0){
              foreach ($tecnicos as $k => $v) { ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
              <?php } ?>
            <?php }else{ ?>
              <option value="0">No hay tecnicos dados de alta</option>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td> <input  type="button" value="Generar Pedido" style="cursor:pointer;" onclick="generaPed(<?php echo $sestmp; ?>);"></td>
      </tr>

    </table>
  </fieldset>

</div>
</div>