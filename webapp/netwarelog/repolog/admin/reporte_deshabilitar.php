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
                                $url_del_reporte = $url_repolog_para_accelog."repolog.php?i=".$idreporte;

                                
                                $sql = "delete from accelog_menu where url = '".$url_del_reporte."' ";
                                $conexion->consultar($sql);


                                $sql = "
                                    update repolog_reportes set
                                        estatus='D',
                                        fechamodificacion=now()
                                    where idreporte = ".$idreporte."
                                    ";
                                $conexion->consultar($sql);

																//REGISTRO TRANSACCIONES -- 2013-10-04
        												$conexion->transaccion("REPOLOG - ADMIN - ELIMINAR - ID:".$idreporte,$sql);
                                
                               
		?>

				  <script type='text/javascript'>
                                        window.location = 'index.php';
				  </script>

				
	</body>
</html>
