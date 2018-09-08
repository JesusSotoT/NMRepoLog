<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/edu.php");

class Edu extends Common
{
	public $EduModel;

	function __construct()
	{
		$this->EduModel = new EduModel();
		$this->EduModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->EduModel->close();
	}

	function instancia()
	{
		//$instancia = "edu.netwarmonitor.com/htdocs/clientes/estudianteudg/webapp/netwarelog/accelog/index.php";
		//$instancia = "www.netwarmonitor.mx/clientes/appcontia/webapp/netwarelog/accelog/index.php";
		$instancia = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return explode("/",$instancia);
	}

	function index()
	{
		//INICIA VARIABLE DE SESSION SI ES EDU O ES CLIENTE
		$_SESSION['tipoinstancia'] = $this->EduModel->tipoinstancia();
		echo "<b style='color:white;'>".$_SESSION['tipoinstancia']."</b>";

		//INICIA VARIABLE DE SESSION SI ES EDU O ES CLIENTE
		if($_SESSION['tipoinstancia'] == 2)
		{		
			$instancia = $this->instancia();
			$inst = $instancia[3];
			$tipo = 'u';
			if($inst == "webapp")
			{
				$inst = $instancia[2];
				$tipo = 'n';
			}
			$instancia = $inst;
			//echo $instancia;
		
			//HACER PROCESO QUE TOME LA INSTANCIA
			$idprofesor = $this->EduModel->idprofesor($instancia);
			$lista_grupos = $this->EduModel->lista_grupos($idprofesor);
			
			$grupo = 999999;
			$ejercicio = date(Y);
			$periodo = 1;
			
			if(isset($_POST['grupo']))
				$grupo = $_POST['grupo'];
			
			if(isset($_POST['ejercicio']))
				$ejercicio = $_POST['ejercicio'];
			
			if(isset($_POST['periodo']))
				$periodo = $_POST['periodo'];

			$alumnos = $this->EduModel->alumnos($idprofesor,$grupo,$ejercicio,$periodo);
			$ej_act = date(Y);
			$ejercicios = "";
			for($i=$ej_act;$i>=$ej_act-3;$i--)
				$ejercicios .= "<option value='$i'>$i</option>";
			if($grupo == 999999)
				$grupo = 0;
			require('views/edu/lista.php');
		}
		else
		{
			echo "<b style='color:red;'>No tienes acceso a este modulo.</b>";
		}
	}

	function revisado()
	{
		echo $this->EduModel->revisado($_POST['idregistro']);
		
	}

	function listado_revisiones($idrelacion)
	{
		$lista = $this->EduModel->listado_revisiones($idrelacion);
		return $lista;
	}

	function cuentasEstudiante($db)
	{
		return $this->EduModel->cuentasEstudiante($db);
	}

	function polizasEstudiante($db,$ejercicio,$periodo)
	{
		return $this->EduModel->polizasEstudiante($db,$ejercicio,$periodo);
	}

	function b3()
	{
		$param = explode('--*--/*-',$_POST['datos']);
		$datos = $this->EduModel->b3($param[0],$param[1],$param[2],0,0,4,0);
		if($_GET['tipo'] == 'u')
			$titulo_al = "del alumno";

		if($_GET['tipo'] == 'n')
			$titulo_al = "de la instancia";
		$alumno = "Revisando B3 $titulo_al: <b style='color:#46b8da;'>".$param[3]."</b>";
		$ej = $param[1];
		$periodo = $this->nombre_mes($param[2]);
		if(intval($param[2])<=1)
			$periodoAnterior = 12;
		else
			$periodoAnterior = intval($param[2])-1;

		$periodoAnterior = $this->nombre_mes($periodoAnterior);

		$nomSucursal=$nomSegmento="Todas";

		require("views/reports/nifReporteB3.php");
	}

