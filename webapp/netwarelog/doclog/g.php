<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	//include("conexionbd.php");	
	$b=session_start();
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
		<title><?php echo $_SESSION["nombredocumento"]?></title>
		<meta name="generator" content="TextMate http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	</head>

        <body id="seccion" onresize="redimensionar()">
                                  
            <div height="20">
                <div class="descripcion"><?php echo $_SESSION["nombredocumento"]?></div>
                <br>
                <?php

										//accelog_seguridad

												require_once "../accelog_claccess.php";
												$accelog_access = new claccess();

                        if($letadd==-1){
														echo "<input class='button' type='button' onclick='abrir(1,0,0)' value='Agregar registro' /> ";
														$accelog_access->add_url("/webapp/netwarelog/doclog/f.php?a=1");
												}
                        if($letmod==-1){ 
														echo "<input class='button' type='button' onclick='abrir(0,1,0)' value='Modificar registro' /> ";
														$accelog_access->add_url("/webapp/netwarelog/doclog/b.php?m=1&primeravez=1");
														$accelog_access->add_url("/webapp/netwarelog/doclog/b.php?m=1");
												}
                        if($letdel==-1){
														echo "<input class='button' type='button' onclick='abrir(0,0,1)' value='Eliminar registro' /> ";
														$accelog_access->add_url("/webapp/netwarelog/doclog/b.php?m=0&primeravez=1");
														$accelog_access->add_url("/webapp/netwarelog/doclog/b.php?m=0");
												}
												if($letadd||$letmod){
														$accelog_access->add_url("/webapp/netwarelog/doclog/f_dependenciacompuesta.php");		
														$accelog_access->add_url("/webapp/netwarelog/doclog/f_detalles_dependenciacompuesta.php");		
														$accelog_access->add_url("/webapp/netwarelog/doclog/fg.php");			
														$accelog_access->add_url("/webapp/netwarelog/doclog/fg_detalles.php");			
												}

										////
                ?>                
           </div>

            
                <iframe id="opciones" frameborder=0 style="width:100%;border:none;"></iframe>
                <script type="text/javascript">

                        function abrir(nuevo,modificar,eliminar){
                                var url = "";
                                if(nuevo==1){
                                        url="f.php?a=1";
                                } else {
                                        if(modificar==1){
                                                url="b.php?m=1&primeravez=1";
                                        } else {
                                                url="b.php?m=0&primeravez=1";
                                        }
                                }
                                var frop = document.getElementById("opciones");
                                frop.src = url;
                        }
                        
                        function redimensionar(){
                            var frop=document.getElementById("opciones");

                            var altura = parent.innerHeight;
                            
                            if(altura==null){ //IE
                                altura = document.documentElement.clientHeight;
                                //alert(altura);
                                altura = altura-80;
                                //alert(altura);
                            } else { //otros browser
                                altura = altura-205;
                            }                         
                                                        
                            frop.setAttribute("height", altura);                            
                        }


                        redimensionar();

                </script>


	</body>
</html>
