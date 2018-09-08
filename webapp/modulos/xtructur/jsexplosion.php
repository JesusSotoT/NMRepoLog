<script>     
    $(function() {
      $("#file_upload_1").uploadify({
        height        : 30,
        swf           : 'uploadify/uploadify.swf',
        uploader      : 'uploadify/uploadify.php',
        width         : 120,
        'onUploadSuccess' : function(file, data, response) {
            $.ajax({
                url:"importacion_insumos.php",
                type: 'POST',
                async: false,
                data:{file:file.name},
                success: function(r){
                    $('#jq_presupuesto').trigger( 'reloadGrid' );
                }
            });
        } 
      });

      
      jQuery("#jq_presupuesto").jqGrid({
        url:'sql_jsexplosion.php?q=3',
        datatype: "json",
        colNames:['Naturaleza','Clave','Descripcion', 'U.M.', 'Cantidad', 'PU concurso', 'Importe'],
        colModel:[

          {name:'naturaleza',index:'naturaleza',stype: 'select', width:75, sortable:false,editable:true,edittype:"select",
            editoptions:{ value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional;No cobrable:No cobrable',
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  $('#la_contra').css('visibility','hidden');
                  $('#codigo_clave').val('');
                  $('#descripcion').val('');
                  $('#unidtext').val('');
                  $('#unidad').val('');
                  $('#precio_costo').val('');
                  $('#unidad').val('');
                  $('#descripcion').prop('disabled', false);
                  $('#unidtext').prop('disabled', false);
                  $('#precio_costo').prop('disabled', false);
                  idnat = $(this).val();
                  if(idnat=='Extra'){
                    $('#lainfo_adic').css('visibility','hidden');
                    $('#tr_codigo_clave').css('visibility','visible');
                    $('#natext').html('EXT- ');
                    $('#codigo_clave').css('width','247');
                  }else if(idnat=='No cobrable'){
                    $('#lainfo_adic').css('visibility','hidden');
                    $('#tr_codigo_clave').css('visibility','visible');
                    $('#natext').html('OTO- ');
                    $('#codigo_clave').css('width','245');
                    $('#precio_costo').val('0.00');
                    $('#precio_costo').prop('disabled', true);
                  }else if(idnat=='Adicional'){
                    $('#claveadi').prop('selectedIndex',0);
                    $('#tr_codigo_clave').css('visibility','hidden');
                    $('#lainfo_adic').css('visibility','visible');
                  }else if(idnat=='Catalogo'){
                    $('#lainfo_adic').css('visibility','hidden');
                    $('#tr_codigo_clave').css('visibility','visible');
                    $('#natext').html('');
                    $('#codigo_clave').css('width','278');

                    $('<tr rowpos="3" class="FormData" id="la_contra" >\
  <td class="CaptionTD"><b>Contraseña</b></td>\
  <td class="DataTD">&nbsp;\
<input id="contra" name="contra"  type="password" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="">\
  </td>\
  </td>\
</tr>').insertAfter('#tr_precio_costo');

                  }else{
                    $('#lainfo_adic').css('visibility','hidden');
                    $('#tr_codigo_clave').css('visibility','visible');
                    $('#natext').html('');
                    $('#codigo_clave').css('width','278');

                  }


                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional;No cobrable:No cobrable' }
          },
          {name:'codigo_clave',index:'clave', width:100, editable:true},
          {name:'descripcion',index:'descripcion', width:400, sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}
          }, 
          {name:'unidtext',index:'unidtext', width:50, sortable:false,editable:true /*,formatter:verificaPres*/ }, 

          {name:'unidad',index:'unidad', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'precio_costo',index:'precio', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'total_costo',index:'(a.unidad*a.precio)', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},
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
          $("#jq_presupuesto").jqGrid('footerData','set',{precio_costo:'Total costos:',total_costo:sum_costo,precio_venta:'Total venta:',total_venta:sum_venta});
          for (var i = 0; i < ids.length; i++) 
          {
              var rowId = ids[i];
              

              var rowData = jQuery('#jq_presupuesto').jqGrid ('getRowData', rowId);
              if(rowData.unidtext==''){
                $('#jq_presupuesto').jqGrid('setRowData', rowId, {unidtext:' ',unidad:' ',precio_costo:' ',total_costo:' ' });
                //$('tr#'+rowId).find('input').replaceWith(' ');
              }
              if(rowData.naturaleza=='Catalogo'){
                //$('tr#'+rowId).find('input').replaceWith(' ');
              }
          }
        },
        beforeSelectRow: function(rowid, e)
        {
            var rowData = $(this).jqGrid('getRowData', rowid);
           if(rowData.naturaleza=='Catalogo'){
              //return false;
           }else{
              //jQuery("#jq_presupuesto").jqGrid('resetSelection');
              return(true);
           }
        },
        editurl: "sql_jsexplosion.php",
      });

      jQuery("#jq_presupuesto").jqGrid('navGrid',"#jqp_presupuesto",
        {edit:true,add:true,del:true,search:true},
        {beforeShowForm: function(form){
            natu=$('#naturaleza').val();
            if(natu=='Catalogo'){
              $('#codigo_clave').prop('disabled',true);
              $('#naturaleza').prop('disabled',true);
              $('#unidtext').prop('disabled',true);
              $('#unidad').prop('disabled',false);
              $('#precio_costo').prop('disabled',false);

              $('<tr rowpos="3" class="FormData" id="la_contra" >\
  <td class="CaptionTD"><b>Contraseña</b></td>\
  <td class="DataTD">&nbsp;\
<input id="contra" name="contra"  type="password" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="">\
  </td>\
  </td>\
</tr>').insertAfter('#tr_precio_costo');

            }

            $('#codigo_clave').before('<span id="natext"></span>');

            $('<tr rowpos="3" class="FormData" id="lainfo_adic" style="visibility:hidden;" >\
  <td class="CaptionTD">Clave</td>\
  <td class="DataTD">&nbsp;\
<select id="claveadi" onchange="seda();" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;"><option value="0">Cargando...</option></select>\
  </td>\
</tr>').insertAfter('#tr_naturaleza');



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

            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();
            idnat = $('#naturaleza').val();
            if(idnat=='Extra'){
              $('#codigo_clave').val($('#codigo_clave').val().substring(4));
              $('#natext').html('EXT- ');
              $('#codigo_clave').css('width','247');
            }else if(idnat=='No cobrable'){
              $('#codigo_clave').val($('#codigo_clave').val().substring(4));
              $('#natext').html('OTO- ');
              $('#codigo_clave').css('width','247');
              $('#precio_costo').prop('disabled', true);
            }else if(idnat=='Adicional'){
              
              $('#tr_codigo_clave').css('visibility','hidden');
              $('#lainfo_adic').css('visibility','visible');
              $('#descripcion').prop('disabled', true);
              $('#unidtext').prop('disabled', true);
              $('#precio_costo').prop('disabled', true);

            }



          },
            afterSubmit: function(response, otro){
            console.log(response);
            if(response.responseText=='sel'){
              return [false,' &nbsp; Seleccione naturaleza  '];
            }else if(response.responseText=='admin'){
              return [false,' &nbsp; Contraseña incorrecta  '];
            }else{
              return [true];
            }
          },
          closeAfterEdit:true,
          width: 400
        },
        {beforeShowForm: function(form){ 
            $('#codigo_clave').before('<span id="natext"></span>'); 

            $('<tr rowpos="3" class="FormData" id="lainfo_adic" style="visibility:hidden;" >\
  <td class="CaptionTD">Clave</td>\
  <td class="DataTD">&nbsp;\
<select id="claveadi" onchange="sedai();" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;"><option value="0">Cargando...</option></select>\
  </td>\
</tr>').insertAfter('#tr_naturaleza');


            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();

            cad='<option value="0">Seleccione</option>';
             $.ajax({
                url:"ajax.php",
                type: 'POST',
                dataType: 'JSON',
                data:{opcion:'claves_insumos'},
                success: function(r){
                  if(r.success==1){
                    $.each( r.datos, function( k, v ) {
                      cad+='<option value="'+v.id+'">'+v.clave+'</option>';
                    });
                  }else{
                      cad+='<option value="0">No hay recursos dados de alta</option>';
                  }

                  $('#claveadi').html(cad);
                    
                }
            });

            

          },

          afterSubmit: function(response, otro){
            console.log(response);
            if(response.responseText=='sel'){
              return [false,' &nbsp; Seleccione naturaleza  '];
            }else if(response.responseText=='admin'){
              return [false,' &nbsp; Contraseña incorrecta  '];
            }else{
              return [true];
            }
          },
          closeAfterAdd:true,
          width: 480
        },
        {
/*
          onclickSubmit: function (rp_ge, postdata) {
            console.log(rp_ge);
            //return false;
              var requestId = $(this).jqGrid("getCell", postdata.id, "naturaleza");
              console.log(requestId);
              // alert("REQUEST_ID=" + requestId);
             // return  rr: requestId ;
          }
*/
          onclickSubmit: function (options, delId) {

              selRowId = $(this).jqGrid('getGridParam', 'selarrrow'),
              celValue = $(this).jqGrid('getCell', selRowId, 'naturaleza');

              for (var i = 0; i < selRowId.length; i++) 
              {

                  esde =  $(this).jqGrid('getCell', selRowId[i], 'naturaleza');
                  if(esde=='Catalogo'){
                    return {
                        name: 'Catalogo'
                    };

                    return false;
                  }
              }

              
          },

          
          afterSubmit: function(response, otro){
            if(response.responseText=='CAT'){
              return [false,' Al tener insumos de naturaleza catalogo seleccionados, no se podran eliminar elementos.'];
            }else{
              return [true];
            }
          }


        }, // settings for delete
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

     <script>
$('#delmodal').on('show.bs.modal', function(e) {
    $('#error').text('');
  $('#ide').val(e.relatedTarget.dataset.eid);
});

function eliminar_insuu(id_obra){
     pass = $('#pass').val();
    if (confirm("Se eliminaran los insumos, desea continuar?") == true && pass=='SUP3R4DM1N') {
        $.ajax({
            url:"eliminar_insuu.php",
            type: 'POST',
            data:{id_obra:id_obra,pass:pass},
            success: function(r){
                    window.location='index.php?modulo='+modulo;
            }
        });
    }else{
      $('#error').text('Contraseña incorrecta');
    }
}


</script>

<div class="modal fade" id="delmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Desea borrar los isumos?</h4>
      </div>
      <div class="modal-body">
        Atención: Si usted borra estos registros no podra revertir los cambios<br>
        Contraseña: <input type='password' id='pass'>
     

         
      </div>
      <label id='error'  style="margin-left:15px"></label>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id='enviarb' class="btn btn-default" onclick="eliminar_insuu(<?php echo $idses_obra; ?>)">Aceptar</button>
      
        <!--<input type="button" id='enviarb' value="Aceptar" style="cursor:pointer" onclick="delent();">-->

      </div>
    </div>

     </div>
    </div>

  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Explosion de insumos</div>
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

        <div class="row">&nbsp;</div>
        <div class="panel panel-default" >
        <!-- Panel Heading -->
        <div class="panel-heading">
        <div class="panel-title">Importar explosion de insumos</div>
        </div><!-- End panel heading -->

        <!-- Panel body -->
        <div class="panel-body" >
          <div class="row">
            <div class="col-sm-9">
              <input type="file" name="file_upload" id="file_upload_1" />
            </div>
            <div class="col-sm-3">
            <a href="plantillaexp.xlsx">Descargar plantilla</a>
          </div>
            
          </div>
        </div>
        </div>

        <div class="col-sm-7">
          <button class="btn btn-danger pull-right" data-toggle="modal" data-target="#delmodal"> Eliminar Explosion</button>
        </div>
        
        
      </div>
    </div>

</body>
  
  
  