<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>catalog</title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	</head>
	<body>
		
		<?php

                                include("parametros.php");

                                $idreporte = mysql_real_escape_string($_GET['idreporte']);
                                $nombrereporte = mysql_real_escape_string($_GET['nombrereporte']);
                                $descripcion = mysql_real_escape_string($_GET['descripcion']);

                                $url_del_reporte = $url_repolog_para_accelog."repolog.php?i=".$idreporte;


                                $sql = " select idmenu from accelog_menu where url = '".$url_del_reporte."' ";
                                $result = $conexion->consultar($sql);
                                if(($rs=$conexion->siguiente($result))){
                                    $sql = "
                                        update accelog_menu set
                                            nombre='".$descripcion."'
                                        where url='".$url_del_reporte."';
                                        ";
                                } else {
                                    $sql = "
                                        insert into accelog_menu values
                                        (null,'".$descripcion."',0,'".$url_del_reporte."',1,-1,20,0);
                                        ";
                                }
                                $conexion->consultar($sql);

                                $sql = "
                                    update repolog_reportes set
                                        estatus='A',
                                        fechamodificacion=now()
                                    where idreporte = ".$idreporte."
                                    ";
                                $conexion->consultar($sql);

																//REGISTRO TRANSACCIONES -- 2013-10-04
        												$conexion->transaccion("REPOLOG - ADMIN - ACTIVAR - ID:".$idreporte,$sql);

                                
                               
		?>

				  <script type='text/javascript'>
						window.location = 'index.php';
				  </script>

				
	</body>
</html>
