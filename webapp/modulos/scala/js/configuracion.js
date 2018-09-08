function newapp(){
	$("#app_nombre,#app_desc").val('');
	$("#app_solucion").val(1);
	$('#modal_add_guia').modal('show');
}

function newsp(spaso){

	$(".subpaso").val('');
	$("#menuAc").val(1);
	$('#btneditact').hide();
	$('#btnsaveact').show();
	$('#menuAc').html('');
	$.ajax({
        url: 'ajax.php?c=configuracion&f=select_menu',
        type: 'post',
        dataType: 'json'
    })
    .done(function(data) {
    	$.each(data, function(index, val) {
    		$('#menuAc').append('<option value="'+val.idmenu+'">'+val.nombre+'</option>');
    	});
    });
  
	
	if(spaso == 1){ var idpasoR = $("#idpaso1R").val(); var idpasoA = 1;}
	if(spaso == 2){ var idpasoR = $("#idpaso2R").val(); var idpasoA = 2;}
	if(spaso == 3){ var idpasoR = $("#idpaso3R").val(); var idpasoA = 3;}
	if(spaso == 4){ var idpasoR = $("#idpaso4R").val(); var idpasoA = 4;}
	if(spaso == 5){ var idpasoR = $("#idpaso5R").val(); var idpasoA = 5;}
	if(idpasoR == ''){
		alert('Debe primer guardar el paso');
		return false;
	}
	$('#modal_add_actividad').modal('show');
	$("#idpasoR").val(idpasoR);
	$("#idpasoA").val(idpasoA);
}
function edit_act(id_act,idpasoR,idpasoA){
	
	$('#btneditact').show();
	$('#btnsaveact').hide();

	//alert(id_act+' '+idpasoR+' '+idpasoA);

	$("#idpasoA").val(idpasoA);
	$("#idpasoR").val(idpasoR);
	$("#id_act").val(id_act);

	if(idpasoA == 1){ var idpasoR = $("#idpaso1R").val(); var idpasoA = 1; }
	if(idpasoA == 2){ var idpasoR = $("#idpaso2R").val(); var idpasoA = 2;}
	if(idpasoA == 3){ var idpasoR = $("#idpaso3R").val(); var idpasoA = 3;}
	if(idpasoA == 4){ var idpasoR = $("#idpaso4R").val(); var idpasoA = 4;}
	if(idpasoA == 5){ var idpasoR = $("#idpaso5R").val(); var idpasoA = 5;}
	

	var menu = 0;
	$("#nombreAc,#descAc,#linkAc").val('');
	$("#opcionalAC").prop('checked', false);
	$.ajax({
		url: 'ajax.php?c=configuracion&f=datos_act',
		type: 'post',
		dataType: 'json',
		data:{id_act:id_act}
	})
	.done(function(data) {
		$("#nombreAc").val(data[0]['nombre']);
		$("#descAc").val(data[0]['desc_larga']);
		$("#linkAc").val(data[0]['link']);
		menu = data[0]['menu'];
		if(data[0]['opcional'] == 1){
			$("#opcionalAC").prop('checked', true);
		}else{
			$("#opcionalAC").prop('checked', false);
		}

		$('#menuAc').html('');
		$.ajax({
	        url: 'ajax.php?c=configuracion&f=select_menu',
	        type: 'post',
	        dataType: 'json'
	    })
	    .done(function(data) {
	    	$.each(data, function(index, val) {
	    		if(val.idmenu == menu){
	    			$('#menuAc').append('<option selected="selected" value="'+val.idmenu+'">'+val.nombre+'</option>').trigger('change');
	    		}else{
	    			$('#menuAc').append('<option value="'+val.idmenu+'">'+val.nombre+'</option>');
	    		}
	    		
	    	});
	    });


	});
	$('#modal_add_actividad').modal('show');	
}

