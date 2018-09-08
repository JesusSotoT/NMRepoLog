$(function(){
	$("#localidad_estado").change(function(){	
		$.ajax({
			url:'localidad.php',
			type:'POST',
			data: {estado:$(this).val(),operacion:'municipios'},
			success: function(callback)
			{	 
				$("#municipios").html(callback);
				$("#localidad_municipio").on( "change", function(){	
						$.ajax({
							url:'localidad.php',
							type:'POST',
							data: {municipio:$(this).val(),operacion:'ciudades'},
							success: function(callback)
							{	 
								$("#ciudades").html(callback);
								$("#localidad_ciudad").on( "change", function(){	
										$.ajax({
											url:'localidad.php',
											type:'POST',
											data: {ciudad:$(this).val(),operacion:'codigos'},
											success: function(callback)
											{	 
												$("#codigospostales").html(callback);
											}
										});	//end ajax	
								});//end change ciudad
							}
						});	//end ajax	
				});//end change municipio
			}
		});	//end ajax	
	});//end change estado
});	//function