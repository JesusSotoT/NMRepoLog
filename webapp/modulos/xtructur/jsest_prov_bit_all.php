<?php

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




$SQL="SELECT a.id_oc,
concat('Proveedor: ',b.razon_social_sp,' EST-',a.id, ' SEM: ',a.xxano ) as Proveedor, a.id
FROM constru_estimaciones_bit_prov a
inner join constru_info_sp b on b.id_alta=a.id_prov
inner join constru_altas alt on alt.id=b.id_alta
LEFT JOIN constru_pedis wa on wa.id=a.id_oc
LEFT JOIN constru_pedidos wb1 on wb1.id_pedid=wa.id
LEFT JOIN constru_requis wc1  on wc1.id=wb1.id_requis
LEFT JOIN constru_requisiciones wb on wb.id_requi=wc1.id
-- LEFT JOIN constru_insumos wc on wc.id=wb.id_clave
-- LEFT JOIN constru_estimaciones_prov wx1 on wx1.id_clave=wc.id AND wx1.id_bit_prov=a.id
WHERE a.id_obra='$idses_obra'    AND a.borrado=0  AND alt.id_tipo_alta=5 AND (alt.estatus='Alta' OR alt.estatus='Incapacitado') group by a.id ORDER BY  Proveedor asc, a.id asc";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $vercompras[]=$row;
        }
      }else{
        $vercompras=0;
      }    


      $SQL = "SELECT a.*, concat('PROV-',a.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=5 AND (a.estatus='Alta' OR a.estatus='Incapacitado');";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
  }


      $SQL = "SELECT  descripcion FROM constru_insumos where id_obra='$idses_obra' ;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $materiales[]=$row;
    }
  }else{
    $materiales=0;
  }

