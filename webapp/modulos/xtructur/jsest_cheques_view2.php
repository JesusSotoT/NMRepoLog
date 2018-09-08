<?php
  $sestmp=time();
  $sema=$_POST['sema'];
  include('conexiondb.php');
  if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
  }
  $SQL = "SELECT a.*, concat('PROV-',b.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$id_obra' and a.borrado=0 AND a.id_tipo_alta=5;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
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

   $SQL = "SELECT * FROM constru_bit_remesas where id_bit_remesa='$sema';";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $pas=$row['tot_pasiv'];
    $ra=$row['rem_aut'];
  }else{
    $pas=0;
    $ra=0;
  }

  $SQL = "SELECT estatus FROM constru_bit_remesa where id='$sema';";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $estatus=$row['estatus'];
  }else{
    $estatus=1;
  }

?>

<!--<div class="row">
  <div class="col-sm-6">
    <div class="row">
      <div class="col-sm-4">
        <label>Total de pasivos:</label>
        <input class="form-control" id="ttp" value="<?php echo $pas; ?>" disabled="disabled">
      </div>
      <div class="col-sm-4">
        <label>Remesa autorizada:</label>
        <input class="form-control" id="rea" value="<?php echo $ra; ?>" disabled="disabled">
      </div>
    </div>
  </div>
</div>-->

<script> 
        $(document).ready(function () {

          $('#fee').datepicker({ showWeek: true, dateFormat: "yy-mm-dd" });

          $('#ttp').currency();
$('#preload').css('display','none');
$('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 

            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_remesa_rep2.php?sema=<?php echo $sema; ?>',
                datatype: "json",
                colModel: [
                      { label: 'Remesa', name: 'remesa',  width: 70, sortable:false },
                    { label: 'Proveedor', name: 'Proveedor',  width: 70, sortable:false },
                    { label: 'Estimacion', name: 'Estimacion',  width: 70, sortable:false },
                    { label: 'No. de cheque', name: 'No_cheque',  width: 70, sortable:false, editable:true },
                    { label: 'Validacion de cheques', name: 'Validacion de cheques',  width: 55, sortable:false, editable:true },
                    { label: 'Banco', name: 'Banco',  width: 70, sortable:false, editable:true },
                   // { label: 'Importe', name: 'importe',  width: 60, sortable:false },
                    //{ label: 'Precio de concurso', name: 'precio',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { 
                        label: 'Fecha de expedicion', 
                        name: 'Fecha_expedicion',
                        width: 70, sortable:false, editable:true,
                        sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
                        editoptions:{ 
                          dataInit: function(el){ 
                            setTimeout(function(){ 
                              $(el).datepicker({ dateFormat: "yy-mm-dd" }); 
                            }, 200); 
                          }
                        },
                    },
              /*      if($stche==1) $txt_stche='Depositado';
              if($stche==2) $txt_stche='Entregado';
              if($stche==3) $txt_stche='Cancelado';
              if($stche==4) $txt_stche='Devuelto';

              if($stfa==1) $txt_stfa='Pagada';
              if($stfa==2) $txt_stfa='Pago parcial';
*/
                    { 
                        label: 'Estatus cheque', 
                        name: 'Estatus_cheque',
                        width: 55, sortable:false, editable:true, edittype:"select",editoptions:{value:'1:Depositado;2:Entregado;3:Cancelado;4:Devuelto'},searchoptions:{sopt:['eq'], value:'1:Depositado;2:Entregado;3:Cancelado;4:Devuelto' },
                    },
                    { 
                        label: 'Estatus factura', 
                        name: 'Estatus_factura',
                        width: 70, sortable:false, editable:true, edittype:"select",editoptions:{value:'1:Pagada;2:Pago parcial'},searchoptions:{sopt:['eq'], value:'1:Depositado;2:Entregado' },
                    }
                ],

                beforeSelectRow: function(rowid, e)
{
    $("#jq_arbol").jqGrid('resetSelection');
    return(true);
},

                loadComplete: function() {
                  
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();
                    $('input[class^=quis__]').numeric();
                    $('input[class^=quis__]').prop('disabled',true);

                    var ids = jQuery("#jq_arbol").jqGrid('getDataIDs');
                    for (var i = 0; i < ids.length; i++) 
                    {
                        var rowId = ids[i];
                        

                        var rowData = jQuery('#jq_arbol').jqGrid ('getRowData', rowId);
                        if(rowData.No_cheque==''){
                          $('tr#'+rowId).find('input').replaceWith(' ');
                        }
                    }


                    $('input[class^=quis__]').keyup(function() {

                    

                    /*  $('input.ccbox').on('change', function() {
                        id_oc = $(this).val();
                        //$('input[class^=quis_]').prop('disabled',true);
                        alert(id_oc);
                        $('input.ccbox').not(this).prop('checked', false);  
                        
                       
                    });

                          sema += Number($(this).val());
                      });
*/
                      ra=$('#rea').val();
                      if( (ra*1)<sema ){
                        alert('La remesa autorizada no puede ser menor al importe capturado');
                        $(this).val(0);
                      }
                    });


                  $('input.ccbox').on('change', function() {
                        id_oc = $(this).val();
                        //$('input[class^=quis_]').prop('disabled',true);
                        $('input.ccbox').not(this).prop('checked', false);  
                        
                       
                    });

                    //$('input[class^=quis_]').prop('disabled',true);
                    $('input.ccbox').on('change', function() {
                        id_oc = $(this).val();
                        //$('input[class^=quis_]').prop('disabled',true);
                        noremesa=$(this).attr('noremesa');
                        if(!$(this).is(':checked') ){
                          $('.quis_'+id_oc+'_').prop('disabled',true);

                        }else{
                          $('#noremesah').val(noremesa);
                          $('.quis_'+id_oc+'_').prop('disabled',false);
                        }
                       // $('input.ccbox').not(this).prop('checked', false);  
                        
                      
                    });

                },

        //cellEdit: true,
        loadonce:true,
        viewrecords: true,
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,
        autowidth: true,
        height: "450",
        sortname: 'remesa',
        pager: "#jqp_arbol",
        grouping: true,
        multiselect: true,
        editurl: "sql_visualizar_remesa_rep2.php?sema=<?php echo $sema; ?>",
 
        beforeSelectRow: function(rowid, e)
        {

              jQuery("#jq_arbol").jqGrid('resetSelection');
              return(true);
           
        },
                groupingView: {
                    groupField: ["remesa","Proveedor","Estimacion"],
                    groupColumnShow: [false,false,false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
                   
                    "<b>{0}</b>",
                    "<b>{0}</b>",
                    "<b>{0}</b>"
          ],
                    groupOrder: ["desc"],
                    groupSummary : [true],
          groupSummaryPos: [],
                    groupCollapse: false
                }
            });

            
            jQuery("#jq_arbol").jqGrid('navGrid',"#jqp_arbol",
                {
                  edit:true,
                  add:false,
                  del:false,
                  search:false
                },
                {
                reloadAfterSubmit: false,
                afterSubmit: function(response, otro) {
                 
                  if(response.responseText!='ec'){

                    estchh = $('#Estatus_cheque option:selected').text();
                    estfact = $('#Estatus_factura option:selected').text();
                    //idsele = $("#jq_arbol").getGridParam('selrow');
                    idsele = $('#jq_arbol').jqGrid('getGridParam','selrow');
                    //console.log(res);
                    //return [true];

                    $('tr#'+idsele).find('td:eq(7)').text(estchh);
                    $('tr#'+idsele).find('td:eq(8)').text(estfact);
                    return [false,' &nbsp; Registro exitoso '];

                  }
                   if(response.responseText=='ec'){
                       return [false,' Error numero de cheque repetido '];
         
                   }
                },

                closeAfterEdit:true
                },
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"hojarequis",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });


        });

    </script>

