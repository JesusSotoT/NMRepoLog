<?php
	
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
//$otra1=10;
    $otra1=$_SESSION['otra1'];
	$otra2=$_SESSION['otra2'];
	$asume=$_SESSION['asume'];
	$cadena =$_SESSION['cadena'];
	
	//$cadena='2,1,4,6,';
	//$asume=4;
	$tasasu='';
	
			if($asume==1234){ $tasasu='Otra Tasa 1'; }
			elseif($asume==12345){ $tasasu='Otra Tasa 2'; }
			else if($asume==123456){ $tasasu='No Calcula';}
			elseif($asume!=1234 && $asume!=12345 && $asume!=123456){
				$tas=$conection->query('select tasa from cont_tasas where id='.$asume);
				if($ta=$tas->fetch_array(MYSQLI_ASSOC)){
					$tasasu=$ta['tasa'];
				}
			}
	
	


$sql=$conection->query('SELECT * FROM mrp_proveedor where idTasaPrvasumir=1234 ORDER BY idPrv desc LIMIT 1');
if($sql->num_rows>0){
	if($row=$sql->fetch_array(MYSQLI_ASSOC)){
		$idprove=$row['idPrv'];
		$ivas=explode(',',$cadena);
		if(isset($_SESSION['idproveedor'])){
		$upd=$conection->query('update cont_tasaPrv set visible=0 where idPrv='.$_SESSION['idproveedor']);
		}
		for($i=0;$i<count($ivas)-1;$i++){
			//echo $ivas[$i];
			if($ivas[$i]!=1234 && $ivas[$i]!=12345 && $ivas[$i]!=123456 ){ 
			$tas=$conection->query('select * from cont_tasas where id='.$ivas[$i]);
				if($ta=$tas->fetch_array(MYSQLI_ASSOC)){
					$tasa=$ta['tasa'];
					$valor=$ta['valor'];
				}
			}
			elseif($ivas[$i]==1234){ $tasa='Otra Tasa 1'; 
			//$bus=str_replace('%', '', @$otra1);
			$valor=@$otra1; }
			elseif($ivas[$i]==12345){ $tasa='Otra Tasa 2'; 
			//$bus2=str_replace('%', '', @$otra2);
			$valor=@$otra2; }
			elseif($ivas[$i]==123456){ $tasa='No Calcula'; 
			$valor=0.0;
			}
			if(isset($_SESSION['idproveedor'])){
				$sqlbusca=$conection->query('select * from `cont_tasaPrv` where tasa="'.$tasa.'" and valor='.$valor.' and idPrv='.$_SESSION['idproveedor']);
				if($b=$sqlbusca->num_rows>0){
					$upd=$conection->query('update cont_tasaPrv set visible=1 where tasa="'.$tasa.'" and idPrv='.$_SESSION['idproveedor']);
				}else{
					$sql2=$conection->query('insert into cont_tasaPrv (idPrv,tasa,valor,visible) values ('.$_SESSION['idproveedor'].',"'.$tasa.'",'.$valor.',1);');
				}	
			}else{
				$sql2=$conection->query('insert into cont_tasaPrv (idPrv,tasa,valor) values ('.$idprove.',"'.$tasa.'",'.$valor.');');
				
			}
			
		}
	$sql3=$conection->query('select * from cont_tasaPrv where idPrv='.$idprove.' and tasa="'.$tasasu.'"');		
	if($sql3->num_rows>0){
		if($row2=$sql3->fetch_array(MYSQLI_ASSOC)){
			// $consultaid=$conection->query('select idTasaPrvasumir from mrp_proveedor where idTasaPrvasumir='.$row2['id'].' and idPrv='.$idprove);
			// if($consultaid->num_rows()<0){
				// $asumir=$conection->query('update mrp_proveedor set idTasaPrvasumir='.$row2['id'].' where idPrv='.$idprove);
// 				
			// }
			$asumir=$conection->query('update mrp_proveedor set idTasaPrvasumir='.$row2['id'].' where idPrv='.$idprove);
			//if($compro=$asumir-affected_rows()>0){
			
			}
			
	}
	}
}

     unset($_SESSION['otra1']);
	 unset($_SESSION['otra2']);
	 unset($_SESSION['asume']);
	 unset($_SESSION['cadena']);
	 unset($_SESSION['idproveedor']);
$conection->close();