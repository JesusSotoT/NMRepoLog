<?php
    //ini_set('memory_limit', '128000M');
    include_once("../../netwarelog/webconfig.php");
	//$mysqli = new mysqli($servidor , $usuariobd, $clavebd, $bd);
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
date_default_timezone_set('America/Mexico_City');

//OBTENER NUMERO DE LA SEMANA DEPENDIENDO DE LA FECHA
function getweek($fecha){
    $date = new DateTime($fecha);
    $week = $date->format("W");
    return $week;
}

//OBTENER PERIDOD SEMANA INICIO Y FIN &$start, &$end 
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

//OBTENER XXXANO
function xxano($fecha){
    $date = new DateTime($fecha);
    $week = $date->format("W");
    $ano=explode('-', $fecha);
    $ano=$ano[0];

    return $ano.'-'.$week;
}
// DIVIDE LA FECHA 2015-33 Y DEVUELVE UN ARREGLO
function dividirfecha($fecha){
	$fechasxx = explode("-", $fecha);
	return $fechasxx;
}
function formatFecha($fecha){
		$yyyy = substr($fecha, 0,4);
		$mm = substr($fecha, 5,2);
		$dd = substr($fecha, 8,2);

		if($mm=='01'){ $mm = 'ENERO';}
		if($mm=='02'){ $mm = 'FEBRERO';}
		if($mm=='03'){ $mm = 'MARZO';}
		if($mm=='04'){ $mm = 'ABRIL';}
		if($mm=='05'){ $mm = 'MAYO';}
		if($mm=='06'){ $mm = 'JUNIO';}
		if($mm=='07'){ $mm = 'JULIO';}
		if($mm=='08'){ $mm = 'AGOSTO';}
		if($mm=='09'){ $mm = 'SEPTIEMBRE';}
		if($mm=='10'){ $mm = 'OCTUBRE';}
		if($mm=='11'){ $mm = 'NOVIEMBRE';}
		if($mm=='12'){ $mm = 'DICIEMBRE';}

		$fechaL = $dd.' de '.$mm.' del '.$yyyy;
		return $fechaL;
	}

$fff=date('Y-m-d');
$db = mysql_connect($servidor, $usuariobd, $clavebd)
or die("Connection Error: " . mysql_error());
mysql_select_db($bd) or die("Error conecting to db.");
mysql_query("set names 'utf8'");
$opcion=$_POST['opcion'];


if(isset($opcion)&& $opcion=='bancos'){

$stringbanc=$_POST['stringbanco'];
	$_SESSION['stringbanc']=$stringbanc;
$id=$_POST['id'];
$_SESSION['prov']=$id;

}

if(isset($opcion)&& $opcion=='bancosa'){
$id=$_POST['id'];
$_SESSION['prov']=$id;
	$SQL = "SELECT a.nombre,b.idbanco,b.numCT from cont_bancos a
LEFT JOIN cont_bancosPrv b on b.idbanco=a.idbanco
LEFT JOIN mrp_proveedor c on c.idPrv=b.idPrv where c.id_xtructur='$id';
	";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
		$cadena[]=$row;

}
echo json_encode($cadena);
	exit();
}


if(isset($opcion) && $opcion=='constructoras'){
	$SQL = "SELECT id,nombre FROM constru_contratista WHERE borrado=0 ORDER BY nombre;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['nombre'].';';
	}

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['prov'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay constructoras dadas de alta';
	}

	echo $cadena;
	exit();
}




if(isset($opcion) && $opcion=='catagrupador'){
	$SQL = "SELECT id,agrupador FROM constru_cat_agrupador WHERE borrado=0 ORDER BY agrupador;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['agrupador'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay agrupadores dados de alta';
	}

	echo $cadena;
	exit();
}

if(isset($opcion) && $opcion=='areastecs'){
	$idagru=$_POST['idagru'];
	$SQL = "SELECT id,nombre FROM constru_especialidad WHERE id_agrupador='$idagru' AND borrado=0 ORDER BY nombre;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['nombre'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay partidas dadas de alta';
	}

	echo $cadena;
	exit();
}

if(isset($opcion) && $opcion=='cats'){
	$idfam=$_POST['idfam'];
	$SQL = "SELECT id, if(sal_semanal>0,concat(categoria,' / $',sal_semanal),concat(categoria,' / $',sal_mensual)) as categoria FROM constru_categoria WHERE id_familia='$idfam' AND borrado=0 ORDER by id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['categoria'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay tabulador de categorias dados de alta para esta familia';
	}

	echo $cadena;
	exit();
}

if(isset($opcion) && $opcion=='desc_insumos_mat_obra'){
	$return=array();
	$id_material=$_POST['id_material'];
	$obra_sal=$_POST['obra_sal'];

	if($id_material=='t'){
		$and = ' ';
	}else{
		$and = ' AND id_familia='.$id_material.' ';
	}

	$SQL = "SELECT id,concat(clave,' - ',SUBSTRING(descripcion,1,100),'...') clave FROM constru_insumos WHERE unidtext!='' AND id_obra='$obra_sal' ".$and." ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	/*if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay insumos dados de alta';
	}*/

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();

}

if(isset($opcion) && $opcion=='delent'){
	   $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
		$id=$_POST['id'];
		$pass=$_POST['pass'];
		 $fecha=date('Y-m-d H:i:s');
		$idusr = $_SESSION['accelog_idempleado'];
		   
		$SQL = "SELECT nombreusuario as username, idempleado from administracion_usuarios where idempleado='$idusr';";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 
 $nombre=$row['username'];
    $id_username_global=$row['idempleado'];
	mysql_query("DELETE FROM constru_bit_entradas where id='$id' ") or die("Couldn t execute query.".mysql_error());
	mysql_query("DELETE FROM constru_entrada_almacen where id_bit_entrada='$id' ") or die("Couldn t execute query.".mysql_error());

	mysql_query("INSERT INTO constru_superadmin(fecha,modulo,usuario,idusuario,pass,accion,idobra) values('$fecha','Visualizar entradas de almacen','$nombre','$id_username_global','$pass',concat('Se elimino la entrada ','$id'),'$id_obra')  ") or die("Couldn t execute query.".mysql_error());

	exit();

}



if(isset($opcion) && $opcion=='delsal'){
	   $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
		$id=$_POST['id'];
		$pass=$_POST['pass'];
		 $fecha=date('Y-m-d H:i:s');
		$idusr = $_SESSION['accelog_idempleado'];
		$SQL = "SELECT nombreusuario as username, idempleado from administracion_usuarios where idempleado='$idusr';";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $nombre=$row['username'];
	mysql_query("DELETE FROM constru_bit_salidas where id='$id' ") or die("Couldn t execute query.".mysql_error());
	mysql_query("DELETE FROM constru_salida_almacen where id_bit_salida='$id' ") or die("Couldn t execute query.".mysql_error());

		mysql_query("INSERT INTO constru_superadmin(fecha,modulo,usuario,idusuario,pass,accion,idobra) values('$fecha','Visualizar salidas de almacen','$nombre','$id_username_global','$pass',concat('Se elimino la salida ','$id'),'$id_obra')  ") or die("Couldn t execute query.".mysql_error());
	exit();

}



if(isset($opcion) && $opcion=='desc_insumos_22'){
	$return=array();
	$id_insumo=$_POST['id_insumo'];
	$id_obra=$_POST['obra_sal'];
	$SQL = "SELECT clave,descripcion,unidtext,precio FROM constru_insumos WHERE id_obra='$id_obra' AND id='$id_insumo' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){

	$SQL ="SELECT a.*, (a.unidad*a.precio) as tc, (a.unidad*a.precio) as tv, 
	(SELECT if(sum(b.llego) is null,0, sum(b.llego)) FROM constru_entrada_almacen b where b.id_obra=a.id_obra and b.id_insumo=a.id) as sumallego,
	(SELECT if(sum(c.salio) is null,0, sum(c.salio)) FROM constru_salida_almacen c where c.id_obra=a.id_obra and c.id_insumo=a.id) as sumasalio,
	if(sum(bb.cantidad) is null,0, sum(bb.cantidad)) as salidatras,
	if(sum(cc.cantidad) is null,0, sum(cc.cantidad)) as entradatras
FROM constru_insumos a 
left join constru_bit_traspasos b on b.id_obra_salida=a.id_obra and b.estatus=4
left join constru_traspasos bb on bb.id_bit_traspaso=b.id and bb.id_clave=a.id

left join constru_bit_traspasos c on c.id_obra_entrada=a.id_obra and c.estatus=4
left join constru_traspasos cc on cc.id_bit_traspaso=c.id and cc.id_clave_sal=a.id

WHERE a.id_obra='$id_obra' AND a.borrado=0 AND a.id='$id_insumo'
Group by a.id";
	$result4 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result4)>0){
		$row4 = mysql_fetch_assoc($result4);
		$return['almacen']=($row4['sumallego']*1)-($row4['sumasalio']*1);
		$return['almacen']-=$row4['salidatras'];
		$return['almacen']+=$row4['entradatras'];
	}

		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;

		    $SQL = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2;

			$SQL = "SELECT if( sum(a.cantidad) is null,0,sum(a.cantidad) ) as vol_est from constru_requisiciones a 
			Inner join constru_requis b on b.id=a.id_requi AND b.estatus!=2
			where a.id_requi>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_clave='$id_insumo' -- AND b.id_destajista='$id_des' 
			 order by a.id DESC LIMIT 1;";
			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['vol_est'];
			}else{
				$return['vol_anterior']=0;
			}


			



		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='traeme_obras_scookie'){
	$obra_sal=$_POST['obra_sal'];

	$SQL = "SELECT id,nomfam famat FROM constru_famat ORDER BY nomfam;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['famat'])).';';
		}
		$cadena=trim($cadena,';');
		$array['familias']=$cadena;
	}else{
		$array['familias']='0:No hay familias dadas de alta';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='cambiaFondo'){

	$op=$_POST['op'];
	$idusr = $_SESSION['accelog_idempleado'];
	mysql_query("DELETE FROM constru_user_interfaz where id_usuario='$idusr'; ") or die("Couldn t execute query.".mysql_error());
	echo $SQL = "INSERT INTO constru_user_interfaz (id_usuario, interfaz) VALUES ('".$idusr."', '".$op."');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}


if(isset($opcion) && $opcion=='save_traspaso'){
    $return=array();


    $id=$_POST['id'];
    $solicito=$_POST['solicito'];
    $iduserlog=$_POST['iduserlog'];

    $obrasal=$_POST['obrasal'];
    $obraent=$_POST['obraent'];

    $fecha=date('Y-m-d H:i:s');
    $xxano = xxano($fff);
$fentrada=$_POST['fentrada'];
    $resalida=$_POST['resalida'];
     $rentrada=$_POST['rentrada'];

$SQL="SELECT correoelectronico from administracion_usuarios where idempleado='$resalida';";



 $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $row = mysql_fetch_array($result); 
$email1=$row['correoelectronico'];
	
	$SQL="SELECT correoelectronico from administracion_usuarios where idempleado='$rentrada';";


 $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $row = mysql_fetch_array($result); 

$email2=$row['correoelectronico'];



    $SQL="INSERT INTO constru_bit_traspasos (id_obra_salida,id_obra_entrada,id_solicito,fecha_solicito,id_quien_os,id_quien_oe,fecha_envio) VALUES ('$obrasal','$obraent','$solicito','$fecha','$resalida','$rentrada','$fentrada');";
    mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $last_id = mysql_insert_id();
    $SQL = "UPDATE constru_traspasos SET id_bit_traspaso='$last_id' WHERE sestmp in (".$id.") AND id_bit_traspaso=0;";
  mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

$SQL="SELECT * from constru_traspasos a 
join constru_bit_traspasos b on b.id=a.id_bit_traspaso
join constru_insumos c on c.id=a.id_clave
where id_bit_traspaso='$last_id';";

	 $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

while($info = mysql_fetch_array($result)) {
   $filas=$filas."Material: <br> Clave:".$info['clave']."<br> descripcion: ".$info['descripcion']."<br> cantidad: ".$info['cantidad']."<br> unidad: ".$info['unidtext']." <br>";
}

$filas=$filas;


	if ($email1 != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Traspaso";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML('Estimado usuario, se le informa que hubo un traspaso.<br>'.$filas.'<br><b>Observaciones:</b> '.$obs);
            $mail->AddAddress($email1, $email1);
            $mail->Send();}

	if ($email2 != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Traspaso";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML('Estimado usuario, se le informa que hubo un traspaso.<br>'.$filas.'<br><b>Observaciones:</b> '.$obs);
            $mail->AddAddress($email2, $email2);
            $mail->Send();}
	





    $JSON = array('success' =>1, 'datos'=>1);




/*
    if($config_autorizaciones==0){
        $idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    
        $SQL="UPDATE constru_requis SET estatus=3,autorizo='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
        mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    }
*/
    echo json_encode($JSON);
    exit();
}

if(isset($opcion) && $opcion=='complemento_clientes'){
	$clientes=array();

	$SQL = "SELECT account_id, manual_code,  concat(description,' (',manual_code,')') as nombre_cuenta FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config);";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['account_id'].':'.$row['nombre_cuenta'].';';
		}
		$cadena=trim($cadena,';');
		$clientes['cuentas']=$cadena;
	}else{
		$clientes['cuentas']='0:No hay cuentas de contabilidad';
	}


	$SQL = "SELECT * from paises where idpais IN (1,43, 47,54,85);";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idpais'].':'.$row['pais'].';';
		}
		$cadena=trim($cadena,';');
		$clientes['paises']=$cadena;
	}else{
		$clientes['paises']='0:No hay paises registrados';
	}

	$SQL = "SELECT * from estados;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idestado'].':'.$row['estado'].';';
		}
		$cadena=trim($cadena,';');
		$clientes['estados']=$cadena;
	}else{
		$clientes['estados']='0:No hay estados registrados';
	}

	$SQL = "SELECT * from municipios;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idmunicipio'].':'.$row['municipio'].';';
		}
		$cadena=trim($cadena,';');
		$clientes['municipios']=$cadena;
	}else{
		$clientes['municipios']='0:No hay estados registrados';
	}




	echo json_encode($clientes);
	exit();

}
if(isset($opcion) && $opcion=='cmb_paises'){
	$return=array();
	$id_pais=$_POST['id_pais'];
	$SQL = "SELECT a.idestado,a.estado FROM estados a left join paises b on b.idpais=a.idpais WHERE a.idpais='$id_pais';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='cmb_operacion'){
	$return=array();
	$id_ter=$_POST['id_ter'];
	$SQL = "SELECT o.id, o.tipoOperacion FROM cont_tipo_operacion as o
    JOIN cont_relacion_ter_oper s on s.idtipoperacion=o.id
    JOIN cont_tipo_tercero t on t.id=s.idtipotercero
    where t.id='$id_ter';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='cmb_estados'){
	$return=array();
	$id_estado=$_POST['id_estado'];
	$SQL = "SELECT a.idmunicipio,a.municipio FROM municipios a left join estados b on b.idestado=a.idestado WHERE a.idestado='$id_estado';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];

    $SQLxx = "SELECT autorizaciones,puaut FROM constru_config WHERE id_obra='$id_obra' LIMIT 1;";
	$resultxx = mysql_query( $SQLxx ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($resultxx)>0){
		$rowxx = mysql_fetch_assoc($resultxx);
		$config_autorizaciones=$rowxx['autorizaciones'];
		$config_puaut=$rowxx['puaut'];
	}else{
		$config_puaut=1;
		$config_autorizaciones=1;
	}
}


if(isset($opcion) && $opcion=='get_obra'){
	

}


if(isset($opcion) && $opcion=='verificaNuevaAut'){
	  $SQL = '(select "Dest" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Estimacion Destajista" as tipo  
from constru_estimaciones_bit_destajista a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_destajista
where a.estatus=0 and b.id='.$id_obra.' and alt.id_tipo_alta=2)
union all
(select "Subc" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Estimacion Subcontratista" as tipo  
from constru_estimaciones_bit_subcontratista a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_subcontratista
where a.estatus=0 and b.id='.$id_obra.' and alt.id_tipo_alta=4)
union all
(select "Prov" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Estimacion Proveedor" as tipo  
from constru_estimaciones_bit_prov a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_prov
where a.estatus=0 and b.id='.$id_obra.')
union all
(select "Clie" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Estimacion Cliente" as tipo  
from constru_estimaciones_bit_cliente a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
inner join constru_altas alt on alt.id=a.id_cliente
where a.estatus=0 and b.id='.$id_obra.')
union all
(select "Chic" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Estimacion Caja chica" as tipo  
from constru_estimaciones_bit_chica a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
where a.estatus=0 and b.id='.$id_obra.')
union all
(select "Indi" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Estimacion Indirectos" as tipo  
from constru_estimaciones_bit_indirectos a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_autorizo
where a.estatus=0 and b.id='.$id_obra.')
union all
(select "Requ", a.id, a.fecha_captura, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha_captura,1,10) as fecha, substr(a.fecha_captura,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Requisiciones" as tipo
from constru_requis a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.solicito
where a.estatus=1 and b.id='.$id_obra.')
union all
(select "Pedi", a.id, a.fecha_captura, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha_captura,1,10) as fecha, substr(a.fecha_captura,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Ordenes de compra" as tipo
from constru_pedis a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.solicito
where a.estatus=1 and b.id='.$id_obra.')
union all
(select "Reme", a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Cuentas por pagar" as tipo  
from constru_bit_remesa a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=9999999999
where a.estatus=1 and b.id='.$id_obra.')
union all
(select "Extra" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Solicitud Extraordinarios" as tipo  
from constru_bit_solicitudes a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_solicito
where a.estatus=0 and a.naturaleza="extra" and b.id='.$id_obra.' )
union all
(select "Adic" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Solicitud Adicionales" as tipo  
from constru_bit_solicitudes a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_solicito
where a.estatus=0 and b.id='.$id_obra.' and a.naturaleza="adicional")
union all
(select "Nocob" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Solicitud no cobrables" as tipo  
from constru_bit_solicitudes a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_solicito
where a.estatus=0 and b.id='.$id_obra.' and a.naturaleza="no cobrable")
union all
(select "ESTNOMC" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Nomina Central" as tipo  
from constru_bit_nominaca a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_aut
where a.estatus=0 and b.id='.$id_obra.' and a.id_tecnico=1)
union all
(select "ESTNOMOC" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Nomina Campo" as tipo  
from constru_bit_nominaca a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_aut
where a.estatus=0 and b.id='.$id_obra.' and a.id_tecnico=2)
union all
(select "NOMDEST" as menu, a.id, a.fecha, a.estatus, 0 as TMP_ORDER, b.obra, c.nombre, substr(a.fecha,1,10) as fecha, substr(a.fecha,12,5) as hora, concat(d.nombre," ",d.apellido1) as user, "Nomina Obreros" as tipo  
from constru_bit_nominadest a
left join constru_generales b on b.id=a.id_obra
left join constru_contratista c on c.id=b.construye
left join empleados d on d.idempleado=a.id_aut
where a.estatus=0 and b.id='.$id_obra.')';


$numeroviejo=$_POST['results'];
	
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$nresults = mysql_num_rows($result);
	if($nresults>$numeroviejo){
		while($row = mysql_fetch_assoc($result)) {
	    	$r[]=$row;
		}
		$JSON = array('success' =>1, 'datos'=>$r, 'nres'=>$nresults);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();

}



if(isset($opcion) && $opcion=='cat_partidas'){
	$SQL = "SELECT id,partida FROM constru_cat_partidas WHERE borrado=0 AND (id_obra=0 OR id_obra='$id_obra') ORDER BY partida;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['partida'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay partidas dadas de alta';
	}

	echo $cadena;
	exit();
}

if(isset($opcion) && $opcion=='cat_especialidades'){
	$SQL = "SELECT id,especialidad FROM constru_cat_especialidad WHERE borrado=0 AND (id_obra=0 OR id_obra='$id_obra') ORDER BY especialidad;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['especialidad'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay especialidades dadas de alta';
	}

	echo $cadena;
	exit();
}

if(isset($opcion) && $opcion=='oc'){

	$SQL = "DELETE FROM constru_entradas WHERE sestmp>0 AND id_obra='$id_obra' AND id_entri=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "SELECT id, concat('OC-',id) oc FROM constru_pedis WHERE id_obra='$id_obra' AND borrado=0 AND estatus=3 ORDER BY id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $cadena.=$row['id'].':'.$row['oc'].';';
		}
	}else{
		$cadena='0:No hay ordenes de compra autorizadas';
	}
	$cadena=trim($cadena,';');
	$array['oc']=$cadena;
	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='autorizaOC'){

	$oc=$_POST['oc'];

   $fecha=date('Y-m-d H:i:s');
		$idusr = $_SESSION['accelog_idempleado'];
		$SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];

	$SQL = "UPDATE constru_pedis SET estatus=3,idaut='$id_username_global',fechaaut='$fecha' WHERE id='$oc';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	exit();

       }
if(isset($opcion) && $opcion=='correoOC'){
		$oc=$_POST['oc'];
   	$mens=$_POST['mens'];
   


$fecha=date('Y-m-d H:i:s');

     $id=$oc;
     $hoy = date("m_d_y"); 
	$nom_pdf = $id.'_orden_'.$hoy;
     $SQL = "SELECT substring(a.fecha_entrega,1,10) as fecha_entrega, b1.id_requis, j.obra, a.id, a.fecha_captura, a.id_prov, e2.razon_social_sp, c1.id_agru, i.nombre as nombre_agrupador, h.especialidad 
				as nombre_especialidad, f.partida as nombre_partida, g.nombre as nombre_area, c1.id_area, c1.id_esp, c1.id_part, 
				d.nombre as nombre_tecnico, d.id as id_tecnico, a.obsgen, fp.nombre as fpnombre, j.localizacion, j.director, k.nombre nombreDF, CONCAT('RFC: ', k.rfc, ' ',k.domicilio,' Col. ', k.colonia, ' ', k.ciudad) datosF, a.condpago, j.telefono, substring(a.fecha_entrega,12,5) as hora FROM constru_pedis a
				LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
				LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
				LEFT JOIN constru_requisiciones b on b.id_requi=c1.id and b.borrado=0
				LEFT JOIN constru_insumos c on c.id=b.id_clave
				left JOIN constru_info_tdo d on d.id_alta=a.solicito
				left JOIN constru_info_sp e2 on e2.id_alta=a.id_prov
				LEFT JOIN constru_especialidad es on es.id=c1.id_area
				left JOIN constru_partida e on es.id=c1.id_part
				left JOIN constru_cat_partidas f on f.id=e.id_cat_partida
				left JOIN constru_area g on g.id=c1.id_esp
				left JOIN constru_cat_especialidad h on h.id=g.id_cat_especialidad
				left JOIN constru_agrupador i on i.id=c1.id_agru
				left JOIN constru_generales j on j.id=a.id_obra
				left join forma_pago fp on fp.idFormapago=a.fpago
				left JOIN constru_contratista k on k.id=j.construye
				WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 and a.id = '$id';";
				$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				$row = mysql_fetch_array($result); 
			    $nombre_obra = $row['obra'];
			    $id_requis = $row['id_requis'];
			    $id = $row['id'];
			    $fecha_entrega = $row['fecha_entrega'];
			    $fecha_captura = $row['fecha_captura'];
			    $id_prov =$row['id_prov'];
			    $nombre_proveedor=$row['razon_social_sp'];
			    $id_tecnico =$row['id_tecnico'];
			    $nombre_tecnico=$row['nombre_tecnico'];
			    $id_agrupador =$row['id_agru'];
			    $nombre_agrupador =$row['nombre_agrupador'];
			    $id_especialidad =$row['id_esp'];
			    $nombre_especialidad =$row['nombre_especialidad'];
			    $id_partida =$row['id_part'];
			    $nombre_partida =$row['nombre_partida'];
			    $id_area =$row['id_area'];
			    $nombre_area =$row['nombre_area'];
			    $obsgen =$row['obsgen'];
			    $fpnombre =$row['fpnombre'];
			    $datosF =$row['datosF'];
			    $nombreDF =$row['nombreDF'];
			    $localizacion =$row['localizacion'];
			    $director =$row['director'];
			    $telefono =$row['telefono'];
			    $condpago =$row['condpago'];
			    $hora =$row['hora'];
       

		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }
              
			    if($obsgen==null){
			    	$obsgen=' ';
			    }
			    if($fpnombre==null){
			    	$fpnombre=' No identificada ';
			    }

			    $semana = getweek($fecha_captura);
			    week_bounds($fecha_captura, $start, $end); 

			    $fecha_capturaF = formatFecha($fecha_captura);


				require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					$content = '
					<page backbottom="14mm" footer="page">
					<br><br><br><br><br>
						<table border="1">
							<tr>
								<td border="0" width="240">'.$logo.'</td> <!-- logo-->
								<td border="0" width="190" align="center">ORDEN DE COMPRA</td>
								<td border="0" width="310">
									<table>
										<tr>
											<td>ORDEN DE COMPRA:</td>
											<td width="160" align="center" style="font-weight:bold;">OC-'.$id.'</td>
										</tr>
										<tr>
											<td>OBRA:</td>
											<td width="160" align="center" style="font-size:12;font-weight:bold;"">'.$nombre_obra.'</td>
										</tr>
										<tr>
											<td>FECHA:</td>
											<td width="160" align="center" style="font-weight:bold;">'.$fecha_capturaF.'</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<br>
						<table border="1">
							<tr>
								<td border="0" width="37">
									<table>	
										<tr>
											<td align="center" width="370">PROVEEDOR ASIGNADO</td>
										</tr>
										<tr>
											<td align="center" style="font-weight:bold;">'.$nombre_proveedor.'</td>
										</tr>
										<tr>
											<td align="center">Vendedor: '.$var.'</td>
										</tr>
									</table>
								</td>
								<td border="0" width="370">
									<table>	
										<tr>
											<td align="center" width="360">DATOS DE FACTURACIÓN:</td>
										</tr>
										<tr>
											<td align="center" style="font-weight:bold;">'.$nombreDF.'</td>
										</tr>
										<tr>
											<td width="350">'.$datosF.'</td>
										</tr>
										<tr>
											<td align="center"> Metodo de Pago: '.$fpnombre.'</td>
										</tr>
										<tr>
											<td align="center"> Cuenta de Pago: '.$var.'</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table border="1">
							<tr>
								<td width="100">Cantidad</td>
								<td width="100">Unidad</td>
								<td width="260">Descripción</td>
								<td width="122">Precio Unit.</td>
								<td width="122">Importe</td>
							</tr>';
									
					    			$SQL = "SELECT c.clave, c.descripcion, b.cantidad, c.unidtext, c.precio, b1.fecha_captura, b.precio_compra,
                                         if(b.elprov is null,a.id_prov,b.elprov) as prreal, e2.id_alta as prrep
					    			 FROM constru_pedis a
										LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
										LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
										LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
										LEFT JOIN constru_insumos c on c.id=b.id_clave
										-- left JOIN constru_info_tdo d on d.id_alta=a.solicito
										left JOIN constru_info_sp e2 on e2.id_alta=a.id_prov
										WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 and a.id='$id';";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									$total = 0;
									while($row = mysql_fetch_array($result)) {
										if($row['prreal']!=$row['prrep']){
											continue;
										}
								    		$clave = $row['clave'];
								    		$descripcion = $row['descripcion'];
								    		$cantidad = $row['cantidad'];
								    		$cantidadF =number_format($cantidad,2);
								    		$unidad = $row['unidtext'];
								    		$fecha_captura = $row['fecha_captura'];	

								    		$precio_concurso = $row['precio'];
								    		$importe_concurso = $cantidad * $precio_concurso;
								    		$total_concurso = $importe_concurso + $total_concurso;	
								    		$precio_concursoF = number_format($precio_concurso,2);
								    		$importe_concursoF = number_format($importe_concurso,2);
								    		$total_concursoF = number_format($total_concurso,2);

								    		$precio_compra = $row['precio_compra'];
								    		$importe_compra = $cantidad * $precio_compra;
								    		$total_compra = $importe_compra + $total_compra;
								    		$precio_compraF = number_format($precio_compra,2);
								    		$importe_compraF = number_format($importe_compra,2);
								    		$total_compraF = $total_compra;

								    		$subtotal=$total_compraF/1.16;
								    		$precioiva=$total_compraF*0.16;
								    		$totalt=($precioiva+$total_compraF);


								    																		
					    	$content.='
							<tr>
								<td style="text-align:right;">'.$cantidadF.'</td>
								<td style="text-align:center">'.$unidad.'</td>
								<td width="265"style="font-size:10px;">'.$descripcion.'</td>
								<td style="text-align:right;">$'.$precio_compraF.'</td>
								<td style="text-align:right;">$'.$importe_compraF.'</td>
					    	</tr>';
					    }
					    	$content.='
					    
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >SUBTOTAL</td>
					    		<td style=" text-align:right;">$'.number_format($total_compraF,2).'</td>					    		
					    	</tr>
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >IVA % 16.0</td>
					    		<td style=" text-align:right;">$'.number_format($precioiva,2).'</td>					    		
					    	</tr>
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >TOTAL</td>
					    		<td style=" text-align:right;">$'.number_format($totalt,2).'</td>					    		
					    	</tr>
					    
					    <!--
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >TOTAL</td>
					    		<td style=" text-align:right;">$'.number_format($totalt,2).'</td>					    		
					    	</tr>-->';

					    	$content.='
						</table>
						<table border="1">
							<tr>
								<td width="370">Condiciones de pago:</td>
								<td width="370">'.$condpago.'</td>
							</tr>
							<tr>
								<td width="370">Direccion de entrega:</td>
								<td width="370">'.$localizacion.'</td>
							</tr>
							<tr>
								<td width="370">Contacto en obra y telefono:</td>
								<td width="370">'.$director.' '.$telefono.'</td>
							</tr>
							<tr>
								<td width="370">Fecha de suministro:</td>
								<td width="370">'.$fecha_entrega.'</td>
							</tr>
							<tr>
								<td width="370">Horario de entrega:</td>
								<td width="370">'.$hora.'</td>
							</tr>
							<tr>
								<td colspan="2">Observaciones adicionales: '.$obsgen.'</td>
							</tr>
						</table>
		
					    <page_footer>
						    <table>
						    	<tr>
						    		<td height="30" width="250" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="250" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="250" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="250" align="center" style="font-weight:bold;">COMPRAS</td>
						    		<td width="250" align="center" style="font-weight:bold;">DIRECCIÓN</td>
						    		<td width="250" align="center" style="font-weight:bold;">Firma de PROVEEDOR</td>
						    	</tr>
		
						    </table>
					    </page_footer>
					</page>';

    
					$html2pdf = new HTML2PDF('A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');



     $SQL2="SELECT id_prov from constru_pedis WHERE id='$oc';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_prov'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correo_sp from constru_info_sp WHERE id_alta='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correo_sp'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Autorizacion de orden";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens);
            $mail->AddAddress($email, $email);
            $mail->AddAttachment('pdf/'.$nom_pdf.'.pdf');

            $mail->Send();}
	exit();
}



if(isset($opcion) && $opcion=='autorizaReq'){
	$fecha=date('Y-m-d H:i:s');
		$idusr = $_SESSION['accelog_idempleado'];
 $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
	$req=$_POST['req'];
	$SQL = "UPDATE constru_requis SET estatus=3,autorizo='$id_username_global',fechaaut='$fecha' WHERE id='$req';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$o=explode('###',$_POST['xnx']);;
		foreach ($o as $key => $value) {
			$v=explode('=', $value);
			$v0=$v[0];//idinsumo
			$v1=$v[1];//cantidad
	 echo $SQL = "UPDATE constru_requisiciones SET cantidad='$v[1]' WHERE id='$v[0]';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		}

	exit();
}

if(isset($opcion) && $opcion=='cancelReq'){
	$req=$_POST['req'];

	$obsave=addslashes($_POST['obs']);
	$obs=$_POST['obs'];
	$SQL = "UPDATE constru_requis SET estatus=2, obs_cancel='$obsave' WHERE id='$req';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());


$SQL = "SELECT correo_can from constru_config where id_obra='$id_obra';";
    	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$correocan=$row['correo_can'];
      }

      if($correocan==1){

	$SQL = "SELECT b.correoelectronico from constru_requis a 
	INNER JOIN administracion_usuarios b on b.idempleado=a.solicito
	WHERE a.id='$req' AND a.estatus=2;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$Email=$row['correoelectronico'];
	}else{
		$Email='';
	}

	if ($Email != '') {   
            require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelacion de requisicion";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML('Estimado usuario, se le informa que la requisicion numero <b>'.$req.'</b> fue cancelada.<br><br><b>Observaciones:</b> '.$obs);
            $mail->AddAddress($Email, $Email);


         @$mail->Send();

    }else{

    }}
	exit();
}

