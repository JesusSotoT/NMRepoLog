<?php
    include "../../netwarelog/catalog/conexionbd.php";
    include "clases.php";    

    $netwarstore = new clnetwarstore();
    $nmdev_common = new clnmdev_common();

        //Obteniendo el giro ...
            
            $netwarstore_giro = $netwarstore->get_giro();
            //error_log("GIRO:".$netwarstore_giro);
        
        // Obteniendo los primeros 5 artículos a los que tiene acceso ...
            
            $sql = "select id_articulo from sms_articulos order by id_articulo desc limit 3 ";
            $rs_articulos = $conexion->consultar($sql);
            $articulos = array();
            while($rs_fila = $conexion->siguiente($rs_articulos)){
                $articulos[] = $rs_fila{"id_articulo"};
            }
            $articulos = $nmdev_common->get_articulos($articulos);
            //error_log("ARTICULOS:".count($articulos));

        // Obteniendo las invitaciones ...
            $invitations = $nmdev_common->get_invitations($bd);
            //error_log("INV:".count($invitations));
            $invitaciones = "";
            $num = 1;
            foreach($invitations as $db){
                //error_log("DB:".$db);
                if($db!=""){$invitaciones.= $db."|";}
                if($num==4) break;                
                $num++;
            }
            //error_log($invitaciones);
            $contactos_invitaciones = $netwarstore->get_contactos("%",0,$invitaciones);
            //error_log(count($contactos));
    


    $netwarstore->disconnect();
    $nmdev_common->disconnect();


?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Hazbizne</title>
    <script src="js/prefixfree.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../netwarelog/catalog/js/jquery.js"></script>
    <!--<link rel="stylesheet" href="experiment-styles.css" />-->

    <link rel="stylesheet" href="css/cube.css" />
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->


    <script>


        $(document).ready(function() {            
            
            var myAlert = setInterval(function(){buscar_mensajes();}, 5000);
            // APLICANDO GUI HAZBIZNE /////////////////////////////////////////////////////////////////////////////////////////////

            //Modificando formato del menu ...
            $("table.tblmenu", parent.document).hover(
                function () {
                    $(this).css("background","#404040");
                },
                function () {
                    $(this).css("background","none");
                }
            );

            //Modificando el encabezado ...
            var path_hb = "../../modulos/hazbizne/img/";
            var logo = "";
            var enc_css = "";

            enc_css = "background: url('"+path_hb+"encabezado_background.png') repeat;";
            enc_css+= "height:70px;";

            logo="Hazbizne";
            $(" body table:first tr:first ", parent.document).css("background","url('"+path_hb+"encabezado_background.png') repeat bottom");
            $(" body img:first ", parent.document).attr("src",path_hb+"hazbizne_logo.png");
            $(" body img:first ", parent.document).attr("height","");
            $("#ChatImage", parent.document).attr("src",path_hb+"support_hb.png");


            //Colocando el giro en el encabezado
            if($("#lblgiro", parent.document).length==0){
                var lblgiro = "<span id='lblgiro' style='color:white;font-size:18px;'><?php echo $netwarstore_giro; ?></span>";
                $(" body table:first tr:first td:first ", parent.document).append(lblgiro);
            }
            
            // Cambiando el fondo del menú            
            $("#tdmenu", parent.document).css("background","#626164");

            // Cambiando el color de la fuente de los menús a color blanco debido a que ahora tienen fondo gris
            $("#tdmenu font", parent.document).each(function(index){
                $(this).attr("color","white");
            });

            window.parent.$(".nmtopimages").each(function(){
                $(this).css("background-color","transparent");
                $(this).css("border","none");
                $(this).css("box-shadow","none");
            });

             // FIN APLICANDO GUI HAZBIZNE //////////////////////////////////////////////////////////////////////////////////////


      });

        function btnminegocio_click(){//
            //Abriendo la categoría del punto de venta
            $("#mnu1024 table:first",parent.document).click();
            //Abriendo ahora el menú de la Caja
            $("#mnu_hdr1024", parent.document).click();
        }

        // BOTONES DEL MENU FLOTANTE Y ENTRAR DEL CUBO ...
        function btnarticulos_click(){
        	$("#mnu1028 table[onclick*='Artículos']", parent.document).click();        	
        } 
        function btnofertas_click(){
        	$("#mnu1028 table[onclick*='ofertas']", parent.document).click();        	
        }
        function btncomunidad_click(){
        	$("#mnu1028 table[onclick*='contacto']", parent.document).click();        	
        }
       	function btnforos_click(){
        	$("#mnu1028 table[onclick*='foros']", parent.document).click();        	
        }
         function btntienda_apps_click(){
            window.open("http://store.netwarmonitor.mx","_blank");    
        }       
        ////////


        // BOTONES FLOTANTES PARTE INFERIOR
        function panic(){
            alert("¡ Botón de Panico Presionado !");
        }
        function pause(){
            $(".cube").css("animation-play-state","paused");
            for(i=1;i<=3;i++){
	            $(".cube").removeClass("cube_play_"+i);
            }
        }
        function play(type){
			pause();
			$(".cube").addClass("cube_play_"+type);
            $(".cube").css("animation-play-state","running");   
        }
        /////

        function buscar_mensajes(){
            buscar_contactos = $.ajax({
                type: "POST",
                url: "buscar_mensajes.php",
                async: true
            }).done(function(response){
                if (response != 0)
                    $('#messages').css("visibility","visible");
                else
                    $('#messages').css("visibility","hidden");
            });
        }

    </script>
    <script>
    </script>
    <script src="../../netwarelog/catalog/js/jquery.js"></script>
    <script src="js/mouse.js"></script>     

