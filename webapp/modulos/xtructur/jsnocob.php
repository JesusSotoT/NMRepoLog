<?php
date_default_timezone_set('America/Mexico_City');
  $sestmp=time();
  $fecha=date('Y-m-d H:i:s');
  if(!session_id()){
    session_start();
  }else{
    $_SESSION['req']=$sestmp;
  }

  $SQL = "SELECT a.id, a.nombre FROM constru_agrupador a where a.id_obra='$idses_obra' AND a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $agrupadores[]=$row;
    }
  }else{
    $agrupadores=0;
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
<input type='hidden' id='fecha' value='<?php echo $fecha;?>'>

<script>     
$(function() {

  $('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 

      var formEditingOptions = {

        beforeShowForm: function(form){ 
           
             
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#Unidad').numeric(); 
            $('#precio').numeric();  

          


            $( "input" ).each(function( index ) {
              attr = $( this ).attr('disabled');
            if (typeof attr !== typeof undefined && attr !== false) {
                $( this ).css('background','#e5e5e5');
            }
            });
          },
          afterSubmit: function(response, otro){
            console.log(response);
            console.log(otro);
            if(response.responseText=='OK'){
              return [true];
            }else if(response.responseText=='RP'){
              return [false,' Elemento repetido '];
            }else{
              return [false,' Error al editar el registro '];
            }
          },
          closeAfterEdit:true,
          width: 480
      };

  $.ajax({
    url:'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: {opcion:'contratista_insumos_x',sestmp:'<php echo $sestmp; ?>'},
    success: function(r){
      sestmp=$('#sestmp').val();
      jQuery("#jq_requisiciones").jqGrid({
        url:'sql_jsvisualizarnocob.php?q=3&sestmp='+sestmp,
        datatype: "json",
        colNames:['Codigo','idrecurso','Naturaleza','Descripcion','Justificacion','Unidad', 'Cantidad', 'P.U. Concurso', 'Importe','rid'],
        colModel:[
        {name:'Codigo',index:'codigo', hidden:true, stype: 'select', width:70, sortable:true, editable:true, editrules: {edithidden:true},
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;t:Todas;'+r.familias },
            
          },
          {name:'idrecurso',index:'id_bit_solicitud',hidden:true,stype: 'select', width:70, sortable:true,editable:false,edittype:"text",
            editrules: {edithidden:true},
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona' },
          },

          {name:'Naturaleza',index:'naturaleza', width:70, editable:false,
            editrules: {},
            hidden:false
          },
          {name:'Descripcion',index:'descripcion', width:70, editable:true,
            editrules: {edithidden:false},
            hidden:false
          },
       
          {name:'Unidad',index:'unidtext', width:70, editable:true,
            editrules: {edithidden:false},
            hidden:false
          },

             {name:'Justificacion',index:'justificacion', width:70, 
            editrules: {edithidden:true},
            hidden:true,
            editable:true
          },
          {name:'Cantidad',index:'Unidad', width:70, editable:true,
            editrules: {edithidden:false},  formatter:"number",
            hidden:false
          },
           {name:'precio',index:'Precio', width:70, editable:true,
            editrules: {edithidden:false},  formatter:"number",
            hidden:false
          },
          {name:'Importe',index:'importe', width:70, editable:false,
            editrules: {edithidden:false},  formatter:"number",
            hidden:false
          },
          {name:'rid',index:'rid', width:70, editable:false,
            editrules: {edithidden:true},
            hidden:true
          },
          
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#jqp_requisiciones',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        width: "1035",
        height: "300",
        multiselect: true,
        gridComplete: function(){

         var sum = $("#jq_requisiciones").jqGrid('getCol','Importe',false,'sum');

           $("#total").val(sum);

          
          var ids = jQuery("#jq_requisiciones").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_requisiciones').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_requisiciones").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_jsvisualizarnocob.php?sestmp="+sestmp,
        caption:"Recursos",
        onCellSelect: function(rowid, iRow, iCol, e) {


        }


      });
      
      jQuery("#jq_requisiciones").jqGrid('navGrid',"#jqp_requisiciones",
        {edit:true,add:true,del:true,search:false,
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#cantidad').numeric();  

            id_insumo = $('#id_clave').val();
                  $('.FormData#lainfo_adic').remove();
                  if(id_insumo>0){
                    $('.FormData#lainfo_adic_load_not').remove();
                    $('<tr rowpos="3" class="FormData" id="lainfo_adic_load">\
                        <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                        <td class="DataTD">&nbsp;\
                      Cargando...\
                        </td>\
                      </tr>').insertAfter('#tr_id_clave');


                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'desc_insumos',id_insumo:id_insumo},
                    success: function(r){
                      if(r.success==1){

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Precio</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].precio+'">\
  </td>\
</tr>').insertBefore('#tr_fecha_entrega');

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Descripcion</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].descripcion+'">\
  </td>\
</tr>').insertAfter('#tr_cantidad');
                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Unidad</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].unidtext+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');

                       $('.FormData#lainfo_adic_load').remove();


                        }else{
                          $('.FormData#lainfo_adic_load').remove();

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic_load_not">\
                              <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                              <td class="DataTD">&nbsp;\
                            No hay datos\
                              </td>\
                            </tr>').insertAfter('#tr_id_clave');

                          

                        }
                      }
                    });
                  }
          },
          afterSubmit: function(response, otro){

            if(response.responseText=='OK'){
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
             $('#Cantidad').numeric();
              $('#precio').numeric(); 
          },
          beforeSubmit: function(postdata, formid){

            vol_tope=$('#totcant').val();
            vvol=$('#volant').val();
            volest=$('#cantidad').val();
            $('#vol_anterior').val(vvol);
            nopasar=(volest*1)+(vvol*1);
            //alert(nopasar);
            
            if( (nopasar*1)>(vol_tope*1) ){
              return [false,' &nbsp; El volumen es mayor al tope permitido'];
            }else if(postdata.cantidad==0 || postdata.cantidad==''){
              return [false,' &nbsp; La cantidad es incorrecta'];
            }else{
              return [true,''];
            }
          },
          afterSubmit: function(response, otro){
            if(response.responseText=='RP'){
              return [false,' &nbsp; Este insumo ya esta agregado  '];
            }
else if(response.responseText=='NO0'){
              return [false,' &nbsp; La cantidad tiene que ser mayor a 0  '];
            }
            else{
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
          $("#jq_requisiciones").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_requisiciones").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_requisiciones', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_requisiciones").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
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
  <div class="row">
    <div class="col-xs-12 tablaResponsiva">
      <div class="table-responsive" id="dtabla">
          <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
          <table id="jq_requisiciones"></table>
          <div id="jqp_requisiciones"></div>
      </div>
    </div>
  </div>
  <h4>No Cobrables</h4>
  <div class="row">
      <div class="col-sm-6">
        <h5>Datos generales</h5>
        <div class="row">
          <div class="col-sm-6">
            <label>Solicito:</label>
            <div>
            <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
            </div>
            <input type='hidden' id="iduserlog" value='<?php echo $id_username_global; ?>'>
            <!--
            <select id="val_solicito" class="form-control">
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
            -->
          </div>
          
        <div class="row">
          <div class="col-sm-6">
      <label>Total:</label>
      <input class="form-control" id="total" type="text" value="0" disabled="disabled">
    </div>
          <div class="col-sm-4 col-sm-offset-8">
            <input class="btn btn-primary btnMenu" id="btnGenReq" type="button" value="Generar Solicitud" style="cursor:pointer;" onclick="generanocob(<?php echo $sestmp; ?>);">
          </div>
        </div>
      </div>
  </div>