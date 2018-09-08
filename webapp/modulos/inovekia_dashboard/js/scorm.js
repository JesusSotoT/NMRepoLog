var _curso;
var _seguimiento;
var _url;

function obtenerParametro(parametro)
{
    var url = window.location.search.substring(1);
    var parametrosArray = url.split('&');
    for (var i = 0; i < parametrosArray.length; i++) 
    {
        var parametroArray = parametrosArray[i].split('=');
        if (parametroArray[0] == parametro) {
            return parametroArray[1];
        }
    }
}

function obtenerSeguimiento(curso, url)
{
  	$.ajax({
	    type: "POST",
	    url: url + "inovekia_dashboard/ajax.php?c=lms&f=obtenerSeguimiento",
	    dataType: "json",
	    data: { empresario: obtenerParametro("empresario"), curso: curso },
	    success: function(respuesta){
	        if(respuesta.status !== undefined && respuesta.status == true){
	        	reproducir(respuesta.seguimiento);
	        }else{
	          alert(respuesta.mensaje);
	        }
	    },
	    error: function(error){
	    	alert("No se ha podido completar esta accion, por favor intentalo nuevamente");
	    }
  	});
}

function guardarSeguimiento(curso, seguimiento, url)
{
	_curso = curso;
	_seguimiento = seguimiento;
  _url = url;
	obtenerUbicacion();
}

function obtenerUbicacion() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(siLocalizado, noLocalizado);
    } else {
        alert("El navegador no soporta la localización");
    }
}

function siLocalizado(ubicacion) {
	$.ajax({
    	type: "POST",
      	url: _url +  "inovekia_dashboard/ajax.php?c=lms&f=guardarSeguimiento",
      	dataType: "json",
      	data: { id_empresario: obtenerParametro("empresario"), id_curso: _curso, ultimo_slide: _seguimiento.lastViewedSlide, seguimiento: JSON.stringify(_seguimiento), latitud: ubicacion.coords.latitude, longitud: ubicacion.coords.longitude },
      	success: function(respuesta){
	        if(respuesta.status !== undefined && respuesta.status == true){
	        
          }else{
	          alert(respuesta.mensaje);
	        }
      	},
      	error: function(error){
        	alert("No se ha podido completar esta accion, por favor intentalo nuevamente");
      	}
  	});
}

function noLocalizado(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("No se ha autorizado la localización, no se guardará el seguimiento [1]");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("No se ha autorizado la localización, no se guardará el seguimiento [2]");
            break;
        case error.TIMEOUT:
            alert("No se ha autorizado la localización, no se guardará el seguimiento [3]");
            break;
        case error.UNKNOWN_ERROR:
            alert("No se ha autorizado la localización, no se guardará el seguimiento [4]");
            break;
    }
}