function edit_actividad(){


	var id_app= $("#in_idapp").val();
	var idpasoA = $("#idpasoA").val();
	var idpasoR = $("#idpasoR").val();
	var id_act = $("#id_act").val();
	var nombreAc = $("#nombreAc").val();
	var menuAc = $("#menuAc").val();
	var descAc = $("#descAc").val();
	var linkAc = $("#linkAc").val();
	var idtable = 'p'+idpasoA+'table';
	if( $('#opcionalAC').is(':checked') ) {
	    var opcionalAC = 1;
	}else{
		var opcionalAC = 0;
	}
	//alert(id_act+' '+id_app+' '+idpasoR+' '+nombreAc+' '+menuAc+' '+descAc+' '+linkAc+' '+opcionalAC);

	$.ajax({
        url: 'ajax.php?c=configuracion&f=edit_actividad',
        type: 'post',
        dataType: 'html',
        data:{nombre:nombreAc,menu:menuAc,desc:descAc,link:linkAc,opcional:opcionalAC,estatus:1,idpasoR:idpasoR,id_act:id_act}
    })
    .done(function(data) {
    	$('#modal_add_actividad').modal('hide');
    	if(data != true){
    		console.log('Error en el registro');
    	}
    	reload_actividades(id_app,idpasoR,idtable,idpasoA);
    });


}


function reload_actividades(id_app,id_paso,idtable,idpasoA){
		$.ajax({
			        url: 'ajax.php?c=configuracion&f=reload_actividades',
			        type: 'post',
			        dataType: 'json',
			        data:{id_app:id_app,id_paso:id_paso}
			    })
			    .done(function(data) {
			    	var table = $('#'+idtable+'').DataTable( {dom: 'Bfrtip',                                                            
                                                            destroy: true,
                                                            searching: true,
                                                            paginate: true,
                                                            filter: true,
                                                            sort: true,
                                                            info: true,
                                                            language: {                                                                                                                                   
	                                                            search: "Buscar:",
	                                                            lengthMenu:"Mostrar _MENU_ elementos",
	                                                            zeroRecords: "No hay datos.",
	                                                            infoEmpty: "No hay datos que mostrar.",
	                                                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
	                                                            paginate: {
	                                                                        first:      "Primero",
	                                                                        previous:   "Anterior",
	                                                                        next:       "Siguiente",
	                                                                        last:       "Último"
	                                                                    }
                                                            },
                                    			});
			    	table.clear().draw();
			        var x ='';
			        var estatus ='';
			        $.each(data, function(index, val) {

			        	if(val.estatus == 1 || val.estatus == 2){
			        		estatus = '<a onclick="estatus_act('+val.id_actividad+',0,'+id_app+','+id_paso+','+idpasoA+');"> Desactivar</a></td>';
			        	}else{
			        		estatus = '<a onclick="estatus_act('+val.id_actividad+',1,'+id_app+','+id_paso+','+idpasoA+');"> Activar</a></td>';
			        	}

			        	x ='<tr>'+
			                    '<td>'+val.nombre+'</td>'+
			                    '<td>'+val.menu+'</td>'+			                    
			                    '<td>'+val.desc_larga+'</td>'+
			                    '<td>'+val.link+'</td>'+
			                    '<td>'+val.opcional+'</td>'+
			                    '<td> <a onclick="edit_act('+val.id_actividad+','+id_paso+','+idpasoA+');"> Editar</a> '+estatus+'</td>'+
			                '</tr>';  
			            table.row.add($(x)).draw();  
			        });
			    });
}
function pasos(id_app,solucion){
	$("#idpaso1,#p1nom,#p1link,#p1desc,#idpaso2,#p2nom,#p2link,#p2desc,#idpaso3,#p3nom,#p3link,#p3desc,#idpaso4,#p4nom,#p4link,#p4desc,#idpaso5,#p5nom,#p5link,#p5desc").val('');
	$("#divconfig").hide();
	$("#divpasos").show();
	$("#lbapp").text(solucion);
	$("#in_idapp").val(id_app);
	$("#p1table tbody").html("");
	$("#p2table tbody").html("");
	$("#p3table tbody").html("");
	$("#p4table tbody").html("");
	$("#p5table tbody").html("");
	$.ajax({
        url: 'ajax.php?c=configuracion&f=reload_pasos',
        type: 'post',
        dataType: 'json',
        data:{id_app:id_app}
    })
    .done(function(data) {

        $.each(data, function(index, val) {
        	if(val.paso == 1){
        		$("#idpaso1R").val(val.id_paso);
        		$("#idpaso1").val(val.paso);
        		$("#p1nom").val(val.nombre);
        		$("#p1link").val(val.link);
        		$("#p1desc").val(val.desc_larga);
        		reload_actividades(id_app,val.id_paso,'p1table',1);
        	}
        	if(val.paso == 2){
        		$("#idpaso2R").val(val.id_paso);
        		$("#idpaso2").val(val.paso);
        		$("#p2nom").val(val.nombre);
        		$("#p2link").val(val.link);
        		$("#p2desc").val(val.desc_larga);
        		reload_actividades(id_app,val.id_paso,'p2table',2);
        	}
        	if(val.paso == 3){
        		$("#idpaso3R").val(val.id_paso);
        		$("#idpaso3").val(val.paso);
        		$("#p3nom").val(val.nombre);
        		$("#p3link").val(val.link);
        		$("#p3desc").val(val.desc_larga);
        		reload_actividades(id_app,val.id_paso,'p3table',3);
        	}
        	if(val.paso == 4){
        		$("#idpaso4R").val(val.id_paso);
        		$("#idpaso4").val(val.paso);
        		$("#p4nom").val(val.nombre);
        		$("#p4link").val(val.link);
        		$("#p4desc").val(val.desc_larga);
        		reload_actividades(id_app,val.id_paso,'p4table',4);
        	}
        	if(val.paso == 5){
        		$("#idpaso5R").val(val.id_paso);
        		$("#idpaso5").val(val.paso);
        		$("#p5nom").val(val.nombre);
        		$("#p5link").val(val.link);
        		$("#p5desc").val(val.desc_larga);
        		reload_actividades(id_app,val.id_paso,'p5table',5);
        	}
        });
    });
}

