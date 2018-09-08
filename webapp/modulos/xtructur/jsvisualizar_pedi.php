<?php
///chais

    $SQL = "SELECT DISTINCT a.id, d.nombre FROM constru_pedis a 
    left JOIN constru_info_tdo d on d.id_alta=a.solicito
      where a.id_obra='$idses_obra' ORDER BY a.id DESC";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $vercompras[]=$row;
        }
      }else{
        $vercompras=0;
      }    


///chais

      $SQL = "SELECT correo_aut from constru_config where id_obra='$idses_obra';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
      $row = $result->fetch_array();
           $correoaut=$row['correo_aut'];
      }

      $SQL = "SELECT a.*, concat('PROV-',a.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where  a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=5 AND (a.estatus='Alta' OR a.estatus='Incapacitado') order by b.razon_social_sp;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
  }


$startm    = new DateTime($obra_ini);
$startm->modify('first day of this month');
$endm      = new DateTime($obra_fin);
$endm->modify('first day of next month');
$intervalm = DateInterval::createFromDateString('1 month');
$periodm   = new DatePeriod($startm, $intervalm, $endm);


//Sacar lista de años semanas
    $a1=explode('-', $obra_ini);
    $a1=$a1[0]*1;

    $b1=explode('-', $obra_fin);
    $b1=$b1[0]*1;

    $ini_anos=array();

    $semana = strftime('%V');
    $elano=date('Y');
    week_bounds(date('Y-m-d'), $start, $end);

    $cmbsemanas=array();
    if($a1<$b1){
      for ($i=$a1; $i <= $b1; $i++) { 
        $ini_anos[]=$i;
      }

      $numanos = count($ini_anos);
      $x=1;
      foreach ($ini_anos as $key => $value) {
        if($key+1==1){
          $fsemanos = NumeroSemanasTieneUnAno($value);
          $fsemactual = getweek($obra_ini);
          for ($i=$fsemactual; $i <= $fsemanos; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
            $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
          }
        }else if ( $key+1>1 && $key+1!=$numanos ){
          $fsemanos = NumeroSemanasTieneUnAno($value);
          for ($i=1; $i <= $fsemanos; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
             $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
          }
        }else if ( $key+1==$numanos ) {
          $fsemanos = NumeroSemanasTieneUnAno($value);
          $fsemactual = getweek($obra_fin);
          for ($i=1; $i <= $fsemactual; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
             $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
          }
        }else{
          echo "Error en las fechas de inicio y fin de obra";
          exit();
        }
      }
    }else if($a1==$b1){
      $fsemanos = NumeroSemanasTieneUnAno($a1);
      $fsemaini = getweek($obra_ini);
      $fsemafin = getweek($obra_fin);
      for ($i=$fsemaini; $i <= $fsemafin; $i++) { 
        if(strlen($i)==1){
          $add='0'.$i;
        }else{
          $add=$i;
        }
        $lolo=getStartAndEndDate($add,$a1);
        $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';

      }
    }else{
      echo "Error en las fechas de inicio y fin de obra";
      exit();
    }
   
    $ano = NumeroSemanasTieneUnAno(date('Y'));
///chais




      $SQL = "SELECT a.*, b.razon_social_sp  as nombre FROM constru_altas a right join constru_info_sp b on b.id_alta=a.id 

      where a.id_obra='$idses_obra' and a.id_tipo_alta=5 and a.borrado=0 ;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
  }    

$SQL="SELECT nombre from constru_contratista b
JOIN constru_generales a on a.construye=b.id
where a.id='$idses_obra'";
      
       $result = $mysqli->query($SQL);
       while($row = $result->fetch_array() ) {
       $cli=$row['nombre'];
       }
