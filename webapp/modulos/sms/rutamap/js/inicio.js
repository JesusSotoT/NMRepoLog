var mapa;

function init(){//localizacion
	mapa = new OpenLayers.Map("mapa");
	var osm = new OpenLayers.Layer.OSM();
	mapa.addLayer(osm);
	
	mapa.zoomToMaxExtent();
	mapa.addControl(new OpenLayers.Control.MousePosition());
	var centrogdl = new OpenLayers.LonLat(-11504672.08086, 2353732.75782);
	mapa.setCenter(centrogdl,13);
	//get_geolocation();
	returnInit();
}

function init22(){//localizacion
	mapa = new OpenLayers.Map("mapa");
	var osm = new OpenLayers.Layer.OSM();
	mapa.addLayer(osm);
	
	mapa.zoomToMaxExtent();
	mapa.addControl(new OpenLayers.Control.MousePosition());
	var centrogdl = new OpenLayers.LonLat(-11504672.08086, 2353732.75782);
	mapa.setCenter(centrogdl,13);
	//get_geolocation();
	returnInit2();
}

function reset(){
	mapa.destroy();
	init();	
}

function init2(){
	mapa = new OpenLayers.Map("mapa");
	var osm = new OpenLayers.Layer.OSM();
	mapa.addLayer(osm);
	//mapa.addLayer(getMarkersLayer());

	mapa.zoomToMaxExtent();
	//mapa.events.register('click',mapa,clickHandler);
	mapa.addControl(new OpenLayers.Control.MousePosition());
	var centrogdl = new OpenLayers.LonLat(-11504672.08086, 2353732.75782);//es el punto avisualizar
	mapa.setCenter(centrogdl,13);//es el zoom al mapa
}

var styleMapi = new OpenLayers.StyleMap(OpenLayers.Util.applyDefaults(
        {fillColor: "red", fillOpacity: 1, strokeColor: "red"},
        OpenLayers.Feature.Vector.style["default"]));
  //var map,
            
            
     function getLineStyle(){
     	//var defStyle = {strokeColor: "blue", strokeOpacity: "0.7", strokeWidth: 4, cursor: "pointer"};
     	var defStyle = {strokeColor: "red", strokeOpacity: "1", strokeWidth: 4, cursor: "pointer"};
	 	var sty = OpenLayers.Util.applyDefaults(defStyle, OpenLayers.Feature.Vector.style["default"]);
	 	var sm = new OpenLayers.StyleMap({ 'default': sty, 'select': {strokeColor: "blue", fillColor: "blue"} });
		return sm;
     }
   
        
    
    