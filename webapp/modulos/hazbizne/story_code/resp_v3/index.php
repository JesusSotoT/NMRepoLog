<?php
    include "../../netwarelog/catalog/conexionbd.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Hazbizne</title>
    <script src="prefixfree.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../netwarelog/catalog/js/jquery.js"></script>
    <!--<link rel="stylesheet" href="experiment-styles.css" />-->

    <link rel="stylesheet" href="cube.css" />
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->


    <script>
        $(document).ready(function() {            
            console.log("ok");

            //Ocultar los menús
            var menu = parent.document.getElementById("tdocultar");
            menu.click();
            //console.log(m.id);

            //Ocultar el panel de arriba
            $(" body table tr:first ",parent.document).hide();
        });

        function btnminegocio_click(){
            //alert("Hola");
            //console.log("Entre 4");
            $("#mnu1024 table:first",parent.document).click();
            var menu = parent.document.getElementById("tdocultar");
            menu.click();                        
            $("#mnu_hdr1024", parent.document).click();
            //parent.document.agregatab('../../modulos/punto_venta_nuevo/index.php?c=caja&f=imprimecaja','Caja','',1238);
        }

        function btnofertas_click(){
        	$("#mnu1028 table[onclick*=': Ofertas']", parent.document).click();        	
        }

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
    </script>
    <script src="../../netwarelog/catalog/js/jquery.js"></script>
    <script src="mouse.js"></script>     

</head>
<body>
    <div class="wrapper">
        <article class="viewport">
            <section class="cube">
                
                <div class="face"><div class="face_num">1</div>
                    <h1>ARTICULOS</h1>
					<ul>
						<li><b>Abarrotes de éxito</b>
						<br>Un abarrotes de éxito debe considerar ...</li>

						<li><b>Problemas más comunes de un abarrotero</b>
						<br>Una persona que maneja una tienda de abarrotes debe...</li>

						<li><b>Manejo de Proveedores</b>
						<br>Usualmente en la administración de proveedores ...</li>
					</ul>	
                </div>
                


                <div class="face"><div class="face_num">2</div> <!--INICIAL-->
                    <h1>OFERTAS</h1>
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
					<br>
					<center>
					<button class="btncube" onclick="btnofertas_click()">Entrar</button>
					</center>

		</div>



                <!-- 3 -->
                <div class="face"><div class="face_num">3</div>
                    <h1>APPS</h1>
                    <br>
                    <center>
                        <img class="btnminegocio" src="minegocio.png" 
                        onclick="btnminegocio_click()">
                    </center>
                </div>



                <div class="face"><div class="face_num">4</div>
                    <iframe src="//player.vimeo.com/video/72845323" width="350" height="196" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                   
                </div>



                <div class="face face2"><div class="face_num">5</div>
                    <h1>CONTACTO</h1>
					<b>Everardo Guerrero</b>
					<br>Lorem ipsum dolor sit amet, consectetur adipisicing ...<br><br>

					<b>Mario Damián</b>
					<br>Lorem ipsum Lorem ipsum Lorem ipsum ...<br><br>

					<b>Gonzalo Morales</b>
					<br>Lorem ipsum Lorem ipsum Lorem ipsum ...<br><br>

					<b>Omar Mendoza</b>
					<br>Lorem ipsum Lorem ipsum Lorem ipsum ...<br><br>

					
                </div>



                <div class="face face6"><div class="face_num">6</div>
                	<h1>Foros</h1>
					<b>¿Como han manejado los proveedores?</b>
					<br>Lorem ipsum Lorem ipsum Lorem ipsum ...<br><br>                	


                    
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



    <!-- Thanks references to:
            - Paul Hayes
            - Gerard Ferrandez
    -->
    
</body>
</html>
<?php
    $conexion->close();
?>
