function subirxml(xml){
    var file = '';
    if (xml == 1){file = 'clientes'}
    if (xml == 2){file = 'productos'}
        
    var input = $('#'+file+'File').val();

    if( input == ''){
        alert('¡Debe cargar el archivo de '+file);
        return false;
    }

    $('#btn'+file).prop( "disabled", true ).text('Subiendo...');
    var valida = 1;

    $('#'+file+'File').simpleUpload('views/importar/subirarchivo.php?name='+file+'', {
        start: function(file){                                
            console.log("upload started");
        },
        progress: function(progress){                                
            console.log("upload progress: " + Math.round(progress) + "%");
        },
        success: function(data){            
            console.log(data);
            var objresp = $.parseJSON(data);
            console.log(objresp);
            var suc = objresp['success'];
            var msg = objresp['message'];
            if(suc == false){
                alert(msg);
                validaFile = 0;
                $("#modalLoad").modal('hide');
            }                              
        },
        error: function(error){
            alert('Error al subir la imagen');
            return false;
            console.log("upload error: " + error.name + ": " + error.message);
            alert("upload error: " + error.name + ": " + error.message); 
            valida = 0;                              
        }
    });

    setTimeout(function(){ savexml(); }, 2000);

    function savexml(){
        if(valida == 1){
            $.ajax({
                    url: 'ajax.php?c=importar&f=subir'+file+'',
                    type: 'post',
                    datatype:'json',
                    data:{file:file}               
            })
            .done(function(data) {
                console.log(data); 
                $('#btn'+file).prop( "disabled", false ).text('OK').addClass('btn-success').removeClass('btn-primary');                 
            }) 
        } 
    }
}
/*
function subirClientes(){
	var valida = 1;
    var file = 'clientes';
    $('#'+file+'File').simpleUpload('views/importar/subirarchivo.php?name='+file+'', {
                        start: function(file){                                
                            console.log("upload started");
                        },
                        progress: function(progress){                                
                            console.log("upload progress: " + Math.round(progress) + "%");
                        },
                        success: function(data){
                            //upload successful
                            console.log(data);
                            var objresp = $.parseJSON(data);
                            console.log(objresp);
                            var suc = objresp['success'];
                            var msg = objresp['message'];
                            if(suc == false){
                                alert(msg);
                                validaFile = 0;
                                $("#modalLoad").modal('hide');
                            }                              
                        },
                        error: function(error){
                            alert('Error al subir la imagen');
                            return false;
                            console.log("upload error: " + error.name + ": " + error.message);
                            alert("upload error: " + error.name + ": " + error.message); 
                            valida = 0;                              
                        }
                    });

     setTimeout(function(){ savexml(); }, 2000);
    
    function savexml(){
    	if(valida == 1){
        	$.ajax({
	                url: 'ajax.php?c=importar&f=subirclientes',
	                type: 'post',
	                datatype:'json'                
	        })
	        .done(function(data) {
	        	console.log(data);		        	
	        }) 
        } 
    }         
}
function subirProductos(){
	var valida = 1;
    var file = 'productos';

    $('#'+file+'File').simpleUpload('views/importar/subirarchivo.php?name='+file+'', {
                        start: function(file){                                
                            console.log("upload started");
                        },
                        progress: function(progress){                                
                            console.log("upload progress: " + Math.round(progress) + "%");
                        },
                        success: function(data){
                            //upload successful
                            console.log(data);
                            var objresp = $.parseJSON(data);
                            console.log(objresp);
                            var suc = objresp['success'];
                            var msg = objresp['message'];
                            if(suc == false){
                                alert(msg);
                                validaFile = 0;
                                $("#modalLoad").modal('hide');
                            }                              
                        },
                        error: function(error){
                            alert('Error al subir la imagen');
                            return false;
                            console.log("upload error: " + error.name + ": " + error.message);
                            alert("upload error: " + error.name + ": " + error.message); 
                            valida = 0;                              
                        }
                    });

    setTimeout(function(){ savexml(); }, 2000);
    
    function savexml(){
        if(valida == 1){
            $.ajax({
                    url: 'ajax.php?c=importar&f=subirproductos',
                    type: 'post',
                    datatype:'json'                
            })
            .done(function(data) {
                console.log(data);  
                alert(11);                
            }) 
        } 
    } 
}
*/
function deleteFiles(){
	$.ajax({
            url: 'ajax.php?c=importar&f=deleteFiles',
            type: 'post',                
    })
    .done(function(data) {
    	console.log(data);
    	alert('data');
    }) 
}