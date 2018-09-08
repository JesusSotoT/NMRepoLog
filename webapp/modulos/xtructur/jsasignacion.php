<style>
.ui-dialog-titlebar-close {
  visibility: hidden;
}
</style>

<script>    
var c1=-1;
var shifted=false;
 $(document).on('keyup keydown', function(e){shifted = e.shiftKey} );

  function verificaPres (cellvalue, options, rowObject){
    /*
    if (cellvalue == '')
      return " ";
    else
      return cellvalue;
    */
  }

 

    $(function() {
      var um = $.ajax({
        url: "ajax.php?funcion=um",
        async: false,
        datatype: 'json'
      }).responseText;

      jQuery("#jq_asignacion").jqGrid({
        url:'sql_jsasignacion.php?q=3',
        datatype: "json",
        colNames:['Codigo','Naturaleza','Clave','Descripcion', 'U.M.', 'Cantidad', 'Precio unitario', 'Importe','Asignados'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:true, editoptions:{defaultValue:0} },
          {name:'naturaleza',index:'naturaleza',stype: 'select', width:75, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional'},searchoptions:{sopt:['eq'], value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional' }},
          {name:'codigo_clave',index:'codigo_clave', width:100, editable:true},
          {name:'descripcion',index:'descripcion', width:400, sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}, 
          {name:'unidtext',index:'unidtext', width:50, sortable:false,editable:true /*,formatter:verificaPres*/ }, 
          {name:'unidad',index:'unidad', width:100, sortable:false,editable:true},
          {name:'precio_costo',index:'precio_costo', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'total_costo',index:'(unidad*precio_costo)', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'asignados',index:'asignados', width:100, editable:true, search:false},  
        ],
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#jqp_asignacion',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        footerrow: true,
        multiselect: true,

        gridComplete: function(){
          var ids = jQuery("#jq_asignacion").jqGrid('getDataIDs');

          $("#jq_asignacion").jqGrid('setColProp', 'precio_costo', { formatter:"text", });
          $("#jq_asignacion").jqGrid('setColProp', 'precio_venta', { formatter:"text", });
          var sum_costo = $("#jq_asignacion").jqGrid('getCol','total_costo',false,'sum');
          var sum_venta = $("#jq_asignacion").jqGrid('getCol','total_venta',false,'sum');
          $("#jq_asignacion").jqGrid('footerData','set',{precio_costo:'Total costo:',total_costo:sum_costo,precio_venta:'Total venta:',total_venta:sum_venta});

          for (var i = 0; i < ids.length; i++) 
          {
              var rowId = ids[i];
              var rowData = jQuery('#jq_asignacion').jqGrid ('getRowData', rowId);

              be='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="va_'+ids[i]+'_va" style="cursor:pointer;text-decoration:underline;"onclick="verasignados('+ids[i]+');">'+rowData.asignados+'</a>'; 
              jQuery("#jq_asignacion").jqGrid('setRowData',ids[i],{asignados:be});

              if(rowData.unidtext==''){
                $('#jq_asignacion').jqGrid('setRowData', rowId, {unidtext:' ',unidad:' ',precio_costo:'  ',total_costo:'  ' });
                $('tr#'+rowId).find('input').replaceWith(' ');
                $('tr#'+rowId).attr('no_vale',1);
                //jQuery("#jqg_TableId_"+rowId).attr("disabled", true);
              }else{
                $('tr#'+rowId).attr('no_vale',0);
              }
          }
        },
        editurl: "sql_jsasignacion.php",


        beforeSelectRow: function(rowid, e) {
            if( $('tr#'+rowid).attr("no_vale")==1 ){
                return false;
            }
            return true; 
        },

        onSelectRow: function (rowid, status, e) {
              
             if(status==true && shifted==false ){
              c1=rowid;

           
           }
           if(status==true && shifted==true){
            shifted=false;
            for(i = parseInt(c1)+1; i < parseInt(rowid); i += 1){
             $("#jq_asignacion").jqGrid('setSelection', i, true);}
             c1=parseInt(c1)+1;
           }

        }

      });
      //$("div.ui-jqgrid-sdiv").after($("div.ui-jqgrid-bdiv"));
      jQuery("#jq_asignacion").jqGrid('navGrid',"#jqp_asignacion",
        {edit:false,add:false,del:false,search:true},
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          width: 400
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();
          },
          closeAfterAdd:true,
          width: 480
        },
        {}, // settings for delete
        {onSearch: function(data){
          $("#jq_asignacion").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_asignacion").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_asignacion', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_asignacion").jqGrid('exportarExcelCliente',{nombre:"asignar",formato:"excel"});
                //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
            },
            position: "last"
        });

       $(".add-new-row").on("click",function(){

          $.ajax({
              url:"ajax.php",
              type: 'POST',
              dataType:'JSON',
              data:{opcion:'agrups'},
              success: function(resp){
                $('#ll1').css('display','none');
                $('#cargaagr').css('visibility','visible');
                if(resp.success==1){
                  $('#cargaagr').html('<option value="0" selected="selected">Selecciona</option>');
                  $.each(resp.datos, function (index, data) {
                    $('#cargaagr').append('<option value="'+data.id+'">'+data.nombre+'</option>');
                  });
                }else{
                   $('#cargaagr').html('<option value="0" selected="selected">No hay agrupadores</option>');
                }
              }
          });

            $("#jq_asignacion").jqGrid('editGridRow',"new",{width:400,height:200,reloadAfterSubmit:false,closeAfterAdd:true});
            latable=$("#latable").html();
            $("#editcntjq_asignacion").html(latable);         
      });      

});

    function chpartida(){
      idpartida=$('#cargapart').val();
      if(idpartida>0 ){ 
        $('#basign').css('display','block');
      }else{
        $('#basign').css('display','none');
      }
    }

    function charea(){
      $('#cargapart').css('visibility','hidden');
      $('#ll4').css('display','block');
      idarea=$('#cargaare').val();
      if(idarea>0 ){
        $.ajax({
            url:"ajax.php",
            type: 'POST',
            dataType:'JSON',
            data:{opcion:'charea',idarea:idarea},
            success: function(resp){

              $('#ll4').css('display','none');
              $('#cargapart').prop("disabled", false); // Element(s) are now enabled.
              $('#cargapart').css('visibility','visible');

              if(resp.success==1){
                $('#cargapart').html('<option value="0" selected="selected">Selecciona</option>');
                $.each(resp.datos, function (index, data) {
                  $('#cargapart').append('<option value="'+data.id+'">'+data.nombre+'</option>');
                })
              }else{
                 $('#cargapart').html('<option value="0" selected="selected">No hay partidas</option>');
              }
            }
        });
      }
    }

    function chesp(){
      ids = jQuery("#jq_asignacion").jqGrid('getGridParam','selarrrow');
      $('#cargaare').css('visibility','hidden');
      $('#ll3').css('display','block');
      idesp=$('#cargaesp').val();
      if(ids==''){
        alert('No tienes ningun recurso seleccionado');
        $('#ll3').css('display','none');
      }
      if(idagru>0 ){
        $.ajax({
            url:"ajax.php",
            type: 'POST',
            //async: false,
            dataType:'JSON',
            data:{opcion:'chesp_mod',idesp:idesp,ids:ids},
            success: function(resp){
              $('#ll3').css('display','none');
              $('#cargaare').prop("disabled", false); // Element(s) are now enabled.
              $('#cargaare').css('visibility','visible');
              
              if(resp.success==2){
                alert('El recurso seleccionado o alguno de los seleccionados ya fue asignado a esta area con anterioridad');
                $('#cargaare').html('<option value="0" selected="selected">Area invalida</option>');
                return false;
              }

              if(resp.success==1){
                $('#cargaare').html('<option value="0" selected="selected">Selecciona</option>');
                $.each(resp.datos, function (index, data) {
                  $('#cargaare').append('<option value="'+data.id+'">'+data.nombre+'</option>');
                })
              }else{
                 $('#cargaare').html('<option value="0" selected="selected">No hay especialidades</option>');
              }

            }
        });
      }
    }

    function aspart(){

      idpartida=$('#cargapart').val();
      ida=$('#cargaesp').val();
      ids = jQuery("#jq_asignacion").jqGrid('getGridParam','selarrrow');
      $.each(ids, function (i, v) {
        u = $('#va_'+v+'_va').text()*1;
        d=u+1;
        $('#va_'+v+'_va').text(d)*1;
      })
      //return false;
      if(ids!=''){
        var r = confirm("Seguro que deseas asignar los elementos seleccionados a esta partida");
        if (r == true) {
          $.ajax({
              url:"ajax.php",
              type: 'POST',
              data:{opcion:'aspart',ids:ids, idp:idpartida,ida:ida},
              success: function(resp){
                if(resp=='Error:22'){

                }else{
                  $("#jq_asignacion").jqGrid('resetSelection');
                  //$('tr#').trigger('click');
                  //$('#jq_asignacion').trigger( 'reloadGrid' );
                  $('.ui-jqdialog-titlebar-close').trigger('click');
                }
              }
          });
        } else {

        }
      }else{
        alert('Selecciona un elemento');
      }
    }
    function chagru(){
      $('#cargaesp').css('visibility','hidden');
      $('#ll2').css('display','block');
      idagru=$('#cargaagr').val();

      if(idagru>0){
        $.ajax({
            url:"ajax.php",
            type: 'POST',
            //async: false,
            dataType:'JSON',
            data:{opcion:'chagru',idagru:idagru},
            success: function(resp){

              $('#ll2').css('display','none');
              $('#cargaesp').prop("disabled", false); // Element(s) are now enabled.
              $('#cargaesp').css('visibility','visible');

              if(resp.success==1){
                $('#cargaesp').html('<option value="0" selected="selected">Selecciona</option>');
                $.each(resp.datos, function (index, data) {
                  $('#cargaesp').append('<option value="'+data.id+'">'+data.nombre+'</option>');
                })
              }else{
                 $('#cargaesp').html('<option value="0" selected="selected">No hay areas</option>');
              }
            }
        });
      }
    }

  </script> 

  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Asignar planeacion a presupuesto</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_asignacion"></table>
                <div id="jqp_asignacion"></div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
              <button class="add-new-row btn btn-primary btnMenu">Asignar</button>
            </div>
        </div>
      </div>
    </div>