if(isset($opcion) && $opcion=='cancelOC'){
	$oc=$_POST['oc'];

	/*
	
*/
        $SQL="SELECT id from constru_entrada_almacen where id_oc='$oc';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
echo "ent";
exit();
	}

	$SQL = "SELECT a.id, b1.id_requis, c1.id_clave, c1.precio_compra, c1.cantidad, c1.elprov, a.id_prov,
	if(c1.elprov is null,a.id_prov,c1.elprov) as prreal
	from constru_pedis a 
	LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
	LEFT JOIN constru_requisiciones c1 on c1.id_requi=b1.id_requis
	where a.id='$oc';";

	$fecha =date('Y-m-d H:i:s');
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
			if($row['prreal']!=$row['id_prov']){
				continue;
			}
	    	$cadena.="('".$id_obra."','".$row['id']."','".$row['id_requis']."','".$row['id_clave']."','".$row['precio_compra']."','".$fecha."','".$row['cantidad']."'),";
		}
		$cadena=trim($cadena,',');
		$SQL = "INSERT INTO constru_ocCanceladas (id_obra,id_pedi,id_requi,id_clave,precio_compra,fecha,cantidad)  VALUES  ".$cadena.";";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}else{
		
	}



	$SQL = "UPDATE constru_pedis SET estatus=2 WHERE id='$oc';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	
	$SQL = "UPDATE constru_requis SET estatus=3 WHERE id in (
 select id_requis from constru_pedis a left join constru_pedidos b on b.id_pedid=a.id WHERE a.id='$oc');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	
	exit();
}

if(isset($opcion) && $opcion=='quitarasign'){
	$id=$_POST['id'];
	$idarea=$_POST['idarea'];
	$idpartida=$_POST['idpartida'];
	$idrecurso=$_POST['idrecurso'];

	$SQL = "DELETE FROM constru_asignaciones WHERE id='$id';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "DELETE FROM constru_vol_tope WHERE id_obra='$id_obra' AND id_area='$idarea' AND id_partida='$idpartida' AND id_clave='$idrecurso';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='nominaest'){
	$return=array();
	$id_des=$_POST['id_des'];
	//$id_edif=$_POST['id_edif'];
	$sd=$_POST['sd'];
	$ed=$_POST['ed'];

	$date = new DateTime($sd);
    $semana = $date->format("W");

	$SQL = "SELECT if(sum(total) is null,0,sum(total) ) as subtotal1, if(sum(total) is null,0,sum(total)) as total from constru_estimaciones_bit_destajista WHERE id_obra='$id_obra' AND id_destajista='$id_des' -- AND id_area='$id_edif'
	AND semana='$semana' AND estatus=1;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return,
				'semana'=>$semana);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='desc_gantt'){
	$codigo=$_POST['codigo'];
	$SQL = "SELECT descripcion from constru_recurso where id_obra='$id_obra' AND codigo='$codigo' and borrado=0 limit 1;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		return $row['descripcion'];
	}else{
		return $codigo;
	}
}

if(isset($opcion) && $opcion=='save_gantt'){

	$SQL = "TRUNCATE constru_poGanttLink;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$links=$_POST['links'];	
	$cad='';
	foreach ($links as $k => $v) {
		$cad.="('".$id_obra."','".$v['id']."','".$v['source']."','".$v['target']."','".$v['type']."'),";
	}
	$cad=trim($cad,',');

	echo $SQL = "INSERT INTO constru_poGanttLink (id_obra,id_link,source,target,type) VALUES ".$cad.";";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
}

if(isset($opcion) && $opcion=='data_gantt'){

	$por=$_POST['por'];
	$data=array();
	$links=array();
	$escabeza=array();
    $escabeza[0]=0;
    $escabeza[1]=0;
    $escabeza[2]=0;
    $escabeza[3]=0;



	$SQL = "SELECT a.id id0, b.id id1, c.id id2, d.id id3, da.partida as prtnom, ba.especialidad as espnom, a.codigo Agrupador, a.nombre anom, b.codigo Area, b.nombre arnom, c.codigo Especialidad,  d.codigo Partida, d.nombre prtnomv, e.*, (e.unidad*e.precio_venta) as importet, e.pu_destajo as pdes, e.pu_subcontrato as psub, a.id as agrid, b.id as espid, c.id as areid, d.id as parid, e.id as recid, substr(DATE_FORMAT(g.po_fecha, '%d-%m-%Y'),1,10) as start_date, g.po_dias, g.po_rendimiento
FROM constru_agrupador a 
left join constru_especialidad b on b.id_agrupador=a.id
left join constru_area c on c.id_especialidad=b.id
left join constru_cat_especialidad ba on ba.id=c.id_cat_especialidad
left join constru_partida d on d.id_area=c.id
left join constru_cat_partidas da on da.id=d.id_cat_partida
left join constru_asignaciones g on g.id_partida=d.id AND g.id_obra='$id_obra'
left join constru_recurso e on e.id=g.id_recurso AND e.id_obra='$id_obra'
where 1=1  AND a.id_obra='$id_obra' AND a.borrado=0 AND c.borrado=0 AND d.borrado=0 AND da.borrado=0 AND e.borrado=0 and g.po_fecha is not null and g.po_dias is not null and g.po_rendimiento is not null
ORDER BY a.id, b.id, c.id, d.id asc, g.id, e.id;";

	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		$r=1;
		$colorhead='ffffff';
		$txtcolorhead='cccccc';
		while($row = mysql_fetch_assoc($result)) {
		    //$return[]=$row;
			$row['po_dias']=ceil($row['po_dias']);
		    if($escabeza[0]!=$row['id0']){
	            $escabeza[0]=$row['id0'];
	            $data[]=array('id'=>'AG-'.$row['id0'], 
	            		'text'=>$row['anom'],
	            		'start_date'=>'',
	            		'duration'=>'',
	            		'progress'=>1,
	            		'open'=>1,
	            		'color'=>$colorhead,
	            		'textColor'=>$txtcolorhead
	            	);
	        }

	        if($escabeza[1]!=$row['id1']){
	            $escabeza[1]=$row['id1'];
	            $data[]=array('id'=>'AR-'.$row['id1'], 
	            		'text'=>$row['arnom'],
	            		'start_date'=>'',
	            		'duration'=>'',
	            		'progress'=>1,
	            		'open'=>1,
	            		'parent'=>'AG-'.$row['id0'],
	            		'color'=>$colorhead,
	            		'textColor'=>$txtcolorhead
	            	);
	        }

	        if($escabeza[2]!=$row['id2']){
	            $escabeza[2]=$row['id2'];
	            $data[]=array('id'=>'ES-'.$row['id2'], 
	            		'text'=>$row['espnom'],
	            		'start_date'=>'',
	            		'duration'=>'',
	            		'progress'=>1,
	            		'open'=>1,
	            		'parent'=>'AR-'.$row['id1'],
	            		'color'=>$colorhead,
	            		'textColor'=>$txtcolorhead
	            	);
	        }

	        if($escabeza[3]!=$row['id3']){
	            $escabeza[3]=$row['id3'];
	            $data[]=array('id'=>'PA-'.$row['id3'], 
	            		'text'=>$row['prtnom'],
	            		'start_date'=>'',
	            		'duration'=>'',
	            		'progress'=>1,
	            		'open'=>1,
	            		'parent'=>'ES-'.$row['id2'],
	            		'color'=>$colorhead,
	            		'textColor'=>$txtcolorhead
	            	);
	        }

	        //$totdesc  = strlen($row['descripcion']);
	        //$divi = ceil($totdesc/20);

	        $cortacadena = str_split($row['descripcion'], 35);
	        //var_dump($cortacadena);
	        $ndesc='';
	        $x=0;
	        foreach ($cortacadena as $key => $value) {
	        	$ndesc.=$value.'<br>';
	        	if($x==3){
	        		break;
	        	}
	        	$x++;
	        }

	       	$ndesc=utf8_encode($ndesc);

	       	/*
	       	links":[
				{"id":"10","source":"11","target":"12","type":"1"},
	      
	*/

			$idData=$row['id0'].'-'.$row['id1'].'-'.$row['id2'].'-'.$row['id3'].'-'.$row['id'];
	        $data[]=array('id'=>$idData, 
	            		'text'=>$row['codigo'].' |  | '.$ndesc,
	            		'start_date'=>$row['start_date'],
	            		'duration'=>$row['po_dias'],
	            		'progress'=>1,
	            		'open'=>1,
	            		'parent'=>'PA-'.$row['id3']
	            	);

	        //$links[]=array('id'=>$r, 'source'=>$idData, 'target'=>'PA-'.$row['id3'], 'type'=>1);

	        $SQLl = "SELECT * FROM constru_poGanttLink WHERE source='$idData' AND id_obra='$id_obra';";
	        $resultl = mysql_query( $SQLl ) or die("Couldn t execute query.".mysql_error());
			if(mysql_num_rows($resultl)>0){
				while($rowl = mysql_fetch_assoc($resultl)) {
					$links[]=array('id'=>$rowl['id_link'], 'source'=>$rowl['source'], 'target'=>$rowl['target'], 'type'=>$rowl['type']);
				}
			}


	        $r++;
		}
		$JSON = array('success' =>1, 
				'data'=>$data,
				'links'=>$links
				);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='tomatime'){
	$id=$_POST['id'];
	$st=$_POST['st'];
	$fn=$_POST['fn'];

	$SQL = "SELECT lun, mar, mie, jue, vie, sab from constru_tomaduria WHERE id_obra='$id_obra' AND per_ini='$st' AND per_fin='$fn' AND id_empleado='$id';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='cmbdprenom'){
	$return=array();
	$id=$_POST['id'];

	$SQL = "SELECT b.id idarea, b.nombre from constru_altas a 
inner join constru_especialidad b on b.id=a.id_area
WHERE a.id_responsable='$id' AND a.id_obra='$id_obra' AND a.borrado=0 group by b.id order by b.nombre";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='modiFac'){
	$fact=$_POST['fact'];
	$opt=$_POST['opt'];
	$id=$_POST['id'];
	if($opt=='prov'){
		$SQL = "UPDATE constru_estimaciones_bit_prov SET factura='$fact' WHERE id_obra='$id_obra' AND id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='sub'){
		$SQL = "UPDATE constru_estimaciones_bit_subcontratista SET factura='$fact' WHERE id_obra='$id_obra' AND id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='ind'){
		$SQL = "UPDATE constru_estimaciones_bit_indirectos SET factura='$fact' WHERE id_obra='$id_obra' AND id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='cmbpnom'){
	$return=array();
	$id=$_POST['id'];
		$SQL = "SELECT a.id, concat(a.fecha,' / Fecha: ',a.xxano,' / Maestro: ',b.nombre) as nomi from constru_bit_nominadest a
		join constru_info_tdo b on b.id_alta=a.id_dest
where a.id_obra='$id_obra' AND yearweek(a.fecha,1)='$id' and a.borrado=0;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}






if(isset($opcion) && $opcion=='cmbtec'){
	$return=array();
	$id=$_POST['id'];
		$SQL = " SELECT a.id, concat('NOMINA OFICINA CAMPO / ',a.fecha) as nomi from constru_bit_nominaca a
where a.id_tecnico=2 AND a.id_obra='$id_obra' AND yearweek(a.fecha,1)='$id' and a.borrado=0;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='cmbcen'){
	$return=array();
	$id=$_POST['id'];
		$SQL = " SELECT a.id, concat('NOMINA OFICINA CENTRAL / ',a.fecha) as nomi from constru_bit_nominaca a
where a.id_tecnico=1 AND a.id_obra='$id_obra' AND yearweek(a.fecha,1)='$id' and a.borrado=0;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}





if(isset($opcion) && $opcion=='cmbest'){
	$return=array();
	$opt=$_POST['opt'];
	$id=$_POST['id'];
	if($opt=='des'){
		$SQL = "SELECT id, concat('ESTDEST-',id,' - Semana: ',xxano) as estimacion FROM constru_estimaciones_bit_destajista WHERE id_obra='$id_obra' AND borrado=0 AND id_destajista='$id' ORDER BY id desc;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='sub'){
		$SQL = "SELECT id, concat('ESTSUB-',id,' - Semana: ',xxano) as estimacion FROM constru_estimaciones_bit_subcontratista WHERE id_obra='$id_obra' AND borrado=0 AND id_subcontratista='$id' ORDER BY id desc;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='ind'){
		$SQL = "SELECT id, concat('ESTIND-',id,' - ',SUBSTRING(fecha,1,10)) as estimacion FROM constru_estimaciones_bit_indirectos WHERE id_obra='$id_obra' AND borrado=0 AND xxano='$id' ORDER BY id desc;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='cc'){
		$SQL = "SELECT id, concat('ESTCC-',id,' - ',SUBSTRING(fecha,1,10)) as estimacion FROM constru_estimaciones_bit_chica WHERE id_obra='$id_obra' AND borrado=0 AND xxano='$id' ORDER BY id desc;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='pro'){
		$SQL = "SELECT id, concat('OC-',id) as oc FROM constru_pedis WHERE id_obra='$id_obra' AND borrado=0 AND id_prov='$id' AND estatus=3 AND estcomp=0 ORDER BY id desc;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='pro2'){
		$SQL = "SELECT id, concat('ESTPRO-',id,' / OC-',id_oc,' Semana: ',xxano) as oc FROM constru_estimaciones_bit_prov WHERE id_obra='$id_obra' AND borrado=0 AND id_prov='$id' AND borrado=0 ORDER BY id desc, semana;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

///chais
if(isset($opcion) && $opcion=='cmbsal'){
	$return=array();
	$opt=$_POST['opt'];
	$id=$_POST['id'];
	if($opt=='des'){
		$SQL = "SELECT a.id, a.fecha from constru_bit_salidas a WHERE a.borrado=0 AND a.id_obra= '$id_obra' and a.id_oc = '$id' order by a.id desc;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='cmbent'){
	$return=array();
	$opt=$_POST['opt'];
	$id=$_POST['id'];
	if($opt=='des'){
		$SQL = "SELECT a.id, a.fecha from constru_bit_entradas a WHERE a.borrado=0 AND a.id_obra= '$id_obra' and a.id_oc = '$id' order by a.id desc;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}
///chais
if(isset($opcion) && $opcion=='verasignados'){
	$return=array();
	$id_recurso=$_POST['id_recurso'];
	$SQL = "SELECT a.*, bc.partida, cc.especialidad, d.nombre area, e.nombre agrupador FROM constru_asignaciones a INNER JOIN constru_partida b on b.id=a.id_partida inner join constru_cat_partidas bc on bc.id=b.id_cat_partida inner join constru_area c on c.id=b.id_area inner join constru_cat_especialidad cc on cc.id=c.id_cat_especialidad INNER JOIN constru_especialidad d on d.id=c.id_especialidad inner join constru_agrupador e on e.id=d.id_agrupador WHERE a.id_recurso='$id_recurso' AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='listpartidas'){
	$SQL = "SELECT a.id,b.partida FROM constru_partida a inner join constru_cat_partidas b on b.id=a.id_cat_partida WHERE a.id_obra='$id_obra' AND a.borrado=0 ORDER BY partida;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['partida'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay areas dadas de alta';
	}

	echo $cadena;
	exit();
}

if(isset($opcion) && $opcion=='empleados'){
	$id_des=$_POST['id_des'];
	$id_edif=$_POST['edif'];
	$SQL = "SELECT a.id, concat('EMP-',a.id,' ',b.nombre,' ',b.paterno) as empleado FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id WHERE a.id_responsable='$id_des' AND a.id_especialidad='$id_edif' AND a.id_obra='$id_obra' AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['empleado'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay empleados dados de alta';
	}

	echo $cadena;
	exit();
}

if(isset($opcion) && $opcion=='eliminarec'){
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_recurso SET borrado=1 WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='saveConfig'){
	$radio=$_POST['radio'];
	$time=$_POST['time'];
	$puaut=$_POST['puaut'];
	$correo=$_POST['correo'];
	$correo_can=$_POST['correo_can'];
		$correo_aut=$_POST['correo_aut'];
	$nominad=$_POST['nominad'];
	$pres=$_POST['pres'];
		$rcorreo=$_POST['rcorreo'];
		$ocorreo=$_POST['ocorreo'];
		$lim=$_POST['lim'];
			$matriz=$_POST['matriz'];
	$SQL = "UPDATE constru_config SET autorizaciones='$radio', tiempo='$time', puaut='$puaut', correo='$correo', correo_can='$correo_can',nominadomingo='$nominad',presupuesto='$pres',limitar='$lim' ,ocorreo='$ocorreo',rcorreo='$rcorreo',matriz='$matriz',correo_aut='$correo_aut' WHERE id_obra='$id_obra';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='correoaut'){
  	$opt=$_POST['opt'];
  if($opt=="oc"){
  	$id=$_POST['id'];



$SQL2="SELECT solicito from constru_pedis WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['solicito'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 

	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Autorización de orden de compra";
            $mail->AltBody = "Xtructur";
          
          
                  $mail->MsgHTML('<b>La solicitud de orden de compra ha sido Autorizada </b> ');

            
         
            $mail->AddAddress($email, $email);

            $mail->Send();}
         
	exit();

  }
  if($opt=="req"){
  	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT solicito from constru_requis WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['solicito'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Autorizacion de requisicion";
            $mail->AltBody = "Xtructur";
          
        
                  $mail->MsgHTML('<b>La solicitud de requisicion ha sido autorizada ');

            	
         
            $mail->AddAddress($email, $email);

            $mail->Send();}
         
	exit();

  }	


}

if(isset($opcion) && $opcion=='proveedores_requis'){

	$SQL = "DELETE FROM constru_pedidos WHERE sestmp>0 AND id_obra='$id_obra' AND id_pedid=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$array=array();
	$SQL = "SELECT a.id, concat('PROVE-',a.id,' / ',b.razon_social_sp) prov FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id WHERE a.id_obra='$id_obra' AND a.id_tipo_alta=5 ORDER BY a.id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['prov'].';';
	}

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['prov'].';';
		}
	}else{
		$cadena='0:No hay proveedores dados de alta';
	}

	$cadena=trim($cadena,';');
	$array['prove']=$cadena;

	$SQL = "SELECT id, concat('REQ-',id) req FROM constru_requis WHERE id_obra='$id_obra' AND borrado=0 AND estatus=3 ORDER BY id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $cadena.=$row['id'].':'.$row['req'].';';
		}
	}else{
		$cadena='0:No hay requisiciones activas';
	}
	$cadena=trim($cadena,';');
	$array['requis']=$cadena;
	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='edit_tecnicos'){
	$return=array();
	$idagru=$_POST['idagru'];
	$SQL = "SELECT id,nombre FROM constru_especialidad WHERE id_agrupador='$idagru' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}



if(isset($opcion) && $opcion=='desc_insumos_mat'){
	$return=array();
	$id_material=$_POST['id_material'];

	if($id_material=='t'){
		$and = ' ';
	}else{
		$and = ' AND id_familia='.$id_material.' ';
	}

	$SQL = "SELECT id,concat(clave,' - ',SUBSTRING(descripcion,1,100),'...') clave FROM constru_insumos WHERE unidtext!='' AND id_obra='$id_obra' ".$and." ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	/*if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay insumos dados de alta';
	}*/

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();

}



if(isset($opcion) && $opcion=='desc_insumos'){
	$return=array();
	$id_insumo=$_POST['id_insumo'];
	$SQL = "SELECT clave,descripcion,unidtext,precio FROM constru_insumos WHERE id_obra='$id_obra' AND id='$id_insumo' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;

		    $SQL = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2;

			$SQL = "SELECT if( sum(a.cantidad) is null,0,sum(a.cantidad) ) as vol_est from constru_requisiciones a 
			Inner join constru_requis b on b.id=a.id_requi AND b.estatus!=2
			where a.id_requi>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_clave='$id_insumo' -- AND b.id_destajista='$id_des' 
			 order by a.id DESC LIMIT 1;";
			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['vol_est'];
			}else{
				$return['vol_anterior']=0;
			}

			$SQL5 = "SELECT if( sum(a.llego) is null,0, sum(a.llego)) as entrada, a.id_oc, b.entrada_alm, c.cantidad as requisicion from constru_entrada_almacen a 
			inner join constru_pedis b on b.id=a.id_oc
			inner join constru_requisiciones c on c.id_requi=a.id_req and c.id_clave=a.id_insumo and c.id_obra=a.id_obra
			where a.id_insumo='$id_insumo' and a.id_obra='$id_obra'
			group by id_oc;";
			$result5 = mysql_query( $SQL5 ) or die("Couldn t execute query.".mysql_error());
			if(mysql_num_rows($result5)>0){
				$sumacantidad=0;
				while($row5 = mysql_fetch_assoc($result5)) {
				    if($row5['entrada_alm']==1){
				    	$sumacantidad+=($row5['requisicion']-$row5['entrada']);
				    }
				}
				$return['vol_anterior']=($return['vol_anterior']-$sumacantidad);
			}


		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='desc_basicos'){
	$return=array();
	$id_basico=$_POST['id_basico'];
	$SQL = "SELECT pub_nombre,codigo,unidtext,total,precio FROM constru_bit_pubasico WHERE id_obra='$id_obra' AND borrado=0 AND id='$id_basico';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		/*
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;

		    $SQL = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2;

			$SQL = "SELECT if( sum(a.cantidad) is null,0,sum(a.cantidad) ) as vol_est from constru_requisiciones a 
			Inner join constru_requis b on b.id=a.id_requi AND b.estatus!=2
			where a.id_requi>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_clave='$id_insumo' -- AND b.id_destajista='$id_des' 
			 order by a.id DESC LIMIT 1;";
			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['vol_est'];
			}else{
				$return['vol_anterior']=0;
			}


		}
		*/
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='desc_destaj_est'){
	$return=array();
	$id_codigo=$_POST['id_codigo'];
	$id_des=$_POST['id_des'];
	$ar=$_POST['ar'];
	$limitar=$_POST['limitar'];


	if($limitar==1){
		$SQLw = "SELECT if(sum(b.volumen) is null,0,sum(b.volumen))as lalala FROM constru_bit_avancevol a 
		inner join constru_avancevol b on b.id_bit_avancevol=a.id	
		WHERE a.id_obra='$id_obra' and a.borrado=0 and b.id_clave='".$id_codigo."';";
		$resultw = mysql_query( $SQLw ) or die("Couldn t execute query.".mysql_error());
		$roww = mysql_fetch_assoc($resultw);
		$volavanceobra=$roww['lalala'];
	}


	$SQL = "SELECT a.codigo,a.descripcion,a.unidtext,a.pu_destajo as precio, b.vol_tope FROM constru_recurso a 
	left join constru_vol_tope b on b.id_clave=a.id
	WHERE b.id_area='$ar' AND b.id_obra='$id_obra' AND a.id_obra='$id_obra' AND a.id='$id_codigo' AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;

		    $SQL = "SELECT sum(unidad) as totcant FROM constru_recurso WHERE id_obra='$id_obra' AND codigo='".$row['codigo']."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2['totcant'];
			if($limitar==1){ $return['totcant']=$volavanceobra; }

			$SQL = "SELECT if( sum(a.vol_est) is null,0,sum(a.vol_est) ) as vol_est from constru_estimaciones_destajista a 
			Inner join constru_estimaciones_bit_destajista b on b.id=a.id_bit_destajista AND b.estatus!=2
			where a.id_bit_destajista>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_clave='$id_codigo' AND (a.id_area='$ar' OR b.id_area='$ar') -- AND b.id_destajista='$id_des' 
			 order by a.id DESC LIMIT 1;";
			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['vol_est'];
			}else{
				$return['vol_anterior']=0;
			}


		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='desc_cliente_est'){
	$return=array();
	$id_codigo=$_POST['id_codigo'];
	$ar=$_POST['ar'];
	$limitar=$_POST['limitar'];


	if($limitar==1){
		$SQLw = "SELECT if(sum(b.volumen) is null,0,sum(b.volumen))as lalala FROM constru_bit_avancevol a 
		inner join constru_avancevol b on b.id_bit_avancevol=a.id	
		WHERE a.id_obra='$id_obra' and a.borrado=0 and b.id_clave='".$id_codigo."';";
		$resultw = mysql_query( $SQLw ) or die("Couldn t execute query.".mysql_error());
		$roww = mysql_fetch_assoc($resultw);
		$volavanceobra=$roww['lalala'];
	}

	$SQL = "SELECT a.codigo,a.descripcion,a.unidtext,a.precio_costo as precio FROM constru_recurso a 
	WHERE a.id_obra='$id_obra' AND a.id='$id_codigo' AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;

		    $SQL = "SELECT sum(unidad) as totcant FROM constru_recurso WHERE id_obra='$id_obra' AND codigo='".$row['codigo']."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2['totcant'];
			if($limitar==1){ $return['totcant']=$volavanceobra; }

			/*$SQL = "SELECT a.id, a.vol_estimacion from constru_estimaciones_subcontratista a Inner join constru_estimaciones_bit_subcontratista b on b.id=a.id_bit_subcontratista where a.id_bit_subcontratista>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_insumo='$id_codigo' AND b.id_subcontratista='$id_des' order by a.id DESC LIMIT 1;";*/

			$SQL = "SELECT if( sum(a.vol_estimacion) is null,0,sum(a.vol_estimacion) ) as vol_estimacion from constru_estimaciones_cliente a Inner join constru_estimaciones_bit_cliente b on b.id=a.id_bit_cliente  AND b.estatus!=2 where a.id_bit_cliente>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_insumo='$id_codigo' AND b.id_cliente='$id_obra' order by a.id DESC LIMIT 1;";

			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['vol_estimacion'];
			}else{
				$return['vol_anterior']=0;
			}


		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='desc_avance'){
	$return=array();
	$id_codigo=$_POST['id_codigo'];
	$ar=$_POST['ar'];
	$SQL = "SELECT a.codigo,a.descripcion,a.unidtext,a.precio_costo as precio FROM constru_recurso a 
	WHERE a.id_obra='$id_obra' AND a.id='$id_codigo' AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;

		    $SQL = "SELECT sum(unidad) as totcant FROM constru_recurso WHERE id_obra='$id_obra' AND codigo='".$row['codigo']."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2['totcant'];

			/*$SQL = "SELECT a.id, a.vol_estimacion from constru_estimaciones_subcontratista a Inner join constru_estimaciones_bit_subcontratista b on b.id=a.id_bit_subcontratista where a.id_bit_subcontratista>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_insumo='$id_codigo' AND b.id_subcontratista='$id_des' order by a.id DESC LIMIT 1;";*/

			$SQL = "SELECT if(sum(b.volumen) is null,0,sum(b.volumen)) as totales FROM constru_bit_avancevol a 
		inner join constru_avancevol b on b.id_bit_avancevol=a.id	
		WHERE a.id_obra='$id_obra' and a.borrado=0 and b.id_clave='$id_codigo';";

			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['totales'];
			}else{
				$return['vol_anterior']=0;
			}


		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='desc_subcontratista_est'){


	$return=array();
	$id_codigo=$_POST['id_codigo'];
	$id_des=$_POST['id_des'];
	$ar=$_POST['ar'];
	$limitar=$_POST['limitar'];
	$id_partida=$_POST['id_partida'];


	if($limitar==1){
		$SQLw = "SELECT if(sum(b.volumen) is null,0,sum(b.volumen))as lalala FROM constru_bit_avancevol a 
		inner join constru_avancevol b on b.id_bit_avancevol=a.id	
		WHERE a.id_obra='$id_obra' and a.borrado=0 and b.id_clave='".$id_codigo."';";
		$resultw = mysql_query( $SQLw ) or die("Couldn t execute query.".mysql_error());
		$roww = mysql_fetch_assoc($resultw);
		$volavanceobra=$roww['lalala'];
	}
	
	$SQL = "SELECT a.codigo,a.descripcion,a.unidtext,a.pu_subcontrato as precio, b.vol_tope FROM constru_recurso a 
	left join constru_vol_tope b on b.id_clave=a.id
	WHERE b.id_area='$ar' AND b.id_partida='$id_partida' AND b.id_obra='$id_obra' AND a.id_obra='$id_obra' AND a.id='$id_codigo' AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		    $SQL = "SELECT sum(unidad) as totcant FROM constru_recurso WHERE id_obra='$id_obra' AND codigo='".$row['codigo']."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2['totcant'];
			if($limitar==1){ $return['totcant']=$volavanceobra; }

			/*$SQL = "SELECT a.id, a.vol_estimacion from constru_estimaciones_subcontratista a Inner join constru_estimaciones_bit_subcontratista b on b.id=a.id_bit_subcontratista where a.id_bit_subcontratista>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_insumo='$id_codigo' AND b.id_subcontratista='$id_des' order by a.id DESC LIMIT 1;";*/

			

			$SQL = "SELECT if( sum(a.vol_estimacion) is null,0,sum(a.vol_estimacion) ) as vol_estimacion from constru_estimaciones_subcontratista a 
			Inner join constru_estimaciones_bit_subcontratista b on b.id=a.id_bit_subcontratista AND b.estatus!=2 
			where a.id_bit_subcontratista>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_insumo='$id_codigo'
			AND (a.id_area='$ar' OR b.id_area='$ar') -- AND b.id_subcontratista='$id_des' 
			order by a.id DESC LIMIT 1;";

			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['vol_estimacion'];
			}else{
				$return['vol_anterior']=0;
			}


		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='desc_insumos_est'){
	$return=array();
	$id_insumo=$_POST['id_insumo'];
	$id_des=$_POST['id_des'];
	$SQL = "SELECT clave,descripcion,unidtext,precio FROM constru_insumos WHERE id_obra='$id_obra' AND id='$id_insumo' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;

		    $SQL = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
			$result2 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row2 = mysql_fetch_assoc($result2);
			$return['totcant']=$row2['totcant'];

			$SQL = "SELECT a.id, a.vol_estimacion from constru_estimaciones_destajista a Inner join constru_estimaciones_bit_destajista b on b.id=a.id_bit_destajista AND b.estatus!=2 where a.id_bit_destajista>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_insumo='$id_insumo' AND b.id_destajista='$id_des' order by a.id DESC LIMIT 1;";
			$result3 = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row3 = mysql_fetch_assoc($result3);
			if(mysql_num_rows($result3)>0){
				$return['vol_anterior']=$row3['vol_estimacion'];
			}else{
				$return['vol_anterior']=0;
			}


		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='desc_recursos'){
	$return=array();
	$id_recurso=$_POST['id_recurso'];
	$SQL = "SELECT descripcion,unidtext,precio_costo FROM constru_recurso WHERE id='$id_recurso' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='eliminapart'){
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_partida SET borrado=1 WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='eliminaarea'){
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_area SET borrado=1 WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='eliminaesp'){
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_especialidad SET borrado=1 WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='eliminaagru'){
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_agrupador SET borrado=1 WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='eliminapre'){
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_presupuesto SET borrado=1 WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='eliminaproy'){
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_proyecto_presupuesto SET borrado=1 WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='savemat'){
	$idm = $_POST['idm'];
	$ids=implode(",",$_POST['ids']);
	$SQL = "UPDATE constru_insumos SET id_familia='$idm' WHERE id IN (".$ids.");";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='savepu'){
	$pre=$_POST['pre'];
	$es=$_POST['es'];
	$ids=implode(', ',$_POST['ids']);
	if($es==1){
		mysql_query("UPDATE constru_recurso SET pu_destajo='$pre' WHERE id in (".$ids.");");
	}
	if($es==2){
		mysql_query("UPDATE constru_recurso SET pu_subcontrato='$pre' WHERE id in (".$ids.");");
	}
	exit();
}

if(isset($opcion) && $opcion=='asignpu'){
	$es=$_POST['es'];
	$ids=implode(', ',$_POST['ids']);

	if($config_puaut==1){
		if($es==1){
			mysql_query("UPDATE constru_recurso SET esdestajo=1, essubcontrato=0, pu_destajo=precio_venta WHERE id in (".$ids.");");
		}
		if($es==2){
			mysql_query("UPDATE constru_recurso SET esdestajo=0, essubcontrato=1, pu_subcontrato=precio_venta WHERE id in (".$ids.");");
		}
		if($es==3){
			mysql_query("UPDATE constru_recurso SET esdestajo=1, essubcontrato=1, pu_destajo=precio_venta, pu_subcontrato=precio_venta WHERE id in (".$ids.");");
		}
		if($es==4){
			mysql_query("UPDATE constru_recurso SET esdestajo=0, essubcontrato=0, pu_destajo=0, pu_subcontrato=0 WHERE id in (".$ids.");");
		}
	}else{
		if($es==1){
			mysql_query("UPDATE constru_recurso SET esdestajo=1, essubcontrato=0 WHERE id in (".$ids.");");
		}
		if($es==2){
			mysql_query("UPDATE constru_recurso SET esdestajo=0, essubcontrato=1 WHERE id in (".$ids.");");
		}
		if($es==3){
			mysql_query("UPDATE constru_recurso SET esdestajo=1, essubcontrato=1 WHERE id in (".$ids.");");
		}
		if($es==4){
			mysql_query("UPDATE constru_recurso SET esdestajo=0, essubcontrato=0 WHERE id in (".$ids.");");
		}
	}
	exit();
}

