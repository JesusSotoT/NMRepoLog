<?php
include_once ("../../../netwarelog/catalog/conexionbd.php");
require("models/connection_sqli.php"); // funciones mySQLi

switch($_POST["funcion"]) {
	case "form" :
		formulario();
		break;
		
	case "modal" :
		modal();
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
		
	case "guardar_cliente" :
		$result=guardar_cliente($_REQUEST);
		print_r($result);
		
		break;
	case "listar_cliente" :
		echo json_encode(listar_cliente($_REQUEST));
		break;
}
/////////////////////////////////////////////////////////////////////////
function actualizardescripcion($id, $des) {
	$query = "UPDATE `com_reservaciones` SET `descripcion` = '" . $des . "' WHERE `com_reservaciones`.`id` =" . $id . ";";
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
		$query = "INSERT INTO `com_reservaciones_grupo` (`id`, `nombre`,`idCliente`) VALUES (NULL, '" . $nombre . "'," . $cliente . ");";
		$resq = mysql_query($query);
		echo realoadGrupo($cliente);
	} catch(Exception $e) {
		echo 2;
	}
}

/////////////////////////////////////////////////////////////////
function modal() { ?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<form id="modal_cliente_reservaciones">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingOne" align="left">
			<!-- Nombre y telefono -->
				<div class="row">
				<!-- Nombre -->
					<div class="col-md-5">
						<div class="input-group">
							<label>Nombre: </label>
							<input id="nombre" required="1" type="text" class="form-control" required="1" placeholder="Pedro paramo">
						</div>
					</div>
					
				<!-- Telefono -->
					<div class="col-md-5">
						<div class="input-group">
							<label>Telefono: </label>
							<input id="tel" type="number" class="form-control" required="1" placeholder="0123456789">
						</div>
					</div>
					
				<!-- Mas detalles -->
					<div class="col-md-2">
						<br />
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> 
							<i style="font-size: 15px" class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
						</a>
					</div>
				</div>
			</div>
			<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;" align="left">
				<div class="panel-body">
					<!-- Direccion y Num. Ext -->
					<div class="row">
						<!-- Direccion -->
						<div class="col-md-6">
							<div class="input-group">
								<label>Direccion: </label>
								<input id="direccion" type="text" class="form-control" placeholder="Algun lugar">
							</div>
						</div>

						<!-- Num. Ext. -->
						<div class="col-md-6">
							<div class="input-group">
								<label>Num. Ext.: </label>
								<input id="num_ext" type="number" class="form-control" placeholder="0000">
							</div>
						</div>
					</div>
					<br />

					<!-- Num. Int y E-mail -->
					<div class="row">
						<!-- Num. int. -->
						<div class="col-md-6">
							<div class="input-group">
								<label>Num. int.: </label>
								<input id="num_int" type="number" class="form-control" placeholder="0000">
							</div>
						</div>
						<!-- E-mail -->
						<div class="col-md-6">
							<div class="input-group">
								<label>E-mail: </label>
								<input id="mail" type="email" class="form-control" placeholder="ejemplo@ejem.com">
							</div>
						</div>
					</div>

					<!-- Colonia y codigo postal -->
					<div class="row">
						<!-- Colonia -->
						<div class="col-md-6">
							<div class="input-group">
								<label>Colonia: </label>
								<input id="colonia" type="text" class="form-control" placeholder="Colonia">
							</div>
						</div>

						<!-- Codigo postal -->
						<div class="col-md-6">
							<div class="input-group">
								<label>CP: </label>
								<input id="cp" type="number" maxlength="5" max="99999" class="form-control" placeholder="00000">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- FIN panel-collapse collapse -->
		</div>
	<!-- FIN panel panel-default -->
	</form>
<!-- FIN FORM -->
</div>
<!-- FIN DIV ACCORDION -->
<?php }

