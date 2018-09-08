<html>
    <head>
      
        <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
        <script type="text/javascript" src="../js/ruta.js"></script>
       <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
      <script type="text/javascript" src="js/inicio.js"></script>
      <script type="text/javascript" src="js/geolocation.js"></script>
       <script type="text/javascript" src="js/cambio.js"></script>
      
      <style>
#mapa{
	z-index: 70;
	position: relative;
	width: 100%;
	height: 57%;
	float: left;
	box-shadow: #333 0px 0px 20px;
	-webkit-box-shadow:#333 0px 0px 20px;
	-moz-box-shadow: #333 0px 0px 20px;
	border: 1px solid #8B0000;
}
#ruta option{
	text-align: center;
}
#ruta{
	text-align: center;
}
#oferta option{
	text-align: center;
}
#oferta{
	text-align: center;
}
      </style>
     <script type="text/javascript">
           var mapa;
            function start(){
            	 init2();
           
                  
                
                  mapa.addControl(new OpenLayers.Control.LayerSwitcher());
               
                  }  
            	 </script>
            	 
            	  </head>
    <body onload="start()">
    	<?php 
    	//include_once('../funcionesBDconexion.php');
    //$consult = new Consult;
    	include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
    	?>
 

    <table border="1" style=" margin:0px auto 0px auto;">
    	<strong>La ruta partira de su posicion actual</strong>
<br></br>
<tr>Seleccione una Oferta
 	<select id="oferta" align="center" onchange="oferta()" align="center" style="  cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;">
      <option selected>----- Eliga una Oferta -----</option>
  		<?php 
