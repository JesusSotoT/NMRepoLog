
	function goBack()
	{
		window.location="../views/clientes/importar_clientes.php";
	};
	
	function registrarClientes(ruta)
	{
		var contador_clientes = $("#contador_filas").val();
		var check = new Array();
		
		for(var i=2; i<=contador_clientes; i++)
		{
			if($("#chk_"+i).is(":checked"))
			{
				check.push(i);
			}
		}
		
		if(check.length < 1)
		{
			alert("No se seleccionó ningún cliente a importar");
		}
		else
		{
			if (confirm('Los clientes del archivo excel se importarán a su sistema. ¿Está seguro?')) 
			{
			   $.ajax(
				{
					async: false,
					url:'../funcionesBD/importar_clientes.php',
					type: 'POST',
					data: {funcion: "registraClientes", ruta: ruta, check: check},
					success: function(callback)
					{	
						if(callback == 1)
						{
							alert("Los clientes se importaron con éxito al sistema");
							window.location="../views/clientes/importar_clientes.php";
						}
					}
				});
			} 	
		}
	};