if(isset($opcion) && $opcion=='aspart'){
	$idp=$_POST['idp'];
	$ida=$_POST['ida'];
	$ids=implode(', ',$_POST['ids']);

	/*$SQL = "SELECT a.id, bc.partida, d.id idarea
	FROM constru_asignaciones a 
	INNER JOIN constru_partida b on b.id=a.id_partida 
	inner join constru_cat_partidas bc on bc.id=b.id_cat_partida 
	inner join constru_area c on c.id=b.id_area 
	inner join constru_cat_especialidad cc on cc.id=c.id_cat_especialidad 
	INNER JOIN constru_especialidad d on d.id=c.id_especialidad 
	inner join constru_agrupador e on e.id=d.id_agrupador WHERE a.id_recurso in (".$ids.") AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		echo 'Error:22';
	}else{
		$cad='';
		foreach ($_POST['ids'] as $k => $v) {
			$cad.="('".$id_obra."','".$v."','".$idp."'),";
		}
		$cad = substr($cad, 0, -1);
		echo 'nope';
		//$SQL = "INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida) VALUES ".$cad."; ";
		//mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}*/
	$cad='';
	$cadtope='';
	foreach ($_POST['ids'] as $k => $v) {
		$cad.="('".$id_obra."','".$v."','".$idp."','".$ida."'),";
		$cadtope.="('".$id_obra."','".$v."','".$ida."','".$idp."',(SELECT unidad FROM constru_recurso WHERE id=".$v."),'0'),";
	}
	$cad = substr($cad, 0, -1);
	$cadtope = substr($cadtope, 0, -1);
	$SQL = "INSERT INTO constru_asignaciones (id_obra,id_recurso,id_partida,id_area) VALUES ".$cad."; ";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,id_partida,vol_tope,borrado) VALUES ".$cadtope."; ";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='agrups'){


	$SQL = "SELECT id,nombre FROM constru_agrupador WHERE id_obra='$id_obra' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$array[]=$row;
		}
		$JSON = array('success' =>1, 
			'datos'=>$array);
	}else{
		$JSON = array('success' =>0, 
			'datos'=>'');
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='cmbcc'){
	$cmbcc=$_POST['cmbcc'];
	$SQL = "SELECT id,costo FROM constru_cuentas_costo WHERE id_cc='$cmbcc' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$array[]=$row;
		}
		$JSON = array('success' =>1, 
			'datos'=>$array);
	}else{
		$JSON = array('success' =>0, 
			'datos'=>'');
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='chcosto'){
	$chcosto=$_POST['chcosto'];
	$SQL = "SELECT id,cargo FROM constru_cuentas_cargo WHERE id_costo='$chcosto' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$array[]=$row;
		}
		$JSON = array('success' =>1, 
			'datos'=>$array);
	}else{
		$JSON = array('success' =>0, 
			'datos'=>'');
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='chagru'){
	$idagru=$_POST['idagru'];
	$SQL = "SELECT id,nombre FROM constru_especialidad WHERE id_agrupador='$idagru' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$array[]=$row;
		}
		$JSON = array('success' =>1, 
			'datos'=>$array);
	}else{
		$JSON = array('success' =>0, 
			'datos'=>'');
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='chesp_mod'){
	$idesp=$_POST['idesp'];
	$ids=$_POST['ids'];
	$ids = implode(', ', $ids);
	$SQL = "SELECT id FROM constru_asignaciones a WHERE a.id_obra='$id_obra' AND a.id_recurso in($ids) AND id_area='$idesp' limit 1;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$JSON = array('success' =>2, 
			'datos'=>'');
		echo json_encode($JSON);
		exit();
	}

	
		$SQL = "SELECT a.id,b.especialidad as nombre FROM constru_area a inner join constru_cat_especialidad b on b.id=a.id_cat_especialidad WHERE a.id_especialidad='$idesp' AND a.borrado=0;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		if(mysql_num_rows($result)>0){
			while($row = mysql_fetch_assoc($result)) {
		    	$array[]=$row;
			}
			$JSON = array('success' =>1, 
				'datos'=>$array);
		}else{
			$JSON = array('success' =>0, 
				'datos'=>'');
		}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='chesp'){
	$idesp=$_POST['idesp'];
	$ids=$_POST['ids'];

		$SQL = "SELECT a.id,b.especialidad as nombre FROM constru_area a inner join constru_cat_especialidad b on b.id=a.id_cat_especialidad WHERE a.id_especialidad='$idesp' AND a.borrado=0;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		if(mysql_num_rows($result)>0){
			while($row = mysql_fetch_assoc($result)) {
		    	$array[]=$row;
			}
			$JSON = array('success' =>1, 
				'datos'=>$array);
		}else{
			$JSON = array('success' =>0, 
				'datos'=>'');
		}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='charea'){
	$idarea=$_POST['idarea'];
	$SQL = "SELECT a.id,b.partida as nombre FROM constru_partida a inner join constru_cat_partidas b on b.id=a.id_cat_partida WHERE a.id_area='$idarea' AND a.borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$array[]=$row;
		}
		$JSON = array('success' =>1, 
			'datos'=>$array);
	}else{
		$JSON = array('success' =>0, 
			'datos'=>'');
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='presu'){
	$idarea=$_POST['idarea'];
	$SQL = "SELECT id,nombre FROM constru_presupuesto;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['nombre'].';';
	}
	$cadena=trim($cadena,';');
	echo $cadena;
	exit();
}



if(isset($opcion) && $opcion=='parts'){
	$SQL = "SELECT * FROM constru_partida ORDER by nombre desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['nombre'].';';
	}
	$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='areas'){
	$SQL = "SELECT * FROM constru_especialidad ORDER by nombre desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['nombre'].';';
	}
	$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='areas_dinamic_combo'){
	$id_agrupador=$_POST['id_agrupador'];
	$SQL = "SELECT * FROM constru_especialidad where id_agrupador='$id_agrupador' AND borrado=0 ORDER by nombre desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.='<option role="option" value="'.$row['id'].'">'.$row['nombre'].'</option>';
	}
	//$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='especialidad_dinamic_combo'){
	$id_area=$_POST['id_area'];
	$SQL = "SELECT a.id,b.especialidad as nombre FROM constru_area a inner join constru_cat_especialidad b on b.id=a.id_cat_especialidad WHERE a.id_especialidad='$id_area' AND a.borrado=0;";

	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.='<option role="option" value="'.$row['id'].'">'.$row['nombre'].'</option>';
	}
	//$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='especialidad_dinamic_combo_cliente'){
	$id_area=$_POST['id_area'];
	$SQL = "SELECT a.*, b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave from constru_asignaciones a 
inner join constru_recurso b on b.id=a.id_recurso
where a.id_area='$id_area' and a.id_obra='$id_obra' and a.borrado=0;";

	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.='<option role="option" value="'.$row['id'].'">'.$row['clave'].'</option>';
	}
	//$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='partida_dinamic_combo'){
	$id_especialidad=$_POST['id_especialidad'];
	$SQL = "SELECT a.id,b.partida as nombre FROM constru_partida a inner join constru_cat_partidas b on b.id=a.id_cat_partida WHERE a.id_area='$id_especialidad' AND a.borrado=0;";
	
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.='<option role="option" value="'.$row['id'].'">'.$row['nombre'].'</option>';
	}
	//$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='claves_dinamic_combo'){
	$ar=$_POST['ar'];
	$pa=$_POST['pa'];
	$SQL = "SELECT b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave FROM constru_asignaciones a 
	LEFT JOIN constru_recurso b on b.id=a.id_recurso
	INNER JOIN constru_vol_tope c on c.id_clave=b.id AND c.id_area=a.id_area  AND c.id_partida=a.id_partida
	WHERE a.id_area='$ar' AND a.id_partida='$pa' AND b.pu_subcontrato>0 AND b.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;";
	
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.='<option role="option" value="'.$row['id'].'">'.$row['clave'].'</option>';
	}
	//$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='claves_dinamic_combo_d'){
	$ar=$_POST['ar'];
	$pa=$_POST['pa'];
	$SQL = "SELECT b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave FROM constru_asignaciones a 
	LEFT JOIN constru_recurso b on b.id=a.id_recurso
	INNER JOIN constru_vol_tope c on c.id_clave=b.id AND c.id_area=a.id_area
	WHERE a.id_area='$ar' AND a.id_partida='$pa' AND b.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;";
	
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.='<option role="option" value="'.$row['id'].'">'.$row['clave'].'</option>';
	}
	//$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='claves_dinamic_combo_dall'){
	$ar=$_POST['ar'];
	$pa=$_POST['pa'];
	$SQL = "SELECT b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave FROM constru_asignaciones a 
	LEFT JOIN constru_recurso b on b.id=a.id_recurso
	INNER JOIN constru_vol_tope c on c.id_clave=b.id AND c.id_area=a.id_area
	WHERE a.id_area='$ar' AND a.id_partida='$pa' AND b.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;";
	
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.='<option role="option" value="'.$row['id'].'">'.$row['clave'].'</option>';
	}
	//$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='fams'){
	$SQL = "SELECT id,especialidad FROM constru_familias ORDER BY especialidad;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['especialidad'].';';
	}
	$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}



if(isset($opcion) && $opcion=='familias'){
	$tecnicos=array();
	$SQL = "SELECT id,if(id_categoria_familia=1,concat('Tecnicos - ',familia),concat('Obreros - ',familia) ) as familia FROM constru_familias ORDER BY familia;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['familia'].';';
	}
	$cadena=trim($cadena,';');
	$tecnicos['familias']=$cadena;
	echo json_encode($tecnicos);
	exit();
}

if(isset($opcion) && $opcion=='familiaso'){
	$tecnicos=array();
	$SQL = "SELECT id,if(id_categoria_familia=1,concat('Tecnicos - ',familia),concat('Obreros - ',familia) ) as familia FROM constru_familias
	WHERE id_obra='$id_obra' AND id_categoria_familia=2 ORDER BY familia;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['familia'].';';
	}
	$cadena=trim($cadena,';');
	$tecnicos['familias']=$cadena;
	echo json_encode($tecnicos);
	exit();
}

if(isset($opcion) && $opcion=='familiast'){
	$tecnicos=array();
	$SQL = "SELECT id,if(id_categoria_familia=1,concat('Tecnicos - ',familia),concat('Obreros - ',familia) ) as familia FROM constru_familias
	WHERE id_obra='$id_obra' AND id_categoria_familia=1 ORDER BY familia;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['familia'].';';
	}
	$cadena=trim($cadena,';');
	$tecnicos['familias']=$cadena;
	echo json_encode($tecnicos);
	exit();
}

if(isset($opcion) && $opcion=='depto'){
	$SQL = "SELECT id,departamento FROM constru_deptos ORDER BY departamento;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['departamento'].';';
	}
	$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

  if(isset($opcion) && $opcion=='saverem'){
  	
  	$sema=$_POST['sema'];
	$monto=$_POST['monto'];
	$fecha=date('Y-m-d H:i:s');

	$SQL = "SELECT id FROM constru_bit_remesa WHERE semana='$sema' AND id_obra='$id_obra' AND estatus>0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		echo 'REP';
		exit();
	}else{
		$SQL = "INSERT INTO constru_bit_remesa (id_obra,fecha,semana,remesa,estatus) VALUES ('$id_obra','$fecha','$sema','$monto',0);";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		echo mysql_insert_id();
		exit();
	}
	

  }

    if(isset($opcion) && $opcion=='checaFactsPedis'){
  	$idoc=$_POST['idoc'];
  	$idest=$_POST['idest'];
  	$sql = "SELECT * FROM constru_xml_pedis WHERE id_obra='$id_obra' AND id_pedi='$idoc' AND id_estimacion='$idest' AND borrado=0 ";

  	$result = mysql_query($sql) or die("Couldn t execute query.".mysql_error());

  	$arr=array();
  	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $arr[]=$row;
		}
		$JSON=array('success'=>1, 'data'=>$arr);
	}else{
		$JSON=array('success'=>0);
	}

	echo json_encode($JSON);
  }

  if(isset($opcion) && $opcion=='checaFactsPedisinfo'){
  	$idoc=$_POST['idoc'];
  	$idest=$_POST['idest'];

  	if($idoc>0){
	  	$sql = "SELECT * FROM constru_xml_pedis WHERE id_obra='$id_obra' AND id_pedi='$idoc' AND borrado=0 AND dedonde='bit_prov' ";
		$result = mysql_query($sql) or die("Couldn t execute query.".mysql_error());
	}

	if($idoc==0){
	  	$sql = "SELECT * FROM constru_xml_pedis WHERE id_obra='$id_obra' AND id_estimacion='$idest' AND borrado=0 AND dedonde='bit_subc' ";
		$result = mysql_query($sql) or die("Couldn t execute query.".mysql_error());
	}

  	$arr=array();
  	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $arr[]=$row;
		}
		$JSON=array('success'=>1, 'data'=>$arr);
	}else{
		$JSON=array('success'=>0);
	}

	echo json_encode($JSON);
  }

  
  if(isset($opcion) && $opcion=='save_addconcepto'){
  	$idRequi=$_POST['idRequi'];
	$clavematerial=$_POST['clavematerial'];
	$canti=$_POST['canti'];
	$sestmp=time();
	$fecha_entrega=date('Y-m-d');
	$SQL = "SELECT count(*) as tot FROM constru_requisiciones WHERE id_obra='$id_obra' AND id_clave='$clavematerial' AND id_requi='$idRequi';";

	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$row = mysql_fetch_assoc($result);

	if(($row['tot']*1)>0){
		echo 'rp';
		exit();
	}
	
	$SQL = "INSERT INTO constru_requisiciones (id_obra,id_clave,id_requi,cantidad,fecha_entrega,sestmp) VALUES ('$id_obra', '$clavematerial','$idRequi', '$canti', '$fecha_entrega','$sestmp');";
	echo $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
  }

if(isset($opcion) && $opcion=='save_pubasico'){
	$return=array();
	$ig_nombre=$_POST['ig_nombre'];
	$ig_codigo=$_POST['ig_codigo'];
	$ig_unidad=$_POST['ig_unidad'];
	$ig_precio=$_POST['ig_precio'];
	$ig_preparacion=$_POST['ig_preparacion'];
	$ttotal=$_POST['ttotal'];
	$entrada=$_POST['entrada'];
	$o=explode(',', $entrada);
	$fecha=date('Y-m-d H:i:s');

	$SQL = "SELECT id FROM constru_bit_pubasico WHERE codigo='$ig_codigo' AND id_obra='$id_obra' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		echo 'REP';
		exit();
	}else{
		$SQL = "INSERT INTO constru_bit_pubasico (id_obra,pub_nombre,codigo,unidtext,total,precio,fecha_creacion,preparacion,borrado) VALUES ('$id_obra','$ig_nombre','$ig_codigo','$ig_unidad','$ttotal','$ig_precio','$fecha','$ig_preparacion',0);";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$idpubasico = mysql_insert_id();

	}
	foreach ($o as $key => $value) {
			$v=explode('=', $value);
			$v0=$v[0];//idinsumo
			$v1=$v[1];//cantidad
			$v2=$v[2];//basico

			if($v2==0){
				$SQL="INSERT INTO constru_pubasico (id_obra, id_insumo, id_bit_pubasico,cantidad) 
				VALUES ('$id_obra', '$v0', '$idpubasico', '$v1');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}
			if($v2==1){
				$SQL="INSERT INTO constru_pubasico (id_obra, id_pubasico, id_bit_pubasico,cantidad) 
				VALUES ('$id_obra', '$v0', '$idpubasico', '$v1');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}
	}
	echo 1;
	exit();
}

if(isset($opcion) && $opcion=='save_puconcepto'){
	$return=array();
	$ig_nombre=$_POST['ig_nombre'];
	$ig_codigo=$_POST['ig_codigo'];
	$ig_unidad=$_POST['ig_unidad'];
	$ig_precio=$_POST['ig_precio'];
	$ig_preparacion=$_POST['ig_preparacion'];
	$ttotal=$_POST['ttotal'];
	$entrada=$_POST['entrada'];
	$cha5=$_POST['cha5'];
	$o=explode(',', $entrada);
	$fecha=date('Y-m-d H:i:s');

	$SQL = "SELECT id FROM constru_bit_puconcepto WHERE id_concepto='$cha5' AND id_obra='$id_obra' AND borrado=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		echo 'REP';
		exit();
	}else{
		$SQL = "INSERT INTO constru_bit_puconcepto (id_obra,id_concepto,fecha_creacion,preparacion,borrado) VALUES ('$id_obra','$cha5','$fecha','$ig_preparacion',0);";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$idpubasico = mysql_insert_id();

	}
	foreach ($o as $key => $value) {
			$v=explode('=', $value);
			$v0=$v[0];//idinsumo
			$v1=$v[1];//cantidad
			$v2=$v[2];//basico

			if($v2==0){
				$SQL="INSERT INTO constru_puconcepto (id_obra, id_insumo, id_bit_puconcepto,cantidad) 
				VALUES ('$id_obra', '$v0', '$idpubasico', '$v1');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}
			if($v2==1){
				$SQL="INSERT INTO constru_puconcepto (id_obra, id_pubasico, id_bit_puconcepto,cantidad) 
				VALUES ('$id_obra', '$v0', '$idpubasico', '$v1');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}
	}
	echo 1;
	exit();
}

  
 if(isset($opcion) && $opcion=='save_cobro'){

	$return=array();
	$xnx=$_POST['xnx'];


	$iduserlog=$_POST['iduserlog'];

	$tp=$_POST['tp'];
	$ra=$_POST['ra'];

	//$idrem=$_POST['idrem'];

	$solicito=$_POST['solicito'];




	$sestmp=time();
	$o=explode(',', $xnx);
//id_ps,id_esti,imp_sem  
	$existe=0;


	$sema=$_POST['sema'];
	$monto=$_POST['monto'];

	$ra=$monto;
	$fecha=date('Y-m-d H:i:s');

	$SQL = "SELECT id FROM constru_bit_cobros WHERE id_obra='$id_obra' AND semana='$sema';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){

		$lala = mysql_fetch_assoc($result);
		$id_bit_rem=$lala['id'];

		$SQL="UPDATE constru_bit_cobros SET id_solicito='$iduserlog', tot_pasiv='$tp', cob_aut='$ra' WHERE id='$id_bit_rem' ;";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				

	}else{

		 $SQL="INSERT INTO constru_bit_cobros (id_obra, tot_pasiv, cob_aut, semana, id_solicito,fecha)  VALUES ('$id_obra', '$tp', '$ra', '$sema', '$iduserlog','$fecha');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				$id_bit_rem = mysql_insert_id();

				$SQL="UPDATE constru_bit_remesa SET estatus=1 WHERE id='$idrem'";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	}
	foreach ($o as $key => $value) {
			$v=explode('=', $value);
			$v0=$v[0];
			$v1=$v[1];
			$v2=$v[2];
			$v3=$v[3];
			$v4=$v[4];

			$SQL = "SELECT id FROM constru_cobros WHERE id_obra='$id_obra'  AND id_esti='$v1' AND semana='$sema' AND id_bit_cobros='$id_bit_rem' AND proviene='$v3';";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

			if(mysql_num_rows($result)>0){
				$zzz = mysql_fetch_assoc($result);
				$id_remesa=$zzz['id'];
				$SQL="UPDATE constru_cobros SET imp_sem='$v2',fp='$v4' WHERE id_obra='$id_obra'  AND id_esti='$v1' AND proviene='$v3' AND id_bit_cobros='$id_bit_rem';";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}else{ 
				
				$SQL="INSERT INTO constru_cobros (id_obra,id_esti, imp_sem,semana,id_bit_cobros,proviene,fp) 
				VALUES ('$id_obra',  '$v1' ,'$v2','$sema','$id_bit_rem','$v3','$v4');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				
			}

	}

		if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');

    $SQL="UPDATE constru_cobros SET idaut='$id_username_global',fechaaut='$fecha' WHERE id_bit_cobros ='$id_bit_rem';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		$SQL="UPDATE constru_bit_cobros SET estatus=1 WHERE id ='$idrem';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		
	}

		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');


		$SQL="UPDATE constru_bit_cobros SET estatus=1 WHERE id ='$id_bit_rem';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		

	echo 1;
	exit();

}




  if(isset($opcion) && $opcion=='save_remesa'){

	$return=array();
	$xnx=$_POST['xnx'];

	$iduserlog=$_POST['iduserlog'];

	$tp=$_POST['tp'];
	$ra=$_POST['ra'];

	//$idrem=$_POST['idrem'];

	$solicito=$_POST['solicito'];




	$sestmp=time();
	$o=explode(',', $xnx);
	
//id_ps,id_esti,imp_sem  
	$existe=0;


	$sema=$_POST['sema'];
	$monto=$_POST['monto'];

	$ra=$monto;
	$fecha=date('Y-m-d H:i:s');

	/*$SQL = "SELECT id FROM constru_bit_remesa WHERE semana='$sema' AND id_obra='$id_obra' AND estatus>0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		echo 'REP';
		exit();
	}else{*/
		$SQL = "INSERT INTO constru_bit_remesa (id_obra,fecha,semana,remesa,estatus) VALUES ('$id_obra','$fecha','$sema','$monto',0);";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$idrem = mysql_insert_id();
		//exit();
	


	$SQL = "SELECT id FROM constru_bit_remesas WHERE id_obra='$id_obra' AND semana='$sema' AND id_bit_remesa='$idrem';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){

		$lala = mysql_fetch_assoc($result);
		$id_bit_rem=$lala['id'];

		$SQL="UPDATE constru_bit_remesas SET id_solicito='$iduserlog', tot_pasiv='$tp', rem_aut='$ra' WHERE id='$id_bit_rem' ;";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				

	}else{
		 $SQL="INSERT INTO constru_bit_remesas (id_obra, tot_pasiv, rem_aut, semana, id_solicito, id_bit_remesa,fecha)  VALUES ('$id_obra', '$tp', '$ra', '$sema', '$iduserlog', '$idrem','$fecha');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				$id_bit_rem = mysql_insert_id();

				$SQL="UPDATE constru_bit_remesa SET estatus=1 WHERE id='$idrem'";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	}
	foreach ($o as $key => $value) {
			$v=explode('=', $value);
			$v0=$v[0];
			$v1=$v[1];
			$v2=$v[2];
			$v3=$v[3];
			$v4=$v[4];

			$v5=$v[5];


			switch($v3){
case 'ESTIND':
$SQL="UPDATE constru_estimaciones_bit_indirectos set factura='$v5' where id='$v1'";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
break;
case 'ESTSUB':
$SQL="UPDATE constru_estimaciones_bit_subcontratista set factura='$v5' where id='$v1'";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
break;
case 'ESTPROV':
$SQL="UPDATE constru_estimaciones_bit_prov set factura='$v5' where id='$v1'";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
break;




			}

			$SQL = "SELECT id FROM constru_remesas WHERE id_obra='$id_obra' AND id_prov='$v0' AND id_esti='$v1' AND semana='$sema' AND id_bit_remesas='$id_bit_rem' AND proviene='$v3';";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

			if(mysql_num_rows($result)>0){
				$zzz = mysql_fetch_assoc($result);
				$id_remesa=$zzz['id'];
				$SQL="UPDATE constru_remesas SET imp_sem='$v2', fp='$v4' WHERE id_obra='$id_obra' AND id_prov='$v0' AND id_esti='$v1' AND proviene='$v3' AND id_bit_remesas='$id_bit_rem';";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}else{
				
				$SQL="INSERT INTO constru_remesas (id_obra, id_prov, id_esti,imp_sem,semana,id_bit_remesas,proviene,fp) 
				VALUES ('$id_obra', '$v0', '$v1', '$v2','$sema','$id_bit_rem','$v3','$v4');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				
			}

	}

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');

    $SQL="UPDATE constru_bit_remesas SET idaut='$id_username_global',fechaaut='$fecha' WHERE id_bit_remesa ='$idrem';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		$SQL="UPDATE constru_bit_remesa SET estatus=2 WHERE id ='$idrem';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		$SQL = "INSERT INTO constru_cheques (id_obra,id_remesa) VALUES ( '$id_obra','$idrem') ;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo 1;
	exit();

}

if(isset($opcion) && $opcion=='save_desglo'){
	$impdesglo=$_POST['impdesglo'];
	$o=explode(',', $_POST['xnx']);
	foreach ($o as $key1 => $oentrada) {
		$v=explode('=', $oentrada);
		$importe=($v[1]/100)*$impdesglo;
		$SQL="UPDATE constru_desgloce SET por='".$v[1]."', importe='".$importe."' WHERE id='".$v[0]."';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
}

if(isset($opcion) && $opcion=='save_pedi'){

	$return=array();
	$id=$_POST['id'];
	$solicito=$_POST['solicito'];
	$pro=$_POST['pro'];
	$fecha_entrega=$_POST['fecente'];
	$obsgen=$_POST['obsgen'];
	$fpago=$_POST['fpago'];
	$condpago=$_POST['condpago'];
	$iduserlog=$_POST['iduserlog'];

	$fecha=date('Y-m-d H:i:s');
	$sestmp=time();

	$xxano = xxano($fff);
$o=explode('/', $_POST['lalala']);




	$arraypro=array();
	foreach ($o as $key1 => $oentrada) {
		$entrada=explode(',',$oentrada);
		foreach ($entrada as $key => $value) {

			$q=explode('>', $value);
			$v=explode('=', $q[1]);

			$idpro=$v[4];
			if (array_key_exists($idpro, $arraypro)) {

			    $arraypro[$v[4]][]=array('q0'=>$q[0], 'v0'=>$v[0], 'v1'=>$v[1], 'v2'=>$v[2], 'v3'=>$v[3], 'v5'=>$v[5]);
			}else{

				$arraypro[$v[4]][]=array('q0'=>$q[0], 'v0'=>$v[0], 'v1'=>$v[1], 'v2'=>$v[2], 'v3'=>$v[3], 'v5'=>$v[5]);

			}
			

		}
	}





foreach ($arraypro as $elprov => $valor) {
	$mp = $arraypro[$elprov][0]['v5'];
	$SQL="INSERT INTO constru_pedis (id_obra,nombre,solicito,estatus,fecha_entrega,id_prov,xxano,fpago,obsgen,condpago,fecha_captura) VALUES ('$id_obra',' ','$iduserlog',1,'$fecha_entrega','$elprov','$xxano','$mp','$obsgen','$condpago','$fecha');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();

	foreach ($arraypro[$elprov] as $k => $v) {
		$SQL = "SELECT id FROM constru_pedidos WHERE id_obra='$id_obra' AND id_pedid='$last_id' AND id_requis='".$v['q0']."';";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			if(mysql_num_rows($result)>0){
				
			}else{
				$SQL="INSERT INTO constru_pedidos (id_obra, id_pedid, id_requis,fecha_captura,sestmp) VALUES ('$id_obra', '$last_id', '".$v['q0']."', NOW(),'$sestmp');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}

			$SQL="UPDATE constru_requisiciones SET id_pedido='".$last_id."', precio_compra='".$v['v2']."', cantidad='".$v['v3']."', elprov='".$elprov."' WHERE id_obra='$id_obra' AND id_requi='".$v['v0']."' AND id_clave='".$v['v1']."' ;";
			mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

			$SQL = "UPDATE constru_requis SET estatus=4 WHERE id in (
 			select id_requis from constru_pedis a left join constru_pedidos b on b.id_pedid=a.id WHERE a.id='$last_id');";
			mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}


  $SQL = "SELECT ocorreo from constru_config where id_obra='$id_obra';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$row = mysql_fetch_array($result); 

    $email=$row['ocorreo'];

    $mmss='Se ha creado la <b>orden de compra '.$last_id.'</b> <br><br>';


		$head="<br><table border=1>
<tbody>
<tr>
<th align='left'>Obra</th>
<th align='left'>Orden</th>
<th align='left'>Area</th>
<th align='left'>Partida</th>
<th align='left'>Solicita</th>
<th align='left'>Descripcion</th>
<th align='left'>U.M.</th>
<th align='left'>Cantidad</th>
<th align='right'>P.U. Compra</th>
<th align='right'>Importe de Compra</th>
</tr>";


$SQL2="SELECT a.id,h.obra,i.nombre as solicito,f.nombre as area,hh.partida as partida,g.descripcion,g.unidtext,d.cantidad,d.precio_compra,d.cantidad*d.precio_compra as importe, if(d.elprov is null,a.id_prov,d.elprov) as prreal, a.id_prov as prrep
from constru_pedis a 
join constru_pedidos b on b.id_pedid=a.id
join constru_requis c on c.id=b.id_requis
join constru_requisiciones d on d.id_requi=c.id
join constru_insumos g on g.id=d.id_clave
join constru_partida e on e.id=c.id_part
join constru_cat_partidas hh on hh.id=e.id_cat_partida
join constru_especialidad f on f.id=c.id_area
join constru_generales h on h.id=$id_obra
join administracion_usuarios i on i.idempleado=a.solicito
WHERE a.id='$last_id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
    while($row = mysql_fetch_assoc($result)) {

    	if($row['prreal']==$row['prrep']){
			
			$data=$data.'<tr>
			<td>'.$row['obra'].'</td>
			<td>'.$row['id'].'</td>
			<td>'.$row['area'].'</td>
			<td>'.$row['partida'].'</td>
			<td>'.$row['solicito'].'</td>
			<td>'.$row['descripcion'].'</td>
			<td>'.$row['unidtext'].'</td>
			<td>'.$row['cantidad'].'</td>
			<td>'.$row['precio_compra'].'</td>
			<td>'.$row['importe'].'</td>
			</tr>';}
		}



$data=$mmss.$head.$data.'</tbody></table>';


    	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');

	 $addresses = explode(',', $email);
foreach ($addresses as $address) {
    $mail->AddAddress($address);
}
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Se ha creado una Ordenes de compra";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($data);

            $mail->Send();

        }

        $data='';
   


}


$JSON = array('success' =>1, 
				'datos'=>1);


	if($config_autorizaciones==0){
        	$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');

		$SQL="UPDATE constru_pedis SET estatus=3,idaut='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();


	$SQL="INSERT INTO constru_pedis (id_obra,nombre,solicito,estatus,fecha_entrega,id_prov,xxano,fpago,obsgen,condpago) VALUES ('$id_obra',' ','$iduserlog',1,'$fecha_entrega','$pro','$xxano','$fpago','$obsgen','$condpago');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$o=explode('/', $_POST['lalala']);

	foreach ($o as $key1 => $oentrada) {

		$entrada=explode(',',$oentrada);
		foreach ($entrada as $key => $value) {

			$q=explode('>', $value);
			$v=explode('=', $q[1]);

			$SQL = "SELECT id FROM constru_pedidos WHERE id_obra='$id_obra' AND id_pedid='$last_id' AND id_requis='".$q[0]."';";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			if(mysql_num_rows($result)>0){
				
			}else{
				$SQL="INSERT INTO constru_pedidos (id_obra, id_pedid, id_requis,fecha_captura,sestmp) VALUES ('$id_obra', '$last_id', '".$q[0]."', NOW(),'$sestmp');";
				mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			}

			$SQL="UPDATE constru_requisiciones SET precio_compra='".$v[2]."', cantidad='".$v[3]."' WHERE id_obra='$id_obra' AND id_requi='".$v[0]."' AND id_clave='".$v[1]."' ;";
			mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

			$SQL = "UPDATE constru_requis SET estatus=4 WHERE id in (
 select id_requis from constru_pedis a left join constru_pedidos b on b.id_pedid=a.id WHERE a.id='$last_id');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

			//$SQL="UPDATE constru_requis SET estatus=4 WHERE id_obra='$id_obra' AND id_requi='".$v[0]."';";
			//mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		}
	}
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');

		$SQL="UPDATE constru_pedis SET estatus=3,idaut='$id_username_global',fechaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();

}

if(isset($opcion) && $opcion=='editOC'){
	$idoc=$_POST['idoc'];
	$idp=$_POST['idp'];

	$SQL = "SELECT a.id as id_pedis, a.condpago, a.obsgen, b.id as id_requisicion, b.elprov, b.id_clave as id_insumo, b.precio_compra,  c.clave, c.descripcion, c.precio, b.id_requi FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id and b.borrado=0 and b.elprov=a.id_prov
LEFT JOIN constru_insumos c on c.id=b.id_clave
WHERE a.id_obra='$id_obra' AND a.id='$idoc' and a.id_prov='$idp' AND a.borrado=0 AND b1.borrado=0 ORDER BY a.id desc;";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$return=array();
while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
}

echo json_encode($return);


}

if(isset($opcion) && $opcion=='saveeditOC'){


	$idoc=$_POST['idoc'];
	$idp=$_POST['idp'];
	$cond=$_POST['cond'];
	$obs=$_POST['obs'];
	$pcval=$_POST['pcval'];
	$idrequi=$_POST['idrequi'];

	$SQL="SELECT id from constru_entrada_almacen where id_oc='$idoc';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		echo "ent";
		exit();
	}

	$SQL="UPDATE constru_pedis SET id_prov='$idp', obsgen='$obs', condpago='$cond' WHERE id='$idoc';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$o=explode('#_#',$pcval);
	foreach ($o as $k => $v) {
		$exp=explode('#', $v);
		$id_insumo=$exp[0];//idinsumo
		$precio_compra=$exp[1];//cantidad
		$idre=$exp[2];//cantidad
		$SQL = "UPDATE constru_requisiciones SET precio_compra='$precio_compra', elprov='$idp' WHERE id_requi='$idre' AND id_clave='$id_insumo' AND id_obra='$id_obra' AND cancelado=0;";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}


		$cookie_xtructur = unserialize($_COOKIE['xtructur']);
    	//$id_obra = $cookie_xtructur['id_obra'];
		//$id=$_POST['id'];
		$pass='SUP3R4DM1N';
		$fecha=date('Y-m-d H:i:s');
		$idusr = $_SESSION['accelog_idempleado'];

		$SQL = "SELECT nombreusuario as username, idempleado from administracion_usuarios where idempleado='$idusr';";
    	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row = mysql_fetch_array($result); 

	    $id_username_global=$row['idempleado'];
	    $nombre=$row['username'];

		mysql_query("INSERT INTO constru_superadmin(fecha,modulo,usuario,idusuario,pass,accion,idobra) values('$fecha','Autorizacion de Ordenes de compra','$nombre','$id_username_global','$pass',concat('Se modifico la orden de compra ','$idoc'),'$id_obra')  ") or die("Couldn t execute query.".mysql_error());



}

