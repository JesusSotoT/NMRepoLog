<?php

	include("conexionbd.php");

	session_start();
	$idestructura = $_SESSION['idestructura'];
	$nombreestructura = $_SESSION['nombreestructura'];
	$descripcion = $_SESSION['descripcion'];
	
	$m=$_GET['m'];


	$columnas="";
	$filas="";
	$filtros="";
	$campos=array();
	$tipo=array();
	$llave=array();

	$script_paralinks="";


	//Obteniendo encabezado ...
	$sql = " select * from catalog_campos where idestructura=".$idestructura." order by orden";
	$result = $conexion->consultar($sql);
	
	$sqlw = "";
	while($reg = $conexion->siguiente($result)){
		$columnas.="<td align='center'>".$reg{'nombrecampousuario'}."</td>";
		
		
		$valor="";
		if(!empty($_REQUEST["i".$reg{'idcampo'}])){
			$valor=$_REQUEST["i".$reg{'idcampo'}];
			if($sqlw!="") $sqlw.=" and ";
			if($reg{'tipo'}='varchar'){
				$sqlw.="".$reg{'nombrecampo'}." like '%".$valor."%'";
			} else {
				$sqlw.="".$reg{'nombrecampo'}."='".$valor."'";
			}
			
		}
		
		$filtros.="<td><input class='input_filtro' id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."' 
					size='20' type='text' onkeydown='input_keydown(event)' value='".$valor."'
					title='Segmento de búsqueda, aplique un filtro sobre el campo: ".$reg{'nombrecampousuario'}.".' />					
					</td>";
					
		$campos[$reg{'idcampo'}]=$reg{'nombrecampo'};
		$tipo[$reg{'idcampo'}]=$reg{'tipo'};
		if($reg{'llaveprimaria'}){
			$llave[$reg{'nombrecampo'}]=1;
		} else {
			$llave[$reg{'nombrecampo'}]=0;
		}
	}
	$conexion->cerrar_consulta($result);
	
	if($sqlw!="") $sqlw=" where ".$sqlw;
	
	//Obteniendo datos ...
	$sql = " select * from ".$nombreestructura."  ".$sqlw;
	//echo $sql;
	$result = $conexion->consultar($sql);
	$i=0;
	$f=0;
	while($reg = $conexion->siguiente($result)){
		$f=$f+1;
		
		if($i==0){
			$filas.="<tr class='busqueda_fila'>";	
			$i=1;
		} else {
			$filas.="<tr class='busqueda_fila2'>";				
			$i=0;
		}
			$sqlw="";
			foreach($campos as $idcampo => $nombrecampo){
				
				if($llave[$nombrecampo]==1){
					if($sqlw!="") $sqlw.="%20and%20";
					$sqlw.=$nombrecampo."%20%3D%20%27".$reg{$nombrecampo}."%27";
				}
				
				$filas.="<td>";
				$filas.="<a id='a".$idcampo.$f."' class='a_registro' href='#' title='Seleccionar registro.'>";
				$info=$reg{$nombrecampo};
				$filas.=$info;
				$filas.="</a>";				
				$filas.="</td>";			
			}
			
			foreach($campos as $idcampo => $nombrecampo){

				if($m==0){ 
					$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', 'javascript:eliminar(\"".$sqlw."\")');";	
				} else {
					$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', 'f.php?a=0&sw=".$sqlw."'); ";	
				}				
				
				//$script_paralinks.="\n $(\"#a".$idcampo.$f."[href]\")='".$link."sw=".$sqlw."'; ";
				//$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', '".$link."sw=".$sqlw."'); ";				
			}
			
		$filas.="</tr>";
	}
	
	

	$conexion->cerrar_consulta($result);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>						
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $descripcion ?></title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->				
		
		<!--RECURSOS EXTERNOS CSS-->		
		<LINK href="css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			//INICIALIZA EL JQUERY
			$(document).ready(function(){
				<?php echo $script_paralinks; ?>
		 	});		
		
			function eliminar(sw){
				if(confirm("¿Esta seguro de querer eliminar el registro?")){
					window.location="e.php?sw="+sw;					
				}
			}
			
			function input_keydown(evt){
				var key = evt.keyCode;
				if(key==13){
					document.getElementById("frm").submit();
				}
				
			}


		
		</script>
	</head>
	<body>	
		<table class="busqueda" border="1" cellpadding="3" cellspacing="1">
			<tr class="tit_tabla_buscar">
				<?php echo $columnas; ?>				
			</tr>
			
			<tr class="titulo_filtros" title='Segmento de búsqueda'>
				
				<!--FORMULARIO-->
				<form id="frm" name="frm" method="post" action="b.php?m=<?php echo $m; ?>" >
					
				    <?php echo $filtros; ?>				
				
				</form>
				
			</tr>
			
			
			<?php echo $filas; ?>
			
		</table>
		
	</body>
</html>
<?php
	$conexion->cerrar();
?>