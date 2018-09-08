<style type="text/css">
	.grid_h0{ background-color: #c3c3c3 !important; border: 1px solid #c3c3c3;}
	.grid_h1{ background-color: #d3d3d3 !important;}
	.grid_h2{ background-color: #e3e3e3 !important;}
	.grid_h3{ background-color: #f3f3f3 !important;}

	.colores{ background-color: #f3f3f3 !important; color:#ff0000;}


#lc_chat_layout{
	display:none;
}

</style>


<?php
 $SQL ="SELECT tiempo from constru_config where id_obra='$idses_obra'";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $rowt = $result->fetch_array();
    $tiempo=$rowt['tiempo'];
  }else{  
    $tiempo=0;
  }

  if($tiempo==0){ $ms=0; }
  if($tiempo==1){ $ms=600000; }
  if($tiempo==2){ $ms=900000; }
  if($tiempo==3){ $ms=1800000; }
  if($tiempo==4){ $ms=3600000; }





$SQL = '(select "Dest" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Estimacion Destajista ",a.id) as tipo  
from constru_estimaciones_bit_destajista a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_destajista
where a.estatus=0 and b.id='.$idses_obra.' and alt.id_tipo_alta=2)
union all
(select "Subc" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Estimacion Subcontratista ",a.id) as tipo  
from constru_estimaciones_bit_subcontratista a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_subcontratista
where a.estatus=0 and b.id='.$idses_obra.' and alt.id_tipo_alta=4)
union all
(select "Prov" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Estimacion Proveedor ",a.id) as tipo  
from constru_estimaciones_bit_prov a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_prov
where a.estatus=0 and b.id='.$idses_obra.')
union all
(select "Clie" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Estimacion Cliente ",a.id) as tipo  
from constru_estimaciones_bit_cliente a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_cliente
where a.estatus=0 and b.id='.$idses_obra.')
union all
(select "Chic" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Estimacion Caja chica ",a.id) as tipo  
from constru_estimaciones_bit_chica a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
where a.estatus=0 and b.id='.$idses_obra.')
union all
(select "Indi" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Estimacion Indirectos ",a.id) as tipo  
from constru_estimaciones_bit_indirectos a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
where a.estatus=0 and b.id='.$idses_obra.')
union all
(select "Requ", a.id, a.fecha_captura, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha_captura,1,10) as fecha, substr(a.fecha_captura,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Requisiciones ",a.id) as tipo
from constru_requis a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.solicito
where a.estatus=1 and b.id='.$idses_obra.')
union all
(select "Pedi", a.id, a.fecha_captura, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha_captura,1,10) as fecha, substr(a.fecha_captura,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Ordenes de compra ",a.id) as tipo
from constru_pedis a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.solicito
where a.estatus=1 and b.id='.$idses_obra.')
union all
(select "Reme", a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Cuentas por pagar" as tipo  
from constru_bit_remesa a
left join constru_generales b on b.id=a.id_obra
left join constru_bit_remesas e on e.id_bit_remesa=a.id
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=e.id_solicito
where a.estatus=1 and b.id='.$idses_obra.')
union all
(select "Extra" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Solicitud Extraordinarios ",a.id) as tipo  
from constru_bit_solicitudes a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_solicito
where a.estatus=0 and a.naturaleza="extra" and b.id='.$idses_obra.' )
union all
(select "Adic" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Solicitud Adicionales ",a.id) as tipo  
from constru_bit_solicitudes a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_solicito
where a.estatus=0 and b.id='.$idses_obra.' and a.naturaleza="adicional")
union all
(select "Nocob" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Solicitud no cobrables ",a.id) as tipo  
from constru_bit_solicitudes a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_solicito
where a.estatus=0 and b.id='.$idses_obra.' and a.naturaleza="no cobrable")
union all
(select "ESTNOMC" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Nomina Central ",a.id) as tipo  
from constru_bit_nominaca a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_aut
where a.estatus=0 and b.id='.$idses_obra.' and a.id_tecnico=1)
union all
(select "ESTNOMOC" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Nomina Campo ",a.id) as tipo  
from constru_bit_nominaca a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_aut
where a.estatus=0 and b.id='.$idses_obra.' and a.id_tecnico=2)
union all
(select "NOMDEST" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, concat("Nomina Obreros ",a.id) as tipo  
from constru_bit_nominadest a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_aut
where a.estatus=0 and b.id='.$idses_obra.') order by 3 desc;';
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $resultados=$result->num_rows;
    while($row = $result->fetch_array()){
    	$pends[]=$row;
    }

  }else{	
    $resultados=0;
  	$pends=0;
  }
?>

<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <!--<div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Tablero de solicitudes pendientes</div>
          </div>
        </div>-->
        <div class="panel panel-default" >
          <!-- Panel Heading -->
          <div class="panel-heading">
          <div class="panel-title">Tablero de solicitudes pendientes</div>
          </div><!-- End panel heading -->

          <!-- Panel body -->
          <div class="panel-body" >
        <div class="row">
          <div class="col-md-12" style="padding:20px 20px 20px 30px;">
            <table style="font-size: 12px;">
            <tr>
              <th width="335">Empresa</th>
              <th width="335">Usuario</th>
              <th width="335">Tipo</th>
              <th width="220">Fecha</th>
              <th width="220">Hora</th>
              <th width="220" align="center">&nbsp;</th>
            </tr>
            <tbody id="bodtable">
            <?php foreach ($pends as $k => $v) { ?>
              <tr style="height:30px;">
                  <td><?php echo $v['nombre']; ?></td>
                  <td><?php echo $v['user']; ?></td>
                  <td><?php echo $v['tipo']; ?></td>
                  <td><?php echo $v['fecha']; ?></td>
                  <td><?php echo $v['hora']; ?></td>
                  <td align="center"><button class="btn btn-primary btn-xs" onclick="llamadaAutorizar('<?php echo $v["menu"]; ?>')">Ver</button></td>
              </tr>
            <?php } ?>
            </tbody>
            </table>
          </div>
        </div>
            </div><!-- ENd panel body -->
        </div>
      </div>
    </div>

</body>


<script>
function showAlert() {
  alert('Tienes una solicitud pendiente');
  window.location.reload();


}
function timeout(results) {
    console.log('time new');
    nres=0;
    setTimeout(function () {
        $.ajax({
          async:false,
          dataType:'json',
          url:"ajax.php",
          type: 'POST',
          data:{opcion:'verificaNuevaAut',results:results},
          success: function(r){
            if(r.success==1){
              nres=r.nres;

              var audioElement = document.createElement('audio');
              audioElement.setAttribute('src', 'notificacion.ogg');
              audioElement.setAttribute('autoplay', 'autoplay');
              audioElement.setAttribute('onended', 'showAlert()');
              audioElement.addEventListener("load", function() {
                audioElement.play();
              }, true);
              audioElement.play();
              sleepX(3000);
              
              
            }else{
              nres=results;
            }
          }
        });
        timeout(nres);
    }, 10000);
}

function timeoutConf(results,ms) {
  console.log('time config');
  if(ms==0){
    return false;
  }
    setTimeout(function () {
        if(results>0){
          var audioElement = document.createElement('audio');
              audioElement.setAttribute('src', 'notificacion.ogg');
              audioElement.setAttribute('autoplay', 'autoplay');
              audioElement.setAttribute('onended', 'showAlert();');
              audioElement.addEventListener("load", function() {
                audioElement.play();
              }, true);
              audioElement.play();
              sleepX(3000);
              
        }else{
          timeoutConf(ms);
        }
    }, ms);
}



function sleepX(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}



$(function() {
  timeout(<?php echo $resultados; ?>);
  timeoutConf(<?php echo $resultados; ?>,<?php echo $ms; ?>);
});
</script>
