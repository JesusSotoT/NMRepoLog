<?php

    if (!isset($_SESSION)) {
        session_start();
    }

    if(!isset($_SESSION["accelog_menus"]))
    {
        header("location: index.php");
    }

    // Security
    ini_set("display_errors",0);
    ini_set('session.cookie_httponly',1);

    /*///////////////////
    Verificación de pago mediante PayPal
    ////////////////////*/

    //ini_set('display_errors', 1); error_reporting(E_ALL);
    /*global $global_path;
    $global_path = "../../";
    include_once $global_path .'modulos/perfil/models/pago.php';
    $paypal_pago = new PagoModel();
    $verificar_pago_paypal = $paypal_pago->pagar(2, null, $_REQUEST);
    if(!$verificar_pago_paypal["status"]){
        echo $verificar_pago_paypal["echo"];   
    }
    if($_SESSION["accelog_nombre_instancia"] == "mlog"){
        echo '  <script>
                    var instancia_path = "http://localhost/mlog/webapp/netwarelog/accelog/menu.php";
                </script>';
    } else {
        echo '  <script>
                    var instancia_path = "https://www.netwarmonitor.com/clientes/'. $_SESSION["accelog_nombre_instancia"] .'/webapp/netwarelog/accelog/menu.php";
                </script>';
    }*/

    /*///////////////////
    Fin verificación de pago mediante PayPal
    ////////////////////*/

    //************** Begin: Getting Information Session
    if(!isset($_SESSION["accelog_idorganizacion"])){
        header("Location: index.php");
    }

    $org = $_SESSION["accelog_idorganizacion"];
    $nombre_org = $_SESSION["accelog_nombre_organizacion"];
    $login = $_SESSION["accelog_login"];
    $idempleado = $_SESSION["accelog_idempleado"];
    $idperfil = $_SESSION["accelog_idperfil"];

    $opciones_permitidas = $_SESSION["accelog_opciones"];
    $menus_permitidos = $_SESSION["accelog_menus"];

    include "webconfig_accelog.php";
    include "clases/clarbolmenu.php";

    $nombre="";
    $puesto="";
    $empresa="";
    $datosempresa="";
    $correoelectronico="";
    $foto="";
    $logoempresa="";

    $sQ="SELECT concat(a.nombre,' ',a.apellidos) nombre,
                    p.puesto,o.nombreorganizacion empresa, a.correoelectronico,o.nombreorganizacion, a.foto, o.logoempresa
                    FROM empleados e
                                            left join organizaciones o on e.idorganizacion=o.idorganizacion
                                            left join administracion_usuarios a on a.idempleado=e.idempleado
                                            left join puestos p on p.idpuesto=a.idpuesto
                                        where e.idempleado='$idempleado'";
    $result = $conexion->consultar($sQ);
    while($rs = $conexion->siguiente($result)){
        $nombre=$rs{"nombre"};
        $puesto=$rs{"puesto"};
        $foto=$rs{"foto"};
        $foto = str_replace('C:fakepath', '', $foto);
        $logoempresa=$rs{"logoempresa"};
        $logoempresa = str_replace('C:fakepath', '', $logoempresa);
        $correoelectronico=$rs{"correoelectronico"};
        $empresa=$rs{"nombreorganizacion"};
    }
    $conexion->cerrar_consulta($result);

    if($foto==""){
        $foto="x.png";
    }
    if($logoempresa==""){
        $logoempresa="x.png";
    }
    $anio=date('Y');
    //************** End: Getting Information Session
    //Agregando actualizacion de

