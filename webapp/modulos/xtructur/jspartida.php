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
  $SQL="SELECT b.especialidad FROM constru_area a INNER JOIN constru_cat_especialidad b on b.id=a.id_cat_especialidad WHERE a.id='$id';";
  $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $id_nombre=$row['especialidad'];

  $_SESSION['xtructur']['id_area']=$id;
  $_SESSION['xtructur']['area']=$id_nombre;

	$id_especialidad=$_SESSION['xtructur']['id_especialidad'];
  $especialidad=$_SESSION['xtructur']['especialidad'];
  $id_agrupador=$_SESSION['xtructur']['id_agrupador'];
  $agrupador=$_SESSION['xtructur']['agrupador'];
  $id_presupuesto=$_SESSION['xtructur']['id_presupuesto'];

?>
<input type="hidden" id="id_area" value="<?php echo $id; ?>">
<input type="hidden" id="id_especialidad" value="<?php echo $id_especialidad; ?>">
<input type="hidden" id="id_agrupador" value="<?php echo $id_agrupador; ?>">
<input type="hidden" id="id_presupuesto" value="<?php echo $id_presupuesto; ?>">
<div id="contjs">
<script type="text/javascript">
$.ajax({
    url:'ajax.php',
    type: 'POST',
    data: {opcion:'cat_partidas'},
    success: function(r){
      console.log(r);
      id_area=$('#id_area').val();
      id_especialidad=$('#id_especialidad').val();
      jQuery("#rowed22").jqGrid({
        url:'sql_jspartida.php?q=3&id_area='+id_area,
        datatype: "json",
        colNames:['','Codigo','Codigo', 'Partida'],
        colModel:[
          {name:'icon', width:20,sortable:false,search:false},
          {name:'id',index:'id', width:55, hidden: true, editable:true, editoptions:{defaultValue:id_area}},
          {name:'codigo',index:'codigo', width:80, editable:false,
            editrules: {edithidden:false},
            hidden:false
          },   

          {name:'nombre',index:'nombre', width:150, sortable:false,editable:true,edittype:"select",
            editoptions:{ 
              value:"0:Selecciona;"+r,
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  op = $(this).val();
                  if(op=='Otro'){
                    console.log(this.id);
                    $('#'+this.id).after('<input id="na" type="text" placeholder="Escribe tu partida" style="margin: 5px 0 3px 4px; width: 271px;">');
                  }else{
                    $('#na').remove();
                  }
                } 
              }]
            },
          }
        ],
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,   
        pager: '#prowed22',
        sortname: 'id',
        viewrecords: true,
        sortorder: "asc",
        autowidth: true,
        footerrow: true,
        multiselect: true,
        gridComplete: function(){
          var ids = jQuery("#rowed22").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn6 = $('#rowed22').jqGrid('getCell',ids[i],'nombre');
            be = '<a style="cursor:pointer;" onclick="cambio(4,'+ids[i]+');"><img src="jqgrid/images/folder_open.png" style="cursor:pointer;" ></a>'; 
            jQuery("#rowed22").jqGrid('setRowData',ids[i],{icon:be});
          } 
          var sum_costo = $("#rowed22").jqGrid('getCol','total_costo',false,'sum');
          var sum_venta = $("#rowed22").jqGrid('getCol','total_venta',false,'sum');
          $("#rowed22").jqGrid('footerData','set',{nombre:'Totales:',total_costo:sum_costo,total_venta:sum_venta});

        },
        editurl: "sql_jspartida.php?id_area="+id_area+"&id_esp="+id_especialidad,

      });

      jQuery("#rowed22").jqGrid('navGrid',"#prowed22",{edit:true,add:true,del:true},
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');
          },
          beforeSubmit: function(postdata, formid){
            if(postdata.nombre==''){
              return [false,' Selecciona una Partida '];
            }else if(postdata.nombre=='Otro'){
              na=$('#na').val();
              if(na==''){
                return [false,' Escribe el nombre de tu Partida '];
              }else{
                postdata.nombre='.nv0.'+na;
                return [true,''];
              }
            }else{
              return [true,''];
            }
          },
          closeAfterEdit:true,
          width: 400
        },
        {
          afterSubmit : function(response, postdata) {
            if(response.responseText==""){
              return [true,''];
            }else{
              return [false, response.responseText, "lalala"];
            }
            
          },
          beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');

          },
          beforeSubmit: function(postdata, formid){
            if(postdata.nombre==''){
              return [false,' Selecciona una Partida '];
            }else if(postdata.nombre=='Otro'){
              na=$('#na').val();
              if(na==''){
                return [false,' Escribe el nombre de tu Partida '];
              }else{
                postdata.nombre='.nv0.'+na;
                return [true,''];
              }
            }else{
              return [true,''];
            }
          },
          closeAfterAdd:true,
          width: 400,
        },
        {width: 400}
      ).jqGrid('navButtonAdd', '#prowed22', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#rowed22").jqGrid('exportarExcelCliente',{nombre:"planeacion",formato:"excel"});
                //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
            },
            position: "last"
        });
    }
  });

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
            <a style="cursor:pointer;" onclick="cambio(2,<?php echo $id_especialidad; ?>);">Especialidad (<?php echo $id_nombre; ?>)</a> >
            <a style="cursor:pointer;" onclick="cambio(3,<?php echo $id; ?>);">Partida</a>
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



