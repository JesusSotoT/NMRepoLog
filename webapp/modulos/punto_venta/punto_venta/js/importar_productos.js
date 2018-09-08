	
	$(document).ready (function(){
		
	});
	
	function goBack()
	{
		window.location="../views/productos/importar_productos.php";
	};
	
	function registrarProductos(ruta)
	{
		var contador_productos = $("#contador_filas").val();
		var check = new Array();
		
		for(var i=2; i<=contador_productos; i++)
		{
			if($("#chk_"+i).is(":checked"))
			{
				check.push(i);
			}
		}
		
		if(check.length < 1)
		{
			alert("No se seleccionó ningún producto a importar");
		}
		else
		{
			if (confirm('Los productos del archivo excel se importarán a su sistema. ¿Está seguro?')) 
			{
			   $.ajax(
				{
					async: false,
					url:'../funcionesBD/importar_productos.php',
					type: 'POST',
					data: {funcion: "registraProductos", ruta: ruta, check: check},
					success: function(callback)
					{	
						if(callback == 1)
						{
							alert("Los productos se importaron con éxito al sistema");
							window.location="../views/productos/importar_productos.php";
						}
					}
				});
			} 	
		}
	};