/////////////////////////////////////////////////////////////////
function realoadGrupo($cliente) {
	$frm = '<select id="grupo" name="grupo" class="nminputselect">';
	$frm .= '<option value="">-Seleccione-</option>';
	
	if (is_numeric($cliente)) {
		$qc = mysql_query("Select id, nombre from com_reservaciones_grupo where activo=1 and idCliente=" . $cliente);
		
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
		$q = mysql_query("Update com_reservaciones_grupo set activo=0 where id=" . $id);

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
		
	// Formatea la fecha y la hora
		$_POST["inicio"]=str_replace('T', ' ', $_POST["inicio"]).':00';
		$_POST["fin"]=str_replace('T', ' ', $_POST["fin"]).':00';
		$fecha_entradai = strtotime(str_replace($array_replace, ":00", $_POST["inicio"]));
		$fecha_entradaf = strtotime(str_replace($array_replace, ":00", $_POST["fin"]));

		if ($fecha_entradai >= $fecha_entradaf) {
			echo 5;
		} elseif ($fecha_actual > $fecha_entradai) {
			echo 4;
		} else {
		// Verifica si la fecha de inicio esta disponible
			// (consulta si la fecha de inicio se encuentra entre alguna fecha de inicio o fin registrada )
			$sql="	SELECT  
						id 
					FROM 
						com_reservaciones 
					WHERE 
						activo=1 
					AND mesa=".$_POST["mesa"]."
					AND '" . 
							str_replace($array_replace, ":00", $_POST["inicio"]) . "' 
						BETWEEN 
							inicio 
						AND 
							fin";
							
			$qdisponiblei = mysql_query($sql);

		// Verifica si la fecha de fin esta disponible
			// (consulta si la fecha de fin se encuentra entre alguna fecha de inicio o fin registrada )
			$sql="	SELECT  
						id 
					FROM 
						com_reservaciones 
					WHERE 
						activo=1 
					AND 
						mesa=".$_POST["mesa"]."
					AND '" . 
							str_replace($array_replace, ":00", $_POST["fin"]) . "' 
						BETWEEN 
							inicio 
						AND 
							fin";
			$qdisponiblef = mysql_query($sql);
			
			$iniciocorto = explode(" ", str_replace($array_replace, ":00", $_POST["inicio"]));
			$fincorto = explode(" ", str_replace($array_replace, ":00", $_POST["fin"]));

		// Consulta si hay algun registro que ocupe todo el dia
			$sql="	SELECT  
						id 
					FROM 
						com_reservaciones 
					WHERE 
							activo=1 
						AND	(
								fin='" . $fincorto[0] . " 00:00:00' 
							AND 
								todoeldia=1
						)OR (
								inicio='" . $iniciocorto[0] . " 00:00:00' 
							AND 
								todoeldia=1
						)";
							
			$qdisponiblet = mysql_query($sql);
			
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
			
			// Si es un registro nuevo, entra aqui
				if (empty($_POST["id"])) {
				// Valida si tiene un telefono
					if (empty($_POST["tel"])) {
						$tel = "NULL";
					} else {
						$tel = $_POST["tel"];
					}
				
				// Obtiene el color del cliente
					$samecolorquery = mysql_query("Select color from com_reservaciones where idCliente=" . $_POST["cliente"]);
				
				// Si existe el color o colores lo guarda en un array
					if (mysql_num_rows($samecolorquery) > 0) {
						$rowsamecolor = mysql_fetch_array($samecolorquery);
						$color = $rowsamecolor["color"];
				// Si no existe le agrega un color aleatoriao
					} else {
						$color = randomColor();
					}
					
				// Inserta un registro en la BD
					$query = "	INSERT INTO 
								`com_reservaciones` (
									`id`, 
									`titulo`, 
									`inicio`, 
									`fin`, 
									`todoeldia`, 
									`descripcion`, 
									`color`, 
									`tel`, 
									`idCliente`, 
									`mesa`, 
									`num_personas`
								) VALUES (
									NULL, 
									'" . $_POST["titulo"] . "', 
									'" . $fecha_inicio . "', 
									'" . $fecha_fin . "', 
									'" . $todoeldia . "', 
									'" . $_POST["descripcion"] . "', 
									'" . $color . "', 
									" . $tel . ",
									" . $_POST["cliente"] . ",
									" . $_POST["mesa"] . ",
									" . $_POST["num_personas"] . "
								);";
								
			// Si es un registro existente actualiza
				} else {
				// Valida si existe algun telefono
					if (empty($_POST["tel"])) {
						$tel = "NULL";
					} else {
						$tel = $_POST["tel"];
					}
				
				// Actualiza el la reservacion
					$query = "	UPDATE 
									`com_reservaciones` 
								SET  
									`idCliente` = " . $_POST["cliente"] . ", 
									`tel` = " . $tel . ", 
									`titulo` = '" . $_POST["titulo"] ."',
									`descripcion` = '" . $_POST["descripcion"] . "' ,
									`inicio` = '" . $fecha_inicio . "' ,
									`fin` = '" . $fecha_fin . "' , 
									`todoeldia` = '" . $todoeldia . "' , 
									`mesa` = " . $_POST["mesa"] . " , 
									`num_personas` = " . $_POST["num_personas"] . " 
								WHERE 
									`com_reservaciones`.`id` =" . $_POST["id"] . ";";
					echo "Entra a update".$query;
				}
				
				try {
					mysql_query($query);
					echo 1;
				} catch(Exception $e) {
					echo 2;
				}
			}
		}
	}
