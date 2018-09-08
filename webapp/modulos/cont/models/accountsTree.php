<?php 
/*===============================================================================
=            models/accountsTree.php - Miguel Angel Velazco Martinez            =
===============================================================================*/

/**

	TODO:
	- changeFather
		- En la operacion "Mover" permite re-asignar el valor de la cuenta padre.
	- deleteAccount
		- Realiza la "eliminacion" de una cuenta contable.
	- fillSelect
		- Permite realizar el llenado de un select por medio de una plantilla basica de consulta.
	- getAccounts
		- Obtiene el Arbol contable que se muestra en la interfaz.
	- insertAccount
		- Inserta una nueva cuenta contable.
	- updateAccount
		- Actualiza una cuenta.
	- updateAffectables
		- Redefine las cuentas afectables.

**/


/*-----  End of models/accountsTree.php - Miguel Angel Velazco Martinez  ------*/

require 'connection_sqli.php';

class Account extends Connection
{
	public function changeFather($data)
	{
		$sql = '';
		for ( $i=0 ; $i < count($data) ; $i++ )
		{ 
			$TipoNiveles = $this->query("SELECT TipoNiveles FROM cont_config WHERE id=1");
			$TipoNiveles = $TipoNiveles->fetch_assoc();

			$account_id         = $data[$i]['account_id'];
			$account_code       = $data[$i]['account_code'];
        	$father_account_id  = $data[$i]['father_account_id'];
        	$main_father        = $data[$i]['main_father'];
			$sql  .= "UPDATE cont_accounts SET ";
			$sql .= "father_account_id = '" . $father_account_id . "', ";
			$sql .= "account_code = '" . $account_code."', ";

			if($TipoNiveles['TipoNiveles'] == 'a')
			{
				$sql .= "manual_code = '" . $account_code."', ";
			}

			$sql .= "main_father = '" . $main_father."', ";
			$sql .= "mod_date = '" . date("Y-m-d") . "' ";
			$sql .= "WHERE account_id = " . $account_id . ";";
		}
		
		$data = $this->dataTransact($sql);
		return $data;
	}
	public function deleteAccount($data)
	{
		$account_id = $data['account_id'];
		$sql  = "UPDATE cont_accounts SET ";
		$sql .= "removed = '1', ";//18
		$sql .= "mod_date = '". date("Y-m-d")."' ";
		$sql .= "WHERE account_id IN(".$account_id.");";

		$data = $this->transact($sql);
		$this->transaccion('Borra Cuenta',$sql);
		return $data;
	}
	public function fillSelect($value,$desc,$table,$where = 1)
	{
		$optList = "";// Almacena la lista de options
		$query = "SELECT $value , UPPER($desc) FROM $table WHERE $where;";
		if($result = $this->query($query))
		{
			for ($i=0; $i < $result->num_rows ; $i++) 
			{ 
				$data = $result->fetch_array(MYSQLI_BOTH);
				$optList .= "<option value='". $data[0] ."'>". $data[1] ."</option>";
			}
			return $optList;
		}
		else
		{
			return false;
		}
	}

	public function cuentas_mayor($t)
	 {
	 	$cuentaoficial = "a.nif";
	 	$where = "a.main_account = 1";
	 	if(!intval($t))
	 	{

	 		$cuentaoficial = "a.cuentaoficial";
	 		$where = "removable = 1";
	 	}


	 	$tipoCuenta = $this->tipoCuenta();
	 	if($tipoCuenta == 'm')
			{
				$orden = 'a.manual_code';
				$masSplits = '';
			}
			//Si el tipo de codigo de la cuenta es automatico
			if($tipoCuenta == 'a')
			{
				$orden = '';
				$masSplits = '';
				for($i=3;$i<=8;$i++)
				{
					if($i!=8)
					{
						$orden .= "CAST(h$i AS UNSIGNED), ";
					}
					else
					{
						$orden .= "CAST(h$i AS UNSIGNED) ";
					}
					$masSplits .= "REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', $i), LENGTH(SUBSTRING_INDEX(a.account_code, '.', $i -1)) + 1),'.', '') AS h$i, ";
				}
			}
	 	$myQuery = "SELECT 
	 	a.account_id, 
	 	a.manual_code, 
	 	a.description, 
	 	REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 1),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 1 -1)) + 1),
       '.', '') AS h1,
	   REPLACE(SUBSTRING(SUBSTRING_INDEX(a.account_code, '.', 2),
       LENGTH(SUBSTRING_INDEX(a.account_code, '.', 2 -1)) + 1),
       '.', '') AS h2,
	 	$masSplits
	 	$cuentaoficial
	 	FROM cont_accounts a 
	 	WHERE $where AND a.removed=0 
	 	ORDER BY CAST(h1 AS UNSIGNED),CAST(h2 AS UNSIGNED) , $orden";
	 	$result = $this->query($myQuery);
		return $result;	
	 }