if(isset($opcion) && $opcion=='save_sali'){

	$return=array();

	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];

	$id_cc=$_POST['ccosto'];

	$id=$_POST['id'];
	$recibio=$_POST['recibio'];
	$entrego=$_POST['entrego'];
	$autorizo=$_POST['autorizo'];
	$fecha_entrada=substr($_POST['fecente'],0,10);
	$fecha_entrada=$fecha_entrada.' '.date('H:i:s');
	$obs=$_POST['obs'];
	$id_oc=$_POST['id_oc'];
	$noagotada=$_POST['noagotada'];
	$entrada=explode(',', $_POST['entrada']);

	$iduserlog=$_POST['iduserlog'];

	$SQL="INSERT INTO constru_bit_salidas (id_obra,id_oc,id_almacenista,observaciones,id_recibio,id_entrego,id_autorizo,id_agru,id_area,id_esp,id_part,id_cc,fecha) VALUES ('$id_obra', '$id_oc','$iduserlog','$obs','$recibio','$entrego','$iduserlog','$ag','$ar','$es','$pa','$id_cc','$fecha_entrada');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	foreach ($entrada as $key => $value) {
		$v=explode('=', $value);
		$SQL="INSERT INTO constru_salida_almacen (id_obra,id_oc,id_req,id_almacenista,fecha_entrada,estatus,salio,id_insumo,id_bit_salida) VALUES ('$id_obra','$id_oc','".$v[0]."','$iduserlog','$fecha_entrada',1,'".$v[2]."','".$v[1]."','$last_id');";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($noagotada==0){
		$SQL="UPDATE constru_pedis SET salida_alm=1 WHERE id='$id_oc';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	exit();
}