///////////////////////////////////////////////////////////////////
function eliminar($id) {
	try {
		$q = mysql_query("Update com_reservaciones set activo=2 where id=" . $id);

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

	$colorquery = mysql_query("Select color from com_reservaciones where color='" . $str . "'");
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
		$mesa= $_POST["mesa"];
		$num_personas= $_POST["num_personas"];
		//$f_ini=date('Y-m-d').'T'.date('H:i');  
		$f_ini=$_POST["inicio"].'T'.date('H:i'); 
	
		//$f_fin = date('Y-m-d H:i');
		$f_fin = strtotime ( '+3 hour' , strtotime ( $f_ini ) ) ;
		$f_fin = date ( 'Y-m-d H:i' , $f_fin );
		$f_fin=str_replace(' ', 'T', $f_fin);
		
		if (strcmp($_POST["todoeldia"], "true") == 0) {
			$todoeldia = "checked";
		}

		if (is_numeric($_POST["id"])) {
			$sql="	SELECT 
						* 
					FROM 
						com_reservaciones 
					WHERE 
						id=" . $_POST["id"];
			
			$query = mysql_query($sql);
			
			$row = mysql_fetch_array($query);
			$inicio = $row["inicio"];
			$fin = $row["fin"];
			$cliente = $row["idCliente"];
			$tel = $row["tel"];
			$mesa = $row["mesa"];
			$num_personas= $row["num_personas"];
			
			$f_fin=str_replace(' ', 'T', $fin);
			$f_ini=str_replace(' ', 'T', $inicio);
			
			if ($row["todoeldia"] == 1) {
				$todoeldia = "checked";
			}
		}
	} else {
		$id = "";
		$titulo = "";
		$descripcion = "";
		$todoeldia = "";
	} ?>
	
	<form id="frm-evento">
		<input type="hidden" name="id" id="id" value="<?php echo $id ?>">
	<!-- Cliente -->
		<div class="row">
			<div class="col-xs-6">
				<label>*Cliente</label>
			</div>
			<div class="col-xs-4" align="left">
				<select id="cliente" name="cliente" onChange="ReloadSubcliente(this.value);">
					<option value="">-Seleccione-</option><?php
					
					$qc = mysql_query("Select id, nombre from comun_cliente");
								
					while ($rowc = mysql_fetch_array($qc)) {
						if ($cliente == $rowc["id"]) { ?>
							<option selected value="<?php echo $rowc["id"] ?>"><?php echo $rowc["nombre"] ?></option> <?php
						} else { ?>
							<option value="<?php echo $rowc["id"] ?>"><?php echo $rowc["nombre"] ?></option> <?php
						}
					} ?>
				</select>
			</div>
			
		<!-- Boton agregar cliente -->
			<div class="col-xs-2" align="left">
				<input type="button" value="+" class="add btn btn-success btn-xs" onClick="add_cliente();" >
			</div>
		</div><br />
	<!-- FIN Cliente -->
	
	<!-- Titulo -->
		<div class="row">
			<div class="col-xs-6">
				<label>*Titulo</label>
			</div>
			<div class="col-xs-6">
				<input name="titulo" maxlength="50" id="titulo" type="text" value="<?php echo $titulo ?>" class="form-control">
			</div>
		</div><br />
	<!-- FIN Titulo -->
	
	<!-- Inicio -->
		<div class="row">
			<div class="col-xs-6">
				<label>*Inicio</label>
			</div>
			<div class="col-xs-6">
				<!-- <input name="inicio" readonly id="inicio" type="text" value="<?php echo $inicio ?>" class="form-control"> -->
				<input id="inicio" type="datetime-local" value="<?php echo $f_ini ?>" class="form-control">
			</div>
		</div><br />
	<!-- FIN Inicio -->
	
	<!-- Fin -->
		<div class="row">
			<div class="col-xs-6">
				<label>*Fin</label>
			</div>
			<div class="col-xs-6">
				<!-- <input name="fin" readonly id="fin" type="text" value="<?php echo $fin ?>" class="form-control"> -->
				<input id="fin" type="datetime-local" value="<?php echo $f_fin ?>" class="form-control">
			</div>
		</div><br />
	<!-- FIN Fin --> <?php
	
			$sql="	SELECT 
						a.id_mesa mesa, 
						b.nombre area,
						a.personas, 
						a.tipo, 
						a.nombre, 
						a.domicilio, 
						if(GROUP_CONCAT(c.idmesa) is NULL,'',GROUP_CONCAT(c.idmesa)) 
							idmesas, 
						if(GROUP_CONCAT(d.personas) is NULL,'',GROUP_CONCAT(d.personas)) 
							mpersonas, 
						if(e.id is NULL,0,e.id) 
							idcomanda 
					FROM 
						com_mesas a 
					LEFT JOIN 
							mrp_departamento b 
						ON 
							b.idDep=a.idDep 
					LEFT JOIN 
							com_union c 
						ON 
							c.idprincipal=a.id_mesa 
					LEFT JOIN 
							com_mesas d 
						ON 
							d.id_mesa=c.idmesa 
					LEFT JOIN 
							com_comandas e 
						ON 
							e.idmesa=a.id_mesa 
						AND 
							e.status=0 
					WHERE(
							a.id_mesa 
						NOT IN
							(select idmesa from com_union) 
						OR 
							a.id_mesa 
						IN
							(select idprincipal from com_union)	
					)
					AND 
						a.tipo=0 
					GROUP BY 
						a.id_mesa 
					ORDER BY 
						a.id_mesa asc";
			
			$mesas = mysql_query($sql); ?>
				
		<!-- Mesa -->
			<div class="row">
				<div class="col-xs-6">
					<label>*Mesa</label>
				</div>
				<div class="col-xs-6" align="left">
					<select id="mesa">
						<option value="">- Seleccionar -</option> <?php
						
						while ($mesas_array = mysql_fetch_array($mesas)) {
							
							if ($mesa == $mesas_array["mesa"]) { ?>
								<option selected value="<?php echo $mesas_array["mesa"] ?>"><?php echo '['.$mesas_array["mesa"].'] '.$mesas_array["nombre"].' ('.$mesas_array["area"].')' ?></option> <?php
							} else { ?>
								<option value="<?php echo $mesas_array["mesa"] ?>"><?php echo '['.$mesas_array["mesa"].'] '.$mesas_array["nombre"].' ('.$mesas_array["area"].')' ?></option> <?php
							}
						} ?>
					</select>
				</div>
			</div><br />
			
		<!-- Mesa -->
			<div class="row">
				<div class="col-xs-6">
					<label>*Num. Personas</label>
				</div>
				<div class="col-xs-6" align="left">
					<input id="num_personas" type="number" min="1" value="<?php echo $num_personas ?>" style="width: 50px"/>
				</div>
			</div><br />
		
	<!-- Descripción -->
		<div class="row">
			<div class="col-xs-6">
				<label>Descripción</label>
			</div>
			<div class="col-xs-6">
				<textarea rows="5" maxlength="500" name="descripcion" id="descripcion" class="form-control"><?php echo $descripcion ?></textarea>
			</div><br />
		</div><br />
	<!-- FIN Descripción -->
		
	<!-- Telefono -->
		<div class="row">
			<div class="col-xs-6">
				<label>Telefono</label>
			</div>
			<div class="col-xs-6" align="left">
				<input id="tel" type="number" value="<?php echo $tel ?>" placeholder="0123456789" style="width: 150px" />
			</div>
		</div><br />
	<!-- Telefono -->
	</form>
	
	<script type="text/javascript">
		$objeto=[];
		$objeto[0]="mesa";
		$objeto[1]="cliente";
		$objeto[2]="grupo";
		
	// Mandamos llamar la funcion que crea el buscador
		select_buscador($objeto);
	</script><?php
}
///////////////////////////////////////////////////////////////////

