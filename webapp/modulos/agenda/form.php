<?php
include_once ("../../netwarelog/catalog/conexionbd.php");
switch($_POST["funcion"]) {
	case "form" :
		formulario();
		break;
		
	case "guardagrupo" :
		echo guardagrupo($_POST["nombre"], $_POST["cliente"]);
		break;
		
	case "eliminargrupo" :
		eliminargrupo($_POST["id"], $_POST["cliente"]);
		break;
		
	case "eliminarevento" :
		eliminar($_POST["id"]);
		break;
		
	case "agregarevento" :
		agregar($_POST);
		break;
		
	case "loadexpediente" :
		echo LoadExpediente($_POST["cliente"]);
		break;
		
	case "actualizardescripcion" :
		echo actualizardescripcion($_POST["id"], $_POST["des"]);
		break;
		
	case "reloadgrupo" :
		echo realoadGrupo($_POST["cliente"]);
		break;
}
/////////////////////////////////////////////////////////////////////////
function actualizardescripcion($id, $des) {
	$query = "UPDATE `agenda` SET `descripcion` = '" . $des . "' WHERE `agenda`.`id` =" . $id . ";";
	try {
		mysql_query($query);
		echo 1;
	} catch(Exception $e) {
		echo 2;
	}

}

/////////////////////////////////////////////////////////////////
function guardagrupo($nombre, $cliente) {
	try {
		$query = "INSERT INTO `agenda_grupo` (`id`, `nombre`,`idCliente`) VALUES (NULL, '" . $nombre . "'," . $cliente . ");";
		$resq = mysql_query($query);
		echo realoadGrupo($cliente);
	} catch(Exception $e) {
		echo 2;
	}
}

/////////////////////////////////////////////////////////////////
function realoadGrupo($cliente) {
	$frm = '<select id="grupo" name="grupo" class="nminputselect">';
	$frm .= '<option value="">-Seleccione-</option>';
	
	if (is_numeric($cliente)) {
		$qc = mysql_query("Select id, nombre from agenda_grupo where activo=1 and idCliente=" . $cliente);
		
		while ($rowc = mysql_fetch_array($qc)) {
			$frm .= '<option value="' . $rowc["id"] . '">' . $rowc["nombre"] . '</option>';
		}
	}
	
	$frm .= '</select>';
	
	return $frm;
}

//////////////////////////////////////////////////////////////
function eliminargrupo($id, $cliente) {
	try {
		//$q=mysql_query("Delete from agenda_grupo where id=".$id);
		$q = mysql_query("Update agenda_grupo set activo=0 where id=" . $id);
		//var_dump("Delete from agenda_grupo where id=".$id)

		echo realoadGrupo($cliente);
	} catch(Exception $e) {
		echo 2;
	}
}

