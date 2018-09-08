<html>
	<head>
		<meta charset="utf-8" /> 
		<style>
			body {
               /* Location of the image */
                background-image: url(imagenes/fondo3.png);
             /* Image is centered vertically and horizontally at all times */
                background-position: center center;
                /* Image doesn't repeat */
                background-repeat: no-repeat;
                /* Makes the image fixed in the viewport so that it doesn't move when
                 the content height is greater than the image height */
                background-attachment: fixed;
                /* This is what makes the background image rescale based on its container's size */
                background-size: cover;
                /* Pick a solid background color that will be displayed while the background image is loading */
                background-color: #464646;
            }
            .opcion {
				background:rgba(0, 0, 0, .6);
				padding:4px;
				font-size: 25px;
				color: white;
				box-shadow:0 5px 5px 3px rgba(0, 0, 0, 0.25);
			}
			.opcion2 {
				background:rgba(0, 0, 0, .6);
				padding:4px;
				font-size: 25px;
				color: white;
				box-shadow:0 5px 5px 3px rgba(0, 0, 0, 0.25);
			}
			.opcion:hover{
				background: #e27f24;
			}
		</style>
		<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../webapp/libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../webapp/libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
	    <link rel="stylesheet" href="../webapp/libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
	    
<!-- ///////////////// ******** ---- 		FIN CSS		------ ************ ////////////////// -->
	    	
	    	
<!-- ///////////////// ******** ---- 		JS			------ ************ ////////////////// -->
	<!-- JQuery -->
		<script src="../webapp/libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../webapp/libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Notify  -->
		<script src="../webapp/libraries/notify.js"></script>
	<!-- printarea -->
		<script src="../webapp/libraries/PrintArea/js/jquery.printarea.js"></script>
		
	<!-- Sistema -->
		<script src="js/externo.js"></script>
			
<!-- ///////////////// ******** ---- 		FIN JS		------ ************ ////////////////// -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body class="body">
<!-- ///////////////// ******** ---- 		CSS			------ ************ ////////////////// -->
	
		<div class="container" align="center">
			<h3 class="opcion2"><?php echo $detalles_producto['nombre'] ?></h3>
			<div class="row" style="Margin:0">
			<!-- Imprimir comanda -->
				<div 
					style="cursor: pointer; <?php if(empty($detalles_producto['resena'])){?>display: none;<?php } ?>" 
					class="col-xs-3 opcion" 
					onclick="change(1)">
					<p>Reseña</p>
				</div>
			<!-- Llamar al mesero -->
				<div 
					style="cursor: pointer; <?php if(empty($detalles_producto['link'])){?>display: none; <?php } ?> <?php if(!empty($detalles_producto['resena'])) {?> float:right;<?php } ?>" 
					class="col-xs-3 opcion" 
					id="click_video"
					onclick="change(2)">
					<p>Video</p>
				</div>
			</div>
			<div id="resenia" class="row" style="Margin:0;height: calc(100% - 180px); margin-bottom: 40px; margin-top: 6px; position: relative;">
				<img src="imagenes/fondo_white.png" style="width: 100%; height: 100%; position: absolute; right: 0;">
			<!-- Reseña -->
				<div 
					style="overflow: auto; margin-top: 20px; height: calc(100% - 40px); max-height: 100%; font-size: 24px; text-align: justify; font-weight: bold;" 
					class="col-xs-6">
					<div style="display: table; height:100%;">
						<p style="padding-left: 20px; padding-right: 20px; display: table-cell; vertical-align: middle;">
							<?php echo $detalles_producto['resena'] ?>	
						</p>
					</div>
				</div>
			<!-- Video -->
				<div 
					style="overflow: auto; height: 100%; max-height: 100%; font-size: 24px; text-align: justify; display: table;" 
					class="col-xs-6" 
					onclick="">
					<div style="padding: 20px; display: table-cell; vertical-align: middle;">
						<img src="imagenes/fondo.png" style="padding: 20px; width: 100%; height: auto;">
					</div>
					
				</div>
			</div>
			<div id="video" class="row" style="position:relative; display: none; margin:0; height: calc(100% - 180px); margin-bottom: 40px; margin-top: 6px">
				<img src="imagenes/fondo_white.png" style="width: 100%; height: 100%; position: absolute; right: 0; z-index: -3;">
				<div id="video3" style=" width: 100%; padding: 25px; height: 100%;"></div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		var url ="<?php echo $detalles_producto['link'] ?>	";
		url = url.split("=");
		var tipo1 = 1;
		function change(tipo){
			if(tipo == 1){
				$("#resenia").show();
				$("#video").hide();
				$("#video3").html("");
			} else {
				$("#resenia").hide();
				$("#video").show();
				if (tipo1 != tipo)
					$("#video3").html('<iframe width="100%" height="100%" id="video_src" src="https://www.youtube.com/embed/'+url[1]+'" frameborder="0" allowfullscreen></iframe>');
			} 
			tipo1=tipo;
		}
		<?php if(empty($detalles_producto['resena'])){?>console.log("lalals");$("#click_video").click();<?php } ?>
	</script>
</html>	