</head>
<body>

    <div class="wrapper">

        <article class="viewport">
            <section class="cube">
                

                <!-- CUBO: *** ARTICULOS *** -->
                <div class="face">

					<table width="100%"><tbody><tr>
					<td align="left"><h2>Artículos</h2></td>
					<td align="right"><img src="img/img_informacion_2.png"></td>
					</tr></tbody></table>
                    <?php 
                       foreach ($articulos as $articulo){
                        ?>
                            <b><?php if(array_key_exists("titulo", $articulo)) echo utf8_encode($articulo["titulo"]); ?></b>
                            <?php if(array_key_exists("contenido", $articulo)) echo utf8_encode($articulo["contenido"]); ?><br>
                        <?php
                        }
                    ?>
					<br><center><button class="btncube" onclick="btnarticulos_click()">Entrar</button></center>
                </div>
                

                <!-- CUBO: *** OPORTUNIDADES - OFERTAS *** -->
                <div class="face">

					<table width="100%"><tbody><tr>
					<td align="left"><h2>Oportunidades</h2></td>
					<td align="right"><img src="img/img_oportunidades_2.png"></td>
					</tr></tbody></table>

                    <?php
                        //estatus = 0 sin pedir
                        //estatus = 1 pedidos

                        $sql = "
                            select descripcion
                            from sms_oferta_client
                            where fin_ofe >= now()
                            order by estatus, fecha desc limit 0,3
                        ";
                        $result_ofertas = $conexion->consultar($sql);
                        while($rs = $conexion->siguiente($result_ofertas)){
                            ?>
                            	<?php echo $rs{"descripcion"}; ?><hr>
                            <?php
                        }

                    ?>
					
					<br><center><button class="btncube" onclick="btnofertas_click()">Entrar</button></center>
				</div>



                <!-- CUBO: *** APP SIN COSTO *** -->
                <div class="face"><div class="face_num">3</div>

					<table width="100%"><tbody><tr>
					<td align="left"><h2>App sin costo</h2></td>
					<td align="right"><img src="img/img_apps_sin_costo_2.png"></td>
					</tr></tbody></table>

                    <br><br><br><br>
                    <center>
                        <img class="btnminegocio" src="img/minegocio.png" 
                        onclick="btnminegocio_click()">
                    </center>
                </div>


                <!-- CUBO: *** VIDEO *** -->
                <div class="face"><div class="face_num">4</div>
                    <br><br>
                    <iframe src="//player.vimeo.com/video/72845323?autoplay=0" width="350" height="196" frameborder="0" 
										webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>


                <!-- CUBO: *** COMUNIDAD *** -->
                <div class="face"><div class="face_num">5</div>

					<table width="100%"><tbody><tr>
					<td align="left"><h2>Comunidad</h2></td>
					<td align="right"><img src="img/img_comunidad_2.png"></td>
					</tr></tbody></table>


                    <?php 
                        foreach($contactos_invitaciones as $contacto){
                            ?>
                                <b><?php echo $contacto["razon"]; ?></b>
                                <br>Desea hacer Hazbizne contigo... <br><br><br>
                            <?php
                        }
                    ?>

					<center><button class="btncube" onclick="btncomunidad_click()">Entrar</button></center>
					
                </div>



                <div class="face face6"><div class="face_num">6</div>

					<table width="100%"><tbody><tr>
					   <td align="left"><h2>Interactúa</h2></td>
					   <td align="right"><img src="img/img_interactua_2.png"></td>
					</tr></tbody></table>

					<b>¿Como han manejado los proveedores?</b>
					<br>Lorem ipsum Lorem ipsum Lorem ipsum ...<br><br>                	


					<br><center><button class="btncube" onclick="btnforos_click()">Entrar</button></center>
                    
                </div>




            </section>
        </article>

    </div>


    <div class="btns">
        <button class="btnpanic" onclick="panic()">!</button>
        <button class="btndefault" onclick="play(1)">►</button>
        <button class="btndefault" onclick="play(2)">▲</button>
        <button class="btndefault" onclick="play(3)">○</button>
        <!--<button class="btndefault" onclick="pause()">ll</button>-->
    </div>

    <div class="shortcuts">
        <table>
            <tbody>
                <tr onclick="btnofertas_click()" title="Oportunidades y Ofertas">
									<td>OPORTUNIDADES</td><td align=center><img src="img/img_oportunidades_2.png"></td></tr>
                <tr onclick="btnminegocio_click()" title="MiNegocio Lite">
									<td>APP SIN COSTO</td><td align=center><img src="img/img_apps_sin_costo_2.png"></td></tr>
                <tr onclick="btnarticulos_click()" title="Publicaciones de empresas">
									<td>ARTÍCULOS</td><td align=center><img src="img/img_informacion_2.png"></td></tr>
                <tr onclick="btncomunidad_click()" title="Foros">
									<td>COMUNIDAD</td><td align=center><img src="img/img_comunidad_2.png"></td></tr>
                <tr onclick="btnforos_click()" title="Mensajes y Contactos">
									<td>INTERACTÚA</td><td align=center><img src="img/img_interactua_2.png"></td></tr>
                <tr onclick="btntienda_apps_click()" title="Conoce otras aplicaciones que puedes añadir a tu sitio.">
									<td>TIENDA APPS</td><td align=center><img src="img/img_tienda_apps_2.png"></td></tr>
            </tbody>
        </table>
    </div>

    <div id="messages" style="position:absolute; top: 10px; right:10px; float: right; visibility: hidden;" onclick="btnforos_click()">
        <span>Mensajes</span>
    </div>

    

    <!-- Thanks references to:
            - Paul Hayes
            - Gerard Ferrandez
    -->

</body>
</html>
<?php
    $conexion->cerrar();
?>