///////////////////////////////////////////////////////////////////
function agregar() {
	// date_default_timezone_set('America/Mexico_City');
	$array_replace = array(" a.m.", " p.m.");
	
	if ($_POST["todoeldia"] == "true") {
		$todoeldia = 1;
	} else {
		$todoeldia = 0;
	}
	
	$fecha_actual = strtotime(date("Y-m-d H:i:00", time()));
	$fecha_entradai = strtotime(str_replace($array_replace, ":00", $_POST["inicio"]));
	$fecha_entradaf = strtotime(str_replace($array_replace, ":00", $_POST["fin"]));
	
	if ($fecha_entradai >= $fecha_entradaf) {
		echo 5;
	} elseif ($fecha_actual > $fecha_entradai) {
		echo 4;
	} else {

		$qdisponiblei = mysql_query("SELECT  id FROM agenda where activo=1 and '" . str_replace($array_replace, ":00", $_POST["inicio"]) . "' BETWEEN inicio and fin");

		$qdisponiblef = mysql_query("SELECT  id FROM agenda where activo=1 and '" . str_replace($array_replace, ":00", $_POST["fin"]) . "' BETWEEN inicio and fin");

		$iniciocorto = explode(" ", str_replace($array_replace, ":00", $_POST["inicio"]));
		$fincorto = explode(" ", str_replace($array_replace, ":00", $_POST["fin"]));

		$qdisponiblet = mysql_query("SELECT  id FROM agenda where activo=1 and (fin='" . $fincorto[0] . " 00:00:00' and todoeldia=1) OR (inicio='" . $iniciocorto[0] . " 00:00:00' and todoeldia=1)");

		if (mysql_num_rows($qdisponiblei) > 0 && !is_numeric($_POST["id"])) {
			echo 3;
		} elseif (mysql_num_rows($qdisponiblef) > 0 && !is_numeric($_POST["id"])) {
			echo 3;
		} elseif (mysql_num_rows($qdisponiblet) > 0 && !is_numeric($_POST["id"])) {
			echo 3;
		} else {
			if ($todoeldia == 1) {
				$inicio = explode(" ", str_replace($array_replace, ":00", $_POST["inicio"]));
				$fin = explode(" ", str_replace($array_replace, ":00", $_POST["fin"]));
				$fecha_inicio = $inicio[0] . " 00:00:00";
				$fecha_fin = $fin[0] . " 23:59:59";
			} else {
				$fecha_inicio = str_replace($array_replace, ":00", $_POST["inicio"]);
				$fecha_fin = str_replace($array_replace, ":00", $_POST["fin"]);
			}

			if (!is_numeric($_POST["id"])) {
				if (!is_numeric($_POST["grupo"])) {$grupo = "NULL";
					/*$color=randomColor();*/
				} else {
					$grupo = $_POST["grupo"];
				}
				//$samecolorquery=mysql_query("Select color from agenda where idGrupo=".$grupo);

				$samecolorquery = mysql_query("Select color from agenda where idCliente=" . $_POST["cliente"]);

				if (mysql_num_rows($samecolorquery) > 0) {
					$rowsamecolor = mysql_fetch_array($samecolorquery);
					$color = $rowsamecolor["color"];
				} else {
					$color = randomColor();
				}
				//}
				$query = "INSERT INTO `agenda` (`id`, `titulo`, `inicio`, `fin`, `todoeldia`, `descripcion`, `color`, `idGrupo`, `idCliente`) VALUES (NULL, '" . $_POST["titulo"] . "', '" . $fecha_inicio . "', '" . $fecha_fin . "', '" . $todoeldia . "', '" . $_POST["descripcion"] . "', '" . $color . "', " . $grupo . "," . $_POST["cliente"] . ");";

			} else {
				if (!is_numeric($_POST["grupo"])) {
					$grupo = "NULL";
				} else {
					$grupo = $_POST["grupo"];
				}
				$query = "UPDATE `agenda` SET  `idCliente` = " . $_POST["cliente"] . ",  `idGrupo` = " . $grupo . ", `titulo` = '" . $_POST["titulo"] . "',`descripcion` = '" . $_POST["descripcion"] . "' ,`inicio` = '" . $fecha_inicio . "' ,`fin` = '" . $fecha_fin . "' , `todoeldia` = " . $todoeldia . " WHERE `agenda`.`id` =" . $_POST["id"] . ";";
			}

			try {
				mysql_query($query);
				echo 1;
			} catch(Exception $e) {
				echo 2;
			}
		}
		//else
	}
}

///////////////////////////////////////////////////////////////////
function eliminar($id) {
	try {
		//$q=mysql_query("Delete from agenda where id=".$id);
		$q = mysql_query("Update agenda set activo=0 where id=" . $id);

		echo 1;
	} catch(Exception $e) {
		echo 2;
	}
}

///////////////////////////////////////////////////////////////////
function randomColor() {
	$str = '#';
	
	for ($i = 0; $i < 6; $i++) {
		$randNum = rand(0, 15);
		
		switch ($randNum) {
			case 10 :
				$randNum = 'A';
				break;
			case 11 :
				$randNum = 'B';
				break;
			case 12 :
				$randNum = 'C';
				break;
			case 13 :
				$randNum = 'D';
				break;
			case 14 :
				$randNum = 'E';
				break;
			case 15 :
				$randNum = 'F';
				break;
		}
		
		$str .= $randNum;
	}

	$colorquery = mysql_query("Select color from agenda where color='" . $str . "'");
	$colorrow = mysql_fetch_array($colorquery);
	
	if (mysql_num_rows($colorquery) > 0) {
		return randomColor();
	} else {
		return $str;
	}
}

