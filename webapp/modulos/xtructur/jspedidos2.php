<?php
  $sestmp=time();
  $SQL = "SELECT a.*, concat('PROV-',b.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=5;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
  }
  $SQL = "SELECT a.*, concat('RT-',b.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $tecnicos[]=$row;
    }
  }else{
    $tecnicos=0;
  }

  $SQL = "SELECT * FROM forma_pago where activo=1 and tipo=1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $fpago[]=$row;
    }
  }else{
    $fpago=0;
  }

  $SQL = "SELECT id,nomfam famat FROM constru_famat ORDER BY nomfam;";
  $result = $mysqli->query($SQL);
  
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $familias[]=$row;
    }
  }else{
    $familias=0;
  }
?>
<div class="modal fade" id="addconcepto" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Agregar concepto</h4>
          <input type="hidden" id="idreqadd" value="">
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-3">
                    Material
                </div>
                <div class="col-sm-9">
                    <select id="fammaterial" onchange="cambiaMatOC();" style="width:350px;">
                      <option value="0">Selecciona</option>
                      <option value="t">Todas</option>
                      <?php
                      foreach ($familias as $k => $v) { ?>
                        <option value="<?php echo $v['id']; ?>"><?php echo $v['famat']; ?></option>
                      <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    Clave
                </div>
                <div class="col-sm-9">
                     <select id="clavematerial" onchange="cambiaMatOC2();" style="width:350px;">
                      <option value="0">Selecciona</option>
                
                    </select>
                </div>
            </div>
            <div id="agregaDatosOC">
              
            </div>



            <div class="row">
                <div class="col-sm-6">
                    
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-block" onclick="aplicaDesParcial();">Aplicar</button>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-danger btn-block" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>


<script> 

        $(document).ready(function () {

$('#fecente').datepicker({ dateFormat: "yy-mm-dd",
            onSelect: function(datetext){

                  var d = new Date(); // for now

                  var h = d.getHours();
                  h = (h < 10) ? ("0" + h) : h ;

                  var m = d.getMinutes();
                  m = (m < 10) ? ("0" + m) : m ;

                  var s = d.getSeconds();
                  s = (s < 10) ? ("0" + s) : s ;

                  datetext = datetext + " " + h + ":" + m + ":" + s;

                  $('#fecente').val(datetext);
              }
           }); 

            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_pedido2.php',
                datatype: "json",
                colModel: [
                    { label: 'Requisicion', name: 'Requisicion',  width: 70, sortable:false },
                    { label: 'Clave', name: 'clave',  width: 70, sortable:false },
                    { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                     { label: 'Partida', name: 'unidad',  width: 40, sortable:false },
                    { label: 'U.M', name: 'unidad',  width: 40, sortable:false },
                    { label: 'Cantidad Req.', name: 'cantidad',  width: 60, sortable:false },
                    //{ label: 'Precio de concurso', name: 'precio',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    /*{ 
                        label: 'Importe', 
                        name: 'importe',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },*/
                    { label: 'Precio concurso', name: 'Precio_concurso',  width: 60, sortable:false},
                    { label: 'Precio compra', name: 'Precio_compra_',  width: 60, sortable:false},
                                        { label: 'Ultimo Precio compra', name: 'Precio_compra2_',  width: 60, sortable:false},
                    { label: 'Proveedor', name: 'Proveedor',  width: 60, sortable:false}
                ],
                loadComplete: function() {
                  

                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();
                    $('input[class^=quis_]').numeric();
                    $('input[class^=quis_]').prop('disabled',true);
                    $('input[class^=cquis_]').numeric();
                    $('input[class^=cquis_]').prop('disabled',true);

                    $('input[class^=quis_]').css('background-color','#f1f1f1');
                    $('input[class^=cquis_]').css('background-color','#f1f1f1');

                    $('input.ccbox').on('change', function() {

                      vermetprov();



                        id_oc = $(this).val();
                        //$('input[class^=quis_]').prop('disabled',true);

                        if(!$(this).is(':checked') ){
                          $('.quis_'+id_oc+'_').css('background-color','#f1f1f1');
                          $('.cquis_'+id_oc+'_').css('background-color','#f1f1f1');

                          $('.quis_'+id_oc+'_').prop('disabled',true);
                          $('.cquis_'+id_oc+'_').prop('disabled',true);
                        }else{
                          $('.quis_'+id_oc+'_').css('background-color','#ffffff');
                          $('.cquis_'+id_oc+'_').css('background-color','#ffffff');
                          $('.quis_'+id_oc+'_').prop('disabled',false);
                          $('.cquis_'+id_oc+'_').prop('disabled',false);
                        }
                       // $('input.ccbox').not(this).prop('checked', false); 

                        
                       
                    });

                },
        cellEdit: true,
        loadonce:true,
        viewrecords: true,
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,
        autowidth: true,
        height: "450",
        sortname: 'pedis',
        pager: "#jqp_arbol",
        grouping: true,
                groupingView: {
                    groupField: ["Requisicion"],
                    groupColumnShow: [false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                    "Requisicion: <b>{0}</b>"
          ],
                    groupOrder: ["desc"],
                    groupSummary : [true],
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"pedidos2",formato:"excel"});
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
              <div class="navbar-brand" style="color:#333;">Elaboracion de Ordenes de compra</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
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
      <div class="panel-title">Datos de la orden de compra</div>
      </div><!-- End panel heading -->

      <!-- Panel body -->
      <div class="panel-body" >
        <div class="row">
        <div class="col-sm-4">
        <label>Solicito:</label>
        <div>
        <label id="userlog" style="color:#096;"><?php echo $username_global; ?></label>
        </div>
        <input type='hidden' id="iduserlog" value='<?php echo $id_username_global; ?>'>
        
        </div>

  <div class="col-sm-8">
        <div class="col-sm-12">
          <label>Proveedores seleccionados:</label>
        </div>
        <div id="mm">
          
        </div>
       

      
      <div class="col-sm-12">
        <label>Fecha de entrega:</label>
        <input class="form-control" id="fecente" type="text">
        <select id="val_fpago" class="form-control" style="display: none;">
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
  

      <div class="col-sm-12">
        
      </div>
      <div class="col-sm-6">
        <label>Condiciones de pago:</label>
        <textarea class="form-control" id="condpago" type="text"></textarea>
      </div>
      <div class="col-sm-6">
        <label>Observaciones generales:</label>
        <textarea class="form-control" id="obsgen" type="text"></textarea>
      </div>
  
      <div class="col-sm-12" style="padding: 15px 30px;">
        
        <button id="btnGenReq"  class="btn btn-primary btn-xm pull-right" onclick="generaPed2(<?php echo $sestmp; ?>);"> Generar Pedido</button>

       
      </div>


      </div>
      </div> 
      </div>

</body>





    
    
    
<script>
function addconcepto(idconcepto){

  $('#idreqadd').val(idconcepto);
  $('#addconcepto').modal({
                backdrop: 'static',
                keyboard: false, 
                show: true
            });
            
}

function validaVolumen(req,insumo){
    valoro=$("#c_"+req+"_"+insumo).attr('valoro');
    valor=$("#c_"+req+"_"+insumo).val();
    tope=$("#c_"+req+"_"+insumo).attr('maxicreal');

    if((valor*1)>(tope*1)){
      alert("La cantidad maxima para este insumo es de: "+tope);
      $("#c_"+req+"_"+insumo).val(valoro);
    }

}

function aplicaDesParcial(){
  idRequi = $('#idreqadd').val();
  clavematerial = $('#clavematerial').val();
  canti = $('#canti').val();

  if(idRequi==""){
    alert("No ha seleccionado un requisicion");
    return false;
  }

  if(canti=="" || canti==0){
    alert("La cantidad no puede ser 0 o estar vacia");
    return false;
  }

  if(clavematerial==0){
    alert("No hay clave de material seleccionada");
    return false;
  }
  $.ajax({
      url:'ajax.php',
      type: 'POST',
      data: {opcion:'save_addconcepto',idRequi:idRequi,clavematerial:clavematerial,canti:canti},
      success: function(r){
        if(r=='rp'){
          alert('Este concepto esta repetido');
        }else{
          //jQuery('#jq_arbol').jqGrid('clearGridData');

          $('#addconcepto').modal('hide');
          $('#agregaDatosOC').html('');
          //$('#fammaterial').html('<option value="0" selected>Selecciona</option><option value="t">Todas</option>');
          //$('#clavematerial').html('<option value="0" selected>Selecciona</option>');
          $("#fammaterial").val(0).change();
          $("#clavematerial").val(0).change();
         // $("#jq_arbol").jqGrid("gridUnload");

         //$('#grid').jqGrid('GridDestroy');


          $.jgrid.gridUnload("#jq_arbol");
          //$("#jq_arbol").gridUnload('#jq_arbol'); 
          //$("#jq_arbol").jqGridMethod('GridUnload');




          //$('#jq_arbol').jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
          //grid.jqGrid().trigger('reloadGrid', [{ page: 1}]);

              $("#jq_arbol").jqGrid({
                    url:'sql_visualizar_pedido2.php',
                    datatype: "json",
                    colModel: [
                        { label: 'Requisicion', name: 'Requisicion',  width: 70, sortable:false },
                        { label: 'Clave', name: 'clave',  width: 70, sortable:false },
                        { label: 'Descripcion', name: 'descripcion',  width: 70, sortable:false },
                        { label: 'U.M', name: 'unidad',  width: 40, sortable:false },
                        { label: 'Cantidad Req.', name: 'cantidad',  width: 60, sortable:false },
                        { label: 'Precio concurso', name: 'Precio_concurso',  width: 60, sortable:false},
                        { label: 'Precio compra', name: 'Precio_compra_',  width: 60, sortable:false},
                        { label: 'Proveedor', name: 'Proveedor',  width: 60, sortable:false}

                    ],
                    loadComplete: function() {
                      

                        $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                        $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                        $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                        $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                        $("tr #null").remove();
                        $('input[class^=quis_]').numeric();
                        $('input[class^=quis_]').prop('disabled',true);
                        $('input.ccbox').on('change', function() {

                          vermetprov();
      
                            id_oc = $(this).val();
                            if(!$(this).is(':checked') ){
                              $('.quis_'+id_oc+'_').prop('disabled',true);
                            }else{
                              $('.quis_'+id_oc+'_').prop('disabled',false);
                            }                       
                        });

                    },


                    cellEdit: true,
        loadonce:true,
        viewrecords: true,
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,
        autowidth: true,
        height: "450",
        sortname: 'pedis',
        pager: "#jqp_arbol",
        grouping: true,

                            groupingView: {
                                groupField: ["Requisicion"],
                                groupColumnShow: [false],
                                groupText: [
                                "Requisicion: <b>{0}</b>"
                      ],
                                groupOrder: ["desc"],
                                groupSummary : [true],
                      groupSummaryPos: [],
                                groupCollapse: false
                            }
                });

                jQuery("#jq_arbol").jqGrid('navGrid',"#jqp_arbol",
                  {edit:false,add:false,del:false,search:false
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
                          $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"pedidos2",formato:"excel"});
                          //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                      },
                      position: "last"
                  });

        }
      }
    });
}

function vermetprov(){
  ji = new Object();

  x=0;
  listaprovs = $('.ccbox:checked').map(function() {
    idtemp = this.value;
    t=0;
     xnx = $('.quis_'+idtemp+'_').map(function() {
      vasel = $('.selopp_'+idtemp+':eq('+t+')').val();
      nambre = $('.selopp_'+idtemp+':eq('+t+') option:selected').text();
      if(vasel in ji){

        ji[vasel]=nambre;
      }else{
        ji[vasel]=nambre;
      }
      t++;
      return vasel+''+nambre;
    }).get().join('_#_');
           x++;
    return xnx;


  }).get().join('_##_');


  htmlvalpago=  $('#val_fpago').html();

  cad='';

  $.each( ji, function( key, value ) {
    cad+='<div id="npnp_'+key+'" ><div class="col-sm-6" style="margin-bottom: 10px;">\
            '+value+'\
          </div>\
          <div class="col-sm-6" style="margin-bottom: 10px;">\
          <select id="sval_fpago_'+key+'" class="form-control">\
            '+htmlvalpago+'\
          </select>\
          </div>';
  });
  $('#mm').html(cad);
}
</script>