?>
<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_pedi.php',
                datatype: "json",
                colModel: [
                    { label: 'Orden', name: 'Orden', width: 255, sortable:false },
                    { label: 'Solicito', name: 'Solicito',  width: 70, sortable:false },
                    { label: 'Requisicion', name: 'Requisicion',  width: 70, sortable:false },
                    { label: 'Proveedor', name: 'prov',  width: 70, sortable:false },
                    { label: 'Area', name: 'area',  width: 60, sortable:false },
                    { label: 'Especialidad', name: 'especialidad',  width: 60, sortable:false },
                    { label: 'Partida', name: 'partida',  width: 60, sortable:false },
                    { label: 'Clave', name: 'clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 30, sortable:false },
                    { label: 'Cantidad', name: 'cantidad',  width: 40, sortable:false },
                    { label: 'PU concurso', name: 'precio',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { 
                        label: 'Importe concurso', 
                        name: 'importec',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryTpl: "<b>{0}</b>",
                        summaryType:'sum'
                    },
                    { label: 'PU compra', name: 'precioc',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { 
                        label: 'Importe compra', 
                        name: 'importe',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryTpl: "<b>{0}</b>",
                        summaryType:'sum'
                    },
                    { label: 'Fecha_Captura', name: 'Fecha_Captura',  width: 0, sortable:false, hidden:true, edithidden:true },
                      { label: 'Fecha_Autorizacion', name: 'Fecha_Autorizacion',  width: 0, sortable:false, hidden:true, edithidden:true },
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();

                },
        loadonce:false,
        viewrecords: true,
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        autowidth: true,
        height: "300",
        sortname: 'pedis',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["Orden", "Solicito", "Requisicion"],
                    groupColumnShow: [false, false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Orden: <b>{0}</b>",
                    "Solicito: <b>{0}</b>",
                    "Requisicion: <b>{0}</b>"
          ],
                    groupOrder: ["asc", "asc", "asc"],
                    groupSummary : [false, false, true],
          groupSummaryPos: [],
                    groupCollapse: false
                }
            });

            
            jQuery("#jq_arbol").jqGrid('navGrid',"#jqp_arbol",
                {
                  edit:false,
                  add:false,
                  del:false,
                  search:false
                },
                {},
                {beforeShowForm: function(form){ 
                    $('.FormGrid input').css('width','270px');
                    $('.FormGrid select').css('width','278px');
                    $('.FormGrid textarea').css('width','274px');
                    $('.FormGrid textarea').css('height','80px');
                    $('#unidad').numeric(); 
                    $('#precio_costo').numeric(); 
                    $('#precio_venta').numeric();
                  },
                  closeAfterAdd:true,
                  width: 600
                },
                {width: 480}, // settings for delete
                {} // search options
              ).jqGrid('navButtonAdd', '#jqp_arbol', {
                caption: "Exportar Excel",
                buttonicon: "ui-icon-export",
                  onClickButton: function() {
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"hojarequisComp",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });
        });

    </script>

 <body>


<div class="modal fade" id="mailmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Autorizar y Enviar Correo</h4>
      </div>
      <div class="modal-body">
        Mensaje:<br>
        <textarea rows="4" cols="50" id='jus'>Por medio del presente y de la manera más atenta, su cliente <?php echo $cli ?> le solicita el envío del material mostrado en el archivo adjunto.</textarea>
        <input type='hidden' id='ide'>

         
      </div>
      <div class="modal-footer">
        <br><label id='lenvio' hidden='true'>'Enviando ...'</label>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      
<input type="button" id='enviarb' value="Enviar" style="cursor:pointer" onclick=autorizaOC(1,-1,<?php echo $correoaut ?>); />

      </div>
    </div>

     </div>
    </div>

    <script>
$('#mailmodal').on('show.bs.modal', function(e) {
  $('#ide').val(e.relatedTarget.dataset.eid);
});

$('#mailmodal').on('hidden.bs.modal', function () {
     $('#enviarb').prop('disabled', false);
$('#lenvio').hide();
})

