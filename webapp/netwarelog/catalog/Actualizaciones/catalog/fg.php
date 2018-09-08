<?php

	include("conexionbd.php");
	include("clases/clcontroles.php");
	include("clases/clutilerias.php");
	
	$utilerias = new utilerias();
	
	session_start();
	
	$a = $_SESSION['catalog_nuevo'];
	$nombreestructura = $_SESSION['nombreestructura'];
	$controles = $_SESSION['controles'];
	$nombrescampos = $controles->getcampos();
	
	$sql="";
	
	//NUEVO
	if($a==1){
	
		$sqlcampos = "";
		$sqlvalores = "";
		foreach ($nombrescampos as $nombrecampo => $idcampo){
			
			$para_grabar=$controles->getgrabar($nombrecampo);
			if($para_grabar!="auto_increment"){ //Este no debe incluirse en el insert
				
				if($sqlcampos!="") $sqlcampos.=", ";
				$sqlcampos.=$nombrecampo;

				if($sqlvalores!="") $sqlvalores.=", ";
				switch($para_grabar){

					case "auto_increment":
						$sqlvalores.=$_REQUEST["i".$idcampo];
						break;
						
					case "varchar":
						$sqlvalores.="'".$_REQUEST["i".$idcampo]."'";
						break;

					case "boolean":
						$sqlvalores.="'".$_REQUEST["i".$idcampo]."'";
						break;
						
					case "select":
						if(empty($_REQUEST["i".$idcampo])){
							$sqlvalores.="'-1'";
						} else {
							$sqlvalores.="'".$_REQUEST["i".$idcampo]."'";
						}						
						break;

					case "bigint":
						$sqlvalores.=$utilerias->getnumero($_REQUEST["i".$idcampo]);
						break;

					case "int":
						$sqlvalores.=$utilerias->getnumero($_REQUEST["i".$idcampo]);
						break;

					case "double":
						$sqlvalores.=$utilerias->getnumero($_REQUEST["i".$idcampo]);
						break;

					case "date":
						$dia = $_REQUEST["i".$idcampo."_2"];
						$mes = $_REQUEST["i".$idcampo."_1"];
						$anual = $_REQUEST["i".$idcampo."_3"];						
						$sqlvalores.="'".$anual."-".$mes."-".$dia."'";
						break;
						
					case "time":
						$hora = $_REQUEST["i".$idcampo."t"];
						$ampm = $_REQUEST["i".$idcampo."ampm"];						
						$horamilitar = strtotime($hora." ".$ampm);
						$horamilitar = date("H:i:s",$horamilitar);						
						$sqlvalores.="'".$horamilitar."'";
						break;
						
					case "datetime":
						$dia = $_REQUEST["i".$idcampo."_2"];
						$mes = $_REQUEST["i".$idcampo."_1"];
						$anual = $_REQUEST["i".$idcampo."_3"];						

						$hora = $_REQUEST["i".$idcampo."t"];
						$ampm = $_REQUEST["i".$idcampo."ampm"];
						$horamilitar = strtotime($hora." ".$ampm);
						$horamilitar = date("H:i:s",$horamilitar);

						$sqlvalores.="'".$anual."-".$mes."-".$dia." ".$horamilitar."'";
						break;
						
				}
				
			}
			
			
		}
	
		$sql = " insert into ".$nombreestructura." (".$sqlcampos.") values (".$sqlvalores.") ";	
		//echo $sql;
		$conexion->consultar($sql);
		
	} else {
		

			$sqlcampos = "";
			$sqlvalores = "";
			foreach ($nombrescampos as $nombrecampo => $idcampo){

				$para_grabar=$controles->getgrabar($nombrecampo);

				if($controles->getllave($nombrecampo)==0){

					if($sqlvalores!="") $sqlvalores.=", ";
					$sqlvalores.=$nombrecampo;
					switch($para_grabar){

						case "auto_increment":
							$sqlvalores.="='".$_REQUEST["i".$idcampo]."'";
							break;

						case "varchar":
							$sqlvalores.="='".$_REQUEST["i".$idcampo]."'";
							break;

						case "boolean":
							$sqlvalores.="='".$_REQUEST["i".$idcampo]."'";
							break;

						case "select":
							$sqlvalores.="='".$_REQUEST["i".$idcampo]."'";
							break;

						case "bigint":
							$sqlvalores.="=".$utilerias->getnumero($_REQUEST["i".$idcampo]);
							break;

						case "int":
							$sqlvalores.="=".$utilerias->getnumero($_REQUEST["i".$idcampo]);
							break;

						case "double":
							$sqlvalores.="=".$utilerias->getnumero($_REQUEST["i".$idcampo]);
							break;

						case "date":
							$dia = $_REQUEST["i".$idcampo."_2"];
							$mes = $_REQUEST["i".$idcampo."_1"];
							$anual = $_REQUEST["i".$idcampo."_3"];						
							$sqlvalores.="='".$anual."-".$mes."-".$dia."'";
							break;

						case "time":
							$hora = $_REQUEST["i".$idcampo."t"];
							$ampm = $_REQUEST["i".$idcampo."ampm"];						
							$horamilitar = strtotime($hora." ".$ampm);
							$horamilitar = date("H:i:s",$horamilitar);						
							$sqlvalores.="='".$horamilitar."'";
							break;

						case "datetime":
							$dia = $_REQUEST["i".$idcampo."_2"];
							$mes = $_REQUEST["i".$idcampo."_1"];
							$anual = $_REQUEST["i".$idcampo."_3"];						

							$hora = $_REQUEST["i".$idcampo."t"];
							$ampm = $_REQUEST["i".$idcampo."ampm"];
							$horamilitar = strtotime($hora." ".$ampm);
							$horamilitar = date("H:i:s",$horamilitar);

							$sqlvalores.="='".$anual."-".$mes."-".$dia." ".$horamilitar."'";
							break;

					}
				}
			}
			
			$sqlw_m = $_REQUEST['sw_m'];
			$sqlw_m = str_replace("\\","",$sqlw_m);

			$sql = " update ".$nombreestructura." set ".$sqlvalores." where ".$sqlw_m;
			//$sql = " insert into ".$nombreestructura." (".$sqlcampos.") values (".$sqlvalores.") ";	
			//echo $sql;
			
			$conexion->consultar($sql);
	}

	$conexion->cerrar();
	
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
	</head>
	<body>
		&nbsp; &nbsp; &nbsp; <b><font color=gray>Información almacenada con éxito.</font></b>	
	</body>
</html>