///////////////////////////////////////////////////////////////////
function formulario() {
	if (count($_POST) > 0) {
		$id = $_POST["id"];
		$cliente = $_POST["cliente"];
		$titulo = $_POST["titulo"];
		$inicio = $_POST["inicio"];
		$fin = $_POST["fin"];
		$descripcion = $_POST["descripcion"];
		
		if (strcmp($_POST["todoeldia"], "true") == 0) {
			$todoeldia = "checked";
		}

		if (is_numeric($_POST["id"])) {
			$query = mysql_query("Select inicio,fin,todoeldia,idGrupo,idCliente from agenda where id=" . $_POST["id"]);
			$row = mysql_fetch_array($query);
			$inicio = $row["inicio"];
			$fin = $row["fin"];
			$cliente = $row["idCliente"];
			$grupo = $row["idGrupo"];
			
			if ($row["todoeldia"] == 1) {
				$todoeldia = "checked";
			}
		}
	} else {
		$id = "";
		$titulo = "";
		//$inicio="";
		//$fin="";
		$descripcion = "";
		$todoeldia = "";
	}

	$frm = '
			<form id="frm-evento">
				<input type="hidden" name="id" id="id" value="' . $id . '">
				<table width="100%">
					<tr>
						<td>
							<label>*Cliente</label>
						</td>
						<td>
							<select id="cliente" name="cliente" style="width:250px;" onChange="ReloadSubcliente(this.value);" class="nminputselect">';
								$frm .= '<option value="">-Seleccione-</option>';
								
								$qc = mysql_query("Select id, nombre from comun_cliente");
								
								while ($rowc = mysql_fetch_array($qc)) {
									if ($cliente == $rowc["id"]) {
										$frm .= '<option selected value="' . $rowc["id"] . '">' . utf8_encode($rowc["nombre"]) . '</option>';
									} else {
										$frm .= '<option value="' . $rowc["id"] . '">' . utf8_encode($rowc["nombre"]) . '</option>';
									}
								}
					$frm .= '</select>
						<td>
					</tr>
					<tr>
						<td>
							<label>*Titulo</label>
						</td>
						<td>
							<input name="titulo" maxlength="50" id="titulo" type="text" value="' . $titulo . '" class="nminputtext">
						</td>
					</tr>
					<tr>
						<td>
							<label>*Inicio</label>
						</td>
						<td>
							<input name="inicio" readonly  id="inicio" type="text" value="' . $inicio . '" class="nminputtext">
						</td>
					</tr>
					<tr>
						<td>
							<label>*Fin</label>
						</td>
						<td>
							<input name="fin" id="fin" readonly  type="text" value="' . $fin . '" class="form-control">
						</td>
					</tr>';
					
					$frm .= '<script type="text/javascript">
									console.log('.json_encode($_COOKIE).');
								</script>';
					print_r($_COOKIE);
				// Si viene desde restaurantes agrega un tr con el listado de las mesas
					if ($_COOKIE['desde_foodware']) {
						$frm .= '<select id="mesa" class="nminputselect">';
								$frm .= '<option value="">- Cliente -</option>';
								
								$qc = mysql_query("Select id, nombre from comun_cliente");
								
								while ($rowc = mysql_fetch_array($qc)) {
									if ($cliente == $rowc["id"]) {
										$frm .= '<option selected value="' . $rowc["id"] . '">' . utf8_encode($rowc["nombre"]) . '</option>';
									} else {
										$frm .= '<option value="' . $rowc["id"] . '">' . utf8_encode($rowc["nombre"]) . '</option>';
									}
								}
						$frm .= '</select>';
						
					// Cambiamos los select por select con buscador
						$frm .= '<script type="text/javascript">
									$objeto=[];
									$objeto[0]="mesa";
									
								// Mandamos llamar la funcion que crea el buscador
									select_buscador($objeto);
								</script>';
						
					}else{
						$frm .= '<tr>
									<td>
										<label>Todo el dia</label>
									</td>
									<td>
										<input name="todoeldia" ' . $todoeldia . ' id="todoeldia" type="checkbox">
									</td>
								</tr>';
						
					}

			$frm .= '
					<tr>
						<td>
							<label>Descripción</label>
						</td>
						<td>
							<textarea rows="6" maxlength="500" name="descripcion" id="descripcion" class="nminputtextarea">' . $descripcion . '</textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Sub-cliente</label>
						</td>
						<td>
							<span id="loadgrupos">
								<select id="grupo" name="grupo" class="nminputselect" style="width:180px">';
									$frm .= '<option value="">-Seleccione-</option>';
								
									if (is_numeric($grupo)) {
										$qc = mysql_query("Select id, nombre from agenda_grupo where activo=1 and id=" . $grupo);
										while ($rowc = mysql_fetch_array($qc)) {
											if ($grupo == $rowc["id"]) {
												$frm .= '<option selected value="' . $rowc["id"] . '">' . $rowc["nombre"] . '</option>';
											} else {
												$frm .= '<option value="' . $rowc["id"] . '">' . $rowc["nombre"] . '</option>';
											}
										}
									}
						$frm .= '</select>
							</span>
							<input type="button" id="addgrupo" value="+" class="add nminputbutton" onClick="addgrup();" >
							<input type="button" id="deletegrupo" value="-" class="add nminputbutton" onClick="deletegrup();" >
						</td>
					</tr>
				</table>
			</form>';
	echo $frm;
}
///////////////////////////////////////////////////////////////////