?>
<script> 
        $(document).ready(function () {
            $("#jq_arbol").jqGrid({
                url:'sql_proveedor_all.php',
                datatype: "json",
                colModel: [

               // d.codigo, d.descripcion, d.unidtext, d.pu_destajo, e.vol_tope*d.pu_destajo as total, c.vol_anterior, c.vol_est

                    { label: 'Proveedor', name: 'Proveedor', width: 255, sortable:false },
                    { label: 'Estimacion', name: 'Estimacion',  width: 70, sortable:false },
                    { label: 'Clave', name: 'Clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 30, sortable:false },
                    { label: 'Volumen OC', name: 'Volumen OC',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
                    { label: 'PU Compra', name: 'PU Compra',  width: 35, sortable:false,sorttype:"float", formatter:"number" },
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
                        summaryType:'sum'
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
                    groupField: ["Proveedor", "Estimacion"],
                    groupColumnShow: [false, false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Proveedor: <b>{0}</b>",
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
                    //window.open('excelMacro.php?o=aestprov');
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"prov",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });


              $('#modal-adju-uno').on('click',function(){
            $('#adju_recep').html('Cargando...');
            $('#adju_xmls').html('Cargando...');
            $('#modal-adju').modal('hide');
        });

              $('#fac').submit( function( e ) {
    console.log(this);
    //return false;
    //$('#verif').css('display','inline');
    $.ajax( {
      url: 'subexml.php',
      type: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false
    } ).done(function( data1 ) {
        console.log(data1)

        //$("#Facturas").dialog('refresh')
        //alert(data1)
           // return false;
           // $('#factura').val('')
            data1 = data1.split('-/-*');
            console.log(data1);
            // "1", "0", "", "1", "tempo.xml,\n", "", "", "2022##2016-08-24T15:37:0...FAFE7A##Camisa Blanca##"
            $('#verif').css('display','none');

            if(parseInt(data1[0]))
            {
                if(parseInt(data1[3]))
                {
                    alert('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4])
                    //$('#resultasoc').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
                }

                if(parseInt(data1[1]))
                {


                    //alert(data1[1]+' Archivos Validados: \n'+data1[2])
                    //$('#resultasoc').html('<button id="xmlfile" name="'+data1[6]+'" class="btn btn-success btn-sx active"><span class="glyphicon glyphicon-ok"></span> XML asociado</button>');
                    datosfac = data1[7].split('##');
                    fac_folio=datosfac[0];
                    fac_fecha=datosfac[1];
                    fac_total=datosfac[2];
                    fac_uuid=datosfac[3];
                    fac_desc_concepto=datosfac[4];
                    fac_subtotal=datosfac[5];
                    xmlfile=data1[6];
                    idoc = $('#pediid').val();//OC
                    estiid = $('#estiid').val();//idest


                    $.ajax({
                        url:"guardaxml.php",
                        type: 'POST',
                        data:{fac_folio:fac_folio,fac_fecha:fac_fecha,fac_total:fac_total,fac_uuid:fac_uuid,concepto:fac_desc_concepto,xmlfile:xmlfile,idoc:idoc,fac_subtotal:fac_subtotal,estiid:estiid},
                        success: function(r){
                            if(r>0){
                                $('#adju_recep').html('Cargando...');
                                $('#adju_xmls').html('Cargando...');
                                $('#modal-adju').modal('hide');
                                alert('XML adjuntado con exito');
                            }
                        }
                    });

                    
                }
                //alert(parseInt(data1[5]))
                if(parseInt(data1[5])){
                    abrefacturasrepetidas();
                    
                }else{
                  //  location.reload();
                }
            }
            else
            {
                alert("El archivo zip no cumple con el formato correcto\nDebe llamarse igual que la carpeta que contiene los xmls.\nSólo debe contener una carpeta.");

            }
        
    
    });
    e.preventDefault();
  });

        });

    </script>
    <div class="row">&nbsp;</div>
<div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Autorizacion Estimacion Proveedores</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
          <!--Filtros-->
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
    <label>Proveedor:</label>
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

       <div class="col-sm-3 col-xs-8">
    <label>Material:</label>
      <select class="form-control" id="filtro_material" onchange="filtros('achica','pro');">
        <option selected="selected" value="0">Todos</option>
        <?php 
          if($materiales!=0){
            foreach ($materiales as $k => $v) { ?>
              <option value="<?php echo $v['descripcion']; ?>"><?php echo $v['descripcion']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay materiales dados de alta</option>
          <?php } ?>
        </select>
    </div>
</div>
<!--Fin filtros -->
          
      </div><!-- ENd panel body -->
    </div>




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
      
<input type="button" id='enviarb' value="Enviar" style="cursor:pointer" onclick="autorizarestAll('prov',-1,2,1);">

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

function adjuntarxml(idest,idoc){
  $('#adju_header').html('Estimacion a proveedor <b>'+idest+'</b><br>Orden de compra <b>'+idoc+'</b>');
  $('#pediid').val(idoc);
  $('#estiid').val(idest);
$('#modal-adju').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });

$.ajax({
    url:'ajax.php',
    type: 'POST',
    data: {opcion:'checaFactsPedis',idoc:idoc,idest:idest},
    dataType:'json',
    success: function(r){
      
        if(r.success==1){



          tabla2='<table id="tablaxmladju">\
                        <tr>\
                        <th width="420">Folio</th>\
                        <th width="160">Fecha subida</th>\
                        <th width="100">Monto</th>\
                        </tr>';


                $.each(r.data, function(i,v) {
                    tabla2+='<tr>\
                        <td style="padding: 2px;">'+v.folio+'</td>\
                        <td style="padding: 2px;">'+v.fecha_subida+'</td>\
                        <td style="padding: 2px;">$'+v.total+'</td>\
                        </tr>';
                });
                // tabla2+='<tr>\
                //         <td style="padding: 2px;">&nbsp;</td>\
                //         <td style="padding: 2px;">&nbsp;</td>\
                //         <td style="padding: 2px;"><b>$'+r.totalxmls+'</b></td>\
                //         </tr>';


                    $('#adju_xmls').html(tabla2);
                

        }else{
          $('#adju_xmls').html('No hay facturas adjuntas');
        }

    

    }
  });


}
function check_file()
    {
        var ext = $('#factura').val();
        ext = ext.split('.');
        ext = ext[1];
        if(ext != 'xml')
        {
            alert("Archivo Inválido. El archivo debe tener una extensión xml.");
            $("#factura").val('');
        }
    }



</script>

<div id="modal-adju" class="modal sfade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Adjuntar XML'S</h4>
            </div>
            <div id="bodyespecialxx" class="modal-body">
            <input type="hidden" id="pediid">
            <input type="hidden" id="estiid">
                <div id="adju_header" class="col-sm-12" style="padding:10px 0 10px 0;">
                    &nbsp;
                </div>
                <!--<div class="col-sm-12" style="padding:10px 0 10px 0;">
                    <b>Recepciones</b>
                </div>
                <div id="adju_recep" class="col-sm-12" style="padding:10px 0 10px 0;">
                    Cargando...
                </div>-->
                <div class="col-sm-12" style="padding:10px 0 10px 0;">
                    <b>Xml's Adjuntos</b>
                </div>
                <div id="adju_xmls" class="col-sm-12" style="padding:10px 0 10px 0;">
                    Cargando...
                </div>

                <div class="col-sm-12" style="padding:10px 0 10px 0;">
                    <b>Subir archivos xml</b>
                </div>
                <div id="divxmls" class="col-sm-12" style="padding:0px;">
                <div class="form-group"  style="padding:0px;">
                    
                    <form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
                    <div class="col-sm-10" style="padding:0px;">
                        
                        <input type='file' name='factura[]' id='factura' onchange='check_file()'>
                        <input type='hidden' name='plz' id='plz' value='lala'>
                        
                    </div>
                    <div class="col-sm-10" style="margin-top:10px; padding:0px;">
                        <input type='submit' id='buttonFactura' value='Asociar Factura'>
                        <div id="resultasoc" style="margin-top:10px;">
                            
                        </div>
                        
                        <span id='verif' style='color:green;display:none;'>Verificando...</span>
                    </div>
                    </form>
                </div>
                </div>

                <div class="row">
                </div>
            </div>
            <div class="modal-footer">
                <button id="modal-adju-uno" type="button" class="btn btn-default">Salir</button> 
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
      <select class="form-control" id="oc_num2">
        <option selected="selected" value="0">Selecciona una Estimacion</option>
        <?php 
        if($vercompras!=0){
          foreach ($vercompras as $k => $v) { ?>
            <option value="<?php echo $v['id']; ?>">OC-<?php echo $v['id_oc']; ?> / <?php echo $v['Proveedor'];?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-2 col-xs-4">
      <button onclick="pdf_est_prov('prov');" id="btnpdfcomp" class="btn btn-primary btn-xm"><span class="glyphicon glyphicon-download"></span> PDF</button>
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
  Proveedores: 
            </div>
            <div id="xxxxsubcon" class="col-sm-10 col-xs-4">
Todos
            </div>
            <div class="col-sm-12 col-xs-4">
  &nbsp;
</div>
    <div class="col-sm-2 col-xs-4">

    <button onclick="graficar_ret('est_prov','<?php echo $idses_obra; ?>');" class="btn btn-primary btn-xm"> Visualizar grafica</button>



    </div>
</div>
          
      </div><!-- ENd panel body -->
    </div>

