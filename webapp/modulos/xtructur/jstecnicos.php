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
  $.ajax({
    url:'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: {opcion:'tecnicos',id_tipo_tab:1},
    success: function(r){
      console.log(r);


      id_partida=45;
      
      jQuery("#jq_alta_tecnico").jqGrid({
        url:'sql_jstecnicos.php?q=3',
        datatype: "json",
        colNames:['ID','Estatus','Fecha de captura','Fecha de ingreso','Fecha de alta IMSS','Fecha de baja IMSS','Agrupador', 'Area','Especialidad', 'Departamento','Tipo','Cuenta de costo','Familia','Categoria / Salario', 'id_alta','Nombre','Apellido paterno','Apellido materno','Domicilio','Colonia','CP','Municipio','Estado','Estado civil','Telefono personal','Correo', 'Casado con','Contacto con','Telefono con','Fecha de nacimiento','Acta de nacimiento','IFE','Curp','IMSS','Infonavit','Carta antecedentes penales','Comprobante de domicilio','Contrato','Foto','Acta de nacimiento','IFE','Curp','IMSS','Infonavit','Carta antecedentes penales','Comprobante domicilio'],
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
          {name:"f_baja_i",index:"f_baja_i",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
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
                  $('#id_area').html('<option value="0">Seleccione</option><option value="0">No aplica ninguna</option>');
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
                          data='<option value="0">Seleccione</option><option value="0">No aplica ninguna</option>';
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

          {name:'oc_inst',index:'oc_inst',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.catesp},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.catesp},
            editrules: {edithidden:false},
            hidden:false
          },
          

          {name:'id_depto',index:'id_depto',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.depto},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.depto }
          },
          {name:'tipo_alta',index:'tipo_alta',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'Tecnico:Tecnico'},searchoptions:{sopt:['eq'], value:'Tecnico:Tecnico' }
          },

          {name:'id_cc',index:'id_cc',stype: 'select', width:70, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.cc},searchoptions:{sopt:['eq'], value:'0:Seleccione'+r.cc }
          },

          {name:'id_familia',index:'id_familia',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.fams,
            dataInit: function (elem) {
                  var v = $(elem).val();
                  if(v!=0){
                    $.ajax({
                      url:'ajax.php',
                      type: 'POST',
                      async:false,
                      data: {opcion:'cats',idfam:v},
                      success: function(r){
                        jQuery("#jq_alta_tecnico").jqGrid('setColProp', 'id_categoria', { editoptions: { value:'0:Selecciona;'+r} });
                      }
                    });
                  }else{
                    jQuery("#jq_alta_tecnico").jqGrid('setColProp', 'id_categoria', { editoptions: { value:'0:Seleccione'} });
                  }

                  
              },
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_familia = $(this).val();
                  $('#id_categoria').html('<option value="">Cargando...</option>');
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'categorias',id_familia:id_familia},
                    success: function(r){
                      if(r.success==1){
                        $.each( r.datos, function(i,d) {
                          data+='<option value="'+d.id+'">'+d.categoria+'</option>';
                        });
                        $('#id_categoria').html(data);
                      }else{
                        $('#id_categoria').html('<option value="">No hay categorias para esta familia</option>');
                      }
                    }
                  });
                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.fams },
          },
          {name:'id_categoria',index:'id_categoria',stype: 'select', width:70, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione'},searchoptions:{sopt:['eq'], value:'0:Seleccione' }
          },

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
          {name:'domicilio',index:'domicilio', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'colonia',index:'colonia', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'cp',index:'cp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'municipio',index:'municipio', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'estado',index:'estado', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'civil',index:'civil', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'tel_personal',index:'tel_personal', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'correo',index:'correo', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'casado_con',index:'casado_con', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'contacto_con',index:'contacto_con', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'telefono_con',index:'telefono_con', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:"fecha_nacimiento",index:"fecha_nacimiento",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
            editoptions:{ 
              dataInit: function(el){ 
                setTimeout(function(){ 
                  $(el).datepicker({ 
                    dateFormat: "yy-mm-dd",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:+0"
                  }); 
                }, 200); 
              }
            },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'acta',index:'acta', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'ife',index:'ife', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'curp',index:'curp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'imss',index:'imss', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'infonavit',index:'infonavit', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'carta_penales',index:'carta_penales', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'domicilio_d',index:'domicilio_d', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'contrato_e',index:'contrato_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'foto_e',index:'foto_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'acta_e',index:'acta_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'ife_e',index:'ife_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'curp_e',index:'curp_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'imss_e',index:'imss_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'infonavit_e',index:'infonavit_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'carta_penales_e',index:'carta_penales_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'domicilio_e',index:'domicilio_e',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:No;1:Si'},searchoptions:{sopt:['eq'], value:'0:No;1:Si' },
            editrules: {edithidden:true},
            hidden:true
          }

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
        editurl: "sql_jstecnicos.php",

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
        {edit:true,add:true,del:true,search:true,
        },
        {beforeShowForm: function(form){ 

          
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();

            var deptoSalario = $('#tr_oc_inst', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Nomina:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_f_baja_i', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);

            var datosPersonales = $('#tr_id_categoria', form).show();
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

            var deptoSalario = $('#tr_oc_inst', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Nomina:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_f_baja_i', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);
            
            var datosPersonales = $('#tr_id_categoria', form).show();
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
        {width: 480}, // settings for delete
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
              <div class="navbar-brand" style="color:#333;">Alta de tecnicos</div>
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

