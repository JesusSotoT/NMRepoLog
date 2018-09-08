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
            $('#Precio').numeric();  

          


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
        url:'sql_jsvisualizaradic.php?q=3&sestmp='+sestmp,
        datatype: "json",
        colNames:['Codigo','idrecurso','Naturaleza','Descripcion','Unidad','P.U. Concurso', 'Cantidad','Justificacion', 'Importe','rid'],
        colModel:[
        {name:'Codigo',index:'codigo', hidden:true, stype: 'select', width:70, sortable:true, editable:true, editrules: {edithidden:false},
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

             {name:'precio',index:'Precio', width:70, editable:true,
            editrules: {edithidden:false},  formatter:"number",
            hidden:false
          },

          {name:'Cantidad',index:'Unidad', width:70, editable:true,
            editrules: {edithidden:false},  formatter:"number",
            hidden:false
          },

            {name:'Justificacion',index:'justificacion', width:70, 
            editrules: {edithidden:true},
            hidden:true,
            editable:true
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
        autowidth: true,
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
        editurl: "sql_jsvisualizaradic.php?sestmp="+sestmp,
    
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
           $('#codigo_clave').before('<span id="natext"></span>');

            $('<tr rowpos="3" class="FormData" id="lainfo_adic" >\
  <td class="CaptionTD">Clave</td>\
  <td class="DataTD">&nbsp;\
<select id="claveadi" onchange="sedadi();" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;"><option value="0">Cargando...</option></select>\
  </td>\
</tr>').insertBefore('#tr_Descripcion');

            laclave = $('#codigo_clave').val();
            cad='<option value="0">Seleccione</option>';
              $.ajax({
                url:"ajax.php",
                type: 'POST',
                dataType: 'JSON',
                data:{opcion:'claves_recurso'},
                success: function(r){
                  if(r.success==1){
                    $.each( r.datos, function( k, v ) {
                      cad+='<option value="'+v.id+'">'+v.codigo+'</option>';
                    });
                  }else{
                      cad+='<option value="0">No hay recursos dados de alta</option>';
                  }
                  $('#claveadi').html('');
                  $('#claveadi').html(cad);
                  $("#claveadi option:contains("+laclave+")").attr('selected', 'selected');
                }
              });










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
            }else if(response.responseText=='NO0'){
              return [false,' &nbsp; La cantidad tiene que ser mayor a 0  '];
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



  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Solicitud de Adicionales</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
                <table id="jq_requisiciones"></table>
                <div id="jqp_requisiciones"></div>
            </div>
          </div>
        </div>
<div class="row">&nbsp;</div>
        <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Datos del solicitud</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        
          <div class="row">
      <div class="col-sm-6">
        <div class="row">
          <div class="col-sm-6">
            <label>Solicito:</label>
            <div>
            <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
            </div>
            <input type='hidden' id="iduserlog" value='<?php echo $id_username_global; ?>'>

          </div>
          

          <div class="col-sm-6">
      <label>Total:</label>
      <input class="form-control" id="total" type="text" value="0" disabled="disabled">
    </div>
          <div class="col-sm-4" style="padding-top: 15px;">
       
            <button style="width:100%" class="btn btn-primary btn-xm pull-right"  id="btnGenReq" onclick="generaadi(<?php echo $sestmp; ?>);"> Generar Solicitud</button>


          </div>
        
      </div>
  </div>
      </div>
    </div>
      </div><!-- ENd panel body -->
    </div>

        

</body>


  
  