</body>

      <div id="latable" style="float:left; width:700px;margin:3px 0 0 5px;color:#ffffff; display:none;">
          <table cellspacing="0" cellpadding="0" border="0" class="EditTable" id="TblGrid_rowed2"><tbody><tr style="display:none" id="FormError"><td colspan="2" class="ui-state-error"></td></tr><tr class="tinfo" style="display:none"><td colspan="2" class="topinfo"></td></tr>
          <tr rowpos="1" class="FormData" id="tr_codigo">
            <td class="CaptionTD">
              Agrupador
            </td>
            <td class="DataTD">&nbsp;
              <img id="ll1" src="jqgrid/images/loading.gif">
            <select id="cargaagr" onchange="chagru();" type="text" id="codigo" name="codigo" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width:200px; visibility:hidden;">
              
            </select>

            </td>
          </tr>
          <tr rowpos="1" class="FormData" id="tr_codigo">
            <td class="CaptionTD">
              Area
            </td>
            <td class="DataTD">&nbsp;
              <img id="ll2" src="jqgrid/images/loading.gif" style="display:none;">
            <select disabled="disabled" id="cargaesp" onchange="chesp();" type="text" id="codigo" name="codigo" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width:200px;">
              <option value="0" selected="selected">Selecciona</option>
            </select>

            </td>
          </tr>
          <tr rowpos="1" class="FormData" id="tr_codigo">
            <td class="CaptionTD">
              Especialidad
            </td>
            <td class="DataTD">&nbsp;
              <img id="ll3" src="jqgrid/images/loading.gif" style="display:none;">
            <select disabled="disabled" id="cargaare" onchange="charea();" type="text" id="codigo" name="codigo" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width:200px;">
              <option value="0" selected="selected">Selecciona</option>
            </select>

            </td>
          </tr>
          <tr rowpos="1" class="FormData" id="tr_codigo">
            <td class="CaptionTD">
              Partida
            </td>
            <td class="DataTD">&nbsp;
              <img id="ll4" src="jqgrid/images/loading.gif" style="display:none;">
            <select disabled="disabled" id="cargapart" onchange="chpartida();" type="text" id="codigo" name="codigo" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width:200px;">
              <option value="0" selected="selected">Selecciona</option>
            </select>

            </td>
          </tr>
          <tr rowpos="1" class="FormData" id="tr_codigo">
            <td class="CaptionTD">
              &nbsp;
            </td>
            <td class="DataTD">&nbsp;&nbsp;&nbsp;&nbsp;
              <input id="basign" type="button" value="Asignar"style="display:none;" onclick="aspart();">
            </td>
          </tr>
          <tr rowpos="2" class="FormData" id="tr_nombre"></tr><tr style="display:none" class="FormData"><td class="CaptionTD"></td><td class="DataTD" colspan="1"><input type="text" value="_empty" name="rowed2_id" id="id_g" class="FormElement"></td></tr></tbody>
        </table>
      </div>



<div style="display:none;font-size:11px;" id="dialog-confirm" title="Recurso asignado"></div>

