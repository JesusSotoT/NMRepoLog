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
    data: {opcion:'tecnicos',id_tipo_tab:2,d:1},
    success: function(r){
      console.log(r);


      id_partida=45;
      
      jQuery("#jq_alta_tecnico").jqGrid({
        url:'sql_jsdestajista.php?q=3',
        datatype: "json",
        colNames:['ID', 'Estatus','Fecha de captura','Fecha de ingreso','Fecha de alta IMSS','Fecha de baja IMSS','Responsable tecnico', 'Especialidad', 'Partidas', 'Departamento','Tipo','Familia','Categoria / Salario','Dias de credito','Limite de credito','id_alta','Nombre','Apellido paterno','Apellido materno','Domicilio','Colonia','CP','Municipio','Estado','Estado civil','Telefono personal','Correo', 'Casado con','Contacto con','Telefono con','Fecha de nacimiento','Acta de nacimiento','IFE','Curp','IMSS','Infonavit','Carta antecedentes penales','Comprobante de domicilio','Contrato','Foto','Acta de nacimiento','IFE','Curp','IMSS','Infonavit','Carta antecedentes penales','Comprobante domicilio'],
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

          {name:'id_responsable',index:'id_responsable',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.respt},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.respt },
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'oc_inst',index:'oc_inst',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;1:Obra civil;2:Instalaciones'},searchoptions:{sopt:['eq'], value:'0:Seleccione;1:Obra civil;2:Instalaciones' }
          },

          {name:'id_partida',index:'id_partida',stype:'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:r.parts, multiple:true,

            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.parts },
            editrules: {edithidden:true},
            hidden:true
          },
          
          {name:'id_depto',index:'id_depto',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.depto},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.depto }
          },

          {name:'tipo_alta',index:'tipo_alta',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'Maestro:Maestro'},searchoptions:{sopt:['eq'], value:'Maestro:Maestro' }
          },
          
          {name:'id_familia',index:'id_familia',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.fams,
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
          {name:'dias_credito',index:'dias_credito', width:50, editable:true},
          {name:'limite_credito',index:'limite_credito', width:50, editable:true},
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
        editurl: "sql_jsdestajista.php",
        
        onCellSelect: function(rowid, iRow, iCol, e) {

        }


      });

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


            

            var deptoSalario = $('#tr_id_partida', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Nomina:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_id_responsable', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);

            var datosPersonales = $('#tr_id_categoria', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos personales:</b></td></tr>').insertAfter(datosPersonales);

            var infoAdicional = $('#tr_fecha_nacimiento', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Información adicional:</b></td></tr>').insertAfter(infoAdicional);

            var docsEscaneados = $('#tr_domicilio_d', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Documentos escaneados:</b></td></tr>').insertAfter(docsEscaneados);

            $('#id_partida option').mousedown(function(e) {
                e.preventDefault();
                $(this).prop('selected', !$(this).prop('selected'));
                return false;
            });
            
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

            var deptoSalario = $('#tr_id_partida', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Nomina:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_id_responsable', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);
            
            var datosPersonales = $('#tr_id_categoria', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos personales:</b></td></tr>').insertAfter(datosPersonales);

            var infoAdicional = $('#tr_fecha_nacimiento', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Información adicional:</b></td></tr>').insertAfter(infoAdicional);

            var docsEscaneados = $('#tr_domicilio_d', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Documentos escaneados:</b></td></tr>').insertAfter(docsEscaneados);

            $('#id_partida option').mousedown(function(e) {
                e.preventDefault();
                $(this).prop('selected', !$(this).prop('selected'));
                return false;
            });

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
              <div class="navbar-brand" style="color:#333;">Alta de maestros</div>
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