?>



    <html>

    <head>

		<link rel="icon" type="image/icon" href="https://www.netwarmonitor.com/assets/img/ico16px.png">
		<link rel="apple-touch-icon" href="https://www.netwarmonitor.com/assets/img/ico60px.png">
		<link rel="apple-touch-icon" sizes="76x76" href="https://www.netwarmonitor.com/assets/img/ico76px.png">
		<link rel="apple-touch-icon" sizes="120x120" href="https://www.netwarmonitor.com/assets/img/ico120px.png">
		<link rel="apple-touch-icon" sizes="152x152" href="https://www.netwarmonitor.com/assets/img/ico152px.png">

    <title>Netwaremonitor</title>

        <!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">-->
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta charset="UTF-8" />

    	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">

		<!--  ##### BOOTSTRAP & FONT ###### -->
    	<link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">

    	<!-- SELECT2 -->
    	<link href="../../libraries/select2/dist/css/select2.min.css" rel="stylesheet">
        <link href="../../libraries/sweetalert/css/sweetalert.css" rel="stylesheet">
        <!--CSS-->
        <?php include('../design/css.php');?>
        <LINK href="../design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
        <LINK href="css/estilo_accelog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSO LOCAL CSS-->
        <LINK href="../catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSOS EXTERNOS COMPATIBILIDAD CATALOG CSS-->
        <LINK href="../../modulos/notificaciones/notificaciones.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSOS EXTERNOS COMPATIBILIDAD CATALOG CSS-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="../../modulos/cont/css/style.css">
        <!--PLUG IN CATALOG
        <script type="text/javascript" src="../catalog/js/jquery.js"></script>-->

		<!--  ##### BEGIN: BOOTSTRAP & JQUERY ###### -->
		<script src="../../libraries/jquery.min.js"></script>
		<script src="../../libraries/jquery.mobile.touch_events.min.js"></script>
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
		<script src="../../libraries/select2/dist/js/i18n/es.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <!--  ##### END: BOOTSTRAP & JQUERY ###### -->
        <script src="../../libraries/sweetalert/js/sweetalert.min.js"></script>
        <script src="../../modulos/cont/js/notificaciones.js" type="text/javascript"></script>

        <script type="text/javascript">

            var estadomenu=1;
            var tabs = new Array();
            var sp;
            var ses = new Array();
            var ntab = 0;
            var tabseleccionado = "";

            var frmfindmenu_interval;
 			function open_frmfindmenu(){
 				$("#cmbmenu").val(null).trigger("change");
				$("#frmfindmenu").modal("show");
        	    frmfindmenu_interval = setInterval(function () {
        	    	$("#cmbmenu").select2("open")
        	    	clearInterval(frmfindmenu_interval);
            	}, 400);
            }


            function chat(){
                $("#lc_chat_layout").toggle("fast");
            }

            function mensajeIcono(tipo, titulo, mensaje, callback)
            {
                swal({
                        title: titulo,
                        text: mensaje,
                        type: tipo,
                        showCancelButton: false,
                        confirmButtonColor: (tipo == "success") ? "#8ED4F5" : "#DD6B55",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                        html: true
                    },
                    (function(){
                        return function(){
                            callback();
                        };
                    }())
                );
            }

            $(document).ready(function() {
              //instancia_actual();

            	// Begin: Find menu ---> Ctrl + Alt + B(66) to find menu
            	$(".select2_cmbmenu").select2({
                	language: "es"
                });
            	$(document).keydown(function(evt){
            	    if (evt.keyCode==66 && (evt.ctrlKey) && (evt.altKey)){
            	        evt.preventDefault();
            	        open_frmfindmenu();
            	    }
            	});
            	$("#cmbmenu").on("select2:select", function(e){
                	var mnu_id_a = $("#cmbmenu").val();
					$("#frmfindmenu").modal("hide");
                	eval($("#"+mnu_id_a).attr("href"));
                });
                /*$("#cmbmenu").on("select2:close", function(e){
                    $("#frmfindmenu").modal("hide");
                });*/
            	// End: End menu


                sp='<?php echo session_id(); ?>';

                //document.getElementById("tdocultar").title="Ocultar menú";
                //document.getElementById("tdhome").title="Inicio";
                //document.getElementById("tdchpwd").title="Cambiar contraseña";
                //document.getElementById("tdexit").title="Salir";

                /*$("#tdhome").click(function () {
                    location.reload();
                });*/

                /*
                $("#tdocultar").click(function () {
                    if(estadomenu==1){
                        document.getElementById("tdocultar").title="Mostrar menú";
                        document.getElementById("tdmenu").style.display="none";''
                        document.getElementById("tdocultar").src="../design/<?php echo $strGNetwarlogCSS;?>/abrir.png";
                        estadomenu=0;
                    } else {
                        document.getElementById("tdocultar").title="Ocultar menú";
                        document.getElementById("tdocultar").alt="Ocultar menú.";
                        document.getElementById("tdmenu").style.display="block";
                        document.getElementById("tdocultar").src="../design/<?php echo $strGNetwarlogCSS;?>/cerrar.png";
                        estadomenu=1;
                    }
                });*/

                //abreultimacat();


                abrirmenuporomision();

				$("#btnleft").bind("tap", move_left);
				$("#btnright").bind("tap", move_right);

				$("#tabs").on("swipeleft", move_left);
				$("#tabs").on("swiperight", move_right);

				$(".ui-loader").hide();

				//alert("llegue");
                $("#divloading").fadeOut("slow");
                

                if(typeof mostrar_mensaje_pagado_anteriormente !== 'undefined' && mostrar_mensaje_pagado_anteriormente){
                    mensajeIcono("success", "Un momento...", "El pago ya ha sido procesado correctamente", function(){
                        window.parent.location.href = instancia_path;
                    });
                    return;
                } else if(typeof mostrar_mensaje_cancelado !== 'undefined' && mostrar_mensaje_cancelado){
                    mensajeIcono("error", "Un momento...", "Haz cancelado el pago mediante PayPal, por favor intenta con otro método de pago", function(){
                        miPerfil();
                    });
                    return;
                } else if(typeof mostrar_mensaje_pagado_correctamente !== 'undefined' && mostrar_mensaje_pagado_correctamente){
                    mensajeIcono("success", "Listo!", "El pago ha sido procesado correctamente", function(){
                        window.parent.location.href = instancia_path;
                    });
                    return;
                } else if(typeof mostrar_mensaje_pagado_erroneamente !== 'undefined' && mostrar_mensaje_pagado_erroneamente){
                    mensajeIcono("error", "Un momento...", "No se ha podido procesar el pago debido a " + mostrar_mensaje_pagado_erroneamente, function(){
                        miPerfil();
                    });
                    return;
                }

                /*if(typeof cobranza != 'undefined'){
                    if(tipo_usuario == '(3)' || tipo_usuario == '(5)' || tipo_usuario == '(2)'){
                        mensajeIcono("info", "Pago pendiente", "Tu instancia ha sido suspendida debido a que no fue realizado el pago de los productos, por favor te pedimos completes el pago para poder seguir disfrutando de nuestros productos.", function(){
                            miPerfil();
                        });
                    }else{
                        mensajeIcono("info", "Pago pendiente", "La instancia ha sido suspendida debido a que no fue realizado el pago de los productos, te pedimos te comuniques con tu administrador para solicitarle que realice el pago y así puedas continuar con tu trabajo.", function(){
                            salir();
                        });
                    }
                }*/

                setTimeout(function(){ $("#lc_chat_layout").hide() }, 500);
                setTimeout(function(){ $("#lc_chat_layout").hide() }, 1000);
                setTimeout(function(){ $("#lc_chat_layout").hide() }, 3000);

            });

            // Submenu support ******************************
            (function($){
                $(document).ready(function(){
                    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        $(this).parent().siblings().removeClass('open');
                        $(this).parent().toggleClass('open');
                    });
                });
            })(jQuery);
            // ************************************************


            var cat_abierta=0;
            function abrircategorias(cat,mnu,mnu_hdr,icn_mnu){
                if (typeof(icn_mnu) != "undefined") {
                    path_string = "../utilerias/img_cat/";
                    icon_string = icn_mnu.substring(8);
                    full_path = path_string + icon_string + "_n.png";
                    $(icn_mnu).attr("src",full_path);
                }

                // Close all menus and categorias
                $(".categorias").css("height","25");
                $(".menu").css("display","none");

                if(cat_abierta!=cat){

                  // Open categoria
                  $(cat).css("height","100%");
                  cat_abierta = cat;

                  // Open menu
                  $(mnu).fadeIn(200);

                  if (typeof(icn_mnu) != "undefined") {
                      $(".ico_mnus").each(function(index, value){
                          match_string = "#" + $(this).attr('id');
                          if (match_string != icn_mnu) {
                              icon_file_name = value.src.substring(value.src.lastIndexOf("/")+1);
                              if (icon_file_name.lastIndexOf("_n") != -1) {
                                  new_path = path_string + $(this).attr('id').substring(7) + ".png";
                                  $(this).attr('src',new_path);
                              }
                          }
                      });
                  }
               } else {
                  cat_abierta = 0;
               }// if(cat_abierta==cat)
            }


            function hijos(divhijo,imagen){
                if($(divhijo).css("display")=="none"){
                    $(divhijo).fadeIn(200);
                    $(imagen).attr("src","../design/<?php echo $strGNetwarlogCSS;?>/menos.png");
                } else {
                    $(divhijo).fadeOut(200);
                    $(imagen).attr("src","../design/<?php echo $strGNetwarlogCSS;?>/mas.png");
                }
            }


			var agregatab_interval;
            function agregatab_continue(url,titulo,imagen,id)
            {
                $("#frmmsgbox").modal('hide');
                agregatab_interval = setInterval(function() {
                	agregatab(url, titulo, imagen, id);
                	clearInterval(agregatab_interval);
              	}, 1500);
            }





            function agregatab(url,titulo,imagen,id)
            {

            	//$(".nmcategory").removeClass("open");
            	//alert("revisar");
            	//$(".nmcategory").display('none');
            	//$('.dropdown.open .dropdown-toogle').dropdown('toggle');
            	//$('[data-toggle="dropdown"]').parent().removeClass('open');
                //alert(titulo);

                $("#divloading").fadeIn("slow");
                if($(".navbar-toggle").css("display")!="none"){ $(".navbar-toggle").click(); } //collapse menu responsive

                //Manda la pagina al top ...
                $('html').animate({scrollTop:0}, 'fast');
                $('body').animate({scrollTop:0}, 'fast');

                //Checa si el tab no esta abierto ya....
                var i = 0;
                for(i=0;i<=ntab;i++){
                    if(tabs[i]==id){
                        clictab("tb"+id);
                		$("#divloading").fadeOut("slow"); // hide loading
                        return;
                    }
                }


                if(ntab==20)
				{
                	$("#divloading").fadeOut(100);
					msg  = "Debe cerrar alguna de las pestañas abiertas antes de poder abrir otra. <br>";

				for(i=1;i<=ntab;i++)
					{
						tab_title = $("span[name='tb"+tabs[i]+"']").text();
						msg += "<span id='tb"+tabs[i]+"_list'><br><i id=\"btnclose\" class=\"fa fa-times\" ";
						msg += " onclick=\"quitartab_list('tb"+tabs[i]+"',"+tabs[i]+",'"+tab_title+"');\" ";
						msg += " title=\"Cerrar pestaña.\"></i>";
						msg += " "+tab_title;
						msg += "</span>";
					}

					msg += "<br><br>NOTA: Solo puede tener 5 pestañas abiertas a la vez.";

					msg +="<p align='right'>";
					msg +="<button data-dismiss='modal' class='btn btn-default'>Cancelar</button>&nbsp;";
					msg +="<button onclick=\"agregatab_continue('"+url+"','"+titulo+"','"+imagen+"','"+id+"');\" ";
					msg +=" data-dismiss='modal' class='btn btn-primary'>Continuar";
					msg +="</button></p>";


					msgbox(msg, "warning");
					return;
				}


                if(url.indexOf("repolog.php?")>0){
                    $('#nmloader_div').show();
                };

                //Añade el nuevo tab a la lista de tabs...
                ntab+=1;
                tabs[ntab]=id;

                $(".contmenu").fadeOut(200);
                lineaimagen="";
                if(imagen!=""){
                    lineaimagen="<img src='"+imagen+"' >";
                }

				unselect_tab();

				// Adding tab ...
                var ntd = "";

				// drag and drop
                ntd+="<td class='tdtab  tab_selected'  id='tb"+id+"-1' tabid='tb"+id+"' ";
                ntd+=" ondragstart='handleDragStart(event)' ";
                ntd+=" ondragenter='handleDragEnter(event)' ";
                ntd+=" ondragover='handleDragOver(event)' ";
                ntd+=" ondragleave='handleDragLeave(event)' ";
                ntd+=" ondrop='handleDrop(event)' ";
                ntd+=" ondragend='handleDragEnd(event)' ";
                ntd+=" onclick='clictab(this.getAttribute(\"tabid\"));' ";
                ntd+=" draggable=true >";
                ntd+="<span name='tb"+id+"'>&nbsp; "+titulo+" </span>";
				ntd+="<i id='btnclose' class='fa fa-times' ";
				ntd+=" onclick='quitartab(\"tb"+id+"\","+id+",\""+titulo+"\")' ";
				ntd+=" title='Cerrar pestaña.' ";
				ntd+="></i>&nbsp;";
				ntd+="</td>";

				// Separator tab ...
                //ntd+="<td id='tb"+id+"-s' style='white-space:nowrap;width: 2px;'></td>";

                $("#filatabs").append(ntd);

                tabseleccionado = "tb"+id;

                //Get absolute URL
                var url_vinculo = document.createElement("a");
                url_vinculo.href = url;
                url = url_vinculo.href;

                //Enviando al control de sesiones
                sp_c(tabseleccionado,url);

                //CARGANDO TD PARA URL
                $(".tdurl").css("display","none");
                var ntdu = "";
                ntdu="<td id='tb"+id+"-u'  class='tdurl'>";
                ntdu+="<iframe id='frurl' frameborder=0 class='frurl' src='"+url+"'></iframe>";
                ntdu+="</td>";
                $("#filaurls").append(ntdu);

                if($(".navbar-toggle").css("display")!="none"){ $(".navbar-toggle").click(); }

                $("#tb"+id+"-u").ready(function() {
                	$("#divloading").fadeOut("slow");
                });

            }

            function sp_c(d,url){

                url_vinculo = url;

                //document.getElementById("divmsg").innerHTML=sp+d;
                html= $.ajax({
                    type: "POST",
                    url: 'sp_c.php',
                    data: {
                        p:sp+d,
                        o:"<?php echo $org; ?>",
                        n:"<?php echo $nombre_org; ?>",
                        l:"<?php echo $login; ?>",
                        e:"<?php echo $idempleado; ?>",
                        per:"<?php echo $idperfil; ?>",
                        cio:"<?php echo $campo_idorganizacion; ?>",
                        url_vinc: url_vinculo
                    },
                    async: false
                }).responseText;
                if(html!=""){
                    if(html=="salir"){
                        document.location="index.php";
                    }
                }
            }

            function utab(cual){
                var tab = "#"+cual+"-4";
                if(tabseleccionado==cual){
                    //$(tab).css("background-color","#98ac31");
                    $(tab).css("background-image","url('../design/<?php echo $strGNetwarlogCSS;?>/cerrar_tab.png')");
                    $(tab).css("background-position-x","center");
                    $(tab).css("background-position-y","center");
                    $(tab).css("background-position","center center");
                    $(tab).css("background-repeat","no-repeat");
                } else {
                    //$(tab).css("background-color","#98ac31");
                    $(tab).css("background-image","url('../design/<?php echo $strGNetwarlogCSS;?>/cerrar_tab.png')");
                    $(tab).css("background-position-x","center");
                    $(tab).css("background-position-y","center");
                    $(tab).css("background-position","center center");
                    $(tab).css("background-repeat","no-repeat");
                }
            }

            function otab(cual){
                var tab = "#"+cual+"-4";
                if(tabseleccionado==cual){
//        $(tab).css("background-color","#98ac31");
                    $(tab).css("background-image","none");
                } else {
//        $(tab).css("background-color","#98ac31");
                    $(tab).css("background-image","none");
                }
            }

			function unselect_tab(){
                $(".tdurl").css("display","none");
                $(".tdtab1").css("background-image","none");
	        	$(".tdtab").removeClass("tab_selected");
            	//$(".tdtab2").removeClass("tab_selected");
			}


            function clictab(cual){

                //if(tabseleccionado == cual) return;
                //alert(cual);

                sp_c(cual);
                unselect_tab();

				$("#"+cual+"-1").addClass("tab_selected");
				//$("#"+cual+"-2").addClass("tab_selected");

                // show frame ...
                $("#"+cual+"-u").fadeIn(200);
                tabseleccionado = cual;

            }


            var preguntar = true;
            function quitartab(cual,id,nombre){
                var resp = true;
                if(preguntar){
                    resp = confirm("Haga clic en aceptar para cerrar: [ "+nombre+" ]");
                }
                if(resp){
                    $("#"+cual+"-1").remove();
                    $("#"+cual+"-u").remove();
                    /*$("#"+cual+"-2").remove();
                    $("#"+cual+"-3").remove();
                    $("#"+cual+"-4").remove();
                    $("#"+cual+"-s").remove();*/

                    var i = 0;
                    for(i=0;i<=ntab;i++){
                        if(tabs[i]==id){
                            tabs.splice(i,1);
                            ntab=ntab-1;
                            break;
                        }
                    }

                    if(preguntar){
                        // If tabselected is, the software will open next tab ...
                        if(tabseleccionado==cual){
	                        for(i=ntab;i>=0;i--){
	                        	if(tabs[i]){
	                        		tab_to_open = "tb"+tabs[i];
	                        		clictab(tab_to_open);
	                            	break;
	                            }
	                        }
                        }
                    }

                }
                $("#divloading_tab").fadeOut("fast");
                $("#divloading").fadeOut("fast");
            }
			function quitartab_list(cual, id, nombre)
			{
				preguntar = false;
				quitartab(cual,id,nombre);
				$("#"+cual+"_list").fadeOut('fast');
				preguntar = true;
			}


            function mostrarcontenido(divcontenido){
                if($(divcontenido).css("display")=="none"){
                    $(".contmenu").fadeOut(200);
                    $(divcontenido).fadeIn(200);
                }
            }

            function closetabs(){
                resp = confirm("¿Desea cerrar todas las pestañas?");
            	if(!resp) return;

                preguntar=false;
                var i = 0;
                for(i=ntab;i>=0;i--){
                    quitartab("tb"+tabs[i]);
                }
                tabs = new Array();
                ntab = 0;
                preguntar=true;
            }

            function salir(){
                preguntar=false;
                var i = 0;
                for(i=ntab;i>=0;i--){
                    quitartab("tb"+tabs[i]);
                }
                document.location="salir.php";
            }

            function modificarclave(){
                agregatab("cambiarclave.php","Cambiar Clave","",-100);
            }

            function miPerfil(){
                agregatab("../../modulos/perfil/index.php?c=index&f=principal", "Mi perfil", "", -100);
            }


            // Agregando soporte para el cambio de tab

            $(window).focus(function(){
                regresa_sesion_tab();
            });
            function regresa_sesion_tab(){
                var h = new Date();
                var hora = h.getHours()+":"+h.getMinutes()+":"+h.getSeconds();
                //console.log(">>> "+tabseleccionado+"  "+hora);
                sp_c(tabseleccionado);
            }


            //EVITAR QUE SE CIERRE LA PAGINA
            window.onbeforeunload = function(e) {
                if(pregunta){
                    return "Favor de confirmar la salida, Gracias por utilizar nuestros sistemas.\n\NetwareMonitor.";
                }
            };


            /*------------------------------------------------------------*/
			// drag and drop ...
			var tab_destiny;
			function handleDragStart(e) {
				//e.target.style.opacity = "0.4"; };
				e.dataTransfer.effectAllowed = 'move';
				e.dataTransfer.setData('text/html', this.innerHTML);
			};
			function handleDragOver(e)
			{
				if(e.preventDefault)
				{
					e.preventDefault();
				}
				e.dataTransfer.dropEffect = 'move';
				return false;
			}
			function handleDragEnter(e)
			{
				//console.log(e);
				if(e.target.localName=="td"){
					$("#"+e.target.id).addClass("over_drag");
					tab_destiny = e.target.id;
				}
			}
			function handleDragLeave(e){
				$("#"+e.target.id).removeClass("over_drag");
				//e.target.style.border = "none";
			}
			function handleDrop(e)
			{
				if(e.stopPropagation)
				{
					e.stopPropagation();
				}

			}
			function handleDragEnd(e)
			{

				var tdtabs = document.querySelectorAll('.tdtab');
				[].forEach.call(tdtabs, function (tab) {
					$("#"+tab.id).removeClass("over_drag");
				});

				if(tab_destiny=="") return;

				var tab_source = e.target.id;
				//alert(" Origen: "+tab_source+" Destino: "+tab_destiny);

				// changing tabs ...
				var tab_temp = $("#"+tab_destiny).html();
				$("#"+tab_destiny).html($("#"+tab_source).html());
				$("#"+tab_source).html(tab_temp);


				// updating control tabs[] ...
   				var i_source;
   				var i_destiny;

				var id_source = tab_source.substring(2, tab_source.indexOf("-"));
				var id_destiny = tab_destiny.substring(2, tab_destiny.indexOf("-"));

   				for(i=0;i<=ntab;i++){
   	   				if(tabs[i]==id_source){
   	   	   				i_source=i;
   	   	   			}
   	   	   			if(tabs[i]==id_destiny){
   	   	   	   			i_destiny=i;
   	   	   	   		}
   	   			}
				tabs[i_source] = id_destiny;
				tabs[i_destiny] = id_source;


				// updating ...
				$("#"+tab_source).attr("id","tab_temp");
				$("#"+tab_destiny).attr("id",tab_source);
				$("#tab_temp").attr("id",tab_destiny);

				$("#"+tab_source).attr("tabid","tb"+id_source);
				$("#"+tab_destiny).attr("tabid","tb"+id_destiny);

				clictab(tabseleccionado);
			}

            /*-End: drag and drop ----------------------------------------*/




        </script>

        <style type="text/css">
            #bs-example-navbar-collapse-9 > .navbar-right{
                margin-right: unset !important;
            }
        </style>

    </head>

    <body>

    	<div id="divloading">
    		<font face="arial" size="4" color="white">
    			<br><i class="fa fa-refresh fa-spin fa-5x"></i>
    			<br><br>
    			Espere un momento ... <!-- , por favor ...-->
    		</font>
    	</div>

    	<div id="divloading_tab">
    		<font face="arial" size="4" color="gray">
    			<i class="fa fa-refresh fa-spin fa-4x"></i>
    		</font>
    	</div>



    	<!-- Begin: TOP BAR -->
		<nav id="nmtopbar" class="navbar navbar-inverse navbar-static-top marginBottom-0" role="navigation">
			<!--<div class="container-fluid"> -->

				<!-- Begin: Button responsive -->
				<div class="navbar-header">
					<button style="background-color: transparent;"
						type="button" class="navbar-toggle collapsed"
						data-toggle="collapse"
						data-target="#bs-example-navbar-collapse-9"
						aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a id="brand-button" class="navbar-brand">
						<img src="img/logonm_figura.png">
						<!--  span id="tdhome" class="glyphicon glyphicon-home" aria-hidden="true"></span>	 -->
					</a>
				</div>
				<!-- End: Button responsive -->

				<div style="" aria-expanded="false"
					 class="navbar-collapse collapse"
					 id="bs-example-navbar-collapse-9">

					<ul class="nav navbar-nav navbar-left">
						<!-- *************** Begin:NEW MENU ****************** -->

                   	<?php
                        //if($_SESSION["estatus_cobranza"] != 2){
                            //Actualizando Datos Contextuales:
                            include "actualizardatos.php";

                           	//Loading icons:
                            include "icons.php";

                            $sql = "select idcategoria, nombre,icono from accelog_categorias order by orden;";
                            $result = $conexion->consultar($sql);

                            $inicio = 1;
                            $altura="";
                            $icono = "";
                            $display="block";
                            $ultimacategoria="";
                            $ocultacategoriassinmenus="";
                            $menusporomision = array();

                            $menustofind = "";
                            while($rs = $conexion->siguiente($result)){

                                $altura="height:100%;";
                                if($inicio==1){
                                    $inicio=2;
                                } else {
                                    $display="none";
                                }


                                /**
                                 *  - Se cargan antes los menus para saber si la categoria tiene menus.
                                 *  - Aquí arranca la carga de submenus de la categoria.
                                 */
                                $menus = new arbolmenu($rs{"idcategoria"},$conexion,$menus_permitidos);

                                //Cargando arreglo de menus por omision
                                $menus->setMenuOmision($menusporomision);

                                //Esta funcion se mandara llamar de forma recursiva...
                                $menus->menustofind_cat = $rs{"nombre"};
                                $susmenus=$menus->construyemenus_bootstrap(0, $rs{"nombre"});
                                $menustofind.= $menus->menustofind;
                                //error_log($menustofind);

                                //Se obtienen los menus por omision
                                $menusporomision=$menus->regresamenusomision();

                                //echo count($menusporomision);
                                $abrirporomision="";
                                for($menusom=0;$menusom<=count($menusporomision)-1;$menusom++){
                                    if($abrirporomision!="") $abrirporomision.=" or ";
                                    $abrirporomision.=" idmenu=".$menusporomision[$menusom];
                                }
                                //echo "menus por omision:".$abrirporomision;

                                if($susmenus!=""){

                                	?>

        								<li class="dropdown nmcategory">
                  						<a href="#" class="nmmenu_main dropdown-toggle" data-toggle="dropdown"
                  							role="button" aria-haspopup="true"
                  							aria-expanded="false">
                  							<?php
                  								$label = "<i class='fa fa-".$icons[$rs{"idcategoria"}]." fa-lg' ";
                  								$label.= " title='".$rs{"nombre"}."'></i>";
                  								$label.= " <span class='lbl_menu'>".$rs{"nombre"}."</span>";
                  								echo $label;
                  							?>
                  						</a>
                  						<ul class="dropdown-menu">
                  							<?php echo $susmenus; ?>
                  						</ul>

                						</li>
                                	<?php

                                    $ultimacategoria="abrircategorias('#cat".$rs{"idcategoria"}."','#mnu".$rs{"idcategoria"}."');";
                                }
                            }
                            $conexion->cerrar_consulta($result);
                        /*}else{
                            echo "  <script>
                                        var cobranza = true;
                                        var tipo_usuario = '". $_SESSION["accelog_idperfil"] ."';
                                    </script>";
                        }*/
                    ?>

					</ul>
					<!-- End: menus -->


					<!-- Begin: Opciones Generales -->
					<ul class="nav navbar-nav navbar-right">

						<!-- Begin: Find menu -->
						<li class="dropdown">
							<!--  a id="btnfindmenu" href="#" data-toggle="modal" data-target="#frmfindmenu" -->
							<a id="btnfindmenu" href="javascript:open_frmfindmenu()">
								 <i class="fa fa-search fa-lg" title='Buscar menú ...'></i>
								 <span class="lbl_menu">&nbsp;Buscar menú ...</span>
							</a>
						</li>
						<!-- End: Find menu -->



				    	<!-- Technical Support -->
          <li class="dropdown">
					<!--	<a href="javascript:$('#chatImageSpan a').click();"
						   title="Soporte técnico o consultoría">
						   <i class="fa fa-question-circle fa-lg">-->
             <a onclick="chat()"  title="Soporte técnico o consultoría">
             <i class="fa fa-question-circle fa-lg"></i>
             <span class='lbl_menu'>&nbsp;Soporte Técnico</span>

							<span id="chatImageSpan">
							</span>
							<div  id="sysaidChatInc">
              </div>


              <script type='text/javascript'>
              var fc_CSS=document.createElement('link');
              fc_CSS.setAttribute('rel','stylesheet');
              var fc_isSecured = (window.location && window.location.protocol == 'https:');
              var fc_lang = document.getElementsByTagName('html')[0].getAttribute('lang');
              var fc_rtlLanguages = ['ar','he']; var fc_rtlSuffix = (fc_rtlLanguages.indexOf(fc_lang) >= 0) ? '-rtl' : '';
              fc_CSS.setAttribute('type','text/css');
              fc_CSS.setAttribute('href',((fc_isSecured)? 'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets1.chat.freshdesk.com')+'/css/visitor'+fc_rtlSuffix+'.css');
              document.getElementsByTagName('head')[0].appendChild(fc_CSS);
              var fc_JS=document.createElement('script');
              fc_JS.type='text/javascript';
              fc_JS.defer=true;fc_JS.src=((fc_isSecured)?'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets.chat.freshdesk.com')+'/js/visitor.js';
              (document.body?document.body:document.getElementsByTagName('head')[0]).appendChild(fc_JS);window.livechat_setting= 'eyJ3aWRnZXRfc2l0ZV91cmwiOiJuZXR3YXJtb25pdG9yLmZyZXNoZGVzay5jb20iLCJwcm9kdWN0X2lkIjpudWxsLCJuYW1lIjoiTmV0d2FybW9uaXRvciIsIndpZGdldF9leHRlcm5hbF9pZCI6bnVsbCwid2lkZ2V0X2lkIjoiODk3MjVhMjQtZDk1MS00M2Q0LTgzN2UtZmQ5M2NjMWQzZTdjIiwic2hvd19vbl9wb3J0YWwiOmZhbHNlLCJwb3J0YWxfbG9naW5fcmVxdWlyZWQiOmZhbHNlLCJsYW5ndWFnZSI6bnVsbCwidGltZXpvbmUiOm51bGwsImlkIjo5MDAwMDMyNjYzLCJtYWluX3dpZGdldCI6MSwiZmNfaWQiOiI0MDA4MWE4MTI0MzdiZmQzMWU2NTBlYmNhNjY1ZTNhZiIsInNob3ciOjEsInJlcXVpcmVkIjoyLCJoZWxwZGVza25hbWUiOiJOZXR3YXJtb25pdG9yIiwibmFtZV9sYWJlbCI6Ik5vbWJyZSIsIm1lc3NhZ2VfbGFiZWwiOiJNZW5zYWplIiwicGhvbmVfbGFiZWwiOiJUZWzDqWZvbm8iLCJ0ZXh0ZmllbGRfbGFiZWwiOiJDYW1wbyBkZSB0ZXh0byIsImRyb3Bkb3duX2xhYmVsIjoiTWVuw7ogZGVzcGxlZ2FibGUiLCJ3ZWJ1cmwiOiJuZXR3YXJtb25pdG9yLmZyZXNoZGVzay5jb20iLCJub2RldXJsIjoiY2hhdC5mcmVzaGRlc2suY29tIiwiZGVidWciOjEsIm1lIjoiWW8iLCJleHBpcnkiOjE0ODkwODEyNDQwMDAsImVudmlyb25tZW50IjoicHJvZHVjdGlvbiIsImVuZF9jaGF0X3RoYW5rX21zZyI6IsKhR3JhY2lhcyEiLCJlbmRfY2hhdF9lbmRfdGl0bGUiOiJGaW5hbGl6YWNpw7NuIiwiZW5kX2NoYXRfY2FuY2VsX3RpdGxlIjoiQ2FuY2VsYXIiLCJzaXRlX2lkIjoiNDAwODFhODEyNDM3YmZkMzFlNjUwZWJjYTY2NWUzYWYiLCJhY3RpdmUiOjEsInJvdXRpbmciOm51bGwsInByZWNoYXRfZm9ybSI6MSwiYnVzaW5lc3NfY2FsZW5kYXIiOm51bGwsInByb2FjdGl2ZV9jaGF0IjowLCJwcm9hY3RpdmVfdGltZSI6MTUsInNpdGVfdXJsIjoibmV0d2FybW9uaXRvci5mcmVzaGRlc2suY29tIiwiZXh0ZXJuYWxfaWQiOm51bGwsImRlbGV0ZWQiOjAsIm1vYmlsZSI6MSwiYWNjb3VudF9pZCI6bnVsbCwiY3JlYXRlZF9hdCI6IjIwMTYtMDEtMTRUMjI6NDk6MzUuMDAwWiIsInVwZGF0ZWRfYXQiOiIyMDE3LTAyLTA4VDIzOjE2OjE0LjAwMFoiLCJjYkRlZmF1bHRNZXNzYWdlcyI6eyJjb2Jyb3dzaW5nX3N0YXJ0X21zZyI6IllvdXIgc2NyZWVuc2hhcmUgc2Vzc2lvbiBoYXMgc3RhcnRlZCIsImNvYnJvd3Npbmdfc3RvcF9tc2ciOiJZb3VyIHNjcmVlbnNoYXJpbmcgc2Vzc2lvbiBoYXMgZW5kZWQiLCJjb2Jyb3dzaW5nX2RlbnlfbXNnIjoiWW91ciByZXF1ZXN0IHdhcyBkZWNsaW5lZCIsImNvYnJvd3NpbmdfYWdlbnRfYnVzeSI6IkFnZW50IGlzIGluIHNjcmVlbiBzaGFyZSBzZXNzaW9uIHdpdGggY3VzdG9tZXIiLCJjb2Jyb3dzaW5nX3ZpZXdpbmdfc2NyZWVuIjoiWW91IGFyZSB2aWV3aW5nIHRoZSB2aXNpdG9y4oCZcyBzY3JlZW4gIiwiY29icm93c2luZ19jb250cm9sbGluZ19zY3JlZW4iOiJZb3UgaGF2ZSBhY2Nlc3MgdG8gdmlzaXRvcuKAmXMgc2NyZWVuICIsImNvYnJvd3NpbmdfcmVxdWVzdF9jb250cm9sIjoiUmVxdWVzdCB2aXNpdG9yIGZvciBzY3JlZW4gYWNjZXNzICIsImNvYnJvd3NpbmdfZ2l2ZV92aXNpdG9yX2NvbnRyb2wiOiJHaXZlIGFjY2VzcyBiYWNrIHRvIHZpc2l0b3IgIiwiY29icm93c2luZ19zdG9wX3JlcXVlc3QiOiJFbmQgeW91ciBzY3JlZW5zaGFyaW5nIHNlc3Npb24iLCJjb2Jyb3dzaW5nX3JlcXVlc3RfY29udHJvbF9yZWplY3RlZCI6IllvdXIgcmVxdWVzdCB3YXMgZGVjbGluZWQiLCJjb2Jyb3dzaW5nX2NhbmNlbF92aXNpdG9yX21zZyI6IlNjcmVlbnNoYXJpbmcgaXMgY3VycmVudGx5IHVuYXZhaWxhYmxlIiwiY29icm93c2luZ19hZ2VudF9yZXF1ZXN0X2NvbnRyb2wiOiJBZ2VudCBpcyByZXF1ZXN0aW5nIGFjY2VzcyB0byB5b3VyIHNjcmVlbiIsImNiX3ZpZXdpbmdfc2NyZWVuX3ZpIjoiQWdlbnQgY2FuIHZpZXcgeW91ciBzY3JlZW4gIiwiY2JfY29udHJvbGxpbmdfc2NyZWVuX3ZpIjoiQWdlbnQgaGFzIGFjY2VzcyB0byB5b3VyIHNjcmVlbiAiLCJjYl92aWV3X21vZGVfc3VidGV4dCI6IllvdXIgYWNjZXNzIHRvIHRoZSBzY3JlZW4gaGFzIGJlZW4gd2l0aGRyYXduICIsImNiX2dpdmVfY29udHJvbF92aSI6IkFsbG93IGFnZW50IHRvIGFjY2VzcyB5b3VyIHNjcmVlbiAiLCJjYl92aXNpdG9yX3Nlc3Npb25fcmVxdWVzdCI6IkFnZW50IHNlZWtzIGFjY2VzcyB0byB5b3VyIHNjcmVlbiAifX0=';
              //var chattt = $("#lc_chat_layout").length;


              </script>

                          <script type="text/javascript">
                        		var txt_for_chat = "";
                        		txt_for_chat = "<i class='fa fa-question-circle fa-lg'></i>";
                        		txt_for_chat+= "<span class='lbl_menu'>&nbsp;Soporte Técnico</span>";
    					              $("#chatImageSpan a").html("");
                      	  </script>
            </a>
						</li>


                    	<li class="dropdown nmmenu_main_profile_li">
				          <a id="nmmenu_main_profile_a" href="#"
				          	 style="padding:0px;"
				          	 class="dropdown-toggle" data-toggle="dropdown"
				             role="button" aria-haspopup="true" aria-expanded="false">


 							 <table><tbody><tr>

 							 <td class="nmmenu_main_profile_td">
						     <?php

		                            //Colocando logo de perfil
		 							$there_is_photo = false;
		 							//echo $foto;
		                            if($foto!=""){
		                            	$filename = "../archivos/1/administracion_usuarios/".$foto;
		                            	if(file_exists($filename)){
		                            		$there_is_photo = true;
		                            	}
		                            }
		                            if(!$there_is_photo){
			                            $filename = "../archivos/1/organizaciones/$logoempresa";
			                            error_log($filename);
			                            if(!file_exists($filename)){
			                                $filename = "../archivos/1/administracion_usuarios/$logoempresa";
			                                if(!file_exists($filename)){
			                                    $filename = "../archivos/1/organizaciones/x.png";
			                                } else {
			                                	$there_is_photo = true;
			                                }
			                            } else {
			                            	$there_is_photo = true;
			                            }
		                            }

		                            if($there_is_photo){
		                            	?>
		                            	 	<img
		                            	 		src="<?php echo $filename; ?>"
		                            	 		class="nmavatar img-thumbnail"
		                            	 		align="middle">
		                            	<?php
		                            }

					      	?>
					      	</td>
	 						<td class="nmmenu_main_profile_td">
 							 	<!-- span style="font-size:8pt;" -->
 							 	<?php if($nombre==""){ ?>
 									<b><?php echo $login; ?></b>
 								<?php } else { ?>
 									<b><?php echo $nombre; ?></b>
 								<?php } ?>
 								<?php if($puesto!=""){ echo "<br>".$puesto; } ?>
 								<!-- /span-->
 								<span class="caret"></span>
					         </td>

					      	</tr></tbody></table>
				          </a>
				          <ul class="dropdown-menu">
                            <?php

                                if(!isset($_SESSION)) session_start();
                                if($_SESSION["accelog_idperfil"] == "(5)" || $_SESSION["accelog_idperfil"] == "(3)" || $_SESSION["accelog_idperfil"] == "(2)"){
                                    echo '
                                        <li><a href="javascript:miPerfil();"><i class="fa fa-user fa-lg"></i> Mi perfil</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="javascript:salir();"><i class="fa fa-sign-out fa-lg"></i> Salir</a></li>
                                    ';
                                } else {
                                    echo '
                                        <li><a href="javascript:modificarclave();"><i class="fa fa-lock fa-lg"></i> Cambiar contraseña</a></li>
                                        <li><a href="javascript:closetabs();"><i class="fa fa-times fa-lg"></i> Cerrar pestañas</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="javascript:salir();"><i class="fa fa-sign-out fa-lg"></i> Salir</a></li>
                                    ';
                                }

                            ?>
				          </ul>
				        </li>

					</ul>
					<!-- End: Opciones generales -->

					<!-- *************** End:NEW MENU ****************** -->


				</div>
			<!-- </div> container-->
		</nav>
    	<!-- End: TOP BAR -->

	<!-- Begin: TABS -->
    <div id="tabs">

    	<script>
    		var left=0;
    		var move_tabs_interval_left;
    		var move_tabs_interval_right;
			function move_left()
			{
				//move_tabs_interval_left = setInterval(function() {
				tdwidth = $("#nmtable_filatabs td:last").css("width").replace('px','');
				tablewidth = $("#nmtable_filatabs").css("width").replace('px','');
				dif = tdwidth - tablewidth;

				if(dif > left) return;

				left -= 45;
				$("#nmtable_filatabs").css("left",left+"px");
				//}, 70);
			}
			function move_right()
			{
				//move_tabs_interval_right = setInterval(function() {
				if(left>=0) return;
				left += 45;
				$("#nmtable_filatabs").css("left",left+"px");
				//}, 70);
			}
			function mouse_up()
			{
				clearInterval(move_tabs_interval_left);
				clearInterval(move_tabs_interval_right);
			}
    	</script>

  		<div id="nmdiv_optionstabs" class="btn-group" role="group">
			<button id="btnleft"  class="btn btn-link" onmousedown="move_left();"  onmouseup="mouse_up();"><i class='fa fa-chevron-left'></i></button>
			<button id="btnright" class="btn btn-link" onmousedown="move_right();" onmouseup="mouse_up();"><i class='fa fa-chevron-right'></i></button>
			<button class="btn btn-link" onmousedown="closetabs();"  onmouseup="mouse_up();"><i class='fa fa-times fa-lg'></i></button>
		</div>
		<table id="nmtable_filatabs" cellspacing="0" cellpadding="0">
       		  <tr valign="middle" id="filatabs"></tr>
		</table>
 	</div>
	<!-- End: TABS -->


    <!--TABLA PRINCIPAL-->
    <!--  <table width="100%" height="100%" style="border-collapse: collapse; border-spacing: 0px; ">-->
    <!-- <table width="100%" height="100%" style="border:none"> -->
    <table width="100%" height="90%" style="border:none"> <!-- 88% because toolbar -->

        <!--Begin: ENCABEZADO-->
        <!--
        <tr>
            <th colspan="3">
                <table width="100%" style="border-collapse: collapse;">
                    <tbody>
                    <tr valign="middle" height="100%">
                        <td valign="center" align="left">
                            <?php
                            //Define Logo de la Empresa
                            /*
                            $filename = "../archivos/1/organizaciones/$logoempresa";
                            error_log($filename);
                            if(!file_exists($filename)){
                                $filename = "../archivos/1/administracion_usuarios/$logoempresa";
                                if(!file_exists($filename)){
                                    $filename = "../archivos/1/organizaciones/x.png";
                                }
                            }
                            */
                            ?>
                            <div class=" nmtopimages ">
                                <img src="<?php echo $filename; ?>" style="height: 55px;">
                            </div>
                        </td>
                        <td align="right">

                            <table>
                                <tbody>
                                <tr>

                                </tr>
                                </tbody>
                            </table>

                        </td>

                    </tr>

                    </tbody>
                </table>
            </th>
        </tr>
        <tr class=" nmtoolbar ">
            <th colspan="3">
                <table width="100%" cellpadding="0" cellspacing="0" height="100%">
                    <tbody>
                    <tr>
                        <td valign="middle" align="left" style=" width: 209px; text-align: center; ">
                            <img id="tdocultar" src="../design/<?php echo $strGNetwarlogCSS;?>/cerrar.png" class=" nmtoolbaricons " >
                            <img id="tdhome" src="../design/<?php echo $strGNetwarlogCSS;?>/home.png" class=" nmtoolbaricons " >
                            <img id="tdchpwd" onclick="modificarclave();" src="../design/<?php echo $strGNetwarlogCSS;?>/clave.png" class=" nmtoolbaricons " >
                            <img id="tdexit" onClick="salir()" src="../design/<?php echo $strGNetwarlogCSS;?>/logout.png" class=" nmtoolbaricons " >
                        </td>
                        <td class="nmtoolbartext" style="text-align: left; ">
                            <?php echo $empresa; ?>
                        </td>
                        <td class="nmtoolbartext" style="text-align: right; ">

                            <?php echo $nombre?>
                            &nbsp;
                            <?php echo $puesto?>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </th>
        </tr>
         -->
        <!--End: ENCABEZADO-->

        <tr height="100%">
            <!--CONTENIDO-->
            <td class="tdcontenido" valign="top">
                <table cellspacing="0" style="border-spacing: 0px; border-collapse: collapse; padding: 0px 0px 0px 0px; "
                	   cellpadding="0" height="100%" width="100%">
                    <tr>
                        <td>
                            <div id="contenido">
                                <table id="tblurls" cellspacing="0" cellpadding="0">
                                    <tr id="filaurls"></tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <!-- Agregando overlay de notificaciones -->
    <script language="javascript">
        function mostrar_notificaciones(){
            //alert(id+ne);
            $("#divcomp").fadeIn(500);
            $("#frmcomp").attr("src","../../modulos/notificaciones/notificaciones_mostrar.php");
        }

        function cerrardiv(){
            $("#divcomp").hide();
        }
    </script>
    <div id="divcomp"><iframe id='frmcomp'></iframe></div>
    <!-- Agregando overlay de notificaciones -->

    <div id="nmloader_div" class=" nmloader ">
        <div>
            Cargando ...
            <br />
            <img src="../design/<?php echo $strGNetwarlogCSS;?>/loader-32.gif" />
        </div>
    </div>








	<!--#### Begin: MENU BY DEFAULT ####-->
	<script type="text/javascript">
    	function abrirmenuporomision(){
        	<?php
            	//echo "alert('".$abrirporomision."');";
            	if($abrirporomision!="") {
                    	$sql = "select idmenu, url, nombre from accelog_menu where ".$abrirporomision;
                    	//echo "alert('".$sql."');";
                    	$result = $conexion->consultar($sql);
                    	while($rs=$conexion->siguiente($result)){
                        	$filename="../utilerias/img_mnu/".$rs{"idmenu"}.".png";
                        	echo " agregatab('".$rs{"url"}."','".$rs{"nombre"}."','".$filename."',".$rs{"idmenu"}.");";
                        	//echo "alert('"." agregatab('".$rs{"url"}."','".$rs{"nombre"}."','".$filename."',".$rs{"idmenu"}.");"."');";
                    	}
                    	$conexion->cerrar_consulta($result);
            	}
        	?>
    	}
	</script>
	<!--#### End: MENU BY DEFAULT ####-->




    </body>
