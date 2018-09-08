<?php  

  include('conexiondb.php');


    $idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = $mysqli->query($SQL);
  $row = $result->fetch_array();
  $username_global=$row['username'];
  $id_username_global=$row['idempleado'];


  $sestmp=time();
  $id_des=$_POST['id_des'];
  $sema=$_POST['sema'];

  $ag=$_POST['ag'];
  $ar=$_POST['ar'];
  $es=$_POST['es'];
  $pa=$_POST['pa'];

  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }

  $SQL = "SELECT id FROM constru_estimaciones_bit_destajista where estatus!=2 and id_destajista='$id_des' AND semana='$sema' AND id_agru='$ag' AND id_area='$ar' AND id_esp='$es' AND id_part='$pa' limit 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    echo 'EXIST';
    exit();
  }

  $SQL = "SELECT id, cc FROM constru_cuentas_cc ORDER by id;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $cc[]=$row;
    }
  }else{
    $cc=0;
  }


  $cad_agrupador='';
  $SQL = "SELECT a.id, a.nombre FROM constru_agrupador a where a.id_obra='$id_obra' and a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {

    while($row = $result->fetch_array() ) {
      $agrupadores[]=$row;
      $cad_agrupador.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['nombre'])).';';
    }
    $cad_agrupador=trim($cad_agrupador,';');
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

  $SQL = "SELECT limitar FROM constru_config where id_obra='$id_obra';";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $rowlimit = $result->fetch_array();
    $limitar=$rowlimit['limitar'];
  }else{
    $limitar=0;
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
  $( "#cargos" ).keyup(function() {

    subt2= uf($("#retencion").val())*1 + uf($("#cargos").val())*1;
    $("#subt2").val(subt2).currency();
    total = uf($("#subt1").val())*1 - uf($("#subt2").val())*1
    $("#total").val(total).currency();
  });

  $('#cargos').numeric();

  $( "#rep" ).keyup(function() {
      rep=uf($(this).val());
      trep= (rep/100)*(uf($("#imp_est").val())*1); 
      $("#retencion").val(trep).currency();

      subt2 = uf($("#retencion").val())*1 + uf($("#cargos").val())*1;
      $("#subt2").val(subt2).currency();
    total = uf($("#subt1").val())*1 - uf($("#subt2").val())*1;
    $("#total").val(total).currency();

  });

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
    data: {opcion:'est_control_destajos',ag:'<?php echo $ag; ?>',ar:'<?php echo $ar; ?>',es:'<?php echo $es; ?>',pa:'<?php echo $pa; ?>',sestmp:'<?php echo $sestmp; ?>'},
    success: function(r){
      sestmp=$('#sestmp').val();
      jQuery("#jq_requisiciones").jqGrid({
        url:'sql_jsest_destajistas.php?ar=<?php echo $ar; ?>&sema=<?php echo $sema; ?>&id_des=<?php echo $id_des; ?>&q=3&sestmp='+sestmp,
        datatype: "json",
        colNames:['Agrupador','Area','Especialidad','Partida','Clave','Descripcion','Unidad','Vol. Tope','PU destajo', 'Importe', 'Vol anterior', 'Vol. Estimacion','Vol acumulado','Vol ejecutar','Imp. Estimacion'],
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
          {name:'id_clave',index:'id_clave',stype: 'select', width:70, sortable:true,editable:true,edittype:"select",
            searchoptions:{sopt:['eq'], value:'0:Selec\"ciona;'+r.insumos },
            editoptions:{value:'0:Selecciona',
            dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_insumo = $(this).val();
                  limitar='<?php echo $limitar; ?>';
                  if (limitar==1){
                    textovol='Vol. Avance obra';
                  }else{
                    textovol='Vol. Tope area';
                  }

                  $('.FormData#lainfo_adic').remove();
                  if(id_insumo>0){
                    ar = $('#Area').val();
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
                    data:{opcion:'desc_destaj_est',id_codigo:id_insumo,id_des:'<?php echo $id_des; ?>',ar:ar,limitar:limitar},
                    success: function(r){
                      console.log(r.datos);
                      if(r.success==1){
                        //$("#jq_requisiciones").jqGrid('setGridParam', 'myNewUrl/foo');
                
                        $("#jq_requisiciones").jqGrid('setGridParam', {url : "sql_jsest_destajistas.php?ar="+$('#Area').val()+"&sema=<?php echo $sema; ?>&id_des=<?php echo $id_des; ?>&sestmp="+sestmp});

                        //jQuery("#grillausers").jqGrid('getGridParam', 'selrow');
                        //$("#jq_requisiciones").getGridParam('editurl');
                        //$("#jq_requisiciones").setGridParam({editurl:"page.php?parameter=bye"});

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">'+textovol+'</font></td>\
  <td class="DataTD">&nbsp;\
<input id="vol_tope" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos.totcant+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');
                    //    $('#vol_anterior').val(r.datos.vol_anterior);

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">Vol. Anterior</font></td>\
  <td class="DataTD">&nbsp;\
<input id="volant" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 278px;" value="'+r.datos.vol_anterior+'">\
  </td>\
</tr>').insertAfter('#tr_id_clave');
                        $('#vol_anterior').val(r.datos.vol_anterior);
                        

                        $('<tr rowpos="3" class="FormData" id="lainfo_adic">\
  <td class="CaptionTD"><font color="#cecece">PU destajo</font></td>\
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
                              <td class="CaptionTD"><font color="#cecece">&nbsp;</font></td>\
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
          {name:'vol_estimacion',index:'vol_estimacion', width:70, editable:true, sorttype:"float", formatter:"number",
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
          {name:'importe_est',index:'importe_est', width:70, editable:false, sorttype:"float", formatter:"number", summaryType:'sum',
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
        footerrow: true,
        gridComplete: function(){
          
          $('#preload').css('display','none');
          var sum = $("#jq_requisiciones").jqGrid('getCol','importe',false,'sum');
          var sum2 = $("#jq_requisiciones").jqGrid('getCol','importe_est',false,'sum');
          $("#jq_requisiciones").jqGrid('footerData','set',{precio:'Total: ',importe:sum});
          $("#jq_requisiciones").jqGrid('footerData','set',{vol_ejecutar:'Total: ',importe_est:sum2});

          pret = uf($("#rep").val());

          $("#imp_est").val(sum2);
          $("#subt1").val(sum2);
          $("#retencion").val(sum2*(pret/100));
          $("#subt2").val( $("#retencion").val() );

          $("#total").val( $("#subt1").val()*1 - $("#subt2").val()*1 );
          
          $("input:not(.ui-pg-input)").currency();
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
        editurl: "sql_jsest_destajistas.php?ar=<?php echo $ar; ?>&sema=<?php echo $sema; ?>&id_des=<?php echo $id_des; ?>&sestmp="+sestmp,

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
            vol_tope=$('#vol_tope').val();
            vvol=$('#volant').val();
            volest=$('#vol_estimacion').val();
            $('#vol_anterior').val(vvol);
            nopasar=(volest*1)+(vvol*1);
            //alert(nopasar);
            
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
      <label>Subtotal 1:</label>
      <input class="form-control" id="subt1" type="text" value="0" disabled="disabled">
    </div>
    <div class="col-sm-3" style="margin-top: -4px;">
      <label>Retencion:</label>
      <input id="rep" value="0.00"  style="width:20% !important;">%:
      <input class="form-control" id="retencion" type="text" value="calculo" disabled="disabled"> 
    </div>
    <div class="col-sm-3">
      <label>Cargos:</label>
      <input class="form-control" id="cargos" type="text" value="">
    </div>
  </div>
  <div class="row">
    <div class="col-sm-3">
      <label>Subtotal 2 (Retenciones):</label>
      <input class="form-control" id="subt2" type="text" value="0" disabled="disabled">
    </div>
    <div class="col-sm-3">
      <label>Total:</label>
      <input class="form-control" id="total" type="text" value="0" disabled="disabled">
    </div>
    <div class="col-sm-3">
      
            <label>Importe estimacion:</label>
      <input class="form-control" id="imp_est" type="text" value="0" disabled="disabled">
      
    </div>
    <div class="col-sm-3" style="padding-top: 25px;">
       <button id="btngenest" style="width:100%" class="btn btn-primary btn-xm pull-right" onclick="generaEst(<?php echo $sestmp; ?>,<?php echo $id_des; ?>,'<?php echo $sema; ?>');"> Generar Estimacion</button>


    </div>
  </div>
          
      </div><!-- ENd panel body -->
    </div>


  

