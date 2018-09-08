<?php
//HTTPOnly
ini_set('session.cookie_httponly',1);

//CSRF
session_regenerate_id();
session_start();
$reset_vars=true;
include "../catalog/clases/clcsrf.php";

$error = 0;
if(isset($_GET['e'])){
    $error = $_GET['e'];
}

$s_cerrada = 0;
if(isset($_GET['s'])){
    $s_cerrada = $_GET['s'];
}

$org=1; //$super_idorganizacion;
$log="";
$pwd="";
$d = "";
$info="";

if(isset($_COOKIE["g"])) $org = $_COOKIE["g"];
if(isset($_COOKIE["n"])) $log = $_COOKIE["n"];

//error_log("cookie d => ".$_COOKIE["d"]);
if(isset($_COOKIE["d"])){
		$d = $_COOKIE["d"];
		$pwd = "mip130719";
} 
if(isset($_COOKIE["i"])) $info = $_COOKIE["i"];

$fecha="";
$dia="";
$mes="";
$año="";

$fecha=date("Y-m-d");

$dia=date("d");
$mes=date("m");
$año=date("Y");

?>

<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>:: NetwarMonitor-Aplicaciones Web para Empresas ::</title>


<style rel="stylesheet" type="text/css">
	body {
		background-color: red;
		color:white;
	}
	#hazbizne_banner {
		position: absolute;
		left:10%;
		top:20%;
		width: 30%;
	}
	.index_divLogin {
		position: fixed;
		top: 10%;
		left: 50%;
		width: 400px;
		height: 65px;
		margin: 0;
		padding:10px;
		background-color: rgba(0,0,0,0.2);
		font-family: "Arial";
		font-size: 11px;
	}
	a {
		color: rgb(255,100,100);
		text-decoration: none;
	}
	a:hover {
		color: white;
	}
	#btnsubmit {
		background-color: rgba(255,0,0,0.5);
		color:white;
		border-width: 2px;
		border-color: rgb(255,100,100);
	}
	table td {
		text-align: left;
		padding: 0px;
	}
	#bolas{
		position: absolute;
		bottom: 0px;
		height: 200px;
		width: 100%;
		background-size: 40%;
		background-image: url("../../modulos/hazbizne/bolas.png");
	}

</style>


<script type="text/javascript" src="../catalog/js/jquery.js"></script>

<script>
	function mensaje(m){
	        switch (m)
	            {
	            case 1:
	                alert("Usuario o contraseña no válidos.");   
	                break;
	            case 3:
	                alert("El correo electronico no es válido."); 
	                break;
	            case 2:
	                alert("EL correo que proporciono esta asociado a otro usuario, escriba otro.");
	                break;
	            }
	}



	$(document).ready(function() {

	});
</script>

</head>
<body<?php 
	if($error==1) echo " onload='mensaje(1)'";
	if($error==3) echo " onload='mensaje(2)'";
	if($error==2) echo " onload='mensaje(3)'";
?>>




<!-- ------------------------------------------------------------ -->
<!-- INICIO LOGIN -->
<div class="index_divLogin" style="text-align:right;">

		<form id="frmaccess" name="frmaccess" action="validapwd.php" method="post">
			<?php 
				//CSRF
				$form_names = $csrf->form_names(array('txtusuario','txtclave'),true);
				echo $csrf->input_token($token_id,$token_value);	 
				//error_log("\nANTES tokenid:".$token_id."  tokenval:".$token_value."  txtusuario:".$form_names['txtusuario']);
			?>

			<center>
			<table>
			<tbody>
				<tr>
					<td>Usuario</td>
					<td>Contraseña</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<input type="text" id="txtusuario" name="<?php echo $form_names['txtusuario']; ?>" value="<?php echo $log; ?>" class="index_inputlogin">
					</td>
					<td>
						<input type="password" AUTOCOMPLETE="off" id="txtclave" name="<?php echo $form_names['txtclave']; ?>" value="<?php echo $pwd; ?>" class="index_inputlogin">
					</td>
					<td>
						<input type="submit" id="btnsubmit" name="btnsubmit" value="Iniciar sesión" class="index_submitlogin">
					</td>
				</tr>
				<tr>
					<td>
						<input value="g" type="checkbox" id="chkinfo" name="chkinfo" class="index_checklogin" <?php echo $checked; ?>> Guardar contraseña
					</td>
					<td>
						<a href="javascript:opd();" class="index_recoverpwd">Olvidé mi contraseña</a>
					</td>
				</tr>
			</tbody>
			</table>
			</center> 
			
			
			
			<script type="text/javascript">
				
				function opd(){

					$("#msgrespuesta").html("[ Espere un momento por favor ... ] &nbsp;&nbsp;");
					var pcorreo=prompt("Capture el correo electrónico del usuario:");

					$.ajax({
							type: 	"post",
							url: 		"ecl.php",
							async: 	false,
							data: 	{ c: pcorreo, <?php echo $token_id; ?>:"<?php echo $token_value; ?>" }
					}).done(function(msg){
						alert(msg);
						$("#msgrespuesta").html("");
					});

				}
			</script>



		 	<br /><br />
<?php 
$checked='';
if($info=="g"){
	$checked='checked="checked"';
} 
?>


			<span id="msgrespuesta"></span>
			<input type="hidden" id="d" name="d" value="<?php echo $d; ?>">
			<input type="hidden" id="stylepath" name="stylepath" value="<?php echo $txtStylePath; ?>">
		</form>


	</div> 
<!-- FIN LOGIN -->
<!-- ------------------------------------------------------------ -->






<div id="bolas"></div>

<img src="../../modulos/hazbizne/hazbizne_banner.png" id="hazbizne_banner">


</body>
</html>
