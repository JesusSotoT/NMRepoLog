<?php

$c=$_GET['c'];
$p=0;
if(isset($_GET['p'])) {
	if($_GET['p']=='c'){
		$p=1;
	}else{
		echo 'Lo sentimos, solicitud no encontrada';
		exit();
	}
}

session_start();
$nombreInst= $_SESSION["accelog_nombre_organizacion"];
//ini_set('display_errors', '0');

$expc=explode('.', $c);
$fila=$expc[1].'.pdf';

$fff=explode('_', $expc[1]);
$cot=$fff[1];


include("controllers/coti.php"); 
$cotiController = new Coti;
$data = $cotiController->estatusCotizacion($cot,$c);
$existe= $data[0]['cadenaCoti'];

if($existe!=''){
	$estatus= $data[0]['aceptada'];

	$dataC = $cotiController->conversacion($cot);

}else{
	echo 'Lo sentimos, solicitud no encontrada';
	exit();
}

/*
echo <?php echo 34; ?>
include gola.php

<link href="hhhajkuh.bootstrap.php" 

<title:=00; discard change in the shadow on the air> </a>

<a href="Seleccion323.php">Link tho words</a>
<br><br><br>

//Hola como estats



*/

?>
<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
   
	    <title>Cotización</title>

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

	  	<script>

		    function aceptarCoti(coti){
		    	$.ajax({
				    url:"ajax.php?c=coti&f=aceptarCoti",
				    type: 'POST',
				    data:{coti:coti},
				    success: function(a){
				    	window.location.reload();
				    }
				  });
		    }


		    function comentario(coti,p){

		    	com=$('#jus2').val();
		    	$.ajax({
				    url:"ajax.php?c=coti&f=comentar",
				    type: 'POST',
				    data:{coti:coti,com:com,p:p},
				    success: function(a){
				    	alert('Comentario Enviado')
				    	window.location.reload();
				    }
				  });
		    }

		    function descargaCoti(){
		    	window.open('../webapp/modulos/cotizaciones/cotizacionesPdf/<?php echo $fila; ?>');
		    }

		</script>

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
				        <!--
				        <div class="col-md-6 col-sm-6 logo">
				        	<?php
				        		//ini_set('display_errors', 1);
				        		//include("controllers/caja.php"); 
								//$cajaController = new Caja;
								//$organizacion = $cajaController->datosorganizacion();
				        	?>
				          	<a style="float:right !important;" class="navbar-brand" href="https://www.netwarmonitor.com" target="_blank">
				          		<img src="../webapp/netwarelog/archivos/1/organizaciones/<?php echo $organizacion[0]['logoempresa']; ?>" class="logo_grande2">
				          	</a>
				        </div>
				        -->
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

		<div class="row">
			&nbsp;
	    </div>

	    <div class="container well" style="box-shadow: none; border: 1px solid rgb(229, 229, 229);">
	    
			<div class="row">
	    		<div class="col-md-12">
	    			<b>Cotización <?php echo $cot; ?> de <?php echo $nombreInst; ?></b>
	    		</div>
	    	</div>
	    </div>
		<?php if($estatus==0){ ?>
	    <div class="container" style="font-size: 11px;">
		    <div class="col-md-7">

		    	<?php if($p==0){ ?>
				<div class="row" style="margin-bottom: 10px;">
		    		<div class="col-md-3">
		    			<button style="width:100%" class="btn btn-success" onclick="aceptarCoti(<?php echo $cot; ?>);">Aceptar</button>

		    			<!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
		    		</div>
		    		<div class="col-md-9" style="padding-top: 9px;">
		    			 Notificar a <b><?php echo $nombreInst; ?></b> que acepta la cotizacion.
		    		</div>

		    	</div>
		    	<?php } ?>
		    	<div class="row"  style="margin-bottom: 10px;">
		    		<div class="col-md-3">
		    			<button style="width:100%" class="btn btn-primary" data-toggle="modal" data-target="#modalc" >Comentar</button>
		    		</div>
		    		<div class="col-md-9" style="padding-top: 9px;">
		    			 Enviar comentarios <?php if($p==1){ ?> al cliente <?php } ?> sobre esta cotizacion.
		    		</div>

		    	</div>
		    	<?php if($p==0){ ?>
		    	<div class="row"  style="margin-bottom: 10px;">
		    		<div class="col-md-3">
		    			<button style="width:100%" class="btn btn-primary" onclick="descargaCoti();">Descargar</button>
		    		</div>
		    		<div class="col-md-9" style="padding-top: 9px;">
		    			 Descarga tu cotizacion en PDF.
		    		</div>

		    	</div>
		    	<?php } ?>
		    </div>
		    <div class="col-md-5" style="background-color: #fdfdfd; border: 1px solid #e4e4e4; height:125px; padding: 0px; overflow-y:auto">
		    
				<?php
				$cliente='<b>Yo:</b> ';
				$respCliente='<b>Respuesta:</b> ';
				if($p==1){
					$respCliente='<b>Yo:</b> ';
					$cliente='<b>Respuesta:</b> ';
				}
					foreach ($dataC as $k => $v) {
						if($v['quien']==0){ 
							echo '<div style="background-color: #e6f2ff; border: 0px solid #40a6d9; margin: 0; padding: 4px;">'.$cliente.' '.$v['comentario'].' <font color="#9a9a9a">('.$v['fecha_comentario'].')</font></div>';
						}else{
							echo '<div style="background-color: #e6ffe6; border: 0px solid #40a6d9; margin: 0; padding: 4px;">'.$respCliente.' '.$v['comentario'].' <font color="#9a9a9a">('.$v['fecha_comentario'].')</font></div>';
						}
						//var_dump($v);
					}
				?>
		    </div>
	    </div>

	    <?php }else{ ?>
		<div class="container" style="font-size: 11px;">
		    <div class="col-md-7">
		    	<div class="row" style="margin-bottom: 10px;">
		    		<div class="col-md-12" style="font-size: 12px; color:#096;">
		    			Esta cotizacion ha sido <b>aceptada</b> por el cliente.
		    		</div>

		    	</div>
		    	<div class="row"  style="margin-bottom: 10px;">
		    		<div class="col-md-3">
		    			<button style="width:100%" class="btn btn-primary" onclick="descargaCoti();">Descargar</button>
		    		</div>
		    		<div class="col-md-9" style="padding-top: 9px;">
		    			 Descarga tu cotizacion en PDF.
		    		</div>

		    	</div>
		    </div>
		    <div class="col-md-5" style="background-color: #fdfdfd; border: 1px solid #e4e4e4; height:125px; padding: 0px; overflow-y:auto">
				<?php
				$cliente='<b>Yo:</b> ';
				$respCliente='<b>Respuesta:</b> ';
				if($p==1){
					$respCliente='<b>Yo:</b> ';
					$cliente='<b>Respuesta:</b> ';
				}
					foreach ($dataC as $k => $v) {
						if($v['quien']==0){ 
							echo '<div style="background-color: #e6f2ff; border: 0px solid #40a6d9; margin: 0; padding: 4px;">'.$cliente.' '.$v['comentario'].' <font color="#9a9a9a">('.$v['fecha_comentario'].')</font></div>';
						}else{
							echo '<div style="background-color: #e6ffe6; border: 0px solid #40a6d9; margin: 0; padding: 4px;">'.$respCliente.' '.$v['comentario'].' <font color="#9a9a9a">('.$v['fecha_comentario'].')</font></div>';
						}
						//var_dump($v);
					}
				?>
		    </div>
	    </div>

	    <?php } ?>

	    <div class="row">
			&nbsp;
	    </div>

	    <div class="container" style="padding:0px;">
	    <div class="row">
	    	<div class="col-md-12">
				<embed  style="width:100%" src="../webapp/modulos/cotizaciones/cotizacionesPdf/<?php echo $fila; ?>" height="1500">
			</div>
		</div>
	    </div>

	    

	    <!--

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

	    -->




	  	   <div class="modal fade" id="modalc" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Comentarios</h4>
      </div>
      <div class="modal-body" style="margin-bottom:-15px;">
        <textarea rows="4" style="border: 1px solid #dedede;
    color: rgb(140, 140, 140);
    padding: 5px 8px;
    width: 100%;" id='jus2' placeholder="Escribe un comentario"></textarea>
        <input type='hidden' id='ide2'>

         
      </div>
      <div class="modal-footer">
        <label id='lenvio2' hidden='true'>'Enviando ...'</label>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" id="enviarb2" class="btn btn-primary" data-dismiss="modal" onclick="comentario(<?php echo $cot; ?>,<?php echo $p; ?>);">Enviar</button>
      
      </div>
    </div>

     </div>
    </div>


	

  	</body>
</html>

