<html>
    <head>
      <LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
      <LINK href="../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

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
      	include("../../../netwarelog/webconfig.php");

        $conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
    	?>
      
      <div class="terminarOrden" style="width:95%;height:68%;background:#ffffff;position:fixed;z-index:100;overflow-x:auto;padding-bottom:10px;border-radius:10px;border:2px solid #585858;display:none" align="center">
        <div style="width:100%;height:25px;color:#2E2E2E;font-size:16px;font-weight:bold;margin-top:1%">Generar Orden</div>
        <div style="width:40%;height:40%;position:absolute;border-radius:10px;background:#F2F2F2;border:5px solid #6E6E6E;margin-left:26%;margin-top:8%;visibility:hidden" class="alertaOrden"><table style="width:100%;height:80%;font-size:18px;font-weight:bold;color:#585858"><tr><td align="center">Generando Documentos</td></tr><tr><td align="center"><img src="../images/preloader.gif" style="width:50px;height:50px"/></td></tr></table></div>
        <table style="width:96%;font-size:12px;float:left" class="terminarOrdenTabla"></table>
        <div><img src="../images/close.gif" style="float:left;margin-top:3px" class="cerrarOrden"/></div>
        <div style="width:96%;height:25px;float:left" align="right">
          <div class="btngenerarOrden" id="btngenerarOrden" style="background:#D8D8D8;width:100px;height:22px;color:#2E2E2E;font-size:12px;border-radius:2px;font-weight:bold;cursor:pointer" align="center">Generar</div>
        </div>
        <div class="comprimirDescarga"></div>
      </div>
      <strong style="font-size: 15; color:#585858;margin-left:10px">La ruta partira del origen</strong>
      <br></br>

      <div class="contenedorDerecho" style="width:49%;float:left;padding-left:20px">
      <div><label style="font-size: 15; color:#1C1C1C" >Seleccione Origen:</label></div>
      <div><select id="origen" align="center" align="center" style="cursor:pointer; margin-top:5px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;" onchange="origen()">
        <option value="0" selected>----- Eliga un Origen -----</option>
        <?php 
          $ori=$conection->query('select sor.id, sor.calle, sor.num_ext, m.municipio, e.estado from sms_origen_rutas sor 
                                  inner join estados e on e.idestado=sor.idEstado
                                  inner join municipios m on m.idmunicipio=sor.municipio');

          while($origen=$ori->fetch_array(MYSQLI_ASSOC)){ 
        ?>
          <option value="<?php echo $origen['id']; ?>"><?php echo $origen['num_ext']." ".utf8_decode($origen['calle']).", ".utf8_decode($origen['municipio']).", ".utf8_decode($origen['estado']); ?></option>
      
        <?php } ?>
      </select></div>

      <div><label style="font-size: 15; color:#1C1C1C" >Seleccione una Oferta:</label></div>
      <div><select id="oferta" align="center" align="center" style="  cursor:pointer; margin-top:5px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;">
        <option selected>----- Eliga una Oferta -----</option>
    		<?php 
          $ofer=$conection->query('select CONCAT(o.cantidad," ",u.compuesto," de ",p.nombre," a solo $",o.precio)oferta, o.idOferta
                                  from  
                                  sms_oferta o,
                                  mrp_producto p, 
                                  mrp_unidades u
                                  where  p.idProducto=o.idProducto and o.idunidad=u.idUni');

          while($oferta=$ofer->fetch_array(MYSQLI_ASSOC)){ 
        ?>
          <option id="<?php echo $oferta['idOferta']; ?>"><?php echo $oferta['idOferta']."->".utf8_decode($oferta['oferta'])?></option>
      
        <?php } ?>
      </select></div>

  <!--<div><label style="font-size: 15; color:#1C1C1C" >Elija Ruta para trazar</label></div>
 	<div><select id="ruta" align="center" style="  cursor:pointer; margin-top:5px; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;">
    <option selected>----- Elija una Ruta -----</option>
  </select></div>-->

<table style="margin-top:5px;margin-left:20px">
  <tr>
    <td><input type="button"  id="trazo" value="Trazar" style=" cursor:pointer; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;"/></td>
    <td style="font-family:verdana;color:#424242;font-size:12">Por:</td>
    <td><input type="radio" name="mapOpt" value="0" checked><label style="font-family:verdana;color:#424242;font-size:12">Distancia</label></td>
    <td><input type="radio" name="mapOpt" value="1"><label style="font-family:verdana;color:#424242;font-size:12">Tiempo</label></td>
    <td><img src="../images/preloader.gif"  id="carga" class="overbox" style="display: none;" /></td>
    <td><input type="button" value="Generar Orden" style="cursor:pointer; border:0px; background-color:#91C313; font-size:12px; color:#FFFFFF;display:none" class="generarOrden"/></td>
  </tr>
</table>
</div>-

<div class="contenedorIzquierdo" style="width:49%;float:left;overflow-y:visible;overflow-x:hidden;height:150px;font-family:verdana;font-size:11px">
  * Seleccione un origen, una ruta y precione el boton trazar para mostrar una trayectoria.
</div>	
    
 <input type="checkbox" id="01" style="visibility:hidden" onclick="FiltroCapa(this.checked, this.id);" value="Puntos" onchange="ruta()" checked/><br/>

    	 <div id='mapa'> </div>
    	  
</body>

<script type='text/javascript'>
var puntos=new Array();	
var archivo="";

function oferta(){ 
      $(".generarOrden").hide();
      showloader();
   		var idofer =jQuery('#oferta').val();
   		var idoferta = idofer.split('->');  
   		  
   		$.post('consulofer.php',{idoferta:idoferta[0]},
        function(respuesta) {
        	
       		$('#ruta').html(respuesta);
      });
   	hideRegis();
}

function ruta(){
  $(".generarOrden").hide();
}

function origen(){
  $(".generarOrden").hide();
}

$(document).ready(function(){
  $('#trazo').click(function(){
  
  var rutas =jQuery('#oferta').val();
  var vectorLayerm=null;
   
  var origen= $("#origen").val();

  if(origen==0){
      alert("Seleccione un origen");
      return false;
  } 

  if(rutas=='----- Elija una Oferta -----'  || rutas=='No hay rutas iniciadas de esta oferta'){
    alert('Seleccione una oferta');
  }else{
    $(".contenedorIzquierdo").html("* Obteniendo trayectoria porfavor espere...");
    cambio2(this.value);
  }//esle
 }); //tarzo

  $(".generarOrden").click(function(){
    $(".terminarOrden").show();
    $(".btngenerarOrden").show();
    $(".comprimirDescarga").empty();
    $(".terminarOrdenTabla").html('<tr style="background:#2E2E2E;color:#FFFFFF" align="center"><td style="border:1px solid #D8D8D8">Id</td><td style="border:1px solid #D8D8D8">Cliente</td><td style="border:1px solid #D8D8D8">Monto</td><td style="border:1px solid #D8D8D8" width="48%"><table style="color:#FFFFFF"><tr><td>Facturacion</td><td><input type="checkbox" class="checkFacts"></td></tr></table></td></tr>');
    $.ajax({
      data:{idruta:$("#oferta").val().split("->")[0]},
          url:'generarOrdenS.php',
          type: 'POST',
          dataType: "json",
          success: function(callback){

            for(var i=0;i<callback.length;i++){
              var options="";
              if(callback[i]['rfc']){
                var rfcs=callback[i]['rfc'].split(",");
                for(var b=0;b<rfcs.length;b++)
                  options+='<option value="'+rfcs[b].split("-")[0]+'">'+rfcs[b].split("-")[1]+'</option>';
              }
              $(".terminarOrdenTabla").append('<tr align="center"><td style="border:1px solid #D8D8D8">'+callback[i]['id']+'</td><td style="border:1px solid #D8D8D8">'+callback[i]['nombre']+'</td><td style="border:1px solid #D8D8D8">'+callback[i]['monto']+'</td><td style="border:1px solid #D8D8D8"><table><tr><td><input type="checkbox" class="checkFact"></td><td style="width:150px;height:24px"><select class="selectFact" valor="'+i+'" style="visibility:hidden"><option value="0">xxx000000xxx</option>'+options+'</select></td><td id="tdcarga_'+i+'_n" style="width:80px;height:24px;font-size:12px;visibility:hidden;">Cargando...</td><td id="tdti_'+i+'_n" style="width:80px;height:24px;font-size:12px;visibility:hidden;">Ticket: <a href="" class="ahrefti_'+i+'" target="_blank"><img id="imgti_'+i+'_n" src="../images/ticket.png" style="cursor:pointer;"></a></td><td id="tdfa_'+i+'_n" style="visibility:hidden;width:100px;height:24px;font-size:12px;">Factura: <a href="" class="ahreffa_'+i+'" target="_blank"><img id="imgfa_'+i+'_n" src="../images/pdf.png"  style="cursor:pointer" title=""></a></td></tr></table></td></tr>');
            } 

            $('.checkFacts').click(function(){
              if($(this).attr('checked')){
                $('.checkFact').each(function(){
                    var select=$("td:nth-child(2) select",$(this).parents(":eq(1)"));
                    $(select).css('visibility','visible');
                    $(this).attr('checked',true);
                });
              }else{
                $('.checkFact').each(function(){
                    var select=$("td:nth-child(2) select",$(this).parents(":eq(1)"));
                    $(select).css('visibility','hidden');
                    $(this).attr('checked',false);
                });      
              }
            });

            $('.checkFact').click(function(){
              var select=$("td:nth-child(2) select",$(this).parents(":eq(1)"));
              if($(this).attr('checked'))
                $(select).css('visibility','visible');
              else
                $(select).css('visibility','hidden');
            });
        }
    }); 
  });

  $(".cerrarOrden").click(function(){
    $(".terminarOrden").hide();
    $(".btngenerarOrden").show();
    $(".comprimirDescarga").empty();
  });

  $(".btngenerarOrden").click(function(){
    $(".btngenerarOrden").css("display","none");
    var factClientes=new Array();
    var contador=0;
    $('.checkFact').each(function(){
      var select=$("td:nth-child(2) select",$(this).parents(":eq(1)")).val();
      var idcliente=$("td:nth-child(1)",$(this).parents(":eq(5)")).html();
      if($(this).attr('checked'))
        factClientes[contador]=idcliente+"-"+select;
      else
        factClientes[contador]=idcliente+"-";
      contador++;
    });
    var array=jQuery('#oferta').val().split('->')[0]+"="+factClientes.join();
    exp1=array.split('=');
    id_orden=exp1[0];
    exp2=exp1[1].split(',');
    $(".alertaOrden").css("visibility","visible");
    setTimeout(ventayfacturacion, 100, id_orden, exp2);
    return false;
    
  });

});//document

function ventayfacturacion(id_orden, exp2){
  x=0;
  var carpeta="";

  $.each(exp2, function( key, value ) {

    $("#tdcarga_"+x+"_n").css('visibility','visible');
    $.ajax({
      async:false,
      data:{ido:id_orden,cad:value,carpeta:carpeta},
      url:'ventas.php',
      type: 'POST',
      dataType:'json',
      success: function(resp){
        if(resp.success==1){
          carpeta=resp.carpeta;
          $("#tdti_"+x+"_n").css('visibility','visible');
          $(".ahrefti_"+x).attr('href','../../punto_venta_nuevo/ticket.php?idventa='+resp.idVenta);
          $.ajax({
            async:false,
            data:{bloqueo:resp.bloqueo, idVenta:resp.idVenta, idProd:resp.idProd, idFact:resp.idFact, cantidad:resp.cantidad, precio:resp.precio, carpeta:resp.carpeta},
            url:'smsfact.php',
            type: 'POST',
            dataType:'json',
            success: function(data){

              switch(data.success){

                case 0:
                  $("#tdcarga_"+x+"_n").css('visibility','hidden');
                  $("#tdfa_"+x+"_n img").attr('src','../images/error.png');
                  $(".ahreffa_"+x).removeAttr("target");
                  $(".ahreffa_"+x).attr('href','javascript:void(0)');
                  $("#tdfa_"+x+"_n").css('visibility','visible');
                  $("#tdfa_"+x+"_n img").attr("title",data.mensaje);
                break;

                case 1:
                  azu = data.azurian;
                  uid = data.datos.UUID;
                  correo = data.correo;
                  var doc=1;
                  if(resp.bloqueo==0)
                    doc=2;

                  $.ajax({
                      async:false,
                      type: 'POST',
                      url: 'smsfunciones.php',
                      data: {
                          UUID: uid,
                          noCertificadoSAT: data.datos.noCertificadoSAT,
                          selloCFD: data.datos.selloCFD,
                          selloSAT: data.datos.selloSAT,
                          FechaTimbrado: data.datos.FechaTimbrado,
                          idComprobante: data.datos.idComprobante,
                          idFact: data.datos.idFact,
                          idVenta: data.datos.idVenta,
                          noCertificado: data.datos.noCertificado,
                          tipoComp: data.datos.tipoComp,
                          trackId: data.datos.trackId,
                          monto: data.monto,
                          cliente: resp.idCliente,
                          idRefact: 0,
                          azurian: data.azurian,
                          doc: doc,
                          carpeta:carpeta,
                          metodo:'guardarFacturacion'
                      },
                      beforeSend: function() {

                      },
                      success: function(resp) {
                          $("#tdcarga_"+x+"_n").css('visibility','hidden');
                          $(".ahreffa_"+x).attr('href','../../facturas/'+uid+'.pdf');
                          $("#tdfa_"+x+"_n").css('visibility','visible');
                          $.ajax({
                              async: false,
                              type: 'POST',
                              url: 'smsfunciones.php',
                              data: {
                                  uid: uid,
                                  correo: correo,
                                  azurian: azu,
                                  doc: doc,
                                  carpeta:carpeta,
                                  metodo:'envioFactura'
                              },
                              beforeSend: function() {

                              },
                              success: function(resp) {
                              },
                              error: function() {
                              }
                          });
                      },
                      error: function() {
                      }
                  });
                break;

                case 5:
                  var doc=1;
                  if(resp.bloqueo==0)
                    doc=2;

                  $.ajax({
                      async:false,
                      type: 'POST',
                      url:'smsfunciones.php',
                      data:{
                          azurian:data.azurian,
                          idFact:resp.idFact,
                          precio:resp.precio,
                          cliente:resp.idCliente,
                          trackId:0,
                          idVenta:resp.idVenta,
                          doc:doc,
                          metodo:'pendienteFacturacion'
                      },
                      beforeSend: function(){
                      },
                      success: function(resp){  
                        $("#tdcarga_"+x+"_n").css('visibility','hidden');
                        $("#tdfa_"+x+"_n img").attr('src','../images/error.png');
                        $(".ahreffa_"+x).removeAttr("target");
                        $(".ahreffa_"+x).attr('href','javascript:void(0)');
                        $("#tdfa_"+x+"_n").css('visibility','visible');
                        $("#tdfa_"+x+"_n img").attr("title",data.mensaje);
                      }
                  });
                break;

                case "500":
                  alert(data.mensaje);
                break;

                case "-1":
                  $("#tdcarga_"+x+"_n").css('visibility','hidden');
                break;

                case "3":
                  $("#tdcarga_"+x+"_n").css('visibility','hidden');
                  $("#tdti_"+x+"_n").css('visibility','visible');
                break;
              }
            }
          });
        }else{
          //$("#tdti_"+x+"_n img").attr('src','../images/error.png');
          //$("#tdti_"+x+"_n").css('visibility','visible');
        }
        x++;
      }
    });
    $(".alertaOrden").css("visibility","hidden");
  });

  $.ajax({
      async:false,
      type: 'POST',
      url:'smsfunciones.php',
      data:{
          carpeta:carpeta,
          metodo:'crearZip'
      },
      beforeSend: function() {

      },
      success: function(resp){  
        $(".comprimirDescarga").append('<div><font style="font-size:14px;color:#585858"><a href="'+resp+'">'+resp+'</a></font></div>');
      }
  });  
}

function returnCambio(s){
    if(s==1){
      var rutas =jQuery('#oferta').val();
    }else{
      var rutas =jQuery('#ruta').val();
    }
    
    var vectorLayerm=null;
    var idruta = rutas.split('->');  
                  
    var las = new OpenLayers.LonLat(feature.geometry.x,feature.geometry.y).transform(toProjection, fromProjection);
    var lon= las.lon
    var lat= las.lat; 
    showloader();

    var pos = new OpenLayers.LonLat(lon,lat).transform(fromProjection, toProjection);

//         
    mapa.addControl(new OpenLayers.Control.LayerSwitcher());  
//         
    puntos.length=0;
        //////////////////////////////////////////////
    $.post('test2.php',{ruta:idruta[0]},
    function(respuesta) {

      res=respuesta.split(',');
      for (x=0;x<res.length;x++){
        puntos.push(res[x]);
      }
   
      var opt = $("input[name='mapOpt']:checked").val();
      var origen= $("#origen option:selected").text();
      $.post('geolo2.php',{idruta:idruta[0],lon:lon,lat:lat,opt:opt,origen:origen},
      function(respues) {
         var array = respues.split("{sep}");
         $(".contenedorIzquierdo").html(array[0]);
         //$(".generarOrden").show();
         sm = getLineStyle();     
         archivo=array[1];
         kmlLayer = new OpenLayers.Layer.Vector("ruta", { styleMap : sm,
                                                           projection: mapa.displayProjection,
                                                           strategies: [new OpenLayers.Strategy.Fixed()],
                                                           protocol: new OpenLayers.Protocol.HTTP({ url:array[1],
                                                                                                    format: new OpenLayers.Format.GeoJSON({ extractStyles: true,
                                                                                                                                             extractAttributes: true,
                                                                                                                                             maxDepth: 2 }),
                                                                                                    callback: function(){
                                                                                                      
                                                                                                    }
                                                                                                    //callback: callBack
                                                                                                  })                                                                                            
                                                });
           
                 mapa.addLayer(kmlLayer);   
                 hideRegis();
        });
             
        capavector = new OpenLayers.Layer.Vector('Marcadores');
          // }

        for (x=0;x<puntos.length;x++){
          //alert(puntos[x]);
          var pos = new OpenLayers.LonLat(puntos[x],puntos[x+1]).transform(fromProjection, toProjection); 
          feature = new OpenLayers.Feature.Vector( new OpenLayers.Geometry.Point(pos.lon, pos.lat),
                                                   {some:'data'},
                                                   {externalGraphic: 'marker.png', graphicHeight: 37, graphicWidth: 32});
          capavector.addFeatures(feature);
          mapa.addLayer(capavector); 
        }

      });
}
</script> 
</html> 