function LoadExpediente($cliente) {
	date_default_timezone_set('America/Mexico_City');
	$fechaHoractual = date("Y-m-d H:i");

	$exp = '<div class="subaccordion">';

	$qsub = mysql_query("Select ag.id id,ag.nombre nombre from agenda_grupo ag INNER JOIN agenda a ON a.idGrupo=ag.id where ag.activo=1 and ag.idCliente=" . $cliente . "  group by ag.id  order by ag.nombre asc");

	if (mysql_num_rows($qsub) > 0) {
		while ($rowsub = mysql_fetch_array($qsub)) {
			$exp .= '<h2 style="font-weight:bold;">' . $rowsub["nombre"] . '</h2>';
			$exp .= "<div>";

			$exp .= '<div class="accordion">';

			$q = mysql_query("Select * from agenda where idGrupo=" . $rowsub["id"] . " and activo=1 and idCliente=" . $cliente . " order by inicio desc");
			
			if (mysql_num_rows($q) > 0) {$i = 0;
				while ($row = mysql_fetch_array($q)) {
					$exp .= '<h2>' . FechaFormateada(date('U', strtotime($row["inicio"]))) . '</h2>
						<div>
						<h3>' . $row["titulo"] . '</h3>
						<p style="float:left;">' . $row["descripcion"] . '</p><br><p>' . substr(HoraFormateada($row["inicio"]), 10) . ' - ' . substr(HoraFormateada($row["fin"]), 10) . '</p>';

					$qf = mysql_query("SELECT e.id,e.nombre FROM agenda_expediente ae,expediente e WHERE ae.idExpediente=e.id and ae.idAgenda=" . $row["id"] . "  ");
					if (mysql_num_rows($qf) > 0) {
						$exp .= "<p style='float:left;'><strong style='float:left;'>Archivos adjuntos:</strong><br>";
						while ($rowf = mysql_fetch_array($qf)) {
							$exp .= "<a class='fancybox' style='float:left;' href='expedientes/" . $rowf["nombre"] . "'>" . $rowf["nombre"] . "</a><br>";
						}
						$exp .= "</p>";
					}
					//if($i==0)
					if ($row["fin"] > $fechaHoractual) {
						$exp .= '<p><input class="btn btn-primary" type="button" value="Actualizar descripción" onclick="Adddescription(' . $row["id"] . ');"></p>';
					}
					$exp .= '</div>';
					$i++;
				}
			} else {
				$exp .= '<h2>No existen citas registradas para este usuario</h2>
					<div>
					<p style="float:left;"></p>
					</div>';
			}
			
			$exp .= '</div>';

			$exp .= '</div>';
		}
	} else {
		//var_dump($exp);
		//	var_dump()

		/*i*/
		$exp = '<div class="accordion">';

		$q = mysql_query("Select * from agenda where activo=1 and idCliente=" . $cliente . " order by inicio desc");
		if (mysql_num_rows($q) > 0) {$i = 0;
			while ($row = mysql_fetch_array($q)) {
				$exp .= '<h2>' . FechaFormateada(date('U', strtotime($row["inicio"]))) . '</h2>
					<div>
					<h3>' . $row["titulo"] . '</h3>
					<p style="float:left;">' . $row["descripcion"] . '</p><br><p>' . substr(HoraFormateada($row["inicio"]), 10) . ' - ' . substr(HoraFormateada($row["fin"]), 10) . '</p>';

				$qf = mysql_query("SELECT e.id,e.nombre FROM agenda_expediente ae,expediente e WHERE ae.idExpediente=e.id and ae.idAgenda=" . $row["id"] . "  ");
				
				if (mysql_num_rows($qf) > 0) {
					$exp .= "<p style='float:left;'><strong style='float:left;'>Archivos adjuntos:</strong><br>";
					while ($rowf = mysql_fetch_array($qf)) {
						$exp .= "<a class='fancybox' style='float:left;' href='expedientes/" . $rowf["nombre"] . "'>" . $rowf["nombre"] . "</a><br>";
					}
					$exp .= "</p>";
				}
				
				//if($i==0)
				if ($row["fin"] > $fechaHoractual) {
					$exp .= '<p><input type="button" class="nminputbutton" value="Actualizar descripción" onclick="Adddescription(' . $row["id"] . ');"></p>';
				}
				
				$exp .= '</div>';
				$i++;
			}
		} else {
			$exp .= '<h2>No existen citas registradas para este usuario</h2>
				<div>
				<p style="float:left;"></p>
				</div>';
		}

		$exp .= '</div>';

		/*f*/
	}

	return $exp;
}

