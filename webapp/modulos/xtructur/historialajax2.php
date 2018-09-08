<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}


include('conexiondb.php');


$idReq=$_POST['idReq'];


	$SQL = "SELECT a.*, concat(b.nombre,' ',b.apellido1,' ',b.apellido2) as solicito, concat(c.nombre,' ',c.apellido1,' ',c.apellido2) as autorizo, substr(a.fecha_captura,1,10) as fechaSol, substr(a.fecha_captura,12,5) as horaSol,substr(a.fechaaut,1,10) as fechaaut, substr(a.fechaaut,12,5) as horaaut, sum(d.cantidad*e.precio) as montoReq,TIMEDIFF(a.fechaaut, a.fecha_captura) as tiempo FROM constru_requis a 
	left join empleados b on b.idempleado=a.solicito
	left join empleados c on c.idempleado=a.autorizo
	left join constru_requisiciones d on d.id_requi=a.id
	left join constru_insumos e on e.id=d.id_clave
	WHERE a.id_obra='$id_obra' AND a.id='$idReq' group by a.id  ORDER BY id desc;";
	$result = $mysqli->query($SQL);

	if($result->num_rows>0){


		while($row = $result->fetch_array()) {
	    	$r[]=$row;
		}
		$SQL2 = "SELECT
		if(emp.nombre is null, concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno), concat(emp.nombre,' ', emp.apellido1,' ', emp.apellido2)) as Solicito, 
		a.id pedis,  
		concat('REQ-',c1.id) Requisicion,
		concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno) as Solicito_Req,
		concat('RT-',d2.id,' - ',d2.nombre,' ',d2.paterno,' ',d2.materno) as autorizo_Req,
		a.fecha_entrega, c.clave, c.descripcion, c.unidtext,  b.cantidad Rcantidad, c.precio, b.cantidad*b.precio_compra importe, b.cantidad*c.precio importec, a.id pedid, b.id pedsid, c.id insuid,concat('IDPROV-',e2.id_alta,' - ',e2.razon_social_sp) prov,b.precio_compra as precioc,
		CASE a.estatus 
		WHEN 1 THEN 'Pendiente'
		WHEN 2 THEN 'Cancelada'
		WHEN 3 THEN 'Autorizada'
		END as estatus,
		CASE a.estatus
		WHEN 1 THEN b.precio_compra
		WHEN 2 THEN can.precio_compra
		WHEN 3 THEN b.precio_compra
		END as precio_compra,
		CASE a.estatus
		WHEN 1 THEN b.cantidad
		WHEN 2 THEN can.cantidad
		WHEN 3 THEN b.cantidad
		END as cantvalida,TIMEDIFF(a.fechaaut, a.fecha_captura) as tiempo,
		if(b.elprov is null,a.id_prov,b.elprov) as prreal, e2.id_alta as prrep,
			substr(a.fecha_captura,1,10) as fechaSol, substr(a.fecha_captura,12,5) as horaSol,
		substr(a.fechaaut,1,10) as fechaaut, substr(a.fechaaut,12,5) as horaaut , sum(b.cantidad*b.precio_compra) as totaltotal
		FROM constru_pedis a
		LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
		LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
		LEFT JOIN constru_requisiciones b on b.id_requi=c1.id and b.borrado=0
		LEFT JOIN constru_insumos c on c.id=b.id_clave
		LEFT JOIN constru_ocCanceladas can on can.id_pedi=a.id AND can.id_requi=b1.id_requis and can.id_clave=c.id
		left JOIN empleados emp on emp.idempleado=a.solicito
		left JOIN empleados emp2 on emp2.idempleado=a.idaut
		left JOIN constru_info_tdo d on d.id_alta=a.solicito
		left JOIN constru_info_tdo d2 on d2.id_alta=a.idaut
		left JOIN constru_info_sp e2 on e2.id_alta=a.id_prov
		WHERE a.id_obra='$id_obra' and b1.id_requis='$idReq' AND a.borrado=0 AND b1.borrado=0   and a.id_prov=b.elprov group by b.elprov ORDER BY a.id desc, b.id";
	$result2 = $mysqli->query($SQL2);


		
	}


	if($result2->num_rows>0){
		while($row2 = $result2->fetch_array()) {
			$oc[]=$row2;
			//Pintar lo de ordenes de compra
           $comp=$row2['pedis'];
			
			$SQL3 = " SELECT a.id as id,a.id_oc,concat('OC-',a.id_oc) as Orden, b.id as ocid,
			concat('REQ-',c.id) as Requisicion, concat('ENT-',a.id,' / ',a.fecha) as Entrada,
			e.clave, e.descripcion, e.unidtext,  b.llego Rcantidad, e.precio,
		b.id_insumo, a.fecha, d.precio_compra, d.precio_compra*b.llego as importec, substr(a.fecha,1,10) as fechaSol, substr(a.fecha,12,5) as horaSol,concat(u.nombre,' ',u.paterno,' ',u.materno) as autorizo, sum(b.llego*d.precio_compra) as totalent
			from constru_bit_entradas a
			inner join constru_entrada_almacen b on b.id_bit_entrada=a.id AND b.id_oc=a.id_oc
			LEFT JOIN constru_requis c  on c.id=b.id_req AND c.id=b.id_req
			LEFT JOIN constru_requisiciones d on d.id_requi=c.id AND d.id_clave=b.id_insumo
			LEFT JOIN constru_insumos e on e.id=d.id_clave AND e.id=b.id_insumo
			left join constru_info_tdo u on u.id_alta=a.id_almacenista
			WHERE a.borrado=0 AND a.id_obra='$id_obra' and b.llego>0 and a.id_oc='$comp'  group by a.id ORDER BY  a.id_oc desc, a.id desc, d.id ";
			$result3 = $mysqli->query($SQL3);
			if($result3->num_rows>0){
				while($row3 = $result3->fetch_array()) {
					$e[]=$row3;
					//Pintar lo de entradas

				}
			}




		  $SQL4 = "SELECT a.id as id,a.id_oc,concat('OC-',a.id_oc) as Orden,
			concat('REQ-',c.id) as Requisicion, concat('SAL-',a.id,' / ',a.fecha) as Salida,
			e.clave, e.descripcion, e.unidtext,  b.salio Rcantidad, e.precio,
			 b.id_insumo, a.fecha, d.precio_compra, d.precio_compra*b.salio as importec,substr(a.fecha,1,10) as fechaSol, substr(a.fecha,12,5) as horaSol, b.id as ocid, sum(b.salio*d.precio_compra) as totalsal,
                 concat(u2.nombre,' ',u2.paterno,' ',u2.materno) as autorizo,
                 concat(u.nombre,' ',u.paterno,' ',u.materno) as recibio,
                 concat(u3.nombre,' ',u3.paterno,' ',u3.materno) as entrego
			from constru_bit_salidas a
			inner join constru_salida_almacen b on b.id_bit_salida=a.id AND b.id_oc=a.id_oc
			LEFT JOIN constru_requis c  on c.id=b.id_req AND c.id=b.id_req
			LEFT JOIN constru_requisiciones d on d.id_requi=c.id AND d.id_clave=b.id_insumo AND d.borrado=0
			LEFT JOIN constru_insumos e on e.id=d.id_clave AND e.id=b.id_insumo
			left join constru_info_tdo u on u.id_alta=a.id_recibio
			left join constru_info_tdo u2 on u2.id_alta=a.id_autorizo
			left join constru_info_tdo u3 on u3.id_alta=a.id_entrego
			WHERE a.borrado=0 AND a.id_obra='$id_obra' and b.salio>0 and a.id_oc='$comp' group by a.id ORDER BY a.id_oc desc, b.id desc, d.id";
			$result4 = $mysqli->query($SQL4);;
                 
			if($result4->num_rows>0){
				while($row4 = $result4->fetch_array()) {
					$s[]=$row4;
					//Pintar lo de salidas

				}
			}

		}}

		

	?>
	<table  class="table" id="t1" style="font-size:12px;" border="2">
