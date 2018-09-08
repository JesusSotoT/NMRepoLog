<?php
//ini_set('display_errors', '0');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
   
	    <title>Modulo de facturación</title>

	    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	  	<link href="css/style.css" type="text/css" rel="stylesheet">

	  	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	  	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	  	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	  	<link href="http://www.netwarmonitor.mx/assets/img/ico16px.png" type="image/icon" rel="icon">
		<link href="http://www.netwarmonitor.mx/assets/img/ico60px.png" rel="apple-touch-icon">
		<link href="http://www.netwarmonitor.mx/assets/img/ico76px.png" sizes="76x76" rel="apple-touch-icon">
		<link href="http://www.netwarmonitor.mx/assets/img/ico120px.png" sizes="120x120" rel="apple-touch-icon">
		<link href="http://www.netwarmonitor.mx/assets/img/ico152px.png" sizes="152x152" rel="apple-touch-icon">

	  	<script src="js/caja.js"></script>

	    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->

	    <style type="text/css">
	    	body {
				padding-top: 60px;
			}
	    	.footer {
				right: 0;
			  	bottom: 0;
			  	left: 0;
			  	padding: 1rem;
			  	text-align: center;
			}
			.cabeza{
				background-color: white;
			}
			.link_cabeza{
				text-decoration: underline;
				margin: 1.2em;
				cursor: pointer;;
			}
			.link_cabeza:hover{
				text-decoration: unset;
			}
			.cuerpo{
				min-height: 550px;
				background: transparent url('images/fondo.jpg') no-repeat scroll center center / cover ;
			}
			.maximo{
				width: 100%;
			}
			.blanco{
				color: white;
			}
			.lbl1{
				padding-right: 1em; 
				font-weight: 100; 
				font-size: 4em; 
				letter-spacing: 0.03em;
				color: #a9c630;
			}
			.lbl2{
				font-weight: 100; 
				font-size: 2em; 
				padding-right: 2em;
			}
			.lbl3{
				font-weight: 100; 
				font-size: 1.2em; 
				padding-right: 3.3em;
			}
			.boton{
				padding-top: 5em;
			}
			.btn1{
			    background-color: transparent; 
			    border: 1px solid white; 
			    color: white; 
			    border-radius: 3px; 
			    padding: 0.4em 1.5em;
			    margin-bottom: 0.5em;
			    margin-right: 1em;
			    font-size: 1.5em;
			    font-weight: 100;
			}
			.btn1:hover{
			    background-color: white;
			    color: rgba(0, 0, 0, 0.6);
			}
			.btn2{
			    background-color: transparent; 
			    border: 1px solid white; 
			    color: white; 
			    border-radius: 3px; 
			    padding: 0.4em 0.4em;
			    margin-bottom: 0.5em;
			    margin-top: 1em;
		  	}
		  	.btn2:hover{
		    	background-color: white;
		  	}
			.logo_grande{
				width: 10em;
			}
			.logo_grande2{
				height: 35px;
    			width: 45px;
			}
			.separador{
				min-height: 10px;
			}
			.razul{
				/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#43637a+24,15263a+54 */
				background: rgb(67,99,122); /* Old browsers */
				background: -moz-radial-gradient(center, ellipse cover, rgba(67,99,122,1) 24%, rgba(21,38,58,1) 54%); /* FF3.6-15 */
				background: -webkit-radial-gradient(center, ellipse cover, rgba(67,99,122,1) 24%,rgba(21,38,58,1) 54%); /* Chrome10-25,Safari5.1-6 */
				background: radial-gradient(ellipse at center, rgba(67,99,122,1) 24%,rgba(21,38,58,1) 54%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#43637a', endColorstr='#15263a',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
			}
			.razul2{
				/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#095472+0,092e43+27,095472+71,095472+100 */
				background: rgb(9,84,114); /* Old browsers */
				background: -moz-linear-gradient(top, rgba(9,84,114,1) 0%, rgba(9,46,67,1) 27%, rgba(9,84,114,1) 71%, rgba(9,84,114,1) 100%); /* FF3.6-15 */
				background: -webkit-linear-gradient(top, rgba(9,84,114,1) 0%,rgba(9,46,67,1) 27%,rgba(9,84,114,1) 71%,rgba(9,84,114,1) 100%); /* Chrome10-25,Safari5.1-6 */
				background: linear-gradient(to bottom, rgba(9,84,114,1) 0%,rgba(9,46,67,1) 27%,rgba(9,84,114,1) 71%,rgba(9,84,114,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#095472', endColorstr='#095472',GradientType=0 ); /* IE6-9 */
			}
			.rverde{
				/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#afbc6f+0,365824+40,365824+100 */
				background: rgb(175,188,111); /* Old browsers */
				background: -moz-linear-gradient(left, rgba(175,188,111,1) 0%, rgba(54,88,36,1) 40%, rgba(54,88,36,1) 100%); /* FF3.6-15 */
				background: -webkit-linear-gradient(left, rgba(175,188,111,1) 0%,rgba(54,88,36,1) 40%,rgba(54,88,36,1) 100%); /* Chrome10-25,Safari5.1-6 */
				background: linear-gradient(to right, rgba(175,188,111,1) 0%,rgba(54,88,36,1) 40%,rgba(54,88,36,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#afbc6f', endColorstr='#365824',GradientType=1 ); /* IE6-9 */
			}
			.rmorado{
				/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#2d1c46+0,312344+32,863d8e+100 */
				background: rgb(45,28,70); /* Old browsers */
				background: -moz-linear-gradient(top, rgba(45,28,70,1) 0%, rgba(49,35,68,1) 32%, rgba(134,61,142,1) 100%); /* FF3.6-15 */
				background: -webkit-linear-gradient(top, rgba(45,28,70,1) 0%,rgba(49,35,68,1) 32%,rgba(134,61,142,1) 100%); /* Chrome10-25,Safari5.1-6 */
				background: linear-gradient(to bottom, rgba(45,28,70,1) 0%,rgba(49,35,68,1) 32%,rgba(134,61,142,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2d1c46', endColorstr='#863d8e',GradientType=0 ); /* IE6-9 */
			}
			.rnaranja{
				/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ef8016+0,ef8016+57,fdca2e+100 */
				background: rgb(239,128,22); /* Old browsers */
				background: -moz-linear-gradient(-45deg, rgba(239,128,22,1) 0%, rgba(239,128,22,1) 57%, rgba(253,202,46,1) 100%); /* FF3.6-15 */
				background: -webkit-linear-gradient(-45deg, rgba(239,128,22,1) 0%,rgba(239,128,22,1) 57%,rgba(253,202,46,1) 100%); /* Chrome10-25,Safari5.1-6 */
				background: linear-gradient(135deg, rgba(239,128,22,1) 0%,rgba(239,128,22,1) 57%,rgba(253,202,46,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ef8016', endColorstr='#fdca2e',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
			}
			.t100{
				font-weight: 100;
				font-size: 0.8em;
				letter-spacing: 0.1em;
				margin-top: 0.5em;
			}
			.producto{
				border-radius: 1em; 
				min-height: 15.5em;
				margin: 0.5em;
				text-align: center;
				box-shadow: 1px 10px 15px #888888;
			}
			.producto_txt{
				font-weight: 100; 
				font-size: 1.2em;
				text-align: center;
			}
			.espacio{
				margin-top: 1em;
				margin-bottom: 1em;
			}
			.logo_chico{
				width: 7em; 
				margin-top: 0.5em; 
				margin-bottom: 1em;
			}
			.logo_chico2{
				margin-top: 1.25em; 
				margin-bottom: 1.6em
			}
			.iconos{
				margin-top: 2em; 
				padding-right: 4.5em
			}
			a{
				color: white;
			}
			.mn1{
				margin-top: 1em;
			}
			.icon-bar{
				height: 1px !important;
			}
			.modal-header-success {
			    color:#fff;
			    padding:9px 15px;
			    border-bottom:1px solid #eee;
			    background-color: #5cb85c;
			    -webkit-border-top-left-radius: 5px;
			    -webkit-border-top-right-radius: 5px;
			    -moz-border-radius-topleft: 5px;
			    -moz-border-radius-topright: 5px;
			     border-top-left-radius: 5px;
			     border-top-right-radius: 5px;
			}
			.modal-header-warning {
			    color:#fff;
			    padding:9px 15px;
			    border-bottom:1px solid #eee;
			    background-color: #f0ad4e;
			    -webkit-border-top-left-radius: 5px;
			    -webkit-border-top-right-radius: 5px;
			    -moz-border-radius-topleft: 5px;
			    -moz-border-radius-topright: 5px;
			     border-top-left-radius: 5px;
			     border-top-right-radius: 5px;
			}
			.modal-header-danger {
			    color:#fff;
			    padding:9px 15px;
			    border-bottom:1px solid #eee;
			    background-color: #d9534f;
			    -webkit-border-top-left-radius: 5px;
			    -webkit-border-top-right-radius: 5px;
			    -moz-border-radius-topleft: 5px;
			    -moz-border-radius-topright: 5px;
			     border-top-left-radius: 5px;
			     border-top-right-radius: 5px;
			}
			.modal-header-info {
			    color:#fff;
			    padding:9px 15px;
			    border-bottom:1px solid #eee;
			    background-color: #5bc0de;
			    -webkit-border-top-left-radius: 5px;
			    -webkit-border-top-right-radius: 5px;
			    -moz-border-radius-topleft: 5px;
			    -moz-border-radius-topright: 5px;
			     border-top-left-radius: 5px;
			     border-top-right-radius: 5px;
			}
			.modal-header-primary {
			    color:#fff;
			    padding:9px 15px;
			    border-bottom:1px solid #eee;
			    background-color: #428bca;
			    -webkit-border-top-left-radius: 5px;
			    -webkit-border-top-right-radius: 5px;
			    -moz-border-radius-topleft: 5px;
			    -moz-border-radius-topright: 5px;
			     border-top-left-radius: 5px;
			     border-top-right-radius: 5px;
			}
			.wrapPro {
			    word-wrap: break-word;
			    position:justify;
			    font-size: 11px;
			    width: 80%;
			    padding: 10px 10px 10px 10px;
			    height: auto;
			    overflow-x: auto;
			    color: #000;
			}

			#cliente-caja{
			    margin-bottom: -3%;
			    width: 100%;
			}
			#search-producto{
			    margin-bottom: -3%;
			    width: 100%;
			}
			.proceso{
				width: 7em;
			}
			.flecha{
				font-size: 1.5em; 
				margin-left: 0.5em; 
				margin-right: 0.5em;
				margin-top: 25%;
			}
			.icono{
				width: 68%; 
				float: left;
			}
			@media only screen and (max-width: 500px){
				.lbl1{
					padding-right: 0;
					font-size: 2em; 
				}
				.lbl2{
					font-size: 1.5em; 
					padding-right: 0;
				}
				.lbl3{
					font-size: 1em; 
					padding-right: 0;
				}
				.iconos{
					padding-right: 0;
				}
				.proceso{
					width: 4em;
				}
				.icono{
					width: 100%;
				}
				.flecha, .logo{
					display: none;
				}
				.txt{
					font-size: 0.8em;
				}
				body {
					padding-top: 53px;
				}
			}
			@media only screen and (min-width: 501px) and (max-width: 690px){
				.lbl1{
					padding-right: 0em; 
					font-size: 2.5em; 
				}
				.lbl2{
					font-size: 1.5em; 
					padding-right: 0;
				}
				.lbl3{
					font-size: 1em; 
					padding-right: 0;
				}
				.iconos{
					padding-right: 0;
				}
			}
			@media only screen and (min-width: 0px) and (max-width: 770px){
				.producto{
					margin: 0.5em 0.5em 0.5em 4em !important;
				}
			}
			@media only screen and (max-width: 400px){
				.producto{
					min-width: 16em;
					min-height: 10em !important;
				}
			}
	    </style>
  	</head>

  	<body>

	    <nav class="navbar navbar-default navbar-fixed-top cabeza">
	      	<div class="container">
		        <div class="navbar-header" style="width:100%;">
		          	<!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
		          	</button>-->
		          	<div class="row">
		          		<div class="col-md-6 col-sm-6">
				          	<a class="navbar-brand" href="https://www.netwarmonitor.com" target="_blank">
				          		<img src="images/netwar.png" class="logo_grande">
				          	</a>
				        </div>
				        <div class="col-md-6 col-sm-6 logo">
				        	<?php
				        		//ini_set('display_errors', 1);
				        		include("controllers/caja.php"); 
								$cajaController = new Caja;
								$organizacion = $cajaController->datosorganizacion();
				        	?>
				          	<a style="float:right !important;" class="navbar-brand" href="https://www.netwarmonitor.com" target="_blank">
				          		<img src="../webapp/netwarelog/archivos/1/organizaciones/<?php echo $organizacion[0]['logoempresa']; ?>" class="logo_grande2">
				          	</a>
				        </div>
				    </div>
		        </div>
	        	<!--<div id="navbar" class="navbar-collapse collapse">
	          		<ul class="nav navbar-nav navbar-right">
			            <li class="link_cabeza" onclick="$('#infoMdl').modal('show');">
			            	<i class="fa fa-question-circle-o"></i> Factura fácil
			            </li>
		          	</ul>
	      		</div>-->
	      	</div>
	    </nav>

	    <div class="container cuerpo maximo">
	    	<div class="row separador razul">
	    		<div class="col-md-12">
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-md-12 text-right">
	    			<button class="btn2" onclick="$('#infoMdl').modal('show');" onmouseover="$('#sr_img').attr('src', 'images/icono_nota_gris.png');" onmouseout="$('#sr_img').attr('src', 'images/icono_nota_blanco.png');"><img id="sr_img" src="images/icono_nota_blanco.png" style="width: 1.5em;"></button>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-md-12 text-right blanco">
	    			<label class="lbl1">Bienvenido al Servicio de<br>Facturación Netwarmonitor.</label><br>
	    			<label class="lbl3">En el cual podrás <strong>consultar y generar</strong> las facturas de las compras <br>
	    								realizadas con <strong>cualquiera de nuestros subscriptores.</strong>.</label>
	    		</div>
	    	</div>
	    	<div class="row iconos">
	    		<div class="col-md-2 col-sm-3 col-xs-4 col-md-offset-6 col-sm-offset-3 text-center blanco">
	    			<section class="icono">
	    				<img class="proceso" src="images/icono_compra.png"> <br>
	    				<i class="txt">Realiza tu compra</i>
	    			</section>
	    			<i class="fa fa-long-arrow-right flecha"></i>
	    		</div>
	    		<div class="col-md-2 col-sm-3 col-xs-4 text-center blanco">
	    			<section class="icono">
	    				<img class="proceso" src="images/icono_usuario.png"> <br>
	    				<i class="txt">Ingresa al portal de Netwarmonitor</i>
	    			</section>
	    			<i class="fa fa-long-arrow-right flecha"></i>
	    		</div>
	    		<div class="col-md-2 col-sm-3 col-xs-4 text-center blanco">
	    			<section class="icono">
	    				<img class="proceso" src="images/icono_factura.png"> <br>
	    				<i class="txt">!Obtén tu factura¡</i>
	    			</section>
	    		</div>
	    	</div>
	    	<div class="row boton">
	    		<div class="col-md-12 text-center">
	    			<button class="btn1" onclick="caja.facturarButton();">
	    				<i class="fa fa-file-text-o"></i>
	    				Obtener facturas
	    			</button>
	    		</div>
	    	</div>
	    </div>

	    <div class="container maximo espacio">
	    	<div class="row">
		    	<div class="col-md-2 col-sm-2">
		    	</div>
		    	<a href="http://acontia.com.mx/" target="_blank">
			    	<div class="col-md-2 col-sm-2 col-xs-4 blanco razul2 producto">
			    		<img src="images/acontia_logo.png" class="logo_chico">
			    		<label class="producto_txt">Para contadores, Administradores, Fiscalistas, y Directores.</label>
			    	</div>
		    	</a>
		    	<a href="http://appministra.com/" target="_blank">
			    	<div class="col-md-2 col-sm-2 col-xs-4 blanco rverde producto">
			    		<img src="images/appministra_logo.png" class="logo_chico logo_chico2">
			    		<label class="producto_txt">Solución integral de software punto de venta para MIPYMES.</label>
			    	</div>
			    </a>
			    <a href="http://foodware.com.mx/" target="_blank">
			    	<div class="col-md-2 col-sm-2 col-xs-4 blanco rmorado producto">
			    		<img src="images/foodware_logo.png" class="logo_chico logo_chico2">
			    		<label class="producto_txt">Automatiza las tareas administrativas vitales de un centro de consumo.</label>
			    	</div>
			    </a>
			    <a href="https://www.netwarmonitor.com/index.php/producto/xtructur" target="_blank">
			    	<div class="col-md-2 col-sm-2 col-xs-4 blanco rnaranja producto">
			    		<img src="images/xtructur_logo.png" class="logo_chico">
			    		<label class="producto_txt">Gestiona tus proyectos de construcción de una manera Fácil, Intuitiva y Accesible.</label>
			    	</div>
			    </a>
		    	<div class="col-md-2 col-sm-2 col-xs-2">
		    	</div>
		    </div>
	    </div>

	    <div class="footer razul">
	    	<div class="container maximo">
		    	<div class="row">
		    		<div class="col-md-2 col-sm-2 col-md-offset-1 col-sm-offset-1 blanco">
		    			<a href="https://www.facebook.com/Netwarmonitor-885265214880142/?fref=ts" target="_blank"><i class="fa fa-facebook-square fa-2x"></i></a>
		    			<a href="https://twitter.com/NetwarmonitorMX" target="_blank"><i class="fa fa-twitter-square fa-2x"></i></a>
		    		</div>
		    		<div class="col-md-5 col-sm-5 text-center blanco t100">
		    			01 800 2777 321 | 
		    			contacto@netwarmonitor.com | 
		    			www.netwarmonitor.com
		    		</div>
		    		<div class="col-md-4 col-sm-4">
		    			<img src="images/netwarblanco.png" class="logo_grande">
		    		</div>
		    	</div>
		    </div>
	    </div>

	    <!-- Modal de mensajes -->
	    <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
		    <div class="modal-dialog modal-sm">
		      	<div class="modal-content">
			        <div class="modal-header">
			        	<h4 class="modal-title">Espere un momento...</h4>
			        </div>
		        	<div class="modal-body">
		          		<div class="alert alert-default">
		            		<div align="center"><label id="lblMensajeEstado"></label></div>
	            			<div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
	                			<span class="sr-only">Loading...</span>
	             			</div>
	        			</div>
	        		</div>
	      		</div>
	    	</div>
	  	</div>

	  	<!-- Modal de Formulario Facturacion -->
	    <div id='modalCuestion' class="modal fade facturarModales" tabindex="-1" role="dialog" style="z-index:1051;">
	        <div class="modal-dialog modal-md">
	            <div class="modal-content">
	                <div class="modal-header modal-header-warning">
	                    <button type="button" class="close" data-dismiss="modal">&times;</button>
	                    <h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Facturar</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="row">
	                        <div class="col-sm-12">
	                            No se encontraron coincidencias. ¿Quieres dar de alta tus datos para facturacion.?
	                        </div>
	                    </div>
	                    <div class="row text-center mn1">
	                        <div class="col-sm-4 col-sm-offset-8">
	                            <button class="btn btn-success btn-block" onclick="caja.despliegaForm();">Dar de Alta los datos</button>
	                        </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <div class="row">
	                        <div class="col-md-6 col-md-offset-6">
	                            <!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

		<!-- Modal de Facturacion -->
	    <div id='modalFacturacion' class="modal fade facturarModales" tabindex="-1" role="dialog">
	        <div class="modal-dialog modal-lg" style="width:90%">
	            <div class="modal-content">
	                <div class="modal-header modal-header-info">
	                    <button type="button" class="close" data-dismiss="modal" id="cierre">&times;</button>
	                    <h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Facturar</h4>
	                </div>
	                <div class="modal-body ">
	                    <div class="row">
	                        <div class="col-sm-3">
	                            <label>Introduce El RFC</label>
	                        </div>
	                        <div class="col-sm-6">
	                            <input type="text" id="rfcMoldal" class="form-control">
	                        </div>
	                        <div class="col-sm-3">
	                            <button type="button" onclick="caja.revisaRfc();" class="btn btn-primary">Verifica RFC</button>
	                        </div>
	                    </div>
	                    <br>
	                    <div style="overlow:auto;overflow-y: hidden;">
	                    <div class="row">
	                        
	                        <div class="col-sm-12" style="display:none;" id="gridHidden">
	                            
	                            <table class="table table-hover table-bordered" id="datosFactGrid" >
	                                <thead>
	                                    <tr>
	                                        <th>RFC</th>
	                                        <th>Razon Social</th>
	                                        <th>Correo</th>
	                                        <th>Pais</th>
	                                        <th>Regimen F.</th>
	                                        <th>Domicilio</th>
	                                        <th>Numero</th>
	                                        <th>Codigo Postal</th>
	                                        <th>Colonia</th>
	                                        <th>Estado</th>
	                                        <th>Municipio</th>
	                                        <th>Ciudad</th>
	                                        <th></th>
	                                        <th></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                </tbody>
	                            </table>
	                            
	                        </div>

	                    </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <div class="row">
	                        <div class="col-md-6 col-md-offset-6">
	                            <!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

	    <!-- Codigo de venta -->
	    <div id='modalCodigoVenta' class="modal fade" tabindex="-1" role="dialog">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header modal-header-info">
	                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
	                    <h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Venta a Facturar</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="row">
	                        <input type="hidden" id="idComunFactu">
	                    </div>
	                    <div class="row">
	                        <div class="col-sm-2">
	                            <label>Ingresa el Codigo del Ticket</label>
	                        </div>
	                        <div class="col-sm-3">
	                            <input type="text" id="codigoTicket" class="form-control">
	                        </div>
	                        <div class="col-sm-3">
	                            <button class="btn btn-default" onclick="caja.buscaTicket();"> Verifica Ticket</button>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-sm-12">
	                            <div style="height:400px; display:none;" id="ticketHideDiv">
	                                <iframe id="ticketDiv" src="" frameborder="0" style="float:left;height:100%;width:100%;"></iframe>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <div class="row">
	                        <div class="col-md-6 col-md-offset-6">
	                            <div id="facB" style="display:none;">
	                                <button class="btn btn-success" onclick="caja.factSale();"><i class="fa fa-floppy-o"></i> Facturar</button> 
	                           </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

	    <?php
	    	//ini_set('display_errors', 1);
	    	$estados = $cajaController->CajaModel->estados();
        	$municipios = $cajaController->CajaModel->munici();
	    ?>

	    <!-- Modal de formulario de datos de Facturacion -->
	    <div id='modalFormFact' class="modal fade facturarModales" tabindex="-1" role="dialog">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header modal-header-info">
	                    <button type="button" class="close" data-dismiss="modal">&times;</button>
	                    <h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Facturar</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="row">
	                        <div class="col-sm-1">
	                            <div id="newOrUpd"></div>
	                        </div>
	                        <div class="col-sm-1">
	                            <input type="hidden" id="comFacId">
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-sm-4">
	                            <label>RFC</label>
	                            <input type="text" class="form-control formF" id="rfcFormF">
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Razon Social <span>*</span></label>
	                            <input type="text" class="form-control formF" id="razonSFormF">
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Correo<span>*</span></label>
	                            <input type="text" class="form-control formF" id="emailFormF">
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-sm-4">
	                            <label>Pais<span>*</span></label>
	                            <input type="text" class="form-control formF" id="paisFormF">
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Regimen Fiscal<span>*</span></label>
	                            <input type="text" class="form-control formF" id="regimenFormF">
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Domicilio<span>*</span></label>
	                            <input type="text" class="form-control formF" id="domicilioFormF">
	                        </div>
	                    </div>                    
	                    <div class="row">
	                        <div class="col-sm-4">
	                            <label>Numero Ext. int.<span>*</span></label>
	                            <input type="text" class="form-control formF" id="numeroFormF">
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Codigo Postal<span>*</span></label>
	                            <input type="text" class="form-control formF" id="cpFormF">
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Colonia<span>*</span></label>
	                            <input type="text" class="form-control formF" id="coloniaFormF">
	                        </div>
	                    </div>                    
	                    <div class="row">
	                        <div class="col-sm-4">
	                            <label>Estado<span>*</span></label>
	                            <select id="estadoFormF" class="form-control formF" onchange="caja.municipiosFact();">
	                                <option value="0">-Selecciona un Estado-</option>
	                                <?php 
	                                    foreach ($estados as $keyE => $valueE) {
	                                        echo '<option value="'.$valueE['idestado'].'">'.$valueE['estado'].'</option>';
	                                    }
	                                ?>
	                            </select>
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Municipio<span>*</span></label>
	                            <select id="municipioFormF" class="form-control formF">
	                                <option value="0">-Selecciona un Municipio-</option>
	                                <?php 
	                                    foreach ($municipios as $keyE => $valueE) {
	                                        echo '<option value="'.$valueE['idmunicipio'].'">'.$valueE['municipio'].'</option>';
	                                    }
	                                ?>
	                            </select>
	                        </div>
	                        <div class="col-sm-4">
	                            <label>Ciudad<span>*</span></label>
	                            <input type="text" class="form-control formF" id="ciudadFormF">
	                        </div>
	                    </div>                    
	                </div>
	                <div class="modal-footer">
	                    <div class="row">
	                        <div class="col-md-6 col-md-offset-6">
	                            <div id="butlo" style="display:none;"><i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i></div>
	                            <div id="but">
	                                <button class="btn btn-primary" onclick="caja.guardaFormF();"><i class="fa fa-floppy-o"></i> Guardar</button> 
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

	    <!-- Modal de info -->
	    <div class="modal fade" id="infoMdl" role="dialog" style="z-index:1051;">
		    <div class="modal-dialog">
		      	<div class="modal-content">
			        <div class="modal-header modal-header-info">
			        	<button type="button" class="close" data-dismiss="modal">&times;</button>
			        	<h4 class="modal-title">Pasos para facturar en el kiosko NMW</h4>
			        </div>
		        	<div class="modal-body">
		          		<div class="row">
		          			<div class="col-md-12">
		          				1. Ingresa tu RFC para validarlo.<br>
								2. Si ya esta registrado tu RFC aparecerán tus datos.<br>
								3. No estas registrado aparecerá una plantilla para llenar tus datos de facturación.<br>
								4. Valida que tus datos están correctos.<br>
								5. Requieres de modificar por favor da en el icono de editar.<br>
								6. Son correctos por favor confirma.<br>
								7. Ingresa el código de tu ticket.<br>
								8. Aparecerá la venta que requieres facturar.<br>
								9. Da clic en facturar, el sistema te notificara que fue realizada con éxito tu factura.<br>
								10. Revisa tu correo el mensaje se envía de parte  De: Netwarmonitor.<br>
								<br>
								<strong>Nota:</strong><br>
								Si tienes algún problema por favor contacta al negocio que te expidió el ticket.
		          			</div>
		          		</div>
	        		</div>
	      		</div>
	    	</div>
	  	</div>

	  	<!-- Modal comprobante -->
	  	<div id='modalComprobante' class="modal fade" tabindex="-1" role="dialog">
	        <div class="modal-dialog">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Comprobante</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="row rTouch">
	                        <div class="col-md-12">
	                            <iframe id="frameComprobante" src="" frameborder="0" style="float:left;height:300px;width:100%;"></iframe>
	                        </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <div class="row">
	                        <div class="col-md-6 col-md-offset-6">
	                            <button class="btn btn-danger btnMenu" onclick="javascript:window.location.reload();">Salir</button>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

  	</body>
</html>