$ofer=$conection->query('
select CONCAT(o.cantidad," ",u.compuesto," de ",p.nombre," a sólo $",o.precio)oferta, o.idOferta
from  
sms_oferta o,
mrp_producto p, 
mrp_unidades u
where  p.idProducto=o.idProducto and o.idunidad=u.idUni 
');
   while($oferta=$ofer->fetch_array(MYSQLI_ASSOC)){ 
   	//$trans=$transporte['id'];
   	?>
      <option id="<?php echo $oferta['idOferta']; ?>"><?php echo $oferta['idOferta']."->".utf8_decode($oferta['oferta'])?></option>
      
<?php } ?></select>

<br>

    	<tr>Elija Ruta para trazar
 	<select id="ruta" align="center" style="  cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;">
      <option selected>----- Elija una Ruta -----</option>
      
  		</select>
&nbsp;
<input type="button"  id="trazo" value="Trazar" style=" cursor:pointer; margin-top:25px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/>
    	</tr>
    	<img src="../images/preloader.gif"  id="carga" class="overbox" style="display: none;" />
    </table>
    
 <input type="checkbox" id="01" style="visibility:hidden" onclick="FiltroCapa(this.checked, this.id);" value="Puntos" checked/><br/>

    	 <div id='mapa'> </div>
    	  
</body>
<?php 


?>
<script type='text/javascript'>
var puntos=new Array();	

function oferta(){ showloader();
   		var idofer =jQuery('#oferta').val();
   		  var idoferta = idofer.split('->');  
   		  
   		 $.post('consulofer.php',{idoferta:idoferta[0]},
    function(respuesta) {
    	
   		$('#ruta').html(respuesta);
   	});
   	hideRegis();
   		}


	 $(document).ready(function(){
   	$('#trazo').click(function(){
   		//alert('entro' );
   var rutas =jQuery('#ruta').val();
var vectorLayerm=null;
  
   
   if(rutas=='----- Elija una Ruta -----'  || rutas=='No hay rutas iniciadas de esta oferta'){
 alert('Seleccione una oferta');
   }else{
   	 cambio(this.value)
       var idruta = rutas.split('->');  
         
			      
var las = new OpenLayers.LonLat(feature.geometry.x,feature.geometry.y).transform(toProjection, fromProjection);
           var lon= las.lon
           var lat= las.lat; 
           showloader();
          // alert(lat+","+lon);
              var pos = new OpenLayers.LonLat(lon,lat).transform(fromProjection, toProjection);

        
        // feature = new OpenLayers.Feature.Vector(
        // new OpenLayers.Geometry.Point(pos.lon, pos.lat),
        // {some:'data'},
        // {externalGraphic: 'http://www.openlayers.org/dev/img/marker.png', graphicHeight: 30, graphicWidth: 25});
        // vectorLayer.addFeatures(feature);
        // mapa.addLayer(vectorLayer);
//         
         mapa.addControl(new OpenLayers.Control.LayerSwitcher());  
//         
       puntos.length=0;
        //////////////////////////////////////////////
       $.post('test.php',{ruta:idruta[0]},
    function(respuesta) {
  //puntos.push(respuesta);
  //alert(respuesta);
  
			
  res=respuesta.split(',');
  for (x=0;x<res.length;x++){
   puntos.push(res[x]);
   //alert(puntos[x]);
  }
   
    
   
  $.post('geolo.php',{idruta:idruta[0],lon:lon,lat:lat},
		function(respues) {
			//alert(respues);
					sm = getLineStyle();
	 // if(kmlLayer != null){
//                                         		
     // kmlLayer.destroy();
      // kmlLayer = null;
// //    
      // }
          	
        kmlLayer = new OpenLayers.Layer.Vector(
     "ruta",
{
	//for (y=0;y<puntos.length;y++){
     styleMap : sm,
      projection: mapa.displayProjection,
     strategies: [new OpenLayers.Strategy.Fixed()],
     protocol: new OpenLayers.Protocol.HTTP(
   {
   
    // url:"http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=Heavygoods&fast=1&layer=mapnik&flon=-103.371504&flat=20.515340&tlon=-99.067929&tlat=19.391020",
     url:respues,

      format: new OpenLayers.Format.GeoJSON(
      {
     extractStyles: true,
     extractAttributes: true,
     maxDepth: 2
      })
    })
 });
         
               mapa.addLayer(kmlLayer); 	
			
			});
			       //
 // if(vectorLayerm!=null){
        	// vectorLayersm.destroy();
        	// vectorLayerm = new OpenLayers.Layer.Vector('Marcadores');
        // }else{
        	capavector = new OpenLayers.Layer.Vector('Marcadores');
        // }
  for (x=0;x<puntos.length;x++){
    var pos = new OpenLayers.LonLat(puntos[x],puntos[x+1]).transform(fromProjection, toProjection);
// 
        // //vectorLayer = new OpenLayers.Layer.Vector('Marcadores');
//        
//         
        feature = new OpenLayers.Feature.Vector(
        new OpenLayers.Geometry.Point(pos.lon, pos.lat),
        {some:'data'},
        {externalGraphic: 'http://www.openlayers.org/dev/img/marker.png', graphicHeight: 30, graphicWidth: 25});
        capavector.addFeatures(feature);
        mapa.addLayer(capavector);

// feature = new OpenLayers.Feature.Vector(
 // new OpenLayers.Geometry.Point(pos.lon, pos.lat),
  // {
// descripcion: 'loq sea'
  // },
// {externalGraphic: 'http://www.openlayers.org/dev/img/marker.png', graphicHeight: 25, graphicWidth: 20});
// capavector.addFeatures(feature);
      // mapa.addLayer(capavector);  
      }
   
    /////////////////////////////////
   
     // for (y=1;y<puntos.length;y++){
   // coor+=puntos[y-1]+",";
    // }
    // coor+=puntos[y-1];
    
   // $.post('puntos.php',{lon:lon,lat:lat,puntos:coor},
		// function(respues) {
			// alert(respues);
		// sm = getLineStyle();
	 // // if(kmlLayer != null){
// //                                         		
     // // kmlLayer.destroy();
      // // kmlLayer = null;
// // //    
      // // }
//           	
        // kmlLayer = new OpenLayers.Layer.Vector(
     // "ruta",
// {
	// //for (y=0;y<puntos.length;y++){
     // styleMap : sm,
      // projection: mapa.displayProjection,
     // strategies: [new OpenLayers.Strategy.Fixed()],
     // protocol: new OpenLayers.Protocol.HTTP(
   // {
//    
    // // url:"http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=Heavygoods&fast=1&layer=mapnik&flon=-103.371504&flat=20.515340&tlon=-99.067929&tlat=19.391020",
     // url:respues,
// 
      // format: new OpenLayers.Format.GeoJSON(
      // {
     // extractStyles: true,
     // extractAttributes: true,
     // maxDepth: 2
      // })
    // })
 // });
//          
               // mapa.addLayer(kmlLayer); 	
			
 // });
  
          //  drawRoute();
  });
   hideRegis();
//    
 // });
   ///////////////////////////////////////
    
    
 }//esle
	 }); //tarzo
});//document
 </script> 
</html> 


