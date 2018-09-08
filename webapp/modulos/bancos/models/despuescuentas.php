<?php
// include("../../netwarelog/webconfig.php");
// $conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
// if($_SESSION['idbancaria']==0){
// $consulta = $conection->query('select * from bco_cuentas_bancarias order by idbancaria desc limit 1');
// }else{
	// $consulta = $conection->query('select * from bco_cuentas_bancarias where idbancaria='.$_SESSION['idbancaria']);
// }
// if($r=$consulta->fetch_array()){
	// // $sqlprevio = $conection->query("select * from bco_saldo_contable where idbancaria=".$r['idbancaria']." and status=1");
	// // if(($sqlprevio->num_rows<2)){
		// // $sql = $conection->query("update bco_saldo_contable set status=0 where idbancaria=".$r['idbancaria']);
		// // $sql = $conection->query("insert into bco_saldo_contable (fecha,saldoinicial,idbancaria,saldofinal) values('".date('Y-m-d') ."',".$r['saldoinicial'].",".$r['idbancaria'].",".$r['saldoinicial'].");");
	// // }
	// if($_SESSION['cheques']==0){
		// $sqldele = $conection->query("delete from bco_controlNumeroCheque where idbancaria=".$r['idbancaria']);
		// $sqlcheq = $conection->query("insert into bco_controlNumeroCheque  (numeroinicial,numerofinal,actualrango,numeroactual,idbancaria) values ('".$r['numInicialCheque']."','".$r['numFinalCheque']."','".$r['numInicialCheque']."','".$r['numeroactual']."',".$r['idbancaria'].")");
	// }
	// unset($_SESSION['idbancaria']);
	// unset($_SESSION['cheques']);
// }
?>