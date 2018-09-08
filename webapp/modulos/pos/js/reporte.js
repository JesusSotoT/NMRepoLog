function buscar(){

	var desde = $('#desde').val();
	var hasta = $('#hasta').val();
	var sucursal = $('#idSucursal').val();
	var reporte = $('#reporte').val();
	var orden = $('#orden').val();

	if(reporte==1){
		VentasTotales(desde,hasta,sucursal,orden);
	}
	if(reporte==2){
		productos(desde,hasta,sucursal,orden);
	}
	if(reporte==3){
		formasDePago(desde,hasta,sucursal,orden);
	}	
	if(reporte==4){
		empleado(desde,hasta,sucursal,orden);
	}
	if(reporte==6){
		departamento(desde,hasta,sucursal,orden);
	}
	if(reporte==7){
		familia(desde,hasta,sucursal,orden);
	}	
	if(reporte==8){
		linea(desde,hasta,sucursal,orden);
	}
	
// Reporte por cliente
	if(reporte == 5){
	// Formamos el objeto
		var $objeto = {};
		$objeto['f_ini'] = desde;
		$objeto['f_fin'] = hasta;
		$objeto['sucursal'] = sucursal;
		$objeto['graficar'] = orden;
	
	// Consulta las ventas
		listar_ventas_cliente_producto($objeto);
	}
}

function productos(desde,hasta,sucursal,orden){
	mensaje('Procesando...');
	$.ajax({
		url: 'ajax.php?c=reporte&f=repProductos',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(data) {
		console.log(data);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableProductos"><thead><tr><th>Producto</th><th>Sucursal</th><th>Cantidad</th><th>Subtotal</th><th>Impuestos</th><th>Total</th></tr></thead><tbody></tbody></table>');
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableProductos').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[5,'desc' ]]
        }); 

		$.each(data.productos, function(index, val) {
			                 
			                x ='<tr class="filas">'+
                                '<td>'+val.label+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                                '<td>'+val.value+'</td>'+
                                '<td>$'+parseFloat(val.subtotal).toFixed(2)+'</td>'+
                                '<td>$'+parseFloat(val.impuestos).toFixed(2)+'</td>'+
                                '<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                '</tr>';  
                            //totalLabel += parseFloat(val.total);     
                    table.row.add($(x)).draw(); 
		});
		
		$.each(data.ventasTotal, function(index, val) {  
            totalLabel += parseFloat(val.monto);     
		});

		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+totalLabel.toFixed(2));
		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: data.dona
        });


        Morris.Line({
          element: 'gLine',
          resize: true,
          data: data.linea,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Vendido $']
        });
		
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function VentasTotales(desde,hasta,sucursal,orden){

}
function formasDePago(desde,hasta,sucursal,orden){
	mensaje('Procesando...');
	$.ajax({
		url: 'ajax.php?c=reporte&f=repFormaDePago',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(resp) {
		console.log(resp);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableFP"><thead><tr><th>Forma de Pago</th><th>Total</th><th>Sucursal</th></tr></thead><tbody></tbody></table>');
	
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableFP').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[2,'desc' ]]
        }); 	
		$.each(resp.formasPago, function(index, val) {
			                x ='<tr class="filas">'+
                                '<td>'+val.label+'</td>'+
                                '<td>$'+parseFloat(val.value).toFixed(2)+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                               // '<td>$'++'</td>'+
                                //'<td>$'+parseFloat(val.impuestos).toFixed(2)+'</td>'+
                                //'<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                '</tr>';  
                            //totalLabel += parseFloat(val.value); 
                            //$('#tableFP tr:last').after(x);    
                    table.row.add($(x)).draw(); 
		});
		$.each(resp.ventasTotal, function(index, val) {  
            totalLabel += parseFloat(val.monto);     
		});
		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+totalLabel.toFixed(2));
		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.dona
        });


        Morris.Line({
          element: 'gLine',
          resize: true,
          data: resp.linea,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Vendido $']
        }); 
		 
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	


}
function empleado(desde,hasta,sucursal,orden){
	mensaje('Procesando...');

	$.ajax({
		url: 'ajax.php?c=reporte&f=repEmpleadoVenta',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(resp) {
		console.log(resp);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableEmple"><thead><tr><th>Empleado</th><th>Total</th></tr></thead><tbody></tbody></table>');
		
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableEmple').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[1,'desc' ]]
        }); 	
		$.each(resp.empleadoVenta, function(index, val) {
			                x ='<tr class="filas">'+
                                '<td>'+val.label+'</td>'+
                                '<td>$'+parseFloat(val.value).toFixed(2)+'</td>'+
                               // '<td>$'++'</td>'+
                                //'<td>$'+parseFloat(val.impuestos).toFixed(2)+'</td>'+
                                //'<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                '</tr>';  
                            //totalLabel += parseFloat(val.value); 
                            //$('#tableFP tr:last').after(x);    
                    table.row.add($(x)).draw(); 
		});
		$.each(resp.ventasTotal, function(index, val) {  
            totalLabel += parseFloat(val.monto);     
		}); 
		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+totalLabel.toFixed(2));

		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.dona
        });


        Morris.Line({
          element: 'gLine',
          resize: true,
          data: resp.linea,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Vendido $']
        }); 
		 
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	

}

