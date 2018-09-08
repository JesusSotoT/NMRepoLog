<?php
	include("../../../netwarelog/webconfig.php");
	
	$funcion = $_POST['funcion'];
	
	if($funcion != "compruebaChequeOTarjetaDuplicados")
	{
		$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		$connection->set_charset('utf8');
		$funcion($connection);
		mysqli_close($connection);
	}
	else 
	{
		$funcion();
	}

//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------

	function cargaDatosCuenta($connection)
	{
		$id = $_POST["id"];
		$result = $connection->query("  SELECT c.fechacargo, c.fechavencimiento, c.idVenta, c.idCliente, c.concepto, c.monto, c.saldoabonado, c.saldoactual, c.estatus, cl.nombre, cl.nombretienda FROM cxc c 
										INNER JOIN comun_cliente cl ON c.idCliente = cl.id 
										WHERE c.idCxc = ".$id);

		if($result->num_rows > 0)
		{
			if($row = $result->fetch_array())
				echo $row["fechacargo"].'$$$^^^###///'.$row["fechavencimiento"].'$$$^^^###///'.$row["concepto"].'$$$^^^###///'.$row["monto"].'$$$^^^###///'.$row["saldoabonado"].'$$$^^^###///'.$row["saldoactual"].'$$$^^^###///'.$row["estatus"].'$$$^^^###///'.$row["idVenta"].'$$$^^^###///'.$row['idCliente'].'$$$^^^###///'.$row['nombre'].'$$$^^^###///'.$row['nombretienda'];	
		}
		else
			echo "Hubo un problema al cargar los datos. Recargue la pagina";
	}
	
	function cargaFormasDePago($connection)
	{
		$result = $connection->query("SELECT idFormapago, nombre FROM forma_pago WHERE idFormapago != 6");
		$select_formas_pago = "<select id='forma_pago' style='width: 100%;' onchange='compruebaChequeOTarjetaRegalo();'>";
		$select_formas_pago .= "<option value=''>Selecciona una forma de pago</option>";
		
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_array())
			{
				$select_formas_pago .= "<option value='".$row['idFormapago']."'>".$row['nombre']."</option>";
			}
		}
		$select_formas_pago .= "</select></div>";
		echo $select_formas_pago;
	}
	
	function consultaPagos($connection)
	{
		$id = $_POST["id"];
		$result = $connection->query("	SELECT c.idCxcpagos, c.fecha, c.monto, c.saldoinicial, c.saldofinal, c.idFormapago, c.referencia, f.nombre 
										FROM cxc_pagos c
										INNER JOIN forma_pago f ON f.idFormapago = c.idFormapago
										WHERE idCxc = ".$id." ORDER BY idCxcpagos;");
										
		$tabla_pagos = "<div id='pagos' style='height:200px; width: 95%; border:1px solid #006efe; overflow:auto; padding: 10px;' >";
		
		if($result->num_rows > 0)
		{			
			$tabla_pagos .= "	
			
			<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
				<tr>
					<th width=25% style='border-bottom: 1px solid #006efe;'>Fecha</th>
					<th width=10% style='border-bottom: 1px solid #006efe;'>Abono</th>
					<th width=40% style='border-bottom: 1px solid #006efe;'>Forma de pago</th>
					<th width=10% style='border-bottom: 1px solid #006efe;'>Saldo inicial</th>
					<th width=25% style='border-bottom: 1px solid #006efe;'>Saldo final</th>
				</tr>";
			
			$i = 0;
			
			while($row = $result->fetch_array())
				$rows[] = $row;
			foreach($rows as $row)
			{
				if($row["idFormapago"] == 2 || $row["idFormapago"] == 3)
				{
					$tabla_pagos .= "
					<tr>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='fecha_".$i."'>			".$row["fecha"]."			</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='monto_".$i."'>			$".number_format($row["monto"], 2, '.', ',')."			</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='formapago_".$i."'>		".$row["nombre"]." (No.".$row["referencia"].")"."			</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='saldoinicial_".$i."'>	$".number_format($row["saldoinicial"], 2, '.', ',')."	</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; '><div id='saldofinal_".$i."' style='text-align: right;'>	$".number_format($row["saldofinal"], 2, '.', ',')."		</div></td>
						 <input type='hidden' id='idCxcpago_".$i."' value=".$row["idCxcpagos"].">
					 </tr>";
				}
				else 
				{
					$tabla_pagos .= "
					<tr>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='fecha_".$i."'>			".$row["fecha"]."			</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='monto_".$i."'>			$".number_format($row["monto"], 2, '.', ',')."			</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='formapago_".$i."'>		".$row["nombre"]."			</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='saldoinicial_".$i."'>	$".number_format($row["saldoinicial"], 2, '.', ',')."	</div></td>
						 <td style='border-bottom: 1px solid #EEEEEE; '><div id='saldofinal_".$i."' style='text-align: right;'>	$".number_format($row["saldofinal"], 2, '.', ',')."		</div></td>
						 <input type='hidden' id='idCxcpago_".$i."' value=".$row["idCxcpagos"].">
					 </tr>";
				}
			}
			$tabla_pagos .= "<input id='cont_pagos' value=".$i." type='hidden'>";
			$tabla_pagos .= "</table>";	
			
			echo $tabla_pagos;		
		}
		else
			echo "<center><div style='color: #006efe;'>No se han registrado pagos para esta cuenta</div></center>";
	}
	
	function agregaPago($connection)
	{
		$fecha_abono = $_POST["fecha_abono"];
		$abono = $_POST["abono"];
		$forma_pago = $_POST["forma_pago"];
		$id_forma_pago = $_POST["id_forma_pago"];
		$id = $_POST["id"];
		$saldo_actual = $_POST["saldo_actual"];
		$fven = $_POST["fven"];
		$referencia = $_POST["referencia"];
		if (!isset($_SESSION))
			session_start();
		
		if (!isset($_SESSION["fecha_abono_array"]))
		    $_SESSION["fecha_abono_array"] = array();
		if (!isset($_SESSION["abono_array"]))
		    $_SESSION["abono_array"] = array();
		if (!isset($_SESSION["forma_pago_array"]))
		    $_SESSION["forma_pago_array"] = array();
		if (!isset($_SESSION["id_forma_pago_array"]))
		    $_SESSION["id_forma_pago_array"] = array();
		if (!isset($_SESSION["referencia_array"]))
		    $_SESSION["referencia_array"] = array();
		
		array_push($_SESSION["fecha_abono_array"], $fecha_abono);
		array_push($_SESSION["abono_array"], $abono);
		array_push($_SESSION["forma_pago_array"], $forma_pago);
		array_push($_SESSION["id_forma_pago_array"], $id_forma_pago);
		array_push($_SESSION["referencia_array"], $referencia);
		
		$grid_pagos = "<form action='../cxc/cuenta.php' type='POST'>
							<input type='hidden' name='id' value=".$id.">
							<input type='hidden' name='fven' value=".$fven.">
							<h3 style='color: #006efe;'>Pagos a agregar</h3>
							<table cellpadding='0' cellspacing='0' width=85%>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe;'>Borrar</th>
								<th width=20% style='border-bottom: 1px solid #006efe;'>Fecha</th>
								<th width=40% style='border-bottom: 1px solid #006efe;'>Forma de pago</th>
								<th width=30% style='border-bottom: 1px solid #006efe;'>Abono</th>
							</tr>";
		
		
		for($i=0; $i<count($_SESSION["abono_array"]); $i++)
		{
			$grid_pagos .= "<tr>";
			$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><center><input type='checkbox' name='chk".$i."' value='1'></center></td>";
			$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["fecha_abono_array"][$i]."</td>";
           
		   	if($_SESSION["id_forma_pago_array"][$i] == 2 || $_SESSION["id_forma_pago_array"][$i] == 3)
			{
				$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["forma_pago_array"][$i]." (no. ".$_SESSION['referencia_array'][$i].")"."</td>";
			}
			else
		    {
		    	$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["forma_pago_array"][$i]."</td>";
            }
            
            $grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; '>".number_format($_SESSION["abono_array"][$i], 2, '.', ',')."</td>";
          	$grid_pagos .= "</tr>";
		}
			$nuevo_saldo_actual = $saldo_actual - array_sum($_SESSION["abono_array"]);
			
			$grid_pagos .= "<tr><td colspan='2' style='text-align: left; border-top: 1px solid #777777;'><input type='submit' id='borrar' value='Borrar seleccionados'/></td>";
			$grid_pagos .= "<td colspan='2' style='text-align: right; border-top: 1px solid #777777;'>Saldo en caso de agregarse los pagos:  <label style='color: #006efe;'>$ ".number_format($nuevo_saldo_actual, 2, '.', ',')."</label></td></tr>";
			$grid_pagos .= "<input type='hidden' id='saldo_final_preliminar' value='".$nuevo_saldo_actual."'>";
			$grid_pagos .= "<tr><td colspan='4' style='text-align: right;'><input type='button' id='registrar_pagos' value='Registrar pagos' onclick='registraPagos();'/></td></tr>";
			
		$grid_pagos .= "</table></form>";
		echo $grid_pagos;
		
	}

	function cargaPrePagos($connection)
	{	
		if (!isset($_SESSION))
			session_start();

		$id = $_POST["id"];
		$saldo_actual = $_POST["saldo_actual"];
		$fven = $_POST["fven"];
		
		if(isset($_SESSION["fecha_abono_array"]) && isset($_SESSION["abono_array"])
			&& isset ($_SESSION["forma_pago_array"]) && isset($_SESSION["id_forma_pago_array"]) && isset($_SESSION["referencia_array"])
			&& count($_SESSION["fecha_abono_array"]) > 0)
		{
			$grid_pagos = "<form action='../cxc/cuenta.php' type='POST'>
							<input type='hidden' name='id' value=".$id.">
							<input type='hidden' name='fven' value=".$fven.">
							<h3 style='color: #006efe;'>Pagos a agregar</h3>
							<table cellpadding='0' cellspacing='0' width=85%>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe;'>Borrar</th>
								<th width=20% style='border-bottom: 1px solid #006efe;'>Fecha</th>
								<th width=40% style='border-bottom: 1px solid #006efe;'>Forma de pago</th>
								<th width=30% style='border-bottom: 1px solid #006efe;'>Abono</th>
							</tr>";
							
							
			for($i=0; $i<count($_SESSION["abono_array"]); $i++)
			{
				$grid_pagos .= "<tr>";
				$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><center><input type='checkbox' name='chk".$i."' value='1'></center></td>";
				$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["fecha_abono_array"][$i]."</td>";
	           	
	           	if($_SESSION["id_forma_pago_array"][$i] == 2  || $_SESSION["id_forma_pago_array"][$i] == 3)
				{
					$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["forma_pago_array"][$i]." (no. ".$_SESSION['referencia_array'][$i].")"."</td>";
				}
				else
			    {
			    	$grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'>".$_SESSION["forma_pago_array"][$i]."</td>";
	            }
			    
			    $grid_pagos .= "<td style='border-bottom: 1px solid #EEEEEE; '>".number_format($_SESSION["abono_array"][$i], 2, '.', ',')."</td>";
	          	$grid_pagos .= "</tr>";
			}
				$nuevo_saldo_actual = $saldo_actual - array_sum($_SESSION["abono_array"]);
				
				$grid_pagos .= "<tr><td colspan='2' style='text-align: left; border-top: 1px solid #777777;'><input type='submit' id='borrar' value='Borrar seleccionados'/></td>";
				$grid_pagos .= "<td colspan='2' style='text-align: right; border-top: 1px solid #777777;'>Saldo en caso de agregarse los pagos:  <label style='color: #006efe;'>$ ".number_format($nuevo_saldo_actual, 2, '.', ',')."</label></td></tr>";
				$grid_pagos .= "<input type='hidden' id='saldo_final_preliminar' value='".$nuevo_saldo_actual."'>";
				$grid_pagos .= "<tr><td colspan='4' style='text-align: right;'><input type='button' id='registrar_pagos' value='Registrar pagos' onclick='registraPagos();'/></td></tr>";
				
			$grid_pagos .= "</table></form>";
			echo $grid_pagos;
		}
	}

	function crearCuenta($connection)
	{
		$fecha_cargo = $_POST["fecha_cargo"];
		$fecha_vencimiento = $_POST["fecha_vencimiento"];
		$monto = $_POST["monto"];			
		$saldo_abonado = $_POST["saldo_abonado"];	
		$saldo_actual = $_POST["saldo_actual"]; 	
		$concepto = $_POST["concepto"];
		
		if ($result = $connection->query("INSERT INTO cxc (fechacargo, fechavencimiento, concepto, monto, saldoabonado, saldoactual) VALUES ('".$fecha_cargo."', '".$fecha_vencimiento."', '".$concepto."', ".$monto.", ".$saldo_abonado.", ".$saldo_actual.")"))
		{
			echo 1;
		}
		else 
		{
			echo 0;
		}	
	}
	
	function registraPagos($connection)
	{
		$sendingArray = array();
		@session_start();
		$id = $_POST["id"];
		$saldo_actual = $_POST["saldo_actual"];
		$saldo_abonado = $_POST["saldo_abonado"];
		
		$saldo_final;
		$saldo_inicial;
		$acumulado_abonos = 0;
		
		date_default_timezone_set('America/Mexico_City'); 
		$fecha = date("Y-m-d");
		
		for($i=0; $i<count($_SESSION["abono_array"]); $i++)
		{
			array_push( $sendingArray, array( $_SESSION['fecha_abono_array'][$i], $_SESSION['abono_array'][$i], $_SESSION['forma_pago_array'][$i], $_SESSION['id_forma_pago_array'][$i], $_SESSION['referencia_array'][$i], $id ) );
			$acumulado_abonos += $_SESSION['abono_array'][$i];
			if($i == 0)
			{
				$saldo_final = $saldo_actual - $_SESSION['abono_array'][$i];
				$result = $connection->query("INSERT INTO cxc_pagos (fecha, monto, saldoinicial, saldofinal, idCxc, idFormapago, referencia) VALUES ('".$fecha."', ".$_SESSION['abono_array'][$i].", ".$saldo_actual.", ".$saldo_final.", ".$id.", ".$_SESSION['id_forma_pago_array'][$i].", '".$_SESSION['referencia_array'][$i]."')");
			}
			else
			{
				$saldo_actual = $saldo_final;
				$saldo_final = $saldo_actual - $_SESSION['abono_array'][$i];
				$result = $connection->query("INSERT INTO cxc_pagos (fecha, monto, saldoinicial, saldofinal, idCxc, idFormapago, referencia) VALUES ('".$fecha."', ".$_SESSION['abono_array'][$i].", ".$saldo_actual.", ".$saldo_final.", ".$id.", ".$_SESSION['id_forma_pago_array'][$i].", '".$_SESSION['referencia_array'][$i]."')");
			}
			
			if($_SESSION["id_forma_pago_array"][$i] == 3)
			{
				$result = $connection->query("UPDATE tarjeta_regalo SET usada = 1 WHERE numero = ".$_SESSION["referencia_array"][$i]);
			}
		}
		$saldo_abonado += $acumulado_abonos;
		$result = $connection->query("UPDATE cxc SET saldoabonado = ".$saldo_abonado.", saldoactual = ".$saldo_final." WHERE idCxc = ".$id);
		echo json_encode($sendingArray);
	}
	
	function compruebaTarjetaRegalo($connection)
	{
		$ref = $_POST["referencia"];
		$valida;
		$result = $connection->query("SELECT id, numero, valor, usada FROM tarjeta_regalo WHERE numero = '".$ref."'");
		
		if($result->num_rows > 0)
		{
			while($rows = $result->fetch_array())
			{
				$row = $rows;
				if($row["usada"] == 1)
				{
					$valida = "Usada";
				}
				else
				{
					if($row["valor"] <= 0)
					{
						$valida = "Agotada";
					}
					else 
					{
						$valida = $row["valor"];
					}
				}	
			}
		}
		else
		{
			$valida = "No existe";
		}
		echo $valida;
	}
	
	function compruebaChequeOTarjetaDuplicados()
	{
		$ref = $_POST["referencia"];
		$id_forma_pago = $_POST["id_forma_pago"];
		$valido = true;
		if (!isset($_SESSION))
			session_start();
		
		for($i=0; $i<count($_SESSION["referencia_array"]); $i++)
		{
			if($ref == $_SESSION["referencia_array"][$i] && $id_forma_pago == $_SESSION["id_forma_pago_array"][$i])
			{
				$valido = false;
			}	
		}
		
		if($valido == false)
		{
			if($id_forma_pago == 2)
			{
				echo 2;
			}
			else if($id_forma_pago == 3)
			{
				echo 3;
			}
		}
		else
		{
			echo 1;
		}
	}
?>