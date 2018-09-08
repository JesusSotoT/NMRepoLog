<?php

  @session_start();
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
  }
 
  include('conexiondb.php');

  $SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
  $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $id_presupuesto=$row['id'];

  $_SESSION['xtructur']['id_presupuesto']=$id_presupuesto;
?>
<div id="contjs">
<input type="hidden" id="id_presupuesto" value="<?php echo $id_presupuesto; ?>">
<script>
$(function() {
      jQuery("#rowed22").jqGrid({
        url:'sql_jsagrupador.php',
        datatype: "json",
        colNames:['', 'Codigo', 'Agrupador'],
        colModel:[
          {name:'icon', width:20,sortable:false,search:false},
          {name:'codigo',index:'codigo', width:80, editable:false, editrules: {edithidden:false}, hidden:false },
          {name:'nombre',index:'nombre', width:150, sortable:false,editable:true,edittype:"select",
            editoptions:{ 
              value:"0:Selecciona;Proyecto:Proyecto integral;Construccion:Construccion;Otro:Otro",
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  op = $(this).val();
                  if(op=='Otro'){
                    console.log(this.id);
                    $('#'+this.id).after('<input id="na" type="text" placeholder="Escribe tu agrupador" style="margin: 5px 0 3px 4px; width: 271px;">');
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
        sortorder: "desc",
        autowidth: true,
        footerrow: false,
        multiselect:true,
        gridComplete: function(){
          var ids = jQuery("#rowed22").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn3 = $('#rowed22').jqGrid('getCell',ids[i],'nombre');
            be = '<a style="cursor:pointer;" onclick="cambio(1,'+ids[i]+');"><img src="jqgrid/images/folder_open.png" style="cursor:pointer;" ></a>'; 
            jQuery("#rowed22").jqGrid('setRowData',ids[i],{icon:be});
          }
          var sum_costo = $("#rowed22").jqGrid('getCol','total_costo',false,'sum');
          var sum_venta = $("#rowed22").jqGrid('getCol','total_venta',false,'sum');
          $("#rowed22").jqGrid('footerData','set',{nombre:'Totales:',total_costo:sum_costo,total_venta:sum_venta});
        },
        editurl: "sql_jsagrupador.php",
        
      });

      jQuery("#rowed22").jqGrid('navGrid',"#prowed22",{edit:true,add:true,del:true},
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');
          },
          beforeSubmit: function(postdata, formid){
            if(postdata.nombre==''){
              return [false,' Selecciona un Agrupador '];
            }else if(postdata.nombre=='Otro'){
              na=$('#na').val();
              if(na==''){
                return [false,' Escribe el nombre de tu Agrupador '];
              }else{
                postdata.nombre=na;
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
          beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');

          },
          beforeSubmit: function(postdata, formid){
            if(postdata.nombre==''){
              return [false,' Selecciona un Agrupador '];
            }else if(postdata.nombre=='Otro'){
              na=$('#na').val();
              if(na==''){
                return [false,' Escribe el nombre de tu Agrupador '];
              }else{
                postdata.nombre=na;
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
            },
            position: "last"
        });
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
            <a style="cursor:pointer;" onclick="cambio(0);">Agrupador</a>
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



