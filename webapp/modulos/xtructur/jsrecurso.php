<?php
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
  } 

  include('conexiondb.php');

  session_start(); 

  $id=$_POST['id'];
  $SQL="SELECT b.partida FROM constru_partida a inner join constru_cat_partidas b on b.id=a.id_cat_partida WHERE a.id='$id';";
  $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $id_nombre=$row['partida'];

	$_SESSION['construccion']['id_partida']=$id;
  $_SESSION['construccion']['partida']=$row['partida'];

  $id_area=$_SESSION['xtructur']['id_area'];
  $area=$_SESSION['xtructur']['area'];
  $id_especialidad=$_SESSION['xtructur']['id_especialidad'];
  $especialidad=$_SESSION['xtructur']['especialidad'];
  $id_agrupador=$_SESSION['xtructur']['id_agrupador'];
  $agrupador=$_SESSION['xtructur']['agrupador'];
  $id_presupuesto=$_SESSION['xtructur']['id_presupuesto'];

?>
<input type="hidden" id="id_partida" value="<?php echo $id; ?>">
<input type="hidden" id="id_area" value="<?php echo $id_area; ?>">
<input type="hidden" id="id_especialidad" value="<?php echo $id_especialidad; ?>">
<input type="hidden" id="id_agrupador" value="<?php echo $id_agrupador; ?>">
<input type="hidden" id="id_presupuesto" value="<?php echo $id_presupuesto; ?>">
<div id="contjs">
<script type="text/javascript">
      var um = $.ajax({
        url: "ajax.php?funcion=um",
        async: false,
        datatype: 'json'
      }).responseText;
      console.log(um);

      id_partida=$('#id_partida').val();
      
      jQuery("#rowed22").jqGrid({

        url:'sql_jsrecurso.php?q=3&id_partida='+id_partida,
        datatype: "json",
        colNames:['','Naturaleza', 'Naturaleza','Clave','Descripcion', 'U.M.', 'Cantidad',  'Precio unitario', 'Importe','P. Destajo','P. Subcontrato'],
        colModel:[
          {name:'icon', width:20,sortable:false,search:false},
          {name:'id',index:'id', width:55, hidden: true, editable:true, editoptions:{defaultValue:id_partida} },
          {name:'naturaleza',index:'naturaleza', width:75, editable:true},
          {name:'codigo',index:'codigo', width:100, editable:true},
          {name:'descripcion',index:'descripcion', width:150, sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}
          },
          {name:'id_um',index:'id_um',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:um},searchoptions:{sopt:['eq'], value:um }
          },
          {name:'unidad',index:'unidad', width:100, sortable:false,editable:true},
          {name:'precio_unitario',index:'precio_venta', width:100, sortable:false,editable:true,sorttype:"float", formatter:"number"},
          {name:'importe',index:'(unidad*precio_venta)', width:100, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'corto',index:'corto', width:80, sortable:false,editable:false},
          {name:'corto',index:'corto', width:100, sortable:false,editable:false}
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#prowed22',
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
          var ids = jQuery("#rowed22").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#rowed22').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#rowed22").jqGrid('setRowData',ids[i],{icon:be});
          }

          $("#rowed22").jqGrid('setColProp', 'precio_costo', { formatter:"text", });
          $("#rowed22").jqGrid('setColProp', 'precio_venta', { formatter:"text", });
          var sum_costo = $("#rowed22").jqGrid('getCol','total_costo',false,'sum');
          var sum_venta = $("#rowed22").jqGrid('getCol','total_venta',false,'sum');
          $("#rowed22").jqGrid('footerData','set',{precio_costo:'Total costo:',total_costo:sum_costo,precio_unitario:'Total venta:',importe:sum_venta});
        },
        editurl: "sql_jsrecurso.php",
        
        onCellSelect: function(id) {
          var rowData = $("#rowed22").jqGrid('getRowData',id); 
          var desc = rowData['descripcion'];
          $("#rdesc").html(desc);
          $("#descripcion").css('display','block');
        }


      });

      jQuery("#rowed22").jqGrid('navGrid',"#prowed22",
        {edit:false,add:false,del:false,search:true,
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','70px');
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
          width: 400
        },
        
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
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
              return [false,' Error al guardar el registro '];
            }
          },
          closeAfterAdd:true,
          width: 400
        },
        {}, // settings for delete
        {onSearch: function(data){
          $("#rowed22").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#rowed22").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#prowed22', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#rowed22").jqGrid('exportarExcelCliente',{nombre:"planeacion",formato:"excel"});
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

</script>

<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Definir planeacion</div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12" style="padding-bottom: 11px; padding-left: 20px; ">
            <a style="cursor:pointer;" onclick="cambio(0);">Agrupador (<?php echo $agrupador; ?>)</a> >
            <a style="cursor:pointer;" onclick="cambio(1,<?php echo $id_agrupador; ?>);">Area (<?php echo $especialidad; ?>)</a> >
            <a style="cursor:pointer;" onclick="cambio(2,<?php echo $id_especialidad; ?>);">Especialidad (<?php echo $area; ?>)</a> >
            <a style="cursor:pointer;" onclick="cambio(3,<?php echo $id_area; ?>);">Partida (<?php echo $id_nombre; ?>)</a> >
            <a style="cursor:pointer;" onclick="cambio(4,<?php echo $id; ?>);">Recurso</a>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
              <table id="rowed22"></table>
              <div id="prowed22"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

</body>