if(isset($opcion) && $opcion=='save_topes'){
	$return=array();
	$entrada=explode(',', $_POST['entrada']);

	foreach ($entrada as $key => $value) {
		$v=explode('=', $value);
		$SQL = "SELECT id FROM constru_vol_tope WHERE id_obra='$id_obra' AND id_area='".$v[0]."' AND id_clave='".$v[1]."' ";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			if(mysql_num_rows($result)>0){
				$row = mysql_fetch_assoc($result);
				$id=$row['id'];
				$SQL="UPDATE constru_vol_tope set vol_tope='".$v[2]."', id_partida='".$v[3]."' WHERE id='$id'; ";
			}else{
				$SQL="INSERT INTO constru_vol_tope (id_obra,id_clave,id_area,vol_tope,id_partida) VALUES 
				('$id_obra','".$v[1]."','".$v[0]."','".$v[2]."','".$v[3]."');";
			}
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	exit();
}

if(isset($opcion) && $opcion=='agotarEntrada'){
	$return=array();
	$idEntrada=$_POST['idEntrada'];


	
	$SQL="UPDATE constru_pedis SET entrada_alm=1 WHERE id='$idEntrada';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	/*$SQL="SELECT * FROM constru_entrada_almacen ;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
*/
	exit();
}

if(isset($opcion) && $opcion=='agotarSalida'){
	$return=array();
	$idSalida=$_POST['idSalida'];


	
	$SQL="UPDATE constru_pedis SET salida_alm=1 WHERE id='$idSalida';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	/*$SQL="SELECT * FROM constru_entrada_almacen ;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
*/
	exit();
}

if(isset($opcion) && $opcion=='save_entri'){
	$return=array();
	$id=$_POST['id'];
	$almacenista=$_POST['solicito'];
	$fecha_entrada=$_POST['fecente'];
	$obs=$_POST['obs'];
	$id_oc=$_POST['id_oc'];
	$noagotada=$_POST['noagotada'];
	$iduserlog=$_POST['iduserlog'];
	$entrada=explode(',', $_POST['entrada']);

	$SQL="INSERT INTO constru_bit_entradas (id_obra,id_oc,id_almacenista,observaciones,fecha) VALUES ('$id_obra', '$id_oc','$iduserlog','$obs','$fecha_entrada');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	foreach ($entrada as $key => $value) {
		$v=explode('=', $value);
		$SQL="INSERT INTO constru_entrada_almacen (id_obra,id_oc,id_req,id_almacenista,fecha_entrada,estatus,llego,id_insumo,id_bit_entrada) VALUES ('$id_obra','$id_oc','".$v[0]."','$iduserlog','$fecha_entrada',1,'".$v[2]."','".$v[1]."','$last_id');";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($noagotada==0){
		//$SQL="UPDATE constru_pedis SET entrada_alm=1 WHERE id='$id_oc';";
		//mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	$SQL="UPDATE constru_pedis SET salida_alm=0 WHERE id='$id_oc';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	/*$SQL="SELECT * FROM constru_entrada_almacen ;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
*/
	exit();
}

if(isset($opcion) && $opcion=='save_est_cliente'){
	$return=array();
	$imp_con=$_POST['imp_con'];
	$imp_cont=$_POST['imp_cont'];
	$ade1=$_POST['ade1'];
	$ade2=$_POST['ade2'];
	$ade3=$_POST['ade3'];
	$anti=$_POST['anti'];
	$iaa=$_POST['iaa'];
	$iae=$_POST['iae'];
	$tota=$_POST['tota'];
	$poramo=$_POST['poramo'];

	$xxano=$_POST['sema'];
	$expxxano = explode('-', $xxano);
	$sema=$expxxano[1];

	$imp_est=$_POST['imp_est'];
	$fgarantia=$_POST['fgarantia'];
	$subt2=$_POST['subt2'];
	$iva=$_POST['iva'];

	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$id_aut=$_POST['id_aut'];

	$id=$_POST['id'];
	$id_des=$_POST['id_des'];
	$subt1=$_POST['subt1'];
	$retencion=$_POST['retencion'];
	$cargos=$_POST['cargos'];
	$total=$_POST['total'];

	$fgp=$_POST['fgp'];
	$rep=$_POST['rep'];

	$modiFac=$_POST['modiFac'];

	$fecha=date('Y-m-d H:i:s');

	$SQL="INSERT INTO constru_estimaciones_bit_cliente (id_obra,id_cliente,fecha,subtotal1,retencion,cargos,total,id_agru,id_area,id_esp,id_part,id_autorizo,imp_contrato,ade1,ade2,ade3,imp_tot_contrato,anticipo,amortizado_anterior,amortizado_estimacion,tot_amortizado,por_amortizar,imp_estimacion,amortizacion,fondo_garantia,subtotal2,iva,semana,fgp,rep,xxano,factura) VALUES ('$id_obra','$id_obra','$fecha','$subt1','$retencion','$cargos','$total','$ag','$ar','$es','$pa','$id_aut','$imp_con','$ade1','$ade2','$ade3','$imp_cont','$anti','$iaa','$iae','$tota','$poramo','$imp_est','$iae','$fgarantia','$subt2','$iva','$sema','$fgp','$rep','$xxano','$modiFac');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_estimaciones_cliente SET id_bit_cliente='$last_id' WHERE sestmp in (".$id.") AND id_bit_cliente=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_estimaciones_bit_cliente SET estatus=1,id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}




if(isset($opcion) && $opcion=='save_avance'){
	$return=array();

	$id_aut=$_POST['id_aut'];

	$id=$_POST['id'];
	$id_des=$_POST['id_des'];

	$fecha=date('Y-m-d H:i:s');

	$SQL="INSERT INTO constru_bit_avancevol(id_obra,id_usuario,fecha) values('$id_obra','$id_aut','$fecha');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_avancevol SET id_bit_avancevol='$last_id' WHERE sestemp in (".$id.") AND id_bit_avancevol=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);
	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='save_est_subcontratista'){
	$return=array();
	$imp_con=$_POST['imp_con'];
	$imp_cont=$_POST['imp_cont'];
	$ade1=$_POST['ade1'];
	$ade2=$_POST['ade2'];
	$ade3=$_POST['ade3'];
	$anti=$_POST['anti'];
	$iaa=$_POST['iaa'];
	$iae=$_POST['iae'];
	$tota=$_POST['tota'];
	$poramo=$_POST['poramo'];
	$ccosto=$_POST['ccosto'];
	$imp_est=$_POST['imp_est'];
	$fgarantia=$_POST['fgarantia'];
	$subt2=$_POST['subt2'];
	$iva=$_POST['iva'];

	$xxano=$_POST['sema'];
	$expxxano = explode('-', $xxano);
	$sema=$expxxano[1];

	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$id_aut=$_POST['id_aut'];

	$id=$_POST['id'];
	$id_des=$_POST['id_des'];
	$subt1=$_POST['subt1'];
	$retencion=$_POST['retencion'];
	$cargos=$_POST['cargos'];
	$total=$_POST['total'];

	$fact=$_POST['fact'];

	$fgp=$_POST['fgp'];
	$rep=$_POST['rep'];
	$fecha=date('Y-m-d H:i:s');

	$SQL="INSERT INTO constru_estimaciones_bit_subcontratista (id_obra,id_subcontratista,fecha,subtotal1,retencion,cargos,total,id_agru,id_area,id_esp,id_part,id_autorizo,imp_contrato,ade1,ade2,ade3,imp_tot_contrato,anticipo,amortizado_anterior,amortizado_estimacion,tot_amortizado,por_amortizar,imp_estimacion,amortizacion,fondo_garantia,subtotal2,iva,id_cc,fgp,rep,factura,semana,xxano) VALUES ('$id_obra','$id_des','$fecha','$subt1','$retencion','$cargos','$total','$ag','$ar','$es','$pa','$id_aut','$imp_con','$ade1','$ade2','$ade3','$imp_cont','$anti','$iaa','$iae','$tota','$poramo','$imp_est','$iae','$fgarantia','$subt2','$iva','$ccosto','$fgp','$rep','$fact','$sema','$xxano');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_estimaciones_subcontratista SET id_bit_subcontratista='$last_id' WHERE sestmp in (".$id.") AND id_bit_subcontratista=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_estimaciones_bit_subcontratista SET estatus=1,id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='save_cheque2'){
	$return=array();

	

	$id_est=$_POST['id_est'];//tecnico oc o campo
	$noc=$_POST['noc'];
	$val=$_POST['val'];
	$ban=$_POST['ban'];
	$fee=$_POST['fee'].' '.date('H:i:s');
	$estc=$_POST['estc'];
	$estf=$_POST['estf'];
	$valpago=$_POST['valpago'];

	$remesa=$_POST['remesa'];

	$date = new DateTime($sd);
    $sema = $date->format("W");
    $fecha=date('Y-m-d H:i:s');

    $idcobro=$_POST['idcobro'];
	$mp=$_POST['mp'];
	$id_est=$_POST['idestimacion'];


	$SQL="INSERT INTO constru_cheques2 (id_obra,id_cobro,no_cheque,banco,fecha_expedicion,estatus_cheque,estatus_factura,fecha,cobro,mpago) VALUES ('$id_obra','$id_est','$noc','$ban','$fee','$estc','$estf','$fecha','$idcobro',$valpago);";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	exit();
}

if(isset($opcion) && $opcion=='save_cheque'){
	$return=array();
	$id_est=$_POST['id_est'];//tecnico oc o campo
	$noc=$_POST['noc'];
	$val=$_POST['val'];
	$ban=$_POST['ban'];
	$fee=$_POST['fee'].' '.date('H:i:s');
	$estc=$_POST['estc'];
	$estf=$_POST['estf'];

	$remesa=$_POST['remesa'];

	$date = new DateTime($sd);
    $sema = $date->format("W");
    $fecha=date('Y-m-d H:i:s');

    $SQL2="SELECT * from constru_cheques where no_cheque='$noc'";
    $result2=mysql_query( $SQL2 ) or die("Couldn t execute query.".mysql_error());
  if(mysql_num_rows($result2)>0){
$JSON = array('success' =>2);
	echo json_encode($JSON);
	exit();

  }



	$SQL="INSERT INTO constru_cheques (id_obra,id_remesa,no_cheque,banco,fecha_expedicion,estatus_cheque,estatus_factura,fecha,remesa) VALUES ('$id_obra','$id_est','$noc','$ban','$fee','$estc','$estf','$fecha','$remesa');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1);
	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='save_nominaca'){
	$return=array();
	$id_des=$_POST['id_des'];//tecnico oc o campo
	$solicito=$_POST['solicito'];
	$ccosto=$_POST['ccosto'];

	$total=$_POST['total'];

	$sd=$_POST['sd'];
	$ed=$_POST['ed'];

	$ids=$_POST['ids'];
	$dts=$_POST['dts'];

	$he= $_POST['he'];
	$idt= $_POST['idt'];
	$ihe= $_POST['ihe'];
	$desci= $_POST['desci'];
	$finis= $_POST['finis'];
	$subt=$_POST['subt'];
	$totallist=$_POST['totallist'];

	$date = new DateTime($sd);
    $sema = $date->format("W");
    $fecha=date('Y-m-d H:i:s');



	$SQL="INSERT INTO constru_bit_nominaca (id_obra,id_tecnico,per_ini,per_fin,total,id_aut,id_cc,fecha) VALUES ('$id_obra','$id_des','$sd','$ed','$total','$solicito','$ccosto','$fecha');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$last_id = mysql_insert_id();

	$x=0;
	foreach ($ids as $k => $v) {
		$SQL="INSERT INTO constru_gen_nomtec (id_obra,semana,id_tecnico,fecha,dt,id_bit_nom,he,idt,ihe,desci,finis,subt,total) VALUES ('$id_obra','$sema','$v','$fecha','".$dts[$x]."','".$last_id."','".$he[$x]."','".$idt[$x]."','".$ihe[$x]."','".$desci[$x]."','".$finis[$x]."','".$subt[$x]."','".$totallist[$x]."');";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$x++;
	}

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_bit_nominaca SET estatus=1,id_aut2='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	
	$JSON = array('success' =>1, 
				'datos'=>1);
	echo json_encode($JSON);

	
	exit();
}


if(isset($opcion) && $opcion=='save_nominades'){
	$return=array();
	$id_des=$_POST['id_des'];//semana
	$solicito=$_POST['solicito'];
	$ccosto=$_POST['ccosto'];
	$id_edif=$_POST['id_edif'];

	$total=$_POST['total'];
	$totale=$_POST['totale'];

	$sd=$_POST['sd'];
	$ed=$_POST['ed'];

	$ids=$_POST['ids'];
	$dts=$_POST['dts'];

	$he= $_POST['he'];
  	$df= $_POST['df'];
	$idt= $_POST['idt'];
	$ihe= $_POST['ihe'];
	$idf= $_POST['idf'];
	$desci= $_POST['desci'];
	$finis= $_POST['finis'];
	$subt=$_POST['subt'];
	$difd= $_POST['difd'];
	$totallist=$_POST['totallist'];

	$date = new DateTime($sd);
    $sema = $date->format("W");
    $fecha=date('Y-m-d H:i:s');

    $xxano = xxano($sd);

	$SQL="INSERT INTO constru_bit_nominadest (id_obra,id_dest,id_edif,id_aut,semana,total,total_est,id_cc,per_ini,per_fin,fecha,xxano) VALUES ('$id_obra','$id_des','$id_edif','$solicito','$sema','$total','$totale','$ccosto','$sd','$ed','$fecha','$xxano');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$last_id = mysql_insert_id();

	$x=0;
	foreach ($ids as $k => $v) {
		$SQL="INSERT INTO constru_gen_nomdest (id_obra,semana,id_dest,id_emp,fecha,dt,id_bit_nom,he,df,idt,ihe,idf,desci,finis,subt,difd,total,xxano) VALUES ('$id_obra','$sema','$id_des','$v','$fecha','".$dts[$x]."','".$last_id."','".$he[$x]."','".$df[$x]."','".$idt[$x]."','".$ihe[$x]."','".$idf[$x]."','".$desci[$x]."','".$finis[$x]."','".$subt[$x]."','".$difd[$x]."','".$totallist[$x]."','$xxano');";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$x++;
	}

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_bit_nominadest SET estatus=1,id_aut2='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	
	$JSON = array('success' =>1, 
				'datos'=>1);
	echo json_encode($JSON);
	
	exit();
}

if(isset($opcion) && $opcion=='save_est_chica'){
	$return=array();
	$xxano=$_POST['id_des'];
	$expxxano = explode('-', $xxano);
	$id_des=$expxxano[1];

	$id=$_POST['id'];
	$subt1=$_POST['subt1'];
	$total=$_POST['total'];
	$id_aut=$_POST['id_aut'];
	$imp_est=$_POST['imp_est'];
	$iva=$_POST['iva'];
	$fecha=date('Y-m-d H:i:s');

	$SQL="INSERT INTO constru_estimaciones_bit_chica (id_obra,id_autorizo,fecha,imp_estimacion,subtotal,iva,total,id_cc,semana,xxano) VALUES ('$id_obra','$id_aut','$fecha','$imp_est','$subt1','$iva','$total',0,'$id_des','$xxano');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_estimaciones_chica SET id_bit_chica='$last_id' WHERE sestmp in (".$id.") AND id_bit_chica=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_estimaciones_bit_chica SET estatus=1,idaut='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='save_est_prov'){
	$return=array();
	$xxano=$_POST['id_des'];
	$expxxano = explode('-', $xxano);
	$id_des=$expxxano[1];


	$id=$_POST['id'];
	$subt1=$_POST['subt1'];
	$total=$_POST['total'];
	$id_aut=$_POST['id_aut'];
	$imp_est=$_POST['imp_est'];

	$iva=$_POST['iva'];
	$fact=$_POST['fact'];

	$id_prov=$_POST['id_prov'];
	$id_oc=$_POST['id_oc'];
	$fecha=date('Y-m-d H:i:s');
	

	$SQL="INSERT INTO constru_estimaciones_bit_prov (id_obra,id_autorizo,fecha,imp_estimacion,subtotal,iva,total,id_oc,factura,semana,id_prov,xxano) VALUES ('$id_obra','$id_aut','$fecha','$imp_est','$subt1','$iva','$total','$id_oc','$fact','$id_des','$id_prov','$xxano');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_estimaciones_prov SET id_bit_prov='$last_id' WHERE sestmp in (".$id.") AND id_bit_prov=0 AND id_oc='$id_oc';";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	$completada=0;
	$SQL="SELECT b.vol_eje, b.* FROM constru_estimaciones_bit_prov a
	left join constru_estimaciones_prov b on b.id_bit_prov=a.id
	where a.estatus!=2 AND a.id_obra='$id_obra' and a.id_oc='$id_oc' order by b.id_clave, id desc;";
	$result=mysql_query($SQL) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$arraypro=array();
		while($row = mysql_fetch_assoc($result)) {
			if (array_key_exists($row['id_clave'], $arraypro)) {
				continue;
			}else{	
				if($row['vol_eje']>0){
					$completada++;
				}
				$arraypro[$row['id_clave']]=1;
			}
		}
	}else{
		$completada=1;
	}

	if($completada==0){
		$SQL = "UPDATE constru_pedis SET estcomp=1 WHERE id='$id_oc';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_estimaciones_bit_prov SET estatus=1,id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='save_est_indirectos'){
	$return=array();
	$xxano=$_POST['id_des'];
	$expxxano = explode('-', $xxano);
	$id_des=$expxxano[1];

	$id=$_POST['id'];
	$subt1=$_POST['subt1'];
	$total=$_POST['total'];
	$id_aut=$_POST['id_aut'];
	$imp_est=$_POST['imp_est'];
	$id_cc=$_POST['id_cc'];
	$iva=$_POST['iva'];
	$fact=$_POST['fact'];
	$id_prov=$_POST['id_prov'];

$fecha=date('Y-m-d H:i:s');
	$SQL="INSERT INTO constru_estimaciones_bit_indirectos (id_obra,id_autorizo,fecha,imp_estimacion,subtotal,iva,total,id_cc,factura,semana,id_prov,xxano) VALUES ('$id_obra','$id_aut','$fecha','$imp_est','$subt1','$iva','$total','$id_cc','$fact','$id_des','$id_prov','$xxano');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_estimaciones_indirectos SET id_bit_indirectos='$last_id' WHERE sestmp in (".$id.") AND id_bit_indirectos=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_estimaciones_bit_indirectos SET estatus=1,id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='save_est_destajista'){
	$return=array();

	$xxano=$_POST['sema'];
	$expxxano = explode('-', $xxano);
	$sema=$expxxano[1];

	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$id_aut=$_POST['id_aut'];

	$ccosto=$_POST['ccosto'];

	$id=$_POST['id'];
	$id_des=$_POST['id_des'];
	$subt1=$_POST['subt1'];
	$retencion=$_POST['retencion'];
	$cargos=$_POST['cargos'];
	$total=$_POST['total'];

	$rep=$_POST['rep'];

$fecha=date('Y-m-d H:i:s');

	$SQL="INSERT INTO constru_estimaciones_bit_destajista (id_obra,id_destajista,fecha,subtotal1,retencion,cargos,total,id_agru,id_area,id_esp,id_part,id_autorizo,semana,id_cc,rep,xxano) VALUES ('$id_obra','$id_des','$fecha','$subt1','$retencion','$cargos','$total','$ag','$ar','$es','$pa','$id_aut','$sema','$ccosto','$rep','$xxano');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_estimaciones_destajista SET id_bit_destajista='$last_id' WHERE sestmp in (".$id.") AND id_bit_destajista=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_estimaciones_bit_destajista SET estatus=1,id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='save_requi'){
    $return=array();
    $ag=$_POST['ag'];
    $ar=$_POST['ar'];
    $es=$_POST['es'];
    $pa=$_POST['pa'];

    $id=$_POST['id'];
    $solicito=$_POST['solicito'];
    $fecha_entrega=$_POST['fecente'];
    $fecha_utilizacion=$_POST['fecente3'];
    $area=$_POST['area'];
     $obs=$_POST['obs'];

    $iduserlog=$_POST['iduserlog'];
    $fecha=date('Y-m-d H:i:s');
    $xxano = xxano($fff);

    $SQL="INSERT INTO constru_requis (id_obra,solicito,estatus,fecha_entrega,id_agru,id_area,id_esp,id_part,xxano,fecha_captura,fecha_utilizacion,obs) VALUES ('$id_obra','$iduserlog',1,'$fecha_entrega','$ag','$ar','$es','$pa','$xxano','$fecha','$fecha_utilizacion','$obs');";
    mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $last_id = mysql_insert_id();
    $SQL = "UPDATE constru_requisiciones SET id_requi='$last_id' WHERE sestmp in (".$id.") AND id_requi=0;";
    mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $JSON = array('success' =>1, 
                'datos'=>1);

    if($config_autorizaciones==0){
        $idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    $row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    
        $SQL="UPDATE constru_requis SET estatus=3,autorizo='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
        mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
    }



$SQL = "SELECT rcorreo from constru_config where id_obra='$id_obra';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$row = mysql_fetch_array($result); 

    $email=$row['rcorreo'];

    $mmss='Se ha creado la <b>requisicion '.$last_id.'</b> <br><br>';


		$head="<br><table border=1>
<tbody>
<tr>
<th align='left'>Obra</th>
<th align='left'>Requisicion</th>
<th align='left'>Area</th>
<th align='left'>Solicita</th>
<th align='left'>Fecha de Entrega</th>
<th align='left'>Descripcion</th>
<th align='left'>U.M.</th>
<th align='left'>Cantidad</th>
<th align='right'>Precio</th>
<th align='right'>Importe</th>
</tr>";


 $SQL2="SELECT c.id,h.obra,i.nombre as solicito,f.nombre as area,c.fecha_entrega,g.descripcion,g.unidtext,d.cantidad,g.precio,d.cantidad*g.precio as importe 
from constru_requis c
join constru_requisiciones d on d.id_requi=c.id
join constru_insumos g on g.id=d.id_clave
join constru_especialidad f on f.id=c.id_area
join constru_generales h on h.id=$id_obra
join administracion_usuarios i on i.idempleado=c.solicito
WHERE c.id='$last_id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
    while($row = mysql_fetch_assoc($result)) {

$data=$data.'<tr>
<td>'.$row['obra'].'</td>
<td>'.$row['id'].'</td>
<td>'.$row['area'].'</td>
<td>'.$row['solicito'].'</td>
<td>'.substr($row['fecha_entrega'],0,10).'</td>
<td>'.$row['descripcion'].'</td>
<td>'.$row['unidtext'].'</td>
<td>'.$row['cantidad'].'</td>
<td>'.$row['precio'].'</td>
<td>'.$row['importe'].'</td>
</tr>';}

 $data=$mmss.$head.$data.'</tbody></table>';


    	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
	 	 $addresses = explode(',', $email);
foreach ($addresses as $address) {
    $mail->AddAddress($address);
}
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Se ha creado una Requisicion";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($data);

            $mail->Send();}
   


    echo json_encode($JSON);
    exit();
}


if(isset($opcion) && $opcion=='save_extra'){
	$return=array();
	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$total=$_POST['total'];
	$id=$_POST['id'];
	$solicito=$_POST['solicito'];
	$fecha=$_POST['fecente'];
	$area=$_POST['area'];

	$iduserlog=$_POST['iduserlog'];

	$xxano = xxano($fff);

	$SQL="INSERT INTO constru_bit_solicitudes (id_obra,id_solicito,estatus,fecha,naturaleza,total) VALUES ('$id_obra','$iduserlog',0,'$fecha','Extra','$total');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	 $SQL = "UPDATE constru_recurso SET id_bit_solicitud='$last_id' WHERE sestemp in (".$id.") AND id_bit_solicitud=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_bit_solicitudes SET estatus=1,id_aut='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}


if(isset($opcion) && $opcion=='save_adi'){
	$return=array();
	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$total=$_POST['total'];
	$id=$_POST['id'];
	$solicito=$_POST['solicito'];
	$fecha=$_POST['fecente'];
	$area=$_POST['area'];

	$iduserlog=$_POST['iduserlog'];

	$xxano = xxano($fff);

	$SQL="INSERT INTO constru_bit_solicitudes (id_obra,id_solicito,estatus,fecha,naturaleza,total) VALUES ('$id_obra','$iduserlog',0,'$fecha','Adicional','$total');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
    $SQL = "UPDATE constru_recurso SET id_bit_solicitud='$last_id' WHERE sestemp in (".$id.") AND id_bit_solicitud=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_bit_solicitudes SET estatus=1,id_aut='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}



if(isset($opcion) && $opcion=='save_nocob'){
	$return=array();
	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$total=$_POST['total'];
	$id=$_POST['id'];
	$solicito=$_POST['solicito'];
	$fecha=$_POST['fecente'];
	$area=$_POST['area'];

	$iduserlog=$_POST['iduserlog'];

	$xxano = xxano($fff);

	$SQL="INSERT INTO constru_bit_solicitudes (id_obra,id_solicito,estatus,fecha,naturaleza,total) VALUES ('$id_obra','$iduserlog',0,'$fecha','No cobrable','$total');";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$last_id = mysql_insert_id();
	$SQL = "UPDATE constru_recurso SET id_bit_solicitud='$last_id' WHERE sestemp in (".$id.") AND id_bit_solicitud=0;";
	mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$JSON = array('success' =>1, 
				'datos'=>1);

	if($config_autorizaciones==0){
		$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
		$SQL="UPDATE constru_bit_solicitudes SET estatus=1,id_aut='$id_username_global',fechaaut='$fecha' WHERE id ='$last_id';";
		mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='tabu'){
	$id_tipo_tab=$_POST['id_tipo_tab'];
	$SQL = "SELECT id, if(sal_semanal>0,concat(categoria,' / $',sal_semanal),concat(categoria,' / $',sal_mensual)) as categoria FROM constru_categoria WHERE borrado=0 AND id_tipo_tab='$id_tipo_tab' ORDER by clave_cat desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['categoria'].';';
	}
	$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='asign_fams'){
	$SQL = "SELECT id, nomfam from constru_famat";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['nomfam'].';';
	}
	$cadena=trim($cadena,';');
	echo ($cadena);
	exit();
}

if(isset($opcion) && $opcion=='categorias'){
	$return=array();
	$id_familia=$_POST['id_familia'];
	$SQL = "SELECT id, if(sal_semanal>0,concat(categoria,' / $',sal_semanal),concat(categoria,' / $',sal_mensual)) as categoria FROM constru_categoria WHERE id_familia='$id_familia' AND borrado=0 ORDER by id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}



if(isset($opcion) && $opcion==''){
	$idp=$_POST['idp'];
	$SQL = "SELECT id,nombre FROM constru_presupuesto WHERE id_proyecto='$idp';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	while($row = mysql_fetch_assoc($result)) {
    	$array[]=$row;
	}
	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='autorizarem'){

	$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];

    date_default_timezone_set('America/Mexico_City');
    
    $fecha=date('Y-m-d H:i:s');



	$idrem=$_POST['idrem'];
	$opt=$_POST['opt'];

	$SQL = "UPDATE constru_bit_remesa SET estatus='$opt' WHERE id='$idrem';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "UPDATE constru_bit_remesas SET  fechaaut='$fecha',idaut='$id_username_global' WHERE id_bit_remesa='$idrem';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
/*
	if($opt==2){
		$SQL = "INSERT INTO constru_cheques (id_obra,id_remesa) VALUES ( '$id_obra','$idrem') ;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());



	}
	*/

}

/////////////////////////chais//////////////////////////////////
if(isset($opcion) && $opcion=='pdf'){
	$id=$_POST['id'];
	$opt=$_POST['opt'];
	$r=$_POST['r'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id.'_'.$opt.'_'.$hoy;

	if($opt=='des'){
					
		$SQL = "SELECT a.id_autorizo,a.id_autorizo2,a.id, a.subtotal1, a.retencion, a.cargos, a.total, a.fecha, a.id_esp, a.rep, a.xxano, h.id as id_tecnico, h.nombre as nombre_tecnico, b.id as id_agrupador, b.nombre as nombre_agrupador, e.id as id_partida, ee.partida as nombre_partida, 
		c.id as id_area, c.nombre as nombre_area, dd.especialidad as nombre_especialidad, f.obra ,u.usuario as solicitante,u2.usuario as autoridad,a.estatus as autorizacion,
				concat(des.nombre,' ',des.paterno,' ',des.materno) as nomdestajista
		 FROM constru_estimaciones_bit_destajista a 
		left join accelog_usuarios u on u.idempleado=a.id_autorizo
		 left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
		left join constru_agrupador b on b.id=a.id_agru
	    left join constru_especialidad c on c.id=a.id_area
		left join constru_area d on d.id=a.id_esp
		left join constru_cat_especialidad dd on dd.id=d.id_cat_especialidad
		left join constru_partida e on e.id=a.id_part 
		left join constru_cat_partidas ee on ee.id=e.id_cat_partida 
		left join constru_altas g on g.id=a.id_autorizo
		left join constru_info_tdo h on h.id_alta=g.id
		left join constru_info_tdo des on des.id_alta=a.id_destajista
		LEFT JOIN constru_generales f on f.id = a.id_obra
		where a.id_obra='$id_obra' AND a.id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row = mysql_fetch_array($result); 
	   	$nombre_obra = $row['obra'];
	   	$id_agrupador =$row['id_agrupador'];
	   	$nombre_agrupador = $row['nombre_agrupador'];
	   	$id_partida =$row['id_partida'];
	   	$nombre_partida = $row['nombre_partida'];
	   	$id_area =$row['id_area'];
	   	$nombre_area = $row['nombre_area'];
	   	$id_especialidad =$row['id_esp'];
	   	$nombre_especialidad = $row['nombre_especialidad'];
	   	//$id_tecnico =$row['id_tecnico'];
	   	//$nombre_tecnico = $row['nombre_tecnico'];	
	   	$subtotal1 =$row['subtotal1'];
	   	$subtotal1F = number_format($subtotal1,2);	
	   	$retencion =$row['retencion'];
	   	$retencionF = number_format($retencion,2);
	   	$cargos =$row['cargos'];
	   	$cargosF = number_format($cargos,2);
	   	$total =$row['total'];
	   	$totalF = number_format($total,2);	
	   	$fecha =$row['fecha']; 
	   	$rep = $row['rep']; 
	   	$semana = $row['xxano'];
	   	$subtotal2 = $retencion + $cargos;
	   	$subtotal2F = number_format($subtotal2,2);
	   	$autorizacion=$row['autorizacion'];	
        $autoridad=$row['autoridad'];
        $nomdestajista=$row['nomdestajista'];
        if ($autorizacion=='0'){$autoridad='Por Autorizar';}
        $solicitante=$row['solicitante'];
		/// funciona con la funcion divid...
		$semanaarry = dividirfecha($semana);
		$semana1=$semanaarry[1];
		// $año1=$$semanaarry[0];   NO ES REQUERIDO 
		//fun para obtener periodo
		week_bounds($fecha, $start, $end);


		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }



					require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					    $content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    	<td border="0" width="240">'.$logo.'</td> <!-- logo-->
					    		<td height="50" width="500"; style="font-weight:bold;">ESTIMACIÓN -'.$id.'</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		<td width="333" style="font-weight:bold;">SEMANA '.$semana1.'</td>
					    		<td width="333" style="font-weight:bold;">FECHA DE ESTIMACIÓN '.$fecha.'</td>
					    		<td width="333" style="font-weight:bold;">PERIODO del '.$start.' al '.$end.'</td>
					    	</tr>
					    	<tr>
					    	<td width="333" style="font-weight:bold;">Solicito: '.$solicitante.'</td>
					    		<td width="333" style="font-weight:bold;">Autorizo: '.$autoridad.'</td>
					    		<td width="333" style="font-weight:bold;">Maestro: '.$nomdestajista.'</td>
					    	</tr>
						</table>
					    <table>
					    	<tr>
					    		<td width="200">'.$id_tecnico.'</td>
					    		<td width="200">'.$nombre_tecnico.'</td>
					    	</tr>
					    </table>
					    <!--
					    <table align="center" width=100% border="0">
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AGRUPADOR</td>
					    		<td width="150">'.$nombre_agrupador.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">ESPECIALIDAD</td>
					    		<td width="150">'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AREA</td>
					    		<td width="150">'.$nombre_area.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">PARTIDA</td>
					    		<td width="150">'.$nombre_partida.'</td>
					    	</tr>
					    </table>
					    -->
					   	<table>
					    	<tr>
					    		<td height="20"width="1000" align="center" style="font-size:20;font-weight:bold;">ESTIMACIÓN DESTAJISTAS</td>
					    	</tr>
					    </table>
					    <table border="1" style="width: 1100px;>
					    	<tr>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Clave</td>
					    		<td style="width: 300px; font-weight:bold; text-align:center;">Descripción</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Unidad</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Vol. Tope</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">PU destajo</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Importe</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol Anterior</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Vol Estimado</td>
					    		<td style="width: 70px; font-weight:bold; text-align:center;">Vol Acumulado</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol Ejecutar</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Imp. Estimación</td>
					    	</tr>';
					    			$SQL = "SELECT c.id_destajista, c.id as iid, a.id as ido, b.id as id_insumo, 
					    			a.vol_est, b.unidtext, b.descripcion, b.pu_destajo, b.codigo, d.vol_tope, d.vol_tope*b.pu_destajo as total, a.vol_anterior
									 FROM constru_estimaciones_bit_destajista c
									 inner join constru_estimaciones_destajista a on a.id_bit_destajista=c.id
									 left join constru_recurso b on b.id=a.id_clave 
									left join constru_vol_tope d on d.id_clave=b.id AND d.id_area=c.id_area AND d.id_obra='$id_obra'
									 WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_destajista='$id' AND a.borrado=0 AND c.id='$id';";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$clave = $row['codigo'];	
								    		$descripcion = $row['descripcion'];
								    		$unidad = $row['unidtext'];	
								    		$vol_tope=$row['vol_tope'];
								    		$pu_destajo=$row['pu_destajo'];
								    		$vol_anterior=$row['vol_anterior'];
								    		$vol_est=$row['vol_est'];	
								    		$importe = $pu_destajo * $vol_tope;
								    		$importeF = number_format($importe,2);
								    		$pu_destajoF =number_format($pu_destajo,2);
								    		$vol_acumulado = $vol_anterior + $vol_est;
								    		$vol_ejecutar = $vol_tope - $vol_acumulado;
								    		$imp_estimacion = $pu_destajo * $vol_est;
								    		$imp_estimacionF = number_format($imp_estimacion,2);
								    		$total_imp = $total_imp + $importe;
								    		$total_impF = number_format($total_imp,2);
								    		$total_imp_est = $total_imp_est + $imp_estimacion;
								    		$total_imp_estF = number_format($total_imp_est,2);
								    		$vol_topeF = number_format($vol_tope,2);
								    		$vol_anteriorF = number_format($vol_anterior,2);
								    		$vol_estF = number_format($vol_est,2);
								    		$vol_acumuladoF = number_format($vol_acumulado,2);
								    		$vol_ejecutarF = number_format($vol_ejecutar,2);
														
					    	$content.='
							<tr>
								<td style="width: 65px; font-size:10px;">'.$clave.'</td>
								<td style="width: 300px; font-size:10px;">'.$descripcion.'</td>
								<td style="width: 65px; text-align:center;">'.$unidad.'</td>
								<td style="width: 65px; text-align:right;">'.$vol_topeF.'</td>
								<td style="width: 60px; text-align:right;">$'.$pu_destajoF.'</td>
								<td style="width: 85px; text-align:right;">$'.$importeF.'</td>
								<td style="width: 60px; text-align:right;">'.$vol_anteriorF.'</td>
								<td style="width: 65px; text-align:right;">'.$vol_estF.'</td>
								<td style="width: 70px; text-align:right;">'.$vol_acumuladoF.'</td>
								<td style="width: 60px; text-align:right;">'.$vol_ejecutarF.'</td>
								<td style="width: 85px; text-align:right;">$'.$imp_estimacionF.'</td>
								
					    	</tr>'
					    	;}
					    	$content.='
					    	<tr>
					    		<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 60px; text-align:right;">Total:</td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$total_impF.'</td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$total_imp_estF.'</td>
					    	</tr>
					    </table>
					    <table border="0">
					    	<tr>
					    		<td colspan="4" height="30" width="200"></td>
					    	</tr>	
					    	<tr>
					    		<td colspan="4" style="font-weight:bold;">Estimación</td>
					    	</tr>
					    	<tr>
					    		<td>Importe esta estimación</td>
					    		<td width="150">$'.$subtotal1F.'</td>
					    		<td width="150"></td>
					    		<td>Planeacion</td>
					    	</tr>
					    	<tr>
					    		<td>Subtotal 1:</td>
					    		<td>$'.$subtotal1F.'</td>
					    		<td></td>
					    		<td>'.$nombre_agrupador.'</td>
					    	</tr>
					    	<tr>
					    		<td>Retención '.$rep.'%:</td>
					    		<td>$'.$retencionF.'</td>
					    		<td></td>
					    		<td>'.$nombre_area.'</td>
					    	</tr>
					    	<tr>
					    		<td>Cargos:</td>
					    		<td>$'.$cargosF.'</td>
					    		<td></td>
					    		<td>'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td>Subtotal 2 (Retenciones):</td>
					    		<td>$'.$subtotal2F.'</td>
					    		<td></td>
					    		<td>'.$nombre_partida.'</td>
					    	</tr>
					    	<tr>
					    		<td>Total:</td>
					    		<td>$'.$totalF.'</td>
					    		<td></td>
					    		<td></td>
					    	</tr>
					    	<tr>
					    		<td>Fecha de Autorización:</td>
					    		<td>'.$fecha.'</td>
					    		<td width="100"></td>
					    		<td></td>
					    	</tr>
					    </table>
					    <page_footer>
						    <table align="center">
						    	<tr>
						    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">DESTAJISTA</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    </table>
					    </page_footer>
					</page>';
   					// horizontal L Vertical P
   					// [[page_cu]]/[[page_nb]] PARA NUMERO DE PAGINA 
				    $html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
}
if(isset($opcion) && $opcion=='pdfrequisicion'){
	$id=$_POST['id'];
	$opt=$_POST['opt'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id.'_'.$opt.'_'.$hoy;
	if($opt=='req'){
				
			$SQL = "SELECT a.fecha_utilizacion,a.id, i.obra, a.id_agru, a.id_area, a.id_esp, a.id_part, a.fecha_entrega, a.xxano, a.fecha_captura, j.nombre as nombre_agrupador, h.especialidad as nombre_especialidad, g.nombre as nombre_area, f.partida as nombre_partida, i.localizacion,u.usuario as solicitante,u2.usuario as autoridad, a.estatus as autorizado,a.obs as obs 

			FROM constru_requis a
			left join accelog_usuarios u on u.idempleado=a.solicito
            left join accelog_usuarios u2 on u2.idempleado=a.autorizo
			LEFT JOIN constru_requisiciones b on b.id_requi=a.id
			LEFT JOIN constru_insumos c on c.id=b.id_clave
			LEFT JOIN constru_especialidad es on es.id=a.id_area
			left JOIN constru_info_tdo d on d.id_alta=a.solicito
			left JOIN constru_partida e on e.id=a.id_part
			left JOIN constru_cat_partidas f on f.id=e.id_cat_partida
			left JOIN constru_area g on g.id=a.id_esp
			left JOIN constru_cat_especialidad h on h.id=g.id_cat_especialidad
			left JOIN constru_generales i on i.id=a.id_obra
			left JOIN constru_agrupador j on j.id=a.id_agru
			WHERE a.id_area is not null AND a.id_obra='$id_obra' AND a.id = '$id';";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$row = mysql_fetch_array($result); 
		   	$nombre_obra = $row['obra'];
		   	$fecha_entrega = $row['fecha_entrega'];
		   	$fecha_captura = $row['fecha_captura'];
		   		$obs = $row['obs'];
		   	$semana = $row['xxano'];
		   	//$id_agrupador =$row['id_agru'];
		   	$nombre_agrupador = $row['nombre_agrupador'];
		   	//$id_partida =$row['id_part'];
		   	$nombre_partida = $row['nombre_partida'];
		   	//$id_area =$row['id_area'];
		   	$nombre_area = $row['nombre_area'];
		   	//$id_especialidad =$row['id_esp'];
		   	$nombre_especialidad = $row['nombre_especialidad'];
			week_bounds($fecha_captura, $start, $end); 	
			$fecha_capturaF = formatFecha($fecha_captura);
			$autorizado=$row['autorizado'];
		    $autoridad=$row['autoridad'];
            if ($autorizado=='0'){$autoridad='Por Autorizar';}
            $solicitante=$row['solicitante'];
$fechau=$row['fecha_utilizacion'];

   if($fechau==null){$fechau='';}

            
	
		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }
           		

  

				require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					$content = '
					<page backbottom="14mm" footer="page">
				<!--		
					    <table width="1240" border="0">
					    	<tr>
					    	    <td width="240">'.$logo.'</td> 
					    		<td height="50" width="500"; style="font-weight:bold;">REQUISICIÓN '.$id.' - REQ</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0" align="center">
					    	<tr>
					    		<td width="133" style="font-weight:bold;">SEMANA '.$semana.'</td>
					    		<td width="300" style="font-weight:bold;">FECHA DE REQUISICIÓN '.$fecha_captura.'</td>
					    		<td width="300" style="font-weight:bold;">FECHA DE ENTREGA '.$fechau.'</td>
					    		<td width="300" style="font-weight:bold;">PERIODO del '.$start.' al '.$end.'</td>
					    	</tr>
					    	
						</table>
					    <table>
					    	<tr>
					    		<td width="200">'.$id_rt.'</td>
					    		<td width="200">'.$nom_tecnico.'</td>
					    	</tr>
					    </table>
					    <table  align="center" width=100% border="0">
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AGRUPADOR</td>
					    		<td width="300">'.$nombre_agrupador.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">ESPECIALIDAD</td>
					    		<td width="300">'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td width="150" style="font-weight:bold;">ÁREA</td>
					    		<td width="300">'.$nombre_area.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">PARTIDA</td>
					    		<td width="300">'.$nombre_partida.'</td>
					    	</tr>
					    </table>
					   	<table>
					    	<tr>
					    		<td height="40"width="1000" align="center" style="font-size:20;font-weight:bold;">REQUISICIÓN</td>
					    	</tr>
					    </table>
				-->
						<br><br><br><br><br><br>
					    <table align="center" border="1">
				            <tr>
				              <td width="240" rowspan="6">'.$logo.'</td> 
				                <td border="0" rowspan="6" width="150"></td>
				                <td border="0" colspan="2" align="center" height="25" style="font-size:14px;"><b>REQUISICIÓN DE MATERIAL</b></td>

				            </tr>
				            <tr>
				                <td border="0" width="155">OBRA:</td>
				                <td border="0" width="625">'.$nombre_obra.'</td>
				            </tr>
				            <tr>
				                <td border="0" >UBICACION</td>
				                <td border="0" >'.$localizacion.'</td>
				            </tr>
				            <tr>
				                <td border="0">Fecha de Pedido: </td>
				                <td border="0" >'.$fecha_capturaF.'</td>
				            </tr>
				              <tr>
				                <td border="0" >Solicito: </td>
				                <td border="0" >'.$solicitante.'</td>
				            </tr>
				            <tr>
				                <td border="0">Autorizo: </td>
				                <td border="0" >'.$autoridad.'</td>
				            </tr>
				            
				        </table>

					    <table align="center" border="1" style="width: 900px;">
					    	<tr>
					    		<td style="width: 100px; font-weight:bold; text-align:center;">CANTIDAD</td>
					    		<td style="width: 100px; font-weight:bold; text-align:center;">UNIDAD</td>
					    		<td style="width: 500px; font-weight:bold; text-align:center;">DESCRIPCIÓN Y ESPECIFICACIÓN</td>
					    		<td style="width: 100px; font-weight:bold; text-align:center;">FECHA REQUERIDA DE ENTREGA</td>					    							    							    		
					    		<td style="width: 100px; font-weight:bold; text-align:center;">SALDO EN RELACION DE MATERIALES</td>
					    	</tr>';
					    			$SQL = "SELECT c.clave, c.unidtext, b.cantidad ,c.descripcion, c.precio, b.fecha_captura, b.id_clave FROM constru_requis a 
									LEFT JOIN constru_especialidad es on es.id=a.id_area
							        LEFT JOIN constru_requisiciones b on b.id_requi=a.id
							        LEFT JOIN constru_insumos c on c.id=b.id_clave
									where a.id_obra='$id_obra' and a.id='$id' and a.borrado=0;";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
										    $idclave=$row['id_clave'];
								    		$clave = $row['clave'];
								    		$unidad = $row['unidtext'];
								    		$cantidad = $row['cantidad'];
								    		$cantidadF = number_format($cantidad,2);
								    		$descripcion = $row['descripcion'];
								    		$fechaE = $row['fecha_captura'];
								    		$precio = $row['precio'];
								    		$precioF = number_format($precio,2);

								    		/* === Cambios Chris */
                                            $SQL2 = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($clave)."' AND borrado=0;";
                                            $result2 = mysql_query( $SQL2 ) or die("Couldn t execute query.".mysql_error());
                                            $row2 = mysql_fetch_array($result2);
                                            $totalproy=$row2['totcant']*1;

                                            $SQL3 = "SELECT if( sum(a.cantidad) is null,0,sum(a.cantidad) ) as vol_est from constru_requisiciones a 
                                            Inner join constru_requis b on b.id=a.id_requi AND b.estatus!=2 where a.id_requi>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_clave='$idclave'
                                            order by a.id DESC LIMIT 1;";
                                            $result3 = mysql_query( $SQL3 ) or die("Couldn t execute query.".mysql_error());
                                            $row3 = mysql_fetch_assoc($result3);
                                            if(mysql_num_rows($result3)>0){
                                                $gastado=$row3['vol_est']*1;
                                            }else{
                                                $gastado=0;
                                            }

                                            $saldo=$totalproy-$gastado;
                                            /* === Fin Cambios Chris */

					    	$content.='
					    	<tr>
					    		<td style="width: 100px; text-align:right;">'.$cantidadF.'</td>
					    		<td style="width: 100px; text-align:center;">'.$unidad.'</td>
					    		<td style="width: 400px; font-size:10px;">'.$descripcion.'</td>
					    		<td style="width: 100px; font-size:12px;">'.substr($fechau,0,10).'</td>					    							    							    		
					    		<td style="width: 100px; text-align:right;">'.$saldo.'</td>
					    	</tr>';}
					    	$content.='
					    </table>
					    Observaciones:'.$obs.'
					    <page_footer>
				<!--
					    	<table border="0">
					    	<tr>
					    		<td height="30" width="500" align="center">FECHA DE ENTREGA</td>
					    		<td width="500" align="center">SOLICITÓ: '.$solicito.'</td>
					    	</tr>
					    	<tr>
					    		<td height="30" width="500" align="center">_________________________</td>
					    		<td width="500" align="center">_________________________</td>
					    	</tr>
					    	<tr>
					    		<td width="500" align="center"></td>
					    		<td width="500" align="center">JEFE DE COMPRAS</td>
					    	</tr>
					    	</table>
						    <table>
						    	<tr>
						    		<td height="50" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">JEFE ADMINISTRATIVO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    </table>
				-->
					    </page_footer>
					</page>';

    
					$html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
}	
if(isset($opcion) && $opcion=='pdfcompras'){
	$id=$_POST['id'];
	$opt=$_POST['opt'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id.'_'.$opt.'_'.$hoy;
	if($opt=='comp'){
				
				$SQL = "SELECT a.estatus,

				 substring(a.fecha_entrega,1,10) as fecha_entrega, b1.id_requis, j.obra, a.id, a.fecha_captura, a.id_prov, e2.razon_social_sp, c1.id_agru, i.nombre as nombre_agrupador, h.especialidad 
				as nombre_especialidad, f.partida as nombre_partida, g.nombre as nombre_area, c1.id_area, c1.id_esp, c1.id_part, 
				d.nombre as nombre_tecnico, d.id as id_tecnico, a.obsgen, fp.nombre as fpnombre, j.horai,j.horaf,j.localizacion, j.director, k.nombre nombreDF, CONCAT('RFC: ', k.rfc, ' ',k.domicilio,' Col. ', k.colonia, ' ', k.ciudad) datosF, a.condpago, j.telefono, substring(a.fecha_entrega,12,5) as hora FROM constru_pedis a
				LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
				LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
				LEFT JOIN constru_requisiciones b on b.id_requi=c1.id and b.borrado=0
				LEFT JOIN constru_insumos c on c.id=b.id_clave
				left JOIN constru_info_tdo d on d.id_alta=a.solicito
				left JOIN constru_info_sp e2 on e2.id_alta=a.id_prov
				LEFT JOIN constru_especialidad es on es.id=c1.id_area
				left JOIN constru_partida e on e.id=c1.id_part
				left JOIN constru_cat_partidas f on f.id=e.id_cat_partida
				left JOIN constru_area g on g.id=c1.id_esp
				left JOIN constru_cat_especialidad h on h.id=g.id_cat_especialidad
				left JOIN constru_agrupador i on i.id=c1.id_agru
				left JOIN constru_generales j on j.id=a.id_obra
				left join forma_pago fp on fp.idFormapago=a.fpago
				left JOIN constru_contratista k on k.id=j.construye
				WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 and a.id = '$id';";
				$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
				$row = mysql_fetch_array($result); 
			    $nombre_obra = $row['obra'];
			    $id_requis = $row['id_requis'];
			    $id = $row['id'];
			    $fecha_entrega = $row['fecha_entrega'];
			    $fecha_captura = $row['fecha_captura'];
			    $id_prov =$row['id_prov'];
			    $nombre_proveedor=$row['razon_social_sp'];
			    $id_tecnico =$row['id_tecnico'];
			    $nombre_tecnico=$row['nombre_tecnico'];
			    $id_agrupador =$row['id_agru'];
			    $nombre_agrupador =$row['nombre_agrupador'];
			    $id_especialidad =$row['id_esp'];
			    $nombre_especialidad =$row['nombre_especialidad'];
			    $id_partida =$row['id_part'];
			    $nombre_partida =$row['nombre_partida'];
			    $id_area =$row['id_area'];
			    $nombre_area =$row['nombre_area'];
			    $obsgen =$row['obsgen'];
			    $fpnombre =$row['fpnombre'];
			    $datosF =$row['datosF'];
			    $nombreDF =$row['nombreDF'];
			    $localizacion =$row['localizacion'];
			    $director =$row['director'];
			    $telefono =$row['telefono'];
			    $condpago =$row['condpago'];
			    $hora =$row['hora'];
			    $horai=$row['horai'];
			    $horaf=$row['horaf'];

			    $esta=$row['estatus'];
			    if($esta=='3'){
			    	$nameEsta='AUTORIZADA';
			    }else if($esta=='2'){
			    	$nameEsta='CANCELADA';
			    }else if($esta=='1'){
			    	$nameEsta='PENDIENTE';
			    }else{
			    	$nameEsta='';
			    }

			    
			    if($obsgen==null){
			    	$obsgen=' ';
			    }
			    if($fpnombre==null){
			    	$fpnombre=' No identificada ';
			    }

			    $semana = getweek($fecha_captura);
			    week_bounds($fecha_captura, $start, $end); 

			    $fecha_capturaF = formatFecha($fecha_captura);
                  
    
		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }

           		

				require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					$content = '
					<page backbottom="14mm" footer="page">
					<br><br><br><br><br>
						<table border="1">
							<tr>
							
								<td border="0" width="240">'.$logo.'</td> <!-- logo-->
								<td border="0" width="190" align="center">ORDEN DE COMPRA <br>'.$nameEsta.'</td>
								<td border="0" width="310">
									<table>
										<tr>
											<td>ORDEN DE COMPRA:</td>
											<td width="160" align="center" style="font-weight:bold;">OC-'.$id.'</td>
										</tr>
										<tr>
											<td>OBRA:</td>
											<td width="160" align="center" style="font-size:12;font-weight:bold;"">'.$nombre_obra.'</td>
										</tr>
											<tr>
											<td>Partida:</td>
											<td width="160" align="center" style="font-size:12;font-weight:bold;"">'.$nombre_partida.'</td>
										</tr>
										<tr>
											<td>FECHA:</td>
											<td width="160" align="center" style="font-weight:bold;">'.$fecha_capturaF.'</td>
										</tr>
									
									
									</table>
								</td>
							</tr>
						</table>
						<br>
						<table border="1">
							<tr>
								<td border="0" width="37">
									<table>	
										<tr>
											<td align="center" width="370">PROVEEDOR ASIGNADO</td>
										</tr>
										<tr>
											<td align="center" style="font-weight:bold;">'.$nombre_proveedor.'</td>
										</tr>
										<tr>
											<td align="center">Vendedor: '.$var.'</td>
										</tr>
									</table>
								</td>
								<td border="0" width="370">
									<table>	
										<tr>
											<td align="center" width="360">DATOS DE FACTURACIÓN:</td>
										</tr>
										<tr>
											<td align="center" style="font-weight:bold;">'.$nombreDF.'</td>
										</tr>
										<tr>
											<td width="350">'.$datosF.'</td>
										</tr>
										<tr>
											<td align="center"> Metodo de Pago: '.$fpnombre.'</td>
										</tr>
										<tr>
											<td align="center"> Cuenta de Pago: '.$var.'</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table border="1">
							<tr>
								<td width="100">Cantidad</td>
								<td width="100">Unidad</td>
								<td width="260">Descripción</td>
								<td width="122">Precio Unit.</td>
								<td width="122">Importe</td>
							</tr>';
									
					    			$SQL = "SELECT c.clave, c.descripcion, b.cantidad, c.unidtext, c.precio, b1.fecha_captura, b.precio_compra,
										if(b.elprov is null,a.id_prov,b.elprov) as prreal, e2.id_alta as prrep, a.id pedis, b.id_pedido, CASE a.estatus 
										WHEN 1 THEN 'Pendiente'
										WHEN 2 THEN 'Cancelada'
										WHEN 3 THEN 'Autorizada'
										END as estatus
					    			 FROM constru_pedis a
										LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
										LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
										LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
										LEFT JOIN constru_insumos c on c.id=b.id_clave
										-- left JOIN constru_info_tdo d on d.id_alta=a.solicito
										left JOIN constru_info_sp e2 on e2.id_alta=a.id_prov
										WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 and a.id='$id';";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									$total = 0;
									while($row = mysql_fetch_array($result)) {
										if($row['prreal']!=$row['prrep']){
											continue;
										}

										if($row['id_pedido']>0){
											if($row['pedis']!=$row['id_pedido'] && $row['estatus']!='Cancelada'){
												continue;
											}
										}

										/*if($row['cantvalida']==null){
											continue;
										}*/
								    		$clave = $row['clave'];
								    		$descripcion = $row['descripcion'];
								    		$cantidad = $row['cantidad'];
								    		$cantidadF =number_format($cantidad,2);
								    		$unidad = $row['unidtext'];
								    		$fecha_captura = $row['fecha_captura'];	

								    		$precio_concurso = $row['precio'];
								    		$importe_concurso = $cantidad * $precio_concurso;
								    		$total_concurso = $importe_concurso + $total_concurso;	
								    		$precio_concursoF = number_format($precio_concurso,2);
								    		$importe_concursoF = number_format($importe_concurso,2);
								    		$total_concursoF = number_format($total_concurso,2);

								    		$precio_compra = $row['precio_compra'];
								    		$importe_compra = $cantidad * $precio_compra;
								    		$total_compra = $importe_compra + $total_compra;
								    		$precio_compraF = number_format($precio_compra,2);
								    		$importe_compraF = number_format($importe_compra,2);
								    		$total_compraF = $total_compra;

								    		$subtotal=$total_compraF/1.16;
								    		$precioiva=$total_compraF*0.16;
								    		$totalt=($precioiva+$total_compraF);


								    																		
					    	$content.='
							<tr>
								<td style="text-align:right;">'.$cantidadF.'</td>
								<td style="text-align:center">'.$unidad.'</td>
								<td width="265"style="font-size:10px;">'.$descripcion.'</td>
								<td style="text-align:right;">$'.$precio_compraF.'</td>
								<td style="text-align:right;">$'.$importe_compraF.'</td>
					    	</tr>';}
					    	$content.='
					    
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >SUBTOTAL</td>
					    		<td style=" text-align:right;">$'.number_format($total_compraF,2).'</td>					    		
					    	</tr>
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >IVA % 16.0</td>
					    		<td style=" text-align:right;">$'.number_format($precioiva,2).'</td>					    		
					    	</tr>
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >TOTAL</td>
					    		<td style=" text-align:right;">$'.number_format($totalt,2).'</td>					    		
					    	</tr>
					    
					    <!--
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td >TOTAL</td>
					    		<td style=" text-align:right;">$'.number_format($totalt,2).'</td>					    		
					    	</tr>-->';

					    	$content.='
						</table>
						<table border="1">
							<tr>
								<td width="370">Condiciones de pago:</td>
								<td width="370">'.$condpago.'</td>
							</tr>
							<tr>
								<td width="370">Direccion de entrega:</td>
								<td width="370">'.$localizacion.'</td>
							</tr>
							<tr>
								<td width="370">Contacto en obra y telefono:</td>
								<td width="370">'.$director.' '.$telefono.'</td>
							</tr>
							<tr>
								<td width="370">Fecha de suministro:</td>
								<td width="370">'.$fecha_entrega.'</td>
							</tr>
							<tr>
								<td width="370">Horario de entrega:</td>
								<td width="370">'.substr($horai,0,5).' - '.substr($horaf,0,5).'</td>
							</tr>
							<tr>
								<td colspan="2">Observaciones adicionales: '.$obsgen.'</td>
							</tr>
						</table>
		
					    <page_footer>
						    <table>
						    	<tr>
						    		<td height="30" width="250" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="250" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="250" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="250" align="center" style="font-weight:bold;">COMPRAS</td>
						    		<td width="250" align="center" style="font-weight:bold;">DIRECCIÓN</td>
						    		<td width="250" align="center" style="font-weight:bold;">Firma de PROVEEDOR</td>
						    	</tr>
		<!--
						    	<tr>
						    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">JEFE ADMINISTRATIVO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    	<tr>
						    		<td VALIGN=bottom height="40" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td style="font-weight:bold;" height="30" width="330" align="center">JEFE DE COMPRAS</td>
						    	</tr>
		-->
						    </table>
					    </page_footer>
					</page>';

    
					$html2pdf = new HTML2PDF('A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
}
if(isset($opcion) && $opcion=='pdfsalidas'){
	$id=$_POST['id'];
	$id_salida=$_POST['id_sal'];
	$opt=$_POST['opt'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id.'_'.$opt.'_'.$hoy;
	if($opt=='salida'){

		$SQL = "SELECT a.observaciones, f.obra, a.id_recibio, a.id_autorizo, a.id_entrego, a.id_agru, a.id_esp, a.id_part
, a.id_area, 
		if(g.nombre is null,sp.razon_social_sp,concat (g.nombre, ' ', g.paterno, ' ', g.materno) ) nombre_recibio,
		-- concat (g.nombre, ' ', g.paterno, ' ', g.materno) nombre_recibio, 
		-- sp.razon_social_sp nombre_autorizo, 
		concat (emp.nombre, ' ', emp.apellido1, ' ', emp.apellido2) nombre_autorizo, 
		concat (i.nombre, ' ', i.paterno, ' ', i.materno) nombre_entrego, 
		 o.partida,
		 a.fecha, n.id_cat_especialidad
,
		j.nombre as nombre_agrupador, k.nombre as nombre_partida, l.nombre as nombre_area, m.especialidad as
 nombre_especialidad
		from constru_bit_salidas a
		
		LEFT JOIN constru_generales f on f.id = a.id_obra
		left JOIN constru_info_tdo g on g.id_alta = a.id_recibio
		LEFT JOIN constru_info_sp sp on sp.id_alta = a.id_recibio
		left JOIN constru_info_tdo i on i.id_alta = a.id_entrego
		LEFT JOIN constru_agrupador j on j.id = a.id_agru
		LEFT JOIN constru_partida k on k.id = a.id_part
		LEFT JOIN constru_especialidad l on l.id = a.id_area
		LEFT JOIN constru_area n on n.id = a.id_esp
		LEFT JOIN constru_cat_especialidad m on m.id = n.id_cat_especialidad
		LEFT JOIN constru_cat_partidas o on o.id = k.id_cat_partida
		left JOIN empleados emp on emp.idempleado = a.id_autorizo
		WHERE a.borrado=0 AND a.id_obra='$id_obra' and a.id='$id_salida';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row = mysql_fetch_array($result); 
	    $nombre_obra = $row['obra'];
	    $fecha_captura= $row['fecha'];
	        if (substr($fecha_captura,-8)=='00:00:00'){
         $fecha_captura=substr($fecha_captura,0,10);

        }
	    $id_tecnico_recibio= $row['id_recibio'];
	    $id_tecnico_autorizo= $row['id_autorizo'];
	    $id_tecnico_entrego= $row['id_entrego'];
	    $nombre_tecnico_recibio=$row['nombre_recibio'];
	    $nombre_tecnico_autorizo=$row['nombre_autorizo'];
	    $nombre_tecnico_entrego=$row['nombre_entrego'];
	    $id_agrupador =$row['id_agru'];
	    $id_especialidad =$row['id_esp'];
	    $id_partida =$row['id_part'];
	    $id_area =$row['id_area'];
	    $nombre_agrupador =$row['nombre_agrupador'];
	    $nombre_partida =$row['partida'];
	    $nombre_area =$row['nombre_area'];
	    $nombre_especialidad =$row['nombre_especialidad'];
	    $observaciones = $row['observaciones'];


		
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					$content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    		<td height="50" width="500"; style="font-weight:bold;">SAL - '.$id_salida.'</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		
					    		<td width="333" style="font-weight:bold;">FECHA Orden de Compra '.$fecha_captura.'</td>
					    	</tr>
						</table>
					    <table>
					    	<tr>
					    		<td width="65">RECIBIÓ: </td>
					    		<td width="250">'.$nombre_tecnico_recibio.'</td>
					    		<td width="75">AUTORIZÓ: </td>
					    		<td width="250">'.$nombre_tecnico_autorizo.'</td>
					    		<td width="75">ENTREGÓ: </td>
					    		<td width="250">'.$nombre_tecnico_entrego.'</td>
					    	</tr>
					    </table>
					    <table width=100% border="0">
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AGRUPADOR</td>
					    		<td width="300">'.$nombre_agrupador.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">ESPECIALIDAD</td>
					    		<td width="200">'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td width="150" style="font-weight:bold;">PARTIDA</td>
					    		<td width="300">'.$nombre_partida.'</td>

					    		<td width="150" style="font-weight:bold;">AREA</td>
					    		<td width="200">'.$nombre_area.'</td>
					    	</tr>
					    </table>
					   	<table>
					    	<tr>
					    		<td height="30"width="1000" align="center" style="font-size:20;font-weight:bold;">VALE DE SALIDA</td>
					    	</tr>
					    </table>
					    <table align="center" border="1">
					    	<tr>
					    		<td style="width: 200px; font-weight:bold; text-align:center;">CLAVE</td>
					    		<td style="width: 500px; font-weight:bold; text-align:center;">DESCRIPCIONES</td>
					    		<td style="width: 100px; font-weight:bold; text-align:center;">UNIDAD</td>
					    		<td style="width: 100px; font-weight:bold; text-align:center;">CANTIDAD</td>
					    	</tr>';
					    			$SQL = "SELECT e.clave, e.descripcion, e.unidtext, b.salio as cantidad
									from constru_bit_salidas a
									inner join constru_salida_almacen b on b.id_bit_salida=a.id AND b.id_oc=a.id_oc
									LEFT JOIN constru_requis c  on c.id=b.id_req AND c.id=b.id_req
									LEFT JOIN constru_requisiciones d on d.id_requi=c.id AND d.id_clave=b.id_insumo AND d.borrado=0
									LEFT JOIN constru_insumos e on e.id=d.id_clave AND e.id=b.id_insumo
									WHERE a.borrado=0 AND a.id_obra= '$id_obra' and a.id_oc = '$id' and a.id = '$id_salida' ORDER BY b.id asc;";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$clave = $row['clave'];	
								    		$descripcion = $row['descripcion'];
								    		$unidad = $row['unidtext'];
								    		$cantidad = $row['cantidad'];
								    		$cantidadF = number_format($cantidad, 2);
								    														
					    	$content.='
							<tr>
					    		<td style="width: 200px;">'.$clave.'</td>
					    		<td style="width: 500px;">'.$descripcion.'</td>
					    		<td style="width: 100px; text-align:center;">'.$unidad.'</td>
					    		<td style="width: 100px; text-align:right;">'.$cantidadF.'</td>
					    	</tr>';}
					    	$content.='
					    </table>
					    <table>
					    	<tr>
					    		<td height="10"></td>
					    	</tr>
					    	<tr>
					    		<td style="width: 120px;">OBSERVACIONES: </td>
					    		<td style="width: 600px;">'.$observaciones.'</td>
					    	</tr>
					    </table>
					        <page_footer> 
						       <table align="center">
							    	<tr>
							    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
							    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
							    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
							    	</tr>
							    	<tr>
							    		<td width="300" align="center" style="font-weight:bold;">Jefe de Almacen</td>
							    		<td width="300" align="center" style="font-weight:bold;">Tecnico de Obra</td>
							    		<td width="300" align="center" style="font-weight:bold;">Proveedor</td>
							    	</tr>
							    	<tr>
							    		<td width="300" align="center" style="font-weight:bold;">RECIBIÓ</td>
							    		<td width="300" align="center" style="font-weight:bold;">AUTORIZÓ</td>
							    		<td width="300" align="center" style="font-weight:bold;">ENTREGÓ</td>
							    	</tr>
							    </table>
						    </page_footer> 
					</page>';

    
					$html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
}	
if(isset($opcion) && $opcion=='pdfentradas'){
	$id_ent=$_POST['id'];
	$id_entrada=$_POST['id_ent'];
	$opt=$_POST['opt'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id_entrada.'_'.$opt.'_'.$hoy;
	if($opt=='entradas'){

	 /*$SQL = "SELECT a.id, a.id_obra, a.id_oc, a.id_almacenista, a.fecha, 
			b.id_req, b.fecha_entrada, b.llego, b.id_insumo, 
			c.solicito, c.fecha_captura, 
			d.id_clave, d.cantidad, d.precio_compra, 
			e.clave, e.descripcion, e.unidad, e.precio, e.unidtext, 
			j.nombre as agrupador,  
			m.especialidad, 
			n.nombre as area, 
			o.partida,
			concat(p.nombre, ' ', p.paterno, ' ', p.materno) as almacenista,
			f.obra
			FROM constru_bit_entradas a
			inner join constru_entrada_almacen b on b.id_oc = a.id_oc
			LEFT JOIN constru_requis c on c.id = b.id_req
			LEFT JOIN constru_requisiciones d on d.id_requi = c.id
			LEFT JOIN constru_insumos e on e.id=d.id_clave AND e.id=b.id_insumo
			LEFT JOIN constru_generales f on f.id = c.id_obra
			LEFT JOIN constru_agrupador j on j.id = c.id_agru
			LEFT JOIN constru_partida k on k.id = c.id_part
			LEFT JOIN constru_especialidad l on l.id = c.id_area
			LEFT JOIN constru_area n on n.id = c.id_esp
			LEFT JOIN constru_cat_especialidad m on m.id = n.id_cat_especialidad
			LEFT JOIN constru_cat_partidas o on o.id = k.id_cat_partida
			LEFT JOIN constru_info_tdo p on p.id_alta = a.id_almacenista
			WHERE a.borrado=0 AND a.id_obra='$id_obra' AND a.id = '$id_entrada';";*/

			$SQL = "SELECT a.id, a.id_obra, a.id_oc, a.id_almacenista, a.fecha, 
			concat (emp.nombre, ' ', emp.apellido1, ' ', emp.apellido2) almacenista, 
			f.obra,i.razon_social_sp as pnom,a.observaciones
			FROM constru_bit_entradas a
			LEFT JOIN constru_generales f on f.id = a.id_obra
			LEFT JOIN empleados emp on emp.idempleado = a.id_almacenista
			LEFT JOIN constru_pedis p on p.id=a.id_oc
			LEFT JOIN constru_info_sp i on i.id_alta=p.id_prov
			WHERE a.borrado=0 AND a.id_obra='$id_obra' AND a.id = '$id_entrada';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row = mysql_fetch_array($result); 
	    $nombre_obra = $row['obra'];
	    $fecha_captura= $row['fecha'];
        if (substr($fecha_captura,-8)=='00:00:00'){
         $fecha_captura=substr($fecha_captura,0,10);

        }

	    $almacenista= $row['almacenista'];
	    $nombre_agrupador= $row['agrupador'];
	    $nombre_especialidad= $row['especialidad'];
	    $nombre_partida= $row['partida'];
	    $nombre_area= $row['area'];
        $nombre_proveedor=$row['pnom'];
              $obs=$row['observaciones'];

		
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					$content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    		<td height="50" width="500"; style="font-weight:bold;">ENT - '.$id_entrada.'</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		
					    		<td width="333" style="font-weight:bold;">FECHA Entrada '.$fecha_captura.'</td>
					    	</tr>
						</table>
					    <table>
					    	<tr>
					    		<td width="100" style="font-weight:bold;">ALMACENISTA: </td>
					    		<td width="250">'.$almacenista.'</td>
					    		
					    	</tr>
					    </table>
					     <table>
					    	<tr>
					    		
					    		<td width="100" style="font-weight:bold;">PROVEEDOR: </td>
					    		<td width="250">'.$nombre_proveedor.'</td>
					    	</tr>
					    </table>
					    <!--
					    <table width=100% border="0">
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AGRUPADOR</td>
					    		<td width="300">'.$nombre_agrupador.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">ESPECIALIDAD</td>
					    		<td width="200">'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td width="150" style="font-weight:bold;">PARTIDA</td>
					    		<td width="300">'.$nombre_partida.'</td>

					    		<td width="150" style="font-weight:bold;">AREA</td>
					    		<td width="200">'.$nombre_area.'</td>
					    	</tr>
					    </table>
					    -->
					   	<table>
					    	<tr>
					    		<td height="30"width="1000" align="center" style="font-size:20;font-weight:bold;">VALE DE ENTRADA</td>
					    	</tr>
					    </table>
					    <table align="center" border="1">
					    	<tr>
					    		<td style="width: 200px; font-weight:bold; text-align:center;">CLAVE</td>
					    		<td style="width: 350px; font-weight:bold; text-align:center;">DESCRIPCIONES</td>
					    		<td style="width: 80px; font-weight:bold; text-align:center;">UNIDAD</td>
					    		<td style="width: 90px; font-weight:bold; text-align:center;">CANTIDAD</td>
					    		<td style="width: 90px; font-weight:bold; text-align:center;">CANTIDAD DE ENTRADA</td>
					    		<!--<td style="width: 90px; font-weight:bold; text-align:center;">PRECIO</td>
					    		<td style="width: 90px; font-weight:bold; text-align:center;">IMPORTE</td>-->
					    	</tr>';
					    			$SQL = "SELECT e.clave, e.descripcion, e.unidtext,  d.cantidad cantidad, b.llego as cantidade,
										d.precio_compra as precio, d.precio_compra*b.llego as importec
										from constru_bit_entradas a
										inner join constru_entrada_almacen b on b.id_bit_entrada=a.id AND b.id_oc=a.id_oc
										LEFT JOIN constru_requis c  on c.id=b.id_req AND c.id=b.id_req
										LEFT JOIN constru_requisiciones d on d.id_requi=c.id AND d.id_clave=b.id_insumo
										LEFT JOIN constru_insumos e on e.id=d.id_clave AND e.id=b.id_insumo
										WHERE a.borrado=0 AND a.id_obra= '$id_obra' AND a.id = '$id_entrada' ORDER BY  a.id_oc desc, a.id desc;";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$clave = $row['clave'];	
								    		$descripcion = $row['descripcion'];
								    		$unidad = $row['unidtext'];
								    		$cantidad = $row['cantidad'];
								    		$cantidade = $row['cantidade'];
								    		$precio = $row['precio'];
								    		$precioF = number_format($precio,2);
								    		$importec = $row['importec'];
								    		$importecF = number_format($importec,2);
								    		$total = $total + $importec;
								    		$totalF = number_format($total,2);

								    														
					    	$content.='
							<tr>
					    		<td style="width: 200px;">'.$clave.'</td>
					    		<td style="width: 350px;">'.$descripcion.'</td>
					    		<td style="width: 80px; text-align:center;">'.$unidad.'</td>
					    		<td style="width: 90px; text-align:right;">'.$cantidad.'</td>
					    		<td style="width: 90px; text-align:right;">'.$cantidade.'</td>
					    		<!--<td style="width: 90px; text-align:right;">$'.$precioF.'</td>
					    		<td style="width: 90px; text-align:right;">$'.$importecF.'</td>-->
					    	</tr>';}
					    	$content.='
					    	<tr>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<!--<td style="width: 90px; text-align:right; font-weight:bold;">TOTAL</td>-->
					    		<td border="0"></td>
					    		<td border="0"></td>
					    		<!--<td style="width: 90px; text-align:right; font-weight:bold;">$'.$totalF.'</td>-->
					    	</tr>';
					    	$content.='
					    </table>
					    Observaciones:'.$obs.'
					        <page_footer> 
						       <table align="center">
							    	<tr>
							    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
							    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
							    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
							    	</tr>
							    	<tr>
							    		<td width="300" align="center" style="font-weight:bold;">Jefe de Almacen</td>
							    		<td width="300" align="center" style="font-weight:bold;">Tecnico de Obra</td>
							    		<td width="300" align="center" style="font-weight:bold;">Proveedor</td>
							    	</tr>
							    	<tr>
							    		<td width="300" align="center" style="font-weight:bold;">RECIBIÓ</td>
							    		<td width="300" align="center" style="font-weight:bold;">AUTORIZÓ</td>
							    		<td width="300" align="center" style="font-weight:bold;">ENTREGÓ</td>
							    	</tr>
							    </table>
						    </page_footer> 
					</page>';

    
					$html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
			
}	
if(isset($opcion) && $opcion=='pdf_est_sub'){

	$id_est =$_POST['id_est'];
	$id_sub = $_POST['id_sub'];
	$opt = $_POST['opt'];
	$nombre_agrupador =$_POST['agru'];
	$hoy = date('m_d_y');
	$nom_pdf = $id_est.'_'.$opt.'_'.$hoy;
	if($opt == 'sub')
	{
		$SQL = "SELECT  b.nombre nombre_agrupador, c.nombre nombre_area, 
		dd.especialidad nombre_especialidad, ee.partida nombre_partida, 
		f.cargo, i.obra, concat('RT-',g.id,' ',h.nombre,' ',h.paterno,' ',h.materno) as tenico, 
		a.id as idest, a.imp_estimacion, a.amortizado_estimacion, a.subtotal1, a.fondo_garantia, a.retencion, a.factura,
		a.cargos, a.subtotal2, a.iva, a.total, a.fecha, a.imp_contrato, a.ade1, a.ade2, a.ade3 , a.imp_tot_contrato, 
		a.anticipo, a.amortizado_anterior, a.amortizado_estimacion, a.tot_amortizado, a.por_amortizar, j.anticipo anticipo_por, a.xxano, a.id_subcontratista, sub.razon_social_sp,
		u.usuario as solicitante, u2.usuario as autoridad,a.estatus as autorizado FROM constru_estimaciones_bit_subcontratista a 
		      left join accelog_usuarios u on u.idempleado=a.id_autorizo
              left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
			  left join constru_agrupador b on b.id=a.id_agru
			  left join constru_especialidad c on c.id=a.id_area
			  left join constru_area d on d.id=a.id_esp
			  left join constru_cat_especialidad dd on dd.id=d.id_cat_especialidad
			  left join constru_partida e on e.id=a.id_part 
			  left join constru_cat_partidas ee on ee.id=e.id_cat_partida 
			  left join constru_cuentas_cargo f on f.id=a.id_cc
			  left join constru_altas g on g.id=a.id_autorizo
			  left join constru_info_tdo h on h.id_alta=g.id
			  left join constru_info_sp sub on sub.id_alta=a.id_subcontratista
			  left join constru_generales i on i.id= a.id_obra
			  left join constru_info_sp j on j.id_alta=a.id_subcontratista
		where a.id_obra='$id_obra' and a.borrado=0 AND a.id='$id_est'";

		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row = mysql_fetch_array($result); 


		//total amortizado
		$SQL = "SELECT if(sum(amortizado_estimacion) is null,0,sum(amortizado_estimacion)) as totamor from constru_estimaciones_bit_subcontratista where id_obra='$id_obra' and estatus=1 and id_subcontratista='".$row['id_subcontratista']."' and id<='$id_est' ;";
		  $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row5 = mysql_fetch_array($result); 

		//Amortizado anterior al id seleccionado
		$SQL = "SELECT if(sum(amortizado_estimacion) is null,0,sum(amortizado_estimacion)) as amorant from constru_estimaciones_bit_subcontratista where id_obra='$id_obra' and estatus=1 and id<'$id_est' and id_subcontratista='".$row['id_subcontratista']."';";
		  $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row6 = mysql_fetch_array($result); 

		  $poramor = $row['anticipo']-$row5['totamor'];

	   	$nombre_obra = $row['obra'];
	   	$nombre_agrupador = $row['nombre_agrupador'];
	   	$nombre_partida = $row['nombre_partida'];
	   	$nombre_area = $row['nombre_area'];
	   	$nombre_especialidad = $row['nombre_especialidad'];
	   	$cargo = $row['cargo'];
	   	$imp_estimacionT = $row['imp_estimacion'];
	   	$amortizado_estimacion = $row['amortizado_estimacion'];
	   	$subtotal1 = $row['subtotal1'];
	   	$fondo_garantia = $row['fondo_garantia'];
	   	$retencion = $row['retencion'];
	   	$cargos = $row['cargos'];
	   	$subtotal2 = $row['subtotal2'];
	   	$iva = $row['iva'];
	   	$total = $row['total'];
	   	$fecha = $row['fecha'];
	   	$tenico = $row['tenico'];
	   	$factura = $row['factura'];
	   	$imp_contrato = $row['imp_contrato'];
	   	$ade1 = $row['ade1'];
	   	$ade2 = $row['ade2'];
	   	$ade3 = $row['ade3'];
	   	$imp_tot_contrato = $row['imp_tot_contrato'];
	   	$anticipo = $row['anticipo'];
	   	$amortizado_anterior = $row6['amorant'];
	   	$amortizado_estimacion = $row['amortizado_estimacion'];
	   	$tot_amortizado = $row5['totamor'];
	   	$por_amortizar = $poramor;
	   	$anticipo_por = $row['anticipo_por'];
	   	$semana = $row['xxano'];
	   	$razon_social_sp = $row['razon_social_sp'];
	   	$imp_estimacionTF = number_format($imp_estimacionT,4);
	   	$amortizado_estimacionF = number_format($amortizado_estimacion,4);
	   	$subtotal1F = number_format($subtotal1,4);
	   	$fondo_garantiaF = number_format($fondo_garantia,4);
	   	$retencionF = number_format($retencion,4);
	   	$cargosF = number_format($cargos,4);
	   	$subtotal2F = number_format($subtotal2,4);
	   	$ivaF = number_format($iva,4);
	   	$totalF = number_format($total,4);
	   	$imp_contratoF = number_format($imp_contrato,4);
	   	$ade1F = number_format($ade1,4);
	   	$ade2F = number_format($ade2,4);
	   	$ade3F = number_format($ade3,4);
	   	$imp_tot_contratoF = number_format($imp_tot_contrato,4);
	   	$anticipoF = number_format($anticipo,4);
	   	$amortizado_anteriorF = number_format($amortizado_anterior,4);
	   	$amortizado_estimacionF = number_format($amortizado_estimacion,4);
	   	$por_amortizarF = number_format($por_amortizar,4);
	   	$tot_amortizadoF = number_format($tot_amortizado,4);
	   	$autorizado=$row['autorizado'];
        $autoridad=$row['autoridad'];
        if ($autorizado=='0'){$autoridad='Por Autorizar';}
        $solicitante=$row['solicitante'];

  
		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }

	   	week_bounds($fecha, $start, $end); 

		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					    $content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    	<td border="0" width="240">'.$logo.'</td> <!-- logo-->
					    		<td height="50" width="500"; style="font-weight:bold;">ESTIMACIÓN -'.$id_est.'</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		<td width="333" style="font-weight:bold;">SEMANA '.$semana.'</td>
					    		<td width="333" style="font-weight:bold;">FECHA DE ESTIMACIÓN '.$fecha.'</td>
					    		<td width="333" style="font-weight:bold;">PERIODO '.$start.' al '.$end.'</td>
					    	</tr>
					    	<tr>
					    	<td width="333" style="font-weight:bold;">Solicito: '.$solicitante.'</td>
   	                        <td width="333" style="font-weight:bold;">Autorizo: '.$autoridad.'</td>
   	                        <td width="333" style="font-weight:bold;">Subcontratista: '.$razon_social_sp.'</td>
   	                        </tr>
						</table>
						<!--
					    <table align="center" width=100% border="0">
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AGRUPADOR</td>
					    		<td width="150">'.$nombre_agrupador.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">ESPECIALIDAD</td>
					    		<td width="150">'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AREA</td>
					    		<td width="250">'.$nombre_area.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">PARTIDA</td>
					    		<td width="250">'.$nombre_partida.'</td>
					    	</tr>
					    </table>
					    -->
					   	<table>
					    	<tr>
					    		<td height="20"width="1000" align="center" style="font-size:20;font-weight:bold;">ESTIMACIÓN SUBCONTRATISTAS</td>
					    	</tr>
					    </table>
					    <table border="1" style="width: 1100px;>
					    	<tr>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Clave</td>
					    		<td style="width: 300px; font-weight:bold; text-align:center;">Descripción</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Unidad</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Vol. Tope</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">PU destajo</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Importe</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol Anterior</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Vol Estimado</td>
					    		<td style="width: 70px; font-weight:bold; text-align:center;">Vol Acumulado</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol Ejecutar</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Imp. Estimación</td>
					    	</tr>';
					    			$SQL = "SELECT c.codigo, c.descripcion, c.unidtext, d.vol_tope, c.pu_subcontrato, a.vol_anterior, a.vol_estimacion
					    			from constru_estimaciones_bit_subcontratista b
											    inner join constru_estimaciones_subcontratista a on a.id_bit_subcontratista=b.id
											    left join constru_recurso c on c.id=a.id_insumo
											    left join constru_vol_tope d on d.id_clave=c.id AND (d.id_area=a.id_area or d.id_area=a.id_area)
											    WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_subcontratista='$id_est';";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$clave = $row['codigo'];	
								    		$descripcion = $row['descripcion'];
								    		$unidad = $row['unidtext'];	
								    		$vol_tope=$row['vol_tope'];
								    		$vol_topeF = number_format($vol_tope,4);
								    		$pu_subcontrato=$row['pu_subcontrato'];
								    		$pu_subcontratoF = number_format($pu_subcontrato,4);
								    		$vol_anterior=$row['vol_anterior'];
								    		$vol_anteriorF = number_format($vol_anterior,4);
								    		$vol_est=$row['vol_estimacion'];	
								    		$importe = $pu_subcontrato * $vol_tope;
								    		$importeF = number_format($importe,4);
								    		$vol_acumulado = $vol_anterior + $vol_est;
								    		$vol_acumuladoF = number_format($vol_acumulado,4);
								    		$vol_ejecutar = $vol_tope - $vol_acumulado;
								    		$imp_estimacion = $pu_subcontrato * $vol_est;
								    		$imp_estimacionF = number_format($imp_estimacion,4);
								    		$total_imp = $total_imp + $importe;
								    		$total_impF = number_format($total_imp,4);
								    		$total_imp_est = $total_imp_est + $imp_estimacion;
								    		$total_imp_estF = number_format($total_imp_est,4);
														
					    	$content.='
							<tr>
								<td style="width: 65px; font-size:10px;">'.$clave.'</td>
								<td style="width: 300px; font-size:10px;">'.$descripcion.'</td>
								<td style="width: 65px; text-align:center;">'.$unidad.'</td>
								<td style="width: 65px; text-align:right;">'.$vol_topeF.'</td>
								<td style="width: 60px; text-align:right;">$'.$pu_subcontratoF.'</td>
								<td style="width: 85px; text-align:right;">$'.$importeF.'</td>
								<td style="width: 60px; text-align:right;">'.$vol_anteriorF.'</td>
								<td style="width: 65px; text-align:right;">'.$vol_est.'</td>
								<td style="width: 70px; text-align:right;">'.$vol_acumuladoF.'</td>
								<td style="width: 60px; text-align:right;">'.$vol_ejecutar.'</td>
								<td style="width: 85px; text-align:right;">$'.$imp_estimacionF.'</td>
								
					    	</tr>'
					    	;}
					    	$content.='
					    	<tr>
					    		<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 60px; text-align:right;">Total:</td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$total_impF.'</td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$total_imp_estF.'</td>
					    	</tr>
					    </table>
					    <br>
						<table border="1">
							<tr>
								<td>
									<table>
								    	<tr>
								    		<td height="30" colspan="5">Estimacion</td>
								    	</tr>
								    	<tr>
								    		<td>Importe de contrato:</td>
								    		<td style="width: 100px; text-align:right;">$'.$imp_contratoF.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Anticipo '.$anticipo_por.'%</td>
								    		<td style="width: 100px; text-align:right;">$'.$anticipoF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Adendum 1:</td>
								    		<td style="width: 100px; text-align:right;">$'.$ade1F.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Importe amortizado anterior:</td>
								    		<td style="width: 100px; text-align:right;">$'.$amortizado_anteriorF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Adendum 2:</td>
								    		<td style="width: 100px; text-align:right;">$'.$ade2F.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Importe amortizado estimacion:</td>
								    		<td style="width: 100px; text-align:right;">$'.$amortizado_estimacionF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Adendum 3:</td>
								    		<td style="width: 100px; text-align:right;">$'.$ade3F.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Total amortizado:</td>
								    		<td style="width: 100px; text-align:right;">$'.$tot_amortizadoF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Importe total de contrato:</td>
								    		<td style="width: 100px; text-align:right;">$'.$imp_tot_contratoF.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Por amortizar:</td>
								    		<td style="width: 100px; text-align:right;">$'.$por_amortizarF.'</td>
								    	</tr>
								    </table>
								</td>
							</tr>
						</table>
					    <table border="1">
					    	<tr>
					    		<td>
					    				<table border="0">
									    	<tr>
									    		<td colspan="4" height="10" width="200"></td>
									    	</tr>	
									    	<tr>
									    		<td colspan="4" style="font-weight:bold;"> Generar Estimación</td>
									    	</tr>
									    	<tr>
									    		<td>Importe esta estimación</td>
									    		<td style="text-align:right; width: 100px;">$'.$imp_estimacionTF.'</td>
									    		<td width="150"></td>
									    		<td>Planeacion</td>
									    	</tr>
									    	<tr>
									    		<td>Amortizado estimación:</td>
									    		<td style="text-align:right;">$'.$amortizado_estimacionF.'</td>
									    		<td></td>
									    		<td>'.$nombre_agrupador.'</td>
									    	</tr>
									    	<tr>
									    		<td>Subtotal 1:</td>
									    		<td style="text-align:right;">$'.$subtotal1F.'</td>
									    		<td></td>
									    		<td>'.$nombre_area.'</td>
									    	</tr>
									    	<tr>
									    		<td>Fondo de garantia</td>
									    		<td style="text-align:right;">$'.$fondo_garantia.'</td>
									    		<td></td>
									    		<td>'.$nombre_especialidad.'</td>
									    	</tr>
									    	<tr>
									    		<td>Retención:</td>
									    		<td style="text-align:right;">$'.$retencion.'</td>
									    		<td></td>
									    		<td>'.$nombre_partida.'</td>
									    	</tr>
									    	<tr>
									    		<td>Cargos:</td>
									    		<td style="text-align:right;">$'.$cargos.'</td>
									    		<td></td>
									    		<td>Cuenta de Costo</td>
									    	</tr>
									    	<tr>
									    		<td>Subtotal 2 (Retenciones):</td>
									    		<td style="text-align:right;">$'.$subtotal2.'</td>
									    		<td width="100"></td>
									    		<td>'.$cargo.'</td>
									    	</tr>
									    	<tr>
									    		<td>Iva:</td>
									    		<td style="text-align:right;">$'.$ivaF.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Total:</td>
									    		<td style="text-align:right;">$'.$totalF.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Fecha:</td>
									    		<td>'.$fecha.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Solicitó:</td>
									    		<td>'.$tenico.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Factura:</td>
									    		<td>'.$factura.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    </table>
					    		</td>
					    	</tr>
					    </table>
					    <page_footer>
						    <table align="center">
						    	<tr>
						    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">SUBCONTRATISTA</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    </table>
					    </page_footer>
					</page>';
   					// horizontal L Vertical P
   					// [[page_cu]]/[[page_nb]] PARA NUMERO DE PAGINA 
				    $html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;
	}
	exit();
}
if(isset($opcion) && $opcion=='pdf_est_cli'){

	$id_est =$_POST['id_cli'];
	$id_cli = $_POST['id_sub'];
	$opt = $_POST['opt'];
	$nombre_agrupador =$_POST['agru'];
	$hoy = date('m_d_y');
	$nom_pdf = $id_est.'_'.$opt.'_'.$hoy;
	if($opt == 'cli')
	{
		$SQL = "SELECT  b.nombre nombre_agrupador, c.nombre nombre_area, 
		dd.especialidad nombre_especialidad, ee.partida nombre_partida, a.cargos, i.obra, concat('RT-',g.id,' ',h.nombre,' ',h.paterno,' ',h.materno) as tenico, 
		a.id as idest, a.imp_estimacion, a.amortizado_estimacion, a.subtotal1, a.fondo_garantia, a.retencion, a.factura,
		a.cargos, a.subtotal2, a.iva, a.total, a.fecha, a.imp_contrato, a.ade1, a.ade2, a.ade3 , a.imp_tot_contrato, 
		a.anticipo, a.amortizado_anterior, a.amortizado_estimacion, a.tot_amortizado, a.por_amortizar, j.anticipo anticipo_por, a.xxano, a.id_cliente, sub.cliente as razon_social_sp,
		u.usuario as solicitante, u2.usuario as autoridad,a.estatus as autorizado FROM constru_estimaciones_bit_cliente a 
		      left join accelog_usuarios u on u.idempleado=a.id_autorizo
              left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
			  left join constru_agrupador b on b.id=a.id_agru
			  left join constru_especialidad c on c.id=a.id_area
			  left join constru_area d on d.id=a.id_esp
			  left join constru_cat_especialidad dd on dd.id=d.id_cat_especialidad
			  left join constru_partida e on e.id=a.id_part 
			  left join constru_cat_partidas ee on ee.id=e.id_cat_partida 
			
			  left join constru_altas g on g.id=a.id_autorizo
			  left join constru_info_tdo h on h.id_alta=g.id
			  left join constru_generales sub on sub.id=a.id_cliente
			  left join constru_generales i on i.id= a.id_obra
			  left join constru_info_sp j on j.id_alta=a.id_cliente
		where a.id_obra='$id_obra' and a.borrado=0 AND a.id='$id_est'";

		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row = mysql_fetch_array($result); 


		//total amortizado
		$SQL = "SELECT if(sum(amortizado_estimacion) is null,0,sum(amortizado_estimacion)) as totamor from constru_estimaciones_bit_cliente where id_obra='$id_obra' and estatus=1 and id_cliente='".$row['id_cliente']."';";
		  $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row5 = mysql_fetch_array($result); 

		//Amortizado anterior al id seleccionado
		$SQL = "SELECT if(sum(amortizado_estimacion) is null,0,sum(amortizado_estimacion)) as amorant from constru_estimaciones_bit_cliente where id_obra='$id_obra' and estatus=1 and id<'$id_est' and id_cliente='".$row['id_cliente']."';";
		  $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row6 = mysql_fetch_array($result); 

		  $poramor = $row['anticipo']-$row5['totamor'];

	   	$nombre_obra = $row['obra'];
	   	$nombre_agrupador = $row['nombre_agrupador'];
	   	$nombre_partida = $row['nombre_partida'];
	   	$nombre_area = $row['nombre_area'];
	   	$nombre_especialidad = $row['nombre_especialidad'];
	   	$cargo = $row['cargos'];
	   	$imp_estimacionT = $row['imp_estimacion'];
	   	$amortizado_estimacion = $row['amortizado_estimacion'];
	   	$subtotal1 = $row['subtotal1'];
	   	$fondo_garantia = $row['fondo_garantia'];
	   	$retencion = $row['retencion'];
	   	$cargos = $row['cargos'];
	   	$subtotal2 = $row['subtotal2'];
	   	$iva = $row['iva'];
	   	$total = $row['total'];
	   	$fecha = $row['fecha'];
	   	$tenico = $row['tenico'];
	   	$factura = $row['factura'];
	   	$imp_contrato = $row['imp_contrato'];
	   	$ade1 = $row['ade1'];
	   	$ade2 = $row['ade2'];
	   	$ade3 = $row['ade3'];
	   	$imp_tot_contrato = $row['imp_tot_contrato'];
	   	$anticipo = $row['anticipo'];
	   	$amortizado_anterior = $row6['amorant'];
	   	$amortizado_estimacion = $row['amortizado_estimacion'];
	   	$tot_amortizado = $row5['totamor'];
	   	$por_amortizar = $poramor;
	   	$anticipo_por = $row['anticipo_por'];
	   	$semana = $row['xxano'];
	   	$razon_social_sp = $row['razon_social_sp'];
	   	$imp_estimacionTF = number_format($imp_estimacionT,2);
	   	$amortizado_estimacionF = number_format($amortizado_estimacion,2);
	   	$subtotal1F = number_format($subtotal1,2);
	   	$fondo_garantiaF = number_format($fondo_garantia,2);
	   	$retencionF = number_format($retencion,2);
	   	$cargosF = number_format($cargos,2);
	   	$subtotal2F = number_format($subtotal2,2);
	   	$ivaF = number_format($iva,2);
	   	$totalF = number_format($total,2);
	   	$imp_contratoF = number_format($imp_contrato,2);
	   	$ade1F = number_format($ade1,2);
	   	$ade2F = number_format($ade2,2);
	   	$ade3F = number_format($ade3,2);
	   	$imp_tot_contratoF = number_format($imp_tot_contrato,2);
	   	$anticipoF = number_format($anticipo,2);
	   	$amortizado_anteriorF = number_format($amortizado_anterior,2);
	   	$amortizado_estimacionF = number_format($amortizado_estimacion,2);
	   	$por_amortizarF = number_format($por_amortizar,2);
	   	$tot_amortizadoF = number_format($tot_amortizado,2);
	   	$autorizado=$row['autorizado'];
        $autoridad=$row['autoridad'];
        if ($autorizado=='0'){$autoridad='Por Autorizar';}
        $solicitante=$row['solicitante'];
	   	week_bounds($fecha, $start, $end); 

	  
		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }

		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					    $content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    	<td border="0" width="240">'.$logo.'</td> <!-- logo-->
					    		<td height="50" width="500"; style="font-weight:bold;">ESTIMACIÓN -'.$id_est.'</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		<td width="333" style="font-weight:bold;">SEMANA '.$semana.'</td>
					    		<td width="333" style="font-weight:bold;">FECHA DE ESTIMACIÓN '.$fecha.'</td>
					    		<td width="333" style="font-weight:bold;">PERIODO '.$start.' al '.$end.'</td>
					    	</tr>
					    	<tr>
					    	<td width="333" style="font-weight:bold;">Solicito: '.$solicitante.'</td>
   	                        <td width="333" style="font-weight:bold;">Autorizo: '.$autoridad.'</td>
   	                        <td width="333" style="font-weight:bold;">Cliente: '.$razon_social_sp.'</td>
   	                        </tr>
						</table>
						<!--
					    <table align="center" width=100% border="0">
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AGRUPADOR</td>
					    		<td width="150">'.$nombre_agrupador.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">ESPECIALIDAD</td>
					    		<td width="150">'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AREA</td>
					    		<td width="250">'.$nombre_area.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">PARTIDA</td>
					    		<td width="250">'.$nombre_partida.'</td>
					    	</tr>
					    </table>
					    -->
					   	<table>
					    	<tr>
					    		<td height="20"width="1000" align="center" style="font-size:20;font-weight:bold;">ESTIMACIÓN CLIENTES</td>
					    	</tr>
					    </table>
					    <table border="1" style="width: 1100px;>
					    	<tr>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Clave</td>
					    		<td style="width: 300px; font-weight:bold; text-align:center;">Descripción</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Unidad</td>
					    		<!--<td style="width: 65px; font-weight:bold; text-align:center;">Vol. Tope</td>-->
					    		<td style="width: 60px; font-weight:bold; text-align:center;">PU destajo</td>
					    		<!--<td style="width: 85px; font-weight:bold; text-align:center;">Importe</td>-->
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol Anterior</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Vol Estimado</td>
					    		<td style="width: 70px; font-weight:bold; text-align:center;">Vol Acumulado</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol Ejecutar</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Imp. Estimación</td>
					    	</tr>';
					    			$SQL = "SELECT c.codigo, c.descripcion, c.unidtext, d.vol_tope, c.precio_costo, a.vol_anterior, a.vol_estimacion
					    			from constru_estimaciones_bit_cliente b
											    inner join constru_estimaciones_cliente a on a.id_bit_cliente=b.id
											    left join constru_recurso c on c.id=a.id_insumo
											    left join constru_vol_tope d on d.id_clave=c.id AND (d.id_area=b.id_area or d.id_area=b.id_area)
											    WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_cliente='$id_est';";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$clave = $row['codigo'];	
								    		$descripcion = $row['descripcion'];
								    		$unidad = $row['unidtext'];	
								    		$vol_tope=$row['vol_tope'];
								    		$vol_topeF = number_format($vol_tope,2);
								    		$pu_subcontrato=$row['precio_costo'];
								    		$pu_subcontratoF = number_format($pu_subcontrato,2);
								    		$vol_anterior=$row['vol_anterior'];
								    		$vol_anteriorF = number_format($vol_anterior,2);
								    		$vol_est=$row['vol_estimacion'];	
								    		$importe = $pu_subcontrato * $vol_tope;
								    		$importeF = number_format($importe,2);
								    		$vol_acumulado = $vol_anterior + $vol_est;
								    		$vol_acumuladoF = number_format($vol_acumulado,2);
								    		$vol_ejecutar = $vol_tope - $vol_acumulado;
								    		$imp_estimacion = $pu_subcontrato * $vol_est;
								    		$imp_estimacionF = number_format($imp_estimacion,2);
								    		$total_imp = $total_imp + $importe;
								    		$total_impF = number_format($total_imp,2);
								    		$total_imp_est = $total_imp_est + $imp_estimacion;
								    		$total_imp_estF = number_format($total_imp_est,2);
														
					    	$content.='
							<tr>
								<td style="width: 65px; font-size:10px;">'.$clave.'</td>
								<td style="width: 460px; font-size:10px;">'.$descripcion.'</td>
								<td style="width: 65px; text-align:center;">'.$unidad.'</td>
								<!--<td style="width: 65px; text-align:right;">'.$vol_topeF.'</td>-->
								<td style="width: 60px; text-align:right;">$'.$pu_subcontratoF.'</td>
								<!--<td style="width: 85px; text-align:right;">$'.$importeF.'</td>-->
								<td style="width: 60px; text-align:right;">'.$vol_anteriorF.'</td>
								<td style="width: 65px; text-align:right;">'.$vol_est.'</td>
								<td style="width: 70px; text-align:right;">'.$vol_acumuladoF.'</td>
								<td style="width: 60px; text-align:right;">'.$vol_ejecutar.'</td>
								<td style="width: 85px; text-align:right;">$'.$imp_estimacionF.'</td>
								
					    	</tr>'
					    	;}
					    	$content.='
					    	<tr>
					    		<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<!--<td border="0"></td>-->
								<td border="0"></td>
								<!--<td style="font-weight:bold; width: 85px; text-align:right;">$'.$total_impF.'</td>-->
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 85px; text-align:right;">Total:</td>

								<td style="font-weight:bold; width: 85px; text-align:right;">$'.	$imp_estimacionT.'</td>
					    	</tr>
					    </table>
					    <br>
						<table border="1">
							<tr>
								<td>
									<table>
								    	<tr>
								    		<td height="30" colspan="5">Estimacion</td>
								    	</tr>
								    	<tr>
								    		<td>Importe de contrato:</td>
								    		<td style="width: 100px; text-align:right;">$'.$imp_contratoF.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Anticipo '.$anticipo_por.'%</td>
								    		<td style="width: 100px; text-align:right;">$'.$anticipoF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Adendum 1:</td>
								    		<td style="width: 100px; text-align:right;">$'.$ade1F.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Importe amortizado anterior:</td>
								    		<td style="width: 100px; text-align:right;">$'.$amortizado_anteriorF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Adendum 2:</td>
								    		<td style="width: 100px; text-align:right;">$'.$ade2F.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Importe amortizado estimacion:</td>
								    		<td style="width: 100px; text-align:right;">$'.$amortizado_estimacionF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Adendum 3:</td>
								    		<td style="width: 100px; text-align:right;">$'.$ade3F.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Total amortizado:</td>
								    		<td style="width: 100px; text-align:right;">$'.$tot_amortizadoF.'</td>
								    	</tr>
								    	<tr>
								    		<td>Importe total de contrato:</td>
								    		<td style="width: 100px; text-align:right;">$'.$imp_tot_contratoF.'</td>
								    		<td style="width: 85px;"></td>
								    		<td>Por amortizar:</td>
								    		<td style="width: 100px; text-align:right;">$'.$por_amortizarF.'</td>
								    	</tr>
								    </table>
								</td>
							</tr>
						</table>
					    <table border="1">
					    	<tr>
					    		<td>
					    				<table border="0">
									    	<tr>
									    		<td colspan="4" height="10" width="200"></td>
									    	</tr>	
									    	<tr>
									    		<td colspan="4" style="font-weight:bold;"> Generar Estimación</td>
									    	</tr>
									    	<tr>
									    		<td>Importe esta estimación</td>
									    		<td style="text-align:right; width: 100px;">$'.$imp_estimacionTF.'</td>
									    		<td width="150"></td>
									    		<td>Planeacion</td>
									    	</tr>
									    	<tr>
									    		<td>Amortizado estimación:</td>
									    		<td style="text-align:right;">$'.$amortizado_estimacionF.'</td>
									    		<td></td>
									    		<td>'.$nombre_agrupador.'</td>
									    	</tr>
									    	<tr>
									    		<td>Subtotal 1:</td>
									    		<td style="text-align:right;">$'.$subtotal1F.'</td>
									    		<td></td>
									    		<td>'.$nombre_area.'</td>
									    	</tr>
									    	<tr>
									    		<td>Fondo de garantia</td>
									    		<td style="text-align:right;">$'.$fondo_garantia.'</td>
									    		<td></td>
									    		<td>'.$nombre_especialidad.'</td>
									    	</tr>
									    	<tr>
									    		<td>Retención:</td>
									    		<td style="text-align:right;">$'.$retencion.'</td>
									    		<td></td>
									    		<td>'.$nombre_partida.'</td>
									    	</tr>
									    	<tr>
									    		<td>Cargos:</td>
									    		<td style="text-align:right;">$'.$cargos.'</td>
									    		<td></td>
									    		<td>Cuenta de Costo</td>
									    	</tr>
									    	<tr>
									    		<td>Subtotal 2 (Retenciones):</td>
									    		<td style="text-align:right;">$'.$subtotal2.'</td>
									    		<td width="100"></td>
									    		<td>'.$cargo.'</td>
									    	</tr>
									    	<tr>
									    		<td>Iva:</td>
									    		<td style="text-align:right;">$'.$ivaF.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Total:</td>
									    		<td style="text-align:right;">$'.$totalF.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Fecha:</td>
									    		<td>'.$fecha.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Solicitó:</td>
									    		<td>'.$tenico.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    	<tr>
									    		<td>Factura:</td>
									    		<td>'.$factura.'</td>
									    		<td width="100"></td>
									    		<td></td>
									    	</tr>
									    </table>
					    		</td>
					    	</tr>
					    </table>
					    <page_footer>
						    <table align="center">
						    	<tr>
						    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">Cliente</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    </table>
					    </page_footer>
					</page>';
   					// horizontal L Vertical P
   					// [[page_cu]]/[[page_nb]] PARA NUMERO DE PAGINA 
				    $html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;
	}
	exit();
}
if(isset($opcion) && $opcion=='pdf_est_prov'){
	$id_est=$_POST['id_est'];
	$opt=$_POST['opt'];
	$r=$_POST['r'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id_est.'_'.$opt.'_'.$hoy;

	if($opt=='prov'){
					
		$SQL = "SELECT k.obra, e.nombre as nombre_agrupador, h.especialidad as nombre_especialidad, j.partida as nombre_partida, g.nombre as nombre_area, l.xxano, 
		l.factura, concat('RT-',m.id,' ',n.nombre,' ',n.paterno,' ',n.materno) as tecnico, l.fecha,
		 u.usuario as solicitante,l.estatus as autorizado,u2.usuario as autoridad, sp.razon_social_sp, a.id as oc
        FROM constru_pedis a
		LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
		LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
		LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
		LEFT JOIN constru_insumos c on c.id=b.id_clave
		left join constru_agrupador e on e.id = c1.id_agru
		left join constru_especialidad f on f.id=c1.id_area
		left join constru_area g on g.id=c1.id_esp
		left join constru_cat_especialidad h on h.id=g.id_cat_especialidad
		left join constru_partida i on i.id=c1.id_part 
		left join constru_cat_partidas j on j.id=i.id_cat_partida 
		LEFT JOIN constru_generales k on k.id = c1.id_obra
		left join constru_estimaciones_bit_prov l on l.id_oc = a.id
		LEFT JOIN constru_altas m on m.id=l.id_autorizo 
		LEFT JOIN constru_info_tdo n on n.id_alta=m.id
		LEFT JOIN constru_info_sp sp on sp.id_alta=a.id_prov
		left join accelog_usuarios u on u.idempleado=l.id_autorizo
        left join accelog_usuarios u2 on u2.idempleado=l.id_autorizo2

		WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 and l.id = '$id_est';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$row = mysql_fetch_array($result); 
	   	$nombre_obra = $row['obra'];
	   	$nombre_agrupador = $row['nombre_agrupador'];
	   	$nombre_partida = $row['nombre_partida'];
	   	$nombre_area = $row['nombre_area'];
	   	$nombre_especialidad = $row['nombre_especialidad'];
	   	$semana = $row['xxano'];
	   	$factura = $row['factura'];
	   	$solicito = $row['tecnico'];
	   	$fecha = $row['fecha'];
	   	$autoridado=$row['autorizado'];
	   	$autoridad=$row['autoridad'];
	   	$razsoc=$row['razon_social_sp'];
	   	 	$oc=$row['oc'];

        if ($autorizado=='0' ){$autoridad='Por Autorizar';}
        $solicitante=$row['solicitante'];

	   	week_bounds($fecha, $start, $end);
	   	 
		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }


					require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					    $content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    	<td border="0" width="240">'.$logo.'</td> <!-- logo-->
					    		<td height="50" width="500"; style="font-weight:bold;">ESTIMACIÓN -'.$id_est.'
<br>OC-'.$oc.'
					    		</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		<td width="333" style="font-weight:bold;">SEMANA '.$semana.'</td>
					    		<td width="333" style="font-weight:bold;">FECHA DE ESTIMACIÓN '.$fecha.'</td>
					    		<td width="333" style="font-weight:bold;">PERIODO del '.$start.' al '.$end.'</td>
					    	</tr>
					    	<tr>
					    	<td width="333" style="font-weight:bold;">Solicito: '.$solicitante.'</td>
   	<td width="333" style="font-weight:bold;">Autorizo: '.$autoridad.'</td>
   		<td width="333" style="font-weight:bold;">Proveedor: '.$razsoc.'</td>

   	</tr>
						</table>
					    <table>
					    	<tr>
					    		<td width="200">'.$id_tecnico.'</td>
					    		<td width="200">'.$nombre_tecnico.'</td>
					    	</tr>
					    </table>
					    <table align="center" width=100% border="0">
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AGRUPADOR</td>
					    		<td width="150">'.$nombre_agrupador.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">ESPECIALIDAD</td>
					    		<td width="150">'.$nombre_especialidad.'</td>
					    	</tr>
					    	<tr>
					    		<td width="150" style="font-weight:bold;">AREA</td>
					    		<td width="150">'.$nombre_area.'</td>
					    		
					    		<td width="150" style="font-weight:bold;">PARTIDA</td>
					    		<td width="150">'.$nombre_partida.'</td>
					    	</tr>
					    </table>
					   	<table>
					    	<tr>
					    		<td height="20"width="1000" align="center" style="font-size:20;font-weight:bold;">ESTIMACIÓN PROVEEDORES</td>
					    	</tr>
					    </table>
					    <table border="1" style="width: 1100px;>
					    	<tr>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Clave</td>
					    		<td style="width: 300px; font-weight:bold; text-align:center;">Concepto</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Unidad</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Volumen OC</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">PU Compra</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Importe</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol Anterior</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Entrada</td>
					    		<td style="width: 70px; font-weight:bold; text-align:center;">Vol Acumulado</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Vol por Ejecutar</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Imp. Estimación</td>
					    	</tr>';
					    			$SQL = "SELECT c.clave, c.descripcion, c.unidtext, b.cantidad Rcantidad, b.precio_compra, b.cantidad*b.precio_compra importe, x1.vol_anterior, x1.vol_gris, x1.vol_acu, x1.vol_eje, x1.imp_est
										FROM constru_estimaciones_bit_prov x
										LEFT JOIN constru_pedis a on a.id=x.id_oc
										LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
										LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
										LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
										LEFT JOIN constru_insumos c on c.id=b.id_clave
										LEFT JOIN constru_estimaciones_prov x1 on x1.id_clave=c.id AND x1.id_bit_prov=x.id
										WHERE x.id_obra='$id_obra' AND x.borrado=0 AND b1.borrado=0 AND x.id='$id_est' group by x1.id;";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$clave = $row['clave'];	
								    		$descripcion = $row['descripcion'];
								    		$unidad = $row['unidtext'];	
								    		$volumen_oc = $row['Rcantidad'];
								    		$volumen_ocF= number_format($volumen_oc, 2);
								    		$pu_compra = $row['precio_compra'];
								    		$importe = $row['importe'];
								    		$importeF =number_format($importe,2);
								    		$vol_anterior = $row['vol_anterior'];
								    		$vol_anteriorF =number_format($vol_anterior, 2);
								    		$entrada = $row['vol_gris'];
								    		$entradaF =number_format($entrada, 2);
								    		$vol_acumulado = $row['vol_acu'];
								    		$vol_acumuladoF =number_format($vol_acumulado,2);
								    		$vol_por_ejecutar = $row['vol_eje'];
								    		$vol_por_ejecutarF =number_format($vol_por_ejecutar,2);
								    		$imp_estimacion = $row['imp_est'];
								    		$imp_estimacionF =number_format($imp_estimacion,2);
								    		$imp_estimacion_tot = $imp_estimacion_tot + $imp_estimacion;
					
														
					    	$content.='
							<tr>
								<td style="width: 65px; font-size:10px;">'.$clave.'</td>
								<td style="width: 300px; font-size:10px;">'.$descripcion.'</td>
								<td style="width: 65px; text-align:center;">'.$unidad.'</td>
								<td style="width: 65px; text-align:right;">'.$volumen_ocF.'</td>
								<td style="width: 60px; text-align:right;">$'.$pu_compra.'</td>
								<td style="width: 85px; text-align:right;">$'.$importeF.'</td>
								<td style="width: 60px; text-align:right;">'.$vol_anteriorF.'</td>
								<td style="width: 65px; text-align:right;">'.$entradaF.'</td>
								<td style="width: 70px; text-align:right;">'.$vol_acumuladoF.'</td>
								<td style="width: 60px; text-align:right;">'.$vol_por_ejecutarF.'</td>
								<td style="width: 85px; text-align:right;">$'.$imp_estimacionF.'</td>
								
					    	</tr>'
					    	;}
					    	$iva = $imp_estimacion_tot * .16;
					    	$total = $iva + $imp_estimacion_tot;
					    	$ivaF = number_format($iva,2);
					    	$totalF = number_format($total,2);
					    	$imp_estimacion_totF = number_format($imp_estimacion_tot,2);
					    	$content.='
					    	<tr>
					    		<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 60px; text-align:right;">Total:</td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$imp_estimacion_totF.'</td>
					    	</tr>
					    </table>
					    <table border="0">
					    	<tr>
					    		<td colspan="4" height="30" width="200"></td>
					    	</tr>	
					    	<tr>
					    		<td colspan="4" style="font-weight:bold;"> Generar Estimación</td>
					    	</tr>
					    	<tr>
					    		<td>Importe esta estimación</td>
					    		<td width="80" style="text-align:right;">$'.$imp_estimacion_totF.'</td>
					    	</tr>
					    	<tr>
					    		<td>Subtotal:</td>
					    		<td style="text-align:right;">$'.$imp_estimacion_totF.'</td>
					    	</tr>
					    	<tr>
					    		<td>IVA 16%</td>
					    		<td style="text-align:right;">$'.$ivaF.'</td>
					    	</tr>
					    	<tr>
					    		<td>Total:</td>
					    		<td style="text-align:right;">$'.$totalF.'</td>
					    	</tr>
					    
					    	<tr>
					    		<td>Factura</td>
					    		<td>'.$factura.'</td>
					    	</tr>
					    </table>
					    <page_footer>
						    <table align="center">
						    	<tr>
						    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">PROVEEDOR</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    </table>
					    </page_footer>
					</page>';
   					// horizontal L Vertical P
   					// [[page_cu]]/[[page_nb]] PARA NUMERO DE PAGINA 
				    $html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
}
if(isset($opcion) && $opcion=='pdf_est_indi'){
	$id =$_POST['id'];
	$opt = $_POST['opt'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id.'_'.$opt.'_'.$hoy;
	
	if($opt =='indi'){
					
			$SQL = " SELECT a.id, a.factura, c.cargo, concat('RT-',b.id,' ',d.nombre,' ',d.paterno,' ',d.materno) as aut, 
			concat('PROV-',b2.id,' ',e.razon_social_sp) as prov, a.estatus, f.obra, a.fecha, a.xxano, a.imp_estimacion, a.subtotal, a.iva, a.total
			FROM constru_estimaciones_bit_indirectos a 
					LEFT JOIN constru_altas b on b.id=a.id_autorizo 
					LEFT JOIN constru_cuentas_cargo c on c.id=a.id_cc 
					LEFT JOIN constru_info_tdo d on d.id_alta=b.id 
					LEFT JOIN constru_altas b2 on b2.id=a.id_prov
					LEFT JOIN constru_info_sp e on e.id_alta=b2.id
					LEFT JOIN constru_generales f on f.id = a.id_obra 
					where a.id_obra='$id_obra' AND a.id='$id';";
					$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
					$row = mysql_fetch_array($result); 
				   	$nombre_obra = $row['obra'];
				   	$fecha = $row['fecha'];	
				   	$semana = $row['xxano'];
				   	$imp_estimacion = $row['imp_estimacion'];
				   	$subtotal = $row['subtotal'];
				   	$iva = $row['iva'];
				   	$total = $row['total'];	
				   	$solicito = $row['aut'];	
				   	$cuenta_costo = $row['cargo'];	
				   	$proveedor = $row['prov'];
				   	$factura = $row['factura'];
				   	$imp_estimacionF = number_format($imp_estimacion,2);
				   	$subtotalF = number_format($subtotal,2);
				   	$ivaF = number_format($iva,2);
				   	$totalF = number_format($total,2);

				   	week_bounds($fecha, $start, $end);

				   	
		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }

					require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					    $content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    	<td border="0" width="240">'.$logo.'</td> <!-- logo-->
					    		<td height="50" width="500"; style="font-weight:bold;">ESTIMACIÓN -'.$id.'</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		<td width="333" style="font-weight:bold;">SEMANA '.$semana.'</td>
					    		<td width="333" style="font-weight:bold;">FECHA DE ESTIMACIÓN '.$fecha.'</td>
					    		<td width="333" style="font-weight:bold;">PERIODO del '.$start.' al '.$end.'</td>
					    	</tr>
						</table>
					   	<table align="center">
					    	<tr>
					    		<td height="20"width="1000" align="center" style="font-size:20;font-weight:bold;">ESTIMACIÓN DE INDIRECTOS</td>
					    	</tr>
					    </table>
					    <table align="center" border="1" style="width: 1100px;>
					    	<tr>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Clave</td>
					    		<td style="width: 300px; font-weight:bold; text-align:center;">Concepto</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Unidad</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Cantidad</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">PU Indirecto</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Importe</td>
					    	</tr>';
					    			$SQL = "SELECT a.clave, a.concepto, a.unidtext, a.cantidad, a.pu_indirecto, a.pu_indirecto*a.cantidad as importe
										 from constru_estimaciones_indirectos a 
										 WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_indirectos='$id' AND a.borrado=0 order by a.clave desc;";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$clave = $row['clave'];	
								    		$concepto = $row['concepto'];
								    		$unidad = $row['unidtext'];	
								    		$cantidad = $row['cantidad'];
								    		$pu_indirecto = $row['pu_indirecto'];
								    		$importe = $row['importe'];
								    		$importeTotal = $importeTotal + $importe;
								    		$importeF=number_format($importe,2);
								    		$pu_indirectoF=number_format($pu_indirecto,2);
								    		$importeTotalF=number_format($importeTotal,2);
					    	$content.='
							<tr>
								<td style="width: 65px; font-size:10px;">'.$clave.'</td>
								<td style="width: 300px; font-size:10px;">'.$concepto.'</td>
								<td style="width: 65px; text-align:center;">'.$unidad.'</td>
								<td style="width: 65px; text-align:right;">'.$cantidad.'</td>
								<td style="width: 60px; text-align:right;">$'.$pu_indirectoF.'</td>
								<td style="width: 85px; text-align:right;">$'.$importeF.'</td>
					    	</tr>'
					    	;}
					    	$content.='
					    	<tr>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$importeTotalF.'</td>
					    	</tr>
					    </table>
					    <table border="0">
					    	<tr>
					    		<td colspan="4" height="30" width="200"></td>
					    	</tr>	
					    	<tr>
					    		<td colspan="4" style="font-weight:bold;"> Generar Estimación</td>
					    	</tr>
					    	<tr>
					    		<td>Importe esta estimación</td>
					    		<td width="80" style="text-align:right;">$'.$imp_estimacionF.'</td>
					    	</tr>
					    	<tr>
					    		<td>Subtotal:</td>
					    		<td style="text-align:right;">$'.$subtotalF.'</td>
					    	</tr>
					    	<tr>
					    		<td>IVA 16%</td>
					    		<td style="text-align:right;">$'.$ivaF.'</td>
					    	</tr>
					    	<tr>
					    		<td>Total:</td>
					    		<td style="text-align:right;">$'.$totalF.'</td>
					    	</tr>
					    	<tr>
					    		<td>Solicitó</td>
					    		<td>'.$solicito.'</td>
					    	</tr>
					    	<tr>
					    		<td>Cuenta Costo:</td>
					    		<td>'.$cuenta_costo.'</td>
					    	</tr>
					    	<tr>
					    		<td>Proveerdor:</td>
					    		<td>'.$proveedor.'</td>
					    	</tr>
					    	<tr>
					    		<td>Factura:</td>
					    		<td>'.$factura.'</td>
					    	</tr>
					    </table>
					    <page_footer>
						    <table align="center">
						    	<tr>
						    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">PROVEEDOR/SUBCONTRATISTA</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    </table>
					    </page_footer>
					</page>';
   					// horizontal L Vertical P
   					// [[page_cu]]/[[page_nb]] PARA NUMERO DE PAGINA 
				    $html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
}
if(isset($opcion) && $opcion=='pdf_est_chica'){
	$id =$_POST['id'];
	$opt = $_POST['opt'];
	$hoy = date("m_d_y"); 
	$nom_pdf = $id.'_'.$opt.'_'.$hoy;
	if($opt =='chica'){
					
			$SQL = " SELECT a.imp_estimacion, a.subtotal, a.total, a.fecha, a.xxano, f.obra,
			concat('RT-',b.id,' ',d.nombre,' ',d.paterno,' ',d.materno) as aut 
			from constru_estimaciones_bit_chica a 
					 LEFT JOIN constru_altas b on b.id=a.id_autorizo
					 LEFT JOIN constru_info_tdo d on d.id_alta=b.id 
					 LEFT JOIN constru_generales f on f.id = a.id_obra 
					 where a.id_obra = '$id_obra' and a.id='$id';";
					$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
					$row = mysql_fetch_array($result); 
				   	$nombre_obra = $row['obra'];
				   	$fecha = $row['fecha'];	
				   	$semana = $row['xxano'];
				   	$imp_estimacion = $row['imp_estimacion'];
				   	$subtotal = $row['subtotal'];
				   	$iva = $row['iva'];
				   	$total1 = $row['total'];	
				   	$solicito = $row['aut'];
				   	$imp_estimacionF=number_format($imp_estimacion,2);
				   	$subtotalF=number_format($subtotal,2);
					$total1F=number_format($total1,2);

					week_bounds($fecha, $start, $end);

	
		        $SQL="SELECT logo FROM constru_generales WHERE id=$id_obra";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	if($row['logo']!=null && $row['logo']!='' && file_exists("../../modulos/xtructur/uploads/".$row['logo'])){

           
               		$logo='<img src="../../modulos/xtructur/uploads/'.$row['logo'].'" alt="Smiley face" width="240">';
           		}


                else{
             
		 $SQL="SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
               $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
               $row = mysql_fetch_array($result); 
               	
               	if($row['logoempresa']==null || $row['logoempresa']==''){
                  	$logo='';
               	}else{

               		
               		if (file_exists("../../netwarelog/archivos/1/organizaciones/".$row['logoempresa'])) {
                       $logo='<img src="../../netwarelog/archivos/1/organizaciones/'.$row['logoempresa'].'" alt="Smiley face" width="240">';
}                   else {
                       
}
               		
           		}
             }

					require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
					    $content = '
					<page backbottom="14mm" footer="page">
					    <table width="1000" border="0">
					    	<tr>
					    	<td border="0" width="240">'.$logo.'</td> <!-- logo-->
					    		<td height="50" width="500"; style="font-weight:bold;">ESTIMACIÓN -'.$id.'</td>
					    		<td width="500" style="font-weight:bold;">'.$nombre_obra.'</td>
					    	</tr>
					    </table>
					    <table width="100%" border="0">
					    	<tr>
					    		<td width="333" style="font-weight:bold;">SEMANA '.$semana.'</td>
					    		<td width="333" style="font-weight:bold;">FECHA DE ESTIMACIÓN '.$fecha.'</td>
					    		<td width="333" style="font-weight:bold;">PERIODO del '.$start.' al '.$end.'</td>
					    	</tr>
						</table>
					   	<table align="center">
					    	<tr>
					    		<td height="20"width="1000" align="center" style="font-size:20;font-weight:bold;">ESTIMACIÓN DE CAJA CHICA</td>
					    	</tr>
					    </table>
					    <table align="center" border="1" style="width: 1100px;>
					    	<tr>
					    		<td style="width: 300px; font-weight:bold; text-align:center;">Concepto</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Unidad</td>
					    		<td style="width: 65px; font-weight:bold; text-align:center;">Cantidad</td>
					    		<td style="width: 60px; font-weight:bold; text-align:center;">Val Factura</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Importe</td>
					    		<td style="width: 75px; font-weight:bold; text-align:center;">Iva</td>
					    		<td style="width: 85px; font-weight:bold; text-align:center;">Total</td>
					    		<td style="width: 95px; font-weight:bold; text-align:center;">Factura</td>
					    		<td style="width: 95px; font-weight:bold; text-align:center;">Cuenta</td>
					    	</tr>';
					    			$SQL = "SELECT a.id_proveedor, a.concepto, a.unidad, a.cantidad, a.val_fact, a.val_fact as importe, (a.val_fact)*(a.iva/100) as iva, 
					    			((a.val_fact)*(a.iva/100)) + (a.val_fact) as total, concat('PROV-',b.id,' ',c.razon_social_sp) prov, a.factura, d.cargo
											 from constru_estimaciones_chica a
											 left join constru_altas b on b.id=a.id_proveedor
											 left join constru_info_sp c on c.id_alta=b.id 
											   left join constru_cuentas_cargo d on d.id=a.id_cc
											 WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_chica='$id' AND a.borrado=0 order by a.id desc;";	
									$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
									while($row = mysql_fetch_array($result)) {
								    		$concepto = $row['concepto'];
								    		$unidad = $row['unidad'];	
								    		$cantidad = $row['cantidad'];
								    		$val_fact = $row['val_fact'];
								    		$val_factF = number_format($val_fact,2);
								    		$importe = $row['importe'];
								    		$importeF = number_format($importe,2);
								    		$iva = $row['iva'];
								    		$ivaTotal = $ivaTotal + $iva;
								    		$ivaTotalF = number_format($ivaTotal,2);
								    		$ivaF = number_format($iva,2);
								    		$total = $row['total'];
								    		$factura = $row['factura'];
								    		$cuenta = $row['cargo'];
								    		$importe_total = $importe_total + $importe;
								    		$totalF = number_format($total,2);
								    		$importe_totalF = number_format($importe_total,2);
					    	$content.='
							<tr>
								<td style="width: 300px; font-size:10px;">'.$concepto.'</td>
								<td style="width: 65px; text-align:center;">'.$unidad.'</td>
								<td style="width: 65px; text-align:right;">'.$cantidad.'</td>
								<td style="width: 60px; text-align:right;">$'.$val_factF.'</td>
								<td style="width: 85px; text-align:right;">$'.$importeF.'</td>
								<td style="width: 75px; text-align:right;">$'.$ivaF.'</td>
								<td style="width: 85px; text-align:right;">$'.$totalF.'</td>
								<td style="width: 95px; text-align:right;">'.$factura.'</td>
								<td style="width: 95px; font-size:10px; text-align:right;">'.$cuenta.'</td>
					    	</tr>'
					    	;}
					    	$content.='
					    	<tr>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td border="0"></td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$importe_totalF.'</td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$ivaTotalF.'</td>
								<td style="font-weight:bold; width: 85px; text-align:right;">$'.$total1F.'</td>
					    	</tr>
					    </table>
					    <table border="0">
					    	<tr>
					    		<td colspan="4" height="30" width="200"></td>
					    	</tr>	
					    	<tr>
					    		<td colspan="4" style="font-weight:bold;"> Generar Estimacón</td>
					    	</tr>
					    	<tr>
					    		<td>Importe esta estimación</td>
					    		<td width="80" style="text-align:right;">$'.$imp_estimacionF.'</td>
					    	</tr>
					    	<tr>
					    		<td>Subtotal:</td>
					    		<td style="text-align:right;">$'.$subtotalF.'</td>
					    	</tr>
					    	<tr>
					    		<td>Total:</td>
					    		<td style="text-align:right;">$'.$total1F.'</td>
					    	</tr>
					    	<tr>
					    		<td>Solicitó</td>
					    		<td>'.$solicito.'</td>
					    	</tr>
					    </table>
					    <page_footer>
						    <table align="center">
						    	<tr>
						    		<td height="30" width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    		<td width="300" align="center" style="font-weight:bold;">_______________________</td>
						    	</tr>
						    	<tr>
						    		<td width="300" align="center" style="font-weight:bold;">ADMINISTRADOR</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE TECNICO</td>
						    		<td width="300" align="center" style="font-weight:bold;">SUPERINTENDENTE GENERAL</td>
						    	</tr>
						    </table>
					    </page_footer>
					</page>';
   					// horizontal L Vertical P
   					// [[page_cu]]/[[page_nb]] PARA NUMERO DE PAGINA 
				    $html2pdf = new HTML2PDF('L','A4','fr');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output('pdf/'.$nom_pdf.'.pdf', 'F');
			echo $nom_pdf;   
		}
	exit();
}
//////////////////////////chais/////////////////////////////////
if(isset($opcion)&& $opcion=='correocan'){
	$opt=$_POST['opt'];
if($opt=='sol'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_aut,naturaleza from constru_bit_solicitudes WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_aut'];
		$nat=$row['naturaleza'];

		$head="<br><table>
<tbody>
<tr>
<th>Codigo</th>
<th>Descripcion</th>
<th>Justificacion</th>
<th>Unidad</th>
<th>Cantidad</th>
<th>P.U. Concurso</th>
</tr>";


$SQL2="SELECT codigo,descripcion,justificacion,unidad,unidtext,precio_costo from constru_recurso WHERE id_bit_solicitud='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
    while($row = mysql_fetch_assoc($result)) {

$data=$data.'<tr>
<td>'.$row['codigo'].'</td>
<td>'.$row['descripcion'].'</td>
<td>'.$row['justificacion'].'</td>
<td>'.$row['unidtext'].'</td>
<td>'.$row['unidad'].'</td>
<td>'.$row['precio_costo'].'</td>
</tr>';}

$data=$head.$data.'</tbody></table>';

	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Solicitud ".$nat;
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens.$data);
            $mail->AddAddress($email, $email);

            $mail->Send();}
	exit();

}




if($opt=='prov'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_autorizo from constru_estimaciones_bit_prov WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_autorizo'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Estimación Proveedores";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens);
            $mail->AddAddress($email, $email);

            $mail->Send();}
	exit();

}

if($opt=='ind'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_autorizo from constru_estimaciones_bit_indirectos WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_autorizo'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Estimación Indirectos";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens);
            $mail->AddAddress($email, $email);

            $mail->Send();}
	exit();

}

if($opt=='sub'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_autorizo from constru_estimaciones_bit_subcontratista WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_autorizo'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Estimación Subcontratista";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens);
            $mail->AddAddress($email, $email);

            $mail->Send();}
	exit();

}

if($opt=='cli'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_autorizo from constru_estimaciones_bit_cliente WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_autorizo'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Estimación Cliente";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens);
            $mail->AddAddress($email, $email);

            $mail->Send();}
	exit();

}

if($opt=='des'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_autorizo from constru_estimaciones_bit_destajista WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_autorizo'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Estimación Destajistas";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens);
            $mail->AddAddress($email, $email);

            $mail->Send();}
	exit();

}

if($opt=='cc'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_autorizo from constru_estimaciones_bit_chica WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_autorizo'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Estimación Caja Chica";
            $mail->AltBody = "Xtructur";
            $mail->MsgHTML($mens);
            $mail->AddAddress($email, $email);

            $mail->Send();}
	exit();

}

if($opt=='nomo'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_aut from constru_bit_nominadest WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_aut'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Nomina obreros";
            $mail->AltBody = "Xtructur";
          
            if($mens==''){

            	
            	  $mail->MsgHTML('<b>La solicitud de Nomina Obreros ha sido cancelada.</b>');
            }
            	else{
                  $mail->MsgHTML('<b>La solicitud de Nomina Obreros ha sido cancelada por el siguiente motivo:</b> '.$mens);

            	}
         
            $mail->AddAddress($email, $email);

            $mail->Send();}
         
	exit();

}

