<?php 
///chais
require_once("conexiondb.php");
/// asi se llama id_obra//
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
  } 

$SQL = "SELECT a.id_destajista, concat('IDDES-',a.id_destajista,' ',b.nombre,' ',b.paterno,' ',b.materno) as nombre
        FROM constru_estimaciones_bit_destajista a
        left join constru_info_tdo b on b.id_alta=a.id_destajista
        WHERE a.id_obra='$id_obra' AND estatus='1'
        group by a.id_destajista;";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $retencion_des[]=$row;
        }
      }else{
        $retencion_des=0;
      }   

$SQL = "SELECT a.id_subcontratista, concat('IDSUB-',a.id_subcontratista,' ',b.razon_social_sp) as nombre
        FROM constru_estimaciones_bit_subcontratista a
        left join constru_info_sp b on b.id_alta=a.id_subcontratista
        WHERE a.id_obra='$id_obra' AND estatus='1'
        group by a.id_subcontratista";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $retencion_sub[]=$row;
        }
      }else{
        $retencion_sub=0;
      }     

///chais?>
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
    
      id_partida=45;
      
      jQuery("#jq_alta_familia").jqGrid({
        url:'sql_jsretenciones.php?q=3',
        datatype: "json",
        colNames:['Tipo','Nombre','Retenciones','Fondo de garantia'],
        colModel:[
          {name:'tipo',index:'tipo', width:20, editable:false,
            editrules: {edithidden:false},
            hidden:false
          },
          {name:'nombre',index:'nombre', width:50, editable:false,
            editrules: {edithidden:false},
            hidden:false
          },
          {name:'retenciones',index:'retenciones', width:20, editable:false,
            editrules: {edithidden:false},
            hidden:false
          },
          {name:'fondo_garantia',index:'fondo_garantia', width:20, editable:true,
            editrules: {edithidden:false},
            hidden:false
          }
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        pager: '#jqp_alta_familia',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        multiselect: true,
        gridComplete: function(){
          var ids = jQuery("#jq_alta_familia").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_alta_familia').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_alta_familia").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_jsretenciones.php",
        
        onCellSelect: function(rowid, iRow, iCol, e) {
          $(this).jqGrid('editGridRow', rowid, formEditingOptions);
        }


      });

      jQuery("#jq_alta_familia").jqGrid('navGrid',"#jqp_alta_familia",
        {edit:true,add:true,del:false,search:true,
        },
        {beforeShowForm: function(form){ 
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
        },
        
        {beforeShowForm: function(form){ 
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
              return [false,' Error al guardar el registro '];
            }
          },
          closeAfterAdd:true,
          width: 480
        },
        {}, // settings for delete
        {onSearch: function(data){
          $("#jq_alta_familia").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_alta_familia").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_alta_familia', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_alta_familia").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
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
              <div class="navbar-brand" style="color:#333;">Retenciones y fondos de garantia</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_alta_familia"></table>
                <div id="jqp_alta_familia"></div>
            </div>
          </div>
        </div> 
      </div>
    </div>





<div class="row">&nbsp;</div>

<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Ver retenciones</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <div class="row">
  <div class="col-sm-3">
   <button id="btngraficarDesAll" style="width: 100%"  class="btn btn-primary btn-xm pull-right" onclick="graficar_ret('des_todos',<?php echo $id_obra?>)"> Destajistas</button>
  </div>
  <div class="col-sm-6">
    <select id="id_ret_des" onchange="cmbgra('des_uno')" class="form-control">
      <option selected="selected" value="0">Selecciona un Destajista</option>
      <?php 
      if($retencion_des!=0){
        foreach ($retencion_des as $k => $v) { ?>
          <option value="<?php echo $v['id_destajista'];?>"><?php echo $v['nombre']; ?></option>
        <?php } ?>
      <?php }else{ ?>
        <option value="0">No hay Destajista</option>
      <?php } ?>  
    </select>
  </div>
  <div class="col-sm-3">
  <button id="btngraficarDesUno" style="width: 100%"  class="btn btn-primary btn-xm pull-right" onclick="graficar_ret('des_uno',<?php echo $id_obra?>)"> Graficar Dest</button>

 
  </div>
</div>
<h5>&nbsp;</h5>
<div class="row">
  <div class="col-sm-3">
  <button id="btngraficarSubAll" style="width: 100%"  class="btn btn-primary btn-xm pull-right" onclick="graficar_ret('sub_todos',<?php echo $id_obra?>)"> Subcontratistas</button>
    
  </div>
  <div class="col-sm-6">
    <select id="id_ret_sub" onchange="cmbgra('sub_uno')" class="form-control">
      <option selected="selected" value="0">Selecciona un Subcontratista</option>
      <?php 
      if($retencion_sub!=0){
        foreach ($retencion_sub as $k => $v) { ?>
          <option value="<?php echo $v['id_subcontratista'];?>"><?php echo $v['nombre']; ?></option>
        <?php } ?>
      <?php }else{ ?>
        <option value="0">No hay Subcontratista</option>
      <?php } ?>
    </select>
  </div>
  <div class="col-sm-3">
  <button id="btngraficarSubUno" style="width: 100%"  class="btn btn-primary btn-xm pull-right" onclick="graficar_ret('sub_uno',<?php echo $id_obra?>)"> Graficar Sub</button>

  </div>
</div>
          
      </div><!-- ENd panel body -->
    </div>




</body>