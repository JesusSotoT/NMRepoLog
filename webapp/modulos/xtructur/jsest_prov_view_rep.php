<?php  
  $sestmp=time();
  $id_des=$_POST['id'];
  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }
  $SQL = "SELECT a.id, a.factura, concat('RT-',b.id,' ',d.nombre,' ',d.paterno,' ',d.materno) as aut, a.estatus FROM constru_estimaciones_bit_prov a 
LEFT JOIN constru_altas b on b.id=a.id_autorizo 
LEFT JOIN constru_info_tdo d on d.id_alta=b.id where a.id_obra='$id_obra' AND a.id='$id_des';";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $factura=$row['factura'];
    $idest=$row['id'];
    $aut=$row['aut'];
  }else{

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
        url:'sql_jsest_prov_rep.php?id_des=<?php echo $id_des; ?>&q=3&sestmp='+sestmp,
        datatype: "json",
        colNames:['Clave','Concepto','Unidad','Volumen OC','PU compra', 'Importe','Vol anterior','Entrada','Vol acumulado','Vol por ejecutar','Imp estimacion'],
        colModel:[
          {name:'clave',index:'clave', width:200, sortable:false,editable:true},
          {name:'concepto',index:'concepto', width:200, sortable:false,editable:true},
          {name:'unidtext',index:'unidtext', width:150, sortable:false,editable:true},
          {name:'volumen_oc',index:'volumen_oc', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'pu_compra',index:'pu_compra', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
          {name:'importe',index:'importe', width:160, sortable:false,editable:true,sorttype:"float",formatter:"number"},
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
        width: "1035",
        height: "300",
        multiselect: false,
        footerrow: true,
        gridComplete: function(){
          
          $('#preload').css('display','none');
          var sum = $("#jq_requisiciones").jqGrid('getCol','imp_estimacion',false,'sum');

          $("#jq_requisiciones").jqGrid('footerData','set',{pu_compra:'Total: ',imp_estimacion:sum});
 

          $("#imp_est").val(sum);
          $("#subt1").val(sum);
          iva=sum*0.16;
          $("#iva").val(iva);

          //$("#tota").val( uf(iae)*1 + uf(iaa)*1);

          $("#total").val( (sum*1) + (iva*1) );

          $("input:not(.ui-pg-input)").currency();

          $("#leg_estimacion").css('display','block');
          
          
        },
        editurl:'sql_jsest_prov_rep.php?id_des=<?php echo $id_des; ?>&q=3&sestmp='+sestmp,
        caption:"Estimaciones de proveedores",
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
  <div class="row">
    <div class="col-xs-12 tablaResponsiva">
      <div class="table-responsive" id="dtabla">
          <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
          <table id="jq_requisiciones"></table>
          <div id="jqp_requisiciones"></div>
      </div>
    </div>
  </div> 
  <h4>Generar estimacion</h4>
  <div class="row">
    <div class="col-sm-3">
      <label>Importe esta estimacion:</label>
      <input class="form-control" id="imp_est" type="text" value="0" disabled="disabled">
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
      <label>Solicito:</label><br>
      <?php echo $aut; ?>
    </div>
    <div class="col-sm-3">
      <label>Factura:</label>
      <input class="form-control" type="text" id="modiFac" value="<?php echo $factura; ?>">
    </div>
    <div class="col-sm-3">
      <label>&nbsp;</label>
      <input class="btn btn-primary btnMenu" type="button" value="Guardar" onclick="modiFac('<?php echo $idest; ?>','prov');">
    </div>
    <div class="col-sm-3"></div>
  </div>
  <?php if ($row['estatus']==0){ ?>
    <div class="row">
      <div class="col-sm-3">
        <input class="best btn btn-primary btnMenu" style="cursor:pointer;" type="button" value="Autorizar" onclick="autorizarest('prov',1);"> 
      </div>
      <div class="col-sm-3">
        <input class="best btn btn-danger btnMenu" style="cursor:pointer;" type="button" value="Cancelar" onclick="autorizarest('prov',2);">
      </div>
    </div>
  <?php } ?>
  <?php if ($row['estatus']==1){ ?>
    <div class="row">
      <div class="col-sm-3">
        <b><font color="green">Estimacion aceptada</font></b>
      </div>
      <div class="col-sm-3">
        <input class="btn btn-primary btnMenu" id="btnpdfprov" type="button" value="PDF" onclick="pdf_est_prov('prov')" style="cursor:pointer;">
      </div>
    </div>
  <?php } ?>
  <?php if ($row['estatus']==2){ ?>
    <div class="row">
      <div class="col-sm-3">
        <b><font color="#ff0000">Estimacion rechazada</font></b>
      </div>
      <div class="col-sm-3">
        <input class="btn btn-primary btnMenu" id="btnpdfprov" type="button" value="PDF" onclick="pdf_est_prov('prov')" style="cursor:pointer;">
      </div>
    </div>
  <?php } ?>

