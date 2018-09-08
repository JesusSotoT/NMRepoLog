<?php  

include('conexiondb.php');


    $idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $username_global=$row['username'];
  $id_username_global=$row['idempleado'];

  $sestmp=time();
  $id_des=$_POST['id_des']; //semana
  $id_prov=$_POST['destaver']; //id_prov
  $id_pedi=$_POST['oc_num']; //oc
  $estpor=$_POST['estpor']; //oc
  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }

  $SQL = "SELECT id FROM constru_estimaciones_bit_prov where estatus!=2 and id_prov='$id_prov' AND semana='$id_des' AND id_oc='$id_pedi' limit 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    echo 'EXIST';
    exit();
  }


  $SQL = "SELECT a.id, a.nombre FROM constru_agrupador a where a.id_obra='$id_obra' and a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $agrupadores[]=$row;
    }
  }else{
    $agrupadores=0;
  }
  $SQL = "SELECT a.*, concat('RT-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$id_obra' and a.borrado=0 AND a.id_tipo_alta=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $tecnicos[]=$row;
    }
  }else{
    $tecnicos=0;
  }

  $SQL = "SELECT id, cargo FROM constru_cuentas_cargo WHERE id_costo=25 ORDER by cargo;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $ccuentas[]=$row;
    }
  }else{
    $ccuentas=0;
  }
?>
<script> 
function formatoNumero(numero, decimales, separadorDecimal, separadorMiles) {
    var partes, array;

    if ( !isFinite(numero) || isNaN(numero = parseFloat(numero)) ) {
        return "";
    }
    if (typeof separadorDecimal==="undefined") {
        separadorDecimal = ",";
    }
    if (typeof separadorMiles==="undefined") {
        separadorMiles = "";
    }

    // Redondeamos
    if ( !isNaN(parseInt(decimales)) ) {
        if (decimales >= 0) {
            numero = numero.toFixed(decimales);
        } else {
            numero = (
                Math.round(numero / Math.pow(10, Math.abs(decimales))) * Math.pow(10, Math.abs(decimales))
            ).toFixed();
        }
    } else {
        numero = numero.toString();
    }

    // Damos formato
    partes = numero.split(".", 2);
    array = partes[0].split("");
    for (var i=array.length-3; i>0 && array[i-1]!=="-"; i-=3) {
        array.splice(i, 0, separadorMiles);
    }
    numero = array.join("");

    if (partes.length>1) {
        numero += separadorDecimal + partes[1];
    }

    return numero;
}

