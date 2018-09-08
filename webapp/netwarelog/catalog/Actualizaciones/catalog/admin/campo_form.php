<?php
	session_start();
	$idestructura=$_SESSION['idestructura'];//$_GET['idestructura'];
	$nombreestructura=$_SESSION['nombreestructura']; //$_GET['nombreestructura'];
	$idcampo=$_GET['idcampo'];
	
	$nombrecampo="";
	$nombrecampousuario="";
	$descripcion="";
	$longitud="0";
	$tipo="varchar";
	$valor="NA";
	$formula="";
	$requerido="0";
	$formato="";
	$orden="0";
	
	$titulo="Nuevo campo de ".$nombreestructura;

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../conexionbd.php");		

	if($idcampo!=-1){
				
		$sql = "
			select 	nombrecampo, nombrecampousuario, descripcion, 
					longitud, tipo, valor, formula, requerido,
					formato, orden 
			from catalog_campos
			where idcampo=".$idcampo;
		$result = $conexion->consultar($sql);
		if($reg = $conexion->siguiente($result)){
			$nombrecampo=$reg{'nombrecampo'};
			$nombrecampousuario=$reg{'nombrecampousuario'};
			$descripcion=$reg{'descripcion'};
			$longitud=$reg{'longitud'};
			$tipo=$reg{'tipo'};
			$valor=$reg{'valor'};
			$formula=$reg{'formula'};
			$requerido=$reg{'requerido'};
			$formato=$reg{'formato'};
			$orden=$reg{'orden'};			
		}
		$conexion->cerrar_consulta($result);
		
		$titulo = "Editar campo de ".$nombreestructura;			
	}
	
	//CERRAR LA BASE
	$conexion->cerrar();	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $titulo?></title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	</head>

	<body>		
		<div class="titulo"><?php echo $titulo?></div>
		<br>
		<a title="Guardar datos" class="nuevo" href="javascript:guardar();"><img class="btn" src="../img/guardar.png"></a>
		<a title="Regresar ..." class="regresar" href="javascript:regresar();"><img class="btn" alt="nuevo" src="../img/regresar.png"></a>
		<form id="frm" action="campo_guardar.php" method="post">
			<table class="formulario">
				<tbody>
					<tr class="listadofila">
						<td>Nombre campo:</td>
						<td><input 
							name="txtnombrecampo" 
							id="txtnombrecampo" type="text" 
							onKeypress="if (event.keyCode == 32 ) event.returnValue = false;"
							maxlength="50" size="30" value="<?php echo $nombrecampo?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Campo usuario:</td>
						<td><input 
							name="txtnombrecampousuario" 
							id="txtnombrecampousuario" type="text" 
							maxlength="50" size="30" value="<?php echo $nombrecampousuario?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Descripción:</td>
						<td><input  
							name="txtdescripcion" 
							id="txtdescripcion" type="text" 
							maxlength="255" size="50" value="<?php echo $descripcion?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Tipo:</td>
						<td>
							<select name="cmbtipo" id="cmbtipo">
								<option <?php if($tipo=="varchar") echo "selected";  ?>  value="varchar">varchar</option>
								<option <?php if($tipo=="double") echo "selected";  ?>  value="double">double</option>
							 	<option <?php if($tipo=="auto_increment") echo "selected";  ?>  value="auto_increment">auto_increment</option>
								<option <?php if($tipo=="int") echo "selected";  ?>  value="int">int</option>
								<option <?php if($tipo=="bigint") echo "selected";  ?>  value="bigint">bigint</option>
								<option <?php if($tipo=="datetime") echo "selected";  ?>  value="datetime">datetime</option>
								<option <?php if($tipo=="date") echo "selected";  ?>  value="date">date</option>
								<option <?php if($tipo=="time") echo "selected";  ?>  value="time">time</option>
								<option <?php if($tipo=="boolean") echo "selected";  ?>  value="boolean">boolean</option>
							</select>
						</td>
					</tr>
					<tr class="listadofila">
						<td>Longitud:</td>
						<td><input 
							name="txtlongitud" 
							id="txtlongitud" type="text" 
							onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;"
							maxlength="11" size="11" value="<?php echo $longitud?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Valor:</td>
						<td><input 
							name="txtvalor" 
							id="txtvalor" type="text" 
							maxlength="45" size="11" value="<?php echo $valor?>"></td>
					</tr>						
					<tr class="listadofila">
						<td>Fórmula:</td>
						<td><input 
							name="txtformula" 
							id="txtformula" type="text" 
							maxlength="300" size="60" value="<?php echo $formula?>"></td>
					</tr>						
					<tr class="listadofila">
						<td>Formato:</td>
						<td><input 
							name="txtformato" 
							id="txtformato" type="text" 							
							maxlength="45" size="40" value="<?php echo $formato?>">
							<a href="javascript:alert('<?php echo $formatosposibles;?>')"><img border="0" src="../img/info.png" align="top"></a>
							</td>
							
					</tr>					
					<tr class="listadofila">
						<td>Requerido:</td>
						<td><input 
							name="chkrequerido" 
							id="chkrequerido" type="checkbox" 
							 <?php 
								if($requerido==-1){
									echo "checked='checked'";
								}   
							?>
						</td>
					</tr>						
					<tr class="listadofila">
						<td>Orden:</td>
						<td><input 
							name="txtorden" 
							id="txtorden" type="text" 
							onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;"
							maxlength="11" size="11" value="<?php echo $orden?>"></td>
					</tr>					
				</tbody>
				<input name="txtidcampo" type="hidden" value="<?php echo $idcampo?>">
			</table>
			<script>
				function guardar(){
					var vtxtnombrecampo = document.getElementById("txtnombrecampo");
					var vtxtnombrecampousuario = document.getElementById("txtnombrecampousuario");
					
					if(vtxtnombrecampo.value=='') {
						alert('Capture el nombre del campo.');						
					} else {
						if(vtxtnombrecampousuario.value==''){
							alert('Capture la etiqueta del campo para el usuario.');							
						} else {
							var frm = document.getElementById("frm");
							frm.submit();
						}
					}
				}			
				function regresar(){
					window.open("campo.php","_self");
				}
			</script>
		</form>
	</body>
</html>
