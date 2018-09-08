<?php
	include("../../../netwarelog/webconfig.php");
	//ini_set("display_errors",1);


	$funcion = $_POST['funcion'];
	
	if($funcion == "guardarEnviar")
	{
		$funcion($servidor,$usuariobd,$clavebd);
	}
	else 
	{
		if($funcion == "refrescaSelects")
		{
			$funcion();
		}
		else 
		{
			if($funcion == "consultaClientes" || $funcion == "buscaClientes" || $funcion == "buscaGiros" || $funcion == "buscaRubros" || $funcion == "buscaEstados" || $funcion == "buscaMunicipios")
			{
				$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
			}
			else
			{
				$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
			}
			$funcion($connection);
			mysqli_close($connection);
		}
	}
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------

	function buscaProductos($connection)
	{
		$select_producto = "<div><select id='producto' style='width: 100%; min-width: 100%;' >";
		$select_producto .= "<option value=''>Selecciona el producto de la oferta</option>";
		
		//if ($result = $connection->query("SELECT idProducto, nombre FROM mrp_producto WHERE vendible=1 order by nombre "))
		if ($result = $connection->query("SELECT idProducto, nombre FROM mrp_producto  order by nombre "))
		
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_producto .= '<option value="'.$row["idProducto"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
			echo $select_producto;
		}
		$select_producto .= "</select></div>";	
	}