function LoadExpediente($cliente) {
	date_default_timezone_set('America/Mexico_City');
	$fechaHoractual = date("Y-m-d H:i");

	$exp = '<div class="subaccordion">';

	$qsub = mysql_query("Select ag.id id,ag.nombre nombre from com_reservaciones_grupo ag INNER JOIN com_reservaciones a ON a.idGrupo=ag.id where ag.activo=1 and ag.idCliente=" . $cliente . "  group by ag.id  order by ag.nombre asc");

	if (mysql_num_rows($qsub) > 0) {
		while ($rowsub = mysql_fetch_array($qsub)) {
			$exp .= '<h2 style="font-weight:bold;">' . $rowsub["nombre"] . '</h2>';
			$exp .= "<div>";

			$exp .= '<div class="accordion">';

			$q = mysql_query("Select * from com_reservaciones where idGrupo=" . $rowsub["id"] . " and activo=1 and idCliente=" . $cliente . " order by inicio desc");
			
			if (mysql_num_rows($q) > 0) {$i = 0;
				while ($row = mysql_fetch_array($q)) {
					$exp .= '<h2>' . FechaFormateada(date('U', strtotime($row["inicio"]))) . '</h2>
						<div>
						<h3>' . $row["titulo"] . '</h3>
						<p style="float:left;">' . $row["descripcion"] . '</p><br><p>' . substr(HoraFormateada($row["inicio"]), 10) . ' - ' . substr(HoraFormateada($row["fin"]), 10) . '</p>';

					$qf = mysql_query("SELECT e.id,e.nombre FROM com_reservaciones_expediente ae,expediente e WHERE ae.idExpediente=e.id and ae.idAgenda=" . $row["id"] . "  ");
					
					if (mysql_num_rows($qf) > 0) {
						$exp .= "<p style='float:left;'><strong style='float:left;'>Archivos adjuntos:</strong><br>";
						while ($rowf = mysql_fetch_array($qf)) {
							$exp .= "<a class='fancybox' style='float:left;' href='expedientes/" . $rowf["nombre"] . "'>" . $rowf["nombre"] . "</a><br>";
						}
						$exp .= "</p>";
					}
					
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

		$q = mysql_query("Select * from com_reservaciones where activo=1 and idCliente=" . $cliente . " order by inicio desc");
		if (mysql_num_rows($q) > 0) {$i = 0;
			while ($row = mysql_fetch_array($q)) {
				$exp .= '<h2>' . FechaFormateada(date('U', strtotime($row["inicio"]))) . '</h2>
					<div>
					<h3>' . $row["titulo"] . '</h3>
					<p style="float:left;">' . $row["descripcion"] . '</p><br><p>' . substr(HoraFormateada($row["inicio"]), 10) . ' - ' . substr(HoraFormateada($row["fin"]), 10) . '</p>';

				$qf = mysql_query("SELECT e.id,e.nombre FROM com_reservaciones_expediente ae,expediente e WHERE ae.idExpediente=e.id and ae.idAgenda=" . $row["id"] . "  ");
				
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


///////////////// ******** ---- 		guardar_cliente		------ ************ //////////////////
	//////// Agrega un cliente a la base de datos en la tabla comun_cliente
		// Como parametros puede recibir:
			// Campos del formulario:
				// -> Nombre, Direccion, Numero interios, Numero Exterior
				// -> Colonia, CP, estado, Municipio, E-mail, Tel
		
			function guardar_cliente($objeto){
				$sql="
					INSERT INTO 
						comun_cliente(
							nombre, 
							direccion, 
							colonia, 
							email, 
							celular, 
							cp, 
							idEstado, 
							idMunicipio, 
							rfc
						)VALUES(
							'".$objeto['nombre']."',
							'".$objeto['direccion']."',
							'".$objeto['colonia']."',
							'".$objeto['mail']."',
							'".$objeto['tel']."',
							'".$objeto['cp']."',
							'".$objeto['estado']."',
							'".$objeto['municipio']."',
							'XAXX010101000'
						);";
					
				$result = mysql_query($sql);
			
			// Si falla la consulta la regresa
				$result = ($result) ? $result : $sql ;
				
				return $result;
			}
			
///////////////// ******** ---- 	FIN	guardar_cliente		------ ************ ///////////////////////////////////


///////////////// ******** ---- 		listar_cliente		------ ************ //////////////////
	// Obtiene el cilente final y lo agrega en el select
		// Como parametro puede recibir:
			// id-> ID del cliente
		
			function listar_cliente($objeto){
				$condicion.=(!empty($objet['id']))?' AND id=\''.$objet['id'].'\'':'';
				
				$sql="	SELECT
							id, nombre, celular
						FROM
							comun_cliente
						ORDER BY
							id DESC;".
						$condicion;
					
				$result = mysql_query($sql);
			
			// Si falla la consulta la regresa
				if ($result) {
					$array=Array();
					
					while ($a = mysql_fetch_array($result)) {
						array_push($array,$a);
					}
					
					$result=$array;
				}else{
					$result=$sql;
				}
				
				return $result;
			}
			
///////////////// ******** ---- 		listar_cliente		------ ************ //////////////////
?>