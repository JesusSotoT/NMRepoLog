<?php
    include "../../netwarelog/catalog/conexionbd.php";
    include_once 'funcionesBD/conexionbda.php';
    
    $consult = new Consult;
    $conection = $consult->conection($servidor, 'nmdevel', 'nmdevel', 'nmdev_common');
    
    $result = $consult->articulos($conection);
    if($result->num_rows >= 1) {
        while($row=$result->fetch_array(MYSQLI_ASSOC)) {
            $articulos[] = $$row;
        }
    } else {
        $articulos = 0;
    }
?>

<!Doctype html>
<html>
    <head>
        <title>Art&iacute;culos de proveedores</title>
        
        <link rel="stylesheet" type="text/css" href="css/hb_art.css">
        <link rel="stylesheet" type="text/css" href="css/estilo.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        
        <style type="text/css">
            #tabs.ui-widget-content{
                border: none;
            }
            
            .ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
                border-bottom-right-radius: 0;
            }
            
            .ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-br {
                border-bottom-left-radius: 0;
            }
            
            .ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
                border-top-right-radius: 0;
            }
            
            .ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
                border-top-left-radius: 0;
            }
            
            table a:link {
                color: #666;
                font-weight: bold;
                text-decoration: none;
            }
            
            table a:visited {
                color: #999999;
                font-weight: bold;
                text-decoration: none;
            }
            
            table a:active, table a:hover {
                color: #bd5a35;
                text-decoration: underline;
            }
            
            table {
                font-family: Arial, Helvetica, sans-serif;
                color: #666;
                font-size: 11px;
                text-shadow: 1px 1px 0px #fff;
                background: #eaebec;
                margin: 0px;
                border: #ccc 1px solid;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                border-radius: 3px;
                -moz-box-shadow: 0 1px 2px #d1d1d1;
                -webkit-box-shadow: 0 1px 2px #d1d1d1;
                box-shadow: 0 1px 2px #d1d1d1;
            }
            
            table th {
                padding: 15px 25px 13px 15px;
                border-top: 1px solid #fafafa;
                border-bottom: 1px solid #fafafa;
                background: #ededed;
                background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
                background: -moz-linear-gradient(top, #ededed, #ebebeb);
            }
            
            table th:first-child {
                text-align: left;
                padding-left: 20px;
            }
            
            table:first-child th:first-child{
                -moz-border-radius-topleft: 3px;
                -webkit-border-top-left-radius: 3px;
                border-top-left-radius: 3px;
            }
            
            table:first-child th:last-child {
                -moz-border-radius-topright: 3px;
                -webkit-border-top-right-radius: 3px;
                border-top-right-radius: 3px;
            }
            
            table tr {
                text-align: center;
                padding-left: 20px;
            }
            
            table td:first-child {
                text-align: left;
                padding-left: 20px;
                border-left: 0;
            }
            
            table td {
                padding: 14px;
                border-top: 1px solid #ffffff;
                border-bottom: 1px solid #e0e0e0;
                border-left: 1px solid #e0e0e0;
                background: #fafafa;
                background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
                background: -moz-linear-gradient(top, #fbfbfb, #fafafa);
            }
            
            table tr.even td {
                background: #f6f6f6;
                background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
                background: -moz-linear-gradient(top, #f8f8f8, #f6f6f6);
            }
            
            table tr:last-child td {
                border-bottom: 0;
            }
            
            table tr:last-child td:first-child {
                -moz-border-radius-bottomleft: 3px;
                -webkit-border-bottom-left-radius: 3px;
                border-bottom-left-radius: 3px;
            }
            
            table tr:last-child td:last-child {
                -moz-border-radius-bottomright: 3px;
                -webkit-border-bottom-right-radius: 3px;
                border-bottom-right-radius: 3px;
            }
            
            table tr:hover td {
                background: #f2f2f2;
                background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
                background: -moz-linear-gradient(top, #f2f2f2, #f0f0f0);
            }
        </style>
        
        <script type="text/javascript" src="js/jquery-1-10-2-min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/multi.js"></script>
        
        <script>
            $( document ).ready(function() {
                $( "#tabs" ).tabs();
                $("#selectQry2").multiselect();
            });
            
            function desplegar(id) {
                $('.loadsart').empty();
                $('.linesep').css('display', 'block');
                $('#line_'+id+'_sep').css('display', 'none');
                $('#load_'+id+'_load').html('<div id="lod_" style="float: left; width: 700px; margin: 20px 0 20px 0; text-align: center;"> <img src="images/loading.gif"> </div>');
                $('.ctitle_t').css('background-color', '#fafafa');
                $('.ctitle_t').css('color', '#000000');
                $('#title_'+id+'_t').css('background-color', '#069');
                $('#title_'+id+'_t').css('color','#ffffff');
                $('#load_'+id+'_load').css('display', 'block');
                url='smsAjax.php';
                
                $.ajax({
                    url:url,
                    type: 'POST',
                    dataType: 'json',
                    data: {funcion:'desplegar_articulos', id:id, con:1},
                    success: function(obj) {
                        console.log(obj);
                        if(obj.coms!==0) {
                            v='';
                            $.each(obj.coms, function( key, value ) {
                                v+='<div id="area_'+id+'com" style="float: left; width: 700px; margin: 5px 0 5px 0;">\
                                    <textarea disabled align="center" id="val_'+id+'_area" style="wudth: 660px; border: 1px none #cecece; min-height: 50px; margin-left: 20px; resize: none; color: #color: #666;">'+value.comentario+'</textarea>\
                                    </div>';
                            });
                        } else {
                            v='';
                            v+='<div id="area_'+id+'_com" style="float: left; width:700px; margin: 5px 0 5px 0;"></div>';
                        }
                        
                        $('#load_'+id+'_load').html('\
                            <div id="cont__art" style="float: left; width: 700px; background-color: #fafafa;">\
                                <div id="foto__desc" style="float: left; width: 700px;margin-top: 5px;">\
                                    <div id="ft__ft" style="float: left; width:240px; height:180px; margin-right:10px; background-color: #cccccc;">\
                                        '+obj.imagen+'\
                                    </div>\
                                    <div id="des__des" style="float: left; width: 440px; height: 180px;">\
                                        '+obj.descripcion+'\
                                    </div>\
                                </div>\
                            </div>\
                            <div id="cont__resp" style="float: left; width: 700px; background-color: #fafafa;">\
                                <div id="resp__desc" style="float: left; width;700px; margin-top: 5px;">\
                                    <div id="txt__area" style="float: left; width700px; background-color: #fafafa;">\
                                        <textarea id="val_'+id+'_area" style="width:700px; border: 1px dashed #cecece; height:80px;" placeholder="Escribe una respuesta"></textarea>\
                                    </div>\
                                    <div id=btn__res" style="float: left; width: 700px; margin: 5px 0 5px 0;">\
                                        <input id="br_'+id+'_br" type="button" value="Responder" onclick="respuesta_com('+id+');" style="float: right;">\
                                        <img id="wa_'+id+'_wa" src="images/wait.gif" style="display: none; float: right;">\
                                    </div>\
                                </div>\
                            </div>\
                            <div id="cont__coments" style="float: left; width: 700px; background-color#fafafa; padding: 0 0 20px 0;">\
                                <div id="resp__coments" style="float: left; width: 700px; margin-top: 5px;">\
                                    <div id="tit_com" style="float: left; width: 680px; margin-left: 20px; font-size: 14px;">\
                                        Comentarios\
                                    </div>\
                                    '+v+'\
                                </div>\
                            </div>\
                        ');
                    }
                });
            }
            
            function respuesta_com(id) {
                coment = $('#val_'+id+'_area').val();
                if(coment.match(/<|>|\'|\"|-/)) {
                    alert('El comentario tiene caracteres inv&aacute;lidos');
                    return false;
                }
                
                if(coment=='') {
                    alert('Escribe un comentario');
                    return false;
                }
                
                $("#br_"+id+"_br").css("display", "none");
                $("wa_"+id+"_wa").css("display", "block");
                url='smsAjax.php';
                $.ajax({
                   url: url,
                   type: 'POST',
                   data: {funcion:'crear_comentario', coment:coment, id:id, con:1},
                   success: function(obj) {
                       $('#area_'+id+'_com').prepend('<textarea disabled align="center" id="val_'+id+'_area" style="width: 660px; border: 1px none #cecece; min-height: 50px; margin-left: 20px; resize: none; color: #color: #666; margin-bottom: 11px;">'+coment+'</textarea>').fadeIn('slow');
                       $("#wa_"+id+"_wa").css("display", "none");
                       $("#br_"+id+"_br").css("display", "block");
                       $("#val_"+id+"_area").val("");
                   }
                }),
            }
            
            function crearArticulo() {
                titulo = $("#tit_arti").val();
                contenido = $("#con_arti").val();
                if(titulo.match(/<|>|\'|\"|-/)) {
                    alert('Hay caracteres inv&aacute;lidos');
                    return false;
                }
                
                if(contenido.match(/<|>|\'|\"\-/)) {
                    alert('Hay caracteres inv&aacute;lidos');
                    return false;
                }
                
                if(titulo=='') {
                    alert('Tienes que agregar un t&iacute;tulo');
                    return false;
                }
                
                if(contenido=='') {
                    alert('Tienes que agregar un contenido');
                    return false;
                }
                
                $("#creart").css("display", "none");
                $("#wait").css("display", "block");
                $.ajax({
                    url:url,
                    type: 'POST',
                    data: {funcion:'crear_articulo', titulo:titulo, contenido:contenido, con:1},
                    success: function(obj) {
                        src = $('#tb1368-u iframe', window.parent.document).attr('src');
                        $('#tb1638-u iframe', window.parent.document).attr('src', src );
                        $("#wait").css("display", "none");
                        //$('#frurl').attr('src', $('#frurl').attr('src'));
                    }
                });
            }
            
            function invitar(){
                s = $("#selectQry22").multiselect("getChecked");
                c = '';
                $.each( s, function( key, value ) {
                    v=$(this).val();
                    c+=v+',';
                });
                
                if(c=='') {
                    alert("tienes que seleccionar al menos un cliente");
                    return false;
                }
                
                $("#resultados2").css('display', 'none');
                $("resultados-load2").css('display', 'block');
                url='smsAjax.php';
                $.ajax({
                    url:url,
                    type: 'POST',
                    data: {funcion:'invitar_cliente', c:c},
                    success: function(obj) {
                        $("#resultados-load2").css('display', 'none');
                        alert('Se ha mandado una invitacion.');
                    }
                });
            }
            
            function asignar(){
                s = $("selectQry").multiselect("getCheched");
                sg = $("selectQry").multiselect("getChecked");
                c = '';
                g = '';
                $.each( s, function( key, value ) {
                    v=$(this).val();
                    g+=v+',';
                });
                
                if(c=='') {
                    alert("Tienes que selecionar al menos un cliente");
                    return false;
                }
                
                if(g=='') {
                    alert("Tienes que seleccionar al mnos un grupo");
                    return false;
                }
                
                $("#resultados").css('display', 'none');
                $("#resultados-load").css('display', 'block');
                url='smsAjax.php';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {funcion:'asignar_grupo', c:c, g:g},
                    //datatype 'JSON'
                    success: function(obj){
                        $("#resultados-load").css('display', 'none');
                        alert('Se han asignado los clientes a los grupos seleccionados');
                    }
                });
            }
            
            function buscar(busqueda){
                $("#resultados-load-not").css('display', 'none');
                $("#resultados").css('display', 'none');
                $("#resultados-load").css('display', 'block')
                var data = new Object();
                data["funcion"]     = "sms_busqueda";
                data["tipo"]        = busqueda;
                url='smsAjax.php';
                
                if(busqueda==1) {
                    data["cmbEstados"]  = $('#cmbEstados').val();
                    data["cmbGrupos"]   = $('#cmbGrupos').val();
                    data["cmbRubros"]   = $('#cmbRubros').val();
                    data["cmbTtienda"]  = $('#cmbTtienda').val();
                }
                if(busqueda==0) {
                    data["cmbClientes"] = $('#cmbClientes').val();
                }
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(obj) {
                        if(obj==0) {
                            $("#resultados-load").css('display', 'none');
                            $("#resultados-load.not").css('display', 'block');
                            return false;
                        }
                        
                        $("#divbusquedaqry").html('<select id="selectQry" style="width: 248px;"></select>');
                        $.each( obj, function( key, value ) {
                            $('#selectQry').append('<option value="'+value.id+'">'+value.nombre+'</option>');
                        });
                        $("#selectQry").multiselect();
                        $(".ui-multiselect-none").trigger( "click" );
                        $("#resultados-load").css('display', 'none');
                        $("#resultados").css('display', 'block');
                    }
                });
            }
            
            function buscar2(busqueda) {
                $("#resultados-load-not2").css('display', 'none');
                $("#resultados2").css('display', 'none');
                $("#resutlasos-load2").css('display', 'block');
                
                var data = new Object();
                data["funcion"] = "sms_busqueda";
                data["tipo"]    = busqueda;
                data["con"]     = 1;
                url='smsAjax.php';
                
                if(busqueda==1) {
                    data["cmbEstados"]  = $('#cmbEstados2').val();
                    data["cmbGrupos"]  = $('#cmbGrupos2').val();
                    data["cmbRubros"]  = $('#cmbRubros2').val();
                    data["cmbTtienda"]  = $('#cmbTtienda').val();
                }
                if(busqueda==0){
                    data["cmbClientes"] = $('#cmbClientes2').val();
                }
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(obj) {
                        if(obj==0) {
                            $("#resultados-load2").css('display', 'none');
                            $("#resultados-load-not2").css('display', 'block');
                            return false;
                        }
                        
                        $("#divbusquedaqry2").html('<select id="selectQry22" style="width: 248px;"></select>');
                        $.each( obj, function( key, value ) {
                            $('#selectQry22').append('<option value="'+value.id'">'+value.nombre+'</option>');
                        });
                        
                        $("#selectQry22").multiselect();
                        $(".ui-multiselect-none2").trigger( "click" );
                        $("#resultados-load2").css('display', 'none');
                        $("#resultados2").css('display', 'block');
                    }
                });
            }
        </script>
    </head>
    
    <body>
        <div id="contenedor" style="float:left; width: 720px;">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Art&iacute;culos</a></li>
                    <li><a href="#tabs-2">Crear art&iacute;culo nuevo</a></li>
                </ul>
                
                <div id="tabs-1" style="font-size: 11px;">
                    <div style="float: left; width: 100%; margin: 0px 0 15px 0; font-size: 18px;">
                        Art&iacute;culos publicados
                    </div>
                    
                    <?php foreach ($articulos as $key => $value) { ?>
                        <div id="line_<?php echo $value['id_articulo']; ?>_sep" class='linesep' style="float: left; width: 700px; border-top: 1px solid #dddddd; border-bottom: 1px solid #ffffff;"></div>
                        <div class="ctitle_t" id="title_<?php echo $value['id_articulo']; ?>_t" style="float: left; width: 700px; background-color: #fafafa; color: #000;">
                            <div style="float: left; width: 670px; margin: 5px 0 5px 10px; font-size: 12px;">
                                <?php echo $value['titulo']; ?>
                            </div>
                            <div style="float: right; width: 20px; margin: 6px 0 6px 0; font-size: 12px;">
                                <img src="images/add.png" style="cursor: pointer;" onclick="desplegar('<?php echo $value['id_articulo']; ?>');">
                            </div>
                        </div>

                        <div class="loadsart" id="load_<?php echo $value['id_articulo']; ?>_load" style="float: left; display: none;">
                            <div id="lod_" style="float: left; width: 700px; margin: 20px 0 20px 0; text-align: center;">
                                Cargando...
                            </div>
                        </div>
                    <?php } ?>    
                </div>
                
                <div id="tabs-2" style="font-size: 11px;">
                    <div style="float: left; width: 100%; margin: 0px 0 5px 0px; font-size: 18px;">
                        Art&iacute;culo nuevo
                    </div>
                    <div style="float: left; width: 100%; margin: 15px 0 0px 0; font-size: 12px">
                        Titulo
                    </div>
                    
                    <div style="float: left; width: 600px; margin: 2px 0 5px 0; font-size: 12px;">
                        <input id="tit_arti" type="text" style="width: 580px; border: 1px solid #bdbdbd;">
                    </div>
                    <div style="float: left; width: 100%; margin: 15px 0 0px 0; font-size: 12px;">
                        Contenido
                    </div>
                    <div style="float: left; width: 600px; margin: 2px 0 5px 0; font-size: 12px;">
                        <textarea id="con_arti" style="width: 580px; height: 100px; border: 1px solid #bdbdbd;"></textarea>
                    </div>
                    
                    <div style="float: left; width: 230px; margin: 5px 0 5px 0; font-size: 12px;">
                        <input id="creart" type="button" value="Crear" onclick="crearArticulo();">
                        <img id="wait" src="images/wait.gif" style="display: none;">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