if($opt=='nomot'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT id_aut from constru_bit_nominaca WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['id_aut'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de Nomina tecnicos";
            $mail->AltBody = "Xtructur";
          
            if($mens==''){

            	
            	  $mail->MsgHTML('<b>La solicitud de Nomina de tecnicos ha sido cancelada.</b>');
            }
            	else{
                  $mail->MsgHTML('<b>La solicitud de Nomina de tecnicos ha sido cancelada por el siguiente motivo:</b> '.$mens);

            	}
         
            $mail->AddAddress($email, $email);

            $mail->Send();}
           
	exit();

}

if($opt=='oc'){
	$id=$_POST['id'];

	$mens=$_POST['mens'];

$SQL2="SELECT solicito from constru_pedis WHERE id='$id';";
     $result=mysql_query($SQL2) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$idprov=$row['solicito'];
	}else{
		$idprov='';
	}
	if ($idprov != '') { 
         $SQL3="SELECT correoelectronico from administracion_usuarios WHERE idempleado='$idprov';";
     $result2=mysql_query($SQL3) or die("Couldn t execute query.".mysql_error());
     if(mysql_num_rows($result2)>0){
		$row2 = mysql_fetch_assoc($result2);
		$email=$row2['correoelectronico'];
		
	}else{
		$email='';
	}
	}

	if ($email != '') { 
	 require_once('../../modulos/phpmailer/sendMail.php');
            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "Xtructur";
            $mail->Subject = "Cancelación de orden de compra";
            $mail->AltBody = "Xtructur";
          
            if($mens==''){

            	
            	  $mail->MsgHTML('<b>La solicitud de orden de compra ha sido cancelada.</b>');
            }
            	else{
                  $mail->MsgHTML('<b>La solicitud de orden de compra ha sido cancelada por el siguiente motivo:</b> '.$mens);

            	}
         
            $mail->AddAddress($email, $email);

            $mail->Send();}
         
	exit();

}
}