function FechaFormateada($FechaStamp) {//FechaFormateada(date('U',strtotime($fecha)));
	$ano = date('Y', $FechaStamp);
	//<-- Año
	$mes = date('m', $FechaStamp);
	//<-- número de mes (01-31)
	$dia = date('d', $FechaStamp);
	//<-- Día del mes (1-31)
	$dialetra = date('w', $FechaStamp);
	//Día de la semana(0-7)
	$hora = date('H', $FechaStamp);
	$minutos = date('i', $FechaStamp);

	switch($dialetra) {
		case 0 :
			$dialetra = "Domingo";
			break;
		case 1 :
			$dialetra = "Lunes";
			break;
		case 2 :
			$dialetra = "Martes";
			break;
		case 3 :
			$dialetra = "Miercoles";
			break;
		case 4 :
			$dialetra = "Jueves";
			break;
		case 5 :
			$dialetra = "Viernes";
			break;
		case 6 :
			$dialetra = "Sabado";
			break;
	}
	
	switch($mes) {
		case '01' :
			$mesletra = "Ene";
			break;
		case '02' :
			$mesletra = "Feb";
			break;
		case '03' :
			$mesletra = "Mar";
			break;
		case '04' :
			$mesletra = "Abr";
			break;
		case '05' :
			$mesletra = "May";
			break;
		case '06' :
			$mesletra = "Jun";
			break;
		case '07' :
			$mesletra = "Jul";
			break;
		case '08' :
			$mesletra = "Ago";
			break;
		case '09' :
			$mesletra = "Sep";
			break;
		case '10' :
			$mesletra = "Oct";
			break;
		case '11' :
			$mesletra = "Nov";
			break;
		case '12' :
			$mesletra = "Dic";
			break;
	}
	
	return "$dia/$mesletra/$ano ";
}

function HoraFormateada($fecha) {
	$subfecha = explode(":", $fecha);
	$hora = $subfecha[0];
	$minutos = $subfecha[1];
	$horario = "am";
	
	if ($hora > 12) {
		if ($hora == 13) {
			$hora = "1";
		}
		if ($hora == 14) {
			$hora = "2";
		}
		if ($hora == 15) {
			$hora = "3";
		}
		if ($hora == 16) {
			$hora = "4";
		}
		if ($hora == 17) {
			$hora = "5";
		}
		if ($hora == 18) {
			$hora = "6";
		}
		if ($hora == 19) {
			$hora = "7";
		}
		if ($hora == 20) {
			$hora = "8";
		}
		if ($hora == 21) {
			$hora = "9";
		}
		if ($hora == 22) {
			$hora = "10";
		}
		if ($hora == 23) {
			$hora = "11";
		}
		if ($hora == 24) {
			$hora = "12";
		}
		
		$horario = "pm";
	}

	return $hora . ":" . $minutos . " " . $horario;
}
?>