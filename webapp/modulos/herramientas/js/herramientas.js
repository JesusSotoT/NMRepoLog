var herramientas = {
///////////////// ******** ---- 					ajax						------ ************ //////////////////
//////// Crea un ajax que retorna un json
	// Como parametros puede recibir:
		// url -> URL a donde se mandara la peticion
		// $objeto -> Array con los datos
		
	ajax : function($objeto){
		console.log('=========> objeto ajax >>>> '+$objeto.url);
		console.log($objeto);
		
		var $mensaje = '';
	
	// Valida de que tipo es el ajax
		if($objeto.json == 1){
			var $return = {};
			var tipo = 'json';
		}else{
			var $return = '';
			var tipo = 'html';
		}
		
	// Loader
		if($objeto.btn){
			var $btn = $('#'+$objeto.btn);
			$btn.button('loading');
		}
		if($objeto.div){
			$('#' + $objeto.div).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		}
		
		$.ajax({
			url : 'ajax.php?'+$objeto.url,
			type : 'POST',
			dataType : tipo,
			async : false,
			data : $objeto,
		}).done(function(resp) {
			console.log('=========> Done ajax');
			console.log(resp);
		
		// Quita el loader
			if($objeto.btn){
				$btn.button('reset');
			}
			
			$return = resp;
		}).fail(function(resp) {
			console.log('=========> !!! Fail ajax');
			console.log(resp);
			
		// Quita el loader
			if($objeto.btn){
				$btn.button('reset');
			}
			
			$mensaje = 'Error, problema interno';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
		
		return $return;
	},
	
///////////////// ******** ---- 				FIN ajax							------ ************ //////////////////

///////////////// ******** ---- 				mudar_instancia						------ ************ //////////////////
//////// Muda la informacion de una instancia vieja a una nueva
	// Como parametros puede recibir:
		// instancia_vieja -> ID de la intancia vieja
		// instancia_nueva -> ID de la intancia nueva
		// proveedores -> true si se den de mudar los proveedores, false si no
		// productos -> true si se den de mudar los productos, false si no
		// unidades -> true si se den de mudar las unidades de medida, false si no
		// btn -> Boton del loader
	
	mudar_instancia : function($objeto) {
		var $btn = $('#'+$objeto.btn);
		$btn.button('loading');
		
	// Muda la instancia despues de medio segundo
		setTimeout(function() {
			$objeto.url = 'c=herramientas&f=mudar_instancia';
			$objeto.json = 1;
			var result = herramientas.ajax($objeto);
			
			
			
			lala = result.mensaje.split('<br>');

			for (var i = 0; i < lala.length; i++) {
				dodo = lala[i].split('#');

				var clase = (dodo[0] == 's') ? 'success': 'warn';

				$.notify(dodo[1], {
					position : "top left",
					autoHide : true,
					autoHideDelay : 20000,
					className : clase,
				});
			}

			
			
			$btn.button('reset');
		}, 500);
	},

   
///////////////// ******** ---- 				FIN mudar_instancia					------ ************ //////////////////
   
}; // Fin de la clase