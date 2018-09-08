$('#almacen')
	.select2({ width: '100%' });
$('#proveedor')
	.select2({ width: '100%' })
	.on("change", function(e) {
        $("#productos").empty().trigger('change');
    });
$('#productos')
	.select2({
		placeholder: "Productos",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=inventario&f=buscarProductos',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { 
                    proveedor : $('#proveedor').val(),
                    patron: params.term };
            },

            processResults: function (data) {
            	data.rows.unshift({ "id": "-1", "text": " --Todos-- "});
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
	});

$('#procesar')
	.click(function(event) {
		tipoAjuste = ($('#tipoAjuste').val() == "0") ? "recalculoexistencias" : "recalculocostoinventario";
		almacen = $('#almacen').val();
		proveedor = $('#proveedor').val();
		productos = "( ";
		$.each( $('#productos option:selected') , function(index, val) {
			if(index == 0)  productos += $(val).val();
			else productos += ( ',' + $(val).val() );
		});
		productos += " )";
		

		src = `ajax.php?c=inventario&f=${tipoAjuste}`;
		$.ajax({
			url: src,
			type: 'GET',
			data: { almacen: almacen,
					proveedor: proveedor,
					productos: productos },
		})
		.done(function(res) {
			$('#reporte').empty().append(res);
			vincularEventos();
		})
		.fail(function() {
			//console.log("error");
		})
		.always(function() {
			//console.log("complete");
		});
		
	});

