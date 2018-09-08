<style>
.p0{
        margin: 0px;
    }
</style>
<?php
  $obra_sal=$_POST['obra_sal'];
  $obra_ent=$_POST['obra_ent'];
  $sestmp=time();

   include('conexiondb.php');


    $idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $username_global=$row['username'];
  $id_username_global=$row['idempleado'];


  $SQL = "SELECT concat(nombre,' ',apellidos) as nombre, idempleado as id from administracion_usuarios;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $maestros[]=$row;
    }
  }else{
    $maestros=0;
  }

?>

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
            console.log(response);
            console.log(otro);
            if(response.statusText=='OK'){
              return [true];
            }else if(response.statusText=='RP'){
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
    data: {opcion:'traeme_obras_scookie',obra_sal:'<?php echo $obra_sal; ?>'},
    success: function(r){
      sestmp=$('#sestmp').val();
      jQuery("#jq_requisiciones").jqGrid({
        url:'sql_traspasos.php?q=3&sestmp='+sestmp+'&obra_sal=<?php echo $obra_sal; ?>&obra_ent=<?php echo $obra_ent; ?>',
        datatype: "json",
        colNames:['Material','Clave','Unidad','Cantidad','Descripcion'],
        colModel:[
        {name:'id_material',index:'id_material', hidden:true, stype: 'select', width:70, sortable:true, editable:true,edittype:"select", editrules: {edithidden:true},
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;t:Todas;'+r.familias },
            editoptions:{value:'0:Selecciona;t:Todas;'+r.familias,
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_material = $(this).val();
                  obra_sal='<?php echo $obra_sal; ?>';
                  $('.FormData#lainfo_adic').remove();
                  if(id_material>0 || id_material=='t'){
                    $('#id_clave').html('<option value="0">Cargando...</option>');
                    $.ajax({
                      url:'ajax.php',
                      type: 'POST',
                      dataType: 'JSON',
                      data: {opcion:'desc_insumos_mat_obra',id_material:id_material,obra_sal:obra_sal},
                      success: function(r){
                        if(r.success==1){
                          $('#id_clave').html('<option value="0">Seleccione</option>');
                          $.each(r.datos, function( k, v ) {
                            $('#id_clave').append('<option value="'+v.id+'">'+v.clave+'</option>');
                          });
                        }else{
                          $('#id_clave').html('<option value="0">No hay insumos</option>');
                        }
                      }
                    });
                  }
              } 
            }]
          }
          },
          {name:'id_clave',index:'id_clave',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona' },
            editoptions:{value:'0:Selecciona',
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_insumo = $(this).val();
                  obra_sal='<?php echo $obra_sal; ?>';
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
                    data: {opcion:'desc_insumos_22',id_insumo:id_insumo,obra_sal:obra_sal},
                    success: function(r){
                      console.log(r.datos);
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

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Cantidad en almacen</font></td>\
  <td class="DataTD">&nbsp;\
<input id="totcant" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos.almacen+'">\
  </td>\
</tr>').insertAfter('#tr_cantidad');

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
                } 
              }]
            }
          },

          {name:'unidad',index:'unidad', width:70, editable:false,
            editrules: {edithidden:true},
            hidden:false
          },

          {name:'cantidad',index:'cantidad', width:70, editable:true,
            editrules: {edithidden:true},
            hidden:false
          },
          {name:'descripcion',index:'descripcion', width:70, editable:false,
            editrules: {edithidden:true},
            hidden:false
          }
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#jqp_requisiciones',
        sortname: 'id',
        viewrecords: true,
        autowidth: true, 
        sortorder: "desc",
        height: "300",
        multiselect: true,
        gridComplete: function(){
          
          var ids = jQuery("#jq_requisiciones").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_requisiciones').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_requisiciones").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_traspasos.php?q=3&sestmp="+sestmp+"&obra_sal=<?php echo $obra_sal; ?>&obra_ent=<?php echo $obra_ent; ?>",
    
        onCellSelect: function(rowid, iRow, iCol, e) {


        }


      });
      
      jQuery("#jq_requisiciones").jqGrid('navGrid',"#jqp_requisiciones",
        {edit:false,add:true,del:true,search:false,
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
            $('#cantidad').numeric(); 
          },
          beforeSubmit: function(postdata, formid){

            vol_tope=$('#totcant').val();
            vvol=0;
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
            }else{
if(response.responseText=='RPN'){
              return [false,' &nbsp; No se permiten cantidades negativas  '];
            }

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
                $("#jq_requisiciones").jqGrid('exportarExcelCliente',{nombre:"traspasos",formato:"excel"});
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


          <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
          <table id="jq_requisiciones"></table>
          <div id="jqp_requisiciones"></div>

       
      <div class="row">
      <div class="col-sm-12">

        <div class="row">
          <div class="row p0">
            <div class="col-sm-12">

            <div class="panel panel-default" >
          
          <!-- Panel Heading -->
          <div class="panel-heading">
            <div class="panel-title">Datos del traspaso</div>
          </div><!-- End panel heading -->
          
          <!-- Panel body -->
          <div class="panel-body" >
              <label>Solicito:</label>
              <div>
              <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
              </div>
              <input type='hidden' id="iduserlog" value='<?php echo $id_username_global; ?>'>

           

          <div class="row" style="padding:0px;">
              <div class="col-sm-6">
                <label>Quien entrega obra salida:</label>
               <select class="form-control" id="resalida">
              <option selected="selected" value="0">Seleccione un usuario</option>
              <?php 
              if($maestros!=0){
                foreach ($maestros as $k => $v) { ?>
                  <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
                <?php } ?>
              <?php }else{ ?>
                <option value="0">No hay usuarios dados de alta</option>
              <?php } ?>
              </select>
            </div>
          
              <div class="col-sm-6">
                  <label>Quien recibe obra entrada:</label>
                  <select class="form-control" id="reentrada">
                <option selected="selected" value="0">Seleccione un usuario</option>
                <?php 
                if($maestros!=0){
                  foreach ($maestros as $k => $v) { ?>
                    <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
                  <?php } ?>
                <?php }else{ ?>
                  <option value="0">No hay usuarios dados de alta</option>
                <?php } ?>
              </select>

            </div>
          </div>

          <div class="row" style="padding:0px;">
            <div class="col-sm-6">
                <label>Fecha de envio:</label>
              <div class="week-picker" style="width:100% !important;"></div>
              <input class="form-control" id="fenvio">
            </div>
          
          </div>


          <div class="row" style="padding:15px 0 0 0;">
              <div class="col-sm-6">
                <button id="btnGenReq2" class="btn btn-primary btn-sx" onclick="guardaTraspaso(<?php echo $sestmp; ?>,<?php echo $obra_sal; ?>,<?php echo $obra_ent; ?>);" > Guardar traspaso</button>
              </div>
          </div>
                
          </div><!-- ENd panel body -->
      </div>
              

       
      </div>
      </div>

      <script>     
    $(function() {

        $('#fenvio').datepicker( {
            dateFormat: "yy-mm-dd",
            onSelect: function(datetext){

                      var d = new Date(); // for now

                      var h = d.getHours();
                      h = (h < 10) ? ("0" + h) : h ;

                      var m = d.getMinutes();
                      m = (m < 10) ? ("0" + m) : m ;

                      var s = d.getSeconds();
                      s = (s < 10) ? ("0" + s) : s ;

                      datetext = datetext + " " + h + ":" + m + ":" + s;

                      $('#fenvio').val(datetext);
                  }
        });


});
  </script> 
<div class="row">&nbsp;</div>
<div class="row">&nbsp;</div>
<div class="row">&nbsp;</div>
<div class="row">&nbsp;</div>



