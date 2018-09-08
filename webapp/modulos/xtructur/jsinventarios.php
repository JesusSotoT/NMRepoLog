<?php

$SQL = "SELECT id,nomfam famat FROM constru_famat ORDER BY nomfam;";
  $result = $mysqli->query($SQL);
  
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $familias[]=$row;
    }
  }else{
    $familias=0;
  }

  $SQL = "SELECT b.id,concat(nombre,' ',paterno,' ',materno) as nombrec FROM constru_info_tdo a
LEFT JOIN constru_altas b on b.id=a.id_alta
where b.id_tipo_alta in (2)
union all
SELECT b.id,concat(nombres_sp,' ',paterno_sp,' ',materno_sp) as nombrec FROM constru_info_sp a
LEFT JOIN constru_altas b on b.id=a.id_alta
where b.id_tipo_alta in (4)
;";
  $result = $mysqli->query($SQL);
  
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $recibio[]=$row;
    }
  }else{
    $recibio=0;
  }

?>

<script>     
    $(function() {

      
      jQuery("#jq_presupuesto").jqGrid({
        url:'sql_jsinventarios.php?q=3',
        datatype: "json",
        colNames:['Naturaleza','Clave','Descripcion', 'U.M.', 'Cantidad', 'Can $','Entradas', 'Ent $', 'Salidas', 'Sal $', 'Entradas traspaso', 'Salidas traspaso', 'Restante', 'Res $', 'En almacen', 'En almacen $'],
        colModel:[

          {name:'naturaleza',index:'naturaleza',stype: 'select', width:75, sortable:false,editable:true,edittype:"select",
            editoptions:{ value:'0:Seleccione;Catalogo:Catalogo;Extra:Extra;Adicional:Adicional;No cobrable:No cobrable',
              dataEvents:[{ 
                type: 'change', fn: function(e) {
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
          {name:'U.M',index:'unidtext', width:50, sortable:false,editable:true /*,formatter:verificaPres*/ }, 

          {name:'Cantidad',index:'unidad', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
            {name:'Can_dinero',index:'Cantidad Dinero', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",  summaryType:'sum'},
          {name:'Entradas',index:'Entradas', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Entradas_Dinero',index:'Entradas Dinero', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",  summaryType:'sum'},
             {name:'Salidas',index:'Salidas', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},  
             {name:'Salidas_Dinero',index:'Salidas Dinero', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",  summaryType:'sum'},
             {name:'Entradas_traspaso',index:'Entradas_traspaso', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
        
       
          {name:'Salidas_traspaso',index:'Salidas_traspaso', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Restante',index:'Restante', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Restante_Dinero',index:'Restante Dinero', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",  summaryType:'sum'},
          {name:'Almacen',index:'Almacen', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'Almacen_Dinero',index:'Almacen Dinero', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number",  summaryType:'sum'},
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
       // multiselect:true,
        footerrow: true,
 

        gridComplete: function(){
         
          var ids = jQuery("#jq_presupuesto").jqGrid('getDataIDs');
             $("#jq_presupuesto").jqGrid('setColProp', 'tEntradasDinero', { formatter:"text", });

  var sum_ent = $("#jq_presupuesto").jqGrid('getCol','Entradas_Dinero',false,'sum');
         
          $("#jq_presupuesto").jqGrid('footerData','set',{tEntradasDinero:'Entrada $ Total:',Entradas_Dinero:sum_ent});


      $("#jq_presupuesto").jqGrid('setColProp', 'tcanDinero', { formatter:"text", });
  var sum_ent = $("#jq_presupuesto").jqGrid('getCol','Can_dinero',false,'sum');
         
          $("#jq_presupuesto").jqGrid('footerData','set',{tcanDinero:'Entrada $ Total:',Can_dinero:sum_ent});
 
    $("#jq_presupuesto").jqGrid('setColProp', 'tSalidasDinero', { formatter:"text", });
 var sum_ent = $("#jq_presupuesto").jqGrid('getCol','Salidas_Dinero',false,'sum');
         
          $("#jq_presupuesto").jqGrid('footerData','set',{tSalidasDinero:'Entrada $ Total:',Salidas_Dinero:sum_ent});
           
    $("#jq_presupuesto").jqGrid('setColProp', 'tRestanteDinero', { formatter:"text", });
           var sum_ent = $("#jq_presupuesto").jqGrid('getCol','Restante_Dinero',false,'sum');
         
          $("#jq_presupuesto").jqGrid('footerData','set',{tRestanteDinero:'Entrada $ Total:',Restante_Dinero:sum_ent});


    $("#jq_presupuesto").jqGrid('setColProp', 'tAlmacenDinero', { formatter:"text", });
 var sum_ent = $("#jq_presupuesto").jqGrid('getCol','Almacen_Dinero',false,'sum');
         
          $("#jq_presupuesto").jqGrid('footerData','set',{tAlmacenDinero:'Entrada $ Total:',Almacen_Dinero:sum_ent});




         
        
          for (var i = 0; i < ids.length; i++) 
          {
              var rowId = ids[i];
              

              var rowData = jQuery('#jq_presupuesto').jqGrid ('getRowData', rowId);
              if(rowData.unidtext==''){
                $('#jq_presupuesto').jqGrid('setRowData', rowId, {unidtext:' ',unidad:' ',precio_costo:' ',total_costo:' ' });
                $('tr#'+rowId).find('input').replaceWith(' ');
              }
              if(rowData.naturaleza=='Catalogo'){
                $('tr#'+rowId).find('input').replaceWith(' ');
              }
          }
        },
        beforeSelectRow: function(rowid, e)
        {
            var rowData = $(this).jqGrid('getRowData', rowid);
           if(rowData.naturaleza=='Catalogo'){
              return false;
           }else{
              jQuery("#jq_presupuesto").jqGrid('resetSelection');
              return(true);
           }
        },
        editurl: "sql_jsinventarios.php",
        
      });

      jQuery("#jq_presupuesto").jqGrid('navGrid',"#jqp_presupuesto",
        {edit:false,add:false,del:false,search:true},
        {beforeShowForm: function(form){
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
          closeAfterAdd:true,
          width: 480
        },
        {}, // settings for delete
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

  <body>

  <div class="row">&nbsp;</div>
<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Inventarios</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <!--Filtros-->
<div class="row">
    <div class="col-sm-3 col-xs-8">
    <label>Familia material:</label>
      <select class="form-control" id="filtro_familia" onchange="filtrosfam('jsinv','fam');">
        <option selected="selected" value="0">Todas</option>
        <?php 
        if($familias!=0){
          foreach ($familias as $key => $value) { 

            ?>
            <option value="<?php echo $value['id']; ?>"><?php echo utf8_encode($value['famat']); ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Familias</option>
        <?php } ?>
      </select>
    </div>
    

    <div class="col-sm-3 col-xs-8">
    <label>Recibio:</label>
      <select class="form-control" id="filtro_recibio" onchange="filtrosrec('jsinv','fam');">
        <option selected="selected" value="0">Todas</option>
        <?php 
        if($recibio!=0){
          foreach ($recibio as $key => $value) { 

            ?>
            <option value="<?php echo $value['id']; ?>"><?php echo utf8_encode($value['nombrec']); ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay subcontratistas/maestros</option>
        <?php } ?>
      </select>
    </div>
    
</div>
<!--Fin filtros -->
          
      </div><!-- ENd panel body -->
    </div>

<div id="divcuandohayobra" class="row">
    <div class="col-xs-12 tablaResponsiva">
          <div class="table-responsive" id="dtabla">
            <table id="jq_presupuesto"></table>
            <div id="jqp_presupuesto"></div>
          </div>
        </div>
        </div>

  <div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Inventarios</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
    
      
  </div><!-- ENd panel body -->
</div>


    <div id="divcuandohayobra" class="row">
        <div class="row">
        
      </div>

    </div>




</body>
  
  
  