//---------------------------------------------------------------------------------

	function buscaSucursales($connection)
	{	
		$select_producto = "<div><select id='sucursal' style='width: 100%; min-width: 100%;' >";
		$select_producto .= "<option value='0'>Todas las sucursales</option>";
		if ($result = $connection->query("SELECT idSuc, nombre FROM mrp_sucursal"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_producto .= '<option value="'.$row["idSuc"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
			$select_producto .= "</select></div>";
			echo $select_producto;
		}
	}

	function buscaSucursal($connection)
	{
		$select_producto = "<div><select id='sucursal_recibe' >";
		$select_producto .= "<option value='' selected>Sucursal que recibirá el pedido</option>";
		if ($result = $connection->query("SELECT idSuc, nombre FROM mrp_sucursal"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_producto .= '<option value="'.$row["idSuc"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
			$select_producto .= "</select></div>";
			echo $select_producto;
		}
	}

//---------------------------------------------------------------------------------

	function buscaUnidades($connection)
	{
		$select_unidad = "<div><select id='unidad' style='width: 100%; min-width: 100%;' >";
		$select_unidad .= "<option value=''>Selecciona la unidad</option>";
		
		if ($result = $connection->query("SELECT idUni, compuesto FROM mrp_unidades"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_unidad .= '<option value="'.$row["idUni"].'">'.utf8_decode($row["compuesto"]).'</option>';
				
			}
		}
		$select_unidad .= "</select></div>";
		echo $select_unidad;
	}

//---------------------------------------------------------------------------------

	function buscaRubros($connection2)
	{
		$select_unidad = "<div><select id='filtro_rubro' style='width: 100%; min-width: 100%;' >";
		$select_unidad .= "<option value=''>Sin filtro</option>";
		
		if ($result = $connection2->query("SELECT idRubro, nombre FROM sms_rubro"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_unidad .= '<option value="'.$row["idRubro"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
		}
		$select_unidad .= "</select></div>";
		echo $select_unidad;
	}

//---------------------------------------------------------------------------------

	function buscaGiros($connection2)
	{
		$select_unidad = "<div><select id='filtro_giro' style='width: 100%; min-width: 100%;' >";
		$select_unidad .= "<option value=''>Sin filtro</option>";
		
		if ($result = $connection2->query("SELECT idGiro, nombre FROM sms_giro"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_unidad .= '<option value="'.$row["idGiro"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
		}
		$select_unidad .= "</select></div>";
		echo $select_unidad;
	}

//---------------------------------------------------------------------------------

	function buscaEstados($connection2)
	{
		$select_unidad = "<div><select id='filtro_estado' style='width: 100%; min-width: 100%;' onchange='filtraMunicipio(this.value);' >";
		$select_unidad .= "<option value=''>Sin filtro</option>";
		
		if ($result = $connection2->query("SELECT idestado, estado FROM estados"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_unidad .= '<option value="'.$row["idestado"].'">'.utf8_decode($row["estado"]).'</option>';
				
			}
		}
		$select_unidad .= "</select></div>";
		echo $select_unidad;
	}

//---------------------------------------------------------------------------------

	function buscaMunicipios($connection2)
	{
		$idEst = $_POST['idEst'];
		
		$select_unidad = "<div><select id='filtro_municipio' style='width: 100%; min-width: 100%;' >";
		$select_unidad .= "<option value=''>Sin filtro</option>";
		
		if ($result = $connection2->query("SELECT idmunicipio, municipio FROM municipios WHERE idestado = ".$idEst." ORDER BY municipio"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_unidad .= '<option value="'.$row["idmunicipio"].'">'.utf8_decode($row["municipio"]).'</option>';
				
			}
		}
		$select_unidad .= "</select></div>";
		echo $select_unidad;
	}

//---------------------------------------------------------------------------------

	function buscaClientes($connection)
	{
		/*$filtro = $_POST['filtro'];
		$filtroGiro = $_POST['filtroGiro'];
		$filtroRubro = $_POST['filtroRubro'];
		$filtroEstado = $_POST['filtroEstado'];
		$filtroMunicipio = $_POST['filtroMunicipio'];
		*/
		
		$condicionAntes = false;
		/*
		if ($filtro != "")
		{
			$filtro = "WHERE (c.nombretienda LIKE '%".$filtro."%' OR c.cp LIKE '%".$filtro."%') ";
			$condicionAntes = true;
		}
		if($filtroGiro != "")
		{
			if($condicionAntes == false)
			{
				$filtroGiro = "WHERE g.idGiro = ".$filtroGiro." ";
				$condicionAntes = true;
			}
			else
			{
				$filtroGiro = " AND g.idGiro = ".$filtroGiro." ";
			}
		}
		else
		{
			$filtroGiro = "";
		}	
		if($filtroRubro != "")
		{
			if($condicionAntes == false)
			{
				$filtroRubro = "WHERE r.idRubro = ".$filtroRubro." ";
				$condicionAntes = true;
			}
			else
			{
				$filtroRubro = " AND r.idRubro = ".$filtroRubro." ";
			}
		}
		else
		{
			$filtroRubro = "";
		}
		if($filtroEstado != "")
		{
			if($condicionAntes == false)
			{
				$filtroEstado = "WHERE e.idEstado = ".$filtroEstado." ";
				$condicionAntes = true;
			}
			else 
			{
				$filtroEstado = " AND e.idEstado = ".$filtroEstado." ";
			}
		}
		else
		{
			$filtroEstado = "";
		}
		if($filtroMunicipio != "")
		{
			if($condicionAntes == false)
			{
				$filtroMunicipio = "WHERE m.idMunicipio = ".$filtroMunicipio." ";
				$condicionAntes = true;
			}
			else 
			{
				$filtroMunicipio = " AND m.idMunicipio = ".$filtroMunicipio." ";
			}
		}
		else
		{
			$filtroMunicipio = "";
		}	
		*/
		$a2 = $_POST['grupo'];
		if($a2==0){
			$ij=' INNER JOIN sms_cliente_grupo b ON b.id_cliente=a.id ';
			$qc=' ';
		}else{
            $ij=' INNER JOIN sms_cliente_grupo b ON b.id_cliente=a.id ';
			$qc=' AND b.id_grupo='.$a2.' ';
		}
		
		$filtro = $filtro.$filtroGiro.$filtroRubro.$filtroEstado.$filtroMunicipio;
		//echo $filtro;
		$condicionAntes = false;
		
		/*echo "SELECT c.id, c.nombretienda, c.direccion, c.colonia, c.cp, e.estado, m.municipio, g.idGiro, g.nombre Giro, r.idRubro, r.nombre Rubro 
										FROM comun_cliente c
										INNER JOIN sms_giro g ON c.idGiro = g.idGiro
										INNER JOIN sms_rubro r ON c.idRubro = r.idRubro
										INNER JOIN municipios m ON m.idmunicipio = c.idMunicipio
										INNER JOIN estados e ON e.idestado = c.idEstado ".$filtro;*/
		
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		/*$qry="  SELECT c.id, c.nombretienda, c.direccion, c.colonia, c.cp, e.estado, m.municipio, g.idGiro, g.nombre Giro, r.idRubro, r.nombre Rubro 
												FROM nmdev_common.comun_cliente c 
												INNER JOIN nmdev_common.sms_giro g ON c.idGiro = g.idGiro 
												INNER JOIN nmdev_common.sms_rubro r ON c.idRubro = r.idRubro 
												INNER JOIN nmdev_common.municipios m ON m.idmunicipio = c.idMunicipio 
												INNER JOIN nmdev_common.estados e ON e.idestado = c.idEstado ".$filtro;*/
		$qry="SELECT a.id, a.celular, a.nombretienda, a.direccion, a.colonia, a.cp, e.estado, m.municipio, g.idGiro, g.nombre Giro, r.idRubro, r.nombre Rubro FROM comun_cliente a
		LEFT JOIN sms_giro g ON a.idGiro = g.idGiro 
		LEFT JOIN sms_rubro r ON a.idRubro = r.idRubro
		LEFT JOIN municipios m ON m.idmunicipio = a.idMunicipio
		LEFT JOIN estados e ON e.idestado = a.idEstado ".$ij." WHERE 1 ".$qc." ";
		//echo $qry;

		$select_cliente = " <input type='hidden' id='org' value='".$_SESSION["accelog_nombre_organizacion"]."'>
							<div style='text-align: left; width:90%; min-width: 90%;'><input type='checkbox' id='select_all' value='1' onclick='selectAll();' > Seleccionar todos</div>
							<div id='cliente' style='height:300px; width:90%; min-width: 90%; border:1px solid #006efe; overflow:auto; padding: 10px;' >";
	
		if ($result = $connection->query($qry))
		{

			if($result->num_rows > 0)
			{
				$select_cliente .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 14px;'>
								<tr>
									<th width=5% style='border-bottom: 1px solid #006efe;'></th>
									<th width=20% style='border-bottom: 1px solid #006efe;'>Nombre</th>
									<th width=40% style='border-bottom: 1px solid #006efe;'>Direccion</th>
									<th width=20% style='border-bottom: 1px solid #006efe;'>Rubro</th>
									<th width=15% style='border-bottom: 1px solid #006efe;'>Giro</th>
									<th width=15% style='border-bottom: 1px solid #006efe;'>Celular</th>
								</tr>";
				$i = 0;
				$nexti;
				while($row = $result->fetch_array())
					$rows[] = $row;
				//var_dump($_SESSION['check_array']);
				foreach($rows as $row)
				{
					if( preg_match('/^[0-9]{10}$/', $row["celular"]) ){
						$cel=$row["celular"];
					}else{
						$cel='<font color="#ff0000">Invalido!</font>';
					}

					for($j = 0; $j<$_SESSION['contador_clientes']; $j++)
					{
						if($_SESSION['id_array'][$j] == $row["id"])
						{
							$nexti = $j;
						}
					}
						if($_SESSION['check_array'][$nexti] == 1 || $_SESSION['check_array'][$nexti] == "1")
						{
							$select_cliente .= "<tr>
										 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><center><input type='checkbox' id='chk".$i."' value='".$row["id"]."' onclick='refrescaSelects(".$row["id"].", ".$i.");' checked='checked'></center></td>
										  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='nom_".$i."'>".utf8_decode($row["nombretienda"])."</div></td>
										  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='dir_".$i."'>".utf8_encode($row["direccion"])." Col. ".utf8_decode($row["colonia"])." C.P. ".$row["cp"]." (".utf8_decode($row["municipio"]).", ".utf8_decode($row["estado"]).") </div></td>
		            					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='rub_".$i."'>".utf8_decode($row["Rubro"])."</div></td>
		            					  	 <input type='hidden' id='estMun_".$i."' value='".$row['municipio'].", ".$row['estado']."'>
		            					  	 <td style='border-bottom: 1px solid #EEEEEE; '><div id='gir_".$i."'>".utf8_decode($row["Giro"])."</div></td>
		            					  	 <td style='border-bottom: 1px solid #EEEEEE; '><div id='cel_".$i."'>".$cel."</div></td>
		            					  	 <input type='hidden' id='id_".$i."' value=".$row["id"].">
		            					  	 </tr>";
						}
						else
						{
							$select_cliente .= "<tr>
										 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><center><input type='checkbox' id='chk".$i."' value='".$row["id"]."' onclick='refrescaSelects(".$row["id"].", ".$i.");' ></center></td>
										 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='nom_".$i."'>".utf8_decode($row["nombretienda"])."</div></td>
										  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='dir_".$i."'>".utf8_encode($row["direccion"])." Col. ".utf8_decode($row["colonia"])." C.P. ".$row["cp"]." (".utf8_decode($row["municipio"]).", ".utf8_decode($row["estado"]).") </div></td>
		            					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='rub_".$i."'>".utf8_decode($row["Rubro"])."</div></td>
		            					  	 <input type='hidden' id='estMun_".$i."' value='".$row['municipio'].", ".$row['estado']."'>
		            					  	 <td style='border-bottom: 1px solid #EEEEEE; '><div id='gir_".$i."'>".utf8_decode($row["Giro"])."</div></td>
		            					  	 <td style='border-bottom: 1px solid #EEEEEE; '><div id='cel_".$i."'>".$cel."</div></td>
		            					  	 <input type='hidden' id='id_".$i."' value=".$row["id"].">
											 </tr>";
						}
	            	$i++;
				}
				$select_cliente .= "<input id='cont_clientes' value=".$i." type='hidden'>";
				if($filtro != "")
				{
					$select_cliente .= "<tr><td colspan=5><center><br><br><input type='button' id='recargar_clientes' onclick='buscaClientesAgain();' value='Mostrar todos los clientes'></center></td></tr>";
				}
				$select_cliente .= "</table>";
			}
			else
			{
				$select_cliente.= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%'>
									<tr><td>
										<br><center>No encontramos ningún cliente que coincidiera con su búsqueda.<br>¿Por qué no intentamos con algo diferente?<br><br>
										<input type='button' id='recargar_clientes' onclick='buscaClientes();' value='Mostrar todos los clientes'></center>
									</td></tr>
									</table>";
			}
		}
		$select_cliente .= "</div>";
		echo $select_cliente;
	}

//---------------------------------------------------------------------------------

	function consultaClientes($connection)
	{
		if ($result = $connection->query("  SELECT c.id, c.nombretienda, c.direccion, c.colonia, c.cp, e.estado, m.municipio, g.idGiro, g.nombre Giro, r.idRubro, r.nombre Rubro 
											FROM comun_cliente c
											INNER JOIN sms_giro g ON c.idGiro = g.idGiro
											INNER JOIN sms_rubro r ON c.idRubro = r.idRubro
											INNER JOIN municipios m ON m.idmunicipio = c.idMunicipio
											INNER JOIN estados e ON e.idestado = c.idEstado "))
		{
			if($result->num_rows > 0)
			{
				if(!isset($_SESSION)) 
				{
					session_start();
				}
				if (!isset($_SESSION["id_array"])) 
				{
					$_SESSION["contador_clientes"] = 0;
				    $_SESSION["id_array"] = array();
				    $_SESSION["nombre_array"] = array();
				    $_SESSION["direccion_array"] = array();
				    $_SESSION["giro_array"] = array();
				    $_SESSION["rubro_array"] = array();
				    $_SESSION["check_array"] = array();
				    
				    while($row = $result->fetch_array())
					$rows[] = $row;
					foreach($rows as $row)
					{
						array_push($_SESSION["id_array"], $row["id"]);
						array_push($_SESSION["nombre_array"], $row["nombretienda"]);
						array_push($_SESSION["direccion_array"], $row["telefono"]);
						array_push($_SESSION["giro_array"], $row["Giro"]);
						array_push($_SESSION["rubro_array"], $row["Rubro"]);
						array_push($_SESSION["check_array"], 1);
						$_SESSION["contador_clientes"] ++;
					}
				}
			}
		}
		var_dump ($_SESSION['check_array']);
	}

//---------------------------------------------------------------------------------

	function refrescaSelects()
	{
		$id = $_POST['id'];
		$checked = $_POST['checked'];
		if(!isset($_SESSION)) 
		{
			session_start();
		}
			
		for($i=0; $i<$_SESSION["contador_clientes"]; $i++)
		{
			if ($_SESSION["id_array"][$i] == $id)
			{
				$_SESSION["check_array"][$i] = $checked;
			}
		}
	}

//---------------------------------------------------------------------------------

	function guardarEnviar($servidor,$usuariobd,$clavebd)
	{

		include("../../../netwarelog/webconfig.php");
		if(!isset($_SESSION)) 
		{
			session_start();
		}

		$checked = $_POST['checked'];
		$ides = trim($_POST['ides'],',');
		$cantidad = $_POST['cantidad'];
		$unidad = $_POST['unidad'];
		$producto = $_POST['producto'];
		$precio = $_POST['precio'];
		$f_inicio = $_POST['f_inicio'];
		$f_fin = $_POST['f_fin'];
		$elaborador = $_POST['elaborador'];
		$sms = $_POST['sms'];
		$sucursal = $_POST['sucursal'];
		$cantidad_existente = $_POST['cantidad_existente'];
		$comprueba_cliente_oferta = true;
		$cadena = "";
		
		date_default_timezone_set('America/Mexico_City'); 
		$f_creacion = date("Y-m-d H:i:s");
		

		$connection = new mysqli($servidor,$usuariobd,$clavebd, $bd);
		
		if ($result = $connection->query("INSERT INTO sms_oferta(idProducto, idUnidad, precio, cantidad, fechaInicio, fechaFin, fechaCreacion, usuarioGenera, mensajeSMS, idSuc, cantidadExistente) VALUES (".$producto.", ".$unidad.", ".$precio.", ".$cantidad.", '".$f_inicio."', '".$f_fin."', '".$f_creacion."', '".$elaborador."', '".$sms."', ".$sucursal.", ".$cantidad_existente." );"))
		{
			$id = $connection->insert_id;

					$clis=explode(',',$ides);
					include ( "../nexmo/NexmoMessage.php" );

					foreach ($clis as $key => $value) {
						$connection->query("INSERT INTO sms_oferta_cliente(idOferta, idCliente, estatus) VALUES (".$id.", ".$value.", 0);");
						$idoc = $connection->insert_id;
						if(!$connection)
							echo "connection failed";
						else{
							$ids=$ides;
							$result = $connection->query("select b.rfc from comun_cliente a inner join comun_facturacion b on b.nombre=a.id where a.id='$value' LIMIT 1 ");
							if($result->num_rows > 0){
								echo 'b';
								$rows = $result->fetch_array();
								
								
								$rfccli=$rows['rfc'];
								$nsconnection = new mysqli($servidor,$usuariobd,$clavebd,"netwarstore");
								// Check connection
								if(!$nsconnection)
									echo "connection failed";
								else{
									$result = $nsconnection->query("select nombre_db, usuario_db, pwd_db from customer where rfc='$rfccli' ");
									if($result->num_rows > 0){
										echo 'd';
										while($row = $result->fetch_array()){
											$cconnection = new mysqli("nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com", $row['usuario_db'], $row['pwd_db'], $row['nombre_db']);
											if(!$cconnection)
												echo "connection failed";
											else{

												$cadena="ido=".$id."&idoc=".$idoc."&rfc=".$rfccli."&idcli=".$value."&bd=".$row['nombre_db']."&bd2=".$bd;
												$cadenc=base64_encode($cadena);
												$otrophp=preg_replace('/offer./', 'offer2.', $_SERVER[REQUEST_URI]);

												$url="http://$_SERVER[HTTP_HOST]".$otrophp.'?id='.$cadenc;
												$cconnection->query("insert into sms_oferta_client (id, descripcion, url, inicio_ofe, fin_ofe, estatus, fecha, id_oferta, id_oferta_cliente) values (null,'$sms','$url','$f_inicio','$f_fin',0,'$f_creacion','$id','$idoc')");
											}
											mysqli_close($cconnection);
										}
									}
								}
								mysqli_close($nsconnection);

								
							}
							echo 555;
							$result = $connection->query("select celular,smsb from comun_cliente where id='$value'");
							if($result->num_rows > 0){
								$rows2 = $result->fetch_array();
								if($rows2["smsb"]==0){
									if( preg_match('/^[0-9]{10}$/', $rows2["celular"]) ){
										$celularbase='525549998487';
										$rows2['celular']='8119927647';
										$nexmo_sms = new NexmoMessage('1a62e892', 'e26f0771');
										$info = $nexmo_sms->sendText( '+52'.$rows2['celular'], 'Netwar', 'Responde XXX al '.$celularbase );
											echo $nexmo_sms->displayOverview($info);
									}
								}

							}
						}
					}
			mysqli_close($connection);
			
		}else{
			$comprueba_cliente_oferta = false;
			echo "Algo salió mal al momento de ingresar a la base de datos. Ponte en contacto con el proveedor de este servicio";
		}
		
		unset($_SESSION["contador_clientes"]);
		unset($_SESSION["id_array"]);
		unset($_SESSION["nombre_array"]);
		unset($_SESSION["direccion_array"]);
		unset($_SESSION["giro_array"]);
		unset($_SESSION["rubro_array"]);
		unset($_SESSION["check_array"]);
		
		echo "Se creó la oferta correctamente";
	}

	function cargaDatosConsulta($connection)
	{
		//var_dump($connection);
		$id = $_POST['id'];
		if ($result = $connection->query("  SELECT p.nombre, p.idProducto, u.compuesto, o.precio, o.cantidad, o.fechaInicio, o.fechaFin, o.fechaCreacion, o.usuarioGenera, o.mensajeSMS, o.idSuc, o.idUnidad 
										FROM sms_oferta o
										INNER JOIN mrp_producto p ON p.idProducto = o.idProducto
										INNER JOIN mrp_unidades u ON u.idUni =  o.idUnidad
										WHERE o.idOferta = ".$id))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					if($row['idSuc'] == 0)
					{
						echo $row['nombre']."$$$^^^###///".$row['compuesto']."$$$^^^###///".$row['precio']."$$$^^^###///".$row['cantidad']."$$$^^^###///".$row['fechaInicio']."$$$^^^###///".$row['fechaFin']."$$$^^^###///".$row['fechaCreacion']."$$$^^^###///".$row['usuarioGenera']."$$$^^^###///".$row['mensajeSMS']."$$$^^^###///".$row['idProducto']."$$$^^^###///0$$$^^^###///Todas las sucursales$$$^^^###///".$row['idUnidad'];
					}
					else 
					{
						$query_suc = $connection->query(" SELECT nombre Nom FROM mrp_sucursal WHERE idSuc = ".$row['idSuc']);
						if($query_suc->num_rows > 0)
						{
							$row2 = $query_suc->fetch_array();
						}
						echo $row['nombre']."$$$^^^###///".$row['compuesto']."$$$^^^###///".$row['precio']."$$$^^^###///".$row['cantidad']."$$$^^^###///".$row['fechaInicio']."$$$^^^###///".$row['fechaFin']."$$$^^^###///".$row['fechaCreacion']."$$$^^^###///".$row['usuarioGenera']."$$$^^^###///".$row['mensajeSMS']."$$$^^^###///".$row['idProducto']."$$$^^^###///0$$$^^^###///".$row2['Nom']."$$$^^^###///".$row['idUnidad'];
					}
			}
		}
	}

	function clientesRecibieronSMS($connection)
	{
		$clienteAComprobar = false;
		$id = $_POST['id'];
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		
		$select_cliente = " <div id='cliente' style='height:300px; width: 95%; border:1px solid #006efe; overflow:auto; padding: 10px;' >";
	
		if ($result = $connection->query("  SELECT  oc.idCliente, oc.idOferta, oc.contesto, oc.cantidad, oc.estatus, oc.idOrdenCompra, oc.fechaRespuesta,
												c.nombretienda, c.direccion, c.cp, e.estado, m.municipio, g.idGiro, g.nombre Giro, r.idRubro, r.nombre Rubro
										FROM sms_oferta_cliente oc
												INNER JOIN comun_cliente c ON oc.idCliente = c.id
												INNER JOIN sms_giro g ON c.idGiro = g.idGiro
												INNER JOIN sms_rubro r ON c.idRubro = r.idRubro
												INNER JOIN municipios m ON m.idmunicipio = c.idMunicipio
												INNER JOIN estados e ON e.idestado = c.idEstado
										WHERE idOferta = ".$id." ORDER BY oc.fechaRespuesta asc"))
		{
			if($result->num_rows > 0)
			{
				$select_cliente .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
								<tr>
									<th width=55% style='border-bottom: 1px solid #006efe;'>Cliente</th>
									<th width=10% style='border-bottom: 1px solid #006efe;'>Respuesta</th>
									<th width=15% style='border-bottom: 1px solid #006efe;'>Fecha respuesta</th>
									<th width=10% style='border-bottom: 1px solid #006efe;'>Cantidad pedida</th>
									<th width=10% style='border-bottom: 1px solid #006efe;'>Estatus*</th>
								</tr>";
				$i = 0;
				while($row = $result->fetch_array())
					$rows[] = $row;
				//var_dump($_SESSION['check_array']);
				foreach($rows as $row)
				{
					if($row[contesto] == 0)
					{
						$select_cliente .= "<tr>
								 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='cliente_".$i."'> <b>".utf8_decode($row["nombretienda"])."</b> (".utf8_decode($row["municipio"]).", ".utf8_decode($row["estado"]).")</div></td>
								  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='contesto_".$i."'><center>---</center></div></td>
								  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='fechaRes_".$i."'><center>---</center></div></td>
								  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='fake_cantidad_".$i."'><center>---</center></div></td>
								  	 <input type='hidden' id='cantidad_".$i."' value=0>
								  	 <td style='border-bottom: 1px solid #EEEEEE; '><div id='fake_estatus_".$i."'><center>---</center></div></td>
								  	 <input type='hidden' id='idCliente_".$i."' value=".$row["idCliente"].">
									 </tr>";
					}
					if($row[contesto] == 1)
					{
						$select_cliente .= "<tr>
								 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='cliente_".$i."'> <b>".utf8_decode($row["nombretienda"])."</b> (".utf8_decode($row["municipio"]).", ".utf8_decode($row["estado"]).")</div></td>
								  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='contesto_".$i."'><center>Si</center></div></td>
								  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='contesto_".$i."'><center>".$row['fechaRespuesta']."</center></div></td>
								  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><div id='fake_cantidad_".$i."'><center>".$row["cantidad"]."</center></div></td>
								  	 <input type='hidden' id='cantidad_".$i."' value=".$row["cantidad"].">";
						
						if($row[idOrdenCompra]==NULL)
						{
							$select_cliente.= "<td style='border-bottom: 1px solid #EEEEEE; color: #FF000C;'><center><div id='fake_estatus_".$i."'>-1</div></center></td>
										<input type='hidden' id='estatus_".$i."' value='-1'>
								  	 	<input type='hidden' id='idCliente_".$i."' value=".$row["idCliente"].">
									 	</tr>";		 	
							$clienteAComprobar = true;
						}
						else
						{
							if($row[estatus]==0)
							{
								$select_cliente.= "<td style='border-bottom: 1px solid #EEEEEE; color: #000CFF;'><center><div id='fake_estatus_".$i."'>0</div></center></td>
											<input type='hidden' id='estatus_".$i."' value='0'>
									  	 	<input type='hidden' id='idCliente_".$i."' value=".$row["idCliente"].">
										 	</tr>";
							}
							else
							{
								$select_cliente.= "<td style='border-bottom: 1px solid #EEEEEE; color: #01a05f;'><center><div id='fake_estatus_".$i."'>1</div></center></td>
											<input type='hidden' id='estatus_".$i."' value='1'>
									  	 	<input type='hidden' id='idCliente_".$i."' value=".$row["idCliente"].">
										 	</tr>";
							}
	
						}
					}
					/*[".utf8_decode($row["Giro"])."/".utf8_decode($row["Rubro"])."]*/
	            	$i++;
				}
				$select_cliente .= "<input id='cont_clientes' value=".$i." type='hidden'>";
				$select_cliente .= "</table>";
			}
		}
		$select_cliente .= "</div>";
		if($clienteAComprobar == true)
		{
			$select_cliente .= "<p><div style='display: table; width: 90%;'>
										<div style='display: table-cell; text-align: left; width: 3%; vertical-align: top-right;'><b>*</b></div>
										<div style='display: table-cell; text-align: left; width: 72%'>En el campo \"Estatus\": 
																		<br><b><font color=#FF000C>-1</font></b>: No se ha comprobado disponibilidad en stock
																		<br><b><font color=#000CFF> 0</font></b>: Se comprobó disponibilidad, pero aun no ha sido asignado una ruta 
																		<br><b><font color=#01a05f> 1</font></b>: El producto ya fue asignado a una ruta </div>
										<div style='display; table-cell; text-align: right;width: 25%; padding-right: 20px; vertical-align: top;'><input type='button' value='Comprobar disponibilidad' onclick='verProducto();' id='forward' ></div>
									</div>";
		}
		else
		{
			$select_cliente .= "<p><div style='display: table; width: 90%;'>
										<div style='display: table-cell; text-align: left; width: 3%; vertical-align: top-right;'><b>*</b></div>
										<div style='display: table-cell; text-align: left; width: 72%'>En el campo \"Estatus\": 
																		<br><b><font color=#FF000C>-1</font></b>: No se ha comprobado disponibilidad en stock
																		<br><b><font color=#000CFF> 0</font></b>: Se comprobó disponibilidad, pero aun no ha sido asignado una ruta 
																		<br><b><font color=#01a05f> 1</font></b>: El producto ya fue asignado a una ruta </div>
										<div style='display; table-cell; text-align: right;width: 25%; padding-right: 20px; vertical-align: top;'><input type='button' value='Comprobar disponibilidad' disabled onclick='verProducto();' id='forward' ></div>
									</div>";
		}
		
		echo $select_cliente;
	}

//---------------------------------------------------------------------------------

	function verProducto($connection)
	{
		$id= $_POST['idProducto'];
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		
		$nombre = $_POST['producto'];
		$cantidad = $_POST['cantidad'];	
		$idSucursal = $_POST['idSucursal'];
		$sucursal = $_POST['sucursal'];	
		$total_pedido = $_POST['total_pedido'];
		$idUnidad = $_POST['idUnidad'];
		
		$_SESSION['unidad'] = "";
		
		$cantidad = conversion($connection, $idUnidad, $cantidad);
		$total_pedido = $total_pedido*$cantidad;
		
		$stock = 0;
		$minimo = 0;
		
		$aviso_sucursal = '';
		
		$_SESSION['contador_productos'] = 0;
		$_SESSION["materiales"]=array();
		
		if($idSucursal == 0)
		{

			$stockNstuff = $connection->query('SELECT cantidad-ocupados cantidad FROM mrp_stock WHERE idProducto = '.$id);
			$aviso_sucursal = "<b>todas las sucursales</b>";		
			
			if($stockNstuff->num_rows > 0)
			{
				while($row = $stockNstuff->fetch_array())
				{
					$stock += $row['cantidad'];
				}
			}
		}
		else
		{
			$stockNstuff = $connection->query('SELECT cantidad-ocupados cantidad FROM mrp_stock WHERE idProducto = '.$id.' AND idSuc = '.$idSucursal);
			$aviso_sucursal = "la sucursal <b>".$sucursal."</b>";
			
			if($stockNstuff->num_rows > 0)
			{
				if($row = $stockNstuff->fetch_array())
				{
					$stock = $row['cantidad'];
				}
			}
		}
		
		$minimoProducto = $connection->query('SELECT minimo FROM mrp_producto WHERE idProducto = '.$id);
		
		if($minimoProducto->num_rows > 0)
		{
			if($row = $minimoProducto->fetch_array())
			{
				$minimo = $row[minimo];
			}
		}
		
		if(($stock - $total_pedido) < $minimo)
		{
			
			$aviso_stock = "<b>todos los productos del stock</b>, a excepcion de los <b>".$minimo." ".$_SESSION['unidad']."</b> marcados como mínimo de reorden del producto.";
			
			$a_pedir = abs(($stock-$total_pedido)-$minimo);
			if($a_pedir)
			{

				$aviso_a_pedir = ". Por tanto, se necesitan pedir ó producir <b>".$a_pedir." ".$_SESSION['unidad']." de ".$nombre."</b> para cubrir la cantidad necesaria para los clientes que han respondido hasta el momento.
				<p><div style='color: #FF000C;'>Asegúrese de, que con los productos que pedirá en su orden de compra, podrá cubrir los <b>".$a_pedir." ".$_SESSION['unidad']."</b> necesarios:</div>";
			}
		}
		else 
		{

			$aviso_stock = "<b>".$total_pedido."</b> de los <b>".$stock."</b> existentes en stock (contando los <b>".$minimo."</b> marcados como minimo del producto) ";
		}
		
		$aviso_orden_compra = "<div style='text-align: left; color: #006efe'>Actualmente en ".$aviso_sucursal." hay un total de <b>".$stock." ".$_SESSION['unidad']." de ".$nombre."</b>.
							   <br>Hasta el momento, los pedidos de los clientes es el equivalente de <b>".$total_pedido." ".$_SESSION['unidad']."</b>. 
							   <p>Se agotarán ".$aviso_stock."".$aviso_a_pedir."
							  </div> ";
		
		$query_materiales = $connection->query("SELECT p.id, p.idProducto, p.cantidad, p.idUnidad, p.idMaterial, l.nombre Nom , u.compuesto
												FROM mrp_producto_material p
												INNER JOIN mrp_producto l ON p.idMaterial = l.idProducto
												INNER JOIN mrp_unidades u on u.idUni=p.idUnidad WHERE p.idProducto= ".$id);
		
		
		if($query_materiales->num_rows < 1)
		{
			//echo 2222;
			echo verComponente2($connection, $id, $nombre, $cantidad, $idSucursal, $sucursal, $total_pedido);
		}
		else
		{
			$cadena_compuestos_anidados=$aviso_orden_compra;
			
			if(($stock - $total_pedido) < $minimo)
			{
				 $cadena_compuestos_anidados='
							
					  			<div>
								<table cellpadding="0" cellspacing="0">
								<p>'.$aviso_orden_compra.'
								<p><div style="display: table;">
										<div style="display: table-cell; font-size: 16">Quiero producir: <input type="text" class="numeric" maxlenght="8" placeholder="cantidad" id="cantidad_base"> '.$nombre.' </div>
										<div style="display: table-cell; font-size: 16; padding-left: 20px"><input type="button" value="Calcular" id="calcular" onclick="cargaAcordeon();"></div>
										<img id="preloader_calcular" src="../../images/preloader.gif">
									</div>
								<p>	
								<div id="aqui_carga_acordeon"></div>
								
								</table>
								</div>';		
			}
			else 
			{
				$cadena_compuestos_anidados .= "
									<input type='button' value='Proceder' onclick='proceder();' >
									<br>(Recuerde que solo se comprobó disponibilidad para los clientes que han respondido hasta el momento).
								";
			}
			echo $cadena_compuestos_anidados;
		}
	}

//---------------------------------------------------------------------------------

	function prep_reccomponentes($connection)
	{
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		
		$id = $_POST['id'];
		$a_pedir = $_POST['a_pedir'];
		$nombre = $_POST['nombre'];
		$idSuc = $_POST['idSuc'];
		
		$cadena = "";
		
		$cadena .= reccomponentes($connection, $id, $a_pedir, $nombre);
		if($idSuc == 0)
			{
				$cadena .= "<p><div>".buscaSucursal($connection)."</div><p>";
			}
		$cadena .= "<input type='hidden' id='contador_componentes' value=".$_SESSION['contador_productos'].">";
		$cadena .= '<input type="button" value="Generar ordenes de compra" onClick="generarOrdenesCompraComponentes();" />
				<br>(Asegúrese de haber rellenado la información de proveedor y unidad para el producto).';
		
		unset($_SESSION['contador_productos']);
		echo $cadena;
	}
	
//---------------------------------------------------------------------------------
	
	function reccomponentes($connection, $id, $cantidad, $nombre)
	{
		$cadena_compuestos_anidados = '';

		$re=componentes($connection, $id);		
		if(count($re)>0) //Tiene mas de algun componente
		{
			$cadena_compuestos_anidados.='<td colspan="9">';
			$cadena_compuestos_anidados.='<div class="acordeon ">'; 	
  
  			$cadena_compuestos_anidados.="<h3>".$cantidad." ".$nombre."
										</h3>";
							
   			$cadena_compuestos_anidados.='
   							
	  						<div>
				 			<table border="0" width="100%" align="center">';
				
			$cadena_compuestos_anidados.='	
							<tr>
								<th width=10% style="border-bottom: 1px solid #006efe;">Cantidad</th>
								<th width=25% style="border-bottom: 1px solid #006efe;">Producto</th>
								<th width=30% style="border-bottom: 1px solid #006efe;">Proveedor</th>
								<th width=20% style="border-bottom: 1px solid #006efe;">Ultimo precio</th>
								<th width=15% style="border-bottom: 1px solid #006efe;">Unidad</th>
					  		</tr>';
					
			foreach($re as $key=>$r)
			{
				list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
				$productop=datosproducto($connection, $idp);
				$cadena_compuestos_anidados .= reccomponentes($connection, $idp,($cantidad*$cantidadp),$nombrep);
			}	
			$cadena_compuestos_anidados.='
							</table>
						  	</div>
						  	</div>';
		}
		else //No tiene mas componentes
		{
			$_SESSION['contador_productos'] ++;  		
			$cadena_compuestos_anidados.="<tr>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><label value='".$cantidad."' id='cantidad_compuesto_".$_SESSION['contador_productos']."'>".$cantidad."</label></td>
										<input type='hidden' id='producto_id_".$_SESSION['contador_productos']."' value=".$id.">
										<input type='hidden' id='nombre_producto_".$_SESSION['contador_productos']."' value='".$nombre."'>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px;'>".$nombre."</td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px; padding-right: 10px;'><center>".proveedorFiltroProductoCompuesto($connection, $id)."</center></td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><img class='preloader_preview_elemento' id='preloader_preview_producto_costo_".$_SESSION['contador_productos']."' src='../../images/preloader.gif'><center>".cargaUltimoCostoPreviewComponente($connection, $id)."</center></td>
										<td style='border-bottom: 1px solid #EEEEEE; text-align: right; '><img class='preloader_preview_elemento' id='preloader_preview_producto_unidad_".$_SESSION['contador_productos']."' src='../../images/preloader.gif'><center>".cargaUnidadesPreviewComponente($connection, $id)."</center></td>
									 	</tr>";
		}
		return $cadena_compuestos_anidados;
	}		 

//---------------------------------------------------------------------------------

	function componentes($connection, $id)
	{
		$arreglo=array();
		$query_materiales = $connection->query("SELECT p.id, p.idProducto, p.cantidad, p.idUnidad, p.idMaterial, l.nombre Nom , u.compuesto
										FROM mrp_producto_material p
										INNER JOIN mrp_producto l ON p.idMaterial = l.idProducto
										INNER JOIN mrp_unidades u on u.idUni=p.idUnidad WHERE p.idProducto=".$id.";");
		
		if($query_materiales->num_rows > 0)
		{							
			while($row = $query_materiales->fetch_array())
				$rows[] = $row;
			foreach($rows as $row)
			{
				$arreglo[$row['idMaterial']."_".$row['Nom']."_".$row['compuesto']."_".$row['cantidad']] = componentes($connection, $row['idMaterial']);
			}
		}
		return $arreglo;
	}
	
//---------------------------------------------------------------------------------

	function verComponente2($connection, $id, $nombre, $cantidad, $idSucursal, $sucursal, $total_pedido)
	{
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		$stock = 0;
		$minimo = 0;
		$a_pedir = 0;
		
		$aviso_a_pedir = "";
		
		if($idSucursal == 0)
		{
			$stockNstuff = $connection->query('SELECT cantidad-ocupados cantidad FROM mrp_stock WHERE idProducto = '.$id);
			$aviso_sucursal = "<b>todas las sucursales</b>";		
			
			if($stockNstuff->num_rows > 0)
			{
				while($row = $stockNstuff->fetch_array())
				{
					$stock += $row['cantidad'];
				}
			}
		}
		else
		{
			$stockNstuff = $connection->query('SELECT cantidad-ocupados cantidad FROM mrp_stock WHERE idProducto = '.$id.' AND idSuc = '.$idSucursal);
			$aviso_sucursal = "la sucursal <b>".$sucursal."</b>";
			
			if($stockNstuff->num_rows > 0)
			{
				if($row = $stockNstuff->fetch_array())
				{
					$stock = $row['cantidad'];
				}
			}
		}
		
		$minimoProducto = $connection->query('SELECT minimo FROM mrp_producto WHERE idProducto = '.$id);
		
		if($minimoProducto->num_rows > 0)
		{
			if($row = $minimoProducto->fetch_array())
			{
				$minimo = $row[minimo];
			}
		}
		
		if(($stock - $total_pedido) < $minimo)
		{
			echo '<b>No hay suficientes productos en inventario.</b><br><br>';
			$aviso_stock = "<b>todos los productos del stock</b>, a excepcion de los <b>".$minimo." ".$_SESSION['unidad']."</b> marcados como mínimo de reorden del producto.";
			
			$a_pedir = abs(($stock-$total_pedido)-$minimo);
			if($a_pedir)
			{
				$aviso_a_pedir = /*". Por tanto, se necesitan pedir ó producir <b>".$a_pedir." ".$_SESSION['unidad']." de ".$nombre."</b> para cubrir la cantidad necesaria para los clientes que han respondido hasta el momento.
				<p><div style='color: #FF000C;'>Asegúrese de, que con los productos que pedirá en su orden de compra, podrá cubrir los <b>".$a_pedir." ".$_SESSION['unidad']."</b> necesarios:</div>"*/"";
			}
		}
		else 
		{
			$aviso_stock = "<b>".$total_pedido."</b> de los <b>".$stock."</b> existentes en stock (contando los <b>".$minimo."</b> marcados como minimo del producto) ";
		}
		
		$aviso_orden_compra = "<div style='text-align: left; color: #006efe'>Actualmente en ".$aviso_sucursal." hay un total de <b>".$stock." ".$_SESSION['unidad']." de ".$nombre."</b>.
							   <br>Hasta el momento, los pedidos de los clientes es el equivalente de <b>".$total_pedido." ".$_SESSION['unidad']."</b>. 
							   <p><!-- Se agotarán ".$aviso_stock."".$aviso_a_pedir."
							  --> </div> ";
							   
		$grid_preview = $aviso_orden_compra;
		
		if(($stock - $total_pedido) < $minimo)
		{
			$grid_preview .="";
			/*$grid_preview .=	"	
								<div class='acordeon'>
								<h3 style='text-align: left;'>".$nombre."</h3>
						  		<div>
								<table cellpadding='0' cellspacing='0'>
										<tr>
										<th width=10% style='border-bottom: 1px solid #006efe;'>Cantidad</th>
										<th width=25% style='border-bottom: 1px solid #006efe;'>Producto</th>
										<th width=30% style='border-bottom: 1px solid #006efe;'>Proveedor</th>
										<th width=20% style='border-bottom: 1px solid #006efe;'>Ultimo precio</th>
										<th width=15% style='border-bottom: 1px solid #006efe;'>Unidad</th>
										</tr>";
			
			$grid_preview .= "			<tr>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><center><input type='text' class='numeric' id='cantidad_compuesto' style='max-width: 50px;'></center></td>
										<input type='hidden' id='producto_id' value=".$id.">
										<input type='hidden' id='nombre_producto' value='".$nombre."'>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px;'>".$nombre."</td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999; padding-left: 10px; padding-right: 10px;'><center>".proveedorFiltroProducto($connection, $id)."</center></td>
										<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #999999;'><img class='preloader_preview_elemento' id='preloader_preview_componente_costo' src='../../images/preloader.gif'><center>".cargaUltimoCostoPreview($connection, $id)."</center></td>
									  	<td style='border-bottom: 1px solid #EEEEEE; text-align: right; '><img class='preloader_preview_elemento' id='preloader_preview_componente_unidad' src='../../images/preloader.gif'><center>".cargaUnidadesPreview($connection, $id)."<div id='equivalencia_componente_unidad'></div></center></td>
									   	</tr>";
		 
			$grid_preview .= "	
								</table>
								</div>
								</div>";
								*/
			if($idSucursal == 0)
			{
				$grid_preview .= "";//"<p><div>".buscaSucursal($connection)."</div><p>";
			}
			$grid_preview .= /*"
									<input type='button' id='agregar_componente' value='Generar orden de compra y proceder' onclick='generarOrdenCompraProducto();' >
									<br>(Asegúrese de haber rellenado la información de proveedor y unidad para el producto).
								"*/"";
		}
		else
		{
			$grid_preview .= "
									<input type='button' id='agregar_componente' value='Proceder' onclick='proceder();' >
									<br>(Recuerde que solo se comprobó disponibilidad para los clientes que han respondido hasta el momento).
								";
		}
		echo $grid_preview;
	}
	
//---------------------------------------------------------------------------------

	function datosproducto($connection, $id)
	{	
		$query_producto = $connection->query('SELECT p.idProducto, p.nombre FROM mrp_producto p WHERE p.idProducto='.$id);
		return $query_producto->fetch_row();
	}

//---------------------------------------------------------------------------------

	function proveedorFiltroProducto($connection, $id)
	{	
		if(buscaProductoProveedor($connection, $id) == false)
			return "<div id='proveedor_producto'>No hay un proveedor para este producto</div>";
		else
		{
			$select =  "<div><select id='proveedor_producto' style='width: 100%;' onchange='filtraUltimoCostoYUnidadPreview(this.value, document.getElementById(\"producto_id\").value);'>";
		
			$sugerido = proveedorSugerido($connection, $id);
		 	if(proveedorSugerido($connection, $id) == false)
				//No habra un proveedor preseleccionado
				$select .= '<option value="" selected>Selecciona proveedor</option>';
			foreach(buscaProductoProveedor($connection, $id) as $prv):
			
				//Si es diferente de false si hay un proveedor sugerido, por lo que entrara a comprobar cual es y pondra uno
				//seleccionado por defecto. Si no hay algun proveedor aventara todos los proveedores sin alguno seleccionado
				if (proveedorSugerido($connection, $id) != false)
				{
					if ($prv->ID == $sugerido->ID)
						$select .= '<option value="'.$prv[ID].'" selected>'.utf8_decode($prv[Nombre]).'</option>';
					else
						$select .= '<option value="'.$prv[ID].'">'.utf8_decode($prv[Nombre]).'</option>';
				}
				else
					$select .= '<option value="'.$prv[ID].'">'.utf8_decode($prv[Nombre]).'</option>';
			endforeach;
			
			$select .= "</select></div>";
			return $select;
		}
	}

//---------------------------------------------------------------------------------

	function proveedorFiltroProductoCompuesto($connection, $id)
	{
		if(!isset($_SESSION)) 
		{
			session_start();
		}
	
		if (buscaProductoProveedor($connection, $id) == false)
		{
			return "<div id='proveedor_producto_".$_SESSION['contador_productos']."'>No hay un proveedor para este producto</div>";	
		}
		else
		{
			$select =  "<div><select id='proveedor_producto_".$_SESSION['contador_productos']."' style='width: 100%;' onchange='filtraUltimoCostoYUnidadPreviewCompuesto(this.value, document.getElementById(\"producto_id_".$_SESSION['contador_productos']."\").value, ".$_SESSION['contador_productos'].");'>";
			$sugerido = proveedorSugerido($connection, $id);
		 	if (proveedorSugerido($connection, $id) == false)
			{
				//No habra un proveedor preseleccionado
				$select .= '<option value="" selected>Selecciona proveedor</option>';
			}
			foreach(buscaProductoProveedor($connection, $id) as $prv):
			
				//Si es diferente de false si hay un proveedor sugerido, por lo que entrara a comprobar cual es y pondra uno
				//seleccionado por defecto. Si no hay algun proveedor aventara todos los proveedores sin alguno seleccionado
				if (proveedorSugerido($connection, $id) != false)
				{
					if ($prv->ID == $sugerido->ID)
					{
						$select .= '<option value="'.$prv[ID].'" selected>'.utf8_decode($prv[Nombre]).'</option>';
					}
					else
					{
						$select .= '<option value="'.$prv[ID].'">'.utf8_decode($prv[Nombre]).'</option>';
					}
				}
				else
				{
					$select .= '<option value="'.$prv[ID].'">'.utf8_decode($prv[Nombre]).'</option>';
				}
			endforeach;
			
			$select .= "</select></div>";
			return $select;
		}
	}
	
//---------------------------------------------------------------------------------
	
	function filtraUnidadProductoCompuesto($connection)
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];
		$contador = $_POST['contador'];
		
		$select_unidad = "<div>
								<select id='unidad_producto_".$contador."'>";
		$select_unidad .= "			<option value=''>----------</option>";
		foreach(buscaUnidad($connection, $idPro, $idPrv) as $uni=>$idUni):
			$piezas = explode("_", $idUni);
			$select_unidad .= '		<option value="'.$piezas[0].'">'.$piezas[1].'</option>';
		endforeach;
		$select_unidad .= "		</select>
						 </div>";
		
		echo $select_unidad;
	}
	
//---------------------------------------------------------------------------------
	
	function cargaUltimoCostoPreviewComponente($connection, $id)
	 {
		if (buscaProductoProveedor($connection, $id) == false)
		{
			return "<div id='ultimo_costo_".$_SESSION['contador_productos']."'>Registre primero un proveedor para el producto</div>";	
		}
		else
		{
			if (proveedorSugerido($connection, $id) == false)
			{
				return '<input class="numeric" type="text" id="ultimo_costo_'.$_SESSION['contador_productos'].'" style="width: 60%" value="">';
			}
			else
			{
				$row=proveedorSugerido($connection, $id);
				return '<input class="numeric" type="text" id="ultimo_costo_'.$_SESSION['contador_productos'].'" style="width: 60%" value="'.$row[Cos].'">';
			}
		}
	 }  

//---------------------------------------------------------------------------------
	 
	function filtraUltimoCosto($connection)
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];	
		
		foreach(buscaUltimoCosto($connection, $idPrv, $idPro) as $cos):
			echo $cos['Precio'];
		endforeach;
	}

//---------------------------------------------------------------------------------
	
	function filtraUltimoCostoProductoCompuesto($connection)
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];
		
		foreach(buscaUltimoCosto($connection, $idPrv, $idPro) as $cos):
			echo $cos['Precio'];
		endforeach;
	}

//---------------------------------------------------------------------------------
		
	function filtraUnidad($connection)
	{
		$idPro = $_POST['idPro'];
		$idPrv = $_POST['idPrv'];
		
		$select_unidad = "<div><select id='unidad_producto' onchange='obtieneEquivalenciaUnidadCompuesto(this.value);'>";
		$select_unidad .= "<option value=''>----------</option>";
		foreach(buscaUnidad($connection, $idPro, $idPrv) as $uni=>$idUni):
			$piezas = explode("_", $idUni);
			$select_unidad .= '<option value="'.$piezas[0].'">'.$piezas[1].'</option>';
		endforeach;
		$select_unidad .= "</select></div>";
		
		echo $select_unidad;
	}
	
//---------------------------------------------------------------------------------	

	function cargaUltimoCostoPreview($connection, $id)
	{
	 	if (buscaProductoProveedor($connection, $id) == false)
		{
			return "<div id='ultimo_costo'>Registre primero un proveedor para el producto</div>";	
		}
		else
		{
			if (proveedorSugerido($connection, $id) == false)
			{
				return '<input maxlength="8" class="numeric" type="text" id="ultimo_costo" style="width: 60%" value="">';
			}
			else
			{
				$row=proveedorSugerido($connection, $id);
				return '<input maxlength="8" class="numeric" type="text" id="ultimo_costo" style="width: 60%" value="'.$row[Cos].'">';
			}
		}
	} 
	
//---------------------------------------------------------------------------------
	
	function cargaUnidadesPreview($connection, $id)
	{
		if (proveedorSugerido($connection, $id) != false)
		{
			$row = proveedorSugerido($connection, $id);
			$select_unidad = "<div id='uni'><div><select id='unidad_producto' onchange='obtieneEquivalenciaUnidadCompuesto(this.value);'>";
			$select_unidad .= "<option value=''>----------</option>";
			
			foreach(buscaUnidad($connection, $id, $row[ID]) as $uni=>$idUni):
				$piezas = explode("_", $idUni);
				$select_unidad .= '<option value="'.$piezas[0].'">'.$piezas[1].'</option>';
			endforeach;
			$select_unidad .= "</select></div></div>";
		}
		else
		{
			if (buscaProductoProveedor($connection, $id) != false)
			{
				return "<div id='uni'>Selecciona primero proveedor</div>";
			}
			else 
			{
				return "<div id='uni'>Este producto no tiene unidades</div>";
			}
		}
		
		return $select_unidad;
	} 

//---------------------------------------------------------------------------------	 
	
	function cargaUnidadesPreviewComponente($connection, $id)
	{
		if (proveedorSugerido($connection, $id) != false)
		{
			$row = proveedorSugerido($connection, $id);
			$select_unidad = "<div id='uni_".$_SESSION['contador_productos']."'>
									<div>
										<select id='unidad_producto_".$_SESSION['contador_productos']."'>";
			$select_unidad .= "				<option value=''>----------</option>";
			
			foreach(buscaUnidad($connection, $id, $row[ID]) as $uni=>$idUni):
				$piezas = explode("_", $idUni);
				$select_unidad .= '			<option value="'.$piezas[0].'">'.$piezas[1].'</option>';
			endforeach;
			$select_unidad .= "			</select>
									</div>
								</div>";
		}
		else
		{
			if (buscaProductoProveedor($connection, $id) != false)
			{
				return "<div id='uni_".$_SESSION['contador_productos']."'>Selecciona primero proveedor</div>";
			}
			else 
			{
				return "<div id='uni_".$_SESSION['contador_productos']."'>Este producto no tiene unidades</div>";
			}
		}
		
		return $select_unidad;
	} 
	
//---------------------------------------------------------------------------------

	function obtieneEquivalenciaUnidadCompuesto ($connection)
	{
		$unidad = $_POST['idUnidad'];
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		
		echo conversion($connection, $unidad, 1)."$$$^^^###///".$_SESSION['unidad'];
	}


	//Algunas funciones que deberian ir en el Modelo ---------------------------------------------------------------------------------------------
	// -----------------------------------------------------------------------------------------------------------------------------------
	// -----------------------------------------------------------------------------------------------------------------------------------
	// -----------------------------------------------------------------------------------------------------------------------------------
	// -----------------------------------------------------------------------------------------------------------------------------------
	// -----------------------------------------------------------------------------------------------------------------------------------
	// -----------------------------------------------------------------------------------------------------------------------------------
	
	
	function buscaUltimoCosto($connection, $idPrv, $idPro)
	{	
		$query = $connection->query('	SELECT p.costo Precio
									FROM mrp_producto_proveedor p
									WHERE p.idProducto = '.$idPro.' AND p.idPrv = '.$idPrv.';');
									
		if ($query->num_rows <1)
		{
			return false;
		}
		else 
		{
			while ($row = $query->fetch_assoc()) 
			{
	     		$parameters[] = $row;
	    	}
	        return $parameters;
		}
	}
	
//---------------------------------------------------------------------------------
	
	function proveedorSugerido($connection, $id)
	{	
		$query_proveedor_sugerido = $connection->query(' SELECT pr.razon_social Nombre, oc.idProveedor ID, c.fecha, dc.costo Cos, poc.idUnidad Uni, u.compuesto Com	
									FROM mrp_detalle_compra dc, mrp_compra c, mrp_producto_orden_compra poc, 
										mrp_producto p, mrp_unidades u, mrp_orden_compra oc, mrp_proveedor pr
									WHERE dc.idCompra = c.id
									AND dc.idProductoOrdenCompra = poc.idPrOr
									AND p.idProducto = poc.idProducto
									AND u.idUni = poc.idUnidad
									AND oc.idOrd = poc.idOrden
									AND oc.idProveedor = pr.idPrv
									AND p.idProducto = '.$id.'
									ORDER BY c.fecha DESC, dc.costo ASC
									LIMIT 0,1;');
		
		if($query_proveedor_sugerido->num_rows > 0)
		{
			return $query_proveedor_sugerido->fetch_assoc();
		}
		else 
		{
			return false;
		}
	}
	
//---------------------------------------------------------------------------------

	function buscaProductoProveedor($connection, $id)
	{		
		$query_producto_proveedor = $connection->query('	SELECT p.idPrv ID, p.razon_social Nombre
									FROM mrp_proveedor p
									INNER JOIN mrp_producto_proveedor l ON p.idPrv = l.idPrv
									INNER JOIN mrp_producto f ON f.idProducto = l.idProducto
									WHERE l.idProducto = '.$id.';');
									
		if($query_producto_proveedor->num_rows > 0)
		{
			while ($row = $query_producto_proveedor->fetch_assoc()) 
			{
	     		$parameters[] = $row;
	    	}
	        return $parameters;
		}
		else 
		{
			return false;
		}
	}
	
//---------------------------------------------------------------------------------	
	
	function buscaUnidad($connection, $idPro, $idPrv)
	{
		$resul = $connection->query("SELECT idUni FROM mrp_producto_proveedor WHERE idPrv = ".$idPrv." AND idProducto = ".$idPro.";");
		
		$query = desglosaUnidad($connection, $resul->fetch_assoc());		
		if ($resul->num_rows > 0) {
			return $query;
		}
		else {
			return false;
		}
	}

//---------------------------------------------------------------------------------
		
	function desglosaUnidad($connection, $id)//4,9,13
	{
		$query = $connection->query('SELECT idUni,compuesto FROM mrp_unidades WHERE idUni='.$id[idUni]); 
		
		if ($query->num_rows > 0)
		{
			$row = $query->fetch_assoc();
		
			 $hijos=damehijos($connection, $id[idUni],true);
			 $padres=damepadres($connection, $id[idUni],true);
			 $result = array_merge((array)$hijos, (array)$padres);
			 $result[]=$row[idUni]."_".$row[compuesto];
			 return ($result);
		}
	}

//---------------------------------------------------------------------------------	
	
	function damehijos($connection, $id,$raiz=false)
	{
		$hijosindemiatos=array();	
		
		if($raiz){$r=',(SELECT compuesto FROM mrp_unidades WHERE idUni='.$id.') as raiz';}else{$r='';}
		$query = $connection->query('select idUni,compuesto,conversion,unidad '.$r.' from mrp_unidades where idUni=(
		SELECT unidad FROM mrp_unidades WHERE idUni='.$id.')'); 
		
		while($row=$query->fetch_assoc())
		{
			
			
			if($row[idUni]!=$id)
			{
				$hijosindemiatos[]=$row[idUni]."_".$row[compuesto];
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)damehijos($connection, $row[idUni]));
				
			}
		}
		
		$hijosindemiatos=array_unique($hijosindemiatos);
		return $hijosindemiatos;
		
	}

//---------------------------------------------------------------------------------

	function damepadres($connection, $id,$raiz=false)
	{
		$hijosindemiatos=array();
		$query = $connection->query('SELECT idUni,unidad,compuesto FROM mrp_unidades WHERE unidad='.$id); 

		while($row=$query->fetch_assoc())
		{
			if($row[idUni]!=$id)
			{
				$hijosindemiatos[]=$row[idUni]."_".$row[compuesto];
				$hijosindemiatos = array_merge((array)$hijosindemiatos, (array)damepadres($connection, $row[idUni]));
				
			}
		}//while
		
		$hijosindemiatos=array_unique($hijosindemiatos);
		return $hijosindemiatos;
	}

//---------------------------------------------------------------------------------	
	
	function conversion($connection, $unidad, $cantidad)
	{
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		
		$conversion=1;
		$query = $connection->query('SELECT compuesto, conversion, unidad from mrp_unidades where idUni='.$unidad); 

		if($query -> num_rows > 0)
		{
			if($row = $query->fetch_assoc())
			{
				if($row[unidad]!=$unidad)
				{
					$conversion = conversion($connection, $row[unidad], $row[conversion]);
					//var_dump($conversion);
				}
				else
				{
					$_SESSION['unidad'] = $row[compuesto];
				}
			}
		}
		return $cantidad*$conversion;	
		
	}
	
//---------------------------------------------------------------------------------
	
	function registraOrdenProducto($connection)
	{
		$idProducto = $_POST['idProducto'];
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		
		date_default_timezone_set('America/Mexico_City'); 
		$hora = date("H:i:s");
		
		
		$cadena_inserts;
		
		$idSucursal = $_POST['idSucursal'];
		$fechaHoy = $_POST['fechaHoy'];
		$elaboradoPor = $_POST['elaboradoPor'];
		$idOferta = $_POST['idOferta'];
		
		$fechaHoy = $fechaHoy." ".$hora;
		
		$cantidad =$_POST['cantidad'];
		$idProveedor = $_POST['idProveedor'];
		$ultCosto = $_POST['ultCosto'];
		$idUnidad = $_POST['idUnidad'];
	
		$query = $connection->query("INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, idProveedor) values ('".$fechaHoy."', '".$elaboradoPor."', '".$idSucursal."', ".$idProveedor.")");
		$idOrden = $connection->insert_id;
		$query_producto = $connection->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$cantidad.', '.$idUnidad.', '.$idProducto.', '.$ultCosto.')');
		

		if ($result = $connection->query("  SELECT oc.idCliente, oc.contesto, oc.estatus, oc.idOrdenCompra, oc.fechaRespuesta 
										FROM sms_oferta_cliente oc WHERE idOferta = ".$idOferta.""))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				
				foreach($rows as $row)
				{ 
					if($row[contesto] == 1 && $row[fechaRespuesta] != NULL)
						if($row[idOrdenCompra]==NULL)
						{
							//$cadena_inserts .= "UPDATE sms_oferta_cliente SET idOrdenCompra =".$idOrden.", estatus = 0 WHERE idCliente = ".$row[idCliente]." AND idOferta = ".$idOferta."";
							$query_actualiza_oferta_cliente = $connection->query("UPDATE sms_oferta_cliente SET idOrdenCompra =".$idOrden.", estatus = 0 WHERE idCliente = ".$row[idCliente]." AND idOferta = ".$idOferta.";");
						}
				}
			}
		}
		//$cadena_inserts = 'INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$cantidad.', '.$idUnidad.', '.$idProducto.', '.$ultCosto.')';
		
		if(isset($_SESSION["unidad"])) unset($_SESSION["unidad"]);
		echo "Se generó la orden de compra";
	}
	
//---------------------------------------------------------------------------------

	function registraOrdenProductoCompuesto($connection)
	{
		if(!isset($_SESSION)) 
		{
			session_start();
		}
		
		date_default_timezone_set('America/Mexico_City'); 
		$hora = date("H:i:s");
		
		$cadena_inserts;
		
		$arrID =  $_POST['arrID']; 
		$arrPrv = $_POST['arrPrv'];
		$arrCos = $_POST['arrCos'];
		$arrUni = $_POST['arrUni'];
		$arrCnt = $_POST['arrCnt'];
		$contadorProductos = $_POST['contadorProductos'];
		
		$idSucursal = $_POST['idSucursal'];
		$fechaHoy = $_POST['fechaHoy'];
		$elaboradoPor = $_POST['elaboradoPor'];
		$idOferta = $_POST['idOferta'];
		
		$fechaHoy = $fechaHoy." ".$hora;
		
		array_multisort($arrPrv, SORT_NUMERIC, $arrCnt, $arrCos, $arrID, $arrUni);
		
		$idOrden = 0;
		
		for ($i=0;$i<count($arrPrv);$i++)
		{
			if ($i == 0)
			{
				$query = $connection->query("INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, idProveedor) values ('".$fechaHoy."', '".$elaboradoPor."', '".$idSucursal."', ".$arrPrv[$i].")");        
				//$cadena = "INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, idProveedor) values ('".$fechaHoy."', '".$elaboradoPor."', '".$idSucursal."', ".$arrPrv[$i].")";        
				$idOrden = $connection->insert_id;
				$query_producto = $connection->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$arrCnt[$i].', '.$arrUni[$i].', '.$arrID[$i].', '.$arrCos[$i].');');
				//$cadena2 = 'INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$arrCnt[$i].', '.$arrUni[$i].', '.$arrID[$i].', '.$arrCos[$i].');';
			}
			else 
			{
				if ($arrPrv[$i] != $arrPrv[$i-1])
				{
					$query = $connection->query("INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, idProveedor) values ('".$fechaHoy."', '".$elaboradoPor."', '".$idSucursal."', ".$arrPrv[$i].")");        
					$idOrden = $connection->insert_id;
					//$cadena .= "INSERT INTO mrp_orden_compra (fecha_pedido, elaborado_por, idSuc, idProveedor) values ('".$fechaHoy."', '".$elaboradoPor."', '".$idSucursal."', ".$arrPrv[$i].")";        
					
				}
				$query_producto = $connection->query('INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$arrCnt[$i].', '.$arrUni[$i].', '.$arrID[$i].', '.$arrCos[$i].');');
				//$cadena2 .= 'INSERT INTO mrp_producto_orden_compra (idOrden, cantidad, idUnidad, idProducto, ultCosto) values ('.$idOrden.', '.$arrCnt[$i].', '.$arrUni[$i].', '.$arrID[$i].', '.$arrCos[$i].');';	
			}
		}

		if ($result = $connection->query("  SELECT oc.idCliente, oc.contesto, oc.estatus, oc.idOrdenCompra, oc.fechaRespuesta 
											FROM sms_oferta_cliente oc WHERE idOferta = ".$idOferta.""))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				
				foreach($rows as $row)
				{ 
					if($row[contesto] == 1 && $row[fechaRespuesta] != NULL)
						if($row[idOrdenCompra]==NULL)
						{
							$query_actualiza_oferta_cliente = $connection->query("UPDATE sms_oferta_cliente SET idOrdenCompra =".$idOrden.", estatus = 0 WHERE idCliente = ".$row[idCliente]." AND idOferta = ".$idOferta.";");
						}
				}
			}
		}
		
		if(isset($_SESSION["unidad"])) unset($_SESSION["unidad"]);
		echo "Se generaron las ofertas correctamente";
	}
	
//---------------------------------------------------------------------------------
	
	function procederExistencias($connection)
	{
		$idOferta = $_POST['idOferta'];
		if(!isset($_SESSION)) 
		{
			session_start();
		}

		if ($result = $connection->query("  SELECT oc.idCliente, oc.contesto, oc.estatus, oc.idOrdenCompra, oc.fechaRespuesta 
										FROM sms_oferta_cliente oc WHERE idOferta = ".$idOferta.""))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				
				foreach($rows as $row)
				{ 
					if($row[contesto] == 1 && $row[fechaRespuesta] != NULL)
						if($row[idOrdenCompra]==NULL)
							$query_actualiza_oferta_cliente = $connection->query("UPDATE sms_oferta_cliente SET idOrdenCompra = 0, estatus = 0 WHERE idCliente = ".$row[idCliente]." AND idOferta = ".$idOferta."");
				}
			}
		}
		
		if(isset($_SESSION["unidad"])) unset($_SESSION["unidad"]);
		echo "Se actualizó la información";
	}


?>