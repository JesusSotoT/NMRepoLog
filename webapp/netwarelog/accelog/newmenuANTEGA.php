<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    //Información en sesion
    session_start();


    if(!isset($_SESSION["accelog_idorganizacion"])){
        header("Location: index.php");
    }

    $org = $_SESSION["accelog_idorganizacion"];
    $nombre_org = $_SESSION["accelog_nombre_organizacion"];
    $login = $_SESSION["accelog_login"];
    $idempleado = $_SESSION["accelog_idempleado"];
    $idperfil = $_SESSION["accelog_idperfil"];

    $opciones_permitidas = $_SESSION["accelog_opciones"];
    $menus_permitidos = $_SESSION["accelog_menus"];


    include "webconfig_accelog.php";
    include "clases/clarbolmenu.php";  


				$nombre=""; 
				$puesto="";
				$empresa="";
        $datosempresa="";
				$correoelectronico="";
        $foto="";
        $logoempresa="";

				$sQ="SELECT concat(a.nombre,' ',a.apellidos) nombre,
					p.puesto,o.nombreorganizacion empresa, a.correoelectronico,o.nombreorganizacion, a.foto, o.logoempresa 
					FROM empleados e 
                                            left join organizaciones o on e.idorganizacion=o.idorganizacion
                                            left join administracion_usuarios a on a.idempleado=e.idempleado
                                            left join puestos p on p.idpuesto=a.idpuesto
                                        where e.idempleado='$idempleado'";
				$result = $conexion->consultar($sQ);
				while($rs = $conexion->siguiente($result)){
                                    $nombre=$rs{"nombre"}; 
                                    $puesto=$rs{"puesto"};
                                    $foto=$rs{"foto"};
                                    $logoempresa=$rs{"logoempresa"};
                                    $correoelectronico=$rs{"correoelectronico"};
                                    $empresa=$rs{"nombreorganizacion"};
                                }
				$conexion->cerrar_consulta($result);	
				
                                if($foto==""){
                                    $foto="x.png";
                                }
                                if($logoempresa==""){
                                    $logoempresa="x.png";
                                }
$anio=date('Y');
                                				
?>


