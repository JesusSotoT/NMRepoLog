<div class="row">&nbsp;</div>

<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Avance de Obra</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
      <div class="row">
  <div class="col-sm-6">
    <!--
    <div class="row">
      <div class="col-sm-6" >
        <label>Agrupador:</label>
        <select class="form-control" id="cargaagr" onchange="chagru2();">
          <option selected="selected" value="0">Seleccione un agrupador</option>
          <?php 
          if($agrupadores!=0){
            foreach ($agrupadores as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay agrupadores dados de alta</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Area:</label>
        <select class="form-control" id="cargaesp">
          <option selected="selected" value="0">Selecciona un area</option>
        </select>
      </div>
    </div>
    -->
    <div class="row">
      <div class="col-sm-6" >
        <label>Dia:</label>
       <?php echo  date('Y-m-d');?>
      </div>
  </div>
  <!--
  <div class="col-sm-6">
    <h5>Ver estimaciones cliente</h5>
    <div class="row">
      <div class="col-sm-6">
        <label>Estimacion:</label>
        <select class="form-control" id="estimacion_num">
          <option selected="selected" value="0">Seleccione una estimacion</option>
            <?php 
            if($estimas!=0){
              foreach ($estimas as $k => $v) { ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['estimacion']; ?></option>
              <?php } ?>
            <?php }else{ ?>
              <option value="0">No hay estimaciones a cliente creadas</option>
            <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" type="button" value="Ver estimacion" onclick="verest('cli');" style="cursor:pointer;">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" type="button" value="Graficar" onclick="graficar_ret('est_cliente',<?php echo $idses_obra?>)" style="cursor:pointer;">
      </div>
    </div>
  </div>
</div>
-->
      
  </div><!-- ENd panel body -->
</div>
</div>


<div class="row">


  <div class="col-sm-12" id="estdestajista">
   
  </div>
</div>
</div>

<style>
.ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    display: none;
    min-width: 160px;   
    padding: 4px 0;
    margin: 0 0 10px 25px;
    list-style: none;
    background-color: #ffffff;
    border-color: #ccc;
    border-color: rgba(0, 0, 0, 0.2);
    border-style: solid;
    border-width: 1px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    *border-right-width: 2px;
    *border-bottom-width: 2px;
    font-size: 10px;
}
</style>
<?php  
  $sestmp=time();
  $id_des=$_POST['id_des'];
  $sema=$_POST['sema'];

  $ag=$_POST['ag'];
  $ar=$_POST['ar'];
  $es=0;
  $pa=0;
  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }
$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $username_global=$row['username'];
  $id_username_global=$row['idempleado'];

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
 $SQL = "SELECT if(sum(amortizado_estimacion) is null,0,sum(amortizado_estimacion)) amoa FROM constru_estimaciones_bit_cliente  where id_obra='$id_obra' and borrado=0 AND id_cliente='$id_obra' ORDER BY id desc LIMIT 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $amoa=$row['amoa'];
  }else{
    $amoa=0;
  }

  $SQL = "SELECT presupuesto as imp_cont,ade1,ade2,ade3,anticipo FROM constru_generales  where id='$id_obra' LIMIT 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
  }else{
    $row=0;
  }

    $cad_agrupador='';
  $SQL = "SELECT a.id, a.nombre FROM constru_agrupador a where a.id_obra='$id_obra' and a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {

    while($rowx = $result->fetch_array() ) {
      $agrupadores[]=$rowx;
      $cad_agrupador.=$rowx['id'].':'.addslashes(preg_replace('/;/', ' ',$rowx['nombre'])).';';
    }
    $cad_agrupador=trim($cad_agrupador,';');
  }else{
    $agrupadores=0;
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
  $('#fgp').numeric();
  $('#rep').numeric();

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


  $.ajax({
    url:'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: {opcion:'est_control_cliente',ag:'<?php echo $ag; ?>',ar:'<?php echo $ar; ?>',es:'<?php echo $es; ?>',pa:'<?php echo $pa; ?>',sestmp:'<php echo $sestmp; ?>'},
    success: function(r){
      sestmp=$('#sestmp').val();
      jQuery("#jq_requisiciones").jqGrid({
        url:'sql_jsavance.php?ar=<?php echo $ar; ?>&id_des=<?php echo $id_des; ?>&q=3&sestmp='+sestmp,
        datatype: "json",
        colNames:['Agrupador','Area','Especialidad','Partida','Clave','Descripcion','Unidad','Vol. Tope','PU contrato', 'Importe', 'Vol anterior','Vol acumulado','Vol faltante','Vol. Avance'],
        colModel:[
        {name:'Agrupador',index:'Agrupador',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;'+r.insumos },editrules: {edithidden:true},
            hidden:true,
            editoptions:{value:'0:Selecciona;'+'<?php echo $cad_agrupador; ?>',
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_agrupador = $(this).val();
                  //$('.FormData#lainfo_adic').remove();
                  if(id_agrupador>0){
                    /*
                    $('.FormData#lainfo_adic_load_not').remove();
                    $('<tr rowpos="3" class="FormData" id="lainfo_adic_load">\
                        <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                        <td class="DataTD">&nbsp;\
                      Cargando...\
                        </td>\
                      </tr>').insertAfter('#tr_id_clave');
                    */


                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    data:{opcion:'areas_dinamic_combo',id_agrupador:id_agrupador},
                    success: function(r){
                        $('#Area').html('<option role="option" value="0">Seleccione</option>'+r);
                      }
                    });
                  }
                } 
              }]
            }
          },
          {name:'Area',index:'Area',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;'+r.insumos },editrules: {edithidden:true},
            hidden:true,
            editoptions:{value:'0:Selecciona',
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_area = $(this).val();
                  if(id_area>0){
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    data:{opcion:'especialidad_dinamic_combo',id_area:id_area},
                    success: function(r){
                        $('#Especialidad').html('<option role="option" value="0">Seleccione</option>'+r);
                      }
                    });
                  }
                } 
              }]
            }
          },
          {name:'Especialidad',index:'Especialidad',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;'+r.insumos },editrules: {edithidden:true},
            hidden:true,
            editoptions:{value:'0:Selecciona',
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_especialidad = $(this).val();
                  if(id_especialidad>0){
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    data:{opcion:'partida_dinamic_combo',id_especialidad:id_especialidad},
                    success: function(r){
                        $('#Partida').html('<option role="option" value="0">Seleccione</option>'+r);
                      }
                    });
                  }
                } 
              }]
            }
          },
          {name:'Partida',index:'Partida',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;'+r.insumos },editrules: {edithidden:true},
            hidden:true,
            editoptions:{value:'0:Selecciona',
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_partida = $(this).val();
                  if(id_partida>0){
                    ar = $('#Area').val();
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    data:{opcion:'claves_dinamic_combo_d',pa:id_partida,ar:ar},
                    success: function(r){
                        $('#id_clave').html('<option role="option" value="0">Seleccione</option>'+r);
                      }
                    });
                  }
                } 
              }]
            }
          },
          /*
          {name:'Area',index:'Area',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;'+r.insumos },editrules: {edithidden:true},
            hidden:true,
            editoptions:{value:'0:Selecciona',
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_area = $(this).val();
                  if(id_area>0){
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    data:{opcion:'especialidad_dinamic_combo_cliente',id_area:id_area},
                    success: function(r){
                        $('#id_clave').html('<option role="option" value="0">Seleccione</option>'+r);
                      }
                    });
                  }
                } 
              }]
            }
          },
          */
          {name:'id_clave',index:'id_clave',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;'+r.insumos },
            editoptions:{value:'0:Selecciona;'+r.insumos,
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_insumo = $(this).val();
                  $('.FormData#lainfo_adic').remove();
                  if(id_insumo>0){
                    $('.FormData#lainfo_adic_load_not').remove();
                    $('<tr rowpos="3" class="FormData" id="lainfo_adic_load">\
                        <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                        <td class="DataTD">&nbsp;\
                      Cargando...\
                        </td>\
                      </tr>').insertAfter('#tr_id_clave');


                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'desc_avance',id_codigo:id_insumo,ar:'<?php echo $ar; ?>'},
                    success: function(r){
                      console.log(r.datos.totcant);
                      if(r.success==1){

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Vol. Tope</font></td>\
  <td class="DataTD">&nbsp;\
<input id="vol_tope" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos.totcant+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Vol. Anterior</font></td>\
  <td class="DataTD">&nbsp;\
<input id="volant" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos.vol_anterior+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');
                        $('#vol_anterior').val(r.datos.vol_anterior);
                        

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">PU de concurso</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].precio+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Descripcion</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].descripcion+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');
                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Unidad</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].unidtext+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Max Cantidad</font></td>\
  <td class="DataTD">&nbsp;\
<input id="totcant" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos.totcant+'">\
  </td>\
</tr>').insertAfter('#tr_vol_tope');

                       $('.FormData#lainfo_adic_load').remove();


                        }else{

                          $('.FormData#lainfo_adic_load').remove();

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic_load_not">\
                              <td class="CaptionTD"><font color="#cecece">&nbsp;.</font></td>\
                              <td class="DataTD">&nbsp;\
                            No hay datos\
                              </td>\
                            </tr>').insertAfter('#tr_id_clave');

                          

                        }
                      }
                    });
                  }
                } 
              }]
            }
          },

          {name:'descripcion',index:'unidad', width:70, editable:false,
            editrules: {edithidden:true},
            hidden:false
          },

          {name:'unidad',index:'unidad', width:50, editable:false,
            editrules: {edithidden:true},
            hidden:false
          },
          {name:'vol_tope',index:'vol_tope', width:70, editable:false,
            editrules: {edithidden:true},
            hidden:false
          },
          {name:'precio',index:'precio', width:70, editable:false,
            editrules: {edithidden:true},
            hidden:false
          },
          {name:'importe',index:'importe', width:70, editable:false, sorttype:"float", formatter:"number", summaryType:'sum',
            editrules: {edithidden:true},
            hidden:false
          },
          {name:'vol_anterior',index:'vol_anterior', width:70, editable:true, sorttype:"float", formatter:"number",
            editrules: {edithidden:true},
            hidden:false
          },
       
          {name:'acumulado',index:'acumulado', width:70, editable:false, sorttype:"float", formatter:"number",
            editrules: {edithidden:true},
            hidden:false
          },
          {name:'vol_ejecutar',index:'vol_ejecutar', width:70, editable:false, sorttype:"float", formatter:"number",
            editrules: {edithidden:true},
            hidden:false
          },
          {name:'vol_estimacion',index:'vol_estimacion', width:70, editable:true, sorttype:"float", formatter:"number",
            editrules: {edithidden:true},
            hidden:false
          },
        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        pager: '#jqp_requisiciones',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        multiselect: true,
        footerrow: false,
        gridComplete: function(){


          
          $('#preload').css('display','none');
          var sum = $("#jq_requisiciones").jqGrid('getCol','importe',false,'sum');
          var sum2 = $("#jq_requisiciones").jqGrid('getCol','importe_est',false,'sum');
          $("#jq_requisiciones").jqGrid('footerData','set',{precio:'Total: ',importe:sum});
          $("#jq_requisiciones").jqGrid('footerData','set',{vol_ejecutar:'Total: ',importe_est:sum2});

         // $("#imp_con").val(sum);
  


          iaa = $("#iaa").val();

          iae = $("#iae").val();

          tota = $("#tota").val();

       

          $("#imp_est").val(sum2);
          $("#subt1").val(sum2*1 - iae*1);

       

         
          
          var ids = jQuery("#jq_requisiciones").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_requisiciones').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_requisiciones").jqGrid('setRowData',ids[i],{icon:be});
          }
          $("#leg_estimacion").css('display','block');
          
        },
        editurl: "sql_jsavance.php?ar=<?php echo $ar; ?>&id_des=<?php echo $id_des; ?>&sestmp="+sestmp,

        onCellSelect: function(rowid, iRow, iCol, e) {
          //$(this).jqGrid('editGridRow', rowid, formEditingOptions);
        }


      });
      
      jQuery("#jq_requisiciones").jqGrid('navGrid',"#jqp_requisiciones",
        {edit:false,add:true,del:true,search:false,
        },
        {beforeShowForm: function(form){

            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#cantidad').numeric();  

            id_insumo = $('#id_clave').val();
                  $('.FormData#lainfo_adic').remove();
                  if(id_insumo>0){
                    $('.FormData#lainfo_adic_load_not').remove();
                    $('<tr rowpos="3" class="FormData" id="lainfo_adic_load">\
                        <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                        <td class="DataTD">&nbsp;\
                      Cargando...\
                        </td>\
                      </tr>').insertAfter('#tr_id_clave');


                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'desc_insumos',id_insumo:id_insumo},
                    success: function(r){
                      if(r.success==1){

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Precio</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].precio+'">\
  </td>\
</tr>').insertBefore('#tr_fecha_entrega');

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Descripcion</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].descripcion+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');
                          $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Unidad</font></td>\
  <td class="DataTD">&nbsp;\
<input disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos[0].unidtext+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');

                       $('.FormData#lainfo_adic_load').remove();


                        }else{

                          $('.FormData#lainfo_adic_load').remove();

                          $('<tr rowpos="3" class="FormData" id="lainfo_adic_load_not">\
                              <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
                              <td class="DataTD">&nbsp;\
                            No hay datos\
                              </td>\
                            </tr>').insertAfter('#tr_id_clave');

                          

                        }
                      }
                    });
                  }
          },

          closeAfterEdit:true,
          width: 480
        },
        
        {beforeShowForm: function(form){

           (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          //alert($('#id_clave').val());
          //$("#id_clave").val($('#id_clave').val());

          $('#id_clave').val($('#id_clave').val()).trigger('change');
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " No existe este elemento" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
 /*
  $(function() {
    $( "#id_clave" ).combobox();
  });
*/
          $('#tr_vol_anterior', form).hide();
          //$('#tr_vol_anterior').prop('display','none');
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','278px');
            $('.FormGrid textarea').css('height','80px');
            $('#cantidad').numeric(); 
          },
          beforeSubmit: function(postdata, formid){

            vol_tope=$('#vol_tope').val();
            vvol=$('#volant').val();
            volest=$('#vol_estimacion').val();
            $('#vol_anterior').val(vvol);
            nopasar=(volest*1)+(vvol*1);

            
            if( (nopasar*1)>(vol_tope*1) ){
              return [false,' &nbsp; El volumen de la estimacion es mayor al tope permitido'];
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

      

    }
  });

      
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

<div class="row">&nbsp;</div>





<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Datos del Avance</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
    <div class="row">
      <div class="col-sm-3" style="padding-top:10px">
    <label>Usuario :</label>
   <div>
            <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
            </div>
            <input type='hidden' id="val_solicito" value='<?php echo $id_username_global; ?>'>
  </div>


</div>

<div class="row">

  <div class="col-sm-3">
    <label>&nbsp;</label>
 

     <button style="width:100%" id="btnGenReq"  class="btn btn-primary btn-xm pull-right" onclick="generaAvance(<?php echo $sestmp; ?>,0,'<?php echo $sema; ?>');"> Guardar</button>

  </div>
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
</div>
      
  </div><!-- ENd panel body -->
</div>