</html>


<!-- Modal: Find Menu -->
<div class="modal fade" id="frmfindmenu" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Buscar menú ...</h4>
      </div>
      <div id="frmfindmenu_body" class="modal-body">
        <select id="cmbmenu" class="select2_cmbmenu" multiple="multiple">
        	<?php echo $menustofind; ?>
        </select>
      </div>
    </div>
  </div>
</div>


<script>
	function msgbox(msg, type, title)
	{
		if(msg=="") return;
		if(!msg) return;
		if(!type) type="";
		if(!title) title="Netwarmonitor";

		var imagen = "";
		switch(type)
		{
			case "info":
				imagen = "<i class='fa fa-info'></i> &nbsp; ";
				break;
			case "success":
				imagen = "<i class='fa fa-check'></i> &nbsp; ";
				break;
			case "warning":
				imagen = "<i class='fa fa-exclamation'></i> &nbsp; ";
				break;
			case "danger":
				imagen = "<i class='fa fa-times'></i> &nbsp; ";
				break;
		}

		$("#msgbox_alert_type").removeClass("alert-"+type);
		$("#msgbox_alert_type").removeClass("alert-success");
		$("#msgbox_alert_type").removeClass("alert-info");
		$("#msgbox_alert_type").removeClass("alert-warning");
		$("#msgbox_alert_type").removeClass("alert-danger");
		if(type!="")
		{
			$("#msgbox_alert_type").addClass("alert-"+type);
		}

		$("#msgbox_title").html("<b>"+title+"</b>");
		$("#msgbox_imagen").html(imagen);
		$("#msgbox_content").html(msg);
		$("#frmmsgbox").modal('show');
	}
