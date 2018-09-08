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
                url:'sql_visor.php',
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
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();

                },
        loadonce:true,
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


                    $.ajax({
                        url:"guardaxml.php",
                        type: 'POST',
                        data:{fac_folio:fac_folio,fac_fecha:fac_fecha,fac_total:fac_total,fac_uuid:fac_uuid,concepto:fac_desc_concepto,xmlfile:xmlfile,idoc:idoc,fac_subtotal:fac_subtotal},
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
      
<input type="button" id='enviarb' value="Enviar" style="cursor:pointer" onclick="autorizaOC(1);">

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
        <script>
$('#mailmodal2').on('show.bs.modal', function(e) {
  $('#ide2').val(e.relatedTarget.dataset.eid);
});

$('#mailmodal2').on('hidden.bs.modal', function () {
     $('#enviarb2').prop('disabled', false);
$('#lenvio2').hide();
})

function adjuntarxmlinfo(idoc){
  $('#adju_header').html('Orden de compra <b>'+idoc+'</b>');
  $('#pediid').val(idoc);
$('#modal-adju').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });

$.ajax({
    url:'ajax.php',
    type: 'POST',
    data: {opcion:'checaFactsPedisinfo',idoc:idoc},
    dataType:'json',
    success: function(r){

        if(r.success==1){



          tabla2='<table id="tablaxmladju">\
                        <tr>\
                        <th width="420">Xml archivo</th>\
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



                <div class="row">
                </div>
            </div>
            <div class="modal-footer">
                <button id="modal-adju-uno" type="button" class="btn btn-default">Salir</button> 
            </div>
        </div>
    </div> 
</div> 

  </body>