function departamento(desde,hasta,sucursal,orden){
	mensaje('Procesando...');

	$.ajax({
		url: 'ajax.php?c=reporte&f=repDepartamento',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(resp) {
		console.log(resp);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableEmple"><thead><tr><th>Departamento</th><th>Total</th></tr></thead><tbody></tbody></table>');
		
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableEmple').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[1,'desc' ]]
        }); 	
         total=0.0;
		$.each(resp.rows, function(index, val) {
	                x ='<tr class="filas">'+
                        '<td>'+val.label+'</td>'+
                        '<td>$'+parseFloat(val.value).toFixed(2)+'</td>'+
                        '</tr>';   
                    table.row.add($(x)).draw(); 
                    total+=parseFloat(val.value);
		});
		
		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+ total.toFixed(2));

		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.rows,
        });


       Morris.Bar({
          element: 'gLine',
          resize: true,
          data: resp.rows,
          xkey: 'label',
          ykeys: ['value'],
          labels: ['Vendido $']
        }); 
		 
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	
}

function familia(desde,hasta,sucursal,orden){
	mensaje('Procesando...');

	$.ajax({
		url: 'ajax.php?c=reporte&f=repFamilia',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(resp) {
		console.log(resp);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableEmple"><thead><tr><th>Familia</th><th>Total</th></tr></thead><tbody></tbody></table>');
		
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableEmple').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[1,'desc' ]]
        }); 	
         total=0.0;
		$.each(resp.rows, function(index, val) {
	                x ='<tr class="filas">'+
                        '<td>'+val.label+'</td>'+
                        '<td>$'+parseFloat(val.value).toFixed(2)+'</td>'+
                        '</tr>';   
                    table.row.add($(x)).draw(); 
                    total+=parseFloat(val.value);
		});
		
		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+ total.toFixed(2));

		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.rows,
        });


       Morris.Bar({
          element: 'gLine',
          resize: true,
          data: resp.rows,
          xkey: 'label',
          ykeys: ['value'],
          labels: ['Vendido $']
        }); 
		 
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	
}

function linea(desde,hasta,sucursal,orden){
	mensaje('Procesando...');

	$.ajax({
		url: 'ajax.php?c=reporte&f=repLinea',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(resp) {
		console.log(resp);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableEmple"><thead><tr><th>Linea</th><th>Total</th></tr></thead><tbody></tbody></table>');
		
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableEmple').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[1,'desc' ]]
        }); 	
         total=0.0;
		$.each(resp.rows, function(index, val) {
	                x ='<tr class="filas">'+
                        '<td>'+val.label+'</td>'+
                        '<td>$'+parseFloat(val.value).toFixed(2)+'</td>'+
                        '</tr>';   
                    table.row.add($(x)).draw(); 
                    total+=parseFloat(val.value);
		});
		
		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+ total.toFixed(2));

		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.rows,
        });


       Morris.Bar({
          element: 'gLine',
          resize: true,
          data: resp.rows,
          xkey: 'label',
          ykeys: ['value'],
          labels: ['Vendido $']
        }); 
		 
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	
}

/*
function familia(desde,hasta,sucursal,orden){
	mensaje('Procesando...');

	$.ajax({
		url: 'ajax.php?c=reporte&f=repFamilia',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(resp) {
		console.log(resp);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableEmple"><thead><tr><th>Familia</th><th>Total</th></tr></thead><tbody></tbody></table>');
		
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableEmple').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[1,'desc' ]]
        }); 	
         total=0.0;
		$.each(resp.rows, function(index, val) {
	                x ='<tr class="filas">'+
                        '<td>'+val.label+'</td>'+
                        '<td>$'+parseFloat(val.value).toFixed(2)+'</td>'+
                        '</tr>';   
                    table.row.add($(x)).draw(); 
                    total+=parseFloat(val.value);
		});
		
		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+ total.toFixed(2));

		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.rows
        });


       Morris.Line({
          element: 'gLine',
          resize: true,
          data: resp.rows,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Vendido $']
        });
		 
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	
}

function linea(desde,hasta,sucursal,orden) {
	mensaje('Procesando...');

	$.ajax({
		url: 'ajax.php?c=reporte&f=repLinea',
		type: 'POST',
		dataType: 'json',
		data: {desde: desde,
				hasta: hasta,
				sucursal: sucursal,
				orden: orden
			},
	})
	.done(function(resp) {
		console.log(resp);
		$('#tableDivCont').empty();

		$('#tableDivCont').append('<table class="table table-bordered table-hover" id="tableEmple"><thead><tr><th>Linea</th><th>Total</th></tr></thead><tbody></tbody></table>');
		
		var x = '';
		var totalLabel = 0;
		var table =  $('#tableEmple').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar:",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[1,'desc' ]]
        }); 	
         total=0.0;
		$.each(resp.rows, function(index, val) {
	                x ='<tr class="filas">'+
                        '<td>'+val.label+'</td>'+
                        '<td>$'+parseFloat(val.value).toFixed(2)+'</td>'+
                        '</tr>';   
                    table.row.add($(x)).draw(); 
                    total+=parseFloat(val.value);
		});
		
		$('#gDonut').empty();
		$('#gLine').empty();
		$('#montoTotalLabel').text('$'+ total.toFixed(2));

		Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.rows
        });


       Morris.Line({
          element: 'gLine',
          resize: true,
          data: resp.rows,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Vendido $']
        }); 
		 
		eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	
}*/

    function mensaje(mensaje) {

        $('#lblMensajeEstado').text(mensaje);
        $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
    }    
    function eliminaMensaje() {

        $('#modalMensajes').modal('hide');
    }
   
