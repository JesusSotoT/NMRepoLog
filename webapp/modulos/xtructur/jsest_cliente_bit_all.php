<?php

  $SQL = "SELECT * FROM forma_pago where activo=1 and tipo=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $fpago[]=$row;
    }
  }else{
    $fpago=0;
  }

//Sacar meses
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



 $SQL="SELECT
concat(bx.cliente,' EST-',b.id, ' SEM: ',b.xxano ) as Cliente, b.id
    from constru_estimaciones_bit_cliente b
    inner join constru_estimaciones_cliente a on a.id_bit_cliente=b.id
    inner join constru_generales bx on bx.id=b.id_cliente
    left join constru_recurso c on c.id=a.id_insumo
    WHERE b.id_obra='$idses_obra' AND a.sestmp>0  AND b.borrado=0 group by b.id";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $vercompras[]=$row;
        }
      }else{
        $vercompras=0;
      }


      $SQL = "SELECT a.id as id,a.cliente as nombre FROM constru_generales a  where a.id='$idses_obra' and a.borrado=0 ;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
  }    
///chais
?>
<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_cliente_all.php',
                datatype: "json",
                colModel: [

               // d.codigo, d.descripcion, d.unidtext, d.pu_destajo, e.vol_tope*d.pu_destajo as total, c.vol_anterior, c.vol_est

                    { label: 'Cliente', name: 'Cliente', width: 255, sortable:false },
                    { label: 'Estimacion', name: 'Estimacion',  width: 70, sortable:false },
                    { label: 'Clave', name: 'Clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 30, sortable:false },
                    { label: 'Volumen Tope', name: 'Volumen Tope',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'PU Subcontrato', name: 'PU Subcontrato',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Importe', name: 'Importe',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Vol. Anterior', name: 'Vol. Anterior',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Entrada', name: 'Entrada',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Acumulado', name: 'Acumulado',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'Por ejecutar', name: 'Por ejecutar',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { 

                        label: 'Importe estimacion', 
                        name: 'impest',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum',
                        summaryTpl:'<b> {0}</b>'
                    },
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    //$(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    //$(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();

                },
        viewrecords: true,
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        autowidth: true,
        height: "300",
        sortname: 'clave',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["Cliente", "Estimacion"],
                    groupColumnShow: [false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Cliente: <b>{0}</b>",
                    "Estimacion: <b>{0}</b>"
          ],
                    groupOrder: ["asc", "asc"],
                    groupSummary : [false, true],
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"cli",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });
        });

    </script>
<body>
<style>
.modal-lg {
  width: 90%;
}
</style>
  <div class="row">&nbsp;</div>
    <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Autorizacion Estimacion Clientes</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <div class="row">
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
            <option value="0">Pendientes</option>
            <option value="1">Autorizadas</option>
            <option value="2">Canceladas</option>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Cliente:</label>
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
</div>
          
      </div><!-- ENd panel body -->
    </div>

<!--Filtros-->

<!--Fin filtros -->


<div class="row">
  <div class="col-xs-12 tablaResponsiva">
    <div class="table-responsive" id="dtabla">
        <table id="jq_arbol"></table>
        <div id="jqp_arbol"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="mailmodal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancelación</h4>
      </div>
      <div class="modal-body">
        Motivo de Cancelación:<br>
        <textarea rows="4" cols="50" id='jus'></textarea>
        <input type='hidden' id='ide'>

         
      </div>
      <div class="modal-footer">
        <br><label id='lenvio' hidden='true'>'Enviando ...'</label>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      
<input type="button" id='enviarb' value="Enviar" style="cursor:pointer" onclick="autorizarestAll('cli',-1,2,1);">

      </div>
    </div>

     </div>
    </div>


