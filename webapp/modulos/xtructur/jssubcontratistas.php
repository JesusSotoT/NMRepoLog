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
    data: {opcion:'tecnicos',id_tipo_tab:2},
    success: function(r){

      
      jQuery("#jq_alta_destajista").jqGrid({
        url:'sql_jssubcontratistas.php?q=3',
        datatype: "json",
        colNames:['ID','Estatus','Fecha de captura','Fecha de ingreso','Dias de credito','Limite de credito','Tipo','Razon social','RFC','Domicilio','Colonia','CP','Municipio','Estado','Telefono empresa','Apellido paterno','Apellido materno','Nombres','Telefono personal','Correo','Importe contrato','Adendum 1','Adendum 2','Adendum 3', '% De Anticipo', '% Fondo de garantia', '% Retencion'],
        colModel:[
        { name:'id', width:40,sortable:true,search:true},
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

          {name:'dias_credito',index:'dias_credito', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'limite_credito',index:'limite_credito', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'tipo_alta',index:'tipo_alta',stype: 'select', width:50, sortable:true,editable:true,edittype:"select",editoptions:{value:'Subcontratista:Subcontratista'},searchoptions:{sopt:['eq'], value:'Subcontratista:Subcontratista' }
          },
         
          {name:'razon_social_sp',index:'razon_social_sp', width:50, editable:true},
          {name:'rfc_sp',index:'rfc_sp', width:50, editable:true},
          {name:'calle_sp',index:'calle_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'colonia_sp',index:'colonia_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'cp_sp',index:'cp_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'municipio_sp',index:'municipio_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'estado_sp',index:'estado_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'tel_emp_sp',index:'tel_emp_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'paterno_sp',index:'paterno_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'materno_sp',index:'materno_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'nombres_sp',index:'nombres_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'tel_personal_sp',index:'tel_personal_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'correo_sp',index:'correo_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'imp_cont',index:'imp_cont', width:100, sortable:true,editable:true,sorttype:"float", formatter:"number"},
          {name:'ade1',index:'ade1', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",editrules: {edithidden:true},
            hidden:true},
          {name:'ade2',index:'ade2', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",editrules: {edithidden:true},
            hidden:true},
          {name:'ade3',index:'ade3', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",editrules: {edithidden:true},
            hidden:true},
          {name:'anticipo',index:'anticipo', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",editrules: {edithidden:true},
            hidden:true},
            {name:'Fondo_garantia',index:'Fondo_garantia', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",editrules: {edithidden:true},
            hidden:true},
            {name:'Retencion',index:'Retencion', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",editrules: {edithidden:true},
            hidden:true}


        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        pager: '#jqp_alta_destajista',
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
          var ids = jQuery("#jq_alta_destajista").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_alta_destajista').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_alta_destajista").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_jssubcontratistas.php",
        onCellSelect: function(rowid, iRow, iCol, e) {
          //$(this).jqGrid('editGridRow', rowid, formEditingOptions);
          /*
          var rowData = $("#jq_alta_tecnico").jqGrid('getRowData',id); 
          var desc = rowData['descripcion'];
          $("#rdesc").html(desc);
          $("#descripcion").css('display','block');
          */
        }


      });

      //$("div.ui-jqgrid-sdiv").after($("div.ui-jqgrid-bdiv"));
      jQuery("#jq_alta_destajista").jqGrid('navGrid',"#jqp_alta_destajista",
        {edit:true,add:true,del:true,search:false,
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();
             $('#imp_cont').numeric();
            $('#ade1').numeric();
            $('#ade2').numeric();
            $('#ade3').numeric();
            $('#anticipo').numeric();

            var deptoSalario = $('#tr_id_partida', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Depto:</b></td></tr>').insertAfter(deptoSalario);

/*
            var planeacion = $('#tr_limite_credito', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);
*/
            var datosPersonales = $('#tr_limite_credito', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos fiscales:</b></td></tr>').insertAfter(datosPersonales);

            var contrato = $('#tr_correo_sp', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos de contrato:</b></td></tr>').insertAfter(contrato);


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
            $('#imp_cont').numeric();
            $('#ade1').numeric();
            $('#ade2').numeric();
            $('#ade3').numeric();

            var deptoSalario = $('#tr_id_partida', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Depto:</b></td></tr>').insertAfter(deptoSalario);
/*
            var planeacion = $('#tr_limite_credito', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);
            */

            var datosPersonales = $('#tr_limite_credito', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos fiscales:</b></td></tr>').insertAfter(datosPersonales);

            var contrato = $('#tr_correo_sp', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos de contrato:</b></td></tr>').insertAfter(contrato);
            
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
        {width: 480}, // settings for delete
        {onSearch: function(data){
          $("#jq_alta_destajista").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_alta_destajista").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_alta_destajista', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
            window.open('xls_expSub.php');
                //$("#jq_alta_destajista").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
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
              <div class="navbar-brand" style="color:#333;">Alta de subcontratistas</div>
          </div>
        </div>
        <div class="row">
        <div class="col-xs-12 tablaResponsiva">
          <div class="table-responsive" id="dtabla">
              <table id="jq_alta_destajista"></table>
              <div id="jqp_alta_destajista"></div>
          </div>
        </div>
      </div>
      <!--<h4>&nbsp;</h4>
      <div class="row">
          <div class="col-sm-12">
            <input class="btn btn-primary" id="btn_gra_sub" type="button" value="Graficar" onclick="graficar_ret('subcontra',<?php echo $idses_obra?>)">
          </div>
      </div>-->
      </div>
    </div>

</body>


  

