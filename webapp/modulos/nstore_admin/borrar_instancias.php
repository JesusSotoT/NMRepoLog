<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript">
			function empieza(valor){
				if (confirm("¿Está seguro de borrar la instancia " + valor + "?") == true) {
					$.ajax({
						type: "POST",
						async: true,
						data: {instancia:valor},
						url: "borrar_instancias/borrar_instancias.php",
            			success:function(response){
            				$("#regcustomer_placeholder").html(response);
            			},
            			error:function(){
            				alert("No se pudo cargar");
            			}
            		});
				}
				else {
					alert("Operacion cancelada");
				}
					
					
			}
		</script>
	</head>
	<body>
		<div id="regcustomer_placeholder"></div>
		
		<div id="customer_placeholder">
			<a href="#" onclick="empieza(4966)">Presione aquí</a>
		</div>
	</body>
</html>