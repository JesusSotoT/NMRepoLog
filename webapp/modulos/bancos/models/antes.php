<?php
include("../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);


switch ($_REQUEST['opc']) {
	case 1:
		$sql=$conection->query('select c.idbancaria,c.cuenta,b.nombre from bco_cuentas_bancarias c,cont_bancos b where c.idbanco=b.idbanco and c.activo=-1 and c.cancelada=0');
		while($row=$sql->fetch_array()){
			if($_REQUEST['idbancaria']==$row['idbancaria']){
				echo "<option value='".$row['idbancaria']."' selected>".utf8_encode($row['nombre'])."(".$row['cuenta'].")</option>";
			}else{
				echo "<option value='".$row['idbancaria']."'>".utf8_encode($row['nombre'])."(".$row['cuenta'].")</option>";
			}
		} 
		break;
	case 2:
		
		$sql=$conection->query('select * from bco_cuentas_bancarias where account_id='.$_REQUEST['idcuenta'].' and idbancaria!='.$_REQUEST['id']);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
		break;
	case 3:
		$sql=$conection->query('select * from cont_accounts where account_code like "1.1%" and affectable=1 and removed=0 order by description asc');
		while($row=$sql->fetch_array()){
			if($_REQUEST['idcuenta']==$row['account_id']){
	       		echo "<option value='".$row['account_id']."' selected>".utf8_encode($row['description'])."(".$row['manual_code'].")</option>";
	       	}else{
	       		echo "<option value='".$row['account_id']."'>".utf8_encode($row['description'])."(".$row['manual_code'].")</option>";
	       	}
		}
		break;
	case 4:
		$sql2=$conection->query('select * from cont_coin');
		while($row=$sql2->fetch_array()){
			if($_REQUEST['coin_id']==$row['coin_id']){
	     		echo "<option value='".$row['coin_id']."' selected>".utf8_encode($row['description'])."(".$row['codigo'].")</option>";	
			}else{
				echo "<option value='".$row['coin_id']."'>".utf8_encode($row['description'])."(".$row['codigo'].")</option>";	
			}
		}
	break;
	case 5:
		$_SESSION['idbancaria']=$_REQUEST['id'];
		$_SESSION['cheques']=$_REQUEST['cheques'];
		echo $_SESSION['cheques'];
		break;
	case 6:
		$sql = $conection->query("select * from bco_controlNumeroCheque where idbancaria=".$_REQUEST['idbancaria']." and (actualrango>numeroinicial || numeroactual>'".$_REQUEST['numeroactual']."')");
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}	
	break;
	case 7://nuevo
		$sql = $conection->query("select * from bco_clasificador where idNivel=2");
		if($sql->num_rows>0){
			while($row=$sql->fetch_array()){
				echo "<option value='".$row['id']."'>".utf8_encode($row['nombreclasificador'])."(".$row['codigo'].")</option>";	
			}
		}else{
			echo "<option value=0>No tiene categorias</option>";
		}	
	break;
case 8://nuevo
	$sql=$conection->query('select * from cont_accounts where affectable=1 and removed=0 order by description asc');
		while($row=$sql->fetch_array()){
	       		echo "<option value='".$row['account_id']."' >".utf8_encode($row['description'])."(".$row['manual_code'].")</option>";
	       	
		}
	break;
	case 9://edicion dependencia
		$sql = $conection->query("select * from bco_clasificador where idNivel=2");
		if($sql->num_rows>0){
			while($row=$sql->fetch_array()){
				if($_REQUEST['depen']==$row['id']){ $se = "selected";}else{$se="";}
				
				echo "<option value='".$row['id']."' $se>".utf8_encode($row['nombreclasificador'])."(".$row['codigo'].")</option>";	
			}
		}else{
			echo "<option value=0>No tiene categorias</option>";
		}	
	break;
case 10://edicion cuenta
	$sql=$conection->query('select * from cont_accounts where affectable=1 and removed=0 order by description asc');
		while($row=$sql->fetch_array()){
			if($_REQUEST['cuenta']==$row['account_id']){ $se = "selected";}else{$se="";}
			echo "<option value='".$row['account_id']."'$se >".utf8_encode($row['description'])."(".$row['manual_code'].")</option>";
	       	
		}
	break;
	case 11://cuenta contable acorde a moneda
	$sql=$conection->query('select * from cont_accounts where affectable=1 and removed=0 and currency_id='.$_REQUEST['idmoneda'].' order by description asc');
		while($row=$sql->fetch_array()){
			echo "<option value='".$row['account_id']."' >".utf8_encode($row['description'])."(".$row['manual_code'].")</option>";
		}
	break;
	case 12://valida si el tipo de documento esta relacionado en un documento
		$sql=$conection->query('select * from bco_documentos where idTipoDoc='.$_REQUEST['idtipo']);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 13:
		$sql=$conection->query('select * from cont_coin');
		if($sql->num_rows>0){
			while($row=$sql->fetch_array()){
				echo "<option value='".$row['coin_id']."' $se>".utf8_encode($row['description'])."(".$row['codigo'].")</option>";	
			}
		}
	break;
	/* verificar si una categoria tiene hijos */
	case 14:
		$sql=$conection->query('select * from bco_clasificador where  idNivel=1 and cuentapadre='.$_REQUEST['id']);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 15://verifica si una subcategoria tiene documentos bancario asociados
		$sql=$conection->query("select id from bco_documentos d where  d.idclasificador=".$_REQUEST['id']." 
						union 
							select id from bco_documentoSubcategorias s where s.idSubcategoria=".$_REQUEST['id']);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 16://comprueba si la cuenta ya fue conciliada
		$sql=$conection->query("select * from bco_saldos_conciliacion where idbancaria=".$_REQUEST['idbancaria']);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 17://comprueba si cuenta ya esta en un documento
		$sql=$conection->query('select * from bco_documentos where idbancaria='.$_REQUEST['idbancaria']);
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	
}


$conection->close();
?>