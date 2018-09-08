<link rel="stylesheet" type="text/css" href="../../modulos/cont/css/jquery-ui.css"/>
<script type="text/javascript" src="../../modulos/cont/js/jquery-ui.js"></script>


<script>
    $( document ).ready(function() {
        alert('doekodekodekdoekdoeokeok');
            $(".trencabezado").append("<td>Imprimir</td>");
            
            $contenido="	<td align='center'>";
            $contenido+="		<img src='../../modulos/restaurantes/images/impresora.jpeg' style='cursor:pointer;' border='0' onclick='closeComanda(this,0)'/>";
            $contenido+="	</td>";
            
            $(".trcontenido").append($contenido);
        });

    function imprime(obj,tipo){
       var idcomanda=$(obj).parent().parent().children("td:nth-child(1)").html();
       alert(idcomanda);
    }
            function closeComanda(obj,tipo){
                var idcomanda=$(obj).parent().parent().children("td:nth-child(1)").html();
                var idmesa=$(obj).parent().parent().children("td:nth-child(2)").html();
                var tipo = 0;   
                var pbandera = 0;
//
                //$(".GtableCloseComanda").css('visibility', 'hidden');
                $.ajax({
                    data:{idComanda:idcomanda, bandera:pbandera, idmesa:idmesa, tipo:tipo},
                    url:'ajax.php?c=comandas&f=closeComanda',
                    type: 'GET',
                    dataType: 'json',
                    success: function(callback){
                        var persona = 0;
                        var totalPersona=0;
                        var totalComanda=0;
                        var idComanda=idcomanda;
                        var bandera=0;

                        if(callback['tipo']==0){
                            var html = '<div style="text-align:left;font-size:14px"><div><input type="image" src="../../netwarelog/archivos/1/organizaciones/x.png" style="width:180px"/></div><div style="border-bottom:1px solid;border-top:1px solid;font-size:12px;font-family:Arial;margin-top:10px;padding-top:8px">Comanda No:'+idComanda+'</div>';
                            var bcontent="";
                            var codigo="";
                            
                            $.each(callback['rows'], function(index, value) {
                             
                                if(persona!=value['npersona']){
                                    html=html.replace(">Persona No:"+persona,">Persona No: "+persona+" $"+totalPersona.toFixed(2));
                                    html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">Persona No:'+value['npersona']+'</div>';
                                    persona=value['npersona'];
                                    totalPersona=0;
                                }
                                if(!bandera){
                                    bandera=1;
                                    if(value['tipo']=="1"){
                                        bcontent='<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Nombre: '+value['nombreu']+'</div>';
                                    }
                                    if(value['tipo']=="2"){
                                        bcontent='<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Nombre: '+value['nombreu']+'</div><div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Domicilio: '+value['domicilio']+'</div>';
                                    }
                                    codigo=value['codigo'];
                                }
                 
                                html += '<div style="margin-left:15px"><table style="font-size:11px;font-family:Arial;border-collapse:collpase"><tr><td>'+value['cantidad']+'</td><td>'+value['nombre']+'</td><td>'+parseFloat(value['precioventa']).toFixed(2)+'</td></tr></table></div>';
                                totalPersona+=parseFloat(value['precioventa']*parseFloat(value['cantidad']));
                                totalComanda+=parseFloat(value['precioventa']*parseFloat(value['cantidad']));
                            });
                  
                            html=html.replace(">Persona No:"+persona,">Persona No: "+persona+" $"+totalPersona.toFixed(2));
                            html=html.replace(">Comanda No:"+idComanda,">Comanda No: "+idComanda+" $"+totalComanda.toFixed(2));
                            var propina=totalComanda*.10;
                            html+=bcontent; 
                            html += '<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Propina sugerida: '+propina.toFixed(2)+'</div><div style="margin-top:10px;"><input type="image" src="../punto_venta/barcode/barras.php?c=barcode&barcode='+codigo+'&text='+codigo+'&width=190" onload="window.print();" style="width:190px;margin-left:-3px;" id="barcode"/></div></div>';
                            bandera=0;
                            bcontent="";
                            var ventana=window.open('','_blank','width=207.874015748,height=10,leftmargin=0');  //abrimos una ventana vacía nueva
                            $(ventana).ready(function(){
                                ventana.document.write(html);  //imprimimos el HTML del objeto en la nueva ventana
                                ventana.resizeTo(207.87,ventana.document.body.firstElementChild.clientHeight);
                                ventana.document.close();  //cerramos el documento
                                //ventana.print();  //imprimimos la ventana
                                setTimeout(closew,1000);
                                function closew(){
                                    ventana.close();
                                    var pathname = window.location.pathname;
                                    $("#tb1594-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
                                }
                            });
                            
                            
                            
                        }
                        if(callback['tipo']==1){
                            var html = '<div style="text-align:left;font-size:14px"><div><input type="image" src="../../netwarelog/archivos/1/organizaciones/x.png" style="width:180px"/></div>';
                            var cuser=html;
                            var bcontent="";
                            var codigo="";
                            $.each(callback['rows'], function(index, value) {
                             
                                if(persona!=value['npersona']){
                                    if(persona!=0){
                                        var propina=totalPersona*.10;
                                        cuser=cuser.replace(">Persona No:"+persona,">Persona No: "+persona+" $"+totalPersona.toFixed(2));
                                        cuser+=bcontent;    
                                        cuser += '<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Propina sugerida: '+propina.toFixed(2)+'</div><div style="margin-top:10px"><input type="image" src="../punto_venta/barcode/barras.php?c=barcode&barcode='+codigo+'&text='+codigo+'&width=190" onload="window.print();" style="width:190px" id="barcode"/></div></div>';
                                        bandera=0;
                                        bcontent="";
                                        totalPersona=0;
                                        var ventana=window.open('','_blank','width=207.874015748,height=10,leftmargin=0');  //abrimos una ventana vacía nueva
                                        $(ventana).ready(function(){
                                            ventana.document.write(cuser);  //imprimimos el HTML del objeto en la nueva ventana
                                            ventana.resizeTo(207.87,ventana.document.body.firstElementChild.clientHeight);
                                            ventana.document.close();  //cerramos el documento
                                            //ventana.print();  //imprimimos la ventana
                                            cuser=html;
                                            setTimeout(closew,1000);
                                            function closew(){
                                                ventana.close();
                                            }
                                        });
                                    }
                                    cuser += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">Persona No:'+value['npersona']+'</div>';
                                    persona=value['npersona'];
                                    totalPersona=0;
                                }
                                if(!bandera){
                                    bandera=1;
                                    if(value['tipo']=="1"){
                                        bcontent='<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Nombre: '+value['nombreu']+'</div>';
                                    }
                                    if(value['tipo']=="2"){
                                        bcontent='<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Nombre: '+value['nombreu']+'</div><div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Domicilio: '+value['domicilio']+'</div>';
                                    }
                                    codigo=value['codigo'];
                                }
                 
                                cuser += '<div style="margin-left:15px"><table style="font-size:11px;font-family:Arial;border-collapse:collpase"><tr><td>'+value['cantidad']+'</td><td>'+value['nombre']+'</td><td>'+parseFloat(value['precioventa']).toFixed(2)+'</td></tr></table></div>';
                                totalPersona+=parseFloat(value['precioventa']*parseFloat(value['cantidad']));
                            });
                            
                            if(persona!=0){
                                var propina=totalPersona*.10;
                                cuser=cuser.replace(">Persona No:"+persona,">Persona No: "+persona+" $"+totalPersona.toFixed(2));
                                cuser+=bcontent;
                                cuser += '<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Propina sugerida: '+propina.toFixed(2)+'</div><div style="margin-top:10px"><input type="image" src="../punto_venta/barcode/barras.php?c=barcode&barcode='+codigo+'&text='+codigo+'&width=190" onload="window.print();" style="width:190px" id="barcode"/></div></div>';
                                bandera=0;
                                bcontent="";
                                totalPersona=0;
                                var ventana=window.open('','_blank','width=207.874015748,height=10,leftmargin=0');  //abrimos una ventana vacía nueva
                                $(ventana).ready(function(){
                                    ventana.document.write(cuser);  //imprimimos el HTML del objeto en la nueva ventana
                                    ventana.resizeTo(207.87,ventana.document.body.firstElementChild.clientHeight);
                                    ventana.document.close();  //cerramos el documento
                                    //ventana.print();  //imprimimos la ventana
                                    cuser=html;
                                    setTimeout(closew,1000);
                                    function closew(){
                                        ventana.close();
                                        var pathname = window.location.pathname;
                                        $("#tb1594-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
                                    }
                                });
                            }
                        }
                     /*  if(callback['tipo']==2){
                            if(callback['rows'][0]['respuesta']=="ok"){

                                var outElement=$("#tb1594-u",window.parent.document).parent();
                                var caja=outElement.find("#tb1238-u");
                                var pestana=$("body",window.parent.document).find("#tb1238-3");
                                var openCaja=$("body",window.parent.document).find("#mnu1024").children().first().children().first();
                                var pathname = window.location.pathname;
                                var url=document.location.host+pathname;
                                //if(caja.length>0){
                                    var campoBuscar=$(".frurl",caja).contents().find("#search-producto");
                                    pestana.trigger("click");
                                    campoBuscar.trigger("focus");
                                    //campoBuscar.trigger("click");
                                    campoBuscar.val(callback['rows'][0]['comanda']);
                                    campoBuscar.trigger({type: "keypress", which: 13});

                            }
                        } */
                        
                    }
                });
            }









</script>                