	function bg()
	{
		$param = explode('--*--/*-',$_POST['datos_bg']);
		$p13 = 0;
		if(intval($param[2]) == 13)
		{
			$param[2] = 12;
			$p13 = 1;
			$periSaldo = ' con Saldos';
		}
		
		$ej = $param[1];
		$periodo = $this->nombre_mes($param[2]);
		$nomSucursal=$nomSegmento="Todas";
		$datos = $this->EduModel->balanceGeneralReporteDemas($param[0],$param[1],$param[2],0,0,1,'m',0,$p13,0,0);
		$activos = $this->EduModel->balanceGeneralReporteActivo($param[0],$param[1],$param[2],0,0,'m',0);
		if($_GET['tipo'] == 'u')
			$titulo_al = "del alumno";

		if($_GET['tipo'] == 'n')
			$titulo_al = "de la instancia";

		$alumno = "Revisando Balanza General $titulo_al: <b style='color:#46b8da;'>".$param[3]."</b>";
		
		require("views/reports/balanceGeneralReporte.php");
	}

	function er()
	{
		$param = explode('--*--/*-',$_POST['datos_er']);
		$p13 = 0;
		if(intval($param[2]) == 13)
		{
			$param[2] = 12;
			$p13 = 1;
			$periSaldo = ' con Saldos';
		}
		
		$ej = $param[1];
		$periodo = $this->nombre_mes($param[2]);
		$nomSucursal=$nomSegmento="Todas";
		$datos = $this->EduModel->balanceGeneralReporteDemas($param[0],$param[1],$param[2],0,0,0,'m',0,$p13,0,0);
		if($_GET['tipo'] == 'u')
			$titulo_al = "del alumno";

		if($_GET['tipo'] == 'n')
			$titulo_al = "de la instancia";
		$alumno = "Revisando Estado de Resultados $titulo_al: <b style='color:#46b8da;'>".$param[3]."</b>";
		require("views/reports/estadoResultadosReporte.php");					
	}

	function lista_users()
	{
		$universidades = $this->EduModel->listaUniversidades();
		require('views/edu/panel_consultor.php');
	}