<tr>
    <td id="headcolspan" colspan="9" style="background-color:#b3b3b3"><b>Requisicion - <?php echo $r[0]['id']; ?></b></td>
  </tr>
  <tbody id="headReq" style="font-weight: bold;">
  <tr>
    <td>Tiempo</td>
    <td>Monto</td>
<td>Autorizo</td>
<td>Fecha aut.</td>
<td>Hora aut.</td>
<td>Solicito</td>
<td>Fecha sol.</td>
<td>Hora sol.</td>
  </tr>

  <?php 
      if($r!=0){
          foreach ($r as $k => $v) { ?>
            <tr><td><?php echo $v['tiempo']; ?></td><td>$<?php echo $v['montoReq'];?></td><td><?php echo $v['autorizo']; ?></td><td><?php echo $v['fechaaut']; ?></td><td><?php echo $v['horaaut'];?></td><td><?php echo $v['solicito']; ?><td><?php echo $v['fechaSol'];?></td><td><?php echo $v['horaSol']; ?></td></tr>
          <?php } } ?>
  </tbody>
  <tbody id="bodyReq">
  </tbody>
</table>
<br>
<?php
if($oc!=0){
foreach ($oc as $k => $v){?>
<br>
<table class="table" id="t1" style="font-size:12px;" border="2">
<tr style="background-color:#f3f3f3;">
<td id="headcolspan" colspan="9" style="background-color:#c3c3c3"><b>Orden de compra <?php echo $v['pedis']; ?> Estado: <?php echo $v['estatus']; ?></b></td>
</tr>
<tbody id="headReqOC" style="font-weight: bold;">
<tr>
<td>Tiempo</td>
<td>Monto</td>
  <td>Proveedor</td>
   <td>Autorizo</td>
<td>Fecha aut.</td>
<td>Hora aut.</td>
<td>Solicito</td>
<td>Fecha sol.</td>
<td>Hora sol.</td>
 	</tr>
 	<tr>
<td> <?php echo $v['tiempo']; ?></td>
<td>$<?php echo $v['totaltotal']; ?></td>
<td><?php echo $v['prov']; ?></td>
<td><?php echo $v['autorizo_Req']; ?></td>
  <td><?php echo $v['fechaaut']; ?></td>
  <td><?php echo $v['horaaut']; ?></td>
  <td><?php echo $v['Solicito_Req']; ?></td>
  <td><?php echo $v['fechaSol']; ?></td>
  <td><?php echo $v['horaSol']; ?></td>
  </tr>
 	</tbody>
 	<tbody id="bodyReqOC">
 	</tbody>
</table>
<br>
<?php
if($e!=0 ){
foreach ($e as $f => $g){
if($v['pedis']==$g['id_oc']){ ?>
<table class="table" id="t1" style="font-size:12px;" border="2">
<tr style="background-color:#f3f3f3;">
<td id="headcolspan" colspan="9" style="background-color:#d3d3d3"><b>Entradas <?php echo $g['id']; ?></b></td>
</tr>
<tbody id="headReqOC" style="font-weight: bold;">
<tr>
<td>Tiempo</td>
<td>Monto</td>
<td>Autorizo</td>
<td>Fecha aut.</td>
<td>Hora aut.</td>
<td>Solicito</td>
<td>Fecha sol.</td>
<td>Hora sol.</td>
</tr>
<tr>
<td><?php echo $g['tiempo']; ?></td>
<td>$<?php echo $g['totalent']; ?></td>
<td><?php echo $g['autorizo']; ?></td>
<td>Fecha aut.</td>
<td>Hora aut.</td>
<td>Solicito</td>
 <td><?php echo $g['fechaSol']; ?></td>
<td>Hora sol.</td>
</tr>
</tbody>
<tbody id="bodyReqOC">
</tbody>
</table>
 <?php }}} ?>
<br>
<?php if($s!=0 ){
foreach ($s as $f => $g){
if($v['pedis']==$g['id_oc']){?>
<table class="table" id="t1" style="font-size:12px;" border="2">
<tr style="background-color:#f3f3f3;">
<td id="headcolspan" colspan="9" style="background-color:#d3d3d3"><b>Salidas <?php echo $g['id']; ?> </b></td>
</tr>
<tbody id="headReqOC" style="font-weight: bold;">
<tr>
<td>Monto</td>
<td>Autorizo</td>
<td>Entrego</td>
<td>Recibio</td>
<td>Fecha aut.</td>
<td>Hora aut.</td>
<td>Solicito</td>
<td>Fecha sol.</td>
<td>Hora sol.</td>
</tr>
<tr>
<td>$<?php echo $g['totalsal']; ?></td>
<td><?php echo $g['autorizo']; ?></td>
<td><?php echo $g['entrego']; ?></td>
<td><?php echo $g['recibio']; ?></td>
<td>Fecha aut.</td>
<td>Hora aut.</td>
<td>Solicito</td>
 <td><?php echo $g['fechaSol']; ?></td>
 <td>Hora sol.</td>
</tr>
</tbody>
<tbody id="bodyReqOC">
</tbody>
</table>

 <?php } }  } }} ?> 

 <br>
