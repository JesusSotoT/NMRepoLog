<?php
$val=$_REQUEST['opc'];
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		
switch ($val) {
	
	case 1:
		//session_start();
	    $_SESSION['otra1']=$_REQUEST['inseriva1'];
		$_SESSION['otra2']=$_REQUEST['inseriva2'];
		$_SESSION['asume']=$_REQUEST['radio'];
		$_SESSION['cadena']=$_REQUEST['cadenacheck'];
		if(isset($_REQUEST['idproveedor'])){
			$_SESSION['idproveedor']=$_REQUEST['idproveedor'];
		}
		else{
			
		}
		
	break;
	case 2:
		//$sql=$conection->query('select account_id,manual_code,description,account_type,account_nature from cont_accounts ca where currency_id=1 and father_account_id!=0 and account_nature=1 and affectable =1 and account_type=2 ');
		//cuentas para proveedores
		$sql=$conection->query('select account_id,manual_code,description,account_type,account_nature from cont_accounts ca where currency_id=1 and  affectable =1  ');
		
		if($sql->num_rows>0){
			while($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo '<option value="'.$fila['account_id'].'">'.$fila['manual_code'].' '.utf8_encode($fila['description']).'</option>';
			}
		}else{
			echo '<option>No existen cuentas</option>';
		}
	break;
	case 3:
		$sql=$conection->query('select * from cont_tasas');
			while($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo '//'.$fila['tasa'].'->'.$fila['id'];
			}
		  
	break;
	case 4:
		
		$sql=$conection->query('select o.`id`,o.`tipoOperacion` 
		from `cont_tipo_operacion` as o
		inner join `cont_relacion_ter_oper` as rel on o.`id`=rel.`idtipoperacion`
		inner join `cont_tipo_tercero` as ter on  rel.`idtipotercero`= ter.`id` 
		and ter.`id`='.$_REQUEST['idtercero']);
		while($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo '<option value="'.$fila['id'].'">'.utf8_encode($fila['tipoOperacion']).'</option>';
			}

	break;
	case 5://tasas q estan en tasa y no en la relacion
		$sql=$conection->query('select ta.id,ta.valor,ta.`tasa`
				FROM 
				cont_tasas ta
				WHERE
				ta.`tasa`  NOT IN (
				select  p.tasa from`cont_tasaPrv` p where  p.visible=1 and p.`idPrv`='.$_REQUEST['idproveedor'].' );');
		while($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo '//'.$fila['tasa'].'->'.$fila['id'];
			}
	break;
	case 6://para cuentas cuando es edicion
		$sql=$conection->query('select ca.account_id,ca.manual_code,ca.description,ca.account_type,ca.account_nature 
				from cont_accounts ca,mrp_proveedor mp
				where (account_type=2 || account_type=4) and account_nature=1 and currency_id=1 
				and ca.account_id=mp.cuenta and mp.idPrv='.$_REQUEST['idproveedor'].'
				limit 1');
			if($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo $fila['account_id'].'//'.$fila['manual_code'].' '.$fila['description'];

			}
	
	break;
	case 7:// tipo iva en edicion
		$sql=$conection->query('select ti.id,ti.tipoiva from cont_tipo_iva ti,mrp_proveedor mp
		where ti.`id`=mp.`idtipoiva` and mp.idPrv='.$_REQUEST['idproveedor']);
		if($sql->num_rows>0){
			if($fila=$sql->fetch_array(MYSQLI_BOTH)){
					echo $fila['id'].'//'.$fila['tipoiva'];
	
			}
		}else{
			echo 0;
		}
	break;
	case 8:
		$sql=$conection->query('select t.id, t.`tipotercero` ,o.id opera,o.`tipoOperacion`
 		from cont_tipo_tercero t,cont_tipo_operacion o,mrp_proveedor p
 		where t.`id`=p.`idtipotercero` and  o.`id`=p.`idtipoperacion` and p.idPrv='.$_REQUEST['idproveedor']);
		if($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo $fila['id'].'->'.utf8_encode($fila['tipotercero']).'//'.$fila['opera'].'<-'.utf8_encode($fila['tipoOperacion']);

			}
	break;
	case 9://tasas de la relacion con prov para edicion
		$sql=$conection->query('select t.id,t.tasa,t.valor
		from cont_tasaPrv t
		where t.`idPrv`='.$_REQUEST['idproveedor'].' and t.visible=1 order by t.tasa asc');
		if($sql->num_rows>0){
			while($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo '//'.$fila['tasa'].'->'.$fila['id'].'->'.$fila['valor'];
			}
		}else{
			 echo "//0->0->0";
			// echo 0;
		}
	break;
	case 10://asumir
		$sql=$conection->query('select t.id from cont_tasas t, mrp_proveedor p,cont_tasaPrv tp
		where tp.id=p.`idTasaPrvasumir` and tp.idPrv=p.idPrv and t.tasa=tp.tasa and p.idPrv='.$_REQUEST['idproveedor']);
		if($fila=$sql->fetch_array(MYSQLI_BOTH)){
			echo $fila['id'];
		}
	break;
	case 11:
		$sql2=$conection->query('select * from cont_tasaPrv where id='.$_REQUEST['id']);
		if($fila2=$sql2->fetch_array(MYSQLI_BOTH)){
			if($fila2['tasa']=='Otra Tasa 1'){
				echo "1234";
			}else if($fila2['tasa']=='Otra Tasa 2'){
				echo "12345";
			}else if ($fila2['tasa']=='No Calcula'){
				echo "123456";
			}else if($fila2['tasa']!='Otra Tasa 1' && $fila2['tasa']!='Otra Tasa 2'){
				$sql=$conection->query('select ct.id,t.tasa
				from cont_tasaPrv t,cont_tasas ct
				where ct.tasa=t.tasa and t.id='.$_REQUEST['id'].' order by t.tasa asc');
				if($fila=$sql->fetch_array(MYSQLI_BOTH)){
					echo $fila['id'];
				}
			}
		}
		
	break;
	case 12:
		$sql=$conection->query('select p.idpais,p.pais,pr.nombrextranjero from mrp_proveedor pr
    	inner join paises p on p.idpais = pr.PaisdeResidencia
        where pr.idPrv='.$_REQUEST['idproveedor']);
		if($fila=$sql->fetch_array(MYSQLI_BOTH)){
			echo $fila['idpais'].'//'.$fila['pais'].'//'.$fila['nombrextranjero'];
		}
	break;
	case 13:
		$id="";
		if($_REQUEST['id']!=0){
			$id=' and idPrv!='.$_REQUEST['id'];
		}
		$sql=$conection->query("select * from mrp_proveedor where rfc='".$_REQUEST['rfc']."' ".$id);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 14:
		$id="";
	if($_REQUEST['id']!=0){
			$id=' and idPrv!='.$_REQUEST['id'];
		}
		$sql=$conection->query("select * from mrp_proveedor where razon_social='".$_REQUEST['nombre']."' ".$id);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 15:
		$sql=$conection->query("select idTasaPrvasumir from mrp_proveedor where idPrv=".$_REQUEST['id']);
		if($fila=$sql->fetch_array(MYSQLI_BOTH)){
			echo $fila['idTasaPrvasumir'];
		}
	break;
	case 16:// tipo IETU en edicion
		$sql=$conection->query('select idIETU from mrp_proveedor mp
		where  mp.idPrv='.$_REQUEST['idproveedor']);
		if($fila=$sql->fetch_array(MYSQLI_BOTH)){
			echo $fila['idIETU'];
		}
	break;
	case 17:// ejer actual
		$sql=$conection->query("select EjercicioActual from cont_config");
		if($fila=$sql->fetch_array(MYSQLI_BOTH)){
			echo $fila['EjercicioActual'];
		}
	break;
	// case 18:
		// $sql=$conection->query('select * from bco_cuentas_bancarias where account_id='.$_REQUEST['idcuenta']);
		// if($sql->num_rows>0){
			// echo $sql->num_rows;
		// }else{
			// echo 0;
		// }
	// break;
	case 19:
		$id="";
		if($_REQUEST['id']!=0){
			$id=' and id!='.$_REQUEST['id'];
		}
		$sql=$conection->query("select * from comun_cliente where rfc='".$_REQUEST['rfc']."' ".$id);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 20:
		$sql=$conection->query('SELECT c.account_id, c.description, c.manual_code FROM cont_accounts c, cont_config f
			WHERE c.status=1 AND c.currency_id=1  and  c.removed=0 AND c.affectable=1 AND c.account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0) and  c.main_father =f.CuentaDeudores			');
		if($sql->num_rows>0){
			while($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo '<option value="'.$fila['account_id'].'">'.$fila['manual_code'].' '.$fila['description'].'</option>';
			}
		}else{
			echo '<option>No existen cuentas</option>';
		}
	break;
	/* se cambio que si es beneficiario pagador ahora muestre todas las afectables
	 * esto por el hecho de un prestamo que el pagador/bene puede tener cuentas de pasivo y ser acreedor
	 * antes solo estaban las cuentas de clientes
	 */
	case 21:
		//$sql=$conection->query('SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config)');
		$sql=$conection->query('SELECT co.account_id, co.description,co.manual_code
			 FROM cont_accounts co
			 WHERE co.status=1 
			 AND co.removed=0 
			 AND co.affectable=1 ');
		
		if($sql->num_rows>0){
			echo "<option value='0'>NINGUNA</option>";
			
			while($fila=$sql->fetch_array(MYSQLI_BOTH)){
				echo '<option value="'.$fila['account_id'].'">'.$fila['manual_code'].' '.utf8_encode($fila['description']).'</option>';
			}
		}else{
			echo '<option>No existen cuentas</option>';
		}
	break;
	case 22:
		$sql=$conection->query('select * from accelog_perfiles_me where idmenu=1932');
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	
}


?>