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

	case "modal2" :
		modal2();
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
							<input id="Nombre" required="1" type="text" class="form-control" required="1" placeholder="Pedro paramo">
						</div>
					</div>
					
				<!-- Telefono -->
					<div class="col-md-5">
						<div class="input-group">
							<label>Telefono: </label>
							<input id="Telefono" type="number" class="form-control" required="1" placeholder="0123456789">
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
				<br />
				<div class="row">
				<!-- E-mail -->
					<div class="col-md-5">
						<div class="input-group">
							<label>E-mail: </label>
							<input id="E-mail" required="1" type="email" class="form-control" placeholder="ejemplo@ejem.com">
						</div>
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
						<!-- Colonia -->
						<div class="col-md-6">
							<div class="input-group">
								<label>Colonia: </label>
								<input id="colonia" type="text" class="form-control" placeholder="Colonia">
							</div>
						</div>

					</div>
					<br />
					<!-- Colonia y codigo postal -->
					<div class="row">
						
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
function modal2() { ?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<form id="modal_cliente_reservaciones2">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingOne" align="left">
			<!-- Nombre y telefono -->
				<div class="row">
				<!-- Nombre -->
					<div class="col-md-5">
						<div class="input-group">
							<label>Nombre: </label>
							<input id="Nombre_edi" required="1" type="text" class="form-control" required="1" placeholder="Pedro paramo">
						</div>
					</div>
					
				<!-- Telefono -->
					<div class="col-md-5">
						<div class="input-group">
							<label>Telefono: </label>
							<input id="Telefono_edi" type="number" class="form-control" required="1" placeholder="0123456789">
						</div>
					</div>
					
				
				</div>
				<br />
				<div class="row">
				<!-- E-mail -->
					<div class="col-md-5">
						<div class="input-group">
							<label>E-mail: </label>
							<input id="E-mail_edi" required="1" type="email" class="form-control" placeholder="ejemplo@ejem.com">
						</div>
					</div>
				</div>
			</div>
			
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
		$qc = mysql_query("Select id, nombre from com_reservaciones_grupo where activo='-1' and idCliente=" . $cliente);
		
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
		if (!empty($_POST["id"])) {
			$_POST["fecha"]=str_replace('T', ' ', $_POST["fecha"]);
		} else {
			$_POST["fecha"]=str_replace('T', ' ', $_POST["fecha"]).':00';
		}
		$fecha_entradai = strtotime(str_replace($array_replace, ":00", $_POST["fecha"]));

		if ($fecha_actual > $fecha_entradai) {
			echo 4;
		} else {
					$fecha = str_replace($array_replace, ":00", $_POST["fecha"]);
				
			
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
									`inicio`, 
									`descripcion`, 
									`color`, 
									`idCliente`, 
									`num_personas`,
									`activo`
								) VALUES (
									NULL, 
									'" . $fecha . "', 
									'" . $_POST["descripcion"] . "', 
									'" . $color . "', 
									" . $_POST["cliente"] . ",
									" . $_POST["num_personas"] . ",
									'-1'
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
									`descripcion` = '" . $_POST["descripcion"] . "' ,
									`inicio` = '" . $fecha . "' ,
									`num_personas` = " . $_POST["num_personas"] . " 
								WHERE 
									`com_reservaciones`.`id` =" . $_POST["id"] . ";";
					echo "Entra a update".$query;
				}
				
				try {
					mysql_query($query);
					$content = '<div style="width:100%; text-align: center;">';

					$qry = mysql_query("SELECT 
										logoempresa as logo
									FROM 
										organizaciones
									WHERE 
										1=1");
					if (mysql_num_rows($qry) > 0) {
						$logo = mysql_fetch_array($qry);
						
					}
					$src = '../../../netwarelog/archivos/1/organizaciones/' . $logo['logo'];
					$logo = (file_exists($src)) ? $src : '';
					
					$qry = mysql_query("SELECT * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio where idorganizacion=1");
					if (mysql_num_rows($qry) > 0) {
						$organizacion = mysql_fetch_array($qry);
						
					}

					$qry = mysql_query("SELECT 
									mp.idSuc AS id,
									mp.nombre, mp.tel_contacto,
									mp.direccion, m.municipio,
									e.estado
								FROM 
									administracion_usuarios au 
								INNER JOIN 
										mrp_sucursal mp 
									ON 
										mp.idSuc = au.idSuc
								INNER JOIN 
										municipios m 
									ON 
										mp.idMunicipio = m.idmunicipio 
								INNER JOIN 
										estados e 
									ON 
										mp.idEstado = m.idestado  
	 
								WHERE 
									au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
								LIMIT 1");
					if (mysql_num_rows($qry) > 0) {
						$datos_sucursal = mysql_fetch_array($qry);
						
					}

					$fecha2 = FechaFormateada(date('U', strtotime($fecha))).' '.substr(HoraFormateada($fecha), 10);
					
					if (!empty($logo)) { 
						$content = $content.'<div id="logo" style="text-align: center">
							<input type="image" src="'.$logo.'" style="width:90%; max-width: 350px;"/>
						</div>';
					}
					
					if(empty($_POST['id']))
						$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Confirmación de Reservación en '.$organizacion['nombreorganizacion'].'.</div>';
					else
						$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Cambio de Reservación en '.$organizacion['nombreorganizacion'].'.</div>';

					$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.utf8_decode($datos_sucursal['direccion']." ".$datos_sucursal['municipio'].", ".$datos_sucursal['estado']).'</div>';


					$content = $content.'<div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.$fecha2.'</div>';

					$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.$_POST["nombre"].'</div>';

					if($organizacion['paginaweb']!='-' || !empty($datos_sucursal['tel_contacto'])){
						$content = $content.'<br><br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Dudas o aclaraciones: ';
						if(!empty($datos_sucursal['tel_contacto'])){
							$content = $content.$datos_sucursal['tel_contacto'];
							if($organizacion['paginaweb']!='-'){
								$content = $content.' y ';
							}
						}
						if($organizacion['paginaweb']!='-'){
							$content = $content.$organizacion['paginaweb'];
						}
						$content = $content.'</div>';
					}
					
					$content = $content.'</div>';

					require_once('../../../modulos/phpmailer/sendMail.php');

					$mail->From = "mailer@netwarmonitor.com";
					$mail->FromName = $organizacion['nombreorganizacion'];
					if(empty($_POST['id']))
						$mail->Subject = "Confirmación de reservación";
					else
						$mail->Subject = "Cambio de reservación";
					$mail->AltBody = $organizacion['nombreorganizacion'];
					$mail->MsgHTML($content);
					$mail->AddAddress($_POST['correo'], $_POST['correo']);
					@$mail->Send();
					echo 1;
				} catch(Exception $e) {
					echo 2;
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
		$fecha = $_POST["fecha"];
		$descripcion = $_POST["descripcion"];
		$num_personas= $_POST["num_personas"];
		//$f_ini=date('Y-m-d').'T'.date('H:i');  
		
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
			$fecha = $row["inicio"];
			$fecha= str_replace(' ', 'T', $fecha);
			$cliente = $row["idCliente"];
			$num_personas= $row["num_personas"];
			$descripcion = $row["descripcion"];
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
			<div class="col-xs-5" style="text-align:right">
				<label>* Nombre del cliente</label>
			</div>
			<div class="col-xs-5" align="left">
				<select id="cliente" name="cliente" class="selectpicker" data-live-search="true" onChange="ReloadSubcliente(this.value);">
					<option value="">-Seleccione-</option><?php
					
					$qc = mysql_query("Select id, nombre, celular, email from comun_cliente");
								
					while ($rowc = mysql_fetch_array($qc)) {
						if ($cliente == $rowc["id"]) { ?>
							<option id="op-<?php echo $rowc["id"]?>" ed-nom='<?php echo $rowc["nombre"]?>' ed-tel='<?php echo $rowc["celular"]?>' ed-ema='<?php echo $rowc["email"]?>' selected value="<?php echo $rowc["id"] ?>"><?php echo $rowc["nombre"] ?></option> <?php
						} else { ?>
							<option id="op-<?php echo $rowc["id"]?>" ed-nom='<?php echo $rowc["nombre"]?>' ed-tel='<?php echo $rowc["celular"]?>' ed-ema='<?php echo $rowc["email"]?>' value="<?php echo $rowc["id"] ?>"><?php echo $rowc["nombre"] ?></option> <?php
						}
					} ?>
				</select>
			</div>
			
		<!-- Boton agregar cliente -->
			<div class="col-xs-1" align="left">
				<input type="button" value="+" class="add btn btn-success btn-xs" onClick="add_cliente();" >
			</div>
			<!-- Boton edita cliente -->
			<div class="col-xs-1" align="left">
				<button type="button" style="display: none;" class="edit btn btn-info btn-xs" onClick="edit_cliente(ed_id);" ><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>
			</div>
		</div><br />
	<!-- FIN Cliente -->

	<!-- Fecha -->
		<div class="row">
			<div class="col-xs-5" style="text-align:right">
				<label>* Fecha</label>
			</div>
			<div class="col-xs-7">
				<!-- <input name="fin" readonly id="fin" type="text" value="<?php echo $fin ?>" class="form-control"> -->
				<input id="fecha" class="form-control" type="datetime-local" value="<?php echo $fecha ?>" class="form-control">
				
			</div>
		</div><br>
	<!-- FIN Fecha --> 
			
		<!-- Num. Personas -->
			<div class="row">
				<div class="col-xs-5" style="text-align:right">
					<label>* Num. Personas</label>
				</div>
				<div class="col-xs-7" align="left">
					<input id="num_personas" type="number" min="1" value="<?php echo $num_personas ?>" class="form-control" style="width: 50px"/>
				</div>
			</div><br />
	<!-- FIN Num. Personas -->

	<!-- Descripción -->
		<div class="row">
			<div class="col-xs-5" style="text-align:right">
				<label>Descripción</label>
			</div>
			<div class="col-xs-7">
				<textarea rows="5" maxlength="500" class="form-control" name="descripcion" id="descripcion" class="form-control"><?php echo $descripcion ?></textarea>
			</div><br />
		</div><br />
	<!-- FIN Descripción -->

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

			$q = mysql_query("Select * from com_reservaciones where idGrupo=" . $rowsub["id"] . " and activo='-1' and idCliente=" . $cliente . " order by inicio desc");
			
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

		$q = mysql_query("Select * from com_reservaciones where activo='-1' and idCliente=" . $cliente . " order by inicio desc");
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
				if($objeto['tipo'] == 2){
					$sql = "UPDATE
								comun_cliente
							SET
								nombre = '".$objeto['Nombre_edi']."', 
								celular = '".$objeto['Telefono_edi']."', 
								email = '".$objeto['E-mail_edi']."' 
							WHERE id = ".$objeto['id_cli'];
				} else {
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
								'".$objeto['Nombre']."',
								'".$objeto['direccion']."',
								'".$objeto['colonia']."',
								'".$objeto['E-mail']."',
								'".$objeto['Telefono']."',
								'".$objeto['cp']."',
								'".$objeto['estado']."',
								'".$objeto['municipio']."',
								'XAXX010101000'
							);";
				}
				$result = mysql_query($sql);
			
			// Si falla la consulta la regresa
				$result = ($result) ? $result : $sql ;
				if($objeto['tipo'] == 2){
					$arr = ['result' => $result, 'id_cli' => $objeto['id_cli']];
					$result = json_encode($arr);
				} else{
					$arr = ['result' => $result, 'id_cli' => mysql_insert_id()];
					$result = json_encode($arr);
				}

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
							id, nombre, celular, email
						FROM
							comun_cliente;".
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