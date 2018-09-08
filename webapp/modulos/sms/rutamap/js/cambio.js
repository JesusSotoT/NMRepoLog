function cambio(algo){
    
	mapa.destroy();
        init();
}

function cambio2(algo){
    mapa.destroy();
        init22();
}

function returnInit(){

       var origen= $("#origen option:selected").text();
        $.post('origen.php',{origen:origen},
        function(respuesta) {

                var latitudlongitud=respuesta.split(",");
                vectorLayer = new OpenLayers.Layer.Vector("Posicion");
                lonlat = new OpenLayers.LonLat(latitudlongitud[1],latitudlongitud[0]).transform(fromProjection, toProjection);
                feature = new OpenLayers.Feature.Vector( new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat),
                {some:'data'},
                {externalGraphic: 'marker.png', graphicHeight: 37, graphicWidth: 32});
                vectorLayer.addFeatures(feature);
                mapa.addLayer(vectorLayer);

                var dragFeature = new OpenLayers.Control.DragFeature(vectorLayer);
                mapa.addControl(dragFeature);
                dragFeature.activate();
                mapa.setCenter(lonlat, 13);

                returnCambio();
        });
}

function returnInit2(){

       var origen= $("#origen option:selected").text();
        $.post('origen.php',{origen:origen},
        function(respuesta) {

                var latitudlongitud=respuesta.split(",");
                vectorLayer = new OpenLayers.Layer.Vector("Posicion");
                lonlat = new OpenLayers.LonLat(latitudlongitud[1],latitudlongitud[0]).transform(fromProjection, toProjection);
                feature = new OpenLayers.Feature.Vector( new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat),
                {some:'data'},
                {externalGraphic: 'marker.png', graphicHeight: 37, graphicWidth: 32});
                vectorLayer.addFeatures(feature);
                mapa.addLayer(vectorLayer);

                var dragFeature = new OpenLayers.Control.DragFeature(vectorLayer);
                mapa.addControl(dragFeature);
                dragFeature.activate();
                mapa.setCenter(lonlat, 13);

                returnCambio(1);
        });
}