if(isset($opcion) && $opcion=='autorizarest'){
	session_start();
	$idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$row = mysql_fetch_array($result); 

    $id_username_global=$row['idempleado'];
    $fecha=date('Y-m-d H:i:s');
$jus=$_POST['jus'];

	$id=$_POST['id'];
	$opt=$_POST['opt'];
	$r=$_POST['r'];
	if($opt=='des'){
		$SQL = "UPDATE constru_estimaciones_bit_destajista SET estatus='$r', id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='sol'){
		$SQL = "UPDATE constru_bit_solicitudes SET estatus='$r', id_aut='$id_username_global',fechaaut='$fecha' WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$SQL = "UPDATE constru_recurso SET autorizado='$r' WHERE id_bit_solicitud='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='sub'){
		$SQL = "UPDATE constru_estimaciones_bit_subcontratista SET estatus='$r', id_autorizo2='$id_username_global',fechaaut='$fecha'  WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='prov'){
		$SQL = "UPDATE constru_estimaciones_bit_prov SET estatus='$r', id_autorizo2='$id_username_global',fechaaut='$fecha'  WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		if($r==2){
			$SQL = "UPDATE constru_estimaciones_prov SET borrado='$r' WHERE id_bit_prov='$id';";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		}
	}
	if($opt=='cc'){
		$SQL = "UPDATE constru_estimaciones_bit_chica SET estatus='$r',id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='ind'){
		$SQL = "UPDATE constru_estimaciones_bit_indirectos SET estatus='$r',id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='cli'){
		$SQL = "UPDATE constru_estimaciones_bit_cliente SET estatus='$r',id_autorizo2='$id_username_global',fechaaut='$fecha' WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='nomo'){
		$SQL = "UPDATE constru_bit_nominadest SET estatus='$r',fechaaut='$fecha',id_aut2='$id_username_global' WHERE id='$id';";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	}
	if($opt=='nomot'){
		$SQL = "UPDATE constru_bit_nominaca SET estatus='$r',fechaaut='$fecha',id_aut2='$id_username_global' WHERE id='$id'";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='tsal'){
		$SQL = "UPDATE constru_bit_traspasos SET estatus='$r',fecha_aut_os='$fecha',id_aut_os='$id_username_global',justificacion_os='$jus' WHERE id='$id'";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='tent'){
		$SQL = "UPDATE constru_bit_traspasos SET estatus='$r',fecha_aut_oe='$fecha',id_aut_oe='$id_username_global',justificacion_oe='$jus' WHERE id='$id'";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

		if($r==4){
			echo 0;
			$SQL = " SELECT a.*, b.clave FROM constru_traspasos a 
			 left join constru_insumos b on b.id=a.id_clave
			  WHERE a.id_bit_traspaso='$id';";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

			if(mysql_num_rows($result)>0){
				echo 1;
				while($row = mysql_fetch_assoc($result)) {
					$idtras=$row['id'];
					$SQL2 = "SELECT id FROM constru_insumos WHERE clave='".addslashes($row['clave'])."' AND id_obra='".$row['id_obra_ent']."' AND borrado=0;";
					$result2 = mysql_query( $SQL2 ) or die("Couldn t execute query.".mysql_error());

					if(mysql_num_rows($result2)>0){
						$row2 = mysql_fetch_assoc($result2);
						$clavinsumosal=$row2['id'];
						$SQL = "UPDATE constru_traspasos SET id_clave_sal='$clavinsumosal' WHERE id='$idtras'";
						$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

					}else{
						echo 2;
					

						$SQL3 = "SELECT * FROM constru_insumos WHERE clave='".addslashes($row['clave'])."' AND id_obra='".$row['id_obra_sal']."' AND borrado=0 limit 1;";
						$result3 = mysql_query( $SQL3 ) or die("Couldn t execute query.".mysql_error());
						$row3 = mysql_fetch_assoc($result3);

						$INS = "INSERT INTO constru_insumos SET id_presupuesto='".$row3['id_presupuesto']."', id_obra='".$row['id_obra_ent']."', naturaleza='".$row3['naturaleza']."', clave='".$row3['clave']."', materiales='".$row3['materiales']."', descripcion='".$row3['descripcion']."', id_um='".$row3['id_um']."', unidad='".$row3['unidad']."', precio='".$row3['precio']."', importe='".$row3['importe']."', unidtext='".$row3['unidtext']."', borrado='".$row3['borrado']."'  ";

						mysql_query( $INS ) or die("Couldn t execute query.".mysql_error());
						$last_id = mysql_insert_id();

						//$result3 = mysql_query($INS) or die("Couldn t execute query.".mysql_error());
						//$last_id = mysql_insert_id();

						//mysql_query("UPDATE constru_insumos SET id_obra='".$row['id_obra_ent']."', unidad='".$row['cantidad']."' WHERE id='$last_id'; ") or die("Couldn t execute query.".mysql_error());

						$SQLXX = "UPDATE constru_traspasos SET id_clave_sal='".$last_id."' WHERE id='$idtras'";

						mysql_query( $SQLXX ) or die("Couldn t execute query.".mysql_error());

					}



				}
			}
		}

	}
	exit();
}