<div class="row">
  <div class="col-xs-12 tablaResponsiva">
    <div class="table-responsive" id="dtabla">
        <input id="sestmp" type="hidden" value="<?php echo $sestmp; ?>">
        <table id="jq_arbol"></table>
        <div id="jqp_arbol"></div>
    </div>
  </div>
</div> 

<div class="row">&nbsp;</div>
<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Datos del pago</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
      <div class="row">
  <div class="col-sm-3">
    <label>No. de cheque:</label>
    <input type="hidden" id="noremesah" value="0">
    <input class="form-control" id="noc" value="">
  </div>
  <div class="col-sm-3">
    <label>Validacion de cheques:</label>
    <input class="form-control" id="val" value="">
  </div>
  <div class="col-sm-3">
    <label>Banco:</label>
    <input class="form-control" id="ban" value="">
  </div>
  <div class="col-sm-3">
    <label>Fecha de expedicion:</label>
    <input class="form-control" id="fee" value="">
  </div>
</div>
<div class="row">
  <div class="col-sm-3">
    <label>Estatus cheque:</label>
    <select class="form-control" id="estc">
      <option value="0" selected="selected">Seleccione</option>
      <option value="1">Depositado</option>
      <option value="2">Entregado</option>
      <option value="3">Cancelado</option>
      <option value="4">Devuelto</option>
    </select>
  </div>
  <div class="col-sm-3">
    <label>Estatus factura:</label>
    <select class="form-control" id="estf">
      <option value="0"  selected="selected">Seleccione</option>
      <option value="1">Pagada</option>
      <option value="2">Pago parcial</option>
    </select>
  </div>
  <div class="col-sm-3">
    <label>&nbsp;</label>
     <button id="btn_cheque" style="width:100%" class="btn btn-primary btn-xm pull-right" onclick="guardacheque();"> Guardar</button>

  </div>
  <div class="col-sm-3"></div>
</div>
      
  </div><!-- ENd panel body -->
</div>



