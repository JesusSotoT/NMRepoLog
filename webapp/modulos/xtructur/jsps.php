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
            //$('#tr_id_categoria').css('display','none');

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
  $.ajax({
    url:'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: {opcion:'tecnicos',id_tipo_tab:2},
    success: function(r){
      console.log(r);


      id_partida=45;
      
      jQuery("#jq_alta_tecnico").jqGrid({
        url:'sql_jsps.php?q=3',
        datatype: "json",
        colNames:['ID','Estatus','Fecha de captura','Fecha de ingreso','Fecha de alta IMSS','Agrupador', 'Area','Responsable subcontratista' /*,'Agrupador', 'Area','Especialidad', 'Departamento'*/,'Tipo','Familia','id_alta','Nombre','Apellido paterno','Apellido materno','No IMSS'],
        colModel:[
          {name:'id', width:40,sortable:false,search:true},
          {name:'estatus',index:'estatus',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'Alta:Alta;Baja:Baja;Incapacitado:Incapacitado;Boletinado:Boletinado'},searchoptions:{sopt:['eq'], value:'Alta:Alta;Baja:Baja;Incapacitado:Incapacitado;Boletinado:Boletinado' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:"f_captura",index:"f_captura",width:35,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
            editoptions:{ 
              dataInit: function(el){ 
                setTimeout(function(){ 
                  $(el).datepicker({ dateFormat: "yy-mm-dd" });
                }, 200); 
              }
            }
          },
          {name:"f_ingreso",index:"f_ingreso",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
            editoptions:{ 
              dataInit: function(el){ 
                setTimeout(function(){ 
                  $(el).datepicker({ dateFormat: "yy-mm-dd" }); 
                }, 200); 
              }
            },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:"f_alta_i",index:"f_alta_i",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
            editoptions:{ 
              dataInit: function(el){ 
                setTimeout(function(){ 
                  $(el).datepicker({ dateFormat: "yy-mm-dd" }); 
                }, 200); 
              }
            },
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'id_agrupador',index:'id_agrupador',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.agrupadores,
              dataInit: function (elem) {
                  var v = $(elem).val();
                  if(v!=0){
                    $.ajax({
                      url:'ajax.php',
                      type: 'POST',
                      async:false,
                      data: {opcion:'areastecs',idagru:v},
                      success: function(r){
                        jQuery("#jq_alta_tecnico").jqGrid('setColProp', 'id_area', { editoptions: { value:'0:Selecciona;'+r} });
                      }
                    });
                  }else{
                    jQuery("#jq_alta_tecnico").jqGrid('setColProp', 'id_area', { editoptions: { value:'0:Seleccione'} });
                  }

                  
              },
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  $('#id_area').html('<option value="0">Seleccione</option>');
                  $('#id_especialidad').html('<option value="0">Seleccione</option>');
                  data='';
                  idagru = $(this).val();
                  if(idagru>0){
                    $('#id_area').html('<option value="0">Cargando...</option>');
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'chagru',idagru:idagru},
                    success: function(r){
                      if(r.success==1){
                          data='<option value="0">Seleccione</option>';
                          $.each( r.datos, function(i,d) {
                            data+='<option value="'+d.id+'">'+d.nombre+'</option>';
                          });
                          $("#id_area").prop('disabled', false);
                          $('#id_area').html(data);
                        }else{  
                          data='<option value="0">No existen areas para este agrupador</option>'; 
                          $('#id_area').html(data);                       

                        }
                      }
                    });
                  }
                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.agrupadores },
          },

          {name:'id_area',index:'id_area',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione'},
            searchoptions:{sopt:['eq'], value:'0:Seleccione' },
          },

          {name:'id_responsable',index:'id_responsable',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.resps},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.resps },
            editrules: {edithidden:true},
            hidden:true
          },

     /*     {name:'id_agrupador',index:'id_agrupador',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.agrupadores,
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  $('#id_area').html('<option value="0">Seleccione</option>');
                  $('#id_especialidad').html('<option value="0">Seleccione</option>');
                  data='';
                  idagru = $(this).val();
                  if(idagru>0){
                    $('#id_area').html('<option value="0">Cargando...</option>');
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'chagru',idagru:idagru},
                    success: function(r){
                      if(r.success==1){
                          data='<option value="0">Seleccione</option>';
                          $.each( r.datos, function(i,d) {
                            data+='<option value="'+d.id+'">'+d.nombre+'</option>';
                          });
                          $("#id_area").prop('disabled', false);
                          $('#id_area').html(data);
                        }else{  
                          data='<option value="0">No existen areas para este agrupador</option>'; 
                          $('#id_area').html(data);                       

                        }
                      }
                    });
                  }
                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.agrupadores },
          },

          {name:'id_area',index:'id_area',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione',
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  $('#id_especialidad').html('<option value="0">Seleccione</option>');
                  data='';
                  idesp = $(this).val();
                  if(idesp>0){
                    $('#id_especialidad').html('<option value="0">Cargando...</option>');
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'chesp',idesp:idesp},
                    success: function(r){
                      if(r.success==1){
                          data='<option value="0">Seleccione</option>';
                          $.each( r.datos, function(i,d) {
                            data+='<option value="'+d.id+'">'+d.nombre+'</option>';
                          });
                          $("#id_especialidad").prop('disabled', false);
                          $('#id_especialidad').html(data);
                        }else{  
                          data='<option value="0">No existen especialidades para esta area</option>'; 
                          $('#id_especialidad').html(data);                       

                        }
                      }
                    });
                  }
                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione' },
          },

          {name:'id_especialidad',index:'id_especialidad',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione',
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  idagru = $(this).val();
                  if(idagru>0){
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'chagru',idagru:idagru},
                    success: function(r){
                      if(r.success==1){

                        }else{                          

                        }
                      }
                    });
                  }
                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione' },
          },

          

          {name:'id_depto',index:'id_depto',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.depto},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.depto }
          },
          */
          {name:'tipo_alta',index:'tipo_alta',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'P subcontratos:P subcontratos'},searchoptions:{sopt:['eq'], value:'P subcontratos:P subcontratos' }
          },
          {name:'id_familia',index:'id_familia',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.fams
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.fams },
          },
          //{name:'id_categoria',index:'id_categoria',stype: 'select', width:70, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione'},searchoptions:{sopt:['eq'], value:'0:Seleccione' }
          //},
          {name:'id_alta',index:'id_alta', width:50, editable:false,
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'nombre',index:'nombre', width:50, editable:true},
          {name:'paterno',index:'paterno', width:50, editable:true},
          {name:'materno',index:'materno', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'imss',index:'imss', width:50, editable:true},


        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        pager: '#jqp_alta_tecnico',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        multiselect: true,
       /* groupingView : {
          groupSummary : [true],
          groupColumnShow : [true],
          groupText : ['<b>{0}</b>'],
          groupCollapse : false,
          groupOrder: ['asc'] 
        }, */
        gridComplete: function(){
         
          var ids = jQuery("#jq_alta_tecnico").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_alta_tecnico').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_alta_tecnico").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_jsps.php",
    
        onCellSelect: function(rowid, iRow, iCol, e) {
         // $(this).jqGrid('editGridRow', rowid, formEditingOptions);
          /*
          var rowData = $("#jq_alta_tecnico").jqGrid('getRowData',id); 
          var desc = rowData['descripcion'];
          $("#rdesc").html(desc);
          $("#descripcion").css('display','block');
          */
        }


      });

      //$("div.ui-jqgrid-sdiv").after($("div.ui-jqgrid-bdiv"));
      jQuery("#jq_alta_tecnico").jqGrid('navGrid',"#jqp_alta_tecnico",
        {edit:true,add:true,del:false,search:true,
        },
        {beforeShowForm: function(form){ 
            /*selid = $('#jq_alta_tecnico').jqGrid('getGridParam','selrow');
            $.ajax({
              url:'ajax.php',
              type: 'POST',
              dataType: 'JSON',
              data: {opcion:'info_tecnico',id:selid},
              success: function(r){
                if(r.success==1){
                 // $('#id_area').html('<option);
                }else{
                  
                }
              }
            });*/
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();

            var deptoSalario = $('#tr_id_responsable', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Depto. Salario:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_f_baja_i', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);

            var datosPersonales = $('#tr_id_familia', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos personales:</b></td></tr>').insertAfter(datosPersonales);

            var infoAdicional = $('#tr_fecha_nacimiento', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Información adicional:</b></td></tr>').insertAfter(infoAdicional);

            var docsEscaneados = $('#tr_domicilio_d', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Documentos escaneados:</b></td></tr>').insertAfter(docsEscaneados);
            
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

            var deptoSalario = $('#tr_id_responsable', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Depto. Salario:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_f_baja_i', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);
            
            var datosPersonales = $('#tr_id_familia', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos personales:</b></td></tr>').insertAfter(datosPersonales);

            var infoAdicional = $('#tr_fecha_nacimiento', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Información adicional:</b></td></tr>').insertAfter(infoAdicional);

            var docsEscaneados = $('#tr_domicilio_d', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Documentos escaneados:</b></td></tr>').insertAfter(docsEscaneados);

          },
          afterSubmit: function(response, otro){
            if(response.statusText=='OK'){
              return [true];
            }else{
              return [false,' Error al guardar el registro '];
            }
          },
          recreateForm: true,
          closeAfterAdd:true,
          width: 480
        },
        {}, // settings for delete
        {onSearch: function(data){
          $("#jq_alta_tecnico").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_alta_tecnico").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_alta_tecnico', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_alta_tecnico").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
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
              <div class="navbar-brand" style="color:#333;">Alta personal de subconbtratos</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_alta_tecnico"></table>
                <div id="jqp_alta_tecnico"></div>
            </div>
          </div>
        </div> 
      </div>
    </div>

</body>

