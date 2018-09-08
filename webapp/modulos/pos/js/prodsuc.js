function esignaSuc(id){

	$.ajax({
		url: 'ajax.php?c=producto&f=getSucuPro',
		type: 'POST',
		dataType: 'json',
		data: {idProducto: id},
	})
	.done(function(res) {
		console.log(res);
		$('#modalSuc').modal();
		$('#modal-labelPr').text(res.producto);
		$('#idProModal').val(res.idP);
		var table = $('#tableSuc').DataTable();
		table.clear().draw();
        var y = '';
		$.each(res.sucursales, function(index, val) {
			y = '<tr>'+
                '<td>'+val.idSuc+'</td>'+
                '<td>'+val.nombre+'</td>'+
                '<td><button onclick="eliminaSucu('+val.idSuc+');" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></button></td>'+
                '<tr>';

			table.row.add($(y)).draw();

		});
	})
	.fail(function() {
		console.log("error");

	})
	.always(function() {
		console.log("complete");
	});
	
}
function vinculaSucursal(){
	var idProducto = $('#idProModal').val();
	var sucursal = $('#sucAdd').val();

	$.ajax({
		url: 'ajax.php?c=producto&f=agregaAsucursal',
		type: 'POST',
		dataType: 'json',
		data: {idProducto:idProducto,
			sucursal: sucursal
		 },
	})
	.done(function(result) {
		console.log(result);
		

		if(result.estatus==true){
			alert('Se vinculo tu producto correctamente.');
			window.location.reload();
		}else{
			alert('El producto ya se encuetra relacionado a esa Sucursal.');
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	

}

function eliminaSucu(idSuc){

	var r = confirm("Deseas eiminar la vinculacion?");
	if (r == true) {
		var idProducto = $('#idProModal').val();
		$('#modalMensajes').modal();
	    $.ajax({
	    	url: 'ajax.php?c=producto&f=eliminaVinculacion',
	    	type: 'POST',
	    	dataType: 'json',
	    	data: {idSuc: idSuc,
	    		idProducto:idProducto
	    	},
	    })
	    .done(function(resp) {
	    	console.log(resp);
		    if(resp.estatus==true){
		    	$('#modalMensajes').modal('hide');
				alert('Se elimino correctamente.');
				window.location.reload();
			}else{
				$('#modalMensajes').modal('hide');
				alert('Ocurrio un error - 1400');
			}
	    })
	    .fail(function() {
	    	console.log("error");
	    })
	    .always(function() {
	    	console.log("complete");
	    });
	    
	} else {
	    txt = "You pressed Cancel!";
	} 
}
function allPro(){
		var oTable = $('#table1').dataTable();
    	var allPages = oTable.fnGetNodes();
    	var sucursal= $('#sucursal').val();
    	var monedero = $('#monedero').val();
    	
    	$('#modalMensajes').modal();
		cadena='';
		$('input:checked', allPages).each(function(){
            cadena+=$(this,allPages).val()+',';
        });

        $.ajax({
        	url: 'ajax.php?c=producto&f=vinculacionMasiva',
        	type: 'POST',
        	dataType: 'json',
        	data: {cadena: cadena,
        			sucursal:sucursal },
        })
        .done(function(res) {
        	console.log(res);
        	if(res.estatus==true){
        		$('#modalMensajes').modal('hide');
        		alert('Se vincularon correctamente.');
        		window.location.reload();
        	}
        })
        .fail(function() {
        	console.log("error");
        })
        .always(function() {
        	console.log("complete");
        });
        
}
function vinculaMonedero(){
        var oTable = $('#table1').dataTable();
        var allPages = oTable.fnGetNodes();
        
        var monedero = $('#monedero').val();
        
        $('#modalMensajes').modal();
        cadena='';
        $('input:checked', allPages).each(function(){
            cadena+=$(this,allPages).val()+',';
        });

        $.ajax({
            url: 'ajax.php?c=producto&f=vinculacionMasivaMonedero',
            type: 'POST',
            dataType: 'json',
            data: {cadena: cadena,
                    monedero:monedero },
        })
        .done(function(res) {
            console.log(res);
            if(res.estatus==true){
                $('#modalMensajes').modal('hide');
                alert('Se vincularon correctamente.');
                window.location.reload();
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
}
function sellAll(){
	var oTable = $('#table1').dataTable();
    var allPages = oTable.fnGetNodes();

    if ($('.checkPro',allPages).is(":checked")) {
    	$('.checkPro',allPages).prop('checked', false);
    	aaa();
    }else{
    	$('.checkPro',allPages).prop('checked', true);
    	aaa();
    }
}
function listaSucu(){

	var sucursal = $('#sucursal').val();
	$('#modalMensajes').modal();
	$.ajax({
		url: 'ajax.php?c=producto&f=filtraProds',
		type: 'post',
		data: {sucursal: sucursal},
	})
	.done(function(resp) {
		console.log(resp);
		
		var table = $('#table1').DataTable();
		table.destroy();
		$('#table1body').html(resp);
		setTimeout(function(){ 
			$('#modalMensajes').modal('hide');
			$('#table1').DataTable(); 
		}, 3000);


	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function filtra(){

	$.ajax({
		url: 'ajax.php?c=producto&f=filtraProds',
		type: 'POST',
		dataType: 'HTML',
		data: {sucursal: $('#sucursal').val()},
	})
	.done(function(resp) {
		console.log(resp);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function satTipo(){
    $.ajax({
        url: 'ajax.php?c=producto&f=divisionesSat',
        type: 'POST',
        dataType: 'json',
        data: {tipo: $('#tipoProdSat').val()},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        //alert('kekeeioe');
        $('#divisionSat').empty();
        $('#divisionSat').append('<option value="0">-Selecciona-</option>');

        $('#grupoSat').empty();
        $('#grupoSat').append('<option value="0">-Selecciona-</option>');

        $('#claseSat').empty();
        $('#claseSat').append('<option value="0">-Selecciona-</option>');

        $('#claveSat').empty();
        $('#claveSat').append('<option value="0">-Selecciona-</option>');
        $('#satCl67').val('');
        $.each(resDiv.divisiones, function(index, val) {
           $('#divisionSat').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#divisionSat').select2({width:'100%'});
        $('#grupoSat').select2({width:'100%'});
        $('#claseSat').select2({width:'100%'});
        $('#claveSat').select2({width:'100%'});


    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function satGrupo(){
    $.ajax({
        url: 'ajax.php?c=producto&f=gruposSat',
        type: 'POST',
        dataType: 'json',
        data: {division: $('#divisionSat').val()},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        $('#grupoSat').empty();
        $('#grupoSat').append('<option value="0">-Selecciona-</option>');
        $.each(resDiv.grupos, function(index, val) {
           $('#grupoSat').append('<option value="'+val.id+'" id_grupo="'+val.id_grupo+'">'+val.nombre+'</option>');
        });
        $('#grupoSat').select2({width:'100%'});

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function satClase(){
    $.ajax({
        url: 'ajax.php?c=producto&f=claseSat',
        type: 'POST',
        dataType: 'json',
        data: {grupo: $('#grupoSat').val()},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        $('#claseSat').empty();
        $('#claseSat').append('<option value="0">-Selecciona-</option>');
        $.each(resDiv.clases, function(index, val) {
           $('#claseSat').append('<option value="'+val.id+'" id_clase="'+val.id_clase+'">'+val.nombre+'</option>');
        });
        $('#claseSat').select2({width:'100%'});

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function satClave(){
    $.ajax({
        url: 'ajax.php?c=producto&f=claveSat',
        type: 'POST',
        dataType: 'json',
        data: {clase: $('#claseSat').val()},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        $('#claveSat').empty();
        $('#claveSat').append('<option value="0">-Selecciona-</option>');
        $.each(resDiv.claves, function(index, val) {
           $('#claveSat').append('<option value="'+val.c_claveprodserv+'" desclave="'+val.c_claveprodserv+'">'+val.c_claveprodserv+' / '+val.descripcion+'</option>');
        });
        $('#claveSat').select2({width:'100%'});

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function csat(){
	var ala = $('#claveSat').val();

	var desc  = $( "#claveSat option:selected" ).attr( "desclave" );
	$('#satCl67').val(desc)
}
function vinculaClave(){
		var oTable = $('#table1').dataTable();
    	var allPages = oTable.fnGetNodes();
    
    	var idDivision = $('#divisionSat').val();
    	var idGrupo = $( "#grupoSat" ).val();
    	var idClase = $( "#claseSat" ).val();
    	var clave = $('#claveSat').val();

        if($('#generica').is(':checked')){
            clave = '01010101';
        }
        if(clave!= '01010101'){
            if(clave == '0'){
                alert('No tienes Clave vinculada.');
                return false;
            }
            if(idDivision == '0'){
                alert('No tienes Division vinculada.');
                return false;
            }
            if(idGrupo == '0'){
                alert('No tienes Grupo vinculado.');
                return false;
            }
            if(idClase == '0'){
                alert('No tienes Clase vinculada.');
                return false;
            }
        }


    	//alert('Te queremos Sandy te queremos!!!');
    	
		cadena='';
		$('input:checked', allPages).each(function(){
            cadena+=$(this,allPages).val()+',';
        });
        if(cadena == ''){
            alert('No has seleccionado ningun producto.');
            return false;
        }
   
     


        $('#modalMensajes').modal();
        $.ajax({
        	url: 'ajax.php?c=producto&f=vinculacionMasivaSat',
        	type: 'POST',
        	dataType: 'json',
        	data: {cadena: cadena,
        			clave:clave,
        			division : idDivision,
        			 grupo : idGrupo,
        			 clase : idClase
        			},
        })
        .done(function(res) {
        	console.log(res);
        	if(res.estatus==true){
        		$('#modalMensajes').modal('hide');
        		alert('Se vincularon correctamente.');
        		window.location.reload();
        	}
        })
        .fail(function() {
        	console.log("error");
        })
        .always(function() {
        	console.log("complete");
        });

}

function buscaFam(){

	 $.ajax({
        url: 'ajax.php?c=producto&f=prodDepa',
        type: 'POST',
        dataType: 'json',
        data: {depa: $('#departamento').val(),
    			familia:$('#familia').val(),
    			linea:$('#linea').val()
    			},
    })
    .done(function(data) {
       
        console.log(data);
            var table = $('#table1').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            $.each(data.productos, function(index, val) {
            	if(val.departamento===null){
            		val.departamento = '';
            	}
            	if(val.familia===null){
            		val.familia='';
            	}
            	if(val.linea===null){
            		val.linea= '';
            	}
            	if(val.clave_sat===null){
            		val.clave_sat = '';
            	}
                x ='<tr class="filas">'+
                                '<td><input class="checkPro" value="'+val.id+'" type="checkbox"></td>'+
                                '<td>'+val.id+'</td>'+
                                '<td>'+val.codigo+'</td>'+
                                '<td>'+val.nombre+'</td>'+
                                '<td>'+val.departamento+'</td>'+
                                '<td>'+val.familia+'</td>'+
                                '<td>'+val.linea+'</td>'+
                                '<td>'+val.clave_sat+'</td>'+
                                //'<td></td>'+
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            });         
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });

    $.ajax({
        url: 'ajax.php?c=producto&f=buscaFam',
        type: 'POST',
        dataType: 'json',
        data: {dep: $('#departamento').val()},
    })
    .done(function(resp1) {
        console.log(resp1);
        $('#familia').empty();
        $.each(resp1, function(index, val) {
           $('#familia').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#familia').select2({width:'100%'});


    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
} 
function buscaLinea(){
	 $.ajax({
        url: 'ajax.php?c=producto&f=prodDepa',
        type: 'POST',
        dataType: 'json',
        data: {depa: $('#departamento').val(),
    			familia:$('#familia').val(),
    			linea:$('#linea').val()
    			},
    })
    .done(function(data) {
       
        console.log(data);
            var table = $('#table1').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            $.each(data.productos, function(index, val) {
            	if(val.departamento===null){
            		val.departamento = '';
            	}
            	if(val.familia===null){
            		val.familia='';
            	}
            	if(val.linea===null){
            		val.linea= '';
            	}
            	if(val.clave_sat===null){
            		val.clave_sat = '';
            	}
                x ='<tr class="filas">'+
                                '<td><input class="checkPro" value="'+val.id+'" type="checkbox"></td>'+
                                '<td>'+val.id+'</td>'+
                                '<td>'+val.codigo+'</td>'+
                                '<td>'+val.nombre+'</td>'+
                                '<td>'+val.departamento+'</td>'+
                                '<td>'+val.familia+'</td>'+
                                '<td>'+val.linea+'</td>'+
                                '<td>'+val.clave_sat+'</td>'+
                               // '<td></td>'+
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            });         
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });

	$.ajax({
        url: 'ajax.php?c=producto&f=prodDepa',
        type: 'POST',
        dataType: 'json',
        data: {depa: $('#departamento').val(),
    			familia:$('#familia').val(),
    			linea:$('#linea').val()
    			},
    })
    .done(function(data) {
       
        console.log(data);
            var table = $('#table1').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            $.each(data.productos, function(index, val) {
            	if(val.departamento===null){
            		val.departamento = '';
            	}
            	if(val.familia===null){
            		val.familia='';
            	}
            	if(val.linea===null){
            		val.linea= '';
            	}
            	if(val.clave_sat===null){
            		val.clave_sat = '';
            	}
                x ='<tr class="filas">'+
                                '<td><input class="checkPro" value="'+val.id+'" type="checkbox"></td>'+
                                '<td>'+val.id+'</td>'+
                                '<td>'+val.codigo+'</td>'+
                                '<td>'+val.nombre+'</td>'+
                                '<td>'+val.departamento+'</td>'+
                                '<td>'+val.familia+'</td>'+
                                '<td>'+val.linea+'</td>'+
                                '<td>'+val.clave_sat+'</td>'+
                                //'<td></td>'+
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            });         
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
    $.ajax({
        url: 'ajax.php?c=producto&f=buscaLinea',
        type: 'POST',
        dataType: 'json',
        data: {fam: $('#familia').val()},
    })
    .done(function(resp1) {
        console.log(resp1);
        $('#linea').empty();
        $.each(resp1, function(index, val) {
           $('#linea').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#linea').select2({width:'100%'});

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
} 
function final(){
	 $.ajax({
        url: 'ajax.php?c=producto&f=prodDepa',
        type: 'POST',
        dataType: 'json',
        data: {depa: $('#departamento').val(),
    			familia:$('#familia').val(),
    			linea:$('#linea').val()
    			},
    })
    .done(function(data) {
       
        console.log(data);
            var table = $('#table1').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            $.each(data.productos, function(index, val) {
            	if(val.departamento===null){
            		val.departamento = '';
            	}
            	if(val.familia===null){
            		val.familia='';
            	}
            	if(val.linea===null){
            		val.linea= '';
            	}
            	if(val.clave_sat===null){
            		val.clave_sat = '';
            	}
                x ='<tr class="filas">'+
                                '<td><input class="checkPro" value="'+val.id+'" type="checkbox"></td>'+
                                '<td>'+val.id+'</td>'+
                                '<td>'+val.codigo+'</td>'+
                                '<td>'+val.nombre+'</td>'+
                                '<td>'+val.departamento+'</td>'+
                                '<td>'+val.familia+'</td>'+
                                '<td>'+val.linea+'</td>'+
                                '<td>'+val.clave_sat+'</td>'+
                               // '<td></td>'+
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            });         
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });

}





