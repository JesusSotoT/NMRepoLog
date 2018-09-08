<!doctype html>
<html>
	<head>
		<link rel="icon" type="image/icon" href="http://www.netwarmonitor.mx/assets/img/ico16px.png">
		<link rel="apple-touch-icon" href="http://www.netwarmonitor.mx/assets/img/ico60px.png">
		<link rel="apple-touch-icon" sizes="76x76" href="http://www.netwarmonitor.mx/assets/img/ico76px.png">
		<link rel="apple-touch-icon" sizes="120x120" href="http://www.netwarmonitor.mx/assets/img/ico120px.png">
		<link rel="apple-touch-icon" sizes="152x152" href="http://www.netwarmonitor.mx/assets/img/ico152px.png">

		<title>Netwarmonitor | High Innovation Technology</title>
		<style>

			html {
				background: url(poster.mp4) no-repeat center center fixed;
				background-size: cover;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
				overflow: hidden;
			}
			video {
				position: fixed;
				top: 50%;
				left: 50%;
				min-width: 100%;
				min-height: 100%;
				width: auto;
				height: auto;
				z-index: -100;
				transform: translateX(-50%) translateY(-50%);
				transition: 1s opacity;
			}
			body {
				margin: 0px;
			}
			#divlogo {
				position: relative;
				top: 10px;
				width:100%;
				text-align: center;
			}
			img {
				width: 300px;
			}
		</style>
	</head>
	<body>
		<div id="divlogo">
			<img src="logo.png">
		</div>

		<!--
		<video 	loop autoplay poster="poster.jpg"
				preload="yes"
				width="50%"
				height=auto
				src="poster.mp4"
				type='video/mp4'></video>
		-->

		<!--
		<video preload="yes" autoplay loop
			width="50%"
			height="auto"
			poster="poster.jpg"
			src="poster.mp4"
			type="video/mp4">
			<source src="prueba_video.mp4" type="video/mp4">
			<source src="prueba_video.webm" type="video/webm">
		</video>
		-->


		<video autoplay="autoplay" poster="poster.mp4" data-mobile-poster="poster.mp4" muted="muted" loop="loop"
			width="100%" height=auto>
			<source src="poster.mp4" data-mobile-src="poster.mp4" type="video/mp4">
			<source src="prueba_video.webm" data-mobile-src="prueba_video.webm" type="video/webm">
			<source src="prueba_video.ogg" data-mobile-src="prueba_video.ogg" type="video/ogg">
		</video>

		<!--  <span style="font-size:15pt;color:white;">HOLA</span> -->
	</body>
</html>
