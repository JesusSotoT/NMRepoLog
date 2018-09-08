<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	//include("conexionbd.php");	
	session_start();
	$idestructura=$_SESSION['idestructura'];
	$descripcion=$_SESSION['descripcion'];
	$letadd = $_SESSION['letadd'];
	$letmod = $_SESSION['letmod'];
	$letdel = $_SESSION['letdel'];	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $descripcion?></title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	</head>

	<body>

            <table style="height:100%;width:100%" border="1"><tr><td>prueba</td></tr></table>

<!--
                <div class="descripcion"><?php echo $descripcion; ?></div>
                <br>
                <?php
                        if($letadd==-1) echo "<input class='button' type='button' onclick='abrir(1,0,0)' value='Agregar registro' /> ";
                        if($letmod==-1) echo "<input class='button' type='button' onclick='abrir(0,1,0)' value='Modificar registro' /> ";
                        if($letdel==-1) echo "<input class='button' type='button' onclick='abrir(0,0,1)' value='Eliminar registro' /> ";
                ?>-->
                <!--<input type="button" value="Salir" onclick="window.open('admin/','_self')">	-->
<!--
                
                <iframe frameborder="1" id="opciones"  style="width:100%; height:100%"></iframe>
                <script type="text/javascript">
                        function abrir(nuevo,modificar,eliminar){
                                var url = "";
                                if(nuevo==1){
                                        url="f.php?a=1";
                                } else {
                                        if(modificar==1){
                                                url="b.php?m=1";
                                        } else {
                                                url="b.php?m=0";
                                        }
                                }
                                var frop = document.getElementById("opciones");
                                frop.src = url;
                        }
                 </script>
-->

	</body>
</html>