public function oficial()
	{
		$optList = "";// Almacena la lista de options
		$query = "SELECT * FROM cont_diarioficial;";
		if($result = $this->query($query))
		{
			for ($i=0; $i < $result->num_rows ; $i++) 
			{ 
				$data = $result->fetch_array(MYSQLI_BOTH);
				$optList .= "<option value='". $data[0] ."'>".$data[2]."(" .$data[1].")"."</option>";
			}
			return $optList;
		}
		else
		{
			return false;
		}
	}
	public function getAccounts()
	{
		$TipoNiveles = "SELECT TipoNiveles FROM cont_config WHERE id=1";
		$TipoNiveles = $this->query($TipoNiveles);
		$TipoNiveles = $TipoNiveles->fetch_assoc();
		$TipoNiveles = $TipoNiveles['TipoNiveles'];
		if($TipoNiveles == 'm')
		{
			$TipoNiveles = 'c.manual_code';
		}
		else
		{
			$TipoNiveles = "1*SUBSTRING_INDEX(c.manual_code, '.', 1) ASC, 1*SUBSTRING_INDEX(c.manual_code, '.', -1) ASC";
		}
		$accounts = "";
		//$qry = "SELECT *,(SELECT COUNT(*) FROM cont_movimientos m WHERE m.Cuenta = c.account_id AND m.Activo = 1) AS activity FROM cont_accounts c ORDER BY father_account_id,account_id;";
		$qry = "SELECT *,(SELECT codigo_agrupador FROM cont_diarioficial WHERE id = cuentaoficial) AS codigo_agrupador, (SELECT COUNT(*) FROM cont_movimientos m,cont_polizas p WHERE m.Cuenta = c.account_id AND m.Activo = 1 and p.id=m.IdPoliza and p.activo=1) AS activity FROM cont_accounts c ORDER BY father_account_id, $TipoNiveles ;";
		$result = $this->query($qry);
		$i = 0;
		for ($i=0; $i < $result->num_rows ; $i++)
		{ 
			$data = $result->fetch_array(MYSQLI_ASSOC);
			$accounts[$i] = $data;
		}
		return $accounts;
	}
	public function getMask()
	{
		$qry = "SELECT Estructura FROM cont_config LIMIT 1;";
		$result = $this->query($qry);
		$data = $result->fetch_array(MYSQLI_ASSOC);
		return $data["Estructura"];
	}
	public function insertAccount($data)
	{
		
			$myQuery = "SELECT account_code FROM cont_accounts WHERE father_account_id = ".$data['father_account_id']." AND removed = 0 ORDER BY account_id DESC LIMIT 1";
			$q = $this->query($myQuery);
			$q = $q->fetch_assoc();
			$q = $q['account_code'];
			$q = explode('.',$q);
			$q2 = end($q);
			$q2 = intval($q2)+1;
			$data['account_code'] = '';
			for($i=0;$i<=count($q)-2;$i++)
			{
				$data['account_code'] .= $q[$i].".";	
			}
			$data['account_code'] .= $q2;

			$myQuery = "SELECT TipoNiveles FROM cont_config WHERE id=1";
			$res = $this->query($myQuery);
			$res = $res->fetch_assoc();
			if($res['TipoNiveles'] == 'a')
				$data['manual_code'] = $data['account_code'];
		
		// $account_id         = $data['account_id'];
        $account_code       = $data['account_code'];
        $manual_code        = $data['manual_code'];
        $description        = $data['description'];
        $sec_desc           = $data['sec_desc'];
        $accout_type        = $data['account_type'];
        $status             = $data['status'];
        $main_account       = $data['main_account'];
        $cash_flow          = 0;
        $reg_date           = date('Y-m-d G:i:00');
        $coin_id            = $data['currency_id'];
        $group_dig          = $data['group_dig'];
        $id_sucursal        = $data['id_sucursal'];
        $seg_neg_mov        = 0;//$data['seg_neg_mov'];
        $affectable         = $data['affectable'];
		//$mod_date           = NULL;
        $father_account_id  = $data['father_account_id'];
        $removable          = 1;
        $account_nature     = $data['account_nature'];
        $main_father		= $data['main_father'];
		$cuentaoficial		= $data['cuentaoficial'];
		$sql  = "INSERT INTO cont_accounts (";
		// $sql .= "account_id,";
		$sql .= "account_code,";
		$sql .= "manual_code,";
		$sql .= "description,";
		$sql .= "sec_desc,";
		$sql .= "account_type,";//5
		$sql .= "status,";
		$sql .= "main_account,";
		$sql .= "cash_flow,";
		$sql .= "reg_date,";
		$sql .= "currency_id,";//10
		$sql .= "group_dig,";
		$sql .= "id_sucursal,";
		$sql .= "seg_neg_mov,";
		$sql .= "affectable,";
		$sql .= "mod_date,";//15
		$sql .= "father_account_id,";
		$sql .= "removable,";
		$sql .= "account_nature,";
		$sql .= "removed,";
		$sql .= "main_father,cuentaoficial)";
		$sql .= "VALUES (";//19
		// $sql .= "'". $account_id ."', ";
		$sql .= "'". $account_code ."', ";
		$sql .= "'". $manual_code ."', ";
		$sql .= "'". $description ."', ";
		$sql .= "'". $sec_desc ."', ";
		$sql .= "'". $accout_type ."', ";//5
		$sql .= "'". $status ."', ";
		$sql .= "'". $main_account ."', ";
		$sql .= "'". $cash_flow ."', ";
		$sql .= "'". $reg_date ."', ";
		$sql .= "'". $coin_id ."', ";//10
		$sql .= "'". $group_dig ."', ";
		$sql .= "'". $id_sucursal ."', ";
		$sql .= "'". $seg_neg_mov ."', ";
		$sql .= "'". $affectable ."', ";
		$sql .= "NULL, ";//15
		$sql .= "'". $father_account_id ."', ";
		$sql .= "'". $removable ."', ";
		$sql .= "'" . $account_nature . "', ";
		$sql .= "0,";// removed
		$sql .= "'" . $main_father . "', ";
		$sql .= "'" . $cuentaoficial . "' ";
		$sql .= ");";//20
		
		if($main_account == 1 || $main_account == 2 )// si la cuenta es de mayor
		{
			$sql .= "UPDATE ";
			$sql .= "	cont_accounts ";
			$sql .= "SET";
			$sql .="	affectable = 0 ";
			$sql .= ($main_account == 1) ? "	,main_father = LAST_INSERT_ID() " : "";
			$sql .= "WHERE";
			$sql .= "	account_id = LAST_INSERT_ID();";
		}
		else if($main_account == 3){
			$sql .= "UPDATE ";
			$sql .= "	cont_accounts ";
			$sql .= "SET";
			$sql .= "	affectable = 1";
			$sql .= "WHERE";
			$sql .= "	account_id = LAST_INSERT_ID();";
		}
		
		$sql .= "SELECT LAST_INSERT_ID();";


		$data = $this->dataTransact($sql);
		if ( !$data )
		{
			return "Transaccion Fallida\n".var_dump($data);
		}
		else
		{
			$this->transaccion('Inserta Cuenta',$sql);
			return $data[0];
		}
	}
	public function updateAccount($data)
	{


		$account_id         = $data['account_id'];
        $account_code       = $data['account_code'];
        $manual_code        = $data['manual_code'];
        $description        = $data['description'];
        $sec_desc           = $data['sec_desc'];
        $account_type        = $data['account_type'];
        $status             = $data['status'];
        $main_account       = $data['main_account'];
        $cash_flow          = 0;
        $reg_date           = date('Y-m-d G:i:00');
        $currency_id            = $data['currency_id'];
        $group_dig          = $data['group_dig'];
        $id_sucursal        = $data['id_sucursal'];
        $seg_neg_mov        = 0;//$data['seg_neg_mov'];
        // $affectable         = $data['affectable'];
		$mod_date           = date('Y-m-d G:i:00');
        $father_account_id  = $data['father_account_id'];
        $removable          = 1;
        $account_nature     = $data['account_nature'];
        $main_father        = $data['main_father'];
		$cuentaoficial 		= $data['cuentaoficial'];

        $affectable = ($main_account == 1 || $main_account == 2) ? 0 : 1;

		$sql  = "UPDATE cont_accounts SET ";
		// $sql .= "account_id,";
		//$sql .= "account_code = '".$account_code."',";
		
		$myQuery = "SELECT TipoNiveles FROM cont_config WHERE id=1";
		$res = $this->query($myQuery);
		$res = $res->fetch_assoc();
		if($res['TipoNiveles'] == 'm')
			$sql .= "manual_code = '".$manual_code."', ";
		
		$sql .= "account_nature = '".$account_nature."', ";

		$sql .= "description = '".$description."', ";
		$sql .= "sec_desc = '".$sec_desc."', ";
		$sql .= "account_type = '".$account_type."', ";//5
		$sql .= "status = '".$status."', ";
		$sql .= "main_account = '".$main_account."', ";
		$sql .= "cash_flow = '".$cash_flow."', ";
		$sql .= "reg_date = '".$reg_date."', ";
		$sql .= "currency_id = '".$currency_id."', ";//10
		$sql .= "group_dig = '".$group_dig."', ";
		$sql .= "id_sucursal = '".$id_sucursal."', ";
		$sql .= "seg_neg_mov = '".$seg_neg_mov."', ";
		$sql .= "affectable = '".$affectable."', ";
		$sql .= "mod_date = '".$mod_date."', ";//15
		$sql .= "father_account_id = '".$father_account_id."', ";
		$sql .= "main_father = '" . $main_father . "', " ;
		$sql .= "cuentaoficial = '".$cuentaoficial."'";
		// $sql .= "removable = '".$removable."', ";
		// $sql .= "removed = '".$removed."' ";//19
		$sql .= "WHERE account_id = '".$account_id."';";
		 $data = $this->transact($sql);
		 $this->transaccion('Modifica Cuenta',$sql);
		 return $data;
	}
	public function updateAffectables()
	{
		$sql  = "CREATE TEMPORARY TABLE temp_father ";
		$sql .= "AS ";
		$sql .= "SELECT DISTINCT ";
		$sql .= "	father_account_id ";
		$sql .= "FROM cont_accounts ";
		$sql .= "WHERE ";
		$sql .= "	removed = 0 ";
		$sql .= "	AND father_account_id  NOT IN ";
		$sql .= "(SELECT account_id FROM cont_accounts WHERE removed = 1); ";
		$sql .= "UPDATE ";
		$sql .= "	cont_accounts ";
		$sql .= "SET ";
		$sql .= "	affectable = 0 ";
		$sql .= "WHERE ";
		$sql .= "	account_id IN (SELECT father_account_id FROM temp_father) OR (main_account = 1 OR main_account = 2) ; ";
		$sql .= "UPDATE ";
		$sql .= "	cont_accounts ";
		$sql .= "SET ";
		$sql .= "	affectable = 1 ";
		$sql .= "WHERE ";
		$sql .= "	account_id NOT IN (SELECT father_account_id FROM temp_father) AND main_account = 3; DROP TABLE temp_father;";

		$data = $this->transact($sql);
		return $data;
	}
	public function cuentas(){
		$result = $this->query('SELECT * FROM `cont_accounts`');
		return $result;
	 }

	 public function getAccountMode()
	 {
	 	$result = $this->query('SELECT TipoNiveles FROM `cont_config` WHERE id=1');
	 	$result = $result->fetch_object();
		return $result->TipoNiveles;
	 }

	 private function tipoCuenta()
		{
			$myQuery = "SELECT TipoNiveles FROM cont_config WHERE id=1";
			$tc = $this->query($myQuery);
			$tc = $tc->fetch_assoc();
			return $tc['TipoNiveles'];
		}

	 

	 public function clas_nif()
	 {
	 	$myQuery = "SELECT* FROM cont_clasificacion_nif";
	 	$result = $this->query($myQuery);
		return $result;	
	 }

	 public function clas_oficial()
	 {
	 	$myQuery = "SELECT* FROM cont_diarioficial";
	 	$result = $this->query($myQuery);
		return $result;	
	 }

	 public function UpdateNif($IdCuenta,$Valor,$Tipo)
	 {

	 	$campo = "nif";
	 	if(!intval($Tipo))
	 	{
	 		$campo = "cuentaoficial";
	 	}
	 	$myQuery = "UPDATE cont_accounts SET $campo = $Valor WHERE account_id = $IdCuenta";
	 	$this->query($myQuery);
	 }

	 public function getManualCode($data)
	 {
	 	$myQuery = "SELECT manual_code FROM cont_accounts WHERE account_id = ".$data['account_id'];
	 	$manualCode = $this->query($myQuery);
	 	$manualCode = $manualCode->fetch_assoc();
	 	return $manualCode['manual_code'];
	 }
}
?>