<!DOCTYPE html>
<html>

    <head>
        
        <title> NetwareMonitor &copy;<?PHP echo $anio;?> </title>
		<meta http-equiv="Expires" content="0" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta charset="UTF-8" />

        <!--CSS-->
        <LINK href="css/estilo_accelog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSO LOCAL CSS-->
        <LINK href="../catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSOS EXTERNOS COMPATIBILIDAD CATALOG CSS-->
        <LINK href="../../modulos/notificaciones/notificaciones.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSOS EXTERNOS COMPATIBILIDAD CATALOG CSS-->
        
        <!--PLUG IN CATALOG-->
        <script type="text/javascript" src="../catalog/js/jquery.js"></script>
    
        <script type="text/javascript">
           
            var estadomenu=1;
            var tabs = new Array();
            var sp;
            var ses = new Array();
            var ntab = 0;
            var tabseleccionado = "";

            $(document).ready(function() {

                sp='<?php echo session_id(); ?>';                

                document.getElementById("tdocultar").title="Ocultar menú";
                
                $("#tdhome").click(function () {
                    location.reload();
                });
                
                $("#tdocultar").click(function () {
                        if(estadomenu==1){
                            document.getElementById("tdocultar").title="Mostrar menú";
                            document.getElementById("tdmenu").style.display="none";''
							document.getElementById("tdocultar").src="img/iw2/interior/abrir.png";
                            //document.getElementById("imgflecha").src="img/flecha_der.png";
                            estadomenu=0;
                        } else {
                            document.getElementById("tdocultar").title="Ocultar menú";
                            document.getElementById("tdocultar").alt="Ocultar menú.";
                            document.getElementById("tdmenu").style.display="block";
							document.getElementById("tdocultar").src="img/iw2/interior/cerrar.png";
                            //document.getElementById("imgflecha").src="img/flecha_izq.png";
                            estadomenu=1;
                        }
                });

                abreultimacat();       
                abrirmenuporomision();

               
            });



            function abrircategorias(cat,mnu,mnu_hdr,icn_mnu){
                if (typeof(icn_mnu) != "undefined") {
					path_string = "../utilerias/img_cat/";
					icon_string = icn_mnu.substring(8);
					full_path = path_string + icon_string + "_n.png";
					$(icn_mnu).attr("src",full_path);
				}
				$(".categorias").css("height","25");
                
                $(cat).css("height","");

                $(".menu").css("display","none");
                //$(mnu).css("display","block");
                $(mnu).fadeIn(200);
                //alert("hola "+cat);
				$(".mnu_hdrs").css("border","2px #525154 solid");
				
				if (typeof(icn_mnu) != "undefined") {
					$(".ico_mnus").each(function(index, value){
						match_string = "#" + $(this).attr('id');
						if (match_string != icn_mnu) {
							icon_file_name = value.src.substring(value.src.lastIndexOf("/")+1);
							if (icon_file_name.lastIndexOf("_n") != -1) {
								new_path = path_string + $(this).attr('id').substring(7) + ".png";
								//alert ("Will take " + new_path);
								$(this).attr('src',new_path);
							}
						}
					});
				}
				
				$(mnu_hdr).css("border","2px #91C313 solid");
            }

            function hijos(divhijo,imagen){
                //alert("hola "+imagen);
                //alert($(divhijo).css("display"));
                if($(divhijo).css("display")=="none"){
                    $(divhijo).fadeIn(200);
                    $(imagen).attr("src","img/menos.png");
                } else {
                    $(divhijo).fadeOut(200);
                    $(imagen).attr("src","img/mas.png");
                }
            }

            function agregatab(url,titulo,imagen,id){

								//alert("Entre "+url+" "+titulo+" "+imagen+" "+id);

                //Checa si el tab no esta abierto ya....
                var i = 0;
                for(i=0;i<=ntab;i++){
                    if(tabs[i]==id){
                        clictab("tb"+id);
                        return;
                    }
                }

                //Añade el nuevo tab a la lista de tabs...
                ntab+=1;
                tabs[ntab]=id;

                                             
                $(".contmenu").fadeOut(200);
                lineaimagen="";
                if(imagen!=""){
                    lineaimagen="<img src='"+imagen+"' >";
                }

                $(".tdtab1").css("background-image","url('img/tab_sel_1.png')");
                $(".tdtab").css("background-image","url('img/tab_sel_bg.png')");
                $(".tdtab2").css("background-image","url('img/tab_sel_2.png')");
                $(".tdtab").css("color","white");
                $(".tdtab").css("font-weight","normal");


                var ntd = "";
                ntd="<td class='tdtab1' id='tb"+id+"-1'  onmouseover='utab(\"tb"+id+"\")'  onmouseout='otab(\"tb"+id+"\")'   onclick='clictab(\"tb"+id+"\")' ></td>";
                ntd+="<td class='tdtab' id='tb"+id+"-2'  onmouseover='utab(\"tb"+id+"\")'  onmouseout='otab(\"tb"+id+"\")'   onclick='clictab(\"tb"+id+"\")' >"+lineaimagen+"</td>";
                ntd+="<td class='tdtab' id='tb"+id+"-3'  onmouseover='utab(\"tb"+id+"\")'  onmouseout='otab(\"tb"+id+"\")'   onclick='clictab(\"tb"+id+"\")' >&nbsp; "+titulo+" &nbsp;</td>";
                ntd+="<td class='tdtab2' id='tb"+id+"-4'  onmouseover='utab(\"tb"+id+"\")'  onmouseout='otab(\"tb"+id+"\")'   onclick='quitartab(\"tb"+id+"\","+id+",\""+titulo+"\")' title='Cerrar esta pestaña.' ></td>";
				//ntd+="<td style='background-color:#fff'>&nbsp</td>";
                $("#filatabs").append(ntd);

                tabseleccionado = "tb"+id;

								//Obteniendo url absoluta
								var url_vinculo = document.createElement("a");
								url_vinculo.href = url;
								url = url_vinculo.href;
								//alert(url);

								//Enviando al control de sesiones
								//console.log(url);
                sp_c(tabseleccionado,url);


                //CARGANDO TD PARA URL
                $(".tdurl").css("display","none");
                var ntdu = "";
                ntdu="<td id='tb"+id+"-u'  class='tdurl'>";
                ntdu+="<iframe id='frurl' frameborder=0 class='frurl' src='"+url+"' ></iframe>";
                ntdu+="</td>";
                //alert(ntdu);
                $("#filaurls").append(ntdu);

            }

            function sp_c(d,url){

							url_vinculo = url;

               //document.getElementById("divmsg").innerHTML=sp+d;
              html= $.ajax({
															type: "POST",
                              url: 'sp_c.php',
                              data: {
																			p:sp+d,
																			o:"<?php echo $org; ?>",
																			n:"<?php echo $nombre_org; ?>",
																			l:"<?php echo $login; ?>",
																			e:"<?php echo $idempleado; ?>",
																			per:"<?php echo $idperfil; ?>",
																			cio:"<?php echo $campo_idorganizacion; ?>",
																			url_vinc: url_vinculo
																		},
                              async: false
                         }).responseText;
               if(html!=""){
									if(html=="salir"){ 
										document.location="index.php";
									}
								}
            }

            function utab(cual){                
                var tab = "#"+cual+"-4";
                //alert(tabseleccionado+"   cual:"+cual);
                if(tabseleccionado==cual){
                    $(tab).css("background-image","url('img/tab_nosel_2_cerrar.png')");
                } else {
                    $(tab).css("background-image","url('img/tab_sel_2_cerrar.png')");
                }
            }

            function otab(cual){
                var tab = "#"+cual+"-4";
                //alert(tabseleccionado+"   cual:"+cual);
                if(tabseleccionado==cual){
                    $(tab).css("background-image","url('img/tab_nosel_2.png')");
                } else {
                    $(tab).css("background-image","url('img/tab_sel_2.png')");
                }
            }

            function clictab(cual){

                if(tabseleccionado == cual) return;
                sp_c(cual);

                $(".tdurl").css("display","none");

                $(".tdtab1").css("background-image","url('img/tab_sel_1.png')");
                $(".tdtab").css("background-image","url('img/tab_sel_bg.png')");
                $(".tdtab2").css("background-image","url('img/tab_sel_2.png')");
                //$(".tdtab").css("color","white");
                $(".tdtab").css("font-weight","normal");

                $("#"+cual+"-1").css("background-image","url('img/tab_nosel_1.png')");
                $("#"+cual+"-2").css("background-image","url('img/tab_nosel_bg.png')");
                $("#"+cual+"-3").css("background-image","url('img/tab_nosel_bg.png')");
                $("#"+cual+"-4").css("background-image","url('img/tab_nosel_2.png')");
                //$("#"+cual+"-3").css("color","white");
                $("#"+cual+"-3").css("font-weight","normal");
                $("#"+cual+"-u").fadeIn(200);

                tabseleccionado = cual;
            }

	    
	    var preguntar = true;
            function quitartab(cual,id,nombre){
		var resp = true;
		if(preguntar){
               		resp = confirm("Haga clic en aceptar para cerrar: [ "+nombre+" ]");
		}
                if(resp){
                    $("#"+cual+"-1").remove();
                    $("#"+cual+"-2").remove();
                    $("#"+cual+"-3").remove();
                    $("#"+cual+"-4").remove();
                    $("#"+cual+"-u").remove();

                    var i = 0;
                    for(i=0;i<=ntab;i++){
                        if(tabs[i]==id){
                            tabs.splice(i,1);
                            ntab=ntab-1;
                            break;
                        }
                    }
                }
            }


            function mostrarcontenido(divcontenido){
                if($(divcontenido).css("display")=="none"){
                    $(".contmenu").fadeOut(200);
                    $(divcontenido).fadeIn(200);
                }
            }

            function salir(){
		preguntar=false;
		var i = 0;
                for(i=ntab;i>=0;i--){
                    quitartab("tb"+tabs[i]);
                }
                document.location="salir.php";            
	    }

            function modificarclave(){
                agregatab("cambiarclave.php","Cambiar Clave","",-100);
            }


	    // Agregando soporte para el cambio de tab

	    $(window).focus(function(){
				regresa_sesion_tab();
	    });
	    function regresa_sesion_tab(){
				var h = new Date();
				var hora = h.getHours()+":"+h.getMinutes()+":"+h.getSeconds();
				//console.log(">>> "+tabseleccionado+"  "+hora);
	   		sp_c(tabseleccionado);
	    }


