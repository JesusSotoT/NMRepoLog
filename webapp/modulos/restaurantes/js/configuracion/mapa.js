var mapa = {
///////////////// ******** ----            		guardar_areas_mapa              ------ ************ //////////////////
/////// Guarda los datos de las areas del mapa
// Como parametros recibe:

	guardar_areas_mapa: function($objeto) {
   		console.log('==========> $objeto guardar_areas_mapa');
        console.log($objeto);
	    
	    if(!$objeto.poligonos){
	    	
	        $mensaje = 'Dibuja areas en el mapa';
	        $.notify($mensaje, {
	            position : "top center",
	            autoHide : true,
	            autoHideDelay : 5000,
	            className : 'warn',
	            arrowSize : 15
	        });
	        
	    	return;
	    }
	    
		var $mensaje = '';
	    $.ajax({
	        data : $objeto,
	        url : 'ajax.php?c=configuracion&f=guardar_areas_mapa',
	        type : 'POST',
	        dataType : 'json'
	    }).done(function(resp) {
	   		console.log('==========> Done guardar_areas_mapa');
	        console.log(resp);
	        
	        $mensaje = 'Areas guardadas';
	        $.notify($mensaje, {
	            position : "top center",
	            autoHide : true,
	            autoHideDelay : 5000,
	            className : 'success',
	            arrowSize : 15
	        });
	    }).fail(function(resp) {
	   		console.log('==========> Fail guardar_areas_mapa');
	        console.log(resp);
	        
		// Mensaje error
	        $mensaje = 'Error al guardar las areas';
	        $.notify($mensaje, {
	            position : "top center",
	            autoHide : true,
	            autoHideDelay : 5000,
	            className : 'error',
	            arrowSize : 15
	        });
	    });
	},

///////////////// ******** ----            		FIN guardar_areas_mapa          ------ ************ //////////////////

///////////////// ******** ----            		eliminar_areas_mapa             ------ ************ //////////////////
/////// Elimina los datos de las areas del mapa
// Como parametros recibe:

	eliminar_areas_mapa: function($objeto) {
   		console.log('==========> $objeto eliminar_areas_mapa');
        console.log($objeto);
	    
	    if(!confirm("¿Eliminar todas las areas?")){
	    	return;
	    }
	    
		var $mensaje = '';
	    $.ajax({
	        data : $objeto,
	        url : 'ajax.php?c=configuracion&f=eliminar_areas_mapa',
	        type : 'POST',
	        dataType : 'json'
	    }).done(function(resp) {
	   		console.log('==========> Done eliminar_areas_mapa');
	        console.log(resp);
	        
	        setMap(null);
	        
	        $mensaje = 'Areas eliminadas';
	        $.notify($mensaje, {
	            position : "top center",
	            autoHide : true,
	            autoHideDelay : 5000,
	            className : 'success',
	            arrowSize : 15
	        });
	    }).fail(function(resp) {
	   		console.log('==========> Fail eliminar_areas_mapa');
	        console.log(resp);
	        
		// Mensaje error
	        $mensaje = 'Error al eliminar las areas';
	        $.notify($mensaje, {
	            position : "top center",
	            autoHide : true,
	            autoHideDelay : 5000,
	            className : 'error',
	            arrowSize : 15
	        });
	    });
	},

///////////////// ******** ----            		FIN guardar_areas_mapa          ------ ************ //////////////////
///////////////// ******** ---- 				agregar_zona_reparto			------ ************ //////////////////
//////// Agrega una via de contacto, esconde la modal, actualiza el select y selecciona la nueva opcion
	// Como parametros recibe:
		// nombre -> Nombre de la nueva via de contacto
		// btn -> Buton del loader

	agregar_zona_reparto : function($objeto) {

		console.log('===============> objeto agregar_zona_reparto');
		console.log($objeto);
	// ** Validaciones
		if (!$objeto['nombre']) {
				var $mensaje = 'Introduce un nombre';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_zona_reparto',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done agregar_zona_reparto');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
				var $mensaje = 'Zonda de reparto guardada';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
			
		// Todo bien :D, oculta la modal
			$('#modal_zona_reparto').modal('hide');
		
		// Actualiza el select
		/*
			$("#via_contacto").append('<option value="'+resp['result']+'">'+$objeto['nombre']+'</option>');
			$("#via_contacto").val(resp['result']);
			$('#via_contacto').selectpicker('refresh');
		*/
			
			$("#area").append('<option value="'+resp['result']+'">'+$objeto['nombre']+'</option>');
			$("#area").val(resp['result']);
			$('#area').selectpicker('refresh');
			mapa.listar_areas_mapa({id_area: $('#area').val()});
		}).fail(function(resp) {
			console.log('================= Fail agregar_zona_reparto');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
				var $mensaje = 'Error al guardar la via de contacto';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_zona_reparto			------ ************ //////////////////

///////////////// ******** ----					listar_areas_mapa				------ ************ //////////////////
/////// Consulta los datos de las areas y las pinta en el mapa
// Como parametros recibe:
	// id_area -> ID del area a dibujar

	listar_areas_mapa: function($objeto) {
   		console.log('==========> $objeto listar_areas_mapa');
        console.log($objeto);
	    
		 $.ajax({
		 	data: $objeto,
	        url : 'ajax.php?c=configuracion&f=listar_areas_mapa',
	        type : 'POST',
	        dataType : 'json'
	    }).done(function(resp) {
	   		console.log('==========> Done listar_areas_mapa');
	        console.log(resp);
			
			setMap(null);
		
		// Arma el array para el mapa
	  		var array = [];
		  	$.each(resp, function(key, value) {
		  		var polygon = [];
		  		var vertices = [];
		  		$.each(value, function(k, v){
		  			var element = {};
		  			element.lat = v.lat;
		  			element.lng = v.lng;
		  			vertices.push(element);
		  		});
		  		polygon = vertices;
		  		array.push(polygon);
		  	});
	  	
	  	// Dibuja los poligonos en el mapa
			map.drawPolygons(array);
	    }).fail(function(resp) {
	   		console.log('==========> Fail listar_areas_mapa');
	        console.log(resp);
	        
		// Mensaje error
	        $mensaje = 'Error al cargar las areas';
	        $.notify($mensaje, {
	            position : "top center",
	            autoHide : true,
	            autoHideDelay : 5000,
	            className : 'error',
	            arrowSize : 15
	        });
	    });
	},

///////////////// ******** ----				FIN listar_areas_mapa				------ ************ //////////////////
};



class Map
{
	constructor(canvas, point) {
		this.canvas = canvas;
		this.googleMap = new google.maps.Map(canvas, {
     	center: point,
     	zoom: 16
    });
    this.googleAutocomplete = null;
    this.lastAutocomplete = null;
    this.overlays = [];
    this.drawingPanel = new google.maps.drawing.DrawingManager({
    	drawingControl: true,
    	drawingControlOptions: {
    		position: google.maps.ControlPosition.TOP_CENTER,
    		drawingModes: ['polygon']
    	}
    });
    this.drawingPanel.setMap(this.googleMap);
    this.addOverlayListener(this);
    this.addClickListener(this);
	}

	addClickListener(self) {
		google.maps.event.addListener(this.googleMap, "click", function(event){
			self.hideContextMenu();
		});
	}

	addOverlayListener(self) {
		google.maps.event.addListener(this.drawingPanel, "overlaycomplete", function(event){
			event.overlay.setOptions({fillColor: self.getColor()});
		  self.addElementClickListener(self, event.overlay, self.overlays.length);
		});
	}

  addElementClickListener(self, overlay, length) {
    (function listener(overlay, length) {
      google.maps.event.addListener(overlay, "click", function(event){
        self.showContextMenu(self, event.latLng, overlay);
      });
      var item = [];
      item["id"] = length;
      item["overlay"] = overlay;
      item["coordinates"] = overlay.getPath().getArray();
      self.overlays.push(item);
    })(overlay, length);
  }

	hideContextMenu() {
		$(".context-menu").remove();
	}

	showContextMenu(self, point, overlay) {
		this.hideContextMenu();
		var mapProjection = this.googleMap.getProjection();
		var contextMenu = document.createElement("div");
		contextMenu = $(contextMenu);
		contextMenu.addClass("context-menu");
		var itemDelete = document.createElement("div");
		itemDelete = $(itemDelete);
		itemDelete.addClass("context");
		itemDelete.html("Eliminar");
		itemDelete.click(function(){
			overlay.setMap(null);
			self.overlays = $.grep(self.overlays, function(element){
				return element.overlay != overlay;
			});
			self.hideContextMenu();
		});
		itemDelete.appendTo(contextMenu);
		contextMenu.appendTo($(this.googleMap.getDiv()));
		this.setMenuCanvasXY(point);
	}

	getMenuCanvasXY(point){
  	var scale = Math.pow(2, this.googleMap.getZoom());
  	var position = new google.maps.LatLng(
  		this.googleMap.getBounds().getNorthEast().lat(),
      	this.googleMap.getBounds().getSouthWest().lng()
  	);
  	var worldCoordinatePosition = this.googleMap.getProjection().fromLatLngToPoint(position);
  	var worldCoordinate = this.googleMap.getProjection().fromLatLngToPoint(point);
  	var realPosition = new google.maps.Point(
      Math.floor((worldCoordinate.x - worldCoordinatePosition.x) * scale),
    	Math.floor((worldCoordinate.y - worldCoordinatePosition.y) * scale)
  	);
  	return realPosition;
 	}

  setMenuCanvasXY(point){
  	var mapWidth = $(this.canvas).width();
   	var mapHeight = $(this.canvas).height();
   	var menuWidth = $('.context-menu').width();
   	var menuHeight = $('.context-menu').height();
   	var clickedPosition = this.getMenuCanvasXY(point);
   	var x = clickedPosition.x;
   	var y = clickedPosition.y;

    if((mapWidth - x ) < menuWidth) x = x - menuWidth;
   	if((mapHeight - y ) < menuHeight) y = y - menuHeight;

   	$('.context-menu').css('left',x  );
   	$('.context-menu').css('top',y );
  }

  getPolygons() {
  	return this.overlays;
  }

  getPolygonsAsCoordinates() {
  	var poligonos = {};
  	$.each(this.overlays, function(position, overlay) {
  		poligonos[overlay["id"]] = {
  			vectores: {}
  		};
  		$.each(overlay["coordinates"], function(position, vertex){
  			poligonos[overlay["id"]].vectores[position] = {
  				lat: 0,
  				lng: 0
  			};
  			
  			poligonos[overlay["id"]].vectores[position].lat = vertex.lat();
  			poligonos[overlay["id"]].vectores[position].lng = vertex.lng();
  		});
  	});
  	
  	return poligonos;
  }

  searchPolygonsContainers(lat, lng) {
  	var zones = [];
  	var point = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
  	$.each(this.overlays, function(position, overlay){
  		if(google.maps.geometry.poly.containsLocation(point, overlay["overlay"])) zones.push(overlay["id"]);
  	});
  	return zones;
  }

  isWithinPolygon(lat, lng) {
  	return (this.searchPolygonsContainers(lat, lng).length > 0);
  }

  enableGeocoding(searchBoxPlace) {
  	var self = this;
  	this.googleAutocomplete = new google.maps.places.Autocomplete(
  		(searchBoxPlace), 
  		{
        types: ['geocode'],
        componentRestrictions: {'country': 'mx'}
      }
    );
    this.googleAutocomplete.addListener('place_changed', function(){
    	var place = self.googleAutocomplete.getPlace();
    	self.lastAutocomplete = {"place": $(searchBoxPlace).val(), "lat": place.geometry.location.lat(), "lng": place.geometry.location.lng()};
    });
  }

  getLastAutocomplete() {
  	return this.lastAutocomplete;
  }

  drawPolygons(polygons) {
    var self = this;
    $.each(polygons, function(position, coordinates){
      var polygon = new google.maps.Polygon({
        paths: coordinates,
        fillColor: self.getColor()
      });
      polygon.setMap(self.googleMap);
      self.addElementClickListener(self, polygon, self.overlays.length);
    });
  }

  getColor() {
    return "#" + (Math.random() * 0xFFFFFF << 0).toString(16);
  }

}

var map;

$(document).ready(function(){
	$("#dirigente").click(function(){
	    var geolocation = {
	      lat: map.getLastAutocomplete()["lat"],
	      lng: map.getLastAutocomplete()["lng"]
	    };
	    map.googleMap.setZoom(16);
	    map.googleMap.setCenter(geolocation);
	  });
	$("#validador").click(function(){
		console.log(map.getPolygonsAsCoordinates());
		alert(map.isWithinPolygon(map.getLastAutocomplete()["lat"], map.getLastAutocomplete()["lng"]));
	});
	$("#localizador").click(function(){
		var $btn = $('#localizador');
		$btn.button('loading');
	    if (navigator.geolocation) {
	      navigator.geolocation.getCurrentPosition(function(position) {
	        var geolocation = {
	          lat: position.coords.latitude,
	          lng: position.coords.longitude
	        };
	        $btn.button('reset');
	        map.googleMap.setZoom(16);
	        map.googleMap.setCenter(geolocation);
	      }, function() {
	        alert("Sorry, something went wrong please try again");
	      });
	    } else {
	      alert("Sorry, your browser doesn't support geolocation");
	    }
	  });
});

function setMap() {
	map = new Map(document.getElementById("google-map"), {lat: parseFloat("20.6296109"), lng: parseFloat("-103.3450892")});
	map.enableGeocoding(document.getElementById("buscador"));
}