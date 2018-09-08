<?php
error_reporting(0);
?>
<html>
	<head><title></title>
		<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
		<script>
			
			function confirmar(id){
				if(confirm("Estas seguro de devolver los productos?")){
					$.ajax({
						data:{accion:'confirmar', id:id},
			       		url:'../../modulos/devoluciones/devoluciones_ajax.php',
			       		type: 'POST',
			       		success: function(callback){ 
							alert(callback)
							$(location).attr('href','repolog.php?i=55');
			   			}
					});
				}
			}
			function cancelar(id){
				if(confirm("Estas seguro de cancelar la devolucion de los productos?")){
					$.ajax({
						data:{accion:'cancelar', id:id},
			       		url:'../../modulos/devoluciones/devoluciones_ajax.php',
			       		type: 'POST',
			       		success: function(callback){ 
							alert(callback)
							$(location).attr('href','repolog.php?i=55');
			   			}
					});
				}
			}
		</script>
	</head>
	<body>
	</body>
</html>