//EVITAR QUE SE CIERRE LA PAGINA
window.onbeforeunload = function(e) {
	if(pregunta){
		return "Favor de confirmar la salida, Gracias por utilizar nuestros sistemas.\n\NetwareMonitor.";
	}
};

        </script>

    </head>
   

 
    <body style="margin:0px 0px 0px 0px; font-family:Arial; font-size:12px; font-weight:normal; color:#000000; "  >

		<div style="width:100%; padding:0px 0px 0px 0px; margin:0px 0px 0px 0px;">
	        <table style="width:100%; border-spacing:0px; border-collapse:collapse;">
	            <tbody>
	                <tr style="height:60px;"> 
	                    <td style="vertical-align:middle; text-align:left; ">	
	                        <?php
		                    //Define Logo de la Empresa
		                    $filename = "../archivos/".$org."/organizaciones/$logoempresa";
		                    if(!file_exists($filename)){
		                        $filename = "../archivos/".$org."/organizaciones/x.png";
		                    }
	                        ?>  
	                        <img alt="" src="<?php echo $filename; ?>" style="height:55px">
	                    </td>
	                    <td style="vertical-align:middle; text-align:right;">
	                        <a href="javascript:modificarclave();"><img alt="" src="img/clave.png" style="border:0px;" ></a>
	                        <a href="javascript:modificarclave();" style=" text-decoration:none; color:#000000; font-size:10px;">Modificar Clave</a>&nbsp;
	                        <?php
	                        //Define Imagen del Usuario
	                        $filename = "../archivos/".$org."/administracion_usuarios/$foto";
	                        if(!file_exists($filename)){
	                            $filename = "../archivos/".$org."/administracion_usuarios/x.png";
	                        }
	                        ?>
	                        <img alt="" src="<?php echo $filename; ?>" style="height:55px">
	                    </td>
	                </tr>
	            </tbody>
	        </table>	
		</div>

		<div style="width:100%; padding:0px 0px 0px 0px; margin:0px 0px 0px 0px;">
	        <table style="width:100%; border-spacing:0px; border-collapse:collapse;">
                <tbody>
                    <tr style="background-color:#525154;">
                        <td style="vertical-align:middle; text-align:left; padding:5px 0px 0px 5px;">
                            <img alt="Ocultar menú" id="tdocultar" src="img/iw/interior/cerrar.png" style="height:25px; width:25px; border:0px; display:inline-block;">
                            <img alt="" id="tdhome" src="img/iw/interior/home.png" style="height:25px; width:25px; border:0px; display:inline-block;">
                         </td> 
                        <td style="vertical-align:middle; text-align:left">
                            <span style="color:#FFFFFF;"><?php echo $empresa?></span>
                        </td>
                        <td style="vertical-align:middle; text-align:right; color:#FFFFFF;">
                        	<span style="padding-left:1px;">Notificaciones</span>
                            <?php
                            $muestra_cuenta = $conexion->not_regresa_numero($conexion);
                            ?>
                            <span style="color:#91C313;"><a style="color:#91C313; text-decoration:none;" href="javascript:mostrar_notificaciones();">(<?php echo $muestra_cuenta; ?>)</a></span>					
                            |
                            <span style="padding-left:1px;"><?php echo $nombre?></span>
                            |
                            <span style="padding-left:1px;"><?php echo $puesto?></span>
                            |
                            <span style="padding-left:1px; padding-right:5px;"><a style="color:#91C313" href="#" onClick="salir()">Salir</a></span>
                        </td>
                    </tr>
                </tbody>
            </table>
		</div>


        <!--TABLA PRINCIPAL-->
        <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="1" >
            <tr height="100%">
                <!--ARBOL DE MENU-->
                <td style="width:200px;border:-#cccccc thin solid;margin:0px;vertical-align:top;" valing="top" id="tdmenu">
                    <!--TABLA DE CATEGORIAS-->
                    <table style="width:200px; " height="100%" cellpadding="0" cellspacing="4" >
                        <tbody>
                        <?php

                        $sql = "select idcategoria, nombre,icono from accelog_categorias order by orden ";
                        $result = $conexion->consultar($sql);

                        $inicio = 1;
                        $altura="";
                        $icono = "";
                        $display="block";
                        $ultimacategoria="";
                        $ocultacategoriassinmenus="";
						$menusporomision = array();
                        while($rs = $conexion->siguiente($result)){

                            $altura="height:100%;";
                            if($inicio==1){
                                $inicio=2;
                            } else {
                                $display="none";
                                //$altura="height:25px;";
                            }
                            
                            if($rs{"icono"}){
                                $icono = "<td align=center width='40' ><img class='ico_mnus' id='ico_mnu".$rs{"idcategoria"}."' src=\"../utilerias/img_cat/".$rs{"idcategoria"}.".png\" ></td>";
                            } else {
                                $icono = "<td width='40' ></td>";
                            }

                                                            //Se cargan antes los menus para saber si la categoria tiene menus

                                                            //Aquí arranca la carga de submenus de la categoria.
                                                            $menus = new arbolmenu($rs{"idcategoria"},$conexion,$menus_permitidos);

															//Cargando arreglo de menus por omision
															$menus->setMenuOmision($menusporomision);

                                                            //Esta funcion se mandara llamar de forma recursiva...
                                                            $susmenus=$menus->construyemenus(0,-5);

															//Se obtienen los menus por omision
															$menusporomision=$menus->regresamenusomision();
															//echo count($menusporomision);
															$abrirporomision="";
															for($menusom=0;$menusom<=count($menusporomision)-1;$menusom++){
																if($abrirporomision!="") $abrirporomision.=" or ";
																$abrirporomision.=" idmenu=".$menusporomision[$menusom];
																//echo "<br>".$menusporomision[$menusom];
															}
															//echo "menus por omision:".$abrirporomision;

                                                            if($susmenus==""){
                                                                if($altura!="") $altura=";";
                                                                $altura="display:none";
                                                            } else {
                                                                $ultimacategoria="abrircategorias('#cat".$rs{"idcategoria"}."','#mnu".$rs{"idcategoria"}."');";
                                                            }

                            ?>
                                    <tr    class="categorias"
                                            id="cat<?php echo $rs{"idcategoria"}; ?>"
                                            valign="top"
                                            style="<?php echo $altura; ?>"        >
                                        
                                            
                                        <td>
                                            <table id="mnu_hdr<?php echo $rs{"idcategoria"}; ?>" style="background-color:aqua;cursor:pointer;width:200px;height:25px; border: 2px #525154 solid;" onclick="abrircategorias('#cat<?php  echo $rs{"idcategoria"};  ?>','#mnu<?php  echo $rs{"idcategoria"};  ?>','#mnu_hdr<?php  echo $rs{"idcategoria"};  ?>','#ico_mnu<?php echo $rs{"idcategoria"}; ?>');" >
                                                <tr valign="middle">
                                                    <?php echo $icono; ?>
                                                    <td style="font-weight:lighter;font-size:11px;">
                                                        <font color="#555555">
                                                            <?php echo $rs{"nombre"}; ?>
                                                        </font>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                            <div class="menu" id="mnu<?php echo $rs{"idcategoria"}; ?>"
                                                style="display:<?php echo $display; ?>;opacity=1;" >
                                                
                                                <font size="2">                                                        
                                                        <?php

                                                            echo $susmenus;

                                                        ?>
                                                                                                    
                                                </font>
                                            </div>                                                

                                        </td>
                                    </tr>
                            <?php
                            

                        }
                        $conexion->cerrar_consulta($result);
                        ?>
                         <script type="text/javascript">
                                function abreultimacat(){
                                    <?php
                                        echo $ultimacategoria;
                                    ?>
                                }

                                function abrirmenuporomision(){
                                    <?php
										//echo "alert('".$abrirporomision."');";
                                        if($abrirporomision!="") {
                                           
                                                $sql = "select idmenu, url, nombre from accelog_menu where ".$abrirporomision;
												//echo "alert('".$sql."');";
                                                $result = $conexion->consultar($sql);
                                                while($rs=$conexion->siguiente($result)){	

													$filename="../utilerias/img_mnu/".$rs{"idmenu"}.".png";
                                                    echo " agregatab('".$rs{"url"}."','".$rs{"nombre"}."','".$filename."',".$rs{"idmenu"}.");";
													//echo "alert('"." agregatab('".$rs{"url"}."','".$rs{"nombre"}."','".$filename."',".$rs{"idmenu"}.");"."');";													
                                                }
                                                $conexion->cerrar_consulta($result);
                                        }
                                    ?>
                                }                                 

                        </script>

                      </tbody>
                    </table>


                </td>

                             
                <!-- OCULTAR MENU -->
                <td id="tdocultara">
                    <font size="1">&nbsp;</font>
                </td>



                <!--CONTENIDO-->
                <td class="tdcontenido" valign="top">

                    <table cellspacing="0" cellpadding="0" height="100%" width="100%">
                        <tr style="height:45px"><td>

                    <!--TABS-->
                    <div id="tabs" style="background-color:#fff;">
                        <table cellspacing="0" cellpadding="0">
                            <tr valign="middle" id="filatabs">
                                
                            </tr>
                        </table>
                    </div>

                        </td></tr>
                        <tr><td>


                    <!--URLS-->
                    <div id="contenido">
                        <table id="tblurls" cellspacing="0" cellpadding="0">
                            <tr id="filaurls">

                            </tr>
                        </table>
                    </div>

                        </td></tr>
                    </table>


                </td>
                

            </tr>
        </table>


	<!-- Agregando overlay de notificaciones -->
    <script language="javascript">
    	function mostrar_notificaciones(){
			//alert(id+ne);
			$("#divcomp").fadeIn(500);
			$("#frmcomp").attr("src","../../modulos/notificaciones/notificaciones_mostrar.php");
		}
		
        function cerrardiv(){
			$("#divcomp").hide();
		}
	</script>	
    <div id="divcomp"><iframe id='frmcomp'></iframe></div>
    <!-- Agregando overlay de notificaciones -->
    </body>
</html>