$(function() {

  $('#cargos').numeric();
  $('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 
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
            console.log(response);
            console.log(otro);
            if(response.statusText=='OK'){
              return [true];
            }else if(response.statusText=='RP'){
              return [false,' Elemento repetido '];
            }else{
              return [false,' Error al editar el registro '];
            }
          },
          closeAfterEdit:true,
          width: 480
      };

//$row[clave],$row[descripcion],$row[unidtext],$row[vol_tope],$row[precio],$row[total]



      sestmp=$('#sestmp').val();
      jQuery("#jq_requisiciones").jqGrid({
        url:'sql_jsest_prov.php?id_prov=<?php echo $id_prov; ?>&estpor=<?php echo $estpor; ?>&id_pedi=<?php echo $id_pedi; ?>&id_des=<?php echo $id_des; ?>&q=3&sestmp='+sestmp,
        datatype: "json",
        colNames:['Clave','Concepto','Unidad','Volumen OC','PU compra', 'Importe','Vol anterior','Vol. Estimar','Vol acumulado','Vol por ejecutar','Imp estimacion'],
        colModel:[
          {name:'clave',index:'clave', width:200, sortable:false,editable:true},
          {name:'concepto',index:'concepto', width:200, sortable:false,editable:true},
          {name:'unidtext',index:'unidtext', width:150, sortable:false,editable:true},
          {name:'volumen_oc',index:'volumen_oc', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'pu_compra',index:'pu_compra', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'importe',index:'importe', width:160, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},
          {name:'vol_anterior',index:'vol_anterior', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'entrada',index:'entrada', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'vol_acumulado',index:'vol_acumulado', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'vol_ejecutar',index:'vol_ejecutar', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'imp_estimacion',index:'imp_estimacion', width:200, sortable:false,editable:false,sorttype:"float", formatter:"number", summaryType:'sum'},

        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        pager: '#jqp_requisiciones',
        sortname: 'lol',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        multiselect: false,
        footerrow: true,
        gridComplete: function(){
          
          $('#preload').css('display','none');
          var sum = $("#jq_requisiciones").jqGrid('getCol','imp_estimacion',false,'sum');
          var sum2 = $("#jq_requisiciones").jqGrid('getCol','importe',false,'sum');

          $("#jq_requisiciones").jqGrid('footerData','set',{pu_compra:'Total: ',imp_estimacion:sum,importe:sum2});
 

          $("#imp_est").val(sum);
          $("#subt1").val(sum);
          iva=sum*0.16;
          $("#iva").val(iva);

          //$("#tota").val( uf(iae)*1 + uf(iaa)*1);

          $("#total").val( (sum*1) + (iva*1) );

          $("input:not(.ui-pg-input)").currency();

          $("#leg_estimacion").css('display','block');
          
          
        },
        editurl: 'sql_jsest_prov.php?id_prov=<?php echo $id_prov; ?>&estpor=<?php echo $estpor; ?>&id_pedi=<?php echo $id_pedi; ?>&id_des=<?php echo $id_des; ?>&q=3&sestmp='+sestmp,

        onCellSelect: function(rowid, iRow, iCol, e) {
          //$(this).jqGrid('editGridRow', rowid, formEditingOptions);
        }


      });
      
      jQuery("#jq_requisiciones").jqGrid('navGrid',"#jqp_requisiciones",
        {edit:false,add:false,del:false,search:false,
        },
        {beforeShowForm: function(form){

            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#cantidad').numeric();  

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
          $('#tr_vol_anterior', form).hide();
          //$('#tr_vol_anterior').prop('display','none');
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#cantidad').numeric(); 
          },
          beforeSubmit: function(postdata, formid){
            totcant=$('#totcant').val();
            vvol=$('#volant').val();
            $('#vol_anterior').val(vvol);
            if((postdata.cantidad*1)>(totcant*1)){
              return [false,' &nbsp; La cantidad es mayor a la permitida'];
            }else if(postdata.cantidad==0 || postdata.cantidad==''){
              return [false,' &nbsp; La cantidad es incorrecta'];
            }else{
              return [true,''];
            }
          },
          afterSubmit: function(response, otro){
            if(response.responseText=='RP'){
              return [false,' &nbsp; Este insumo ya esta agregado  '];
            }else{
              if(response.statusText=='OK'){
                return [true];
              }else{
                return [false,' &nbsp; Error al editar el registro '];
              }
            }
          },
          closeAfterAdd:true,
          width: 480
        },
        {width: 480}, // settings for delete
        {onSearch: function(data){
          $("#jq_requisiciones").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_requisiciones").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_requisiciones', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#jq_requisiciones").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
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



      
});

  </script> 

  <div class="row">&nbsp;</div>

  <div class="row">
    <div class="col-xs-12 tablaResponsiva">
      <div class="table-responsive" id="dtabla">
          <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
          <table id="jq_requisiciones"></table>
          <div id="jqp_requisiciones"></div>
      </div>
    </div>
  </div> 

<div class="row">&nbsp;</div>
<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Datos de la estimacion</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
      <div class="row">
      <div class="col-sm-3">
       <label>Solicito:</label>
            <div>
            <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
            </div>
            <input type='hidden' id="val_solicito" value='<?php echo $id_username_global; ?>'>
            <!--
            <select id="val_solicito" class="form-control">
              <option selected="selected" value="0">Seleccione</option>
              <?php 
              if($tecnicos!=0){
                foreach ($tecnicos as $k => $v) { ?>
                  <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
                <?php } ?>
              <?php }else{ ?>
                <option value="0">No hay tecnicos dados de alta</option>
              <?php } ?>
            </select>
            -->
    </div>
    
    <div class="col-sm-3">
      <label>Subtotal:</label>
      <input class="form-control" id="subt1" type="text" value="0" disabled="disabled">
    </div>
    <div class="col-sm-3">
      <label>Iva 16%:</label>
      <input class="form-control" id="iva" type="text" value="calculo" disabled="disabled">
    </div>
    <div class="col-sm-3">
      <label>Total:</label>
      <input class="form-control" id="total" type="text" value="0" disabled="disabled">
    </div>
  </div>
  <div class="row">
    <div class="col-sm-3">
      <label>Importe esta estimacion:</label>
      <input class="form-control" id="imp_est" type="text" value="0" disabled="disabled">
    </div>
    <div class="col-sm-3">
      <label>Factura:</label>
      <input class="form-control" type="text" id="fact" value="">
    </div>
    <div class="col-sm-3">
      <label>&nbsp;</label>
       <button style="width:100%" id="btngenestpro"  class="btn btn-primary btn-xm pull-right" onclick="generaEst_Pro(<?php echo $sestmp; ?>,'<?php echo $id_des; ?>',<?php echo $id_prov; ?>,<?php echo $id_pedi; ?>);"> Generar Estimacion</button>

 
    </div>
    <div class="col-sm-3"></div>
  </div>
      
  </div><!-- ENd panel body -->
</div>


  

  