function back(){
	/*
    var pathname = window.location.pathname;
    window.location = 'http://'+document.location.host+pathname+'?c=configuracion&f=configuracion';
    */
    $("#divconfig").show();
    $("#divpasos").hide();
}

function reload_app(){
	$.ajax({
        url: 'ajax.php?c=configuracion&f=reload_app',
        type: 'post',
        dataType: 'json'
    })
    .done(function(data) {
    	var table = $('#tableGrid').DataTable( {dom: 'Bfrtip',                                                            
                                                            destroy: true,
                                                            searching: true,
                                                            paginate: true,
                                                            filter: true,
                                                            sort: true,
                                                            info: true,
                                                            language: {                                                                                                                                   
	                                                            search: "Buscar:",
	                                                            lengthMenu:"Mostrar _MENU_ elementos",
	                                                            zeroRecords: "No hay datos.",
	                                                            infoEmpty: "No hay datos que mostrar.",
	                                                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
	                                                            paginate: {
	                                                                        first:      "Primero",
	                                                                        previous:   "Anterior",
	                                                                        next:       "Siguiente",
	                                                                        last:       "Último"
	                                                                    }
                                                            },
                                    			});
    	table.clear().draw();
        var x ='';
        var estatus ='';

        $.each(data, function(index, val) {

        	if(val.estatus == 1){
        		estatus = '<a onclick="estatus_app('+val.id_app+',0);"> Desactivar</a></td>';
        	}else{
        		estatus = '<a onclick="estatus_app('+val.id_app+',1);"> Activar</a></td>';
        	}

        	x ='<tr>'+
                    '<td>'+val.nombre+'</td>'+
                    '<td>'+val.solucion+'</td>'+
                    '<td><a onclick="pasos('+val.id_app+', \''+val.solucion+'\');"> Editar</a>  '+estatus+'</td>'+
                '</tr>';  
            table.row.add($(x)).draw();  
        });
    });
}

function save_app(){

	var app = $("#app_solucion").val();
	var valida = 1;

	$.ajax({
        url: 'ajax.php?c=configuracion&f=ver_app',
        type: 'post',
        dataType: 'json',
        data:{app:app},
        async:false
    })
    .done(function(data) {
    	if(data > 0){
    		alert('Ya existe una solucion como esta');
    		valida = 0;
    	}
    });

    if($("#app_nombre").val() == '' || $("#app_desc").val() == ''){
    	alert('Campos Obligatorios!!');
    	valida = 0;
    }

    if(valida != 1){
    	return false;
    }


	var nombre = $("#app_nombre").val();
	var solucion = $("#app_solucion").val();
	var desc = $("#app_desc").val();

	$.ajax({
        url: 'ajax.php?c=configuracion&f=save_app',
        type: 'post',
        dataType: 'html',
        data:{nombre:nombre,solucion:solucion,desc:desc}
    })
    .done(function(data) {
    	reload_app();
    	$('#modal_add_guia').modal('hide');
    	if(data != true){
    		console.log('Error en el registro');
    	}
    });
}

