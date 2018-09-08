var latitud;                // Latitud que saca de donde se visita.
var longitud;               // Longitud que saca de donde se visita.
var fromProjection = new OpenLayers.Projection('EPSG:4326');
var toProjection = new OpenLayers.Projection('EPSG:900913');
var lonlat;

function get_geolocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getCoordinates, errorCoordinates);
    }else
        alert("Este navegador no soporta geolocalizacion!");
};

function getCoordinates(position) {
    latitud = position.coords.latitude;
    longitud = position.coords.longitude;
    lonlat = new OpenLayers.LonLat(longitud,latitud).transform(fromProjection, toProjection);
    mapa.setCenter(lonlat, 13);
    returnInit();
}

function errorCoordinates(error){

    switch(error.code) {
        case 1:
            alert("Error: Permisos Denegados");    
        break;
        
        case 2:
            alert("Error: El sistema de navegacion no esta disponible!"); 
        break;
        
        case 3:
            alert("Error: El sistema de navegacion vencio el tiempo de espera. No hay respuesta!"); 
        break;
        
        case 0:
            alert("Error: Desconocido"); 
        break;
    }
}