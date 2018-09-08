<?php
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ArbolModel extends Connection
{
		
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
		$qry = "SELECT *,(SELECT COUNT(*) FROM cont_movimientos m,cont_polizas p WHERE m.Cuenta = c.account_id AND m.Activo = 1 and p.id=m.IdPoliza and p.activo=1) AS activity FROM cont_accounts c ORDER BY father_account_id, $TipoNiveles ;";
		$result = $this->query($qry);
		$i = 0;
		for ($i=0; $i < $result->num_rows ; $i++)
		{ 
			$data = $result->fetch_array(MYSQLI_ASSOC);
			$accounts[$i] = $data;
		}
		return $accounts;
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
public function oficial()
	{
		$optList = "";// Almacena la lista de options
		$query = "SELECT * FROM cont_diarioficial;";
		if($result = $this->query($query))
		{
			for ($i=0; $i < $result->num_rows ; $i++) 
			{ 
				$data = $result->fetch_array(MYSQLI_BOTH);
				$optList .= "<option value='". $data[0] ."'>"."(" .$data[1].") ".$data[2]."</option>";
			}
			return $optList;
		}
		else
		{
			return false;
		}
	}
	
	public function getMask()
	{
		$qry = "SELECT Estructura FROM cont_config LIMIT 1;";
		$result = $this->query($qry);
		$data = $result->fetch_array(MYSQLI_ASSOC);
		return $data["Estructura"];
	}
	

	 public function getAccountMode()
	 {
	 	$result = $this->query('SELECT TipoNiveles FROM `cont_config` WHERE id=1');
	 	$result = $result->fetch_object();
		return $result->TipoNiveles;
	 }

	 public function guardar($numero,$nombre,$nombre_idioma,$subcuentade,$naturaleza,$moneda,$clasificacion,$digito,$estatus,$idcuenta,$tipoinstancia)
	 {

	 	if(intval($subcuentade))
	 	{
	 		//DATOS DE LA CUENTA PADRE
		 	$myQuery = "SELECT* FROM cont_accounts WHERE account_id = $subcuentade AND removed=0 AND main_account != 3";
		 	$datospadre = $this->query($myQuery);
		 	$datospadre = $datospadre->fetch_assoc();

		 	//SI EL PADRE NO EXISTE REGRESA ERROR
		 	if(!intval($datospadre['account_id']))
		 	{
		 		return 1;
		 	}
	 	}
	 	else
	 	{
	 		$datospadre['main_father'] = 0;	
	 	}

	 	//SI SE TRATA DE UNA CUENTA AFECTABLE Y NO EXISTE ANCESTRO DE MAYOR
	 	if(!intval($datospadre['main_father']) AND intval($clasificacion) == 3 AND !$tipoinstancia)
	 	{
	 		return 6;
	 	}
	 	//SI SE TRATA DE UNA CUENTA DE MAYOR PERO YA EXISTE UNA ARRIBA ERROR
	 	if(intval($datospadre['main_father']) AND intval($clasificacion) == 1)
	 	{
	 		return 2;
	 	}
	 	else//SI NO ES DE MAYOR O NO EXISTE ANCESTRO DE MAYOR
	 	{
	 		$affectable = 0;
	 		if(intval($clasificacion) == 3) $affectable = 1;

	 		//SI TIENE HERMANOS HEREDA SUS ATRIBUTOS Y AGREGA EL NUMERO DE CUENTA + 1 , SI NO TIENE HERMANOS COMIENZA CON 1
	 		//$myQuery = "SELECT account_code FROM cont_accounts WHERE father_account_id = $subcuentade AND removed = 0 ORDER BY account_id DESC LIMIT 1";
	 		$myQuery = "SELECT account_code, SUBSTRING_INDEX( account_code , '.', -1 ) AS orden FROM cont_accounts WHERE father_account_id =$subcuentade AND removed = 0 ORDER BY CAST(orden AS UNSIGNED) DESC LIMIT 1;";
	 		$hermanos = $this->query($myQuery);
	 		$hermanos = $hermanos->fetch_assoc();

	 		if(intval($subcuentade))
	 		{
	 			 		if($hermanos)
	 			 		{
	 			 			$hermanos = explode('.',$hermanos['account_code']);
	 				 		$hermanos2 = end($hermanos);
	 						$hermanos2 = intval($hermanos2)+1;
	 						$account_code .= $datospadre['account_code'].".".$hermanos2;
	 			 		}
	 			 		else
	 			 		{
	 			 			$account_code = $datospadre['account_code'].".1";
	 			 		}
	 			 		$account_type = $datospadre['account_code'][0];
	 		}
	 		else
	 		{
	 			$account_code = intval($hermanos['account_code'])+1;
	 			
	 			//SI GENERA 4 TIENE QUE CAMBIAR A 5 DADO QUE 4 PERTENECE A LAS CUENTAS DE RESULTADOS
	 			if($account_code == 4)
	 				$account_code = 5;

	 			$account_type = $account_code;
	 		}
	 		
	 		//TIPO DE NIVELES DE CUENTAS
	 		$myQuery = "SELECT TipoNiveles FROM cont_config WHERE id=1";
			$res = $this->query($myQuery);
			$res = $res->fetch_assoc();
			if($res['TipoNiveles'] == 'a')
				$numero = $account_code;
			
			$where = '';
			if(intval($idcuenta))
				$where = "AND account_id != $idcuenta";

		 	//SI YA EXISTE EL NUMERO MANUAL DE LA CUENTA EN OTRA CUENTA QUE NO SEA LA MISMA
		 	$myQuery = "SELECT account_id FROM cont_accounts WHERE manual_code = '$numero' AND removed=0 $where";
		 	$existe = $this->query($myQuery);
		 	$existe = $existe->fetch_assoc();
		 	if(intval($existe['account_id']))
		 		return 5;


			$main_father = $datospadre['main_father'];

			//SI ES UNA ACTUALIZACION
		 	if(intval($idcuenta))
		 	{
		 		//BUSCA LOS DATOS ANTERIORES DE LA CUENTA
		 		$myQuery = "SELECT account_code,father_account_id,main_account,main_father FROM cont_accounts WHERE account_id = $idcuenta";
		 		$anterior_datos = $this->query($myQuery);
		 		$anterior_datos = $anterior_datos->fetch_assoc();
		 		$anterior_codigo = $anterior_datos['account_code'];
		 		$anterior_padre = $anterior_datos['father_account_id'];
		 		$anterior_main_account = $anterior_datos['main_account'];
		 		$anterior_main_father = $anterior_datos['main_father'];
		 		
		 		//SI SE TRATA DE UNA CUENTA DE MAYOR BUSCARA SI ENTRE SUS HIJOS NO HAY CUENTAS DE MAYOR, SI ES ASI MARCA ERROR, PUES SOLO SE PERMITE UNA CUENTA DE MAYOR POR RAMA
		 		if(intval($clasificacion) == 1)
		 		{
		 			$myQuery = "SELECT COUNT(account_id) AS num FROM cont_accounts WHERE removed = 0 AND account_code LIKE '$anterior_codigo.%' AND main_account = 1;";
			 		$hijos_mayor = $this->query($myQuery);
			 		$hijos_mayor = $hijos_mayor->fetch_assoc();
			 		if(intval($hijos_mayor['num']))
			 			return 2;
		 			$main_father = $idcuenta;
		 		}


		 		//SI EL TIPO DE CUENTA ANTERIOR ES AFECTABLE Y EL NUEVO TIPO ES DE MAYOR O DE TITULO, BUSCA QUE NO TENGA MOVIMIENTOS CONTABLES
		 		if(intval($anterior_main_account) == 3 AND intval($clasificacion) != 3)
		 		{
		 			$myQuery = "SELECT COUNT(m.Activo) AS num FROM cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza WHERE p.activo = 1 AND m.Activo = 1 AND m.Cuenta = $idcuenta;";
			 		$num = $this->query($myQuery);
			 		$num = $num->fetch_assoc();
			 		if(intval($num['num']))
			 			return 4;
		 		}

		 		//SI LA CUENTA SE VA A CAMBIAR A AFECTABLE, BUSCA QUE NO TENGA HIJOS, SI ES ASI MARCA ERROR , PORQUE NO PUEDEN HABER CUENTAS AFECTABLES CON HIJOS
		 		if(intval($clasificacion) == 3)
		 		{
		 			$myQuery = "SELECT COUNT(account_id) AS num FROM cont_accounts WHERE removed = 0 AND account_code LIKE '$anterior_codigo.%';";
			 		$tiene_hijos = $this->query($myQuery);
			 		$tiene_hijos = $tiene_hijos->fetch_assoc();
			 		if(intval($tiene_hijos['num']))
			 			return 3;
		 		}

		 		//INICIA ACTUALIACION DE LOS DATOS DE LA CUENTA EXISTENTE
		 		$myQuery = "UPDATE cont_accounts SET ";

		 		if($subcuentade != $anterior_padre)
		 		{
		 			$myQuery .= "account_code = '$account_code', account_type = $account_type, ";
		 			if($res['TipoNiveles'] == 'a')
		 				$myQuery .= "manual_code = '$numero', ";
		 		}

		 		if($res['TipoNiveles'] == 'm')
		 			$myQuery .= "manual_code = '$numero', ";

		 		$myQuery .= "description = '".strtoupper($nombre)."',
		 		sec_desc = '".strtoupper($nombre_idioma)."',
		 		status = $estatus,
		 		main_account = $clasificacion,
		 		currency_id = $moneda,
		 		affectable = $affectable,
		 		mod_date = DATE_SUB(NOW(), INTERVAL 6 HOUR),
		 		father_account_id = $subcuentade,
		 		account_nature = $naturaleza,
		 		main_father = $main_father,
		 		cuentaoficial = '$digito' 
		 		WHERE account_id = $idcuenta;";
		 		$this->query($myQuery);
				
				//GUARDA LA TRANSACCION EN EL REGISTRO
				$this->transaccion('Modifica Cuenta',$myQuery);

				
					//SI TIENE HIJOS MODIFICARA SU CUENTA DE MAYOR Y EL CODIGO
				 	$myQuery = "SELECT account_id,account_code,main_account FROM cont_accounts WHERE removed = 0 AND account_code LIKE '$anterior_codigo.%'";
				 	$hijos = $this->query($myQuery);
				 	$myQuery = '';

				 	while($hi = $hijos->fetch_assoc())
				 	{
				 		$myQuery .= "UPDATE cont_accounts SET ";
				 		$myQuery .= " removed = 0 ";
				 		if($subcuentade != $anterior_padre)
						{
					 		$ultimo_codigo = str_replace("$anterior_codigo.", '', $hi['account_code']);
					
					 		$myQuery .= ", account_code = '".$account_code.".".$ultimo_codigo."'";
					 	
					 		if($main_father != $anterior_main_father  && intval($hi['main_account']) != 1)
					 		{
					 			$myQuery .= ", main_father = $main_father ";
					 		}
					 		
					 		if($main_father != $anterior_main_father && intval($hi['main_account']) == 1)
					 		{
					 			$myQuery .= ", main_account = 2, main_father = $main_father ";
					 		}

					 		
				
					 		if($res['TipoNiveles'] == 'a')
					 		{
					 			$myQuery .= ", manual_code = '".$account_code.".".$ultimo_codigo."'";
					 		}
					 		
					 		$myQuery .= ", account_type = $account_type ";
					 	}
					 	elseif(intval($main_father))
					 	{
					 		$myQuery .= ", main_father = $main_father ";
					 		if(intval($hi['main_account']) == 1)
					 		{
					 			$myQuery .= ", main_account = 2 ";
					 		}
					 	}

					 	$myQuery .= " WHERE account_id = ".$hi['account_id']."; ";
				 	}

				 	if($myQuery != '')
				 		$this->multi_query($myQuery);
				
		 	}
		 	else
		 	{
		 		//SI ES UNA CUENTA NUEVA INSERTA EL REGISTRO, OBTIENE EL ULTIMO ID Y ACTUALIZA LA CUENTA DE MAYOR SI FUERA EL CASO	
		 		$myQuery = "INSERT INTO cont_accounts(account_id,account_code,manual_code,description,sec_desc,account_type,status,main_account,cash_flow,reg_date,currency_id,group_dig,id_sucursal,seg_neg_mov,affectable,mod_date,father_account_id,removable,account_nature,removed,main_father,cuentaoficial,nif) 
		 									VALUES(0,'$account_code','$numero','".strtoupper($nombre)."','".strtoupper($nombre_idioma)."',$account_type,$estatus,$clasificacion,0,DATE_SUB(NOW(), INTERVAL 6 HOUR),$moneda,0,0,0,$affectable,DATE_SUB(NOW(), INTERVAL 6 HOUR),$subcuentade,1,$naturaleza,0,$main_father,'$digito',0);";
		 		$ultima = $this->insert_id($myQuery);
		 		$myQuery2 = "";
			 	if(intval($clasificacion) == 1)
			 	{
			 		$myQuery2 = "UPDATE cont_accounts SET main_father = $ultima WHERE account_id = $ultima";
			 		$this->query($myQuery2);
			 	}

			 	$this->transaccion('Inserta Cuenta',$myQuery.'/'.$myQuery2);
		 	}
		 	// SI SE GUARDO SIN PROBLEMAS MANDA ALERT DE SATISFACTORIO
		 	return 10;
		 	
	 	}
	 }

	 function datosCuenta($idCuenta)
	 {
	 	$myQuery = "SELECT a.*, (SELECT CONCAT('( ',manual_code,' ) ',description) FROM cont_accounts WHERE account_id=a.father_account_id) AS description_father FROM cont_accounts a WHERE a.account_id = $idCuenta";
	 	$datos = $this->query($myQuery);
	 	$datos = $datos->fetch_assoc();
	 	return $datos;
	 }

	 function eliminarCuenta($idcuenta)
	 {
	 	//DATOS DE LA CUENTA
		$myQuery = "SELECT account_code,removable FROM cont_accounts WHERE account_id = $idcuenta";
		$datos = $this->query($myQuery);
		$datos = $datos->fetch_assoc();
		$account_code = $datos['account_code'];
		$removable = $datos['removable'];
		
		//SI LA CUENTA SE PUEDE ELIMINAR PASA AL SIGUIENTE PROCESO, SI NO MANDA ERROR
		if(intval($removable))
		{
			//BUSCA A SU DESCENDENCIA
			$myQuery = "SELECT account_id FROM cont_accounts WHERE removed = 0 AND account_code LIKE '$account_code.%';";
			$hijos = $this->query($myQuery);
			
			$cuentasElim=$cuentasElim2='';
			$cont=0;//CONTADOR DE HIJOS
			
			//CONCATENA A LOS HIJOS EN UNA CADENA PARA USAR EN CONSULTAS
			while($h = $hijos->fetch_object())
			{
				$cuentasElim .= "OR Cuenta = $h->account_id ";
				$cuentasElim2 .= "OR account_id = $h->account_id ";
				$cont++;
			}
			$myQuery = "SELECT COUNT(m.Cuenta) AS num FROM cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza WHERE p.activo = 1 AND m.Activo = 1 AND (Cuenta = $idcuenta $cuentasElim) ";
			$tieneMovimientos = $this->query($myQuery);
			$tieneMovimientos = $tieneMovimientos->fetch_assoc();
			
			//SI TIENE MOVIMIENTOS REGRESA ERROR, SI NO PASA AL SIGUIENTE PROCESO
			if(intval($tieneMovimientos['num']))
			{
				return 2;
			}
			else
			{
				$myQuery = "UPDATE cont_accounts SET removed = 1 WHERE account_id = $idcuenta $cuentasElim2";
				if($this->query($myQuery))
				{
					$this->transaccion('Eliminar Cuenta',$myQuery);
					return 10;
				}
				else
				{
					return 0;
				}
			}
			
		}
		else
		{
			//MANDANDO ERROR AL NO SER UNA CUENTA ELIMINABLE
			return 1;
		}
	 }

	 function tipoinstancia()
	 {
	 	$myQuery = "SELECT tipoinstancia FROM organizaciones WHERE idorganizacion=1;";
	 	$id = $this->query($myQuery);
		$id = $id->fetch_assoc();
		return $id['tipoinstancia'];
	 }

	 function corregirEstructura($vars)
	 {

	 	/*arreglar problemas arbol
	 	update cont_accounts
		set account_code = concat('2.1', substr(account_code, 2)), account_type = 1
		WHERE account_code LIKE '8%';*/
		
	 	$codigo = '';

	 	//Guarda respaldo tabla
	 	$fecha = date('Y_m_d_H_i_s',strtotime('-7 hour',strtotime(date('Y-m-d H:i:s'))));
	 	$myQuery = "CREATE TABLE cont_accounts_resp_$fecha LIKE cont_accounts;
	 				INSERT INTO cont_accounts_resp_$fecha SELECT* FROM cont_accounts;";

	 	for($i=1; $i<=6; $i++)
	 	{
	 		switch($i)
	 		{
	 			case 1:$codigo = $vars['activo'];break;
	 			case 2:$codigo = $vars['pasivo'];break;
	 			case 3:$codigo = $vars['capital'];break;
	 			case 4:$codigo = $vars['ingreso'];break;
	 			case 5:$codigo = $vars['egreso'];break;
	 			case 6:$codigo = $vars['orden'];break;
	 		}
	 			$tipo = $i;
	 			if($i == 4)
	 				$tipo = 4.1;
	 			if($i == 5)
	 				$tipo = 4.2;

	 			$myQuery .= "SELECT @a$i := (SELECT a.account_code FROM cont_accounts a WHERE a.manual_code = '$codigo' AND removed = 0);
	 				UPDATE cont_accounts SET account_code = REPLACE(account_code,@a$i, '$tipo') WHERE account_code LIKE CONCAT(@a$i,'%');
	 				UPDATE cont_accounts SET account_type = ".intval($tipo)." WHERE account_code LIKE '$tipo%';
	 				UPDATE cont_accounts SET main_father = 0 WHERE account_code LIKE '$tipo%' AND main_father NOT IN (SELECT account_id FROM (SELECT a.account_id FROM cont_accounts a WHERE a.account_type = ".intval($tipo)." AND a.account_code LIKE '$tipo%' AND removed = 0 AND main_account = 1) AS c);";
	 	}

	 	$myQuery .= " 	UPDATE cont_accounts SET main_father = account_id WHERE main_account = 1;

						UPDATE cont_accounts AS a
						INNER JOIN cont_accounts AS b ON b.account_id = a.father_account_id
						SET a.main_father = b.main_father WHERE a.main_account = 2;

						UPDATE cont_accounts AS a
						INNER JOIN cont_accounts AS b ON b.account_id = a.father_account_id
						SET a.main_father = b.main_father WHERE a.main_account = 3;";
	 	
	 	
		
		if($this->dataTransact($myQuery))
		{
			$this->transaccion('Corregir Arbol Mal Formado',"Corrige Arbol");
			return 1;
		}
		else
		{
				$this->transaccion('Correccion Arbol fallo',"---");
			return 0;
		}

		/*CREATE TABLE cont_accounts_resp LIKE cont_accounts;

INSERT INTO cont_accounts_resp SELECT* FROM cont_accounts;

SELECT @a := (SELECT a.account_code FROM cont_accounts a WHERE a.manual_code = '000-0040' AND removed = 0);
SELECT @b:= (SELECT a.account_code FROM cont_accounts a WHERE a.manual_code = '000-0050' AND removed = 0);

UPDATE cont_accounts SET account_code = REPLACE(account_code,@a, '4.1') WHERE account_code LIKE CONCAT(@a,'%');
UPDATE cont_accounts SET account_code = REPLACE(account_code,@b, '4.2') WHERE account_code LIKE CONCAT(@b,'%');

UPDATE cont_accounts SET account_type = 4 WHERE account_code LIKE '4%';

UPDATE cont_accounts SET main_father = 0 WHERE account_code LIKE '4.1%' AND main_father NOT IN (SELECT account_id FROM (SELECT a.account_id FROM cont_accounts a WHERE a.account_type = 4 AND a.account_code LIKE '4.1%' AND removed = 0 AND main_account = 1) AS c);

UPDATE cont_accounts SET main_father = 0 WHERE account_code LIKE '4.2%' AND main_father NOT IN (SELECT account_id FROM (SELECT a.account_id FROM cont_accounts a WHERE a.account_type = 4 AND a.account_code LIKE '4.2%' AND removed = 0  AND main_account = 1) AS c);

*/
	 }
	 
}
?>