</script>

<!-- SmallModal: frmmsgbox -->
<div id="frmmsgbox"
	class="modal fade"
	tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  	<div class="modal-dialog">
    	<div class="modal-content">
    		<div id="msgbox_alert_type" class="alert" style="margin-bottom:0px;">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    			<span aria-hidden="true">×</span></button>
    			<h4><span id="msgbox_imagen"></span> <span id="msgbox_title">Netwarmonitor</span></h4>
    			<span id="msgbox_content"></span>
    		</div>
    	</div>
  	</div>
</div>

<div id="notificaciones">
  <a role="button" onclick="mostrarNoticiasModal(1)"><i class="material-icons">notifications</i></a>
</div>

<section id="noticias" class="container">
  <a class="close-btn" onclick="mostrarNoticiasModal(0)" style="z-index:100;">
    <i id="cerrar-noticias" class="material-icons">close</i>
  </a>
  <input type="hidden" id="inst" value="">
  <input type="hidden" id="tipo" value="">
  <div class="row">
    <!-- Noticias -->
    <div class="col-md-8" id="noticias_container">
      <h2 style="color:white; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Últimas noticias</h2>
      <hr>
      <div class="col-md-12">
        <div id="lista-loading-noticias">
          <h4 class="white-text">Cargando...</h4>
        </div>
        <ul id="lista-noticias"></ul> 
      </div>    
    </div>
    <!-- Recordatorios -->
    <div class="col-md-4 hidden" id="recordatorios_container">
      <h2 style="color:white; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Recordatorios</h2>
      <hr>
      <div class="col-md-12">
        <div id="lista-loading-recordatorios">
          <h4 class="white-text">Cargando...</h4>
        </div>
        <ul id="lista-recordatorios"></ul>  
      </div>  
    </div>
  </div>
</section>