<div class="modal fade" id="previaFact" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ultimo paso</h4>
        <input type="hidden" value="0" id="idEstCli">
      </div>
      <div class="modal-body" id="contPrevia">
        <select id="metpago" class="form-control">
          <option selected="selected" value="0">Seleccione metodo de pago</option>
          <?php 
          if($fpago!=0){
            foreach ($fpago as $k => $v) { ?>
              <option value="<?php echo $v['idFormapago']; ?>"><?php echo ($v['nombre']); ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay formas de pago disponibles</option>
          <?php } ?>
        </select>
      </div>
      <div class="modal-footer">
        <br><label id='lenvio' hidden='true'>'Enviando ...'</label>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-default" onclick="facturaXtructur();">Facturar</button>
      </div>
    </div>
  </div>
</div>

    <script>

function verXML(file){
    window.open("../../modulos/cont/xmls/facturas/temporales/"+file+".xml");
  }

  function verPDF(file){
    window.open("../../modulos/facturas/"+file+".pdf");
  }


$('#mailmodal').on('show.bs.modal', function(e) {
  $('#ide').val(e.relatedTarget.dataset.eid);
});

$('#mailmodal').on('hidden.bs.modal', function () {
     $('#enviarb').prop('disabled', false);
$('#lenvio').hide();
})

function facturaCliente(idEst){
  $('#idEstCli').val(idEst);
  $('#previaFact').modal({
      backdrop: 'static',
      keyboard: false, 
      show: true
  });
}

function facturaXtructur(){

  $('#lenvio').css('display','block');
  $('.btn').prop('disabled',true);

  fp = $('#metpago').val();
  if(fp==0 || fp==''){
    alert('Seleccione un metodo de pago');
    return false;
  }

  idEstCli = $('#idEstCli').val();
  $.ajax({
    url:"facturacionXtru.php",
    type: 'POST',
    dataType:'JSON',
    data:{idEstCli:idEstCli,fp:fp},
    success: function(resp){

      /* NUEVA FACTURACION Y RESPUESTA DE VENTA
      ================================================ */
      if (resp.success == 0 || resp.success == 5) {
          if (resp.success == 0) {
              alert('Ha ocurrido un error durante el proceso de facturacion, intente mas tarde. Error ' + resp.error + ' - ' + resp.mensaje);
          }

      }
      
          if (resp.success == 1){
            azu = JSON.parse(resp.azurian);
            uid = resp.datos.UUID;
            correo = resp.correo;

            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                dataType: 'json',
                data: {
                    opcion:'guardaFactura',
                    UUID: uid,
                    noCertificadoSAT: resp.datos.noCertificadoSAT,
                    selloCFD: resp.datos.selloCFD,
                    selloSAT: resp.datos.selloSAT,
                    FechaTimbrado: resp.datos.FechaTimbrado,
                    idComprobante: resp.datos.idComprobante,
                    idFact: 0,
                    idVenta: resp.datos.idVenta,
                    noCertificado: resp.datos.noCertificado,
                    tipoComp: resp.datos.tipoComp,
                    trackId: resp.datos.trackId,
                    monto: resp.monto,
                    cliente: idEstCli,
                    idRefact: 0,
                    azurian: resp.azurian,
                    xmlfile:resp.xmlfile,
                    fp:fp,
                    doc: 2
                },
                beforeSend: function() {
                    
                },
                success: function(ccc) {

                  $.ajax({
                    url:"../../modulos/cont/controllers/visorpdf.php?name="+uid+".xml&logo=f_de_foodware.png&id=temporales&caja=1&nominas=1",
                    type: 'POST',
                    success: function(resp){

    
                    }
                  });
                   
                    //caja.eliminaMensaje();
                    //window.open('../../modulos/facturas/'+uid+'.pdf');
                    /*$.ajax({
                        async: false,
                        type: 'POST',
                        url: 'ajax.php?c=ventas&f=envioFactura',
                        dataType: 'json',
                        data: {
                            uid: uid,
                            correo: correo,
                            azurian: azu,
                            doc: 2
                        },
                        beforeSend: function() {
                            
                        },
                        success: function(resp) {
                            
                          
                            window.open('../../modulos/facturas/' + uid + '.pdf');
                            //window.location.reload();
                        },
                        error: function() {
                            alert('Error');
                        }
                    });*/
                  window.location.reload();
                  alert('Has facturado correctamente');
                    
                    
                },
                error: function() {
                    alert('Error 456');
                    //$('#modal-conff').modal('hide');
                }
            });
          } 

      }
    
  });
}

</script>


<div class="row">&nbsp;</div>

    <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Estimaciones realizadas</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <div class="row">
    <div class="col-sm-3 col-xs-8">
      <select class="form-control" id="idcli">
        <option selected="selected" value="0">Selecciona una Estimacion</option>
        <?php 
        if($vercompras!=0){
          foreach ($vercompras as $k => $v) { ?>
            <option value="<?php echo $v['id']; ?>">OC-<?php echo $v['Cliente'];?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-2 col-xs-4">

    <button onclick="pdf_est_cli('cli','<?php echo $idses_obra; ?>');" class="btn btn-primary btn-xm" id="buttonpdfcli"> Pdf</button>

    </div>
</div>
          
      </div><!-- ENd panel body -->
    </div>


    <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Graficar estimaciones</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <div class="row">
                        <div class="col-sm-12 col-xs-4">
              <b>Filtro seleccionado</b>
            </div>
            <div class="col-sm-12 col-xs-4">
  &nbsp;
            </div>
<div class="col-sm-2 col-xs-4">
  Estado: 
            </div>
            <div id="xxxxestado" class="col-sm-10 col-xs-4">
Todas
            </div>
            <div class="col-sm-2 col-xs-4">
  Cliente: 
            </div>
            <div id="xxxxsubcon" class="col-sm-10 col-xs-4">
Todos
            </div>
            <div class="col-sm-12 col-xs-4">
  &nbsp;
</div>
    <div class="col-sm-2 col-xs-4">

    <button onclick="graficar_ret('est_cliente','<?php echo $idses_obra; ?>');" class="btn btn-primary btn-xm"> Visualizar grafica</button>



    </div>
</div>
          
      </div><!-- ENd panel body -->
    </div>



