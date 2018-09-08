<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("conexionbd.php");	
	
	session_start();
	$idestructura=$_SESSION['idestructura'];
	$descripcion=$_SESSION['descripcion'];
	$a = $_GET['a'];
	
	//SCRIPT POR ALGUNOS CAMPOS QUE REQUIEREN MASCARAS O ALGO EN ESPECIAL
	$script_inputmask="";
	
	//CAMPOS NECESARIOS PARA LA CAPTURA
	$captura = "";
			
	$sql = "select * from catalog_campos where idestructura=".$idestructura." order by orden";
	$result = $conexion->consultar($sql);
	while($reg = $conexion->siguiente($result)){					


		// VERIFICANDO EL OBJETO QUE UTILIZARA EL CAMPO PARA EL FORMULARIO

			$longitud = $reg{'longitud'}; 
			if($reg{'longitud'}<=0){
				$longitud = 50; //LONGITUD MAXIMA
			}
			
			$tamano = "50"; //TAMAÑO MAXIMO
			if($reg{'longitud'}<$tamano){
				$tamano=$reg{'longitud'};
			}
			
			
			// SWITCH DEL TIPO DE CAMPO
			$objeto = "";
			switch($reg{'tipo'}){
				
				//TIPO AUTO_INCREMENT
				case "auto_increment":
					$objeto = "
								<input id='i".$reg{'idcampo'}."' 
								       name='i".$reg{'idcampo'}."' 
								       type='text' 
									   disabled size='15' style='text-align:center'
									   value='(Autonúmerico)' 
							 />";				
					break;
				
				//TIPO VARCHAR 	
				case "varchar":
					$objeto = "
							<input id='i".$reg{'idcampo'}."' 
							       name='i".$reg{'idcampo'}."' 
							       type='text' 
								   size='".$tamano."' 
								   maxlength='".$longitud."' 
						  	   />";
					break;		
					
					
				//TIPO BIG INT									
				case "bigint":
					$objeto = "
						<input id='i".$reg{'idcampo'}."' 
						       name='i".$reg{'idcampo'}."' 
						       type='text' 
							   size='20' 
							   maxlength='18' 
					  	   />";			
					break;	
					
				//TIPO INT										
				case "int":
					$objeto = "
						<input id='i".$reg{'idcampo'}."' 
					       name='i".$reg{'idcampo'}."' 
					       type='text' 
						   size='10' 
						   maxlength='9' 
				  	      />";			
					break;
					
				//TIPO DOUBLE 
				case "double":
					$objeto = "
						<input id='i".$reg{'idcampo'}."' 
					       name='i".$reg{'idcampo'}."' 
					       type='text' 
						   size='40' 
						   maxlength='100' 
				  	     />";			
					break;	

				//TIPO BOOLEAN 
				case "boolean":										
					$objeto = "
						<input id='i".$reg{'idcampo'}."' 
					       name='i".$reg{'idcampo'}."' 
					       type='checkbox' 
				  	     />";			
					break;	
					
				case "date":
					$objeto="";
					break;

				case "time":										
					$objeto="En eso estamos jeje";
					break;
				
				case "datetime":										
					$objeto="En eso estamos jeje";
					break;
										
			}
		//////
		
		// FORMATO 
			if(($reg{'tipo'}=="varchar")||($reg{'tipo'}=="bigint")||($reg{'tipo'}=="int")||($reg{'tipo'}=="double")){
				if(strlen($reg{'formato'})!=0){
					$mascara=" $('#i".$reg{'idcampo'}."').mask('".$reg{'formato'}."'); \n";	
					
					if($reg{'formato'}=="$"){
						$mascara=" $('#i".$reg{'idcampo'}."').maskMoney({symbol: '$'}); \n";
						$mascara.=" $('#i".$reg{'idcampo'}."').css('text-align','right'); \n";
					}
										
					if($reg{'formato'}=="0.00"){
						$mascara=" $('#i".$reg{'idcampo'}."').maskMoney({symbol: ''}); \n";							
						$mascara.=" $('#i".$reg{'idcampo'}."').css('text-align','right'); \n";						
					} 										

					/*
					if($reg{'formato'}=="0.0"){
						$mascara=" $('#i".$reg{'idcampo'}."').maskMoney({symbol: '', precision:1}); \n";							
						$mascara.=" $('#i".$reg{'idcampo'}."').css('text-align','right'); \n";						
					} 
					*/										
				
					$script_inputmask.=$mascara;
				}
			}
		//////
		
		// CONSTRUYENDO EL FORMULARIO		
			$captura.="
					<tr class='listadofila' title='".$reg{'descripcion'}."' >
						<td class='campo'><b>".$reg{'nombrecampousuario'}."</b><br>".$objeto."</td>
						<!--<td class='ayuda'>".$reg{'descripcion'}."</td>-->					
					</tr>		
					";		
		////
		
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
		
		<!--RECURSOS EXTERNOS-->
		
		<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<!--<LINK href="css/view.css" title="estilo" rel="stylesheet" type="text/css" />-->
		
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/maskedinput.js"></script>
		<script type="text/javascript" src="js/maskmoney.js"></script>
		<script type="text/javascript" src="js/view.js"></script>
		<script type="text/javascript" src="js/calendar.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				<?php echo $script_inputmask; ?>		   		
		 	});
		</script>		
		
	</head>
	<body>
		<div class="descripcion"><?php echo $descripcion; ?></div>
		<hr class="division">
		<div class="tipo"><?php if($a==1) echo "Nuevo Registro"; else echo "Editar registro"; ?></div>
		<br>
		<form action="fg.php" method="post">
			<table class="campos" cellpadding="10" cellspacing="0">
				<?php echo $captura; ?>
			</table>
		</form>
		
	</body>
</html>