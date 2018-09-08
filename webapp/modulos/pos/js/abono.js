function formAbono(){
	
	 $('#modalformAbono').modal({
            show:true,
        });
}
function abona(){
	var cliente = $('#cliente').val();
	var importe = $('#cantidad').val();
	var concepto = $('#concepto').val();
	var formaPago = $('#formaPago').val();
	var moneda = $('#moneda').val();
	var cargo = $('#cargos').val();

	if(importe =='' || importe < 0){
		alert('Tienes que ingresar un importe mayo a cero.');
		return false;
	}
	if(concepto==''){
		alert('Tienes que agregar un concepto.');
		return false;
	}

	if(cliente > 0){
		if(cargo > 0){
			alert('Debes de seleccionar un cargo al cual se le aplicar el abono.');
		}
	}
	$.ajax({
		url: 'ajax.php?c=retiro&f=agregaAbono',
		type: 'post',
		dataType: 'json',
		data: {cliente: cliente,
			   importe: importe,
			   concepto: concepto,
			   formaPago: formaPago,
			   moneda: moneda,
			   cargo: cargo,
		},
	})
	.done(function(data) {

		alert('Se realizo el abono satisfactoriamente.');
		window.location.reload();

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function buscaCargos(){
	var cliente = $('#cliente').val();

	$.ajax({
		url: 'ajax.php?c=retiro&f=buscaCargos',
		type: 'POST',
		dataType: 'json',
		data: {cliente: cliente},
	})
	.done(function(data) {
		console.log(data);
		 $('#cargos').empty();
		$.each(data, function(index, val) {
			$("#cargos").append('<option value="'+val.id+'">'+val.concepto+'</option>');
		}); 

		$('#cargos').select2({width:'100%'});
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}