///////////////// ******** ---- 			listar_ventas_cliente_producto			------ ************ //////////////////
//////// Lista las ventas del cliente por los productos
	// Como parametros puede recibir:
		// f_ini -> Fecha de inicio
		// f_fin -> Fecha final
		// sucursal -> ID de las sucursal
		// graficar -> 1 -> dia, 2 -> semana, 3 -> mes, 4 -> año
				
	function listar_ventas_cliente_producto($objeto) {
		console.log('--------> Objet listar_ventas_cliente_producto');
		console.log($objeto);
	
	// Loader
		mensaje('Procesando...');
	
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=reporte&f=listar_ventas_cliente_producto',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done listar_ventas_cliente_producto');
			console.log(resp);
		
		// Quita el loader
			eliminaMensaje();
		
		// Limpia el contenedor
			$('#tableDivCont').empty();
		
		// Valida que exista informacion
			if(resp['status'] == 2){
				var contenido = '<div align="center">'+
									'<h3>'+
										'<span class="label label-default">'+
											'* No se detecto informacion *'+
										'</span>'+
									'</h3>'+
								'</div>';
				$('#tableDivCont').append(contenido);
				
				return 0;
			}
			
		// Crea una nueva tabla
			var tabla = '<table class="table table-bordered table-hover" id="tabla_cliente_producto">'+
							'<thead>'+
								'<tr>'+
									'<th>Cliente</th>'+
									'<th>Producto</th>'+
									'<th>Cantidad</th>'+
									'<th>Sucursal</th>'+
								'</tr>'+
							'</thead>'+
							'<tbody></tbody>'+
						'</table>';
			$('#tableDivCont').append(tabla);
			
			var table = $('#tabla_cliente_producto').DataTable({
							dom : 'Bfrtip',
							buttons : ['excel'],
							language : {
								search : "Buscar:",
								lengthMenu : "",
								zeroRecords : "No hay datos.",
								infoEmpty : "No hay datos que mostrar.",
								info : "Mostrando del _START_ al _END_ de _TOTAL_ elementos",
								paginate : {
									first : "Primero",
									previous : "Anterior",
									next : "Siguiente",
									last : "Último"
								},
							}
						});
			
			var totalLabel = 0;
			var tr = '';
			$.each(resp['result'], function(index, val) {
				totalLabel += parseFloat(val.monto);
				var precio = parseFloat(val['monto']);
				precio.toFixed(2);
				
				tr = '<tr class="filas">' + 
						'<td>' + val['nombre'] + '</td>' +
						'<td>' + val['producto'] + '</td>' +
						'<td>' + val['cantidad'] + '</td>' +
						'<td>' + val['sucursal'] + '</td>' +
					'</tr>';
				table.row.add($(tr)).draw();
			});
			$('#gDonut').empty();
			$('#gLine').empty();
			$('#montoTotalLabel').text('$' + totalLabel.toFixed(2));
	
			Morris.Donut({
				element : 'gDonut',
				resize : true,
				data : resp.dona,
				formatter: function (y, data) {
					return '$' + y;
				}
			});
	
			Morris.Line({
				element : 'gLine',
				resize : true,
				data : resp.lineal,
				xkey : 'fecha',
				ykeys : ['ventas'],
				labels : ['Vendido $']
			});
		}).fail(function(resp) {
			console.log('=========> Fail listar_ventas_cliente_producto');
			console.log(resp);
			
		// Quita el loader
			eliminaMensaje();
			
			var $mensaje = 'Error al consultar las ventas';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	}
			
///////////////// ******** ---- 			FIN listar_ventas_cliente_producto		------ ************ //////////////////
