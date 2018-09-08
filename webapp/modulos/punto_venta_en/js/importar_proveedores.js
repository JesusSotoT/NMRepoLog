
	function goBack()
	{
		window.location="../views/proveedores/importar_proveedores.php";
	};
	
	function registrarProveedores(ruta)
	{
		var contador_proveedores = $("#contador_filas").val();
		var check = new Array();
		
		for(var i=2; i<=contador_proveedores ; i++)
		{
			if($("#chk_"+i).is(":checked"))
			{
				check.push(i);
			}
		}
		
		if(check.length < 1)
		{
			alert("No se seleccionó ningún proveedor a importar");
		}
		else
		{
			if (confirm('Los proveedores del archivo excel se importarán a su sistema. ¿Está seguro?')) 
			{
			   $.ajax(
				{
					async: false,
					url:'../funcionesBD/importar_proveedores.php',
					type: 'POST',
					data: {funcion: "registraProveedores", ruta: ruta, check: check},
					success: function(callback)
					{	
						if(callback == 1)
						{
							alert("Los proveedores se importaron con éxito al sistema");
							window.location="../views/proveedores/importar_proveedores.php";
						}
					}
				});
			} 	
		}
	};