function estatus_app(id_app, estatus){
	$.ajax({
	        url: 'ajax.php?c=configuracion&f=estatus_app',
	        type: 'post',
	        dataType: 'json',
	        data:{id_app:id_app, estatus:estatus}
	    })
	.done(function(data) {
		if(data != 1){
			console.log('Error al actualizar estatus');
		}
		reload_app();
	});
}

function estatus_act(id_act,estatus,id_app,idpasoR,idpasoA){
	
	var idtable = 'p'+idpasoA+'table';

	$.ajax({
	        url: 'ajax.php?c=configuracion&f=estatus_act',
	        type: 'post',
	        dataType: 'json',
	        data:{id_act:id_act,estatus:estatus}
	    })
	.done(function(data) {
		if(data != 1){
			console.log('Error al actualizar estatus');
		}
		reload_actividades(id_app,idpasoR,idtable,idpasoA);
	});
}

function save_pasos(){
	var id_app= $("#in_idapp").val();
	var lbapp = $("#lbapp").text();

	var save1 = save2 = save3 = save4 = save5 = 0;

	var paso1 = $("#idpaso1").val();
	var p1nom = $("#p1nom").val();
	var p1link = $("#p1link").val();
	var p1desc = $("#p1desc").val();
	var paso2 = $("#idpaso2").val();
	var p2nom = $("#p2nom").val();
	var p2link = $("#p2link").val();
	var p2desc = $("#p2desc").val();
	var paso3 = $("#idpaso3").val();
	var p3nom = $("#p3nom").val();
	var p3link = $("#p3link").val();
	var p3desc = $("#p3desc").val();
	var paso4 = $("#idpaso4").val();
	var p4nom = $("#p4nom").val();
	var p4link = $("#p4link").val();
	var p4desc = $("#p4desc").val();
	var paso5 = $("#idpaso5").val();
	var p5nom = $("#p5nom").val();
	var p5link = $("#p5link").val();
	var p5desc = $("#p5desc").val();

	if(p1nom != '' || p1desc != ''){
		if (p1nom == '' || p1desc == '') {
			alert('Debe llenar los campos obligaorios en el paso 1');
		}else{
			if(p1nom != '' && p1desc != ''){
				if(paso1 == 1){ // si existe en la DB se actualiza
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=edit_pasos',
				        type: 'post',
				        dataType: 'html',
				        data:{paso:1,nombre:p1nom,link:p1link,desc_larga:p1desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	//alert('se actualizo el paso 1');
				    	//pasos(id_app,lbapp);
				    	if(data != true){
				    		console.log('Error en el registro');
				    	}
				    });
					alert('se actualizo el paso 1');
				}else{ // se crea el paso
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=save_pasos',
				        type: 'post',
				        async:false,
				        data:{paso:1,nombre:p1nom,link:p1link,desc_larga:p1desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	alert('se guarda el paso 1');
				    	if(data < 0){
				    		console.log('Error en el registro');
				    	}else{
				    		$("#idpaso1R").val(data);
				    		$("#idpaso1").val(1);
				    	}				    	
				    });
				}
			}	
		}
	}

	if(p2nom != '' || p2desc != ''){
		if (p2nom == '' || p2desc == '') {
			alert('Debe llenar los campos obligaorios obligaorios en el paso 2');
		}else{
			if(p2nom != '' && p2desc != ''){
				if(paso2 == 2){ // si existe en la DB se actualiza
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=edit_pasos',
				        type: 'post',
				        dataType: 'html',
				        data:{paso:2,nombre:p2nom,link:p2link,desc_larga:p2desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	if(data != true){
				    		console.log('Error en el registro');
				    	}
				    });
					alert('se actualizo el paso 2');
				}else{ // se crea el apaso
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=save_pasos',
				        type: 'post',
				        async:false,
				        data:{paso:2,nombre:p2nom,link:p2link,desc_larga:p2desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	alert('se guarda el paso 2');
				    	if(data < 0){
				    		console.log('Error en el registro');
				    	}else{
				    		$("#idpaso2R").val(data);
				    		$("#idpaso2").val(2);
				    	}				    	
				    });
				}
			}				
		}
	}

	if(p3nom != '' || p3desc != ''){
		if (p3nom == '' || p3desc == '') {
			alert('Debe llenar los campos obligaorios obligaorios en el paso 3');
		}else{
			if(p3nom != '' && p3desc != ''){
				if(paso3 == 3){ // si existe en la DB se actualiza
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=edit_pasos',
				        type: 'post',
				        dataType: 'html',
				        data:{paso:3,nombre:p3nom,link:p3link,desc_larga:p3desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	if(data != true){
				    		console.log('Error en el registro');
				    	}
				    });
					alert('se actualizo el paso 3');
				}else{ // se crea el apaso
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=save_pasos',
				        type: 'post',
				        async:false,
				        data:{paso:3,nombre:p3nom,link:p3link,desc_larga:p3desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	alert('se guarda el paso 3');
				    	if(data < 0){
				    		console.log('Error en el registro');
				    	}
				    	else{
				    		$("#idpaso3R").val(data);
				    		$("#idpaso3").val(3);
				    	}
				    });
				}
			}				
		}
	}

	if(p4nom != '' || p4desc != ''){
		if (p4nom == '' || p4desc == '') {
			alert('Debe llenar los campos obligaorios obligaorios en el paso 4');
		}else{
			if(p4nom != '' && p4desc != ''){
				if(paso4 == 4){ // si existe en la DB se actualiza
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=edit_pasos',
				        type: 'post',
				        dataType: 'html',
				        data:{paso:4,nombre:p4nom,link:p4link,desc_larga:p4desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	if(data != true){
				    		console.log('Error en el registro');
				    	}
				    });
					alert('se actualizo el paso 4');
				}else{ // se crea el apaso
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=save_pasos',
				        type: 'post',
				        async:false,
				        data:{paso:4,nombre:p4nom,link:p4link,desc_larga:p4desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	alert('se guarda el paso 4');
				    	if(data < 0){
				    		console.log('Error en el registro');
				    	}else{
				    		$("#idpaso4R").val(data);
				    		$("#idpaso4").val(4);
				    	}				    	
				    });
				}
			}				
		}
	}

	if(p5nom != '' || p5desc != ''){
		if (p5nom == '' || p5desc == '') {
			alert('Debe llenar los campos obligaorios obligaorios en el paso 5');
		}else{
			if(p5nom != '' && p5desc != ''){
				if(paso5 == 5){ // si existe en la DB se actualiza
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=edit_pasos',
				        type: 'post',
				        dataType: 'html',
				        data:{paso:5,nombre:p5nom,link:p5link,desc_larga:p5desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	if(data != true){
				    		console.log('Error en el registro');
				    	}
				    });
					alert('se actualizo el paso 5');
				}else{ // se crea el apaso
					$.ajax({
				        url: 'ajax.php?c=configuracion&f=save_pasos',
				        type: 'post',
				        async:false,
				        data:{paso:5,nombre:p5nom,link:p5link,desc_larga:p5desc,id_app:id_app}
				    })
				    .done(function(data) {
				    	alert('se guarda el paso 5');
				    	if(data < 0){
				    		console.log('Error en el registro');
				    	}else{
				    		$("#idpaso5R").val(data);
				    		$("#idpaso5").val(5);				    	
				    	}
				    	
				    });
				}
			}				
		}
	}
}
function save_actividad(){

	var id_app= $("#in_idapp").val();
	var idpasoA = $("#idpasoA").val();
	var idpasoR = $("#idpasoR").val();
	var nombreAc = $("#nombreAc").val();
	var menuAc = $("#menuAc").val();
	var descAc = $("#descAc").val();
	var linkAc = $("#linkAc").val();
	var idtable = 'p'+idpasoA+'table';
	////alert(idtable);
	if( $('#opcionalAC').is(':checked') ) {
	    var opcionalAC = 1;
	}else{
		var opcionalAC = 0;
	}

	////alert(id_app+' '+idpasoR+' '+nombreAc+' '+menuAc+' '+descAc+' '+linkAc+' '+opcionalAC);

	$.ajax({
        url: 'ajax.php?c=configuracion&f=save_actividad',
        type: 'post',
        dataType: 'html',
        data:{nombre:nombreAc,menu:menuAc,desc:descAc,link:linkAc,opcional:opcionalAC,estatus:1,idpasoR:idpasoR}
    })
    .done(function(data) {
    	$('#modal_add_actividad').modal('hide');
    	if(data != true){
    		console.log('Error en el registro');
    	}
    	reload_actividades(id_app,idpasoR,idtable,idpasoA);
    });
}