	function lista_panel()
	{
		$lista = $this->EduModel->listaUsers($_POST);
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			array_push($datos,array(
				'id' => $l->id,
				'razon' => utf8_encode($l->razon),
				'nombre' => utf8_encode($l->nombre),
				'correo' => utf8_encode("<a href='javascript:abrir_modal_mail($l->id)' id='link-$l->id'>".$l->correo."</a>"),
				'telefono' => utf8_encode($l->telefono),
				'giro' => utf8_encode($l->giro),
				'instancia' => utf8_encode($l->instancia),
				'usuario_master' => utf8_encode($l->usuario_master),
				'pwd_master' => utf8_encode($l->pwd_master),
				'profesor' => utf8_encode($l->profesor)
				));
		}
		echo json_encode($datos);
	}

	function lista_profes()
	{
		$lista = $this->EduModel->lista_users($_POST['univ']);
	}

	function lista_todos_grupos()
	{
		$lista = $this->EduModel->lista_todos_grupos($_POST['univ']);
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			array_push($datos,array(
				'id' => $l->idgrupo,
				'nombre' => utf8_encode($l->descripcion),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-grupo-modal-sm' onclick='modificar_grupo($l->idgrupo)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => "<button class='btn btn-danger btn-sm' onclick='eliminar_grupo($l->idgrupo)'>Eliminar <span class='glyphicon glyphicon-delete'></span></button>"
				));
		}
		echo json_encode($datos);
	}

	function lista_grupos_select()
	{
		$lista = $this->EduModel->lista_todos_grupos($_POST['univ']);
		$select="<option value='0'>Ninguno</option>";
		while($l = $lista->fetch_object())
		{
			$select .= "<option value='$l->idgrupo'>$l->descripcion</option>";
		}
		echo $select;

	}

	function lista_todos_relaciones()
	{
		$lista = $this->EduModel->lista_todos_relaciones($_POST['univ'],$_POST['grupo']);
		$datos=array(); 
		while($l = $lista->fetch_object())
		{
			array_push($datos,array(
				'id' => $l->id,
				'profesor' => utf8_encode($l->profesor),
				'alumno' => utf8_encode($l->alumno),
				'grupo' => utf8_encode($l->grupo),
				'mod' => "<button class='btn btn-default btn-sm' data-toggle='modal' data-target='.bs-relaciones-modal-sm' onclick='modificar_rel($l->id)'>Modificar <span class='glyphicon glyphicon-edit'></span></button>",
				'elim' => '<input type="checkbox" class="form-control" data-id="'.$l->id.'">'
				));
		}
		echo json_encode($datos);
	}

	function guarda_grupo()
	{
		echo $this->EduModel->guarda_grupo($_POST);
	}

	function profe_default()
	{
		echo $this->EduModel->profe_default($_POST['grupo']);
	}


	function eliminar_grupo()
	{
		$this->EduModel->eliminar_grupo($_POST['id']);
	}

	function eliminar_relacion(){
		foreach ($_POST['ids'] as $instancia => $id) {
			$Result[] = $this->EduModel->eliminar_relacion($id);
		}
	}

	function datos_grupo()
	{

		$datos = $this->EduModel->datos_grupo($_POST['id']);
		$datos = $datos->fetch_assoc();
		echo $datos['idgrupo']."**//**".$datos['descripcion'];
	}

	function datos_user()
	{
		echo $this->EduModel->datos_user($_POST['instancia']);
	}

	function guarda_rel()
	{
		echo $this->EduModel->guarda_rel($_POST);
	}

	function panel_inst()
	{
		$instancia = $this->instancia();
		$inst = $instancia[3];
		if($inst == "webapp")
			$inst = $instancia[2];

		$instancia = $inst;
		
		$lista = $this->EduModel->traerListaInstancias($this->EduModel->idprofesor($instancia),$_SESSION["accelog_idempleado"]);
		require('views/edu/panel_inst.php');
	}


	function tipo_inst()
	{
		echo $this->EduModel->tipoinstancia();
	}

	function cambia_session()
	{
		$instancia = $this->instancia();
		$path = "/htdocs/clientes/$instancia[3]";
		if($instancia[3] == "webapp")
			$path = "/clientes/$instancia[2]";
		
		if($_POST['inst_lig'] != '0')
		{
			//Antes de asignar a la session verificar que la instancia este vinculada al principal
			setcookie('inst_lig',$_POST['inst_lig'],strtotime( '+2 days' ),$path,$_SERVER[HTTP_HOST]);
			echo $_SERVER[HTTP_HOST].$path;
		}
		else
		{
			//reinicia la cookie
			setcookie ("inst_lig", "", time() - 3600,$path,$_SERVER[HTTP_HOST]);
			echo 2;
		}	
		
	}

	function rel_user_inst()
	{
		$instancia = $this->instancia();
		$inst = $instancia[3];
		if($inst == "webapp")
			$inst = $instancia[2];

		$instancia = $inst;
		$id_empleado = $_SESSION["accelog_idempleado"];
		//$id_empleado = 2;
		if(intval($id_empleado) <= 2)
		{
			$lista_empleados = $this->EduModel->lista_empleados();
			$lista_instancias = $this->EduModel->traerListaInstancias($this->EduModel->idprofesor($instancia),$id_empleado);
			require('views/edu/rel_user_inst.php');
		}
		else
			echo "<b style='color:red;'>No tienes acceso a este modulo.</b>";
	}

	function get_relaciones()
	{
		$instancia = $this->instancia();
		$inst = $instancia[3];
		if($inst == "webapp")
			$inst = $instancia[2];
		$instancia = $inst;
		$rels = $this->EduModel->traerListaInstancias($this->EduModel->idprofesor($instancia),$_POST['empleado']);
		$tabla = '';
		while($r = $rels->fetch_assoc())
		{
			$elimina = 'No se elimina';
			if(intval($r['id']))
				$elimina = "<a href='javascript:elimina(".$r['id'].")'><img src='images/eliminado.png'></a>";
			$tabla .= "<tr><td>".$r['instancia']."</td><td>$elimina</td></tr>";
		}
		echo $tabla;
	}

	function guarda_rel_emp()
	{
		$relaciones = $this->EduModel->valida_rel_emp($_POST['empleado'],$_POST['inst_rel']);
		$relaciones = explode("**//**",$relaciones);
		if(!intval($relaciones[0]))
			echo $this->EduModel->guarda_rel_emp($_POST['empleado'],$_POST['inst_rel']);
		else
		{
			echo "Ya existen las siguientes relaciones con este usuario: ".$relaciones[1];
		}
	}

	function elimina_rel_emp()
	{
		$this->EduModel->elimina_rel_emp($_POST['idrel']);
	}

	#Notificaciones ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞
	function capturar_notificacion(){
		if (isset($_GET['id'])) {
			$id = "data-id='".$_GET['id']."'";
			$titulo = "Editar Notificación";
			$boton = "Guardar";
		} else {
			$id = "";
			$titulo = "Agregar Notificación";
			$boton = "Agregar";
		}
		require('views/edu/agregar_notificacion.php');
	}

	function agregar_notificacion(){
		$id  = $_POST['id'];
		$pdf = $_FILES['archivo'];
		$_POST['pdf'] = $pdf['name'];
		
		$dir = '../cont/notificaciones';
		//Validamos que no este vacio y no tenga errores el archivo
		if ($error == UPLOAD_ERR_OK && $pdf['name'] != '') {
			$tmp_name = $pdf["tmp_name"];
			$name = basename($pdf["name"]);
			//Validamos que el pdf no exista en la carpeta
			if (file_exists("$dir/$name")) {
				$data['file'] = 'Un archivo con ese nombre ya existe';
			} 
			else {
				$se_agrego = move_uploaded_file($tmp_name, "$dir/$name");
				//Validamos que se haya subido correctamente
				if ($se_agrego) {
					$data['file'] = 'El archivo se subio correctamente';
				} else {
					$data['file'] = 'Error al subir el archivo';
				}
			}
		}

		date_default_timezone_set('America/Mexico_City');

		$_POST['fecha'] = date('Y-m-d h:i:s', time());
		if ($id <= 0) {
			$data['result'] = $this->EduModel->agregar_notificacion($_POST);			
		} else {
			$data['result'] = $this->EduModel->editar_notificacion($_POST);
			if ($data['result'] == 1) {
				$data['result'] = 2;
			}
		}
		echo json_encode($data);
	}

	function obtener_noticias(){
		$Result = $this->EduModel->obtener_noticias($_POST['tipo']);
		$cont = 1;
		$arr = array();
		if ($Result->num_rows != 0) {
			while($registro = $Result->fetch_assoc()){
				foreach ($registro as $campo => $valor) {
					if ($campo == 'producto') {
						$temp[$campo] = $this->obtener_nom_producto($valor);
					} else if($campo == 'estatus'){
						if ($valor == 0) {
							$temp[$campo] = "<span style='color:red'>Inactivo</span>";
						} else {
							$temp[$campo] = "<span style='color:green'>Activo</span>";
						}
					} else if($campo == 'mensaje'){
						$temp[$campo] = "<p style='text-align:left;word-break:break-all;'>$valor</p>";
					} else {
						$temp[$campo] = $valor;
					}
				}
				$arr[] = $temp;
			}
			$arr["error"] = 0;
			echo json_encode($arr);
		} else {
			$arr["error"] = "<p id='notfound'>No hay noticias.</p>";
			echo json_encode($arr);
		}
	}

	function obtener_pdf(){
		$nom_archivo = $_POST['archivo'];
		$path = $this->obtener_ruta_pdfs($nom_archivo);
		echo json_encode($path);
	}

	function ver_notificaciones(){
		require('views/edu/editar_notificaciones.php');
	}

	function obtener_notificacion(){
		$Result = $this->EduModel->obtener_notificacion($_POST['id']);
		$notificacion = $Result->fetch_assoc();
		
		$arr = array("titulo"=>$notificacion['titulo'], "mensaje"=>$notificacion['mensaje'], "activa"=>$notificacion['estatus'], "producto"=>$notificacion['producto'], "archivo"=>$notificacion['archivo']);

		echo(json_encode($arr));
	}

	function ultima_conexion($idalumno)
	{
		return $this->EduModel->ultima_conexion($idalumno);
	}

	function quitar_pdf(){
		$pdf['result'] = unlink($_POST['pdf']);
		$pdf['bd_result'] = $this->EduModel->quitar_pdf($_POST['id']);
		echo json_encode($pdf);
	}

	#Eventos ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞
	function capturar_evento(){
		if($_SESSION['tipoinstancia'] == 2){
			$instancia = $this->instancia();
			$inst = $instancia[3];
			$tipo = 'u';
			if($inst == "webapp")
			{
				$inst = $instancia[2];
				$tipo = 'n';
			}
			$instancia = $inst;

			if (isset($_GET['id'])){
				$id = "data-id='".$_GET['id']."'";
				$titulo = "Editar Evento";
				$boton = "Guardar";
			} else {
				$id = "";
				$titulo = "Agregar Evento";
				$boton = "Agregar";
			}
			require('views/edu/agregar_evento.php');
		} else {
			echo "<b style='color:red;'>No tienes acceso a este modulo.</b>";
		}	
	}

	function agregar_evento(){
		$arr;
		$id = $_POST['id'];
		$fecha_inicio = new DateTime($_POST['fecha_antes']);
		$fecha_fin = new DateTime($_POST['fecha_despues']);
		$_POST['fecha_inicio'] = $fecha_inicio->format('Y-m-d');
		$_POST['fecha_fin'] = $fecha_fin->format('Y-m-d');
		//Si id es igual a 0 usamos la función de agregar
		$arr['emails'] = $_POST['email'];

		if ($_POST['validar_correo'] != 0) {
			if (isset($_POST['asunto'])) {
				$arr['correo'] = $this->enviar_email($_POST['email'], $_POST['asunto'], '');
			} else {
				$arr['correo'] = $this->enviar_email($_POST['email'], '', $_POST['mensaje']);
			}
		}

		if ($id <= 0) {
			$Result = $this->EduModel->agregar_evento($_POST);
			//Validamos que el evento se haya agregado para agregar los usuarios al evento.
			if ($Result == 1) {
				$Result = $this->EduModel->agregar_usuarios_evento($_POST['lista_usuarios'], $_POST['instancia']);
			} else {
				$Result == 0; #Fallo al agregar.
			}		
		//De lo contrario usamos editar
		} else {
			$Result = $this->EduModel->editar_evento($_POST);
			if ($Result == 1) {
				$Result = $this->EduModel->agregar_usuarios_evento($_POST['lista_usuarios'], $_POST['instancia'], $id);
				if ($Result == 1) {
					$Result = 2; #Se edito correctamente
				} else {
					$Result == 0; #Fallo al editar.
				}	
			}
		}
		# 1: se agrego correctamente 
		# 2: Se edito correctamente 
		# else: No se pudo agregar/editar.
		$arr['resultado'] = $Result;
		echo json_encode($arr);
	}

	function obtener_eventos(){
		$eventos = array();
		$evt = array();
		$id = $_POST['id'];
		$instancia = $_POST['instancia'];

		$Result = $this->EduModel->obtener_eventos($id, 1, $instancia);
		if ($Result->num_rows != 0) {
			while ($registro = $Result->fetch_assoc()) {
				$evt['title']     = $registro['nombre'];
				$evt['startDate'] = $registro['fecha_inicio'];
				$evt['endDate']   = $registro['fecha_fin'];
				array_push($eventos, $evt);
			}
			echo(json_encode($eventos));
		} else {
			echo("No se encontraron registros.");
		}
	}

	function obtener_eventos_tabla(){
		$id = $_POST['id'];
		$Result = $this->EduModel->obtener_eventos($id, 1, $_POST['instancia']);
		if ($Result->num_rows != 0) {
			while($registro = $Result->fetch_assoc()){
				echo "<tr>";
				foreach ($registro as $campo => $valor) {
					if($campo == "nombre") {
						echo("<td title='Modificar evento'><a href='index.php?c=edu&f=capturar_evento&id=".$registro['id']."'>".$valor."</a></td>");
					} else if($campo == 'id') {
						//No imprimir nada.
					} else if($campo == 'lista_usuarios') {
						//No imprimir nada.
					} else if($campo == 'estatus') {
						echo("<td");
						if ($valor == 1) {
							echo(">Activa");
						} else if($valor == 2){
							echo(" style='color:green;'>Verificada");
						} else {
							echo(" style='color:red;'>Inactiva");
						}
						echo("</td>");
					} else {
						echo("<td>".$valor."</td>");
					} 
				}
				echo "</tr>";
			}
		} else {
			echo("<p id='notfound'>No hay eventos.</p>");
		}
	}

	function ver_eventos(){
		$_SESSION['tipoinstancia'] = $this->EduModel->tipoinstancia();
		if($_SESSION['tipoinstancia'] == 2){
			$instancia = $this->instancia();
			$inst = $instancia[3];
			$tipo = 'u';
			if($inst == "webapp")
			{
				$inst = $instancia[2];
				$tipo = 'n';
			}
			$instancia = $inst;
			require('views/edu/editar_eventos.php');
		} else {
			echo "<b style='color:red;'>No tienes acceso a este modulo.</b>";
		}
	}

	function obtener_evento(){
		$Result_evento = $this->EduModel->obtener_evento($_POST['id']);
		$evento = $Result_evento->fetch_assoc();

		$Result_usuarios_evento = $this->EduModel->obtener_usuarios_evento($_POST['id']);
		$lista_usuarios = array();
		
		while($usuario = $Result_usuarios_evento->fetch_assoc()){
			array_push($lista_usuarios, $usuario['idusuario']);
		}

		if (isset($evento['emails'])) {
			$validar_correo = 1;
		} else {
			$validar_correo = 0;
		}

		$arr = array(
			"titulo"=>$evento['nombre'],
			"fecha_antes"=>$evento['fecha_inicio'],
			"fecha_despues"=>$evento['fecha_fin'],
			"estatus"=>$evento['estatus'],
			"lista_usuarios"=>$lista_usuarios,
			"emails"=>$evento['emails'],
			"asunto"=>$evento['asunto'],
			"mensaje"=>$evento['mensaje'],
			"validar_correo"=>$validar_correo);

		echo(json_encode($arr));
	}

	function alumnos_select() {
		$lista = $this->EduModel->alumnos($this->EduModel->idprofesor($_POST['idprofesor']),0);
		$select='';
		while($l = $lista->fetch_object()){
			$select .= "<option value='$l->idalumno'>$l->instancia($l->nombre)</option>";
		}
		echo $select;
	}

	function obtener_instancia(){
		$arr = array();
		$instancia = $this->instancia();

		$inst = $instancia[3];
		$tipo = 'u';
		if($inst == "webapp")
		{
			$inst = $instancia[2];
			$tipo = 'n';
		}
		$instancia = $this->EduModel->idprofesor($inst);

		$arr["instancia"] = $instancia;
		$arr["tipo"]      = $tipo;
		echo json_encode($arr);
	}

	function validar_pertenece_despacho(){
		$Result = $this->EduModel->validar_pertenece_despacho($_POST['id']);
		if (!isset($Result)) {
			$Result['id'] = 0;
		}
		echo(json_encode($Result));
	}

	#Email ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞
	function enviar_email($e, $a, $m){
		$arr;
		if(isset($e)){
			$email = $e;
		} else {
			$email = $_POST['email'];
		}

		if (!isset($_POST['asunto'])) {
			$asunto = $a;
		} else {
			$asunto = $_POST['asunto'];
		}

		if (!isset($_POST['mensaje'])) {
			$mensaje = $m;
		} else {
			$mensaje = $_POST['mensaje'];
		}

		//Validamos si se esta generando desde el panel de eventos.
		if (!isset($_POST['lista_usuarios'])) {
			$correo_consultor = "serviciosuniversitarios@netwarmonitor.com";
			$mensaje .= "\n\nConsultas o dudas mandarlas a este correo: ".$correo_consultor;
		}

		//ENVIO DE MAIL
		include("../../netwarelog/webconfig.php");
		include("../../netwarelog/repolog/phpmailer/class.phpmailer.php");
		include("../../netwarelog/repolog/phpmailer/class.smtp.php");
 
		$mail = new PHPMailer();	
		$mail->CharSet='UTF-8';
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;
		$mail->Username = $netwarelog_correo_usu;
		$mail->Password = $netwarelog_correo_pwd;

		$mail->FromName = "Consultor";
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;
		if (is_array($email)) {
			foreach ($email as $correo => $valor) {
				$mail->addAddress($valor);
			}
		} else {
			$mail->addAddress($email);
		}
		if(!$mail->Send()) {
			//echo($mail->ErrorInfo);
			return $mail->ErrorInfo;
		} else {
			//echo(1);
			return 1;
		}
	}

	#Importar ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞ ∞∞∞∞∞∞∞∞∞∞∞∞
	function importar_datos(){
		$instancia_importar = $_POST['nombre_instancia'];
		
		#Obtenemos la instancia actual.
		$instancia = $this->instancia();
		$inst = $instancia[3];
		if($inst == "webapp")
		{
			$inst = $instancia[2];
		}
		$instancia_actual = $inst;

		#Obtenemos los nombres de las bases de datos para hacer la migración.
		$bd_importar = $this->EduModel->obtener_nombre_bd($instancia_importar);
		$bd_actual = $this->EduModel->obtener_nombre_bd($instancia_actual);

		#Convertimos a objetos.
		$importar = $bd_importar->fetch_assoc();
		$actual = $bd_actual->fetch_assoc();

		//var_dump($importar);
		//var_dump($actual);

		#Mandamos los datos para generar la migración.
		$Result = $this->EduModel->importar_datos($actual['nombre_db'], $importar['nombre_db']);
		echo json_encode($Result);
	}

	function cambiar_estatus(){
		$arr;
		$mes = $_POST['mes'];
		$anio = $_POST['anio'];
		$estatus_nuevo = $_POST['estatus'];
		$instancia = $_POST['instancia'];
		$estatus_actual = $_POST['estatus_actual'];

		//Validamos que no exista el registro con los mismos datos y que no tenga estatus.
		$validar = $this->EduModel->validar_estatus($mes, $anio, $instancia);
		if ($_POST['estatus_actual'] == 'no definido' && $validar == 0) {
			$Result = $this->EduModel->agregar_estatus($estatus_nuevo, $mes, $anio, $instancia);
		} else {
			$Result = $this->EduModel->cambiar_estatus($estatus_nuevo, $mes, $anio, $instancia);
		}
		$arr["resultado"] = $Result;
		echo json_encode($arr);
	}

	function obtener_estatus($mes,$anio,$instancia){
		$Result = $this->EduModel->obtener_estatus($mes,$anio,$instancia);
		return $Result;
	}

	function obtener_nom_producto($num){
		switch ($num) {
			case 1:
				return "Acontia";
				break;
			case 2:
				return "Foodware";
				break;
			case 3:
				return "Appministra";
				break;
			case 4:
				return "Xtructur";
				break;
			default:
				return "General";
			break;
		}
	}

}
?>
