<?php
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
      $obra_ini = $cookie_xtructur['obra_ini'];
    $obra_fin = $cookie_xtructur['obra_fin'];
  }

function NumeroSemanasTieneUnAno($ano){
        $date = new DateTime;
        $date->setISODate("$ano", 53);
        if($date->format("W")=="53")
            return 53;
        else
            return 52;
    }

    function getweek($fecha){
        $date = new DateTime($fecha);
        $week = $date->format("W");
        return $week;
    }

    function week_bounds( $date, &$start, &$end ) {
        $date = strtotime( $date );
        // Find the start of the week, working backwards
        $start = $date;
        while( date( 'w', $start ) > 1 ) {
          $start -= 86400; // One day
        }
        // End of the week is simply 6 days from the start
        $end = date( 'Y-m-d', $start + ( 6 * 86400 ) );
        $start = date( 'Y-m-d', $start );
    }

    function getStartAndEndDate($week, $year) {
      $dto = new DateTime();
      $dto->setISODate($year, $week);
      $ret[0] = $dto->format('Y-m-d');
      $dto->modify('+6 days');
      $ret[1] = $dto->format('Y-m-d');
      return $ret;}
 

  $sestmp=time();
  $sema=$_POST['sema'];
  include('conexiondb.php');
  

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

          

          $('#ttp').currency();
$('#preload').css('display','none');
$('#fecente').datepicker({ dateFormat: "yy-mm-dd" }); 

            $("#jq_arbol").jqGrid({
                url:'sql_visualizar_remesa_rep.php?sema=<?php echo $sema; ?>',
                datatype: "json",
                colModel: [
                    { label: 'Pago', name: 'pago',  width: 70, sortable:false },
                    { label: 'Proveedor', name: 'Proveedor',  width: 70, sortable:false },
                    { label: 'Fecha', name: 'fecha',  width: 70, sortable:false },
                    { label: 'Estimacion', name: 'estimacion',  width: 70, sortable:false },
                    { label: 'No. Factura', name: 'no_factura',  width: 40, sortable:false },
                   // { label: 'Importe', name: 'importe',  width: 60, sortable:false },
                    //{ label: 'Precio de concurso', name: 'precio',  width: 60, sortable:false,sorttype:"float", formatter:"number" },
                    { 
                        label: 'Importe', 
                        name: 'Importe_(Por_pagar)',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Pagado remesas', 
                        name: 'Pagado_Remesas',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { 
                        label: 'Saldo por pagar', 
                        name: 'Saldo_por_pagar',
                        width: 70,
                        sorttype:"float", 
                        formatter:"number",
                        sortable:false,
                        summaryType:'sum'
                    },
                    { label: 'Importe pagado', name: 'Importe_Pagado',  width: 60, sortable:false}
                ],
                loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();
                    $('input[class^=quis__]').numeric();
                    $('input[class^=quis__]').prop('disabled',true);
                    $('input[class^=quis__]').keyup(function() {

                      var sema = 0;
                      $('input[class^=quis__]').each(function() {
                          sema += Number($(this).val());
                      });

                      ra=$('#rea').val();
                      if( (ra*1)<sema ){
                        alert('La remesa autorizada no puede ser menor al importe capturado');
                        $(this).val(0);
                      }
                    });
                    //$('input[class^=quis_]').prop('disabled',true);
                    $('input.ccbox').on('change', function() {
                        id_oc = $(this).val();
                        //$('input[class^=quis_]').prop('disabled',true);

                        if(!$(this).is(':checked') ){
                          $('.quis_'+id_oc+'_').prop('disabled',true);
                        }else{
                          $('.quis_'+id_oc+'_').prop('disabled',false);
                        }
                       // $('input.ccbox').not(this).prop('checked', false);  
                        
                      
                    });

                },

        cellEdit: true,
        loadonce:false,
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
                    groupField: ["pago","Proveedor"],
                    groupColumnShow: [false,false],
                    groupText: [
          //  "Agrupador: <b>{0}</b> - ${importe}",
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
                  edit:false,
                  add:true,
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
                        $("#jq_arbol").jqGrid('exportarExcelCliente',{nombre:"hojarequispp",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });


        });

    </script>


          
      </div><!-- ENd panel body -->
    </div>


    <div class="panel panel-default" >
      <!-- Panel Heading -->
      <div class="panel-heading">
      <div class="panel-title">Autorizar cuentas por pagar</div>
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
            <option value="1">Pendientes</option>
            <option value="2">Autorizadas</option>
            <option value="0">Canceladas</option>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Proveedores:</label>
      <select class="form-control" id="filtro_proveedor" onchange="filtros('achica','pro');">
        <option selected="selected" value=" ">Todos</option>
          <option value="ESTDEST">Destajista</option>
         <option value="ESTPROV">Proveedores</option>
            <option value="ESTIND">Indirectos</option>
            <option value="ESTSUB">Subcontratista</option>
             <option value="ESTCAJA">Caja Chica</option>
            <option value="ESTNOMOC">Nomina Campo</option>
            <option value="ESTNOMC">Nomina Oficina Central</option>
        </select>
    </div>
</div>
<!--Fin filtros -->

          
      </div><!-- ENd panel body -->
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

<div class="row" style="padding-top: 10px;">
  <?php if ($row['estatus']==1){ ?>
    <div class="col-sm-3">
    <button style="width:100%" class="btn btn-primary btn-xm" onclick="autorizarem(2,<?php echo $sema; ?>);"> Autorizar</button>
    </div>
    <div class="col-sm-3">
    <button style="width:100%" class="btn btn-danger btn-xm" onclick="autorizarem(3,<?php echo $sema; ?>);"> Cancelar</button>

    </div>
  <?php } ?>
  <?php if ($row['estatus']==2){ ?>
    <div class="col-sm-6">
      <b><font color="green">Remesa aceptada</font></b>
    </div>
  <?php } ?>
  <?php if ($row['estatus']==3){ ?>
    <div class="col-sm-6">
      <b><font color="#ff0000">Remesa rechazada</font></b>
    </div>
  <?php } ?>
</div>