</script>


    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">

        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Autorizacion de ordenes de compra</div>
          </div>
        </div>
   <div class="col-sm-3 col-xs-8">
    <label>Semana:</label>
      <select class="form-control" id="filtro_semana" onchange="filtros('achica','sem');">
        <option selected="selected" value="0">Todas</option>
        <?php 
        if($cmbsemanas!=0){
          foreach ($cmbsemanas as $key => $value) { 
            $expano=explode('(', $value);
            $anoexact=$expano=explode('-', $expano[1]);
            $anoexact=$anoexact[0];

            $expsema=explode(' ', $value);
            $semaexact=$expsema[0];
            ?>
            <option value="<?php echo $anoexact.''.$semaexact; ?>">Semana <?php echo $value; ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Mes:</label>
      <select class="form-control" id="filtro_mes" onchange="filtros('achica','mes');">
        <option selected="selected" value="0">Todos</option>
        <?php 
        if($periodm!=0){
          foreach ($periodm as $dt) { ?>
            <option value="<?php echo $dt->format("Y-m"); ?>"><?php echo $dt->format("Y-m"); ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Estado:</label>
      <select class="form-control" id="filtro_estatus" onchange="filtros('achica','est');">
        <option selected="selected" value="x">Todos</option>
            <option value="1">Pendientes</option>
            <option value="3">Autorizadas</option>
            <option value="2">Canceladas</option>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Proveedores:</label>
      <select class="form-control" id="filtro_proveedor" onchange="filtros('achica','pro');">
        <option selected="selected" value="0">Todos</option>
        <?php 
          if($proveedores!=0){
            foreach ($proveedores as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay proveedores dados de alta</option>
          <?php } ?>
        </select>
    </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_arbol"></table>
                <div id="jqp_arbol"></div>
            </div>
          </div>
        </div>
      </div>
    </div>




  <div class="row">&nbsp;</div>
  <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Generar PDF</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        <div class="row">
            <div class="col-sm-3 col-xs-8">
              <select class="form-control" id="compraver">
                <option selected="selected" value="0">Selecciona una Compra</option>
                <?php 
                if($vercompras!=0){
                  foreach ($vercompras as $k => $v) { ?>
                    <option value="<?php echo $v['id']; ?>">OC-<?php echo $v['id'];?></option>
                  <?php } ?>
                <?php }else{ ?>
                  <option value="0">No hay Ordenes de Compra</option>
                <?php } ?>
              </select>
            </div>
            

            <div class="col-sm-1">
              

              <button id="btnpdfcomp" class="btn btn-primary btn-xm pull-right" onclick="pdfcompras('comp');"><span class="glyphicon glyphicon-download"></span> PDF</button>

            </div>

        </div>

      </div>
  </div>
  <div class="modal fade" id="mailmodal2" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancelación</h4>
      </div>
      <div class="modal-body">
        Motivo de Cancelación:<br>
        <textarea rows="4" cols="50" id='jus2'></textarea>
        <input type='hidden' id='ide2'>

         
      </div>
      <div class="modal-footer">
        <br><label id='lenvio2' hidden='true'>'Enviando ...'</label>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      
<input type="button" id="enviarb2" value="Enviar" style="cursor:pointer" onclick="cancelOC(-1,1);" >

      </div>
    </div>

     </div>
    </div>



        <div class="modal fade" id="passmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Introduzca Contraseña</h4>
      </div>
      <div class="modal-body">

        Contraseña: <input type='password' id='pass'>
     

         
      </div>
          <input type='hidden' id='ide2'>
      <label id='error'  style="margin-left:15px"></label>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id='enviarb' class="btn btn-default" onclick="can_aut_ped();">Aceptar</button>
      
        <!--<input type="button" id='enviarb' value="Aceptar" style="cursor:pointer" onclick="delent();">-->

      </div>
    </div>

     </div>
    </div>


<div class="modal fade" id="editmodal" role="dialog">
    <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        Edicion de Orden de compra
      </div>
      <div class="modal-body">
      <input type="hidden" id="tid" value="0">
      <input type="hidden" id="tidr" value="0">

      <br>
        <label>Proveedor</label>
        <select id="tselect" class="form-control">
          <option selected="selected" value="0">Selecciona un proveedor</option>
          <?php 
          if($proveedores!=0){
            foreach ($proveedores as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay proveedores dados de alta</option>
          <?php } ?>
        </select>
        <br>

        <table class="table" style="font-size: 12px;">
          <thead>
          <tr>
          <th>Clave</th>
          <th>Descripcion</th>
          <th>PU Concurso</th>
          <th>PU Compra</th>
          </tr>
          </thead>
          <tbody id="tbody">
            
          </tbody>

        </table>
        <br>
        <label>Condiciones de pago</label>
        <textarea id="tcondpago" class="form-control"></textarea>
        <br>
        <label>Observaciones generales</label>
        <textarea id="tobsgen" class="form-control"></textarea>
        <br>
        <label>Contraseña</label>
        <input type="password" id="passadmin" class="form-control">
     

         
      </div>
          <input type='hidden' id='ide2'>
      <label id='error'  style="margin-left:15px"></label>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id='enviarb' class="btn btn-default" onclick="guardaedit();">Aceptar</button>
      
        <!--<input type="button" id='enviarb' value="Aceptar" style="cursor:pointer" onclick="delent();">-->

      </div>
    </div>

     </div>
    </div>



        <script>

function guardaedit(){

  passadmin=$('#passadmin').val();

  idp=$('#tselect').val();
  cond=$('#tcondpago').val();
  obs=$('#tobsgen').val();
  idoc=$('#tid').val();
  idrequi=$('#tidr').val();

  if(idp==0){
    alert('Seleccione un proveedor');
    return false;
  }

  if(passadmin!='SUP3R4DM1N'){
    alert('La contraseña es incorrecta');
    return false;
  }

  pcval = $('.tpc').map(function() {
    return $(this).attr('idinsumo')+'#'+$(this).val()+'#'+$(this).attr('idre'); //id_ps,id_esti,imp_sem,proviene 
  }).get().join('#_#');

  

  $.ajax({
      url:"ajax.php",
      type: 'POST',
      data:{opcion:'saveeditOC',idoc:idoc,idp:idp,cond:cond,obs:obs,pcval:pcval,idrequi:idrequi},
      success: function(r){
        if(r=='ent'){
          alert('No se puede modificar esta OC ya que contiene entradas');
          $('#passadmin').val('');
          return false;
        }
        jQuery("#jq_arbol").trigger("reloadGrid");
        $('#editmodal').modal('hide');
        $('#passadmin').val('');
        //window.location='index.php?modulo='+modulo;
      }
  });

}

function loadOC(idoc,idp){
  $.ajax({
      url:"ajax.php",
      type: 'POST',
      dataType: 'json',
      data:{opcion:'editOC',idoc:idoc,idp:idp},
      success: function(r){
        console.log(r);


        html=''
        $.each(r, function( i, v ) {
          html+='<tr><td>'+v.clave+'</td><td>'+v.descripcion+'</td><td>'+v.precio+'</td><td><input idinsumo="'+v.id_insumo+'" class="tpc" value="'+v.precio_compra+'" idre="'+v.id_requi+'" ></td>';
        });
        //alert(html);
        $('#tid').val(idoc);
        $('#tidr').val(r[0].id_requi);
        $('#tselect option[value='+idp+']').attr('selected','selected');
        $('#tbody').html(html);
        $('#tcondpago').html(r[0].condpago);
        $('#tobsgen').html(r[0].obsgen);

      }
    });
}

$('#mailmodal2').on('show.bs.modal', function(e) {
  $('#ide2').val(e.relatedTarget.dataset.eid);
});

$('#passmodal').on('show.bs.modal', function(e) {
  $('#ide2').val(e.relatedTarget.dataset.eid);
});

$('#mailmodal2').on('hidden.bs.modal', function () {
     $('#enviarb2').prop('disabled', false);
$('#lenvio2').hide();
});

</script>


  </body>