function vincularEventos() {

	$('#tablaInventario').DataTable({
		dom: 'Bfrtip',
        buttons: [ 
        	{
                extend: 'excel',
                text: 'Exportar',
                exportOptions: {
                    columns: [0,1],
                }
            }
        ],

		bPaginate: false,
        language: {
            search: "Buscar:",
            zeroRecords: "No hay datos.",
            infoEmpty: "No hay datos que mostrar.",
            info:"Mostrando del _START_ Inicio al _END_ de _TOTAL_ elementos",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Último"
            }
        }
	});

	$('#barcode').keyup(function(data) { 
		let producto = "";
		if(data.key == "Enter") {
			barcode = $('#barcode').val().split(" ");
			producto = barcode[0]
			caracteristicas = barcode[1] ? barcode[1] : "0";
			console.log("PR",producto,"CAR",caracteristicas)

			let normal = $(`.p_${producto}[caracteristicas='${caracteristicas}'][lote='0']`).not("[series*=',']")
			let conSerie = $(`.p_${producto}[caracteristicas='${caracteristicas}'][series*=','][lote='0']`) 
			let conLote = $(`.p_${producto}[caracteristicas='${caracteristicas}'][lote!='0']`).not("[series*=',']")

			if ( normal.length ) {
				console.log("normal")
				normal.each(function(index, el) {
					 $(':nth-child(3)>input',  el).val( Number( $(':nth-child(3)>input',  el).val() ) + 1  ) 
					 $(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
				});
			}
			else if ( conSerie.length ) {
				console.log("con serie")
				series = []
				ajusteseries = []
				conSerie.each(function(index, el) { 
					series = JSON.stringify(  JSON.parse( $(el).attr('series') ).series   )
					ajusteseries = JSON.parse( "["+ $(el).attr('ajusteseries') +"]" )
				});
				console.log("Series:",series, "\nElegidos:",ajusteseries)
				serieElegida = prompt("Sistema: " + series + "\nElegidos:" + ajusteseries + "\nIntroduce número de serie: ")
				if( series.indexOf(serieElegida) != -1 ) {
					$(`.p_${producto}[caracteristicas='${caracteristicas}'][series*='["${serieElegida}",'][lote='0'] , .p_${producto}[caracteristicas='${caracteristicas}'][series*=',"${serieElegida}"]'][lote='0'] , .p_${producto}[caracteristicas='${caracteristicas}'][series*=',"${serieElegida}",'][lote='0'] , .p_${producto}[caracteristicas='${caracteristicas}'][series*='["${serieElegida}"]'][lote='0']`)
					.each(function(index, el) {
						serieElegida = JSON.parse( $(el).attr('series') ).series.indexOf(serieElegida) ;
						serieElegida = (JSON.parse( $(el).attr('series') ).id_series)[serieElegida];
					});
					if( ajusteseries.indexOf( Number(serieElegida) ) == -1 ) {
						$(`.p_${producto}[caracteristicas='${caracteristicas}'][series*='["${serieElegida}",'][lote='0'] , .p_${producto}[caracteristicas='${caracteristicas}'][series*=',"${serieElegida}"]'][lote='0'] , .p_${producto}[caracteristicas='${caracteristicas}'][series*=',"${serieElegida}",'][lote='0'] , .p_${producto}[caracteristicas='${caracteristicas}'][series*='["${serieElegida}"]'][lote='0']`)
						.each(function(index, el) {
							$(el).attr('ajusteseries' , $(el).attr('ajusteseries' )+","+serieElegida )
							$(':nth-child(3)>input',  el).val( Number( $(':nth-child(3)>input',  el).val() ) + 1  )
							$(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
						});
					} else {
						alert("La serie esta repetida")	
					}
					
				} else {
					alert("Elige una serie que hubiera sido previamente registrada en el sistema")			
				}
				
			}
			else if ( conLote.length ) {
				console.log("con lote")
				lotes = []
				conLote.each(function(index, el) { lotes.push( $(el).attr('no_lote') ) });
				loteElegido = prompt(lotes + "\nIntroduce número de lote: " )
				if( lotes.indexOf(loteElegido) != -1 ) {
					$(`.p_${producto}[caracteristicas='${caracteristicas}'][no_lote='${loteElegido}']`)
					.each(function(index, el) {
						$(':nth-child(3)>input',  el).val( Number( $(':nth-child(3)>input',  el).val() ) + 1  )
						$(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
					});
				} else {
					alert("Elige un lote que hubiera sido previamente registrado en el sistema")
				}
				
			}
			else console.log("Esto nunca debería ocurrir.")

			barcode = $('#barcode').val("")

		}
	});

	$('#tablaInventario tbody>tr :nth-child(3)>input')
	.change(function(event) {
		thiss = $(this).parent().parent();

		if ( thiss.attr('lote') == "0" && thiss.attr('series') == "[]" ) {
			console.log("normal")
			thiss.each(function(index, el) {
				 $(':nth-child(3)>input',  el).val( Number( $(':nth-child(3)>input',  el).val() )  ) 
				 $(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
			});
		}
		else if ( thiss.attr('series') != "[]" ) {
			console.log("con serie")
			series = []
			ajusteseries = []
			thiss.each(function(index, el) { 
				series = JSON.stringify(  JSON.parse( $(el).attr('series') ).series   )
				ajusteseries = JSON.parse( "["+ $(el).attr('ajusteseries') +"]" )
			});
			console.log("Series:",series, "\nElegidos:",ajusteseries)
			serieElegida = prompt("Sistema: " + series + "\nElegidos:" + ajusteseries + "\nIntroduce número de serie: ")
			if( series.indexOf(serieElegida) != -1 ) {
				thiss.each(function(index, el) {
						serieElegida = JSON.parse( $(el).attr('series') ).series.indexOf(serieElegida) ;
						serieElegida = (JSON.parse( $(el).attr('series') ).id_series)[serieElegida];
					});
				if( ajusteseries.indexOf( Number(serieElegida) ) == -1 ) {
					thiss.each(function(index, el) {
						$(el).attr('ajusteseries' , $(el).attr('ajusteseries' )+","+serieElegida )
						$(':nth-child(3)>input',  el).val( Number( $(el).attr('ajusteseries' ).split(",").length ) - 1  )
						$(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
					});
				} else {
					alert("La serie esta repetida")	
					thiss.each(function(index, el) {
						$(':nth-child(3)>input',  el).val( Number( $(el).attr('ajusteseries' ).split(",").length ) - 1  )
						$(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
					});
				}
				
			} else {
				alert("Elige una serie que hubiera sido previamente registrada en el sistema")	
				thiss.each(function(index, el) {
					$(':nth-child(3)>input',  el).val( Number( $(el).attr('ajusteseries' ).split(",").length ) - 1  )
					$(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
				});		
			}
			
		}
		else if ( thiss.attr('lote') != "0" ) {
			console.log("con lote")
			thiss.each(function(index, el) {
				 $(':nth-child(3)>input',  el).val( Number( $(':nth-child(3)>input',  el).val() )  ) 
				 $(':nth-child(4)>input' , el).val( $(':nth-child(3)>input' , el).val() - $(':nth-child(2)' , el).html() ) ;
			});			
		}
		else console.log("Esto nunca debería ocurrir.")
	});

	$('#realizarAjusteExistencias')
		.click(function(event) {
			ajustesInventario = [];
			$('#tablaInventario tbody>tr').each(function(index, el) {
				objTmp = {};
				ajuste = $(el).find(':nth-child(4)>input').val();
				if ( ajuste != "0" ) {
					objTmp.id = $(el).attr('id');
					objTmp.caracteristicas = $(el).attr('caracteristicas');
					objTmp.lote = $(el).attr('lote');
					objTmp.series = JSON.parse( $(el).attr('series') );
					objTmp.costo = $(el).attr('costo');
					objTmp.ajuste =  ajuste;
					objTmp.ajusteseries = JSON.parse( "["+ $(el).attr('ajusteseries') +"]" );
					objTmp.almacen = $('#almacen').val();
					ajustesInventario.push(objTmp);
				} 
			});
			$.ajax({
				url: 'ajax.php?c=inventario&f=realizarAjusteExistencias',
				type: 'POST',
				dataType: 'json',
				data: { ajustesInventario },
			})
			.done(function(response) {
				if (response = true) {
					alert("Se ha realizado el ajuste de inventario con éxito");
					window.location.reload();
				}
				else {
					alert("Podría haber ocurrido un error a realizar ajuste de inventario, verifica los ajustes realizados para comprobarlo");
					window.location.reload();
				}

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		});

	$('#realizarAjusteCostos')
		.click(function(event) {
			ajustesInventario = [];
			$('#tablaInventario tbody>tr').each(function(index, el) {
				objTmp = {};
				ajuste = $(el).find(':nth-child(4)>input').val();
				if ( ajuste != "0" ) {
					objTmp.id = $(el).attr('id');
					objTmp.caracteristicas = $(el).attr('caracteristicas');
					objTmp.lote = $(el).attr('lote');
					objTmp.series = $(el).attr('series');
					objTmp.cantidad = $(el).attr('cantidad');
					objTmp.ajuste =  ajuste;
					ajustesInventario.push(objTmp);
				} 
			});
			$.ajax({
				url: 'ajax.php?c=inventario&f=realizarAjusteCostos',
				type: 'POST',
				dataType: 'json',
				data: { ajustesInventario },
			})
			.done(function(response) {
				if (response = true) {
					alert("Se ha realizado el ajuste de inventario con éxito");
					$('#procesar').trigger('click');
				}
				else {
					alert("Podría haber ocurrido un error a realizar ajuste de inventario, verifica los ajustes realizados para comprobarlo");
					$('#procesar').trigger('click');
				}

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		});

}
