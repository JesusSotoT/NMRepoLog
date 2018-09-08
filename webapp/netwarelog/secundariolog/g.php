<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	//include("conexionbd.php");	
	$b=session_start();
	$idestructura=$_SESSION['secundariolog_idestructura'];
	if (isset($_SESSION['secundariolog_descripcion'])){
		$descripcion=$_SESSION['secundariolog_descripcion'];
	} else {
		$descripcion="";
	}
	$letadd = $_SESSION['secundariolog_letadd'];
	$letmod = $_SESSION['secundariolog_letmod'];
	$letdel = $_SESSION['secundariolog_letdel'];        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />

 		<!--  ##### BOOTSTRAP & FONT ###### -->
    	<link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    	<style>
    		#nmtoolbar_catalog {
    			border: none;
				/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ffffff+0,ffffff+97,dbdbdb+100 */
				background: #ffffff; /* Old browsers */
				background: -moz-linear-gradient(top,  #ffffff 0%, #ffffff 97%, #dbdbdb 100%); /* FF3.6-15 */
				background: -webkit-linear-gradient(top,  #ffffff 0%,#ffffff 97%,#dbdbdb 100%); /* Chrome10-25,Safari5.1-6 */
				background: linear-gradient(to bottom,  #ffffff 0%,#ffffff 97%,#dbdbdb 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#dbdbdb',GradientType=0 ); /* IE6-9 */
    		}
    		a:focus { outline: none; }
			#nmtoolbar_catalog { margin:0px; }
			#nmtoolbar_catalog li:hover {  color: silver; }
			#nmtoolbar_catalog li:hover {  color: black; }
			.navbar-nav > li { float:none; display:inline-block; width: 45px; text-align: center;}
			.navbar-nav > li > a { padding-top: 15px; padding-bottom: 15px; }
			.navbar-nav > li.open > ul.dropdown-menu { 
				position: absolute; 
				border: 1px; 
		  		-webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
				box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
			}
			li.focus {
            	border-bottom: solid 2px gray;
			}
			li.focus a {
				color: black !important;
			}
			body {
				padding: 0px;
			}
    	</style>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $descripcion?></title>
		
	  	<!--  ##### BEGIN: BOOTSTRAP & JQUERY ###### -->
		<script src="../../libraries/jquery.min.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script> 
		<!--  ##### END: BOOTSTRAP & JQUERY ###### -->			
		
	</head>

        <body id="seccion" onresize="redimensionar()">
        
        	<script>
				$(document).ready(function(){
					$('#div_wait_secundariolog', window.parent.document).hide();
					$('#secundariolog_lbl_title', window.parent.document).html('<b><?php echo $descripcion; ?></b>');
					$('#frsecundariolog', window.parent.document).fadeIn('slow');
				});
        	</script>
        
                                  
            <!--  <div height="10">-->
                <?php

					//accelog_seguridad
					
					require_once "../accelog_claccess.php";	
					$accelog_access	= new claccess();													

					
					/*
					if($letadd==-1){
                    	echo "<input class='button' type='button' onclick='abrir(1,0,0)' value='Agregar registro' /> ";	
						$accelog_access->add_url("/mlog/webapp/netwarelog/secundariolog/f.php?a=1");
					}
					if($letmod==-1){
						echo "<input class='button' type='button' onclick='abrir(0,1,0)' value='Modificar registro' /> ";
						$accelog_access->add_url("/mlog/webapp/netwarelog/secundariolog/b.php?m=1&primeravez=1");
						$accelog_access->add_url("/mlog/webapp/netwarelog/secundariolog/b.php?m=1");
					}
					if($letdel==-1){
						echo "<input class='button' type='button' onclick='abrir(0,0,1)' value='Eliminar registro' /> ";
						$accelog_access->add_url("/mlog/webapp/netwarelog/secundariolog/b.php?m=0&primeravez=1");
						$accelog_access->add_url("/mlog/webapp/netwarelog/secundariolog/b.php?m=0");
					}
					if($letadd||$letmod){
						$accelog_access->add_url("/mlog/webapp/netwarelog/secundariolog/f_dependenciacompuesta.php");	
						$accelog_access->add_url("/mlog/webapp/netwarelog/secundariolog/fg.php");			
					}
					*/
					
					////


                        //if($letadd==-1) echo "<input class='button' type='button' onclick='abrir(1,0,0)' value='Agregar registro' /> ";
                        //if($letmod==-1) echo "<input class='button' type='button' onclick='abrir(0,1,0)' value='Modificar registro' /> ";
                        //if($letdel==-1) echo "<input class='button' type='button' onclick='abrir(0,0,1)' value='Eliminar registro' /> ";                                                
                ?>                
           <!-- </div>-->



			<!-- ------ Begin: Navbar ------- -->
			<nav id="nmtoolbar_catalog" class="navbar navbar-default">
    			
    			<div class="container-fluid">
    		
					<ul class="nav navbar-nav" style="padding:0px;margin:0px;">
						<?php 
			    		if($letadd==-1){
			        		?>
			        		<li id="linew"><a href="javascript:abrir(1,0,0);"><i class="fa fa-plus fa-lg"></i></a></li>
			        		<?php
			        		$accelog_access->add_url("/webapp/netwarelog/secundariolog/f.php?a=1");
			    		}
			    		if($letmod==-1){
			        		?>
			        		<li id="liedit"><a href="javascript:abrir(0,1,0);" title="Modificar"><i class="fa fa-pencil-square-o fa-lg"></i></a></li>
			        		<?php
			        		$accelog_access->add_url("/webapp/netwarelog/secundariolog/b.php?m=1&primeravez=1");
			        		$accelog_access->add_url("/webapp/netwarelog/secundariolog/b.php?m=1");
			    		}
			    		if($letdel==-1){
			        		?>
			        		<li id="lidelete"><a href="javascript:abrir(0,0,1);" title="Eliminar"><i class="fa fa-trash-o fa-lg"></i></a></li>
			        		<?php
			        		$accelog_access->add_url("/webapp/netwarelog/secundariolog/b.php?m=0&primeravez=1");
			        		$accelog_access->add_url("/webapp/netwarelog/secundariolog/b.php?m=0");
			    		}
			    		if($letadd||$letmod){
			        		$accelog_access->add_url("/webapp/netwarelog/secundariolog/f_dependenciacompuesta.php");
			        		$accelog_access->add_url("/webapp/netwarelog/secundariolog/fg.php");
			    		}
						?>
			
		        		<li><a href="javascript:print();" title="Imprimir"><i class="fa fa-print fa-lg"></i></a></li>
		        		<li><a href="javascript:previous();" title="Anterior"><i class="fa fa-chevron-left fa-lg"></i></a></li>
		        		<li><a href="javascript:next();" title="Siguiente"><i class="fa fa-chevron-right fa-lg"></i></a></li>

                		<li><span id='lblstatus' class='label label-default'>Espere un momento ...</span></li>
		   			</ul>
				</div>
			</nav>
			<!-- ------ End: Navbar ------- -->


			<iframe id="opciones" frameborder=0 style="width:100%;height:310px;border:none;"></iframe>
			<script type="text/javascript">

					    $(document).ready(function (){
					        $("#lblstatus").fadeOut(1000);
					    });
			
			
					    window.onfocus = function(){
					        //console.log("entre al subiframe");
					        parent.regresa_sesion_tab();
					    };

                        function abrir(nuevo,modificar,eliminar){
                            	$("#lblstatus").hide();
                            	$("#lblstatus").removeClass("label-default");
                            	$("#lblstatus").removeClass("label-primary");
                            	$("#lblstatus").removeClass("label-danger");
                            	$("#lblstatus").removeClass("label-warning");
                           		window.parent.$("#divloading_tab").fadeIn("slow");                            
                                var url = "";
                                if(nuevo==1){
                                        url="f.php?a=1";
            							$("#lblstatus").addClass("label-primary");
            							$("#lblstatus").html("Nuevo");
                                } else {
                                        if(modificar==1){
                								$("#lblstatus").addClass("label-warning");
                								$("#lblstatus").html("Modificar registro");
                                                url="b.php?m=1&primeravez=1";
                                        } else {
                								$("#lblstatus").addClass("label-danger");
                								$("#lblstatus").html("Eliminar registro");
                                                url="b.php?m=0&primeravez=1";
                                        }
                                }
                                var frop = document.getElementById("opciones");
                                frop.src = url;
        						$("#lblstatus").fadeIn(3000);
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


    					function next(){
       						document.getElementById("opciones").contentWindow.mostrar_filas(1);
    					}
    					function previous(){
       						document.getElementById("opciones").contentWindow.mostrar_filas(0);
    					}
    					function print(){
       						document.getElementById("opciones").contentWindow.print();
    					}

                        redimensionar();

			</script>


	</body>
</html>