if(isset($opcion) && $opcion=='claves_insumos'){
	$SQL = "SELECT id,clave FROM constru_insumos WHERE id_obra='$id_obra' ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}

	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='est_pend'){
	$opt=$_POST['opt'];
	$id=$_POST['id_des'];

	if($opt=='des'){
		$SQL = "SELECT id FROM constru_estimaciones_bit_destajista WHERE id_obra='$id_obra' AND borrado=0 AND id_destajista='$id' AND estatus=0 limit 1;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='sub'){
		$SQL = "SELECT id FROM constru_estimaciones_bit_subcontratista WHERE id_obra='$id_obra' AND borrado=0 AND id_subcontratista='$id' AND estatus=0 limit 1;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='cli'){
		$SQL = "SELECT id FROM constru_estimaciones_bit_cliente WHERE id_obra='$id_obra' AND borrado=0 AND id_cliente='$id_obra' AND estatus=0 limit 1;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($opt=='ind'){
		$SQL = "SELECT id FROM constru_estimaciones_bit_indirectos WHERE id_obra='$id_obra' AND borrado=0 AND estatus=0 Limit 1;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='cc'){
		$SQL = "SELECT id FROM constru_estimaciones_bit_chica WHERE id_obra='$id_obra' AND borrado=0 AND estatus=0 Limit 1;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='pro'){
		$SQL = "SELECT id FROM constru_estimaciones_bit_prov WHERE id_obra='$id_obra' AND borrado=0 AND id_prov='$id' AND estatus=0 LIMIT 1;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if($opt=='pro2'){
		$SQL = "SELECT id, concat('ESTPRO-',id,' / OC-',id_oc,' Semana: ',semana) as oc FROM constru_estimaciones_bit_prov WHERE id_obra='$id_obra' AND borrado=0 AND id_prov='$id' AND borrado=0 ORDER BY id desc, semana;";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}

	if(mysql_num_rows($result)>0){
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='claves_recurso'){
	$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$row = mysql_fetch_assoc($result);
	$id_presupuesto=$row['id'];

	$SQL = "SELECT id,codigo FROM constru_recurso WHERE precio_costo>0 AND unidtext!='' AND id_presupuesto='$id_presupuesto' AND autorizado=1 ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}

	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='est_control_chica'){
	$sestmp=$_POST['sestmp'];
	$array=array();

	$SQL = "DELETE FROM constru_estimaciones_chica WHERE sestmp='$sestmp' AND id_obra='$id_obra' AND id_bit_chica=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "SELECT a.id,concat('PROV-',a.id,' ',b.razon_social_sp) prov FROM constru_altas a left join constru_info_sp b on b.id_alta=a.id WHERE a.id_obra='$id_obra' AND a.borrado=0 AND a.id_tipo_alta=5 ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['prov'])).';';
		}
		$cadena=trim($cadena,';');
		$array['prov']=$cadena;
	}else{
		$array['prov']='0:No hay proveedores dados de alta';
	}

	$SQL = "SELECT a.id,a.cargo FROM constru_cuentas_cargo a WHERE  a.borrado=0 AND a.id_costo=25 ORDER BY a.id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['cargo'])).';';
		}
		$cadena=trim($cadena,';');
		$array['cc']=$cadena;
	}else{
		$array['cc']='0:No hay cuentas de costo dadas de alta';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='est_control_indirectos'){
	$sestmp=$_POST['sestmp'];
	$array=array();

	$SQL = "DELETE FROM constru_estimaciones_indirectos WHERE sestmp='$sestmp' AND id_obra='$id_obra' AND id_bit_indirectos=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "SELECT id,concat(codigo,' - ',SUBSTRING(descripcion,1,100),'...') clave FROM constru_recurso WHERE pu_destajo>0 AND unidtext!='' AND id_obra='$id_obra' ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay recursos dados de alta';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='est_control_destajos'){
	$array=array();
	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$sestmp=$_POST['sestmp'];

	$SQL = "DELETE FROM constru_estimaciones_destajista WHERE sestmp='$sestmp' AND id_obra='$id_obra' AND id_bit_destajista=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "SELECT b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave FROM constru_asignaciones a 
	LEFT JOIN constru_recurso b on b.id=a.id_recurso
	INNER JOIN constru_vol_tope c on c.id_clave=b.id AND c.id_area=a.id_area
	WHERE a.id_area='$ar' AND a.id_partida='$pa' AND b.pu_destajo>0 AND b.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay recursos que pertenezcan a esta area y tengan volumen tope y PU de destajo autorizado';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='est_control_cliente'){
	$array=array();
	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$sestmp=$_POST['sestmp'];

	$SQL="DELETE FROM constru_estimaciones_cliente WHERE sestmp='$sestmp' AND id_obra='$id_obra' AND id_bit_cliente=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "SELECT b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave FROM constru_asignaciones a 
	LEFT JOIN constru_recurso b on b.id=a.id_recurso
	WHERE a.id_area='$ar' AND b.pu_subcontrato>0 AND b.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;";

/*
	$SQL = "SELECT b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave FROM constru_asignaciones a 
	LEFT JOIN constru_recurso b on b.id=a.id_recurso
	WHERE a.id_area='$ar' AND b.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;"; */

	$SQL = "SELECT a.id,concat(a.codigo,' - ',SUBSTRING(a.descripcion,1,100),'...') clave 
	FROM constru_recurso a 
	WHERE a.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;";

	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());


	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay recursos dados de alta';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='est_control_subcontratista'){
	$array=array();
	$ag=$_POST['ag'];
	$ar=$_POST['ar'];
	$es=$_POST['es'];
	$pa=$_POST['pa'];
	$sestmp=$_POST['sestmp'];

	$SQL="DELETE FROM constru_estimaciones_subcontratista WHERE sestmp='$sestmp' AND id_obra='$id_obra' AND id_bit_subcontratista=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$SQL = "SELECT b.id,concat(b.codigo,' - ',SUBSTRING(b.descripcion,1,100),'...') clave FROM constru_asignaciones a 
	LEFT JOIN constru_recurso b on b.id=a.id_recurso
	WHERE a.id_area='$ar' AND a.id_partida='$pa' AND b.pu_subcontrato>0 AND b.unidtext!='' AND a.id_obra='$id_obra' ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());


	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay recursos dados de alta';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='contratista_insumos'){
	$array=array();

	$SQL = "DELETE FROM constru_estimaciones_destajista WHERE sestmp>0 AND id_obra='$id_obra' AND id_bit_destajista=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());


	$SQL = "SELECT a.id,a.tipo_alta, concat('CONTRA-',a.id,' / Partida: ',b.nombre) as contra FROM constru_altas a inner join constru_partida b on b.id=a.id_partida WHERE id_tipo_alta=4 ORDER BY id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['contra'].';';
	}
	$cadena=trim($cadena,';');
	$array['contratista']=$cadena;

	$SQL = "SELECT id,concat(clave,' - ',SUBSTRING(descripcion,1,100),'...') clave FROM constru_insumos WHERE unidtext!='' AND id_obra='$id_obra' ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay insumos dados de alta';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='traeme_obras'){
	$obra_sal=$_POST['obra_sal'];

	$SQL = "SELECT id,obra FROM constru_generales where borrado=0 ORDER BY obra;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['obra'])).';';
		}
		$cadena=trim($cadena,';');
		$array['obras']=$cadena;
	}else{
		$array['obras']='0:No hay obras dadas de alta';
	}

	$SQL = "SELECT id,nomfam famat FROM constru_famat ORDER BY nomfam;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['famat'])).';';
		}
		$cadena=trim($cadena,';');
		$array['familias']=$cadena;
	}else{
		$array['familias']='0:No hay familias dadas de alta';
	}

	echo json_encode($array);

}

if(isset($opcion) && $opcion=='contratista_insumos_x'){
	$sestmp=$_POST['sestmp'];
	$array=array();

	$SQL = "DELETE FROM constru_requisiciones WHERE sestmp='$sestmp' AND id_obra='$id_obra' AND id_requi=0;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());


	$SQL = "SELECT a.id,a.tipo_alta, concat('CONTRA-',a.id,' / Partida: ',b.nombre) as contra FROM constru_altas a inner join constru_partida b on b.id=a.id_partida WHERE id_tipo_alta=4 ORDER BY id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['contra'].';';
	}
	$cadena=trim($cadena,';');
	$array['contratista']=$cadena;

	$SQL = "SELECT id,concat(clave,' - ',SUBSTRING(descripcion,1,100),'...') clave FROM constru_insumos WHERE unidtext!='' AND id_obra='$id_obra' ORDER BY id;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['clave'])).';';
		}
		$cadena=trim($cadena,';');
		$array['insumos']=$cadena;
	}else{
		$array['insumos']='0:No hay insumos dados de alta';
	}

	$SQL = "SELECT id,nomfam famat FROM constru_famat ORDER BY nomfam;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.addslashes(preg_replace('/;/', ' ',$row['famat'])).';';
		}
		$cadena=trim($cadena,';');
		$array['familias']=$cadena;
	}else{
		$array['familias']='0:No hay familias dadas de alta';
	}

	echo json_encode($array);
	exit();
}

if(isset($opcion) && $opcion=='complemento_proveedores'){
	$proveedores=array();

	$SQL = "SELECT account_id, CONCAT(manual_code, ' ',description) nombre_cuenta ,account_type,account_nature FROM cont_accounts ca WHERE currency_id=1 AND  affectable =1;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['account_id'].':'.$row['nombre_cuenta'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['cuentas']=$cadena;
	}else{
		$proveedores['cuentas']='0:No hay cuentas de contabilidad';
	}


	$SQL = "SELECT * from paises where idpais IN (1,43, 47,54,85);";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idpais'].':'.$row['pais'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['paises']=$cadena;
	}else{
		$proveedores['paises']='0:No hay paises registrados';
	}

	$SQL = "SELECT * from estados;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idestado'].':'.$row['estado'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['estados']=$cadena;
	}else{
		$proveedores['estados']='0:No hay estados registrados';
	}

	$SQL = "SELECT * from municipios;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idmunicipio'].':'.$row['municipio'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['municipios']=$cadena;
	}else{
		$proveedores['municipios']='0:No hay estados registrados';
	}

	$SQL = "SELECT idbanco, concat(nombre,'(',clave,')') as banco from cont_bancos order by nombre;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idbanco'].':'.$row['banco'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['bancos']=$cadena;
	}else{
		$proveedores['bancos']='0:No hay bancos registrados';
	}


$SQL = "SELECT * FROM tipo_proveedor;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['idtipo'].':'.$row['tipo'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['tprov']=$cadena;
	}else{
		$proveedores['tprov']='0:No hay tipos registrados';
	}


$SQL = "SELECT co.account_id, CONCAT(manual_code, ' ',description) nombre_cuenta
			 FROM cont_accounts co
			 WHERE co.status=1 
			 AND co.removed=0 
			 AND co.affectable=1;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['account_id'].':'.$row['nombre_cuenta'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['ccliente']=$cadena;
	}else{
		$proveedores['ccliente']='0:No hay cuentas registradas';
	}

	$SQL = "SELECT * from cont_tipo_tercero;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['tipotercero'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['ttercero']=$cadena;
	}else{
		$proveedores['ttercero']='0:No hay tipos registrados';
	}

	$SQL = "SELECT o.id, o.tipoOperacion FROM cont_tipo_operacion as o;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['tipoOperacion'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['toperacion']=$cadena;
	}else{
		$proveedores['toperacion']='0:No hay estados registrados';
	}



	$SQL = "SELECT * FROM cont_tipo_iva;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['tipoiva'].';';
		}
		$cadena=trim($cadena,';');
		$proveedores['tiva']=$cadena;
	}else{
		$proveedores['tiva']='0:No hay estados registrados';
	}


	echo json_encode($proveedores);
	exit();

}



if(isset($opcion) && $opcion=='tecnicos'){
	$tecnicos=array();

	$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$row = mysql_fetch_assoc($result);
	$id_presupuesto=$row['id'];

	$SQL = "SELECT id,cargo FROM constru_cuentas_cargo WHERE borrado=0 ORDER BY cargo;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['cargo'].';';
		}
		$cadena=trim($cadena,';');
		$tecnicos['cc']=$cadena;
	}else{
		$tecnicos['cc']='0:No hay cuentas de costo';
	}

	$SQL = "SELECT id,nombre FROM constru_agrupador WHERE id_obra='$id_obra' AND borrado=0 ORDER BY nombre;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['nombre'].';';
		}
		$cadena=trim($cadena,';');
		$tecnicos['agrupadores']=$cadena;
	}else{
		$tecnicos['agrupadores']='0:No hay agrupadores definidos';
	}

	if(!isset($_POST['d'])){
		$ca='AND (id=1 OR id=2)';
	}else{
		$ca='';
	}
	$SQL = "SELECT id,departamento FROM constru_deptos  WHERE borrado=0 ".$ca." ORDER BY departamento;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['departamento'].';';
	}
	$cadena=trim($cadena,';');
	$tecnicos['depto']=$cadena;

	$SQL = "SELECT * FROM constru_cat_especialidad  WHERE borrado=0  ORDER by especialidad desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['especialidad'].';';
	}
	$cadena=trim($cadena,';');
	$tecnicos['catesp']=$cadena;

	$SQL = "SELECT * FROM constru_especialidad  WHERE borrado=0  ORDER by nombre desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['nombre'].';';
	}
	$cadena=trim($cadena,';');
	$tecnicos['areas']=$cadena;
	$id_tipo_tab=$_POST['id_tipo_tab'];

	$SQL = "SELECT id,familia FROM constru_familias WHERE id_obra='$id_obra' AND id_categoria_familia='$id_tipo_tab' ORDER BY familia;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';

	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['familia'].';';
		}
		$cadena=trim($cadena,';');
		$tecnicos['fams']=$cadena;
	}else{
		$tecnicos['fams']='0:No hay familias dadas de alta';
	}


	$SQL = "SELECT a.id,b.partida FROM constru_partida a inner join constru_cat_partidas b on b.id=a.id_cat_partida WHERE a.id_obra='$id_obra' AND a.borrado=0 GROUP BY b.id ORDER BY b.partida;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
	    	$cadena.=$row['id'].':'.$row['partida'].';';
		}
		$cadena=trim($cadena,';');
	}else{
		$cadena='0:No hay partidas dadas de alta';
	}

	$cadena=trim($cadena,';');
	$tecnicos['parts']=$cadena;


	$id_tipo_tab=$_POST['id_tipo_tab'];
	$SQL = "SELECT id, if(sal_semanal>0,concat(categoria,' / $',sal_semanal),concat(categoria,' / $',sal_mensual)) as categoria FROM constru_categoria WHERE borrado=0  ORDER by clave_cat desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	while($row = mysql_fetch_assoc($result)) {
	    $cadena.=$row['id'].':'.$row['categoria'].';';
	}
	$cadena=trim($cadena,';');
	$tecnicos['tabu']=$cadena;

	$SQL = "SELECT a.id, concat('Maestro - ',b.nombre,' ',b.paterno) as resp  FROM constru_altas a INNER JOIN constru_info_tdo b on b.id_alta=a.id AND a.tipo_alta='Maestro' WHERE a.id_obra='$id_obra' AND a.borrado=0  ORDER by id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $cadena.=$row['id'].':'.$row['resp'].';';
		}
		$cadena=trim($cadena,';');
		$tecnicos['resp']=$cadena;
	}else{
		$tecnicos['resp']='0:No hay maestros dados de alta';
	}

	$SQL = "SELECT a.id, concat('Subcontratista - ',b.razon_social_sp) as resp  FROM constru_altas a INNER JOIN constru_info_sp b on b.id_alta=a.id AND a.tipo_alta='Subcontratista' WHERE a.id_obra='$id_obra' AND a.borrado=0  ORDER by id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $cadena.=$row['id'].':'.$row['resp'].';';
		}
		$cadena=trim($cadena,';');
		$tecnicos['resps']=$cadena;
	}else{
		$tecnicos['resps']='0:No hay subcontratistas dados de alta';
	}

	$SQL = "SELECT a.id, concat('Tecnico - ',b.nombre,' ',b.paterno) as resp  FROM constru_altas a INNER JOIN constru_info_tdo b on b.id_alta=a.id AND a.tipo_alta='Tecnico' WHERE a.id_obra='$id_obra' AND a.borrado=0  ORDER by id desc;";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	$cadena='';
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $cadena.=$row['id'].':'.$row['resp'].';';
		}
		$cadena=trim($cadena,';');
		$tecnicos['respt']=$cadena;
	}else{
		$tecnicos['respt']='0:No hay tecnicos dados de alta';
	}

	echo json_encode($tecnicos);
	exit();
}

if(isset($opcion) && $opcion=='info_tecnico'){
	$return=array();
	$id=$_POST['id'];
	$SQL = "SELECT a.id, pa.id agid, pa.nombre nomagru, pb.id esid, pb.nombre nomesp, pc.id arid, pc.nombre nomare, ta.id faid,  ta.familia, tb.id caid, if(tb.sal_semanal>0,concat(tb.categoria,' / $',tb.sal_semanal),concat(tb.categoria,' / $',tb.sal_mensual)) as categoria FROM constru_altas a 
	LEFT JOIN constru_agrupador pa on pa.id=a.id_agrupador 
	LEFT JOIN constru_area pb on pb.id=a.id_especialidad 
	LEFT JOIN constru_especialidad pc on pc.id=a.id_area
    LEFT JOIN constru_familias ta on ta.id=a.id_familia
    LEFT JOIN constru_categoria tb on tb.id=a.id_categoria
WHERE a.id='$id';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)) {
		    $return[]=$row;
		}
		$JSON = array('success' =>1, 
				'datos'=>$return);
	}else{
		$JSON = array('success' =>0);
	}
	echo json_encode($JSON);
	exit();
}

if(isset($opcion) && $opcion=='guardaFactura'){

	include("../../libraries/xml2json/xml2json.php");
	date_default_timezone_set('America/Mexico_City');
	$fechahoy=date('Y-m-d h:i:s');

	$UUID = $_POST['UUID'];
    $noCertificadoSAT = $_POST['noCertificadoSAT'];
    $selloCFD = $_POST['selloCFD'];
    $selloSAT = $_POST['selloSAT'];
    $FechaTimbrado = $_POST['FechaTimbrado'];
    $idComprobante = $_POST['idComprobante'];
    $idFact = $_POST['idFact'];
    $idVenta = $_POST['idVenta'];
    $noCertificado = $_POST['noCertificado'];
    $trackId = $_POST['trackId'];
    $monto = $_POST['monto'];
    $cliente = $_POST['cliente'];
    $idRefact = $_POST['idRefact'];
    $azurian = $_POST['azurian'];
    $tipoComp = $_POST['tipoComp'];
    $estatus = $_POST['estatus'];
    $xmlfile = $_POST['xmlfile'];
    $fp = $_POST['fp'];
    $numpago = $_POST['numpago'];
    $fp = $_POST['fp'];


    if($_POST['doc'] == 3)
    {
        $tipoComp = "R";
    }


    $azurianjsondec=json_decode($azurian);
    $azurian = base64_encode($azurian);

    $moneda = $azurianjsondec->Basicos->Moneda;


    $fechaactual = preg_replace('/T/', ' ', $FechaTimbrado);
    if ($idRefact == 'c') {
        $tipoComp = 'C';
        $queryRespuesta = "UPDATE app_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta' AND origen=1 AND idOs=0";
        $this->queryArray($queryRespuesta);
    }


    if($trackId=='refact2'){
        $queryRespuesta = "UPDATE app_respuestaFacturacion SET cancelre=1 WHERE idSale='$idVenta' AND origen=1 and tipoComp='F';";
        $this->queryArray($queryRespuesta);
    }

    /*if($tipoComp=='C'){
        $lala = "SELECT id_ov FROM app_devolucioncli where id='$idVenta'";
        $rlala = $this->queryArray($lala);
        $id_ov = $rlala['rows'][0]['id_ov'];
    }else{
        $id_ov=0;
    }*/

    $insertRespuestaFacturacion = "INSERT INTO app_respuestaFacturacion "
    . "(idSale,idOs,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal,xmlfile,proviene) VALUES "
    . "('" . $idVenta . "','".$id_ov."','" . $idFact . "','" . $UUID . "','" . $trackId . "','" . $noCertificadoSAT . "','" . $noCertificado . "','" . $selloSAT . "','" . $selloCFD . "','" . $fechaactual . "',0,'" . $tipoComp . "','" . $idComprobante . "','" . $azurian . "','".$xmlfile."',4);";


	mysql_query( $insertRespuestaFacturacion ) or die("Couldn t execute query.".mysql_error());
	$insertedId = mysql_insert_id();

    //$resultInsert = $this->queryArray($insertRespuestaFacturacion);
    //$insertedId = $resultInsert["insertId"];


	//mysql_query("UPDATE app_envios set forma_pago='$fp', numpago='$numpago' where id='$idVenta'") or die("Couldn t execute query.".mysql_error());
    //$this->queryArray("UPDATE app_envios set forma_pago='$fp', numpago='$numpago' where id='$idVenta'");


    if (is_numeric($insertedId)) {
        $queryUpdateContador = "DELETE FROM app_pagos WHERE origen=1 and concepto='Ticket Venta-".$idVenta."' ";
        //mysql_query($queryUpdateContador) or die("Couldn t execute query.".mysql_error());
        //$this->queryArray($queryUpdateContador);

        $queryUpdateContador = "UPDATE pvt_contadorFacturas set total=total+1 where id=1";
        //mysql_query($queryUpdateContador) or die("Couldn t execute query.".mysql_error());
        //$this->queryArray($queryUpdateContador);

        $ContadorLicencias = "UPDATE comun_parametros_licencias set valor=valor-1 where parametro='Facturas'";
        //mysql_query($ContadorLicencias) or die("Couldn t execute query.".mysql_error());
        //$this->queryArray($ContadorLicencias);

        if ($tipoComp == "R") {
            $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio_r=folio_r+1 where id=1";
        } else {
            $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio=folio+1 where id=1";
        }
        //mysql_query($queryUpdateFolo) or die("Couldn t execute query.".mysql_error());
        //$this->queryArray($queryUpdateFolo); 
    }

    if (preg_match('/all/', $idRefact)) {
        $idRefact = preg_replace('/all/', '', $idRefact);
        $updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale in (" . $idRefact . ")";
        mysql_query($updatePendienteFactura) or die("Couldn t execute query.".mysql_error());
        //$this->queryArray($updatePendienteFactura);
    }

    if ($idRefact > 0 && $idRefact != 'c') {
        $updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'";
        mysql_query($updatePendienteFactura) or die("Couldn t execute query.".mysql_error());
        //$this->queryArray($updatePendienteFactura);
    }


    $cont_xml = simplexml_load_file('../../modulos/cont/xmls/facturas/temporales/'.$xmlfile);
$json = xmlToArray($cont_xml);

if( isset($json['Comprobante']['@Version']) ){
    //3.3
    $folio = $json['Comprobante']['@Folio'];
    $uuid=$fac_uuid;
    $er='R';
    $tipo='Egreso';
    $serie= $json['Comprobante']['@Serie'];
    $emisor= $json['Comprobante']['cfdi:Emisor']['@Nombre'];
    $receptor= $json['Comprobante']['cfdi:Receptor']['@Nombre'];
    $importe= $json['Comprobante']['@Total'];
    $moneda= $json['Comprobante']['@Moneda'];
    $rfc= $json['Comprobante']['cfdi:Emisor']['@Rfc'];
    $fecha=$json['Comprobante']['@Fecha'];
    $fecha_subida=$fechahoy;
    $xml='../../modulos/cont/xmls/facturas/temporales/'.$uuid.'.xml';
    $version=$json['Comprobante']['@Version'];
    $cancelada=0;
    $json=json_encode($json,JSON_HEX_APOS);
    $temporal=1;
}else{
    //3.2
    $folio = $json['Comprobante']['@folio'];
    $uuid=$fac_uuid;
    $er='R';
    $tipo='Egreso';
    $serie= $json['Comprobante']['@serie'];
    $emisor= $json['Comprobante']['cfdi:Emisor']['@nombre'];
    $receptor= $json['Comprobante']['cfdi:Receptor']['@nombre'];
    $importe= $json['Comprobante']['@total'];
    $moneda= $json['Comprobante']['@Moneda'];
    $rfc= $json['Comprobante']['cfdi:Emisor']['@rfc'];
    $fecha=$json['Comprobante']['@fecha'];
    $fecha_subida=$fechahoy;
    $xml='../../modulos/cont/xmls/facturas/temporales/'.$uuid.'.xml';
    $version=$json['Comprobante']['@version'];
    $cancelada=0;
    $json=json_encode($json,JSON_HEX_APOS);
    $temporal=1;


}

	mysql_query(" INSERT INTO cont_facturas (folio,uuid,er,tipo,serie,emisor,receptor,importe,moneda,rfc,fecha,fecha_subida,xml,version,cancelada,json,temporal) VALUES ('$folio','$uuid','$er','$tipo','$serie','$emisor','$receptor','$importe','$moneda','$rfc','$fecha','$fecha_subida','$xml','$version','$cancelada','$json','$temporal') ") or die("Couldn t execute query.".mysql_error());


	mysql_query(" UPDATE constru_estimaciones_bit_cliente SET estatus=4, fp='$fp' WHERE id='$cliente';") or die("Couldn t execute query.".mysql_error());


    echo 333;
    exit();
    //$queryEnvio = "UPDATE app_pos_venta set envio=2 where idVenta=".$idVenta;
    //$this->queryArray($queryEnvio);

    

 //   $this->creaPoliza($idVenta,$insertedId,$monto,$xmlfile,$moneda,$fechaactual,$tipoComp,0);
    
   // return $insertedId;

   // echo json_encode($resultado);

}

/*
switch ($opcion) {
	case 'proydinamico':
		# code...
		break;
	
	default:
		# code...
		break;
}
*/
$oper = $_POST['oper'];
$SQL = "SELECT * FROM constru_um;";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$cadena='';
while($row = mysql_fetch_assoc($result)) {
    $cadena.=$row['id'].':('.$row['codigo'].') '.$row['nombre'].';';
}
$cadena=trim($cadena,';');
echo ($cadena);
?>